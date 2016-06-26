<div id="sancionesAlumnos2">
	<h5 class="centrado">Aplicar sanción</h5>
	<div class="row">
		<div class="col s12">
			<div class="row">
				<div class="input-field col s2">
					<input id="txtNumeroControlSancion" placeholder="" type="text" class="validate" disabled>
					<label for="txtNumeroControlSancion">Número de control</label>
				</div>
				<div class="input-field col s7">
					<input id="txtNombreAlumnoSancion" placeholder="" type="text" class="validate" disabled>
					<label for="txtNombreAlumnoSancion">Nombre del alumno</label>
				</div>
				<div class="input-field col s3">
					<input id="txtIdentificadorArtSancion" placeholder="" type="text" class="validate" disabled>
					<label for="txtIdentificadorArtSancion">Artículo</label>
				</div>
			</div>
			<div class="row">
				<div class="input-field col s2">
					<input placeholder="" type="text" id="txtFechaSancion" disabled>
					<label for="txtFechaSancion">Fecha</label>
				</div>
				<div class="input-field col s5">
					<select id="cmbSanciones">
						<option value="" disabled selected>Selecciona la sanción</option>
					</select>
					<label>Nombre de la sanción</label>
				</div>
				<div class="input-field col s5">
					<textarea id="txtComentariosSanciones" class="materialize-textarea"></textarea>
					<label for="txtComentariosSanciones">Comentarios</label>
				</div>
			</div>
			<div class="row">
				<div class="col s5 offset-s4">
					<input type="hidden" id="txtClavePrestamoSancion">
					<a class="waves-effect waves-light btn green darken-2 " id="btnAplicarSancion">Aplicar</a>
					<a class="waves-effect waves-light btn amber darken-2" id="btnRegresarSancion">Regresar</a>
				</div>
			</div>
		</div>
	</div>
</div>
