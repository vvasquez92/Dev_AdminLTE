<?php
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
    header("Location:login.php");
} else {

    require 'header.php';

    if ($_SESSION['administrador'] == 1 || $_SESSION['Instalaciones'] == 1) {
        ?>

        <!-- page content -->
        <div class="content-wrapper">
            <div class="">

                <div class="clearfix"></div>

                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>PROYECTOS INSTALACIONES</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div id="listadoproyectos" class="x_content">

                                <!--<table id="tblproyectos" class="table table-striped border border-gray-dark projects dt-responsive" cellspacing="0" width="100%">-->
                                <table id="tblproyectos" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>PROYECTO</th>
                                            <th>CODIGO</th>
                                            <th>MANAGER</th>    
                                            <th>SUPERVISOR</th>
                                            <th>PROGRESO</th>
                                            <th>ESTADO</th>
                                            <th>FECHA <br> ACTUALIZACIÓN</th>
                                            <th>DÍAS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>

                            <div id="formulariovisita" style="display: none;" class="x_content">
                                <br/>
                                <div class="col-md-12 center-margin">
                                    <form class="form-horizontal form-label-left" id="formulario" name="formulario">
                                        <section class="panel">
                                            <div class="x_title">
                                                <h2 class="green">VISITA N° <b id="numerovisita"></b></h2>
                                                <div class="clearfix"></div>
                                            </div>
                                            <div class="panel-body">
                                                <div class="project_detail">
                                                    <p class="title"> NOMBRE</p>
                                                    <h3 class="value text-success" id="nombProy"></h3>

                                                    <div class="clearfix"></div>

                                                    <p class="title"> ETAPA ACTUAL  </p>
                                                    <p class="value text-info text-bold" id="estadoproy"></p>

                                                    <div class="clearfix"></div>

                                                    <p class="title"> FECHA DE INICIO </p>
                                                    <p class="value text-success" id="created_time"></p>

                                                    <div class="clearfix"></div>


                                                    <p class="title"> CODIGO </p>
                                                    <p class="value text-success" id="codigo"></p>

                                                    <p class="title">CENTRO DE COSTO</p>
                                                    <p class="green" id="ccnomb"></p>



                                                    <p class="title">MANAGER</p>
                                                    <p id="pm"></p>

                                                    <p class="title">SUPERVISOR</p>
                                                    <p id="supervisor"></p>
                                                    <!--<p class="title">Project Leader</p>
                                                    <p>Tony Chicken</p>-->
                                                </div>
                                            </div>
                                        </section>
                                        
                                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                            <input type="hidden" id="idproyecto" name="idproyecto" class="form-control" required="Campo requerido">
                                            <input type="hidden" id="idestado" name="idestado" class="form-control" required="Campo requerido">
                                            <input type="hidden" id="idventa" name="idventa" class="form-control" required="Campo requerido">
                                        </div>
                                        
                                        <div id="ascensores"></div>
                                        
                                        <!--<div class="col-md-12 col-sm-12 col-xs-12 form-group" id="completada">
                                            <label>MARQUE SI LA ETAPA DEL PROYECTO FUE COMPLETADA</label><br>
                                            <input type="checkbox" id="esetapa" name="esetapa" value="0" class="js-switch" />
                                        </div>-->
                                        
                                        <div class="ln_solid"></div> 
                                        <div class="form-group">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <button class="btn btn-success" type="submit" id="btnGuardar">Agregar</button>
                                                <button class="btn btn-primary" style="float: right;" type="button" id="btnCancelar" onclick="cancelarform()">Cancelar</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            
                            
                            <div id="revisarestado" style="display: none;" class="x_content">
                                <br/>
                                <div class="col-md-12 center-margin">
                                    <form class="form-horizontal form-label-left" id="formulario" name="formulario">
                                        <section class="panel">
                                            <div class="x_title">
                                                <h2 class="green">VISITA N° <b id="numerovisita2"></b></h2>
                                                <div class="clearfix"></div>
                                            </div>
                                            <div class="panel-body">
                                                <div class="project_detail">
                                                    <p class="title"> NOMBRE</p>
                                                    <h3 class="value text-success" id="nombProy2"></h3>

                                                    <div class="clearfix"></div>

                                                    <p class="title"> ETAPA ACTUAL  </p>
                                                    <p class="value text-info text-bold" id="estadoproy2"></p>

                                                    <div class="clearfix"></div>

                                                    <p class="title"> FECHA DE INICIO </p>
                                                    <p class="value text-success" id="created_time2"></p>

                                                    <div class="clearfix"></div>


                                                    <p class="title"> CODIGO </p>
                                                    <p class="value text-success" id="codigo2"></p>

                                                    <p class="title">CENTRO DE COSTO</p>
                                                    <p class="green" id="ccnomb2"></p>



                                                    <p class="title">MANAGER</p>
                                                    <p id="pm2"></p>

                                                    <p class="title">SUPERVISOR</p>
                                                    <p id="supervisor2"></p>
                                                    <!--<p class="title">Project Leader</p>
                                                    <p>Tony Chicken</p>-->
                                                </div>
                                            </div>
                                        </section>
                                        
                                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                            <input type="hidden" id="idproyecto2" name="idproyecto" class="form-control" required="Campo requerido">
                                            <input type="hidden" id="idestado2" name="idestado" class="form-control" required="Campo requerido">
                                            <input type="hidden" id="idventa2" name="idventa" class="form-control" required="Campo requerido">
                                        </div>
                                        
                                        <div id="ascensores2"></div>
                                        
                                        <!--<div class="col-md-12 col-sm-12 col-xs-12 form-group" id="completada">
                                            <label>MARQUE SI LA ETAPA DEL PROYECTO FUE COMPLETADA</label><br>
                                            <input type="checkbox" id="esetapa" name="esetapa" value="0" class="js-switch" />
                                        </div>-->
                                        
                                        <div class="ln_solid"></div> 
                                        <div class="form-group">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <button class="btn btn-primary" style="float: right;" type="button" id="btnCancelar" onclick="cancelarrevisar()">Volver</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /page content -->

        <?php
    } else {
        require 'nopermiso.php';
    }
    require 'footer.php';
    ?>
    <script src="../public/build/js/jspdf.min.js"></script>
    <script src="../public/build/js/jspdf.plugin.autotable.js"></script>
    <script src="../public/build/js/jsPDFcenter.js"></script>
    <script src="../public/build/js/nprogress.js"></script>
    <script src="../public/build/js/bootstrap-progressbar.min.js"></script>
    <script type="text/javascript" src="scripts/visita.js"></script>
    <?php
}
ob_end_flush();
?>