<?php
// include 'servicios/funcionesJson.php';
include 'servicios/funcionesDynamo.php';
include 'config/ut_con.php';
// fetch table rows from mysql db
// $sql = "select * from lsgroupdb.campos";
// $db = new BD ();
// $db->con($db, $host, $user, $pass);
// $result =$db->sql($sql) or die ( "Error in Selecting " . mysqli_error ( $db->Conexion_ID ) );
// //create an array
// $emparray = array();
// while($row =mysqli_fetch_assoc($result))
// {
// $emparray[] = $row;
// }
// esribirArchivoCampos(json_encode($emparray));

$query = "SELECT c.nombre, cu.id_usuario, cu.listado, cu.forma, cu.mail FROM lsgroupdb.camposxusuario cu, lsgroupdb.campos c where cu.id_campo=c.id order by id_usuario desc";
$db = new BD ();
$db->con ( $db, $host, $user, $pass );
$result = $db->sql ( $query ) or die ( "Error in Selecting " . mysqli_error ( $db->Conexion_ID ) );
// create an array
$emparray = array ();
$empresa = new empresa ();
while ( $row = mysqli_fetch_assoc ( $result ) ) {
	$emparray [] = $row;
	if ($empresa->nit != $row ['id_usuario']) {
		if (! empty ( $empresa->nit )) {
			crearEmpresa ( $empresa ) ;
		}
		$empresa = cargarEmpresa ( $row ["id_usuario"] );		
		$empresa->campos=array();
		$empresa->campos_cliente=array();
		$empresa->campos_mail=array();
		print_r ( $empresa->nombre );
	}
	$empresa->campos [] = $row ['nombre'];
	if ($row ['listado'] === 'Si') {
		$empresa->campos_cliente [] = $row ['nombre'];
	}
	if ($row ['mail'] === 'Si') {
		$empresa->campos_mail [] = $row ['nombre'];
	}
}

?>