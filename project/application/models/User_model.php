<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User_model extends CI_Model {

	public function __construct()
    {
        parent::__construct();
    }

	// Checks to see if username and password exists in database
	public function check_user($username, $password = "") 
	{
		// construct sql query
		$this->db->select('Username, Password');
		$this->db->from('Users');
		$this->db->where('Username', $username);

		// there will be times where only username is checked to see if it exists in database
		if ($password != "") {
			$this->db->where('Password', $password);
		}

		// will get query result
		$result = $this->db->get();

		if ($result->num_rows() == 1){
			return true;
		} else {
			return false;
		}
	}

	// check to ensure email address is unique
	public function check_register_user($email) {
		// construct sql query
		$this->db->select('Username, Password');
		$this->db->from('Users');
		$this->db->where('Email', $email);
		$result = $this->db->get();

		if ($result->num_rows() == 1){
			return true;
		} else {
			return false;
		}
	}

	// registers user to database
	public function register_user($username, $email, $password) {
		// data to be inserted into database
		$data = array(
			'Username' => $username,
			'Password' => $password,
			'Email' => $email
		);

		$this->db->insert('Users', $data);
	}

	// load user details from database
	public function load_user_data($username)
	{
		$this->db->select('*');
		$this->db->from('Users');
		$this->db->where('Username', $username);
		$query = $this->db->get();
		return $query->row_array();
	}

	// get user ID registered in database
	public function get_user_id($username) 
	{
		$user_data = $this->load_user_data($username);
		return $user_data["UserID"];
	}

	// will insert editted user data profile into database
	public function edit_user_profile($username, $occupation, $phone, $about, $country) {
		$data = array(
			'Country' => $country,
			'Occupation' => $occupation,
			'About' => $about,
			'Phone' => $phone
		);

		$userID = $this->get_user_id($username);
		$this->db->where('UserID', $userID);
		$this->db->update('Users', $data);
	}
}
?>
