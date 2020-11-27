/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/** DATATABLES ESPAÑOL */

var Español = {
    "sProcessing": "Procesando...",
    "sLengthMenu": "Mostrar _MENU_ registros",
    "sZeroRecords": "No se encontraron resultados",
    "sEmptyTable": "Ningún dato disponible en esta tabla",
    "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
    "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
    "sInfoFiltered": "<br>(filtrado de un total de _MAX_ registros)",
    "sInfoPostFix": "",
    "sSearch": "Buscar:",
    "sUrl": "",
    "sInfoThousands": ",",
    "sLoadingRecords": "Cargando...",
    "oPaginate": {
        "sFirst": "Primero",
        "sLast": "Último",
        "sNext": "Siguiente",
        "sPrevious": "Anterior"
    },
    "oAria": {
        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
    }
};

var Botones = [
    {extend: 'copyHtml5', text: '<i class="fa fa-copy" data-toggle="tooltip" data-placement="bottom" title="Copiar"></i>'},
    {extend: 'print', text: '<i class="fa fa-print" data-toggle="tooltip" data-placement="bottom" title="Imprimir"></i>'},
    {extend: 'excelHtml5', text: 'Excel'},
    {extend: 'csvHtml5', text: '<i class="fa fa-file-excel-o" data-toggle="tooltip" data-placement="bottom" title="CSV"></i>'},
    {extend: 'pdf', text: 'PDF'}
];

/** DATATABLES ESPAÑOL */

/** FORMATO FECHA DD/MM/YYYY HH:M:S */

/**
 * le da formato a una fecha
 * @param {string} texto
 * @returns {String}
 */
function formatofecha(texto) {
    if (texto != "") {
        if (texto != null) {
            var fecha = texto.split(" ");
            var info = fecha[0].split('-');
            return info[2] + '/' + info[1] + '/' + info[0] + ' ' + fecha[1];
        }
    }
}

/**
 * le da formato a una fecha
 * @param {string} texto
 * @returns {String}
 */
function formatofechacorta(texto) {
    if (texto != "") {
        if (texto != null) {
            var fecha = texto.split(" ");
            var info = fecha[0].split('-');
            return info[2] + '/' + info[1] + '/' + info[0];
        }
    }
}

/** FORMATO FECHA DD/MM/YYYY HH:M:S */

/**
 * Valida si rut es correcto
 * @param {string} rut
 * @returns {Boolean}
 */
function validarut(rut) {
    if (!/[0-9]{1,2}.[0-9]{3}.[0-9]{3}-[0-9Kk]{1}/.test(rut))
        return false;
    var tmp = rut.split('-');
    var dv1 = tmp[1], rut = tmp[0].split('.').join('');
    if (dv1 == 'K')
        dv1 = 'k';
    return (dv(rut) == dv1);
}
/**
 * calcula el digito verificador 
 * @param {string} rut
 * @returns {Number|String}
 */
function dv(rut) {
    var M = 0, S = 1;
    for (; rut; rut = Math.floor(rut / 10))
        S = (S + rut % 10 * (9 - M++ % 6)) % 11;
    return S ? S - 1 : 'k';
}


/**
 * Fullscreen de pantalla
 * @return {undefined}
 */
function fullsecreen() {
    if (document.webkitCurrentFullScreenElement != null) {
        document.webkitCancelFullScreen();
    } else {
        document.documentElement.webkitRequestFullScreen();
    }
}

/**
 * Obtiene razon social desde sistema SII
 * (**)-> Rut No existe
 * (***)-> Rut incorrecto
 * @param {string} rut 
 * @returns texto
 */
function getrazonsocial(rut) {
    var rz = "";
    $.ajax({
        url: 'https://siichile.herokuapp.com/consulta?rut='+rut,
        type: "GET",
        async: false,
        cache: false,
        timeout: 30000,
        contentType: false,
        processData: false,
        success: function (data){
            if(!data.error){
                rz = data.razon_social;
            }else{
                rz = "***";
            }
        },
        error: function (){
            rz = "**";
        }
    });
    
    return rz;
}

/**
 * Metodopara obtener un correlativo para ascensor enviando numero de region
 * @param {int} region
 * @return {int} correlativo
 */
function getcorrelativo(region){
    var correlativo = 0;
    var formData = new FormData();
    formData.append("region", region);
    //Obtengo correlativo
    $.ajax({
        url: "../ajax/correlativo_fm.php?op=getcorreativo",
        type: "POST",
        data: formData,
        async: false,
        cache: false,
        timeout: 30000,
        contentType: false,
        processData: false,
        success: function (data){
            data = JSON.parse(data);
            console.log(data);
            correlativo = parseInt(data.codigo) + 1;
            
            formData.append('correlativo', correlativo);
            //guardo el correlativo
            if (correlativo !== 0){
                $.ajax({
                    url: "../ajax/correlativo_fm.php?op=setcorrelativo",
                    type: "POST",
                    data: formData,
                    async: false,
                    cache: false,
                    timeout: 30000,
                    contentType: false,
                    processData: false,
                    success: function (data1){
                        if(data1 === 0){
                            correlativo = 0;                            
                        }
                    }
                });
            }
        },
        error: function (data){
            console.log(data);
            return 0;
        }
    });
    
    var codigo = "FM" + (region > 9 ? region : "0"+region) + "" + (correlativo > 999 ? correlativo : "0"+correlativo);
    
    if (correlativo === 0){
        codigo = 0;
    }
    return codigo;
}

/**
 * devuelve el correlativo en caso de que ocurra un error
 * @param {int} region
 * @return {Number}
 */
function setcorrelativo(region){
    var correlativo = 0;
    var formData = new FormData();
    formData.append("region", region);
    //Obtengo correlativo
    $.ajax({
        url: "../ajax/correlativo_fm.php?op=getcorreativo",
        type: "POST",
        data: formData,
        async: false,
        cache: false,
        timeout: 30000,
        contentType: false,
        processData: false,
        success: function (data){
            data = JSON.parse(data);
            console.log(data);
            correlativo = parseInt(data.codigo) - 1;
            
            formData.append('correlativo', correlativo);
            //guardo el correlativo
            if (correlativo !== 0){
                $.ajax({
                    url: "../ajax/correlativo_fm.php?op=setcorrelativo",
                    type: "POST",
                    data: formData,
                    async: false,
                    cache: false,
                    timeout: 30000,
                    contentType: false,
                    processData: false,
                    success: function (data1){
                        if(data1 !== 0){
                            correlativo = 0;
                        }
                    }
                });
            }
        },
        error: function (data){
            console.log(data);
            correlativo = 0;
        }
    });
    return correlativo;
}