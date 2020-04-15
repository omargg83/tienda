<?php
//////////////////////////////////////////////////////////////////////////////
	require __DIR__ .  '/vendor/autoload.php';
	MercadoPago\SDK::setAccessToken("TEST-3678107396790432-040504-850b450567bc9af742a685edda25faad-538795708");

	$merchant_order = null;

	switch($_GET["topic"]) {
			case "payment":
					$payment = MercadoPago\Payment::find_by_id($_GET["id"]);
					// Get the payment and the corresponding merchant_order reported by the IPN.
					$merchant_order = MercadoPago\MerchantOrder::find_by_id($payment->order->id);
					break;
			case "merchant_order":
					$merchant_order = MercadoPago\MerchantOrder::find_by_id($_GET["id"]);
					break;
	}

	$paid_amount = 0;
	foreach ($merchant_order->payments as $payment) {
			if ($payment['status'] == 'approved'){
					$paid_amount += $payment['transaction_amount'];
			}
	}

	// If the payment's transaction amount is equal (or bigger) than the merchant_order's amount you can release your items
	if($paid_amount >= $merchant_order->total_amount){
			if (count($merchant_order->shipments)>0) { // The merchant_order has shipments
					if($merchant_order->shipments[0]->status == "ready_to_ship") {
							$resp="Totally paid. Print the label and release your item.";
							print_r("Totally paid. Print the label and release your item.");
					}
			} else { // The merchant_order don't has any shipments
					$resp="Totally paid. Release your item.";
					print_r("Totally paid. Release your item.");
			}
	} else {
			$resp="Not paid yet. Do not release your item.";
			print_r("Not paid yet. Do not release your item.");
	}
///////////////////////////////////////////////////////////////////////////////




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

	$texto="$id1 -  $texto pago:".$id."  Topic: $topic  $resp";

	$sql="insert into new_table (log) values (:log)";
	$sth = $db->dbh->prepare($sql);
	$sth->bindValue(':log',$texto);
	echo $sth->execute();



	if (isset($_GET["id"], $_GET["topic"])) {
	    http_response_code(200);
	    return;
	}


?>
