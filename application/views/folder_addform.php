<form name="addfolder" action="filemanager/save_folder" method="POST">		
	<div class="form-group">
		<label>Company's Name <span class="text-danger">*</span></label>
		<?php 
		echo generate_combobox('dms_companyid',$company,'dms_companyid','dms_companyname','','class="form-control" id="dms_companyid" required');
		?>							
	</div>
	<div class="form-group">
		<label>Parent Folder's Name <span class="text-danger">*</span></label>
		<?php 
		echo generate_combobox('dms_foid',$folder,'dms_foid','dms_foldername','','class="form-control" id="dms_foid" required');
		?>							
	</div>
	<div class="form-group">
		<label>New Folder Name <span class="text-danger">*</span></label>
		<input type="text" class="form-control" id="foldername" name="foldername" placeholder="New Folder Name" required/> 
	</div>	
	<div class="modal-footer">
		<input type="submit" name="submit"  class="btn btn-primary" value="Submit">
		<input type="reset" name="reset"  class="btn btn-default" value="Reset">
	</div>							
</form>	