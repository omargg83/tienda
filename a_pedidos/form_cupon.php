<?php
	$id=$_REQUEST['id'];
	$id2=$_REQUEST['id2'];
?>
<?php
echo "<input  type='hidden' id='idproducto' NAME='idproducto' value='$id'>";
echo "<input  type='hidden' id='idpedido' NAME='idpedido' value='$id2'>";
?>
  <div class='modal-header'>
  	<h5 class='modal-title'>Buscar cupón</h5>
  </div>
  <div class="modal-body" style='max-height:580px;overflow: auto;'>
    <div clas='row'>
        <div class="input-group mb-3">
        <input type="text" class="form-control" name="prod_venta" id='prod_venta' placeholder='buscar producto' aria-label="buscar producto" aria-describedby="basic-addon2" onkeyup='Javascript: if (event.keyCode==13) buscar_cupon()'>
        <div class="input-group-append">
          <button class="btn btn-outline-secondary btn-sm" type="button" onclick='buscar_cupon()'><i class='fas fa-search'></i>Buscar</button>
        </div>
      </div>
    </div>
    <div clas='row' id='resultadosx'>

    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal"><i class="fas fa-sign-out-alt"></i>Cerrar</button>

  </div>
