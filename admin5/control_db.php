<?php
	if (!isset($_SESSION)) { session_start(); }
	if (isset($_REQUEST['function'])){$function=$_REQUEST['function'];}	else{ $function="";}
	if (isset($_REQUEST['ctrl'])){$ctrl=$_REQUEST['ctrl'];}	else{ $ctrl="";}

	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	date_default_timezone_set("America/Mexico_City");

	class Tienda{
		public $nivel_personal;
		public $nivel_captura;
		public $derecho=array();
		public $lema;
		public $personas;
		public $arreglo;
		public $limite=300;

		public function __construct(){
			$this->Salud = array();
			date_default_timezone_set("America/Mexico_City");
			$_SESSION['mysqluser']="sagyccom_esponda";
			$_SESSION['mysqlpass']="esponda123$";
			$_SESSION['servidor'] ="sagyc.com.mx";
			$_SESSION['bdd']="sagycrmr_txpika";
			$this->dbh = new PDO("mysql:host=".$_SESSION['servidor'].";dbname=".$_SESSION['bdd']."", $_SESSION['mysqluser'], $_SESSION['mysqlpass']);
			self::set_names();
		}
		public function set_names(){
			return $this->dbh->query("SET NAMES 'utf8'");
		}
		public function acceso(){
			try{
				$userPOST = htmlspecialchars($_REQUEST["userAcceso"]);
				$passPOST=$_REQUEST["passAcceso"];
				$sql="SELECT* FROM usuarios where (usuario=:usuario) and (UPPER(pass)=UPPER(:pass)) and autoriza=1";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(":usuario",$userPOST);
				$sth->bindValue(":pass",$passPOST);
				$sth->execute();

				 if ($sth->rowCount()>0){
					$suma=1;
					$CLAVE=$sth->fetch();
					$_SESSION['autoriza']=1;
					$_SESSION['nombre']=$CLAVE['nombre'];
					$_SESSION['usuario'] = $CLAVE['usuario'];
					$_SESSION['pass'] = $CLAVE['pass'];
					$_SESSION['pagnivel']=40;
					$_SESSION['idpersona']=$CLAVE['idpersona'];

					//$fondo=mysqli_fetch_array(mysqli_query($link,"select * from config_fondo where idfondo='".$CLAVE['idfondo']."'"));
					//$_SESSION['fondo']=$fondo['fondo'];
					$_SESSION['anio']=date("Y");
					$_SESSION['mes']=date("m");
					$_SESSION['dia']=date("d");
					$_SESSION['hasta']=2016;
					$_SESSION['foco']=mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));
					$_SESSION['cfondo']="white";
					$arr=array();
					$arr=array('acceso'=>1,'idpersona'=>$_SESSION['idpersona']);
					return json_encode($arr);
				}
				else {
					$arr=array();
					$arr=array('acceso'=>0,'idpersona'=>0);
					return json_encode($arr);
				}
				return $obj;
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function login(){
			$arreglo=array();
			if(isset($_SESSION['idpersona']) and $_SESSION['autoriza'] == 1) {
				///////////////////////////sesion abierta
				$valor=$_SESSION['idfondo'];
				$x="";
				$x.="<nav class='navbar navbar-expand-sm navbar-dark fixed-top bg-dark' style='opacity:1;'>";
					$x.="<button class='btn btn-outline-secondary' type='button' id='sidebarCollapse'>";
						$x.="<span class='navbar-toggler-icon'></span>";
					$x.="</button>";
					$x.="<img src='img/escudo.png' width='40' height='30' alt=''>";
					$x.="<img src='img/SSH.png' width='40' height='30' alt=''>";
					$x.="<a class='navbar-brand' href='#escritorio/dashboard' style='font-size:10px'>Sistema Administrativo de Salud Pública</a>";

					$x.="<button class='navbar-toggler navbar-toggler-right' type='button' data-toggle='collapse' data-target='#principal' aria-controls='principal' aria-expanded='false' aria-label='Toggle navigation'>";
						$x.="<i class='fab fa-rocketchat'></i>";
					$x.="</button>";

					$x.="<div class='collapse navbar-collapse' id='principal'>";
						$x.="<ul class='navbar-nav mr-auto'>";
							$x.="<li class='nav-item dropdown'>";
								$x.="<a class='nav-link navbar-brand' href='#' id='navbarDropdown' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'></a>";
							$x.="</li>";
						$x.="</ul>";
						$x.="<ul class='nav navbar-nav navbar-right' id='notificaciones'></ul>";

						$x.="<ul class='nav navbar-nav navbar-right' id='chatx'>";
							$x.="<li class='nav-item dropdown'>";
								$x.= "<a class='nav-link dropdown-toggle' id='navbarDropdown' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
									<i class='fab fa-rocketchat fa-spin' style='color:#96ff57 !important;'></i> Chat";
								$x.= "</a>";

								$x.= "<div id='myUL' class='dropdown-menu' aria-labelledby='navbarDropdown' style='width:200px;max-height:400px !important; overflow: scroll; overflow-x: hidden;'>";
								$x.="<div class='row'><div class='col-12'><input type='text' id='myInput' placeholder='Buscar..' title='Buscar' class='form-control' autocomplete='off'></div></div>";
									$x.="<div id='conecta_x'>";
									$x.= "</div>";
								$x.= "</div>";
							$x.= "</li>";
						$x.="</ul>";

						$x.="<ul class='nav navbar-nav navbar-right' id='fondo'></ul>";
						$x.="<ul class='nav navbar-nav navbar-right'>";
							$x.="<li class='nav-item dropdown'>";
								$x.="<a class='nav-link dropdown-toggle' href='#' id='navbarDropdown' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>";

									$x.=" <img src='a_personal/Screenshot_1.png' alt='Cuenta' class='rounded-circle' width='20px' height='20px'>";
									$x.=$_SESSION['nombre'];
								$x.="</a>";
								$x.="<div class='dropdown-menu' aria-labelledby='navbarDropdown'>";
									$x.="<a class='dropdown-item' id='winmodal_pass' data-id='".$_SESSION['idpersona']."' data-lugar='a_personal/form_pass' title='Cambiar contraseña' ><i class='fas fa-key'></i>Contraseña</a>";

								$x.="</div>";
							$x.="</li>";
						$x.="</ul>";
						$x.="<ul class='nav navbar-nav navbar-right'>";
							$x.="<li class='nav-item'>";
								$x.="<a class='nav-link pull-left' onclick='salir()'>";
									$x.="<i class='fas fa-door-open' style='color:red;'></i>Salir";
								$x.="</a>";
							$x.="</li>";
						$x.="</ul>";
					$x.="</div>";
				$x.="</nav>";

				$y="";
				/////////////fin del header
				$y.="<div class='wrapper'>";
					$y.="<div class='content navbar-default'>";
						$y.="<div class='container-fluid'>";
							$y.="<div class='sidebar sidenav' id='navx'>";
								$y.="<a href='#escritorio/dashboard' class='activeside'><i class='fas fa-home'></i> <span>Inicio</span></a>";
								$y.="<a href='#a_productos/index' title='Productos'><i class='fas fa-users '></i> <span>Productos</span></a>";				/////////////// listo
							$y.="</div>";
						$y.="</div>";
						$y.="<div class='fijaproceso main' id='contenido'>";
						$y.="</div>";
					$y.="</div>";
				$y.="</div>";
				
				$admin=0;
				$arreglo=array('sess'=>"abierta", 'fondo'=>$valor, 'header'=>$x, 'cuerpo'=>$y, 'admin'=>$admin);
				///////////////////////////fin sesion abierta
			}
			else {
				///////////////////////////login
				$valor=$_SESSION['idfondo'];
				$x="<form id='acceso' action=''>
						<div class='container'>
								<center><img src='img/SSH.png' width='250px'></center>
								<p class='input_title'>Usuario o correo:</p>
								<div class='form-group input-group'>
									<div class='input-group-prepend'>
										<span class='input-group-text'> <i class='fas fa-user-circle'></i> </span>
									</div>
									<input class='form-control' placeholder='Introduzca usuario o correo' type='text'  id='userAcceso' name='userAcceso' required>
								</div>
								<p class='input_title'>Contraseña:</p>
								<div class='form-group input-group'>
									<div class='input-group-prepend'>
										<span class='input-group-text'> <i class='fa fa-lock'></i> </span>
									</div>
									<input class='form-control' placeholder='Contraseña' type='password'  id='passAcceso' name='passAcceso' required>
								</div>
								<button class='btn btn-secondary btn-block' type='submit'><i class='fa fa-check'></i>Aceptar</button>
								<button class='btn btn-secondary btn-block' type='button' id='recuperar'><i class='fas fa-key'></i>Recuperar contraseña</button>
								<center>http://spublicahgo.ddns.net</center>
						</div>
					</form>";
				$arreglo=array('sess'=>"cerrada", 'fondo'=>$valor, 'carga'=>$x);
				//////////////////////////fin login
			}
			return json_encode($arreglo);
		}
		public function salir(){
			$_SESSION['autoriza'] = 0;
			$_SESSION['idpersona']="";
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

		public function borrar($DbTableName, $key,$id){
			try{
				self::set_names();
				$sql="delete from $DbTableName where $key=$id";
				$this->dbh->query($sql);
				return 1;
			}
			catch(PDOException $e){
				return "------->$sql <------------- Database access FAILED!".$e->getMessage();
			}
		}
		public function general($sql){
			try{
				self::set_names();
				$sth = $this->dbh->prepare($sql);
				$sth->execute();
				return $sth->fetchAll();
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
?>
