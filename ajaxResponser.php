<?php
header ( 'Access-Control-Allow-Origin: *' );
header ( 'Content-type: application/json' );
include 'modules/mod_embarques/servicios/funcionesDynamo.php';
$parametros = array ();
$response = consultarEmpresas ( ' ' );
foreach ( $_GET as $key => $value ) {
	if (empty ( $value )) {
	} else {
		if (strcmp ( $key, "accion" ) != 0)
			$parametros [$key] = $value;
	}
}

if ((! empty ( $parametros ["id_cliente"] )) && (! empty ( $parametros ["estado_id"] ))) {
	echo $parametros ["id_cliente"];
	if ($parametros ["estado_id"] == "Finalizado") {
		$embarques = consultarEmbarquesEstadoCliente ( $parametros ["estado_id"], $parametros ["id_cliente"] );
	} else {
echo "Buscando no finalizado";
		$embarques = consultarEmbarquesNoFinalizadoCliente (  $parametros ["estado_id"],$parametros ["id_cliente"] );
	}
} elseif (! empty ( $parametros ["id_cliente"] )) {
	$embarques = consultarEmbarquesDynamo ( $parametros );
} else {
	if ($parametros ["estado_id"] == "Finalizado") {
		$embarques = consultarEmbarquesEstado ( $parametros ["estado_id"] );
	} else {
		$embarques = consultarEmbarquesEstado ( "En programacion" );
		$embarques = array_merge ( $embarques, consultarEmbarquesEstado ( "Reservado" ) );
		$embarques = array_merge ( $embarques, consultarEmbarquesEstado ( "En transito" ) );
		$embarques = array_merge ( $embarques, consultarEmbarquesEstado ( "Arribo" ) );
		$embarques = array_merge ( $embarques, consultarEmbarquesEstado ( "En proceso aduanero" ) );
		$embarques = array_merge ( $embarques, consultarEmbarquesEstado ( "Entregado" ) );
	}
}

if (strcmp($parametros ["version"],"jQuery")==0) {
	$retorno = count($embarques).'{"data": [';
	foreach ( $embarques as $embarque ) {				
		$retorno = $retorno . str_replace ( "00:00:00", "", preg_replace ( "/\r|\n/", "", '{	
		"exportador":"' . $embarque ['exportador'] . ',
		"importador":"' . trim ( $embarque ['importador'] ) . ',
		"referencia_cliente1":"' . trim ( $embarque ['referencia_cliente1'] ) . ',
		"fecha_radicacion_bls":"' . trim ( $embarque ['fecha_radicacion_bls'] ) . ',
		"mawb":"' . trim ( $embarque ['mawb'] ) . ',
		"etd":" ' . trim ( explode ( " ", ($embarque ['etd']) ) [0] ) . ',
		"eta":"' . trim ( explode ( " ", ($embarque ['eta']) ) [0] ) . ',
		"ata":"' . trim ( explode ( " ", ($embarque ['ata']) ) [0] ) . ',
		"destino_final":"' . trim ( $embarque ['destino_final'] ) . ',
		"observaciones":"' . trim ( $embarque ['observaciones'] ) . ',
		"operativo":"' . trim ( $embarque ['operativo'] ) . ',
		"vendedor":"' . trim ( $embarque ['vendedor'] ) . ',
		"pais":"' . trim ( $embarque ['pais'] ) . ',
		"estado":"' . trim ( $embarque ['estado'] ) . ',
		"via":"' . trim ( $embarque ['via'] ) . ',
		"tipo":"' . trim ( $embarque ['tipo'] ) . ',
		"origen":"' . trim ( $embarque ['origen'] ) . ',
		"destino_final":"' . trim ( $embarque ['destino_final'] ) . ',
		"do_ls":"' . trim ( $embarque ['do_ls'] ) . ',
		"naviera_ls":"' . trim ( $embarque ['naviera_ls'] ) . ',
		"vapor_ls":"' . trim ( $embarque ['vapor_ls'] ) . ',
		"referencia_cliente1":"' . trim ( $embarque ['referencia_cliente1'] ) . ',
		"hawb":"' . trim ( $embarque ['hawb'] ) . ',
		"acciones":"<a href=embarques?accion=ConsultarEmbarque&id_embarque=' . $embarque ["id"] . '&id_cliente=' . $embarque ["id_cliente"] . ' target=_blank>Ver</a></br><a href=embarques?accion=AgregarEnvio&id_embarque=' . $embarque ["id"] . '&id_cliente=' . $embarque ["id_cliente"] . ' target=_blank>Agregar</a><br><a href=# onclick=return confirm(' . "Are you sure?" . ')>Eliminar</a>"},' ) );
	}
	
	$retorno = rtrim ( $retorno, ',' );
	$retorno=$retorno.']}';
} else {
	
	$retorno = '{"cols": [
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
		{"id":"operativo","label":"Operativo","type":"string"},
		{"id":"vendedor","label":"Vendedor","type":"string"},
		{"id":"pais","label":"Pais","type":"string"},
		{"id":"estado","label":"Estado","type":"string"},
		{"id":"via","label":"Via","type":"string"},
		{"id":"tipo","label":"Tipo","type":"string"},
		{"id":"origen","label":"Origen","type":"string"},
		{"id":"destino","label":"Destino","type":"string"},
		{"id":"do","label":"DO","type":"string"},
		{"id":"naviera","label":"Naviera","type":"string"},
		{"id":"vapor","label":"Vapor","type":"string"},
		{"id":"referencia","label":"Referencia","type":"string"},
		{"id":"hawb","label":"HAWB","type":"string"},
		{"id":"Accion","label":"Accion","type":"string"}
      ],
"rows": [';
	foreach ( $embarques as $embarque ) {
		
		$retorno = $retorno . str_replace ( "00:00:00", "", preg_replace ( "/\r|\n/", "", '{"c":[	
		{"v":"' . $embarque ['exportador'] . '",p:{style: "font-size: 7pt "}},
		{"v":"' . trim ( $embarque ['importador'] ) . '",p:{style: "font-size: 7pt "}},
		{"v":"' . trim ( $embarque ['referencia_cliente1'] ) . '",p:{style: "font-size: 7pt "}},
		{"v":"' . trim ( $embarque ['fecha_radicacion_bls'] ) . '",p:{style: "font-size: 7pt "}},
		{"v":"' . trim ( $embarque ['mawb'] ) . '",p:{style: "font-size: 7pt "}},
		{"v":" ' . trim ( explode ( " ", ($embarque ['etd']) ) [0] ) . '",p:{style: "font-size: 7pt; white-space: nowrap "}},
		{"v":"' . trim ( explode ( " ", ($embarque ['eta']) ) [0] ) . '",p:{style: "font-size: 7pt; white-space: nowrap "}},
		{"v":"' . trim ( explode ( " ", ($embarque ['ata']) ) [0] ) . '",p:{style: "font-size: 7pt; white-space: nowrap "}},
		{"v":"' . trim ( $embarque ['destino_final'] ) . '",p:{style: "font-size: 7pt "}},
		{"v":"' . trim ( $embarque ['observaciones'] ) . '",p:{style: "font-size: 7pt "}},
		{"v":"' . trim ( $embarque ['operativo'] ) . '",p:{style: "font-size: 7pt "}},
		{"v":"' . trim ( $embarque ['vendedor'] ) . '",p:{style: "font-size: 7pt "}},
		{"v":"' . trim ( $embarque ['pais'] ) . '",p:{style: "font-size: 7pt "}},
		{"v":"' . trim ( $embarque ['estado'] ) . '",p:{style: "font-size: 7pt "}},
		{"v":"' . trim ( $embarque ['via'] ) . '",p:{style: "font-size: 7pt "}},
		{"v":"' . trim ( $embarque ['tipo'] ) . '",p:{style: "font-size: 7pt "}},
		{"v":"' . trim ( $embarque ['origen'] ) . '",p:{style: "font-size: 7pt "}},
		{"v":"' . trim ( $embarque ['destino_final'] ) . '",p:{style: "font-size: 7pt "}},
		{"v":"' . trim ( $embarque ['do_ls'] ) . '",p:{style: "font-size: 7pt "}},
		{"v":"' . trim ( $embarque ['naviera_ls'] ) . '",p:{style: "font-size: 7pt "}},
		{"v":"' . trim ( $embarque ['vapor_ls'] ) . '",p:{style: "font-size: 7pt "}},
		{"v":"' . trim ( $embarque ['referencia_cliente1'] ) . '",p:{style: "font-size: 7pt "}},
		{"v":"' . trim ( $embarque ['hawb'] ) . '",p:{style: "font-size: 7pt "}},
		{"v":"<a href=embarques?accion=ConsultarEmbarque&id_embarque=' . $embarque ["id"] . '&id_cliente=' . $embarque ["id_cliente"] . ' target=_blank>Ver</a></br><a href=embarques?accion=AgregarEnvio&id_embarque=' . $embarque ["id"] . '&id_cliente=' . $embarque ["id_cliente"] . ' target=_blank>Agregar</a><br><a href=# onclick=return confirm(' . "Are you sure?" . ')>Eliminar</a>"}]},' ) );
	}
	
	$retorno = rtrim ( $retorno, ',' );
}
echo $retorno;
?>