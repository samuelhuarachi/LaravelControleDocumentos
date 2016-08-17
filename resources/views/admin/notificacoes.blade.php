@extends('base')


@section('content')


<div style="margin-top:20px;" class="row">
	<div class="col-md-6">
		<br>
        <a href="{{route('admin.index')}}"><button type="button" class="btn btn-danger btn-sm">Voltar</button></a>
        <br>

		<h3><span style="color:#333;" class="glyphicon glyphicon-bell"></span> Notificações</h3>
		
		<a href="{{ route('admin.notificacoes') }}"><button class="btn btn-primary pull-right btn-xs">Atualizar 
			<span class="glyphicon glyphicon-refresh"></span></button></a>

		<br>
		<br>
		<table class="table table-bordered">

			@foreach($observacao->getMyObservations(Auth::user()->id) as $obs)
				<?php  
					$documentoFind = $documento->find($obs->arq_id);
				?>
				<tr>
					<td>
						<small style="color:#333;">{{ $obs->observacao }} ({{$obs->nome}} / {{ date('d/m/Y H:i:s', strtotime($obs->created_at))  }})</small>
						<a onclick="return confirm('Tem certeza da exclusão?')" href="{{route('admin.observacao.delete', $obs->id)}}"><span class="glyphicon glyphicon-trash"></span></a>
					</td>
					<td>
						@if($documentoFind)
							<a href="{{ route('admin.documentos.detalhes', [$documentoFind->user_id, 
								$documentoFind->nivel, $documentoFind->relacao, $documentoFind->id]) }}">
								<button class="btn btn-xs btn-success">acessar 
								<span class="glyphicon glyphicon-forward"></span></button>
							</a>
						@else
							<small style="color:#e54b4b;">Documento deletado</small>
						@endif
						
					</td>
				</tr>
			@endforeach

		</table>
	</div>
</div>

@stop
