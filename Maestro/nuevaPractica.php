<div id="solNuevaMaestro">
	<div class="row" id="nuevaMaestro">
		<h5 class="centrado">Nueva práctica</h5>
		<div class="col s12">
			<div class="row">
				<div class="input-field col s6">
					<input id= "txtTituloPractica" type="text" size="150" class="datepicker">
					<label for="txtTituloPractica">Titulo de la práctica</label>
				</div>
				<div class="input-field col s6">
					<select id="cmbMatPractica">

					</select>
					<label>Materia</label>
				</div>	
			</div>
			<div class="row">
				<div class="input-field col s6">
					<select id="cmbLabPractica">

					</select>
					<label>Laboratorio</label>
				</div>
				<div class="input-field col s2">
					<input id="txtDuracionPract" type="number" min="1" max="6" value="1"class="validate">
					<label for="txtDuracionPract">Duración (Horas)</label>
				</div>
				<div class="input-field col s4">
					<textarea id="textareaDesPrac" size="255" class="materialize-textarea"></textarea>
					<label for="textareaDesPrac">Descripción</label>
				</div>
			</div>
			<div class="row">
				<div class="col s5 offset-s7">
					<a class="waves-effect waves-light btn green darken-2 " id="btnFinalizarNPractica">Finalizar</a>
					<a class="waves-effect waves-light btn red darken-2" id="btnCancelarPractica">Cancelar</a>
				</div>
			</div>
		</div>
	</div>
</div>
