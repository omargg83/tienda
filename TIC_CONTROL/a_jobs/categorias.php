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


  $sql="select * from productos group by categoria";
  $stmt1= $db->dbh->query($sql);
  foreach($stmt1 as $key){
    echo "<hr>";
    echo "<br>".$key['categoria'];
    $sql="select * from categoria_ct where categoria='".$key['categoria']."'";
    $categoria = $db->dbh->prepare($sql);
    $categoria->execute();
    $cat=$categoria->fetch();
    if(is_array($cat)){
      /////////////////si existe se actualizan las subcategorias
      echo "<br>Existe";

      $sql="SELECT * FROM productos where categoria='".$cat['categoria']."' group by subcategoria";
      echo $sql;
      $sub = $db->dbh->prepare($sql);
      $sub->execute();

      foreach($sub as $key2){
        echo "<br>Subcategoria: ".$key2['subcategoria'];
        $sql="select * from categoriasub_ct where subcategoria='".$key2['subcategoria']."'";
        $subcat = $db->dbh->prepare($sql);
        $subcat->execute();
        $subx=$subcat->fetch();
        if(is_array($subx)){
          echo "<br>Existe subcat";
        }
        else{
          echo "<br>NO existe subcat";
          $sql="insert into categoriasub_ct (idcategoria, subcategoria, interno) values('".$cat['id']."','".$key2['subcategoria']."', 0)";
          echo $sql;
          echo "<br>";

          $cat_inserta = $db->dbh->prepare($sql);
          $cat_inserta->execute();
        }
      }
    }
    else{
      ///////////////inserta en categorias si no existe
      $sql="insert into categoria_ct (categoria, heredado, interno) as values('".$key['categoria']."','".$key['categoria']."', 0)";
      $cat_inserta = $db->dbh->prepare($sql);
      $cat_inserta->execute();
    }
  }
?>
