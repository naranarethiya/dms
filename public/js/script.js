/* for active currant page */
$(function(){
	var url = window.location.pathname;  
	var activePage = url.substring(url.lastIndexOf('/'));
	$('.sidebar-menu a').each(function(){  
		var currentPage = this.href;
		currentPage =currentPage.substring(this.href.lastIndexOf('/'));
		if (activePage == currentPage) {
			var parent=$(this).parent().parent();
			if($(parent).hasClass('sidebar-menu')) {
				$(this).parent().addClass('active'); 	
			}
			else {
				$(parent).parent().addClass('active');
				$(parent).css("display","block");		
			}
		} 
	});
});

function get_folder_tree(ele) {
	if($(ele).is(':checked')) {
		var folder=$(ele).val();
		var url=base_url+"file_manager/folder_tree/"+folder;
		$.ajax({
			"url":url,
			"method":"POST",
			"success": function(data) {
				$('#ol'+folder).html(data);
			}
		});	
	}
}

$(document).ready(function() {
	/* for tree menu */
	$('input[name="folder_treecheckbox"]').click(function() {
		if($(ele).is(':checked')) {
			var folder=$(ele).val();
			var url=base_url+"file_manager/folder_tree/"+folder;
			$.ajax({
				"url":url,
				"method":"POST",
				"success": function(data) {
					$('#ol'+folder).html(data);
				}
			});	
		}
	});

	/* for Breadcrumbs */
	$(window).resize(function() {
        ellipses1 = $("#breadcrumb_1 :nth-child(2)")
        if ($("#bc1 a:hidden").length >0) {ellipses1.show()} else {ellipses1.hide()}       
    })

});

/* ajax waiting */
function loading() {
	var over='<div id="overlay"><img src="'+base_url+'public/img/gif-load.gif" id="loading" /></div>'
	$('body').append(over);
}

function remove_loading() {
	$('#overlay').remove();
}

function load_popup(title,data) {
	$('#commanModal .modal-title').html(title);
	$('#commanModal .modal-body').html(data);
	$('#commanModal').modal('show');
}

function get_modaldata(title,url) {
	$.ajax({
		type:"POST",
		url:url,
		beforeSend:function() {
			loading();
		}

	})
	.done(function(data) {
		load_popup(title,data);
		$('.chosen-container').css('width','250px');
	})
	.fail(function(jqXHR, textStatus) {
		alert( "Request failed: " + textStatus );
	})
	.always(function() {
		$('#overlay').remove();
	});
}
