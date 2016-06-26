var inicioMaestros = function ()
{
	$('ul.tabs').tabs();
	$('select').material_select(); //agregado
	var articulos = new Array();
	var articulosAgregados = new Array();
	var numArticulos = new Array();
	var articulosE = new Array();
	var articulosAgregadosE = new Array();
	var numArticulosE = new Array();

	var opcion 	= "";
    var valor 	= ""; 
	//eventos menu Solicitudes

	//Empieza función salir del sistema
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
				inicioTab();
				swal("OK..!","Aún sigues en el sistema", "warning");
			} 
		});
	}//Termina función salir del sistema
	var inicioTab = function()
	{
		$("#sNuevaMaestro").hide();
		$("#sAceptadasMaestro").hide();
		$("#sPendientesMaestro").hide();
		$("#sRealizadas").hide();
		$("#inicioma").show();
		$("#inicioMaestro").show();
	}
	var fechaActual = function()
	{
		var f  = new Date();
    	var dd = f.getDate();
    	var mm = (f.getMonth())+1;
    	(dd<10) ? (dd="0"+dd) : dd;
    	(mm<10) ? (mm="0"+mm) : mm;
    	var fe  = (f.getFullYear()+"-"+mm+"-"+dd);
    	return fe;	
	}
	//Empieza función de solicitudes Aceptadas
	var solAceptadas = function()
	{
		$("#loaderImage").show();
		$("#tbSolAceptadas").html(" ");
		//ocultar los div
		$("#sNuevaMaestro").hide();
		$("#sPendientesMaestro").hide();
		$("#sRealizadas").hide();
		$("#inicioMaestro").hide();
		//contenido dinamico
		var parametros = "opc=solicitudesAceptadas1"+
		"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/maestros.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#loaderImage").hide();
					$("#tbSolAceptadas").html(" ");
					$("#tbSolAceptadas").append(response.renglones);
					$("#tbSolAceptadas a").on("click",practicaRealizada);
				}
				else
				{
					$("#loaderImage").hide();
					sweetAlert("No hay solicitudes aceptadas..!", "Debe crear una solicitud antes", "warning");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImagen").hide();
				console.log("Error de conexión sol aceptadas");
			}
		});
		$("#sAceptadasMaestro").show("slow");
	}//Termina función de solicitudes Aceptadas

	//Empieza función para liberar una solicitud realizada a aceptadas
	var practicaRealizada = function()
	{
		$("#loaderImage").show();
		//contenido dinamico
		
		var atr     = $(this).attr("name");
		var atrs 	= atr.split(",");
		var realid  = atrs[0];
		var fecha   = atrs[1];
		var fechaAc = fechaActual();
		if(fechaAc > fecha)
		{		
			var parametros = "opc=liberarPractica1"+
			"&clave="+realid+
			"&id="+Math.random();
			$.ajax({
				cache:false,
				type: "POST",
				dataType: "json",
				url:"../data/maestros.php",
				data: parametros,
				success: function(response)
				{
					if(response.respuesta == true)
					{
						$("#loaderImage").hide();
						solAceptadas();
						swal("Practica realizada..!", "Buen trabajo..!", "success");
					}
					else
					{
						$("#loaderImage").hide();
						sweetAlert("No existe esa solicitud..!", "", "warning");
					}
				},
				error: function(xhr, ajaxOptions,x)
				{
					$("#loaderImage").hide();
					console.log("Error de conexión realizadas");
				}
			});
		}
		else
		{
			$("#loaderImage").hide();
			swal("No se puede liberar la práctica..!", "Es posible que la fecha u hora de la práctica aún no alla pasado..!", "warning");
		}	
	}//Termina función para liberar una solicitud realizada a aceptadas
	
	//Empieza función de solicitudes pendientes
	var solPendientes = function()
	{
		$("#tabSolPendientes").html("");
		$("#sNuevaMaestro").hide();
		$("#sAceptadasMaestro").hide();
		$("#editarSolicitudLab").hide();
		$("#editarSol").hide();
		$("#sRealizadas").hide();
		$("#loaderImage").show();
		//Contenido Dinamico
		var parametros = "opc=solicitudesPendientes1"+
		"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/maestros.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#loaderImage").hide();
					$("#tabSolPendientes").html("");
					$("#tabSolPendientes").append(response.renglones);
					//$("#tabSolPendientes").on("click", ".btnEditarSolicitudLab" , editarSolicitudLab);
					//$("#tabSolPendientes").on("click", ".btnEliminarSolicitudLab" , eliminarSolicitud);
				}
				else
				{
					$("#loaderImage").hide();
					sweetAlert("No hay solicitudes pendientes..!", "Han aceptado todas tus solicitudes o no ha enviado ninguna solicitud", "warning");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImage").hide();
				console.log("Error de conexión pendientes");	
			}
		});
		$("#sPendientesMaestro").show("slow");
		$("#solicitudesPendientesLab").show("slow");
		//modifique
		$("#tabSolPendientes").on("click", ".btnEditarSolicitudLab" , editarSolicitudLab);
		$("#tabSolPendientes").on("click", ".btnEliminarSolicitudLab" , eliminarSolicitud);	
	}//Termina función de solicitudes pendientes
	
	//Empieza función de solicitudes realizadas
	var solRealizadas = function()
	{
		$("#loaderImage").show();
		//ocultar los div
		$("#sNuevaMaestro").hide();
		$("#sAceptadasMaestro").hide();
		$("#sPendientesMaestro").hide();
		$("#sRealizadas").hide();
		//contenido dinamico
		var parametros = "opc=solicitudesRealizadas1"+
		"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/maestros.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#loaderImage").hide();
					$("#tbSolRealizadas").html(" ");
					$("#tbSolRealizadas").append(response.renglones);
				}
				else
				{
					$("#loaderImage").hide();
					sweetAlert("No hay solicitudes realizadas..!", "Debes liberar la práctica realizada", "warning");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImage").hide();
				console.log("Error de conexión srealizadas");	
			}
		});
		$("#sRealizadas").show("slow");
	}//Termina función de solicitudes realizadas
	
	//Empieza función de crear nueva solicitud
	var solNueva = function()
	{
		$("#sAceptadasMaestro").hide();
		$("#sPendientesMaestro").hide();
		$("#sRealizadas").hide();
		$("#eleccionMaterial").hide();
		$("#loaderImage").show();
		$("#sNuevaMaestro").show("slow");
		$("#nuevaMaestro").show("slow");
    	var fe  = fechaActual();
		$("#txtFechaS").val(fe);
		$("#txtFechaS").attr("min",fe);
		//modificación combo
		var parametros = "opc=comboMat1"+
		"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/funciones.php",
			data: parametros,
			success: function(response){
				if(response.respuesta == true)
				{
					$("#txtFechaS").attr("max",response.fecha);
					$("#loaderImage").hide();
					$("#cmbMateria").html(" ");
					$("#cmbMateria").html("<option value='' disabled selected>Seleccione la materia</option>");
					for (var i = 0; i < response.contador; i++) 
					{
						$("#cmbMateria").append($("<option></option>").attr("value",response.claveMat[i]).text(response.nombreMat[i]));
					}
					$("cmbMateria").trigger('contentChanged');
					$('select').material_select();
					//limpiar campos

					//Combo hora materia
					$("#cmbHoraMat").html(" ");
					$("#cmbHoraMat").html("<option value='' disabled selected>Seleccione la hora</option>");
					$('select').material_select();
					//combo Practica
					$("#cmbPractica").html(" ");
					$("#cmbPractica").html("<option value='' disabled selected>Seleccione la práctica</option>");	
					$('select').material_select();
					//combo laboratorio
					$("#cmbLaboratorio").html(" ");
					$("#cmbLaboratorio").html("<option value='' disabled selected>Seleccione el laboratorio</option>");
					$('select').material_select();
					//Combo hora practica
					$("#cmbHoraPract").html(" ");
					$("#cmbHoraPract").html("<option value='' disabled selected>Seleccione la hora</option>");
					$('select').material_select();
					//txt
					$("#txtFechaS").val(fe);									
					$("#txtCantAlumnos").val("1");
					$("#textarea1").val("");
					$("#txtNumArt").val("1");
					articulos = Array();
					articulosAgregados = Array();
					numArticulos = Array();
					$("#bodyArt").html("");
				}
				else
				{
					$("#loaderImage").hide();
					$("#cmbMateria").html(" ");
					$("#cmbMateria").html("<option value='' disabled selected>Seleccione la materia</option>");
					$('select').material_select();
					sweetAlert("No tiene materias", "Es posible que no tenga materias asignadas!", "warning");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImage").hide();
				console.log("Error de conexión combomat");	
			}
		});
    }//Termina función de crear nueva solicitud
    
    //Empieza función de elegir material
    var elegirMaterial = function()
    {
    	var cantAlu = parseInt($("#txtCantAlumnos").val());
    	var maxCantAlu = parseInt($("#txtCantAlumnos").attr("max"));
    	var minCantAlu = parseInt($("#txtCantAlumnos").attr("min"));
    	//var fecha = 
    	if( ($("#cmbMateria").val()) != null && ($("#cmbHoraMat").val()) != null && ($("#txtFechaS").val()) != "" && ($("#cmbPractica").val()) != null && ($("#cmbHoraPract").val())!= null && ($("#txtCantAlumnos").val()) != "" && ($("#textarea1").val())!= "")
		{
			if((cantAlu >= minCantAlu) && (cantAlu <= maxCantAlu))
	    	{
			    $("#nuevaMaestro").hide();
			    $("#eleccionMaterial").show("slow");
			    articulos = Array();
			    articulosAgregados = Array();
			    numArticulos = Array();
			    llenarcomboEleArt();
			}
			else
		    {
	    		sweetAlert("Revise la cantidad de alumnos seleccionada", "Es posible que la cantida de alumnos seleccionada supere la cantidad permitida por el laboratorio!", "warning");	    				    	
		    }
	    }
	    else
	    {
	    	sweetAlert("Revise que todos los campos esten llenos", "Es posible que algunos campos se encuentren vacios!", "warning");
	    }
    }//Termina función de elegir material
    
    //Empieza función agregar articulo
    var agregarArt = function()
    {
    	var cantArt 	= parseInt($("#txtNumArt").val());
    	var maxCantArt 	= parseInt($("#txtNumArt").attr("max"));
    	var minCantArt 	= parseInt($("#txtNumArt").attr("min"));
    	//aquiEmpieza todo
    	if(($("#cmbMaterialCat" ).val()) != null && (cantArt >= minCantArt) && (cantArt <= maxCantArt))
    	{
	    	var artCve = $("#cmbMaterialCat" ).val();
	    	var artNom = $("#cmbMaterialCat option:selected").text();
	    	var num    = $("#txtNumArt").val();
		    articulos.push(artNom);
		    articulosAgregados.push(artCve);
		    numArticulos.push(num);
		    //construir tabla
		    construirTabla();
		}
		else
		{
	    	sweetAlert("Revise el material y cantidad solicitados", "Es posible que no seleccionara ningun material o supere la cantidad existente de ese material!", "warning");			
		}
    }//Termina función agregar articulo
    
    //Comienza función de eliminar Articulo
    var eliminarArt = function()
    {
    	var art = ($(this).attr("name"));
    	var i = articulosAgregados.indexOf(art);
    	articulos = eliminar(articulos,i);
    	articulosAgregados = eliminar(articulosAgregados,i);
    	numArticulos = eliminar(numArticulos,i);
    	//construir tabla
    	construirTabla();
    }//Termina función de eliminar Articulo

    //inicia función de llenar combo de elegir articulos
    var llenarcomboEleArt = function()
    {
    	$("#loaderImage").show();
    	var comboArt 	= Array();
    	var comboclaArt = Array();
    	var c 			= articulosAgregados.length;
    	var i 			= 0;
    	var o 			= 0;	
    	var laboratorio = $("#cmbLaboratorio").val();
    	var parametros 	= "opc=comboEleArt1"+
    	"&laboratorio="+laboratorio+
    	"&id="+Math.random();
    	$.ajax({
    		cache:false,
    		type: "POST",
    		dataType: "json",
    		url:"../data/funciones.php",
    		data: parametros,
    		success: function(response)
    		{
    			if(response.respuesta == true)
    			{
    				$("#loaderImage").hide();
    				comboclaArt = response.comboCveArt;
    				comboArt 	= response.comboNomArt;
					//eliminar elementos repetidos
					for (var r =0; r< c; r++) 
					{
						o = (articulosAgregados[r]);
						i = parseInt((comboclaArt).indexOf(o));
						comboclaArt = (eliminar(comboclaArt,i));
						comboArt 	= (eliminar(comboArt,i));				
					}
					var con = comboclaArt.length;
					//termina eliminación
					$("#cmbMaterialCat").html(" ");
					$("#cmbMaterialCat").html("<option value='' disabled selected>Seleccione el material</option>");
					for (var i = 0; i < con; i++) 
					{
						$("#cmbMaterialCat").append($("<option></option>").attr("value",comboclaArt[i]).text(comboArt[i]));
					}
					$("cmbMaterialCat").trigger('contentChanged');
					$('select').material_select();
				}
				else
				{
					$("#loaderImage").hide();
					$("#cmbMaterialCat").html(" ");
					$("#cmbMaterialCat").html("<option value='' disabled selected>Seleccione el material</option>");
					$('select').material_select();
					sweetAlert("No existen articulos", "Es posible que no existan articulos en dicho laboratorio!", "warning");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImage").hide();
				console.log("Error de conexión combomat");	
			}
		});
	}//termina función de llenar combo de elegir articulos
	
	//Inicia función para llenar la tabla de elegirMaterial
	var construirTabla = function()
    {
    	$("#loaderImage").show();
    	var parametros = "opc=construirTbArt1"+
    	"&articulosAgregados="+articulosAgregados+
    	"&articulos="+articulos+
    	"&numArticulos="+numArticulos+
    	"&id="+Math.random();
    	$.ajax({
    		cache:false,
    		type: "POST",
    		dataType: "json",
    		url:"../data/maestros.php",
    		data: parametros,
    		success: function(response){
    			if(response.respuesta == true)
    			{
    				$("#loaderImage").hide();
    				$("#bodyArt").html("");
    				$("#bodyArt").append(response.renglones);
    				llenarcomboEleArt();
    				$("#txtNumArt").val("1");
    				$(".btnEliminarArt").on("click",eliminarArt);
					//formar de nuevo el combo
				}//termina if
				else
				{
					$("#loaderImage").hide();
					console.log("no elimino");
					$("#bodyArt").html("");
					llenarcomboEleArt();
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				console.log("Error de conexión construir tabla");	
			}
		});

	}//Termina función para llenar la tabla de elegirMaterial

	//inicia función para eliminar un elemento de un arreglo
	var eliminar = function(arreglo,posicion)
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
	}//termina función para eliminar un elemento de un arreglo
    
    //Empieza función editar solicitud
    var editarSolicitudLab = function()
    {
	    //ocultar elementos
	    //$(this).closest("td").children("input").val();
	    $("#solicitudesPendientesLab").hide();
	    $("#eleccionMaterialE").hide();
	    //Contenido Dinamico
	    $("#loaderImage").show();
	    $("#editarSolicitudLab").show("slow");
	    $("#editarSol").show("slow");
    	var con   			= 0
	    var solId 			= parseInt($(this).attr("name"));
	    $("#txtFechaSE").attr("min",fechaActual());
	    var parametros 		= "opc=editarSolicitud1"+
	    						"&solId="+solId+
	    						"&id="+Math.random();
	    $.ajax({
	    	cache:false,
	    	type: "POST",
	    	dataType: "json",
	    	url:'../data/maestros.php',
	    	data: parametros,
	    	success: function(response)
	    	{
	    		if(response.respuesta == true)
	    		{
	    			$("#loaderImage").hide();
	    			//limpiando arreglos
	    			articulosE 			= Array();
    				articulosAgregadosE	= Array();
    				numArticulosE 		= Array();
       				//llenado datos
       				$("#txtFechaSE").attr("max",response.terminaPeriodo);
       				$("#txtMateriaE").val(response.materia);
       				$("#txtMateriaE").attr("name",response.claveSol);
       				$("#txtHoraMatE").val(((response.horas[0]).substring(0,2))+":00");
       				$("#txtFechaSE").val(response.fechaPrac);
       				$("#txtPracticaE").val(response.practica);
       				$("#txtLabE").val(response.laboratorio);
       				$("#txtLabE").attr("name",response.claveLab);
       				$("#cmbHoraPractE").val(); //modificar
       				$("#txtCantAlumnosE").val(response.cantidad);
       				$("#textarea1E").val(response.motivoUso);
       				
       				//combo
       				comboHoraPracE(response.claveLab,response.horaPractica);
       				//llenar tabla y combo
       				con =((response.materiales['claveMat']).length);
       				for (var i =0; i<con; i++) 
       				{
       					articulosAgregadosE.push((response.materiales['claveMat'][i]));
       					articulosE.push((response.materiales['nomMat'][i]));
						numArticulosE.push((response.materiales['cantidad'][i]));
       				}
       			}
       			else
       			{
       				$("#loaderImage").hide();
       				sweetAlert("Solicitud no encontrada", "No existe esa solicitud..!", "warning");
       			}
       		},
       		error: function(xhr, ajaxOptions,x)
       		{
       			$("#loaderImage").hide();
       			console.log("Error de conexion editar");
       		}
       	});
    }//Termina función editar solicitud
    
    //inicia función editar solicitud
    var elegirMaterialE = function()
    {
    	var cantAluE = parseInt($("#txtCantAlumnosE").val());
    	var maxCantAluE = parseInt($("#txtCantAlumnosE").attr("max"));
    	var minCantAluE = parseInt($("#txtCantAlumnosE").attr("min"));
    	if(($("#txtFechaSE").val())!= "" && ($("#txtCantAlumnosE").val()) != "")
		{
	    	if( (cantAluE >= minCantAluE) && (cantAluE <= maxCantAluE))
	    	{
		    	//ocultar elementos
		    	$("#editarSol").hide();
		    	$("#eleccionMaterialE").show("slow");
		    	//llenar combo y crear tabla
		    	construirTablaE();
		    }
		    else
		    {
	    		sweetAlert("Revise la cantidad de alumnos seleccionada", "Es posible que la cantida de alumnos seleccionada supere la cantidad permitida por el laboratorio!", "warning");	    				    	
		    }
	    }
	    else
	    {
	    	sweetAlert("Revise que tods los campos esten llenos", "Es posible que algunos campos se encuentren vacios!", "warning");
	    }
    }//Termina función elegirMaterial de editar
    
    //Inicia función para construir tabla de los articulos a elegir para la practica
    var construirTablaE = function()
    {
    	$("#loaderImage").show();
    	$("#bodyArtE").html("");
    	var parametros = "opc=construirTbArtE1"+
    	"&articulosAgregadosE="+articulosAgregadosE+
    	"&articulosE="+articulosE+
    	"&numArticulosE="+numArticulosE+
    	"&id="+Math.random();
    	$.ajax({
    		cache:false,
    		type: "POST",
    		dataType: "json",
    		url:"../data/maestros.php",
    		data: parametros,
    		success: function(response)
    		{
    			if(response.respuesta == true)
    			{
    				$("#loaderImage").hide();
    				$("#bodyArtE").html("");
    				$("#bodyArtE").append(response.renglones);
    				//formar de nuevo el combo
    				llenarcomboEleArtE();
    				$(".btnEliminarArtE").on("click",eliminarArtE);
				}//termina if
				else
				{
					$("#loaderImage").hide();
					console.log("no elimino");
					$("#bodyArt").html("");
					llenarcomboEleArtE();
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImage").hide();
				console.log("Error de conexión construir tabla");	
			}
		});
    }//Termina función para construir tabla
    
    //Inicia función para llenar el combo de elegir material de editar
    var llenarcomboEleArtE = function()
    {
    	$("#loaderImage").show();
    	var comboArt 	= Array();
    	var comboclaArt = Array();
    	var c 			= articulosAgregadosE.length;
    	var i 			= 0;
    	var o 			= 0;	
    	var laboratorio = $("#txtLabE").attr("name");
    	var parametros 	= "opc=comboEleArt1"+
    						"&laboratorio="+laboratorio+
    						"&id="+Math.random();
    	$.ajax({
    		cache:false,
    		type: "POST",
    		dataType: "json",
    		url:"../data/funciones.php",
    		data: parametros,
    		success: function(response)
    		{
    			if(response.respuesta == true)
    			{
    				$("#loaderImage").hide();
    				comboclaArt = response.comboCveArt;
    				comboArt 	= response.comboNomArt;
					//eliminar elementos repetidos
					for (var r =0; r< c; r++) 
					{
						o = (articulosAgregadosE[r]);
						i = parseInt((comboclaArt).indexOf(o));
						comboclaArt = (eliminar(comboclaArt,i));
						comboArt 	= (eliminar(comboArt,i));				
					}
					var con = comboclaArt.length;
					//termina eliminación
					$("#cmbMaterialCatE").html(" ");
					$("#cmbMaterialCatE").html("<option value='' disabled selected>Seleccione el material</option>");
					for (var i = 0; i < con; i++) 
					{
						$("#cmbMaterialCatE").append($("<option></option>").attr("value",comboclaArt[i]).text(comboArt[i]));
					}
					$("cmbMaterialCatE").trigger('contentChanged');
					$('select').material_select();
				}
				else
				{
					$("#loaderImage").hide();
					$("#cmbMaterialCat").html(" ");
					$("#cmbMaterialCat").html("<option value='' disabled selected>Seleccione el material</option>");
					$('select').material_select();
					sweetAlert("No existen articulos", "Es posible que no existan articulos en dicho laboratorio!", "warning");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImage").hide();
				console.log("Error de conexion combomat editar");
			}
		});
    }//Termina función para llenar el combo de elegir material de editar
    
    //Inicia función para agregarArticulos en editar
    var agregarArtE = function()
    {
	    var cantArtE 	= parseInt($("#txtNumArt").val());
    	var maxCantArtE = parseInt($("#txtNumArt").attr("max"));
    	var minCantArtE = parseInt($("#txtNumArt").attr("min"));
    	//aquiEmpieza todo
    	if(($("#cmbMaterialCat" ).val()) != null && (cantArtE >= minCantArtE) && (cantArtE <= maxCantArtE))
	    {	
	    	var artCve = $("#cmbMaterialCatE" ).val();
	    	var artNom = $("#cmbMaterialCatE option:selected").text();
	    	var num    = $("#txtNumArtE").val();
		    articulosE.push(artNom);
		    articulosAgregadosE.push(artCve);
		    numArticulosE.push(num);
		    $("#txtNumArtE").val("1");
		    construirTablaE();
		}
		else
		{
	    	sweetAlert("Revise el material y cantidad solicitados", "Es posible que no seleccionara ningun material o supere la cantidad existente de ese material!", "warning");			
		}
    }//Termina función para agregarArticulos en editar
    
    //Inicia función para eliminarArticulos en editar
    var eliminarArtE = function()
    {
    	$("#loaderImage").show();
    	var art = ($(this).attr("name"));
    	var solId = $("#txtMateriaE").attr("name");
    	var i = articulosAgregadosE.indexOf(art);
    	articulosE = eliminar(articulosE,i);
    	articulosAgregadosE = eliminar(articulosAgregadosE,i);
    	numArticulosE = eliminar(numArticulosE,i);
    	construirTablaE();
    	var parametros 	= "opc=eliminarArt1"+
    						"&claveArt="+art+
    						"&solId="+solId
    						"&id="+Math.random();
    	$.ajax({
    		cache:false,
    		type: "POST",
    		dataType: "json",
    		url:"../data/maestros.php",
    		data: parametros,
    		success: function(response)
    		{
    			if(response.respuesta == true)
    			{
    				$("#loaderImage").hide();
    				console.log("si se elimino de la bd el art");
				}
				else
				{
					$("#loaderImage").hide();
					console.log("no se elimino de la bd el art");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImage").hide();
				console.log("Error de conexion combomat editar");	
			}
		});
    }//Termina función para eliminarArticulos en editar
    
    //Inicia función para regresar al editar
    var regresarEditar = function()
    {
    	//ocultar elementos
    	$("#eleccionMaterialE").hide();
    	$("#editarSol").show("slow");
    }//Termina función para regresar al editar
    
    //empieza función de eliminar solicitud
    var eliminarSolicitud = function()
    {
    	$(this).closest("tr").remove();
    	var solId = parseInt($(this).attr("name"));
    	swal({   	
    		title: "¿Esta seguro que desea eliminar la solicitud?",   
    		text: "Una vez eliminada la solicitud, esta ya no existira..!",   
    		type: "error",   
    		showCancelButton: true,   
    		confirmButtonColor: "#DD6B55",   
    		confirmButtonText: "Si",   
    		cancelButtonText: "No",   
    		closeOnConfirm: false,   
    		closeOnCancel: false 
    	},  function(isConfirm)
    	{   
    		if (isConfirm) 
    		{ 
    			$("#loaderImage").show();
    			var parametros = "opc=eliminarSolicitud1"+
    			"&solId="+solId+
    			"&id="+Math.random();
    			$.ajax({
    				cache:false,
    				type: "POST",
    				dataType: "json",
    				url:"../data/maestros.php",
    				data: parametros,
    				success: function(response)
    				{
    					if(response.respuesta)
    					{
    						$("#loaderImage").hide();
    						swal("La solicitud fue eliminada con éxito!", "Da click en el botón", "success");
    						solPendientes();
    					}
    					else
    					{
    						$("#loaderImage").hide();
    						sweetAlert("La solicitud no fue eliminada", "", "warning");
    					}
    				},
    				error: function(xhr, ajaxOptions,x)
    				{
    					$("#loaderImage").hide();
    					console.log("Error de conexión eliminar s");
    				}
    			});
    		} 
    		else 
    		{
    			$("#loaderImage").hide();
    			solPendientes();
    			swal("OK..!","La solicitud no fue eliminada..!", "warning");
    		} 
    	});
    }//fin función eliminar solicitud
    
    //inicia funcion de capacidad de laboratorio
    var capacidadLab = function()
    {
    	$("#loaderImage").show();
    	var laboratorio = $("#cmbLaboratorio").val();
    	var parametros 	  = "opc=capacidadLab1"+
    	"&laboratorio="+laboratorio+
    	"&id="+Math.random();
    	$.ajax({
    		cache:false,
    		type: "POST",
    		dataType: "json",
    		url:"../data/funciones.php",
    		data: parametros,
    		success: function(response)
    		{
    			if(response.respuesta == true)
    			{
    				$("#loaderImage").hide();
    				if(parseInt($("#txtCantAlumnos").val()) <= response.capacidad)
    				{
						return true;
					}
					else
					{
						return false;
					}
				}
				else
				{
					$("#loaderImage").hide();
					return false;
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				console.log("Error de conexión validar capacidad");	
			}
		});
    }//fin de funcion de capacidad de laboratorio
    
    //inicio de funcion para dar de alta una nueva solicitud de lab
    var altaNuevaSol = function()
    {
    	//insertar una nueva solicitud
    	if(articulos != "" && articulosAgregados != "" && numArticulos != "")
    	{
    		var res=parseInt($("#txtCantAlumnos").attr("max"));
    		var cantalu = parseInt($("#txtCantAlumnos").val());
    		
    		if(cantalu <= res)
    		{
    			$("#loaderImage").show();
    			var fe  = fechaActual();
    			var ff  = $("#txtFechaS").val();
    			var a  = ff.substring(0,4);
    			var m  = ff.substring(5,7);
    			var d  = ff.substring(8,10);
    			var fs = a+"-"+m+"-"+d;
    			var hs  = $("#cmbHoraPract option:selected").text();
    			var lab = $("#cmbLaboratorio").val();
    			var uso = $("#textarea1").val();
    			var prac = $("#cmbPractica").val();
    			var mat = $("#cmbMateria").val();
    			var gp  = $("#cmbHoraMat option:selected").text();
				var gpo = parseInt(gp.substring(0,2))//segun la hora se saca el grupo
				var cant = $("#txtCantAlumnos").val();
				var n = (($("#tbMaterialSol tr").length)-1);
				var parametros = "opc=nuevaSol1"+
				        			"&fe="+fe+
									"&fs="+fs+
									"&hs="+hs+
									"&lab="+lab+
									"&uso="+uso+
				        			"&prac="+prac+
				        			"&mat="+mat+
				        			"&gpo="+gpo+
				        			"&cant="+cant+
				        			"&art="+articulosAgregados+
				        			"&num="+numArticulos+
				        			"&n="+n+
				        			"&id="+Math.random();
				$.ajax({
				        cache:false,
				        type: "POST",
				        dataType: "json",
				        url:'../data/maestros.php',
				        data: parametros,
				        success: function(response)
				        {
				        		if(response.respuesta == true && response.respuesta2 == true && response.respuesta3 == true)
				        		{
				        			$("#loaderImage").hide();
				                    //limpiar datos
				                    $("#txtFechaS").val(fechaActual);									
				                    $("#txtCantAlumnos").val("1");
				                    $("#textarea1").val("");
				                    $("#txtNumArt").val("1");
				                    articulos = Array();
				                    articulosAgregados = Array();
				                    numArticulos = Array();
				                    $("#bodyArt").html("");
									//combo materia
									$("#cmbMateria").html(" ");
									$("#cmbMateria").html("<option value='' disabled selected>Seleccione la materia</option>");
									$('select').material_select();
									//Combo hora materia
									$("#cmbHoraMat").html(" ");
									$("#cmbHoraMat").html("<option value='' disabled selected>Seleccione la hora</option>");
									$('select').material_select();
									//combo Practica
									$("#cmbPractica").html(" ");
									$("#cmbPractica").html("<option value='' disabled selected>Seleccione la práctica</option>");	
									$('select').material_select();
									//combo laboratorio
									$("#cmbLaboratorio").html(" ");
									$("#cmbLaboratorio").html("<option value='' disabled selected>Seleccione el laboratorio</option>");
									$('select').material_select();
									//Combo hora practica
									$("#cmbHoraPract").html(" ");
									$("#cmbHoraPract").html("<option value='' disabled selected>Seleccione la hora</option>");
									$('select').material_select();
									solNueva();
									swal("La solicitud fue creada con éxito!", "Da clic en el botón OK!", "success");				
								}
								else
								{
									$("#loaderImage").hide();
									if(response.respuesta3==false)
									{
										$("#loaderImage").hide();
										$("#eleccionMaterial").hide();
										$("#nuevaMaestro").show();
										sweetAlert("warning", "Ya existe una calendarización del laboratorio en esa fecha y hora!", "warning");
									}
									else
									{
										$("#loaderImage").hide();	
										sweetAlert("warning", "No se pudo crear la solicitud!", "warning");
									}
								}
							},
							error: function(xhr, ajaxOptions,x)
							{
								$("#loaderImage").hide();
								console.log("Error de conexión articulo");
							}
				});
			}//Termina if de checar si la capacidad del lab es correcta
			else
			{
				$("#loaderImage").hide();
				sweetAlert("Veficiar la capacidad", "La capacidad seleccionada es superior a la que permite el laboratorio!", "warning");
			}
	   	}//Termina if de checar campos vacios
	   	else
	   	{
	   		$("#loaderImage").hide();
	    	sweetAlert("Warning", "Debe llenar todos los campos!", "warning");
	   	}
    }//fin de funcion para dar de alta una nueva solicitud de lab
    
    //Inicia función para editar una solicitud
    var editSol = function()
    {
    	if(articulosE != "" && articulosAgregadosE != "" && numArticulosE != "")
    	{
    		$("#loaderImage").show();
    		var fs 	 		= $("#txtFechaSE").val();
    		var hs 	 		= $("#cmbHoraPractE option:selected").text();
    		var cant 		= $("#txtCantAlumnosE").val();
    		var solId 		= $("#txtMateriaE").attr("name");
    		var lab 		= $("#txtLabE").attr("name");
    		var parametros  = "opc=ModificarSol1"+
    							"&fs="+fs+
    							"&hs="+hs+
    							"&cant="+cant+
    							"&solId="+solId+
    							"&lab="+lab+
    							"&articulosAgregadosE="+articulosAgregadosE+
    							"&numArticulos="+numArticulosE+
				        		"&id="+Math.random();
			$.ajax({
	    		cache:false,
	    		type: "POST",
	    		dataType: "json",
	    		url:"../data/maestros.php",
	    		data: parametros,
	    		success: function(response)
	    		{
	    			if (response.respuesta==true && response.respuesta2 == true) 
	    			{
	    				$("#loaderImage").hide();
	    				swal("La solicitud fue actualizada exitosamente", "Da clic en el botón OK!", "success");
	    				solPendientes();
	    			}
	    			else
	    			{
	    				$("#loaderImage").hide();
	    				if(response.respuesta2 == false)
	    				{
	    					$("#loaderImage").hide();
	    					sweetAlert("Modifique la fecha y hora", "ya existe una calendarización en esa fecha y hora.!", "warning");
	    				}
	    				else
	    				{
	    					$("#loaderImage").hide();
	    					sweetAlert("Warning", "La solicitud no pudo ser actualizada exitosamente.!", "warning");
	    				}
	    			}
				},
				error: function(xhr, ajaxOptions,x)
				{
					$("#loaderImage").hide();
					console.log("Error de conexión ModificarSol");
				}
			});
    	}//Termina if de checar campos vacios en editar
    	else
    	{
    		$("#loaderImage").hide();
    		sweetAlert("Verifique los campos", "Debe llenar todos los campos!", "warning");
    	}
    }//Termina función para editar una solicitud

    //inicio de funcion para llenar el combo de hora de materia
    var comboHoraMat = function()
    {
    	$("#loaderImage").show();
    	var hh = "";
    	var materia = $("#cmbMateria").val();
    	var parametros = "opc=comboMatHr1"+
    	"&materia="+materia+
    	"&id="+Math.random();
    	$.ajax({
    		cache:false,
    		type: "POST",
    		dataType: "json",
    		url:"../data/funciones.php",
    		data: parametros,
    		success: function(response)
    		{
    			if(response.respuesta == true)
    			{
    				$("#loaderImage").hide();
    				$("#cmbHoraMat").html(" ");
    				$("#cmbHoraMat").html("<option value='' disabled selected>Seleccione la hora</option>");	
    				for (var i = 0; i < response.cont; i++) 
    				{
						//arreglar la hora
						if (response.comboHr[i]!="") 
						{
							hh = ((response.comboHr[i]).substring(0,2)+":00");
							$("#cmbHoraMat").append($("<option></option>").attr("value",response.cont).text(hh));
						}
					}
					$("cmbHoraMat").trigger('contentChanged');
					$('select').material_select();
				}
				else
				{
					$("#loaderImage").hide();
					$("#cmbHoraMat").html(" ");
					$("#cmbHoraMat").html("<option value='' disabled selected>Seleccione la hora</option>");
					$('select').material_select();
					sweetAlert("No existen horas", "Es posible que no existan horas para la materia seleccionada", "warning");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImage").hide();
				console.log("Error de conexión combomat");	
			}
		});
		//inicializa los otros combos
		//combo Practica
		$("#cmbPractica").html(" ");
		$("#cmbPractica").html("<option value='' disabled selected>Seleccione la práctica</option>");	
		$('select').material_select();
		//combo laboratorio
		$("#cmbLaboratorio").html(" ");
		$("#cmbLaboratorio").html("<option value='' disabled selected>Seleccione el laboratorio</option>");
		$('select').material_select();
		//Combo hora practica
		$("#cmbHoraPract").html(" ");
		$("#cmbHoraPract").html("<option value='' disabled selected>Seleccione la hora</option>");
		$('select').material_select();
    }//fin de funcion para llenar el combo de hora de materia
    
    //inicio de funcion para llenar el combo de practica
    var comboPract = function()
    {
    	$("#loaderImage").show();
    	var materia = $("#cmbMateria").val();
    	var parametros = "opc=comboPract1"+
    	"&materia="+materia+
    	"&id="+Math.random();
    	$.ajax({
    		cache:false,
    		type: "POST",
    		dataType: "json",
    		url:"../data/funciones.php",
    		data: parametros,
    		success: function(response)
    		{
    			if(response.respuesta == true)
    			{
    				$("#loaderImage").hide();
    				$("#cmbPractica").html(" ");
    				$("#cmbPractica").html("<option value='' disabled selected>Seleccione la práctica</option>");	
    				for (var i = 0; i < response.con; i++) 
    				{

    					$("#cmbPractica").append($("<option></option>").attr("value",response.comboCvePrac[i]).text(response.comboTitPrac[i]));
    				}
    				$("cmbPractica").trigger('contentChanged');
    				$('select').material_select();
    			}
    			else
    			{
    				$("#loaderImage").hide();
    				$("#cmbPractica").html(" ");
    				$("#cmbPractica").html("<option value='' disabled selected>Seleccione la práctica</option>");	
    				$('select').material_select();
    				sweetAlert("No existen prácticas", "Es posible que no existan prácticas asociadas a dicha materia!", "warning");
    			}
    		},
    		error: function(xhr, ajaxOptions,x)
    		{
    			$("#loaderImage").hide();
    			console.log("Error de conexión comboPrac");	
    		}
    	});
    }//fin de funcion para llenar el combo de practica
    
    //inicio de funcion para llenar el combo de laboratorio
    var comboLab = function()
    {
    	$("#loaderImage").show();
    	var practica   = $("#cmbPractica").val();
    	var parametros = "opc=comboLab1"+
    	"&practica="+practica+
    	"&id="+Math.random();
    	$.ajax({
    		cache:false,
    		type: "POST",
    		dataType: "json",
    		url:"../data/funciones.php",
    		data: parametros,
    		success: function(response)
    		{
    			if(response.respuesta == true)
    			{
    				$("#loaderImage").hide();
    				$("#cmbLaboratorio").html(" ");
    				$("#cmbLaboratorio").html("<option value='' disabled selected>Seleccione el laboratorio</option>");	
    				for (var i = 0; i < response.con; i++) 
    				{
    					$("#cmbLaboratorio").append($("<option></option>").attr("value",response.comboCveLab[i]).text(response.comboNomLab[i]));
    				}
    				$("cmbLaboratorio").trigger('contentChanged');
    				$('select').material_select();
    			}
    			else
    			{
    				$("#loaderImage").hide();
    				$("#cmbLaboratorio").html(" ");
    				$("#cmbLaboratorio").html("<option value='' disabled selected>Seleccione el laboratorio</option>");
    				$('select').material_select();
    				sweetAlert("No existen laboratorio", "Es posible que no existan laboratorios asociados a dicha práctica!", "Warning");
    			}
    		},
    		error: function(xhr, ajaxOptions,x)
    		{
    			$("#loaderImage").hide();
    			console.log("Error de conexión comboPrac");	
    		}
    	});
    }//fin de funcion para llenar el combo de laboratorio
    
    //inicio de funcion para llenar el combo de la hora de la practica
    var comboHoraPrac = function()
    {
    	$("#loaderImage").show();
    	var laboratorio   = $("#cmbLaboratorio").val();
    	var parametros 	  = "opc=comboHoraPrac1"+
    	"&laboratorio="+laboratorio+
    	"&id="+Math.random();
    	var hi = "";
    	var hii = 0;
    	var hf = "";
    	var fff = 0;
    	$.ajax({
    		cache:false,
    		type: "POST",
    		dataType: "json",
    		url:"../data/funciones.php",
    		data: parametros,
    		success: function(response){
    			if(response.respuesta == true)
    			{
    				$("#loaderImage").hide();
    				$("#cmbHoraPract").html(" ");
    				$("#cmbHoraPract").html("<option value='' disabled selected>Seleccione la hora</option>");	
    				(((response.horaApertura).length)<4) ? (hi=(response.horaApertura).substring(0,1)) : (hi=(response.horaApertura).substring(0,2));
    				hii = parseInt(hi);
    				(((response.horaCierre).length)<4) ? (hf=(response.horaCierre).substring(0,1)) : (hf=(response.horaCierre).substring(0,2));
    				hff = parseInt(hf);
					//modificando capacidad segun el laboratorio
					$("#txtCantAlumnos").attr("max",response.capacidad);
					for (var i = hii; i <= hff; i++) 
					{
						if(i>9)
						{
							$("#cmbHoraPract").append($("<option></option>").attr("value",i).text(i+":00"));
						}
						else
						{
							$("#cmbHoraPract").append($("<option></option>").attr("value","0"+i).text("0"+i+":00"));
						}
					}
					$("cmbHoraPract").trigger('contentChanged');
					$('select').material_select();
				}
				else
				{
					$("#loaderImage").hide();
					$("#cmbHoraPract").html(" ");
					$("#cmbHoraPract").html("<option value='' disabled selected>Seleccione la hora</option>");
					$('select').material_select();
					sweetAlert("No existen horas", "Es posible que no existan horas asociados a dicha práctica!", "warning");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImage").hide();
				console.log("Error de conexión comboPrac");	
			}
		});
    }//fin de funcion para llenar el combo de la hora de la practica
	
    //Inicio de funcion para llenar el combo de la hora de la practica en Editar
	var comboHoraPracE = function(lab,hor)
    {
    	$("#loaderImage").show();
    	var hora 			= hor;
    	var laboratorio   	= lab;
    	var parametros 	  	= "opc=comboHoraPrac1"+
    							"&laboratorio="+laboratorio+
    							"&id="+Math.random();
    	var hi = "";
    	var hii = 0;
    	var hf = "";
    	var fff = 0;
    	$.ajax({
    		cache:false,
    		type: "POST",
    		dataType: "json",
    		url:"../data/funciones.php",
    		data: parametros,
    		success: function(response)
    		{
    			if(response.respuesta == true)
    			{
    				$("#loaderImage").hide();
    				$("#cmbHoraPractE").html(" ");
    				$("#cmbHoraPractE").html("<option value='"+hora+"' disabled selected>"+hora+"</option>");	
    				(((response.horaApertura).length)<4) ? (hi=(response.horaApertura).substring(0,1)) : (hi=(response.horaApertura).substring(0,2));
    				hii = parseInt(hi);
    				(((response.horaCierre).length)<4) ? (hf=(response.horaCierre).substring(0,1)) : (hf=(response.horaCierre).substring(0,2));
    				hff = parseInt(hf);
					//modificando capacidad segun el laboratorio
					$("#txtCantAlumnosE").attr("max",response.capacidad);
					for (var i = hii; i <= hff; i++) 
					{
						if(i>9)
						{
							$("#cmbHoraPractE").append($("<option></option>").attr("value",i).text(i+":00"));
						}
						else
						{
							$("#cmbHoraPractE").append($("<option></option>").attr("value","0"+i).text("0"+i+":00"));
						}
					}
					$("cmbHoraPractE").trigger('contentChanged');
					$('select').material_select();
				}
				else
				{
					$("#loaderImage").hide();
					$("#cmbHoraPractE").html(" ");
					$("#cmbHoraPractE").html("<option value='' disabled selected>Seleccione la hora</option>");
					$('select').material_select();
					sweetAlert("No existen horas", "Es posible que no existan horas asociados a dicha práctica!", "warning");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImage").hide();
				console.log("Error de conexión comboPracE");	
			}
		});
    }//Termina funcion para llenar el combo de la hora de la practica en Editar
	
	//eventos menu Reportes
	var tbReportes = function()
	{
		//ocultar elementos
		$("#lista").hide();
		$("#selecionarLista").show();
		$("#inicioMaestro").hide();
		//limpiar datos
		$("#txtClaveMaestroRep2").val(" ");
    	$("#txtNombreMaestroRep2").val(" ");
    	$("#txtPeriodoRep").val(" ");
	    $("#txtMateriaRep").val(" ");
	    $("#txtHoraMatRep").val(" ");
	    $("#txtPracticaRep").val(" ");
	    $("#txtHoraPractRep").val(" ");
	    $("#txtFechaPracticaRep2").val(" ");
	    $("#txtFechaPracticaRep").val(" ");
		//valor por default de los combos
		//Combo de la materia
		$("#cmbMateriaRep").html(" ");
		$("#cmbMateriaRep").html("<option value='' disabled selected>Seleccione la materia</option>");
		$('select').material_select();
		//Combo hora materia
		$("#cmbHoraMatRep").html(" ");
		$("#cmbHoraMatRep").html("<option value='' disabled selected>Seleccione la hora</option>");
		$('select').material_select();
		//combo de practica
		$("#cmbPracticaRep").html(" ");
		$("#cmbPracticaRep").html("<option value='' disabled selected>Seleccione la práctica</option>");
		$('select').material_select();
		//combo hora práctica
		$("#cmbHoraPracticaRep").html(" ");
		$("#cmbHoraPracticaRep").html("<option value='' disabled selected>Seleccione la hora</option>");
		$('select').material_select();
		//Contenido Dinamico
		$("#cmbMateriaRep")
		$("#loaderImage").show();
		var parametros = "opc=datosMaestro1"+
							"&id="+Math.random();
		$.ajax({
    		cache:false,
    		type: "POST",
    		dataType: "json",
    		url:"../data/maestros.php",
    		data: parametros,
    		success: function(response)
    		{
    			if (response.respuesta==true) 
    			{
    				$("#loaderImage").hide();
	    			$("#txtClaveMaestroRep").val(response.datosMa["PERCVE"]);
	    			$("#txtNombreMaestroRep").val((response.datosMa["PERNOM"])+" "+(response.datosMa["PERAPE"]));
	    			//llenar combo
	    			$("#cmbPeriodoRep").html(" ");
					$("#cmbPeriodoRep").html("<option value='' disabled selected>Seleccione el Periodo</option>");
	    			for (var i =0; i< 20; i++) 
	    			{
	    				$("#cmbPeriodoRep").append($("<option></option>").attr("value",response.periodos[i]["pdocve"]).text(response.periodos[i]["pdodes"]));
	    			}
	    			$("#cmbPeriodoRep").trigger('contentChanged');
					$('select').material_select();

	    		}
	    		else
	    		{
	    			$("#loaderImage").hide();
	    			$("#cmbPeriodoRep").html(" ");
					$("#cmbPeriodoRep").html("<option value='' disabled selected>Seleccione el Periodo</option>");
					$('select').material_select();
					sweetAlert("No existen Periodos", "Es posible que no existan periodos!", "warning");
	    		}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImage").hide();
				console.log("Error de conexión reportes");	
			}
		});
	}
	var listaAsistencia = function()
	{
		//oculta y mostrar elementos
		//limpiar datos
		$("#txtClaveMaestroRep2").val(" ");
	    $("#txtNombreMaestroRep2").val(" ");
	    $("#txtPeriodoRep").val(" ");
		$("#txtMateriaRep").val(" ");
		$("#txtHoraMatRep").val(" ");
		$("#txtPracticaRep").val(" ");
		$("#txtHoraPractRep").val(" ");
		$("#txtFechaPracticaRep2").val(" ");
		$("#tbListaAsistencia").html(" ");
		if($("#cmbPeriodoRep").val()!= null && $("#cmbMateriaRep").val() != null && $("#cmbHoraMatRep").val() != null && $("#cmbPracticaRep").val() != null && $("#cmbHoraPracticaRep").val() != null && $("#txtFechaPracticaRep").val() != " ")
		{
			$("#loaderImage").show();
			//contenido dinamico
			var periodo 	= $("#cmbPeriodoRep").val();
			var materia 	= $("#cmbMateriaRep").val();
			var h			= $("#cmbHoraMatRep option:selected").text();
			var horaMat 	= parseInt(h.substring(0,2));
			var practica 	= $("#cmbPracticaRep").val();
			var fecha  		= $("#txtFechaPracticaRep").val();
			var horaPract 	= $("#cmbHoraPracticaRep option:selected").text(); 	
			var parametros = "opc=listaAlumnos1"+
								"&periodo="+periodo+
								"&materia="+materia+
								"&horaMat="+horaMat+
								"&practica="+practica+
								"&fecha="+fecha+
								"&horaPract="+horaPract+
								"&id="+Math.random();
			$.ajax({
	    		cache:false,
	    		type: "POST",
	    		dataType: "json",
	    		url:"../data/maestros.php",
	    		data: parametros,
	    		success: function(response)
	    		{
	    			if (response.respuesta==true) 
	    			{
	    				$("#loaderImage").hide();
	    				$("#selecionarLista").hide();
	    				//llenar datos	
	    				$("#txtClaveMaestroRep2").val($("#txtClaveMaestroRep").val());
	    				$("#txtNombreMaestroRep2").val($("#txtNombreMaestroRep").val());
	    				$("#txtPeriodoRep").val($("#cmbPeriodoRep option:selected").text());
		    			$("#txtMateriaRep").val($("#cmbMateriaRep option:selected").text());
		    			$("#txtHoraMatRep").val($("#cmbHoraMatRep option:selected").text());
		    			$("#txtPracticaRep").val($("#cmbPracticaRep option:selected").text());
		    			$("#txtHoraPractRep").val($("#cmbHoraPracticaRep option:selected").text());
		    			$("#txtFechaPracticaRep2").val($("#txtFechaPracticaRep").val());
		    			$("#txtCarreraRep").val(response.carrera);
		    			//crear tabla de asistencia de alumnos
		    			$("#tbListaAsistencia").html(" ");
						$("#tbListaAsistencia").append(response.renglones);
						//Abrir ventana en otro lado
						$("#lista").show("slow");
						window.open("../Maestro/reporteListas.php?"+"periodo="+periodo+"&nomLab="+response.nomLab+"&nomDepto="+response.depto+"&cveMaestro="+$("#txtClaveMaestroRep2").val()+"&nomMaestro="+$("#txtNombreMaestroRep2").val()+"&carrera="+$("#txtCarreraRep").val()+"&nomMateria="+$("#txtMateriaRep").val()+"&nomPractica="+$("#txtPracticaRep").val()+"&materia="+materia+"&horaMat="+horaMat+"&fechaPrac="+fecha+"&practica="+practica+"&horaPrac="+horaPract,"asistencia","width=120,height=300,scrollbars=NO");
		    		}
		    		else
		    		{
		    			$("#loaderImage").hide();
		    			//limpiar datos
		    			$("#txtCarreraRep").val("");
						$("#txtClaveMaestroRep2").val(" ");
				    	$("#txtNombreMaestroRep2").val(" ");
				    	$("#txtPeriodoRep").val(" ");
					    $("#txtMateriaRep").val(" ");
					    $("#txtHoraMatRep").val(" ");
					    $("#txtPracticaRep").val(" ");
					    $("#txtHoraPractRep").val(" ");
					    $("#txtFechaPracticaRep2").val(" ");
						$("#tbListaAsistencia").html(" ");
						$("#tbListaAsistencia").html(" ");
						sweetAlert("No existe ninguna práctica", "No se realizo ninguna práctica!", "warning");
		    		}
				},
				error: function(xhr, ajaxOptions,x)
				{
					$("#loaderImage").hide();
					console.log("Error de conexión datos Maestro");
				}
			});
		}
		else
		{
			$("#loaderImage").hide();
			sweetAlert("Verifique los campos", "No ha seleccionado todos los campos necesarios", "warning");
		}
	}
	var regresarRep = function()
	{
		$("#lista").hide();
		$("#selecionarLista").show("slow");
		//limpiar
		tbReportes();
	}
	var comboMatRep = function()
	{
		//limpiar combos
		$("#loaderImage").show();
		//Combo hora materia
		$("#cmbHoraMatRep").html(" ");
		$("#cmbHoraMatRep").html("<option value='' disabled selected>Seleccione la hora</option>");
		$('select').material_select();
		//combo de practica
		$("#cmbPracticaRep").html(" ");
		$("#cmbPracticaRep").html("<option value='' disabled selected>Seleccione la práctica</option>");
		$('select').material_select();
		//combo hora práctica
		$("#cmbHoraPracticaRep").html(" ");
		$("#cmbHoraPracticaRep").html("<option value='' disabled selected>Seleccione la hora</option>");
		$('select').material_select();
		var periodo = $("#cmbPeriodoRep").val();
		var parametros = "opc=comboMatRep1"+
							"&periodo="+periodo+
							"&id="+Math.random();
			$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/funciones.php",
			data: parametros,
			success: function(response)
			{
				if(response.respuesta == true)
				{
					$("#loaderImage").hide();
					$("#cmbMateriaRep").html(" ");
					$("#cmbMateriaRep").html("<option value='' disabled selected>Seleccione la materia</option>");
					for (var i = 0; i < response.contador; i++) 
					{
						$("#cmbMateriaRep").append($("<option></option>").attr("value",response.claveMat[i]).text(response.nombreMat[i]));
					}
					$("cmbMateriaRep").trigger('contentChanged');
					$('select').material_select();
					//limpiar campos
					//combo de practica
					$("#cmbPracticaRep").html(" ");
					$("#cmbPracticaRep").html("<option value='' disabled selected>Seleccione la práctica</option>");
					$('select').material_select();
					//combo hora práctica
					$("#cmbHoraPracticaRep").html(" ");
					$("#cmbHoraPracticaRep").html("<option value='' disabled selected>Seleccione la hora</option>");
					$('select').material_select();
				}
				else
				{
					$("#loaderImage").hide();
					$("#cmbMateria").html(" ");
					$("#cmbMateria").html("<option value='' disabled selected>Seleccione la materia</option>");
					$('select').material_select();
					sweetAlert("No tiene materias", "Es posible que no tenga materias asignadas!", "warning");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImage").hide();
				console.log("Error de conexión combomat");	
			}
		});
	}
	var comboHrMatRep = function()
	{
		//limpiar combos
		$("#loaderImage").show();
		//combo de practica
		$("#cmbPracticaRep").html(" ");
		$("#cmbPracticaRep").html("<option value='' disabled selected>Seleccione la práctica</option>");
		$('select').material_select();
		//combo hora práctica
		$("#cmbHoraPracticaRep").html(" ");
		$("#cmbHoraPracticaRep").html("<option value='' disabled selected>Seleccione la hora</option>");
		$('select').material_select();
		var hh 			= "";
		var materia    	= $("#cmbMateriaRep").val();
		var periodo    	= $("#cmbPeriodoRep").val();
		var parametros 	= "opc=comboHrMatRep1"+
							"&periodo="+periodo+
							"&materia="+materia+
							"&id="+Math.random();
		$.ajax({
    		cache:false,
    		type: "POST",
    		dataType: "json",
    		url:"../data/funciones.php",
    		data: parametros,
    		success: function(response){
    			if(response.respuesta == true)
    			{
    				$("#loaderImage").hide();
    				$("#cmbHoraMatRep").html(" ");
    				$("#cmbHoraMatRep").html("<option value='' disabled selected>Seleccione la hora</option>");	
    				for (var i = 0; i < response.cont; i++) 
    				{
						//arreglar la hora
						hh = ((response.comboHr[i]).substring(0,2)+":00");
						$("#cmbHoraMatRep").append($("<option></option>").attr("value",response.cont).text(hh));
					}
					$("cmbHoraMatRep").trigger('contentChanged');
					$('select').material_select();
				}
				else
				{
					$("#loaderImage").hide();
					$("#cmbHoraMatRep").html(" ");
					$("#cmbHoraMatRep").html("<option value='' disabled selected>Seleccione la hora</option>");
					$('select').material_select();
					sweetAlert("No existen horas", "Es posible que no existan horas para la materia seleccionada", "warning");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImage").hide();
				console.log("Error de conexión combomatRep");	
			}
		});
	}
	var comboPractRep = function()
    {
    	$("#loaderImage").show();
    	var materia 	= $("#cmbMateriaRep").val();
    	var hr 			= $("#cmbHoraMatRep option:selected").text();
    	var hora 		= hr.substring(0,2);
    	var parametros 	= "opc=comboPractRep1"+
    						"&materia="+materia+
    						"&hora="+hora+
    						"&id="+Math.random();
    	$.ajax({
    		cache:false,
    		type: "POST",
    		dataType: "json",
    		url:"../data/funciones.php",
    		data: parametros,
    		success: function(response)
    		{
    			if(response.respuesta == true)
    			{
    				$("#loaderImage").hide();
    				$("#cmbPracticaRep").html(" ");
    				$("#cmbPracticaRep").html("<option value='' disabled selected>Seleccione la práctica</option>");	
    				for (var i = 0; i < response.con; i++) 
    				{

    					$("#cmbPracticaRep").append($("<option></option>").attr("value",response.comboCvePrac[i]).text(response.comboTitPrac[i]));
    				}
    				$("cmbPracticaRep").trigger('contentChanged');
    				$('select').material_select();
    			}
    			else
    			{
    				$("#loaderImage").hide();
    				$("#cmbPracticaRep").html(" ");
    				$("#cmbPracticaRep").html("<option value='' disabled selected>Seleccione la práctica</option>");	
    				$('select').material_select();
    				sweetAlert("No existen prácticas", "Es posible que no existan prácticas asociadas a dicha materia!", "warning");
    			}
    		},
    		error: function(xhr, ajaxOptions,x)
    		{
    			$("#loaderImage").hide();
    			console.log("Error de conexión comboPrac");
    		}
    	});
    }
    var comboHoraPracRep = function()
    {
    	$("#loaderImage").show();
    	var practica   		= $("#cmbPracticaRep").val();
    	var parametros 	  	= "opc=comboHoraPracRep1"+
    							"&practica="+practica+
    							"&id="+Math.random();
    	var hi = "";
    	var hii = 0;
    	var hf = "";
    	var fff = 0;
    	$.ajax({
    		cache:false,
    		type: "POST",
    		dataType: "json",
    		url:"../data/funciones.php",
    		data: parametros,
    		success: function(response){
    			if(response.respuesta == true)
    			{
    				$("#loaderImage").hide();
    				$("#cmbHoraPracticaRep").html(" ");
    				$("#cmbHoraPracticaRep").html("<option value='' disabled selected>Seleccione la hora</option>");	
    				(((response.horaApertura).length)<4) ? (hi=(response.horaApertura).substring(0,1)) : (hi=(response.horaApertura).substring(0,2));
    				hii = parseInt(hi);
    				(((response.horaCierre).length)<4) ? (hf=(response.horaCierre).substring(0,1)) : (hf=(response.horaCierre).substring(0,2));
    				hff = parseInt(hf);
					for (var i = hii; i <= hff; i++) 
					{
						if(i>9)
						{
							$("#cmbHoraPracticaRep").append($("<option></option>").attr("value",i).text(i+":00"));
						}
						else
						{
							$("#cmbHoraPracticaRep").append($("<option></option>").attr("value","0"+i).text("0"+i+":00"));
						}
					}
					$("cmbHoraPracticaRep").trigger('contentChanged');
					$('select').material_select();
				}
				else
				{
					$("#loaderImage").hide();
					$("#cmbHoraPracticaRep").html(" ");
					$("#cmbHoraPracticaRep").html("<option value='' disabled selected>Seleccione la hora</option>");
					$('select').material_select();
					sweetAlert("No existen horas", "Es posible que no existan horas asociados a dicha práctica!", "warning");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImage").hide();
				console.log("Error de conexión comboPrac");
			}
		});
    }

    //Inicia función para mostrar el catalago de las prácticas
    var practicasMaestro = function()
    {
    	$("#loaderImage").show();
    	$("#tabCatPracticas").html(" ");
		//ocultar los div
		$("#inicioMaestro").hide();
		$("#NuevaPracticaM").hide();

		//limpiar contenido
		$("#txtBuscarPracticas").val("");
		$("#rdoTituloG").prop('checked',true);
		$("#divTituloBG").show();
		//ocultar elementos
		$("#divPracticaB").hide();
		$("#divMateriaB").hide();
		$("#txtBuscarPracticas").val("");

		//contenido dinamico
		var parametros = "opc=catPracticas1"+
		"&id="+Math.random();
		$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/maestros.php",
			data: parametros,
			success: function(response)
			{
				if(response.respuesta == true)
				{
					$("#loaderImage").hide();
					$("#tabCatPracticas").html(" ");
					$("#tabCatPracticas").append(response.renglones);
				}
				else
				{
					$("#loaderImage").hide();
					sweetAlert("No hay Prácticas..!", "Ninguna de las materias asignadas, cuenta con una práctica", "warning");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImage").hide();
				console.log("Error de conexión catalogo prácticas");
			}
		});
    	$("#catalagoPracticas").show("slow");
    }//Termina función para mostrar el catalago de las prácticas

    //inicia función de crear una nueva práctica
    /*var nPracticaMaestro = function()
    {
    	//ocultar Elementos
    	$("#catalagoPracticas").hide();
    	$("#NuevaPracticaM").show("slow");
    	//contenido dinamico
    	$("#loaderImage").show();
    	//limpiar datos
		$("#txtTituloPractica").val("");
    	$("#txtDuracionPract").val("1");
    	$("#textareaDesPrac").val("");
    	//limpiando combos
		$("#cmbMatPractica").html(" ");
		$("#cmbMatPractica").html("<option value='' disabled selected>Seleccione la materia</option>");
		$('select').material_select();
		$("#cmbLabPractica").html(" ");
		$("#cmbLabPractica").html("<option value='' disabled selected>Seleccione el laboratorio</option>");
		$('select').material_select();
		var parametros = "opc=comboMat1"+
							"&id="+Math.random();
			$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/funciones.php",
			data: parametros,
			success: function(response)
			{
				if(response.respuesta == true)
				{
					$("#loaderImage").hide();
					$("#cmbMatPractica").html(" ");
					$("#cmbMatPractica").html("<option value='' disabled selected>Seleccione la materia</option>");
					for (var i = 0; i < response.contador; i++) 
					{
						$("#cmbMatPractica").append($("<option></option>").attr("value",response.claveMat[i]).text(response.nombreMat[i]));
					}
					$("cmbMatPractica").trigger('contentChanged');
					$('select').material_select();
				}
				else
				{
					$("#loaderImage").hide();
					$("#cmbMatPractica").html(" ");
					$("#cmbMatPractica").html("<option value='' disabled selected>Seleccione la materia</option>");
					$('select').material_select();
					sweetAlert("No tiene materias", "Es posible que no tenga materias asignadas!", "warning");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImage").hide();
				console.log("Error de conexión combomat nueva practica");	
			}
		});
    }//Termina función de crear una nueva práctica*/

    //Inicia función para llenar todos los laboratorios
    var comboLaboratorios = function()
    {
    	//contenido dinamico
    	$("#loaderImage").show();
    	//limpiando combos
		$("#cmbLabPractica").html(" ");
		$("#cmbLabPractica").html("<option value='' disabled selected>Seleccione el laboratorio</option>");
		$('select').material_select();
		var parametros = "opc=comboLaboratorios1"+
							"&id="+Math.random();
			$.ajax({
			cache:false,
			type: "POST",
			dataType: "json",
			url:"../data/funciones.php",
			data: parametros,
			success: function(response)
			{
				if(response.respuesta == true)
				{
					$("#loaderImage").hide();
					$("#cmbLabPractica").html(" ");
					$("#cmbLabPractica").html("<option value='' disabled selected>Seleccione el laboratorio</option>");
					for (var i = 0; i < response.contador; i++) 
					{
						$("#cmbLabPractica").append($("<option></option>").attr("value",response.comboCveLab[i]).text(response.comboNomLab[i]));
					}
					$("cmbLabPractica").trigger('contentChanged');
					$('select').material_select();
				}
				else
				{
					$("#loaderImage").hide();
					console.log("no trajo laboratorios");
				}
			},
			error: function(xhr, ajaxOptions,x)
			{
				$("#loaderImage").hide();
				console.log("Error de conexión combomat nueva practica");	
			}
		});
    }//Termina función para llenar todos los laboratorios

    //Inicia función para insertar una nueva práctica
    /*var altaNuevaPractica = function()
    {
    	var durPract 	= parseInt($("#txtDuracionPract").val());
    	var maxDurPract = parseInt($("#txtDuracionPract").attr("max"));
    	var minDurPract = parseInt($("#txtDuracionPract").attr("min"));
    	if($("#txtTituloPractica").val()!=""&& $("#cmbMatPractica option:selected").val()!="" && $("#cmbLabPractica option:selected").val() != "" && $("#textareaDesPrac").val() != "")
    	{
    		if((durPract >= minDurPract) && (durPract <= maxDurPract))
    		{
	    		//contenido dinamico
	    		$("#loaderImage").show();
	    		var titulo 		= $("#txtTituloPractica").val();
	    		var materia 	= $("#cmbMatPractica").val();
	    		var laboratorio = $("#cmbLabPractica").val();
	    		var duracion 	= $("#txtDuracionPract").val();
	    		var descripcion = $("#textareaDesPrac").val();
	    		var parametros = "opc=nuevaPract1"+
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
					        url:'../data/maestros.php',
					        data: parametros,
					        success: function(response)
					        {
					        		if(response.respuesta == true)
					        		{
					        			$("#loaderImage").hide();
					                    //limpiar datos
					                    nPracticaMaestro();
										swal("La práctica fue creada con éxito!", "Da clic en el botón OK!", "success");				
									}
									else
									{	$("#loaderImage").hide();					
										sweetAlert("Warning", "No se pudo crear la práctica!", "warning");
									}
								},
								error: function(xhr, ajaxOptions,x)
								{
									$("#loaderImage").hide();
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
    }*///Termina función para insertar una nueva práctica

    //Inicia funcion para buscar una práctica
    var buscarPractica = function()
    {
    	var titulo = $("#txtBuscarPracticas").val();
    	var laboratorio = $("#cmbMatPracticaB").val();
    	var materia = $("#cmbLaboratorioB").val();
    	if( titulo != "" || laboratorio != null || materia != null)
    	{
	    	if( $("#rdoTitulo").is(':checked'))
	    	{
	    		opcion 	= "p.tituloPractica"
	    		valor 	= $("#txtBuscarPracticas").val();
	    	}

	    	else if( $("#rdoMateria").is(':checked'))
	    	{
	    		opcion 	= "ap.MATCVE"
	    		valor 	= $("#cmbMatPracticaB").val();
	    	}
	    	else if ( $("#rdoLaboratorio").is(':checked'))
	    	{
	    		opcion 	= "ap.claveLaboratorio"
	    		valor 	= $("#cmbLaboratorioB").val();
	    	}
    		//contenido dinamico
    		$("#loaderImage").show("slow");
    		$("#tabCatPracticas").html(" ");
			var parametros = "opc=catPracticasFiltro1"+
							 "&opcion="+opcion+
							 "&valor="+valor+
								"&id="+Math.random();
			$.ajax({
				cache:false,
				type: "POST",
				dataType: "json",
				url:"../data/maestros.php",
				data: parametros,
				success: function(response)
				{
					if(response.respuesta == true)
					{
						$("#loaderImage").hide();
						$("#tabCatPracticas").html(" ");
						$("#tabCatPracticas").append(response.renglones);
					}
					else
					{
						$("#loaderImage").hide();
						sweetAlert("No hay Prácticas..!", "Ninguna de las materias asignadas, cuenta con una práctica", "warning");
					}
				},
				error: function(xhr, ajaxOptions,x)
				{
					$("#loaderImage").hide();
					console.log("Error de conexión catalogo prácticas");
				}
			});
	    	$("#catalagoPracticas").show("slow");
    	}
    	else
    	{
    		sweetAlert("Verifique los campos", "Debe seleccionar por lo menos un campo!", "warning");
    	}

    }//Termina funcion para buscar una práctica

    var llenarDatosBuscar = function()
    {
    	$("#txtBuscarPracticas").val("");
    	if( $("#rdoTitulo").is(':checked'))
    	{
    		$("#divTituloB").show();
    		$("#divMateriaB").hide();
    		$("#divPracticaB").hide();
    	}
    	else if( $("#rdoMateria").is(':checked'))
    	{
    		$("#divMateriaB").show();
    		$("#divTituloB").hide();
    		$("#divPracticaB").hide();
    		//llamar combo
    		comboPracticasM();
    	}
    	else if ( $("#rdoLaboratorio").is(':checked'))
    	{
    		$("#divPracticaB").show();
    		$("#divTituloB").hide();
    		$("#divMateriaB").hide();
    		//llamar combo
    		comboPracticasM();
    	}

    }
    var comboPracticasM = function()
    {
    	$("#loaderImage").show();
    	var maestro = false;
    	if( $("#rdoMateria").is(':checked'))
    	{
    		maestro = true; 
    	}
    	if(maestro)
    	{
    		practicasMaestro();
	    	$("#divTituloB").hide();
	    	$("#divPracticaB").hide();
			$("#rdoMateria").prop("checked",true);
			$("#divMateriaB").show();
    	}
    	else
    	{
    		practicasMaestro();
	    	$("#divTituloB").hide();
	    	$("#divMateriaB").hide();
			$("#rdoLaboratorio").prop("checked",true);
			$("#divPracticaB").show();
    	}
    	//Limpiar combo laboratorio
		$("#cmbLaboratorioB").html("");
		$("#cmbLaboratorioB").html("<option value='' disabled selected>Seleccione el laboratorio</option>");
		$("cmbLaboratorioB").trigger('contentChanged');
		$('select').material_select();

		//Limpiar combo Materia
		$("#cmbMatPracticaB").html("");
		$("#cmbMatPracticaB").html("<option value='' disabled selected>Seleccione la materia</option>");
		$("cmbMatPracticaB").trigger('contentChanged');
		$('select').material_select();

    	var parametros = "opc=comboPracticasM1"+
							"&id="+Math.random();
			$.ajax({
				cache:false,
				type: "POST",
				dataType: "json",
				url:"../data/maestros.php",
				data: parametros,
				success: function(response)
				{
					if(response.respuesta == true)
					{
						$("#loaderImage").hide();
						//LLenar combo laboratorio
						$("#cmbLaboratorioB").html(" ");
						$("#cmbLaboratorioB").html("<option value='' disabled selected>Seleccione el laboratorio</option>");
						for (var i = 0; i < (response.labs.length); i++) 
						{
							$("#cmbLaboratorioB").append($("<option></option>").attr("value",response.labs[i]).text(response.labsnoms[i]));
						}
						$("cmbLaboratorioB").trigger('contentChanged');
						$('select').material_select();
						
						//LLenar combo materias
						$("#cmbMatPracticaB").html(" ");
						$("#cmbMatPracticaB").html("<option value='' disabled selected>Seleccione la materia</option>");
						for (var i = 0; i < response.materias.length; i++) 
						{
							$("#cmbMatPracticaB").append($("<option></option>").attr("value",response.materias[i]).text(response.matsnoms[i]));
						}
						$("cmbMatPracticaB").trigger('contentChanged');
						$('select').material_select();					
					}
					else
					{
						$("#loaderImage").hide();
						$("#cmbLabPractica").html(" ");
						$("#cmbLabPractica").html("<option value='' disabled selected>Seleccione el laboratorio</option>");
						$('select').material_select();
						//sweetAlert("No existen prácticas", "Las materias que tiene asignadas, no cuentan con ninguna práctica!", "warning");
					}
				},
				error: function(xhr, ajaxOptions,x)
				{
					$("#loaderImage").hide();
					console.log("Error de conexión combo catalogo prácticas");
				}
			});
    }
	//Configuramos el evento del Tab
	$("#iniciotab").on("click",inicioTab);
	$("#solicitudestab").on("click",solAceptadas);
	$("#practicasMatab").on("click",practicasMaestro);
	$("#reportestab").on("click",tbReportes);
	$("#salirTab").on("click",salir);	
	
	//Configuramos los eventos Menu Solicitudes
	$("#btnSolicitudesAceptadas").on("click",solAceptadas);
	$("#btnSolicitudesPendientes").on("click",solPendientes);
	$("#btnElegirMaterialE").on("click",elegirMaterialE);
	$("#btnAgregarArtE").on("click",agregarArtE);
	$("#btnRegresarE").on("click",regresarEditar);
	$("#btnRegresarPen").on("click",solPendientes);
	$("#btnFinalizarNSE").on("click",editSol);
	//para botones que son creados dinamicamente primero se coloca:
	//el nombre de el id de la tabla que lo contiene despues el on y despues el evento
	//y de ahi el nombre del boton que desencadenara el evento
	//$("#tbSolRealizadas").on("click","#btnPracticaRealizada",practicaRealizada);
	$("#btnSolicitudesRealizadas").on("click",solRealizadas);
	$("#btnNuevaSolicitud").on("click",solNueva);
	
	//eventos de los combos
	$("#cmbMateria").on("change",comboHoraMat);
	$("#cmbHoraMat").on("change",comboPract);
	$("#cmbPractica").on("change",comboLab);
	$("#cmbLaboratorio").on("change",comboHoraPrac);
	
	//eventos de altaArticulo
	$("#btnElegirMaterial").on("click",elegirMaterial);
	$("#btnFinalizarNS").on("click",altaNuevaSol);
	$("#btnRegresar").on("click",solNueva);
	$("#btnAgregarArt").on("click",agregarArt);
	
	//Configuramos los eventos Menu Prácticas
	$("#btnBuscarPractica").on("click", buscarPractica);
	$(".filtroB").on("change", llenarDatosBuscar);
	//$("#btnCatalagoPracticas").on("click",practicasMaestro);
	//$("#btnNuevaPracticaMa").on("click",nPracticaMaestro);
	//$("#cmbMatPractica").on("change",comboLaboratorios);
	//$("#btnFinalizarNPractica").on("click",altaNuevaPractica);
	//$("#btnCancelarPractica").on("click",nPracticaMaestro);

	//Configuramos los eventos Menu Reportes
	$("#btnListaAsistencia").on("click",listaAsistencia);
	$("#btnRegresarRep").on("click",regresarRep);
	//conbos del menu de reportes
	$("#cmbPeriodoRep").on("change",comboMatRep);
	$("#cmbMateriaRep").on("change",comboHrMatRep);
	$("#cmbHoraMatRep").on("change",comboPractRep);
	$("#cmbPracticaRep").on("change",comboHoraPracRep);
}
$(document).on("ready",inicioMaestros);