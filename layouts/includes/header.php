<?php if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<div class="content">
    <!-- Navbar Start -->
    <nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
        <a href="index.html" class="navbar-brand d-flex d-lg-none me-4">
            <h2 class="text-primary mb-0"><i class="fa fa-hashtag"></i></h2>
        </a>
        <a href="#" class="sidebar-toggler flex-shrink-0">
            <i class="fa fa-bars"></i>
        </a>
        <form class="d-none d-md-flex ms-4">
            <input class="form-control border-0" type="search" placeholder="Search">
        </form>
        <div class="navbar-nav align-items-center ms-auto">
            <div class="nav-item dropdown me-3">
                <?php if (!isset($_SESSION['auth_user'])) { ?>
                    <div class="actions">
                        <a class="link-primary m-3 btn btn-primary" href="<?= $baseUrl ?>/signin.php">Sign In</a>
                        <a class="link-primary m-3 btn btn-primary" href="<?= $baseUrl ?>/signup.php">Sign Up</a>
                    </div>
                <?php } else { ?>

                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-2"></i>
                        <span class="d-none d-lg-inline-flex"><?= $_SESSION['auth_user']['name'] ?></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                        <a href="#" class="dropdown-item">My Profile</a>
                        <form class="d-inline" action="<?= $baseUrl ?>/logout.php" method="post">
                            <button class="btn btn-danger ms-3" type="submit">Logout</button>
                        </form>
                    </div>

                <?php } ?>
            </div>
        </div>
    </nav>
    <!-- Navbar End -->

    <div class="flex-grow-1">

