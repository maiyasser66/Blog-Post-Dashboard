<?php
session_start();
require __DIR__ . '/../../config/conn.php';
require __DIR__ . '/../../config/app.php';

if (!isset($_SESSION['auth_user'])) {
    $_SESSION['fail'] = "Login first to delete a comment.";
    header("Location: ../../signin.php");
    exit;
}

$currentUserId = $_SESSION['auth_user']['id'];

if (!isset($_GET['id']) || !isset($_GET['post_id'])) {
    $_SESSION['fail'] = "Invalid request.";
    header("Location: ../../index.php");
    exit;
}

$commentId = intval($_GET['id']);
$postId = intval($_GET['post_id']);

$sql = "SELECT * FROM comments WHERE id = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $commentId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    $_SESSION['fail'] = "Comment not found.";
    header("Location: ../../posts/post-index.php?id=" . $postId);
    exit;
}

$comment = $result->fetch_assoc();

if ($comment['user_id'] != $currentUserId) {
    $_SESSION['fail'] = "You are not allowed to delete this comment.";
    header("Location: ../../posts/post-index.php?id=" . $postId);
    exit;
}

// Delete the comment
$sql = "DELETE FROM comments WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $commentId);

if ($stmt->execute()) {
    $_SESSION['success'] = "Comment deleted successfully.";
} else {
    $_SESSION['fail'] = "Failed to delete comment.";
}

header("Location: ../../posts/post-index.php?id=" . $postId);
exit;
?>