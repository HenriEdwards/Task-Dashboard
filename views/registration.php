<?php
session_start();
// Check if user is NOT logged in
if (isset($_SESSION['userID'])) {
  // Redirect user to login page
  header("Location: /Task-Dashboard/views/tasks.php");
  exit;
}
  include_once './layouts/header.php';
?>
<div class="main-container">
  <div class="form-container">
    <div class="form-heading">
      <h1>Welcome Back</h1>
      <div class="form-nav-login">
        <p>Already have an account ?</p>
        <a href="/Task-Dashboard/views/login.php">Sign In</a>
      </div>
    </div>
    <form id="register-form">
      <input type="hidden" name="type" value="register">
      <div class="form-items">
        <input type="text" id="username" name="username" value="Username">
      </div>
      <div class="form-items">
        <input type="text" id="password-text" value="Password">
        <input type="password" id="password" class="hidden" name="password" value="Password">
      </div>
      <button class="register-btn" type="submit">Submit</button>
    </form>
    <div class="register-response-box">
      <p id="response"></p>
    </div>
  </div>
</div>

<?php
  include_once './layouts/footer.php';
?>

