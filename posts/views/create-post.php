<?php session_start(); ?>
<?php require __DIR__ . '/../../config/conn.php'; ?>

<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'create-posts') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = './images/';
        $fileName = time() . '_' . basename($_FILES['image']['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $image = $fileName;
        } else {
            $_SESSION['fail'] = 'Failed to upload file';
            header("Location: create-post.php");
            exit;
        }
    } else {
        $_SESSION['fail'] = 'Upload error';
        header("Location: create-post.php");
        exit;
    }

    $createdBy = $_SESSION['auth_user']['name'];
    $sql = "INSERT INTO posts (title, image , content , created_by) VALUES (? , ? , ? , ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $title, $image, $content, $createdBy);
    if ($stmt->execute()) {
        $_SESSION['success'] = 'Post Uploaded Successfully';
        header("Location: ../../index.php ");
        die;
    }
}


?>

<?php

ob_start();

?>

<form class="container col-9 my-5" method="post" enctype="multipart/form-data">
    <h2 class="my-3">Upload a New Post</h2>
    <div class="mb-3">
        <label for="post-title" class="form-label">Post Title</label>
        <input type="text" name="title" class="form-control" id="post-title" placeholder="Post Title" required>
    </div>
    <div class="mb-3">
        <label for="post" class="form-label">Post image</label>
        <input class="form-control" name="image" type="file" id="post-image" required>
    </div>
    <div class="mb-3">
        <label for="content" class="form-label">Post content</label>
        <textarea class="form-control" name="content" id="post-content" rows="4"
            placeholder="Write the post's content..."></textarea>
    </div>
    <button type="submit" class="btn btn-primary" name="action" value="create-posts">Upload a new post</button>
</form>

<?php
$content = ob_get_clean();
?>

<?php require __DIR__ . '/../../layouts/main.php'; ?>