<?php
  require_once("../control_db.php");
  error_reporting(E_ALL);
  ini_set('display_errors', '1');

  class Existencia extends Tienda{
  	public function __construct(){
  		parent::__construct();
  	}
  }
  $db = new Existencia();

  $resp = crearNuevoToken();
  $tok=$resp->token;

  //$compara=mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-900;
  //$sql="select * from productos where timeexis<$compara order by timeexis asc limit 75";

  echo $tok;
/*
  $fecha=mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));
  $sql="select * from productos where interno=0 order by timeexis asc limit 10";
  $fmodif = date("Y-m-d H:i:s");

  $stmt= $db->dbh->query($sql);
  foreach($stmt as $key){
    $clave=$key['clave'];

    $servicio = "existencia/$clave/TOTAL";
    $metodo="GET";
    echo "<br>".$tok;
    echo "<br>".$servicio;
    $resp =servicioApi($metodo,$servicio,NULL,$tok);
    $existencia=$resp->existencia_total;

//    echo "<br>Clave:".$clave;
//    echo "<br>TIEMPO:".$key['timevar'];
    //$dif=$compara-$key['timevar'];

//    echo "<br>Diferencia:".$dif;
//  echo "<br>Existencia:".$existencia;

    $sql="update productos set existencia='$existencia', timeexis='$fecha', horaexist='$fmodif' where idProducto='".$key['idProducto']."'";
    $stmt2= $db->dbh->query($sql);

  }

*/
?>
