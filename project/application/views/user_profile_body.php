    <div id="user_profile_heading" class="container-sm shadow bg-white rounded" >
        <div class="row">
            <!-- for display pic -->
            <div class="col-sm-4 d-flex align-items-center justify-content-center">
                <img id="user_display_pic" class="img-fluid rounded-circle" src="<?php echo base_url(); ?>assets/img/placeholder.PNG" alt="Placeholder picture for users to upload their own">
            </div>

            <!-- for username and email -->
            <div class="col-sm-4 d-flex flex-column justify-content-center">
            <!-- align-items-center for mobile later on -->
                <h3 id="username"><?php echo $username; ?></h3>
                <h6 id="email"><?php echo $email; ?></h6>
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

        <div class="row bg-dark">
            <!-- for users to toggle between statistics and profile of users -->
            <nav id="secondary-nav" class="navbar navbar-expand-md navbar-dark d-flex justify-content-between">

                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a id="details" class="nav-link" href="#">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a id="statistics" class="nav-link" href="#">Statistics</a>
                    </li>
                </ul>

                <!-- user can update / edit profile as needed -->
                <ul class="navbar-nav">
                    <li class="nav-item" data-toggle="modal" data-target="#editProfileModal">
                        <a class="nav-link" href="#">Edit Profile</a>
                    </li>
                </ul>
            </nav>

            <!-- modal for users to edit user details -->        
            <div class="modal fade" id="editProfileModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-dark">
                            <h5 class="modal-title text-white" id="staticBackdropLabel">Edit Profile</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" class="text-white">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="editProfile">
                                <div class="form-group">
                                    <label for="countryEdit">Country</label>
                                    <input type="text" class="form-control" id="countryEdit" aria-describedby="countryEditInput" name="countryEdit" value="<?php echo $country; ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="occupationEdit">Occupation</label>
                                    <input type="text" class="form-control" id="occupationEdit" aria-describedby="occupationEditInput" name="occupationEdit" value="<?php echo $occupation; ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="phoneEdit">Phone</label>
                                    <input type="text" class="form-control" id="phoneEdit" aria-describedby="phoneEditInput" name="phoneEdit" value="<?php echo $phone_number; ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="aboutEdit">About</label>
                                    <textarea  type="text" class="form-control" id="aboutEdit" aria-describedby="aboutEditInput" name="aboutEdit"><?php echo $about; ?></textarea required>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary bg-danger" data-dismiss="modal">Exit</button>
                            <button type="submit" class="btn btn-primary bg-dark" id="save_profile_edit" name="save_profile_edit">Save Changes</button>
                        </div>
                    </div>
                </div>
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
                    <p class="col-sm-7"><?php 
                    echo date('j M Y', strtotime($date_joined));?></p>
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

    <script>
        $(document).ready(function () {
            var username = "<?php echo $username; ?>";

            // for when user wants to save new information in user profile section
            $("#save_profile_edit").click(function() {
                event.preventDefault();
                let country = $('#countryEdit').val();
                let occupation = $('#occupationEdit').val();
                let phone = $('#phoneEdit').val();
                let about = $('#aboutEdit').val();

                $.ajax({
                    url: "<?php echo base_url() ?>UserProfile/edit_profile",
                    method: "POST",
                    data: { username:username, country:country, occupation:occupation, phone:phone, about:about },
                    success: function(response) {
                        console.log(response);
                        if (response === "Changes Saved") {
                            $("#editProfileModal").modal('hide');
                            location.reload();
                        }
                    }
                });
            });
        });
    </script>