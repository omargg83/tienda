<?php
	require_once("db_.php");
	$id=$_REQUEST['id'];
	$nombre="";
	$descripcion="";
	$sku="";
	if($id>0){
		$per = $db->producto_editar($id);
		$nombre=$per->nombre;
		$descripcion=$per->descripcion;
		$sku=$per->sku;
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
					 <label for="sku">SKU</label>
					 <input type="text" class="form-control" id="sku" name='sku' placeholder="SKU" value="<?php echo $sku; ?>">
				 </div>
			 </div>

			  <div class="form-row">
			    <div class="form-group col-md-6">
			      <label for="inputEmail4">Nombre</label>
			      <input type="text" class="form-control" id="nombre" name='nombre' placeholder="Nombre" value="<?php echo $nombre; ?>">
			    </div>
			  </div>
				<div class="form-row">
			    <div class="form-group col-md-12">
			      <label for="inputPassword4">Información</label>
			      <input type="text" class="form-control" id="descripcion" name='descripcion' placeholder="Descripción" value="<?php echo $descripcion; ?>">
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
				<div class='btn-group'>
		  		<button type="submit" class="btn btn-outline-secondary btn-sm"><i class='far fa-save'></i>Guardar</button>
					<button class='btn btn-outline-secondary btn-sm' id='lista_cat' data-lugar='a_categorias/lista' title='regresar'><i class='fas fa-undo-alt'></i>Regresar</button>
				</div>
			</div>
	</form>
</div>
