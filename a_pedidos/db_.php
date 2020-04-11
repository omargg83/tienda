<?php
require_once("../control_db.php");
if (isset($_REQUEST['function'])){$function=$_REQUEST['function'];}	else{ $function="";}

class Pedidos extends Tienda{

	public function __construct(){
		parent::__construct();
	}
	public function pedidos_lista(){
		try{
			parent::set_names();
			if (isset($_REQUEST['buscar']) and strlen(trim($_REQUEST['buscar']))>0){
				$texto=trim(htmlspecialchars($_REQUEST['buscar']));
				$sql="SELECT * from pedidos
				left outer join clientes on clientes.id=pedidos.idcliente
				where pedidos.id like '%$texto%' or pedidos.estatus like '%$texto%' or clientes.nombre like '%$texto' limit 100";
			}
			else{
				$sql="SELECT * from pedidos";
			}
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll();
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
	public function editar_pedido($id){
		try{
			parent::set_names();
			$sql="SELECT * from pedidos where id=:id";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(':id', "$id");
			$sth->execute();
			return $sth->fetch();
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
	public function guardar_pedido(){
		try{
			parent::set_names();
			$id=$_REQUEST['id'];
			$arreglo =array();

			if (isset($_REQUEST['fecha']) and strlen($_REQUEST['fecha'])>0){
				$fx=explode("-",$_REQUEST['fecha']);
				$arreglo+=array('fecha'=>$fx['2']."-".$fx['1']."-".$fx['0']);
			}
			if (isset($_REQUEST['estatus'])){
				$arreglo+= array('estatus'=>$_REQUEST['estatus']);
			}
			if (isset($_REQUEST['idfactura'])){
				$arreglo+= array('idfactura'=>$_REQUEST['idfactura']);
			}
			if (isset($_REQUEST['idenvio'])){
				$arreglo+= array('idenvio'=>$_REQUEST['idenvio']);
			}
			if (isset($_REQUEST['notas'])){
				$arreglo+= array('notas'=>$_REQUEST['notas']);
			}

			$x="";
			if($id==0){
				$x=$this->insert('pedidos', $arreglo);
			}
			else{
				$x=$this->update('pedidos',array('id'=>$id), $arreglo);
			}
			return $x;
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
	public function busca_cliente(){
		try{
			parent::set_names();
			$texto=$_REQUEST['texto'];
			$idcliente=$_REQUEST['idcliente'];
			$idpedido=$_REQUEST['idpedido'];

			$sql="SELECT * from clientes where nombre like '%$texto%' or apellido like '%$texto%' or correo like '%$texto%' limit 100";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			echo "<table class='table table-sm'>";
			echo "<tr><th>-</th><th>Nombre</th><th>Apellido</th><th>Correo</th></tr>";
			foreach($sth->fetchAll() as $key){
				echo "<tr>";
					echo "<td>";
						echo "<div class='btn-group'>";
						echo "<button type='button' onclick='cliente_add(".$key['id'].",$idpedido)' class='btn btn-outline-secondary btn-sm' title='Seleccionar cliente'><i class='fas fa-plus'></i></button>";
						echo "</div>";
					echo "</td>";
					echo "<td>";
							echo $key['nombre'];
					echo "</td>";
					echo "<td>";
							echo $key['apellido'];
					echo "</td>";
					echo "<td>";
							echo $key['correo'];
					echo "</td>";
				echo "</tr>";
			}
			echo "</table>";
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
	public function agrega_cliente(){
		try{
			parent::set_names();
			$x="";
			$idcliente=$_REQUEST['idcliente'];
			$id=$_REQUEST['idpedido'];
			$arreglo =array();
			$arreglo+= array('idcliente'=>$_REQUEST['idcliente']);
			if($id==0){
				$arreglo+= array('fecha'=>date("Y-m-d H:i:s"));
				$arreglo+= array('estatus'=>"pendiente");
				$x=$this->insert('pedidos', $arreglo);
			}
			else{
				$x=$this->update('pedidos',array('id'=>$id), $arreglo);
			}
			return $x;
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
	public function cliente($idcliente){
		try{
			$sql="select * from clientes where id=$idcliente";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetch();
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
	public function direccion($idcliente){
		try{
			$sql="select * from clientes_direccion where idcliente=$idcliente";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll();
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
	public function busca_producto(){
		try{
			parent::set_names();
			$texto=$_REQUEST['texto'];
			$idproducto=$_REQUEST['idproducto'];
			$idpedido=$_REQUEST['idpedido'];

			$sql="SELECT * from productos where activo=1 and existencia>0 and (clave like '%$texto%' or numParte like '%$texto%' or nombre like '%$texto%' or modelo like '%$texto%' or marca like '%$texto%') limit 100";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			echo "<div class='row'>";
				echo "<div class='col-1'>-</div>";
				echo "<div class='col-2'><b>Clave</b></div>";
				echo "<div class='col-5'><b>Nombre</b></div>";
				echo "<div class='col-2 text-center'><b>Existencia</b></div>";
				echo "<div class='col-2'><b>Precio</b></div>";
			echo "</div>";
			$envio=0;
			foreach($sth->fetchAll(PDO::FETCH_OBJ) as $key){
				echo "<div class='row' style='border-bottom: 1px solid silver;font-size:12px'>";
					echo "<div class='col-1' >";
						echo "<div class='btn-group'>";
						echo "<button type='button' onclick='prod_add(".$key->id.",$idpedido)' class='btn btn-outline-secondary btn-sm' title='Seleccionar cliente'><i class='fas fa-plus'></i></button>";
						echo "</div>";
					echo "</div>";
					echo "<div class='col-2' >";
							echo $key->clave;
					echo "</div>";
					echo "<div class='col-5' >";
							echo $key->nombre."<br>";
							echo "<b>Parte: </b>".$key->numParte;
							echo "<br>+ Envio: ";
							if($key->envio_tipo==0){
								echo moneda($this->egeneral);
								$envio+=$this->egeneral;
							}
							if($key->envio_tipo==1){
								echo moneda($key->envio_costo);
								$envio+=$key->envio_costo;
							}
					echo "</div>";
					echo "<div class='col-2 text-center' >";
							echo $key->existencia;
					echo "</div>";
					echo "<div class='col-2 text-right'>";

							if($key->precio_tipo==0){
								echo moneda($key->preciof);
							}
							if($key->precio_tipo==1){
								$total=$key->preciof+(($key->preciof*$this->cgeneral)/100);
								echo moneda($total);
							}
							if($key->precio_tipo==2){
								echo moneda($key->precio_tic);
							}
							if($key->precio_tipo==3){
								$total=$key->precio_tic+(($key->precio_tic*$this->cgeneral)/100);
								echo moneda($total);
							}

					echo "</div>";
				echo "</div>";
			}
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
	public function producto_add(){
		try{
			parent::set_names();
			$x="";
			$idproducto=$_REQUEST['id'];
			$id=$_REQUEST['idpedido'];
			$arreglo =array();
			if($id==0){
				$arreglo+= array('estatus'=>"pendiente");
				$x=$this->insert('pedidos', $arreglo);
				$ped=json_decode($x);
				$id=$ped->id;
			}
			$arreglo =array();
			$arreglo+= array('idprod'=>$idproducto);
			$arreglo+= array('idpedido'=>$id);
			$precio=$_REQUEST['preciof'];
			$arreglo+= array('precio'=>$precio);
			$arreglo+= array('cantidad'=>1);
			$arreglo+= array('total'=>$precio);

			if (isset($_REQUEST['idProducto'])){
				$arreglo+= array('idProducto'=>$_REQUEST['idProducto']);
			}
			if (isset($_REQUEST['clave'])){
				$arreglo+= array('clave'=>$_REQUEST['clave']);
			}
			if (isset($_REQUEST['numParte'])){
				$arreglo+= array('numParte'=>$_REQUEST['numParte']);
			}
			if (isset($_REQUEST['nombre'])){
				$arreglo+= array('nombre'=>$_REQUEST['nombre']);
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
			if (isset($_REQUEST['descripcion_corta'])){
				$arreglo+= array('descripcion_corta'=>$_REQUEST['descripcion_corta']);
			}
			$x=$this->insert('pedidos_prod', $arreglo);
			$ped=json_decode($x);
			if($ped->error==0){
				$arreglo =array();
				$arreglo+=array('id'=>$id);
				$arreglo+=array('error'=>0);
				$arreglo+=array('terror'=>0);
				$arreglo+=array('param1'=>"");
				$arreglo+=array('param2'=>"");
				$arreglo+=array('param3'=>"");
				return json_encode($arreglo);
			}
			else{
				return $x;
			}
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
	public function productos_pedido($idpedido){
		try{
			parent::set_names();
			$sql="SELECT * from pedidos_prod where pedidos_prod.idpedido=$idpedido";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll();
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}

	public function borrar_prodped(){
		if (isset($_POST['id'])){$id=$_REQUEST['id'];}
		return $this->borrar('pedidos_prod',"id",$id);
	}
	public function busca_cupon(){
		try{
			parent::set_names();
			$texto=$_REQUEST['texto'];
			$idpedido=$_REQUEST['idpedido'];

			$sql="SELECT * from cupon where codigo like '%$texto%' limit 100";
			echo $sql;
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			echo "<div class='row'>";
				echo "<div class='col-1'>-</div>";
				echo "<div class='col-2'><b>Código</b></div>";
				echo "<div class='col-5'><b>Descripción</b></div>";
				echo "<div class='col-2 text-center'><b>Existencia</b></div>";
				echo "<div class='col-2'><b>Precio</b></div>";
			echo "</div>";
			foreach($sth->fetchAll() as $key){
				echo "<div class='row' style='border-bottom: 1px solid silver;font-size:12px'>";
					echo "<div class='col-1' >";
						echo "<div class='btn-group'>";
						echo "<button type='button' onclick='prod_add(".$key['id'].",$idpedido)' class='btn btn-outline-secondary btn-sm' title='Seleccionar cliente'><i class='fas fa-plus'></i></button>";
						echo "</div>";
					echo "</div>";
					echo "<div class='col-2' >";
							echo $key['codigo'];
					echo "</div>";
					echo "<div class='col-5' >";
							echo $key['descripcion'];
					echo "</div>";
					echo "<div class='col-2 text-center' >";
							echo $key['existencia'];
					echo "</div>";
					echo "<div class='col-2 text-right'>";
							echo moneda($key['preciof']);
					echo "</div>";
				echo "</div>";
			}
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
	public function almacen_busca($clave){
		try{
			self::set_names();
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
	public function producto_exist($id,$tipo=0){
		try{
			self::set_names();
			
			if($tipo==0){
				$sql="select * from producto_exist where id=$id";
			}
			if($tipo==1){
				$sql="select existencia as total, 'Pachuca' as alma from producto_exist where id=$id and almacen='PAC' UNION
					select sum(existencia) as total, 'Otros' as alma from producto_exist where id=$id and almacen!='PAC' group by idProducto";
			}
			if($tipo==2){
				$sql="select sum(existencia) as existencia, almacen from producto_exist where id=$id";
			}
			echo $sql;

			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}
}
$db = new Pedidos();
if(strlen($function)>0){
	echo $db->$function();
}
?>
