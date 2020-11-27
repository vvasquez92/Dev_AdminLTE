<?php
require_once '../config/conexion.php';

/**
 * Description of EtapaProyecto
 *
 * @author aaron
 */
class EtapaProyecto {
    //put your code here
    
    function __construct()   {
        
    }
    
    function insertar($idproyecto, $idestadopro, $duracion){
        $sql = "INSERT INTO `etapa_proyecto`(`idproyecto`, `idestadopro`, `duracion`) "
                . "VALUES ($idproyecto, $idestadopro, $duracion)";
        return ejecutarConsulta($sql);
    }
    
    function ultimafecha($idproyecto){
        $sql = "SELECT max(`created_time`) as 'fecha', `duracion` FROM `etapa_proyecto` WHERE idproyecto = $idproyecto";
        
        return ejecutarConsulta($sql);
    }
    
    
}
