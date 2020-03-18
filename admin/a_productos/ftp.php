<?php
  require_once("../control_db.php");
  class Productos extends Tienda{
  	public function __construct(){
  		parent::__construct();
  	}
  }
  $destino="file.json";
  echo "<br>".date("Y-m-d H:i:s");
  class ftp{
      public $conn;

      public function __construct($url){
          $this->conn = ftp_connect($url);
      }

      public function __call($func,$a){
          if(strstr($func,'ftp_') !== false && function_exists($func)){
              array_unshift($a,$this->conn);
              return call_user_func_array($func,$a);
          }else{
              // replace with your own error handler.
              die("$func is not a valid FTP function");
          }
      }
      public function desconectar(){
         if ( $this->conn ) {
             ftp_close( $this->conn );
         }
      }
      public function descargar( $fuente, $destino, $modo = FTP_BINARY ){
        if ( ftp_get($this->conn, $destino, $fuente, $modo, 0) ) {
            $temp = true;
        } else {
            $temp = false;
        }
        return $temp;
      }
      public function lista(){
        $contents = ftp_nlist($this->conn, "catalogo_xml");
        return $contents;
      }
  }

  $ftp = new ftp('216.70.82.104');
  $ftp->ftp_login('PAC0736','Eku3q1dxIAbXe2u39Jtv');
  if (file_exists ($destino)){
    unlink($destino);
  }

  $ftp->descargar("catalogo_xml/productos.json",$destino,FTP_BINARY);
  $ftp->desconectar();
  $fmodif=date("Y-m-d H:i:s");
  if (file_exists ($destino)){
    ///////////////////////////////////////    PROCESO  /////////////////////////////////////////////////////////////
    $db = new Productos();
/*
    $sql="TRUNCATE TABLE producto_exist";
    $sth0 = $db->dbh->prepare($sql);
    echo "Truncate:".$sth0->execute();

    $sql="ALTER TABLE producto_exist AUTO_INCREMENT = 1";
    $sth0 = $db->dbh->prepare($sql);
    $sth0->execute();
*/

    $data = file_get_contents($destino);
    $products = json_decode($data, true);
    $x="";

    $i=0;
    foreach ($products as $product) {
        try{
          $sql="select * from productos where idProducto='".$product['idProducto']."' limit 1";
          $stmt= $db->dbh->query($sql);
          if($stmt->rowCount()==0){
            $sql="insert into productos (idProducto, clave, numParte, nombre, modelo, idMarca, marca, idCategoria, categoria, idSubCategoria, subcategoria, descripcion_corta, precio, moneda, tipoCambio, imagen, upc, activo, modificado) values (:idProducto, :clave, :numParte, :nombre, :modelo, :idMarca, :marca, :idCategoria, :categoria, :idSubCategoria, :subcategoria, :descripcion_corta, :precio, :moneda, :tipoCambio, :imagen, :upc, :activo, :modificado)";
            $sth = $db->dbh->prepare($sql);
            $sth->bindValue(':idProducto', $product['idProducto']);
            $sth->bindValue(':clave', $product['clave']);
            $sth->bindValue(':numParte', $product['numParte']);
            $sth->bindValue(':nombre', $product['nombre']);
            $sth->bindValue(':modelo', $product['modelo']);
            $sth->bindValue(':idMarca', $product['idMarca']);
            $sth->bindValue(':marca', $product['marca']);
            $sth->bindValue(':idCategoria', $product['idCategoria']);
            $sth->bindValue(':categoria', $product['categoria']);
            $sth->bindValue(':idSubCategoria', $product['idSubCategoria']);
            $sth->bindValue(':subcategoria', $product['subcategoria']);
            $sth->bindValue(':descripcion_corta', $product['descripcion_corta']);
            $sth->bindValue(':imagen', $product['imagen']);
            $sth->bindValue(':upc', $product['upc']);
          }
          else{
            $sql="update productos set precio=:precio, moneda=:moneda, tipoCambio=:tipoCambio, imagen=:imagen, activo=:activo, modificado=:modificado where idProducto='".$product['idProducto']."'";
            $sth = $db->dbh->prepare($sql);
          }
          $sth->bindValue(':precio', $product['precio']);
          $sth->bindValue(':moneda', $product['moneda']);
          $sth->bindValue(':tipoCambio', $product['tipoCambio']);
          $sth->bindValue(':imagen', $product['imagen']);
          $sth->bindValue(':activo', $product['activo']);
          $sth->bindValue(':modificado', $fmodif);
          $sth->execute();

          $sql="delete from producto_exist where idProducto='".$product['idProducto']."'";
          $sth3 = $db->dbh->prepare($sql);
          $sth3->execute();

          while (current($product['existencia'])) {
            $name=key($product['existencia']);
            $valor=$product['existencia'][$name];

            $sql="insert into producto_exist (idProducto, almacen, existencia) values (:idProducto, :almacen, :existencia)";
            $sth2 = $db->dbh->prepare($sql);
            $sth2->bindValue(':idProducto', $product['idProducto']);
            $sth2->bindValue(':almacen', $name);
            $sth2->bindValue(':existencia', $valor);
            $sth2->execute();
            next($product['existencia']);
          }

          $sql="delete from producto_espe where idProducto='".$product['idProducto']."'";
          $sth3 = $db->dbh->prepare($sql);
          $sth3->execute();

          foreach($product['especificaciones'] as $key){
            $sql="insert into producto_espe (idProducto, tipo, valor) values (:idProducto, :tipo, :valor)";
            $sth2 = $db->dbh->prepare($sql);
            $sth2->bindValue(':idProducto', $product['idProducto']);
            $sth2->bindValue(':tipo', $key['tipo']);
            $sth2->bindValue(':valor', $key['valor']);
            $sth2->execute();
          }

        }
        catch(PDOException $e){
          return "Database access FAILED! ".$e->getMessage();
        }
        $i++;
/*
        if ($i==2){
          break;
        }
*/
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////
  }

  $date=date("YmdHis");
  $file="file_".$date.".json";
  rename($destino, "../historial/$file");
  echo "<br>".date("Y-m-d H:i:s");
?>
