<?PHP
//ini_set("session.gc_maxlifetime","86400");
include_once ("ut_bd_site.php");

function consultarComboUsuarios ($bd_ID)
{
	$combo="<select name='cliente'>";
	$sql_datos="select nit,nombre from empresas order by nombre";
	$ID_datos = $bd_ID->sql($sql_datos);
	while ($row_clientes = mysql_fetch_object($ID_datos)){
		$combo=$combo."<option value='".$row_clientes->nit."'>".$row_clientes->nombres;
		$combo=$combo."</option>";
	}
	$combo=$combo."</select>";
	return $combo;
}
function consultarComboUsuariosB ($bd_ID,$cliente)
{
	$combo="<select name='cliente'>";
	$sql_datos="select nit ,nombres from empresas order by nombres";
	$ID_datos = $bd_ID->sql($sql_datos);
	$combo=$combo."<option value='%%'>(No seleccionado)</option>";
	while ($row_clientes = mysql_fetch_object($ID_datos)){

		if($cliente==$row_clientes->nit){
			$combo=$combo."<option value='".$row_clientes->nit."' selected>".$row_clientes->nombres;
		}else{
			$combo=$combo."<option value='".$row_clientes->nit."'>".$row_clientes->nombres;
		}
		$combo=$combo."</option>";
	}
	$combo=$combo."</select>";
	return $combo;
}

function consultarComboUsuariosCambio ($bd_ID,$cliente)
{
	$combo="<select name='id_cliente' onchange='selectCliente(this);'>";
	$sql_datos="select nit ,nombres from empresas order by nombres";
	$ID_datos = $bd_ID->sql($sql_datos);
	$combo=$combo."<option value=''>(No seleccionado)</option>";
	while ($row_clientes = mysql_fetch_object($ID_datos)){

		if($cliente==$row_clientes->nit){
			$combo=$combo."<option value='".$row_clientes->nit."' selected >".$row_clientes->nombres;
		}else{
			$combo=$combo."<option value='".$row_clientes->nit."'>".$row_clientes->nombres;
		}
		$combo=$combo."</option>";
	}
	$combo=$combo."</select>";
	return $combo;
}

function consultarComboEstados ($bd_ID)
{
	$combo="<select name='estado'>";
	$sql_datos="select id,nombre from estados_embarques";
	$ID_datos = $bd_ID->sql($sql_datos);
	while ($row_estados = mysql_fetch_object($ID_datos)){
		$combo=$combo."<option value='".$row_estados->id."'>".$row_estados->nombre;
		$combo=$combo."</option>";
	}
	$combo=$combo."</select>";
	return $combo;
}

function crearDO($bd_ID,$d_o, $pais,$contenedor, $pos, $poa,$vapor, $naviera,$fcl,$lcl,$etd,$eta,$mbl,$hbl,$fecha_agencia,$fecha_naviera,$fecha_dian,$comentario,$estado, $id_importador,$ciudad_origen,$ciudad_destino, $proveedor,$pedido, $incoterm,$pod, $aerolinea, $peso, $volumen, $ata,$zona_aduanera, $tipo, $fecha_pre_alerta, $fecha_zona_aduanera)
{

	$registro="insert into embarques(d_o,pais,contenedor,pos,poa,vapor,naviera,fcl,lcl,etd,eta,mbl,hbl,fecha_agencia,fecha_naviera,fecha_dian,observaciones,id_estado,id_importador,ciudad_origen,ciudad_destino,proveedor,pedido, incoterm,pod, aerolinea, peso, volumen, ata,zona_aduanera,tipo, fecha_pre_alerta, fecha_zona_aduanera) values('".$d_o."','".$pais."','".$contenedor."','".$pos."','".$poa."','".$vapor."','".$naviera."','".$fcl."','".$lcl."','".$etd."','".$eta."','".$mbl."','".$hbl."','".$fecha_agencia."','".$fecha_naviera."','".$fecha_dian."','".$comentario."','".$estado."','".$id_importador."','".$ciudad_origen."','".$ciudad_destino."','". $proveedor."','".$pedido."','". $incoterm."','".$pod."','". $aerolinea."','". $peso."','". $volumen."','". $ata."','".$zona_aduanera."','". $tipo."','". $fecha_pre_alerta."','". $fecha_zona_aduanera."')";

	$bd_ID->sql($registro);

}
function eliminarDO($bd_ID,$id){
	$registro="delete from embarques_seguimiento where id=".$id;

	$bd_ID->sql($registro);
}

function actualizarDO($bd_ID,$id,$d_o, $pais,$contenedor, $pos, $poa,$vapor, $naviera,$fcl,$lcl,$etd,$eta,$mbl,$hbl,$fecha_agencia,$fecha_naviera,$fecha_dian,$comentario,$estado, $cliente,$ciudad_origen,$ciudad_destino, $proveedor,$pedido, $incoterm,$pod, $aerolinea, $peso, $volumen, $ata,$zona_aduanera, $tipo, $fecha_pre_alerta, $fecha_zona_aduanera){
	$registro="update embarques set d_o='".$d_o."' ,pais='".$pais."' ,contenedor='".$contenedor."',pos='".$pos."',poa='".$poa."',vapor='".$vapor."',naviera='".$naviera."',fcl='".$fcl."',lcl='".$lcl."',etd='".$etd."',eta='".$eta."',mbl='".$mbl."',hbl='".$hbl."',fecha_agencia='".$fecha_agencia."',fecha_naviera='".$fecha_naviera."',fecha_dian='".$fecha_dian."',observaciones='".$comentario."',id_estado='".$estado."',id_importador='".$cliente."',ciudad_origen='".$ciudad_origen."',ciudad_destino='".$ciudad_destino."',proveedor='".$proveedor."',pedido='".$pedido."',incoterm='". $incoterm."',pod='".$pod."',aerolinea='". $aerolinea."', peso='".$peso."', volumen='".$volumen."',ata='".$ata."', zona_aduanera='".$zona_aduanera."',tipo='". $tipo."', fecha_pre_alerta='".$fecha_pre_alerta."',fecha_zona_aduanera='". $fecha_zona_aduanera."' where id=".$id;
	$bd_ID->sql($registro);
}

function crear($bd_ID,$estado,$exportador,$importador,$asesor,$via,$do_ls,$referencia_cliente1,$referencia_cliente2,$lc,$fecha_entrega_estimada,$fecha_entrega_proveedor,$pickup,$ingreso_agente,$mawb,$hawb,$fecha_radicacion_bls,$fletes,$origen,$puerto_embarque,$destino_final,$vapor,$viaje,$naviera,$cantidad,$tipo_contenedor,$cutoff,$etd,$eta,$ata,$desconsolidacion,$inspeccion,$ingreso_deposito,$facturar_a,$entrega_documentos_a,$fecha_dec_importacion,$pago_tributos,$levante,$entrega_cliente,$observaciones,$cliente,$tipo){
echo "Fecha".$fecha_estimada_entrega;
	if($fecha_estimada_entrega=''){
	echo "Entro al if";
		$fecha_estimada_entrega='0000-00-00';
	}
	if($fecha_entrega_proveedor=''){
		$fecha_entrega_proveedor='0000-00-00';
	}
	if($pickup=''){
		$pickup='0000-00-00';
	}
	if($ingreso_agente=''){
		$ingreso_agente='0000-00-00';
	}
	if($fecha_radicacion_bls=''){
		$fecha_radicacion_bls='0000-00-00';
	}
	if($cutoff=''){
		$fecha_radicacion_bls='0000-00-00';
	}
	$registro="insert into embarques_seguimiento(estado,exportador,importador,asesor,via,do_ls,referencia_cliente1,referencia_cliente2,lc,fecha_estimada_entrega,fecha_entrega_proveedor,pickup,ingreso_agente,mawb,hawb,fecha_radicacion_bls,fletes,origen,puerto_embarque,destino_final,vapor,viaje,naviera,cantidad,tipo_contenedor,cutoff,etd,eta,ata,desconsolidacion,inspeccion,ingreso_deposito,facturar_a,entrega_documentos_a,fecha_dec_importacion,pago_tributos,levante,entrega_cliente,observaciones,id_cliente,tipo) values ('".$estado."','".$exportador."','".$importador."','".$asesor."','".$via."','".$do_ls."','".$referencia_cliente1."','".$referencia_cliente2."','".$lc."','".$fecha_entrega_estimada."','".$fecha_entrega_proveedor."','".$pickup."','".$ingreso_agente."','".$mawb."','".$hawb."','".$fecha_radicacion_bls."','".$fletes."','".$origen."','".$puerto_embarque."','".$destino_final."','".$vapor."','".$viaje."','".$naviera."','".$cantidad."','".$tipo_contenedor."','".$cutoff."','".$etd."','".$eta."','".$ata."','".$desconsolidacion."','".$inspeccion."','".$ingreso_deposito."','".$facturar_a."','".$entrega_documentos_a."','".$fecha_dec_importacion."','".$pago_tributos."','".$levante."','".$entrega_cliente."','".$observaciones."','".$cliente."','".$tipo."')";
echo $registro;
	$bd_ID->sql($registro);
}
function crearGenerico($bd_ID,$parametros){
	$cadenaInsert="";
	$cadenaParametros="";

	foreach($parametros as $nombre_campo=>$valor){
	
		if($nombre_campo!="Accion"){
			if(strcmp($valor,"")!=0){
				$cadenaInsert=$cadenaInsert.",".$nombre_campo;
				$campo=consultarCampo($bd_ID,$nombre_campo);
				if(strcmp($campo->tipo,"Fecha")==0){					
					if(strcmp($valor,"")==0){
						$valor='0000-00-00';
					}
				}
				
				if(strcmp($campo->tipo,"Fecha")==0 or strcmp($campo->tipo,"Texto")==0 or strcmp($campo->tipo,"Lista")==0){
					$valor="'".$valor."'";
				}
				$cadenaParametros=$cadenaParametros.",".$valor;
			}

		}
	} 	
	$registro="insert into embarques_seguimiento(".substr($cadenaInsert,1)." ) values (".substr($cadenaParametros,1).")";
echo $registro;
	$bd_ID->sql($registro);
}
function actualizarGenerico($bd_ID,$parametros){
	$cadenaUpdate="";
	$cadenaParametros="";

	foreach($parametros as $nombre_campo=>$valor){
	
		if($nombre_campo!="Accion" && $nombre_campo!="id" && $nombre_campo!="cliente"){
			if(strcmp($valor,"")!=0){
				$cadenaUpdate=$cadenaUpdate.",".$nombre_campo."=";
				$campo=consultarCampo($bd_ID,$nombre_campo);
				if(strcmp($campo->tipo,"Fecha")==0){					
					if(strcmp($valor,"")==0){
						$valor='0000-00-00';
					}
				}
				
				if(strcmp($campo->tipo,"Fecha")==0 or strcmp($campo->tipo,"Texto")==0 or strcmp($campo->tipo,"Lista")==0){
					$valor="'".$valor."'";
				}
				$cadenaUpdate=$cadenaUpdate." ".$valor;
			}

		}

		if($nombre_campo=="id"){
			$id=$valor;
		}

	} 	
	$registro="update embarques_seguimiento set ".substr($cadenaUpdate,1)." where id=".$id;
//echo $registro;
	$bd_ID->sql($registro);
}

function crearGenericoDetalle($bd_ID,$id_embarque,$parametros){
	$cadenaInsert="";
	$cadenaParametros="";

	foreach($parametros as $nombre_campo=>$valor){
		if($nombre_campo!="Accion"){
			if(strcmp($valor,"")!=0){
				$cadenaInsert=$cadenaInsert.",".$nombre_campo;
				$campo=consultarCampo($bd_ID,$nombre_campo);
				if(strcmp($campo->tipo,"Fecha")==0){					
					if(strcmp($valor,"")==0){
						$valor='0000-00-00';
					}
				}
				
				if(strcmp($campo->tipo,"Fecha")==0 or strcmp($campo->tipo,"Texto")==0 or strcmp($campo->tipo,"Lista")==0){
					$valor="'".$valor."'";
				}
				$cadenaParametros=$cadenaParametros.",".$valor;
			}

		}
	} 	
	$registro="insert into embarques_detalles(id_embarque,".substr($cadenaInsert,1)." ) values (".$id_embarque.",".substr($cadenaParametros,1).")";
echo $registro;
//	$bd_ID->sql($registro);
}

function actualizar($bd_ID,$estado,$exportador,$importador,$asesor,$via,$do_ls,$referencia_cliente1,$referencia_cliente2,$lc,$fecha_estimada_entrega,$fecha_entrega_proveedor,$pickup,$ingreso_agente,$mawb,$hawb,$fecha_radicacion_bls,$fletes,$origen,$puerto_embarque,$destino_final,$vapor,$viaje,$naviera,$cantidad,$tipo_contenedor,$cutoff,$etd,$eta,$ata,$desconsolidacion,$inspeccion,$ingreso_deposito,$facturar_a,$entrega_documentos_a,$fecha_dec_importacion,$pago_tributos,$levante,$entrega_cliente,$observaciones,$id,$tipo){
	$registro="update embarques_seguimiento set estado='".$estado."',exportador='".$exportador."',importador='".$importador."',asesor='".$asesor."',via='".$via."',do_ls='".$do_ls."',referencia_cliente1='".$referencia_cliente1."',referencia_cliente2='".$referencia_cliente2."',lc='".$lc."',fecha_estimada_entrega='".$fecha_estimada_entrega."',fecha_entrega_proveedor='".$fecha_entrega_proveedor."',pickup='".$pickup."',ingreso_agente='".$ingreso_agente."',mawb='".$mawb."',hawb='".$hawb."',fecha_radicacion_bls='".$fecha_radicacion_bls."',fletes='".$fletes."',origen='".$origen."',puerto_embarque='".$puerto_embarque."',destino_final='".$destino_final."',vapor='".$vapor."',viaje='".$viaje."',naviera='".$naviera."',cantidad='".$cantidad."',tipo_contenedor='".$tipo_contenedor."',cutoff='".$cutoff."',etd='".$etd."',eta='".$eta."',ata='".$ata."',desconsolidacion='".$desconsolidacion."',inspeccion='".$inspeccion."',ingreso_deposito='".$ingreso_deposito."',facturar_a='".$facturar_a."',entrega_documentos_a='".$entrega_documentos_a."',fecha_dec_importacion='".$fecha_dec_importacion."',pago_tributos='".$pago_tributos."',levante='".$levante."',entrega_cliente='".$entrega_cliente."',observaciones='".$observaciones."',tipo='".$tipo."' where id=".$id;
	$bd_ID->sql($registro);
}

function creararchivo($bd_ID,$id_embarque,$nombre,$descripcion,$archivo,$id_detalle){
echo $registro;
	if(strcmp($id_detalle,"")==0){
$id_detalle='null';
}
if($id_detalle==NULL){
$id_detalle='null';

}
	$registro="insert into archivos(id_embarque,nombre, descripcion, archivo,id_detalle) values (".$id_embarque.",'".$nombre."','".$descripcion."','".$archivo."',".$id_detalle.")";
echo $registro;
	$bd_ID->sql($registro);
}
function eliminararchivo($bd_ID,$id){
	$registro="delete from archivos where id=".$id;
	echo $registro;
	$bd_ID->sql($registro);
}
function asignarCampo($bd_ID,$campo, $usuario,$orden){
	/*$updateorden="update camposxusuarios set orden=orden+1 where id_usuario='".$usuario."' and order>=".$orden;
	 $bd_ID->sql($updateorden);*/
	$registro="insert into camposxusuario(id_campo,id_usuario, orden) values(".$campo.",".$usuario.",".$orden.")";
	$bd_ID->sql($registro);
}
function asignarCampoDetalle($bd_ID,$campo, $usuario,$orden){
	/*$updateorden="update camposxusuarios set orden=orden+1 where id_usuario='".$usuario."' and order>=".$orden;
	 $bd_ID->sql($updateorden);*/
	$registro="insert into camposxusuariodetalle(id_campo,id_usuario, orden) values(".$campo.",".$usuario.",".$orden.")";
	$bd_ID->sql($registro);
}

function asignarCampoForma($bd_ID,$campo, $usuario,$orden){
	$registro="update camposxusuario set forma='Si' where id_campo=".$campo." and id_usuario=".$usuario;
//echo $registro."<br>";
	$bd_ID->sql($registro);
}
function asignarCampoListado($bd_ID,$campo, $usuario,$orden){
	$registro="update camposxusuario set listado='Si' where id_campo=".$campo." and id_usuario=".$usuario;
//echo $registro."<br>";
	$bd_ID->sql($registro);
}
function asignarCampoMail($bd_ID,$campo, $usuario,$orden){
	$registro="update camposxusuario set mail='Si' where id_campo=".$campo." and id_usuario=".$usuario;
//echo $registro."<br>";
	$bd_ID->sql($registro);
}
function limpiarCampos($bd_ID, $usuario,$orden){
	$registro="update camposxusuario set forma='No',listado='No',mail='No' where id_campo=".$campo.",id_usuario=".$usuario;
	$bd_ID->sql($registro);
}


function asignarEnvio($bd_ID,$id_cliente, $nit){
	/*$updateorden="update camposxusuarios set orden=orden+1 where id_usuario='".$usuario."' and order>=".$orden;
	 $bd_ID->sql($updateorden);*/
	$registro="insert into empresasxenvios(id_usuario,nit) values(".$id_cliente.",".$nit.")";	
	$bd_ID->sql($registro);
}
function eliminarCampo($bd_ID,$campo, $usuario,$orden){
	$registro="delete from camposxusuarios where id_usuario='".$usuario."' and id_campo='".$campo."'";
	$bd_ID->sql($registro);
	/*$updateorden="update camposxusuarios set orden=orden-1 where id_usuario='".$usuario."' and order>=".$orden;
	 $bd_ID->sql($updateorden);*/
}

function consultarCampos($bd_ID, $usuario){
	$registro="select campo from camposxusuario where $id_usuario='".usuario."' order by orden";
	$ID_datos=$bd_ID->sql($registro);
	$campos;
	while ($row_datos = mysql_fetch_object($ID_datos)){
		$campos[]=$row_datos->campo;
	}
	return $campos;
}

function tienePermiso($bd_ID,$usuario,$campo){
	$registro="select id_campo from camposxusuario where id_usuario='".$usuario."' and id_campo=".$campo;
	$ID_datos=$bd_ID->sql($registro);
	if ($row_datos = mysql_fetch_object($ID_datos)){
		return true;
	}
	return false;
}
function consultarClientes($bd_ID,$cadena){
	$registro="select nit from empresas where nombres like '%".$cadena."%' order by nombres";
	$ID_datos=$bd_ID->sql($registro);
	$usuarios;
	while ($row_datos = mysql_fetch_object($ID_datos)){
		$usuarios[]=$row_datos->nit;
	}
	return $usuarios;
}


function enviarMailHTML($destinos,$mensaje){
$mensaje.= "<br>Enviado el " . date('d/m/Y', time());
/*	require_once "Mail.php";
	require_once "mail/Mime.php";*/
	$from = " LSGROUP <lsgroup@lsupplier.com>";
	$reply = "lsgroup@lsupplier.com";
	$to = "camilo.garzon@seft.net, liliana@seft.net ";
	foreach($destinos as $destino){
		if($destino!=""){
			
			$to=$to.",".$destino;
		}
	}
	$subject = "Seguimiento Embarque";

	$host = "localhost";
	$username = "correoweb@seft.net";
	$password = "web";

$headers  = "MIME-Version: 1.0\r\n"; 
$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 
$headers .= "To: ".$to."\r\n"; 
$headers .= "From:".$from."\r\n"; 
$headers .= "Cc: \r\n"; 
$headers .= "Bcc: \r\n"; 
$headers.=" Return-Path: sender@yahoo.com \r\n";



/*echo $to;
echo "<br>";
echo $headers;
echo "<br>";
echo $subject;
echo "<br>";
echo $mensaje;
echo "<br>";*/
         if (mail($to, $subject, $mensaje, $headers)) {
echo "sent";
}else{
echo "error";
}

	/*$headers = array ('From' => $from,
'To' => $to,
'Reply-to' => $reply,
'Subject' => $subject,'MIME-Version: 1.0','Content-type: text/html; charset=iso-8859-1');

	$smtp = Mail::factory('smtp',
	array ('host' => $host,
'auth' => true,
'username' => $username,
'password' => $password));
	$crlf = "\r\n";
	$mime = new Mail_mime();
	$mime->setHtmlBody($mensaje);
	$body=$mime->get();
	$headers=$mime->headers($headers);

	$mail = $smtp->send($to, $headers, $body);

	if (PEAR::isError($mail)) {
		echo("<p>" . $mail->getMessage() . "</p>");
	} else {
		echo("<p>El mensaje fue enviado!</p>");

	}*/
echo "Termino de enviar el mail";
}

function crearCampo($bd_ID,$nombre, $etiqueta,$tipo,$obligatorio,$orden){
	$sql="select max(orden)+1 as orden from campos";
	$ID_datos = $bd_ID->sql($sql);
	if($row_datos = mysql_fetch_object($ID_datos)){
		$orden=$row_datos->orden;
	}
	$sql="insert into campos(nombre, etiqueta,tipo, obligatorio, orden) values ('".$nombre."', '".$etiqueta."','".$tipo."','".$obligatorio."',".$orden.")";
	$bd_ID->sql($sql);
	$sql="alter table embarques_seguimiento add column ".$nombre;
	if(strcmp($tipo,"Texto")==0){
		$sql=$sql." varchar(200)";
	}else if(strcmp($tipo,"Fecha")==0){
		$sql=$sql." datetime";
	}else if(strcmp($tipo,"Numero")==0){
		$sql=$sql." decimal";
	}
	$bd_ID->sql($sql);
}

function crearCampoDetalle($bd_ID,$nombre, $etiqueta,$tipo,$obligatorio,$orden){
	$sql="select max(orden)+1 as orden from camposdetalle";
	$ID_datos = $bd_ID->sql($sql);
	if($row_datos = mysql_fetch_object($ID_datos)){
		$orden=$row_datos->orden;
	}
	$sql="insert into camposdetalle(nombre, etiqueta,tipo, obligatorio, orden) values ('".$nombre."', '".$etiqueta."','".$tipo."','".$obligatorio."',".$orden.")";
	$bd_ID->sql($sql);
	$sql="alter table embarques_detalles add column ".$nombre;
	if(strcmp($tipo,"Texto")==0){
		$sql=$sql." varchar(200)";
	}else if(strcmp($tipo,"Fecha")==0){
		$sql=$sql." datetime";
	}else if(strcmp($tipo,"Numero")==0){
		$sql=$sql." decimal";
	}
	$bd_ID->sql($sql);
}

function modificarCampo($bd_ID,$nombre, $etiqueta,$tipo,$obligatorio,$orden,$id){
$sql="update campos set nombre='".$nombre."', etiqueta='".$etiqueta."',tipo='".$tipo."', obligatorio='".$obligatorio."', order=".$orden." where id=".$id;
	$bd_ID->sql($sql);
}

function deleteCampo($bd_ID,$id){
	$sql="delete from campos where id=".$id;
	$bd_ID->sql($sql);
}

function consultarCampo($bd_ID,$nombre){

$sql="select etiqueta, tipo, obligatorio, orden from campos where nombre='".$nombre."'";
$ID_datos=$bd_ID->sql($sql);
	if ($row_datos = mysql_fetch_object($ID_datos)){
		$campo->etiqueta=$row_datos->etiqueta;
		$campo->tipo=$row_datos->tipo;
		$campo->obligatorio=$row_datos->obligatorio;
		$campo->orden=$row_datos->orden;
		$campo->nombre=$nombre;
		return $campo;
	}
return null;
}
function agregarDetalle($bd_ID,$id_embarque,$factura,$fecha_factura,$guia_wr,$valor_total,$cajas,$volumen,$peso,$fecha_wr,$pedido_original){
	$sql="insert into embarques_detalles(factura,id_embarque,fecha_factura,guia_wr,valor_total,cajas,volumen,peso,fecha_wr,pedido_original) values ('".$factura."','".$id_embarque."','".$fecha_factura."','".$guia_wr."','".$valor_total."','".$cajas."','".$volumen."','".$peso."','".$fecha_wr."','".$pedido_original."')";
	$bd_ID->sql($sql);
}
function editarDetalle($bd_ID,$id,$id_embarque,$factura,$fecha_factura,$guia_wr,$valor_total,$cajas,$volumen,$peso,$fecha_wr,$pedido_original){
	$sql="update embarques_detalles set factura='".$factura."',fecha_factura='".$fecha_factura."',guia_wr='".$guia_wr."',valor_total='".$valor_total."',cajas='".$cajas."',volumen='".$volumen."',peso='".$peso."',fecha_wr='".$fecha_wr."',pedido_original='".$pedido_original."' where id=".$id;
	$bd_ID->sql($sql);
}

function eliminarDetalle($bd_ID,$id){
	$sql="delete from embarques_detalles where id=".$id;
	$bd_ID->sql($sql);
}
function consolidar($bd_ID,$id,$embarque){
	$sql="update embarques_detalles set id_embarque=".$embarque." where id=".$id;
//echo $sql;
	$bd_ID->sql($sql);
	$sql="update archivos set id_embarque=".$embarque." where id_detalle=".$id;
	$bd_ID->sql($sql);
}

function register_global_array( $sg ) {
    Static $superGlobals    = array(
        'e' => '_ENV'       ,
        'g' => '_GET'       ,
        'p' => '_POST'      ,
        'c' => '_COOKIE'    ,
        'r' => '_REQUEST'   ,
        's' => '_SERVER'    ,
        'f' => '_FILES'
    );
   
    Global ${$superGlobals[$sg]};
   
    foreach( ${$superGlobals[$sg]} as $key => $val ) {
        $GLOBALS[$key]  = $val;
    }
}
 
function register_globals( $order = 'gpc' ) {
    $_SERVER;       //See Note Below
    $_ENV;
    $_REQUEST;
   
    $order  = str_split( strtolower( $order ) );
    array_map( 'register_global_array' , $order );
}
?>