<?php
require_once("../control_db.php");
if (isset($_REQUEST['function'])){$function=$_REQUEST['function'];}	else{ $function="";}

class Productos extends Tienda{

	public function __construct(){
		parent::__construct();

		$this->doc="a_imagenextra/";
	}
	public function productos_lista(){
		try{
			parent::set_names();
			if (isset($_REQUEST['buscar']) and strlen(trim($_REQUEST['buscar']))>0){
				$texto=trim(htmlspecialchars($_REQUEST['buscar']));
				$sql="SELECT * from productos where clave like '%$texto%' or nombre like '%$texto%' or modelo like '%$texto%' or marca like '%$texto%' or idProducto like '%$texto%' limit 100";
			}
			else{
				$sql="SELECT * from productos limit 100";
			}
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll();
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
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
	public function producto_exist($id){
		try{
			parent::set_names();
			$sql="select * from producto_exist where idProducto=:id";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(':id', "$id");
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function producto_espe($id){
		try{
			parent::set_names();
			$sql="select * from producto_espe where idProducto=:id";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(':id', "$id");
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}



	public function producto_categoria($id){
		try{
			parent::set_names();
			$sql="select * from producto_cat left outer join categorias on categorias.idcategoria=producto_cat.idcategoria where idproducto=:id";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(':id', "$id");
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
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
			if (isset($_REQUEST['descripcion'])){
				$arreglo += array('descripcion'=>$_REQUEST['descripcion']);
			}
			if (isset($_REQUEST['sku'])){
				$arreglo += array('sku'=>$_REQUEST['sku']);
			}
			if (isset($_REQUEST['precio'])){
				$arreglo += array('precio'=>$_REQUEST['precio']);
			}
			if (isset($_REQUEST['cantidad'])){
				$arreglo += array('cantidad'=>$_REQUEST['cantidad']);
			}

			$x="";
			if($id==0){
				$x=$this->insert('productos', $arreglo);
			}
			else{
				$x=$this->update('productos',array('idproducto'=>$id), $arreglo);
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
	public function categoria_agrega(){
		try{
			parent::set_names();
			$arreglo =array();
			$idcategoria="";
			$idproducto="";
			if (isset($_REQUEST['idcategoria'])){
				$idcategoria=$_REQUEST['idcategoria'];
				$arreglo += array('idcategoria'=>$idcategoria);
			}
			if (isset($_REQUEST['id'])){
				$idproducto=$_REQUEST['id'];
				$arreglo += array('idproducto'=>$idproducto);
			}
			$sql="select * from producto_cat where idproducto=:idprod and idcategoria=:idcat";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(':idprod', $idproducto);
			$sth->bindValue(':idcat', $idcategoria);
			$sth->execute();
			if ($sth->rowCount()==0){
				$x=$this->insert('producto_cat', $arreglo);
				$arreglo =array();
				$arreglo+=array('id'=>$idproducto);
				$arreglo+=array('error'=>0);
				return json_encode($arreglo);
			}
			else{
				$arreglo =array();
				$arreglo+=array('id'=>0);
				$arreglo+=array('error'=>1);
				$arreglo+=array('terror'=>"Ya existe en esta categoria");
				return json_encode($arreglo);
			}
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function almacen_busca($clave){
		try{
			parent::set_names();
			$sql="select * from almacen where homoclave=:id";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(':id', "$clave");
			$sth->execute();
			return $sth->fetch(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}

	public function imagen($id){
		try{
			parent::set_names();
			$sql="select * from producto_img where idproducto='$id'";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll();
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}

}
$db = new Productos();
if(strlen($function)>0){
	echo $db->$function();
}
?>
