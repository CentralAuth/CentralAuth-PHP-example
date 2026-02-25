<?php
// Simple PHP router for clean URLs when .htaccess/nginx rewrites aren't available
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$routes = [
  '/api/auth/login' => 'login.php',
  '/api/auth/logout' => 'logout.php',
  '/api/auth/callback' => 'callback.php',
  '/profile' => 'profile.php',
];

// Remove trailing slash for matching
$uri = rtrim($uri, '/');

if (isset($routes[$uri])) {
  require __DIR__ . '/' . $routes[$uri];
  exit;
}
