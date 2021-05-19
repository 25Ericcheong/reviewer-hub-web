<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserProfileStats extends CI_Controller {

    public function __construct() {
        parent:: __construct();
		$this->load->library('session'); //enable session
		$this->load->model('User_model');
		$this->load->model('File_model');
		$this->load->model('Review_model');
		$this->load->model('Rating_model');
		$this->load->library('image_lib');
    }

	public function index()
	{

		$this->load->view('template/header');
		$username_logged_in = $this->session->userdata('username');
		$userID = $this->User_model->get_user_id($username_logged_in);

		// user data loaded
		$user_reputation = $this->User_model->user_reputation($username_logged_in);
		$num_of_reviews = $this->Review_model->reviews_made_by_user($userID);
		$query = $this->User_model->load_user_data($username_logged_in);
		$path_image_dp = "";
		$table = 'UserDisplayPictures';

		if ($this->File_model->get_path_image($userID,$table)) {
			$path_image_dp = $this->File_model->get_path_image($userID,$table)["Path"];
		}

		$user_data = array(
			'username' => $query["Username"],
			'email' => $query["Email"],
			'image_dp' => $path_image_dp,
			'reviews_num' => $num_of_reviews,
			'user_reputation' => $user_reputation
		);

		$this->load->view('user_profile_stats_body',$user_data);

		$this->load->view('template/footer');
	}


	// collect data that is user specific for data input for charts
	// method is used to gather information for heatmap and/or area charts for user profile stats
	public function gather_user_specific_chart() {
		$username = $this->input->post('username');
		$heatmap = $this->input->post('heatmap');
		$area = $this->input->post('area');
		$user_reviews = $this->User_model->reviews_made_by_user($username);

		// set to australia timzone
		date_default_timezone_set('Australia/Queensland');

		// conditions will change the function of the method
		// outputs data to produce heatmap chart
		if ($heatmap) {
			echo json_encode($user_reviews);

		// outputs data to produce area chart
		} else if ($area) {
			// get earliest date when a review was made by this user to find specific date to start aggregating data in the past 10 days
			$days_to_display = array(9, 8, 7, 6, 5, 4, 3, 2, 1, 0);
			$earliest_review_date = $this->Review_model->earliest_review_made_by_user($username)[0]->DateReviewed;
			$today_date = date("Y-m-d H:i:s");
			$review_ratings_dataset = array();

			foreach ($user_reviews as $reviewID => $reviewDetails) {
				foreach ($days_to_display as $day_num) {
					// remember it is 12:00AM of a day
					$previous_days = date('Y-m-d 00:00:00', strtotime(' - '.$day_num.' days'));
					$date_label_data_point = date('d/m/Y', strtotime(' - '.$day_num.' days'));

					if ($day_num == 0) {
						$ratings = $this->Rating_model->count_ratings_on_review($reviewID, $earliest_review_date, $today_date);
					} else {
						$ratings = $this->Rating_model->count_ratings_on_review($reviewID, $earliest_review_date, $previous_days);
					}

					if (array_key_exists($date_label_data_point , $review_ratings_dataset)) {
						$review_ratings_dataset[$date_label_data_point]['bad_ratings'] += $ratings['bad_ratings'];
						$review_ratings_dataset[$date_label_data_point]['good_ratings'] += $ratings['good_ratings'];
					} else {
						$review_ratings_dataset[$date_label_data_point] = $ratings;
					}
				}
			}

			echo json_encode($review_ratings_dataset); 
		}
	}
}
?>