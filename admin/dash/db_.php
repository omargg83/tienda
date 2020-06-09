<?php
require_once("../control_db.php");
if (isset($_REQUEST['function'])){$function=$_REQUEST['function'];}	else{ $function="";}

class Reporte extends Tienda{

	public function __construct(){
		parent::__construct();
	}
	public function productos_numero(){
		try{
			parent::set_names();
			$sql="select count(id) as total from productos";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetch(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function productos_top(){
		try{
			parent::set_names();
			$sql="select * from productos where existencia>0 and activo=1 order by id desc limit 10";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function pedidos_top(){
		try{
			parent::set_names();
			$sql="select * from pedidos order by id desc limit 10";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
}
$db = new Reporte();
if(strlen($function)>0){
	echo $db->$function();
}
?>
