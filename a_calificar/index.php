<?php
  require_once("db_.php");
  $_SESSION['nivel_captura']=1;
 ?>
 <nav class='navbar navbar-expand-sm navbar-light bg-light'>
 		  <a class='navbar-brand' ><i class="far fa-star"></i>Calificaciones</a>
 		  <button class='navbar-toggler navbar-toggler-right' type='button' data-toggle='collapse' data-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>
 			<span class='navbar-toggler-icon'></span>
 		  </button>
 		  <div class='collapse navbar-collapse' id='navbarSupportedContent'>
 			<ul class='navbar-nav mr-auto'>
        <div class='form-inline my-2 my-lg-0' id='daigual' action='' >
          <div class="input-group  mr-sm-2">
            <input type="text" class="form-control form-control-sm" placeholder="Buscar" aria-label="Buscar" aria-describedby="basic-addon2"  id='buscar' onkeyup='Javascript: if (event.keyCode==13) buscar_star()'>
            <div class="input-group-append">
              <button class="btn btn-outline-secondary btn-sm" type="button" onclick='buscar_star()'><i class='fas fa-search'></i></button>
            </div>
          </div>
				</div>
 				<li class='nav-item active'><a class='nav-link barranav' title='Mostrar todo' id='lista_prod' data-lugar='a_calificar/lista'><i class="fas fa-list"></i><span>Lista</span></a></li>
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

  function buscar_star(){
    var buscar = $("#buscar").val();
    $.ajax({
      data:  {
        "buscar":buscar
      },
      url:   'a_calificar/lista.php',
      type:  'post',
      success:  function (response) {
        $("#trabajo").html(response);
      }
    });
  }
  function publica_c(){
    var id = $("#id").val();
    $.ajax({
      data:  {
        "id":id,
        "activo":1,
        "function":"guardar_producto"
      },
      url:   'a_calificar/db_.php',
      type:  'post',
      success:  function (response) {
        var datos = JSON.parse(response);
        if (datos.error==0){
          Swal.fire({
              type: 'success',
              title: 'Se publicó correctamente',
              showConfirmButton: false,
              timer: 1000
          });
        }
        else{

        }
      }
    });
  }

  function no_publica_c(){
    var id = $("#id").val();
    $.ajax({
      data:  {
        "id":id,
        "activo":0,
        "function":"guardar_producto"
      },
      url:   'a_calificar/db_.php',
      type:  'post',
      success:  function (response) {
        var datos = JSON.parse(response);
        if (datos.error==0){
          Swal.fire({
              type: 'success',
              title: 'Se bloqueó correctamente',
              showConfirmButton: false,
              timer: 1000
          });
        }
        else{

        }
      }
    });
  }

  </script>
