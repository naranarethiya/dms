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
		$this->load->model('user_model');
		if(!$folder_id) {
			$folder_id=$this->session->userdata('home_folder');
		}
		
		$user=$this->session->all_userdata('users_id');
		$folder=$this->dms_model->get_folders('',array('folder_id'=>$folder_id));
		if(count($folder) < 1) {
			show_404();
		}
		$folder=$folder[0];
		$access=$this->dms_model->get_access_mode($folder,$user,true);
		if($access>='1') {
			$data['extfolder']=$this->dms_model->listout_folder($folder_id);
			$data['folder_id']=$folder_id;
			$data['folder_info']=$folder;
		}
		else {
			show_404();
		}

		/* (Owner) User details */
		$data['owner']=$this->user_model->get_child_users(true);

		/* category details*/
		$data['category']=$this->dms_model->get_category(array('user_id'=>$this->session->userdata('users_id')));
		$data['owner'][]=$this->session->all_userdata();

		$data['contant']=$this->load->view('view_data',$data,true);		
		$this->load->view('master',$data);		
	}

	function search_file() {
		//dsm($this->input->post()); die;
		$data['pageTitle']="Search Result";
		$data['title']="Search Result";		
		$this->load->model('dms_model');
		$this->load->model('user_model');		
		$keyword=$this->input->post('keyword');
		$category_id=$this->input->post('category_id');
		$owner_id=$this->input->post('owner_id');
		$from_date=$this->input->post('from_date');
		$to_date=$this->input->post('to_date');
		$filter=array();
		if($keyword!='') {
			$filter['WHERE']="dms_documents.file_title like '%".$keyword."%' OR dms_document_files.file_name like '%".$keyword."%' OR dms_document_files.real_path like '%".$keyword."%' or dms_document_files.file_extension like '%".$keyword."%' or dms_document_files.file_comment like '%".$keyword."%'";
		}
		if($category_id!='') {
			$filter['document_category.category_id']=$category_id;
		}
		if($owner_id!='') {
			$filter['dms_documents.owner_id']=$owner_id;
		}
		if($from_date!='') {
			$filter['dms_document_files.created_at >= ']=$from_date;
		}
		if($to_date!='') {
			$filter['dms_document_files.created_at <= ']=$to_date;
		}
		if(count($filter) < 1) {
			redirect(base_url()."file_manager");
		}
		$file=$this->dms_model->get_document($filter);
		$data['extfolder']['files']=$file;
		/* (Owner) User details */
		$data['owner']=$this->user_model->get_child_users(true);
		/* category details*/
		$data['category']=$this->dms_model->get_category(array('user_id'=>$this->session->userdata('users_id')));		
		$data['contant']=$this->load->view('search_file_view',$data,true);		
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
		$data['owner']=$this->user_model->get_child_users(true);
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
		$data['owner']=$this->user_model->get_child_users(true);
		/* Keyword details*/
		$data['keyword']=$this->dms_model->get_keyword();
		/* category details*/
		$data['category']=$this->dms_model->get_category(array('user_id'=>$this->session->userdata('users_id')));
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
		$keywords = rtrim($this->input->post('keywords'),',');
		$category_id = $this->input->post('category_id');
		
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
				if(!empty($category_id) && $category_id[0]!='') {
					foreach ($category_id as $key => $value) {
						$category_data=array(
							'document_id'=>$document_id,
							'category_id'=>$value,
						);
						$res1=$this->dms_model->add_document_category($category_data);
					}
				}
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

	function file_view($document_id) {
		$data['pageTitle']="File View";
		$data['title']="File View";
		$this->load->model('dms_model');
		$data['file']=$this->dms_model->get_document(array('dms_documents.document_id'=>$document_id));
		
		if(!isset($data['file'][$document_id]) ) {
			show_404();
		}
		$data['file']=$data['file'][$document_id];
		$user=$this->session->all_userdata();
		$access_mode=$this->dms_model->get_access_mode($data['file'],$user);
		if($access_mode < 1 ) {
			show_404();
		}
		$this->dms_model->log_activity($document_id,$this->session->userdata('users_id'),'View');
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
		$user=$this->session->all_userdata();
		if(count($download_file) < 1) {
			show_404();
			die;
		}
		$download_file=$download_file[$document_id];
		$access_mode=$this->dms_model->get_access_mode($download_file,$user);
		if($access_mode < 1 ) {
			show_404();
		}
		else {
			$this->dms_model->log_activity($document_id,$this->session->userdata('users_id'),'Download');
			$url='application/'.$download_file['real_path'].$download_file['file_name'];
			_push_file($url,$download_file['file_name']);		
		}
	}

	function add_keyword($keyword_id=false) {
		$data['pageTitle']="Keyword Details";
		$data['title']="Keyword Details";
		$this->load->model('dms_model');
		$filter=array();
		if($keyword_id!='') {
			$filter=array(
				'dsm_keywords.keyword_id'=>$keyword_id
			);			
			$data['edit_keyword']=$this->dms_model->get_keyword($filter);
		}

		$data['keyword']=$this->dms_model->get_keyword();
		$data['contant']=$this->load->view('keyword_addform',$data,true);
		$this->load->view('master',$data);				
	}

	function save_keyword() {
		$keyword_id=$this->input->post('keyword_id');
		$this->load->model('dms_model');
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		$this->form_validation->set_rules('keyword', 'Keyword', 'required|min_length[1]|max_length[100]');
		if ($this->form_validation->run() == FALSE) {
			set_message(validation_errors());
			redirect_back();		
			return 0;
			die;
		}
		$keyword=strtoupper($this->input->post('keyword'));
		$keyword_data = array(
			'keyword' => $keyword,
			'user_id' => $this->session->userdata('users_id'),
		);
		if($keyword_id) {
			$res=$this->dms_model->update_keyword($keyword_data,$keyword_id);
			if($res){
				set_message('Keyword eidtted.','success');
				redirect(base_url().'file_manager/add_keyword');
			}	
			else {
				set_message('something went wrong'.$this->db->_error_message);
				redirect_back();				
			}
		}	
		else {
			$keyword_data['created_at']=date('Y-m-d H:i:s');
			$res=$this->dms_model->add_keyword($keyword_data);
			if($res){
				set_message('New keyword added.','success');
				redirect_back();
			}	
			else {
				set_message('something went wrong'.$this->db->_error_message);
				redirect_back();				
			}		
		}	
	}

	function del_keyword() {
		$this->load->model('dms_model');
		$del_id=$this->input->post('id');
		$delquery = $this->dms_model->delete_keyword($del_id);
		if($delquery) {
			$return=array("status"=>'1',"message"=>"Keyword deleted successfully");
		}
		else {
			$return=array("status"=>'0',"message"=>"Something went wrong!!");
		}
		echo json_encode($return);		
	}	

	function add_category($category_id=false) {
		$data['pageTitle']="File Category Details";
		$data['title']="File Category Details";
		$this->load->model('dms_model');
		if($category_id!='') {
			$data['edit_category']=$this->dms_model->get_category(array('dsm_category.category_id'=>$category_id));
		}
		$data['category']=$this->dms_model->get_category();
		$data['contant']=$this->load->view('category_addform',$data,true);
		$this->load->view('master',$data);				
	}

	function save_category() {
		$category_id=$this->input->post('category_id');
		$this->load->model('dms_model');
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		$this->form_validation->set_rules('category_title', 'Category Title', 'required|min_length[1]|max_length[100]');
		if ($this->form_validation->run() == FALSE) {
			set_message(validation_errors());
			redirect_back();		
			return 0;
			die;
		}
		$category_title=strtoupper($this->input->post('category_title'));
		$note=$this->input->post('note');
		$category_data = array(
			'category_title' => $category_title,
			'note' => $note,
			'user_id' => $this->session->userdata('users_id'),
		);
		if($category_id) {
			$res=$this->dms_model->update_category($category_data,$category_id);
			if($res){
				set_message('Category eidtted.','success');
				redirect(base_url().'file_manager/add_category');
			}	
			else {
				set_message('something went wrong'.$this->db->_error_message);
				redirect_back();				
			}
		}	
		else {
			$category_data['created_at']=date('Y-m-d H:i:s');
			$res=$this->dms_model->add_category($category_data);
			if($res){
				set_message('New Category added.','success');
				redirect_back();
			}	
			else {
				set_message('something went wrong'.$this->db->_error_message);
				redirect_back();				
			}		
		}	
	}

	function del_category() {
		$this->load->model('dms_model');
		$del_id=$this->input->post('id');
		$delquery = $this->dms_model->delete_category($del_id);
		if($delquery) {
			$return=array("status"=>'1',"message"=>"Category deleted successfully");
		}
		else {
			$return=array("status"=>'0',"message"=>"Something went wrong!!");
		}
		echo json_encode($return);		
	}

	function get_keyword() {
		$this->load->model('dms_model');
		$title=$this->input->get('term');
		$filter['WHERE']="keyword like '%".$title."%'";		
		$keyword=$this->dms_model->get_keyword($filter);
		$return=array();
		foreach($keyword as $row) {
			$return[]=array(
				'label'=>$row['keyword'],
			);
		}
		echo json_encode($return);
	}

	function edit_access($resource_id,$type="folder") {
		$this->load->model('dms_model');
		$user=$this->session->all_userdata();
		if($type=="folder") {
			/* get folder details */
			$folder=$this->dms_model->get_folders('',array('folder_id'=>$resource_id));
			if(count($folder) < 1) {
				die("Forlder not exist");
			}
			$resource=$folder[0];

			/* check if user has permission */
			$access_mode=$this->dms_model->get_access_mode($resource,$user,true);
			if($access_mode < 4) {
				die("access forbidden");
			}

			/* check if has inherited Access */
			if($resource['inherited_access']!='1') {
				$access_list=$this->dms_model->get_access_list();
			}
		}
		else {
			$document=$this->dms_model->get_document(array("document_id"=>$resource_id));
			if(count($document) < 1) {
				die("file not exist");
			}	
			$resource=$document[0];

			/* check if has inherited Access */
			if($resource['inherited_access']!='1') {
				$access_list=$this->dms_model->get_access_list($resource,$user);
			}
		}
		
		/* Child users details */
		$data['owner']=$this->user_model->get_child_users(true);
		
	}			
}