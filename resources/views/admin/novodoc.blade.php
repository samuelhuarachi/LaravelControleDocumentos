@extends('base')


@section('content')

<div class="row">
    <div class="col-md-4 col-sm-6">
        <hr>
            <a href="{{route('admin.documentos.clientes', [$id, $nivel, $relacao])}}"><button type="button" class="btn btn-default btn-xs">Voltar</button></a>
        
        <br>
        <h3>Novo documento</h3>
        <form method="POST" action="{{ route('admin.documentos.clientes.documento.store') }}">
            {!! csrf_field() !!}
            <div>
                <label for="InputName">Título <span style="color:#ff0000;">*</span></label>
                <input type="hidden" name="user_id" value="{{ $id }}">
                <input type="hidden" name="nivel" value="{{ $nivel }}">
                <input type="hidden" name="relacao" value="{{ $relacao }}">
                <input type="text" name="titulo" class="form-control" id="InputName" value="{{ old('titulo') }}" >
                
                <br>
                <label for="InputName">Detalhes</label>
                <textarea name="detalhes" class="form-control" rows="4" cols="50">{{ old('detalhes') }}</textarea>
                
            </div>
            <hr>
            <div>
                <button type="submit" class="btn btn-primary btn-sm pull-right">Adicionar</button>
            </div>
        </form>
        <br>
        <br>
        @include('_error-message')
        @include('_success-message')
    </div>
</div>



@stop


