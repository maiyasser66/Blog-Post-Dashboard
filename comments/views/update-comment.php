<?php
session_start();
require __DIR__ . '/../../config/conn.php';

if (!isset($_SESSION['auth_user'])) {
    $_SESSION['fail'] = "Login required.";
    header("Location: ../../signin.php");
    exit;
}

if (!isset($_GET['id']) || !isset($_GET['post_id'])) {
    $_SESSION['fail'] = "Invalid request.";
    header("Location: ../../index.php");
    exit;
}

$commentId = intval($_GET['id']);
$postId = intval($_GET['post_id']);
$userId = $_SESSION['auth_user']['id'];

$sql = "SELECT * FROM comments WHERE id = ? AND user_id = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $commentId, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    $_SESSION['fail'] = "Comment not found or not yours.";
    header("Location: ../../posts/post-index.php?id=" . $postId);
    exit;
}

$comment = $result->fetch_assoc();

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = trim($_POST['content']);

    if (!empty($content)) {
        $sql = "UPDATE comments SET content = ? WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $content, $commentId, $userId);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Comment updated successfully!";
        } else {
            $_SESSION['fail'] = "Failed to update comment.";
        }
    }

    header("Location: ../../posts/post-index.php?id=" . $postId);
    exit;
}

ob_start();
?>

<div class="container my-5">
    <h2>Edit Comment</h2>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Comment</label>
            <textarea name="content" class="form-control" rows="3" required><?= htmlspecialchars($comment['content']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="../../posts/post-index.php?id=<?= $postId ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../../layouts/main.php';
?>