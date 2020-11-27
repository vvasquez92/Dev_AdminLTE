<?php
require_once '../config/conexion.php';
/**
 * Description of Venta
 *
 * @author azuni
 */
class Venta {
    //put your code here
    function __construct() {
        
    }
    
    function Insertar($idimportacion, $idcentrocosto, $idvendedor, $idmandante, $codigo, $permiso, $contrato, $retenciones, $multas, $oc, $garantiaex, $mesesgarex, $mantencionpost, $mesesmantp, $montosn, $monedasn, $montosi, $monedasi, 
                $observaciones, $created_user, $idtventa , $fecha, $adicionales){
        
        $sql = "INSERT INTO `venta`(`idimportacion`, `idcentrocosto`, `idvendedor`,`idmandante`, `codigo`, `permiso`, `contrato`, `retenciones`, `multas`, `oc`, `garantiaex`, mesesgarex, `mantencionpost`, mesesmantp,"
                . "`montosn`, `idmonedasn`, `montosi`, `idmonedasi`, `observaciones`, `created_user`, `idtventa`, `fecha`,`adicionales`) "
                . "VALUES ($idimportacion,$idcentrocosto,$idvendedor, $idmandante, '$codigo', '$permiso',$contrato, $retenciones,$multas, $oc , '$garantiaex','$mesesgarex', '$mantencionpost', '$mesesmantp', '$montosn' ,$monedasn, '$montosi',$monedasi,"
                . "'$observaciones', $created_user, $idtventa, '$fecha', '$adicionales')";
        var_dump($sql);
        return ejecutarConsulta_retornarID($sql);
    }
    
    function Editar($idventa, $idimportacion, $idcentrocosto, $idvendedor, $idmandante,$codigo, $permiso, $contrato, $retenciones, $multas, $oc, $garantiaex, $mesesgarex, $mantencionpost, $mesesmantp, $montosn, $monedasn, $montosi, $monedasi, 
                $observaciones, $condicion, $idtventa , $fecha, $adicionales){
        
        $sql = "UPDATE `venta` "
                . "SET `idimportacion`= '$idimportacion',"
                . "`idcentrocosto`= '$idcentrocosto',"
                . "`idvendedor`= '$idvendedor',"
                . "`idmandante`= '$idmandante', "
                . "`codigo`= '$codigo',"
                . "`permiso`= '$permiso',"
                . "`contrato`= '$contrato',"
                . "`retenciones`= '$retenciones',"
                . "`multas`= '$multas',"
                . "`oc`= '$oc',"
                . "`garantiaex` = '$garantiaex', "
                . " mesesgarex = '$mesesgarex', "
                . "`mantencionpost` = '$mantencionpost',"
                . " mesesmantp = '$mesesmantp', "
                . "`montosn`= '$montosn',"
                . "`idmonedasn`= '$monedasn',"
                . "`montosi`= '$montosi',"
                . "`idmonedasi`= '$monedasi',"
                . "`observaciones`= '$observaciones',"
                . "`condicion`= '$condicion', "
                . "`idtventa`= '$idtventa',"
                . "`fecha`= '$fecha' ,"
                . "`adicionales` = '$adicionales' "
                . "WHERE `idventa`= '$idventa'";
        
        var_dump($sql);
        return ejecutarConsulta($sql);
    }
    
    
    function Listar(){
        /*$sql = "SELECT v.*, m.razon_social, m.rut, m.calle, m.numero, i.codigo as 'codVenta', p.nombre as 'pronomb', p.calle as 'procalle' , p.numero as 'pronumero' "
                . ",(select count(*) FROM formapago fp INNER JOIN ascensor a ON a.idascensor = fp.idascensor Where a.idventa = v.idventa) as pagos "
                . ",(select count(*) FROM infoventa iv INNER JOIN ascensor a ON a.idascensor = iv.idascensor Where a.idventa = v.idventa) as infocontrato "
                . "FROM `venta` v "
                . "left join mandante m on m.idmandante = v.idmandante "
                . "left join importacion i on i.idimportacion = v.idimportacion "
                . "left join proyecto p on p.idventa = v.idventa "
                . "WHERE v.estado = 0 or v.estado = 2 and v.condicion = 1";*/
        $sql = "SELECT v.*, m.razon_social, m.rut, m.calle, m.numero, i.codigo as 'codVenta', p.nombre as 'pronomb', p.calle as 'procalle' , p.numero as 'pronumero' "
                . " ,c.codigo  as 'cccodigo', c.nombre, concat(ven.nombre, ' ', ven.apellido) as 'vendedor' "
                . " ,(select count(*) FROM formapago fp INNER JOIN ascensor a ON a.idascensor = fp.idascensor Where a.idventa = v.idventa) as pagos "
                . " ,(select count(*) FROM infoventa iv INNER JOIN ascensor a ON a.idascensor = iv.idascensor Where a.idventa = v.idventa) as infocontrato "
                . " FROM `venta` v "
                . " left join mandante m on m.idmandante = v.idmandante "
                . " left join importacion i on i.idimportacion = v.idimportacion "
                . " left join proyecto p on p.idventa = v.idventa "
                . " LEFT JOIN centrocosto c on c.idcentrocosto = v.idcentrocosto "
                . " LEFT JOIN vendedor ven on ven.idvendedor = v.idvendedor "
                . " WHERE v.estado = 0 or v.estado = 2 and v.condicion = 1 ";
        return ejecutarConsulta($sql);
    }
    
    function mostrar($idventa){
        $sql = "SELECT `idventa`, `idimportacion`, `idcentrocosto`, `idvendedor`, `idmandante`, `idtventa`, `codigo`, DATE_FORMAT(`fecha` , '%d-%m-%Y') as 'fecha', DATE_FORMAT(`permiso`, '%d-%m-%Y') as 'permiso', "
             . "`contrato`, `retenciones`, `multas`, `oc`, `garantiaex`, mesesgarex, `mantencionpost`,mesesmantp, `montosn`, `idmonedasn`, `montosi`, `idmonedasi`, `observaciones`, `adicionales`, `created_time`, `created_user`, `estado`, "
             . "`condicion` FROM venta Where idventa = $idventa";
        return ejecutarConsultaSimpleFila($sql);
    }
    
    Function cambiarEstado($idventa, $estado){
        $sql = "UPDATE `venta` "
                . "SET estado = $estado "
                . "WHERE `idventa`= $idventa ";
        return ejecutarConsulta($sql);
    }
    
    function validarventa($codigo){
        $sql = "SELECT count(*) as 'existe' FROM `venta` WHERE `codigo` LIKE '$codigo'";
        return ejecutarConsultaSimpleFila($sql);
    }
}

