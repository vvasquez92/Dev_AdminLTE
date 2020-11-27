var tabla;


//funcion que se ejecuta iniciando
function init() {
    mostrarform(false);
    var url = window.location.pathname;
    var str = url.split("/");
    console.log(str[3]);

    if (str[3] == "visita.php") {
        listar('1,2');
    } else {
        listarmodernizacion(2);
    }

    $('[data-toggle="tooltip"]').tooltip();

    $("#formulario").on("submit", function(e) {
        visita(e);
    });

}


// Otras funciones
function limpiar() {
    $("#idproyecto").val("");
    $("#idestado").val("");
    $("#observaciones").val("");
    $("#esetapa").prop("checked", false);
}


function mostrarform(flag) {
    limpiar();
    if (flag) {
        $("#listadoproyectos").hide();
        $("#formulariovisita").show();
        $("#op_actualizar").hide();
        $("#btnGuardar").prop("disabled", false);
    } else {
        $("#formulariovisita").hide();
        $("#listadoproyectos").show();
        $("#op_actualizar").show();
        $("#btnGuardar").prop("disabled", false);
    }

}

function revisarform(flag) {
    limpiar();
    if (flag) {
        $("#listadoproyectos").hide();
        $("#revisarestado").show();
        $("#op_actualizar").hide();
    } else {
        $("#revisarestado").hide();
        $("#listadoproyectos").show();
        $("#op_actualizar").show();
    }

}

function cancelarform() {
    limpiar();
    mostrarform(false);

}

function cancelarrevisar() {
    limpiar();
    revisarform(false);
}

function mostrar(idproyecto) {

    $.post("../ajax/visitasinstalacionesAjax.php?op=mostrar", { idproyecto: idproyecto }, function(data, status) {
        data = JSON.parse(data);
        mostrarform(true);

        $("#nombProy").empty();
        $("#created_time").empty();
        $("#estadoproy").empty();
        $("#codigo").empty();
        $("#ccnomb").empty();
        $("#pm").empty();
        $("#supervisor").empty();
        $("#numerovisita").empty();
        $("#ascensores").empty();

        $("#idproyecto").val(data.idproyecto);
        $("#idestado").val(data.estado);
        $("#idventa").val(data.idventa);


        $("#nombProy").append(data.nombre);
        $("#created_time").append(data.fecha);
        $("#estadoproy").append(data.estadonomb);
        $("#codigo").append(data.codigo);
        $("#ccnomb").append(data.ccnomb);
        $("#pm").append(data.pm);
        $("#supervisor").append(data.supervisor);
        $("#numerovisita").append(data.nrovisita);



        if (data.idventa && data.estado > 1) {

            $.post("../ajax/ascensorAjax.php?op=listar", { idventa: data.idventa }, function(data3, status) {
                data3 = JSON.parse(data3);
                var asce = "";
                for (i = 0; i < data3.length; i++) {
                    asce += '<div class="product_price col-md-6 col-sm-6 col-xs-12">' +
                        '    <input type="hidden" id="idascensor' + data3[i]['idascensor'] + '" name="idascensor[]" class="form-control" required="Campo requerido" value="' + data3[i]['idascensor'] + '">' +
                        '    <input type="hidden" id="estadoins' + data3[i]['idascensor'] + '" name="estadoins' + data3[i]['idascensor'] + '" class="form-control" required="Campo requerido" value="' + data3[i]['estadoins'] + '">' +
                        '    <label class="green">' + data3[i]['codigo'] + ' - ken: ' + data3[i]['ken'] + ' - ubicación: ' + data3[i]['ubicacion'] + '</label><br>' +
                        '    <label style="color:' + data3[i]['color'] + ';"> Etapa: ' + data3[i]['strestado'] + '</label>' +
                        '    <div class="col-md-12 col-sm-12 col-xs-12 form-group">' +
                        '        <p>Observaciones de la visita:</p>' +
                        '        <textarea type="text" id="observaciones' + data3[i]['idascensor'] + '" style="text-transform:uppercase;" name="observaciones' + data3[i]['idascensor'] + '" class="resizable_textarea form-control" required="Campo requerido"></textarea>' +
                        '    </div>' +
                        '    <div class="col-md-12 col-sm-12 col-xs-12 form-group" id="">' +
                        '        <p><input type="checkbox" id="esetapa' + data3[i]['idascensor'] + '" name="esetapa' + data3[i]['idascensor'] + '" value="1" class="js-switch"/> ' +
                        '        Marque si la etapa fue completada</p>' +
                        '    </div>' +
                        '</div>';
                }
                $("#ascensores").append(asce);
            });
        }


        if (data.estado == 1) {
            $("#completada").hide();
            $("#esetapa").trigger('click').attr("checked", "checked");
            var btn = document.getElementById("btnGuardar");
            btn.innerHTML = "Iniciar Proyecto";
        } else {
            $("#completada").show();
            $("#esetapa").trigger('click').attr("checked", "checked");
            $("#esetapa").trigger('click').removeAttr("checked");
            var btn = document.getElementById("btnGuardar");
            btn.innerHTML = "Agregar";
        }


    });


}

function visitaotro(idproyecto) {

    $.post("../ajax/visitasinstalacionesAjax.php?op=mostrar", { idproyecto: idproyecto }, function(data, status) {
        data = JSON.parse(data);
        mostrarform(true);

        $("#nombProy").empty();
        $("#created_time").empty();
        $("#estadoproy").empty();
        $("#codigo").empty();
        $("#ccnomb").empty();
        $("#pm").empty();
        $("#supervisor").empty();
        $("#numerovisita").empty();
        $("#ascensores").empty();

        $("#idproyecto").val(data.idproyecto);
        $("#idestado").val(data.estado);
        $("#idventa").val(data.idventa);


        $("#nombProy").append(data.nombre);
        $("#created_time").append(data.fecha);
        $("#estadoproy").append(data.estadonomb);
        $("#codigo").append(data.codigo);
        $("#ccnomb").append(data.ccnomb);
        $("#pm").append(data.pm);
        $("#supervisor").append(data.supervisor);
        $("#numerovisita").append(data.nrovisita);



        if (data.idventa && data.estado > 1) {

            $.post("../ajax/ascensorAjax.php?op=listar", { idventa: data.idventa }, function(data3, status) {
                data3 = JSON.parse(data3);
                var asce = "";
                for (i = 0; i < data3.length; i++) {
                    asce += '<div class="product_price col-md-6 col-sm-6 col-xs-12">' +
                        '    <input type="hidden" id="idascensor' + data3[i]['idascensor'] + '" name="idascensor[]" class="form-control" required="Campo requerido" value="' + data3[i]['idascensor'] + '">' +
                        '    <input type="hidden" id="estadoins' + data3[i]['idascensor'] + '" name="estadoins' + data3[i]['idascensor'] + '" class="form-control" required="Campo requerido" value="' + data3[i]['estadoins'] + '">' +
                        '    <label class="green">' + data3[i]['codigo'] + ' - ken: ' + data3[i]['ken'] + ' - ubicación: ' + data3[i]['ubicacion'] + '</label><br>' +
                        '    <label style="color:' + data3[i]['color'] + ';"> Etapa: ' + data3[i]['strestado'] + '</label>' +
                        '    <div class="col-md-12 col-sm-12 col-xs-12 form-group">' +
                        '        <p>Observaciones de la visita:</p>' +
                        '        <textarea type="text" id="observaciones' + data3[i]['idascensor'] + '" style="text-transform:uppercase;" name="observaciones' + data3[i]['idascensor'] + '" class="resizable_textarea form-control" required="Campo requerido"></textarea>' +
                        '    </div>' +
                        '</div>';
                }
                $("#ascensores").append(asce);
            });
        }



        $("#completada").show();
        $("#esetapa").trigger('click').attr("checked", "checked");
        $("#esetapa").trigger('click').removeAttr("checked");
        var btn = document.getElementById("btnGuardar");
        btn.innerHTML = "Agregar";



    });


}


function revisar(idproyecto) {

    $.post("../ajax/visitasinstalacionesAjax.php?op=mostrar", { idproyecto: idproyecto }, function(data, status) {
        data = JSON.parse(data);
        revisarform(true);

        $("#nombProy2").empty();
        $("#created_time2").empty();
        $("#estadoproy2").empty();
        $("#codigo2").empty();
        $("#ccnomb2").empty();
        $("#pm2").empty();
        $("#supervisor2").empty();
        $("#numerovisita2").empty();
        $("#ascensores2").empty();

        $("#idproyecto2").val(data.idproyecto);
        $("#idestado2").val(data.estado);
        $("#idventa2").val(data.idventa);

        if (data.estado == 202) {
            $("#btnGrabar2").hide();
        }

        $("#nombProy2").append(data.nombre);
        $("#created_time2").append(data.fecha);
        $("#estadoproy2").append(data.estadonomb + " - (Duración: " + data.dias + " dias)");
        $("#codigo2").append(data.codigo);
        $("#ccnomb2").append(data.ccnomb);
        $("#pm2").append(data.pm);
        $("#supervisor2").append(data.supervisor);
        $("#numerovisita2").append(data.nrovisita);



        if (data.idventa) {

            $.post("../ajax/ascensorAjax.php?op=listar", { idventa: data.idventa }, function(data3, status) {
                data3 = JSON.parse(data3);
                var asce = "";
                for (i = 0; i < data3.length; i++) {
                    asce += '<div class="product_price col-md-6 col-sm-6 col-xs-12">' +
                        '    <input type="hidden" id="idascensor' + data3[i]['idascensor'] + '" name="idascensor[]" class="form-control" required="Campo requerido" value="' + data3[i]['idascensor'] + '">' +
                        '    <input type="hidden" id="estadoins' + data3[i]['idascensor'] + '" name="estadoins' + data3[i]['idascensor'] + '" class="form-control" required="Campo requerido" value="' + data3[i]['estadoins'] + '">' +
                        '    <label class="green">' + data3[i]['codigo'] + ' - ken: ' + data3[i]['ken'] + ' - ubicación: ' + data3[i]['ubicacion'] + '</label><br>' +
                        '    <label style="color:' + data3[i]['color'] + ';"> Etapa: ' + data3[i]['strestado'] + '</label>' +
                        '</div>';
                }
                $("#ascensores2").append(asce);
            });
        }


    });


}

function listar(tipoproyecto) {
    tabla = $('#tblproyectos').dataTable({
        "responsive": true,
        "aProcessing": true,
        "aServerSide": true,
        "scrollX": false,
        dom: 'Bfrtip',
        buttons: Botones,
        "language": Español,
        "ajax": {
            url: '../ajax/visitasinstalacionesAjax.php?op=listar',
            type: "get",
            dataType: "json",
            data: { 'tipoproyecto': tipoproyecto },
            error: function(e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 20, //Paginacion 10 items
        "order": [
                [2, "desc"]
            ] //Ordenar en base a la columna 0 descendente
    }).DataTable();
}

function listarmodernizacion(tipoproyecto) {
    tabla = $('#tblproyectos').dataTable({
        "responsive": true,
        "aProcessing": true,
        "aServerSide": true,
        "scrollX": false,
        dom: 'Bfrtip',
        buttons: Botones,
        "language": Español,
        "ajax": {
            url: '../ajax/visitasinstalacionesAjax.php?op=listarModernizacion',
            type: "get",
            dataType: "json",
            data: { 'tipoproyecto': tipoproyecto },
            error: function(e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 20, //Paginacion 10 items
        "order": [
                [2, "desc"]
            ] //Ordenar en base a la columna 0 descendente
    }).DataTable();
}

function visita(e) {
    e.preventDefault();
    $("#btnGuardar").prop("disabled", true);
    var formData = new FormData($("#formulario")[0]);

    //if( $('#esetapa').prop('checked')) {
    bootbox.confirm("Va a registrar las visitas a este proyecto, ¿esta seguro?", function(result) {
        if (result) {
            $.ajax({
                url: '../ajax/visitasinstalacionesAjax.php?op=visitayetapa',
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,

                success: function(datos) {
                    bootbox.alert(datos);
                    mostrarform(false);
                    tabla.ajax.reload();
                }
            });
        }
    });

    limpiar();

}

function finalizar() {

    $("#btnGuardar").prop("disabled", true);
    var formData = new FormData($("#formulario2")[0]);

    bootbox.confirm("Va a avanzar a este proyecto, ¿esta seguro?", function(result) {
        if (result) {
            $.ajax({
                url: '../ajax/visitasinstalacionesAjax.php?op=finalizarmodernizacion',
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,

                success: function(datos) {
                    bootbox.alert(datos);
                    revisarform(false);
                    tabla.ajax.reload();
                }
            });
        }
    });

    limpiar();
}

function pdf(idventa) {


    $.post("../ajax/venta.php?op=PDFCorto", { idventa: idventa }, function(data, status) {
        data = JSON.parse(data);
        console.log(data);
        var company_logo = {
            firma: 'data:image/png;base64,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Oi8vd3d3LmdpbXAub3JnL3htcC8iCiAgICB4bWxuczpkYz0iaHR0cDovL3B1cmwub3JnL2RjL2VsZW1lbnRzLzEuMS8iCiAgICB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iCiAgIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6Q0MxRTdERTM2NkIzMTFFNkFCRjlGOTU5ODYyNkQ1N0MiCiAgIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NmM0OTBlMGYtNzBlZi00OTIwLTkxNzctMjNjZWVmMjFjZWJjIgogICB4bXBNTTpPcmlnaW5hbERvY3VtZW50SUQ9InhtcC5kaWQ6MDc1ZDQ1ZjctODliNy00YzY0LWE1NTctMjBhNmRiNmQ4YjRkIgogICBHSU1QOkFQST0iMi4wIgogICBHSU1QOlBsYXRmb3JtPSJMaW51eCIKICAgR0lNUDpUaW1lU3RhbXA9IjE1MzI5Njk2NDM5MDQ2MzMiCiAgIEdJTVA6VmVyc2lvbj0iMi4xMC4wIgogICBkYzpGb3JtYXQ9ImltYWdlL3BuZyIKICAgeG1wOkNyZWF0b3JUb29sPSJHSU1QIDIuMTAiPgogICA8aXB0Y0V4dDpMb2NhdGlvbkNyZWF0ZWQ+CiAgICA8cmRmOkJhZy8+CiAgIDwvaXB0Y0V4dDpMb2NhdGlvbkNyZWF0ZWQ+CiAgIDxpcHRjRXh0OkxvY2F0aW9uU2hvd24+CiAgICA8cmRmOkJhZy8+CiAgIDwvaXB0Y0V4dDpMb2NhdGlvblNob3duPgogICA8aXB0Y0V4dDpBcnR3b3JrT3JPYmplY3Q+CiAgICA8cmRmOkJhZy8+CiAgIDwvaXB0Y0V4dDpBcnR3b3JrT3JPYmplY3Q+CiAgIDxpcHRjRXh0OlJlZ2lzdHJ5SWQ+CiAgICA8cmRmOkJhZy8+CiAgIDwvaXB0Y0V4dDpSZWdpc3RyeUlkPgogICA8eG1wTU06SGlzdG9yeT4KICAgIDxyZGY6U2VxPgogICAgIDxyZGY6bGkKICAgICAgc3RFdnQ6YWN0aW9uPSJzYXZlZCIKICAgICAgc3RFdnQ6Y2hhbmdlZD0iLyIKICAgICAgc3RFdnQ6aW5zdGFuY2VJRD0ieG1wLmlpZDpkYzI3YTlmZS1hYTBlLTRkM2ItOGU1Yi0zMjhiNDJkYTk1ZTIiCiAgICAgIHN0RXZ0OnNvZnR3YXJlQWdlbnQ9IkdpbXAgMi4xMCAoTGludXgpIgogICAgICBzdEV2dDp3aGVuPSItMDQ6MDAiLz4KICAgICA8cmRmOmxpCiAgICAgIHN0RXZ0OmFjdGlvbj0ic2F2ZWQiCiAgICAgIHN0RXZ0OmNoYW5nZWQ9Ii8iCiAgICAgIHN0RXZ0Omluc3RhbmNlSUQ9InhtcC5paWQ6NGNiMGYzOGYtMWJhMC00MDdjLTgyODktMGE0ZGVlMDM4ZTY0IgogICAgICBzdEV2dDpzb2Z0d2FyZUFnZW50PSJHaW1wIDIuMTAgKExpbnV4KSIKICAgICAgc3RFdnQ6d2hlbj0iLTA0OjAwIi8+CiAgICA8L3JkZjpTZXE+CiAgIDwveG1wTU06SGlzdG9yeT4KICAgPHhtcE1NOkRlcml2ZWRGcm9tCiAgICBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOkNDMUU3REUxNjZCMzExRTZBQkY5Rjk1OTg2MjZENTdDIgogICAgc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpDQzFFN0RFMDY2QjMxMUU2QUJGOUY5NTk4NjI2RDU3QyIvPgogICA8cGx1czpJbWFnZVN1cHBsaWVyPgogICAgPHJkZjpTZXEvPgogICA8L3BsdXM6SW1hZ2VTdXBwbGllcj4KICAgPHBsdXM6SW1hZ2VDcmVhdG9yPgogICAgPHJkZjpTZXEvPgogICA8L3BsdXM6SW1hZ2VDcmVhdG9yPgogICA8cGx1czpDb3B5cmlnaHRPd25lcj4KICAgIDxyZGY6U2VxLz4KICAgPC9wbHVzOkNvcHlyaWdodE93bmVyPgogICA8cGx1czpMaWNlbnNvcj4KICAgIDxyZGY6U2VxLz4KICAgPC9wbHVzOkxpY2Vuc29yPgogIDwvcmRmOkRlc2NyaXB0aW9uPgogPC9yZGY6UkRGPgo8L3g6eG1wbWV0YT4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg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',
            srcf: 'data:image/jpeg;base64,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',
            src: 'data:image/jpeg;base64,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',
            w: 200,
            h: 40,
            wf: 130
        };

        var fontSizes = {
            HeadTitleFontSize: 18,
            Head2TitleFontSize: 16,
            TitleFontSize: 14,
            SubTitleFontSize: 12,
            NormalFontSize: 10,
            SmallFontSize: 8
        };

        var lineSpacing = {
            NormalSpacing: 12,
            DobleSpacing: 24,
            LineSpacing: 10,
            LetterSpace: 6
        };


        var doc = new jsPDF('p', 'pt', 'letter');

        var rightStartCol1 = 40;
        var rightStartCol2 = 530;

        var border = 3;
        var tabla = 0;


        var InitialstartX = 45;
        var startX = 40;
        var startbX = 43;
        var InitialstartY = 20;
        var startY = 20;
        var lines = 0;
        var space = 0;

        doc.addImage(company_logo.src, 'PNG', startX, startY, company_logo.w, company_logo.h);
        doc.addImage(company_logo.srcf, 'PNG', startX + 400, startY, company_logo.wf, company_logo.h);
        startY += lineSpacing.NormalSpacing + company_logo.h;

        startY += lineSpacing.NormalSpacing;

        doc.setFontSize(fontSizes.Head2TitleFontSize);
        doc.setFontType('bold');
        doc.myText("MEMO DE VENTA", { align: "center" }, 0, startY);

        startY += lineSpacing.NormalSpacing;

        doc.setDrawColor(0);
        doc.setFillColor(255, 255, 255);
        doc.roundedRect(startX, startY, 100, 20, 0, 0, 'FD');

        doc.setFontSize(fontSizes.TitleFontSize);
        doc.setFontType('bold');
        doc.text(InitialstartX, startY + 20 - border, 'N° ' + data.Datos.codigo);

        doc.setDrawColor(0);
        doc.setFillColor(170, 170, 170);
        doc.roundedRect(startX + 100, startY, 320, 20, 0, 0, 'FD');

        doc.setFontSize(fontSizes.TitleFontSize);
        doc.setFontType('bold');
        doc.setTextColor(255, 255, 255);
        doc.myText(data.Datos.proyecto, { align: "center" }, 0, startY + 20 - border);

        doc.setDrawColor(0);
        doc.setFillColor(255, 255, 255);
        doc.roundedRect(startX + 420, startY, 100, 20, 0, 0, 'FD');

        doc.setFontSize(fontSizes.TitleFontSize);
        doc.setFontType('bold');
        doc.setTextColor(0)
        doc.text(InitialstartX + 420, startY + 20 - border, data.Datos.fecha);

        //INFORMACION DE LA VENTA

        doc.setDrawColor(0);
        doc.setFillColor(230, 230, 230);
        doc.roundedRect(startX, startY += 20, 520, 20, 0, 0, 'FD');

        doc.setFontSize(fontSizes.SubTitleFontSize);
        doc.setFontType('bold');
        doc.setTextColor(0);
        doc.myText('INFORMACION DE LA VENTA', { align: "center" }, 0, startY + 20 - border);

        doc.setDrawColor(0);
        doc.setFillColor(255, 255, 255);
        doc.roundedRect(startX, startY += 20, 173, (lineSpacing.NormalSpacing * 4), 0, 0, 'FD');

        doc.setDrawColor(0);
        doc.setFillColor(255, 255, 255);
        doc.roundedRect(startX + 173, startY, 173, (lineSpacing.NormalSpacing * 4), 0, 0, 'FD');

        doc.setDrawColor(0);
        doc.setFillColor(255, 255, 255);
        doc.roundedRect(startX + 346, startY, 174, (lineSpacing.NormalSpacing * 4), 0, 0, 'FD');

        doc.setFontSize(fontSizes.SmallFontSize);
        doc.setFontType('bold');
        doc.text(startX + 86, startY += lineSpacing.NormalSpacing, "TIPO DE VENTA", 'center');
        doc.text(startX + 259, startY, "IMPORTACION", 'center');
        doc.text(startX + 432, startY, "CENTRO DE COSTO", 'center');
        doc.setFontType('normal');
        doc.text(startX + 86, startY += lineSpacing.NormalSpacing, data.Datos.descripcion, 'center');
        doc.text(startX + 259, startY, data.Datos.proveedor, 'center');
        doc.text(startX + 432, startY, data.Datos.ccnom, 'center');
        doc.text(startX + 86, startY += lineSpacing.NormalSpacing, '', 'center');
        doc.text(startX + 259, startY, data.Datos.codigoimp, 'center');
        doc.text(startX + 432, startY, data.Datos.cc, 'center');

        //INFORMACION DEL PROYECTO
        doc.setDrawColor(0);
        doc.setFillColor(230, 230, 230);
        doc.roundedRect(startX, startY += lineSpacing.NormalSpacing, 520, 20, 0, 0, 'FD');

        doc.setFontSize(fontSizes.SubTitleFontSize);
        doc.setFontType('bold');
        doc.setTextColor(0);
        doc.myText('INFORMACION DEL PROYECTO', { align: "center" }, 0, startY + 20 - border);

        doc.setDrawColor(0);
        doc.setFillColor(255, 255, 255);
        doc.roundedRect(startX, startY += 20, 520, (lineSpacing.NormalSpacing * 3), 0, 0, 'FD');

        doc.setFontSize(fontSizes.SmallFontSize);
        doc.setFontType('bold');
        doc.text(startbX, startY += lineSpacing.NormalSpacing, "NOMBRE: ");
        doc.setFontType('normal');
        doc.text(startbX + 40, startY, data.Datos.proyecto);

        doc.setFontType('bold');
        doc.text(startbX, startY += lineSpacing.NormalSpacing, "DIRECCION: ");
        doc.setFontType('normal');
        doc.text(startbX + 50, startY, data.Datos.direcpro);

        //CONTACTO DE VENTAS Y CONTACTO OBRA

        doc.setDrawColor(0);
        doc.setFillColor(230, 230, 230);
        doc.roundedRect(startX, startY += lineSpacing.NormalSpacing, 520, 10, 0, 0, 'FD');

        doc.setFontType('bold');
        doc.setFontSize(fontSizes.SmallFontSize);
        doc.text(startX + 130, startY + 10 - 1, 'CONTACTO DE VENTAS', 'center');
        doc.text(startX + 260 + 130, startY + 10 - 1, 'CONTACTO DE OBRA', 'center');

        doc.setDrawColor(0);
        doc.setFillColor(255, 255, 255);
        doc.roundedRect(startX, startY += 10, 260, (lineSpacing.NormalSpacing * 5), 0, 0, 'FD');

        doc.setDrawColor(0);
        doc.setFillColor(255, 255, 255);
        doc.roundedRect(startX + 260, startY, 260, (lineSpacing.NormalSpacing * 5), 0, 0, 'FD');

        doc.setFontSize(fontSizes.SmallFontSize);
        doc.setFontType('bold');
        doc.text(startbX, startY += lineSpacing.NormalSpacing, "CONTACTO: ");
        doc.text(startbX + 260, startY, "CONTACTO: ");
        doc.setFontType('normal');

        doc.text(startbX + 50, startY, data.Datos.nom_ven);
        doc.text(startbX + 50 + 260, startY, data.Datos.nom_obra);

        doc.setFontType('bold');
        doc.text(startbX, startY += lineSpacing.NormalSpacing, "RUT: ");
        doc.text(startbX + 260, startY, "RUT: ");
        doc.setFontType('normal');

        doc.text(startbX + 20, startY, data.Datos.rut_ven);
        doc.text(startbX + 20 + 260, startY, data.Datos.rut_obra);


        doc.setFontType('bold');
        doc.text(startbX, startY += lineSpacing.NormalSpacing, "EMAIL: ");
        doc.text(startbX + 260, startY, "EMAIL: ");
        doc.setFontType('normal');

        doc.text(startbX + 30, startY, data.Datos.email_ven);
        doc.text(startbX + 30 + 260, startY, data.Datos.email_obra);

        doc.setFontType('bold');
        doc.text(startbX, startY += lineSpacing.NormalSpacing, "TELEFONO: ");
        doc.text(startbX + 260, startY, "TELEFONO: ");
        doc.setFontType('normal');

        doc.text(startbX + 50, startY, data.Datos.tele_ven);
        doc.text(startbX + 50 + 260, startY, data.Datos.tele_obra);


        //INFORMACION DEL MANDANTE
        doc.setDrawColor(0);
        doc.setFillColor(230, 230, 230);
        doc.roundedRect(startX, startY += lineSpacing.NormalSpacing, 520, 20, 0, 0, 'FD');

        doc.setFontSize(fontSizes.SubTitleFontSize);
        doc.setFontType('bold');
        doc.setTextColor(0);
        doc.myText('INFORMACION DEL MANDANTE', { align: "center" }, 0, startY + 20 - border);

        doc.setDrawColor(0);
        doc.setFillColor(255, 255, 255);
        doc.roundedRect(startX, startY += 20, 520, (lineSpacing.NormalSpacing * 4), 0, 0, 'FD');

        doc.setFontSize(fontSizes.SmallFontSize);
        doc.setFontType('bold');
        doc.text(startbX, startY += lineSpacing.NormalSpacing, "RAZON SOCIAL: ");
        doc.setFontType('normal');
        doc.text(startbX + 65, startY, data.Datos.razon_social);

        doc.setFontType('bold');
        doc.text(startbX, startY += lineSpacing.NormalSpacing, "RUT: ");
        doc.setFontType('normal');
        doc.text(startbX + 20, startY, data.Datos.rut);

        doc.setFontType('bold');
        doc.text(startbX, startY += lineSpacing.NormalSpacing, "DIRECCION: ");
        doc.setFontType('normal');
        doc.text(startbX + 50, startY, data.Datos.direcman);

        //INFORMACION DEL CONTRACTUAL
        doc.setDrawColor(0);
        doc.setFillColor(230, 230, 230);
        doc.roundedRect(startX, startY += lineSpacing.NormalSpacing, 520, 20, 0, 0, 'FD');

        doc.setFontSize(fontSizes.SubTitleFontSize);
        doc.setFontType('bold');
        doc.setTextColor(0);
        doc.myText('INFORMACION CONTRACTUAL INSTALACION', { align: "center" }, 0, startY + 20 - border);

        doc.setDrawColor(0);
        doc.setFillColor(255, 255, 255);
        doc.roundedRect(startX, startY += 20, 220, (lineSpacing.NormalSpacing * (parseInt(data.Etapas) + 2)), 0, 0, 'FD');

        var infoStarty = startY;
        doc.setFontSize(fontSizes.SmallFontSize);
        switch (data.Info.length % data.Etapas) {
            case 2:
                doc.setDrawColor(0);
                doc.setFillColor(255, 255, 255);
                doc.roundedRect(startX + 220, infoStarty, 150, (lineSpacing.NormalSpacing * (parseInt(data.Etapas) + 1)), 0, 0, 'FD');
                doc.setDrawColor(0);
                doc.setFillColor(255, 255, 255);
                doc.roundedRect(startX + 220 + 150, infoStarty, 150, (lineSpacing.NormalSpacing * (parseInt(data.Etapas) + 1)), 0, 0, 'FD');

                break;

            case 3:
                doc.setDrawColor(0);
                doc.setFillColor(255, 255, 255);
                doc.roundedRect(startX + 220, infoStarty, 100, (lineSpacing.NormalSpacing * (parseInt(data.Etapas) + 1)), 0, 0, 'FD');
                doc.setDrawColor(0);
                doc.setFillColor(255, 255, 255);
                doc.roundedRect(startX + 220 + 100, infoStarty, 100, (lineSpacing.NormalSpacing * (parseInt(data.Etapas) + 1)), 0, 0, 'FD');
                doc.setDrawColor(0);
                doc.setFillColor(255, 255, 255);
                doc.roundedRect(startX + 220 + 100 + 100, infoStarty, 100, (lineSpacing.NormalSpacing * (parseInt(data.Etapas) + 1)), 0, 0, 'FD');
                break;

            default:
                doc.setDrawColor(0);
                doc.setFillColor(255, 255, 255);
                doc.roundedRect(startX + 220, infoStarty, 300, (lineSpacing.NormalSpacing * (parseInt(data.Etapas) + 2)), 0, 0, 'FD');
                for (var i = 0; i < data.Info.length; i++) {
                    doc.setFontType('bold');
                    doc.text(startX + 110, infoStarty += lineSpacing.NormalSpacing, data.Info[i][0], 'center');
                    doc.setFontType('normal');
                    doc.text(startX + 220 + 150, infoStarty, data.Info[i][1], 'center');
                }
                break;
        }

        //INFORMACION DEL CONTRACTUAL
        doc.setDrawColor(0);
        doc.setFillColor(230, 230, 230);
        doc.roundedRect(startX, startY += (lineSpacing.NormalSpacing * (parseInt(data.Etapas) + 2)), 520, 20, 0, 0, 'FD');

        doc.setFontSize(fontSizes.SubTitleFontSize);
        doc.setFontType('bold');
        doc.setTextColor(0);
        doc.myText('FORMA DE PAGO CONTRACTUAL', { align: "center" }, 0, startY + 20 - border);

        doc.setDrawColor(0);
        doc.setFillColor(230, 230, 230);
        doc.roundedRect(startX, startY += 20, 520, 10, 0, 0, 'FD');

        doc.setFontType('bold');
        doc.setFontSize(fontSizes.SmallFontSize);
        doc.text(startX + 130, startY + 10 - 1, 'PARTE IMPORTADA', 'center');
        doc.text(startX + 260 + 130, startY + 10 - 1, 'PARTE NACIONAL', 'center');

        if (parseInt(data.FSi.length) > parseInt(data.FSn.length)) {
            doc.setDrawColor(0);
            doc.setFillColor(255, 255, 255);
            doc.roundedRect(startX, startY += 10, 260, (lineSpacing.NormalSpacing * (parseInt(data.FSi.length) + 1)), 0, 0, 'FD');
            doc.setDrawColor(0);
            doc.setFillColor(255, 255, 255);
            doc.roundedRect(startX + 260, startY, 260, (lineSpacing.NormalSpacing * (parseInt(data.FSi.length) + 1)), 0, 0, 'FD');
            infoStarty = startY;
            for (var i = 0; i < data.FSi.length; i++) {
                doc.setFontType('normal');
                doc.text(startX + 130, infoStarty += lineSpacing.NormalSpacing, data.FSi[i][0] + ' (' + parseInt(data.FSi[i][1] * 100) + '%)', 'center');
            }
            for (var i = 0; i < data.FSn.length; i++) {
                doc.setFontType('normal');
                doc.text(startX + 260 + 130, startY += lineSpacing.NormalSpacing, data.FSn[i][0] + ' (' + parseInt(data.FSn[i][1] * 100) + '%)', 'center');
            }
            startY += lineSpacing.NormalSpacing;
        } else {
            doc.setDrawColor(0);
            doc.setFillColor(255, 255, 255);
            doc.roundedRect(startX, startY += 10, 260, (lineSpacing.NormalSpacing * (parseInt(data.FSn.length) + 1)), 0, 0, 'FD');
            doc.setDrawColor(0);
            doc.setFillColor(255, 255, 255);
            doc.roundedRect(startX + 260, startY, 260, (lineSpacing.NormalSpacing * (parseInt(data.FSn.length) + 1)), 0, 0, 'FD');
            infoStarty = startY;
            for (var i = 0; i < data.FSi.length; i++) {
                doc.setFontType('normal');
                doc.text(startX + 130, infoStarty += lineSpacing.NormalSpacing, data.FSi[i][0] + ' (' + parseInt(data.FSi[i][1] * 100) + '%)', 'center');
            }
            for (var i = 0; i < data.FSn.length; i++) {
                doc.setFontType('normal');
                doc.text(startX + 260 + 130, startY += lineSpacing.NormalSpacing, data.FSn[i][0] + ' (' + parseInt(data.FSn[i][1] * 100) + '%) ', 'center');
            }
            startY += lineSpacing.NormalSpacing;
        }

        //OTRO ACUERDOS CONTRACTUALES
        doc.setDrawColor(0);
        doc.setFillColor(255, 255, 255);
        doc.roundedRect(startX, startY, 130, 10, 0, 0, 'FD');
        doc.roundedRect(startX + 130, startY, 130, 10, 0, 0, 'FD');
        doc.roundedRect(startX + 260, startY, 130, 10, 0, 0, 'FD');
        doc.roundedRect(startX + 390, startY, 130, 10, 0, 0, 'FD');

        doc.setFontType('bold');
        doc.setFontSize(fontSizes.SmallFontSize);
        doc.text(startbX, startY + 10 - 1, 'CONTRATO:');
        doc.text(startbX + 130, startY + 10 - 1, 'RETENCIONES:');
        doc.text(startbX + 260, startY + 10 - 1, 'MULTAS:');
        doc.text(startbX + 390, startY + 10 - 1, 'ORDEN DE COMPRA:');
        doc.setFontType('normal');
        doc.text(startbX + 50, startY + 10 - 1, data.Datos.contrato);
        doc.text(startbX + 130 + 65, startY + 10 - 1, data.Datos.retenciones);
        doc.text(startbX + 260 + 40, startY + 10 - 1, data.Datos.multas);
        doc.text(startbX + 390 + 85, startY + 10 - 1, data.Datos.oc);

        doc.setDrawColor(0);
        doc.setFillColor(255, 255, 255);
        doc.roundedRect(startX, startY += 10, 260, 10, 0, 0, 'FD');
        doc.roundedRect(startX + 260, startY, 260, 10, 0, 0, 'FD');
        doc.setFontType('bold');
        startY += 10;
        doc.text(startbX, startY - 1, 'GARANTIA MAYOR A 12 MESES:');
        doc.text(startbX + 260, startY - 1, 'MANTENCION SIN COSTO POST ENTREGA:');
        doc.setFontType('normal');

        doc.text(startbX + 130, startY - 1, data.Datos.garan_ex);
        doc.text(startbX + 260 + 170, startY - 1, data.Datos.man_post);

        if (data.Boletas.length > 0) {

            doc.setDrawColor(0);
            doc.setFillColor(230, 230, 230);
            doc.roundedRect(startX, startY, 520, 10, 0, 0, 'FD');

            doc.setFontType('bold');
            doc.setFontSize(fontSizes.SmallFontSize);
            doc.text(startX + 260, startY + 10 - 1, 'GARANTIAS', 'center');

            startY += 10;
            console.log(data.Boletas.length);
            var salto = 0;

            for (var i = 0; i < data.Boletas.length; i++) {

                if (i % 3 == 0) {
                    //startY = +data.Equipos[i].length * lineSpacing.NormalSpacing;

                    if (i == 0) {
                        var startyb = startY;
                        console.log('inicio ' + startY);
                    } else if (salto == 1) {
                        var startyb = startY;
                        console.log('Salto de pagina ' + startY);
                    } else {
                        startY += (lineSpacing.NormalSpacing * 6);
                        console.log('salto de linea ' + startY);
                        var startyb = startY;
                    }

                    var startxb = 160;
                    var medio = 67;
                    doc.setDrawColor(0);
                    doc.setFillColor(230, 230, 230);
                    doc.roundedRect(startX, startyb, 120, lineSpacing.NormalSpacing * 6, 0, 0, 'FD');

                    doc.setFontType('bold');
                    doc.text(startX + 60, startyb += lineSpacing.NormalSpacing, 'EMISOR', 'center');
                    doc.text(startX + 60, startyb += lineSpacing.NormalSpacing, 'TIPO DE GARANTIA', 'center');
                    doc.text(startX + 60, startyb += lineSpacing.NormalSpacing, 'TIPO DOCUMENTO', 'center');
                    doc.text(startX + 60, startyb += lineSpacing.NormalSpacing, 'DURACION', 'center');
                    doc.text(startX + 60, startyb += lineSpacing.NormalSpacing, 'VENCIMIENTO', 'center');
                }

                infoStarty = startY;
                doc.setDrawColor(0);
                doc.setFillColor(255, 255, 255);
                doc.roundedRect(startxb, infoStarty, 133, (lineSpacing.NormalSpacing * (data.Boletas[i].length + 1)), 0, 0, 'FD');
                for (var o = 0; o < data.Boletas[i].length; o++) {
                    doc.setFontType('normal');
                    if (data.Boletas[i][o] == null) {
                        doc.text(startxb + medio, infoStarty += lineSpacing.NormalSpacing, 'SIN INFORMACION', 'center');
                    } else {
                        doc.text(startxb + medio, infoStarty += lineSpacing.NormalSpacing, data.Boletas[i][o], 'center');
                    }

                }
                startxb += 133;

            }

        }

        doc.setLineWidth(0.5);
        var firmay = 750;
        doc.line(startX + 100, firmay, startX + 250, firmay);
        doc.line(startX + 300, firmay, startX + 450, firmay);
        doc.text(startX + 175, firmay += lineSpacing.NormalSpacing, 'VENDEDOR', 'center');
        doc.text(startX + 375, firmay, 'GERENCIA', 'center');
        doc.text(startX + 175, firmay += lineSpacing.NormalSpacing, data.Datos.vendedor, 'center');
        doc.text(startX + 375, firmay, 'MAURICIO GIORNADO', 'center');


        //INICIO SEGUNDA PAGINA

        if (data.Equipos.length > 0) {

            doc.addPage();
            startY = 20;

            doc.addImage(company_logo.src, 'PNG', startX, startY, company_logo.w, company_logo.h);
            doc.addImage(company_logo.srcf, 'PNG', startX + 400, startY, company_logo.wf, company_logo.h);
            startY += lineSpacing.NormalSpacing + company_logo.h;

            startY += lineSpacing.NormalSpacing;

            doc.setFontSize(fontSizes.Head2TitleFontSize);
            doc.setFontType('bold');
            doc.myText("MEMO DE VENTA", { align: "center" }, 0, startY);

            startY += lineSpacing.NormalSpacing;

            doc.setDrawColor(0);
            doc.setFillColor(255, 255, 255);
            doc.roundedRect(startX, startY, 100, 20, 0, 0, 'FD');

            doc.setFontSize(fontSizes.TitleFontSize);
            doc.setFontType('bold');
            doc.text(InitialstartX, startY + 20 - border, 'N° ' + data.Datos.codigo);

            doc.setDrawColor(0);
            doc.setFillColor(170, 170, 170);
            doc.roundedRect(startX + 100, startY, 320, 20, 0, 0, 'FD');

            doc.setFontSize(fontSizes.TitleFontSize);
            doc.setFontType('bold');
            doc.setTextColor(255, 255, 255);
            doc.myText(data.Datos.proyecto, { align: "center" }, 0, startY + 20 - border);

            doc.setDrawColor(0);
            doc.setFillColor(255, 255, 255);
            doc.roundedRect(startX + 420, startY, 100, 20, 0, 0, 'FD');

            doc.setFontSize(fontSizes.TitleFontSize);
            doc.setFontType('bold');
            doc.setTextColor(0)
            doc.text(InitialstartX + 420, startY + 20 - border, data.Datos.fecha);

            //INFORMACION DE EQUIPOS

            doc.setDrawColor(0);
            doc.setFillColor(230, 230, 230);
            doc.roundedRect(startX, startY += 20, 520, 20, 0, 0, 'FD');

            doc.setFontSize(fontSizes.SubTitleFontSize);
            doc.setFontType('bold');
            doc.setTextColor(0);
            doc.myText('EQUIPOS', { align: "center" }, 0, startY + 20 - border);

            startY += 20;
            doc.setFontSize(fontSizes.SmallFontSize);

            console.log(data.Equipos.length);
            var salto = 0;

            for (var i = 0; i < data.Equipos.length; i++) {

                if (i % 2 == 0) {
                    //startY = +data.Equipos[i].length * lineSpacing.NormalSpacing;

                    if (i == 0) {
                        var startyb = startY;
                        console.log('inicio ' + startY);
                    } else if (salto == 1) {
                        var startyb = startY;
                        console.log('Salto de pagina ' + startY);
                    } else {
                        startY += (lineSpacing.NormalSpacing * 8);
                        console.log('salto de linea ' + startY);
                        var startyb = startY;
                    }

                    var startxb = 160;
                    var medio = 100;
                    doc.setDrawColor(0);
                    doc.setFillColor(230, 230, 230);
                    doc.roundedRect(startX, startyb, 120, lineSpacing.NormalSpacing * 8, 0, 0, 'FD');

                    doc.setFontType('bold');
                    doc.text(startX + 60, startyb += lineSpacing.NormalSpacing, 'TIPO DE EQUIPO', 'center');
                    doc.text(startX + 60, startyb += lineSpacing.NormalSpacing, 'MODELO/MARCA', 'center');
                    doc.text(startX + 60, startyb += lineSpacing.NormalSpacing, 'COMANDO', 'center');
                    doc.text(startX + 60, startyb += lineSpacing.NormalSpacing, 'CODIGO FM', 'center');
                    doc.text(startX + 60, startyb += lineSpacing.NormalSpacing, 'KEN', 'center');
                    doc.text(startX + 60, startyb += lineSpacing.NormalSpacing, 'PARADAS / ACCESOS', 'center');
                    doc.text(startX + 60, startyb += lineSpacing.NormalSpacing, 'CAP. PER / CAP. KG', 'center');
                }

                infoStarty = startY;
                doc.setDrawColor(0);
                doc.setFillColor(255, 255, 255);
                doc.roundedRect(startxb, infoStarty, 200, (lineSpacing.NormalSpacing * (data.Equipos[i].length + 1)), 0, 0, 'FD');
                for (var o = 0; o < data.Equipos[i].length; o++) {
                    doc.setFontType('normal');
                    if (data.Equipos[i][o] == null) {
                        doc.text(startxb + medio, infoStarty += lineSpacing.NormalSpacing, 'SIN INFORMACION', 'center');
                    } else {
                        doc.text(startxb + medio, infoStarty += lineSpacing.NormalSpacing, data.Equipos[i][o], 'center');
                    }

                }
                startxb += 200;

            }

        }

        doc.setLineWidth(0.5);
        var firmay = 750;
        doc.line(startX + 100, firmay, startX + 250, firmay);
        doc.line(startX + 300, firmay, startX + 450, firmay);
        doc.text(startX + 175, firmay += lineSpacing.NormalSpacing, 'VENDEDOR', 'center');
        doc.text(startX + 375, firmay, 'GERENCIA', 'center');
        doc.text(startX + 175, firmay += lineSpacing.NormalSpacing, data.Datos.vendedor, 'center');
        doc.text(startX + 375, firmay, 'MAURICIO GIORNADO', 'center');

        doc.save(data.Datos.codigoimp + ' - Memo de Venta.pdf');

    });
}


function pdfvisitas(idproyecto) {


    $.post("../ajax/visitasinstalacionesAjax.php?op=PDF", { idproyecto: idproyecto }, function(data, status) {
        data = JSON.parse(data);
        console.log(data);
        var company_logo = {
            firma: 'data:image/png;base64,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Oi8vd3d3LmdpbXAub3JnL3htcC8iCiAgICB4bWxuczpkYz0iaHR0cDovL3B1cmwub3JnL2RjL2VsZW1lbnRzLzEuMS8iCiAgICB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iCiAgIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6Q0MxRTdERTM2NkIzMTFFNkFCRjlGOTU5ODYyNkQ1N0MiCiAgIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NmM0OTBlMGYtNzBlZi00OTIwLTkxNzctMjNjZWVmMjFjZWJjIgogICB4bXBNTTpPcmlnaW5hbERvY3VtZW50SUQ9InhtcC5kaWQ6MDc1ZDQ1ZjctODliNy00YzY0LWE1NTctMjBhNmRiNmQ4YjRkIgogICBHSU1QOkFQST0iMi4wIgogICBHSU1QOlBsYXRmb3JtPSJMaW51eCIKICAgR0lNUDpUaW1lU3RhbXA9IjE1MzI5Njk2NDM5MDQ2MzMiCiAgIEdJTVA6VmVyc2lvbj0iMi4xMC4wIgogICBkYzpGb3JtYXQ9ImltYWdlL3BuZyIKICAgeG1wOkNyZWF0b3JUb29sPSJHSU1QIDIuMTAiPgogICA8aXB0Y0V4dDpMb2NhdGlvbkNyZWF0ZWQ+CiAgICA8cmRmOkJhZy8+CiAgIDwvaXB0Y0V4dDpMb2NhdGlvbkNyZWF0ZWQ+CiAgIDxpcHRjRXh0OkxvY2F0aW9uU2hvd24+CiAgICA8cmRmOkJhZy8+CiAgIDwvaXB0Y0V4dDpMb2NhdGlvblNob3duPgogICA8aXB0Y0V4dDpBcnR3b3JrT3JPYmplY3Q+CiAgICA8cmRmOkJhZy8+CiAgIDwvaXB0Y0V4dDpBcnR3b3JrT3JPYmplY3Q+CiAgIDxpcHRjRXh0OlJlZ2lzdHJ5SWQ+CiAgICA8cmRmOkJhZy8+CiAgIDwvaXB0Y0V4dDpSZWdpc3RyeUlkPgogICA8eG1wTU06SGlzdG9yeT4KICAgIDxyZGY6U2VxPgogICAgIDxyZGY6bGkKICAgICAgc3RFdnQ6YWN0aW9uPSJzYXZlZCIKICAgICAgc3RFdnQ6Y2hhbmdlZD0iLyIKICAgICAgc3RFdnQ6aW5zdGFuY2VJRD0ieG1wLmlpZDpkYzI3YTlmZS1hYTBlLTRkM2ItOGU1Yi0zMjhiNDJkYTk1ZTIiCiAgICAgIHN0RXZ0OnNvZnR3YXJlQWdlbnQ9IkdpbXAgMi4xMCAoTGludXgpIgogICAgICBzdEV2dDp3aGVuPSItMDQ6MDAiLz4KICAgICA8cmRmOmxpCiAgICAgIHN0RXZ0OmFjdGlvbj0ic2F2ZWQiCiAgICAgIHN0RXZ0OmNoYW5nZWQ9Ii8iCiAgICAgIHN0RXZ0Omluc3RhbmNlSUQ9InhtcC5paWQ6NGNiMGYzOGYtMWJhMC00MDdjLTgyODktMGE0ZGVlMDM4ZTY0IgogICAgICBzdEV2dDpzb2Z0d2FyZUFnZW50PSJHaW1wIDIuMTAgKExpbnV4KSIKICAgICAgc3RFdnQ6d2hlbj0iLTA0OjAwIi8+CiAgICA8L3JkZjpTZXE+CiAgIDwveG1wTU06SGlzdG9yeT4KICAgPHhtcE1NOkRlcml2ZWRGcm9tCiAgICBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOkNDMUU3REUxNjZCMzExRTZBQkY5Rjk1OTg2MjZENTdDIgogICAgc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpDQzFFN0RFMDY2QjMxMUU2QUJGOUY5NTk4NjI2RDU3QyIvPgogICA8cGx1czpJbWFnZVN1cHBsaWVyPgogICAgPHJkZjpTZXEvPgogICA8L3BsdXM6SW1hZ2VTdXBwbGllcj4KICAgPHBsdXM6SW1hZ2VDcmVhdG9yPgogICAgPHJkZjpTZXEvPgogICA8L3BsdXM6SW1hZ2VDcmVhdG9yPgogICA8cGx1czpDb3B5cmlnaHRPd25lcj4KICAgIDxyZGY6U2VxLz4KICAgPC9wbHVzOkNvcHlyaWdodE93bmVyPgogICA8cGx1czpMaWNlbnNvcj4KICAgIDxyZGY6U2VxLz4KICAgPC9wbHVzOkxpY2Vuc29yPgogIDwvcmRmOkRlc2NyaXB0aW9uPgogPC9yZGY6UkRGPgo8L3g6eG1wbWV0YT4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAg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',
            srcf: 'data:image/jpeg;base64,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',
            src: 'data:image/jpeg;base64,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',
            w: 200,
            h: 40,
            wf: 130
        };

        var fontSizes = {
            HeadTitleFontSize: 18,
            Head2TitleFontSize: 16,
            TitleFontSize: 14,
            SubTitleFontSize: 12,
            NormalFontSize: 10,
            SmallFontSize: 8
        };

        var lineSpacing = {
            NormalSpacing: 12,
            DobleSpacing: 24,
            LineSpacing: 10,
            LetterSpace: 6
        };


        var doc = new jsPDF('p', 'pt', 'letter');

        var rightStartCol1 = 40;
        var rightStartCol2 = 530;

        var border = 3;
        var tabla = 0;


        var InitialstartX = 45;
        var startX = 40;
        var startbX = 43;
        var InitialstartY = 20;
        var startY = 20;
        var lines = 0;
        var space = 0;

        doc.addImage(company_logo.src, 'PNG', startX, startY, company_logo.w, company_logo.h);
        startY += lineSpacing.NormalSpacing + company_logo.h;

        startY += lineSpacing.NormalSpacing;

        doc.setFontSize(fontSizes.Head2TitleFontSize);
        doc.setFontType('bold');
        doc.myText("REPORTE DE PROYECTO", { align: "center" }, 0, startY);

        startY += lineSpacing.NormalSpacing;

        doc.setDrawColor(0);
        doc.setFillColor(255, 255, 255);
        doc.roundedRect(startX, startY, 100, 20, 0, 0, 'FD');

        doc.setFontSize(fontSizes.TitleFontSize);
        doc.setFontType('bold');
        doc.text(InitialstartX, startY + 20 - border, data.Datos.codigoimp);

        doc.setDrawColor(0);
        doc.setFillColor(170, 170, 170);
        doc.roundedRect(startX + 100, startY, 320, 20, 0, 0, 'FD');

        doc.setFontSize(fontSizes.TitleFontSize);
        doc.setFontType('bold');
        doc.setTextColor(255, 255, 255);
        doc.myText(data.Datos.proyecto, { align: "center" }, 0, startY + 20 - border);

        doc.setDrawColor(0);
        doc.setFillColor(255, 255, 255);
        doc.roundedRect(startX + 420, startY, 100, 20, 0, 0, 'FD');

        doc.setFontSize(fontSizes.TitleFontSize);
        doc.setFontType('bold');
        doc.setTextColor(0)
        doc.text(InitialstartX + 420, startY + 20 - border, data.Datos.fecha);

        //INFORMACION DEL PROYECTO

        doc.setDrawColor(0);
        doc.setFillColor(230, 230, 230);
        doc.roundedRect(startX, startY += 20, 520, 20, 0, 0, 'FD');

        doc.setFontSize(fontSizes.SubTitleFontSize);
        doc.setFontType('bold');
        doc.setTextColor(0);
        doc.myText('INFORMACION DEL PROYECTO', { align: "center" }, 0, startY + 20 - border);

        doc.setDrawColor(0);
        doc.setFillColor(255, 255, 255);
        doc.roundedRect(startX, startY += 20, 520, (lineSpacing.NormalSpacing * 11), 0, 0, 'FD');

        doc.setFontSize(fontSizes.SmallFontSize);
        doc.setFontType('bold');
        doc.text(startbX, startY += lineSpacing.NormalSpacing, "NOMBRE: ");
        doc.setFontType('normal');
        doc.text(startbX + 40, startY, data.Datos.proyecto);

        doc.setFontType('bold');
        doc.text(startbX, startY += lineSpacing.NormalSpacing, "DIRECCION: ");
        doc.setFontType('normal');
        doc.text(startbX + 50, startY, data.Datos.direcpro);

        doc.setFontSize(fontSizes.SmallFontSize);
        doc.setFontType('bold');
        doc.text(startbX, startY += lineSpacing.NormalSpacing, "CENTRO DE COSTO: ");
        doc.setFontType('normal');
        doc.text(startbX + 85, startY, data.Datos.cc);

        doc.setFontSize(fontSizes.SmallFontSize);
        doc.setFontType('bold');
        doc.text(startbX, startY += lineSpacing.NormalSpacing, "IMPORTACION: ");
        doc.setFontType('normal');
        doc.text(startbX + 65, startY, data.Datos.codigoimp + ' - ' + data.Datos.proveedor);

        doc.setFontSize(fontSizes.SmallFontSize);
        doc.setFontType('bold');
        doc.text(startbX, startY += lineSpacing.NormalSpacing, "CANTIDAD DE EQUIPOS: ");
        doc.setFontType('normal');
        doc.text(startbX + 100, startY, data.Nequipos.toString());

        doc.setFontSize(fontSizes.SmallFontSize);
        doc.setFontType('bold');
        doc.text(startbX, startY += lineSpacing.NormalSpacing, "CANTIDAD DE VISITAS: ");
        doc.setFontType('normal');
        doc.text(startbX + 100, startY, data.Nvisitas.toString());

        doc.setFontSize(fontSizes.SmallFontSize);
        doc.setFontType('bold');
        doc.text(startbX, startY += lineSpacing.NormalSpacing, "ESTADO DEL PROYECTO: ");
        doc.setFontType('normal');
        doc.text(startbX + 110, startY, data.Datos.estado);

        doc.setFontSize(fontSizes.SmallFontSize);
        doc.setFontType('bold');
        doc.text(startbX, startY += lineSpacing.NormalSpacing, "FECHA DE INICIO: ");
        doc.setFontType('normal');
        doc.text(startbX + 75, startY, data.Datos.fechaini);

        doc.setFontSize(fontSizes.SmallFontSize);
        doc.setFontType('bold');
        doc.text(startbX, startY += lineSpacing.NormalSpacing, "ULTIMO CAMBIO DE ETAPA: ");
        doc.setFontType('normal');
        doc.text(startbX + 110, startY, data.Datos.fechaact);

        doc.setFontSize(fontSizes.SmallFontSize);
        doc.setFontType('bold');
        doc.text(startbX, startY += lineSpacing.NormalSpacing, "FECHA DE FINALIZACION: ");
        doc.setFontType('normal');
        doc.text(startbX + 105, startY, data.Datos.fechaclo);



        //INFORMACION DEL REPORTE

        doc.setDrawColor(0);
        doc.setFillColor(230, 230, 230);
        doc.roundedRect(startX, startY += lineSpacing.NormalSpacing, 520, 20, 0, 0, 'FD');

        doc.setFontSize(fontSizes.SubTitleFontSize);
        doc.setFontType('bold');
        doc.setTextColor(0);
        doc.myText('INFORMACION DE VENTA', { align: "center" }, 0, startY + 20 - border);

        doc.setDrawColor(0);
        doc.setFillColor(255, 255, 255);
        doc.roundedRect(startX, startY += 20, 520, (lineSpacing.NormalSpacing * 8), 0, 0, 'FD');

        doc.setFontSize(fontSizes.SmallFontSize);
        doc.setFontType('bold');
        doc.text(startbX, startY += lineSpacing.NormalSpacing, "FECHA DE VENTA: ");
        doc.setFontType('normal');
        doc.text(startbX + 75, startY, data.Datos.fecha);

        doc.setFontSize(fontSizes.SmallFontSize);
        doc.setFontType('bold');
        doc.text(startbX, startY += lineSpacing.NormalSpacing, "TIPO DE VENTA: ");
        doc.setFontType('normal');
        doc.text(startbX + 65, startY, data.Datos.descripcion);

        doc.setFontSize(fontSizes.SmallFontSize);
        doc.setFontType('bold');
        doc.text(startbX, startY += lineSpacing.NormalSpacing, "CODIGO DE VENTA: ");
        doc.setFontType('normal');
        doc.text(startbX + 80, startY, data.Datos.codigo);

        doc.setFontSize(fontSizes.SmallFontSize);
        doc.setFontType('bold');
        doc.text(startbX, startY += lineSpacing.NormalSpacing, "VENDEDOR: ");
        doc.setFontType('normal');
        doc.text(startbX + 50, startY, data.Datos.vendedor);

        doc.setFontSize(fontSizes.SmallFontSize);
        doc.setFontType('bold');
        doc.text(startbX, startY += lineSpacing.NormalSpacing, "MANTENCION POST ENTREGA: ");
        doc.setFontType('normal');
        doc.text(startbX + 130, startY, data.Datos.man_post);

        doc.setFontSize(fontSizes.SmallFontSize);
        doc.setFontType('bold');
        doc.text(startbX, startY += lineSpacing.NormalSpacing, "GARENTIA MAYOR A 12 MESES: ");
        doc.setFontType('normal');
        doc.text(startbX + 130, startY, data.Datos.garan_ex);


        doc.addPage();

        startY = 20;
        doc.addImage(company_logo.src, 'PNG', startX, startY, company_logo.w, company_logo.h);
        startY += lineSpacing.DobleSpacing + company_logo.h;

        doc.setFontSize(fontSizes.Head2TitleFontSize);
        doc.setFontType('bold');
        doc.myText("ETAPAS DEL PROYECTO", { align: "center" }, 0, startY);

        startY += lineSpacing.NormalSpacing;

        doc.setDrawColor(0);
        doc.setFillColor(255, 255, 255);
        doc.roundedRect(startX, startY += 20, 140, (lineSpacing.NormalSpacing * 40), 0, 0, 'FD');

        doc.setDrawColor(0);
        doc.setFillColor(255, 255, 255);
        doc.roundedRect(startX + 140, startY, 140, (lineSpacing.NormalSpacing * 40), 0, 0, 'FD');

        doc.setDrawColor(0);
        doc.setFillColor(255, 255, 255);
        doc.roundedRect(startX + 280, startY, 240, (lineSpacing.NormalSpacing * 40), 0, 0, 'FD');

        doc.setFontSize(fontSizes.SubTitleFontSize);
        doc.setFontType('bold');
        doc.text(startX + 70, startY += lineSpacing.DobleSpacing, "FECHA DE INICIO", 'center');
        doc.text(startX + 210, startY, "DURACION", 'center');
        doc.text(startX + 400, startY, "ETAPA", 'center');
        doc.setFontSize(fontSizes.NormalFontSize);
        doc.setFontType('normal');

        for (var i = 0; i < data.Etapas.length; i++) {

            doc.text(startX + 70, startY += lineSpacing.DobleSpacing, data.Etapas[i][2], 'center');
            doc.text(startX + 210, startY, data.Etapas[i][1], 'center');
            doc.text(startX + 400, startY, data.Etapas[i][0], 'center');

        }


        for (var i = 0; i < data.VisitasFe.length; i++) {

            doc.addPage();

            startY = 20;
            doc.addImage(company_logo.src, 'PNG', startX, startY, company_logo.w, company_logo.h);
            startY += lineSpacing.DobleSpacing + company_logo.h;

            doc.setFontSize(fontSizes.Head2TitleFontSize);
            doc.setFontType('bold');
            doc.myText("VISITA N° " + (i + 1), { align: "center" }, 0, startY);

            startY += lineSpacing.NormalSpacing;

            doc.setDrawColor(0);
            doc.setFillColor(170, 170, 170);
            doc.roundedRect(startX, startY += 20, 520, 20, 0, 0, 'FD');

            doc.setFontSize(fontSizes.SubTitleFontSize);
            doc.setFontType('bold');
            doc.setTextColor(0);
            doc.myText('DATOS DE LA VISITA', { align: "center" }, 0, startY + 20 - border);

            doc.setDrawColor(0);
            doc.setFillColor(255, 255, 255);
            doc.roundedRect(startX, startY += 20, 520, (lineSpacing.NormalSpacing * 3), 0, 0, 'FD');

            doc.setFontSize(fontSizes.SmallFontSize);
            doc.setFontType('bold');
            doc.text(startbX, startY += lineSpacing.NormalSpacing, "FECHA DE LA VISITA: ");
            doc.setFontType('normal');
            doc.text(startbX + 90, startY, data.VisitasFe[i][0]);

            doc.setFontType('bold');
            doc.text(startbX, startY += lineSpacing.NormalSpacing, "ESTADO DEL PROYECTO: ");
            doc.setFontType('normal');
            doc.text(startbX + 110, startY, data.VisitasFe[i][1]);

            startY += 10;

            for (var o = 0; o < data.VisitasFe[i][2].length; o++) {

                doc.setDrawColor(0);
                doc.setFillColor(230, 230, 230);
                doc.roundedRect(startX, startY, 520, 20, 0, 0, 'FD');

                doc.setFontSize(fontSizes.SubTitleFontSize);
                doc.setFontType('bold');
                doc.setTextColor(0);
                doc.myText('ASCENSOR - ' + data.VisitasFe[i][2][o][0], { align: "center" }, 0, startY + 20 - border);

                lines = doc.splitTextToSize(data.VisitasFe[i][2][o][1], 780);

                doc.setDrawColor(0);
                doc.setFillColor(255, 255, 255);
                doc.roundedRect(startX, startY += 20, 520, (lineSpacing.NormalSpacing * (lines.length + data.VisitasFe[i][2][o].length)), 0, 0, 'FD');

                doc.setFontSize(fontSizes.SmallFontSize);
                doc.setFontType('bold');
                doc.text(startbX, startY += lineSpacing.NormalSpacing, "COLABORADOR: ");
                doc.setFontType('normal');
                doc.text(startbX + 75, startY, data.VisitasFe[i][2][o][3]);

                doc.setFontType('bold');
                doc.text(startbX, startY += lineSpacing.NormalSpacing, "ESTADO DEL EQUIPO: ");
                doc.setFontType('normal');
                doc.text(startbX + 95, startY, data.VisitasFe[i][2][o][2]);

                doc.setFontType('bold');
                doc.text(startbX, startY += lineSpacing.NormalSpacing, "OBSERVACION: ");

                doc.setFontType('normal');
                doc.text(startX + border, startY += lineSpacing.NormalSpacing, lines);
                startY += lines.length * lineSpacing.NormalSpacing;

            }


        }

        doc.save('REPORTE DEL PROYECTO' + data.Datos.codigoimp + '.pdf');

    });

}





init();