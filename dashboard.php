<?php
require_once 'includes/init.php';
require_login();

$stmt = $pdo->prepare(
    'SELECT d.id, d.name, d.summary,
            COUNT(s.id) AS total,
            COUNT(p.id) AS done
       FROM domains d
       LEFT JOIN roadmap_steps s ON s.domain_id = d.id
       LEFT JOIN progress p ON p.step_id = s.id AND p.user_id = ?
      GROUP BY d.id, d.name, d.summary, d.sort_order
      ORDER BY d.sort_order'
);
$stmt->execute([$_SESSION['user_id']]);
$domains = $stmt->fetchAll();

$pageTitle = 'My domains';
require 'includes/header.php';
?>
<section class="section">
  <div class="wrap">
    <h1>Choose your domain</h1>
    <p class="lead">Pick the area you want to grow in. You can start more than one and switch any time.</p>
    <ul class="domain-grid">
      <?php foreach ($domains as $d):
        $total = (int)$d['total'];
        $done  = (int)$d['done'];
        $pct   = $total ? round($done * 100 / $total) : 0;
        $label = $done === 0 ? 'Start roadmap' : ($done >= $total ? 'Review roadmap' : 'Continue roadmap');
      ?>
      <li class="domain-card">
        <h3><?= e($d['name']) ?></h3>
        <p><?= e($d['summary']) ?></p>
        <div class="meter" role="progressbar" aria-valuenow="<?= $pct ?>" aria-valuemin="0" aria-valuemax="100" aria-label="<?= e($d['name']) ?> progress">
          <span style="width: <?= $pct ?>%"></span>
        </div>
        <p class="meter-text"><?= $done ?> of <?= $total ?> steps complete</p>
        <a class="btn btn-small" href="roadmap.php?domain=<?= (int)$d['id'] ?>"><?= e($label) ?></a>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>
<?php require 'includes/footer.php'; ?>
