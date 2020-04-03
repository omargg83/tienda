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
				$sql="SELECT * from categoria_ct where categoria like '%$texto%'";
			}
			else{
				$sql="SELECT * from categoria_ct";
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
			$sql="select * from categoria_ct where id=:id";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(':id', "$id");
			$sth->execute();
			return $sth->fetch(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function guardar_categoriact(){
		try{
			parent::set_names();
			$id=$_REQUEST['id'];
			$arreglo =array();

			if (isset($_REQUEST['categoria'])){
				$arreglo = array('categoria'=>$_REQUEST['categoria']);
			}
			if (isset($_REQUEST['heredado'])){
				$arreglo = array('heredado'=>$_REQUEST['heredado']);
			}

			$x="";
			if($id==0){
				$arreglo = array('interno'=>1);
				$x=$this->insert('categoria_ct', $arreglo);
			}
			else{
				$x=$this->update('categoria_ct',array('id'=>$id), $arreglo);
			}
			return $x;
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}


	public function edita_subcat($id){
		try{
			parent::set_names();
			$sql="SELECT * from categoriasub_ct where id='$id'";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetch(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function agrega_subcategoria(){
		try{
			parent::set_names();
			$id=$_REQUEST['id'];
			$idcategoria=$_REQUEST['idcategoria'];
			$arreglo =array();

			if (isset($_REQUEST['subcategoria'])){
				$arreglo = array('subcategoria'=>$_REQUEST['subcategoria']);
			}
			if (isset($_REQUEST['heredado'])){
				$arreglo = array('heredado'=>$_REQUEST['heredado']);
			}

			$x="";
			if($id==0){
				$arreglo = array('interno'=>1);
				$arreglo = array('idcategoria'=>$idcategoria);
				$x=$this->insert('categoriasub_ct', $arreglo);
			}
			else{
				$x=$this->update('categoriasub_ct',array('id'=>$id), $arreglo);
			}
			return $x;
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function producto_cat($idcategoria){
		try{
			parent::set_names();
			$sql="SELECT * from categoriasub_ct where idcategoria=:id";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(':id', $idcategoria);
			$sth->execute();
			return $sth->fetchAll();
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function quitar_categoria(){
		if (isset($_POST['id'])){$id=$_REQUEST['id'];}
		return $this->borrar('producto_cat',"idcatprod",$id);
	}
	public function busca_sub(){
		try{
			$cat=$_REQUEST['categoria'];
			$sql="select * from categoriasub_ct where idcategoria='$cat'";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			echo "<table class='table table-sm' style='font-size:12px'>";
			echo "<tr><th>Subcategorias incluidas</th></tr>";
			foreach($sth->fetchAll() as $key){
				echo "<tr>";
				echo "<td>";
					echo $key['subcategoria'];
				echo "</td>";
				echo "</tr>";
			}
			echo "</table>";
			//return $cat;
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
}
$db = new Categorias();
if(strlen($function)>0){
	echo $db->$function();
}
?>
