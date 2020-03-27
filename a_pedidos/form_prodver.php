<?php
	require_once("../a_productos/db_.php");
	   $id=$_REQUEST['id'];
	   $idpedido=$_REQUEST['idpedido'];

		$per = $db->producto_editar($id);
		$idProducto=$per->idProducto;
    $espe = $db->producto_espe($idProducto);
		$alma = $db->producto_exist($idProducto,2);
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
		$existencia=$per->existencia;
		/*
				echo "remoto:".$_SESSION['remoto'];
				if($_SESSION['remoto']==1){
					$resp = crearNuevoToken();
					if($resp){
						$tok=$resp->token;
						$servicio = "existencia/$clave/TOTAL";
						$metodo="GET";
						$resp =servicioApi($metodo,$servicio,NULL,$tok);
						$existencia=$resp->existencia_total;
					}
				}
		*/
?>

	<form id='form_agregaprod' action='' data-lugar='a_pedidos/db_' data-destino='a_pedidos/editar' data-funcion='producto_add'>
			<div class='card-body'>
				<input type="hidden" class="form-control form-control-sm" id="id" name='id' value="<?php echo $id; ?>">
				<input type="hidden" class="form-control form-control-sm" id="idpedido" name='idpedido' value="<?php echo $idpedido; ?>">
				<div class='row'>
					<div class='col-3'>
						<?php
							echo "<img src='../admin/a_imagen/$imagen' width='100%' />";
							$c_archivoc = $db->imagen($id);
							////////////////////////////////////////////////////////////////imagenes extra
							echo "<div class='row'>";
								echo "<div class='baguetteBoxOne '>";
									echo "<div style='border:.1px solid silver;float:left;margin:10px'>";
										echo "<a href='../admin/a_imagen/$imagen' data-caption='Producto' >";
											echo "<img src='../admin/a_imagen/$imagen' alt='Producto' width='30px' height='30px'/>";
										echo "</a>";
									echo "</div>";

									foreach($c_archivoc as $key){
										echo "<div style='border:.1px solid silver;float:left;margin:10px'>";
											if(file_exists("../".$db->doc.$key['direccion'])){
												echo "<a href='".$db->doc.$key['direccion']."' data-caption='Producto'>";
												echo "<img src='".$db->doc.$key['direccion']."' alt='Producto' width='30px' height='30px'>";
												echo "</a><br>";
											}
										echo "</div>";
									}
								echo "</div>";
							echo "</div>";



						 ?>
					</div>
					<div class='col-9'>
						<div class="form-row">
						 <div class="form-group col-md-4">
							 <label for="sku">Clave</label>
							 <input type="text" class="form-control form-control-sm" id="clave" name='clave' placeholder="CLAVE" value="<?php echo $clave; ?>" readonly>
						 </div>

						 <div class="form-group col-md-4">
							 <label for="sku">Idproducto</label>
							 <input type="text" class="form-control form-control-sm" id="idProducto" name='idProducto' placeholder="CLAVE" value="<?php echo $idProducto; ?>" readonly>
						 </div>

					    <div class="form-group col-md-4">
					      <label for="nombre">Numero de parte</label>
					      <input type="text" class="form-control form-control-sm" id="numParte" name='numParte' placeholder="Numero de parte" value="<?php echo $numParte; ?>" readonly>
					    </div>
					  </div>

						<div class="form-row">
					    <div class="form-group col-md-12">
					      <label for="descripcion">Nombre</label>
					      <input type="text" class="form-control form-control-sm" id="nombre" name='nombre' placeholder="Nombre" value="<?php echo $nombre; ?>" readonly>
					    </div>
					  </div>

						<div class="form-row">
					    <div class="form-group col-md-12">
					      <label for="descripcion">Descripción corta</label>
					      <input type="text" class="form-control form-control-sm" id="descripcion_corta" name='descripcion_corta' placeholder="Descripción corta" value="<?php echo $descripcion_corta; ?>" readonly>
					    </div>
					  </div>
					</div>
				</div>

				<div class="form-row">
			    <div class="form-group col-md-3">
			      <label for="descripcion">Modelo</label>
			      <input type="text" class="form-control form-control-sm" id="modelo" name='modelo' placeholder="Nombre" value="<?php echo $modelo; ?>" readonly>
			    </div>

			    <div class="form-group col-md-3">
			      <label for="descripcion">Marca</label>
			      <input type="text" class="form-control form-control-sm" id="marca" name='marca' placeholder="Marca" value="<?php echo $marca; ?>" readonly>
			    </div>

			    <div class="form-group col-md-3">
			      <label for="descripcion">Categoria</label>
			      <input type="text" class="form-control form-control-sm" id="categoria" name='categoria' placeholder="Categoria" value="<?php echo $categoria; ?>" readonly>
			    </div>

			    <div class="form-group col-md-3">
			      <label for="descripcion">Subcategoria</label>
			      <input type="text" class="form-control form-control-sm" id="subcategoria" name='subcategoria' placeholder="Subcategoria" value="<?php echo $subcategoria; ?>" readonly>
			    </div>
			  </div>

				<div class="form-row">

          <div class="form-group col-md-3">
            <label for="preciof">Costo </label>
            <input type="text" class="form-control form-control-sm" id="preciof" name='preciof' placeholder="Costo" value="<?php echo $preciof; ?>" readonly>
          </div>

					<div class="form-group col-md-3">
            <label for="preciof">Existencias</label>
            <input type="text" class="form-control form-control-sm" id="existe" name='existe' placeholder="Existencias" value="<?php echo $existencia; ?>" readonly>
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
			}
      echo "<button type='submit' class='btn btn-outline-secondary btn-sm'><i class='fas fa-plus'></i>Agregar</button>";
		?>

	</form>


<script type="text/javascript">
	$(function() {
		baguetteBox.run('.baguetteBoxOne');
	});
</script>
