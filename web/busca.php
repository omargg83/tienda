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

	<!-- Cart -->
	<?php

		foreach($resp as $key){


		}


	 ?>


	 					<div class="shop_content">
	 						<div class="shop_bar clearfix">
	 							<div class="shop_product_count"><span><?php echo $contar; ?></span> products found</div>
	 							<div class="shop_sorting">
	 								<span>Sort by:</span>
	 								<ul>
	 									<li>
	 										<span class="sorting_text">highest rated<i class="fas fa-chevron-down"></span></i>
	 										<ul>
	 											<li class="shop_sorting_button" data-isotope-option='{ "sortBy": "original-order" }'>highest rated</li>
	 											<li class="shop_sorting_button" data-isotope-option='{ "sortBy": "name" }'>name</li>
	 											<li class="shop_sorting_button"data-isotope-option='{ "sortBy": "price" }'>price</li>
	 										</ul>
	 									</li>
	 								</ul>
	 							</div>
	 						</div>

	 						<div class="product_grid">
	 							<div class="product_grid_border"></div>

	 							<!-- Product Item -->
	 							<?php
	 								foreach($resp as $key){
	 									echo "<a href='product.php?id=".$key->id."'><div class='product_item'>
	 										<div class='product_border'></div>
	 										<div class='product_image d-flex flex-column align-items-center justify-content-center'><img src='".$db->doc.$key->img."' alt='' width='100px'></div>
	 										<div class='product_content'>
	 											<div class='product_price'>".moneda($key->preciof)."</div>
	 											<div class='product_name'><div><a href='#' tabindex='0'>".$key->nombre."</a></div></div>
	 										</div>
	 										<div class='product_fav' onclick='wish(".$key->id.")'><i class='fas fa-heart'></i></div>
	 										<ul class='product_marks'>
	 											<li class='product_mark product_discount'>-25%</li>
	 											<li class='product_mark product_new'>new</li>
	 										</ul>
	 									</div></a>";
	 								}
	 							 ?>
	 						</div>

	 						<!-- Shop Page Navigation -->

	 						<div class="shop_page_nav d-flex flex-row">
	 							<div class="page_prev d-flex flex-column align-items-center justify-content-center"><i class="fas fa-chevron-left"></i></div>
	 							<ul class="page_nav d-flex flex-row">
	 								<li><a href="#">1</a></li>
	 								<li><a href="#">2</a></li>
	 								<li><a href="#">3</a></li>
	 								<li><a href="#">...</a></li>
	 								<li><a href="#">21</a></li>
	 							</ul>
	 							<div class="page_next d-flex flex-column align-items-center justify-content-center"><i class="fas fa-chevron-right"></i></div>
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

<script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
<script src="sagyc.js"></script>
</body>

</html>
