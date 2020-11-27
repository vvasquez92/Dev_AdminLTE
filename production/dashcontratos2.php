<?php
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
    header("Location:login.php");
} else {

    require 'header.php';
?>
    <div class="content-wrapper">

        <div class="row">
            <div class="animated flipInY col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h3>DASHBOARD CONTRATOS</h3>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="row top_tiles" style="text-align: center;">
                            <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <div class="tile-stats">
                                    <div class="count" id="num_contrato"></div>
                                    <h3>Contratos en Cartera</h3>
                                </div>
                            </div>
                            <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <div class="tile-stats">
                                    <div class="count" id="num_clientes"></div>
                                    <h3>Clientes en Cartera</h3>
                                </div>
                            </div>
                            <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <div class="tile-stats">
                                    <div class="count" id="num_edificios"></div>
                                    <h3>Edificios en Cartera</h3>
                                </div>
                            </div>
                            <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                <div class="tile-stats">
                                    <div class="count" id="num_ascensores"></div>
                                    <h3>Ascensores en Cartera</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="x_panel">
                    <div class="x_title">
                        <h3 id="titulo_grafico_lineal"></h3>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="form-group">
                            <div class="col-md-5">
                                <label>AÑO</label>
                                <select id="anio_busq" name="anio_busq" class="form-control select2bs4"></select>
                            </div>
                            <div class="col-md-5">
                                <label>TIPO DE EQUIPO</label>
                                <select id="tipo_equipo" name="tipo_equipo" class="form-control select2bs4">
                                    <option value="TODOS">TODOS</option>
                                    <option value="MULTIMARCA">MULTIMARCA</option>
                                    <option value="RECUPERADOS_KONE">RECUPERADOS KONE</option>
                                    <option value="NUEVOS_KONE">NUEVOS KONE</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <br>
                                <button type="button" class="btn btn-success" onclick="javascript:ActualizaGraficoAscensoresPorMes();">BUSCAR</button>
                            </div>
                        </div>
                    </div>
                    <div class="x_content">
                        <div class="col-md-12 col-sm-12 col-xs-12" style="overflow:hidden;">

                            <div class="row tile_count ">
                                <div class="col-md-12 col-sm-12 col-xs-12" style="overflow:hidden;">
                                    <div class="col-md-12" style="overflow:hidden;">
                                        <canvas height="30%" id="lineChart1"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-md-6 col-sm-12 col-xs-12" style="overflow:hidden;">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2 id=""></h2>
                            <h3>TIPOS DE INGRESO DE EQUIPOS </h3>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <h4>Cantidad de Equipos</h4>


                            <div class="widget_summary">
                                <div class="w_left w_25">
                                    <span>Equipos Nuevos KONE</span>
                                </div>
                                <div class="w_center w_55">
                                    <div class="progress">
                                        <div id="barra_ascensoresnuevoskone" class="progress-bar bg-green" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">
                                            <span class="sr-only"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="w_right w_20">
                                    <span id="num_ascensoresnuevoskone"></span>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="widget_summary">
                                <div class="w_left w_25">
                                    <span>Equipos Recuperados KONE</span>
                                </div>
                                <div class="w_center w_55">
                                    <div class="progress">
                                        <div id="barra_ascensoresrecuperadoskone" class="progress-bar bg-green" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">
                                            <span class="sr-only"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="w_right w_20">
                                    <span id="num_ascensoresrecuperadoskone"></span>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="widget_summary">
                                <div class="w_left w_25">
                                    <span>Equipos Multimarca</span>
                                </div>
                                <div class="w_center w_55">
                                    <div class="progress">
                                        <div id="barra_ascensoresmultimarca" class="progress-bar bg-green" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">
                                            <span class="sr-only"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="w_right w_20">
                                    <span id="num_ascensoresmultimarca"></span>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-sm-12 col-xs-12" style="overflow:hidden;">
                    <div class="x_panel">
                        <div class="x_title">
                            <h3 id="">INGRESO DE EQUIPOS ACUMULADO POR REGIÓN</h3>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="form-group">
                                <div class="col-md-5">
                                    <label>AÑO</label>
                                    <select id="anio_busqueda" name="anio_busqueda" class="form-control select2bs4">
                                        <option value="">TODOS</option>
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <label>MES</label>
                                    <select id="mes_busqueda" name="mes_busqueda" class="form-control select2bs4">
                                        <option value="0">TODOS</option>
                                        <option value="1">ENERO</option>
                                        <option value="2">FEBRERO</option>
                                        <option value="3">MARZO</option>
                                        <option value="4">ABRIL</option>
                                        <option value="5">MAYO</option>
                                        <option value="6">JUNIO</option>
                                        <option value="7">JULIO</option>
                                        <option value="8">AGOSTO</option>
                                        <option value="9">SEPTIEMBRE</option>
                                        <option value="10">OCTUBRE</option>
                                        <option value="11">NOMVIEMBRE</option>
                                        <option value="12">DICIEMBRE</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <br>
                                    <button type="button" class="btn btn-success" onclick="ActualizarGraficosXregion();">BUSCAR</button>
                                </div>
                            </div>
                        </div>
                        <div class="x_content">
                            <div class="panel">
                                <canvas id="mybarChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    require 'footer.php';
    ?>
    <script type="text/javascript" src="scripts/estado2.js"></script>

<?php
}
ob_end_flush();
?>