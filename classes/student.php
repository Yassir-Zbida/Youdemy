<?php
require_once(__DIR__ . '/db.php');
require_once(__DIR__ . '/user.php');

class Student extends User {

    public function performAction(){
        return "hey" ;
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
    
        // Check if username exists
        $stmt = $connection->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($usernameExists); 
        $stmt->fetch();
        $stmt->close();
    
        // Check if email exists
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
    
        $stmt = $connection->prepare("INSERT INTO users (username, email, passwordHash, role, status) VALUES (?, ?, ?, 'Student', 'activated')");
        $stmt->bind_param("sss", $username, $email, $passwordHash);
    
        if ($stmt->execute()) {
            return true;
        } else {
            return "There was an error registering the user.";
        }
    }
    

    
}
?>
