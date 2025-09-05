<?php
// session_start();
include __DIR__ . '/includes/head.php';
include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/aside.php';
?>


<main id="main" class="main">
  <?php include __DIR__ . '/includes/alert.php'; ?>
  
  <div class="pagetitle">
    <h1><?= $title ?? '' ?></h1>
  </div><!-- End Page Title -->

  <!-- content here -->
  <?= $content ?? '' ?>

</main><!-- End #main -->

<?php
include __DIR__ . '/includes/footer.php';
include __DIR__ . '/includes/foot.php';

unset($_SESSION['success'], $_SESSION['fail']);
?>