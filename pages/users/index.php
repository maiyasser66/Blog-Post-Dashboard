<?php
session_start();
require __DIR__ . '/../../config/conn.php';

$title = 'Users';
$action = $_GET['action'] ?? 'index';

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'update':
            $id = (int) $_POST['user_id'];
            if ($id === (int) $_SESSION['auth_user']['id']) {
                $name = $_POST['name'];
                $email = $_POST['email'];
                $phone = $_POST['phone'];

                $sql = "UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssi", $name, $email, $phone, $userId);
                if ($stmt->execute()) {
                    $_SESSION['success'] = 'Profile updated successfully!';
                    header("Location: index.php");
                    exit;
                }
            } else {
                $_SESSION['fail'] = "You can't edit other's accounts";
                header("Location: .");
                exit;
            }
            break;

        case 'delete':
            $id = (int) $_POST['user_id'];
            if ($id === (int) $_SESSION['auth_user']['id']) {
                $sql = "DELETE FROM users WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $id);
                if ($stmt->execute()) {
                    session_destroy(); // logout after deleting account
                    header("Location:  ../../signup.php");
                    $_SESSION['success'] = 'User Deleted Successfully';
                    exit;
                }
            } else {
                $_SESSION['fail'] = "You can't delete other's accounts";
                header("Location: .");
                exit;
            }
            break;
    }
}

ob_start();

switch ($action) {
    case 'create':
        include __DIR__ . '/views/create.php';
        break;
    case 'edit':
        if (isset($_GET['id'])) {
            $sql = "SELECT * FROM users WHERE id = ? LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $_GET['id']);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
        }
        include __DIR__ . '/views/edit.php';
        break;
    case 'index';
    default:
        $sql = "SELECT * FROM users";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        include __DIR__ . '/views/index.php';
        break;
}

$content = ob_get_clean();

require __DIR__ . '/../../layouts/main.php';
