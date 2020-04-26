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


	if($monto_pago>=$pedido->total){

		$sql="update pedidos set estado_pago='$estado_pago' confirmacion='$estado_pago', idpago='$id', pagador='ipn', pago='Mercado Pago', estatus='PROCESANDO' where id='$idpedido'";
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

		if ($estado_pago=="approved"){
			$estatus="";
			$rechazado=0;

			$texto="$id $idpedido $estado_pago";
			$sql="insert into new_table (log) values (:log)";
			$sth = $db->dbh->prepare($sql);
			$sth->bindValue(':log',$texto);
			$sth->execute();

			$resp = crearNuevoToken();
			$tok=$resp->token;
			foreach($datos as $key){
				$clave=$key->clave;
				$idprod=$key->idprod;
				$cantidad=$key->cantidad;

				$sql="select * from productos where id='".$idprod."'";
				$prod_query = $db->dbh->prepare($sql);
				$prod_query->execute();
				$prod_pedido=$prod_query->fetch(PDO::FETCH_OBJ);
				$precio_prod=$prod_pedido->precio;

				$sql="select producto_exist.*,almacen.numero from producto_exist left outer join almacen on almacen.homoclave=producto_exist.almacen where id='$idprod' order by existencia desc";
				$exist = $db->dbh->prepare($sql);
				$exist->execute();
				$contar=$exist->rowCount();
				if($contar>0){
					$alma_pedido=$exist->fetchAll(PDO::FETCH_OBJ);
					foreach($alma_pedido as $pedx){
						if($cantidad>0){
							$pedir=$pedx->existencia-$cantidad;
							if($pedir>=0){
								$pedir=$cantidad;
							}
							else{
								$pedir=$cantidad+$pedir;
							}
							$cantidad=$cantidad-$pedir;

							if($pedir>0){
								$envio=array();
								$contar=0;

								$resp =servicioApi('GET',"existencia/detalle/".$clave."/".$pedx->numero,NULL,$tok);
								if($resp->promocion){

									if ($resp->promocion->descuentoPrecio>0){
										$precio_desc=$resp->promocion->descuentoPrecio;
									}
									if($resp->promocion->descuentoPorcentaje>0){
										$porc=$resp->promocion->descuentoPorcentaje;
										$precio_desc=$precio_prod-(($precio_prod*$porc)/100);
									}
									$precio_f=round($precio_desc,2);
								}
								else{
									$precio_f=$precio_prod;
								}

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

								if($key->tipo=="CT"){
									$producto[0]=array(
										'cantidad' => $pedir,
										'clave' => $clave,
										'precio' => "$precio_f",
										'moneda' => $prod_pedido->moneda
									);
								}

								$arreglo=array(
									'idPedido' => (int)$idpedido,
									'almacen' => $pedx->numero,
									'tipoPago' => "03",
									'envio' => json_decode(json_encode($envio)),
									'producto' => json_decode(json_encode($producto)),
								);
								$json = json_encode($arreglo);

								$resp =servicioApi('POST','pedido',$json,$tok); 					/////////////////////////////////////////////PEDIDO
								$pedidoweb=$resp[0]->respuestaCT->pedidoWeb;
								$estatus=$resp[0]->respuestaCT->estatus;
								$sql="insert into pedidos_web (idprod, clave, cantidad, pedidoWeb, estatus, idpedido) values ('$idprod', '$clave', '$pedir', '$pedidoweb', '$estatus', '$idpedido')";
								$stmt= $db->dbh->query($sql);
							}

						}
						else{
							break;
						}
					}
				}
			}

			/////////////////////////////////////////////Correo
			if($rechazado==0){
					$texto="<h3>TIC-SHOP</h3><br>
					<h3><b><center>Pedido</center></b></h3>

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

							$texto.= "<tr>";
								$texto.= "<td colspan=4>";
									$texto.= "<b>Subtotal</b>";
								$texto.= "</td>";
								$texto.= "<td>";
									$texto.= moneda($sub_total);
								$texto.= "</td>";
							$texto.= "</tr>";
							$texto.= "<tr>";
								$texto.= "<td colspan=4>";
									$texto.= "<b>Envio</b>";
								$texto.= "</td>";
								$texto.= "<td>";
									$texto.= moneda($sub_envio);
								$texto.= "</td>";
							$texto.= "</tr>";

							$texto.= "<tr>";
								$texto.= "<td colspan=4>";
									$texto.= "<b>Total</b>";
									$texto.= "</td>";
									$texto.= "<td>";
									$texto.= moneda($gtotal);
								$texto.= "</td>";
							$texto.= "</tr>";

							if(is_array($cupones)){
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
					$texto.="</table>";

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
			$db->correo($correo, $texto, $asunto);
			////////////////////////////////////////////////////
		}




	}

	if (isset($id)) {
			http_response_code(200);
			return;
	}



?>
