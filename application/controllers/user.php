<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class user extends CI_Controller {

	public function __construct() {
		parent::__construct();
		if($this->session->userdata('uid') == '') {
			redirect(base_url());
		}
	}
	
	function view_user() {
		$data['pageTitle']="Company User Details";
		$data['title']="Company User Details";
		$this->load->model('dashboard_model');
		$this->load->helper('form');
		$data['user']=$this->dashboard_model->get_companies_user();
		$data['contant']=$this->load->view('user_list',$data,true);
		$this->load->view('master',$data);		
	}

	function search_user() {
		$data['pageTitle']="Company User Details";
		$data['title']="Company User Details";
		$this->load->model('dashboard_model');
		$this->load->helper('form');
		$companyname=$this->input->post('companyname');
		$name=$this->input->post('name');
		$mobile = $this->input->post('mobile');
		$from_date=$this->input->post('from_date');
		$to_date=$this->input->post('to_date');			
		$data['user']=$this->dashboard_model->get_companies_user($companyname,$name,$mobile,$from_date,$to_date);
		$data['contant']=$this->load->view('user_list',$data,true);
		$this->load->view('master',$data);		
	}	
}