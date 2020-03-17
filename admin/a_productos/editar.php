<?php
	require_once("db_.php");
	$id=$_REQUEST['id'];
 	$idProducto="";
 	$clave="";
 	$numParte="";
 	$nombre="";

	$modelo="";
	$idMarca="";
	$marca="";
	$idCategoria="";
	$categoria="";
	$idSubCategoria="";
	$subcategoria="";
	$descripcion_corta="";
	$precio="";
	$moneda="";
	$tipoCambio="";
	$imagen="";
	$upc="";
	$activo="";

	if($id>0){
		$per = $db->producto_editar($id);
		$idProducto=$per->idProducto;
		$nombre=$per->nombre;
		$clave=$per->clave;
		$numParte=$per->numParte;
		$nombre=$per->nombre;

		$modelo=$per->modelo;
		$idMarca=$per->idMarca;
		$marca=$per->marca;
		$idCategoria=$per->idCategoria;
		$categoria=$per->categoria;
		$idSubCategoria=$per->idSubCategoria;
		$idSubCategoria=$per->idSubCategoria;
		$subcategoria=$per->subcategoria;
		$descripcion_corta=$per->descripcion_corta;
		$precio=$per->precio;
		$moneda=$per->moneda;
		$tipoCambio=$per->tipoCambio;
		$imagen=$per->imagen;
		$upc=$per->upc;
		$activo=$per->activo;
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
					 <label for="sku">Clave</label>
					 <input type="text" class="form-control" id="clave" name='clave' placeholder="CLAVE" value="<?php echo $clave; ?>" readonly>
				 </div>

				 <div class="form-group col-md-4">
					 <label for="sku">Idproducto</label>
					 <input type="text" class="form-control" id="idProducto" name='idProducto' placeholder="CLAVE" value="<?php echo $idProducto; ?>" readonly>
				 </div>

			    <div class="form-group col-md-6">
			      <label for="nombre">Numero de parte</label>
			      <input type="text" class="form-control" id="numParte" name='numParte' placeholder="Numero de parte" value="<?php echo $numParte; ?>" readonly>
			    </div>
			  </div>

				<div class="form-row">
			    <div class="form-group col-md-12">
			      <label for="descripcion">Nombre</label>
			      <input type="text" class="form-control" id="nombre" name='nombre' placeholder="Nombre" value="<?php echo $nombre; ?>" readonly>
			    </div>
			  </div>

				<div class="form-row">
			    <div class="form-group col-md-4">
			      <label for="descripcion">Modelo</label>
			      <input type="text" class="form-control" id="modelo" name='modelo' placeholder="Nombre" value="<?php echo $modelo; ?>" readonly>
			    </div>

			    <div class="form-group col-md-4">
			      <label for="descripcion">idMarca</label>
			      <input type="text" class="form-control" id="idmarca" name='idmarca' placeholder="idMarca" value="<?php echo $idMarca; ?>" readonly>
			    </div>

			    <div class="form-group col-md-4">
			      <label for="descripcion">Marca</label>
			      <input type="text" class="form-control" id="marca" name='marca' placeholder="Marca" value="<?php echo $marca; ?>" readonly>
			    </div>
			  </div>

				<div class="form-row">
			    <div class="form-group col-md-12">
			      <label for="descripcion">Id Categoria</label>
			      <input type="text" class="form-control" id="idCategoria" name='idCategoria' placeholder="Id Categoria" value="<?php echo $idCategoria; ?>" readonly>
			    </div>
			  </div>

				<div class="form-row">
			    <div class="form-group col-md-12">
			      <label for="descripcion">Categoria</label>
			      <input type="text" class="form-control" id="categoria" name='categoria' placeholder="Categoria" value="<?php echo $categoria; ?>" readonly>
			    </div>
			  </div>

				<div class="form-row">
			    <div class="form-group col-md-6">
			      <label for="descripcion">ID Subcategoria</label>
			      <input type="text" class="form-control" id="idSubCategoria" name='idSubCategoria' placeholder="ID Subcategoria" value="<?php echo $idSubCategoria; ?>" readonly>
			    </div>

			    <div class="form-group col-md-6">
			      <label for="descripcion">Subcategoria</label>
			      <input type="text" class="form-control" id="subcategoria" name='subcategoria' placeholder="Subcategoria" value="<?php echo $subcategoria; ?>" readonly>
			    </div>
			  </div>

				<div class="form-row">
			    <div class="form-group col-md-12">
			      <label for="descripcion">Descripción corta</label>
			      <input type="text" class="form-control" id="descripcion_corta" name='descripcion_corta' placeholder="Descripción corta" value="<?php echo $descripcion_corta; ?>" readonly>
			    </div>
			  </div>

				<div class="form-row">
			    <div class="form-group col-md-12">
			      <label for="descripcion">Precio</label>
			      <input type="text" class="form-control" id="precio" name='precio' placeholder="Precio" value="<?php echo $precio; ?>" readonly>
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
