<?php
	require_once("control_db.php");
	$db = new Tienda();

  $contar=0;
  $sumar=0;
  $contar_wish=0;
  if(isset($_SESSION['idcliente'])){
    $res=$db->carrito_sum();
    $contar=$res->contar;
    $sumar=$res->sumar;
    $res2=$db->wish_sum();
    $contar_wish=$res2->contar;
  }
  $cat=$db->categorias();
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v3.8.6">
    <title>Product example · Bootstrap</title>
		<link rel="stylesheet" type="text/css" href="style.css">
  </head>
  <body>

  <header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark" style='background-color:black !important;'>
      <div class="container">
        <a class="navbar-brand" href="#"><i class="fas fa-mobile-alt"></i>777 324 77324</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample07" aria-controls="navbarsExample07" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExample07">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
              <a class="nav-link" href="mailto:fastsales@gmail.com">correo@gmail.com</a>
            </li>

          </ul>
          <form class="form-inline my-2 my-md-0">
            <a class="navbar-brand" href="#"><i class="fas fa-mobile-alt"></i>777 324 77324</a>
          </form>
        </div>
      </div>
    </nav>
  </header>

<div class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-light" style='margin:0 !important'>
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

              <div class="input-group mb-3">
                <input type="text" class="form-control" id='bucar_text' name='bucar_text' placeholder="Buscar producto" aria-label="Recipient's username" aria-describedby="basic-addon2">
                <div class="input-group-append">
                  <button class="btn btn-outline-secondary" type="button" onclick="buscar_prod()"><i class="fas fa-search"></i></button>
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
            <div class="wishlist_icon"><img src="images/heart.png" alt=""></div>
            <div class="wishlist_content">
              <div class="wishlist_text"><a href="wish.php">Deseos</a></div>
              <div class="wishlist_count" id='wish_count'><?php echo $contar_wish; ?></div>
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


<!--- main nav --->

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
                            <a href='shop.php?cat1=".$key2->id."&ncat=".$key2->categoria."'>".$key2->heredado."<i class='fas fa-chevron-right'></i></a>
                            <ul>";
                            foreach($db->sub_cat($key2->id) as $key3){
                              echo " <li><a href='shop.php?sub=".$key3->id."&ncat=".$key3->subcategoria."'>".$key3->heredado."<i class='fas fa-chevron-right'></i></a></li>";
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
                <li><a href="vendido.php">Tienda<i class="fas fa-chevron-down"></i></a></li>
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


<!--- main end --->


<div class="d-md-flex flex-md-equal w-100 my-md-3 pl-md-3">
  <div class="bg-dark mr-md-3 pt-3 px-3 pt-md-5 px-md-5 text-center text-white overflow-hidden">
    <div class="my-3 py-3">
      <h2 class="display-5">Another headline</h2>
      <p class="lead">And an even wittier subheading.</p>
    </div>
    <div class="bg-light shadow-sm mx-auto" style="width: 80%; height: 300px; border-radius: 21px 21px 0 0;"></div>
  </div>
  <div class="bg-light mr-md-3 pt-3 px-3 pt-md-5 px-md-5 text-center overflow-hidden">
    <div class="my-3 p-3">
      <h2 class="display-5">Another headline</h2>
      <p class="lead">And an even wittier subheading.</p>
    </div>
    <div class="bg-dark shadow-sm mx-auto" style="width: 80%; height: 300px; border-radius: 21px 21px 0 0;"></div>
  </div>
</div>

<div class="d-md-flex flex-md-equal w-100 my-md-3 pl-md-3">
  <div class="bg-light mr-md-3 pt-3 px-3 pt-md-5 px-md-5 text-center overflow-hidden">
    <div class="my-3 p-3">
      <h2 class="display-5">Another headline</h2>
      <p class="lead">And an even wittier subheading.</p>
    </div>
    <div class="bg-dark shadow-sm mx-auto" style="width: 80%; height: 300px; border-radius: 21px 21px 0 0;"></div>
  </div>
  <div class="bg-primary mr-md-3 pt-3 px-3 pt-md-5 px-md-5 text-center text-white overflow-hidden">
    <div class="my-3 py-3">
      <h2 class="display-5">Another headline</h2>
      <p class="lead">And an even wittier subheading.</p>
    </div>
    <div class="bg-light shadow-sm mx-auto" style="width: 80%; height: 300px; border-radius: 21px 21px 0 0;"></div>
  </div>
</div>

<div class="d-md-flex flex-md-equal w-100 my-md-3 pl-md-3">
  <div class="bg-light mr-md-3 pt-3 px-3 pt-md-5 px-md-5 text-center overflow-hidden">
    <div class="my-3 p-3">
      <h2 class="display-5">Another headline</h2>
      <p class="lead">And an even wittier subheading.</p>
    </div>
    <div class="bg-white shadow-sm mx-auto" style="width: 80%; height: 300px; border-radius: 21px 21px 0 0;"></div>
  </div>
  <div class="bg-light mr-md-3 pt-3 px-3 pt-md-5 px-md-5 text-center overflow-hidden">
    <div class="my-3 py-3">
      <h2 class="display-5">Another headline</h2>
      <p class="lead">And an even wittier subheading.</p>
    </div>
    <div class="bg-white shadow-sm mx-auto" style="width: 80%; height: 300px; border-radius: 21px 21px 0 0;"></div>
  </div>
</div>

<div class="d-md-flex flex-md-equal w-100 my-md-3 pl-md-3">
  <div class="bg-light mr-md-3 pt-3 px-3 pt-md-5 px-md-5 text-center overflow-hidden">
    <div class="my-3 p-3">
      <h2 class="display-5">Another headline</h2>
      <p class="lead">And an even wittier subheading.</p>
    </div>
    <div class="bg-white shadow-sm mx-auto" style="width: 80%; height: 300px; border-radius: 21px 21px 0 0;"></div>
  </div>
  <div class="bg-light mr-md-3 pt-3 px-3 pt-md-5 px-md-5 text-center overflow-hidden">
    <div class="my-3 py-3">
      <h2 class="display-5">Another headline</h2>
      <p class="lead">And an even wittier subheading.</p>
    </div>
    <div class="bg-white shadow-sm mx-auto" style="width: 80%; height: 300px; border-radius: 21px 21px 0 0;"></div>
  </div>
</div>

<footer class="container py-5">
  <div class="row">
    <div class="col-12 col-md">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="d-block mb-2" role="img" viewBox="0 0 24 24" focusable="false"><title>Product</title><circle cx="12" cy="12" r="10"/><path d="M14.31 8l5.74 9.94M9.69 8h11.48M7.38 12l5.74-9.94M9.69 16L3.95 6.06M14.31 16H2.83m13.79-4l-5.74 9.94"/></svg>
      <small class="d-block mb-3 text-muted">&copy; 2017-2019</small>
    </div>
    <div class="col-6 col-md">
      <h5>Features</h5>
      <ul class="list-unstyled text-small">
        <li><a class="text-muted" href="#">Cool stuff</a></li>
        <li><a class="text-muted" href="#">Random feature</a></li>
        <li><a class="text-muted" href="#">Team feature</a></li>
        <li><a class="text-muted" href="#">Stuff for developers</a></li>
        <li><a class="text-muted" href="#">Another one</a></li>
        <li><a class="text-muted" href="#">Last time</a></li>
      </ul>
    </div>
    <div class="col-6 col-md">
      <h5>Resources</h5>
      <ul class="list-unstyled text-small">
        <li><a class="text-muted" href="#">Resource</a></li>
        <li><a class="text-muted" href="#">Resource name</a></li>
        <li><a class="text-muted" href="#">Another resource</a></li>
        <li><a class="text-muted" href="#">Final resource</a></li>
      </ul>
    </div>
    <div class="col-6 col-md">
      <h5>Resources</h5>
      <ul class="list-unstyled text-small">
        <li><a class="text-muted" href="#">Business</a></li>
        <li><a class="text-muted" href="#">Education</a></li>
        <li><a class="text-muted" href="#">Government</a></li>
        <li><a class="text-muted" href="#">Gaming</a></li>
      </ul>
    </div>
    <div class="col-6 col-md">
      <h5>About</h5>
      <ul class="list-unstyled text-small">
        <li><a class="text-muted" href="#">Team</a></li>
        <li><a class="text-muted" href="#">Locations</a></li>
        <li><a class="text-muted" href="#">Privacy</a></li>
        <li><a class="text-muted" href="#">Terms</a></li>
      </ul>
    </div>
  </div>
</footer>

<!--   Core JS Files   -->
<script src="librerias15/jquery-3.4.1.min.js" type="text/javascript"></script>

<!--   Boostrap   -->
<link rel="stylesheet" href="librerias15/boostrap/css/bootstrap.min.css">
<script src="librerias15/boostrap/js/bootstrap.js"></script>

<link rel="stylesheet" href="librerias15/fontawesome-free-5.12.1-web/css/all.css">
<script src="sagyc.js"></script>
</html>
