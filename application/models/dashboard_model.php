<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
  
class dashboard_model extends CI_Model {
	public function __construct() {
		$this->load->database();
	}	
	
	function add_company($data) {
		return $this->db->insert('dms_company',$data);
	}
	
	function add_user($user_data) {
		return $this->db->insert('users',$user_data);
	}	
	
	function add_usergroup($ugdata) {
		return $this->db->insert('dms_usergroup',$ugdata);
	}
	
	function add_folder($fodata) {
		return $this->db->insert('dms_folder',$fodata);
	}
	
	function up_folder($ufodata,$dms_foid) {
		$this->db->where('dms_foid',$dms_foid);
		return $this->db->update('dms_folder',$ufodata);
	}	
	
	function get_companies($dms_companyid=false,$companyname=false,$short_name=false,$cmobile=false,$from_date=false,$to_date=false) {
		if($this->session->userdata('role')!='admin') {
			$this->db->where('dms_company.dms_companyid',$this->session->userdata('dms_companyid'));
		}
		if($dms_companyid!='') {
			$this->db->where("dms_company.dms_companyid",$dms_companyid);
 		}		
		if($companyname!='') {
			$this->db->like("dms_company.dms_companyname",$companyname);
 		} 	
		if($short_name!='') {
			$this->db->like("dms_company.dms_shortname",$short_name);
 		} 
		if($cmobile!='') {
			$this->db->where("dms_company.dms_cmobile",$cmobile);
 		} 		
		if($from_date!='') {
			$this->db->where('date(dms_company.dms_establishdate) >= ',$from_date);
		}		
		if($to_date!='') {
			$this->db->where('date(dms_company.dms_establishdate) <=',$to_date);
		} 	
		$this->db->where('dms_company.deleted_at IS NULL', null, false);
		$rs=$this->db->get('dms_company');
		return $rs->result_array();		
	}
	
	function get_companies_user($uid=false,$companyname=false,$name=false,$mobile=false,$from_date=false,$to_date=false) {
		if($this->session->userdata('role')!='admin') {
			$this->db->where('users.dms_companyid',$this->session->userdata('dms_companyid'));
		}	
		if($uid!='') {
			$this->db->where("users.uid",$uid);
		}
		if($companyname!='') {
			$this->db->like("users.company_name",$companyname);
 		} 	
		if($name!='') {
			$this->db->like("users.owner",$name);
 		} 
		if($mobile!='') {
			$this->db->where("users.mobile",$mobile);
 		} 		
		if($from_date!='') {
			$this->db->where('date(users.dob) >= ',$from_date);
		}		
		if($to_date!='') {
			$this->db->where('date(users.dob) <=',$to_date);
		} 
		$this->db->where('users.deleted_at IS NULL', null, false);
		$rs=$this->db->get('users');
		return $rs->result_array();		
	}	

	function get_folders() {
		if($this->session->userdata('role')!='admin') {
			$this->db->where('dms_folder.dms_companyid',$this->session->userdata('dms_companyid'));
		}	
		$rs=$this->db->get('dms_folder');
		return $rs->result_array();		
	}
	
	function get_companyfolders() {
		if($this->session->userdata('role')!='admin') {
			$this->db->where('dms_folder.dms_companyid',$this->session->userdata('dms_companyid'));
		}	
		$this->db->select('dms_folder.*, dms_company.dms_companyname');
		$this->db->from('dms_folder');
		$this->db->join('dms_company','dms_company.dms_companyid=dms_folder.dms_companyid');
		$rs=$this->db->get();
		return $rs->result_array();		
	}
}