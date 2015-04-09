<?php
	if(isset($folder_info) && $folder_info['real_path'] !='') {
		$up_folder=$folder_info['parent_folder_id'];
	}
	else {
		$up_folder="1";
	}
	if(isset($file)) {
		$file=$file[0];
	}
?>
<!-- File folder view BEGIN -->
<div class="mailbox row">
	<div class="col-xs-12">
		<div class="box box-solid">
			<div class="box-body">
				<div class="row">
					<div class="col-md-3 col-sm-4">
						 <div class="box-header">
						 	<h5>
								Document Information
							</h5>
						</div>
						<div>
							<ul class="nav nav-pills nav-stacked">
								<li class="header"></li>
							</ul>
							<div class="well">
								<table class="table-condensed">
									<tbody>
										<tr>
											<td>ID:</td>
											<td><?php echo $file['document_file_id'];?></td>
										</tr>
										<tr>
											<td>Owner:</td>
											<td><?php echo $file['first_name'].' '.$file['last_name'];?></td>
										</tr>
										<tr>
											<td>Access:</td>
											<td><?php if($file['inherited_access']=="1") { echo "Inherited"; }?></td>
										</tr>
										<tr>
											<td>Size:</td>
											<td><?php echo $file['file_size'].'KB';?></td>
										</tr>
										<tr>
											<td>Keyword:</td>
											<td><?php echo $file['keywords'];?></td>
										</tr>										
										<tr>
											<td>Created At:</td>
											<td><?php echo dateformat($file['created_at']);?></td>
										</tr>																																								
									</tbody>
								</table>
							</div>
						</div>
					</div><!-- /.col (LEFT) -->
					<div class="col-md-9 col-sm-8">
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
										<li><a href="#">Download</a></li>
										<li><a href="#">Delete</a></li>
										<li><a href="#">Permission</a></li>
										<li><a href="#">Mark Favourite</a></li>
										<li><a href="#">Clear Favourite</a></li>
									</ul>
								</div>							
								<!-- Action button -->
								<input type="hidden" id="parent_folder_id" value="<?php if(isset($folder_id)) { echo $folder_id; } else { echo $this->session->userdata('home_folder'); }?>"/>
								<a href="#" onclick="create_folder()" class="btn btn-sm btn-primary"><i class="fa fa-folder"></i><span>&nbsp;&nbsp;New Folder</span></a>
								<a href="#" onclick="create_file()" class="btn btn-primary btn-sm"><i class="fa fa-file"></i><span>&nbsp;&nbsp;New File</span></a>	
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
						<div class="row pad">
							<a href="#" class="fa fa-fw fa-refresh" onclick="window.location.reload( true );" data-toggle="tooltip" data-placement="top" title="Refresh"></a>
							<a href="<?php echo base_url()."file_manager/index/".$up_folder;?>" class="fa fa-fw fa-arrow-up" data-toggle="tooltip" data-placement="top" title="Up-Level"></a>
							<a href="#" class="fa fa-fw fa-arrow-left" onclick="window.history.back();" data-toggle="tooltip" data-placement="top" title="Back"></a>
						</div>	
						<div class="well">
							<table class="table-condensed">
								<tbody>
									<tr>
										<td width="25%"></td>
										<td width="25%">File</td>
										<td width="25%">Comment</td>
										<td width="25%">Action</td>
									</tr>
									<tr>
										<td><?php echo "<img src='".getMimeIcon($file['file_name'])."' width='50' height='50'/>"; ?></td>
										<td>
											<?php echo $file['file_name'];?><br/>
											<?php echo 'Version:'.$file['file_version'];?><br/>
											<?php echo $file['file_size'].'KB';?><br/>
											<?php echo 'Uploaded by'.$file['first_name'].' '.$file['last_name'];?><br/>
											<?php echo dateformat($file['created_at']);?>
										</td>
										<td><?php echo $file['description'];?></td>
										<td>
											<i class="fa fa-fw fa-download"></i>Download<br/>
											<i class="fa fa-fw fa-external-link"></i>View Online
										</td>
									</tr>																																							
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
});

function change_folder(ele) {
	$('#parent_folder_id').val(ele);
	var folder_id=ele;
	var url=base_url+"file_manager/index/"+folder_id;
	window.open(url,"_self");
}

function create_folder() {
	var parent_folder_id=$('#parent_folder_id').val();
	var url=base_url+"file_manager/create_folder/"+parent_folder_id;
	get_modaldata('New Folder',url);
}

function create_file() {
	var parent_folder_id=$('#parent_folder_id').val();
	var url=base_url+"file_manager/create_file/"+parent_folder_id;
	get_modaldata('New File',url);
}

function up_level() {
	var folder_id=$('#parent_folder_id').val();
	$.ajax({
		url     : base_url+"file_manager/upward_level/",
		type    : 'POST',
		data    : {'folder_id':folder_id},
		success : function(data){
			var url=base_url+"file_manager/index/"+data;
			window.open(url,"_self");				
		}
	});
}
</script>