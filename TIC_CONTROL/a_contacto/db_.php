<?php
require_once("../control_db.php");

class Clientes extends Tienda{

	public function __construct(){
		parent::__construct();
	}
	public function clientes_lista(){
		try{
			parent::set_names();
			if (isset($_REQUEST['buscar']) and strlen(trim($_REQUEST['buscar']))>0){
				$texto=trim(htmlspecialchars($_REQUEST['buscar']));
				$sql="SELECT * from contacto where nombre like '%$texto%' or mensaje like '%$texto%' or correo like '%$texto%' or telefono like '%$texto%' limit 100";
			}
			else{
				$sql="SELECT * from contacto order by fecha desc";
			}
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll();
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}

	public function contacto($id){
		try{
			parent::set_names();
			$sql="select * from contacto where id=:id";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(':id', "$id");
			$sth->execute();
			return $sth->fetch(PDO::FETCH_OBJ);
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
