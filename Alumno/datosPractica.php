<div id="datosPractica2">
	<h5 class="centrado">Datos de la práctica</h5>
	<div class="row">
		<div class="col s12">
			<div class="row">
				<div class="input-field col s2">
					<input id="txtNControlAlu" placeholder="" type="text" class="validate" disabled>
					<label active for="txtNControlAlu">Número control</label>
				</div>
				<div class="input-field col s4">
					<input id="txtNombreAlu" placeholder="" type="text" class="validate" disabled>
					<label active for="txtNombreAlu">Nombre del alumno</label>
				</div>
			</div>
			<div class="row">
				<div class="input-field col s5">
					<select id="cmbMateriasAlumnos">
						<option value="" disabled selected>Selecciona la materia</option>
					</select>
					<label>Nombre de la materia</label>
				</div>
				<div class="input-field col s5 offset-s1">
					<select id="cmbMaestrosMat">
						<option value="" disabled selected>Selecciona el maestro</option>
					</select>
					<label>Nombre del maestro</label>
				</div>
			</div>
			<div class="row">
				<div class="input-field col s5">
					<select id="cmbHorariosPractica">
						<option value="" disabled selected>Selecciona la hora de la práctica</option>
					</select>
					<label>Hora de la práctica</label>
				</div>
				<div class="input-field col s5  offset-s1">
					<select id="cmbNombrePracticas">
						<option value="" disabled selected>Selecciona la práctica</option>
					</select>
					<label>Nombre de la práctica</label>
				</div>
			</div>
			<div class="row">
				<div class="input-field col s2">
					<input type="checkbox" id="chbElegirMaterial">
					<label for="chbElegirMaterial">Necesito material</label>
				</div>
				<div class="input-field col s5 offset-s1">
					<a class="btn waves-effect waves-light green darken-2" type="submit" name="action" id="btnEntradaAlumno">Entrar</a>
					<a class="waves-effect waves-light btn red darken-1" id="btnCancelarDatosPractica">Cancelar</a>
				</div>
			</div>
		</div>
	</div>  
</div>
<div id="materialAlumno">
	<?php include 'eleccionMaterialAlumno.php';?>
</div> 