<!-- Begin Mailchimp Signup Form -->
<link href="//cdn-images.mailchimp.com/embedcode/horizontal-slim-10_7.css" rel="stylesheet" type="text/css">
<style type="text/css">
  #mc_embed_signup{background:#fff; clear:left; font:14px Montesrrat,sans-serif; width:100%;}
  /* Add your own Mailchimp form style overrides in your site stylesheet or in this style block.
     We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */
</style>
<div id="mc_embed_signup" style="background-image: url('img/fondon.jpg');">
<form action="https://tic-shop.us19.list-manage.com/subscribe/post?u=26b3aa6c43e721f09e78709ac&amp;id=441467c84e" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate="">
    <div id="mc_embed_signup_scroll" style="
    padding: 10% 0 10% 0;
    /* background-color: azure; */
">
  <label for="mce-EMAIL" style="
    font-size: 30px;
    font-family: montserrat;
">Entérate de nuestras promociones y noticias</label>
  <input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="Correo electrónico" required="" style="
    font-family: montserrat;
    width: 44%;
    font-size: 14px;
    height: 40px;
    min-width: 300px;
">
    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
    <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_26b3aa6c43e721f09e78709ac_441467c84e" tabindex="-1" value=""></div>
    <div class="clear"><input type="submit" value="Suscribirse" name="subscribe" id="mc-embedded-subscribe" class="button" style="
    height: 40px;
    background: #b4f22f;
    color: black;
    max-width: 300px;
"></div>
    </div>
</form>
</div>

<!--End mc_embed_signup-->


<div class="banner" style='background-color: black;color:white !important;'>
  <div class="container">
    <div class="row">

      <div class="col-lg-3 footer_col">
        <div class="footer_column footer_contact">
          <div class="logo_container">
            <div class="logo"><a href="#"><img src='img/LOGO.png' width='100px'></a></div>
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

      <div class="col-lg-2 offset-lg-2 catf">
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
      <div class="col-lg-2 offset-lg-2 polf">
        <div class="footer_column">
          <div class="footer_title">POLITICAS DE LA EMPRESA</div>
          <ul class="footer_list footer_list_2">
             
            <li><a href="terminos-condiciones.php">Terminos y condiciones</a></li>
            <li><a href="aviso.php">Avíso de privacidad</a></li>
            <li><a href="preguntas.php">Preguntas frecuentes</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="//code.tidio.co/ylbayjzufl3r0a8aku5sjorrnm7ztqco.js" async></script>
