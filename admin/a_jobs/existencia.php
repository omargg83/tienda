<?php
  require_once("../control_db.php");
  class Existencia extends Tienda{
  	public function __construct(){
  		parent::__construct();
  	}
  }
  $db = new Existencia();
/*  $resp = crearNuevoToken();
  $tok=$resp->token;
*/
/*
  $fecha_ant=mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-1000;

  $fecha=mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));
*/
  $fmodif=date("Y-m-d H:i:s");
  echo $fmodif;
  echo "<br>";


  $date = new DateTime();
  $date->modify('-10 minute');
  $compara=$date->format('Y-m-d H:i:s');


  $sql="select * from productos where timevar<$compara limit 100";
  echo $sql;
  //$sql="select * from productos";
  $stmt= $db->dbh->query($sql);
  foreach($stmt as $key){
    $clave=$key['clave'];
/*
    $servicio = "existencia/$clave/TOTAL";
    $metodo="GET";
    $resp =servicioApi($metodo,$servicio,NULL,$tok);
    $existencia=$resp->existencia_total;
*/
    echo "<br>Clave:".$clave;

  /*  echo "<br>Existencia:".$existencia;

    $sql="update productos set existencia='$existencia', timevar='$fecha', horaexist='$fmodif' where idProducto='".$key['idProducto']."'";
    $stmt2= $db->dbh->query($sql);
    */
  }
  echo "<br>".$fecha;

?>
