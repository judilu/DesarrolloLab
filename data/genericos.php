<?php
require_once('../data/conexion.php');
require_once('../data/funciones.php');

function usuario ()
{
	$usuario = GetSQLValueString($_POST['clave1'],"int");
	//$usuario = substr($usuario, -2,1);
	session_start();
	$_SESSION['nombre'] =$usuario;
}
function salirG()
{
	session_start();
	session_destroy();
	$respuesta = true;
	$arrayJSON = array('respuesta' => $respuesta);
	print json_encode($arrayJSON);
}
function existeSolLabG ($clave)
{
	$claveSol	= $clave;
	$conexion 	= conectaBDSICLAB();
	$consulta 	= sprintf("select claveSolicitud from lbsolicitudlaboratorios 
		where claveSolicitud =%s",$claveSol);
	$res 		= mysql_query($consulta); 
	if($row = mysql_fetch_array($res))
	{
		return true;
	}
	return false;
}
function nomMae($clave)
{
	$claveMaestro 	= $clave;
	$conexion 		= conectaBDSIE();
	$consulta 		= sprintf("select PERCVE, PERNOM, PERAPE from DPERSO where PERCVE =%d",$claveMaestro);
	$res			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		return $row["PERNOM"]." ".$row["PERAPE"];
	}
	else
	{
		return "";
	}
}
function nombreMat($clave)
{
	$claveMat 	= GetSQLValueString($clave,"text");
	$conexion	= conectaBDSIE();
	$consulta	= sprintf("select MATCVE, MATNCO from DMATER where MATCVE=%s",$claveMat);
	$res 		= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		return $row["MATNCO"];
	}
	else
	{
		return "";
	}
}
function claveResp($usuario)
{
	$claveUsuario 	= $usuario;
	$conexion		= conectaBDSICLAB();
	$consulta		= sprintf("select claveResponsable from lbresponsables where claveUsuario=%d",$claveUsuario);
	$res 			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		return $row["claveResponsable"];
	}
	else
	{
		return "";
	}

}
//Solicitudes pendientes de laboratorio
function pendientesLaboratorio()
{
	$respuesta 	= false;
	session_start();
	$responsable= $_SESSION['nombre'];
	$claveLab 	= GetSQLValueString(obtieneCveLab($responsable),"text");
	$con 		= 0;
	$rows		= array();
	$renglones	= "";
	$dependencia= 0;
	$maestro 	= "";
	$percve 	= "";
	$nomMaestro = "";
	$nomMat 	= "";
	$tipoUsuario= tipoUsuario($responsable);
	$solPendientesLab ="";
	$conexion 	= conectaBDSICLAB();
	$consulta	= sprintf("select s.claveDependencia,s.claveSolicitud,s.MATCVE,p.tituloPractica,s.fechaSolicitud,s.horaSolicitud,s.PERCVE 
		from lbsolicitudlaboratorios as s  
		INNER JOIN lbpracticas as p ON p.clavePractica = s.clavePractica
		where s.estatus='V' and s.claveLaboratorio=%s and
		NOT EXISTS (select * from lbcalendarizaciones as c where c.claveSolicitud=s.claveSolicitud)",$claveLab);
	$res 		= mysql_query($consulta);
	$renglones	.= "<thead>";
	$renglones	.= "<tr>";
	$renglones	.= "<th data-field='maestro'>Maestro</th>";
	$renglones	.= "<th data-field='materia'>Materia</th>";
	$renglones	.= "<th data-field='nombrePractica'>Nombre de la práctica</th>";
	$renglones	.= "<th data-field='fecha'>Fecha</th>";
	$renglones	.= "<th data-field='hora'>Hora</th>";
	$renglones	.= "<th data-field='accion'>Acción</th>";
	$renglones	.= "</tr>";
	$renglones	.= "</thead>";

	while($row = mysql_fetch_array($res))
	{
		$solPendientesLab .="'".($row["claveSolicitud"])."',";
		$rows[]=$row;
		$respuesta = true;
		$con++;
	}
	$solPendientesLab = (rtrim($solPendientesLab,","));
	for($c= 0; $c< $con; $c++)
	{
		$dependencia 	= $rows[$c]["claveDependencia"];
		$cveGpo 	= $rows[$c]["MATCVE"];
		if($dependencia == 10000000)
		{
			$maestro 	= $rows[$c]["PERCVE"];
			$nomMaestro = nomMae($maestro);
			$nomMat 	= nombreMat($cveGpo);
		}
		else
		{
			$nomMaestro = consultaDep($dependencia);
			$nomMat 	= $cveGpo;
		}
		$renglones .= "<tbody>";
		$renglones .= "<tr>";
		$renglones .= "<td>".$nomMaestro."</td>";
		$renglones .= "<td>".$nomMat."</td>";
		$renglones .= "<td>".$rows[$c]["tituloPractica"]."</td>";
		$renglones .= "<td>".$rows[$c]["fechaSolicitud"]."</td>";
		$renglones .= "<td>".$rows[$c]["horaSolicitud"]."</td>";
		$renglones .= "<td><a name = '".$rows[$c]["claveSolicitud"]."' class='btn-floating btn-large waves-effect  green darken-2' type='button' id='btnCalendarizado'><i class='material-icons'>done</i></a></td>";
		$renglones .= "<td><a name = '".$rows[$c]["claveSolicitud"]."' class='btn-floating btn-large waves-effect amber darken-2' id='btnVerMas'><i class='material-icons'>add</i></a></td>";
		if($tipoUsuario != 3)
		{
			$renglones .= "<td><a name = '".$rows[$c]["claveSolicitud"]."' class='btn-floating btn-large waves-effect red darken-1' id='btnEliminarSolLab'><i class='material-icons'>delete</i></a></td>";
		}
		$renglones .= "</tr>";
		$renglones .= "</tbody>";
		$respuesta = true;
	}
	$arrayJSON = array('respuesta' 	=> $respuesta,
		'renglones' => $renglones,
		'contador' 	=> $con);
	print json_encode($arrayJSON);
}
//Ver mas de las solicitudes pendientes del laboratorio
function verMas()
{
	$respuesta 		= false;
	session_start();
	$clave 			= GetSQLValueString($_POST["clave"],"int");
	$renglones		= "";
	$fechaSolicitud	= "";
	$horaSolicitud	= "";
	$con 			= "";
	$maestro		= "";
	$nombrePractica = "";
	$nombreArticulo = "";
	$cantidad 		= "";
	$usuario 		= 0;
	$dependencia	= 0;
	$rows			= array();
	$conexion 		= conectaBDSICLAB();
	if(existeSolLabG($clave))
	{
		$consulta  		= sprintf("select sl.claveDependencia,sl.fechaSolicitud,sl.horaSolicitud,sl.MATCVE,p.tituloPractica, ac.nombreArticulo, aa.cantidad, sl.PERCVE 
			from lbarticuloscat as ac
			INNER JOIN lbasignaarticulospracticas as aa ON ac.claveArticulo=aa.claveArticulo
			INNER JOIN lbsolicitudlaboratorios as sl ON sl.claveSolicitud=aa.claveSolicitud 
			INNER JOIN lbpracticas as p ON sl.clavePractica=p.clavePractica
			where sl.claveSolicitud =%s 
			AND sl.claveSolicitud NOT IN(select c.claveSolicitud from lbcalendarizaciones c where c.claveSolicitud=%s)",$clave,$clave);
		$renglones	.= "<thead>";
		$renglones	.= "<tr>";
		$renglones	.= "<th data-field='nombreArt'>Nombre del artículo</th>";
		$renglones	.= "<th data-field='cantidad'>Cantidad</th>";
		$renglones	.= "</tr>";
		$renglones	.= "</thead>";
		$res 	 	=  mysql_query($consulta);
		while($row = mysql_fetch_array($res))
		{	
			$respuesta		= true;
			$dependencia 	= $row["claveDependencia"];
			$fechaSolicitud = $row["fechaSolicitud"];
			$horaSolicitud 	= $row["horaSolicitud"];
			if($dependencia == 10000000)
			{
				$usuario 		= $row["PERCVE"];
				$maestro 		= nomMae($usuario);
			}
			else
			{
				$maestro = consultaDep($dependencia);
			}
			$nombrePractica = $row["tituloPractica"];
			$rows[]=$row;
			$con++;

		}
		for($c= 0; $c< $con; $c++)
		{
			$renglones .= "<tbody>";
			$renglones .= "<tr>";
			$renglones .= "<td>".$rows[$c]["nombreArticulo"]."</td>";
			$renglones .= "<td>".$rows[$c]["cantidad"]."</td>";
			$renglones .= "</tr>";
			$renglones .= "</tbody>";
			$respuesta = true;
		}
	}
	$arrayJSON = array('respuesta' => $respuesta, 
		'fecha' =>$fechaSolicitud, 
		'hora' => $horaSolicitud,
		'maestro' => $maestro, 
		'practica' => $nombrePractica, 
		'renglones' => $renglones);
	print json_encode($arrayJSON);
}
//obtiene los datos de la solicitud para mostrar en la pantalla guardaSolLab
function obtenerDatosSolLab()
{
	$respuesta 		= false;
	session_start();
	$fechaPer 		= fechaPeriodo();
	$clave 			= GetSQLValueString($_POST["clave"],"int");
	$fecha 			= "";
	$hora 			= "";
	$conexion 		= conectaBDSICLAB();
	if(existeSolLabG($clave))
	{
		$consulta  	= sprintf("select fechaSolicitud,horaSolicitud from lbsolicitudlaboratorios where claveSolicitud=%d",$clave);
		$res 	 	=  mysql_query($consulta);
		while($row = mysql_fetch_array($res))
		{
			$respuesta = true;
			$fecha	= $row["fechaSolicitud"];
			$hora	= $row["horaSolicitud"];
		}
	}
	$arrayJSON = array('respuesta' => $respuesta, 
		'fecha' => $fecha, 
		'hora' => $hora,
		'fechaP' => $fechaPer);
	print json_encode($arrayJSON);
}
//consulta si la fecha de la solicitud de laboratorio esta disponible
function consultaFechaSol($fecha,$hora,$periodo)
{
	date_default_timezone_set("America/Mazatlan");
	$fechaSolicitada 	= $fecha;
	$horaSolicitada 	= $hora;
	/*$fechaSolicitada2 	= str_replace("/", "-", $fechaSolicitada);
	$fechaSolicitada2 	= str_replace("'", "", $fechaSolicitada2);
	$fechaSolicitada2 	= strtotime($fechaSolicitada2);*/
	$periodoSol			= $periodo;
	$fecha_hoy 			= date("'Y-m-d'");
	if($fecha_hoy <= $fechaSolicitada)
	{
		$conexion 			= conectaBDSICLAB();
		$consulta 			= sprintf("select fechaAsignada,horaAsignada from lbcalendarizaciones
			where fechaAsignada=%s and horaAsignada=%s and PDOCVE=%s"
			,$fechaSolicitada,$horaSolicitada,$periodo);
		$res 	 			=  mysql_query($consulta);
		if($row = mysql_fetch_array($res))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	else
	{
		return false;
	}
}
//Se inserta la solicitud en la tabla lbcalendarizaciones
function guardaSolicitudLab()
{
	$respuesta 		= false;
	session_start();
	$periodo 		= periodoActual();
	$clave 			= GetSQLValueString($_POST["clave"],"int");
	$fecha 			= GetSQLValueString($_POST["fecha"],"text");
	$hora 			= GetSQLValueString($_POST["hora"],"text");
	$firmaJefe 		= GetSQLValueString($_POST["firmaJefe"],"text");
	$comentarios	= GetSQLValueString($_POST["comentarios"],"text");
	$conexion 		= conectaBDSICLAB();
	if(existeSolLabG($clave) && consultaFechaSol($fecha,$hora,$periodo))
	{
		$consulta  		= sprintf("insert into lbcalendarizaciones values(%s,%d,%s,%s,%s,%s,%d,%s)",$periodo,"''",$fecha,$hora,$firmaJefe,$comentarios,$clave,'"NR"');
		$res 	 	=  mysql_query($consulta);
		if(mysql_affected_rows()>0)
			$respuesta = true; 
	}
	$arrayJSON = array('respuesta' => $respuesta);
	print json_encode($arrayJSON);
}
//eliminar una solicitud de laboratorio
function eliminaSolicitudLab()
{
	$respuesta 		= false;
	$respuesta2 	= false;
	session_start();
	$clave 			= GetSQLValueString($_POST["clave"],"int");
	$conexion 		= conectaBDSICLAB();
	if(existeSolLabG($clave))
	{
		$consulta  	= sprintf("update lbsolicitudlaboratorios set estatus='B' where claveSolicitud=%d",$clave);
		$res 	 	=  mysql_query($consulta);
		if(mysql_affected_rows()>0)
		{
			$respuesta = true;
			$consulta2 = sprintf("update lbasignaarticulospracticas set estatus='B' where claveSolicitud=%d",$clave);
			$res2 	 	=  mysql_query($consulta2);
			if(mysql_affected_rows()>0)
			{
				$respuesta2 = true;
			}
		}
	}
	$arrayJSON = array('respuesta' => $respuesta,
		'respuesta2' => $respuesta2);
	print json_encode($arrayJSON);
}
//funcion para sacar la lista de las solicitudes calendarizadas
function aceptadasLaboratorio()
{
	date_default_timezone_set("America/Mazatlan");
	$respuesta 	= false;
	session_start();
	$responsable 		= $_SESSION['nombre'];
	$claveLab 			= GetSQLValueString(obtieneCveLab($responsable),"text");
	$con 				= 0;
	$rows				= array();
	$renglones			= "";
	$nombreMateria 		= "";
	$claveMateria 		= 0;
	$dependencia 		= 0;
	$nombreMaestro 		= "";
	$claveMaestro 		= 0;
	$solAceptadasLab 	= "";
	$finPeriodo 		= GetSQLValueString(fechaPeriodo(),"text");
	$fechaActual 		= date("'Y-m-d'");
	$conexion 			= conectaBDSICLAB();
	$consulta			= sprintf("select s.claveDependencia,s.claveSolicitud,s.MATCVE,p.tituloPractica,c.fechaAsignada,c.horaAsignada,s.PERCVE 
		from lbsolicitudlaboratorios as s 
		INNER JOIN lbpracticas as p ON p.clavePractica = s.clavePractica
		INNER JOIN lbcalendarizaciones c ON c.claveSolicitud=s.claveSolicitud
		where s.estatus='V' and s.claveLaboratorio=%s and c.estatus='NR' and c.fechaAsignada BETWEEN %s and %s",$claveLab,$fechaActual,$finPeriodo);
	$res 		= mysql_query($consulta);
	$renglones	.= "<thead>";
	$renglones	.= "<tr>";
	$renglones	.= "<th data-field='maestro'>Maestro</th>";
	$renglones	.= "<th data-field='materia'>Materia</th>";
	$renglones	.= "<th data-field='nombrePractica'>Nombre de la práctica</th>";
	$renglones	.= "<th data-field='fecha'>Fecha</th>";
	$renglones	.= "<th data-field='hora'>Hora</th>";
	$renglones	.= "<th data-field='accion'>Acción</th>";
	$renglones	.= "</tr>";
	$renglones	.= "</thead>";

	while($row = mysql_fetch_array($res))
	{
		$solAceptadasLab .="'".($row["claveSolicitud"])."',";
		$rows[]=$row;
		$respuesta = true;
		$con++;
	}
	$solAceptadasLab = (rtrim($solAceptadasLab,","));
	for($c= 0; $c< $con; $c++)
	{

		$dependencia 	= $rows[$c]["claveDependencia"];
		$claveMaestro 	= $rows[$c]["PERCVE"];
		$claveMateria 	= $rows[$c]["MATCVE"];
		if($dependencia == 10000000)
		{
			$nombreMaestro 	= nomMae($claveMaestro);
			$nombreMateria 	= nombreMat($claveMateria);
		}
		else
		{
			$nombreMateria 	= $claveMateria;
			$nombreMaestro 	= consultaDep($dependencia);
		}
		$renglones .= "<tbody>";
		$renglones .= "<tr>";
		$renglones .= "<td>".$nombreMaestro."</td>";
		$renglones .= "<td>".$nombreMateria."</td>";
		$renglones .= "<td>".$rows[$c]["tituloPractica"]."</td>";
		$renglones .= "<td>".$rows[$c]["fechaAsignada"]."</td>";
		$renglones .= "<td>".$rows[$c]["horaAsignada"]."</td>";
		$renglones .= "<td><a name = '".$rows[$c]["claveSolicitud"]."' class='btn-floating btn-large waves-effect amber darken-2' id='btnVerMas2'><i class='material-icons'>add</i></a></td>";
		$renglones .= "</tr>";
		$renglones .= "</tbody>";
		$respuesta = true;
	}
	$arrayJSON = array('respuesta' => $respuesta,
		'renglones' => $renglones);
	print json_encode($arrayJSON);
}
function solDependencia($clave)
{
	$claveSol = $clave;
	$conexion = conectaBDSICLAB();
	$consulta 	= sprintf("select DEPCVE from DPUEST WHERE PERCVE =%s",$clave);
	$res 		= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		return true;
	}
	else
	{
		return false;
	}
}
function verMas2()
{
	$respuesta 		= false;
	session_start();
	$claveCal 		= GetSQLValueString($_POST["clave"],"int");
	$renglones		= "";
	$fechaAsignada	= "";
	$horaAsignada	= "";
	$con 			= "";
	$maestro		= "";
	$cveMaestro		= 0;
	$practica 		= "";
	$nombreArticulo = "";
	$cantidad 		= "";
	$dependencia 	= 0;
	$rows			= array();
	$conexion 		= conectaBDSICLAB();

	if(existeSolLabG($claveCal))
	{
		$consulta  		= sprintf("select s.claveDependencia,a.claveCalendarizacion, a.fechaAsignada, a.horaAsignada, s.GPOCVE, b.nombreArticulo, d.cantidad, s.PERCVE,c.tituloPractica
			from lbarticuloscat as b 
			INNER JOIN lbasignaarticulospracticas as d ON b.claveArticulo = d.claveArticulo
			INNER JOIN lbsolicitudlaboratorios as s ON d.claveSolicitud = s.claveSolicitud 
			INNER JOIN lbcalendarizaciones as a ON s.claveSolicitud = a.claveSolicitud 
			INNER JOIN lbpracticas as c ON s.clavePractica = c.clavePractica
			where s.claveSolicitud=%d ",$claveCal);
		$renglones	.= "<thead>";
		$renglones	.= "<tr>";
		$renglones	.= "<th data-field='nombreArt'>Nombre del artículo</th>";
		$renglones	.= "<th data-field='cantidad'>Cantidad</th>";
		$renglones	.= "</tr>";
		$renglones	.= "</thead>";
		$res 	 	=  mysql_query($consulta);
		while($row = mysql_fetch_array($res))
		{	
			$respuesta 		= true;
			$dependencia 	= $row["claveDependencia"];
			$fechaAsignada 	= $row["fechaAsignada"];
			$horaAsignada 	= $row["horaAsignada"];
			if($dependencia == 10000000)
			{
				$cveMaestro		= $row["PERCVE"];
				$maestro 		= nomMae($cveMaestro);
			}
			else
			{
				$maestro = consultaDep($dependencia);
			}
			$practica 		= $row["tituloPractica"];
			$rows[] 		= $row;
			$con++;
		}
		for($c= 0; $c< $con; $c++)
		{
			$renglones .= "<tbody>";
			$renglones .= "<tr>";
			$renglones .= "<td>".$rows[$c]["nombreArticulo"]."</td>";
			$renglones .= "<td>".$rows[$c]["cantidad"]."</td>";
			$renglones .= "</tr>";
			$renglones .= "</tbody>";
			$respuesta = true;
		}
	}
	$arrayJSON = array('respuesta' => $respuesta, 
		'fechaAsignada' =>$fechaAsignada, 
		'horaAsignada' => $horaAsignada,
		'maestro' => $maestro, 
		'practica' => $practica, 
		'renglones' => $renglones);
	print json_encode($arrayJSON);
}
function jefeDepto($cvePersona)
{
	$clave 		= $cvePersona;
	$conexion 	= conectaBDSIE();
	$consulta 	= sprintf("select DEPCVE from DPUEST WHERE PERCVE =%s",$clave);
	$res 		= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		return $row["DEPCVE"];
	}
	else
	{
		return 0;
	}
}
function listaArticulos()
{
	$respuesta 	= false;
	session_start();
	$responsable= $_SESSION['nombre'];
	$claveLab 	= GetSQLValueString(obtieneCveLab($responsable),"text");
	$art 		= "";
	$articulos 	= "";
	$con 		= 0;
	$rows		= array();
	$renglones	= "";
	$tipoUsu 	= tipoUsuario($responsable);
	$conexion 	= conectaBDSICLAB();
	if ($tipoUsu == 5) 
	{
		$clavesLab 	= arrayLabs(jefeDepto(claveMaestro($responsable)));
		$consulta	= sprintf("select B.claveArticulo,B.nombreArticulo,COUNT(A.claveArticulo) as cantidad 
			FROM lbarticulos as A 
			INNER JOIN lbarticuloscat as B ON A.claveArticulo=B.claveArticulo 
			INNER JOIN lbasignaarticulos C ON A.identificadorArticulo=C.identificadorArticulo 
			WHERE C.claveLaboratorio IN(%s) and A.estatus='V' GROUP BY A.claveArticulo",$clavesLab);
	}
	else
	{
		$consulta	= sprintf("select B.claveArticulo,B.nombreArticulo,COUNT(A.claveArticulo) as cantidad 
			FROM lbarticulos as A 
			INNER JOIN lbarticuloscat as B ON A.claveArticulo=B.claveArticulo 
			INNER JOIN lbasignaarticulos C ON A.identificadorArticulo=C.identificadorArticulo 
			WHERE C.claveLaboratorio=%s and A.estatus='V' GROUP BY A.claveArticulo",$claveLab);

	}
	$res 		= mysql_query($consulta);
	$renglones	.= "<thead>";
	$renglones	.= "<tr>";
	$renglones	.= "<th data-field='nombreArticulo'>Nombre del artículo</th>";
	$renglones	.= "<th data-field='cantidad'>Cantidad</th>";
	$renglones	.= "</tr>";
	$renglones	.= "</thead>";
	while($row = mysql_fetch_array($res))
	{
		$art 	.= "'".($row["claveArticulo"])."',";
		$rows[]=$row;
		$respuesta = true;
		$con++;
	}
	$art = (rtrim($art,","));
	for($c= 0; $c< $con; $c++)
	{
		$renglones .= "<tbody>";
		$renglones .= "<tr>";
		$renglones .= "<td>".$rows[$c]["nombreArticulo"]."</td>";
		$renglones .= "<td>".$rows[$c]["cantidad"]."</td>";
		$renglones .= "</tr>";
		$renglones .= "</tbody>";
		$respuesta = true;
	}
	$arrayJSON = array('respuesta' 	=> $respuesta,
		'renglones' => $renglones);
	print json_encode($arrayJSON);
}
function altaInventario1()
{
	$respuesta 	= false;
	session_start();
	$conexion					= conectaBDSICLAB();
	$periodo 					= periodoActual();
	$imagen						= GetSQLValueString($_POST["imagen"],"text");
	$fecha						= GetSQLValueString($_POST["fecha"],"text");
	$hora						= GetSQLValueString($_POST["hora"],"text");
	$identificadorArticulo 		= "' '";
	$modelo						= GetSQLValueString($_POST["modelo"],"text");
	$numeroSerie				= GetSQLValueString($_POST["numeroSerie"],"text");
	$marca						= GetSQLValueString($_POST["marca"],"text");
	$tipoContenedor				= GetSQLValueString($_POST["tipoContenedor"],"text");
	$descripcionArticulo		= GetSQLValueString($_POST["descripcionArticulo"],"text");
	$descripcionUso				= GetSQLValueString($_POST["descripcionUso"],"text");
	$unidadMedida				= GetSQLValueString($_POST["unidadMedida"],"text");
	$fechaCaducidad				= GetSQLValueString($_POST["fechaCaducidad"],"text");
	$claveKit					= GetSQLValueString($_POST["claveKit"],"text");
	$ubicacionAsignada			= GetSQLValueString($_POST["ubicacionAsignada"],"text");
	$claveArticulo 				= GetSQLValueString($_POST["claveArticulo"],"text");	
	$estatus 					= GetSQLValueString($_POST["estatus"],"text");
	$responsable 				= $_SESSION['nombre'];
	$claveResponsable			= claveResp($responsable);
	$claveLaboratorio 			= GetSQLValueString(obtieneCveLab($responsable),"text");
	$observaciones 				= "'Entrada de artículo'";
	$claveMov 					= "' '";
	$estatusMov 				= "'E'"; 					
		//insert a tabla lbarticulos
	$consulta= sprintf("insert into lbarticulos values(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
		$claveArticulo,$descripcionUso,$descripcionArticulo,$numeroSerie,$marca,$modelo,$estatus,$unidadMedida,$fechaCaducidad,$tipoContenedor,$imagen,$identificadorArticulo,$ubicacionAsignada,$claveKit);
	$resconsulta = mysql_query($consulta);
	if(mysql_affected_rows()>0)
	{
		$identificadorArticulo = mysql_insert_id($conexion);
		$consulta2 	= sprintf("insert into lbasignaarticulos values(%d,%s)",$identificadorArticulo,$claveLaboratorio);
		$res 		= mysql_query($consulta2);
		if(mysql_affected_rows()>0)
		{
			$consulta3 	= sprintf("insert into lbmovimientosarticulos values(%s,%s,%s,%d,%s,%s,%s,%d,%s)",$periodo,$fecha,$hora,$claveResponsable,$identificadorArticulo,$observaciones,$estatusMov,$claveMov,$claveLaboratorio);
			$res 		= mysql_query($consulta3);
			if(mysql_affected_rows()>0)
			{
				$respuesta 	= true; 
			}
		}
	}
	$salidaJSON = array('respuesta' => $respuesta,
		'idu' => $identificadorArticulo);
	print json_encode($salidaJSON);
	
}
function buscaArticulo()
{
	$respuesta 	= false;
	session_start();
	$responsable= $_SESSION['nombre'];
	$identificadorArticulo= GetSQLValueString($_POST["identificadorArticulo"],"text");
	$rows			= array();
	$modelo			= "";
	$numeroSerie	= "";
	$nombreArticulo	= "";
	$marca			= "";
	$fechaCaducidad	= "";
	$descripcionArticulo	= "";
	$descripcionUso	= "";
	$unidadMedida	= "";
	$tipoContenedor	= "";
	$conexion 	= conectaBDSICLAB();
	$consulta	= sprintf("select A.modelo,A.numeroSerie,B.nombreArticulo,A.marca,A.fechaCaducidad,
		A.descripcionArticulo,A.descripcionUso,A.unidadMedida, A.tipoContenedor
		from lbarticulos as A inner join lbarticuloscat as B on A.claveArticulo=B.claveArticulo
		where A.estatus='V' and A.identificadorArticulo=%s",$identificadorArticulo);
	$res 		= mysql_query($consulta);

	while($row = mysql_fetch_array($res))
	{
		$respuesta = true;
		$modelo			= $row["modelo"];
		$numeroSerie 	= $row["numeroSerie"];
		$nombreArticulo	= $row["nombreArticulo"];
		$marca		 	= $row["marca"];
		$fechaCaducidad	= $row["fechaCaducidad"];
		$descripcionArticulo	= $row["descripcionArticulo"];
		$descripcionUso	= $row["descripcionUso"];
		$unidadMedida	= $row["unidadMedida"];
		$tipoContenedor	= $row["tipoContenedor"];
	}
	$salidaJSON = array('respuesta' => $respuesta,
		'modelo' => $modelo, 'numeroSerie' => $numeroSerie, 'nombreArticulo' => $nombreArticulo,
		'marca' => $marca, 'fechaCaducidad' => $fechaCaducidad, 'descripcionArticulo' => $descripcionArticulo,
		'descripcionUso' => $descripcionUso, 'unidadMedida' => $unidadMedida, 'tipoContenedor' => $tipoContenedor);
	print json_encode($salidaJSON);
}
function bajaArticulos()
{
	$respuesta = false;
	session_start();
	$responsable 			= $_SESSION['nombre'];
	$periodo 				= periodoActual();
	$identificadorArticulo	= GetSQLValueString($_POST["identificadorArticulo"],"int");
	$estatus				= GetSQLValueString($_POST["estatus"],"text");
	$fecha					= GetSQLValueString($_POST["fecha"],"text");
	$hora					= GetSQLValueString($_POST["hora"],"text");
	$observaciones			= GetSQLValueString($_POST["observaciones"],"text");
	$claveResponsable		= claveResp($responsable);
	$claveLaboratorio 		= GetSQLValueString(obtieneCveLab($responsable),"text");
	$claveMov 				= "' '";
	$conexion 	= conectaBDSICLAB();
	$consulta1	= sprintf("update lbarticulos set estatus='B' where identificadorArticulo=%d",$identificadorArticulo);
	$res = mysql_query($consulta1);
	if(mysql_affected_rows()>0)
	{
		$consulta2	= sprintf("insert into lbmovimientosarticulos values(%s,%s,%s,%d,%s,%s,%s,%d,%s)",$periodo,$fecha,$hora,$claveResponsable,$identificadorArticulo,$observaciones,$estatus,$claveMov,$claveLaboratorio);
		$res = mysql_query($consulta2);
		if(mysql_affected_rows()>0)
		{
			$respuesta = true;
		}
	}
	$salidaJSON = array('respuesta' => $respuesta);
	print json_encode($salidaJSON);
}
function arrayLabs($depto)
{
	$cveDep 	= $depto;
	$labs 		= "";
	$conexion 	= conectaBDSICLAB();
	$consulta 	= sprintf("select claveLaboratorio from lblaboratorios where DEPCVE=%d",$cveDep);
	$res 		= mysql_query($consulta);
	while($row = mysql_fetch_array($res))
	{
		$labs 	.= "'".($row["claveLaboratorio"])."',";
	}
	$labs = (rtrim($labs,","));
	return $labs;
}
function listaMantenimiento()
{
	$respuesta 	= false;
	session_start();
	$responsable= $_SESSION['nombre'];
	$tipoUsuario= tipoUsuario($responsable);
	$claveLab 	= GetSQLValueString(obtieneCveLab($responsable),"text");
	$art 		= "";
	$articulos 	= "";
	$con 		= 0;
	$mod 		= "%";
	$rows		= array();
	$renglones	= "";
	$conexion 	= conectaBDSICLAB();
	if($tipoUsuario == 5)
	{
		$labs 		= arrayLabs(jefeDepto(claveMaestro($responsable)));

		$consulta	= sprintf("select identificadorArticulo,nombreArticulo,fechaMovimiento,claveMovimiento from (
			select ma.claveMovimiento,B.nombreArticulo, C.identificadorArticulo,ma.fechaMovimiento, COUNT(*) AS total
			from lbarticuloscat B inner join lbarticulos as C ON B.claveArticulo=C.claveArticulo 
			inner join lbmovimientosarticulos ma ON ma.identificadorArticulo=C.identificadorArticulo 
			where ma.claveLaboratorio in(%s) AND ma.estatus in('E','M')
			group by C.identificadorArticulo
			ORDER BY ma.fechaMovimiento) as subconsulta
		where total %s 2 = 0
		ORDER BY fechaMovimiento DESC",$labs,$mod);
	}
	else
	{
		$consulta	= sprintf("select identificadorArticulo,nombreArticulo,fechaMovimiento,claveMovimiento from (
			select ma.claveMovimiento,B.nombreArticulo, C.identificadorArticulo,ma.fechaMovimiento, COUNT(*) AS total
			from lbarticuloscat B inner join lbarticulos as C ON B.claveArticulo=C.claveArticulo 
			inner join lbmovimientosarticulos ma ON ma.identificadorArticulo=C.identificadorArticulo 
			where ma.claveLaboratorio=%s AND ma.estatus in('E','M')
			group by C.identificadorArticulo
			ORDER BY ma.fechaMovimiento) as subconsulta
		where total %s 2 = 0
		ORDER BY fechaMovimiento DESC",$claveLab,$mod);
	}
	$res 		= mysql_query($consulta);
	$renglones	.= "<thead>";
	$renglones	.= "<tr>";
	$renglones	.= "<th data-field='codigoBarras'>Identificador artículo</th>";
	$renglones	.= "<th data-field='nombreArticulo'>Nombre del artículo</th>";
	$renglones	.= "<th data-field='movimiento'>Fecha de envío</th>";
	$renglones	.= "<th data-field='accion'>Acción</th>";
	$renglones	.= "</tr>";
	$renglones	.= "</thead>";
	while($row = mysql_fetch_array($res))
	{
		$art 	.= "'".($row["identificadorArticulo"])."',";
		$rows[]=$row;
		$respuesta = true;
		$con++;
	}
	$art = (rtrim($art,","));
	for($c= 0; $c< $con; $c++)
	{
		$renglones .= "<tbody>";
		$renglones .= "<tr>";
		$renglones .= "<td>".$rows[$c]["identificadorArticulo"]."</td>";
		$renglones .= "<td>".$rows[$c]["nombreArticulo"]."</td>";
		$renglones .= "<td>".$rows[$c]["fechaMovimiento"]."</td>";
		if($tipoUsuario != 3)
		{
			$renglones .= "<td><a name = '".$rows[$c]["identificadorArticulo"]."' class='btn-floating btn-large waves-effect  green darken-2' id='btnRegresaDelMtto'><i class='material-icons'>done</i></a></td>";
		}
		$renglones .= "</tbody>";
		$respuesta = true;
	}
	$arrayJSON = array('respuesta' => $respuesta,
		'renglones' => $renglones);
	print json_encode($arrayJSON);
}
function mantenimientoArticulos()
{
	$respuesta = false;
	session_start();
	$responsable 			= $_SESSION['nombre'];
	$periodo				= periodoActual();
	$estatus				= '"M"';
	$claveMovimiento		= 0;
	$claveLab				= GetSQLValueString(obtieneCveLab($responsable),"text");
	$identificadorArticulo	= GetSQLValueString($_POST["identificadorArticulo"],"int");
	$observaciones			= GetSQLValueString($_POST["observaciones"],"text");
	$fechaMovimiento		= GetSQLValueString($_POST["fechaMovimiento"],"text");
	$horaMovimiento			= GetSQLValueString($_POST["horaMovimiento"],"text");
	$claveResponsable		= claveResp($responsable);
	$conexion 	= conectaBDSICLAB();

	$consulta 	= sprintf("insert into lbmovimientosarticulos values(%s,%s,%s,%s,%s,%s,%s,%d,%s)",
		$periodo,$fechaMovimiento,$horaMovimiento,$claveResponsable,$identificadorArticulo,$observaciones,
		$estatus,$claveMovimiento,$claveLab);
	$res = mysql_query($consulta);
	if(mysql_affected_rows()>0)
		$respuesta = true;
	$salidaJSON = array('respuesta' => $respuesta);
	print json_encode($salidaJSON);
}
function buscaArticuloMtto()
{
	$respuesta 	= false;
	session_start();
	$responsable= $_SESSION['nombre'];
	$identificadorArticulo= GetSQLValueString($_POST["identificadorArticulo"],"text");
	$modelo			= "";
	$numeroSerie	= "";
	$nombreArticulo	= "";
	$marca			= "";
	$fechaCaducidad	= "";
	$conexion 	= conectaBDSICLAB();
	$consulta	= sprintf("select A.modelo,A.numeroSerie,B.nombreArticulo,A.marca,A.fechaCaducidad
		from lbarticulos as A inner join lbarticuloscat as B on A.claveArticulo=B.claveArticulo
		where A.estatus='V' and A.identificadorArticulo=%s",$identificadorArticulo);
	$res 		= mysql_query($consulta);
	while($row = mysql_fetch_array($res))
	{
		$respuesta = true;
		$modelo			= $row["modelo"];
		$numeroSerie 	= $row["numeroSerie"];
		$nombreArticulo	= $row["nombreArticulo"];
		$marca		 	= $row["marca"];
		$fechaCaducidad	= $row["fechaCaducidad"];
	}
	$salidaJSON = array('respuesta' 	=> $respuesta,
		'modelo' 		=> $modelo, 
		'numeroSerie' 	=> $numeroSerie, 
		'nombreArticulo'=> $nombreArticulo,
		'marca' 		=> $marca, 
		'fechaCaducidad'=> $fechaCaducidad);
	print json_encode($salidaJSON);
}
function tipoUsuario($claveUsuario)
{
	$cve 			= $claveUsuario;
	$conexion 		= conectaBDSICLAB();
	$consulta		= sprintf("select tipoUsuario from lbusuarios where claveUsuario=%d",$cve);
	$res 			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		return (int)$row["tipoUsuario"];
	}
	else
	{
		return 0;
	}
}
function peticionesPendientesArt()
{
	$respuesta 	= false;
	session_start();
	$responsable= $_SESSION['nombre'];
	$labCve 	= GetSQLValueString(obtieneCveLab($responsable),"text");
	$tipoUsuario= tipoUsuario($responsable);
	$art 		= "";
	$articulos 	= "";
	$con 		= 0;
	$rows		= array();
	$renglones	= "";
	$nombreLaboratorio = "";
	$conexion 	= conectaBDSICLAB();
	if($tipoUsuario == 5)
	{
		$labs 		= arrayLabs(jefeDepto(claveMaestro($responsable)));
		$consulta	= sprintf("select clavePedido,claveLaboratorio,nombreArticulo,cantidad 
			from lbpedidos 
			where estatus='P' and claveLaboratorio IN(%s)",$labs);
	}
	else
	{
		$consulta	= sprintf("select p.clavePedido,p.claveLaboratorio,p.nombreArticulo,p.cantidad 
			from lbpedidos p 
			where estatus='P' and claveLaboratorio=%s",$labCve);	
	}
	$res 		= mysql_query($consulta);
	$renglones	.= "<thead>";
	$renglones	.= "<tr>";
	$renglones	.= "<th data-field='laboratorio'>Laboratorio</th>";
	$renglones	.= "<th data-field='nombre'>Nombre del artículo</th>";
	$renglones	.= "<th data-field='cantidad'>Cantidad</th>";
	if($tipoUsuario == 5)
	{
		$renglones	.= "<th data-field='accion'>Acción</th>";
	}
	$renglones	.= "</tr>";
	$renglones	.= "</thead>";
	while($row = mysql_fetch_array($res))
	{
		$art 	.= "'".($row["clavePedido"])."',";
		$rows[]=$row;
		$respuesta = true;
		$con++;
	}
	$art = (rtrim($art,","));
	for($c= 0; $c< $con; $c++)
	{
		$nombreLaboratorio = nombreLab($rows[$c]["claveLaboratorio"]);
		$renglones .= "<tbody>";
		$renglones .= "<tr>";
		$renglones .= "<td>".$nombreLaboratorio."</td>";
		$renglones .= "<td>".$rows[$c]["nombreArticulo"]."</td>";
		$renglones .= "<td>".$rows[$c]["cantidad"]."</td>";
		if($tipoUsuario == 5)
		{
			$renglones .= "<td><a name ='".$rows[$c]["clavePedido"]."'class='btn-floating btn-large waves-effect green darken-2' id='btnAceptaPeticionArt'><i class='material-icons'>done</i></a></td>";
		}
		$renglones .= "</tbody>";
		$respuesta = true;
	}
	$arrayJSON = array('respuesta' 	=> $respuesta,
		'renglones' => $renglones);
	print json_encode($arrayJSON);
}
function nombreLab($claveLab)
{
	$cve 			= $claveLab;
	$conexion 		= conectaBDSICLAB();
	$consulta		= sprintf("select nombreLaboratorio from lblaboratorios where claveLaboratorio=%s",$cve);
	$res 			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		return $row["nombreLaboratorio"];
	}
	else
	{
		return 0;
	}
}
function aceptaPeticionArticulos()
{
	$respuesta 	= false;
	session_start();
	$responsable 	= $_SESSION['nombre'];
	$clavePedido 	= GetSQLValueString($_POST["clavePedido"],"text");
	$conexion 		= conectaBDSICLAB();
	$consulta		= sprintf("update lbpedidos set estatus='A' where clavePedido=%s",$clavePedido);
	$res 			= mysql_query($consulta);
	if(mysql_affected_rows()>0)
		$respuesta = true;
	$arrayJSON = array('respuesta' => $respuesta);
	print json_encode($arrayJSON);
}
function prestamosPendientes()
{
	$respuesta 	= false;
	$respuesta2	= false;
	session_start();
	$responsable= $_SESSION['nombre'];
	$prestamo	= "";
	$con 		= 0;
	$rows		= array();
	$prestamo2	= "";
	$con2 		= 0;
	$rows2		= array();
	$renglones	= "";
	$nombreDep 	= "";
	$nombreAlu 	= "";
	$tipoAlumno = "";
	$numControl = "";
	$tipoUsuario= tipoUsuario($responsable);
	$claveLab 	= GetSQLValueString(obtieneCveLab($responsable),"text");
	$conexion 	= conectaBDSICLAB();
	$consulta	= sprintf("select ea.ALUCTR,p.horaPrestamo,p.fechaPrestamo,p.clavePrestamo, ea.tipoEntrada
		from lbentradasalumnos ea 
		INNER JOIN lbprestamos p on ea.ALUCTR=p.ALUCTR
		inner join lbsolicitudarticulos sa on p.clavePrestamo=sa.clavePrestamo
		where sa.estatus='S' and ea.claveLaboratorio=%s GROUP BY p.clavePrestamo",$claveLab);
	$res 		= mysql_query($consulta);
	$renglones	.= "<thead>";
	$renglones	.= "<tr>";
	$renglones	.= "<th data-field='numeroControl'>No. de control</th>";
	$renglones	.= "<th data-field='nombre'>Nombre</th>";
	$renglones	.= "<th data-field='fecha'>Fecha</th>";
	$renglones	.= "<th data-field='hora'>Hora</th>";
	$renglones	.= "<th data-field='accion'>Acción</th>";
	$renglones	.= "</tr>";
	$renglones	.= "</thead>";
	while($row = mysql_fetch_array($res))
	{
		$prestamo 	.= "'".($row["clavePrestamo"])."',";
		$rows[]=$row;
		$respuesta = true;
		$con++;
	}
	$prestamo = (rtrim($prestamo,","));
	for($c= 0; $c< $con; $c++)
	{
		$tipoAlumno = $rows[$c]["tipoEntrada"];
		$numControl = $rows[$c]["ALUCTR"];
		$renglones .= "<tbody>";
		$renglones .= "<tr>";
		$renglones .= "<td>".$rows[$c]["ALUCTR"]."</td>";
		if($tipoAlumno == 'I')
		{
			$nombreAlu 	= consultaAlumno($numControl);
		}
		else
		{
			$nombreAlu = consultaDep($numControl);
		}
		$renglones .= "<td>".$nombreAlu."</td>";
		$renglones .= "<td>".$rows[$c]["fechaPrestamo"]."</td>";
		$renglones .= "<td>".$rows[$c]["horaPrestamo"]."</td>";
		$renglones .= "<td><a name = '".$rows[$c]["clavePrestamo"]."' class='btn waves-effect waves-light  green darken-2' id='btnAtenderPrestamo'>Atender</a></td>";
		if($tipoUsuario != 3)
		{
			$renglones .= "<td><a name = '".$rows[$c]["clavePrestamo"]."' class='btn waves-effect waves-light red darken-1 eliminarPrestamo' id='btnEliminarPrestamo' type='submit'>Eliminar</a></td>";
		}
		$renglones .= "</tr>";
		$renglones .= "</tbody>";
		$respuesta = true;
	}


	$salidaJSON = array('respuesta' => $respuesta, 
		'renglones' => $renglones,
		'nombre' 	=> $nombreAlu,
		'nombreDep' => $nombreDep,
		'respuesta2'=> $respuesta2);
	print json_encode($salidaJSON);
}
function atenderPrestamo()
{
	date_default_timezone_set("America/Mazatlan");
	$respuesta 	= false;
	session_start();
	$responsable= $_SESSION['nombre'];
	$prestamo	= "";
	$clavePrestamo = GetSQLValueString($_POST["clavePrestamo"],"int");
	$con 		= 0;
	$fechaActual= date("'Y-m-d'");
	$rows		= array();
	$renglones	= "";
	$nombre		= "";
	$conexion 	= conectaBDSICLAB();
	$consulta	= sprintf("select p.ALUCTR,p.clavePrestamo,ac.claveArticulo,ac.nombreArticulo,sa.cantidad 
		from lbarticuloscat ac 
		INNER JOIN lbsolicitudarticulos sa on sa.claveArticulo=ac.claveArticulo 
		INNER JOIN lbprestamos p on p.clavePrestamo=sa.clavePrestamo
		where sa.estatus='S' and p.clavePrestamo=%d",$clavePrestamo);
	$res 		= mysql_query($consulta);
	$renglones	.= "<thead>";
	$renglones	.= "<tr>";
	$renglones	.= "<th data-field='cantidad'>Cantidad</th>";
	$renglones	.= "<th data-field='descripcion'>Descripcion</th>";
	$renglones	.= "</tr>";
	$renglones	.= "</thead>";
	while($row = mysql_fetch_array($res))
	{
		$prestamo 	.= "'".($row["clavePrestamo"])."',";
		$rows[]=$row;
		$respuesta = true;
		$con++;
	}
	$prestamo = (rtrim($prestamo,","));
	for($c= 0; $c< $con; $c++)
	{
		$renglones .= "<tbody>";
		$renglones .= "<tr>";
		$renglones .= "<td>".$rows[$c]["cantidad"]."</td>";
		$renglones .= "<td>".$rows[$c]["nombreArticulo"]."</td>";
		$renglones .= "</tr>";
		$renglones .= "</tbody>";
		$respuesta = true;
	}
	$salidaJSON = array('respuesta' => $respuesta,
		'renglones' => $renglones, 
		'clavePrestamo' => $clavePrestamo);
	print json_encode($salidaJSON);
}
function agregaArticulos()
{
	$respuesta 	= false;
	session_start();
	$responsable			= $_SESSION['nombre'];
	$identificadorArticulo	= GetSQLValueString($_POST["identificadorArticulo"],"int");
	$clavePrestamo			= GetSQLValueString($_POST["clavePrestamo"],"int");
	$claveLaboratorio 		= GetSQLValueString(obtieneCveLab($responsable),"text");
	$idu 					= 0;
	$nomArt 				= "";
	if (consultaArtLab($identificadorArticulo,$claveLaboratorio)) 
	{
		if (buscaArtSolicitud($clavePrestamo,$identificadorArticulo)) {
			$conexion 				= conectaBDSICLAB();
			$consulta				= sprintf("select A.identificadorArticulo,B.nombreArticulo,B.claveArticulo
				from lbarticulos as A inner join lbarticuloscat as B on A.claveArticulo=B.claveArticulo
				where A.estatus='V' and A.identificadorArticulo=%d",$identificadorArticulo);
			$res 					= mysql_query($consulta);
			if($row = mysql_fetch_array($res))
			{
				$idu  		=$row["identificadorArticulo"];
				$nomArt 	=$row["nombreArticulo"];
				$respuesta 	= true;
			}
		}
	}
	$salidaJSON = array('respuesta' => $respuesta,
		'idu' 		=> $idu,
		'nomArt' 	=> $nomArt);
	print json_encode($salidaJSON);
}
function consultaArtLab($idu,$lab)
{
	$identificador 	= $idu;
	$cveLab 		= $lab;
	$conexion 		= conectaBDSICLAB();
	$consulta 		= sprintf("select identificadorArticulo 
		from lbasignaarticulos 
		where claveLaboratorio=%s and identificadorArticulo=%d",$cveLab,$identificador);
	$res 			= mysql_query($consulta);
	if(mysql_affected_rows()>0)
	{
		return true;
	}
	else
	{
		return false;
	}
}
function buscaArtSolicitud($prestamo,$idu)
{
	$identificador 	= $idu;
	$cvePrestamo 	= $prestamo;
	$conexion 		= conectaBDSICLAB();
	$consulta		= sprintf("select sa.claveArticulo from lbsolicitudarticulos sa
		inner join lbarticulos a on sa.claveArticulo=a.claveArticulo
		where a.identificadorArticulo=%d and sa.clavePrestamo=%d",$identificador,$cvePrestamo);
	$res 			= mysql_query($consulta);
	if(mysql_affected_rows()>0)
	{
		return true;
	}
	else
	{
		return false;
	}	
}
function insertaPrestamo($art,$prestamo)
{
	$articulo		= $art;
	$cvePrestamo 	= $prestamo;
	$conexion 		= conectaBDSICLAB();
	$consulta	= sprintf("insert into lbprestamosarticulos values(%s,%s,%d,%s)",'""',$articulo,$cvePrestamo,'"P"');
	$res 		= mysql_query($consulta);
	if(mysql_affected_rows()>0)
	{
		return true;
	}
	else
	{
		return false;
	}
}
function guardaPrestamoPendiente()
{
	$respuesta 	= false;
	session_start();
	$responsable	= $_SESSION['nombre'];
	$clavePrestamo	= GetSQLValueString($_POST["clavePrestamo"],"int");
	$listaArt		= $_POST['listaArt'];
	$arrayArt 		= explode(',',$listaArt); 
	$cantidad 		= count($arrayArt);
	$claveResponsable= claveResp($responsable);
	$conexion 		= conectaBDSICLAB();
	for ($i=0; $i < $cantidad ; $i++) 
	{ 
		if(insertaPrestamo($arrayArt[$i],$clavePrestamo))
		{
			if(actualizaSolArt($clavePrestamo))
			{
				$consulta2 = sprintf("update lbprestamos set claveResponsable=%d where clavePrestamo=%d",$claveResponsable,$clavePrestamo);
				$res 		= mysql_query($consulta2);
				if(mysql_affected_rows()>0)
				{
					$respuesta = true;
				}
			}
		}
	}
	$arrayJSON = array('respuesta' => $respuesta);
	print json_encode($arrayJSON);
}
function actualizaSolArt($prestamo)
{
	$cveP 		= $prestamo;
	$conexion 	= conectaBDSICLAB();
	$consulta	= sprintf("update lbsolicitudarticulos set estatus ='A' where clavePrestamo=%d",$cveP);
	$res 		= mysql_query($consulta);
	if($res)
	{
		return true;
	}
	else
	{
		return false;
	}
}
function eliminaPrestamoPendiente()
{
	$respuesta 	= false;
	session_start();
	$responsable 	= $_SESSION['nombre'];
	$clavePrestamo	= GetSQLValueString($_POST["clavePrestamo"],"text");
	$conexion 		= conectaBDSICLAB();
	$consulta	= sprintf("update lbsolicitudarticulos set estatus='N' 
		where clavePrestamo=%s",$clavePrestamo);
	$res 		= mysql_query($consulta);
	if(mysql_affected_rows()>0)
		$respuesta = true;
	$salidaJSON = array('respuesta' => $respuesta);
	print json_encode($salidaJSON);
}
function prestamosProceso()
{
	$respuesta 	= false;
	session_start();
	$responsable= $_SESSION['nombre'];
	$prestamo	= "";
	$con 		= 0;
	$rows		= array();
	$renglones	= "";
	$nombreAlu 	= "";
	$conexion 	= conectaBDSICLAB();
	$consulta	= sprintf("select ea.ALUCTR,p.fechaPrestamo,p.horaPrestamo, p.clavePrestamo 
		from lbentradasalumnos ea 
		inner join lbprestamos p ON ea.ALUCTR = p.ALUCTR 
		inner join lbprestamosarticulos pa ON pa.clavePrestamo=p.clavePrestamo
		where estatus='P' GROUP BY p.clavePrestamo");
	$res 		= mysql_query($consulta);
	$renglones	.= "<thead>";
	$renglones	.= "<tr>";
	$renglones	.= "<th data-field='numeroControl'>No. de control</th>";
	$renglones	.= "<th data-field='nombre'>Nombre</th>";
	$renglones	.= "<th data-field='fecha'>Fecha</th>";
	$renglones	.= "<th data-field='hora'>Hora</th>";
	$renglones	.= "<th data-field='accion'>Acción</th>";
	$renglones	.= "</tr>";
	$renglones	.= "</thead>";
	while($row = mysql_fetch_array($res))
	{
		$prestamo 	.= "'".($row["clavePrestamo"])."',";
		$rows[]=$row;
		$respuesta = true;
		$con++;
	}
	$prestamo = (rtrim($prestamo,","));
	for($c= 0; $c< $con; $c++)
	{
		$renglones .= "<tbody>";
		$renglones .= "<tr>";
		$renglones .= "<td>".$rows[$c]["ALUCTR"]."</td>";
		$nombreAlu 	= consultaAlumno($rows[$c]["ALUCTR"]);
		$renglones .= "<td>".$nombreAlu."</td>";
		$renglones .= "<td>".$rows[$c]["fechaPrestamo"]."</td>";
		$renglones .= "<td>".$rows[$c]["horaPrestamo"]."</td>";
		$renglones .= "<td><a name = '".$rows[$c]["clavePrestamo"]."' class='waves-effect waves-light btn amber darken-2' id='btnDevolucionMaterial'>Devolución</a></td>";
		$renglones .= "</tr>";
		$renglones .= "</tbody>";
		$respuesta = true;
	}
	$salidaJSON = array('respuesta' => $respuesta, 'renglones' => $renglones);
	print json_encode($salidaJSON);
}
function consultaAlumno($nc)
{
	$respuesta		= false;
	$nControl		= $nc;
	$ALUAPP 		= "";
	$ALUAPM			= "";
	$ALUNOM			= "";
	$conexion 		= conectaBDSIE();
	$consulta 		= sprintf("select ALUAPP, ALUAPM, ALUNOM from DALUMN where ALUCTR=%s limit 1",$nControl);
	$res			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		return $row["ALUAPP"]." ".$row["ALUAPM"]." ".$row["ALUNOM"];
	}

}
function consultaDep($dep)
{
	$dependencia	= $dep;
	$nombre 		= "";
	$conexion 		= conectaBDSICLAB();
	$consulta 		= sprintf("select claveDependencia,nombreDependencia from lbdependencias where claveDependencia=%d LIMIT 1",$dependencia);
	$res			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		$nombre = $row["nombreDependencia"];
	}
	return $nombre;

}
function devolucionPrestamo()
{
	$respuesta 	= false;
	session_start();
	$responsable 	= $_SESSION['nombre'];
	$prestamo		= "";
	$clavePrestamo 	= GetSQLValueString($_POST["clavePrestamo"],"int");
	$numC 			= consultaNCPrestamo($clavePrestamo);
	$nombreAlu 		= consultaAlumno($numC);
	$con 			= 0;
	$rows			= array();
	$renglones		= "";
	$conexion 		= conectaBDSICLAB();
	$consulta		= sprintf("select p.clavePrestamo,ac.nombreArticulo,a.identificadorArticulo 
		from lbarticuloscat ac 
		INNER JOIN lbarticulos a ON ac.claveArticulo=a.claveArticulo
		INNER JOIN lbprestamosarticulos pa on a.identificadorArticulo=pa.identificadorArticulo
		INNER JOIN lbprestamos p on p.clavePrestamo=pa.clavePrestamo
		where pa.estatus='P'");
	$res 		= mysql_query($consulta);

	$renglones	.= "<thead>";
	$renglones	.= "<tr>";
	$renglones	.= "<th data-field='cantidad'>Código</th>";
	$renglones	.= "<th data-field='descripcion'>Nombre el artículo</th>";
	$renglones	.= "<th data-field='descripcion'>Acción</th>";
	$renglones	.= "</tr>";
	$renglones	.= "</thead>";
	while($row = mysql_fetch_array($res))
	{
		$prestamo 	.= "'".($row["identificadorArticulo"])."',";
		$rows[]=$row;
		$respuesta = true;
		$con++;
	}
	$prestamo = (rtrim($prestamo,","));
	for($c= 0; $c< $con; $c++)
	{
		$renglones .= "<tbody>";
		$renglones .= "<tr>";
		$renglones .= "<td>".$rows[$c]["identificadorArticulo"]."</td>";
		$renglones .= "<td>".$rows[$c]["nombreArticulo"]."</td>";
		$renglones .= "<td><a name = '".$rows[$c]["identificadorArticulo"]."' class='btn waves-effect waves-light green darken-2 devolucionArt' id='btnDevolverArt'>Devolver</a></td>";
		$renglones .= "<td><a name = '".$rows[$c]["identificadorArticulo"]."' class='waves-effect waves-light btn amber darken-2 aplicaSancion' id='btnAplicaSancion'>Sancionar</a></td>";
		$renglones .= "</tr>";
		$renglones .= "</tbody>";
		$respuesta = true;
	}
	$salidaJSON = array('respuesta' => $respuesta, 
		'renglones' => $renglones, 
		'clavePrestamo' => $clavePrestamo,
		'numeroControl' => $numC,
		'nombreAlumno' => $nombreAlu);
	print json_encode($salidaJSON);
}
function consultaNCPrestamo($cveP)
{
	$clave 		= $cveP;
	$nc 		= "";
	$conexion 	= conectaBDSICLAB();
	$consulta  	= sprintf("select ALUCTR from lbprestamos where clavePrestamo=%d",$clave);
	$res 	 	=  mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		return (int)($row["ALUCTR"]);
	}
	else
	{
		return 0;
	}
}
function guardaDevolucion()
{
	$respuesta 	= false;
	session_start();
	$responsable			= $_SESSION['nombre'];
	$clavePrestamo 			= GetSQLValueString($_POST["clavePrestamo"],"text");
	$identificadorArticulo 	= GetSQLValueString($_POST["identificadorArticulo"],"text");
	$fecha 					= GetSQLValueString($_POST["fechaDevolucion"],"text");
	$hora 					= GetSQLValueString($_POST["horaDevolucion"],"text");
	$periodo 				= periodoActual();
	$claveResponsable 		= claveResp($responsable);
	$conexion 				= conectaBDSICLAB();
	$consulta  				= sprintf("insert into lbdevoluciones values(%s,%s,%s,%s,%s,%s)",
		$periodo,$clavePrestamo,$identificadorArticulo,$claveResponsable,$fecha,$hora);
	$res 	 				=  mysql_query($consulta);
	if(mysql_affected_rows()>0)
		$respuesta = true; 
	$arrayJSON = array('respuesta' => $respuesta);
	print json_encode($arrayJSON);
}
function aplicaSancion()
{
	$respuesta 	= false;
	session_start();
	$responsable 	= $_SESSION['nombre'];
	$idArt 			= GetSQLValueString($_POST["identificador"],"text");
	$clavePrestamo 	= GetSQLValueString($_POST["clavePrestamo"],"int");
	$numeroControl 	= GetSQLValueString($_POST["nc"],"text");
	$nombre 		= GetSQLValueString($_POST["nom"],"text");
	$con 			= 0;
	$comboSanciones	= array();
	$claveSancion	= "";
	$nombreSancion	= "";
	$conexion 		= conectaBDSICLAB();
	$consulta		= sprintf("select claveSancion,nombreSancion 
		from lbsanciones");
	$res 		= mysql_query($consulta);
	if($res)
	{
		while($row = mysql_fetch_array($res))
		{
			$comboSanciones[] = $row;
			$respuesta = true;
			$con++;
		}
		for ($i=0; $i < $con ; $i++)
		{ 
			$claveSancion[] 	=$comboSanciones[$i]["claveSancion"];
			$nombreSancion[] 	=$comboSanciones[$i]["nombreSancion"];
		}
	}
	$arrayJSON = array('respuesta' 		=> $respuesta,
		'claveSancion' 	=> $claveSancion, 
		'nombreSancion' => $nombreSancion, 
		'contador' 		=> $con,
		'prestamo' 		=> $clavePrestamo);
	print json_encode($arrayJSON);
}
function guardaSancion()
{
	$respuesta 	= false;
	session_start();
	$responsable 	= $_SESSION['nombre'];
	$periodo 		= periodoActual();
	$idArt 			= GetSQLValueString($_POST["idu"],"text");
	$clavePrestamo 	= GetSQLValueString($_POST["clavePrestamo"],"int");
	$numControl 	= GetSQLValueString($_POST["nc"],"text");
	$claveSancion 	= GetSQLValueString($_POST["claveSancion"],"int");
	$fecha 			= GetSQLValueString($_POST["fecha"],"text");
	$comentario 	= GetSQLValueString($_POST["comentario"],"text");
	$cveLab 		= GetSQLValueString(obtieneCveLab($responsable),"text");
	$conexion 		= conectaBDSICLAB();
	$consulta  		= sprintf("insert into lbasignasanciones values(%s,%d,%d,%s,%s,%s,%s,%s,%s,%s)",
		$periodo,'""',$claveSancion,$numControl,$fecha,'"dd/mm/aaaa"',$comentario,$idArt,$cveLab,'"P"');
	$res 	 		=  mysql_query($consulta);
	if(mysql_affected_rows()>0)
	{
		$consulta2 = sprintf("update lbprestamosarticulos set estatus='S' where identificadorArticulo=%s and clavePrestamo=%d",$idArt,$clavePrestamo);
		$res 	 		=  mysql_query($consulta2);
		if(mysql_affected_rows()>0)
		{
			$respuesta = true;
		}
	}	
	$arrayJSON = array('respuesta' => $respuesta);
	print json_encode($arrayJSON);
}
function listaAlumnosSancionados()
{
	$respuesta 	= false;
	session_start();
	$responsable= $_SESSION['nombre'];
	$prestamo	= "";
	$con 		= 0;
	$tipoUsuario= tipoUsuario($responsable);
	$rows		= array();
	$renglones	= "";
	$conexion 	= conectaBDSICLAB();
	$consulta	= sprintf("select sa.ALUCTR,sa.inicioSancion,sa.finSancion,sa.comentarios,s.claveSancion,ac.nombreArticulo 
		from lbsanciones s 
		inner join lbasignasanciones sa ON sa.claveSancion=s.claveSancion 
		INNER JOIN lbarticulos art ON art.identificadorArticulo=sa.identificadorArticulo 
		INNER JOIN lbarticuloscat ac ON ac.claveArticulo=art.claveArticulo
		where sa.estatus='P'");
	$res 		= mysql_query($consulta);

	$renglones	.= "<thead>";
	$renglones	.= "<tr>";
	$renglones	.= "<th data-field='numeroControl'>No. de control</th>";
	$renglones	.= "<th data-field='articulo'>Artículo</th>";
	$renglones	.= "<th data-field='fecha'>Inicio sanción</th>";
	$renglones	.= "<th data-field='hora'>Fin sanción</th>";
	$renglones	.= "<th data-field='comentarios'>Comentarios</th>";
	$renglones	.= "<th data-field='accion'>Acción</th>";
	$renglones	.= "</tr>";
	$renglones	.= "</thead>";
	while($row = mysql_fetch_array($res))
	{
		$prestamo 	.= "'".($row["claveSancion"])."',";
		$rows[]=$row;
		$respuesta = true;
		$con++;
	}
	$prestamo = (rtrim($prestamo,","));
	for($c= 0; $c< $con; $c++)
	{
		$renglones .= "<tbody>";
		$renglones .= "<tr>";
		$renglones .= "<td>".$rows[$c]["ALUCTR"]."</td>";
		$renglones .= "<td>".$rows[$c]["nombreArticulo"]."</td>";
		$renglones .= "<td>".$rows[$c]["inicioSancion"]."</td>";
		$renglones .= "<td>".$rows[$c]["finSancion"]."</td>";
		$renglones .= "<td>".$rows[$c]["comentarios"]."</td>";
		if($tipoUsuario != 3)
		{
			$renglones .= "<td><a name = '".$rows[$c]["claveSancion"]."' class='btn waves-effect waves-light green darken-2' id='btnQuitaSancion'>Quitar</a></td>";
		}
		$renglones .= "</tr>";
		$renglones .= "</tbody>";
		$respuesta = true;
	}
	$salidaJSON = array('respuesta' => $respuesta, 'renglones' => $renglones);
	print json_encode($salidaJSON);
}
function quitaSanciones()
{
	$respuesta 		= false;
	session_start();
	$responsable 	= $_SESSION['nombre'];
	$claveSancion 	= GetSQLValueString($_POST["claveSancion"],"int");
	$fecha 			= GetSQLValueString($_POST["fecha"],"text");
	$periodo 		= periodoActual();
	$conexion 		= conectaBDSICLAB();
	$consulta 		= sprintf("update lbasignasanciones set estatus='L', finSancion=%s where claveSancion=%d",$fecha,$claveSancion);
	$res 	 		=  mysql_query($consulta);
	if(mysql_affected_rows()>0)
		$respuesta = true;
	
	$arrayJSON = array('respuesta' => $respuesta);
	print json_encode($arrayJSON);
}
function guardaPeticionArticulos()
{
	$respuesta 		= false;
	session_start();
	$responsable	= $_SESSION['nombre'];
	$nombreArticulo = GetSQLValueString($_POST["nombreArticulo"],"text");
	$cantidad		= GetSQLValueString($_POST["cantidad"],"text");
	$marca 			= GetSQLValueString($_POST["marca"],"text");
	$modelo 		= GetSQLValueString($_POST["modelo"],"text");
	$motivo 		= GetSQLValueString($_POST["motivo"],"text");
	$fecha 			= GetSQLValueString($_POST["fecha"],"text");
	$cveLab 		= GetSQLValueString(obtieneCveLab($responsable),"text");
	$firma 			= "'000000'";
	$cveResp 		= claveResp($responsable);
	$periodo 		= periodoActual();
	$conexion 		= conectaBDSICLAB();
	$consulta  		= sprintf("insert into lbpedidos values(%s,%d,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",$periodo,"''",$fecha,$cveLab,$firma,$cveResp,$nombreArticulo,$cantidad,$motivo,$marca,$modelo,"'P'");
	$res 	 		=  mysql_query($consulta);
	if(mysql_affected_rows()>0)
		$respuesta = true; 

	$arrayJSON = array('respuesta' => $respuesta);
	print json_encode($arrayJSON);
}
function obtieneCveLab($clave)
{
	$cveResp 		= $clave;
	$conexion		= conectaBDSICLAB();
	$consulta 		= sprintf("select claveLaboratorio from lbresponsables where claveUsuario=%d",$cveResp);
	$res 			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		return $row["claveLaboratorio"];
	}
	else
	{
		return "''";
	}
}
function obtieneDepto($claveLab)
{
	$cveLab 		= $claveLab;
	$conexion		= conectaBDSICLAB();
	$consulta 		= sprintf("select DEPCVE 
		from lblaboratorios 
		where claveLaboratorio=%s",$cveLab);
	$res 			= mysql_query($consulta);
	if($row = mysql_fetch_array($res))
	{
		return $row["DEPCVE"];
	}
	else
	{
		return 0;
	}

}
function listaArticulosAlta()
{
	$resp 	 		= false;
	session_start();
	$responsable 	= $_SESSION['nombre'];
	$claveLab 		= GetSQLValueString(obtieneCveLab($responsable),"text");
	$departamento 	= obtieneDepto($claveLab);
	if ($departamento == 420) 
	{
		$flag="'S'";
	}
	elseif ($departamento == 407) 
	{
		$flag="'B'";
	}
	elseif ($departamento == ( 404 || 406 || 408 || 410)) 
	{
		$flag="'M'";
	}
	else
	{
		$flag="'G'";
	}

	$contador		= 0;
	$comboMaterial	= array();
	$claveMaterial 	= "";
	$nombreMaterial = "";
	$conexion		= conectaBDSICLAB();
	$consulta 		= sprintf("select claveArticulo,nombreArticulo 
		from lbarticuloscat where bandera=%s",$flag);
	$res 			= mysql_query($consulta);
	while($row = mysql_fetch_array($res))
	{
		$comboMaterial[] 	= $row;
		$resp 		 		= true;
		$contador++;
	}
	for ($i=0; $i < $contador ; $i++)
	{ 
		$claveMaterial[] 	=$comboMaterial[$i]["claveArticulo"];
		$nombreMaterial[] 	=$comboMaterial[$i]["nombreArticulo"];
	}
	$arrayJSON = array('respuesta' => $resp,
		'claveMaterial' => $claveMaterial,
		'nombreMaterial' => $nombreMaterial, 
		'contador' => $contador);
	print json_encode($arrayJSON);
}
function comboArtPeticiones()
{
	$respuesta 	 		= false;
	session_start();
	$responsable= $_SESSION['nombre'];
	$claveLab 	= GetSQLValueString(obtieneCveLab($responsable),"text");
	$art 		= "";
	$articulos 	= "";
	$con 		= 0;
	$comboArt	= array();
	$conexion 	= conectaBDSICLAB();
	$consulta	= sprintf("select B.claveArticulo,B.nombreArticulo
		FROM lbarticulos as A inner join lbarticuloscat as B ON A.claveArticulo=B.claveArticulo 
		INNER JOIN lbasignaarticulos C ON A.identificadorArticulo=C.identificadorArticulo 
		WHERE C.claveLaboratorio =%s GROUP BY A.claveArticulo",$claveLab);
	$res 		= mysql_query($consulta);
	if($res)
	{
		while($row = mysql_fetch_array($res))
		{
			$comboArt[] = $row;
			$respuesta = true;
			$con++;
		}
		for ($i=0; $i < $con ; $i++)
		{ 
			$articulos[] 	=$comboArt[$i]["nombreArticulo"];
		}
	}
	$arrayJSON = array('respuesta' => $respuesta, 
		'nombreArt' => $articulos, 
		'contador' => $con);
	print json_encode($arrayJSON);
}
function regresaMantenimiento()
{
	$respuesta 	 		= false;
	session_start();
	$responsable 			= $_SESSION['nombre'];
	$identificador 			= GetSQLValueString($_POST["iduArt"],"int");
	$periodo				= periodoActual();
	$estatus				= "'E'";
	$claveMovimiento		= 0;
	$claveLab				= GetSQLValueString(obtieneCveLab($responsable),"text");
	$observaciones			= "'Regresa de mantenimiento'";
	$fechaMovimiento		= GetSQLValueString($_POST["fecha"],"text");
	$horaMovimiento			= GetSQLValueString($_POST["hora"],"text");
	$claveResponsable 		= claveResp($responsable);
	$conexion 				= conectaBDSICLAB();

	$consulta 	= sprintf("insert into lbmovimientosarticulos values(%s,%s,%s,%s,%s,%s,%s,%d,%s)",
		$periodo,$fechaMovimiento,$horaMovimiento,$claveResponsable,$identificador,$observaciones,
		$estatus,$claveMovimiento,$claveLab);
	$res = mysql_query($consulta);
	if(mysql_affected_rows()>0)
		$respuesta = true;
	$salidaJSON = array('respuesta' => $respuesta);
	print json_encode($salidaJSON);
}
function buscaArtLista()
{
	$respuesta 	 	= false;
	session_start();
	$responsable 	= $_SESSION['nombre'];
	$articulo 	 	= "%".$_POST["articulo"]."%";
	$art 			= "";
	$articulos 		= "";
	$con 			= 0;
	$rows			= array();
	$renglones		= "";
	$claveLab 		= GetSQLValueString(obtieneCveLab($responsable),"text");
	$tipoUsu 		= tipoUsuario($responsable);
	$clavesLab 		= arrayLabs(jefeDepto(claveMaestro($responsable)));
	$conexion 		= conectaBDSICLAB();
	if ($tipoUsu == 5) 
	{
		$consulta	= sprintf("select B.claveArticulo,B.nombreArticulo,COUNT(A.claveArticulo) as cantidad 
			FROM lbarticulos as A 
			INNER JOIN lbarticuloscat as B ON A.claveArticulo=B.claveArticulo 
			INNER JOIN lbasignaarticulos C ON A.identificadorArticulo=C.identificadorArticulo 
			WHERE C.claveLaboratorio IN(%s) and B.nombreArticulo like '%s' GROUP BY A.claveArticulo",$clavesLab,$articulo);
	}
	else
	{
		$consulta	= sprintf("select B.claveArticulo,B.nombreArticulo,COUNT(A.claveArticulo) as cantidad 
			FROM lbarticulos as A 
			INNER JOIN lbarticuloscat as B ON A.claveArticulo=B.claveArticulo 
			INNER JOIN lbasignaarticulos C ON A.identificadorArticulo=C.identificadorArticulo 
			WHERE C.claveLaboratorio =%s and B.nombreArticulo like '%s' GROUP BY A.claveArticulo",$claveLab,$articulo);
	}
	$res 		= mysql_query($consulta);
	$renglones	.= "<thead>";
	$renglones	.= "<tr>";
	$renglones	.= "<th data-field='nombreArticulo'>Nombre del artículo</th>";
	$renglones	.= "<th data-field='cantidad'>Cantidad</th>";
	$renglones	.= "</tr>";
	$renglones	.= "</thead>";
	while($row = mysql_fetch_array($res))
	{
		$art 	.= "'".($row["claveArticulo"])."',";
		$rows[]=$row;
		$respuesta = true;
		$con++;
	}
	$art = (rtrim($art,","));
	for($c= 0; $c< $con; $c++)
	{
		$renglones .= "<tbody>";
		$renglones .= "<tr>";
		$renglones .= "<td>".$rows[$c]["nombreArticulo"]."</td>";
		$renglones .= "<td>".$rows[$c]["cantidad"]."</td>";
		$renglones .= "</tr>";
		$renglones .= "</tbody>";
		$respuesta = true;
	}
	$arrayJSON = array('respuesta' => $respuesta,
		'renglones' => $renglones);
	print json_encode($arrayJSON);
}
function listaDependencias()
{
	$respuesta 	 		= false;
	session_start();
	$responsable 		= $_SESSION['nombre'];
	$fecha 				= fechaPeriodo();
	$claveDependencia 	= "";
	$nombreDependencia 	= "";
	$contador 			= 0;
	$practicas 			= array();
	$contador2 			= 0;
	$claveLab 			= GetSQLValueString(obtieneCveLab($responsable),"text");
	$conexion 			= conectaBDSICLAB();
	$consulta 			= sprintf("select claveDependencia,nombreDependencia from lbdependencias where claveDependencia<>10000000");
	$res 				= mysql_query($consulta);
	while($row = mysql_fetch_array($res))
	{
		$claveDependencia[] 	= $row["claveDependencia"];
		$nombreDependencia[]	= $row["nombreDependencia"];
		$respuesta 		 		= true;
		$contador++;
	}
	$practicas = llenarComboPracticasExt();
	$contador2 = count($practicas['clavePract']);
	$arrayJSON = array('respuesta' 			=> $respuesta,
		'respuesta2'		=> $practicas['respuesta'],
		'claveDependencia' 	=> $claveDependencia,
		'nombreDependencia' => $nombreDependencia,
		'clavePractica' 	=> $practicas['clavePract'],
		'nombrePractica'	=> $practicas['tituloPract'],
		'contador' 			=> $contador,
		'contador2' 		=> $contador2,
		'fecha' 			=> $fecha);
	print json_encode($arrayJSON);
}
function llenarComboPracticasExt()
{
	$respuesta   = false;
	$clavePract  = array();
	$tituloPract = array();
	$conexion 	 = conectaBDSICLAB();
	$consulta 	 = sprintf("select clavePractica,tituloPractica from lbpracticas");
	$res 		 = mysql_query($consulta);
	while ($row  = mysql_fetch_array($res)) 
	{
		$clavePract[] 	= $row['clavePractica'];
		$tituloPract[] 	= $row['tituloPractica'];
		$respuesta 		= true;
	}
	$datosPracticas = array('clavePract' => $clavePract,
		'tituloPract' => $tituloPract,
		'respuesta' => $respuesta);
	return $datosPracticas;
}
function llenarcomboLabExt()
{
	$practica    = GetSQLValueString($_POST['practica'],"int");
	$respuesta   = false;
	$claveLab  	 = array();
	$nombreLab 	 = array();
	$conexion 	 = conectaBDSICLAB();
	$consulta 	 = sprintf("select l.claveLaboratorio, l.nombreLaboratorio from lbasignapracticas p inner join lblaboratorios l on p.claveLaboratorio = l.claveLaboratorio where clavePractica =%d",$practica);
	$res 		 = mysql_query($consulta);
	while ($row  = mysql_fetch_array($res)) 
	{
		$claveLab[] 	= $row['claveLaboratorio'];
		$nombreLab[] 	= $row['nombreLaboratorio'];
		$respuesta 		= true;
	}
	$arrayJSON 			= array('claveLab' => $claveLab,
		'nombreLab' => $nombreLab,
		'contador'  => count($claveLab),
		'respuesta' => $respuesta);
	print json_encode($arrayJSON);
}
function llenarcomboHrLabExt()
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
function construirTbArtExt()
{
	$cve 	 	= $_POST['articulosAgregadosExt'];
	$nom 	 	= $_POST['articulosExt'];
	$numArt		= $_POST['numArticulosExt'];
	$cveArt 	= explode(",",$cve);
	$nomArt 	= explode(",",$nom);
	$num 		= explode(",",$numArt);
	$n 			= count($cveArt);
	$respuesta 	= false;
	$renglones 	= "";
	for ($i=0; $i < $n ; $i++) 
	{ 
		if ($cveArt[$i]!= "") 
		{
			$respuesta	= true;
			$renglones .= "<tr id=".$cveArt[$i].">";
			$renglones .= "<td class='col s2'>".$num[$i]."</td>";
			$renglones .= "<td class='col s8'>".$nomArt[$i]."</td>";
			$renglones .= "<td class='col s2'><a name ='".$cveArt[$i]."' class='btnEliminarArtExt btn-floating btn-large waves-effect waves-light red darken-1'><i class='material-icons'>delete</i></a></td>";
			$renglones .= "</tr>";
		}
	}
	$arrayJSON = array('respuesta' => $respuesta,
		'renglones' => $renglones);
	print json_encode($arrayJSON);	
}
function comboEleArtExt()
{
	$laboratorio 	= GetSQLValueString($_POST["laboratorio"],"text");
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
function insertaSolicitud($dep,$fecE,$fecS,$hr,$prac,$lab,$cantAlu,$motivo,$nl,$art,$cant,$usu)
{
	$cveDep 		= $dep;
	$periodo 		= periodoActual();
	$cveSol 		= "''";
	$fechaE 		= $fecE;
	$fechaS 		= $fecS;
	$hora 			= $hr;
	$cveLab 		= $lab;
	$motivoUso 		= $motivo;
	$cvePractica 	= $prac;
	$usuario 		= $usu;
	$firma 			= "'000000'";
	$mat 			= "'NA'";
	$gpo 			= "'NA'";
	$cantAlumnos 	= $cantAlu;
	$estatus 		= "'V'";
	$numL 			= $nl;
	$PERCVE 		= '000';
	$articulos 		= $art;
	$cantidad 		= $cant;
	$art 			= array();
	$cant 			= array();
	$art 			= explode(",",$articulos);
	$cant 			= explode(",",$cantidad);
	//para validar
	if(valirdarFeHr($fechaS,$hora,$cveLab))
	{
		$conexion 		= conectaBDSICLAB();
		$consulta 		= sprintf("insert into lbsolicitudlaboratorios values(%s,%s,%s,%s,%s,%s,%s,%s,%d,%d,%d,%s,%s,%s,%s,%s)",$cveDep,$periodo,$cveSol,$fechaE,$fechaS,$hora,$cveLab,$motivoUso,$cvePractica,$usuario,$PERCVE,$firma,$mat,$gpo,$cantAlumnos,$estatus);
		$res 		= mysql_query($consulta);
		if(mysql_affected_rows()>0)
		{
			$claveSolicitud = mysql_insert_id($conexion);
			if(detalleArt($numL,$claveSolicitud,$art,$cant))
			{
				return true;
			}
		}
	}
}
function guardaSolLabExterno()
{

	$respuesta 		= false;
	session_start();
	$responsable 	= $_SESSION['nombre'];
	$dependencia   	= GetSQLValueString($_POST['dependencia'],"int");
	$nombreDep   	= GetSQLValueString($_POST['nomDep'],"text");
	$fechaE   		= GetSQLValueString($_POST['fechaEnvio'],"text");
	$fechaS   		= GetSQLValueString($_POST['fechaSol'],"text");
	$practica   	= GetSQLValueString($_POST['practica'],"int");
	$laboratorio   	= GetSQLValueString($_POST['laboratorio'],"text");
	$hora 		  	= GetSQLValueString($_POST['hora'],"text");
	$cantidad  		= GetSQLValueString($_POST['cantAlu'],"int");
	$motivo   		= GetSQLValueString($_POST['motivo'],"text");
	$nomEncargado	= GetSQLValueString($_POST['nomEncargado'],"text");
	$direccion   	= GetSQLValueString($_POST['direccion'],"text");
	$telefono  		= GetSQLValueString($_POST['telefono'],"text");
	$articulos   	= $_POST['articulos'];
	$cantArt   		= $_POST['cantArt'];
	$otra   		= GetSQLValueString($_POST['otra'],"int");
	$numeroLineas	= GetSQLValueString($_POST['numeroLineas'],"int");
	$claveDep 		= 0;
	if($otra == 1)
	{
		$conexion		= conectaBDSICLAB();
		$consulta 		= sprintf("insert into lbdependencias values(%d,%s,%s,%s,%s)",
			$claveDep,$nombreDep,$nomEncargado,$direccion,$telefono);
		$res = mysql_query($consulta);
		if(mysql_affected_rows()>0)
		{
			$claveDep = mysql_insert_id($conexion);
			if(insertaSolicitud($claveDep,$fechaE,$fechaS,$hora,$practica,$laboratorio,$cantidad,$motivo,$numeroLineas,$articulos,$cantArt,$responsable))
			{
				$respuesta = true;
			}
		}
	}
	else if(insertaSolicitud($dependencia,$fechaE,$fechaS,$hora,$practica,$laboratorio,$cantidad,$motivo,$numeroLineas,$articulos,$cantArt,$responsable))
	{
		$respuesta = true;
	}
	$arrayJSON 	= array('respuesta' 	=> $respuesta,
		'dependenciaN' 	=> $claveDep,
		'dependenciaE' 	=> $dependencia,
		'otra' 			=> $otra);
	print json_encode($arrayJSON);
	
}
//FIN ANA
//INICIO EDWIN

//AGREGUE
//CONSULTA LOS ALUMNOS QUE SE ENCUENTRAN ACTUALMENTE DENTRO DEL LABORATORIO
function alumnosActuales()
{
	date_default_timezone_set("America/Mazatlan");
	$respuesta 	= false;
	session_start();
	$usuario	= $_SESSION['nombre'];
	$labUsuario	= "";
	$tipoUsu 	= tipoUsuario($usuario);
	$claveLab 	= GetSQLValueString(obtieneCveLab($usuario),"text");
	$rows		= array();
	$renglones	= "";
	$fechaActual= date("'Y-m-d'");
	$noExternos = 0;
	$conexion 	= conectaBDSICLAB();
	if($tipoUsu == 5)
	{
		$labs 		= arrayLabs(jefeDepto(claveMaestro($usuario)));
		$consulta 	= sprintf("select count(*) as Contador 
			from lbentradasalumnos a 
			inner join lbcalendarizaciones c on a.claveCalendarizacion=c.claveCalendarizacion 
			inner join lbsolicitudlaboratorios l on l.claveSolicitud=c.claveSolicitud 
			where l.claveLaboratorio IN(%s) and a.fechaEntrada = %s and a.tipoEntrada='I'",$labs,$fechaActual);
		$noExternos = cuentaExternos($tipoUsu,$labs,$fechaActual);
	}
	else
	{
		$consulta 	= sprintf("select count(*) as Contador 
			from lbentradasalumnos a 
			inner join lbcalendarizaciones c on a.claveCalendarizacion=c.claveCalendarizacion 
			inner join lbsolicitudlaboratorios l on l.claveSolicitud=c.claveSolicitud 
			where l.claveLaboratorio=%s and a.fechaEntrada = %s and a.tipoEntrada='I'",$claveLab,$fechaActual);
		$noExternos = cuentaExternos($tipoUsu,$claveLab,$fechaActual);
	}
	$res = mysql_query($consulta);
		if (mysql_num_rows($res)) //Si se encontraron datos en la búsqueda 
		{ 
			$rows = mysql_fetch_array($res);  
			$renglones .= "<p>Uso del día</p>";
			$renglones .= "<p style= font-size:16px;>".$rows["Contador"]." Alumnos</p>";
			$renglones .= "<p style= font-size:16px;>".(int)$noExternos." Externos</p>";
			$renglones .= "<p style= font-size:16px;><br></p>";
			$respuesta  = true; 
		} 
		$arrayJSON = array('respuesta' => $respuesta,
			'renglones' => $renglones);
		print json_encode($arrayJSON);
	}
	function cuentaExternos($usu,$lab,$fecha)
	{
		$usuario 	= $usu;
		$cveLab 	= $lab;
		$fecActual 	= $fecha;
		$conexion 	= conectaBDSICLAB();
		if ($usuario == 5) 
		{
			$consulta 	= sprintf("select count(*) as Contador2 
				from lbentradasalumnos a 
				inner join lbcalendarizaciones c on a.claveCalendarizacion=c.claveCalendarizacion 
				inner join lbsolicitudlaboratorios l on l.claveSolicitud=c.claveSolicitud 
				where l.claveLaboratorio in(%s) and a.fechaEntrada = %s and a.tipoEntrada='E'",$cveLab,$fecActual);
		}
		else
		{
			$consulta 	= sprintf("select count(*) as Contador2 
				from lbentradasalumnos a 
				inner join lbcalendarizaciones c on a.claveCalendarizacion=c.claveCalendarizacion 
				inner join lbsolicitudlaboratorios l on l.claveSolicitud=c.claveSolicitud 
				where l.claveLaboratorio=%s and a.fechaEntrada = %s and a.tipoEntrada='E'",$cveLab,$fecActual);
		}
		$res = mysql_query($consulta);
	if (mysql_num_rows($res)) //Si se encontraron datos en la búsqueda 
	{
		return ["Contador2"];
	}
	else
		return 0;

}
//CONSULTA EL ARTICULO MAS PRESTADO Y SU CANTIDAD
function articuloMasPrestado()
{
	$respuesta 	= false;
	session_start();
	$usuario	= $_SESSION['nombre'];
	$labUsuario ="";
	$tipoUsu 	= tipoUsuario($usuario);
	$claveLab 	= GetSQLValueString(obtieneCveLab($usuario),"text");
	$rows		= array();
	$renglones	= "";
	$conexion 	= conectaBDSICLAB();
	if($tipoUsu == 5)
	{
		$labs 		= arrayLabs(jefeDepto(claveMaestro($usuario)));
		$consulta 	= sprintf("select ac.nombreArticulo,COUNT(a.claveArticulo) cantidad
			from lbarticulos a 
			INNER JOIN lbprestamosarticulos pa 
			ON a.identificadorArticulo=pa.identificadorArticulo
			INNER JOIN lbarticuloscat ac 
			ON a.claveArticulo=ac.claveArticulo
			INNER JOIN lbasignaarticulos aa
			ON a.identificadorArticulo= aa.identificadorArticulo
			WHERE aa.claveLaboratorio in(%s) 
			GROUP BY a.claveArticulo
			ORDER BY a.claveArticulo DESC LIMIT 1",$labs);
	}
	else
	{
		$consulta 	= sprintf("select ac.nombreArticulo,COUNT(a.claveArticulo) cantidad
			from lbarticulos a 
			INNER JOIN lbprestamosarticulos pa 
			ON a.identificadorArticulo=pa.identificadorArticulo
			INNER JOIN lbarticuloscat ac 
			ON a.claveArticulo=ac.claveArticulo
			INNER JOIN lbasignaarticulos aa
			ON a.identificadorArticulo= aa.identificadorArticulo
			WHERE aa.claveLaboratorio = %s
			GROUP BY a.claveArticulo
			ORDER BY a.claveArticulo DESC LIMIT 1",$claveLab);
	}
	$res = mysql_query($consulta);
		if (mysql_num_rows($res)) //Si se encontraron datos en la búsqueda 
		{ 
			$rows = mysql_fetch_array($res); 
			$renglones .= "<p>Materiales</p>";
			$renglones .= "<p style= font-size:16px;> Mas solicitado: <br> ".$rows["nombreArticulo"]." (".$rows["cantidad"].")</p>";
			$respuesta=true; 
		}
		$arrayJSON = array('respuesta' => $respuesta,
			'renglones' => $renglones);
		print json_encode($arrayJSON);
	}
//Pendiente materiales sin stock
	function articulosSinExistencia()
	{
		$respuesta 	= false;
		session_start();
		$usuario 	= $_SESSION['nombre'];
		$prestamo	= "";
		$con 		= 0;
		$tipoUsu 	= tipoUsuario($usuario);
		$claveLab 	= GetSQLValueString(obtieneCveLab($usuario),"text");
		$rows		= array();
		$renglones	= "";
		$conexion 	= conectaBDSICLAB();
		if($tipoUsu == 5)
		{
			$labs 		= arrayLabs(jefeDepto(claveMaestro($usuario)));
			$consulta	= sprintf("select COUNT(a.claveArticulo) as cant, ac.nombreArticulo
				from lbarticulos a
				INNER JOIN lbasignaarticulos aa 
				on a.identificadorArticulo = aa.identificadorArticulo
				inner JOIN lbinventarios i 
				on i.claveArticulo = a.claveArticulo
				inner join lbarticuloscat ac
				on a.claveArticulo=ac.claveArticulo
				WHERE aa.claveLaboratorio in(%s) AND i.cantidad = 0",$labs);
		}
		else
		{
			$consulta	= sprintf("select COUNT(a.claveArticulo) as cant, ac.nombreArticulo
				from lbarticulos a
				INNER JOIN lbasignaarticulos aa 
				on a.identificadorArticulo = aa.identificadorArticulo
				inner JOIN lbinventarios i 
				on i.claveArticulo = a.claveArticulo
				inner join lbarticuloscat ac
				on a.claveArticulo=ac.claveArticulo
				WHERE aa.claveLaboratorio = %s AND i.cantidad = 0",$claveLab);
		}
		$res 		= mysql_query($consulta);

		$renglones	.= "<thead>";
		$renglones	.= "<tr>";
		$renglones	.= "<th data-field='descripcionArticulo'>Articulo</th>";
		$renglones	.= "</tr>";
		$renglones	.= "</thead>";
		while($row = mysql_fetch_array($res))
		{
			$rows[]=$row;
			$respuesta = true;
			$con++;
		}
		
		for($c= 0; $c< $con; $c++)
		{
			$renglones .= "<tbody>";
			$renglones .= "<tr>";
			$renglones .= "<td>".$rows[$c]["nombreArticulo"]."</td>";
			$renglones .= "</tr>";
			$renglones .= "</tbody>";
			$respuesta = true;
		}
		$salidaJSON = array('respuesta' => $respuesta, 
			'renglones' => $renglones);
		print json_encode($salidaJSON);
	}
	function proximosApartados()
	{
		date_default_timezone_set("America/Mazatlan");
		$respuesta 	= false;
		session_start();
		$responsable= $_SESSION['nombre'];
		$claveLab 	= GetSQLValueString(obtieneCveLab($responsable),"text");
		$con 		= 0;
		$rows		= array();
		$renglones	= "";
		$maestro 	= "";
		$percve 	= "";
		$nomMaestro = "";
		$nomMat 	= "";
		$fechaActual= date("'Y-m-d'");
		$tipoUsu 	= tipoUsuario($responsable);
		$solAceptadas="";
		$conexion 	= conectaBDSICLAB();
		if($tipoUsu == 5)
		{
			$labs 		= arrayLabs(jefeDepto(claveMaestro($responsable)));
			$consulta			= sprintf("select s.claveSolicitud,s.MATCVE,p.tituloPractica,c.fechaAsignada,c.horaAsignada,s.PERCVE 
				from lbsolicitudlaboratorios as s  
				INNER JOIN lbpracticas as p ON p.clavePractica = s.clavePractica
				INNER JOIN lbcalendarizaciones c ON c.claveSolicitud=s.claveSolicitud
				where s.estatus='V' and s.claveLaboratorio in(%s) and c.fechaAsignada >= %s",$labs,$fechaActual);
		}
		else
		{
			$consulta			= sprintf("select s.claveSolicitud,s.MATCVE,p.tituloPractica,c.fechaAsignada,c.horaAsignada,s.PERCVE 
				from lbsolicitudlaboratorios as s 
				INNER JOIN lbpracticas as p ON p.clavePractica = s.clavePractica
				INNER JOIN lbcalendarizaciones c ON c.claveSolicitud=s.claveSolicitud
				where s.estatus='V' and s.claveLaboratorio=%s and c.estatus='NR' and c.fechaAsignada >= %s",$claveLab,$fechaActual);
		}
		$res 		= mysql_query($consulta);
		$renglones	.= "<thead>";
		$renglones	.= "<tr>";
		$renglones	.= "<th data-field='maestro'>Maestro</th>";
		$renglones	.= "<th data-field='materia'>Materia</th>";
		$renglones	.= "<th data-field='nombrePractica'>Nombre de la práctica</th>";
		$renglones	.= "<th data-field='fecha'>Fecha</th>";
		$renglones	.= "<th data-field='hora'>Hora</th>";
		$renglones	.= "</tr>";
		$renglones	.= "</thead>";
		
		while($row = mysql_fetch_array($res))
		{
			$solAceptadas .="'".($row["claveSolicitud"])."',";
			$rows[]=$row;
			$respuesta = true;
			$con++;
		}
		$solAceptadas = (rtrim($solAceptadas,","));
		for($c= 0; $c< $con; $c++)
		{
			$maestro 	= $rows[$c]["PERCVE"];
			$nomMaestro = nomMae($maestro);
			$cveGpo 	= $rows[$c]["MATCVE"];
			$nomMat 	= nombreMat($cveGpo);
			$renglones .= "<tbody>";
			$renglones .= "<tr>";
			$renglones .= "<td>".$nomMaestro."</td>";
			$renglones .= "<td>".$nomMat."</td>";
			$renglones .= "<td>".$rows[$c]["tituloPractica"]."</td>";
			$renglones .= "<td>".$rows[$c]["fechaAsignada"]."</td>";
			$renglones .= "<td>".$rows[$c]["horaAsignada"]."</td>";
			$renglones .= "</tr>";
			$renglones .= "</tbody>";
			$respuesta = true;
		}
		$arrayJSON = array('respuesta' 	=> $respuesta,
			'renglones' => $renglones,
			'contador' 	=> $con);
		print json_encode($arrayJSON);
	}
	function resumenInventarioActual()
	{
		$respuesta 	= false;
		session_start();
		$responsable= $_SESSION['nombre'];
		$prestamo	= "";
		$con 		= 0;
		$claveLab 	= GetSQLValueString(obtieneCveLab($responsable),"text"); 
		$tipoUsu 	= tipoUsuario($responsable);
		$rows		= array();
		$renglones	= "";
		$conexion 	= conectaBDSICLAB();
		if($tipoUsu == 5)
		{
			$labs 		= arrayLabs(jefeDepto(claveMaestro($responsable)));
			$consulta	= sprintf("select a.identificadorArticulo,ac.nombreArticulo,
				CASE WHEN a.fechaCaducidad='1900-01-01' THEN 'NA' ELSE a.fechaCaducidad END as caducidad
				FROM lbarticulos a
				INNER JOIN lbarticuloscat ac 
				ON a.claveArticulo=ac.claveArticulo
				INNER JOIN lbasignaarticulos aa
				ON a.identificadorArticulo = aa.identificadorArticulo
				WHERE aa.claveLaboratorio in(%s) and a.estatus='V'",$labs);
		}
		else
		{
			$consulta	= sprintf("select a.identificadorArticulo,ac.nombreArticulo,
				CASE WHEN a.fechaCaducidad='1900-01-01' THEN 'NA' ELSE a.fechaCaducidad END as caducidad
				FROM lbarticulos a
				INNER JOIN lbarticuloscat ac 
				ON a.claveArticulo=ac.claveArticulo
				INNER JOIN lbasignaarticulos aa
				ON a.identificadorArticulo = aa.identificadorArticulo
				WHERE aa.claveLaboratorio=%s and a.estatus='V'",$claveLab);
		}
		$res 		= mysql_query($consulta);
		$renglones	.= "<thead>";
		$renglones	.= "<tr>";
		$renglones	.= "<th data-field='identificador'>Identificador</th>";
		$renglones	.= "<th data-field='nombre'>Nombre del artículo</th>";
		$renglones	.= "<th data-field='caducidad'>Caducidad</th>";
		$renglones	.= "</tr>";
		$renglones	.= "</thead>";
		while($row = mysql_fetch_array($res))
		{
			$rows[]=$row;
			$respuesta = true;
			$con++;
		}
		
		for($c= 0; $c< $con; $c++)
		{
			$renglones .= "<tbody>";
			$renglones .= "<tr>";
			$renglones .= "<td>".$rows[$c]["identificadorArticulo"]."</td>";
			$renglones .= "<td>".$rows[$c]["nombreArticulo"]."</td>";
			$renglones .= "<td>".$rows[$c]["caducidad"]."</td>";
			$renglones .= "</tr>";
			$renglones .= "</tbody>";
			$respuesta = true;
		}
		$salidaJSON = array('respuesta' => $respuesta, 
			'renglones' => $renglones);
		print json_encode($salidaJSON);
	}
	function enBaja()
	{
		$respuesta 	= false;
		session_start();
		$responsable= $_SESSION['nombre'];
		$con 		= 0;
		$periodo 	= periodoActual();
		$claveLab 	= GetSQLValueString(obtieneCveLab($responsable),"text"); 
		$rows		= array();
		$renglones	= "";
		$tipoUsu 	= tipoUsuario($responsable);
		$conexion 	= conectaBDSICLAB();
		if($tipoUsu == 5)
		{
			$labs 		= arrayLabs(jefeDepto(claveMaestro($responsable)));
			$consulta	= sprintf("select a.identificadorArticulo,cat.nombreArticulo,ma.observaciones 
				from lbmovimientosarticulos ma
				INNER JOIN lbarticulos a ON ma.identificadorArticulo=a.identificadorArticulo
				INNER JOIN lbarticuloscat cat on a.claveArticulo=cat.claveArticulo
				INNER JOIN lbasignaarticulos aa on aa.identificadorArticulo=a.identificadorArticulo
				WHERE a.estatus='B' AND aa.claveLaboratorio in(%s) and ma.PDOCVE=%s and ma.observaciones<>'Entrada de artículo' 
				ORDER BY identificadorArticulo",$labs,$periodo);
		}
		else
		{
			$consulta	= sprintf("select a.identificadorArticulo,cat.nombreArticulo,ma.observaciones 
				from lbmovimientosarticulos ma
				INNER JOIN lbarticulos a ON ma.identificadorArticulo=a.identificadorArticulo
				INNER JOIN lbarticuloscat cat on a.claveArticulo=cat.claveArticulo
				INNER JOIN lbasignaarticulos aa on aa.identificadorArticulo=a.identificadorArticulo
				WHERE a.estatus='B' AND aa.claveLaboratorio=%s and ma.PDOCVE=%s and ma.observaciones<>'Entrada de artículo' 
				ORDER BY identificadorArticulo",$claveLab,$periodo);
		}
		
		$res 		= mysql_query($consulta);
		$renglones	.= "<thead>";
		$renglones	.= "<tr>";
		$renglones	.= "<th data-field='identificador'>Identificador</th>";
		$renglones	.= "<th data-field='nombre'>Nombre del artículo</th>";
		$renglones	.= "<th data-field='motivo'>Motivo</th>";
		$renglones	.= "</tr>";
		$renglones	.= "</thead>";
		while($row = mysql_fetch_array($res))
		{
			$rows[]=$row;
			$respuesta = true;
			$con++;
		}
		
		for($c= 0; $c< $con; $c++)
		{
			$renglones .= "<tbody>";
			$renglones .= "<tr>";
			$renglones .= "<td>".$rows[$c]["identificadorArticulo"]."</td>";
			$renglones .= "<td>".$rows[$c]["nombreArticulo"]."</td>";
			$renglones .= "<td>".$rows[$c]["observaciones"]."</td>";
			$renglones .= "</tr>";
			$renglones .= "</tbody>";
			$respuesta = true;
		}
		$salidaJSON = array('respuesta' => $respuesta,
			'renglones' => $renglones);
		print json_encode($salidaJSON);
	}
	function enReparacion()
	{
		$respuesta 	= false;
		session_start();
		$responsable= $_SESSION['nombre'];
		$con 		= 0;
		$periodo 	= periodoActual();
		$claveLab 	= GetSQLValueString(obtieneCveLab($responsable),"text");
		$tipoUsu 	= tipoUsuario($responsable);
		$rows		= array();
		$renglones	= "";
		$conexion 	= conectaBDSICLAB();
		if($tipoUsu == 5)
		{
			$labs 		= arrayLabs(jefeDepto(claveMaestro($responsable)));
			$consulta	= sprintf("select m.identificadorArticulo,ac.nombreArticulo,m.observaciones,m.fechaMovimiento 
				from lbmovimientosarticulos m
				INNER JOIN lbarticulos a
				ON m.identificadorArticulo=a.identificadorArticulo
				INNER JOIN lbarticuloscat ac
				ON a.claveArticulo=ac.claveArticulo
				WHERE m.estatus='M' AND m.claveLaboratorio in(%s) AND m.PDOCVE=%s",$labs,$periodo);
		}
		else
		{
			$consulta	= sprintf("select m.identificadorArticulo,ac.nombreArticulo,m.observaciones,m.fechaMovimiento 
				from lbmovimientosarticulos m
				INNER JOIN lbarticulos a
				ON m.identificadorArticulo=a.identificadorArticulo
				INNER JOIN lbarticuloscat ac
				ON a.claveArticulo=ac.claveArticulo
				WHERE m.estatus='M' AND m.claveLaboratorio=%s AND m.PDOCVE=%s",$claveLab,$periodo);
		}
		$res 		= mysql_query($consulta);
		$renglones	.= "<thead>";
		$renglones	.= "<tr>";
		$renglones	.= "<th data-field='identificador'>Identificador</th>";
		$renglones	.= "<th data-field='nombre'>Nombre del artículo</th>";
		$renglones	.= "<th data-field='fecha'>Fecha de envío</th>";
		$renglones	.= "<th data-field='comentarios'>Motivo</th>";
		$renglones	.= "</tr>";
		$renglones	.= "</thead>";
		while($row = mysql_fetch_array($res))
		{
			$rows[]=$row;
			$respuesta = true;
			$con++;
		}
		
		for($c= 0; $c< $con; $c++)
		{
			$renglones .= "<tbody>";
			$renglones .= "<tr>";
			$renglones .= "<td>".$rows[$c]["identificadorArticulo"]."</td>";
			$renglones .= "<td>".$rows[$c]["nombreArticulo"]."</td>";
			$renglones .= "<td>".$rows[$c]["fechaMovimiento"]."</td>";
			$renglones .= "<td>".$rows[$c]["observaciones"]."</td>";
			$renglones .= "</tr>";
			$renglones .= "</tbody>";
			$respuesta = true;
		}
		$salidaJSON = array('respuesta' => $respuesta,
			'renglones' => $renglones);
		print json_encode($salidaJSON);
	}
	function enPrestamo()
	{
		$respuesta 	= false;
		session_start();
		$responsable= $_SESSION['nombre'];
		$claveLab 	= GetSQLValueString(obtieneCveLab($responsable),"text");
		$tipoUsu 	= tipoUsuario($responsable);
		$prestamo	= "";
		$con 		= 0;
		$rows		= array();
		$renglones	= "";
		$conexion 	= conectaBDSICLAB();
		if($tipoUsu == 5)
		{
			$labs 		= arrayLabs(jefeDepto(claveMaestro($responsable)));
			$consulta	= sprintf("select cat.nombreArticulo,COUNT(sa.cantidad) cantidad, 
				CASE WHEN sa.estatus='A' THEN 'PRESTADO' ELSE 'NEGADO' END AS estatus
				FROM lbarticuloscat cat
				INNER JOIN lbsolicitudarticulos sa 
				ON sa.claveArticulo=cat.claveArticulo
				INNER JOIN lbprestamos p
				ON p.clavePrestamo = sa.clavePrestamo
				INNER JOIN lbentradasalumnos ea
				on p.ALUCTR = ea.ALUCTR
				WHERE sa.estatus <> 'S' and ea.claveLaboratorio in(%s)
				GROUP BY sa.cantidad,cat.nombreArticulo",$labs);
		}
		$consulta	= sprintf("select cat.nombreArticulo,COUNT(sa.cantidad) cantidad, 
			CASE WHEN sa.estatus='A' THEN 'PRESTADO' ELSE 'NEGADO' END AS estatus
			FROM lbarticuloscat cat
			INNER JOIN lbsolicitudarticulos sa 
			ON sa.claveArticulo=cat.claveArticulo
			INNER JOIN lbprestamos p
			ON p.clavePrestamo = sa.clavePrestamo
			INNER JOIN lbentradasalumnos ea
			on p.ALUCTR = ea.ALUCTR
			WHERE sa.estatus <> 'S' and ea.claveLaboratorio=%s
			GROUP BY sa.cantidad,cat.nombreArticulo",$claveLab);
		$res 		= mysql_query($consulta);

		$renglones	.= "<thead>";
		$renglones	.= "<tr>";
		$renglones	.= "<th data-field='nombre'>Nombre del artículo</th>";
		$renglones	.= "<th data-field='cantidad'>Cantidad</th>";
		$renglones	.= "<th data-field='accion'>Prestado/Negado</th>";
		$renglones	.= "</tr>";
		$renglones	.= "</thead>";
		while($row = mysql_fetch_array($res))
		{
			$rows[]=$row;
			$respuesta = true;
			$con++;
		}
		
		for($c= 0; $c< $con; $c++)
		{
			$renglones .= "<tbody>";
			$renglones .= "<tr>";
			$renglones .= "<td>".$rows[$c]["nombreArticulo"]."</td>";
			$renglones .= "<td>".$rows[$c]["cantidad"]."</td>";
			$renglones .= "<td>".$rows[$c]["estatus"]."</td>";
			$renglones .= "</tr>";
			$renglones .= "</tbody>";
			$respuesta = true;
		}
		$salidaJSON = array('respuesta' => $respuesta, 
			'renglones' => $renglones);
		print json_encode($salidaJSON);
	}
	function enPedido()
	{
		$respuesta 	= false;
		session_start();
		$responsable= $_SESSION['nombre'];
		$claveLab 	= GetSQLValueString(obtieneCveLab($responsable),"text");
		$tipoUsu 	= tipoUsuario($responsable) ;
		$prestamo	= "";
		$con 		= 0;
		$rows		= array();
		$renglones	= "";
		$conexion 	= conectaBDSICLAB();
		if($tipoUsu == 5)
		{
			$labs 		= arrayLabs(jefeDepto(claveMaestro($responsable)));
			$consulta	= sprintf("select clavePedido,fechaPedido,nombreArticulo,cantidad,motivoPedido 
				FROM lbpedidos l 
				inner join lbresponsables r on l.claveResponsable=r.claveResponsable 
				where r.claveLaboratorio in(%s) and l.estatus='P'",$labs);
		}
		else
		{
			$consulta	= sprintf("select clavePedido,fechaPedido,nombreArticulo,cantidad,motivoPedido 
				FROM lbpedidos l 
				inner join lbresponsables r on l.claveResponsable=r.claveResponsable 
				where r.claveLaboratorio=%s and l.estatus='P'",$claveLab);
		}
		$res 		= mysql_query($consulta);

		$renglones	.= "<thead>";
		$renglones	.= "<tr>";
		$renglones	.= "<th data-field='clavePedido'>Clave</th>";
		$renglones	.= "<th data-field='fechaPedido'>Fecha</th>";
		$renglones	.= "<th data-field='nombreArticulo'>Articulo</th>";
		$renglones	.= "<th data-field='cantidad'>Cantidad</th>";
		$renglones	.= "<th data-field='motivoPedido'>Motivo</th>";
		$renglones	.= "</tr>";
		$renglones	.= "</thead>";
		while($row = mysql_fetch_array($res))
		{
			$rows[]=$row;
			$respuesta = true;
			$con++;
		}
		
		for($c= 0; $c< $con; $c++)
		{
			$renglones .= "<tbody>";
			$renglones .= "<tr>";
			$renglones .= "<td>".$rows[$c]["clavePedido"]."</td>";
			$renglones .= "<td>".$rows[$c]["fechaPedido"]."</td>";
			$renglones .= "<td>".$rows[$c]["nombreArticulo"]."</td>";
			$renglones .= "<td>".$rows[$c]["cantidad"]."</td>";
			$renglones .= "<td>".$rows[$c]["motivoPedido"]."</td>";
			$renglones .= "</tr>";
			$renglones .= "</tbody>";
			$respuesta = true;
		}
		$salidaJSON = array('respuesta' => $respuesta, 'renglones' => $renglones);
		print json_encode($salidaJSON);
	}
	function practicasNoRealizadas()
	{
		$respuesta 	= false;
		session_start();
		$responsable= $_SESSION['nombre'];
		$claveLab 	= GetSQLValueString(obtieneCveLab($responsable),"text");
		$tipoUsu 	= tipoUsuario($responsable);
		$prestamo	= "";
		$con 		= 0;
		$rows		= array();
		$renglones	= "";
		$conexion 	= conectaBDSICLAB();
		if($tipoUsu == 5)
		{
			$labs 		= arrayLabs(jefeDepto(claveMaestro($responsable)));
			$consulta	= sprintf("select c.claveCalendarizacion, c.fechaAsignada, c.horaAsignada, p.tituloPractica, p.duracionPractica
				from lbcalendarizaciones c
				INNER JOIN lbsolicitudlaboratorios sl on c.claveSolicitud=sl.claveSolicitud
				INNER JOIN lbasignapracticas ap on ap.clavePractica=sl.clavePractica
				INNER JOIN lbpracticas p on ap.clavePractica=p.clavePractica
				WHERE ap.claveLaboratorio in(%s) and c.estatus='NR' and p.estatus='V'",$labs);
		}
		else
		{
			$consulta	= sprintf("select c.claveCalendarizacion, c.fechaAsignada, c.horaAsignada, p.tituloPractica, p.duracionPractica
				from lbcalendarizaciones c
				INNER JOIN lbsolicitudlaboratorios sl on c.claveSolicitud=sl.claveSolicitud
				INNER JOIN lbasignapracticas ap on ap.clavePractica=sl.clavePractica
				INNER JOIN lbpracticas p on ap.clavePractica=p.clavePractica
				WHERE ap.claveLaboratorio=%s and c.estatus='NR' and p.estatus='V'",$claveLab);
		}
		$res 		= mysql_query($consulta);
		$renglones	.= "<thead>";
		$renglones	.= "<tr>";
		$renglones	.= "<th data-field='claveCalendarizacion'>Clave</th>";
		$renglones	.= "<th data-field='fechaAsignada'>Fecha</th>";
		$renglones	.= "<th data-field='horaAsignada'>Hora</th>";
		$renglones	.= "<th data-field='tituloPractica'>Práctica</th>";
		$renglones	.= "<th data-field='duracionPractica'>Duración</th>";
		$renglones	.= "</tr>";
		$renglones	.= "</thead>";
		while($row = mysql_fetch_array($res))
		{
			$rows[]=$row;
			$respuesta = true;
			$con++;
		}
		
		for($c= 0; $c< $con; $c++)
		{
			$renglones .= "<tbody>";
			$renglones .= "<tr>";
			$renglones .= "<td>".$rows[$c]["claveCalendarizacion"]."</td>";
			$renglones .= "<td>".$rows[$c]["fechaAsignada"]."</td>";
			$renglones .= "<td>".$rows[$c]["horaAsignada"]."</td>";
			$renglones .= "<td>".$rows[$c]["tituloPractica"]."</td>";
			$renglones .= "<td>".$rows[$c]["duracionPractica"]."</td>";
			$renglones .= "</tr>";
			$renglones .= "</tbody>";
			$respuesta = true;
		}
		$salidaJSON = array('respuesta' => $respuesta, 'renglones' => $renglones);
		print json_encode($salidaJSON);
	}
	function practicasRealizadas()
	{
		$respuesta 	= false;
		session_start();
		$responsable= $_SESSION['nombre'];
		$claveLab 	= GetSQLValueString(obtieneCveLab($responsable),"text");
		$tipoUsu 	= tipoUsuario($responsable);
		$prestamo	= "";
		$con 		= 0;
		$rows		= array();
		$renglones	= "";
		$conexion 	= conectaBDSICLAB();
		if ($tipoUsu == 5) 
		{
			$labs 		= arrayLabs(jefeDepto(claveMaestro($responsable)));
			$consulta	= sprintf("select c.claveCalendarizacion, c.fechaAsignada, c.horaAsignada, p.tituloPractica, p.duracionPractica
				from lbcalendarizaciones c
				INNER JOIN lbsolicitudlaboratorios sl on c.claveSolicitud=sl.claveSolicitud
				INNER JOIN lbasignapracticas ap on ap.clavePractica=sl.clavePractica
				INNER JOIN lbpracticas p on ap.clavePractica=p.clavePractica
				WHERE ap.claveLaboratorio in(%s) and c.estatus='R' and p.estatus='V'",$labs);
		}
		else
		{
			$consulta	= sprintf("select c.claveCalendarizacion, c.fechaAsignada, c.horaAsignada, p.tituloPractica, p.duracionPractica
				from lbcalendarizaciones c
				INNER JOIN lbsolicitudlaboratorios sl on c.claveSolicitud=sl.claveSolicitud
				INNER JOIN lbasignapracticas ap on ap.clavePractica=sl.clavePractica
				INNER JOIN lbpracticas p on ap.clavePractica=p.clavePractica
				WHERE ap.claveLaboratorio=%s and c.estatus='R' and p.estatus='V'",$claveLab);
		}
		$res 		= mysql_query($consulta);
		$renglones	.= "<thead>";
		$renglones	.= "<tr>";
		$renglones	.= "<th data-field='claveCalendarizacion'>Clave</th>";
		$renglones	.= "<th data-field='fechaAsignada'>Fecha</th>";
		$renglones	.= "<th data-field='horaAsignada'>Hora</th>";
		$renglones	.= "<th data-field='tituloPractica'>Práctica</th>";
		$renglones	.= "<th data-field='duracionPractica'>Duración</th>";
		$renglones	.= "</tr>";
		$renglones	.= "</thead>";
		while($row = mysql_fetch_array($res))
		{
			$rows[]=$row;
			$respuesta = true;
			$con++;
		}
		
		for($c= 0; $c< $con; $c++)
		{
			$renglones .= "<tbody>";
			$renglones .= "<tr>";
			$renglones .= "<td>".$rows[$c]["claveCalendarizacion"]."</td>";
			$renglones .= "<td>".$rows[$c]["fechaAsignada"]."</td>";
			$renglones .= "<td>".$rows[$c]["horaAsignada"]."</td>";
			$renglones .= "<td>".$rows[$c]["tituloPractica"]."</td>";
			$renglones .= "<td>".$rows[$c]["duracionPractica"]."</td>";
			$renglones .= "</tr>";
			$renglones .= "</tbody>";
			$respuesta = true;
		}
		$salidaJSON = array('respuesta' => $respuesta, 'renglones' => $renglones);
		print json_encode($salidaJSON);
	}
	function practicasCanceladas()
	{
		$respuesta 	= false;
		session_start();
		$responsable= $_SESSION['nombre'];
		$claveLab 	= GetSQLValueString(obtieneCveLab($responsable),"text"); 
		$tipoUsu 	= tipoUsuario($responsable);
		$prestamo	= "";
		$con 		= 0;
		$rows		= array();
		$renglones	= "";
		$xfecha 	= "";
		$xfecha2 	= "";
		$conexion 	= conectaBDSICLAB();
		if($tipoUsu == 5)
		{
			$labs 		= arrayLabs(jefeDepto(claveMaestro($responsable)));
			$consulta	= sprintf("Select sl.claveSolicitud, sl.fechaSolicitud, sl.horaSolicitud, p.tituloPractica, p.duracionPractica 
				from lbsolicitudlaboratorios sl 
				INNER JOIN lbpracticas p
				on p.clavePractica=sl.clavePractica
				where sl.claveLaboratorio in(%s)
				and sl.estatus='B'",$labs);
		}
		else
		{
			$consulta	= sprintf("Select sl.claveSolicitud, sl.fechaSolicitud, sl.horaSolicitud, p.tituloPractica, p.duracionPractica 
				from lbsolicitudlaboratorios sl 
				INNER JOIN lbpracticas p
				on p.clavePractica=sl.clavePractica
				where sl.claveLaboratorio=%s
				and sl.estatus='B'",$claveLab);
		}
		$res 		= mysql_query($consulta);

		$renglones	.= "<thead>";
		$renglones	.= "<tr>";
		$renglones	.= "<th data-field='claveSolicitud'>Clave</th>";
		$renglones	.= "<th data-field='fechaSolicitud'>Fecha</th>";
		$renglones	.= "<th data-field='horaSolicitud'>Hora</th>";
		$renglones	.= "<th data-field='tituloPractica'>Práctica</th>";
		$renglones	.= "<th data-field='duracionPractica'>Duración</th>";
		$renglones	.= "</tr>";
		$renglones	.= "</thead>";
		while($row = mysql_fetch_array($res))
		{
			$rows[]=$row;
			$respuesta = true;
			$con++;
		}
		
		for($c= 0; $c< $con; $c++)
		{
			$renglones .= "<tbody>";
			$renglones .= "<tr>";
			$renglones .= "<td>".$rows[$c]["claveSolicitud"]."</td>";
			$renglones .= "<td>".$rows[$c]["fechaSolicitud"]."</td>";
			$renglones .= "<td>".$rows[$c]["horaSolicitud"]."</td>";
			$renglones .= "<td>".$rows[$c]["tituloPractica"]."</td>";
			$renglones .= "<td>".$rows[$c]["duracionPractica"]."</td>";
			$renglones .= "</tr>";
			$renglones .= "</tbody>";
			$respuesta = true;
		}
		$salidaJSON = array('respuesta' => $respuesta, 'renglones' => $renglones);
		print json_encode($salidaJSON);
	}
//AGREGUE
	function practicasNR()
	{
		date_default_timezone_set("America/Mazatlan");
		$respuesta 	= false;
		session_start();
		$responsable	= $_SESSION['nombre'];
		$labUsuario	= "";
		$claveLab 	= GetSQLValueString(obtieneCveLab($responsable),"text"); 
		$tipoUsu 	= tipoUsuario($responsable);
		$rows		= array();
		$renglones	= "";
		$fechaActual= date("'Y-m-d'");
		$conexion 	= conectaBDSICLAB();
		if($tipoUsu == 5)
		{
			$labs 		= arrayLabs(jefeDepto(claveMaestro($responsable)));
			$consulta 	= sprintf("select count(claveCalendarizacion) as cantidad 
				from lbcalendarizaciones c 
				INNER JOIN lbsolicitudlaboratorios sl 
				ON c.claveSolicitud=sl.claveSolicitud 
				where c.estatus= 'NR' and sl.claveLaboratorio in(%s) and c.fechaAsignada>=%s",$labs,$fechaActual);
		}
		else
		{
			$consulta 	= sprintf("select count(claveCalendarizacion) as cantidad 
				from lbcalendarizaciones c 
				INNER JOIN lbsolicitudlaboratorios sl 
				ON c.claveSolicitud=sl.claveSolicitud 
				where c.estatus= 'NR' and sl.claveLaboratorio=%s and c.fechaAsignada>=%s",$claveLab,$fechaActual);
		}
		$res = mysql_query($consulta);
		if (mysql_num_rows($res)) //Si se encontraron datos en la búsqueda 
		{ 
			$rows = mysql_fetch_array($res); 
			$renglones .= "<p>Prácticas por realizar:</p>";
			$renglones .= "<p style= font-size:16px;>".$rows["cantidad"]." Prácticas</p>";
			$renglones .= "<p style= font-size:16px;><br></p>";
			$respuesta  = true; 
		} 
		$arrayJSON = array('respuesta' => $respuesta,
			'renglones' => $renglones);
		print json_encode($arrayJSON);
	}
	function articulosBajoInventario()
	{
		$respuesta 	= false;
		session_start();
		$responsable= $_SESSION['nombre'];
		$claveLab 	= GetSQLValueString(obtieneCveLab($responsable),"text"); 
		$tipoUsu 	= tipoUsuario($responsable);
		$rows		= array();
		$con=0;
		$renglones	= "";
		$conexion 	= conectaBDSICLAB();
		if($tipoUsu == 5)
		{
			$labs 		= arrayLabs(jefeDepto(claveMaestro($responsable)));
			$consulta	= sprintf("select ac.nombreArticulo as Nombre, count(a.claveArticulo) AS Cantidad from lbarticulos a inner join lbarticuloscat ac on a.claveArticulo=ac.claveArticulo
				inner join lbasignaarticulos aa on aa.identificadorArticulo=a.identificadorArticulo
				where aa.claveLaboratorio in(%s) group by a.claveArticulo having count(a.claveArticulo)<=5",$labs);
		}
		else
		{
			$consulta	= sprintf("select ac.nombreArticulo as Nombre, count(a.claveArticulo) AS Cantidad from lbarticulos a inner join lbarticuloscat ac on a.claveArticulo=ac.claveArticulo
				inner join lbasignaarticulos aa on aa.identificadorArticulo=a.identificadorArticulo
				where aa.claveLaboratorio=%s group by a.claveArticulo having count(a.claveArticulo)<=5",$claveLab);
		}
		$res 		= mysql_query($consulta);
		$renglones	.= "<thead>";
		$renglones	.= "<tr>";
		$renglones	.= "<th data-field='nombre'>Nombre del artículo</th>";
		$renglones	.= "<th data-field='cantidad'>Cantidad</th>";
		$renglones	.= "</tr>";
		$renglones	.= "</thead>";
		while($row = mysql_fetch_array($res))
		{
			$rows[]=$row;
			$respuesta = true;
			$con++;
		}
		
		for($c= 0; $c< $con; $c++)
		{
			$renglones .= "<tbody>";
			$renglones .= "<tr>";
			$renglones .= "<td>".$rows[$c]["Nombre"]."</td>";
			$renglones .= "<td>".$rows[$c]["Cantidad"]."</td>";
			$renglones .= "</tr>";
			$renglones .= "</tbody>";
			$respuesta = true;
		}
		$salidaJSON = array('respuesta' => $respuesta, 
			'renglones' => $renglones);
		print json_encode($salidaJSON);
	}
	function llenarComboMaterias()
	{
		session_start();
		$respuesta 		= false;
		$periodo 		= periodoActual();
		$usuario 		= $_SESSION["nombre"];
		$con 			= 0;
		$combomat 		= array();
		$claveMat 		= "";
		$nombreMat		= "";
		$claveLab 		= "";
		$nombreLab 		= "";
		$conexion		= conectaBDSIE();
		$consulta		= sprintf("select DISTINCT(m.MATCVE), m.MATNCO from DMATER m inner join DGRUPO g on m.MATCVE = g.MATCVE where g.PDOCVE = %s and g.GRUBAS = ' ' and g.INSNUM > 0 order by m.MATNCO",$periodo);
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
		$conexion 		= conectaBDSICLAB();
		$consulta		= sprintf("select l.claveLaboratorio, l.nombreLaboratorio from lbresponsables r inner join lblaboratorios l on r.claveLaboratorio = l.claveLaboratorio where r.claveUsuario = %d",$usuario);
		$res 			= mysql_query($consulta);
		if($res)
		{
			while($row = mysql_fetch_array($res))
			{
				$claveLab 	= $row["claveLaboratorio"];
				$nombreLab 	= $row["nombreLaboratorio"];
			}
		}
		$conexion 		= conectaBDSICLAB();
		$consulta		= sprintf("select DISTINCT(m.MATCVE), m.MATNCO from DMATER m inner join DGRUPO g on m.MATCVE = g.MATCVE where g.PDOCVE = %s and g.GRUBAS = ' ' and g.INSNUM > 0 order by m.MATNCO",$periodo);
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
			'claveLab'	=> $claveLab,
			'nombreLab' => $nombreLab);
		print json_encode($arrayJSON);
	}
	function nuevaPractGn()
	{
	//insertar en lbsolicitudes
		$respuesta		= false;
		$practica 		= 0;
		$respuesta2 	= false;
		$titulo 		= GetSQLValueString($_POST['titulo'],"text");
		$materia 		= GetSQLValueString($_POST['materia'],"text");
		$laboratorio 	= GetSQLValueString($_POST['laboratorio'],"text");
		$duracion 		= "'0".($_POST['duracion']).":00'";
		$descripcion 	= GetSQLValueString($_POST['descripcion'],"text");
		$conexion 	= conectaBDSICLAB();
		$consulta 	= sprintf("insert into lbpracticas values(' ',%s,%s,%s,'I','V')",$titulo,$descripcion,$duracion);
		$res 		= mysql_query($consulta);
		if(mysql_affected_rows()>0)
		{
			$practica 	= clavePractica($titulo,$duracion);
			$respuesta2 = asignaPractica($practica,$materia,$laboratorio);
			if($respuesta2==true)
			{
				$respuesta = true;
			} 
		}
		$arrayJSON = array('respuesta' => $respuesta);
		print json_encode($arrayJSON);
	}
	function catalagoPracticasG()
	{
		session_start();
		$responsable= $_SESSION['nombre'];
		$claveLab 	= GetSQLValueString(obtieneCveLab($responsable),"text"); 
		$respuesta 	= false;
		$nomMaterias ="";
	$mat 		= ""; //aqui guardaremos las materias que tiene ese maestro
	$con 		= 0;
	$rows 		= array();
	$renglones	= "";
	$conexion 	= conectaBDSICLAB();
	$consulta	= sprintf("select p.tituloPractica, p.descripcion, p.duracionPractica, ap.MATCVE, l.claveLaboratorio, l.nombreLaboratorio 
		from lbpracticas p 
		inner join lbasignapracticas ap on p.clavePractica = ap.clavePractica 
		inner join lblaboratorios l on ap.claveLaboratorio = l.claveLaboratorio 
		where ap.claveLaboratorio=%s order by p.tituloPractica",$claveLab);
	$res 		= mysql_query($consulta);
	$renglones	.= "<thead>";
	$renglones	.= "<tr>";
	$renglones	.= "<th data-field='nomPractica'>Nombre de la práctica</th>";
	$renglones	.= "<th data-field='descripcion'>Descripción</th>";
	$renglones	.= "<th data-field='duracion'>Duración</th>";
	$renglones  .= "<th data-field='materia'>Materia</th>";
	$renglones	.= "<th data-field='laboratorio'>Laboratorio</th>";
	$renglones	.= "</tr>";
	$renglones	.= "</thead>";
	while($row = mysql_fetch_array($res))
	{
		$mat 		.= "'".($row["MATCVE"])."',";
		$rows[]		= $row;
		$respuesta 	= true;
		$con++;
	}
	$mat 			= (rtrim($mat,","));
	$nomMaterias 	= nomMat($mat);   
	for($c= 0; $c< $con; $c++)
	{
		$renglones .= "<tbody>";
		$renglones .= "<tr>";
		$renglones .= "<td>".$rows[$c]['tituloPractica']."</td>";
		$renglones .= "<td>".$rows[$c]['descripcion']."</td>";
		$renglones .= "<td>".$rows[$c]['duracionPractica']."</td>";
		$renglones .= "<td>".$nomMaterias[$rows[$c]["MATCVE"]]."</td>";
		$renglones .= "<td>".$rows[$c]['nombreLaboratorio']."</td>";
		$renglones .= "</tr>";
		$renglones .= "</tbody>";
		$respuesta = true;
	}
	$arrayJSON = array('respuesta' => $respuesta,
		'renglones' => $renglones);
	print json_encode($arrayJSON);	
}
function llenarComboPracticas()
{
	session_start();
	$responsable= $_SESSION['nombre'];
	$claveLab 	= GetSQLValueString(obtieneCveLab($responsable),"text"); 
	$nomMaterias ="";
	$mats 		= "";
	$mat 		= ""; //aqui guardaremos las materias que tiene ese maestro
	$con 		= 0;
	$rows 		= array();
	$materias 	= clavematerias($maestro);
	$conexion 	= conectaBDSICLAB();
	$consulta	= sprintf("select p.tituloPractica, p.descripcion, p.duracionPractica, ap.MATCVE, l.claveLaboratorio, l.nombreLaboratorio 
		from lbpracticas p 
		inner join lbasignapracticas ap on p.clavePractica = ap.clavePractica 
		inner join lblaboratorios l on ap.claveLaboratorio = l.claveLaboratorio 
		where ap.claveLaboratorio=%s order by p.tituloPractica",$claveLab);
	$res 		= mysql_query($consulta);
	while($row = mysql_fetch_array($res))
	{
		$mat 		.= "'".($row["MATCVE"])."',";
		$rows[]		= $row;
		$mats 		= "'".$row["MATCVE"]."',";
		$respuesta 	= true;
		$con++;
	}
	$mat 			= (rtrim($mat,","));
	
	$mats 			= (rtrim($mats,","));
	$datosMaterias 	= nomMaterias($mats);

	$nomMaterias 	= nomMat($mat);   
	$arrayJSON = array('respuesta' => $respuesta,
		'materias'  => $datosMaterias["materias"],
		'matsnoms'	=> $datosMaterias["nomMaterias"]);
	print json_encode($arrayJSON);	
}
function catPracticasFiltroG()
{
	session_start();
	$opcion 	= $_POST["opcion"];
	$valor 		= $_POST["valor"];
	if ($opcion == "p.tituloPractica") 
	{
		$valor = "%".$valor."%";
	}

	$respuesta 	= false;
	$mat 		= ""; //aqui guardaremos las materias que tiene ese maestro
	$con 		= 0;
	$rows 		= array();
	$renglones	= "";
	$conexion 	= conectaBDSICLAB();
	$consulta	= sprintf("select p.tituloPractica, p.descripcion, p.duracionPractica, ap.MATCVE, l.claveLaboratorio, l.nombreLaboratorio from lbpracticas p inner join lbasignapracticas ap on p.clavePractica = ap.clavePractica inner join lblaboratorios l on ap.claveLaboratorio = l.claveLaboratorio where %s LIKE '%s' order by p.tituloPractica",$opcion,$valor);
	$res 		= mysql_query($consulta);
	$renglones	.= "<thead>";
	$renglones	.= "<tr>";
	$renglones	.= "<th data-field='nomPractica'>Nombre de la práctica</th>";
	$renglones	.= "<th data-field='descripcion'>Descripción</th>";
	$renglones	.= "<th data-field='duracion'>Duración</th>";
	$renglones  .= "<th data-field='materia'>Materia</th>";
	$renglones	.= "<th data-field='laboratorio'>Laboratorio</th>";
	$renglones	.= "</tr>";
	$renglones	.= "</thead>";
	while($row = mysql_fetch_array($res))
	{
		$mat 		.= "'".($row["MATCVE"])."',";
		$rows[]		= $row;
		$respuesta 	= true;
		$con++;
	}
	$mat 			= (rtrim($mat,","));
	$nomMaterias 	= nomMat($mat);   
	for($c= 0; $c< $con; $c++)
	{
		$renglones .= "<tbody>";
		$renglones .= "<tr>";
		$renglones .= "<td>".$rows[$c]['tituloPractica']."</td>";
		$renglones .= "<td>".$rows[$c]['descripcion']."</td>";
		$renglones .= "<td>".$rows[$c]['duracionPractica']."</td>";
		$renglones .= "<td>".$nomMaterias[$rows[$c]["MATCVE"]]."</td>";
		$renglones .= "<td>".$rows[$c]['nombreLaboratorio']."</td>";
		$renglones .= "</tr>";
		$renglones .= "</tbody>";
		$respuesta = true;
	}
	$arrayJSON = array('respuesta' => $respuesta,
		'renglones' => $renglones);

	print json_encode($arrayJSON);
}
function asignaPractica($p,$m,$l)
{
	//insertar en lbasignapracticas
	$practica 		= $p;
	$materia 		= $m;
	$laboratorio 	= $l;
	$respuesta		= false;
	$conexion  	= conectaBDSICLAB();
	$consulta 	= sprintf("insert into lbasignapracticas values(%d,%s,%s)",$practica,$materia,$laboratorio);
	$res 		= mysql_query($consulta);
	if(mysql_affected_rows()>0)
	{
		$respuesta = true;
	}
	return $respuesta;
}
//FIN AGREGUE
function datosGrafica()
{
	session_start();
	$usuario  = $_SESSION["nombre"];
	$claveLab = GetSQLValueString(obtieneCveLab($usuario),"text");
	$nombreLab = nombreLab($claveLab);
	$nombrePdo = periodoNombre();
	$respuesta = true;
	$FI       = "'2016-01-28'";
	$FF       = "'2016-06-10'";
	$datos    = array();
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
	
	$consultaEnero = sprintf("select count(month(fechaEntrada))as CANTIDAD from lbentradasalumnos e
		WHERE month(e.fechaEntrada)=1
		AND year(e.fechaEntrada)=year(NOW())
		and e.claveLaboratorio=%s
		and e.fechaEntrada between %s and %s
		group by month(e.fechaEntrada)",$claveLab,$FI,$FF);
	$resEnero = mysql_query($consultaEnero);
	$rowE = mysql_fetch_array($resEnero);
	if($rowE["CANTIDAD"]!=NULL)
		$enero[]=$rowE["CANTIDAD"];
	else
		$enero[]=0;

	$consultaFebrero = sprintf("select count(month(fechaEntrada))as CANTIDAD from lbentradasalumnos e
		WHERE month(e.fechaEntrada)=2
		AND year(e.fechaEntrada)=year(NOW())
		and e.claveLaboratorio=%s
		and e.fechaEntrada between %s and %s
		group by month(e.fechaEntrada)",$claveLab,$FI,$FF);
	$resFeb = mysql_query($consultaFebrero);
	$rowF = mysql_fetch_array($resFeb);
	if($rowF["CANTIDAD"]!=NULL)
		$febrero[]=$rowF["CANTIDAD"];
	else
		$febrero[]=0;
	$consultaMarzo = sprintf("select count(month(fechaEntrada))as CANTIDAD from lbentradasalumnos e
		WHERE month(e.fechaEntrada)=3
		AND year(e.fechaEntrada)=year(NOW())
		and e.claveLaboratorio=%s
		and e.fechaEntrada between %s and %s
		group by month(e.fechaEntrada)",$claveLab,$FI,$FF);
	$resMarzo = mysql_query($consultaMarzo);
	$rowM = mysql_fetch_array($resMarzo);
	if($rowM["CANTIDAD"]!=NULL)
		$marzo[]=$rowM["CANTIDAD"];
	else
		$marzo[]=0;

	$consultaAbril = sprintf("select count(month(fechaEntrada))as CANTIDAD from lbentradasalumnos e
		WHERE month(e.fechaEntrada)=4
		AND year(e.fechaEntrada)=year(NOW())
		and e.claveLaboratorio=%s
		and e.fechaEntrada between %s and %s
		group by month(e.fechaEntrada)",$claveLab,$FI,$FF);
	$resAbril = mysql_query($consultaAbril);
	$rowA = mysql_fetch_array($resAbril);
	if($rowA["CANTIDAD"]!=NULL)
		$abril[]=$rowA["CANTIDAD"];
	else
		$abril[]=0;

	$consultaMayo = sprintf("select count(month(fechaEntrada))as CANTIDAD from lbentradasalumnos e
		WHERE month(e.fechaEntrada)=5
		AND year(e.fechaEntrada)=year(NOW())
		and e.claveLaboratorio=%s
		and e.fechaEntrada between %s and %s
		group by month(e.fechaEntrada)",$claveLab,$FI,$FF);
	$resMayo = mysql_query($consultaMayo);
	$rowMayo = mysql_fetch_array($resMayo);
	if($rowMayo["CANTIDAD"]!=NULL)
		$mayo[]=$rowMayo["CANTIDAD"];
	else
		$mayo[]=0;

	$consultaJunio = sprintf("select count(month(fechaEntrada))as CANTIDAD from lbentradasalumnos e
		WHERE month(e.fechaEntrada)=6
		AND year(e.fechaEntrada)=year(NOW())
		and e.claveLaboratorio=%s
		and e.fechaEntrada between %s and %s
		group by month(e.fechaEntrada)",$claveLab,$FI,$FF);
	$resJunio = mysql_query($consultaJunio);
	$rowJun = mysql_fetch_array($resJunio);
	if($rowJun["CANTIDAD"]!=NULL)
		$junio[]=$rowJun["CANTIDAD"];
	else
		$junio[]=0;

	$consultaJulio = sprintf("select count(month(fechaEntrada))as CANTIDAD from lbentradasalumnos e
		WHERE month(e.fechaEntrada)=7
		AND year(e.fechaEntrada)=year(NOW())
		and e.claveLaboratorio=%s
		and e.fechaEntrada between %s and %s
		group by month(e.fechaEntrada)",$claveLab,$FI,$FF);
	$resJulio = mysql_query($consultaJulio);
	$rowJul = mysql_fetch_array($resJulio);
	if($rowJul["CANTIDAD"]!=NULL)
		$julio[]=$rowJul["CANTIDAD"];
	else
		$julio[]=0;

	$consultaAgosto = sprintf("select count(month(fechaEntrada))as CANTIDAD from lbentradasalumnos e
		WHERE month(e.fechaEntrada)=8
		AND year(e.fechaEntrada)=year(NOW())
		and e.claveLaboratorio=%s
		and e.fechaEntrada between %s and %s
		group by month(e.fechaEntrada)",$claveLab,$FI,$FF);
	$resAgosto = mysql_query($consultaAgosto);
	$rowAg = mysql_fetch_array($resAgosto);
	if($rowAg["CANTIDAD"]!=NULL)
		$agosto[]=$rowAg["CANTIDAD"];
	else
		$agosto[]=0;

	$consultaSeptiembre = sprintf("select count(month(fechaEntrada))as CANTIDAD from lbentradasalumnos e
		WHERE month(e.fechaEntrada)=9
		AND year(e.fechaEntrada)=year(NOW())
		and e.claveLaboratorio=%s
		and e.fechaEntrada between %s and %s
		group by month(e.fechaEntrada)",$claveLab,$FI,$FF);
	$resSeptiembre = mysql_query($consultaSeptiembre);
	$rowS = mysql_fetch_array($resSeptiembre);
	if($rowS["CANTIDAD"]!=NULL)
		$septiembre[]=$rowS["CANTIDAD"];
	else
		$septiembre[]=0;

	$consultaOctubre = sprintf("select count(month(fechaEntrada))as CANTIDAD from lbentradasalumnos e
		WHERE month(e.fechaEntrada)=10
		AND year(e.fechaEntrada)=year(NOW())
		and e.claveLaboratorio=%s
		and e.fechaEntrada between %s and %s
		group by month(e.fechaEntrada)",$claveLab,$FI,$FF);
	$resOct = mysql_query($consultaOctubre);
	$rowO = mysql_fetch_array($resOct);
	if($rowO["CANTIDAD"]!=NULL)
		$octubre[]=$rowO["CANTIDAD"];
	else
		$octubre[]=0;

	$consultaNoviembre = sprintf("select count(month(fechaEntrada))as CANTIDAD from lbentradasalumnos e
		WHERE month(e.fechaEntrada)=11
		AND year(e.fechaEntrada)=year(NOW())
		and e.claveLaboratorio=%s
		and e.fechaEntrada between %s and %s
		group by month(e.fechaEntrada)",$claveLab,$FI,$FF);
	$resNov = mysql_query($consultaNoviembre);
	$rowN = mysql_fetch_array($resNov);
	if($rowN["CANTIDAD"]!=NULL)
		$noviembre[]=$rowN["CANTIDAD"];
	else
		$noviembre[]=0;

	$consultaDiciembre = sprintf("select count(month(fechaEntrada))as CANTIDAD from lbentradasalumnos e
		WHERE month(e.fechaEntrada)=12
		AND year(e.fechaEntrada)=year(NOW())
		and e.claveLaboratorio=%s
		and e.fechaEntrada between %s and %s
		group by month(e.fechaEntrada)",$claveLab,$FI,$FF);
	$resD = mysql_query($consultaDiciembre);
	$rowD = mysql_fetch_array($resD);
	if($rowD["CANTIDAD"]!=NULL)
		$diciembre[]=$rowD["CANTIDAD"];
	else
		$diciembre[]=0;

	$datos = [$categorias,$enero,$febrero,$marzo,$abril,$mayo,$junio,$julio,$agosto,$septiembre,$octubre,
						$noviembre,$diciembre];
	$arrayJSON = array('respuesta' => $respuesta, 
						'datos' => $datos,
						'nombreLab' => $nombreLab,
						'nombrePdo' => $nombrePdo);

	print json_encode($arrayJSON);
}
//Menú principal
$opc = $_POST["opc"];
switch ($opc){
	case 'altaInventario1':
	altaInventario1();
	break;
	case 'listaArticulos1':
	listaArticulos();
	break;
	case 'usuario1':
	usuario();
	break;
	case 'bajaArticulos1':
	bajaArticulos();
	break;
	case 'buscaArticulos1':
	buscaArticulo();
	break;
	case 'listaMantenimiento1':
	listaMantenimiento();
	break;
	case 'mantenimientoArticulos1':
	mantenimientoArticulos();
	break;
	case 'buscaArticulos2':
	buscaArticuloMtto();
	break;
	case 'peticionesPendientesArt1':
	peticionesPendientesArt();
	break;
	case 'pendientesLab1':
	pendientesLaboratorio();
	break;
	case 'aceptadasLab1':
	aceptadasLaboratorio();
	break;
	case 'verMasLab1':
	verMas();
	break;
	case 'verMasLab2':
	verMas2();
	break;
	case 'obtenerDatosSolLab1':
	obtenerDatosSolLab();
	break;
	case 'guardaSolicitudLab1':
	guardaSolicitudLab();
	break;
	case 'eliminaSolicitudLab1':
	eliminaSolicitudLab();
	break;
	case 'prestamosPendientes1':
	prestamosPendientes();
	break;
	case 'atenderPrestamo1':
	atenderPrestamo();
	break;
	case 'agregaArticulos1':
	agregaArticulos();
	break;
	case 'guardaPrestamoPendiente1':
	guardaPrestamoPendiente();
	break;
	case 'eliminaPrestamoPendiente1':
	eliminaPrestamoPendiente();
	break;
	case 'aceptaPeticionArticulos1':
	aceptaPeticionArticulos();
	break;
	case 'prestamosProceso1':
	prestamosProceso();
	break;
	case 'devolucionPrestamo1':
	devolucionPrestamo();
	break;
	case 'guardaDevolucion1':
	guardaDevolucion();
	break;
	case 'aplicaSancion1':
	aplicaSancion();
	break;
	case 'guardaSancion1':
	guardaSancion();
	break;
	case 'listaSanciones1':
	listaAlumnosSancionados();
	break;
	case 'quitaSanciones1':
	quitaSanciones();
	break;
	case 'salir1':
	salirG();
	break;
	case 'guardaPeticionArticulos1':
	guardaPeticionArticulos();
	break;
	case 'listaArtAlta':
	listaArticulosAlta();
	break;
	case 'comboArtPeticiones1':
	comboArtPeticiones();
	break;
	case 'regresaMtto1':
	regresaMantenimiento();
	break;
	case 'buscaArtLista1':
	buscaArtLista();
	break;
	case 'listaDependencias1':
	listaDependencias();
	break;
	case 'llenarcomboLabExt1':
	llenarcomboLabExt();
	break;
	case 'llenarcomboHrLabExt1':
	llenarcomboHrLabExt();
	break;
	case 'construirTbArtExt1':
	construirTbArtExt();
	break;
	case 'comboEleArtExt1':
	comboEleArtExt();
	break;	
	case 'guardaSolLabExterno1':
	guardaSolLabExterno();
	break;
	case 'llenarComboPracticas1':
	llenarComboPracticas();
	break;
	case 'catalagoPracticasG1':
	catalagoPracticasG();
	break;
	case 'nuevaPractGn1':
	nuevaPractGn();
	break;
	case 'catPracticasFiltroG1':
	catPracticasFiltroG();
	break;
	case 'llenarComboMaterias1':
	llenarComboMaterias();
	break;
	//AGREGUE
	case 'alumnosActuales1':
	alumnosActuales();
	break;
	case 'articulosSinExistencia1':
	articulosSinExistencia();
	break;
	case 'articuloMasPrestado1':
	articuloMasPrestado();
	break;
	case 'proximosApartados1':
	proximosApartados();
	break;
	case 'resumenInventarioActual1':
	resumenInventarioActual();
	break;
	case 'enReparacion1':
	enReparacion();
	break;
	case 'enBaja1':
	enBaja();
	break;
	case 'enPrestamo1':
	enPrestamo();
	break;
	case 'enPedido1':
	enPedido();
	break;
	case 'practicasNoRealizadas1':
	practicasNoRealizadas();
	break;
	case 'practicasRealizadas1':
	practicasRealizadas();
	break;
	case 'practicasCanceladas1':
	practicasCanceladas();
	break;
	//AGREGUE
	case 'practicasNR1':
	practicasNR();
	break;
	case 'articulosBajoInventario1':
	articulosBajoInventario();
	break;
	//FIN AGREGUE
	case 'datosGrafica1':
		datosGrafica();
		break;
} 
?>