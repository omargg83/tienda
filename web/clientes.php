<?php
	require_once("control_db.php");
	$db = new Tienda();

	$ped=$db->pedidos_lista();

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
<link rel="stylesheet" type="text/css" href="styles/main_styles.css">
<link rel="stylesheet" type="text/css" href="styles/cart_styles.css">
<link rel="stylesheet" type="text/css" href="styles/cart_responsive.css">
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
					<a href='clientes.php' class="btn btn-primary btn-lg btn-block">Pedidos</a>
					<a href='cli_direcciones.php' class="btn btn-primary btn-lg btn-block">Direcciones</a>
 				 	<a href='cli_datos.php' class="btn btn-primary btn-lg btn-block">Mis datos</a>
 				 	<a href='#' class="btn btn-primary btn-lg btn-block" onclick='salir()'>Salir</a>
				</div>
			</div>
			<div class='col-9'>
				<?php
					echo "<h4>Pedidos</h4>";
					echo "<table class='table table-sm'>";
					echo "<tr><th>-</th><th>Pedido</th><th>Fecha</th><th>Estatus</th><th>Total</th><th>Envio</th><th>Total</th></tr>";
					foreach ($ped as $key) {
						echo "<tr>";

							echo "<td>";
								echo "<div class='btn-group'>";
									if($key->estatus=="EN ESPERA"){
										echo "<a class='btn btn-outline-secondary btn-sm' href='pago.php?idpedido=".$key->id."' title='Editar' ><i class='fas fa-pencil-alt'></i></a>";
									}
									if($key->estatus=="PROCESANDO" OR $key->estatus=="PROCESANDO PAGO" ){
										echo "<a class='btn btn-outline-secondary btn-sm' href='estado_pedido.php?idpedido=".$key->id."' title='Editar' ><i class='fas fa-pencil-alt'></i></a>";
									}

								echo "</div>";
							echo "</td>";

							echo "<td>#";
								echo $key->id;
							echo "</td>";

							echo "<td>";
								echo fecha($key->fecha);
							echo "</td>";
							echo "<td>";
								echo $key->estatus;
							echo "</td>";
							echo "<td class='text-right'>";
								echo moneda($key->monto);
							echo "</td>";
							echo "<td class='text-right'>";
								echo moneda($key->envio);
							echo "</td>";
							echo "<td class='text-right'>";
								echo moneda($key->total);
							echo "</td>";
						echo "</tr>";
					}
					echo "</table>";


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
