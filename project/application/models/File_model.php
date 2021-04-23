<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class File_model extends CI_Model {

	public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
    }

    // upload display picture of user
    public function upload_user_display_pic($filename, $path, $userID) {

        $data = array (
            'Filename' => $filename,
            'Path' => $path,
            'UserID' => $userID
        );
        $this->db->insert('UserDisplayPictures', $data);
    }

    // upload new display picture of user
    public function update_user_display_pic($filename, $path, $userID) {
        $data = array (
            'Filename' => $filename,
            'Path' => $path,
        );

        $this->db->where('UserID', $userID);
        $this->db->update('UserDisplayPictures', $data);
    }

    // will return path for image depending on type of images required to be displayed
    public function get_path_image($userID, $table) {

        // construct sql query for Tokens table
		$this->db->select('Path');
		$this->db->from($table);
		$this->db->where('UserID', $userID);
		$query = $this->db->get();
        
		return $query->row_array();
    }
}
?>
