<?php
	if (!isset($_SESSION)) { session_start(); }
	if (isset($_REQUEST['function'])){$function=$_REQUEST['function'];}	else{ $function="";}
	if (isset($_REQUEST['ctrl'])){$ctrl=$_REQUEST['ctrl'];}	else{ $ctrl="";}

	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	date_default_timezone_set("America/Mexico_City");

	class Sagyc{
		public $nivel_personal;
		public $nivel_captura;
		public $derecho=array();
		public $lema;
		public $personas;
		public $arreglo;
		public $limite=300;

		public function __construct(){
			date_default_timezone_set("America/Mexico_City");
			$_SESSION['mysqluser']="sagyccom_esponda";
			$_SESSION['mysqlpass']="esponda123$";
			$_SESSION['servidor'] ="sagyc.com.mx";
			$_SESSION['bdd']="sagycrmr_txpika";
			$this->dbh = new PDO("mysql:host=".$_SESSION['servidor'].";dbname=".$_SESSION['bdd']."", $_SESSION['mysqluser'], $_SESSION['mysqlpass']);
			$_SESSION['n_sistema']="Protecta SNTE";
			self::set_names();
		}
		public function set_names(){
			return $this->dbh->query("SET NAMES 'utf8'");
		}
		public function login(){
			$arreglo=array();
			if(isset($_SESSION['idpersona']) and $_SESSION['autoriza'] == 1) {
				///////////////////////////sesion abierta
				$valor=$_SESSION['idfondo'];
				$x="";
				$x.="<nav class='navbar navbar-expand-sm navbar-dark fixed-top bg-dark'>";
					$x.="<img src='img/logo_.png' width='40' height='30'>";
					$x.="<img src='img/casa.png' width='40' height='30'>";
					$x.="<img src='img/auto.png' width='40' height='30'>";

					$x.="<a class='navbar-brand' href='#'>Protecta SNTE</a>";

				  $x.="<button class='navbar-toggler' type='button' data-toggle='collapse' data-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>
				    <span class='navbar-toggler-icon'></span>
				  </button>

				  <div class='collapse navbar-collapse' id='navbarSupportedContent'>
				    <ul class='navbar-nav mr-auto'>
				      <li class='nav-item active'>
				        <a class='nav-link' href='#'>Home <span class='sr-only'>(current)</span></a>
				      </li>
				      <li class='nav-item'>
				        <a class='nav-link' href='#'>Link</a>
				      </li>
				      <li class='nav-item dropdown'>
				        <a class='nav-link dropdown-toggle' href='#' id='navbarDropdown' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
				          Dropdown
				        </a>
				        <div class='dropdown-menu' aria-labelledby='navbarDropdown'>
				          <a class='dropdown-item' href='#'>Action</a>
				          <a class='dropdown-item' href='#'>Another action</a>
				          <div class='dropdown-divider'></div>
				          <a class='dropdown-item' href='#'>Something else here</a>
				        </div>
				      </li>
				      <li class='nav-item'>
				        <a class='nav-link disabled' href='#' tabindex='-1' aria-disabled='true'>Disabled</a>
				      </li>
				    </ul>
				    <form class='form-inline my-2 my-lg-0'>";

						$x.="<a class='nav-link pull-left' onclick='salir()'>";
							$x.="<i class='fas fa-door-open' style='color:red;'></i>Salir";
						$x.="</a>";

				    $x.="</form>
				  </div>
				</nav>";

				/*
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
				*/
				$y="";
				/////////////fin del header
				$y.="<div class='wrapper'>";
					$y.="<div class='content navbar-default'>";
						$y.="<div class='fijaproceso main' id='contenido'>";
						$y.="</div>";
					$y.="</div>";
				$y.="</div>";
				$arreglo=array('sess'=>"abierta", 'fondo'=>$valor, 'header'=>$x, 'cuerpo'=>$y);
				///////////////////////////fin sesion abierta
			}
			else {
				///////////////////////////login
				$valor=$_SESSION['idfondo'];
				$x="<form id='acceso' action=''>
						<div class='container'>
								<center><img src='img/logo_.png' width='250px'></center>
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
								<center>(Ver.2) SAGYC.COM.MX</center>
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
		public function acceso(){
			$userPOST = htmlspecialchars($_REQUEST["userAcceso"]);
			$passPOST=md5($_REQUEST["passAcceso"]);
			$sql="SELECT nombre, usuario, pass, idpersona FROM usuarios where (usuario=:usuario) and (UPPER(pass)=UPPER(:pass)) and autoriza=1";
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
			/*


			self::set_names();
			$sql="SELECT nombre, usuario, pass, nick, estudio, idpersona, file_foto, personal.idarea, idcargo, correo, personal_orga.cargo, personal_orga.parentid, personal_orga.orden as puesto, idfondo FROM personal left outer join personal_orga on personal_orga.id=personal.idcargo where (usuario=:usuario or correo=:correo) and pass=:pass and autoriza=1";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(":usuario",$userPOST);
			$sth->bindValue(":correo",$userPOST);
			$sth->bindValue(":pass",$passPOST);
			$sth->execute();
			$CLAVE=$sth->fetch();

			if(is_array($CLAVE)){
				if(($userPOST == $CLAVE['usuario'] or $userPOST == $CLAVE['correo']) and strtoupper($passPOST)==strtoupper($CLAVE['pass'])){
					$_SESSION['autoriza']=1;
					$_SESSION['nombre']=$CLAVE['nombre'];
					$_SESSION['usuario'] = $CLAVE['usuario'];
					$_SESSION['pass'] = $CLAVE['pass'];
					$_SESSION['puesto']=$CLAVE['puesto'];
					$_SESSION['cargo']=$CLAVE['cargo'];

					$superior = $this->superior($CLAVE['parentid']);
					$_SESSION['superior']=$superior['idpersona'];

					$_SESSION['idfondo']=$CLAVE['idfondo'];
					$_SESSION['nick']=$CLAVE['nick'];
					$_SESSION['estudio']=$CLAVE['estudio'];
					$_SESSION['idpersona']=$CLAVE['idpersona'];
					$_SESSION['idarea']=$CLAVE['idarea'];
					$_SESSION['foto']=$CLAVE['file_foto'];
					$_SESSION['cargo']=$CLAVE['cargo'];

					$fecha=date("Y-m-d");
					list($anyo,$mes,$dia) = explode("-",$fecha);

					$lema = $this->lema($mes,$anyo);
					$_SESSION['lema']=$lema['lema'];

					$_SESSION['avatar']="librerias/img/1220140826130455.png";
					$_SESSION['n_sistema']="Salud";

					$centro = $this->centro($CLAVE['idarea']);
					$_SESSION['idcentro']=$centro['idcentro'];

					$_SESSION['cfondo']="white";
					$_SESSION['anio']=date("Y");
					$_SESSION['mes']=date("m");
					$_SESSION['dia']=date("d");
					$_SESSION['nocuenta'] = 1;
					if($CLAVE['idpersona']==7){
						$_SESSION['administrador']=1;
					}
					else{
						$_SESSION['administrador']=0;
					}
					$_SESSION['hasta']=2020;
					$_SESSION['foco']=mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));
					$_SESSION['cfondo']="white";
					$arr=array();
					$arr=array('acceso'=>1,'idpersona'=>$_SESSION['idpersona']);
					return json_encode($arr);
				}
			}
			else {
				$arr=array();
				$arr=array('acceso'=>0,'idpersona'=>0);
				return json_encode($arr);
			}
			*/
		}

		public function insert($DbTableName, $values = array()){
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
				$sth->execute();
				return $this->lastId = $this->dbh->lastInsertId();
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function update($DbTableName, $id = array(), $values = array()){
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
					return "$idx";
				}
				else{
					return "error";
				}
			}
			catch(PDOException $e){
				return "------->$sql2 <------------- Database access FAILED!".$e->getMessage();
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
		public function superior($id){
			try{
				self::set_names();
				$sql="SELECT * from personal where idcargo=:id";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(":id",$id);
				$sth->execute();
				return $sth->fetch();
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function permiso($idpersona){
			try{
				self::set_names();
				$sql="select * from personal_permiso where idpersona=:id";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(":id",$idpersona);
				$sth->execute();
				return $sth->fetch();
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
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
		public function lema($mes,$anyo){
			try{
				self::set_names();
				$sql="SELECT lema from lemas where mes=:mes and anio=:anio";
				$sth = $this->dbh->prepare($sql);
	 			$sth->bindValue(":mes",$mes);
	 			$sth->bindValue(":anio",$anyo);
	 			$sth->execute();
	 			return $sth->fetch();
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}

		public function centro_costos($idcentro){
			try{
				self::set_names();
				if(strlen($idcentro)>0){
					$sql="SELECT * FROM cord_che_centro_costos where idcentro='$idcentro'";
				}
				else{
					$sql="SELECT * FROM cord_che_centro_costos";
				}
				$sth = $this->dbh->prepare($sql);
				$sth->execute();
				return $sth->fetchAll();
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function centro($idarea){
			try{
				self::set_names();
				$sql="select titulo, idcentro, area, estado from area where idarea=:id";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(":id",$idarea);
				$sth->execute();
				return $sth->fetch();
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}

		public function personal(){
			try{
				self::set_names();
				$sql="SELECT personal.idpersona,personal.nombre, area.idarea, area.area FROM personal left outer join area on area.idarea=personal.idarea left outer join personal_orga on personal_orga.id=personal.idcargo where personal.idarea<100 order by area.orden,personal_orga.orden,personal.idpersona";
				$sth = $this->dbh->prepare($sql);
				$sth->execute();
				return $sth->fetchAll();
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function personal_edit($id){
			try{
				self::set_names();
				$sql="SELECT personal.*,personal_orga.cargo from personal left outer join personal_orga on personal_orga.id=personal.idcargo where personal.idpersona=:id";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(":id",$id);
				$sth->execute();
				return $sth->fetch();
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function area($id){
			try{
				self::set_names();
				$sql="select * from area where idarea=:id";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(":id",$id);
				$sth->execute();
				return $sth->fetch();
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}

		public function area_p(){
			try{
				self::set_names();
				$sql="select idarea,area from area where tipo<4 or idarea=150";
				$sth = $this->dbh->prepare($sql);
				$sth->execute();
				return $sth->fetchAll();
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function area_ver($nivel){
			try{
				self::set_names();
				if ($nivel==0){
					$sql="select * from area order by area.orden";
				}
				if ($nivel==1){
					$sql="select * from area where tipo<=3 order by area.orden";
				}
				if ($nivel==2){
					$sql="select * from area where idarea='".$_SESSION['idarea']."' or sub='".$_SESSION['idarea']."'";
					$areas=$this->general($sql);
					$listaar="";
					for($i=0;$i<count($areas);$i++){
						$listaar.=$areas[$i]['idarea'].",";
					}
					$listaar=substr($listaar,0,strlen($listaar)-1);
					$sql="select * from area where idarea in($listaar) and tipo<=3 and idarea<100 order by area.orden";
				}
				if ($nivel==10 or $nivel==9 or $nivel==3){
					$sql="select * from area where idarea='".$_SESSION['idarea']."' and tipo<=3 order by area.orden";
				}
				if ($nivel==4){
					$sql = "select * from area where area.idcentro='".$_SESSION['idcentro']."' and tipo<=3 order by area.orden";
				}
				$sth = $this->dbh->prepare($sql);
				$sth->execute();
				return $sth->fetchAll();
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}

		}
		public function mes($mes,$key=""){
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
			if($key==1){
				$mes=substr($mes,0,3);
			}
			return $mes;
		}

		public function color($id){
			try{
				self::set_names();
				$sql="SELECT * from colores where idcolor=:id";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(":id",$id);
				$sth->execute();
				return $sth->fetch();
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function colores(){
			try{
				self::set_names();
				$sql="select * from colores";
				$sth = $this->dbh->prepare($sql);
				$sth->execute();
				return $sth->fetchAll();
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}
		public function letranum($numero){

				if($numero>0){
				$var=($numero)*100;
				$tmp=strlen($var);
				$tmp2=substr($var,$tmp-2,2);
				$entero=substr($var,0,$tmp-2);

				$num=$entero;
				$fem = true;
				$dec = true;
				$matuni[2]  = "dos";
				$matuni[3]  = "tres";
				$matuni[4]  = "cuatro";
				$matuni[5]  = "cinco";
				$matuni[6]  = "seis";
				$matuni[7]  = "siete";
				$matuni[8]  = "ocho";
				$matuni[9]  = "nueve";
				$matuni[10] = "diez";
				$matuni[11] = "once";
				$matuni[12] = "doce";
				$matuni[13] = "trece";
				$matuni[14] = "catorce";
				$matuni[15] = "quince";
				$matuni[16] = "dieciseis";
				$matuni[17] = "diecisiete";
				$matuni[18] = "dieciocho";
				$matuni[19] = "diecinueve";
				$matuni[20] = "veinte";
				$matunisub[2] = "dos";
				$matunisub[3] = "tres";
				$matunisub[4] = "cuatro";
				$matunisub[5] = "quin";
				$matunisub[6] = "seis";
				$matunisub[7] = "sete";
				$matunisub[8] = "ocho";
				$matunisub[9] = "nove";

				$matdec[2] = "veint";
				$matdec[3] = "treinta";
				$matdec[4] = "cuarenta";
				$matdec[5] = "cincuenta";
				$matdec[6] = "sesenta";
				$matdec[7] = "setenta";
				$matdec[8] = "ochenta";
				$matdec[9] = "noventa";
				$matsub[3]  = 'mill';
				$matsub[5]  = 'bill';
				$matsub[7]  = 'mill';
				$matsub[9]  = 'trill';
				$matsub[11] = 'mill';
				$matsub[13] = 'bill';
				$matsub[15] = 'mill';
				$matmil[4]  = 'millones';
				$matmil[6]  = 'billones';
				$matmil[7]  = 'de billones';
				$matmil[8]  = 'millones de billones';
				$matmil[10] = 'trillones';
				$matmil[11] = 'de trillones';
				$matmil[12] = 'millones de trillones';
				$matmil[13] = 'de trillones';
				$matmil[14] = 'billones de trillones';
				$matmil[15] = 'de billones de trillones';
				$matmil[16] = 'millones de billones de trillones';

				$num = trim((string)@$num);
				if ($num[0] == '-') {
					$neg = 'menos ';
					$num = substr($num, 1);
				}else
					$neg = '';
				while ($num[0] == '0') $num = substr($num, 1);
				if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num;
				$zeros = true;
				$punt = false;
				$ent = '';
				$fra = '';
				for ($c = 0; $c < strlen($num); $c++) {
					$n = $num[$c];
					if (! (strpos(".,'''", $n) === false)) {
						if ($punt) break;
						else{
							$punt = true;
						continue;
						}
					}elseif (! (strpos('0123456789', $n) === false)) {
						if ($punt) {
							if ($n != '0') $zeros = false;
							$fra .= $n;
						}else
						$ent .= $n;
					}else
					break;
				}
				$ent = '     ' . $ent;
				if ($dec and $fra and ! $zeros) {
					$fin = ' coma';
					for ($n = 0; $n < strlen($fra); $n++) {
						if (($s = $fra[$n]) == '0')
							$fin .= ' cero';
						elseif ($s == '1')
							$fin .= $fem ? ' un' : ' un';
						else
							$fin .= ' ' . $matuni[$s];
					}
				}else
					$fin = '';
					if ((int)$ent === 0) return 'Cero ' . $fin;
					$tex = '';
					$sub = 0;
					$mils = 0;
					$neutro = false;
					while ( ($num = substr($ent, -3)) != '   ') {
						$ent = substr($ent, 0, -3);
						if (++$sub < 3 and $fem) {
							$matuni[1] = 'un';
							$subcent = 'os';
						}else{
							$matuni[1] = $neutro ? 'un' : 'uno';
							$subcent = 'os';
						}
					$t = '';
					$n2 = substr($num, 1);

					if ($n2 == '00') {
					}elseif ($n2 < 21)
						$t = ' ' . $matuni[(int)$n2];
					elseif ($n2 < 30) {
						$n3 = $num[2];
						if ($n3 != 0) $t = 'i' . $matuni[$n3];
						$n2 = $num[1];
						$t = ' ' . $matdec[$n2] . $t;
					}else{
						$n3 = $num[2];
						if ($n3 != 0) $t = ' y ' . $matuni[$n3];
						$n2 = $num[1];
						$t = ' ' . $matdec[$n2] . $t;
					}
					$n = $num[0];
					if ($n == 1) {
						$t = ' ciento' . $t;
					}elseif ($n == 5){
						$t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t;
					}elseif ($n != 0){
						$t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t;
					}
					if ($sub == 1) {
					}elseif (! isset($matsub[$sub])) {
						if ($num == 1) {
							$t = ' mil';
						}elseif ($num > 1){
							$t .= ' mil';
						}
					}elseif ($num == 1) {
						$t .= ' ' . $matsub[$sub] . '?n';
					}elseif ($num > 1){
						$t .= ' ' . $matsub[$sub] . 'ones';
					}
					if ($num == '000') $mils ++;
					elseif ($mils != 0) {
						if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub];
						$mils = 0;
					}
					$neutro = true;
					$tex = $t . $tex;
				}
				$tex = $neg . substr($tex, 1) . $fin;
				$letra= ucfirst($tex)." pesos ".$tmp2;
				$letra =$letra."/100 M.N";
				return $letra;
			}
			else {
				return "";
			}
		}
		public function partidas(){
			try{
				self::set_names();
				$sql="SELECT * FROM cord_che_partidas where activa=1 order by partida";
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
			$this->update('personal',array('idpersona'=>$_SESSION['idpersona']), array('idfondo'=>$_SESSION['idfondo']));
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
		public function anioc(){
			$_SESSION['anio']=$_REQUEST['id'];
			return "Se establecio ".$_SESSION['anio']." como año de trabajo";
		}

		public function notificarx(){
			$x="";
			if($_SESSION['idpersona']==710 or $_SESSION['idpersona']==7){

				if(date("H")>=15 and date("H")<16){

					$sql="SELECT count(yoficiosp.idoficiop) as total, personal.idpersona, personal.nombre, personal.correo FROM yoficiosp
					left outer join personal on personal.idpersona=yoficiosp.idpersturna
					where email=0 and idpersona!=334 and LENGTH(personal.correo)>0 group by personal.idpersona limit 1";
					$ofi=$this->general($sql);
					if(count($ofi)>0){
						if(strlen($ofi[0]['correo'])>0){
								$correo=$ofi[0]['correo'];
								$x=$correo;
								$texto="";
								$texto.="Buenos dias/tardes: ".$ofi[0]['nombre']."<br>";
								$sql="select * from yoficiosp
								left outer join yoficios on yoficios.idoficio=yoficiosp.idoficio
								where idpersturna='".$ofi[0]['idpersona']."' and email=0";
								$res=$this->general($sql);
								$texto.="Sistema de correspondencia<br><br>";
								$texto.="Se han turnado los siguientes oficios en el sistema de correspondencia:<br><br>";
								$texto.="<table style='border:1px solid; border-collapse: collapse;'>";
								$texto.="<tr><th style='border: 1px solid;'>Número interno</th><th style='border: 1px solid;'>Número de oficio</th><th style='border: 1px solid;'>Asunto</th></tr>";
								foreach($res AS $key){
									$texto.= "<tr>";

									$texto.= "<td style='border: 1px solid;'><center>";
									$texto.= $key['numero'];
									$texto.= "</center></td>";

									$texto.= "<td style='border: 1px solid;'>";
									$texto.= $key['numoficio'];
									$texto.= "</td>";

									$texto.= "<td style='border: 1px solid;'>";
									$texto.= $key['remitente']."<br>";
									$texto.= $key['asunto'];
									$texto.= "</td>";

									$texto.= "</tr>";
									$arreglo=array();
									$arreglo=array("email"=>1);
									$arreglo+=array("fechamail"=>date("Y-m-d H:i:s"));
									$this->update('yoficiosp',array('idoficiop'=>$key['idoficiop']), $arreglo);
								}
								$texto.="</table><br>";

								$texto.="http://172.16.0.20/  <br>";
								$texto.="http://spublicahgo.ddns.net/  <br>";

								$sql="select * from yoficiosp left outer join yoficios on yoficios.idoficio=yoficiosp.idoficio where idpersturna='".$ofi[0]['idpersona']."' and contesto=0";
								$res=$this->general($sql);
								$texto.="<br>Otros oficios pendientes";
								$texto.="<table style='border:1px solid; border-collapse: collapse;'>";
								$texto.="<tr><th style='border: 1px solid;'>Número interno</th><th style='border: 1px solid;'>Número de oficio</th><th style='border: 1px solid;'>Asunto</th></tr>";
								foreach($res AS $key){
									$texto.= "<tr>";

									$texto.= "<td style='border: 1px solid;'><center>";
									$texto.= $key['numero'];
									$texto.= "</center></td>";

									$texto.= "<td style='border: 1px solid;'>";
									$texto.= $key['numoficio'];
									$texto.= "</td>";

									$texto.= "<td style='border: 1px solid;'>";
									$texto.= $key['remitente']."<br>";
									$texto.= $key['asunto'];
									$texto.= "</td>";

									$texto.= "</tr>";
								}
								$texto.="</table><br><br><br>";

								$texto.="-----Correo generado automáticamente favor de no repondender-----<br>";
								$texto.="Cualquier duda o aclaración enviar correo a la dirección omargg83@gmail.com";
								$texto.="Gracias";

								///////////////////////////////////////
								require 'librerias15/PHPMailer-5.2-stable/PHPMailerAutoload.php';
								$mail = new PHPMailer;
								$mail->isSMTP();                                      // Set mailer to use SMTP
								$mail->Host = "smtp.gmail.com";						  // Specify main and backup SMTP servers
								$mail->SMTPAuth = true;                               // Enable SMTP authentication
								$mail->Username = "sistema.subsaludpublicahgo@gmail.com";       // SMTP username
								$mail->Password = "TEUFEL123";                       // SMTP password
								$mail->SMTPSecure = "ssl";                            // Enable TLS encryption, `ssl` also accepted
								$mail->Port = 465;                                    // TCP port to connect to
								$mail->CharSet = 'UTF-8';

								$mail->From = "sistema.subsaludpublicahgo@gmail.com";
								$mail->FromName = "Sistema Administrativo de Salud Pública";
								$mail->Subject = "Correspondencia";
								$mail->AltBody = "Correspondencia";
								$mail->addAddress($correo);     // Add a recipient
								//$mail->addCC('omargg83@gmail.com');

								$mail->isHTML(true);                                  // Set email format to HTML

								$mail->Body    = $texto;
								$mail->AltBody = "Recuperar contraseña";

								if(!$mail->send()) {
										$x.= 'Message could not be sent.';
										$x.= 'Mailer Error: ' . $mail->ErrorInfo;
								} else {
										$x= ' Se envío enlace a su correo';
								}
								///////////////////////////////////////
								$arr=array('texto'=>$correo.$x,'correo'=>1);
								return json_encode($arr);
						}
					}
				}
				else{
					$correo="algo";
					$x.="hola mundo ".$correo;
					$arr=array('texto'=>$x,'correo'=>0);
					return json_encode($arr);
				}
			}
		}
		public function correo(){
			$sql="SELECT * from personal where idpersona='".$_SESSION['idpersona']."'";
			$ofi=$this->general($sql);
			if(count($ofi)>0){
				if(strlen($ofi[0]['correo'])>0){
					return "";
				}
				else{
					return "correo";
				}
			}
			else{
				return "session";
			}
		}
		public function alertas(){
			self::set_names();
			try{
				$x="";
				$arr=array();
				$entra=0;
				$numero="";
				$id="";
				$nombre="";
				$idoficio="";
				$tipo=0;
				$asunto="";
				$idpersona="";
				$sql="select yoficios_solicitud.id,yoficios_solicitud.fecha, yoficios_solicitud.idoficio, personal.idpersona, personal.nombre, yoficios.numero, yoficios.asunto FROM yoficios_solicitud left outer join yoficios on yoficios.idoficio=yoficios_solicitud.idoficio	left outer join personal on personal.idpersona=yoficios_solicitud.idpersona	where yoficios_solicitud.estado=0 limit 1";
				$sth = $this->dbh->prepare($sql);
				$sth->execute();
				$cuenta = $sth->rowCount();

				if($cuenta>0){
					$entra=1;
					$row=$sth->fetch();
					$numero=$row['numero'];
					$id=$row['id'];
					$nombre=$row['nombre'];
					$idoficio=$row['idoficio'];
					$asunto=$row['asunto'];
					$idpersona=$row['idpersona'];
					$tipo=1;
					$x.="Tiene $cuenta oficio(s) por aprobar en correspondencia de entrada";
				}
				else{
					$sql="select yoficiosp_solicitud.id, yoficiosp_solicitud.fecha, yoficiosp_solicitud.idoficio, yoficiosze.numero, personal.idpersona, personal.nombre, yoficiosze.asunto FROM yoficiosp_solicitud left outer join yoficiosze on yoficiosze.idoficio=yoficiosp_solicitud.idoficio left outer join personal on personal.idpersona=yoficiosp_solicitud.idpersona where yoficiosp_solicitud.estado=0 limit 1";
					$sth = $this->dbh->prepare($sql);
					$sth->execute();
					$cuenta = $sth->rowCount();

					if($cuenta>0){
						$entra=1;
						$row=$sth->fetch();
						$numero=$row['numero'];
						$id=$row['id'];
						$nombre=$row['nombre'];
						$idoficio=$row['idoficio'];
						$asunto=$row['asunto'];
						$idpersona=$row['idpersona'];
						$tipo=2;
						$x.="Tiene $cuenta oficio(s) por aprobar en correspondencia de salida";
					}
				}
				$arr=array('texto'=>$x,'entra'=>$entra,'id'=>$id,'numero'=>$numero,'nombre'=>$nombre,'idoficio'=>$idoficio,'tipo'=>$tipo,'asunto'=>$asunto,'idpersona'=>$idpersona);
				return json_encode($arr);
			}
			catch(PDOException $e){
				return "Database access FAILED! ".$e->getMessage();
			}


		}
		public function password_cambia(){
			if (isset($_REQUEST['usuario'])){$usuario=$_REQUEST['usuario'];}
			if (isset($_REQUEST['pass1'])){$pass1=trim($_REQUEST['pass1']);}
			if (isset($_REQUEST['pass2'])){$pass2=trim($_REQUEST['pass2']);}

			if($pass1==$pass2){
				$arreglo=array('pass'=>$pass1);
				$x=$this->update('personal',array('llave'=>$usuario), $arreglo);
				return 1;
			}
			else{
				return "no coincide";
			}
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
		public function recuperar(){
			$x="";
			require 'librerias15/PHPMailer-5.2-stable/PHPMailerAutoload.php';

			$telefono="";
			if (isset($_REQUEST['telefono'])){$texto=$_REQUEST['telefono'];}
			$sql="select idpersona,usuario,correo,correoinstitucional from personal where usuario='$texto' or correo='$texto' or correoinstitucional='$texto'";
			$res=$this->general($sql);
			if(count($res)>0){

				if(strlen($res[0]['correo'])>0){
					$x.=$res[0]['usuario'];
					$mail = new PHPMailer;
					$mail->isSMTP();                                      // Set mailer to use SMTP
					$mail->Host = "smtp.gmail.com";						  // Specify main and backup SMTP servers
					$mail->SMTPAuth = true;                               // Enable SMTP authentication
					$mail->Username = "sistema.subsaludpublicahgo@gmail.com";       // SMTP username
					$mail->Password = "TEUFEL123";                       // SMTP password
					$mail->SMTPSecure = "ssl";                            // Enable TLS encryption, `ssl` also accepted
					$mail->Port = 465;                                    // TCP port to connect to
					$mail->CharSet = 'UTF-8';

					$mail->From = "sistema.subsaludpublicahgo@gmail.com";
					$mail->FromName = "Sistema Administrativo de Salud Pública";
					$mail->Subject = "Recuperar contraseña";
					$mail->AltBody = "Contraseña";
					$mail->addAddress($res[0]['correo']);     // Add a recipient
					$mail->addCC('omargg83@gmail.com');

					$mail->isHTML(true);                                  // Set email format to HTML

					$numero="pass_".rand(1,6666666);
					$arreglo=array("llave"=>$numero);
					$this->update('personal',array('idpersona'=>$res[0]['idpersona']), $arreglo);

					$texto="Recuperar contraseña <b>Contraseña! $x</b>";
					$texto.="<br><a href='http://spublicahgo.ddns.net/acceso/index.php?llave=$numero'>De click para recuperar la contraseña</a>";
					$mail->Body    = $texto;
					$mail->AltBody = "Recuperar contraseña";

					if(!$mail->send()) {
					    $x.= 'Message could not be sent.';
					    $x.= 'Mailer Error: ' . $mail->ErrorInfo;
					} else {
					   	$x= 'Se envío enlace a su correo';
					}
					return $x;
				}
				else{
					return "no tiene correo registrado en la plantilla";
				}

			}
			else{
				return 0;
			}
		}
		public function recuperar2($llave){
			try{
				self::set_names();
				$sql="SELECT * FROM personal where llave=:llave and autoriza=1";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(":llave",$llave);
				$sth->execute();
				$res=$sth->fetch();
				return $res;
			}
			catch(PDOException $e){
				return "Database access FAILED!".$e->getMessage();
			}
		}

		public function json(){
			$correo="algo";
			$x="hola mundo ".$correo;
			$arr=array('texto'=>$x,'correo'=>0);
			return json_encode($arr);
		}
	}

	if(strlen($ctrl)>0){
		$db = new Sagyc();
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
