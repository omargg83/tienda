<?php
	require_once("db_.php");
  $id=$_REQUEST['id'];
  $fecha=date("d-m-Y");
  $estatus="";
  $idcliente=0;
  $idenvio="";
  $idfactura="";
  $notas="";
  $pago="";
  $idpago="";
  $pagador="";
  $estado_pago="";

	$nombre_cli="";
	$apellido_cli="";
	$correo_cli="";
	$direccion1_cli="";
	$entrecalles_cli="";
	$numero_cli="";
	$colonia_cli="";
	$ciudad="";
	$rfc="";
	$cfdi="";

	if($id>0){
    $row=$db->editar_pedido($id);
		$cupones=$db->pedido_cupones($id);
    $fecha=fecha($row['fecha']);
    $estatus=$row['estatus'];
    $idcliente=$row['idcliente'];
    $idenvio=$row['idenvio'];
    $idfactura=$row['idfactura'];
    $notas=$row['notas'];
		$pago=$row['pago'];
		$idpago=$row['idpago'];
		$pagador=$row['pagador'];
		$estado_pago=$row['estado_pago'];

		$rfc=$row['rfc'];
		$cfdi=$row['cfdi'];
		$ciudad=$row['ciudad'];

		$nombre_cli=$row['nombre'];
		$apellido_cli=$row['apellido'];
		$correo_cli=$row['correo'];
		$direccion1_cli=$row['direccion1'];
		$entrecalles_cli=$row['entrecalles'];
		$numero_cli=$row['numero'];
		$colonia_cli=$row['colonia'];


    $cli=$db->cliente($idcliente);
  }
  echo "<div class='container'>";
    echo "<form id='form_comision' action='' data-lugar='a_pedidos/db_' data-destino='a_pedidos/editar' data-funcion='guardar_pedido'>";
      echo "<input type='hidden' class='form-control' id='id' name='id' value='$id'>";
      echo "<div class='card'>";
        echo "<div class='card-header'>";
          echo "Pedido # $id";
        echo "</div>";

        echo "<div class='card-body'>";

          echo "<div class='row'>";
            echo "<div class='col-2'>";
              echo "<label>Fecha</label>";
              echo "<input type='text' class='form-control form-control-sm fechaclass' id='fecha' name='fecha' value='$fecha' readonly>";
            echo "</div>";

						echo "<div class='col-3'>";
							echo "<label>Estado</label>";
							echo "<select id='estatus' name='estatus' class='form-control form-control-sm'>";
								echo "<option value='EN ESPERA'"; if($estatus=='EN ESPERA'){ echo " selected"; } echo ">EN ESPERA</option>";
								echo "<option value='PROCESANDO'"; if($estatus=='PROCESANDO'){ echo " selected"; } echo ">PROCESANDO</option>";
								echo "<option value='PROCESANDO PAGO'"; if($estatus=='PROCESANDO PAGO'){ echo " selected"; } echo ">PROCESANDO PAGO</option>";
								echo "<option value='PROCESANDO PAGO PENDIENTE'"; if($estatus=='PROCESANDO PAGO PENDIENTE'){ echo " selected"; } echo ">PROCESANDO PAGO PENDIENTE</option>";
							echo "</select>";
						echo "</div>";
					echo "</div>";
					echo "<hr>";

					echo "<div class='row'>";
						echo "<div class='col-4'>";
							echo "<label>RFC:</label>";
							echo "<input type='text' class='form-control form-control-sm' placeholder='RFC' id='rfc' name='rfc' value='$rfc' readonly  >";
						echo "</div>";
						echo "<div class='col-8'>";
							echo "<label>CFDI:</label>";
							echo "<input type='text' class='form-control form-control-sm' placeholder='CFDI' id='cfdi' name='cfdi' value='$cfdi' readonly  >";
						echo "</div>";

						echo "<div class='col-4'>";
							echo "<label>Nombre:</label>";
							if($estatus=='EN ESPERA'){
								echo "<input type='text' class='form-control form-control-sm' placeholder='Click para agregar Cliente' id='winmodal_cliente' name='winmodal_cliente' value='$nombre_cli' readonly  data-id='$idcliente' data-id2='$id' data-lugar='a_pedidos/form_cliente' title='Click para agregar Cliente'>";
							}
							else{
								echo "<input type='text' class='form-control form-control-sm' id='cliente' name='cliente' value='$nombre_cli' readonly>";
							}
						echo "</div>";

						echo "<div class='col-4'>";
							echo "<label>Apellido:</label>";
							echo "<input type='text' class='form-control form-control-sm' placeholder='Apellido' id='apellido' name='apellido' value='$apellido_cli' readonly  >";
						echo "</div>";

						echo "<div class='col-4'>";
							echo "<label>Correo:</label>";
							echo "<input type='text' class='form-control form-control-sm' id='correo' name='correo' value='$correo_cli' readonly>";
						echo "</div>";

						echo "<div class='col-12'>";
							echo "<label>Dirección 1:</label>";
							echo "<input type='text' class='form-control form-control-sm' id='direccion1' name='direccion1' value='$direccion1_cli' readonly>";
						echo "</div>";

						echo "<div class='col-4'>";
							echo "<label>Entrecalles:</label>";
							echo "<input type='text' class='form-control form-control-sm' id='entrecalles' name='entrecalles' value='$entrecalles_cli' readonly>";
						echo "</div>";

						echo "<div class='col-4'>";
							echo "<label>Num. Exterior:</label>";
							echo "<input type='text' class='form-control form-control-sm' id='numero' name='numero' value='$numero_cli' readonly>";
						echo "</div>";

						echo "<div class='col-4'>";
							echo "<label>Colonia:</label>";
							echo "<input type='text' class='form-control form-control-sm' id='colonia' name='colonia' value='$colonia_cli' readonly>";
						echo "</div>";

						echo "<div class='col-4'>";
							echo "<label>Ciudad:</label>";
							echo "<input type='text' class='form-control form-control-sm' id='ciudad' name='ciudad' value='$ciudad' readonly>";
						echo "</div>";

					echo "</div>";


					echo "<hr>";

				  echo "<div class='row'>";
            echo "<div class='col-12'>";
              echo "<label>Notas del pedido</label>";
              echo "<input type='text' class='form-control form-control-sm' id='notas' name='notas' value='$notas' placeholder='Notas del pedido'>";
            echo "</div>";
          echo "</div>";


					echo "<div class='row'>";
            echo "<div class='col-2'>";
              echo "<label>Pago</label>";
              echo "<input type='text' class='form-control form-control-sm' id='pago' name='pago' value='$pago' placeholder='pago' readonly>";
            echo "</div>";

            echo "<div class='col-2'>";
              echo "<label>Id de Pago</label>";
              echo "<input type='text' class='form-control form-control-sm' id='idpago' name='idpago' value='$idpago' placeholder='identificador de pago' readonly>";
            echo "</div>";

            echo "<div class='col-4'>";
              echo "<label>Pagador</label>";
              echo "<input type='text' class='form-control form-control-sm' id='pagador' name='pagador' value='$pagador' placeholder='Pagador' readonly>";
            echo "</div>";

            echo "<div class='col-3'>";
              echo "<label>Estado</label>";
              echo "<input type='text' class='form-control form-control-sm' id='estado_pago' name='estado_pago' value='$estado_pago' placeholder='Estado del pago' readonly>";
            echo "</div>";

          echo "</div>";
        echo "</div>";
        echo "<div class='card-footer'>";
          echo "<div class='btn-group'>";
							if($estatus=='EN ESPERA'){
            		echo "<button type='submit' class='btn btn-outline-secondary btn-sm'><i class='far fa-save'></i>Guardar</button>";

	            	echo "<button type='button' class='btn btn-outline-secondary btn-sm' id='winmodal_cli' data-id='$idcliente' data-id2='$id' data-lugar='a_pedidos/form_cliente' title='Agregar Cliente' ><i class='fas fa-user-tag'></i>Agregar Cliente</button>";

	              echo "<button type='button' class='btn btn-outline-secondary btn-sm' id='winmodal_prod' data-id='$idcliente' data-id2='$id' data-lugar='a_pedidos/form_producto' title='Agregar Producto' ><i class='fab fa-product-hunt'></i>Agregar Producto</button>";

								echo "<button type='button' class='btn btn-outline-secondary btn-sm' id='winmodal_cup' data-id='$idcliente' data-id2='$id' data-lugar='a_pedidos/form_cupon' title='Agregar Cupón' ><i class='fas fa-ticket-alt'></i>Agregar Cupón</button>";
							}

            echo "<button class='btn btn-outline-secondary btn-sm' id='lista_cat' data-lugar='a_pedidos/lista' title='regresar'><i class='fas fa-undo-alt'></i>Regresar</button>";
          echo "</div>";
        echo "</div>";

					if($id>0){
						$row=$db->productos_pedido($id);
						echo "<div class='card-body'>";
							echo "<table class='table table-sm'>";
							echo "<tr><th>-</th><th>Clave</th><th>Num. Parte</th><th>Nombre</th><th>Precio</th><th>Cantidad</th><th>Total</th></tr>";
							$gtotal=0;
							$genvio=0;
							foreach($row as $key){
								echo "<tr id='".$key['id']."' class='edit-t'>";
									echo "<td>";
										echo "<div class='btn-group'>";
											if($estatus=='EN ESPERA'){
												echo "<button class='btn btn-outline-secondary btn-sm' id='eliminar_prodn".$key['id']."' data-lugar='a_pedidos/db_' data-destino='a_pedidos/editar' data-id='".$key['id']."' data-iddest='$id' data-funcion='borrar_prodped' data-div='trabajo'><i class='far fa-trash-alt'></i></i></button>";
											}
										echo "</div>";
									echo "</td>";
									echo "<td>";
										echo $key['clave'];
									echo "</td>";
									echo "<td>";
										echo $key['numParte'];
									echo "</td>";
									echo "<td><b> Nombre: </b>";
										echo $key['nombre'];
									echo "<br><b> Modelo: </b>";
										echo $key['modelo'];
									echo "<br><b> Marca: </b>";
										echo $key['marca'];
										echo "<br>+ Envio:".moneda($key['envio']);
									echo "</td>";
									echo "<td class='text-right'>";
										echo moneda($key['precio']);
									echo "</td>";
									echo "<td class='text-center'>";
										echo $key['cantidad'];
									echo "</td>";
									echo "<td class='text-right'>";
										echo moneda($key['total']);
									echo "</td>";
								echo "</tr>";
								$gtotal+=$key['total'];
								$genvio+=$key['envio'];
							}
							echo "<tr>";
								echo "<td colspan=6 class='text-right'>";
									echo "<b>Subtotal:</b>";
								echo "</td>";
								echo "<td class='text-right'>";
									echo moneda($gtotal);
								echo "</td>";
							echo "</tr>";

							echo "<tr>";
								echo "<td colspan=6 class='text-right'>";
									echo "<b>Total:</b>";
								echo "</td>";
								echo "<td class='text-right'>";
									echo moneda($gtotal);
								echo "</td>";
							echo "</tr>";


						if(is_array($cupones)){
							echo "<tr>";
								echo "<td colspan=7 class='text-center'>";
								echo "<h5>Cupones</h5>";
								echo "</td>";
							echo "</tr>";

						foreach($cupones as $keyc){
							echo "<tr>";
								echo "<td colspan=2 class='text-right'>";
									echo "<a href='#' onclick='elimina_cupon(".$keyc->id.", $id)'><i class='far fa-times-circle'></i></a>";
								echo "</td>";

								echo "<td colspan=4>";
									echo $keyc->codigo." ".$keyc->descripcion;
								echo "</td>";

								echo "<td class='text-right'>";
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

								echo "</td>";
							echo "</tr>";

							echo "<tr>";
								echo "<td colspan=6'>";
									echo "<h4><b>Total:</b></h4>";
								echo "</td>";

								echo "<td class='text-right'>";
									echo "<h4><b>".moneda($gtotal)."</b></h4>";
								echo "</td>";
							echo "</tr>";
						}


							echo "</table>";
						echo "</div>";


						$row=$db->pedidos_web($id);
						echo "<div class='card-body'>";
							echo "<h5>PEDIDOS A CT</h5>";
							echo "<table class='table table-sm'>";
							echo "<tr><td></td><td>Clave</td><td>Cantidad</td><td>Pedido Web</td><td>Estado</td></tr>";
							foreach($row as $key){
								echo "<tr id='".$key['id']."' class='edit-t'>";

								echo "<td>";
								echo "<div class='btn-group'>";
								?>
									<button type="button" class="btn btn-outline-secondary btn-sm" id="confirma" title="Editar" onclick="confirmar_web('<?php echo $key["pedidoWeb"]; ?>',<? echo $id; ?>)"><i class="far fa-check-circle"></i></button>
								<?php
								echo "</div>";
								echo "</td>";


								echo "<td>".$key["clave"]."</td>";
								echo "<td>".$key["cantidad"]."</td>";
								echo "<td>".$key["pedidoWeb"]."</td>";
								echo "<td>".$key["estatus"]."</td>";
								echo "</tr>";
							}
							echo "</table>";
						echo "</div>";
					}


					echo "</table>";
				echo "</div>";
				}


      echo "</div>";
    echo "</form>";
  echo "</div>";
 ?>

 <script>
   $(function() {
     fechas();
   });
 </script>
