<?php @session_start();
	if (isset($_REQUEST['function'])){$function=clean_var($_REQUEST['function']);}else{ $function=""; }
	if (isset($_REQUEST['ctrl'])){$ctrl=clean_var($_REQUEST['ctrl']);} else{ $ctrl=""; }

	ini_set('include_path', 'tienda/');

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;

	class Tienda{
		public function __construct(){
			try{
				date_default_timezone_set("America/Mexico_City");
				/*
				$mysqluser="ticshopc_admin";
				$mysqlpass="admin123$%";
				$servidor ="tic-shop.com.mx";
				$bdd="ticshopc_tienda";
				*/

				$mysqluser="root";
				$mysqlpass="root";
				$servidor ="localhost";
				$bdd="ticshopc_tienda";

				$this->dbh = new PDO("mysql:host=$servidor;dbname=$bdd", $mysqluser, $mysqlpass);
				$this->dbh->query("SET NAMES 'utf8'");

				$this->doc="TIC_CONTROL/a_imagen/";
				$this->extra="TIC_CONTROL/a_imagenextra/";
				$this->banner="TIC_CONTROL/a_imagenpagina/";

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

				$galleta="";
				$contar=0;
				if(isset($_COOKIE["tickshop"])){
					$galleta=trim($_COOKIE["tickshop"]);
					$sql="SELECT * FROM clientes where galleta=:galleta";
					$sth_i = $this->dbh->prepare($sql);
					$sth_i->bindValue(":galleta",$galleta);
					$sth_i->execute();
					$contar=$sth_i->rowCount();
				}

				if($contar==1){
					$CLAVE=$sth_i->fetch(PDO::FETCH_OBJ);
					$_SESSION['autoriza_web']=1;
					$_SESSION['interno']=1;
					$_SESSION['correo']=$CLAVE->correo;
					$_SESSION['nombre']=$CLAVE->nombre." ".$CLAVE->apellido;
					$_SESSION['idcliente']=$CLAVE->id;
					$_SESSION['gt']=$galleta;
				}
				else{
					$_SESSION['autoriza_web']=1;
					$_SESSION['interno']=0;
					$_SESSION['correo']="";
					$_SESSION['nombre']="";
					$_SESSION['idcliente']=0;

					$azr=$this->genera_random(8);
					$ip=getRealIP();
					$clave=md5($azr."tic%cookie_$%&/()=".$ip);
					$clave=hash("sha512",$clave);
					$_SESSION['gt']=$clave;

					$secure=false;
					$domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
					if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
						$secure=true;
					}
					setcookie("tickshop",$clave, strtotime( '+15 days' ),"/", $domain, $secure, true);
				}
			}
			catch(PDOException $e){
				return "Database access FAILED!";
			}
		}

		public function insert($DbTableName, $values = array()){
			$arreglo=array();
			try{
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

		public function genera_random($length = 15) {
    	return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
		}
		public function registro(){
			//Obtenemos los datos del formulario de acceso
			try{
				$arreglo=array();
				if (isset($_REQUEST['pass'])){
					$pass = trim($_REQUEST["pass"]);
					$encriptx=md5("tic%pika_$%&/()=").md5(trim($pass));
					$passPOST=hash("sha512",$encriptx);
					$arreglo+= array('pass'=>$passPOST);
				}
				if (isset($_REQUEST['nombre'])){
					$nombre=clean_var($_REQUEST["nombre"]);
					$arreglo+= array('nombre'=>$nombre);
				}
				if (isset($_REQUEST['apellido'])){
					$apellido=clean_var($_REQUEST["apellido"]);
					$arreglo+= array('apellido'=>$apellido);
				}
				if (isset($_REQUEST['correo'])){
					$correo=clean_var($_REQUEST["correo"]);
					$arreglo+= array('correo'=>$correo);
				}
				if (isset($_REQUEST['direccion1'])){
					$arreglo+= array('direccion1'=>clean_var($_REQUEST["direccion1"]));
				}
				if (isset($_REQUEST['entrecalles'])){
					$arreglo+= array('entrecalles'=>clean_var($_REQUEST["entrecalles"]));
				}
				if (isset($_REQUEST['colonia'])){
					$arreglo+= array('colonia'=>clean_var($_REQUEST["colonia"]));
				}
				if (isset($_REQUEST['numero'])){
					$arreglo+= array('numero'=>clean_var($_REQUEST["numero"]));
				}
				if (isset($_REQUEST['ciudad'])){
					$arreglo+= array('ciudad'=>clean_var($_REQUEST["ciudad"]));
				}
				if (isset($_REQUEST['cp'])){
					$arreglo+= array('cp'=>clean_var($_REQUEST["cp"]));
				}
				if (isset($_REQUEST['pais'])){
					$arreglo+= array('pais'=>clean_var($_REQUEST["pais"]));
				}
				if (isset($_REQUEST['estado'])){
					$arreglo+= array('estado'=>clean_var($_REQUEST["estado"]));
				}
				if (isset($_REQUEST['telefono'])){
					$arreglo+= array('telefono'=>clean_var($_REQUEST["telefono"]));
				}

				$sql="select * from clientes where correo='$correo'";
				$sth = $this->dbh->prepare($sql);
				$sth->execute();
				if($sth->rowCount()>0){
					$arreglo+=array('id'=>0);
					$arreglo+=array('error'=>1);
					$arreglo+=array('terror'=>"Correo electrónico ya registrado");
					return json_encode($arreglo);
				}

				$sql="SELECT * FROM clientes where id=:id and galleta=:galleta";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(":id",$_SESSION['idcliente']);
				$sth->bindValue(":galleta",$_SESSION['gt']);
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
				$_SESSION['interno']=1;
				return $x;
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function recuperar(){
			try{
				$mail=clean_var($_REQUEST['mail']);

				$sql="select * from clientes where correo=:mail";
				$sth_i = $this->dbh->prepare($sql);
				$sth_i->bindValue(":mail",$mail);
				$sth_i->execute();
				if($sth_i->rowCount()>0){
					$resp=$sth_i->fetch(PDO::FETCH_OBJ);

					$pass=$this->genera_random(8);
					$encriptx=md5("tic%pika_$%&/()=").md5(trim($pass));
					$passg=hash("sha512",$encriptx);

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

			$NewDate=Date('y:m:d', strtotime('-30 days'));
			$sql="delete from clientes where correo is null and fechacreado<:fecha";
			$sth1 = $this->dbh->prepare($sql);
			$sth1->bindValue(":fecha",$NewDate);
			$sth1->execute();

			$sql="delete from cliente_carro where fechaagrega<:fecha";
			$sth1 = $this->dbh->prepare($sql);
			$sth1->bindValue(":fecha",$NewDate);
			$sth1->execute();

			$sql="delete from cliente_wish where fechaagrega<:fecha";
			$sth1 = $this->dbh->prepare($sql);
			$sth1->bindValue(":fecha",$NewDate);
			$sth1->execute();

			$sql="update clientes set galleta='' where galleta=:galleta";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(":galleta",$_SESSION['gt']);
			$sth->execute();
			$_SESSION['interno']=0;
			$_SESSION['autoriza_web']=0;
			$_SESSION['correo']="";
			$_SESSION['nombre']="";
		}

		public function categorias(){															///////////  HEADER
			try{

				$sql="select * from categorias order by orden asc";
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

		public function cfdi(){
			try{

				$sql="select * from cfdi";
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

				$sql="select * from categorias where idcategoria='$id'";
				$sth = $this->dbh->prepare($sql);
				$sth->execute();

				return $sth->fetch(PDO::FETCH_OBJ);
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function cat_categoria_name($cat){									//////////////nivel 2
			try{

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

				$sql="select * from categoriasub_ct where id='$cat'";
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

		public function producto_ver($id){
			try{

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
				if(isset($_SESSION['idcliente'])){
					$sql="select cliente_carro.id, cliente_carro.cantidad, productos.id, productos.img, productos.nombre, productos.preciof, productos.precio_tipo, productos.precio_tic, productos.envio_costo, productos.envio_tipo, productos.idProducto, productos.clave, productos.numParte, productos.modelo, productos.marca, productos.categoria, productos.descripcion_corta, productos.interno from cliente_carro
					left outer join productos on productos.id=cliente_carro.idproducto
					where cliente_carro.idcliente=:id";
					$sth = $this->dbh->prepare($sql);
					$sth->bindValue(":id",$_SESSION['idcliente']);
					$sth->execute();
					return $sth->fetchAll(PDO::FETCH_OBJ);
				}
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function carrito(){
			try{
				$id=$_REQUEST['id'];
				$cantidad=$_REQUEST['cantidad'];

				if($_SESSION['idcliente']==0){
					$sql="update clientes set galleta='' where galleta=:galleta";
					$sth = $this->dbh->prepare($sql);
					$sth->bindValue(":galleta",$_SESSION['gt']);
					$sth->execute();

					$sql="insert into clientes (galleta, fechacreado) values (:galleta, :fechacreado)";
					$sth = $this->dbh->prepare($sql);
					$sth->bindValue(":galleta",$_SESSION['gt']);
					$sth->bindValue(":fechacreado",date("Y-m-d H:i:s"));
					if($sth->execute()){
						$sql="SELECT * FROM clientes where galleta=:galleta limit 1";
						$sth_i = $this->dbh->prepare($sql);
						$sth_i->bindValue(":galleta",$_SESSION['gt']);
						$sth_i->execute();
						$CLAVE=$sth_i->fetch(PDO::FETCH_OBJ);
						$_SESSION['autoriza_web']=1;
						$_SESSION['interno']=1;
						$_SESSION['correo']="";
						$_SESSION['nombre']="";
						$_SESSION['idcliente']=$CLAVE->id;
					}
				}

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
				$id=$_REQUEST['id'];
				$sql="delete from cliente_carro where idproducto=:id and idcliente=:cli";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(":id",$id);
				$sth->bindValue(":cli",$_SESSION['idcliente']);
				return $sth->execute();
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function limpia_carrito(){
			try{
				$id=$_REQUEST['id'];
				$sql="delete from cliente_carro where idcliente=:cli";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(":cli",$_SESSION['idcliente']);
				return $sth->execute();
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}

		public function wish(){
			try{

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

				$texto=clean_var($_REQUEST['id']);
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
				$nombre = clean_var($_REQUEST["nombre"]);
				$apellido = clean_var($_REQUEST["apellido"]);
				$rfc = clean_var($_REQUEST["rfc"]);
				$cfdi = clean_var($_REQUEST["cfdi"]);
				$direccion1 = clean_var($_REQUEST["direccion1"]);
				$entrecalles = clean_var($_REQUEST["entrecalles"]);
				$colonia = clean_var($_REQUEST["colonia"]);
				$numero = clean_var($_REQUEST["numero"]);
				$ciudad = clean_var($_REQUEST["ciudad"]);
				$cp = clean_var($_REQUEST["cp"]);
				$pais = clean_var($_REQUEST["pais"]);
				$estado = clean_var($_REQUEST["estado"]);
				$telefono = clean_var($_REQUEST["telefono"]);

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
				$factura=0;
				if(isset($_REQUEST["factura"])){
					$factura=1;
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
				$nombre = clean_var($_REQUEST["nombre"]);
				$apellido = clean_var($_REQUEST["apellido"]);
				$rfc = clean_var($_REQUEST["rfc"]);
				$cfdi = clean_var($_REQUEST["cfdi"]);

				////////////////////direccion normal
				$direccion1 = clean_var($_REQUEST["direccion1"]);
				$entrecalles = clean_var($_REQUEST["entrecalles"]);
				$numero = clean_var($_REQUEST["numero"]);
				$colonia = clean_var($_REQUEST["colonia"]);
				$ciudad = clean_var($_REQUEST["ciudad"]);
				$cp = clean_var($_REQUEST["cp"]);
				$pais = clean_var($_REQUEST["pais"]);
				$pais = clean_var($_REQUEST["pais"]);
				$estado = clean_var($_REQUEST["estado"]);
				$dir_factfin = clean_var($_REQUEST["dir_factfin"]);

				if($dir_factfin!="0"){
					////////////////////direccion factura
					$fact_direccion1 = clean_var($_REQUEST["fact_direccion1"]);
					$fact_entrecalles = clean_var($_REQUEST["fact_entrecalles"]);
					$fact_numero = clean_var($_REQUEST["fact_numero"]);
					$fact_colonia = clean_var($_REQUEST["fact_colonia"]);
					$fact_ciudad = clean_var($_REQUEST["fact_ciudad"]);
					$fact_cp = clean_var($_REQUEST["fact_cp"]);
					$fact_pais = clean_var($_REQUEST["fact_pais"]);
					$fact_estado = clean_var($_REQUEST["fact_estado"]);
				}

				$telefono = trim($_REQUEST["tele_x"]);
				$correo = clean_var($_REQUEST["correo"]);
				$notas = clean_var($_REQUEST["notas"]);
				$dir_fin="";

				if(isset($_REQUEST["dir_fin"])){
					$dir_fin = $_REQUEST["dir_fin"];
				}
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

				if($dir_fin=="nueva"){
					$sql="insert into clientes_direccion (idcliente, direccion1, entrecalles, numero, colonia, ciudad, cp, pais, estado) values (:id, :direccion1, :entrecalles, :numero, :colonia, :ciudad, :cp, :pais, :estado)";
					$sth = $this->dbh->prepare($sql);
					$sth->bindValue(":id",$_SESSION['idcliente']);
					$sth->bindValue(":direccion1",$direccion1);
					$sth->bindValue(":entrecalles",$entrecalles);
					$sth->bindValue(":numero",$numero);
					$sth->bindValue(":colonia",$colonia);
					$sth->bindValue(":ciudad",$ciudad);
					$sth->bindValue(":cp",$cp);
					$sth->bindValue(":pais",$pais);
					$sth->bindValue(":estado",$estado);
					$sth->execute();
				}

				if($dir_factfin=="nueva"){
					$sql="insert into clientes_direccion (idcliente, direccion1, entrecalles, numero, colonia, ciudad, cp, pais, estado) values (:id, :direccion1, :entrecalles, :numero, :colonia, :ciudad, :cp, :pais, :estado)";
					$sth = $this->dbh->prepare($sql);
					$sth->bindValue(":id",$_SESSION['idcliente']);
					$sth->bindValue(":direccion1",$fact_direccion1);
					$sth->bindValue(":entrecalles",$fact_entrecalles);
					$sth->bindValue(":numero",$fact_numero);
					$sth->bindValue(":colonia",$fact_colonia);
					$sth->bindValue(":ciudad",$fact_ciudad);
					$sth->bindValue(":cp",$fact_cp);
					$sth->bindValue(":pais",$fact_pais);
					$sth->bindValue(":estado",$fact_estado);
					$sth->execute();
				}

				///////////////////////////se genera el pedido
				try{
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

					if($dir_factfin!="0"){
						$arreglo+= array('fact_direccion1'=>$fact_direccion1);
						$arreglo+= array('fact_entrecalles'=>$fact_entrecalles);
						$arreglo+= array('fact_numero'=>$fact_numero);
						$arreglo+= array('fact_ciudad'=>$fact_ciudad);
						$arreglo+= array('fact_colonia'=>$fact_colonia);
						$arreglo+= array('fact_cp'=>$fact_cp);
						$arreglo+= array('fact_pais'=>$fact_pais);
						$arreglo+= array('fact_estado'=>$fact_estado);
						$arreglo+= array('dir_tipo'=>1);
					}
					else{
						$arreglo+= array('dir_tipo'=>0);
					}

					$arreglo+= array('correo'=>$correo);
					$arreglo+= array('idcliente'=>$_SESSION['idcliente']);
					$arreglo+= array('factura'=>$factura);
					$arreglo+= array('notas'=>$notas);
					$x="";

					$x=$this->insert('pedidos', $arreglo);
					$pedido=json_decode($x);

					$carro=$this->carro_list();
					$preciot=0;
					$enviot=0;
					$totalPrecio=0;
					$totalEnvio=0;
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
							$enviof=$this->egeneral;
						}
						if($key->envio_tipo==1){
							$enviof=$key->envio_costo;
						}

						$enviot=$enviof*$key->cantidad;
						$preciot=$preciof*$key->cantidad;
						$sub=$enviot+$preciot;

						$arreglo =array();
						$arreglo+= array('idprod'=>$key->id);
						$arreglo+= array('idpedido'=>$pedido->id);
						$arreglo+= array('cantidad'=>$key->cantidad);

						$arreglo+= array('precio'=>$preciof);
						$arreglo+= array('envio'=>$enviof);

						$arreglo+= array('total'=>$sub);

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

						$totalPrecio+=$preciot;
						$totalEnvio+=$enviot;
					}
					$arreglo =array();
					$arreglo+= array('monto'=>$totalPrecio);
					$arreglo+= array('envio'=>$totalEnvio);
					$gtotal=$totalPrecio+$totalEnvio;

					$gtotal=$gtotal*1.16;
					$arreglo+= array('total'=>round($gtotal,2));
					$this->update('pedidos',array('id'=>$pedido->id), $arreglo);
					////////////////////////////////////////////////////////////////
					/// pedidos a CT..
					////////////////////////////////////////////////////////////////
					$idpedido=$pedido->id;

					$ped=$this->pedido_ver($idpedido);
					$cupones=$this->pedido_cupones($idpedido);
					$datos=$this->datos_pedido($idpedido);

					/*
					$direccion1=$ped['direccion1'];
					$entrecalles=$ped['entrecalles'];
					$numero=$ped['numero'];
					$colonia=$ped['colonia'];

					$ciudad=$ped['ciudad'];
					$cp=$ped['cp'];
					$pais=$ped['pais'];
					$estado=$ped['estado'];
					$telefono=$ped['telefono'];
					$gmonto=$ped['monto'];
					$genvio=$ped['envio'];
					$gtotal=$ped['total'];
					$estatus=$ped['estatus'];
					$pago=$ped['pago'];
					$idpago=$ped['idpago'];
					*/
					$resp = crearNuevoToken();
					$tok=$resp->token;

					foreach($datos as $key){
						$clave=$key->clave;
						$idprod=$key->idprod;
						$cantidad=$key->cantidad;
						$sql="select * from productos where id='".$idprod."'";
						$prod_query = $this->dbh->prepare($sql);
						$prod_query->execute();
						$prod_pedido=$prod_query->fetch(PDO::FETCH_OBJ);
						$precio_prod=$prod_pedido->precio;

						if($key->tipo=="CT"){
							$sql="select producto_exist.*,almacen.numero from producto_exist left outer join almacen on almacen.homoclave=producto_exist.almacen where id='$idprod' order by existencia desc";
							$exist = $this->dbh->prepare($sql);
							$exist->execute();
							$contar=$exist->rowCount();

							if($contar>0){
								$alma_pedido=$exist->fetchAll(PDO::FETCH_OBJ);
								foreach($alma_pedido as $pedx){
									if($cantidad>0){
										$pedir=$pedx->existencia-$cantidad;
										if($pedir>=0){
											$pedir=$cantidad;
										}
										else{
											$pedir=$cantidad+$pedir;
										}
										$cantidad=$cantidad-$pedir;

										if($pedir>0){
											$envio=array();
											$contar=0;

											$resp =servicioApi('GET',"existencia/detalle/".$clave."/".$pedx->numero,NULL,$tok);
											if($resp->promocion){

												if ($resp->promocion->descuentoPrecio>0){
													$precio_desc=$resp->promocion->descuentoPrecio;
												}
												if($resp->promocion->descuentoPorcentaje>0){
													$porc=$resp->promocion->descuentoPorcentaje;
													$precio_desc=$precio_prod-(($precio_prod*$porc)/100);
												}
												$precio_f=round($precio_desc,2);
											}
											else{
												$precio_f=$precio_prod;
											}

											$envio[0]=array(
												'nombre' => $nombre. " ".$apellido,
												'direccion' => $direccion1,
												'entreCalles' => $entrecalles,
												'noExterior' => $numero,
												'colonia' => $colonia,
												'estado' => $estado,
												'ciudad' => $ciudad,
												'codigoPostal' => $cp,
												'telefono' => $telefono
											);

											$producto[0]=array(
												'cantidad' => $pedir,
												'clave' => $clave,
												'precio' => "$precio_f",
												'moneda' => $prod_pedido->moneda
											);

											$arreglo=array(
												'idPedido' => (int)$idpedido,
												'almacen' => $pedx->numero,
												'tipoPago' => "03",
												'envio' => json_decode(json_encode($envio)),
												'producto' => json_decode(json_encode($producto)),
											);
											$json = json_encode($arreglo);

											$resp =servicioApi('POST','pedido',$json,$tok); 					/////////////////////////////////////////////PEDIDO
											if (isset($resp->errorCode)){
												$arreglo+=array('id'=>0);
												$arreglo+=array('error'=>1);
												$arreglo+=array('terror'=>$resp->errorMessage);
												return json_encode($arreglo);
											}
											else{
												$pedidoweb=$resp[0]->respuestaCT->pedidoWeb;
												$estatus=$resp[0]->respuestaCT->estatus;
												$sql="insert into pedidos_web (idprod, clave, cantidad, pedidoWeb, estatus, idpedido) values ('$idprod', '$clave', '$pedir', '$pedidoweb', '$estatus', '$idpedido')";
												$stmt= $this->dbh->query($sql);
											}
										}
									}
									else{
										break;
									}
								}
							}
						}
						else{
							$sql="select * from producto_exist where id='".$idprod."' order by existencia desc limit 1";
							$exist = $this->dbh->prepare($sql);
							$exist->execute();
							if($exist->rowCount()>0){
								$invent=$exist->fetch(PDO::FETCH_OBJ);
								$total=($invent->existencia-$cantidad);
								$sql="update producto_exist set existencia='$total' where idexist='".$invent->idexist."'";
								$stmt= $this->dbh->query($sql);

								$sql="select sum(existencia) as total from producto_exist where id='$idprod'";
								$sth = $this->dbh->prepare($sql);
								$sth->execute();
								$resp=$sth->fetch(PDO::FETCH_OBJ);

								$fecha=mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));
								$fmodif = date("Y-m-d H:i:s");

								$sql="update productos set existencia='".$resp->total."', timeexis='$fecha', horaexist='$fmodif' where id='$idprod'";
								$stmt2= $this->dbh->query($sql);
							}
						}
					}

					/////////////////////////////////////////////Correo

					$texto="<h3>TIC-SHOP</h3><br>
					<h3><b><center>Pedido</center></b></h3>

					<table style='width:100%'>
						<tr>
						<td>
							<b>Pedido #:</b><br> $idpedido
						</td>
						<td>
							<b>Estatus:</b><br> $estatus
						</td>
						<td>
							<b>Pago:</b><br> Pendiente por pagar
						</td>

						<td>
							<b>Nombre:</b><br> $nombre $apellido</b>
						</td>
						<td>
							<b>Correo:</b><br> $correo
						</td>
					</tr>
					</table>
					<br>
					<h3><center>Información de envío</center></h3>
					<table style='width:100%'>
					<tr>
						<td>
							<b>RFC:</b><br> $rfc
						</td>
						<td>
							<b>Uso CFDI:</b><br> $cfdi
						</td>
						<td>
							<b>Dirección:</b><br> $direccion1
						</td>
						<td>
							<b>Entre calles:</b><br> $entrecalles
						</td>
					</tr>
					</table>
					<table style='width:100%'>
					<tr>
						<td>
							<b>Num. Exterior:</b><br> $numero
						</td>
						<td>
							<b>Colonia:</b><br> $colonia
						</td>
						<td>
							<b>Ciudad:</b><br> $ciudad
						</td>
						<td>
							<b>Código postal:</b><br> $cp
						</td>
						<td>
							<b>Pais:</b><br> $pais
						</td>
						<td>
							<b>Estado:</b><br> $estado
						</td>
						<td>
							<b>Teléfono:</b><br> $telefono
						</div>
					</tr>
					<br>
					<hr>
					<h3>Productos</h3>
					<table>
					<hr>
					<table style='width:100%'>
						<tr>
							<td>
								<b>Descripción</b>
							</td>
							<td>
								<b>Cantidad</b>
							</td>
							<td>
								<b>Precio unitario</b>
							</td>
							<td>
								<b>Envío</b>
							</td>

							<td>
								<b>Total</b>
							</td>
						</tr>
					";

						///////////////////////////////////
						$sub_total=0;
						$sub_envio=0;
						foreach($datos as $key){
							$texto.="<tr>";
								$texto.= "<td>";
										$texto.= $key->clave;
										$texto.= "<br><b>".$key->nombre."</b>";
										$texto.= "<br>".$key->modelo;
										$texto.= "<br>".$key->marca;
										$texto.= "<br>".$key->categoria;
								$texto.= "</td>";

								$texto.= "<td>";
									$texto.= $key->cantidad;
								$texto.= "</td>";

								$texto.= "<td>";
									$texto.= moneda($key->precio);
									$sub_total+=$key->precio*$key->cantidad;
								$texto.= "</td>";

								$texto.= "<td>";
									$texto.= moneda($key->envio);
									$sub_envio+=$key->envio*$key->cantidad;
								$texto.= "</td>";

								$texto.= "<td>";
									$texto.= moneda($key->total);
								$texto.= "</td>";

							$texto.= "</tr>";
						}

						///////////////////////////////////
							$gtotal=$sub_total+$sub_envio;
							$texto.= "<tr>";
								$texto.= "<td colspan=4>";
									$texto.= "<b>Subtotal</b>";
								$texto.= "</td>";
								$texto.= "<td>";
									$texto.= moneda($sub_total+$sub_envio);
								$texto.= "</td>";
							$texto.= "</tr>";


							if(is_array($cupones) and count($cupones)>0){
								$texto.= "<tr><td colspan=5>Cupones</td></tr>";
								foreach($cupones as $keyc){
									$texto.= "<tr>";
										$texto.= "<td colspan=4>";
											$texto.= $keyc['codigo'];
											$texto.= "<br>";
											$texto.= $keyc['descripcion'];
										$texto.= "</td>";
										$texto.= "<td>";

											if($keyc['tipo']=='porcentaje'){
												$texto.= $keyc['descuento']."%";
												$monto=($gtotal*$keyc['descuento'])/100;
												$texto.= "<br>- ".moneda($monto);
												$gtotal=$gtotal-$monto;
											}

											if($keyc['tipo']=='carrito'){
												$texto.= "<br>- ".moneda($keyc['descuento']);
												$gtotal=$gtotal-$keyc['descuento'];
											}

											if($keyc['envio']=='si'){
												$gtotal=$gtotal-$envio;
												$texto.= "<br>Envio: -".$envio;
											}

										$texto.= "</td>";
									$texto.= "</tr>";

									$texto.= "<tr>";
										$texto.= "<td colspan=4>";
											$texto.= "<h4><b>Total:</b></h4>";
										$texto.= "</td>";

										$texto.= "<td>";
											$texto.= "<h4><b>".moneda($gtotal)."</b></h4>";
										$texto.= "</td>";
									$texto.= "</tr>";
								}

								$texto.= "<tr>";
									$texto.= "<td colspan=4>";
										$texto.= "<b>Subtotal</b>";
									$texto.= "</td>";
									$texto.= "<td>";
										$texto.= moneda($gtotal);
									$texto.= "</td>";
								$texto.= "</tr>";
							}

							$iva=$gtotal*.16;
							$texto.= "<tr>";
								$texto.= "<td colspan=4>";
									$texto.= "<b>IVA</b>";
								$texto.= "</td>";
								$texto.= "<td>";
									$texto.= moneda($iva);
								$texto.= "</td>";
							$texto.= "</tr>";

							$gtotal=$gtotal*1.16;
							$texto.= "<tr>";
								$texto.= "<td colspan=4>";
									$texto.= "<b>Total</b>";
								$texto.= "</td>";
								$texto.= "<td>";
									$texto.= moneda($gtotal);
								$texto.= "</td>";
							$texto.= "</tr>";

					$texto.="</table>";
					$asunto="Compra Exitosa";
					$this->correo($correo, $texto, $asunto);
					/////////////////////////////////////////////////////////////////////////////////////////////////
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
				$estrella=clean_var($_REQUEST['estrella']);
				$idproducto=clean_var($_REQUEST['idproducto']);
				$texto=clean_var($_REQUEST['texto']);

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

				$idpedido=clean_var($_REQUEST['idpedido']);
				$cupon=clean_var($_REQUEST['cupon']);

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

		public function correo($correo, $texto, $asunto){
			/////////////////////////////////////////////Correo
			require 'vendor/autoload.php';
			$mail = new PHPMailer;
			$mail->CharSet = 'UTF-8';

			$mail->Body    = $asunto;
			$mail->Subject = $asunto;
			$mail->AltBody = $asunto;

			$mail->isSMTP();
			$mail->Host = "smtp.gmail.com";						  // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username = "tic.shop.adm@gmail.com";       // SMTP username
			$mail->Password = "NvZMzfyqWfZe?1";                       // SMTP password
			$mail->SMTPSecure = "ssl";                            // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 465;                                    // TCP port to connect to
			$mail->CharSet = 'UTF-8';
			//$mail->From = "tic.shop.adm@gmail.com";
			$mail->From = "ventas@tic-shop.com.mx";
			$mail->FromName = "TIC-SHOP";

			$mail->IsHTML(true);
			$mail->addAddress($correo);
			$mail->addCC("ventas@tic-shop.com.mx");

			$mail->msgHTML($texto);
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
	function clean_var($val){
		$val=htmlspecialchars(strip_tags(trim($val)));
		return $val;
	}
	function getRealIP(){
		if (isset($_SERVER["HTTP_CLIENT_IP"])){
				return $_SERVER["HTTP_CLIENT_IP"];
		}
		elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
				return $_SERVER["HTTP_X_FORWARDED_FOR"];
		}
		elseif (isset($_SERVER["HTTP_X_FORWARDED"])){
				return $_SERVER["HTTP_X_FORWARDED"];
		}
		elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])){
				return $_SERVER["HTTP_FORWARDED_FOR"];
		}
		elseif (isset($_SERVER["HTTP_FORWARDED"])){
				return $_SERVER["HTTP_FORWARDED"];
		}
		else{
				return $_SERVER["REMOTE_ADDR"];
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

	    echo curl_error($ch);
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
