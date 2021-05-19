<?php defined('BASEPATH') OR exit('No direct script access allowed');

class VerifyEmail extends CI_Controller {

    public function __construct() {
        parent:: __construct();
		$this->load->library('session'); //enable session
		$this->load->model('User_model');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('captcha');
    }

	public function index() {
		// config for captcha
		$config = array(
			'img_path' => 'uploads/captcha/',
			'img_url' => base_url().'uploads/captcha/',
			'img_width'     => '300',
			'img_height'    => 100,
			'word_length'   => 3,
			'font_size'     => 30
		);
		$captcha = create_captcha($config);
		$data['captchaImg'] = $captcha['image'];

		// unset previous captcha and set new captcha word
		$this->session->unset_userdata('captchaCode');
		$this->session->set_userdata('captchaCode', $captcha['word']);

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

		$this->load->view('template/header', $data);

		$user_feedback = array(
			'success' => "",
			'error' => "",
			'resend' => "",
			'verification' => "",
			'test' => ''
		);
        
		$this->load->view('verify_email_body', $user_feedback);

		$this->load->view('template/footer');
	}
	
    // this method will be used to update existing verification code in database and will also be used to send a new email to user
    public function resend_verification_code() {
		function generateSalt($max = 64) {
			$characterList = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*?";
			$i = 0;
			$salt = "";
			while ($i < $max) {
				$salt .= $characterList{mt_rand(0, (strlen($characterList) - 1))};
				$i++;
			}
			return $salt;
		}

		$username = $this->input->post('username');
		$user_email = $this->User_model->load_user_data($username)['Email'];
		$verification_code = generateSalt(8);
		$this->User_model->reset_verification_code($username, $verification_code);

		// send user new verification code
		$config = Array(
            'protocol' => 'smtp',
            'smtp_host' => 'mailhub.eait.uq.edu.au',
            'smtp_port' => 25,
            'mailtype' => 'html',
            'charset' => 'iso-8859-1',
            'wordwrap' => TRUE ,
            'mailtype' => 'html',
            'starttls' => true,
            'newline' => "\r\n"
            );
           
        $this->email->initialize($config);
        $this->email->from(get_current_user().'@student.uq.edu.au',get_current_user());
        $this->email->to($user_email);
		$this->email->subject('Reviewers Hub : New email verification code');
	
		$message = "Hi, ".$username.". <br> This is your new email verification code : ".$verification_code."<br> You can visit this link (https://infs3202-8a85b4a1.uqcloud.net/project/VerifyEmail/) to verify your email! Thank you very much for signing up with us!";

        $this->email->message($message);
		$this->email->send();
		
    }
	
	public function verify_email() 
	{
		// config for captcha
		$config = array(
			'img_path' => 'uploads/captcha/',
			'img_url' => base_url().'uploads/captcha/',
			'img_width'     => '300',
			'img_height'    => 100,
			'word_length'   => 3,
			'font_size'     => 30
		);
		$captcha = create_captcha($config);
		$data['captchaImg'] = $captcha['image'];

		// unset previous captcha and set new captcha word
		$this->session->unset_userdata('captchaCode');
		$this->session->set_userdata('captchaCode', $captcha['word']);
	
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

		$this->load->view('template/header', $data);
		$user_feedback = array(
			'success' => "",
			'error' => "",
			'resend' => "",
			'verification' => "",
			'test' => ''
		);

		$username = $this->session->userdata('username');
		$verification_code = $this->input->post('verification_code');

		// check verification code sent
		if ($verification_code) {
			if ( $this->User_model->check_verification_code($username, $verification_code) ) {
				$user_feedback['success'] = '<p class="col-sm-12 mt-2 mb-0 text-success text-center">Your email has successfully been verified! You and everyone else can now see the status.</p>';
	
			} else {
				$user_feedback['error'] = '<p class="col-sm-12 mt-2 mb-0 text-danger text-center">The verification code that you have entered is wrong. Please try again.</p>';
			}
		} else {
			$user_feedback['error'] = '<p class="col-sm-12 mt-2 mb-0 text-danger text-center">Please enter your verification code please.</p>';
		}
		$user_feedback['verification'] = $verification_code;

        $this->load->view('verify_email_body', $user_feedback);
		
		$this->load->view('template/footer');
	}
}
?>
