<?php
	require_once("db_.php");
	$pd = $db->productos_lista();
	echo "<div class='container-fluid' style='background-color:".$_SESSION['cfondo']."; '>";
	echo "<br><h5>Productos</h5>";
	echo "<hr>";
?>
		<div class="content table-responsive table-full-width" >
			<table id='x_prod' class='dataTable compact hover row-border' style='font-size:10pt;'>
				<thead>
				<tr>
					<th>#</th>
					<th>Clave</th>
					<th>Nombre</th>
					<th>Precio</th>
					<th>Moneda</th>
					<th>Modelo</th>
					<th>Marca</th>
					<th>Existencia</th>
				</tr>
			</thead>
			<tbody>
			<?php
				if (count($pd)>0){
					foreach($pd as $key){
						echo "<tr id='".$key['id']."' class='edit-t'>";
						echo "<td>";
							echo "<div class='btn-group'>";
								echo "<button class='btn btn-outline-secondary btn-sm' id='edit_comision' title='Editar' data-lugar='a_productos/editar'><i class='fas fa-pencil-alt'></i></i></button>";
								echo "<button class='btn btn-outline-secondary btn-sm' id='eliminar_comision' data-lugar='a_productos/db_' data-destino='a_productos/lista' data-id='".$key['id']."' data-funcion='borrar_oficio' data-div='trabajo'><i class='far fa-trash-alt'></i></i></button>";
							echo "</div>";
						echo "</td>";

						echo "<td>".$key["clave"]."</td>";
						echo "<td>".$key["nombre"]."</td>";
						echo "<td>".$key["precio"]."</td>";
						echo "<td>".$key["moneda"]."</td>";
						echo "<td>".$key["modelo"]."</td>";
						echo "<td>".$key["marca"]."</td>";
						echo "<td>".$key["existencia"]."</td>";
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
		lista("x_prod");
	} );
</script>
