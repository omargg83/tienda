<?php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	date_default_timezone_set("America/Mexico_City");

	class Tienda{
		public function __construct(){
			date_default_timezone_set("America/Mexico_City");
			$_SESSION['mysqluser']="ticshopc_admin";
			$_SESSION['mysqlpass']="admin123$%";
			$_SESSION['servidor'] ="tic-shop.com.mx";
			$_SESSION['bdd']="ticshopc_tienda";
			$this->dbh = new PDO("mysql:host=".$_SESSION['servidor'].";dbname=".$_SESSION['bdd']."", $_SESSION['mysqluser'], $_SESSION['mysqlpass']);
			self::set_names();
		}
		public function set_names(){
			return $this->dbh->query("SET NAMES 'utf8'");
		}
		public function ajustes_editar(){
			try{
				self::set_names();
				$sql="select * from ajustes where id=1";
				$sth = $this->dbh->prepare($sql);
				$sth->execute();
				return $sth->fetch(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
	}
	$db = new Tienda();
	$mercado=$db->ajustes_editar();
	$mercado_token=$mercado->mercado_token;

	$input = @file_get_contents("php://input");
	$texto=$input;
	$event_json = json_decode($input);
	$id = $event_json->data->id;


	require __DIR__ .  '/vendor/autoload.php';
	MercadoPago\SDK::setAccessToken($mercado_token);
  $payment = MercadoPago\Payment::find_by_id($id);

	$externa=$payment->external_reference;
	$monto=$payment->transaction_amount;
	$estado=$payment->status;

  echo "<br>external:".$externa;
  echo "<br>Monto:".$monto;
  echo "<br>estado:".$estado;

	$sql="select * from pedidos where id='$externa'";
	echo "<br>$sql";
	$sth = $db->dbh->prepare($sql);
	$sth->execute();
	$pedido=$sth->fetch(PDO::FETCH_OBJ);
	echo "<br>Monto:".$monto;
	echo "<br>Total:".$pedido->total;
	if($monto>=$pedido->total){
		echo "<br>Bien pagado";

		$sql="update pedidos set confirmacion='$estado', idpago='$id', pagador='ipn', pago='Mercado Pago', estatus='Mercado Pago' where id='".$pedido->id."'";
		$sth = $db->dbh->prepare($sql);
		$sth->execute();

		




	}


	$texto="$id $texto  Pedido:".$rex->id;
	$sql="insert into new_table (log) values (:log)";
	$sth = $db->dbh->prepare($sql);
	$sth->bindValue(':log',$texto);
	echo $sth->execute();

	if (isset($id)) {
			http_response_code(200);
			return;
	}



?>
