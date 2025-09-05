<?php
require __DIR__ . '/../config/conn.php';
require __DIR__ . '/../config/app.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['auth_user'])) {
    header("Location: ../signin.php");
    exit;
}

$currentUser = $_SESSION['auth_user']['name'];

// Fetch only posts created by this user
$sql = "SELECT * FROM posts WHERE created_by = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $currentUser);
$stmt->execute();
$result = $stmt->get_result();

ob_start();
?>

<div class="container">
    <h2 class="m-4">My Posts</h2>

    <?php if ($result->num_rows === 0): ?>
        <div class="alert alert-info">You haven't created any posts yet.</div>
    <?php endif; ?>

    <div class="row my-5">
        <?php while ($row = $result->fetch_assoc()) { ?>
            <div class="card text-center m-3" style="width: 18rem;">
                <img src="./views/images/<?= !empty($row['image']) ? htmlspecialchars($row['image']) : 'default.jpg' ?>" class="card-img-top" alt="<?= htmlspecialchars($row['title']) ?>">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
                    <p class="card-text text-start"><?= substr($row['content'], 0, 150) . '.....' ?></p>
                    <a href="./views/post-details.php?id=<?= $row['id'] ?>" class="btn btn-primary mt-auto w-60 mx-auto">Post Details</a>
                </div>
                <div class="text-body-secondary pb-4">
                    <strong class="text-secondary">
                        <?= htmlspecialchars($row['created_by']) ?> •
                        <time><?= date("F j, Y, g:i a", strtotime($row['created_at'])) ?></time>
                    </strong>
                </div>

                <!-- Comments Section -->
                <div class="card-footer text-start">
                    <h6>Comments:</h6>
                    <?php
                    $postId = $row['id'];
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
                                    <a href="../comments/views/update-comment.php ?id=<?= $comment['id'] ?>&post_id=<?= $postId ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="../comments/views/delete-comment.php?id=<?= $comment['id'] ?>&post_id=<?= $postId ?>" class="btn btn-sm btn-danger"
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
                        <form method="post" action="../comments/views/add-comment.php" class="mt-2">
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
        <?php } ?>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
?>