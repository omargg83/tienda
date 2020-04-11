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
	

	<div class="banner">
		<div class="container ">
			<div class="row ">
				<div class="col-lg-12 ">
					<div class="banner_content">
						<h1 class="banner_text" style="color: black; font-size: 30px;">Preguntas frecuentes</h1><br> 
					
<p>Dudas sobre la compra</p>
<ol>
<li>&iquest;Es seguro comprar en Tic-Shop?</li>
<li>&iquest;Tienen tiendas donde pueda ver o comprar la mercanc&iacute;a en persona?</li>
<li>&iquest;Puedo pagar a Meses Sin Intereses o con pagos diferidos?</li>
<li>&iquest;Los precios incluyen IVA, me pueden dar factura?</li>
<li>&iquest;Tienen garant&iacute;a?</li>
<li>&iquest;No vivo en M&eacute;xico pero me interesa comprar enTic-Shop?</li>
</ol>
<p>&nbsp;</p>
<p>Dudas sobre el env&iacute;o</p>
<ol>
<li>&iquest;Puedo recoger mi pedido en alg&uacute;n establecimiento?</li>
<li>&iquest;Puedo ahorrarme el env&iacute;o?</li>
<li>&iquest;Si pido m&aacute;s de una pieza tengo que pagar doble env&iacute;o?</li>
<li>Me urge mi pedido, &iquest;me lo pueden enviar express?</li>
</ol>

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
