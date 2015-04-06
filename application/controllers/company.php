<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class company extends CI_Controller {

	public function __construct() {
		parent::__construct();
		if($this->session->userdata('uid') == '') {
			redirect(base_url());
		}
	}
	
	function add_client() {
		$data['pageTitle']="Add Client";
		$data['title']="Add Client";
		$this->load->model('dashboard_model');
		$this->load->helper('form');
		$data['contant']=$this->load->view('companyuserform',$data,true);
		$this->load->view('master',$data);	
	}

	function save_client() {
		/* dsm($this->input->post()); die; */
		/* Including Validation Library */
		$this->load->model('dashboard_model');
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		$this->form_validation->set_rules('companyname', 'Company Name', 'required|min_length[1]|max_length[100]');
		$this->form_validation->set_rules('short_name', 'Company short Name', 'required|min_length[1]|max_length[15]');
		$this->form_validation->set_rules('cmobile', 'Company Mobile', 'required|regex_match[/^[0-9]{10}$/]');
		$this->form_validation->set_rules('cemail', 'Company Email', 'valid_email');
		$this->form_validation->set_rules('edate', 'Establish date', 'regex_match[/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/]');
		$this->form_validation->set_rules('fname', 'Name', 'required|min_length[1]|max_length[100]');
		$this->form_validation->set_rules('lname', 'Name', 'required|min_length[1]|max_length[100]');
		$this->form_validation->set_rules('dob', 'Date of Birth', 'regex_match[/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/]');
		$this->form_validation->set_rules('mobile', 'Mobile', 'required|regex_match[/^[0-9]{10}$/]');
		$this->form_validation->set_rules('email', 'Email', 'valid_email');
		$this->form_validation->set_rules('password', 'Password', 'required');
		if ($this->form_validation->run() == FALSE) {
			set_message(validation_errors());
			redirect(base_url().'dashboard/add_client');
			return 0;
		}		
		else {
			$companyname=strtoupper($this->input->post('companyname'));
			$short_name=strtoupper($this->input->post('short_name'));
			$edate = $this->input->post('edate');
			$cemail = $this->input->post('cemail');
			$cmobile = $this->input->post('cmobile');	
			$caddress = $this->input->post('caddress');	
			$name = strtoupper($this->input->post('fname'))." ".strtoupper($this->input->post('lname'));
			$dob = $this->input->post('dob');
			$email = $this->input->post('email');
			$mobile = $this->input->post('mobile');
			$passowrd = md5($this->input->post('password'));
			$temp = $this->input->post('password');	
			$created_at	= date('Y-m-d H:i:s');	
			
			$this->check_dir($short_name);	
			$file=$_FILES['file'];
			$ext_imgfile=pathinfo($file['name'],PATHINFO_EXTENSION);		
			
			if($ext_imgfile=="png" || $ext_imgfile=="jpg" || $ext_imgfile=="jpeg" || $ext_imgfile=="gif") {
			
				$folder='./logo/'.$short_name;			
				$thumb_folder=$folder.'/thumb_150';	

				$file_name=$file['name'];
				$upload=$folder.'/'.$file_name;
				$storepath=$short_name.'/'.$file_name;
				move_uploaded_file($file['tmp_name'],$upload);	
				
				if(file_exists($upload)) {
					$this->image_thumb($upload,150,30,$file_name,$thumb_folder);
				}
				
				$data = array(
					'dms_companyname'=>$companyname,
					'dms_shortname'=>$short_name,
					'dms_cemail'=>$cemail,
					'dms_cmobile'=>$cmobile,
					'dms_caddress'=>$caddress,
					'dms_establishdate'=>$edate,
					'dms_clogo' => $storepath,
					'created_at'=>$created_at
				);					
				$res1=$this->dashboard_model->add_company($data);
				$dms_companyid=$this->db->insert_id();
				if($res1){				
					$user_data = array(
						'company_name'=>$companyname,
						'dms_companyid'=>$dms_companyid,
						'owner'=>$name,
						'dob'=>$dob,
						'email'=>$email,
						'mobile'=>$mobile,
						'password'=>$passowrd,
						'temp'=>$temp,
						'role'=>"manager",
						'added_on'=>$created_at
					);		
					$res2=$this->dashboard_model->add_user($user_data);
					$dms_uid=$this->db->insert_id();
					if($res2){				
						$ugdata=array(
							'dms_companyid' =>$dms_companyid,
							'dms_uid' =>$dms_uid,
							'dms_puid' =>$dms_uid
						);
						$res3=$this->dashboard_model->add_usergroup($ugdata);			
					}
					if($res1 && $res2 && $res3) {
						/* main root folder */
						$this->check_root();
						/* folder of company */
						$this->check_company($companyname);	
						
						$fodata=array(
							'dms_companyid' =>$dms_companyid,
							'dms_foldername'=>$companyname,
							'created_at'=>$created_at
						);
						$res4=$this->dashboard_model->add_folder($fodata);
						$dms_foid=$this->db->insert_id();
						if($res4) {
							$ufodata=array('dms_foid_2' =>$dms_foid);
							$res5=$this->dashboard_model->up_folder($ufodata,$dms_foid);
						}
						
						$this->session->set_flashdata('success','New Client added successfully');
						redirect(base_url().'dashboard/add_client');
					}
					else {
						$this->session->set_flashdata('error','Somthing went wrong');
						redirect(base_url().'dashboard/add_client');				
					}
				}
				else {
					$this->session->set_flashdata('error','Somthing went wrong');
					redirect(base_url().'dashboard/add_client');
				}
			}	
			else {
				$this->session->set_flashdata("error","Undefined file format");
				redirect(base_url()."dashboard/add_client");				
			}	
		}
	}	

	function view_company() {
		$data['pageTitle']="Company Details";
		$data['title']="Company Details";
		$this->load->model('dashboard_model');
		$this->load->helper('form');
		$data['company']=$this->dashboard_model->get_companies();
		$data['contant']=$this->load->view('company_list',$data,true);
		$this->load->view('master',$data);		
	}

	function search_company() {
		$data['pageTitle']="Company Details";
		$data['title']="Company Details";
		$this->load->model('dashboard_model');
		$this->load->helper('form');
		$companyname=$this->input->post('companyname');
		$short_name=$this->input->post('short_name');
		$cmobile = $this->input->post('cmobile');
		$from_date=$this->input->post('from_date');
		$to_date=$this->input->post('to_date');		
		$data['company']=$this->dashboard_model->get_companies($companyname,$short_name,$cmobile,$from_date,$to_date);
		$data['contant']=$this->load->view('company_list',$data,true);
		$this->load->view('master',$data);		
	}

	function add_company() {
		$data['pageTitle']="Add Company";
		$data['title']="Add Company";
		$this->load->model('dashboard_model');
		$this->load->helper('form');
		$data['user']=$this->dashboard_model->get_companies_user();
		$data['contant']=$this->load->view('company_addform',$data,true);
		$this->load->view('master',$data);	
	}

	function save_company() {
		/* Including Validation Library */
		$this->load->model('dashboard_model');
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		$this->form_validation->set_rules('uid', 'User', 'required');
		$this->form_validation->set_rules('companyname', 'Company Name', 'required|min_length[1]|max_length[100]');
		$this->form_validation->set_rules('short_name', 'Company short Name', 'required|min_length[1]|max_length[15]');
		$this->form_validation->set_rules('cmobile', 'Company Mobile', 'required|regex_match[/^[0-9]{10}$/]');
		$this->form_validation->set_rules('cemail', 'Company Email', 'valid_email');
		$this->form_validation->set_rules('edate', 'Establish date', 'regex_match[/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/]');
		if ($this->form_validation->run() == FALSE) {
			set_message(validation_errors());
			redirect(base_url().'dashboard/add_company');
			return 0;
		}
		else {
			$uid = $this->input->post('uid');
			$companyname=strtoupper($this->input->post('companyname'));
			$short_name=strtoupper($this->input->post('short_name'));
			$edate = $this->input->post('edate');
			$cemail = $this->input->post('cemail');
			$cmobile = $this->input->post('cmobile');	
			$caddress = $this->input->post('caddress');	
			$created_at	= date('Y-m-d H:i:s');	
			
			$this->check_dir($short_name);	
			$file=$_FILES['file'];
			$ext_imgfile=pathinfo($file['name'],PATHINFO_EXTENSION);
			if($ext_imgfile=="png" || $ext_imgfile=="jpg" || $ext_imgfile=="jpeg" || $ext_imgfile=="gif") {
			
				$folder='./logo/'.$short_name;			
				$thumb_folder=$folder.'/thumb_150';	

				$file_name=$file['name'];
				$upload=$folder.'/'.$file_name;
				$storepath=$short_name.'/'.$file_name;
				move_uploaded_file($file['tmp_name'],$upload);	
				
				if(file_exists($upload)) {
					$this->image_thumb($upload,150,30,$file_name,$thumb_folder);
				}
				
				$data = array(
					'dms_companyname'=>$companyname,
					'dms_shortname'=>$short_name,
					'dms_cemail'=>$cemail,
					'dms_cmobile'=>$cmobile,
					'dms_caddress'=>$caddress,
					'dms_establishdate'=>$edate,
					'dms_clogo' => $storepath,
					'created_at'=>$created_at
				);					
				$res=$this->dashboard_model->add_company($data);
				$dms_companyid=$this->db->insert_id();
				if($res) {
					$ugdata=array(
						'dms_companyid' =>$dms_companyid,
						'dms_uid' =>$uid,
						'dms_puid' =>$uid
					);
					$res1=$this->dashboard_model->add_usergroup($ugdata);
					if($res && $res1) {
						/* main root folder */
						$this->check_root();
						/* folder of company */
						$this->check_company($companyname);
						
						$fodata=array(
							'dms_companyid' =>$dms_companyid,
							'dms_foldername'=>$companyname,
							'created_at'=>$created_at
						);
						$res4=$this->dashboard_model->add_folder($fodata);
						$dms_foid=$this->db->insert_id();
						if($res4) {
							$ufodata=array('dms_foid_2' =>$dms_foid);
							$res5=$this->dashboard_model->up_folder($ufodata,$dms_foid);
						}	
						
						$this->session->set_flashdata('success','New Company added successfully');
						redirect(base_url().'dashboard/add_company');
					}
					else {
						$this->session->set_flashdata('error','Somthing went wrong');
						redirect(base_url().'dashboard/add_company');				
					}					
				}
				else {
					$this->session->set_flashdata('error','Somthing went wrong');
					redirect(base_url().'dashboard/add_company');				
				}				
			}
			else {
				$this->session->set_flashdata("error","Undefined file format");
				redirect(base_url()."dashboard/add_company");				
			}			
		}
	}
	
	function check_dir($short_name) {
		$referer=$_SERVER['HTTP_REFERER'];
		$server=parse_url($referer);

		/* main upload folder */
		if(!file_exists('./logo') || !is_dir('./logo')) {
			mkdir("./logo");
		}
		
		/* Logo folder of user */
		$document_path='./logo/'.$short_name;
		if(!file_exists($document_path) || !is_dir($document_path)) {
			mkdir($document_path);
		}	

		/* thumb150 folder of each user */
		$thumb_folder=$document_path.'/thumb_150';
		if(!file_exists($thumb_folder) || !is_dir($thumb_folder)) {
			mkdir($thumb_folder);
		}
	}	
	
	function image_thumb($image_path, $width, $height,$ofilename,$thumb_folder) {
	    // Path to image thumbnail
	    $image_thumb = $thumb_folder . '/' . $ofilename;
	    if (!file_exists($image_thumb)) {
	        // LOAD LIBRARY
	        $this->load->library('image_lib');

	        // CONFIGURE IMAGE LIBRARY
	        $config['image_library']    = 'gd2';
	        $config['source_image']     = $image_path;
			$config['new_image']        = $image_thumb;
	        $config['create_thumb']     = TRUE;
	        $config['maintain_ratio']   = TRUE;
	        $config['width']            = $width;
	        $config['height']           = $height;
	        $this->image_lib->initialize($config);
	        $this->image_lib->resize();
	        $this->image_lib->clear();
	    }
	}

	function check_root() {
		$referer=$_SERVER['HTTP_REFERER'];
		$server=parse_url($referer);
		/* main root folder */
		if(!file_exists('./dms_doc') || !is_dir('./dms_doc')) {
			mkdir("./dms_doc");
		}
	}

	function check_company($companyname,$folder=false) {
		/* company folder */
		$user_folder_path='./dms_doc/'.$companyname;
		if(!file_exists($user_folder_path) || !is_dir($user_folder_path)) {
			mkdir($user_folder_path);
		}
		/* folder of company */
		if($folder!="") {
			$folder_path='./dms_doc/'.$companyname.'/'.$folder;
			if(!file_exists($folder_path) || !is_dir($folder_path)) {
				mkdir($folder_path);
			}			
		}
	}		
}