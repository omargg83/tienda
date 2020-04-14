<?php
	$id=$_REQUEST['id'];
?>
<?php
echo "<input  type='hidden' id='idcupon' NAME='idcupon' value='$id'>";
?>
  <div class='modal-header'>
  	<h5 class='modal-title'>Correos</h5>
  </div>
  <div class="modal-body" style='max-height:580px;overflow: auto;'>
    <div clas='row'>
      <div class='col-12'>
        <input type="text" class="form-control" name="correox" id='correox' placeholder='Agregar correo' aria-label="buscar cliente" aria-describedby="basic-addon2" onkeyup='Javascript: if (event.keyCode==13) agregar_correo()'>
      </div>
    </div>
    <div clas='row' id='resultadosx'>

    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal" onclick='agregar_correo()'><i class="fas fa-plus"></i>Agregarr</button>
    <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal"><i class="fas fa-sign-out-alt"></i>Cerrar</button>
  </div>
