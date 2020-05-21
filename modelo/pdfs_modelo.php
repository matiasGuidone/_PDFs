<?php
class pdfs_model{
    private $db;
    private $pdfs;
    private $table;
   
    public function __construct(){
        $this->db=Conectar::conexion();
        $this->pdfs = array();
    }
    public function db(){
        return $this->db;
    }


    public function get_pdfs(){
        $consulta=$this->db->query("select * from pdfs;");
        while($filas = $consulta->fetch_assoc()){
            $this->pdfs[]=$filas;
        }
        return $this->pdfs;
    }

    public function getByCod($codPdf){
        $query = $this->db->query("SELECT * FROM pdfs WHERE codPdf = '$codPdf';");
         
        if($row = $query->fetch_object()) {
           $resultSet = $row;
        }
         
        return $resultSet;
    }
    public function InsertPdf($usuario, $blob){
        $permitted_chars = '0123456789abcdef0123456789ghijklmnop0123456789qrstuvwxyzAB0123456789CDEFG0123456789HIJKLMNOPQRSTUVWXYZ';
        $codPdf = substr(str_shuffle($permitted_chars), 0, 6);
        $query="INSERT INTO pdfs (usuario , blobPdf, codPdf)
                VALUES('$usuario', '$blob', '$codPdf');";
        $this->db()->query($query);

         
        return $codPdf;
    }


}
?>
