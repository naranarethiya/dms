<?php 
foreach ($files as $file) { 
	if($file['file_name']=='') {
		continue;
	}
?>
<tr>
<td class="small-col"><input type="checkbox" class="selectAll"/></td>
<td class="subject">
	<?php echo "<img src='".getMimeIcon($file['file_name'])."'/>"; ?>
	&nbsp;&nbsp;
	<a href="<?php echo base_url().'file_manager/file_view/'.$file['document_id']; ?>"><?php echo $file['file_name']; ?></a>&nbsp;&nbsp;
	<?php if($file['favorite_users']!='') {
		echo '<i class="fa fa-star"></i>&nbsp;';
	}  ?>
	<small class="label label-danger"><?php echo $file['keywords']; ?></small>
	<br/>
	<span>created at : <b><?php echo dateformat($file['created_at']);?></b></span>
</td>
<td class="small-col"><?php echo $file['file_extension'];?></td>
<td class="small-col"><?php echo formatted_size($file['file_size']);?></td>
<td>
	<a href="<?php echo base_url()."file_manager/download_file/".$file['document_id'].""; ?>"><i class="fa fa-fw fa-download"></i></a>
	<a href="#"><i class="fa fa-fw fa-edit" data-toggle="tooltip" data-placement="top" title="Edit"></i></a>
	<a href="#"><i class="fa fa-fw fa-trash-o" data-toggle="tooltip" data-placement="top" title="Delete"></i></a>
</td>
</tr>
<?php } ?>