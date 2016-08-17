@extends('base')


@section('content')

<div class="row">
    <div class="col-md-12 col-sm-12">

        <hr>

        <strong>Cliente</strong> {{ $clienteFind->name }}
        <br>
        <strong>E-mail</strong> {{ $clienteFind->email }}
        <br>
        <strong>Telefone</strong> {{ $clienteFind->telefone }}
        <br>
        <strong>CPF</strong> {{ $clienteFind->cpf }}
        <br>
        <br>
        <a href="{{route('clientes.index', [$nivel, $relacao])}}"><button type="button" class="btn btn-default btn-xs">Voltar</button></a>
        
        <h3>Detalhes documento</h3>
        <form method="POST" action="{{ route('clientes.documentos.detalhes.update') }}">
            {!! csrf_field() !!}
            <div>
                <label for="InputName">Título</label>
                <input type="hidden" name="nivel" value="{{ $nivel }}">
                <input type="hidden" name="relacao" value="{{ $relacao }}">
                <input type="hidden" name="doc-id" value="{{ $documentoFind->id }}">
                <input type="text" name="titulo" class="form-control" id="InputName" value="{{ $documentoFind->titulo }}" >
                <br>
                <label for="InputName">Detalhes</label>
                <textarea name="detalhes" class="form-control" rows="4" cols="50">{{ $documentoFind->detalhes }}</textarea>
            </div>
            <hr>
            <div>
                <button type="submit" class="btn btn-primary btn-sm pull-right r2-atualizar"><span class="glyphicon glyphicon-refresh"></span> Atualizar</button>
            </div>
        </form>
        <br>
        <br>
        @include('_error-message')
        @include('_success-message')
        <h3>Arquivos anexados</h3>
        
        {!! Form::open(['route' => 'clientes.documentos.anexo', 'method' => 'post', 'enctype'=> "multipart/form-data", 'class' => 'form' ]) !!}
            <div class="form-group ">
                <input type="hidden" name="nivel" value="{{$nivel}}" >
                <input type="hidden" name="relacao" value="{{$relacao}}" >
                <input type="hidden" name="docid" value="{{$documentoFind->id}}" >
                {!! Form::file( 'documento' )  !!}
            </div>
            <button style="margin-right:5px;" type="submit" class="btn btn-primary btn-xs pull-right"><span class="glyphicon glyphicon-paperclip"></span> Anexar novo arquivo</button>
        {!! Form::close() !!}

        

        <br>
        <br>
        @if(count($arquivos) > 0)
            <table class="table table-bordered">
                <tr>
                    <td>Arquivos</td>
                    <td>Ações</td>
                </tr>
                @foreach(array_reverse($arquivos) as $arquivo)
                    <tr>
                        <td>
                            <small>{{$arquivo}}</small>
                        </td>
                        <td>
                            <a target="_blank" href="{{ asset('anexos/' . $documentoFind->id . '/' . $arquivo) }}"><button type="button" class="btn btn-link btn-xs"><span class="glyphicon glyphicon-eye-open"></span></button></a>
                            
                            <a target="_blank" href="{{ asset('anexos/' . $documentoFind->id . '/' . $arquivo) }}" download><button type="button" class="btn btn-link btn-xs"><span class="glyphicon glyphicon-download-alt"></span></button></a>
                            
                            {{-- <a href="{{ route('clientes.documentos.apagar.anexo', [ $nivel,$relacao, $documentoFind->id, $arquivo]  ) }}">
                                <button style="margin-right:5px;" type="button" class="btn btn-link btn-sm pull-right">
                                    <span class="glyphicon glyphicon-trash"></span>
                                </button>
                            </a> --}}
                            
                        </td>
                    </tr>
                @endforeach
            </table>
        @else
            <small>Nenhum arquivo anexado</small>
        @endif
        
    </div>
</div>



@stop





