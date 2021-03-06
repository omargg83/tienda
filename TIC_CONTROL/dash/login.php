<?php
	if (!isset($_SESSION)) { session_start(); }
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
  //nhk_alldump();
  class Login{
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
    public function genera_random($length = 24) {
      try{
        $ip=self::getRealIP();

        $_SESSION['idsess']="";
        $_SESSION['autoriza']=0;

        $random=substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
        $in=md5(substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 16));
        $pin=md5(substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 16));
        $encrip=password_hash($random, PASSWORD_DEFAULT);

        $date = new DateTime();
        $date->modify('+3 hours');
        $limite=$date->format('Y-m-d H:i:s');

        $fecha=date('Y-m-d H:i:s');
        $sql="insert into token_pikatic (token, cadena, in_u, in_p, expira, generado, ip, intentos) values (:token, :cadena, :inp, :pin, :expira, :genera, :ip, 0)";
				$sth = $this->dbh->prepare($sql);

				$sth->bindValue(":token",$random);
				$sth->bindValue(":cadena",$encrip);
				$sth->bindValue(":inp",$in);
				$sth->bindValue(":pin",$pin);
				$sth->bindValue(":expira",$limite);
				$sth->bindValue(":genera",$fecha);
				$sth->bindValue(":ip",$ip);
				$sth->execute();
        return array($in,$pin);
      }
      catch(PDOException $e){
        echo "Database access FAILED!";
      }
    }
    public function ip(){
      try{
        $ip=self::getRealIP();

        $date = new DateTime();
        $date->modify('-10 minutes');
        $limite=$date->format('Y-m-d H:i:s');

        $sql="delete from token_pikatic where ip=:ip and generado<:limite";
        $sth = $this->dbh->prepare($sql);
        $sth->bindValue(":ip",$ip);
        $sth->bindValue(":limite",$limite);
        $sth->execute();

        if(strlen($ip)>8){
          $sql="select count(token) as numero from token_pikatic where ip=:ip";
          $sth = $this->dbh->prepare($sql);
          $sth->bindValue(":ip",$ip);
          $sth->execute();
          $CLAVE=$sth->fetch(PDO::FETCH_OBJ);
          return $CLAVE->numero;
        }
        else{
          return 100;
        }
      }
      catch(PDOException $e){
        return "Database access FAILED!";
      }
    }
    public function baneada(){
      try{
        $ip=self::getRealIP();
        $sql="SELECT baneada FROM token_log where baneada=:baneada";
        $sth = $this->dbh->prepare($sql);
        $sth->bindValue(":baneada",$ip);
        $sth->execute();
        $contar=$sth->rowCount();
        return $contar;
      }
      catch(PDOException $e){
        return "Database access FAILED!";
      }
    }
  }
  $intentos=0;
  $db = new Login();
  if($db->baneada()>0){
    echo "ERROR FAVOR DE VERIFICAR CON EL ADMINISTRADOR";
    die();
  }
	echo $db->ip();
  $intentos=$db->ip();
  if($intentos>3){
    echo "Ha superado el número de ingresos permitidos, favor de esperar 10 minutos para volver a intentarlo";
    die();
  }
  else{
    $ar=$db->genera_random();
    $a=$ar[0];
    $b=$ar[1];
  }
?>

<form id='acceso' action=''>
    <div class='container'>
        <center><img src='img/LOGO.png' width='250px'></center>
        <p class='input_title'>Usuario o correo:</p>
        <div class='form-group input-group'>
          <div class='input-group-prepend'>
            <span class='input-group-text'> <i class='fas fa-user-circle'></i> </span>
          </div>
          <input class='form-control' placeholder='Introduzca usuario o correo' type='text'  id='<?php echo $a;?>' name='<?php echo $a;?>' required autocomplete="off">
        </div>
        <p class='input_title'>Contraseña:</p>
        <div class='form-group input-group'>
          <div class='input-group-prepend'>
            <span class='input-group-text'> <i class='fa fa-lock'></i> </span>
          </div>
          <input class='form-control' placeholder='Contraseña' type='password'  id='<?php echo $b;?>' name='<?php echo $b;?>' required autocomplete="off">
        </div>
        <button class='btn btn-secondary btn-block' type='submit' id='submit'><i class='fa fa-check'></i>Aceptar</button>
        <button class='btn btn-secondary btn-block' type='button' id='recuperar' ><i class='fas fa-key'></i>Recuperar contraseña</button>
        <center>http://tic-shop.com.mx/</center>
    </div>
    <div id='registro' style='display:none'>
      <input class='form-control' type='text' id='usuario' name='usuario' value='' onchange='md5pass()' autocomplete="off">
      <input class='form-control' type='text' id='password' name='password' value='' onchange='md5pass()' autocomplete="off">
    </div>
  </form>
