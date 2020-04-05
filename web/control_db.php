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

			$sql="select * from ajustes";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			$tmp=$sth->fetch(PDO::FETCH_OBJ);
			$this->cgeneral=$tmp->p_general;
			$this->egeneral=$tmp->c_envio;
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
				if(strlen($CLAVE['correo'])>0){
					$_SESSION['autoriza_web']=1;
					$_SESSION['interno']=1;
					$_SESSION['correo']=$CLAVE['correo'];
				}
				else{
					$_SESSION['autoriza_web']=1;
					$_SESSION['interno']=0;
					$_SESSION['correo']="";
				}
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
			$_SESSION['autoriza_web']=0;
			$_SESSION['correo']="";
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

		public function carro_list(){
			try{
				self::set_names();
				$sql="select cliente_carro.id, productos.img, productos.nombre, productos.preciof, productos.precio_tipo, productos.precio_tic, productos.envio_costo, productos.envio_tipo from cliente_carro
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
				$sql="select productos.id, productos.img, productos.nombre, productos.preciof, cliente_wish.id as cliid from cliente_wish
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

		public function busca() {
			try{
				self::set_names();
				$texto=trim(htmlspecialchars($_REQUEST['texto']));
				$sql="SELECT * from productos where activo=1 and
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
