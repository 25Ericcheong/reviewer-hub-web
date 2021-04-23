<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class File extends CI_Controller {

    public function __construct() {
        parent:: __construct();
		$this->load->library('session'); //enable session
        $this->load->model('User_model');
        $this->load->model('File_model');
    }
}
?>