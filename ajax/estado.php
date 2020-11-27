<?php 
session_start();
require_once "../modelos/Estado.php";


$estado = new Estado();

switch ($_GET["op"]) {
        
    case 'ContarDatos':
    
        $results = array(
                "contratos"=>$estado->contratos(),
                "clientes"=>$estado->clientes(), 
                "edificios"=>$estado->edificios(), 
                "ascensores"=>$estado->ascensores(),
                "ascensoresMultimarca"=>$estado->ascensoresMultimarca(),
                "ascensoresRecuperadosKone"=>$estado->ascensoresRecuperadosKone(),
                "ascensoresNuevosKone"=>$estado->ascensoresNuevosKone()
            );

        echo json_encode($results);
        break;
        
    case 'DatosGrafico':
        $rspta=$estado->contarcontratos();
        $data = Array();
        while ($reg = $rspta->fetch_object()){
            $data[] = array(
                "0"=>$reg->mes,
                "1"=>$reg->contratos
            );
        }
        $results = array(
            "Totalalertas"=>count($data),
            "aaData"=>$data
        );
        
        echo json_encode($results);
        break;


    case 'DatosGraficoAnt':
        $rspta=$estado->contarcontratosant();
        $data = Array();
        while ($reg = $rspta->fetch_object()){
            $data[] = array(
                "0"=>$reg->mes,
                "1"=>$reg->contratos
            );
        }
        $results = array(
            "Totalalertas"=>count($data),
            "aaData"=>$data
        );
        
        echo json_encode($results);
        break;
        
    case 'DatosGraficoxregion':
        $mes_busqueda = isset($_REQUEST['mes']) ? $_REQUEST['mes'] : "";
        $anio_busqueda = isset($_REQUEST['ano']) ? $_REQUEST['ano'] : "";
        
        $rspta=$estado->graficoAscfecha($mes_busqueda, $anio_busqueda);
        $data = Array();
        while ($reg = $rspta->fetch_object()){
            $data[] = array(
                "0"=>$reg->region_numero,
                "1"=>$reg->in,
                "2"=>$reg->out
            );
        }
        $results = array(
            "Totalalertas"=>count($data),
            "aaData"=>$data
        );
        
        echo json_encode($results);
        break;
        
    case "selectano":
        $rspta = $estado->SelectAno();

        while($reg = $rspta->fetch_object()){
             echo '<option value='.$reg->ano.'>'.$reg->ano.'</option>';
        }
        break;
    
      case 'GraficoIngresoSalidaAscensoresPorMes':

        $anio_busq = isset($_REQUEST['anio_busq']) ? $_REQUEST['anio_busq'] : "YEAR(NOW())";
        $tipo_equipo = isset($_REQUEST['tipo_equipo']) ? $_REQUEST['tipo_equipo'] : "TODOS";
        
        $rspta = $estado->IngresoAscensoresPorMes($anio_busq,$tipo_equipo);
        $data_ingreso = Array();
        while ($reg = $rspta->fetch_object()) {
            $data_ingreso[] = array(
                "mes" => $reg->mes,
                "cantidad" => $reg->cantidad
            );
        }
        
        $rspta = $estado->SalidaAscensoresPorMes($anio_busq,$tipo_equipo);
        $data_salida = Array();
        while ($reg = $rspta->fetch_object()) {
            $data_salida[] = array(
                "mes" => $reg->mes,
                "cantidad" => $reg->cantidad
            );
        }
        
        echo json_encode( array('ingreso'=>$data_ingreso,'salida'=>$data_salida) );
        
        break;    
        
        
}

 ?>