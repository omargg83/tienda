<?php
	require_once("db_.php");
	$pd = $db->baner_lista();
	echo "<div class='container-fluid' style='background-color:".$_SESSION['cfondo']."; '>";
	echo "<br><h5>Banner</h5>";
	echo "<hr>";
	echo "<button class='btn btn-outline-secondary btn-sm' title='Agregar imagen' id='new_poliza' data-lugar='a_pagina/baner1'><i class='fas fa-folder-plus'></i><span>Agregar imagen</span></button>";
	echo "<hr>";
?>
		<div class="content table-responsive table-full-width" >
			<table id='x_lista' class='dataTable compact hover row-border' style='font-size:10pt;'>
			<thead>
			<th>#</th>
			<th>Titulo</th>
			</tr>
			</thead>
			<tbody>
			<?php

				foreach($pd as $key){
					echo "<tr id='".$key['id']."' class='edit-t'>";
					echo "<td>";
						echo "<div class='btn-group'>";

							echo "<button class='btn btn-outline-secondary btn-sm' id='edit_comision' title='Editar' data-lugar='a_pagina/baner1'><i class='fas fa-pencil-alt'></i></button>";

							echo "<button class='btn btn-outline-secondary btn-sm' id='eliminar_baner' data-lugar='a_pagina/db_' data-destino='a_pagina/lista' data-id='".$key['id']."' data-funcion='quitar_baner' data-div='trabajo'><i class='far fa-trash-alt'></i></button>";

						echo "</div>";
					echo "</td>";
					echo "<td>".$key["titulo"]."</td>";
					echo "</tr>";
				}

			?>
			</tbody>
			</table>
		</div>
	</div>

<script>
	$(document).ready( function () {
		lista("x_lista");
	} );
</script>
