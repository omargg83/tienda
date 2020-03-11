<?php
require_once("../control_db.php");
if (isset($_REQUEST['function'])){$function=$_REQUEST['function'];}	else{ $function="";}

class Categorias extends Tienda{

	public function __construct(){
		parent::__construct();
	}
	public function categorias_lista(){
		try{
			parent::set_names();
			$sql="SELECT * from categorias";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll();
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function categoria_editar($id){
		try{
			parent::set_names();
			$sql="select * from categorias where idcategoria=:id";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(':id', "$id");
			$sth->execute();
			return $sth->fetch(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}

	public function guardar_categoria(){
		try{
			parent::set_names();
			$id=$_REQUEST['id'];
			$arreglo =array();
			if (isset($_REQUEST['descripcion'])){
				$arreglo = array('descripcion'=>$_REQUEST['descripcion']);
			}

			$x="";
			if($id==0){
				$x=$this->insert('categorias', $arreglo);
			}
			else{
				$x=$this->update('categorias',array('idcategoria'=>$id), $arreglo);
			}
			return $x;
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function borrar_oficio(){
		if (isset($_POST['id'])){$id=$_POST['id'];}

		$arreglo =array();
		$arreglo+=array('idcomision'=>NULL);
		$this->update('zsalidacal',array('idcomision'=>$id), $arreglo);
		return $this->borrar('zofcomision',"idcomision",$id);
	}

}
$db = new Categorias();
if(strlen($function)>0){
	echo $db->$function();
}
?>
