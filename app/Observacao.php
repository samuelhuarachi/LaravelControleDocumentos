<?php

namespace documentos;

use Illuminate\Database\Eloquent\Model;

class Observacao extends Model
{
    
    protected $fillable = ['nome', 'arq_id', 'observacao', 'ativo', 'admin_id'];


    public function registraObservacoes($arq_id, $observacao, $nome, Acompanhando $acompanhando) {
    	
    	$acompanhantes = $acompanhando->getAcompanhantes($arq_id);

    	foreach ($acompanhantes as $acompanhante) {

			$this::create([
	                'admin_id'      => $acompanhante->admin_id,
	                'arq_id'        => $arq_id,
	                'observacao'   	=> $observacao,
	                'ativo'   		=> 1,
	                'nome' 			=> $nome
	            ]);
    	}

    }

    public function getMyObservations($admin_id) {
    	return $this->where('admin_id', $admin_id)->where('ativo', 1)->orderBy('id', 'DESC')->get();
    }

    public function removerObservation($id) {
        $info = $this->find($id);
        
        $data = $info->toArray()[0];
        $data['ativo'] = 0;
        
        $observacao = $this->find($data['id']);
        $data['id'] = $observacao->id;
        $observacao->update($data);
    }

}
