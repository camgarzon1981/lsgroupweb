<?php
defined ( '_JEXEC' ) or die ();
include 'modules/mod_embarques/views/sections/empresas.php';
require_once dirname ( __FILE__ ) . '/helper.php';
$user = JFactory::getUser ();
$input = new JInput ();
$view = $params->get ( 'view' );
$accion = $input->get ( 'accion', null );
if (strcmp ( $view, "Clientes" ) == 0) {
	if ($accion != null) {
		if (strcmp ( $accion, "Consultar" ) == 0) {
			require JModuleHelper::getLayoutPath ( 'mod_embarques', 'editarCliente' );
		} else if (strcmp ( $accion, "GrabarEmpresa" ) == 0) {
			$empresa = new empresa ();
			$empresa->nit = $input->getString ( 'nit', null );
			$empresa->nombre = $input->getString ( 'nombre', null );
			if (! empty ( $_POST ['campo'] )) {
				$infoCampo = $_POST ['campo'];
				foreach ( $infoCampo as $selected ) {
					$empresa->campos [] = $selected;
				}
			}
			if (! empty ( $_POST ['campo_cliente'] )) {
				$infoCampo = $_POST ['campo_cliente'];
				foreach ( $infoCampo as $selected ) {
					$empresa->campos_cliente [] = $selected;
				}
			}
			if (! empty ( $_POST ['campo_mail'] )) {
				$infoCampo = $_POST ['campo_mail'];
				foreach ( $infoCampo as $selected ) {
					$empresa->campos_mail [] = $selected;
				}
			}
			crearEmpresa ( $empresa );
			require JModuleHelper::getLayoutPath ( 'mod_embarques', 'editarCliente' );
		} else if (strcmp ( $accion, "AsignarUsuario" ) == 0) {
			$empresa = new empresa ();
			$empresa->nit = $input->get ( 'nit', null );
			$empresa->nombre = $input->get ( 'nombre', null );
			crearEmpresa ( $empresa );
			require JModuleHelper::getLayoutPath ( 'mod_embarques', 'editarCliente' );
		} else if (strcmp ( $accion, "MigrarEmpresa" ) == 0) {
			
			migrarEmpresaCampos ( "899999102" );
			/*
			 * $empresa=new empresa();
			 * $empresa->nit=$input->get('nit',null);
			 * $empresa->nombre=$input->get('nombre',null);
			 * crearEmpresa($empresa);
			 * require JModuleHelper::getLayoutPath ( 'mod_embarques', 'editarCliente' );
			 */
		}
	} else {
		$listadoEmpresas = consultarEmpresas ( " " );
		require JModuleHelper::getLayoutPath ( 'mod_embarques', 'listadoClientes' );
	}
} elseif (strcmp ( $view, "Operaciones" ) == 0) {
	if (strcmp ( $accion, "BuscarEmbarquesOperaciones" ) == 0) {		
		$parametros = array ();
		foreach ( $_POST as $key => $value ) {
			if (empty ( $value )) {
			} else {
				if (strcmp ( $key, "accion" ) != 0)
					$parametros [$key] = $value;
			}
		}

		if((!empty($parametros["id_cliente"]))&&(!empty($parametros["estado_id"]))){
			if($parametros["estado_id"]=="Finalizado"){
				$embarques = consultarEmbarquesEstadoCliente ($parametros["estado_id"],$parametros["id_cliente"]);
			}else{				
				$embarques = consultarEmbarquesNoFinalizadoCliente ("En programacion",$parametros["id_cliente"]);				
				
			}
			
		}elseif(!empty($parametros["id_cliente"])){
			$embarques = consultarEmbarquesDynamo ( $parametros );
		}else{
			if($parametros["estado_id"]=="Finalizado"){
				$embarques = consultarEmbarquesEstado ($parametros["estado_id"]);
			}else{
				$embarques = consultarEmbarquesEstado ("En programacion");
				$embarques = array_merge($embarques,consultarEmbarquesEstado ("Reservado"));
				$embarques = array_merge($embarques,consultarEmbarquesEstado ("En transito"));
				$embarques = array_merge($embarques,consultarEmbarquesEstado ("Arribo"));
				$embarques = array_merge($embarques,consultarEmbarquesEstado ("En proceso aduanero"));
				$embarques = array_merge($embarques,consultarEmbarquesEstado ("Entregado"));
			}
		}
		$embarquesFiltrados=array();
		foreach($embarques as $embarque ){
			$dato=json_encode($embarque);
			$keysParametros=array_keys($parametros);
			$bandera=true;	
			foreach($keysParametros as $key ){			
				if($key!='estado_id' and $key!='id_cliente' ){
					$datoComparacion='"'.$key.'":"'.$parametros[$key].'"';
					if (strpos($dato, $datoComparacion) !== false) {
					}else{
						$bandera=false;
					}					
				}
			}
			if($bandera==1){
				$embarquesFiltrados[]=$embarque;
			}
		}
		$session = JFactory::getSession ();
		$session->set ( 'parametros', $parametros );
		$session->set ( 'resultadosBusqueda', $embarquesFiltrados );
		require JModuleHelper::getLayoutPath ( 'mod_embarques', 'listadoEmbarques' );
	} else if (strcmp ( $accion, "ConsultarEmbarque" ) == 0) {
		$embarque = consultarEmbarqueDynamo ( $_GET ['id_embarque'], $_GET ['id_cliente'] );
		require JModuleHelper::getLayoutPath ( 'mod_embarques', 'editorEmbarque' );
	} else if (strcmp ( $accion, "ConsultarEmbarquesAntiguos" ) == 0) {
		$embarques = consultarEmbarquesAntiguos ( $parametros );
		$session = JFactory::getSession ();
		$session->set ( 'resultadosBusqueda', $embarques );
		require JModuleHelper::getLayoutPath ( 'mod_embarques', 'listadoEmbarques' );
	} else if (strcmp ( $accion, "ConsultarEmbarquesAntiguos" ) == 0) {
		$embarques = consultarEmbarquesAntiguos ( $parametros );
		$session = JFactory::getSession ();
		$session->set ( 'resultadosBusqueda', $embarques );
		require JModuleHelper::getLayoutPath ( 'mod_embarques', 'listadoEmbarques' );
	} else if (strcmp ( $accion, "MigrarEmpresa" ) == 0) {
		migrarEmpresaCampos ( "830129024" );
	} else if ($accion == "GuardarEmbarque") {
		$parametros = array ();
		$isNew = "No";
    	
		foreach ( $_POST as $key => $value ) {
        	        
			if ($key == "id_embarque") {
            	echo "Valor id embarque: ".$value."</br>";
				if (empty(trim($value))) {
                	echo "No Encontro el valor para id_embarque ".$value;
					$parametros ['id'] = uniqid();
					$isNew = "Yes";
				} else {                	
                   echo "se encontro valor de id_embarque ";
					$parametros ['id'] = $value;
				}
               echo "Id_embarque ".$parametros ['id'] ;
			}
			if (empty ( $value )) {
			} else {
				if (strcmp ( $key, "accion" ) != 0)
					$parametros [$key] = $value;
			}
		}
		if ($isNew == "No") {
			echo "Embarque Directo";
			$embarque = crearEmbarqueDirecto ( $parametros );
		} else {
			echo "Embarque Dynamo";
			$embarque = crearEmbarqueDynamo ( $parametros );

		}
		
			echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";
		$mensaje = "Embarque grabado con exito!!!!";
		require JModuleHelper::getLayoutPath ( 'mod_embarques', 'listadoEmbarques' );
	} else if ($accion == "NuevoEmbarque") {
		$id_cliente = $input->get ( 'id_cliente', null );
		if (! empty ( $id_cliente )) {
			$embarque = array ();
			$embarque ['id_cliente'] = $id_cliente;
		}
		require JModuleHelper::getLayoutPath ( 'mod_embarques', 'editorEmbarque' );
	} else if ($accion == "AgregarDetalleEmbarque") {
		$parametros = array ();
		$embarque = consultarEmbarqueDynamo ( $_POST ['id_embarque'], $_POST ['id_cliente'] );
		
		$detalle = array ();
		$detalle ["factura"] = $_POST ['factura'];
		$detalle ["id"] = uniqid ();
		$detalle ["guia_wr"] = $_POST ['guia_wr'];
		$detalle ["fecha_factura"] = $_POST ['fecha_factura'];
		$detalle ["valor_total"] = $_POST ['valor_total'];
		$detalle ["cajas"] = $_POST ['cajas'];
		$detalle ["volumen"] = $_POST ['volumen'];
		$detalle ["peso"] = $_POST ['peso'];
		$detalle ["fecha_wr"] = $_POST ['fecha_wr'];
		$detalle ["pedido_original"] = $_POST ['pedido_original'];
		$embarque ["detalles"] [] = $detalle; // array($detalle,$detalle);
		
		$embarque = modificarDetallesEmbarque ( $embarque );
		
		$mensaje = "Embarque grabado con exito!!!!";
		require JModuleHelper::getLayoutPath ( 'mod_embarques', 'editorEmbarque' );
	} else if ($accion == "GrabarDetalleEmbarque") {
		$parametros = array ();
		$embarque = consultarEmbarqueDynamo ( $_POST ['id_embarque'], $_POST ['id_cliente'] );
		for($i = 0; $i < sizeof ( $embarque ['detalles'] ); $i ++) {
			if ($embarque ['detalles'] [$i] ['id'] == $_POST ['id_detalle']) {
				
				$embarque ['detalles'] [$i] ["factura"] = $_POST ['factura'];
				$embarque ['detalles'] [$i] ["guia_wr"] = $_POST ['guia_wr'];
				$embarque ['detalles'] [$i] ["fecha_factura"] = $_POST ['fecha_factura'];
				$embarque ['detalles'] [$i] ["valor_total"] = $_POST ['valor_total'];
				$embarque ['detalles'] [$i] ["cajas"] = $_POST ['cajas'];
				$embarque ['detalles'] [$i] ["volumen"] = $_POST ['volumen'];
				$embarque ['detalles'] [$i] ["peso"] = $_POST ['peso'];
				$embarque ['detalles'] [$i] ["fecha_wr"] = $_POST ['fecha_wr'];
				$embarque ['detalles'] [$i] ["pedido_original"] = $_POST ['pedido_original'];
			}
		}
		
		$embarque = modificarDetallesEmbarque ( $embarque );
		$mensaje = "Embarque grabado con exito!!!!";
		require JModuleHelper::getLayoutPath ( 'mod_embarques', 'editorEmbarque' );
	} else if ($accion=="ConsolidarEmbarques"){
		
		consolidarDynamo($_POST ['id_embarque_original'], $_POST ['pedido_consolidar'], $_POST ['id_detalle'], $_POST ['id_cliente']);
		$embarque = consultarEmbarqueDynamo ( $_POST  ['id_embarque_original'], $_POST  ['id_cliente'] );
		//require JModuleHelper::getLayoutPath ( 'mod_embarques', 'editorEmbarque' );
		require JModuleHelper::getLayoutPath ( 'mod_embarques', 'editorEmbarque' );
	}else if ($accion=="ConsolidarEmbarquesArchivos"){
		
		consolidarDynamoArchivos($_POST ['id_embarque_original_archivos'], $_POST ['pedido_consolidar_archivos'], $_POST ['id_archivo'], $_POST ['id_cliente_archivos']);
		$embarque = consultarEmbarqueDynamo ( $_POST  ['id_embarque_original_archivos'], $_POST  ['id_cliente_archivos'] );
		//require JModuleHelper::getLayoutPath ( 'mod_embarques', 'editorEmbarque' );
		require JModuleHelper::getLayoutPath ( 'mod_embarques', 'editorEmbarque' );
	} else if ($accion == "eliminarDetalleEmbarque") {		
		$parametros = array ();
		$embarque = consultarEmbarqueDynamo ( $_GET ['id_embarque'], $_GET ['id_cliente'] );
		$detalles=$embarque['detalles'];
		
			for($i = 0; $i <= sizeof ( $detalles); $i++) {
				if ($detalles [$i]['id'] == $_GET ['id_detalle']) {
					echo "Borrando el detalle ".$i."<br>";
					unset($detalles[$i]);
					}
			}
			$detalles=array_filter($detalles);
			$embarque['detalles']=$detalles;
				
		$embarque=modificarDetallesEmbarque($embarque);
		
		$mensaje = "Embarque grabado con exito!!!!";
		require JModuleHelper::getLayoutPath ( 'mod_embarques', 'editorEmbarque' );
	} else if ($accion == "editarDetalleEmbarque") {
		$parametros = array ();
		$embarque = consultarEmbarqueDynamo ( $_GET ['id_embarque'], $_GET ['id_cliente'] );
		$i = 0;
		$detalle = "";
		foreach ( $embarque ['detalles'] as $detalleTemp ) {
			/*echo '<br>';
			echo($_GET['id_detalle'].' i: '.$i);
			echo '<br>';
			var_dump($detalleTemp);*/
			if (strcmp($detalleTemp ['id'], $_GET['id_detalle'])==0) {
				$detalle = $detalleTemp;
				
			}
			$i ++;
		}
		require JModuleHelper::getLayoutPath ( 'mod_embarques', 'editorDetalle' );
	} else if ($accion == "migrador") {
		require JModuleHelper::getLayoutPath ( 'mod_embarques', 'migrador' );
	} else if ($accion == "MigrarEmpresas") {
		migrarEmpresas ( $_POST ['inicial'], $_POST ['cantidad'] );
		require JModuleHelper::getLayoutPath ( 'mod_embarques', 'migrador' );
	} else if ($accion == "MigrarEmbarques") {
		consultarEmbarquesAntiguos ( $_POST ["id_cliente"] );
		$mensaje = "Cliente Migrado!!!!";
		require JModuleHelper::getLayoutPath ( 'mod_embarques', 'migrador' );
	} else if ($accion == "MigrarEmbarquesRango") {
		consultarEmbarquesAntiguosRango ( $_POST ["inicial"], $_POST ["cantidad"] );
		$mensaje = "Embarques Migrados!!!!";
		require JModuleHelper::getLayoutPath ( 'mod_embarques', 'migrador' );
	} else if ($accion == "AgregarEnvio") {
		$embarque = consultarEmbarqueDynamo ( $_GET ['id_embarque'], $_GET ['id_cliente'] );
		$session = JFactory::getSession ();
		$datos = $session->get ( "embarquesParaEnviar" );
		if (empty ( $datos )) {
			$datos = array ();
		}
		$datos [] = $embarque;
		$session->set ( "embarquesParaEnviar", $datos );
		echo "<script>window.close();</script>";
	} else if ($accion == "EliminarEnvio") {
		eliminarEmbarque ( $_GET ['id_embarque'], $_GET ['id_cliente'] );
		require JModuleHelper::getLayoutPath ( 'mod_embarques', 'listadoEmbarques' );
	} else if ($accion == "EnviarMailPreview") {
		$email [] = $_POST ['email'];
		$tipoEnvio = $_POST ['tipoEnvio'];
		if ($tipoEnvio == "Interno") {
		}
		if ($tipoEnvio == "Cliente") {
		}
		crearMensaje ( $tipoEnvio, $email );
	} else if ($accion == "MigrarUsuarios") {
		migarUsuarios ();
	} else if ($accion == "ConsultarEmbarquesAEnviar") {
		require JModuleHelper::getLayoutPath ( 'mod_embarques', 'embarquesParaEnviar' );
	} else if ($accion=="AgregarTodasEmbarquesAEnviar"){
		$session = JFactory::getSession ();
		$datos = $session->get ( "embarquesParaEnviar" );
		if (empty ( $datos )) {
			$datos = array ();
		}
		$datos = array_merge($datos, $session->get('resultadosBusqueda'));
		$session->set ( "embarquesParaEnviar", $datos );
		require JModuleHelper::getLayoutPath ( 'mod_embarques', 'listadoEmbarques' );
	} else if ($accion=="LimpiarEmbarquesAEnviar"){
		$session = JFactory::getSession ();		
		$session->set ( "embarquesParaEnviar", null );
		require JModuleHelper::getLayoutPath ( 'mod_embarques', 'listadoEmbarques' );
	}  else if($accion=="AgregarArchivo"){
		$target_dir = "uploads/";
		echo $_FILES["archivo"]["size"];
		$tmp_name=$_FILES["archivo"]["tmp_name"];
		$target_file = $target_dir . basename($_FILES["archivo"]["name"]);
		$uploadOk = 1;
		$parametros = array ();
		$embarque = consultarEmbarqueDynamo ( $_POST ['id_embarque'], $_POST ['id_cliente'] );
		$archivo = array ();
		$archivo ["descripcion"] = $_POST ['descripcion'];
		$archivo ["archivo"] = $_FILES["archivo"]["name"];
		$archivo ["id"] = uniqid ();
		$embarque ["archivos"] [] = $archivo; // array($detalle,$detalle);
		move_uploaded_file($tmp_name, $target_name."".$target_file);
		$embarque=modificarDetallesEmbarque($embarque);

		require JModuleHelper::getLayoutPath ( 'mod_embarques', 'editorEmbarque' );
	}
	else if($accion=="eliminarArchivo"){
	$parametros = array ();
		$embarque = consultarEmbarqueDynamo ( $_GET ['id_embarque'], $_GET ['id_cliente'] );
		$detalles=$embarque['archivos'];
		
			for($i = 0; $i <= sizeof ( $detalles); $i++) {
				if ($detalles [$i]['id'] == $_GET ['id_archivo']) {
					echo "Borrando el archivo ".$i."<br>";
					unset($detalles[$i]);
					}
			}
			$detalles=array_filter($detalles);
			$embarque['archivos']=$detalles;
				
		$embarque=modificarDetallesEmbarque($embarque);
		
		$mensaje = "Embarque grabado con exito!!!!";
		require JModuleHelper::getLayoutPath ( 'mod_embarques', 'editorEmbarque' );
	}
	else {
		// echo "No capturo la accion: ".$accion;
		require JModuleHelper::getLayoutPath ( 'mod_embarques', 'listadoEmbarques' );
	}
} elseif (strcmp ( $view, "Usuario" ) == 0) {
	echo "Usuario";
}
?>