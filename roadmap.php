<?php
require_once 'includes/init.php';
require_login();

$domainId = (int)($_GET['domain'] ?? 0);
$stmt = $pdo->prepare('SELECT id, name, summary FROM domains WHERE id = ?');
$stmt->execute([$domainId]);
$domain = $stmt->fetch();
if (!$domain) { header('Location: dashboard.php'); exit; }

$stmt = $pdo->prepare(
    'SELECT s.id, s.step_order, s.title, s.level, s.description,
            (p.id IS NOT NULL) AS completed
       FROM roadmap_steps s
       LEFT JOIN progress p ON p.step_id = s.id AND p.user_id = ?
      WHERE s.domain_id = ?
      ORDER BY s.step_order'
);
$stmt->execute([$_SESSION['user_id'], $domainId]);
$steps = $stmt->fetchAll();

$total = count($steps);
$done  = 0;
$nextId = null;
foreach ($steps as $s) {
    if ($s['completed']) { $done++; }
    elseif ($nextId === null) { $nextId = (int)$s['id']; }
}
$pct = $total ? round($done * 100 / $total) : 0;

$pageTitle = $domain['name'] . ' roadmap';
require 'includes/header.php';
?>
<section class="section">
  <div class="wrap narrow">
    <p class="crumb"><a href="dashboard.php">My domains</a> / <?= e($domain['name']) ?></p>
    <h1><?= e($domain['name']) ?> roadmap</h1>
    <p class="lead"><?= e($domain['summary']) ?></p>
    <div class="meter meter-big" role="progressbar" aria-valuenow="<?= $pct ?>" aria-valuemin="0" aria-valuemax="100" aria-label="Roadmap progress">
      <span style="width: <?= $pct ?>%"></span>
    </div>
    <p class="meter-text"><?= $done ?> of <?= $total ?> steps complete (<?= $pct ?>%)</p>

    <?php if ($total === 0): ?>
      <p>This roadmap has no steps yet. Add rows to the <code>roadmap_steps</code> table to fill it.</p>
    <?php else: ?>
    <ol class="trail">
      <?php foreach ($steps as $s):
        $class = $s['completed'] ? 'done' : ((int)$s['id'] === $nextId ? 'current' : '');
      ?>
      <li class="node <?= $class ?>">
        <span class="dot" aria-hidden="true"><?= $s['completed'] ? '&#10003;' : (int)$s['step_order'] ?></span>
        <div class="node-body">
          <h3><a href="learn.php?step=<?= (int)$s['id'] ?>"><?= e($s['title']) ?></a></h3>
          <small class="level"><?= e($s['level']) ?><?= $s['completed'] ? ' - completed' : '' ?></small>
          <p><?= e($s['description']) ?></p>
          <a class="btn btn-small <?= $class === 'current' ? '' : 'btn-ghost' ?>" href="learn.php?step=<?= (int)$s['id'] ?>">
            <?= $s['completed'] ? 'Watch again' : 'Learn this step' ?>
          </a>
        </div>
      </li>
      <?php endforeach; ?>
    </ol>
    <?php endif; ?>
  </div>
</section>
<?php require 'includes/footer.php'; ?>
