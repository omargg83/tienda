<?php
	require_once("control_db.php");
	$db = new Tienda();

	////////////productos ofertas
	$oferta=$db->ofertas();
	//////////productos destacados
	$destacados=$db->productos_destacados();
	//////////////semana
	$semana=$db->productos_semana();

?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>TIC SHOP</title>
<link rel="icon" type="image/png" href="/img/LOGO.png">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="TicShop la tienda de tecnología mas grande de México">
<meta name="keywords" content="Electrónica,Tecnología,Hardware,Software,Tarjetas Video,Tarjetas Madre,Procesadores,Tienda de tecnología,Tienda de tecnología online,Electronicos en linea" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="/styles/bootstrap4/bootstrap.min.css">
<link href="/plugins/fontawesome-free-5.0.1/css/fontawesome-all.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="/plugins/OwlCarousel2-2.2.1/owl.carousel.css">
<link rel="stylesheet" type="text/css" href="/plugins/OwlCarousel2-2.2.1/owl.theme.default.css">
<link rel="stylesheet" type="text/css" href="/plugins/OwlCarousel2-2.2.1/animate.css">
<link rel="stylesheet" type="text/css" href="/plugins/slick-1.8.0/slick.css">


<link rel="stylesheet" type="text/css" href="/styles/main_styles.css">
<style type="text/css"> .cat_menu_container ul {visibility: visible; opacity: 100; min-height: 75vh; box-shadow: none;}</style>
<link rel="stylesheet" type="text/css" href="/styles/responsive.css">



</head>

<body>

<div class="super_container">

	<!-- Header -->
	<header class="header">
		<?php
			include "a_header.php";
		?>
	</header>

	<!-- Slider -->
<div class="owl-carousel owl-theme banner_2_slider">
	<?php
		foreach($db->baner_lista() as $key){
			echo "<div class='owl-item'>";
				echo "<div class='banner'>";
					echo "<div class='banner_background' style='background-image:url(".$db->banner.$key->img.")'></div>";
					echo "<div class='container fill_height'>";
						echo "<div class='row fill_height'>";

							echo "<div class='col-lg-5 offset-lg-4 fill_height'>";
								echo "<div class='banner_content'>";

									echo "<h1 class='banner_text'>".$key->titulo."</h1>";
									echo "<div class='banner_price'><span>".$key->texto."</span></div>";
									if(strlen($key->enlace)>0){
										echo "<div class='button banner_button'><a href='".$key->enlace."'>IR</a></div>";
									}
								echo "</div>";
							echo "</div>";
						echo "</div>";

					echo "</div>";
				echo "</div>";
			echo "</div>";
		}
	 ?>


</div>


	<!-- Characteristics -->
	<div class="characteristics">
		<div class="container">
			<div class="row">

				<!-- Char. Item -->
				<div class="col-lg-3 col-md-6 char_col">

					<div class="char_item d-flex flex-row align-items-center justify-content-start">
						<div class="char_icon"><img src="/img/comprasegura.png" alt="" width="50px"></div>
						<div class="char_content">
							<div class="char_title">Compras seguras</div>
						</div>
					</div>
				</div>

				<!-- Char. Item -->
				<div class="col-lg-3 col-md-6 char_col">

					<div class="char_item d-flex flex-row align-items-center justify-content-start">
						<div class="char_icon"><img src="/img/enviosseguros.png" alt="" width="50px"></div>
						<div class="char_content">
							<div class="char_title">Envíos seguros</div>
						</div>
					</div>
				</div>

				<!-- Char. Item -->
				<div class="col-lg-3 col-md-6 char_col">

					<div class="char_item d-flex flex-row align-items-center justify-content-start">
						<div class="char_icon"><img src="/img/devolucion.png" alt="" width="50px"></div>
						<div class="char_content">
							<div class="char_title">Devoluciones fáciles</div>

						</div>
					</div>
				</div>

				<!-- Char. Item -->
				<div class="col-lg-3 col-md-6 char_col">

					<div class="char_item d-flex flex-row align-items-center justify-content-start">
						<div class="char_icon"><img src="/img/tarjetas.png" alt="" width="50px"></div>
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

										echo "<div class='owl-item deals_item'>";
										echo "<a href='/producto/".$key->clave."'>";
										echo "<div class='deals_image'><img src='/".$db->doc.$key->img."' alt=''></div>
											<div class='deals_content'>
												<div class='deals_info_line d-flex flex-row justify-content-start'>
													<div class='deals_item_category'><a href='#'>".$key->categoria."</a></div>
													<div class='deals_item_price_a ml-auto'>".moneda($preciof)."</div>
												</div>
												<div class='deals_info_line d-flex flex-row justify-content-start'>
													<div class='deals_item_name'>".$key->nombre."</div>
													<div class='deals_item_price ml-auto'>".moneda($preciof)."</div>
												</div>
												<div class='available'>
													<div class='available_line d-flex flex-row justify-content-start'>
														<button class='btn btn-outline-primary btn-block' onclick='carrito(".$key->id.",1)'>Agregar al carrito</button>
													</div>
												</div>

											</div>";
											echo "</a>";
										echo "</div>";
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


											echo "<div class='featured_slider_item'>";
											echo "<a href='/producto/".$key->clave."'>";
												echo "<div class='border_active'></div>
												<div class='product_item discount d-flex flex-column align-items-center justify-content-center text-center'>
													<div class='product_image d-flex flex-column align-items-center justify-content-center'><img src='/".$db->doc.$key->img."' alt='' width='90%'></div>
													<div class='product_content'>
														<div class='product_price discount'>".moneda($preciof)."<span>".moneda($preciof)."</span></div>
														<div class='product_name'><div><a href='/producto/".$key->clave."'>".$key->nombre."</a></div></div>
														<div class='product_extras'>
															<button class='product_cart_button' onclick='carrito(".$key->id.",1)'>Agregar al carrito</button>
														</div>
													</div>
													<div class='product_fav' onclick='wish(".$key->id.")'><i class='fas fa-heart'></i></div>";
													/*
													<ul class='product_marks'>
														<li class='product_mark product_discount'>-25%</li>
														<li class='product_mark product_new'>new</li>
													</ul>*/
												echo "</div>";
												echo "</a>";
											echo "</div>";
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
						<a href="https://tic-shop.com.mx/tienda.php?tipo=1&id=9">
							<div class="owl-item">
								<div class="popular_category d-flex flex-column align-items-center justify-content-center">
									<div class="popular_category_image"><img src="/img/cat/Comunicaciones-y-Redes.jpg" alt=""></div>
									<div class="popular_category_text">Comunicaciones y Redes</div>
								</div>
							</div>
						</a>
							<!-- Popular Categories Item -->
							<a href="https://tic-shop.com.mx/tienda.php?tipo=1&id=3">
							<div class="owl-item">
								<div class="popular_category d-flex flex-column align-items-center justify-content-center">
									<div class="popular_category_image"><img src="/img/cat/Construye-tu-Negocio.jpg" alt=""></div>
									<div class="popular_category_text">Construye tu Negocio</div>
								</div>
							</div>
							</a>

							<!-- Popular Categories Item -->
						<a href="https://tic-shop.com.mx/tienda.php?tipo=1&id=8">
							<div class="owl-item">
								<div class="popular_category d-flex flex-column align-items-center justify-content-center">
									<div class="popular_category_image"><img src="/img/cat/Electrónica-Y-Hogar.jpg" alt=""></div>
									<div class="popular_category_text">Electrónica y Hogar</div>
								</div>
							</div>
						</a>

							<!-- Popular Categories Item -->
						<a href="https://tic-shop.com.mx/tienda.php?tipo=1&id=7">
							<div class="owl-item">
								<div class="popular_category d-flex flex-column align-items-center justify-content-center">
									<div class="popular_category_image"><img src="/img/cat/Equipo-de-Energía.jpg" alt=""></div>
									<div class="popular_category_text">Equipo de Energía</div>
								</div>
							</div>
						</a>

							<!-- Popular Categories Item -->

						<a href="https://tic-shop.com.mx/tienda.php?tipo=1&id=1">
							<div class="owl-item">
								<div class="popular_category d-flex flex-column align-items-center justify-content-center">
									<div class="popular_category_image"><img src="/img/cat/Gamer.jpg" alt=""></div>
									<div class="popular_category_text">Gamers</div>
								</div>
							</div>
						</a>

							<!-- Popular Categories Item -->

						<a href="https://tic-shop.com.mx/tienda.php?tipo=1&id=6">
							<div class="owl-item">
								<div class="popular_category d-flex flex-column align-items-center justify-content-center">
									<div class="popular_category_image"><img src="/img/cat/Impresion.jpg" alt=""></div>
									<div class="popular_category_text">Impresión</div>
								</div>
							</div>
						</a>

							<!-- Popular Categories Item -->

						<a href="https://tic-shop.com.mx/tienda.php?tipo=1&id=2">
							<div class="owl-item">
								<div class="popular_category d-flex flex-column align-items-center justify-content-center">
									<div class="popular_category_image"><img src="/img/cat/Mejora-tu-Pc.jpg" alt=""></div>
									<div class="popular_category_text">Mejora tu Pc</div>
								</div>
							</div>
						</a>


							<!-- Popular Categories Item -->

						<a href="hhttps://tic-shop.com.mx/tienda.php?tipo=1&id=4">
							<div class="owl-item">
								<div class="popular_category d-flex flex-column align-items-center justify-content-center">
									<div class="popular_category_image"><img src="/img/cat/Mi-Compu.jpg" alt=""></div>
									<div class="popular_category_text">Mi compu</div>
								</div>
							</div>
						</a>

							<!-- Popular Categories Item -->

						<a href="https://tic-shop.com.mx/tienda.php?tipo=1&id=5">
							<div class="owl-item">
								<div class="popular_category d-flex flex-column align-items-center justify-content-center">
									<div class="popular_category_image"><img src="/img/cat/Seguridad-y-Vigilancia.jpg" alt=""></div>
									<div class="popular_category_text">Seguridad y Vigilancia</div>
								</div>
							</div>
						</a>



						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Banner -->



	<div class="banner_fijo">
		<!-- Banner 2 Slider -->
		<!-- Banner 2 Slider Item -->
		<?php
			$key=$db->baner2(1);
			echo "<div style='background-image:url(".$db->banner.$key->img."); background-size: cover;'>";
				echo "<div class='banner_2_item'>";
					echo "<div class='container fill_height'>";
						echo "<div class='row fill_height'>";
							echo "<div class='col-lg-4 col-md-6 fill_height'>";
								echo "<div class='banner_2_content'>";
									echo "<div class='banner_2_title'>".$key->texto."</div>";
									/*echo "<div class='banner_2_text'>10% DE DESCUENTO EN ACCESORIOS</div>";
									echo "<div class='rating_r rating_r_4 banner_2_rating'><i></i><i></i><i></i><i></i><i></i></div>";
									*/
									if(strlen($key->enlace)>0){
										echo "<div class='button banner_2_button'><a href='".$key->enlace."'>Ver productos</a></div>";
									}
								echo "</div>";
							echo "</div>";
						echo "</div>";
					echo "</div>";
				echo "</div>";
			echo "</div>";
			?>
		</div>




	<!-- Hot New Arrivals -->
	<div class="new_arrivals">
		<div class="container">
			<div class="row">
				<div class="col">
					<div class="tabbed_container">
						<div class="tabs clearfix tabs-right">
							<div class="new_arrivals_title" style="font-size: 18px;border-bottom: solid;border-color: #b4f22f;">Productos de la semana</div>
							<ul class="clearfix">
								<li class="active"></li>
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
											foreach($semana as $key){
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


												echo "<div class='arrivals_slider_item' >";
												echo "<a href='/producto/".$key->clave."'>";
													echo "<div class='border_active'></div>
													<div class='product_item is_new d-flex flex-column align-items-center justify-content-center text-center' >
														<div class='product_image d-flex flex-column align-items-center justify-content-center'><img src='/".$db->doc.$key->img."' alt='' width='100px'></div>
														<div class='product_content'>
															<div class='product_price'>".moneda($preciof)."</div>
															<div class='product_name'><div><a href='/producto/".$key->clave."'>".$key->nombre."</a></div></div>
															<div class='product_extras'>
																<button class='product_cart_button' onclick='carrito(".$key->id.",1)'>Agregar al carrito</button>
															</div>
														</div>
														<div class='product_fav' onclick='wish(".$key->id.")'><i class='fas fa-heart'></i></div>
														<ul class='product_marks'>
															<li class='product_mark product_discount'>-25%</li>
															<li class='product_mark product_new'>Nuevo</li>
														</ul>
													</div>";
													echo "</a>";
												echo "</div>";
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
		<h1>Extenso surtido en productos de tecnología</h1>
<div class="button banner_2_button" style="
    background: white;
"><a href="https://tic-shop.com.mx/tienda.php">Ver productos</a></div>
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
	<div class="copyright">
		<?php
			include "a_copyright.php";
		?>
	</div>

</div>

<script src="/js/jquery-3.3.1.min.js"></script>
<script src="/styles/bootstrap4/popper.js"></script>
<script src="/styles/bootstrap4/bootstrap.min.js"></script>
<script src="/plugins/greensock/TweenMax.min.js"></script>
<script src="/plugins/greensock/TimelineMax.min.js"></script>
<script src="/plugins/scrollmagic/ScrollMagic.min.js"></script>
<script src="/plugins/greensock/animation.gsap.min.js"></script>
<script src="/plugins/greensock/ScrollToPlugin.min.js"></script>
<script src="/plugins/OwlCarousel2-2.2.1/owl.carousel.js"></script>
<script src="/plugins/slick-1.8.0/slick.js"></script>
<script src="/plugins/easing/easing.js"></script>
<script src="/js/custom.js"></script>

<script src="/librerias15/swal/dist/sweetalert2.min.js"></script>
<link rel="stylesheet" href="/librerias15/swal/dist/sweetalert2.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
<script src="/sagyc.js"></script>
</body>

</html>
