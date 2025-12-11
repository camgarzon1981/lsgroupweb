<?php
include 'modules/mod_embarques/objetos/campo.php';
function cargarCampos() {
	$string = file_get_contents ( "modules/mod_embarques/config/campos.json" );
	$json_a = json_decode ( $string, true );
	$campos = array ();
	foreach ( $json_a as $campos_name => $campos_a ) {
		$campo = new campo ();
		$campo->nombre = $campos_a ['nombre'];
		$campo->etiqueta = $campos_a ['etiqueta'];
		$campo->orden = $campos_a ['orden'];
		$campos [] = $campo;
	}
	return $campos;
}
function esribirArchivoCampos($contenido) {
	$myfile = fopen ( "modules/mod_embarques/config/campos.json", "w" ) or die ( "Unable to open file!" );
	fwrite ( $myfile, $contenido );
	fclose ( $myfile );
}
?>