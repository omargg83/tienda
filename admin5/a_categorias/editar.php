<?php
	require_once("db_.php");
	$id=$_REQUEST['id'];
	$descripcion="";

	if($id>0){
		$per = $db->categoria_editar($id);
		$descripcion=$per->descripcion;
	}
?>
<div class='container'>
	<form id='form_comision' action='' data-lugar='a_categorias/db_' data-destino='a_categorias/editar' data-funcion='guardar_categoria'>
		<div class='card'>
			<div class='card-header'>
				Categoria <?php echo $id; ?>
			</div>
			<div class='card-body'>
				<input type="hidden" class="form-control" id="id" name='id' value="<?php echo $id; ?>">

			  <div class="form-row">
			    <div class="form-group col-md-6">
			      <label for="inputEmail4">Descripción</label>
			      <input type="text" class="form-control" id="descripcion" name='descripcion' placeholder="Descripción" value="<?php echo $descripcion; ?>">
			    </div>
			  </div>

			</div>
			<div class='card-footer'>
				<div class='btn-group'>
		  		<button type="submit" class="btn btn-outline-secondary btn-sm"><i class='far fa-save'></i>Guardar</button>
					<button class='btn btn-outline-secondary btn-sm' id='lista_cat' data-lugar='a_categorias/lista' title='regresar'><i class='fas fa-undo-alt'></i>Regresar</button>
				</div>
			</div>
	</form>
</div>
