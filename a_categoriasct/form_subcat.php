<?php
	require_once("db_.php");
	$id=$_REQUEST['id'];
	$id2=$_REQUEST['id2'];
	$subcategoria="";
	$heredado="";
	$interno="1";
	if($id>0){
		$cat=$db->edita_subcat($id);
		$subcategoria=$cat->subcategoria;
		$heredado=$cat->heredado;
		$interno=$cat->interno;
	}
?>
<div class='modal-header'>
	<h5 class='modal-title'>Subcategorias</h5>
</div>
  <div class='modal-body' >
	<form id='form_personal' data-lugar='a_categoriasct/db_' data-funcion='agrega_subcategoria' data-destino='a_categoriasct/editar' data-id='$id'>
	<?php
		echo "<input  type='hidden' id='id' NAME='id' value='$id'>";
		echo "<input  type='hidden' id='idcategoria' NAME='idcategoria' value='$id2'>";
	?>
		<div class="form-row">
		 <div class="form-group col-md-6">
			 <label for="descripcion">Subcategoria</label>
			 <input type="text" class="form-control" id="subcategoria" name='subcategoria' placeholder="Subcategoria (CT)" value="<?php echo $subcategoria; ?>"
			 <?php
				 if($interno==1){

				 }
				 else{
					 echo "readonly";
				 }
				 ?>>
		 </div>

			<div class="form-group col-md-6">
				<label for="descripcion">Nombre para mostrar</label>
				<input type="text" class="form-control" id="heredado" name='heredado' placeholder="Nombre para mostrar" value="<?php echo $heredado; ?>">
			</div>
		</div>

		<div class='btn-group'>
		<button class='btn btn-outline-secondary btn-sm' type='submit' id='acceso' title='Guardar'><i class='far fa-save'></i>Guardar</button>
		<button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal" title='Cancelar'><i class="fas fa-sign-out-alt"></i>Cancelar</button>
		</div>
		</form>
  </div>
