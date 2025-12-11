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
		$embarques=$session->get('embarquesParaEnviar');
	}

	$usuarios=consultarUsuariosJoomla();
	$usuariosls=consultarUsuariosJoomlaEmpresa("830136560");
	$usuariosaduanera=consultarUsuariosJoomlaEmpresa("860028026");
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
		{"id":"exportador","label":"Exportador","type":"string"},
		{"id":"importador","label":"Importador","type":"string"},
		{"id":"ref1_cliente","label":"Ref 1 Cliente","type":"string"},
		{"id":"radicacion_bl","label":"Radicacion BL","type":"string"},
		{"id":"mawb","label":"MAWB","type":"string"},
		{"id":"etd","label":"ETD","type":"string"},
		{"id":"eta","label":"ETA","type":"string"},
		{"id":"ata","label":"ATA","type":"string"},
		{"id":"destino","label":"Destino","type":"string"},
		{"id":"observaciones","label":"Observaciones","type":"string"},
		{"id":"operativo","label":"Operativo","type":"string"},
		{"id":"vendedor","label":"Vendedor","type":"string"},
		{"id":"pais","label":"Pais","type":"string"},
		{"id":"estado","label":"Estado","type":"string"},
		{"id":"via","label":"Via","type":"string"},
		{"id":"tipo","label":"Tipo","type":"string"},
		{"id":"origen","label":"Origen","type":"string"},
		{"id":"destino","label":"Destino","type":"string"},
		{"id":"do","label":"DO","type":"string"},
		{"id":"naviera","label":"Naviera","type":"string"},
		{"id":"vapor","label":"Vapor","type":"string"},
		{"id":"referencia","label":"Referencia","type":"string"},
		{"id":"Accion","label":"Accion","type":"string"}
      ],
"rows": [
<?foreach($embarques as $embarque ){
	
	echo str_replace("00:00:00","",preg_replace( "/\r|\n/", "", '{"c":[	
		{"v":"' . $embarque['exportador']. '",p:{style: "font-size: 7pt "}},
		{"v":"' . trim($embarque['importador']) . '",p:{style: "font-size: 7pt "}},
		{"v":"' . trim($embarque['ref1_cliente']) . '",p:{style: "font-size: 7pt "}},
		{"v":"' . trim($embarque['radicacion_bl']) . '",p:{style: "font-size: 7pt "}},
		{"v":"' . trim($embarque['mawb']) . '",p:{style: "font-size: 7pt "}},
		{"v":" '. trim(explode(" ",($embarque['etd']))[0]) . '",p:{style: "font-size: 7pt; white-space: nowrap "}},
		{"v":"' . trim(explode(" ",($embarque['eta']))[0]) . '",p:{style: "font-size: 7pt; white-space: nowrap "}},
		{"v":"' . trim(explode(" ",($embarque['ata']))[0]) . '",p:{style: "font-size: 7pt; white-space: nowrap "}},
		{"v":"' . trim($embarque['destino']) . '",p:{style: "font-size: 7pt "}},
		{"v":"' . trim($embarque['observaciones']) . '",p:{style: "font-size: 7pt "}},
		{"v":"' . trim($embarque['operativo']) . '",p:{style: "font-size: 7pt "}},
		{"v":"' . trim($embarque['vendedor']) . '",p:{style: "font-size: 7pt "}},
		{"v":"' . trim($embarque['pais']) . '",p:{style: "font-size: 7pt "}},
		{"v":"' . trim($embarque['estado']) . '",p:{style: "font-size: 7pt "}},
		{"v":"' . trim($embarque['via']) . '",p:{style: "font-size: 7pt "}},
		{"v":"' . trim($embarque['tipo']) . '",p:{style: "font-size: 7pt "}},
		{"v":"' . trim($embarque['origen']) . '",p:{style: "font-size: 7pt "}},
		{"v":"' . trim($embarque['destino_final']) . '",p:{style: "font-size: 7pt "}},
		{"v":"' . trim($embarque['do_ls']) . '",p:{style: "font-size: 7pt "}},
		{"v":"' . trim($embarque['naviera_ls']) . '",p:{style: "font-size: 7pt "}},
		{"v":"' . trim($embarque['vapor_ls']) . '",p:{style: "font-size: 7pt "}},
		{"v":"' . trim($embarque['ref1_cliente']) . '",p:{style: "font-size: 7pt "}},
		{"v":"<a href=embarques?accion=EliminarEnvio&id_embarque='.$embarque["id"].'&id_cliente='.$embarque["id_cliente"].'>Eliminar Envio</a>"}]},' ));?>
				<?}?>

]});
			// Create a range slider, passing some options
       

                       var referenciaFilter = new google.visualization.ControlWrapper({
          "controlType": "StringFilter",
          "containerId": "referencia_div",
          "options": {
            "filterColumnLabel": "Referencia"
          }
        });	

            
			    var table = new google.visualization.ChartWrapper({ "chartType": "Table", "containerId": "map", "options": { allowHtml: true, page:"enable", pageSize: 20, width:
     "100%",'cssClassNames': cssClassNames } }); 
			    //table.draw(data, {pageSize: 15, page: true,width: "100%", height: "100%"});
			    table.setView({'columns': [0, 1,2,3,4,5,6,7,8,9,10,11, 22]}); 
			dashboard.bind( [referenciaFilter],table);
			dashboard.draw(data);
			} </script>
			<h4><? echo $mensaje; ?></h4>
			<tbody>
<tr>
<td colspan="3"><div id="referencia_div"></div></td>
</tr></tbody></table>
</div>
<form method="POST" >
<div id="dashboard_div">
			<div id="referencia_div"></div>
			<div id="map"></div>
			</div>
<br>
<br>
			<div id="usuarios">
			<table width="100%" border="1" cellspacing="2"> 
			<tr><th colspan="2">Seleccion de correos</th></tr>
		<tr><td valign="top" width="50%">	<table border="1" width="100%">
				<tr><th colspan="3">Usuarios L.S Group</th></tr>
				<tr><th>Nombre</th><th>Mail</th><th></th></tr>
				<?foreach($usuariosls as $usuario){?>
					<tr><td><?echo $usuario->name;?></td><td><?echo $usuario->email;?></td><td align="center"><input type="checkbox" id="email[]" name="email[]" value="<?echo $usuario->email;?>" /></td></tr>
				<?}?>
			</table>
			</td><td valign="top" width="50%">
						<table border="1" width="100%">
				<tr><th colspan="4">Usuarios Aduanera Colombiana</th></tr>
				<tr><th>Nombre</th><th>Mail</th><th></th></tr>
				<?foreach($usuariosaduanera as $usuario){?>
					<tr><td><?echo $usuario->name;?></td><td><?echo $usuario->email;?></td><td align="center"><input type="checkbox" id="email[]" name="email[]" value="<?echo $usuario->email;?>" /></td></tr>
				<?}?>
			</table>
			</td></tr>
			<tr><td colspan="2">Tipo de envio:<select id="tipoEnvio" name="tipoEnvio"><option value="Interno">Interno</option><option value="Cliente">Cliente</option></select></td></tr>
</table>
			</div>
			<div id="usuarios_internos">
			</div>
			<div align="center"><input type="hidden" name="accion" id="accion" value="EnviarMailPreview" /><button>Enviar</button></div>
	</form>	