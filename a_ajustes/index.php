<?php
	require_once("db_.php");

	$per = $db->ajustes_editar();
	$c_envio=$per->c_envio;
	$p_general=$per->p_general;

?>
<div class='container'>
	<form id='form_comision' action='' data-lugar='a_ajustes/db_' data-destino='a_ajustes/editar' data-funcion='guardar_ajustes'>
		<div class='card'>
			<div class='card-header'>
				Ajuste
			</div>
			<div class='card-body'>
				<input type="hidden" class="form-control" id="id" name='id' value="<?php echo $id; ?>">


        <div class="form-group row">
          <label for="staticEmail" class="col-sm-3 col-form-label">Costo general de envío nacional</label>
          <div class="col-sm-9">
            <input type="text"  class="form-control" id="c_envio" name='c_envio' value="<?php echo $c_envio; ?>" placeholder='Costo general de envio nacional'>
          </div>
        </div>

        <div class="form-group row">
          <label for="inputPassword" class="col-sm-3 col-form-label">Porcentaje general de ganancia por producto</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" id="p_general" name='p_general' value="<?php echo $p_general; ?>" placeholder='Porcentaje general de ganancia por producto'>
          </div>
        </div>


			</div>
			<div class='card-footer'>
				<div class='btn-group'>
		  		<button type="submit" class="btn btn-outline-secondary btn-sm"><i class='far fa-save'></i>Guardar</button>
				</div>
			</div>
		</div>
	</form>
</div>
