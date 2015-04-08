<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
  
class login_model extends CI_Model
{
	function app_login($username,$pass)
	{
		$username=htmlspecialchars($username,ENT_QUOTES);

		$this->db->where("username",$username);
		//$this->db->where('deleted_at IS NULL', null, false);
		$row=$this->db->get('dms_users');
		//if username exists
		if($row->num_rows() > 0)
		{
			$row=$row->row(0);
			if(strcmp($row->password,md5($pass))==0)
			{
				$session=array(
					'users_id'=>$row->users_id,
					'role'=>$row->role,
					'mobile'=>$row->mobile,
					'email'=>$row->email,
					'name'=>$row->first_name." ".$row->last_name,
					'username'=>$row->username,
					'home_folder'=>$row->home_folder,
					'parent_user'=>$row->parent_user	
				);
/*				$date=date("Y-m-d H:r:s");
				$login_history=array(
					'uid'=>$row->uid,
					'name'=>$row->email,
					'datetime'=>$date
				);
				$this->db->insert('log_history',$login_history);*/
				//dsm($session); die;
				return $session;
			} 
			else 
			{
				return false;
			}
		}
		else 
		{
			return false;
		}
	}
	public function check_old_password($old_pass,$uid)
	{
		print_r($this->session->all_userdata());
		$sql="select * from dms_users where users_id=?";
		$row=$this->db->query($sql,$uid);
		if($row->num_rows() > 0)
		{
			$row=$row->row(0);
			if(strcmp($row->password,$old_pass)==0)
			{
				return true;
			} 
			else 
			{
				return false;
			}
		}
		else 
		{
			return false;
		}
		
	}
	public function change_password($old,$new,$confirm)
	{
		if($this->check_old_password(md5($old),$this->session->userdata('users_id')))
		{
			if(strcmp($new,$confirm)==0)
			{
				$this->db->query("update dms_users set password=?,temp_pwd=? where users_id=?",array(md5($confirm),$confirm,$this->session->userdata('users_id')));
				$return = "Success";
				return $return;
			}
			else
			{
				$return = "Confirm fail";
				return $return;
			}
		}
		else
		{
			$return = "Old fail";
			return $return;
		}
	}
	
	public function check_user($user)
	{
		if($this->session->userdata('role')==$user)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
}