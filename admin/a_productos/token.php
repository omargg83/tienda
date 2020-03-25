<?php
if (!isset($_SESSION)) { session_start(); }
//@AUTOR ING. JOSE DANIEL SOLIS VELARDE
//AQUI SE DEFINE LA FUNCION GENERICA PARA CONSUMIR UN SERVICIO
//servicioApi(METODO, RUTA_SERVICIO, DATOS_JSON, TOKEN)
//METODO = GET, POST, PUT, DELETE
//RUTA_SERVICIO = Servicio que se requiere consumir
//DATOS_JSON = Objeto JSON de datos que el servico requiere si no se ocupa se envia NULL
//TOKEN = Cadena de texto alfanumerica que requiere el servico para validar la seguridad sino se ocupa se envia NULL

function servicioApi($metodo, $servicio, $json = null, $token = null) {
    $ch = curl_init('http://187.210.141.12:3001/' . $servicio);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $metodo);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($json), 'x-auth: ' . $token));
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
    $result = curl_exec($ch);

    //echo curl_error($ch);
    curl_close($ch); // close cURL handler
    return json_decode($result);
}
function crearNuevoToken() {
    //Credenciales del cliente para poder consumir el servicio de TOKEN
    $cliente = 'PAC0736';
    $email = 'juanluisvitevivanco@hotmail.com';
    $rfc = 'VIVJ820926GR9';

    $servicio = 'cliente/token'; //Ruta del servicio para la creacion de un nuevo token
    $json = json_encode(array('email' => $email, 'cliente' => $cliente, 'rfc' => $rfc));

    //AQUI SE CONSUME UN SERVICIO POR == METODO POST == y SE RETORNA COMO RESPUESTA
    return servicioApi('POST', $servicio, $json, null);
}


$resp = crearNuevoToken();
$tok=$resp->token;

$servicio = 'existencia/MTFHPI1535/TOTAL';
$metodo="GET";
$resp =servicioApi($metodo,$servicio,NULL,$tok);
echo $resp->existencia_total;


?>
