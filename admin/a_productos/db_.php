<?php
require_once("../control_db.php");
if (isset($_REQUEST['function'])){$function=$_REQUEST['function'];}	else{ $function="";}

class Productos extends Sagyc{

	public function __construct(){
		parent::__construct();
	}
	public function productos_lista(){
		try{
			parent::set_names();
			$sql="SELECT * from productos";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll();
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function producto_editar($id){
		try{
			parent::set_names();
			$sql="select * from productos where id=:id";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(':id', "$id");
			$sth->execute();
			return $sth->fetch(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}

	public function guardar_producto(){
		try{
			parent::set_names();
			$id=$_REQUEST['id'];
			$arreglo =array();
			if (isset($_REQUEST['nombre'])){
				$arreglo = array('nombre'=>$_REQUEST['nombre']);
			}
			if (isset($_REQUEST['informacion'])){
				$arreglo += array('informacion'=>$_REQUEST['informacion']);
			}

			$x="";
			if($id==0){
				$x.=$this->insert('productos', $arreglo);
			}
			else{
				$x.=$this->update('productos',array('id'=>$id), $arreglo);
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
$db = new Productos();
if(strlen($function)>0){
	echo $db->$function();
}
?>
