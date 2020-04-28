<?php
require_once("../control_db.php");
if (isset($_REQUEST['function'])){$function=$_REQUEST['function'];}	else{ $function="";}

class Productos extends Tienda{
	public function __construct(){
		parent::__construct();
		$this->doc1="a_imagen/";
		$this->doc="a_imagenextra/";
	}
	public function productos_lista(){
		try{
			parent::set_names();
			if (isset($_REQUEST['buscar']) and strlen(trim($_REQUEST['buscar']))>0){
				$texto=trim(htmlspecialchars($_REQUEST['buscar']));
				$sql="SELECT * from productos where clave like '%$texto%' or nombre like '%$texto%' or modelo like '%$texto%' or marca like '%$texto%' or idProducto like '%$texto%' or id like '%$texto%' limit 100";
			}
			else{
				$sql="SELECT * from productos order by id desc limit 300";
			}
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll();
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}

	public function productos_destacados(){
		try{
			parent::set_names();
			$sql="SELECT * from productos where cb_destacados=1";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll();
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
	public function productos_semana(){
		try{
			parent::set_names();
			$sql="SELECT * from productos where cb_prodsemana=1";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll();
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
	public function productos_oferta(){
		try{
			parent::set_names();
			$sql="SELECT * from productos where cb_ofertasemana=1";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll();
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}

	public function producto_editar($id){
		try{
			parent::set_names();
			$sql="select * from productos where id=:id";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(':id', "$id");
			$sth->execute();
			return $sth->fetch(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function producto_exist($id,$tipo=0){
		try{
			parent::set_names();
			if($tipo==0){
				$sql="select * from producto_exist where id=$id";
			}
			if($tipo==1){
				$sql="select existencia, almacen from producto_exist where id=$id and almacen='PAC' UNION
					select existencia, almacen from producto_exist where id=$id and almacen!='PAC' group by idProducto";
			}
			if($tipo==2){
				$sql="select sum(existencia) as existencia, almacen from producto_exist where id=$id";
			}
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function producto_espe($id){
		try{
			parent::set_names();
			$sql="select * from producto_espe where id=:id";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(':id', "$id");
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function producto_espe_editar($id){
		try{
			parent::set_names();
			$sql="select * from producto_espe where idespecificacion=:id";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(':id', "$id");
			$sth->execute();
			return $sth->fetch(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}

	public function producto_exist_editar($id){
		try{
			parent::set_names();
			$sql="select * from producto_exist where idexist=:id";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(':id',$id);
			$sth->execute();
			return $sth->fetch(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}

	public function producto_categoria($id){
		try{
			parent::set_names();
			$sql="select * from producto_cat left outer join categorias on categorias.idcategoria=producto_cat.idcategoria where idproducto=:id";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(':id', "$id");
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function guardar_producto(){
		try{
			parent::set_names();
			$id=$_REQUEST['id'];
			$arreglo =array();

			$interno=$_REQUEST['interno'];
			if($interno==1){
				if (isset($_REQUEST['clave'])){
					$arreglo+= array('clave'=>$_REQUEST['clave']);
				}
				if (isset($_REQUEST['idProducto'])){
					$arreglo+= array('idProducto'=>$_REQUEST['idProducto']);
				}
				if (isset($_REQUEST['numParte'])){
					$arreglo+= array('numParte'=>$_REQUEST['numParte']);
				}
				if (isset($_REQUEST['nombre'])){
					$arreglo+= array('nombre'=>$_REQUEST['nombre']);
				}
				if (isset($_REQUEST['descripcion_corta'])){
					$arreglo+= array('descripcion_corta'=>$_REQUEST['descripcion_corta']);
				}
				if (isset($_REQUEST['modelo'])){
					$arreglo+= array('modelo'=>$_REQUEST['modelo']);
				}
				if (isset($_REQUEST['marca'])){
					$arreglo+= array('marca'=>$_REQUEST['marca']);
				}
				if (isset($_REQUEST['categoria'])){
					$arreglo+= array('categoria'=>$_REQUEST['categoria']);
				}
				if (isset($_REQUEST['subcategoria'])){
					$arreglo+= array('subcategoria'=>$_REQUEST['subcategoria']);
				}
				if (isset($_REQUEST['precio'])){
					$arreglo+= array('precio'=>$_REQUEST['precio']);
				}
				if (isset($_REQUEST['moneda'])){
					$arreglo+= array('moneda'=>$_REQUEST['moneda']);
				}
				if (isset($_REQUEST['tipoCambio'])){
					$arreglo+= array('tipoCambio'=>$_REQUEST['tipoCambio']);
				}
				if (isset($_REQUEST['existencia'])){
					$arreglo+= array('existencia'=>$_REQUEST['existencia']);
				}
				if (isset($_REQUEST['precio_tic'])){
					$arreglo+= array('preciof'=>$_REQUEST['precio_tic']);
				}
			}

			if (isset($_REQUEST['descripcion_larga'])){
				$arreglo+= array('descripcion_larga'=>$_REQUEST['descripcion_larga']);
			}
			if (isset($_REQUEST['precio_tic'])){
				$arreglo+= array('precio_tic'=>$_REQUEST['precio_tic']);
			}
			if (isset($_REQUEST['precio_oferta'])){
				$arreglo+= array('precio_oferta'=>$_REQUEST['precio_oferta']);
			}

			if (isset($_REQUEST['envio_costo'])){
				$arreglo+= array('envio_costo'=>$_REQUEST['envio_costo']);
			}
			if (isset($_REQUEST['precio_tipo'])){
				$arreglo+= array('precio_tipo'=>$_REQUEST['precio_tipo']);
			}
			if (isset($_REQUEST['envio_tipo'])){
				$arreglo+= array('envio_tipo'=>$_REQUEST['envio_tipo']);
			}
			if (isset($_REQUEST['cb_ofertasemana']) and strlen($_REQUEST['cb_ofertasemana'])>0){
				$arreglo+= array('cb_ofertasemana'=>$_REQUEST['cb_ofertasemana']);
			}
			else{
				$arreglo+= array('cb_ofertasemana'=>NULL);
			}
			if (isset($_REQUEST['cb_prodsemana']) and strlen($_REQUEST['cb_prodsemana'])>0){
				$arreglo+= array('cb_prodsemana'=>$_REQUEST['cb_prodsemana']);
			}
			else{
				$arreglo+= array('cb_prodsemana'=>NULL);
			}
			if (isset($_REQUEST['cb_destacados']) and strlen($_REQUEST['cb_destacados'])>0){
				$arreglo+= array('cb_destacados'=>$_REQUEST['cb_destacados']);
			}
			else{
				$arreglo+= array('cb_destacados'=>NULL);
			}

			if (isset($_REQUEST['activo'])){
				$arreglo+= array('activo'=>$_REQUEST['activo']);
			}

			$x="";
			if($id==0){
				$arreglo+= array('interno'=>1);
				$x=$this->insert('productos', $arreglo);
			}
			else{
				$x=$this->update('productos',array('id'=>$id), $arreglo);
			}
			return $x;
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function borrar_oficio(){
		if (isset($_POST['id'])){$id=$_POST['id'];}

		$arreglo =array();
		$arreglo+=array('idcomision'=>NULL);
		$this->update('zsalidacal',array('idcomision'=>$id), $arreglo);
		return $this->borrar('zofcomision',"idcomision",$id);
	}
	public function categorias_lista(){
		try{
			parent::set_names();
			$sql="SELECT * from categorias";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll();
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function categoria_agrega(){
		try{
			parent::set_names();
			$arreglo =array();
			$idcategoria="";
			$idproducto="";
			if (isset($_REQUEST['idcategoria'])){
				$idcategoria=$_REQUEST['idcategoria'];
				$arreglo += array('idcategoria'=>$idcategoria);
			}
			if (isset($_REQUEST['id'])){
				$idproducto=$_REQUEST['id'];
				$arreglo += array('idproducto'=>$idproducto);
			}
			$sql="select * from producto_cat where idproducto=:idprod and idcategoria=:idcat";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(':idprod', $idproducto);
			$sth->bindValue(':idcat', $idcategoria);
			$sth->execute();
			if ($sth->rowCount()==0){
				$x=$this->insert('producto_cat', $arreglo);
				$arreglo =array();
				$arreglo+=array('id'=>$idproducto);
				$arreglo+=array('error'=>0);
				return json_encode($arreglo);
			}
			else{
				$arreglo =array();
				$arreglo+=array('id'=>0);
				$arreglo+=array('error'=>1);
				$arreglo+=array('terror'=>"Ya existe en esta categoria");
				return json_encode($arreglo);
			}
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function almacen_busca($clave){
		try{
			parent::set_names();
			$sql="select * from almacen where homoclave=:id";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(':id', "$clave");
			$sth->execute();
			return $sth->fetch(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function almacen_lista(){
		try{
			parent::set_names();
			$sql="select * from almacen";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function agrega_existencia(){
		try{
			parent::set_names();
			$id=$_REQUEST['id'];
			$idproducto=$_REQUEST['idproducto'];
			$alam=$_REQUEST['almacen'];
			$valor=$_REQUEST['valor'];

			$arreglo =array();
			if (isset($_REQUEST['almacen'])){
				$arreglo+= array('almacen'=>$alam);
			}
			if (isset($_REQUEST['valor'])){
				$arreglo+= array('existencia'=>$valor);
			}

			$x="";
			if($id==0){
				$sql="select * from producto_exist where almacen='$alam' and id='$idproducto'";
				$sth = $this->dbh->prepare($sql);
				$sth->execute();
				if ($sth->rowCount()>0){
					$arreg=array();
					$arreg+=array('id'=>$idproducto);
					$arreg+=array('error'=>1);
					$arreg+=array('terror'=>"Ya existe");
					return json_encode($arreg);
				}
				$arreglo+= array('id'=>$idproducto);
				$x=$this->insert('producto_exist', $arreglo);
			}
			else{
				$x=$this->update('producto_exist',array('idexist'=>$id), $arreglo);
			}

			$sql="select sum(existencia) as total from producto_exist where id='$idproducto'";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			$resp=$sth->fetch(PDO::FETCH_OBJ);

			$fecha=mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));
			$fmodif = date("Y-m-d H:i:s");

			$sql="update productos set existencia='".$resp->total."', timeexis='$fecha', horaexist='$fmodif' where id='$idproducto'";
	    $stmt2= $this->dbh->query($sql);

			$arreglo =array();
			$arreglo+=array('id'=>$idproducto);
			$arreglo+=array('error'=>0);
			return json_encode($arreglo);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}

	public function agrega_espe(){
		try{
			parent::set_names();
			$id=$_REQUEST['id'];
			$id2=$_REQUEST['idproducto'];
			$arreglo =array();

			if (isset($_REQUEST['tipo'])){
				$arreglo+= array('tipo'=>$_REQUEST['tipo']);
			}
			if (isset($_REQUEST['valor'])){
				$arreglo+= array('valor'=>$_REQUEST['valor']);
			}

			$x="";
			if($id==0){
				$arreglo+= array('id'=>$id2);
				$x=$this->insert('producto_espe', $arreglo);
			}
			else{
				$x=$this->update('producto_espe',array('idespecificacion'=>$id), $arreglo);
			}
			$arreglo =array();
			$arreglo+=array('id'=>$id2);
			$arreglo+=array('error'=>0);
			return json_encode($arreglo);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function quitar_espe(){
		if (isset($_POST['id'])){$id=$_POST['id'];}
		return $this->borrar('producto_espe',"idespecificacion",$id);
	}
	public function quitar_existencia(){
		if (isset($_POST['id'])){$id=$_POST['id'];}
		return $this->borrar('producto_exist',"idexist",$id);
	}

	public function imagen($id){
		try{
			parent::set_names();
			$sql="select * from producto_img where idproducto='$id'";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll();
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}

	public function existencia_api(){
		$resp = crearNuevoToken();
	  $tok=$resp->token;

		$clave=$_REQUEST['clave'];
		$id=$_REQUEST['id'];

		$servicio = "existencia/$clave/TOTAL";
		$metodo="GET";

		$resp_a=array();

		$resp =servicioApi($metodo,$servicio,NULL,$tok);

		if (is_object($resp)){
			$existencia=$resp->existencia_total;
			$fmodif = date("Y-m-d H:i:s");
			$fecha=mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));

			$sql="update productos set existencia='$existencia', timeexis='$fecha', horaexist='$fmodif' where id='$id'";
			$sth = $this->dbh->prepare($sql);
			if($sth->execute()){
				$resp_a+=array('id'=>$id);
				$resp_a+=array('error'=>0);
				$resp_a+=array('param1'=>"Existencias actualizadas:".$existencia);
				return json_encode($resp_a);
			}
			else{
				$resp_a+=array('error'=>1);
				return json_encode($resp_a);
			}
		}
		else{
			return "error";
		}

	}
	public function almacen_api(){
		$resp = crearNuevoToken();
	  $tok=$resp->token;
		$clave=$_REQUEST['clave'];
		$id=$_REQUEST['id'];

		$servicio = "existencia/$clave";
		$metodo="GET";

		$resp_a=array();

		$resp =servicioApi($metodo,$servicio,NULL,$tok);
		if (is_object($resp)){

			$sql="delete from producto_exist where id='$id'";
			$sth3 = $this->dbh->prepare($sql);
			$sth3->execute();

			$objectToArray = (array)$resp;
			$limite=0;
			while (current($objectToArray)) {
				$name=key($objectToArray);
				$info = (array)$objectToArray[$name];
				$valor=$info['existencia'];
				if($valor>0){
					$sql="select * from almacen where numero='$name'";
					$sth4 = $this->dbh->prepare($sql);
					$sth4->execute();
					if($sth4->rowCount()){
						$almax=$sth4->fetch(PDO::FETCH_OBJ);

						$sql="insert into producto_exist (id, almacen, existencia) values (:id, :almacen, :existencia)";
						$sth2 = $this->dbh->prepare($sql);
						$sth2->bindValue(':id', $id);
						$sth2->bindValue(':almacen', $almax->homoclave);
						$sth2->bindValue(':existencia', $valor);
						$sth2->execute();
					}
					else{
						$sql="delete from producto_exist where id='$id'";
						$sth3 = $this->dbh->prepare($sql);
						$sth3->execute();

						$arreglo =array();
						$arreglo+=array('id'=>$id);
						$arreglo+=array('error'=>1);
						$arreglo+=array('terror'=>"El almacen no existe, favor de darlo de alta $name");
						return json_encode($arreglo);
					}
				}
				next($objectToArray);
			}
			$arreglo =array();
			$arreglo+=array('id'=>$id);
			$arreglo+=array('error'=>0);
			$arreglo+=array('terror'=>"");
			return json_encode($arreglo);
		}
		else{
			$arreglo =array();
			$arreglo+=array('id'=>$id);
			$arreglo+=array('error'=>1);
			$arreglo+=array('terror'=>"Error favor de verificar");
			return json_encode($arreglo);
		}
	}
	public function imagen_api(){
		try{
			$id=$_REQUEST['id'];
			$sql="select * from productos where id='$id'";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			$almax=$sth->fetch(PDO::FETCH_OBJ);

			try {
				$data = file_get_contents($almax->imagen);
				$img = imagecreatefromstring($data);
				if($img){
					if(imagejpeg($img,"../a_imagen/".$almax->img) ){
						$arreglo =array();
						$arreglo+=array('id'=>$id);
						$arreglo+=array('error'=>0);
						$arreglo+=array('terror'=>"");
						return json_encode($arreglo);
					}
					else{
						$arreglo =array();
						$arreglo+=array('id'=>$id);
						$arreglo+=array('error'=>1);
						$arreglo+=array('terror'=>"error favor de verificar");
						return json_encode($arreglo);
					}
				}
				else{
					$arreglo =array();
					$arreglo+=array('id'=>$id);
					$arreglo+=array('error'=>1);
					$arreglo+=array('terror'=>"error favor de verificar");
					return json_encode($arreglo);
				}
	    } catch (\Exception $ex) {
				$arreglo =array();
				$arreglo+=array('id'=>$id);
				$arreglo+=array('error'=>1);
				$arreglo+=array('terror'=>"error favor de verificar");
				return json_encode($arreglo);
	    }

		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}

	public function generar_codigo($length = 8) {
		return "TIC_".substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
	}
	public function categoria_ct(){
		try{
			parent::set_names();
			$sql="SELECT * from categoria_ct";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
	public function subcategoria_ct($categoria=""){
		try{
			parent::set_names();
			$sql="SELECT * FROM categoriasub_ct left outer join categoria_ct on categoria_ct.id=categoriasub_ct.idcategoria where categoria_ct.categoria='$categoria'";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
	public function sub_cambio(){
		$cat=$_REQUEST['cat'];
		echo "<label for='subcategoria'>Subcategoria</label>";
		echo "<select id='subcategoria' name='subcategoria' class='form-control form-control-sm' required>";
			echo "<option value='' disabled selected>Seleccione una opción</option>";
			foreach($this->subcategoria_ct($cat) as $key){
				echo "<option value='".$key->subcategoria."'>".$key->subcategoria."</option>";
			}
		echo "</select>";
	}
}
$db = new Productos();
if(strlen($function)>0){
	echo $db->$function();
}
?>
