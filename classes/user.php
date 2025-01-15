<?php
require_once(__DIR__ . '/db.php');
require_once(__DIR__ . '/student.php');
require_once(__DIR__ . '/instructor.php');
require_once(__DIR__ . '/admin.php');

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
        switch ($role) {
            case 'Admin':
                return [
                    ['Dashboard', './pages/dashboard.php'],
                    ['Categories', './pages/categories.php'],
                    ['Users', './pages/users.php'],
                    ['Statistics', './pages/statistics.php'],
                    ['Tags', './pages/tags.php'],
                ];
            case 'Student':
                return [
                    ['Home', './index.php'],
                    ['Courses', './pages/courses.php'],
                    ['My Courses', './pages/my_courses.php'],
                    ['Help Center', './pages/contact.php'],
                ];
            case 'Instructor':
                return [
                    ['Home', './index.php'],
                    ['My Courses', './pages/my_courses.php'],
                    ['Statistics', './pages/statistics.php'],
                    ['Help Center', './pages/contact.php'],
                ];
            default:
                return [
                    ['Home', './index.php'],
                    ['Courses', './pages/courses.php'],
                    ['Pricing', './pages/pricing.php'],
                    ['Features', './pages/features.php'],
                    ['Blog', './blog.php'],
                    ['Help Center', './pages/contact.php'],
                ];
        }
    }
}
?>