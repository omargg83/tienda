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
	$direccion2_cli="";

	if($id>0){
    $row=$db->editar_pedido($id);
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

    $cli=$db->cliente($idcliente);
    $nombre_cli=$cli['nombre'];
		$apellido_cli=$cli['apellido'];
    $correo_cli=$cli['correo'];
    $direccion1_cli=$cli['direccion1'];
    $direccion2_cli=$cli['direccion2'];
  }
  echo "<div class='container'>";
    echo "<form id='form_comision' action='' data-lugar='a_pedidos/db_' data-destino='a_pedidos/editar' data-funcion='guardar_pedido'>";
      echo "<input type='hidden' class='form-control' id='id' name='id' value='$id'>";
      echo "<div class='card'>";
        echo "<div class='card-header'>";
          echo "Pedido";
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
								echo "<option value='pendiente'"; if($estatus=='pendiente'){ echo " selected"; } echo ">Pendiente de pago</option>";
								echo "<option value='procesando'"; if($estatus=='procesando'){ echo " selected"; } echo ">Procesando</option>";
								echo "<option value='espera'"; if($estatus=='espera'){ echo " selected"; } echo ">En espera</option>";
								echo "<option value='completado'"; if($estatus=='completado'){ echo " selected"; } echo ">Completado</option>";
								echo "<option value='cancelado'"; if($estatus=='cancelado'){ echo " selected"; } echo ">Cancelado</option>";
								echo "<option value='reembolsado'"; if($estatus=='reembolsado'){ echo " selected"; } echo ">Reembolsado</option>";
								echo "<option value='fallido'"; if($estatus=='fallido'){ echo " selected"; } echo ">Fallido</option>";
							echo "</select>";
						echo "</div>";
					echo "</div>";
					echo "<hr>";

					echo "<div class='row'>";
						echo "<div class='col-4'>";
							echo "<label>Nombre:</label>";
							echo "<input type='text' class='form-control form-control-sm' placeholder='Click para agregar Cliente' id='winmodal_cliente' name='winmodal_cliente' value='$nombre_cli' readonly  data-id='$idcliente' data-id2='$id' data-lugar='a_pedidos/form_cliente' title='Click para agregar Cliente'>";
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

						echo "<div class='col-12'>";
							echo "<label>Dirección 2:</label>";
							echo "<input type='text' class='form-control form-control-sm' id='direccion2' name='direccion2' value='$direccion2_cli' readonly>";
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
            echo "<button type='submit' class='btn btn-outline-secondary btn-sm'><i class='far fa-save'></i>Guardar</button>";

            	echo "<button type='button' class='btn btn-outline-secondary btn-sm' id='winmodal_cli' data-id='$idcliente' data-id2='$id' data-lugar='a_pedidos/form_cliente' title='Agregar Cliente' ><i class='fas fa-user-tag'></i>Agregar Cliente</button>";

              echo "<button type='button' class='btn btn-outline-secondary btn-sm' id='winmodal_prod' data-id='$idcliente' data-id2='$id' data-lugar='a_pedidos/form_producto' title='Agregar Producto' ><i class='fab fa-product-hunt'></i>Agregar Producto</button>";

							echo "<button type='button' class='btn btn-outline-secondary btn-sm' id='winmodal_cup' data-id='$idcliente' data-id2='$id' data-lugar='a_pedidos/form_cupon' title='Agregar Cupón' ><i class='fas fa-ticket-alt'></i>Agregar Cupón</button>";

            echo "<button class='btn btn-outline-secondary btn-sm' id='lista_cat' data-lugar='a_pedidos/lista' title='regresar'><i class='fas fa-undo-alt'></i>Regresar</button>";
          echo "</div>";
        echo "</div>";


					if($id>0){
						$row=$db->productos_pedido($id);
						echo "<div class='card-body'>";
							echo "<table class='table table-sm'>";
							echo "<tr><th>-</th><th>Clave</th><th>Num. Parte</th><th>Nombre</th><th>Precio</th><th>Cantidad</th><th>Total</th></tr>";
							$total=0;
							foreach($row as $key){
								echo "<tr id='".$key['id']."' class='edit-t'>";
									echo "<td>";
										echo "<div class='btn-group'>";
											echo "<button class='btn btn-outline-secondary btn-sm' id='eliminar_prodn".$key['id']."' data-lugar='a_pedidos/db_' data-destino='a_pedidos/editar' data-id='".$key['id']."' data-iddest='$id' data-funcion='borrar_prodped' data-div='trabajo'><i class='far fa-trash-alt'></i></i></button>";
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
									echo "<b> Marca: </b>";
										echo $key['marca'];
									echo "</td>";
									echo "<td class='text-right'>";
										echo moneda($key['precio']);
									echo "</td>";
									echo "<td class='text-center'>";
										echo $key['cantidad'];
									echo "</td>";
									echo "<td class='text-right'>";
										echo moneda($key['total']);
										$total+=$key['total'];
									echo "</td>";
								echo "</tr>";
							}
							echo "<tr>";
								echo "<td colspan=6 class='text-right'>";
									echo "<b>Total:</b>";
								echo "</td>";
								echo "<td class='text-right'>";
									echo moneda($total);
								echo "</td>";
							echo "</tr>";
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
