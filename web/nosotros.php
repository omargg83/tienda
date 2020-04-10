<?php
	require_once("control_db.php");
	$db = new Tienda();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Tic-Shop</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="OneTech shop project">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="styles/bootstrap4/bootstrap.min.css">
<link href="plugins/fontawesome-free-5.0.1/css/fontawesome-all.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="styles/contact_styles.css">
<link rel="stylesheet" type="text/css" href="styles/contact_responsive.css">
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
	<?php
		$key=$db->baner2(2);

		echo "<div class='banner'>";
			echo "<div class='banner_background' style='background-image:url(".$db->banner.$key->img.")'></div>";
			echo "<div class='container fill_height' style='padding: 10% 0 10% 0;'>";
				echo "<div class='row fill_height'>";
					echo "<div class='col-lg-12 fill_height text-center'>";
						echo "<div class='banner_content'>";
							echo "<h1 class='banner_text'>".$key->texto."</h1>";
							if(strlen($key->enlace)>0){
								echo "<div class='button banner_button'><a href='".$key->enlace."'>IR</a></div>";
							}
						echo "</div>";
					echo "</div>";
				echo "</div>";
			echo "</div>";
		echo "</div>";
	?>

	<div class="banner">
		<div class="container fill_height">
			<div class="row fill_height">
				<div class="col-lg-12 fill_height text-center">
					<div class="banner_content">
						<h1 class="banner_text" style="color: black;">¿Quiénes somos?</h1><br>
						<p class='text-center' style='color:black;font-size:16px;'>Tic-Shop, es una empresa de Soluciones Tecnológicas e Informática, creada para hacer tus compras electrónicas más rápidas, con
						los mejores tiempos de entrega. Contando con una gama extensa de productos de marcas líderes en el mercado.</p>
					</div>
				</div>
			</div>
		</div>
	</div>


	<!-- Characteristics -->
	<div class="characteristics">
		<div class="container">
			<h1 class="banner_text text-center" style="color: black;" >¿Qué nos hace diferentes?</h1><br>
			<div class="row">

				<!-- Char. Item -->
				<div class="col-lg-3 col-md-6 char_col">
					<div class="char_item d-flex flex-row align-items-center justify-content-start">
						<div class="char_icon"><img src="img/comprasegura.png" alt="" width="50px"></div>
						<div class="char_content">
							<div class="char_title">Compras seguras</div>
						</div>
					</div>
				</div>

				<!-- Char. Item -->
				<div class="col-lg-3 col-md-6 char_col">

					<div class="char_item d-flex flex-row align-items-center justify-content-start">
						<div class="char_icon"><img src="img/enviosseguros.png" alt="" width="50px"></div>
						<div class="char_content">
							<div class="char_title">Envíos seguros</div>
						</div>
					</div>
				</div>

				<!-- Char. Item -->
				<div class="col-lg-3 col-md-6 char_col">

					<div class="char_item d-flex flex-row align-items-center justify-content-start">
						<div class="char_icon"><img src="img/devolucion.png" alt="" width="50px"></div>
						<div class="char_content">
							<div class="char_title">Devoluciones fáciles</div>

						</div>
					</div>
				</div>

				<!-- Char. Item -->
				<div class="col-lg-3 col-md-6 char_col">

					<div class="char_item d-flex flex-row align-items-center justify-content-start">
						<div class="char_icon"><img src="img/tarjetas.png" alt="" width="50px"></div>
						<div class="char_content">
							<div class="char_title">Aceptamos diferentes métodos de pago</div>
						</div>
					</div>
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
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyCIwF204lFZg1y4kPSIhKaHEXMLYxxuMhA"></script>
<script src="js/contact_custom.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
<script src="sagyc.js"></script>

</body>

</html>
