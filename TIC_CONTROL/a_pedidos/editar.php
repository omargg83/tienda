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
	$pais="";
	$estado="";
	$cp="";

	$fact_direccion1_cli="";
	$fact_entrecalles_cli="";
	$fact_numero_cli="";
	$fact_colonia_cli="";
	$fact_ciudad="";
	$fact_pais="";
	$fact_estado="";
	$fact_cp="";

	$rfc="";
	$cfdi="";

	$telefono="";
	$factura="0";
	$dir_tipo=0;

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

		$telefono=$row['telefono'];
		$factura=$row['factura'];

		$nombre_cli=$row['nombre'];
		$apellido_cli=$row['apellido'];
		$correo_cli=$row['correo'];

		$direccion1_cli=$row['direccion1'];
		$entrecalles_cli=$row['entrecalles'];
		$numero_cli=$row['numero'];
		$colonia_cli=$row['colonia'];
		$ciudad=$row['ciudad'];
		$cp=$row['cp'];
		$pais=$row['pais'];
		$estado=$row['estado'];

		$fact_direccion1_cli=$row['fact_direccion1'];
		$fact_entrecalles_cli=$row['fact_entrecalles'];
		$fact_numero_cli=$row['fact_numero'];
		$fact_colonia_cli=$row['fact_colonia'];
		$fact_ciudad=$row['fact_ciudad'];
		$fact_cp=$row['fact_cp'];
		$fact_pais=$row['fact_pais'];
		$fact_estado=$row['fact_estado'];
		$dir_tipo=$row['dir_tipo'];
  }
  echo "<div class='container'>";
    echo "<form id='form_comision' action='' data-lugar='a_pedidos/db_' data-destino='a_pedidos/editar' data-funcion='guardar_pedido'>";
      echo "<input type='hidden' class='form-control' id='id' name='id' value='$id'>";
      echo "<input type='hidden' class='form-control' id='idcliente' name='idcliente' value='$idcliente'>";
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
								echo "<option value='PEDIDO CONFIRMADO'"; if($estatus=='PEDIDO CONFIRMADO'){ echo " selected"; } echo ">PEDIDO CONFIRMADO</option>";
							echo "</select>";
						echo "</div>";

						echo "<div class='col-3'>";
							echo "<label>Factura</label>";
							echo "<select id='factura' name='factura' class='form-control form-control-sm' onchange='factura_datos()'>";
								echo "<option value='0'"; if($factura=='0'){ echo " selected"; } echo ">No</option>";
								echo "<option value='1'"; if($factura=='1'){ echo " selected"; } echo ">Si</option>";
							echo "</select>";
						echo "</div>";

						echo "<div class='col-4'>";
							echo "<label>Correo:</label>";
							echo "<input type='text' class='form-control form-control-sm' id='correo' name='correo' value='$correo_cli' readonly>";
						echo "</div>";

					echo "</div>";

					echo "<hr>";
					echo "<div class='row'>";
						echo "<div class='col-4'>";
							echo "<label>Nombre:</label>";
							if($estatus=='EN ESPERA' or $id==0){
								echo "<input type='text' class='form-control form-control-sm' placeholder='Click para agregar Cliente' id='winmodal_cliente' name='winmodal_cliente' value='$nombre_cli' readonly  data-id='$idcliente' data-id2='$id' data-lugar='a_pedidos/form_cliente' title='Click para agregar Cliente'>";
							}
							else{
								echo "<input type='text' class='form-control form-control-sm' id='cliente' name='cliente' value='$nombre_cli' readonly>";
							}
						echo "</div>";

						echo "<div class='col-8'>";
							echo "<label>Apellido: </label>";
							echo "<input type='text' class='form-control form-control-sm' placeholder='Apellido' id='apellido' name='apellido' value='$apellido_cli' readonly  >";
						echo "</div>";
					echo "</div>";


					echo "<div class='row' id='factura_div' ";
					if ($factura=="0"){ echo " style='display:none' "; }
					echo " >";
						echo "<div class='col-3'>";
							echo "<label>RFC:</label>";
							echo "<input type='text' class='form-control form-control-sm' placeholder='RFC' id='rfc' name='rfc' value='$rfc'>";
						echo "</div>";
						echo "<div class='col-9'>";
							echo "<label>CFDI:</label>";

							echo "<select id='cfdi' name='cfdi' class='form-control form-control-sm'>";
							$cfdi_obj=$db->cfdi();
							foreach($cfdi_obj as $key){
								echo "<option value='".$key->cfdi."'"; if($cfdi==$key->cfdi){ echo " selected";} echo " >".$key->cfdi."</option>";
							}
							echo "</select>";
						echo "</div>";

						echo "<div class='col-12'>";
							echo "<label>Direcciones de facturación disponibles</label>";
							$resp=$db->direcciones($idcliente);
							echo "<select id='dir_tipo' name='dir_tipo' class='form-control' onchange='select_factdir()'>";
							echo "<option value='0'>Utilizar la misma dirección de envío</option>";
							foreach($resp as $key){
								echo "<option value='".$key['iddireccion']."'";
								if ($dir_tipo==$key['iddireccion']){ echo " selected";}
								echo ">".$key['direccion1']."</option>";
							}
							echo "<option value='nueva'>* Nueva dirección</option>";
							echo "</select>";
						echo "</div>";

						echo "<div class='col-12' id='dirfactura_div' ";
							if ($dir_tipo=="0"){ echo " style='display:none' "; }
							echo  " >";
							echo "<div class='row'>";

								echo "<div class='col-12'>";
									echo "<h5>Dirección de facturación</h5>";
								echo "</div>";

								echo "<div class='col-8'>";
									echo "<label>Dirección 1:</label>";
									echo "<input type='text' class='form-control form-control-sm' id='fact_direccion1' name='fact_direccion1' value='$fact_direccion1_cli' >";
								echo "</div>";

								echo "<div class='col-4'>";
									echo "<label>Entrecalles:</label>";
									echo "<input type='text' class='form-control form-control-sm' id='fact_entrecalles' name='fact_entrecalles' value='$fact_entrecalles_cli' >";
								echo "</div>";

								echo "<div class='col-3'>";
									echo "<label>Num. Exterior:</label>";
									echo "<input type='text' class='form-control form-control-sm' id='fact_numero' name='fact_numero' value='$fact_numero_cli' >";
								echo "</div>";

								echo "<div class='col-3'>";
									echo "<label>Colonia:</label>";
									echo "<input type='text' class='form-control form-control-sm' id='fact_colonia' name='fact_colonia' value='$fact_colonia_cli' >";
								echo "</div>";

								echo "<div class='col-3'>";
									echo "<label>Ciudad:</label>";
									echo "<input type='text' class='form-control form-control-sm' id='fact_ciudad' name='fact_ciudad' value='$fact_ciudad'>";
								echo "</div>";

								echo "<div class='col-3'>";
									echo "<label>CP:</label>";
									echo "<input type='text' class='form-control form-control-sm' id='fact_cp' name='fact_cp' value='$fact_cp'>";
								echo "</div>";

								echo "<div class='col-3'>";
									echo "<label>Pais:</label>";
									echo "<input type='text' class='form-control form-control-sm' id='fact_pais' name='fact_pais' value='$fact_pais'>";
								echo "</div>";

								echo "<div class='col-3'>";
									echo "<label>Estado:</label>";
									echo "<input type='text' class='form-control form-control-sm' id='fact_estado' name='fact_estado' value='$fact_estado'>";
								echo "</div>";

							echo "</div>";
						echo "</div>";
					echo "</div>";

					echo "<hr>";
					echo "<h5>Dirección de envío</h5>";
					echo "<div class='row'>";
						echo "<div class='col-8'>";
							echo "<label>Dirección 1:</label>";
							echo "<input type='text' class='form-control form-control-sm' id='direccion1' name='direccion1' value='$direccion1_cli'>";
						echo "</div>";

						echo "<div class='col-4'>";
							echo "<label>Entrecalles:</label>";
							echo "<input type='text' class='form-control form-control-sm' id='entrecalles' name='entrecalles' value='$entrecalles_cli' >";
						echo "</div>";

						echo "<div class='col-3'>";
							echo "<label>Num. Exterior:</label>";
							echo "<input type='text' class='form-control form-control-sm' id='numero' name='numero' value='$numero_cli'>";
						echo "</div>";

						echo "<div class='col-3'>";
							echo "<label>Colonia:</label>";
							echo "<input type='text' class='form-control form-control-sm' id='colonia' name='colonia' value='$colonia_cli'>";
						echo "</div>";

						echo "<div class='col-3'>";
							echo "<label>Ciudad:</label>";
							echo "<input type='text' class='form-control form-control-sm' id='ciudad' name='ciudad' value='$ciudad'>";
						echo "</div>";

						echo "<div class='col-3'>";
							echo "<label>CP:</label>";
							echo "<input type='text' class='form-control form-control-sm' id='cp' name='cp' value='$cp' >";
						echo "</div>";

						echo "<div class='col-3'>";
							echo "<label>Pais:</label>";
							echo "<input type='text' class='form-control form-control-sm' id='pais' name='pais' value='$pais' >";
						echo "</div>";

						echo "<div class='col-3'>";
							echo "<label>Estado:</label>";
							echo "<input type='text' class='form-control form-control-sm' id='estado' name='estado' value='$estado' >";
						echo "</div>";

						echo "<div class='col-3'>";
							echo "<label>Teléfono:</label>";
							echo "<input type='text' class='form-control form-control-sm' id='telefono' name='telefono' value='$telefono' >";
						echo "</div>";
					echo "</div>";
					echo "<hr>";

				  echo "<div class='row'>";
            echo "<div class='col-12'>";
              echo "<label>Notas del pedido</label>";
              echo "<input type='text' class='form-control form-control-sm' id='notas' name='notas' value='$notas' placeholder='Notas del pedido'>";
            echo "</div>";
          echo "</div>";
					echo "<hr>";

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
							echo "<button type='submit' class='btn btn-outline-secondary btn-sm'><i class='far fa-save'></i>Guardar</button>";

							if($estatus=='EN ESPERA' or $id==0){
	            	echo "<button type='button' class='btn btn-outline-secondary btn-sm' id='winmodal_cli' data-id='$idcliente' data-id2='$id' data-lugar='a_pedidos/form_cliente' title='Agregar Cliente' ><i class='fas fa-user-tag'></i>+ Cliente</button>";

	              echo "<button type='button' class='btn btn-outline-secondary btn-sm' id='winmodal_prod' data-id='$idcliente' data-id2='$id' data-lugar='a_pedidos/form_producto' title='Agregar Producto' ><i class='fab fa-product-hunt'></i>+ Producto</button>";

								echo "<button type='button' class='btn btn-outline-secondary btn-sm' id='winmodal_cup' data-id='$idcliente' data-id2='$id' data-lugar='a_pedidos/form_cupon' title='Agregar Cupón' ><i class='fas fa-ticket-alt'></i>+ Cupón</button>";
							}

						echo "<button type='button' class='btn btn-outline-secondary btn-sm' onclick='solicitar_ct($id)' title='Solicitar a CT'><i class='fas fa-poo-storm'></i>Procesar pedido</button>";
            echo "<button type='button' class='btn btn-outline-secondary btn-sm' id='lista_cat' data-lugar='a_pedidos/lista' title='Regresar'><i class='fas fa-undo-alt'></i>Regresar</button>";
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
												echo "<button class='btn btn-outline-secondary btn-sm' id='eliminar_prodn".$key['id']."' data-lugar='a_pedidos/db_' data-destino='a_pedidos/editar' data-id='".$key['id']."' data-iddest='$id' data-funcion='borrar_prodped' data-div='trabajo'><i class='far fa-trash-alt'></i></button>";
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

						if(is_array($cupones) or count($cupones)>0){
							echo "<tr>";
								echo "<td colspan=7 class='text-center'>";
								echo "<h5>Cupones</h5>";
								echo "</td>";
							echo "</tr>";

							foreach($cupones as $keyc){
								echo "<tr>";
									echo "<td class='text-right'>";
										echo "<button type='button' class='btn btn-outline-secondary btn-sm'  onclick='elimina_cuadmin(".$keyc->id.", $id)'><i class='far fa-times-circle'></i></button>";
									echo "</td>";

									echo "<td colspan=5>";
										echo $keyc->codigo." ".$keyc->descripcion;
									echo "</td>";

									echo "<td class='text-right'>";

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

									echo "</td>";
								echo "</tr>";
							}
						}
							echo "<tr>";
								echo "<td colspan=6' class='text-right'>";
									echo "<b>Subtotal:</b>";
								echo "</td>";

								echo "<td class='text-right'>";
									echo moneda($gtotal);
								echo "</td>";
							echo "</tr>";

							$iva=$gtotal*.16;
							echo "<tr>";
								echo "<td colspan=6 class='text-right'>";
									echo "<b>IVA:</b>";
								echo "</td>";
								echo "<td class='text-right'>";
									echo moneda($iva);
								echo "</td>";
							echo "</tr>";

							$gtotal=$gtotal*1.16;
							echo "<tr>";
								echo "<td colspan=6 class='text-right'>";
									echo "<b>Total:</b>";
								echo "</td>";
								echo "<td class='text-right'>";
									echo moneda($gtotal);
								echo "</td>";
							echo "</tr>";

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
