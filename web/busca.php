<?php
	require_once("control_db.php");
	$db = new Tienda();
	$resp=$db->busca();
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

<link rel="stylesheet" type="text/css" href="plugins/OwlCarousel2-2.2.1/owl.carousel.css">
<link rel="stylesheet" type="text/css" href="plugins/OwlCarousel2-2.2.1/owl.theme.default.css">
<link rel="stylesheet" type="text/css" href="plugins/OwlCarousel2-2.2.1/animate.css">
<link rel="stylesheet" type="text/css" href="plugins/slick-1.8.0/slick.css">

<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

<div class="super_container">

	<!-- Header -->
	<header class="header">
		<?php
			include "a_header.php";
		?>
	</header>


		<!-- Hot New Arrivals -->
		<div class="new_arrivals">
			<div class="container">
				<div class="row">
					<div class="col">
						<div class="tabbed_container">
							<div class="tabs clearfix tabs-right">
								<div class="new_arrivals_title">Buscar productos</div>
								<ul class="clearfix">
									<li class="active">Productos</li>
								</ul>
								<div class="tabs_line"><span></span></div>
							</div>
							<div class="row">
								<div class="col-lg-12" style="z-index:1;">
									<!-- Product Panel -->
									<div class="product_panel panel active">
										<div class="arrivals_slider slider">

											<!-- Slider Item -->
											<?php
												foreach($resp as $key){
													echo "<div class='arrivals_slider_item'>
														<div class='border_active'></div>
														<div class='product_item is_new d-flex flex-column align-items-center justify-content-center text-center'>
															<div class='product_image d-flex flex-column align-items-center justify-content-center'><img src='".$db->doc.$key->img."' alt='' width='100px'></div>
															<div class='product_content'>
																<div class='product_price'>".moneda($key->preciof)."</div>
																<div class='product_name'><div><a href='product.php?id=".$key->id."'>".$key->nombre."</a></div></div>
																<div class='product_extras'>

																	<button class='product_cart_button' onclick='carrito(".$key->id.")'>Agregar al carrito</button>
																</div>
															</div>
															<div class='product_fav' onclick='wish(".$key->id.")'><i class='fas fa-heart'></i></div>
															<ul class='product_marks'>
																
															</ul>
														</div>
													</div>";
												}
											?>

										</div>
										<div class="arrivals_slider_dots_cover"></div>
									</div>
								</div>
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
<script src="plugins/OwlCarousel2-2.2.1/owl.carousel.js"></script>
<script src="plugins/slick-1.8.0/slick.js"></script>
<script src="plugins/easing/easing.js"></script>
<script src="js/custom.js"></script>

<script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
<script src="sagyc.js"></script>
</body>

</html>
