		var chatx="";
		var newx="";

		function chat_inicia(){
			if(chatx==""){
				chatx=window.setInterval("conectados()",120000);
			}
			if(newx==""){
				newx=window.setInterval("nuevos()",2000);
			}
			$.ajax({
				data:  {
					"function":"inicia"
				},
				url: "chat/chat.php",
				type: "post",
				success:  function (response) {
					$("#chatx").html(response);
				}
			});
			conectados();
		}
		function conectados(){
			$.ajax({
				data:  {
					"function":"conectados"
				},
				url: "chat/chat.php",
				type: "post",
				success:  function (response) {
					$("#conecta_x").html(response);
				}
			});
		}
		function carga(id){
			$.ajax({
				data:  {
					"function":"carga",
					"id":id
				},
				url: "chat/chat.php",
				type: "post",
				success:  function (response) {
					$("#chat_"+id).html(response);
					scroll("contenido"+id);
				}
			});
		}

		function nuevos(){
			$.ajax({
				data:  {
					"function":"nuevos"
				},
				url: "chat/chat.php",
				type: "post",
				timeout:1000,
				success:  function (response) {
					if(response.length>0){
						var data = JSON.parse(response);
						for (i = 0; i < data.length; i++) {
						  register_popup(data[i].para);
						  $("#contenido"+data[i].para).append(data[i].texto);
						  scroll("contenido"+data[i].para);
						  $("#head"+data[i].para).addClass("brilla");
						}
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					if(textStatus==="timeout") {
						
					}
				}
			});
		}
		function leido(id){
			$.ajax({
				data:  {
					"function":"leido",
					"id":id
				},
				url: "chat/chat.php",
				type: "post",
				success:  function (response) {
				}
			});
			$("#head"+id).removeClass("brilla");
		}
		function mensaje_manda(texto,id){
			$.ajax({
				data:  {
					"function":"manda",
					"id":id,
					"texto":texto
				},
				url: "chat/chat.php",
				type: "post",
				success:  function (response) {
					$("#contenido"+id).append(response);
					scroll("contenido"+id);
					document.getElementById("mensaje_"+id).value="";
				}
			});
		}

		$(document).on("keyup",".mensaje_chat",function(e){
			e.preventDefault();
			var textarea=$(this).attr('id');
			if ( e.which == 13 ) {
				var id= $(this).data('para');
				var texto=$(this).html();
				$.ajax({
					data:  {
						"function":"manda",
						"id":id,
						"texto":texto
					},
					url: "chat/chat.php",
					type: "post",
					success:  function (response) {
						$("#"+textarea).empty();
						$("#contenido"+id).append(response);
						scroll("contenido"+id);
						document.getElementById("mensaje_"+id).value="";
					}
				});
			}
		});
		$(document).on("keyup","#myInput",function(e){
			var value = $(this).val().toLowerCase();
			$("#myUL a").filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			});
		});
		$(document).on('change',"[id^='subechat_']",function(e){
			var control=$(this).data('control');
			var id = $(this).data('id');
			var ruta = $(this).data('ruta');
			var funcion=$(this).data('funcion');
			var urlx=$(this).data('urlx');

			var fileSelect = document.getElementById(control);
			var files = fileSelect.files;
			var formData = new FormData();
			for (var i = 0; i < files.length; i++) {
			   var file = files[i];
			   formData.append('photos'+i, file, file.name);
			}

			$("#contenido"+id).append("<div class='b2' id='carga_f'>Cargando...<br><progress value='22' max='100' class='progress-bar progress-bar-danger' id='progress' name='progress' style='width:100%'></progress><br></div>");
			scroll("contenido"+id);

			var xhr = new XMLHttpRequest();
			xhr.open('POST','chat/chat.php?function=subir_archivo&id='+id+'&ruta='+ruta);
			xhr.onload = function() {
			};

			xhr.upload.onprogress = function (event) {
				var complete = Math.round(event.loaded / event.total * 100);
				if (event.lengthComputable) {
					progress.value = progress.innerHTML = complete;
				}
			};
			xhr.onreadystatechange = function(){
				if(xhr.readyState === 4 && xhr.status === 200){
					var data = JSON.parse(xhr.response);
					for (i = 0; i < data.length; i++) {
						var parametros={
							"id":id,
							"function":funcion,
							"archivo":data[i].archivo,
							"original":data[i].original
						};
						$.ajax({
							data: parametros,
							url: urlx,
							type: "post",
							success:  function (response) {
								Swal.fire({
								  type: 'success',
								  title: "Se cargó correctamente",
								  showConfirmButton: false,
								  timer: 1000
								})
								$("#contenido"+id).append(response);
								scroll("contenido"+id);
								$("#carga_f").remove();
							}
						});
					}
				}
			}
			xhr.send(formData);
		});
		$(document).on("click",".emoji",function(e){
			var id= $(this).data('id');
			$("#opcion_"+id).toggleClass('optionvisible');
		});
		$(document).on("click",".emojiimg",function(e){
			var id= $(this).data('id');
			var lugar= $(this).data('lugar');
			$("#mensaje_"+id).append("<img src='"+lugar+"' width='16px'>");
			$("#opcion_"+id).toggleClass('optionvisible');
			$("#mensaje_"+id).focus();
		});

		var tempo="";
		var ints="";
		var dis_val="";

		//this function can remove a array element.
		Array.remove = function(array, from, to) {
			var rest = array.slice((to || from) + 1 || array.length);
			array.length = from < 0 ? array.length + from : from;
			return array.push.apply(array, rest);
		};

		//this variable represents the total number of popups can be displayed according to the viewport width
		var total_popups = 0;

		//arrays of popups ids
		var popups = [];

		//this is used to close a popup
		function close_popup(id){
			for(var iii = 0; iii < popups.length; iii++)
			{
				if(id == popups[iii])
				{
					$("#chat_"+id).remove();
					Array.remove(popups, iii);
					calculate_popups();
					return;
				}
			}
		}
		//displays the popups. Displays based on the maximum number of popups that can be displayed on the current viewport width
		function display_popups(){
			var right = 10;
			var iii = 0;
			for(iii; iii < total_popups; iii++)
			{
				if(popups[iii] != undefined)
				{
					var element = document.getElementById("chat_"+popups[iii]);
					element.style.right = right + "px";
					right = right + 305;
					element.style.display = "block";
				}
			}
			for(var jjj = iii; jjj < popups.length; jjj++)
			{
				var element = document.getElementById(popups[jjj]);
				element.style.display = "none";
			}

		}

		//creates markup for a new popup. Adds the id to popups array.
		function register_popup(id){
			showIsActive(1,id);
			for(var iii = 0; iii < popups.length; iii++)
			{
				//already registered. Bring it to front.
				if(id == popups[iii])
				{
					Array.remove(popups, iii);
					popups.unshift(id);
					calculate_popups();
					return;
				}
			}
			$('body').append('<div class="card bg-dark popup-box text-white" id="chat_'+ id +'"></div>');
			popups.unshift(id);
			calculate_popups();
			carga(id);
		}
		//calculate the total number of popups suitable and then populate the toatal_popups variable.
		function calculate_popups(){
			var width = window.innerWidth;
			if(width < 100)
			{
				total_popups = 0;
			}
			else
			{
				width = width - 100;
				//220 is width of a single popup box
				total_popups = parseInt(width/100);
			}
			display_popups();
		}
		function existe(id,desde){
			for(var iii = 0; iii < popups.length; iii++)
			{
				//already registered. Bring it to front.
				if(id == popups[iii])
				{
					return;
				}
			}
			xajax_conversar(id,desde,1);
		}
		function scroll(id){
			if ( $("#"+id).length ) {
				document.getElementById(id).scrollTop=document.getElementById(id).scrollHeight;
			}
		}
		function enfoque(id){
			document.getElementById(id).focus();
		}

		function minimizar(nombre_capa){
			document.getElementById("chat_"+nombre_capa).style.height="60px";
			document.getElementById("contenido" + nombre_capa).style.display="none";
			document.getElementById("mensajex" + nombre_capa).style.display="none";
			document.getElementById("min" + nombre_capa).style.display="none";
			document.getElementById("max" + nombre_capa).style.display="block";
		}
		function maximizar(nombre_capa){
			document.getElementById("chat_"+nombre_capa).style.height="450px";
			document.getElementById("contenido" + nombre_capa).style.display="block";
			document.getElementById("mensajex" + nombre_capa).style.display="block";
			document.getElementById("min" + nombre_capa).style.display="block";
			document.getElementById("max" + nombre_capa).style.display="none";
			scroll("contenido"+nombre_capa);
		}

		//recalculate when window is loaded and also when window is resized.
		window.addEventListener("resize", calculate_popups);
		window.addEventListener("load", calculate_popups);

		$(function() {
			window.isActive = true;
			$(window).focus(function() { this.isActive = true; });
			$(window).blur(function() { this.isActive = false; });
			showIsActive();
		});

		function showIsActive(numero,de){
			if (!window.isActive){
				if(numero>0){
					//xajax_color_mensaje(de);
					hover2.playclip();
					//notifica4000(de,"librerias/img/Chat-256.png","Mensaje nuevo");
				}
			}
		}
		function disparador(){
			if(dis_val==""){
				dis_val=window.setInterval("recurrente()",20000);
			}
		}
		function deten_dispara(){
			if(dis_val!=""){
				window.clearInterval(dis_val);
				dis_val="";
			}
		}
		function recurrente(){
			hover2.playclip();
		}
		function order_sound(){
			hover3.playclip();
		}

		/////////////////////
		function enter(e) {
			var id = e.target.id; // Elemento sobre el que se arrastra
			if (id){
				e.target.style.border = '1px dotted #555';
				$("#"+id).children().hide();
			}
		}
		function leave(e) {
			var id = e.target.id; // Elemento sobre el que se arrastra
			if (id){
				e.target.style.border = '';
				$("#"+id).children().show();
			}
		}
		function over(e) {
			var elemArrastrable = e.dataTransfer.getData("Data"); // Elemento arrastrado
			var id = e.target.id; // Elemento sobre el que se arrastra
			if (id){
				return false; // Cualquier elemento se puede soltar sobre el div destino 1
			}
		}
		function drop(e,idorden){
			e.stopPropagation(); // Stops some browsers from redirecting.
			e.preventDefault();

			var ruta="tmp";
			var funcion="carga_archivo";
			var urlx='chat/chat.php';

			var div = e.target.id; // Elemento sobre el que se arrastra
			e.target.style.border = '';
			$("#"+div).children().show();


			var xhr = new XMLHttpRequest();
			var archivosn="";

			var adicional=0;
			adicional=(idorden*100)+Math.floor((Math.random() * 100) + 1);



			var files = e.dataTransfer.files;
			var formData = new FormData();

			for(i=0; i<files.length; i++){
				formData.append('file'+i, files[i]);
				archivosn=archivosn+files[i].name;
			}

			$("#contenido"+idorden).append("<div class='b2' id='carga_f'>Cargando...<br><progress value='22' max='100' class='progress-bar progress-bar-danger' id='progress' name='progress' style='width:100%'></progress><br></div>");
			scroll("contenido"+idorden);

			var xhr = new XMLHttpRequest();
			xhr.open('POST','chat/chat.php?function=subir_archivo&id='+idorden+'&ruta='+ruta);
			xhr.onload = function() {
			};

			xhr.upload.onprogress = function (event) {
				var complete = Math.round(event.loaded / event.total * 100);
				if (event.lengthComputable) {
					progress.value = progress.innerHTML = complete;

				}
			};
			xhr.onreadystatechange = function(){
				if(xhr.readyState === 4 && xhr.status === 200){
					var data = JSON.parse(xhr.response);
					for (i = 0; i < data.length; i++) {
						var parametros={
							"id":idorden,
							"function":funcion,
							"archivo":data[i].archivo,
							"original":data[i].original
						};
						$.ajax({
							data: parametros,
							url: urlx,
							type: "post",
							beforeSend: function () {

							},
							success:  function (response) {
								Swal.fire({
								  type: 'success',
								  title: "Se cargó correctamente",
								  showConfirmButton: false,
								  timer: 1000
								})

								$("#contenido"+idorden).append(response);
								scroll("contenido"+idorden);
								$("#carga_f").remove();

							}
						});
					}
				}
			}
			xhr.send(formData);
		}
