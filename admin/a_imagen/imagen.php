<?php
  require_once("../control_db.php");
  class Imagen extends Tienda{
  	public function __construct(){
  		parent::__construct();
  	}
  }

  $db = new Imagen();

  $directory=".";
  $dirint = dir($directory);
  while (($archivo = $dirint->read()) !== false){
    if ($archivo != "." && $archivo != ".." && $archivo != "" && substr($archivo,-4)==".jpg"){
      $sql="select * from productos where imagen like '%$archivo'";
      $stmt= $db->dbh->query($sql);
      if($stmt->rowCount()==0){
        echo "no existe:".$archivo;
        unlink($archivo);
      }
      else{
        echo "existe:".$archivo;
      }
      echo "<br>";
    }
  }


  $sql="select * from productos limit 50";
  $stmt= $db->dbh->query($sql);

  foreach($stmt as $key){
    $url=$key['imagen'];
    $dir = "";
    $archivoFinal = fopen($dir . basename($url), "w");

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Googlebot/2.1 (+http://www.google.com/bot.html)');
    curl_setopt($ch, CURLOPT_FILE, $archivoFinal);

    fclose($archivoFinal);
    curl_close($ch);
/*
    $url = "http://www.dominio.com/imagen.png";
    $dir = "/var/www/directorio/local/";
    $archivoFinal = fopen($dir . basename($url), "w");

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Googlebot/2.1 (+http://www.google.com/bot.html)');
    curl_setopt($ch, CURLOPT_FILE, $archivoFinal);

    fclose($archivoFinal);
    curl_close($ch);

*/
    //captureImage($key['imagen'],"file.jpg");

    echo $key['imagen'];
    echo "<br>";
  }


  function captureImage($origin, $destination) {
    $mi_curl = curl_init($origin);
    $fp_destination = fopen ($destination, "w");
    curl_setopt ($mi_curl, CURLOPT_FILE, $fp_destination);
    curl_setopt ($mi_curl, CURLOPT_HEADER, 0);
    curl_exec ($mi_curl);
    curl_close ($mi_curl);
    fclose ($fp_destination);
  }

?>
