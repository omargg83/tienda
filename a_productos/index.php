<?php
  require_once("db_.php");
  $_SESSION['nivel_captura']=1;
 ?>

 <nav class='navbar navbar-expand-sm navbar-light bg-light'>
 		  <a class='navbar-brand' ><i class="fab fa-product-hunt"></i>Productos</a>
 		  <button class='navbar-toggler navbar-toggler-right' type='button' data-toggle='collapse' data-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>
 			<span class='navbar-toggler-icon'></span>
 		  </button>
 		  <div class='collapse navbar-collapse' id='navbarSupportedContent'>
 			<ul class='navbar-nav mr-auto'>
        <div class='form-inline my-2 my-lg-0' id='daigual' action='' >
          <div class="input-group  mr-sm-2">
            <input type="text" class="form-control form-control-sm" placeholder="Buscar" aria-label="Buscar" aria-describedby="basic-addon2"  id='buscar' onkeyup='Javascript: if (event.keyCode==13) buscarx()'>
            <div class="input-group-append">
              <button class="btn btn-outline-secondary btn-sm" type="button" onclick='buscarx()'><i class='fas fa-search'></i></button>
            </div>
          </div>
				</div>

 				<li class='nav-item active'><a class='nav-link barranav' title='Mostrar todo' id='new_poliza' data-lugar='a_productos/editar'><i class="fas fa-folder-plus"></i><span>Nuevo</span></a></li>
 				<li class='nav-item active'><a class='nav-link barranav' title='Mostrar todo' id='lista_prod' data-lugar='a_productos/lista'><i class="fas fa-list"></i><span>Lista</span></a></li>

      </li>

 			</ul>
 		</div>
 	  </div>
 	</nav>

<?php

   echo "<div id='trabajo' style='margin-top:5px;'>";
    include 'lista.php';
   echo "</div>";

 ?>
<script type="text/javascript">
  function buscarx(){
    var buscar = $("#buscar").val();
    $.ajax({
      data:  {
        "buscar":buscar
      },
      url:   'a_productos/lista.php',
      type:  'post',
      success:  function (response) {
        $("#trabajo").html(response);
      }
    });
  }
  function cat_borra(idseguro,idafiliado){
   $.confirm({
     title: 'Cancelar',
     content: '¿Desea eliminar la categoria seleccionada?',
     buttons: {
       Aceptar: function () {
         $.ajax({
           data:  {
             "function":"poliza_baja",
             "idseguro":idseguro,
             "idafiliado":idafiliado
           },
           url:   'a_polizas/db_.php',
           type:  'post',
           success:  function (response) {
             if (response==1){
               Swal.fire({
                   type: 'success',
                   title: 'Se canceló correctamente',
                   showConfirmButton: false,
                   timer: 1000
               })

               $.ajax({
                 data:  {
                   "id":idseguro
                 },
                 url:   'a_polizas/editar_pol_ver.php',
                 type:  'post',
                 success:  function (response) {
                   $("#datos_x").html(response);
                 }
               });
             }
             else{
               Swal.fire({
                   type: 'error',
                   title: 'Error, favor de verificar',
                   showConfirmButton: false,
                   timer: 1000
               })
             }
           }
         });
       },
       Cancelar: function () {

       }
     }
   });
 }

</script>
