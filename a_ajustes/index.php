<?php
	require_once("db_.php");

	$per = $db->ajustes_editar();
	$c_envio=$per->c_envio;
	$p_general=$per->p_general;
	$mercado_public=$per->mercado_public;
	$mercado_token=$per->mercado_token;
	$paypal_client=$per->paypal_client;

	$correo=$per->correo;
	$host=$per->host;
	$SMTPAuth=$per->SMTPAuth;
	$Password=$per->Password;
	$SMTPSecure=$per->SMTPSecure;
	$Port=$per->Port;
?>
<div class="alert alert-primary" role="alert">
	<h4 class='text-center'>Estos son parametros globales pero pueden modificarse individualmente en cada producto</h4>
</div>

<div class='container'>
	<form id='form_comision' action='' data-lugar='a_ajustes/db_' data-destino='a_ajustes/editar' data-funcion='guardar_ajustes'>
		<div class='card'>
			<div class='card-header'>
				Ajustes
			</div>
			<div class='card-body'>
				<input type="hidden" class="form-control" id="id" name='id' value="<?php echo $id; ?>">

				<div class="form-group row">
					<label for="staticEmail" class="col-sm-4 col-form-label">Correo</label>
					<div class="col-sm-8">
						<input type="text"  class="form-control" id="correo" name='correo' value="<?php echo $correo; ?>" placeholder='Correo'>
					</div>
				</div>

				<div class="form-group row">
					<label for="staticEmail" class="col-sm-4 col-form-label">HOST</label>
					<div class="col-sm-8">
						<input type="text"  class="form-control" id="host" name='host' value="<?php echo $host; ?>" placeholder='smtp.gmail.com'>
					</div>
				</div>

				<div class="form-group row">
					<label for="staticEmail" class="col-sm-4 col-form-label">SMTPAuth</label>
					<div class="col-sm-8">
						<input type="text"  class="form-control" id="SMTPAuth" name='SMTPAuth' value="<?php echo $SMTPAuth; ?>" placeholder='true'>
					</div>
				</div>

				<div class="form-group row">
					<label for="staticEmail" class="col-sm-4 col-form-label">Password</label>
					<div class="col-sm-8">
						<input type="password"  class="form-control" id="Password" name='Password' value="<?php echo $Password; ?>" placeholder='Password'>
					</div>
				</div>
				<div class="form-group row">
					<label for="staticEmail" class="col-sm-4 col-form-label">SMTPSecure</label>
					<div class="col-sm-8">
						<input type="text"  class="form-control" id="SMTPSecure" name='SMTPSecure' value="<?php echo $SMTPSecure; ?>" placeholder='ssl'>
					</div>
				</div>
				<div class="form-group row">
					<label for="staticEmail" class="col-sm-4 col-form-label">Port</label>
					<div class="col-sm-8">
						<input type="text"  class="form-control" id="Port" name='Port' value="<?php echo $Port; ?>" placeholder='465'>
					</div>
				</div>

				<hr>


        <div class="form-group row">
          <label for="staticEmail" class="col-sm-4 col-form-label">Costo general de envío nacional</label>
          <div class="col-sm-8">
            <input type="text"  class="form-control" id="c_envio" name='c_envio' value="<?php echo $c_envio; ?>" placeholder='Costo general de envio nacional'>
          </div>
        </div>

        <div class="form-group row">
          <label for="inputPassword" class="col-sm-4 col-form-label">Porcentaje general de ganancia por producto</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="p_general" name='p_general' value="<?php echo $p_general; ?>" placeholder='Porcentaje general de ganancia por producto'>
          </div>
        </div>

        <div class="form-group row">
          <label for="inputPassword" class="col-sm-4 col-form-label">Mercado pago Public key</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="mercado_public" name='mercado_public' value="<?php echo $mercado_public; ?>" placeholder='Mercado pago Public key'>
          </div>
        </div>
        <div class="form-group row">
          <label for="inputPassword" class="col-sm-4 col-form-label">Mercado pago Token</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="mercado_token" name='mercado_token' value="<?php echo $mercado_token; ?>" placeholder='Mercado pago Token'>
          </div>
        </div>
        <div class="form-group row">
          <label for="inputPassword" class="col-sm-4 col-form-label">PayPal Client ID</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="paypal_client" name='paypal_client' value="<?php echo $paypal_client; ?>" placeholder='PayPal Client ID'>
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
