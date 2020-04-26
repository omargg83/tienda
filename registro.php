<?php
	require_once("control_db.php");
	$db = new Tienda();

	if(isset($_SESSION['autoriza_web']) and $_SESSION['autoriza_web']==1 and strlen($_SESSION['idcliente'])>0 and $_SESSION['interno']==1){
		header('Location: /clientes.php');
	}
	else{
	}
	$nombre="";
	$apellido="";
	$correo="";
	$pass="";

?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>TIC SHOP</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="TIC-SHOP la mejor tienda de tecnologia">
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
	<form id='registro' action=''>
		<div class="cart_section">
			<div class="container reg">
				<h3 class='text-center'>Registro</h3>
				<div class="row">
					<div class="col-4">
			      <label class='text-center'>Nombre</label>
			      <input type="text" class="form-control" id="nombre" name='nombre' placeholder="" value="<?php echo $nombre; ?>" required>
			    </div>

					<div class="col-4">
			      <label class='text-center'>Apellidos</label>
			      <input type="text" class="form-control" id="apellido" name='apellido' placeholder="" value="<?php echo $apellido; ?>" required autocomplete="off">
					</div>
					<div class="col-4">
			      <label class='text-center'>Correo</label>
			      <input type="email" class="form-control" id="correo" name='correo' placeholder="" value="<?php echo $correo; ?>" required autocomplete="off">
			    </div>
				</div>

				<div class='row'>
					<div class="col-12">
						<label>Dirección (línea 1)</label>
						<input type="text" class="form-control" id="direccion1" name='direccion1' placeholder="" value="" required>
					</div>
					<div class="col-4">
						<label>Entre calles</label>
						<input type="text" class="form-control" id="entrecalles" name='entrecalles' placeholder="" value="" >
					</div>
					<div class="col-4">
						<label>No. exterior</label>
						<input type="text" class="form-control" id="numero" name='numero' placeholder="" value="" >
					</div>
					<div class="col-4">
						<label>Colonia</label>
						<input type="text" class="form-control" id="colonia" name='colonia' placeholder="" value="" >
					</div>
					<div class="col-4">
						<label>Ciudad</label>
						<input type="text" class="form-control" id="ciudad" name='ciudad' placeholder="" value="" required>
					</div>
					<div class="col-4">
						<label>Código postal</label>
						<input type="text" class="form-control" id="cp" name='cp' placeholder="" value="" required>
					</div>
					<div class="col-4">
						<label>País</label>
						<input type="text" class="form-control" id="pais" name='pais' placeholder="" value="" required>
					</div>
					<div class="col-4">
						<label>Estado</label>
						<input type="text" class="form-control" id="estado" name='estado' placeholder="" value="" required>
					</div>
					<div class="col-4">
						<label>Teléfono</label>
						<input type="text" class="form-control" id="telefono" name='telefono' placeholder="" value="" required>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-4 offset-4">
			      <label class='text-center'>Contraseña</label>
			      <input type="password" class="form-control" id="pass" name='pass' placeholder="" value="<?php echo $pass; ?>" required autocomplete="off">
					</div>
				</div>
				<div class="row">
					<div class="col-4 offset-4">
			      <label class='text-center'>Repetir contraseña</label>
			      <input type="password" class="form-control" id="pass2" name='pass2' placeholder="" value="<?php echo $pass; ?>" required autocomplete="off">
			    </div>
				</div>


				<div class="row">
					<div class="col-4 offset-4">
						<button type="submit" class="btn btn-primary btn-block" style="
    margin-top: 20px;
    background-color: #b4f22f;
    border: none;
    color: black;
    cursor: pointer;">Registrar</button>
					</div>
				</div>
				<div class="row">
					<div class="col-4 offset-4">
						<small id="emailHelp" class="form-text text-muted">
						Al crear una cuenta, aceptas los <a href='/terminos-condiciones.php'>Terminos y Condiciones </a > y el <a href='/aviso.php'>Aviso de Privacidad</a> de TIC-SHOP.
						¿Ya tienes una cuenta? <a href='/acceso.php'>Iniciar sesión</a>
						</small>
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

<!--   Alertas   -->
<script src="/librerias15/swal/dist/sweetalert2.min.js"></script>
<link rel="stylesheet" href="/librerias15/swal/dist/sweetalert2.min.css">


<script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
<script src="/sagyc.js"></script>
</body>

</html>
