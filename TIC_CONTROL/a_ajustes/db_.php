<?php
require_once("../control_db.php");

class Clientes extends Tienda{

	public function __construct(){
		parent::__construct();
	}

	public function ajustes_editar(){
		try{
			parent::set_names();
			$sql="select * from ajustes where id=1";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetch(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}

	public function guardar_ajustes(){
		try{
			parent::set_names();
			$arreglo=array();
			if (isset($_REQUEST['correo'])){
				$arreglo+= array('correo'=>$_REQUEST['correo']);
			}
			if (isset($_REQUEST['host'])){
				$arreglo+= array('host'=>$_REQUEST['host']);
			}
			if (isset($_REQUEST['SMTPAuth'])){
				$arreglo+= array('SMTPAuth'=>$_REQUEST['SMTPAuth']);
			}
			if (isset($_REQUEST['Password'])){
				$arreglo+= array('Password'=>$_REQUEST['Password']);
			}
			if (isset($_REQUEST['SMTPSecure'])){
				$arreglo+= array('SMTPSecure'=>$_REQUEST['SMTPSecure']);
			}
			if (isset($_REQUEST['Port'])){
				$arreglo+= array('Port'=>$_REQUEST['Port']);
			}



			if (isset($_REQUEST['c_envio'])){
				$arreglo+= array('c_envio'=>$_REQUEST['c_envio']);
			}
			if (isset($_REQUEST['p_general'])){
				$arreglo+= array('p_general'=>$_REQUEST['p_general']);
			}
			if (isset($_REQUEST['mercado_public'])){
				$arreglo+= array('mercado_public'=>$_REQUEST['mercado_public']);
			}
			if (isset($_REQUEST['mercado_token'])){
				$arreglo+= array('mercado_token'=>$_REQUEST['mercado_token']);
			}
			if (isset($_REQUEST['paypal_client'])){
				$arreglo+= array('paypal_client'=>$_REQUEST['paypal_client']);
			}
			$x=$this->update('ajustes',array('id'=>1), $arreglo);
			return $x;
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
