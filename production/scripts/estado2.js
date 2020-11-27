var refdata, arrIn, arrOut;
var ctx, mybarChart, lineChart;

function init() {

    $('.anio_busq').select2();
    $('.anio_busqueda').select2();

    $.post("../ajax/estado.php?op=selectano", function(r) {
        $("#anio_busq").html(r);

        $("#anio_busqueda").append(r);

    });



    cargartiles();

    CargarGraficosXregion();

    GraficoAscensoresPorMes();

    setInterval("Actualizar()", 600000);
}

function Actualizar() {
    cargartiles();
    ActualizarGraficosXregion();
    ActualizaGraficoAscensoresPorMes();
}

function CargarGraficosXregion() {
    var f = new Date();
    var anio_busqueda = 0;
    if ($("#anio_busqueda").val() == null || $("#anio_busqueda").val() == '') {
        anio_busqueda = f.getFullYear();
    } else {
        anio_busqueda = $("#anio_busqueda").val()
    }
    var mes_busqueda = $("#mes_busqueda").val();

    $.post("../ajax/estado.php?op=DatosGraficoxregion", { 'ano': anio_busqueda, 'mes': mes_busqueda }, function(data, status) {
        data = JSON.parse(data);

        refdata = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        //inicializo con 0 los valores para las 15 regiones

        for (var i = 0; i < data.aaData.length; i++) {
            refdata[data.aaData[i][0] - 1] = data.aaData[i][1];
        }

        ctx = document.getElementById("mybarChart");
        ctx.height = 125;
        mybarChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ["Tarapacá", "Antofagasta", "Atacama", "Coquimbo", "Valparaiso", "Libertador Bdo. Ohiggins", "Maule", "Biobío", "La araucanía", "Los Lagos", "Aisén", "Magallanes", "Metropolitana", "Los Ríos", "Arica"],
                datasets: [{
                    label: 'Acumulado hasta la fecha ' + anio_busqueda,
                    backgroundColor: "#26B99A",
                    data: refdata
                }]
            },

            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    });

}

function ActualizarGraficosXregion() {
    var f = new Date();
    var anio_busqueda = 0;
    if ($("#anio_busqueda").val() == null || $("#anio_busqueda").val() == 0) {
        anio_busqueda = f.getFullYear();
    } else {
        anio_busqueda = $("#anio_busqueda").val()
    }
    var mes_busqueda = $("#mes_busqueda").val();



    $.post("../ajax/estado.php?op=DatosGraficoxregion", { 'ano': anio_busqueda, 'mes': mes_busqueda }, function(data, status) {
        data = JSON.parse(data);

        var updata = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

        for (var i = 0; i < data.aaData.length; i++) {
            updata[data.aaData[i][0] - 1] = data.aaData[i][1];
        }

        var cant_diferencia = 0;

        for (var i = 0; i < updata.length; i++) {
            if (refdata[i] != updata[i]) {
                cant_diferencia++;
                refdata[i] = updata[i];
            }
        }

        if (cant_diferencia > 0) {
            mybarChart.data.datasets[0]['data'] = refdata;
            mybarChart.data.datasets[0]['label'] = 'Acumulado hasta la fecha ' + anio_busqueda;
            mybarChart.update();
        }

    });
}

function cargartiles() {
    $.post("../ajax/estado.php?op=ContarDatos", function(data, status) {
        data = JSON.parse(data);

        /*calculo de porcentajes para graficar las barras*/
        var porcentajeAscNuevosKone = Math.round((parseInt(data.ascensoresNuevosKone.cantidad) / parseInt(data.ascensores.ascensores)) * 100);
        var porcentajeAscRecupKone = Math.round((parseInt(data.ascensoresRecuperadosKone.cantidad) / parseInt(data.ascensores.ascensores)) * 100);
        var porcentajeAscMultimarca = Math.round((parseInt(data.ascensoresMultimarca.cantidad) / parseInt(data.ascensores.ascensores)) * 100);


        //Actualizamos valores
        $("#num_contrato").html(data.contratos.contratos);
        $("#num_clientes").html(data.clientes.clientes);
        $("#num_edificios").html(data.edificios.edificios);
        $("#num_ascensores").html(data.ascensores.ascensores);
        $("#num_ascensores2").html(data.ascensores.ascensores);
        $("#num_ascensoresnuevoskone").html(data.ascensoresNuevosKone.cantidad + ' (' + porcentajeAscNuevosKone + '%)');
        $("#num_ascensoresrecuperadoskone").html(data.ascensoresRecuperadosKone.cantidad + ' (' + porcentajeAscRecupKone + '%)');
        $("#num_ascensoresmultimarca").html(data.ascensoresMultimarca.cantidad + ' (' + porcentajeAscMultimarca + '%)');


        $("#barra_ascensoresnuevoskone").css("width", porcentajeAscNuevosKone + "%");
        $("#barra_ascensoresrecuperadoskone").css("width", porcentajeAscRecupKone + "%");
        $("#barra_ascensoresmultimarca").css("width", porcentajeAscMultimarca + "%");

    });
}

function GraficoAscensoresPorMes() {

    var fecha = new Date();
    var anio_busq, tipo_equipo;

    if ($("#anio_busq").val() == null) {
        anio_busq = fecha.getFullYear();
    } else {
        anio_busq = $("#anio_busq").val()
    }

    if ($("#tipo_equipo").val() == null) {
        tipo_equipo = 'TODOS';
    } else {
        tipo_equipo = $("#tipo_equipo").val()
    }

    $("#titulo_grafico_lineal").html("INGRESO / SALIDA DE EQUIPOS DEL AÑO: " + anio_busq);

    $.post("../ajax/estado.php?op=GraficoIngresoSalidaAscensoresPorMes", { 'anio_busq': anio_busq, 'tipo_equipo': tipo_equipo },
        function(data, status) {
            data = JSON.parse(data);

            arrIn = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

            arrOut = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

            for (i = 0; i < data.ingreso.length; i++) {
                arrIn[parseInt(data.ingreso[i]['mes']) - 1] = data.ingreso[i]['cantidad'];;
            }

            for (i = 0; i < data.salida.length; i++) {
                arrOut[parseInt(data.salida[i]['mes']) - 1] = data.salida[i]['cantidad'];;
            }

            if ($('#lineChart1').length) {
                var ctx = document.getElementById("lineChart1");
                lineChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ["ene", "feb", "mar", "abr", "may", "jun", "jul", "ago", "sep", "oct", "nov", "dic"],
                        datasets: [{
                                label: "Ingreso de Equipos",
                                backgroundColor: "rgba(38, 185, 154, 0.31)",
                                borderColor: "rgba(38, 185, 154, 0.7)",
                                pointBorderColor: "rgba(38, 185, 154, 0.7)",
                                pointBackgroundColor: "rgba(38, 185, 154, 0.7)",
                                pointHoverBackgroundColor: "#fff",
                                pointHoverBorderColor: "rgba(220,220,220,1)",
                                pointBorderWidth: 1,
                                data: arrIn
                            },
                            {
                                label: "Salida de Equipos",
                                backgroundColor: "rgba(255,0,0, 0.31)",
                                borderColor: "rgba(255,0,0, 0.7)",
                                pointBorderColor: "rgba(38, 185, 154, 0.7)",
                                pointBackgroundColor: "rgba(38, 185, 154, 0.7)",
                                pointHoverBackgroundColor: "#fff",
                                pointHoverBorderColor: "rgba(220,220,220,1)",
                                pointBorderWidth: 1,
                                data: arrOut
                            },
                        ]
                    },
                    options: {
                        legend: {
                            display: false
                        },
                        scales: {
                            xAxes: [{
                                display: true,
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Mes'
                                }
                            }],
                            yAxes: [{
                                display: true,
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Cantidad de Equipos'
                                }
                            }]
                        }
                    }
                });

            }
        });
}

function ActualizaGraficoAscensoresPorMes() {

    var fecha = new Date();
    var anio_busq, tipo_equipo;

    if ($("#anio_busq").val() == null) {
        anio_busq = fecha.getFullYear();
    } else {
        anio_busq = $("#anio_busq").val()
    }

    if ($("#tipo_equipo").val() == null) {
        tipo_equipo = 'TODOS';
    } else {
        tipo_equipo = $("#tipo_equipo").val()
    }

    $("#titulo_grafico_lineal").html("INGRESO / SALIDA DE EQUIPOS DEL AÑO: " + anio_busq);

    $.post("../ajax/estado.php?op=GraficoIngresoSalidaAscensoresPorMes", { 'anio_busq': anio_busq, 'tipo_equipo': tipo_equipo },
        function(data, status) {
            data = JSON.parse(data);

            //comparar los datos de ingreso de equipos.
            var arrIn_update = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

            for (i = 0; i < data.ingreso.length; i++) {
                arrIn_update[parseInt(data.ingreso[i]['mes']) - 1] = data.ingreso[i]['cantidad'];;
            }

            var cant_difIn = 0;

            for (var i = 0; i < arrIn_update.length; i++) {
                if (parseInt(arrIn[i]) !== parseInt(arrIn_update[i])) {
                    cant_difIn++;
                    arrIn[i] = arrIn_update[i];
                }
            }

            //comparar los datos de salida de equipos. 
            var arrOut_update = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

            for (i = 0; i < data.salida.length; i++) {
                arrOut_update[parseInt(data.salida[i]['mes']) - 1] = data.salida[i]['cantidad'];;
            }

            var cant_difOut = 0;

            for (var i = 0; i < arrOut_update.length; i++) {
                if (parseInt(arrOut[i]) !== parseInt(arrOut_update[i])) {
                    cant_difOut++;
                    arrOut[i] = arrOut_update[i];
                }
            }

            //si consegui diferencias actualizo el grafico.
            if (cant_difIn > 0) {
                lineChart.data.datasets[0]['data'] = arrIn;
            }
            if (cant_difOut > 0) {
                lineChart.data.datasets[1]['data'] = arrOut;
            }

            if (cant_difIn > 0 || cant_difOut > 0) {
                lineChart.update();
            }
        });
}

init();