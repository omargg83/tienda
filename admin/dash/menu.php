<?php
  require_once("db_.php");
?>
<div class='wrapper'>
  <div class='content navbar-default'>
    <div class='container-fluid'>
      <div class='sidebar' id='navx'>
        <a href='#dash/index' class='activeside'><i class='fas fa-home'></i><span>Inicio</span></a>
        <a href='#a_productos/index' title='Productos'><i class='fab fa-product-hunt'></i><span>Productos</span></a>
        <a href='#a_almacen/index' title='Almacén'><i class='fas fa-boxes'></i><span>Almacén</span></a>
        <a href='#a_categorias/index' title='TIC SHOP Menú'><i class='fab fa-buffer'></i><span>TIC SHOP Menú</span></a>
        <a href='#a_categoriasct/index' title='Categorias Ct'><i class='fab fa-buffer'></i><span>Categorias CT</span></a>
        <a href='#a_clientes/index' title='Clientes'><i class='fas fa-user-tag'></i><span>Clientes</span></a>
        <a href='#a_cupones/index' title='Cupones'><i class='fas fa-ticket-alt'></i><span>Cupones</span></a>
        <a href='#a_pedidos/index' title='Pedidos'><i class='fas fa-shopping-basket'></i><span>Pedidos</span></a>
        <a href='#a_contacto/index' title='Contacto'><i class='fas fa-file-signature'></i><span>Contacto</span></a>
        <a href='#a_calificar/index' title='Calificaciones'><i class="far fa-star"></i><span>Calificaciones</span></a>
        <a href='#a_reportes/index' title='Reportes'><i class='fas fa-chart-line'></i><span>Reportes</span></a>
        <hr>
        <?php
        /*
          if($_SESSION['nivel']==1){
            echo "<a href='#a_pagina/index' title='Banners'><i class="far fa-file-image"></i><span>banners</span></a>";
            echo "<a href='#a_ajustes/index' title='Cupones'><i class='fas fa-tools'></i><span>Ajustes</span></a>";
            echo "<a href='#a_usuarios/index' title='Usuarios'><i class='fas fa-users'></i> <span>Usuarios</span></a>";
          }
          */
        ?>
      </div>
    </div>
    <div class='fijaproceso main' id='contenido'>
    </div>
  </div>
</div>
