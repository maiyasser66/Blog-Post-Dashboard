<?php session_start();
require __DIR__ . '/../../config/conn.php';
require __DIR__ . '/../../config/app.php';


if (!isset($_SESSION['auth_user'])) {
    header("Location: ../../signin.php");
    $_SESSION['fail'] = "Login first to delete a post.";
    exit;
}

$currentUser = $_SESSION['auth_user']['name'];

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['fail'] = "No post selected.";
    header("Location: ../post-index.php");
    exit;
}

$postId = $_GET['id'];

$sql = "SELECT * FROM posts WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $postId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['fail'] = "Post not found.";
    header("Location: ../post-index.php");
    exit;
}

$post = $result->fetch_assoc();

if ($post['created_by'] !== $currentUser) {
    $_SESSION['fail'] = "You are not allowed to delete this post.";
    header("Location: ../../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    $sql = "DELETE FROM posts WHERE id = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $postId);

    if ($stmt->execute()) {
        if (!empty($post['image']) && file_exists("./posts/views/images/" . $post['image'])) {
            unlink("./posts/views/images/" . $post['image']);
        }

        $_SESSION['success'] = "Post deleted successfully!";
        header("Location: ../post-index.php");
        exit;
    } else {
        $_SESSION['fail'] = "Failed to delete post.";
        header("Location: post-details.php?id=" . $postId);
        exit;
    }
}

ob_start();
?>

<div class="container my-5">
    <h2 class="mb-4 text-danger">Delete Post</h2>
    <div class="card shadow">
        <div class="card-body">
            <h4 class="card-title"><?= htmlspecialchars($post['title']) ?></h4>
            <p>Are you sure you want to delete this post? This action <strong>cannot be undone</strong>.</p>
            <form method="post">
                <button type="submit" name="confirm_delete" class="btn btn-danger">Yes, Delete</button>
                <a href="post-details.php?id=<?= $postId ?>" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../../layouts/main.php';
?>
