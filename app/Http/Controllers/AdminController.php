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


use Session;
use Storage;
use File;
use Auth;

class AdminController extends Controller
{
    

	public function index(User $user, Session $session) {
		$clientes = $user->getClientes();

		$pesquisa = $session::get('pesquisa');
		if($pesquisa) {
			$clientes = $user->getClientes($pesquisa);
		}
		
		return view('admin.index', compact('clientes', 'pesquisa'));
	}


	public function registrar() {


		return view('admin.registrar');
	}

	public function armazenar(Requests\Registrar $request, User $user, Session $session) {
		$data = $request->all();

		$userFind = $user::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'telefone' => $data['telefone'],
            'cpf' => $data['cpf'],
            'password' => bcrypt($data['password']),
            'nivel' => 1,
            'ativo' => 1
        ]);

		$session::flash('flash_message','Cliente adicionado com sucesso!'); //<--FLASH MESSAGE
		return redirect()->route('admin.registrar');
	}

	public function documentosCliente($id, $nivel, $relacao, User $user, Pasta $pasta, documento $documento) {
		$cliente = $user->find($id);
		$myFolders = $pasta->getPastas($cliente->id, $nivel, $relacao);
		$myDocuments = $documento->getDocumentos($cliente->id, $nivel, $relacao);

		if((int)$relacao > 0) {
			$pastaFind = $pasta->find($relacao);
		}

		return view('admin.documentos', compact('cliente', 'myFolders', 
			'relacao', 'nivel', 'pastaFind', 'myDocuments'));
	}

	public function documentosClientePastasNova($id, $nivel, $relacao) {

		return view('admin.novapasta', compact('id', 'nivel', 'relacao'));
	}

	public function documentosClientePastasStore(Requests\Pasta $request, Pasta $pasta,
        Session $session) {
		$data = $request->all();
		$pastaFind = $pasta::create($data);

        $session::flash('flash_message', 'Pasta adicionada!'); //<--FLASH MESSAGE

		return redirect()->route('admin.documentos.clientes', [$data['user_id'], $data['nivel'], $data['relacao']]);
	}

	public function documentosClienteNewDoc($id, $nivel, $relacao) {


		return view('admin.novodoc', compact('id', 'nivel', 'relacao'));
	}

	public function documentosClienteDocumentosStore(Requests\documento $request, 
		documento $documento, Session $session ) {
		$data = $request->all();
		$docFind = $documento::create($data);

		$session::flash('flash_message', 'Documento foi adicionado!'); //<--FLASH MESSAGE


		return redirect()->route('admin.documentos.clientes', [$data['user_id'], $data['nivel'], $data['relacao']]);
	}

	public function documentosDetalhes($clienteId, $nivel, $relacao, $id, 
        documento $documento, User $user, Acompanhando $acompanhando) {
		$documentoFind = $documento->find($id);
		$clienteFind = $user->find($clienteId);

        //Logs
        $acompanhando->registraView(Auth::user()->id, $documentoFind->id);

		$arquivos = $documento->pegaNomesArquivos( $id, Storage::disk('public_anexos')->getDriver()->getAdapter()->getPathPrefix() );
		
		return view('admin.documentosdetalhes', compact('arquivos', 'clienteFind' ,
            'documentoFind', 'clienteId', 'nivel', 'relacao', 'id', 'user', 'acompanhando'));
	}

	public function documentosAnexo(Requests\Anexar $request, 
		documento $documento, Funcoes $funcoes, Session $session, 
        Acompanhando $acompanhando, Observacao $observacao) {
		
		$file = $request->file('documento');
        $extension = $file->getClientOriginalExtension();

        $fileName = $file->getClientOriginalName();
        $fileWithoutExtension = $funcoes->Slug(trim(str_replace('.'.$extension, '', $fileName)));

        $data = $request->all();
        $documento->ajustaDiretorio( $data['docid'], Storage::disk('public_anexos')->getDriver()->getAdapter()->getPathPrefix() );

        Storage::disk('public_anexos')->put( $data['docid'] .'/'. $fileWithoutExtension . '.' . $extension, File::get($file) );

        $session::flash('flash_message', 'Anexo adicionado!'); //<--FLASH MESSAGE

        //Log
        $observacao->registraObservacoes($data['docid'], 
                    'O anexo "'.$fileWithoutExtension . '.' . $extension.'" foi adicionado',
                    Auth::user()->name, $acompanhando);

        return redirect()->route('admin.documentos.detalhes', [ $data['user_id'], $data['nivel'], $data['relacao'], $data['docid'] ]);
	}

	public function documentosClienteDetalhesUpdate(Requests\documento $request, 
        documento $documento, Session $session, Acompanhando $acompanhando, Observacao 
        $observacao) {
        $data = $request->all();
        $docId = $data['doc-id'];
        $documentoFind = $documento->find($docId);
        $documentoFind->update($data);
        $session::flash('flash_message','Documento atualizado!'); //<--FLASH MESSAGE

        //$acompanhando->resetaViews(Auth::user()->id, $data['doc-id']);

        $observacao->registraObservacoes($docId, 
            'O título, ou a descrição do documento "'.$documentoFind->titulo.'" foi atualizado!',
            Auth::user()->name, $acompanhando);

        return redirect()->route('admin.documentos.detalhes', [ $data['user_id'], $data['nivel'], $data['relacao'], $data['doc-id'] ]);
    }


    public function apagarAnexo($userid, $nivel, $relacao, $docid, $anexoname, 
    	documento $documento, Session $session, Acompanhando $acompanhando, Observacao $observacao) {
    	$storage = Storage::disk('public_anexos')->getDriver()->getAdapter()->getPathPrefix();
    	$documento->apagarAnexo($docid, $storage, $anexoname);

    	$session::flash('flash_message', 'Anexo deletado!'); //<--FLASH MESSAGE

        //$acompanhando->resetaViews(Auth::user()->id, $docid);

        $observacao->registraObservacoes($docid, 
            'O anexo "'.$anexoname.'" foi deletado!',
            Auth::user()->name, $acompanhando);


    	return redirect()->route('admin.documentos.detalhes', [ $userid, $nivel, $relacao, $docid ]);
    }

    public function documentosExcluir($id, documento $documento, 
    	Diretorio $diretorio, Session $session, Observacao $observacao,
        Acompanhando $acompanhando) {

    	$documentoFind = $documento->find($id);

        $observacao->registraObservacoes($documentoFind->id, 
            'O documento "'.$documentoFind->titulo.'" foi deletado!',
            Auth::user()->name, $acompanhando);

    	$storage = Storage::disk('public_anexos')->getDriver()->getAdapter()->getPathPrefix();
    	$caminho = $storage . $documentoFind->id;
    	$diretorio->deleteDirectory($caminho);
    	$documentoFind->delete();

    	$session::flash('flash_message', 'Documento foi removido!'); //<--FLASH MESSAGE

        

    	return redirect()->route('admin.documentos.clientes', [  $documentoFind->user_id  ,
    	 $documentoFind->nivel, $documentoFind->relacao ]);

    }

    public function pastasExcluir($pastaId, Pasta $pasta, 
    	Documento $documento, Diretorio $diretorio, Session $session) {
    	//Pega a relacao da pasta
    	$pastaFind = $pasta->find($pastaId);
    	$relacao = $pastaFind->relacao;
    	$nivel = $pastaFind->nivel;
    	$userId = $pastaFind->user_id;

    	//Pega as pastas que estão dentro desta pasta
    	$this->removePastas($pastaId, $documento, $diretorio, $pasta);

    	$session::flash('flash_message','A pasta foi excluida!'); //<--FLASH MESSAGE

    	return redirect()->route('admin.documentos.clientes', [ $userId,
    	                                       $nivel, $relacao ]);
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

    public function clienteExcluir($userid, Pasta $pasta, Documento $documento,
    	Diretorio $diretorio, User $user, Session $session) {
    	
    	//Apaga pastas
    	$pastasInside = $pasta->where('relacao', 0)->where('user_id', $userid)->get();
    	foreach ($pastasInside as $pasta) {
    		$this->removePastas($pasta->id, $documento, $diretorio, $pasta);
    	}

    	//Apaga os documentos
    	$documentoInside = $documento->where('relacao', 0)->get();
		$storage = Storage::disk('public_anexos')->getDriver()->getAdapter()->getPathPrefix();
		foreach ($documentoInside as $doc) {
			$caminho = $storage . $doc->id;
			$diretorio->deleteDirectory($caminho);
			$doc->delete();
		}

		//apaga usuario
		$userFind = $user->find($userid);
		$userFind->delete();

		$session::flash('flash_message','O cliente foi excluido!'); //<--FLASH MESSAGE

		return redirect()->route('admin.index');
    }

    //Faz a pesquisa
   	public function localizarCliente(Request $request, 
   		Session $session, User $user) {
   		$data = $request->all();
   		$session::set('pesquisa', trim($data['pesquisa']));

		return redirect()->route('admin.index');
   	}

   	public function clienteEditar($id, User $user) {
   		$userfind = $user->find($id);

   		return view('admin.editarcliente', compact('userfind'));
   	}

   	public function clienteUpdate(Requests\UpdateCliente $request, 
        User $user, Session $session) {
   		$data = $request->all();
        $user->updateCliente($data);
        $session::flash('flash_message','Cliente atualizado com sucesso!'); //<--FLASH MESSAGE
        return redirect()->route('admin.cliente.editar', $data['userid']);
   	}

    // Lista todos os administradores
    public function listagem(User $user) {

        return view('admin.listagem', compact('user'));
    }

    public function novo() {

        return view('admin.novoadmin');
    }

    public function novoRegistrar(Requests\Registrar $request, User $user, Session $session) {
        $data = $request->all();

        $userFind = $user::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'telefone' => $data['telefone'],
            'cpf' => $data['cpf'],
            'password' => bcrypt($data['password']),
            'nivel' => 2,
            'ativo' => 1
        ]);

        $session::flash('flash_message','Admin adicionado com sucesso!'); //<--FLASH MESSAGE
        return redirect()->route('admin.novo');
    }

    public function editarAdmin($id, User $user) {
        $userFind = $user->find($id);


        return view('admin.editaradmin', compact('userFind'));
    }

    public function atualizarAdmin(Request $request, User $user, Session $session) {
        $data = $request->all();
        $user->updateCliente($data);
        $session::flash('flash_message','Administrador atualizado com sucesso!'); //<--FLASH MESSAGE
        return redirect()->route('admin.editar', $data['userid']);
    }

    public function excluirAdmin($id, User $user, Session $session) {
        //apaga usuario
        $userFind = $user->find($id);
        $userFind->delete();

        $session::flash('flash_message','O administrador foi excluído!'); //<--FLASH MESSAGE

        return redirect()->route('admin.listagem');

    }

    public function acompanharStore(Request $request, 
        Acompanhando $acompanhando, Session $session, Documento $documento,
        Observacao $observacao, User $user) {
        $data = $request->all();
        $arq_id = $data['arq_id'];
        $admin_id = $data['admin_id'];

        $infoLog = $acompanhando->getInfo($admin_id, $arq_id);
        if( count($infoLog) == 0 ) {
            $acompanhamento = $acompanhando->setInfo($admin_id, $arq_id);
        }

        $session::flash('flash_message','Administrador acompanhando!'); //<--FLASH MESSAGE

        $documentoFind = $documento->find($arq_id);

        $acompanhando->resetaViews(Auth::user()->id, $arq_id);

        $userFind = $user->find($admin_id);
        if($userFind) {
            $observacao->registraObservacoes($documentoFind->id, 
                'O "'.$userFind->name.'" está acompanhando o documento ' . $documentoFind->titulo,
                Auth::user()->name, $acompanhando);
        }

        return redirect()->route('admin.documentos.detalhes', [ $documentoFind->user_id, 
            $documentoFind->nivel, $documentoFind->relacao, $arq_id ]);
    }

    public function acompanharDelete($id, Acompanhando $acompanhando, 
        Session $session, Documento $documento, User $user, Observacao $observacao) {
        $acompanhandoFind = $acompanhando->find($id);
        $documentoFind = $documento->find($acompanhandoFind->arq_id);

        $userFind = $user->find($acompanhandoFind->admin_id);
        if($userFind) {
            $observacao->registraObservacoes($documentoFind->id, 
                    'O "'.$userFind->name.'" não está mais acompanhando o documento ' . $documentoFind->titulo,
                    Auth::user()->name, $acompanhando);
        }

        
        $acompanhandoFind->delete();

        $session::flash('flash_message','Retirado!'); //<--FLASH MESSAGE


        return redirect()->route('admin.documentos.detalhes', [ $documentoFind->user_id, 
            $documentoFind->nivel, $documentoFind->relacao, $documentoFind->id ]);


    }

    public function notificacoes(Acompanhando $acompanhando, 
        Documento $documento, Observacao $observacao) {


        return view('admin.notificacoes', compact('acompanhando', 'documento', 'observacao'));
    }

    public function observacaoDelete($id, Observacao $observacao) {
        $data = [];
        $data['id'] = $id;
        $data['ativo'] = 0;
        $obFind = $observacao->find($id);
        $obFind->update($data);

        return redirect()->route('admin.notificacoes');
    }

    public function enviaEmail($id, Documento $documento, Session $session) {
        $documentoFind = $documento->find($id);

        
        $message = 'A System Consultoria fez uma atualização no documento ' . $documentoFind->titulo . '
        Para acessar esse documentos, clique no link abaixo
        http://systenconsultoria.com.br/área-do-cliente

        Obrigado!
        ';

        mail($documentoFind->user->email, 'System Consultoria', $message);

        $session::flash('flash_message','E-mail enviado!'); //<--FLASH MESSAGE

        return redirect()->route('admin.documentos.detalhes', [ $documentoFind->user_id, 
            $documentoFind->nivel, $documentoFind->relacao, $documentoFind->id ]);
    }

}
