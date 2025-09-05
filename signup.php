<?php session_start() ?>
<?php require __DIR__ . '/config/conn.php'; ?>

<?php
if(isset($_SESSION['auth_user'])) {
    header("location: index.php");
}
?>

<?php 
if(isset($_POST['action']) && $_POST['action'] === 'register') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone= trim($_POST['phone']);
    $password = trim($_POST['password']);
    $hashedPassword = password_hash($password , PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name , email , phone , password) VALUES (? , ? , ? , ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss" , $name , $email , $phone , $hashedPassword);
    $stmt->execute();
    
    $_SESSION['success'] = 'User Created Successfuly';
    header("location: signin.php");
    die;
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


    <!-- Sign Up Start -->
    <div class="container-fluid">
        <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
            <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                <div class="bg-light rounded p-4 p-sm-5 my-4 mx-3">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <a href="index.html" class="">
                            <h3 class="text-primary"><i class="fa fa-hashtag me-2"></i>ECHO</h3>
                        </a>
                        <h3>Sign Up</h3>
                    </div>
                    <form action="signup.php" method="POST">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="name" id="floatingText" placeholder="John Doe" required>
                            <label for="floatingText">Full Name</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" name="email" id="floatingInput" placeholder="name@example.com" required>
                            <label for="floatingInput">Email address</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="phone" id="floatingPhone" placeholder="Your Phone">
                            <label for="floatingPhone">Phone Number</label>
                        </div>

                        <div class="form-floating mb-4">
                            <input type="password" class="form-control" name="password" id="floatingPassword" placeholder="Password" required>
                            <label for="floatingPassword">Password</label>
                        </div>

                        <button type="submit" class="btn btn-primary py-3 w-100 mb-4" name="action" value="register">Sign Up</button>
                        <p class="text-center mb-0">Already have an Account? <a href="signin.php">Sign In</a></p>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <!-- Sign Up End -->
</div>

<?php require __DIR__ . '/layouts/includes/foot.php'; ?>