<?php

class Category {
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

    public function getname() {
        return $this->name;
    }

    public function setname($name) {
        $this->name = $name;
    }

    public function createCategory($name) {
        $stmt = $this->db->prepare('INSERT INTO Category (name) VALUES (?)');
        return $stmt->execute([$name]);
    }

  
public function getCategories() {
    
    $stmt = $this->db->prepare('SELECT * FROM Category ORDER BY RAND() LIMIT 6');
    $stmt->execute();
    return $stmt->fetchAll(db::FETCH_ASSOC);}
   

    public function deleteCategory($id) {
        $stmt = $this->db->prepare('DELETE FROM Category WHERE id = ?');
        return $stmt->execute([$id]);
    }
}

?>