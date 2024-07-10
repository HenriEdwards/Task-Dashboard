<?php
  include_once './layouts/header.php';
?>
<h1>Please register</h1>

<form id="register-form">
    <input type="hidden" name="type" value="register">
    <label for="name">Username:</label>
    <input type="text" id="username" name="username" required>

    <label for="password">Password</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Submit</button>
</form>

<div id="response">hello world</div>


<?php
  include_once './layouts/footer.php';
?>

