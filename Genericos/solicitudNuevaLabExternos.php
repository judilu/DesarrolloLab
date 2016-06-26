<div id="solNuevaLabExterno">
	<div class="row" id="nuevaExterno">
		<h5 class="centrado">Nueva solicitud</h5>
		<div class="col s12">
			<div class="row">
				<div class="input-field col s4">
					<select id="cmbNombreDependencias">
						<option value="" disabled selected>Seleccione</option>

					</select>
					<label>Nombre de la dependencia</label>
				</div>
				<div class="input-field col s1">
					<input type="checkbox" id="chbOtraDependencia">
					<label for="chbOtraDependencia">Otra</label>
				</div>
				<div class="input-field col s4 offset-s1">
					<input id="txtNombreDependencia" type="text" class="validate" disabled>
					<label for="txtNombreDependencia">Nombre</label>
				</div>
				<div class="input-field col s2">
					<input id="txtNumeroControlDependencia" type="text" class="validate" disabled placeholder="">
					<label for="txtNumeroControlDependencia">Número de control</label>
				</div>
			</div>
			<div class="row" id="datosDependencia">
				<div class="input-field col s4">
					<input id="txtNombreEncargado" type="text" class="validate">
					<label for="txtNombreEncargado">Nombre del encargado</label>
				</div>
				<div class="input-field col s4">
					<input id="txtDireccionDependencia" type="text" class="validate">
					<label for="txtDireccionDependencia">Dirección dependencia</label>
				</div>
				<div class="input-field col s4">
					<input id="txtTelefonoDependencia" type="text" class="validate" maxlength="20">
					<label for="txtTelefonoDependencia">Telefono dependencia</label>
				</div>
			</div>
			<div class="row">
				<div class="input-field col s3">
					<input id= "txtFechaSolExterno" placeholder= " " type="date" class="datepicker">
					<label class="active" for="txtFechaS">Fecha práctica</label>
				</div>
				<div class="input-field col s4">
					<select id="cmbPracticaExterno">

					</select>
					<label>Práctica</label>
				</div>
				<div class="input-field col s5">
					<select id="cmbLaboratorioExterno">

					</select>
					<label>Laboratorio</label>
				</div>
			</div>
			<div class="row">
				<div class="input-field col s3">
					<select id="cmbHoraPractExterno">

					</select>
					<label>Hora de la práctica</label>
				</div>
				<div class="input-field col s2 offset-s1">
					<input id="txtCantAlumnosExterno" type="number" min="1" value="1" class="validate">
					<label for="txtCantAlumnosExterno">Cant. de alumnos</label>
				</div>
				<div class="input-field col s6">
					<textarea id="txtMotivoUsoExterno" class="materialize-textarea"></textarea>
					<label for="textarea1">Motivo de uso</label>
				</div>
			</div>
			<div class="row">
				<div class="col s4 offset-s9">
					<a class="waves-effect waves-light btn amber darken-2" id="btnElegirMaterialExt">Elegir material</a>
				</div>
			</div>
		</div>
	</div>
	<!-- //elección material -->
	<div id="eleccionMaterialExt">
		<div class="row">
			<div class="col s12">
				<div class="row">
					<div class="input-field col s6 offset-s1">
						<select id="cmbMaterialCatExt">

						</select>
						<label>Materiales</label>
					</div>
					<div class="input-field col s2">
						<input id="txtNumArtExt" type="number" min="1" max="20" value="1"class="validate">
						<label for="txtNumArt">Cant. de articulos</label>
					</div>
					<div class="col s3">
						<a id="btnAgregarArtExt" class="waves-effect waves-light btn amber darken-2"><i class="material-icons left">add</i>Agregar</a>
					</div>
				</div>
				<div class="row">
					<div class="col s10 offset-s1">
						<table id="tbMaterialSolExt" class="bordered">
							<thead>
								<tr>
									<th data-field="txtCantidad" class="col s2">Cantidad</th>
									<th data-field="txtDescripcion" class="col s8">Descripción</th>
									<th data-field="txtHora" class="col s2">Acción</th>
								</tr>
							</thead>
							<tbody id="bodyArtExt">

							</tbody>
						</table>
					</div>
				</div>
				<div class="row">
					<div class="col s4 offset-s4">
						<a class="waves-effect waves-light btn green darken-2 " id="btnFinalizarNSExt">Finalizar</a>
						<a class="waves-effect waves-light btn red darken-1" id="btnRegresarExt">Cancelar</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Termina elección material -->
</div>