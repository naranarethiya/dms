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
	})
	.fail(function(jqXHR, textStatus) {
		alert( "Request failed: " + textStatus );
	})
	.always(function() {
		$('#overlay').remove();
	});
}