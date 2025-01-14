<?php
require_once(__DIR__ . '/db.php');
require_once(__DIR__ . '/user.php');

class Admin extends User {

    public function __construct($db, $id, $username, $email) {
        $this->db = $db;
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->role = 'Admin';
    }

    public function register($username, $email, $password) {}

}
?>
