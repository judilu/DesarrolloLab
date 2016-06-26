<div id="NuevaPracticaGenericos">
	<div class="row" id="nuevaMaestroG">
		<h5 class="centrado">Nueva práctica</h5>
		<div class="col s12">
			<div class="row">
				<div class="input-field col s6">
					<input id= "txtTituloPracticaG" type="text" size="150" class="datepicker">
					<label for="txtTituloPracticaG">Titulo de la práctica</label>
				</div>
				<div class="input-field col s6">
					<select id="cmbMatPracticaG">

					</select>
					<label>Materia</label>
				</div>	
			</div>
			<div class="row">
				<div class="input-field col s6">
					<input disabled placeholder="" id= "txtLaboratorioPracticaG" type="text" size="150" class="datepicker">
					<label class="active" for="txtLaboratorioPracticaG">Laboratorio</label>
				</div>
				<div class="input-field col s2">
					<input id="txtDuracionPractG" type="number" min="1" max="6" value="1"class="validate">
					<label for="txtDuracionPractG">Duración (Horas)</label>
				</div>
				<div class="input-field col s4">
					<textarea id="textareaDesPracG" size="255" class="materialize-textarea"></textarea>
					<label for="textareaDesPracG">Descripción</label>
				</div>
			</div>
			<div class="row">
				<div class="col s5 offset-s7">
					<a class="waves-effect waves-light btn green darken-2 " id="btnFinalizarNPracticaG">Finalizar</a>
					<a class="waves-effect waves-light btn red darken-2" id="btnCancelarPracticaG">Cancelar</a>
				</div>
			</div>
		</div>
	</div>
</div>
