<?php
	require_once("control_db.php");
	$db = new Tienda();
	$idpedido=$_REQUEST['idpedido'];

	$mercado=$db->ajustes_editar();
	$merca=$mercado->mercado_public;

	$ped=$db->pedido_ver($idpedido);
	$datos=$db->datos_pedido($idpedido);

	$nombre=$ped->nombre;
	$apellido=$ped->apellido;
	$correo=$ped->correo;
	$rfc=$ped->rfc;
	$cfdi=$ped->cfdi;
	$direccion1=$ped->direccion1;
	$direccion2=$ped->direccion2;
	$ciudad=$ped->ciudad;
	$cp=$ped->cp;
	$pais=$ped->pais;
	$estado=$ped->estado;
	$telefono=$ped->telefono;

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
		<h3 class='text-center'>Carrito de compras</h3>

		<div class='row'>
			<div class='col-8'>

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
					<div class="col-12">
						<label>Dirección (linea 2)</label>
						<input type="text" class="form-control" id="direccion2" name='direccion2' placeholder="Dirección (linea 2)" value="<?php echo $direccion2; ?>" >
					</div>
					<div class="col-4">
						<label>Ciudad</label>
						<input type="text" class="form-control" id="ciudad" name='ciudad' placeholder="Ciudad" value="<?php echo $ciudad; ?>" >
					</div>
					<div class="col-4">
						<label>Código postal</label>
						<input type="text" class="form-control" id="cp" name='cp' placeholder="Código postal" value="<?php echo $cp; ?>" >
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
						<input type="text" class="form-control" id="telefono" name='telefono' placeholder="Teléfono" value="<?php echo $telefono; ?>" >
					</div>
				</div>


			</div>
			<div class='col-4'>
				<div class="jumbotron">
					<?php
					///////////////////////////////////
					$total=0;
					$envio=0;
					foreach($carro as $key){
						$preciof=0;
						$enviof=0;
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

						echo "<div class='row'>";
							echo "<div class='col-12'>";
									echo $key->nombre;
							echo "</div>";

							echo "<div class='col-12'>";
								echo "<label>Costo envio: ";
								if($key->envio_tipo==0){
									echo moneda($db->egeneral);
									$envio+=$db->egeneral;
								}
								if($key->envio_tipo==1){
									echo moneda($key->envio_costo);
									$envio+=$key->envio_costo;
								}
								echo "</label>";
							echo "</div>";
						echo "</div>";


						echo "<div class='row'>";
							echo "<div class='col-4 text-center'>";
								echo "<b>Cantidad</b>";
							echo "</div>";
							echo "<div class='col-4 text-center'>";
								echo "<b>Precio unitario</b>";
							echo "</div>";
							echo "<div class='col-4 text-center'>";
								echo "<b>Total</b>";
							echo "</div>";
						echo "</div>";

						echo "<div class='row'>";
							echo "<div class='col-4 text-center'>";
								echo "1";
							echo "</div>";
							echo "<div class='col-4 text-right'>";
								echo moneda($preciof);
							echo "</div>";
							echo "<div class='col-4 text-right'>";
								echo moneda($preciof);
							echo "</div>";
						echo "</div>";
						echo "<hr>";

						$total+=$preciof;
					}

					///////////////////////////////////


						echo "<h4>TOTAL DEL CARRITO</h4>";
						echo "<div class='row'>";
							echo "<div class='col-6'>";
								echo "Subtotal";
							echo "</div>";
							echo "<div class='col-6 text-right'>";
								echo moneda($total);
							echo "</div>";
						echo "</div>";

						echo "<div class='row'>";
							echo "<div class='col-6'>";
								echo "Envío";
							echo "</div>";
							echo "<div class='col-6 text-right'>";
								echo moneda($envio);
							echo "</div>";
						echo "</div>";

						$gtotal=$total+$envio;
						echo "<hr>";
						echo "<div class='row'>";
							echo "<div class='col-6'>";
								echo "Total";
							echo "</div>";
							echo "<div class='col-6 text-right'>";
								echo moneda($gtotal);
							echo "</div>";
						echo "</div>";
					?>
					<form action="http://tic-shop.com.mx/tienda/web/procesar-pago.php" method="POST">
					  <script
					    src="https://www.mercadopago.com.mx/integrations/v1/web-tokenize-checkout.js"
					    data-public-key="<?php echo $merca; ?>"
					    data-transaction-amount="<?php echo $gtotal; ?>">
					  </script>
					</form>

					<script src="https://www.paypal.com/sdk/js?client-id=sb&currency=MXN"></script>
					<script>paypal.Buttons().render('body');</script>


					<script
						src="https://www.paypal.com/sdk/js?client-id=sb-zugkk1390830@business.example.com"> // Required. Replace SB_CLIENT_ID with your sandbox client ID.
					</script>

					<div id="paypal-button-container"></div>

					<script>
					  paypal.Buttons({
					    createOrder: function(data, actions) {
					      // This function sets up the details of the transaction, including the amount and line item details.
					      return actions.order.create({
					        purchase_units: [{
					          amount: {
					            value: '<?php echo $gtotal; ?>'
					          }
					        }]
					      });
					    },
					    onApprove: function(data, actions) {
								// Authorize the transaction
								actions.order.authorize().then(function(authorization) {

									// Get the authorization id
									var authorizationID = authorization.purchase_units[0]
										.payments.authorizations[0].id

									// Call your server to validate and capture the transaction
									return fetch('/paypal-transaction-complete.php', {
										method: 'post',
										headers: {
											'content-type': 'application/json'
										},
										body: JSON.stringify({
											orderID: data.orderID,
											authorizationID: authorizationID
										})
									});
								});
					    }
					  }).render('#paypal-button-container');
					  //This function displays Smart Payment Buttons on your web page.

					</script>


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

<script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
<script src="sagyc.js"></script>
</body>

</html>
