<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
  
class user_model extends CI_Model {
	public function __construct() {
		$this->load->database();
	}	

	function add_user($user_data) {
		return $this->db->insert('dms_users',$user_data);
	}

	function update_user($user_data,$users_id) {
		$this->db->where('users_id',$users_id);
		return $this->db->update('dms_users',$user_data);
	}

	function delete_users($del_id) {
		$this->db->where_in('users_id',$del_id);
		return $this->db->delete('dms_users');
	}	

	function add_folder($fodata) {
		return $this->db->insert('dms_folders',$fodata);
	}

	function update_folder($up_folderdata,$folder_id) {
		$this->db->where('folder_id',$folder_id);
		return $this->db->update('dms_folders',$up_folderdata);		
	}

	function get_users($filter=false) {
		if($filter!='') {
			apply_filter($filter);
		}
		$this->db->select('dms_users.*,dms_folders.folder_name');
		$this->db->join('dms_folders','dms_folders.folder_id=dms_users.home_folder');
		$rs=$this->db->get('dms_users');
		return $rs->result_array();		
	}

	function get_folders($filter=false) {
		if($filter!='') {
			apply_filter($filter);
		}		
		$rs=$this->db->get('dms_folders');
		return $rs->result_array();			
	}

	function get_user_permissionlist() {
		$rs=$this->db->get('dsm_user_permissionlist');
		return $rs->result_array();			
	}	

	function add_user_permission($permission_data) {
		return $this->db->insert('dsm_user_permission',$permission_data);
	}

	function get_user_permission($filter=false) {
		if($filter!='') {
			apply_filter($filter);
		}		
		$rs=$this->db->get('dsm_user_permission');
		return $rs->result_array();			
	}

	function get_usergroup_members($filter=false) {
		if($filter!='') {
			apply_filter($filter);
		}		
		$rs=$this->db->get('dsm_group_members');
		return $rs->result_array();	
	}

	function userpermission_group($user_id=false) {
		if($user_id!='') {
			$this->db->where('dsm_user_permission.user_id',$user_id);
		}	
		$this->db->select('group_concat(`dsm_user_permission`.`user_permissionlist_id`) as user_permissionlist_id');		
		$this->db->join('dsm_user_permission','dsm_user_permissionlist.user_permissionlist_id=dsm_user_permission.user_permissionlist_id');
		$this->db->join('dms_users','dsm_user_permission.user_id=dms_users.users_id');
		$this->db->group_by('dsm_user_permission.user_id');
		$this->db->order_by('dsm_user_permission.user_permissionlist_id','ASC');
		$res=$this->db->get('dsm_user_permissionlist');
		return $res->result_array();			
	}
}