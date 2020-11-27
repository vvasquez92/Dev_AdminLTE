<?php

require_once '../config/conexion.php';

class Ascensor{
    function __construct() {
        
    }
     public function SelectAscensores() {
        $sql = "SELECT a.idascensor, t.nombre AS tascensor, m.nombre AS marca, a.codigo, a.codigocli, a.ken, a.ubicacion "
                . "FROM ascensor a "
                . "INNER JOIN tascensor t ON a.idtascensor=t.idtascensor "
                . "INNER JOIN marca m ON a.marca = m.idmarca "
                . "WHERE a.codigo IS NOT NULL AND a.condicion = 1 and a.idcontrato IS NOT null "
                . "and idventa is NULL ";
        return ejecutarConsulta($sql);
    }
    
    public function listarAscEstados($estado){
        $sql = "SELECT a.codigo, a.idedificio, a.ubicacion, a.codigocli, e.nombre as 'stredificio', a.idcontrato, c.ncontrato, a.marca, m.nombre as 'strmarca', a.modelo,ifnull(mo.nombre, '') as 'strmodelo' "
                . "FROM `ascensor` a "
                . "INNER JOIN edificio e on e.idedificio = a.idedificio "
                . "INNER JOIN contrato c on c.idcontrato = a.idcontrato "
                . "LEFT JOIN marca m on m.idmarca = a.marca "
                . "LEFT JOIN modelo mo on mo.idmodelo = a.modelo "
                . "Where a.estado in ($estado) "
                . "ORDER BY a.`idcontrato`  DESC";
        return ejecutarConsulta($sql);
    }
    
    function listarpro($idventa){
        $sql = "SELECT a.* , ifNull(mo.nombre, '') as 'strmodelo' ,mar.nombre as 'strmarca', st.nombre as 'strestado', st.color , ta.nombre as 'tiponomb', st.carga "
                . "FROM `ascensor` as a "
                . "INNER JOIN marca as mar on mar.idmarca = a.marca "
                . "left JOIN modelo as mo on mo.idmodelo = a.modelo and mo.marca = a.marca "
                . "INNER JOIN estadopro as st on st.estado = a.estadoins "
                . "INNER JOIN tascensor as ta on ta.idtascensor = a.idtascensor "
                . "Where a.idventa = $idventa";
        return ejecutarConsulta($sql);
    }
    
    function listarxedificio($idedificio, $idcontrato){
        $sql = "SELECT a.* , ifNull(mo.nombre, '') as 'strmodelo' ,mar.nombre as 'strmarca', st.nombre as 'strestado', st.color , ta.nombre as 'tiponomb' "
            . "FROM `ascensor` as a  "
            . "INNER JOIN marca as mar on mar.idmarca = a.marca  "
            . "LEFT JOIN modelo as mo on mo.idmodelo = a.modelo and mo.marca = a.marca "
            . "LEFT JOIN estadopro as st on st.estado = a.estadoins "
            . "INNER JOIN tascensor as ta on ta.idtascensor = a.idtascensor "
            . "WHERE a.idedificio = $idedificio and a.idcontrato = $idcontrato";
        return ejecutarConsulta($sql);
    }
    
    public function mostrar($idascensor) {
        $sql = "SELECT a.*, e.nombre AS edificio, c.ncontrato AS contrato, n.nombre AS modeloNomb, m.nombre AS marcaNomb, v.nombre AS tipo "
                . "FROM ascensor a "
                . "INNER JOIN edificio e ON a.idedificio =e.idedificio "
                . "INNER JOIN contrato c ON a.idcontrato = c.idcontrato "
                . "INNER JOIN marca m ON a.marca=m.idmarca "
                . "INNER JOIN modelo n ON a.modelo=n.idmodelo "
                . "INNER JOIN tascensor v ON a.idtascensor = v.idtascensor "
                . "WHERE a.idascensor='$idascensor'";
        return ejecutarConsultaSimpleFila($sql);
    }
    
    public function editarNUEVO($idascensor, $idventa,  $montosi, $montosn, $updated_user) {
        $sql = "UPDATE `ascensor` SET "
                . "`idventa`= $idventa,"
                . "`estadoins` =  1,"
                . "`montosi`= $montosi,"
                . "`montosn`= $montosn,"
                . "`updated_time`= CURRENT_TIMESTAMP,"
                . "`updated_user`= $updated_user "
                . "WHERE `idascensor`= $idascensor";
        return ejecutarConsulta($sql);
    }
    
     public function editarContrato($idascensor, $idcontrato , $updated_user) {
        $sql = "UPDATE `ascensor` SET "
                . "`idcontrato` = $idcontrato, "
                . "`updated_time`= CURRENT_TIMESTAMP,"
                . "`updated_user`= $updated_user "
                . "WHERE `idascensor`= $idascensor";
        return ejecutarConsulta($sql);
    }
    
    
    public  function getEdificio($idcontrato){
        $sql = "Select DISTINCT idedificio from ascensor Where idcontrato = $idcontrato";
        return ejecutarConsultaSimpleFila($sql);
    }
            
    function estado($idascensor, $estado){
        $sql = "UPDATE `ascensor` SET `estadoins` = $estado WHERE `idascensor` = $idascensor";
        return ejecutarConsulta($sql);
    }
    
    function estadotodos($idventa, $estado){
        $sql = "UPDATE `ascensor` SET `estadoins` = $estado WHERE `idventa` = $idventa";
        return ejecutarConsulta($sql);
    }
    
    function menoretapa($idventa){
        $sql = "Select min(estadoins) as 'estado' from ascensor where idventa =$idventa";
        return ejecutarConsulta($sql);
    }
    
    public function InsertarIds($idascensor, $codigo, $iduser) {
        $sql = "UPDATE ascensor SET codigo= '$codigo', updated_time=CURRENT_TIMESTAMP, updated_user='$iduser' WHERE idascensor='$idascensor'";
        return ejecutarConsulta($sql);
    }

    public function insertar($iduser, $idedicon, $idtascensor, $marca, $modelo, $valoruf, $valorclp, $paradas, $capper, $capkg, $velocidad, $pservicio, $gtecnica, $ken, $dcs, $elink) {
        $sql = "INSERT INTO ascensor (iduser, idedificio_contrato, idtascensor, marca, modelo, ken, paradas, capper, capkg, velocidad, dcs, elink, valoruf, valorclp, pservicio, gtecnica, condicion,  created_user) VALUES ('$iduser', '$idedicon', '$idtascensor','$marca','$modelo', '$ken', '$paradas', '$capper', '$capkg', '$velocidad', '$dcs', '$elink', '$valoruf', '$valorclp', '$pservicio', '$gtecnica', 1,'$iduser')";
        return ejecutarConsulta($sql);
    }
    
    public function InsertarAscensor($idedificio, $idcontrato, $idtascensor, $marca, $modelo, $ubicacion, $codigo, $codigocli, $ken, $paradas, $capper, $capkg, $velocidad, $dcs, $elink, $pservicio, $gtecnica, $created_user) {
        $sql = "INSERT INTO ascensor (idedificio, idcontrato, idtascensor, marca, modelo, ubicacion, codigo, codigocli, ken, paradas, capper, capkg, velocidad, dcs, elink, pservicio, gtecnica, created_user) "
             . "VALUES ('$idedificio', '$idcontrato', '$idtascensor','$marca','$modelo', '$ubicacion', '$codigo', '$codigocli', '$ken', '$paradas', '$capper', '$capkg', '$velocidad', '$dcs', '$elink', '$pservicio', '$gtecnica', '$created_user')";
        return ejecutarConsulta($sql);
    }
    
    public function InsertarAscensorNUEVO($idedificio, $idcontrato, $idventa, $idtascensor, $marca, $modelo, $ubicacion, $codigo, $codigocli, $ken, $paradas, $capper, $capkg, $velocidad, $dcs, $elink, $pservicio, $gtecnica, $created_user, $estadoins, $montosi, $montosn, $comando, $accesos) {
        $filas = 0;
        
        if($codigo){
            $filas = $this->VerificarCodigo($codigo);
        }
        
        if ($filas > 0){
            return '0';
        }else{
            $sql = "INSERT INTO ascensor (idedificio, idcontrato, idventa, idtascensor, marca, modelo, ubicacion, codigo, codigocli, ken, paradas, capper, capkg, velocidad, dcs, elink, pservicio, gtecnica, created_user, estadoins,`montosi`, `montosn`, `comando`, `accesos`) "
                 . "VALUES($idedificio, $idcontrato, $idventa, $idtascensor,'$marca','$modelo', '$ubicacion', '$codigo', '$codigocli', '$ken', '$paradas', '$capper', '$capkg', '$velocidad', $dcs, $elink, '$pservicio', '$gtecnica', '$created_user', $estadoins, '$montosi', '$montosn', '$comando', $accesos)";
            return ejecutarConsulta($sql);
        }
    }
    
    public function solid_ascid($idcontrato) {
        $sql = "SELECT a.idascensor, m.nombre as marca, o.nombre as modelo, e.nombre, e.calle, e.numero, e.idedificio, r.region_nombre, r.region_ordinal FROM ascensor a INNER JOIN edificio_contrato w ON a.idedificio_contrato=w.idedificio_contrato INNER JOIN edificio e ON w.idedificio=e.idedificio INNER JOIN regiones r ON e.idregiones = r.region_id INNER JOIN marca m ON a.marca=m.idmarca INNER JOIN modelo o ON a.modelo=o.idmodelo WHERE w.idcontrato='$idcontrato' AND a.codigo IS null";
        return ejecutarConsulta($sql);
    }

    public function solid_edifid($idcontrato) {
        $sql = "SELECT COUNT(a.idascensor) AS nascensores, e.idedificio FROM ascensor a INNER JOIN edificio_contrato w ON a.idedificio_contrato=w.idedificio_contrato INNER JOIN edificio e ON w.idedificio=e.idedificio WHERE w.idcontrato='$idcontrato' GROUP BY e.idedificio";
        return ejecutarConsulta($sql);
    }

    public function ascensores_contrato($idcontrato) {
        $sql = "SELECT a.codigo, a.codigocli, a.ken, m.nombre AS marca, b.nombre AS modelo, e.nombre AS edificio, r.region_nombre AS region, n.comuna_nombre AS comuna FROM ascensor a INNER JOIN edificio e ON a.idedificio = e.idedificio INNER JOIN marca m ON a.marca = m.idmarca INNER JOIN modelo b ON a.modelo = b.idmodelo INNER JOIN regiones r ON e.idregiones = r.region_id INNER JOIN comunas n ON e.idcomunas = n.comuna_id  WHERE a.idcontrato = '$idcontrato'";
        return ejecutarConsulta($sql);
    }

    public function ascensores_edificio($idedificio) {
        $sql = "SELECT a.codigo, m.nombre AS marca, b.nombre AS modelo, a.valoruf,c.ncontrato, x.nombre AS tipo FROM ascensor a  INNER JOIN contrato c ON a.idcontrato=c.idcontrato INNER JOIN edificio e ON a.idedificio = e.idedificio INNER JOIN marca m ON a.marca = m.idmarca INNER JOIN modelo b ON a.modelo = b.idmodelo INNER JOIN tascensor x ON a.idtascensor=x.idtascensor WHERE e.idedificio='$idedificio'";
        return ejecutarConsulta($sql);
    }

    public function ascensores_cliente($idcliente) {
        $sql = "SELECT a.codigo, m.nombre AS marca, b.nombre AS modelo, a.valoruf,c.ncontrato, e.nombre AS edificio, x.nombre AS tipo FROM ascensor a INNER JOIN contrato c ON a.idcontrato=c.idcontrato INNER JOIN edificio e ON a.idedificio = e.idedificio INNER JOIN marca m ON a.marca = m.idmarca INNER JOIN modelo b ON a.modelo = b.idmodelo INNER JOIN tascensor x ON a.idtascensor=x.idtascensor WHERE c.idcliente = '$idcliente' GROUP BY c.idcliente";
        return ejecutarConsulta($sql);
    }

    public function listar() {
        $sql = "SELECT a.idascensor, a.codigo, a.valoruf, e.nombre AS edificio, c.ncontrato AS contrato, a.condicion "
                . "FROM ascensor a "
                . "INNER JOIN edificio e ON a.idedificio=e.idedificio "
                . "INNER JOIN contrato c ON a.idcontrato = c.idcontrato "
                . "ORDER BY e.nombre ASC";
        return ejecutarConsulta($sql);
    }

    public function cambiarcondicion($idascensor, $condicion){
            $sql="UPDATE ascensor SET condicion='$condicion' WHERE idascensor ='$idascensor'";
            return ejecutarConsulta($sql);
    }
    
    public function editar($idascensor, $idtascensor, $marca, $modelo, $ken, $pservicio, $gtecnica, $valoruf, $valorclp, $paradas, $capkg, $capper, $velocidad, $dcs, $elink, $iduser) {
        $sql = "UPDATE ascensor SET idtascensor='$idtascensor', marca='$marca', modelo='$modelo', ken='$ken', pservicio='$pservicio', gtecnica='$gtecnica', valoruf='$valoruf', valorclp='$valorclp', paradas='$paradas', capkg='$capkg', capper='$capper', velocidad='$velocidad', dcs='$dcs', elink='$elink', updated_time=CURRENT_TIMESTAMP, updated_user='$iduser' WHERE idascensor='$idascensor'";
        return ejecutarConsulta($sql);
    }

    public function formeditar($idascensor) {
        $sql = "SELECT * FROM ascensor WHERE idascensor='$idascensor'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function guia($codigo) {
        $sql = "SELECT a.idascensor, a.codigo , e.nombre AS edificio, e.calle, e.numero, n.nombre AS modelo, m.nombre AS marca, v.nombre AS tipo, w.nombre AS estado, w.id AS idtestado FROM ascensor a INNER JOIN edificio e ON a.idedificio=e.idedificio INNER JOIN marca m ON a.marca=m.idmarca INNER JOIN modelo n ON a.modelo=n.idmodelo INNER JOIN tascensor v ON a.idtascensor = v.idtascensor INNER JOIN testado w ON a.estado=w.id WHERE a.codigo='$codigo'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function UpEstado($idascensor, $estado) {
        $sql = "UPDATE ascensor SET estado='$estado'WHERE idascensor='$idascensor'";
        return ejecutarConsulta($sql);
    }

    public function Estados() {
        $sql = "SELECT estado, COUNT(idascensor) as equipos FROM ascensor Where idcontrato is not null GROUP BY estado";
        return ejecutarConsulta($sql);
    }

    public function SelectAsc($idedificio) {
        $sql = "SELECT a.idascensor, t.nombre AS tascensor, m.nombre AS marca, a.codigo, a.codigocli, a.ken, a.ubicacion 
            FROM ascensor a 
            INNER JOIN tascensor t ON a.idtascensor=t.idtascensor 
            INNER JOIN marca m ON a.marca = m.idmarca 
            WHERE a.codigo IS NOT NULL 
            AND a.idedificio='$idedificio' 
            AND a.condicion = 1";
 
        return ejecutarConsulta($sql);
    }
    
    public function SelectAscensoresSinDirk($idedificio) {
        $sql = "SELECT a.idascensor, t.nombre AS tascensor, m.nombre AS marca, a.codigo, a.codigocli, a.ken, a.ubicacion 
            FROM ascensor a 
            INNER JOIN tascensor t ON a.idtascensor=t.idtascensor 
            INNER JOIN marca m ON a.marca = m.idmarca 
            WHERE a.codigo IS NOT NULL 
            AND a.idedificio='$idedificio' 
            AND a.condicion = 1
            AND a.iddirk IS NULL";  
         
        return ejecutarConsulta($sql);
    }
    
    public function VerificarCodigo($codigo){
        $sql="SELECT * FROM ascensor WHERE codigo='$codigo'";
            return Filas($sql);
    }
    
    public function VerificarKEN($ken){
        $sql="SELECT * FROM ascensor WHERE ken = '$ken'";
            return Filas($sql);
    }
    
    public function eliminar($idascensor){
        $sql = "DELETE FROM `ascensor` WHERE idascensor='$idascensor'";
        return ejecutarConsulta($sql);
    }
    
    public function actualizar_ascensor($idascensor,$iddirk,$lat,$lon) {
        
        $sql = "UPDATE `ascensor` SET iddirk=$iddirk,lat='$lat',lon='$lon' WHERE idascensor=$idascensor";
        
        return ejecutarConsulta($sql);
        
    }
    
    public function inhabilitarAscensoresDirk($iddirk) {
        
        $sql = "UPDATE ascensor SET iddirk=NULL  WHERE iddirk=$iddirk";
        
        return NumeroFilas($sql);
        
    }
    
    public function inhabilitarAscensorDirk($idascensor) {
        
        $sql = "UPDATE ascensor SET iddirk=NULL  WHERE idascensor=$idascensor";
        
        return NumeroFilas($sql);
        
    }
    
    public function listadoAscensores($idedificio,$iddirk) {
          
        $sql = "SELECT idascensor,codigo,codigocli,lat,lon from ascensor WHERE idedificio=$idedificio AND iddirk=$iddirk";
        
        return ejecutarConsulta($sql);
        
    }  
    
}