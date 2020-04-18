<?php
	require_once("control_db.php");
	$db = new Tienda();
	$carro=$db->carro_list();
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
		<h3 class='text-center'>Carrito de compras</h3>

		<div class='row'>
			<div class='col-8 cartlist'>
				<div class='row'>
					<div class='col-3 text-center'>
						<b>Opciones</b>
					</div>
					<div class='col-9'>
						<b>Descripción</b>
					</div>
				</div>
				<?php
				$total=0;
				$envio=0;
				foreach($carro as $key){
					$preciof=0;
					$enviof=0;
					if($key->precio_tipo==0){
						$preciof=$key->preciof;
					}
					if($key->precio_tipo==1){
						$p_total=$key->preciof+(($key->preciof*$db->cgeneral)/100);
						$preciof=$p_total;
					}
					if($key->precio_tipo==2){
						$preciof=$key->precio_tic;
					}
					if($key->precio_tipo==3){
						$p_total=$key->precio_tic+(($key->precio_tic*$db->cgeneral)/100);
						$preciof=$p_total;
					}
					$preciof=round($preciof,2);
					echo "<div class='row' style='border-bottom:.5px solid silver'>";
						echo "<div class='col-3 text-center'>";
							echo "<img src='".$db->doc.$key->img."' alt='' width='130px'>";
						echo "</div>";
						echo "<div class='col-9'>";
							echo "<div class='row'>";
								echo "<div class='col-12'>";
										echo $key->clave;
										echo "<br>";
										echo $key->nombre;
								echo "</div>";
							echo "</div>";

							echo "<div class='row'>";
								echo "<div class='col-6'>";
									echo "<label>Costo envio: ";
									if($key->envio_tipo==0){
										echo moneda($db->egeneral);
										$envio+=$db->egeneral;
									}
									if($key->envio_tipo==1){
										echo moneda($key->envio_costo);
										$envio+=$key->envio_costo;
									}
									echo "</label>";
								echo "</div>";
							echo "</div>";

							echo "<div class='row'>";
								echo "<div class='col-10 offset-2'>";
									echo "<div class='row'>";
										echo "<div class='col-4 text-center'>";
											echo "<b>Cantidad</b>";
										echo "</div>";
										echo "<div class='col-4 text-center'>";
											echo "<b>Precio unitario</b>";
										echo "</div>";
										echo "<div class='col-4 text-center'>";
											echo "<b>Total</b>";
										echo "</div>";
									echo "</div>";

									echo "<div class='row'>";
										echo "<div class='col-4 text-center'>";
											echo $key->cantidad;
										echo "</div>";
										echo "<div class='col-4 text-right'>";
											echo moneda($preciof);
										echo "</div>";
										echo "<div class='col-4 text-right'>";
											$p_final=($key->cantidad*$preciof);
											echo moneda($p_final);
										echo "</div>";
									echo "</div>";

								echo "</div>";

							echo "</div>";
							echo "<button class='btn btn-warning btn-sm' onclick='borra_carrito(".$key->id.")'><i class='far fa-trash-alt'></i></button>";
						echo "</div>";
					echo "</div>";
					$total+=$p_final;
				}

				?>
			</div>
			<div class='col-4 cartgris'>
				<div class="jumbotron">
					<?php
						echo "<h4>TOTAL DEL CARRITO</h4>";
						echo "<div class='row'>";
							echo "<div class='col-6'>";
								echo "Subtotal";
							echo "</div>";
							echo "<div class='col-6 text-right'>";
								echo moneda($total);
							echo "</div>";
						echo "</div>";

						echo "<div class='row'>";
							echo "<div class='col-6'>";
								echo "Envío";
							echo "</div>";
							echo "<div class='col-6 text-right'>";
								echo moneda($envio);
							echo "</div>";
						echo "</div>";

						$gtotal=$total+$envio;
						echo "<hr>";
						echo "<div class='row'>";
							echo "<div class='col-6'>";
								echo "Total";
							echo "</div>";
							echo "<div class='col-6 text-right'>";
								echo moneda($gtotal);
							echo "</div>";
						echo "</div>";

						echo "<a class='btn btn-warning btn-block' href='finalizar.php'><i class='fas fa-cart-plus'></i>Finalizar</a>";
					?>
				</div>
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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
<script src="sagyc.js"></script>
</body>

</html>
