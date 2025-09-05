<?php
session_start();
require __DIR__ . '/../../config/conn.php';

if (!isset($_SESSION['auth_user'])) {
    $_SESSION['fail'] = "You must login to comment.";
    header("Location: ../../signin.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postId = intval($_POST['post_id']);
    $content = trim($_POST['content']);
    $userId = $_SESSION['auth_user']['id']; 
    $userName = $_SESSION['auth_user']['name'];

    if (!empty($content)) {
        $sql = "INSERT INTO comments (content, created_by, user_id, post_id) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssii", $content, $userName, $userId, $postId);
        $stmt->execute();
    }
}

header("Location: ../../index.php");
exit;
?>