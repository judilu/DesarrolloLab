<div class="row" id="guardarSolicitud">
	<div class="col s12">
			<h5 class="centrado">Aceptar solicitud de laboratorio</h5>
		<div class="row">
			<div class="input-field col s3">
				<input placeholder=" " id="txtFechaAsignada" type="date" class="validate">
				<label class="active" for="txtFecha"></label>
			</div>
			<div class="input-field col s3">
				<input placeholder=" " id="txtHoraAsignada" type="text" class="validate">
				<label for="txtHora">Hora solicitada(formato 24hrs)</label>
			</div>
		</div>
		<div class="row">
			<div class="input-field col s6">
				<textarea placeholder=" " id="txtComentariosSol" type="text" class="materialize-textarea"></textarea>
				<label class="active" for="txtComentariosSol">Comentarios</label>
			</div>
			<div class="input-field col s1">
				<input id="txtClaveSol" type="hidden" class="validate">
			</div>
		</div>
		<div class="row">
				<div class="col s5 offset-s7">
					<a id="btnAceptaSolLab" class="waves-effect waves-light btn green darken-2"><i class="material-icons left">done</i>Aceptar</a>
					<a id="btnCancelarSolLab" class="waves-effect red darken-1 btn"><i class="material-icons left">clear</i>Cancelar</a>
				</div>
			</div>
	</div>
</div>
