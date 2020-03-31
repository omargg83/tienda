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
			if (isset($_REQUEST['buscar']) and strlen(trim($_REQUEST['buscar']))>0){
				$texto=trim(htmlspecialchars($_REQUEST['buscar']));
				$sql="SELECT * from categorias where descripcion like '%$texto%' limit 100";
			}
			else{
				$sql="SELECT * from categorias";
			}
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll();
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
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
	public function agrupa_cat(){
		try{
			parent::set_names();
			$sql="SELECT * from categoria_ct group by categoria asc";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll();
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function agrega_categoria(){
		try{
			parent::set_names();
			$id=$_REQUEST['id'];
			$categoria=$_REQUEST['categoria'];
			$arreglo =array();
			$arreglo+= array('idcategoria'=>$id);
			$arreglo+= array('categoria'=>$categoria);
			$x=$this->insert('producto_cat', $arreglo);

			$tmp=json_decode($x);
			$arreglo=array();
			$arreglo+=array('id'=>$id);
			$arreglo+=array('error'=>$tmp->error);
			$arreglo+=array('terror'=>$tmp->terror);
			$arreglo+=array('param1'=>$tmp->id);
			$arreglo+=array('param2'=>"");
			$arreglo+=array('param3'=>"");
			return json_encode($arreglo);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function producto_cat($idcategoria){
		try{
			parent::set_names();
			$sql="SELECT * from producto_cat where idcategoria=:id";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(':id', $idcategoria);
			$sth->execute();
			return $sth->fetchAll();
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
			if (isset($_REQUEST['categoria_usuario'])){
				$arreglo = array('categoria_usuario'=>$_REQUEST['categoria_usuario']);
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
	public function quitar_categoria(){
		if (isset($_POST['id'])){$id=$_REQUEST['id'];}
		return $this->borrar('producto_cat',"idcatprod",$id);
	}

}
$db = new Categorias();
if(strlen($function)>0){
	echo $db->$function();
}
?>
