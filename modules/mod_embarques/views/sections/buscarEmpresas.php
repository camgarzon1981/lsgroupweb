<?php
require 'servicios/funcionesDynamo.php';
$datos = consultarEmpresas ( $_POST["nombre"]);
$_SESSION["retorno"]=$datos;
header( 'Location: listarEmpresas.php' ) ;
exit;
?>