<header class="header">
	<a href="<?php echo base_url().'dashboard'; ?>" class="logo">
		<?php
			echo "DMS";
		?>		
	</a>
	<nav class="navbar navbar-static-top" role="navigation">
		<!-- Sidebar toggle button-->
		<div class="navbar-right">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="<?php echo base_url().'dashboard'; ?>" class="dropdown-toggle"><i class="fa fa-dashboard"></i><span> Dashboard</span></a>            
                </li>
                <li class="dropdown user user-menu">
                    <a href="<?php echo base_url().'file_manager'; ?>" class="dropdown-toggle"><i class="fa fa-folder"></i><span> File Manager</span></a>            
               </li>                                       
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-fw fa-wrench"></i><span>Settings</span> <i class="caret"></i>
                    </a>
                    <ul class="dropdown-menu" style="width: 0px;">
                        <li class="treeview"><a href="<?php echo base_url().'user'; ?>"><span>User</span></a></li>
                        <li class="treeview"></li>
                    </ul>                                            
                </li>
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="glyphicon glyphicon-user"></i>
                        <span><?php echo $this->session->userdata("name"); ?><i class="caret"></i></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="treeview">
                            <a href="#" onclick="$(cngpwd).modal('show');">
                                <i class="fa fa-user"></i>
                                <span>Change Password</span>
                            </a>
                        </li>
                        <li class="treeview">
                            <a href="<?php echo base_url().'login/logout'; ?>">
                                <i class="fa fa-sign-out"></i>
                                <span>SignOut</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
		</div>
	</nav>
</header>

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