<?php
require_once("../control_db.php");
if (isset($_REQUEST['function'])){$function=$_REQUEST['function'];}	else{ $function="";}

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
