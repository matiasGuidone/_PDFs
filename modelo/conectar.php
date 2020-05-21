<?php
class Conectar{
    public static function conexion(){
        $conexion=new mysqli("localhost:3306", "root", "", "PDF");
        $conexion->query("SET NAMES 'utf8'");
        return $conexion;
    }
}
?>
