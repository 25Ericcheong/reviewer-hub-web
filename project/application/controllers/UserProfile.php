<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserProfile extends CI_Controller {

    public function __construct() {
        parent:: __construct();
		$this->load->library('session'); //enable session
		$this->load->model('User_model');
		$this->load->model('File_model');
		$this->load->library('image_lib');
    }

	public function index()
	{

		$this->load->view('template/header');
		$username_logged_in = $this->session->userdata('username');
		$userID = $this->User_model->get_user_id($username_logged_in);

		$query = $this->User_model->load_user_data($username_logged_in);
		$path_image_dp = "";
		$table = 'UserDisplayPictures';

		if ($this->File_model->get_path_image($userID,$table)) {
			$path_image_dp = $this->File_model->get_path_image($userID,$table)["Path"];
		}

		$user_data = array(
			'username' => $query["Username"],
			'email' => $query["Email"],
			'phone_number' => $query["Phone"],
			'country' => $query["Country"],
			'occupation' => $query["Occupation"],
			'about' => $query["About"],
			'date_joined' => $query["DateJoined"],
			'error' => '',
			'success' => '',
			'image_dp' => $path_image_dp
		);

		$this->load->view('user_profile_body',$user_data);

		$this->load->view('template/footer');
	}

	// executed when user clicks edit profile button
	public function edit_profile() {
		$username = $this->input->post('username');
        $occupation = $this->input->post('occupation');
		$phone = $this->input->post('phone');
		$about = $this->input->post('about');
		$country = $this->input->post('country');
		
		$this->User_model->edit_user_profile($username, $occupation, $phone, $about, $country);
		echo "Changes Saved";
	}

	// will upload file and register path of file to database and will send any relevant info back to user profile for user to look into
	public function upload_user_display_pic()
	{
        $this->load->view('template/header');

		$username_logged_in = $this->session->userdata('username');
		$userID = $this->User_model->get_user_id($username_logged_in);
		$query = $this->User_model->load_user_data($username_logged_in);
		$path_image_dp = "";
		$table = 'UserDisplayPictures';

		if ($this->File_model->get_path_image($userID,$table)) {
			$path_image_dp = $this->File_model->get_path_image($userID,$table)["Path"];
		}
        
        // data that will be passed into body of user profile body
		$user_data = array(
			'username' => $query["Username"],
			'email' => $query["Email"],
			'phone_number' => $query["Phone"],
			'country' => $query["Country"],
			'occupation' => $query["Occupation"],
			'about' => $query["About"],
			'date_joined' => $query["DateJoined"],
			'error' => '',
			'success' => '',
			'image_dp' => $path_image_dp
        );
                
        $config['upload_path'] = './uploads/user_display_pic/';
		$config['allowed_types'] = 'jpg|mp4|png';
		$config['max_size'] = 10000;
		$config['max_width'] = 1000;
		$config['max_height'] = 1000;
		$config['min_width'] = 500;
		$config['min_height'] = 500;

		$config['overwrite'] = true;
		$config['file_name'] = 'User'.$userID;

		$this->load->library('upload', $config);
		$this->upload->initialize($config);

        // before loading user profile body, will need to check for upload status
        if ( !$this->upload->do_upload('input_image') ) {
			$user_data["error"] = $this->upload->display_errors();
			
            $this->load->view('user_profile_body',$user_data);
        } else {

			$ori_path = $this->upload->data('full_path');
			// path needs to be process for it to work on browser
			$path = str_replace('/var/www/htdocs/project/', base_url(),$ori_path);

			$filename = $this->upload->data('file_name');

			// if display pic exists, will be overwritten on db, nothing should be same since everything has been normalized but just incase path changes
			if (!$this->File_model->get_path_image($userID,$table)) {
				$this->File_model->upload_user_display_pic(
					$filename,
					$path,
					$userID
				);
			} else {
				$this->File_model->update_user_display_pic(
					$filename,
					$path,
					$userID
				);
			}

			$user_data["success"] = "File has been uploaded";
			
			// before loading, should check if height or width > 500, then will need to process images and resize them then.
			$uploaded_height = $this->upload->data('image_height');
			$uploaded_width = $this->upload->data('full_width');

			if ($uploaded_height > 500 || uploaded_width > 500) {
				$config_resize['image_library'] = 'gd2';
				$config_resize['source_image'] = $ori_path;
				$config_resize['maintain_ratio'] = TRUE;
				$config_resize['width'] = 500;
				$config_resize['height'] = 500;

				$this->load->library('image_lib', $config_resize);
				$this->image_lib->initialize($config_resize);
				
				if (!$this->image_lib->resize()) {
					$user_data["error"] = $this->image_lib->display_errors();
				}
			}

            $this->load->view('user_profile_body',$user_data);
		}

        $this->load->view('template/footer');
	}


}
?>