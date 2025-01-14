<?php
require_once(__DIR__ . '/db.php'); 
require_once(__DIR__ . '/db.php');

abstract class User {
    protected $db;
    protected $id;
    protected $username;
    protected $email;
    protected $passwordHash;
    protected $role;

    public function __construct($db) {
        $this->db = $db;
    }

    abstract public function register($username, $email, $password);

    public function login($email, $password) {
        
    }

    public function logout() {
        session_destroy();
    }
}
?>
