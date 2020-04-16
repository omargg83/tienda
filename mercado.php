<?php
	require_once("control_db.php");
	$db = new Tienda();

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
		/*
			$event_json = json_decode($input);
			$id1 = $event_json->data->id;
		*/
		$input = @file_get_contents("php://input");
		$texto=$input;

		$sql="select * from pedidos where idpago='$id";
		$sth = $db->dbh->prepare($sql);
		if($sth->execute()){
			$pedido=$sth->fetch(PDO::FETCH_OBJ);

			$texto="$id $texto  Pedido:".$pedido->id;
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


?>
