<?php
	require_once("db_.php");
  $id=$_REQUEST['id'];
  $fecha=date("d-m-Y");
  $estado=date("d-m-Y");
  $idcliente=0;
  $nombre_cli="";
  $correo_cli="";
  $idenvio="";
  $idfactura="";
  $notas="";
  if($id>0){
    $row=$db->editar_pedido($id);
    $fecha=fecha($row['fecha']);
    $estado=$row['estado'];
    $idcliente=$row['idcliente'];
    $idenvio=$row['idenvio'];
    $idfactura=$row['idfactura'];
    $notas=$row['notas'];
    $cli=$db->cliente($idcliente);
    $nombre_cli=$cli['nombre'];
    $correo_cli=$cli['correo'];
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
              echo "<input type='text' class='form-control fechaclass' id='fecha' name='fecha' value='$fecha' readonly>";
            echo "</div>";

            echo "<div class='col-3'>";
              echo "<label>Estado</label>";
              echo "<select id='estado' name='estado' class='form-control'>";
                echo "<option value='pendiente'"; if($estado=='pendiente'){ echo " selected"; } echo ">Pendiente de pago</option>";
                echo "<option value='procesando'"; if($estado=='procesando'){ echo " selected"; } echo ">Procesando</option>";
                echo "<option value='espera'"; if($estado=='espera'){ echo " selected"; } echo ">En espera</option>";
                echo "<option value='completado'"; if($estado=='completado'){ echo " selected"; } echo ">Completado</option>";
                echo "<option value='cancelado'"; if($estado=='cancelado'){ echo " selected"; } echo ">Cancelado</option>";
                echo "<option value='reembolsado'"; if($estado=='reembolsado'){ echo " selected"; } echo ">Reembolsado</option>";
                echo "<option value='fallido'"; if($estado=='fallido'){ echo " selected"; } echo ">Fallido</option>";
              echo "</select>";
            echo "</div>";
          echo "</div>";

          echo "<div class='row'>";
            echo "<div class='col-4'>";
              echo "<label>Cliente:</label>";
              echo "<input type='text' class='form-control' id='cliente' name='cliente' value='$nombre_cli' readonly>";
            echo "</div>";

            echo "<div class='col-4'>";
              echo "<label>Correo:</label>";
              echo "<input type='text' class='form-control' id='correo' name='correo' value='$correo_cli' readonly>";
            echo "</div>";
          echo "</div>";

          if($id>0){
            echo "<div class='row'>";
              echo "<div class='col-4'>";
                echo "<label>Dirección de Envio:</label>";
                echo "<select class='form-control' id='idenvio' name='idenvio' >";
                foreach($db->direccion($idcliente) as $row){
                  echo "<option value='".$row['iddireccion']."'"; if($idenvio==$row['iddireccion']){ echo " selected";} echo ">".$row['direccion1']."</option>";
                }
                echo "</select>";
              echo "</div>";
              echo "<div class='col-4'>";
                echo "<label>Dirección de Facturación:</label>";
                echo "<select class='form-control' id='idfactura' name='idfactura' >";
                foreach($db->direccion($idcliente) as $row){
                  echo "<option value='".$row['iddireccion']."'"; if($idfactura==$row['iddireccion']){ echo " selected";} echo ">".$row['direccion1']."</option>";
                }
                echo "</select>";
              echo "</div>";
            echo "</div>";
          }
          echo "<div class='row'>";
            echo "<div class='col-12'>";
              echo "<label>Notas del pedido</label>";
              echo "<input type='text' class='form-control' id='notas' name='notas' value='$notas' placeholder='Notas del pedido'>";
            echo "</div>";
          echo "</div>";

        echo "</div>";
        echo "<div class='card-footer'>";
          echo "<div class='btn-group'>";
            echo "<button type='submit' class='btn btn-outline-secondary btn-sm'><i class='far fa-save'></i>Guardar</button>";
            	echo "<button type='button' class='btn btn-outline-secondary btn-sm' id='winmodal_cli' data-id='$idcliente' data-id2='$id' data-lugar='a_pedidos/form_cliente' title='Agregar cliennte' ><i class='fas fa-plus'></i>Cliente</button>";
              echo "<button type='button' class='btn btn-outline-secondary btn-sm' id='winmodal_prod' data-id='$idcliente' data-id2='$id' data-lugar='a_pedidos/form_producto' title='Agregar cliennte' ><i class='fas fa-plus'></i>Producto</button>";
            echo "<button class='btn btn-outline-secondary btn-sm' id='lista_cat' data-lugar='a_pedidos/lista' title='regresar'><i class='fas fa-undo-alt'></i>Regresar</button>";
          echo "</div>";
        echo "</div>";


					if($id>0){
						$row=$db->productos_pedido($id);
						echo "<div class='card-header'>";
							echo "Incluir productos";
						echo "</div>";
						echo "<div class='card-body'>";
							echo "<table class='table table-sm'>";
							echo "<tr><th>-</th><th>Clave</th><th>Num. Parte</th><th>Nombre</th><th>Precio</th><th>Cantidad</th><th>Total</th></tr>";
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
									echo "</td>";
								echo "</tr>";
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
