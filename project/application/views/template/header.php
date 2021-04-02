<html>
        <head>
                <title>INFS7202 Project</title>
                <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap.css">
                <script src="<?php echo base_url(); ?>assets/js/jquery-3.6.0.min.js"></script>
                <script src="<?php echo base_url(); ?>assets/js/bootstrap.js"></script>
        </head>
        <body>
  <script>

  </script>
  <?php echo current_url(); ?>
  <nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <a class="navbar-brand mb-0 h1" href="https://infs3202-8a85b4a1.uqcloud.net/project/">Reviewers Hub</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbar">
      <ul class="navbar-nav">
        <li class="nav-item 
          <?php 
            if ($this->uri->segment(1) === "Home") {
              echo "active";
            } else if (strcmp(current_url(), "https://infs3202-8a85b4a1.uqcloud.net/project/") == 0) {
              echo "active";
          }?>">
          <a class="nav-link" href="<?php echo base_url();?>Home">Home</a></li>
          <li class="nav-item <?php echo ($this->uri->segment(1) == ("UserReview")) ? "active" : "" ?>">
          <a class="nav-link" href="<?php echo base_url();?>UserReview">My Review</a>
        </li>
      </ul>
    </div>
  </nav>
  <!-- <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">INFS7202 Demo</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
        <li class="nav-item">
            <a href="<?php echo base_url(); ?>login"> Home </a>
        </li>
    </ul>
    <ul class="navbar-nav my-lg-0">
    <?php if(!$this->session->userdata('logged_in')) : ?>
          <li class="nav-item">
            <a href="<?php echo base_url(); ?>login"> Login </a>
          </li>
          <?php endif; ?>
          <?php if($this->session->userdata('logged_in')) : ?>
            <li class="nav-item">
            <a href="<?php echo base_url(); ?>login/logout"> Logout </a>
           </li>
           <?php endif; ?>
    </ul>

    </div>
</nav> -->
<div class="container">

