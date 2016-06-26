<div class="listaAsistencia">
	<div class="row" id="selecionarLista">
		<h5 class="centrado">Seleccionar lista de asistencia</h5>
		<div class="col s12">
			<div class="row">
				<div class="input-field col s2">
					<input disabled placeholder="" id="txtClaveMaestroRep" type="text" class="validate">
					<label class="active"for="txtClaveMaestroRep">Clave</label>
				</div>
				<div class="input-field col s7">
					<input disabled placeholder="" id="txtNombreMaestroRep" type="text" class="validate">
					<label for="txtNombreMaestroRep">Nombre</label>
				</div>
				<div class="input-field col s3">
					<select id="cmbPeriodoRep">

					</select>
					<label>Periodo</label>
				</div>
			</div>
			<div class="row">
				<div class="input-field col s9">
					<select id="cmbMateriaRep">

					</select>
					<label>Materia</label>
				</div>
				<div class="input-field col s3">
					<select id="cmbHoraMatRep">

					</select>
					<label>Hora de la materia</label>
				</div>
			</div>
			<div class="row">
				<div class="input-field col s7">
					<select id="cmbPracticaRep">
						
					</select>
					<label>Práctica</label>
				</div>
				<div class="input-field col s2">
					<select id="cmbHoraPracticaRep">

					</select>
					<label>Hora de la práctica</label>
				</div>
				<div class="input-field col s3">
					<input id= "txtFechaPracticaRep" placeholder= " " type="date" class="datepicker">
					<label class="active" for="txtFechaPracticaRep">Fecha práctica</label>
				</div>
			</div>
		</div>
		<div class="col s4 offset-s8">
			<a id="btnListaAsistencia" class="waves-effect waves-light btn amber darken-2 "><i class="material-icons left">view_list</i>Lista de asistencia</a>
		</div>  
	</div>
	<div class="row" id="lista">
		<?php include '..\Maestro\listaAsistencia.php';?>
	</div>
</div>