<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Comment_model extends CI_Model {

	public function __construct()
    {
        parent::__construct();
    }

    // check to see if comment exists, currently only 1 user account can have 1 comment for a review
	public function check_posted_comment($username, $reviewID) {
		// construct sql query
		$this->db->select('Username, Comments');
		$this->db->from('UserComments');
        $this->db->where('Username', $username);
        $this->db->where('ReviewID', $reviewID);
		$result = $this->db->get();

		if ($result->num_rows() == 1){
			return true;
		} else {
			return false;
		}
    }
    
    // update existing comment row in database
    public function update_posted_comment($userID, $username, $reviewID, $comment) {
        $data = array (
            'Comments' => $comment,
        );

        $this->db->where('UserID', $userID);
        $this->db->update('UserComments', $data);
    }

    // register comment made by user to database
    public function register_user_comment($userID, $username, $reviewID, $comment) {
        $data = array (
            'UserID' => $userID,
            'Username' => $username,
            'ReviewID' => $reviewID,
            'Comments' => $comment
        );
        $this->db->insert('UserComments', $data);
    }

    // load comments made by all users from database for a specific review
    public function load_user_comments($reviewID) {
        $sql = '
        SELECT Username, Comments, DateComment FROM UserComments WHERE ReviewID = ?
        ';
        
        $query = $this->db->query($sql, $reviewID);
		return $query->result_array();
    }

    // load specific user comment for a specific post for user to observer
    public function load_specific_user_comment($userID, $reviewID) {
        $this->db->select('Username, Comments, DateComment');
        $this->db->from('UserComments');
        $where = array(
            'UserID' => $userID,
            'ReviewID' => $reviewID
        );
		$this->db->where($where);
		$query = $this->db->get();
		return $query->row_array();
    }
}
?>
