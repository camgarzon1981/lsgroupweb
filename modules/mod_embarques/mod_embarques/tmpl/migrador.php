<?php 
defined ( '_JEXEC' ) or die ();
$campos = cargarCampos ();
$empresas=consultarEmpresas(" ");
$empresas_sort=array();
foreach($empresas as $empresa){
	$empresas_sort[$empresa->nit]=$empresa->nombre;
}
asort($empresas_sort);
$session = JFactory::getSession();
$embarques=$session->get('resultadosBusqueda');
?>
<h4><? echo $mensaje; ?></h4>
<form method="POST" name="buscadorEmbarquesForm"><table align="center" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC" border="1" width="500">
			<tbody><tr bgcolor="#FF9933">
				<th colspan="4">Migrador</th>
			</tr>
			<tr>
				<th>Cliente</th>
								<td><select name="id_cliente"><option value="">(No seleccionado)</option><?foreach($empresas_sort as $nit => $nombre){?><option value="<?echo $nit?>"><?echo $nombre?><?}?></select></td>
				
			</tr>
			
			<tr>
				<td colspan="4">
				<div align="center"><input type="hidden" name="accion" id="accion" value="MigrarEmbarques" />
	<button type="submit">Migrar</button></div>
				</td>
			</tr>
			</table>
</form>
<form method="POST" name="buscadorEmbarquesForm">
<div align="center"><input type="hidden" name="accion" id="accion" value="MigrarEmpresas" />
	<input type=number id="inicial" name="inicial"/>
	- <input type=number id="cantidad" name="cantidad" value="10"/><button type="submit">Migrar Empresas</button></div>
</form>

<form method="POST" name="buscadorEmbarquesForm">
<div align="center"><input type="hidden" name="accion" id="accion" value="MigrarEmbarquesRango" />
	<input type=number id="inicial" name="inicial"/>
	- <input type=number id="cantidad" name="cantidad" value="10"/><button type="submit">Migrar Embarques</button></div>
</form>