<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
  
class dms_model extends CI_Model {

	function listout_folder($folder_id=false,$user_id=false) {
		/* set folder id */
		if(!$folder_id) {
			$folder_id=$this->get_home_folder();
		}

		if($user_id) {
			$this->load->model('user_model');
			$user=$this->user_model->get_users(array('users_id'=>$user_id));
			$user_data=$user[0];
		}
		else {
			$user_data=$this->session->all_userdata();	
		}
		
		
		$data['folders']=$this->list_folders($folder_id,$user_id);
		$data['files']=$this->list_documents($folder_id,$user_id);
		return $data;
	}

	function list_folders($folder_id=false,$user_id=false) {
		/* set folder id */
		if(!$folder_id) {
			$folder_id=$this->get_home_folder();
		}

		if($user_id) {
			$this->load->model('user_model');
			$user=$this->user_model->get_users(array('users_id'=>$user_id));
			$user_data=$user[0];
		}
		else {
			$user_data=$this->session->all_userdata();	
		}
		
		$folders=$this->get_folders($folder_id);
		$valid_folders=array();

		foreach($folders as $folder) {
			$access_mode=$this->get_access_mode($folder,$user_data,true);
			if($access_mode >= DMS_READ) {
				$valid_folders[]=$folder;
			}
		}
		return $valid_folders;
	}

	function list_documents($folder_id=false,$user_id=false) {
		/* set folder id */
		if(!$folder_id) {
			$folder_id=$this->get_home_folder();
		}

		if($user_id) {
			$this->load->model('user_model');
			$user=$this->user_model->get_users(array('users_id'=>$user_id));
			$user_data=$user[0];
		}
		else {
			$user_data=$this->session->all_userdata();	
		}
		$filter=array('dms_documents.parent_folder_id'=>$folder_id);
		$files=$this->get_document($filter);
		$valid_files=array();

		foreach($files as $file) {
			$access_mode=$this->get_access_mode($file,$user_data);
			if($access_mode >= DMS_READ) {
				$valid_files[]=$file;
			}
		}
		return $valid_files;
	}

	/* adding document data*/

	function add_document_data($document_data) {
		return $this->db->insert('dms_documents',$document_data);
	}

	/* adding document file data*/

	function add_documentfile_data($documentfile_data) {
		return $this->db->insert('dms_document_files',$documentfile_data);
	}

	/* adding documnet category */

	function add_document_category($category_data) {
		return $this->db->insert('document_category',$category_data);
	}
	
	/* return home folder of user */
	function get_home_folder($user=false) {
		if(!$user) {
			$this->load->model('user_model');
			$user=$this->user_model->get_users(array('users_id'=>$this->session->userdata('users_id')));
			//print_last_query();
			$user=$user[0];
		}
		return $user['home_folder'];
	}

	/* get folder with owner rights */
	function get_own_folders($user_id) {
		$own_folder_ids=$this->session->userdata('own_folders_'.$user_id);
		if(!$own_folder_ids) {
			$filter=array('owner_id'=>$user_id);
			$own_folder=$this->get_folders(false,$filter);
			$own_folder_ids=array_column($own_folder,'folder_id');	
			$this->session->set_userdata('own_folders_'.$user_id,$own_folder_ids);
		}
		return $own_folder_ids;
	}
	
	/* get folder list by parent folder */
	function get_folders($parent_folder=false,$filter=false) {
		if($filter) {
			apply_filter($filter);
		}
		if($parent_folder) {
			$this->db->where('dms_folders.parent_folder_id',$parent_folder);	
		}
		
		//$this->db->join("dms_document","dms_documents.document_id=dms_document_files.document_id");
		$rs=$this->db->get('dms_folders');
		$result=$rs->result_array();
		return $result;
	}
	
	/* get document with join document file */
	function get_documents($parent_folder,$filter=false,$join_category=false) {
		if($filter) {
			apply_filter($filter);
		}

		$this->db->where('dms_documents.parent_folder_id',$parent_folder);

		if($join_category) {
			$this->db->select('dms_document_files.*,dms_documents.*,group_concat(document_category.category) as document_category');
			$this->db->join("document_category","dms_documents.document_id=document_category.document_id");
		}
		$this->db->join("dms_document_files","dms_documents.document_id=dms_document_files.document_id");
		$this->db->join("dsm_favorite_document","dsm_favorite_document.document_id=dms_documents.document_id",'left');
		$this->db->join("dms_users","dms_documents.owner_id=dms_users.users_id");
		$rs=$this->db->get('dms_documents');
		$result=$rs->result_array();
		$result=parent_child_array($result,'document_id');
		return $result;
	}

	/* get document */
	function get_document($filter=false) {
		if($filter!='') {
			apply_filter($filter);
		}
		$this->db->select('dms_documents.*,dms_document_files.*,dms_users.first_name,dms_users.last_name,dsm_favorite_document.user_id as favorite_users,group_concat(dsm_category.category_title) as categories');
		$this->db->join("dms_document_files","dms_documents.document_id=dms_document_files.document_id");
		$this->db->join("dsm_favorite_document","dsm_favorite_document.document_id=dms_documents.document_id",'left');
		$this->db->join("dms_users","dms_documents.owner_id=dms_users.users_id");
		
		$this->db->join("document_category","document_category.document_id=dms_documents.document_id",'left');
		$this->db->join("dsm_category","dsm_category.category_id=document_category.category_id",'left');

		$this->db->where("(dsm_favorite_document.user_id=".$this->session->userdata('users_id')." or dsm_favorite_document.user_id is null)");
		$this->db->group_by('dms_documents.document_id');
		$res=$this->db->get('dms_documents')->result_array();
		$result=parent_child_array($res,'document_id');
		return $result;		
	}
	
	/* return maximum access rights of user of file */
	function get_access_mode($resource,$user,$is_folder=false) {
		$this->load->model('user_model');
		/* Administrators have unrestricted access */
		if($user['role']=='admin') {
			return DMS_ALL;
		}

		/* if accessing inside home folder or not */
		$home_folder=$user['home_folder'];
		//echo '/'.$resource['id_path'].'<br/>';
		//echo '/'.$home_folder.'/';
		if(strpos('/'.$resource['id_path'],'/'.$home_folder.'/')===false) {
			return false;
		}

		/* The owner of the resource has unrestricted access */
		if($resource['owner_id']==$user['users_id']) {
			return DMS_ALL;
		}

		/* check if resource or file is inside any folder which is owned by user */
		$own_folders=$this->get_own_folders($user['users_id']);
		foreach($own_folders as $folder) {
			if(strpos('/'.$resource['id_path'],'/'.$folder.'/')!==false) {
				return DMS_ALL;
			}
		}

		$access_list=$this->get_access_list($resource,$user,$is_folder);
		if (!$access_list) { 
			return false; 
		}

		/* Get the right defined by user */
		if(array_key_exists($user['users_id'], $access_list["users"])) {
			return $access_list["users"][$user['users_id']];
		}

		$result=0;
		/* Get the highest right defined by a group */
		$user_groups=$this->user_model->get_usergroup_members(array('user_id'=>$user['users_id']));
		if(count($user_groups) > 0) {
			$group_ids=array_column($user_groups,'group_id');
			foreach ($access_list["groups"] as $group=>$rights) {
				if(array_search($group,$group_ids) && $result > $rights) {
					$result=$rights;
				}
			}
		}
		if($result) {
			return $result;	
		}
		else {
			return $resource['default_access'];
		}
		
	}

	function get_keyword($filter=false) {
		if($filter!=''){
			apply_filter($filter);
		}
		$this->db->where('dsm_keywords.user_id',$this->session->userdata('users_id'));
		$res=$this->db->get('dsm_keywords');
		return $res->result_array();		
	}

	function delete_keyword($del_id) {
		$this->db->where_in('keyword_id',$del_id);
		return $this->db->delete('dsm_keywords');		
	}

	function add_keyword($keyword_data) {
		return $this->db->insert('dsm_keywords',$keyword_data);
	}

	function update_keyword($keyword_data,$keyword_id) {
		$this->db->where('keyword_id',$keyword_id);
		$this->db->where('user_id',$this->session->userdata('users_id'));
		return $this->db->update('dsm_keywords',$keyword_data);
	}

	function get_category($filter=false) {
		if($filter!=''){
			apply_filter($filter);
		}
		$this->db->where('dsm_category.user_id',$this->session->userdata('users_id'));
		$res=$this->db->get('dsm_category');
		return $res->result_array();		
	}

	function delete_category($del_id) {
		$this->db->where_in('category_id',$del_id);
		return $this->db->delete('dsm_category');		
	}

	function add_category($category_data) {
		return $this->db->insert('dsm_category',$category_data);
	}

	function update_category($category_data,$category_id) {
		$this->db->where('category_id',$category_id);
		$this->db->where('user_id',$this->session->userdata('users_id'));
		return $this->db->update('dsm_category',$category_data);
	}			

	/* 
		@resouce array - row of dms_folders or dms_documents table
		@is_folder - true if folder
		@mod - access type
		@op - operation =,>,>=,<,<=,!=
		return access list of users and users groups 

	*/
	function get_access_list($resource,$user,$is_folder=false,$mode=false,$op='=') {
		/* if document has inherited_access */
		if($resource['inherited_access']=='1') {
			$filter=array('folder_id'=>$resource['parent_folder_id']);
			$parent_folder=$this->get_folders(false,$filter);
			$parent_folder=$parent_folder[0];
			return $this->get_access_list($parent_folder,$user,true);
		}

		if($is_folder) {
			$this->db->where('folder_id',$resource['folder_id']);
		}
		else {
			$this->db->where('document_id',$resource['document_id']);
		}
		if($mode) {
			$this->db->where('access_code '.$op,$mode);
		}
		$rs=$this->db->get('dsm_folderdocument_access');
		$access_array=$rs->result_array();
		if (is_bool($access_array) && !$access_array) {
			return false;
		}

		$access_list=array("users"=>array(),"groups"=>array());
		foreach($access_array as $row) {
			if($row['users_id']!='') {
				$access_list['users'][$row['user_id']]=$row['access_code'];
			}
			else {
				$access_list['groups'][$row['group_id']]=$row['access_code'];
			}
		}
		return $access_list;
	}

	function log_activity($document_id,$user_id,$action="download") {
		$insert=array(
			"document_id"=>$document_id,
			"user_id"=>$user_id,
			"action"=>$action,
			"created_at"=>date('Y-m-d H:i:s')
		);
		return $this->db->insert('dsm_access_log',$insert);
	}
}

?>