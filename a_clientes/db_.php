<?php
require_once("../control_db.php");
if (isset($_REQUEST['function'])){$function=$_REQUEST['function'];}	else{ $function="";}

class Clientes extends Tienda{

	public function __construct(){
		parent::__construct();
	}
	public function clientes_lista(){
		try{
			parent::set_names();
			if (isset($_REQUEST['buscar']) and strlen(trim($_REQUEST['buscar']))>0){
				$texto=trim(htmlspecialchars($_REQUEST['buscar']));
				$sql="SELECT * from clientes where correo is not null and (nombre like '%$texto%' or apellido like '%$texto%' or correo like '%$texto%') limit 100";
			}
			else{
				$sql="SELECT * from clientes where correo is not null";
			}
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll();
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
	public function clientes_listasin(){
		try{
			parent::set_names();
			$sql="SELECT * from clientes where correo is null";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll();
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
	public function cliente_editar($id){
		try{
			parent::set_names();
			$sql="select * from clientes where id=:id";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(':id', "$id");
			$sth->execute();
			return $sth->fetch(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function guardar_cliente(){
		try{
			parent::set_names();
			$id=$_REQUEST['id'];
			$arreglo =array();

			if (isset($_REQUEST['rfc'])){
				$arreglo+= array('rfc'=>$_REQUEST['rfc']);
			}
			if (isset($_REQUEST['cfdi'])){
				$arreglo+= array('cfdi'=>$_REQUEST['cfdi']);
			}
			if (isset($_REQUEST['nombre'])){
				$arreglo+= array('nombre'=>$_REQUEST['nombre']);
			}
			if (isset($_REQUEST['apellido'])){
				$arreglo+= array('apellido'=>$_REQUEST['apellido']);
			}
			if (isset($_REQUEST['correo'])){
				$arreglo+= array('correo'=>$_REQUEST['correo']);
			}
			if (isset($_REQUEST['direccion1'])){
				$arreglo+= array('direccion1'=>$_REQUEST['direccion1']);
			}
			if (isset($_REQUEST['direccion2'])){
				$arreglo+= array('direccion2'=>$_REQUEST['direccion2']);
			}
			if (isset($_REQUEST['ciudad'])){
				$arreglo+= array('ciudad'=>$_REQUEST['ciudad']);
			}
			if (isset($_REQUEST['cp'])){
				$arreglo+= array('cp'=>$_REQUEST['cp']);
			}
			if (isset($_REQUEST['pais'])){
				$arreglo+= array('pais'=>$_REQUEST['pais']);
			}
			if (isset($_REQUEST['estado'])){
				$arreglo+= array('estado'=>$_REQUEST['estado']);
			}
			if (isset($_REQUEST['telefono'])){
				$arreglo+= array('telefono'=>$_REQUEST['telefono']);
			}

			$x="";
			if($id==0){
				$x=$this->insert('clientes', $arreglo);
			}
			else{
				$x=$this->update('clientes',array('id'=>$id), $arreglo);
			}
			return $x;
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function password(){
		if (isset($_REQUEST['id'])){$id=$_REQUEST['id'];}
		if (isset($_REQUEST['pass1'])){$pass1=$_REQUEST['pass1'];}
		if (isset($_REQUEST['pass2'])){$pass2=$_REQUEST['pass2'];}
		if(trim($pass1)==($pass2)){
			$arreglo=array();
			$passPOST=md5(trim($pass1));
			$arreglo=array('pass'=>$passPOST);
			$x=$this->update('clientes',array('id'=>$id), $arreglo);
			return $x;
		}
		else{
			return "La contraseña no coincide";
		}
	}
	public function direccion_editar($id){
		try{
			parent::set_names();
			$sql="select * from clientes_direccion where iddireccion=:id";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(':id', "$id");
			$sth->execute();
			return $sth->fetch(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}

	}
	public function guardar_direccion(){
		try{
			parent::set_names();
			$id=$_REQUEST['id'];
			$id2=$_REQUEST['id2'];
			$arreglo =array();
			$arreglo = array('idcliente'=>$id2);

			if (isset($_REQUEST['nombre'])){
				$arreglo+= array('nombre'=>$_REQUEST['nombre']);
			}
			if (isset($_REQUEST['apellidos'])){
				$arreglo+= array('apellidos'=>$_REQUEST['apellidos']);
			}
			if (isset($_REQUEST['empresa'])){
				$arreglo+= array('empresa'=>$_REQUEST['empresa']);
			}
			if (isset($_REQUEST['direccion1'])){
				$arreglo+= array('direccion1'=>$_REQUEST['direccion1']);
			}
			if (isset($_REQUEST['direccion2'])){
				$arreglo+= array('direccion2'=>$_REQUEST['direccion2']);
			}
			if (isset($_REQUEST['ciudad'])){
				$arreglo+= array('ciudad'=>$_REQUEST['ciudad']);
			}
			if (isset($_REQUEST['cp'])){
				$arreglo+= array('cp'=>$_REQUEST['cp']);
			}
			if (isset($_REQUEST['pais'])){
				$arreglo+= array('pais'=>$_REQUEST['pais']);
			}
			if (isset($_REQUEST['estado'])){
				$arreglo+= array('estado'=>$_REQUEST['estado']);
			}
			if (isset($_REQUEST['mail'])){
				$arreglo+= array('mail'=>$_REQUEST['mail']);
			}
			if (isset($_REQUEST['telefono'])){
				$arreglo+= array('telefono'=>$_REQUEST['telefono']);
			}

			$x="";
			if($id==0){
				$x=$this->insert('clientes_direccion', $arreglo);
			}
			else{
				$x=$this->update('clientes_direccion',array('iddireccion'=>$id), $arreglo);
			}
			return $x;
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}


	}
	public function direcciones($id){
		try{
			parent::set_names();
			$sql="SELECT * from clientes_direccion where idcliente=:id";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(':id',$id);
			$sth->execute();
			return $sth->fetchAll();
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
	public function dir_envio(){
		try{
			parent::set_names();
			$id=$_REQUEST['id'];
			$idcliente=$_REQUEST['idcliente'];

			$sql="update clientes_direccion set envio=null where idcliente='$idcliente'";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();

			$sql="update clientes_direccion set envio=1 where iddireccion='$id'";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function dir_factura(){
		try{
			parent::set_names();
			$id=$_REQUEST['id'];
			$idcliente=$_REQUEST['idcliente'];

			$sql="update clientes_direccion set factura=null where idcliente='$idcliente'";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();

			$sql="update clientes_direccion set factura=1 where iddireccion='$id'";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();

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
