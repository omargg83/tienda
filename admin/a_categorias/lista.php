<?php
	require_once("db_.php");
	$pd = $db->categorias_lista();
	echo "<div class='container-fluid' style='background-color:".$_SESSION['cfondo']."; '>";
	echo "<br><h5>TIC SHOP Menú</h5>";
	echo "<hr>";
?>
		<div class="content table-responsive table-full-width" >
			<table id='x_lista' class='dataTable compact hover row-border' style='font-size:10pt;'>
			<thead>
			<th>#</th>
			<th>Orden</th>
			<th>Nombre para mostrar</th>
			<th>Subcategorias</th>
			</tr>
			</thead>
			<tbody>
			<?php
				if (count($pd)>0){
					foreach($pd as $key){
						echo "<tr id='".$key['idcategoria']."' class='edit-t'>";
						echo "<td>";
							echo "<div class='btn-group'>";
								echo "<button class='btn btn-outline-secondary btn-sm' id='edit_comision' title='Editar' data-lugar='a_categorias/editar'><i class='fas fa-pencil-alt'></i></i></button>";

									echo "<button class='btn btn-outline-secondary btn-sm' id='eliminar_cat' data-lugar='a_categorias/db_' data-destino='a_categorias/lista' data-id='".$key['idcategoria']."' data-funcion='quitar_principal' data-div='trabajo'><i class='far fa-trash-alt'></i></i></button>";

							echo "</div>";
						echo "</td>";
						echo "<td>".$key["orden"]."</td>";
						echo "<td>".$key["descripcion"]."</td>";

						echo "<td>";
						$row=$db->producto_cat($key['idcategoria']);
						foreach($row as $key){
							echo $key['categoria'].", ";
						}

						echo "</td>";

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
