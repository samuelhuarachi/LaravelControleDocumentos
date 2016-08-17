@extends('base')


@section('content')

<div style="margin-top:20px;" class="row">
	<div class="col-md-6">
		<br>
        <a href="{{route('admin.index')}}"><button type="button" class="btn btn-danger btn-sm">Voltar</button></a>
        <br>
        <a href="{{ route('admin.novo') }}"><button style="margin-bottom:20px;" class="btn btn-primary btn-xs pull-right">
        	Adicionar novo administrador</button></a>

		<table style="margin-top:20px;" class="table table-bordered">
			<tr>
				<td>
					<b>Administradores</b>
				</td>
				<td>
					<b>Ações</b>
				</td>
			</tr>
			@foreach($user->getAdmins() as $admin)
				<tr>
					<td><img width="20" src="{{ asset('img/administrator.png') }}" alt=""> {{ $admin->name }}</td>
					<td>
						<a href="{{ route('admin.editar', $admin->id) }}"><button class="btn btn-primary btn-xs">Editar</button></a>
						<a href="{{ route('admin.excluir', $admin->id) }}"><button style="margin-right:5px;" type="button" class="btn btn-link btn-sm pull-right"><span class="glyphicon glyphicon-trash"></span></button></a>
					</td>
				</tr>

			@endforeach
		</table>
		
		{!! $user->getAdmins()->render() !!}

	</div>
</div>

@stop