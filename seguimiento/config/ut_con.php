<?php
$host = "matlantic.c8quuwggkk5q.us-east-1.rds.amazonaws.com";
$db = "lsgroupdb";
$user = "lsgroupdb";
$pass = "LSGroup123";
class BD {
	var $BaseDatos;
	var $Servidor;
	var $Usuario;
	var $Clave;
	var $Conexion_ID = 0;
	var $Consulta_ID = 0;
	var $Resultado_ID = 0;
	var $Errno = 0;
	var $Error = "";
	var $Resultado;
	function BD($bd = "", $host = "localhost", $user = "nobody", $pass = "") {
		$this->BaseDatos = $bd;
		$this->Servidor = $host;
		$this->Usuario = $user;
		$this->Clave = $pass;
	}
	function con($bd, $host, $user, $pass) {
		if ($bd != "")
			$this->BaseDatos = $bd;
		if ($host != "")
			$this->Servidor = $host;
		if ($user != "")
			$this->Usuario = $user;
		if ($pass != "")
			$this->Clave = $pass;
		$this->Conexion_ID = new mysqli ( $this->Servidor, $this->Usuario, $this->Clave );
		if (! $this->Conexion_ID) {
			$this->Error = "Ha fallado la conexión.";
			return 0;
		}
		
		return $this->Conexion_ID;
	}
	function sql($sql = "") {
		if ($sql == "") {
			$this->Error = "No ha especificado una consulta SQL";
			return 0;
		}
		
		$this->Resultado = $this->Conexion_ID->query( $sql );
		
		if (! $this->Resultado) {
			$this->Errno =  $this->Conexion_ID->connect_errno;
			$this->Error = $this->Conexion_ID->connect_error;
		}
		return $this->Resultado;
	}
}
?>