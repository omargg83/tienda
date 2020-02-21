<?php
	require_once("db_.php");
	$id=$_REQUEST['id'];
	$nombre="";
	$informacion="";

	if($id>0){
		$per = $db->producto_editar($id);
		$nombre=$per->nombre;
		$informacion=$per->informacion;
	}
?>
<div class='container'>
	<form id='form_comision' action='' data-lugar='a_productos/db_' data-destino='a_productos/editar' data-funcion='guardar_producto'>
		<div class='card'>
			<div class='card-header'>
				Producto <?php echo $id; ?>
			</div>
			<div class='card-body'>
				<input type="hidden" class="form-control" id="id" name='id' value="<?php echo $id; ?>">

			  <div class="form-row">
			    <div class="form-group col-md-6">
			      <label for="inputEmail4">Nombre</label>
			      <input type="text" class="form-control" id="nombre" name='nombre' placeholder="Nombre" value="<?php echo $nombre; ?>">
			    </div>
			  </div>
				<div class="form-row">
			    <div class="form-group col-md-12">
			      <label for="inputPassword4">Información</label>
			      <input type="text" class="form-control" id="informacion" name='informacion' placeholder="informacion" value="<?php echo $informacion; ?>">
			    </div>
			  </div>
			  <div class="form-group">
			    <label for="inputAddress">Address</label>
			    <input type="text" class="form-control" id="inputAddress" placeholder="1234 Main St">
			  </div>
			  <div class="form-group">
			    <label for="inputAddress2">Address 2</label>
			    <input type="text" class="form-control" id="inputAddress2" placeholder="Apartment, studio, or floor">
			  </div>
			  <div class="form-row">
			    <div class="form-group col-md-6">
			      <label for="inputCity">City</label>
			      <input type="text" class="form-control" id="inputCity">
			    </div>
			    <div class="form-group col-md-4">
			      <label for="inputState">State</label>
			      <select id="inputState" class="form-control">
			        <option selected>Choose...</option>
			        <option>...</option>
			      </select>
			    </div>
			    <div class="form-group col-md-2">
			      <label for="inputZip">Zip</label>
			      <input type="text" class="form-control" id="inputZip">
			    </div>
			  </div>
			  <div class="form-group">
			    <div class="form-check">
			      <input class="form-check-input" type="checkbox" id="gridCheck">
			      <label class="form-check-label" for="gridCheck">
			        Check me out
			      </label>
			    </div>
			  </div>
			</div>
			<div class='card-footer'>
	  		<button type="submit" class="btn btn-primary"><i class='far fa-save'></i>Guardar</button>
			</div>
	</form>
</div>
