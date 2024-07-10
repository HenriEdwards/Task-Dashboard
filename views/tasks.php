<?php
session_start();
// Check if user is NOT logged in
if (!isset($_SESSION['username'])) {
  // Redirect user to login page
  header("Location: /Task-Dashboard/views/login.php");
  exit;
}

include_once './layouts/header.php';
?>

welcome to your task manager

<form id="task-form">

</form>

<a class="logout" href=" /Task-Dashboard/includes/logout.php">Logout</a>

<?php
  include_once './layouts/footer.php';
?>

