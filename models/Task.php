
<?php
require_once "../config/database.php";

class Task {
  // Retrieve tasks
  public function getTasks($userId, $pageNr) {
    $dbo = Database::getConnection();

    // Count the number of tasks for the user and value must be returned so that the frontend knows how many pages to create



    // O

  }

  // Create a task
  public function createTasks($title, $description, $dueDate, $status, $userId) {
    $dbo = Database::getConnection();

    // Check what status_ID the provided status is
    $stmt = $dbo->prepare("SELECT status_ID from status WHERE status = :status");
    $stmt->bindParam('status', $status);

    // Execute
    try {
      if ($stmt->execute()) {
        $rowsAffected = $stmt->rowCount();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($rowsAffected > 0){
          $statusId = $result['status_ID'];
          echo " got the status id as $statusId";
        } else {
          echo " could not find id of the status ";
        }
      } else {
        echo " error retrieving the status id ";
      }
    } catch (Exception $e) {
      echo " exception error STATUS";
    }

    // Prepare insert statement
    $stmt = $dbo->prepare("INSERT INTO tasks (title, description, due_date, status_ID, user_ID) VALUES (:title, :description, :duedate, :statusId, :userId)");
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':duedate', $dueDate);
    $stmt->bindParam(':statusId', $statusId);
    $stmt->bindParam(':userId', $userId);

    // POSSIBLY REMOVE ONLY FOR TESTING
    // As i will probably just add the 'data sent to the backend', meanbing the data provided by the user/frontend ON SUCCESSFULLY ADDING OF TASK
    $array = [$title, $description, $dueDate, $status];
    // Execute insert statement

    try {
      if ($stmt->execute()) {
          $rowsAffected = $stmt->rowCount();
          if ($rowsAffected > 0) {
              echo "Creation Successful";
              echo '<pre>'; print_r($array); echo '</pre>';
          } else {
              echo "No rows were created ";
          }
      } else {
          echo "ERROR creating task";
      }
    } catch (Exception $e) {
        echo "Error occurred while creating: " . $e->getMessage();
    }
  }

  // Delete a task
  public function deleteTasks($taskId) {
    $dbo = Database::getConnection();

    $stmt = $dbo->prepare("DELETE FROM tasks WHERE :taskId = task_ID");
    $stmt->bindParam(':taskId', $taskId);

    try {
      if ($stmt->execute()) {
          $rowsAffected = $stmt->rowCount();
          if ($rowsAffected > 0) {
              echo "Deletion Successful";
          } else {
              echo "No rows were deleted (the task ID may not exist)";
          }
      } else {
          echo "ERROR deleting task";
      }
    } catch (Exception $e) {
        echo "Error occurred while deleting: " . $e->getMessage();
    }
  }

  // Update a task
  public function updateTasks($title, $description, $dueDate, $status, $taskId) {
    $dbo = Database::getConnection();

    // Check what status_ID the provided status is
    $stmt = $dbo->prepare("SELECT status_ID from status WHERE status = :status");
    $stmt->bindParam(':status', $status);

    // Execute
    try {
      if ($stmt->execute()) {
        $rowsAffected = $stmt->rowCount();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($rowsAffected > 0){
          $statusId = $result['status_ID'];
          echo " got the status id as $statusId";
        } else {
          echo " could not find id of the status ";
        }
      } else {
        echo " error retrieving the status id ";
      }
    } catch (Exception $e) {
      echo " exception error STATUS";
    }
    

    $stmt = $dbo->prepare("UPDATE tasks SET title = :title, description = :description, due_date = :dueDate, status_ID = :statusId WHERE task_ID = :taskId");
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':dueDate', $dueDate);
    $stmt->bindParam(':statusId', $statusId);
    $stmt->bindParam(':taskId', $taskId);
    
    // Execute
    try {
      if ($stmt->execute()) {
        $rowsAffected = $stmt->rowCount();
        if ($rowsAffected > 0){
          echo " Updated the task ";
        } else {
          echo " Task could not be updated as it might not exist ";
        }
      } else {
        echo " error updating the task ";
      }
    } catch (Exception $e) {
      echo $e;
    }
  }
}