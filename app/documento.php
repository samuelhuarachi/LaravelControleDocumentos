<?php

namespace documentos;

use Illuminate\Database\Eloquent\Model;

class documento extends Model
{
    protected $fillable = ['user_id', 'nivel', 'relacao', 'titulo', 'detalhes'];

    public function user() {
        return $this->belongsTo('documentos\User');
    }

    public function getDocumentos($user_id, $nivel, $relacao) {
    	return $this->where('nivel', $nivel)->where('user_id', $user_id)->where('relacao', $relacao)->get();
    }

    public function ajustaDiretorio($docId, $storage) {
    	$caminho = $storage . $docId;
    	if (!file_exists($caminho)) {
            mkdir($caminho, 0755, true);
        }

        //Gera um arquivo index na página
        $content = "<h1>Documentos</h1>";
        $fp = fopen($caminho . DIRECTORY_SEPARATOR . "index.html","wb");
        fwrite($fp,$content);
        fclose($fp);
    }

    public function pegaNomesArquivos($docId, $storage) {
    	$caminho = $storage . $docId . DIRECTORY_SEPARATOR;
    	$files = [];
        if (file_exists($caminho)) {
            $arquivosFind = scandir($caminho);
            foreach ($arquivosFind as $arquivo) {
            	if($arquivo != '.' && $arquivo != '..' && $arquivo != 'index.html') {
            		array_push($files, $arquivo);
            	}
            }
        }
        return $files;
    }

    public function apagarAnexo($docId, $storage, $filename) {
        $caminho = $storage . $docId . DIRECTORY_SEPARATOR;
        $files = [];
        if (file_exists($caminho)) {
            $arquivosFind = scandir($caminho);
            foreach ($arquivosFind as $arquivo) {
                if($arquivo == $filename) {
                    unlink( $caminho . $arquivo);
                    return true;
                }
            }
        }
        return false;
    }
    
}