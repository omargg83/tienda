<?php
	require_once("control_db.php");
	$db = new Tienda();
	$id=$_REQUEST['id'];
	$prod = $db->producto_ver($id);
	$imextra=$db->producto_imagen($id);
	$espe = $db->producto_espe($id);
	$alma = $db->producto_exist($id,1);


	$rel=$db->relacionados($prod->subcategoria);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title><?php   echo $prod->nombre;  ?></title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="OneTech shop project">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="styles/bootstrap4/bootstrap.min.css">
<link href="plugins/fontawesome-free-5.0.1/css/fontawesome-all.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="plugins/OwlCarousel2-2.2.1/owl.carousel.css">
<link rel="stylesheet" type="text/css" href="plugins/OwlCarousel2-2.2.1/owl.theme.default.css">
<link rel="stylesheet" type="text/css" href="plugins/OwlCarousel2-2.2.1/animate.css">
<link rel="stylesheet" type="text/css" href="styles/product_styles.css">
<link rel="stylesheet" type="text/css" href="styles/product_responsive.css">
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
	<!-- Single Product -->

	<div class="single_product">
		<div class="container">
			<div class="row">

				<!-- Images -->
				<div class="col-lg-2 order-lg-1 order-2">
					<ul class="image_list">
						<?php
							echo "<li data-image='".$db->doc.$prod->img."'><img src='".$db->doc.$prod->img."' alt=''></li>";
							foreach($imextra as $key){
								echo "<li data-image='".$db->extra.$key->direccion."'><img src='".$db->extra.$key->direccion."' alt=''></li>";
							}
						?>
					</ul>
				</div>

				<!-- Selected Image -->
				<div class="col-lg-5 order-lg-2 order-1">
					<?php
						echo "<div class='image_selected'><img src='".$db->doc.$prod->img."' alt=''></div>";
					?>
				</div>

				<!-- Description -->
				<div class="col-lg-5 order-3">
					<div class="product_description">
						<div class="product_category"><?php   echo $prod->clave;  ?></div>
						<div class="product_name"><?php   echo $prod->nombre;  ?></div>
						<div class="rating_r rating_r_4 product_rating"><i></i><i></i><i></i><i></i><i></i></div>
						<div class="product_text"><p><?php   echo $prod->descripcion_corta;  ?></p></div>
						<div class="order_info d-flex flex-row">
							<form action="#">
								<div class="clearfix" style="z-index: 1000;">

									<!-- Product Quantity -->
									<div class="product_quantity clearfix">
										<span>Cantidad: </span>
										<input id="quantity_input" type="text" pattern="[0-9]*" value="1">
										<div class="quantity_buttons">
											<div id="quantity_inc_button" class="quantity_inc quantity_control"><i class="fas fa-chevron-up"></i></div>
											<div id="quantity_dec_button" class="quantity_dec quantity_control"><i class="fas fa-chevron-down"></i></div>
										</div>

									</div>

								</div>

								<div class="product_price">Precio: <?php
									if($prod->precio_tipo==0){
										echo moneda($prod->preciof);
									}
									if($prod->precio_tipo==1){
										$total=$prod->preciof+(($prod->preciof*$db->cgeneral)/100);
										echo moneda($total);
									}
									if($prod->precio_tipo==2){
										echo moneda($prod->precio_tic);
									}
									if($prod->precio_tipo==3){
										$total=$prod->precio_tic+(($prod->precio_tic*$db->cgeneral)/100);
										echo moneda($total);
									}
							  ?></div>
								<?php
								echo "<br>+ Envio:";
								if($prod->envio_tipo==0){
									echo moneda($db->egeneral);
									$envio+=$db->egeneral;
								}
								if($prod->envio_tipo==1){
									echo moneda($prod->envio_costo);
									$envio+=$prod->envio_costo;
								}
								?>
								<hr>
								<?php
									echo "<h5>Existencia</h5>";
									echo "<table class='table table-sm'>";
									foreach($alma as $key){

										echo "<tr>";
											echo "<td>";
											echo $key->alma;
											echo "</td>";

											echo "<td>";
											echo $key->total;
											echo "</td>";
										echo "</tr>";
									}
									echo "</table>";

								?>


								<div class="button_container">
									<?php
										echo "<button type='button' class='button cart_button'  onclick='carrito(".$prod->id.")'>Agregar al carrito</button>";
									?>
									<div class="product_fav"><i class="fas fa-heart"></i></div>
								</div>

							</form>
						</div>
					</div>
			</div>
		</div>

			<hr>

			<div class="row">
				<div class='col-6'>
					<h3>Especificaciones</h3>
					<?php

						foreach($espe as $key){
							echo "<div class='row'>";

								echo "<div class='col-6'>";
								echo $key->tipo;
								echo "</div>";

								echo "<div class='col-6'>";
								echo $key->valor;
								echo "</div>";

							echo "</div>";
						}

					?>
				</div>
				<div class='col-6'>
					<h3>Más información</h3>
					<?php
						echo $prod->descripcion_larga;
					 ?>

				</div>
			</div>
		</div>
	</div>

	<!-- Recently Viewed -->
	<div class="viewed">
		<?php
			include "a_relacionados.php";
		?>
	</div>

	<!-- Brands -->
	<div class="brands">
		<?php
			include "a_brand.php";
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
<script src="plugins/OwlCarousel2-2.2.1/owl.carousel.js"></script>
<script src="plugins/easing/easing.js"></script>
<script src="js/product_custom.js"></script>

<script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
<script src="sagyc.js"></script>
</body>

</html>
