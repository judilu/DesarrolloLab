<div id="eleccionMaterialAlumno2">
	<div class="row">
		<div class="col s3">
			<div class="input-field col s12">
				<input placeholder="" id="txtNumeroControlPrestamo" type="text" class="validate" disabled>
				<label for="txtNumeroControlPrestamo">No. de control</label>
			</div>
		</div>
		<div class="col s5">
			<div class="input-field col s12">
				<input placeholder="" id="txtNombreAluPrestamo" type="text" class="validate" disabled>
				<label for="txtNombreAluPrestamo">Nombre</label>
			</div>
		</div>
	</div>
	<p id="textoMaterial" class="centrado">Por favor, seleccione los articulos y la cantidad de ellos que necesitará para su práctica.</p>
	<div class="row">
		<div class="input-field col s2">
			<input type="checkbox" id="chbElegirOtroMaterial">
			<label for="chbElegirOtroMaterial">Otro material</label>
		</div>
		<div class="input-field col s5 offset-s1">
			<select id="cmbMaterialesLab">
				<option value="" disabled selected>Selecciona el articulo</option>
			</select>
			<label>Agregar otro artículo</label>
		</div>
		<div class="input-field col s1">
		<input id="txtNumArtMat" type="number" min="1" max="10" value="1"class="validate" disabled>
			<label for="txtNumArtMat">Cantidad</label>
		</div>
		<div class="col s3">
			<a id="btnAgregarArtAlu" class="waves-effect waves-light btn amber darken-2" style="display:none"><i class="material-icons left">add</i>Agregar</a>
		</div>
	</div>
	<div class="row">
		<div class="col s10 offset-s1">
			<table class="bordered" id="tbEleccionMaterial">
				<thead>
					<tr>
						<th data-field="nombre" class="col s6">Nombre del artículo</th>
						<th data-field="cantidad" class="col s3">Cantidad</th>
						<th data-field="accion" class="col s3">Acción</th>
					</tr>
				</thead>
				<tbody id="bodyArtAlumno">
					
				</tbody>
			</table>
		</div>
	</div>
	<div class="row">
		<div class="col s6 offset-s4">
			<a class="btn waves-effect waves-light  green darken-2" type="submit" name="action" id="btnAceptarEleccionMat">Aceptar
			</a>
			<a class="waves-effect waves-light btn red darken-1" id="btnCancelarEleccionMat">Cancelar</a>
		</div>
	</div>
</div>
</div>