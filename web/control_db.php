<?php
	if (!isset($_SESSION)) { session_start(); }
	if (isset($_REQUEST['function'])){$function=$_REQUEST['function'];}	else{ $function="";}
	if (isset($_REQUEST['ctrl'])){$ctrl=$_REQUEST['ctrl'];}	else{ $ctrl="";}

	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	date_default_timezone_set("America/Mexico_City");

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;


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
			$this->banner="../a_pagina/";

			$sql="select * from ajustes";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			$tmp=$sth->fetch(PDO::FETCH_OBJ);
			$this->cgeneral=$tmp->p_general;
			$this->egeneral=$tmp->c_envio;


			$this->ecorreo=$tmp->correo;
			$this->host=$tmp->host;
			$this->SMTPAuth=$tmp->SMTPAuth;
			$this->Password=$tmp->Password;
			$this->SMTPSecure=$tmp->SMTPSecure;
			$this->Port=$tmp->Port;

		}
		public function set_names(){
			return $this->dbh->query("SET NAMES 'utf8'");
		}

		public function insert($DbTableName, $values = array()){
			$arreglo=array();
			try{
				self::set_names();
				$this->dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

				foreach ($values as $field => $v)
				$ins[] = ':' . $field;

				$ins = implode(',', $ins);
				$fields = implode(',', array_keys($values));
				$sql="INSERT INTO $DbTableName ($fields) VALUES ($ins)";
				$sth = $this->dbh->prepare($sql);
				foreach ($values as $f => $v){
					$sth->bindValue(':' . $f, $v);
				}
				if ($sth->execute()){
					$arreglo+=array('id'=>$this->lastId = $this->dbh->lastInsertId());
					$arreglo+=array('error'=>0);
					$arreglo+=array('terror'=>'');
					$arreglo+=array('param1'=>'');
					$arreglo+=array('param2'=>'');
					$arreglo+=array('param3'=>'');
					return json_encode($arreglo);
				}
			}
			catch(PDOException $e){
				$arreglo+=array('id'=>0);
				$arreglo+=array('error'=>1);
				$arreglo+=array('terror'=>$e->getMessage());
				return json_encode($arreglo);
			}
		}
		public function update($DbTableName, $id = array(), $values = array()){
			$arreglo=array();
			try{
				self::set_names();
				$this->dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
				$x="";
				$idx="";
				foreach ($id as $field => $v){
					$condicion[] = $field.'= :' . $field."_c";
				}
				$condicion = implode(' and ', $condicion);
				foreach ($values as $field => $v){
					$ins[] = $field.'= :' . $field;
				}
				$ins = implode(',', $ins);

				$sql2="update $DbTableName set $ins where $condicion";
				$sth = $this->dbh->prepare($sql2);
				foreach ($values as $f => $v){
					$sth->bindValue(':' . $f, $v);
				}
				foreach ($id as $f => $v){
					if(strlen($idx)==0){
						$idx=$v;
					}
					$sth->bindValue(':' . $f."_c", $v);
				}
				if($sth->execute()){
					$arreglo+=array('id'=>$idx);
					$arreglo+=array('error'=>0);
					$arreglo+=array('terror'=>'');
					$arreglo+=array('param1'=>'');
					$arreglo+=array('param2'=>'');
					$arreglo+=array('param3'=>'');
					return json_encode($arreglo);
				}
			}
			catch(PDOException $e){
				$arreglo+=array('id'=>0);
				$arreglo+=array('error'=>1);
				$arreglo+=array('terror'=>$e->getMessage());
				return json_encode($arreglo);
			}
		}

		public function galleta(){
			try{
				$galleta=$_REQUEST['galleta'];
				$contar=0;
				if(strlen($galleta)>0){
					$sql="SELECT * FROM clientes where galleta=:galleta";
					$sth_i = $this->dbh->prepare($sql);
					$sth_i->bindValue(":galleta",$galleta);
					$sth_i->execute();
					$contar=$sth_i->rowCount();
				}

				if($contar==0){
					$galleta=$this->genera_random();
					$sql="insert into clientes (galleta, fechacreado) values (:galleta, :fechacreado)";
					$sth = $this->dbh->prepare($sql);
					$sth->bindValue(":galleta",$galleta);
					$sth->bindValue(":fechacreado",date("Y-m-d H:i:s"));
					if($sth->execute()){
						$sql="SELECT * FROM clientes where galleta=:galleta";
						$sth_i = $this->dbh->prepare($sql);
						$sth_i->bindValue(":galleta",$galleta);
						$sth_i->execute();
						$contar=$sth_i->rowCount();
					}
				}
				$CLAVE=$sth_i->fetch(PDO::FETCH_OBJ);
				if(strlen($CLAVE->correo)>0){
					$_SESSION['autoriza_web']=1;
					$_SESSION['interno']=1;
					$_SESSION['correo']=$CLAVE->correo;
					$_SESSION['nombre']=$CLAVE->nombre." ".$CLAVE->apellido;
				}
				else{
					$_SESSION['autoriza_web']=1;
					$_SESSION['interno']=0;
					$_SESSION['correo']="";
					$_SESSION['nombre']="";
				}
				$_SESSION['idcliente']=$CLAVE->id;
				return $galleta;
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function genera_random($length = 15) {
    	return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
		}

		public function registro(){
			//Obtenemos los datos del formulario de acceso
			try{
				$nombre = trim(htmlspecialchars($_REQUEST["nombre"]));
				$apellido = trim(htmlspecialchars($_REQUEST["apellido"]));
				$correo = trim(htmlspecialchars($_REQUEST["correo"]));
				$pass = trim(htmlspecialchars($_REQUEST["pass"]));
				$pass2 = trim(htmlspecialchars($_REQUEST["pass2"]));

				$sql="SELECT * FROM clientes where id=:id";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(":id",$_SESSION['idcliente']);
				$sth->execute();
				$CLAVE=$sth->fetch();
				if($CLAVE){
					$passPOST=md5(trim($pass));
					$sql="update clientes set nombre=:nombre, apellido=:apellido, correo=:correo, pass=:pass where id=:id";
					$sth = $this->dbh->prepare($sql);
					$sth->bindValue(":id",$_SESSION['idcliente']);
					$sth->bindValue(":nombre",$nombre);
					$sth->bindValue(":apellido",$apellido);
					$sth->bindValue(":correo",$correo);
					$sth->bindValue(":pass",$passPOST);
					if($sth->execute()){
						$_SESSION['autoriza_web']=1;
						$_SESSION['correo']=$correo;
						$_SESSION['nombre']=$nombre." ".$apellido;
						$_SESSION['idcliente']=$_SESSION['idcliente'];
						$_SESSION['interno']=1;
					}
				}
				else {
					$sql="insert into clientes (nombre, apellido, correo) values (:nombre, :apellido, :correo, :pass)";
					$sth = $this->dbh->prepare($sql);
					$sth->bindValue(":nombre",$nombre);
					$sth->bindValue(":apellido",$apellido);
					$sth->bindValue(":correo",$correo);
					$sth->bindValue(":pass",$passPOST);
					$sth->execute();
					return "No existe".$_SESSION['idcliente'];
				}
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
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
						$_SESSION['nombre']=$CLAVE['nombre']." ".$CLAVE['apellido'];
						$_SESSION['interno']=1;

						$galleta=$this->genera_random();
						$sql="update clientes set galleta=:galleta, fechacreado=:fechacreado where id=:id";
						$sth = $this->dbh->prepare($sql);
						$sth->bindValue(":galleta",$galleta);
						$sth->bindValue(":fechacreado",date("Y-m-d H:i:s"));
						$sth->bindValue(":id",$CLAVE['id']);
						$sth->execute();


						$arr=array();
						$arr+=array('acceso'=>1);
						$arr+=array('galleta'=>$galleta);
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
			$_SESSION['interno']=0;
			$_SESSION['autoriza_web']=0;
			$_SESSION['correo']="";
			$_SESSION['nombre']="";
		}
		public function categorias(){
			try{
				self::set_names();
				$sql="select * from categorias order by orden asc";
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
				$sql="SELECT categoria_ct.id, categoria_ct.categoria, categoria_ct.heredado from producto_cat left outer join categoria_ct on categoria_ct.id=producto_cat.idcategoria_ct where producto_cat.idcategoria=:id";
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
		public function estrellas($id){
			try{
				self::set_names();
				$sql="select producto_estrella.*, clientes.nombre from producto_estrella
				left outer join clientes on clientes.id=producto_estrella.idcliente
				where idproducto=:id and producto_estrella.publico=1";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(':id', "$id");
				$sth->execute();
				return $sth->fetchAll(PDO::FETCH_OBJ);
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
		public function producto_exist($id,$tipo=0){
			try{
				self::set_names();
				if($tipo==0){
					$sql="select * from producto_exist where id=$id";
				}
				if($tipo==1){
					$sql="select existencia as total, 'Pachuca' as alma from producto_exist where id=$id and almacen='PAC' UNION
						select sum(existencia) as total, 'Otros' as alma from producto_exist where id=$id and almacen!='PAC' group by idProducto";
				}
				if($tipo==2){
					$sql="select sum(existencia) as existencia, almacen from producto_exist where id=$id";
				}
				$sth = $this->dbh->prepare($sql);
				$sth->execute();
				return $sth->fetchAll(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function producto_espe($id){
			try{
				self::set_names();
				$sql="select * from producto_espe where id=:id";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(':id', "$id");
				$sth->execute();
				return $sth->fetchAll(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function almacen_busca($clave){
			try{
				self::set_names();
				$sql="select * from almacen where homoclave=:id";
				echo $sql;
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(':id', "$clave");
				$sth->execute();
				return $sth->fetch(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		///////////////////////Productos destacados
		public function productos_destacados(){
			try{
				self::set_names();
				$sql="SELECT * from productos where cb_destacados=1 and activo=1 and existencia>0";
				$sth = $this->dbh->prepare($sql);
				$sth->execute();
				return $sth->fetchAll(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		/////////////////////ofertas de la semana
		public function productos_semana(){
			try{
				self::set_names();
				$sql="SELECT * from productos where cb_prodsemana=1 and activo=1 and existencia>0";
				$sth = $this->dbh->prepare($sql);
				$sth->execute();
				return $sth->fetchAll(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}

		////////////////////productos ofertas
		public function ofertas(){
			try{
				self::set_names();
				$sql="SELECT * from productos where cb_ofertasemana=1 and activo=1 and existencia>0";
				$sth = $this->dbh->prepare($sql);
				$sth->execute();
				return $sth->fetchAll(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function relacionados($subcategoria){
			try{
				self::set_names();
				$sql="SELECT * from productos where subcategoria='$subcategoria' and activo=1 and existencia>0 limit 20";
				$sth = $this->dbh->prepare($sql);
				$sth->execute();
				return $sth->fetchAll(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}

		public function carro_list(){
			try{
				self::set_names();
				$sql="select cliente_carro.id, productos.img, productos.nombre, productos.preciof, productos.precio_tipo, productos.precio_tic, productos.envio_costo, productos.envio_tipo, productos.idProducto, productos.clave, productos.numParte, productos.modelo, productos.marca, productos.categoria, productos.descripcion_corta, productos.interno from cliente_carro
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
		public function borra_carrito(){
			try{
				self::set_names();
				$id=$_REQUEST['id'];
				$sql="delete from cliente_carro where id=:id";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(":id",$id);
				$a=$sth->execute();
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
					$res2=$this->wish_sum();
					return $res2->contar;
				}
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function borra_wish(){
			try{
				self::set_names();
				$id=$_REQUEST['id'];
				$sql="delete from cliente_wish where id=:id";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(":id",$id);
				$a=$sth->execute();
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function wish_list(){
			try{
				self::set_names();
				$sql="select productos.id, productos.img, productos.nombre, productos.preciof, cliente_wish.id as cliid, productos.precio_tipo, productos.envio_tipo, productos.precio_tic from cliente_wish
				left outer join productos on productos.id=cliente_wish.idproducto
				where cliente_wish.idcliente=:id";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(":id",$_SESSION['idcliente']);
				$sth->execute();
				return $sth->fetchAll(PDO::FETCH_OBJ);
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
		public function productos_general(){
			try{
				self::set_names();
				$sql="select * from productos where activo=1 limit 100";
				$sth = $this->dbh->prepare($sql);
				$sth->execute();
				return $sth->fetchAll(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function productos_marcas($tipo){
			try{
				self::set_names();
				$sql="SELECT * FROM productos group by marca limit 20";
				$sth = $this->dbh->prepare($sql);
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
				$sql="SELECT * from productos where activo=1 and existencia>0 and
				(clave like :texto or nombre like :texto or modelo like :texto or marca like :texto or idProducto like :texto) limit 100";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(":texto","%".$texto."%");
				$sth->execute();
				return $sth->fetchAll(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}

		}

		public function datos(){
			try{
				self::set_names();
				$sql="SELECT * FROM clientes where id=:id";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(":id",$_SESSION['idcliente']);
				$sth->execute();
				return $sth->fetch(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function datos_update(){
			try{
				$nombre = trim(htmlspecialchars($_REQUEST["nombre"]));
				$apellido = trim(htmlspecialchars($_REQUEST["apellido"]));
				$rfc = trim(htmlspecialchars($_REQUEST["rfc"]));
				$cfdi = trim(htmlspecialchars($_REQUEST["cfdi"]));
				$direccion1 = trim(htmlspecialchars($_REQUEST["direccion1"]));
				$direccion2 = trim(htmlspecialchars($_REQUEST["direccion2"]));
				$ciudad = trim(htmlspecialchars($_REQUEST["ciudad"]));
				$cp = trim(htmlspecialchars($_REQUEST["cp"]));
				$pais = trim(htmlspecialchars($_REQUEST["pais"]));
				$pais = trim(htmlspecialchars($_REQUEST["pais"]));
				$estado = trim(htmlspecialchars($_REQUEST["estado"]));
				$telefono = trim(htmlspecialchars($_REQUEST["telefono"]));

				$sql="update clientes set nombre=:nombre, apellido=:apellido, rfc=:rfc, cfdi=:cfdi, direccion1=:direccion1, direccion2=:direccion2, ciudad=:ciudad, cp=:cp, pais=:pais, estado=:estado, telefono=:telefono  where id=:id";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(":nombre",$nombre);
				$sth->bindValue(":apellido",$apellido);
				$sth->bindValue(":rfc",$rfc);
				$sth->bindValue(":cfdi",$cfdi);
				$sth->bindValue(":direccion1",$direccion1);
				$sth->bindValue(":direccion2",$direccion2);
				$sth->bindValue(":ciudad",$ciudad);
				$sth->bindValue(":cp",$cp);
				$sth->bindValue(":pais",$pais);
				$sth->bindValue(":estado",$estado);
				$sth->bindValue(":telefono",$telefono);
				$sth->bindValue(":id",$_SESSION['idcliente']);

				if($sth->execute()){
					$arr=array();
					$arr+=array('error'=>0);
					$arr+=array('terror'=>"");
					return json_encode($arr);
				}
				else{
					$arr=array();
					$arr+=array('error'=>1);
					$arr+=array('terror'=>"");
					return json_encode($arr);
				}
			}
			catch(PDOException $e){
				$arr=array();
				$arr+=array('error'=>1);
				$arr+=array('terror'=>$e->getMessage());
				return json_encode($arr);
			}

		}

		public function direcciones(){
			try{
				self::set_names();
				$sql="SELECT * from clientes_direccion where idcliente=:id";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(':id',$_SESSION['idcliente']);
				$sth->execute();
				return $sth->fetchAll();
			}
			catch(PDOException $e){
				return "Database access FAILED! ".$e->getMessage();
			}
		}
		public function direccion_editar($id){
			try{
				self::set_names();
				$sql="select * from clientes_direccion where iddireccion=:id and idcliente=:idcliente";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(':id', "$id");
				$sth->bindValue(':idcliente', $_SESSION['idcliente']);
				$sth->execute();
				return $sth->fetch(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}

		}
		public function guardar_direccion(){
			try{
				self::set_names();
				$id=$_REQUEST['id'];
				$arreglo =array();
				$arreglo = array('idcliente'=>$_SESSION['idcliente']);


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

		public function ajustes_editar(){
			try{
				self::set_names();
				$sql="select * from ajustes where id=1";
				$sth = $this->dbh->prepare($sql);
				$sth->execute();
				return $sth->fetch(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}

		public function pedido_generar(){
			try{
				if(isset($_REQUEST["factura"])){
					$factura=1;
				}
				else{
					$factura=0;
				}
				if (isset($_REQUEST["pass"]) and strlen($_REQUEST["pass"])){
					if(trim($_REQUEST["pass"])!=trim($_REQUEST["pass2"])){
						$arreglo=array();
						$arreglo+=array('id'=>0);
						$arreglo+=array('error'=>1);
						$arreglo+=array('terror'=>"La contraseña no coincide");
						return json_encode($arreglo);
					}
				}

				//////////////////////////////////actualiza datos
				$nombre = trim(htmlspecialchars($_REQUEST["nombre"]));
				$apellido = trim(htmlspecialchars($_REQUEST["apellido"]));
				$rfc = trim(htmlspecialchars($_REQUEST["rfc"]));
				$cfdi = trim(htmlspecialchars($_REQUEST["cfdi"]));
				$direccion1 = trim(htmlspecialchars($_REQUEST["direccion1"]));
				$direccion2 = trim(htmlspecialchars($_REQUEST["direccion2"]));
				$ciudad = trim(htmlspecialchars($_REQUEST["ciudad"]));
				$cp = trim(htmlspecialchars($_REQUEST["cp"]));
				$pais = trim(htmlspecialchars($_REQUEST["pais"]));
				$pais = trim(htmlspecialchars($_REQUEST["pais"]));
				$estado = trim(htmlspecialchars($_REQUEST["estado"]));
				$telefono = trim(htmlspecialchars($_REQUEST["telefono"]));
				$correo = trim(htmlspecialchars($_REQUEST["correo"]));
				$notas = trim(htmlspecialchars($_REQUEST["notas"]));
				if(isset($_REQUEST["pass"])){
					$pass = trim($_REQUEST["pass"]);
				}

				if(strlen($_SESSION['correo'])==0){
					$sql="select * from clientes where correo='$correo'";
					$sth_i = $this->dbh->prepare($sql);
					$sth_i->execute();
					if($sth_i->rowCount()>0){
						$arreglo=array();
						$arreglo+=array('id'=>0);
						$arreglo+=array('error'=>1);
						$arreglo+=array('terror'=>"Ya existe usuario registrado con el correo seleccionado");
						return json_encode($arreglo);
					}

					$sql="update clientes set nombre=:nombre, apellido=:apellido, rfc=:rfc, cfdi=:cfdi, direccion1=:direccion1, direccion2=:direccion2, ciudad=:ciudad, cp=:cp, pais=:pais, estado=:estado, telefono=:telefono, correo=:correo, pass=:pass where id=:id";
					$sth = $this->dbh->prepare($sql);
					$sth->bindValue(":nombre",$nombre);
					$sth->bindValue(":apellido",$apellido);
					$sth->bindValue(":rfc",$rfc);
					$sth->bindValue(":cfdi",$cfdi);
					$sth->bindValue(":direccion1",$direccion1);
					$sth->bindValue(":direccion2",$direccion2);
					$sth->bindValue(":ciudad",$ciudad);
					$sth->bindValue(":cp",$cp);
					$sth->bindValue(":pais",$pais);
					$sth->bindValue(":estado",$estado);
					$sth->bindValue(":telefono",$telefono);
					$sth->bindValue(":correo",$correo);
					$sth->bindValue(":id",$_SESSION['idcliente']);
					$sth->bindValue(":pass",md5($pass));
					if($sth->execute()){
						$_SESSION['autoriza_web']=1;
						$_SESSION['interno']=1;
						$_SESSION['correo']=$correo;
						$_SESSION['nombre']=$nombre." ".$apellido;
					}
				}

				///////////////////////////se genera el pedido
				try{
					self::set_names();
					$id=0;
					$arreglo =array();
					$arreglo+=array('fecha'=>date("Y-m-d H:i:s"));
					$arreglo+= array('estatus'=>"EN ESPERA");
					$arreglo+= array('nombre'=>$nombre);
					$arreglo+= array('apellido'=>$apellido);
					$arreglo+= array('rfc'=>$rfc);
					$arreglo+= array('cfdi'=>$cfdi);
					$arreglo+= array('direccion1'=>$direccion1);
					$arreglo+= array('direccion2'=>$direccion2);
					$arreglo+= array('ciudad'=>$ciudad);
					$arreglo+= array('cp'=>$cp);
					$arreglo+= array('pais'=>$pais);
					$arreglo+= array('telefono'=>$telefono);
					$arreglo+= array('estado'=>$estado);
					$arreglo+= array('correo'=>$correo);
					$arreglo+= array('idcliente'=>$_SESSION['idcliente']);
					$arreglo+= array('factura'=>$factura);
					$arreglo+= array('notas'=>$notas);
					$x="";
					if($id==0){
						$x=$this->insert('pedidos', $arreglo);
						$pedido=json_decode($x);

						$carro=$this->carro_list();
						$totalEnvio=0;
						$total=0;
						//////////////////////////////////////////////////////////////////////////
						foreach($carro as $key){
							////////////precio
							$preciof=0;
							$enviof=0;
							if($key->precio_tipo==0){
								$preciof=$key->preciof;
							}
							if($key->precio_tipo==1){
								$p_total=$key->preciof+(($key->preciof*$this->cgeneral)/100);
								$preciof=$p_total;
							}
							if($key->precio_tipo==2){
								$preciof=$key->precio_tic;
							}
							if($key->precio_tipo==3){
								$p_total=$key->precio_tic+(($key->precio_tic*$this->cgeneral)/100);
								$preciof=$p_total;
							}
							//////////////////envio

							if($key->envio_tipo==0){
								$envio=$this->egeneral;
							}
							if($key->envio_tipo==1){
								$envio=$key->envio_costo;
							}

							$arreglo =array();
							$arreglo+= array('idprod'=>$key->idProducto);
							$arreglo+= array('idpedido'=>$pedido->id);
							$arreglo+= array('precio'=>$preciof);
							$arreglo+= array('envio'=>$envio);
							$arreglo+= array('cantidad'=>1);
							$arreglo+= array('total'=>$preciof);
							$arreglo+= array('idProducto'=>$key->idProducto);
							$arreglo+= array('clave'=>$key->clave);
							$arreglo+= array('numParte'=>$key->numParte);
							$arreglo+= array('nombre'=>$key->nombre);
							$arreglo+= array('modelo'=>$key->modelo);
							$arreglo+= array('marca'=>$key->marca);
							$arreglo+= array('categoria'=>$key->categoria);
							$arreglo+= array('descripcion_corta'=>$key->descripcion_corta);

							if($key->interno==1){
								$arreglo+= array('tipo'=>"TIC");
							}
							else{
								$arreglo+= array('tipo'=>"CT");
							}
							$this->insert('pedidos_prod', $arreglo);

							$total+=$preciof;
							$totalEnvio+=$envio;
						}
						$arreglo =array();
						$arreglo+= array('monto'=>$total);
						$arreglo+= array('envio'=>$totalEnvio);
						$gtotal=$total+$totalEnvio;
						$arreglo+= array('total'=>$gtotal);
						$this->update('pedidos',array('id'=>$pedido->id), $arreglo);
						//////////////////////////////////////////////////////////////////////////
					}
					else{
						$x=$this->update('pedidos',array('id'=>$id), $arreglo);
					}

					$arreglo=array();
					$arreglo+=array('id'=>$pedido->id);
					$arreglo+=array('error'=>0);
					$arreglo+=array('terror'=>0);
					$arreglo+=array('param1'=>0);
					$arreglo+=array('param2'=>"");
					$arreglo+=array('param3'=>"");
					return json_encode($arreglo);
				}
				catch(PDOException $e){
					return "Database access FAILED!".$e->getMessage();
				}
			}
			catch(PDOException $e){
				$arr=array();
				$arr+=array('error'=>1);
				$arr+=array('terror'=>$e->getMessage());
				return json_encode($arr);
			}
		}

		public function contacto(){
			try{
				self::set_names();
				$arreglo =array();
				if (isset($_REQUEST['nombre'])){
					$arreglo+= array('nombre'=>$_REQUEST['nombre']);
				}
				if (isset($_REQUEST['correo'])){
					$arreglo+= array('correo'=>$_REQUEST['correo']);
				}
				if (isset($_REQUEST['telefono'])){
					$arreglo+= array('telefono'=>$_REQUEST['telefono']);
				}
				if (isset($_REQUEST['mensaje'])){
					$arreglo+= array('mensaje'=>$_REQUEST['mensaje']);
				}
				$arreglo+= array('fecha'=>date("Y-m-d H:i:s"));
				return $this->insert('contacto', $arreglo);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function pedidos_lista(){
			try{
				self::set_names();
				$sql="select * from pedidos where idcliente=:id";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(":id",$_SESSION['idcliente']);
				$sth->execute();
				return $sth->fetchAll(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function pedido_ver($id){
			try{
				self::set_names();
				$sql="select * from pedidos where id=:id";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(":id",$id);
				$sth->execute();
				return $sth->fetch(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function datos_pedido($id){
			try{
				self::set_names();
				$sql="select * from pedidos_prod where idpedido=:id";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(":id",$id);
				$sth->execute();
				return $sth->fetchAll(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}

		public function baner_lista(){
			try{
				self::set_names();
				$sql="select * from baner";
				$sth = $this->dbh->prepare($sql);
				$sth->execute();
				return $sth->fetchAll(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function baner2($id){
			try{
				self::set_names();
				$sql="select * from baner2 where id=:id";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(':id', "$id");
				$sth->execute();
				return $sth->fetch(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}

		public function estrella(){
			try{
				self::set_names();

				$estrella=$_REQUEST['estrella'];
				$idproducto=$_REQUEST['idproducto'];
				$texto=trim(htmlspecialchars($_REQUEST['texto']));

				$sql="insert into producto_estrella (idcliente, idproducto, estrella, texto, fecha) values (:idcliente, :idproducto, :estrella, :texto, :fecha)";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(":idcliente",$_SESSION['idcliente']);
				$sth->bindValue(":idproducto",$idproducto);
				$sth->bindValue(":estrella",$estrella);
				$sth->bindValue(":texto",$texto);
				$sth->bindValue(":fecha",date("Y-m-d H:i:s"));
				return $sth->execute();
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function cupon_busca(){
			try{
				self::set_names();
				$idpedido=$_REQUEST['idpedido'];
				$cupon=trim(htmlspecialchars($_REQUEST['cupon']));

				$sql="select * from pedidos_cupon where codigo='$cupon'";
				$sth_i = $this->dbh->prepare($sql);
				$sth_i->execute();
				if($sth_i->rowCount()>0){
					$arreglo=array();
					$arreglo+=array('id'=>$idpedido);
					$arreglo+=array('error'=>1);
					$arreglo+=array('terror'=>"El cupón ya fue utilizado");
					return json_encode($arreglo);
				}


				$sql="SELECT * FROM cupon where codigo=:codigo";
				$sth_i = $this->dbh->prepare($sql);
				$sth_i->bindValue(":codigo",$cupon);
				$sth_i->execute();
				$contar=$sth_i->rowCount();
				if($contar>0){
					$ped=$sth_i->fetch(PDO::FETCH_OBJ);

					$sql="insert into pedidos_cupon (idpedido, idcupon, descuento, codigo, descripcion) values (:idpedido, :idcupon, :descuento, :codigo, :descripcion)";
					$sth = $this->dbh->prepare($sql);
					$sth->bindValue(":idpedido",$idpedido);
					$sth->bindValue(":idcupon",$ped->id);
					$sth->bindValue(":descuento",$ped->importe);
					$sth->bindValue(":codigo",$ped->codigo);
					$sth->bindValue(":descripcion",$ped->descripcion);
					if($sth->execute()){
						$arreglo=array();
						$arreglo+=array('id'=>$idpedido);
						$arreglo+=array('error'=>0);
						$arreglo+=array('terror'=>"");
						return json_encode($arreglo);
					}
					else{
						$arreglo=array();
						$arreglo+=array('id'=>$idpedido);
						$arreglo+=array('error'=>1);
						$arreglo+=array('terror'=>$sth->errorInfo());
						return json_encode($arreglo);
					}
				}
				else{
					$arreglo=array();
					$arreglo+=array('id'=>$idpedido);
					$arreglo+=array('error'=>1);
					$arreglo+=array('terror'=>"Cupón no valido");
					return json_encode($arreglo);
				}
			}
			catch(PDOException $e){
				$arreglo=array();
				$arreglo+=array('id'=>$idpedido);
				$arreglo+=array('error'=>1);
				$arreglo+=array('terror'=>$e->getMessage());
				return json_encode($arreglo);
			}
		}
		public function elimina_cupon(){
			try{
				self::set_names();
				$id=$_REQUEST['id'];
				$sql="delete from pedidos_cupon where id=:id";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(":id",$id);
				$a=$sth->execute();
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function pedido_cupones($id){
			try{
				self::set_names();
				$sql="select * from pedidos_cupon where idpedido=:id";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(":id",$id);
				$sth->execute();
				return $sth->fetchAll(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function mayoreo(){
			$clave=$_REQUEST['clave'];
			$producto=$_REQUEST['producto'];
			$descripcion_corta=$_REQUEST['descripcion_corta'];
			$correo=$_REQUEST['correo'];
			$nombre=$_REQUEST['nombre'];
			$cantidad=$_REQUEST['cantidad'];
			$comentario=$_REQUEST['comentario'];
			$x="";

			$texto="<h4><b>Cotización de productos al mayoreo</b></h4>";
			$texto.="<br>Producto:";
			$texto.="<br><b>$clave</b> - $producto<br>";
			$texto.="$descripcion_corta";
			$texto.="<br><br>";
			$texto.="<br>Correo: $correo";
			$texto.="<br>Nombre: $nombre -";
			$texto.="<br>Cantidad: $cantidad";
			$texto.="<br>Observaciones: $comentario";
			$asunto="Cotización de Mayoreo";
			return $this->correo($correo, $texto, $asunto);
		}

		public function correo($correo, $texto,$asunto){
			/////////////////////////////////////////////Correo
			require 'vendor/autoload.php';
			$mail = new PHPMailer;

			$mail->isSMTP();
			$mail->Host = 'localhost';
			$mail->SMTPAuth = false;
			$mail->SMTPAutoTLS = false;
			$mail->Port = 25;

			$mail->Username = $this->ecorreo;
			$mail->Password = $this->Password;
			$mail->setFrom("admin@tic-shop.com.mx", 'TIC-SHOP');
			$mail->addAddress($correo);
			$mail->addCC("admin@tic-shop.com.mx");
			$mail->CharSet = 'UTF-8';
			//Set the subject line
			$mail->Subject = $asunto;

			$mail->msgHTML($texto);

			$mail->AltBody = $asunto;
			$arreglo=array();
			//send the message, check for errors
			if (!$mail->send()) {
				$arreglo+=array('id'=>0);
				$arreglo+=array('error'=>1);
				$arreglo+=array('terror'=>$mail->ErrorInfo);
				$arreglo+=array('param1'=>'');
				$arreglo+=array('param2'=>'');
				$arreglo+=array('param3'=>'');
				return json_encode($arreglo);
			} else {
				$arreglo+=array('id'=>0);
				$arreglo+=array('error'=>0);
				$arreglo+=array('terror'=>'');
				$arreglo+=array('param1'=>'');
				$arreglo+=array('param2'=>'');
				$arreglo+=array('param3'=>'');
				return json_encode($arreglo);
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
