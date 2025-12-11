<?php
include 'config/ut_con.php';
include 'servicios/funcionesDynamo.php';
include 'servicios/funcionesJSon.php';
function getBuscadorEmpresa($nombre) {
	$datos = array ();
	if (strlen ( $nombre ) >= 1) {
		$datos = consultarEmpresas ( $nombre );
	}
	
	$forma = "<form method='POST' action=''>
<table width='100%'>
<tr>
<td>
Buscar
</td>
<td>
<input type='text' id='nombre' name='nombre' value='" . $nombre . "'/>
</td>
</tr>
</table>
</form>";
	$tabla = "<table width='100%'>
	<tr>
		<th>Nit</th>
		<th>Nombre</th>
		<th>Consultar</th>
	</tr>
		";
	foreach ( $datos as $dato ) {
		$tabla = $tabla . "<tr><td>" . $dato->nit . "</td><td>" . $dato->nombre . "</td><td><a href='index.php?accion=consultarEmpresa&nit=" . $dato->nit . "'>Consultar</a></tr>";
	}
	$tabla = $tabla . "</tabla>";
	return $forma . " " . $tabla;
}
/**
 * Consulta todas las formas para una empresa
 *
 * @param unknown $nit        	
 * @return string
 */
function getConsultarEmpresa($nit) {
	$empresa = new empresa ();
	if (! empty ( $nit )) {
		$empresa = cargarEmpresa ( $nit );
		$formaEmpresa = crearFormaEmpresa ( $empresa );
		$formaCampos = crearFormaCampos ( $empresa );
		$formaUsuarios = "<h3>Usuarios</h3>";
		$formaUsuarios = $formaUsuarios . "<table>";
		
		$formaUsuarios = $formaUsuarios . "</table>";
		return $formaEmpresa . " " . $formaCampos . " " . $formaUsuarios;
	} else {
		return crearFormaEmpresa ( $empresa );
	}
}
/**
 * Crea la forma de asignacion de campos
 *
 * @param unknown $empresa        	
 * @return string
 */
function crearFormaCampos($empresa) {
	$formaCampos = "<h3>Seleccion Campos</h3>";
	$formaCampos = $formaCampos . "<table><tr><th>Nombre</th><th>General Operaciones</th><th>Visualizacion cliente</th><th>Resumen Mail</th></tr>";
	$campos = cargarCampos ();
	foreach ( $campos as $campo ) {
		$formaCampos = $formaCampos . "<tr><td>" . $campo->etiqueta . "</td>";
		if (in_array ( $campo->nombre, $empresa->campos )) {
			$formaCampos = $formaCampos . "<td align='center'><input type='checkbox' id='campo' value='" . $campo->nombre . "' checked/></td>";
		} else {
			$formaCampos = $formaCampos . "<td align='center'><input type='checkbox' id='campo' value='" . $campo->nombre . "'/></td>";
		}
		if (in_array ( $campo->nombre, $empresa->campos_cliente )) {
			$formaCampos = $formaCampos . "<td align='center'><input type='checkbox' id='campo_cliente' value='" . $campo->nombre . "' checked/></td>";
		} else {
			$formaCampos = $formaCampos . "<td align='center'><input type='checkbox' id='campo_cliente' value='" . $campo->nombre . "'/></td>";
		}
		if (in_array ( $campo->nombre, $empresa->campos_mail )) {
			$formaCampos = $formaCampos . "<td align='center'><input type='checkbox' id='campo_mail' value='" . $campo->nombre . "' checked/></td>";
		} else {
			$formaCampos = $formaCampos . "<td align='center'><input type='checkbox' id='campo_mail' value='" . $campo->nombre . "'/></td>";
		}
		$formaCampos = $formaCampos . "</tr>";
	}
	$formaCampos = $formaCampos . "</table>";
	return $formaCampos;
}
/**
 * Crea la forma para el crud de las empresas
 *
 * @param unknown $empresa        	
 * @return string
 */
function crearFormaEmpresa($empresa) {
	$formaEmpresa = "<h2>Empresa</h2>";
	$formaEmpresa = $formaEmpresa . "<form action='' method='POST'><table><tr><th>Nit</th><td><input id='nit' name='id' type='number' value='" . $empresa->nit . "'/></td></tr><tr><th>Nombre</th><td><input id='nombre' value='" . $empresa->nombre . "' name='nombre' type='text'/></td></tr><tr><td colspan='2'><input type='Submit' id='accion' name='accion' value='Grabar Empresa'/></td></tr></table></form>";
	return $formaEmpresa;
}

?>