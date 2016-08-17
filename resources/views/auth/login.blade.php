@extends('base')

@section('content')

<div class="col-sm-6">
    <form method="POST" action="/auth/login">
        {!! csrf_field() !!}
        <div>
            E-mail
            <input class="form-control" type="email" name="email" value="{{ old('email') }}">
        </div>
        <div>
            <br>
            Senha
            <input class="form-control" type="password" name="password" id="password">
        </div>
        <div>
            <br>
            <button class="btn btn-primary pull-right" type="submit">Login</button>
        </div>
    </form>
</div>


@stop