


@if(Session::has('flash_message'))

	@section('javascript-includes')
		<script type="text/javascript">
		    $.notify(
		      "{{ Session::get('flash_message') }}", 
		      { position:"left top", className:"success" }
		    );
		</script>
	@stop

@endif
