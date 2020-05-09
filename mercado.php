<?php
	require_once("control_db.php");
	$db = new Tienda();

	$mercado=$db->ajustes_editar();
	$mercado_token=$mercado->mercado_token;

	$input = @file_get_contents("php://input");
	$texto=$input;
	$event_json = json_decode($input);
	$id = $event_json->data->id;

	require __DIR__ .  '/vendor/autoload.php';
	MercadoPago\SDK::setAccessToken($mercado_token);
  $payment = MercadoPago\Payment::find_by_id($id);

	$idpedido=$payment->external_reference;
	$monto_pago=$payment->transaction_amount;
	$estado_pago=$payment->status;

	$sql="select * from pedidos where id='$idpedido'";
	$sth = $db->dbh->prepare($sql);
	$sth->execute();
	$pedido=$sth->fetch(PDO::FETCH_OBJ);

	if($estado_pago=="approved"){

		$sql="update pedidos set estado_pago='$estado_pago', confirmacion='$estado_pago', idpago='$id', pagador='ipn', pago='Mercado Pago', estatus='PROCESANDO' where id='$idpedido'";
		$sth = $db->dbh->prepare($sql);
		$sth->execute();


		$ped=$db->pedido_ver($idpedido);
		$cupones=$db->pedido_cupones($idpedido);
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
		$idpago=$ped->idpago;


		$texto="$id $idpedido $estado_pago";
		$sql="insert into new_table (log) values (:log)";
		$sth = $db->dbh->prepare($sql);
		$sth->bindValue(':log',$texto);
		$sth->execute();

		/////////////////////////////////////////////Correo

				$texto="<h3>TIC-SHOP</h3><br>
				<h3><b><center>Pedido confirmado</center></b></h3>

				<table style='width:100%'>
					<tr>
					<td>
						<b>Pedido #:</b><br> $idpedido
					</td>
					<td>
						<b>Estatus:</b><br> $estatus
					</td>
					<td>
						<b>Pago:</b><br> $pago
					</td>
					<td>
						<b>Pago #:</b><br> $idpago
					</td>
					<td>
						<b>Nombre:</b><br> $nombre $apellido</b>
					</td>
					<td>
						<b>Correo:</b><br> $correo
					</td>
				</tr>
				</table>
				<br>
				<h3><center>Información de envío</center></h3>
				<table style='width:100%'>
				<tr>
					<td>
						<b>RFC:</b><br> $rfc
					</td>
					<td>
						<b>Uso CFDI:</b><br> $cfdi
					</td>
					<td>
						<b>Dirección:</b><br> $direccion1
					</td>
					<td>
						<b>Entre calles:</b><br> $entrecalles
					</td>
				</tr>
				</table>

				<table style='width:100%'>
				<tr>
					<td>
						<b>Num. Exterior:</b><br> $numero
					</td>
					<td>
						<b>Colonia:</b><br> $colonia
					</td>
					<td>
						<b>Ciudad:</b><br> $ciudad
					</td>
					<td>
						<b>Código postal:</b><br> $cp
					</td>
					<td>
						<b>Pais:</b><br> $pais
					</td>
					<td>
						<b>Estado:</b><br> $estado
					</td>
					<td>
						<b>Teléfono:</b><br> $telefono
					</div>
				</tr>
				</table>
				<br>
				<hr>
				<h3>Productos</h3>
				<table style='width:100%'>
				<hr>
				<table style='width:100%'>
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
							<b>Envío</b>
						</td>

						<td>
							<b>Total</b>
						</td>
					</tr>
				";

					///////////////////////////////////
					$sub_total=0;
					$sub_envio=0;
					foreach($datos as $key){
						$texto.="<tr>";
							$texto.= "<td>";
									$texto.= $key->clave;
									$texto.= "<br><b>".$key->nombre."</b>";
									$texto.= "<br>".$key->modelo;
									$texto.= "<br>".$key->marca;
									$texto.= "<br>".$key->categoria;
							$texto.= "</td>";

							$texto.= "<td>";
								$texto.= $key->cantidad;
							$texto.= "</td>";

							$texto.= "<td>";
								$texto.= moneda($key->precio);
								$sub_total+=$key->precio*$key->cantidad;
							$texto.= "</td>";

							$texto.= "<td>";
								$texto.= moneda($key->envio);
								$sub_envio+=$key->envio*$key->cantidad;
							$texto.= "</td>";

							$texto.= "<td>";
								$texto.= moneda($key->total);
							$texto.= "</td>";

						$texto.= "</tr>";
					}

					///////////////////////////////////
						$gtotal=$sub_total+$sub_envio;
						$texto.= "<tr>";
							$texto.= "<td colspan=4>";
								$texto.= "<b>Subtotal</b>";
							$texto.= "</td>";
							$texto.= "<td>";
								$texto.= moneda($gtotal);
							$texto.= "</td>";
						$texto.= "</tr>";

						if(is_array($cupones) and count($cupones)>0){
							$texto.= "<tr><td colspan=5>Cupones</td></tr>";
							foreach($cupones as $keyc){
								$texto.= "<tr>";
									$texto.= "<td colspan=4>";
										$texto.= $keyc->codigo;
										$texto.= "<br>";
										$texto.= $keyc->descripcion;
									$texto.= "</td>";
									$texto.= "<td>";

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

									$texto.= "</td>";
								$texto.= "</tr>";

								$texto.= "<tr>";
									$texto.= "<td colspan=4>";
										$texto.= "<h4><b>Total:</b></h4>";
									$texto.= "</td>";

									$texto.= "<td>";
										$texto.= "<h4><b>".moneda($gtotal)."</b></h4>";
									$texto.= "</td>";
								$texto.= "</tr>";
							}
						}
						$texto.= "<tr>";
							$texto.= "<td colspan=4>";
								$texto.= "<b>Subtotal</b>";
							$texto.= "</td>";
							$texto.= "<td>";
								$texto.= moneda($gtotal);
							$texto.= "</td>";
						$texto.= "</tr>";

						$iva=$gtotal*.16;
						$texto.= "<tr>";
							$texto.= "<td colspan=4>";
								$texto.= "<b>IVA</b>";
							$texto.= "</td>";
							$texto.= "<td>";
								$texto.= moneda($iva);
							$texto.= "</td>";
						$texto.= "</tr>";

						$gtotal=$gtotal*1.16;
						$texto.= "<tr>";
							$texto.= "<td colspan=4>";
								$texto.= "<b>Total</b>";
							$texto.= "</td>";
							$texto.= "<td>";
								$texto.= moneda($gtotal);
							$texto.= "</td>";
						$texto.= "</tr>";

				$texto.="</table>";

			$asunto="Compra Exitosa";
			////////////////////////////////////////////////////
			$db->correo($correo, $texto, $asunto);
	}

	if (isset($id)) {
			http_response_code(200);
			return;
	}

?>
