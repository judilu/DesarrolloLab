var inicioreporte = function ()
{
	//Inicia funcion para llenar los datos del reporte
	var llenarDatosReporte = function ()
	{
		var variables = obtenerDatos();
		console.log(variables);
		$("#txtDepto").val(variables['nomDepto']);
		$("#txtLaboratorioRp").val(variables['nomLab']);
		//llenar datos
		$("#txtClaveMaestroRp").val(variables['cveMaestro']);
		$("#txtNombreMaestroRp").val(variables['nomMaestro']);
		$("#txtPeriodoRp").val(variables['periodo']);
		$("#txtCarreraRp").val(variables['carrera']);
		$("#txtMateriaRp").val(variables['nomMateria']);
		$("#txtHoraMatRp").val(variables['horaMat']+": 00");
		$("#txtPracticaRp").val(variables['nomPractica']);
		$("#txtHoraPractRp").val(variables['horaPrac']);
		$("#txtFechaPracticaRp2").val(variables['fechaPrac']);
		llenarTabla(variables['periodo'],variables['materia'],variables['horaMat'],variables['practica'],variables['fechaPrac'],variables['horaPrac']);
	}//Termina funcion para llenar los datos del reporte
	
	//Inicia función para obtener los datos de la url
	var obtenerDatos = function()
	{
		var dir = document.location.href;
		var dr 	= dir.split('?')[1];
		var dat = dr.split('&');
		var variables = {};//objeto
		var con = dat.length; 
		for (var i =0; i < con; i++) 
		{
			var aux = dat[i].split('=');
			variables[aux[0]] = unescape(decodeURI(aux[1]));
		}
		return variables;
	}//Termina función para obtener los datos de la url

	//Inicio función para imprimir pantalla de reporte
	var imprimirPDF = function()
    {
    	$("#btnImprimir").hide();
    	//window.close();
    	window.print();
    }//Termina función para imprimir pantalla de reporte
	
	//Inicio función para construir la tabla
	var llenarTabla = function(periodo,materia,horaMat,practica,fechaPrac,horaPrac)
	{	
		var p 		= periodo;
		var m 		= materia;
		var hrmat 	= horaMat;
		var pr 	  	= practica;
		var fcPr 	= fechaPrac;
		var hrPr 	= horaPrac;
		var parametros = "opc=listaAlumnos1"+
								"&periodo="+p+
								"&materia="+m+
								"&horaMat="+hrmat+
								"&practica="+pr+
								"&fecha="+fcPr+
								"&horaPract="+hrPr+
								"&id="+Math.random();
			console.log(parametros);
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
		    			//crear tabla de asistencia de alumnos
		    			$("#tbListaAsistenciaRp").html(" ");
						console.log(response.renglones);
						$("#tbListaAsistenciaRp").append(response.renglones);
		    		}
		    		else
		    		{
		    			console.log("else");
						$("#tbListaAsistenciaRp").html(" ");
						$("#tbListaAsistenciaRp").html(" ");
		    		}
				},
				error: function(xhr, ajaxOptions,x)
				{
					console.log("Error de conexión datos Maestro");
					console.log(xhr);	
				}
			});
	}

	llenarDatosReporte();
	
	//eventos
	$("#btnImprimir").on("click",imprimirPDF);	
}
$(document).on("ready",inicioreporte);