<?php
	require_once("control_db.php");
	$db = new Tienda();

	$nombre="";
	$apellido="";
	$correo="";
	$pass="";

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
	<form id='registro' action=''>
		<div class="cart_section">
			<div class="container">
				<h3 class='text-center'>Registro</h3>
				<div class="row">
					<div class="col-4 offset-4">
			      <label class='text-center'>Nombre</label>
			      <input type="text" class="form-control" id="nombre" name='nombre' placeholder="Nombre" value="<?php echo $nombre; ?>" required>
			    </div>
		    </div>
				<div class="row">
					<div class="col-4 offset-4">
			      <label class='text-center'>Apellidos</label>
			      <input type="text" class="form-control" id="apellido" name='apellido' placeholder="Apellidos" value="<?php echo $apellido; ?>" required>
					</div>
				</div>
				<div class="row">
					<div class="col-4 offset-4">
			      <label class='text-center'>Correo</label>
			      <input type="text" class="form-control" id="correo" name='correo' placeholder="Correo" value="<?php echo $correo; ?>" required>
			    </div>
			   </div>

				<div class="row">
					<div class="col-4">
			      <label class='text-center'>Contraseña</label>
			      <input type="password" class="form-control" id="pass" name='pass' placeholder="Contraseña" value="<?php echo $pass; ?>" required>
			    </div>
					<div class="col-4">
			      <label class='text-center'>Repetir contraseña</label>
			      <input type="password" class="form-control" id="pass2" name='pass2' placeholder="Contraseña" value="<?php echo $pass; ?>" required>
			    </div>
				</div>

				<div class="row">
					<div class="col-4">
						<button type="submit" class="btn btn-primary">Registrar</button>
					</div>
				</div>
			</div>
		</div>
</form>


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
