<?php
require_once(__DIR__ . '/db.php');
require_once(__DIR__ . '/student.php');
require_once(__DIR__ . '/instructor.php');
require_once(__DIR__ . '/admin.php');
define('BASE_PATH', '/');

abstract class User
{
    protected $db;
    protected $id;
    protected $username;
    protected $email;
    protected $passwordHash;
    protected $role;
    protected $status;

    public function __construct($db)
    {
        $this->db = $db;
    }


    abstract public function performAction();


    public static function login($db, $email, $password)
{
    session_start();

    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $db->prepare($query);
    
    if ($stmt === false) {
        throw new Exception("Error preparing the statement: " . $db->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['passwordHash'])) {

            switch ($user['role']) {
                case 'Student':
                    if ($user['status'] === 'pending') {
                        header("Location: ./pending_status.php");
                        exit;
                    } elseif ($user['status'] === 'suspended') {
                        header("Location: ./suspended_status.php");
                        exit;
                    } elseif ($user['status'] === 'activated') {
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['role'] = $user['role'];
                        header("Location: ./mycourses.php");
                        exit; 
                    } else {
                        throw new Exception("Unknown status: " . $user['status']);
                    }

                case 'Instructor':
                    if ($user['status'] === 'pending') {
                        header("Location: ./pending_status.php");
                        exit;
                    } elseif ($user['status'] === 'suspended') {
                        header("Location: ./suspended_status.php");
                        exit;
                    } elseif ($user['status'] === 'activated') {
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['role'] = $user['role'];
                        header("Location: ./instructor_dashboard.php");
                        exit; 
                    } else {
                        throw new Exception("Unknown status: " . $user['status']);
                    }

                case 'Admin':
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['role'] = $user['role'];
                    header("Location: ./admin_dashboard.php");
                    exit; 
                default:
                    throw new Exception("Unknown role: " . $user['role']);
            }

        } else {
            throw new Exception("Invalid password");
        }
    } else {
        throw new Exception("Invalid email");
    }
    }

    



    public static function logout()
    {
        session_start();
        session_unset();
        session_destroy();

        header("Location: ../index.php");
        exit();
    }

    public static function isLoggedIn()
    {
        session_start(); 

        if (!isset($_SESSION['user_id'])) {
            header("Location: ../pages/login.php");
            exit();
        }
    }

    public static function redirectIfLoggedIn()
    {
        session_start();

        if (isset($_SESSION['user_id'])) {
            // If logged in, redirect to index.php
            header("Location: ../index.php");
            exit();
        }
    }

    public static function getMenuItems($role)
{
    $basePath = BASE_PATH;
    switch ($role) {
        case 'Admin':
            return [
                ['Dashboard', "{$basePath}pages/dashboard.php"],
                ['Categories', "{$basePath}pages/categories.php"],
                ['Users', "{$basePath}pages/users.php"],
                ['Statistics', "{$basePath}pages/statistics.php"],
                ['Tags', "{$basePath}pages/tags.php"],
            ];
        case 'Student':
            return [
                ['Home', "{$basePath}index.php"],
                ['Courses', "{$basePath}pages/courses.php"],
                ['My Courses', "{$basePath}pages/mycourses.php"],
                ['Help Center', "{$basePath}pages/contact.php"],
            ];
        case 'Instructor':
            return [
                ['Home', "{$basePath}index.php"],
                ['My Courses', "{$basePath}pages/my_courses.php"],
                ['Statistics', "{$basePath}pages/statistics.php"],
                ['Help Center', "{$basePath}pages/contact.php"],
            ];
        default:
            return [
                ['Home', "{$basePath}index.php"],
                ['Courses', "{$basePath}pages/courses.php"],
                ['Pricing', "{$basePath}pages/pricing.php"],
                ['Features', "{$basePath}pages/features.php"],
                ['Blog', "{$basePath}blog.php"],
                ['Help Center', "{$basePath}pages/contact.php"],
            ];
    }
}

}
?>