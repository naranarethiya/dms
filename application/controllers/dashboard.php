<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class dashboard extends CI_Controller {

	public function __construct() {
		parent::__construct();
		if($this->session->userdata('users_id') == '') {
			redirect(base_url());
		}
	}
	
	function index() {
		$data['pageTitle']="Dashboard";
		$data['title']="Dashboard";
		$this->load->model('dashboard_model');
		$data['contant']=$this->load->view('dashboard','',true);
		$this->load->view('master',$data);
	}

}
