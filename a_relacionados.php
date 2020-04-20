<div class="container">
  <div class="row">
    <div class="col">
      <div class="viewed_title_container">
        <h3 class="viewed_title">Productos relacionados</h3>
        <div class="viewed_nav_container">
          <div class="viewed_nav viewed_prev"><i class="fas fa-chevron-left"></i></div>
          <div class="viewed_nav viewed_next"><i class="fas fa-chevron-right"></i></div>
        </div>
      </div>

      <div class="viewed_slider_container">
        <!-- Recently Viewed Slider -->
        <div class="owl-carousel owl-theme viewed_slider">
          <?php
            foreach($rel as $key){
              echo "<div class='owl-item'>";
                echo "<a href='product.php?id=".$key->id."'>";
                  echo "<div class='viewed_item discount d-flex flex-column align-items-center justify-content-center text-center'>";
                    echo "<div class='viewed_image'><img src='../".$db->doc.$key->img."' alt=''></div>";
                    echo "<div class='viewed_content text-center'>";
                      echo "<div class='viewed_price'>";
                        if($key->precio_tipo==0){
                          echo moneda($key->preciof);
                        }
                        if($key->precio_tipo==1){
                          $total=$key->preciof+(($key->preciof*$db->cgeneral)/100);
                          echo moneda($total);
                        }
                        if($key->precio_tipo==2){
                          echo moneda($key->precio_tic);
                        }
                        if($key->precio_tipo==3){
                          $total=$key->precio_tic+(($key->precio_tic*$db->cgeneral)/100);
                          echo moneda($total);
                        }
                      echo "</div>";
                      echo "<div class='viewed_name'>".$prod->nombre."</div>";
                    echo "</div>";
                  echo "</div>";
                echo "</a>";
              echo "</div>";
            }
           ?>

        </div>

      </div>
    </div>
  </div>
</div>
