<div class="row">
	<form name="addfolder" action="<?php echo base_url().'file_manager/save_folder';?>" method="POST">	
		<input type="hidden" name="parent_folder_id" value="<?php echo $parent_folder_id;?>">
		<div class="col-md-12">
			<div class="col-md-6">
				<label>Owner's Id <span class="text-danger">*</span></label>
	 			<?php 
	 				if($this->session->userdata('users_id')!='') {
	 					$option=$this->session->userdata('users_id');
	 				}
	 				else {
	 					$option='';
	 				}	 			
					echo generate_combobox('owner_id',$owner,'users_id','username',$option,'class="form-control chosen" id="owner_id" required');
				?>							
			</div>
			<div class="col-md-6">
				<label>New Folder Name <span class="text-danger">*</span></label>
				<input type="text" class="form-control" name="folder_name" id="folder_name" required palceholder="No special character" pattern="^[a-zA-Z][a-zA-Z0-9-_\. ]{1,20}$"/>
			</div>				
		</div>	
		<div class="col-md-12">
			<div class="col-md-6">
				<label>Description</label>
				<textarea class="form-control" id="description" name="description" placeholder="Description"></textarea> 
			</div>					
		</div>
		<div class="col-md-12">	
			<div class="modal-footer">
				<input type="submit" name="submit"  class="btn btn-primary" value="Submit">
				<input type="reset" name="reset"  class="btn btn-default" value="Reset">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>							
		</div>							
	</form>	
</div>
<script type="text/javascript">
	$('.chosen').chosen();
</script>