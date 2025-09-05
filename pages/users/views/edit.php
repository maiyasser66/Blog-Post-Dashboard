<?php
require __DIR__ . '/../../../config/conn.php';

?>

<?php
$userId = $_GET['id'] ?? $_POST['user_id'] ?? null;

if (!$userId) {
    $_SESSION['fail'] = "No user selected.";
    header("Location: ."); 
    exit;
}

$sql = "SELECT * FROM users WHERE id = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    $_SESSION['fail'] = "User not found.";
    header("Location: .");
    exit;
}

$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    $checkSql = "SELECT id FROM users WHERE email = ? AND id != ? LIMIT 1";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("si", $email, $userId);
    $checkStmt->execute();
    $checkStmt->store_result();


        $updateSql = "UPDATE users SET name = ?, email = ?, phone = ? = ? WHERE id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("sssi", $name, $email, $phone, $userId);

        if ($updateStmt->execute()) {
            $_SESSION['success'] = "User updated successfully!";
        } else {
            $_SESSION['fail'] = "Failed to update user: " . $conn->error;
        }
    

    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}

?>


<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Update User</h5>

            <!-- Update User Form -->
            <form class="row g-3" action="." method="post">
                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" name="name" class="form-control" id="floatingName" placeholder="Your Name" value="<?= $user['name'] ?>">
                        <label for="floatingName">Your Name</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="email" name="email" class="form-control" id="floatingEmail" placeholder="Your Email" value="<?= $user['email'] ?>">
                        <label for="floatingEmail">Your Email</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" name="phone" class="form-control" id="floatingPhone" placeholder="Your Phone" value="<?= $user['phone'] ?>">
                        <label for="floatingPhone">Your Phone</label>
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary" name="action" value="update">Update User</button>
                    <a href="." class="btn btn-secondary">Back</a>
                </div>
            </form><!-- End Update User Form -->

        </div>
    </div>
</section>