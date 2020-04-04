<?php
	require_once("control_db.php");
	$db = new Tienda();
	$id=$_REQUEST['dir'];
	$per = $db->direccion_editar($id);
	$nombre=$per->nombre;
	$apellidos=$per->apellidos;
	$empresa=$per->empresa;
	$direccion1=$per->direccion1;
	$direccion2=$per->direccion2;
	$ciudad=$per->ciudad;
	$cp=$per->cp;
	$pais=$per->pais;
	$estado=$per->estado;
	$mail=$per->mail;
	$telefono=$per->telefono;
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
		<div class='row'>
			<div class='col-3'>
				<div class="btn-group-vertical">
					<a href='' class="btn btn-primary btn-lg btn-block">Pedidos</a>
					<a href='cli_direcciones.php' class="btn btn-primary btn-lg btn-block">Direcciones</a>
 				 	<a href='cli_datos.php' class="btn btn-primary btn-lg btn-block">Mis datos</a>
 				 	<a href='#' class="btn btn-primary btn-lg btn-block" onclick='salir()'>Salir</a>
				</div>
			</div>
			<div class='col-9'>
				<?php

					echo "<div class='card-body'>";
				?>
				<form id='direccion' action=''>
					<div class='modal-header'>Dirección</h5>
					</div>
						<div class='modal-body' >
						<?php
							echo "<input type='hidden' id='id' NAME='id' value='$id'>";
						?>
							<div class='row'>
								<div class="col-4">
									<label>Nombre</label>
									<input type="text" class="form-control" id="nombre" name='nombre' placeholder="Nombre" value="<?php echo $nombre; ?>" required>
								</div>

								<div class="col-8">
									<label>Apellidos</label>
									<input type="text" class="form-control" id="apellidos" name='apellidos' placeholder="Apellidos" value="<?php echo $apellidos; ?>" required>
								</div>

								<div class="col-12">
									<label>Empresa</label>
									<input type="text" class="form-control" id="empresa" name='empresa' placeholder="Empresa" value="<?php echo $empresa; ?>" required>
								</div>

								<div class="col-12">
									<label>Dirección linea 1</label>
									<input type="text" class="form-control" id="direccion1" name='direccion1' placeholder="Dirección linea 1" value="<?php echo $direccion1; ?>" required>
								</div>

								<div class="col-12">
									<label>Dirección linea 2</label>
									<input type="text" class="form-control" id="direccion2" name='direccion2' placeholder="Dirección linea 2" value="<?php echo $direccion2; ?>" required>
								</div>

								<div class="col-4">
									<label>Ciudad</label>
									<input type="text" class="form-control" id="ciudad" name='ciudad' placeholder="Ciudad" value="<?php echo $ciudad; ?>" required>
								</div>

								<div class="col-4">
									<label>Código postal</label>
									<input type="text" class="form-control" id="cp" name='cp' placeholder="Código postal" value="<?php echo $cp; ?>" required>
								</div>

								<div class="col-4">
									<label>Pais</label>
									<input type="text" class="form-control" id="pais" name='pais' placeholder="Pais" value="<?php echo $pais; ?>" required>
								</div>

								<div class="col-4">
									<label>Estado</label>
									<input type="text" class="form-control" id="estado" name='estado' placeholder="Estado" value="<?php echo $estado; ?>" required>
								</div>

								<div class="col-4">
									<label>Correo</label>
									<input type="text" class="form-control" id="mail" name='mail' placeholder="Correo" value="<?php echo $mail; ?>" required>
								</div>

								<div class="col-4">
									<label>Telefono</label>
									<input type="text" class="form-control" id="telefono" name='telefono' placeholder="Telefono" value="<?php echo $telefono; ?>" required>
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

<!--   Alertas   -->
<script src="librerias15/swal/dist/sweetalert2.min.js"></script>
<link rel="stylesheet" href="librerias15/swal/dist/sweetalert2.min.css">


<script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
<script src="sagyc.js"></script>
</body>

</html>
