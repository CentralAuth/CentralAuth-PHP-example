<?php
session_start();
require_once 'lib.php';
$user = get_user();
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CentralAuth PHP Example</title>
  <link rel="stylesheet" href="/public/styles.css">
</head>

<body>
  <div class="container">
    <h1>CentralAuth PHP Example</h1>
    <p>Welcome to the CentralAuth integration example using PHP!</p>

    <?php if (isset($_GET['error'])): ?>
      <div class="error"><?= htmlspecialchars(get_error_message($_GET['error'])) ?></div>
    <?php endif; ?>

    <div id="auth-section">
      <a href="/api/auth/login" class="btn">Login with CentralAuth</a>
      <a href="/profile" class="btn btn-success">View Profile</a>
      <a href="/api/auth/logout" class="btn btn-danger">Logout</a>
    </div>
  </div>
</body>

</html>