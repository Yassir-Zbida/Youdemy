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
    $duration = intval($_POST['duration_hours']);
    $categoryId = intval($_POST['category']);

    $tags = [];
    if (isset($_POST['selectedTags']) && !empty($_POST['selectedTags'])) {
        $tagArray = explode(',', $_POST['selectedTags']);
        $tags = array_map('intval', $tagArray);
        $tags = array_filter($tags, function ($tag) {
            return $tag > 0;
        });
    }

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
            $_SESSION['course_message'] = 'Course added successfully';
        } else {
            $_SESSION['course_message'] = 'Failed to add course';
        }
    } catch (Exception $e) {
        $_SESSION['course_message'] = 'Error: ' . $e->getMessage();
    }

    header('Location: ./instructor_dashboard.php');
    exit();
}

$db->closeConnection();

?>