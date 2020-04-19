<?php
	require_once("db_.php");
	$id=$_REQUEST['id'];
	$nombre="";
	$apellido="";
	$correo="";
	$rfc="";
	$cfdi="";
	$direccion1="";
	$direccion2="";
	$ciudad="";
	$cp="";
	$pais="";
	$estado="";
	$telefono="";

	if($id>0){
		$per = $db->cliente_editar($id);
		$nombre=$per->nombre;
		$apellido=$per->apellido;
		$correo=$per->correo;
		$rfc=$per->rfc;
		$cfdi=$per->cfdi;
		$direccion1=$per->direccion1;
		$entrecalles=$per->entrecalles;
		$numero=$per->numero;
		$colonia=$per->colonia;

		$ciudad=$per->ciudad;
		$cp=$per->cp;
		$pais=$per->pais;
		$estado=$per->estado;
		$telefono=$per->telefono;
	}
?>
<div class='container'>
	<form id='form_comision' action='' data-lugar='a_clientes/db_' data-destino='a_clientes/editar' data-funcion='guardar_cliente'>
		<div class='card'>
			<div class='card-header'>
				Datos Cliente
			</div>
			<div class='card-body'>
				<input type="hidden" class="form-control form-control-sm" id="id" name='id' value="<?php echo $id; ?>">

			  <div class="row">

					<div class="col-3">
						<label>RFC</label>
						<input type="text" class="form-control form-control-sm" id="rfc" name='rfc' placeholder="RFC" value="<?php echo $rfc; ?>" required>
					</div>

					<div class="col-3">
						<label>Uso cfdi</label>
						<input type="text" class="form-control form-control-sm" id="cfdi" name='cfdi' placeholder="Uso cfdi" value="<?php echo $cfdi; ?>" required>
					</div>
				</div>

				<div class="row">

			    <div class="col-4">
			      <label>Nombre</label>
			      <input type="text" class="form-control form-control-sm" id="nombre" name='nombre' placeholder="Nombre" value="<?php echo $nombre; ?>" required>
			    </div>

			    <div class="col-4">
			      <label>Apellidos</label>
			      <input type="text" class="form-control form-control-sm" id="apellido" name='apellido' placeholder="Apellidos" value="<?php echo $apellido; ?>" required>
			    </div>
			    <div class="col-4">
			      <label>Correo</label>
			      <input type="text" class="form-control form-control-sm" id="correo" name='correo' placeholder="Correo" value="<?php echo $correo; ?>" required>
			    </div>
			    <div class="col-12">
			      <label>Dirección (linea 1)</label>
			      <input type="text" class="form-control form-control-sm" id="direccion1" name='direccion1' placeholder="Dirección (linea 1)" value="<?php echo $direccion1; ?>" >
			    </div>
			    <div class="col-4">
			      <label>Entre calles</label>
			      <input type="text" class="form-control form-control-sm" id="entrecalles" name='entrecalles' placeholder="Entre calles" value="<?php echo $entrecalles; ?>" >
			    </div>
			    <div class="col-4">
			      <label>No. exterior</label>
			      <input type="text" class="form-control form-control-sm" id="numero" name='numero' placeholder="No. exteriors" value="<?php echo $numero; ?>" >
			    </div>
			    <div class="col-4">
			      <label>Colonia</label>
			      <input type="text" class="form-control form-control-sm" id="colonia" name='colonia' placeholder="Colonia" value="<?php echo $colonia; ?>" >
			    </div>
					<div class="col-4">
			      <label>Código postal</label>
			      <input type="text" class="form-control form-control-sm" id="cp" name='cp' placeholder="Código postal" value="<?php echo $cp; ?>" >
			    </div>

			    <div class="col-4">
			      <label>Ciudad</label>
			      <input type="text" class="form-control form-control-sm" id="ciudad" name='ciudad' placeholder="Ciudad" value="<?php echo $ciudad; ?>" >
			    </div>
			    <div class="col-4">
			      <label>Pais</label>
			      <input type="text" class="form-control form-control-sm" id="pais" name='pais' placeholder="Pais" value="<?php echo $pais; ?>" >
			    </div>
			    <div class="col-4">
			      <label>Estado</label>
			      <input type="text" class="form-control form-control-sm" id="estado" name='estado' placeholder="Estado" value="<?php echo $estado; ?>" >
			    </div>
			    <div class="col-4">
			      <label>Teléfono</label>
			      <input type="text" class="form-control form-control-sm" id="telefono" name='telefono' placeholder="Teléfono" value="<?php echo $telefono; ?>" >
			    </div>
			  </div>

			</div>
			<div class='card-footer'>
				<div class='btn-group'>
		  		<button type="submit" class="btn btn-outline-secondary btn-sm"><i class='far fa-save'></i>Guardar</button>
					<?php
						if($id>0){
							echo "<button type='button' class='btn btn-outline-secondary btn-sm' id='winmodal_pass' data-id='$id' data-lugar='a_clientes/form_pass' title='Cambiar contraseña' ><i class='fas fa-key'></i>Cambiar Contraseña</button>";


						}
					?>
					<button class='btn btn-outline-secondary btn-sm' id='lista_cat' data-lugar='a_clientes/lista' title='regresar'><i class='fas fa-undo-alt'></i>Regresar</button>
				</div>
			</div>
		</div>
		<hr>
		<div class='card'>
			<?php
				if($id>0){


					$row=$db->direcciones($id);
					echo "<div class='card-header'>";
						echo "Direcciones adicionales";

					echo "</div>";
					echo "<div class='card-body'>";
						echo "<button type='button' class='btn btn-outline-secondary btn-sm' id='winmodal_pass' data-id='0' data-id2='$id' data-lugar='a_clientes/form_direccion' title='Cambiar contraseña' ><i class='fas fa-street-view'></i>Agregar Direccion</button>";

						echo "<table class='table table-sm'>";
						echo "<tr><th>-</th><th>Nombre</th><th>Apellidos</th><th>Empresa</th><th>Ciudad</th><th>Estado</th></tr>";
						foreach($row as $key){
							echo "<tr id='".$key['iddireccion']."' class='edit-t'>";
								echo "<td>";
									echo "<div class='btn-group'>";
										echo "<button type='button' class='btn btn-outline-secondary btn-sm' id='winmodal_pass' data-id='".$key['iddireccion']."' data-id2='$id' data-lugar='a_clientes/form_direccion' title='Editar' ><i class='fas fa-pencil-alt'></i></button>";

										echo "<button class='btn btn-outline-secondary btn-sm' id='eliminar_cat' data-lugar='a_clientes/db_' data-destino='a_clientes/editar' data-id='".$key['iddireccion']."' data-id2='$id' data-iddest='$id' data-funcion='quitar_espe' data-div='trabajo'><i class='far fa-trash-alt'></i></button>";
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
