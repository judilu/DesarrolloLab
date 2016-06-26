<div class="catPracticasGenerico">
	<div class="row" id="practicasGenerico">
		<div class="row">
			<h5 class="centrado">Catalogo de prácticas</h5>
			<form action="#" class=" col s6 offset-s1">
				<div>
					<input class="with-gap filtroBG" name="grpBG" type="radio" id="rdoTituloG" value= "1" checked/>
					<label for="rdoTituloG">Título</label>

					<input class="with-gap filtroBG" name="grpBG" type="radio" id="rdoMateriaG" value= "2"/>
					<label for="rdoMateriaG">Materia</label>
				</div>
			</form>
			<div class="input-field col s6 offset-s1" id="divTituloBG">
				<input id="txtBuscarPracticasG" type="text" class="validate">
				<label class="active" for="txtBuscarPracticasG">Titulo de la práctica</label>
			</div>
			<div class="input-field col s6 offset-s1" id="divMateriaBG">
				<select id="cmbMatPracticaBG">

				</select>
				<label>Materia</label>
			</div>
			<div class="col s3">
				<a class="waves-effect waves-light btn blue darken-1" id="btnBuscarPracticaG"><i class="material-icons">search</i> Buscar</a>
			</div>
		</div>
		<h5 class="centrado">Lista de prácticas</h5>
		<div class="col s10 offset-s1">
			<table id="tabCatPracticasG">
				
			</table>
		</div>
	</div>
</div>