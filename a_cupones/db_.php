<?php
require_once("../control_db.php");
if (isset($_REQUEST['function'])){$function=$_REQUEST['function'];}	else{ $function="";}

class Cupones extends Tienda{
	public function __construct(){
		parent::__construct();
	}

	function generacupon($length = 8) {
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
	}

	public function cupones_lista(){
		try{
			parent::set_names();
			if (isset($_REQUEST['buscar']) and strlen(trim($_REQUEST['buscar']))>0){
				$texto=trim(htmlspecialchars($_REQUEST['buscar']));
				$sql="SELECT * from cupon where codigo like '%$texto%' limit 100";
			}
			else{
				$sql="SELECT * from cupon";
			}
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll();
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
	public function cupon_editar($id){
		try{
			parent::set_names();
			$sql="select * from cupon where id=:id";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(':id', $id);
			$sth->execute();
			return $sth->fetch(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function guardar_cupon(){
		try{
			parent::set_names();
			$id=$_REQUEST['id'];
			$arreglo =array();
			if (isset($_REQUEST['codigo'])){
				$arreglo+= array('codigo'=>$_REQUEST['codigo']);
			}
			if (isset($_REQUEST['descripcion'])){
				$arreglo+= array('descripcion'=>$_REQUEST['descripcion']);
			}
			if (isset($_REQUEST['tipo'])){
				$arreglo+= array('tipo'=>$_REQUEST['tipo']);
			}
			if (isset($_REQUEST['envio'])){
				$arreglo+= array('envio'=>$_REQUEST['envio']);
			}
			if (isset($_REQUEST['caducidad']) and strlen($_REQUEST['caducidad'])>0){
				$fx=explode("-",$_REQUEST['caducidad']);
				$arreglo+=array('caducidad'=>$fx['2']."-".$fx['1']."-".$fx['0']);
			}
			if (isset($_REQUEST['gasto_minimo'])){
				$arreglo+= array('gasto_minimo'=>$_REQUEST['gasto_minimo']);
			}
			if (isset($_REQUEST['gasto_maximo'])){
				$arreglo+= array('gasto_maximo'=>$_REQUEST['gasto_maximo']);
			}
			if (isset($_REQUEST['individual'])){
				$arreglo+= array('individual'=>$_REQUEST['individual']);
			}
			if (isset($_REQUEST['excluir'])){
				$arreglo+= array('excluir'=>$_REQUEST['excluir']);
			}
			if (isset($_REQUEST['importe'])){
				$arreglo+= array('importe'=>$_REQUEST['importe']);
			}
			if (isset($_REQUEST['limite_cup'])){
				$arreglo+= array('limite_cup'=>$_REQUEST['limite_cup']);
			}
			if (isset($_REQUEST['limite_art'])){
				$arreglo+= array('limite_art'=>$_REQUEST['limite_art']);
			}
			if (isset($_REQUEST['limite_user'])){
				$arreglo+= array('limite_user'=>$_REQUEST['limite_user']);
			}

			$x="";
			if($id==0){
				$x=$this->insert('cupon', $arreglo);
			}
			else{
				$x=$this->update('cupon',array('id'=>$id), $arreglo);
			}
			return $x;
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}

	public function busca_producto(){
		try{
			parent::set_names();
			$texto=$_REQUEST['texto'];
			$idcupon=$_REQUEST['idcupon'];
			$tipo_C=$_REQUEST['tipo_C'];

			//$tipo_C=1 incluir
			//$tipo_C=2 excluir

			$sql="SELECT * from productos where clave like '%$texto%' or numParte like '%$texto%' or nombre like '%$texto%' or modelo like '%$texto%' or marca like '%$texto%' limit 100";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			echo "<table class='table table-sm'>";
			echo "<tr><th>-</th><th>Clave</th><th>Num Parte</th><th>Nombre</th></tr>";
			foreach($sth->fetchAll() as $key){
				echo "<tr>";
					echo "<td>";
						echo "<div class='btn-group'>";
						echo "<button type='button' onclick='producto_cupon(".$key['idProducto'].",$idcupon,$tipo_C)' class='btn btn-outline-secondary btn-sm' title='Seleccionar cliente'><i class='fas fa-plus'></i></button>";
						echo "</div>";
					echo "</td>";
					echo "<td>";
							echo $key['clave'];
					echo "</td>";
					echo "<td>";
							echo $key['numParte'];
					echo "</td>";
					echo "<td>";
							echo $key['nombre'];
					echo "</td>";
				echo "</tr>";
			}
			echo "</table>";
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
	public function producto_cupon(){
		try{
			parent::set_names();
			$arreglo =array();
			if (isset($_REQUEST['idProducto'])){
				$arreglo+= array('idProducto'=>$_REQUEST['idProducto']);
			}
			if (isset($_REQUEST['idcupon'])){
				$arreglo+= array('idcupon'=>$_REQUEST['idcupon']);
			}
			if (isset($_REQUEST['tipo'])){
				$arreglo+= array('tipo'=>$_REQUEST['tipo']);
			}
			$x=$this->insert('cupon_productos', $arreglo);
			return $x;
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
	public function productos_incluir($idcupon,$tipo){
		try{
			parent::set_names();
			$sql="SELECT cupon_productos.id, productos.clave, productos.numParte, productos.nombre, productos.modelo, productos.marca from cupon_productos left outer join productos on productos.idProducto=cupon_productos.idproducto
			where cupon_productos.idcupon=$idcupon and cupon_productos.tipo=$tipo";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll();
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
	public function borrar_producto(){
		if (isset($_POST['id'])){$id=$_REQUEST['id'];}
		return $this->borrar('cupon_productos',"id",$id);
	}


	public function busca_cat(){
		try{
			parent::set_names();
			$texto=$_REQUEST['texto'];
			$idcupon=$_REQUEST['idcupon'];
			$tipo_C=$_REQUEST['tipo_C'];

			//$tipo_C=1 incluir
			//$tipo_C=2 excluir

			$sql="SELECT * from categorias where descripcion like '%$texto%' limit 100";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			echo "<table class='table table-sm'>";
			echo "<tr><th>-</th><th>Clave</th><th>Num Parte</th><th>Nombre</th></tr>";
			foreach($sth->fetchAll() as $key){
				echo "<tr>";
					echo "<td>";
						echo "<div class='btn-group'>";
						echo "<button type='button' onclick='categoria_cupon(".$key['idcategoria'].",$idcupon,$tipo_C)' class='btn btn-outline-secondary btn-sm' title='Seleccionar cliente'><i class='fas fa-plus'></i></button>";
						echo "</div>";
					echo "</td>";
					echo "<td>";
							echo $key['descripcion'];
					echo "</td>";
				echo "</tr>";
			}
			echo "</table>";
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
	public function categoria_cupon(){
		try{
			parent::set_names();
			$arreglo =array();
			if (isset($_REQUEST['idcategoria'])){
				$arreglo+= array('idcategoria'=>$_REQUEST['idcategoria']);
			}
			if (isset($_REQUEST['idcupon'])){
				$arreglo+= array('idcupon'=>$_REQUEST['idcupon']);
			}
			if (isset($_REQUEST['tipo'])){
				$arreglo+= array('tipo'=>$_REQUEST['tipo']);
			}
			$x=$this->insert('cupon_categoria', $arreglo);
			return $x;
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
	public function categoria_incluir($idcupon,$tipo){
		try{
			parent::set_names();
			$sql="SELECT cupon_categoria.id, categorias.descripcion from cupon_categoria left outer join categorias on categorias.idcategoria=cupon_categoria.idcategoria
			where cupon_categoria.idcupon=$idcupon and cupon_categoria.tipo=$tipo";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll();
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
	public function borrar_categoria(){
		if (isset($_POST['id'])){$id=$_REQUEST['id'];}
		return $this->borrar('cupon_categoria',"id",$id);
	}

	public function agregar_correo(){
		try{
			parent::set_names();
			$arreglo =array();
			if (isset($_REQUEST['idcupon'])){
				$arreglo+= array('idcupon'=>$_REQUEST['idcupon']);
			}
			if (isset($_REQUEST['correo'])){
				$arreglo+= array('correo'=>$_REQUEST['correo']);
			}
			$x=$this->insert('cupon_correo', $arreglo);
			return $x;
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
	public function correos($idcupon){
		try{
			parent::set_names();
			$sql="SELECT * from cupon_correo where cupon_correo.idcupon=$idcupon";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll();
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
	public function borrar_correo(){
		if (isset($_POST['id'])){$id=$_REQUEST['id'];}
		return $this->borrar('cupon_correo',"id",$id);
	}

	public function generar_cupon(){
		return $this->generacupon();
	}
}

$db = new Cupones();
if(strlen($function)>0){
	echo $db->$function();
}
?>
