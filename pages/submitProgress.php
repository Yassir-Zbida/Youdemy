<?php
session_start();
require_once '../classes/course.php';
require_once '../classes/db.php';

if (!isset($_SESSION['user_id'])) {
    die("<script>alert('You must be logged in to update progress.'); window.history.back();</script>");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $courseId = intval($_POST['courseId']);
    $studentId = intval($_SESSION['user_id']);
    $completionRate = $_POST['progress'];

    $validRates = ['0', '25', '50', '75', '100'];
    if (!in_array($completionRate, $validRates)) {
        die("<script>alert('Invalid progress value.'); window.history.back();</script>");
    }

    $db = new Database();
    $course = new Course($db->getConnection());

    if ($course->updateCompletionRate($courseId, $studentId, $completionRate)) {
        echo "<script>alert('Progress updated successfully.'); window.location.href='course-view.php?id=$courseId';</script>";
    } else {
        echo "<script>alert('Failed to update progress.'); window.history.back();</script>";
    }

    $db->closeConnection();
} else {
    echo "<script>alert('Invalid request method.'); window.history.back();</script>";
}
?>

