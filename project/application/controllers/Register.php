<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Register extends CI_Controller 
{
    public function __construct() {
        parent:: __construct();
        $this->load->library('session'); //enable session
    }
    
	public function check_register()
	{
		$this->load->model('User_model');
		$password_registered = $this->input->post('password');
		$username_registered = $this->input->post('username');
		$email_registered = $this->input->post('email');

		$username_check = $this->User_model->check_user($username_registered);
		$email_check = $this->User_model->check_register_user($email_registered);

		if ($username_check) {
			echo "Username Exists";
		} else if ($email_check) {
			echo "Email Exists";
		} else {
			// since username and email is unique, an account will be created, user will be logged in straight away and a session is created
			$this->User_model->register_user($username_registered, $email_registered, $password_registered);

			// session creation for user
            $newdata = array(
                'username' => $username_registered,
                'logged_in' => true
            );
            $this->session->set_userdata($newdata);
		}
	}    
}
?>