<?php
	require_once("control_db.php");
	$db = new Tienda();

		echo var_dump($_REQUEST);

		$idpedido=$_REQUEST['idpedido'];
		$payment_id=$_REQUEST['payment_id'];
		$payment_status=$_REQUEST['payment_status'];

		echo "<br>idpedido:".$idpedido;
		echo "<br>payment_id:".$payment_id;
		echo "<br>payment_status':".$payment_status;

		$ped=$db->pedido_ver($idpedido);

	  if($payment_status=="approved"){
			$arreglo =array();
			$arreglo+= array('estatus'=>"procesando");
			$arreglo+= array('pago'=>"Mercado Pago");
			$arreglo+= array('idpago'=>$payment_id);
			$arreglo+= array('estado_pago'=>$payment_status);
			$x=$db->update('pedidos',array('id'=>$idpedido), $arreglo);
			$ped=json_decode($x);
			$id=$ped->id;
			if($ped->error==0){
				header('Location: https://www.tic-shop.com.mx/tienda/web/estado_pedido.php?idpedido=$idpedido');
			}
		}
		else{

		}


 ?>
