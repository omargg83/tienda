<?php
  session_start();

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
      $mysqluser="ticshopc_admin";
      $mysqlpass="admin123$%";
      $servidor ="tic-shop.com.mx";
      $bdd="ticshopc_tienda";
      $this->dbh = new PDO("mysql:host=$servidor;dbname=$bdd", $mysqluser, $mysqlpass);
      self::set_names();
    }
    public function set_names(){
      return $this->dbh->query("SET NAMES 'utf8'");
    }
  }
  $db = new Tienda();
  echo "inicio->".date("Y-m-d H:i:s");

  $destino="file.json";     //////////////////////ARCHIVO JSON

  ///////////////////////////////////////CLASE PARA EL FTP
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

  //////////////////////////////////////////////DESCARGA EL ARCHIVO JSON
  $ftp = new ftp('216.70.82.104');
  $ftp->ftp_login('PAC0736','Eku3q1dxIAbXe2u39Jtv');
  if (file_exists ($destino)){
    unlink($destino);          //////////////////SE BORRA EL ANTERIOR JSON EN CASO DE EXISTIR
  }

  $ftp->descargar("catalogo_xml/productos.json",$destino,FTP_BINARY);
  $ftp->desconectar();

  $fmodif=date("Y-m-d H:i:s");    ///////////////   HORA DE MOFIDICACION
  $update_hora=date("H");         ///////////////   VARIABLE PARA ACTUALIZAR
  /////////////////////////////////SI SE DESCARGA EL JSON HAY PROCESO

  if (file_exists ($destino)){
    ///////////////////////////////////////    PROCESO  /////////////////////////////////////////////////////////////


    //////////////////////////////////////   JSON SE CONVIERTE EN ARREGLO
    $data = file_get_contents($destino);
    $products = json_decode($data, true);
    $x="";
    $nuevo=0;
    $i=0;
    ////////////////////////////////////     SE RECORRE CADA PRODUCTO DEL JSON
    foreach ($products as $product) {
        try{
          ////////////////////////BUSCAMOS EL PRODUCTO DEL JSON SI EXISTE SOLO SE ACTUALIZA, SI NO EXISE SE INGRESA
          $sql="select idProducto from productos where idProducto='".$product['idProducto']."' limit 1";
          $stmt= $db->dbh->query($sql);
          //echo "<br>".$product['idProducto'];
          if($stmt->rowCount()==0){
            $nuevo=1;
            $sql="insert into productos (idProducto, clave, numParte, nombre, modelo, idMarca, marca, idCategoria, categoria, idSubCategoria, subcategoria, descripcion_corta, precio, moneda, tipoCambio, preciof, imagen, upc, activo, modificado, interno, alta, precio_tipo, envio_tipo) values (:idProducto, :clave, :numParte, :nombre, :modelo, :idMarca, :marca, :idCategoria, :categoria, :idSubCategoria, :subcategoria, :descripcion_corta, :precio, :moneda, :tipoCambio, :preciof,:imagen, :upc, :activo, :modificado, :interno, :alta, :precio_tipo, :envio_tipo)";
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
            $sth->bindValue(':alta', $fmodif);
            $sth->bindValue(':precio_tipo', 1);
            $sth->bindValue(':envio_tipo', 0);
          }
          else{
            $nuevo=0;
            $sql="update productos set precio=:precio, moneda=:moneda, tipoCambio=:tipoCambio, preciof=:preciof, imagen=:imagen, activo=:activo, modificado=:modificado, interno=:interno where idProducto='".$product['idProducto']."'";
            $sth = $db->dbh->prepare($sql);
          }

          //////////////////////////// SOLO SE ACTUALIZAN UNOS CAMPOS
          $sth->bindValue(':precio', $product['precio']);
          $sth->bindValue(':moneda', $product['moneda']);
          $sth->bindValue(':tipoCambio', $product['tipoCambio']);
          $total=round($product['precio']*$product['tipoCambio'],2);
          $sth->bindValue(':preciof', $total);
          $sth->bindValue(':imagen', $product['imagen']);
          $sth->bindValue(':activo', $product['activo']);
          $sth->bindValue(':modificado', $fmodif);
          $sth->bindValue(':interno', 0);

          if($sth->execute()){

            $sql="select id, idProducto from productos where idProducto='".$product['idProducto']."'";
            $prod= $db->dbh->query($sql);
            $prod->execute();
            $resp=$prod->fetch();
            $id=$resp['id'];

            //////////////////////////// SE BORRAN LAS EXISTENCIAS DEL PRODUCTO PARA ACTUALIZARLAS
            $sql="delete from producto_exist where id='$id'";
            $sth3 = $db->dbh->prepare($sql);
            $sth3->execute();

            $existencia=0;
            ///////////////////////////  SE ACTUALIZAN LAS EXISTENCIAS CADA HORA
            if (is_array($product['existencia'])){
              while (current($product['existencia'])) {
                $name=key($product['existencia']);
                $valor=$product['existencia'][$name];

                $sql="insert into producto_exist (id, idProducto, almacen, existencia) values (:id, :idProducto, :almacen, :existencia)";
                $sth2 = $db->dbh->prepare($sql);
                $sth2->bindValue(':id', $id);
                $sth2->bindValue(':idProducto', $product['idProducto']);
                $sth2->bindValue(':almacen', $name);
                $sth2->bindValue(':existencia', $valor);
                $sth2->execute();
                $existencia+=$valor;
                next($product['existencia']);
              }
            }
            $fechaext=mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));
            $fexist=date("Y-m-d H:i:s");
            $sql="update productos set existencia='$existencia', timeexis='$fechaext', horaexist='$fexist' where id='$id'";
            $stmt2= $db->dbh->query($sql);

            ////////////////////////////  SE ACTUALIZAN LAS ESPECIFICACIONES A LAS 8 DE LA MAÑANA Y LAS 8 DE LA NOCHE
            if ($nuevo==1){
              $sql="delete from producto_espe where id='".$id."'";
              $sth3 = $db->dbh->prepare($sql);
              $sth3->execute();
              if (is_array($product['especificaciones'])){
                foreach($product['especificaciones'] as $key){
                  $sql="insert into producto_espe (id, idProducto, tipo, valor) values (:id, :idProducto, :tipo, :valor)";
                  $sth2 = $db->dbh->prepare($sql);
                  $sth2->bindValue(':id', $id);
                  $sth2->bindValue(':idProducto', $product['idProducto']);
                  $sth2->bindValue(':tipo', $key['tipo']);
                  $sth2->bindValue(':valor', $key['valor']);
                  $sth2->execute();
                }
              }
            }

          }
        }
        catch(PDOException $e){
          return "Database access FAILED! ".$e->getMessage();
        }
        $i++;
    }

    $sql="update productos set existencia=0, modificado='$fmodif' where modificado!='$fmodif' and interno=0";
    $sth4 = $db->dbh->prepare($sql);
    $sth4->execute();

  }
  echo "finalizo";
  echo "fin->".date("Y-m-d H:i:s");
?>
