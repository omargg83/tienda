<?php
	require_once("db_.php");
	$id=$_REQUEST['id'];
	$nombre="";
	$descripcion="";
	$sku="";
	$precio="";
	$cantidad="";
	if($id>0){
		$per = $db->producto_editar($id);
		$cat = $db->producto_categoria($id);
		$nombre=$per->nombre;
		$descripcion=$per->descripcion;
		$sku=$per->sku;
		$precio=$per->precio;
		$cantidad=$per->cantidad;
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
				 <div class="form-group col-md-4">
					 <label for="sku">SKU</label>
					 <input type="text" class="form-control" id="sku" name='sku' placeholder="SKU" value="<?php echo $sku; ?>">
				 </div>

			    <div class="form-group col-md-6">
			      <label for="nombre">Nombre</label>
			      <input type="text" class="form-control" id="nombre" name='nombre' placeholder="Nombre" value="<?php echo $nombre; ?>">
			    </div>
			  </div>

				<div class="form-row">
			    <div class="form-group col-md-12">
			      <label for="descripcion">Información</label>
			      <input type="text" class="form-control" id="descripcion" name='descripcion' placeholder="Descripción" value="<?php echo $descripcion; ?>">
			    </div>
			  </div>
				<div class="form-row">
			    <div class="form-group col-md-4">
			      <label for="precio">Precio</label>
			      <input type="text" class="form-control" id="precio" name='precio' placeholder="Precio" value="<?php echo $precio; ?>">
			    </div>

			    <div class="form-group col-md-4">
			      <label for="cantidad">Cantidad</label>
			      <input type="text" class="form-control" id="cantidad" name='cantidad' placeholder="Cantidad" value="<?php echo $cantidad; ?>">
			    </div>
			  </div>
			</div>

			<div class='card-footer'>
				<div class='btn-group'>
		  		<button type="submit" class="btn btn-outline-secondary btn-sm"><i class='far fa-save'></i>Guardar</button>
		  		<button type='button' class='btn btn-outline-secondary btn-sm' id='winmodal_pass' data-id='<?php echo $id; ?>' data-lugar='a_productos/form_categoria' title='Categorias' ><i class="fab fa-buffer"></i>Categorias</button>
					<button class='btn btn-outline-secondary btn-sm' id='lista_cat' data-lugar='a_productos/lista' title='regresar'><i class='fas fa-undo-alt'></i>Regresar</button>
				</div>
			</div>

			<div class='card-body'>
				<?php
					if($id>0){
						foreach($cat as $key){
							echo "<div class='input-group mb-3'>";
							  echo "<div class='input-group-prepend'>
							    <button class='btn btn-outline-secondary btn-sm' type='button' onclick='cat_borra(".$key->idcatprod.")'><i class='far fa-trash-alt'></i></button>
							  </div>
							  <input type='text' class='form-control' placeholder='' aria-label='' aria-describedby='basic-addon1' value='".$key->descripcion."' readonly>
							</div>";
						}
					}

				 ?>
			</div>


	</form>
</div>
