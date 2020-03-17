<?php
	require_once("db_.php");
	$pd = $db->categorias_lista();
	$id=$_REQUEST['id'];
?>

  <div class='modal-body' >
	<form id='form_addcat' data-lugar='a_productos/db_' data-funcion='categoria_agrega' data-destino='a_productos/editar'>
	<?php
		echo "<input  type='hidden' id='id' NAME='id' value='$id'>";
	?>
		<p class='input_title'>Categoria:</p>
		<div class='form-group input-group'>
			<div class='input-group-prepend'>
				<span class='input-group-text'> <i class="fab fa-buffer"></i>
			</div>
			<?php
				echo "<select class='form-control' name='idcategoria' id='idcategoria' required>";
				echo "<option value='' disabled selected style='color: silver;'>Seleccione ...</option>";
				foreach($pd as $key){
					echo  "<option value='".$key['idcategoria']."'>".$key['descripcion']."</option>";
				}
				echo  "</select>";
			?>
		</div>

		<div class='btn-group'>
		<button class='btn btn-outline-secondary btn-sm' type='submit' id='acceso' title='Guardar'><i class="fas fa-plus"></i>Agregar</button>
		<button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal" title='Cancelar'><i class="fas fa-sign-out-alt"></i>Cancelar</button>
		</div>
		</form>
  </div>
