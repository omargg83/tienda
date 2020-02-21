<?php
	require_once("db_.php");
	echo "<nav class='navbar navbar-expand-lg navbar-light bg-light'>
		  <a class='navbar-brand' ><i class='fas fa-clipboard'></i>Productos</a>
		  <button class='navbar-toggler navbar-toggler-right' type='button' data-toggle='collapse' data-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>
			<span class='navbar-toggler-icon'></span>
		  </button>
		  <div class='collapse navbar-collapse' id='navbarSupportedContent'>
			<ul class='navbar-nav mr-auto'>";
				echo "<li class='nav-item active'><a class='nav-link barranav izq' title='Nuevo' id='new_comision' data-lugar='a_productos/editar'><i class='fas fa-plus'></i>Nuevo</span></a></li>";
				echo "<li class='nav-item active'><a class='nav-link barranav der' title='Mostrar todo' id='lista_comision' data-lugar='a_productos/lista'><i class='fas fa-list-ul'></i><span>Productos</span></a></li>";
				/*
				echo "<li class='nav-item active'><a class='nav-link barranav izq' title='Nuevo' id='new_incidencia' data-lugar='a_comision/editar_incidencia'><i class='fas fa-plus'></i>Nuevo</span></a></li>";
				echo "<li class='nav-item active'><a class='nav-link barranav der' title='Mostrar todo' id='lista_incidencia' data-lugar='a_comision/lista_incidencias'><i class='fas fa-list-ul'></i><span>Incidencias</span></a></li>";
				*/
			echo "</ul>";
		echo "</div>
	  </div>
	</nav>";
	echo "<div id='trabajo' style='margin-top:5px;'>";
		include 'lista.php';
	echo "</div>";
?>
<script type="text/javascript">
	function suma_com (id) {
		var pastotal="";
		var pas1=parseFloat($("#a5").val());
		var pas2=parseFloat($("#a6").val());

		var pass1=pas1;
		var pass2=pas2;
		var valpass=0;

		if (isNaN(pas1)){
			pass1=0;
			$("#a5").val("");
		}
		if (isNaN(pas2)){
			pass2=0;
			$("#a6").val("");
		}
		if (isNaN(pas1) || isNaN(pas2)){
			$("#a12").val("");
		}
		else{
			if($('#gastos').is(':checked')){
				$("#a12").val("");
			}
			else{
				valpass=pass1 * pass2;
				$("#a12").val(valpass);
			}
		}

		var valor1=parseFloat($("#a1").val());
		if (isNaN(valor1)){
			valor1=0;
			$("#a1").val(0);
		}
		var valor2=parseFloat($("#a2").val());
		if (isNaN(valor2)){
			valor2=0;
			$("#a2").val(0);
		}
		var valor3=parseFloat($("#a3").val());
		if (isNaN(valor3)){
			valor3=0;
			$("#a3").val(0);
		}
		var valor4=parseFloat($("#a4").val());
		if (isNaN(valor4)){
			valor4=0;
			$("#a4").val(0);
		}

		if($('#devengable').is(':checked')){
			$("#total4").val("NO DEVENGABLE");
			var valor5=0;
		}
		else{
			$("#total4").val( valor3 * valor4);
			var valor5=valor4;
		}

		$("#total").val(valor2 + valor4 + pass2);

		if($('#gastos').is(':checked')){
			$("#a12").val(0);
			$("#total2").val(0);
			$("#total3").val(0);
			$("#total4").val(0);
		}
		else{
			$("#total3").val(valor1 * valor2);
			$("#total2").val((valor1 * valor2) + ( valor3 * valor5) + valpass);
		}
	};
	function agregagc (){
		var valor1=parseFloat($("#a1").val());
		if (isNaN(valor1)){
			valor1=0;
			$("#a1").val(0);
		}
		else{
			if(valor1==0){
				valor1=valor1+300;
				$("#a1").val(valor1);
			}
		}

		var valor2=parseFloat($("#a2").val());
		if (isNaN(valor2)){
			valor2=0;
			$("#a2").val(0);
		}
		else{
			valor2=valor2+1;
			$("#a2").val(valor2);
		}
		suma_com(0);
	}
	function agregagv(){
		var valor3=parseFloat($("#a3").val());
		if (isNaN(valor3)){
			valor3=0;
			$("#a3").val(0);
		}
		else{
			if(valor3==0){
				valor3=valor3+350;
				$("#a3").val(valor3);
			}

		}

		var valor4=parseFloat($("#a4").val());
		if (isNaN(valor4)){
			valor4=0;
			$("#a4").val(0);
		}
		else{
			valor4=valor4+1;
			$("#a4").val(valor4);
		}
		suma_com(0);
	}
	$(document).on('change','#idpersona',function(e){
		e.preventDefault();
		var idpersona = $(this).val();
		$.ajax({
			data:  {
				"idpersona":idpersona,
				"function":"oficio_nuevo"
			},
			url:   "a_comision/db_.php",
			type:  'post',
			success:  function (response) {
				var datos = JSON.parse(response);
				$('#idrespon').val(datos.idrespon);
				$('#vb').val(datos.idvobo);
				$('#idcuenta').val(datos.idcuenta);
				$('#idrubrica1').val(datos.idrubrica1);
				$('#idrubrica2').val(datos.idrubrica2);
				$('#idrubrica3').val(datos.idrubrica3);
				$('#idrubrica4').val(datos.idrubrica4);
				$('#idrubrica5').val(datos.idrubrica5);
				$('#idrubrica6').val(datos.idrubrica6);
			}
		});

	});
	function agrega_lugar(id){

		$("#lugar_"+id).show();
		$("#boton_"+id).hide();
		id=id+1;
		$("#boton_"+id).show();
	}
</script>
