<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Anon_model extends CI_Model {

	public function __construct()
    {
        parent::__construct();
    }

    // check to see if anon's IP address have been registered in db
	public function check_anon($IP_address) {
		// construct sql query
		$this->db->select('IP_Address');
		$this->db->from('Anons');
        $this->db->where('IP_Address', $IP_address);
		$result = $this->db->get();

		if ($result->num_rows() == 1){
			return true;
		} else {
			return false;
		}
    }

    // register IP address of user when wanting to post anonymously
    public function register_anon_ip($IP_address) {
        $data = array (
            'IP_Address' => $IP_address,
        );
        $this->db->insert('Anons', $data);
    }
}
?>
