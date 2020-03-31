<?php
	require_once("control_db.php");
	$db = new Tienda();
	$nombre="";
	$contar=0;
	if(isset($_REQUEST['cat'])){
		$tipo=1;
		$cat=$_REQUEST['cat'];
		echo "global $cat";
	}
	if(isset($_REQUEST['cat1'])){
		$tipo=2;
		$cat1=$_REQUEST['cat1'];
		$ncat=$_REQUEST['ncat'];
		
		$resp=$db->sub_categoria($ncat);
		echo "cat1 $cat1  cat $ncat";
	}
	if(isset($_REQUEST['sub'])){
		$tipo=3;
		$sub=$_REQUEST['sub'];
		$ncat=$_REQUEST['ncat'];
		$nombre=$ncat;
//		echo "sub $sub cat $cat ";
		$resp=$db->sub_categoria($ncat);
		$contar=count($resp);
	}



?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Shop</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="OneTech shop project">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="styles/bootstrap4/bootstrap.min.css">
<link href="plugins/fontawesome-free-5.0.1/css/fontawesome-all.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="plugins/OwlCarousel2-2.2.1/owl.carousel.css">
<link rel="stylesheet" type="text/css" href="plugins/OwlCarousel2-2.2.1/owl.theme.default.css">
<link rel="stylesheet" type="text/css" href="plugins/OwlCarousel2-2.2.1/animate.css">
<link rel="stylesheet" type="text/css" href="plugins/jquery-ui-1.12.1.custom/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="styles/shop_styles.css">
<link rel="stylesheet" type="text/css" href="styles/shop_responsive.css">
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

	<!-- Home -->
	<div class="home">
		<div class="home_background parallax-window" data-parallax="scroll" data-image-src="images/shop_background.jpg"></div>
		<div class="home_overlay"></div>
		<div class="home_content d-flex flex-column align-items-center justify-content-center">
			<h2 class="home_title"><?php echo $nombre; ?></h2>
		</div>
	</div>

	<!-- Shop -->
	<div class="shop">
		<div class="container">
			<div class="row">
				<div class="col-lg-3">

					<!-- Shop Sidebar -->
					<div class="shop_sidebar">

						<?php
							if($tipo==1 or $tipo==2){
								echo "<div class='sidebar_section'>
									<div class='sidebar_title'>Categories</div>
									<ul class='sidebar_categories'>
										<li><a href='#'>Computers & Laptops</a></li>
										<li><a href='#'>Cameras & Photos</a></li>
										<li><a href='#'>Hardware</a></li>
										<li><a href='#'>Smartphones & Tablets</a></li>
										<li><a href='#'>TV & Audio</a></li>
										<li><a href='#'>Gadgets</a></li>
										<li><a href='#'>Car Electronics</a></li>
										<li><a href='#'>Video Games & Consoles</a></li>
										<li><a href='#'>Accessories</a></li>
									</ul>
								</div>";
							}
						?>

						<div class="sidebar_section filter_by_section">
							<div class="sidebar_title">Filter By</div>
							<div class="sidebar_subtitle">Price</div>
							<div class="filter_price">
								<div id="slider-range" class="slider_range"></div>
								<p>Range: </p>
								<p><input type="text" id="amount" class="amount" readonly style="border:0; font-weight:bold;"></p>
							</div>
						</div>

						<div class="sidebar_section">
							<div class="sidebar_subtitle brands_subtitle">Brands</div>
							<ul class="brands_list">
								<li class="brand"><a href="#">Apple</a></li>
								<li class="brand"><a href="#">Beoplay</a></li>
								<li class="brand"><a href="#">Google</a></li>
								<li class="brand"><a href="#">Meizu</a></li>
								<li class="brand"><a href="#">OnePlus</a></li>
								<li class="brand"><a href="#">Samsung</a></li>
								<li class="brand"><a href="#">Sony</a></li>
								<li class="brand"><a href="#">Xiaomi</a></li>
							</ul>
						</div>
					</div>

				</div>

				<div class="col-lg-9">

					<!-- Shop Content -->

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
									echo "<a href='product.php?id=".$key->id."'><div class='product_item is_new'>
										<div class='product_border'></div>
										<div class='product_image d-flex flex-column align-items-center justify-content-center'><img src='".$db->doc.$key->img."' alt='' width='100px'></div>
										<div class='product_content'>
											<div class='product_price'>".moneda($key->preciof)."</div>
											<div class='product_name'><div><a href='#' tabindex='0'>".$key->nombre."</a></div></div>
										</div>
										<div class='product_fav'><i class='fas fa-heart'></i></div>
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
<script src="plugins/easing/easing.js"></script>
<script src="plugins/Isotope/isotope.pkgd.min.js"></script>
<script src="plugins/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<script src="plugins/parallax-js-master/parallax.min.js"></script>
<script src="js/shop_custom.js"></script>
</body>

</html>
