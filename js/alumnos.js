var inicio = function()
{
	$('select').material_select();
	var articulosSolicitadosAlumnos = new Array();
	var numeroArticulos = new Array();
	var nombreArticulos = new Array();
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
				inicioRegistro();
				swal("OK..!","Aún sigues en el sistema", "warning");
			} 
		});
	}
	var uno= function()
	{
		$("#txtNControl").val($("#txtNControl").val()+1);
		str=$("#txtNControl").val();
		if(str.length==8)
		{
			if ($("#rdoAlumno").is(':checked'))
			{
				consultaAlumno(str);
			}
			else
			{
				consultaExterno(str);
			}
		}

	}
	var dos= function()
	{
		$("#txtNControl").val($("#txtNControl").val()+2);
		str=$("#txtNControl").val();
		if(str.length==8)
		{
			if ($("#rdoAlumno").is(':checked'))
			{
				consultaAlumno(str);
			}
			else
			{
				consultaExterno(str);
			}
		}
	}
	var tres= function()
	{
		$("#txtNControl").val($("#txtNControl").val()+3);
		str=$("#txtNControl").val();
		if(str.length==8)
		{
			if ($("#rdoAlumno").is(':checked'))
			{
				consultaAlumno(str);
			}
			else
			{
				consultaExterno(str);
			}
		}
	}
	var cuatro= function()
	{
		$("#txtNControl").val($("#txtNControl").val()+4);
		str=$("#txtNControl").val();
		if(str.length==8)
		{
			if ($("#rdoAlumno").is(':checked'))
			{
				consultaAlumno(str);
			}
			else
			{
				consultaExterno(str);
			}
		}
	}
	var cinco= function()
	{
		$("#txtNControl").val($("#txtNControl").val()+5);
		str=$("#txtNControl").val();
		if(str.length==8)
		{
			if ($("#rdoAlumno").is(':checked'))
			{
				consultaAlumno(str);
			}
			else
			{
				consultaExterno(str);
			}
		}
	}
	var seis= function()
	{
		$("#txtNControl").val($("#txtNControl").val()+6);
		str=$("#txtNControl").val();
		if(str.length==8)
		{
			if ($("#rdoAlumno").is(':checked'))
			{
				consultaAlumno(str);
			}
			else
			{
				consultaExterno(str);
			}
		}
	}
	var siete= function()
	{
		$("#txtNControl").val($("#txtNControl").val()+7);
		str=$("#txtNControl").val();
		if(str.length==8)
		{
			if ($("#rdoAlumno").is(':checked'))
			{
				consultaAlumno(str);
			}
			else
			{
				consultaExterno(str);
			}
		}
	}
	var ocho= function()
	{
		$("#txtNControl").val($("#txtNControl").val()+8);
		str=$("#txtNControl").val();
		if(str.length==8)
		{
			if ($("#rdoAlumno").is(':checked'))
			{
				consultaAlumno(str);
			}
			else
			{
				consultaExterno(str);			
			}
		}
	}
	var nueve= function()
	{
		$("#txtNControl").val($("#txtNControl").val()+9);
		str=$("#txtNControl").val();
		if(str.length==8)
		{
			if ($("#rdoAlumno").is(':checked'))
			{
				consultaAlumno(str);
			}
			else
			{
				consultaExterno(str);
			}
		}
	}
	var cero= function()
	{
		$("#txtNControl").val($("#txtNControl").val()+0);
		str=$("#txtNControl").val();
		if(str.length==8)
		{
			if ($("#rdoAlumno").is(':checked'))
			{
				consultaAlumno(str);
			}
			else
			{
				consultaExterno(str);
			}

		}
	}
	var ma= function()
	{
		if ($("#rdoAlumno").is(':checked'))
			{
				if($("#txtNControl").val()=="")
					$("#txtNControl").val("MA");
				else
				{
					sweetAlert("El prefijo MA solo puede ir al inicio.","", "warning");
				}
			}
			else
			{
				$("#btnMA").attr('disabled');
			}
		
	}
	var del= function()
	{
		var objEntrada = document.getElementById('txtNControl');
		if(objEntrada.value.length > 0 && objEntrada.value!="MA")
			objEntrada.value = objEntrada.value.substring(0,objEntrada.value.length-1);
		else
		{
			if(objEntrada.value=="MA")
				objEntrada.value = objEntrada.value.substring(objEntrada.value.length);
		}
	}

	//FUNCIONES PHP
	var consultaAlumno = function(str)
	{
		$("#loaderImageA").show();
		var parametros = "opc=consultaAlumno"+
		"&nControl="+str+
		"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/alumnos.php",
			data: parametros,
			success: function(response)
			{
				if(response.respuesta)
				{
					$("#loaderImageA").hide();
					document.getElementById('txtNombre').focus();
					$("#txtNombre").val(response.ALUAPP+" "+ response.ALUAPM+" "+response.ALUNOM);	
					consultaCarrera(str);
				}
				else
				{
					$("#loaderImageA").hide();
					sweetAlert("Número de control no encontrado", "", "warning");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageA").hide();
				console.log("Error de conexión");
			}
		});
	}
	var consultaExterno = function(str)
	{
		$("#loaderImageA").show();
		var parametros = "opc=consultaExterno"+
		"&nControl="+str+
		"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/alumnos.php",
			data: parametros,
			success: function(response)
			{
				if(response.respuesta)
				{
					$("#loaderImageA").hide();
					document.getElementById('txtNombre').focus();
					$("#txtNombre").val(response.encargado);
					$("#txtCarrera").val(response.dependencia);	
				}
				else
				{
					$("#loaderImageA").hide();
					sweetAlert("Número de control no encontrado", "", "warning");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageA").hide();
				console.log("Error de conexión");
			}
		});
	}
	var consultaCarrera = function(str)
	{
		$("#loaderImageA").show();
		var parametros = "opc=consultaCarrera"+
		"&nControl="+str+
		"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/alumnos.php",
			data: parametros,
			success: function(response)
			{
				if(response.respuesta)
				{
					$("#loaderImageA").hide();
					document.getElementById('txtCarrera').focus();
					$("#txtCarrera").val(response.CARNOM);
					document.getElementById('txtSemestre').focus();	
					$("#txtSemestre").val(response.CALNPE);
					$("#txtNControl").attr("disabled","disabled");
				}
				else
				{
					$("#loaderImageA").hide();
					sweetAlert("No tienes materias", "", "warning");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageA").hide();
				console.log("Error de conexión");
			}
		});
	}
	var practicaAlumnos = function()
	{
		if($("#txtNControl").val()!="" && $("#txtNombre").val()!="") 
		{
			$("#accesoAlumno").hide();
			$("#datosPracticas").show();
			$("#datosPractica2").show();
			if ($("#rdoAlumno").is(':checked'))
			{							
				var numeroControl = $("#txtNControl").val();
				$("#txtNControlAlu").val(numeroControl.substr(0,8));
				var numCtrl 	= $("#txtNControlAlu").val();
				$("#txtNombreAlu").val($("#txtNombre").val());
				$("#loaderImageA").show();
				var parametros = "opc=consultaMatAlumno"+
								"&numeroControl="+numCtrl+
								"&id="+Math.random();
				$.ajax({
					cache:false,
					type: "POST",
					dataType: "json",
					url:"../data/alumnos.php",
					data: parametros,
					success: function(response)
					{
						if(response.respuesta)
						{
		       				$("#loaderImageA").hide();
		       				$("#cmbMateriasAlumnos").html(" ");
		       				$("#cmbMaestrosMat").html(" ");
		       				$("#cmbMaestrosMat").html("<option value='' disabled selected>Selecciona el maestro</option>");
		       				$("#cmbHorariosPractica").html(" ");
		       				$("#cmbHorariosPractica").html("<option value='' disabled selected>Selecciona la hora de la práctica</option>");
		       				$("#cmbNombrePracticas").html(" ");
		       				$("#cmbNombrePracticas").html("<option value='' disabled selected>Selecciona la práctica</option>");
		       				$("#cmbMateriasAlumnos").html("<option value='' disabled selected>Selecciona la materia</option>");
							for (var i = 0; i < response.contador; i++) 
							{
								$("#cmbMateriasAlumnos").append($("<option></option>").attr("value",response.claveMateria[i]).text(response.nombreMateria[i]));
							}
							$("#cmbMateriasAlumnos").trigger('contentChanged');
							$('select').material_select();
		   				}
		   				else
		   				{
		   					$("#loaderImageA").hide();
		   					$("#cmbMateriasAlumnos").html(" ");
		       				$("#cmbMateriasAlumnos").html("<option value='' disabled selected>Selecciona la materia</option>");
		   					$('select').material_select();
		   					sweetAlert("No hay materias disponibles", "", "warning");
		   				}
		   			},
		   			error: function(xhr, ajaxOptions,x)
		   			{
		   				$("#loaderImageA").hide();
		   				console.log("Error de conexión datos practica");
		   				console.log(xhr);
		   			}
		   		});
		   	}
			else
			{
				$("#loaderImageA").hide();
				$("#accesoAlumno").hide();
				$("#datosPracticas").hide();
				$("#materialExterno").show("slow");
				consultaCalExterno();
			}
		}
		else
		{
			$("#loaderImageA").hide();
			sweetAlert("Ingrese un número de control", "", "warning");
		}
	}
	var consultaCalExterno = function()
	{
		var f  = new Date();
		var dd = f.getDate();
		var mm = (f.getMonth())+1;
		(dd<10) ? (dd="0"+dd) : dd;
		(mm<10) ? (mm="0"+mm) : mm;
		var fe  = (f.getFullYear()+"-"+mm+""+dd);
		var ncExterno 	= $("#txtNControl").val();
		$("#loaderImageA").show();
		var parametros = "opc=consultaCalExt"+
							"&fecha="+fe+
							"&ncExterno="+ncExterno+
							"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/alumnos.php",
			data: parametros,
			success: function(response)
			{
				if(response.respuesta)
				{
					$("#loaderImageA").hide();
					$("#txtCveCalExt").val(response.calendarizacion);
					$("#txtFechaActualEM").val(fe);
					$("#txtNumeroControlDep").val($("#txtNControl").val());
					$("#txtNombreEncargadoDep").val($("#txtNombre").val());
					materialPracticaExt(response.calendarizacion);
				}
				else
				{
					$("#loaderImageA").hide();
					sweetAlert("No hay practicas calendarizadas en esta fecha", "", "warning");
					inicioRegistro();
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageA").hide();
				console.log("Error de conexión");
			}
		});

	}
	var materialPracticaExt = function(calendarizacion)
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

		$("#txtFechaActualEM").val(fe);
		var claveCal 			= calendarizacion;
		var ncExterno 			= $("#txtNControl").val();
		var nombreDep 			= $("#txtNombre").val();
		$("#bodyArtExterno").html("");
		$("#txtNumeroControlDep").val(ncExterno);
		$("#txtNombreEncargadoDep").val(nombreDep);
		$("#loaderImageA").show();
		var parametros 			= "opc=consultaMaterialExterno"+
									"&fecha="+fe+
									"&hora="+hora+
									"&nC="+ncExterno+
									"&calendarizacion="+claveCal+
									"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/alumnos.php",
			data: parametros,
			success: function(response)
			{
				if(response.respuesta)
				{
					$("#loaderImageA").hide();
					articulosSolicitadosAlumnos = (response.materiales).split(",");
					numeroArticulos = (response.cantidad).split(",");
					nombreArticulos = (response.nombre).split(",");
					$("#bodyArtExterno").html("");
					$("#bodyArtExterno").append(response.renglones);
					$("#bodyArtExterno #btnEliminarArtExt").on("click",eliminarArtExterno);
					/*for (var i = 0; i < response.contador; i++) 
					{
						articulosSolicitadosAlumnos.push(response.materiales[i]);
						numeroArticulos.push(response.cantidad[i]);
						nombreArticulos.push(response.nombre[i]);
					}*/
				}
				else
				{
					$("#loaderImageA").hide();
					$("#bodyArtExterno").html("");
					sweetAlert("No hay material asignado para la práctica", "", "warning");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageA").hide();
				console.log("Error de conexión");
			}
		});

	}
	var materialPractica = function()
	{
		//fecha del sistema
		var f  = new Date();
		var dd = f.getDate();
		var mm = (f.getMonth())+1;
		(dd<10) ? (dd="0"+dd) : dd;
		(mm<10) ? (mm="0"+mm) : mm;
		var fe  = (f.getFullYear()+"-"+mm+"-"+dd);

		$("#bodyArtAlumno").html("");
		articulosSolicitadosAlumnos = new Array();
		nombreArticulos = new Array();
		numeroArticulos = new Array();
		$("#datosPractica2").hide();
		$("#materialAlumno").show("slow");
		$("#txtNumeroControlPrestamo").val($("#txtNControlAlu").val());
		$("#txtNombreAluPrestamo").val($("#txtNombreAlu").val());
		var nc 		=$("#txtNumeroControlPrestamo").val();
		var claveCal = $("#cmbHorariosPractica").val();
		
		$("#loaderImageA").show();
		var parametros = "opc=consultaMaterialPractica1"+
							"&clave="+claveCal+
							"&nC="+nc+
							"&fecha="+fe+
							"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/alumnos.php",
			data: parametros,
			success: function(response)
			{
				if(response.respuesta)
				{
					$("#loaderImageA").hide();
					//hacer arreglos
					articulosSolicitadosAlumnos = (response.materiales).split(",");
					numeroArticulos = (response.cantidad).split(",");
					nombreArticulos = (response.nombre).split(",");
					$("#bodyArtAlumno").html("");
					$("#bodyArtAlumno").append(response.renglones);
					$("#bodyArtAlumno #btnEliminarArtAlu").on("click",eliminarArtAlumno);
				}
				else
				{
					$("#loaderImageA").hide();
					$("#bodyArtAlumno").html("");
					//sweetAlert("No hay material asignado para la práctica", "", "warning");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageA").hide();
				console.log("Error de conexión");
				console.log(xhr);
			}
		});

	}
	var maestroPractica = function()
	{
		$("#loaderImageA").show();
		var claveMateria = $("#cmbMateriasAlumnos").val();
		var parametros = "opc=consultaMaestro"+
		"&claveMateria="+claveMateria+
		"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/alumnos.php",
			data: parametros,
			success: function(response)
			{
				if(response.respuesta)
				{
   					$("#loaderImageA").hide();
   					$("#cmbMaestrosMat").html(" ");
   					$("#cmbMaestrosMat").html("<option value='' disabled selected>Selecciona el maestro</option>");
						for (var i = 0; i < response.contador; i++) 
						{
							$("#cmbMaestrosMat").append($("<option></option>").attr("value",response.claveMaestro[i]).text(response.nombreMaestro[i]));
						}
						$("#cmbMaestrosMat").trigger('contentChanged');
						$('select').material_select();
   				}
   				else
   				{
   					$("#loaderImageA").hide();
   					$("#cmbMaestrosMat").html("<option value='' disabled selected>Selecciona el maestro</option>");
   					$("#cmbMaestrosMat").trigger('contentChanged');
					$('select').material_select();
   					sweetAlert("Maestro incorrecto", "", "warning");
   				}
   			},
   			error: function(xhr, ajaxOptions,x)
   			{
   				$("#loaderImageA").hide();
   				console.log("Error de conexión");
   			}
   		});
	}
	var nombrePracticaMaestro = function()
	{
		$("#loaderImageA").show();
		var horaPrac 	= $("#cmbHorariosPractica").val();
		var parametros 	= "opc=consultaPracticaNombre"+
							"&horaPrac="+horaPrac+
							"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/alumnos.php",
			data: parametros,
			success: function(response)
			{
				if(response.respuesta)
				{
   					$("#loaderImageA").hide();
   					$("#cmbNombrePracticas").html(" ");
   					$("#cmbNombrePracticas").html("<option value='' disabled selected>Selecciona la práctica</option>");
					$("#cmbNombrePracticas").append($("<option></option>").attr("value",response.clavePractica).text(response.nombrePractica));
					$("#cmbNombrePracticas").trigger('contentChanged');
					$('select').material_select();
   				}
   				else
   				{
   					$("#loaderImageA").hide();
   					$("#cmbNombrePracticas").html(" ");
   					$("#cmbNombrePracticas").html("<option value='' disabled selected>Selecciona la práctica</option>");
   					$("#cmbNombrePracticas").trigger('contentChanged');
					$('select').material_select();
   					sweetAlert("No hay practicas asignadas a esa hora", "", "warning");
   				}
   			},
   			error: function(xhr, ajaxOptions,x)
   			{
   				$("#loaderImageA").hide();
   				console.log("Error de conexión");
   			}
   		});
	}
	var horarioPractica = function()
	{
		//fecha del sistema
		var f  = new Date();
		var dd = f.getDate();
		var mm = (f.getMonth())+1;
		(dd<10) ? (dd="0"+dd) : dd;
		(mm<10) ? (mm="0"+mm) : mm;
		var fe  = (f.getFullYear()+"-"+mm+"-"+dd);
		var claveMaestro = $("#cmbMaestrosMat").val();
		$("#loaderImageA").show();
		var parametros 		= "opc=consultaHoraPractica"+
								"&claveMaestro="+claveMaestro+
								"&fecha="+fe+
								"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/alumnos.php",
			data: parametros,
			success: function(response)
			{
				$("#cmbHorariosPractica").html(" ");
				$("#cmbHorariosPractica").html("<option value='' disabled selected>Selecciona la hora de la práctica</option>");
				if(response.respuesta)
				{
   						$("#loaderImageA").hide();
   						$("#cmbHorariosPractica").append($("<option></option>").attr("value",response.clavePractica).text(response.horaPractica));
						$("#cmbHorariosPractica").trigger('contentChanged');
						$('select').material_select();
   				}
   				else
   				{
   					$("#loaderImageA").hide();
   					sweetAlert("El maestro no tiene prácticas", "", "warning");

   				}
   			},
   			error: function(xhr, ajaxOptions,x)
   			{
   				$("#loaderImageA").hide();
   				console.log("Error de conexión");
   			}
   		});
	}
	var guardaEntradaExterno = function()
	{
		var ncE = $("#txtNumeroControlDep").val();
		var cal = $("#txtCveCalExt").val();
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
		var horaEntrada			= hora + ":" + minutos;
		$("#loaderImageA").show();
		var parametros 	= "opc=guardaEntradaExt"+
						"&nControl="+ncE+
						"&calendarizacion="+cal+
						"&fecha="+fe+
						"&hora="+horaEntrada+
						"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/alumnos.php",
			data: parametros,
			success: function(response)
			{
				if(response.respuesta)
				{
   					$("#loaderImageA").hide();
   					if($("#chbElegirMaterial").is(':checked'))
   					{
   						sweetAlert("Registro de entrada guardado con éxito!", "Da click en el botón OK", "success");
   						materialPractica();
   					}
   					else
   					{
   						sweetAlert("Registro de entrada guardado con éxito!", "Da click en el botón OK", "success");
   						inicioRegistro();
   					}
   				}
   				else
   				{
   					$("#loaderImageA").hide();
   					sweetAlert("No se registró la entrada", "", "warning");
   				}
   			},
   			error: function(xhr, ajaxOptions,x)
   			{
   				$("#loaderImageA").hide();
   				console.log("Error de conexión registro de entrada alumnos");
   			}
   		});		


	}
	var guardaEntradaAlumno = function()
	{
		if (($("#cmbNombrePracticas").val()) != "") 
		{
			var nc   		= $("#txtNControlAlu").val();
			var claveCal 	= $("#cmbNombrePracticas").val();
		}
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
		var horaEntrada			= hora + ":" + minutos;
		$("#loaderImageA").show();
		var parametros 	= "opc=guardaEntrada1"+
						"&claveCal="+claveCal+
						"&nControl="+nc+
						"&fecha="+fe+
						"&hora="+horaEntrada+
						"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/alumnos.php",
			data: parametros,
			success: function(response)
			{
				if(response.respuesta)
				{
   					$("#loaderImageA").hide();
   					if($("#chbElegirMaterial").is(':checked'))
   					{
   						sweetAlert("Registro de entrada guardado con éxito!", "Da click en el botón OK", "success");
   						materialPractica();
   					}
   					else
   					{
   						sweetAlert("Registro de entrada guardado con éxito!", "Da click en el botón OK", "success");
   						inicioRegistro();
   					}
   				}
   				else
   				{
   					$("#loaderImageA").hide();
   					sweetAlert("No se registró la entrada", "", "warning");
   				}
   			},
   			error: function(xhr, ajaxOptions,x)
   			{
   				$("#loaderImageA").hide();
   				console.log("Error de conexión registro de entrada alumnos");
   			}
   		});
	}
	var cancelaEntrada = function ()
	{
		$("#txtNControl").val("");
		$("#txtNombre").val("");
		$("#txtCarrera").val("");
		$("#txtSemestre").val("");
		$("#txtNControl").removeAttr("disabled");
		del();
	}
	var agregaArtAlumno = function()
	{
		var articuloCve = $("#cmbMaterialesLab" ).val();
		var artNom 		= $("#cmbMaterialesLab option:selected").text();
    	var numArt    	= $("#txtNumArtMat").val();
    	nombreArticulos.push(artNom);
		numeroArticulos.push(numArt);
    	articulosSolicitadosAlumnos.push(articuloCve); 	
    	//construir la tabla de nuevo con los articulos que estan guardados
		construirTablaAlu();
	}
	var agregaArtExterno = function()
	{
		var articuloCve = $("#cmbMaterialesLabDep" ).val();
		var artNom 		= $("#cmbMaterialesLabDep option:selected").text();
    	var numArt    	= $("#txtNumArtMatDep").val();
    	nombreArticulos.push(artNom);
		numeroArticulos.push(numArt);
    	articulosSolicitadosAlumnos.push(articuloCve); 	
    	//construir la tabla de nuevo con los articulos que estan guardados
		construirTablaExt();
	}
	//función para llenar el combo de materiales, omitiendo los que estan en la tabla
	var checkOtroArticulo = function()
	{
		var claveSol = $("#cmbHorariosPractica").val();
		comboclaArt = "";
    	comboArt 	= "";
		if ($("#chbElegirOtroMaterial").is(':checked'))
		{
			$(".select-dropdown").removeAttr("disabled");
			$("#cmbMaterialesLab").removeAttr("disabled");
			$("#txtNumArtMat").removeAttr("disabled");
			$("#btnAgregarArtAlu").show();
			$("#loaderImageA").show();
			var parametros = "opc=materialesDisponibles1"+
							"&claveSol="+claveSol+
    						"&id="+Math.random();
    		var c = articulosSolicitadosAlumnos.length;
			$.ajax({
	    		cache:false,
	    		type: "POST",
	    		dataType: "json",
	    		url:"../data/alumnos.php",
	    		data: parametros,
	    		success: function(response)
	    		{
	    			if(response.respuesta == true)
	    			{
	    				$("#loaderImageA").hide();
	    				$("#cmbMaterialesLab").html(" ");
						$("#cmbMaterialesLab").html("<option value='' disabled selected>Selecciona el material</option>");
						comboclaArt = response.claveArticulo;
    					comboArt 	= response.nombreArticulo;
    					//eliminar elementos repetidos
						for (var r =0; r< c; r++) 
						{
							o = (articulosSolicitadosAlumnos[r]);
							i = parseInt((comboclaArt).indexOf(o));
							comboclaArt = (eliminar2(comboclaArt,i));
							comboArt 	= (eliminar2(comboArt,i));				
						}
						console.log(comboArt);
						console.log(comboArt[0]);
						var con = comboclaArt.length;
						$("#cmbMaterialesLab").html(" ");
						$("#cmbMaterialesLab").html("<option value='' disabled selected>Selecciona el material</option>");
						for (var i = 0; i < con; i++) 
						{
							$("#cmbMaterialesLab").append($("<option></option>").attr("value",comboclaArt[i]).text(comboArt[i]));
						}
						$("#cmbMaterialesLab").trigger('contentChanged');
						$('select').material_select();
					}
					else
					{
						$("#loaderImageA").hide();
						$("#cmbMaterialesLab").html(" ");
						$("#cmbMaterialesLab").html("<option value='' disabled selected>Selecciona el material</option>");
						sweetAlert("No hay mas articulos en el laboratorio", "", "warning");
					}
				},
				error: function(xhr, ajaxOptions,x)
				{
					$("#loaderImageA").hide();
					console.log("Error de conexión");
					console.log(xhr);
				}
			});
		}
		else
		{
			$("#loaderImageA").hide();
			$("#btnAgregarArtAlu").hide();
			$("#txtNumArtMat").attr("disabled","disabled");
			$(".select-dropdown").attr("disabled","disabled");
			$("#cmbMaterialesLab").attr("disabled","disabled");
		}
	}
	var checkOtroArticuloDep = function()
	{
		var claveSol = 9;//$("#cmbHorariosPractica").val();
		comboclaArt = "";
    	comboArt 	= "";
		if ($("#chbElegirOtroMaterialDep").is(':checked'))
		{
			$(".select-dropdown").removeAttr("disabled");
			$("#cmbMaterialesLabDep").removeAttr("disabled");
			$("#txtNumArtMatDep").removeAttr("disabled");
			$("#btnAgregarArtDep").show();
			$("#loaderImageA").show();
			var parametros = "opc=materialesDisponibles1"+
							"&claveSol="+claveSol+
    						"&id="+Math.random();
    		var c = articulosSolicitadosAlumnos.length;
			$.ajax({
	    		cache:false,
	    		type: "POST",
	    		dataType: "json",
	    		url:"../data/alumnos.php",
	    		data: parametros,
	    		success: function(response)
	    		{
	    			if(response.respuesta == true)
	    			{
	    				$("#loaderImageA").hide();
	    				$("#cmbMaterialesLabDep").html(" ");
						$("#cmbMaterialesLabDep").html("<option value='' disabled selected>Selecciona el material</option>");
						comboclaArt = response.claveArticulo;
    					comboArt 	= response.nombreArticulo;
    					//eliminar elementos repetidos
						for (var r =0; r< c; r++) 
						{
							o = (articulosSolicitadosAlumnos[r]);
							i = parseInt((comboclaArt).indexOf(o));
							comboclaArt = (eliminar2(comboclaArt,i));
							comboArt 	= (eliminar2(comboArt,i));				
						}
						console.log(comboArt);
						console.log(comboArt[0]);
						var con = comboclaArt.length;
						$("#cmbMaterialesLabDep").html(" ");
						$("#cmbMaterialesLabDep").html("<option value='' disabled selected>Selecciona el material</option>");
						for (var i = 0; i < con; i++) 
						{
							$("#cmbMaterialesLabDep").append($("<option></option>").attr("value",comboclaArt[i]).text(comboArt[i]));
						}
						$("#cmbMaterialesLabDep").trigger('contentChanged');
						$('select').material_select();
					}
					else
					{
						$("#loaderImageA").hide();
						$("#cmbMaterialesLabDep").html(" ");
						$("#cmbMaterialesLabDep").html("<option value='' disabled selected>Selecciona el material</option>");
						sweetAlert("No hay mas articulos en el laboratorio", "", "warning");
					}
				},
				error: function(xhr, ajaxOptions,x)
				{
					$("#loaderImageA").hide();
					console.log("Error de conexión");
					console.log(xhr);
				}
			});
		}
		else
		{
			$("#loaderImageA").hide();
			$("#btnAgregarArtDep").hide();
			$("#txtNumArtMatDep").attr("disabled","disabled");
			$(".select-dropdown").attr("disabled","disabled");
			$("#btnAgregarArtDep").attr("disabled","disabled");
		}
	}		
	var eliminarArtAlumno = function()
	{
    	var artEliminar = ($(this).attr('name'));
    	console.log("eliminar "+artEliminar);
    	var i = articulosSolicitadosAlumnos.indexOf(artEliminar);
    	nombreArticulos = eliminar2(nombreArticulos,i);
    	articulosSolicitadosAlumnos = eliminar2(articulosSolicitadosAlumnos,i);
    	numeroArticulos = eliminar2(numeroArticulos,i);
    	//construir la tabla de nuevo con los articulos que estan guardados
		construirTablaAlu();
	}
	var eliminarArtExterno = function()
	{
		var artEliminar = ($(this).attr('name'));
    	console.log("eliminar "+artEliminar);
    	var i = articulosSolicitadosAlumnos.indexOf(artEliminar);
    	nombreArticulos = eliminar2(nombreArticulos,i);
    	articulosSolicitadosAlumnos = eliminar2(articulosSolicitadosAlumnos,i);
    	numeroArticulos = eliminar2(numeroArticulos,i);
    	//construir la tabla de nuevo con los articulos que estan guardados
		construirTablaExt();
	}
	//función para construir la tabla con los materiales
	var construirTablaAlu = function()
	{
		$("#loaderImageA").show();
		var parametros = "opc=construirTablaArt1"+
				    	"&articulosSolicitados="+articulosSolicitadosAlumnos+
				    	"&nombreArticulos="+nombreArticulos+
				    	"&numeroArticulos="+numeroArticulos+
				    	"&id="+Math.random();
    	$.ajax({
    		cache:false,
    		type: "POST",
    		dataType: "json",
    		url:"../data/alumnos.php",
    		data: parametros,
    		success: function(response){
    			if(response.respuesta == true)
    			{
    				$("#loaderImageA").hide();
    				$("#bodyArtAlumno").html("");
    				$("#bodyArtAlumno").append(response.renglones);
    				//formar de nuevo el combo
    				checkOtroArticulo();
    				$("#bodyArtAlumno #btnEliminarArtAlu").on("click",eliminarArtAlumno);			
				}//termina if
				else
				{
					$("#loaderImageA").hide();
					$("#bodyArtAlumno").html("");
					checkOtroArticulo();
					//sweetAlert("No se eliminó el articulo", "", "warning");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageA").hide();
				console.log("Error de conexión articulo eliminar Alu");	
				console.log(xhr);
			}
		});
	}
	var construirTablaExt = function()
	{
		$("#loaderImageA").show();
		var parametros = "opc=construirTablaExt1"+
				    	"&articulosSolicitados="+articulosSolicitadosAlumnos+
				    	"&nombreArticulos="+nombreArticulos+
				    	"&numeroArticulos="+numeroArticulos+
				    	"&id="+Math.random();
    	$.ajax({
    		cache:false,
    		type: "POST",
    		dataType: "json",
    		url:"../data/alumnos.php",
    		data: parametros,
    		success: function(response){
    			if(response.respuesta == true)
    			{
    				$("#loaderImageA").hide();
    				$("#bodyArtExterno").html("");
    				$("#bodyArtExterno").append(response.renglones);
    				//formar de nuevo el combo
    				checkOtroArticulo();
    				$("#bodyArtExterno #btnEliminarArtExt").on("click",eliminarArtExterno);			
				}//termina if
				else
				{
					$("#loaderImageA").hide();
					$("#bodyArtExterno").html("");
					checkOtroArticulo();
					//sweetAlert("No se eliminó el articulo", "", "warning");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageA").hide();
				console.log("Error de conexión articulo eliminar Alu");	
				console.log(xhr);
			}
		});
	}
	//funcion para eliminar un elemento según su posición de un arreglo
	var eliminar2 = function(arreglo,posicion)
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
	var guardaSolMaterial = function ()
	{
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
		(minutos<10) ? (minutos="0"+minutos) : minutos;
		var hora					= hora + ":" + minutos;

		var listaArt 		= articulosSolicitadosAlumnos;
		var cantArt 		= numeroArticulos;
		var nc 				=$("#txtNumeroControlPrestamo").val();
		var claveCal 		= $("#cmbHorariosPractica").val();
		$("#loaderImageA").show();
		var parametros 		= "opc=guardaSolicitudAlu"+
							"&listaArt="+listaArt+
							"&cantArt="+cantArt+
							"&claveCal="+claveCal+
							"&numC="+nc+
							"&fecha="+fe+
							"&hora="+hora+
    						"&id="+Math.random();
		$.ajax({
    		cache:false,
    		type: "POST",
    		dataType: "json",
    		url:"../data/alumnos.php",
    		data: parametros,
    		success: function(response){
    			if(response.respuesta == true)
    			{
    				$("#loaderImageA").hide();
    				inicioRegistro();
    				swal("Solicitud enviada con éxito!", "Da clic en el botón OK!", "success");
				}
				else
				{
					$("#loaderImageA").hide();
					sweetAlert("No fue posible enviar la solicitud", "", "warning");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageA").hide();
				console.log("Error de conexión");
				console.log(xhr)	;
			}
		});

	}
	var guardaSolMatExt = function()
	{
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
		(minutos<10) ? (minutos="0"+minutos) : minutos;
		var horaEntrada				= hora + ":" + minutos;
		var listaArt 		= articulosSolicitadosAlumnos;
		var cantArt 		= numeroArticulos;
		var claveCal 		= $("#txtCveCalExt").val();
		var nc 				= $("#txtNumeroControlDep").val();
		$("#loaderImageA").show();
		var parametros = "opc=guardaSolicitudExterno"+
							"&listaArt="+listaArt+
							"&cantArt="+cantArt+
							"&claveCal="+claveCal+
							"&fecha="+fe+
							"&hora="+horaEntrada+
							"&numC="+nc+
    						"&id="+Math.random();
		$.ajax({
    		cache:false,
    		type: "POST",
    		dataType: "json",
    		url:"../data/alumnos.php",
    		data: parametros,
    		success: function(response)
    		{
    			if(response.respuesta)
    			{
    				$("#loaderImageA").hide();
    				swal("Solicitud enviada con éxito!", "Da clic en el botón OK!", "success");
    				inicioRegistro();
				}
				else
				{
					$("#loaderImageA").hide();
					sweetAlert("No se envió la solicitud de material", "", "warning");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImageA").hide();
				console.log("Error de conexión");
			}
		});
	}
 	var inicioRegistro = function ()
 	{
 		//limpiar variables
 		$("#bodyArtAlumno").html("");
 		$("#chbElegirMaterial").removeAttr("checked");
 		$("#chbElegirOtroMaterial").removeAttr("checked");
 		$("#btnAgregarArtAlu").hide();
		articulosSolicitadosAlumnos = Array();
		nombreArticulos				= Array();
		numeroArticulos 			= Array();
 		$("#materialExterno").hide();
 		$("#datosPracticas").hide();
 		$("#datosPractica2").hide();
 		$("#materialAlumno").hide();
 		$("#accesoAlumno").show();
 		$("#txtNControl").val("");
		$("#txtNombre").val("");
		$("#txtCarrera").val("");
		$("#txtSemestre").val("");
		$("#txtNControl").removeAttr("disabled");
		del();

 	}
	$("#btnPracticaAlumnos").on("click",practicaAlumnos);
	$("#btnMaterialAlumno").on("click",materialPractica);

	//Botones teclado numerico
	$("#btn1").on("click",uno);
	$("#btn2").on("click",dos);
	$("#btn3").on("click",tres);
	$("#btn4").on("click",cuatro);
	$("#btn5").on("click",cinco);
	$("#btn6").on("click",seis);
	$("#btn7").on("click",siete);
	$("#btn8").on("click",ocho);
	$("#btn9").on("click",nueve);
	$("#btn0").on("click",cero);
	$("#btnMA").on("click",ma);
	$("#btnDel").on("click",del);
	//selects
	$("#chbElegirOtroMaterial").on("change",checkOtroArticulo);
	$("#chbElegirOtroMaterialDep").on("change",checkOtroArticuloDep)
	$("#cmbMateriasAlumnos").on("change",maestroPractica);
	$("#cmbMaestrosMat").on("change",horarioPractica);
	$("#cmbHorariosPractica").on("change",nombrePracticaMaestro );
	$("#btnEntradaAlumno").on("click",guardaEntradaAlumno);
	$("#btnAceptarSinMat").on("click",guardaEntradaExterno);
	$("#btnCancelarEntrada").on("click",cancelaEntrada);
	$("#btnAgregarArtAlu").on("click",agregaArtAlumno);
	$("#btnAgregarArtDep").on("click",agregaArtExterno);
	$("#btnAceptarEleccionMat").on("click",guardaSolMaterial);
	$("#btnCancelarEleccionMat").on("click",inicioRegistro);
	$("#btnCancelarEleccionMatExt").on("click",inicioRegistro);
	$("#btnAceptarEleccionMatDep").on("click",guardaSolMatExt);
	$("#btnSalirLaboratorio").on("click",salir);
	$("#btnCancelarDatosPractica").on("click",inicioRegistro);
	$("#rdoAlumno").on("change",inicioRegistro);
	$("#rdoExterno").on("change",inicioRegistro);
}
$(document).on("ready",inicio);
