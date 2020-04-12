<?php
	require_once("db_.php");
	$id=$_REQUEST['id'];
	//////////////bloque 1
	$codigo="";
	$descripcion="";
	$tipo="";
	$envio="";
	$caducidad="";
	$gasto_minimo="";
	$gasto_maximo="";
	$individual="";
	$excluir="";
	$importe="";
	$limite_cup="";
	$limite_art="";
	$limite_user="";

	//////////////bloque 2
	if($id>0){
		$per = $db->cupon_editar($id);
		$codigo=$per->codigo;
		$descripcion=$per->descripcion;
		$tipo=$per->tipo;
		$envio=$per->envio;
		$caducidad=fecha($per->caducidad);
		$gasto_minimo=$per->gasto_minimo;
		$gasto_maximo=$per->gasto_maximo;
		$individual=$per->individual;
		$excluir=$per->excluir;
		$importe=$per->importe;
		$limite_cup=$per->limite_cup;
		$limite_art=$per->limite_art;
		$limite_user=$per->limite_user;
	}
?>
<div class='container'>
	<form id='form_comision' action='' data-lugar='a_cupones/db_' data-destino='a_cupones/editar' data-funcion='guardar_cupon'>
		<div class='card'>
			<div class='card-header'>
				Cupón <?php echo $id; ?>
			</div>
			<div class='card-body'>
				<input type="hidden" class="form-control" id="id" name='id' value="<?php echo $id; ?>">

			  <div class="row">
			    <div class='col-3'>
			      <label>Código</label>
			      <input type='text' class='form-control' id='codigo' name='codigo' placeholder='Código' value='<?php echo $codigo; ?>' >
			    </div>

			    <div class='col-6'>
			      <label>Descripción</label>
			      <input type='text' class='form-control' id='descripcion' name='descripcion' placeholder='Descripción' value='<?php echo $descripcion; ?>' >
			    </div>

					<div class='col-3'>
					 <label>Importe de cupón</label>
					 <input type='text' class='form-control' id='importe' name='importe' placeholder='Importe' value='<?php echo $importe; ?>' >
				 </div>

				  <div class='col-4'>
			      <label>Tipo</label>
						<select class='form-control' id='tipo' name='tipo'>
							<option value='porcentaje' <?php if ($tipo=="porcentaje"){ echo " selected"; } ?> >Descuento el porcentaje</option>
							<option value='carrito' <?php if ($tipo=="carrito"){ echo " selected"; } ?> >Descuento fijo en el carrito</option>
							<option value='producto' <?php if ($tipo=="producto"){ echo " selected"; } ?> >Descuento fijo de producto</option>
						</select>
		    	</div>

	 				<div class='col-4'>
			      <label>Permitir el envío gratuito</label>
						<select class='form-control' id='envio' name='envio'>
							<option value='si' <?php if ($envio=="si"){ echo " selected"; } ?> >Si</option>
							<option value='no' <?php if ($envio=="no"){ echo " selected"; } ?> >No</option>
						</select>
						<small id="emailHelp" class="form-text text-muted">El cupón ofrece envío gratuito. El método de envío gratuito debe estar activo e</small>
			    </div>

					<div class='col-4'>
			      <label>Caducidad</label>
			      <input type='text' class='form-control fechaclass' id='caducidad' name='caducidad' placeholder='Caducidad' value='<?php echo $caducidad; ?>' >
			    </div>

					<?php
					/*
					<div class='col-4'>
			      <label>Gasto Mínimo</label>
			      <input type='text' class='form-control' id='gasto_minimo' name='gasto_minimo' placeholder='Gasto Mínimo' value='<?php echo $gasto_minimo; ?>' >
			    </div>

					<div class='col-4'>
			      <label>Gasto Máximo</label>
			      <input type='text' class='form-control' id='gasto_maximo' name='gasto_maximo' placeholder='Gasto Máximo' value='<?php echo $gasto_maximo; ?>' >
			    </div>

					<div class='col-4'>
			      <label>Uso individual</label>
						<select class='form-control' id='individual' name='individual'>
							<option value='si' <?php if ($individual=="si"){ echo " selected"; } ?> >Si</option>
							<option value='no' <?php if ($individual=="no"){ echo " selected"; } ?> >No</option>
						</select>
						<small id="emailHelp" class="form-text text-muted">El cupón no se puede utilizar en combinación con otros cupones..</small>
			    </div>

					<div class='col-4'>
			      <label>Excluir los artículos en oferta</label>
						<select class='form-control' id='excluir' name='excluir'>
							<option value='si' <?php if ($excluir=="si"){ echo " selected"; } ?> >Si</option>
							<option value='no' <?php if ($excluir=="no"){ echo " selected"; } ?> >No</option>
						</select>
						<small id="emailHelp" class="form-text text-muted">El cupón no debe aplicarse a artículos rebajados. Los cupones para artículos concretos sólo funcionarán si el artículo no está rebajado. Los cupones para carrito completo solo funcionarán si no hay artículos rebajados en el carrito.</small>
			    </div>

					<div class='col-4'>
						<label>Límite de uso por cupón</label>
						<input type='text' class='form-control' id='limite_cup' name='limite_cup' placeholder='Límite de uso por cupón' value='<?php echo $limite_cup; ?>' >
					</div>

					<div class='col-4'>
						<label>Limitar el uso a X artículos</label>
						<input type='text' class='form-control' id='limite_art' name='limite_art' placeholder='Límite de uso por cupón' value='<?php echo $limite_art; ?>' >
					</div>

					<div class='col-4'>
						<label>Límite de uso por usuario</label>
						<input type='text' class='form-control' id='limite_user' name='limite_user' placeholder='Límite de uso por cupón' value='<?php echo $limite_user; ?>' >
					</div>
*/
?>
			  </div>

			</div>
			<div class='card-footer'>
				<div class='btn-group'>
		  		<button type="submit" class="btn btn-outline-secondary btn-sm"><i class='far fa-save'></i>Guardar</button>
					<?php
					/*
						if($id>0){
							echo "<div class='btn-group' role='group'>";
						    echo "<button id='btnGroupDrop1' type='button' class='btn btn-outline-secondary btn-sm dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'><i class='fab fa-product-hunt'></i>";
						      echo "Productos";
						    echo "</button>";
						    echo "<div class='dropdown-menu' aria-labelledby='btnGroupDrop1'>";
									echo "<button type='button' class='dropdown-item' id='winmodal_incluir' data-id='$id' data-id2='1' data-lugar='a_cupones/form_producto' title='Incluir producto' ><i class='fas fa-plus-circle'></i>Incluir Productos</button>";

									echo "<button type='button' class='dropdown-item' id='winmodal_exlu' data-id='$id' data-id2='2' data-lugar='a_cupones/form_producto' title='Excluir producto' ><i class='fas fa-minus-circle'></i>Excluir Productos</button>";
						    echo "</div>";
						  echo "</div>";

							echo "<div class='btn-group' role='group'>";
								echo "<button id='btnGroupDrop2' type='button' class='btn btn-outline-secondary btn-sm dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'><i class='fab fa-buffer'></i>";
									echo "Categorias";
								echo "</button>";
								echo "<div class='dropdown-menu' aria-labelledby='btnGroupDrop2'>";
									echo "<button type='button' class='dropdown-item' id='winmodal_incluircat' data-id='$id' data-id2='1' data-lugar='a_cupones/form_categorias' title='Incluir producto' ><i class='fas fa-plus-circle'></i>Incluir Categorias</button>";

									echo "<button type='button' class='dropdown-item' id='winmodal_exlucat' data-id='$id' data-id2='2' data-lugar='a_cupones/form_categorias' title='Excluir producto' ><i class='fas fa-minus-circle'></i>Excluir Categorias</button>";
								echo "</div>";
							echo "</div>";

							echo "<button type='button' class='btn btn-outline-secondary btn-sm' id='winmodal_correos' data-id='$id' data-id2='2' data-lugar='a_cupones/form_correos' title='Permitir correos' ><i class='far fa-envelope'></i>Correos</button>";
						}
							*/
					?>
					<button class='btn btn-outline-secondary btn-sm' id='lista_cat' data-lugar='a_cupones/lista' title='regresar'><i class='fas fa-undo-alt'></i>Regresar</button>
				</div>
			</div>
			<?php
				/*
				if($id>0){
					$row=$db->productos_incluir($id,1);
					echo "<div class='card-header'>";
						echo "Incluir productos";
					echo "</div>";
					echo "<div class='card-body'>";
						echo "<table class='table table-sm'>";
						echo "<tr><th>-</th><th>Clave</th><th>Num. Parte</th><th>nombre</th><th>Modelo</th><th>Marca</th></tr>";
						foreach($row as $key){
							echo "<tr id='".$key['id']."' class='edit-t'>";
								echo "<td>";
									echo "<div class='btn-group'>";
										echo "<button class='btn btn-outline-secondary btn-sm' id='eliminar_comision".$key['id']."' data-lugar='a_cupones/db_' data-destino='a_cupones/editar' data-id='".$key['id']."' data-iddest='$id' data-funcion='borrar_producto' data-div='trabajo'><i class='far fa-trash-alt'></i></i></button>";
									echo "</div>";
								echo "</td>";
								echo "<td>";
									echo $key['clave'];
								echo "</td>";
								echo "<td>";
									echo $key['numParte'];
								echo "</td>";
								echo "<td>";
									echo $key['nombre'];
								echo "</td>";
								echo "<td>";
									echo $key['modelo'];
								echo "</td>";
								echo "<td>";
									echo $key['marca'];
								echo "</td>";
							echo "</tr>";
						}
						echo "</table>";
					echo "</div>";

					$row=$db->productos_incluir($id,2);
					echo "<div class='card-header'>";
						echo "Excluir productos";
					echo "</div>";
					echo "<div class='card-body'>";
						echo "<table class='table table-sm'>";
						echo "<tr><th>-</th><th>Clave</th><th>Num. Parte</th><th>nombre</th><th>Modelo</th><th>Marca</th></tr>";
						foreach($row as $key){
							echo "<tr id='".$key['id']."' class='edit-t'>";
								echo "<td>";
									echo "<div class='btn-group'>";
										echo "<button class='btn btn-outline-secondary btn-sm' id='eliminar_comision".$key['id']."' data-lugar='a_cupones/db_' data-destino='a_cupones/editar' data-id='".$key['id']."' data-iddest='$id' data-funcion='borrar_producto' data-div='trabajo'><i class='far fa-trash-alt'></i></i></button>";
									echo "</div>";
								echo "</td>";
								echo "<td>";
									echo $key['clave'];
								echo "</td>";
								echo "<td>";
									echo $key['numParte'];
								echo "</td>";
								echo "<td>";
									echo $key['nombre'];
								echo "</td>";
								echo "<td>";
									echo $key['modelo'];
								echo "</td>";
								echo "<td>";
									echo $key['marca'];
								echo "</td>";
							echo "</tr>";
						}
						echo "</table>";
					echo "</div>";


					$row=$db->categoria_incluir($id,1);
					echo "<div class='card-header'>";
						echo "Incluir Categorias";
					echo "</div>";
					echo "<div class='card-body'>";
						echo "<table class='table table-sm'>";
						echo "<tr><th>-</th><th>Categoria</th></tr>";
						foreach($row as $key){
							echo "<tr id='".$key['id']."' class='edit-t'>";
								echo "<td>";
									echo "<div class='btn-group'>";
										echo "<button class='btn btn-outline-secondary btn-sm' id='eliminar_cat".$key['id']."' data-lugar='a_cupones/db_' data-destino='a_cupones/editar' data-id='".$key['id']."' data-iddest='$id' data-funcion='borrar_categoria' data-div='trabajo'><i class='far fa-trash-alt'></i></i></button>";
									echo "</div>";
								echo "</td>";
								echo "<td>";
									echo $key['descripcion'];
								echo "</td>";
							echo "</tr>";
						}
						echo "</table>";
					echo "</div>";

					$row=$db->categoria_incluir($id,2);
					echo "<div class='card-header'>";
						echo "Excluir Categorias";
					echo "</div>";
					echo "<div class='card-body'>";
						echo "<table class='table table-sm'>";
						echo "<tr><th>-</th><th>Categoria</th></tr>";
						foreach($row as $key){
							echo "<tr id='".$key['id']."' class='edit-t'>";
								echo "<td>";
									echo "<div class='btn-group'>";
										echo "<button class='btn btn-outline-secondary btn-sm' id='eliminar_cat".$key['id']."' data-lugar='a_cupones/db_' data-destino='a_cupones/editar' data-id='".$key['id']."' data-iddest='$id' data-funcion='borrar_categoria' data-div='trabajo'><i class='far fa-trash-alt'></i></i></button>";
									echo "</div>";
								echo "</td>";
								echo "<td>";
									echo $key['descripcion'];
								echo "</td>";
							echo "</tr>";
						}
						echo "</table>";
					echo "</div>";

					$row=$db->correos($id);
					echo "<div class='card-header'>";
						echo "Correos";
					echo "</div>";
					echo "<div class='card-body'>";
						echo "<table class='table table-sm'>";
						echo "<tr><th>-</th><th>Correo</th></tr>";
						foreach($row as $key){
							echo "<tr id='".$key['id']."' class='edit-t'>";
								echo "<td>";
									echo "<div class='btn-group'>";
										echo "<button class='btn btn-outline-secondary btn-sm' id='eliminar_mail".$key['id']."' data-lugar='a_cupones/db_' data-destino='a_cupones/editar' data-id='".$key['id']."' data-iddest='$id' data-funcion='borrar_correo' data-div='trabajo'><i class='far fa-trash-alt'></i></i></button>";
									echo "</div>";
								echo "</td>";
								echo "<td>";
									echo $key['correo'];
								echo "</td>";
							echo "</tr>";
						}
						echo "</table>";
					echo "</div>";
				}
				*/
			?>
		</div>
	</form>
</div>
<script>
	$(function() {
		fechas();
	});
</script>
