<?php

namespace documentos\Http\Controllers;

use Illuminate\Http\Request;

use documentos\Http\Requests;
use documentos\Http\Controllers\Controller;

use documentos\User;
use documentos\Pasta;
use documentos\documento;
use documentos\Funcoes;
use documentos\Diretorio;
use documentos\Acompanhando;
use documentos\Observacao;

use Auth;
use Session;
use Storage;
use File;

class ClienteController extends Controller
{
    

    public function index($nivel, $relacao, User $user, Pasta $pasta, documento $documento) {
    	$user = Auth::user();

		$myFolders = $pasta->getPastas($user->id, $nivel, $relacao);
		$myDocuments = $documento->getDocumentos($user->id, $nivel, $relacao);

		if((int)$relacao > 0) {
			$pastaFind = $pasta->find($relacao);
		}
		$cliente = $user;
		return view('clientes.index', compact('cliente', 'myFolders', 
			'relacao', 'nivel', 'pastaFind', 'myDocuments'));
    }


    public function documentosClientePastasNova($nivel, $relacao) {

		return view('clientes.novapasta', compact( 'nivel', 'relacao'));
	}
	
	public function documentosClientePastasStore(Requests\Pasta $request, Pasta $pasta,
		Session $session) {
		$data = $request->all();
		$user = Auth::user();
		$data['user_id'] = $user->id;
		$pastaFind = $pasta::create($data);

		$session::flash('flash_message', 'Pasta adicionada!'); //<--FLASH MESSAGE

		return redirect()->route('clientes.index', [ $data['nivel'], $data['relacao'] ] );
	}

	public function documentosClienteNewDoc($nivel, $relacao) {
		$user = Auth::user();
		$id = $user->id;

		return view('clientes.novodoc', compact('id', 'nivel', 'relacao'));
	}


	public function documentosClienteDocumentosStore(Requests\documento $request, 
		documento $documento, Session $session ) {
		$data = $request->all();
		$user = Auth::user();
		$data['user_id'] = $user->id;
		$docFind = $documento::create($data);

		$session::flash('flash_message', 'Documento foi adicionado!'); //<--FLASH MESSAGE

		return redirect()->route('clientes.index', [$data['nivel'], $data['relacao']]);
	}

	public function documentosDetalhes($nivel, $relacao, $id, documento $documento, User $user) {
		$user = Auth::user();
		$clienteId = $user->id;

		$documentoFind = $documento->find($id);

		$clienteFind = $user;

		$arquivos = $documento->pegaNomesArquivos( $id, Storage::disk('public_anexos')->getDriver()->getAdapter()->getPathPrefix() );
		
		return view('clientes.documentosdetalhes', compact('arquivos', 
			'clienteFind' ,'documentoFind', 'clienteId', 'nivel', 'relacao', 'id'));
	}

	public function documentosClienteDetalhesUpdate(Requests\documento $request, 
		documento $documento, Session $session, Observacao $observacao,
		Acompanhando $acompanhando) {
		$user = Auth::user();
		$data = $request->all();
		$docId = $data['doc-id'];

		$documentoFind = $documento->find($docId);
		if($user->id == $documentoFind->user_id) {
			$data = $request->all();
	        $docId = $data['doc-id'];
	        
	        $documentoFind->update($data);

	        //Log
	        $observacao->registraObservacoes($documentoFind->id, 
                    'CLIENTE - O título, ou a descrição do documento "'.$documentoFind->titulo.'" foi atualizado!',
                    Auth::user()->name, $acompanhando);


	        $session::flash('flash_message','Documento atualizado!'); //<--FLASH MESSAGE
		}

        return redirect()->route('clientes.documentos.detalhes', [ $data['nivel'], 
        	$data['relacao'], $data['doc-id'] ]);
    }


    public function documentosAnexo(Requests\Anexar $request, 
		documento $documento, Funcoes $funcoes, Session $session, Observacao $observacao,
		Acompanhando $acompanhando) {
		$user = Auth::user();
		$data = $request->all();
		$docId = $data['docid'];

		$documentoFind = $documento->find($docId);
		if($user->id == $documentoFind->user_id) {

			$file = $request->file('documento');
	        $extension = $file->getClientOriginalExtension();

	        $fileName = $file->getClientOriginalName();
	        $fileWithoutExtension = $funcoes->Slug(trim(str_replace('.'.$extension, '', $fileName)));

	        $documento->ajustaDiretorio( $data['docid'], Storage::disk('public_anexos')->getDriver()->getAdapter()->getPathPrefix() );

	        Storage::disk('public_anexos')->put( $data['docid'] .'/'. $fileWithoutExtension . '.' . $extension, File::get($file) );

	        $session::flash('flash_message', 'Anexo adicionado!'); //<--FLASH MESSAGE

	        $observacao->registraObservacoes($documentoFind->id, 
                    'CLIENTE - O anexo "'.$fileWithoutExtension . '.' . $extension.'" foi adicionado!',
                    Auth::user()->name, $acompanhando);

	    }
        return redirect()->route('clientes.documentos.detalhes', [ $data['nivel'], $data['relacao'], $data['docid'] ]);
	}

	public function apagarAnexo($nivel, $relacao, $docid, $anexoname, 
    	documento $documento, Session $session) {
		$user = Auth::user();

		$documentoFind = $documento->find($docid);
		if($user->id == $documentoFind->user_id) {
	    	$storage = Storage::disk('public_anexos')->getDriver()->getAdapter()->getPathPrefix();
	    	$documento->apagarAnexo($docid, $storage, $anexoname);

	    	$session::flash('flash_message', 'Anexo deletado!'); //<--FLASH MESSAGE
	    }

    	return redirect()->route('clientes.documentos.detalhes', [ $nivel, $relacao, $docid ]);
    }

    public function pastasExcluir($pastaId, Pasta $pasta, 
    	Documento $documento, Diretorio $diretorio, Session $session) {
    	//Pega a relacao da pasta
    	$pastaFind = $pasta->find($pastaId);
    	$userId = $pastaFind->user_id;
    	$user = Auth::user();
    	$relacao = $pastaFind->relacao;
    	$nivel = $pastaFind->nivel;
	
    	if($user->id == $pastaFind->user_id) {
	    	
	    	

	    	//Pega as pastas que estão dentro desta pasta
	    	$this->removePastas($pastaId, $documento, $diretorio, $pasta);

	    	$session::flash('flash_message','A pasta foi excluida!'); //<--FLASH MESSAGE
	    }

    	return redirect()->route('clientes.index', [ $nivel, $relacao ]);
    }

    public function removePastas($pastaId, $documento, $diretorio, $pastaM) {
    	$pastasInside = $pastaM->where('relacao', $pastaId)->get();

    	foreach ($pastasInside as $pasta) {
    		$pastasInside2 = $pasta->where('relacao', $pasta->id)->get();
    		if($pastasInside2) {
    			$this->removePastas($pasta->id, $documento, $diretorio, $pastaM);
    		}
    	}

    	//Pega todos os documentos
		$documentoInside = $documento->where('relacao', $pastaId)->get();
		$storage = Storage::disk('public_anexos')->getDriver()->getAdapter()->getPathPrefix();
		foreach ($documentoInside as $doc) {
			$caminho = $storage . $doc->id;
			$diretorio->deleteDirectory($caminho);
			$doc->delete();
		}
    	
		$pastaFind = $pastaM->find($pastaId);
		$pastaFind->delete();
    }



}
