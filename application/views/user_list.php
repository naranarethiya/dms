<div class="panel panel-default">
	<div class="panel-heading">
		<a href="#" class="fa fa-fw fa-refresh" onclick="window.location.reload( true );" data-toggle="tooltip" data-placement="bottom" title="Refresh"></a>
		<a href="#" class="fa fa-fw fa-filter" onclick="$(searchuser).modal('show');" data-toggle="tooltip" data-placement="bottom" title="Filter Data"></a>
	</div>	
	<div class="panel-body">
		<div class="box box-success">
			<div class="box-body table-responsive">
				<table class='table table-bordered table-striped dataTable'>
					<thead>
						<tr>
							<th>Name</th> 
							<th>Company</th>
							<th>Date of birth</th>
							<th>Mobile</th>
							<th>Email</th> 
							<th>Address</th>		
							<th>Added On</th>		  			
						</tr>
					</thead>
 					<tbody>
					<?php
						foreach ($user as $row)
						{
					?>					
						<tr>
							<td><?php echo $row['owner']; ?></td>
							<td><?php echo $row['company_name']; ?></td>
							<td><?php $date = date_create($row['dob']); echo date_format($date,"d-m-Y h:i:s");?></td>
							<td><?php echo $row['mobile']; ?></td>
							<td><?php echo $row['email']; ?></td>
							<td><?php echo $row['address']; ?></td>
							<td><?php $date = date_create($row['added_on']); echo date_format($date,"d-m-Y h:i:s");?></td>
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
				<h4 class="modal-title" id="myModalLabel">Search Company</h4>
			</div>
			<div class="modal-body">
				<form name="search_user" method="post" action="search_user">
					<div class="form-group">
						<div class="col-md-6">
							<label>Name </label>
							<input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" />						
						</div>
						<div class="col-md-6">
							<label>Company Name</label>
							<input type="text" class="form-control" id="companyname" name="companyname" placeholder="Enter Company Name"/> 
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
					<div class="form-group">
						<div class="col-md-6">
							<label>Mobile</label>
							<input type="text" class="form-control" id="mobile" name="mobile" placeholder="Enter Company Mobile"/>
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
</script>