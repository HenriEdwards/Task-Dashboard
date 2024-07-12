<?php
session_start();
// Check if user is NOT logged in
if (!isset($_SESSION['userID'])) {
  // Redirect user to login page
  header("Location: /Task-Dashboard/views/login.php");
  exit;
}

include_once './layouts/header.php';
?>

  <nav class="navbar">
    <ul>
      <li>Welcome to your Task Dashboard...</li>
      <li> <a class="logout" href="/Task-Dashboard/includes/logout.php">Sign out</a></li>
    </ul>
  </nav>

  <div class="tasks-response-box">
    <p id="response-task"></p>
  </div>

  <div id="pagination-controls"></div>

  </div>

  <div class="tasks-section">
    <div class="left-container">
      <!-- User specific tasks rendered -->
      <div id="task-list"></div>
    </div>

    <div class="right-container">
      <form id="task-form">
        <input type="hidden" id="task_id"> 
        <input type="text" id="title" placeholder="Title" >
        <textarea id="description" placeholder="Description" ></textarea>
        <input type="date" id="due_date" >
        <select id="status" >
          <option value="In Progress">In Progress</option>
          <option value="Completed">Completed</option>
          <option value="Pending">Pending</option>
        </select>
        <button id="task-btn" type="submit">Create Task</button>
      </form>
    </div>
  </div>

<?php
include_once './layouts/footer.php';
?>
