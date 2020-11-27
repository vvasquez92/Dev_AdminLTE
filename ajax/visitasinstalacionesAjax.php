<?php

session_start();
require_once '../modelos/VisitaInstalaciones.php';
require_once '../modelos/Proyecto.php';
require_once '../modelos/Ascensor.php';
require_once '../modelos/EtapaProyecto.php';

$visita = new VisitasInstalaciones();
$proyecto = new Proyecto();
$ascensor = new Ascensor();


//Datos desde el formulario - Editar ascensor
$idproyecto = isset($_POST["idproyecto"]) ? limpiarCadena($_POST["idproyecto"]) : "";
$idestado = isset($_POST["idestado"]) ? limpiarCadena($_POST["idestado"]) : "";
$idventa = isset($_POST["idventa"]) ? limpiarCadena($_POST["idventa"]) : "";

$tipoproyecto = isset($_REQUEST['tipoproyecto']) ? $_REQUEST['tipoproyecto'] : "";

switch ($_GET["op"]) {

    case 'visita':
        $iduser = $_SESSION['iduser'];
        $scensores = count($_POST['idascensor']);

        for ($o = 0; $o < (int) $scensores; $o++) {

            $idascensor = $_POST['idascensor'][$o];
            $observaciones = limpiarCadena($_POST['observaciones' . $idascensor]);
            $esetapa = $_POST['esetapa' . $idascensor];
            $estadoins = $_POST['estadoins' . $idascensor];

            $rspta = $visita->insertar($idproyecto, $idestado, $observaciones, $iduser, $idascensor);

            if ($esetapa == 1) {
                $ascensor->estado($idascensor, (intval($estadoins) + 1));
            }
        }



        echo $rspta ? "REGISTRADA" : "ERROR AL REGISTRAR";
        break;

    case 'visitayetapa':

        if ($_SESSION['SupProyectos'] == 1) {

            //id usuario por session
            $iduser = $_SESSION['iduser'];
            //cuento los ascensores que fueron seleccionados para subir de etapa

            $scensores = isset($_POST['idascensor']) ? count($_POST['idascensor']) : 0;
            //inicializo objeto etapa
            $etapa = new EtapaProyecto();

            //recorro ascensores
            for ($o = 0; $o < (int) $scensores; $o++) {

                $idascensor = $_POST['idascensor'][$o];
                $observaciones = limpiarCadena($_POST['observaciones' . $idascensor]);
                $esetapa = isset($_POST['esetapa' . $idascensor]) ? $_POST['esetapa' . $idascensor] : 0;
                $estadoins = $_POST['estadoins' . $idascensor];

                //inserto la visita por ascensor
                $rspta = $visita->insertar($idproyecto, $idestado, $observaciones, $iduser, $idascensor);

                //si etapa de proyecto es 1 cambio estado de ascensor a 2
                if ($esetapa == 1) {
                    if ($estadoins < 12) {
                        $ascensor->estado($idascensor, (intval($estadoins) + 1));
                    }
                }
            }

            //Si estado de protyecto es 1 modifico todos los ascensores a 2
            if ($idestado == 1) {
                $ascensor->estadotodos($idventa, 2);
            }

            //obtengo el ascensor que va en la menor etapa de todos
            $respuesta = $ascensor->menoretapa($idventa);
            while ($reg = $respuesta->fetch_object()) {
                $menoretapa = $reg->estado;
            }

            //si la menor etapa es distinta al estado del proyecto, modifico el estadp proyecto
            if ($menoretapa != $idestado) {

                //cambio estado de proyecto con fecha de actualizacion y usuario
                $update_time = date("Y-m-d H:i:s");
                if ((int) $menoretapa == 12) {
                    $closed_time = date("Y-m-d H:i:s");
                    $closed_user = $_SESSION['iduser'];
                    $rspta = $proyecto->etapafinal($idproyecto, $menoretapa, $update_time, $iduser, $closed_time, $closed_user);
                } else {
                    $rspta = $proyecto->etapa($idproyecto, $menoretapa, $update_time, $iduser);
                }

                //si estado es distinto de 1la duracion de dias es 0, si no, 
                //calculo los dias transcurridos entre la ultima acualizacion y la fecha actual
                if ($idestado != 1) {

                    //Obtengo mayor fecha de actualizacion
                    $respuesta2 = $etapa->ultimafecha($idproyecto);
                    while ($reg2 = $respuesta2->fetch_object()) {
                        $created_time = date_create($reg2->fecha);
                        $update_time = date_create($update_time);
                    }

                    $fini = date_format($created_time, "Y-m-d");
                    $ffin = date_format($update_time, "Y-m-d");

                    $diff = (strtotime($fini) - strtotime($ffin)) / 86400;
                    $diff = abs($diff);
                    $diff = floor($diff);

                    $duracion = $diff;
                } else {
                    $duracion = 0;
                }

                //Inserto la etapa proyecto, con el id de proyecto, estado de proyecto y cantidad de dias en actualizar
                $rspra = $etapa->insertar($idproyecto, $idestado, $duracion);
            }
            echo $rspta ? "REGISTRADA" : "ERROR AL REGISTRAR";
        } elseif ($_SESSION['GEProyectos'] == 1 || $_SESSION['PMProyectos'] == 1 || $_SESSION['administrador'] == 1) {

            //id usuario por session
            $iduser = $_SESSION['iduser'];
            //cuento los ascensores que fueron seleccionados para subir de etapa
            $scensores = count($_POST['idascensor']);

            //recorro ascensores
            for ($o = 0; $o < (int) $scensores; $o++) {

                $idascensor = $_POST['idascensor'][$o];
                $observaciones = limpiarCadena($_POST['observaciones' . $idascensor]);
                $estadoins = $_POST['estadoins' . $idascensor];

                //inserto la visita por ascensor
                $rspta = $visita->insertar($idproyecto, $idestado, $observaciones, $iduser, $idascensor);
            }
            echo $rspta ? "REGISTRADA" : "ERROR AL REGISTRAR";
        }
        break;

    case 'listar':

        $rspta = $proyecto->listar($_SESSION['rut'], $tipoproyecto);

        $data = Array();

        while ($reg = $rspta->fetch_object()) {

            $barra = '<td class="project_progress"><div class="progress progress_sm" style="margin-bottom:5px;"><div class="progress-bar bg-green" role="progressbar" data-transitiongoal="' . $reg->carga . '" aria-valuenow="' . $reg->carga . '" style="width: ' . $reg->carga . '%;"></div></div><small>' . $reg->carga . '% Completo</small></td>';
            $barraestado = '<button type="button" class="btn btn-xs" style="background-color:' . $reg->color . '; color: #fff;">' . $reg->estadonomb . '</button>';

            $op = "";

            if ($_SESSION['SupProyectos'] == 1) {
                $op = '<div class="btn-group">'
                        . ' <button data-toggle="dropdown" class="btn btn-success dropdown-toggle btn-xs" type="button"><i class="fa fa-cog"></i></button>'
                        . ' <ul role="menu" class="dropdown-menu">'
                        . '     <li><a onclick="mostrar(' . $reg->idproyecto . ')">Visita</a></li>'
                        . '     <li class="divider"></li>'
                        . '     <li><a onclick="revisar(' . $reg->idproyecto . ')">Visualizar estado</a></li>'
                        . '</ul>'
                        . '</div>';
            } elseif ($_SESSION['PMProyectos'] == 1 && $reg->estado > 1) {
                $op = '<div class="btn-group">'
                        . ' <button data-toggle="dropdown" class="btn btn-success dropdown-toggle btn-xs" type="button"><i class="fa fa-cog"></i></button>'
                        . ' <ul role="menu" class="dropdown-menu">'
                        . '     <li><a onclick="visitaotro(' . $reg->idproyecto . ')">Visita</a></li>'
                        . '     <li class="divider"></li>'
                        . '     <li><a onclick="revisar(' . $reg->idproyecto . ')">Visualizar estado</a></li>';
                if ($_SESSION['AsigProyectos'] == 1) {
                    $op .= '     <li class="divider"></li>'
                            . '     <li><a onclick="pdfvisitas(' . $reg->idproyecto . ')">Reporte PDF</a></li>'
                            . '     <li class="divider"></li>'
                            . '     <li><a onclick="pdf(' . $reg->idventa . ')">Memo de Venta</a></li>';
                }
                $op .= '</ul>'
                        . '</div>';
            } elseif ($_SESSION['PMProyectos'] == 1 && $reg->estado == 1) {
                $op = '<div class="btn-group">'
                        . ' <button data-toggle="dropdown" class="btn btn-success dropdown-toggle btn-xs" type="button"><i class="fa fa-cog"></i></button>'
                        . ' <ul role="menu" class="dropdown-menu">'
                        . '     <li><a onclick="revisar(' . $reg->idproyecto . ')">Visualizar estado</a></li>';
                if ($_SESSION['AsigProyectos'] == 1) {
                    $op .= '     <li class="divider"></li>'
                            . '     <li><a onclick="pdfvisitas(' . $reg->idproyecto . ')">Reporte PDF</a></li>'
                            . '     <li class="divider"></li>'
                            . '     <li><a onclick="pdf(' . $reg->idventa . ')">Memo de Venta</a></li>';
                }
                $op .= '</ul>'
                        . '</div>';
            } elseif ($_SESSION['GEProyectos'] == 1 && $reg->estado > 1) {
                $op = '<div class="btn-group">'
                        . ' <button data-toggle="dropdown" class="btn btn-success dropdown-toggle btn-xs" type="button"><i class="fa fa-cog"></i></button>'
                        . ' <ul role="menu" class="dropdown-menu">'
                        . '     <li><a onclick="visitaotro(' . $reg->idproyecto . ')">Visita</a></li>'
                        . '     <li class="divider"></li>'
                        . '     <li><a onclick="revisar(' . $reg->idproyecto . ')">Visualizar estado</a></li>';
                if ($_SESSION['AsigProyectos'] == 1) {
                    $op .= '     <li class="divider"></li>'
                            . '     <li><a onclick="pdfvisitas(' . $reg->idproyecto . ')">Reporte PDF</a></li>'
                            . '     <li class="divider"></li>'
                            . '     <li><a onclick="pdf(' . $reg->idventa . ')">Memo de Venta</a></li>';
                }
                $op .= '</ul>'
                        . '</div>';
            } elseif ($_SESSION['GEProyectos'] == 1 && $reg->estado == 1) {
                $op = '<div class="btn-group">'
                        . ' <button data-toggle="dropdown" class="btn btn-success dropdown-toggle btn-xs" type="button"><i class="fa fa-cog"></i></button>'
                        . ' <ul role="menu" class="dropdown-menu">'
                        . '     <li><a onclick="revisar(' . $reg->idproyecto . ')">Visualizar estado</a></li>';
                if ($_SESSION['AsigProyectos'] == 1) {
                    $op .= '     <li class="divider"></li>'
                            . '     <li><a onclick="pdfvisitas(' . $reg->idproyecto . ')">Reporte PDF</a></li>'
                            . '     <li class="divider"></li>'
                            . '     <li><a onclick="pdf(' . $reg->idventa . ')">Memo de Venta</a></li>';
                }
                $op .= '</ul>'
                        . '</div>';
            } elseif ($_SESSION['administrador'] == 1 && $reg->estado > 1) {
                $op = '<div class="btn-group">'
                        . ' <button data-toggle="dropdown" class="btn btn-success dropdown-toggle btn-xs" type="button"><i class="fa fa-cog"></i></button>'
                        . ' <ul role="menu" class="dropdown-menu">'
                        . '     <li><a onclick="mostrar(' . $reg->idproyecto . ')">Visita</a></li>'
                        . '     <li class="divider"></li>'
                        . '     <li><a onclick="visitaotro(' . $reg->idproyecto . ')">Visita PM</a></li>'
                        . '     <li class="divider"></li>'
                        . '     <li><a onclick="visitaotro(' . $reg->idproyecto . ')">Visita Gerente</a></li>'
                        . '     <li class="divider"></li>'
                        . '     <li><a onclick="revisar(' . $reg->idproyecto . ')">Visualizar estado</a></li>';
                $op .= '     <li class="divider"></li>'
                        . '     <li><a onclick="pdfvisitas(' . $reg->idproyecto . ')">Reporte PDF</a></li>'
                        . '     <li class="divider"></li>'
                        . '     <li><a onclick="pdf(' . $reg->idventa . ')">Memo de Venta</a></li>';
                $op .= '</ul>'
                        . '</div>';
            }

            $data[] = array(
                "0" => $op,
                "1" => $reg->nombre,
                "2" => $reg->codigo,
                "3" => $reg->pm,
                "4" => $reg->supervisor,
                "5" => $barra,
                "6" => $barraestado,
                "7" => $reg->updated_time,
                "8" => $reg->dias);
        }
        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );

        echo json_encode($results);
        break;

    /** MODERNIZACIONES * */
    case 'listarModernizacion':

        $rspta = $proyecto->listar($_SESSION['rut'], $tipoproyecto);

        $data = Array();

        while ($reg = $rspta->fetch_object()) {

            $barra = '<td class="project_progress"><div class="progress progress_sm" style="margin-bottom:5px;"><div class="progress-bar bg-green" role="progressbar" data-transitiongoal="' . $reg->carga . '" aria-valuenow="' . $reg->carga . '" style="width: ' . $reg->carga . '%;"></div></div><small>' . $reg->carga . '% Completo</small></td>';
            $barraestado = '<button type="button" class="btn btn-xs" style="background-color:' . $reg->color . '; color: #fff;">' . $reg->estadonomb . '</button>';


            if ($_SESSION['administrador'] == 1 || $_SESSION['Modernizaciones'] == 1) {
                /* $op = '<div class="btn-group">'
                  . ' <button data-toggle="dropdown" class="btn btn-success dropdown-toggle btn-xs" type="button"><i class="fa fa-cog"></i></button>'
                  . ' <ul role="menu" class="dropdown-menu">'
                  . '     <li><a onclick="finalizar(' . $reg->idproyecto . ')">Finalizar</a></li>'
                  . '     <li class="divider"></li>'
                  . '     <li><a onclick="revisar(' . $reg->idproyecto . ')">Visualizar estado</a></li>'
                  . '</ul>'
                  . '</div>'; */
                $op = '<button class="btn btn-info btn-xs" onclick="revisar(' . $reg->idproyecto . ')"><i class="fa fa-eye"></i></button>';
            }

            $data[] = array(
                "0" => $op,
                "1" => $reg->nombre,
                "2" => $reg->codigo,
                "3" => $reg->pm,
                "4" => $reg->supervisor,
                "5" => $barra,
                "6" => $barraestado,
                "7" => $reg->updated_time,
                "8" => $reg->dias);
        }
        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );

        echo json_encode($results);
        break;

    case 'finalizarmodernizacion':

        $iduser = $_SESSION['iduser'];
        $estadoins = isset($_POST['idestado2']) ? $_POST['idestado2'] : 0;
        $idproyecto = isset($_POST['idproyecto2']) ? $_POST['idproyecto2'] : 0;
        $scensores = isset($_POST['idascensor']) ? count($_POST['idascensor']) : 0;
        $update_time = date("Y-m-d H:i:s");

        for ($o = 0; $o < (int) $scensores; $o++) {
            $idascensor = $_POST['idascensor'][$o];
            $ascensor->estado($idascensor, (intval($estadoins) + 1));
        }
        $rspta = $proyecto->etapa($idproyecto, (intval($estadoins) + 1), $update_time, $iduser);
        echo $rspta ? "REGISTRADA" : "ERROR AL REGISTRAR" . $rspta;
        break;

    /** MODERNIZACIONES * */
    case 'listarNoPmSup':
        $rspta = $proyecto->listarnoPMSUP($_SESSION['rut'], $tipoproyecto);

        $data = Array();

        while ($reg = $rspta->fetch_object()) {


            if ($_SESSION['AsigProyectos'] == 1) {
                $op = '<button type="button" class="btn btn-success btn-xs" onclick="mostrar(' . $reg->idproyecto . ')"><i class="fa fa-pencil"></i></button><button type="button" class="btn btn-info btn-xs" onclick="pdf(' . $reg->idventa . ')" data-tooltip="tooltip" title="Memo de Venta (PDF)"><i class="fa fa-file-pdf-o"></i></button>';
            } elseif ($_SESSION['administrador'] == 1) {
                $op = '<button type="button" class="btn btn-success btn-xs" onclick="mostrar(' . $reg->idproyecto . ')"><i class="fa fa-pencil"></i></button><button type="button" class="btn btn-info btn-xs" onclick="pdf(' . $reg->idventa . ')" data-tooltip="tooltip" title="Memo de Venta (PDF)"><i class="fa fa-file-pdf-o"></i></button>';
            }

            $data[] = array(
                "0" => $op,
                "1" => $reg->nombre,
                "2" => $reg->codigo,
                "3" => 'SIN ASIGNAR');
        }
        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );

        echo json_encode($results);
        break;
    case 'mostrarNoPmSup':

        $rspta = $proyecto->mostrarNoPmSup($idproyecto);
        echo json_encode($rspta);
        break;
    case 'editarNoPmSup':

        if ($idproyecto) {
            $supervisor = isset($_POST['supervisor']) ? $_POST['supervisor'] : 0;
            $pm = isset($_POST['pm']) ? $_POST['pm'] : 0;
            if ($supervisor <> 0 && $pm <> 0) {
                $updated_user = $_SESSION['iduser'];
                $rspta = $proyecto->Editar($idproyecto, $pm, $supervisor, $updated_user);
                echo $rspta ? "Proyecto Editado" : "Proyecto no editado";
            } else {
                echo "Falta Project manager o Supervisor";
            }
        }
        break;
    case 'mostrar':

        $rspta = $proyecto->mostrar($idproyecto);
        echo json_encode($rspta);
        break;


    case 'numerovisita':
        $rspta = $visita->numerovisita($idproyecto);
        echo json_encode($rspta);
        break;

    case 'PDF':
        $idproyecto = isset($_POST["idproyecto"]) ? limpiarCadena($_POST["idproyecto"]) : "";


        //DATOS DEL PROYECTO
        $datos = $visita->pdfdatos($idproyecto);

        //NUMERO DE VISITAS
        $nvisitas = $visita->cantvisitas($idproyecto);

        //NUMERO DE EQUIPOS
        $nequipos = $visita->cantequipos($idproyecto);

        //INFORMACIO CONTRACTUAL
        $rspetapas = $visita->infoetapas($idproyecto);
        $infoetapas = Array();
        while ($reg = $rspetapas->fetch_object()) {
            $infoetapas[] = array(
                "0" => $reg->nombre,
                "1" => $reg->duracion,
                "2" => $reg->fecha
            );
        }

        $rspvi = $visita->infovi($idproyecto);
        $infovi = Array();
        while ($reg = $rspvi->fetch_object()) {
            $rspinvi = $visita->infovife($reg->fehora, $idproyecto);
            $infofevi = Array();
            while ($regvi = $rspinvi->fetch_object()) {
                $infofevi[] = array(
                    "0" => $regvi->codigo,
                    "1" => $regvi->observacion,
                    "2" => $regvi->nombre,
                    "3" => $regvi->colaborador
                );
            }

            $infovi[] = array(
                "0" => $reg->fecha,
                "1" => $reg->estado,
                "2" => $infofevi
            );
        }

        $results = array(
            "Datos" => $datos,
            "Nvisitas" => $nvisitas,
            "Nequipos" => $nequipos,
            "Etapas" => $infoetapas,
            "VisitasFe" => $infovi
        );

        echo json_encode($results);
        break;
        
    case 'dashinstalacion':
        $idproyecto = isset($_POST["idproyecto"]) ? limpiarCadena($_POST["idproyecto"]) : "";
        //DATOS DEL PROYECTO
        $datos = $visita->dashinstalacion($idproyecto);
        
         while ($reg = $datos->fetch_object()) {
             $data[] = array(
                "dia" => $reg->dia,
                "mes" => $reg->mes,
                "user"=> $reg->user,
                "obs" => $reg->observacion,
                "asc" => $reg->codigo,
                "imagen"=> $reg->imagen,
                "fecha" => $reg->fecha);
             
         }
        echo json_encode($data);
        break;
}

