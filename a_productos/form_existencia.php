<?php
	require_once("db_.php");
	$id=$_REQUEST['id'];
	$id2=$_REQUEST['id2'];

	$homoclave="";
	$valor="";

	if($id>0){
		$resp=$db->producto_exist_editar($id);
		$valor=$resp->existencia;
		$homoclave=$resp->almacen;
	}
?>
<form id='form_exist' data-lugar='a_productos/db_' data-funcion='agrega_existencia' data-destino='a_productos/editar'>
<div class='modal-header'>
	<h5 class='modal-title'>Agregar existencia</h5>
</div>
  <div class='modal-body' >
	<?php
		echo "<input  type='hidden' id='id' NAME='id' value='$id'>";
		echo "<input  type='hidden' id='idproducto' NAME='idproducto' value='$id2'>";
	?>
		<div class='row'>
			<div class='col-6'>
				<label>Almacén</label>
				<?php
					echo "<select id='almacen' name='almacen' class='form-control form-control-sm'>";
						foreach($db->almacen_lista() as $key){
							echo "<option value='".$key->homoclave."'"; if ($homoclave==$key->homoclave){ echo " selected";} echo ">".$key->numero."-".$key->sucursal."</option>";
						}
					echo "</select>";
				?>
			</div>
			<div class='col-6'>
				<label>Existencia</label>
				<input type="text" class="form-control form-control-sm" id="valor" name='valor' placeholder="Valor" value="<?php echo $valor; ?>" >
			</div>
		</div>
	</div>
	<div class='modal-footer' >
		<div class='btn-group'>
		<button class='btn btn-outline-secondary btn-sm' type='submit' id='acceso' title='Guardar'><i class='far fa-save'></i>Guardar</button>
		<button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal" title='Cancelar'><i class="fas fa-sign-out-alt"></i>Cancelar</button>
		</div>
  </div>
</form>
