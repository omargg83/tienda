<?php
  session_start();
  date_default_timezone_set("America/Mexico_City");
  function nhk_alldump(){
    if (isset($_SERVER)) {
      $fecha = new DateTime(null, new DateTimeZone('UTC'));
      $data = [];
      $dir_log = $_SERVER['DOCUMENT_ROOT'].'/tienda/nkj';
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

  class daasldjflks{
		public function __construct(){
      try{
        date_default_timezone_set("America/Mexico_City");

				$mysqluser="ticshopc_admin";
				$mysqlpass="admin123$%";
				$servidor ="tic-shop.com.mx";
				$bdd="ticshopc_tienda";

				$this->dbh = new PDO("mysql:host=$servidor;dbname=$bdd", $mysqluser, $mysqlpass);
        $this->dbh->query("SET NAMES 'utf8'");
			}
			catch(PDOException $e){
        die();
				return "Database access FAILED!";
			}
		}
    public function acceso(){
			try{
				$ip=self::getRealIP();

        $sql="SELECT baneada FROM token_log where baneada=:baneada";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(":baneada",$ip);
				$sth->execute();
				$contar=$sth->rowCount();
				if($contar>0){
					$arr=array();
					$arr=array('acceso'=>0);
					$arr=array('info'=>"Error 1");
					return json_encode($arr);
				}

				////////////////////////////los id y name de los input de login son variantes por lo que si no existen quiere decir que el usuario intento hackear y por lo tanto se banea la IP
				$metodo=$_SERVER['REQUEST_METHOD'];
				$keys=array_keys($_REQUEST);
				$uno=$keys[0];
				$dos=$keys[1];

        $user=clean_var($_REQUEST[$uno]);
				$pass=clean_var($_REQUEST[$dos]);

        $us_fake=clean_var($_REQUEST['usuario']);
        $pa_fake=clean_var($_REQUEST['password']);

        if(strlen($uno)<8 or strlen($dos)<8 or strlen($us_fake)>0 or strlen($pa_fake)>0){
          return 0;
        }

				$sql="SELECT in_u, in_p, intentos FROM token_pikatic where in_u=:usuario and in_p=:pass";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(":usuario",$uno);
				$sth->bindValue(":pass",$dos);
				$sth->execute();
				$contar=$sth->rowCount();
				$row=$sth->fetch(PDO::FETCH_OBJ);
				if($contar and $row->intentos<3){
					///////////////////////numero de intentos

					$total=$row->intentos+1;
					$sql="update token_pikatic set intentos=$total where in_u=:usuario and in_p=:pass";
					$sth = $this->dbh->prepare($sql);
					$sth->bindValue(":usuario",$uno);
					$sth->bindValue(":pass",$dos);
					$sth->execute();

					$userPOST=$user;
					$encriptx=md5("tic%pika_$%&/()=").md5(trim($pass));
					$passPOST=hash("sha512",$encriptx);

					$sql="SELECT nombre, nivel, idpersona, correo_xptic, pass_xptic as pxs FROM usuarios where correo_xptic=:usuario and pass_xptic=:pass and autoriza=1";
					$sth = $this->dbh->prepare($sql);
					$sth->bindValue(":usuario",$userPOST);
					$sth->bindValue(":pass",$passPOST);
					$sth->execute();
					$CLAVE=$sth->fetch(PDO::FETCH_OBJ);
					 if ($sth->rowCount()>0 and $CLAVE->correo_xptic==$userPOST and $CLAVE->pxs==$passPOST){
						$suma=1;
						/////////////////la llave
						$clave=md5("tic%pika_$%&/()=".$ip);
						$llave=hash("sha512",$clave);
						$_SESSION['idsess']=$llave;
						$_SESSION['autoriza']=1;
						$_SESSION['nombre']=$CLAVE->nombre;
						$_SESSION['nivel'] = $CLAVE->nivel;
						$_SESSION['idpersona'] = $CLAVE->idpersona;
						$_SESSION['pagnivel']=40;
						$_SESSION['remoto']=0;
						$_SESSION['cfondo']="white";
						$arr=array();
						$arr=array('acceso'=>1);
						return json_encode($arr);
					}
					else {
						$arr=array();
						$arr=array('acceso'=>0);
						$arr=array('info'=>"Usuario o contraseña incorrecta");
						return json_encode($arr);
					}
					return $obj;
				}
				else{
					if($contar==0){
						$sql="insert into token_log (baneada) values (:ip)";
						$sth = $this->dbh->prepare($sql);
						$sth->bindValue(":ip",$ip);
						$sth->execute();
						$arr=array();
						$arr=array('acceso'=>0);
						$arr=array('info'=>"Error 1");
						return json_encode($arr);
					}
					$arr=array();
					$arr=array('acceso'=>0);
					$arr=array('info'=>"Ha excedido el numero máximo de intentos permitido, espere unos minutos y vuelva a intentarlo");
					return json_encode($arr);
				}
			}
			catch(PDOException $e){
				return "Database access FAILED!";
			}
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
  }
  function clean_var($val){
		$val=htmlspecialchars(strip_tags(trim($val)));
		return $val;
	}

  $db = new daasldjflks();
  echo $db->acceso();
 ?>
