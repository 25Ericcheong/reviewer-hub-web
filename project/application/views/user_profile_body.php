<div id="user_profile_heading" class="container-sm shadow bg-white rounded" >
    <div class="row">
        <!-- for display pic -->
        <div class="col-sm-4 d-flex align-items-center justify-content-center">
            <img id="user_display_pic" class="img-fluid rounded-circle" src="<?php echo base_url(); ?>assets/img/placeholder.PNG" alt="Placeholder picture for users to upload their own">
        </div>

        <!-- for username and email -->
        <div class="col-sm-4 d-flex flex-column justify-content-center">
        <!-- align-items-center for mobile later on -->
            <h3 id="username"><?php echo $username ?></h3>
            <h6 id="email"><?php echo $email ?></h6>
        </div>

        <!-- number of reviews made -->
        <div class="col-sm-2 d-flex flex-column align-items-center justify-content-center">
            <h3 id="num_of_reviews">0</h3>
            <h6>Reviews Made</h6>
        </div>

        <!-- reputation of user -->
        <div class="col-sm-2 d-flex flex-column align-items-center justify-content-center">
            <h3 id="reputation">0</h3>
            <h6>Reputation</h6>
        </div>
    </div>
</div>

<div id="user_details" class="container-sm" >
    <div class="row d-flex justify-content-between">
        <!-- extra details -->
        <div id="details_section" class="col-sm-6 shadow bg-white rounded">
            <div class="row">
                <h3 class="col-sm-12">Details</h3>
                <p class="col-sm-5">Username</p>
                <p class="col-sm-7"><?php echo $username ?></p>

                <p class="col-sm-5">Country</p>
                <p class="col-sm-7"><?php echo $country ?></p>

                <p class="col-sm-5">Email</p>
                <p class="col-sm-7"><?php echo $email ?></p>

                <p class="col-sm-5">Occupation</p>
                <p class="col-sm-7"><?php echo $occupation ?></p>

                <p class="col-sm-5">Phone</p>
                <p class="col-sm-7"><?php echo $phone_number ?></p>

                <p class="col-sm-5">Date Joined</p>
                <p class="col-sm-7"><?php echo $date_joined ?></p>
            </div>
        </div>

        <!-- about you section -->
        <div id="about_section" class="col-sm-5 shadow bg-white rounded">
        <!-- align-items-center for mobile later on -->
            <div class="row">
                <h3 class="col-sm-12">About</h3>
                <p class="col-sm-12"><?php echo $about ?></p>
            </div>
        </div>
    </div>
</div>
<div id="testing">
    <p id="something">Test</p>
</div>