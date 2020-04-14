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
/*
  $sql="select * from productos where img is null";
  $stmt= $db->dbh->query($sql);

  $sql="update productos set img=:nombre where id=:id";
  $sth2 = $db->dbh->prepare($sql);
  foreach($stmt as $key){
    $sth2->bindValue(':id',$key['id']);
    $sth2->bindValue(':nombre', trim(basename(trim($key['imagen']))));
    $sth2->execute();
  }

  $directory="../a_imagen/";
  $dirint = dir($directory);
  while (($archivo = $dirint->read()) !== false){
    if ($archivo != "." && $archivo != ".." && $archivo != "" && substr($archivo,-4)==".jpg"){
      $sql="select * from productos where img='$archivo'";
      $stmt= $db->dbh->query($sql);
      if($stmt->rowCount()==0){
        unlink("../a_imagen/".$archivo);
      }
    }
  }
*/
//$sql="select * from productos where activo=1 and interno=0 limit 100";
  $sql="select * from productos where activo=1 and imagen_exist=0 and interno=0 limit 75";
  $stmt= $db->dbh->query($sql);

  $sql="update productos set imagen_exist=1, img=:nombre where id=:id";
  $sth2 = $db->dbh->prepare($sql);

  foreach($stmt as $key){
    $url=$key['imagen'];
    echo "<br>".$url;
    $nombre=trim(basename(trim($key['imagen'])));
    if (!file_exists("../a_imagen/".$nombre)) {
      $imagen = file_get_contents($url);
      file_put_contents("../a_imagen/".$nombre, $imagen);
    }
    $sth2->bindValue(':nombre', $nombre);
    $sth2->bindValue(':id',$key['id']);
    $sth2->execute();
  }

?>
