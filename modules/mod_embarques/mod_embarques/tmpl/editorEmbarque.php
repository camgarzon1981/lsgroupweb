<?php
defined ( '_JEXEC' ) or die ();
$campos = cargarCampos ();
$empresas = consultarEmpresas ( " " );
$empresas_sort = array ();
foreach ( $empresas as $empresa ) {
	$empresas_sort [$empresa->nit] = $empresa->nombre;
}
asort ( $empresas_sort );
if (! empty ( $embarque )) {
 $cliente = cargarEmpresa ( $embarque ['id_cliente'] );
}
$campos = cargarCampos ();
$script = 'jQuery(document).ready(function(){
jQuery("#editorEmbarques").hide();';
foreach ( $embarque ["detalles"] as $detalle ) {
	$script=$script.'
	jQuery("#editarDetalleButton'.$detalle["id"].'").on(function(){	
		jQuery("#editorEmbarques").show();
		return false; 
	});';
}
$script = $script . '}';
JFactory::getDocument ()->addScriptDeclaration ( $script );
?>
<script>
function selectCliente(parametro){
	window.location.href="/index.php/es/cobertura-3/sistema-de-embarques/embarques?accion=NuevoEmbarque&id_cliente=" +parametro.options[parametro.selectedIndex].value;
}
</script>
<?
$document = JFactory::getDocument();
$document->setTitle("Seguimiento Embarque: ".$embarque['id_cliente']." | ".$embarque["estado"]." | ".$embarque["referencia_cliente1"]);
?>
<? $groups = $user->get('groups');
$hasAccesstoModify=false;
foreach ($groups as $group)
{
	if($group==8 or $group==12 or $group==11 or $group==10){
		$hasAccesstoModify=true;
	}
    
}?>
<form method="POST" name="editorEmbarques">
	<table width="80%" border="1" align="center" cellpadding="2"
		cellspacing="0" bordercolor="#CCCCCC">
		<tbody>
			<tr bgcolor="#FF9933">
				<th colspan="4">Nuevo DO</th>
			</tr>
			<tr>
				<input name="id_embarque" type="hidden" id="id_embarque"
					value="<? echo $embarque["id"]?>">
				<th>Cliente</th>
				<td><?if($hasAccesstoModify){?><select name="id_cliente" onchange="selectCliente(this)"><option
							value="">(No seleccionado)</option><?foreach($empresas_sort as $nit => $nombre){?><option
							value="<?echo $nit?>" <? if ($embarque["id_cliente"]==$nit){?>
							selected <?}?>><?echo $nombre?><?}?></select><?} else {?><?echo '<p>'.$user->name.' - '.$user->nit. '</p>'; echo '<input type="hidden" name="id_cliente" id="id_cliente" value="'.$user->nit.'"/>'; }?></td>
				<th>Estado</th>
				<td><select name="estado">
						<option value="En programacion"
							<? if ($embarque["estado"]=="En programacion"){?> selected <?}?>>En
							programacion</option>
						<option value="Reservado"
							<? if ($embarque["estado"]=="Reservado"){?> selected <?}?>>Reservado</option>
						<option value="En transito"
							<? if ($embarque["estado"]=="En transito"){?> selected <?}?>>En
							transito</option>
						<option value="Arribo" <? if ($embarque["estado"]=="Arribo"){?>
							selected <?}?>>Arribó</option>
						<option value="En proceso aduanero"
							<? if ($embarque["estado"]=="En proceso aduanero"){?> selected
							<?}?>>En proceso aduanero</option>
						<option value="Entregado"
							<? if ($embarque["estado"]=="Entregado"){?> selected <?}?>>Entregado</option>
						<option value="Finalizado"
							<? if ($embarque["estado"]=="Finalizado"){?> selected <?}?>>Finalizado</option>
				</select></td>

			</tr>
			<tr>
				<th>Tipo</th>
				<td><select name="tipo">
						<option value="Exportacion"
							<? if ($embarque["tipo"]=="Exportacion"){?> selected <?}?>>Exportacion</option>
						<option value="Importacion"
							<? if ($embarque["tipo"]=="Importacion"){?> selected <?}?>>Importacion</option>

				</select></td>
				<th>Via</th>
				<td><select name="via" onchange="cambiar(this.form)">
						<option value="Aerea" <? if ($embarque["via"]=="Aerea"){?>
							selected <?}?>>Aerea</option>
						<option value="Maritima" <? if ($embarque["via"]=="Maritima"){?>
							selected <?}?>>Maritima</option>
						<option value="Terrestre" <? if ($embarque["via"]=="Terrestre"){?>
							selected <?}?>>Terrestre</option>
				</select></td>

			</tr>
			<?
			$orden = 0;
			$json_campos = json_encode ( $cliente->campos );
			foreach ( $campos as $campo ) {
				if ($campo->nombre != "nombre" and $campo->nombre != "via" and $campo->nombre != "tipo" and $campo->nombre != "observaciones") {
					if (strpos ( $json_campos, $campo->nombre )) {
						if ($orden == 2) {
							$orden = 0;
						}
						if ($orden == 0) {
							?>
			<tr>
					<?}?>
			<th><? echo $campo->etiqueta; ?></th>

				<td><input <?if(strcmp($campo->tipo,'Fecha')==0){?> type="date"
					<?}else{?> type="text" <?}?> name="<? echo $campo->nombre; ?>"
					value="<?echo str_replace(' 00:00:00','',$embarque[$campo->nombre])?>" /></td>
			<?if($orden==1){?>
			</tr>
					<?
						}
						$orden ++;
						?>
				<?}}?>
			<?}?>
			

<tr>
				<th>Operativo</th>
				<td><input type="text" name="operativo" id="operativo"
						value="<? echo $embarque['operativo']?>" /></td>
						<th>Vendedor</th>
				<td><input type="text" name="vendedor" id="vendedor"
						value="<? echo $embarque['vendedor']?>" /></td>
			</tr>
			<tr>
				<th>Observaciones</th>
				<td colspan="3"><textarea name="observaciones" id="observaciones"
						size="30"><? echo $embarque['observaciones']?></textarea></td>
			</tr>
		<?if($hasAccesstoModify){	?><tr>
				<th colspan="4"><input name="accion" type="hidden" id="accion"
					value="GuardarEmbarque">
					<button type="submit">Guardar</button></th>
			</tr>
<?}?>
		</tbody>
	</table>

	<table>
		</form>
		</br>

<?if(!empty($embarque['id'])){?>
<table width="100%" border="1" align="center" cellpadding="2"
			cellspacing="0" bordercolor="#CCCCCC">
			<tr><th colspan="10">Detalles del embarque</th></tr>
			<tr>
				<th>Factura</th>
				<th>Fecha factura</th>
				<th>Guia/WR</th>
				<th>Valor Total</th>
				<th>Cajas</th>
				<th>Volumen</th>
				<th>Peso</th>
				<th>Fecha WR</th>
				<th>Pedido Original</th>
				<?if($hasAccesstoModify){	?><th>Accion</th><?}?>

			</tr>

  <?
	foreach ( $embarque ['detalles'] as $detalle ) {
		?>
  <tr>

				<td><? echo $detalle['factura'];?></td>
				<td><? echo $detalle['fecha_factura'];?></td>
				<td><? echo $detalle['guia_wr']; ?></td>
				<td><? echo $detalle['valor_total'];?></td>
				<td><? echo $detalle['cajas'];?></td>
				<td><? echo $detalle['volumen'];?></td>
				<td><? echo $detalle['peso'];?></td>
				<td><? echo $detalle['fecha_wr']; ?></td>
				<td><? echo $detalle['pedido_original'];?></td>
				<?if($hasAccesstoModify){	?><td>
					
					<a href="index.php?accion=eliminarDetalleEmbarque&id_detalle=<? echo $detalle['id'] ?>&id_embarque=<?echo $embarque["id"]?>&id_cliente=<?echo $embarque["id_cliente"]?>">Eliminar</a>
					<br>
					<a href="index.php?accion=editarDetalleEmbarque&id_detalle=<? echo $detalle['id'] ?>&id_embarque=<?echo $embarque["id"]?>&id_cliente=<?echo $embarque["id_cliente"]?>">Editar</a>
					<br>
					<a href="index.php?accion=verArchivosDetalle&id_detalle=<? echo $detalle['id'] ?>&id_embarque=<?echo $embarque["id"]?>&id_cliente=<?echo $embarque["id_cliente"]?>" target="_blank">Ver Archivos</a>
				</td>
				<?}?>

			</tr> 
  
  <?}?>
  </table>
  <br>
  <table width="100%" border="1" align="center" cellpadding="2"
			cellspacing="0" bordercolor="#CCCCCC">
			<tr><th colspan="3">Archivos adjuntos</th></tr>
			<tr>
				<th>Archivo</th>
				<th>Descripcion</th>
				<th>Accion</th>
			</tr>

  <?
	
	foreach ( $embarque ['archivos'] as $detalle ) {
		?>
  <tr>

				<td><a href="/uploads/<?echo $detalle['archivo'];?>" target="_blank"><? echo $detalle['archivo'];?></a></td>
				<td><? echo $detalle['descripcion'];?></td>
				<td>					
					<a href="index.php?accion=eliminarArchivo&id_archivo=<? echo $detalle['id'] ?>&id_embarque=<?echo $embarque["id"]?>&id_cliente=<?echo $embarque["id_cliente"]?>">Eliminar</a>				
				</td>

			</tr> 
  
  <?}?>
  </table>

		<br />
		<?if($hasAccesstoModify){	?>
		<table width="100%">
		<tr><td width="50%">
		<table align="center" border="1">
			<tr>
				<th colspan="4">Nuevo detalle de embarque</th>
			</tr>
			<form method="POST" name="nuevoDetallesEmbarques">
				<tr>
					<th>Factura</th>
					<td><input type="text" name="factura" size="2" /></td>
					<th>Fecha Factura</th>
					<td><input type="date" name="fecha_factura" size="10" /></td>
				</tr>
				<tr>
					<th>Guia WR</th>
					<td><input type="text" name="guia_wr" size="10" /></td>
					<th>Valor Total</th>
					<td><input type="number" name="valor_total" size="10" /></td>
				</tr>
				<tr>
					<th>Cajas</th>
					<td><input type="number" name="cajas" size="10" /></td>
					<th>Volumen</th>
					<td><input type="text" name="volumen" size="10" /></td>
				</tr>
				<tr>
					<th>Peso</th>
					<td><input type="text" name="peso" size="10" /></td>
					<th>Fecha WR</th>
					<td><input type="date" name="fecha_wr" size="10" /></td>
				
				
				<tr>
					<th>Pedido original</th>
					<td><input type="text" name="pedido_original" size="10" /></td>
					<th></th>
					<td></td>
				</tr>
				<tr>
					<td colspan="4" align="center"><input name="id_embarque"
						type="hidden" id="id_embarque"
						value="<? echo $embarque["id"]?>" /><input name="id_cliente"
						type="hidden" id="id_cliente"
						value="<? echo $embarque["id_cliente"]?>" /><input name="accion"
						type="hidden" id="accion" value="AgregarDetalleEmbarque">
						<button type="submit">Guardar</button></td>
				</tr>
				</tr>
			</form>

		</table></td><td valign="top">
			<form enctype="multipart/form-data" method="post">
				<table width="100%" align="center" border="1">
					<tr>
						<th colspan="2">Carga de archivos</th>
					</tr>
					<tr>
						<th>Archivo</th>
						<td>
							<input type="file" name="archivo" id="archivo"/>
						</td>
					</tr>
					<tr>
						<th>Descripcion</th>
						<td>
							<textarea name="descripcion" id="descripcion"> </textarea>
						</td>
					</tr>
					<tr>
						<td colspan="2" align="center">
							<input name="id_embarque"
						type="hidden" id="id_embarque"
						value="<? echo $embarque["id"]?>" /><input name="id_cliente"
						type="hidden" id="id_cliente"
						value="<? echo $embarque["id_cliente"]?>" />
						<input name="accion"
						type="hidden" id="accion" value="AgregarArchivo">
							<input type="submit" name="cargar" id="cargar" value="Cargar archivo">
						</td>
					</tr>
				</table>
			</form>
		</td>
		</tr>
		</table>
		<?}?>
<?}?>
		
