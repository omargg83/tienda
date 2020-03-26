<?php
  require_once("db_.php");
  $productos=$db->productos_numero();

 ?>
  <div class='container-fluid'>
    <div class="card-group">
      <div class="card bg-light acceso">
        <div class="card-body text-center">
          <p class="card-text">Productos <?php echo $productos->total; ?></p>
        </div>
        <div class="card-footer">
          <a class="btn btn-light btn-sm btn-lg btn-block" href='#a_productos/index'><i class="fab fa-product-hunt"></i>Ver</a>
        </div>
      </div>
      <div class="card bg-light acceso">
        <div class="card-body text-center">
          <p class="card-text">Pedidos</p>
        </div>
        <div class="card-footer">
          <a class="btn btn-light btn-sm btn-lg btn-block" href='#a_pedidos/index'><i class="fas fa-shopping-basket"></i>Ver</a>
        </div>
      </div>
      <div class="card bg-light acceso">
        <div class="card-body text-center">
          <p class="card-text">Some text inside the third card</p>
        </div>
      </div>
      <div class="card bg-light acceso">
        <div class="card-body text-center">
          <p class="card-text">Some text inside the fourth card</p>
        </div>
      </div>
  </div>
</div>
