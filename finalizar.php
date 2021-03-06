<?php
	require_once("control_db.php");
	$db = new Tienda();
	if(!isset($_SESSION['idcliente']) or strlen($_SESSION['idcliente'])==0){
		header('Location: /');
		die();
	}
	$mercado=$db->ajustes_editar();
	$merca=$mercado->mercado_public;
	$carro=$db->carro_list();
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
	$correo=$resp->correo;
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
		<h3 class='text-center'>Pedido</h3>
		<form id='pedido_form' action=''>
		<div class='row'>
			<div class='col-8'>

				<div class="row">
					<div class="col-4">
						<label class='text-center'>Nombre</label>
						<input type="text" class="form-control" id="nombre" name='nombre' placeholder="Nombre" value="<?php echo $nombre; ?>" required
						<?php
							if (strlen($_SESSION['correo'])>0){
								echo "readonly";
							}
						?>
						>
					</div>

					<div class="col-4">
						<label class='text-center'>Apellidos</label>
						<input type="text" class="form-control" id="apellido" name='apellido' placeholder="Apellidos" value="<?php echo $apellido; ?>" required autocomplete="off"
						<?php
							if (strlen($_SESSION['correo'])>0){
								echo "readonly";
							}
						?>
						>
					</div>

					<div class="col-4">
						<label class='text-center'>Correo</label>
						<input type="email" class="form-control" id="correo" name='correo' placeholder="Correo" value="<?php echo $correo; ?>" required
						<?php
							if (strlen($_SESSION['correo'])>0){
								echo "readonly";
							}
						?>
						>
					</div>

					<div class="col-4">
						<label>Teléfono</label>
						<input type="text" class="form-control input-number" id="tele_x" name='tele_x' placeholder="Teléfono" value="<?php echo $telefono; ?>" required
						<?php
							if (strlen($_SESSION['correo'])>0){
								echo "readonly";
							}
						?>
						>
					</div>

					<?php
						if (strlen($_SESSION['correo'])==0){
							echo "<div class='col-4'>";
								echo "<label class='text-center'>Contraseña</label>";
								echo "<input type='password' class='form-control' id='pass' name='pass' placeholder='Contraseña' value='' required pattern='[A-Za-z0-9]{8,15}'>";
								echo "<small>De 8 a 15 caracteres</small>";
							echo "</div>";

							echo "<div class='col-4'>";
								echo "<label class='text-center'>Repetir</label>";
								echo "<input type='password' class='form-control' id='pass2' name='pass2' placeholder='Contraseña' value='' required>";
							echo "</div>";
						}
					?>
					<div class="col-4">
						<label>Requiere factura</label><br>
						<input type="checkbox" id="factura" name='factura' value=1 onclick='factura_act()'>
					</div>

				</div>

				<div class='row' id='factura_div' style='display:none'>
					<div class="col-4">
						<label>RFC</label>
						<input type="text" class="form-control" id="rfc" name='rfc' placeholder="RFC" value="<?php echo $rfc; ?>">
					</div>

				<?php
					echo "<div class='col-8'>";
						echo "<label>Uso CFDI</label>";
						echo "<select id='cfdi' name='cfdi' class='form-control'>";
						$cfdi_obj=$db->cfdi();
						foreach($cfdi_obj as $key){
							echo "<option value='".$key->cfdi."'"; if($cfdi==$key->cfdi){ echo " selected";} echo " >".$key->cfdi."</option>";
						}
						echo "</select>";
					echo "</div>";

					$resp=$db->direcciones();
					/*if(is_array($resp) and strlen($_SESSION['correo'])>0){*/
						echo "<div class='col-12'>";
							echo "<label>Direcciones de facturación disponibles</label>";
							echo "<select id='dir_factfin' name='dir_factfin' class='form-control' onchange='select_factdir()'>";
							echo "<option value='0'>Utilizar la misma dirección de envío</option>";
							foreach($resp as $key){
								echo "<option value='".$key['iddireccion']."'>".$key['direccion1']."</option>";
							}
							echo "<option value='nueva'>* Nueva dirección</option>";
							echo "</select>";
						echo "</div>";
					/*}*/
				?>
				</div>

				<div class='row' id='dirfactura_div' style='display:none'>
					<br>
					<h4 class='text-center'>Dirección de facturación</h4>

 						<div class="col-12">
 							<label>Dirección</label>
 							<input type="text" class="form-control" id="fact_direccion1" name='fact_direccion1' placeholder="Dirección" value="<?php echo $direccion1; ?>">
 						</div>
 						<div class="col-4">
 							<label>Entre calles</label>
 							<input type="text" class="form-control" id="fact_entrecalles" name='fact_entrecalles' placeholder="Entre calles" value="<?php echo $entrecalles; ?>" >
 						</div>
 						<div class="col-4">
 							<label>Num. Exterior</label>
 							<input type="text" class="form-control" id="fact_numero" name='fact_numero' placeholder="Num. Exterior" value="<?php echo $numero; ?>" >
 						</div>
 						<div class="col-4">
 							<label>Colonia</label>
 							<input type="text" class="form-control" id="fact_colonia" name='fact_colonia' placeholder="Col" value="<?php echo $colonia; ?>" >
 						</div>
 						<div class="col-4">
 							<label>Ciudad</label>
 							<input type="text" class="form-control" id="fact_ciudad" name='fact_ciudad' placeholder="Ciudad" value="<?php echo $ciudad; ?>">
 						</div>
 						<div class="col-4">
 							<label>Código postal</label>
 							<input type="text" class="form-control input-number" id="fact_cp" name='fact_cp' placeholder="Código postal" value="<?php echo $cp; ?>">
 						</div>
 						<div class="col-4">
 							<label>Pais</label>
 							<input type="text" class="form-control" id="fact_pais" name='fact_pais' placeholder="Pais" value="<?php echo $pais; ?>">
 						</div>
 						<div class="col-4">
 							<label>Estado</label>
 							<input type="text" class="form-control" id="fact_estado" name='fact_estado' placeholder="Estado" value="<?php echo $estado; ?>">
 						</div>
				</div>

				<div class='row'>
					<br>
					<h4 class='text-center'>Dirección de envio</h4>
					<?php
						$resp=$db->direcciones();
						if(is_array($resp) and strlen($_SESSION['correo'])>0){
							echo "<div class='col-12'>";
								echo "<label>Direcciones disponibles</label>";
								echo "<select id='dir_fin' name='dir_fin' class='form-control' onchange='select_dir()'>";
								echo "<option value='0'>utilizar principal</option>";
								foreach($resp as $key){
									echo "<option value='".$key['iddireccion']."'>".$key['direccion1']."</option>";
								}
								echo "<option value='nueva'>* Nueva dirección</option>";
								echo "</select>";
							echo "</div>";
						}
					?>
					<div class="col-12">
						<label>Dirección</label>
						<input type="text" class="form-control" id="direccion1" name='direccion1' placeholder="Dirección" value="<?php echo $direccion1; ?>" required>
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
						<input type="text" class="form-control" id="colonia" name='colonia' placeholder="Col" value="<?php echo $colonia; ?>" >
					</div>
					<div class="col-4">
						<label>Ciudad</label>
						<input type="text" class="form-control" id="ciudad" name='ciudad' placeholder="Ciudad" value="<?php echo $ciudad; ?>" required>
					</div>
					<div class="col-4">
						<label>Código postal</label>
						<input type="text" class="form-control input-number" id="cp" name='cp' placeholder="Código postal" value="<?php echo $cp; ?>" required>
					</div>
					<div class="col-4">
						<label>Pais</label>
						<input type="text" class="form-control" id="pais" name='pais' placeholder="Pais" value="<?php echo $pais; ?>" required>
					</div>
					<div class="col-4">
						<label>Estado</label>
						<input type="text" class="form-control" id="estado" name='estado' placeholder="Estado" value="<?php echo $estado; ?>" required>
					</div>
					<div class="col-12">
						<label>Notas</label>
						<input type="text" class="form-control" id="notas" name='notas' placeholder="Notas" value="" >
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

						if($key->envio_tipo==0){
							$enviof=($db->egeneral);
						}
						if($key->envio_tipo==1){
							$enviof=($key->envio_costo);
						}

						echo "<div class='row'>";
							echo "<div class='col-12'>";
									echo $key->nombre;
							echo "</div>";

							echo "<div class='col-12'>";
								echo "<label>Costo envio: ";
									echo moneda($enviof*$key->cantidad);
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
								echo moneda($preciof);
							echo "</div>";
							echo "<div class='col-4 text-right'>";
								$p_final=$key->cantidad*($preciof+$enviof);
								echo moneda($p_final);
							echo "</div>";
						echo "</div>";
						echo "<hr>";

						$total+=$p_final;
					}

					///////////////////////////////////s
						echo "<h4>TOTAL DEL CARRITO</h4>";
						echo "<div class='row'>";
							echo "<div class='col-6'>";
								echo "Subtotal";
							echo "</div>";
							echo "<div class='col-6 text-right'>";
								echo moneda($total);
							echo "</div>";
						echo "</div>";


						$iva=$total*.16;
						echo "<div class='row'>";
							echo "<div class='col-6'>";
								echo "IVA";
							echo "</div>";
							echo "<div class='col-6 text-right'>";
								echo moneda($iva);
							echo "</div>";
						echo "</div>";


						$gtotal=$total*1.16;
						echo "<hr>";
						echo "<div class='row'>";
							echo "<div class='col-6'>";
								echo "Total";
							echo "</div>";
							echo "<div class='col-6 text-right'>";
								echo moneda($gtotal);
							echo "</div>";
						echo "</div>";

						echo "<button type='submit' class='btn btn-warning btn-block'><i class='fas fa-cart-plus'></i>Finalizar pedido</button>";
						echo "<input type='checkbox' id='terminos' name='terminos' value=1 required checked> Acepto los <a href='/terminos-condiciones.php' target='blanck_'>Términos y condiciones</a>";
					?>
				</div>
			</div>

		</div>
		</form>
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
