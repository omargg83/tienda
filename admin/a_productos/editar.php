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
	$moneda="";
	$tipoCambio="";

	if($id>0){
		$per = $db->producto_editar($id);
		$idProducto=$per->idProducto;
		$alma = $db->producto_exist($idProducto);
		$espe = $db->producto_espe($idProducto);
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
		$moneda=$per->moneda;
		$tipoCambio=$per->tipoCambio;
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
				<div class='row'>
					<div class='col-3'>
						<?php
							echo "<img src='$imagen' width='100%' />";
						 ?>
					</div>
					<div class='col-9'>
						<div class="form-row">
						 <div class="form-group col-md-3">
							 <label for="sku">Clave</label>
							 <input type="text" class="form-control" id="clave" name='clave' placeholder="CLAVE" value="<?php echo $clave; ?>" readonly>
						 </div>

						 <div class="form-group col-md-3">
							 <label for="sku">Idproducto</label>
							 <input type="text" class="form-control" id="idProducto" name='idProducto' placeholder="CLAVE" value="<?php echo $idProducto; ?>" readonly>
						 </div>

					    <div class="form-group col-md-3">
					      <label for="nombre">Numero de parte</label>
					      <input type="text" class="form-control" id="numParte" name='numParte' placeholder="Numero de parte" value="<?php echo $numParte; ?>" readonly>
					    </div>
					  </div>

						<div class="form-row">
					    <div class="form-group col-md-9">
					      <label for="descripcion">Nombre</label>
					      <input type="text" class="form-control" id="nombre" name='nombre' placeholder="Nombre" value="<?php echo $nombre; ?>" readonly>
					    </div>
					  </div>

						<div class="form-row">
					    <div class="form-group col-md-12">
					      <label for="descripcion">Descripción corta</label>
					      <input type="text" class="form-control" id="descripcion_corta" name='descripcion_corta' placeholder="Descripción corta" value="<?php echo $descripcion_corta; ?>" readonly>
					    </div>
					  </div>
					</div>
				</div>





				<div class="form-row">
			    <div class="form-group col-md-6">
			      <label for="descripcion">Modelo</label>
			      <input type="text" class="form-control" id="modelo" name='modelo' placeholder="Nombre" value="<?php echo $modelo; ?>" readonly>
			    </div>

			    <div class="form-group col-md-6">
			      <label for="descripcion">Marca</label>
			      <input type="text" class="form-control" id="marca" name='marca' placeholder="Marca" value="<?php echo $marca; ?>" readonly>
			    </div>
			  </div>

				<div class="form-row">
			    <div class="form-group col-md-6">
			      <label for="descripcion">Categoria</label>
			      <input type="text" class="form-control" id="categoria" name='categoria' placeholder="Categoria" value="<?php echo $categoria; ?>" readonly>
			    </div>

			    <div class="form-group col-md-6">
			      <label for="descripcion">Subcategoria</label>
			      <input type="text" class="form-control" id="subcategoria" name='subcategoria' placeholder="Subcategoria" value="<?php echo $subcategoria; ?>" readonly>
			    </div>
			  </div>

				<div class="form-row">
			    <div class="form-group col-md-3">
			      <label for="descripcion">Precio</label>
			      <input type="text" class="form-control" id="precio" name='precio' placeholder="Precio" value="<?php echo $precio; ?>" readonly>
			    </div>

					<div class="form-group col-md-3">
			      <label for="descripcion">Moneda</label>
			      <input type="text" class="form-control" id="moneda" name='moneda' placeholder="Moneda" value="<?php echo $moneda; ?>" readonly>
			    </div>

					<div class="form-group col-md-3">
			      <label for="descripcion">Tipo de cambio</label>
			      <input type="text" class="form-control" id="tipoCambio" name='tipoCambio' placeholder="Tipo de cambio" value="<?php echo $tipoCambio; ?>" readonly>
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

		<?php
			if($id>0){

				echo "<div class='card-header'>";
					echo "Existencias";
				echo "</div>";
				echo "<div class='card-body'>";
					echo "<table class='table table-sm'>";
						foreach($alma as $key){
							 $almacen=$db->almacen_busca($key->almacen);
							echo "<tr>";
								echo "<td>";
								echo "(".$key->almacen.")";
								echo $almacen->sucursal;
								echo "</td>";

								echo "<td>";
								echo $key->existencia;
								echo "</td>";
							echo "</tr>";
						}
					echo "</table>";
				echo "</div>";
			}

			if($id>0){
				echo "<div class='card-header'>";
					echo "Especificaciones";
				echo "</div>";
				echo "<div class='card-body'>";
					echo "<table class='table table-sm'>";
					foreach($espe as $key){
						echo "<tr>";

							echo "<td>";
							echo $key->tipo;
							echo "</td>";

							echo "<td>";
							echo $key->valor;
							echo "</td>";

						echo "</tr>";
					}
					echo "</table>";
				echo "</div>";
			}
		?>
	</form>
</div>
