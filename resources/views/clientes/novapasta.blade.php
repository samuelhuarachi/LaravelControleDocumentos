@extends('base')


@section('content')

<div class="row">
    <div class="col-md-4 col-sm-6">
        <hr>
            <a href="{{route('clientes.index', [$nivel, $relacao])}}"><button type="button" class="btn btn-default btn-xs">Voltar</button></a>
        
        <br>
        <br>
        <form method="POST" action="{{ route('clientes.novapasta.store') }}">
            {!! csrf_field() !!}
            <div>
                <label for="InputName">Nome da nova pasta</label>
                <input type="hidden" name="nivel" value="{{ $nivel }}">
                <input type="hidden" name="relacao" value="{{ $relacao }}">
                <input type="text" name="pasta" class="form-control" id="InputName" value="{{ old('pasta') }}" >
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


