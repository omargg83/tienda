<?php
require_once("../control_db.php");
if (isset($_REQUEST['function'])){$function=$_REQUEST['function'];}	else{ $function="";}

class Clientes extends Tienda{

	public function __construct(){
		parent::__construct();
		$this->doc1="a_pagina/";
	}

	public function reporte1(){
		try{
			parent::set_names();
			$cwxtra="";
			$desde=$_REQUEST['desde'];
			$hasta=$_REQUEST['hasta'];

			$desde = date("Y-m-d", strtotime($desde))." 00:00:00";
			$hasta = date("Y-m-d", strtotime($hasta))." 23:59:59";

			$sql="select * from pedidos where (fecha BETWEEN :fecha1 AND :fecha2) order by fecha desc";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(":fecha1",$desde);
			$sth->bindValue(":fecha2",$hasta);
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
	public function reporte2(){
		try{
			parent::set_names();
			$cwxtra="";
			$desde=$_REQUEST['desde'];
			$hasta=$_REQUEST['hasta'];

			$desde = date("Y-m-d", strtotime($desde))." 00:00:00";
			$hasta = date("Y-m-d", strtotime($hasta))." 23:59:59";

			$sql="select pedidos_prod.*, pedidos.id, pedidos.fecha, pedidos.monto, pedidos.estatus, pedidos.factura, pedidos.pago, pedidos.estado_pago  from pedidos_prod left outer join pedidos on pedidos.id=pedidos_prod.idpedido where (fecha BETWEEN :fecha1 AND :fecha2) order by pedidos.fecha desc";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(":fecha1",$desde);
			$sth->bindValue(":fecha2",$hasta);
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}

}
$db = new Clientes();
if(strlen($function)>0){
	echo $db->$function();
}
?>
