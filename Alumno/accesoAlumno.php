<!DOCTYPE html>
<html lang="es">
<head>
	<link rel="stylesheet" href="../css/alumnos.css">
	<script src="../js/alumnos.js"></script>
</head>
<body>
	<div  class="body2">
		<div id="accesoAlumno">
			<form >
				<div class="row">
					<div class="col s2">
						<input placeholder="" id="txtFechaAcceso" type="text" class="validate" disabled>
					</div>
					<div class="col s1 offset-s8">
						<a class="waves-effect waves-light btn red darken-1" id="btnSalirLaboratorio">Salir</a>
					</div>
					<div class="row">
						<div class="col s3 offset-s9">
							<h6>Entrada:</h6>
							<form action="#">
								<div class="col s6">
									<input name="grpRdoEntrada" type="radio" id="rdoAlumno" checked/>
									<label for="rdoAlumno">Alumno</label>
								</div>
								<div class="col s6">
									<input name="grpRdoEntrada" type="radio" id="rdoExterno" />
									<label for="rdoExterno">Externo</label>
								</div>
							</form>
						</div>
					</div>
					<div class="col s2">
						<p>
							<img class="materialboxed col s12" src="..\img\persona.png">
						</p>
					</div>
					<section class="col s5">      
						<div class="row">
							<div class="input-field col s12">
								<input placeholder="" maxlength="8" id="txtNControl" type="text" class="validate" autofocus> 
								<label active for="txtNControl">Número de control</label>
							</div>
						</div>
						<div class="row">
							<div class="input-field col s12">
								<input placeholder="" id="txtNombre" type="text" class="validate" disabled>
								<label for="txtNombre">Nombre</label>
							</div>
						</div>
						<div class="row">
							<div class="input-field col s12">
								<input placeholder="" id="txtCarrera" type="text" class="validate" disabled>
								<label for="txtCarrera">Carrera o dependencia</label>
							</div>
						</div>
						<div class="row">
							<div class="input-field col s12">
								<input placeholder="" id="txtSemestre" type="number" class="validate" disabled>
								<label for="txtSemestre">Semestre</label>
							</div>
						</div>
					</section>
					<div class="col s5" id="tecladoNumerico">
						<div class="row filaNumeros">
							<p>
								<a class="waves-effect waves-light btn-large light-blue darken-1 col s2 offset-s1" id="btn7">7</a>
							</p>
							<p>
								<a class="waves-effect waves-light btn-large light-blue darken-1 col s2 offset-s2" id="btn8">8</a>
							</p>
							<p>
								<a class="waves-effect waves-light btn-large light-blue darken-1 col s2 offset-s2" id="btn9">9</a>
							</p>
						</div>
						<div class="row filaNumeros">
							<p>
								<a class="waves-effect waves-light btn-large light-blue darken-1 col s2 offset-s1" id="btn4">4</a>
							</p>
							<p>
								<a class="waves-effect waves-light btn-large light-blue darken-1 col s2 offset-s2" id="btn5">5</a>
							</p>
							<p>
								<a class="waves-effect waves-light btn-large light-blue darken-1 col s2 offset-s2" id="btn6">6</a>
							</p>
						</div>
						<div class="row filaNumeros">
							<p>
								<a class="waves-effect waves-light btn-large light-blue darken-1 col s2 offset-s1" id="btn1">1</a>
							</p>
							<p>
								<a class="waves-effect waves-light btn-large light-blue darken-1 col s2 offset-s2" id="btn2">2</a>
							</p>
							<p>
								<a class="waves-effect waves-light btn-large light-blue darken-1 col s2 offset-s2" id="btn3">3</a>
							</p>
						</div>
						<div class="row filaNumeros">
							<p>
								<a class="waves-effect waves-light btn-large blue darken-3 col s2 offset-s1" id="btnDel">DEL</a>
							</p>
							<p>
								<a class="waves-effect waves-light btn-large light-blue darken-1 col s2 offset-s2" id="btn0">0</a>
							</p>
							<p>
								<a class="waves-effect waves-light btn-large blue darken-3 col s2 offset-s2" id="btnMA">MA</a>
							</p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col s6 offset-s3">
						<a class="btn waves-effect waves-light blue darken-3" type="submit" name="action" id="btnPracticaAlumnos">Datos práctica
						</a>
						<a class="waves-effect waves-light btn red darken-1" id="btnCancelarEntrada">Cancelar</a>
					</div>
				</div>
			</form>
		<div id="loaderImageA">
			<img src="../img/159.gif" alt="">
		</div>
		</div>
		<div id="materialExterno">
			<?php include 'eleccionMaterialE.php';?>
		</div>
		<div id="datosPracticas">
			<?php include 'datosPractica.php';?>
		</div>
		
	</div>
</body>
</html>