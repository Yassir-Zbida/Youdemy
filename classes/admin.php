<?php
require_once(__DIR__ . '/db.php');
require_once(__DIR__ . '/user.php');

class Admin extends User {


    public function performAction(){
        return "hey" ;
    }

    public function __construct($db, $id) {
        $this->db = $db;
        $this->id = $id;
        $this->role = 'Admin';
    }

    public function register($username, $email, $password) {}

    public function getUsers() {
        $stmt = $this->db->prepare('SELECT id, username, email, role, status FROM users');
        if ($stmt) {
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC); 
        } else {
            return [];
        }
    }

    public function updateUserStatus($userId, $status) {
        $validStatuses = ['pending', 'suspended', 'activated'];
        
        if (!in_array($status, $validStatuses)) {
            return false;  
        }

        $stmt = $this->db->prepare('UPDATE users SET status = ? WHERE id = ?');
        if ($stmt) {
            $stmt->bind_param('si', $status, $userId);
            $result = $stmt->execute();
            $stmt->close();
            return $result;  
        } else {
            return false; 
        }
    }

    public function deleteUser($id) {
        $stmt = $this->db->prepare('DELETE FROM users WHERE id = ?');
        if ($stmt) {
            $stmt->bind_param('i', $id); 
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        } else {
            return false; 
        }
    } 

    public function countStudents() {
        $query = "SELECT COUNT(*) AS student_count FROM users WHERE role = 'Student'";
        $result = $this->db->getConnection()->query($query);
    
        if ($result && $row = $result->fetch_assoc()) {
            return $row['student_count'];
        }
    
        return 0;
    }

    public function countCourses(){
        $query = "SELECT COUNT(*) AS courses_count FROM courses WHERE status = 'Published'";
        $result = $this->db->getConnection()->query($query);
    
        if ($result && $row = $result->fetch_assoc()) {
            return $row['courses_count'];
        }
    
        return 0;
    }

    public function getNewUserRate(): float {
        $currentMonth = date('Y-m');
        $queryTotal = "SELECT COUNT(*) AS total_users FROM users";
        $queryNew = "SELECT COUNT(*) AS new_users FROM users WHERE DATE_FORMAT(created_at, '%Y-%m') = '$currentMonth'";
    
        $totalResult = $this->db->getConnection()->query($queryTotal);
        $newResult = $this->db->getConnection()->query($queryNew);
    
        if ($totalResult && $newResult) {
            $totalUsers = $totalResult->fetch_assoc()['total_users'] ?? 0;
            $newUsers = $newResult->fetch_assoc()['new_users'] ?? 0;
    
            if ($totalUsers > 0) {
                return ($newUsers / $totalUsers) * 100;
            }
        }
    
        return 0;
    }

    public function getNewCoursesRate() {
        $currentMonth = date('Y-m'); 
        $lastMonth = date('Y-m', strtotime('-1 month'));
    
        $query = "SELECT COUNT(*) as total FROM courses WHERE DATE_FORMAT(createdDate, '%Y-%m') = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $currentMonth);
        $stmt->execute();
        $result = $stmt->get_result();
        $currentMonthCourses = $result->fetch_assoc()['total'] ?? 0;
    
        $query = "SELECT COUNT(*) as total FROM courses WHERE DATE_FORMAT(createdDate, '%Y-%m') = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $lastMonth);
        $stmt->execute();
        $result = $stmt->get_result();
        $lastMonthCourses = $result->fetch_assoc()['total'] ?? 0;
    
        if ($lastMonthCourses == 0) {
            if ($currentMonthCourses > 0) {
                return 100.0; 
            }
            return 0.0; 
        }
        $growthRate = (($currentMonthCourses - $lastMonthCourses) / $lastMonthCourses) * 100;
        
        return round($growthRate, 1);
    }

    public function getAverageCompletionRate() {
        $query = "SELECT AVG(completionRate) AS averageRate FROM enrollment";
        $stmt = $this->db->prepare($query); 
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row['averageRate'];
        }
        return null; 
    }

    public function getTotalEnrollment() {
        $query = "SELECT COUNT(*) AS totalEnrollments FROM enrollment";
        $result = $this->db->getConnection()->query($query);
    
        if ($result && $row = $result->fetch_assoc()) {
            return $row['totalEnrollments']; 
        }
    
        return 0; 
    }

    public function getTopInstructors($limit = 3) {
        $query = "
            SELECT 
                users.id AS instructor_id,
                users.username AS instructor_name,
                users.avatarImg AS profile_image,
                users.bio AS specialization,
                COUNT(enrollment.enrollmentId) AS enrollment_count
            FROM 
                users
            JOIN 
                courses ON users.id = courses.instructorId
            JOIN 
                enrollment ON courses.id = enrollment.courseId
            WHERE 
                users.role = 'Instructor'
            GROUP BY 
                users.id
            ORDER BY 
                enrollment_count DESC
            LIMIT ?
        ";
    
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $limit);
        $stmt->execute();
        $result = $stmt->get_result();
    
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getTopCourses($limit = 2) {
        $query = "
            SELECT 
                courses.id AS course_id,
                courses.title AS course_name,
                COUNT(enrollment.enrollmentId) AS enrollment_count,
                courses.price,
                courses.thumbnail,
                (SELECT COUNT(*) FROM enrollment WHERE courseId = courses.id) AS sales_percentage
            FROM 
                courses
            LEFT JOIN 
                enrollment ON courses.id = enrollment.courseId
            WHERE 
                courses.status = 'Published'
            GROUP BY 
                courses.id
            ORDER BY 
                enrollment_count DESC
            LIMIT ?
        ";
    
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $limit);
        $stmt->execute();
        $result = $stmt->get_result();
    
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    
    public function getRecentEnrollments($limit = 10) {
        $query = "
            SELECT 
                users.username AS student_name,
                users.email AS student_email,
                users.avatarImg AS student_avatar,
                courses.title AS course_name,
                 courses.price AS course_price,
                enrollment.completionRate AS progress,
                enrollment.enrollmentDate AS enrollment_date
            FROM 
                enrollment
            JOIN 
                users ON enrollment.studentId = users.id
            JOIN 
                courses ON enrollment.courseId = courses.id
            ORDER BY 
                enrollment.enrollmentDate DESC
            LIMIT ?
        ";
    
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $limit);
        $stmt->execute();
        $result = $stmt->get_result();
    
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    
    
    
    
    
    
    
    

}
?>
