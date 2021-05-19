<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SearchedReview extends CI_Controller {

    public function __construct() {
        parent:: __construct();
		$this->load->library('session'); //enable session
		$this->load->model('User_model');
		$this->load->model('Review_model');
        $this->load->model('File_model');
		$this->load->model('Comment_model');
		$this->load->model('Rating_model');
		$this->load->helper('captcha');
    }

	public function search($searched_title = null)
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

		}

		$this->load->view('template/header', $data);

		if (!$searched_title) {
			// acquired from search input by user
			$searched_title = $this->input->post('searchTitle');
		}
		
		// need to 'clean' searched_title
		$searched_title = str_ireplace("%20"," ",$searched_title);
		$review_data['searched'] = $searched_title;

		// this would indicate that there is a title inputted and this title cana be found in db
		if ($searched_title && $this->Review_model->load_review_data($searched_title, '')) {
			// body will show results of review title searched along with information for relevant review found
			$review = $this->Review_model->load_review_data($searched_title, '');
	
			$review_data = $review;
			$review_data['searched'] = $searched_title;
			unset($review_data['ReviewID']);
			$reviewID = $review['ReviewID'];

            $review_data['exists'] = true;
            
            // review images and user who is responsible for posting review
			$review_images = $this->File_model->get_review_images($reviewID);
			$review_data['images'] = $review_images;

			// reviews posted can some times be anonymously posted
			// so will need to check if review was possted anonymously or not
			if ($this->Review_model->get_user_for_review($reviewID)) {
				$review_created_by = $this->Review_model->get_user_for_review($reviewID)['Username'];
				$review_data['created_by'] = $review_created_by;

				// since it is not anonymously posted, will let people know if user is verified or not
				$review_data['verified'] = $this->User_model->load_user_data($review_created_by)['EmailStatus'];

			} else {
				$review_data['verified'] = false;
				$review_data['created_by'] = 'Anonymous';
			}
            
            // now need to load comments for specific review posted
            $review_data['comments'] = $this->Comment_model->load_user_comments($reviewID);

			// load comment posted by user himself
			$userID = $this->User_model->get_user_id($this->session->userdata('username'));
			$review_data['user_comment'] = $this->Comment_model->load_specific_user_comment($userID, $reviewID);
			
			if ($userID) {
				$rating_wishlist = $this->Rating_model->load_specific_user_rating($userID, $reviewID);

				if ($rating_wishlist) {
					$favorited = $rating_wishlist[0]->Favorited;
					$rating = $rating_wishlist[0]->Rating;
				} else {
					$favorited = '';
					$rating = '';
				}
				$overall_rating = $this->Rating_model->overall_review_rating($reviewID);

				$review_data['favorited'] = $favorited;
				$review_data['overall_rating'] = $overall_rating;
				$review_data['rating'] = $rating;
			}


		} else {
			$review_data['exists'] = false;
		}

		$this->load->view('search_review_body', $review_data);
		$this->load->view('template/footer');
    }
    
    public function post_comment() {

        // comment posted by user
        $comment = $this->input->post('comment');
        $username = $this->session->userdata('username');
		$userID = $this->User_model->get_user_id($username);

		$reviewTitle = $this->input->post('reviewTitle');

        $reviewID = $this->Review_model->load_review_data($reviewTitle)['ReviewID'];

        if ($comment && $username) {

            // if comment exist in database, merely edit
            if ($this->Comment_model->check_posted_comment($username, $reviewID)) {
                $this->Comment_model->update_posted_comment($userID, $username, $reviewID, $comment);

            // since it doesnt exist in database, will need to register to database
            } else {
				$this->Comment_model->register_user_comment($userID, $username, $reviewID, $comment);
				
				
            }

		}
	}
}
?>