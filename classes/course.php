<?php
require_once(__DIR__ . '/db.php');

class Course {
    private $db;
    private $id;
    private $instructorId;
    private $title;
    private $description;
    private $price;
    private $categoryId;
    private $thumbnail;
    private $content;
    private $videoUrl;

    public function __construct() {
        $this->db = new Database();
    }
    
    // Get all courses
    public function getAllCourses() {
        $connection = $this->db->getConnection();
        $query = "SELECT c.*, cat.name as category_name 
                 FROM courses c 
                 LEFT JOIN categories cat ON c.categoryId = cat.id";
        $result = $connection->query($query);
        
        $courses = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $courses[] = $row;
            }
        }
        return $courses;
    }

    // Get course by ID
    public function getCourseById($id) {
        $connection = $this->db->getConnection();
        $id = $connection->real_escape_string($id);
        
        $query = "SELECT c.*, cat.name as category_name 
                 FROM courses c 
                 LEFT JOIN categories cat ON c.categoryId = cat.id 
                 WHERE c.id = '$id'";
        $result = $connection->query($query);
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
   

    // Add new course
    public function addCourse($title, $description, $price, $categoryId, $thumbnail, $content, $videoUrl) {
        $connection = $this->db->getConnection();
        
        // Sanitize inputs
        $title = $connection->real_escape_string($title);
        $description = $connection->real_escape_string($description);
        $price = $connection->real_escape_string($price);
        $categoryId = $connection->real_escape_string($categoryId);
        $thumbnail = $connection->real_escape_string($thumbnail);
        $content = $connection->real_escape_string($content);
        $videoUrl = $connection->real_escape_string($videoUrl);
        
        $query = "INSERT INTO courses (title, description, price, categoryId, thumbnail, content, videoUrl, createdDate) 
                 VALUES ('$title', '$description', '$price', '$categoryId', '$thumbnail', '$content', '$videoUrl', NOW())";
        
        return $connection->query($query);
    }

    // Update course
    public function updateCourse($id, $title, $description, $price, $categoryId, $thumbnail, $content, $videoUrl) {
        $connection = $this->db->getConnection();
        
        // Sanitize inputs
        $id = $connection->real_escape_string($id);
        $title = $connection->real_escape_string($title);
        $description = $connection->real_escape_string($description);
        $price = $connection->real_escape_string($price);
        $categoryId = $connection->real_escape_string($categoryId);
        $thumbnail = $connection->real_escape_string($thumbnail);
        $content = $connection->real_escape_string($content);
        $videoUrl = $connection->real_escape_string($videoUrl);
        
        $query = "UPDATE courses 
                 SET title = '$title', 
                     description = '$description', 
                     price = '$price', 
                     categoryId = '$categoryId', 
                     thumbnail = '$thumbnail', 
                     content = '$content', 
                     videoUrl = '$videoUrl' 
                 WHERE id = '$id'";
        
        return $connection->query($query);
    }

    public function deleteCourse($id) {
        $connection = $this->db->getConnection();
        $id = $connection->real_escape_string($id);
        
        $query = "DELETE FROM courses WHERE id = '$id'";
        return $connection->query($query);
    }

    public function getCourseStatistics($courseId) {
        $connection = $this->db->getConnection();
        $courseId = $connection->real_escape_string($courseId);
        
        $query = "SELECT * FROM statistics WHERE courseId = '$courseId'";
        $result = $connection->query($query);
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    // Close database connection
    public function __destruct() {
        $this->db->closeConnection();
    }
}
?>