<?php
include('../data/conexion.php');


function validaUsuario()
{
	$respuesta		= false;
	$usu 			= GetSQLValueString($_POST["usuario"],"text");
	$cve 			= GetSQLValueString(md5($_POST["cveUsuario"]),"text");
	//$usu2 = $_POST["usuario"];

	//$cve2= md5($_POST["cveUsuario"]);
	$tipo			= "0";
	$usuario 		= "";
	$claveUsuario 	= "";
	$conexion 		= conectaBDSICLAB();
	$consulta 		= sprintf("select * from lbusuarios where usuario=%s and cveUsuario=%s limit 1",$usu,$cve);
	//$consulta 		= sprintf("select * from lbusuarios where usuario='%s' and cveUsuario='%s' limit 1",$usu2,$cve2);
	
	//var_dump($consulta);
	$res			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		$respuesta = true;
		$claveUsuario = $row["claveUsuario"];
		$usuario = $row["usuario"];
		$tipo = $row["tipoUsuario"];
	}
	$arrayJSON = array('respuesta' 		=> $respuesta,
						'claveUsuario' 	=> $claveUsuario,
						'usuario' 		=> $usuario,
						'tipo' 			=> $tipo,
						);
	print json_encode($arrayJSON);
}
function claveUsuario1()
{
	$usu 			= GetSQLValueString($_POST["usuario"],"text");
	$respuesta		= false;
	$claveUsuario	= -1;
	$conexion 		= conectaBDSICLAB();
	$consulta 		= sprintf("select * from lbusuarios where usuario=%s limit 1",$usu);
	$res			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		$respuesta = true;
		$claveUsuario = (int)$row["claveUsuario"];
	}
	$arrayJSON = array('respuesta' => $respuesta,
						'claveUsuario' => $claveUsuario);
	print json_encode($arrayJSON); 
} 
function cambiarContra ()
{
	$usu 			= GetSQLValueString($_POST["usuario"],"int");
	$clave 			= GetSQLValueString(md5($_POST["clave"]),"text");
	$respuesta		= false;
	$conexion 		= conectaBDSICLAB();
	$consulta  	= sprintf("update lbusuarios set cveUsuario =%s where claveUsuario =%d",$clave,$usu);
	$res 	 	=  mysql_query($consulta);
	if(mysql_affected_rows()>0)
	{
		$respuesta = true;
	}
	$arrayJSON = array('respuesta' => $respuesta);
	print json_encode($arrayJSON);
}
//Menú principal
$opc = $_POST["opc"];
switch ($opc){
	case 'validaUsuario':
	validaUsuario();
	break;
	case 'claveUsuario1':
	 claveUsuario1();
	case 'cambiarContra1':
		cambiarContra();
		break;
} 
?>