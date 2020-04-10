<?php
  require_once("db_.php");

  $id=2;
  $baner=$db->baner2($id);
  $titulo=$baner->titulo;
  $texto=$baner->texto;
  $imagen=$baner->img;
  $enlace=$baner->enlace;



?>
<form id='form_comision' action='' data-lugar='a_pagina/db_' data-destino='a_pagina/baner2' data-funcion='guardar_baner2'>
  <input type="hidden" class="form-control form-control-sm" id="id" name='id' value="<?php echo $id; ?>" readonly>
  <div class='container'>
    <div class='card'>
      <div class='card-header'>
        Banner 3
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
            <textarea rows='10' id='texto' NAME='texto'><?php echo $texto; ?></textarea>
          </div>
        </div>
      </div>
      <?php
        if(file_exists("../".$db->doc1."$imagen")){
          echo "<img src='".$db->doc1."$imagen' width='100%' height='50%'/><br>";
        }
     ?>

      <div class='card-footer'>
        <div class='btn-group'>
          <button type="submit" class="btn btn-outline-secondary btn-sm"><i class='far fa-save'></i>Guardar</button>
          <?php

            echo "<button type='button' class='btn btn-outline-secondary btn-sm' data-toggle='modal' data-target='#myModal' id='fileup_imagenx' data-ruta='a_pagina/' data-tabla='baner2' data-campo='img' data-tipo='1' data-id='$id' data-keyt='id' data-destino='a_pagina/lista3' data-iddest='$id' data-ext='.jpg,.png' ><i class='fas fa-cloud-upload-alt'></i>Agregar imagen</button>";

            ?>
          <button class='btn btn-outline-secondary btn-sm' id='lista_cat' data-lugar='a_pagina/lista' title='regresar'><i class='fas fa-undo-alt'></i>Regresar</button>
        </div>
      </div>
    </div>
  </div>
</form>


<script type="text/javascript">
	$(function() {
		$('#texto').summernote({
			lang: 'es-ES',
			placeholder: 'Texto',
			tabsize: 5,
			height: 150
		});
	});
</script>
