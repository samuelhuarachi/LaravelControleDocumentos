<?php

namespace documentos;

use Illuminate\Database\Eloquent\Model;

class Acompanhando extends Model
{
    
    protected $fillable = ['admin_id', 'arq_id', 'visualizacao', 'observacoes'];

    public function getInfo($admin_id, $arq_id) {

    	return $this->where('admin_id', $admin_id)->where('arq_id', $arq_id)->get();
    }

    public function setInfo($admin_id, $arq_id) {

    	$acompanhamento = $this::create([
            'admin_id' => $admin_id,
            'arq_id' => $arq_id
        ]);

    	return $acompanhamento;

    }

    public function getAcompanhantes($arq_id) {
    	return $this->where('arq_id', $arq_id)->get();
    }


    public function getInfoByUseridNews($admin_id) {
    	return $this->where('admin_id', $admin_id)->where('visualizacao', 0)->get();
    }
    
    public function registraView($admin_id, $arq_id) {
    	$info = $this->getInfo($admin_id, $arq_id);
    	
    	if(count($info) > 0) {
    		$data = $info->toArray()[0];
    		$data['visualizacao'] = (int)$data['visualizacao'] + 1;
    		
    		$acompFind = $this->find($data['id']);
    		$data['id'] = $acompFind->id;
    		$acompFind->update($data);
    	}
    }

    public function resetaViews($admin_id, $arq_id) {
    	$info = $this->getInfo($admin_id, $arq_id);
    	
    	if(count($info) > 0) {
    		$data = $info->toArray()[0];
    		$data['visualizacao'] = -1;
    		
    		$acompFind = $this->find($data['id']);
    		$data['id'] = $acompFind->id;
    		$acompFind->update($data);
    	}
    }

    

}
