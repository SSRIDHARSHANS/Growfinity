<?php
require_once 'includes/init.php';
$pageTitle = 'Know what to learn next';
$domains = $pdo->query(
    'SELECT d.id, d.name, d.summary, COUNT(s.id) AS total
       FROM domains d LEFT JOIN roadmap_steps s ON s.domain_id = d.id
      GROUP BY d.id ORDER BY d.sort_order'
)->fetchAll();
require 'includes/header.php';
?>
<section class="hero">
  <div class="wrap hero-grid">
    <div>
      <h1>Know exactly what to learn next in engineering.</h1>
      <p class="lead">Pick a domain, follow a step-by-step roadmap, and watch every lesson inside GrowFinity. No more guessing where to start.</p>
      <div class="actions">
        <?php if (is_logged_in()): ?>
          <a class="btn" href="dashboard.php">Go to my domains</a>
        <?php else: ?>
          <a class="btn" href="signup.php">Create free account</a>
          <a class="btn btn-ghost" href="login.php">Log in</a>
        <?php endif; ?>
      </div>
    </div>
    <aside class="sample" aria-label="Example roadmap">
      <p class="sample-title">Example: Embedded Systems</p>
      <ol class="trail trail-mini">
        <li class="node done"><span class="dot">&#10003;</span><div><strong>C programming basics</strong><small>Beginner</small></div></li>
        <li class="node done"><span class="dot">&#10003;</span><div><strong>Microcontroller architecture</strong><small>Beginner</small></div></li>
        <li class="node current"><span class="dot">3</span><div><strong>GPIO, timers and interrupts</strong><small>Intermediate</small></div></li>
        <li class="node"><span class="dot">4</span><div><strong>UART, SPI and I2C</strong><small>Intermediate</small></div></li>
      </ol>
    </aside>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <h2>How it works</h2>
    <div class="steps-row">
      <div><h3>1. Sign up</h3><p>Create an account with your email. It takes under a minute.</p></div>
      <div><h3>2. Choose your domain</h3><p>Select the area you want to build a career in.</p></div>
      <div><h3>3. Follow the path</h3><p>See every skill in the right order, from beginner to advanced.</p></div>
      <div><h3>4. Learn and track</h3><p>Watch the video for each skill and mark it complete.</p></div>
    </div>
  </div>
</section>

<section class="section section-tint">
  <div class="wrap">
    <h2>Domains you can start today</h2>
    <ul class="domain-list">
      <?php foreach ($domains as $d): ?>
        <li>
          <div>
            <h3><?= e($d['name']) ?></h3>
            <p><?= e($d['summary']) ?></p>
          </div>
          <span class="chip"><?= (int)$d['total'] ?> steps</span>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>
<?php require 'includes/footer.php'; ?>
