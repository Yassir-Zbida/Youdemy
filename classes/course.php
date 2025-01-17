<?php
require_once(__DIR__ . '/db.php');

class Course
{
    protected $db;
    protected $id;
    protected $instructorId;
    protected $title;
    protected $description;
    protected $price;
    protected $categoryId;
    protected $thumbnail;
    protected $content;
    protected $createdDate;
    protected $studentCount;
    protected $Duration;
    protected $Difficulty;

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

    public function getInstructorInfo($instructorId)
    {
        $connection = $this->db->getConnection();

        $query = "SELECT u.username, u.avatarImg, u.bio, u.poste, 
                         COUNT(DISTINCT e.studentId) AS total_students,
                         COUNT(DISTINCT c.id) AS total_courses
                  FROM users u
                  LEFT JOIN courses c ON u.id = c.instructorId
                  LEFT JOIN enrollment e ON c.id = e.courseId
                  WHERE u.id = ?
                  GROUP BY u.id";

        $stmt = $connection->prepare($query);
        $stmt->bind_param("i", $instructorId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return null;
    }

    public function isUserEnrolled($studentId, $courseId)
    {
        $connection = $this->db->getConnection();
        $query = "SELECT COUNT(*) as count FROM enrollment WHERE studentId = ? AND courseId = ?";
        if ($stmt = $connection->prepare($query)) {
            $stmt->bind_param("ii", $studentId, $courseId);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
            $stmt->close();
            return $data['count'] > 0;
        } else {
            throw new Exception("Failed to prepare the query: " . $connection->error);
        }
    }

    public function addCourse()
    {
    }

    public function displayCourse()
    {
    }



    public function __destruct()
    {
        $this->db->closeConnection();
    }
}
?>