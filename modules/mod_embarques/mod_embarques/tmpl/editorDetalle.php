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
<?
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
					value="<? echo $embarque["id"]?>">
				<th>Cliente</th>
				<td><? echo $embarque["id_cliente"]?></td>
				<th>Estado</th>
				<td><? echo $embarque["estado"] ?></td>

			</tr>
			<tr>
				<th>Tipo</th>
				<td><? echo $embarque["tipo"] ?></td>
				<th>Via</th>
				<td><? echo $embarque["via"] ?></td>

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

				<td><?echo str_replace(' 00:00:00','',$embarque[$campo->nombre])?></td>
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
				<td><? echo $embarque['operativo']?></td>
						<th>Vendedor</th>
				<td><? echo $embarque['vendedor']?></td>
			</tr>
			<tr>
				<th>Observaciones</th>
				<td colspan="3"><? echo $embarque['observaciones']?></td>
			</tr>
		
		</tbody>
	</table>

	<table>
		</form>
		</br>

<?if(!empty($embarque['id'])){?>
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

  <?
	
	foreach ( $embarque ['detalles'] as $detalleTemp ) {
		?>
  <tr>

				<td><? echo $detalleTemp['factura'];?></td>
				<td><? echo $detalleTemp['fecha_factura'];?></td>
				<td><? echo $detalleTemp['guia_wr']; ?></td>
				<td><? echo $detalleTemp['valor_total'];?></td>
				<td><? echo $detalleTemp['cajas'];?></td>
				<td><? echo $detalleTemp['volumen'];?></td>
				<td><? echo $detalleTemp['peso'];?></td>
				<td><? echo $detalleTemp['fecha_wr']; ?></td>
				<td><? echo $detalleTemp['pedido_original'];?></td>
				<td>
					
					<a href="index.php?accion=eliminarDetalleEmbarque&id_detalle=<? echo $detalleTemp['id'] ?>&id_embarque=<?echo $embarque["id"]?>&id_cliente=<?echo $embarque["id_cliente"]?>">Eliminar</a>
					<br>
					<a href="index.php?accion=editarDetalleEmbarque&id_detalle=<? echo $detalleTemp['id'] ?>&id_embarque=<?echo $embarque["id"]?>&id_cliente=<?echo $embarque["id_cliente"]?>">Editar</a>
				</td>

			</tr> 
  
  <?}?>
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
							size="2" value="<?echo $detalle['factura']?>"/></td>
						<th>Fecha Factura</th>
						<td><input type="date" name="fecha_factura"
							id="fecha_factura" size="10" value="<?echo $detalle['fecha_factura']?>" /></td>
					</tr>
					<tr>
						<th>Guia WR</th>
						<td><input type="text" name="guia_wr" id="guia_wr"
							size="10" value="<?echo $detalle['guia_wr']?>"/></td>
						<th>Valor Total</th>
						<td><input type="number" name="valor_total"
							id="valor_total" size="10" value="<?echo $detalle['valor_total']?>"/></td>
					</tr>
					<tr>
						<th>Cajas</th>
						<td><input type="number" name="cajas" id="cajas"
							size="10" value="<?echo $detalle['cajas']?>"/></td>
						<th>Volumen</th>
						<td><input type="text" name="volumen" id="volumen"
							size="10" value="<?echo $detalle['volumen']?>"/></td>
					</tr>
					<tr>
						<th>Peso</th>
						<td><input type="text" name="peso" id="peso" size="10" value="<?echo $detalle['peso']?>"/></td>
						<th>Fecha WR</th>
						<td><input type="date" name="fecha_wr" id="fecha_wr"
							size="10" value="<?echo $detalle['fecha_wr']?>"/></td>
					
					
					<tr>
						<th>Pedido original</th>
						<td><input type="text" name="pedido_original"
							id="pedido_original" size="10" value="<?echo $detalle['pedido_original']?>"/></td>
						<th></th>
						<td></td>
					</tr>
					<tr>
						<td colspan="4" align="center"><input name="id_embarque"
							type="hidden" id="id_embarque"
							value="<? echo $embarque["id"]?>" /><input name="id_cliente"
							type="hidden" id="id_cliente"
							value="<? echo $embarque["id_cliente"]?>" /><input name="id_detalle"
							type="hidden" id="id_detalle"
							value="<? echo $detalle['id']?>" /><input name="accion"
							type="hidden" id="accion" value="GrabarDetalleEmbarque">
							<button type="submit">Guardar</button></td>
					</tr>
					</tr>
				</form>

			</table>
		</div>
<?}?>
		
