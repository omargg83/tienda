<?php
  session_start();

  error_reporting(E_ALL);
  ini_set('display_errors', '1');
  echo "entra";
  class Imagen{
    public function __construct(){
      $this->Salud = array();
      date_default_timezone_set("America/Mexico_City");
      $_SESSION['mysqluser']="ticshopc_admin";
      $_SESSION['mysqlpass']="admin123$%";
      $_SESSION['servidor'] ="tic-shop.com.mx";
      $_SESSION['bdd']="ticshopc_tienda";
      $this->dbh = new PDO("mysql:host=".$_SESSION['servidor'].";dbname=".$_SESSION['bdd']."", $_SESSION['mysqluser'], $_SESSION['mysqlpass']);
      self::set_names();
    }
    public function set_names(){
			return $this->dbh->query("SET NAMES 'utf8'");
		}
  }
  $db = new Imagen();

  $sql="select * from productos where activo=1 and imagen_exist=0 and interno=0 limit 10";
  $stmt= $db->dbh->query($sql);

  /*

    $url=$key['imagen'];
     echo "<br>".$url;

     if (!file_exists("../a_imagen/".$nombre)) {
       $imagen = file_get_contents($url);
       file_put_contents("../a_imagen/".$nombre, $imagen);
     }
  */


  foreach($stmt as $key){
    echo "<hr>";
    $nombre=trim(basename(trim($key['imagen'])));

    $url=$key['imagen'];
    echo "<br>".$url;
    echo "<br>../a_imagen/".$nombre;
    $data = file_get_contents($url);
    $img = imagecreatefromstring($data);

    if($img){
      if(imagejpeg($img,"../a_imagen/".$nombre)){
        echo "<br>bien";
        $sql="update productos set imagen_exist=1, img=:nombre where id=:id";
        $sth2 = $db->dbh->prepare($sql);
        $sth2->bindValue(':id',$key['id']);
        $sth2->bindValue(':nombre', $nombre);
        $sth2->execute();
      }
      else{
        //$imagen = file_get_contents($url);
        //echo "contenido:".file_put_contents("../a_imagen/".$nombre, $imagen);

        $sql="update productos set imagen_exist=2, img=:nombre where id=:id";
        $sth2 = $db->dbh->prepare($sql);
        $sth2->bindValue(':id',$key['id']);
        $sth2->bindValue(':nombre', $nombre);
        $sth2->execute();
        echo "<br>error";

      }
    }
    else{
      echo "<br>mal";
      $sql="update productos set imagen_exist=2, img=:nombre where id=:id";
      $sth2 = $db->dbh->prepare($sql);
      $sth2->bindValue(':id',$key['id']);
      $sth2->bindValue(':nombre', $nombre);
      $sth2->execute();

    }

      /*
      $nombre=trim(basename(trim($key['imagen'])));

      $imagen = file_get_contents($url);

      $data = file_get_contents($almax->imagen);
      $img = imagecreatefromstring($data);

      $resp=file_put_contents("../a_imagen/".$nombre, $imagen);


      echo "error de imagen 1";
      $img = imagecreatefromstring($imagen);
      if(imagejpeg($img,"../a_imagen/".$nombre)){
        $sth2->bindValue(':nombre', $nombre);
        $sth2->bindValue(':id',$key['id']);
        $sth2->execute();
      }
      else{
        echo "error de imagen 2";
      }
      */
  }
  echo "<br>Fin";
?>
