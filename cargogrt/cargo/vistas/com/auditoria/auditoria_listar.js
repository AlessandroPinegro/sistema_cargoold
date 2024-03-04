$(document).ready(function () {
    $('[data-toggle="popover"]').popover({html: true}).popover();
    cargarTitulo("titulo", "");
    select2.iniciar();
    iniciarDataPicker();
    ax.setSuccess("onResponseAuditoriaListar");
    obtenerConfiguracionesInicialesAuditoria();
    obtenerDataBusquedaAuditoria();
});

function onResponseAuditoriaListar(response) {
    if (response['status'] === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesInicialesAuditoria':
                onResponseObtenerConfiguracionesIniciales(response.data);
                colapsarBuscador();
                break;
            case 'obtenerDataAuditoria':
                onResponseGetDataGridAuditoria(response.data);
                loaderClose();
                break;
            case 'obtenerDetalleAuditoria':
                onResponseDetalleAuditoria(response.data);
                loaderClose();
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
//            case 'obtenerReporteKardexExcel':
//                loaderClose();
//                break;
        }
    }
}

function obtenerConfiguracionesInicialesAuditoria()
{
    ax.setAccion("obtenerConfiguracionesInicialesAuditoria");
    ax.addParamTmp("id_empresa", commonVars.empresa);
    ax.consumir();
}

function onResponseObtenerConfiguracionesIniciales(data) {
//    if (!isEmpty(data.persona_activa)) {
//        select2.cargar("cboPersona", data.persona_activa, "id", "nombre");
//    }
    var string = '<option selected value="-1">Seleccionar una persona</option>';
    if (!isEmpty(data.persona_activa)) {
        $.each(data.persona_activa, function (indexPersona, itemPersona) {
            string += '<option value="' + itemPersona.id + '">' + itemPersona.nombre + ' | ' + itemPersona.codigo_identificacion + '</option>';
        });
        $('#cboPersona').append(string);
        select2.asignarValor('cboPersona', "-1");
    }
    
    
    loaderClose();
}

var valoresBusquedaAuditoria = [{persona: "", fechaInicio: "",fechaFin: "", comentario: ""}];//bandera 0 es balance

function cargarDatosBusqueda()
{
    var personaId = $('#cboPersona').val();
    
    var fechaInicio = $('#txtFechaInicio').val();
    
    var fechaFin = $('#txtFechaFin').val();
    
    var comentario = $('#txtComentario').val();

    valoresBusquedaAuditoria[0].persona = personaId;
    valoresBusquedaAuditoria[0].fechaInicio = fechaInicio;
    valoresBusquedaAuditoria[0].fechaFin = fechaFin;
    valoresBusquedaAuditoria[0].comentario = comentario;
    valoresBusquedaAuditoria[0].empresaId = commonVars.empresa;
}

function obtenerDatosBusqueda()
{
    var cadena = "";
    cargarDatosBusqueda();

    if (valoresBusquedaAuditoria[0].persona != -1)
    {
        cadena += negrita("Persona: ");
        cadena += select2.obtenerText('cboPersona');
        cadena += "<br>";
    }
    
    if (!isEmpty(valoresBusquedaAuditoria[0].fechaInicio) || !isEmpty(valoresBusquedaAuditoria[0].fechaFin))
    {
        cadena += negrita("Fecha emisión: ");
        cadena += valoresBusquedaAuditoria[0].fechaInicio + " - " + valoresBusquedaAuditoria[0].fechaFin;
        cadena += "<br>";
    }
    
    if (!isEmpty(valoresBusquedaAuditoria[0].comenatario))
    {
        cadena += negrita("Comentario: ");
        cadena += $('#txtFecha').val();
        cadena += "<br>";
    }
    if(isEmpty(cadena))
    {
        cadena = "Todos";
    }
    return cadena;
}

function buscarAuditoria(colapsa) {
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    if (!isEmpty(cadena) && cadena !== 0)
    {
        $('#idPopover').attr("data-content", cadena);
    }
    $('[data-toggle="popover"]').popover('show');
    banderaBuscar = 1;
     obtenerDataBusquedaAuditoria();
     if (colapsa === 1)
        colapsarBuscador();
//    obtenerDataBusquedaAuditoria(cadena);
}

function obtenerDataBusquedaAuditoria()
{
    ax.setAccion("obtenerDataAuditoriaPorCriterios");
    ax.addParamTmp("criterios", valoresBusquedaAuditoria);
    
    $('#datatable').dataTable({
        "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ning\xfAn dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
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
        },
        "processing": true,
        "serverSide": true,
        "bFilter": false,
        "ajax": ax.getAjaxDataTable(),
        "scrollX": true,
        "order": [[0, "desc"]],
        "columns": [
                {"data": "fecha_creacion"},
                {"data": "auditoria_fecha"},
                {"data": "persona_nombre"},
                {"data": "persona_apellidos"},
                {"data": "auditoria_comentario"},
                {data: "auditoria_estado",
                render: function (data, type, row) {
                    if (type === 'display') {
                        return '<a onclick="editarAuditoria('+ row.auditoria_id +')"><b><i class="fa fa-edit" style="color:#E8BA2F;"></i><b></a>&nbsp;\n\n\
                                <a onclick="verDetalleAuditoria('+ row.auditoria_id +')"><b><i class="fa fa-eye"" style="color:b"></i><b></a>&nbsp;\n';
                    //                        return '<button  onClick = "agregarDocumentoPago(' + row.documento_id + ');" name = "btn_' + row.documento_id + '" id="btn_' + row.documento_id + '" class="btn btn-primary" style="border-radius: 0px;" ><i class = "ion-android-add"></i></button>'
                    }
                    return data;
                },
                "orderable": false,
                "class": "alignCenter"
            },
        ],
        destroy: true
    });
    loaderClose();
}

//function onResponseGetDataGridAuditoria(data) {
//
//    if (!isEmptyData(data))
//    {
//        $.each(data, function (index, item) {
//            data[index]["opciones"] = '<a onclick="verDetalleAuditoria(' + item['bien_id'] + ',' + item['organizador_id'] + ',\'' + item['fecha_inicio'] + '\',\'' + item['fecha_fin'] + '\')"><b><i class="fa fa-eye"" style="color:b"></i><b></a>';
//        });
//        $('#datatable').dataTable({
//            "scrollX": true,
//            "order": [[0, "desc"]],
//            "data": data,
//            "columns": [
//                {"data": "organizador_descripcion", "width": "150px"},
//                {"data": "bien_descripcion", "width": "250px"},
////                {"data": "organizador_descripcion", "width": "150px"},
//                {"data": "bien_tipo_descripcion", "width": "120px"},
//                {"data": "unidad_medida_descripcion", "width": "120px"},
//                {"data": "stock", "sClass": "alignRight", "width": "80px"},
//                {"data": "opciones", "sClass": "alignCenter", "width": "50px"}
//            ],
//            "destroy": true
//        });
//    }
//    else
//    {
//        var table = $('#datatable').DataTable();
//        table.clear().draw();
//    }
//}

function loaderBuscarDeuda()
{
    loaderShow();
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscarAuditoria();
    }
    loaderClose();
}

function verDetalleAuditoria(id)
{
    loaderShow();
    ax.setAccion("obtenerDetalleAuditoria");
    ax.addParamTmp("auditoria_id", id);
    ax.consumir();
}

function onResponseDetalleAuditoria(data)
{
    if (!isEmptyData(data))
    {
//        $('[data-toggle="popover"]').popover('hide');
//        var stringTituloStock = '<strong> ' + data[0]['organizador_descripcion'] + ' - ' + data[0]['bien_descripcion'] + '</strong>';

        $('#datatableDetalleAuditoria').dataTable({
            order: [[0, "desc"]],
            "ordering": false,
            "data": data,
            "columns": [
                {"data": "bien_descripcion"},
                {"data": "unidad_medida_descripcion"},
                {"data": "organizador_descripcion"},
                {"data": "valor_sistema", "sClass": "alignRight"},
                {"data": "valor_real", "sClass": "alignRight"},
                {"data": "discrepancia", "sClass": "alignRight"}
            ],
            "destroy": true
        });
//        $('.modal-title').empty();
//        $('.modal-title').append(stringTituloStock);
        $('#modal-detalle-kardex').modal('show');
    }
    else
    {
        var table = $('#datatableDetalleAuditoria').DataTable();
        table.clear().draw();
        mostrarAdvertencia("No se encontro detalles de este bien.")
    }
}

function exportarReporteKardexExcel()
{
    loaderShow();
    cargarDatosBusqueda();
    ax.setAccion("obtenerReporteKardexExcel");
    ax.addParamTmp("criterios", valoresBusquedaAuditoria);
    ax.addParamTmp("tipo", 1);
    ax.consumir();
}

function loaderBuscar()
{
    loaderShow();
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscarAuditoria();
    }
    loaderClose();
}

function colapsarBuscador() {
    if (actualizandoBusquedaAuditoria) {
        actualizandoBusquedaAuditoria = false;
        return;
    }
    if ($('#bg-info').hasClass('in')) {
        $('#bg-info').attr('aria-expanded', "false");
        $('#bg-info').attr('height', "0px");
        $('#bg-info').removeClass('in');
    } else {
        $('#bg-info').attr('aria-expanded', "false");
        $('#bg-info').attr('height', "0px");
        $('#bg-info').addClass('in');
    }
}
var actualizandoBusquedaAuditoria = false;
function actualizarBusqueda()
{
    actualizandoBusquedaAuditoria = true;
    var estadobuscador = $('#bg-info').attr("aria-expanded");
    if (estadobuscador == "false")
    {
        buscarAuditoria(0);
    }
}
function nuevoFormAuditoria()
{
//    VALOR_ID_USUARIO = null;
    commonVars.auditoriaId = 0;
    cargarDiv('#window', 'vistas/com/auditoria/auditoria.php');
}

function editarAuditoria(id) {
    commonVars.auditoriaId = id;
    cargarDiv("#window", "vistas/com/auditoria/auditoria.php");
}