<?php
require_once('../data/conexion.php');
function salir()
{
	session_start();
	session_destroy();
	$respuesta = true;
	$arrayJSON = array('respuesta' => $respuesta);
	print json_encode($arrayJSON);
}
function periodoActual ()
{
	$conexion 		= conectaBDSIE();
	$consulta 		= sprintf("select PARFOL1 FROM DPARAM WHERE PARCVE='PRDO'");
	$res			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		return $row["PARFOL1"];
	}
}
function periodoNombre ()
{
	$periodo 		= periodoActual();
	$conexion 		= conectaBDSIE();
	$periodoNom 	= "";
	$consulta 		= sprintf("select PDODES from DPERIO where PDOCVE = %s", $periodo);
	$res			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		$periodoNom = $row["PDODES"];
	}
	return $periodoNom;
}
function fechaPeriodo ()
{
	$conexion 		= conectaBDSIE();
	$fecha 			= "";
	$periodo 		= periodoActual();
	$consulta 		= sprintf("select PDOINI,PDOTER from DPERIO where PDOCVE =%s",$periodo);
	$res			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		$fecha = $row["PDOTER"];
	}
	return $fecha;
}
function sumaUnDia($fI)
{
	$conexion 		= conectaBDSICLAB();
	$fechaI 		= GetSQLValueString($fI,"text");
	$fecha 			= "";
	$periodo 		= periodoActual();
	$consulta 		= sprintf("select DATE_ADD(%s,INTERVAL 1 DAY) as fechaF",$fechaI);
	$res			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		$fecha = $row["fechaF"];
	}
	return $fecha;
}
function claveLab($clave)
{
	$cveResp 		= $clave;
	$conexion		= conectaBDSICLAB();
	$consulta 		= sprintf("select ALUCTR from lbusuarios where claveUsuario =%d",$cveResp);
	$res 			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		return $row["ALUCTR"];
	}
	else
	{
		return 0;
	}
}
//treae el nombre del laboratorio y el departamento
function deptoLab($s)
{
	$sol 			= $s;
	$nomLab 		= "";
	$depto 			= "";
	$conexion 		= conectaBDSICLAB();
	$consulta 		= sprintf("select l.nombreLaboratorio,l.DEPCVE from lbsolicitudlaboratorios s left join lblaboratorios l on s.claveLaboratorio = l.claveLaboratorio where s.claveSolicitud=%d",$sol);
	$res			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		$nomLab = $row["nombreLaboratorio"];
		$depto  = $row["DEPCVE"];
	}
	$resultado = array('nomLab' => $nomLab,
						'depto' => $depto );
	return $resultado;
}
function nomDepto($d)
{
	$depto 			= GetSQLValueString($d,"int");
	$nomDep 		= "";
	$conexion 		= conectaBDSIE();
	$consulta 		= sprintf("select DEPNOM from DDEPTO where DEPCVE =%d",$depto);
	$res			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		$depto  = $row["DEPNOM"];
	}
	return $depto;
}
//trae el nombre corto de las materias segun una clave
function nomMat ($claves)
{
	$claveMat 	= $claves;
	$materias	= array();
	$conexion	= conectaBDSIE();
	$consulta	= sprintf("select MATCVE, MATNCO from DMATER where MATCVE IN (%s) order by MATNCO",$claveMat);
	$res 		= mysql_query($consulta);
	if($res)
	{
		while($row = mysql_fetch_array($res))
		{
			$materias[$row["MATCVE"]] =$row["MATNCO"];
		}
		return $materias;
	}
	else
	{
		return "";
	}
}
function nomMaterias ($claves)
{
	$claveMat 	= $claves;
	$materias	= array();
	$nomMaterias= array();
	$conexion	= conectaBDSIE();
	$consulta	= sprintf("select MATCVE, MATNCO from DMATER where MATCVE IN (%s) order by MATNCO",$claveMat);
	$res 		= mysql_query($consulta);
	if($res)
	{
		while($row = mysql_fetch_array($res))
		{
			$materias[] = $row["MATCVE"];
			$nomMaterias[] = $row["MATNCO"];
		}

		$arrayMaterias = array('materias' => $materias,
								'nomMaterias' => $nomMaterias);
		return $arrayMaterias;
	}
	else
	{
		$arrayMaterias = array('materias' => '',
								'nomMaterias' => '');
		return $arrayMaterias;
	}
}
function nomCarr ($cve,$gpo,$mat,$pe)
{
	$carrera 	= "";
	$maestro 	= $cve;
	$grupo 		= $gpo;
	$materia 	= $mat;
	$periodo 	= $pe;
	$conexion	= conectaBDSIE();
	$consulta	= sprintf("select c.CARNOM from DGRUPO g INNER JOIN DCARRE c on g.CARCVE = c.CARCVE where g.PERCVE =%d and g.GPOCVE =%s and g.MATCVE =%s and g.PDOCVE =%s",$maestro,$grupo,$materia,$periodo);
	$res 		= mysql_query($consulta); 
	if($row = mysql_fetch_array($res))
	{
		$carrera = $row["CARNOM"];
	}
	return $carrera;	
}
function existeCal ($clave)
{
	$claveCal	= $clave;
	$conexion 	= conectaBDSICLAB();
	$consulta 	= sprintf("select claveCalendarizacion from lbcalendarizaciones where claveCalendarizacion =%d",$claveCal);
	$res 		= mysql_query($consulta); 
	if($row = mysql_fetch_array($res))
	{
		return true;
	}
	return false;
}
function clavePractica($t,$d)
{	
	$titulo		= $t;
	$duracion 	= $d;
	$practica 	= 0;
	$conexion 	= conectaBDSICLAB();
	$consulta 	= sprintf("select clavePractica from lbpracticas WHERE tituloPractica =%s and duracionPractica =%s",$titulo,$duracion);
	$res 		= mysql_query($consulta); 
	if($res)
	{
		if($row = mysql_fetch_array($res))
		{
			$practica = (int)$row["clavePractica"];
		}
	}
	return $practica;
}
function existCal($clave,$p,$mat,$gpo,$pract,$f,$hr)
{	
	$maestro	= $clave;
	$periodo 	= $p;
	$materia 	= $mat;
	$grupo 		= $gpo;
	$practica 	= $pract;
	$fecha 		= $f;
	$hora 		= $hr;
	$cal 		= 0;
	$sol 		= 0;
	$conexion 	= conectaBDSICLAB();
	$consulta 	= sprintf("select c.claveCalendarizacion, c.claveSolicitud from lbcalendarizaciones c inner join lbsolicitudlaboratorios s on c.claveSolicitud = s.claveSolicitud where s.PERCVE=%d and s.PDOCVE=%s and s.MATCVE=%s and s.GPOCVE=%s and s.clavePractica=%d and c.fechaAsignada=%s and c.horaAsignada=%s and c.estatus = 'R'",$maestro,$periodo,$materia,$grupo,$practica,$fecha,$hora);
	$res 		= mysql_query($consulta); 
	if($res)
	{
		if($row = mysql_fetch_array($res))
		{
			$cal = (int)$row["claveCalendarizacion"];
			$sol = (int)$row["claveSolicitud"];
		}
	}
	$resultado = array('cal' => $cal,
						'sol' => $sol);
	return $resultado;
}
function numerosControl($nums)
{
	$respuesta  = false;
	$ncs 		= $nums;
	$numeros	= array();
	$nombres 	= array();
	$conexion	= conectaBDSIE();
	$consulta	= sprintf("select ALUCTR, ALUNOM, ALUAPP, ALUAPM from DALUMN WHERE ALUCTR in(%s) ORDER BY ALUAPP, ALUAPM, ALUNOM",$ncs);
	$res 		= mysql_query($consulta);
	if($res)
	{
		$respuesta = true;
		while($row = mysql_fetch_array($res))
		{	
			$numeros[] = $row["ALUCTR"];
			$nombres[] = $row["ALUAPP"]." ".$row["ALUAPM"]." ".$row["ALUNOM"];
		}
	}
	$datosAlu = array('respuesta' => $respuesta,
						 	'numeros' => $numeros, 
							'nombres' => $nombres);
	return $datosAlu;
}
function existeSol ($clave)
{
	$claveSol	= $clave;
	$respuesta  = false;
	$conexion 	= conectaBDSICLAB();
	$consulta 	= sprintf("select claveSolicitud from lbsolicitudlaboratorios where claveSolicitud =%d",$claveSol);
	$res 		= mysql_query($consulta); 
	if($row = mysql_fetch_array($res))
	{
		$respuesta = true;
	}
	return $respuesta;
}
function claveMaestro($clave)
{
	$claveUsuario 	= $clave;
	$conexion 		= conectaBDSICLAB();
	$consulta 		= sprintf("select PERCVE from lbusuarios where claveUsuario = %d",$claveUsuario);
	$res			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		return (int)($row["PERCVE"]);
	}
	else
	{
		return 0;
	}
}
function clavematerias($clave)
{
	$maestro 		= $clave;
	$periodo 		= periodoActual();
	$materias 		= "";
	$conexion 		= conectaBDSIE();
	$consulta 		= sprintf("select m.MATCVE from DMATER m inner join DGRUPO g on m.MATCVE = g.MATCVE where g.PERCVE =%d and g.PDOCVE ='%s' and g.GRUBAS = ' ' and g.INSNUM > 0",$maestro,$periodo);
	$res			= mysql_query($consulta);

	if (mysql_num_rows($res) > 0)
	{
		while($row = mysql_fetch_array($res))
		{
			$materias .= "'".($row["MATCVE"])."',";
		}
		$materias = (rtrim($materias,","));
	}
	else
	{
		$materias = "'vacio'";
	}
	return $materias;

}
function nombreMaestro($clave)
{
	$maestro 		= $clave;
	$conexion 		= conectaBDSIE();
	$consulta 		= sprintf("select PERCVE, PERNOM, PERAPE from DPERSO where PERCVE =%d",$maestro);
	$res			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		return $row;
	}
	else
	{
		return "";
	}
}
function grupo($mat,$per,$pdo,$hor)
{
	$matcve  	= $mat;
	$maestro   	= $per;
	$periodo 	= $pdo;
	$hora 		= $hor;
	/*$n 	 	 = (string)$hor;
	$n2 	 = (string)($n+1);
	$hora 	 = "";
	if (strlen($n)==1) 
	{
		$hora 		.= "0".$n."00";
		if (strlen($n2)==1)
		{
			$hora 		.= "0".$n2."00"; 
		}
		else
		{
			$hora 		.= $n2."00";
		} 
	}
	else
	{
		$hora 		.= $n."00";
		if (strlen($n2)==1)
		{
			$hora 		.= "0".$n2."00"; 
		}
		else
		{
			$hora 		.= $n2."00";
		} 
	}*/
	$conexion 		= conectaBDSIE();
	$consulta 		= sprintf("select GPOCVE from DGRUPO where MATCVE =%s AND PERCVE=%d AND PDOCVE =%s AND (LUNHRA =%s OR MARHRA =%s OR MIEHRA =%s OR JUEHRA =%s OR VIEHRA =%s) AND GRUBAS = ' ' ",$matcve,$maestro,$periodo,$hora,$hora,$hora,$hora,$hora);
	$res			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		return $row["GPOCVE"];
	}
	else
	{
		return "";
	}
}
function horaMat($mat,$per)
{
	$materia    = $mat;
	$maestro   	= $per;
	$periodo 	= periodoActual();
	$horas 		= array("");  
	$conexion 	= conectaBDSIE();
	$consulta 	= sprintf("select LUNHRA, MARHRA,MIEHRA,JUEHRA,VIEHRA from DGRUPO where MATCVE ='TIB1025' and PERCVE=920 and PDOCVE ='2161'",$materia,$maestro,$periodo);
	$res		= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		$horas = $row;
	}
	return $horas;
}
function existeSolLab($dep,$pdo,$fce,$fcs,$hrs,$lab,$pra,$mat,$gpo,$usu)
{
	$d              = $dep;
	$periodo 		= $pdo;
	$fe  			= $fce;
	$fs 			= $fcs;
	$h 				= $hrs;
	$l 				= $lab;
	$p 				= $pra;
	$m 				= $mat;
	$g 				= $gpo;
	$u     			= $usu;	
	$conexion 		= conectaBDSICLAB();
	$consulta 		= sprintf("select claveSolicitud from lbsolicitudlaboratorios where claveDependencia =%s and PDOCVE =%s and fechaEnvio =%s and  fechaSolicitud =%s and horaSolicitud =%s and claveLaboratorio =%s and clavePractica =%d and MATCVE =%s and GPOCVE =%s and claveUsuario =%s",$d,$periodo,$fe,$fs,$h,$l,$p,$m,$g,$u);
	$res			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		return $row["claveSolicitud"];
	}
	else
	{
		return 0;
	}
}
function detalleArt($nu,$so,$art,$num)
{
	$n 			= GetSQLValueString($nu,"int");
	$sol 		= GetSQLValueString($so,"int");;
	$porArt 	= $art;
	$porNum		= $num;
	$cant 		= 0;
	$respuesta 	= false;
	if($sol != 0)
	{
		for ($i=0; $i <$n; $i++) 
		{ 
			$cant 		= (int)($porNum[$i]);
			$conexion 	= conectaBDSICLAB();
			$consulta 	= sprintf("insert into lbasignaarticulospracticas values('%s',%d,%d,'V')",$porArt[$i],$sol,$cant);
			$res 		= mysql_query($consulta);
			if(mysql_affected_rows()>0)
			{
				$respuesta = true; 
			}
		}	
	}
	return $respuesta;
}
function comboMat ()
{
	session_start();
	$maestro  		= GetSQLValueString(($_SESSION['nombre']),"int");
	$fecha 			= fechaPeriodo();
	$respuesta 		= false;
	$periodo 		= periodoActual();
	$con 			= 0;
	$combomat 		= array();
	$claveMat 		= "";
	$nombreMat		= "";
	$conexion		= conectaBDSIE();
	$consulta		= sprintf("select m.MATCVE, m.MATNCO from DMATER m inner join DGRUPO g on m.MATCVE = g.MATCVE where g.PERCVE =%d and g.PDOCVE =%s and g.GRUBAS = ' ' and g.INSNUM > 0 order by m.MATNCO",$maestro,$periodo);
	$res 			= mysql_query($consulta);
	if($res)
	{
		while($row = mysql_fetch_array($res))
		{
			$combomat[] = $row;
			$respuesta = true;
			$con++;
		}
		for ($i=0; $i < $con ; $i++)
		{ 
			$claveMat[] 	=$combomat[$i]["MATCVE"];
			$nombreMat[] 	=$combomat[$i]["MATNCO"];
		}
	}
	$arrayJSON = array('respuesta' => $respuesta,
						 'claveMat' => $claveMat, 
						'nombreMat' => $nombreMat, 
						'contador' => $con,
						'fecha' => $fecha);
	print json_encode($arrayJSON);

}
function comboMatHr ()
{
	session_start();
	$maestro  		= GetSQLValueString(($_SESSION['nombre']),"int");
	$materia 		= GetSQLValueString($_POST["materia"],"text");
	$periodo 		= periodoActual();
	$respuesta 		= false;
	$comboHr 		= "";
	$comboHrMat     = array();
	$cont 			= 0;
	$conexion		= conectaBDSIE();
	$consulta		= sprintf("select LUNHRA,MARHRA,MIEHRA,JUEHRA,VIEHRA from DGRUPO where MATCVE =%s and PERCVE=%d and PDOCVE =%s",$materia,$maestro,$periodo);
	$res 			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{		
		for ($i=0; $i < 5; $i++) 
		{
		 	if($row[$i]!= "")
		 	{
				$comboHr .= $row[$i].",";
			}
		}
		$comboHr = (rtrim($comboHr,","));
		$comboHrMat = array_unique((explode(",",$comboHr)));
		$respuesta 	= true;
		$cont 		= count($comboHrMat);
	}
	$arrayJSON = array('respuesta' => $respuesta,
						 'comboHr' => $comboHrMat,
						 'cont' => $cont); 
	print json_encode($arrayJSON);

}
function comboPract()
{
	$materia 		= GetSQLValueString($_POST["materia"],"text");
	$respuesta 		= false;
	$comboPrac 		= array();
	$comboCvePrac 	= "";
	$comboTitPrac 	= "";
	$con 			= 0;
	$conexion		= conectaBDSICLAB();
	$consulta		= sprintf("select p.clavePractica, p.tituloPractica from lbpracticas p inner join lbasignapracticas ap on p.clavePractica = ap.clavePractica where p.estatus = 'V' and ap.MATCVE =%s order by p.tituloPractica",$materia);
	$res 			= mysql_query($consulta);
	while($row = mysql_fetch_array($res))
	{
			$comboPrac[] = $row;
			$respuesta = true;
			$con++;
	}
	for ($i=0; $i < $con ; $i++)
	{ 
		$comboCvePrac[] 	=$comboPrac[$i]["clavePractica"];
		$comboTitPrac[] 	=$comboPrac[$i]["tituloPractica"];
	}
	$arrayJSON = array('respuesta' => $respuesta,
						'comboCvePrac' => $comboCvePrac, 
						'comboTitPrac' => $comboTitPrac, 
						'con' => $con);
	print json_encode($arrayJSON);	
}
function comboPractRep()
{
	session_start();
	$maestro 		= $_SESSION["nombre"];
	$materia 		= GetSQLValueString($_POST["materia"],"text");
	$hora 			= $_POST["hora"];
	$periodo 		= periodoActual();	
	$grupo 			= GetSQLValueString(grupo($materia,$maestro,$periodo,$hora),"text");
	$respuesta 		= false;
	$comboPrac 		= array();
	$comboCvePrac 	= "";
	$comboTitPrac 	= "";
	$con 			= 0;
	$conexion		= conectaBDSICLAB();
	$consulta		= sprintf("select DISTINCT p.clavePractica, p.tituloPractica from lbpracticas p inner join lbsolicitudlaboratorios s on p.clavePractica = s.clavePractica inner join lbcalendarizaciones c on c.claveSolicitud = s.claveSolicitud where c.estatus = 'R' and s.GPOCVE =%s order by p.tituloPractica",$grupo);
	$res 			= mysql_query($consulta);
	while($row = mysql_fetch_array($res))
	{
			$comboPrac[] = $row;
			$respuesta = true;
			$con++;
	}
	for ($i=0; $i < $con ; $i++)
	{ 
		$comboCvePrac[] 	=$comboPrac[$i]["clavePractica"];
		$comboTitPrac[] 	=$comboPrac[$i]["tituloPractica"];
	}
	$arrayJSON = array('respuesta' => $respuesta,
						'comboCvePrac' => $comboCvePrac, 
						'comboTitPrac' => $comboTitPrac, 
						'con' => $con);
	print json_encode($arrayJSON);	
}
function comboLab()
{
	$practica 		= GetSQLValueString($_POST["practica"],"int");
	$respuesta 		= false;
	$comboLab 		= array();
	$comboCveLab 	= "";
	$comboNomLab 	= "";
	$con 			= 0;
	$conexion		= conectaBDSICLAB();
	$consulta		= sprintf("select ap.claveLaboratorio, l.nombreLaboratorio from lbasignapracticas ap inner join lbpracticas p on ap.clavePractica = p.clavePractica inner join lblaboratorios l on ap.claveLaboratorio = l.claveLaboratorio where (l.usoAsignado = '1' or '3') and p.estatus = 'V' and ap.clavePractica=%d order by l.nombreLaboratorio",$practica);
	$res 			= mysql_query($consulta);
	while($row = mysql_fetch_array($res))
	{
			$comboLab[]  = $row;
			$respuesta = true;
			$con++;
	}
	for ($i=0; $i < $con ; $i++)
	{ 
		$comboCveLab[] 	=$comboLab[$i]["claveLaboratorio"];
		$comboNomLab[] 	=$comboLab[$i]["nombreLaboratorio"];
	}
	$arrayJSON = array('respuesta' => $respuesta,
						'comboCveLab' => $comboCveLab, 
						'comboNomLab' => $comboNomLab, 
						'con' => $con);
	print json_encode($arrayJSON);
}
function comboLaboratorios()
{
	$respuesta 		= false;
	$comboCveLab 	= "";
	$comboNomLab 	= "";
	$con 			= 0;
	$conexion		= conectaBDSICLAB();
	$consulta		= sprintf("select claveLaboratorio, nombreLaboratorio from  lblaboratorios  where usoAsignado = ('1' or '3') order by nombreLaboratorio");
	$res 			= mysql_query($consulta);
	while($row = mysql_fetch_array($res))
	{
		$comboCveLab[] 	=$row["claveLaboratorio"];
		$comboNomLab[] 	=$row["nombreLaboratorio"];
		$respuesta = true;
	}
	$con = count($comboCveLab);
	$arrayJSON = array('respuesta' => $respuesta,
						'comboCveLab' => $comboCveLab, 
						'comboNomLab' => $comboNomLab, 
						'contador' => $con);
	print json_encode($arrayJSON);
}
function comboHoraPrac()
{
	$laboratorio 	= GetSQLValueString($_POST["laboratorio"],"text");
	$respuesta 		= false;
	$comboHrAp 		= "";
	$comboHrC 		= "";
	$capacidad 		= 0;
	$conexion		= conectaBDSICLAB();
	$consulta		= sprintf("select horaApertura, horaCierre, capacidad from lblaboratorios where claveLaboratorio =%s",$laboratorio);
	$res 			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
			$comboHrAp  = $row["horaApertura"];
			$comboHrC 	= $row["horaCierre"];
			$capacidad  = (int)($row["capacidad"]);
			$respuesta = true;
	}
	$arrayJSON = array('respuesta' => $respuesta,
						'horaApertura' => $comboHrAp, 
						'horaCierre' => $comboHrC,
						'capacidad' => $capacidad);
	print json_encode($arrayJSON);
}
function comboEleArt()
{
	$laboratorio 		= GetSQLValueString($_POST["laboratorio"],"text");
	$respuesta 		= false;
	$comboEleArt 	= array();
	$comboCveArt 	= "";
	$comboNomArt 	= "";
	$con 			= 0;
	$conexion		= conectaBDSICLAB();
	$consulta		= sprintf("select DISTINCT (c.nombreArticulo), c.claveArticulo from lbarticuloscat c inner join lbarticulos a on a.claveArticulo = c.claveArticulo inner join lbasignaarticulos aa on aa.identificadorArticulo = a.identificadorArticulo where aa.claveLaboratorio =%s and a.estatus = 'V' order by c.nombreArticulo",$laboratorio);
	$res 			= mysql_query($consulta);
	while($row = mysql_fetch_array($res))
	{
			$comboEleArt[]  = $row;
			$respuesta = true;
			$con++;
	}
	for ($i=0; $i < $con ; $i++)
	{ 
		$comboCveArt[] 	=$comboEleArt[$i]["claveArticulo"];
		$comboNomArt[] 	=$comboEleArt[$i]["nombreArticulo"];
	}
	$arrayJSON = array('respuesta' => $respuesta,
						'comboCveArt' => $comboCveArt, 
						'comboNomArt' => $comboNomArt, 
						'con' => $con);
	print json_encode($arrayJSON);
}
function capacidadLab()
{
	$laboratorio 	= GetSQLValueString($_POST["laboratorio"],"text");
	$respuesta 		= false;
	$capacidad 		= 0;
	$conexion		= conectaBDSICLAB();
	$consulta		= sprintf("	select capacidad from lblaboratorios where claveLaboratorio =%s",$laboratorio);
	$res 			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
			$capacidad  = (int)($row["capacidad"]);
			$respuesta = true;
	}
	$arrayJSON = array('respuesta' => $respuesta,
						'capacidad' => $capacidad);
	print json_encode($arrayJSON);
}
function valirdarFeHr($f,$h,$lab)
{
	$periodo 	 = periodoActual();
	$fecha 		 = $f;
	$hora 		 = $h;
	$laboratorio = $lab;
	$conexion 	 = conectaBDSICLAB();
	$consulta 	 = sprintf("select c.claveCalendarizacion from lbcalendarizaciones c inner join lbsolicitudlaboratorios s on c.claveSolicitud = s.claveSolicitud where c.PDOCVE =%s and c.fechaAsignada =%s and c.horaAsignada =%s AND c.estatus = 'NR' AND s.claveLaboratorio =%s",$periodo,$fecha,$hora,$laboratorio);
	$res 		 = mysql_query($consulta); 
	$respuesta   = true;
	if($row = mysql_fetch_array($res))
	{
		$respuesta = false;
	}
	return $respuesta;
}
function periodosAn()
{
	$periodos 		= array();
	$conexion		= conectaBDSIE();
	$consulta		= sprintf("select pdocve,pdodes from DPERIO where PDOINI > '2002-01-01' order by PDOCVE desc limit 3");
	$res 			= mysql_query($consulta);
	if($res)
	{
		while($row = mysql_fetch_array($res))
		{				
			$periodos[]  = $row;
		}
	}
	return $periodos;
}
function comboMatRep ()
{
	session_start();
	$maestro  		= GetSQLValueString(($_SESSION['nombre']),"int");
	$respuesta 		= false;
	$periodo 		= GetSQLValueString(($_POST['periodo']),"text");
	$con 			= 0;
	$combomat 		= array();
	$claveMat 		= "";
	$nombreMat		= "";
	$conexion		= conectaBDSIE();
	$consulta		= sprintf("select m.MATCVE, m.MATNCO from DMATER m inner join DGRUPO g on m.MATCVE = g.MATCVE where g.PERCVE =%d and g.PDOCVE =%s and g.GRUBAS = ' ' and g.INSNUM > 0 order by m.MATNCO",$maestro,$periodo);
	$res 			= mysql_query($consulta);
	if($res)
	{
		while($row = mysql_fetch_array($res))
		{
			$combomat[] = $row;
			$respuesta = true;
			$con++;
		}
		for ($i=0; $i < $con ; $i++)
		{ 
			$claveMat[] 	=$combomat[$i]["MATCVE"];
			$nombreMat[] 	=$combomat[$i]["MATNCO"];
		}
	}
	$arrayJSON = array('respuesta' => $respuesta,
						 'claveMat' => $claveMat, 
						'nombreMat' => $nombreMat, 
						'contador' => $con);
	print json_encode($arrayJSON);

}
function comboHrMatRep()
{
	session_start();
	$maestro  		= GetSQLValueString(($_SESSION['nombre']),"int");
	$materia 		= GetSQLValueString($_POST["materia"],"text");
	$periodo 		= GetSQLValueString(($_POST["periodo"]),"text");
	$respuesta 		= false;
	$comboHr 		= "";
	$comboHrMat     = array();
	$cont 			= 0;
	$conexion		= conectaBDSIE();
	$consulta		= sprintf("select LUNHRA,MARHRA,MIEHRA,JUEHRA,VIEHRA from DGRUPO where MATCVE =%s and PERCVE=%d and PDOCVE =%s",$materia,$maestro,$periodo);
	$res 			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		for ($i=0; $i < 5; $i++) 
		{ 
			$comboHr .= $row[$i].",";
		}		
		$comboHr = (rtrim($comboHr,","));
		$comboHrMat = array_unique((explode(",",$comboHr)));
		$respuesta 	= true;
		$cont 		= count($comboHrMat);
	}
	$arrayJSON = array('respuesta' => $respuesta,
						 'comboHr' => $comboHrMat,
						 'cont' => $cont); 
	print json_encode($arrayJSON);
}
function comboHoraPracRep()
{
	$practica 		= GetSQLValueString($_POST["practica"],"int");
	$respuesta 		= false;
	$comboHrAp 		= "";
	$comboHrC 		= "";
	$conexion		= conectaBDSICLAB();
	$consulta		= sprintf("select horaApertura, horaCierre from lbasignapracticas a inner join lblaboratorios l on a.claveLaboratorio = l.claveLaboratorio where a.clavePractica =%d",$practica);
	$res 			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
			$comboHrAp  = $row["horaApertura"];
			$comboHrC 	= $row["horaCierre"];
			$respuesta = true;
	}
	$arrayJSON = array('respuesta' => $respuesta,
						'horaApertura' => $comboHrAp, 
						'horaCierre' => $comboHrC);
	print json_encode($arrayJSON);
}
//Menú principal
$opc = $_POST["opc"];
switch ($opc)
{
	case 'salir1':
		salir();
	break;
	case 'comboMat1':
		comboMat();
	break;
	case 'comboMatHr1':
		comboMatHr();
		break;
	case 'comboPract1':
		comboPract();
		break;
	case 'comboLab1':
		comboLab();
		break;
	case 'comboLaboratorios1':
		comboLaboratorios();
		break;
	case 'comboHoraPrac1':
		comboHoraPrac();
		break;
	case 'comboEleArt1':
		comboEleArt();
		break;
	case 'capacidadLab1':
		capacidadLab();
		break;
	case 'comboMatRep1':
		comboMatRep();
		break;
	case 'comboHrMatRep1':
		comboHrMatRep();
		break;
	case 'comboHoraPracRep1':
		comboHoraPracRep();
		break;
	case 'comboPractRep1':
		comboPractRep();
		break;
}	 
?>