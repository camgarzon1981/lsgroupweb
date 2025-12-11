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

$sql_datos="select destino_final,tipo,e.id as id,estado,exportador,importador,referencia_cliente1,mawb,puerto_embarque,destino_final,CONCAT(YEAR(etd),'-',MONTH(etd),'-',DAY(etd)) as etd,CONCAT(YEAR(eta),'-',MONTH(eta),'-',DAY(eta)) as eta,CONCAT(YEAR(ata),'-',MONTH(ata),'-',DAY(ata)) as ata,observaciones from embarques_seguimiento e  where origen like '%".$origen."%' and destino_final like '%".$destino_final."%' and naviera like '%".$naviera."%' and vapor like '%".$vapor."%' and referencia_cliente1 like '%".$referencia."%' and id_cliente like '%".$id_usuario."%' ".$filtro_estado;
if($tipo=="Importacion"){
$sql_datos=$sql_datos." and tipo like 'Importacion'";
}else if($tipo=="Exportacion"){
$sql_datos=$sql_datos." and tipo like 'Exportacion'";
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
		<table width="500" border="1" align="center" cellpadding="2"
			cellspacing="0" bordercolor="#CCCCCC">
			<tr bgcolor="#FF9933">
				<th colspan="4">Buscar DO's - <?=$_SESSION['usuario']->lastname?></th>
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
				<th>Referencia</th>
				<td><input name="referencia" type="text" id="referencia" value="<?=$referencia?>"></td>
				<th>Tipo</th>
<td><select name="tipo">
<option value="">(No filtrar)</option>
<option value="Exportacion" <?if($tipo=="Exportacion"){?>selected<?}?>>Exportación</option>
<option value="Importacion" <?if($tipo=="Importacion"){?>selected<?}?>>Importación</option>
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
            <td colspan="4"><div align="center"> 
                <input name="Accion" type="submit" id="Accion" value="Buscar">
              </div></td>
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
				<th>Exportador</th>
				<th>Importador</th>
				<th>REF 1 Cliente</th>
				<th>MAWB</th>
				<th>ETD</th>
				<th>ETA</th>				
				<th>ATA</th>
				<th>Destino</th>
				<th>Observaciones</th>
				
				<th>&nbsp;</th>
			</tr>
			<?
if($Accion=="Buscar"){
$ID_datos = $bd_ID->sql($sql_datos);
while($row_datos = mysql_fetch_object($ID_datos)){?>
			<tr>
				<td><?=$row_datos->exportador;?></td>
				<td><?=$row_datos->importador;?></td>
				<td><?=$row_datos->referencia_cliente1;?></td>
				<td><?=$row_datos->mawb;?></td>
				<td nowrap><?=$row_datos->etd;?></td>
				<td nowrap><?=$row_datos->eta;?></td>
				<td nowrap><?=$row_datos->ata;?></td>
				<td><?=$row_datos->destino_final;?></td>
				<td><?=$row_datos->observaciones;?></td>			
				<td>
				<div align="center"><a href="estado_cliente.php?id=<?=$row_datos->id;?>&referencia=<?=$referencia;?>&pais=<?=$pais;?>&origen=<?=$origen;?>&destino_final=<?=$destino_final;?>&tipo=<?=$tipo;?>&do_ls=<?=$do_ls;?>&vapor=<?=$vapor;?>&naviera=<?=$naviera;?>&via=<?=$via;?>&estado=<?=$estado;?>"><img
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
