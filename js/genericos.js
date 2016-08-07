var inicioGenerico = function()
 {
 	$('ul.tabs').tabs();
	$('select').material_select(); //agregado
	$('.collapsible').collapsible({
      accordion : false}); // A setting that changes the collapsible behavior to expandable instead of the default accordion style
	var articulosPrestados = new Array();
	var articulosExt = new Array();
	var articulosAgregadosExt = new Array();
	var numArticulosExt = new Array();
	var bandera = false;
    //Salir del sistema
    var salir = function()
    {
    	swal({   	
    		title: "¿Estas seguro que deseas salir?",   
    		text: "",   
    		type: "warning",   
    		showCancelButton: true,   
    		confirmButtonColor: "#DD6B55",   
    		confirmButtonText: "Si",   
    		cancelButtonText: "No",   
    		closeOnConfirm: false,   closeOnCancel: false 
    	}, 
    	function(isConfirm)
    	{   
    		if (isConfirm) 
    		{ 
    			var parametros = "opc=salir1"+
    			"&id="+Math.random();
    			$.ajax({
    				cache:false,
    				type: "POST",
    				dataType: "json",
    				url:"../data/funciones.php",
    				data: parametros,
    				success: function(response)
    				{
    					if(response.respuesta)
    					{
    						document.location.href= "acceso.php";
    					}
    					else
    					{
    						console.log(response.respuesta);
    					}
    				},
    				error: function(xhr, ajaxOptions,x)
    				{
    					console.log("Error de conexión salir");
    				}
    			});
    		} 
    		else 
    		{
    			InicioGenericos();
    			swal("OK..!","Aún sigues en el sistema", "warning");
    		} 
    	});
    }
	//Prestamos de matertial a alumnos y externos
	var prestamosPendientes = function()
	{
		$("#inicioG").hide();
		if(tipoUsu != 5)
		{
			$("#atenderSolicitud").hide("slow");
			$("#alumnosSancionados").hide("slow");
			$("#solicitudesEnProceso").hide("slow");
			$("#solicitudesPendientes").show("slow");
			$("#solicitudesPendientes2").show("slow");
			$("#tabSolPendientesAlumnos").html(" ");
			$("#loaderImageG").show();
			var parametros 	= "opc=prestamosPendientes1"+"&id="+Math.random();
			$.ajax({
				cache:false,
				type: "POST",
				dataType: "json",
				url:"../data/genericos.php",
				data: parametros,
				success: function(response){
					if(response.respuesta || response.respuesta2)
					{
						$("#loaderImageG").hide();
						$("#tabSolPendientesAlumnos").html(" ");
						$("#tabSolPendientesAlumnos").append(response.renglones);
						$("#tabSolPendientesAlumnos #btnAtenderPrestamo").on("click",atenderPrestamoMaterial);
					}
					else
					{
						$("#loaderImageG").hide();
						sweetAlert("No hay prestamos pendientes!", " ", "warning");
					}
				},
				error: function(xhr, ajaxOptions,x)
				{
					$("#loaderImageG").hide();
					console.log("Error de conexión prestamos pendientes");
				}
			});
		}
	}
	var atenderPrestamoMaterial = function()
	{
		$(this).closest('tr').remove();
		articulosPrestados = Array();
		$("#solicitudesPendientes2").hide("slow");
		$("#tbListaMaterialPrestamo").html("");
		$("#bodyArtSolicitados").html("");
		$("#loaderImageG").show();
		var clavePrestamo= $(this).attr('name');
		var parametros 	= "opc=atenderPrestamo1"
						+"&clavePrestamo="+clavePrestamo
						+"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					$("#txtclavePrestamo").val(response.clavePrestamo);
					$("#txtcodigoBarrasPrestamo").val("");
					$("#tbListaMaterialPrestamo").append(response.renglones);
					$("#txtnombreAlumnoPrestamo").val(response.nombre);
				}
				else
				{
					$("#loaderImageG").hide();
					sweetAlert("No se pudo atender la solicitud!", " ", "warning");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión atender prestamo material");
			}
		});
		$("#atenderSolicitud").show("slow");
		$("#atenderSolicitud2").show("slow");

	}
	var agregarArticuloPrestamo = function()
	{
		if(($("#txtcodigoBarrasPrestamo").val())!="")
		{
			$("#loaderImageG").show();
			var identificadorArticulo 	= $("#txtcodigoBarrasPrestamo").val();
			var clavePrestamo 			= $("#txtclavePrestamo").val();
			var parametros 				= "opc=agregaArticulos1"
											+"&identificadorArticulo="+identificadorArticulo
											+"&clavePrestamo="+clavePrestamo
											+"&id="+Math.random();
			$.ajax({
				cache:false,
				type: "POST",
				dataType: "json",
				url:'../data/genericos.php',
				data: parametros,
				success: function(response){
					if(response.respuesta == true)
					{
						$("#loaderImageG").hide();
						$("#txtcodigoBarrasPrestamo").val("");
						$("#tbArticulosSolicitados > tbody").append("<tr><td>"+response.idu+"</td><td>"+response.nomArt+"</td></tr>");
						articulosPrestados.push(response.idu);
					}
					else
					{
						$("#loaderImageG").hide();
						sweetAlert("El artículo no existe", "Es posible que el artículo no esté dado de alta", "warning");
					}
				},
				error: function(xhr, ajaxOptions,x)
				{
					$("#loaderImageG").hide();
					console.log("Error de conexión al buscar el articulo para agregarlo");
				}
			});
		}
		else
		{
			sweetAlert("Seleccione un artículo", "Es posible que no haya seleccionado ningun artículo", "warning");
		}
	}
	var guardarPrestamoPendiente = function()
	{
		$("#loaderImageG").show();
		var listaArt 		= articulosPrestados;
		var clavePrestamo 	= $("#txtclavePrestamo").val();
		var parametros 		= "opc=guardaPrestamoPendiente1"
								+"&listaArt="+listaArt
								+"&clavePrestamo="+clavePrestamo
								+"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:'../data/genericos.php',
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					swal("Prestamo finalizado con éxito!", "Da clic en el botón OK!", "success");
					prestamosPendientes();
				}
				else
				{
					$("#loaderImageG").hide();
					sweetAlert("No se pudo finalizar el prestamo!", "", "warning");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión guarda prestamo pendiente");
			}
		});
		$("#txtclavePrestamo").val("");
	}
	var eliminaPrestamoPendiente = function()
	{
		if (tipoUsu == 1 || tipoUsu == 2 )
		{
			var clavePrestamo 	= ($(this).attr("name"));//$("#btnEliminarPrestamo").attr('name');
			$("#loaderImageG").show();
			var parametros 		= "opc=eliminaPrestamoPendiente1"
								+"&clavePrestamo="+clavePrestamo
								+"&id="+Math.random();
			$.ajax({
				cache:false,
				type: "POST",
				dataType: "json",
				url:'../data/genericos.php',
				data: parametros,
				success: function(response){
					if(response.respuesta)
					{
						$("#loaderImageG").hide();
						prestamosPendientes();
						swal("Prestamo eliminado con éxito!", "Da clic en el botón OK!", "success");
					}
					else
					{
						$("#loaderImageG").hide();
						sweetAlert("No se pudo eliminar el prestamo!", "", "warning");
					}
				},
				error: function(xhr, ajaxOptions,x)
				{
					$("#loaderImageG").hide();
					console.log("Error de conexión elimina prestamo pendiente");
				}
			});
		}
		else
		{
			$("#loaderImageG").hide();
			$("#btnEliminarPrestamo").hide();
			sweetAlert("No tienes permisos para eliminar el préstamo", "", "warning");
		}
	}
	var prestamosProceso = function()
	{
		$("#loaderImageG").show();
		$("#tabSolProcesoAlumnos").html("");
		$("#solicitudesPendientes").hide("slow");
		$("#devolucionMaterial").hide("slow");
		$("#alumnosSancionados").hide("slow");
		var parametros 	= "opc=prestamosProceso1"
							+"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					$("#tabSolProcesoAlumnos").html("");
					$("#tabSolProcesoAlumnos").append(response.renglones);
					$("#tabSolProcesoAlumnos #btnDevolucionMaterial").on("click",devolucionPrestamo);
				}
				else
				{
					$("#loaderImageG").hide();
					sweetAlert("No hay prestamos en proceso!", " ", "warning");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión prestamos en proceso");
			}
		});
		$("#solicitudesEnProceso").show("slow");
		$("#solicitudesEnProceso2").show("slow");
	}
	var listaSanciones = function()
	{	
		$("#loaderImageG").show();
		$("#solicitudesPendientes").hide("slow");
		$("#devolucionMaterial").hide("slow");
		$("#solicitudesEnProceso").hide("slow");
		var parametros 	= "opc=listaSanciones1"
							+"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					$("#tabListaSanciones").html("");
					$("#tabListaSanciones").append(response.renglones);
					$("#tabListaSanciones .btnQuitaSancion").on("click",quitaSancion);
				}
				else
				{
					$("#loaderImageG").hide();
					sweetAlert("No hay personas sancionadas!", " ", "warning");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión lista de sanciones");
			}
		});
		$("#alumnosSancionados").show("slow");
		$("#listaSanciones2").show("slow");
	}
	var quitaSancion = function ()
	{
		var f  = new Date();
		var dd = f.getDate();
		var mm = (f.getMonth())+1;
		(dd<10) ? (dd="0"+dd) : dd;
		(mm<10) ? (mm="0"+mm) : mm;
		var fe  = (f.getFullYear()+"-"+mm+"-"+dd);	
		if (tipoUsu == 1 || tipoUsu == 2 )
		{
			$("#loaderImageG").show();
			$(this).closest("tr").remove();
			var claveSancion 	= $(this).attr('name');
			var claveArt 		= $(this).attr('id');
			var parametros 	= "opc=quitaSanciones1"+
								"&claveSancion="+claveSancion+
								"&claveArt="+claveArt+
								"&fecha="+fe+
								"&id="+Math.random();
			$.ajax({
				cache:false,
				type: "POST",
				dataType: "json",
				url:"../data/genericos.php",
				data: parametros,
				success: function(response){
					if(response.respuesta == true)
					{
						$("#loaderImageG").hide();
						swal("Sanción eliminada con éxito!", "Da clic en el botón OK!", "success");
					}
					else
					{
						$("#loaderImageG").hide();
						sweetAlert("La sanción no se pudo eliminar!", " ", "warning");
					}
				},
				error: function(xhr, ajaxOptions,x)
				{
					$("#loaderImageG").hide();
					console.log("Error de conexión quitar sanción");
				}
			});
		}
	}
	var aplicaSancion = function()
	{	
		$("#devolucionMaterial2").hide("slow");
		var f  = new Date();
		var dd = f.getDate();
		var mm = (f.getMonth())+1;
		(dd<10) ? (dd="0"+dd) : dd;
		(mm<10) ? (mm="0"+mm) : mm;
		var fe  = (f.getFullYear()+"-"+mm+"-"+dd);	
		var idu 			= $(this).attr("name");
		var clavePrestamo 	= $("#txtClavePrestamoDevolucion").val();
		$("#txtNumeroControlSancion").val($("#txtNControlAluDev").val());
		$("#txtNombreAlumnoSancion").val($("#txtNombreAluDev").val());
		$("#txtIdentificadorArtSancion").val(idu);
		$("#txtFechaSancion").val(fe);
		$("#loaderImageG").show();
		var nc 				= $("#txtNumeroControlSancion").val();
		var nom 			= $("#txtNombreAlumnoSancion").val();
		var parametros 		= "opc=aplicaSancion1"
							+"&identificador="+idu
							+"&clavePrestamo="+clavePrestamo
							+"&nc="+nc
							+"&nom="+nom
							+"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					$("#cmbSanciones").html(" ");
					$("#cmbSanciones").html("<option value='' disabled selected>Selecciona la sanción</option>");
					$("#txtClavePrestamoSancion").val(response.prestamo);
					for (var i = 0; i < response.contador; i++) 
					{
						$("#cmbSanciones").append($("<option></option>").attr("value",response.claveSancion[i]).text(response.nombreSancion[i]));
					}
					$("#cmbSanciones").trigger('contentChanged');
					$('select').material_select();
				}
				else
				{
					$("#loaderImageG").hide();
					console.log("Error de botón aplicar sanción");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión aplica sanción");
			}
		});
		$("#aplicaSanciones").show("slow");
	}
	var  guardaSancionAlumno = function()
	{
		var f  = new Date();
		var dd = f.getDate();
		var mm = (f.getMonth())+1;
		(dd<10) ? (dd="0"+dd) : dd;
		(mm<10) ? (mm="0"+mm) : mm;
		var fe  = (f.getFullYear()+"-"+mm+"-"+dd);	
		var clavePrestamo 	= $("#txtClavePrestamoSancion").val();
		var idu 			= $("#txtIdentificadorArtSancion").val();
		var nc 				= $("#txtNumeroControlSancion").val();
		var claveSancion 	= $("#cmbSanciones").val();
		var fecha 			= $("#txtFechaSancion").val();
		var comentario 		= $("#txtComentariosSanciones").val();
		$("#loaderImageG").show();
		var parametros 	= "opc=guardaSancion1"
							+"&clavePrestamo="+clavePrestamo
							+"&idu="+idu
							+"&nc="+nc
							+"&claveSancion="+claveSancion
							+"&fecha="+fecha
							+"&comentario="+comentario
							+"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					swal("Sanción aplicada con éxito!", "Da clic en el botón OK!", "success");
					listaSanciones();
				}
				else
				{
					$("#loaderImageG").hide();
					sweetAlert("No se pudo aplicar la sanción!", " ", "warning");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión al guardar la sanción");
			}
		});
	}
	var devolucionPrestamo = function()
	{
		$("#loaderImageG").show();
		$("#solicitudesEnProceso2").hide("slow");
		$("#aplicaSanciones").hide("slow");
		$("#tbListaArticulosDevolucion").html("");
		var clavePrestamo 	= ($(this).attr('name'));
		var parametros 	= "opc=devolucionPrestamo1"
						+"&clavePrestamo="+clavePrestamo
						+"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					$("#txtNControlAluDev").val(response.numeroControl);
					$("#txtNombreAluDev").val(response.nombreAlumno);
					$("#tbListaArticulosDevolucion").append(response.renglones);
					$("#txtClavePrestamoDevolucion").val(response.clavePrestamo);
				}
				else
				{
					$("#loaderImageG").hide();
					sweetAlert("No existe el prestamo!", " ", "warning");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión devolución prestamo");
			}
		});
		$("#devolucionMaterial").show("slow");
		$("#devolucionMaterial2").show("slow");
	}
	var guardarDevolucionPrestamo = function()
	{
		var f  = new Date();
		var dd = f.getDate();
		var mm = (f.getMonth())+1;
		(dd<10) ? (dd="0"+dd) : dd;
		(mm<10) ? (mm="0"+mm) : mm;
		var fe  = (f.getFullYear()+"-"+mm+"-"+dd);
		var horaActual 				= new Date();
		var hora 					=horaActual.getHours();
		var minutos 				=horaActual.getMinutes();
		(minutos<10) ? (minutos="0"+minutos) : minutos;
		var hora					= hora + ":" + minutos;

		var identificadorArticulo 	= $(this).attr('name');
		var clavePrestamo 			= $("#txtClavePrestamoDevolucion").val();
		$(this).closest("tr").remove();
		$("#loaderImageG").show();
		var parametros 		= "opc=guardaDevolucion1"
							+"&clavePrestamo="+clavePrestamo
							+"&identificadorArticulo="+identificadorArticulo
							+"&horaDevolucion="+hora
							+"&fechaDevolucion="+fe
							+"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:'../data/genericos.php',
			data: parametros,
			success: function(response){
				if(response.respuesta)
				{
					$("#loaderImageG").hide();
					swal("Devolución guardada con éxito!", "Da clic en el botón OK!", "success");
				}
				else
				{
					$("#loaderImageG").hide();
					sweetAlert("No se pudo guardar la devolución!", "", "warning");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión guarda prestamo devolución");
			}
		});
	}
	//Laboratorios
	//solicitudes pendientes de laboratorio...
	//funcion para aceptar una solicitud, introduciendo datos faltantes para agendarla
	var aceptarSolicitudLab = function()
	{
		if (tipoUsu == 1 || tipoUsu == 2 )
		{
			var f  = new Date();
			var dd = f.getDate();
			var mm = (f.getMonth())+1;
			(dd<10) ? (dd="0"+dd) : dd;
			(mm<10) ? (mm="0"+mm) : mm;
			var fe  = (f.getFullYear()+"-"+mm+"-"+dd);
			$("#verPrincipal").hide("slow");
			$("#solicitudesPendientesLab2").hide("slow");
			$("#aceptarSolLab").show("slow");
			$("#guardarSolicitud").show("slow");
			$("#verMasSolicitud").show("slow");
			$("#txtComentariosSol").val("");
			$("#loaderImageG").show();
			var claveSol= $(this).attr('name');
			var parametros 	= "opc=obtenerDatosSolLab1"+
								"&clave="+claveSol+
								"&id="+Math.random();
			$.ajax({
				cache:false,
				type: "POST",
				dataType: "json",
				url:"../data/genericos.php",
				data: parametros,
				success: function(response){
					if(response.respuesta == true)
					{
						/*d 		 = (response.fecha).substring(0,2);
       					m 		 = (response.fecha).substring(3,5);
       					a 		 = (response.fecha).substring(6,10);
       					fechaSol = (a+"-"+m+"-"+d);*/
						$("#loaderImageG").hide();
						$("#txtFechaAsignada").val(response.fecha);
						$("#txtFechaAsignada").attr("max",response.fechaP);
						$("#txtFechaAsignada").attr("min",fe);
						$("#txtHoraAsignada").val(response.hora);
						$("#txtClaveSol").val(claveSol);
					}
					else
					{
						$("#loaderImageG").hide();
						sweetAlert("La solicitud no existe!", " ", "warning");
					}
				},
				error: function(xhr, ajaxOptions,x)
				{
					$("#loaderImageG").hide();
					console.log("Error de conexión aceptar solicitud de laboratorio");
				}
			});
		}
	}
	//funcion para guardar una solicitud de laboratorio
	var sGuardaCanderalizada = function()
	{
		if (tipoUsu == 1 || tipoUsu == 2 )
		{
			var claveSol	 = $("#txtClaveSol").val();
			var fechaAsignada= $("#txtFechaAsignada").val();
			var horaAsignada = $("#txtHoraAsignada").val();
			var firmaJefe 	 = "000000";
			var comentarios  = $("#txtComentariosSol").val();
			if (($("#txtClaveSol").val())!="" && ($("#txtComentariosSol").val())!="" && ($("#txtFechaAsignada").val())!="" && ($("#txtHoraAsignada").val())!="") 
			{
				$("#loaderImageG").show();
				var parametros 	= "opc=guardaSolicitudLab1"
								+"&clave="+claveSol
								+"&fecha="+fechaAsignada
								+"&hora="+horaAsignada
								+"&firmaJefe="+firmaJefe
								+"&comentarios="+comentarios
								+"&id="+Math.random();
				$.ajax({
					cache:false,
					type: "POST",
					dataType: "json",
					url:"../data/genericos.php",
					data: parametros,
					success: function(response){
						if(response.respuesta == true)
						{
							$("#loaderImageG").hide();
							sweetAlert("La solicitud fue calendarizada con éxito!", "Da click en el botón OK", "success");
							sLaboratorioPendientes();
						}
						else
						{
							$("#loaderImageG").hide();
							sweetAlert("La solicitud no se calendarizó elige otra fecha u hora!", " ", "warning");
						}
					},
					error: function(xhr, ajaxOptions,x)
					{
						$("#loaderImageG").hide();
						console.log("Error de conexión guarda solicitud laboratorio");
					}
				});
			}
			else
			{
				sweetAlert("Campos incompletos", "Es posible que no haya llenado todos los campos, revise de nuevo", "warning");
			}
		}
	}
	//funcion para eliminar una solicitud de laboratorio
	var eliminarSolLab = function(){
		if (tipoUsu == 1 || tipoUsu == 2 )
		{
			var claveSol= ($(this).attr('name'));
			$(this).closest('tr').remove();
			$("#loaderImageG").show();
			var parametros 	= "opc=eliminaSolicitudLab1"
							+"&clave="+claveSol
							+"&id="+Math.random();
			$.ajax({
				cache:false,
				type: "POST",
				dataType: "json",
				url:"../data/genericos.php",
				data: parametros,
				success: function(response){
					if(response.respuesta && response.respuesta2)
					{
						$("#loaderImageG").hide();
						sweetAlert("La solicitud fue eliminada con éxito!", "Da click en el botón OK", "success");
					}
					else
					{
						$("#loaderImageG").hide();
						sweetAlert("No se pudo eliminar la solicitud!", " ", "warning");
					}
				},
				error: function(xhr, ajaxOptions,x)
				{
					$("#loaderImageG").hide();
					console.log("Error de conexión elimina solicitud de laboratorio");
				}
			});
		}
	}
	var sLaboratorioNuevas = function()
	{
		var f  = new Date();
    	var dd = f.getDate();
    	var mm = (f.getMonth())+1;
    	(dd<10) ? (dd="0"+dd) : dd;
    	(mm<10) ? (mm="0"+mm) : mm;
    	var fe  = (f.getFullYear()+"-"+mm+"-"+dd);
    	articulosAgregadosExt = Array();
		numArticulosExt = Array();
		$("#eleccionMaterialExt").hide();
		$("#sNuevaPracticaLab").hide("slow");
		$("#sCatPracticas").hide("slow");
		$("#sAceptadasLab").hide("slow");
		$("#sPendientesLab").hide("slow");
		$("#verMasSolicitud").hide("slow");
		$("#aceptarSolLab").hide("slow");
		$("#guardarSolicitud").hide("slow");
		$("#sNuevaLabExternos").show("slow");
		$("#nuevaExterno").show("slow");
		$("#datosDependencia").hide("slow");
		$("input").val("");
		$("#txtCantAlumnosExterno").val("1");
		$("textarea").val("");
		$("#chbOtraDependencia").prop('checked', false); ;
		$("#txtFechaSolExterno").val(fe);
		$("#txtFechaSolExterno").attr("min",fe);
		//inicializar combo
		//combo Lab
		$("#cmbLaboratorioExterno").html(" ");
		$("#cmbLaboratorioExterno").html("<option value='' disabled selected>Selecciona el laboratorio</option>");
		//combo horaLab
		$("#cmbHoraPractExterno").html(" ");
    	$("#cmbHoraPractExterno").html("<option value='' disabled selected>Seleccione la hora</option>");	
		$("#loaderImageG").show();	
			var parametros  = "opc=listaDependencias1"
							+"&id="+Math.random();
			$.ajax({
				cache:false,
				type: "POST",
				dataType: "json",
				url:"../data/genericos.php",
				data: parametros,
				success: function(response)
				{
					if(response.respuesta)
					{
						$("#txtFechaSolExterno").attr("max",response.fecha);
						$("#loaderImageG").hide();
						$("#cmbNombreDependencias").html(" ");
						$("#cmbNombreDependencias").html("<option value='' disabled selected>Selecciona la dependencia</option>");
						
						for (var i = 0; i < response.contador; i++) 
						{
							$("#cmbNombreDependencias").append($("<option></option>").attr("value",response.claveDependencia[i]).text(response.nombreDependencia[i]));
						}
						$("#cmbNombreDependencias").trigger('contentChanged');
						$('select').material_select();
					}
					else
					{
						$("#loaderImageG").hide();
						sweetAlert("No existen dependencias", "", "warning");
					}
					if(response.respuesta2)
					{
						$("#loaderImageG").hide();
						$("#cmbPracticaExterno").html(" ");
						$("#cmbPracticaExterno").html("<option value='' disabled selected>Selecciona la práctica</option>");

						for (var i = 0; i < response.contador2; i++) 
						{
							$("#cmbPracticaExterno").append($("<option></option>").attr("value",response.clavePractica[i]).text(response.nombrePractica[i]));
						}
						$("#cmbPracticaExterno").trigger('contentChanged');
						$('select').material_select();
					}
					else
					{
						$("#loaderImageG").hide();
						sweetAlert("No existen prácticas", "", "warning");
					}
				},
				error: function(xhr, ajaxOptions,x)
				{
					$("#loaderImageG").hide();
					console.log("Error de conexión alta articulos");
				}
			});
	}
	var agregarArtExt = function()
	{
		//aquiEmpieza todo
    	var artCve = $("#cmbMaterialCatExt" ).val();
    	var artNom = $("#cmbMaterialCatExt option:selected").text();
    	var num    = $("#txtNumArtExt").val();
	    articulosExt.push(artNom);
	    articulosAgregadosExt.push(artCve);
	    numArticulosExt.push(num);
	    //construir tabla
	    construirTablaExt();
	}
	var eliminarArtExt = function()
    {
    	var art = ($(this).attr("name"));
    	var i = articulosAgregadosExt.indexOf(art);
    	articulosExt = eliminarG(articulosExt,i);
    	articulosAgregadosExt = eliminarG(articulosAgregadosExt,i);
    	numArticulos = eliminarG(numArticulosExt,i);
    	//construir tabla
    	construirTablaExt();
    }
    var eliminarG = function(arreglo,posicion)
	{
		var ar = arreglo;
		var p  = posicion;
		var c  = ar.length;
		var af = Array();
		for (var i=0; i < c; i++) 
		{
			if(i != posicion)
			{
				af.push(ar[i]);
			}
		}
		return af;
	}
	var elegirMatExterno = function()
	{
		//Limpiar variables
		articulosExt = Array();
	    articulosAgregadosExt = Array();
	    numArticulosExt = Array();
	    $("#bodyArtExt").html("");
		var cantAlu 	= parseInt($("#txtCantAlumnosExterno").val());
		var maxCantAlu 	= parseInt($("#txtCantAlumnosExterno").attr("max"));
    	var minCantAlu 	= parseInt($("#txtCantAlumnosExterno").attr("min"));

		if(($("#cmbNombreDependencias").val())!= null || $("#txtNombreDependencia").val() != "")
		{
			if($("#txtFechaSolExterno").val() != "" && ($("#cmbPracticaExterno").val())!= null && ($("#cmbLaboratorioExterno").val())!= null
				&& ($("#cmbHoraPractExterno").val())!= null && $("#txtCantAlumnosExterno").val()!= "" && $("#txtMotivoUsoExterno").val() != ""
				&& (cantAlu >= minCantAlu) && (cantAlu <= maxCantAlu))
			{
				//ocultar elementos
		    	$("#nuevaExterno").hide();
		    	$("#eleccionMaterialExt").show("slow");
		    	$("#txtNumArtExt").val("1");
		    	//llenar combo y crear tabla
		    	//Crear tabla y crear combo
		    	llenarcomboEleArtExt();
		    }
		    else
		    {
		    	sweetAlert("Debe llenar todos los campos"," ","warning");
		    }
		}
	    else
	    {
			sweetAlert("Debe llenar todos los campos"," ", "warning");
	    }
	}
	var construirTablaExt = function()
	{
		$("#loaderImageG").show();
		var parametros = "opc=construirTbArtExt1"+
    	"&articulosAgregadosExt="+articulosAgregadosExt+
    	"&articulosExt="+articulosExt+
    	"&numArticulosExt="+numArticulosExt+
    	"&id="+Math.random();
    	$.ajax({
    		cache:false,
    		type: "POST",
    		dataType: "json",
    		url:"../data/genericos.php",
    		data: parametros,
    		success: function(response){
    			if(response.respuesta == true)
    			{
    				$("#loaderImageG").hide();
    				$("#bodyArtExt").html("");
    				$("#bodyArtExt").append(response.renglones);
    				llenarcomboEleArtExt();
    				$("#txtNumArtExt").val("1");
    				$(".btnEliminarArtExt").on("click",eliminarArtExt);
					//formar de nuevo el combo
				}//termina if
				else
				{
					$("#loaderImageG").hide();
					console.log("no elimino");
					$("#bodyArtExt").html("");
					llenarcomboEleArtExt();
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión construir tabla");	
			}
		});
	}
	var llenarcomboEleArtExt = function()
	{
		$("#loaderImageG").show();
		var comboArt 	= Array();
    	var comboclaArt = Array();
    	var c 			= articulosAgregadosExt.length;
    	var i 			= 0;
    	var o 			= 0;	
    	var laboratorio = $("#cmbLaboratorioExterno").val();
    	var parametros 	= "opc=comboEleArtExt1"+
    	"&laboratorio="+laboratorio+
    	"&id="+Math.random();
    	$.ajax({
    		cache:false,
    		type: "POST",
    		dataType: "json",
    		url:"../data/genericos.php",
    		data: parametros,
    		success: function(response)
    		{
    			if(response.respuesta == true)
    			{
    				$("#loaderImageG").hide();
    				comboclaArt = response.comboCveArt;
    				comboArt 	= response.comboNomArt;
					//eliminar elementos repetidos
					for (var r =0; r< c; r++) 
					{
						o = (articulosAgregadosExt[r]);
						i = parseInt((comboclaArt).indexOf(o));
						comboclaArt = (eliminarG(comboclaArt,i));
						comboArt 	= (eliminarG(comboArt,i));				
					}
					var con = comboclaArt.length;
					//termina eliminación
					$("#cmbMaterialCatExt").html(" ");
					$("#cmbMaterialCatExt").html("<option value='' disabled selected>Seleccione el material</option>");
					for (var i = 0; i < con; i++) 
					{
						$("#cmbMaterialCatExt").append($("<option></option>").attr("value",comboclaArt[i]).text(comboArt[i]));
					}
					$("cmbMaterialCatExt").trigger('contentChanged');
					$('select').material_select();
				}
				else
				{
					$("#loaderImageG").hide();
					$("#cmbMaterialCatExt").html(" ");
					$("#cmbMaterialCatExt").html("<option value='' disabled selected>Seleccione el material</option>");
					$('select').material_select();
					sweetAlert("No existen articulos", "Es posible que no existan articulos en dicho laboratorio!", "warning");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión combomat");	
			}
		});
	}
	var comboLaboratoriosExt = function()
	{
		$("#loaderImageG").show();
		practica  = $("#cmbPracticaExterno").val();
		var parametros  = "opc=llenarcomboLabExt1"
							+"&practica="+practica
							+"&id="+Math.random();
			$.ajax({
				cache:false,
				type: "POST",
				dataType: "json",
				url:"../data/genericos.php",
				data: parametros,
				success: function(response)
				{
					if(response.respuesta)
					{
						$("#loaderImageG").hide();
						$("#cmbLaboratorioExterno").html(" ");
						$("#cmbLaboratorioExterno").html("<option value='' disabled selected>Selecciona el laboratorio</option>");
						for (var i = 0; i < response.contador; i++) 
						{
							$("#cmbLaboratorioExterno").append($("<option></option>").attr("value",response.claveLab[i]).text(response.nombreLab[i]));
						}
						$("#cmbLaboratorioExterno").trigger('contentChanged');
						$('select').material_select();
					}
					else
					{
						$("#loaderImageG").hide();
						$("#cmbLaboratorioExterno").html(" ");
						$("#cmbLaboratorioExterno").html("<option value='' disabled selected>Selecciona el laboratorio</option>");
						sweetAlert("No existen laboratorios", "la practica seleccionada no tiene asignado ningun laboratorio", "warning");
					}
				},
				error: function(xhr, ajaxOptions,x)
				{
					$("#loaderImageG").hide();
					console.log("Error de conexión alta articulos");
				}
			});
	}
	var comboHoraExt = function()
	{
		$("#loaderImageG").show();
		var laboratorio   = $("#cmbLaboratorioExterno").val();
    	var parametros 	  = "opc=llenarcomboHrLabExt1"
    						+"&laboratorio="+laboratorio
    						+"&id="+Math.random();
    	var hi = "";
    	var hii = 0;
    	var hf = "";
    	var fff = 0;
    	$.ajax({
    		cache:false,
    		type: "POST",
    		dataType: "json",
    		url:"../data/genericos.php",
    		data: parametros,
    		success: function(response){
    			if(response.respuesta == true)
    			{
    				$("#loaderImageG").hide();
    				$("#cmbHoraPractExterno").html(" ");
    				$("#cmbHoraPractExterno").html("<option value='' disabled selected>Seleccione la hora</option>");	
    				(((response.horaApertura).length)<4) ? (hi=(response.horaApertura).substring(0,1)) : (hi=(response.horaApertura).substring(0,2));
    				hii = parseInt(hi);
    				(((response.horaCierre).length)<4) ? (hf=(response.horaCierre).substring(0,1)) : (hf=(response.horaCierre).substring(0,2));
    				hff = parseInt(hf);
					//modificando capacidad segun el laboratorio
					$("#txtCantAlumnosExterno").attr("max",response.capacidad);
					for (var i = hii; i <= hff; i++) 
					{
						if(i>9)
						{
							$("#cmbHoraPractExterno").append($("<option></option>").attr("value",i).text(i+":00"));
						}
						else
						{
							$("#cmbHoraPractExterno").append($("<option></option>").attr("value","0"+i).text("0"+i+":00"));
						}
					}
					$("cmbHoraPractExterno").trigger('contentChanged');
					$('select').material_select();
				}
				else
				{
					$("#loaderImageG").hide();
					$("#cmbHoraPractExterno").html(" ");
					$("#cmbHoraPractExterno").html("<option value='' disabled selected>Seleccione la hora</option>");
					$('select').material_select();
					sweetAlert("No existen horas", "Es posible que no existan horas asociados a dicha práctica!", "warning");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión comboPrac");	
			}
		});
	}
	var guardaSolLabExterno = function()
	{
		if($("#chbOtraDependencia").is(':checked'))
		{
			var nomDependencia 	= $("#txtNombreDependencia").val();
			var dependencia 	= 0;
			var otra 			= 1;
		}
		else
		{
			var dependencia 	= $("#txtNumeroControlDependencia").val();
			var nomDependencia 	= $("#cmbNombreDependencias option:selected").text();
			var otra 			= 0;
		}
		$("#loaderImageG").show();
		var f  = new Date();
		var dd = f.getDate();
		var mm = (f.getMonth())+1;
			(dd<10) ? (dd="0"+dd) : dd;
			(mm<10) ? (mm="0"+mm) : mm;
		var fe  = (f.getFullYear()+"-"+mm+"-"+dd);
		var ff 	= $("#txtFechaSolExterno").val();
		/*var a  = ff.substring(0,4);
    	var m  = ff.substring(5,7);
    	var d  = ff.substring(8,10);
    	var fecha = a+"-"+m+"-"+d;*/
		var practica 	= $("#cmbPracticaExterno").val();
		var laboratorio	= $("#cmbLaboratorioExterno").val();
		var hora		= $("#cmbHoraPractExterno option:selected").text();
		var cantAlu		= $("#txtCantAlumnosExterno").val();
		var motivo		= $("#txtMotivoUsoExterno").val();
		var nomEncargado= $("#txtNombreEncargado").val();
		var direccion 	= $("#txtDireccionDependencia").val();
		var telefono	= $("#txtTelefonoDependencia").val();
		var articulos	= articulosAgregadosExt;
		var cantArt		= numArticulosExt;
		var numeroLineas= (($("#tbMaterialSolExt tr").length)-1);
		var parametros 	= "opc=guardaSolLabExterno1"
							+"&dependencia="+dependencia
							+"&nomDep="+nomDependencia
							+"&fechaEnvio="+fe
							+"&fechaSol="+ff
							+"&practica="+practica
							+"&laboratorio="+laboratorio
							+"&hora="+hora
							+"&cantAlu="+cantAlu
							+"&motivo="+motivo
							+"&nomEncargado="+nomEncargado
							+"&direccion="+direccion
							+"&telefono="+telefono
							+"&articulos="+articulos
							+"&cantArt="+cantArt
							+"&otra="+otra
							+"&numeroLineas="+numeroLineas
							+"&id="+Math.random();
			$.ajax({
				cache:false,
				type: "POST",
				dataType: "json",
				url:"../data/genericos.php",
				data: parametros,
				success: function(response){
					if(response.respuesta == true)
					{
						$("#loaderImageG").hide();
						if (response.otra == 1) 
						{
							swal("La solicitud fue enviada con éxito! El numero de control de la dependencia es: "+response.dependenciaN, "Da clic en el botón OK!", "success");
							articulosAgregadosExt = Array();
							numArticulosExt = Array();
							sLaboratorioNuevas();
						}
						else
						{
							swal("La solicitud fue enviada con éxito! El numero de control de la dependencia es: "+response.dependenciaE, "Da clic en el botón OK!", "success");
							articulosAgregadosExt = Array();
							numArticulosExt = Array();
							sLaboratorioNuevas();
						}
					}
					else
					{
						$("#loaderImageG").hide();
						sweetAlert("No se envió la solicitud!", " ", "warning");
					}
				},
				error: function(xhr, ajaxOptions,x)
				{
					$("#loaderImageG").hide();
					console.log("Error de conexión solicitudes pendientes de laboratorio");
				}
			});
	}
	var numeroControlLabExterno = function()
	{
		$("#txtNumeroControlDependencia").val($("#cmbNombreDependencias").val());
	}
	var checkOtraDependencia = function()
	{
		if ($("#chbOtraDependencia").is(':checked'))
		{
			$("#datosDependencia").show("slow");
			$("#txtNombreDependencia").removeAttr("disabled");
			$("#cmbNombreDependencias").html("");
			$("#cmbNombreDependencias").html("<option value='' disabled selected>Selecciona la dependencia</option>");
			$("#cmbNombreDependencias").trigger('contentChanged');
			$('select').material_select();
			$("#txtNumeroControlDependencia").val("");
		}
		else
		{
			$("#datosDependencia").hide("slow");
			$("#txtNombreDependencia").attr("disabled","disabled");
			sLaboratorioNuevas();
		}
	}
	//Empieza Judith
	var nuevaPracticaG = function()
	{
		//Ocultar elementos
		$("#cmbLaboratorioBG").hide();
		$("#sCatPracticas").hide("slow");
		$("#sAceptadasLab").hide("slow");
		$("#sPendientesLab").hide("slow");
		$("#sNuevaLabExternos").hide("slow");
		$("#sCatPracticas").hide("slow");
		$("#sNuevaPracticaLab").show("slow");
		$("#nuevaMaestroG").show("slow");

		//Limpiar elementos
		$("#txtDuracionPractG").val(1);
		$("#textareaDesPracG").val("");
		$("#txtTituloPracticaG").val("");

		//Contenido dinamico - llenando comboMaterias
		$("#loaderImageG").show("slow");
		var parametros 	= "opc=llenarComboMaterias1"
							+"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response)
			{
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					$("#cmbMatPracticaG").html("");
					$("#cmbMatPracticaG").html("<option value='' disabled selected>Seleccione la materia</option>");
					for (var i = 0; i < response.claveMat.length; i++) 
					{
						$("#cmbMatPracticaG").append($("<option></option>").attr("value",response.claveMat[i]).text(response.nombreMat[i]));
					}
					$("cmbMatPracticaG").trigger('contentChanged');
					$('select').material_select();
					$("#txtLaboratorioPracticaG").val(response.nombreLab);
					$("#txtLaboratorioPracticaG").attr("name",response.claveLab);
				}
				else
				{
					$("#loaderImage").hide();
					$("#cmbMatPracticaG").html(" ");
					$("#cmbMatPracticaG").html("<option value='' disabled selected>Seleccione la materia</option>");
					$('select').material_select();
					console.log("no trajo materias");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión comboMaterias nuevaPractica");
			}
		});
	}
	var altaNuevaPracticaG = function()
    {
    	var durPractG 	 = parseInt($("#txtDuracionPractG").val());
    	var maxDurPractG = parseInt($("#txtDuracionPractG").attr("max"));
    	var minDurPractG = parseInt($("#txtDuracionPractG").attr("min"));
    	if($("#txtTituloPracticaG").val()!=""&& $("#cmbMatPracticaG option:selected").val()!="" && $("#textareaDesPracG").val() != "")
    	{
    		if((durPractG >= minDurPractG) && (durPractG <= maxDurPractG))
    		{
	    		//contenido dinamico
	    		$("#loaderImageG").show();
	    		var titulo 		= $("#txtTituloPracticaG").val();
	    		var materia 	= $("#cmbMatPracticaG").val();
	    		var laboratorio = $("#txtLaboratorioPracticaG").attr("name");
	    		var duracion 	= $("#txtDuracionPractG").val();
	    		var descripcion = $("#textareaDesPracG").val();
	    		var parametros = "opc=nuevaPractGn1"+
					        			"&titulo="+titulo+
										"&materia="+materia+
										"&laboratorio="+laboratorio+
										"&duracion="+duracion+
										"&descripcion="+descripcion+
					        			"&id="+Math.random();
					$.ajax({
					        cache:false,
					        type: "POST",
					        dataType: "json",
					        url:'../data/genericos.php',
					        data: parametros,
					        success: function(response)
					        {
					        		if(response.respuesta == true)
					        		{
					        			$("#loaderImageG").hide();
					                    //limpiar datos
					                    nuevaPracticaG();
										swal("La práctica fue creada con éxito!", "Da clic en el botón OK!", "success");				
									}
									else
									{	$("#loaderImageG").hide();					
										sweetAlert("Warning", "No se pudo crear la práctica!", "warning");
									}
								},
								error: function(xhr, ajaxOptions,x)
								{
									$("#loaderImageG").hide();
									console.log("Error de conexión Nueva Practica alta");
								}
					});
			}
			else
			{
				sweetAlert("Verifique la duración de la práctica", "La duración de la práctica debe estar en un intervalo de 1Hr a 5 Hrs!", "warning");
			}
    	}
    	else
    	{
    		$("#loaderImage").hide();
    		sweetAlert("Verifique los campos", "Llene todos los campos!", "warning");
    	}
    }
    var catalagoPracticasG = function()
	{
		$("#txtBuscarPracticasG").val("");
		$("#rdoTituloG").prop('checked',true);
		$("#divTituloBG").show();
		$("#divMateriaBG").hide();
		$("#divPracticaBG").hide();
		$("#sNuevaPracticaLab").hide("slow");
		$("#sAceptadasLab").hide("slow");
		$("#sPendientesLab").hide("slow");
		$("#sNuevaLabExternos").hide("slow");
		$("#sCatPracticas").hide("slow");
		$("#sNuevaPracticaLab").hide("slow");
		$("#nuevaMaestroG").hide("slow");
		$("#sCatPracticas").show("slow");
		//Contenido Dinamico
		var parametros = "opc=catalagoPracticasG1"+
							"&id="+Math.random();
		$("#loaderImageG").show();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response)
			{
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					$("#tabCatPracticasG").html(" ");
					$("#tabCatPracticasG").append(response.renglones);
				}
				else
				{
					$("#loaderImage").hide();
					sweetAlert("No hay prácticas asignadas al laboratorio..!", "", "warning");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión comboMaterias nuevaPractica");
			}
		});
		$("#sCatPracticas").show("slow");
	}
	var llenarDatosBuscarG = function()
    {
    	$("#txtBuscarPracticasG").val("");
    	if( $("#rdoTituloG").is(':checked'))
    	{
    		$("#divTituloBG").show();
    		$("#divMateriaBG").hide();
    		$("#divPracticaBG").hide();
    		catalagoPracticasG();
    	}
    	else if( $("#rdoMateriaG").is(':checked'))
    	{
    		$("#divMateriaBG").show();
    		$("#divTituloBG").hide();
    		$("#divPracticaBG").hide();
    		llenarCombosPracticas();
    	}
    }
    var llenarCombosPracticas = function()
    {
    	catalagoPracticasG();
    	$("#divTituloBG").hide();
		$("#rdoMateriaG").prop("checked",true);
		$("#divMateriaBG").show();
    	//Limpiar combo Materia
		$("#cmbMatPracticaBG").html("");
		$("#cmbMatPracticaBG").html("<option value='' disabled selected>Seleccione la materia</option>");
		$("cmbMatPracticaBG").trigger('contentChanged');
		$('select').material_select();

    	var parametros = "opc=llenarComboPracticas1"+
							"&id="+Math.random();
		$("#loaderImageG").show();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response)
			{
				if(response.respuesta == true)
				{
					$("loaderImageG").hide();
					//LLenar combo materias
					$("#cmbMatPracticaBG").html(" ");
					$("#cmbMatPracticaBG").html("<option value='' disabled selected>Seleccione la materia</option>");
					for (var i = 0; i < response.materias.length; i++) 
					{
						$("#cmbMatPracticaBG").append($("<option></option>").attr("value",response.materias[i]).text(response.matsnoms[i]));
					}
					$("cmbMatPracticaBG").trigger('contentChanged');
					$('select').material_select();
				}
				else
				{
					$("#loaderImageG").hide();
					console.log("no trajo nada");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión comboMaterias nuevaPractica");
			}
		});

		$("#sCatPracticas").show("slow");
    }
    var buscarPracticaG = function()
    {
    	var titulo = $("#txtBuscarPracticasG").val();
    	var laboratorio = $("#cmbMatPracticaBG").val();
    	if( titulo != "" || laboratorio != null)
    	{
	    	if( $("#rdoTituloG").is(':checked'))
	    	{
	    		opcion 	= "p.tituloPractica"
	    		valor 	= $("#txtBuscarPracticasG").val();
	    	}

	    	else if( $("#rdoMateriaG").is(':checked'))
	    	{
	    		opcion 	= "ap.MATCVE"
	    		valor 	= $("#cmbMatPracticaBG").val();
	    	}
	    	//contenido dinamico
    		$("#loaderImageG").show("slow");
    		$("#tabCatPracticasG").html(" ");
			var parametros = "opc=catPracticasFiltroG1"+
							 "&opcion="+opcion+
							 "&valor="+valor+
								"&id="+Math.random();
			$.ajax({
				cache:false,
				type: "POST",
				dataType: "json",
				url:"../data/genericos.php",
				data: parametros,
				success: function(response)
				{
					if(response.respuesta == true)
					{
						$("#loaderImageG").hide();
						$("#tabCatPracticasG").html(" ");
						$("#tabCatPracticasG").append(response.renglones);
					}
					else
					{
						$("#loaderImageG").hide();
						sweetAlert("No existen prácticas..!", "", "warning");
					}
				},
				error: function(xhr, ajaxOptions,x)
				{
					$("#loaderImageG").hide();
					console.log("Error de conexión catalogo prácticas");
				}
			});
	    }
    	else
    	{
    		sweetAlert("Verifique los campos", "Debe seleccionar por lo menos un campo!", "warning");
    	}

    }//Termina funcion para buscar una práctica
	var sLaboratorioPendientes = function()
	{
		if(tipoUsu == 1 || tipoUsu == 2)
		{
			$("#sAceptadasLab").hide("slow");
			$("#sNuevaPracticaLab").hide("slow");
			$("#sCatPracticas").hide("slow");
			$("#sNuevaLabExternos").hide("slow");
			$("#verMasSolicitud").hide("slow");
			$("#aceptarSolLab").hide("slow");
			$("#guardarSolicitud").hide("slow");
			$("#tbPendientesLab").html("");
			$("#loaderImageG").show();
			var parametros 	= "opc=pendientesLab1"
							+"&id="+Math.random();
			$.ajax({
				cache:false,
				type: "POST",
				dataType: "json",
				url:"../data/genericos.php",
				data: parametros,
				success: function(response){
					if(response.respuesta == true)
					{
						$("#loaderImageG").hide();
						$("#tbPendientesLab").append(response.renglones);
						$("#tbPendientesLab #btnCalendarizado").on("click",aceptarSolicitudLab);
						$("#tbPendientesLab #btnVerMas").on("click",verMas);
						$("#tbPendientesLab #btnEliminarSolLab").on("click",eliminarSolLab);
					}
					else
					{
						$("#loaderImageG").hide();
						sweetAlert("No hay solicitudes de laboratorio pendientes!", " ", "warning");
					}
				},
				error: function(xhr, ajaxOptions,x)
				{
					$("#loaderImageG").hide();
					console.log("Error de conexión solicitudes pendientes de laboratorio");
				}
			});
			$("#sPendientesLab").show("slow");
			$("#solicitudesPendientesLab2").show("slow");
		}
	}
	var sLaboratorioAceptadas = function()
	{
		$("#inicioG").hide();
		$("#loaderImageG").show();
		$("#sCatPracticas").hide("slow");
		$("#sNuevaPracticaLab").hide("slow");
		$("#sPendientesLab").hide("slow");
		$("#sNuevaLabExternos").hide("slow");
		$("#verMasSolicitud2").hide("slow");
		$("#tbAceptadasLab").html("");
		
		var claveCal = $(this).attr('name');
		var parametros 	= "opc=aceptadasLab1"
						+"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response)
			{
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					$("#tbAceptadasLab").append(response.renglones);
					$("#tbAceptadasLab a").on("click",verMas2);
				}
				else
				{
					$("#loaderImageG").hide();
					sweetAlert("No hay solicitudes de laboratorio aceptadas!", " ", "warning");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión solicitudes aceptadas de laboratorio");
			}
		});
		$("#sAceptadasLab").show("slow");
		$("#solicitudesAceptadasLab2").show("slow");
	}
	var verMas = function()
	{		
		$("#loaderImageG").show();
		$("#solicitudesPendientesLab2").hide("slow");
		//contenido dinamico
		var clave = $(this).attr("name");
		$("#tbMaterialesPendientesLab").html("");
		var parametros = "opc=verMasLab1"
						+"&clave="+clave
						+"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta)
				{
					$("#loaderImageG").hide();
					$("#txtFechaVM1").val(response.fecha);
					$("#txtHoraVM1").val(response.hora);
					$("#txtMaestroVM1").val(response.maestro);
					$("#txtPracticaVM1").val(response.practica);
					$("#tbMaterialesPendientesLab").append(response.renglones);
				}
				else
				{
					$("#loaderImageG").hide();
					sweetAlert("No existe esa solicitud..!", "", "warning");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión realizadas");
			}
		});
		$("#verMasSolicitud").show("slow");
		$("#verPrincipal").show("slow");	
	}
	var verMas2 = function()
	{		
		$("#solicitudesAceptadasLab2").hide("slow");
		$("#loaderImageG").show();
		//contenido dinamico
		var claveCal = $(this).attr("name");
		$("#tbMaterialesAceptadasLab").html("");
		var parametros = "opc=verMasLab2"
						+"&clave="+claveCal
						+"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					$("#txtFecha2").val(response.fechaAsignada);
					$("#txtHora2").val(response.horaAsignada);
					$("#txtMaestro2").val(response.maestro);
					$("#txtPractica2").val(response.practica);
					$("#tbMaterialesAceptadasLab").append(response.renglones);
				}
				else
				{
					$("#loaderImageG").hide();
					sweetAlert("No existe esa solicitud..!", "", "warning");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión realizadas");
			}
		});	
		$("#verMasSolicitud2").show("slow");
		$("#verPrincipal2").show("slow");
	}
	//Inventario
	//Busca un articulo en la lista de artículos del laboratorio
	var buscaArtInventario = function()
	{
		$("#loaderImageG").show();
		var articulo = $("#txtArticuloLista").val();
		var parametros 	= "opc=buscaArtLista1"
						+"&articulo="+articulo
						+"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response)
			{
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					$("#tbInventario").html("");
					$("#tbInventario").append(response.renglones);
				}
				else
				{
					$("#loaderImageG").hide();
					sweetAlert("No existe ese artículo..!", " ", "warning");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión lista de artículos");
			}
		});
	}
	//Muestra la lista de artículos pertenecientes al laboratorio del usuario
	var listaArticulos = function()
	{
		$("#inicioG").hide();
		$("#loaderImageG").show();
		$("#txtArticuloLista").val("");
		$("#altaArticulos").hide("slow");
		$("#bajaArticulos").hide("slow");
		$("#menuMtto").hide("slow");
		$("#editar").hide("slow");
		$("#peticionesPendientes").hide("slow");
		$("#peticionesArticulos").hide("slow");
		var parametros 	= "opc=listaArticulos1"
						+"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					$("#tbInventario").html("");
					$("#tbInventario").append(response.renglones);
				}
				else if (tipoUsu == 5) 
				{
					$("#loaderImageG").hide();
					sweetAlert("No hay artículos pertenecientes al departamento..!", " ", "warning");
				}
				else
				{
					$("#loaderImageG").hide();
					sweetAlert("No hay artículos pertenecientes al laboratorio..!", " ", "warning");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión lista de artículos");
			}
		});
		$("#listaArt").show("slow");
		$("#pantallaInventario").show("slow");
	}
	//Para mostrar pantalla de alta y que llene un combobox de los articulos que puede dar de alta
	var altaArticulos = function()
	{
		if (tipoUsu == 1 || tipoUsu == 2) 
		{
			$("#loaderImageG").show();
			$("#pantallaInventario").hide("slow");
			$("#bajaArticulos").hide("slow");
			$("#menuMtto").hide("slow");
			$("#peticionesPendientes").hide("slow");
			$("#peticionesArticulos").hide("slow");
			$("input").val("");
			$("textarea").val("");
			$("#cmbNombreArtAlta").html(" ");
			$("#cmbNombreArtAlta").html("<option value='' disabled selected>Selecciona el articulo</option>");
			var parametros  = "opc=listaArtAlta"
							+"&id="+Math.random();
			$.ajax({
				cache:false,
				type: "POST",
				dataType: "json",
				url:"../data/genericos.php",
				data: parametros,
				success: function(response)
				{
					if(response.respuesta)
					{
						$("#loaderImageG").hide();
						$("#cmbNombreArtAlta").html(" ");
						$("#cmbNombreArtAlta").html("<option value='' disabled selected>Selecciona el articulo</option>");
						for (var i = 0; i < response.contador; i++) 
						{
							$("#cmbNombreArtAlta").append($("<option></option>").attr("value",response.claveMaterial[i]).text(response.nombreMaterial[i]));
						}
						$("#cmbNombreArtAlta").trigger('contentChanged');
						$('select').material_select();
					}
					else
					{
						$("#loaderImageG").hide();
						sweetAlert("No hay articulos disponibles", "", "warning");
					}
				},
				error: function(xhr, ajaxOptions,x)
				{
					$("#loaderImageG").hide();
					console.log("Error de conexión alta articulos");
				}
			});
			$("#altaArticulos").show("slow");
		}
	}
	//guarda la alta de los articulos
	var altaInventario = function()
	{
		if (tipoUsu == 1 || tipoUsu == 2) 
		{
			if(($("#txtModeloArtAlta").val())!="" && ($("#txtNumSerieAlta").val())!="" && ($("#cmbNombreArtAlta").val())!= null
				&& ($("#txtMarcaArtAlta").val())!="" && ($("#txtTipoContenedorAlta").val())!="" && ($("#txtUbicacionAlta").val())!="")
			{
				if($("#txtClaveKitAlta").val() == "")
				{
					var claveKit 		= "0000";
				}
				else
				{
					var claveKit 		= $("#txtClaveKitAlta").val();
				}
				if($("#txtFechaCaducidadAlta").val() == "") 
				{
					var fechaCaducidad	= "1900-01-01";
				}
				else
				{
					var fechaCaducidad	= $("#txtFechaCaducidadAlta").val();
				}
	       		$("#loaderImageG").show();
	       		//fecha del sistema
				var f  = new Date();
				var dd = f.getDate();
				var mm = (f.getMonth())+1;
				(dd<10) ? (dd="0"+dd) : dd;
				(mm<10) ? (mm="0"+mm) : mm;
				var fe  = (f.getFullYear()+"-"+mm+"-"+dd);
				//hora del sistema
				var horaActual 				= new Date();
				var hora 					=horaActual.getHours();
				var minutos 				=horaActual.getMinutes();
				(hora<10) ? (hora="0"+hora) : hora;
				(minutos<10) ? (minutos="0"+minutos) : minutos;
				var hora					= hora + ":" + minutos;

	       		var imagen						= " ";
	       		var modelo 						= $("#txtModeloArtAlta").val();
	       		var numeroSerie 				= $("#txtNumSerieAlta").val();
				var claveArticulo				= $("#cmbNombreArtAlta").val();//ocupo sacar el valor del select
				var marca						= $("#txtMarcaArtAlta").val();
				var tipoContenedor 				= $("#txtTipoContenedorAlta").val();
				var descripcionArticulo			= $("#txtDescripcionArtAlta").val();
				var descripcionUso				= $("#txtDescripcionUsoAlta").val();
				var unidadMedida 				= $("#cmbUm").val();
				var ubicacionAsignada			= $("#txtUbicacionAlta").val();
				var estatus						= "V";
				var parametros 	= "opc=altaInventario1"+"&claveArticulo="+claveArticulo
								+"&imagen="+imagen
								+"&modelo="+modelo
								+"&numeroSerie="+numeroSerie
								+"&marca="+marca
								+"&tipoContenedor="+tipoContenedor
								+"&descripcionArticulo="+descripcionArticulo
								+"&descripcionUso="+descripcionUso
								+"&unidadMedida="+unidadMedida
								+"&fechaCaducidad="+fechaCaducidad
								+"&claveKit="+claveKit
								+"&ubicacionAsignada="+ubicacionAsignada
								+"&estatus="+estatus
								+"&fecha="+fe
								+"&hora="+hora
								+"&id="+Math.random();
				$.ajax({
					cache:false,
					type: "POST",
					dataType: "json",
					url:'../data/genericos.php',
					data: parametros,
					success: function(response)
					{
						if(response.respuesta)
						{
							$("#loaderImageG").hide();
							swal("El articulo fue dado de alta con éxito! El identificador del artículo es: "+response.idu, "Da clic en el botón OK!", "success");
							$("input").val("");
							$("textarea").val("");
						}
						else
						{
							$("#loaderImageG").hide();
							sweetAlert("No se pudo insertar el articulo!", "", "warning");
						}
					},
					error: function(xhr, ajaxOptions,x)
					{
						$("#loaderImageG").hide();
						console.log("Error de conexión alta inventario");
					}
				});
			}
			else
			{
				sweetAlert("Información incompleta", "Es posible que no haya llenado todos los campos, verifique de nuevo", "warning");
			}
		}
	}
	//muestra la pantalla de baja articulos
	var bajaArticulos = function()
	{
		if(tipoUsu == 1 || tipoUsu ==2)
		{
			$("#altaArticulos").hide("slow");
			$("#menuMtto").hide("slow");
			$("#pantallaInventario").hide("slow");
			$("#peticionesPendientes").hide("slow");
			$("#peticionesArticulos").hide("slow");
			$("input").val("");
			$("textarea").val("");
			$("#bajaArticulos").show("slow");
			$("#btnBuscarArtBaja").show();
			$("#txtCodigoBarrasBaja").removeAttr("disabled");
		}
	}
	//da de baja un articulo al presionar el boton dar de baja
	var bajaInventario = function()
	{
		if(tipoUsu == 1 || tipoUsu ==2)
		{
			if($("#cmbTipoBaja").val()!= null && $("#txtMotivoDeBaja").val()!="" && $("#txtModeloArtBaja").val()!="")
			{
				$("#loaderImageG").show();
				//fecha del sistema
				var f  = new Date();
				var dd = f.getDate();
				var mm = (f.getMonth())+1;
				(dd<10) ? (dd="0"+dd) : dd;
				(mm<10) ? (mm="0"+mm) : mm;
				var fe  = (f.getFullYear()+"-"+mm+"-"+dd);
				//hora del sistema
				var horaActual 				= new Date();
				var hora 					=horaActual.getHours();
				var minutos 				=horaActual.getMinutes();
				(hora<10) ? (hora="0"+hora) : hora;
				(minutos<10) ? (minutos="0"+minutos) : minutos;
				var hora					= hora + ":" + minutos;

				var identificadorArticulo	= $("#txtCodigoBarrasBaja").val();//obtener el articulo a dar de baja
				var estatus 				= $("#cmbTipoBaja").val();
				var observaciones 			= $("#txtMotivoDeBaja").val()
				var parametros 	= "opc=bajaArticulos1"
								+"&identificadorArticulo="+identificadorArticulo
								+"&estatus="+estatus
								+"&fecha="+fe
								+"&hora="+hora
								+"&observaciones="+observaciones
								+"&id="+Math.random();
				$.ajax({
					cache:false,
					type: "POST",
					dataType: "json",
					url:'../data/genericos.php',
					data: parametros,
					success: function(response){
						if(response.respuesta == true)
						{
							$("#loaderImageG").hide();
							swal("El articulo fue dado de baja con éxito!", "Da clic en el botón OK!", "success");
							$("input").val("");
							$("textarea").val("");
						}
						else
						{
							$("#loaderImageG").hide();
							sweetAlert("Error", "No se pudo dar de baja el articulo!", "error");
						}
					},
					error: function(xhr, ajaxOptions,x)
					{
						$("#loaderImageG").hide();
						console.log("Error de conexión baja de artículos");
					}
				});
			}
			else
			{
				$("#loaderImageG").hide();
				sweetAlert("Información incompleta", "Es posible que no haya llenado todos los campos, verifique de nuevo", "warning");
			}
		}
	}
	//buscar el articulo a dar de baja y regresa todos sus datos y los muestra 
	//en la pantalla de dar de baja, rellenando los campos
	var buscarArticulo = function() 
	{
		if(($("#txtCodigoBarrasBaja").val())!="")
		{
			$("#loaderImageG").show();
			var identificadorArticulo= $("#txtCodigoBarrasBaja").val();
			var parametros  = "opc=buscaArticulos1"
							+"&identificadorArticulo="+identificadorArticulo
							+"&id="+Math.random();
			$.ajax({
				cache:false,
				type: "POST",
				dataType: "json",
				url:'../data/genericos.php',
				data: parametros,
				success: function(response){
					if(response.respuesta == true)
					{
						$("#loaderImageG").hide();
						$("#txtModeloArtBaja").val(response.modelo);
						$("#txtNumSerieBaja").val(response.numeroSerie);
						$("#txtNombreArtBaja").val(response.nombreArticulo);
						$("#txtMarcaArtBaja").val(response.marca);
						$("#txtFechaCaducidadBaja").val(response.fechaCaducidad);
						$("#txtDescripcionArtBaja").val(response.descripcionArticulo);
						$("#txtDescripcionUsoBaja").val(response.descripcionUso);
						$("#txtUnidadMedidaBaja").val(response.unidadMedida);
						$("#txtTipoContenedorBaja").val(response.tipoContenedor);
						$("#btnBuscarArtBaja").hide();
						$("#txtCodigoBarrasBaja").attr("disabled","disabled");
					}
					else
					{
						$("#loaderImageG").hide();
						bajaArticulos();
						sweetAlert("El artículo no existe", "", "warning");
					}
				},
				error: function(xhr, ajaxOptions,x)
				{
					$("#loaderImageG").hide();
					console.log("Error de conexión al buscar el articulo para darlo de baja");
				}
			});
		}
		else
		{
			sweetAlert("Ingrese el código del artículo", "", "warning");
		}
	}
	//Muestra la pantalla de enviar articulos a mantenimiento con sus dos botones
	var mantenimientoArticulos = function()
	{
		if(tipoUsu == 1 || tipoUsu ==2)
		{
			$("#altaArticulos").hide("slow");
			$("#bajaArticulos").hide("slow");
			$("#pantallaInventario").hide("slow");
			$("#peticionesPendientes").hide("slow");
			$("#peticionesArticulos").hide("slow");
			$("#listaArtMtto").hide("slow");
			$("#listaMtto").hide("slow");
			$("#menuMtto").show("slow");
			$("#sEnvioMtto").show("slow");
		}
	}
	//Pantalla para enviar articulos a mantenimiento(solicitud)
	var enviaArtMtto = function()
	{
		if(tipoUsu == 1 || tipoUsu ==2)
		{
			$("#listaArtMtto").hide("slow");
			$("#listaMtto").hide("slow");
			$("input").val("");
			$("textarea").val("");
			$("#menuMtto").show("slow");
			$("#sEnvioMtto").show("slow");
			$("#btnBuscarArtMtto").show();
			$("#txtCodigoBarrasMtto").removeAttr("disabled");
		}
	}
	//Pantalla para visualizar que articulos fueron enviados a mantenimiento
	var listaArtMtto = function()
	{
		$("#btnImprimirArtMtto").hide();
		if(tipoUsu == 1 || tipoUsu ==2)
		{
			$("#sEnvioMtto").hide("slow");
			$("#loaderImageG").show();
			var parametros 	= "opc=listaMantenimiento1"+
								"&id="+Math.random();
			$.ajax({
				cache:false,
				type: "POST",
				dataType: "json",
				url:'../data/genericos.php',
				data: parametros,
				success: function(response)
				{
					if(response.respuesta == true)
					{
						$("#loaderImageG").hide();
						$("#btnImprimirArtMtto").show();
						$("#tbArticulosMtto").html("");
						$("#tbArticulosMtto").append(response.renglones);
						$("#tbArticulosMtto #btnRegresaDelMtto").on("click",regresaMtto);
					}
					else
					{
						$("#loaderImageG").hide();
						sweetAlert("No hay articulos en mantenimiento!", " ", "warning");
					}
				},
				error: function(xhr, ajaxOptions,x)
				{
					$("#loaderImageG").hide();
					console.log("Error de conexión lista de articulos en mantenimiento");
				}
			});
			$("#listaArtMtto").show("slow");
			$("#listaMtto").show("slow");
			$("#menuMtto").show("slow");
		}
	}
	//Regresa un articulo de mantenimiento
	var regresaMtto = function()
	{
		if(tipoUsu == 1 || tipoUsu ==2)
		{
			$("#loaderImageG").show();
			var iduArt = $(this).attr('name');
			$(this).closest('tr').remove();
			//fecha del sistema
			var f  = new Date();
			var dd = f.getDate();
			var mm = (f.getMonth())+1;
			(dd<10) ? (dd="0"+dd) : dd;
			(mm<10) ? (mm="0"+mm) : mm;
			var fe  = (f.getFullYear()+"-"+mm+"-"+dd);
			//hora del sistema
			var horaActual 				= new Date();
			var hora 					=horaActual.getHours();
			var minutos 				=horaActual.getMinutes();
			(hora<10) ? (hora="0"+hora) : hora;
			(minutos<10) ? (minutos="0"+minutos) : minutos;
			var hora					= hora + ":" + minutos;
			var parametros  ="opc=regresaMtto1"
							+"&iduArt="+iduArt
							+"&fecha="+fe
							+"&hora="+hora
							+"&id="+Math.random();
			$.ajax({
				cache:false,
				type: "POST",
				dataType: "json",
				url:'../data/genericos.php',
				data: parametros,
				success: function(response){
					if(response.respuesta == true)
					{
						$("#loaderImageG").hide();
						swal("El artículo se regresó de mantenimiento con éxito!", "Da clic en el botón OK!", "success");
					}
					else
					{
						$("#loaderImageG").hide();
						sweetAlert("No se pudo registrar el regreso de mantenimiento!", "", "warning");
					}
				},
				error: function(xhr, ajaxOptions,x)
				{
					$("#loaderImageG").hide();
					console.log("Error de conexión regreso de mantenimiento");
				}
			});
		}
	}
	//Busca el articulo que queremos enviar a mantenimiento
	var buscarArticuloMtto = function() 
	{
		if(($("#txtCodigoBarrasMtto").val())!="")
		{
			$("#loaderImageG").show();
			var identificadorArticulo= $("#txtCodigoBarrasMtto").val();
			var parametros= "opc=buscaArticulos2"
			+"&identificadorArticulo="+identificadorArticulo
			+"&id="+Math.random();
			$.ajax({
				cache:false,
				type: "POST",
				dataType: "json",
				url:'../data/genericos.php',
				data: parametros,
				success: function(response){
					if(response.respuesta == true)
					{
						$("#loaderImageG").hide();
						$("#txtModeloArtMtto").val(response.modelo);
						$("#txtNumSerieMtto").val(response.numeroSerie);
						$("#txtNombreArtMtto").val(response.nombreArticulo);
						$("#txtMarcaArtMtto").val(response.marca);
						$("#txtFechaCaducidadMtto").val(response.fechaCaducidad);
						$("#btnBuscarArtMtto").hide();
						$("#txtCodigoBarrasMtto").attr("disabled","disabled");
					}
					else
					{
						$("#loaderImageG").hide();
						enviaArtMtto();
						sweetAlert("El artículo no existe", "", "warning");
					}
				},
				error: function(xhr, ajaxOptions,x)
				{
					$("#loaderImageG").hide();
					console.log("Error de conexión al buscar el artículo para registrar el mantenimiento");
				}
			});
		}
	}
	//Cambia el estatus del articulo a M que es mantenimiento
	var guardaMtto = function()
	{
		if(tipoUsu == 1 || tipoUsu ==2)
		{
			if(($("#txtCodigoBarrasMtto").val())!="" && ($("#txtLugarReparacionMtto").val())!=""
				&& ($("#txtMotivoMtto").val())!="")
			{
				$("#loaderImageG").show();
				var f  = new Date();
				var dd = f.getDate();
				var mm = (f.getMonth())+1;
				(dd<10) ? (dd="0"+dd) : dd;
				(mm<10) ? (mm="0"+mm) : mm;
				var fe  = (f.getFullYear()+"-"+mm+"-"+dd);
				var horaActual 				= new Date();
				var hora 					=horaActual.getHours();
				var minutos 				=horaActual.getMinutes();
				(minutos<10) ? (minutos="0"+minutos) : minutos;
				var hora					= hora + ":" + minutos;

				var identificadorArticulo	= $("#txtCodigoBarrasMtto").val();//obtener el articulo a dar de baja
				var observaciones 			= $("#txtMotivoMtto").val()
				var parametros 	= "opc=mantenimientoArticulos1"
								+"&identificadorArticulo="+identificadorArticulo
								+"&observaciones="+observaciones
								+"&horaMovimiento="+hora
								+"&fechaMovimiento="+fe
								+"&id="+Math.random();
				$.ajax({
					cache:false,
					type: "POST",
					dataType: "json",
					url:'../data/genericos.php',
					data: parametros,
					success: function(response){
						if(response.respuesta == true)
						{
							$("#loaderImageG").hide();
							swal("El envío a mantenimiento quedó registrado!", "Da clic en el botón OK!", "success");
							$("input").val("");
							$("textarea").val("");
						}
						else
						{
							$("#loaderImageG").hide();
							sweetAlert("Error", "No se pudo registrar el envío a mentenimiento!", "error");
						}
					},
					error: function(xhr, ajaxOptions,x)
					{
						$("#loaderImageG").hide();
						console.log("Error de conexión al registrar el mantenimiento del artículo");
					}
				});
			}
			else
			{
				$("#loaderImageG").hide();
				sweetAlert("Información incompleta", "Es posible que no haya llenado todos los campos, revise de nuevo", "warning");
			}
		}
	}
	//Inventario
	//Peticiones de articulos
	var peticionesPendientesArt = function()
	{
		if(tipoUsu == 1 || tipoUsu == 2 || tipoUsu == 5)
		{
			$("#loaderImageG").show();
			$("#altaArticulos").hide("slow");
			$("#bajaArticulos").hide("slow");
			$("#menuMtto").hide("slow");
			$("#pantallaInventario").hide("slow");
			$("#peticionesArticulos").hide("slow");
			var parametros 	= "opc=peticionesPendientesArt1"
							+"&id="+Math.random();
			$.ajax({
				cache:false,
				type: "POST",
				dataType: "json",
				url:"../data/genericos.php",
				data: parametros,
				success: function(response){
					if(response.respuesta == true)
					{
						$("#loaderImageG").hide();
						$("#tbPeticionArticulos").html("");
						$("#tbPeticionArticulos").append(response.renglones);
						$("#tbPeticionArticulos #btnAceptaPeticionArt").on("click",aceptarPeticionArt);
					}
					else
					{
						$("#loaderImageG").hide();
						sweetAlert("No hay pedidos de artículos..!", " ", "warning");
					}
				},
				error: function(xhr, ajaxOptions,x)
				{
					$("#loaderImageG").hide();
					console.log("Error de conexión peticiones pendientes");
				}
			});
			$("#peticionesPendientes").show("slow");
		}
	}
	var aceptarPeticionArt = function()
	{
		if(tipoUsu == 5)
		{
			$("#loaderImageG").show();
			var clavePedido = $(this).attr('name');
			$(this).closest('tr').remove();
			var parametros  = "opc=aceptaPeticionArticulos1"
							+"&clavePedido="+clavePedido
							+"&id="+Math.random();
			$.ajax({
				cache:false,
				type: "POST",
				dataType: "json",
				url:'../data/genericos.php',
				data: parametros,
				success: function(response){
					if(response.respuesta == true)
					{
						$("#loaderImageG").hide();
						swal("Solicitud aceptada!", "Da clic en el botón OK!", "success");
					}
					else
					{
						$("#loaderImageG").hide();
						sweetAlert("No se pudo aceptar la solicitud!", "", "warning");
					}
				},
				error: function(xhr, ajaxOptions,x)
				{
					$("#loaderImageG").hide();
					console.log("Error de conexión aceptar petición artículo");
				}
			});
		}
	}
	var peticionesArticulos = function()
	{
		if(tipoUsu == 1 || tipoUsu == 2)
		{
			$("#loaderImageG").show();
			$("#altaArticulos").hide("slow");
			$("#bajaArticulos").hide("slow");
			$("#menuMtto").hide("slow");
			$("#pantallaInventario").hide("slow");
			$("#peticionesPendientes").hide("slow");
			var parametros  = "opc=listaArtAlta"
							+"&id="+Math.random();
			$.ajax({
				cache:false,
				type: "POST",
				dataType: "json",
				url:'../data/genericos.php',
				data: parametros,
				success: function(response){
					if(response.respuesta == true)
					{
						$("#loaderImageG").hide();
						$("#cmbNombreArtPeticiones").html(" ");
						$("#cmbNombreArtPeticiones").html("<option value='' disabled selected>Selecciona el articulo</option>");
						for (var i = 0; i < response.contador; i++) 
						{
							$("#cmbNombreArtPeticiones").append($("<option></option>").attr("value",response.nombreMaterial[i]).text(response.nombreMaterial[i]));
						}
						$("#cmbNombreArtPeticiones").trigger('contentChanged');
						$('select').material_select();
					}
					else
					{
						$("#loaderImageG").hide();
					}
				},
				error: function(xhr, ajaxOptions,x)
				{
					$("#loaderImageG").hide();
					console.log("Error de conexión enviar petición artículo");
				}
			});
			$("#peticionesArticulos").show("slow");
			$("input").val("");
			$("textarea").val("");
		}
	}
	var checkOtroArticulo = function()
	{
		if ($("#chbOtroArticulo").is(':checked'))
		{
			$("#txtNombreArticuloPeticion").removeAttr("disabled");
			$(".select-dropdown").attr("disabled","disabled");
		}
		else
		{
			$("#txtNombreArticuloPeticion").attr("disabled","disabled");
			$(".select-dropdown").removeAttr("disabled");
		}
	}
	var guardaPeticionArticulo = function()
	{
		if(tipoUsu == 1 || tipoUsu == 2)
		{
			var nombreArticulo="";
			if ($("#chbOtroArticulo").is(':checked'))
			{
				nombreArticulo = $("#txtNombreArticuloPeticion").val();
			}
			else
			{
				nombreArticulo = $("#cmbNombreArtPeticiones option:selected").text();
			}
			var cantArt 	= parseInt($("#txtCantidadPeticionArt").val());
    		var maxCantArt 	= parseInt($("#txtCantidadPeticionArt").attr("max"));
    		var minCantArt 	= parseInt($("#txtCantidadPeticionArt").attr("min"));
			if ($("#txtMotivoPedidoArt").val()!="" && $("#txtMarcaPeticionArt").val()!="" && $("#txtModeloPeticionArt").val()!="" && nombreArticulo!=null)
			{
				if((cantArt >= minCantArt) && (cantArt <= maxCantArt) )
				{
					$("#loaderImageG").show();
					var f  = new Date();
					var dd = f.getDate();
					var mm = (f.getMonth())+1;
					(dd<10) ? (dd="0"+dd) : dd;
					(mm<10) ? (mm="0"+mm) : mm;
					var fe  = (f.getFullYear()+"-"+mm+"-"+dd);

					var cantidad 	= $("#txtCantidadPeticionArt").val();
					var marca 		= $("#txtMarcaPeticionArt").val();
					var modelo 		= $("#txtModeloPeticionArt").val();
					var motivo 		= $("#txtMotivoPedidoArt").val();

					var parametros= "opc=guardaPeticionArticulos1"
									+"&nombreArticulo="+nombreArticulo
									+"&cantidad="+cantidad
									+"&marca="+marca
									+"&modelo="+modelo
									+"&motivo="+motivo
									+"&fecha="+fe
									+"&id="+Math.random();
					$.ajax({
						cache:false,
						type: "POST",
						dataType: "json",
						url:'../data/genericos.php',
						data: parametros,
						success: function(response){
							if(response.respuesta == true)
							{
								$("#loaderImageG").hide();
								swal("La solicitud fue enviada con éxito!", "Da clic en el botón OK!", "success");
								$("input").val("");
								$("textarea").val("");
							}
							else
							{
								$("#loaderImageG").hide();
								sweetAlert("No se pudo enviar la solicitud!", "Es posible que la solicitu contenga datos erroneos", "warning");
							}
						},
						error: function(xhr, ajaxOptions,x)
						{
							$("#loaderImageG").hide();
							console.log("Error de conexión enviar petición artículo");
						}
					});
				}
				else
				{
					$("#loaderImageG").hide();
					sweetAlert("Seleccione una cantidad válida de artículos!", "Es posible que la cantidad de artículos sea muy grande o contenga valores negativos", "warning");
				}
			}
			else
			{
				sweetAlert("Debe llenar todos los campos"," ", "warning");
			}
		}
	}
	var atenderSolicitud = function()
	{		
		$("#solicitudesPendientes2").hide("slow");
		$("#atenderSolicitud").show("slow");
	}
	var buscarInventario = function()
	{
		if ($("#txtArticuloLista").val() == "") 
		{
			listaArticulos();
		}
	}
	//fin de las peticiones de los articulos
	
	//Reportes
	var resumenReportes=function()
	{

		$("#loaderImageG").show();
		$("#inicioG").hide();
		$("#existenciaInventario").hide("slow");
		$("#pedidoMaterial").hide("slow");
		$("#bajoInventario").hide("slow");
		$("#enReparacion").hide("slow");
		$("#enPrestamo").hide("slow");
		$("#practicasNoRealizadas").hide("slow");
		$("#practicasRealizadas").hide("slow");
		$("#practicasCanceladas").hide("slow");
		$("#resumenReportes").show("slow");
		var parametros = "opc=datosGrafica1"+
							"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response)
			{
				if(response.respuesta == true)
				{
					$("loaderImageG").hide();
					var datos = response.datos;
					google.charts.load("visualization", "1", {packages:["corechart"]});
			      	google.charts.setOnLoadCallback(dibujarGrafico);
			      	function dibujarGrafico() 
			      	{
			        	var data = google.visualization.arrayToDataTable(datos);

			        	var options = {
			          	title: 'Gráfica uso mensual '+ response.nombreLab + '(' + response.nombrePdo + ')',
			          	hAxis: {title: 'MESES', titleTextStyle: {color: 'black'}},
			          	vAxis: {title: 'VISITAS', titleTextStyle: {color: 'black'}},
			          	backgroundColor:'grey ligthten-3',
			          	legend:{position: 'bottom', textStyle: {color: 'blue', fontSize: 12}},
			          	width:950,
			            height:400
			        	};

			        	var grafico = new google.visualization.ColumnChart(document.getElementById('grafica'));
			        	grafico.draw(data, options);
		      		}
				}

			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();		
				console.log("Error de conexión");
			}
		});
		alumnosActuales();
	}
	var existenciaInventario=function()
	{
		$("#resumenReportes").hide("slow");
		$("#pedidoMaterial").hide("slow");
		$("#bajoInventario").hide("slow");	
		$("#enReparacion").hide("slow");
		$("#enPrestamo").hide("slow");
		$("#practicasNoRealizadas").hide("slow");
		$("#practicasRealizadas").hide("slow");
		$("#practicasCanceladas").hide("slow");
		$("#existenciaInventario").show("slow");
		$("#btnImprimirInventario").hide();
		resumenInventarioActual();
	}
	var bajoInventario = function()
	{
		$("#resumenReportes").hide("slow");
		$("#existenciaInventario").hide("slow");
		$("#pedidoMaterial").hide("slow");
		$("#enReparacion").hide("slow");
		$("#enPrestamo").hide("slow");
		$("#practicasNoRealizadas").hide("slow");
		$("#practicasRealizadas").hide("slow");
		$("#practicasCanceladas").hide("slow");
		$("#bajoInventario").show("slow");
		$("#btnImprimirBajoInventario").hide();
		articulosDeBaja();
	}
	var enReparacion = function()
	{
		$("#resumenReportes").hide("slow");
		$("#existenciaInventario").hide("slow");
		$("#pedidoMaterial").hide("slow");
		$("#bajoInventario").hide("slow");
		$("#enPrestamo").hide("slow");
		$("#practicasNoRealizadas").hide("slow");
		$("#practicasRealizadas").hide("slow");
		$("#practicasCanceladas").hide("slow");
		$("#enReparacion").show("slow");
		$("#btnImprimirEnReparacion").hide();
		articulosEnReparacion();

	}
	var enPrestamo = function()
	{
		$("#resumenReportes").hide("slow");
		$("#existenciaInventario").hide("slow");
		$("#pedidoMaterial").hide("slow");
		$("#bajoInventario").hide("slow");
		$("#enReparacion").hide("slow");
		$("#practicasNoRealizadas").hide("slow");
		$("#practicasRealizadas").hide("slow");
		$("#practicasCanceladas").hide("slow");
		$("#enPrestamo").show("slow");
		$("#btnImprimirEnPrestamo").hide();
		articulosEnPrestamo();

	}
	var pedidoMaterial=function()
	{
		$("#resumenReportes").hide("slow");
		$("#existenciaInventario").hide("slow");
		$("#bajoInventario").hide("slow");
		$("#enReparacion").hide("slow");
		$("#enPrestamo").hide("slow");
		$("#practicasNoRealizadas").hide("slow");
		$("#practicasRealizadas").hide("slow");
		$("#practicasCanceladas").hide("slow");
		$("#pedidoMaterial").show("slow");
		$("#btnImprimirPedidoMaterial").hide();
		articulosPedidos();

	}
	var articulosMtto=function()
	{
		$("#resumenReportes").hide("slow");
		$("#existenciaInventario").hide("slow");
		$("#bajoInventario").hide("slow");
		$("#enReparacion").hide("slow");
		$("#enPrestamo").hide("slow");
		$("#practicasNoRealizadas").hide("slow");
		$("#practicasRealizadas").hide("slow");
		$("#practicasCanceladas").hide("slow");
		$("#pedidoMaterial").hide("slow");
		$("#btnImprimirArtMtto").hide();
		articulosMantenimiento();

	}
	var practicasNoRealizadas=function()
	{
		$("#resumenReportes").hide("slow");
		$("#existenciaInventario").hide("slow");
		$("#bajoInventario").hide("slow");
		$("#enReparacion").hide("slow");
		$("#enPrestamo").hide("slow");
		$("#pedidoMaterial").hide("slow");
		$("#practicasRealizadas").hide("slow");
		$("#practicasCanceladas").hide("slow");
		$("#practicasNoRealizadas").show("slow");
		$("#btnImpPracticasNoRealizadas").hide();
		noRealizadas();
	}
	var practicasRealizadas=function()
	{
		$("#resumenReportes").hide("slow");
		$("#existenciaInventario").hide("slow");
		$("#bajoInventario").hide("slow");
		$("#enReparacion").hide("slow");
		$("#enPrestamo").hide("slow");
		$("#pedidoMaterial").hide("slow");
		$("#practicasNoRealizadas").hide("slow");
		$("#practicasCanceladas").hide("slow");
		$("#practicasRealizadas").show("slow");
		$("#btnImpPracticasRealizadas").hide();
		Realizadas();
	}
	var practicasCanceladas=function()
	{
		$("#resumenReportes").hide("slow");
		$("#existenciaInventario").hide("slow");
		$("#bajoInventario").hide("slow");
		$("#enReparacion").hide("slow");
		$("#enPrestamo").hide("slow");
		$("#pedidoMaterial").hide("slow");
		$("#practicasNoRealizadas").hide("slow");
		$("#practicasRealizadas").hide("slow");
		$("#practicasCanceladas").show("slow");
		$("#btnImpPracticasCanceladas").hide();
		Canceladas();
	}
	var alumnosActuales = function()	
	{
		$("#loaderImageG").show();
		var parametros = "opc=alumnosActuales1"+
							"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					$("#alumnosActuales").html("");
					$("#alumnosActuales").append(response.renglones);
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();		
				console.log("Error de conexión");
			}
		});
	}
	var articuloMasPrestado = function()	
	{
		$("#loaderImageG").show();
		var parametros = "opc=articuloMasPrestado1"+
		"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					$("#masSolicitado").html(" ");
					$("#masSolicitado").append(response.renglones);
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();	
				console.log("Error de conexión");
			}
		});
	}
	var proximosApartados = function()	
	{
		$("#loaderImageG").show();
		var parametros = "opc=proximosApartados1"+
		"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response)
			{
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					$("#tbProximosApartados").html(" ");
					$("#tbProximosApartados").append(response.renglones);
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión");
			}
		});
	}
	var resumenInventarioActual = function()
	{
		$("#loaderImageG").show();
		var parametros = "opc=resumenInventarioActual1"+
		"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response)
			{
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					$("#btnImprimirInventario").show();
					$("#tbInventarioActual").html(" ");
					$("#tbInventarioActual").append(response.renglones);
				}
				else
				{
					$("#loaderImageG").hide();
					sweetAlert("Sin inventario", "", "warning");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión resumen inventario");
			}
		});
	}
	var articulosDeBaja = function()
	{
		$("#loaderImageG").show();
		var parametros = "opc=enBaja1"+
						"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					$("#btnImprimirBajoInventario").show();
					$("#tbBajoInventario").html(" ");
					$("#tbBajoInventario").append(response.renglones);
				}
				else
				{
					$("#loaderImageG").hide();
					sweetAlert("Sin articulos dados de baja", "No hay articulos en reparación actualmente.", "warning");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión");
			}
		});
	}
	var articulosEnReparacion = function()
	{
		$("#loaderImageG").show();
		var parametros = "opc=enReparacion1"+
		"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					$("#btnImprimirEnReparacion").show();
					$("#tbEnReparacion").html(" ");
					$("#tbEnReparacion").append(response.renglones);
				}
				else
				{
					$("#loaderImageG").hide();
					sweetAlert("Sin articulos en reparación", "No hay articulos en reparación actualmente.", "warning");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión");
			}
		});
	}
	var articulosEnPrestamo= function()
	{
		$("#loaderImageG").show();
		var parametros = "opc=enPrestamo1"+
		"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					$("#btnImprimirEnPrestamo").show();
					$("#tbArticulosEnPrestamo").html(" ");
					$("#tbArticulosEnPrestamo").append(response.renglones);
				}
				else
				{
					$("#loaderImageG").hide();
					sweetAlert("Sin articulos en préstamo", "No hay articulos en préstamo actualmente.", "warning");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión");
			}
		});
	}
	var articulosPedidos= function()
	{
		$("#loaderImageG").show();
		var parametros = "opc=enPedido1"+
		"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					$("#btnImprimirPedidoMaterial").show();
					$("#tbPedidoMaterialReporte").html(" ");
					$("#tbPedidoMaterialReporte").append(response.renglones);
				}
				else
				{
					$("#loaderImageG").hide();
					sweetAlert("Sin articulos pedidos", "No hay articulos en peticion sin aceptar.", "warning");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión");
			}
		});
	}
	/*var articulosSinExistencia = function()
	{
		$("#loaderImageG").hide();
		var parametros = "opc=articulosSinExistencia1"+
		"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					$("#tbMaterialesSinStock").html(" ");
					$("#tbMaterialesSinStock").append(response.renglones);
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión");
			}
		});
	}*/
	
	var noRealizadas = function()
	{
		$("#loaderImageG").show();
		var parametros = "opc=practicasNoRealizadas1"+
		"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response)
			{
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					$("#btnImpPracticasNoRealizadas").show();
					$("#tbpracticasNoRealizadas").html(" ");
					$("#tbpracticasNoRealizadas").append(response.renglones);
				}
				else
				{
					$("#loaderImageG").hide();
					sweetAlert("Sin prácticas", "No hay prácticas pendientes actualmente.", "warning");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión");
			}
		});
	}
	var Realizadas = function()
	{
		$("#loaderImageG").show();
		var parametros = "opc=practicasRealizadas1"+
		"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response)
			{
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					$("#btnImpPracticasRealizadas").show();
					$("#tbpracticasRealizadas").html(" ");
					$("#tbpracticasRealizadas").append(response.renglones);
				}
				else
				{
					$("#loaderImageG").hide();
					sweetAlert("Sin prácticas", "No hay prácticas realizadas actualmente.", "warning");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión");
			}
		});
	}
	var Canceladas = function()
	{
		$("#loaderImageG").show();
		var parametros = "opc=practicasCanceladas1"+
		"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					$("#btnImpPracticasCanceladas").show();
					$("#tbpracticasCanceladas").html(" ");
					$("#tbpracticasCanceladas").append(response.renglones);
				}
				else
				{
					$("#loaderImageG").hide();
					sweetAlert("Sin prácticas", "No hay prácticas canceladas actualmente.", "warning");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();
				console.log("Error de conexión");
			}
		});
	}
	//AGREGUE
	var practicasNR = function()	
	{
		$("#loaderImageG").show();
		var parametros = "opc=practicasNR1"+
		"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/genericos.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#loaderImageG").hide();
					$("#practicasNR").html(" ");
					$("#practicasNR").append(response.renglones);
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageG").hide();	
				console.log("Error de conexión");
			}
		});
		//FIN AGREGUE
	}

	var InicioGenericos = function ()
	{
		$("#prestamos").hide();
		$("#laboratorios").hide();
		$("#inventarios").hide();
		$("#reportesGenericos").hide();
		$("#inicioG").show();
	}
	//Inicio
	$("#tabInicioG").on("click",InicioGenericos);
	//Salir
	$("#tabSalir").on("click",salir);
	//Prestamos
	$("#tabPrestamos").on("click",prestamosPendientes);
	$("#btnPendientes").on("click",prestamosPendientes);
	$("#btnEnProceso").on("click",prestamosProceso);
	$("#btnCancelarDevolucion").on("click",prestamosProceso);
	$("#btnListaSanciones").on("click",listaSanciones);
	$("#btnAplicaSancion").on("click",aplicaSancion);
	$("#btnAplicarSancion").on("click",guardaSancionAlumno);
	$("#btnAgregarArtPrestamo").on("click",agregarArticuloPrestamo);
	$("#btnRegresarSancion").on("click",devolucionPrestamo);
	$("#btnDevolucion").on("click",devolucionPrestamo);
	$("#btnFinalizarAtenderSol").on("click",guardarPrestamoPendiente);
	$("#btnCancelarAtenderSol").on("click",prestamosPendientes);
	$("#tabSolPendientesAlumnos").on("click",".eliminarPrestamo",eliminaPrestamoPendiente);
	$("#tbListaArticulosDevolucion").on("click",".devolucionArt",guardarDevolucionPrestamo);
	$("#tbListaArticulosDevolucion").on("click",".aplicaSancion",aplicaSancion);
	$("#btnDevolucionMaterial").on("click",devolucionPrestamo);
	$("#btnFinalizarDevolucion").on("click",prestamosProceso);
	//Laboratorios
	$("#tabLabs").on("click",sLaboratorioAceptadas);
	$("#btnPendientesLab").on("click",sLaboratorioPendientes);
	$("#btnAceptadasLab").on("click",sLaboratorioAceptadas);
	$("#btnRegresarVerMas").on("click",sLaboratorioPendientes);
	$("#btnRegresarVerMas2").on("click",sLaboratorioAceptadas);
	$("#btnVerMas").on("click",verMas);
	$("#btnVerMas2").on("click",verMas2);
	$("#btnAceptaSolLab").on("click",sGuardaCanderalizada);
	$("#btnCancelarSolLab").on("click",sLaboratorioPendientes);
	$("#btnNuevaLabExtenos").on("click",sLaboratorioNuevas);
	$("#btnElegirMaterialExt").on("click",elegirMatExterno);
	$("#btnRegresarExt").on("click",sLaboratorioNuevas);
	$("#btnAgregarArtExt").on("click",agregarArtExt);
	$("#cmbNombreDependencias").on("change",numeroControlLabExterno);
	$("#chbOtraDependencia").on("change",checkOtraDependencia);
	$("#cmbPracticaExterno").on("change",comboLaboratoriosExt);
	$("#cmbLaboratorioExterno").on("change",comboHoraExt);
	$("#btnFinalizarNSExt").on("click", guardaSolLabExterno);
	$("#btnNuevaPracticaG").on("click",nuevaPracticaG);
	$("#btnCancelarPracticaG").on("click", nuevaPracticaG);
	$("#btnFinalizarNPracticaG").on("click",altaNuevaPracticaG);
	$("#btnPracticasG").on("click",catalagoPracticasG);
	$(".filtroBG").on("change", llenarDatosBuscarG);
	$("#btnBuscarPracticaG").on("click",buscarPracticaG);
	//Inventario 
	$("#tabInventario").on("click",listaArticulos);
	$("#btnArticulos").on("click",listaArticulos);
	$("#btnAlta").on("click",altaArticulos);
	$("#btnAltaArt").on("click",altaInventario);
	$("#btnCancelarAlta").on("click",altaArticulos);
	$("#btnBaja").on("click",bajaArticulos);
	$("#btnCancelarBaja").on("click",bajaArticulos);
	$("#btnBajaArt").on("click",bajaInventario);
	$("#btnBuscarArtBaja").on("click",buscarArticulo);
	$("#btnMantenimiento").on("click",mantenimientoArticulos);
	$("#btnCancelarMtto").on("click",enviaArtMtto);
	$("#btnEnviaMtto").on("click",enviaArtMtto);
	$("#btnListaMtto").on("click",listaArtMtto);
	$("#btnBuscarArtMtto").on("click",buscarArticuloMtto);
	$("#btnGuardaMantenimiento").on("click",guardaMtto);
	$("#btnPeticionesPendientes").on("click",peticionesPendientesArt);
	$("#btnPeticionArticulo").on("click",peticionesArticulos);
	$("#btnCancelarPeticionArt").on("click",peticionesArticulos);
	$("#chbOtroArticulo").on("change",checkOtroArticulo);
	$("#btnEnviarPeticionArticulo").on("click",guardaPeticionArticulo);	
	$("#btnBuscarInventario").on("click",buscaArtInventario);
	$("#txtArticuloLista").on("change",buscarInventario);
	//Reportes
	$("#tabReportesGenericos").on("click",resumenReportes);
	$("#btnPracticasNoRealizadas").on("click",practicasNoRealizadas);
	$("#btnMantenimientoRpt").on("click",articulosMtto);
	$("#btnPracticasRealizadas").on("click",practicasRealizadas);
	$("#btnPracticasCanceladas").on("click",practicasCanceladas);
	$("#btnResumenReportes").on("click",resumenReportes);
	$("#tabReportesGenericos").on("click",alumnosActuales);
	$("#tabReportesGenericos").on("click",articuloMasPrestado);
	$("#tabReportesGenericos").on("click",practicasNR);/*
	$("#tabReportesGenericos").on("click",articulosSinExistencia);*/
	$("#tabReportesGenericos").on("click",proximosApartados);
	$("#btnExistenciaInventario").on("click",existenciaInventario);
	$("#btnBajoInventario").on("click",bajoInventario);
	$("#btnMaterialDañado").on("click",enReparacion);
	$("#btnMaterialEnPrestamo").on("click",enPrestamo);
	$("#btnPedidoMaterial").on("click",pedidoMaterial);

	$('.dropdown-button').dropdown({
		inDuration: 300,
		outDuration: 225,
      constrain_width: true, // Does not change width of dropdown to that of the activator
      hover: true, // Activate on hover
      gutter: 0, // Spacing from edge
      belowOrigin: false, // Displays dropdown below the button
      alignment: 'left' // Displays dropdown with edge aligned to the left of button
  });
}
$(document).on("ready",inicioGenerico);