<?php session_start() ?>
<?php require __DIR__ . '/config/conn.php'; ?>

<?php
if(isset($_SESSION['auth_user'])) {
    header("location: index.php");
}
?>

<?php 
if(isset($_POST['action']) && $_POST['action'] === 'login') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s" , $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $_SESSION['fail'] = 'Invalid Data';
        header("Location: signin.php");
        exit;
    } else {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['auth_user'] = [
                'id' => $row['id'],
                'name' => $row['name'],
            ];

            header("Location: index.php");
            exit;
        } else {
            $_SESSION['fail'] = 'Invalid Data';
            header("Location: signin.php");
            exit;
        }
    }
}

?>

<?php require __DIR__ . '/layouts/includes/head.php'; ?>

    <div class="container-xxl position-relative bg-white d-flex p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->


        <!-- Sign In Start -->
        <div class="container-fluid">
            <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
                <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                    <div class="bg-light rounded p-4 p-sm-5 my-4 mx-3">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <a href="index.html" class="">
                                <h3 class="text-primary"><i class="fa fa-hashtag me-2"></i>ECHO</h3>
                            </a>
                            <h3>Sign In</h3>
                        </div>
                        <?php require __DIR__ . '/layouts/includes/alert.php'; ?>
                        <form action="signin.php" method="POST">
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" name="email" required>
                                <label>Email address</label>
                            </div>
                            <div class="form-floating mb-4">
                                <input type="password" class="form-control" name="password" required>
                                <label>Password</label>
                            </div>
                            <button type="submit" class="btn btn-primary w-100" name="action" value="login">Sign In</button>
                        </form>

                        <p class="text-center m-2">Don't have an Account? <a href="signup.php">Sign Up</a></p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Sign In End -->
    </div>


<?php require __DIR__ . '/layouts/includes/foot.php'; ?>