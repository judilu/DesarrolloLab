<div id="mantenimientoArt">
	<div class="row">
		<div class="col s12">
			<h5 class="centrado">Envío de articulos a mantenimiento</h5>
			<div class="row">
				<div class="input-field col s5">
					<input id="txtCodigoBarrasMtto" type="text" class="validate">
					<label class="active" for="txtCodigoBarrasMtto">Identificador artículo</label>
				</div>
				<div class="col s3">
					<a class="waves-effect waves-light btn blue darken-1" id="btnBuscarArtMtto">Buscar</a>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col s12">
			<div class="row">
				<div class="input-field col s4">
					<input placeholder=" " id="txtNombreArtMtto" type="text" class="validate" disabled>
					<label class="active" for="txtNombreArtMtto">Nombre del artículo</label>
				</div>
				<div class="input-field col s4 offset-s1">
					<input placeholder=" " id="txtModeloArtMtto" type="text" class="validate" disabled>
					<label class="active" for="txtModeloArtMtto">Modelo</label>
				</div>
				<div class="input-field col s2 offset-s1">
					<input placeholder=" " id="txtNumSerieMtto" type="text" class="validate" disabled>
					<label class="active" for="txtNumSerieMtto">Número de serie</label>
				</div>
			</div>
			<div class="row">
				<div class="input-field col s4">
					<input placeholder=" " id="txtMarcaArtMtto" type="text" class="validate" disabled>
					<label class="active" for="txtMarcaArtMtto">Marca</label>
				</div>
				<div class="input-field col s4 offset-s1">
					<input id="txtLugarReparacionMtto" type="text" class="validate">
					<label class="active" for="txtLugarReparacionMtto">Lugar de reparación</label>
				</div>
				<div class="input-field col s2 offset-s1">
					<input placeholder=" " id="txtFechaCaducidadMtto" type="text" class="validate" disabled>
					<label class="active" for="txtFechaCaducidadMtto">Fecha de caducidad</label>
				</div>
			</div>
			<div class="row">
				<div class="input-field col s6">
					<textarea id="txtMotivoMtto" type="text" class="materialize-textarea"></textarea>
					<label class="active" for="txtMotivoMtto">Motivo de la reparación</label>
				</div>
				<!--<div class="input-field col s2 offset-s1">
					<select id="hrMtto">
						<option value="" disabled selected>Hora</option>
						<option value="10:00">10:00</option>
						<option value="10:30">10:30</option>
						<option value="11:00">11:00</option>
						<option value="11:00">11:30</option>
						<option value="12:00">12:00</option>
					</select>
					<label>Hora</label>
				</div>-->
				
			</div>
			<div class="row">
				<div class="col s5 offset-s7">
					<a id="btnGuardaMantenimiento" class="waves-effect waves-light btn green darken-2"><i class="material-icons left">done</i>Aceptar</a>
					<a id="btnCancelarMtto" class="waves-effect btn red darken-1"><i class="material-icons left">clear</i>Cancelar</a>
				</div>
			</div>
		</div>
	</div>
</div>
