<?php
require_once("../control_db.php");
if (isset($_REQUEST['function'])){$function=$_REQUEST['function'];}	else{ $function="";}

class Productos extends Tienda{
	public function __construct(){
		parent::__construct();
		$this->doc1="a_imagen/";
		$this->doc="a_imagenextra/";
	}
	public function califiaciones_lista(){
		try{
			parent::set_names();
			if (isset($_REQUEST['buscar']) and strlen(trim($_REQUEST['buscar']))>0){
				$texto=trim(htmlspecialchars($_REQUEST['buscar']));
				$sql="SELECT producto_estrella.*, productos.nombre from producto_estrella
				left outer join productos on productos.id=producto_estrella.idproducto
				where clave like '%$texto%' or nombre like '%$texto%' or modelo like '%$texto%' or marca like '%$texto%' or productos.idProducto like '%$texto%' limit 100";
			}
			else{
				$sql="SELECT producto_estrella.*, productos.nombre from producto_estrella
				left outer join productos on productos.id=producto_estrella.idproducto
				order by producto_estrella.fecha desc limit 100";
			}
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll();
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
	public function estrellla_editar($id){
		try{
			parent::set_names();
			$sql="select producto_estrella.*,productos.nombre, productos.clave from producto_estrella
			left outer join productos on productos.id=producto_estrella.idproducto
			where producto_estrella.id=:id";
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

			$id=$_REQUEST['id'];
			$activo=$_REQUEST['activo'];


			$arreglo =array();
			$arreglo+= array('publico'=>$activo);
			$x=$this->update('producto_estrella',array('id'=>$id), $arreglo);

			return $x;
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
