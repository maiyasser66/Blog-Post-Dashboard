<?php require __DIR__ . '/../../config/app.php'; ?>

<?php

$currentUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

?>


<!-- Sidebar Start -->
<div class="sidebar pe-4 pb-3">
    <nav class="navbar bg-light navbar-light">
        <a href="index.php" class="navbar-brand mx-4 mb-3">
            <h3 class="text-primary"><i class="fa fa-hashtag me-2"></i>ECHO</h3>
        </a>
        <div class="d-flex align-items-center ms-4 mb-4">
            <div class="position-relative">
                <i class="bi bi-person fs-4"></i>
                <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
            </div>

            <?php if(isset($_SESSION['auth_user'])){ ?>
                <div class="ms-3">
                    <h6 class="mb-0"><?= $_SESSION['auth_user']['name'] ?></h6>
                </div>
            <?php } else { ?>
                <span class="nav-item ms-2 mt-2">Guest</span>
            <?php } ?>
            
        </div>
        <div class="navbar-nav w-100">
            <a href="<?= $baseUrl ?>/index.php" class="nav-item nav-link active <?= $currentUrl === $baseUrl . "/" ? '' : 'collapsed' ?>" href="<?= $baseUrl ?>"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
            <li class="nav-item ms-5 mt-2">Pages</li>
            <a class="nav-link <?= $currentUrl === $baseUrl . "/pages/users/" ? '' : 'collapsed' ?>" href="<?= $baseUrl ?>/pages/users">
                <i class="bi bi-person"></i>
                <span>Users</span>
            </a>


            <a href="<?= $baseUrl ?>/posts/post-index.php" class="nav-item nav-link"><i class="bi bi-card-text me-2"></i>posts</a>
            <a href="<?= $baseUrl ?>/comments/comments-index.php" class="nav-item nav-link"><i class="bi bi-chat-left-dots-fill me-2"></i>comments</a>
            <div class="nav-item dropdown">
            </div>
        </div>
    </nav>
</div>
<!-- Sidebar End -->