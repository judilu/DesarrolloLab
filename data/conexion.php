<?php
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
{
  if ($theDefinedValue!="sinhtml")
  {
    $theValue = strip_tags(htmlentities($theValue));
  }
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'".$theValue."'" : "NULL";
      break;
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "sincomillas":
      $theValue = ($theValue != "") ? $theValue : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

function conectaBDSIE()
{
	//Servidor, Usuario, Contraseña
	//$conexion = mysql_connect("67.228.19.168", "sieapibduser", "B5fa4x_7*.*") ;
  $conexion = mysql_connect("itculiacan.edu.mx", "sieapibduser", "B5fa4x_7*.*") ;
/*  if(!$conexion){
    die("error " . mysql_error());
  }*/
	//Seleccionamos la BD
mysql_select_db("sieapibd");
	return $conexion;
}
function conectaBDSICLAB()
{
	//Servidor, Usuario, Contraseña
	$conexion = mysql_connect("itculiacan.edu.mx","siclab_useritc","Ef22Ap17Jg12*.*");
	//Seleccionamos la BD
mysql_select_db("siclabbd");
mysql_query("SET NAMES UTF8");
	return $conexion;
}
?>