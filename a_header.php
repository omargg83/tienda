<?php
  $contar=0;
  $sumar=0;
  $contar_wish=0;
  $t_carro=0;
  if(isset($_SESSION['idcliente'])){
    $res2=$db->wish_sum();
    $contar_wish=$res2->contar;

    $contar=0;
    $envio=0;
    $t_carro=0;
    $carro=$db->carro_list();
    foreach($carro as $key){
      $preciof=0;
      $enviof=0;
      $contar++;
      ///////////////precio

      if($key->precio_tipo==0){
        $preciof=$key->preciof;
      }
      if($key->precio_tipo==1){
        $p_total=$key->preciof+(($key->preciof*$db->cgeneral)/100);
        $preciof=$p_total;
      }
      if($key->precio_tipo==2){
        $preciof=$key->precio_tic;
      }
      if($key->precio_tipo==3){
        $p_total=$key->precio_tic+(($key->precio_tic*$db->cgeneral)/100);
        $preciof=$p_total;
      }

      ////////////envio
      if($key->envio_tipo==0){
        $envio=$db->egeneral;
      }
      if($key->envio_tipo==1){
        $envio=$key->envio_costo;
      }

      $p_final=($key->cantidad*($preciof+$envio));
      $t_carro+=$p_final;
    }
  }
  $t_carro=$t_carro*1.16;
?>
  <!-- Top Bar -->
  <?php
	  $key=$db->baner2(3);
    if($key->activo==1){
      echo "<div class='alerta' style='background-color:#b4f22f; '>";
      	echo "<div class='container'>";

         echo "<div class='row'>";
         	echo "<a style='text-align: center;width: 100%;cursor: pointer;height: 50px;color: black;padding-top: 15px;font-weight: 600;' href='$key->enlace'> $key->texto </a>";
         echo "</div>";
        echo "</div>";
      echo "</div>";
    }
  ?>

  <div class="top_bar">
    <div class="container">
      <div class="row">
        <div class="col d-flex flex-row">
          <div class="top_bar_contact_item"><div class="top_bar_icon"><img src="/img/telefono blanco.png" alt=""style="width: 30px;"></div>771 702 8040</div>
          <div class="top_bar_contact_item"><div class="top_bar_icon"><img src="/img/email.png" alt="" style="width: 30px;"></div><a href="mailto:ventas@tic-shop.com.mx">ventas@tic-shop.com.mx</a></div>
          <div class="top_bar_content ml-auto">

            <div class="top_bar_user">
              <div class="user_icon"><img src="/images/user.svg" alt=""></div>
              <?php
                if(isset($_SESSION['autoriza_web']) and $_SESSION['autoriza_web']==1 and strlen($_SESSION['idcliente'])>0 and $_SESSION['interno']==1){
                  echo "<div><a href='/clientes.php'>".$_SESSION['correo']."</a></div>";
                  echo "<div><a href='#' onclick='salir()'>Salir</a></div>";
                }
    						else{
                  echo "<div><a href='/registro/'>Registro</a></div>";
                  echo "<div><a href='/acceso/'>Ingresar</a></div>";
                }
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Header Main -->

  <div class="header_main">
    <div class="container">
      <div class="row">

        <!-- Logo -->
        <div class="col-lg-2 col-sm-3 col-3 order-1">
          <div class="logo_container">
            <div class="logo"><a href="/"><img src='/img/logo-ticshop.png' width='100px'></a></div>
          </div>
        </div>

        <!-- Search -->
        <div class="col-lg-6 col-12 order-lg-2 order-3 text-lg-left text-right">
          <div class="header_search">
            <div class="header_search_content">
              <div class="header_search_form_container">

                  <input type="search" required="required" class="header_search_input" id='bucar_text' name='bucar_text' onkeyup='Javascript: if (event.keyCode==13) buscar_prod()' placeholder="Buscar productos...">
                  <button type="button" class="header_search_button trans_300" onclick="buscar_prod()"><img src="/images/search.png" alt=""></button>
                  <div class="custom_dropdown" style="display: none;">
                    <div class="custom_dropdown_list">
                      <span class="custom_dropdown_placeholder"></span>
                      <ul class="custom_list">
                      </ul>
                    </div>
                  </div>

              </div>
            </div>
          </div>
        </div>

        <!-- Wishlist -->
        <div class="col-lg-4 col-9 order-lg-3 order-2 text-lg-left text-right">
          <div class="wishlist_cart d-flex flex-row align-items-center justify-content-end">
            <div class="wishlist d-flex flex-row align-items-center justify-content-end">
              <div class="wishlist_icon"><img src="/images/heart.png" alt=""></div>
              <div class="wishlist_content">
                <div class="wishlist_text"><a href="/deseos/">Deseos</a></div>
                <div class="wishlist_count" id='wish_count'><?php echo $contar_wish; ?></div>
              </div>
            </div>

            <!-- Cart -->
            <div class="cart" ><a href="cart.php">
              <div class="cart_container d-flex flex-row align-items-center justify-content-end">
                <div class="cart_icon">
                  <img src="/images/cart.png" alt="">
                  <div class="cart_count"><span><?php echo $contar; ?></span></div>
                </div>
                <div class="cart_content">
                  <div class="cart_text"><a href="/carrito/">Carrito</a></div>
                  <div class="cart_price"><a href="/carrito/"><?php echo moneda($t_carro); ?></a></div>
                </div>
              </div>
            </a>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- Main Navigation -->

  <nav class="main_nav">
    <div class="container">
      <div class="row">
        <div class="col">

          <div class="main_nav_content d-flex flex-row">

            <!-- Categories Menu -->

            <div class="cat_menu_container">
              <div class="cat_menu_title d-flex flex-row align-items-center justify-content-start">
                <div class="cat_burger"><span></span><span></span><span></span></div>
                <div class="cat_menu_text">Categorías</div>
              </div>

              <ul class="cat_menu">
                <?php
                  $cat=$db->categorias();
                  foreach($cat as $key){
                    echo "<li class='hassubs'>
                      <a href='/tienda.php?tipo=1&id=".$key->idcategoria."'>".$key->descripcion."<i class='fas fa-chevron-right'></i></a>
                      <ul>";
                      foreach($db->cat_ct($key->idcategoria) as $key2){
                        echo "<li class='hassubs'>
                            <a href='/tienda.php?tipo=2&id=".$key2->id."'>".$key2->heredado."<i class='fas fa-chevron-right'></i></a>
                            <ul>";
                            foreach($db->sub_cat($key2->id) as $key3){
                              echo " <li><a href='/tienda.php?tipo=3&id=".$key3->id."'>".$key3->heredado."<i class='fas fa-chevron-right'></i></a></li>";
                            }
                            echo "</ul>";
                          echo "</li>";
                      }
                      echo "</ul>";
                    echo "</li>";
                  }
                ?>

              </ul>
            </div>

            <!-- Main Nav Menu -->

            <div class="main_nav_menu ml-auto">
              <ul class="standard_dropdown main_nav_dropdown">
                <li><a href="/">Inicio<i class="fas fa-chevron-down"></i></a></li>
                <li><a href="/nosotros.php">Nosotros<i class="fas fa-chevron-down"></i></a></li>
                <li><a href="/tienda.php">Tienda<i class="fas fa-chevron-down"></i></a></li>
                <li><a href="/contact.php">Contacto<i class="fas fa-chevron-down"></i></a></li>
              </ul>
            </div>

            <!-- Menu Trigger -->

            <div class="menu_trigger_container ml-auto">
              <div class="menu_trigger d-flex flex-row align-items-center justify-content-end">
                <div class="menu_burger">
                  <div class="menu_trigger_text">menu</div>
                  <div class="cat_burger menu_burger_inner"><span></span><span></span><span></span></div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </nav>

  <!-- Menu -->

  <div class="page_menu">
    <div class="container">
      <div class="row">
        <div class="col">

          <div class="page_menu_content">

            <div class="page_menu_search">
              <form action="#">
                <input type="search" required="required" class="page_menu_search_input" name='bucar_textm' id='bucar_textm' placeholder="Buscar productos" onkeyup='Javascript: if (event.keyCode==13) buscar_prod2()'>
              </form>
            </div>
            <ul class="page_menu_nav">
              <li class="page_menu_item">
                <a href="/">Inicio<i class="fa fa-angle-down"></i></a>
              </li>

              <li class="page_menu_item">
                <a href="/nosotros.php">Nosotros<i class="fa fa-angle-down"></i></a>
              </li>
              <li class="page_menu_item">
                <a href="/tienda.php">tienda<i class="fa fa-angle-down"></i></a>
              </li>
              <li class="page_menu_item">
                <a href="/contact.php">Contacto<i class="fa fa-angle-down"></i></a>
              </li>
            </ul>

            <div class="menu_contact">
              <div class="menu_contact_item"><div class="menu_contact_icon"><img src="images/phone_white.png" alt=""></div>771 702 8040</div>
              <div class="menu_contact_item"><div class="menu_contact_icon"><img src="images/mail_white.png" alt=""></div><a href="mailto:ventas@tic-shop.com.mx">ventas@tic-shop.com.mx</a></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
