<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Review_model extends CI_Model {

	public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
    }

    // since title column is unique, will need to check to ensure that column does not exist
    public function check_review($title) {
        // construct sql query
		$this->db->select('ReviewTitle');
		$this->db->from('Reviews');
		$this->db->where('ReviewTitle', $title);
		$result = $this->db->get();

		// this checks if review exists already
		if ($result->num_rows() == 1){
            return true;
		} else {
			return false;
		}
    }

    // get first 5 rows of reviews for timeline
    public function get_reviews() {
        $query = $this->db->query('SELECT * FROM Reviews');
        return $query->num_rows(5);
    }

    // since title is unique, we can get review id with it
    public function get_review_id($title) {
        // construct sql query
        $review_data = $this->load_review_data($title);
		return $review_data["ReviewID"];
    }

    // used to get user who created specific review
    public function get_user_for_review($reviewID) {
        $this->db->select('Username');
		$this->db->from('UserReviews');
		$this->db->where('ReviewID', $reviewID);
		$query = $this->db->get();
		return $query->row_array();
    }

    // register user with review
    public function register_user_with_review($userID, $username, $reviewID) {
        $data = array (
            'UserID' => $userID,
            'Username' => $username,
            'ReviewID' => $reviewID
        );
        $this->db->insert('UserReviews', $data);
    }

    // load user details from database
	public function load_review_data($title = null, $reviewID = null)
	{
		$this->db->select('*');
        $this->db->from('Reviews');

        if ($title) {
            $this->db->where('ReviewTitle', $title);
        } else if ($reviewID) {
            $this->db->where('ReviewID', $reviewID);
        }

		$query = $this->db->get();
		return $query->row_array();
	}

    // create row in reviews table - essentially registering review in database
	public function register_review($title, $description, $country, $brand, $quality_rating, $quality_comment, $usability_rating, $usability_comment, $price_rating, $price_comment, $recommendation, $summary) {

        $data = array (
            'ReviewTitle' => $title,
            'Description' => $description,
            'ReviewedIn' => $country,
            'Brand' => $brand,
            'QualityRating' => $quality_rating,
            'QualityComment' => $quality_comment,
            'UsabilityRating' => $usability_rating,
            'UsabilityComment' => $usability_comment,
            'PricingRating' => $price_rating,
            'PricingComment' => $price_comment,
            'Recommendation' => $recommendation,
            'RecommendationComment' => $summary,
        );
        $this->db->insert('Reviews', $data);
    }

    // search database for reviews related to what was typed in search box and try to suggest possibly related reviews
    public function suggest_reviews_title($title) {
        $query = $this->db->query("
        SELECT * FROM Reviews WHERE INSTR(ReviewTitle,'".$title."') > 0");
        // where INSTR( content, "test" ) > 0
        return $query->result();
    }

    // fetch reviews for continuous scrolling
    public function fetch_reviews($limit, $start) {
        $this->db->select("*");
        $this->db->from("Reviews");
        $this->db->order_by("ReviewID", "DESC");
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        return $query->result();
    }

    // find earliest review made by any user
	public function earliest_review_made() {
		$this->db->select('DateReviewed');
		$this->db->from('Reviews');
		$this->db->order_by('DateReviewed', 'ASC');
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result();
    }

    // find earliest review made by a user
	public function earliest_review_made_by_user($username) {
        $userID = $this->User_model->get_user_id($username);

		$this->db->select('DateReviewed');
        $this->db->from('UserReviews');
        $this->db->join('Reviews', 'Reviews.ReviewID = UserReviews.ReviewID');
        $this->db->order_by('DateReviewed', 'ASC');
        $this->db->where('UserID', $userID);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result();
    }
    
    // used to get number of reviews created from the first date a row was inserted to date inputted as argument, if there is no start_date, then method will count from the earliest row existed
	// all dates are expected to be received as such mm/dd/yyyy
	public function count_reviews_made($start_date, $end_date) {

		// convert start date to time stamp
		if (!$start_date) {
			$formated_start_date = $this->earliest_review_made()[0]->DateReviewed;
		} else {
			$formated_start_date = date("Y-m-d H:i:s",strtotime($start_date));
		}

		// convert end date to time stamp
		$formated_end_date = date("Y-m-d H:i:s",strtotime($end_date));

		$query = $this->db->query("
			SELECT count(*) AS 'review_count' FROM Reviews
			WHERE DateReviewed between '".$formated_start_date."' AND '".$formated_end_date."'
		");
		return $query->result_array(); 
    }

    // find number of reviews made by user
	public function reviews_made_by_user($userID) {

        $where = array(
            'UserID' => $userID,
        );
        $this->db->where($where);
        $this->db->from('UserReviews');
        return $this->db->count_all_results();
    }
}
?>
