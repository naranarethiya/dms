<div class="row">
	<form name="file_add" action="<?php echo base_url().'file_manager/save_file';?>" method="POST" enctype="multipart/form-data">
		<input type="hidden" name="parent_folder_id" value="<?php echo $parent_folder_id;?>">
		<div class="col-md-12">
			<div class="col-md-6">
				<label>Owner's Id <span class="text-danger">*</span></label>
	 			<?php 
					echo generate_combobox('owner_id',$owner,'users_id','username','','class="form-control chosen" id="owner_id" required');
				?>							
			</div>
			<div class="col-md-6">
				<label>File Title</label>
				<input type="text" class="form-control" name="file_title" required>
			</div>			
		</div>	
		<div class="col-md-12">
			<div class="col-md-6">
				<div class="row">
					<div class="col-md-8">
						<label>File</label>		
						<input type="file" class="form-control" id="file" name="file" />
					</div>
					<div class="col-md-4">
						<!--<input type="button" value="+ Files" id="add_more" class="btn btn-sm btn-primary" style="margin-top:25px;"/>-->
					</div>
				</div>
			</div>			
			<div class="col-md-6">
				<label>Keyword</label>
	 			<?php 
					echo generate_combobox('keywords',$keyword,'keyword','keyword','','class="form-control chosen" id="keywords"');
				?>							
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
	$(document).ready(function(){
		$('#add_more').click(function(e){
			e.preventDefault();
			$('#file').after("<br/><input name='file[]' class='form-control' type='file' />");
		});
	});	
</script>