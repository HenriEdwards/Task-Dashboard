
<?php
require_once "../config/database.php";

class User {
  // Register a new user
  public function register($username, $password) {
    // Get database connection
    $dbo = Database::getConnection();
  
    try {
      // Preprae sql statement to check if username already exists
      $stmt = $dbo->prepare("
      SELECT * FROM users 
      WHERE username = :username");
      $stmt->bindParam(':username', $username);
    
      // Execute username select statement
      if ($stmt->execute()) {
        $rowsAffected = $stmt->rowCount();
  
        if ($rowsAffected > 0) {
          return [
            "response" => false,
            "error" => "Username already registered."
          ];
        } else {
          // Username doesn't exist, encrypt password
          $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
  
          // Create new user
          $stmt = $dbo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
          $stmt->bindParam(':username', $username);
          $stmt->bindParam(':password', $hashedPassword);
  
          if ($stmt->execute()){
            $rowsAffected = $stmt->rowCount();
  
            if ($rowsAffected > 0) {
              // User successfully registered
              return [
                "response" => true
              ];
            } else {
              return [
                "response" => false,
                "error" => "Error occurred while executing statement."
              ];
            }
          } else {
            return [
              "response" => false,
              "error" => "Error occurred while executing statement."
            ];
          }
        }
      } else {
        return [
          "response" => false,
          "error" => "Error occurred while executing statement."
        ];
      }
    } catch (Exception $e) {
      return [
        "response" => false,
        "error" => "Internal server error: " . $e->getMessage()
      ];
    }
  }
  
  public function login($username, $password) {
    // Get database connection
    $dbo = Database::getConnection();
  
    try {
      // Prepare sql statement
      $stmt = $dbo->prepare("
      SELECT * FROM users 
      WHERE username = :username");
      $stmt->bindParam("username", $username);
  
      if ($stmt->execute()) {
        $rowsAffected = $stmt->rowCount();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
  
        // Check if username exists
        if ($rowsAffected > 0) {
          // User exists, compare provided password with stored hash
          if (is_array($result) && password_verify($password, $result["password"])) {
            // username & password match
            return [
              "response" => true,
              "userID" => $result["user_ID"]
            ];
          } else {
            // Password doesn't match
            return [
              "response" => false,
              "error" => "Invalid username or password."
            ];
          }
        } else {
          // Username doesn't exist
          return [
            "response" => false,
            "error" => "Invalid username or password."
          ];
        }
      } else {
        return [
          "response" => false,
          "error" => "Error occurred while executing statement."
        ];
      }
    } catch (Exception $e) {
      return [
        "response" => false,
        "error" => "Internal server error: " . $e->getMessage()
      ];
    }
  }
}