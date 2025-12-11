<?php
defined ( '_JEXEC' ) or die ();
$campos = cargarCampos ();
asort ( $empresas_sort );
if (! empty ( $embarque )) {
	$cliente = cargarEmpresa ( $embarque ['id_cliente'] );
}
$campos = cargarCampos ();
$script = 'jQuery(document).ready(function(){
jQuery("#editorEmbarques").hide();';
foreach ( $embarque ["detalles"] as $detalleTemp ) {
	$script=$script.'
	jQuery("#editarDetalleButton'.$detalleTemp["id"].'").on(function(){	
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
?>

<form method="POST" name="editorEmbarques">
	<table width="80%" border="1" align="center" cellpadding="2"
		cellspacing="0" bordercolor="#CCCCCC">
		<tbody>
			<tr bgcolor="#FF9933">
				<th colspan="4">Consulta DO</th>
			</tr>
			<tr>
				<input name="id_embarque" type="hidden" id="id_embarque"
					value="<?php  echo $embarque["id"]?>">
				<th>Cliente</th>
				<td><?php  echo $embarque["id_cliente"]?></td>
				<th>Estado</th>
				<td><?php  echo $embarque["estado"] ?></td>

			</tr>
			<tr>
				<th>Tipo</th>
				<td><?php  echo $embarque["tipo"] ?></td>
				<th>Via</th>
				<td><?php  echo $embarque["via"] ?></td>

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
					<?php }?>
			<th><?php  echo $campo->etiqueta; ?></th>

				<td><?php echo str_replace(' 00:00:00','',$embarque[$campo->nombre])?></td>
			<?php if($orden==1){?>
			</tr>
					<?php 
						}
						$orden ++;
						?>
				<?php }}?>
			<?php }?>
			

<tr>
				<th>Operativo</th>
				<td><?php  echo $embarque['operativo']?></td>
						<th>Vendedor</th>
				<td><?php  echo $embarque['vendedor']?></td>
			</tr>
			<tr>
				<th>Observaciones</th>
				<td colspan="3"><?php  echo $embarque['observaciones']?></td>
			</tr>
		
		</tbody>
	</table>

	<table>
		</form>
		</br>

<?php if(!empty($embarque['id'])){?>
<table width="100%" border="1" align="center" cellpadding="2"
			cellspacing="0" bordercolor="#CCCCCC">
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
				<th>Accion</th>

			</tr>

  <?php 
	
	foreach ( $embarque ['detalles'] as $detalleTemp ) {
		?>
  <tr>

				<td><?php  echo $detalleTemp['factura'];?></td>
				<td><?php  echo $detalleTemp['fecha_factura'];?></td>
				<td><?php  echo $detalleTemp['guia_wr']; ?></td>
				<td><?php  echo $detalleTemp['valor_total'];?></td>
				<td><?php  echo $detalleTemp['cajas'];?></td>
				<td><?php  echo $detalleTemp['volumen'];?></td>
				<td><?php  echo $detalleTemp['peso'];?></td>
				<td><?php  echo $detalleTemp['fecha_wr']; ?></td>
				<td><?php  echo $detalleTemp['pedido_original'];?></td>
				<td>
					
					<a href="index.php?accion=eliminarDetalleEmbarque&id_detalle=<?php  echo $detalleTemp['id'] ?>&id_embarque=<?php echo $embarque["id"]?>&id_cliente=<?php echo $embarque["id_cliente"]?>">Eliminar</a>
					<br>
					<a href="index.php?accion=editarDetalleEmbarque&id_detalle=<?php  echo $detalleTemp['id'] ?>&id_embarque=<?php echo $embarque["id"]?>&id_cliente=<?php echo $embarque["id_cliente"]?>">Editar</a>
				</td>

			</tr> 
  
  <?php }?>
  </table>
		<div id="editorEmbarques">
		<br/>
			<table align="center" border="1">
				<tr>
					<th colspan="4">Editor detalle de embarque</th>
				</tr>
				<form method="POST" name="nuevoDetallesEmbarques">
					<tr>
						<th>Factura</th>
						<td><input type="text" name="factura" id="factura"
							size="2" value="<?php echo $detalle['factura']?>"/></td>
						<th>Fecha Factura</th>
						<td><input type="date" name="fecha_factura"
							id="fecha_factura" size="10" value="<?php echo $detalle['fecha_factura']?>" /></td>
					</tr>
					<tr>
						<th>Guia WR</th>
						<td><input type="text" name="guia_wr" id="guia_wr"
							size="10" value="<?php echo $detalle['guia_wr']?>"/></td>
						<th>Valor Total</th>
						<td><input type="number" name="valor_total"
							id="valor_total" size="10" value="<?php echo $detalle['valor_total']?>"/></td>
					</tr>
					<tr>
						<th>Cajas</th>
						<td><input type="number" name="cajas" id="cajas"
							size="10" value="<?php echo $detalle['cajas']?>"/></td>
						<th>Volumen</th>
						<td><input type="text" name="volumen" id="volumen"
							size="10" value="<?php echo $detalle['volumen']?>"/></td>
					</tr>
					<tr>
						<th>Peso</th>
						<td><input type="text" name="peso" id="peso" size="10" value="<?php echo $detalle['peso']?>"/></td>
						<th>Fecha WR</th>
						<td><input type="date" name="fecha_wr" id="fecha_wr"
							size="10" value="<?php echo $detalle['fecha_wr']?>"/></td>
					
					
					<tr>
						<th>Pedido original</th>
						<td><input type="text" name="pedido_original"
							id="pedido_original" size="10" value="<?php echo $detalle['pedido_original']?>"/></td>
						<th></th>
						<td></td>
					</tr>
					<tr>
						<td colspan="4" align="center"><input name="id_embarque"
							type="hidden" id="id_embarque"
							value="<?php  echo $embarque["id"]?>" /><input name="id_cliente"
							type="hidden" id="id_cliente"
							value="<?php  echo $embarque["id_cliente"]?>" /><input name="id_detalle"
							type="hidden" id="id_detalle"
							value="<?php  echo $detalle['id']?>" /><input name="accion"
							type="hidden" id="accion" value="GrabarDetalleEmbarque">
							<button type="submit">Guardar</button></td>
					</tr>
					</tr>
				</form>

			</table>
		</div>
<?php }?>