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

    public function createCategory($name) {
        $stmt = $this->db->prepare('INSERT INTO categories (name) VALUES (?)');
        $stmt->bind_param('s', $name); 
        return $stmt->execute();
    }

    public function getCategories() {
        $stmt = $this->db->prepare('SELECT * FROM Categories');
        $stmt->execute();
        $result = $stmt->get_result(); 
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function deleteCategory($id) {
        $stmt = $this->db->prepare('DELETE FROM categories WHERE id = ?');
        $stmt->bind_param('i', $id); 
        return $stmt->execute();
    }
}


?>