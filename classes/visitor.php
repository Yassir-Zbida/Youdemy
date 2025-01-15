<?php
require_once(__DIR__ . '/db.php');
require_once(__DIR__ . '/user.php');

class  Visitor extends User {

    public function performAction(){
        return "hey" ;
    }

    public function __construct($db) {
        $this->db = $db;
        $this->role = 'Student';
    }

    public function getUsername(){
        return $this->username;
    }

    
    
}
?>
