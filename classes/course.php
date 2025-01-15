<?php
require_once(__DIR__ . '/db.php');

class Course
{
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
    private $Duration;
    private $Difficulty;
    private $createdDate;
    private $studentCount;

    public function __construct()
    {
        $this->db = new Database();
    }

    // Get all courses
    public static function browseCourses($db, $limit, $offset)
{
    $connection = $db->getConnection();
    $query = "SELECT 
                c.id AS course_id, 
                c.title, 
                c.description, 
                c.price, 
                c.thumbnail,
                u.username AS instructor_name
              FROM courses c
              LEFT JOIN users u ON c.instructorId = u.id
              LIMIT $limit OFFSET $offset";
    $result = $connection->query($query);

    $courses = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $courses[] = $row;
        }
    }
    return $courses;
}


    public static function countCourses($db)
    {
        $connection = $db->getConnection();
        $query = "SELECT COUNT(*) AS total FROM courses";
        $result = $connection->query($query);

        if ($result->num_rows > 0) {
            return $result->fetch_assoc()['total'];
        }
        return 0;
    }

    public function getCourseById($id)
    {
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

    
    

    

    

    public function __destruct()
    {
        $this->db->closeConnection();
    }
}
?>