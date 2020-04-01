<?php
  $contar=0;
  $sumar=0;
  if(isset($_SESSION['idcliente'])){
    $res=$db->carrito_sum();
    $contar=$res->contar;
    $sumar=$res->sumar;

    $res2=$db->wish_sum();
    $contar_wish=$res2->contar;
  }
  $cat=$db->categorias();
?>
  <!-- Top Bar -->

  <div class="top_bar">
    <div class="container">
      <div class="row">
        <div class="col d-flex flex-row">
          <div class="top_bar_contact_item"><div class="top_bar_icon"><img src="images/phone.png" alt=""></div>777 324 77324</div>
          <div class="top_bar_contact_item"><div class="top_bar_icon"><img src="images/mail.png" alt=""></div><a href="mailto:fastsales@gmail.com">correo@gmail.com</a></div>
          <div class="top_bar_content ml-auto">

            <div class="top_bar_user">
              <div class="user_icon"><img src="images/user.svg" alt=""></div>
              <?php
                if(isset($_SESSION['autoriza_web']) and $_SESSION['autoriza_web']==1 and strlen($_SESSION['idcliente'])>0 and $_SESSION['interno']==1){
                  echo "<div><a href='cart.php'>".$_SESSION['correo']."</a></div>";
                  echo "<div><a href='#' onclick='salir()'>Salir</a></div>";
                }
    						else{
                  echo "<div><a href='registro.php'>Registro</a></div>";
                  echo "<div><a href='acceso.php'>Ingresar</a></div>";
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
            <div class="logo"><a href="index.php"><img src='img/logo-ticshop.png' width='100px'></a></div>
          </div>
        </div>

        <!-- Search -->
        <div class="col-lg-6 col-12 order-lg-2 order-3 text-lg-left text-right">
          <div class="header_search">
            <div class="header_search_content">
              <div class="header_search_form_container">
                <form class="header_search_form clearfix">
                  <input type="search" required="required" class="header_search_input" id='bucar_text' name='bucar_text' placeholder="Buscar productos..." onkeyup='Javascript: if (event.keyCode==13) buscar()'>
                  <button type="button" class="header_search_button trans_300" value="Submit" onclick="buscar()"><img src="images/search.png" alt=""></button>

                  <div class="custom_dropdown">
                    <div class="custom_dropdown_list">
                      <span class="custom_dropdown_placeholder clc"></span>
                      <i class="fas fa-chevron-down"></i>
                      <ul class="custom_list clc">

                      </ul>
                    </div>
                  </div>

                </form>
              </div>
            </div>
          </div>
        </div>

        <!-- Wishlist -->
        <div class="col-lg-4 col-9 order-lg-3 order-2 text-lg-left text-right">
          <div class="wishlist_cart d-flex flex-row align-items-center justify-content-end">
            <div class="wishlist d-flex flex-row align-items-center justify-content-end">
              <div class="wishlist_icon"><img src="images/heart.png" alt=""></div>
              <div class="wishlist_content">
                <div class="wishlist_text"><a href="wish.php">Deseos</a></div>
                <div class="wishlist_count"><?php echo $contar_wish; ?></div>
              </div>
            </div>

            <!-- Cart -->
            <div class="cart">
              <div class="cart_container d-flex flex-row align-items-center justify-content-end">
                <div class="cart_icon">
                  <img src="images/cart.png" alt="">
                  <div class="cart_count"><span><?php echo $contar; ?></span></div>
                </div>
                <div class="cart_content">
                  <div class="cart_text"><a href="cart.php">Carrito</a></div>
                  <div class="cart_price"><?php echo moneda($sumar); ?></div>
                </div>
              </div>
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
                <div class="cat_menu_text">categories</div>
              </div>

              <ul class="cat_menu">
                <?php
                  foreach($cat as $key){
                    echo "<li class='hassubs'>
                      <a href='shop.php?cat=".$key->idcategoria."&ncat=".$key->descripcion."'>".$key->descripcion."<i class='fas fa-chevron-right'></i></a>
                      <ul>";
                      foreach($db->cat_ct($key->idcategoria) as $key2){
                        echo "<li class='hassubs'>
                            <a href='shop.php?cat1=".$key2->id."&ncat=".$key2->categoria."'>".$key2->categoria."<i class='fas fa-chevron-right'></i></a>
                            <ul>";
                            foreach($db->sub_cat($key2->id) as $key3){
                              echo " <li><a href='shop.php?sub=".$key3->id."&ncat=".$key3->subcategoria."'>".$key3->subcategoria."<i class='fas fa-chevron-right'></i></a></li>";
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
                <li><a href="index.php">Inicio<i class="fas fa-chevron-down"></i></a></li>
                <li><a href="info.php">Nosotros<i class="fas fa-chevron-down"></i></a></li>
                <li><a href="vendido.php">Lo más vendido<i class="fas fa-chevron-down"></i></a></li>
                <li><a href="contact.php">Contacto<i class="fas fa-chevron-down"></i></a></li>
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
                <input type="search" required="required" class="page_menu_search_input" placeholder="Search for products..." onkeyup='Javascript: if (event.keyCode==13) buscar()'>
              </form>
            </div>
            <ul class="page_menu_nav">
              <li class="page_menu_item">
                <a href="index.php">Inicio<i class="fa fa-angle-down"></i></a>
              </li>

              <li class="page_menu_item">
                <a href="index.php">Nosotros<i class="fa fa-angle-down"></i></a>
              </li>
              <li class="page_menu_item">
                <a href="index.php">Lo más vendid<i class="fa fa-angle-down"></i></a>
              </li>
              <li class="page_menu_item">
                <a href="index.php">Contacto<i class="fa fa-angle-down"></i></a>
              </li>
            </ul>

            <div class="menu_contact">
              <div class="menu_contact_item"><div class="menu_contact_icon"><img src="images/phone_white.png" alt=""></div>+38 068 005 3570</div>
              <div class="menu_contact_item"><div class="menu_contact_icon"><img src="images/mail_white.png" alt=""></div><a href="mailto:fastsales@gmail.com">fastsales@gmail.com</a></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
