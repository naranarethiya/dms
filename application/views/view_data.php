<!-- MAILBOX BEGIN -->
<div class="mailbox row">
	<div class="col-xs-12">
		<div class="box box-solid">
			<div class="box-body">
				<div class="row">
					<div class="col-md-3 col-sm-4">
						 <div class="box-header">
							<i class="fa fa-folder"></i>
							<h3 class="box-title">Folders</h3>
						</div>
						<div>
						<ul class="nav nav-pills nav-stacked">
							<li class="header"></li>
						</ul>
							<ol class="tree">
									<li>
										<label>Folder 1</label>
										<input type="checkbox"/>
										<ol>
											<li>
												<label for="folder2">Folder2</label>
												<input type="checkbox" id="folder2"/>
												<ol>
													<li class="file">
														<a href="#">File 2</a>
													</li>
												</ol>
											</li>
											<li class="file">
												<a href="file">File 1</a>
											</li>
										</ol>
									</li>
								</ol>						
						</div>
					</div><!-- /.col (LEFT) -->
					<div class="col-md-9 col-sm-8">
						<div class="row pad">
							<div class="col-sm-6">
								<label style="margin-right: 10px;">
									<input type="checkbox" id="check-all"/>
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
								<a href="#" onclick="get_modaldata('New Folder','<?php echo base_url().'filemanager/create_folder'; ?>')" class="btn btn-sm btn-primary"><i class="fa fa-folder"></i><span>&nbsp;&nbsp;New Folder</span></a>
								<a href="#" class="btn btn-primary btn-sm"><i class="fa fa-file"></i><span>&nbsp;&nbsp;New File</span></a>	
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

						<div class="table-responsive">
							<table class='table table-mailbox'>
								<thead>
									<tr>
										<th>#</th>
										<th>Name</th> 
										<th>Type</th> 
										<th>Size</th> 
										<th>Date</th> 
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td class="small-col"><input type="checkbox" /></td>
                                        <td class="subject">
											<img src="<?php echo base_url().'public/img/img.gif';?>" />&nbsp;&nbsp;<a href="#">Panda.jpg</a>&nbsp;&nbsp;
											<small class="label label-danger">animal</small>
										</td>
										<td class="small-col"><?php echo "JPG";?></td>
										<td class="small-col"><?php echo "410 kb";?></td>
                                        <td class="name"><?php echo date('Y-m-d H:i:s');?></td>
										<td>
											<i class="fa fa-fw fa-download"></i>&nbsp;&nbsp;
											<i class="fa fa-fw fa-edit"></i>
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
<!-- MAILBOX END -->