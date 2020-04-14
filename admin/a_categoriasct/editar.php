<?php
	require_once("db_.php");
	$id=$_REQUEST['id'];
	$categoria="";
	$heredado="";
	$interno="1";

	if($id>0){
		$per = $db->categoria_editar($id);
		$categoria=$per->categoria;
		$heredado=$per->heredado;
		$interno=$per->interno;
	}
?>
<div class='container'>
	<form id='form_cat' action='' data-lugar='a_categoriasct/db_' data-destino='a_categoriasct/editar' data-funcion='guardar_categoriact'>
		<div class='card'>
			<div class='card-header'>
				<?php echo $categoria; ?>
			</div>
			<div class='card-body'>
				<input type="hidden" class="form-control" id="id" name='id' value="<?php echo $id; ?>">

				<div class="form-row">
				 <div class="form-group col-md-3">
					 <label for="descripcion">Categoria</label>
					 <input type="text" class="form-control" id="categoria" name='categoria' placeholder="Categoria" value="<?php echo $categoria; ?>"
					 <?php
					 	if($interno==1){

						}
						else{
							echo "readonly";
						}
					  ?>
					  >
				 </div>

			    <div class="form-group col-md-6">
			      <label for="descripcion">Nombre para mostrar</label>
			      <input type="text" class="form-control" id="heredado" name='heredado' placeholder="Nombre para mostrar" value="<?php echo $heredado; ?>">
			    </div>
			  </div>
			</div>
			<div class='card-footer'>
				<div class='btn-group'>
		  		<button type="submit" class="btn btn-outline-secondary btn-sm"><i class='far fa-save'></i>Guardar</button>
					<?php
						if($id>0){
							echo "<button type='button' class='btn btn-outline-secondary btn-sm' id='winmodal_passa' data-id='0' data-id2='$id' data-lugar='a_categoriasct/form_subcat' title='Subcategorias' ><i class='far fa-object-group'></i>Agregar elemento</button>";
						}
					?>
					<button class='btn btn-outline-secondary btn-sm' id='lista_cat' data-lugar='a_categoriasct/lista' title='regresar'><i class='fas fa-undo-alt'></i>Regresar</button>
				</div>
			</div>
			<?php
				if($id>0){
					echo "<div class='card-body'>";
						echo "<table class='table table-sm' id='x_listacat' class='display compact hover'>";
						$row=$db->producto_cat($id);
						echo "<thead><tr><th>-</th><th>Subcategoria CT</th><th>Nompre para mostrar</th><th>CT/TIC</th></tr></thead>";
						foreach($row as $key){
							echo "<tr id='".$key['id']."' class='edit-t'>";
							echo "<td>";
								echo "<div class='btn-group'>";
									echo "<button type='button' class='btn btn-outline-secondary btn-sm' id='winmodal_pass".$key['id']."' data-id='".$key['id']."' data-id2='$id' data-lugar='a_categoriasct/form_subcat' title='Subcategorias'><i class='fas fa-pencil-alt'></i></button>";

									if ($key["interno"]==1){
										echo "<button class='btn btn-outline-secondary btn-sm' id='eliminar_sub".$key['id']."' data-lugar='a_categoriasct/db_' data-destino='a_categoriasct/editar' data-id='".$key['id']."' data-funcion='quitar_subcat' data-iddest='$id' data-div='trabajo'><i class='far fa-trash-alt'></i></i></button>";
									}

								echo "</div>";
							echo "</td>";
							echo "<td>";
							echo $key['subcategoria'];
							echo "</td>";
							echo "<td>";
							echo $key['heredado'];
							echo "</td>";
							echo "<td>";
								if ($key["interno"]==0){
									echo "CT";
								}
								else{
									echo "TIC";
								}
							echo "</td>";
							echo "<tr>";
						}
					echo "</div>";
				}
			?>

	</form>

</div>

<script>
	$(document).ready( function () {
		lista("x_listacat");
	} );
</script>
