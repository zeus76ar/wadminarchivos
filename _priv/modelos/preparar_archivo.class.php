<?php
/*
version: 15.04.02
Autor: Ariel Balmaceda.
Compatible con PHP 5
*/

class PrepararArchivo{
    protected $error_prep;//boolean
	protected $archivo_prep;//string
  
	function __construct(){
		$this->error_prep=false;
		$this->archivo_prep='';
	}
    
    public function getErrorPreparar(){
        return $this->error_prep;
    }
    
    public function getArchivoPreparar(){
        return $this->archivo_prep;
    }
    
    public function prepararControlador($config, $controlador){
        $destino=($config["rutabase"].$config["dircontrol"].$controlador.".class.php");
        
        $this->error_prep=false;
        $this->archivo_prep='';
        
        if (file_exists($destino)){
            $this->archivo_prep=$destino;
        }else{
            $this->error_prep=true;
        }
    }//fin function

    public function prepararVista($config, $vista="vstmaestra.phtml"){
        $destino=($config["rutabase"].$config["dirvista"].$vista);
        
        $this->error_prep=false;
        $this->archivo_prep='';
        
        if (file_exists($destino)){
            $this->archivo_prep=$destino;
        }else{
            $this->error_prep=true;
        }
    }//fin function

    public function prepararModeloBase($config, $modelobase){
        $destino=($config["rutabase"].$config["dirmodelo_base"].$modelobase);
        
        $this->error_prep=false;
        $this->archivo_prep='';
        
        if (file_exists($destino)){
            $this->archivo_prep=$destino;
        }else{
            $this->error_prep=true;
        }
    }//fin function

    public function prepararModelo($config, $modelo){
        $destino=($config["rutabase"].$config["dirmodelo"].$modelo);
        
        $this->error_prep=false;
        $this->archivo_prep='';
        
        if (file_exists($destino)){
            $this->archivo_prep=$destino;
        }else{
            $this->error_prep=true;
        }
    }//fin function

    public function prepararConfig($config, $opcion){
        $destino=($config["rutabase"]."/config/".$opcion);
        
        $this->error_prep=false;
        $this->archivo_prep='';
        
        if (file_exists($destino)){
            $this->archivo_prep=$destino;
        }else{
            $this->error_prep=true;
        }
    }//fin function
	
	public function prepararExtra($config, $extra){
        $destino=($config["rutabase"].$config["dirextras"].$extra);
        
        $this->error_prep=false;
        $this->archivo_prep='';
        
        if (file_exists($destino)){
            $this->archivo_prep=$destino;
        }else{
            $this->error_prep=true;
        }
    }
}
?>