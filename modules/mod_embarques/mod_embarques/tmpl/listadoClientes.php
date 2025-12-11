<?php 
defined ( '_JEXEC' ) or die ();
$campos = cargarCampos ();
$empresas=consultarEmpresas(" ");
$empresas_sort=array();
foreach($empresas as $empresa){
	$empresas_sort[$empresa->nit]=$empresa->nombre;
}
asort($empresas_sort);
if(empty($embarques)){
		$session = JFactory::getSession();
		$embarques=$session->get('resultadosBusqueda');
	}
?>
<div id="buscador">
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
			<script type="text/javascript">
    google.charts.load("current", {"packages":["corechart","table","controls"]});
    google.charts.setOnLoadCallback(drawChart);
    		function drawChart() {
				var cssClassNames = {
    'headerRow': '',
    'tableRow': '',
    'oddTableRow': '',
    'selectedTableRow': '',
    'hoverTableRow': '',
    'headerCell': '',
    'tableCell': 'font-size: 30%',
    'rowNumberCell': ''};
				
				//var json="";
				var dashboard = new google.visualization.Dashboard(document.getElementById("dashboard_div"));
				
				var data = new google.visualization.DataTable({"cols": [
		{"id":"nit","label":"NIT","type":"string"},
		{"id":"nombre","label":"Nombre","type":"string"},
		{"id":"accion","label":"Accion","type":"string"}
      ],
"rows": [
<?foreach($listadoEmpresas as $embarque ){
	
	echo str_replace("00:00:00","",preg_replace( "/\r|\n/", "", '{"c":[	
		{"v":"' . $embarque->nit. '","p":{"style": "font-size: 7pt "}},
		{"v":"' . trim($embarque->nombre) . '","p":{"style": "font-size: 7pt "}},
		{"v":"<a href=sistema-de-seguimiento?accion=Consultar&nit='.$embarque->nit.'>Ver</a>","p":{"style": "font-size: 7pt "}}
		]
	},'));
	echo "\n";?>

				<?}?>

]});
			// Create a range slider, passing some options
        var nombreFilter = new google.visualization.ControlWrapper({
          "controlType": "StringFilter",
          "containerId": "nombre_div",
          "options": {
            "filterColumnLabel": "Nombre"
          }
        });
        var nitFilter = new google.visualization.ControlWrapper({
          "controlType": "StringFilter",
          "containerId": "nit_div",
          "options": {
            "filterColumnLabel": "NIT"
          }
        });
			    var table = new google.visualization.ChartWrapper({ "chartType": "Table", "containerId": "map", "options": { allowHtml: true, page:"enable", pageSize: 20, width:
     "100%",'cssClassNames': cssClassNames } }); 
			    //table.draw(data, {pageSize: 15, page: true,width: "100%", height: "100%"});
			    table.setView({'columns': [0, 1,2]}); 
			dashboard.bind( [nombreFilter, nitFilter],table);
			dashboard.draw(data);
			} </script>
			<h4><? echo $mensaje; ?></h4>
			
<form method="POST" name="buscadorEmbarquesForm"><table align="center" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC" border="1" width="100%">
			<tbody>
			<tr bgcolor="#FF9933">
				<th colspan="2">Filtrar Resultado</th>
			</tr>
			<tr>
				
				<td><div id="nit_div"></div></td>
				
				<td><div id="nombre_div"></div></td>
			</tr>
			
			
</form>


<!--<form method="POST" name="consultarEmbarquesForm">
<div align="center"><input type="hidden" name="accion" id="accion" value="ConsultarEmbarquesAntiguos" />
	<button type="submit">Consultar</button></div></form>
	
	<form method="POST" name="migrarEmbarquesForm">
<div align="center"><input type="hidden" name="accion" id="accion" value="MigrarEmpresa" />
	<button type="submit">Migrar Empresa</button></div></form>-->






		</tbody></table>
					

</div>
<div id="dashboard_div">
			<div id="map"></div>
			</div>
			<a href=sistema-de-seguimiento?accion=Consultar>Nuevo cliente</a>