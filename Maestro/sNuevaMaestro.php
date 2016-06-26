<div id="solNuevaMaestro">
		<div class="row" id="nuevaMaestro">
			<h5 class="centrado">Nueva solicitud</h5>
			<div class="col s12">
				<div class="row">
					<div class="input-field col s6">
						<select id="cmbMateria">

						</select>
						<label>Materia</label>
					</div>
					<div class="input-field col s3">
						<select id="cmbHoraMat">
							
						</select>
						<label>Hora de la materia</label>
					</div>
					<div class="input-field col s3">
						<input id= "txtFechaS" placeholder= " " type="date" class="datepicker">
						<label class="active" for="txtFechaS">Fecha práctica</label>
					</div>
				</div>
				<div class="row">
					<div class="input-field col s6">
						<select id="cmbPractica">
							
						</select>
						<label>Práctica</label>
					</div>
					<div class="input-field col s6">
						<select id="cmbLaboratorio">
							
						</select>
						<label>Laboratorio</label>
					</div>
				</div>
				<div class="row">
					<div class="input-field col s3">
						<select id="cmbHoraPract">
							
						</select>
						<label>Hora de la práctica</label>
					</div>
					<div class="input-field col s2 offset-s1">
						<input id="txtCantAlumnos" type="number" min="1" value="1"class="validate">
						<label for="txtCantAlumnos">Cant. de alumnos</label>
					</div>
					<div class="input-field col s6">
						<textarea id="textarea1" class="materialize-textarea"></textarea>
						<label for="textarea1">Motivo de uso</label>
					</div>
				</div>
				<div class="row">
					<div class="col s4 offset-s9">
						<a class="waves-effect waves-light btn amber darken-2" id="btnElegirMaterial">Elegir material</a>
					</div>
				</div>
			</div>
		</div>
		<!-- //elección material -->
		<div id="eleccionMaterial">
			<div class="row">
				<div class="col s12">
					<div class="row">
						<div class="input-field col s6 offset-s1">
							<select id="cmbMaterialCat">
								<!-- <option value="" disabled selected>Seleccione el material</option>
								<option value="110">ALUMINIO</option>
								<option value="115">AMONIACO</option>
								<option value="120">AMPERIMETRO</option> -->
							</select>
							<label>Materiales</label>
						</div>
						<div class="input-field col s2">
							<input id="txtNumArt" type="number" min="1" max="20" value="1"class="validate">
							<label for="txtNumArt">Cant. de articulos</label>
						</div>
						<div class="col s3">
							<a id="btnAgregarArt" class="waves-effect waves-light btn amber darken-2"><i class="material-icons left">add</i>Agregar</a>
						</div>
					</div>
					<div class="row">
						<div class="col s10 offset-s1">
							<table id="tbMaterialSol" class="bordered">
								<thead>
									<tr>
										<th data-field="txtCantidad" class="col s2">Cantidad</th>
										<th data-field="txtDescripcion" class="col s8">Descripción</th>
										<th data-field="txtHora" class="col s2">Acción</th>
									</tr>
								</thead>
								<tbody id="bodyArt">
									<!-- <tr>
										<td class="col s2">3</td>
										<td class="col s8">Osciloscopio</td>
										<td class="col s2"><a name = 1 class="btnEliminarArt btn-floating btn-large waves-effect waves-light red darken-1"><i class="material-icons">delete</i></a></td>";
									</tr> -->
								</tbody>
							</table>
						</div>
					</div>
					<div class="row">
						<div class="col s4 offset-s4">
							<a class="waves-effect waves-light btn green darken-2 " id="btnFinalizarNS">Finalizar</a>
							<a class="waves-effect waves-light btn red darken-2" id="btnRegresar">Cancelar</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Termina elección material -->
	</div>