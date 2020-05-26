<?php
/*
Ultima modificacion: 17.05.07
Autor: Ariel Balmaceda.
Compatible con PHP 5.
*/

class Subir_Archivo Extends Archivos {
	//Propiedades
	protected $error; //tipo: string
	protected $extensiones_validas; //tipo: string
	protected $ruta_destino; //tipo: string
	protected $nom_campo; //tipo: string
	protected $crea_ruta_destino; //tipo boolean
	protected $nom_destino; //tipo string
	protected $nuevo_nombre; //tipo string
	protected $tamanio_archivo; //tipo integer - valor en bytes
	protected $indice; //tipo integer

	//
	function __construct(){
		$this->error = "";
		$this->extensiones_validas = "*";
		$this->ruta_destino = "./";
		$this->nom_campo = "";
		$this->crea_ruta_destino = true;
		$this->nom_destino = "";
		$this->nuevo_nombre = "";
		$this->tamanio_archivo = 0;
		$this->indice = -1;
	}
	
	// metodos privados
	protected function _validarArchivo(){
		$this->error = "";
		
		if (trim($this->nom_campo) == ""){
			$this->error = "NO se ingreso el nombre del campo tipo file para Procesar";
			return;
		}
		
		if (!isset($_FILES[$this->nom_campo])){
			$this->error = "NO se existe el campo tipo file con nombre " .
			$this->nom_campo;
			return;
		}
		
		$campo_name = ($this->indice >= 0)?
		$_FILES[$this->nom_campo]['name'][$this->indice]:
		$_FILES[$this->nom_campo]['name'];

		if (trim($campo_name) == ""){
			$this->error = "NO se ha seleccionado un archivo a subir";
			return;
		}
		
		$campo_size = ($this->indice >= 0)?
		$_FILES[$this->nom_campo]['size'][$this->indice]:
		$_FILES[$this->nom_campo]['size'];

		if ($this->tamanio_archivo > 0){
			if ($campo_size < 1){
				$this->error = "El tama&ntilde;o del archivo '" . 
				$campo_name . 
				"' NO se pudo comprobar. El archivo NO se procesa";
				
				return;
			}else{
				if ($campo_size > $this->tamanio_archivo){
					$this->error = "El archivo '" . $campo_name . 
					"' tiene un tama&ntilde;o mayor al permitido (" . 
					$this->convertirTamanio($this->tamanio_archivo) . 
					" m&aacute;ximo). El archivo NO se procesa"; 
					
					return;
				}
			}
		}
		
		if ($this->extensiones_validas != "*"){
			$partes_nom_arch = explode(".", $campo_name);
			
			$extarch = $partes_nom_arch[count($partes_nom_arch) - 1];
			
			$extensiones = explode(",", $this->extensiones_validas);
			
			if (!in_array($extarch, $extensiones)){
				$this->error = "El archivo '" . $campo_name .
				"' NO tiene una extension valida (" . $this->extensiones_validas . ")";
				return;
			}
		}
	}

	// metodos publicos
	public function setExtensionesValidas($extension){
		if (trim($extension) != ""){
			$this->extensiones_validas = str_replace(" ", "", $extension);
			
			$this->extensiones_validas = str_replace(".", "",
			$this->extensiones_validas);
			
			$this->extensiones_validas = strtolower($this->extensiones_validas);
		}
	}
	
	public function getExtensionesValidas(){
		return $this->extensiones_validas;
	}
	
	public function setRutaDestino($destino){
		if (trim($destino) != "") $this->ruta_destino = $destino;
		
		$this->ruta_destino = str_replace("\\", "/", $this->ruta_destino);
		
		if (substr($this->ruta_destino, -1) != "/") $this->ruta_destino .= "/";
		
		if (!file_exists($this->ruta_destino)){
			if ($this->crea_ruta_destino){
				if (!mkdir($this->ruta_destino, 0777)){
					$this->error = "Error al crear el directorio destino ".
					$this->ruta_destino;
					
					$this->ruta_destino = "./";
				}
			}else{
				$this->error = "El directorio destino " . $this->ruta_destino . 
				" NO existe !!.";
				
				$this->ruta_destino = "./";
			}
		}
	}
	
	public function getRutaDestino(){
		return $this->ruta_destino;
	}
	
	public function setNombreCampo($nomcampo){
		if (trim($nomcampo) != "") $this->nom_campo = trim($nomcampo);
	}
	
	public function getNombreCampo(){
		return $this->nom_campo;
	}
	
	public function setNuevoNombre($nombre){
		//if (trim($nombre) != ""){
			$this->nuevo_nombre = $this->parsearTexto(trim($nombre));
		//}
	}
	
	public function setCreaRutaDestino($opcion){
		$condicion=($opcion == true) || ($opcion == 1);
		
		$condicion = $condicion || (($opcion == false) || ($opcion == 0));
		
		if ($condicion) $this->crea_ruta_destino=$opcion;
	}
	
	public function getCreaRutaDestino($opcion){
		return $this->crea_ruta_destino;
	}
	
	public function setTamanioArchivo($valor){
		if ($valor > 0) $this->tamanio_archivo = $valor; 
	}
	
	public function getTamanioArchivo(){
		return $this->tamanio_archivo; 
	}
	
	public function getNombreArchivoOriginal(){
		$campo_name = ($this->indice >= 0)?
		$_FILES[$this->nom_campo]['name'][$this->indice]:
		$_FILES[$this->nom_campo]['name'];

		return $campo_name;
	}
	
	public function getNombreArchivoSubido(){
		return $this->nom_destino;
	}
	
	public function getError(){
		return $this->error;
	}
	
	public function setIndice($valor){
		if ($valor >= 0) $this->indice = $valor; 
	}
	
	public function getIndice(){
		return $this->indice; 
	}

	public function procesarArchivo(){
		$this->_validarArchivo();
		
		if (trim($this->error) != "") return;
		
		$campo_name = ($this->indice >= 0)?
		$_FILES[$this->nom_campo]['name'][$this->indice]:
		$_FILES[$this->nom_campo]['name'];

		$partes_nom_arch = explode(".", $campo_name);
		
		$solo_ext = $partes_nom_arch[count($partes_nom_arch) - 1];
		
		if (trim($this->nuevo_nombre) == ""){
			$this->nom_destino = $this->parsearTexto($campo_name);
		}else{
			$this->nom_destino = $this->nuevo_nombre . "." . $solo_ext;
		}
		
		//reviso si el nombre del archivo ya existe en el destino
		//, si existe, renombro el archivo nuevo
		$partes_nom_arch = explode(".", $this->nom_destino);
		
		$solo_nom = '';
		
		foreach ($partes_nom_arch as $ind => $valor){
			if ($ind == (count($partes_nom_arch) -1)) break;
			
			if ($solo_nom != '') $solo_nom .= '.';
			
			$solo_nom .= $valor;
		}
		
		$ind = 1;
		$xnombre = $this->nom_destino;
		
		while (file_exists($this->ruta_destino . $xnombre)){
			$xnombre = $solo_nom . "-" . $ind . "." . $solo_ext;
			
			$ind++;
		}
		
		$this->nom_destino = $xnombre;
		
		$campo_tmp_name = ($this->indice >= 0)?
		$_FILES[$this->nom_campo]['tmp_name'][$this->indice]:
		$_FILES[$this->nom_campo]['tmp_name'];

		if (function_exists("move_uploaded_file")){
			$retorno = move_uploaded_file($campo_tmp_name,
			($this->ruta_destino . $this->nom_destino));
			
			if ($retorno === false) $this->error = "(1) Error al enviar el archivo " .
			$campo_name; 
		}else{
			if (is_uploaded_file($campo_tmp_name)){
				$retorno = copy($campo_tmp_name,
				$this->rutadestino . $this->nom_destino);
				
				if ($retorno === false) $this->error = "(2) Error al enviar el archivo " .
				$campo_name; 
			}else{
				$this->error = "Error al enviar el archivo " . $campo_name . 
				". Posible ataque externo !!";
			}
		}
	}
}//fin clase
?>