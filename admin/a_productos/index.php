<?php
  require_once("db_.php");
  $_SESSION['nivel_captura']=1;
 ?>

 <nav class='navbar navbar-expand-lg navbar-light bg-light'>
 		  <a class='navbar-brand' ><i class='fas fa-clipboard'></i>Polizas</a>
 		  <button class='navbar-toggler navbar-toggler-right' type='button' data-toggle='collapse' data-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>
 			<span class='navbar-toggler-icon'></span>
 		  </button>
 		  <div class='collapse navbar-collapse' id='navbarSupportedContent'>
 			<ul class='navbar-nav mr-auto'>
        <div class='form-inline my-2 my-lg-0' id='daigual' action='' >
					<input class='form-control mr-sm-2' type='search' placeholder='Buscar' aria-label='Search' name='buscar' id='buscar'  onkeyup='Javascript: if (event.keyCode==13) buscarx()'>
          <div class='btn-group'>
            <div class="input-group-text"><label><input type='checkbox' value='1' id='c_pol' name='c_pol'><span>Poliza</span></label></div>
            <button type='button' class='btn btn-outline-warning btn-sm' onclick='buscarx()'><i class='fas fa-search'></i>Buscar</button>
          </div>
				</div>


 				<li class='nav-item active'><a class='nav-link barranav' title='Mostrar todo' id='new_poliza' data-lugar='a_productos/nuevo'><i class='far fa-sticky-note'></i><span>NUEVO</span></a></li>

      </li>

 			</ul>
 		</div>
 	  </div>
 	</nav>

<?php
  echo "<div class='container'>";
   echo "<div id='trabajo' style='margin-top:5px;'>";

   echo "</div>";
  echo "</div>";
 ?>
