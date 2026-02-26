<?php
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../lib.php';
$provider = get_provider();
$config = get_config();

try {
  // Generate a random state parameter for CSRF protection.
  $state = bin2hex(random_bytes(16));

  $authParams = [
    'state' => $state
  ];

  // Create a high-entropy code verifier (43-128 characters per RFC 7636)
  $randomBytes = random_bytes(64);
  $codeVerifier = rtrim(strtr(base64_encode($randomBytes), '+/', '-_'), '=');
  // Ensure length within 43-128
  $codeVerifier = substr($codeVerifier, 0, 128);
  if (strlen($codeVerifier) < 43) {
    // Pad if too short (unlikely with 64 random bytes)
    $codeVerifier = str_pad($codeVerifier, 43, 'A');
  }
  $_SESSION['pkce_code_verifier'] = $codeVerifier;

  $codeChallenge = rtrim(strtr(base64_encode(hash('sha256', $codeVerifier, true)), '+/', '-_'), '=');
  $authParams['code_challenge_method'] = 'S256';
  $authParams['code_challenge'] = $codeChallenge;

  // Construct absolute return_to URL
  $forwardedProto = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '';
  $forwardedSsl = $_SERVER['HTTP_X_FORWARDED_SSL'] ?? '';
  $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || strtolower($forwardedProto) === 'https'
    || strtolower($forwardedSsl) === 'on';
  $scheme = $isHttps ? 'https' : 'http';
  $host = $_SERVER['HTTP_HOST'];
  $baseUrl = $scheme . '://' . $host;
  $returnTo = $_GET['return_to'] ?? '/profile';
  if (!filter_var($returnTo, FILTER_VALIDATE_URL)) {
    // If it's not already a full URL, prepend the base URL
    $returnTo = $baseUrl . (strpos($returnTo, '/') === 0 ? $returnTo : '/' . $returnTo);
  }
  $authParams['redirect_uri'] = $config['redirect_uri'] . '?return_to=' . urlencode($returnTo);

  // Optional: Add custom translations (example)
  // $translations = [
  //   'loginPageIntro' => 'Welcome to my website!',
  // ];
  // $authParams['translations'] = base64_encode(json_encode($translations));

  // Get the authorization URL (with PKCE params if enabled)
  $authorizationUrl = $provider->getAuthorizationUrl($authParams);

  // Redirect to CentralAuth OAuth provider
  header('Location: ' . $authorizationUrl);
  exit;
} catch (Exception $e) {
  $_SESSION['error'] = 'OAuth initialization failed: ' . $e->getMessage();
  header('Location: /');
  exit;
}
