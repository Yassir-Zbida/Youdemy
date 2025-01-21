<?php
require_once '../classes/course.php';
require_once '../classes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $courseId = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $difficulty = $_POST['difficulty'];
    $duration = $_POST['duration'];
    $categoryId = $_POST['category'];
    $tags = isset($_POST['tags']) ? $_POST['tags'] : [];
    $contentType = $_POST['content-type'];

    $thumbnail = null;
    if (isset($_FILES['file-upload-thumbnail']) && $_FILES['file-upload-thumbnail']['error'] === UPLOAD_ERR_OK) {
        $thumbnail = $_FILES['file-upload-thumbnail']['tmp_name'];
        $thumbnailDir = '../uploads/thumbnails/';
        $thumbnailFileName = basename($_FILES['file-upload-thumbnail']['name']);
        $thumbnailPath = $thumbnailDir . $thumbnailFileName;
        move_uploaded_file($thumbnail, $thumbnailPath);
        $thumbnail = $thumbnailPath;  
    }

    $contentFile = null;
    if ($contentType === 'document' && isset($_FILES['file-upload-document']) && $_FILES['file-upload-document']['error'] === UPLOAD_ERR_OK) {
        $contentFile = $_FILES['file-upload-document']['tmp_name'];
        $docDir = '../uploads/documents/';
        $contentFileName = basename($_FILES['file-upload-document']['name']);
        $contentPath = $docDir . $contentFileName;
        move_uploaded_file($contentFile, $contentPath);
        $contentFile = $contentPath;
    } elseif ($contentType === 'video' && isset($_FILES['file-upload-video']) && $_FILES['file-upload-video']['error'] === UPLOAD_ERR_OK) {
        $videoFile = $_FILES['file-upload-video'];

        if ($videoFile['error'] === UPLOAD_ERR_OK) {
            $contentFile = $videoFile['tmp_name'];
            $videoDir = '../uploads/videos/';
            $contentFileName = basename($videoFile['name']);
            $contentPath = $videoDir . $contentFileName;
            
            if (move_uploaded_file($contentFile, $contentPath)) {
                $contentFile = $contentPath; 
                echo "Video uploaded successfully: " . $contentFileName . "<br>"; 
            } else {
                echo "Error uploading video file.<br>"; 
            }
        } else {
            echo "Video upload error: " . $videoFile['error'] . "<br>"; 
        }
    }
    

    try {
        $course = new Course();
        $updateSuccess = $course->editCourse($courseId, $title, $description, $price, $difficulty, $duration, $thumbnail, $categoryId, $tags, $contentType, $contentFile);
    
        if ($updateSuccess) {
            $_SESSION['alert_message'] = 'Course updated successfully';
            header('Location: ./instructor_dashboard.php');
            exit;
        }
    } catch (Exception $e) {
        $_SESSION['alert_message'] = 'Error updating course: ' . $e->getMessage();
        header('Location: ./instructor_dashboard.php');
        exit;
    }
}
?>
