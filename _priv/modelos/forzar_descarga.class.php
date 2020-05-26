<?php
/*
Ultima modificacion: 12/12/2014.
Autor: http://wordpresstutoriales.com/forzar-descargas-de-archivos-con-php/
Adaptado por: Ariel Balmaceda.
Compatible con PHP 5.
*/

class ForzarDescarga {
    //Propiedades
    protected $error;//tipo: string
    protected $dir_descarga;//tipo: string
    protected $tipo_archivo;//tipo: string
    
    //contructor
    function __construct(){
        $this->error="";
        $this->dir_descarga="";
        $this->tipo_archivo="";
    }
    
    public function getError(){
        return $this->error;
    }
    
    public function setDirDescarga($directorio){
        $directorio=trim($directorio);
        $this->error="";
        
        //valido el valor que se quiere asignar
        if ($directorio == ""){
            $this->error="El directorio de descarga No puede quedar en blanco.";
            return;
        }
        
        if (!is_dir($directorio)){
            $this->error="El directorio ".$directorio." No es valido.";
            return;
        }
        
        if (substr($directorio, -1) != "/") $directorio.="/";
        
        $this->dir_descarga=$directorio;
    }
    
    public function getDirDescarga(){
        return $this->dir_descarga;
    }
    
    public function setTipoArchivo($tipo){
        $tipo=trim($tipo);
        $this->error="";
        
        //valido el valor que se quiere asignar
        if (tipo == ""){
            $this->error="El tipo de archivo No puede quedar en blanco.";
            return;
        }
        
        $this->tipo_archivo=$tipo;
    }
    
    public function getTipoArchivo(){
        return $this->tipo_archivo;
    }
    
    public function descargarArchivo($archivo){
        $archivo=trim($archivo);
        $this->error="";
        
        //valido el archivo que se pasa como parametro
        if ($archivo == ""){
            $this->error="El nombre del archivo a descargar No puede quedar en blanco.";
            return;
        }
        
        //valido que el directorio de descarga este configurado
        if ($this->dir_descarga == ""){
            $this->error="El directorio de descarga No puede quedar en blanco.";
            return;
        }
        
        $ruta_completa=$this->dir_descarga.$archivo;
        
        if (!is_file($ruta_completa)){
            $this->error="El archivo ".$ruta_completa." No es valido.";
            return;
        }
        
        $tamanio = filesize($ruta_completa);
        
        if (function_exists('mime_content_type')) {
            $this->tipo_archivo = mime_content_type($ruta_completa);
        }elseif (function_exists('finfo_file')) {
            $info = finfo_open(FILEINFO_MIME);
            
            $this->tipo_archivo = finfo_file($info, $ruta_completa);
            
            finfo_close($info);
        }
        
        if ($this->tipo_archivo == "") $this->tipo_archivo = "application/force-download";
        
        // Definir headers
        header("Content-Type: ".$this->tipo_archivo);
        header("Content-Disposition: attachment; filename=".basename($ruta_completa));
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: " . $tamanio);
        
        // Descargar archivo
        readfile($ruta_completa);
    }
}//fin clase
?>