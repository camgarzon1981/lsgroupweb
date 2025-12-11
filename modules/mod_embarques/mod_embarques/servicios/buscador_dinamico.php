<?php
session_start();
ini_set("session.gc_maxlifetime","86400");
include_once ("ut_bd_site.php");
include_once ("funciones.php");
include_once ("validador.php");
if ($bd_Servidor != "localhost") {
	session_save_path($_SERVER['DOCUMENT_ROOT']);
}
$filtro_estado="";
echo $_SESSION['cliente_ses'];
if($Accion=="Buscar"||$Accion=="Enviar"){
			$_SESSION['cliente_ses']=$cliente;
			$_SESSION['tipo_ses']=$tipo;
			$_SESSION['via_ses']=$via;
			$_SESSION['estado_ses']=$estado;
}
if($_SESSION['estado_ses']=="Finalizado"){
	$filtro_estado="AND estado = 'Finalizado' ";
}else if($_SESSION['estado_ses']=="No Finalizado"){
	$filtro_estado="AND estado <> 'Finalizado' ";

}
$filtro_fecha="";
if($fecha_radicacion_inicial!=""){
	$filtro_fecha=$filtro_fecha." AND (etd >= ".$fecha_radicacion_inicial." OR ata >=".$fecha_radicacion_inicial.")";
}
if($fecha_radicacion_final!=""){
	$filtro_fecha=$filtro_fecha." AND (etd <= ".$fecha_radicacion_final." OR ata <=".$fecha_radicacion_final.")";
}
if($atd!=""){
	$filtro_fecha=$filtro_fecha." AND (atd <= ".$atd.")";
} 
$sql_datos="select tipo,e.id as id,estado,exportador,destino_final,importador,asesor,via,do_ls,referencia_cliente1,referencia_cliente2,lc,CONCAT(YEAR(fecha_estimada_entrega),'-',MONTH(fecha_estimada_entrega),'-',DAY(fecha_estimada_entrega)) as fecha_entrega_estimada,CONCAT(YEAR(fecha_entrega_proveedor),'-',MONTH(fecha_entrega_proveedor),'-',DAY(fecha_entrega_proveedor)) as fecha_entrega_proveedor,CONCAT(YEAR(pickup),'-',MONTH(pickup),'-',DAY(pickup)) as pickup,CONCAT(YEAR(ingreso_agente),'-',MONTH(ingreso_agente),'-',DAY(ingreso_agente)) as ingreso_agente,mawb,hawb,CONCAT(YEAR(fecha_radicacion_bls),'-',MONTH(fecha_radicacion_bls),'-',DAY(fecha_radicacion_bls)) as fecha_radicacion_bls,fletes,origen,puerto_embarque,destino_final,vapor,viaje,naviera,cantidad,tipo_contenedor,CONCAT(YEAR(cutoff),'-',MONTH(cutoff),'-',DAY(cutoff)) as cutoff,CONCAT(YEAR(etd),'-',MONTH(etd),'-',DAY(etd)) as etd,CONCAT(YEAR(eta),'-',MONTH(eta),'-',DAY(eta)) as eta,CONCAT(YEAR(ata),'-',MONTH(ata),'-',DAY(ata)) as ata,CONCAT(YEAR(desconsolidacion),'-',MONTH(desconsolidacion),'-',DAY(desconsolidacion)) as desconsolidacion,CONCAT(YEAR(inspeccion),'-',MONTH(inspeccion),'-',DAY(inspeccion)) as inspeccion,CONCAT(YEAR(ingreso_deposito),'-',MONTH(ingreso_deposito),'-',DAY(ingreso_deposito)) as ingreso_deposito,facturar_a,entrega_documentos_a,CONCAT(YEAR(fecha_dec_importacion),'-',MONTH(fecha_dec_importacion),'-',DAY(fecha_dec_importacion)) as fecha_dec_importacion,CONCAT(YEAR(pago_tributos),'-',MONTH(pago_tributos),'-',DAY(pago_tributos)) as pago_tributos,CONCAT(YEAR(levante),'-',MONTH(levante),'-',DAY(levante)) as levante,CONCAT(YEAR(entrega_cliente),'-',MONTH(entrega_cliente),'-',DAY(entrega_cliente)) as entrega_cliente,observaciones from embarques_seguimiento e inner join empresas u on u.nit=e.id_cliente where (naviera like '%".$naviera."%' or naviera is null) and (vapor like '%".$vapor."%' or vapor is null) and (origen like '%".$origen."%' or origen is null) and (do_ls like '%".$do_ls."%' or do_ls is null) and (destino_final like '%".$destino_final."%' or destino_final is null) and (via like '%".$_SESSION['via_ses']."%' or via is null) and (exportador like '%".$exportador."%' or exportador is null) and (importador like '%".$importador."%' or importador is null) and ((referencia_cliente1 like '%".$referencia."%' or referencia_cliente1 is null) and (referencia_cliente2 like '%".$referencia."%' or referencia_cliente2 is null)) and id_cliente like '".$_SESSION['cliente_ses']."' ".$filtro_estado." ".$filtro_fecha;

if($_SESSION['tipo_ses']=="Importacion"){
	$sql_datos=$sql_datos." and tipo like 'Importacion'";
}else if($_SESSION['tipo_ses']=="Exportacion"){
	$sql_datos=$sql_datos." and tipo like 'Exportacion'";

}

			
			
			$i = 0;

			

				$ID_datos = $bd_ID->sql($sql_datos);
				while($row_datos = mysql_fetch_object($ID_datos)){				
				$_SESSION['datos' . $i] = $row_datos;
				$i++;
				}
				
				
				echo $sql_datos;
if($Accion=="Enviar"){
	$filtro_envio="(";
	$contador=count($envios);
	foreach($envios as $envio){
		$filtro_envio=$filtro_envio.$envio;
		if($contador!=1){
			$filtro_envio=$filtro_envio.",";
		}
		$contador--;
	}
	$filtro_envio=$filtro_envio.")";
	$sql_envio="select nit,tipo,e.id as id,estado,destino_final,exportador,importador,asesor,via,do_ls,referencia_cliente1,referencia_cliente2,lc,CONCAT(YEAR(fecha_estimada_entrega),'-',MONTH(fecha_estimada_entrega),'-',DAY(fecha_estimada_entrega)) as fecha_entrega_estimada,CONCAT(YEAR(fecha_entrega_proveedor),'-',MONTH(fecha_entrega_proveedor),'-',DAY(fecha_entrega_proveedor)) as fecha_entrega_proveedor,CONCAT(YEAR(pickup),'-',MONTH(pickup),'-',DAY(pickup)) as pickup,CONCAT(YEAR(ingreso_agente),'-',MONTH(ingreso_agente),'-',DAY(ingreso_agente)) as ingreso_agente,mawb,hawb,CONCAT(YEAR(fecha_radicacion_bls),'-',MONTH(fecha_radicacion_bls),'-',DAY(fecha_radicacion_bls)) as fecha_radicacion_bls,fletes,origen,puerto_embarque,destino_final,vapor,viaje,naviera,cantidad,tipo_contenedor,CONCAT(YEAR(cutoff),'-',MONTH(cutoff),'-',DAY(cutoff)) as cutoff,CONCAT(YEAR(etd),'-',MONTH(etd),'-',DAY(etd)) as etd,CONCAT(YEAR(eta),'-',MONTH(eta),'-',DAY(eta)) as eta,CONCAT(YEAR(ata),'-',MONTH(ata),'-',DAY(ata)) as ata,CONCAT(YEAR(desconsolidacion),'-',MONTH(desconsolidacion),'-',DAY(desconsolidacion)) as desconsolidacion,CONCAT(YEAR(inspeccion),'-',MONTH(inspeccion),'-',DAY(inspeccion)) as inspeccion,CONCAT(YEAR(ingreso_deposito),'-',MONTH(ingreso_deposito),'-',DAY(ingreso_deposito)) as ingreso_deposito,facturar_a,entrega_documentos_a,CONCAT(YEAR(fecha_dec_importacion),'-',MONTH(fecha_dec_importacion),'-',DAY(fecha_dec_importacion)) as fecha_dec_importacion,CONCAT(YEAR(pago_tributos),'-',MONTH(pago_tributos),'-',DAY(pago_tributos)) as pago_tributos,CONCAT(YEAR(levante),'-',MONTH(levante),'-',DAY(levante)) as levante,CONCAT(YEAR(entrega_cliente),'-',MONTH(entrega_cliente),'-',DAY(entrega_cliente)) as entrega_cliente,observaciones from embarques_seguimiento e inner join empresas u on u.nit=e.id_cliente where e.id in ".$filtro_envio;
	$ID_envio = $bd_ID->sql($sql_envio);
	$mensaje="<html>
<head>
<title>LS Group - Crear DO</title>
<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'/>
<link href='estilo.css' rel='stylesheet' type='text/css'>
</head>

<body leftmargin='0' topmargin='0' marginwidth='0' marginheight='0' >
<font size='2'><strong>Apreciado Cliente:</strong> <br><br><br>
Este es un reporte de sus embarques a nuestro cargo, para mas información por favor ingrese a nuestro sistema de informacion <a href='http://www.lsupplier.com'><font size='2'>L.S Group</font></a>, con su login y password, y por favor ingrese por la opción Atencion al clientes y despues vaya al link Mis embarques. <br>
Si usted quiere obtener información de todos sus embarques, sencillamente haga click en buscar sin ingresar ningun dato. Por el contrario si quiere obtener informacion de un embarque en especial ingrese los datos deseados y haga click en buscar. <br>
</font><br><br>
<table width='100%' border='1' cellspacing='0' cellpadding='2'>
	<tr>";
	if($tipoEnvio=="Interna"){
		$mensaje=$mensaje."
		<th><font size='1'>Exportador</font></th>
				<th bgcolor='#FF9933'><font size='1'>Importador</font></th>
				<th bgcolor='#FF9933'><font size='1'>REF 1 Cliente</font></th>
				<th bgcolor='#FF9933'><font size='1'>MAWB</font></th>
				<th bgcolor='#FF9933'><font size='1'>ETD</font></th>
				<th bgcolor='#FF9933'><font size='1'>ETA</font></th>				
				<th bgcolor='#FF9933'><font size='1'>ATA</font></th>
				<th bgcolor='#FF9933'><font size='1'>Destino</font></th>
				<th bgcolor='#FF9933'><font size='1'>Observaciones</font></th>
				</tr>";
	
		while($row_envio = mysql_fetch_object($ID_envio)){
			$mensaje=$mensaje."
			<tr>";		
			if($row_envio->exportador==""){
				$mensaje=$mensaje."<td><font size='1'>&nbsp;</font></td>";
			}else{
				$mensaje=$mensaje."<td><font size='1'>".$row_envio->exportador."</font></td>";
			}
			if($row_envio->importador==""){
				$mensaje=$mensaje."<td><font size='1'>&nbsp;</font></td>";
			}else{
				$mensaje=$mensaje."<td><font size='1'>".$row_envio->importador."</font></td>";
			}
			if($row_envio->referencia_cliente1==""){
				$mensaje=$mensaje."<td><font size='1'>&nbsp;</font></td>";
			}else{
				$mensaje=$mensaje."<td><font size='1'>".$row_envio->referencia_cliente1."</font></td>";
			}
			if($row_envio->mawb==""){
				$mensaje=$mensaje."<td><font size='1'>&nbsp;</font></td>";
			}else{
				$mensaje=$mensaje."<td><font size='1'>".$row_envio->mawb."</font></td>";
			}
			if($row_envio->etd==""){
				$mensaje=$mensaje."<td><font size='1'>&nbsp;</font></td>";
			}else{
				$mensaje=$mensaje."<td nowrap><font size='1'>".$row_envio->etd."</font></td>";
			}
			if($row_envio->eta==""){
				$mensaje=$mensaje."<td><font size='1'>&nbsp;</font></td>";
			}else{
				$mensaje=$mensaje."<td nowrap><font size='1'>".$row_envio->eta."</font></td>";
			}
	
			if($row_envio->ata==""){
				$mensaje=$mensaje."<td><font size='1'>&nbsp;</font></td>";
			}else{
				$mensaje=$mensaje."<td nowrap><font size='1'>".$row_envio->ata."</font></td>";
			}
			if($row_envio->destino_final==""){
				$mensaje=$mensaje."<td><font size='1'>&nbsp;</font></td>";
			}else{
				$mensaje=$mensaje."<td nowrap><font size='1'>".$row_envio->destino_final."</font></td>";
			}
	
			if($row_envio->observaciones==""){
				$mensaje=$mensaje."<td><font size='1'>&nbsp;</font></td>";
			}else{
				$mensaje=$mensaje."<td nowrap><font size='1'>".$row_envio->observaciones."</font></td>";
			}
		$mensaje=$mensaje."</tr>";
		}
	}
	else{
		$sql_datos="SELECT cam.nombre, cam.etiqueta, cam.tipo, cam.obligatorio, cam.contenido, cam.tipo_contenido FROM camposxusuario c inner join campos cam on c.id_campo=cam.id and listado='Si' and id_usuario like '".$cliente."'";
		$ID_datos = $bd_ID->sql($sql_datos);
		$sql_estado_din="SELECT id,estado,via,tipo";
		while($row_campos = mysql_fetch_object($ID_datos)){
				$mensaje=$mensaje."<th bgcolor='#FF9933'><font size='1'>".$row_campos->etiqueta."</font></th>";
		}
		$mensaje=$mensaje."</tr>"; 		
		$ID_datos = $bd_ID->sql($sql_datos);
		$sql_estado_din="SELECT id";
		while($row_campos = mysql_fetch_object($ID_datos)){
			if($row_campos->tipo=="Fecha"){
				$sql_estado_din=$sql_estado_din.",CONCAT(YEAR(".$row_campos->nombre."),'-',MONTH(".$row_campos->nombre."),'-',DAY(".$row_campos->nombre.")) as ".$row_campos->nombre;
			}else{
				$sql_estado_din=$sql_estado_din.",".$row_campos->nombre;
			}
		}
		$sql_estado_din=$sql_estado_din." from embarques_seguimiento where id in ".$filtro_envio;
		$result= $bd_ID->sql($sql_estado_din);
		//echo $sql_estado_din;
		while($row = mysql_fetch_array($result, MYSQL_BOTH)){
			$mensaje=$mensaje."<tr>";
			$sql_datos="SELECT cam.nombre, cam.etiqueta, cam.tipo, cam.obligatorio, cam.contenido, cam.tipo_contenido FROM camposxusuario c inner join campos cam on c.id_campo=cam.id and listado='Si' and id_usuario like '".$cliente."'";
			$ID_datos = $bd_ID->sql($sql_datos);
			$contador=0;
			while($row_campos = mysql_fetch_object($ID_datos)){
				$mensaje=$mensaje."<td nowrap><font size='1'>".$row[$contador+1]."</font></td>";			
				$contador++;
			}
			$mensaje=$mensaje."</tr>";
		}
		
	}
	$mensaje=$mensaje."</table><br>Cordialmente<br>
<strong>LOGISTICS SUPPLIER GROUP S.A </strong></body></html>
";
	enviarMailHTML($mail,$mensaje);
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>LS Group - Buscador</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<SCRIPT LANGUAGE="JavaScript" SRC="/scw.js">
</SCRIPT>
<SCRIPT LANGUAGE="JavaScript" SRC="libreria.js">
</SCRIPT>
</head>
<link href="estilo.css" rel="stylesheet" type="text/css">
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
En linea:
<?=$_SESSION['usuario']->lastname?>
<br>
<a href="/index.php">Regresar al home</a>

<form action="#" method="post" name="envio">

<table width="100%" border="0" align="right" cellpadding="2"
	cellspacing="0">
	<tr>
		<td>

		<table width="500" border="1" align="center" cellpadding="2"
			cellspacing="0" bordercolor="#CCCCCC">
			<tr bgcolor="#FF9933">
				<th colspan="4">Buscar DO</th>
			</tr>
			<tr>
				<th>Cliente</th>
				<?if(isset($_SESSION['cliente_ses'])){?>
				<td><?echo consultarComboUsuariosB($bd_ID,$_SESSION['cliente_ses']);?></td>
				<?}else{?>
				<td><?echo consultarComboUsuariosB($bd_ID,$cliente);?></td>
				
				<?}?>
				<th>Pais</th>
				<td><input name="pais" type="text" id="pais" value="<?=$pais?>"></td>
			</tr>
			<tr>
				<th>Origen</th>
				<td><input name="origen" type="text" id="origen"
					value="<?=$origen?>"></td>
				<th>Destino Final</th>
				<td><input name="destino_final" type="text" id="destino_final"
					value="<?=$destino_final?>"></td>
			</tr>
			<tr>
				<th>D.O</th>
				<td><input name="do_ls" type="text" id="do_ls" value="<?=$do_ls?>"></td>
				<th>Tipo</th>
				<?if(isset($_SESSION['tipo_ses'])){?>
				<td><select name="tipo">
					<option value="">(No filtrar)</option>
					<option value="Exportacion" <?if($_SESSION['tipo_ses']=="Exportacion"){?> selected
					<?}?>>Exportacion</option>
					<option value="Importacion" <?if($_SESSION['tipo_ses']=="Importacion"){?> selected
					<?}?>>Importacion</option>
				</select></td>
				<?}else{?>
				<td><select name="tipo">
					<option value="">(No filtrar)</option>
					<option value="Exportacion" <?if($tipo=="Exportacion"){?> selected
					<?}?>>Exportacion</option>
					<option value="Importacion" <?if($tipo=="Importacion"){?> selected
					<?}?>>Importacion</option>
				</select></td>
				<?}?>
			</tr>
			<tr>
				<th>Vapor</th>
				<td><input name="vapor" type="text" id="vapor" value="<?=$vapor?>"></td>
				<th>Naviera/Aerolinea</th>
				<td><input name="naviera" type="text" id="naviera"
					value="<?=$naviera?>"></td>
			</tr>
			<tr>
				<th>VIA</th>
				<?if(isset($_SESSION['via_ses'])){?>
				<td><select name="via">
					<option value="">(No Filtrar)</option>
					<option value="Aerea" <?if($_SESSION['via_ses']=="Aerea"){?> selected <?}?>>Aerea</option>
					<option value="Maritima" <?if($_SESSION['via_ses']=="Maritima"){?> selected <?}?>>Maritima</option>
					<option value="Terrestre" <?if($_SESSION['via_ses']=="Terrestre"){?> selected <?}?>>Terrestre</option>
				</select></td>
				<?}else{?>
				<td><select name="via">
					<option value="">(No Filtrar)</option>
					<option value="Aerea" <?if($via=="Aerea"){?> selected <?}?>>Aerea</option>
					<option value="Maritima" <?if($via=="Maritima"){?> selected <?}?>>Maritima</option>
					<option value="Terrestre" <?if($via=="Terrestre"){?> selected <?}?>>Terrestre</option>
				</select></td>
				<?}?>
				<th>Estado</th>
				<?if(isset($_SESSION['estado_ses'])){?>
				<td><select name="estado">
					<option value="">(No Filtrar)</option>
					<option value="No Finalizado" <?if($_SESSION['estado_ses']=="No Finalizado"){?>
						selected <?}?>>No Finalizado</option>
					<option value="Finalizado" <?if($_SESSION['estado_ses']=="Finalizado"){?> selected
					<?}?>>Finalizado</option>
				</select></td>
				<?}else{?>
				<td><select name="estado">
					<option value="">(No Filtrar)</option>
					<option value="No Finalizado" <?if($estado=="No Finalizado"){?>
						selected <?}?>>No Finalizado</option>
					<option value="Finalizado" <?if($estado=="Finalizado"){?> selected
					<?}?>>Finalizado</option>
				</select></td>
				<?}?>
			</tr>
			<tr>
				<th>ETA/ATA</th>
				<td><input size="12" onfocus="scwShow(this,event);return false"
					onclick="scwShow(this,event);return false"
					name="fecha_radicacion_inicial" type="text"
					id="fecha_radicacion_inicial"
					value="<?=$fecha_radicacion_inicial?>"> <input size="12"
					onclick="scwShow(this,event);return false"
					onfocus="scwShow(this,event);return false"
					name="fecha_radicacion_final" type="text"
					id="fecha_radicacion_final" value="<?=$fecha_radicacion_final?>"></td>

				<th>ATD</th>
				<td><input size="12" onfocus="scwShow(this,event);return false"
					onclick="scwShow(this,event);return false"
					name="atd" type="text"
					id="atd"
					value="<?=$atd?>"></td>
			</tr>
<tr>
				<th>Exportador</th>
				<td><input name="exportador" type="text" id="exportador" value="<?=$exportador?>"></td>
<th>Importador</th>
				<td><input name="importador" type="text" id="importador" value="<?=$importador?>"></td>

</tr>
<tr>
				<th>Referencia</th>
				<td colspan="3"><input name="referencia" type="text" id="referencia" value="<?=$referencia?>"></td>

</tr>

			<tr>
				<td colspan="4">
				<div align="center"><input name="Accion" type="submit" id="Accion"
					value="Buscar"></div>
				</td>
			</tr>




		</table>
		</td>
	</tr>
	<tr>
		<td>
		<div align="center"><a href="estado-dinamico.php"><img
			src="images/new16x16.gif" width="16" height="16" border="0">Agregar</a></div>
		</td>
	</tr>
	<tr>
		<td>
		<table width="90%" border="1" align="center" cellpadding="2"
			cellspacing="0" bordercolor="#CCCCCC">
			<tr bgcolor="#FF9933">
				<th colspan="39">Listado de embarques</th>
			</tr>
			<tr>
				<th>Exportador</th>
				<th>Importador</th>
				<th>REF 1 Cliente</th>
<th>Radicación de BL</th>
				<th>MAWB</th>
				<th>ETD</th>
				<th>ETA</th>
				<th>ATA</th>
				<th>Destino</th>
				<th>Observaciones</th>

				<th colspan="2"><a
					href="javascript:selectAll('envio','envios[]',true)">Todos</a>/<a
					href="javascript:selectAll('envio','envios[]',false)">Limpiar</a></th>
			</tr>
			<?
			
			

			

				
				$ID_datos = $bd_ID->sql($sql_datos);
				while($row_datos = mysql_fetch_object($ID_datos)){
				?>
			 <tr>
				<td><?=$row_datos->exportador;?></td>
				<td><?=$row_datos->importador;?></td>
				<td><?=$row_datos->referencia_cliente1;?></td>

	<td><?=$row_datos->fecha_radicacion_bls;?></td>
				<td><?=$row_datos->mawb;?></td>
				<td nowrap><?=$row_datos->etd;?></td>
				<td nowrap><?=$row_datos->eta;?></td>
				<td nowrap><?=$row_datos->ata;?></td>
				<td><?=$row_datos->destino_final;?></td>
				<td><?=$row_datos->observaciones;?></td>
				<td>

				<div align="center"><a href="estado-dinamico.php?id=<?=$row_datos->id;?>&cliente=<?=$cliente;?>&pais=<?=$pais;?>&origen=<?=$origen;?>&destino_final=<?=$destino_final;?>&tipo=<?=$tipo;?>&do_ls=<?=$do_ls;?>&vapor=<?=$vapor;?>&naviera=<?=$naviera;?>&via=<?=$via;?>&estado=<?=$estado;?>"><img
					src="images/note16x16.gif" width="15" height="18" border="0"></a></div>
				</td>
				<td><input type="checkbox" name="envios[]"
					value="<?=$row_datos->id;?>" /></td>

			</tr> 
			<?
			}
			
			
			?>
			<?
			$i=0;
			while(isset($_SESSION['datos' . $i])){
			?>
			<!-- <tr>
			<td><?=$_SESSION['datos' . $i]->exportador;?></td>
				<td><?=$_SESSION['datos' . $i]->importador;?></td>
				<td><?=$_SESSION['datos' . $i]->referencia_cliente1;?></td>

	<td><?=$_SESSION['datos' . $i]->fecha_radicacion_bls;?></td>
				<td><?=$_SESSION['datos' . $i]->mawb;?></td>
				<td nowrap><?=$_SESSION['datos' . $i]->etd;?></td>
				<td nowrap><?=$_SESSION['datos' . $i]->eta;?></td>
				<td nowrap><?=$_SESSION['datos' . $i]->ata;?></td>
				<td><?=$_SESSION['datos' . $i]->destino_final;?></td>
				<td><?=$_SESSION['datos' . $i]->observaciones;?></td>
				<td>

				<div align="center"><a href="estado-dinamico.php?id=<?=$_SESSION['datos' . $i]->id;?>&cliente=<?=$cliente;?>&pais=<?=$pais;?>&origen=<?=$origen;?>&destino_final=<?=$destino_final;?>&tipo=<?=$tipo;?>&do_ls=<?=$do_ls;?>&vapor=<?=$vapor;?>&naviera=<?=$naviera;?>&via=<?=$via;?>&estado=<?=$estado;?>"><img
					src="images/note16x16.gif" width="15" height="18" border="0"></a></div>
				</td>
				<td><input type="checkbox" name="envios[]"
					value="<?=$_SESSION['datos' . $i]->id;?>" /></td>
			</tr> -->
			<?
			$i++;
			}?>
		</table>

		</td>
	</tr>
	<tr>
		<td colspan="11" align="center">



		<table width="500" border="1" align="center" cellpadding="2"
			cellspacing="0" bordercolor="#CCCCCC">
			<tr bgcolor="#FF9933">
				<th colspan="4">Enviar notificacion</th>
			</tr>
			<tr>
				<td>Tipo de envio</td>
				<td colspan="2" align="center"><select name="tipoEnvio">
					<option value="Al cliente">Al cliente</option>
					<option value="Interna">Interna</option>
				</select></td>
			</tr>
			<tr>
				<th>Cliente</th>
				<?
				$sql_datos="select email from exponent_user e inner join empresasxenvios em on e.id=em.id_usuario where em.nit='".$cliente."'";
				$ID_datos = $bd_ID->sql($sql_datos);
				?>
				<td>
				<table width="100%">
				<?
				while($row_mail = mysql_fetch_object($ID_datos)){?>
					<tr>
						<td><?=$row_mail->email?></td>
						<td><input type="checkbox" value="<?=$row_mail->email?>"
							name="mail[]" /></td>
					</tr>
					<?}?>
				</table>
				<textarea name="correo_cliente"></textarea></td>


			</tr>
			<tr>
				<th>L.S Group</th>
				<?
				$sql_datos="select email from exponent_user e inner join empresasxenvios em on e.id=em.id_usuario where em.nit='830136560'";
				$ID_datos = $bd_ID->sql($sql_datos);
				?>

				<td>
				<table width="100%">
				<?
				while($row_mail = mysql_fetch_object($ID_datos)){?>
					<tr>
						<td><?=$row_mail->email?></td>
						<td><input type="checkbox" value="<?=$row_mail->email?>"
							name="mail[]" /></td>
					</tr>
					<?}?>
				</table>
				<textarea name="correo_interno"></textarea></td>
			</tr>
			<tr>
				<th>Aduanera Gran Colombiana</th>
				<?
				$sql_datos="select email from exponent_user e inner join empresasxenvios em on e.id=em.id_usuario where em.nit='860028026'";
				$ID_datos = $bd_ID->sql($sql_datos);
				?>

				<td>
				<table width="100%">
				<?
				while($row_mail = mysql_fetch_object($ID_datos)){?>
					<tr>
						<td><?=$row_mail->email?></td>
						<td><input type="checkbox" value="<?=$row_mail->email?>"
							name="mail[]" /></td>
					</tr>
					<?}?>
				</table>
				<textarea name="correo_interno"></textarea></td>
			</tr>
			<tr>
				<td colspan="2" align="center"><input type="hidden" name="id"
					value="<?=$id?>" /><input type="submit" value="Enviar"
					name="Accion" /><br>
				<a href="javascript:selectAll('envio','mail[]',true)">Seleccionar
				Todos</a>/<a href="javascript:selectAll('envio','mail[]',false)">Limpiar
				Seleccion</a></td>
			</tr>

		</table>
		</td>
	</tr>
</table>



</form>
</body>
</html>