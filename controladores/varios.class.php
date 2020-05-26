<?php
class Varios extends Controlador{
	protected $info;
	
	function __construct(){
		parent::__construct();
		
		$this->info = array();
	}
	
	protected function _esImagen($archivo){
		$ext_imagen=array();
		$ext_imagen[] = "jpg";
		$ext_imagen[] = "jpeg";
		$ext_imagen[] = "png";
		$ext_imagen[] = "gif";
		
		$retorno = false;
		
		foreach ($ext_imagen as $i=>$valor){
			if (substr($archivo, (strlen($valor) * -1)) == $valor){
				$retorno = true;
				break;
			}
		}
		
		return $retorno;
	}
	
	//
	public function pegar(){
		$clases=array();

		$clases[]='textos.class.php';
		$clases[]='archivos.class.php';

		$this->_cargarModelosBase($clases);
		
		//
		$oa = new Archivos();
		
		$arch_json = array();
		$dir_json = array();
		$cont_errores=0;
		
		$retorno=array();
		$retorno['error']='';
		$retorno['info']='';
		
		if (trim($_POST["harchselec"]) != "") $arch_json = json_decode($_POST["harchselec"]);
		
		if (trim($_POST["hdirselec"]) != "") $dir_json = json_decode($_POST["hdirselec"]);
		
		//
		if ($_POST["haccion"] == "copiar"){
			if (count($arch_json) > 0){
				foreach ($arch_json as $ind=>$valor){
					if (trim($valor) == "") continue;
					
					$oa->copiar($valor, $_POST["hdir"]);
					
					if ($oa->getError() != '') $cont_errores++;
				}
			}
			
			if (count($dir_json) > 0){
				foreach ($dir_json as $ind=>$valor){
					if (trim($valor) == "") continue;
					
					$oa->copiar($valor, $_POST["hdir"]);
					
					if ($oa->getError() != '') $cont_errores++;
				}
			}
		}elseif ($_POST["haccion"] == "cortar"){
			if (count($arch_json) > 0){
				foreach ($arch_json as $ind=>$valor){
					if (trim($valor) == "") continue;
					
					$oa->mover($valor, $_POST["hdir"]);
					
					if ($oa->getError() != '') $cont_errores++;
				}
			}
			
			if (count($dir_json) > 0){
				foreach ($dir_json as $ind=>$valor){
					if (trim($valor) == "") continue;
					
					$oa->mover($valor, $_POST["hdir"]);
					
					if ($oa->getError() != '') $cont_errores++;
				}
			}
		}
		
		if ($cont_errores > 0) $retorno["error"] = "Algunos elementos NO se pudieron mover";
		
		echo json_encode($retorno);
	}
	
	public function eliminar(){
		$clases=array();

		$clases[]='textos.class.php';
		$clases[]='archivos.class.php';

		$this->_cargarModelosBase($clases);
		
		//
		$oa = new Archivos();
		
		$arch_json = array();
		$dir_json = array();
		$cont_errores=0;
		
		$retorno=array();
		$retorno['error']='';
		$retorno['info']='';
		
		if (trim($_POST["harchselec"]) != "") $arch_json = json_decode($_POST["harchselec"]);
		
		if (trim($_POST["hdirselec"]) != "") $dir_json = json_decode($_POST["hdirselec"]);
		
		//
		if (count($arch_json) > 0){
			foreach ($arch_json as $ind=>$valor){
				if (trim($valor) == "") continue;
				
				$oa->eliminar($valor);
					
				if ($oa->getError() != '') $cont_errores++;
			}
		}
			
		if (count($dir_json) > 0){
			foreach ($dir_json as $ind=>$valor){
				if (trim($valor) == "") continue;
				
				$oa->eliminar($valor);
				
				if ($oa->getError() != '') $cont_errores++;
			}
		}
		
		if ($cont_errores > 0) $retorno["error"] = "Algunos elementos NO se pudieron eliminar";
		
		echo json_encode($retorno);
	}
	
	public function renombrar(){
		$retorno=array();
		$retorno['error']='';
		$retorno['info']='';
		
		//
		$dir_nuevo = dirname($_POST["hcambiarant"]);
		
		if (substr($dir_nuevo, -1) != '/') $dir_nuevo .= '/';
			
		$nom_nuevo = $dir_nuevo . $_POST["hcambiarnuevo"];
		
		if (!@rename($_POST["hcambiarant"], $nom_nuevo)){
			$retorno['error'] = 'No se pudo renombrar el elemento';
		}
		
		echo json_encode($retorno);
	}
	
	public function comprimir(){
		$clases=array();

		$clases[]='textos.class.php';
		$clases[]='archivos.class.php';

		$this->_cargarModelosBase($clases);
		
		//
		$oa = new Archivos();
		
		$arch_json = array();
		$dir_json = array();
		$cont_errores = 0;
		$acomprimir = array();
		$nomtar = "";
		
		$retorno=array();
		$retorno['error']='';
		$retorno['info']='';
		
		if (trim($_POST["harchselec"]) != "") $arch_json = json_decode($_POST["harchselec"]);
		
		if (trim($_POST["hdirselec"]) != "") $dir_json = json_decode($_POST["hdirselec"]);
		
		//
		if (count($arch_json) > 0){
			foreach ($arch_json as $ind=>$valor){
				if (trim($valor) == "") continue;
				
				$acomprimir[] = $valor;
			}
		}
			
		if (count($dir_json) > 0){
			foreach ($dir_json as $ind=>$valor){
				if (trim($valor) == "") continue;
				
				$acomprimir[] = $valor;
			}
		}
		
		$nomtar = ($_POST["hdir"] . ((substr($_POST["hdir"], -1) != '/')?'/':'') .
		$_POST["hnomcomprimir"]);
		
		$oa->comprimirTarGzip($acomprimir, $nomtar);
		
		if ($oa->getError() != "") $retorno["error"] = $oa->getError();
		
		echo json_encode($retorno);
	}
	
	public function mostrarinfo(){
		$clases=array();

		$clases[]='textos.class.php';
		$clases[]='archivos.class.php';

		$this->_cargarModelosBase($clases);
		
		//
		$oa = new Archivos();
		$ou = new Url();
		
		$this->info = $oa->informacion($_POST["harchbajar"]);
		$this->info["url_imagen"] = '';
		
		if ($this->_esImagen($_POST["harchbajar"])){
			$this->info["url_imagen"] = $ou->convertirPathaUrl($_POST["harchbajar"]);
		}
		
		//cargo las vistas
		$vista="html/vstinfo.phtml";

		$this->generarVista($vista);
	}//fin function
}//fin class
?>
