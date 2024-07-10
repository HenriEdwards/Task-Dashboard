<?php
class Database {
  // Define private properties for the database connection details
  private $servername = "localhost";
  private $username = "root";
  private $password = "";
  private $dbname = "task_manager";
  private $conn;

  // Private constructor to prevent direct instantiation of the class
  private function __construct() {
    // Call the private connect() method to establish the database connection
    $this->connect();
  }

  // Private method to establish the database connection
  private function connect() {
    try {
      // Create a new PDO instance to connect to the database
      $this->conn = new PDO("mysql:host=$this->servername;dbname=$this->dbname", $this->username, $this->password);
      // Set the PDO error mode to exception, so that exceptions are thrown for errors
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      // echo "Connected successfully";
    } catch(PDOException $e) {
      // If there's an error, echo the error message
      // echo "Connection failed: " . $e->getMessage();
    }
  }

  // Public method to retrieve the database connection
  public static function getConnection() {
    // Check if an instance of the Database class already exists
    if (self::$instance === null) {
      // If not, create a new instance and store it in the static $instance property
      self::$instance = new self();
    }
    // Return the database connection object from the Database instance
    return self::$instance->conn;
  }
  // Static property to store the single instance of the Database class
  private static $instance = null;
}