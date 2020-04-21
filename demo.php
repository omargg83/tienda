<?php
		require_once("control_db.php");
		$db = new Tienda();

    $resp = crearNuevoToken();
    $tok=$resp->token;
    echo "<br>Token:"$tok;


    $resp =servicioApi('GET','pedido/listar',NULL,$tok);

    echo "<pre>";
      echo var_dump($resp);
    echo "</pre>";

?>
