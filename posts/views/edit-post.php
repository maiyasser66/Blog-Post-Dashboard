<?php session_start(); ?>
<?php
require __DIR__ . '/../../config/conn.php';
require __DIR__ . '/../../config/app.php';


if (!isset($_SESSION['auth_user'])) {
    header("Location: ../../signin.php");
    $_SESSION['fail'] = "Login first to edit a post.";
    exit;
}

$currentUser = $_SESSION['auth_user']['name'];

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['fail'] = "No post selected.";
    header("Location: ../post-index.php");
    exit;
}

$postId = $_GET['id'];


$sql = "SELECT * FROM posts WHERE id = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $postId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    $_SESSION['fail'] = "Post not found.";
    header("Location: ../post-index.php");
    exit;
}

$post = $result->fetch_assoc();

if ($post['created_by'] !== $currentUser) {
    $_SESSION['fail'] = "You are not allowed to edit this post.";
    header("Location: ../../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update-post') {
    $postId = ($_POST['id']);
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $image = $_POST['image'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . './images/';
        $fileName = time() . '_' . basename($_FILES['image']['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $image = $fileName;
        } else {
            $_SESSION['fail'] = "Image upload failed.";
            header("Location: edit-post.php?id=" . $postId);
            exit;
        }
    }


    $sql = "UPDATE posts SET title = ?, content = ?, image = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $title, $content, $image, $postId);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Post updated successfully!";
        header("Location: ./post-details.php?id=" . $postId);
        exit;
    } else {
        $_SESSION['fail'] = "Failed to update post.";
    }
}

ob_start();
?>

<div class="container my-5 w-50">
    <h2 class="mb-4">Edit Post</h2>

    <?php if (isset($_SESSION['fail'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['fail'];
        unset($_SESSION['fail']); ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $post['id'] ?>">
        <input type="hidden" name="image" value="<?= htmlspecialchars($post['image']) ?>">
        <div class="mb-3">
            <label class="form-label">Post Title</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($post['title']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Current Image</label><br>
            <img src="./images/<?= htmlspecialchars($post['image']) ?>"
                alt="<?= htmlspecialchars($post['title']) ?>" width="200" class="mb-2">
            <input type="file" name="image" class="form-control">
            <small class="text-muted">Upload only if you want to replace the current image</small>
        </div>

        <div class="mb-3">
            <label class="form-label">Post Content</label>
            <textarea name="content" class="form-control" rows="6" required><?= htmlspecialchars($post['content']) ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary" name="action" value="update-post">Update Post</button>
        <a href="./post-details.php?= $postId ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../../layouts/main.php';
?>