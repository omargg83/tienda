<?php
	require_once("db_.php");
	$id=$_REQUEST['id'];
	$id2=$_REQUEST['id2'];

	$id=$_REQUEST['id'];
	$direccion1="";
	$entrecalles="";
	$numero="";
	$colonia="";
	$ciudad="";
	$cp="";
	$pais="";
	$estado="";
	$telefono="";

	if($id>0){
		$per = $db->direccion_editar($id);
		$direccion1=$per->direccion1;
		$entrecalles=$per->entrecalles;
		$numero=$per->numero;
		$colonia=$per->colonia;
		$ciudad=$per->ciudad;
		$cp=$per->cp;
		$pais=$per->pais;
		$estado=$per->estado;
		$telefono=$per->telefono;
	}
?>
<form id='form_personal' data-lugar='a_clientes/db_' data-funcion='guardar_direccion' data-destino='a_clientes/editar' data-iddest='$id2'>
	<div class='modal-header'>Dirección</h5>
	</div>
	  <div class='modal-body' >
		<?php
			echo "<input type='hidden' id='id' NAME='id' value='$id'>";
			echo "<input type='hidden' id='id2' NAME='id2' value='$id2'>";
		?>
			<div class='row'>

				<div class="col-12">
					<label>Dirección linea 1</label>
					<input type="text" class="form-control" id="direccion1" name='direccion1' placeholder="Dirección linea 1" value="<?php echo $direccion1; ?>" required>
				</div>

				<div class="col-4">
					<label>Entre calles</label>
					<input type="text" class="form-control" id="entrecalles" name='entrecalles' placeholder="Entre calles" value="<?php echo $entrecalles; ?>" required>
				</div>

				<div class="col-4">
					<label>Num. Exterior</label>
					<input type="text" class="form-control" id="numero" name='numero' placeholder="Num. Exterior" value="<?php echo $numero; ?>" required>
				</div>

				<div class="col-4">
					<label>Colonia</label>
					<input type="text" class="form-control" id="colonia" name='colonia' placeholder="Colonia" value="<?php echo $colonia; ?>" required>
				</div>

				<div class="col-4">
					<label>Ciudad</label>
					<input type="text" class="form-control" id="ciudad" name='ciudad' placeholder="Ciudad" value="<?php echo $ciudad; ?>" required>
				</div>

				<div class="col-4">
					<label>Código postal</label>
					<input type="text" class="form-control" id="cp" name='cp' placeholder="Código postal" value="<?php echo $cp; ?>" required>
				</div>

				<div class="col-4">
					<label>Pais</label>
					<input type="text" class="form-control" id="pais" name='pais' placeholder="Pais" value="<?php echo $pais; ?>" required>
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
