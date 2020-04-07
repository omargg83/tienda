<div class="banner" style='background-color: black;color:white !important;'>
  <div class="container">
    <div class="row">

      <div class="col-lg-3 footer_col">
        <div class="footer_column footer_contact">
          <div class="logo_container">
            <div class="logo"><a href="#"><img src='img/logo-ticshop.png' width='100px'></a></div>
          </div>
          <div class="footer_title">¿Tiene alguna duda?</div>
          <div class="footer_phone">+469448498458654</div>
          <div class="footer_contact_text">
          
          </div>
          <div class="footer_social">
            <ul>
              <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
              <li><a href="#"><i class="fab fa-twitter"></i></a></li>
              <li><a href="#"><i class="fab fa-youtube"></i></a></li>
              <li><a href="#"><i class="fab fa-google"></i></a></li>
              <li><a href="#"><i class="fab fa-vimeo-v"></i></a></li>
            </ul>
          </div>
        </div>
      </div>

      <div class="col-lg-2 offset-lg-2">
        <div class="footer_column">
          <div class="footer_title">Categorias</div>
          <ul class="footer_list">
            <?php
              $cat=$db->categorias();
              foreach($cat as $key){
                echo "<li>
                  <a href='shop.php?cat=".$key->idcategoria."&ncat=".$key->descripcion."'>".$key->descripcion."</a>
                  <li>";
              }
            ?>
          </ul>
        </div>
      </div>
      <div class="col-lg-2 offset-lg-2">
        <div class="footer_column">
          <div class="footer_title">POLITICAS DE LA EMPRESA</div>
          <ul class="footer_list footer_list_2">

            <li><a href="#">Preguntas frecuentes</a></li>
            <li><a href="#">Politicas de dovoluciones</a></li>
            <li><a href="#">Ayuda</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
