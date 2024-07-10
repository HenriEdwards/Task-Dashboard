<?php
  include_once './layouts/header.php';
?>
<h1>Please sign in</h1>
<div>
  <form id="login-form">
      <input type="hidden" name="type" value="login">
      <label for="name">Username:</label>
      <input type="text" id="username" name="username">

      <label for="password">Password</label>
      <input type="password" id="password" name="password">

      <button type="submit">Submit</button>
  </form>

  <p id="response">Form responses here boi</p>
</div>
<div>
  <p>Not registered ?</p>
  <a class="logout" href=" /Task-Dashboard/views/registration.php">Register</a>
</div>

<?php
  include_once './layouts/footer.php';
?>

