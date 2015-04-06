<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class filemanager extends CI_Controller {

	public function __construct() {
		parent::__construct();
		if($this->session->userdata('uid') == '') {
			redirect(base_url());
		}
	}
	
	function index() {
		$data['pageTitle']="FileManager";
		$data['title']="FileManager";
		$this->load->model('dashboard_model');
		$data['extfolder']=$this->dashboard_model->get_companyfolders();
		$data['contant']=$this->load->view('view_data',$data,true);		
		$this->load->view('master',$data);		
	}	
	
	function create_folder() {
		$this->load->model('dashboard_model');
		$this->load->helper('form');
		$data['company']=$this->dashboard_model->get_companies();
		$data['folder']=$this->dashboard_model->get_folders();
		$data['extfolder']=$this->dashboard_model->get_companyfolders();
		$this->load->view('folder_addform',$data);
	}

	function save_folder() {
		/* Including Validation Library */
		$this->load->model('dashboard_model');
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		$this->form_validation->set_rules('dms_companyid', 'Company Name', 'required');		
		$this->form_validation->set_rules('dms_foid', 'Parent Folder Name', 'required');
		$this->form_validation->set_rules('foldername', 'Folder Name', 'required|min_length[1]');		
		if ($this->form_validation->run() == FALSE) {
			set_message(validation_errors());
			redirect(base_url().'dashboard/create_folder');
			return 0;
		}
		else {
			$dms_foid_2=$this->input->post('dms_foid');
			$dms_companyid=$this->input->post('dms_companyid');
			$dms_foldername=$this->input->post('foldername');
			$created_at=date('Y-m-d H:i:s');
			$fdata=array(
				'dms_foid_2'=>$dms_foid_2,
				'dms_companyid'=>$dms_companyid,
				'dms_foldername'=>$dms_foldername,
				'created_at'=>$created_at
			);
			$res=$this->dashboard_model->add_folder($fdata);
			if($res) {
				/* main root folder */
				$this->check_root();
				$company=$this->dashboard_model->get_companies($dms_companyid);
				$companyname=$company[0]['dms_companyname'];
				/* company folder */
				$this->check_company($companyname);
				/* folder of company */
				$this->check_company($companyname, $dms_foldername);
				
				$this->session->set_flashdata('success','New Folder created successfully');
				redirect(base_url().'dashboard/view_folder');				
			}
			else {
				$this->session->set_flashdata('error','Somthing went wrong');
				redirect(base_url().'dashboard/create_folder');			
			}
		}		
	}

	function check_root() {
		$referer=$_SERVER['HTTP_REFERER'];
		$server=parse_url($referer);
		/* main root folder */
		if(!file_exists('./dms_doc') || !is_dir('./dms_doc')) {
			mkdir("./dms_doc");
		}
	}

	function check_company($companyname,$folder=false) {
		/* company folder */
		$user_folder_path='./dms_doc/'.$companyname;
		if(!file_exists($user_folder_path) || !is_dir($user_folder_path)) {
			mkdir($user_folder_path);
		}
		/* folder of company */
		if($folder!="") {
			$folder_path='./dms_doc/'.$companyname.'/'.$folder;
			if(!file_exists($folder_path) || !is_dir($folder_path)) {
				mkdir($folder_path);
			}			
		}
	}		
}