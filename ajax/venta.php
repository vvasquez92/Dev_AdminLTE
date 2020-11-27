<?php

session_start();
include_once '../modelos/Venta.php';
include_once '../modelos/AproVentas.php';
include_once '../modelos/Vendedor.php';

$venta = new Venta();
$aproven = new AproVentas();
$vendedor = new Vendedor();

$idventa = isset($_POST["idventa"]) ? $_POST["idventa"] : 0;
$idimportacion = isset($_POST["idimportacion"]) ? limpiarCadena($_POST["idimportacion"]) : "";
$idcentrocosto = isset($_POST["idcentrocosto"]) ? $_POST["idcentrocosto"] : 0;
$idvendedor = isset($_POST["idvendedor"]) ? $_POST["idvendedor"] : "NULL";
$idmandante = isset($_POST['idmandante']) ? $_POST['idmandante'] : 0;

$codigo = isset($_POST["codigo"]) ? limpiarCadena($_POST["codigo"]) : "";
$contrato = isset($_POST["contrato"]) ? $_POST["contrato"] : 0;
$retenciones = isset($_POST["retenciones"]) ? $_POST["retenciones"] : 0;
$multas = isset($_POST["multas"]) ? $_POST["multas"] : 0;
$oc = isset($_POST["oc"]) ? $_POST["oc"] : "";

$garantiaex = isset($_POST["garantiaex"]) ? $_POST["garantiaex"] : 0;
$mesesgarex = isset($_POST["mesesgarex"]) ? $_POST["mesesgarex"] : 0;

$mantencionpost = isset($_POST["mantencionpost"]) ? $_POST["mantencionpost"] : 0;
$mesesmantp = isset($_POST["mesesmantp"]) ? $_POST["mesesmantp"] : 0;

$montosn = isset($_POST["montosn"]) ? $_POST["montosn"] : 0;
$monedasn = isset($_POST["idmonedasn"]) ? $_POST["idmonedasn"] : 0;
$montosi = isset($_POST["montosi"]) ? $_POST["montosi"] : 0;
$monedasi = isset($_POST["idmonedasi"]) ? $_POST["idmonedasi"] : 0;
$observaciones = isset($_POST["observaciones"]) ? limpiarCadena($_POST["observaciones"]) : "";
$adicionales = isset($_POST["adicionales"]) ? limpiarCadena($_POST["adicionales"]) : "";
$created_user = $_SESSION['iduser'];
$condicion = isset($_POST["condicion"]) ? $_POST["condicion"] : 1;
$idtventa = isset($_POST["idtventa"]) ? $_POST["idtventa"] : 0;

$permiso = isset($_POST["permiso"]) ? ($_POST["permiso"] == "0000-00-00 00:00:00" ? "0000-00-00 00:00:00" : date('Y-m-d H:i:s', strtotime($_POST["permiso"]))) : "0000-00-00 00:00:00";
$fecha = isset($_POST["fecha"]) ? date_format(date_create($_POST["fecha"]), 'Y-m-d H:i:s') : "";


$estado = isset($_POST['estado']) ? $_POST['estado'] : 0;

switch ($_GET['op']) {
    case 'guardaryeditar':

        if (!$idventa) {
            $id = $vendedor->IDVendedor($_SESSION['rut']);
            $idvendedor = $id['idvendedor'];
            $rspta = $venta->Insertar($idimportacion, $idcentrocosto, $idvendedor, $idmandante, $codigo, $permiso, $contrato, $retenciones, $multas, $oc, $garantiaex, $mesesgarex, $mantencionpost, $mesesmantp, $montosn, $monedasn, $montosi, $monedasi, $observaciones, $created_user, $idtventa, $fecha, $adicionales);
            echo $rspta;
        } else {
            $id = $vendedor->IDVendedor($_SESSION['rut']);
            $idvendedor = $id['idvendedor'];
            $rspta = $venta->Editar($idventa, $idimportacion, $idcentrocosto, $idvendedor, $idmandante, $codigo, $permiso, $contrato, $retenciones, $multas, $oc, $garantiaex, $mesesgarex, $mantencionpost, $mesesmantp, $montosn, $monedasn, $montosi, $monedasi, $observaciones, $condicion, $idtventa, $fecha, $adicionales);
            echo $rspta == 1 ? $idventa : "0";
        }
        break;

    case 'listarpreventa':
        $rspta = $venta->Listar();
        $data = Array();
        while ($reg = $rspta->fetch_object()) {

            if ($reg->created_user == $_SESSION['iduser']) {
                if ($reg->pagos > 0 && $reg->infocontrato > 0) {
                    $op = '<button class="btn btn-info btn-xs" onclick="mostrar(' . $reg->idventa . ')"><i class="fa fa-eye"></i></button>'
                            . '<button class="btn btn-success btn-xs" onclick="enviararevision(' . $reg->idventa . ', 1)"><i class="fa fa-check"></i></button>'
                            . '<button class="btn btn-danger btn-xs" onclick="pdf(' . $reg->idventa . ')"><i class="fa fa-file-pdf-o" data-tooltip="tooltip" title="Memo PDF"></i></button>';
                } else {
                    $op = '<button class="btn btn-info btn-xs" onclick="mostrar(' . $reg->idventa . ')"><i class="fa fa-eye"></i></button>';
                }
            } else {
                if ($reg->pagos > 0 && $reg->infocontrato > 0) {
                    $op = '<button class="btn btn-danger btn-xs" onclick="pdfcorto(' . $reg->idventa . ')"><i class="fa fa-file-pdf-o" data-tooltip="tooltip" title="Memo PDF"></i></button>';
                } else {
                    $op = "";
                }
            }


            $data[] = array(
                "0" => $op,
                "1" => $reg->codigo,
                "2" => $reg->pronomb,
                "3" => $reg->calle . ', #' . $reg->numero,
                "4" => $reg->razon_social,
                "5" => $reg->rut,
                "6" => $reg->cccodigo,
                "7" => $reg->codVenta,
                "8" => $reg->vendedor
                    );
        }
        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );
        echo json_encode($results);
        break;

    case 'mostrar':

        $rspta = $venta->mostrar($idventa);
        echo json_encode($rspta);
        break;

    case 'cambiarestado':
        $rspta = $venta->cambiarEstado($idventa, $estado);
        echo json_encode($rspta);
        break;

    case 'validarventa':
        $rspta = $venta->validarventa($codigo);
        echo json_encode($rspta);
        break;

    case 'PDF':
        $idventa = isset($_POST["idventa"]) ? limpiarCadena($_POST["idventa"]) : "";

        //DATOS DEL MEMO
        $datos = $aproven->pdfdatos($idventa);

        //DATOS DEL COMERCIALES VENTA
        $vencomer = $aproven->pdfMontoPro($idventa);

        //NUMERO DE ETAPAS
        $ninfo = $aproven->ninfo($idventa);


        //INFORMACIO CONTRACTUAL
        $rspinfo = $aproven->pdfinfo($idventa);
        $info = Array();
        while ($reg = $rspinfo->fetch_object()) {
            $info[] = array(
                "0" => $reg->descripcion,
                "1" => $reg->fecha
            );
        }

        $rspsi = $aproven->pdfformasi($idventa);
        $fsi = Array();
        while ($reg = $rspsi->fetch_object()) {
            $fsi[] = array(
                "0" => $reg->descripcion,
                "1" => $reg->porcentaje
            );
        }

        $rspsn = $aproven->pdfformasn($idventa);
        $fsn = Array();
        while ($reg = $rspsn->fetch_object()) {
            $fsn[] = array(
                "0" => $reg->descripcion,
                "1" => $reg->porcentaje
            );
        }

        $rspboleta = $aproven->pdfboleta($idventa);
        $boletas = Array();
        while ($reg = $rspboleta->fetch_object()) {
            $boletas[] = array(
                "0" => $reg->banco,
                "1" => $reg->descripcion,
                "2" => $reg->documento,
                "3" => $reg->tipo,
                "4" => $reg->validez
            );
        }

        $rspequipos = $aproven->pdfequipos($idventa);
        $equipos = Array();
        while ($reg = $rspequipos->fetch_object()) {
            $equipos[] = array(
                "0" => $reg->tascensor,
                "1" => $reg->modelo,
                "2" => $reg->comando,
                "3" => $reg->codigo,
                "4" => $reg->ken,
                "5" => $reg->paradas . ' / '. $reg->accesos ,
                "6" => $reg->capper . ' PERSONAS / ' . $reg->capkg . ' KG',
            );
        }

        $montoequipos = $aproven->pdfMontoEquipos($idventa);
        $montos = Array();
        while ($reg = $montoequipos->fetch_object()) {
            $montos[] = array(
                "0" => $reg->nombre . ' ' . $reg->modelo,
                "1" => $reg->codigo,
                "2" => $reg->ken,
                "3" => $reg->montosi,
                "4" => $reg->simsi,
                "5" => $reg->montosn,
                "6" => $reg->simsn
            );
        }

        $results = array(
            "Datos" => $datos,
            "Etapas" => $ninfo,
            "Info" => $info,
            "FSi" => $fsi,
            "FSn" => $fsn,
            "Boletas" => $boletas,
            "Equipos" => $equipos,
            "MontoVen" => $vencomer,
            "MontoAsc" => $montos
        );

        echo json_encode($results);
        break;


    case 'PDFCorto':
        $idventa = isset($_POST["idventa"]) ? limpiarCadena($_POST["idventa"]) : "";

        //DATOS DEL MEMO
        $datos = $aproven->pdfdatos($idventa);

        //NUMERO DE ETAPAS
        $ninfo = $aproven->ninfo($idventa);


        //INFORMACIO CONTRACTUAL
        $rspinfo = $aproven->pdfinfo($idventa);
        $info = Array();
        while ($reg = $rspinfo->fetch_object()) {
            $info[] = array(
                "0" => $reg->descripcion,
                "1" => $reg->fecha
            );
        }

        $rspsi = $aproven->pdfformasi($idventa);
        $fsi = Array();
        while ($reg = $rspsi->fetch_object()) {
            $fsi[] = array(
                "0" => $reg->descripcion,
                "1" => $reg->porcentaje
            );
        }

        $rspsn = $aproven->pdfformasn($idventa);
        $fsn = Array();
        while ($reg = $rspsn->fetch_object()) {
            $fsn[] = array(
                "0" => $reg->descripcion,
                "1" => $reg->porcentaje
            );
        }

        $rspboleta = $aproven->pdfboleta($idventa);
        $boletas = Array();
        while ($reg = $rspboleta->fetch_object()) {
            $boletas[] = array(
                "0" => $reg->banco,
                "1" => $reg->descripcion,
                "2" => $reg->documento,
                "3" => $reg->tipo,
                "4" => $reg->validez
            );
        }

        $rspequipos = $aproven->pdfequipos($idventa);
        $equipos = Array();
        while ($reg = $rspequipos->fetch_object()) {
            $equipos[] = array(
                "0" => $reg->tascensor,
                "1" => $reg->modelo,
                "2" => $reg->comando,
                "3" => $reg->codigo,
                "4" => $reg->ken,
                "5" => $reg->paradas . ' / ' . $reg->accesos,
                "6" => $reg->capper . ' PERSONAS / ' . $reg->capkg . ' KG',
            );
        }


        $results = array(
            "Datos" => $datos,
            "Etapas" => $ninfo,
            "Info" => $info,
            "FSi" => $fsi,
            "FSn" => $fsn,
            "Boletas" => $boletas,
            "Equipos" => $equipos
        );

        echo json_encode($results);
        break;
}