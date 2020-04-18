	var intval="";
	var cuenta="";
	var notif="";
	var monit="";

	$(function(){
		$("#cargando").removeClass("is-active");
		acceso();
		if(intval==""){
			intval=window.setInterval("sesion_ver()",3000);
		}
	});
	function acceso(){
		$.ajax({
			data:  {
				"ctrl":"control",
				"function":"login"
			},
			url:   'control_db.php',
			type:  'post',
			timeout:30000,
			beforeSend: function () {
				$("#cargando").addClass("is-active");
			},
			success:  function (response) {
				console.log(response);
				if (isJSON(response)){
					var datos = JSON.parse(response);
					if (datos.sess=="cerrada"){
						$("#header").html("");
						$("#bodyx").html("");
						$("#modal_dispo").removeClass("modal-lg");
						$("#modal_form").load("dash/login.php");
						$('#myModal').modal({backdrop: 'static', keyboard: false})
						$('#myModal').modal('show');
					}
					if (datos.sess=="abierta"){
						$("#header").load("dash/header.php");
						$("#bodyx").load("dash/menu.php");
						$("#modal_dispo").addClass("modal-lg");
						if(datos.fondo.length>0){
							$("body").css("background-image","url('"+datos.fondo+"')");
						}
						else{
							$("body").css("background-image","url('fondo/27A.jpg')");
						}
						setTimeout(fondos, 15000);
						loadContent(location.hash.slice(1));
					}
				}
				else{
					$("#header").html("");
					$("#bodyx").html("");
					$("#modal_dispo").removeClass("modal-lg");
					$("#modal_form").load("dash/login.php");
					$('#myModal').modal({backdrop: 'static', keyboard: false})
					$('#myModal').modal('show');
				}
				$("#cargando").removeClass("is-active");
			},
			error: function(jqXHR, textStatus, errorThrown) {
				if(textStatus==="timeout") {
					$("#cargando").removeClass("is-active");
					$("#bodyx").html("<div class='container' style='background-color:white; width:300px'><center><img src='img/giphy.gif' width='300px'></center></div><br><center><div class='alert alert-danger' role='alert'>Ocurrio un error intente de nuevo en unos minutos, vuelva a entrar o presione ctrl + F5, para reintentar</div></center> ");
				}
			}
		});
	}

	$(window).on('hashchange',function(){
		loadContent(location.hash.slice(1));
	});
	var url=window.location.href;
	var hash=url.substring(url.indexOf("#")+1);

	if(hash===url || hash===''){
		hash='dash/index';
	}
	function loadContent(hash){
		$("#cargando").addClass("is-active");
		var id=$(this).attr('id');
		if(hash===''){
			hash= 'dash/index';
		}
		$('html, body').animate({strollTop:0},'600','swing');

		var destino=hash + '.php';
		$.ajax({
			url: destino,
			type: "POST",
			timeout:30000,
			beforeSend: function () {
				$("#contenido").html("<div class='container' style='background-color:white; width:300px'><center><img src='img/carga.gif' width='300px'></center></div>");
			},
			success:  function (response) {
				$("#contenido").html(response);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				if(textStatus==="timeout") {
					$("#contenido").html("<div class='container' style='background-color:white; width:300px'><center><img src='img/giphy.gif' width='300px'></center></div><br><center><div class='alert alert-danger' role='alert'>Ocurrio un error intente de nuevo en unos minutos, vuelva a entrar o presione ctrl + F5, para reintentar</div></center> ");
				}
			}
		});
		$("#cargando").removeClass("is-active");
	}

	function sesion_ver(){
		$.ajax({
			data:  {
				"ctrl":"control",
				"function":"ses"
			},
			url: "control_db.php",
			type: "post",
			success:  function (response) {
				console.log(response);
				if (isJSON(response)){
					var datos = JSON.parse(response);
					if (datos.sess=="cerrada"){
						$("#header").html("");
						$("#bodyx").html("");
						$("#modal_dispo").removeClass("modal-lg");
						$("#modal_form").load("dash/login.php");
						$('#myModal').modal({backdrop: 'static', keyboard: false})
						$('#myModal').modal('show');
					}
				}
			}
		});
	}
	function fondos(){
		var parametros={
			"ctrl":"control",
			"function":"fondo_carga"
		};
		$.ajax({
			data:  parametros,
			url: "control_db.php",
			type: "post",
			beforeSend: function () {
			},
			success:  function (response) {
				$("#fondo").html(response);
			}
		});
	}
	function lista(id) {
		$('#'+id).DataTable({
			dom: 'Bfrtip',
			buttons: [
					{
							extend: 'copy',
							text: 'Copiar'
					},
				'csv', 'excel', 'pdf', 'print'
			],
			"pageLength": 100,
				 "language": {
				"sSearch": "Buscar aqui",
				"lengthMenu": "Mostrar _MENU_ registros",
				"zeroRecords": "No se encontró",
				"info": " Página _PAGE_ de _PAGES_",
				"infoEmpty": "No records available",
				"infoFiltered": "(filtered from _MAX_ total records)",
				"paginate": {
					"first":      "Primero",
					"last":       "Ultimo",
					"next":       "Siguiente",
					"previous":   "Anterior"
				},
			}
		});
	}
	function fechas () {
		$.datepicker.regional['es'] = {
			 closeText: 'Cerrar',
			 yearRange: '1910:2040',
			 prevText: '<Ant',
			 nextText: 'Sig>',
			 currentText: 'Hoy',
			 monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
			 monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
			 dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
			 dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
			 dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
			 weekHeader: 'Sm',
			 dateFormat: 'dd-mm-yy',
			 firstDay: 0,
			 isRTL: false,
			 showMonthAfterYear: false,
			 yearSuffix: ''
		 };

		$.datepicker.setDefaults($.datepicker.regional['es']);
		$(".fechaclass").datepicker();
	};
	function salir(){
		$.ajax({
			data:  {
				"ctrl":"control",
				"function":"salir"
			},
			url:   'control_db.php',
			type:  'post',
			beforeSend: function () {
				$("#cargando").addClass("is-active");
			},
			success:  function (response) {
				acceso();
				$("#cargando").removeClass("is-active");
			}
		});
	}
	$(document).on('submit','#acceso',function(e){
		e.preventDefault();
		var tipo=1;
		var userAcceso=document.getElementById("userAcceso").value;
		var passAcceso=$.md5(document.getElementById("passAcceso").value);

		$.ajax({
		  url: "control_db.php",
			type: "POST",
		  data: {
				"tipo":tipo,
				"ctrl":"control",
				"function":"acceso",
				"userAcceso":userAcceso,
				"passAcceso":passAcceso
		  },
		  success: function( response ) {
				var data = JSON.parse(response);
				if (data.acceso==1){
					acceso();

					$('#myModal').modal('hide');
					$("#modal_dispo").addClass("modal-lg");
				}
				else{
					Swal.fire({
						  type: 'error',
						  title: 'Usuario o contraseña incorrecta',
						  showConfirmButton: false,
						  timer: 1000
					})
				}
		  }
		});

	});
	//////////////////////subir archivos
	$(document).on("click","[id^='fileup_']",function(e){
		e.preventDefault();
		var id = $(this).data('id');
		var ruta = $(this).data('ruta');
		var tipo = $(this).data('tipo');
		var ext = $(this).data('ext');
		var tabla = $(this).data('tabla');
		var campo = $(this).data('campo');
		var keyt = $(this).data('keyt');
		var destino = $(this).data('destino');
		var iddest = $(this).data('iddest');
		var proceso="";
		if ( $(this).data('proceso') ) {
			proceso=$(this).data('proceso');
		}
		$("#modal_form").load("archivo.php?id="+id+"&ruta="+ruta+"&ext="+ext+"&tipo="+tipo+"&tabla="+tabla+"&campo="+campo+"&keyt="+keyt+"&destino="+destino+"&iddest="+iddest+"&proceso="+proceso);
	});
	$(document).on('change',"#prefile",function(e){
		e.preventDefault();
		var control=$(this).attr('id');
		var accept=$(this).attr('accept');

		var fileSelect = document.getElementById(control);
		var files = fileSelect.files;
		var formData = new FormData();
		for (var i = 0; i < files.length; i++) {
		   var file = files[i];
		   formData.append('photos'+i, file, file.name);
		}
		var tam=(fileSelect.files[0].size/1024)/1024;
		if (tam<30){
			var xhr = new XMLHttpRequest();
			xhr.open('POST','control_db.php?function=subir_file&ctrl=control');
			xhr.onload = function() {
			};
			xhr.upload.onprogress = function (event) {
				var complete = Math.round(event.loaded / event.total * 100);
				if (event.lengthComputable) {
					btnfile.style.display="none";
					progress_file.style.display="block";
					progress_file.value = progress_file.innerHTML = complete;
					// conteo.innerHTML = "Cargando: "+ nombre +" ( "+complete+" %)";
				}
			};
			xhr.onreadystatechange = function(){
				if(xhr.readyState === 4 && xhr.status === 200){
					progress_file.style.display="none";
					btnfile.style.display="block";
					try {
						var data = JSON.parse(xhr.response);
						for (i = 0; i < data.length; i++) {
							$("#contenedor_file").html("<div style='border:0;float:left;margin:10px;'>"+
							"<input type='hidden' id='direccion' name='direccion' value='"+data[i].archivo+"'>"+
							"<img src='historial/"+data[i].archivo+"' width='300px'></div>");
						}
					}
					catch (err) {
					   alert(xhr.response);
					}
				}
			}
			xhr.send(formData);
		}
		else{
			alert("Archivo muy grande");
		}
	});
	$(document).on('submit','#upload_File',function(e){
		e.preventDefault();
		var funcion="guardar_file";
		var destino = $("#destino").val();
		var iddest = $("#iddest").val();
		var proceso="control_db.php";

		if ( $("#direccion").length ) {
			var dataString = $(this).serialize()+"&function="+funcion+"&ctrl=control";
			$.ajax({
				data:  dataString,
				url: proceso,
				type: "post",
				success:  function (response) {
					var datos = JSON.parse(response);
					if (datos.error==0){
						lugar=destino+".php?id="+iddest;
						$("#trabajo").load(lugar);
						$('#myModal').modal('hide');
						Swal.fire({
						  type: 'success',
						  title: "Se cargó correctamente",
						  showConfirmButton: false,
						  timer: 1000
						});
					}
					else{
						$.alert(datos.terror);
					}
				}
			});
		}
		else{
			$.alert('Debe seleccionar un archivo');
		}
	});
	$(document).on('click','.sidebar a', function() {
       $(".sidebar a").removeClass("activeside");
       $(this).addClass("activeside");
	});
	$(document).on("click","#fondocambia",function(e){
		e.preventDefault();
		var imagen=$("img", this).attr("src");
		$.ajax({
			data:  {
				"imagen":imagen,
				"ctrl":"control",
				"function":"fondo"
			},
			url:   'control_db.php',
			type:  'post',
			success:  function () {
				$("body").css("background-image","url('"+imagen+"')");
			}
		});
	});
	$(document).on('click','#sidebarCollapse', function () {
		$('#navx').toggleClass('sidenav');
        $('#contenido').toggleClass('fijaproceso');
        $('#sidebar').toggleClass('active');
    });
	$(document).on("click","[id^='edit_'], [id^='lista_'], [id^='new_']",function(e){	//////////// para ir a alguna opcion
			e.preventDefault();
			monit="";
			var id=$(this).attr('id');
			var funcion="";
			if ( $(this).data('funcion') ) {
				funcion = $(this).data('funcion');
			}
			var lugar="";
			var contenido="#trabajo";
			var xyId=0;
			var valor="";
			padre=id.split("_")[0]
			opcion=id.split("_")[1];
			$("#cargando").addClass("is-active");

			if ( $(this).data('valor')!=undefined ) {
				valor=$("#"+$(this).data('valor')).val();
			}

			if ( $(this).data('div')!=undefined ) {
				contenido="#"+$(this).data('div');
			}

			if(padre=="edit" || padre=="new" || padre=="lista"){
				lugar = $("#"+id).data('lugar')+".php";
				if(padre=="edit"){
					lugar=$(this).attr("data-lugar")+".php";
					if ( $(this).closest(".edit-t").attr("id")){
						xyId = $(this).closest(".edit-t").attr("id");
					}
					else{
						xyId = $("#"+id).data('id');
					}
				}
			}
			$.ajax({
				data:  {"algo":"algo","padre":padre,"opcion":opcion,"id":xyId,"nombre":id,"funcion":funcion,"valor":valor},
				url:   lugar,
				type:  'post',
				timeout:30000,
				beforeSend: function () {
					$(contenido).html("<div class='container' style='background-color:white; width:300px'><center><img src='img/carga.gif' width='300px'></center></div>");
				},
				success:  function (response) {
					$(contenido).html(response);
				},
				error: function(jqXHR, textStatus, errorThrown) {
					if(textStatus==="timeout") {
						$("#container").html("<div class='container' style='background-color:white; width:300px'><center><img src='img/giphy.gif' width='300px'></center></div><br><center><div class='alert alert-danger' role='alert'>Ocurrio un error intente de nuevo en unos minutos, vuelva a entrar o presione ctrl + F5, para reintentar</div></center> ");
					}
				}

			});
			$("#cargando").removeClass("is-active");
		});
	$(document).on("click","[id^='select_']",function(e){								//////////// para consulta con combo
		var combo=$(this).data('combo');
		var combo2;
		var id2;
		var lugar=$(this).data('lugar')+".php";
		var div;
		if ($(this).data('combo2')){
			combo2=$(this).data('combo2');
			id2=$("#"+combo2).val();
		}
		if ( $(this).data('div') ) {
			div = $(this).data('div');
		}
		else{
			div="trabajo";
		}
		var id=$("#"+combo).val();
		$.ajax({
			data:  {"id":id,"id2":id2},
			url:   lugar,
			type:  'post',
			success:  function (response) {
				$("#"+div).html(response);
			}
		});
	});
	$(document).on("click","[id^='imprimir_'], [id^='imprime_'], [id^='imprimeid_']",function(e){
		e.preventDefault();
		var id=$(this).attr('id');
		var padre=id.split("_")[0]
		var opcion=id.split("_")[1];
		var valor=0;
		var xyId;

		if ( $(this).data('valor') ) {
			var control=$(this).data('valor');
			valor = $("#"+control).val();
		}

		if(padre=="imprimir"){
			xyId = $(this).closest(".edit-t").attr("id");
		}
		if(padre=="imprime"){
			xyId= $("#id").val();
		}
		if(padre=="imprimeid"){
			xyId=$(this).data('id');
		}

		if( $("#"+id).data('select') ){
			var select=$("#"+id).data('select');
			xyId=$("#"+select).val();
		}
		else{

		}
		var lugar = $("#"+id).data('lugar')+".php";
		var tipo = $("#"+id).data('tipo');
		VentanaCentrada(lugar+'?id='+xyId+'&tipo='+tipo+'&valor='+valor,'Impresion','','1024','768','true');
	});
	$(document).on('submit',"[id^='form_']",function(e){
		e.preventDefault();
		$("#cargando").addClass("is-active");
		var id=$(this).attr('id');
		var lugar = $(this).data('lugar')+".php";
		var destino = $(this).data('destino');
		var div;
		var funcion="";
		var cerrar=0;

		if ( $(this).data('funcion') ) {
			var funcion = $(this).data('funcion');
		}
		if ( $(this).data('div') ) {
			div = $(this).data('div');
		}
		else{
			div="trabajo";
		}
		if ( $(this).data('cmodal') ) {
			cerrar=$(this).data('cmodal');
		}
		var dataString = $(this).serialize()+"&function="+funcion;
		$.ajax({
			data:  dataString,
			url: lugar,
			type: "post",
			timeout:30000,
			success:  function (response) {
				if (isJSON(response)){
					var datos = JSON.parse(response);
					if (datos.error==0){
						document.getElementById("id").value=datos.id;
						if (destino != undefined) {
							lugar=destino+".php";
							$.ajax({
								data:  {
									"id":datos.id,
									"param1":datos.param1,
									"param2":datos.param2,
									"param3":datos.param3,
								},
								url:   lugar,
								type:  'post',
								beforeSend: function () {

								},
								success:  function (response) {
									$("#"+div).html(response);
								}
							});
						}
						if(cerrar==0){
							$('#myModal').modal('hide');
						}
						$("#cargando").removeClass("is-active");
						Swal.fire({
							type: 'success',
							title: "Se guardó correctamente #" + datos.id,
							showConfirmButton: false,
							timer: 1000
						})
					}
					else{
						$("#cargando").removeClass("is-active");
						$.alert(datos.terror);
					}
				}
				else{
					$("#cargando").removeClass("is-active");
					$.alert(response);
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				if(textStatus==="timeout") {
					$.alert("<div class='container' style='background-color:white; width:300px'><center><img src='img/giphy.gif' width='300px'></center></div><br><center><div class='alert alert-danger' role='alert'>Ocurrio un error intente de nuevo en unos minutos, vuelva a entrar o presione ctrl + F5, para reintentar</div></center> ");
				}
			}
		});
	});
	function isJSON (something) {
		if (typeof something != 'string')
				something = JSON.stringify(something);

		try {
				JSON.parse(something);
				return true;
		} catch (e) {
				return false;
		}
	}
	$(document).on('submit',"[id^='consulta_']",function(e){
		e.preventDefault();
		var dataString = $(this).serialize();
		var div = $(this).data('div');
		var funcion = $(this).data('funcion');

		var destino = $(this).data('destino')+".php?funcion="+funcion;
		$.ajax({
			data:  dataString,
			url: destino,
			type: "post",
			success:  function (response) {
				$("#"+div).html(response);
			}
		});
	});
	$(document).on("click","[id^='eliminar_']",function(e){
		e.preventDefault();
		var id = $(this).data('id');
		var id2 = $(this).data('id2');
		var id3 = $(this).data('id3');
		var lugar = $(this).data('lugar')+".php";
		var destino = $(this).data('destino')+".php";
		var iddest = $(this).data('iddest');
		var div;

		if ( $(this).data('funcion') ) {
			var funcion = $(this).data('funcion');
		}
		else{
			return;
		}

		if ( $(this).data('div') ) {
			div = $(this).data('div');
		}
		else{
			div="trabajo";
		}
		$.confirm({
			title: 'Eliminar',
			content: '¿Desea borrar el registro seleccionado?',
			buttons: {
				Aceptar: function () {
					var parametros={
						"id":id,
						"id2":id2,
						"id3":id3,
						"iddest":iddest,
						"function":funcion
					};
					$.ajax({
						data:  parametros,
						url: lugar,
						type:  'post',
						timeout:10000,
						beforeSend: function () {
							$("#cargando").addClass("is-active");
						},
						success:  function (response) {
							if (isJSON(response)){
								var datos = JSON.parse(response);
								if (datos.error==0){
									if (destino != undefined) {
										$("#"+div).html("");
										$.ajax({
											data:  {"id":iddest},
											url:   destino,
											type:  'post',
											success:  function (response) {
												$("#"+div).html(response);
											}
										});
									}
									Swal.fire({
									  type: 'success',
									  title: "Se eliminó correctamente",
									  showConfirmButton: false,
									  timer: 700
									});
									$("#cargando").removeClass("is-active");
								}
								else{
									$("#cargando").removeClass("is-active");
									$.alert("error"+datos.terror);
								}
							}
							else{
								$("#cargando").removeClass("is-active");
								$.alert("error"+response);
							}
						},
						error: function(jqXHR, textStatus, errorThrown) {
							if(textStatus==="timeout") {
								$.alert("<div class='container' style='background-color:white; width:300px'><center><img src='img/giphy.gif' width='300px'></center></div><br><center><div class='alert alert-danger' role='alert'>Ocurrio un error intente de nuevo en unos minutos, vuelva a entrar o presione ctrl + F5, para reintentar</div></center> ");
							}
						}
					});
				},
				Cancelar: function () {
				}
			}
		});
	});
	$(document).on("change","#yearx_val",function(e){
		e.preventDefault();
		var id=$(this).val();
		$.ajax({
			data:  {
				"id":id,
				"ctrl":"control",
				"function":"anioc"
			},
			url:   "control_db.php",
			type:  'post',
			success:  function (response) {
				$("#contenido").load('dash/index.php');
				Swal.fire({
				  type: 'success',
				  title: response,
				  showConfirmButton: false,
				  timer: 1000
				});
			}
		});
	});
	$(document).on("click","[id^='delfile_']",function(e){
		e.preventDefault();
		var ruta = $(this).data('ruta');
		var keyt = $(this).data('keyt');
		var key = $(this).data('key');
		var tabla = $(this).data('tabla');
		var campo = $(this).data('campo');
		var tipo = $(this).data('tipo');
		var iddest = $(this).data('iddest');
		var divdest = $(this).data('divdest');
		var dest = $(this).data('dest');
		var borrafile=0;
		if ( $(this).data('borrafile') ) {
			borrafile=$(this).data('borrafile');
		}

		var parametros={
			"ruta":ruta,
			"keyt":keyt,
			"key":key,
			"tabla":tabla,
			"campo":campo,
			"tipo":tipo,
			"borrafile":borrafile,
			"ctrl":"control",
			"function":"eliminar_file"
		};

		$.confirm({
			title: 'Eliminar',
			content: '¿Desea eliminar el archivo?',
			buttons: {
				Aceptar: function () {
					$.ajax({
						url: "control_db.php",
						type: "POST",
						data: parametros
					}).done(function(response){
						var datos = JSON.parse(response);
						if (datos.error==0){
							$("#"+divdest).load(dest+iddest);
							Swal.fire({
							  type: 'success',
							  title: "Se eliminó correctamente",
							  showConfirmButton: false,
							  timer: 1000
							})
						}
						else{
							$.alert(echo);
						}
					});
				},
				Cancelar: function () {
					$.alert('Canceled!');
				}
			}
		});
	});
	$(document).on("click","[id^='winmodal_']",function(e){
		e.preventDefault();
		var id = "0";
		var id2 = "0";
		var id3 = "0";
		var lugar = $(this).data('lugar');

		if ( $(this).data('id') ) {
			id = $(this).data('id');
		}
		if ( $(this).data('id2') ) {
			id2 = $(this).data('id2');
		}
		if ( $(this).data('id3') ) {
			id3 = $(this).data('id3');
		}

		$("#modal_form").load(lugar+".php?id="+id+"&id2="+id2+"&id3="+id3);
		$('#myModal').modal({backdrop: 'static', keyboard: false})
		$('#myModal').modal('show');
	});
	$(document).on("click",'#recuperar',function(e){
		e.preventDefault();
		$.ajax({
			url:   'acceso/recuperar.php',
			  beforeSend: function () {
					$("#data").html("Procesando, espere por favor...");
			  },
			  success:  function (response) {
				$("#data").html('');
				$("#data").html(response);
			  }
		});
	});
	$(document).on('submit','#recovery',function(e){
			e.preventDefault();
			var telefono=document.getElementById("userAcceso").value;
			telefono=telefono.trim();
			if(telefono.length>2){
				var btn=$(this).find(':submit')
				$(btn).attr('disabled', 'disabled');
				var tmp=$(btn).children("i").attr('class');
				$(btn).children("i").removeClass();
				$(btn).children("i").addClass("fas fa-spinner fa-pulse");

				var tipo=2;
				var parametros={
					"ctrl":"control",
					"function":"recuperar",
					"tipo":tipo,
					"telefono":telefono
				};
				$.ajax({
					url: "control_db.php",
					type: "post",
					data: parametros,
					beforeSend: function(objeto){
						$(btn).children("i").addClass(tmp);
					},
					success:function(response){
						if (response == "") {
							Swal.fire({
							  type: "error",
							  title: response,
							  showConfirmButton: false,
							  timer: 1000
							});

						} else {
							Swal.fire({
							  type: 'success',
							  title: response,
							  showConfirmButton: false,
							  timer: 3000
							});
						}
						$(btn).children("i").removeClass();
						$(btn).children("i").addClass(tmp);
						$(btn).prop('disabled', false);
					}
				});
			}
			else{
				$( "#telefono" ).focus();
				$( "#telefono" ).val("");
			}
		});
