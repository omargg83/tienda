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
		echo "<br>".$payment->date_approved;
		echo "<br>".$payment->payer->email;

		$arreglo =array();
		$arreglo+= array('pago'=>"Mercado Pago");
		$arreglo+= array('idpago'=>$payment->id);
		$arreglo+= array('pagador'=>$payment->payer->email);
		$arreglo+= array('estado_pago'=>$payment->status);
		echo "udate:".$db->update('pedidos',array('id'=>$idx), $arreglo);
	}







  ?>
