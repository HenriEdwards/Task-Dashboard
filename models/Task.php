<?php
require_once "../config/database.php";

class Task {
  // Function to get user specific tasks with pagination
  public function getTasks($userId, $limit, $offset) {
    // Get database connection
    $dbo = Database::getConnection();

    try {
      // Prepare sql statement to fetch tasks
      $stmt = $dbo->prepare("
        SELECT 
          t.task_ID, 
          t.title, 
          t.description, 
          t.due_date,
          s.status_val
        FROM tasks t
        JOIN status s ON t.status_ID = s.status_ID  
        WHERE t.user_ID = :userId
        ORDER BY t.due_date ASC
        LIMIT :limit OFFSET :offset");
      $stmt->bindParam(':userId', $userId);
      $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
      $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

      // Execute get task statement
      if ($stmt->execute()) {
        // Fetch tasks as an associative array
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Get total num of tasks for pagination
        $totalTasks = $this->getTotalTasks($userId);
        $totalPages = ceil($totalTasks / $limit);
        return [
          "response" => true,
          "tasks" => $tasks,
          "totalPages" => $totalPages
        ];
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

  // Function to get total num of tasks for user
  private function getTotalTasks($userId) {
    // Get database connection
    $dbo = Database::getConnection();
    try {
      // Prepare sql statement to count tasks
      $stmt = $dbo->prepare("SELECT COUNT(*) FROM tasks WHERE user_ID = :userId");
      $stmt->bindParam(':userId', $userId);
      // Execute & fetch count
      if ($stmt->execute()) {
        return $stmt->fetchColumn();
      } else {
        return 0;
      }
    } catch (Exception $e) {
      return 0;
    }
  }

  // Function to create a new task
  public function createTask($userId, $title, $description, $dueDate, $status) {
    // Get database connection
    $dbo = Database::getConnection();

    try {
      // Prepare sql statement to get status id
      $stmt = $dbo->prepare("
        SELECT status_ID 
        FROM status 
        WHERE status_val = :status
      ");
      $stmt->bindParam(':status', $status);
      $stmt->execute();

      // Retrieve status
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      if (!$result) {
        return [
          "response" => false,
          "error" => "Invalid status value."
        ];
      }
      $statusID = $result['status_ID'];
      
      // Prepare sql statement to insert new task
      $stmt = $dbo->prepare("
        INSERT INTO tasks (user_ID, title, description, due_date, status_ID)
        VALUES (:userId, :title, :description, :dueDate, :statusId)
      ");
      $stmt->bindParam(':userId', $userId);
      $stmt->bindParam(':title', $title);
      $stmt->bindParam(':description', $description);
      $stmt->bindParam(':dueDate', $dueDate);
      $stmt->bindParam(':statusId', $statusID);

      if ($stmt->execute()) {
        $rowsAffected = $stmt->rowCount();

        if ($rowsAffected > 0) {
          return ["response" => true];
        } else {
          return [
            "response" => false,
            "error" => "Task not created."
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

  // Function to update an existing task
  public function updateTask($taskId, $title, $description, $dueDate, $status) {
    // Get database connection
    $dbo = Database::getConnection();

    try {
      // Prepare sql statement to get status id
      $stmt = $dbo->prepare("
      SELECT status_ID 
      FROM status 
      WHERE status_val = :status
      ");
      $stmt->bindParam(':status', $status);
      $stmt->execute();

      // Retrieve status
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      if (!$result) {
        return [
          "response" => false,
          "error" => "Invalid status value."
        ];
      }
      $statusID = $result['status_ID'];
      // Prepare sql statement to update a task
      $stmt = $dbo->prepare("
        UPDATE tasks
        SET title = :title, description = :description, due_date = :dueDate, status_ID = :statusId
        WHERE task_ID = :taskId
      ");
      $stmt->bindParam(':taskId', $taskId);
      $stmt->bindParam(':title', $title);
      $stmt->bindParam(':description', $description);
      $stmt->bindParam(':dueDate', $dueDate);
      $stmt->bindParam(':statusId', $statusID);

      if ($stmt->execute()) {
        $rowsAffected = $stmt->rowCount();

        if ($rowsAffected > 0) {
          return ["response" => true];
        } else {
          return [
            "response" => false,
            "error" => "No information changed."
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

  // Function to delete a task
  public function deleteTask($taskId) {
    // Get database connection
    $dbo = Database::getConnection();

    try {
      // Prepare sql statement to delete a task
      $stmt = $dbo->prepare("DELETE FROM tasks WHERE task_ID = :taskId");
      $stmt->bindParam(':taskId', $taskId);

      if ($stmt->execute()) {
        $rowsAffected = $stmt->rowCount();

        if ($rowsAffected > 0) {
          return [
            "response" => true
          ];
        } else {
          return [
            "response" => false,
            "error" => "Task not deleted."
          ];
        }
      } else {
        return [
          "response" => false,
          "error" => "Error occurred while executing statement."
        ];
      }
    } catch (Exception $e) {
      // Exception caught
      return [
        "response" => false,
        "error" => "Internal server error: " . $e->getMessage()
      ];
    }
  }
}
