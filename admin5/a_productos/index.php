<?php
  require_once("db_.php");
  $_SESSION['nivel_captura']=1;
 ?>

 <nav class='navbar navbar-expand-lg navbar-light bg-light'>
 		  <a class='navbar-brand' ><i class="fab fa-product-hunt"></i>Productos</a>
 		  <button class='navbar-toggler navbar-toggler-right' type='button' data-toggle='collapse' data-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>
 			<span class='navbar-toggler-icon'></span>
 		  </button>
 		  <div class='collapse navbar-collapse' id='navbarSupportedContent'>
 			<ul class='navbar-nav mr-auto'>
        <div class='form-inline my-2 my-lg-0' id='daigual' action='' >
					<input class='form-control mr-sm-2' type='search' placeholder='Buscar' aria-label='Search' name='buscar' id='buscar'  onkeyup='Javascript: if (event.keyCode==13) buscarx()'>
          <div class='btn-group'>
            <div class="input-group-text"><label><input type='checkbox' value='1' id='c_pol' name='c_pol'><span>Productos</span></label></div>
            <button type='button' class='btn btn-outline-warning btn-sm' onclick='buscarx()'><i class='fas fa-search'></i>Buscar</button>
          </div>
				</div>


 				<li class='nav-item active'><a class='nav-link barranav' title='Mostrar todo' id='new_poliza' data-lugar='a_productos/editar'><i class="fas fa-folder-plus"></i><span>Nuevo</span></a></li>
 				<li class='nav-item active'><a class='nav-link barranav' title='Mostrar todo' id='lista_prod' data-lugar='a_productos/lista'><i class="fas fa-list"></i><span>Lista</span></a></li>
 				<li class='nav-item active'><a class='nav-link barranav' title='Mostrar todo' id='lista_carga' data-lugar='a_productos/carga'><i class="fas fa-list"></i><span>Carga</span></a></li>

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
 function subir(){
   var id = $(this).data('id');
   var ruta = $(this).data('ruta');
   var tipo = $(this).data('tipo');
   var ext = $(this).data('ext');
   var tabla = $(this).data('tabla');
   var campo = $(this).data('campo');
   var keyt = $(this).data('keyt');
   var destino = $(this).data('destino');
   var iddest = $(this).data('iddest');
   var proceso="";
   if ( $(this).data('proceso') ) {
     proceso=$(this).data('proceso');
   }
   $("#modal_form").load("a_productos/archivo.php?id="+id+"&ruta="+ruta+"&ext="+ext+"&tipo="+tipo+"&tabla="+tabla+"&campo="+campo+"&keyt="+keyt+"&destino="+destino+"&iddest="+iddest+"&proceso="+proceso);
 }
 $(document).on('change',"#precarga",function(e){
   e.preventDefault();
   $("#cargando").addClass("is-active");
   var control=$(this).attr('id');
   var accept=$(this).attr('accept');

   var fileSelect = document.getElementById(control);
   var files = fileSelect.files;
   var formData = new FormData();
   for (var i = 0; i < files.length; i++) {
      var file = files[i];
      formData.append('photos'+i, file, file.name);
   }
   var tam=(fileSelect.files[0].size/1024)/1024;
   if (tam<30){
     var xhr = new XMLHttpRequest();
     xhr.open('POST','a_productos/db_.php?function=subir_file');
     xhr.onload = function() {
     };
     xhr.upload.onprogress = function (event) {
       var complete = Math.round(event.loaded / event.total * 100);
       if (event.lengthComputable) {
         btnfile.style.display="none";
         progress_file.style.display="block";
         progress_file.value = progress_file.innerHTML = complete;
         // conteo.innerHTML = "Cargando: "+ nombre +" ( "+complete+" %)";
       }
     };
     xhr.onreadystatechange = function(){
       if(xhr.readyState === 4 && xhr.status === 200){
         progress_file.style.display="none";
         btnfile.style.display="block";
         var data = JSON.parse(xhr.response);
         if(data.error==0){
           Swal.fire({
             type: 'success',
             title: "Se cargó correctamente",
             showConfirmButton: false,
             timer: 1000
           });
           $("#contenedor_file").html("<div style='border:0;float:left;margin:10px;'>"+
           "<input type='hidden' id='direccion' name='direccion' value='"+data.archivo+"'>"+
           "<img src='historial/"+data.archivo+"' width='300px'></div>");
         }
         else{
           alert(xhr.response);
         }
       }
     }
     xhr.send(formData);
   }
   else{
     alert("Archivo muy grande");
   }
   $("#cargando").removeClass("is-active");
 });
 $(document).on('submit','#subir_File',function(e){
   e.preventDefault();
   var direccion = $("#direccion").val();
   $("#cargando").addClass("is-active");
   if ( direccion.length ) {
     $.ajax({
       data:  {
         "function":"subida_orden",
         "direccion":direccion
       },
       url: "a_productos/db_.php",
       type: "post",
       success:  function (response) {
         $('#myModal').modal('hide');
         $("#trabajo").html(response);
       }
     });
   }
   else{
     $.alert('Debe seleccionar un archivo');
   }
   $("#cargando").removeClass("is-active");
 });
 function migrar(){
   var direccion = $("#direccion").val();
   $.ajax({
     data:  {
       "function":"migrar",
       "direccion":direccion
     },
     url: "a_productos/db_.php",
     type: "GET",
     timeout:300000,
     beforeSend: function () {
       $("#cargando").addClass("is-active");
     },
     success:  function (response) {
       console.log(response);
       $("#trabajo").html(response);
       $("#cargando").removeClass("is-active");
     },
     error: function(jqXHR, textStatus, errorThrown) {
       if(textStatus==="timeout") {
         $("#cargando").removeClass("is-active");
         $("#bodyx").html("<div class='container' style='background-color:white; width:300px'><center><img src='img/giphy.gif' width='300px'></center></div><br><center><div class='alert alert-danger' role='alert'>Ocurrio un error intente de nuevo en unos minutos, vuelva a entrar o presione ctrl + F5, para reintentar</div></center> ");
       }
     }
   });
 }

$(document).on('change',"#prejson",function(e){
  console.log("entra");
  var input = document.getElementById('prejson');
  var file = input.files[0];
  var json;
  var reader = new FileReader();
   reader.onload = function(e) {
     return JSON.parse(e.target.result);
   };
   console.log(reader.readAsText(file));

});


</script>
