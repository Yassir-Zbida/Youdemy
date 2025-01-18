<?php
require_once '../classes/course.php';
require_once '../classes/db.php';

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Instructor') {
    header('Location: ../index.php');
    exit();
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $courseId = $_GET['id'];

    $db = new Database();
    $course = new Course($db);

    try {
        $result = $course->deleteCourse($courseId);

        if ($result) {
            $_SESSION['course_message'] = 'Course deleted successfully!';
        } else {
            $_SESSION['course_message'] = 'Failed to delete course.';
        }
    } catch (Exception $e) {
        $_SESSION['course_message'] = 'Error: ' . $e->getMessage();
    }

    header('Location: ./instructor_dashboard.php');
    exit();
} else {
    $_SESSION['course_message'] = 'Invalid course ID.';
    header('Location: ./instructor_dashboard.php');
    exit();
}
?>
