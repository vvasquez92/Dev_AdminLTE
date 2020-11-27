<?php
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
    header("Location:login.php");
} else {

    require 'header.php';
?>
    <div class="content-wrapper">

        <div class="x_panel">
            <div class="x_title">
                <h3>CHAT FABRIMETAL</h3>
                <div class="clearfix"></div>
            </div>
            <div class="row">
                <div class="col-3">
                    <div>Aquí van los usuarios conectados</div>
                </div>
                <div class="col-9">
                    <div class="chat-rbox">
                        <ul class="chat-list p-20" id="divChatbox"></ul>
                    </div>
                    <div class="card-body b-t">
                        <form id="formEnviar">
                            <div class="row">
                                <div class="col-10">
                                    <input autocomplete="off" id="txtMensaje" placeholder="Escribe tu mensaje aquí" class="form-control b-0" autofocus></input>
                                </div>
                                <div class="col-2 text-right">
                                    <button type="submit" class="btn btn-info btn-circle btn-lg"><i class="fab fa-telegram-plane"></i> </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>

    </div>
    <?php
    require 'footer.php';
    ?>

<?php
}
ob_end_flush();
?>