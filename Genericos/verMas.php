<div class="row" id="verPrincipal">
	<div class="col s12">
		<div class="row">
			<div class="col">
				<h5 class="centrado">Información general</h5>
			</div>
			
			<div class="input-field col s3 offset-s3">
				<input placeholder=" " id="txtFechaVM1" type="text" class="validate" disabled>
				<label for="txtFecha">Fecha</label>
			</div>
			<div class="input-field col s3">
				<input placeholder=" " id="txtHoraVM1" type="text" class="validate" disabled>
				<label for="txtHora">Hora</label>
			</div>
		</div>
		<div class="row">
			<div class="input-field col s6">
				<input placeholder=" " id="txtMaestroVM1" type="text" class="validate" disabled>
				<label for="txtMaestro" class="active">Maestro</label>
			</div>
			<div class="input-field col s5 offset-s1">
				<input placeholder=" " id="txtPracticaVM1" type="text" class="validate" disabled>
				<label for="txtPractica">Nombre de la práctica</label>
			</div>
		</div>
		<div class="row">
			<div class="col s10 offset-s1">
				<h5 class="centrado">Materiales necesarios para la práctica</h5>
				<table id="tbMaterialesPendientesLab">
					<!--se llena dinamicamente con php-->
				</table>
			</div>
		</div>
		<div class="row">
			<a class="btn-floating btn-large waves-effect amber darken-2" id="btnRegresarVerMas"><i class="material-icons">reply</i></a>
		</div>
	</div>
</div>
<div id="aceptarSolLab">
	<?php include 'guardaSolLab.php';?>
</div>
