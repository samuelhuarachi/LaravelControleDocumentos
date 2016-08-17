<?php

namespace documentos;

use Illuminate\Database\Eloquent\Model;

class Diretorio extends Model
{
    
    public function deleteDirectory($dirPath) {
    	if (is_dir($dirPath)) {
	        $objects = scandir($dirPath);
	        foreach ($objects as $object) {
	            if ($object != "." && $object !="..") {
	                if (filetype($dirPath . DIRECTORY_SEPARATOR . $object) == "dir") {
	                    deleteDirectory($dirPath . DIRECTORY_SEPARATOR . $object);
	                } else {
	                    unlink($dirPath . DIRECTORY_SEPARATOR . $object);
	                }
	            }
	        }
		    reset($objects);
		    rmdir($dirPath);
	    }
    }
	  
}
