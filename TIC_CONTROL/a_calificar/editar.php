<?php
	require_once("db_.php");
	$id=$_REQUEST['id'];
	$estrella="";
	$texto="";
	$fecha="";
	$publico="";
	$nombre="";
	$clave="";

	if($id>0){
		$per = $db->estrellla_editar($id);
		$estrella=$per->estrella;
		$texto=$per->texto;
		$fecha=$per->fecha;
		$publico=$per->publico;
		$nombre=$per->nombre;
		$clave=$per->clave;
	}
?>
<div class='container'>
	<form id='form_almacen' action='' data-lugar='a_almacen/db_' data-destino='a_almacen/editar' data-funcion='guardar_almacen'>
		<div class='card'>
			<div class='card-header'>
			 	Calificaciones
			</div>
			<div class='card-body'>
				<input type="hidden" class="form-control" id="id" name='id' value="<?php echo $id; ?>">

			  <div class="form-row">
					<div class="form-group col-3">
					 <label>Clave</label>
					 <input type="text" class="form-control" id="clave" name='clave' placeholder="clave" readonly  value='<?php echo $clave; ?>' >
				 </div>

					<div class="form-group col-9">
					 <label>Producto</label>
					 <input type="text" class="form-control" id="nombre" name='nombre' placeholder="Nombre" readonly value='<?php echo $nombre; ?>'  >
				 </div>

			    <div class="form-group col-12">
			      <label>Comentarios</label>
			      <textarea type="text" class="form-control" id="texto" name='texto' placeholder="texto" readonly><?php echo $texto; ?></textarea>
			    </div>
					<div class="form-group col-md-3">
			      <label>Estrellas</label>
						<?php
						echo "<select id='estrella' name='estrella' class='form-control form-control-sm' readonly>";
							echo "<option value='0'"; if($estrella=='0'){ echo " selected"; } echo ">0 Estrella</option>";
							echo "<option value='1'"; if($estrella=='1'){ echo " selected"; } echo ">1 estrellas</option>";
							echo "<option value='2'"; if($estrella=='2'){ echo " selected"; } echo ">2 estrellas</option>";
							echo "<option value='3'"; if($estrella=='3'){ echo " selected"; } echo ">3 estrellas</option>";
							echo "<option value='4'"; if($estrella=='4'){ echo " selected"; } echo ">4 estrellas</option>";
							echo "<option value='5'"; if($estrella=='5'){ echo " selected"; } echo ">5 estrellas</option>";
						echo "</select>";
						?>
			    </div>
			  </div>
			</div>
			<div class='card-footer'>
				<div class='btn-group'>
		  		<button type="button" class="btn btn-outline-secondary btn-sm" id='publica' onclick="publica_c()"><i class="fas fa-check"></i>Publicar</button>
		  		<button type="button" class="btn btn-outline-secondary btn-sm" id='nopublica' onclick="no_publica_c()"><i class="fas fa-ban"></i>No Publicar</button>
					<button class='btn btn-outline-secondary btn-sm' id='lista_cat' data-lugar='a_calificar/lista' title='regresar'><i class='fas fa-undo-alt'></i>Regresar</button>
				</div>
			</div>
	</form>
</div>
