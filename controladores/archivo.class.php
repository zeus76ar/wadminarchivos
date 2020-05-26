<?php
class Archivo extends Controlador{
	function __construct(){
		parent::__construct();
	}
	
	//
	public function upload(){
		$clases=array();

		$clases[]='textos.class.php';
		$clases[]='archivos.class.php';
		$clases[]='subir_archivo.class.php';

		$this->_cargarModelosBase($clases);
		
		//
		$osa = new Subir_Archivo();
		
		$retorno=array();
		$retorno['error']='';
		$retorno['info']='';
		
		//$osa->setExtensionesValidas('');
		//$osa->setCreaRutaDestino(false);
		$osa->setRutaDestino($_POST["hdir_upload"]);
		$osa->setNombreCampo('archivo');
		
		$osa->procesarArchivo();
		
		if ($osa->getError() != "") $retorno["error"] = $osa->getError();
		
		echo json_encode($retorno);
	}
	
	public function download(){
		$clases=array();
		
		$clases[] = 'forzar_descarga.class.php';

		$this->_cargarModelosBase($clases);
		
		//
		$adescargar = '';
		$retorno = array();
		
		$retorno['error'] = '';
		$retorno['info'] = '';
		
		$ofd = new ForzarDescarga();
		$ofd->setDirDescarga(dirname($_POST["harchbajar"]));
		
		$ofd->descargarArchivo(basename($_POST["harchbajar"]));
		
		if ($ofd->getError() != "") $retorno["error"] = $ofd->getError();
		
		echo json_encode($retorno);
	}
	
	public function extraer(){
		$clases=array();

		$clases[]='textos.class.php';
		$clases[]='archivos.class.php';

		$this->_cargarModelosBase($clases);
		
		//
		$retorno=array();
		$retorno['error']='';
		$retorno['info']='';
		
		$oa = new Archivos();
		
		$oa->extraerTarGzip($_POST["harchextraer"]);
		
		if ($oa->getError() != "") $retorno["error"] = $oa->getError();
		
		echo json_encode($retorno);
	}//fin function
	
	public function nuevo_arch(){
		$clases=array();

		$clases[]='textos.class.php';
		$clases[]='archivos.class.php';

		$this->_cargarModelosBase($clases);
		
		//
		$oa = new Archivos();
		
		$retorno=array();
		$retorno['error']='';
		$retorno['info']='';
		
		//
		$objetivo = $_POST["hdir"] . ((substr($_POST["hdir"], -1) != '/')?'/':'') .
		$_POST["hnuevodir"];
		
		/*
		$oa->guardarArchivo('', $objetivo, 'nuevo');
		
		if ($oa->getError() != '') $retorno["error"] = $oa->getError();
		*/
		if (!touch($objetivo)) $retorno['error'] = 'Error al crear el archivo ' . $_POST["hnuevodir"];
		
		echo json_encode($retorno);
	}
	
	public function modif_arch(){
		$clases=array();

		$clases[]='textos.class.php';
		$clases[]='archivos.class.php';

		$this->_cargarModelosBase($clases);
	
		//
		$oa = new Archivos();
		$texto_error = '--||Error||--';
	
		if ($_POST["hmodifaccion"] == "cargar"){
			//reviso que sea un archivo de texto
			/*
			if (function_exists('finfo_file')){
				$finfo = finfo_open(FILEINFO_MIME_TYPE); // devuelve el tipo mime de su extensión
				$tipo_arch = finfo_file($finfo, $_POST["hrutaarch"]);
				finfo_close($finfo);
			}elseif (function_exists('mime_content_type')){
				$tipo_arch = mime_content_type($_POST["hrutaarch"]);
			}else{
				echo $texto_error . 'El tipo de archivo No se pudo determinar';
				return;
			}
			*/
			
			//if (substr($tipo_arch, 0, 5) == "text/"){
				$contenido = $oa->leerArchivo($_POST["hrutaarch"]);
				
				if ($oa->getError() === ''){
					echo $contenido;
				}else{
					echo $texto_error . $oa->getError();
				}
			//}else{
			//	echo $texto_error . 'El archivo No es de tipo texto';
			//}
		}else{
			//guardar
			$retorno=array();
			$retorno['error']='';
			$retorno['info']='';
			
			//
			$oa->guardarArchivo($_POST["tarchivo"], $_POST["hrutaarch"], 'nuevo');
			
			if ($oa->getError() != '') $retorno["error"] = $oa->getError();
			
			echo json_encode($retorno);
		}
	}
	
	public function comprimir_zip(){
		$clases=array();

		$clases[]='textos.class.php';
		$clases[]='archivos.class.php';

		$this->_cargarModelosBase($clases);
		
		//
		$oa = new Archivos();
		
		$arch_json = array();
		$cont_errores = 0;
		$acomprimir = array();
		$nomzip = "";
		
		$retorno=array();
		$retorno['error']='';
		$retorno['info']='';
		
		if (trim($_POST["harchselec"]) != "") $arch_json = json_decode($_POST["harchselec"]);
		
		//
		if (count($arch_json) > 0){
			foreach ($arch_json as $ind=>$valor){
				if (trim($valor) == "") continue;
				
				$acomprimir[] = $valor;
			}
		}
		
		$nomzip = ($_POST["hdir"] . ((substr($_POST["hdir"], -1) != '/')?'/':'') .
		$_POST["hnomcomprimir"]);
		
		$oa->comprimirZip($acomprimir, $nomzip);
		
		if ($oa->getError() != "") $retorno["error"] = $oa->getError();
		
		echo json_encode($retorno);
	}
	
	public function extraer_zip(){
		$clases=array();

		$clases[]='textos.class.php';
		$clases[]='archivos.class.php';

		$this->_cargarModelosBase($clases);
		
		//
		$retorno=array();
		$retorno['error']='';
		$retorno['info']='';
		
		$oa = new Archivos();
		
		$oa->extraerZip($_POST["harchextraer"]);
		
		if ($oa->getError() != "") $retorno["error"] = $oa->getError();
		
		echo json_encode($retorno);
	}//fin function
}//fin class
?>