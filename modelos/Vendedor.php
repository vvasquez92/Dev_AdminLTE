<?php

require "../config/conexion.php";

Class Vendedor {

    //Constructor para instancias
    public function __construct() {
        
    }

    public function IDVendedor($rut) {
        $sql = "SELECT idvendedor FROM vendedor WHERE rut='$rut'";
        return ejecutarConsultaSimpleFila($sql);
    }


}

?>