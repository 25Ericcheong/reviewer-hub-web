<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wishlist extends CI_Controller {

    public function __construct() {
        parent:: __construct();
		$this->load->library('session'); //enable session
		$this->load->model('User_model');
		$this->load->model('Review_model');
		$this->load->model('File_model');
		$this->load->model('Rating_model');
		$this->load->helper('captcha');
    }

	// loads all reviews that have been added to wishlist
	public function index()
	{
		$this->load->view('template/header');

		// get id based on user logged in currently, note that only users who are logged in should be able to see this page anyway
		$review_wishlisted_data = array();
		$review_wishlisted_data['reviews'] = array();
		$username = $this->session->userdata('username');
		$userID = $this->User_model->get_user_id($username);
		$reviewIDs_wishlisted = $this->Rating_model->get_reviews_wishlisted($userID);
		
		// get data on wishlisted reviews to be displayed on view 
		foreach($reviewIDs_wishlisted as $reviewID_wishlisted) {
			$reviewID = $reviewID_wishlisted->ReviewID;

			// information on review is loaded
			$review_data = $this->Review_model->load_review_data(null, $reviewID);
			$temp['reviewTitle'] = $review_data['ReviewTitle'];
			$temp['description'] = $review_data['Description'];
			$temp['reviewDate'] = $review_data['DateReviewed'];

			// get path for images for review
			$review_image_path = $this->File_model->get_review_images($reviewID)[0]['Path'];
			$temp['reviewImagePath'] = $review_image_path;

			array_push($review_wishlisted_data['reviews'], $temp);
		}

		$this->load->view('wishlist_body', $review_wishlisted_data);
		$this->load->view('template/footer');
	}
}
?>