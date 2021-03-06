<?php
	require_once("control_db.php");
	$db = new Tienda();

	if (isset($_REQUEST['dir'])){
		$id=$_REQUEST['dir'];
		if($id>0){
			$per = $db->direccion_editar($id);
			if(is_object($per)){
				$direccion1=$per->direccion1;
				$entrecalles=$per->entrecalles;
				$numero=$per->numero;
				$colonia=$per->colonia;
				$ciudad=$per->ciudad;
				$cp=$per->cp;
				$pais=$per->pais;
				$estado=$per->estado;
				$error=0;
			}
		}
	}
	else{
		$id=0;
		$direccion1="";
		$entrecalles="";
		$colonia="";
		$numero="";
		$ciudad="";
		$cp="";
		$pais="";
		$estado="";
		$error=0;
	}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>TIC SHOP</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="TicShop la tienda de tecnología mas grande de México">
<meta name="keywords" content="Electrónica,Tecnología,Hardware,Software,Tarjetas Video,Tarjetas Madre,Procesadores,Tienda de tecnología,Tienda de tecnología online,Electronicos en linea" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="/styles/bootstrap4/bootstrap.min.css">
<link href="/plugins/fontawesome-free-5.0.1/css/fontawesome-all.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="/styles/main_styles.css">
<link rel="stylesheet" type="text/css" href="/styles/cart_styles.css">
<link rel="stylesheet" type="text/css" href="/styles/cart_responsive.css">

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
		<div class='row'>
			<div class='col-3' id='cliedash'>
				<div class="btn-group-vertical">
					<a href='/clientes/' class="btn btn-primary btn-lg btn-block">Pedidos</a>
					<a href='/direcciones/' class="btn btn-primary btn-lg btn-block">Direcciones</a>
					<a href='/datos/' class="btn btn-primary btn-lg btn-block">Mis datos</a>
 				 	<a href='#' class="btn btn-primary btn-lg btn-block" onclick='salir()'>Salir</a>
				</div>
			</div>
			<div class='col-9' id='cliedash'>
				<?php

					echo "<div class='card-body'>";
					if($error==0){
				?>
				<form id='direccion' action=''>

					<div class='modal-header'>Dirección</h5>
					</div>
						<div class='modal-body' >
						<?php
							echo "<input type='hidden' id='id' NAME='id' value='$id'>";
						?>
							<div class='row'>

								<div class="col-12">
									<label>Dirección</label>
									<input type="text" class="form-control" id="direccion1" name='direccion1' placeholder="Dirección" value="<?php echo $direccion1; ?>" required>
								</div>

								<div class="col-4">
									<label>Entre calles</label>
									<input type="text" class="form-control" id="entrecalles" name='entrecalles' placeholder="Entre calles" value="<?php echo $entrecalles; ?>" required>
								</div>

								<div class="col-4">
									<label>Num. Exterior</label>
									<input type="text" class="form-control" id="numero" name='numero' placeholder="Num. Exterior" value="<?php echo $numero; ?>" required>
								</div>

								<div class="col-4">
									<label>Colonia</label>
									<input type="text" class="form-control" id="colonia" name='colonia' placeholder="Colonia" value="<?php echo $colonia; ?>" required>
								</div>

								<div class="col-4">
									<label>Ciudad</label>
									<input type="text" class="form-control" id="ciudad" name='ciudad' placeholder="Ciudad" value="<?php echo $ciudad; ?>" required>
								</div>

								<div class="col-4">
									<label>Código postal</label>
									<input type="number" class="form-control input-number" id="cp" name='cp' placeholder="Código postal" value="<?php echo $cp; ?>" required>
								</div>

								<div class="col-4">
									<label>Pais</label>
									<input type="text" class="form-control" id="pais" name='pais' placeholder="Pais" value="<?php echo $pais; ?>" required>
								</div>

								<div class="col-4">
									<label>Estado</label>
									<input type="text" class="form-control" id="estado" name='estado' placeholder="Estado" value="<?php echo $estado; ?>" required>
								</div>


							</div>
						</div>
						<div class='modal-footer' >


							<div class='btn-group'>
							<button class='btn btn-outline-secondary btn-sm' type='submit' id='acceso' title='Guardar'><i class='far fa-save'></i>Guardar</button>
							</div>
						</div>
				</form>

				<?php
				}
				else{
					echo "<h3 class='text-center'>No encontrado</h3>";
				}
					echo "</div>";

				?>
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

<script src="/js/jquery-3.3.1.min.js"></script>
<script src="/styles/bootstrap4/popper.js"></script>
<script src="/styles/bootstrap4/bootstrap.min.js"></script>
<script src="/plugins/greensock/TweenMax.min.js"></script>
<script src="/plugins/greensock/TimelineMax.min.js"></script>
<script src="/plugins/scrollmagic/ScrollMagic.min.js"></script>
<script src="/plugins/greensock/animation.gsap.min.js"></script>
<script src="/plugins/greensock/ScrollToPlugin.min.js"></script>
<script src="/plugins/easing/easing.js"></script>
<script src="/js/cart_custom.js"></script>

<!--   Alertas   -->
<script src="/librerias15/swal/dist/sweetalert2.min.js"></script>
<link rel="stylesheet" href="/librerias15/swal/dist/sweetalert2.min.css">

<script src="/sagyc.js"></script>
</body>

</html>
