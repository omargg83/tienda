<?php
require_once("../control_db.php");
if (isset($_REQUEST['function'])){$function=$_REQUEST['function'];}	else{ $function="";}

class Proveedores extends Sagyc{
	public $nivel_personal;
	public $nivel_captura;

	public function __construct(){
		parent::__construct();

		if(isset($_SESSION['idpersona']) and $_SESSION['autoriza'] == 1) {

		}
		else{
			include "../error.php";
			die();
		}
	}
	public function proveedor(){
		try{
			self::set_names();
			$sql="SELECT * FROM proveedor";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			$res=$sth->fetchAll();
			return $res;
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
	public function proveedor_edit($idproveedor){
		try{
			parent::set_names();
			$sql="SELECT * FROM proveedor where idproveedor=:idproveedor";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(":idproveedor",$idproveedor);
			$sth->execute();
			$res=$sth->fetch();
			return $res;
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
	public function guardar_proveedor(){
		if (isset($_REQUEST['id'])){$id=$_REQUEST['id'];}
		$arreglo =array();
		if (isset($_REQUEST['nombre'])){
			$arreglo+=array('nombre'=>$_REQUEST['nombre']);
		}

		if (isset($_REQUEST['rfc'])){
			$arreglo+=array('rfc'=>$_REQUEST['rfc']);
		}
		if (isset($_REQUEST['direccion'])){
			$arreglo+=array('direccion'=>$_REQUEST['direccion']);
		}
		if (isset($_REQUEST['telefono'])){
			$arreglo+=array('telefono'=>$_REQUEST['telefono']);
		}
		if (isset($_REQUEST['razonsocial'])){
			$arreglo+=array('razonsocial'=>$_REQUEST['razonsocial']);
		}

		if (isset($_REQUEST['activo'])){
			$arreglo+=array('activo'=>$_REQUEST['activo']);
		}
		else{
			$arreglo+=array('activo'=>0);
		}
		if($id==0){
			$x=$this->insert('proveedor', $arreglo);
		}
		else{
			$x=$this->update('proveedor',array('idproveedor'=>$id), $arreglo);
		}
		return $x;
	}
}

$db = new Proveedores();
if(strlen($function)>0){
	echo $db->$function();
}
