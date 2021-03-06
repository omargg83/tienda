<?php
	require_once("db_.php");
	$id=$_REQUEST['id'];
	$descripcion="";
	$orden="";

	if($id>0){
		$per = $db->categoria_editar($id);
		$descripcion=$per->descripcion;
		$orden=$per->orden;
	}
?>
<div class='container'>
	<form id='form_comision' action='' data-lugar='a_categorias/db_' data-destino='a_categorias/editar' data-funcion='guardar_categoria'>
		<div class='card'>
			<div class='card-header'>
				Categoria <?php echo $descripcion; ?>
			</div>
			<div class='card-body'>
				<input type="hidden" class="form-control" id="id" name='id' value="<?php echo $id; ?>">

				<div class="form-row">
				 <div class="form-group col-md-3">
					 <label for="descripcion">Orden para mostrar</label>
					 <input type="text" class="form-control" id="orden" name='orden' placeholder="Orden" value="<?php echo $orden; ?>">
				 </div>

			    <div class="form-group col-md-6">
			      <label for="descripcion">Nombre para mostrar</label>
			      <input type="text" class="form-control" id="descripcion" name='descripcion' placeholder="Nombre" value="<?php echo $descripcion; ?>">
			    </div>
			  </div>

			</div>
			<div class='card-footer'>
				<div class='btn-group'>
		  		<button type="submit" class="btn btn-outline-secondary btn-sm"><i class='far fa-save'></i>Guardar</button>
					<?php
						if($id>0){
							echo "<button type='button' class='btn btn-outline-secondary btn-sm' id='winmodal_pass' data-id='$id' data-lugar='a_categorias/form_grupo' title='Subcategorias' ><i class='far fa-object-group'></i>Agregar elemento</button>";
						}
					?>
					<button class='btn btn-outline-secondary btn-sm' id='lista_cat' data-lugar='a_categorias/lista' title='regresar'><i class='fas fa-undo-alt'></i>Regresar</button>
				</div>
			</div>
			<?php
				if($id>0){
					echo "<div class='card-body'>";
						echo "<table class='table table-sm' id='x_listacat' class='display compact hover'>";
						$row=$db->producto_cat($id);
						echo "<thead><tr><th>-</th><th>categoria</th></tr></thead>";
						foreach($row as $key){
							echo "<tr>";
							echo "<td>";
								echo "<div class='btn-group'>";
									echo "<button class='btn btn-outline-secondary btn-sm' id='eliminar_cat' data-lugar='a_categorias/db_' data-destino='a_categorias/editar' data-id='".$key['idcatprod']."' data-iddest='$id' data-funcion='quitar_categoria' data-div='trabajo'><i class='far fa-trash-alt'></i></i></button>";
								echo "</div>";
							echo "</td>";
							echo "<td>";
							echo $key['categoria'];
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
