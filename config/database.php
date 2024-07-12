<?php
class Database {
  private $servername = "localhost";
  private $username = "root";
  private $password = "";
  private $dbname = "task_manager";
  private $conn;

  private function __construct() {
    $this->connect();
  }

  // Function to establish the database connection
  private function connect() {
    try {
      $this->conn = new PDO("mysql:host=$this->servername;dbname=$this->dbname", $this->username, $this->password);
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
      echo "Connection failed: " . $e->getMessage();
    }
  }

  // Public method to retrieve the database connection
  public static function getConnection() {
    // Check if an instance exist
    if (self::$instance === null) {
      self::$instance = new self();
    }
    // Return database conn object
    return self::$instance->conn;
  }
  private static $instance = null;
}