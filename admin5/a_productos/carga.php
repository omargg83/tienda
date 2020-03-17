<?php
	require_once("db_.php");
	$id="";
	$cbusca="";
	echo "<div class='container' >";
	echo "<div class='card'>";
		echo "<div class='card-header'>";
		echo "Subir archivo JSON";
		echo "</div>";
		echo "<div class='card-body'>";
			echo "<center><table class='info'>";
			echo "<thead></thead><tbody><tr><td>";
			echo "<b>Notas</b>:";
			echo "<br>* El archivo deberá de tener formato .xlsx";
			echo "<br>* El archivo solo deberá de contar con un libro llamado 'vp'";
			echo "<br>* los encabezados deberán de ser:
			<br> (A1) -QUINCENA Y AÑO EN FORMATO AAAA/QQ EJEMPLO (2016/13)
			<br> (A2) -CURP
			<br> (A3) -RFC
			<br> (A4) -NOMBRE
			<br> (A5) -PRIMER_APELLIDO
			<br> (A6) -SEGUNDO_APELLIDO
			<br> (A7) -CVE_CONCEPTO
			<br> (A8) -DESCRIPCION
			<br> (A9) -IMPORTE";
			echo "</td></tr>";
			echo "</table>";
		echo "</div>";
		echo "<div class='card-footer'>";
			echo "<button type='button' class='btn btn-outline-secondary btn-sm' data-toggle='modal' data-target='#myModal' id='carga' data-ruta='../a_archivos' data-tabla='personal' data-campo='file_foto' data-tipo='1' data-id='$id' data-keyt='idpersona' data-destino='a_personal/editar' data-iddest='$id' data-ext='.jpg,.png' title='Subir foto' onclick='subir()'><i class='fas fa-cloud-upload-alt'></i>Subir archivo</button>";
		echo "</div>";
	echo "</div>";


?>
