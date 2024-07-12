// Function validates user input & returns errors & cont if ok
function validateInput (username, password) {
  username = username.trim();
  password = password.trim();
  
  // Check if input is empty
  if (username.length == 0 || password.length == 0) {
    $("#response").text("Input can't be empty.");
    return false;
  }

  // Check if input contains spaces/tabs/
  if (username.includes(' ') || username.includes('\t') || username.includes('\n') 
    || password.includes(' ') || password.includes('\t') || password.includes('\n')) {
    $("#response").text("Input contains invalid character.");
    return false;
  }

  // Check if the password contains only alphanumeric characters
  const invalidRegex = /^[a-zA-Z0-9]+$/;
  if (!invalidRegex.test(password) || !invalidRegex.test(username)) {
    $("#response").text("Input contains invalid character.");
    return false;
  }

  let dataObj = {username: username, password: password};
  return dataObj;
}

$(document).ready(function() {
  // Handle username input focus and blur on login/registration page
  $('#username').focus(function() {
    if ($(this).val() === 'Username') {
      $(this).val('');
    }
  }).blur(function() {
    if ($(this).val() === '') {
      $(this).val('Username');
    }
  });

  // Handle password input focus and blur on login/registration page
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

  // Extract user data, validate & send ajax request to relevant controller
  $('#login-form').submit(function(e) {
    e.preventDefault();
  
    let username = $("#username").val();
    let password = $("#password").val();
    
    // Validate input
    let inputData = validateInput(username, password);

    // Stop if invalid
    if (!inputData) {
      return;
    }

    // Send LOGIN ajax request to controller
    $.ajax({
      type: "POST",
      url: "/Task-Dashboard/controllers/UserController.php", 
      data: {
        type: "login",
        username: inputData.username,
        password: inputData.password
      },
      success: function (response) {
        window.location.href = "/Task-Dashboard/views/tasks.php";
      },
      error: function (xhr, status, error) {
        let errorMessage = "An error occurred. Please try again.";
        try {
          const response = JSON.parse(xhr.responseText);
          errorMessage = response.error;
        } catch (e) {
          errorMessage = "Internal server error. Please try again later.";
        }
        $("#response").text(errorMessage);
      }
    });
  });
  
  // Extract user data, validate & send ajax request to relevant controller
  $('#register-form').submit(function(e) {
    e.preventDefault();

    // Retrieve input
    let username = $("#username").val();
    let password = $("#password").val();

    // Call validateInput to check input validity
    let inputData = validateInput(username, password);

    // Stop if invalid
    if (!inputData) {
      return;
    }

    // Send REGISTER request to controller
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
        // Navigate user to login after delay
        setTimeout(function() {
          window.location.href = "/Task-Dashboard/views/login.php";
      }, 2500);
      },
      error: function (xhr, status, error) {
        try {
          const response = JSON.parse(xhr.responseText);
          errorMessage = response.error;
        } catch (e) {
          errorMessage = "Internal server error. Please try again later.";
        }
        $("#response").text(errorMessage);
      }
    })
  })
});