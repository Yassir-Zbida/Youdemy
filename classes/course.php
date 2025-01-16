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

        $query = "SELECT c.*, 
                     cat.name AS category_name, 
                     COUNT(e.studentId) AS student_count
              FROM courses c
              LEFT JOIN categories cat ON c.categoryId = cat.id
              LEFT JOIN enrollment e ON e.courseId = c.id
              WHERE c.id = '$id'
              GROUP BY c.id, c.title, c.description, c.price, c.categoryId, 
                       c.thumbnail, c.content, c.videoUrl, c.createdDate, 
                       c.instructorId, c.Difficulty, c.Duration, cat.name";
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