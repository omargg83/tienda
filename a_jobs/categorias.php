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
    if(is_array($categoria->fetch())){
      echo "<br>exsite";

      $sql="SELECT * FROM productos where categoria='".$key['categoria']."' group by subcategoria";
      $sub = $db->dbh->prepare($sql);
      $sub->execute();

      foreach($sub as $key){
        echo "<br>Subcategoria: ".$key['subcategoria'];
      }

    }
    else{
      echo "<br>no exsite";
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
