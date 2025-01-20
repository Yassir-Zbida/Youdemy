<?php
require_once '../classes/db.php';
require_once '../classes/admin.php';
session_start();
$AdminId = $_SESSION['user_id'];

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $userId = $_GET['id'];

    $db = new Database();
    $admin = new Admin($db, $AdminId);

    if ($admin->deleteUser($userId)) {
        header('Location: users_dashbord.php?message=User deleted successfully');
        exit;
    } else {
        header('Location: tags_dashbord.php?message=Error deleting User');
        exit;
    }
} else {
    header('Location: tags_dashbord.php?message=Invalid User ID');
    exit;
}
?>