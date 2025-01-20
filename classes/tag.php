<?php

class Tag {
    private $id;
    private $name;
    private $db;

    public function __construct($db, $id = null, $name = '') {
        $this->db = $db; 
        $this->id = $id;
        $this->name = $name;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }
    

    public function createTag($name) {
        $stmt = $this->db->prepare('INSERT INTO tags (name) VALUES (?)');
        if ($stmt) {
            $stmt->bind_param('s', $name);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        } else {
            return false; 
        }
    }

    public function getTags() {
        $stmt = $this->db->prepare('SELECT * FROM Tags'); 
        if ($stmt) {
            $stmt->execute();
            $result = $stmt->get_result(); 
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return []; 
        }
    }
    

    public function deleteTag($id) {
        $stmt = $this->db->prepare('DELETE FROM tags WHERE id = ?');
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
