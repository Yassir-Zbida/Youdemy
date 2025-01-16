<?php
require_once(__DIR__ . '/db.php');
require_once(__DIR__ . '/user.php');

class Student extends User {

    private $myCourses = [];
    private function setMyCourses(array $courses) {
        $this->myCourses = $courses;
    }
    public function getMyCourses() {
        return $this->myCourses;
    }

    public function performAction(){
        return "Test" ;
    }

    public function __construct($db) {
        $this->db = $db;
        $this->role = 'Student';
    }

    public function setProperties($user_id , $username , $email) {
        $this->id = $user_id;
        $this->username = $username;
        $this->email = $email;
    }

    public function getUsername(){
        return $this->username;
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
            return "Username '$username' is already taken";
        }
    
        if ($emailExists > 0) {
            return "Email '$email' is already in use";
        }
    
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    
        $stmt = $connection->prepare("INSERT INTO users (username, email, passwordHash, role, status) VALUES (?, ?, ?, 'Student', 'activated')");
        $stmt->bind_param("sss", $username, $email, $passwordHash);
    
        if ($stmt->execute()) {
            return true;
        } else {
            return "There was an error registering the user";
        }
    }

    public function enroll($studentId, $courseId) {
        $connection = $this->db->getConnection();
    
        $checkQuery = "SELECT * FROM enrollment WHERE studentId = ? AND courseId = ?";
        $stmt = $connection->prepare($checkQuery);
        $stmt->bind_param("ii", $studentId, $courseId);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            return "You are already enrolled in this course"; 
        }
    
        $insertQuery = "INSERT INTO enrollment (studentId, courseId, enrollmentDate) VALUES (?, ?, NOW())";
        $stmt = $connection->prepare($insertQuery);
        $stmt->bind_param("ii", $studentId, $courseId);
    
        if ($stmt->execute()) {
            return true; 
        } else {
            return "Failed to enroll the student: " . $stmt->error;
        }
    }

    public function getEnrolledCourses($studentId) {
        $connection = $this->db->getConnection(); 
        $query = "SELECT courses.*, users.username AS instructorName 
              FROM courses
              JOIN enrollment ON courses.id = enrollment.courseId
              JOIN users ON courses.instructorId = users.id
              WHERE enrollment.studentId = ?";
    
        if ($stmt = $connection->prepare($query)) { 
            $stmt->bind_param("i", $studentId);
            $stmt->execute();
    
            $result = $stmt->get_result();
            $courses = [];
    
            while ($course = $result->fetch_assoc()) {
                $courses[] = $course;
            }
    
            $this->setMyCourses($courses);
            $stmt->close();
        } else {
            throw new Exception("Failed to prepare the query: " . $connection->error);
        }
    }
    
 
}
?>
