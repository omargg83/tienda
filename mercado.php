<?php
	// Recibir el cuerpo de la petición.
	$input = @file_get_contents("php://input");
	// Parsear el contenido como JSON.
	$texto=$input;

	$event_json = json_decode($input);

	// for extra security, retrieve from the Stripe API
	$id1 = $event_json->data->id;



  class Tienda{
    public $nivel_personal;
    public $nivel_captura;
    public $derecho=array();
    public $lema;
    public $personas;
    public $arreglo;
    public $limite=300;

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
	$db = new Tienda();


	$topic=$_REQUEST['topic'];
	$id=$_REQUEST['id'];
	echo "<br>topic:".$topic;
	echo "<br>IP:".$id;

	$texto="$id1 -  $texto pago:".$id."  Topic: $topic";

	$sql="insert into new_table (log) values (:log)";
	$sth = $db->dbh->prepare($sql);
	$sth->bindValue(':log',$texto);
	echo $sth->execute();

	if (isset($_GET["id"], $_GET["topic"])) {
	    http_response_code(200);
	    return;
	}


?>