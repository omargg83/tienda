<?php
  $token = $_REQUEST["token"];
  $payment_method_id = $_REQUEST["payment_method_id"];
  $installments = $_REQUEST["installments"];
  $issuer_id = $_REQUEST["issuer_id"];


  require_once 'vendor/autoload.php';
  MercadoPago\SDK::setAccessToken("TEST-3678107396790432-040504-850b450567bc9af742a685edda25faad-538795708");

  $payment = new MercadoPago\Payment();
  $payment->transaction_amount = 133;
  $payment->token = $token;
  $payment->description = "Durable Wooden Pants";
  $payment->installments = $installments;
  $payment->payment_method_id = $payment_method_id;
  $payment->issuer_id = $issuer_id;
  $payment->payer = array(
  "email" => "omar@hotmail.com"
  );

  $payment->save();
  echo $payment->status;

  ?>
