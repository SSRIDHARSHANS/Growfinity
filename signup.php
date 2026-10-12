<?php
require_once 'includes/init.php';
if (is_logged_in()) { header('Location: dashboard.php'); exit; }

$errors = [];
$name = $email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $name    = trim($_POST['name'] ?? '');
    $email   = strtolower(trim($_POST['email'] ?? ''));
    $pass    = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if ($name === '' || mb_strlen($name) > 80)            $errors[] = 'Enter your name (up to 80 characters).';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))       $errors[] = 'Enter a valid email address.';
    if (strlen($pass) < 8)                                $errors[] = 'Use a password with at least 8 characters.';
    if ($pass !== $confirm)                               $errors[] = 'The two passwords do not match.';

    if (!$errors) {
        $check = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $check->execute([$email]);
        if ($check->fetch()) {
            $errors[] = 'An account with this email already exists. Try logging in.';
        } else {
            $ins = $pdo->prepare('INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)');
            $ins->execute([$name, $email, password_hash($pass, PASSWORD_DEFAULT)]);
            session_regenerate_id(true);
            $_SESSION['user_id']   = (int)$pdo->lastInsertId();
            $_SESSION['user_name'] = $name;
            header('Location: dashboard.php');
            exit;
        }
    }
}

$pageTitle = 'Sign up';
require 'includes/header.php';
?>
<section class="auth">
  <div class="auth-box">
    <h1>Create your account</h1>
    <p class="muted">Free. Choose a domain right after you sign up.</p>
    <?php if ($errors): ?>
      <div class="alert" role="alert"><ul><?php foreach ($errors as $err): ?><li><?= e($err) ?></li><?php endforeach; ?></ul></div>
    <?php endif; ?>
    <form method="post" novalidate id="signup-form">
      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
      <label for="name">Full name</label>
      <input id="name" name="name" type="text" value="<?= e($name) ?>" required autocomplete="name">
      <label for="email">Email</label>
      <input id="email" name="email" type="email" value="<?= e($email) ?>" required autocomplete="email">
      <label for="password">Password (at least 8 characters)</label>
      <input id="password" name="password" type="password" required autocomplete="new-password">
      <label for="confirm">Confirm password</label>
      <input id="confirm" name="confirm" type="password" required autocomplete="new-password">
      <button class="btn btn-block" type="submit">Create account</button>
    </form>
    <p class="auth-alt">Already have an account? <a href="login.php">Log in</a></p>
  </div>
</section>
<?php require 'includes/footer.php'; ?>
