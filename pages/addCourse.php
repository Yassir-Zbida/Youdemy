<?php
session_start();
require_once '../classes/db.php';
require_once '../classes/course.php';

$db = new Database();
$course = new Course($db);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = floatval($_POST['price']);
    $difficulty = $_POST['difficulty'];
    $duration = intval($_POST['duration_hours']) ;
    $categoryId = intval($_POST['category']);
    $tags = isset($_POST['tags']) ? $_POST['tags'] : [];
    $contentType = $_POST['content-type'];
    $status = 'Published';

    $thumbnail = null;
    if (!empty($_FILES['file-upload-thumbnail']['name'])) {
        $thumbnailDir = '../uploads/thumbnails/';
        $thumbnailPath = $thumbnailDir . basename($_FILES['file-upload-thumbnail']['name']);
        if (move_uploaded_file($_FILES['file-upload-thumbnail']['tmp_name'], $thumbnailPath)) {
            $thumbnail = $thumbnailPath; 
        }
    }

    $contentFile = null;
    if ($contentType === 'video' && !empty($_FILES['file-upload-video']['name'])) {
        $videoDir = '../uploads/videos/';
        $contentPath = $videoDir . basename($_FILES['file-upload-video']['name']);
        if (move_uploaded_file($_FILES['file-upload-video']['tmp_name'], $contentPath)) {
            $contentFile = $contentPath; 
        }
    } elseif ($contentType === 'document' && !empty($_FILES['file-upload-document']['name'])) {
        $docDir = '../uploads/documents/';
        $contentPath = $docDir . basename($_FILES['file-upload-document']['name']);
        if (move_uploaded_file($_FILES['file-upload-document']['tmp_name'], $contentPath)) {
            $contentFile = $contentPath;
        }
    }

    try {
        $result = $course->addCourse($title, $description, $price, $difficulty, $duration, $thumbnail, $categoryId, $tags, $contentType, $contentFile);

        if ($result) {
            echo "Course added successfully!";
        } else {
            echo "Failed to add course.";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

$db->closeConnection();
?>
