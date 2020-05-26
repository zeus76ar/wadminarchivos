<?php
/*
version: 16.10.05
Autor: Ariel Balmaceda.
Compatible con PHP 5
*/

class Url{
    protected function _cargarModulos($config){
        $retorno=array();
        
        $destino=($config["rutabase"].$config["dircontrol"]);
        $entradas=scandir($destino);
        
        foreach ($entradas as $ind=>$entrada){
        	if (($entrada == '.') || ($entrada == '..') ||
        	($entrada == 'base')) continue;
        	
        	if (is_dir($destino.$entrada)) $retorno[]=$entrada; 
        }
        
        return $retorno;
    }//fin function
    
    public function redireccionar($destino, $tiempo=0){
		$protocolo='http';
		
		if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') $protocolo='https';

        $puerto=($_SERVER['SERVER_PORT'] != 80)?(":".$_SERVER['SERVER_PORT']):"";
        
        if (substr($destino, 0, 1) != "/") $destino=("/".$destino);
        
        header('Refresh: ' . $tiempo . ';URL=' . $protocolo . '://' . $_SERVER['SERVER_NAME'] .
		$puerto . dirname($_SERVER['PHP_SELF']) . $destino);
    }//fin function
    
    public function generarUrlMenu($opciones, $config){
        /*
        Funcion que se encarga de generar las url de los menus
        para url amigables o para el formato estandar.
        $opciones: array con los parametos $_GET. Ej: $opciones["c"]="datos", $opciones["a"]="buscar", ...
        IMPORTANTE: en el caso de url_amigables, pasar primero los indices "c", "a", ...
        $config: array con los valores de configuracion generales.
        */
        $retorno="";
        
        if ($config["url_amigables"]){
            if (!isset($config["url_incluir_index"])) $config["url_incluir_index"]=0;
    
            if ($config["url_incluir_index"] == 1) $retorno='index.php';
            
            foreach ($opciones as $ind=>$opcion){
                if ($retorno != "") $retorno.="/";
                
                $retorno.=$opcion;
            }//fin foreach
        }else{
            $inicio_url="index.php?";
            $retorno=$inicio_url;
            
            foreach ($opciones as $ind=>$opcion){
                if ($retorno != $inicio_url) $retorno.="&";
                
                $retorno.=($ind."=".$opcion);
            }//fin foreach
        }//fin if
        
        return $retorno;
    }//fin function
    
    public function revisarUrl($config, $rutas=array()){
        $retorno=array();
        
        $modulos=$this->_cargarModulos($config);
    
        //if (!isset($_GET["ajax"])){
        //	if ($config["url_amigables"]){
        if ((strpos($_SERVER['REQUEST_URI'], 'index.php?') === false) &&
        (strpos($_SERVER["PHP_SELF"], 'index.php?') === false)){
			//// transformo la url en variables GET
			$pos_index=strpos($_SERVER['REQUEST_URI'], 'index.php');
			
			if ($pos_index !== false){
				$aux=substr($_SERVER['REQUEST_URI'], ($pos_index + strlen('index.php/')));
			}else{
				$aux=str_replace("index.php", "", $_SERVER["PHP_SELF"]);
				$aux=str_replace($aux, "", $_SERVER['REQUEST_URI']);
			}
			
			//reviso las rutas definidas
			if (count($rutas) > 0){
				if (isset($rutas[$aux])) $aux=$rutas[$aux];
			}
			
			$retorno=explode('/', $aux);
			
			if (count($modulos) > 0){
				$_GET["m"]=(count($retorno) > 0)?$retorno[0]:$config['m_base'];
				$_GET["c"]=(count($retorno) > 1)?$retorno[1]:$config['c_base'];
				$_GET["a"]=(count($retorno) > 2)?$retorno[2]:$config['a_base'];
			}else{
				$_GET["c"]=(count($retorno) > 0)?$retorno[0]:$config['c_base'];
				$_GET["a"]=(count($retorno) > 1)?$retorno[1]:$config['a_base'];
			}
        }
            //}//fin if
        //}//fin if
        
        if (count($modulos) > 0){
            if (!isset($_GET["m"]) || (trim($_GET["m"]) == "")) $_GET["m"]=$config['m_base'];
            
            //reviso el modulo
            if (!in_array($_GET["m"], $modulos)){
                // arreglo las variables $_GET
                $_GET["a"]=$_GET["c"];
                $_GET["c"]=$_GET["m"];
                $_GET["m"]=$config['m_base'];
                
                // arreglo la variable $retorno
                for ($ind=count($retorno); $ind > 0; $ind--){
                    $retorno[$ind]=$retorno[($ind - 1)];
                }
                
                $retorno[0]=$config['m_base'];
            }
        }
        
        if (!isset($_GET["c"]) || (trim($_GET["c"]) == "")) $_GET["c"]=$config['c_base'];
        if (!isset($_GET["a"]) || (trim($_GET["a"]) == "")) $_GET["a"]=$config['a_base'];
        
        if (isset($_GET["m"])) $_GET["m"]=strtolower(trim($_GET["m"]));
        $_GET["c"]=strtolower(trim($_GET["c"]));
        $_GET["a"]=strtolower(trim($_GET["a"]));
        
        return $retorno;
    }//fin function
	
	public function convertirPathaUrl($ruta){
		// ejemplo:
		// $ruta = /home/ariel/public_html/php/pruebas/imagenes/tempo/archivo.jpg
		
		$nueva_ruta = $ruta;
		$doc_root = str_replace('\\', '/', $_SERVER["DOCUMENT_ROOT"]);
		
		if (stripos($ruta, $doc_root) === false){
			//busco el contexto
			if (isset($_SERVER["CONTEXT_DOCUMENT_ROOT"])){
				if (stripos($ruta, $_SERVER["CONTEXT_DOCUMENT_ROOT"]) !== false){
					$nueva_ruta = str_replace($_SERVER["CONTEXT_DOCUMENT_ROOT"], '', $ruta);
					$nueva_ruta = $_SERVER["CONTEXT_PREFIX"] . $nueva_ruta;
				}
			}
		}else{
			$nueva_ruta = str_replace($_SERVER["DOCUMENT_ROOT"], '', $ruta);
		}
		
		$protocolo = 'http';
		if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') $protocolo = 'https';
		
		$retorno = $protocolo . "://" . $_SERVER["HTTP_HOST"] . "/" . $nueva_ruta;
		
		return $retorno;
	}
}
?>