<?php

namespace documentos;

use Illuminate\Database\Eloquent\Model;

class Pasta extends Model
{
    
    protected $fillable = ['user_id', 'nivel', 'pasta', 'relacao'];
    
    public function getPastas( $user_id, $nivel, $relacao ) {
    	return $this->where('nivel', $nivel)->where('user_id', $user_id)->where('relacao', $relacao)->get();

    }
}
