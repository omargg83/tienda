<?php
	if (!isset($_SESSION)) { session_start(); }
	if (isset($_REQUEST['function'])){$function=$_REQUEST['function'];}	else{ $function="";}
	if (isset($_REQUEST['ctrl'])){$ctrl=$_REQUEST['ctrl'];}	else{ $ctrl="";}

	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	date_default_timezone_set("America/Mexico_City");

	class Tienda{

		public function __construct(){
			$this->Salud = array();
			date_default_timezone_set("America/Mexico_City");
			$_SESSION['mysqluser']="ticshopc_admin";
			$_SESSION['mysqlpass']="admin123$%";
			$_SESSION['servidor'] ="tic-shop.com.mx";
			$_SESSION['bdd']="ticshopc_tienda";
			$this->dbh = new PDO("mysql:host=".$_SESSION['servidor'].";dbname=".$_SESSION['bdd']."", $_SESSION['mysqluser'], $_SESSION['mysqlpass']);
			self::set_names();

			$this->doc="../a_imagen/";
			$this->extra="../a_imagenextra/";
		}
		public function set_names(){
			return $this->dbh->query("SET NAMES 'utf8'");
		}

		public function galleta(){
			try{
				$galleta=$_REQUEST['galleta'];
				if(strlen($galleta)==0){
					$galleta=$this->genera_random();
					$sql="insert into clientes (galleta, fechacreado) values (:galleta, :fechacreado)";
					$sth = $this->dbh->prepare($sql);
					$sth->bindValue(":galleta",$galleta);
					$sth->bindValue(":fechacreado",date("Y-m-d H:i:s"));
					$sth->execute();
				}
				$sql="SELECT * FROM clientes where galleta=:galleta";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(":galleta",$galleta);
				$sth->execute();
				$CLAVE=$sth->fetch();
				$_SESSION['autoriza_web']=1;
				$_SESSION['interno']=0;
				$_SESSION['correo']="";
				$_SESSION['idcliente']=$CLAVE['id'];
				return $galleta;
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function genera_random($length = 15) {
    	return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
		}

		public function login(){



			return "algo";


		}
		public function acceso(){
			//Obtenemos los datos del formulario de acceso
			try{
				$userPOST = htmlspecialchars($_REQUEST["userAcceso"]);
				$passPOST = $_REQUEST["passAcceso"];

				$sql="SELECT * FROM clientes where correo=:correo and pass=:pass";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(":correo",$userPOST);
				$sth->bindValue(":pass",$passPOST);
				$sth->execute();
				$CLAVE=$sth->fetch();
				if($CLAVE){
					if($userPOST == $CLAVE['correo'] and strtoupper($passPOST)==strtoupper($CLAVE['pass'])){
						$_SESSION['autoriza_web']=1;
						$_SESSION['correo']=$CLAVE['correo'];
						$_SESSION['idcliente']=$CLAVE['id'];
						$_SESSION['interno']=1;
						$arr=array();
						$arr=array('acceso'=>1);
						return json_encode($arr);
					}
					else {
						$arr=array();
						$arr=array('acceso'=>0);
						return json_encode($arr);	return "Usuario o Contraseña incorrecta";
					}
				}
				else {
					$arr=array();
					$arr=array('acceso'=>0);
					return json_encode($arr);	return "Usuario o Contraseña incorrecta";
				}

			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function salir(){
			$_SESSION['autoriza_web']=0;
			$_SESSION['correo']="";
		}
		public function categorias(){
			try{
				self::set_names();
				$sql="select * from categorias";
				$sth = $this->dbh->prepare($sql);
				$sth->execute();
				return $sth->fetchAll(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function cat_ct($id){
			try{
				self::set_names();
				$sql="SELECT categoria_ct.id, categoria_ct.categoria from producto_cat left outer join categoria_ct on categoria_ct.id=producto_cat.idcategoria_ct where producto_cat.idcategoria=:id";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(':id', "$id");
				$sth->execute();
				return $sth->fetchAll(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function sub_cat($id){
			try{
				self::set_names();
				$sql="SELECT * from categoriasub_ct where idcategoria=:id";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(':id', "$id");
				$sth->execute();
				return $sth->fetchAll(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}

		public function producto_ver($id){
			try{
				self::set_names();
				$sql="select * from productos where id=:id";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(':id', "$id");
				$sth->execute();
				return $sth->fetch(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function producto_imagen($id){
			try{
				self::set_names();
				$sql="select * from producto_img where id=:id";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(':id', "$id");
				$sth->execute();
				return $sth->fetchAll(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}

		public function ofertas(){
			try{
				self::set_names();
				$sql="select * from productos limit 3";
				$sth = $this->dbh->prepare($sql);
				$sth->execute();
				return $sth->fetchAll(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function destacados(){
			try{
				self::set_names();
				$sql="select * from productos limit 16 OFFSET 16";
				$sth = $this->dbh->prepare($sql);
				$sth->execute();
				return $sth->fetchAll(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function venta(){
			try{
				self::set_names();
				$sql="select * from productos limit 16 OFFSET 32";
				$sth = $this->dbh->prepare($sql);
				$sth->execute();
				return $sth->fetchAll(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function rated(){
			try{
				self::set_names();
				$sql="select * from productos limit 16 OFFSET 64";
				$sth = $this->dbh->prepare($sql);
				$sth->execute();
				return $sth->fetchAll(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}

		public function nuevos(){
			try{
				self::set_names();
				$sql="select * from productos limit 64";
				$sth = $this->dbh->prepare($sql);
				$sth->execute();
				return $sth->fetchAll(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function nuevos_2(){
			try{
				self::set_names();
				$sql="select * from productos limit 16 offset 128";
				$sth = $this->dbh->prepare($sql);
				$sth->execute();
				return $sth->fetchAll(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}

		public function carro(){
			try{
				self::set_names();
				$sql="select * from cliente_carro
				left outer join productos on productos.id=cliente_carro.idproducto
				where cliente_carro.idcliente=:id";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(":id",$_SESSION['idcliente']);
				$sth->execute();
				return $sth->fetchAll(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function carrito(){
			try{
				self::set_names();
				$id=$_REQUEST['id'];
				if(isset($_SESSION['autoriza_web']) and $_SESSION['autoriza_web']==1 and strlen($_SESSION['idcliente'])>0){
					$sql="insert into cliente_carro (idcliente, idproducto, fechaagrega) values (:idcliente, :idproducto, :fecha)";
					$sth = $this->dbh->prepare($sql);
					$sth->bindValue(":idcliente",$_SESSION['idcliente']);
					$sth->bindValue(":idproducto",$id);
					$sth->bindValue(":fecha",date("Y-m-d H:i:s"));
					$resp=$sth->execute();
					if($resp){
						$arr=array();
						$arr=array('error'=>0);
						return json_encode($arr);
					}
					else{
						$arr=array();
						$arr=array('error'=>1);
						$arr=array('terror'=>$resp);
						return json_encode($arr);
					}
				}
				else{
					$arr=array();
					$arr=array('error'=>2);
					return json_encode($arr);
				}
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function wish(){
			try{
				self::set_names();
				$id=$_REQUEST['id'];
				if(isset($_SESSION['autoriza_web']) and $_SESSION['autoriza_web']==1 and strlen($_SESSION['idcliente'])>0){
					$sql="insert into cliente_wish (idcliente, idproducto, fechaagrega) values (:idcliente, :idproducto, :fecha)";
					$sth = $this->dbh->prepare($sql);
					$sth->bindValue(":idcliente",$_SESSION['idcliente']);
					$sth->bindValue(":idproducto",$id);
					$sth->bindValue(":fecha",date("Y-m-d H:i:s"));
					$resp=$sth->execute();
					if($resp){
						$arr=array();
						$arr=array('error'=>0);
						return json_encode($arr);
					}
					else{
						$arr=array();
						$arr=array('error'=>1);
						$arr=array('terror'=>$resp);
						return json_encode($arr);
					}
				}
				else{
					$arr=array();
					$arr=array('error'=>2);
					return json_encode($arr);
				}
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}


		public function carrito_sum(){

			try{
				self::set_names();
				$sql="select count(productos.id) as contar, sum(productos.preciof) as sumar from cliente_carro
				left outer join productos on productos.id=cliente_carro.idproducto
				where cliente_carro.idcliente=:id";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(":id",$_SESSION['idcliente']);
				$sth->execute();
				return $sth->fetch(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function wish_sum(){

			try{
				self::set_names();
				$sql="select count(id) as contar from cliente_wish where cliente_wish.idcliente=:id";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(":id",$_SESSION['idcliente']);
				$sth->execute();
				return $sth->fetch(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function cat_categoria($cat){
			try{
				self::set_names();
				$sql="select * from productos where categoria=:id and activo=1";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(":id",$cat);
				$sth->execute();
				return $sth->fetchAll(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function sub_categoria($cat){
			try{
				self::set_names();
				$sql="select * from productos where subcategoria=:id and activo=1";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(":id",$cat);
				$sth->execute();
				return $sth->fetchAll(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function cat_categoriatic($cat){
			try{
				self::set_names();
				$sql="select productos.* from producto_cat
							left outer join categoria_ct on categoria_ct.id=producto_cat.idcategoria_ct
							left outer join productos on productos.categoria=categoria_ct.categoria
							where producto_cat.idcategoria=:cat and productos.activo=1";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(":cat",$cat);
				$sth->execute();
				return $sth->fetchAll(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}


		}

		public function busca() {
			try{
				self::set_names();
				$texto=trim(htmlspecialchars($_REQUEST['texto']));
				$sql="SELECT * from productos where clave like :texto or nombre like :texto or modelo like :texto or marca like :texto or idProducto like :texto limit 100";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(":texto","%".$texto."%");
				$sth->execute();
				return $sth->fetchAll(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}

		}



}

	if(strlen($ctrl)>0){
		$db = new Tienda();
		if(strlen($function)>0){
			echo $db->$function();
		}
	}
	function moneda($valor){
		return "$ ".number_format( $valor, 2, "." , "," );
	}
	/////////////////////////////////////////////token
	function servicioApi($metodo, $servicio, $json = null, $token = null) {
	    $ch = curl_init('http://187.210.141.12:3001/' . $servicio);
	    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $metodo);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($json), 'x-auth: ' . $token));
	    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
	    $result = curl_exec($ch);

	    //echo curl_error($ch);
	    curl_close($ch); // close cURL handler
	    return json_decode($result);
	}
	function crearNuevoToken() {
	    //Credenciales del cliente para poder consumir el servicio de TOKEN
	    $cliente = 'PAC0736';
	    $email = 'juanluisvitevivanco@hotmail.com';
	    $rfc = 'VIVJ820926GR9';

	    $servicio = 'cliente/token'; //Ruta del servicio para la creacion de un nuevo token
	    $json = json_encode(array('email' => $email, 'cliente' => $cliente, 'rfc' => $rfc));

	    //AQUI SE CONSUME UN SERVICIO POR == METODO POST == y SE RETORNA COMO RESPUESTA
	    return servicioApi('POST', $servicio, $json, null);
	}




?>
