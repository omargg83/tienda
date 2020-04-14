<?php
	require_once("db_.php");
	$id=$_REQUEST['id'];
	$descripcion="";
	$idalmacen="";
	$numero="";
	$sucursal="";
	$homoclave="";
	$calle="";
	$num="";
	$referencia="";
	$colonia="";
	$cp="";
	$ciudad="";
	$estado="";
	$telefono="";
	$correog="";
	$jefe="";
	$correo="";

	if($id>0){
		$per = $db->almacen_editar($id);
		$idalmacen=$per->idalmacen;
		$numero=$per->numero;
		$sucursal=$per->sucursal;
		$homoclave=$per->homoclave;
		$calle=$per->calle;
		$num=$per->num;
		$referencia=$per->referencia;
		$colonia=$per->colonia;
		$cp=$per->cp;
		$ciudad=$per->ciudad;
		$estado=$per->estado;
		$telefono=$per->telefono;
		$correog=$per->correog;
		$jefe=$per->jefe;
		$correo=$per->correo;
	}
?>
<div class='container'>
	<form id='form_almacen' action='' data-lugar='a_almacen/db_' data-destino='a_almacen/editar' data-funcion='guardar_almacen'>
		<div class='card'>
			<div class='card-header'>
				Almacén <?php echo $id; ?>
			</div>
			<div class='card-body'>
				<input type="hidden" class="form-control" id="id" name='id' value="<?php echo $idalmacen; ?>">

			  <div class="form-row">
			    <div class="form-group col-md-3">
			      <label>Número</label>
			      <input type="text" class="form-control" id="numero" name='numero' placeholder="Número" value="<?php echo $numero; ?>">
			    </div>
					<div class="form-group col-md-3">
			      <label>Sucursal</label>
			      <input type="text" class="form-control" id="sucursal" name='sucursal' placeholder="Sucursal" value="<?php echo $sucursal; ?>">
			    </div>
					<div class="form-group col-md-3">
			      <label>Homoclave</label>
			      <input type="text" class="form-control" id="homoclave" name='homoclave' placeholder="Homoclave" value="<?php echo $homoclave; ?>">
			    </div>
			  </div>

				<div class="form-row">
				 <div class="form-group col-md-6">
					 <label>Calle</label>
					 <input type="text" class="form-control" id="calle" name='calle' placeholder="Calle" value="<?php echo $calle; ?>">
				 </div>
				 <div class="form-group col-md-2">
					 <label>Número</label>
					 <input type="text" class="form-control" id="num" name='num' placeholder="Número" value="<?php echo $num; ?>">
				 </div>
				 <div class="form-group col-md-4">
					 <label>Referencia</label>
					 <input type="text" class="form-control" id="referencia" name='referencia' placeholder="Referencia" value="<?php echo $referencia; ?>">
				 </div>
				 <div class="form-group col-md-3">
					 <label>Colonia</label>
					 <input type="text" class="form-control" id="colonia" name='colonia' placeholder="Colonia" value="<?php echo $colonia; ?>">
				 </div>
				 <div class="form-group col-md-3">
					 <label>CP</label>
					 <input type="text" class="form-control" id="cp" name='cp' placeholder="CP" value="<?php echo $cp; ?>">
				 </div>
				 <div class="form-group col-md-3">
					 <label>Ciudad</label>
					 <input type="text" class="form-control" id="ciudad" name='ciudad' placeholder="Ciudad" value="<?php echo $ciudad; ?>">
				 </div>
				 <div class="form-group col-md-3">
					 <label>Estado</label>
					 <input type="text" class="form-control" id="estado" name='estado' placeholder="Estado" value="<?php echo $estado; ?>">
				 </div>
				 <div class="form-group col-md-3">
					 <label>Telefono</label>
					 <input type="text" class="form-control" id="telefono" name='telefono' placeholder="Telefono" value="<?php echo $telefono; ?>">
				 </div>
				 <div class="form-group col-md-3">
					 <label>Correo grupal</label>
					 <input type="text" class="form-control" id="correog" name='correog' placeholder="Correo grupal" value="<?php echo $correog; ?>">
				 </div>
				 <div class="form-group col-md-6">
					 <label>Jefe</label>
					 <input type="text" class="form-control" id="jefe" name='jefe' placeholder="Jefe" value="<?php echo $jefe; ?>">
				 </div>
				 <div class="form-group col-md-3">
					 <label>Correo</label>
					 <input type="text" class="form-control" id="correo" name='correo' placeholder="Correo" value="<?php echo $correo; ?>">
				 </div>
			 </div>

			</div>
			<div class='card-footer'>
				<div class='btn-group'>
		  		<button type="submit" class="btn btn-outline-secondary btn-sm"><i class='far fa-save'></i>Guardar</button>
					<button class='btn btn-outline-secondary btn-sm' id='lista_cat' data-lugar='a_almacen/lista' title='regresar'><i class='fas fa-undo-alt'></i>Regresar</button>
				</div>
			</div>
	</form>
</div>
