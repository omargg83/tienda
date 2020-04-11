<?php
  session_start();
  error_reporting(E_ALL);
  ini_set('display_errors', '1');

  class Tienda{
    public function __construct(){
      $this->Salud = array();
      date_default_timezone_set("America/Mexico_City");
      $_SESSION['mysqluser']="ticshopc_admin";
      $_SESSION['mysqlpass']="admin123$%";
      $_SESSION['servidor'] ="localhost";
      $_SESSION['bdd']="ticshopc_tienda";
      $this->dbh = new PDO("mysql:host=".$_SESSION['servidor'].";dbname=".$_SESSION['bdd']."", $_SESSION['mysqluser'], $_SESSION['mysqlpass']);
      self::set_names();

    }
    public function set_names(){
      return $this->dbh->query("SET NAMES 'utf8'");
    }
  }
  $db = new Tienda();

  function servicioApi($metodo, $servicio, $json = null, $token = null) {
      $ch = curl_init('http://187.210.141.12:3001/' . $servicio);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $metodo);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($json), 'x-auth: ' . $token));
      curl_setopt($ch, CURLOPT_TIMEOUT, 20);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
      $result = curl_exec($ch);

      //echo curl_error($ch);
      curl_close($ch); // close cURL handler
      return json_decode($result);
  }
  function crearNuevoToken() {
      //Credenciales del cliente para poder consumir el servicio de TOKEN
      $cliente = 'PAC0736';
      $email = 'juanluisvitevivanco@hotmail.com';
      $rfc = 'VIVJ820926GR9';

      $servicio = 'cliente/token'; //Ruta del servicio para la creacion de un nuevo token
      $json = json_encode(array('email' => $email, 'cliente' => $cliente, 'rfc' => $rfc));

      //AQUI SE CONSUME UN SERVICIO POR == METODO POST == y SE RETORNA COMO RESPUESTA
      return servicioApi('POST', $servicio, $json, null);
  }

  $resp = crearNuevoToken();
  $tok=$resp->token;

  echo $tok;
  //$compara=mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-900;
  //$sql="select * from productos where timeexis<$compara order by timeexis asc limit 75";

  $fecha=mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));
  $sql="select * from productos where interno=0 order by timeexis asc limit 50";
  $fmodif = date("Y-m-d H:i:s");

  $stmt= $db->dbh->query($sql);
  foreach($stmt as $key){
    $clave=$key['clave'];

    $servicio = "existencia/$clave/TOTAL";
    $metodo="GET";

    $resp =servicioApi($metodo,$servicio,NULL,$tok);
    $existencia=$resp->existencia_total;

    echo "<br>clave:".$clave;

//    echo "<br>Clave:".$clave;
//    echo "<br>TIEMPO:".$key['timevar'];
    //$dif=$compara-$key['timevar'];

//    echo "<br>Diferencia:".$dif;
    echo "<br>Existencia:".$existencia;

    $sql="update productos set existencia='$existencia', timeexis='$fecha', horaexist='$fmodif' where idProducto='".$key['idProducto']."'";
    echo "<br>".$sql;
    $stmt2= $db->dbh->query($sql);

  }


?>
