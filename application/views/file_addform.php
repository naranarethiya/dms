<div class="row">
	<form name="file_add" action="<?php echo base_url().'file_manager/save_file';?>" method="POST" enctype="multipart/form-data">
		<?php
			if(isset($file)) {
				echo "<input type='hidden' name='document_id' value='".$file['document_id']."'>";
				echo "<input type='hidden' name='document_file_id' value='".$file['document_file_id']."'>";
			}
		?>
		<input type="hidden" name="parent_folder_id" value="<?php if(isset($file)){ echo $file['parent_folder_id']; } else { echo $parent_folder_id; }?>">
		<div class="col-md-12">
			<div class="col-md-6">
				<label>File Owner<span class="text-danger">*</span></label>
	 			<?php 
	 				if(isset($file)) {
	 					$option=$file['owner_id'];
	 				}
	 				elseif($this->session->userdata('users_id')!='') {
	 					$option=$this->session->userdata('users_id');
	 				}
	 				else {
	 					$option='';
	 				}
					echo generate_combobox('owner_id',$owner,'users_id','username',$option,'class="form-control chosen" id="owner_id" required');
				?>							
			</div>
			<div class="col-md-6">
				<label>File Title</label>
				<input type="text" class="form-control" name="file_title" required value="<?php if(isset($file)) { echo $file['file_title']; } ?>">
			</div>			
		</div>	
		<div class="col-md-12">
			<div class="col-md-6">
				<div class="row">
					<div class="col-md-8">
						<label>File</label>		
						<input type="file" class="form-control" id="file" name="file" />
					</div>
					<div class="col-md-4">
						<!--<input type="button" value="+ Files" id="add_more" class="btn btn-sm btn-primary" style="margin-top:25px;"/>-->
					</div>
				</div>
			</div>			
			<div class="col-md-6">
				<label>Keyword</label>
				<input type="text" class="form-control autocomplete" name="keywords" placeholder="Choose Keyword" value="<?php if(isset($file)) { echo $file['keywords']; } ?>"/>						
			</div>
		</div>
		<div class="col-md-12">
			<div class="col-md-6">
				<label>Category</label>
	 			<?php 
					if(isset($file['categories_id'])){
						$option=$file['categories_id'];
						if(strpos($option,',')!== FALSE) {
							$option=explode(',',$option);
						}
						else {
							$option=$option;
						}
					}
					else {
						$option='';
					}
					echo generate_combobox('category_id[]',$category,'category_id','category_title',$option,'class="form-control chosen" multiple');
				?>							
			</div>			
			<div class="col-md-6">
				<label>Description</label>
				<textarea class="form-control" id="description" name="description" placeholder="Description"><?php if(isset($file)) { echo $file['description']; } ?></textarea> 
			</div>					
		</div>		
		<div class="col-md-12">	
			<div class="modal-footer">
				<input type="submit" name="submit"  class="btn btn-primary" value="Submit">
				<input type="reset" name="reset"  class="btn btn-default" value="Reset">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>		
		</div>
	</form>	
</div>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()."public/js/jquery-ui/jquery-ui.min.css"; ?>">
<script type="text/javascript" src="<?php echo base_url()."public/js/jquery-ui/jquery-ui.min.js"; ?>"></script>
<style type="text/css">
	ul.ui-autocomplete {
	    z-index: 5000;
	}
</style>	
<script type="text/javascript">
	$('.chosen').chosen();
	$(document).ready(function(){
		$('#add_more').click(function(e){
			e.preventDefault();
			$('#file').after("<br/><input name='file[]' class='form-control' type='file' />");
		});
	});	

/* Start keyword autocomplete */
$(function() {
    function split(val) {
      return val.split( /,\s*/ );
    }
    function extractLast(term) {
      return split( term ).pop();
    }
 	var source=base_url+"file_manager/get_keyword";
    $("input[name='keywords']")
    // don't navigate away from the field on tab when selecting an item
      .bind("keydown", function(event) {
        if (event.keyCode === $.ui.keyCode.TAB &&
            $(this).autocomplete( "instance" ).menu.active) {
          event.preventDefault();
        }
      })
    .autocomplete({
    source: function( request, response ) {
			$.getJSON( source, {
				term: extractLast( request.term )
			}, response );
		},
	search: function() {
          // custom minLength
          var term = extractLast( this.value );
          if ( term.length < 1 ) {
            return false;
          }
        },
    focus: function() {
          // prevent value inserted on focus
          return false;
    	},
    select: function(event, ui) {
		var terms = split( this.value );
		// remove the current input
		terms.pop();
		// add the selected item
		terms.push( ui.item.value );
		// add placeholder to get the comma-and-space at the end
		terms.push("");
		this.value = terms.join( "," );
		return false;
		}
	});      
});
/* End keyword autocomplete */	
</script>