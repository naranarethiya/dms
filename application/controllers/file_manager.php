<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class file_manager extends CI_Controller {
	public function __construct() {
		parent::__construct();
		if($this->session->userdata('users_id') == '') {
			redirect(base_url());
		}
	}
	
	function index($folder_id=false) {
		$data['pageTitle']="FileManager";
		$data['title']="FileManager";
		$this->load->model('dms_model');
		if($folder_id!='') {
			$data['extfolder']=$this->dms_model->listout_folder($folder_id);
			$data['folder_id']=$folder_id;
		}
		else {
			$data['extfolder']=$this->dms_model->listout_folder();
		}
		//dsm($data['extfolder']); die;
		if(!empty($data['extfolder'])) {
			$subfolders=array();
			$folders=$data['extfolder']['folders'];
			foreach ($folders as $value) {
				$subfolders=$this->dms_model->get_folders($value['folder_id']);
			}
			$new_arr=array();
			foreach ($subfolders as $key => $value) {
				$new_arr[$value['parent_folder_id']]=$value;
			}
			$data['extfolder']['subfolder']=$new_arr;
		}
		//dsm($data['extfolder']); die;
		$data['contant']=$this->load->view('view_data',$data,true);		
		$this->load->view('master',$data);		
	}

	function create_folder($parent_folder_id=false) {
		$this->load->model('dms_model');
		$this->load->model('user_model');
		if($parent_folder_id!='') {
			$data['parent_folder_id']=$parent_folder_id;
		}
		else {
			$data['parent_folder_id']=$this->session->userdata('home_folder');
		}
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
			'description'=>$description,
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

	function create_file($parent_folder_id=false) {
		$this->load->model('dms_model');
		$this->load->model('user_model');
		if($parent_folder_id!='') {
			$data['parent_folder_id']=$parent_folder_id;
		}
		else {
			$data['parent_folder_id']=$this->session->userdata('home_folder');
		}
		/* (Owner) User details */
		$data['owner']=$this->user_model->get_users(array('parent_user'=>$this->session->userdata('parent_user')));
		/* Keyword details*/
		$data['keyword']=$this->dms_model->get_user_keyword(array('user_id'=>$this->session->userdata('users_id')));
		$this->load->view('file_addform',$data);
	}

	function save_file() {
		//dsm($this->input->post()); die;

		$this->load->model('dms_model');
		$this->load->model('user_model');
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		$this->form_validation->set_rules('owner_id', 'Owner', 'required');
		$this->form_validation->set_rules('file_title', 'File Title', 'required|min_length[1]|max_length[100]|regex_match[/^[a-zA-Z0-9_ ]+$/]');

		if ($this->form_validation->run() == FALSE) {
			set_message(validation_errors());
			redirect_back();		
			return 0;
			die;
		}

		$parent_folder_id = $this->input->post('parent_folder_id');		
		$owner_id = $this->input->post('owner_id');		
		$file_title = $this->input->post('file_title');		
		$description = $this->input->post('description');
		$keywords = $this->input->post('keywords');

		/* Parent folder details */
		$filter['WHERE']=array('folder_id'=>$parent_folder_id);
		$parent_folder=$this->dms_model->get_folders('',$filter);

		$document_data=array(
			'file_title'=>$file_title,
			'parent_folder_id'=>$parent_folder_id,
			'owner_id'=>$owner_id,
			'description'=>$description,
			'keywords'=>$keywords,
			'shareable'=>'0',
			'inherited_access'=>'1',
			'default_access'=>'0',
			'locked'=>'0',
			'created_by'=>$this->session->userdata('users_id'),
			'created_at'=>date('Y-m-d H:i:s')
		);
		$res=$this->dms_model->add_document_data($document_data);
		$document_id=$this->db->insert_id();
		if($res){
			/* file upload */
			if($_FILES['file']['name'][0]!='') {
				$file=$_FILES['file'];
				$ext_file=pathinfo($file['name'],PATHINFO_EXTENSION);
				if($ext_file=="png" || $ext_file=="jpg" || $ext_file=="jpeg" || $ext_file=="gif" || $ext_file=="doc"|| $ext_file=="docx"|| $ext_file=="ppt"|| $ext_file=="pptx"|| $ext_file=="xls"|| $ext_file=="xlsx" || $ext_file=="pdf" || $ext_file=="txt") {

					$file_name=$file['name'];
					$file_size=$_FILES['file']['size'];
					$folder=DOCUMENT_ROOT.$parent_folder[0]['real_path'];
					$upload=$folder.$file_name;
					$storepath=$folder.$file_name;
					$idpath=$parent_folder[0]['id_path'];
					move_uploaded_file($file['tmp_name'],$upload);	
					$documentfile_data=array(
						'document_id'=>$document_id,
						'file_name'=>$file_name,
						'file_path'=>$storepath,
						'file_path_id'=>$idpath,
						'file_size'=>$file_size,
						'file_mimetype'=>$ext_file,
						'file_extension'=>$ext_file,
						'user_id'=>$this->session->userdata('users_id'),
						'file_comment'=>$description,
						'created_at'=>date('Y-m-d H:i:s')
					);	
					$res1=$this->dms_model->add_documentfile_data($documentfile_data);
				}
				else {
					set_message('Undefined file format');
					redirect_back();
				}				
			}
			if($res1){
				set_message('files uploaded successfully','success');
				redirect_back();
			}
			else {
				set_message('Somthing went wrong');
				redirect_back();			
			}			
		}	
		else {
			set_message('something went wrong'.$this->db->_error_message);
			redirect_back();
		}	
	}
}