<?php
class Inicio extends Controlador{
	function __construct(){
		parent::__construct();
	}
	
	//
	public function index(){
		$ou = new Url();
		$param = array();
		
		//cargo las vistas
		$this->vista["titulo"] = "Inicio";
		$this->vista["html"] = "vstexplorador.phtml";
		
		$param["c"] = "directorio";
		$param["a"] = "explorar";
		$this->vista["form_action"] = $ou->generarUrlMenu($param, $this->config);
		
        $param["c"] = "archivo";
		$param["a"] = "upload";
		$this->vista["form_action_subirarch"] = $ou->generarUrlMenu($param, $this->config);
		
        $param["c"] = "archivo";
		$param["a"] = "download";
		$this->vista["form_action_descargar"] = $ou->generarUrlMenu($param, $this->config);
		
        $param["c"] = "archivo";
		$param["a"] = "modif_arch";
		$this->vista["form_action_modifarch"] = $ou->generarUrlMenu($param, $this->config);
		
		$vista="html/vstmaestra.phtml";

		$this->generarVista($vista);
	}//fin function
}//fin class
?>