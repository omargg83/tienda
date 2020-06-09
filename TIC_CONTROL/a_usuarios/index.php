<?php
  require_once("db_.php");
  
  if($_SESSION['nivel']!=1){
    echo "<h4>Página no encontrada</h4>";
    die();
  }

  $_SESSION['nivel_captura']=1;
 ?>

 <nav class='navbar navbar-expand-sm navbar-light bg-light'>
 		  <a class='navbar-brand' ><i class="fas fa-users"></i>Usuarios</a>
 		  <button class='navbar-toggler navbar-toggler-right' type='button' data-toggle='collapse' data-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>
 			<span class='navbar-toggler-icon'></span>
 		  </button>
 		  <div class='collapse navbar-collapse' id='navbarSupportedContent'>
 			<ul class='navbar-nav mr-auto'>
        <div class='form-inline my-2 my-lg-0' id='daigual' action='' >
					<input class='form-control mr-sm-2' type='search' placeholder='Buscar' aria-label='Search' name='buscar' id='buscar'  onkeyup='Javascript: if (event.keyCode==13) buscar_usuario()'>
          <div class='btn-group'>
            <button type='button' class='btn btn-outline-warning btn-sm' onclick='buscar_usuario()'><i class='fas fa-search'></i>Buscar</button>
          </div>
				</div>

 				<li class='nav-item active'><a class='nav-link barranav' title='Mostrar todo' id='new_poliza' data-lugar='a_usuarios/editar'><i class="fas fa-folder-plus"></i><span>Nuevo</span></a></li>
 				<li class='nav-item active'><a class='nav-link barranav' title='Mostrar todo' id='lista_prod' data-lugar='a_usuarios/lista'><i class="fas fa-list"></i><span>Lista</span></a></li>

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

  function buscar_usuario(){
    var buscar = $("#buscar").val();
    $.ajax({
      data:  {
        "buscar":buscar
      },
      url:   'a_usuarios/lista.php',
      type:  'post',
      success:  function (response) {
        $("#trabajo").html(response);
      }
    });
  }
usuario</script>
