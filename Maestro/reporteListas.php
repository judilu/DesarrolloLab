<!DOCTYPE html>
<html lang="es">
<head>
	<meta  http-equiv="Content-type" content="text/html;charset='UTF-8'">
	<title>SICLAB</title>
	<link rel="stylesheet" href="../css/materialize/css/materialize.css">
	<link rel="stylesheet" href="../css/siclab.css">
	<link rel="stylesheet" href="../css/maestro.css">
	<link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="../sweetalert-master/dist/sweetalert.css">
	<script src="../js/jquery-2.2.0.js"></script>
	<script src="../js/materialize.min.js"></script>
	<script src="../sweetalert-master/dist/sweetalert.min.js"></script>
	<script src="../js/reportesMaestro.js"></script>
</head>
<body>
	<header>
		<div class="row col s12">
			<div class="col s2 cuadro fuente centrado">
				<img id="LogoTec" src="../img/logo.png" alt="">
			</div>
			<div class="col s6 bajo fuente">
				<div class="row">
					Nombre del documentos: Formato<br>
					Control de asistencia de práctica de
					laboratorio realizada
					Referencia a la norma ISO
				</div>
			</div>
			<div class="col s4 cuadro fuente">
				<div class="row">
					Código: XXX-XX-XX
				</div>
				<div class="row">
					Revisión:1 <br>
					Página 1 de X
				</div>
			</div>
		</div>
	</header>
	<div class="listaAsistencia">
		<div class="fuente centrado col s12">
			INSTITUTO TECNOLOGICO DE CULIACAN <br>
			<div class="input-field col s3 offset-s2">
				<input disabled placeholder="" id="txtDepto" type="text" class="centrado validate">
				<label class="active fuente"for="txtDepto">Departamento:</label>			
			</div>
			<div class="input-field col s5 offset-s2">
				<input disabled placeholder="" id="txtLaboratorioRp" type="text" class="centrado validate">
				<label class="active"for="txtLaboratorioRp">Laboratorio:</label>
			</div>
		</div>
		<div class="row">
			<div class="col s12">
				<div class="row fuente">
					<div class="input-field col s2">
						<input disabled placeholder="" id="txtClaveMaestroRp" type="text" class="validate">
						<label class="active"for="txtClaveMaestroRp">Clave</label>
					</div>
					<div class="input-field col s7">
						<input disabled placeholder="" id="txtNombreMaestroRp" type="text" class="validate">
						<label class="active" for="txtNombreMaestroRp">Nombre</label>
					</div>
					<div class="input-field col s3">
						<input disabled placeholder="" id="txtPeriodoRp" type="text" class="validate">
						<label class="active" for="txtPeriodoRp">Periodo</label>
					</div>
				</div>
				<div class="row fuente">
					<div class="input-field col s12">
						<input disabled placeholder="" id="txtCarreraRp" type="text" class="validate">
						<label class="active" for="txtCarreraRp">Carrera</label>
					</div>
				</div>
				<div class="row fuente">
					<div class="input-field col s10">
						<input disabled placeholder="" id="txtMateriaRp" type="text" class="validate">
						<label class="active" for="txtMateriaRp">Materia</label>
					</div>
					<div class="input-field col s2">
						<input disabled placeholder="" id="txtHoraMatRp" type="text" class="validate">
						<label class="active" for="txtHoraMatRp">Hora de la materia</label>
					</div>
				</div>
				<div class="row fuente">
					<div class="input-field col s7">
						<input disabled placeholder="" id="txtPracticaRp" type="text" class="validate">
						<label class="active" for="txtPracticaRp">Práctica</label>
					</div>
					<div class="input-field col s2">
						<input disabled placeholder="" id="txtHoraPractRp" type="text" class="validate">
						<label class="active" for="txtHoraPractRp">Hora de la práctica</label>
					</div>
					<div class="input-field col s3">
						<input disabled placeholder="" id= "txtFechaPracticaRp2" type="text" class="validate">
						<label class="active" for="txtFechaPracticaRp2">Fecha práctica</label>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col s10 offset-s1">
					<table  id ="tbListaAsistenciaRp" class="bordered responsive-table">
						
					</table>
				</div>
			</div>  
		</div>
		<div class="row">
			<div class="col s5 offset-s7">
				<a id="btnImprimir" class="waves-effect waves-light btn green darken-2 "><i class="material-icons left">print</i>Imprimir</a>
			</div>
		</div>
	</div>	
</body>
</html>