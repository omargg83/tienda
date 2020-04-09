<?php
	require_once("control_db.php");
	$db = new Tienda();

<<<<<<< HEAD
	$mercado=$db->ajustes_editar();
	$merca=$mercado->mercado_token;
=======
>>>>>>> 4827f19782e0b2a5ee8fb00a98beaf01ce832ca0

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

<<<<<<< HEAD
  if($payment->status=="approved"){


	}
=======
  echo $payment->status;
>>>>>>> 4827f19782e0b2a5ee8fb00a98beaf01ce832ca0

  ?>
