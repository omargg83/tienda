<?php
	require_once("control_db.php");
	$db = new Tienda();

  $idpago=$_REQUEST['id'];
  $mail=$_REQUEST['mail'];
  $estatus=$_REQUEST['estatus'];
  $idx=$_REQUEST['idx'];

  echo "<br>idpago:".$idpago;
  echo "<br>mail:".$mail;
  echo "<br>estatus:".$estatus;
  echo "<br>pedido:".$idx;

  if($estatus=="COMPLETED"){
    $arreglo =array();
    $arreglo+= array('estatus'=>"procesando");
    $arreglo+= array('pago'=>"PayPal");
    $arreglo+= array('idpago'=>$idpago);
    $arreglo+= array('pagador'=>$mail);
    $arreglo+= array('estado_pago'=>$estatus);
    $x=$db->update('pedidos',array('id'=>$idx), $arreglo);
    echo $x;
    $ped=json_decode($x);
    $id=$ped->id;
    if($ped->error==0){

    }
  }

 ?>
