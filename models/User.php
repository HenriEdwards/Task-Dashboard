
<?php
require_once "../config/database.php";

class User {
  // Register a new user
  public function register($username, $password) {
    $dbo = Database::getConnection();

    try {
      // Check if username already exists
      $stmt = $dbo->prepare("SELECT * FROM users WHERE username = :username");
      $stmt->bindParam(':username', $username);
  
      if ($stmt->execute()) {
        $rowsAffected = $stmt->rowCount();

        if ($rowsAffected > 0) {
          return "user exists";

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
              // user successfully registered
              return true;
              
            } else {
              // user not registered for some reason
              return false;
            }
          } else {
            return 'error occurred while executing statement';
          }
        }
      } else {
        return 'error occurred while executing statement';
      }
    } catch (Exception $e) {
      return $e;
    }
  }

  public function login($username, $password) {
    $dbo = Database::getConnection();

    try {
      // Check if username exists
      $stmt = $dbo->prepare("SELECT * FROM users WHERE username = :username");
      $stmt->bindParam("username", $username);

      if ($stmt->execute()) {
        $rowsAffected = $stmt->rowCount();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
  
        if ($rowsAffected > 0) {
          // User exists, compare provided password with stored hash
          if (is_array($result) && password_verify($password, $result["password"])) {
            // username & password matches
            return true;
          } else {
            // Password doesnt match
            return false;
          }
        } else {
          // Username doesnt match
          return false;
        }
      } else {
        return "Error executing login";
      }
    } catch (Exception $e) {
      return "Error occurred while logging in: " . $e->getMessage();
    }
  }
}