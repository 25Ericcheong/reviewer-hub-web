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

        <!-- check for when there is a session or a cookie and change the link to trigger the modal login if there is no session or cookie -->
        <li class="nav-item <?php echo ($this->uri->segment(1) == ("UserReview")) ? "active" : "" ?>">
          <a class="nav-link" href="<?php echo base_url();?>UserReview">My Review</a>
        </li>
      </ul>
      
      <ul class="navbar-nav">
        <?php if( (!$this->session->userdata('logged_in')) && ($cookie === 'not true') ) : ?>
          <!-- Login popup triggered when clicked and user has not logged in -->
          <li class="nav-item" data-toggle="modal" data-target="#loginModal">
            <a class="nav-link" href=#>Login</a>
          </li>
        <?php endif; ?>
        <?php if( ($this->session->userdata('logged_in')) || ($cookie === true) ) : ?>
          <!-- will need to create user profile controller -->
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
            <a class="nav-link" href="<?php echo base_url(); ?>LoginRegistration/logout"> Logout</a>
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

  <script>
    $(document).ready(function(){
      $('#login_submit').click(function() {
        event.preventDefault();
        var username = $('#usernameInput').val();
        var password = $('#passwordInput').val();
        var remember = $('#RememberCheck').is(":checked");
        if (username != '' && password != '') {
          $.ajax({
            url: "<?php echo base_url() ?>LoginRegistration/check_login",
            method: "POST",
            data: { username:username, password:password, remember:remember },
            success: function(response) {
              console.log(response);
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

    // load_data();
    //     function load_data(query){
    //         $.ajax({
    //         url:"<?php echo base_url(); ?>ajax/fatch",
    //         method:"GET",
    //         data:{query:query},
    //         success:function(response){
    //             $('#result').html("");
    //             if (response == "" ) {
    //                 $('#result').html(response);
    //             }else{
    //                 var obj = JSON.parse(response);
    //                 if(obj.length>0){
    //                     var items=[];
    //                     $.each(obj, function(i,val){
    //                         items.push($("<h4>").text(val.filename));
    //                         items.push($("<h4>").text(val.username));
    //                         if (val.filename.includes("jpg")) {
    //                             items.push($('<img width="320" height="240" src="' +'<?php echo base_url(); ?>/uploads/' +val.filename + '" />'));
    //                         }else{
    //                             items.push($('<video width="320" height="240" controls><source  src="' +'<?php echo base_url(); ?>/uploads/' +val.filename + '" type="video/mp4"></video>'));
    //                         }
    //                 });
    //                 $('#result').append.apply($('#result'), items);         
    //                 }else{
    //                 $('#result').html("Not Found!");
    //                 }; 
    //             };
    //         }
    //     });
    //     }
        // $('#search_text').keyup(function(){
        //     var search = $(this).val();
        //     console.log(search);
        //     if(search != ''){
        //         load_data(search);
        //     }else{
        //         load_data();
        //     }
        // });
    });
  </script>