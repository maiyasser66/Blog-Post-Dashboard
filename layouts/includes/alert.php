<?php if (isset($_SESSION['success'])) { ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i>
        <?= $_SESSION['success'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php } ?>


<?php if (isset($_SESSION['fail'])) { ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i>
        <?= $_SESSION['fail'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php } ?>