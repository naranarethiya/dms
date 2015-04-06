<div class="form-group">
	<label>First Name <span class="text-danger">*</span></label>
	<input type="text" class="form-control" id="fname" name="fname" placeholder="Enter First Name" required/> 
</div>		
<div class="form-group">
	<label>Last Name <span class="text-danger">*</span></label>
	<input type="text" class="form-control" id="lname" name="lname" placeholder="Enter Last Name" required/> 
</div>						
<div class="form-group">
	<label>Mobile <span class="text-danger">*</span> <span class="text-danger" style="color:red;"><b>(Will be used for login)</b></span></label>
	<input type="text" class="form-control" id="mobile" name="mobile" placeholder="Enter Last Name" required pattern="[789][0-9]{9}"/> 			
</div>
<div class="form-group">
	<label>Password <span class="text-danger">*</span></label>
	<input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required/> 
</div>
<div class="form-group">
	<label>Email</label>
	<input type="email" class="form-control" id="email" name="email" placeholder="Enter Email"/>
</div>					
<div class="form-group">
	<label>Date of Birth(YYYY-MM-DD)</label>
	<input type="text" class="form-control" name="dob" id="dob" data-date-format="yyyy-mm-dd" placeholder="1990-01-01" pattern="\d{4}-\d{1,2}-\d{1,2}">	
</div>
<?php if($this->session->userdata('role')=="manager") { ?>
<div class="form-group">
	<input type="hidden" class="form-control" id="puid" name="puid" value="<?php echo $this->session->userdata('uid');?>"/>
</div>	
<?php } ?>