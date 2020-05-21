<?php
      
        if (isset($_GET["codPdf"])) {
            require_once("../modelo/conectar.php");
            require_once("../modelo/pdfs_modelo.php");
            $codPdf = $_GET["codPdf"];
            $pdf = new pdfs_model();
          
            $_pdf = $pdf->getByCod($codPdf);
            echo json_encode($_pdf);
        }
     
     
        if ($_POST != null) {
            $json = file_get_contents('php://input');
            // Los convertimos en un array
            $json = json_decode( $json, true );
             
            require_once("../modelo/conectar.php");
            require_once("../modelo/pdfs_modelo.php");
            
            $pdf = new pdfs_model();
            $blobPdf = $json["blobPdf"];
            $usuario = $json["usuario"]; 
            $codigo = $pdf->InsertPdf($usuario, $blobPdf);
            echo $codigo;
        }
     

?>