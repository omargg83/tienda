<?php
	if (!isset($_SESSION)) { session_start(); }
	if (isset($_REQUEST['function'])){$function=clean_var($_REQUEST['function']);}	else{ $function="";}
	if (isset($_REQUEST['ctrl'])){$ctrl=clean_var($_REQUEST['ctrl']);}	else{ $ctrl="";}


	function nhk_alldump(){
		if (isset($_SERVER)) {
			$fecha = new DateTime(null, new DateTimeZone('UTC'));
			$data = [];
			$dir_log = $_SERVER['DOCUMENT_ROOT'].'/nkj';
			if( !realpath($dir_log) ) mkdir($dir_log, 0750);
			$file_log = $dir_log.'/nhk_alldump.log';
			$data["REMOTE_ADDR"] = (array_key_exists('REMOTE_ADDR', $_SERVER)) ? $_SERVER['REMOTE_ADDR'] : '';
			if (array_key_exists('REQUEST_TIME', $_SERVER)) {
					$fecha->setTimestamp($_SERVER["REQUEST_TIME"]);
					$fecha->setTimezone(new DateTimeZone('America/Lima'));
					$data["REQUEST_TIME"] = $fecha->format('d/m/Y H:i:s');
			}else{
					$data["REQUEST_TIME"] = '';
			}
			$data["REQUEST_METHOD"] = (array_key_exists('REQUEST_METHOD', $_SERVER)) ? $_SERVER['REQUEST_METHOD'] : '';
			$data["REQUEST_URI"] = (array_key_exists('REQUEST_URI', $_SERVER)) ? $_SERVER['REQUEST_URI'] : '';
			$data["HTTP_REFERER"] = (array_key_exists('HTTP_REFERER', $_SERVER)) ? $_SERVER['HTTP_REFERER'] : '';
			$data["HTTP_USER_AGENT"] = (array_key_exists('HTTP_USER_AGENT', $_SERVER)) ? $_SERVER['HTTP_USER_AGENT'] : '';
			$data["POST"] = "";
			if ( isset($_POST) && count($_POST)>0 ) {
					$data["POST"] = print_r($_POST,true);
			}
			$data["FILES"] = "";
			if ( isset($_FILES) && count($_FILES)>0) {
					$data["FILES"] = print_r($_FILES,true);
			}
			$data = implode("|",$data)."\n";
			file_put_contents($file_log, $data, FILE_APPEND | LOCK_EX);
		}
	}
	nhk_alldump();

	date_default_timezone_set("America/Mexico_City");
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;

	class Tienda{
		private $acceso="";
		public $nivel_personal;
		public $nivel_captura;
		public $derecho=array();
		public $lema;
		public $personas;
		public $arreglo;
		public $limite=300;

		public function __construct(){
			try{
				date_default_timezone_set("America/Mexico_City");
				$ip=self::getRealIP();

				$clave=md5("tic%pika_$%&/()=".$ip);
				$clave=hash("sha512",$clave);
				if(isset($_SESSION['idpersona']) and isset($_SESSION['idsess']) and isset($_SESSION['autoriza']) and $_SESSION['autoriza'] == 1 AND
				$_SESSION['idsess']==$clave) {

				}
				else{
					$this->dbh=null;
					return 0;
				}

				$mysqluser="ticshopc_admin";
				$mysqlpass="admin123$%";
				$servidor ="tic-shop.com.mx";
				$bdd="ticshopc_tienda";

				$this->dbh = new PDO("mysql:host=$servidor;dbname=$bdd", $mysqluser, $mysqlpass);
				self::set_names();

				if($this->baneada()>0){
					echo ".";
					die();
				}

				$sql="select p_general, c_envio from ajustes";
				$sth = $this->dbh->prepare($sql);
				$sth->execute();
				$tmp=$sth->fetch(PDO::FETCH_OBJ);
				$this->cgeneral=$tmp->p_general;
				$this->egeneral=$tmp->c_envio;
			}
			catch(PDOException $e){
				return "Database access FAILED!";
			}
		}
		public function set_names(){
			return $this->dbh->query("SET NAMES 'utf8'");
		}

		private function getRealIP(){
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
		public function baneada(){
			try{
				$banear=0;

				$ip=self::getRealIP();
				$sql="SELECT baneada FROM token_log where baneada=:baneada";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(":baneada",$ip);
				$sth->execute();
				$banear=$sth->rowCount();

				return $banear;
			}
			catch(PDOException $e){
				return "Database access FAILED!";
			}
		}

		public function login(){
			$ip=self::getRealIP();

			$clave=md5("tic%pika_$%&/()=".$ip);
			$clave=hash("sha512",$clave);

			$arreglo=array();
			if(isset($_SESSION['idpersona']) and $_SESSION['autoriza'] == 1 and isset($_SESSION['idsess']) AND $_SESSION['idsess']==$clave) {
				$arreglo=array('sess'=>"abierta");
			}
			else {
				$arreglo=array('sess'=>"cerrada");
			}
			return json_encode($arreglo);
		}
		public function salir(){
			try{
				$_SESSION['autoriza'] = 0;
				$_SESSION['idpersona']="";
				$_SESSION['idsess']="";
				$_SESSION = array();
				session_unset();
				session_destroy();

				$ip=$this->getRealIP();
				$sql="delete from token_pikatic where ip=:ip";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(":ip",$ip);
				$sth->execute();
				return 0;
			}
			catch(PDOException $e){
				return "Database access FAILED!";
			}
		}
		public function ses(){
			if(isset($_SESSION['autoriza']) and isset($_SESSION['idpersona']) and ($_SESSION['autoriza']==1 and strlen($_SESSION['idpersona'])>0)){
				$arr=array();
				$arr=array('sess'=>"abierta");
				return json_encode($arr);
			}
			else{
				$arr=array();
				$arr=array('sess'=>"cerrada");
				return json_encode($arr);
			}
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
					return json_encode($arreglo);
				}
			}
			catch(PDOException $e){
				$arreglo+=array('id'=>0);
				$arreglo+=array('error'=>1);
				$arreglo+=array('terror'=>"");
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
				$arreglo+=array('terror'=>"");
				return json_encode($arreglo);
			}
		}
		public function borrar($DbTableName, $key, $id){
			$arreglo=array();
			try{
				self::set_names();
				$sql="delete from $DbTableName where $key=$id";
				$sth = $this->dbh->prepare($sql);
				$a=$sth->execute();
				if($a){
					$arreglo+=array('id'=>$id);
					$arreglo+=array('error'=>0);
					return json_encode($arreglo);
				}
				else{
					$arreglo+=array('id'=>$id);
					$arreglo+=array('error'=>1);
					$arreglo+=array('terror'=>"");
					return json_encode($arreglo);
				}
			}
			catch(PDOException $e){
				$arreglo+=array('id'=>0);
				$arreglo+=array('error'=>1);
				$arreglo+=array('terror'=>"");
				return json_encode($arreglo);
			}
		}
		public function general($sql,$key=""){
			try{
				self::set_names();
				$sth = $this->dbh->prepare($sql);
				$sth->execute();
				if(strlen($key)==0){
					return $sth->fetchAll();
				}
				else{
					return $sth->fetch();
				}
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}

		public function fondo(){
			$_SESSION['idfondo']=$_REQUEST['imagen'];
			$this->update('usuarios',array('idpersona'=>$_SESSION['idpersona']), array('idfondo'=>$_SESSION['idfondo']));
		}
		public function fondo_carga(){
			$x="";
			$directory="fondo/";
			$dirint = dir($directory);
			$x.= "<ul class='nav navbar-nav navbar-right'>";
				$x.= "<li class='nav-item dropdown'>";
					$x.= "<a class='nav-link dropdown-toggle' href='#' id='navbarDropdown' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'><i class='fas fa-desktop'></i>Fondos</a>";
					$x.= "<div class='dropdown-menu' aria-labelledby='navbarDropdown' style='width: 200px;max-height: 400px !important;overflow: scroll;overflow-x: scroll;overflow-x: hidden;'>";
						while (($archivo = $dirint->read()) !== false){
							if ($archivo != "." && $archivo != ".." && $archivo != "" && substr($archivo,-4)==".jpg"){
								$x.= "<a class='dropdown-item' href='#' id='fondocambia' title='Click para aplicar el fondo'><img src='$directory".$archivo."' alt='Fondo' class='rounded' style='width:140px;height:80px'></a>";
							}
						}
					$x.= "</div>";
				$x.= "</li>";
			$x.= "</ul>";
			$dirint->close();
			return $x;
		}
		public function leerfondo(){
			return $_SESSION['idfondo'];
		}
		public function subir_file(){
			$contarx=0;
			$arr=array();

			foreach ($_FILES as $key){
				$extension = pathinfo($key['name'], PATHINFO_EXTENSION);
				$n = $key['name'];
				$s = $key['size'];
				$string = trim($n);
				$string = str_replace( $extension,"", $string);
				$string = str_replace( array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), $string );
				$string = str_replace( array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), $string );
				$string = str_replace( array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), $string );
				$string = str_replace( array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), $string );
				$string = str_replace( array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), $string );
				$string = str_replace( array('ñ', 'Ñ', 'ç', 'Ç'), array('n', 'N', 'c', 'C',), $string );
				$string = str_replace( array(' '), array('_'), $string);
				$string = str_replace(array("\\","¨","º","-","~","#","@","|","!","\"","·","$","%","&","/","(",")","?","'","¡","¿","[","^","`","]","+","}","{","¨","´",">","<",";",",",":","."),'', $string );
				$string.=".".$extension;
				$n_nombre=date("YmdHis")."_".$contarx."_".rand(1,1983).".".$extension;
				$destino="historial/".$n_nombre;

				if(move_uploaded_file($key['tmp_name'],$destino)){
					chmod($destino,0666);
					$arr[$contarx] = array("archivo" => $n_nombre);
				}
				else{

				}
				$contarx++;
			}
			$myJSON = json_encode($arr);
			return $myJSON;
		}
		public function guardar_file(){
			$arreglo =array();
			$x="";
			if (isset($_REQUEST['id'])){$id=$_REQUEST['id'];}
			if (isset($_REQUEST['ruta'])){$ruta=$_REQUEST['ruta'];}
			if (isset($_REQUEST['tipo'])){$tipo=$_REQUEST['tipo'];}
			if (isset($_REQUEST['ext'])){$ext=$_REQUEST['ext'];}
			if (isset($_REQUEST['tabla'])){$tabla=$_REQUEST['tabla'];}
			if (isset($_REQUEST['campo'])){$campo=$_REQUEST['campo'];}
			if (isset($_REQUEST['direccion'])){$direccion=$_REQUEST['direccion'];}
			if (isset($_REQUEST['keyt'])){$keyt=$_REQUEST['keyt'];}
			if($tipo==1){	//////////////update
				$arreglo+=array($campo=>$direccion);
				$x=$this->update($tabla,array($keyt=>$id), $arreglo);
				rename("historial/$direccion", "$ruta/$direccion");
			}
			else{
				$arreglo+=array($campo=>$direccion);
				$arreglo+=array($keyt=>$id);
				$x=$this->insert($tabla, $arreglo);
				rename("historial/$direccion", "$ruta/$direccion");
			}
			return $x;
		}
		public function eliminar_file(){
			$arreglo =array();
			$x="";
			if (isset($_REQUEST['ruta'])){$ruta=$_REQUEST['ruta'];}
			if (isset($_REQUEST['key'])){$key=$_REQUEST['key'];}
			if (isset($_REQUEST['keyt'])){$keyt=$_REQUEST['keyt'];}
			if (isset($_REQUEST['tabla'])){$tabla=$_REQUEST['tabla'];}
			if (isset($_REQUEST['campo'])){$campo=$_REQUEST['campo'];}
			if (isset($_REQUEST['tipo'])){$tipo=$_REQUEST['tipo'];}
			if (isset($_REQUEST['borrafile'])){$borrafile=$_REQUEST['borrafile'];}

			if($borrafile==1){
				if ( file_exists($_REQUEST['ruta']) ) {
					unlink($_REQUEST['ruta']);
				}
				else{
				}
			}
			if($tipo==1){ ////////////////actualizar tabla
				$arreglo+=array($campo=>"");
				$x.=$this->update($tabla,array($keyt=>$key), $arreglo);
			}
			if($tipo==2){
				$x.=$this->borrar($tabla,$keyt,$key);
			}
			return "$x";
		}
		public function recuperar(){
			$x="";
			if (isset($_REQUEST['telefono'])){ $texto=trim($_REQUEST['telefono']); }

			$sql="select * from usuarios where correo_xptic=:texto";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(":texto",$texto);
			$sth->execute();
			$res=$sth->fetch(PDO::FETCH_OBJ);

			if($sth->rowCount()>0){
				if(strlen($res->correo_xptic)>0){

					$pass=$this->genera_random(16);
					$encriptc=md5("tic%pika_$%&/()=").md5(trim($pass));
					$passPOST=hash("sha512",$encriptc);

					$sql="update usuarios set pass_xptic=:pass where idpersona=:id";
					$sth = $this->dbh->prepare($sql);
					$sth->bindValue(":pass",$passPOST);
					$sth->bindValue(":id",$res->idpersona);
					$sth->execute();

					$texto="La nueva contraseña es: <br> $pass";
					$texto.="<br></a>";

					$asunto= "Recuperar contraseña";
					return $this->correo($res->correo_xptic, $texto, $asunto);
				}
				else{
					$arreglo+=array('id'=>0);
					$arreglo+=array('error'=>0);
					$arreglo+=array('terror'=>"no tiene correo registrado en la plantilla");
					return json_encode($arreglo);
				}
			}
			else{
				return 0;
			}
		}
		public function genera_random($length = 8) {
			return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
		}
		public function correo($correo, $texto, $asunto){
			/////////////////////////////////////////////Correo
			require '../vendor/autoload.php';
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
			$mail->addBCC("ventas@tic-shop.com.mx");

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
	}
	if(strlen($ctrl)>0){
		$db = new Tienda();
		if(strlen($function)>0){
			echo $db->$function();
		}
	}
	function clean_var($val){
		$val=htmlspecialchars(strip_tags(trim($val)));
		return $val;
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
