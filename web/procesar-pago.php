<?php
	require_once("control_db.php");
	$db = new Tienda();
	$mercado=$db->ajustes_editar();
	$merca=$mercado->mercado_token;

  $token = $_REQUEST["token"];
  $payment_method_id = $_REQUEST["payment_method_id"];
  $installments = $_REQUEST["installments"];
  $issuer_id = $_REQUEST["issuer_id"];
  $idx = $_REQUEST["idx"];

	$ped=$db->pedido_ver($idx);
	$nombre=$ped->nombre;
	$correo=$ped->correo;
	$total=$ped->total;

	echo "<br>Total:".$total;


  echo "<br>token:".$token;
  echo "<br>payment_method_idken:".$payment_method_id;
  echo "<br>installments:".$installments;
  echo "<br>issuer_id:".$issuer_id;

  require_once 'vendor/autoload.php';
	MercadoPago\SDK::setAccessToken($merca);

  $payment = new MercadoPago\Payment();
  $payment->transaction_amount = 133;
	$payment->description = "TIC-SHOP";
  $payment->token = $token;
  $payment->installments = $installments;
  $payment->payment_method_id = $payment_method_id;
  $payment->issuer_id = $issuer_id;
  $payment->payer = array(
  "email" => $correo
  );

  $payment->save();


  if($payment->status=="approved"){
		echo "<br>".$payment->id;
	}



	echo "<br>";
	echo '<pre>';
		echo var_dump($payment);
	echo '</pre>';






  ?>
