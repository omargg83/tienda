<?php
  session_start();

  error_reporting(E_ALL);
  ini_set('display_errors', '1');

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

  $sql="update productos set imagen_exist=1, img=:nombre where id=:id";
  $sth2 = $db->dbh->prepare($sql);

  foreach($stmt as $key){
    $url=$key['imagen'];
    echo "<br>".$url;

    $data = file_get_contents($url);
    $img = imagecreatefromstring($data);
    if($img){
      if(imagejpeg($img,"../a_imagen/".$key['img']) ){
        echo "<br>bien";
      }
      else{
        echo "<br>error";
      }
    }
    else{
      echo "<br>error";
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

?>
