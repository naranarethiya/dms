<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class file_manager extends CI_Controller {
	public function __construct() {
		parent::__construct();
		if($this->session->userdata('users_id') == '') {
			redirect(base_url());
		}
	}
	
	function index() {
		$data['pageTitle']="FileManager";
		$data['title']="FileManager";
		$this->load->model('dms_model');
		$data['extfolder']=$this->dms_model->listout_folder();
		//dsm($data['extfolder']); die;
		$data['extfolder']=$data['extfolder']['folders'];
		foreach ($data['extfolder'] as $value) {
			$subfolders=$this->dms_model->get_folders($value['folder_id']);
		}
		$new_arr=array();
		foreach ($subfolders as $key => $value) {
			$new_arr[$value['parent_folder_id']]=$value;
		}
		$data['subfolder']=$new_arr;
		//dsm($data); die;
		$data['contant']=$this->load->view('view_data',$data,true);		
		$this->load->view('master',$data);		
	}

	function create_folder() {
		$this->load->model('dms_model');
		$this->load->model('user_model');
		/* Parent folder details */
		$filter['WHERE']=array('parent_folder_id'=>$this->session->userdata('home_folder'));
		$data['parent_folder']=$this->dms_model->get_folders('',$filter);
		/* (Owner) User details */

		$data['owner']=$this->user_model->get_users(array('parent_user'=>$this->session->userdata('parent_user')));
		$this->load->view('folder_addform',$data);
	}

	function save_folder() {
		//dsm($this->input->post()); die;
		/* Including Validation Library */
		$this->load->model('dms_model');
		$this->load->model('user_model');
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		$this->form_validation->set_rules('parent_folder_id', 'Parent Folder', 'required');
		$this->form_validation->set_rules('owner_id', 'Owner', 'required');
		$this->form_validation->set_rules('folder_name', 'Folder Name', 'required|min_length[1]|max_length[100]|regex_match[/^[a-zA-Z0-9_ ]+$/]');

		if ($this->form_validation->run() == FALSE) {
			set_message(validation_errors());
			redirect_back();		
			return 0;
			die;
		}

		$parent_folder_id = $this->input->post('parent_folder_id');		
		$owner_id = $this->input->post('owner_id');		
		$folder_name = $this->input->post('folder_name');		
		$description = $this->input->post('description');

		/* Parent folder details */
		$filter['WHERE']=array('folder_id'=>$parent_folder_id);
		$parent_folder=$this->dms_model->get_folders('',$filter);

		$folder_data=array(
			'folder_name'=>$folder_name,
			'parent_folder_id'=>$parent_folder_id,
			'owner_id'=>$owner_id,
			'inherited_access'=>'1',
			'default_access'=>'0',
			'real_path'=>$parent_folder[0]['folder_name'].'/'.$folder_name.'/',
			'created_by'=>$this->session->userdata('users_id'),
			'created_at'=>date('Y-m-d H:i:s')
		);

		$res=$this->user_model->add_folder($folder_data);
		$folder_id=$this->db->insert_id();
		if($res) {
			/* updating id path of folder*/
			$id_path=$parent_folder_id.'/'.$folder_id.'/';
			$up_folderdata=array('id_path'=>$id_path);
			$res4=$this->user_model->update_folder($up_folderdata,$folder_id);	

			mkdir(DOCUMENT_ROOT.$parent_folder[0]['folder_name'].'/'.$folder_name);

			set_message('Folder Created.','success');
			redirect_back();			
		}
		else {
			set_message('something went wrong'.$this->db->_error_message);
			redirect_back();
		}				
	}	


}