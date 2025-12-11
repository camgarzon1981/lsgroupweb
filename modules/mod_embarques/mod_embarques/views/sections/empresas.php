<?php
include 'modules/mod_embarques/config/ut_con.php';
include 'modules/mod_embarques/servicios/funcionesDynamo.php';
//include 'modules/mod_embarques/servicios/funcionesJSon.php';
function getBuscadorEmpresa($nombre) {
	$datos = array ();
	if (strlen ( $nombre ) >= 1) {
		$datos = consultarEmpresas ( $nombre );
	}

	$jsonEmpresas="";
	$jsonEmpresas=getTableEmpresas();
	$forma = '
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
			<script type="text/javascript">
    google.charts.load("current", {"packages":["corechart","table","controls"]});
    google.charts.setOnLoadCallback(drawChart);
    		function drawChart() {
				var json="";	
				
				var dashboard = new google.visualization.Dashboard(document.getElementById("dashboard_div"));
				json='.$jsonEmpresas.';
				var data = new google.visualization.DataTable(json);
			// Create a range slider, passing some options
        var nombreFilter = new google.visualization.ControlWrapper({
          "controlType": "StringFilter",
          "containerId": "filter_div",
          "options": {
            "filterColumnLabel": "Nombre"
          }
        });	
			    var table = new google.visualization.ChartWrapper({ "chartType": "Table", "containerId": "map", "options": { allowHtml: true, page:"enable", pageSize: 20, width:
     "100%" } }); 
			    //table.draw(data, {pageSize: 15, page: true,width: "100%", height: "100%"});
			dashboard.bind( [nombreFilter],table);
			dashboard.draw(data);
			} </script>
			<div id="dashboard_div">
			<div id="filter_div"></div>
			<div id="map"></div>
			</div>';
	return $forma ;
}
function getTableGoogleEmpresas() {
	$datos = getTableEmpresas ();
	return $datos;
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
	$formaEmpresa = $formaEmpresa . "<form name='empresaForm' method='POST' action=".JRoute::_('index.php')."><table><tr><th>Nit</th><td><input id='nit' name='id' type='number' value='" . $empresa->nit . "'/></td></tr><tr><th>Nombre</th><td><input id='nombre' value='" . $empresa->nombre . "' name='nombre' type='text'/></td></tr><tr><td colspan='2'><input type='hidden' name='accion' value='GrabarEmpresa'/><input type='submit'  value='Grabar Empresa'/></td></tr></table></form>";
	return $formaEmpresa;
}

?>