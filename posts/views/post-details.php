<?php
require __DIR__ . '/../../config/conn.php';
require __DIR__ . '/../../config/app.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../../index.php");
    exit;
}

$postId = intval($_GET['id']);

$sql = "SELECT * FROM posts WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $postId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: ../../index.php");
    exit;
}

$post = $result->fetch_assoc();

ob_start();
?>

<div class="container my-5 w-50">
    <div class="card shadow">
        <img src="./images/<?= !empty($post['image']) ? htmlspecialchars($post['image']) : 'default.jpg' ?>"
            class="card-img-top"
            alt="<?= htmlspecialchars($post['title']) ?>">

        <div class="card-body">
            <h2 class="card-title"><?= htmlspecialchars($post['title']) ?></h2>
            <div class="actions text-end">
                <a class="btn btn-success" href="edit-post.php?id=<?= $post['id'] ?>"><i class="bi bi-pencil-square"></i></a>
                <form class="d-inline" action="delete-post.php?id=<?= $post['id'] ?>" method="post">
                    <button type="submit" name="id" value="<?= $post['id'] ?>" class="btn btn-danger"><i class="bi bi-trash-fill"></i></button>
                </form>
            </div>
            <p class="card-text"><?= nl2br(htmlspecialchars($post['content'])) ?></p>
        </div>

        <div class="card-footer text-muted">
            <strong><?= htmlspecialchars($post['created_by']) ?></strong> •
            <time><?= date("F j, Y, g:i a", strtotime($post['created_at'])) ?></time>
        </div>

        <!-- Comments Section -->
        <div class="card-footer text-start">
            <h6>Comments:</h6>
            <?php
            $postId = intval($post['id']);
            $sql = "SELECT * FROM comments WHERE post_id = ? ORDER BY created_at DESC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $postId);
            $stmt->execute();
            $comments = $stmt->get_result();


            if ($comments->num_rows > 0) {
                while ($comment = $comments->fetch_assoc()) {
                    echo "<p><strong>" . htmlspecialchars($comment['created_by']) . ":</strong> "
                        . htmlspecialchars($comment['content'])
                        . "<br><small class='text-muted'>"
                        . date("F j, Y, g:i a", strtotime($comment['created_at']))
                        . "</small></p>";

                    if (isset($_SESSION['auth_user']) && $_SESSION['auth_user']['id'] == $comment['user_id']) { ?>
                        <div class="mb-3 mt-0">
                            <a href="../../comments/views/update-comment.php?id=<?= $comment['id'] ?>&post_id=<?= $postId ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="../../comments/views/delete-comment.php?id=<?= $comment['id'] ?>&post_id=<?= $postId ?>" class="btn btn-sm btn-danger"
                                onclick="return confirm('Are you sure you want to delete this comment?')">Delete</a>
                            <hr>
                        </div>

            <?php }
                }
            } else {
                echo "<p class='text-muted'>No comments yet.</p>";
            }
            ?>

            <!-- Add Comment Form -->
            <?php if (isset($_SESSION['auth_user'])): ?>
                <form method="post" action="../../comments/views/add-comment.php" class="mt-2">
                    <input type="hidden" name="post_id" value="<?= $row['id'] ?>">
                    <div class="input-group">
                        <input type="text" name="content" class="form-control" placeholder="Write a comment..." required>
                        <button type="submit" class="btn btn-sm btn-primary">Post</button>
                    </div>
                </form>
            <?php else: ?>
                <p class="text-muted">Login to comment</p>
            <?php endif; ?>
        </div>
    </div>

    <a href="../post-index.php" class="btn btn-secondary mt-3">← Back to Posts</a>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../../layouts/main.php';
?>