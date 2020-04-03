<?php
	require_once("db_.php");
	$id=$_REQUEST['id'];
	$cat=$db->agrupa_cat();
?>
<div class='modal-header'>
	<h5 class='modal-title'>Agregar</h5>
</div>
  <div class='modal-body' >
	<form id='form_personal' data-lugar='a_categorias/db_' data-funcion='agrega_categoria' data-destino='a_categorias/editar' data-id='$id'>
	<?php
		echo "<input  type='hidden' id='id' NAME='id' value='$id'>";
	?>
		<p class='input_title'>Categorias (CT):</p>
		<div class='form-group input-group'>
			<div class='input-group-prepend'>
				<span class='input-group-text'> <i class='fas fa-user-circle'></i>
			</div>
			<?php
				echo "<select class='form-control' name='categoria' id='categoria' onchange='subcat()'>";
				foreach($cat as $key){
					echo  "<option value='".$key['id']."'";
					echo  ">".$key['categoria']."</option>";
				}
				echo  "</select>";
			?>
		</div>
		<div id='resp'>

		</div>

		<div class='btn-group'>
		<button class='btn btn-outline-secondary btn-sm' type='submit' id='acceso' title='Guardar'><i class='far fa-save'></i>Guardar</button>
		<button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal" title='Cancelar'><i class="fas fa-sign-out-alt"></i>Cancelar</button>
		</div>
		</form>
  </div>
