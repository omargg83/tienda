<?php
	require_once("db_.php");
	$pd = $db->pedidos_ct();
	echo "<div class='container-fluid' style='background-color:".$_SESSION['cfondo']."; '>";
	echo "<br><h5>Pedidos</h5>";
	echo "<hr>";
?>
		<div class="content table-responsive table-full-width" >
			<table id='x_lista' class='dataTable compact hover row-border' style='font-size:10pt;'>
			<thead>
			<th># Pedido</th>
			<th>Prod</th>
			<th>Clave</th>
			<th>Cantidad</th>
			<th>Pedido CT</th>
			<th>Estatus</th>

			</tr>
			</thead>
			<tbody>
			<?php
				if (count($pd)>0){
					foreach($pd as $key){
						echo "<tr id='".$key['id']."' class='edit-t'>";
						echo "<div class='btn-group'>";
							echo "<button class='btn btn-outline-secondary btn-sm' id='edit_comision' title='Editar' data-lugar='a_pedidos/editar'><i class='fas fa-pencil-alt'></i></button>";
						echo "</div>";
						echo "<td>".$key["id"]."</td>";
						echo "<td>".$key["idprod"]."</td>";
						echo "<td>".$key["clave"]."</td>";
						echo "<td>".$key["cantidad"]."</td>";
						echo "<td>".$key["pedidoWeb"]."</td>";
						echo "<td>".$key["estatus"]."</td>";
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
