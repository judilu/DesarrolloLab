var tipoUsu = -1;
var inicio = function ()
{
	var verificarUsuario = function()
	{
		//fecha del sistema
		var f  = new Date();
		var dd = f.getDate();
		var mm = (f.getMonth())+1;
		(dd<10) ? (dd="0"+dd) : dd;
		(mm<10) ? (mm="0"+mm) : mm;
		var fe  = (dd+"/"+mm+"/"+f.getFullYear());
		$("#txtFechaAcceso").val(fe);
		//
		var usu = $("#txtUsuario").val();
		var cve = $("#txtClave").val();
		if(usu!="" && cve!="")
		{
			var parametros = "opc=validaUsuario"+"&usuario="+usu+"&cveUsuario="+cve+"&id="+Math.random();
			$.ajax({
				cache:false,
				type: "POST",
				dataType: "json",
				url:"../data/usuarios.php",
				data: parametros,
				success: function(response)
				{
					if(response.respuesta == true)
					{
						tipoUsu = response.tipo;
						switch (response.tipo)
						{
							case "1":
							$("#acceso").hide();
							$("#genericos").show("slow");
							 var parametros = "opc=usuario1"+
							 					"&clave1="+response.claveUsuario+
							 					"&id="+Math.random();
				               $.ajax({  
				                    cache:false,
				                    type: "POST",
				                    dataType: "json",
				                    url:"../data/genericos.php",
				                    data: parametros, 
				                    success: function(data) 
				                    {  
				                    
				                    }  
				                }); 
							break;
							case "2":
							$("#acceso").hide();
							$("#genericos").show("slow");
							var parametros = "opc=usuario1"+
												"&clave1="+response.claveUsuario+
												"&id="+Math.random();
				               $.ajax({  
				                    cache:false,
				                    type: "POST",
				                    dataType: "json",
				                    url:"../data/genericos.php",
				                    data: parametros, 
				                    success: function(data) 
				                    {  
				                              
				                    }  
				                }); 
							break;
							case "3":
							$("#acceso").hide();
							$("#tabReportesGenericos").addClass("disabled");
							$("#btnAlta").hide();
							$("#btnMantenimiento").hide();
							$("#btnBaja").hide();
							$("#btnPeticionArticulo").hide();
							$("#btnPeticionesPendientes").hide();
							$("#btnPendientesLab").hide();
							$("#genericos").show("slow");
							$("#btnNuevaLabExtenos").hide();
							var parametros = "opc=usuario1"+
												"&clave1="+response.claveUsuario+
												"&id="+Math.random();
				               $.ajax({  
				                    cache:false,
				                    type: "POST",
				                    dataType: "json",
				                    url:"../data/genericos.php",
				                    data: parametros, 
				                    success: function(data) 
				                    {  
				                              
				                    }  
				                }); 
							break;
							case "4":
							$("#acceso").hide();
							$("#alumno").show("slow");
							var parametros = "opc=usuario1"+
												"&clave1="+response.claveUsuario+
												"&id="+Math.random();
				               $.ajax({  
				                    cache:false,
				                    type: "POST",
				                    dataType: "json",
				                    url:"../data/alumnos.php",
				                    data: parametros, 
				                    success: function(data) 
				                    {  
				                              
				                    }  
				                }); 
							break;
							case "5":
							$("#acceso").hide();
							$("#tabPrestamos").addClass("disabled");
							$("#tabLabs").addClass("disabled");
							$("#btnPendientes").hide();
							$("#btnEnProceso").hide();
							$("#btnListaSanciones").hide();
							$("#btnAlta").hide();
							$("#btnMantenimiento").hide();
							$("#btnBaja").hide();
							$("#btnPeticionArticulo").hide();
							$("#btnNuevaLabExtenos").hide();
							$("#genericos").show("slow");
							var parametros = "opc=usuario1"+
												"&clave1="+response.claveUsuario+
												"&id="+Math.random();
				               $.ajax({  
				                    cache:false,
				                    type: "POST",
				                    dataType: "json",
				                    url:"../data/genericos.php",
				                    data: parametros, 
				                    success: function(data) 
				                    {  
				                              
				                    }  
				                }); 
							break;	
						}//termina switch
					}
					else
					{
						//verificar en la otra vase de datos
						var parametros = "opc=usuariosMaestros1"+
											"&usuario="+usu+
											"&clave="+cve+
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
									$("#acceso").hide();
									$("#maestro").show("slow");
									//Pasando la claveUsuario
								 	var parametros = "opc=usuario1"+
								 					"&clave1="+response.maestro+
								 					"&id="+Math.random();
					               	$.ajax({  
					                    cache:false,
					                    type: "POST",
					                    dataType: "json",
					                    url:"../data/maestros.php",
					                    data: parametros, 
					                    success: function(data) 
					                    { 

					                    }  
					                });
								}
								else
								{
									$("#txtUsuario").val("");
									$("#txtClave").val("");
									sweetAlert("Usuario y/o contraseña incorrectos", "tecle un usuario y/o contraseña correctos", "error");
								}
							},
							error: function(xhr, ajaxOptions,x)
							{
								$("#txtUsuario").val("");
								$("#txtClave").val("");
								console.log("Error de conexión login maestros");
								console.log(xhr);
							}
						});
						
					}//termina else maestro
				},
				error: function(xhr,ajaxOptions,x)
				{
					console.log("Error de conexión login");
					usu = $("#txtUsuario").val("");
					cve = $("#txtClave").val("");
				}
			});
		}
		else
		{
			sweetAlert("Usuario y/o contraseña incorrectos", "tecle un usuario y/o contraseña correctos", "error");
		}
	}
	//cambiar contraseña
	var cambiarContra = function()
	{
		//ocultar elementos
		$("#accesoPrin").hide();
		$("#cambioContra").show("slow");
	}
	var cambiarContras = function()
	{
		var usu 	= $("#txtUsuarioC").val();
		var cveA 	= $("#txtClaveC").val();
		var clave  	= $("#txtNuevaClave").val();
		var clave2 	= $("#txtNuevaClaveR").val();
		
		if( usu!="" && cveA!="" && clave != "" && clave2 != "" )
		{
			//consultar si existe el usuario
			var parametros = "opc=validaUsuario"+
							"&usuario="+usu+
							"&cveUsuario="+cveA+
							"&id="+Math.random();
				$.ajax({
					cache:false,
					type: "POST",
					dataType: "json",
					url:"../data/usuarios.php",
					data: parametros,
					success: function(response)
					{
						if(response.respuesta == true)
						{
							//cambiar contra
							realizarCambio(clave,clave2,response.claveUsuario);
						}
						else
						{
							//verificar en la otra vase de datos
							var parametros = "opc=usuariosMaestros1"+
											"&usuario="+usu+
											"&clave="+cveA+
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
										$("#txtUsuario").val("");
										$("#txtClave").val("");
										sweetAlert("No es posible cambiar su contraseña", "Si desea cambiar la contraseña, deberá hacerlo directamente sobre el sitec", "error");										
									}
									else
									{
										$("#txtUsuarioC").val("");
										$("#txtClaveC").val("");
										$("#txtNuevaClave").val("");
										$("#txtNuevaClaveR").val("");
										sweetAlert("Usuario y/o contraseña incorrectos", "tecle un usuario y/o contraseña correctos", "error");
									}
								},
								error: function(xhr, ajaxOptions,x)
								{
									$("#txtUsuarioC").val("");
									$("#txtClaveC").val("");
									$("#txtNuevaClave").val("");
									$("#txtNuevaClaveR").val("");
									console.log("Error de conexión login maestros");
									console.log(xhr);
								}
							});
						}
					},				
					error: function(xhr, ajaxOptions,x)
					{
						$("#txtUsuarioC").val("");
						$("#txtClaveC").val("");
						$("#txtNuevaClave").val("");
						$("#txtNuevaClaveR").val("");
						console.log("Error de conexión cambio ");
					}
				});
			//termina
		}
		else
		{
			$("#txtUsuarioC").val("");
			$("#txtClaveC").val("");
			$("#txtNuevaClave").val("");
			$("#txtNuevaClaveR").val("");
			sweetAlert("Verifique que todos los campos esten llenos", "tecle todos los campos", "warning");
		}
	}

	var realizarCambio = function (cve1,cve2,usuario)
	{
		var clave 	= cve1;
		var clave2 	= cve2;
		var usu 	= usuario;
		if(clave === clave2)
		{
			var parametros = "opc=cambiarContra1"+
							"&usuario="+usu+
							"&clave="+clave+
							"&id="+Math.random();
			$.ajax({
				cache:false,
				type: "POST",
				dataType: "json",
				url:"../data/usuarios.php",
				data: parametros,
				success: function(response)
				{
					if(response.respuesta == true)
					{
						$("#accesoPrin").show("slow");
						$("#cambioContra").hide();
						//
						$("#txtUsuarioC").val("");
						$("#txtClaveC").val("");
						$("#txtNuevaClave").val("");
						$("#txtNuevaClaveR").val("");
						
						$("#txtUsuario").val("");
						$("#txtClave").val("");
						 swal("Bien hecho!", "Su contraseña fue cambiada exitosamente!", "success")
					}
					else
					{
						$("#txtUsuarioC").val("");
						$("#txtClaveC").val("");
						$("#txtNuevaClave").val("");
						$("#txtNuevaClaveR").val("");
						sweetAlert("warning", "Su contraseña no fue cambiada exitosamente", "warning");
					}
				},				
				error: function(xhr, ajaxOptions,x)
				{
					$("#txtUsuarioC").val("");
					$("#txtClaveC").val("");
					$("#txtNuevaClave").val("");
					$("#txtNuevaClaveR").val("");
					console.log("Error de conexión cambio ");
				}
			});					
		}
		else
		{
			$("#txtNuevaClave").val("");
			$("#txtNuevaClaveR").val("");
			sweetAlert("Las contraseñas no coinciden", "verificar que la contraseña, deben ser iguales las dos", "error");								
		}
	}

	//Configuramos los eventos
	$("#btnIngresar").on("click",verificarUsuario);
	$("#btnCambiarContra").on("click",cambiarContra);
	$("#btnRealizarCambio").on("click",cambiarContras);
}
$(document).on("ready",inicio);
