
function validateInput (username, password) {
  username = username.trim();
  password = password.trim();
  
  // Check if input is empty
  if (username.length == 0 || password.length == 0) {
    $("#response").text("Username or Password can't be empty.");
    return false;
  }

  // Check if input contains spaces/tabs/
  if (username.includes(' ') || username.includes('\t') || username.includes('\n') || password.includes(' ') || password.includes('\t') || password.includes('\n')) {
    $("#response").text("Username or Password contains invalid character.");
    return false;
  }

  // Check if the password contains only alphanumeric characters
  const invalidRegex = /^[a-zA-Z0-9]+$/;
  if (!invalidRegex.test(password) || !invalidRegex.test(username)) {
    $("#response").text("Username or Password contains invalid character.");
    return false;
  }

  // Check if password is at least 6 chars
  // if (password.length < 6){
  //   $("#response").text("Password should be at least 6 characters.");
  //   return false;
  // }

  // Return validated input
  let dataObj = {username: username, password: password};

return dataObj;
}

$(document).ready(function() {

  $('#login-form').submit(function(e) {
    e.preventDefault();

    // Retrieve input
    let username = $("#username").val();
    let password = $("#password").val();

    // Call validateInput to check input validity
    let inputData = validateInput(username, password);

    // Stop script on invalid input
    if (!inputData) {
      return;
    }

    // Send ajax request to controller
    $.ajax({
      type: "POST",
      url: "/Task-Dashboard/controllers/UserController.php", 
      data: {
        type: "login",
        username: inputData.username,
        password: inputData.password,
      },
      success: function (response) {
        console.log("OK LETS SEE1: ");
        // Parse the JSON response
        let responseData = JSON.parse(response);

        console.log("OK LETS SEE1: ", responseData);
        if (!responseData.success) {
          console.log("FALSE !");
        }
        console.log("OK LETS SEE2: ", responseData.success);

        if (responseData.success) {
          // User login successful, redirect to tasks dashboard
          console.log("OK LETS SEE3: ", responseData);

          window.location.href = "/Task-Dashboard/views/tasks.php";
        } else {
          // User not redirected, invalid input
          $("#response").text("Invalid Username or Password.");
        }
      },
      error: function (){
          $("#response").text("Internal Error Occurred.");
      }
    })
  })

  $('#register-form').submit(function(e) {
    e.preventDefault();

    // Retrieve input
    let username = $("#username").val();
    let password = $("#password").val();

    // Call validateInput to check input validity
    let inputData = validateInput(username, password);

    // Stop script on invalid input
    if (!inputData) {
      return;
    }

    // Send registration request to controller
    $.ajax({
      type: "POST",
      url: "/Task-Dashboard/controllers/UserController.php", 
      data: {
        type: "register",
        username: username,
        password: password
      },
      success: function (response) {

        // Parse the JSON response
        let responseData = JSON.parse(response);

        if (responseData.success) {
          // User registration successful, redirect to login
          window.location.href = "/Task-Dashboard/views/login.php";
        } else {
          // User not redirected, invalid input
          $("#response").text("Username already taken.");
        }
      },
      error: function (){
        $("#response").text("Internal Error Occurred.");
      }
    })
  })
});