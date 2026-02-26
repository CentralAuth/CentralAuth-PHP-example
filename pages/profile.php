<?php
session_start();
require_once __DIR__ . '/../lib.php';
$user = get_user();

// Check if user is logged in
if (!$user) {
  header('Location: /?error=not_logged_in');
  exit;
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile - CentralAuth PHP Example</title>
  <link rel="stylesheet" href="/public/styles.css">
</head>

<body>
  <div class="container">
    <h1>User Profile</h1>

    <?php if (isset($_GET['success'])): ?>
      <div class="success">Successfully logged in!</div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
      <div class="error"><?= htmlspecialchars(get_error_message($_GET['error'])) ?></div>
    <?php endif; ?>

    <?php if ($user): ?>
      <div class="user-info">
        <?php if (!empty($user['gravatar'])): ?>
          <img src="<?= htmlspecialchars($user['gravatar']) ?>" alt="User Avatar">
        <?php endif; ?>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email'] ?? 'N/A') ?></p>
      </div>
    <?php else: ?>
      <p>You are not logged in.</p>
    <?php endif; ?>

    <div class="button-group">
      <a href="/" class="btn">Back to Home</a>
      <a href="/api/auth/logout" class="btn btn-danger">Logout</a>
    </div>
  </div>
</body>

</html>