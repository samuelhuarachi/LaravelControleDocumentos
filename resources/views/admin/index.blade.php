@extends('base')


@section('content')


<div style="margin-top:20px;" class="row">
	<div class="col-md-6">
		<a href="{{route('admin.listagem')}}"><button class="btn btn-success btn-xs"><span class="glyphicon glyphicon-user"></span> 
			Administradores</button></a>
		<a href="{{route('admin.notificacoes')}}"><button class="btn btn-info btn-xs"><span class="glyphicon glyphicon-repeat"></span> 
			Notificações</button></a>

		<a href="/auth/logout"><button type="button" class="btn btn-danger btn-xs pull-right"><span class="glyphicon glyphicon-off"></span> Sair</button></a>
		<a href="{{route('admin.registrar')}}"><button style="margin-right:5px;" type="button" class="btn btn-primary btn-xs pull-right"><span class="glyphicon glyphicon-plus-sign"></span> Adicionar novos clientes</button></a>
		<br><br>
		
		<div class="col-md-8 input-group">
			{!! Form::open(['route' => 'admin.cliente.localizar', 'class' => 'form form-inline', 'method' => 'post']) !!}
				<div class="form-group">
					<input class="form-control" type="text" name="pesquisa" >
				</div>
				<div class="form-group">
					<button class="btn btn-default" type="submit">
						<span class="glyphicon glyphicon-search"></span> Localizar cliente
					</button>
				</div>
			{!! Form::close() !!}
			@if($pesquisa)
				<small>termo da pesquisa: "{{ $pesquisa }}"</small>
			@endif
		</div><!-- /input-group -->

		<br>
		
		@if(count($clientes) > 0)
			<table class="table table-bordered">
				<tr>
					<td><span class="glyphicon glyphicon-user"></span> Clientes</td>
					<td><span class="glyphicon glyphicon-cog"></span> Ações</td>
				</tr>
				@foreach($clientes as $cliente)
					<tr>
						<td><a href="{{route('admin.cliente.editar', $cliente->id )}}">{{ $cliente->name }}</a></td>
						<td>
							<a href="{{ route('admin.documentos.clientes', [$cliente->id, 0, 0]) }}"><button style="margin-right:5px;" type="button" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-th-list"></span> documentos</button></a>

							<a onclick="return confirm('Tem certeza da exclusão?')" href="{{ route('admin.cliente.excluir', $cliente->id) }}"><button style="margin-right:5px;" type="button" class="btn btn-link btn-sm pull-right"><span class="glyphicon glyphicon-trash"></span></button></a>
						</td>
					</tr>
				@endforeach
			</table>

			
			{!! $clientes->render() !!}
			
			
		@else
			<small>Nenhum cliente cadastrado</small>
		@endif
	</div>
</div>

@include('_error-message')
@include('_success-message')

@stop

