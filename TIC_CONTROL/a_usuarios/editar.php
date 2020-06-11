<?php
	require_once("db_.php");
	$id=$_REQUEST['id'];
	$nombre="";
	$autoriza="";
	$nivel="";
	$correo_xptic="";
	$hash="";

	if($id>0){
		$per = $db->usuario_editar($id);
		$nombre=$per->nombre;
		$correo_xptic=$per->correo_xptic;
		$autoriza=$per->autoriza;
		$nivel=$per->nivel;
		$hash=$per->hash;
	}
?>
<div class='container'>
	<form id='form_comision' action='' data-lugar='a_usuarios/db_' data-destino='a_usuarios/editar' data-funcion='guardar_usuario'>
		<div class='card'>
			<div class='card-header'>
				Usuarios <?php echo $id; ?>
			</div>
			<div class='card-body'>
				<input type="hidden" class="form-control" id="id" name='id' value="<?php echo $id; ?>">
			  <div class="form-row">
			    <div class="form-group col-md-4">
			      <label>Nombre</label>
			      <input type="text" class="form-control" id="nombre" name='nombre' placeholder="Nombre" value="<?php echo $nombre; ?>" >
			    </div>
					<div class="form-group col-md-4">
			      <label>Usuario</label>
			      <input type="text" class="form-control" id="correo_xptic" name='correo_xptic' placeholder="Usuario" value="<?php echo $correo_xptic; ?>" >
			    </div>


					<div class="form-group col-md-4">
			      <label>Nivel</label>
						<select id='nivel' name='nivel' class='form-control'>
							<?php
								echo "<option value=1 "; if ($nivel==1) echo " selected"; echo ">Nivel 1- Administrador</option>";
								echo "<option value=2 "; if ($nivel==2) echo " selected"; echo ">Nivel 2- Nivel 2</option>";
							?>
						</select>
			    </div>

					<div class="form-group col-md-4">
			      <label>Autorizado</label>
						<select id='autoriza' name='autoriza' class='form-control'>
							<?php
								echo "<option value=1 "; if ($autoriza==1) echo " selected"; echo ">Autoriza</option>";
								echo "<option value=0 "; if ($autoriza==0) echo " selected"; echo ">Sin acceso</option>";
							?>
						</select>
			    </div>

			  </div>

			</div>
			<div class='card-footer'>
				<div class='btn-group'>
		  		<button type="submit" class="btn btn-outline-secondary btn-sm"><i class='far fa-save'></i>Guardar</button>
					<?php
						if($id>0){
							echo "<button type='button' class='btn btn-outline-secondary btn-sm' id='winmodal_pass' data-id='$hash' data-id2='$id' data-lugar='a_usuarios/form_update' title='Cambiar contraseña' ><i class='fas fa-key'></i>Contraseña</button>";
						}
					?>
					<button class='btn btn-outline-secondary btn-sm' id='lista_cat' data-lugar='a_usuarios/lista' title='regresar'><i class='fas fa-undo-alt'></i>Regresar</button>
				</div>
			</div>
	</form>
</div>
