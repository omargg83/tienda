<?php
  require_once("../control_db.php");
  class Existencia extends Tienda{
  	public function __construct(){
  		parent::__construct();
  	}
  }
  $db = new Existencia();


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
          $sql="insert into categoriasub_ct (idcategoria, subcategoria) values('".$cat['id']."','".$key2['subcategoria']."')";
          echo $sql;
          echo "<br>";

          $cat_inserta = $db->dbh->prepare($sql);
          $cat_inserta->execute();

        }


      }

    }
    else{
      ///////////////inserta en categorias si no existe
      $sql="insert into categoria_ct (categoria) as values(".$key['categoria'].")";
      $cat_inserta = $db->dbh->prepare($sql);
      $cat_inserta->execute();
    }

    //if (is_array($product['existencia'])){
    /*

    $stmt2= $db->dbh->query($sql);
    if($stmt2->rowCount()==0){

      echo "<br>-----------NO EXISTE";
      //$sql="insert into categoria_ct (categoria) as values(".$key['categoria'].")";
      //$sth = $db->dbh->prepare($sql);



    }
    else{
      echo "<br>--------------Existe";
      echo $stmt2



    }
    */
  }


 ?>
