<?php
class Database {
  // Define private properties for the database connection details
  private $servername = "localhost";
  private $username = "root";
  private $password = "";
  private $dbname = "task_manager";
  private $conn;

  private function __construct() {
    // Call connect() method to establish the database connection
    $this->connect();
  }

  // Function to establish the database connection
  private function connect() {
    try {
      // Create a new PDO instance 
      $this->conn = new PDO("mysql:host=$this->servername;dbname=$this->dbname", $this->username, $this->password);
      // Set the PDO error mode to exception
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      // echo "Connected successfully";
    } catch(PDOException $e) {
      echo "Connection failed: " . $e->getMessage();
    }
  }

  // Public method to retrieve the database connection
  public static function getConnection() {
    // Check if an instance of the Database class already exists
    if (self::$instance === null) {
      // If not, create a new instance and store it in $instance
      self::$instance = new self();
    }
    // Return database connection object from the Database instance
    return self::$instance->conn;
  }
  // Static property to store the single instance of Database instance
  private static $instance = null;
}