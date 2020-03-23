<?php
  $cliente = 'PAC0736';
  $email = 'juanluisvitevivanco@hotmail.com';
  $rfc = 'VIVJ820926GR9';

  $json = json_encode(array('email' => $email, 'cliente' => $cliente, 'rfc' => $rfc));

  $servicio = 'cliente/token'; //Ruta del servicio para la creacion de un nuevo token
  $metodo='POST';
  $token = null;

  $ch = curl_init('http://187.210.141.12:3001/' . $servicio);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($json), 'x-auth: ' . $token));
  curl_setopt($ch, CURLOPT_TIMEOUT, 20);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
  $result = curl_exec($ch);
  echo "resultado:".$result;
  echo "<br>";
  echo die(curl_error($ch));
  curl_close($ch); // close cURL handler

?>
