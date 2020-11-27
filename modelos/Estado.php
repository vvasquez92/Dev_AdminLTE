<?php

require "../config/conexion.php";

Class Estado {

    //Constructor para instancias
    public function __construct() {
        
    }

    public function contratos() {
        $sql = "SELECT COUNT(idcontrato) AS contratos FROM contrato";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function clientes() {
        $sql = "SELECT COUNT(idcliente) AS clientes FROM cliente WHERE condicion=1";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function edificios() {
        $sql = "SELECT COUNT(idedificio) AS edificios FROM edificio WHERE condicion=1 ";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function ascensores() {
        $sql = "SELECT COUNT(idascensor) AS ascensores FROM ascensor WHERE condicion = 1 and idcontrato is not null";
        return ejecutarConsultaSimpleFila($sql);
    }

     public function ascensoresMultimarca() {
        $sql = "SELECT COUNT(idascensor) AS cantidad FROM ascensor WHERE condicion = 1 and idcontrato is not null and marca <> 1";
        return ejecutarConsultaSimpleFila($sql);
    }
    
     public function ascensoresRecuperadosKone() {
        $sql = "SELECT COUNT(idascensor) AS cantidad FROM ascensor WHERE condicion = 1 and idcontrato is not null and marca = 1 and idventa is null";
        return ejecutarConsultaSimpleFila($sql);
    }
    
     public function ascensoresNuevosKone() {
        $sql = "SELECT COUNT(idascensor) AS cantidad FROM ascensor WHERE condicion = 1 and idcontrato is not null and marca = 1 and idventa is not null";
        return ejecutarConsultaSimpleFila($sql);
    }
    
    public function contarcontratos() {
        $sql = "SELECT MONTH(fecha) AS mes, COUNT(idcontrato) AS contratos FROM contrato WHERE YEAR(fecha)=YEAR(NOW()) GROUP BY MONTH(fecha)";
        return ejecutarConsulta($sql);
    }

    public function contarcontratosant() {
        $sql = "SELECT MONTH(fecha) AS mes, COUNT(idcontrato) AS contratos FROM contrato WHERE YEAR(fecha)=YEAR(NOW())-1 GROUP BY MONTH(fecha)";
        return ejecutarConsulta($sql);
    }

    public function graficoAscfecha($mes_busqueda, $anio_busqueda) {
        
        if ($mes_busqueda == 0) { $mes_busqueda = 12;}

        $sql = "Select IFNULL((SELECT count(a.idascensor) as 'ascensoresOUT' FROM `ascensor` a INNER JOIN contrato c ON c.idcontrato = a.idcontrato WHERE a.created_time <= '$anio_busqueda-$mes_busqueda-28' and c.idregiones = reg.region_id group by c.idregiones),0) 'out',"
                ."IFNULL((SELECT count(a.idascensor) as 'ascensoresINT' FROM `ascensor` a INNER JOIN contrato c ON c.idcontrato = a.idcontrato WHERE c.fecha <= '$anio_busqueda-$mes_busqueda-28' and c.idregiones = reg.region_id group by c.idregiones),0) 'in' ,"
                ."reg.region_numero "
                ."FROM regiones reg "
                . "order by reg.region_numero";
 
        return ejecutarConsulta($sql);
    }

    public function SelectAno() {
        $sql = "SELECT DISTINCT YEAR(fecha) 'ano' FROM `contrato` ORDER BY YEAR(fecha) DESC";
        return ejecutarConsulta($sql);
    }
    
    public function IngresoAscensoresPorMes($anio_busq,$tipo_equipo){

        if($tipo_equipo=="TODOS"){
             $cond_tipo_equipo = "";           
        }
        
        if($tipo_equipo=="MULTIMARCA"){
             $cond_tipo_equipo = " AND a.marca <> 1";               
        }
        
        if($tipo_equipo=="RECUPERADOS_KONE"){
             $cond_tipo_equipo = " AND a.marca = 1 AND a.idventa is null";               
        }
        
        if($tipo_equipo=="NUEVOS_KONE"){
            $cond_tipo_equipo = " AND a.marca = 1 AND a.idventa is not null";                
        }
        
        $sql = "SELECT YEAR(b.fecha) as anio, MONTH(b.fecha) as mes ,count(a.idascensor) as cantidad
                FROM ascensor as a
                inner join contrato as b on a.idcontrato = b.idcontrato
                where YEAR(b.fecha) = $anio_busq
                $cond_tipo_equipo    
                group by YEAR(b.fecha),MONTH(b.fecha)
                order by YEAR(b.fecha),MONTH(b.fecha)";
        return ejecutarConsulta($sql);
    }
    
     public function SalidaAscensoresPorMes($anio_busq,$tipo_equipo){
         
        if($tipo_equipo=="TODOS"){
             $cond_tipo_equipo = "";           
        }
        
        if($tipo_equipo=="MULTIMARCA"){
             $cond_tipo_equipo = " AND a.marca <> 1";               
        }
        
        if($tipo_equipo=="RECUPERADOS_KONE"){
             $cond_tipo_equipo = " AND a.marca = 1 AND a.idventa is null";               
        }
        
        if($tipo_equipo=="NUEVOS_KONE"){
            $cond_tipo_equipo = " AND a.marca = 1 AND a.idventa is not null";                
        }
     
        $sql = "SELECT YEAR(a.closed_time) as anio,
                MONTH(a.closed_time) as mes , 
                count(a.idascensor) as cantidad
                FROM ascensor as a
                inner join contrato as b on a.idcontrato = b.idcontrato
                where YEAR(a.closed_time) = $anio_busq
                and a.closed_time is not null
                $cond_tipo_equipo
                group by YEAR(a.closed_time),MONTH(a.closed_time)
                order by YEAR(a.closed_time),MONTH(a.closed_time)";
        
        return ejecutarConsulta($sql);
    }
    
}

?>