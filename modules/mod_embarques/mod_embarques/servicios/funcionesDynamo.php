<?PHP
require 'modules/mod_embarques/aws/aws.phar';
include 'modules/mod_embarques/objetos/empresa.php';
include 'modules/mod_embarques/objetos/embarque.php';
include 'modules/mod_embarques/objetos/campo.php';
include 'modules/mod_embarques/objetos/usuario.php';
use Aws\DynamoDb\Marshaler;

/**
 * ****************************************************DynamoDB*******************************************
 */
function crearEmpresa($empresa) {
	$dynamodb = conectarDynamo ();
	$datosEnviar = json_encode ( $empresa, JSON_UNESCAPED_UNICODE );
	$marshaler = new Marshaler ();
	$response = $dynamodb->putItem ( [ 
			'TableName' => 'lsupplier_empresas',
			'Item' => $marshaler->marshalJson ( $datosEnviar ) 
	] );
}
function crearEmbarquesBatch($embarques) {
	$dynamodb = conectarDynamo ();
	$marshaler = new Marshaler ();
	$requestArray = array ();
	foreach ( $embarques as $embarque ) {
		try {
			
			$request = array ();
			$request ["PutRequest"] = array ();
			$datosEnviar = json_encode ( $embarque, JSON_UNESCAPED_UNICODE );
			$request ["PutRequest"] ["Item"] = $marshaler->marshalJson ( $datosEnviar );
			$requestArray [] = $request;
		} catch ( Exception $e ) {
			echo "Error al transformar: " . $embarque ["id"] . "</br>";
			echo "</br>";
		}
	}
	try {
		$tableName = "lsupplier_embarques";
		$requestItems = array ();
		$requestItems ["RequestItems"] [$tableName] = $requestArray;
		$response = $dynamodb->batchWriteItem ( $requestItems );
	} catch ( Exception $e ) {
		echo $e;
	}
}

/**
 *
 * @param
 *        	_SERVER
 */
function conectarDynamo() {
	$_SERVER ["AWS_ACCESS_KEY_ID"] = "";
	$_SERVER ["AWS_SECRET_ACCESS_KEY"] = "";
	$sdk = new Aws\Sdk ( [ 
			'credentials' => array (
					'key' => "",
					'secret' => "" 
			),
			'region' => 'us-east-1',
			'version' => 'latest' 
	] );
	$dynamodb = $sdk->createDynamoDb ();
	return $dynamodb;
}
function consultarEmpresas($nombre) {
	$dynamodb = conectarDynamo ();
	$response = $dynamodb->scan ( [ 
			'TableName' => 'lsupplier_empresas',
			'ExpressionAttributeValues' => [ 
					':val1' => [ 
							'S' => $nombre 
					] 
			],
			'FilterExpression' => 'contains(nombre ,:val1)' 
	] );
	$empresas = array ();
	foreach ( $response ['Items'] as $dato ) {
		$empresa = new empresa ();
		$empresa->nit = $dato ['nit'] ['S'];
		$empresa->nombre = $dato ['nombre'] ['S'];
		$empresa->campos = $dato ['campos'] ['L'];
		$empresa->campos_cliente = $dato ['campos_cliente'] ['L'];
		$empresa->campos_mail = $dato ['campos_mail'] ['L'];
		$empresa->usuarios = $dato ['usuarios'] ['L'];
		$empresas [] = $empresa;
	}
	return $empresas;
}
function migrarEmpresaCampos($nit) {
	$query = "select c.nombre, cu.id_usuario, cu.listado as listado, cu.mail as mail, cu.orden  from campos c, camposxusuario cu where c.id=cu.id_campo and cu.id_usuario=" . $nit;
	$bd_ID = mysqli_connect ( 'matlantic.c8quuwggkk5q.us-east-1.rds.amazonaws.com', 'lsgroupdb', 'LSGroup123', 'lsgroupdb' );
	$json = array ();
	if ($ID_datos = $bd_ID->query ( $query )) {
		$retorno = array ();
		$count = 0;
		$empresa = cargarEmpresa ( $nit );
		while ( $row_datos = $ID_datos->fetch_assoc () ) {
			$row_datos = array_filter ( $row_datos );
			$empresa->campos [] = $row_datos ["nombre"];
			if (strcmp ( $row_datos ["mail"], "Si" ) == 0) {
				$empresa->campos_mail [] = $row_datos ["nombre"];
			}
			if (strcmp ( $row_datos ["listado"], "Si" ) == 0) {
				$empresa->campos_cliente [] = $row_datos ["nombre"];
			}
		}
					
		crearEmpresa ( $empresa );
		return $empresa;
	} else {
		echo "Query failed";
		return null;
	}
}
function migrarEmpresas($inicial, $cantidad) {
	$query = "select nit, nombres  FROM empresas where nombres<>'' and nit<>'' order by nombres limit " . $inicial . "," . $cantidad;
	$bd_ID = mysqli_connect ( 'matlantic.c8quuwggkk5q.us-east-1.rds.amazonaws.com', 'lsgroupdb', 'LSGroup123', 'lsgroupdb' );
	$json = array ();
	if ($ID_datos = $bd_ID->query ( $query )) {
		$retorno = array ();
		$count = 0;
		while ( $row_datos = $ID_datos->fetch_assoc () ) {
			try {
				$empresa = new empresa ();
				$empresa->nit = $row_datos ["nit"];
				$empresa->nombre = $row_datos ["nombres"];
				$empresa->campos = array ();
				$empresa->campos_cliente = array ();
				$empresa->campos_mail = array ();
				$empresa->usuarios = array ();
				crearEmpresa ( $empresa );
				echo "<br>Empresa creada: " . $empresa->nombre;
				$empresa = migrarEmpresaCampos ( $empresa->nit );
				echo "<br>Campos migrados: " . $empresa->nombre;
			} catch ( Exception $e ) {
				echo "Error creando empresa: " . $e;
			}
		}
		echo ($count);
	} else {
		echo "Query failed";
	}
}
function consultarEmbarquesDynamo($getAllParameters) {
	$dynamodb = conectarDynamo ();
	$expresionValues = array ();
	$filterExpression = "";
	$marshaler = new Marshaler ();
	foreach ( $getAllParameters as $key => $value ) {
		$expresionValues [':' . $key] = array ();
		$expresionValues [':' . $key] ['S'] = $value;
		$filterExpression = $filterExpression . ' contains(' . $key . ', :' . $key . ') AND ';
	}
	$eav = $marshaler->marshalJson ( '
    {
        ":id_cliente" : "' . $getAllParameters ['id_cliente'] . '"
    }
' );
	$filterExpression = substr ( $filterExpression, 0, strlen ( $filterExpression ) - 5 );
	$response = $dynamodb->query ( [ 
			'TableName' => 'lsupplier_embarques',
			'ProjectionExpression' => "exportador, importador, ref1_cliente, radicacion_bl, mawb, etd, eta, ata, destino, observaciones, operativo, vendedor, d_o, destino_final, pais, id_cliente,origen, via, estado, naviera, vapor, tipo, id",
			'ExpressionAttributeValues' => $eav,
			'KeyConditionExpression' => 'id_cliente = :id_cliente' 
	] );
	$embarques = array ();
	foreach ( $response ["Items"] as $embarqueDato ) {
		try {
			$embarques [] = $marshaler->unmarshalItem ( $embarqueDato );
		} catch ( Exception $e ) {
			echo "Error cargando embarque: " . $e;
		}
	}
	return $embarques;
}

function consultarEmbarquesEstado($estado) {
	$dynamodb = conectarDynamo ();
	$expresionValues = array ();
	$filterExpression = "";
	$marshaler = new Marshaler ();
	$condition ='estado = :estado' ;	
	$eav = $marshaler->marshalJson ( '
    {
        ":estado" : "' . $estado . '"
    }
' );
	$response = $dynamodb->query ( [ 
			'TableName' => 'lsupplier_embarques',
			'IndexName'=> 'estado-index',
			'ProjectionExpression' => "exportador, importador, referencia_cliente1, fecha_radicacion_bls, mawb, etd, eta, ata, destino, observaciones, operativo, vendedor, d_o, destino_final, pais, id_cliente,origen, via, estado, naviera, vapor, tipo, id",
			'ExpressionAttributeValues' => $eav,
			'KeyConditionExpression' => $condition
	] );
	$embarques = array ();
	foreach ( $response ["Items"] as $embarqueDato ) {
		try {
			$embarques [] = $marshaler->unmarshalItem ( $embarqueDato );
		} catch ( Exception $e ) {
			echo "Error cargando embarque: " . $e;
		}
	}
	return $embarques;
}

function consultarEmbarquesEstadoCliente($estado,$id_cliente) {
	$dynamodb = conectarDynamo ();
	$expresionValues = array ();
	$filterExpression = "";
	$marshaler = new Marshaler ();
	$condition ='estado = :estado' ;	
	$eav = $marshaler->marshalJson ( '
    {
        ":estado" : "' . $estado . '"
    }
' );
	$response = $dynamodb->query ( [ 
			'TableName' => 'lsupplier_embarques',
			'IndexName'=> 'estado-index',
			'ProjectionExpression' => "exportador, importador, referencia_cliente1, fecha_radicacion_bls, mawb, etd, eta, ata, destino, observaciones, operativo, vendedor, d_o, destino_final, pais, id_cliente,origen, via, estado, naviera, vapor, tipo, id, hawb",
			'ExpressionAttributeValues' => $eav,
			'KeyConditionExpression' => $condition
	] );
	$embarques = array ();
	foreach ( $response ["Items"] as $embarqueDato ) {
		try {
			$embarqueTemp=$marshaler->unmarshalItem ( $embarqueDato );
			if(strcmp ($embarqueTemp["id_cliente"],$id_cliente)==0){
				$embarques [] = $embarqueTemp;
			}
		} catch ( Exception $e ) {
			echo "Error cargando embarque: " . $e;
		}
	}
	return $embarques;
}

function consultarEmbarquesTodos() {
	$dynamodb = conectarDynamo ();
	$expresionValues = array ();
	$marshaler = new Marshaler ();
	$filterExpression = substr ( $filterExpression, 0, strlen ( $filterExpression ) - 5 );
	$response = $dynamodb->scan ( [ 
			'TableName' => 'lsupplier_embarques'			
	] );
	$embarques = array ();
	foreach ( $response ["Items"] as $embarqueDato ) {
		try {
			$embarques [] = $marshaler->unmarshalItem ( $embarqueDato );
		} catch ( Exception $e ) {
			echo "Error cargando embarque: " . $e;
		}
	}
	return $embarques;
}
function consultarEmbarqueDynamo($id, $id_cliente) {
	$dynamodb = conectarDynamo ();
	$marshaler = new Marshaler ();
	$parametro = array ();
	$parametro ['S'] = $id;
	$response = $dynamodb->getItem ( [ 
			'TableName' => 'lsupplier_embarques',
			'Key' => [ 
					'id_cliente' => [ 
							'S' => $id_cliente 
					],
					'id' => [ 
							'S' => $id 
					] 
			] 
	] );
	return $marshaler->unmarshalItem ( $response ["Item"] );
}

function eliminarEmbarque($id, $id_cliente) {
	$dynamodb = conectarDynamo ();
	$marshaler = new Marshaler ();
	$parametro = array ();
	$parametro ['S'] = $id;
	$response = $dynamodb->deleteItem ( [ 
			'TableName' => 'lsupplier_embarques',
			'Key' => [ 
					'id_cliente' => [ 
							'S' => $id_cliente 
					],
					'id' => [ 
							'S' => $id 
					] 
			] 
	] );
}

function crearEmbarque($embarque) {
	$dynamodb = conectarDynamo ();
	$datosEnviar = json_encode ( $embarque );
	$marshaler = new Marshaler ();
	/*
	 * $response = $dynamodb->putItem ( [
	 * 'TableName' => 'lsupplier_embarques',
	 * 'Item' => $marshaler->marshalJson ( $datosEnviar )
	 * ] );
	 */
}

function crearEmbarqueDirecto($embarque) {
	try {
		$dynamodb = conectarDynamo ();
		$marshaler = new Marshaler ();
		
		if (empty ( $embarque ["detalles"] )) {
			var_dump($embarque);
			$embarqueAntiguo = consultarEmbarqueDynamo ( $embarque ["id"],$embarque ["id_cliente"] );
			if (! empty ( $embarqueAntiguo )) {
				$embarque ["detalles"] = $embarqueAntiguo ["detalles"];
			}
		}
		
		$date = new DateTime ();
		$result = $date->format ( 'Y-m-d H:i:s' );
		$embarque ["fechaActualizacion"] = $result;
		$response = $dynamodb->putItem ( [ 
				'TableName' => 'lsupplier_embarques',
				'Item' => $marshaler->marshalItem ( $embarque ) 
		] );
		return $embarque;
	} catch ( Exception $e ) {
		echo ( $e->getMessage() );
	}
}

function modificarDetallesEmbarque($embarque) {
	try {
		$dynamodb = conectarDynamo ();
		$marshaler = new Marshaler ();		
			
		$date = new DateTime ();
		$result = $date->format ( 'Y-m-d H:i:s' );
		$embarque ["fechaActualizacion"] = $result;
		$response = $dynamodb->putItem ( [ 
				'TableName' => 'lsupplier_embarques',
				'Item' => $marshaler->marshalItem ( $embarque ) 
		] );
		return $embarque;
	} catch ( Exception $e ) {
		echo ( $e->getMessage() );
	}
}
function consultarEmbarques($parametros) {
	$dynamodb = conectarDynamo ();
	$response = $dynamodb->scan ( [ 
			'TableName' => 'lsupplier_embarques',
			'ExpressionAttributeValues' => [ 
					':val1' => [ 
							'S' => $parametros ['cliente'] 
					] 
			],
			'FilterExpression' => 'contains(cliente ,:val1)' 
	] );
	$embarques = array ();
	foreach ( $response ['Items'] as $dato ) {
		$embarques [] = $dato;
	}
	return $embarques;
}
function isJson($string) {
	json_decode ( $string );
	return (json_last_error () == JSON_ERROR_NONE);
}
function crearEmbarqueDynamo($embarque) {
	$dynamodb = conectarDynamo ();
	$date = new DateTime ();
	$result = $date->format ( 'Y-m-d H:i:s' );
	$embarque ["fechaActualizacion"] = $result;
	try {
		$marshaler = new Marshaler ();
		$response = $dynamodb->putItem ( [ 
				'TableName' => 'lsupplier_embarques',
				'Item' => $marshaler->marshalItem ( $embarque ) 
		] );
	} catch ( Exception $e ) {
		echo "Unable to save embarque: " . $embarque ["id"] . "<br/>";
	}
	return $embarque;
}
function consultarEmpresa($nit) {
	$dynamodb = conectarDynamo ();
	$response = $dynamodb->getItem ( [ 
			'TableName' => 'lsupplier_empresas',
			'ConsistentRead' => true,
			'Key' => [ 
					'Id' => $nit 
			] 
	] );
	print_r ( $response ['Item'] );
}
function getTableEmpresas() {
	$datos = consultarEmpresas ( " " );
	$retorno = array ();
	$json = '{"cols": [
		{"id":"Nit","label":"Nit","type":"string"},
		{"id":"Nombre","label":"Nombre","type":"string"},
		{"id":"Accion","label":"Accion","type":"string"}
      ],
"rows": [';
	foreach ( $datos as $empresa ) {
		$json .= '{"c":[{"v":"' . $empresa->nit . '"},{"v":"' . $empresa->nombre . '"},{"v": "<form method=POST><input type=hidden name=accion value=Consultar /><input type=hidden name=nit value=' . $empresa->nit . ' /><input type=submit value=Ver /></form>"}]},';
	}
	$json = substr ( $json, 0, - 1 );
	$json .= ']}';
	return $json;
}
function getTableEmbarques($datos) {
	$retorno = array ();
	$json = '{"cols": [
		{"id":"exportador","label":"Exportador","type":"string"},
		{"id":"importador","label":"Importador","type":"string"},
		{"id":"ref1_cliente","label":"Ref 1 Cliente","type":"string"},
		{"id":"radicacion_bl","label":"Radicacion BL","type":"string"},
		{"id":"mawb","label":"MAWB","type":"string"},
		{"id":"etd","label":"ETD","type":"string"},
		{"id":"eta","label":"ETA","type":"string"},
		{"id":"ata","label":"ATA","type":"string"},
		{"id":"destino","label":"Destino","type":"string"},
		{"id":"observaciones","label":"Observaciones","type":"string"},
		{"id":"Accion","label":"Accion","type":"string"}
      ],
"rows": [';
	foreach ( $datos as $embarque ) {
		$json .= '{"c":[{"v":"' . $embarque ['exportador'] . '"},{"v":"' . $embarque ['importador'] . '"},{"v":"' . $embarque ['referencia_cliente1'] . '"},{"v":"' . $embarque ['radicacion_bl'] . '"},{"v":"' . $embarque ['mawb'] . '"},{"v":"' . $embarque ['etd'] . '"},{"v":"' . $embarque ['eta'] . '"},{"v":"' . $embarque ['ata'] . '"},{"v":"' . $embarque ['destino'] . '"},{"v":"' . $embarque ['observaciones'] . '"},{"v": "<form method=POST><input type=hidden name=accion value=Consultar /><input type=hidden name=nit value=' . $embarque ['id'] . ' /><input type=submit value=Ver /></form>"}]},';
	}
	$json = substr ( $json, 0, - 1 );
	$json .= ']}';
	return $json;
}
function cargarEmpresa($nit) {
	$dynamodb = conectarDynamo ();
	$marshaler = new Marshaler ();
	$response = $dynamodb->getItem ( [ 
			'TableName' => 'lsupplier_empresas',
			'ConsistentRead' => true,
			'Key' => [ 
					'nit' => $marshaler->marshalValue ( $nit ) 
			] 
	] );
	$dato = $marshaler->unmarshalItem ( $response ['Item'] );
	$empresa = new empresa ();
	$empresa->nit = $dato ['nit'];
	$empresa->nombre = $dato ['nombre'];
	$empresa->campos = $dato ['campos'];
	$empresa->campos_cliente = $dato ['campos_cliente'];
	$empresa->campos_mail = $dato ['campos_mail'];
	$empresa->usuarios = $dato ['usuarios'];
	return $empresa;
}
function consultarComboUsuarios($bd_ID) {
	$combo = "<select name='cliente'>";
	$sql_datos = "select nit,nombre from empresas order by nombre";
	$ID_datos = $bd_ID->sql ( $sql_datos );
	while ( $row_clientes = mysql_fetch_object ( $ID_datos ) ) {
		$combo = $combo . "<option value='" . $row_clientes->nit . "'>" . $row_clientes->nombres;
		$combo = $combo . "</option>";
	}
	$combo = $combo . "</select>";
	return $combo;
}
function consultarComboUsuariosB($bd_ID, $cliente) {
	$combo = "<select name='cliente'>";
	$sql_datos = "select nit ,nombres from empresas order by nombres";
	$ID_datos = $bd_ID->sql ( $sql_datos );
	$combo = $combo . "<option value='%%'>(No seleccionado)</option>";
	while ( $row_clientes = mysql_fetch_object ( $ID_datos ) ) {
		
		if ($cliente == $row_clientes->nit) {
			$combo = $combo . "<option value='" . $row_clientes->nit . "' selected>" . $row_clientes->nombres;
		} else {
			$combo = $combo . "<option value='" . $row_clientes->nit . "'>" . $row_clientes->nombres;
		}
		$combo = $combo . "</option>";
	}
	$combo = $combo . "</select>";
	return $combo;
}
function consultarComboUsuariosCambio($bd_ID, $cliente) {
	$combo = "<select name='id_cliente' onchange='selectCliente(this);'>";
	$sql_datos = "select nit ,nombres from empresas order by nombres";
	$ID_datos = $bd_ID->sql ( $sql_datos );
	$combo = $combo . "<option value=''>(No seleccionado)</option>";
	while ( $row_clientes = mysql_fetch_object ( $ID_datos ) ) {
		
		if ($cliente == $row_clientes->nit) {
			$combo = $combo . "<option value='" . $row_clientes->nit . "' selected >" . $row_clientes->nombres;
		} else {
			$combo = $combo . "<option value='" . $row_clientes->nit . "'>" . $row_clientes->nombres;
		}
		$combo = $combo . "</option>";
	}
	$combo = $combo . "</select>";
	return $combo;
}
function consultarComboEstados($bd_ID) {
	$combo = "<select name='estado'>";
	$sql_datos = "select id,nombre from estados_embarques";
	$ID_datos = $bd_ID->sql ( $sql_datos );
	while ( $row_estados = mysql_fetch_object ( $ID_datos ) ) {
		$combo = $combo . "<option value='" . $row_estados->id . "'>" . $row_estados->nombre;
		$combo = $combo . "</option>";
	}
	$combo = $combo . "</select>";
	return $combo;
}
function crearDO($bd_ID, $d_o, $pais, $contenedor, $pos, $poa, $vapor, $naviera, $fcl, $lcl, $etd, $eta, $mbl, $hbl, $fecha_agencia, $fecha_naviera, $fecha_dian, $comentario, $estado, $id_importador, $ciudad_origen, $ciudad_destino, $proveedor, $pedido, $incoterm, $pod, $aerolinea, $peso, $volumen, $ata, $zona_aduanera, $tipo, $fecha_pre_alerta, $fecha_zona_aduanera) {
	$registro = "insert into embarques(d_o,pais,contenedor,pos,poa,vapor,naviera,fcl,lcl,etd,eta,mbl,hbl,fecha_agencia,fecha_naviera,fecha_dian,observaciones,id_estado,id_importador,ciudad_origen,ciudad_destino,proveedor,pedido, incoterm,pod, aerolinea, peso, volumen, ata,zona_aduanera,tipo, fecha_pre_alerta, fecha_zona_aduanera) values('" . $d_o . "','" . $pais . "','" . $contenedor . "','" . $pos . "','" . $poa . "','" . $vapor . "','" . $naviera . "','" . $fcl . "','" . $lcl . "','" . $etd . "','" . $eta . "','" . $mbl . "','" . $hbl . "','" . $fecha_agencia . "','" . $fecha_naviera . "','" . $fecha_dian . "','" . $comentario . "','" . $estado . "','" . $id_importador . "','" . $ciudad_origen . "','" . $ciudad_destino . "','" . $proveedor . "','" . $pedido . "','" . $incoterm . "','" . $pod . "','" . $aerolinea . "','" . $peso . "','" . $volumen . "','" . $ata . "','" . $zona_aduanera . "','" . $tipo . "','" . $fecha_pre_alerta . "','" . $fecha_zona_aduanera . "')";
	
	$bd_ID->sql ( $registro );
}
function eliminarDO($bd_ID, $id) {
	$registro = "delete from embarques_seguimiento where id=" . $id;
	
	$bd_ID->sql ( $registro );
}
function actualizarDO($bd_ID, $id, $d_o, $pais, $contenedor, $pos, $poa, $vapor, $naviera, $fcl, $lcl, $etd, $eta, $mbl, $hbl, $fecha_agencia, $fecha_naviera, $fecha_dian, $comentario, $estado, $cliente, $ciudad_origen, $ciudad_destino, $proveedor, $pedido, $incoterm, $pod, $aerolinea, $peso, $volumen, $ata, $zona_aduanera, $tipo, $fecha_pre_alerta, $fecha_zona_aduanera) {
	$registro = "update embarques set d_o='" . $d_o . "' ,pais='" . $pais . "' ,contenedor='" . $contenedor . "',pos='" . $pos . "',poa='" . $poa . "',vapor='" . $vapor . "',naviera='" . $naviera . "',fcl='" . $fcl . "',lcl='" . $lcl . "',etd='" . $etd . "',eta='" . $eta . "',mbl='" . $mbl . "',hbl='" . $hbl . "',fecha_agencia='" . $fecha_agencia . "',fecha_naviera='" . $fecha_naviera . "',fecha_dian='" . $fecha_dian . "',observaciones='" . $comentario . "',id_estado='" . $estado . "',id_importador='" . $cliente . "',ciudad_origen='" . $ciudad_origen . "',ciudad_destino='" . $ciudad_destino . "',proveedor='" . $proveedor . "',pedido='" . $pedido . "',incoterm='" . $incoterm . "',pod='" . $pod . "',aerolinea='" . $aerolinea . "', peso='" . $peso . "', volumen='" . $volumen . "',ata='" . $ata . "', zona_aduanera='" . $zona_aduanera . "',tipo='" . $tipo . "', fecha_pre_alerta='" . $fecha_pre_alerta . "',fecha_zona_aduanera='" . $fecha_zona_aduanera . "' where id=" . $id;
	$bd_ID->sql ( $registro );
}
function crearGenerico($bd_ID, $parametros) {
	$cadenaInsert = "";
	$cadenaParametros = "";
	
	foreach ( $parametros as $nombre_campo => $valor ) {
		
		if ($nombre_campo != "Accion") {
			if (strcmp ( $valor, "" ) != 0) {
				$cadenaInsert = $cadenaInsert . "," . $nombre_campo;
				$campo = consultarCampo ( $bd_ID, $nombre_campo );
				if (strcmp ( $campo->tipo, "Fecha" ) == 0) {
					if (strcmp ( $valor, "" ) == 0) {
						$valor = '0000-00-00';
					}
				}
				
				if (strcmp ( $campo->tipo, "Fecha" ) == 0 or strcmp ( $campo->tipo, "Texto" ) == 0 or strcmp ( $campo->tipo, "Lista" ) == 0) {
					$valor = "'" . $valor . "'";
				}
				$cadenaParametros = $cadenaParametros . "," . $valor;
			}
		}
	}
	$registro = "insert into embarques_seguimiento(" . substr ( $cadenaInsert, 1 ) . " ) values (" . substr ( $cadenaParametros, 1 ) . ")";
	$bd_ID->sql ( $registro );
}
function consultarEmbarquesAntiguos($parametros) {
	$query = "SELECT id,
    id_cliente,
    estado,
    importador,
    exportador,
    asesor,
    via,
    do_ls,
    referencia_cliente1,
    referencia_cliente2,
    lc,
    fecha_estimada_entrega,
    fecha_entrega_proveedor,
    pickup,
    mawb,
    hawb,
    fecha_radicacion_bls,
    fletes,
    origen,
    puerto_embarque,
    destino_final,
    vapor,
    viaje,
    naviera,
    cantidad,
    tipo_contenedor,
    cutoff,
    etd,
    eta,
    ata,
    desconsolidacion,
    inspeccion,
    ingreso_deposito,
    facturar_a,
    entrega_documentos_a,
    fecha_dec_importacion,
    pago_tributos,
    levante,
    entrega_cliente,
    observaciones,
    ingreso_agente,
    pais,
    tipo,
    Prueba,
    proveedor,
    atd,
    pruebaslqs,
    otra_prueba,
    LULO,
    LULO2,
    FRESA,
    CEREZA,
    asterisko,
    prueba5seft,
    asterisk_obelisk,
    asterisk,
    Referencia,
    FACTURA,
    Fecha_notificacion,
    fecha_instruccion,
    Envio_borrador_bl_awb,
    aprobacion_bl_awb,
    envio_prealerta,
    radicacion_factura,
    entrega_documentos,
    cotizacion,
    Recibo_Documentos_ITR,
    Preinspeccion,
    Presentacion_Declaracion,
    Solicitud_Levante,
    Insp_Fisica_DIAN,
    Asignacion_Cita_Cargue,
    Cargue_a_Camion,
    Descargue_Obra,
    Devolucion_Vacio
FROM embarques_seguimiento";
	$bd_ID = mysqli_connect ( 'matlantic.c8quuwggkk5q.us-east-1.rds.amazonaws.com', 'lsgroupdb', 'LSGroup123', 'lsgroupdb' );
	$json = array ();
	$errores = "";
	if ($ID_datos = $bd_ID->query ( $query )) {
		$retorno = array ();
		$count = 0;
		while ( $row_datos = $ID_datos->fetch_assoc () ) {
			$row_datos = array_filter ( $row_datos );
			$json [] = $row_datos;
			$query_detalle = "select id, fecha_factura, guia_wr, valor_total, cajas, volumen, peso, fecha_wr, pedido_original, factura from embarques_detalles where id_embarque=" . $row_datos ['id'];
			if ($ID_datos_detalle = $bd_ID->query ( $query_detalle )) {
				$detalles = array ();
				while ( $row_datos_detalle = $ID_datos_detalle->fetch_assoc () ) {
					$row_datos_detalle = array_filter ( $row_datos_detalle );
					$row_datos ['detalles'] = $row_datos_detalle;
				}
			}
			$count ++;
			try {
				crearEmbarqueDirecto ( $row_datos );
			} catch ( Exception $e ) {
				$errores = $errores . " " . $e;
			}
		}
		echo ("Embarques migrados: " . $count);
		if (strlen ( $errores ) > 0) {
			echo ("Error al migrar!!!");
		}
	} else {
		echo "Query failed";
	}
	
	/*
	 * $jsonencoded = json_encode($json,JSON_UNESCAPED_UNICODE);
	 * var_dump(json_encode($json));
	 * $fh = fopen("output.json", 'w');
	 * fwrite($fh, json_encode($json));
	 * fclose($fh);
	 */
	return $retorno;
}
function consultarEmbarquesAntiguosRango($inicial, $cantidad) {
	$query = "SELECT id,
    id_cliente,
    estado,
    importador,
    exportador,
    asesor,
    via,
    do_ls,
    referencia_cliente1,
    referencia_cliente2,
    lc,
    fecha_estimada_entrega,
    fecha_entrega_proveedor,
    pickup,
    mawb,
    hawb,
    fecha_radicacion_bls,
    fletes,
    origen,
    puerto_embarque,
    destino_final,
    vapor,
    viaje,
    naviera,
    cantidad,
    tipo_contenedor,
    cutoff,
    etd,
    eta,
    ata,
    desconsolidacion,
    inspeccion,
    ingreso_deposito,
    facturar_a,
    entrega_documentos_a,
    fecha_dec_importacion,
    pago_tributos,
    levante,
    entrega_cliente,
    observaciones,
    ingreso_agente,
    pais,
    tipo,
    Prueba,
    proveedor,
    atd,
    pruebaslqs,
    otra_prueba,
    LULO,
    LULO2,
    FRESA,
    CEREZA,
    asterisko,
    prueba5seft,
    asterisk_obelisk,
    asterisk,
    Referencia,
    FACTURA,
    Fecha_notificacion,
    fecha_instruccion,
    Envio_borrador_bl_awb,
    aprobacion_bl_awb,
    envio_prealerta,
    radicacion_factura,
    entrega_documentos,
    cotizacion,
    Recibo_Documentos_ITR,
    Preinspeccion,
    Presentacion_Declaracion,
    Solicitud_Levante,
    Insp_Fisica_DIAN,
    Asignacion_Cita_Cargue,
    Cargue_a_Camion,
    Descargue_Obra,
    Devolucion_Vacio
FROM embarques_seguimiento order by id desc limit " . $inicial . "," . $cantidad;
	mysqli_set_charset ( $connect, "utf8" );
	$bd_ID = mysqli_connect ( 'matlantic.c8quuwggkk5q.us-east-1.rds.amazonaws.com', 'lsgroupdb', 'LSGroup123', 'lsgroupdb' );
	$json = array ();
	$errores = "";
	if ($ID_datos = $bd_ID->query ( $query )) {
		$retorno = array ();
		$count = 0;
		while ( $row_datos = $ID_datos->fetch_assoc () ) {
			$row_datos = array_filter ( $row_datos );
			foreach ( $row_datos as $key => $value ) {
				$row_datos [$key] = utf8_encode ( $value );
			}
			$json [] = $row_datos;
			$query_detalle = "select id, fecha_factura, guia_wr, valor_total, cajas, volumen, peso, fecha_wr, pedido_original, factura from embarques_detalles where id_embarque=" . $row_datos ['id'];
			if ($ID_datos_detalle = $bd_ID->query ( $query_detalle )) {
				$detalles = array ();
				while ( $row_datos_detalle = $ID_datos_detalle->fetch_assoc () ) {
					$row_datos_detalle = array_filter ( $row_datos_detalle );
					$row_datos ['detalles'] = $row_datos_detalle;
				}
			}
			$count ++;
			try {
				$retorno [] = $row_datos;
				// crearEmbarqueDirecto ( $row_datos );
			} catch ( Exception $e ) {
				$errores = $errores . " " . $e;
			}
			if ($count == 3) {
				crearEmbarquesBatch ( $retorno );
				echo ("<strong>Embarques migrados: " . $count . "</strong></br>");
				$count = 0;
				$retorno = array ();
			}
		}
		
		if (strlen ( $errores ) > 0) {
			echo ("Error al migrar!!!");
		}
	} else {
		echo "Query failed";
	}
	
	/*
	 * $jsonencoded = json_encode($json,JSON_UNESCAPED_UNICODE);
	 * var_dump(json_encode($json));
	 * $fh = fopen("output.json", 'w');
	 * fwrite($fh, json_encode($json));
	 * fclose($fh);
	 */
	return $retorno;
}
function actualizarGenerico($bd_ID, $parametros) {
	$cadenaUpdate = "";
	$cadenaParametros = "";
	
	foreach ( $parametros as $nombre_campo => $valor ) {
		
		if ($nombre_campo != "Accion" && $nombre_campo != "id" && $nombre_campo != "cliente") {
			if (strcmp ( $valor, "" ) != 0) {
				$cadenaUpdate = $cadenaUpdate . "," . $nombre_campo . "=";
				$campo = consultarCampo ( $bd_ID, $nombre_campo );
				if (strcmp ( $campo->tipo, "Fecha" ) == 0) {
					if (strcmp ( $valor, "" ) == 0) {
						$valor = '0000-00-00';
					}
				}
				
				if (strcmp ( $campo->tipo, "Fecha" ) == 0 or strcmp ( $campo->tipo, "Texto" ) == 0 or strcmp ( $campo->tipo, "Lista" ) == 0) {
					$valor = "'" . $valor . "'";
				}
				$cadenaUpdate = $cadenaUpdate . " " . $valor;
			}
		}
		
		if ($nombre_campo == "id") {
			$id = $valor;
		}
	}
	$registro = "update embarques_seguimiento set " . substr ( $cadenaUpdate, 1 ) . " where id=" . $id;
	// echo $registro;
	$bd_ID->sql ( $registro );
}
function crearGenericoDetalle($bd_ID, $id_embarque, $parametros) {
	$cadenaInsert = "";
	$cadenaParametros = "";
	
	foreach ( $parametros as $nombre_campo => $valor ) {
		if ($nombre_campo != "Accion") {
			if (strcmp ( $valor, "" ) != 0) {
				$cadenaInsert = $cadenaInsert . "," . $nombre_campo;
				$campo = consultarCampo ( $bd_ID, $nombre_campo );
				if (strcmp ( $campo->tipo, "Fecha" ) == 0) {
					if (strcmp ( $valor, "" ) == 0) {
						$valor = '0000-00-00';
					}
				}
				
				if (strcmp ( $campo->tipo, "Fecha" ) == 0 or strcmp ( $campo->tipo, "Texto" ) == 0 or strcmp ( $campo->tipo, "Lista" ) == 0) {
					$valor = "'" . $valor . "'";
				}
				$cadenaParametros = $cadenaParametros . "," . $valor;
			}
		}
	}
	$registro = "insert into embarques_detalles(id_embarque," . substr ( $cadenaInsert, 1 ) . " ) values (" . $id_embarque . "," . substr ( $cadenaParametros, 1 ) . ")";
	echo $registro;
	// $bd_ID->sql($registro);
}
function actualizar($bd_ID, $estado, $exportador, $importador, $asesor, $via, $do_ls, $referencia_cliente1, $referencia_cliente2, $lc, $fecha_estimada_entrega, $fecha_entrega_proveedor, $pickup, $ingreso_agente, $mawb, $hawb, $fecha_radicacion_bls, $fletes, $origen, $puerto_embarque, $destino_final, $vapor, $viaje, $naviera, $cantidad, $tipo_contenedor, $cutoff, $etd, $eta, $ata, $desconsolidacion, $inspeccion, $ingreso_deposito, $facturar_a, $entrega_documentos_a, $fecha_dec_importacion, $pago_tributos, $levante, $entrega_cliente, $observaciones, $id, $tipo) {
	$registro = "update embarques_seguimiento set estado='" . $estado . "',exportador='" . $exportador . "',importador='" . $importador . "',asesor='" . $asesor . "',via='" . $via . "',do_ls='" . $do_ls . "',referencia_cliente1='" . $referencia_cliente1 . "',referencia_cliente2='" . $referencia_cliente2 . "',lc='" . $lc . "',fecha_estimada_entrega='" . $fecha_estimada_entrega . "',fecha_entrega_proveedor='" . $fecha_entrega_proveedor . "',pickup='" . $pickup . "',ingreso_agente='" . $ingreso_agente . "',mawb='" . $mawb . "',hawb='" . $hawb . "',fecha_radicacion_bls='" . $fecha_radicacion_bls . "',fletes='" . $fletes . "',origen='" . $origen . "',puerto_embarque='" . $puerto_embarque . "',destino_final='" . $destino_final . "',vapor='" . $vapor . "',viaje='" . $viaje . "',naviera='" . $naviera . "',cantidad='" . $cantidad . "',tipo_contenedor='" . $tipo_contenedor . "',cutoff='" . $cutoff . "',etd='" . $etd . "',eta='" . $eta . "',ata='" . $ata . "',desconsolidacion='" . $desconsolidacion . "',inspeccion='" . $inspeccion . "',ingreso_deposito='" . $ingreso_deposito . "',facturar_a='" . $facturar_a . "',entrega_documentos_a='" . $entrega_documentos_a . "',fecha_dec_importacion='" . $fecha_dec_importacion . "',pago_tributos='" . $pago_tributos . "',levante='" . $levante . "',entrega_cliente='" . $entrega_cliente . "',observaciones='" . $observaciones . "',tipo='" . $tipo . "' where id=" . $id;
	$bd_ID->sql ( $registro );
}
function creararchivo($bd_ID, $id_embarque, $nombre, $descripcion, $archivo, $id_detalle) {
	echo $registro;
	if (strcmp ( $id_detalle, "" ) == 0) {
		$id_detalle = 'null';
	}
	if ($id_detalle == NULL) {
		$id_detalle = 'null';
	}
	$registro = "insert into archivos(id_embarque,nombre, descripcion, archivo,id_detalle) values (" . $id_embarque . ",'" . $nombre . "','" . $descripcion . "','" . $archivo . "'," . $id_detalle . ")";
	echo $registro;
	$bd_ID->sql ( $registro );
}
function eliminararchivo($bd_ID, $id) {
	$registro = "delete from archivos where id=" . $id;
	echo $registro;
	$bd_ID->sql ( $registro );
}
function asignarCampo($bd_ID, $campo, $usuario, $orden) {
	/*
	 * $updateorden="update camposxusuarios set orden=orden+1 where id_usuario='".$usuario."' and order>=".$orden;
	 * $bd_ID->sql($updateorden);
	 */
	$registro = "insert into camposxusuario(id_campo,id_usuario, orden) values(" . $campo . "," . $usuario . "," . $orden . ")";
	$bd_ID->sql ( $registro );
}
function asignarCampoDetalle($bd_ID, $campo, $usuario, $orden) {
	/*
	 * $updateorden="update camposxusuarios set orden=orden+1 where id_usuario='".$usuario."' and order>=".$orden;
	 * $bd_ID->sql($updateorden);
	 */
	$registro = "insert into camposxusuariodetalle(id_campo,id_usuario, orden) values(" . $campo . "," . $usuario . "," . $orden . ")";
	$bd_ID->sql ( $registro );
}
function asignarCampoForma($bd_ID, $campo, $usuario, $orden) {
	$registro = "update camposxusuario set forma='Si' where id_campo=" . $campo . " and id_usuario=" . $usuario;
	// echo $registro."<br>";
	$bd_ID->sql ( $registro );
}
function asignarCampoListado($bd_ID, $campo, $usuario, $orden) {
	$registro = "update camposxusuario set listado='Si' where id_campo=" . $campo . " and id_usuario=" . $usuario;
	// echo $registro."<br>";
	$bd_ID->sql ( $registro );
}
function asignarCampoMail($bd_ID, $campo, $usuario, $orden) {
	$registro = "update camposxusuario set mail='Si' where id_campo=" . $campo . " and id_usuario=" . $usuario;
	// echo $registro."<br>";
	$bd_ID->sql ( $registro );
}
function limpiarCampos($bd_ID, $usuario, $orden) {
	$registro = "update camposxusuario set forma='No',listado='No',mail='No' where id_campo=" . $campo . ",id_usuario=" . $usuario;
	$bd_ID->sql ( $registro );
}
function asignarEnvio($bd_ID, $id_cliente, $nit) {
	/*
	 * $updateorden="update camposxusuarios set orden=orden+1 where id_usuario='".$usuario."' and order>=".$orden;
	 * $bd_ID->sql($updateorden);
	 */
	$registro = "insert into empresasxenvios(id_usuario,nit) values(" . $id_cliente . "," . $nit . ")";
	$bd_ID->sql ( $registro );
}
function eliminarCampo($bd_ID, $campo, $usuario, $orden) {
	$registro = "delete from camposxusuarios where id_usuario='" . $usuario . "' and id_campo='" . $campo . "'";
	$bd_ID->sql ( $registro );
	/*
	 * $updateorden="update camposxusuarios set orden=orden-1 where id_usuario='".$usuario."' and order>=".$orden;
	 * $bd_ID->sql($updateorden);
	 */
}
function consultarCampos($bd_ID, $usuario) {
	$registro = "select campo from camposxusuario where $id_usuario='" . usuario . "' order by orden";
	$ID_datos = $bd_ID->sql ( $registro );
	$campos;
	while ( $row_datos = mysql_fetch_object ( $ID_datos ) ) {
		$campos [] = $row_datos->campo;
	}
	return $campos;
}
function tienePermiso($bd_ID, $usuario, $campo) {
	$registro = "select id_campo from camposxusuario where id_usuario='" . $usuario . "' and id_campo=" . $campo;
	$ID_datos = $bd_ID->sql ( $registro );
	if ($row_datos = mysql_fetch_object ( $ID_datos )) {
		return true;
	}
	return false;
}
function consultarClientes($bd_ID, $cadena) {
	$registro = "select nit from empresas where nombres like '%" . $cadena . "%' order by nombres";
	$ID_datos = $bd_ID->sql ( $registro );
	$usuarios;
	while ( $row_datos = mysql_fetch_object ( $ID_datos ) ) {
		$usuarios [] = $row_datos->nit;
	}
	return $usuarios;
}
function enviarMailHTML($destinos, $mensaje) {

	$mensaje .= "<br>Enviado el " . date ( 'd/m/Y', time () );
	/*
	 * require_once "Mail.php";
	 * require_once "mail/Mime.php";
	 */
	$from = " LSGROUP <lsgroup@lsupplier.com>";
	$reply = "lsgroup@lsupplier.com";
	$to = "";
	$bcc = "camilo.garzon@seft.net, liliana@seft.net ";
	foreach ( $destinos[0] as $destino ) {	
		if ($destino != "") {			
			$to = $to . ", " . $destino;
		}
	}
	echo "<br>Enviando:".$to."</br>";
	$subject = "Seguimiento Embarque";
	
	$host = "hosting.seft.net";
	$username = "correoweb@seft.net";
	$password = "web";
	
	$headers = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	$headers .= "To: " . $to . "\r\n";
	$headers .= "From:" . $from . "\r\n";
	$headers .= "Cc: \r\n";
	$headers .= "Bcc: ".$bcc."\r\n";
	$headers .= " Return-Path: lsgroup@lsupplier.com \r\n";
	
	/*
	 * echo $to;
	 * echo "<br>";
	 * echo $headers;
	 * echo "<br>";
	 * echo $subject;
	 * echo "<br>";
	 * echo $mensaje;
	 * echo "<br>";
	 */
	/*if (mail ( $to, $subject, $mensaje, $headers )) {
		echo "sent";
	} else {
		echo "error";
	}*/
	
# Invoke JMail Class
$mailer = JFactory::getMailer();
	$mailer->setSender($from);
	$mailer->addRecipient('camilo@seft.net');
	foreach ( $destinos[0] as $destino ) {	
		if ($destino != "") {			
			$mailer->addRecipient($destino);
		}
	}
	var_dump($destinos[1]);
	foreach ( $destinos[1] as $destino ) {	
		if ($destino != "") {			
			$mailer->addRecipient($destino);
		}
	}
	$mailer->setSubject($subject);
$mailer->setBody($mensaje);
	var_dump($mailer);

# If you would like to send as HTML, include this line; otherwise, leave it out
$mailer->isHTML();

# Send once you have set all of your options
$mailer->send();
	/*
	 * $headers = array ('From' => $from,
	 * 'To' => $to,
	 * 'Reply-to' => $reply,
	 * 'Subject' => $subject,'MIME-Version: 1.0','Content-type: text/html; charset=iso-8859-1');
	 *
	 * $smtp = Mail::factory('smtp',
	 * array ('host' => $host,
	 * 'auth' => true,
	 * 'username' => $username,
	 * 'password' => $password));
	 * $crlf = "\r\n";
	 * $mime = new Mail_mime();
	 * $mime->setHtmlBody($mensaje);
	 * $body=$mime->get();
	 * $headers=$mime->headers($headers);
	 *
	 * $mail = $smtp->send($to, $headers, $body);
	 *
	 * if (PEAR::isError($mail)) {
	 * echo("<p>" . $mail->getMessage() . "</p>");
	 * } else {
	 * echo("<p>El mensaje fue enviado!</p>");
	 *
	 * }
	 */
	echo "Termino de enviar el mail";
}
function crearCampo($bd_ID, $nombre, $etiqueta, $tipo, $obligatorio, $orden) {
	$sql = "select max(orden)+1 as orden from campos";
	$ID_datos = $bd_ID->sql ( $sql );
	if ($row_datos = mysql_fetch_object ( $ID_datos )) {
		$orden = $row_datos->orden;
	}
	$sql = "insert into campos(nombre, etiqueta,tipo, obligatorio, orden) values ('" . $nombre . "', '" . $etiqueta . "','" . $tipo . "','" . $obligatorio . "'," . $orden . ")";
	$bd_ID->sql ( $sql );
	$sql = "alter table embarques_seguimiento add column " . $nombre;
	if (strcmp ( $tipo, "Texto" ) == 0) {
		$sql = $sql . " varchar(200)";
	} else if (strcmp ( $tipo, "Fecha" ) == 0) {
		$sql = $sql . " datetime";
	} else if (strcmp ( $tipo, "Numero" ) == 0) {
		$sql = $sql . " decimal";
	}
	$bd_ID->sql ( $sql );
}
function crearCampoDetalle($bd_ID, $nombre, $etiqueta, $tipo, $obligatorio, $orden) {
	$sql = "select max(orden)+1 as orden from camposdetalle";
	$ID_datos = $bd_ID->sql ( $sql );
	if ($row_datos = mysql_fetch_object ( $ID_datos )) {
		$orden = $row_datos->orden;
	}
	$sql = "insert into camposdetalle(nombre, etiqueta,tipo, obligatorio, orden) values ('" . $nombre . "', '" . $etiqueta . "','" . $tipo . "','" . $obligatorio . "'," . $orden . ")";
	$bd_ID->sql ( $sql );
	$sql = "alter table embarques_detalles add column " . $nombre;
	if (strcmp ( $tipo, "Texto" ) == 0) {
		$sql = $sql . " varchar(200)";
	} else if (strcmp ( $tipo, "Fecha" ) == 0) {
		$sql = $sql . " datetime";
	} else if (strcmp ( $tipo, "Numero" ) == 0) {
		$sql = $sql . " decimal";
	}
	$bd_ID->sql ( $sql );
}
function modificarCampo($bd_ID, $nombre, $etiqueta, $tipo, $obligatorio, $orden, $id) {
	$sql = "update campos set nombre='" . $nombre . "', etiqueta='" . $etiqueta . "',tipo='" . $tipo . "', obligatorio='" . $obligatorio . "', order=" . $orden . " where id=" . $id;
	$bd_ID->sql ( $sql );
}
function deleteCampo($bd_ID, $id) {
	$sql = "delete from campos where id=" . $id;
	$bd_ID->sql ( $sql );
}
function consultarCampo($bd_ID, $nombre) {
	$sql = "select etiqueta, tipo, obligatorio, orden from campos where nombre='" . $nombre . "'";
	$ID_datos = $bd_ID->sql ( $sql );
	if ($row_datos = mysql_fetch_object ( $ID_datos )) {
		$campo->etiqueta = $row_datos->etiqueta;
		$campo->tipo = $row_datos->tipo;
		$campo->obligatorio = $row_datos->obligatorio;
		$campo->orden = $row_datos->orden;
		$campo->nombre = $nombre;
		return $campo;
	}
	return null;
}
function agregarDetalle($bd_ID, $id_embarque, $factura, $fecha_factura, $guia_wr, $valor_total, $cajas, $volumen, $peso, $fecha_wr, $pedido_original) {
	$sql = "insert into embarques_detalles(factura,id_embarque,fecha_factura,guia_wr,valor_total,cajas,volumen,peso,fecha_wr,pedido_original) values ('" . $factura . "','" . $id_embarque . "','" . $fecha_factura . "','" . $guia_wr . "','" . $valor_total . "','" . $cajas . "','" . $volumen . "','" . $peso . "','" . $fecha_wr . "','" . $pedido_original . "')";
	$bd_ID->sql ( $sql );
}
function editarDetalle($bd_ID, $id, $id_embarque, $factura, $fecha_factura, $guia_wr, $valor_total, $cajas, $volumen, $peso, $fecha_wr, $pedido_original) {
	$sql = "update embarques_detalles set factura='" . $factura . "',fecha_factura='" . $fecha_factura . "',guia_wr='" . $guia_wr . "',valor_total='" . $valor_total . "',cajas='" . $cajas . "',volumen='" . $volumen . "',peso='" . $peso . "',fecha_wr='" . $fecha_wr . "',pedido_original='" . $pedido_original . "' where id=" . $id;
	$bd_ID->sql ( $sql );
}
function eliminarDetalle($bd_ID, $id) {
	$sql = "delete from embarques_detalles where id=" . $id;
	$bd_ID->sql ( $sql );
}
function consolidar($bd_ID, $id, $embarque) {
	$sql = "update embarques_detalles set id_embarque=" . $embarque . " where id=" . $id;
	// echo $sql;
	$bd_ID->sql ( $sql );
	$sql = "update archivos set id_embarque=" . $embarque . " where id_detalle=" . $id;
	$bd_ID->sql ( $sql );
}
function register_global_array($sg) {
	Static $superGlobals = array (
			'e' => '_ENV',
			'g' => '_GET',
			'p' => '_POST',
			'c' => '_COOKIE',
			'r' => '_REQUEST',
			's' => '_SERVER',
			'f' => '_FILES' 
	);
	
	Global ${$superGlobals [$sg]};
	
	foreach ( ${$superGlobals [$sg]} as $key => $val ) {
		$GLOBALS [$key] = $val;
	}
}
function register_globals($order = 'gpc') {
	$_SERVER; // See Note Below
	$_ENV;
	$_REQUEST;
	
	$order = str_split ( strtolower ( $order ) );
	array_map ( 'register_global_array', $order );
}
function cargarCampos() {
	$string = file_get_contents ( "modules/mod_embarques/config/campos.json" );
	$json_a = json_decode ( $string, true );
	$campos = array ();
	foreach ( $json_a as $campos_name => $campos_a ) {
		$campo = new campo ();
		$campo->nombre = $campos_a ['nombre'];
		$campo->etiqueta = $campos_a ['etiqueta'];
		$campo->orden = $campos_a ['orden'];
		$campo->tipo = $campos_a ['tipo'];
		$campos [] = $campo;
	}
	return $campos;
}
function consultarUsuariosJoomla() {
	$db = & JFactory::getDBO ();
	$query = "SELECT * FROM #__users";
	$db->setQuery ( $query );
	
	$rows = $db->loadObjectList ();
	return $rows;
}
function consultarUsuariosJoomlaEmpresa($nit) {
	$db = & JFactory::getDBO ();
	$query = "SELECT * FROM #__users" . " where nit='" . $nit . "'";
	$db->setQuery ( $query );
	
	$rows = $db->loadObjectList ();
	return $rows;
}
function migarUsuarios() {
	$query = "select username, firstname as nit, email from exponent_user where firstname<>'830136560' ";
	mysqli_set_charset ( $connect, "utf8" );
	$bd_ID = mysqli_connect ( 'matlantic.c8quuwggkk5q.us-east-1.rds.amazonaws.com', 'lsgroupdb', 'LSGroup123', 'lsgroupdb' );
	$json = array ();
	$errores = "";
	if ($ID_datos = $bd_ID->query ( $query )) {
		$retorno = array ();
		$count = 0;
		while ( $row_datos = $ID_datos->fetch_assoc () ) {
			$dato = array ();
			$dato ['username'] = $row_datos ['username'];
			$dato ['name'] = $row_datos ['username'];
			$dato ['email'] = $row_datos ['email'];
			$dato ['nit'] = $row_datos ['nit'];
			echo $dato ['email'];
			try {
				createUserJoomla ( $dato );
			} catch ( Exception $e ) {
				echo "Error: " . $e;
			}
		}
		
		if (strlen ( $errores ) > 0) {
			echo ("Error al migrar!!!");
		}
	} else {
		echo "Query failed";
	}
	return $retorno;
}
function createUserJoomla($new) {
	$db = & JFactory::getDBO ();
	$query = "SELECT * FROM #__users" . " where nit='" . $nit . "'";
	$insertUser = "INSERT INTO  #__users( `name`, `username`, `password`, `email`, `usertype`, `gid`, nit ) VALUES( '" . $new ['name'] . "', '" . $new ['name'] . "', md5('Temporal'), '" . $new ['email'] . "', 'Registered', 18,'" . $new ['nit'] . "' );";
	
	$insertGroups = "INSERT INTO #_user_usergroup_map(user_id, group_id ) VALUES ( LAST_INSERT_ID() ,11 );";
	$query = $db->getQuery ( true );
	$columns = array (
			'name',
			'username',
			'password',
			'email',
			'nit' 
	);
	$values = array (
			"'" . $new ["name"] . "'",
			"'" . $new ['name'] . "'",
			"'" . md5 ( 'Temporal' ) . "'",
			"'" . $new ['email'] . "'",
			"'" . $new ['nit'] . "'" 
	);
	$query->insert ( $db->quoteName ( '#__users' ) )->columns ( $db->quoteName ( $columns ) )->values ( implode ( ',', $values ) );
	$db->setQuery ( $query );
	$db->execute ();
	$last_id = $db->insertid ();
	$query1 = $db->getQuery ( true );
	$columns = array (
			'user_id',
			'group_id' 
	);
	$values = array (
			$last_id,
			2 
	);
	$query1->insert ( $db->quoteName ( '#__user_usergroup_map' ) )->columns ( $db->quoteName ( $columns ) )->values ( implode ( ',', $values ) );
	$db->setQuery ( $query1 );
	$db->execute ();
}
function crearMensaje($tipoEnvio, $correos) {
	$mensaje = "<html>
<head>
<title>LS Group - Crear DO</title>
<meta http-equiv='Content-Type' content='text/html; charset=ISO-8859-1'/>
<link href='estilo.css' rel='stylesheet' type='text/css'>
</head>

<body leftmargin='0' topmargin='0' marginwidth='0' marginheight='0' >
<font size='2'><strong>Apreciado Cliente:</strong> <br><br><br>
Este es un reporte de sus embarques a nuestro cargo, para mas información por favor ingrese a nuestro sistema de informacion <a href='http://www.lsupplier.com'><font size='2'>L.S Group</font></a>, con su login y password, y por favor ingrese por la opción Atencion al clientes y despues vaya al link Mis embarques. <br>
Si usted quiere obtener información de todos sus embarques, sencillamente haga click en buscar sin ingresar ningun dato. Por el contrario si quiere obtener informacion de un embarque en especial ingrese los datos deseados y haga click en buscar. <br>
</font><br><br>
<table width='100%' border='1' cellspacing='0' cellpadding='2'>
	<tr>";
	$session = JFactory::getSession ();
	$datos = $session->get ( "embarquesParaEnviar" );
	
	if ($tipoEnvio == "Interno") {
		$mensaje = $mensaje . "
		<th bgcolor='#FF9933'><font size='1'>Exportador</font></th>
				<th bgcolor='#FF9933'><font size='1'>Importador</font></th>
				<th bgcolor='#FF9933'><font size='1'>REF 1 Cliente</font></th>
				<th bgcolor='#FF9933'><font size='1'>MAWB</font></th>
				<th bgcolor='#FF9933'><font size='1'>ETD</font></th>
				<th bgcolor='#FF9933'><font size='1'>ETA</font></th>				
				<th bgcolor='#FF9933'><font size='1'>ATA</font></th>
				<th bgcolor='#FF9933'><font size='1'>Destino</font></th>
				<th bgcolor='#FF9933'><font size='1'>Origen</font></th>
				<th bgcolor='#FF9933'><font size='1'>HAWB</font></th>
				<th bgcolor='#FF9933'><font size='1'>Observaciones</font></th>
				</tr>";
		
		foreach ( $datos as $dato ) {
			$mensaje = $mensaje . "
			<tr>";
			$mensaje = $mensaje . "<td><font size='1'>" . $dato ["exportador"] . "</font></td>";
			$mensaje = $mensaje . "<td><font size='1'>" . $dato ["importador"] . "</font></td>";
			$mensaje = $mensaje . "<td><font size='1'>" . $dato ["referencia_cliente1"] . "</font></td>";
			$mensaje = $mensaje . "<td nowrap><font size='1'>" . $dato ["mawb"] . "</font></td>";
			$mensaje = $mensaje . "<td nowrap><font size='1'>" . $dato ["etd"] . "</font></td>";
			$mensaje = $mensaje . "<td nowrap><font size='1'>" . $dato ["eta"] . "</font></td>";
			$mensaje = $mensaje . "<td nowrap><font size='1'>" . $dato ["ata"] . "</font></td>";
			$mensaje = $mensaje . "<td><font size='1'>" . $dato ["destino_final"] . "</font></td>";
			$mensaje = $mensaje . "<td><font size='1'>" . $dato ["origen"] . "</font></td>";
			$mensaje = $mensaje . "<td><font size='1'>" . $dato ["hawb"] . "</font></td>";
			$mensaje = $mensaje . "<td><font size='1'>" . $dato ["observaciones"] . "</font></td>";
			$mensaje = $mensaje . "</tr>";
		}
	} else {		
		$cliente = cargarEmpresa ( $datos [0] ["id_cliente"] );		
		
		foreach ( $cliente->campos_mail as $campo ) {
			$mensaje = $mensaje . "<th bgcolor='#FF9933'><font size='1'>" . $campo . "</font></th>";
		}
		$mensaje = $mensaje . "</tr>";
		foreach ( $datos as $dato ) {
			$mensaje = $mensaje . "<tr>";
			foreach ( $cliente->campos_mail as $campo ) {
				$mensaje = $mensaje . "<td nowrap><font size='1'>" . $dato [$campo] . "</font></td>";
			}
			$mensaje = $mensaje . "</tr>";
		}
		$correos_clientes=consultarCorreosClientes($datos [0] ["id_cliente"]);
		foreach($correos_clientes as $correoc){
			$correos[0][]=$correoc;
		}
		
	}
	$mensaje = $mensaje . "</table><br>Cordialmente<br>
<strong>LOGISTICS SUPPLIER GROUP S.A </strong></body></html>
";
$mensaje=str_replace("00:00:00","",$mensaje);
var_dump($mensaje);
enviarMailHTML ( $correos, $mensaje );
}
function consultarCorreosClientes($cliente){
	$retorno=array();
	$usuarios=consultarUsuariosJoomlaEmpresa($cliente);	
	foreach($usuarios as $usuario){
		
		$retorno[]=$usuario->email;
	}
	
	return $retorno;
}

?>