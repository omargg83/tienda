<?php
	require_once("db_.php");
	$id=$_REQUEST['id'];


	$per = $db->contacto($id);
	$nombre=$per->nombre;
	$correo=$per->correo;
	$telefono=$per->telefono;
	$mensaje=$per->mensaje;

?>
<div class='container'>
	<form id='form_comision' action='' data-lugar='a_clientes/db_' data-destino='a_clientes/editar' data-funcion='guardar_cliente'>
		<div class='card'>
			<div class='card-header'>
				Datos Contacto
			</div>
			<div class='card-body'>
				<input type="hidden" class="form-control form-control-sm" id="id" name='id' value="<?php echo $id; ?>">
				<div class="row">

			    <div class="col-4">
			      <label>Nombre</label>
			      <input type="text" class="form-control form-control-sm" id="nombre" name='nombre' placeholder="Nombre" value="<?php echo $nombre; ?>" required>
			    </div>

			    <div class="col-4">
			      <label>Correo</label>
			      <input type="text" class="form-control form-control-sm" id="correo" name='correo' placeholder="Correo" value="<?php echo $correo; ?>" required>
			    </div>

			    <div class="col-4">
			      <label>Teléfono</label>
			      <input type="text" class="form-control form-control-sm" id="telefono" name='telefono' placeholder="Teléfono" value="<?php echo $telefono; ?>" >
			    </div>
			    <div class="col-12">
			      <label>Mensaje</label>
			      <textarea class="form-control form-control-sm"><?php echo $mensaje; ?></textarea>
			    </div>
			  </div>

			</div>
			<div class='card-footer'>
				<div class='btn-group'>
					<button class='btn btn-outline-secondary btn-sm' id='lista_cat' data-lugar='a_contacto/lista' title='regresar'><i class='fas fa-undo-alt'></i>Regresar</button>
				</div>
			</div>
		</div>


	</form>
</div>
