<?php
require_once("../control_db.php");
if (isset($_REQUEST['function'])){$function=$_REQUEST['function'];}	else{ $function="";}

class Productos extends Tienda{
	public function __construct(){
		parent::__construct();
	}
	public function carga(){
		$idProducto=$_REQUEST['idProducto'];
		return $idProducto;
	}

}
$db = new Productos();
if(strlen($function)>0){
	echo $db->$function();
}
?>
