<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent:: __construct();
        $this->load->library('session'); //enable session
    }

	public function index()
	{
		$data['cookie']= 'not true';
		if ($this->input->cookie('remember') == 'true') {
			$data['cookie']= true;	
		}

		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->view('template/header', $data);

		// still require body

		$this->load->view('template/footer');
	}
}

