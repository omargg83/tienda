<?php
  require_once("db_.php");

  $id=3;
  $baner=$db->baner2($id);
  $titulo=$baner->titulo;
  $texto=$baner->texto;
  $imagen=$baner->img;
  $enlace=$baner->enlace;
  $activo=$baner->activo;

?>
<form id='form_comision' action='' data-lugar='a_pagina/db_' data-destino='a_pagina/lista4' data-funcion='guardar_baner2'>
  <input type="hidden" class="form-control form-control-sm" id="id" name='id' value="<?php echo $id; ?>" readonly>
  <div class='container'>
    <div class='card'>
      <div class='card-header'>
        Banner 4
      </div>
      <div class='card-body'>
        <div class="row">
          <div class="col-12">
            <label for="descripcion">Titulo</label>
            <input type="text" class="form-control form-control-sm" id="titulo" name='titulo' placeholder="titulo" value="<?php echo $titulo; ?>" >
          </div>

          <div class="col-12">
            <label for="descripcion">Enlace</label>
            <input type="text" class="form-control form-control-sm" id="enlace" name='enlace' placeholder="Enlace" value="<?php echo $enlace; ?>" >
          </div>

          <div class="col-12">
            <label for="descripcion">Descripción larga</label>
            <textarea rows='10' id='texto' NAME='texto' class='form-control'><?php echo $texto; ?></textarea>
          </div>
          <div class="col-4">
            <label>Activo</label>
            <select id="activo" name='activo' class='form-control'>
              <option value="1" <?php if($activo==1){ echo " selected";} ?>>Activo</option>
              <option value="0" <?php if($activo==0){ echo " selected";} ?>>Inactivo</option>
            </select>
          </div>
        </div>
      </div>


      <div class='card-footer'>
        <div class='btn-group'>
          <button type="submit" class="btn btn-outline-secondary btn-sm"><i class='far fa-save'></i>Guardar</button>
          <button class='btn btn-outline-secondary btn-sm' id='lista_cat' data-lugar='a_pagina/lista' title='regresar'><i class='fas fa-undo-alt'></i>Regresar</button>
        </div>
      </div>
    </div>
  </div>
</form>
