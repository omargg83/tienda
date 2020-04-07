<?php
  require_once("db_.php");

?>
<div class='container'>
<div class="banner" style='display: block;
		position: relative;
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		box-sizing: border-box;'>


    <?php
      $baner=$db->baner1();
      $titulo=$baner->titulo;
      $texto=$baner->texto;
      $imagen=$baner->img;
     ?>

		<div class="banner_background" style='background-image:url(<?php echo $db->doc1."/".$imagen; ?>)'></div>
		<div class="container fill_height">
			<div class="row fill_height">

				<div class="col-lg-5 offset-lg-4 fill_height">
					<div class="banner_content">
						<h1 class="banner_text"><?php  echo $titulo; ?></h1>
						<div class="banner_product_name"><?php  echo $texto; ?></div>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>
