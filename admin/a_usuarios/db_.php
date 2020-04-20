<?php
require_once("../control_db.php");
if (isset($_REQUEST['function'])){$function=$_REQUEST['function'];}	else{ $function="";}

class Usuarios extends Tienda{

	public function __construct(){
		parent::__construct();
	}


	public function usuarios_lista(){
		try{
			parent::set_names();
			if (isset($_REQUEST['buscar']) and strlen(trim($_REQUEST['buscar']))>0){
				$texto=trim(htmlspecialchars($_REQUEST['buscar']));
				$sql="SELECT * from usuarios where nombre like '%$texto%'";
			}
			else{
				$sql="SELECT * from usuarios";
			}
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll();
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
	public function usuario_editar($id){
		try{
			parent::set_names();
			$sql="select * from usuarios where idpersona=:id";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(':id', "$id");
			$sth->execute();
			return $sth->fetch(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function guardar_usuario(){
		try{
			parent::set_names();
			$id=$_REQUEST['id'];
			$arreglo =array();
			if (isset($_REQUEST['nombre'])){
				$arreglo+= array('nombre'=>$_REQUEST['nombre']);
			}
			if (isset($_REQUEST['usuario'])){
				$arreglo+= array('usuario'=>$_REQUEST['usuario']);
			}
			if (isset($_REQUEST['autoriza'])){
				$arreglo+= array('autoriza'=>$_REQUEST['autoriza']);
			}
			if (isset($_REQUEST['nivel'])){
				$arreglo+= array('nivel'=>$_REQUEST['nivel']);
			}

			$x="";
			if($id==0){
				$x=$this->insert('usuarios', $arreglo);
			}
			else{
				$x=$this->update('usuarios',array('idpersona'=>$id), $arreglo);
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
			$x=$this->update('usuarios',array('idpersona'=>$id), $arreglo);
			return $x;
		}
		else{
			return "La contraseña no coincide";
		}
	}

}
$db = new Usuarios();
if(strlen($function)>0){
	echo $db->$function();
}
?>
