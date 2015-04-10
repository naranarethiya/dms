<?php
	if(isset($folder_info) && $folder_info['real_path'] !='') {
		$up_folder=$folder_info['parent_folder_id'];
	}
	else {
		$up_folder="1";
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
							<?php
							//dsm($folder_info);die;
								echo create_breadcrumbs($file['real_path'],$file['id_path']);
							?>
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
											<td><?php echo $file['document_id'];?></td>
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
							<a href="#" class="fa fa-fw fa-refresh" onclick="window.location.reload( true );" data-toggle="tooltip" data-placement="top" title="Refresh"></a>
							<a href="<?php echo base_url()."file_manager/index/".$file['parent_folder_id'];?>" class="fa fa-fw fa-arrow-up" data-toggle="tooltip" data-placement="top" title="Up-Level"></a>
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
											<?php echo 'Version: '.$file['file_version'];?><br/>
											<?php echo 'Size: '.$file['file_size'].'KB';?><br/>
											<?php echo 'Uploaded by: '.$file['first_name'].' '.$file['last_name'];?><br/>
										</td>
										<td><?php echo $file['description'];?></td>
										<td valign="top">
											<a href="<?php echo base_url().'file_manager/download_file/'.$file['document_id'].'/'.$file['file_version'];?>"><i class="fa fa-fw fa-download"></i>Download</a><br/>
											<a href="#"><i class="fa fa-fw fa-copy"></i> Copy</a><br/>
											<a href="#"><i class="fa fa-fw fa-cut "></i> Move</a><br/>
											<a href="#"><i class="fa fa-fw fa-trash-o"></i> Delete</a><br/>
											<?php if($file['favorite_users']!='') {
												echo'<a href="#"><i class="fa fa-fw fa-star-o"></i> Clear Favorite</a><br/>';
											}
											else {
												echo '<a href="#"><i class="fa fa-fw fa-star"></i> Mark Favorite</a> <br/>';
											} ?>
										</td>
									</tr>
									<?php foreach ($file['child'] as $child) { ?>
										<tr>
											<td><?php echo "<img src='".getMimeIcon($child['file_name'])."' width='50' height='50'/>"; ?></td>
											<td>
												<?php echo $child['file_name'];?><br/>
												<?php echo 'Version: '.$child['file_version'];?><br/>
												<?php echo 'Size: '.$child['file_size'].'KB';?><br/>
												<?php echo 'Uploaded by: '.$child['first_name'].' '.$child['last_name'];?><br/>
											</td>
											<td><?php echo $child['description'];?></td>
											<td valign="top">
												<a href="<?php echo base_url().'file_manager/download_file/'.$child['document_file_id'].'/'.$child['file_version'];?>"><i class="fa fa-fw fa-download"></i>Download</a><br/>
												<!--<i class="fa fa-fw fa-external-link"></i>View Online<br/>
												<i class="fa fa-fw fa-external-link"></i>Copy<br/>
												<i class="fa fa-fw fa-external-link"></i>Move<br/>
												<i class="fa fa-fw fa-external-link"></i>Delete<br/>
												if($file['favorite_id']=='') {
													<i class="fa fa-fw fa-external-link"></i>Mark Favorite<br/>
												}
												else {
													<i class="fa fa-fw fa-external-link"></i>Clear Favorite<br/>
												}
												-->
											</td>
										</tr>										
									<?php } ?>																																							
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