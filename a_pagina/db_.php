<?php
require_once("../control_db.php");
if (isset($_REQUEST['function'])){$function=$_REQUEST['function'];}	else{ $function="";}

class Clientes extends Tienda{

	public function __construct(){
		parent::__construct();
		$this->doc1="a_pagina/";
	}
	public function baner1(){
		try{
			parent::set_names();
			$sql="select * from baner where id=1";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetch(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}

	public function ajustes_editar(){
		try{
			parent::set_names();
			$sql="select * from ajustes where id=1";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetch(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}

	public function guardar_baner1(){
		try{
			parent::set_names();
			$arreglo=array();
			if (isset($_REQUEST['titulo'])){
				$arreglo+= array('titulo'=>$_REQUEST['titulo']);
			}
			if (isset($_REQUEST['texto'])){
				$arreglo+= array('texto'=>$_REQUEST['texto']);
			}

			$x=$this->update('baner',array('id'=>1), $arreglo);
			return $x;
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}

}
$db = new Clientes();
if(strlen($function)>0){
	echo $db->$function();
}
?>
