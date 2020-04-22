<?php
	require_once("control_db.php");
	$db = new Tienda();
	$idpedido=$_REQUEST['idpedido'];
	$error=0;

	$mercado=$db->ajustes_editar();
	$merca=$mercado->mercado_public;
	$paypal_client=$mercado->paypal_client;

	$ped=$db->pedido_ver($idpedido);
	echo var_dump($ped);
	
	if(!is_array($ped)){
		$error=1;
	}
	else{
		$datos=$db->datos_pedido($idpedido);
		$nombre=$ped->nombre;
		$apellido=$ped->apellido;
		$correo=$ped->correo;
		$rfc=$ped->rfc;
		$cfdi=$ped->cfdi;
		$direccion1=$ped->direccion1;
		$entrecalles=$ped->entrecalles;
		$numero=$ped->numero;
		$colonia=$ped->colonia;
		$ciudad=$ped->ciudad;
		$cp=$ped->cp;
		$pais=$ped->pais;
		$estado=$ped->estado;
		$telefono=$ped->telefono;
		$gmonto=$ped->monto;
		$genvio=$ped->envio;
		$gtotal=$ped->total;
		$estatus=$ped->estatus;
		$pago=$ped->pago;
		$error=0;
	}


?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Cart</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="OneTech shop project">
<meta name="viewport" content="width=device-width, initial-scale=1">


<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>

<meta name="viewport" content="width=device-width, initial-scale=1"> <!-- Ensures optimal rendering on mobile devices. -->
<meta http-equiv="X-UA-Compatible" content="IE=edge" /> <!-- Optimal Internet Explorer compatibility -->


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

				<?php
				if($error=0){
				?>

				<h3 class='text-center'>Pedido</h3>
				<div class="row">
					<div class="col-2">
						<label class='text-center'>Pedido</label>
						<input type="text" class="form-control form-control-sm" id="pedido" name='pedido' placeholder="Pedido" value="<?php echo $idpedido; ?>" readonly>
					</div>
					<div class="col-3">
						<label class='text-center'>Estatus</label>
						<input type="text" class="form-control form-control-sm" id="estatus" name='estatus' placeholder="Estatus" value="<?php echo $estatus; ?>" readonly>
					</div>
					<div class="col-3">
						<label class='text-center'>Pago</label>
						<input type="text" class="form-control form-control-sm" id="pago" name='pago' placeholder="Pago" value="<?php echo $pago; ?>" readonly>
					</div>
				</div>

				<div class="row">
					<div class="col-4">
						<label class='text-center'>Nombre</label>
						<input type="text" class="form-control form-control-sm" id="nombre" name='nombre' placeholder="Nombre" value="<?php echo $nombre; ?>" readonly>
					</div>

					<div class="col-4">
						<label class='text-center'>Apellidos</label>
						<input type="text" class="form-control form-control-sm" id="apellido" name='apellido' placeholder="Apellidos" value="<?php echo $apellido; ?>" readonly autocomplete="off">
					</div>

					<div class="col-4">
						<label class='text-center'>Correo</label>
						<input type="email" class="form-control form-control-sm" id="correo" name='correo' placeholder="Correo" value="<?php echo $correo; ?>" readonly>
					</div>

				</div>
				<div class='row'>
					<div class="col-3">
						<label>RFC</label>
						<input type="text" class="form-control form-control-sm" id="rfc" name='rfc' placeholder="RFC" value="<?php echo $rfc; ?>" readonly>
					</div>

					<div class="col-6">
						<label>Uso cfdi</label>
						<input type="text" class="form-control form-control-sm" id="cfdi" name='cfdi' placeholder="Uso cfdi" value="<?php echo $cfdi; ?>" readonly>
					</div>
				</div>

				<div class='row'>
					<div class="col-12">
						<label>Dirección (linea 1)</label>
						<input type="text" class="form-control form-control-sm" id="direccion1" name='direccion1' placeholder="Dirección (linea 1)" value="<?php echo $direccion1; ?>" readonly>
					</div>
					<div class="col-4">
						<label>Entre calles</label>
						<input type="text" class="form-control form-control-sm" id="entrecalles" name='entrecalles' placeholder="Entre calles" value="<?php echo $entrecalles; ?>" readonly>
					</div>
					<div class="col-4">
						<label>Num. Exterior</label>
						<input type="text" class="form-control form-control-sm" id="numero" name='numero' placeholder="Num. Exterior" value="<?php echo $numero; ?>" readonly>
					</div>
					<div class="col-4">
						<label>Colonia</label>
						<input type="text" class="form-control form-control-sm" id="colonia" name='colonia' placeholder="Num. Exterior" value="<?php echo $colonia; ?>" readonly>
					</div>
					<div class="col-4">
						<label>Ciudad</label>
						<input type="text" class="form-control form-control-sm" id="ciudad" name='ciudad' placeholder="Ciudad" value="<?php echo $ciudad; ?>" readonly>
					</div>
					<div class="col-4">
						<label>Código postal</label>
						<input type="text" class="form-control form-control-sm" id="cp" name='cp' placeholder="Código postal" value="<?php echo $cp; ?>" readonly>
					</div>
					<div class="col-4">
						<label>Pais</label>
						<input type="text" class="form-control form-control-sm" id="pais" name='pais' placeholder="Pais" value="<?php echo $pais; ?>" readonly>
					</div>
					<div class="col-4">
						<label>Estado</label>
						<input type="text" class="form-control form-control-sm" id="estado" name='estado' placeholder="Estado" value="<?php echo $estado; ?>" readonly>
					</div>
					<div class="col-4">
						<label>Teléfono</label>
						<input type="text" class="form-control form-control-sm" id="telefono" name='telefono' placeholder="Teléfono" value="<?php echo $telefono; ?>" readonly>
					</div>
				</div>
				<hr>
				<div class="jumbotron">
					<div class='row'>
						<div class='col-6'>
							<b>Descripción</b>
						</div>
						<div class='col-2'>
							<b>Cantidad</b>
						</div>
						<div class='col-2'>
							<b>Precio unitario</b>
						</div>
						<div class='col-2'>
							<b>Total</b>
						</div>
					</div>

					<?php

					///////////////////////////////////
					$total=0;
					$envio=0;
					foreach($datos as $key){

						echo "<div class='row'>";
							echo "<div class='col-6'>";
									echo $key->clave;
									echo "<br>".$key->nombre;
									echo "<br>".$key->modelo;
									echo "<br>".$key->marca;
									echo "<br>".$key->categoria;
									echo "<br>+ Costo envio:";
									echo "<b>".moneda($key->envio)."</b>";
							echo "</div>";

							echo "<div class='col-2 text-center'>";
								echo $key->cantidad;
							echo "</div>";

							echo "<div class='col-2 text-center'>";
								echo moneda($key->precio);
							echo "</div>";

							echo "<div class='col-2 text-center'>";
								echo moneda($key->total);
							echo "</div>";

						echo "</div>";
						echo "<hr>";

					}

					///////////////////////////////////

						echo "<h4>TOTAL DEL CARRITO</h4>";
						echo "<div class='row'>";
							echo "<div class='col-2 offset-8 text-right'>";
								echo "<b>Subtotal</b>";
							echo "</div>";
							echo "<div class='col-2 text-right'>";
								echo moneda($gmonto);
							echo "</div>";
						echo "</div>";

						echo "<div class='row'>";
							echo "<div class='col-2 offset-8 text-right'>";
								echo "<b>Envío</b>";
							echo "</div>";
							echo "<div class='col-2 text-right'>";
								echo moneda($genvio);
							echo "</div>";
						echo "</div>";

						echo "<hr>";
						echo "<div class='row'>";
							echo "<div class='col-2 offset-8 text-right'>";
								echo "<b>Total</b>";
							echo "</div>";
							echo "<div class='col-2 text-right'>";
								echo moneda($gtotal);
							echo "</div>";
						echo "</div>";
					}
					else{
						echo "<h5>No encontrado</h5>";
					}
				?>

				</div>
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
