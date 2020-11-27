<?php
require_once '../modelos/Ascensor.php';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$ascensor = new Ascensor();

$idventa = isset($_POST["idventa"]) ? limpiarCadena($_POST["idventa"]) : "";
$estadoins = isset($_POST["estadoins"]) ? $_POST["estadoins"] : "";

switch ($_GET["op"]) {

     case 'listar':
        $rspta = $ascensor->listarpro($idventa);
        $data = Array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "idascensor"=>$reg->idascensor,
                "marca"=>$reg->marca,
                "modelo"=>$reg->modelo,
                "codigo"=>$reg->codigo,
                "strmodelo"=>$reg->strmodelo,
                "strmarca"=>$reg->strmarca,
                "strestado"=>$reg->strestado,
                "estadoins"=>$reg->estadoins,
                "ken"=> is_null($reg->ken) ? 0 : $reg->ken,
                "estado"=>$reg->estado,
                "color"=>$reg->color,
                "ubicacion"=>is_null($reg->ubicacion) ? "" : $reg->ubicacion,
                "carga"=> $reg->carga
                );
        }
        
        echo json_encode($data);
        break;
        
    case 'estado':
        
        $ascensor->estado($idascensor, (intval($estadoins)+1));
        echo $rspta ? "REGISTRADA" : "ERROR AL REGISTRAR";
        
        break;
}