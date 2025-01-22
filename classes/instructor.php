<?php
require_once(__DIR__ . '/db.php');
require_once(__DIR__ . '/user.php');

class Instructor extends User {

    public function performAction(){
        return "action" ;
    }

    public function __construct($db) {
        $this->db = $db;
        $this->role = 'Instructor';
    }

    public function register($username, $email, $password) {
        $connection = $this->db->getConnection();
    
        $usernameExists = 0; 
        $emailExists = 0;
    
        $stmt = $connection->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($usernameExists); 
        $stmt->fetch();
        $stmt->close();
    
        $stmt = $connection->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($emailExists); 
        $stmt->fetch();
        $stmt->close();
    
        if ($usernameExists > 0) {
            return "Username '$username' is already taken.";
        }
    
        if ($emailExists > 0) {
            return "Email '$email' is already in use.";
        }
    
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    
        $stmt = $connection->prepare("INSERT INTO users (username, email, passwordHash, role, status) VALUES (?, ?, ?, 'Instructor', 'pending')");
        $stmt->bind_param("sss", $username, $email, $passwordHash);
    
        if ($stmt->execute()) {
            return true;
        } else {
            return "There was an error registering the user";
        }
    }
    
    public function getCoursesCount($instructorId) {
        $connection = $this->db->getConnection();
        $courseCount = 0; 
    
        $stmt = $connection->prepare("SELECT COUNT(*) FROM courses WHERE instructorId = ?");
        if ($stmt) {
            $stmt->bind_param("i", $instructorId);
            $stmt->execute();
            $stmt->bind_result($courseCount);
            $stmt->fetch();
            $stmt->close();
        }
    
        return $courseCount;
    }

    public function getEnrolledStudentsCount($instructorId) {
        $connection = $this->db->getConnection();
        $enrolledStudentsCount = 0; 
    
        $query = "
            SELECT COUNT(e.studentId) AS total_enrolled_students
            FROM enrollment e
            INNER JOIN courses c ON e.courseId = c.id
            WHERE c.instructorId = ?
        ";
        $stmt = $connection->prepare($query);
        if ($stmt) {
            $stmt->bind_param("i", $instructorId);
            $stmt->execute();
            $stmt->bind_result($enrolledStudentsCount);
            $stmt->fetch();
            $stmt->close();
        }
    
        return $enrolledStudentsCount;
    }

    public function getCoursesWithDetails($instructorId) {
        $connection = $this->db->getConnection();
        $courses = [];
    
        $query = "
            SELECT 
                c.id, 
                c.title, 
                cat.name AS category, 
                COUNT(DISTINCT e.studentId) AS students, 
                c.status
            FROM courses c
            LEFT JOIN categories cat ON c.categoryId = cat.id
            LEFT JOIN enrollment e ON e.courseId = c.id
            WHERE c.instructorId = ?
            GROUP BY c.id
        ";
        $stmt = $connection->prepare($query);
        if ($stmt) {
            $stmt->bind_param("i", $instructorId);
            $stmt->execute();
            $result = $stmt->get_result();
    
            while ($row = $result->fetch_assoc()) {
                $courses[] = $row;
            }
    
            $stmt->close();
        }
    
        return $courses;
    }

    public function getCompletionRate($instructorId) {
        $connection = $this->db->getConnection();
        $averageCompletionRate = 0;
    
        $query = "
            SELECT AVG(e.completionRate) AS avgCompletionRate
            FROM enrollment e
            INNER JOIN courses c ON e.courseId = c.id
            WHERE c.instructorId = ?
        ";
    
        $stmt = $connection->prepare($query);
        if ($stmt) {
            $stmt->bind_param("i", $instructorId);
            $stmt->execute();
            $stmt->bind_result($averageCompletionRate);
            $stmt->fetch();
            $stmt->close();
        }
        if ($averageCompletionRate === null) {
            $averageCompletionRate = 0; 
        }
    
        return round($averageCompletionRate, 2);
    }
    
       
}
?>

