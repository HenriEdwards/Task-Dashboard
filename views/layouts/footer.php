
</body>

<?php 
// Check the current page and include the appropriate JavaScript file
if (strpos($_SERVER['REQUEST_URI'], 'tasks') !== false) {
  echo '<script src="../assets/js/tasks.js"></script>';
} else {
  echo '<script src="../assets/js/login.js"></script>';
}
?>
</html>