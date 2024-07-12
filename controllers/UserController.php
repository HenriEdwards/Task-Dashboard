<?php

$root = $_SERVER["DOCUMENT_ROOT"];
include_once $root."/Task-Dashboard/models/User.php";

class UserController {

  private $userModel;

  // Initialize User model
  public function __construct () {
    $this->userModel = new User;
  }

  // Function to log user in
  public function login($username, $password) {

    // Log user in
    $response = $this->userModel->login($username, $password);

    // Check the response from the UserModel
    if ($response["response"]) {
      // Successful login
      $session_lifetime = 3600; // 1hr

      // Configure relevant session parameters
      session_set_cookie_params([
          'lifetime' => $session_lifetime,
      ]);
      session_start();
      $_SESSION['userID'] = $response['userID'];
      http_response_code(200);
    } else {
      // Failed login due to invalid username/password or user not found
      if (isset($response['error']) && $response['error'] === "Invalid username or password.") {
        // Unauthorized
        http_response_code(401);
      } else {
        // Internal Server Error
        http_response_code(500);
      }
    }

    // Return response as JSON
    $response = json_encode($response);
    echo $response;
    die();
}
  
  // Function to register a user
  public function register($username, $password) {
    // Register user
    $response = $this->userModel->register($username, $password);

    // Check the response from the UserModel
    if ($response["response"]) {
      // Successful registration
      http_response_code(200);
    } else {
      // Failed login due to invalid username/password or user not found
      if (isset($response['error']) && $response['error'] === "Username already registered.") {
        // Unauthorized
        http_response_code(401);
      } else {
        // Internal Server Error
        http_response_code(500);
      }
    }

    // Return response as JSON
    $response = json_encode($response);
    echo $response;
    die();
  }
}

$init = new UserController;

// Perform necessary actions based on request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  switch($_POST["type"]) {
    case 'login':
      $username = $_POST["username"];
      $password = $_POST["password"];
      $init->login($username, $password);
      break;
    case 'register':
      $username = $_POST["username"];
      $password = $_POST["password"];
      $init->register($username, $password);
      break;
  }
}