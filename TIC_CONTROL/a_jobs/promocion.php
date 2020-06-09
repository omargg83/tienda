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

  $metodo="GET";
  $servicio = "existencia/promociones";

  $resp =servicioApi($metodo,$servicio,NULL,$tok);
  $rx=var_dump($resp);

  $sql="insert into new_table2 (id,respuesta) values (:id, :log)";
  $sth = $db->dbh->prepare($sql);
  $sth->bindValue(':id',"1");
  $sth->bindValue(':log',$rx);
  echo $sth->execute();


?>
