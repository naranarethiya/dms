<div class="row">
	<form name="addfolder" action="<?php echo base_url().'file_manager/save_folder';?>" method="POST">	
		<div class="col-md-12">
			<div class="col-md-6">
				<label>Parent Folder's Name <span class="text-danger">*</span></label>
				<?php 
					echo generate_combobox('parent_folder_id',$parent_folder,'folder_id','folder_name','','class="form-control chosen" id="parent_folder_id" required');
				?>							
			</div>
			<div class="col-md-6">
				<label>Owner's Id <span class="text-danger">*</span></label>
				<?php 
					echo generate_combobox('owner_id',$owner,'users_id','username','','class="form-control chosen" id="owner_id" required');
				?>							
			</div>	
		</div>	
		<div class="col-md-12">
			<div class="col-md-6">
				<label>New Folder Name <span class="text-danger">*</span></label>
				<input type="text" class="form-control" name="folder_name" id="folder_name" required palceholder="No special character" pattern="^[a-zA-Z][a-zA-Z0-9-_\.]{1,20}$"/>
			</div>
			<div class="col-md-6">
				<label>Description</label>
				<textarea class="form-control" id="description" name="description" placeholder="Description"></textarea> 
			</div>					
		</div>
		<div class="col-md-12">	
			<div class="modal-footer">
				<input type="submit" name="submit"  class="btn btn-primary" value="Submit">
				<input type="reset" name="reset"  class="btn btn-default" value="Reset">
			</div>							
		</div>							
	</form>	
</div>
<script type="text/javascript">
	$('.chosen').chosen();
</script>