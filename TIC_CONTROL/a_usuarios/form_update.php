<?php
  require_once("db_.php");
	$id=$_REQUEST['id'];
	$id2=$_REQUEST['id2'];
?>
<div class='modal-header'>
	<h5 class='modal-title'>Cambiar contraseña</h5>
</div>
  <div class='modal-body' >
	<form id='form_personal' data-lugar='a_usuarios/db_' data-funcion='password'>
	<?php
		echo "<input  type='hidden' id='id' NAME='id' value='$id'>";
		echo "<input  type='hidden' id='id2' NAME='id2' value='$id2'>";
	?>
		<p class='input_title'>Contraseña:</p>
		<div class='form-group input-group'>
			<div class='input-group-prepend'>
				<span class='input-group-text'> <i class='fas fa-user-circle'></i>
			</div>
			<input class='form-control' placeholder='Contraseña' type='password'  id='pass1' name='pass1' required>
		</div>

		<p class='input_title'>Contraseña:</p>
		<div class='form-group input-group'>
			<div class='input-group-prepend'>
				<span class='input-group-text'> <i class='fa fa-lock'></i>
			</div>
			<input class='form-control' placeholder='Repetir contraseña' type='password'  id='pass2' name='pass2' required>
		</div>

    <br>* La clave tiene al menos 6 caracteres
    <br>* El password tiene como máximo 16 caracteres
    <br>* Tiene al menos 1 letra minúscula
    <br>* Al menos tiene 1 letra mayúscula
    <br>* Tiene al menos un carácter numérico<br>

		<div class='btn-group'>
		<button class='btn btn-outline-secondary btn-sm' type='submit' id='acceso' title='Guardar'><i class='far fa-save'></i>Guardar</button>
		<button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal" title='Cancelar'><i class="fas fa-sign-out-alt"></i>Cancelar</button>
		</div>
		</form>
  </div>
