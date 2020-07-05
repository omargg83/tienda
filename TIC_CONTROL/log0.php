<?php
  session_start();
  date_default_timezone_set("America/Mexico_City");
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

  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\SMTP;

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
    public function recuperar(){
      $x="";
      if (isset($_REQUEST['telefono'])){ $texto=clean_var($_REQUEST['telefono']); }

      $sql="select * from usuarios where correo_xptic='$texto'";
      $sth = $this->dbh->query($sql);
      //$sth->bindValue(":texto",$texto);
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
  function clean_var($val){
		$val=htmlspecialchars(strip_tags(trim($val)));
		return $val;
	}

  $db = new daasldjflks();
  echo $db->recuperar();
 ?>
