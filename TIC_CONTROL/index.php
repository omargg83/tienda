<?php
	session_start();
	require_once("control_db.php");
	$bdd = new Tienda();
?>
<!DOCTYPE HTML>
<html lang="es">
<head>
	<title>Tic Shop </title>
	<link rel="icon" type="image/png" href="img/LOGO.png">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<meta http-equiv="Expires" content="0">
	<meta http-equiv="Last-Modified" content="0">
	<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
	<meta http-equiv="Pragma" content="no-cache">

	<link rel="stylesheet" href="librerias15/load/css-loader.css">
</head>
<?php
	if(isset($_SESSION['idpersona']) and $_SESSION['autoriza'] == 1) {
		$valor=$_SESSION['idfondo'];
	}
	else{
		$arreglo=array();
		$directory="fondo/";
		$dirint = dir($directory);
		$contar=0;
		while (($archivo = $dirint->read()) !== false){
			if ($archivo != "." && $archivo != ".." && $archivo != "" && substr($archivo,-4)==".jpg"){
				$arreglo[$contar]=$directory.$archivo;
				$contar++;
			}
		}
		$valor=$arreglo[rand(1,$contar-1)];
		$_SESSION['idfondo']=$valor;
	}
	echo "<body style='background-image: url(\"$valor\")'>";
?>

<header class="d-block p-2" id='header'>

</header>

<div class="page-wrapper d-block p-2" id='bodyx'>
</div>

<div class="modal animated fadeInDown" tabindex="-1" role="dialog" id="myModal">
	<div class="modal-dialog" role="document" id='modal_dispo'>
		<div class="modal-content" id='modal_form'>

		</div>
	</div>
</div>

<div class="loader loader-default is-active" id='cargando' data-text="Cargando">
	<h2><span style='font-color:white'></span></h2>
</div>

</body>
	<!--   Core JS Files   -->
	<script src="librerias15/jquery-3.4.1.min.js" type="text/javascript"></script>

	<!--   url   -->
	<script src="librerias15/jquery/jquery-ui.js"></script>
	<link rel="stylesheet" type="text/css" href="librerias15/jquery/jquery-ui.min.css" />

	<!-- Animation library for notifications   -->
  <link href="librerias15/animate.css" rel="stylesheet"/>

	<!-- WYSWYG   -->
	<link href="librerias15/summernote8.12/summernote-lite.css" rel="stylesheet" type="text/css">
  <script src="librerias15/summernote8.12/summernote-lite.js"></script>
	<script src="librerias15/summernote8.12/lang/summernote-es-ES.js"></script>

	<!--   Alertas   -->
	<script src="librerias15/swal/dist/sweetalert2.min.js"></script>
	<link rel="stylesheet" href="librerias15/swal/dist/sweetalert2.min.css">

	<!--   para imprimir   -->
	<script src="librerias15/VentanaCentrada.js" type="text/javascript"></script>

	<!--   Cuadros de confirmación y dialogo   -->
	<link rel="stylesheet" href="librerias15/jqueryconfirm/css/jquery-confirm.css">
	<script src="librerias15/jqueryconfirm/js/jquery-confirm.js"></script>

	<!--   iconos   -->
	<link rel="stylesheet" href="librerias15/fontawesome-free-5.12.1-web/css/all.css">

	<!--   carrusel de imagenes   -->
	<link rel="stylesheet" href="librerias15/baguetteBox.js-dev/baguetteBox.css">
	<script src="librerias15/baguetteBox.js-dev/baguetteBox.js" async></script>
	<script src="librerias15/baguetteBox.js-dev/highlight.min.js" async></script>

	<script src="librerias15/popper.js"></script>
	<script src="librerias15/tooltip.js"></script>

	<!--   Propios   -->
	<script src="sagyc_admin.js"></script>
	<link rel="stylesheet" type="text/css" href="librerias15/modulos.css"/>

	<script src="librerias15/chartjs/Chart.js"></script>

	<!--   Tablas  -->
	<script type="text/javascript" src="librerias15/DataTables/datatables.js"></script>
	<script type="text/javascript" src="librerias15/DataTables/DataTables-1.10.18/js/dataTables.bootstrap4.min.js"></script>
	<script type="text/javascript" src="librerias15/DataTables/DataTables-1.10.18/js/dataTables.buttons.min.js"></script>
	<script type="text/javascript" src="librerias15/DataTables/DataTables-1.10.18/js/buttons.flash.min.js"></script>
	<script type="text/javascript" src="librerias15/DataTables/DataTables-1.10.18/js/jszip.min.js"></script>
	<script type="text/javascript" src="librerias15/DataTables/DataTables-1.10.18/js/pdfmake.min.js"></script>
	<script type="text/javascript" src="librerias15/DataTables/DataTables-1.10.18/js/vfs_fonts.js"></script>
	<script type="text/javascript" src="librerias15/DataTables/DataTables-1.10.18/js/buttons.html5.min.js"></script>
	<script type="text/javascript" src="librerias15/DataTables/DataTables-1.10.18/js/buttons.print.min.js"></script>

	<link rel="stylesheet" type="text/css" href="librerias15/DataTables/datatables.min.css"/>
	<link rel="stylesheet" type="text/css" href="librerias15/DataTables/DataTables-1.10.18/css/dataTables.bootstrap4.css"/>

	<!--   Boostrap   -->
	<link rel="stylesheet" href="librerias15/css/bootstrap.min.css">
	<script src="librerias15/js/bootstrap.js"></script>

	<script src="librerias15/jQuery-MD5-master/jquery.md5.js"></script>

</html>
