<?php
require_once '../classes/db.php';
require_once '../classes/tag.php';
session_start();

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $tagId = $_GET['id'];

    $db = new Database();
    $tag = new Tag($db);

    if ($tag->deleteTag($tagId)) {
        header('Location: tags_dashbord.php?message=Tag deleted successfully');
        exit;
    } else {
        header('Location: tags_dashbord.php?message=Error deleting tag');
        exit;
    }
} else {
    header('Location: tags_dashbord.php?message=Invalid tag ID');
    exit;
}
?>
