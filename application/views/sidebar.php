<aside class="left-side sidebar-offcanvas">
	<!-- sidebar: style can be found in sidebar.less -->
	<section class="sidebar">
		<!-- sidebar menu: : style can be found in sidebar.less -->
		<ul class="sidebar-menu">
			<li>
				<a href="<?php echo base_url(); ?>">
					<i class="fa fa-dashboard"></i> <span>Dashboard</span>
				</a>
			</li>
			<?php if($this->session->userdata('role') == 'admin') { ?>
			<li>
				<a href="<?php echo base_url().'filemanager'; ?>">
					<i class="fa fa-folder"></i> <span>File Manager</span>
				</a>
			</li>						
		
			<li class="treeview">
				<a href="#">
					<i class="fa fa-fw fa-wrench"></i>
					<span>Settings</span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li><a href="<?php echo base_url().'dashboard/add_client'; ?>"><span>Add Client</span></a></li>
					<li><a href="<?php echo base_url().'dashboard/add_company'; ?>"><span>Add Company</span></a></li>								
					<li><a href="<?php echo base_url().'dashboard/view_company'; ?>"><span>View Company</span></a></li>
					<li><a href="<?php echo base_url().'dashboard/view_user'; ?>"><span>View Company User</span></a></li>
				</ul>
			</li>
			<?php } ?>
		</ul>					
	</section>
	<!-- /.sidebar -->
</aside>
		


<!-- Modal -->
<div class="modal fade" id="commanModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"></h4>
      </div>
      <div class="modal-body"></div>
    </div>
  </div>
</div>		
			