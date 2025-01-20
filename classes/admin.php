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

}
?>
