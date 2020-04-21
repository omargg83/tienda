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

			$this->doc="admin/a_imagen/";
			$this->extra="admin/a_imagenextra/";
			$this->banner="admin/a_imagenpagina/";

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
				$arreglo=array();
				if (isset($_REQUEST['pass'])){
					$pass = trim($_REQUEST["pass"]);
					$arreglo+= array('pass'=>md5(trim($pass)));
				}
				if (isset($_REQUEST['nombre'])){
					$nombre=trim(htmlspecialchars($_REQUEST["nombre"]));
					$arreglo+= array('nombre'=>$nombre);
				}
				if (isset($_REQUEST['apellido'])){
					$apellido=trim(htmlspecialchars($_REQUEST["apellido"]));
					$arreglo+= array('apellido'=>$apellido);
				}
				if (isset($_REQUEST['correo'])){
					$correo=trim(htmlspecialchars($_REQUEST["correo"]));
					$arreglo+= array('correo'=>$correo);
				}
				if (isset($_REQUEST['direccion1'])){
					$arreglo+= array('direccion1'=>trim(htmlspecialchars($_REQUEST["direccion1"])));
				}
				if (isset($_REQUEST['direccion2'])){
					$arreglo+= array('direccion2'=>trim(htmlspecialchars($_REQUEST["direccion2"])));
				}
				if (isset($_REQUEST['ciudad'])){
					$arreglo+= array('ciudad'=>trim(htmlspecialchars($_REQUEST["ciudad"])));
				}
				if (isset($_REQUEST['cp'])){
					$arreglo+= array('cp'=>trim(htmlspecialchars($_REQUEST["cp"])));
				}
				if (isset($_REQUEST['pais'])){
					$arreglo+= array('pais'=>trim(htmlspecialchars($_REQUEST["pais"])));
				}
				if (isset($_REQUEST['estado'])){
					$arreglo+= array('estado'=>trim(htmlspecialchars($_REQUEST["estado"])));
				}
				if (isset($_REQUEST['telefono'])){
					$arreglo+= array('telefono'=>trim(htmlspecialchars($_REQUEST["telefono"])));
				}
				$sql="select * from clientes where correo='$correo'";
				$sth = $this->dbh->prepare($sql);
				$sth->execute();
				if($sth->rowCount()==0){
					$sql="SELECT * FROM clientes where id=:id";
					$sth = $this->dbh->prepare($sql);
					$sth->bindValue(":id",$_SESSION['idcliente']);
					$sth->execute();
					$CLAVE=$sth->fetch();
					if($CLAVE){
						$x=$this->update('clientes',array('id'=>$_SESSION['idcliente']), $arreglo);
					}
					else {
						$x=$this->insert('clientes', $arreglo);
					}
					$_SESSION['autoriza_web']=1;
					$_SESSION['correo']=$correo;
					$_SESSION['nombre']=$nombre." ".$apellido;
					$_SESSION['idcliente']=$_SESSION['idcliente'];
					$_SESSION['interno']=1;
					return $x;
				}
				else{
					$arreglo+=array('id'=>0);
					$arreglo+=array('error'=>1);
					$arreglo+=array('terror'=>"Correo electrónico ya registrado");
					return json_encode($arreglo);
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
		public function recuperar(){
			try{
				$mail=trim(htmlspecialchars($_REQUEST['mail']));
				$sql="select * from clientes where correo=:mail";
				$sth_i = $this->dbh->prepare($sql);
				$sth_i->bindValue(":mail",$mail);
				$sth_i->execute();
				if($sth_i->rowCount()>0){
					$resp=$sth_i->fetch(PDO::FETCH_OBJ);
					$pass=$this->genera_random(8);
					$passg=md5(trim($pass));

					$sql="update clientes set pass=:pass where id=:id";
					$sth = $this->dbh->prepare($sql);
					$sth->bindValue(":pass",$passg);
					$sth->bindValue(":id",$resp->id);
					$sth->execute();

					$texto="";
					$texto.="TIC-SHOP";
					$texto.="<br>Nueva contraseña: $pass";
					$texto.="<br>Cambio de contraseña ";
					$texto.=$resp->correo;

					$asunto="Cambio de contraseña";
					return $this->correo($resp->correo, $texto, $asunto);
				}
				else{
					$arreglo=array();
					$arreglo+=array('id'=>0);
					$arreglo+=array('error'=>1);
					$arreglo+=array('terror'=>"El correo no existe");
					return json_encode($arreglo);
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

		public function categorias(){															///////////  HEADER
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
		public function categorias_name($id){
			try{
				self::set_names();
				$sql="select * from categorias where idcategoria='$id'";
				$sth = $this->dbh->prepare($sql);
				$sth->execute();

				return $sth->fetch(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}

		public function n1_productos_marcas($cat,$marca){
			try{
				self::set_names();
				$sql="select productos.* from producto_cat
							left outer join categoria_ct on categoria_ct.id=producto_cat.idcategoria_ct
							left outer join productos on productos.categoria=categoria_ct.categoria
							where producto_cat.idcategoria=$cat and productos.activo=1";
							if(strlen($marca)>0){
								$sql.=" and productos.marca='$marca'";
							}
							$sql.=" group by productos.marca";
				$sth = $this->dbh->prepare($sql);
				$sth->execute();
				return $sth->fetchAll(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function n2_productos_marcas($cat,$marca){
			try{
				self::set_names();
				$sql="select * from productos where categoria='$cat'";
				if(strlen($marca)>0){
					$sql.=" and productos.marca='$marca'";
				}
				$sql.=" and activo=1 group by marca";
				$sth = $this->dbh->prepare($sql);
				$sth->execute();
				return $sth->fetchAll(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function n3_productos_marcas($cat,$marca){
			try{
				self::set_names();
				$sql="select * from productos where subcategoria='$cat'";
				if(strlen($marca)>0){
					$sql.=" and productos.marca='$marca'";
				}
				$sql.=" and activo=1 group by marca";
				$sth = $this->dbh->prepare($sql);
				$sth->execute();
				return $sth->fetchAll(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function n4_productos_marcas($marca){
			try{
				self::set_names();
				$sql="select * from productos where activo=1";
				if(strlen($marca)>0){
					$sql.=" and productos.marca='$marca'";
				}
				$sql.=" group by marca limit 100";
				$sth = $this->dbh->prepare($sql);
				$sth->execute();
				return $sth->fetchAll(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}

		public function cat_categoria_name($cat){									//////////////nivel 2
			try{
				self::set_names();
				$sql="select * from categoria_ct where id='$cat'";
				$sth = $this->dbh->prepare($sql);
				$sth->execute();
				return $sth->fetch(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}

		}
		public function sub_categoria_name($cat){									//////////////nivel 3
			try{
				self::set_names();
				$sql="select * from categoriasub_ct where id='$cat'";
				$sth = $this->dbh->prepare($sql);
				$sth->execute();
				return $sth->fetch(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}

		public function cat_categoriatic($cat,$marca,$tipo){										//////////nivel 1
			try{
				self::set_names();
				$filtro="";

				if(strlen($marca)>0){
					$filtro=" and productos.marca='$marca'";
				}
				if($tipo==1){
					$consulta="and producto_cat.idcategoria='$cat'";
				}
				if($tipo==2){
					$consulta="and categoria='$cat'";
				}

				$sql="select count(productos.id) as total from productos
				left outer join categoria_ct on productos.categoria=categoria_ct.categoria
				left outer join producto_cat on categoria_ct.id=producto_cat.idcategoria_ct
				where productos.activo=1 and productos.existencia>0 $consulta $filtro";
				$sth = $this->dbh->prepare($sql);
				$sth->execute();
				$resp=$sth->fetch(PDO::FETCH_OBJ);
				echo "Total:".$resp->total;

				$sql="select * from productos
				left outer join categoria_ct on productos.categoria=categoria_ct.categoria
				left outer join producto_cat on categoria_ct.id=producto_cat.idcategoria_ct
				where productos.activo=1 and productos.existencia>0 $consulta $filtro";

				$sth = $this->dbh->prepare($sql);
				$sth->execute();
				return $sth->fetchAll(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function productos_general($marca){							////////////////nivel 4 o nivel 0
			try{
				self::set_names();
				$sql="select * from productos where activo=1";
				if(strlen($marca)>0){
					$sql.=" and productos.marca='$marca'";
				}
				$sql.=" limit 100";
				$sth = $this->dbh->prepare($sql);
				$sth->execute();
				return $sth->fetchAll(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}


		public function cat_categoria($cat,$marca){
			try{
				self::set_names();
				$sql="select * from productos where categoria='$cat'";
				if(strlen($marca)>0){
					$sql.=" and productos.marca='$marca'";
				}
				$sql.=" and activo=1";
				$sth = $this->dbh->prepare($sql);
				$sth->execute();
				return $sth->fetchAll(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function sub_categoria($cat,$marca){
			try{
				self::set_names();
				$sql="select * from productos where subcategoria='$cat'";
				if(strlen($marca)>0){
					$sql.=" and productos.marca='$marca'";
				}
				$sql.=" and activo=1";
				$sth = $this->dbh->prepare($sql);
				$sth->execute();
				return $sth->fetchAll(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}

		public function cat_ct($id){															/////////////  HEADER
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
		public function sub_cat($id){															////////////   HEADER
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
				$sql="select * from productos where clave=:id";
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
				$sql="select cliente_carro.id, cliente_carro.cantidad, productos.id, productos.img, productos.nombre, productos.preciof, productos.precio_tipo, productos.precio_tic, productos.envio_costo, productos.envio_tipo, productos.idProducto, productos.clave, productos.numParte, productos.modelo, productos.marca, productos.categoria, productos.descripcion_corta, productos.interno from cliente_carro
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
				$cantidad=$_REQUEST['cantidad'];

				if(isset($_SESSION['autoriza_web']) and $_SESSION['autoriza_web']==1 and strlen($_SESSION['idcliente'])>0){
					$cantidad_carro=0;

					////////////////////////checamos si ya esta en el carrito
					$sql="select * from cliente_carro where idproducto='$id' and idcliente='".$_SESSION['idcliente']."'";
					$sth_i = $this->dbh->prepare($sql);
					$sth_i->execute();
					$contar=$sth_i->rowCount();
					if($contar>0){
						$resp=$sth_i->fetch(PDO::FETCH_OBJ);
						$cantidad_carro=$resp->cantidad;
						$id_carro=$resp->id;
					}
					else{
						$cantidad_carro=0;
					}
					//////////////verificar existencia
					$sql="select * from productos where id='$id'";
					$sth_i = $this->dbh->prepare($sql);
					$sth_i->execute();
					$verifica_exi=$sth_i->fetch(PDO::FETCH_OBJ);
					if($verifica_exi->existencia<($cantidad_carro+$cantidad)){
						$arr=array();
						$arr+=array('error'=>1);
						$arr+=array('terror'=>"Verificar existencias");
						return json_encode($arr);
					}
					/////////////si no esta en el carrito lo ingresamos
					if($contar==0){
						$sql="insert into cliente_carro (idcliente, idproducto, fechaagrega, cantidad) values (:idcliente, :idproducto, :fecha, :cantidad)";
						$sth = $this->dbh->prepare($sql);
						$sth->bindValue(":idcliente",$_SESSION['idcliente']);
						$sth->bindValue(":idproducto",$id);
						$sth->bindValue(":cantidad",$cantidad);
						$sth->bindValue(":fecha",date("Y-m-d H:i:s"));
					}
					else{
					/////////////si ya esta solo se suma
						$sql="update cliente_carro set cantidad=:cantidad where id=:id";
						$sth = $this->dbh->prepare($sql);
						$cantidad=$cantidad+$cantidad_carro;
						$sth->bindValue(":id",$id_carro);
						$sth->bindValue(":cantidad",$cantidad);
					}
					$respx=$sth->execute();
					if($respx){
						$arr=array();
						$arr+=array('error'=>0);
						return json_encode($arr);
					}
					else{
						$arr=array();
						$arr+=array('error'=>1);
						$arr+=array('terror'=>$respx);
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




		public function busca() {
			try{
				self::set_names();
				$texto=trim(htmlspecialchars($_REQUEST['id']));
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
				$entrecalles = trim(htmlspecialchars($_REQUEST["entrecalles"]));
				$colonia = trim(htmlspecialchars($_REQUEST["colonia"]));
				$numero = trim(htmlspecialchars($_REQUEST["numero"]));
				$ciudad = trim(htmlspecialchars($_REQUEST["ciudad"]));
				$cp = trim(htmlspecialchars($_REQUEST["cp"]));
				$pais = trim(htmlspecialchars($_REQUEST["pais"]));
				$estado = trim(htmlspecialchars($_REQUEST["estado"]));
				$telefono = trim(htmlspecialchars($_REQUEST["telefono"]));

				$sql="update clientes set nombre=:nombre, apellido=:apellido, rfc=:rfc, cfdi=:cfdi, direccion1=:direccion1, entrecalles=:entrecalles, colonia=:colonia, numero=:numero, ciudad=:ciudad, cp=:cp, pais=:pais, estado=:estado, telefono=:telefono  where id=:id";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(":nombre",$nombre);
				$sth->bindValue(":apellido",$apellido);
				$sth->bindValue(":rfc",$rfc);
				$sth->bindValue(":cfdi",$cfdi);
				$sth->bindValue(":direccion1",$direccion1);
				$sth->bindValue(":entrecalles",$entrecalles);
				$sth->bindValue(":colonia",$colonia);
				$sth->bindValue(":numero",$numero);
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
				if (isset($_REQUEST['entrecalles'])){
					$arreglo+= array('entrecalles'=>$_REQUEST['entrecalles']);
				}
				if (isset($_REQUEST['numero'])){
					$arreglo+= array('numero'=>$_REQUEST['numero']);
				}
				if (isset($_REQUEST['colonia'])){
					$arreglo+= array('colonia'=>$_REQUEST['colonia']);
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

				$carro=$this->carro_list();
				foreach($carro as $key){

				}

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
				$entrecalles = trim(htmlspecialchars($_REQUEST["entrecalles"]));
				$numero = trim(htmlspecialchars($_REQUEST["numero"]));
				$colonia = trim(htmlspecialchars($_REQUEST["colonia"]));
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

					$sql="update clientes set nombre=:nombre, apellido=:apellido, rfc=:rfc, cfdi=:cfdi, direccion1=:direccion1, entrecalles=:entrecalles, numero=:numero, colonia=:colonia, ciudad=:ciudad, cp=:cp, pais=:pais, estado=:estado, telefono=:telefono, correo=:correo, pass=:pass where id=:id";
					$sth = $this->dbh->prepare($sql);
					$sth->bindValue(":nombre",$nombre);
					$sth->bindValue(":apellido",$apellido);
					$sth->bindValue(":rfc",$rfc);
					$sth->bindValue(":cfdi",$cfdi);
					$sth->bindValue(":direccion1",$direccion1);
					$sth->bindValue(":entrecalles",$entrecalles);
					$sth->bindValue(":numero",$numero);
					$sth->bindValue(":colonia",$colonia);
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
					$arreglo+= array('entrecalles'=>$entrecalles);
					$arreglo+= array('numero'=>$numero);
					$arreglo+= array('ciudad'=>$ciudad);
					$arreglo+= array('colonia'=>$colonia);
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
							$arreglo+= array('idprod'=>$key->id);
							$arreglo+= array('idpedido'=>$pedido->id);
							$arreglo+= array('precio'=>round($preciof,2));
							$arreglo+= array('envio'=>$envio);
							$arreglo+= array('cantidad'=>$key->cantidad);
							$subtotal=round($preciof*$key->cantidad);

							$arreglo+= array('total'=>$subtotal);
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

							$total+=$subtotal;
							$totalEnvio+=$envio;
						}
						$arreglo =array();
						$arreglo+= array('monto'=>$total);
						$arreglo+= array('envio'=>$totalEnvio);
						$gtotal=$total+$totalEnvio;
						$arreglo+= array('total'=>round($gtotal,2));
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

				$sql="select * from pedidos_cupon where codigo='$cupon' and idpedido='$idpedido'";
				$sth_i = $this->dbh->prepare($sql);
				$sth_i->execute();
				if($sth_i->rowCount()>0){
					$arreglo=array();
					$arreglo+=array('id'=>$idpedido);
					$arreglo+=array('error'=>1);
					$arreglo+=array('terror'=>"El cupón ya esta agregado");
					return json_encode($arreglo);
				}


				$sql="SELECT * FROM cupon where codigo=:codigo";
				$sth_i = $this->dbh->prepare($sql);
				$sth_i->bindValue(":codigo",$cupon);
				$sth_i->execute();
				$contar=$sth_i->rowCount();
				if($contar>0){
					$ped=$sth_i->fetch(PDO::FETCH_OBJ);

					$sql="insert into pedidos_cupon (idpedido, idcupon, descuento, codigo, descripcion, tipo, envio) values (:idpedido, :idcupon, :descuento, :codigo, :descripcion, :tipo, :envio)";
					$sth = $this->dbh->prepare($sql);
					$sth->bindValue(":idpedido",$idpedido);
					$sth->bindValue(":idcupon",$ped->id);
					$sth->bindValue(":descuento",$ped->importe);
					$sth->bindValue(":codigo",$ped->codigo);
					$sth->bindValue(":descripcion",$ped->descripcion);
					$sth->bindValue(":tipo",$ped->tipo);
					$sth->bindValue(":envio",$ped->envio);
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
			/*
			$mail->isSMTP();
			$mail->Host = 'localhost';
			$mail->SMTPAuth = false;
			$mail->SMTPAutoTLS = false;
			$mail->Port = 25;
			*/
			/*
			$email->isSMTP();
			$email->SMTPDebug = 1;
			$email->SMTPAuth = true;
			$email->SMTPSecure = 'tls';
			$email->Host = "mail.tic-shop.com.mx";
			$email->Port = 465;
			*/

			$mail->IsSMTP(); // enable SMTP
			//	  $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
		  $mail->SMTPAuth = true; // authentication enabled
		  $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
		  $mail->Host = "mail.tic-shop.com.mx";
		  $mail->Port = 465; // or 587
		  $mail->IsHTML(true);


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
		public function dir_update(){
			try{
				$dir=$_REQUEST['dir_fin'];
				if($dir==0){
					$sql="select * from clientes where id=:id";
					$sth = $this->dbh->prepare($sql);
				}
				else{
					$sql="select * from clientes_direccion where iddireccion=:iddir and idcliente=:id";
					$sth = $this->dbh->prepare($sql);
					$sth->bindValue(":iddir",$dir);
					$sth->bindValue(":id",$_SESSION['idcliente']);
				}
				$sth->bindValue(":id",$_SESSION['idcliente']);
				$sth->execute();
				$resp=$sth->fetch(PDO::FETCH_OBJ);

				$arreglo=array();
				$arreglo+=array('id'=>0);
				$arreglo+=array('error'=>0);
				$arreglo+=array('terror'=>'');
				$arreglo+=array('direccion1'=>$resp->direccion1);
				$arreglo+=array('entrecalles'=>$resp->entrecalles);
				$arreglo+=array('numero'=>$resp->numero);
				$arreglo+=array('colonia'=>$resp->colonia);
				$arreglo+=array('ciudad'=>$resp->ciudad);
				$arreglo+=array('cp'=>$resp->cp);
				$arreglo+=array('pais'=>$resp->pais);
				$arreglo+=array('estado'=>$resp->estado);
				return json_encode($arreglo);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
				$arreglo+=array('id'=>0);
				$arreglo+=array('error'=>1);
				$arreglo+=array('terror'=>'Favor de verificar');
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
