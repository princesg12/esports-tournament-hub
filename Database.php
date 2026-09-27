<?php
class Database {
    // Database credentials
    private $host = "localhost";
    private $user = "root";
    private $password = ""; // Default XAMPP password is empty
    private $database = "esports_tournament";
    public $conn;

    // Method to establish the database connection
    public function getConnection() {
        $this->conn = null;
        
        // Create a new mysqli object
        $this->conn = new mysqli($this->host, $this->user, $this->password, $this->database);
        
        // Check for connection errors
        if($this->conn->connect_error){
            die("Connection failed: " . $this->conn->connect_error);
        }
        
        return $this->conn;
    }
}
?>