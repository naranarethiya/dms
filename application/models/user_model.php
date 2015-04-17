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

	function get_user_withgroup() {
		$this->db->select('dms_users.*,group_concat(dsm_group.group_name) as groups');		
		$this->db->join('dsm_group_members','dms_users.users_id=dsm_group_members.user_id','left');
		$this->db->join('dsm_group','dsm_group.group_id=dsm_group_members.group_id','left');
		$this->db->group_by('dms_users.users_id');
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

	function get_group($filter=false) {
		if($filter!=''){
			apply_filter($filter);
		}
		$this->db->select('dsm_group.*,count(dsm_group_members.user_id) as cnt');
		$this->db->where('dsm_group.user_id',$this->session->userdata('users_id'));
		$this->db->join('dsm_group_members','dsm_group_members.group_id=dsm_group.group_id');
		$this->db->group_by('dsm_group_members.group_id');
		$res=$this->db->get('dsm_group');
		return $res->result_array();		
	}

	function add_group($group_data) {
		return $this->db->insert('dsm_group',$group_data);
	}

	function update_group($group_data,$group_id) {
		$this->db->where('group_id',$group_id);
		$this->db->where('user_id',$this->session->userdata('users_id'));
		return $this->db->update('dsm_group',$group_data);
	}

	function delete_group($del_id) {
		$this->db->where_in('group_id',$del_id);
		return $this->db->delete('dsm_group');		
	}

	function add_group_member($grp_mem) {
		return $this->db->insert('dsm_group_members',$grp_mem);
	}

	function get_group_member($filter=false) {
		if($filter!=''){
			apply_filter($filter);
		}
		$res=$this->db->get('dsm_group_members');
		return $res->result_array();		
	}

	function get_user_group($filter=false) {
		if($filter!=''){
			apply_filter($filter);
		}
		$this->db->select('group_concat(`dsm_group_members`.`user_id`) as user_id');		
		$this->db->join('dsm_group_members','dsm_group_members.user_id=dms_users.users_id');
		$this->db->group_by('dsm_group_members.group_id');
		$res=$this->db->get('dms_users');
		return $res->result_array();		
	}

	function get_child_users($with_parent=false) {
		$child=$this->get_users(array('parent_user'=>$this->session->userdata('users_id')));
		if($with_parent) {
			$child[]=$this->session->all_userdata();	
		}
		return $child;
	}	
}