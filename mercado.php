<?php
	class Tienda{
		public function __construct(){
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
		public function ajustes_editar(){
			try{
				self::set_names();
				$sql="select * from ajustes where id=1";
				$sth = $this->dbh->prepare($sql);
				$sth->execute();
				return $sth->fetch(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
	}
	$db = new Tienda();

	$input = @file_get_contents("php://input");
	$texto=$input;
	$event_json = json_decode($input);
	$id = $event_json->data->id;

	$sql="select * from pedidos where idpago='$id'";
	$sth = $db->dbh->prepare($sql);
	$sth->execute();
	$rex=$sth->fetch(PDO::FETCH_OBJ);

	$resp=var_dump($rex);

	$texto="$id $sql ($resp) $texto  Pedido: (".$rex->id.")";
	$sql="insert into new_table (log) values (:log)";
	$sth = $db->dbh->prepare($sql);
	$sth->bindValue(':log',$texto);
	echo $sth->execute();


	if (isset($id, 	$topic)) {
			http_response_code(200);
			return;
	}


/*
	if(isset($_GET["id"]) and strlen($_GET["id"])>0){

		$id=$_GET["id"];
		$topic=$_GET["topic"];


		$mercado=$db->ajustes_editar();
		$mercado_token=$mercado->mercado_token;

	//////////////////////////////////////////////////////////////////////////////
		require __DIR__ .  '/vendor/autoload.php';
		MercadoPago\SDK::setAccessToken($mercado_token);

		$merchant_order = null;
		echo $id;
		switch(	$topic) {
				case "payment":
						$payment = MercadoPago\Payment::find_by_id($id);
						$merchant_order = MercadoPago\MerchantOrder::find_by_id($payment->order->id);
						break;
				case "merchant_order":
						$merchant_order = MercadoPago\MerchantOrder::find_by_id($id);
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
								$resp="Totally paid. Print the label and release your item. $id";
								print_r("Totally paid. Print the label and release your item. $id");
						}
				} else { // The merchant_order don't has any shipments
						$resp="Totally paid. Release your item. $id";
						print_r("Totally paid. Release your item. $id");
				}
		} else {
				$resp="Not paid yet. Do not release your item. $id";
				print_r("Not paid yet. Do not release your item. $id");
		}
	///////////////////////////////////////////////////////////////////////////////



		$input = @file_get_contents("php://input");
		$texto=$input;
		$event_json = json_decode($input);
		$id = $event_json->data->id;

		$sql="select * from pedidos where idpago='$id";
		$sth = $db->dbh->prepare($sql);
		if($sth->execute()){
			$pedidos=$sth->fetch(PDO::FETCH_OBJ);

			$texto="$id $texto  Pedido:".$pedidos->id;
			$sql="insert into new_table (log) values (:log)";
			$sth = $db->dbh->prepare($sql);
			$sth->bindValue(':log',$texto);
			echo $sth->execute();
		}

		if (isset($id, 	$topic)) {
		    http_response_code(200);
		    return;
		}
	}
*/

?>
