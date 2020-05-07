<?php
	require_once("control_db.php");
	$db = new Tienda();

	$idpedido=$_REQUEST['id'];

	if(!isset($_REQUEST['id']) or !isset($_SESSION['idcliente']) or strlen($_REQUEST['id'])==0){
		header('Location: /');
		die();
	}
	$db->limpia_carrito();

	$mercado=$db->ajustes_editar();
	$merca_public=$mercado->mercado_public;
	$mercado_token=$mercado->mercado_token;
	$paypal_client=$mercado->paypal_client;

	$ped=$db->pedido_ver($idpedido);
	if(!is_object($ped)){
		header('Location: /');
		die();
	}
	$datos=$db->datos_pedido($idpedido);
	$cupones=$db->pedido_cupones($idpedido);

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
	$total=$ped->total;
	$factura=$ped->factura;

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
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
<meta name="viewport" content="width=device-width, initial-scale=1"> <!-- Ensures optimal rendering on mobile devices. -->
<meta http-equiv="X-UA-Compatible" content="IE=edge" /> <!-- Optimal Internet Explorer compatibility -->

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
	<div class='container pagm'>
		<h3 class='text-center'>Finalizar pedido</h3>

		<div class='row'>
			<div class='col-8'>

				<div class="row">
					<div class="col-4">
						<label class='text-center'>Nombre</label>
						<input type="text" class="form-control" id="nombre" name='nombre' placeholder="Nombre" value="<?php echo $nombre; ?>" readonly>
					</div>

					<div class="col-4">
						<label class='text-center'>Apellidos</label>
						<input type="text" class="form-control" id="apellido" name='apellido' placeholder="Apellidos" value="<?php echo $apellido; ?>" readonly autocomplete="off">
					</div>

					<div class="col-4">
						<label class='text-center'>Correo</label>
						<input type="email" class="form-control" id="correo" name='correo' placeholder="Correo" value="<?php echo $correo; ?>" readonly>
					</div>
				</div>
				<?php

					if($factura==1){
						echo "<div class='row'>";
							echo "<div class='col-3'>";
								echo "<label>RFC</label>";
								echo "<input type='text' class='form-control' id='rfc' name='rfc' placeholder='RFC' value='<?php echo $rfc; ?>' readonly>";
							echo "</div>";

							echo "<div class='col-6'>";
								echo "<label>Uso cfdi</label>";
								echo "<input type='text' class='form-control' id='cfdi' name='cfdi' placeholder='Uso cfdi' value='<?php echo $cfdi; ?>' readonly>";
							echo "</div>";
						echo "</div>";
					}

				?>

				<div class='row'>
					<div class="col-12">
						<label>Dirección (linea 1)</label>
						<input type="text" class="form-control" id="direccion1" name='direccion1' placeholder="Dirección (linea 1)" value="<?php echo $direccion1; ?>" readonly>
					</div>
					<div class="col-4">
						<label>Entre calles</label>
						<input type="text" class="form-control" id="entrecalles" name='entrecalles' placeholder="Entre calles" value="<?php echo $entrecalles; ?>" readonly>
					</div>
					<div class="col-4">
						<label>Num. Exterior</label>
						<input type="text" class="form-control" id="numero" name='numero' placeholder="Num. Exterior" value="<?php echo $numero; ?>" readonly>
					</div>
					<div class="col-4">
						<label>Colonia</label>
						<input type="text" class="form-control" id="colonia" name='colonia' placeholder="Colonia" value="<?php echo $colonia; ?>" readonly>
					</div>
					<div class="col-4">
						<label>Ciudad</label>
						<input type="text" class="form-control" id="ciudad" name='ciudad' placeholder="Ciudad" value="<?php echo $ciudad; ?>" readonly>
					</div>
					<div class="col-4">
						<label>Código postal</label>
						<input type="text" class="form-control" id="cp" name='cp' placeholder="Código postal" value="<?php echo $cp; ?>" readonly>
					</div>
					<div class="col-4">
						<label>Pais</label>
						<input type="text" class="form-control" id="pais" name='pais' placeholder="Pais" value="<?php echo $pais; ?>" readonly>
					</div>
					<div class="col-4">
						<label>Estado</label>
						<input type="text" class="form-control" id="estado" name='estado' placeholder="Estado" value="<?php echo $estado; ?>" readonly>
					</div>
					<div class="col-4">
						<label>Teléfono</label>
						<input type="text" class="form-control" id="telefono" name='telefono' placeholder="Teléfono" value="<?php echo $telefono; ?>" readonly>
					</div>
				</div>
				<hr>
				<div class='row'>
					<div class="col-12">
						<h4 class='text-center'>Cupones</h4>
					</div>
					<div class="col-6">
						<div class="input-group mb-3">
						  <input type="text" class="form-control" id='cupon' name='cupon' placeholder="Agregar cupon" aria-label="Recipient's username" aria-describedby="basic-addon2">
						  <div class="input-group-append">
						    <button class="btn btn-outline-secondary" type="button" onclick='cupon_agrega(<?php echo $idpedido; ?>)'>Agregar</button>
						  </div>
						</div>
					</div>
				</div>


			</div>
			<div class='col-4'>
				<div class="jumbotron">
					<?php
					echo "<div class='row'>";
						echo "<div class='col-12 text-center'>";
							echo "<h3>Pedido NO: ".$idpedido."</h3>";
						echo "</div>";
					echo "</div>";
					///////////////////////////////////
					$total=0;
					$envio=0;
					foreach($datos as $key){
						$preciof=0;
						$enviof=0;

						echo "<div class='row'>";
							echo "<div class='col-12'>";
									echo $key->nombre;
							echo "</div>";

							echo "<div class='col-12'>";
								echo "<label>Costo envio: ";
								echo moneda($key->envio*$key->cantidad);
								$envio+=$key->envio*$key->cantidad;
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
								echo $key->cantidad;
							echo "</div>";
							echo "<div class='col-4 text-right'>";
								echo moneda($key->precio);
							echo "</div>";
							echo "<div class='col-4 text-right'>";
								echo moneda($key->total);
							echo "</div>";
						echo "</div>";
						echo "<hr>";

						$total+=$key->total;
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

						echo "<hr>";
						echo "<div class='row' style='font-size:14px;'>";
							echo "<div class='col-6'>";
								echo "<b>Total</b>";
							echo "</div>";
							echo "<div class='col-6 text-right'>";
								echo moneda($total);
							echo "</div>";
						echo "</div>";

						if(is_array($cupones)){
							echo "<h4>Cupones</h4>";
							foreach($cupones as $keyc){
								echo "<div class='row'>";
									echo "<div class='col-2'>";
										echo "<a href='#' onclick='elimina_cupon(".$keyc->id.", $idpedido)'><i class='far fa-times-circle'></i></a>";
									echo "</div>";
									echo "<div class='col-6'>";
										echo $keyc->codigo;
										echo "<br>";
										echo $keyc->descripcion;
									echo "</div>";

									echo "<div class='col-4 text-right'>";

										/*
										<option value='porcentaje' <?php if ($tipo=="porcentaje"){ echo " selected"; } ?> >Descuento en porcentaje</option>
										<option value='carrito' <?php if ($tipo=="carrito"){ echo " selected"; } ?> >Descuento fijo en el carrito</option>
										<option value='producto' <?php if ($tipo=="producto"){ echo " selected"; } ?> >Descuento fijo de productos</option>
										*/

										if($keyc->tipo=='porcentaje'){
											echo $keyc->descuento."%";
											$monto=($total*$keyc->descuento)/100;
											echo "<br>- ".moneda($monto);
											$total=$total-$monto;
										}

										if($keyc->tipo=='carrito'){
											echo "<br>- ".moneda($keyc->descuento);
											$total=$total-$keyc->descuento;
										}

										if($keyc->envio=='si'){
											$total=$total-$envio;
											echo "<br>Envio: -".$envio;
										}

									echo "</div>";
								echo "</div>";

								echo "<div class='row'>";
									echo "<div class='col-6'>";
										echo "<h4><b>Total:</b></h4>";
									echo "</div>";

									echo "<div class='col-6 text-right'>";
										echo "<h4><b>".moneda($total)."</b></h4>";
									echo "</div>";
								echo "</div>";
							}
						}



					?>
					<?php
						////////////////////////
						require __DIR__ .  '/vendor/autoload.php';

						// Agrega credenciales
						MercadoPago\SDK::setAccessToken($mercado_token);

						// Crea un objeto de preferencia
						$preference = new MercadoPago\Preference();
						$preference->external_reference="$idpedido";

						$preference->back_urls = array(
						    "success" => "https://www.tic-shop.com.mx/pago_fin.php?idpedido=$idpedido",
						    "failure" => "https://www.tic-shop.com.mx/pago/$idpedido",
						    "pending" => "https://www.tic-shop.com.mx/pago_fin.php?idpedido=$idpedido&procesando=1"
						);
						$preference->auto_return = "approved";

						// Crea un ítem en la preferencia
						$item = new MercadoPago\Item();
						$item->title = 'TIC-SHOP';
						$item->quantity = 1;
						$item->unit_price = round($total,2);
						$preference->items = array($item);
						$preference->save();
					?>

					<form action="https://www.tic-shop.com.mx/pago_fin.php?idpedido=<?php echo $idpedido; ?>" method="POST">
					  <script
					   src="https://www.mercadopago.com.mx/integrations/v1/web-payment-checkout.js"
					   data-preference-id="<?php echo $preference->id; ?>">
					  </script>
					</form>

					<script
					   src="https://www.paypal.com/sdk/js?client-id=<?php echo $paypal_client; ?>&currency=MXN" >
					 </script>

					 <div id="paypal-button-container"></div>

					 <script>
					    paypal.Buttons({
					    createOrder: function(data, actions) {
					      // This function sets up the details of the transaction, including the amount and line item details.
					      return actions.order.create({
					        purchase_units: [{
					          amount: {
					            value: '<?php echo round($total,2); ?>'
					          }
					        }]
					      });
					    },
					    onApprove: function(data, actions) {
					      Swal.fire({
					          type: 'success',
					          title: 'no cierre la ventana, finalizando pago',
					          showConfirmButton: false
					      });
					      return actions.order.capture().then(function(details) {
									$.ajax({
								    url: "/paypal-transaction-complete.php",
								    type: "POST",
										data: {
										 "id":details.id,
										 "mail":details.payer.email_address,
										 "estatus":details.status,
										 "idx":<?php echo $idpedido; ?>
									 	},
								    success: function( response ) {
											Swal.fire({
							            type: 'success',
							            title: "Se proceso correctamente",
							            showConfirmButton: false,
							            timer: 1000
							        });
											window.location.href="https://www.tic-shop.com.mx/estado_pedido.php?idpedido="+<?php echo $idpedido; ?>;
								    }
								  });
					        //alert('Transaction completed by ' + details.payer.name.given_name);
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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
<script src="/sagyc.js"></script>
</body>

</html>
