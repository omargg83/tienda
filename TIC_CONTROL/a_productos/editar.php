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
	$moneda="";
	$tipoCambio="";
	$preciof="";
	$existencia="";
	$precio_tic="";
	$activo=1;
	$interno=1;
	$envio_costo="";
	$precio_tipo="1";
	$envio_tipo="";

	$cb_ofertasemana=0;
	$cb_prodsemana=0;
	$cb_destacados=0;
	$precio_oferta=0;
	$activo=0;

	if($id>0){
		$per = $db->producto_editar($id);
		$idProducto=$per->idProducto;
		$alma = $db->producto_exist($id);
		$espe = $db->producto_espe($id);
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
		$origen=$per->imagen;
		$upc=$per->upc;
		$activo=$per->activo;
		$moneda=$per->moneda;
		$tipoCambio=$per->tipoCambio;
		$preciof=$per->preciof;
		$existencia=$per->existencia;
		$precio_tic=$per->precio_tic;
		$envio_costo=$per->envio_costo;
		$interno=$per->interno;
		$precio_tipo=$per->precio_tipo;
		$envio_tipo=$per->envio_tipo;
		$cb_ofertasemana=$per->cb_ofertasemana;
		$cb_prodsemana=$per->cb_prodsemana;
		$cb_destacados=$per->cb_destacados;
		$precio_oferta=$per->precio_oferta;
		$activo=$per->activo;
		$modificado=$per->modificado;
	}
	else{
		$interno=1;
		$activo=1;
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
				<?php echo $nombre;
					if($interno==0){
						echo " (CT)";
					}
					else{
						echo " (TIC)";
					}
				?>
			</div>
			<div class='card-body'>
				<input type="hidden" class="form-control form-control-sm" id="id" name='id' value="<?php echo $id; ?>" readonly>
				<input type="hidden" class="form-control form-control-sm" id="interno" name='interno' value="<?php echo $interno; ?>" readonly>
				<div class='row'>
					<div class='col-3'>
						<?php
							$a="?id=".rand(1,1500);
							if(file_exists("../".$db->doc1."$imagen")){
								echo "<img src='".$db->doc1.$imagen."$a' width='100%' /><br>";
							}
							//if($interno==1 and $id>0){
								echo "<button type='button' class='btn btn-outline-secondary btn-sm' data-toggle='modal' data-target='#myModal' id='fileup_imagenx' data-ruta='a_imagen/' data-tabla='productos' data-campo='img' data-tipo='1' data-id='$id' data-keyt='id' data-destino='a_productos/editar' data-iddest='$id' data-ext='.jpg,.png' ><i class='fas fa-cloud-upload-alt'></i>Agregar imagen</button>";
							//}
						 ?>
					</div>
					<div class='col-9'>
						<div class="row">
						 <div class="col-4">
							 <label for="sku">Clave</label>
							 <input type="text" class="form-control form-control-sm" id="clave" name='clave' placeholder="CLAVE" value="<?php echo $clave; ?>" <?php  echo $bloqueo;  ?> >
						 </div>
						 <?php
							 if($interno!=1){
								 echo "<div class='col-4'>";
								 	echo "<label for='sku'>Idproducto (CT)</label>";
								 	echo "<input type='text' class='form-control form-control-sm' id='idProducto' name='idProducto' placeholder='CLAVE' value='$idProducto' $bloqueo>";
								 echo "</div>";

						    echo "<div class='col-4'>";
						      echo "<label for='nombre'>Numero de parte </label>";
						      echo "<input type='text' class='form-control form-control-sm' id='numParte' name='numParte' placeholder='Numero de parte' value='$numParte' $bloqueo>";
						    echo "</div>";
							}
							?>
					  </div>

						<div class="row">
					    <div class="col-9">
					      <label for="descripcion">Nombre</label>
					      <input type="text" class="form-control form-control-sm" id="nombre" name='nombre' placeholder="Nombre" value="<?php echo $nombre; ?>" <?php  echo $bloqueo;  ?>>
					    </div>

							<?php
								echo "<div class='col-3'>";
									echo "<label>Activo</label>";
									echo "<select id='activo' name='activo' class='form-control form-control-sm' $bloqueo>";
										echo "<option value='0'"; if($activo=='0'){ echo " selected"; } echo ">Inactivo</option>";
										echo "<option value='1'"; if($activo=='1'){ echo " selected"; } echo ">Activo</option>";
									echo "</select>";
								echo "</div>";
							?>

							<div class="col-12">
								<label for="descripcion">Descripción corta</label>
								<input type="text" class="form-control form-control-sm" id="descripcion_corta" name='descripcion_corta' placeholder="Descripción corta" value="<?php echo $descripcion_corta; ?>" <?php  echo $bloqueo;  ?>>
							</div>
							<?php
 						if($interno!=1){
						 	 ?>
							<div class="col-12">
								<label for="originalx">Imagen original</label>
								<input type="text" class="form-control form-control-sm" id="originalx" name='originalx' placeholder="Imagen original" value="<?php echo $origen; ?>" readonly>
							</div>
							<?php
							}
						?>
					  </div>
					</div>
				</div>

				<div class="row">

				</div>
				<div class="row">
					<div class="col-12">
						<label for="descripcion">Descripción larga</label>
						<textarea rows='10' id='descripcion_larga' NAME='descripcion_larga'><?php echo $descripcion_larga; ?></textarea>
					</div>
				</div>

				<div class="row">
			    <div class="col-6">
			      <label for="descripcion">Modelo</label>
			      <input type="text" class="form-control form-control-sm" id="modelo" name='modelo' placeholder="Nombre" value="<?php echo $modelo; ?>" <?php  echo $bloqueo;  ?>>
			    </div>

			    <div class="col-6">
			      <label for="descripcion">Marca</label>
			      <input type="text" class="form-control form-control-sm" id="marca" name='marca' placeholder="Marca" value="<?php echo $marca; ?>" <?php  echo $bloqueo;  ?>>
			    </div>
			  </div>

				<?php
					echo "<div class='row'>";
				    echo "<div class='col-6'>";
				      echo "<label for='categoria'>Categoria</label>";
							echo "<select id='categoria' name='categoria' class='form-control form-control-sm' $bloqueo required onchange='subcat_cmb()'>";
								echo "<option value='' disabled selected>Seleccione una opción</option>";
								foreach($db->categoria_ct() as $key){
									echo "<option value='".$key->categoria."'";  if($key->categoria==$categoria){ echo " selected"; } echo ">".$key->categoria."</option>";
								}
							echo "</select>";
				    echo "</div>";

				    echo "<div class='col-6' id='subcatdiv'>";
				      echo "<label for='subcategoria'>Subcategoria</label>";
							echo "<select id='subcategoria' name='subcategoria' class='form-control form-control-sm' $bloqueo required>";
								echo "<option value='' disabled selected>Seleccione una opción</option>";
								foreach($db->subcategoria_ct($categoria) as $key){
									echo "<option value='".$key->subcategoria."'";  if($key->subcategoria==$subcategoria){ echo " selected"; } echo ">".$key->subcategoria."</option>";
								}
							echo "</select>";
				    echo "</div>";
				  echo "</div>";
				?>

				<hr>
				<?php
				/*
				<div class="row">
			    <div class="col-2">
			      <label for="descripcion">Precio base (CT)</label>
			      <input type="text" class="form-control form-control-sm" id="precio" name='precio' placeholder="Precio" value="<?php echo $precio; ?>" <?php  echo $bloqueo;  ?>>
			    </div>

					<div class="col-3">
			      <label for="descripcion">Moneda (CT)</label>
						<?php
							echo "<select id='estado' name='estado' class='form-control form-control-sm' $bloqueo>";
								echo "<option value='MXN'"; if($moneda=='MXN'){ echo " selected"; } echo ">MXN</option>";
								echo "<option value='USD'"; if($moneda=='USD'){ echo " selected"; } echo ">USD</option>";
							echo "</select>";
						?>
			    </div>

					<div class="col-3">
			      <label for="descripcion">Tipo de cambio (CT)</label>
			      <input type="text" class="form-control form-control-sm" id="tipoCambio" name='tipoCambio' placeholder="Tipo de cambio" value="<?php echo $tipoCambio; ?>" <?php  echo $bloqueo;  ?>>
			    </div>
				</div>
				<hr>
				*/
				?>

				<div class="row">
					<?php
					if($interno==0){
						echo "<div class='col-3'>";
							echo "<label for='preciof'>Precio original CT</label>";
							echo "<input type='text' class='form-control form-control-sm text-right' id='preciof' name='preciof' placeholder='Costo' value='$preciof' $bloqueo>";
						echo "</div>";
					}

					?>

					<div class="col-3">
			      <label for="preciof">Precio manual</label>
			      <input type="text" class="form-control form-control-sm text-right" id="precio_tic" name='precio_tic' placeholder="Precio personalizado" value="<?php echo $precio_tic; ?>">
			    </div>



					<div class="col-3">
			      <label for="preciof">Envio manual</label>
			      <input type="text" class="form-control form-control-sm text-right" id="envio_costo" name='envio_costo' placeholder="Costo de envío" value="<?php echo $envio_costo; ?>"
						data-toggle="tooltip" data-placement="top" title="Tooltip on top">
			    </div>

					<div class="col-3">
						<label for="existencia">Existencia </label>
						<input type="text" class="form-control form-control-sm" id="existencia" name='existencia' placeholder="Existencia" value="<?php echo $existencia; ?>" <?php  echo $bloqueo;  ?> readonly>
					</div>
			  </div>

				<div class="row">
					<div class="col-6">
			      <label for="precio_tipo">Precio final</label>
						<?php
							echo "<select id='precio_tipo' name='precio_tipo' class='form-control form-control-sm' >";
								if($interno==0){
									echo "<option value='1'"; if($precio_tipo=='1'){ echo  "selected"; } echo " >".moneda($preciof)." Precio original CT + ".$db->cgeneral."% general de ganancia</option>";
									echo "<option value='0'"; if($precio_tipo=='0'){ echo " selected"; } echo ">".moneda($preciof)." Precio original CT</option>";
									echo "<option value='2'"; if($precio_tipo=='2'){ echo " selected"; } echo ">Precio manual </option>";
									echo "<option value='3'"; if($precio_tipo=='3'){ echo " selected"; } echo ">Precio manual  + ".$db->cgeneral."% general de ganancia</option>";
								}
								else{
									echo "<option value='2'"; if($precio_tipo=='2'){ echo " selected"; } echo ">Precio manual </option>";
								}
							echo "</select>";
						?>
			    </div>
					<div class="col-6">
			      <label for="envio_tipo">Envio final</label>
						<?php
							echo "<select id='envio_tipo' name='envio_tipo' class='form-control form-control-sm'>";
								echo "<option value='0'"; if($envio_tipo=='0'){ echo " selected"; } echo ">".moneda($db->egeneral)." Envio general</option>";
								echo "<option value='1'"; if($envio_tipo=='1'){ echo " selected"; } echo ">Envio manual</option>";
							echo "</select>";
						?>
			    </div>
				</div>

				<hr>
				<div class='row'>
					<?php
					echo "<div class='col-sm-3'>";
						echo "<label>Oferta de la semana: </label><br>";
						echo "<input type='checkbox' name='cb_ofertasemana' id='cb_ofertasemana' value=1";
						if($cb_ofertasemana==1){ echo " checked";}
						echo ">";
					echo "</div>";

					echo "<div class='col-sm-3'>";
						echo "<label>Producto de la semana: </label><br>";
						echo "<input type='checkbox' name='cb_prodsemana' id='cb_prodsemana' value=1";
						if($cb_prodsemana==1){ echo " checked";}
						echo ">";
					echo "</div>";

					echo "<div class='col-sm-3'>";
						echo "<label>Producto destacado: </label><br>";
						echo "<input type='checkbox' name='cb_destacados' id='cb_destacados' value=1";
						if($cb_destacados==1){ echo " checked";}
						echo ">";
					echo "</div>";
					?>
				</div>
			</div>

			<div class='card-footer'>
				<div class='btn-group'>
		  		<button type="submit" class="btn btn-outline-secondary btn-sm"><i class='far fa-save'></i>Guardar</button>
					<?php
						if($interno==1){
							echo "<button type='button' class='btn btn-outline-secondary btn-sm' onclick='generar_codigoprod()'><i class='fas fa-sync'></i>Generar código</button>";
							if($id>0){
								echo "<button type='button' class='btn btn-outline-secondary btn-sm' id='winmodal_espe' data-id='0' data-id2='$id' data-lugar='a_productos/form_especificacion' title='Agregar especificacion' ><i class='fas fa-plus'></i>Especificacion</button>";

								echo "<button type='button' class='btn btn-outline-secondary btn-sm' id='winmodal_exist' data-id='0' data-id2='$id' data-lugar='a_productos/form_existencia' title='Agregar existencias' ><i class='fas fa-plus'></i>Existencia</button>";
							}
						}

						if ($interno==0 and $id>0){
							echo "<button type='button' class='btn btn-outline-secondary btn-sm' onclick='existencia_api()'><i class='fas fa-sync'></i>Existencia</button>";
							echo "<button type='button' class='btn btn-outline-secondary btn-sm' onclick='almacen_api()'><i class='fas fa-sync'></i>Almacen</button>";
							echo "<button type='button' class='btn btn-outline-secondary btn-sm' onclick='imagen_api()'><i class='fas fa-sync'></i>Imagen</button>";
							//echo "<button type='button' class='btn btn-outline-secondary btn-sm' onclick='espe_api()'><i class='fas fa-sync'></i>Especificaciones</button>";
						}

					?>
					<button class='btn btn-outline-secondary btn-sm' id='lista_cat' data-lugar='a_productos/lista' title='regresar'><i class='fas fa-undo-alt'></i>Regresar</button>
				</div>
			</div>


		<?php
			if($id>0){
				echo "<div class='card-header'>";
					echo "Especificaciones";
				echo "</div>";
				echo "<div class='card-body'>";
					echo "<table class='table table-sm' style='font-size:12px'>";
					echo "<tr><th>-</th><th>Tipo</th><th>Valor</th></tr>";
					foreach($espe as $key){
						echo "<tr>";

							echo "<td>";
								echo "<div class='btn-group'>";
								if ($interno==1 and $id>0){
									echo "<button type='button' class='btn btn-outline-secondary btn-sm' id='winmodal_espex' data-id='".$key->idespecificacion."' data-id2='$id' data-lugar='a_productos/form_especificacion' title='Editar especificacion' ><i class='fas fa-pencil-alt'></i></button>";

									echo "<button class='btn btn-outline-secondary btn-sm' id='eliminar_cat' data-lugar='a_productos/db_' data-destino='a_productos/editar' data-id='".$key->idespecificacion."' data-id2='$id' data-iddest='$id' data-funcion='quitar_espe' data-div='trabajo'><i class='far fa-trash-alt'></i></button>";
								}
								echo "</div>";
							echo "</td>";

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
					echo "<table class='table table-sm' style='font-size:12px'>";
						foreach($alma as $key){
							 $almacen=$db->almacen_busca($key->almacen);
							echo "<tr>";
								echo "<td>";
								echo "<div class='btn-group'>";

								if ($interno==1 and $id>0){
									echo "<button type='button' class='btn btn-outline-secondary btn-sm' id='winmodal_existx' data-id='".$key->idexist."' data-id2='$id' data-lugar='a_productos/form_existencia' title='Editar existencias' ><i class='fas fa-pencil-alt'></i></button>";

									echo "<button class='btn btn-outline-secondary btn-sm' id='eliminar_esp' data-lugar='a_productos/db_' data-destino='a_productos/editar' data-id='".$key->idexist."' data-id2='$id' data-iddest='$id' data-funcion='quitar_existencia' data-div='trabajo'><i class='far fa-trash-alt'></i></i></button>";
								}
								echo "</div>";
								echo "</td>";
								echo "<td>";
								echo "(".$almacen->numero.") ";
								echo $almacen->sucursal;
								echo " ->".$almacen->homoclave;
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

						echo "<button type='button' class='btn btn-outline-secondary btn-sm' data-toggle='modal' data-target='#myModal' id='fileup_respuesta' data-ruta='".$db->doc."' data-tabla='producto_img' data-campo='direccion' data-tipo='2' data-id='$id' data-keyt='idproducto' data-destino='a_productos/editar' data-iddest='$id' data-ext='.jpg,.png' ><i class='fas fa-cloud-upload-alt'></i>Agregar imagen</button>";

						echo "</div>";
					echo "</div>";
			}
		?>
	</form>
</div>

<script type="text/javascript">
	$(function() {
		baguetteBox.run('.baguetteBoxOne');
		$('#example').tooltip({ boundary: 'window' })
		$('#descripcion_larga').summernote({
			lang: 'es-ES',
			placeholder: 'Descripción larga del producto',
			tabsize: 5,
			height: 150
		});

	});

</script>
