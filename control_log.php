<?php
  session_start();
  date_default_timezone_set("America/Mexico_City");

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
				////////////////////////////los id y name de los input de login son variantes por lo que si no existen quiere decir que el usuario intento hackear y por lo tanto se banea la IP
				$metodo=$_SERVER['REQUEST_METHOD'];
				$keys=array_keys($_REQUEST);
				$uno=$keys[0];
				$dos=$keys[1];

        $user=trim($_REQUEST[$uno]);
				$pass=trim($_REQUEST[$dos]);

        $us_fake=$_REQUEST['usuario'];
        $pa_fake=$_REQUEST['password'];

        if(strlen($uno)<8 or strlen($dos)<8 or strlen($us_fake)>0 or strlen($pa_fake)>0){
          return 0;
        }

				$userPOST=$user;
				$encriptx=md5("tic%pika_$%&/()=").md5(trim($pass));
				$passPOST=hash("sha512",$encriptx);

			  ////////////////////////////////////////////////////////////////////////////
 				$sql="SELECT * FROM clientes where correo=:correo and pass=:pass";
 				$sth = $this->dbh->prepare($sql);
 				$sth->bindValue(":correo",$userPOST);
 				$sth->bindValue(":pass",$passPOST);
 				$sth->execute();
 				$CLAVE=$sth->fetch();
 				if($CLAVE){

 					if($userPOST == $CLAVE['correo'] and $passPOST==$CLAVE['pass']){
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
    public function genera_random($length = 15) {
    	return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
		}
  }

  $db = new daasldjflks();
  echo $db->acceso();
 ?>
