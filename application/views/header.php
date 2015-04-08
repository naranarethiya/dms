	<header class="header">
		<a href="<?php echo base_url(); ?>" class="logo">
			<?php
/* 			if($this->session->userdata('dms_companyid')!="0") {
				$this->db->where('dms_companyid',$this->session->userdata('dms_companyid'));
				$ires=$this->db->get('dms_company');
				$iaaray=$ires->result_array();
				echo $iaaray[0]['company_name'];
			}
			else { */
				echo "DMS";
			//}
			?>		
		</a>
		<nav class="navbar navbar-static-top" role="navigation">
			<!-- Sidebar toggle button-->
			<a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<div class="navbar-right">
                <ul class="nav navbar-nav">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <?php echo ucfirst($this->session->userdata('role')); ?>
                        </a>
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