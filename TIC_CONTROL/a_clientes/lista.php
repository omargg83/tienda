<?php
	require_once("db_.php");
	$pd = $db->clientes_lista();
	echo "<div class='container-fluid' style='background-color:".$_SESSION['cfondo']."; '>";
	echo "<br><h5>Clientes</h5>";
	echo "<hr>";
?>
		<div class="content table-responsive table-full-width" >
			<table id='x_lista' class='dataTable compact hover row-border' style='font-size:10pt;'>
			<thead>
			<th>#</th>
			<th>Nombre</th>
			<th>Apellido</th>
			<th>Correo</th>
			</tr>
			</thead>
			<tbody>
			<?php
				if (count($pd)>0){
					foreach($pd as $key){
						echo "<tr id='".$key['id']."' class='edit-t'>";
						echo "<td>";
							echo "<div class='btn-group'>";
								echo "<button class='btn btn-outline-secondary btn-sm' id='edit_comision' title='Editar' data-lugar='a_clientes/editar'><i class='fas fa-pencil-alt'></i></button>";

								echo "<button class='btn btn-outline-secondary btn-sm' id='eliminar_comision' data-lugar='a_clientes/db_' data-destino='a_clientes/lista' data-id='".$key['id']."' data-funcion='borrar_cliente' data-div='trabajo'><i class='far fa-trash-alt'></i></button>";
							echo "</div>";
						echo "</td>";
						echo "<td>".$key["nombre"]."</td>";
						echo "<td>".$key["apellido"]."</td>";
						echo "<td>".$key["correo"]."</td>";
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
