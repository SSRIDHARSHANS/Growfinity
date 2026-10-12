<?php
// Set $pageTitle before including this file.
$pageTitle = $pageTitle ?? 'GrowFinity';
$current   = basename($_SERVER['SCRIPT_NAME']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($pageTitle) ?> | GrowFinity</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@500;700;800&family=Figtree:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="site-header">
  <div class="wrap bar">
    <a class="brand" href="index.php"><span class="brand-mark" aria-hidden="true"></span>GrowFinity</a>
    <button class="nav-toggle" type="button" aria-label="Open menu" aria-expanded="false" aria-controls="main-nav">Menu</button>
    <nav id="main-nav" class="nav">
      <a href="index.php" <?= $current === 'index.php' ? 'aria-current="page"' : '' ?>>Home</a>
      <?php if (is_logged_in()): ?>
        <a href="dashboard.php" <?= $current === 'dashboard.php' ? 'aria-current="page"' : '' ?>>My domains</a>
        <span class="nav-user">Hi, <?= e($_SESSION['user_name']) ?></span>
        <a class="btn btn-small btn-ghost" href="logout.php">Log out</a>
      <?php else: ?>
        <a href="login.php" <?= $current === 'login.php' ? 'aria-current="page"' : '' ?>>Log in</a>
        <a class="btn btn-small" href="signup.php">Sign up</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<main>
