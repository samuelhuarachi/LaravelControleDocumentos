@extends('base')


@section('content')

<div class="row">
    <div class="col-md-4 col-sm-6">
        <form method="POST" action="/auth/register">
            {!! csrf_field() !!}
            <div>
                <label for="InputName">Nome</label>
                <input type="text" name="name" class="form-control" id="InputName" value="{{ old('name') }}" >
            </div>

            <div>
                <label for="InputEmail1">E-mail</label>
                <input type="email" name="email" class="form-control" id="InputEmail1" value="{{ old('email') }}" >
            </div>

            <div>
                <label for="InputSenha">Senha</label>
                <input type="password" name="password"  class="form-control" id="InputSenha" >
            </div>

            <div>
                <label for="InputSenhaC">Senha</label>
                <input type="password" name="password_confirmation"  class="form-control" id="InputSenhaC" >
            </div>
            <hr>
            <div>
                <button type="submit" class="btn btn-primary btn-sm pull-right">Cadastrar</button>
            </div>
        </form>
    </div>
</div>



@stop


