<?php
	require_once("db_.php");
	$pd = $db->cupones_lista();
	echo "<div class='container-fluid' style='background-color:".$_SESSION['cfondo']."; '>";
	echo "<br><h5>Cupones</h5>";
	echo "<hr>";
?>
		<div class="content table-responsive table-full-width" >
			<table id='x_lista' class='dataTable compact hover row-border' style='font-size:10pt;'>
			<thead>
			<th>#</th>
			<th>Código</th>
			<th>Descripción</th>
			<th>Tipo</th>
			</tr>
			</thead>
			<tbody>
			<?php
				if (count($pd)>0){
					foreach($pd as $key){
						echo "<tr id='".$key['id']."' class='edit-t'>";
						echo "<td>";
							echo "<div class='btn-group'>";
								echo "<button class='btn btn-outline-secondary btn-sm' id='edit_comision' title='Editar' data-lugar='a_cupones/editar'><i class='fas fa-pencil-alt'></i></i></button>";
							echo "</div>";
						echo "</td>";
						echo "<td>".$key["codigo"]."</td>";
						echo "<td>".$key["descripcion"]."</td>";
						echo "<td>".$key["tipo"]."</td>";
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
