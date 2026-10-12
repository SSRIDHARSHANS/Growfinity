<?php
require_once 'includes/init.php';
require_login();

$stepId = (int)($_GET['step'] ?? 0);
$stmt = $pdo->prepare(
    'SELECT s.*, d.name AS domain_name
       FROM roadmap_steps s JOIN domains d ON d.id = s.domain_id
      WHERE s.id = ?'
);
$stmt->execute([$stepId]);
$step = $stmt->fetch();
if (!$step) { header('Location: dashboard.php'); exit; }

// Mark complete / undo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    if (($_POST['action'] ?? '') === 'complete') {
        $pdo->prepare('INSERT IGNORE INTO progress (user_id, step_id) VALUES (?, ?)')
            ->execute([$_SESSION['user_id'], $stepId]);
    } elseif (($_POST['action'] ?? '') === 'undo') {
        $pdo->prepare('DELETE FROM progress WHERE user_id = ? AND step_id = ?')
            ->execute([$_SESSION['user_id'], $stepId]);
    }
    header('Location: learn.php?step=' . $stepId);
    exit;
}

$q = $pdo->prepare('SELECT 1 FROM progress WHERE user_id = ? AND step_id = ?');
$q->execute([$_SESSION['user_id'], $stepId]);
$completed = (bool)$q->fetchColumn();

$q = $pdo->prepare('SELECT id, title FROM roadmap_steps WHERE domain_id = ? AND step_order < ? ORDER BY step_order DESC LIMIT 1');
$q->execute([$step['domain_id'], $step['step_order']]);
$prev = $q->fetch();

$q = $pdo->prepare('SELECT id, title FROM roadmap_steps WHERE domain_id = ? AND step_order > ? ORDER BY step_order ASC LIMIT 1');
$q->execute([$step['domain_id'], $step['step_order']]);
$next = $q->fetch();

$videoId = preg_match('/^[A-Za-z0-9_-]{6,20}$/', $step['video_id']) ? $step['video_id'] : '';

$pageTitle = $step['title'];
require 'includes/header.php';
?>
<section class="section">
  <div class="wrap narrow">
    <p class="crumb">
      <a href="dashboard.php">My domains</a> /
      <a href="roadmap.php?domain=<?= (int)$step['domain_id'] ?>"><?= e($step['domain_name']) ?></a> /
      Step <?= (int)$step['step_order'] ?>
    </p>
    <h1><?= e($step['title']) ?></h1>
    <p class="level"><?= e($step['level']) ?></p>

    <div class="video">
      <?php if ($videoId): ?>
        <iframe src="https://www.youtube-nocookie.com/embed/<?= e($videoId) ?>"
                title="<?= e($step['title']) ?> lesson video"
                loading="lazy" allowfullscreen
                allow="accelerometer; encrypted-media; picture-in-picture"></iframe>
      <?php else: ?>
        <div class="video-empty">
          <p><strong>Video coming soon</strong></p>
          <p>Add a YouTube video ID for this step in the <code>roadmap_steps</code> table to show the lesson here.</p>
        </div>
      <?php endif; ?>
    </div>

    <h2>What you will learn</h2>
    <p><?= e($step['description']) ?></p>

    <form method="post" class="complete-form">
      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
      <?php if ($completed): ?>
        <input type="hidden" name="action" value="undo">
        <p class="done-note">You completed this step.</p>
        <button class="btn btn-ghost" type="submit">Mark as not complete</button>
      <?php else: ?>
        <input type="hidden" name="action" value="complete">
        <button class="btn" type="submit">Mark step complete</button>
      <?php endif; ?>
    </form>

    <nav class="pager" aria-label="Step navigation">
      <?php if ($prev): ?>
        <a href="learn.php?step=<?= (int)$prev['id'] ?>">Previous: <?= e($prev['title']) ?></a>
      <?php else: ?><span></span><?php endif; ?>
      <?php if ($next): ?>
        <a href="learn.php?step=<?= (int)$next['id'] ?>">Next: <?= e($next['title']) ?></a>
      <?php else: ?>
        <a href="roadmap.php?domain=<?= (int)$step['domain_id'] ?>">Back to roadmap</a>
      <?php endif; ?>
    </nav>
  </div>
</section>
<?php require 'includes/footer.php'; ?>
