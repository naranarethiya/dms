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
		if(!$folder_id) {
			$folder_id=$this->session->userdata('home_folder');
		}
		if($folder_id!='') {
			$user=$this->session->all_userdata('users_id');
			$folder=$this->dms_model->get_folders('',array('folder_id'=>$folder_id));
			if(count($folder) > 0) {
				$folder=$folder[0];
			}
			$access=$this->dms_model->get_access_mode($folder,$user,true);
			if($access>='1') {
				$data['extfolder']=$this->dms_model->listout_folder($folder_id);
				$data['folder_id']=$folder_id;
				$data['folder_info']=$folder;
			}
			else {
				set_message('Access deined'.$folder_id);
				redirect_back();
			}
		}
		else {
			$data['extfolder']=$this->dms_model->listout_folder();
		}
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
		$data['owner']=$this->user_model->get_users(array('parent_user'=>$this->session->userdata('users_id')));
		$data['owner'][]=$this->session->all_userdata();
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
		$this->form_validation->set_rules('folder_name', 'Folder Name', 'required|min_length[1]|max_length[100]');


		if ($this->form_validation->run() == FALSE) {
			set_message(validation_errors());
			redirect_back();		
			return 0;
			die;
		}

		$parent_folder_id = $this->input->post('parent_folder_id');		
		$owner_id = $this->input->post('owner_id');		
		$folder_name = $this->input->post('folder_name');
		$replace_chrs=array('/','\\','?','%','*',':','|','"',"'",'<','>');
		$folder_name=str_replace($replace_chrs,"-",$folder_name);
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
			'real_path'=>$parent_folder[0]['real_path'].$folder_name.'/',
			'created_by'=>$this->session->userdata('users_id'),
			'created_at'=>date('Y-m-d H:i:s')
		);

		$res=$this->user_model->add_folder($folder_data);
		$folder_id=$this->db->insert_id();
		if($res) {
			/* updating id path of folder*/
			$id_path=$parent_folder[0]['id_path'].$folder_id.'/';
			$up_folderdata=array('id_path'=>$id_path);
			$res4=$this->user_model->update_folder($up_folderdata,$folder_id);	

			mkdir(DOCUMENT_ROOT.$parent_folder[0]['real_path'].$folder_name);

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
		$this->form_validation->set_rules('file_title', 'File Title', 'required|min_length[1]|max_length[100]');

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
					/* replace special characters */
					$replace_chrs=array('/','\\','?','%','*',':','|','"',"'",'<','>');
					$file_name=str_replace($replace_chrs,"-",$file_name);
					$file_size=$_FILES['file']['size'];
					$folder=ROOT_FOLDER.$parent_folder[0]['real_path'];
					$upload=DOCUMENT_ROOT.$parent_folder[0]['real_path'].$file_name;
					$storepath=$folder;
					$idpath=ROOT_FOLDER_ID.$parent_folder[0]['id_path'];
					move_uploaded_file($file['tmp_name'],$upload);	
					$documentfile_data=array(
						'document_id'=>$document_id,
						'file_name'=>$file_name,
						'real_path'=>$storepath,
						'id_path'=>$idpath,
						'file_size'=>$file_size,
						'file_mimetype'=>$ext_file,
						'file_extension'=>$ext_file,
						'file_version'=>'1',
						'user_id'=>$this->session->userdata('users_id'),
						'file_comment'=>$description,
						'created_at'=>date('Y-m-d H:i:s')
					);
					//dsm($documentfile_data); die;	
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

	function folder_tree($folder_id=false) {
		$this->load->model('dms_model');
		if(!$folder_id) {
			$folder_id=$this->session->userdata('home_folder');
		}

		$folders=$this->dms_model->list_folders($folder_id);
		$li="";
		foreach ($folders as $row) {
			$li.='<li>
				<label data-path="'.$row['real_path'].'" onclick="change_folder('.$row['folder_id'].');">'.$row['folder_name'].'</label><input class="folder_checkbox" value="'.$row['folder_id'].'" type="checkbox" name="folder_tree_checkbox" onclick="get_folder_tree(this)" id="folder'.$row['folder_id'].'"/>
				<ol id="ol'.$row['folder_id'].'"></ol>
			</li>';	
		}
		echo $li;
	}

	function file_view($file_id) {
		$data['pageTitle']="File View";
		$data['title']="File View";
		$this->load->model('dms_model');
		$data['file']=$this->dms_model->get_document(array('dms_documents.document_id'=>$file_id));
		
		if(!isset($data['file'][$file_id])) {
			show_404();
		}
		$data['file']=$data['file'][$file_id];
		//dsm($data['file']); die;
		$data['contant']=$this->load->view('file_view',$data,true);		
		$this->load->view('master',$data);			
	}

	function download_file($document_id,$version=1) {
		$this->load->model('dms_model');
		$this->load->helper('download');
		$filter=array(
			'dms_documents.document_id'=>$document_id, 
			'dms_document_files.file_version'=>$version
		);
		$download_file=$this->dms_model->get_document($filter);
		$download_file=$download_file[$document_id];
		$url='application/'.$download_file['real_path'].$download_file['file_name'];
		_push_file($url,$download_file['file_name']);	
	}
}