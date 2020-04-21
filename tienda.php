<?php
	require_once("control_db.php");
	$db = new Tienda();
	$nombre="";
	$contar=0;
	$tipo=0;

	$marcaf="";
	$_SESSION['pag']=20;

	$pag=0;
	if(isset($_REQUEST['pag'])){
		$pag=$_REQUEST['pag'];
	}
	if(isset($_REQUEST['marca']) and strlen($_REQUEST['marca'])>0){
		$marcaf=$_REQUEST['marcaf'];
	}
	else{
		$marcaf="";
	}

	if(isset($_REQUEST['tipo']) and $_REQUEST['tipo']==1){
		$tipo=$_REQUEST['tipo'];
		$id=$_REQUEST['id'];
		$rx=$db->categorias_name($id);
		$nombre=$rx->descripcion;
		$cat=$id;
		$marca=$db->n1_productos_marcas($id,$marcaf);
	}
	else if(isset($_REQUEST['tipo']) and $_REQUEST['tipo']==2){
		$tipo=$_REQUEST['tipo'];
		$id=$_REQUEST['id'];
		$rx=$db->cat_categoria_name($id);
		$nombre=$rx->heredado;
		$cat=$rx->categoria;
		$marca=$db->n2_productos_marcas($rx->categoria,$marcaf);
	}
	else if(isset($_REQUEST['tipo']) and $_REQUEST['tipo']==3){
		$tipo=$_REQUEST['tipo'];
		$id=$_REQUEST['id'];
		$rx=$db->sub_categoria_name($id);
		$nombre=$rx->heredado;
		$cat=$rx->subcategoria;
		$marca=$db->n3_productos_marcas($rx->subcategoria,$marcaf);
	}
	else{
		$tipo=4;
		$id=0;
		$cat="";

		$marca=$db->n4_productos_marcas($marcaf);
	}

	/////////////////////////////////////////////
	$filtro="";
	$consulta="";
	if(strlen($marcaf)>0){
		$filtro=" and productos.marca='$marcaf'";
	}
	if($tipo==1){
		$consulta="and producto_cat.idcategoria='$cat'";
	}
	if($tipo==2){
		$consulta="and productos.categoria='$cat'";
	}
	if($tipo==3){
		$consulta="and productos.subcategoria='$cat'";
	}
	if($tipo==4 or $tipo==0){
		$consulta="";
	}

	$sql="select count(productos.id) as total from productos
	left outer join categoria_ct on productos.categoria=categoria_ct.categoria
	left outer join producto_cat on categoria_ct.id=producto_cat.idcategoria_ct
	where productos.activo=1 and productos.existencia>0 $consulta $filtro";
	$sth = $db->dbh->prepare($sql);
	$sth->execute();
	$resp=$sth->fetch(PDO::FETCH_OBJ);
	echo "Total:".$resp->total;
	$num_paginas=$resp->total/$_SESSION['pag'];

	$tam=($pag*$_SESSION['pag']);
	$sql="select * from productos
	left outer join categoria_ct on productos.categoria=categoria_ct.categoria
	left outer join producto_cat on categoria_ct.id=producto_cat.idcategoria_ct
	where productos.activo=1 and productos.existencia>0 $consulta $filtro limit $tam,".$_SESSION['pag']."";

	//echo $sql;
	$sth = $db->dbh->prepare($sql);
	$sth->execute();
	$resp=$sth->fetchAll(PDO::FETCH_OBJ);
	/////////////////////////////////////////////
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>TIC-Shop</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="OneTech shop project">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="/styles/bootstrap4/bootstrap.min.css">
<link href="/plugins/fontawesome-free-5.0.1/css/fontawesome-all.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="/plugins/OwlCarousel2-2.2.1/owl.carousel.css">
<link rel="stylesheet" type="text/css" href="/plugins/OwlCarousel2-2.2.1/owl.theme.default.css">
<link rel="stylesheet" type="text/css" href="/plugins/OwlCarousel2-2.2.1/animate.css">
<link rel="stylesheet" type="text/css" href="/plugins/jquery-ui-1.12.1.custom/jquery-ui.css">

<link rel="stylesheet" type="text/css" href="/styles/main_styles.css">
<link rel="stylesheet" type="text/css" href="/styles/shop_styles.css">
<link rel="stylesheet" type="text/css" href="/styles/shop_responsive.css">
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
	<div class="home" style="position: relative;max-height: 70px;background: ghostwhite;top: -14px;">

		<div class="home_content d-flex flex-column align-items-center justify-content-center">
			<h2 class="home_title" style="text-transform: uppercase;font-size: 30px;"><?php echo $nombre; ?></h2>
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
							if($tipo==1){
								echo "<div class='sidebar_section'>
									<div class='sidebar_title'>Categoria</div>
									<ul class='sidebar_categories'>";
									foreach($db->cat_ct($id) as $key2){
										echo "<li><a href='/tienda.php?tipo=2&id=".$key2->id."'>".$key2->categoria."</a></li>";
									}
									echo "</ul>
									</div>";
							}
							if($tipo==2){
								echo "<div class='sidebar_section'>
									<div class='sidebar_title'>Categoria</div>
									<ul class='sidebar_categories'>";
									 foreach($db->sub_cat($id) as $key3){
										echo "<li><a href='/tienda.php?tipo=3&id=".$key3->id."'>".$key3->subcategoria."</a></li>";
									}
									echo "</ul>
									</div>";
							}

							if($tipo==4){
									echo "<div class='sidebar_section'>
									<div class='sidebar_title'>Categorias</div>
									<ul class='sidebar_categories'>";
									 foreach($db->categorias() as $key){
										echo "<li><a href='/tienda.php?tipo=1&id=".$key->idcategoria."'>".$key->descripcion."</a></li>";
									}
									echo "</ul>
									</div>";
							}
						?>

						<div class="sidebar_section">
							<div class="sidebar_subtitle brands_subtitle">Marcas</div>
							<ul class="brands_list">
								<?php
									foreach($marca as $key){
										echo "<li class='brand'><a href='/tienda.php?tipo=".$tipo."&id=".$id."&marca=".$key->marca."'>".$key->marca."</a></li>";
									}
								 ?>
							</ul>
						</div>
					</div>

				</div>

				<div class="col-lg-9">

					<!-- Shop Content -->
					<div class="shop_content">
						<div class="shop_bar clearfix">
							<div class="shop_product_count">Productos encontrados</div>
							<div class="shop_sorting">
								<span>Ordernar por:</span>
								<ul>
									<li>
										<span class="sorting_text">-<i class="fas fa-chevron-down"></span></i>
										<ul>
											<li class="shop_sorting_button" data-isotope-option='{ "sortBy": "name" }'>Nombre</li>
											<li class="shop_sorting_button" data-isotope-option='{ "sortBy": "price" }'>Precio</li>
										</ul>
									</li>
								</ul>
							</div>
						</div>

						<div class="product_grid">
							<div class="product_grid_border"></div>
							<!-- Product Item -->
							<?php
								$a="?id=".rand(1,1500);
								foreach($resp as $key){
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
									echo "<a href='/producto/".$key->clave."'><div class='product_item'>
										<div class='product_border'></div>
										<div class='product_image d-flex flex-column align-items-center justify-content-center'><img src='/".$db->doc.$key->img.$a."' alt='' width='100px'></div>
										<div class='product_content'>
											<div class='product_price'>".moneda($preciof)."</div>
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
						<div>
							<nav aria-label="Page navigation example">
							  <ul class="pagination">
									<?php
										echo "<li class='page-item'><a class='page-link' href='tienda.php?tipo=$tipo&id=$id&pag=0&marca=$marcaf'>Primera</a></li>";
										for($i=1; $i<$num_paginas;$i++){
											echo "<li class='page-item'><a class='page-link' href='tienda.php?tipo=$tipo&id=$id&pag=$i&marca=$marcaf'>$i</a></li>";
										}
										echo "<li class='page-item'><a class='page-link' href='tienda.php?tipo=$tipo&id=$id&pag=$num_paginas&marca=$marcaf'>Ultima</a></li>";
									?>
							  </ul>
							</nav>
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
<script src="/plugins/easing/easing.js"></script>
<script src="/plugins/Isotope/isotope.pkgd.min.js"></script>
<script src="/plugins/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<script src="/plugins/parallax-js-master/parallax.min.js"></script>
<script src="/js/shop_custom.js"></script>

<script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
<script src="/sagyc.js"></script>


</body>

</html>
