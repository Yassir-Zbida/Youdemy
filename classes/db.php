<?php
class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "yassir10-52";
    private $database = "youdemy";
    private $connection;

    public function __construct() {
        
        $this->connection = new mysqli($this->host, $this->username, $this->password, $this->database);
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    public function prepare($query) {
        return $this->connection->prepare($query);
    }

    public function getConnection() {
        return $this->connection;
    }

    public function closeConnection() {
        $this->connection->close();
    }

    public function __get($name) {
        if ($name === 'insert_id') {
            return $this->connection->insert_id;
        }
        return null;
    }


}

?>