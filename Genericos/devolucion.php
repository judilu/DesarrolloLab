<div id="devolucionMaterial2">
	<h5 class="centrado">Devolución de material</h5>
	<div class="row">
				<div class="input-field col s2">
					<input id="txtNControlAluDev" placeholder="" type="text" class="validate" disabled>
					<label active for="txtNControlAluDev">Número control</label>
				</div>
				<div class="input-field col s4">
					<input id="txtNombreAluDev" placeholder="" type="text" class="validate" disabled>
					<label active for="txtNombreAluDev">Nombre del alumno</label>
				</div>
				<div class="input-field col s1 offset-s1">
					<input id="txtCvePrestamoDev" type="hidden" class="validate">
				</div>
			</div>
	<div class="row">
		<div class="col s8 offset-s2">
			<table class="bordered highlight responsive-table" id="tbListaArticulosDevolucion">

			</table>
		</div>
	</div>
	<div class="row">
		<div class="input-field col s1">
			<input id="txtClavePrestamoDevolucion" type="hidden" class="validate">
		</div>
		<div class="col s7 offset-s4">
			<a class="btn waves-effect waves-light green darken-2 " type="submit" name="action" id="btnFinalizarDevolucion">Finalizar</a>
			<a class="btn waves-effect waves-light red darken-1" type="submit" name="action" id="btnCancelarDevolucion">Cancelar</a>
		</div>
	</div>		
</div>
<div id="aplicaSanciones">
	<?php include 'sanciones.php';?>
</div>
