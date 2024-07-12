<?php
session_start();

if (!isset($_SESSION['userID'])) {
  header("Location: /Task-Dashboard/index.php");
  exit;
}

// Include the Task model
$root = $_SERVER["DOCUMENT_ROOT"];
include_once $root."/Task-Dashboard/models/Task.php";

// Define the TaskController class
class TaskController {

  private $taskModel;

  public function __construct() {
    $this->taskModel = new Task();
  }

  // Function to get tasks with pagination
  public function getTasks($userId, $page = 1, $limit = 10) {
    $offset = ($page - 1) * $limit;

    $response = $this->taskModel->getTasks($userId, $limit, $offset);
    header('Content-Type: application/json');

    if ($response["response"]) {
      http_response_code(200);
    } else {
      http_response_code(500);
    }
    echo json_encode($response);
    exit;
  }

  // Function to create a new task
  public function createTask($userId, $title, $description, $dueDate, $status) {
    $response = $this->taskModel->createTask($userId, $title, $description, $dueDate, $status);
    header('Content-Type: application/json');

    if ($response["response"]) {
      http_response_code(201);
    } else {
      http_response_code(500); 
    }
    echo json_encode($response);
    exit;
  }

  // Function to update an existing task
  public function updateTask($taskId, $title, $description, $dueDate, $status) {
    $response = $this->taskModel->updateTask($taskId, $title, $description, $dueDate, $status);
    header('Content-Type: application/json');

    if ($response["response"]) {
      http_response_code(200); 
    } else {
      http_response_code(500);
    }
    echo json_encode($response);
    exit;
  }

  // Function to delete a task
  public function deleteTask($taskId) {
    $response = $this->taskModel->deleteTask($taskId);
    header('Content-Type: application/json');

    if ($response["response"]) {
      http_response_code(200);
    } else {
      http_response_code(500); 
    }
    echo json_encode($response);
    exit;
  }
}

// Get the user ID from the session
$userId = $_SESSION['userID'];
$init = new TaskController();

// Perform necessary actions based on request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['type'])) {
  switch ($_POST['type']) {
    case 'createTask':
      $title = $_POST['title'];
      $description = $_POST['description'];
      $dueDate = $_POST['due_date'];
      $status = $_POST['status'];
      $init->createTask($userId, $title, $description, $dueDate, $status);
      break;
    case 'updateTask':
      $taskId = $_POST['task_id'];
      $title = $_POST['title'];
      $description = $_POST['description'];
      $dueDate = $_POST['due_date'];
      $status = $_POST['status'];
      $init->updateTask($taskId, $title, $description, $dueDate, $status);
      break;
    case 'deleteTask':
      $taskId = $_POST['task_id'];
      $init->deleteTask($taskId);
      break;
  }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['type']) && $_GET['type'] === 'getTasks') {
  // Get page number from the GET request or set default to 1
  $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
  $init->getTasks($userId, $page);
}
