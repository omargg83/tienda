<?php
require_once("../control_db.php");

class Almacen extends Tienda{

	public function __construct(){
		parent::__construct();
	}
	public function almacen_lista(){
		try{
			parent::set_names();
			if (isset($_REQUEST['buscar']) and strlen(trim($_REQUEST['buscar']))>0){
				$texto=trim(htmlspecialchars($_REQUEST['buscar']));
				$sql="SELECT * from almacen where numero like '%$texto%' or sucursal like '%$texto%' or homoclave like '%$texto%' or estado like '%$texto%' or ciudad like '%$texto%' ";
			}
			else{
				$sql="SELECT * from almacen";
			}
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll();
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function almacen_editar($id){
		try{
			parent::set_names();
			$sql="select * from almacen where idalmacen=:id";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(':id', "$id");
			$sth->execute();
			return $sth->fetch(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function guardar_almacen(){
		try{
			parent::set_names();
			$id=$_REQUEST['id'];
			$arreglo=array();
			if (isset($_REQUEST['numero'])){
				$arreglo+= array('numero'=>$_REQUEST['numero']);
			}
			if (isset($_REQUEST['sucursal'])){
				$arreglo+= array('sucursal'=>$_REQUEST['sucursal']);
			}
			if (isset($_REQUEST['homoclave'])){
				$arreglo+= array('homoclave'=>$_REQUEST['homoclave']);
			}
			if (isset($_REQUEST['calle'])){
				$arreglo+= array('calle'=>$_REQUEST['calle']);
			}
			if (isset($_REQUEST['num'])){
				$arreglo+= array('num'=>$_REQUEST['num']);
			}
			if (isset($_REQUEST['referencia'])){
				$arreglo+= array('referencia'=>$_REQUEST['referencia']);
			}
			if (isset($_REQUEST['colonia'])){
				$arreglo+= array('colonia'=>$_REQUEST['colonia']);
			}
			if (isset($_REQUEST['cp'])){
				$arreglo+= array('cp'=>$_REQUEST['cp']);
			}
			if (isset($_REQUEST['ciudad'])){
				$arreglo+= array('ciudad'=>$_REQUEST['ciudad']);
			}
			if (isset($_REQUEST['estado'])){
				$arreglo+= array('estado'=>$_REQUEST['estado']);
			}
			if (isset($_REQUEST['telefono'])){
				$arreglo+= array('telefono'=>$_REQUEST['telefono']);
			}
			if (isset($_REQUEST['correog'])){
				$arreglo+= array('correog'=>$_REQUEST['correog']);
			}
			if (isset($_REQUEST['jefe'])){
				$arreglo+= array('jefe'=>$_REQUEST['jefe']);
			}
			if (isset($_REQUEST['correo'])){
				$arreglo+= array('correo'=>$_REQUEST['correo']);
			}
			$x="";
			if($id==0){
				$x=$this->insert('almacen', $arreglo);
			}
			else{
				$x=$this->update('almacen',array('idalmacen'=>$id), $arreglo);
			}
			return $x;
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function borrar_oficio(){
		if (isset($_POST['id'])){$id=$_POST['id'];}

		$arreglo+=array();
		$arreglo+=array('idcomision'=>NULL);
		$this->update('zsalidacal',array('idcomision'=>$id), $arreglo);
		return $this->borrar('zofcomision',"idcomision",$id);
	}

}
$db = new Almacen();
if(strlen($function)>0){
	echo $db->$function();
}
?>
