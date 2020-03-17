<?php
require_once("../control_db.php");
if (isset($_REQUEST['function'])){$function=$_REQUEST['function'];}	else{ $function="";}

class Productos extends Tienda{

	public function __construct(){
		parent::__construct();
	}


	public function productos_lista(){
		try{
			parent::set_names();
			$poliza="";
			if (isset($_REQUEST['buscar']) and strlen(trim($_REQUEST['buscar']))>0){
				$texto=trim(htmlspecialchars($_REQUEST['buscar']));
				$sql="SELECT * from productos where clave like '%$texto%' or nombre like '%$texto%' or modelo like '%$texto%' or marca like '%$texto%' limit 100";
			}
			else{
				$sql="SELECT * from productos limit 100";
			}
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
			if (isset($_REQUEST['nombre'])){
				$arreglo = array('nombre'=>$_REQUEST['nombre']);
			}
			if (isset($_REQUEST['descripcion'])){
				$arreglo += array('descripcion'=>$_REQUEST['descripcion']);
			}
			if (isset($_REQUEST['sku'])){
				$arreglo += array('sku'=>$_REQUEST['sku']);
			}
			if (isset($_REQUEST['precio'])){
				$arreglo += array('precio'=>$_REQUEST['precio']);
			}
			if (isset($_REQUEST['cantidad'])){
				$arreglo += array('cantidad'=>$_REQUEST['cantidad']);
			}

			$x="";
			if($id==0){
				$x=$this->insert('productos', $arreglo);
			}
			else{
				$x=$this->update('productos',array('idproducto'=>$id), $arreglo);
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

	public function cargarjson(){
		$idProducto=$_REQUEST['idProducto'];
		return $idProducto;
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
			$destino="../historial/".$n_nombre;

			if(move_uploaded_file($key['tmp_name'],$destino)){
				chmod($destino,0666);
				$arr = array("archivo" => $n_nombre,"error"=>0);
			}
			else{
				$arr = array("archivo" => $n_nombre,"error"=>1);
			}
			$contarx++;
		}
		$myJSON = json_encode($arr);
		return $myJSON;
	}
	public function subida_orden(){
		$destino=$_REQUEST['direccion'];
		try{
			parent::set_names();
			$sql="TRUNCATE TABLE productos";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
		try{
			parent::set_names();
			$sql="ALTER TABLE productos AUTO_INCREMENT = 1 ";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
		$x="";
		if(strlen($destino)>2){
			$x.= "<div class='container' >";
				$x.= "<div class='card'>";
					$x.= "<div class='card-header'>";
					$x.= "Procesar archivo Xlsx";
					$x.= "</div>";
					$x.= "<div class='card-body'>";
						$x.= "<center><table class='info'>";
						$x.= "<tr><td>El archivo se subio correctamente: <b>$destino</b></td></tr>";
						$x.= "<tr><td>Paso 1: Obtener información del archivo de excel</td></tr>";
						$x.= "<input type='hidden' id='direccion' name='direccion' value='$destino'>";
						$x.= "<tr><td><button type='button' title='Editar' class='btn btn-outline-warning btn-sm' onclick='migrar()'><i class='fa fa-arrow-right'></i>Siguiente</button></td></tr>";
						$x.= "</table>";
					$x.="</div>";
				$x.="</div>";
			$x.="</div>";
		}
		return $x;
	}
	public function migrar(){
		$direccion=$_REQUEST['direccion'];
		$data = file_get_contents("../historial/".$direccion);
		$products = json_decode($data, true);
		$x="";
		echo "<div class='container' style='background-color:".$_SESSION['cfondo']."; '>";
		foreach ($products as $product) {
				$sql="insert into productos (idprod, clave, nombre, modelo, idMarca, marca, idCategoria, categoria, subcategoria, descripcion_corta) values (:idProducto, :clave, :nombre, :modelo, :idMarca, :marca, :idCategoria, :categoria, :subcategoria, :descripcion_corta)";

				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(':idProducto', $product['idProducto']);
				$sth->bindValue(':clave', $product['clave']);
				$sth->bindValue(':nombre', $product['nombre']);
				$sth->bindValue(':modelo', $product['modelo']);
				$sth->bindValue(':idMarca', $product['idMarca']);
				$sth->bindValue(':marca', $product['marca']);
				$sth->bindValue(':idCategoria', $product['idCategoria']);
				$sth->bindValue(':subcategoria', $product['subcategoria']);
				$sth->bindValue(':categoria', $product['categoria']);
				$sth->bindValue(':descripcion_corta', $product['descripcion_corta']);
				$sth->execute();
		}
		return 0;
		echo "</div> fin de archivo";
	}
}
$db = new Productos();
if(strlen($function)>0){
	echo $db->$function();
}
?>
