<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent:: __construct();
		$this->load->library('session'); //enable session
		$this->load->model('User_model');
    }

	public function index()
	{
		// check if there was a cookie saved previously
		if (get_cookie("remember")) {

			$token = get_cookie("token");

			// verify token, if true, give access to user
			// check if there is a cookie saved and check token with token saved in database
			if ($this->User_model->check_token($token)) {
				$username = $this->User_model->get_username_with_token($token);

				// create session since user exists
				$newdata = array(
					'username' => $username,
					'logged_in' => true
				);
				$this->session->set_userdata($newdata);
			}

			echo $this->User_model->get_username_with_token($token)."<br>";
			echo md5($token)."<br>";
		}

		$this->load->view('template/header');

		// still require body

		$this->load->view('template/footer');
	}
}
?>