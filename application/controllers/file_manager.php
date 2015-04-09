<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class file_manager extends CI_Controller {
	function index() {
		$this->load->model('dms_model');
		$home_folder=1;
		//$home_folder=$this->session->userdata('home_folder');
		$user_data='4';
		//$user_data=$this->session->all_userdata();	
		$list=$this->dms_model->listout_folder($home_folder,$user_data);
		dsm($list);
	}
}