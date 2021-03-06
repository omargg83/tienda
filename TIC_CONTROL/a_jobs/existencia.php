<?php
  session_start();
  error_reporting(E_ALL);
  ini_set('display_errors', '1');

  class Tienda{
    public function __construct(){
      $this->Salud = array();
      date_default_timezone_set("America/Mexico_City");
      $mysqluser="ticshopc_admin";
      $mysqlpass="admin123$%";
      $servidor ="tic-shop.com.mx";
      $bdd="ticshopc_tienda";

      $this->dbh = new PDO("mysql:host=$servidor;dbname=$bdd", $mysqluser, $mysqlpass);
      self::set_names();

    }
    public function set_names(){
      return $this->dbh->query("SET NAMES 'utf8'");
    }
  }
  $db = new Tienda();
  echo "inicio:".date("Y-m-d H:i:s");

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

  $fecha=mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));
  $sql="select * from productos where interno=0 and existencia=0 order by timeexis asc limit 100";
  $stmt= $db->dbh->query($sql);
  foreach($stmt as $key){
    $fmodif = date("Y-m-d H:i:s");

    $clave=$key['clave'];
    $id=$key['id'];
    $servicio = "existencia/$clave/TOTAL";
    $metodo="GET";
    $existencia=0;

    $resp =servicioApi($metodo,$servicio,NULL,$tok);
    if (is_object($resp)){
      $existencia=$resp->existencia_total;
    }
    $sql="update productos set existencia='$existencia', timeexis='$fecha', horaexist='$fmodif' where id='$id'";
    $stmt2= $db->dbh->query($sql);

    $sql="delete from producto_exist where id='$id'";
    $sth3 = $db->dbh->prepare($sql);
    $sth3->execute();

    if($existencia>0){
      $servicio = "existencia/$clave";
      $metodo="GET";

      $resp_a=array();
      $resp =servicioApi($metodo,$servicio,NULL,$tok);
      if (is_object($resp)){
        $objectToArray = (array)$resp;
        while (current($objectToArray)) {
          $name=key($objectToArray);
          $info = (array)$objectToArray[$name];
          $valor=$info['existencia'];
          if($valor>0){
            $sql="select * from almacen where numero='$name'";
            $sth4 = $db->dbh->prepare($sql);
            $sth4->execute();
            if($sth4->rowCount()){
              $almax=$sth4->fetch(PDO::FETCH_OBJ);
              $sql="insert into producto_exist (id, almacen, existencia) values (:id, :almacen, :existencia)";
              $sth2 = $db->dbh->prepare($sql);
              $sth2->bindValue(':id', $id);
              $sth2->bindValue(':almacen', $almax->homoclave);
              $sth2->bindValue(':existencia', $valor);
              $sth2->execute();
            }
          }
          next($objectToArray);
        }
      }
    }
  }
  echo "<br>fin:".$fmodif;
  echo "<br>finalizo";

?>
