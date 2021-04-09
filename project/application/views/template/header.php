<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>INFS7202 Project</title>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap.css">
    <script src="<?php echo base_url(); ?>assets/js/jquery-3.6.0.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.js"></script>
  </head>
  
  <body>
  <?php echo current_url(); ?>
  <nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <a class="navbar-brand mb-0 h1" href="https://infs3202-8a85b4a1.uqcloud.net/project/">Reviewers Hub</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-lg-between" id="navbar">
      <!-- having 2 groups of ul tags to add space between rest of nav links and login function -->
      <ul class="navbar-nav">
        <li class="nav-item 
          <?php 
            if ($this->uri->segment(1) === "Home") {
              echo "active";
            } else if (strcmp(current_url(), "https://infs3202-8a85b4a1.uqcloud.net/project/") == 0) {
              echo "active";
          }?>">
          <a class="nav-link" href="<?php echo base_url();?>Home">Home</a>
        </li>

        <li class="nav-item <?php echo ($this->uri->segment(1) == ("UserReview")) ? "active" : "" ?>">
          <a class="nav-link" href="<?php echo base_url();?>UserReview">My Review</a>
        </li>
      </ul>
      
      <!-- check for when there is a session or a cookie if both are not present, button will be displayed -->
      <ul class="navbar-nav">
        <?php if( (!$this->session->userdata('logged_in')) && ($cookie === 'not true') ) : ?>
          <!-- Login popup triggered when clicked and will be shown user has not logged in -->
          <li class="nav-item" data-toggle="modal" data-target="#loginModal">
            <a class="nav-link" href=#>Login</a>
          </li>
          <li class="nav-item" data-toggle="modal" data-target="#signupModal">
            <a class="nav-link" href=#>Create Account</a>
          </li>
        <?php endif; ?>
        <?php if( ($this->session->userdata('logged_in')) || ($cookie === true) ) : ?>
          <!-- will need to create user profile controller -->
          <!-- user profile link will change depending on name of username -->
          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>UserProfile">
              <?php
              $username = "";
              if ($this->session->userdata('logged_in')) {
                $username = $this->session->userdata('username');
              } else if ($cookie === true) {
                $username = get_cookie("username");
              }

              echo $username;
              ?>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url(); ?>Login/logout"> Logout</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </nav>

  <!-- Login popup form, will only be shown when login is clicked -->
  <div class="modal fade" id="loginModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-dark">
          <h5 class="modal-title text-white" id="staticBackdropLabel">Login</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" class="text-white">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="loginForm">
            <div class="form-group">
              <label for="usernameInput">Username</label>
              <input type="text" class="form-control" id="usernameInput" aria-describedby="emailHelp" name="usernameInput">
            </div>
            <div class="form-group">
              <label for="passwordInput">Password</label>
              <input type="password" class="form-control" id="passwordInput" name="passwordInput">
            </div>
            <div class="form-group form-check d-flex justify-content-between">
              <div>
              <input type="checkbox" class="form-check-input" id="RememberCheck" name="RememberCheck">
              <label class="form-check-label" for="RememberCheck">Remember me</label>
              </div>
              <!-- Will require href link and further development -->
              <a>Forgot Password?</a>
            </div>
          </form id="loginForm">
        </div>
        <div class="modal-footer d-flex justify-content-between">
          <button type="button" class="btn btn-secondary bg-danger" data-dismiss="modal">Exit</button>
          <button type="button" class="btn btn-primary bg-dark" id="login_submit" name="login_submit">Log In</button>
        </div>
      </div>
    </div>          
  </div>

   <!-- Sign up popup form, will only be shown when create account is clicked -->
   <div class="modal fade" id="signupModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-dark">
          <h5 class="modal-title text-white" id="staticBackdropLabel">Sign Up</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" class="text-white">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="signupForm">
            <div class="form-group">
              <label for="usernameRegister">Username</label>
              <input type="text" class="form-control" id="usernameRegister" aria-describedby="usernameRegisterInput" name="usernameRegister">
            </div>

            <div class="form-group">
              <label for="emailRegister">Email</label>
              <input type="text" class="form-control" id="emailRegister" aria-describedby="emailRegisterInput" name="emailRegister">
            </div>

            <div class="form-group">
              <label for="passwordRegister">Password</label>
              <div class="d-flex flex-row justify-content-between">

                <div id="first-progress" class="progress" style="height: 0.5rem !important; width: 25%;">
                  <div id="first-progress-bar" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>

                <div id="second-progress" class="progress" style="height: 0.5rem !important; width: 25%;">
                  <div id="second-progress-bar" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>

                <div id="third-progress" class="progress" style="height: 0.5rem !important; width: 25%;">
                  <div id="third-progress-bar" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>

                <div id="fourth-progress" class="progress" style="height: 0.5rem !important; width: 25%;">
                  <div id="fourth-progress-bar" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>

              </div>
              <input type="password" class="form-control" id="passwordRegister" name="passwordRegister" required>
            </div>

            <div class="form-group">
              <label for="confirmPWRegister">Confirm Password</label>
              <input type="password" class="form-control" id="confirmPWRegister" name="confirmPWRegister">
            </div>
          </form id="signupForm">
        </div>
        <div class="modal-footer d-flex justify-content-between">
          <button type="button" class="btn btn-secondary bg-danger" data-dismiss="modal">Exit</button>
          <button type="button" class="btn btn-primary bg-dark" id="create_account" name="create_account">Create</button>
        </div>
      </div>
    </div>          
  </div>

  <script>
    $(document).ready(function(){

      // for login
      $('#login_submit').click(function() {
        event.preventDefault();
        var username = $('#usernameInput').val();
        var password = $('#passwordInput').val();
        var remember = $('#RememberCheck').is(":checked");
        if (username && password) {
          $.ajax({
            url: "<?php echo base_url() ?>Login/check_login",
            method: "POST",
            data: { username:username, password:password, remember:remember },
            success: function(response) {
              if (response == "User Exists") {
                // session and cookies are registed via check_login and cookies are created depending on if checkbox for remember me is checked 
                $('#loginModal').modal('hide');
                location.reload();

                // sub 'login' button with username that is linked to user's detail
                // can easily hide login button with .d-none for bootstrap and since 
                // register on session and cookie;
              } else {
                // remove currently existing feedback
                $('#emptyPassword').remove();
                $('#emptyUsername').remove();

                // if feedback exists already, do not add feedback again
                if (!$('#invalidPassword').length) {
                  $('#usernameInput').addClass("is-invalid");
                  $('<div id="invalidUsername" class="invalid-feedback">Please provide a valid username.</div>').insertAfter("#usernameInput");
                  $('#passwordInput').addClass("is-invalid");
                  $('<div id="invalidPassword" class="invalid-feedback">Please provide a valid password.</div>').insertAfter("#passwordInput");
                }
              }
            }
          });
        } else {
          // remove currently existing feedback
          $('#invalidPassword').remove();
          $('#invalidUsername').remove();

          // if feedback exists already, do not add feedback again
          if (!$('#emptyPassword').length) {
            $('#usernameInput').addClass("is-invalid");
            $('<div id="emptyPassword" class="invalid-feedback">Please insert username.</div>').insertAfter("#usernameInput");
            $('#passwordInput').addClass("is-invalid");
            $('<div id="emptyUsername" class="invalid-feedback">Please insert password.</div>').insertAfter("#passwordInput");
          }
        }
      });

      // ensures email is in correct format
      // for explanation - https://stackoverflow.com/questions/46155/how-to-validate-an-email-address-in-javascript.
        function validateEmail(email) {
        const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
        } 

      // password strength     
      function checkPasswordStrength() {
        var number = /([0-9])/;
        var alphabets = /([a-zA-Z])/;
        var special_characters = /([~,!,@,#,$,%,^,&,*,-,_,+,=,?,>,<])/;
        var strength = 0;

        if($('#passwordRegister').val().length < 6) {
          return strength;
        } else {
          if( $('#passwordRegister').val().match(number) ) {
            strength++;
          }

          if ($('#passwordRegister').val().match(alphabets)) {
            strength++;
          }

          if ($('#passwordRegister').val().match(special_characters)) {
            strength++;
         }
        }
        return strength;
      }

      // password strength validator and visualization on browser
      $('#passwordRegister').keyup(function() {
        $('#first-progress-bar').css("width", "0%");
        $('#second-progress-bar').css("width", "0%");
        $('#third-progress-bar').css("width", "0%");
        $('#fourth-progress-bar').css("width", "0%");

        $(".bg-success").removeClass("bg-success");
        $(".bg-info").removeClass("bg-info");
        $(".bg-warning").removeClass("bg-warning");
        $(".bg-danger").removeClass("bg-danger");

        if (checkPasswordStrength() == 0) {
          $('#first-progress-bar').addClass("bg-danger").css("width","100%");
        } else if (checkPasswordStrength() == 1) {
          $('#first-progress-bar').addClass("bg-warning").css("width","100%");
          $('#second-progress-bar').addClass("bg-warning").css("width","100%");
        } else if (checkPasswordStrength() == 2) {
          $('#first-progress-bar').addClass("bg-info").css("width","100%");
          $('#second-progress-bar').addClass("bg-info").css("width","100%");
          $('#third-progress-bar').addClass("bg-info").css("width","100%");
        } else {
          $('#first-progress-bar').addClass("bg-success").css("width","100%");
          $('#second-progress-bar').addClass("bg-success").css("width","100%");
          $('#third-progress-bar').addClass("bg-success").css("width","100%");
          $('#fourth-progress-bar').addClass("bg-success").css("width","100%");
        }
      });

      // for sign up
      $('#create_account').click(function() {
        event.preventDefault();
        var username = $('#usernameRegister').val();
        var email = $('#emailRegister').val();
        var password = $('#passwordRegister').val();
        var confirm_password = $('#confirmPWRegister').val();

        if (username && password && email && confirm_password) {
          // remove existing feedback
          $('#emptyRegisterPassword').remove();
          $('#emptyRegisterConfirmPassword').remove();
          $('#weakRegisterPassword').remove();
          $('#passwordNotEqual').remove();
          $('#emptyRegisterUsername').remove();
          $('#emptyRegisterEmail').remove();
          $('#wrongRegisterEmail').remove();
          $(".is-invalid").removeClass("is-invalid");
          $('#emailExists').remove();
          $('#usernameExists').remove();

          // email is not in correct form
          if (!validateEmail(email)) {
            $('#emailRegister').addClass("is-invalid");
            $('<div id="wrongRegisterEmail" class="invalid-feedback">Please ensure email is valid. Example: cococrunch23@hotmail.com</div>').insertAfter("#emailRegister");
            
          // passwords must be equal
          } else if (password != confirm_password) {
            $('#confirmPWRegister').addClass("is-invalid");
            $('<div id="passwordNotEqual" class="invalid-feedback">Please ensure passwords inputted are equal.</div>').insertAfter("#confirmPWRegister");

          // password strength is too weak
          } else if (checkPasswordStrength() == 0) {
            $('#passwordRegister').addClass("is-invalid");
            $('<div id="weakRegisterPassword" class="invalid-feedback">Password needs to be at least 6 letters long.</div>').insertAfter("#passwordRegister"); 

          // satisfies all pre requisites
          } else {
            $.ajax({
              url: "<?php echo base_url() ?>Register/check_register",
              method: "POST",
              data: { username:username, password:password, email:email },
              success: function(response) {

                console.log(response);
                if (response == "Username Exists") {
                  // username needs to be unique
                  if (!$('#usernameExists').length) {
                    $('#usernameRegister').addClass("is-invalid");
                    $('<div id="usernameExists" class="invalid-feedback">Username already exists, please use a different one.</div>').insertAfter("#usernameRegister");
                  }

                } else if (response == "Email Exists") {
                  // email needs to be unique
                  if (!$('#emailExists').length) {
                    $('#emailRegister').addClass("is-invalid");
                    $('<div id="emailExists" class="invalid-feedback">Email already exists, please use a different one.</div>').insertAfter("#emailRegister");
                  }

                } else {
                  // user's account will be registered into database and sign up modal will be hidden
                  $('#signupModal').modal('hide');
                  location.reload();
                }
              }
            });
          }
        } else {

          // if feedback exists already, do not add feedback again
          if (!$('#emptyRegisterPassword').length) {
            $('#passwordRegister').addClass("is-invalid");
            $('<div id="emptyRegisterPassword" class="invalid-feedback">Please insert password.</div>').insertAfter("#passwordRegister");
            $('#confirmPWRegister').addClass("is-invalid");
            $('<div id="emptyRegisterConfirmPassword" class="invalid-feedback">Please insert password confirmation.</div>').insertAfter("#confirmPWRegister");

            $('#usernameRegister').addClass("is-invalid");
            $('<div id="emptyRegisterUsername" class="invalid-feedback">Please insert username.</div>').insertAfter("#usernameRegister");
            $('#emailRegister').addClass("is-invalid");
            $('<div id="emptyRegisterEmail" class="invalid-feedback">Please insert email.</div>').insertAfter("#emailRegister");
          }
        }
      });

    });
  </script>