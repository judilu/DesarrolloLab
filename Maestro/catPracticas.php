<div class="catPracticasMaestro">
	<div class="row" id="practicasMaestro">
		<div class="row">
			<form action="#" class=" col s6 offset-s1">
				<div>
					<input class="with-gap filtroB" name="grpB" type="radio" id="rdoTitulo" value= "1" checked/>
					<label for="rdoTitulo">Título</label>

					<input class="with-gap filtroB" name="grpB" type="radio" id="rdoMateria" value= "2"/>
					<label for="rdoMateria">Materia</label>
		
					<input class="with-gap filtroB" name="grpB" type="radio" id="rdoLaboratorio" value= "3"/>
					<label for="rdoLaboratorio">Laboratorio</label>
				</div>
			</form>
			<div class="input-field col s6 offset-s1" id="divTituloB">
				<input id="txtBuscarPracticas" type="text" class="validate">
				<label class="active" for="txtBuscarPracticas">Titulo de la práctica</label>
			</div>
			<div class="input-field col s6 offset-s1" id="divMateriaB">
				<select id="cmbMatPracticaB">

				</select>
				<label>Materia</label>
			</div>
			<div class="input-field col s6 offset-s1" id="divPracticaB">
				<select id="cmbLaboratorioB">

				</select>
				<label>Laboratorio</label>
			</div>
			<div class="col s3">
				<a class="waves-effect waves-light btn blue darken-1" id="btnBuscarPractica"><i class="material-icons">search</i> Buscar</a>
			</div>
		</div>
		<h5 class="centrado">Catalogo de prácticas</h5>
		<div class="col s10 offset-s1">
			<table id="tabCatPracticas">
				
			</table>
		</div>
	</div>
</div>