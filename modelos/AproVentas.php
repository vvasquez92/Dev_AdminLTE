<?php

require "../config/conexion.php";

Class AproVentas {

    //Constructor para instancias
    public function __construct() {
        
    }

    public function LV() {
        $sql = "SELECT ve.idventa, pro.nombre AS proyecto, imp.codigo, CONCAT(man.razon_social, ' - ', man.rut) AS mandante, DATE(ve.created_time) AS fecha, CONCAT(ven.nombre, ' ', ven.apellido) as vendedor  FROM venta ve INNER JOIN proyecto pro ON ve.idventa = pro.idventa INNER JOIN importacion imp ON ve.idimportacion = imp.idimportacion INNER JOIN estadopro esta ON pro.estado = esta.estado INNER JOIN mandante man ON ve.idmandante = man.idmandante INNER JOIN vendedor ven ON ve.idvendedor=ven.idvendedor WHERE ve.condicion = 1 AND ve.estado=1  ORDER BY pro.nombre ASC";
        return ejecutarConsulta($sql);
    }
    
    public function pdfdatos($idventa) {
        $sql = "SELECT CONCAT(ven.nombre, ' ', ven.apellido) AS vendedor,cc.codigo as cc, cc.nombre as ccnom,  ve.codigo, pro.nom_ven, pro.rut_ven, pro.email_ven, pro.tele_ven, pro.nom_obra, pro.rut_obra, pro.email_obra, pro.tele_obra, pro.nombre AS proyecto, DATE_FORMAT(ve.fecha , '%d-%m-%Y') AS fecha, imp.codigo AS codigoimp, imp.nef, imp.proveedor, tven.descripcion, CONCAT(pro.calle,' ', pro.numero, '. ', cop.comuna_nombre,', ', rep.region_nombre,' ', rep.region_ordinal) AS direcpro, man.razon_social, man.rut, CONCAT(man.calle, ' ', man.numero, '. ', cov.comuna_nombre, ', ', rev.region_nombre, ' ', rev.region_ordinal) AS direcman, IF(ve.contrato=1,'SI', 'NO') AS contrato, IF(ve.retenciones=1,'SI', 'NO') AS retenciones, IF(ve.multas=1,'SI', 'NO') AS multas, IF(ve.oc=1,'SI', 'NO') AS oc, IF(ve.mantencionpost=1,'SI (3 meses)', 'NO') AS man_post, IF(ve.garantiaex=1,'SI (12 meses)', 'NO') AS garan_ex  FROM venta ve INNER JOIN proyecto pro ON ve.idventa = pro.idventa INNER JOIN importacion imp ON ve.idimportacion = imp.idimportacion INNER JOIN tventa tven ON ve.idtventa = tven.idtventa INNER JOIN mandante man ON ve.idmandante = man.idmandante INNER JOIN centrocosto cc ON ve.idcentrocosto=cc.idcentrocosto INNER JOIN regiones rep ON rep.region_id = pro.idregion  INNER JOIN comunas cop ON pro.idcomuna = cop.comuna_id INNER JOIN regiones rev ON man.idregion = rev.region_id INNER JOIN comunas cov ON man.idcomuna = cov.comuna_id INNER JOIN vendedor ven ON ve.idvendedor=ven.idvendedor WHERE ve.idventa = '$idventa'";
        return ejecutarConsultaSimpleFila($sql);
    }
    
    public function ninfo($idventa) {
        $sql = "SELECT descripcion FROM infoventa info INNER JOIN ascensor ascen ON info.idascensor = ascen.idascensor WHERE ascen.idventa = '$idventa' GROUP BY info.descripcion";
        return NumeroFilas($sql);
    }
    
    public function pdfinfo($idventa) {
        $sql = "SELECT DISTINCT info.descripcion,  DATE_FORMAT(info.fecha , '%d-%m-%Y') AS fecha FROM infoventa info INNER JOIN ascensor ascen ON info.idascensor = ascen.idascensor WHERE ascen.idventa = '$idventa' ORDER BY info.fecha ASC";
        return ejecutarConsulta($sql);
    }
    
    public function pdfformasi($idventa) {
        $sql = "SELECT form.descripcion ,form.porcentaje FROM formapago form INNER JOIN ascensor ascen ON form.idascensor = ascen.idascensor WHERE ascen.idventa = '$idventa' AND idtformapago = 1 GROUP BY form.descripcion ORDER BY form.secuencia ASC";
        return ejecutarConsulta($sql);
    }
    
    public function pdfformasn($idventa) {
        $sql = "SELECT form.descripcion ,form.porcentaje FROM formapago form INNER JOIN ascensor ascen ON form.idascensor = ascen.idascensor WHERE ascen.idventa = '$idventa' AND idtformapago = 2 GROUP BY form.descripcion ORDER BY form.secuencia ASC";
        return ejecutarConsulta($sql);
    }
      
    public function pdfboleta($idventa) {
        $sql = "SELECT banco, descripcion, documento, tipo, DATE_FORMAT(validez , '%d-%m-%Y') AS validez FROM boletas WHERE idventa = '$idventa'";
        return ejecutarConsulta($sql);
    }
    
    public function pdfequipos($idventa) {
        $sql = "SELECT ascen.codigo, ascen.ken, ascen.capkg, ascen.capper, ascen.paradas, ascen.accesos, ascen.comando, ascen.velocidad, tascen.nombre AS tascensor, CONCAT(mar.nombre, ' ', mo.nombre) AS modelo FROM ascensor ascen INNER JOIN tascensor tascen ON ascen.idtascensor = tascen.idtascensor INNER JOIN marca mar ON ascen.marca = mar.idmarca LEFT JOIN modelo mo ON ascen.modelo = mo.idmodelo WHERE ascen.idventa='$idventa'";
        return ejecutarConsulta($sql);
    }
    
    public function pdfMontoPro($idventa) {
        $sql = "SELECT ven.montosn, monsn.nombre AS nomsn, monsn.simbolo AS simsn, ven.montosi, monsi.nombre AS nomsi, monsi.simbolo AS simsi FROM venta ven INNER JOIN moneda monsn ON ven.idmonedasn = monsn.idmoneda INNER JOIN moneda monsi ON ven.idmonedasi = monsi.idmoneda WHERE ven.idventa = '$idventa'";
        return ejecutarConsultaSimpleFila($sql);
    }
    
    public function pdfMontoEquipos($idventa) {
        $sql = "SELECT ascen.codigo, ascen.ken, tascen.nombre, CONCAT(mar.nombre, ' ', mo.nombre) AS modelo, ascen.montosi, ascen.montosn, monsn.simbolo AS simsn, monsi.simbolo AS simsi FROM ascensor ascen INNER JOIN tascensor tascen ON ascen.idtascensor = tascen.idtascensor INNER JOIN marca mar ON ascen.marca = mar.idmarca LEFT JOIN modelo mo ON ascen.modelo = mo.idmodelo INNER JOIN venta ven ON ascen.idventa = ven.idventa LEFT JOIN moneda monsn ON ven.idmonedasn = monsn.idmoneda LEFT JOIN moneda monsi ON ven.idmonedasi = monsi.idmoneda WHERE ascen.idventa ='$idventa'";
        return ejecutarConsulta($sql);
    }

}

?>