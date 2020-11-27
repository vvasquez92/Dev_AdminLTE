<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/conexion.php';

class Proyecto {

    function __construct() {
        
    }

    function listar($rut, $tipoproyecto) {
        $supervisor = 0;
        $pm = 0;

        $sql1 = "Select (select count(*) from supervisorins s Where s.rut = '" . $rut . "') as 'supervisor', "
                . "(select count(*) from pm Where pm.rut = '" . $rut . "')as 'pm'";
        $rspta = ejecutarConsulta($sql1);

        while ($reg = $rspta->fetch_object()) {
            $supervisor = $reg->supervisor;
            $pm = $reg->pm;
        }

        $sql = "Select p.idproyecto, p.idventa, p.nombre, p.codigo, p.estado, DATE(p.created_time) as fecha,"
                . " e.nombre as 'estadonomb', e.color, e.carga, c.codigo as 'ccnomb' , "
                . "concat(pm.nombre, ' ', pm.apellido) as 'pm', concat(s.nombre, ' ', s.apellido) as 'supervisor',"
                . " DATE_FORMAT(p.updated_time, '%d-%m-%Y') as 'updated_time', "
                . "ifnull(datediff(curdate(), p.updated_time),0) as 'dias', "
                . "ifnull(datediff(curdate(), p.created_time),0) as 'dias_comienzo' "
                . "from proyecto p "
                . "INNER JOIN estadopro e on e.estado = p.estado "
                . "INNER JOIN venta ve ON p.idventa = ve.idventa INNER JOIN centrocosto c on ve.idcentrocosto=c.idcentrocosto "
                . "INNER JOIN pm pm on pm.idpm = p.idpm "
                . "INNER JOIN supervisorins s on s.idsupervisorins = p.idsupervisor "
                . "WHERE p.estado <> 12 and p.tipoproyecto IN ($tipoproyecto)";
        /*
        $sql = "Select p.idproyecto, p.idventa, p.nombre, p.codigo, p.estado, DATE(p.created_time) as fecha,"
                . " e.nombre as 'estadonomb', e.color, e.carga, c.codigo as 'ccnomb' , "
                . "concat(pm.nombre, ' ', pm.apellido) as 'pm', concat(s.nombre, ' ', s.apellido) as 'supervisor',"
                . " DATE_FORMAT(p.updated_time, '%d-%m-%Y') as 'updated_time', "
                . "ifnull(datediff(curdate(), p.updated_time),0) as 'dias', "
                . "ifnull(datediff(curdate(), p.created_time),0) as 'dias_comienzo' "
                . "from proyecto p "
                . "INNER JOIN estadopro e on e.estado = p.estado "
                . "INNER JOIN venta ve ON p.idventa = ve.idventa INNER JOIN centrocosto c on ve.idcentrocosto=c.idcentrocosto "
                . "INNER JOIN pm pm on pm.idpm = p.idpm "
                . "INNER JOIN supervisorins s on s.idsupervisorins = p.idsupervisor "
                . "WHERE p.estado <> 12 and p.tipoproyecto = $tipoproyecto and ve.estado = 3";*/

        if ($supervisor <> 0) {
            $sql .= " AND s.rut = '$rut'";
        } else {
            if ($pm <> 0) {
                $sql .= " AND pm.rut = '$rut'";
            }
        }

        $sql .= " ORDER BY p.nombre asc";

        return ejecutarConsulta($sql);
    }
    
    function Insertar($idventa, $idsupervisor, $idpm, $codigo, $nombre, $estado, $tipoproyecto, $created_user){
        $sql = "INSERT INTO `proyecto`( `idventa`, `idsupervisor`, `idpm`, `codigo`, `nombre`, `estado`, `tipoproyecto`, `created_user`) "
                . "VALUES ($idventa,$idsupervisor,$idpm,'$codigo', '$nombre' , $estado, $tipoproyecto, $created_user)";
        return ejecutarConsulta($sql);
    }
    
     function InsertarNUEVO($idventa, $idtsegmento, $idtclasificacion, $idsupervisor, $idpm, $codigo, $nombre,$calle, $numero, $idregion, $idcomuna, $estado, $tipoproyecto, $created_user, $nombre_ven, $rut_ven, $email_ven, $telefono_ven, $nombre_obra, $rut_obra, $email_obra, $telefono_obra){
        $sql = "INSERT INTO `proyecto`(`idventa`, `idtsegmento`, `idtclasificacion`, `idsupervisor`, `idpm`, `codigo`, `nombre`, `calle`, "
                . "`numero`, `idregion`, `idcomuna`, `estado`, `tipoproyecto`, `nom_ven`, `rut_ven`, `email_ven`, `tele_ven`, `nom_obra`, `rut_obra`, `email_obra`, `tele_obra`, `created_user`) "
                . "VALUES ( $idventa, $idtsegmento, $idtclasificacion, $idsupervisor,$idpm,'$codigo','$nombre','$calle',"
                . "'$numero',$idregion,$idcomuna,$estado,$tipoproyecto,'$nombre_ven','$rut_ven','$email_ven','$telefono_ven','$nombre_obra','$rut_obra','$email_obra','$telefono_obra', $created_user)";
        
        return ejecutarConsulta_retornarID($sql);
    }
    
    function EditaNUEVO($idproyecto, $idventa, $idtsegmento, $idtclasificacion, $idsupervisor, $idpm, $codigo, $nombre,$calle, $numero, $idregion, $idcomuna, $estado, $tipoproyecto, $updated_user, $closed_time, $closed_user, $nombre_ven, $rut_ven, $email_ven, $telefono_ven, $nombre_obra, $rut_obra, $email_obra, $telefono_obra){
        $sql = "UPDATE `proyecto` "
                . "SET `idventa`= $idventa,"
                . "`idtsegmento` = $idtsegmento,"
                . "`idtclasificacion` = $idtclasificacion,"
                . "`idsupervisor`= $idsupervisor,"
                . "`idpm`= $idpm,"
                . "`codigo`= '$codigo',"
                . "`nombre`= '$nombre',"
                . "`calle` = '$calle' ,"
                . "`numero` = '$numero',"
                . "`idregion` = $idregion, "
                . "`idcomuna` = $idcomuna,"
                . "`estado`= $estado,"
                . "`tipoproyecto`= $tipoproyecto,"
                . "`nom_ven`= '$nombre_ven',"
                . "`rut_ven`= '$rut_ven',"
                . "`email_ven`= '$email_ven',"
                . "`tele_ven`= '$telefono_ven',"
                . "`nom_obra`= '$nombre_obra',"
                . "`rut_obra`= '$rut_obra',"
                . "`email_obra`= '$email_obra',"
                . "`tele_obra`= '$telefono_obra',"
                . "`updated_time`= CURRENT_TIMESTAMP,"
                . "`updated_user`= $updated_user,"
                . "`closed_time`= $closed_time,"
                . "`closed_user`= $closed_user "
                . "WHERE `idproyecto`= $idproyecto";
        
        return ejecutarConsulta($sql);
    }
    
    function mostrarNUEVO($idventa) {
        $sql = "Select p.*,DATE(p.created_time) as fecha, e.nombre as 'estadonomb', c.codigo as 'ccnomb' , concat(pm.nombre, ' ', pm.apellido) as 'pm', concat(s.nombre, ' ', s.apellido) as 'supervisor', (count(v.idascensor) + 1) as 'nrovisita', ifnull(datediff(curdate(), p.updated_time),0) as 'dias' "
                . " from proyecto p  "
                . "INNER JOIN estadopro e on e.estado = p.estado  "
                . "INNER JOIN venta ve ON p.idventa = ve.idventa INNER JOIN centrocosto c on ve.idcentrocosto=c.idcentrocosto "
                . "LEFT JOIN pm pm on pm.idpm = p.idpm "
                . "LEFT JOIN supervisorins s on s.idsupervisorins = p.idsupervisor "
                . "LEFT JOIN visita v on p.idproyecto = v.idproyecto and v.idestado = p.estado "
                . "WHERE p.idventa = $idventa "
                . "group by v.idascensor LIMIT 1";
        //var_dump($sql);
        return ejecutarConsultaSimpleFila($sql);
    }
    
    
    function Editaproyecto($idproyecto, $idventa, $idsupervisor, $idpm, $codigo, $nombre, $estado, $tipoproyecto, $updated_user, $closed_time, $closed_user){
        $sql = "UPDATE `proyecto` "
                . "SET `idventa`= $idventa,"
                . "`idsupervisor`= $idsupervisor,"
                . "`idpm`= $idpm,"
                . "`codigo`= '$codigo',"
                . "`nombre`= '$nombre',"
                . "`estado`= $estado,"
                . "`tipoproyecto`= $tipoproyecto,"
                . "`updated_time`= CURRENT_TIMESTAMP,"
                . "`updated_user`= $updated_user,"
                . "`closed_time`= $closed_time,"
                . "`closed_user`= $closed_user "
                . "WHERE `idproyecto`= $idproyecto";
        return ejecutarConsulta($sql);
    }
    
    
    /********************** EDITAR PROYECTO SUPERVISOR PROJECTMANAGER**************/
    function listarnoPMSUP($rut, $tipoproyecto) {
        $supervisor = 0;
        $pm = 0;

        $sql1 = "Select (select count(*) from supervisorins s Where s.rut = '" . $rut . "') as 'supervisor', "
                . "(select count(*) from pm Where pm.rut = '" . $rut . "')as 'pm'";
        $rspta = ejecutarConsulta($sql1);

        while ($reg = $rspta->fetch_object()) {
            $supervisor = $reg->supervisor;
            $pm = $reg->pm;
        }

        $sql = "Select p.idventa, p.idproyecto, p.nombre, p.codigo, p.estado, DATE(p.created_time) as fecha, e.nombre as 'estadonomb', e.color, e.carga, c.codigo as 'ccnomb' , concat(pm.nombre, ' ', pm.apellido) as 'pm', concat(s.nombre, ' ', s.apellido) as 'supervisor', DATE_FORMAT(p.updated_time, '%d-%m-%Y') as 'updated_time', ifnull(datediff(curdate(), p.updated_time),0) as 'dias' "
                . "from proyecto p "
                . "INNER JOIN estadopro e on e.estado = p.estado "
                . "INNER JOIN venta ve ON p.idventa = ve.idventa INNER JOIN centrocosto c on ve.idcentrocosto=c.idcentrocosto "
                . "LEFT JOIN pm pm on pm.idpm = p.idpm "
                . "LEFT JOIN supervisorins s on s.idsupervisorins = p.idsupervisor "
                . "WHERE p.estado <> 12 AND p.tipoproyecto IN ($tipoproyecto) AND ((p.idpm is null) or (p.idsupervisor is null)) AND ve.estado = 3";

        if ($supervisor <> 0) {
            $sql .= " AND s.rut = '$rut'";
        } else {
            if ($pm <> 0) {
                $sql .= " AND pm.rut = '$rut'";
            }
        }

        return ejecutarConsulta($sql);
    }
    
    function mostrarNoPmSup($idproyecto) {
        $sql = "Select p.*,DATE(p.created_time) as fecha, e.nombre as 'estadonomb', c.codigo as 'ccnomb' , concat(pm.nombre, ' ', pm.apellido) as 'pm', concat(s.nombre, ' ', s.apellido) as 'supervisor', (count(v.idascensor) + 1) as 'nrovisita', ifnull(datediff(curdate(), p.updated_time),0) as 'dias' "
                . " from proyecto p  "
                . "INNER JOIN estadopro e on e.estado = p.estado  "
                . "INNER JOIN venta ve ON p.idventa = ve.idventa INNER JOIN centrocosto c on ve.idcentrocosto=c.idcentrocosto "
                . "LEFT JOIN pm pm on pm.idpm = p.idpm "
                . "LEFT JOIN supervisorins s on s.idsupervisorins = p.idsupervisor "
                . "LEFT JOIN visita v on p.idproyecto = v.idproyecto and v.idestado = p.estado "
                . "WHERE p.idproyecto = $idproyecto "
                . "group by v.idascensor LIMIT 1";

        return ejecutarConsultaSimpleFila($sql);
    }
    
    function Editar($idproyecto, $idPm, $idsupervisor, $updated_user){
        $sql = "UPDATE `proyecto` SET "
                . "`idsupervisor`= $idsupervisor,"
                . "`idpm`= $idPm,"
                . "`updated_time`= CURRENT_TIMESTAMP,"
                . "`updated_user`= $updated_user "
                . "WHERE `idproyecto`= $idproyecto";
        return ejecutarConsulta($sql);
    }
    /********************** EDITAR PROYECTO SUPERVISOR PROJECTMANAGER **************/

    function mostrar($idproyecto) {
        $sql = "Select p.*,DATE(p.created_time) as fecha, e.nombre as 'estadonomb', c.codigo as 'ccnomb' , concat(pm.nombre, ' ', pm.apellido) as 'pm', concat(s.nombre, ' ', s.apellido) as 'supervisor', (count(v.idascensor) + 1) as 'nrovisita', ifnull(datediff(curdate(), p.updated_time),0) as 'dias' "
                . " from proyecto p  "
                . "INNER JOIN estadopro e on e.estado = p.estado  "
                . "INNER JOIN venta ve ON p.idventa = ve.idventa INNER JOIN centrocosto c on ve.idcentrocosto=c.idcentrocosto "
                . "INNER JOIN pm pm on pm.idpm = p.idpm "
                . "INNER JOIN supervisorins s on s.idsupervisorins = p.idsupervisor "
                . "LEFT JOIN visita v on p.idproyecto = v.idproyecto and v.idestado = p.estado "
                . "WHERE p.idproyecto = $idproyecto "
                . "group by v.idascensor LIMIT 1";

        return ejecutarConsultaSimpleFila($sql);
    }
    
    //DASHBOARD
    function dashboardinstalacion($idproyecto){
        $sql = "Select p.*,DATE_FORMAT(p.created_time, '%d-%m-%Y') as fecha, e.nombre as 'estadonomb', c.codigo as 'ccnomb' , concat(pm.nombre, ' ', pm.apellido) as 'pm', concat(s.nombre, ' ', s.apellido) as 'supervisor', (count(v.idascensor) + 1) as 'nrovisita', ifnull(datediff(curdate(), p.updated_time),0) as 'dias', DATE_FORMAT(p.updated_time, '%d-%m-%Y') as fechaactualiza ,
                usup.imagen as 'imgsup', upm.imagen as 'imgproject', im.codigo as 'importacion', e.carga
                from proyecto p  
                INNER JOIN estadopro e on e.estado = p.estado  
                INNER JOIN venta ve ON p.idventa = ve.idventa INNER JOIN centrocosto c on ve.idcentrocosto=c.idcentrocosto 
                INNER JOIN pm pm on pm.idpm = p.idpm 
                INNER JOIN supervisorins s on s.idsupervisorins = p.idsupervisor 
                LEFT JOIN visita v on p.idproyecto = v.idproyecto and v.idestado = p.estado
                INNER JOIN user usup on usup.num_documento = s.rut
                INNER JOIN user upm on upm.num_documento = pm.rut
                INNER JOIN importacion im ON im.idimportacion = ve.idimportacion
                WHERE p.idproyecto = $idproyecto group by v.idascensor LIMIT 1";
        return ejecutarConsultaSimpleFila($sql);
    }
    
    function proyectosDashboard(){
        $sql = "Select idproyecto from proyecto Where estado in (2,3,4,5,6,7,8,9,10,11)";
        return ejecutarConsulta($sql);
    }
    
    public function etapa($idproyecto, $estado, $update_time, $update_user) {
        $sql = "UPDATE proyecto SET estado='$estado', `updated_time`= '$update_time',`updated_user`= $update_user WHERE idproyecto='$idproyecto'";
        return ejecutarConsulta($sql);
    }

    public function etapafinal($idproyecto, $estado, $update_time, $update_user, $closed_time, $closed_user) {
        $sql = "UPDATE proyecto SET estado= '$estado', `updated_time`= '$update_time',`updated_user`= $update_user "
                . ",`closed_time`= '$closed_time' ,`closed_user`= $closed_user "
                . " WHERE idproyecto= '$idproyecto'";
        return ejecutarConsulta($sql);
    }
    
    public function mostrarproyecto($idproyecto) {
        $sql="SELECT idproyecto,nombre,codigo FROM proyecto where idproyecto=".$idproyecto;
        return ejecutarConsultaSimpleFila($sql);
    }

    public function listaDatosProyecto($idproyecto) {
        $sql="SELECT idpm, idsupervisor FROM proyecto where idproyecto=".$idproyecto;        
        return ejecutarConsulta($sql);
    }

    public function editarPmSupervisor($idProyecto,$idPm,$idSupervisor){
        $sql="UPDATE proyecto set idpm = ".$idPm.", idsupervisor = ".$idSupervisor. " WHERE idproyecto=".$idProyecto;        
        return ejecutarConsulta($sql);
    }

}
