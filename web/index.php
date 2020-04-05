<?php
	require_once("control_db.php");
	$db = new Tienda();

	$oferta=$db->ofertas();
	$destacados=$db->destacados();
	$venta=$db->venta();
	$rated=$db->rated();
	$nuevos=$db->nuevos();
	$nuevos2=$db->nuevos_2();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>TIC SHOP</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="OneTech shop project">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="styles/bootstrap4/bootstrap.min.css">
<link href="plugins/fontawesome-free-5.0.1/css/fontawesome-all.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="plugins/OwlCarousel2-2.2.1/owl.carousel.css">
<link rel="stylesheet" type="text/css" href="plugins/OwlCarousel2-2.2.1/owl.theme.default.css">
<link rel="stylesheet" type="text/css" href="plugins/OwlCarousel2-2.2.1/animate.css">
<link rel="stylesheet" type="text/css" href="plugins/slick-1.8.0/slick.css">
<link rel="stylesheet" type="text/css" href="styles/main_styles.css">
<link rel="stylesheet" type="text/css" href="styles/responsive.css">
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
	<!-- Banner -->

	<div class="banner">
		<div class="banner_background" style="background-image:url(img/banner_home.jpg)"></div>
		<div class="container fill_height">
			<div class="row fill_height">
				<div class="banner_product_image"><img src="images/banner_product.png" alt=""></div>
				<div class="col-lg-5 offset-lg-4 fill_height">
					<div class="banner_content">
						<h1 class="banner_text">new era of smartphones</h1>
						<div class="banner_price"><span>$530</span>$460</div>
						<div class="banner_product_name">Apple Iphone 6s</div>
						<div class="button banner_button"><a href="#">Shop Now</a></div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Characteristics -->
	<div class="characteristics">
		<div class="container">
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

	<!-- Deals of the week -->

	<div class="deals_featured">
		<div class="container">
			<div class="row">
				<div class="col d-flex flex-lg-row flex-column align-items-center justify-content-start">

					<!-- Deals -->

					<div class="deals">
						<div class="deals_title">Ofertas de la semana</div>
						<div class="deals_slider_container">

							<!-- Deals Slider -->
							<div class="owl-carousel owl-theme deals_slider">

								<!-- Deals Item -->
								<?php
									foreach($oferta as $key){
										echo "<div class='owl-item deals_item'>
											<div class='deals_image'><img src='".$db->doc.$key->img."' alt=''></div>
											<div class='deals_content'>
												<div class='deals_info_line d-flex flex-row justify-content-start'>
													<div class='deals_item_category'><a href='#'>".$key->categoria."</a></div>
													<div class='deals_item_price_a ml-auto'>".moneda($key->preciof)."</div>
												</div>
												<div class='deals_info_line d-flex flex-row justify-content-start'>
													<div class='deals_item_name'>".$key->categoria."</div>
													<div class='deals_item_price ml-auto'>".moneda($key->preciof)."</div>
												</div>
												<div class='available'>
													<div class='available_line d-flex flex-row justify-content-start'>
														<button class='btn btn-outline-primary btn-block' onclick='carrito(".$key->id.")'>Agregar al carrito</button>
													</div>
												</div>

											</div>
										</div>";
									}
								?>

							</div>

						</div>

						<div class="deals_slider_nav_container">
							<div class="deals_slider_prev deals_slider_nav"><i class="fas fa-chevron-left ml-auto"></i></div>
							<div class="deals_slider_next deals_slider_nav"><i class="fas fa-chevron-right ml-auto"></i></div>
						</div>
					</div>

					<!-- Featured -->
					<div class="featured">
						<div class="tabbed_container">
							<div class="tabs">
								<ul class="clearfix">
									<li class="active">Destacados</li>
								</ul>
								<div class="tabs_line"><span></span></div>
							</div>

							<!-- Product Panel -->
							<div class="product_panel panel active">
								<div class="featured_slider slider">

									<?php
										foreach ($destacados as $key) {
											echo "<div class='featured_slider_item'>
												<div class='border_active'></div>
												<div class='product_item discount d-flex flex-column align-items-center justify-content-center text-center'>
													<div class='product_image d-flex flex-column align-items-center justify-content-center'><img src='".$db->doc.$key->img."' alt=''></div>
													<div class='product_content'>
														<div class='product_price discount'>".moneda($key->preciof)."<span>".moneda($key->preciof)."</span></div>
														<div class='product_name'><div><a href='product.php?id=".$key->id."'>".$key->nombre."</a></div></div>
														<div class='product_extras'>
															<button class='product_cart_button' onclick='carrito(".$key->id.")'>Agregar al carrito</button>
														</div>
													</div>
													<div class='product_fav' onclick='wish(".$key->id.")'><i class='fas fa-heart'></i></div>
													<ul class='product_marks'>
														<li class='product_mark product_discount'>-25%</li>
														<li class='product_mark product_new'>new</li>
													</ul>
												</div>
											</div>";
										}
									?>

								</div>
								<div class="featured_slider_dots_cover"></div>
							</div>

						</div>
					</div>

				</div>
			</div>
		</div>
	</div>

	<!-- Popular Categories -->

	<div class="popular_categories">
		<div class="container">
			<div class="row">
				<div class="col-lg-3">
					<div class="popular_categories_content">
						<div class="popular_categories_title">Categorias destacadas</div>
						<div class="popular_categories_slider_nav">
							<div class="popular_categories_prev popular_categories_nav"><i class="fas fa-angle-left ml-auto"></i></div>
							<div class="popular_categories_next popular_categories_nav"><i class="fas fa-angle-right ml-auto"></i></div>
						</div>
						<div class="popular_categories_link"><a href="#">Ver mas</a></div>
					</div>
				</div>

				<!-- Popular Categories Slider -->

				<div class="col-lg-9">
					<div class="popular_categories_slider_container">
						<div class="owl-carousel owl-theme popular_categories_slider">

							<!-- Popular Categories Item -->
							<div class="owl-item">
								<div class="popular_category d-flex flex-column align-items-center justify-content-center">
									<div class="popular_category_image"><img src="img/categoria-almacenamiento.png" alt=""></div>
									<div class="popular_category_text">Almacenamiento</div>
								</div>
							</div>

							<!-- Popular Categories Item -->
							<div class="owl-item">
								<div class="popular_category d-flex flex-column align-items-center justify-content-center">
									<div class="popular_category_image"><img src="img/categoria-audio.png" alt=""></div>
									<div class="popular_category_text">Audio</div>
								</div>
							</div>

							<!-- Popular Categories Item -->
							<div class="owl-item">
								<div class="popular_category d-flex flex-column align-items-center justify-content-center">
									<div class="popular_category_image"><img src="img/categoria-computo.png" alt=""></div>
									<div class="popular_category_text">Computo</div>
								</div>
							</div>

							<!-- Popular Categories Item -->
							<div class="owl-item">
								<div class="popular_category d-flex flex-column align-items-center justify-content-center">
									<div class="popular_category_image"><img src="img/categoria-comunicaciones.png" alt=""></div>
									<div class="popular_category_text">Comunicaciones</div>
								</div>
							</div>

							<!-- Popular Categories Item -->
							<div class="owl-item">
								<div class="popular_category d-flex flex-column align-items-center justify-content-center">
									<div class="popular_category_image"><img src="img/categoria-gamer.png" alt=""></div>
									<div class="popular_category_text">Gamer</div>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Banner -->

	<div class="banner_2">
		<div class="banner_2_background" style="background-image:url(img/fondogammer.jpg)"></div>
		<div class="banner_2_container">
			<div class="banner_2_dots"></div>
			<!-- Banner 2 Slider -->

			<div class="owl-carousel owl-theme banner_2_slider">

				<!-- Banner 2 Slider Item -->
				<div class="owl-item">
					<div class="banner_2_item">
						<div class="container fill_height">
							<div class="row fill_height">
								<div class="col-lg-4 col-md-6 fill_height">
									<div class="banner_2_content">
										<div class="banner_2_title">Grandes ofertas para que disfrutes al máxico el tiempo en casa</div>
										<div class="banner_2_text">10% DE DESCUENTO EN ACCESORIOS</div>
										<div class="rating_r rating_r_4 banner_2_rating"><i></i><i></i><i></i><i></i><i></i></div>
										<div class="button banner_2_button"><a href="#">Ver productos</a></div>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>


			</div>
		</div>
	</div>

	<!-- Hot New Arrivals -->
	<div class="new_arrivals">
		<div class="container">
			<div class="row">
				<div class="col">
					<div class="tabbed_container">
						<div class="tabs clearfix tabs-right">
							<div class="new_arrivals_title">PRODUCTOS DE LA SEMANA</div>
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
											foreach($nuevos as $key){
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
															<li class='product_mark product_discount'>-25%</li>
															<li class='product_mark product_new'>new</li>
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

	<!-- Best Sellers -->

	<div class="best_sellers text-center">
		<h2>Extenso surtido en productos de tecnología</h2>
	</div>

	<!-- Adverts -->

	<div class="adverts">
		<div class="container">
			<div class="row">

				<div class="col-lg-4 advert_col">

					<!-- Advert Item -->

					<div class="advert d-flex flex-row align-items-center justify-content-start">
						<div class="advert_content">
							<div class="advert_title"><a href="#">Trends 2018</a></div>
							<div class="advert_text">Lorem ipsum dolor sit amet, consectetur adipiscing Donec et.</div>
						</div>
						<div class="ml-auto"><div class="advert_image"><img src="images/adv_1.png" alt=""></div></div>
					</div>
				</div>

				<div class="col-lg-4 advert_col">

					<!-- Advert Item -->

					<div class="advert d-flex flex-row align-items-center justify-content-start">
						<div class="advert_content">
							<div class="advert_subtitle">Trends 2018</div>
							<div class="advert_title_2"><a href="#">Sale -45%</a></div>
							<div class="advert_text">Lorem ipsum dolor sit amet, consectetur.</div>
						</div>
						<div class="ml-auto"><div class="advert_image"><img src="images/adv_2.png" alt=""></div></div>
					</div>
				</div>

				<div class="col-lg-4 advert_col">

					<!-- Advert Item -->

					<div class="advert d-flex flex-row align-items-center justify-content-start">
						<div class="advert_content">
							<div class="advert_title"><a href="#">Trends 2018</a></div>
							<div class="advert_text">Lorem ipsum dolor sit amet, consectetur.</div>
						</div>
						<div class="ml-auto"><div class="advert_image"><img src="images/adv_3.png" alt=""></div></div>
					</div>
				</div>

			</div>
		</div>
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
<script src="plugins/slick-1.8.0/slick.js"></script>
<script src="plugins/easing/easing.js"></script>
<script src="js/custom.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
<script src="sagyc.js"></script>
</body>

</html>
