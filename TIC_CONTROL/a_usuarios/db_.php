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
			return "Database access FAILED! ";
		}
	}
	public function usuario_editar($id){
		try{
			parent::set_names();
			$sql="select nombre, correo_xptic, autoriza, nivel, hash from usuarios where idpersona=:id";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(':id', "$id");
			$sth->execute();
			return $sth->fetch(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!";
		}
	}
	public function guardar_usuario(){
		try{
			$id=$_REQUEST['id'];
			$arreglo =array();
			$fecha=('dmYHis');
			if (isset($_REQUEST['nombre'])){
				$arreglo+= array('nombre'=>$_REQUEST['nombre']);
			}
			if (isset($_REQUEST['autoriza'])){
				$arreglo+= array('autoriza'=>$_REQUEST['autoriza']);
			}
			if (isset($_REQUEST['nivel'])){
				$arreglo+= array('nivel'=>$_REQUEST['nivel']);
			}
			if (isset($_REQUEST['correo'])){
				$arreglo+= array('correo'=>$_REQUEST['correo']);
			}

			$x="";
			if($id==0){
				$cadena = $fecha;
				$cadena=md5($cadena);
				$cadena=hash("sha512",$cadena);
				$arreglo+= array('hash'=>$cadena);
				$x=$this->insert('usuarios', $arreglo);
			}
			else{
				$cadena = $id.$fecha;
				$cadena=md5($cadena);
				$cadena=hash("sha512",$cadena);
				$arreglo+= array('hash'=>$cadena);
				$x=$this->update('usuarios',array('hash'=>$id), $arreglo);
			}
			return $x;
		}
		catch(PDOException $e){
			return "Database access FAILED!";
		}
	}
	public function password(){
		try{
			if (isset($_REQUEST['id'])){$id=$_REQUEST['id'];}
			if (isset($_REQUEST['id2'])){$id2=$_REQUEST['id2'];}
			if (isset($_REQUEST['pass1'])){$pass1=$_REQUEST['pass1'];}
			if (isset($_REQUEST['pass2'])){$pass2=$_REQUEST['pass2'];}

			$a=$this->validar_clave($pass1);
			if(strlen($a)>0){
				return $a;
			}
			if(trim($pass1)==($pass2)){
				$arreglo=array();

				////////////////////
				$cadena=md5("tic%pika_$%&/()=").md5(trim($pass1));
				$cadena=hash("sha512",$cadena);

				$arreglo=array('pass_xptic'=>$cadena);
				$x=$this->update('usuarios',array('hash'=>$id), $arreglo);

				$arr=array();
				$arr+=array('error'=>0);
				$arr+=array('id'=>$id2);
				return json_encode($arr);
			}
			else{
				return "$a La contraseña no coincide";
			}
		}
		catch(PDOException $e){
			return "Database access FAILED!";
		}
	}
	private function validar_clave($clave){
		if(strlen($clave) < 12){
		  return "La clave debe tener al menos 6 caracteres";
		}
		if(strlen($clave) > 16){
		  return "La clave no puede tener más de 16 caracteres";
		}
		if (!preg_match('`[a-z]`',$clave)){
		  return "La clave debe tener al menos una letra minúscula";
		}
		if (!preg_match('`[A-Z]`',$clave)){
		  return "La clave debe tener al menos una letra mayúscula";
		}
		if (!preg_match('`[0-9]`',$clave)){
		  return "La clave debe tener al menos un caracter numérico";
		}
	}
	public function borrar_usuario(){
		try{
			if (isset($_POST['id'])){$id=$_REQUEST['id'];}
			return $this->borrar('usuarios',"idpersona",$id);
		}
		catch(PDOException $e){
			return "$a La contraseña no coincide";
		}
	}
}
$db = new Usuarios();
if(strlen($function)>0){
	echo $db->$function();
}
?>
