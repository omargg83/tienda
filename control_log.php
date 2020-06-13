<?php
  session_start();
  date_default_timezone_set("America/Mexico_City");
  echo "hola mundo";
  

/*
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

				////////////////////////////los id y name de los input de login son variantes
				$metodo=$_SERVER['REQUEST_METHOD'];
				$keys=array_keys($_REQUEST);
				$uno=$keys[0];
				$dos=$keys[1];

        $user=trim($_REQUEST[$uno]);
				$pass=trim($_REQUEST[$dos]);

        if(strlen($uno)<8 or strlen($dos)<8 or strlen($user)>0 or strlen($pass)>0){
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

  $db = new daasldjflks();
  echo $db->acceso();
*/

 ?>
