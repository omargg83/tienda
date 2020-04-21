<?php
		require_once("control_db.php");
		$db = new Tienda();

		$idpedido=$_REQUEST['idpedido'];
		$payment_id=$_REQUEST['payment_id'];
		$payment_status=$_REQUEST['payment_status'];

		$ped=$db->pedido_ver($idpedido);

		$estatus="";
		$rechazado=0;
	  if($payment_status=="approved"){
			$estatus="PROCESANDO";
			$rechazado=0;
		}
	  if($payment_status=="in_process"){
			$estatus="PROCESANDO PAGO";
			$rechazado=0;
		}
	  if($payment_status=="pending"){
			$estatus="PROCESANDO PAGO PENDIENTE";
			$rechazado=0;
		}
	  if($payment_status=="rejected"){
			$estatus="EN ESPERA";
			$rechazado=1;
		}
		$arreglo =array();
		$arreglo+= array('estatus'=>$estatus);
		$arreglo+= array('pago'=>"Mercado Pago");
		$arreglo+= array('idpago'=>$payment_id);
		$arreglo+= array('estado_pago'=>$payment_status);
		$x=$db->update('pedidos',array('id'=>$idpedido), $arreglo);
		$ped=json_decode($x);
		$id=$ped->id;
		if($ped->error==0){

		}


		$ped=$db->pedido_ver($idpedido);
		$datos=$db->datos_pedido($idpedido);
		$cupones=$db->pedido_cupones($id);

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
		$idpago=$ped->idpago;

		if($payment_status=="approved" or $payment_status=="in_process"){
			$resp = crearNuevoToken();
			$tok=$resp->token;
			echo $tok;

			$envio=array();
			$contar=0;

			$envio[0]=array(
				'nombre' => $nombre. " ".$apellido,
				'direccion' => $direccion1,
				'entreCalles' => $entrecalles,
				'noExterior' => $numero,
				'colonia' => $colonia,
				'estado' => $estado,
				'ciudad' => $ciudad,
				'codigoPostal' => $cp,
				'telefono' => $telefono
			);

			$ct_producto=0;
			$contar=0;
			foreach($datos as $key){
				if($key->tipo=="CT"){
					$ct_producto++;
					$producto[$contar]=array(
						'cantidad' => (int)$key->cantidad,
						'clave' => $key->clave,
						'precio' => (int)$key->precio,
						'moneda' => "MXN"
					);
					$contar++;
				}
			}

			$arreglo=array(
				'idPedido' => (int)$idpedido,
				'almacen' => "31A",
				'tipoPago' => "03",
				'envio' => json_decode(json_encode($envio)),
				'producto' => json_decode(json_encode($producto)),
			);
			$json = json_encode($arreglo);

			echo "<pre>";
				echo print_r($json);
			echo "</pre>";

			$resp =servicioApi('POST','pedido',$json,$tok);
			echo "<pre>";
				echo var_dump($resp);
			echo "</pre>";
		}



		////////$ct_producto;


		/////////////////////////////////////////////Correo
		if($rechazado==0){
				$texto="<h3>TIC-SHOP</h3><br>
				<h3><b>Pedido</b></h3>

				<div class='row'>
					<div class='col-2'>
						<label>Pedido #: $idpedido</label>
					</div>
					<div class='col-3'>
						<label>Estatus: $estatus</label>
					</div>
					<div class='col-3'>
						<label>Pago: $pago</label>
					</div>
					<div class='col-3'>
						<label>Pago #: $idpago</label>
					</div>
					<div class='col-4'>
						<label>Nombre: $nombre $apellido</label>
					</div>
					<div class='col-4'>
						<label>Correo: $correo</label>
					</div>
				</div>
				<hr>
				<div class='row'>
					<div class='col-3'>
						<label>RFC: $rfc</label>
					</div>
					<div class='col-3'>
						<label>Uso CFDI: $cfdi</label>
					</div>

					<div class='col-12'>
						<label>Dirección: $direccion1</label>
					</div>
					<div class='col-4'>
						<label>Entre calles: $entrecalles</label>
					</div>
					<div class='col-4'>
						<label>Num. Exterior: $numero</label>
					</div>
					<div class='col-4'>
						<label>Colonia: $colonia</label>
					</div>
					<div class='col-12'>
						<label>Ciudad: $ciudad</label>
					</div>
					<div class='col-12'>
						<label>Código postal: $cp</label>
					</div>
					<div class='col-12'>
						<label>Pais: $pais</label>
					</div>
					<div class='col-12'>
						<label>Estado: $estado</label>
					</div>
					<div class='col-12'>
						<label>Teléfono: $telefono</label>
					</div>
				</div>
				<hr>
				<table>
					<tr>
						<td>
							<b>Descripción</b>
						</td>
						<td>
							<b>Cantidad</b>
						</td>
						<td>
							<b>Precio unitario</b>
						</td>
						<td>
							<b>Total</b>
						</td>
					</tr>
				";

					///////////////////////////////////
					$total=0;
					$envio=0;
					foreach($datos as $key){
						$texto.="<tr>";
							$texto.= "<td>";
									$texto.= $key->clave;
									$texto.= "<br>".$key->nombre;
									$texto.= "<br>".$key->modelo;
									$texto.= "<br>".$key->marca;
									$texto.= "<br>".$key->categoria;
									$texto.= "<br>+ Costo envio:";
									$texto.= "<b>".moneda($key->envio)."</b>";
							$texto.= "</td>";

							$texto.= "<td>";
								$texto.= $key->cantidad;
							$texto.= "</td>";

							$texto.= "<td>";
								$texto.= moneda($key->precio);
							$texto.= "</td>";

							$texto.= "<td'>";
								$texto.= moneda($key->total);
							$texto.= "</td>";

						$texto.= "</tr>";
					}
					$texto.="</table>";
					///////////////////////////////////

						$texto.= "<h4>TOTAL DE LA COMPRA</h4>";
						$texto.= "<div class='row'>";
							$texto.= "<div class='col-2 offset-8 text-right'>";
								$texto.= "<b>Subtotal</b>";
							$texto.= "</div>";
							$texto.= "<div class='col-2 text-right'>";
								$texto.= moneda($gmonto);
							$texto.= "</div>";
						$texto.= "</div>";

						$texto.= "<div class='row'>";
							$texto.= "<div class='col-2 offset-8 text-right'>";
								$texto.= "<b>Envío</b>";
							$texto.= "</div>";
							$texto.= "<div class='col-2 text-right'>";
								$texto.= moneda($genvio);
							$texto.= "</div>";
						$texto.= "</div>";

						$texto.= "<div class='row'>";
							$texto.= "<div class='col-2 offset-8 text-right'>";
								$texto.= "<b>Total</b>";
							$texto.= "</div>";
							$texto.= "<div class='col-2 text-right'>";
								$texto.= moneda($gtotal);
							$texto.= "</div>";
						$texto.= "</div>";

						if(is_array($cupones)){
							$texto.= "<h4>Cupones</h4>";
							foreach($cupones as $keyc){
								$texto.= "<div class='row'>";
									$texto.= "<div class='col-10'>";
										$texto.= $keyc->codigo;
										$texto.= "<br>";
										$texto.= $keyc->descripcion;
									$texto.= "</div>";
									$texto.= "<div class='col-2 text-right'>";

										if($keyc->tipo=='porcentaje'){
											$texto.= $keyc->descuento."%";
											$monto=($gtotal*$keyc->descuento)/100;
											$texto.= "<br>- ".moneda($monto);
											$gtotal=$gtotal-$monto;
										}

										if($keyc->tipo=='carrito'){
											$texto.= "<br>- ".moneda($keyc->descuento);
											$gtotal=$gtotal-$keyc->descuento;
										}

										if($keyc->envio=='si'){
											$gtotal=$gtotal-$envio;
											$texto.= "<br>Envio: -".$envio;
										}

									$texto.= "</div>";
								$texto.= "</div>";

								$texto.= "<div class='row'>";
									$texto.= "<div class='col-6'>";
										$texto.= "<h4><b>Total:</b></h4>";
									$texto.= "</div>";

									$texto.= "<div class='col-6 text-right'>";
										$texto.= "<h4><b>".moneda($gtotal)."</b></h4>";
									$texto.= "</div>";
								$texto.= "</div>";
							}
						}

				$texto.="</div>";
			$texto.="</div>";

			$asunto="Compra Exitosa";
		}
		if($rechazado==1){
			$texto="<h3>TIC-SHOP</h3>
			<h3 class='text-center'>Pedido</h3>
			<div class='row'>
				<div class='col-2'>
					<label>Pedido #: $idpedido</label>
				</div>
				<div class='col-3'>
					<label>Estatus: $estatus</label>
				</div>
				<div class='col-3'>
					<label>Pago: $pago</label>
				</div>
				<div class='col-4'>
					<label>Nombre: $nombre $apellido</label>
				</div>
				<div class='col-4'>
					<label>Correo: $correo</label>
				</div>
			</div>
			<hr>";
			$texto.="<br><br>PAGO RECHAZADO";
			$asunto="Se rechazo el pago";
		}

		//$db->correo($correo, $texto, $asunto);
		////////////////////////////////////////////////////

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
						<a href='clientes.php' class="btn btn-primary btn-lg btn-block">Pedidos</a>
						<a href='cli_direcciones.php' class="btn btn-primary btn-lg btn-block">Direcciones</a>
						<a href='cli_datos.php' class="btn btn-primary btn-lg btn-block">Mis datos</a>
						<a href='#' class="btn btn-primary btn-lg btn-block" onclick='salir()'>Salir</a>
					</div>
				</div>
				<div class='col-9'>
					<h3 class='text-center'><?php echo "#".$id; ?> Pedido</h3>
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
						<div class="col-3">
							<label class='text-center'>Pago #</label>
							<input type="text" class="form-control form-control-sm" id="idpago" name='idpago' placeholder="Pago" value="<?php echo $idpago; ?>" readonly>
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
							<input type="text" class="form-control form-control-sm" id="entrecalles" name='entrecalles' placeholder="Entrecalles" value="<?php echo $entrecalles; ?>" readonly>
						</div>
						<div class="col-4">
							<label>Num. Exterior</label>
							<input type="text" class="form-control form-control-sm" id="numero" name='numero' placeholder="Num. Exterior" value="<?php echo $numero; ?>" readonly>
						</div>
						<div class="col-4">
							<label>Colonia</label>
							<input type="text" class="form-control form-control-sm" id="colonia" name='colonia' placeholder="Colonia" value="<?php echo $colonia; ?>" readonly>
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
							if(is_array($cupones)){
								echo "<h4>Cupones</h4>";
								foreach($cupones as $keyc){
									echo "<div class='row'>";
										echo "<div class='col-10'>";
											echo $keyc->codigo;
											echo "<br>";
											echo $keyc->descripcion;
										echo "</div>";
										echo "<div class='col-2 text-right'>";

											/*
											<option value='porcentaje' <?php if ($tipo=="porcentaje"){ echo " selected"; } ?> >Descuento en porcentaje</option>
											<option value='carrito' <?php if ($tipo=="carrito"){ echo " selected"; } ?> >Descuento fijo en el carrito</option>
											<option value='producto' <?php if ($tipo=="producto"){ echo " selected"; } ?> >Descuento fijo de productos</option>
											*/

											if($keyc->tipo=='porcentaje'){
												echo $keyc->descuento."%";
												$monto=($gtotal*$keyc->descuento)/100;
												echo "<br>- ".moneda($monto);
												$gtotal=$gtotal-$monto;
											}

											if($keyc->tipo=='carrito'){
												echo "<br>- ".moneda($keyc->descuento);
												$gtotal=$gtotal-$keyc->descuento;
											}

											if($keyc->envio=='si'){
												$gtotal=$gtotal-$envio;
												echo "<br>Envio: -".$envio;
											}

										echo "</div>";
									echo "</div>";

									echo "<div class='row'>";
										echo "<div class='col-6'>";
											echo "<h4><b>Total:</b></h4>";
										echo "</div>";

										echo "<div class='col-6 text-right'>";
											echo "<h4><b>".moneda($gtotal)."</b></h4>";
										echo "</div>";
									echo "</div>";
								}
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
