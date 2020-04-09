<?php
	require_once("control_db.php");
	$db = new Tienda();
	$mercado=$db->ajustes_editar();
	$merca=$mercado->mercado_token;

  require_once 'vendor/autoload.php';
	MercadoPago\SDK::setAccessToken($merca);

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
							print_r("Totally paid. Print the label and release your item.");
					}
			} else { // The merchant_order don't has any shipments
					print_r("Totally paid. Release your item.");
			}
	} else {
			print_r("Not paid yet. Do not release your item.");
	}

	/*



  $token = $_REQUEST["token"];
  $payment_method_id = $_REQUEST["payment_method_id"];
  $installments = $_REQUEST["installments"];
  $issuer_id = $_REQUEST["issuer_id"];
  $idx = $_REQUEST["idx"];

  echo "<br>token:".$token;
  echo "<br>payment_method_idken:".$payment_method_id;
  echo "<br>installments:".$installments;
  echo "<br>issuer_id:".$issuer_id;
  require_once 'vendor/autoload.php';
	MercadoPago\SDK::setAccessToken($merca);

  $payment = new MercadoPago\Payment();
  $payment->transaction_amount = 133;
  $payment->token = $token;
  $payment->description = "Durable Wooden Pants";
  $payment->installments = $installments;
  $payment->payment_method_id = $payment_method_id;
  $payment->issuer_id = $issuer_id;
  $payment->payer = array(
  "email" => "omargg83@gmail.com"
  );

  $payment->save();


  if($payment->status=="approved"){


	}

  echo $payment->status;
*/

  ?>
