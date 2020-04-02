<?php
	require_once("control_db.php");
	$db = new Tienda();
	$carro=$db->wish_list();
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
		<div class="cart_section">
			<h3 class='text-center'>Lista de deseos</h3>
		</div>
			<?php
				$total=0;
				foreach($carro as $key){
					echo "<div class='row' style='border-bottom:1px solid silver'>";
						echo "<div class='col-3'><img src='".$db->doc.$key->img."' alt='' width='100px'></div>";
							echo "
								<div class='col-3'>
									<div class='cart_item_title'>Nombre</div>
									<div class='cart_item_text'>".$key->nombre."</div>
								</div>
								<div class='col-2'>
									<div class='cart_item_title'>Cantidad</div>
									<div class='cart_item_text'>1</div>
								</div>
								<div class='col-2'>
									<div class='cart_item_title'>Precio</div>
									<div class='cart_item_text'>".moneda($key->preciof)."</div>
								</div>
								<div class='col-2'>
									<div class='cart_item_title'>Total</div>
									<div class='cart_item_text'>".moneda($key->preciof)."</div>
								</div>
							";
						echo "</div>";
					$total+=$key->preciof;
				}
			?>


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

<script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
<script src="sagyc.js"></script>
</body>

</html>
