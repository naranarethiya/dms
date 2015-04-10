<div class="panel panel-default">
	<div class="panel-body">
		<div class="col-md-6">
			<div class="panel-heading">
				<h4>Add Group Information</h4>
			</div>				
			<form name="add_group" method="post" action="<?php echo base_url().'user/save_group';?>">
				<?php
					if(isset($edit_group)) {
						$editgroup=$edit_group[0];
						$userid=$edit_group['userid'][0];
						echo "<input type='hidden' name='group_id' value=".$editgroup['group_id'].">";
					}
				?>
				<div class="form-group">
					<label>Group Name <span calss="text-danger">*</span></label>
					<input type="text" class="form-control" name="group_name" id="group_name" required value="<?php if(isset($editgroup)) { echo $editgroup['group_name']; }?>">
				</div>
				<div class="form-group">
					<label>Users</label>
					<?php
						if(isset($edit_group['userid'][0])){
							$option=$userid['user_id'];
							if(strpos($option,',')!== FALSE) {
								$option=explode(',',$option);
							}
							else {
								$option=$option;
							}
						}
						else {
							$option='';
						}					
						echo generate_combobox('user_id[]',$user,'users_id','username',$option,'class="form-control chosen" id="user_id" multiple');					
					?>
				</div>				
				<div class="form-group">
					<label>Note</label>
					<textarea name="note" id="note" class="form-control"><?php if(isset($editgroup)) { echo $editgroup['note']; }?></textarea>
				</div>	
				<div class="form-group">
					<div class="modal-footer">
						<input type="submit" name="submit"  class="btn btn-primary" value="Submit">
						<input type="reset" name="reset" class="btn btn-default" value="Reset">
					</div>						
				</div>						
			</form>
		</div>
		<div class="col-md-6">
			<div class="panel-heading">
				<a href="#" class="fa fa-fw fa-refresh" onclick="window.location.reload( true );" data-toggle="tooltip" data-placement="top" title="Refresh"></a>
				<a href="#" class="fa fa-fw fa-check" id="checkall" data-toggle="tooltip" data-placement="bottom" title="Select All"></a>
				<a href="#" class="fa fa-fw fa-edit" id="edit_group" data-toggle="tooltip" data-placement="bottom" title="Edit Group"></a>				
				<a href="#" class="fa fa-fw fa-trash-o" id="del_group" data-toggle="tooltip" data-placement="bottom" title="Delete Group"></a>								
			</div>			
			<table class='table table-bordered table-striped dataTable'>
				<thead>
					<tr>
						<th>#</th>
						<th>Group Name</th>
						<th>Total User</th>	
						<th>Note</th>	
						<th>Added On</th>		  			
					</tr>
				</thead>
					<tbody>
				<?php foreach ($group as $row) { ?>					
					<tr id="<?php echo 'gtr'.$row['group_id'];?>">
						<td>
							<label>
								<input type="checkbox"  id="gID[]" name="gID[]" value="<?php echo $row['group_id']; ?>" class="selectAll">
							</label>				
						</td>							
						<td><?php echo $row['group_name']; ?></td>
						<td><?php echo $row['cnt']; ?></td>
						<td><?php echo $row['note']; ?></td>
						<td><?php echo dateformat($row['created_at']);?></td>
					</tr>	
				<?php } ?>								
				</tbody>
			</table>			
		</div>
	</div>
</div>
<script type="text/javascript">
$('.dataTable').dataTable({
	"bPaginate": false,
	"bInfo":false,
	"bFilter":false,
	"bsort": true,
	"scrollY": "510px",
	"aaSorting": [] 	
});
$('.chosen').chosen();
function get_selected() {
	var slvals = []
	$('input:checkbox[name^=gID]:checked').each(function() {
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


	$('#edit_group').click(function () {
		var id=get_selected();
		if(id.length>1 || id.length<1) {
			alert ('Select 1 record at a time!');
		}
		else {
			window.location=base_url+"user/add_group/"+id;	
		}
	});
	
	$('#del_group').click(function () {
		var id=get_selected();
		if(id.length<1) {
			alert ('Select 1 record at a time!');
		}
		else {
			var x=confirm("Are you sure to delete record?");
			if (x) {
				$.ajax({
					  url     : base_url+"user/del_group/",
					  type    : 'POST',
					  data    : {'id':id},
					  success : function(data){
						data=$.parseJSON(data);
						if(data.status == '1') {
							alert(data.message);
							$.each(id,function( item,value ) {
							   $('#gtr'+value).remove();
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