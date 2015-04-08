<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
  
class dms_model extends CI_Model {

	function listout_folder($folder_id=false,$user_id=false) {
		/* set folder id */
		if(!$folder_id) {
			$folder_id=$this->get_home_folder();
		}

		if($user_id) {
			$this->load->model('user_model');
			$user=$this->user_model->get_users(array('user_id'=>$user_id));
			$user_data=$user[0];
		}
		else {
			$user_data=$this->session->all_userdata();	
		}
		
		$folders=$this->get_folders($folder_id);
		$files=$this->get_documents($folder_id);
		$valid_files=array();
		$valid_folders=array();

		foreach($folders as $folder) {
			$access_mode=$this->get_access_mode($folder,$user_data,true);
			if($access_mode >= DMS_READ) {
				$valid_folders[]=$folder;
			}
		}

		foreach($files as $file) {
			$access_mode=$this->get_access_mode($file,$user_data);
			if($access_mode >= DMS_READ) {
				$valid_files[]=$file;
			}
		}
		$data['folders']=$valid_folders;
		$data['files']=$valid_files;
		return $data;
	}

	/* return home folder of user */
	function get_home_folder($user=false) {
		if(!$user) {
			$this->load->model('user_model');
			$user=$this->user_model->get_users(array('user_id'=>$this->session->userdata('user_id')));
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
		if($join_category) {
			$this->db->select('dms_document_files.*,dms_documents.*,group_concat(document_category.category) as document_category');
			$this->db->join("document_category","dms_documents.document_id=document_category.document_id");
		}
		if($parent_folder) {
			$this->db->where('dms_folders.parent_folder',$parent_folder);	
		}
		
		$this->db->join("dms_document_files","dms_documents.document_id=dms_document_files.document_id");
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
		$rs=$this->db->get('dms_documents');
		$result=$rs->result_array();
		$result=parent_child_array($result,$document_id);
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
		if(strpos('/'.$resource['id_path'],'/'.$home_folder.'/')===false) {
			return false;
		}

		/* The owner of the resource has unrestricted access */
		if($resource['owner_id']==$user['user_id']) {
			return DMS_ALL;
		}

		/* check if resource or file is inside any folder which is owned by user */
		$own_folders=$this->get_own_folders($user['user_id']);
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
		if(array_key_exists($user['user_id'], $access_list["users"])) {
			return $access_list["users"][$user['user_id']];
		}

		$result=0;
		/* Get the highest right defined by a group */
		$user_groups=$this->user_model->get_usergroup_members(array('user_id'=>$user['user_id']));
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
			return $this->get_access_list($resource['parent_folder_id']);
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
		$this->db->get('dsm_folderdocument_access');
		$access_array=$rs->result_array();
		if (is_bool($access_list) && !$access_list) {
			return false;
		}

		$access_list=array("users"=>array(),"groups"=>array());
		foreach($access_array as $row) {
			if($row['user_id']!='') {
				$access_list['users'][$row['user_id']]=$row['access_code'];
			}
			else {
				$access_list['groups'][$row['group_id']]=$row['access_code'];
			}
		}
		return $access_list;
	}
}

?>