<?php
	require_once("db_.php");
	$pd = $db->bloqueos();
	echo "<div class='container-fluid' style='background-color:".$_SESSION['cfondo']."; '>";
	echo "<br><h5>Productos</h5>";
	echo "<hr>";
?>
		<div class="content table-responsive table-full-width" >
			<table id='x_lista' class='dataTable compact hover row-border' style='font-size:10pt;'>
			<thead>
			<th>#</th>
			<th>Nombre</th>
			<th>Correo</th>
			</tr>
			</thead>
			<tbody>
			<?php
				if (count($pd)>0){
					foreach($pd as $key){
						echo "<tr id='".$key['idpersona']."' class='edit-t'>";
						echo "<td>";
							echo "<div class='btn-group'>";
								echo "<button class='btn btn-outline-secondary btn-sm' id='edit_comision' title='Editar' data-lugar='a_usuarios/editar'><i class='fas fa-pencil-alt'></i></i></button>";
								echo "<button class='btn btn-outline-secondary btn-sm' id='eliminar_comision' data-lugar='a_usuarios/db_' data-destino='a_usuarios/lista' data-id='".$key['idpersona']."' data-funcion='borrar_usuario' data-div='trabajo'><i class='far fa-trash-alt'></i></i></button>";
							echo "</div>";
						echo "</td>";

						echo "<td>".$key["nombre"]."</td>";
						echo "<td>".$key["correo_xptic"]."</td>";
						echo "</tr>";
					}
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
