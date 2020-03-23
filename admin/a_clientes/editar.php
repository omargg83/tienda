<?php
	require_once("db_.php");
	$id=$_REQUEST['id'];
	$nombre="";
	$apellido="";
	$correo="";

	if($id>0){
		$per = $db->cliente_editar($id);
		$nombre=$per->nombre;
		$apellido=$per->apellido;
		$correo=$per->correo;
	}
?>
<div class='container'>
	<form id='form_comision' action='' data-lugar='a_clientes/db_' data-destino='a_clientes/editar' data-funcion='guardar_cliente'>
		<div class='card'>
			<div class='card-header'>
				Usuarios <?php echo $id; ?>
			</div>
			<div class='card-body'>
				<input type="hidden" class="form-control" id="id" name='id' value="<?php echo $id; ?>">

			  <div class="form-row">
			    <div class="form-group col-md-4">
			      <label>Nombre</label>
			      <input type="text" class="form-control" id="nombre" name='nombre' placeholder="Nombre" value="<?php echo $nombre; ?>" required>
			    </div>

			    <div class="form-group col-md-4">
			      <label>Apellidos</label>
			      <input type="text" class="form-control" id="apellido" name='apellido' placeholder="Apellidos" value="<?php echo $apellido; ?>" required>
			    </div>
			    <div class="form-group col-md-4">
			      <label>Correo</label>
			      <input type="text" class="form-control" id="correo" name='correo' placeholder="Correo" value="<?php echo $correo; ?>" required>
			    </div>
			  </div>

			</div>
			<div class='card-footer'>
				<div class='btn-group'>
		  		<button type="submit" class="btn btn-outline-secondary btn-sm"><i class='far fa-save'></i>Guardar</button>
					<?php
						if($id>0){
							echo "<button type='button' class='btn btn-outline-secondary btn-sm' id='winmodal_pass' data-id='$id' data-lugar='a_clientes/form_pass' title='Cambiar contraseña' ><i class='fas fa-key'></i>Contraseña</button>";

							echo "<button type='button' class='btn btn-outline-secondary btn-sm' id='winmodal_pass' data-id='0' data-id2='$id' data-lugar='a_clientes/form_direccion' title='Cambiar contraseña' ><i class='fas fa-street-view'></i>Direccion</button>";
						}
					?>
					<button class='btn btn-outline-secondary btn-sm' id='lista_cat' data-lugar='a_clientes/lista' title='regresar'><i class='fas fa-undo-alt'></i>Regresar</button>
				</div>
			</div>
			<?php
				if($id>0){
					$row=$db->direcciones($id);
					echo "<div class='card-header'>";
						echo "Direcciones";
					echo "</div>";
					echo "<div class='card-body'>";
						echo "<table class='table table-sm'>";
						echo "<tr><th>-</th><th>Nombre</th><th>Apellidos</th><th>Empresa</th><th>Ciudad</th><th>Estado</th></tr>";
						foreach($row as $key){
							echo "<tr id='".$key['iddireccion']."' class='edit-t'>";
								echo "<td>";
									echo "<div class='btn-group'>";

										echo "<button type='button' class='btn btn-outline-secondary btn-sm' id='winmodal_pass' data-id='".$key['iddireccion']."' data-id2='$id' data-lugar='a_clientes/form_direccion' title='Editar' ><i class='fas fa-pencil-alt'></i></button>";
										if($key['envio']==1){
											echo "<button class='btn btn-outline-secondary btn-sm' type='button' title='Dirección de envio'><i class='fas fa-map-marked-alt'></i></button>";
										}
										if($key['factura']==1){
											echo "<button class='btn btn-outline-secondary btn-sm' type='button'  title='Dirección de facturación'><i class='far fa-id-badge'></i></button>";
										}

									echo "</div>";
								echo "</td>";
								echo "<td>";
									echo $key['nombre'];
								echo "</td>";
								echo "<td>";
									echo $key['apellidos'];
								echo "</td>";
								echo "<td>";
									echo $key['empresa'];
								echo "</td>";
								echo "<td>";
									echo $key['ciudad'];
								echo "</td>";
								echo "<td>";
									echo $key['estado'];
								echo "</td>";
							echo "</tr>";
						}
						echo "</table>";
					echo "</div>";
				}
			?>
	</form>
</div>
