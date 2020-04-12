<?php
	require_once("control_db.php");
	$db = new Tienda();
	$id=$_REQUEST['id'];
	$prod = $db->producto_ver($id);

	$imextra=$db->producto_imagen($id);
	$espe = $db->producto_espe($id);
	$alma = $db->producto_exist($id,1);
	$rel=$db->relacionados($prod->subcategoria);

	$star=$db->estrellas($id);
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
<link rel="stylesheet" type="text/css" href="styles/main_styles.css">
<link rel="stylesheet" type="text/css" href="styles/product_styles.css">
<link rel="stylesheet" type="text/css" href="styles/product_responsive.css">
<style>

		/* Rating Star Widgets Style */
		.rating-stars ul {
		  list-style-type:none;
		  padding:0;

		  -moz-user-select:none;
		  -webkit-user-select:none;
		}
		.rating-stars ul > li.star {
		  display:inline-block;

		}

		/* Idle State of the stars */
		.rating-stars ul > li.star > i.fa {
		  font-size:2.5em; /* Change the size of the stars */
		  color:#ccc; /* Color on idle state */
		}

		/* Hover state of the stars */
		.rating-stars ul > li.star.hover > i.fa {
		  color:#FFCC36;
		}

		/* Selected state of the stars */
		.rating-stars ul > li.star.selected > i.fa {
		  color:#FF912C;
		}
</style>
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
								$envio=0;
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
									<div class="product_fav"><i class="fas fa-heart" style="display: none;"></i></div>
									<button type="button" class="button cart_button" href="#" style="margin-top: 10px;">Cotizar por mayoreo</button>
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

	<div class='container'>
	<?php
		$sql="select * from producto_estrella where idcliente=".$_SESSION['idcliente']." and idproducto=$id";
		$sth = $db->dbh->prepare($sql);
		$sth->execute();
		$tmp=$sth->fetchAll();
		$comento=count($tmp);

		if(isset($_SESSION['autoriza_web']) and $_SESSION['autoriza_web']==1 and strlen($_SESSION['idcliente'])>0 and $_SESSION['interno']==1 and $comento==0){
?>
			<div class='rating-stars text-center'>
		    <ul id='stars'>
		      <li class='star' title='Poor' data-value='1'>
		        <i class='fa fa-star fa-fw'></i>
		      </li>
		      <li class='star' title='Fair' data-value='2'>
		        <i class='fa fa-star fa-fw'></i>
		      </li>
		      <li class='star' title='Good' data-value='3'>
		        <i class='fa fa-star fa-fw'></i>
		      </li>
		      <li class='star' title='Excellent' data-value='4'>
		        <i class='fa fa-star fa-fw'></i>
		      </li>
		      <li class='star' title='WOW!!!' data-value='5'>
		        <i class='fa fa-star fa-fw'></i>
		      </li>
		    </ul>
		  </div>


			<div class='row'>
				<div class='col-12'>
					<label>Tu reseña</label>
					<textarea placeholder='Comparte tu opinión' id='texto' name='texto' class='form-control' rows='5'></textarea>
				</div>
				<div class='col-12'>
					<button type="button" class="btn btn-primary" onclick='estrella(<?php echo $id; ?>)'>Enviar reseña</button>
				</div>
			</div>
<?php
		}


		foreach($star as $key){
			echo "<hr>";
			echo "<div class='row'>";
				echo "<div class='col-12'>";
					echo "Reseña de:".$key->nombre;
				echo "</div>";
				echo "<div class='col-2'>";
					for($i=0; $i<=$key->estrella; $i++){
						echo "<i class='far fa-star'></i>";
					}
				echo "</div>";
				echo "<div class='col-4'>";
					echo fecha($key->fecha);
				echo "</div>";
				echo "<div class='col-12'>";
				echo $key->texto;
				echo "</div>";
			echo "</div>";
		}





	?>
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

<!--   Alertas   -->
<script src="librerias15/swal/dist/sweetalert2.min.js"></script>
<link rel="stylesheet" href="librerias15/swal/dist/sweetalert2.min.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
<script src="sagyc.js"></script>

<script>
$(document).ready(function(){

  /* 1. Visualizing things on Hover - See next part for action on click */
  $('#stars li').on('mouseover', function(){
    var onStar = parseInt($(this).data('value'), 10); // The star currently mouse on

    // Now highlight all the stars that's not after the current hovered star
    $(this).parent().children('li.star').each(function(e){
      if (e < onStar) {
        $(this).addClass('hover');
      }
      else {
        $(this).removeClass('hover');
      }
    });

  }).on('mouseout', function(){
    $(this).parent().children('li.star').each(function(e){
      $(this).removeClass('hover');
    });
  });


  /* 2. Action to perform on click */
  $('#stars li').on('click', function(){
    var onStar = parseInt($(this).data('value'), 10); // The star currently selected
    var stars = $(this).parent().children('li.star');

    for (i = 0; i < stars.length; i++) {
      $(stars[i]).removeClass('selected');
    }

    for (i = 0; i < onStar; i++) {
      $(stars[i]).addClass('selected');
    }

    // JUST RESPONSE (Not needed)
    var ratingValue = parseInt($('#stars li.selected').last().data('value'), 10);
    var msg = "";
    if (ratingValue > 1) {
        msg = "Thanks! You rated this " + ratingValue + " stars.";
    }
    else {
        msg = "We will improve ourselves. You rated this " + ratingValue + " stars.";
    }
    responseMessage(msg);
  });


});
function responseMessage(msg) {
  $('.success-box').fadeIn(200);
  $('.success-box div.text-message').html("<span>" + msg + "</span>");
}

</script>

</body>

</html>
