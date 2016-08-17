@extends('base')


@section('content')


<div style="margin-top:20px;" class="row">
	<div class="col-md-6">
		<br>
        <a href="{{route('admin.listagem')}}"><button type="button" class="btn btn-default btn-xs">Voltar</button></a>
        <br>
		
		<h3>Editando administrador {{ $userFind->name }}</h3>
        <form method="POST" action="{{ route('admin.atualizar') }}">
            {!! csrf_field() !!}
            <input type="hidden" name="userid" value="{{ $userFind->id }}" />
            <div>
                <label for="InputName">Nome <span style="color:#ff0000;">*</span></label>
                <input type="text" name="name" class="form-control" id="InputName" value="{{ $userFind->name }}" >
            </div>

            <div>
                <label for="InputEmail1">E-mail <span style="color:#ff0000;">*</span></label>
                <input type="email" name="email" class="form-control" id="InputEmail1" value="{{ $userFind->email }}" >
            </div>
            <div>
                <label for="InputEmail1">Telefone</label>
                <input type="text" name="telefone" class="form-control" id="InputEmail1" value="{{ $userFind->telefone }}" >
            </div>
            <div>
                <label for="InputEmail1">CPF/CNPJ</label>
                <input type="text" name="cpf" class="form-control" id="InputEmail1" value="{{ $userFind->cpf }}" >
            </div>
            <div>
                <label for="InputSenha">Senha <span style="color:#ff0000;">*</span></label>
                <input type="password" name="password"  class="form-control" id="InputSenha" >
            </div>

            <hr>
            <div>
                
                <button type="submit" class="btn btn-primary btn-sm pull-right">
                	<span class="glyphicon glyphicon-refresh"></span> Atualizar</button>
            </div>
        </form>
        <br>

    </div>
</div>

@include('_error-message')
@include('_success-message')


@stop