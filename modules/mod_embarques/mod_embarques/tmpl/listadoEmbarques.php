<?php 
defined ( '_JEXEC' ) or die ();
$campos = cargarCampos ();
$empresas=consultarEmpresas(" ");
$empresas_sort=array();
foreach($empresas as $empresa){
	$empresas_sort[$empresa->nit]=$empresa->nombre;
}
$user = JFactory::getUser();

asort($empresas_sort);
//if(empty($embarques)){
		$session = JFactory::getSession();
		$embarques=$session->get('resultadosBusqueda');
	//}
$parametros=$session->get('parametros');
?>
<? $groups = $user->get('groups');
$hasAccesstoModify=false;
foreach ($groups as $group)
{
	if($group==8 or $group==12 or $group==11 or $group==10){
		$hasAccesstoModify=true;
	}
    
}?>
<div id="buscador">
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">

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

 var jsonData = jQuery.ajax({
          url: "/ajaxResponser.php?id_cliente=860513978&estado_id=No%20Finalizado",
          dataType: "json",
          async: false
          }).responseText;
				
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
    //{"id":"pais","label":"Pais","type":"string"},
    //{"id":"estado","label":"Estado","type":"string"},
    //{"id":"via","label":"Via","type":"string"},
    //{"id":"tipo","label":"Tipo","type":"string"},
    {"id":"origen","label":"Origen","type":"string"},
    //{"id":"destino","label":"Destino","type":"string"},
    //{"id":"do","label":"DO","type":"string"},
    //{"id":"naviera","label":"Naviera","type":"string"},
    //{"id":"vapor","label":"Vapor","type":"string"},
    //{"id":"referencia","label":"Referencia","type":"string"},
    {"id":"hawb","label":"HAWB","type":"string"},
    {"id":"Accion","label":"Accion","type":"string"}
      ],
"rows": [
<?foreach($embarques as $embarque ){
  if($hasAccesstoModify){
  echo str_replace("00:00:00","",preg_replace( "/\r|\n/", "", '{"c":[ 
    {"v":"' . $embarque['exportador']. '",p:{style: "font-size: 7pt "}},
    {"v":"' . trim($embarque['importador']) . '",p:{style: "font-size: 7pt "}},
    {"v":"' . trim($embarque['referencia_cliente1']) . '",p:{style: "font-size: 7pt "}},
    {"v":"' . trim($embarque['fecha_radicacion_bls']) . '",p:{style: "font-size: 7pt "}},
    {"v":"' . trim($embarque['mawb']) . '",p:{style: "font-size: 7pt "}},
    {"v":" '. trim(explode(" ",($embarque['etd']))[0]) . '",p:{style: "font-size: 7pt; white-space: nowrap "}},
    {"v":"' . trim(explode(" ",($embarque['eta']))[0]) . '",p:{style: "font-size: 7pt; white-space: nowrap "}},
    {"v":"' . trim(explode(" ",($embarque['ata']))[0]) . '",p:{style: "font-size: 7pt; white-space: nowrap "}},
    {"v":"' . trim($embarque['destino_final']) . '",p:{style: "font-size: 7pt "}},
    {"v":"' . substr(trim($embarque['observaciones']),0,30) . '",p:{style: "font-size: 7pt "}},
    {"v":"' . trim($embarque['operativo']) . '",p:{style: "font-size: 7pt "}},
    {"v":"' . trim($embarque['vendedor']) . '",p:{style: "font-size: 7pt "}},
     {"v":"' . trim($embarque['origen']) . '",p:{style: "font-size: 7pt "}},
    {"v":"' . trim($embarque['hawb']) . '",p:{style: "font-size: 7pt "}},
    {"v":"<a href=embarques?accion=ConsultarEmbarque&id_embarque='.$embarque["id"].'&id_cliente='.$embarque["id_cliente"].' target=_blank>Ver</a></br>
   		<a href=embarques?accion=AgregarEnvio&id_embarque='.$embarque["id"].'&id_cliente='.$embarque["id_cliente"].' target=_blank>Agregar</a><br><a href=embarques?accion=EliminarEnvio&id_embarque='.$embarque["id"].'&id_cliente='.$embarque["id_cliente"].' onclick=return confirm('."Are you sure?".')>Eliminar</a>"}]},' ));
}else{
	echo str_replace("00:00:00","",preg_replace( "/\r|\n/", "", '{"c":[ 
    {"v":"' . $embarque['exportador']. '",p:{style: "font-size: 7pt "}},
    {"v":"' . trim($embarque['importador']) . '",p:{style: "font-size: 7pt "}},
    {"v":"' . trim($embarque['referencia_cliente1']) . '",p:{style: "font-size: 7pt "}},
    {"v":"' . trim($embarque['fecha_radicacion_bls']) . '",p:{style: "font-size: 7pt "}},
    {"v":"' . trim($embarque['mawb']) . '",p:{style: "font-size: 7pt "}},
    {"v":" '. trim(explode(" ",($embarque['etd']))[0]) . '",p:{style: "font-size: 7pt; white-space: nowrap "}},
    {"v":"' . trim(explode(" ",($embarque['eta']))[0]) . '",p:{style: "font-size: 7pt; white-space: nowrap "}},
    {"v":"' . trim(explode(" ",($embarque['ata']))[0]) . '",p:{style: "font-size: 7pt; white-space: nowrap "}},
    {"v":"' . trim($embarque['destino_final']) . '",p:{style: "font-size: 7pt "}},
    {"v":"' . substr(trim($embarque['observaciones']),0,30) . '",p:{style: "font-size: 7pt "}},
    {"v":"' . trim($embarque['operativo']) . '",p:{style: "font-size: 7pt "}},
    {"v":"' . trim($embarque['vendedor']) . '",p:{style: "font-size: 7pt "}},
     {"v":"' . trim($embarque['origen']) . '",p:{style: "font-size: 7pt "}},
    {"v":"' . trim($embarque['hawb']) . '",p:{style: "font-size: 7pt "}},
    {"v":"<a href=embarques?accion=ConsultarEmbarque&id_embarque='.$embarque["id"].'&id_cliente='.$embarque["id_cliente"].' target=_blank>Ver</a>"}]},' ));
}
	?>
        <?}?>

]});

				 
		/*	// Create a range slider, passing some options
        var nombreFilter = new google.visualization.ControlWrapper({
          "controlType": "StringFilter",
          "containerId": "importador_div",
          "options": {
            "filterColumnLabel": "Importador"
          }
        });
        var paisFilter = new google.visualization.ControlWrapper({
          "controlType": "CategoryFilter",
          "containerId": "pais_div",
          "options": {
            "filterColumnLabel": "Pais"
          }
        });
        var exportadorFilter = new google.visualization.ControlWrapper({
          "controlType": "StringFilter",
          "containerId": "exportador_div",
          "options": {
            "filterColumnLabel": "Exportador"
          }
        });
        var operativoFilter = new google.visualization.ControlWrapper({
          "controlType": "CategoryFilter",
          "containerId": "operativo_div",
          "options": {
            "filterColumnLabel": "Operativo"
          }
        });	
        var vendedorFilter = new google.visualization.ControlWrapper({
          "controlType": "CategoryFilter",
          "containerId": "vendedor_div",
          "options": {
            "filterColumnLabel": "Vendedor"
          }
        });	
         var estadoFilter = new google.visualization.ControlWrapper({
          "controlType": "CategoryFilter",
          "containerId": "estado_div",
          "options": {
            "filterColumnLabel": "Estado"
          }
        });	
          var viaFilter = new google.visualization.ControlWrapper({
          "controlType": "CategoryFilter",
          "containerId": "via_div",
          "options": {
            "filterColumnLabel": "Via"
          }
        });	

           var tipoFilter = new google.visualization.ControlWrapper({
          "controlType": "CategoryFilter",
          "containerId": "tipo_div",
          "options": {
            "filterColumnLabel": "Tipo"
          }
        });	

            var origenFilter = new google.visualization.ControlWrapper({
          "controlType": "CategoryFilter",
          "containerId": "origen_div",
          "options": {
            "filterColumnLabel": "Origen"
          }
        });	

               var destinoFilter = new google.visualization.ControlWrapper({
          "controlType": "CategoryFilter",
          "containerId": "destino_div",
          "options": {
            "filterColumnLabel": "Destino"
          }
        });	

                var doFilter = new google.visualization.ControlWrapper({
          "controlType": "StringFilter",
          "containerId": "do_div",
          "options": {
            "filterColumnLabel": "DO"
          }
        });	

                    var navieraFilter = new google.visualization.ControlWrapper({
          "controlType": "CategoryFilter",
          "containerId": "naviera_div",
          "options": {
            "filterColumnLabel": "Naviera"
          }
        });	

                     var vaporFilter = new google.visualization.ControlWrapper({
          "controlType": "CategoryFilter",
          "containerId": "vapor_div",
          "options": {
            "filterColumnLabel": "Vapor"
          }
        });	

                       var referenciaFilter = new google.visualization.ControlWrapper({
          "controlType": "StringFilter",
          "containerId": "referencia_div",
          "options": {
            "filterColumnLabel": "Referencia"
          }
        });	

                        var etaFilter = new google.visualization.ControlWrapper({
          "controlType": "StringFilter",
          "containerId": "eta_div",
          "options": {
            "filterColumnLabel": "ETA"
          }
        });	

                         var ataFilter = new google.visualization.ControlWrapper({
          "controlType": "StringFilter",
          "containerId": "ata_div",
          "options": {
            "filterColumnLabel": "ATA"
          }
        });	


                         var etdFilter = new google.visualization.ControlWrapper({
          "controlType": "StringFilter",
          "containerId": "etd_div",
          "options": {
            "filterColumnLabel": "ETD"
          }
        }); */
        	
			    var table = new google.visualization.Table(document.getElementById('map')); 
			    //table.draw(data, {pageSize: 15, page: true,width: "100%", height: "100%"});
			  //  table.setView({'columns': [0, 1,2,3,4,5,6,7,8,9,10,11,16,22,23]}); 
          table.draw(data,{ allowHtml: true, page:"enable", pageSize: 20, width:
     "100%",'cssClassNames': cssClassNames});
		//	dashboard.bind( null,table);
			//dashboard.draw(data);
			} </script>
			<h4><? echo $mensaje; ?></h4>
			
<div>
	<?echo '<p>'.$user->name.' - '.$user->nit. '</p>'?>
	

			<?if($hasAccesstoModify){?><table>
				<tr><th colspan="8">Acciones</th></tr><tr><td><form method="GET" name="nuevoEmbarqueForm">
<div><input type="hidden" name="accion" id="accion" value="NuevoEmbarque" />
	<button type="submit" title="Crear un nuevo embarque">Nuevo Embarque</button></div>
</form></td><td><form method=GET><input type=hidden name=accion value="migrador" /><input type=submit value="Migrador" /></form></td><td><form method=GET><input type=hidden name=accion value="AgregarTodasEmbarquesAEnviar" /><input type=submit value="Agregar todas" title="Agrega todos los envios que hay en la tabla de abajo a los envios pendientes por enviar."/></form></td><td><form method=GET><input type=hidden name=accion value="ConsultarEmbarquesAEnviar" /><input type=submit value="Envio de Informacion (<?$session = JFactory::getSession();
		$embarquesEnviar=$session->get('embarquesParaEnviar'); echo count($embarquesEnviar)?>)" title="Muestra los embarques seleccionados para ser enviados al cliente o internamente"/></form></td><td><form method=GET><input type=hidden name=accion value="LimpiarEmbarquesAEnviar" /><input type=submit value="Limpiar Embarques a Enviar" title="Elimina los embarques que se habian agregado para ser enviados por mail" /></form></td></tr></table><?}?></div>
<form method="POST" name="buscadorEmbarquesForm"><table align="center" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC" border="1" width="100%">
			<tbody><tr bgcolor="#FF9933">
				<th colspan="2">Buscar DO</th>
			</tr>
			<tr>
				
								<td colspan="2">Cliente <?if($hasAccesstoModify){?><select name="id_cliente"><option value="">(No seleccionado)</option><?foreach($empresas_sort as $nit => $nombre){?><option value="<?echo $nit?>" <?if ($_POST["id_cliente"]==$nit){ echo "selected"; }?>><?echo $nombre?><?}?></select><?} else {?><?echo '<p>'.$user->name.' - '.$user->nit. '</p>'; echo '<input type="hidden" name="id_cliente" id="id_cliente" value="'.$user->nit.'"/>'; }?><input type="hidden" name="accion" id="accion" value="BuscarEmbarquesOperaciones" />
	</td>

				
							
				
			</tr>
			
					<tr>
				
				<td><div id="origen_div"></div>Origen: <input type="text" name="origen" id="origen" value="<?php echo $parametros['origen'] ?>"/></td>
				
				<td><div id="destino_div"></div>Destino: <input type="text" name="destino" id="destino" value="<?php echo $parametros['destino'] ?>"/></td>
			</tr>
			<tr>
				
				<td><div id="do_div"></div>DO: <input type="text" name="do" id="do" value="<?php echo $parametros['do'] ?>"/></td>
				
								<td><div id="tipo_div"></div>Tipo: <select name="tipo" id="tipo"><option value="">(No seleccionado)</option><option value="Importacion" <?if($parametros['tipo']=='Importacion'){?>selected<?}?>>Importacion</option><option value="Exportacion" <?if($parametros['tipo']=='Exportacion'){?>selected<?}?>>Exportacion</option></select></td>
							</tr>
			<tr>
				
				<td><div id="vapor_div"></div>Vapor: <input type="text" name="vapor" id="vapor" value="<?php echo $parametros['vapor'] ?>"/></td>
				

				<td><div id="naviera_div"></div>Naviera: <input type="text" name="naviera" id="naviera" value="<?php echo $parametros['naviera'] ?>"/></td>
			</tr>
			<tr>
				
								<td><div id="via_div"></div>Via <select name="via" id="via"><option value="">(No seleccionado)</option><option value="Aerea" <?if($parametros['via']=='Aerea'){?>selected<?}?> >Aerea</option><option value="Maritima" <?if($parametros['via']=='Maritima'){?>selected<?}?>>Maritima</option></select></td>
								<td><div id="pais_div"></div>Pais: <input type="text" name="pais" id="pais" value="<?php echo $parametros['pais'] ?>"/></td>
							</tr>
			<tr>
				
				<td><div id="eta_div"></div>ETA:<input type="date" name="eta" id="eta" value="<?php echo $parametros['eta'] ?>"/><br>
			<div id="ata_div"></div>ATA:<input type="date" name="ata" id="ata" value="<?php echo $parametros['ata'] ?>"/></td>

				
				<td><div id="etd_div"></div>ETD:<input type="date" name="etd" id="etd" value="<?php echo $parametros['etd'] ?>"/></td>
			</tr>
<tr>
				
				<td><div id="importador_div">Importador:<input type="text" name="importador" id="importador" value="<?php echo $parametros['importador'] ?>"/></div>
			</td>

				<td><div id="exportador_div"></div>Exportador:<input type="text" name="exportador" id="exportador" value="<?php echo $parametros['exportador'] ?>"/></td>

</tr>

<tr>

				<td ><div id="operativo_div"></div>Operativo:<input type="text" name="operativo" id="operativo" value="<?php echo $parametros['operativo'] ?>"/>
			</td>

				<td ><div id="vendedor_div"></div>Vendedor:<input type="text" name="vendedor" id="vendedor" value="<?php echo $parametros['vendedor'] ?>"/></td>

</tr>
<tr>
			
			<td ><div id="estado_div"></div>Estado: <select name="estado_id" id="estado_id"><option value="">(No seleccionado)</option><option value="Finalizado" <?if($parametros['estado_id']=='Finalizado'){?>selected<?}?>>Finalizado</option><option value="No Finalizado" <?if($parametros['estado_id']=='No Finalizado'){?>selected<?}?>>No Finalizado</option></select></td>

				<td ><div id="referencia_div"></div>Referencia:<input type="text" name="referencia" id="referencia" value="<?php echo $parametros['referencia'] ?>"/></td>

</tr>
<tr><td colspan="2" align="center"><button type="submit" title="Busca los embarques por cliente o por estado, para un filtro mas detallado, una vez ejecutada esta busqueda puede utilizar los filtros de abajo.">Buscar</button></td></tr>

			
</form>


<!-- <table id="embarquesJquery" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>             
                <th>Exportador</th>
                <th>Importador</th>
                <th>Ref 1 Cliente</th>
                <th>Radicacion BL</th>
                <th>MAWB</th>
                <th>ETD</th>
                <th>ETA</th>
                <th>ATA</th>
                <th>Destino</th>
                <th>Observaciones</th>
                <th>Vendedor</th>
                <th>Pais</th>
                <th>Estado</th>
                <th>Via</th>
                <th>Tipo</th>
                <th>Origen</th>
                <th>Destino</th>
                <th>DO</th>
                <th>Naviera</th>
                <th>Vapor</th>
                 <th>Referencia</th>
                  <th>HAWB</th>
                   <th>Accion</th>
            </tr>
        </thead>
        
    </table> -->


<!--<form method="POST" name="consultarEmbarquesForm">
<div align="center"><input type="hidden" name="accion" id="accion" value="ConsultarEmbarquesAntiguos" />
	<button type="submit">Consultar</button></div></form>
	
	<form method="POST" name="migrarEmbarquesForm">
<div align="center"><input type="hidden" name="accion" id="accion" value="MigrarEmpresa" />
	<button type="submit">Migrar Empresa</button></div></form>-->






		</tbody></table>
					

</div>
<div id="dashboard_div">
			<div id="cliente_div"></div>
			
			
			
			<div id="do_div"></div>
			<div id="tipo_div"></div>
			<div id="vapor_div"></div>
			<div id="naviera_div"></div>
			
			<div id="atd_div"></div>
			
			<div id="referencia_div"></div>
			
			<div id="map"></div>
			</div>
			