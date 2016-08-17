@extends('base')


@section('content')

<div class="row">
    <div class="col-md-4 col-sm-6">
        <br>
        <a href="{{route('admin.index')}}"><button type="button" class="btn btn-danger btn-sm">Voltar</button></a>
        <br>
        <h3>Editar cliente</h3>
        <form method="POST" action="{{route('admin.cliente.update')}}">
            {!! csrf_field() !!}
            <input type="hidden" name="userid" value="{{ $userfind->id }}">
            <div>
                <label for="InputName">Nome <span style="color:#ff0000;">*</span></label>
                <input type="text" name="name" class="form-control" id="InputName" value="{{ $userfind->name }}" >
            </div>

            <div>
                <label for="InputEmail1">E-mail <span style="color:#ff0000;">*</span></label>
                <input type="email" name="email" class="form-control" id="InputEmail1" value="{{ $userfind->email }}" >
            </div>
            <div>
                <label for="InputEmail1">Telefone</label>
                <input type="text" name="telefone" class="form-control" id="InputEmail1" value="{{ $userfind->telefone }}" >
            </div>
            <div>
                <label for="InputEmail1">CPF</label>
                <input type="text" name="cpf" class="form-control" id="InputEmail1" value="{{ $userfind->cpf }}" >
            </div>
            <div>
                <label for="InputSenha">Senha <span style="color:#ff0000;">*</span></label>
                <input type="password" name="password"  class="form-control" id="InputSenha" >
            </div>

            <hr>
            <div>
                
                <button type="submit" class="btn btn-primary btn-sm pull-right"><span class="glyphicon glyphicon-refresh"></span> Atualizar</button>
            </div>
        </form>
        <br>
        @include('_error-message')

    </div>
</div>

@include('_error-message')
@include('_success-message')

@stop


