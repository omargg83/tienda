<?php
	require_once("db_.php");
	$pd = $db->categorias_lista();
	echo "<div class='container-fluid' style='background-color:".$_SESSION['cfondo']."; '>";
	echo "<br><h5>Categorias CT</h5>";
	echo "<hr>";
?>
		<div class="content table-responsive table-full-width" >
			<table id='x_lista' class='dataTable compact hover row-border' style='font-size:10pt;'>
			<thead>
			<th>#</th>
			<th>Categoria CT</th>
			<th>Nombre para mostrar</th>
			<th>Subcategoria CT</th>
			</tr>
			</thead>
			<tbody>
			<?php
				if (count($pd)>0){
					foreach($pd as $key){
						echo "<tr id='".$key['id']."' class='edit-t'>";
						echo "<td>";
							echo "<div class='btn-group'>";
								echo "<button class='btn btn-outline-secondary btn-sm' id='edit_comision' title='Editar' data-lugar='a_categoriasct/editar'><i class='fas fa-pencil-alt'></i></i></button>";
							echo "</div>";
						echo "</td>";
						echo "<td>".$key["categoria"]."</td>";
						echo "<td>".$key["heredado"]."</td>";
						echo "<td>";
						$row=$db->producto_cat($key['id']);
						foreach($row as $key){
							echo $key['subcategoria'].", ";
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