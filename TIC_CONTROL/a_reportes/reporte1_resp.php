<?php
  require_once("db_.php");
  $pd=$db->reporte1();

  echo "<div class='container-fluid' style='background-color:".$_SESSION['cfondo']."; '>";
  echo "<br><h5>Pedidos emitidas</h5>";
	echo "<hr>";
  ?>

  <div class="content table-responsive table-full-width" >
    <table id='x_lista' class='display compact hover' style='font-size:10pt;'>
    <thead>
    <tr>
    <th># pedido</th>
    <th>Fecha</th>
    <th>Monto</th>
    <th>Envio</th>
    <th>Total</th>
    <th>Estatus</th>
    <th>Factura</th>
    <th>Pago</th>
    <th>Estado del pago</th>
    <th># Pago</th>
    <th>Cliente</th>
    <th>RFC</th>
    <th>Correo</th>
    </tr>
    </thead>
    <tbody>
    <?php
      if (count($pd)>0){
        foreach($pd as $key){
          echo "<tr id='".$key->id."' class='edit-t'>";
          echo "<td>".$key->id."</td>";
          echo "<td>".fecha($key->fecha)."</td>";
          echo "<td class='text-right'>".moneda($key->monto)."</td>";
          echo "<td class='text-right'>".moneda($key->envio)."</td>";
          echo "<td class='text-right'>".moneda($key->total)."</td>";
          echo "<td>".$key->estatus."</td>";
          echo "<td>";
            if($key->factura){
              echo "Facturar";
            }
          echo "</td>";
          echo "<td>".$key->pago."</td>";
          echo "<td>".$key->estado_pago."</td>";
          echo "<td>".$key->idpago."</td>";
          echo "<td>".$key->nombre." ".$key->apellido."</td>";
          echo "<td>".$key->rfc."</td>";
          echo "<td>".$key->correo."</td>";

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
