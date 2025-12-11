<?php
ini_set('memory_limit', '256M');
include 'views/components/header.php';
include 'views/components/footer.php';
include 'views/sections/empresas.php';
print_r ( getHeader ( "Index" ) );
if ($_GET ['accion'] == 'buscarEmpresas') {
	print_r ( getBuscadorEmpresa ( $_POST ['nombre'] ) );
} else if ($_GET ['accion'] == 'consultarEmpresa') {
	print_r ( getConsultarEmpresa( $_GET ['nit'] ) );
}
print_r ( $footer );
?>