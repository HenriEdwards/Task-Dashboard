<?php

$root = $_SERVER["DOCUMENT_ROOT"];
include_once $root."/Task-Dashboard/models/user.php";

class UserController {

  private $userModel;

  public function __construct () {
    $this->userModel = new User;
  }

  public function login($username, $password) {
    $result = $this->userModel->login($username, $password);
  
    if ($result) {
      // Set the user's session data
      session_start();
      $_SESSION['username'] = $username;
    }
  
    // Return response as JSON
    $response = json_encode(['success' => $result]);
    echo $response;
    die();
  }
  
  public function register($username, $password) {

    $result = $this->userModel->register($username, $password);
    
    // Return response as JSON
    $response = json_encode(['success' => $result]);
    echo $response;
    die();
  }
}

$init = new UserController;

$username = $_POST["username"];
$password = $_POST["password"];

// Perform necessary actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  switch($_POST["type"]) {
    case 'login':
      $init->login($username, $password);
      break;
    case 'register':
      $init->register($username, $password);
      break;
  }
}