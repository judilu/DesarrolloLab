<?php
include 'genericos.php';
require_once('../data/conexion.php');

  $usuario=usuario();
  $claveLab       = GetSQLValueString(obtieneCveLab($usuario),"text");

  $FI       = "'2016-01-28'";
  $FF       = "'2016-06-10'";
  $conexion = conectaBDSICLAB();
  $categorias = array('MES');
  $enero = array('01');
  $febrero = array('02');
  $marzo = array('03');
  $abril = array('04');
  $mayo = array('05');
  $junio = array('06');
  $julio = array('07');
  $agosto = array('08');
  $septiembre = array('09');
  $octubre = array('10');
  $noviembre = array('11');
  $diciembre = array('12');

  $categorias[]='USO';

  $consultaEnero=mysql_query("select count(month(fechaEntrada))as CANTIDAD from lbentradasalumnos e
                              WHERE month(e.fechaEntrada)=1
                              AND year(e.fechaEntrada)=year(NOW())
                              and e.claveLaboratorio=%s
                              and e.fechaEntrada between %s and %s
                              group by month(e.fechaEntrada)",$claveLab,$FI,$FF);
  $row = mysql_fetch_array($consultaEnero);
  if($row["CANTIDAD"]!=NULL)
    $enero[]=$row["CANTIDAD"];
  else
    $enero[]=0;
  
  $consultaFebrero = mysql_query("select count(month(fechaEntrada))as CANTIDAD from lbentradasalumnos e
                              WHERE month(e.fechaEntrada)=2
                              AND year(e.fechaEntrada)=year(NOW())
                              and e.claveLaboratorio=%s
                              and e.fechaEntrada between %s and %s
                              group by month(e.fechaEntrada)",$claveLab,$FI,$FF);
  $row = mysql_fetch_array($consultaFebrero);
  if($row["CANTIDAD"]!=NULL)
    $febrero[]=$row["CANTIDAD"];
  else
    $febrero[]=0;
  $consultaMarzo = mysql_query("select count(month(fechaEntrada))as CANTIDAD from lbentradasalumnos e
                              WHERE month(e.fechaEntrada)=3
                              AND year(e.fechaEntrada)=year(NOW())
                              and e.claveLaboratorio=%s
                              and e.fechaEntrada between %s and %s
                              group by month(e.fechaEntrada)",$claveLab,$FI,$FF);
  $row = mysql_fetch_array($consultaMarzo);
  if($row["CANTIDAD"]!=NULL)
    $marzo[]=$row["CANTIDAD"];
  else
    $marzo[]=0;

$consultaAbril = mysql_query("select count(month(fechaEntrada))as CANTIDAD from lbentradasalumnos e
                              WHERE month(e.fechaEntrada)=4
                              AND year(e.fechaEntrada)=year(NOW())
                              and e.claveLaboratorio=%s
                              and e.fechaEntrada between %s and %s
                              group by month(e.fechaEntrada)",$claveLab,$FI,$FF);
  $row = mysql_fetch_array($consultaAbril);
  if($row["CANTIDAD"]!=NULL)
    $abril[]=$row["CANTIDAD"];
  else
    $abril[]=0;
 
    $consultaMayo = mysql_query("select count(month(fechaEntrada))as CANTIDAD from lbentradasalumnos e
                              WHERE month(e.fechaEntrada)=5
                              AND year(e.fechaEntrada)=year(NOW())
                              and e.claveLaboratorio=%s
                              and e.fechaEntrada between %s and %s
                              group by month(e.fechaEntrada)",$claveLab,$FI,$FF);
  $row = mysql_fetch_array($consultaMayo);
  if($row["CANTIDAD"]!=NULL)
    $mayo[]=$row["CANTIDAD"];
  else
    $mayo[]=0;

  $consultaJunio = mysql_query("select count(month(fechaEntrada))as CANTIDAD from lbentradasalumnos e
                              WHERE month(e.fechaEntrada)=6
                              AND year(e.fechaEntrada)=year(NOW())
                              and e.claveLaboratorio=%s
                              and e.fechaEntrada between %s and %s
                              group by month(e.fechaEntrada)",$claveLab,$FI,$FF);
  $row = mysql_fetch_array($consultaJunio);
  if($row["CANTIDAD"]!=NULL)
    $junio[]=$row["CANTIDAD"];
  else
    $junio[]=0;

$consultaJulio = mysql_query("select count(month(fechaEntrada))as CANTIDAD from lbentradasalumnos e
                              WHERE month(e.fechaEntrada)=7
                              AND year(e.fechaEntrada)=year(NOW())
                              and e.claveLaboratorio=%s
                              and e.fechaEntrada between %s and %s
                              group by month(e.fechaEntrada)",$claveLab,$FI,$FF);
  $row = mysql_fetch_array($consultaJulio);
  if($row["CANTIDAD"]!=NULL)
    $julio[]=$row["CANTIDAD"];
  else
    $julio[]=0;

$consultaAgosto = mysql_query("select count(month(fechaEntrada))as CANTIDAD from lbentradasalumnos e
                              WHERE month(e.fechaEntrada)=8
                              AND year(e.fechaEntrada)=year(NOW())
                              and e.claveLaboratorio=%s
                              and e.fechaEntrada between %s and %s
                              group by month(e.fechaEntrada)",$claveLab,$FI,$FF);
  $row = mysql_fetch_array($consultaAgosto);
  if($row["CANTIDAD"]!=NULL)
    $agosto[]=$row["CANTIDAD"];
  else
    $agosto[]=0;

  $consultaSeptiembre = mysql_query("select count(month(fechaEntrada))as CANTIDAD from lbentradasalumnos e
                              WHERE month(e.fechaEntrada)=9
                              AND year(e.fechaEntrada)=year(NOW())
                              and e.claveLaboratorio=%s
                              and e.fechaEntrada between %s and %s
                              group by month(e.fechaEntrada)",$claveLab,$FI,$FF);
  $row = mysql_fetch_array($consultaSeptiembre);
  if($row["CANTIDAD"]!=NULL)
    $septiembre[]=$row["CANTIDAD"];
  else
    $septiembre[]=0;

  $consultaOctubre = mysql_query("select count(month(fechaEntrada))as CANTIDAD from lbentradasalumnos e
                              WHERE month(e.fechaEntrada)=10
                              AND year(e.fechaEntrada)=year(NOW())
                              and e.claveLaboratorio=%s
                              and e.fechaEntrada between %s and %s
                              group by month(e.fechaEntrada)",$claveLab,$FI,$FF);
  $row = mysql_fetch_array($consultaOctubre);
  if($row["CANTIDAD"]!=NULL)
    $octubre[]=$row["CANTIDAD"];
  else
    $octubre[]=0;

  $consultaNoviembre = mysql_query("select count(month(fechaEntrada))as CANTIDAD from lbentradasalumnos e
                              WHERE month(e.fechaEntrada)=11
                              AND year(e.fechaEntrada)=year(NOW())
                              and e.claveLaboratorio=%s
                              and e.fechaEntrada between %s and %s
                              group by month(e.fechaEntrada)",$claveLab,$FI,$FF);
  $row = mysql_fetch_array($consultaNoviembre);
  if($row["CANTIDAD"]!=NULL)
    $noviembre[]=$row["CANTIDAD"];
  else
    $noviembre[]=0;

  $consultaDiciembre = mysql_query("select count(month(fechaEntrada))as CANTIDAD from lbentradasalumnos e
                              WHERE month(e.fechaEntrada)=12
                              AND year(e.fechaEntrada)=year(NOW())
                              and e.claveLaboratorio=%s
                              and e.fechaEntrada between %s and %s
                              group by month(e.fechaEntrada)",$claveLab,$FI,$FF);
  $row = mysql_fetch_array($consultaDiciembre);
  if($row["CANTIDAD"]!=NULL)
    $diciembre[]=$row["CANTIDAD"];
  else
    $diciembre[]=0;

  echo json_encode(array($categorias,$enero,$febrero,$marzo,$abril,$mayo,$junio,$julio,$agosto,$septiembre,$octubre,$noviembre,$diciembre) );
  
?>