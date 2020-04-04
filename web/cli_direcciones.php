<?php
	require_once("control_db.php");
	$db = new Tienda();
  $row=$db->direcciones();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Cart</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="OneTech shop project">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="styles/bootstrap4/bootstrap.min.css">
<link href="plugins/fontawesome-free-5.0.1/css/fontawesome-all.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="styles/cart_styles.css">
<link rel="stylesheet" type="text/css" href="styles/cart_responsive.css">
<link rel="stylesheet" type="text/css" href="styles/main_styles.css">
</head>

<body>

<div class="super_container">

	<!-- Header -->
	<header class="header">
		<?php
			include "a_header.php";
		?>
	</header>

	<!-- Cart -->
	<div class='container'>
		<div class='row'>
			<div class='col-3'>
				<div class="btn-group-vertical">
					<a href='' class="btn btn-primary btn-lg btn-block">Pedidos</a>
					<a href='cli_direcciones.php' class="btn btn-primary btn-lg btn-block">Direcciones</a>
 				 	<a href='cli_datos.php' class="btn btn-primary btn-lg btn-block">Mis datos</a>
 				 	<a href='#' class="btn btn-primary btn-lg btn-block" onclick='salir()'>Salir</a>
				</div>
			</div>
			<div class='col-9'>
				<?php

					$row=$db->direcciones();
					echo "<div class='card-header'>";
						echo "Direcciones adicionales";

					echo "</div>";
					echo "<div class='card-body'>";
						echo "<button type='button' class='btn btn-outline-secondary btn-sm' id='winmodal_pass' data-id='0' data-lugar='a_clientes/form_direccion' title='Cambiar contraseña' ><i class='fas fa-street-view'></i>Agregar Direccion</button>";

						echo "<table class='table table-sm'>";
						echo "<tr><th>-</th><th>Nombre</th><th>Apellidos</th><th>Empresa</th><th>Ciudad</th><th>Estado</th></tr>";
						foreach($row as $key){
							echo "<tr>";
								echo "<td>";
									echo "<div class='btn-group'>";
										echo "<a class='btn btn-outline-secondary btn-sm' href='cli_diredit.php?dir=".$key['iddireccion']."' title='Editar' ><i class='fas fa-pencil-alt'></i></a>";
									echo "</div>";
								echo "</td>";
								echo "<td>";
									echo $key['nombre'];
								echo "</td>";
								echo "<td>";
									echo $key['apellidos'];
								echo "</td>";
								echo "<td>";
									echo $key['empresa'];
								echo "</td>";
								echo "<td>";
									echo $key['ciudad'];
								echo "</td>";
								echo "<td>";
									echo $key['estado'];
								echo "</td>";
							echo "</tr>";
						}
						echo "</table>";
					echo "</div>";

				?>
			</div>
		</div>
	</div>


	<!-- Footer -->
	<footer class="footer">
		<?php
			include "a_footer.php";
		?>
	</footer>

	<!-- Copyright -->
	<div class="copyright">
		<?php
			include "a_copyright.php";
		?>
	</div>

</div>

<script src="js/jquery-3.3.1.min.js"></script>
<script src="styles/bootstrap4/popper.js"></script>
<script src="styles/bootstrap4/bootstrap.min.js"></script>
<script src="plugins/greensock/TweenMax.min.js"></script>
<script src="plugins/greensock/TimelineMax.min.js"></script>
<script src="plugins/scrollmagic/ScrollMagic.min.js"></script>
<script src="plugins/greensock/animation.gsap.min.js"></script>
<script src="plugins/greensock/ScrollToPlugin.min.js"></script>
<script src="plugins/easing/easing.js"></script>
<script src="js/cart_custom.js"></script>

<!--   Alertas   -->
<script src="librerias15/swal/dist/sweetalert2.min.js"></script>
<link rel="stylesheet" href="librerias15/swal/dist/sweetalert2.min.css">


<script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
<script src="sagyc.js"></script>
</body>

</html>
