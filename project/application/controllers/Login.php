<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends CI_Controller
{

    public function __construct() {
        parent:: __construct();
		$this->load->library('session'); //enable session
		$this->load->model('User_model');
    }

    // method doesn't load a page but rather helps with checking if username and password is correct which then helps ajax decide on what to do
    public function check_login() {
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
                $userID = $this->User_model->get_user_id($username_input);

                // if cookie expired and token is still in database, will need to remove from database to prevent any errors
                if ($this->User_model->check_token_with_userID($userID)) {
                    $this->User_model->remove_token($userID);
                }

                // Generate a random string.
                $token = openssl_random_pseudo_bytes(16);

                // Convert the binary data into hexadecimal representation.
                $token = bin2hex($token);

                // register token to database
                $this->User_model->register_token($username_input, $token);

                set_cookie("token", $token, 300); // set token
				set_cookie("remember", $remember_me, 300); //set cookie remember
            }

            echo "User Exists"; // username found in database
        } else {
            echo ""; // username inputted is not found in database
        }
    }

    public function logout() {
        // when logging out, will need to destroy token in database
        $username = $this->session->userdata('username');
        $userID = $this->User_model->get_user_id($username);
        $this->User_model->remove_token($userID);

        // upon logging out, both cookies and sessions will be destroyed
        $remove_keys = array('username', 'logged_in');
        $this->session->unset_userdata($remove_keys); //delete login status

        // delete all cookies
        delete_cookie("remember");
        delete_cookie("token");
		redirect('Home'); //redirect user back to homepage
	}

}
?>