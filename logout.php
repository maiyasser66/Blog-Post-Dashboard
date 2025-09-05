<?php session_start() ?>

<?php
if (isset($_SESSION['auth_user'])) {
    unset($_SESSION['auth_user']);
    session_unset();
    session_destroy();
    
    header("Location: index.php");
    die;
}
?>