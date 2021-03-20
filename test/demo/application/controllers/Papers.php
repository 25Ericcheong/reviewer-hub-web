<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Papers extends CI_Controller {

	public function index()
	{
		$this->load->view('welcome_message');
	}

    //public function read()
    public function write($year)
	{
        if ( ! file_exists(APPPATH.'views/papers/'.$year.'.php')) 
        { 
            show_404(); 
        } 
    
        $data['year'] = $year;
		//$this->load->view('papers/2017');
        $this->load->view('templates/header',$data);
        $this->load->view('papers/'.$year,$data);
        $this->load->view('templates/footer',$data);
	}
}

