<?php
session_start();
require_once __DIR__ . '/../lib.php';
$provider = get_provider();

// Check if we have the required parameters
if (!isset($_GET['code']) || !isset($_GET['state'])) {
  $_SESSION['error'] = 'OAuth callback missing required parameters';
  header('Location: /');
  exit;
}

// Verify the state parameter to prevent CSRF attacks
if (!isset($_SESSION['oauth_state']) || $_GET['state'] !== $_SESSION['oauth_state']) {
  $_SESSION['error'] = 'Invalid OAuth state parameter';
  header('Location: /');
  exit;
}

try {
  // Build token request parameters
  $tokenParams = [
    'code' => $_GET['code']
  ];
  // Include PKCE code_verifier if we used PKCE
  if (!empty($_SESSION['pkce_code_verifier'])) {
    $tokenParams['code_verifier'] = $_SESSION['pkce_code_verifier'];
  }
  // Exchange the authorization code for an access token (with PKCE if applicable)
  $accessToken = $provider->getAccessToken('authorization_code', $tokenParams);

  // Use provider resource owner (internally handles POST pattern)
  $resourceOwner = $provider->getResourceOwner($accessToken);
  $userData = $resourceOwner->toArray();
  if (!isset($userData['email']))
    throw new Exception('Invalid user info response: missing email');

  // Store user data in session if you want to cache it. Remember that session hijacking protection is disabled if you do this.
  // For production, consider storing minimal info in session and fetching fresh data as needed.
  // $_SESSION['user'] = $userData;

  // Store access token and expiration in session
  $_SESSION['access_token'] = $accessToken->getToken();
  $_SESSION['token_expires'] = $accessToken->getExpires();

  // Clean up OAuth session variables
  unset($_SESSION['oauth_state']);
  unset($_SESSION['oauth_provider']);
  unset($_SESSION['pkce_code_verifier']);

  // Get post-login return URL
  $returnUrl = $_GET['return_to'] ?? '/';

  header('Location: ' . $returnUrl);
  exit;
} catch (Exception $e) {
  $_SESSION['error'] = 'OAuth callback failed: ' . $e->getMessage();
  header('Location: /');
  exit;
}
