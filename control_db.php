<?php
	if (!isset($_SESSION)) { session_start(); }
	if (isset($_REQUEST['function'])){$function=$_REQUEST['function'];}	else{ $function="";}
	if (isset($_REQUEST['ctrl'])){$ctrl=$_REQUEST['ctrl'];}	else{ $ctrl="";}

	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	date_default_timezone_set("America/Mexico_City");

	class Sagyc{
		public function __construct(){
			$this->Salud = array();
			date_default_timezone_set("America/Mexico_City");

			$_SESSION['mysqluser']="saludpublica";
			$_SESSION['mysqlpass']="saludp123$";
			$_SESSION['bdd']="sagycrmr_txpika";
			$_SESSION['servidor'] ="localhost";
			$this->dbh = new PDO("mysql:host=".$_SESSION['servidor'].";dbname=".$_SESSION['bdd']."", $_SESSION['mysqluser'], $_SESSION['mysqlpass']);
			self::set_names();
			$_SESSION['n_sistema']="Protecta SNTE";
		}
		public function set_names(){
			return $this->dbh->query("SET NAMES 'utf8'");
		}
		public function json(){
			$correo="algo";
			$x="hola mundo ".$correo;
			$arr=array('texto'=>$x,'correo'=>0);
			return json_encode($arr);
		}
    public function producto($id){
      try {
				self::set_names();
        $sql="select * from productos where id=:id";
        $sth = $this->dbh->prepare($sql);
        $sth->bindValue(':id', "$id");
        $sth->execute();
        if ($sth->rowCount()>0){
          return $sth->fetch(PDO::FETCH_OBJ);
        }
        else{
          include ("error.php");
          die();
        }
      }
      catch(PDOException $e){
        return "Database access FAILED!".$e->getMessage();
      }
    }
		public function productos_lista(){
			try{
				self::set_names();
				$sql="SELECT * from productos";
				$sth = $this->dbh->prepare($sql);
				$sth->execute();
				return $sth->fetchAll(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
	}

	if(strlen($ctrl)>0){
		$db = new Sagyc();
		if(strlen($function)>0){
			echo $db->$function();
		}
	}
	function moneda($valor){
		return "$ ".number_format( $valor, 2, "." , "," );
	}
	function fecha($fecha,$key=""){
		$fecha = new DateTime($fecha);
		if($key==1){
			$mes=$fecha->format('m');
			if ($mes==1){ $mes="Enero";}
			if ($mes==2){ $mes="Febrero";}
			if ($mes==3){ $mes="Marzo";}
			if ($mes==4){ $mes="Abril";}
			if ($mes==5){ $mes="Mayo";}
			if ($mes==6){ $mes="Junio";}
			if ($mes==7){ $mes="Julio";}
			if ($mes==8){ $mes="Agosto";}
			if ($mes==9){ $mes="Septiembre";}
			if ($mes==10){ $mes="Octubre";}
			if ($mes==11){ $mes="Noviembre";}
			if ($mes==12){ $mes="Diciembre";}

			return $fecha->format('d')." de $mes de ".$fecha->format('Y');
		}
		if($key==2){
			return $fecha->format('d-m-Y H:i:s');
		}
		else{
			return $fecha->format('d-m-Y');
		}
	}
?>
