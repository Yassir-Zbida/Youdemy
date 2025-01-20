<?php
require_once '../classes/db.php';
require_once '../classes/tag.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tagName = $_POST['tag_name'];

    $db = new Database();
    
    $stmt = $db->prepare('SELECT COUNT(*) FROM tags WHERE name = ?');
    if ($stmt) {
        $stmt->bind_param('s', $tagName);
        $stmt->execute();
        $stmt->bind_result($count); 
        $stmt->fetch(); 
        $stmt->close();

        if ($count > 0) {
            header('Location: tags_dashbord.php?message=Tag already exists');
            exit;
        } else {
            $tag = new Tag($db);
            if ($tag->createTag($tagName)) {
                header('Location: tags_dashbord.php?message=Tag added successfully');
                exit;
            } else {
                header('Location: tags_dashbord.php?message=Error adding tag');
                exit;
            }
        }
    } else {
        header('Location: tags_dashbord.php?message=Error checking tag existence');
        exit;
    }
}
?>
