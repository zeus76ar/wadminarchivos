<?php
// rutas de acceso
$config["dirvista"]="/vistas/";
$config["dircontrol"]="/controladores/";
$config["dirmodelo"]="/modelos/";
$config["dirmodelo_base"]="/_priv/modelos/";
$config["dirextras"]="/extras/";

// info del sistema
$config["sist_nom"]="wAdminArchivos";
$config["sist_desc"]="Sistema web para administrar archivos.";

$config["sist_anios"]="2016";
if (date("Y") != $config["sist_anios"]) $config["sist_anios"].="-".date("Y");

$config["sist_desarrollo"]="Ariel Balmaceda. Analista Programador.";
$config["sist_ver"]="20.05";//año.mes
$config["sist_subver"]="26";//dia

//usar sesion? 1: si, 0:no
$config["usar_sesion"]=0;

// prefijo para variables sesion
$config["prefijo_sesion"]="waa_";

// estado de la aplicacion: 1-en produccion, 0-en desarrollo
$config["en_produccion"]=0;

// valores por defecto para las url
$config["m_base"]='';
$config["c_base"]='inicio';
$config["a_base"]='index';

//
$config["mostrar_errores"]=($config["en_produccion"] == 1)?0:1;
$config["timezone"]="America/Argentina/Buenos_Aires";
$config["url_amigables"]=1;
$config["url_incluir_index"]=1;
$config["charset"]="utf-8";
$config["pag_error"]="html/404.html";

// para las vistas
$vista=array();

$vista["titulo"]="Inicio";
$vista["error"]="";
$vista["info"]="";
$vista["form_action"]="";

$protocolo='http';
if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') $protocolo='https';

$vista["base"]=$protocolo."://".$_SERVER["HTTP_HOST"].dirname($_SERVER["SCRIPT_NAME"])."/";
//unset($protocolo);

$partes = explode('/', $_SERVER['DOCUMENT_ROOT']);

if ( count($partes) == 1)  $partes = explode('\\', $_SERVER['DOCUMENT_ROOT']);

$vista["dir_root"] = trim( $partes[ ( count($partes) - 1 ) ] );

if ($vista["dir_root"] === '') $vista["dir_root"] = trim($partes[ ( count($partes) - 2 ) ]);

unset($partes, $protocolo);

// ingreso sistema
$config["sist_usuario"]="";
$config["sist_clave"]="";
$config["sist_tiempo"]=0;
?>