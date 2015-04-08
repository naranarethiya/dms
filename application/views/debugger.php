<style type="text/css">
	.bottom-right {
		position: fixed;
	    bottom: 0;
	    right: 0;
	    cursor: pointer;
	    background-color: #DD4814;
	}
	.right {
	    right: 0;
	}
	#codeigniter_profiler {
		display: none;
		position: fixed;
		padding: 0px;
	    bottom: 0;
	    right: 0;
	    height: 270px;
	    overflow: scroll;
	    width: 100%;
	}
	.degugger_header {
		width: 100%;
		position: fixed;
		background-color: antiquewhite;
	}
</style>

<div class="bottom-right">
	<img src="http://www.codeigniter.com/assets/images/ci-logo-white.png" alt="Debugger"/>
</div>

<!--debugger -->
<div id="degugger_header" style="display:none">
	<div class="degugger_header">
		<ul class="nav nav-tabs">

			<li class="active">
				<a href="#database_query" data-toggle="tab">Query</a>
			</li>
			<li >
				<a href="#ci_profiler_post" data-toggle="tab">POST</a>
			</li>
			<li >
				<a href="#ci_profiler_get" data-toggle="tab">GET</a>
			</li>
			<li >
				<a href="#ci_profiler_csession" data-toggle="tab">Session</a>
			</li>
			<li >
				<a href="#ci_profiler_config" data-toggle="tab">Config</a>
			</li>
			<li >
				<a href="#ci_profiler_benchmarks" data-toggle="tab">Benchmarks</a>
			</li>
			<li >
				<a href="#ci_profiler_memory_usage" data-toggle="tab">Memory usage</a>
			</li>
			<li >
				<a href="#ci_profiler_uri_string" data-toggle="tab">URI String</a>
			</li>
			<li >
				<a href="#ci_profiler_controller_info" data-toggle="tab">Controller</a>
			</li>
			<li >
				<a href="#ci_profiler_http_headers" data-toggle="tab">HTTP Header</a>
			</li>
-			<li class="pull-right header" onclick="debugger_status();"><a style="color:red;" href="#" class="text-muted"><i class="fa fa-power-off"></i></a></li>
		</ul>
	</div>
</div>

<script>
	var status=0;
	var degugger_header=$('#degugger_header').html();
	var profiler=$('#codeigniter_profiler');
	
	$(document).ready(function() {
		create_debugger();
		$('.bottom-right').click(function() {
			debugger_status();
		});
	});

	function debugger_status() {
		if(status==0) {
			status=1;
			$('.bottom-right').hide();
			$('#codeigniter_profiler').show();
		}
		else {
			status=0;
			$('.bottom-right').show();
			$('#codeigniter_profiler').hide();
		}
	}

	function create_debugger() {
		var html='<div class="tab-content">'+$('#codeigniter_profiler').html()+'</div>';
		$('#codeigniter_profiler').html(html)
		$('#codeigniter_profiler').prepend(degugger_header);
		$('#codeigniter_profiler').css("padding","0px");
		$('#codeigniter_profiler fieldset').each(function() {
			var id=$(this).attr('id');
			if (typeof id == 'undefined'){
				$(this).attr('id','database_query')
				$(this).addClass('tab-pane active');
			}
			else {
				$(this).addClass('tab-pane');
			}
			$(this).css("margin-top","50px");
			
		});
	}
</script>