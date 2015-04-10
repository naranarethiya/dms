<div class="panel panel-default">
	<div class="panel-body">
		<form name="user_add" method="post" action="<?php echo base_url().'user/save_user';?>" onsubmit="return chk_password();">
			<?php
				if(isset($edit_user)) {
					$user=$edit_user[0];
					$permission=$edit_user['user_permissionlist_id'][0];
					echo "<input type='hidden' name='users_id' value=".$user['users_id'].">";
				}
				$old_data=$this->session->flashdata('old_data');
				if(is_array($old_data)) {
					$user=$old_data;
				}					
			?>
			<div class="col-md-12">
				<div class="col-md-6">
					<label>First Name <span class="text-danger">*</span></label>
					<input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter First Name" required value="<?php if(isset($user)) { echo $user['first_name']; }?>"/> 
				</div>		
				<div class="col-md-6">
					<label>Last Name</label>
					<input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter Last Name" value="<?php if(isset($user)) { echo $user['last_name']; }?>"/> 
				</div>					
			</div>

			<div class="col-md-12">
				<div class="col-md-6">
					<label>Username <span class="text-danger" style="color:red;">* <b>(Will be used for login)</b></span></label>
					<input type="text" class="form-control" id="username" name="username" placeholder="Enter Username" required value="<?php if(isset($user)) { echo $user['username']; }?>" <?php if(isset($user)) { echo "readonly"; }?>/> 
				</div>		
				<div class="col-md-3">
					<label>Password <span class="text-danger">*</span></label><span class="text-danger" style="color:red;" id="err"><b>Password Not Match.</b></span>
					<input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required value="<?php if(isset($user)) { echo $user['temp_pwd']; }?>" <?php if(isset($user)) { echo "readonly"; }?>/> 
				</div>
				<?php if(!isset($edit_user)) { ?>
				<div class="col-md-3">
					<label>Confirm Password <span class="text-danger">*</span></label>
					<input type="password" class="form-control" id="cfpassword" name="cfpassword" placeholder="Enter Confirm Password" required value="<?php if(isset($user)) { echo $user['temp_pwd']; }?>" <?php if(isset($user)) { echo "readonly"; }?>/> 
				</div>
				<?php } ?>									
			</div>	

			<div class="col-md-12">
				<div class="col-md-6">
					<label>Mobile <span class="text-danger">*</span></label>
					<input type="text" class="form-control" id="mobile" name="mobile" placeholder="Mobile 10 digit only" required pattern="[0-9]{10}" value="<?php if(isset($user)) { echo $user['mobile']; }?>"/> 			
				</div>	
				<div class="col-md-6">
					<label>Email</label>
					<input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" value="<?php if(isset($user)) { echo $user['email']; }?>"/>
				</div>					
			</div>
			<div class="col-md-12">
				<div class="col-md-6">
					<label>Permission</label><br/>
					<?php 
						if(isset($edit_user['user_permissionlist_id'][0])){
							$option=$permission['user_permissionlist_id'];
							if(strpos($option,',')!== FALSE) {
								$option=explode(',',$option);
							}
							else {
								$option=$option;
							}
						}				
						foreach ($permission_list as $row => $value) {
					?>
		                <label class="radio-inline">
		                    <input type="checkbox" name="permission[]" value="<?php echo $value['user_permissionlist_id'];?>" <?php if(isset($option[$row])) { if($value['user_permissionlist_id']==$option[$row]) { echo "checked"; } }?>>
		                    <b><?php echo $value['title'];?></b>
		                </label>
		            <?php } ?>			
				</div>
				<?php if(!isset($edit_user)) { ?>
				<div class="col-md-2">
					<label>Create New Folder</label><br/>
					<label class="radio-inline">
	                    <input type="checkbox" name="create_folder" id="create_folder" value="1">
	                    <b>Create New Folder</b>
	                </label>
				</div>
				<div class="col-md-2">
					<div id="folder">
						<label>Folder Name<span class="text-danger">*</span></label>
						<input type="text" class="form-control" name="folder_name" id="folder_name" palceholder="No special character" pattern="^[a-zA-Z][a-zA-Z0-9-_\.]{1,20}$"/>
					</div>
				</div>
				<div class="col-md-2">
					<div id="access">
						<label>Default Access<span class="text-danger">*</span></label>
						<select name="default_access" class="form-control">
							<option value="0">No Access</option> 
							<option value="1">Read</option> 
							<option value="2">Read Write</option> 
							<option value="3">Read Write Share</option> 
							<option value="4">All Permission</option> 
						</select>
					</div>
				</div>	
				<?php } ?>		
			</div>
			<div class="col-md-12">
				<div class="col-md-6">
					<label>Disable User</label><br/>
	                <label class="radio-inline">
	                    <input type="radio" name="disabled" value="0" <?php if(isset($user)) { if($user['disabled']=='0') { echo "checked"; } } else { echo "checked"; }?>>
	                    <b>Enable</b>
	                </label>
	                <label class="radio-inline">
	                    <input type="radio" name="disabled" value="1" <?php if(isset($user)) { if($user['disabled']=='1') { echo "checked"; } }?>>
	                    <b>Disable</b>
	                </label>	                
				</div>	
				<div class="col-md-6">
					<label>Comment </label>
					<textarea name="comment" class="form-control" row="3"><?php if(isset($user)) { echo $user['comment']; }?></textarea>
				</div>					
			</div>			
			<div class="col-md-12">
				<div class="modal-footer">
					<input type="submit" name="submit"  class="btn btn-primary" value="Submit">
					<input type="reset" name="reset" class="btn btn-default" value="Reset">
				</div>			
			</div>
		</form>
	</div>
</div>

<script type="text/javascript">
function check_allowfolder() {
	var val=$('#create_folder:checked').val();
	if(val=="1") {
		$('#folder').show();
		$('#access').show();
		$('#folder_name').attr("required", "true");
		$('#default_access').attr("required", "true");
	}
	else {
		$('#folder').hide();
		$('#access').hide();
		$('#folder_name').value="";
		$('#folder_name').removeAttr('required');
		$('#default_access').removeAttr('required');
	}
}
$(document).ready(function() {
	check_allowfolder();
	$('#err').hide();
	$('#create_folder').change(function() {
		check_allowfolder();
	});	
});

function chk_password() {
	var password = $('#password').val();
	var cfpassword = $('#cfpassword').val();
	if(password!=cfpassword) {
		$('#err').show();
		return false;
	}
}
</script>