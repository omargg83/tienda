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
				where pedidos.id like '%$texto%' or pedidos.estatus like '%$texto%' or clientes.nombre like '%$texto' order by fecha desc limit 100";
			}
			else{
				$sql="SELECT * from pedidos order by fecha desc";
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
	public function pedidos_web($id){
		try{
			parent::set_names();
			$sql="SELECT * from pedidos_web where idpedido=:id";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(':id', "$id");
			$sth->execute();
			return $sth->fetchAll();
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
			$id=$_REQUEST['idpedido'];
			$idcliente=$_REQUEST['idcliente'];

			$sql="select * from clientes where id=:id";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(":id",$idcliente);
			$sth->execute();
			$cli_x=$sth->fetch(PDO::FETCH_OBJ);

			$arreglo =array();
			$arreglo+= array('idcliente'=>$cli_x->id);
			$arreglo+= array('rfc'=>$cli_x->rfc);
			$arreglo+= array('cfdi'=>$cli_x->cfdi);
			$arreglo+= array('nombre'=>$cli_x->nombre);
			$arreglo+= array('apellido'=>$cli_x->apellido);
			$arreglo+= array('correo'=>$cli_x->correo);
			$arreglo+= array('direccion1'=>$cli_x->direccion1);
			$arreglo+= array('entrecalles'=>$cli_x->entrecalles);
			$arreglo+= array('numero'=>$cli_x->numero);
			$arreglo+= array('colonia'=>$cli_x->colonia);
			$arreglo+= array('cp'=>$cli_x->cp);
			$arreglo+= array('pais'=>$cli_x->pais);
			$arreglo+= array('estado'=>$cli_x->estado);
			$arreglo+= array('telefono'=>$cli_x->telefono);
			$arreglo+= array('ciudad'=>$cli_x->ciudad);
			$arreglo+= array('cfdi'=>$cli_x->cfdi);

			if($id==0){
				$arreglo+= array('fecha'=>date("Y-m-d H:i:s"));
				$arreglo+= array('estatus'=>"EN ESPERA");
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
				echo "<div class='col-2 text-center'><b>Cantidad</b></div>";
				echo "<div class='col-2'><b>Precio</b></div>";
			echo "</div>";
			$envio=0;
			foreach($sth->fetchAll(PDO::FETCH_OBJ) as $key){
				echo "<div class='row' style='border-bottom: 1px solid silver;font-size:14px'>";
					echo "<div class='col-1' >";
						echo "<div class='btn-group'>";
						echo "<button type='button' onclick='prod_add(".$key->id.",$idpedido)' class='btn btn-outline-secondary btn-sm' title='Agregar producto'><i class='fas fa-plus'></i></button>";
						echo "</div>";
					echo "</div>";
					echo "<div class='col-2' >";
							echo $key->clave;
					echo "</div>";
					echo "<div class='col-5' >";
							echo $key->nombre."<br>";
							echo "<b>Parte: </b>".$key->numParte;
							echo "<br>Exitencia: ".$key->existencia;
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
							echo "<input id='cantidad_".$key->id."' name='cantidad_".$key->id."' placeholder='cantidad' value='1' class='form-control form-control-sm'>";
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
			$cantidad=$_REQUEST['cantidad'];


			$arreglo =array();
			if($id==0){
				$arreglo+= array('estatus'=>"EN ESPERA");
				$x=$this->insert('pedidos', $arreglo);
				$ped=json_decode($x);
				$id=$ped->id;
			}
			////////////////////////////////////////////////////////
			$idpedido_prod=0;
			$sql="select * from pedidos_prod where idprod='$idproducto' and idpedido='".$id."'";
			$sth_i = $this->dbh->prepare($sql);
			$sth_i->execute();
			$contar=$sth_i->rowCount();
			if($contar>0){
				$resp=$sth_i->fetch(PDO::FETCH_OBJ);
				$cantidad=$cantidad+$resp->cantidad;
				$idpedido_prod=$resp->id;
			}

			//////////////verificar existencia
			$sql="select * from productos where id='$idproducto'";
			$sth_i = $this->dbh->prepare($sql);
			$sth_i->execute();
			$prod=$sth_i->fetch(PDO::FETCH_OBJ);
			if($prod->existencia<$cantidad){
				$arr=array();
				$arr+=array('error'=>1);
				$arr+=array('terror'=>"Verificar existencias");
				return json_encode($arr);
			}
			////////////////////////////////////////////////////////

			if($prod->precio_tipo==0){
				$preciof=$prod->preciof;
			}
			if($prod->precio_tipo==1){
				$p_total=$prod->preciof+(($prod->preciof*$this->cgeneral)/100);
				$preciof=$p_total;
			}
			if($prod->precio_tipo==2){
				$preciof=$prod->precio_tic;
			}
			if($prod->precio_tipo==3){
				$p_total=$prod->precio_tic+(($prod->precio_tic*$this->cgeneral)/100);
				$preciof=$p_total;
			}
			//////////////////envio

			if($prod->envio_tipo==0){
				$enviof=$this->egeneral;
			}
			if($prod->envio_tipo==1){
				$enviof=$prod->envio_costo;
			}

			$enviot=$enviof*$cantidad;
			$preciot=$preciof*$cantidad;
			$sub=$enviot+$preciot;


			$arreglo =array();
			$arreglo+= array('precio'=>$preciof);
			$arreglo+= array('envio'=>$enviof);
			$arreglo+= array('total'=>$sub);
			$arreglo+= array('cantidad'=>$cantidad);

			if($idpedido_prod==0){
				$arreglo+= array('idprod'=>$idproducto);
				$arreglo+= array('idpedido'=>$id);
				$arreglo+= array('idProducto'=>$prod->idProducto);
				$arreglo+= array('clave'=>$prod->clave);
				$arreglo+= array('numParte'=>$prod->numParte);
				$arreglo+= array('nombre'=>$prod->nombre);
				$arreglo+= array('modelo'=>$prod->modelo);
				$arreglo+= array('marca'=>$prod->marca);
				$arreglo+= array('categoria'=>$prod->categoria);
				$arreglo+= array('descripcion_corta'=>$prod->descripcion_corta);
				$x=$this->insert('pedidos_prod', $arreglo);
			}
			else{
				$x=$this->update('pedidos_prod',array('id'=>$idpedido_prod), $arreglo);
			}

			$ped=json_decode($x);
			if($ped->error==0){
				////////////////////////// update total
					$sql="select sum(total) as totalx, sum(envio) as enviox from pedidos_prod where idpedido=$id";
					$sth = $this->dbh->prepare($sql);
					$sth->execute();
					$total_ped=$sth->fetch(PDO::FETCH_OBJ);

					$arreglo =array();
					$arreglo+= array('monto'=>round($total_ped->totalx,2));
					$arreglo+= array('envio'=>round($total_ped->enviox));
					$gtotal=round($total_ped->totalx+$total_ped->enviox,2);
					$arreglo+= array('total'=>$gtotal);
					$this->update('pedidos',array('id'=>$id), $arreglo);

				//////////////////////////
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
		$id=$_REQUEST['id'];

		/////////////////////////////////busca el pedido
		$sql="select * from pedidos_prod where id=$id";
		$sth = $this->dbh->prepare($sql);
		$sth->execute();
		$pedx=$sth->fetch(PDO::FETCH_OBJ);
		$idpedido=$pedx->idpedido;
		//////////////////////////elimina el producto
		$x=$this->borrar('pedidos_prod',"id",$id);

		///////////////////////////actualiza el total
		$sql="select sum(total) as totalx, sum(envio) as enviox from pedidos_prod where idpedido=$idpedido";
		$sth = $this->dbh->prepare($sql);
		$sth->execute();
		$total_ped=$sth->fetch(PDO::FETCH_OBJ);

		$arreglo =array();
		$arreglo+= array('monto'=>round($total_ped->totalx,2));
		$arreglo+= array('envio'=>round($total_ped->enviox));
		$gtotal=round($total_ped->totalx+$total_ped->enviox,2);
		$arreglo+= array('total'=>$gtotal);
		$this->update('pedidos',array('id'=>$idpedido), $arreglo);

		return $x;
	}
	public function busca_cupon(){
		try{
			parent::set_names();
			$texto=$_REQUEST['texto'];
			$idpedido=$_REQUEST['idpedido'];

			$sql="SELECT * from cupon where codigo like '%$texto%' limit 100";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			echo "<div class='row'>";
				echo "<div class='col-1'>-</div>";
				echo "<div class='col-2'><b>Código</b></div>";
				echo "<div class='col-5'><b>Descripción</b></div>";
				echo "<div class='col-2 text-center'><b>Tipo</b></div>";
				echo "<div class='col-2'><b>Cantidad</b></div>";
			echo "</div>";
			foreach($sth->fetchAll() as $key){
				echo "<div class='row' style='border-bottom: 1px solid silver;font-size:12px'>";
					echo "<div class='col-1' >";
						echo "<div class='btn-group'>";
						echo "<button type='button' onclick='cupon_agrega(".$key['id'].",$idpedido)' class='btn btn-outline-secondary btn-sm' title='Seleccionar cliente'><i class='fas fa-plus'></i></button>";
						echo "</div>";
					echo "</div>";
					echo "<div class='col-2' >";
							echo $key['codigo'];
					echo "</div>";
					echo "<div class='col-5' >";
							echo $key['descripcion'];
					echo "</div>";
					echo "<div class='col-2 text-center' >";
					  if ($key['tipo']=="porcentaje"){ echo "Descuento en porcentaje"; }
						if ($key['tipo']=="carrito"){ echo "Descuento fijo en el carrito"; }
					echo "</div>";
					echo "<div class='col-2 text-right'>";
							echo $key['importe'];
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
	public function pedido_cupones($id){
		try{
			self::set_names();
			$sql="select * from pedidos_cupon where idpedido=:id";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(":id",$id);
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_OBJ);
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}

	public function cupon_busca(){
		try{
			self::set_names();
			$idpedido=$_REQUEST['idpedido'];
			$idcupon=$_REQUEST['idcupon'];

			$sql="select * from pedidos_cupon where idcupon='$idcupon' and idpedido='$idpedido'";
			$sth_i = $this->dbh->prepare($sql);
			$sth_i->execute();
			if($sth_i->rowCount()>0){
				$arreglo=array();
				$arreglo+=array('id'=>$idpedido);
				$arreglo+=array('error'=>1);
				$arreglo+=array('terror'=>"El cupón ya esta agregado");
				return json_encode($arreglo);
			}


			$sql="SELECT * FROM cupon where id=:idcupon";
			$sth_i = $this->dbh->prepare($sql);
			$sth_i->bindValue(":idcupon",$idcupon);
			$sth_i->execute();
			$contar=$sth_i->rowCount();
			if($contar>0){
				$ped=$sth_i->fetch(PDO::FETCH_OBJ);

				$sql="insert into pedidos_cupon (idpedido, idcupon, descuento, codigo, descripcion, tipo, envio) values (:idpedido, :idcupon, :descuento, :codigo, :descripcion, :tipo, :envio)";
				$sth = $this->dbh->prepare($sql);
				$sth->bindValue(":idpedido",$idpedido);
				$sth->bindValue(":idcupon",$ped->id);
				$sth->bindValue(":descuento",$ped->importe);
				$sth->bindValue(":codigo",$ped->codigo);
				$sth->bindValue(":descripcion",$ped->descripcion);
				$sth->bindValue(":tipo",$ped->tipo);
				$sth->bindValue(":envio",$ped->envio);
				if($sth->execute()){
					$arreglo=array();
					$arreglo+=array('id'=>$idpedido);
					$arreglo+=array('error'=>0);
					$arreglo+=array('terror'=>"");
					return json_encode($arreglo);
				}
				else{
					$arreglo=array();
					$arreglo+=array('id'=>$idpedido);
					$arreglo+=array('error'=>1);
					$arreglo+=array('terror'=>$sth->errorInfo());
					return json_encode($arreglo);
				}
			}
			else{
				$arreglo=array();
				$arreglo+=array('id'=>$idpedido);
				$arreglo+=array('error'=>1);
				$arreglo+=array('terror'=>"Cupón no valido");
				return json_encode($arreglo);
			}
		}
		catch(PDOException $e){
			$arreglo=array();
			$arreglo+=array('id'=>$idpedido);
			$arreglo+=array('error'=>1);
			$arreglo+=array('terror'=>$e->getMessage());
			return json_encode($arreglo);
		}
	}
	public function elimina_cupon(){
		try{
			self::set_names();
			$id=$_REQUEST['id'];
			$sql="delete from pedidos_cupon where id=:id";
			$sth = $this->dbh->prepare($sql);
			$sth->bindValue(":id",$id);
			return $sth->execute();
		}
		catch(PDOException $e){
			return "Database access FAILED!".$e->getMessage();
		}
	}

	public function pedidos_ct(){
		try{
			parent::set_names();
			$sql="select pedidos_web.idprod, pedidos_web.clave, pedidos_web.pedidoWeb, pedidos_web.estatus as webs, pedidos_web.cantidad ,pedidos.* from pedidos_web left outer join pedidos on pedidos_web.idpedido=pedidos.id order by pedidos_web.id desc";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return $sth->fetchAll();
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
	public function confirmar_web(){
		try{
			$pedido_web=trim($_REQUEST['pedido_web']);
			$idpedido=$_REQUEST['idpedido'];

			$resp = crearNuevoToken();
			$tok=$resp->token;
			$json = json_encode(array('folio' => $pedido_web));
			$resp =servicioApi('POST','pedido/confirmar',$json,$tok);

			if (isset($resp->errorCode)){
				$estado=$resp->errorMessage;
				$arreglo =array();
				$arreglo+=array('id'=>$idpedido);
				$arreglo+=array('error'=>1);
				$arreglo+=array('terror'=>$estado);
			}
			if(isset($resp->okReference)){
				$estado=$resp->okReference;
			}

			$sql="update pedidos_web set estatus='$estado' where pedidoWeb='$pedido_web'";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();
			return json_encode($arreglo);
		}
		catch(PDOException $e){
			return "Database access FAILED! ".$e->getMessage();
		}
	}
	public function pedir_ct(){
		//////////////////////////////////////////////////////
		$idpedido=$_REQUEST['id'];

		$ped=$this->editar_pedido($idpedido);
		$cupones=$db->pedido_cupones($idpedido);
		$datos=$db->productos_pedido($idpedido);

		$nombre=$ped->nombre;
		$apellido=$ped->apellido;
		$correo=$ped->correo;
		$rfc=$ped->rfc;
		$cfdi=$ped->cfdi;
		$direccion1=$ped->direccion1;

		$entrecalles=$ped->entrecalles;
		$numero=$ped->numero;
		$colonia=$ped->colonia;

		$ciudad=$ped->ciudad;
		$cp=$ped->cp;
		$pais=$ped->pais;
		$estado=$ped->estado;
		$telefono=$ped->telefono;
		$gmonto=$ped->monto;
		$genvio=$ped->envio;
		$gtotal=$ped->total;
		$estatus=$ped->estatus;
		$pago=$ped->pago;
		$idpago=$ped->idpago;

		/*
		$resp = crearNuevoToken();
		$tok=$resp->token;
		foreach($datos as $key){
			$clave=$key->clave;
			$idprod=$key->idprod;
			$cantidad=$key->cantidad;

			$sql="select * from productos where id='".$idprod."'";
			$prod_query = $db->dbh->prepare($sql);
			$prod_query->execute();
			$prod_pedido=$prod_query->fetch(PDO::FETCH_OBJ);
			$precio_prod=$prod_pedido->precio;

			$sql="select producto_exist.*,almacen.numero from producto_exist left outer join almacen on almacen.homoclave=producto_exist.almacen where id='$idprod' order by existencia desc";
			$exist = $db->dbh->prepare($sql);
			$exist->execute();
			$contar=$exist->rowCount();
			if($contar>0){
				$alma_pedido=$exist->fetchAll(PDO::FETCH_OBJ);
				foreach($alma_pedido as $pedx){
					if($cantidad>0){
						$pedir=$pedx->existencia-$cantidad;
						if($pedir>=0){
							$pedir=$cantidad;
						}
						else{
							$pedir=$cantidad+$pedir;
						}
						$cantidad=$cantidad-$pedir;

						if($pedir>0){
							$envio=array();
							$contar=0;

							$resp =servicioApi('GET',"existencia/detalle/".$clave."/".$pedx->numero,NULL,$tok);
							if($resp->promocion){

								if ($resp->promocion->descuentoPrecio>0){
									$precio_desc=$resp->promocion->descuentoPrecio;
								}
								if($resp->promocion->descuentoPorcentaje>0){
									$porc=$resp->promocion->descuentoPorcentaje;
									$precio_desc=$precio_prod-(($precio_prod*$porc)/100);
								}
								$precio_f=round($precio_desc,2);
							}
							else{
								$precio_f=$precio_prod;
							}

							$envio[0]=array(
								'nombre' => $nombre. " ".$apellido,
								'direccion' => $direccion1,
								'entreCalles' => $entrecalles,
								'noExterior' => $numero,
								'colonia' => $colonia,
								'estado' => $estado,
								'ciudad' => $ciudad,
								'codigoPostal' => $cp,
								'telefono' => $telefono
							);

							if($key->tipo=="CT"){
								$producto[0]=array(
									'cantidad' => $pedir,
									'clave' => $clave,
									'precio' => "$precio_f",
									'moneda' => $prod_pedido->moneda
								);
							}

							$arreglo=array(
								'idPedido' => (int)$idpedido,
								'almacen' => $pedx->numero,
								'tipoPago' => "03",
								'envio' => json_decode(json_encode($envio)),
								'producto' => json_decode(json_encode($producto)),
							);
							$json = json_encode($arreglo);

							$resp =servicioApi('POST','pedido',$json,$tok); 					/////////////////////////////////////////////PEDIDO
							$pedidoweb=$resp[0]->respuestaCT->pedidoWeb;
							$estatus=$resp[0]->respuestaCT->estatus;
							$sql="insert into pedidos_web (idprod, clave, cantidad, pedidoWeb, estatus, idpedido) values ('$idprod', '$clave', '$pedir', '$pedidoweb', '$estatus', '$idpedido')";
							$stmt= $db->dbh->query($sql);
						}

					}
					else{
						break;
					}
				}
			}
		}
		*/
		/////////////////////////////////////////////Correo

		$texto="<h3>TIC-SHOP</h3><br>
		<h3><b><center>Pedido</center></b></h3>

		<table style='width:100%'>
			<tr>
			<td>
				<b>Pedido #:</b><br> $idpedido
			</td>
			<td>
				<b>Estatus:</b><br> $estatus
			</td>
			<td>
				<b>Pago:</b><br> $pago
			</td>
			<td>
				<b>Pago #:</b><br> $idpago
			</td>
			<td>
				<b>Nombre:</b><br> $nombre $apellido</b>
			</td>
			<td>
				<b>Correo:</b><br> $correo
			</td>
		</tr>
		</table>
		<br>
		<h3><center>Información de envío</center></h3>
		<table style='width:100%'>
		<tr>
			<td>
				<b>RFC:</b><br> $rfc
			</td>
			<td>
				<b>Uso CFDI:</b><br> $cfdi
			</td>
			<td>
				<b>Dirección:</b><br> $direccion1
			</td>
			<td>
				<b>Entre calles:</b><br> $entrecalles
			</td>
		</tr>
		</table>
		<table style='width:100%'>
		<tr>
			<td>
				<b>Num. Exterior:</b><br> $numero
			</td>
			<td>
				<b>Colonia:</b><br> $colonia
			</td>
			<td>
				<b>Ciudad:</b><br> $ciudad
			</td>
			<td>
				<b>Código postal:</b><br> $cp
			</td>
			<td>
				<b>Pais:</b><br> $pais
			</td>
			<td>
				<b>Estado:</b><br> $estado
			</td>
			<td>
				<b>Teléfono:</b><br> $telefono
			</div>
		</tr>
		<br>
		<hr>
		<h3>Productos</h3>
		<table>
		<hr>
		<table style='width:100%'>
			<tr>
				<td>
					<b>Descripción</b>
				</td>
				<td>
					<b>Cantidad</b>
				</td>
				<td>
					<b>Precio unitario</b>
				</td>
				<td>
					<b>Envío</b>
				</td>

				<td>
					<b>Total</b>
				</td>
			</tr>
		";

			///////////////////////////////////
			$sub_total=0;
			$sub_envio=0;
			foreach($datos as $key){
				$texto.="<tr>";
					$texto.= "<td>";
							$texto.= $key->clave;
							$texto.= "<br><b>".$key->nombre."</b>";
							$texto.= "<br>".$key->modelo;
							$texto.= "<br>".$key->marca;
							$texto.= "<br>".$key->categoria;
					$texto.= "</td>";

					$texto.= "<td>";
						$texto.= $key->cantidad;
					$texto.= "</td>";

					$texto.= "<td>";
						$texto.= moneda($key->precio);
						$sub_total+=$key->precio*$key->cantidad;
					$texto.= "</td>";

					$texto.= "<td>";
						$texto.= moneda($key->envio);
						$sub_envio+=$key->envio*$key->cantidad;
					$texto.= "</td>";

					$texto.= "<td>";
						$texto.= moneda($key->total);
					$texto.= "</td>";

				$texto.= "</tr>";
			}

			///////////////////////////////////

				$texto.= "<tr>";
					$texto.= "<td colspan=4>";
						$texto.= "<b>Subtotal</b>";
					$texto.= "</td>";
					$texto.= "<td>";
						$texto.= moneda($sub_total);
					$texto.= "</td>";
				$texto.= "</tr>";
				$texto.= "<tr>";
					$texto.= "<td colspan=4>";
						$texto.= "<b>Envio</b>";
					$texto.= "</td>";
					$texto.= "<td>";
						$texto.= moneda($sub_envio);
					$texto.= "</td>";
				$texto.= "</tr>";

				$texto.= "<tr>";
					$texto.= "<td colspan=4>";
						$texto.= "<b>Total</b>";
						$texto.= "</td>";
						$texto.= "<td>";
						$texto.= moneda($gtotal);
					$texto.= "</td>";
				$texto.= "</tr>";

				if(is_array($cupones)){
					$texto.= "<tr><td colspan=5>Cupones</td></tr>";
					foreach($cupones as $keyc){
						$texto.= "<tr>";
							$texto.= "<td colspan=4>";
								$texto.= $keyc->codigo;
								$texto.= "<br>";
								$texto.= $keyc->descripcion;
							$texto.= "</td>";
							$texto.= "<td>";

								if($keyc->tipo=='porcentaje'){
									$texto.= $keyc->descuento."%";
									$monto=($gtotal*$keyc->descuento)/100;
									$texto.= "<br>- ".moneda($monto);
									$gtotal=$gtotal-$monto;
								}

								if($keyc->tipo=='carrito'){
									$texto.= "<br>- ".moneda($keyc->descuento);
									$gtotal=$gtotal-$keyc->descuento;
								}

								if($keyc->envio=='si'){
									$gtotal=$gtotal-$envio;
									$texto.= "<br>Envio: -".$envio;
								}

							$texto.= "</td>";
						$texto.= "</tr>";

						$texto.= "<tr>";
							$texto.= "<td colspan=4>";
								$texto.= "<h4><b>Total:</b></h4>";
							$texto.= "</td>";

							$texto.= "<td>";
								$texto.= "<h4><b>".moneda($gtotal)."</b></h4>";
							$texto.= "</td>";
						$texto.= "</tr>";
					}
				}
		$texto.="</table>";

	$asunto="Compra Exitosa";
		$this->correo($correo, $texto, $asunto);
	}
}
$db = new Pedidos();
if(strlen($function)>0){
	echo $db->$function();
}
?>
