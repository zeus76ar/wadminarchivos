<?php
/*
version: 16.06.01
Autor: Ariel Balmaceda.
Compatible con PHP 5
*/
class Textos{
    public function parsearTexto($texto){
		$mitexto=strtolower(trim($texto));
		
        $mitexto=str_replace('ñ', 'n', $mitexto);
		$mitexto=str_replace(' ', '_', $mitexto);
		$mitexto=str_replace('á', 'a', $mitexto);
		$mitexto=str_replace('é', 'e', $mitexto);
		$mitexto=str_replace('í', 'i', $mitexto);
		$mitexto=str_replace('ó', 'o', $mitexto);
		$mitexto=str_replace('ú', 'u', $mitexto);
		
        $mitexto = preg_replace('/[^a-z0-9 ._\/]/si', '', $mitexto);
		
		return $mitexto;
	}
	
	public function formatearTextoParaWeb($texto){
		$retorno=stripslashes($texto);
		
		$retorno=htmlspecialchars($retorno);
		
		return $retorno;
	}
}
?>
