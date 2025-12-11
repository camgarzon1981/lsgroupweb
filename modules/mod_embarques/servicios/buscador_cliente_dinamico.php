<?
ini_set("session.gc_maxlifetime","86400");
include_once ("ut_bd_site.php");
include_once ("funciones.php");
session_start();
include_once ("validador.php");
$filtro_estado="";
if($estado=="Finalizado"){
	$filtro_estado="AND estado = 'Finalizado' ";
}else if($estado=="No Finalizado"){
	$filtro_estado="AND estado <> 'Finalizado' ";

}

if ($bd_Servidor != "localhost") {
	session_save_path($_SERVER['DOCUMENT_ROOT']);
}
$id_usuario=$_SESSION['usuario']->firstname;
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
$sql_datos=" (naviera like '%".$naviera."%' or naviera is null) and (vapor like '%".$vapor."%' or vapor is null) and (origen like '%".$origen."%' or origen is null) and (do_ls like '%".$do_ls."%' or do_ls is null) and (destino_final like '%".$destino_final."%' or destino_final is null) and (via like '%".$via."%' or via is null) and (exportador like '%".$exportador."%' or exportador is null)  and (importador like '%".$importador."%' or importador is null) and ((referencia_cliente1 like '%".$referencia."%' or referencia_cliente1 is null) and (referencia_cliente2 like '%".$referencia."%' or referencia_cliente2 is null))  ".$filtro_estado." ".$filtro_fecha;

if($tipo=="Importacion"){
	$sql_datos=$sql_datos." and tipo like 'Importacion'";
}else if($tipo=="Exportacion"){
	$sql_datos=$sql_datos." and tipo like 'Exportacion'";

}
//echo $sql_datos."</br>";

//$sql_datos="select destino_final,tipo,e.id as id,estado,exportador,importador,referencia_cliente1,mawb,puerto_embarque,destino_final,CONCAT(YEAR(etd),'-',MONTH(etd),'-',DAY(etd)) as etd,CONCAT(YEAR(eta),'-',MONTH(eta),'-',DAY(eta)) as eta,CONCAT(YEAR(ata),'-',MONTH(ata),'-',DAY(ata)) as ata,observaciones from embarques_seguimiento e  where  (via like '%".$via."%' or via is null) and (proveedor like '%".$proveedor."%' or proveedor is null) and (pais like '%".$pais."%' or pais is null) and (origen like '%".$origen."%' or origen is null) and (destino_final like '%".$destino_final."%' or destino_final is null) and (naviera like '%".$naviera."%' or naviera is null) and (vapor like '%".$vapor."%' or vapor is null) and (referencia_cliente1 like '%".$referencia."%' or referencia_cliente1 is null) and id_cliente like '%".$id_usuario."%' ".$filtro_estado;
//$sql_datos_ed="(via like '%".$via."%' or via is null) and (exportador like '%".$exportador."%' or exportador is null) and (pais like '%".$pais."%' or pais is null) and (origen like '%".$origen."%' or origen is null) and (destino_final like '%".$destino_final."%' or destino_final is null) and (naviera like '%".$naviera."%' or naviera is null) and (vapor like '%".$vapor."%' or vapor is null) and (referencia_cliente1 like '%".$referencia."%' or referencia_cliente1 is null)  ".$filtro_estado;
$sql_datos_ed=$sql_datos;
//echo $sql_datos_ed;
if($tipo=="Importacion"){
$sql_datos_ed=$sql_datos_ed." and tipo like 'Importacion'";
}else if($tipo=="Exportacion"){
$sql_datos_ed=$sql_datos_ed." and tipo like 'Exportacion'";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>LS Group - Buscador</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

</head>
<link href="estilo.css" rel="stylesheet" type="text/css">
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" border="0" align="right" cellpadding="2"
	cellspacing="0">
	<tr>
		<td>
		<form name="form1" method="post" action="">
En linea: <?=$_SESSION['usuario']->lastname?><br><a href="/index.php">Regresar al home</a>
<table width="100%" border="0" align="right" cellpadding="2"
	cellspacing="0">
	<tr>
		<td>

		<table width="500" border="1" align="center" cellpadding="2"
			cellspacing="0" bordercolor="#CCCCCC">
			<tr bgcolor="#FF9933">
				<th colspan="4">Buscar DO's</th>
			</tr>
			<tr>
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
				<td><select name="tipo">
					<option value="">(No filtrar)</option>
					<option value="Exportacion" <?if($tipo=="Exportacion"){?> selected
					<?}?>>Exportación</option>
					<option value="Importacion" <?if($tipo=="Importacion"){?> selected
					<?}?>>Importación</option>
				</select></td>
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
				<td><select name="via">
					<option value="">(No Filtrar)</option>

					<option value="Aerea" <?if($via=="Aerea"){?> selected <?}?>>Aerea</option>
					<option value="Maritima" <?if($via=="Maritima"){?> selected <?}?>>Maritima</option>
					<option value="Terrestre" <?if($via=="Terrestre"){?> selected <?}?>>Terrestre</option>
				</select></td>
				<th>Estado</th>
				<td><select name="estado">
					<option value="">(No Filtrar)</option>

					<option value="No Finalizado" <?if($estado=="No Finalizado"){?>
						selected <?}?>>No Finalizado</option>
					<option value="Finalizado" <?if($estado=="Finalizado"){?> selected
					<?}?>>Finalizado</option>
				</select></td>
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

<div align="center">

BIENVENIDO al sistema de informacion de LS GROUP , para obtener informacion de todos sus embarques por favor haga click en buscar sin ingresar ningun dato. <br>
Si quiere obtener informacion de un embarque en especial, ingrese los datos y haga click en buscar. <br>
Para obtener información detallada de cada uno de los embarques por favor haga click en el icono naranja al frente de cada embarque. <br>

</div>
		</form>
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
<?
$sql_datos="SELECT cam.nombre, cam.etiqueta, cam.tipo, cam.obligatorio, cam.contenido, cam.tipo_contenido FROM camposxusuario c inner join campos cam on c.id_campo=cam.id and listado='Si' and id_usuario like '".$id_usuario."'";
$ID_datos = $bd_ID->sql($sql_datos);
				$sql_estado_din="SELECT id,estado,via,tipo";
				while($row_campos = mysql_fetch_object($ID_datos)){
?>
				<th><?=$row_campos->etiqueta?></th>
				
<?}?>

				<th>&nbsp;</th>
			</tr>
			<?
if($Accion=="Buscar"){
$sql_datos="SELECT cam.nombre, cam.etiqueta, cam.tipo, cam.obligatorio, cam.contenido, cam.tipo_contenido FROM camposxusuario c inner join campos cam on c.id_campo=cam.id and listado='Si' and id_usuario like '".$id_usuario."'";
$ID_datos = $bd_ID->sql($sql_datos);

$sql_estado_din="SELECT id,estado,via,tipo";
				while($row_campos = mysql_fetch_object($ID_datos)){
					if($row_campos->tipo=="Fecha"){
					$sql_estado_din=$sql_estado_din.",CONCAT(YEAR(".$row_campos->nombre."),'-',MONTH(".$row_campos->nombre."),'-',DAY(".$row_campos->nombre.")) as ".$row_campos->nombre;
					}else{
					$sql_estado_din=$sql_estado_din.",".$row_campos->nombre;
					}
				}
				$sql_estado_din=$sql_estado_din." from embarques_seguimiento where id_cliente=".$id_usuario." and ".$sql_datos_ed;				
				$result= $bd_ID->sql($sql_estado_din);

//echo $sql_estado_din;
while($row = mysql_fetch_array($result, MYSQL_BOTH)){?>
			<tr>
<?$sql_datos="SELECT cam.nombre, cam.etiqueta, cam.tipo, cam.obligatorio, cam.contenido, cam.tipo_contenido FROM camposxusuario c inner join campos cam on c.id_campo=cam.id and listado='Si' and id_usuario like '".$id_usuario."'";
$ID_datos = $bd_ID->sql($sql_datos);
$contador=0;
				while($row_campos = mysql_fetch_object($ID_datos)){?>

				<td><?=$row[$contador+4]?></td>
				
<?
$contador++;
}?>
				<td>
				<div align="center"><a href="estado_cliente_dinamico.php?id=<?=$row[0]?>&referencia=<?=$referencia;?>&pais=<?=$pais;?>&origen=<?=$origen;?>&destino_final=<?=$destino_final;?>&tipo=<?=$tipo;?>&do_ls=<?=$do_ls;?>&vapor=<?=$vapor;?>&naviera=<?=$naviera;?>&via=<?=$via;?>&estado=<?=$estado;?>"><img
					src="images/note16x16.gif" width="15" height="18" border="0"></a></div>
				</td>
			</tr>
			<?}}?>
		</table>
		</td>
	</tr>
</table>


<div align="center"><br>
<br>
</div>
</body>
</html>
