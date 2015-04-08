<?php if($this->session->userdata('success') != '') { ?>
<div class="alert alert-success alert-dismissable">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
	<?php 
		echo "<ul>".$this->session->userdata('success')."</ul>"; 
		$this->session->unset_userdata('success');
	?>
</div>
<?php } ?>
<?php if($this->session->userdata('error') != '') { ?>
<div class="alert alert-danger alert-dismissable">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
	<?php 
		echo "<ul>".$this->session->userdata('error')."</ul>"; 
		$this->session->unset_userdata('error');
	?>
</div>
<?php } ?>