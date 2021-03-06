<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class login extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}
	
	public function index() {
		if($this->session->userdata('uid') != '') {
			redirect(base_url()."dashboard");
		}
		$data['title']="Sign me in";
		$this->load->helper('form');
		$data['contant']=$this->load->view('login','',true);
		$this->load->view('login',$data);
	}
	public function check() { 
		//dsm($this->input->post()); die;
		$username=$this->input->post('username');
		$password=$this->input->post('password');
		$remember_user=false;
		$this->load->model('login_model');
		$data=$this->login_model->app_login($username, $password);
		
		if($data==""){
			set_message("Username or password is wrong");
			redirect(base_url());
		}
		else {
			$this->session->set_userdata($data);	
			redirect(base_url()."dashboard");
		}
	}
	
	public function logout() {
		$this->session->sess_destroy();
		redirect(base_url());
	}

	public function change_password() {
		$opasssword=$this->input->post('opasssword');
		$npasssword=$this->input->post('npasssword');
		$ncpasssword=$this->input->post('ncpasssword');
		$this->load->model('login_model');
		$rs=$this->login_model->change_password($opasssword,$npasssword,$ncpasssword);	
	
		if($rs=="Success") {
			set_message("Password Change Successfully","success");
			redirect(base_url()."dashboard");
		}
		elseif($rs=="Confirm fail") {
			set_message("Confirm Password Done Not Match");
			redirect(base_url()."dashboard");
		}
		elseif($rs=="Old fail") {
			set_message("Old Password Is Wrong","success");
			redirect(base_url()."dashboard");
		}
	}	
}