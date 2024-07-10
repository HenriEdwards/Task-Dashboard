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

      // Return success response
      echo json_encode(['success' => $result]);
    } else {
      // Return fail response
      echo json_encode(['success' => $result]);
    }
  }
  
  public function register($username, $password) {

    $result = $this->userModel->register($username, $password);
    echo json_encode(['success' => $result]);
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