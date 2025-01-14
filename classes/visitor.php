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

    public function setProperties($user_id , $username , $email) {
        $this->id = $user_id;
        $this->username = $username;
        $this->email = $email;
    }

    public function getUsername(){
        return $this->username;
    }

    
    
}
?>
