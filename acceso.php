<?php
	require_once("control_db.php");
	$db = new Tienda();
	$correo="";
	$pass="";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>TIC SHOP</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="TicShop la tienda de tecnología mas grande de México">
<meta name="keywords" content="Electrónica,Tecnología,Hardware,Software,Tarjetas Video,Tarjetas Madre,Procesadores,Tienda de tecnología,Tienda de tecnología online,Electronicos en linea" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="/styles/bootstrap4/bootstrap.min.css">
<link href="/plugins/fontawesome-free-5.0.1/css/fontawesome-all.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="/styles/main_styles.css">
<link rel="stylesheet" type="text/css" href="/styles/cart_styles.css">
<link rel="stylesheet" type="text/css" href="/styles/cart_responsive.css">
</head>

<body>

<div class="super_container">

	<!-- Header -->
	<header class="header">
		<?php
			include "a_header.php";
		?>
	</header>
	<?php

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

				$_SESSION['idcli_sess']="";
				$_SESSION['autocli_sess']=0;
				$_SESSION['fecha']=date("YmdHis");

				$clave=md5("tic%pika_$%&/()=".$ip);
				$clave=hash("sha512",$clave);
				$_SESSION['iduser']=$clave;
				$_SESSION['numero']=0;

				$in=md5(substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 16));
				$pin=md5(substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 16));

				return array($in,$pin);
			}
			catch(PDOException $e){
				echo "Database access FAILED!";
			}
		}


	}

	$loginx = new Login();
	$ar=$loginx->genera_random();
	$a=$ar[0];
	$b=$ar[1];

	?>
	<!-- Cart -->
	<form id='logintix' action=''>
		<div class="cart_section">
			<div class="container">
				<div class="row">
					<div class="col-12" >
						<div class="col-4 offset-4 text-center" style="min-width: 300px;display: table;margin: 0 auto;">
							<label>Correo</label>
							<input type="text" class="form-control" id="<?php echo $a; ?>" name='<?php echo $a; ?>' placeholder="Correo" value="" required>
						</div>
						<div class="col-4 offset-4 text-center" style="min-width: 300px;display: table;margin: 0 auto;">
							<label>Contraseña</label>
							<input type="password" class="form-control" id="<?php echo $b; ?>" name='<?php echo $b; ?>' placeholder="Contraseña" value="" required>
						</div>
						<div class="col-4 offset-4 text-center" style="min-width: 300px;display: table;margin: 0 auto;"><br>
							<button class="btn btn-outline-primary btn-block btn-sm" type="submit" style="
							    background-color: #b4f22f;
							    border: none;
							    color: black;
							    cursor: pointer;
							"><i class="fa fa-check"></i>Aceptar</button>
							<br>
							<p><a href='/recuperar/'>¿Olvidaste tu contraseña?</a></p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id='registro' style='display:none'>
      <input class='form-control' type='text' id='usuario' name='usuario' value='' >
      <input class='form-control' type='text' id='password' name='password' value=''>
    </div>
	</form>


	<!-- Footer -->
	<footer class="footer">
		<?php
			include "a_footer.php";
		?>
	</footer>

	<!-- Copyright -->
	<div class="copyright">
		<?php
			include "a_copyright.php";
		?>
	</div>

</div>

<script src="/js/jquery-3.3.1.min.js"></script>
<script src="/styles/bootstrap4/popper.js"></script>
<script src="/styles/bootstrap4/bootstrap.min.js"></script>
<script src="/plugins/greensock/TweenMax.min.js"></script>
<script src="/plugins/greensock/TimelineMax.min.js"></script>
<script src="/plugins/scrollmagic/ScrollMagic.min.js"></script>
<script src="/plugins/greensock/animation.gsap.min.js"></script>
<script src="/plugins/greensock/ScrollToPlugin.min.js"></script>
<script src="/plugins/easing/easing.js"></script>
<script src="/js/cart_custom.js"></script>

<!--   Alertas   -->
<script src="/librerias15/swal/dist/sweetalert2.min.js"></script>
<link rel="stylesheet" href="/librerias15/swal/dist/sweetalert2.min.css">

<script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
<script src="/sagyc.js"></script>

</body>

</html>
