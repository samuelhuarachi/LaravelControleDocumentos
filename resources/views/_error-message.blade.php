@if ($errors->any())

	@section('javascript-includes')
		<script type="text/javascript">
			@foreach ($errors->all() as $error)

				$.notify(
			      "{!! $error !!}", 
			      { position:"left top" }
			    );

			@endforeach
		</script>
	@stop

@endif

