<?php
	require_once("control_db.php");
	$db = new Tienda();
  $resp=$db->datos();

	$nombre=$resp->nombre;
	$apellido=$resp->apellido;
	$correo=$resp->correo;
	$rfc=$resp->rfc;
	$cfdi=$resp->cfdi;
	$direccion1=$resp->direccion1;
	$entrecalles=$resp->entrecalles;
	$numero=$resp->numero;
	$colonia=$resp->colonia;

	$ciudad=$resp->ciudad;
	$cp=$resp->cp;
	$pais=$resp->pais;
	$estado=$resp->estado;
	$telefono=$resp->telefono;
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
<link rel="stylesheet" type="text/css" href="styles/main_styles.css">
<link rel="stylesheet" type="text/css" href="styles/cart_styles.css">
<link rel="stylesheet" type="text/css" href="styles/cart_responsive.css">

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
					<a href='/clientes.php' class="btn btn-primary btn-lg btn-block">Pedidos</a>
					<a href='/cli_direcciones.php' class="btn btn-primary btn-lg btn-block">Direcciones</a>
 				 	<a href='/cli_datos.php' class="btn btn-primary btn-lg btn-block">Mis datos</a>
 				 	<a href='#' class="btn btn-primary btn-lg btn-block" onclick='salir()'>Salir</a>
				</div>
			</div>
			<div class='col-9'>
				<form id='datos' action=''>
					<h3 class='text-center'>Mis Datos</h3>
					<div class="row">
						<div class="col-4">
				      <label class='text-center'>Nombre</label>
				      <input type="text" class="form-control" id="nombre" name='nombre' placeholder="Nombre" value="<?php echo $nombre; ?>" required>
				    </div>

						<div class="col-4">
				      <label class='text-center'>Apellidos</label>
				      <input type="text" class="form-control" id="apellido" name='apellido' placeholder="Apellidos" value="<?php echo $apellido; ?>" required autocomplete="off">
						</div>

						<div class="col-4">
				      <label class='text-center'>Correo</label>
				      <input type="email" class="form-control" id="correo" name='correo' placeholder="Correo" value="<?php echo $correo; ?>" readonly>
				    </div>
					</div>
	        <div class='row'>
	          <div class="col-3">
	            <label>RFC</label>
	            <input type="text" class="form-control" id="rfc" name='rfc' placeholder="RFC" value="<?php echo $rfc; ?>">
	          </div>

	          <div class="col-6">
	            <label>Uso cfdi</label>
	            <input type="text" class="form-control" id="cfdi" name='cfdi' placeholder="Uso cfdi" value="<?php echo $cfdi; ?>" >
	          </div>
	        </div>

	        <div class='row'>
	          <div class="col-12">
	            <label>Dirección (linea 1)</label>
	            <input type="text" class="form-control" id="direccion1" name='direccion1' placeholder="Dirección (linea 1)" value="<?php echo $direccion1; ?>" >
	          </div>
	          <div class="col-4">
	            <label>Entre calles</label>
	            <input type="text" class="form-control" id="entrecalles" name='entrecalles' placeholder="Entre calles" value="<?php echo $entrecalles; ?>" >
	          </div>
	          <div class="col-4">
	            <label>Num. Exterior</label>
	            <input type="text" class="form-control" id="numero" name='numero' placeholder="Num. Exterior" value="<?php echo $numero; ?>" >
	          </div>
	          <div class="col-4">
	            <label>Colonia</label>
	            <input type="text" class="form-control" id="colonia" name='colonia' placeholder="Colonia" value="<?php echo $colonia; ?>" >
	          </div>
	          <div class="col-4">
	            <label>Ciudad</label>
	            <input type="text" class="form-control" id="ciudad" name='ciudad' placeholder="Ciudad" value="<?php echo $ciudad; ?>" >
	          </div>
	          <div class="col-4">
	            <label>Código postal</label>
	            <input type="number" class="form-control" id="cp" name='cp' placeholder="Código postal" value="<?php echo $cp; ?>" >
	          </div>
	          <div class="col-4">
	            <label>Pais</label>
	            <input type="text" class="form-control" id="pais" name='pais' placeholder="Pais" value="<?php echo $pais; ?>" >
	          </div>
	          <div class="col-4">
	            <label>Estado</label>
	            <input type="text" class="form-control" id="estado" name='estado' placeholder="Estado" value="<?php echo $estado; ?>" >
	          </div>
	          <div class="col-4">
	            <label>Teléfono</label>
	            <input type="text" class="form-control input-number" id="telefono" name='telefono' placeholder="Teléfono" value="<?php echo $telefono; ?>" >
	          </div>
	        </div>

					<div class="row">
						<div class="col-4 offset-4">
							<button type="submit" class="btn btn-primary btn-block">Actualizar</button>
						</div>
					</div>

				</form>

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
