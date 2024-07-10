$(document).ready(function() {
  console.log('jquery loaded.');

  $('#login-form').submit(function(e) {
    e.preventDefault();

    console.log('login clicked.');

    // Retrieve data

    let username = $("#username").val();
    let password = $("#password").val();

    console.log(username, password);
    // Validate input
    
    // Send ajax request to controller
    $.ajax({
      type: "POST",
      url: "/Task-Dashboard/controllers/UserController.php", 
      data: {
        type: "login",
        username: username,
        password: password
      },
      // dataType: 'json',
      success: function (response) {
        console.log('AJAX success callback');
        console.log('Response:', response);
        // Redirect user to tasks dashboard
        window.location.href = "/Task-Dashboard/views/tasks.php";
      },
      error: function (response){
        console.log('AJAX error callback');
        console.log('Response:', response);
      }
    })
  })

  $('#register-form').submit(function(e) {
    e.preventDefault();

    console.log('register clicked.');

    // Retrieve data

    let username = $("#username").val();
    let password = $("#password").val();

    console.log(username, password);
    // Validate input

    // Send registration request to controller
    $.ajax({
      type: "POST",
      url: "/Task-Dashboard/controllers/UserController.php", 
      data: {
        type: "register",
        username: username,
        password: password
      },
      dataType: 'json',
      success: function (response) {
        console.log('AJAX success callback');
        console.log('Response:', response);
      },
      error: function (response){
        console.log('AJAX error callback');
        console.log('Response:', response);
      }
    })
  })
});