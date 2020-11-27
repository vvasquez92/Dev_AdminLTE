<?php
if (strlen(session_id()) < 1) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Fabrimetal</title>
    <link rel="icon" href="../public/favicon.ico" type="image/x-icon" />
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bbootstrap 4 -->
    <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="../plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="../plugins/summernote/summernote-bs4.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <!-- Select2 -->
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- FORMS -->
    <link href="../public/build/css/prettify.min.css" rel="stylesheet">
    <link href="../public/build/css/select2.min.css" rel="stylesheet">
    <link href="../public/build/css/switchery.min.css" rel="stylesheet">
    <link href="../public/build/css/starrr.css" rel="stylesheet">

    <!-- bootstrap-file-imput -->
    <link href="../public/build/css/fileinput.min.css" rel="stylesheet">
    <!-- bootstrap-select -->
    <link href="../public/build/css/bootstrap-select.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../public/build/css/custom.css" rel="stylesheet">
    <link href="../iconos/css/all.min.css" rel="stylesheet">

    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <script src="../plugins/jquery/jquery.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            var ulMaster = $('#ulMaster');

            var html = '';


            html += '<li class="nav-item has-treeview">';
            html += '<a href="#" class="nav-link">';
            html += '<i class="nav-icon fas fa-file-contract"></i>';
            html += '<p>';
            html += 'Contratos';
            html += '<i class="right fas fa-angle-left"></i>';
            html += '</p>';
            html += '</a>';
            html += '<ul class="nav nav-treeview">';
            html += '<li class="nav-item">';
            html += '<a href="./dashcontratos2.php" class="nav-link">';
            html += '<i class="far fa-circle nav-icon"></i>';
            html += '<p>Dashboard Contratos</p>';
            html += '</a>';
            html += '</li>';
            html += '</ul>';
            html += '</li>';

            html += '<li class="nav-item has-treeview">';
            html += '<a href="#" class="nav-link">';
            html += '<i class="nav-icon fas fa-tools"></i>';
            html += '<p>';
            html += 'Instalaciones';
            html += '<i class="right fas fa-angle-left"></i>';
            html += '</p>';
            html += '</a>';
            html += '<ul class="nav nav-treeview">';
            html += '<li class="nav-item">';
            html += '<a href="./visita.php" class="nav-link">';
            html += '<i class="far fa-circle nav-icon"></i>';
            html += '<p>Proyectos</p>';
            html += '</a>';
            html += '</li>';
            html += '</ul>';
            html += '</li>';

            ulMaster.append(html);
        });
    </script>

</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-success navbar-badge">4</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">4 Mensajes pendientes</span>
                        <div class="dropdown-divider"></div>
                        <a href="./chat.php" class="dropdown-item">
                            <i class="fas fa-envelope mr-2"></i> 4 nuevos mensajes
                            <span class="float-right text-muted text-sm">3 mins</span>
                        </a>                        
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                        <i class="fas fa-th-large"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="#" class="brand-link">
                <img src="../dist/img/fabrimetal.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">Fabrimetal</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="../dist/img/noimg.jpg" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block"><?php echo $_SESSION['nombre'] . " " . $_SESSION['apellido'] ?></a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul id="ulMaster" class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                        <li class="nav-item has-treeview">
                            <!-- Agregar clase menu-open para que un menu aparezca abierto por defecto -->
                            <a href="#" class="nav-link">
                                <!-- Agregar clase active para pintar el menu activo -->
                                <i class="nav-icon fas fa-home"></i>
                                <p>
                                    Inicio
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="./inicio.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Inicio</p>
                                    </a>
                                </li>
                            </ul>
                        </li>


                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>