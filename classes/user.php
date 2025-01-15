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

    public function __construct($db)
    {
        $this->db = $db;
    }


    abstract public function performAction();
    // public function register($username, $email, $password);


    public static function login($db, $email, $password)
    {
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['passwordHash'])) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];

                switch ($user['role']) {

                    case 'Student':
                        header("Location: ./mycourses.php");
                        return new Student($db, $user['id'], $user['username'], $user['email']);

                    case 'Instructor':
                        header("Location: ./instructor_dashboard.php");
                        return new Instructor($db, $user['id'], $user['username'], $user['email']);

                    case 'Admin':
                        header("Location: ./admin_dashboard.php");
                        return new Admin($db, $user['id'], $user['username'], $user['email']);

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


    public function logout()
    {
        session_destroy();
    }
}
?>