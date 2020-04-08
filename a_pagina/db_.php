<?php
require_once("../control_db.php");
if (isset($_REQUEST['function'])){$function=$_REQUEST['function'];}	else{ $function="";}

class Clientes extends Tienda{

	public function __construct(){
		parent::__construct();
		$this->doc1="a_pagina/";
	}
	public function baner_lista(){
		try{
			parent::set_names();
			$sql="select * from baner";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll();
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}

	public function baner1($id){
		try{
			parent::set_names();
			$sql="select * from baner where id=:id";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(':id', "$id");
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
			$id=$_REQUEST['id'];
			if (isset($_REQUEST['titulo'])){
				$arreglo+= array('titulo'=>$_REQUEST['titulo']);
			}
			if (isset($_REQUEST['texto'])){
				$arreglo+= array('texto'=>$_REQUEST['texto']);
			}
			if (isset($_REQUEST['enlace'])){
				$arreglo+= array('enlace'=>$_REQUEST['enlace']);
			}
			$x="";
			if($id==0){
				$x=$this->insert('baner', $arreglo);
			}
			else{
				$x=$this->update('baner',array('id'=>$id), $arreglo);
			}
			return $x;
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function quitar_baner(){
		if (isset($_POST['id'])){$id=$_POST['id'];}
		return $this->borrar('baner',"id",$id);
	}
}
$db = new Clientes();
if(strlen($function)>0){
	echo $db->$function();
}
?>
