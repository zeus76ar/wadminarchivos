<?php
class Directorio extends Controlador{
	protected $directorios;
	protected $archivos;
	protected $archivos_imagenes;
	
	function __construct(){
		parent::__construct();
		
		$this->directorios = array();
		$this->archivos = array();
		$this->archivos_imagenes = array();
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
    
	protected function _convertirUrlWindows($url){
		$retorno = $url;
		
		if (stripos($retorno, '/') === false){
			$retorno = str_replace('\\', '/', $retorno);
			
			if (stripos($retorno, '/') === false){
				$retorno = str_replace(':', ':/', $retorno);
				$retorno = str_replace('inetpub', 'inetpub/', $retorno);
				$retorno = str_replace('wwwroot', 'wwwroot/', $retorno);
			}
		}
		
		return $retorno;
	}
	
	//
	public function explorar(){
		$clases=array();

		$clases[]='textos.class.php';
		$clases[]='archivos.class.php';

		$this->_cargarModelosBase($clases);
		
		//
		$ou = new Url();
		$oa = new Archivos();
		
		$destino = "..";
		
		if (trim($_POST["hdir"]) != '') $destino = $_POST["hdir"];
		
		$this->directorios = $oa->listarDirectorio($destino, 'asc', '*', 'dir');
		$this->archivos = $oa->listarDirectorio($destino, 'asc', '*', 'arch');
		
		if (count($this->archivos) > 0){
			for ($i = 0; $i < count($this->archivos); $i++){
				if ($this->_esImagen($this->archivos[$i])){
					$dir_arch = $this->_convertirUrlWindows(realpath($destino));
					
					if (substr($dir_arch, -1) != "/") $dir_arch .= "/";
					
					$dir_arch .= $this->archivos[$i];
					
					$this->archivos_imagenes[$i] = $ou->convertirPathaUrl($dir_arch);
				}else{
					$this->archivos_imagenes[$i] = '';
				}
			}
		}
		
		$dir_programa = dirname($_SERVER["SCRIPT_FILENAME"]);
		
		if (stripos($dir_programa, '/') === false){
			$dir_programa = str_replace('\\', '/', $dir_programa);
		}
		
		$partes = explode("/", $dir_programa);
		
		if ((count($this->directorios) > 0) && (count($partes) > 0)){
			for ($i = 0; $i < count($this->directorios); $i++){
				if ($this->directorios[$i] == $partes[(count($partes) - 1)]){
					$this->directorios[$i] = '';
					break;
				}
			}
		}
		
		//cargo las vistas
		$this->vista["dir_actual"] = $this->_convertirUrlWindows(realpath($destino));
		
		$vista="html/vstlistado.phtml";

		$this->generarVista($vista);
	}//fin function
	
	public function nuevodir(){
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
		
		$oa->crearDirectorio($objetivo, 0755);
		
		if ($oa->getError() != '') $retorno["error"] = $oa->getError();
		
		echo json_encode($retorno);
	}
}//fin class
?>