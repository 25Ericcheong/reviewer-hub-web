<?php echo $test; ?>
<div class="m-5">
    <!-- this is for when no users can be found to be logged in -->
    <?php if( (!$this->session->userdata('logged_in')) && (!get_cookie("remember")) ) : ?>
        There is no user logged in or the verification code sent to user is not correctly matched with the owner of the account. Please login by clicking the login button on the top right located in the navigational bar. Then verify yourr email by clicking on the indicated button. Thank you.
    <?php endif; ?>

    <!-- this is for when there is a user logged in, so user can verfiy email right away! -->
    <?php if( ($this->session->userdata('logged_in')) || (get_cookie("remember") == 'true') ) : ?>
        <div id="email_verification_form" class="container row">
            <h3 class="col-sm-12 mt-5">Email Verification</h3>
            <p class="col-sm-12" id="username_verify"><?php echo $this->session->userdata('username'); ?></p>
            <h5 class="col-sm-12">If this is not your username, please <a href="<?php echo base_url(); ?>Login/logout">logout</a></h5>
            <p class="col-sm-12 m-0">If yes, please enter the verification code sent to your email to verify your email. This will be reflected on your account for everyone else to see!</p>

            <?php 
                $attributes = array('class' => 'w-100');
                echo form_open_multipart('VerifyEmail/verify_email', $attributes); 
            ?>

                <label class="col-sm-12 text-center mt-5" for="verification_code">Enter code</label>
                <div class="col-sm-12 d-flex justify-content-center">
                    <input type="text" id="verification_code" name="verification_code" class="w-25" value="<?php if ($verification) {echo $verification;} ?>">
                </div>

                <!-- feedback for user when verification code was entered -->
                <?php if ($success || $error) : ?>
                    <?php if ($success) : ?>
                        <?php echo $success; ?>
                    <?php endif; ?>

                    <?php if ($error) : ?>
                        <?php echo $error; ?>
                    <?php endif; ?>
                <?php endif; ?>
                
                <div class="col-sm-12 d-flex justify-content-end mb-5" id="verification_section">
                    <button type="submit" class="bg-dark mt-3" id="verification_button" name="verification_button" style="color:white; padding: 0.375rem 0.75rem; border-radius: 0.25rem">Verify</button>
                </div>
            <?php echo form_close(); ?>

            <div class="col-sm-12 mb-3 d-flex justify-content-center" id="resend_verification_code">
                <button id="resend_verification_button" type="button" class="btn bg-info text-white">Click this to resend verification code to email</button>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
$(document).ready(function(){
    var username = $('#username_verify').text();

    // feedback will be sent to user when user wants to resend verification code for email verification and verification code will be updated as well
    $('#resend_verification_button').click(function() {
        event.preventDefault();
        $.ajax({
            url: "<?php echo base_url() ?>VerifyEmail/resend_verification_code",
            method: "POST",
            data: { username:username },
            success: function(response) {

                // if feedback exists already, do not add feedback again
                if (!$('#verifyEmailFeedback').length) {
                    $('<div id="verifyEmailFeedback" class="col-sm-12 mb-5 text-center">New verification code to verify your email has been sent to the designated account! Please check your email. Thank you.</div>').insertAfter("#resend_verification_code");
                }
            }
        });

        });
})
</script>
