<div id="bajaArticulos2">
	<div class="row">
		<div class="col s12">
			<div class="row">
				<div class="input-field col s5">
					<input id="txtCodigoBarrasBaja" type="text" class="validate">
					<label class="active" for="txtCodigoBarrasBaja">Identificador arículo</label>
				</div>
				<div class="col s3">
					<a class="waves-effect waves-light btn blue darken-1" id="btnBuscarArtBaja">Buscar</a>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col s12">
			<div class="row">
				<div class="input-field col s4">
					<select id="cmbTipoBaja">
						<option value=" " disabled selected>Tipo de salida</option>
						<option value="B">Baja</option>
						<option value="MR">Merma</option>
					</select>
					<label>Tipo de salida</label>
				</div>
				<div class="input-field col s4 offset-s1">
					<input disabled placeholder=" " id="txtModeloArtBaja" type="text" class="validate">
					<label class="active" for="txtModeloArt">Modelo</label>
				</div>
				<div class="input-field col s2 offset-s1">
					<input disabled placeholder=" " id="txtNumSerieBaja" type="text" class="validate">
					<label class="active" for="txtNumSerie">Número de serie</label>
				</div>
			</div>
			<div class="row">
				<div class="input-field col s4">
					<input disabled placeholder=" " id="txtNombreArtBaja" type="text" class="validate">
					<label class="active" for="txtNombreArt">Nombre del artículo</label>
				</div>
				<div class="input-field col s4 offset-s1">
					<input disabled placeholder=" " id="txtMarcaArtBaja" type="text" class="validate">
					<label class="active" for="txtMarcaArt">Marca</label>
				</div>
				<div class="input-field col s2 offset-s1">
					<input disabled placeholder=" " id="txtFechaCaducidadBaja" type="text" class="validate">
					<label class="active" for="txtFechaCaducidad">Fecha de caducidad</label>
				</div>
			</div>
			<div class="row">
				<div class="input-field col s6">
					<textarea disabled placeholder=" " id="txtDescripcionArtBaja" type="text" class="materialize-textarea"></textarea>
					<label class="active" for="txtDescripcionArt">Descripción del artículo</label>
				</div>
				<div class="input-field col s6">
					<textarea disabled placeholder=" " id="txtDescripcionUsoBaja" type="text" class="materialize-textarea"></textarea>
					<label class="active" for="txtDescripcionUso">Descripción de uso</label>
				</div>
			</div>
			<div class="row">
				<div class="input-field col s2">
					<input disabled placeholder=" " id="txtUnidadMedidaBaja" type="text" class="validate">
					<label class="active" for="txtUnidadMedida">Unidad de medida</label>
				</div>
				<div class="input-field col s2 offset-s1">
					<input disabled placeholder=" " id="txtTipoContenedorBaja" type="text" class="validate">
					<label class="active" for="txtTipoContenedor">Tipo de contenedor</label>
				</div>
				<div class="input-field col s6 offset-s1">
					<textarea id="txtMotivoDeBaja" type="text" class="materialize-textarea"></textarea>
					<label class="active" for="txtMotivoDeBaja">Motivo de baja</label>
				</div>
			</div>
			<div class="row">
				<div class="col s5 offset-s7">
					<a id="btnBajaArt" class="waves-effect waves-light btn green darken-2"><i class="material-icons left">done</i>Dar de Baja</a>
					<a id="btnCancelarBaja" class="waves-effect btn red darken-1"><i class="material-icons left">clear</i>Cancelar</a>
				</div>
			</div>
		</div>
	</div>
</div>
