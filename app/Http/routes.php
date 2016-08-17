<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group([ 'prefix' => 'admin', 'middleware' => ['auth', 'redireciona'] ], function() {

	//Trata os admin
	Route::get('lista-admins', ['as' => 'admin.listagem', 'uses' => 'AdminController@listagem']);
	Route::get('novo-admin', ['as' => 'admin.novo', 'uses' => 'AdminController@novo']);
	Route::post('novo-admin-registrar', ['as' => 'admin.novo.registrar', 'uses' => 'AdminController@novoRegistrar']);
	Route::get('editar-admin/{id}', ['as' => 'admin.editar', 'uses' => 'AdminController@editarAdmin']);
	Route::post('atualizar-admin', ['as' => 'admin.atualizar', 'uses' => 'AdminController@atualizarAdmin']);
	Route::get('excluir-admin/{id}', ['as' => 'admin.excluir', 'uses' => 'AdminController@excluirAdmin']);

	// Trata os acompanhamentos
	Route::post('acompanhar/store', ['as' => 'admin.acompanhar.store', 'uses' => 'AdminController@acompanharStore']);
	Route::get('acompanhar/{id}/remover', ['as' => 'admin.acompanhar.delete', 'uses' => 'AdminController@acompanharDelete']);
	
	// Mostra as atualizações
	Route::get('notificacoes', ['as' => 'admin.notificacoes', 'uses' => 'AdminController@notificacoes']);

	//Observacoes
	Route::get('observacao/{id}/remover', ['as' => 'admin.observacao.delete', 'uses' => 'AdminController@observacaoDelete']);
	

	Route::get('/', ['as' => 'admin.index', 'uses' => 'AdminController@index']);
	Route::get('/registrar', ['as' => 'admin.registrar', 'uses' => 'AdminController@registrar']);
	Route::post('/armazenar', ['as' => 'admin.armazenar', 'uses' => 'AdminController@armazenar']);
	Route::post('/cliente/localizar', ['as' => 'admin.cliente.localizar', 'uses' => 'AdminController@localizarCliente']);
	Route::get('/cliente/localizar', ['as' => 'admin.cliente.localizar', 'uses' => 'AdminController@localizarClienteGet']);
	Route::get('/cliente/editar/{id}', ['as' => 'admin.cliente.editar', 'uses' => 'AdminController@clienteEditar']);
	Route::post('/cliente/update', ['as' => 'admin.cliente.update', 'uses' => 'AdminController@clienteUpdate']);
	

	Route::get('/documentos/cliente/{id}/{nivel}/{relacao}', ['as' => 'admin.documentos.clientes', 'uses' => 'AdminController@documentosCliente']);
	Route::get('/documentos/cliente/{id}/novapasta/{nivel}/relacao/{relacao}', ['as' => 'admin.documentos.clientes.novapasta', 'uses' => 'AdminController@documentosClientePastasNova']);
	Route::post('/documentos/cliente/pasta/store', ['as' => 'admin.documentos.clientes.pasta.store', 'uses' => 'AdminController@documentosClientePastasStore']);

	Route::get('/documentos/cliente/novo-documento/{id}/{nivel}/{relacao}', ['as' => 'admin.documentos.docs', 'uses' => 'AdminController@documentosClienteNewDoc']);
	Route::post('/documentos/cliente/documento/store', ['as' => 'admin.documentos.clientes.documento.store', 'uses' => 'AdminController@documentosClienteDocumentosStore']);
	
	Route::get( '/documentos-detalhes/{clienteid}/{nivel}/{relacao}/{id}', ['as' => 'admin.documentos.detalhes', 'uses' => 'AdminController@documentosDetalhes'] );
	Route::post( '/documentos-anexo', ['as' => 'admin.documentos.anexo', 'uses' => 'AdminController@documentosAnexo'] );

	Route::post('/documentos/cliente/detalhes/update', ['as' => 'admin.documentos.clientes.detalhes.update', 'uses' => 'AdminController@documentosClienteDetalhesUpdate']);
	
	Route::get( '/apagar-anexo/{clienteid}/{nivel}/{relacao}/{id}/{nomeanexo}', ['as' => 'admin.documentos.apagar.anexo', 'uses' => 'AdminController@apagarAnexo'] );
	
	Route::get('/documentos/excluir/{id}', ['as' => 'admin.documentos.excluir', 'uses' => 'AdminController@documentosExcluir']);
	
	Route::get('/pasta/excluir/{id}', ['as' => 'admin.pasta.excluir', 'uses' => 'AdminController@pastasExcluir']);
	
	Route::get('/cliente/excluir/{id}', ['as' => 'admin.cliente.excluir', 'uses' => 'AdminController@clienteExcluir']);
	
	//Envia email
	Route::get('documento/{id}/enviaemail', ['as' => 'admin.documentos.email', 'uses' => 'AdminController@enviaEmail']);
	

});

Route::group([ 'prefix' => 'clientes', 'middleware' => ['auth', 'redireciona'] ], function() {
	Route::get('{nivel}/{relacao}', ['as' => 'clientes.index', 'uses' => 'ClienteController@index']);
	Route::get('/novapasta/{nivel}/{relacao}', ['as' => 'clientes.novapasta', 'uses' => 'ClienteController@documentosClientePastasNova']);
	Route::post('novapasta/store', ['as' => 'clientes.novapasta.store', 'uses' => 'ClienteController@documentosClientePastasStore']);

	Route::get('novo-documento/{nivel}/{relacao}', ['as' => 'clientes.novo-documento', 'uses' => 'ClienteController@documentosClienteNewDoc']);
	Route::post('novo-documento/store', ['as' => 'clientes.novo-documento.store', 'uses' => 'ClienteController@documentosClienteDocumentosStore']);
	
	Route::get('documentos-detalhes/{nivel}/{relacao}/{id}', ['as' => 'clientes.documentos.detalhes', 'uses' => 'ClienteController@documentosDetalhes'] );
	Route::post('/documentos-detalhes/update', ['as' => 'clientes.documentos.detalhes.update', 'uses' => 'ClienteController@documentosClienteDetalhesUpdate']);
	
	Route::post( 'documentos-anexo', ['as' => 'clientes.documentos.anexo', 'uses' => 'ClienteController@documentosAnexo'] );
	Route::get( 'apagar-anexo/{nivel}/{relacao}/{id}/{nomeanexo}', ['as' => 'clientes.documentos.apagar.anexo', 'uses' => 'ClienteController@apagarAnexo'] );
	
	Route::get('/pasta/excluir/{id}', ['as' => 'clientes.pasta.excluir', 'uses' => 'ClienteController@pastasExcluir']);


});



Route::get('/', function () {
    return view('welcome');
});


// Registration routes...

Route::get('auth/logout', ['as' => 'auth.login.logout', 'uses' => 'Auth\AuthController@getLogout']);
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');