<?php
  session_start();
  error_reporting(E_ALL);
  ini_set('display_errors', '1');
  date_default_timezone_set("America/Mexico_City");

  class Login{
		public function __construct(){
      try{
        date_default_timezone_set("America/Mexico_City");
        /*
          $mysqluser="sagyccom_esponda";
          $mysqlpass="esponda123$";
          $servidor ="sagyc.com.mx";
          $bdd="sagycrmr_tienda";
        */

				$mysqluser="ticshopc_admin";
				$mysqlpass="admin123$%";
				$servidor ="tic-shop.com.mx";
				$bdd="ticshopc_tienda";

				$this->dbh = new PDO("mysql:host=$servidor;dbname=$bdd", $mysqluser, $mysqlpass);
        self::set_names();
			}
			catch(PDOException $e){
        die();
				return "Database access FAILED!";
			}
		}
    private function set_names(){
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
    public function genera_random($length = 24) {
      self::set_names();
      try{
        $ip=self::getRealIP();
        
        $_SESSION['idsess']="";

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
      <input class='form-control' type='text' id='usuario' name='usuario' value='' onchange='md5pass()' >
      <input class='form-control' type='text' id='password' name='password' value='' onchange='md5pass()'>
    </div>
  </form>
