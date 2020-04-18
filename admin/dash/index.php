<?php
  require_once("db_.php");
  $productos=$db->productos_numero();
  $resp=$db->productos_top();
  $ped=$db->pedidos_top();
  echo $_SESSION['idpersona'];
 ?>
  <div class='container-fluid'>
    <div class="card-group ">
      <div class="card bg-light acceso col-sm-12 col-lg-6">
        <div class="card-body" >
          <p class="card-text">Productos: <b><?php echo $productos->total; ?></b></p>
          <?php
            echo "<div class='content table-responsive table-full-width'>";
              echo "<table class='table table-sm' style='font-size:14px;'>";
              echo "<thead>";
              echo "<tr><th>Nombre</th><th>Existencia</th><th>Precio CT</th></tr>";
              echo "</thead>";
              foreach($resp as $key){
                echo "<tr>";
                  echo "<td>";
                    echo $key->nombre;
                  echo "</td>";
                  echo "<td class='text-center'>";
                    echo $key->existencia;
                  echo "</td>";
                  echo "<td class='text-right'>";
                    echo moneda($key->preciof);
                  echo "</td>";
                echo "</tr>";
              }
              echo "</table>";
            echo "</div>";
          ?>
        </div>
        <div class="card-footer">
          <a class="btn btn-light btn-sm btn-lg btn-block" href='#a_productos/index'><i class="fab fa-product-hunt"></i>Ir</a>
        </div>
      </div>
      <div class="card bg-light acceso col-9">
        <div class="card-body text-center">
          <p class="card-text"><b>Pedidos</b></p>

          <?php
            echo "<div class='content table-responsive table-full-width'>";
              echo "<table class='table table-sm' style='font-size:14px;'>";
              echo "<thead>";
              echo "<tr><th>Fecha</th><th>Estado</th><th>Monto</th></tr>";
              echo "</thead>";
              foreach($ped as $key){
                echo "<tr>";
                  echo "<td>";
                    echo fecha($key->fecha);
                  echo "</td>";
                  echo "<td class='text-center'>";
                    echo $key->estado;
                  echo "</td>";
                  echo "<td class='text-right'>";
                    echo moneda($key->monto);
                  echo "</td>";
                echo "</tr>";
              }
              echo "</table>";
            echo "</div>";
          ?>


        </div>
        <div class="card-footer">



          <a class="btn btn-light btn-sm btn-lg btn-block" href='#a_pedidos/index'><i class="fas fa-shopping-basket"></i>Ver</a>
        </div>
      </div>

      </div>
  </div>
</div>
