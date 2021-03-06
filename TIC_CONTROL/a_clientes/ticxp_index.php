<?php
  require_once("db_.php");
 ?>
 <nav class='navbar navbar-expand-sm navbar-light bg-light'>
 		  <a class='navbar-brand' ><i class="fas fa-ticket-alt"></i>Clientes</a>
 		  <button class='navbar-toggler navbar-toggler-right' type='button' data-toggle='collapse' data-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>
 			<span class='navbar-toggler-icon'></span>
 		  </button>
 		  <div class='collapse navbar-collapse' id='navbarSupportedContent'>
 			<ul class='navbar-nav mr-auto'>
        <div class='form-inline my-2 my-lg-0' id='daigual' action='' >
          <div class="input-group  mr-sm-2">
            <input type="text" class="form-control form-control-sm" placeholder="Buscar" aria-label="Buscar" aria-describedby="basic-addon2"  id='buscar' onkeyup='Javascript: if (event.keyCode==13) buscar_cliente()'>
            <div class="input-group-append">
              <button class="btn btn-outline-secondary btn-sm" type="button" onclick='buscar_cliente()'><i class='fas fa-search'></i></button>
            </div>
          </div>
				</div>
 				<li class='nav-item active'><a class='nav-link barranav' title='Mostrar todo' id='new_poliza' data-lugar='a_clientes/editar'><i class="fas fa-folder-plus"></i><span>Nuevo</span></a></li>

 				<li class='nav-item active'><a class='nav-link barranav' title='Mostrar todo' id='lista_prod' data-lugar='a_clientes/lista'><i class="fas fa-list"></i><span>Registrados</span></a></li>

        <li class='nav-item active'><a class='nav-link barranav' title='Mostrar todo' id='lista_sin' data-lugar='a_clientes/lista_sin'><i class="fas fa-list"></i><span>Sin registro</span></a></li>
      </li>
 			</ul>
 		</div>
 	  </div>
 	</nav>

<?php
  echo "<div class='container'>";
   echo "<div id='trabajo' style='margin-top:5px;'>";
    include 'lista.php';
   echo "</div>";
  echo "</div>";
 ?>

 <script type="text/javascript">
  function buscar_cliente(){
    var buscar = $("#buscar").val();
    $.ajax({
      data:  {
        "buscar":buscar
      },
      url:   'a_clientes/lista.php',
      type:  'post',
      success:  function (response) {
        $("#trabajo").html(response);
      }
    });
  }
  function dir_envio(id,idcliente){
    $.confirm({
      title: 'Direción de envío',
      content: '¿Desea establecerla como dirección de envío?',
      buttons: {
        Aceptar: function () {
          $.ajax({
            data:  {
              "function":"dir_envio",
              "id":id,
              "idcliente":idcliente
            },
            url:   'a_clientes/db_.php',
            type:  'post',
            success:  function (response) {

              $.ajax({
                data:  {
                  "id":idcliente
                },
                url:   'a_clientes/editar.php',
                type:  'post',
                success:  function (response) {
                  $("#trabajo").html(response);
                }
              });
              Swal.fire({
                  type: 'success',
                  title: 'Se establecio correctamente',
                  showConfirmButton: false,
                  timer: 1000
              })
              $('#myModal').modal('hide');

            }
          });
        },
        Cancelar: function () {

        }
      }
    });
  }
  function dir_factura(id,idcliente){
    $.confirm({
      title: 'Direción de facturación',
      content: '¿Desea establecerla como dirección de facturación?',
      buttons: {
        Aceptar: function () {
          $.ajax({
            data:  {
              "function":"dir_factura",
              "id":id,
              "idcliente":idcliente
            },
            url:   'a_clientes/db_.php',
            type:  'post',
            success:  function (response) {
              $.ajax({
                data:  {
                  "id":idcliente
                },
                url:   'a_clientes/editar.php',
                type:  'post',
                success:  function (response) {
                  $("#trabajo").html(response);
                }
              });
              Swal.fire({
                  type: 'success',
                  title: 'Se establecio correctamente',
                  showConfirmButton: false,
                  timer: 1000
              })
              $('#myModal').modal('hide');
            }
          });
        },
        Cancelar: function () {

        }
      }
    });
  }
  </script>
