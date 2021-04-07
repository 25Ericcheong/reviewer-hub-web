<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserReview extends CI_Controller {

    public function __construct() {
        parent:: __construct();
        $this->load->library('session'); //enable session
    }

	public function index()
	{
		$data['cookie']= 'not true';
		if ($this->input->cookie('remember') == 'true') {
			$data['cookie']= true;	
		} 
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->view('template/header', $data);

		// if (!$this->session->userdata('logged_in')) //check if user already login
		// {
		// 	$this->load->view('login', $data); //if user has not login ask user to login
		// }else{
		// 	$this->load->view('welcome_message'); //if user already login-ed show main page
		// }

		$this->load->view('template/footer');
	}
	public function check_login()
	{
		$this->load->model('user_model');
		$data['error']= "<div class=\"alert alert-danger\" role=\"alert\"> Incorrect username or passwrod!! </div> ";
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->view('template/header');
		$username = $this->input->post('username'); //getting username from login form
		$password = $this->input->post('password'); //getting password from login form
		if (!$this->session->userdata('logged_in')) {
			if ( $this->user_model->login($username, $password) ) {
				$user_data = array(
					'username' => $username,
					'logged_in' => true //create session variable
				);
				$this->session->set_userdata($user_data); //set user status to login in session
				redirect('login'); //direct user home page
			} else {
				$this->load->view('login', $data); //if username password incorrect, show error msg
			}	
		} else { 
			redirect('login'); //if user already login-ed direct user to home page
			$this->load->view('template/footer');
		}
	}

	public function logout() {
		$this->session->unset_userdata('logged_in'); //delete login status
		redirect('login'); //redirect user back to login
	}
}

