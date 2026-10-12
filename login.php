<?php
require_once 'includes/init.php';
if (is_logged_in()) { header('Location: dashboard.php'); exit; }

$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $email = strtolower(trim($_POST['email'] ?? ''));
    $pass  = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT id, name, password_hash FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($pass, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user_id']   = (int)$user['id'];
        $_SESSION['user_name'] = $user['name'];
        header('Location: dashboard.php');
        exit;
    }
    $error = 'Email or password is incorrect. Check both and try again.';
}

$pageTitle = 'Log in';
require 'includes/header.php';
?>
<section class="auth">
  <div class="auth-box">
    <h1>Welcome back</h1>
    <p class="muted">Log in to continue your roadmap.</p>
    <?php if ($error): ?><div class="alert" role="alert"><?= e($error) ?></div><?php endif; ?>
    <form method="post">
      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
      <label for="email">Email</label>
      <input id="email" name="email" type="email" value="<?= e($email) ?>" required autocomplete="email">
      <label for="password">Password</label>
      <input id="password" name="password" type="password" required autocomplete="current-password">
      <button class="btn btn-block" type="submit">Log in</button>
    </form>
    <p class="auth-alt">New here? <a href="signup.php">Create an account</a></p>
  </div>
</section>
<?php require 'includes/footer.php'; ?>
