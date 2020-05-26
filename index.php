<?php
$config["rutabase"]=str_replace("\\", "/", realpath(dirname(__FILE__)));

// archivo de configuracion base
include($config["rutabase"]."/config/conf_general.php");
include($config["rutabase"]."/config/conf_rutas.php");

if ($config["usar_sesion"] == 1) session_start();

ini_set("display_errors", $config["mostrar_errores"]);

date_default_timezone_set($config["timezone"]);

header('charset=' . $config["charset"]);

//
require($config["rutabase"].$config["dirmodelo_base"]."url.class.php");
require($config["rutabase"].$config["dirmodelo_base"]."preparar_archivo.class.php");
require($config["rutabase"].$config["dirmodelo_base"]."controlador.class.php");

//preparo las variables $_GET
$ou=new Url();

$url_array=array();
$url_array=$ou->revisarUrl($config, $rutas);

unset($ou);

// cargo el controlador correspondiente
$controlador=((isset($_GET["m"]))?($_GET["m"]."/"):'').$_GET["c"];

$opa=new prepararArchivo();
$opa->prepararControlador($config, $controlador);

if ($opa->getErrorPreparar() == false){
    include($opa->getArchivoPreparar());
    
    $comando='$ctr=new '.ucwords($_GET['c']).'();';
    eval($comando);
    
    unset($comando);
    
    if (method_exists($ctr, $_GET['a'])){
        $ctr->setConfig($config);
        $ctr->setVista($vista);
        $ctr->setUrl($url_array);
        
        unset($config, $vista, $url_array);
        
        $metodo=$_GET['a'];
        
        $ctr->$metodo();
    }else{
        include($config["rutabase"].$config["dirvista"].$config["pag_error"]);
    }
}else{
    include($config["rutabase"].$config["dirvista"].$config["pag_error"]);
}
?>