<div id = "editarSolLab">
		<div class="row" id="editarSol">
			<h5 class="centrado">Editar Solicitud</h5>
			<div class="col s12">
				<div class="row">
					<div class="input-field col s6">
						<input disabled placeholder="" id="txtMateriaE" type="text" class="validate">
						<label class="active" for="txtMateriaE">Materia</label>
					</div>
					<div class="input-field col s3">
						<input disabled placeholder="" id="txtHoraMatE" type="text" class="validate">
						<label class="active" for="txtHoraMatE">Hora de la materia</label>
					</div>
					<div class="input-field col s3">
						<input id= "txtFechaSE" placeholder= " " type="date">
						<label class="active" for="txtFechaSE">Fecha práctica</label>
					</div>
				</div>
				<div class="row">
					<div class="input-field col s6">
						<input disabled id= "txtPracticaE" placeholder= " " type="text" class="validate">
						<label class="active" for="txtPracticaE">Práctica</label>
					</div>
					<div class="input-field col s6">
						<input disabled id= "txtLabE" placeholder= " " type="text" class="validate">
						<label class="active" for="txtLabE">Laboratorio</label>
					</div>
				</div>
				<div class="row">
					<div class="input-field col s3">
						<select id="cmbHoraPractE">
							
						</select>
						<label>Hora de la práctica</label>
					</div>
					<div class="input-field col s2 offset-s1">
						<input placeholder=" " id="txtCantAlumnosE" type="number" min="1" value="1"class="validate">
						<label class="active" for="txtCantAlumnosE">Cant. de alumnos</label>
					</div>
					<div class="input-field col s6">
						<textarea disabled id="textarea1E" class="materialize-textarea"></textarea>
						<label class="active" for="textarea1E">Motivo de uso</label>
					</div>
				</div>
				<div class="row">
					<div class="col s8 offset-s7">
						<a class="waves-effect waves-light btn amber darken-2" id="btnElegirMaterialE">Elegir material</a>
						<a class="waves-effect waves-light btn green darken-2" id="btnRegresarPen">Regresar</a>
					</div>
				</div>
			</div>
		</div>
		<!-- //elección material -->
		<div id="eleccionMaterialE">
			<div class="row">
				<div class="col s12">
					<div class="row">
						<div class="input-field col s6 offset-s1">
							<select id="cmbMaterialCatE">
								
							</select>
							<label>Materiales</label>
						</div>
						<div class="input-field col s2">
							<input id="txtNumArtE" type="number" min="1" max="20" value="1"class="validate">
							<label for="txtNumArtE">Cant. de articulos</label>
						</div>
						<div class="col s3">
							<a id="btnAgregarArtE" class="waves-effect waves-light btn amber darken-2"><i class="material-icons left">add</i>Agregar</a>
						</div>
					</div>
					<div class="row">
						<div class="col s10 offset-s1">
							<table id="tbMaterialSolE" class="bordered">
								<thead>
									<tr>
										<th data-field="txtCantidad" class="col s2">Cantidad</th>
										<th data-field="txtDescripcion" class="col s8">Descripción</th>
										<th data-field="txtHora" class="col s2">Acción</th>
									</tr>
								</thead>
								<tbody id="bodyArtE">
									
								</tbody>
							</table>
						</div>
					</div>
					<div class="row">
						<div class="col s4 offset-s4">
							<a class="waves-effect waves-light btn green darken-2 " id="btnFinalizarNSE">Finalizar</a>
							<a class="waves-effect waves-light btn red darken-2" id="btnRegresarE">Cancelar</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Termina elección material -->
	</div>