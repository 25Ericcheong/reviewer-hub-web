<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Rating_model extends CI_Model {

	public function __construct()
    {
        parent::__construct();
    }

    // get all reviews wishlisted by user
    public function get_reviews_wishlisted($userID) {
        $this->db->select('ReviewID');
        $this->db->from('UserRatings');
        $where = array(
            'UserID' => $userID,
            'Favorited' => 'yes'
        );
        $this->db->where($where);
		$query = $this->db->get();
		return $query->result();
    }

    // check database for ratings given by user logged in (if there is any) for specific review
    public function load_specific_user_rating($userID, $reviewID) {
        $this->db->select('Rating, Favorited');
        $this->db->from('UserRatings');
        $where = array(
            'UserID' => $userID,
            'ReviewID' => $reviewID
        );
		$this->db->where($where);
		$query = $this->db->get();
		return $query->result();
    }
    
    // update existing rating row in database
    public function update_user_rating($userID, $reviewID, $rating, $favorited) {
        $where = array(
            'UserID' => $userID,
            'ReviewID' => $reviewID
        );
        $this->db->where($where);

        $data = array (
            'Rating' => $rating,
            'Favorited' => $favorited,
        );
        $this->db->update('UserRatings', $data);
    }

    // register rating given by user to database
    public function register_user_rating($userID, $reviewID, $rating, $favorited) {

        $data = array (
            'UserID' => $userID,
            'ReviewID' => $reviewID,
            'Rating' => $rating,
            'Favorited' => $favorited,
        );
        $this->db->insert('UserRatings', $data);
    }

    // load overall rating of review
    public function overall_review_rating($reviewID) {
        $overall_ratings = 0;

        // find number of people who agreed with review
        $where = array(
            'ReviewID' => $reviewID,
            'Rating' => 'agree'
        );
        $this->db->where($where);
        $this->db->from('UserRatings');
        $good_ratings = $this->db->count_all_results();

        // find number of people who disagreed with review
        $where = array(
            'ReviewID' => $reviewID,
            'Rating' => 'disagree'
        );
        $this->db->where($where);
        $this->db->from('UserRatings');
        $bad_ratings = $this->db->count_all_results();

        $overall_ratings = $good_ratings - $bad_ratings;
        return $overall_ratings;
    }

        // get date of earliest rate on a review
        public function earliest_rating_given() {
            $this->db->select('DateRated');
            $this->db->from('UserRatings');
            $this->db->order_by('DateRated', 'ASC');
            $this->db->limit(1);
            $query = $this->db->get();
            return $query->result();
        }
    
        // used to get number of reviews rated already based on dates inputted
        public function count_reviews_rated($start_date, $end_date) {
    
            // convert start date to time stamp
            if (!$start_date) {
                $formated_start_date = $this->earliest_rating_given()[0]->DateRated;
            } else {
                $formated_start_date = date("Y-m-d H:i:s",strtotime($start_date));
            }
    
            // convert end date to time stamp
            $formated_end_date = date("Y-m-d H:i:s",strtotime($end_date));
    
            $query = $this->db->query("
                SELECT count(DISTINCT ReviewID) AS 'review_rated_count' FROM UserRatings
                WHERE DateRated between '".$formated_start_date."' AND '".$formated_end_date."'
            ");
            return $query->result_array(); 
        }

        // used to get number of upvotes and downvotes for review based on date period inputed as argument
        public function count_ratings_on_review($reviewID, $start_date, $end_date) {
            // for downvotes
            $ratings = array();
            $query = $this->db->query("
                SELECT COUNT(*) FROM UserRatings
                WHERE  ReviewID ='".$reviewID."' AND Rating = 'disagree' AND DateRated between '".$start_date."' AND '".$end_date."'
            ");
            $bad_ratings = $query->result_array()[0];
            $ratings['bad_ratings'] = (int)$bad_ratings["COUNT(*)"];

            // for upvotes
            $query = $this->db->query("
                SELECT COUNT(*) FROM UserRatings
                WHERE  ReviewID ='".$reviewID."' AND Rating = 'agree' AND DateRated between '".$start_date."' AND '".$end_date."'
            ");
            $good_ratings = $query->result_array()[0];
            $ratings['good_ratings'] = (int)$good_ratings["COUNT(*)"];

            return $ratings;
        }
}
?>
