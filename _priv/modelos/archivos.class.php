<?php
/*
version: 16.08.16
Autor: Ariel Balmaceda.
Compatible con PHP 5
*/

class Archivos Extends Textos{
	protected $error;//tipo string
	protected $parsear_nom_archdir;
	protected $nom_sistema;
	
	function __construct(){
		$this->error="";
		$this->parsear_nom_archdir=false;
		$this->nom_sistema = 'linux';
		
		if (stripos(php_uname('s'), 'windows') !== false) $this->nom_sistema = 'windows';
	}
	
	public function getError(){
		return $this->error;
	}
	
	public function setParsearNombreArchDir($opcion){
		$this->parsear_nom_archdir=$opcion;
	}
	
	public function getParsearNombreArchDir(){
		return $this->parsear_nom_archdir;
	}
	
	public function leerArchivo($nomarchivo){
		$this->error="";
		
		if (!file_exists($nomarchivo)){
			$this->error="El archivo ".$nomarchivo." NO existe.";
			return;
		}
		
		$retorno="";
		
		if (function_exists("file_get_contents")){
			$retorno=file_get_contents($nomarchivo);
		}else{
			$lineas = file($nomarchivo);
			
			if ($lineas===false){
				$this->error="Hubo un error al Abrir el archivo ".$nomarchivo;
				return;
			}
			
			foreach ($lineas as $linea_num => $linea) {
				$retorno.=$linea;
			}
		}
		
		return $retorno;
	}
	
	public function guardarArchivo($contenido, $nomarchivo, $tipo="nuevo"){
		//$tipo: "agregar", "nuevo""
		$this->error="";
		
		if ($this->parsear_nom_archdir) $nomarchivo=$this->parsearTexto($nomarchivo);
		
		if (function_exists("file_put_contents")){
			if ($tipo == "agregar"){
				$retorno=file_put_contents($nomarchivo, $contenido, FILE_APPEND);
			}else{
				$retorno=file_put_contents($nomarchivo, $contenido);
			}
			
			if ($retorno === false) $this->error="Hubo un error al guardar el archivo".$nomarchivo;
		}else{
			if ($tipo=="agregar"){
				if (!is_writable($nomarchivo)){
					$this->error="El archivo ".$nomarchivo.
					" No se puede modificar porque No se lo puede escribir.";
					return;
				}
				
				$modoap="a";
			}else{
				$modoap="w";
			}
			
			if (!$gestor = fopen($nomarchivo, $modoap)) {
				$this->error="NO se puede abrir el archivo ".$nomarchivo;
				return;
			}
			
			if (fwrite($gestor, $contenido) === false) {
				$this->error="Hubo un error al guardar el archivo ".$nomarchivo;
				return;
			}
			
			fclose($gestor);
		}
	}
	
	public function listarDirectorio($directorio, $orden="asc",  $filtro="*", $tiporet="arch"){
		/*
		este metodo permite explorar un directorio y retorna su contenido en un arreglo.
		parametros:
		$directorio: ruta y nombre del directorio a explorar
		$orden: 'asc'- ascendente y 'desc'-descendente
		$filtro= extension de los archivos validos (ej: doc, txt, exe). '*' para devolver todos.
		$tiporet='arch' para devolver solo archivos, 'dir' para para devolver solo directorios o '*' para todos.
		*/
		
		$this->error="";
		
		if (trim($directorio) == ""){
			$this->error="Falta el directorio a explorar !!";
			return;
		}elseif (!is_dir($directorio)){
			$this->error=$directorio." No es un directorio valido !!";
			return;
		}elseif (!file_exists($directorio)){
			$this->error="El directorio ".$directorio." No existe !!";
			return;
		}
		
		if (substr($directorio, -1) != "/") $directorio.="/";
		
		$orden=strtolower($orden);
		$filtro=strtolower($filtro);
		$tiporet=strtolower($tiporet);
		
		$archivos=array();
		$tempo=array();
		
		if (function_exists('scandir')){
			$tempo=scandir($directorio, (($orden == "asc")?0:1));
			
			if ($tempo === false){
				$this->error="No se pudo acceder al directorio ".$directorio." !!";
				return;
			}
		}else{
			$dh  = opendir($directorio);
			
			if (!$dh){
				$this->error="No se pudo acceder al directorio ".$directorio." !!";
				return;
			}
			
			$nom_arch = readdir($dh);
			
			while ($nom_arch !== false) {
				$tempo[] = $nom_arch;
				
				$nom_arch = readdir($dh);
			}
			
			if ($orden == "desc"){
				rsort($tempo);
			}else{
				sort($tempo);
			}
		}
		
		// proceso los archivos
		foreach ($tempo as $ind=>$valor){
			if (($valor == ".") || ($valor == "..")) continue;
			
			if ($tiporet == "arch"){
				if (is_dir($directorio.$valor)) continue;
			}elseif ($tiporet == "dir"){
				if (!is_dir($directorio.$valor)) continue;
			}
			
			if ($filtro != "*"){
				if (!is_dir($directorio.$valor)){
					$filtro=str_replace(".", "", $filtro);
					$filtro=str_replace(", ", ",", $filtro);
					$filtro=str_replace(" ,", ",", $filtro);
					
					$extensiones=explode(",", $filtro);
					
					$partes_nom_arch=explode(".", $valor);
					
					$extarch=$partes_nom_arch[count($partes_nom_arch) - 1];
					
					if (!in_array($extarch, $extensiones)) continue;
				}
			}
		
			$archivos[]=$valor;
		}
		
		return $archivos;
	}
	
	public function eliminarArchivoDirectorio($objetivo){
		$this->error="";
		
		if (!file_exists($objetivo)){
			$this->error=$objetivo." NO existe. NO se puede eliminar!!";
			return;
		}
		
		if (is_dir($objetivo)){
			if (!rmdir($objetivo)) $this->error="Error al eliminar ".$objetivo;
		}else{
			if (!unlink($objetivo)) $this->error="Error al eliminar ".$objetivo;
		}
	}
	
	public function crearDirectorio($objetivo, $permisos=0755){
		$this->error="";
		
		if ($this->parsear_nom_archdir) $objetivo=$this->parsearTexto($objetivo);
		
		if (file_exists($objetivo)){
			$this->error=$objetivo." Ya existe. NO se puede crear!!";
			return;
		}
		
		if (!mkdir($objetivo, $permisos)) $this->error="Error al crear el directorio ".$objetivo;
	}
	
	public function verRutaAbsoluta($ruta){
		$this->error='';
		$rutaabs=realpath($ruta);
		
		if ($rutaabs == false){
			$this->error="No se pudo comprobar la ruta ".$ruta;
			return "";
		}
		
		return str_replace("\\", "/", $rutaabs);
	}
	
	public function convertirTamanio($tamanio){
		//$tamanio: tamaño del archivos en bytes.
		$cont = 0;
		$retorno = $tamanio;
		$coeficiente = 1024;
		
		while ($retorno >= $coeficiente){
			$retorno = ($retorno / $coeficiente);
			$cont++;
		}
		
		switch ($cont){
			case 0: $medida = " bytes";break;
			case 1: $medida = " Kbytes";break;
			case 2: $medida = " Mbytes";break;
			case 3: $medida = " Gbytes";break;
			case 4: $medida = " Tbytes";break;
		}
		
		return number_format($retorno, 2, ",", ".").$medida;
	}
	
	public function comprimirGZ($nom_archivo, $nivel=5){
		if (($nivel > 9) || ($nivel < 0)){
			$this->error="El nivel de compresion debe ser entre 0 y 9.";
			return;
		}
		
		if ($this->parsear_nom_archdir) $nom_archivo=$this->parsearTexto($nom_archivo);
		
		$fptr = fopen($nom_archivo, "rb");
		if ($fptr === false){
			$this->error="Error al abrir el archivo.";
			return;
		}
		
		$dump = fread($fptr, filesize($nom_archivo));
		if ($dump === false){
			$this->error="Error al leer el archivo.";
			return;
		}
		
		fclose($fptr);
		
		$gzbackupData = gzencode($dump, $nivel);
		if ($gzbackupData === false){
			$this->error="Error al comprimir los datos.";
			return;
		}
		
		$fptr = fopen($nom_archivo . ".gz", "wb");
		if ($fptr === false){
			$this->error="Error al abrir el archivo para escritura.";
			return;
		}
		
		$retorno=fwrite($fptr, $gzbackupData);
		if ($retorno === false){
			$this->error="Error al guardar el archivo.";
			return;
		}
		
		fclose($fptr);
	}
	
	public function calcularNuevoNombre($nom_arch){
		//$nomarch debe incluir ruta y nombre archivo
		$this->error = "";
		$solo_dir = dirname($nom_arch);
		$solo_arch = basename($nom_arch);
		$partes = explode(".", $solo_arch);
		$solo_ext = "";
		
		if (count($partes) > 1) $solo_ext = "." . $partes[(count($partes) - 1)];
		
		$nuevo_nom = str_replace($solo_ext, '', $solo_arch);
		
		if (substr($solo_dir, -1) != "/") $solo_dir .= "/";
		
		$ind=1;
		
		$nuevo_arch = $solo_dir.$nuevo_nom.$solo_ext;
		
		while (file_exists($nuevo_arch)){
			$nuevo_nom = str_replace($solo_ext, '', $solo_arch);
			$nuevo_nom = $nuevo_nom . "_" . $ind;
			
			$nuevo_arch = $solo_dir . $nuevo_nom . $solo_ext;
			$ind++;
		}
		
		return $nuevo_arch;
	}
	
	public function copiar($origen, $destino){
		//$origen="/www/php/algo/archivo.txt"
		//destino="/www/php/otro"
		//--
		//$origen="/www/php/algo"
		//destino="/www/php/otro"
		
		$this->error = "";
		
		$salida_comando = array();
		$retorno_comando = 0;
		$xorigen = str_replace('/', '\\', $origen);
		$xdestino = str_replace('/', '\\', $destino);
		
		if (is_dir($origen)){
			if ($this->nom_sistema == 'linux'){
				$comando = "cp -R ".$origen." ".$destino;
			}elseif($this->nom_sistema == 'windows'){
				$comando = "xcopy ".$xorigen." ".$xdestino." /E/Q/Y";
			}
		}else{
			if ($this->nom_sistema == 'linux'){
				$comando = "cp ".$origen." ".$destino;
			}elseif($this->nom_sistema == 'windows'){
				$comando = "copy /Y ".$xorigen." ".$xdestino;
			}
		}
		
		exec($comando, $salida_comando, $retorno_comando);
		
		if ($retorno_comando > 0) $this->error = "Error al copiar ". $destino;
	}
	
	public function mover($origen, $destino){
		//$origen="/www/php/algo/archivo.txt"
		//destino="/www/php/otro"
		//--
		//$origen="/www/php/algo"
		//destino="/www/php/otro"
		
		$this->error = "";
		
		$salida_comando = array();
		$retorno_comando = 0;
		$xorigen = str_replace('/', '\\', $origen);
		$xdestino = str_replace('/', '\\', $destino);
		
		if ($this->nom_sistema == 'linux'){
			$comando = "mv ".$origen." ".$destino;
		}elseif($this->nom_sistema == 'windows'){
			$comando = "move /Y ".$xorigen." ".$xdestino;
		}
		
		exec($comando, $salida_comando, $retorno_comando);
		
		if ($retorno_comando > 0) $this->error = "Error al mover ". $destino;
	}
	
	public function eliminar($objetivo){
		$this->error = "";
		
		$salida_comando = array();
		$retorno_comando = 0;
		
		if (is_dir($objetivo)){
			if ($this->nom_sistema == 'linux'){
				$comando = "rm -R ".$objetivo;
			}elseif($this->nom_sistema == 'windows'){
				$comando = "rmdir /Q /S ".str_replace('/', '\\', $objetivo);
			}
		}else{
			if ($this->nom_sistema == 'linux'){
				$comando = "rm ".$objetivo;
			}elseif($this->nom_sistema == 'windows'){
				$comando = "del /Q ".str_replace('/', '\\', $objetivo);
			}
		}
		
		exec($comando, $salida_comando, $retorno_comando);
		
		if ($retorno_comando > 0) $this->error = "Error al eliminar ". $objetivo;
	}
	
	public function comprimirTarGzip($arch_dir, $nomtar){
		//$arch_dir: array con los archivos y directorios a comprimir
		//deben incluir la ruta completa
		//$nomtar: el nombre del archivo comprimido
		
		$this->error = "";
		
		$salida_comando = array();
		$cont_errores = 0;
		$ext_tar = ".tar";
		$ext_gz = ".gz";
		
		$nomtar = str_replace($ext_gz, '', $nomtar);
		
		if (substr($nomtar, (strlen($ext_tar) * -1)) != $ext_tar) $nomtar .= $ext_tar;
		
		$dir_ant = getcwd();
		chdir(dirname($nomtar));
		
		$comando = "tar -c -f " . basename($nomtar);
		
		foreach ($arch_dir as $ind=>$valor){
			if ($ind > 0) $comando = "tar -r -f " . basename($nomtar);
			
			$comando .= " -C " . dirname($valor). " " . basename($valor);	
			exec($comando, $salida_comando, $retorno_comando);
			
			if ($retorno_comando > 0) $cont_errores++;
		}
		
		$comando = "gzip -q -S " . $ext_gz . " " . basename($nomtar);
		exec($comando, $salida_comando, $retorno_comando);
		
		if ($retorno_comando > 0) $cont_errores++;
		
		chdir($dir_ant);
		
		if ($cont_errores > 0) $this->error = "Algunos elementos NO se pudieron comprimir";
	}
	
	public function darFormatoPermisos($num_permiso){
		//r=4, w=2, x=1
		//0 a 7
		
		$retorno = '---';
		
		switch ($num_permiso){
		    case 7:
		        $retorno = 'rwx';
		        break;
		    case 6:
		        $retorno = 'rw-';
		        break;
		    case 5:
		        $retorno = 'r-x';
		        break;
		    case 4:
		        $retorno = 'r--';
		        break;
		    case 3:
		        $retorno = '-wx';
		        break;
		    case 2:
		        $retorno = '-w-';
		        break;
		    case 1:
		        $retorno = '--x';
			    break;
		}
		
		return $retorno;
	}
	
	public function informacion($objetivo){
		//$objetivo: archivo o directorio a obtener informacion
		
		$this->error = '';
		$info = array();
		
		if($this->nom_sistema == 'windows'){
			$objetivo = str_replace('/', '\\', $objetivo);
			
			$info["grupo_id"] = -1;
			$info["dueño_id"] = -1;
			$info["dueño_nombre"]='-';
			$info["grupo_nombre"] = '-';
		}elseif ($this->nom_sistema == 'linux'){
			clearstatcache(true, $objetivo);
			$info["grupo_id"] = filegroup($objetivo);
			if ($info["grupo_id"] === false) $info["grupo_id"] = -1;
			
			clearstatcache(true, $objetivo);
			$info["dueño_id"] = fileowner($objetivo);
			if ($info["dueño_id"] === false) $info["dueño_id"] = -1;
			
			$info["dueño_nombre"]='-';
			if ($info["dueño_id"] != -1){
				$datos_propietario = posix_getpwuid($info["dueño_id"]);
				
				if ($datos_propietario !== false) $info["dueño_nombre"] = $datos_propietario["name"];
			}
			
			$info["grupo_nombre"] = '-';
			if ($info["grupo_id"] != -1){
				$datos_grupo = posix_getgrgid($info["grupo_id"]);
				
				if ($datos_grupo !== false) $info["grupo_nombre"] = $datos_grupo["name"];
			}
		}
		
		clearstatcache(true, $objetivo);
		$info["permisos"] = sprintf('%o', fileperms($objetivo));
		
		clearstatcache(true, $objetivo);
		$info["tamaño"] = filesize($objetivo);
		$info["tamaño_formato"] = '';
		if ($info["tamaño"] === false){
			$info["tamaño"] = -1;
		}else{
			$info["tamaño_formato"] = $this->convertirTamanio($info["tamaño"]);;
		}
		
		clearstatcache(true, $objetivo);
		$info["tipo"] = filetype($objetivo);
		$info["tipo_traducido"] = '';
		if ($info["tipo"] === false){
			$info["tipo"] = '';
		}else{
			$info["tipo_traducido"] = ($info["tipo"] == 'dir')?'directorio':'archivo';
		}
		
		clearstatcache(true, $objetivo);
		$info["ult_modificacion"] = filemtime($objetivo);
		if ($info["ult_modificacion"] === false) $info["ult_modificacion"] = -1;
		
		clearstatcache(true, $objetivo);
		$info["ult_acceso"] = fileatime($objetivo);
		if ($info["ult_acceso"] === false) $info["ult_acceso"] = -1;
		
		$info["ult_modificacion_formato"] = '';
		if ($info["ult_modificacion"] != -1){
			$info["ult_modificacion_formato"] = date("d/m/Y H:i:s", $info["ult_modificacion"]);
		}
		
		$info["ult_acceso_formato"] = '';
		if ($info["ult_acceso"] != -1){
			$info["ult_acceso_formato"] = date("d/m/Y H:i:s", $info["ult_acceso"]);
		}
		
		$info["permisos_dueño"] = '';
		$info["permisos_grupo"] = '';
		$info["permisos_otros"] = '';
		
		$info["nombre"] = basename($objetivo);
		
		if (strlen($info["permisos"]) > 1){
			$solo_permisos = substr($info["permisos"], -3);
			
			$permisos_owner = substr($solo_permisos, 0, 1);
			$permisos_group = substr($solo_permisos, 1, 1);
			$permisos_other = substr($solo_permisos, 2, 1);
			
			$info["permisos_dueño"] = $this->darFormatoPermisos($permisos_owner);
			$info["permisos_grupo"] = $this->darFormatoPermisos($permisos_group);
			$info["permisos_otros"] = $this->darFormatoPermisos($permisos_other);
		}
		
		return $info;
	}
	
	public function extraerTarGzip($archtar){
		//$nomtar: el archivo comprimido a extraer
		//debe incluir la ruta completa
		
		$this->error = "";
		
		$salida_comando = array();
		$retorno_comando = 0;
		
		$extension = ".tar.gz";
		
		$solo_dir = dirname($archtar);
		$solo_arch = basename($archtar);
		
		if (substr($archtar, -7) != $extension){
			$this->error = "El archivo " . $solo_arch . "No tiene la extension " .
			$extension . " para procesar";
			return;
		}
		
		$dir_ant = getcwd();
		chdir($solo_dir);
		
		$comando = "tar -x -z -f " . $archtar;
		
		exec($comando, $salida_comando, $retorno_comando);
		
		chdir($dir_ant);
		
		if ($retorno_comando > 0) $this->error = "Error al extraer ". $solo_arch;
	}
	
	public function comprimirZip($archivos, $nomzip){
		//$arch_dir: array con los archivos a comprimir
		//deben incluir la ruta completa
		//$nomzip: el nombre del archivo comprimido
		
		$this->error = "";
		
		if (!function_exists('zip_open')){
			$this->error = "No se encontro la libreria ZIP habilitada";
			return;
		}
		
		$ext_zip = ".zip";
		
		if (substr($nomzip, (strlen($ext_zip) * -1)) != $ext_zip) $nomzip .= $ext_zip;
		
		$nomzip = $this->calcularNuevoNombre($nomzip);
		
		$dir_ant = getcwd();
		chdir(dirname($nomzip));
		
		//
		$zip = new ZipArchive();
		
		if ($zip->open($nomzip, ZipArchive::CREATE) !== TRUE) {
			$this->error = "No se pudo crear el archivo ZIP";
			return;
		}
		
		$cont_errores = 0;
		
		foreach ($archivos as $ind=>$valor){
			if (!is_file($valor)) continue;
			
			if (!$zip->addFile($valor, basename($valor))) $cont_errores++;
		}
		
		$zip->close();
		
		chdir($dir_ant);
		
		if ($cont_errores > 0) $this->error = "Algunos archivos NO se pudieron comprimir";
	}
	
	public function extraerZip($archzip){
		//$nomzip: el archivo comprimido a extraer
		//debe incluir la ruta completa
		
		$this->error = "";
		$extension = ".zip";
		
		if (!function_exists('zip_open')){
			$this->error = "No se encontro la libreria ZIP habilitada";
			return;
		}
		
		$solo_dir = dirname($archzip);
		$solo_arch = basename($archzip);
		
		if (substr($archzip, (strlen($extension) * -1)) != $extension){
			$this->error = "El archivo " . $solo_arch . "No tiene la extension " .
			$extension . " para procesar";
			return;
		}
		
		$dir_ant = getcwd();
		chdir($solo_dir);
		
		$zip = new ZipArchive;
		
		if ($zip->open($archzip) !== TRUE) {
			$this->error = "No se pudo abrir el archivo ZIP";
			return;
		}
		
		if ($zip->extractTo('.') == false) $this->error = "Error al extraer ". $solo_arch;
		
		$zip->close();
		
		chdir($dir_ant);
	}
}//fin clase
?>
