<?php
class cliente{
	public $identificacion;
	public $nombre;
	public $direccion;
	public $campos;	
}

$var=new cliente;
$var->identificacion="123232";
$var->nombre="camilo";
echo "Salida: ".$var->identificacion;

?>