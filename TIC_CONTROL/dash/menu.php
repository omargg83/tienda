<?php
  require_once("db_.php");
?>
<div class='wrapper'>
  <div class='content navbar-default'>
    <div class='container-fluid'>
      <div class='sidebar' id='navx'>
        <a href='#dash/tic_index' class='activeside'><i class='fas fa-home'></i><span>Inicio</span></a>
        <a href='#a_productos/ticxp_index' title='Productos'><i class='fab fa-product-hunt'></i><span>Productos</span></a>
        <a href='#a_almacen/ticxp_index' title='Almacén'><i class='fas fa-boxes'></i><span>Almacén</span></a>
        <a href='#a_categorias/ticxp_index' title='TIC SHOP Menú'><i class='fab fa-buffer'></i><span>TIC SHOP Menú</span></a>
        <a href='#a_categoriasct/ticxp_index' title='Categorias Ct'><i class='fab fa-buffer'></i><span>Categorias CT</span></a>
        <a href='#a_clientes/ticxp_index' title='Clientes'><i class='fas fa-user-tag'></i><span>Clientes</span></a>
        <a href='#a_cupones/ticxp_index' title='Cupones'><i class='fas fa-ticket-alt'></i><span>Cupones</span></a>
        <a href='#a_pedidos/ticxp_index' title='Pedidos'><i class='fas fa-shopping-basket'></i><span>Pedidos</span></a>
        <a href='#a_contacto/ticxp_index' title='Contacto'><i class='fas fa-file-signature'></i><span>Contacto</span></a>
        <a href='#a_calificar/ticxp_index' title='Calificaciones'><i class="far fa-star"></i><span>Calificaciones</span></a>
        <a href='#a_reportes/ticxp_index' title='Reportes'><i class='fas fa-chart-line'></i><span>Reportes</span></a>
        <hr>
        <?php
          if($_SESSION['nivel']==1){
            echo "<a href='#a_pagina/ticxp_index' title='Banners'><i class='far fa-file-image'></i><span>banners</span></a>";
            echo "<a href='#a_ajustes/ticxp_index' title='Cupones'><i class='fas fa-tools'></i><span>Ajustes</span></a>";
            echo "<a href='#a_usuarios/ticxp_index' title='Usuarios'><i class='fas fa-users'></i> <span>Usuarios</span></a>";
          }
        ?>
      </div>
    </div>
    <div class='fijaproceso main' id='contenido'>
    </div>
  </div>
</div>
