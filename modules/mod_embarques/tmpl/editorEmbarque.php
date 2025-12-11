<?php
defined ( '_JEXEC' ) or die ();
$campos = cargarCampos ();
$empresas = consultarEmpresas ( " " );
$empresas_sort = array ();
foreach ( $empresas as $empresa ) {
	$empresas_sort [$empresa->nit] = $empresa->nombre;
}
$otrosPedidos = array();

asort ( $empresas_sort );
$otrosPedidos = array();

if (! empty ( $embarque )) {
 $cliente = cargarEmpresa ( $embarque ['id_cliente'] );
	$otrosPedidos = consultarEmbarquesNoFinalizadoCliente('No',$embarque ['id_cliente']);
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
<?php
$document = JFactory::getDocument();
$document->setTitle("Seguimiento Embarque: ".$embarque['id_cliente']." | ".$embarque["estado"]." | ".$embarque["referencia_cliente1"]);
$groups = $user->get('groups');
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
					value="<?php echo $embarque['id'] ?>">
				<th>Cliente</th>
				<td><?php if($hasAccesstoModify){?><select name="id_cliente" onchange="selectCliente(this)"><option
							value="">(No seleccionado)</option><?php foreach($empresas_sort as $nit => $nombre){?><option
							value="<?php echo $nit?>" <?php if ($embarque["id_cliente"]==$nit){?>
							selected <?php } ?>><?php echo $nombre?><?php }?></select><?php } else {?><?php echo '<p>'.$user->name.' - '.$user->nit. '</p>'; echo '<input type="hidden" name="id_cliente" id="id_cliente" value="'.$user->nit.'"/>'; }?></td>
				<th>Estado</th>
				<td><select name="estado">
						<option value="En programacion"
							<?php if ($embarque["estado"]=="En programacion"){?> selected <?php }?>>En
							programacion</option>
						<option value="Reservado"
							<?php if ($embarque["estado"]=="Reservado"){?> selected <?php }?>>Reservado</option>
						<option value="En transito"
							<?php if ($embarque["estado"]=="En transito"){?> selected <?php }?>>En
							transito</option>
						<option value="Arribo" <?php if ($embarque["estado"]=="Arribo"){?>
							selected <?php }?>>Arribó</option>
						<option value="En proceso aduanero"
							<?php if ($embarque["estado"]=="En proceso aduanero"){?> selected
							<?php }?>>En proceso aduanero</option>
						<option value="Entregado"
							<?php if ($embarque["estado"]=="Entregado"){?> selected <?php }?>>Entregado</option>
						<option value="Finalizado"
							<?php if ($embarque["estado"]=="Finalizado"){?> selected <?php }?>>Finalizado</option>
				</select></td>

			</tr>
			<tr>
				<th>Tipo</th>
				<td><select name="tipo">
						<option value="Exportacion"
							<?php if ($embarque["tipo"]=="Exportacion"){?> selected <?php }?>>Exportacion</option>
						<option value="Importacion"
							<?php if ($embarque["tipo"]=="Importacion"){?> selected <?php }?>>Importacion</option>

				</select></td>
				<th>Via</th>
				<td><select name="via" onchange="cambiar(this.form)">
						<option value="Aerea" <?php if ($embarque["via"]=="Aerea"){?>
							selected <?php }?>>Aerea</option>
						<option value="Maritima" <?php if ($embarque["via"]=="Maritima"){?>
							selected <?php }?>>Maritima</option>
						<option value="Terrestre" <?php if ($embarque["via"]=="Terrestre"){?>
							selected <?php }?>>Terrestre</option>
				</select></td>

			</tr>
			<?php
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
					<?php } ?>
			<th><?php echo $campo->etiqueta; ?></th>

				<td><input <?php if(strcmp($campo->tipo,'Fecha')==0){?> type="date"
					<?php }else{?> type="text" <?php }?> name="<?php echo $campo->nombre; ?>"
					value="<?php echo str_replace(' 00:00:00','',$embarque[$campo->nombre])?>" /></td>
			<?php if($orden==1){?>
			</tr>
					<?php
						}
						$orden ++;
					 }}}?>
			

<tr>
				<th>Operativo</th>
				<td><input type="text" name="operativo" id="operativo"
						value="<?php echo $embarque['operativo']?>" /></td>
						<th>Vendedor</th>
				<td><input type="text" name="vendedor" id="vendedor"
						value="<?php echo $embarque['vendedor']?>" /></td>
			</tr>
			<tr>
				<th>Observaciones</th>
				<td colspan="3"><textarea name="observaciones" id="observaciones"
						size="30"><?php echo $embarque['observaciones']?></textarea></td>
			</tr>
		<?php if($hasAccesstoModify){	?><tr>
				<th colspan="4"><input name="accion" type="hidden" id="accion"
					value="GuardarEmbarque">
					<button type="submit">Guardar</button></th>
			</tr>
<?php }?>
		</tbody>
	</table>

	<table>
		</form>
		</br>
<form method="POST" name="editorEmbarques">
<?php if(!empty($embarque['id'])){?>
<table width="100%" border="1" align="center" cellpadding="2"
			cellspacing="0" bordercolor="#CCCCCC">
			<tr><th colspan="11">Detalles del embarque</th></tr>
			<tr>
				<th></th>
				<th>Factura</th>
				<th>Fecha factura</th>
				<th>Guia/WR</th>
				<th>Valor Total</th>
				<th>Cajas</th>
				<th>Volumen</th>
				<th>Peso</th>
				<th>Fecha WR</th>
				<th>Pedido Original</th>
				<th>To Delete</th>
				<?php if($hasAccesstoModify){	?><th>Accion</th><?php }?>

			</tr>

  <?php
	foreach ( $embarque ['detalles'] as $detalle ) {
		?>
	<?php if(is_null($detalle['todelete'])){?>
  <tr>

	  <td><input type="checkbox" id="id_detalle[]" name="id_detalle[]" value="<?php  echo $detalle['id'];?>"/></td>
	  			<td><?php  echo $detalle['factura'];?></td>
				<td><?php  echo $detalle['fecha_factura'];?></td>
				<td><?php  echo $detalle['guia_wr']; ?></td>
				<td><?php  echo $detalle['valor_total'];?></td>
				<td><?php  echo $detalle['cajas'];?></td>
				<td><?php  echo $detalle['volumen'];?></td>
				<td><?php  echo $detalle['peso'];?></td>
				<td><?php  echo $detalle['fecha_wr']; ?></td>
				<td><?php  echo $detalle['pedido_original'];?></td>
	            <td><?php  echo $detalle['todelete'];?></td>
				<?php if($hasAccesstoModify){	?><td>
					
					<a href="index.php?accion=eliminarDetalleEmbarque&id_detalle=<?php  echo $detalle['id'] ?>&id_embarque=<?php echo $embarque["id"]?>&id_cliente=<?php echo $embarque["id_cliente"]?>">Eliminar</a>
					<br>
					<a href="index.php?accion=editarDetalleEmbarque&id_detalle=<?php  echo $detalle['id'] ?>&id_embarque=<?php echo $embarque["id"]?>&id_cliente=<?php echo $embarque["id_cliente"]?>">Editar</a>
					<br>
					<a href="index.php?accion=verArchivosDetalle&id_detalle=<?php  echo $detalle['id'] ?>&id_embarque=<?php echo $embarque["id"]?>&id_cliente=<?php echo $embarque["id_cliente"]?>" target="_blank">Ver Archivos</a>
				</td>
				<?php }?>

			</tr> 
	<?php }?>
	<?php }?>
	<tr><td colspan="11" align="center" valign="bottom"><select id="pedido_consolidar" name="pedido_consolidar">
		<?php foreach ( $otrosPedidos as  $otroPedido) {?>
		<?php if($otroPedido["id"]<>$embarque["id"]){?>
  <option value="<?php echo $otroPedido["id"]?>"><?php if($otroPedido["do_ls"]<>null){?><?php  echo $otroPedido["do_ls"]?> - <?php }?><?php  echo $otroPedido["referencia_cliente1"]?></option>
		<?php }?>
		<?php }?>
</select><input name="accion" type="hidden" id="accion"
					value="ConsolidarEmbarques">
		<input name="id_embarque_original" type="hidden" id="id_embarque_original"
					value="<?php echo $embarque["id"]?>"><input name="id_cliente" type="hidden" id="id_cliente"
					value="<?php echo $embarque["id_cliente"]?>"><button type="submit">Consolidar</button></td></tr>
  
  
	
  </table>
	
	</form>
  <br>
<form method="POST" name="editorEmbarques">
  <table width="100%" border="1" align="center" cellpadding="2"
			cellspacing="0" bordercolor="#CCCCCC">
			<tr><th colspan="4">Archivos adjuntos</th></tr>
			<tr>
	  <th></th>
				<th>Archivo</th>
				<th>Descripcion</th>
				<th>Accion</th>
			</tr>

  <?php 
	
	foreach ( $embarque ['archivos'] as $detalle ) {
		?>
  <tr>
	  <td><input type="checkbox" name="id_archivo[]" id="id_archivo[]" value="<?php  echo $detalle['id'];?>"/></td>
				<td><a href="/uploads/<?php echo $detalle['archivo'];?>" target="_blank"><?php  echo $detalle['archivo'];?></a></td>
				<td><?php  echo $detalle['descripcion'];?></td>
				<td>					
					<a href="index.php?accion=eliminarArchivo&id_archivo=<?php  echo $detalle['id'] ?>&id_embarque=<?php echo $embarque["id"]?>&id_cliente=<?php echo $embarque["id_cliente"]?>">Eliminar</a>				
				</td>

			</tr> 
	  <?php }?>
<tr>
	<td colspan="4" align="center" valign="bottom"><select id="pedido_consolidar_archivos" name="pedido_consolidar_archivos">
		<?php foreach ( $otrosPedidos as  $otroPedido) {?>
		<?php if($otroPedido["id"]<>$embarque["id"]){?>
  <option value="<?php echo $otroPedido["id"]?>"><?php if($otroPedido["do_ls"]<>null){?><?php  echo $otroPedido["do_ls"]?> - <?php }?><?php  echo $otroPedido["referencia_cliente1"]?></option>
		<?php }?>
		<?php }?>
</select><input name="accion" type="hidden" id="accion"
					value="ConsolidarEmbarquesArchivos">
		<input name="id_embarque_original_archivos" type="hidden" id="id_embarque_original_archivos"
					value="<?php echo $embarque["id"]?>"><input name="id_cliente_archivos" type="hidden" id="id_cliente_archivos"
					value="<?php echo $embarque["id_cliente"]?>"><button type="submit">Consolidar</button></td>
</tr>
  
  
  </table>
</form>
		<br />
		<?php if($hasAccesstoModify){	?>
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
						value="<?php  echo $embarque["id"]?>" /><input name="id_cliente"
						type="hidden" id="id_cliente"
						value="<?php  echo $embarque["id_cliente"]?>" /><input name="accion"
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
						value="<?php  echo $embarque["id"]?>" /><input name="id_cliente"
						type="hidden" id="id_cliente"
						value="<?php  echo $embarque["id_cliente"]?>" />
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
		<?php  }?>
<?php }?>
		
