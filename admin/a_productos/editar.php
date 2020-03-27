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
	$descripcion_larga="";
	$precio="";
	$moneda="";
	$tipoCambio="";
	$imagen="";
	$upc="";
	$activo="";
	$moneda="";
	$tipoCambio="";
	$preciof="";
	$existencia="";
	$precio_tic="";
	$interno="";
	$costo_envio="";

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
		$descripcion_larga=$per->descripcion_larga;
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
		$precio_tic=$per->precio_tic;
		$interno=$per->interno;
		$costo_envio=$per->costo_envio;
	}
	$bloqueo="";
	if ($interno==0){
		$bloqueo="readonly";
	}
	if ($interno==1 or $id==0){
		$bloqueo="";
	}
?>
<div class='container'>
	<form id='form_comision' action='' data-lugar='a_productos/db_' data-destino='a_productos/editar' data-funcion='guardar_producto'>
		<div class='card'>
			<div class='card-header'>
				Producto: <?php echo $nombre; ?>
			</div>
			<div class='card-body'>
				<input type="hidden" class="form-control" id="id" name='id' value="<?php echo $id; ?>">
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
							 <input type="text" class="form-control" id="clave" name='clave' placeholder="CLAVE" value="<?php echo $clave; ?>"  >
						 </div>

						 <div class="form-group col-md-4">
							 <label for="sku">Idproducto</label>
							 <input type="text" class="form-control" id="idProducto" name='idProducto' placeholder="CLAVE" value="<?php echo $idProducto; ?>"  >
						 </div>

					    <div class="form-group col-md-4">
					      <label for="nombre">Numero de parte</label>
					      <input type="text" class="form-control" id="numParte" name='numParte' placeholder="Numero de parte" value="<?php echo $numParte; ?>" <?php  echo $bloqueo;  ?>>
					    </div>
					  </div>

						<div class="form-row">
					    <div class="form-group col-md-12">
					      <label for="descripcion">Nombre</label>
					      <input type="text" class="form-control" id="nombre" name='nombre' placeholder="Nombre" value="<?php echo $nombre; ?>" <?php  echo $bloqueo;  ?>>
					    </div>
					  </div>
					</div>
				</div>

				<div class="form-row">
					<div class="form-group col-md-12">
						<label for="descripcion">Descripción corta</label>
						<input type="text" class="form-control" id="descripcion_corta" name='descripcion_corta' placeholder="Descripción corta" value="<?php echo $descripcion_corta; ?>" <?php  echo $bloqueo;  ?>>
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-md-12">
						<label for="descripcion">Descripción larga</label>
						<textarea rows='10' id='descripcion_larga' NAME='descripcion_larga'><?php echo $descripcion_larga; ?></textarea>
					</div>
				</div>

				<div class="form-row">
			    <div class="form-group col-md-6">
			      <label for="descripcion">Modelo</label>
			      <input type="text" class="form-control" id="modelo" name='modelo' placeholder="Nombre" value="<?php echo $modelo; ?>" <?php  echo $bloqueo;  ?>>
			    </div>

			    <div class="form-group col-md-6">
			      <label for="descripcion">Marca</label>
			      <input type="text" class="form-control" id="marca" name='marca' placeholder="Marca" value="<?php echo $marca; ?>" <?php  echo $bloqueo;  ?>>
			    </div>
			  </div>

				<div class="form-row">
			    <div class="form-group col-md-6">
			      <label for="descripcion">Categoria</label>
			      <input type="text" class="form-control" id="categoria" name='categoria' placeholder="Categoria" value="<?php echo $categoria; ?>" <?php  echo $bloqueo;  ?>>
			    </div>

			    <div class="form-group col-md-6">
			      <label for="descripcion">Subcategoria</label>
			      <input type="text" class="form-control" id="subcategoria" name='subcategoria' placeholder="Subcategoria" value="<?php echo $subcategoria; ?>" <?php  echo $bloqueo;  ?>>
			    </div>
			  </div>

				<div class="form-row">
					<div class="form-group col-md-2">
						<label for="existencia">Existencia </label>
						<input type="text" class="form-control" id="existencia" name='existencia' placeholder="Existencia" value="<?php echo $existencia; ?>" <?php  echo $bloqueo;  ?>>
					</div>

			    <div class="form-group col-md-3">
			      <label for="descripcion">Precio base</label>
			      <input type="text" class="form-control" id="precio" name='precio' placeholder="Precio" value="<?php echo $precio; ?>" <?php  echo $bloqueo;  ?>>
			    </div>

					<div class="form-group col-md-2">
			      <label for="descripcion">Moneda</label>
			      <input type="text" class="form-control" id="moneda" name='moneda' placeholder="Moneda" value="<?php echo $moneda; ?>" <?php  echo $bloqueo;  ?>>
			    </div>

					<div class="form-group col-md-2">
			      <label for="descripcion">Tipo de cambio</label>
			      <input type="text" class="form-control" id="tipoCambio" name='tipoCambio' placeholder="Tipo de cambio" value="<?php echo $tipoCambio; ?>" <?php  echo $bloqueo;  ?>>
			    </div>

					<div class="form-group col-md-3">
			      <label for="preciof">Precio CT</label>
			      <input type="text" class="form-control text-right" id="preciof" name='preciof' placeholder="Costo" value="<?php echo $preciof; ?>" <?php  echo $bloqueo;  ?>>
			    </div>

					<div class="form-group col-md-3">
			      <label for="preciof">Precio final</label>
			      <input type="text" class="form-control text-right" id="precio_tic" name='precio_tic' placeholder="Precio TIC" value="<?php echo $precio_tic; ?>" <?php  echo $bloqueo;  ?>>
			    </div>
					<div class="form-group col-md-3">
			      <label for="preciof">Costo de envío</label>
			      <input type="text" class="form-control text-right" id="costo_envio" name='costo_envio' placeholder="Costo de envío" value="<?php echo $costo_envio; ?>" <?php  echo $bloqueo;  ?>>
			    </div>
			  </div>
			</div>

			<div class='card-footer'>
				<div class='btn-group'>
		  		<button type="submit" class="btn btn-outline-secondary btn-sm"><i class='far fa-save'></i>Guardar</button>
					<button class='btn btn-outline-secondary btn-sm' id='lista_cat' data-lugar='a_productos/lista' title='regresar'><i class='fas fa-undo-alt'></i>Regresar</button>
				</div>
			</div>

		<?php
			if($id>0){
				echo "<div class='card-header'>";
					echo "Especificaciones";
				echo "</div>";
				echo "<div class='card-body'>";
					echo "<table class='table table-sm' style='font-size:10px'>";
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
				echo "<div class='card-header '> Galeria de imagenes</div>";
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
					echo "<div class='card-footer'>";
						echo "<div class='btn-group'>";
						echo "<button type='button' class='btn btn-outline-secondary btn-sm' data-toggle='modal' data-target='#myModal' id='fileup_respuesta' data-ruta='".$db->doc."' data-tabla='producto_img' data-campo='direccion' data-tipo='2' data-id='$id' data-keyt='idproducto' data-destino='a_productos/editar' data-iddest='$id' data-ext='.jpg,.png' ><i class='fas fa-cloud-upload-alt'></i>Subir</button>";

						echo "</div>";
					echo "</div>";
			}
		?>
	</form>
</div>

<script type="text/javascript">
	$(function() {
		baguetteBox.run('.baguetteBoxOne');
		$('#descripcion_larga').summernote({
			lang: 'es-ES',
			placeholder: 'Mensaje de texto',
			tabsize: 5,
			height: 200
		});

	});

</script>
