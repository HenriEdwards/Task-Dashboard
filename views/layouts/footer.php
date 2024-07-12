
</body>

<?php 
// Check the current page and include the appropriate JavaScript file
if (strpos($_SERVER['REQUEST_URI'], 'tasks') !== false) {
  // Include the JavaScript file for the tasks page
  echo '<script src="../assets/js/tasks.js"></script>';
} else {
  // Include the default JavaScript file
  echo '<script src="../assets/js/login.js"></script>';
}
?>
</html>