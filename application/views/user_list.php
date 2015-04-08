<div class="panel panel-default">
	<div class="panel-heading">
		<a href="#" class="fa fa-fw fa-refresh" onclick="window.location.reload( true );" data-toggle="tooltip" data-placement="top" title="Refresh"></a>
		<a href="#" class="fa fa-fw fa-check" id="checkall" data-toggle="tooltip" data-placement="bottom" title="Select All"></a>
		<a href="#" class="fa fa-fw fa-filter" onclick="$(searchuser).modal('show');" data-toggle="tooltip" data-placement="top" title="Filter Data"></a>
		<a href="<?php echo base_url().'user/add_user';?>" class="fa fa-fw fa-plus" data-toggle="tooltip" data-placement="top" title="Add User"></a>
		<a href="#" class="fa fa-fw fa-edit" id="edit_user" data-toggle="tooltip" data-placement="bottom" title="Edit User"></a>				
		<a href="#" class="fa fa-fw fa-trash-o" id="del_user" data-toggle="tooltip" data-placement="bottom" title="Delete User"></a>								
		<span class="paginations"><?php if (isset($pagination)){ echo $pagination; } ?></span>	
	</div>	
	<div class="panel-body">
		<div class="box box-success">
			<div class="box-body table-responsive">
				<table class='table table-bordered table-striped dataTable'>
					<thead>
						<tr>
							<th>#</th>
							<th>Name</th>
							<th>Mobile</th>
							<th>Email</th> 
							<th>Folder Name</th>		
							<th>Added On</th>		  			
						</tr>
					</thead>
 					<tbody>
					<?php foreach ($user as $row) { ?>					
						<tr id="<?php echo 'utr'.$row['users_id'];?>">
							<td>
								<label>
									<input type="checkbox"  id="uID[]" name="uID[]" value="<?php echo $row['users_id']; ?>" class="selectAll">
								</label>				
							</td>							
							<td><?php echo $row['first_name']." ".$row['last_name']; ?></td>
							<td><?php echo $row['mobile']; ?></td>
							<td><?php echo $row['email']; ?></td>
							<td><?php echo $row['folder_name']; ?></td>
							<td><?php echo dateformat($row['created_at']);?></td>
						</tr>	
					<?php } ?>								
					</tbody>
				</table>
			</div>
		</div>
	</div>			
</div>

<!-- Modal -->
<div class="modal fade" id="searchuser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Search User</h4>
			</div>
			<div class="modal-body">
				<form name="search_user" method="post" action="<?php echo base_url().'user/search_user';?>">
					<div class="form-group">
						<div class="col-md-6">
							<label>Name </label>
							<input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" />						
						</div>
						<div class="col-md-6">
							<label>Mobile</label>
							<input type="text" class="form-control" id="mobile" name="mobile" placeholder="Enter Company Mobile"/>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-6">
							<label>From Date</label>
							<input type="text" class="form-control" id="from_date" name="from_date" data-date-format="yyyy-mm-dd" placeholder="1990-01-01" pattern="\d{4}-\d{1,2}-\d{1,2}"/>											
						</div>
						<div class="col-md-6">
							<label>To Date</label>
							<input type="text" class="form-control" id="to_date" name="to_date" data-date-format="yyyy-mm-dd" placeholder="1990-01-01" pattern="\d{4}-\d{1,2}-\d{1,2}"/>																				
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
$('.dataTable').dataTable({
	"bPaginate": false,
	"bsort": true,
	"scrollY": "510px",
	"aaSorting": [] 	
});

$('#from_date').datepicker();
$('#to_date').datepicker();

function get_selected() {
	var slvals = []
	$('input:checkbox[name^=uID]:checked').each(function() {
	slvals.push($(this).val())
	})
	id=slvals;
	return id;
}

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


	$('#edit_user').click(function () {
		var id=get_selected();
		if(id.length>1 || id.length<1) {
			alert ('Select 1 record at a time!');
		}
		else {
			window.location=base_url+"user/add_user/"+id;	
		}
	});
	
	$('#del_user').click(function () {
		var id=get_selected();
		if(id.length<1) {
			alert ('Select 1 record at a time!');
		}
		else {
			var x=confirm("Are you sure to delete record?");
			if (x) {
				$.ajax({
					  url     : base_url+"user/del_user/",
					  type    : 'POST',
					  data    : {'id':id},
					  success : function(data){
						data=$.parseJSON(data);
						if(data.status == '1') {
							alert(data.message);
							$.each(id,function( item,value ) {
							   $('#utr'+value).remove();
							});			
						}
						else {
							alert(data.message);
						}
					  }
				});
			} 
		}
	});	
});
</script>