<?php
session_start();
require __DIR__ . '/../config/conn.php';
require __DIR__ . '/../config/app.php';

if (!isset($_SESSION['auth_user'])) {
    $_SESSION['fail'] = "Login first to view your comments.";
    header("Location: ../signin.php");
    exit;
}

$currentUserId = $_SESSION['auth_user']['id'];

$sql = "SELECT c.*, p.title AS post_title 
        FROM comments c
        JOIN posts p ON c.post_id = p.id
        WHERE c.user_id = ?
        ORDER BY c.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $currentUserId);
$stmt->execute();
$result = $stmt->get_result();

ob_start();
?>

<div class="container my-5">
    <h2 class="mb-4">My Comments</h2>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($comment = $result->fetch_assoc()): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <p><?= nl2br(htmlspecialchars($comment['content'])) ?></p>
                    <small class="text-muted">
                        On post: <strong><?= htmlspecialchars($comment['post_title']) ?></strong><br>
                        <?= date("F j, Y, g:i a", strtotime($comment['created_at'])) ?>
                    </small>
                </div>
                <div class="card-footer">
                    <a href="views/edit-comment.php?id=<?= $comment['id'] ?>&post_id=<?= $comment['post_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="views/delete-comment.php?id=<?= $comment['id'] ?>&post_id=<?= $comment['post_id'] ?>" 
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Are you sure you want to delete this comment?')">Delete</a>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>You have not written any comments yet.</p>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
?>