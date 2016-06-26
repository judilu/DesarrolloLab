<div class="row" id="peticionesArticulos2">
	<div class="col s12">	
		<div class="row">
			<div class="input-field col s4">
				<select id="cmbNombreArtPeticiones">
					<option value="" disabled selected>Seleccione</option>
					
				</select>
				<label>Nombre del artículo</label>
			</div>
			<div class="input-field col s2">
				<input type="checkbox" id="chbOtroArticulo">
				<label for="chbOtroArticulo">Otro articulo</label>
			</div>
			<div class="input-field col s4 offset-s1">
				<input id="txtNombreArticuloPeticion" type="text" class="validate" disabled="true">
				<label for="txtNombreArticuloPeticion">Nombre</label>
			</div>
		</div>
		<div class="row">
			<div class="input-field col s4 offset">
				<input id="txtMarcaPeticionArt" type="text" class="validate">
				<label for="txtMarcaPeticionArt">Marca</label>
			</div>
			<div class="input-field col s4 offset-s1">
				<input id="txtModeloPeticionArt" type="text" class="validate">
				<label for="txtModeloPeticionArt">Modelo</label>
			</div>
			<div class="input-field col s2 offset-s1">
				<input id="txtCantidadPeticionArt" type="number" class="validate" min="1" max="100">
				<label for="txtCantidadPeticionArt">Cantidad</label>
			</div>
		</div>
		<div class="row">
			<div class="input-field col s6">
				<textarea id="txtMotivoPedidoArt" type="text" class="materialize-textarea"></textarea>
				<label class="active" for="txtMotivoPedidoArt">Motivo de pedido del artículo</label>
			</div>
		</div>
	</div>
	<div class="col s5 offset-s7">
		<a class="waves-effect waves-light btn green darken-2 " id="btnEnviarPeticionArticulo">Enviar</a>
		<a id="btnCancelarPeticionArt" class="waves-effect red darken-1 btn"><i class="material-icons left">clear</i>Cancelar</a>
	</div>
</div>