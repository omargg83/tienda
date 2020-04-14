<?php
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

$destino="file.json";
$ftp = new ftp('216.70.82.104');
$ftp->ftp_login('PAC0736','Eku3q1dxIAbXe2u39Jtv');
if (file_exists ($destino)){
  unlink($destino);
}

$ftp->descargar("catalogo_xml/productos.json",$destino,FTP_BINARY);
$ftp->desconectar();


?>
