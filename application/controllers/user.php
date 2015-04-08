<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class user extends CI_Controller {

	public function __construct() {
		parent::__construct();
		if($this->session->userdata('users_id') == '') {
			redirect(base_url());
		}
	}
	
	function index() {
		$data['pageTitle']="User Details";
		$data['title']="User Details";
		$this->load->model('user_model');
		$page=$this->input->get('per_page');
		$per_page="50";
		$filter['LIMIT']=array($per_page,$page);
		$data['user']=$this->user_model->get_users($filter);
		$this->load->library('pagination');
        $config['base_url'] = base_url().'user/index/?';
        $config['num_links'] = 2;
        $config['per_page'] = $per_page;
		$res=$this->db->query('select count(*) as cnt from dms_users');
		$cust_cnt=$res->result_array();
		$config['total_rows'] =$cust_cnt[0]['cnt'];
		$config['page_query_string']=true;
        $this->pagination->initialize($config);	
        $data['pagination'] = $this->pagination->create_links();		
		$data['contant']=$this->load->view('user_list',$data,true);
		$this->load->view('master',$data);		
	}

	function search_user() {
		$data['pageTitle']="User Details";
		$data['title']="User Details";
		$this->load->model('user_model');
		$name=$this->input->post('name');
		$mobile = $this->input->post('mobile');
		$from_date=$this->input->post('from_date');
		$to_date=$this->input->post('to_date');
		if($name!='') {
			$filter['WHERE']="dms_users.first_name like '%".$name."%' OR dms_users.last_name like '%".$name."%'";
		}			
		if($mobile!='') {
			$filter['WHERE']="dms_users.mobile =".$mobile;
		}
		if($from_date!='') {
			$filter['WHERE']='dms_users.created_at >='.$from_date;
		}
		if($to_date!='') {
			$filter['WHERE']='dms_users.created_at <='.$to_date;
		}					
		$data['user']=$this->user_model->get_users($filter);
		$data['contant']=$this->load->view('user_list',$data,true);
		$this->load->view('master',$data);		
	}

	function add_user($user_id=false) {
		$data['pageTitle']="Add User";
		$data['title']="Add User";
		$this->load->model('user_model');
		if($user_id!='') {
			$filter['WHERE']='dms_users.users_id='.$user_id;
			$data['edit_user']=$this->user_model->get_users($filter);
			$data['edit_user']['user_permissionlist_id']=$this->user_model->userpermission_group($user_id);
		}	
		$data['permission_list']=$this->user_model->get_user_permissionlist();	
		$data['contant']=$this->load->view('user_addform',$data,true);
		$this->load->view('master',$data);			
	}

	function save_user() {
		//dsm($this->input->post()); die;
		$users_id=$this->input->post('users_id');
		$create_folder=$this->input->post('create_folder');
		/* Including Validation Library */
		$this->load->model('user_model');
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		$this->form_validation->set_rules('first_name', 'First Name', 'required|min_length[1]|max_length[100]');
		$this->form_validation->set_rules('username', 'Username', 'required|min_length[1]|max_length[100]');
		if($users_id=='') {
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[1]|max_length[100]|matches[cfpassword]');
			$this->form_validation->set_rules('cfpassword', 'Confirm Password', 'required|min_length[1]|max_length[100]');
		}
		$this->form_validation->set_rules('mobile', 'Mobile No.', 'regex_match[/^[0-9]{10}$/]');
		$this->form_validation->set_rules('email', 'Email', 'valid_email');
		if($create_folder=='1') {
			$this->form_validation->set_rules('folder_name', 'Folder Name', 'required|min_length[1]|max_length[100]|regex_match[/^[a-zA-Z0-9_ ]+$/]');
		}
		if ($this->form_validation->run() == FALSE) {
			set_message(validation_errors());
			redirect_back();		
			return 0;
			die;
		}
		$first_name=strtoupper($this->input->post('first_name'));
		$last_name=strtoupper($this->input->post('last_name'));
		$username = $this->input->post('username');				
		$password = $this->input->post('password');				
		$mobile = $this->input->post('mobile');				
		$email = $this->input->post('email');				
		$permission = $this->input->post('permission');				
		$folder_name = $this->input->post('folder_name');				
		$disabled = $this->input->post('disabled');				
		$comment = $this->input->post('comment');				
		$default_access = $this->input->post('default_access');				

		$user_data = array(
			'first_name'=>$first_name,
			'last_name'=>$last_name,
			'email'=>$email,
			'mobile'=>$mobile,
			'disabled'=>$disabled,
			'role'=>'user',
			'comment'=>$comment,
			'parent_user'=>$this->session->userdata('users_id'),
		);

		if($users_id) {
			$user_data['updated_at']=date('Y-m-d H:i:s');
			$this->db->trans_begin();
			$res=$this->user_model->update_user($user_data,$users_id);
			if($res){
				/* Permission editted to user */
				$filter['WHERE']=array('user_id'=>$users_id);
				$permission_id=$this->user_model->get_user_permission($filter);
				$permission_id=array_column($permission_id,'user_permissionlist_id');
				foreach ($permission as $key => $value) {
					if(in_array($value,$permission_id)) {
						$key_to_remove=array_search($value, $permission_id);
						unset($permission_id[$key_to_remove]);
					}
					else {
						$permission_data=array(
							'user_id'=>$users_id,
							'user_permissionlist_id' =>$permission[$key]
						);
						$res1=$this->user_model->add_user_permission($permission_data);													
					}
				}			
				foreach($permission_id as $key=>$value) {
					$this->db->where('user_id',$users_id);
					$this->db->where('user_permissionlist_id',$value);
					$this->db->delete('dsm_user_permission');
				}

				if ($this->db->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
					set_message('something went wrong'.$this->db->_error_message);
					redirect_back();				
				}
				else
				{
					$this->db->trans_commit();
					set_message('User editted.','success');
					redirect_back();		
				}				

			}
			else {
				set_message('something went wrong'.$this->db->_error_message);
				redirect_back();
			}			
		}	
		else {
			$user_data['created_at']=date('Y-m-d H:i:s');
			$user_data['username']=$username;
			$user_data['password']=md5($password);
			$user_data['temp_pwd']=$password;
			$this->db->trans_begin();
			$res=$this->user_model->add_user($user_data);
			$users_id=$this->db->insert_id();
			if($res) {
				/* Permission added to user */
				if(!empty($permission)) {
					foreach ($permission as $key => $value) {
						$permission_data=array(
							'user_id'=>$users_id,
							'user_permissionlist_id' =>$value
						);
						$res1=$this->user_model->add_user_permission($permission_data);
					}
				}
				/* Parent folder details */
				$filter['WHERE']=array('owner_id'=>$this->session->userdata('parent_user'));
				$parent_folder=$this->user_model->get_folders($filter);

				/* Adding new folder */
				if($create_folder=='1') {
					$folder_data=array(
						'folder_name'=>$folder_name,
						'parent_folder_id'=>$parent_folder[0]['folder_id'],
						'owner_id'=>$users_id,
						'inherited_access'=>'1',
						'default_access'=>$default_access,
						'real_path'=>$folder_name.'/',
						'created_by'=>$this->session->userdata('users_id'),
						'created_at'=>date('Y-m-d H:i:s')
					);

					$res2=$this->user_model->add_folder($folder_data);
					$folder_id=$this->db->insert_id();
					if($res2) {
						/* updating home folder of user */
						$up_userdata=array('home_folder'=>$folder_id);
						$res3=$this->user_model->update_user($up_userdata,$users_id);

						/* updating id path of folder*/
						$id_path=$folder_id.'/';
						$up_folderdata=array('id_path'=>$id_path);
						$res4=$this->user_model->update_folder($up_folderdata,$folder_id);	

						mkdir(DOCUMENT_ROOT.$folder_name);
					}		
				}
				else {		
					/* updating home folder of user */
					$up_userdata=array('home_folder'=>$parent_folder[0]['folder_id']);
					$res3=$this->user_model->update_user($up_userdata,$users_id);
				}

				if ($this->db->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
					set_message('something went wrong'.$this->db->_error_message);
					$this->session->set_flashdata('old_data',$this->input->post());
					redirect_back();				
				}
				else
				{
					$this->db->trans_commit();
					set_message('User added.','success');
					redirect_back();		
				}

			}
			else {
				set_message('something went wrong'.$this->db->_error_message);
				redirect_back();
			}
		}				
	}	

	function del_user() {
		$this->load->model('user_model');
		$del_id=$this->input->post('id');
		$delquery = $this->user_model->delete_users($del_id);
		if($delquery) {
			$return=array("status"=>'1',"message"=>"User deleted successfully");
		}
		else {
			$return=array("status"=>'0',"message"=>"Something went wrong!!");
		}
		echo json_encode($return);		
	}	
}