<div class="col-md-12">
	<div class="box box-primary">
		<div class="box-body">
			<div class="row">
			<form name="company" action="save_company" method="POST" enctype="multipart/form-data">
				<div class="col-md-6">
					<div class="box-header">
						<h3 class="box-title"><i class="fa fa-fw fa-building-o"></i> Company Details</h3>
					</div><!-- /.box-header --> 
					<div class="form-group">
						<label>User Name <span class="text-danger">*</span></label>
						<?php 
						echo generate_combobox('uid',$user,'uid','owner','','class="form-control" id="uid" required');								
						?>
					</div>					
					<?php $this->load->view('companyform');?>	
					<div class="modal-footer">
						<input type="submit" name="submit"  class="btn btn-primary" value="Submit">
						<input type="reset" name="reset"  class="btn btn-default" value="Reset">
					</div>					
				</div>				
			</form>
			</div>
		</div><!-- /.box-body -->
	</div>
</div>

<script type="text/javascript">
	$('#dob').datepicker();
	$('#edate').datepicker();
</script>