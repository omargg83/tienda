<?php
	require_once("control_db.php");
	$db = new Tienda();
	$correo="";
	$pass="";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>TIC SHOP</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="OneTech shop project">
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
	<form id='acceso' action=''>
		<div class="cart_section">
			<div class="container">
				<div class="row">
					<div class="col-12" >
						<div class="col-4 offset-4 text-center">
							<label>Correo</label>
							<input type="text" class="form-control" id="userAcceso" name='userAcceso' placeholder="Correo" value="<?php echo $correo; ?>" required>
						</div>
						<div class="col-4 offset-4 text-center">
							<label>Contraseña</label>
							<input type="password" class="form-control" id="passAcceso" name='passAcceso' placeholder="Contraseña" value="<?php echo $pass; ?>" required>
						</div>
						<div class="col-4 offset-4 text-center"><br>

							<button class="btn btn-outline-primary btn-block btn-sm" type="submit" style="
							    background-color: #b4f22f;
							    border: none;
							    color: black;
							    cursor: pointer;
							"><i class="fa fa-check"></i>Aceptar</button>

							<br>
							<p><a href='/recuperar/'>¿Olvidaste tu contraseña?</a></p>
						</div>
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


<script src="/librerias15/jQuery-MD5-master/jquery.md5.js"></script>

<!--   Alertas   -->
<script src="/librerias15/swal/dist/sweetalert2.min.js"></script>
<link rel="stylesheet" href="/librerias15/swal/dist/sweetalert2.min.css">

<script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
<script src="/sagyc.js"></script>
</body>

</html>
