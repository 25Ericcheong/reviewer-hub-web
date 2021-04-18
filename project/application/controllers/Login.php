<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends CI_Controller
{
    // method doesn't load a page but rather helps with checking if username and password is correct which then helps ajax decide on what to do
    public function check_login() {
        $this->load->model('User_model'); // load User_model
        $username_input = $this->input->post('username');
        $password_input = $this->input->post('password');
        $remember_me = $this->input->post('remember');

        $data = $this->User_model->check_user($username_input, $password_input); // send query to user_model for checking and will return "" or "User Exists"


        if ($data) {
            // create session always when user exists in database and logging in
            $newdata = array(
                'username' => $username_input,
                'logged_in' => true
            );
            $this->session->set_userdata($newdata);

            // create cookie since remember me was checked
            if ($remember_me == "true") {
                set_cookie("username", $username_input, 300); //set cookie username
				set_cookie("password", $password_input, 300); //set cookie password
				set_cookie("remember", $remember_me, 300); //set cookie remember
            }

            echo "User Exists"; // username found in database
        } else {
            echo ""; // username inputted is not found in database
        }
    }

    public function logout() {
        // upon logging out, both cookies and sessions will be destroyed
        $remove_keys = array('username', 'logged_in');
        $this->session->unset_userdata($remove_keys); //delete login status
        // delete all cookies
		delete_cookie("username");
		delete_cookie("password");
		delete_cookie("remember");
		redirect('Home'); //redirect user back to homepage
	}

}
?>