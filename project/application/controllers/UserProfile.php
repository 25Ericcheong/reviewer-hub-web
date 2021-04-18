<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserProfile extends CI_Controller {

    public function __construct() {
        parent:: __construct();
		$this->load->library('session'); //enable session
		$this->load->model('User_model');
    }

	public function index()
	{

		$this->load->view('template/header');

		if (get_cookie("username")) {
			$username_logged_in = get_cookie("username");
		} else if ($this->session->userdata('username')) {
			$username_logged_in = $this->session->userdata('username');
		}

		$query = $this->User_model->load_user_data($username_logged_in);

		$user_data = array(
			'username' => $query["Username"],
			'email' => $query["Email"],
			'phone_number' => $query["Phone"],
			'country' => $query["Country"],
			'occupation' => $query["Occupation"],
			'about' => $query["About"],
			'date_joined' => $query["DateJoined"],
		);

		$this->load->view('user_profile_body',$user_data);

		$this->load->view('template/footer');
	}

	// executed when user clicks edit profile button
	public function edit_profile()
	{
		
	}

	public function logout() {
		$this->session->unset_userdata('logged_in'); //delete login status
		redirect('login'); //redirect user back to login
	}
}
?>