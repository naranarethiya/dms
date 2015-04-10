<div class="panel panel-default">
	<div class="panel-body">
		<div class="col-md-6">
			<div class="panel-heading">
				<h4>Add Keyword Information</h4>
			</div>				
			<form name="add_group" method="post" action="<?php echo base_url().'file_manager/save_keyword';?>">
				<?php
					if(isset($edit_keyword)) {
						$editkeyword=$edit_keyword[0];
						echo "<input type='hidden' name='keyword_id' value=".$editkeyword['keyword_id'].">";
					}
				?>
				<div class="form-group">
					<label>Keword Name <span calss="text-danger">*</span></label>
					<input type="text" class="form-control" name="keyword" id="keyword" required value="<?php if(isset($editkeyword)) { echo $editkeyword['keyword']; }?>">
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
				<a href="#" class="fa fa-fw fa-edit" id="edit_keyword" data-toggle="tooltip" data-placement="bottom" title="Edit Keyword"></a>				
				<a href="#" class="fa fa-fw fa-trash-o" id="del_keyword" data-toggle="tooltip" data-placement="bottom" title="Delete Keyword"></a>								
			</div>			
			<table class='table table-bordered table-striped dataTable'>
				<thead>
					<tr>
						<th>#</th>
						<th>Keyword Name</th>
						<th>Added On</th>		  			
					</tr>
				</thead>
					<tbody>
				<?php foreach ($keyword as $row) { ?>					
					<tr id="<?php echo 'ktr'.$row['keyword_id'];?>">
						<td>
							<label>
								<input type="checkbox"  id="kID[]" name="kID[]" value="<?php echo $row['keyword_id']; ?>" class="selectAll">
							</label>				
						</td>							
						<td><?php echo $row['keyword']; ?></td>
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
	$('input:checkbox[name^=kID]:checked').each(function() {
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


	$('#edit_keyword').click(function () {
		var id=get_selected();
		if(id.length>1 || id.length<1) {
			alert ('Select 1 record at a time!');
		}
		else {
			window.location=base_url+"file_manager/add_keyword/"+id;	
		}
	});
	
	$('#del_keyword').click(function () {
		var id=get_selected();
		if(id.length<1) {
			alert ('Select 1 record at a time!');
		}
		else {
			var x=confirm("Are you sure to delete record?");
			if (x) {
				$.ajax({
					  url     : base_url+"file_manager/del_keyword/",
					  type    : 'POST',
					  data    : {'id':id},
					  success : function(data){
						data=$.parseJSON(data);
						if(data.status == '1') {
							alert(data.message);
							$.each(id,function( item,value ) {
							   $('#ktr'+value).remove();
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