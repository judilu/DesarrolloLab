<div id="altaArticulos2">
	<div class="col s12">
		<div class="row">
			<div class="col s6">
				<form action="#">
					<!--<div class="file-field input-field">
						<div class="btn">
							<span>File</span>
							<input type="file">
						</div>
						<div class="file-path-wrapper">
							<input class="file-path validate" type="text" id="txtImagenAlta">
						</div>
					</div>-->
				</form>
			</div>	
		</div>
	</div>
	<div class="row">
		<div class="col s12">
			<div class="row">
				<div class="input-field col s4">
					<select id="cmbNombreArtAlta">
						<option value="" disabled selected>Seleccione</option>
						
					</select>
					<label>Nombre del artículo</label>
				</div>
				<div class="input-field col s4 offset-s1">
					<input id="txtModeloArtAlta" type="text" class="validate">
					<label class="active" for="txtModeloArt">Modelo</label>
				</div>
				<div class="input-field col s2 offset-s1">
					<input id="txtNumSerieAlta" type="text" class="validate">
					<label class="active" for="txtNumSerie">Número de serie</label>
				</div>
			</div>
			<div class="row">
				<div class="input-field col s4">
					<select id="cmbUm">
						<option value="" disabled selected>Seleccione</option>
						<option value="Pieza">Pieza</option>
						<option value="Litros">Litros</option>
						<option value="Mililitros">Mililitros</option>
						<option value="Kilogramos">Kilogramos</option>
						<option value="Gramos">Gramos</option>
						<option value="Miligramos">Miligramos</option>
					</select>
					<label>Unidad medida</label>
				</div>
				<div class="input-field col s4 offset-s1">
					<input id="txtMarcaArtAlta" type="text" class="validate">
					<label class="active" for="txtMarcaArt">Marca</label>
				</div>
				<div class="input-field col s2 offset-s1">
					<input id="txtClaveKitAlta" type="text" class="validate">
					<label class="active" for="txtClaveKit">Clave kit</label>
				</div>		
			</div>
			<div class="row">
				
				<div class="input-field col s4">
					<input id="txtTipoContenedorAlta" type="text" class="validate">
					<label class="active" for="txtTipoContenedor">Tipo contenedor(bote,caja)</label>
				</div>
				<div class="input-field col s3  offset-s1">
					<input id="txtFechaCaducidadAlta" type="text" class="validate">
					<label class="active" for="txtFechaCaducidad">Caducidad(aaaa-mm-dd)</label>
				</div>
				<div class="input-field col s3 offset-s1">
					<input id="txtUbicacionAlta" type="text" class="validate">
					<label class="active" for="txtUbicacion">Ubicación(Estante#,cajón#)</label>
				</div>
			</div>
			<div class="row">
				<div class="input-field col s6">
					<textarea id="txtDescripcionArtAlta" type="text" class="materialize-textarea"></textarea>
					<label class="active" for="txtDescripcionArt">Descripción del artículo</label>
				</div>
				<div class="input-field col s6">
					<textarea id="txtDescripcionUsoAlta" type="text" class="materialize-textarea"></textarea>
					<label class="active" for="txtDescripcionUso">Descripción de uso</label>
				</div>
			</div>
			<div class="row">
				<div class="col s5 offset-s7">
					<a id="btnAltaArt" class="waves-effect waves-light btn green darken-2"><i class="material-icons left">done</i>Dar de alta</a>
					<a id="btnCancelarAlta" class="waves-effect red darken-1 btn"><i class="material-icons left">clear</i>Cancelar</a>
				</div>
			</div>
		</div>
	</div>
</div>
