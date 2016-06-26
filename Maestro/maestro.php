<!DOCTYPE html>
<html lang="es">
<head>
	<link rel="stylesheet" href="../css/maestro.css">
	<script src="../js/maestros.js"></script>
</head>
<body>
	<div id="menuMaestro" class="body2">
		<div class="row">
			<div class="col s12" id="tabsMaestro">
				<ul class="tabs">
					<li id= "iniciotab" class="tab col s2 active"><a href="#inicioma">Inicio</a></li>
					<li id= "solicitudestab" class="tab col s2"><a id="soliAceptadas" href="#solicitudes">Solicitudes</a></li>
					<li id="practicasMatab" class="tab col s2"><a href="#practicasMa">Prácticas</a></li>
					<li id="reportestab" class="tab col s2"><a href="#reportes">Reportes</a></li>
					<li id= "salirTab" class="tab col s3"><a href="#salir">Salir</a></li>
				</ul>
			</div>
		</div>
		<div id="inicioma">
			<?php include 'inicioMae.php';?>
		</div>
		<div id="solicitudes" class="col s12">
			<div id="menuSolicitudesMaestro">
				<ul>
					<button class="waves-effect waves-light btn grey" id="btnSolicitudesAceptadas">Aceptadas</button>
					<button class="waves-effect waves-light btn blue darken-1" id="btnSolicitudesPendientes">Pendientes</button>
					<button class="waves-effect waves-light btn blue darken-1" id="btnSolicitudesRealizadas">Realizadas</button>
					<button class="waves-effect waves-light btn blue darken-2" id="btnNuevaSolicitud">Nueva</button>
				</ul>
			</div>
			<div id="sAceptadasMaestro">
				<?php include 'sAceptadasMaestro.php';?>
			</div>
			<div id="sPendientesMaestro">
				<?php include 'sPendientesMaestro.php';?>
			</div>
			<div id="sRealizadas">
				<?php include 'sRealizadasMaestro.php';?>
			</div>
			<div id="sNuevaMaestro">
				<?php include 'sNuevaMaestro.php';?>
			</div>
		</div>
		<div id="practicasMa">
			<div id="menuPracticasMaestro">
				<ul>
					<!-- <button class="waves-effect waves-light btn grey" id="btnCatalagoPracticas">Prácticas</button> -->
					<!-- <button class="waves-effect waves-light btn blue darken-2" id="btnNuevaPracticaMa">Nueva</button> -->
				</ul>
			</div>
			<div id="catalagoPracticas">
				<?php include 'catPracticas.php';?>	
			</div>
			<div id="NuevaPracticaM">
				<?php include 'nuevaPractica.php';?>
			</div>
		</div>
		<div id="reportes">
			<?php include 'seleccionListaAsistencia.php';?>
		</div>
		<div id="loaderImage">
			<img src="../img/159.gif" alt="">
		</div>
		<div id="salir">
			<?php include '../Genericos/salirSistema.php';?>
		</div>
	</div>
</body>
</html>