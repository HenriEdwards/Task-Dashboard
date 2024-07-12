// Function validates user input
function validateInput (username, password) {
  username = username.trim();
  password = password.trim();
  
  // Check if input is empty
  if (username.length == 0 || password.length == 0) {
    $("#response").text("Input can't be empty.");
    return false;
  }

  // Check if input contains spaces/tabs/
  if (username.includes(' ') || username.includes('\t') || username.includes('\n') || password.includes(' ') || password.includes('\t') || password.includes('\n')) {
    $("#response").text("Input contains invalid character.");
    return false;
  }

  // Check if the password contains only alphanumeric characters
  const invalidRegex = /^[a-zA-Z0-9]+$/;
  if (!invalidRegex.test(password) || !invalidRegex.test(username)) {
    $("#response").text("Input contains invalid character.");
    return false;
  }

  /*
    Due to implementing default/dynamic input values in the input,
    Have to hardcode a solution for now
    When user types nothing and just sign in, it will attempt to create
    an account with username 'Username'
  */
  if (username === "Username") {
    $("#response").text("Input can't be empty.");
    return false;
  }

  // Check if password is at least 6 chars
  // Not necessary for this program yet, leaving it out
  // if (password.length < 6){
  //   $("#response").text("Password should be at least 6 characters.");
  //   return false;
  // }

  // Return validated input
  let dataObj = {username: username, password: password};

  return dataObj;
}

$(document).ready(function() {

  // Handle username input focus and blur
  $('#username').focus(function() {
    if ($(this).val() === 'Username') {
      $(this).val('');
    }
  }).blur(function() {
    if ($(this).val() === '') {
      $(this).val('Username');
    }
  });

  // Handle password input focus and blur
  $('#password-text').focus(function() {
    $(this).val('').addClass('hidden');
    $('#password').removeClass('hidden').val('').focus();
  });

  $('#password').blur(function() {
    if ($(this).val() === '') {
      $(this).addClass('hidden');
      $('#password-text').removeClass('hidden').val('Password');
    }
  });

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
        password: inputData.password
      },
      success: function (response) {
  
        // Navigate user to task dashboard
        window.location.href = "/Task-Dashboard/views/tasks.php";
      },
      error: function (xhr, status, error) {
        let errorMessage = "An error occurred. Please try again.";

        // Parse the response to get the error message
        try {
          const response = JSON.parse(xhr.responseText);
          errorMessage = response.error;
        } catch (e) {
          errorMessage = "Internal server error. Please try again later.";
        }

        // Display the error message
        $("#response").text(errorMessage);
      }
    });
  });
  

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
        username: inputData.username,
        password: inputData.password
      },
      success: function (response) {

        $("#response")
        .text("You successfully signed up, please sign in.")
        .css("color", "green");
        // Navigate user to login
        setTimeout(function() {
          window.location.href = "/Task-Dashboard/views/login.php";
      }, 2500);
      },
      error: function (xhr, status, error) {
        let errorMessage = "An error occurred. Please try again.";

        // Parse the response to get the error message
        try {
          const response = JSON.parse(xhr.responseText);
          errorMessage = response.error;
          console.log(errorMessage);
        } catch (e) {
          errorMessage = "Internal server error. Please try again later.";
        }

        // Display the error message
        $("#response").text(errorMessage);
      }
    })
  })
});