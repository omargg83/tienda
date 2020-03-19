<?php
	require_once("control_db.php");
	$id=$_REQUEST['id'];
	$ruta=$_REQUEST['ruta'];
	$tipo=$_REQUEST['tipo'];
	$ext=$_REQUEST['ext'];
	$tabla=$_REQUEST['tabla'];
	$campo=$_REQUEST['campo'];
	$keyt=$_REQUEST['keyt'];
	$destino=$_REQUEST['destino'];
	$iddest=$_REQUEST['iddest'];
	$proceso=$_REQUEST['proceso'];

	echo "<form autocomplete=off id='upload_File' action='' >";
		echo "<input  type='hidden' id='id' NAME='id' value='$id'>";
		echo "<input  type='hidden' id='ruta' NAME='ruta' value='$ruta'>";
		echo "<input  type='hidden' id='tipo' NAME='tipo' value='$tipo'>";
		echo "<input  type='hidden' id='tabla' NAME='tabla' value='$tabla'>";
		echo "<input  type='hidden' id='campo' NAME='campo' value='$campo'>";
		echo "<input  type='hidden' id='keyt' NAME='keyt' value='$keyt'>";
		echo "<input  type='hidden' id='destino' NAME='destino' value='$destino'>";
		echo "<input  type='hidden' id='iddest' NAME='iddest' value='$iddest'>";
		echo "<input  type='hidden' id='proceso' NAME='proceso' value='$proceso'>";

		echo "<div class='card'>";
		echo "<div class='card-header'>Subir archivo</div>";
		echo "<div class='card-body' >";

			echo "<div class='custom-file'>";
				//echo "<input type='file' class='custom-file-input' id='prefile' required accept='$ext'>";
				echo "<input type='file' class='custom-file-input' id='prefile' required>";
				echo "<label class='custom-file-label' for='prefile'>Seleccionar archivo...</label>";
				echo "<div class='invalid-feedback'>Example invalid custom file feedback</div>";
			echo "</div>";

			echo "<div id='contenedor_file'>";
			echo "</div>";
/*
			echo "<span style='font-size:10px;'>";
			echo "-Favor de verificar que el archivo se suba correctamente intentando descargarlo del destino final<br>";
			echo "-El archivo podría no subirse en caso de contar con caracteres especiales en el nombre (°!#$%&/())<br>";
			echo "-Tamaño máximo 20mb<br>";
			echo "-Si el archivo causa error al subirse, favor de enviarlo al correo <b>omargg83@gmail.com</b> con el nombre del comite y numero de fecha para subirlo manualmente y verificarlo";
			echo "</span>";
*/
			echo "<progress max='100' value='0' id='progress_file' class='progress-bar bg-danger' style='display:none;width:100%;'></progress>";

		echo "</div>";
		echo "<div class='card-footer' >";
			echo "<div class='btn-group'>";
				echo "<button class='btn btn-outline-secondary btn-sm' type='submit' id='btnfile'><i class='far fa-save'></i>Guardar</button>";
				echo "<button type='button' class='btn btn-outline-secondary btn-sm' data-dismiss='modal'><i class='fas fa-sign-out-alt'></i>Cerrar</button>";
			echo "</div>";
			echo "</div>";
		echo "</div>";
	echo "</form>";
?>
