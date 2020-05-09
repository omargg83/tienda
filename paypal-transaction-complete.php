<?php
	require_once("control_db.php");
	$db = new Tienda();

  $idpago=$_REQUEST['id'];
  $mail=$_REQUEST['mail'];
  $estatus=$_REQUEST['estatus'];
  $idpedido=$_REQUEST['idx'];


  if($estatus=="COMPLETED"){
    $arreglo =array();
    $arreglo+= array('estatus'=>"PROCESANDO");
    $arreglo+= array('pago'=>"PayPal");
    $arreglo+= array('idpago'=>$idpago);
    $arreglo+= array('pagador'=>$mail);
    $arreglo+= array('estado_pago'=>$estatus);
    $x=$db->update('pedidos',array('id'=>$idpedido), $arreglo);
    $ped=json_decode($x);
    $id=$ped->id;
    if($ped->error==0){

    }

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

		/////////////////////////////////////////////Correo
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
				<table>
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
		$db->correo($correo, $texto, $asunto);
		////////////////////////////////////////////////////
  }

 ?>
