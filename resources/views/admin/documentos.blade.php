@extends('base')


@section('content')

<div class="row">
    <div class="col-md-12 col-sm-12">
        <hr>
        <a href="{{route('admin.index')}}"><button type="button" class="btn btn-danger btn-sm">Voltar</button></a>
        <br>
        <br>
        <strong>Cliente</strong> {{ $cliente->name }}
        <br>
        <strong>E-mail</strong> {{ $cliente->email }}
        <br>
        <strong>Telefone</strong> {{ $cliente->telefone }}
        <br>
        <strong>CPF</strong> {{ $cliente->cpf }}
        <br>
        <div style="margin-top:30px;"></div>
        <img style="width:55px;" src="{{ asset('img/folders.png') }}" alt="">
        <a href="{{route('admin.documentos.docs', [$cliente->id, $nivel, $relacao])}}"><button style="margin-right:5px;" type="button" class="btn btn-primary btn-xs pull-right"><span class="glyphicon glyphicon-plus-sign"></span> Novo documento</button></a>
        <a href="{{route('admin.documentos.clientes.novapasta', [$cliente->id, $nivel, $relacao])}}"><button style="margin-right:5px;" type="button" class="btn btn-primary btn-xs pull-right"><span class="glyphicon glyphicon-plus-sign"></span> Nova pasta</button></a>
        
        <div style="margin-top:30px;"></div>

        @if($nivel > 0)
            <h3>Pasta {{ $pastaFind->pasta }} <a onclick="return confirm('Tem certeza da exclusão?')" href="{{ route('admin.pasta.excluir', $pastaFind->id) }}"><span style="color:#c2c2c2;" class="glyphicon glyphicon-trash"></span></a> </h3>
            <a href="{{ route('admin.documentos.clientes', [$cliente->id, ($nivel - 1), $pastaFind->relacao ]) }}"><button style="margin-right:5px;" type="button" class="btn btn-default btn-xs">Voltar</button></a>
            <hr>
        @endif


        @if(count($myFolders) > 0)
            @foreach($myFolders as $folder)
                <a class="tes" href="{{route('admin.documentos.clientes', [$cliente->id, ($nivel + 1), $folder->id ])}}">
                    <div class="pp">
                        <img src="{{ asset('img/folder.png') }}" alt="">
                        {{ $folder->pasta }}
                    </div>
                </a>
                
            @endforeach
        @endif

        @if(count($myDocuments) > 0)
            <h3>Meus documentos</h3>
            <table class="table table-bordered">
                <tr>
                    <td>Documento</td>
                    <td>Ações</td>
                </tr>
                @foreach($myDocuments->reverse() as $doc)
                    <tr>
                        <td>{{ $doc->titulo }}</td>
                        <td>
                            <a href="{{route('admin.documentos.detalhes', [$cliente->id, $doc->nivel, $doc->relacao, $doc->id ])}}"><button style="margin-right:5px;" type="button" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-list"></span> Detalhes</button></a>
                            
                            <a onclick="return confirm('Tem certeza da exclusão?')" href="{{ route('admin.documentos.excluir', $doc->id) }}"><button style="margin-right:5px;" type="button" class="btn btn-link btn-sm pull-right"><span class="glyphicon glyphicon-trash"></span></button></a>
                        </td>
                    </tr>
                    
                @endforeach
            </table>
            
        @endif
        

    </div>
</div>

<style>
    .pp {
        margin-right: 13px;
        display: inline-block;
        margin-bottom: 30px;
    }
    .pp:hover {
        opacity: 0.5;
        cursor: pointer;
    }
    .tes {
        text-decoration: none;
    }
</style>
@include('_error-message')
@include('_success-message')


@stop


