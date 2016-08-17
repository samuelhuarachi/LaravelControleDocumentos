@extends('base')


@section('content')

<div class="row">
    <div class="col-md-4 col-sm-6">
        <br>
        <a href="{{route('admin.listagem')}}"><button type="button" class="btn btn-default btn-xs">Voltar</button></a>
        <br>
        
        <h3>Novo Admnistrador</h3>
        <form method="POST" action="{{ route('admin.novo.registrar') }}">
            {!! csrf_field() !!}
            <div>
                <label for="InputName">Nome <span style="color:#ff0000;">*</span></label>
                <input type="text" name="name" class="form-control" id="InputName" value="{{ old('name') }}" >
            </div>

            <div>
                <label for="InputEmail1">E-mail <span style="color:#ff0000;">*</span></label>
                <input type="email" name="email" class="form-control" id="InputEmail1" value="{{ old('email') }}" >
            </div>
            
                <input type="hidden" name="telefone" class="form-control" id="InputEmail1" value="{{ old('telefone') }}" >
         
                <input type="hidden" name="cpf" class="form-control" id="InputEmail1" value="{{ old('cpf') }}" >
          
            <div>
                <label for="InputSenha">Senha <span style="color:#ff0000;">*</span></label>
                <input type="password" name="password"  class="form-control" id="InputSenha" >
            </div>

            <hr>
            <div>
                
                <button type="submit" class="btn btn-primary btn-sm pull-right"><span class="glyphicon glyphicon-plus"></span> Adicionar</button>
            </div>
        </form>
        <br>
        

    </div>
</div>

@include('_error-message')
@include('_success-message')

@stop


