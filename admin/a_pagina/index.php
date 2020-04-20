<?php
  require_once("db_.php");
  $_SESSION['nivel_captura']=1;
  if($_SESSION['nivel']!=1){
    echo "<h4>Página no encontrada</h4>";
    die();
  }
 ?>

 <nav class='navbar navbar-expand-sm navbar-light bg-light'>
 		  <a class='navbar-brand' ><i class="fas fa-users"></i>Página</a>
 		  <button class='navbar-toggler navbar-toggler-right' type='button' data-toggle='collapse' data-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>
 			<span class='navbar-toggler-icon'></span>
 		  </button>
 		  <div class='collapse navbar-collapse' id='navbarSupportedContent'>
 			<ul class='navbar-nav mr-auto'>

        <li class='nav-item active'><a class='nav-link barranav' title='Banner 1' id='lista_baner1' data-lugar='a_pagina/lista'><i class="fas fa-list"></i><span>Banner principal</span></a></li>
        <li class='nav-item active'><a class='nav-link barranav' title='Banner 2' id='lista_baner2' data-lugar='a_pagina/lista2'><i class="fas fa-list"></i><span>Banner 2</span></a></li>
        <li class='nav-item active'><a class='nav-link barranav' title='Banner 3' id='lista_baner3' data-lugar='a_pagina/lista3'><i class="fas fa-list"></i><span>Banner 3</span></a></li>
        <li class='nav-item active'><a class='nav-link barranav' title='Banner 3' id='lista_baner4' data-lugar='a_pagina/lista4'><i class="fas fa-list"></i><span>Banner 4</span></a></li>

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
