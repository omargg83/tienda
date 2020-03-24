<?php
	require_once("../a_productos/db_.php");
	   $id=$_REQUEST['id'];
	   $idpedido=$_REQUEST['idpedido'];

		$per = $db->producto_editar($id);
		$idProducto=$per->idProducto;
    $espe = $db->producto_espe($idProducto);
		$alma = $db->producto_exist($idProducto,1);
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
		$imagen=$per->img;
		$upc=$per->upc;
		$activo=$per->activo;
		$moneda=$per->moneda;
		$tipoCambio=$per->tipoCambio;
		$preciof=$per->preciof;

?>

	<form id='form_agregaprod' action='' data-lugar='a_pedidos/db_' data-destino='a_pedidos/editar' data-funcion='producto_add'>
			<div class='card-body'>
				<input type="hidden" class="form-control" id="id" name='id' value="<?php echo $id; ?>">
				<input type="hidden" class="form-control" id="idpedido" name='idpedido' value="<?php echo $idpedido; ?>">
				<div class='row'>
					<div class='col-3'>
						<?php
							echo "<img src='../admin/a_imagen/$imagen' width='100%' />";
						 ?>
					</div>
					<div class='col-9'>
						<div class="form-row">
						 <div class="form-group col-md-4">
							 <label for="sku">Clave</label>
							 <input type="text" class="form-control" id="clave" name='clave' placeholder="CLAVE" value="<?php echo $clave; ?>" readonly>
						 </div>

						 <div class="form-group col-md-4">
							 <label for="sku">Idproducto</label>
							 <input type="text" class="form-control" id="idProducto" name='idProducto' placeholder="CLAVE" value="<?php echo $idProducto; ?>" readonly>
						 </div>

					    <div class="form-group col-md-4">
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
					    <div class="form-group col-md-12">
					      <label for="descripcion">Descripción corta</label>
					      <input type="text" class="form-control" id="descripcion_corta" name='descripcion_corta' placeholder="Descripción corta" value="<?php echo $descripcion_corta; ?>" readonly>
					    </div>
					  </div>
					</div>
				</div>

				<div class="form-row">
			    <div class="form-group col-md-3">
			      <label for="descripcion">Modelo</label>
			      <input type="text" class="form-control" id="modelo" name='modelo' placeholder="Nombre" value="<?php echo $modelo; ?>" readonly>
			    </div>

			    <div class="form-group col-md-3">
			      <label for="descripcion">Marca</label>
			      <input type="text" class="form-control" id="marca" name='marca' placeholder="Marca" value="<?php echo $marca; ?>" readonly>
			    </div>

			    <div class="form-group col-md-3">
			      <label for="descripcion">Categoria</label>
			      <input type="text" class="form-control" id="categoria" name='categoria' placeholder="Categoria" value="<?php echo $categoria; ?>" readonly>
			    </div>

			    <div class="form-group col-md-3">
			      <label for="descripcion">Subcategoria</label>
			      <input type="text" class="form-control" id="subcategoria" name='subcategoria' placeholder="Subcategoria" value="<?php echo $subcategoria; ?>" readonly>
			    </div>
			  </div>

				<div class="form-row">

          <div class="form-group col-md-3">
            <label for="preciof">Costo </label>
            <input type="text" class="form-control" id="preciof" name='preciof' placeholder="Costo" value="<?php echo $preciof; ?>" readonly>
          </div>
			  </div>
			</div>
		<?php
			if($id>0){
					echo "<table class='table table-sm' style='font-size:10px;'>";
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

				$c_archivoc = $db->imagen($id);
				////////////////////////////////////////////////////////////////imagenes extra
				echo "<div class='card-header '> Imagenes extra</div>";
					echo "<div class='card-body'>";
						echo "<div class='row' >";
							echo "<div class='baguetteBoxOne gallery'>";
								foreach($c_archivoc as $key){
									echo "<div style='border:.1px solid silver;float:left;margin:10px'>";

										if(file_exists("../".$db->doc.$key['direccion'])){
											echo "<a href='".$db->doc.$key['direccion']."' data-caption='Correspondencia'>";
											echo "<img src='".$db->doc.$key['direccion']."' alt='Correspondencia' >";
											echo "</a><br>";

											echo "<button class='btn btn-outline-secondary btn-sm' title='Eliminar archivo'
											id='delfile_orden'
											data-ruta='".$db->doc.$key['direccion']."'
											data-keyt='id'
											data-key='".$key['id']."'
											data-tabla='producto_img'
											data-campo='direccion'
											data-tipo='2'
											data-iddest='$id'
											data-divdest='trabajo'
											data-borrafile='1'
											data-dest='a_productos/editar.php?id='
											><i class='far fa-trash-alt'></i></button>";
										}
									echo "</div>";
								}
							echo "</div>";
						echo "</div>";
					echo "</div>";
			}
      echo "<button type='submit' class='btn btn-outline-secondary btn-sm'><i class='far fa-save'></i>Guardar</button>";
		?>

	</form>


<script type="text/javascript">
	$(function() {
		baguetteBox.run('.baguetteBoxOne');
	});
</script>
