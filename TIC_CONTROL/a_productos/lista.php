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
					<th>Producto</th>
					<th>Nombre</th>
					<th>Precio</th>
					<th>Envio</th>
					<th>Modelo</th>
					<th>Marca</th>
					<th>Existencia</th>
					<th>Imagen</th>
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
							echo "</div>";
						echo "</td>";

						echo "<td>".$key["clave"]."</td>";
						echo "<td>";
						if($key["interno"]==0){
							echo "CT";
						}
						else{
							echo "TIC";
						}
						echo "</td>";
						echo "<td>".$key["nombre"]."</td>";

						echo "<td>";
						if($key["precio_tipo"]==0){
							echo moneda($key["preciof"]);
						}
						if($key["precio_tipo"]==1){
							$total=$key["preciof"]+(($key["preciof"]*$db->cgeneral)/100);
							echo moneda($total);
						}
						if($key["precio_tipo"]==2){
							echo moneda($key["precio_tic"]);
						}
						if($key["precio_tipo"]==3){
							$total=$key["precio_tic"]+(($key["precio_tic"]*$db->cgeneral)/100);
							echo moneda($total);
						}
						echo "</td>";

						echo "<td>";
						if($key["envio_tipo"]==0){
							echo moneda($db->egeneral);
						}
						if($key["envio_tipo"]==1){
							echo moneda($key["envio_costo"]);
						}
						echo "</td>";
						echo "<td>".$key["modelo"]."</td>";
						echo "<td>".$key["marca"]."</td>";
						echo "<td>".$key["existencia"]."</td>";
						echo "<td>";
							$a="?id=".rand(1,1500);
							echo "<img src='".$db->doc1.$key['img']."$a' width='50px' />";
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
		lista("x_prod");
	} );
</script>
