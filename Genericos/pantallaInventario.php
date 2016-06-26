<div class="row" id="listaArt">
	<div class="col s12">
		<div class="row">
			<div class="input-field col s4">
				<input id="txtArticuloLista" type="text" class="validate">
				<label class="active" for="txtArticulo">Artículo</label>
			</div>
			<div class="col s3">
				<a class="waves-effect waves-light btn blue darken-1" id="btnBuscarInventario"><i class="material-icons left">search</i>Buscar</a>
			</div>
		</div>
		<div class="row">
			<div class="col s10 offset-s1">
				<table class="bordered responsive-table centered" id="tbInventario">
				
				</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<div id="editar">
	<?php include 'editarArticulos.php';?>
</div>