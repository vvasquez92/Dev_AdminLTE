<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/conexion.php';

class VisitasInstalaciones{
   
    function __construct() {
        
    }
    
    function insertar( $idproyecto, $idestado, $observacion, $created_user, $idascensor){
        $sql = "INSERT INTO `visita`(`idproyecto`, `idestado`, `observacion`, `created_user`, `idascensor`) "
                . " VALUES ($idproyecto, $idestado, '$observacion', $created_user, $idascensor)";
        //var_dump($sql);
        return ejecutarConsulta($sql);
    }
    
    
     function editar($idvisita, $idproyecto, $idestado, $observacion, $acta, $created_time, $created_user, $idascensor){
        $sql = "UPDATE `visita` SET "
                . "`idestado`= $idestado,"
                . "`observacion`= '$observacion',"
                . "`acta`= '$acta',"
                . "`created_time`= '$created_time',"
                . "`created_user`= $created_user, "
                . "`idascensor` = $idascensor "
                . " WHERE `idvisita`= $idvisita, "
                . "`idproyecto`= $idproyecto,";
        
        return ejecutarConsulta($sql);
    }
    
     function mostrar($idproyecto){
        $sql = "SELECT * FROM `visita` WHERE `idproyecto`= $idproyecto,";
        return ejecutarConsultaSimpleFila($sql);
    }
    
    
     function listar($idproyecto){
        $sql = "SELECT * FROM `visita` WHERE `idproyecto`= $idproyecto,";
        return ejecutarConsulta($sql);
    }
    
   public function numerovisita($idproyecto){
        $sql = "SELECT (count(*) + 1) as 'nrovisita' FROM proyecto p inner join `visita` v  on p.idproyecto = v.idproyecto Where p.idproyecto = $idproyecto and v.idestado = p.estado";
        return ejecutarConsultaSimpleFila($sql);
    }
    
    public function pdfdatos($idproyecto) {
        $sql = "SELECT cc.codigo as cc, ve.codigo, pro.nombre AS proyecto, DATE_FORMAT(ve.fecha , '%d-%m-%Y') AS fecha, imp.codigo AS codigoimp, imp.proveedor, tven.descripcion, CONCAT(pro.calle,' ', pro.numero, '. ', cop.comuna_nombre,', ', rep.region_nombre,' ', rep.region_ordinal) AS direcpro, IF(ve.mantencionpost=1,'SI', 'NO') AS man_post, IF(ve.garantiaex=1,'SI', 'NO') AS garan_ex, pro.estado, CONCAT(ven.nombre, ' ', ven.apellido) AS vendedor, espro.nombre AS estado, pro.tipoproyecto, DATE_FORMAT(pro.created_time , '%d-%m-%Y') AS fechaini, IF(pro.created_time IS NOT NULL, DATE_FORMAT(pro.updated_time, '%d-%m-%Y'), 'SIN AVANCE') AS fechaact, IF(pro.closed_time IS NOT NULL, DATE_FORMAT(pro.updated_time, '%d-%m-%Y'), 'EN PROCESO') AS fechaclo FROM venta ve INNER JOIN proyecto pro ON ve.idventa = pro.idventa INNER JOIN importacion imp ON ve.idimportacion = imp.idimportacion INNER JOIN tventa tven ON ve.idtventa = tven.idtventa INNER JOIN mandante man ON ve.idmandante = man.idmandante INNER JOIN centrocosto cc ON ve.idcentrocosto=cc.idcentrocosto INNER JOIN regiones rep ON rep.region_id = pro.idregion  INNER JOIN comunas cop ON pro.idcomuna = cop.comuna_id INNER JOIN vendedor ven ON ve.idvendedor=ven.idvendedor INNER JOIN estadopro espro ON pro.estado = espro.idestadopro WHERE pro.idproyecto = '$idproyecto'";
        return ejecutarConsultaSimpleFila($sql);
    }
    
    public function cantequipos($idproyecto) {
        $sql = "SELECT ascen.idascensor FROM proyecto pro INNER JOIN ascensor ascen ON pro.idventa = ascen.idventa WHERE pro.idproyecto = '$idproyecto'";
        return NumeroFilas($sql);
    }
    
    public function cantvisitas($idproyecto) {
        $sql = "SELECT idvisita FROM visita WHERE idproyecto = '$idproyecto'";
        return NumeroFilas($sql);
    }
    
    public function infoetapas($idproyecto) {
        $sql = "SELECT DATE_FORMAT(ep.created_time , '%d-%m-%Y') AS fecha, ep.duracion, estpro.nombre FROM etapa_proyecto ep INNER JOIN estadopro estpro ON ep.idestadopro = estpro.idestadopro  WHERE idproyecto = '$idproyecto'";
        return ejecutarConsulta($sql);
    }
    
    public function infovi($idproyecto) {
        $sql = "SELECT DATE(vi.created_time) AS fecha, vi.created_time AS fehora, (SELECT nombre FROM estadopro WHERE idestadopro = MIN(vi.idestado)) as estado FROM visita vi INNER JOIN proyecto pro ON vi.idproyecto = pro.idproyecto INNER JOIN estadopro estpro ON pro.estado = estpro.idestadopro WHERE vi.idproyecto = '$idproyecto' GROUP BY fehora ORDER BY fecha ASC";
        return ejecutarConsulta($sql);
    }
    
    public function infovife($fecha, $idproyecto) {
        $sql = "SELECT ascen.codigo, vi.observacion, estpro.nombre, CONCAT(u.nombre, ' ', u.apellido) AS colaborador FROM visita vi INNER JOIN ascensor ascen ON vi.idascensor = ascen.idascensor INNER JOIN estadopro estpro ON vi.idestado = estpro.idestadopro INNER JOIN user u ON u.iduser = vi.created_user WHERE vi.created_time = '$fecha' AND vi.idproyecto = '$idproyecto'";
        return ejecutarConsulta($sql);
    }
    
    
    public function dashinstalacion($idproyecto){
        $sql = "SELECT v.* , u.imagen, DAY(v.created_time) as 'dia', DATE_FORMAT(v.created_time, '%M') as 'mes', concat(u.nombre, ' ', u.apellido) as 'user', a.codigo, DATE_FORMAT(v.created_time, '%d-%m-%Y') as 'fecha'
                FROM `visita` v
                INNER JOIN user u on u.iduser = v.created_user
                INNER JOIN ascensor a on a.idascensor = v.idascensor
                Where v.idproyecto = $idproyecto 
                order by v.created_time desc
                LIMIT 5";
        return ejecutarConsulta($sql);
    }
    
    public function visitaproyectos() {
        $sql="SELECT a.idproyecto,g.nombre as nombre_proyecto,g.codigo, h.nombre as nombre_estado, 
            h.color,a.created_user,
              CONCAT(e.nombre,', ',e.apellido) as nombre_tecnico,
              f.descripcion as cargo,
             (ROUND( count(a.idvisita) /
             (SELECT COUNT(d.idascensor) 
             FROM proyecto as b
             INNER JOIN venta as c on b.idventa = c.idventa
             INNER JOIN ascensor as d on d.idventa =c.idventa
             WHERE b.idproyecto=a.idproyecto
             GROUP BY b.idproyecto)
             ) ) as cantidad_visitas 
            FROM `visita` as a 
            INNER JOIN user as e on e.iduser = a.created_user
            INNER JOIN role as f on f.idrole = e.idrole
            INNER JOIN proyecto as g on g.idproyecto = a.idproyecto
            INNER JOIN estadopro as h on h.estado = g.estado
            group by a.idproyecto,a.created_user
            order by a.idproyecto,a.created_user";
        
        return ejecutarConsulta($sql);
    }
    
    public function visitasetapa($idproyecto,$created_user) {
        $sql=" SELECT e.`idestadopro`, e.`estado`, e.`nombre` as nombre_estado, e.`color`, e.`carga`, e.`condicio`,
                ifnull( ( SELECT (ROUND( count(a.idvisita) /
                             (SELECT COUNT(d.idascensor) 
                             FROM proyecto as b
                             INNER JOIN venta as c on b.idventa = c.idventa
                             INNER JOIN ascensor as d on d.idventa =c.idventa
                             WHERE b.idproyecto=a.idproyecto
                             GROUP BY b.idproyecto)
                             ) ) 
                        from visita as a 
                        where a.idproyecto = $idproyecto
                        and a.created_user = $created_user
                        and a.idestado = e.estado
                        GROUP BY a.idproyecto,a.created_user,a.idestado
                        ORDER by a.idproyecto,a.created_user,a.idestado ),0) as cantidad_visitas 
                 FROM `estadopro` e
                 WHERE (e.estado >= 0 and e.estado <= 99 ) and e.condicio = 1
                 ORDER BY e.estado ASC";
        
        return ejecutarConsulta($sql);
        
    }
}