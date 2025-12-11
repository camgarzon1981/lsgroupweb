<?php
defined ( '_JEXEC' ) or die ();
$nit = $input->get ( 'nit', null );
if($nit!=null){
$empresaCargada = cargarEmpresa ( $nit );
}
?>
<form method="POST" name="clienteForm">
<div>
	<h2>Empresa</h2>
	<div></div>
	
		<fieldset>
			<label for="nit">Nit</label> <input id="nit" name="nit" type="number"
				value="<? print $empresaCargada->nit?>"> <label for="nombre">Nombre</label>
			<input id="nombre" value="<? print $empresaCargada->nombre?>"
				name="nombre" type="text">
			
		</fieldset>
		

</div>



<div>
<script language="JavaScript">
function selectAll(source,objeto) {
  checkboxes = document.getElementsByName(objeto);
  for(var checkbox in checkboxes)
    checkbox.checked = source.checked;
}
</script>
<form method="POST" name="camposForm">
	<h3>Seleccion Campos</h3>
	<table>
		<tr>
			<th>Nombre</th>
			<th>General Operaciones</th>
			<th>Visualizacion cliente</th>
			<th>Resumen Mail</th>
			
			
		</tr><?php 
		$campos = cargarCampos (); 
		
		$stringCamposOperaciones= json_encode($empresaCargada->campos);
		/*echo "<br>Campos operaciones<br>";
		var_dump($stringCamposOperaciones);*/
		$stringCamposCliente= json_encode($empresaCargada->campos_cliente);
		/*echo "<br>Campos cliente<br>";
		var_dump($stringCamposCliente);*/
		$stringCamposMail= json_encode($empresaCargada->campos_mail);
		/*echo "<br>Campos mail<br>";
		var_dump($stringCamposMail);*/
		foreach ( $campos as $campo ) {?>		
		<tr>
			<td>
			<?php echo trim($campo->etiqueta); ?>
			</td>
			<?php if (strpos ($stringCamposOperaciones,'"'.$campo->nombre.'"')!=false) {?>
			<td align='center'><input type='checkbox' name='campo[]' id='campo' value='<?php echo $campo->nombre; ?>' checked /></td>
			<?php  } else {?>
			<td align='center'><input type='checkbox' name='campo[]' id='campo' value='<?php echo $campo->nombre; ?>' /></td>
			<?php  } 
			if (strpos ($stringCamposCliente,'"'.$campo->nombre.'"')!=false) {?>
			<td align='center'>
			<input type='checkbox' id='campo_cliente' name='campo_cliente[]' value='<?php  echo $campo->nombre; ?>' checked /></td>
			<?php  } else {	?>
			<td align='center'>
			<input type='checkbox' id='campo_cliente' name='campo_cliente[]' value='<?php  echo $campo->nombre; ?>' /></td>
			<?php } 
			if (strpos ($stringCamposMail,'"'.$campo->nombre.'"')!=false) { ?> 
			<td align='center'>
			<input type='checkbox' id='campo_mail' name='campo_mail[]' value='<?php echo $campo->nombre; ?>' checked /></td>
			<?php  } else { ?>
			<td align='center'>
			<input type='checkbox' id='campo_mail'	name='campo_mail[]' value='<?php  echo $campo->nombre; ?>' /></td>
			<?php   }?>
		</tr>
		<tr><td><table ></table></td>
		</tr>
		<?php   }?>
	</table>
	<input type="hidden" name="accion" id="accion" value="GrabarEmpresa" />
	<button type="submit">Guardar</button>
		</form>
</div>