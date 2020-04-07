<?php
  require_once("db_.php");
  $_SESSION['nivel_captura']=1;
 ?>

 <nav class='navbar navbar-expand-sm navbar-light bg-light'>
 		  <a class='navbar-brand' ><i class="fas fa-users"></i>Página</a>
 		  <button class='navbar-toggler navbar-toggler-right' type='button' data-toggle='collapse' data-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>
 			<span class='navbar-toggler-icon'></span>
 		  </button>
 		  <div class='collapse navbar-collapse' id='navbarSupportedContent'>
 			<ul class='navbar-nav mr-auto'>

				<li class='nav-item active'><a class='nav-link barranav' title='Mostrar todo' id='new_poliza' data-lugar='a_pagina/baner1'><i class="fas fa-folder-plus"></i><span>Banner 1</span></a></li>


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
