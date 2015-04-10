<?php
	$files=$extfolder['files'];
	$data['files']=$files;
?>
<!-- File folder view BEGIN -->
<div class="mailbox row">
	<div class="col-xs-12">
		<div class="box box-solid">
			<div class="box-body">
				<div class="row">
					<div class="col-md-3 col-sm-3">
						<?php
						//dsm($folder_info);die;
							echo create_breadcrumbs('Search',$this->session->userdata('home_folder'));
						?>
					</div><!-- /.col (LEFT) -->
					<div class="col-md-9 col-sm-9">
						<div class="row pad">
							<div class="col-sm-6">
								<label style="margin-right: 10px;">
									<input type="checkbox" id="checkall"/>
								</label>
								<!-- Action button -->
								<div class="btn-group">
									<button type="button" class="btn btn-default btn-sm btn-flat dropdown-toggle" data-toggle="dropdown">
										Action <span class="caret"></span>
									</button>
									<ul class="dropdown-menu" role="menu">
										<li><a href="#">Copy</a></li>
										<li><a href="#">Move</a></li>
										<li><a href="#">Delete</a></li>
										<li><a href="#">Permission</a></li>
										<li><a href="#">Mark Favourite</a></li>
										<li><a href="#">Clear Favourite</a></li>
									</ul>
								</div>							
								<a href="#" onclick="$(searchfile).modal('show');" class="fa fa-fw fa-filter" data-toggle="tooltip" data-placement="top" title="Filter data"></a>
							</div>
							<div class="col-sm-6 search-form">
								<form action="#" class="text-right">
									<div class="input-group">
										<input type="text" class="form-control input-sm" placeholder="Search">
										<div class="input-group-btn">
											<button type="submit" name="q" class="btn btn-sm btn-primary"><i class="fa fa-search"></i></button>
										</div>
									</div>
								</form>
							</div>
						</div><!-- /.row -->
						<div class="table-responsive">
							<table class='table table-mailbox'>
								<thead>
									<tr>
										<th>#</th>
										<th>Name</th> 
										<th>Type</th> 
										<th>Size</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php 
										$this->load->view('file_list_view',$data);
									?>
								</tbody>
							</table>							
						</div><!-- /.table-responsive -->
					</div><!-- /.col (RIGHT) -->
				</div><!-- /.row -->
			</div><!-- /.box-body -->
		</div><!-- /.box -->
	</div><!-- /.col (MAIN) -->
</div>
<!-- File folder view END -->


<!-- Modal -->
<div class="modal fade" id="searchfile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Search File</h4>
			</div>
			<div class="modal-body">
				<form name="search_user" method="post" action="<?php echo base_url().'file_manager/search_file';?>">
					<div class="form-group">
						<div class="col-md-12">
							<label>Keyword </label>
							<input type="text" class="form-control" id="keyword" name="keyword" placeholder="title,filename,folder name,file type,comment" />
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-6">
							<label>Category</label>
				 			<?php 
								echo generate_combobox('category_id',$category,'category_id','category_title','','class="form-control chosen"');
							?>	
						</div>						
						<div class="col-md-6">
							<label>Owner's Id</label>
				 			<?php 
								echo generate_combobox('owner_id',$owner,'users_id','username','','class="form-control chosen" id="owner_id"');
							?>	
						</div>
					</div>					
					<div class="form-group">
						<div class="col-md-6">
							<label>From Date</label>
							<input type="text" class="form-control" id="from_date" name="from_date" data-date-format="yyyy-mm-dd" placeholder="1990-01-01" pattern="\d{4}-\d{1,2}-\d{1,2}"/>											
						</div>
						<div class="col-md-6">
							<label>To Date</label>
							<input type="text" class="form-control" id="to_date" name="to_date" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d');?>" pattern="\d{4}-\d{1,2}-\d{1,2}"/>
						</div>
					</div>
				
					<div class="modal-footer">
						<input type="submit" name="submit"  class="btn btn-primary" value="Search" style="margin-top:25px;">
						<input type="reset" name="reset"  class="btn btn-default" value="Reset" style="margin-top:25px;">
						<button type="button" class="btn btn-danger" data-dismiss="modal" style="margin-top:25px;">Close</button>
					</div>							      			
				</form>
			</div>
		</div>
	</div>
</div>	


<script type="text/javascript">
$(document).ready(function(){
	$('#checkall').click(function(){
		$('.selectAll').each(function(event) {
			if(this.checked) {
				this.checked = false;
			}
			else {       
				this.checked = true;
			}
		});
	});

	$('#from_date').datepicker();
	$('#to_date').datepicker();
	$('.chosen').chosen();	
	$('.chosen-container').css('width','250px');
});

function change_folder(ele) {
	$('#parent_folder_id').val(ele);
	var folder_id=ele;
	var url=base_url+"file_manager/index/"+folder_id;
	window.open(url,"_self");
}
</script>