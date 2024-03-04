
$(document).ready(function () {
    ax.setSuccess("exitoProgramacionAtencion");
    var documentoId = document.getElementById("documentoId").value;
    obtenerConfiguracionesIniciales(documentoId);
    cargarSelect2();
    datePiker.iniciarPorClase('fecha');
//    dibujarTablaDetalleCabecera();
    dibujarTablaProgramacionCabecera();
});

function cargarSelect2() {
    $(".select2").select2({
        width: '100%'
    });
}

function cargarPantallaListar() {
    var url = URL_BASE + "vistas/com/programacionAtencion/atencion_listar.php";
    cargarDiv("#window", url);
}

function exitoProgramacionAtencion(response) {
    if (response.status === 'ok') {
        switch (response[PARAM_ACCION_NAME]) {
            case 'obtenerConfiguracionesIniciales':
                onResponseObtenerConfiguracionesIniciales(response.data);
                loaderClose();
                break;
            case 'guardarProgramacionAtencion':
                onResponseGuardarProgramacionAtencion(response.data);
                loaderClose();
                break;
            case 'obtenerDocumento':
                onResponseObtenerDocumento(response.data);
                loaderClose();
                break;
        }
    } else {
        switch (response[PARAM_ACCION_NAME]) {
            case 'guardarProgramacionAtencion':
                loaderClose();
                swal({title: "Stock insuficiente", text: response.message, type: "error", html: true});
                break;
        }
    }
}

function obtenerConfiguracionesIniciales(documentoId) {
    loaderShow();
    ax.setAccion("obtenerConfiguracionesIniciales");
    ax.addParam("documentoId", documentoId);
    ax.consumir();
}

var dataInicial;
function onResponseObtenerConfiguracionesIniciales(data) {
//    console.log(data);
    dataInicial = data;
    var documentoId = document.getElementById('documentoId').value;
    var dataDocumento = data.dataDocumento[0];
    var htmlCab = dataDocumento.documento_tipo_descripcion;
    htmlCab += " <a  onclick='obtenerDocumento(" + documentoId + ")' style='color: black;' title='Visualizar documento'>[" + dataDocumento.serie_numero + "]</a>";
    htmlCab += ' | ' + dataDocumento.moneda_simbolo + ' ' + formatearNumero(dataDocumento.total);
    htmlCab += ' | ' + dataDocumento.persona_nombre;

    $('#cabeceraAtencion').html(htmlCab);

    datex.setNow1('txtFechaProgamada');
    //llenado de combo de producto
    //    select2.cargar('cboProducto', data.dataMovimientoBien, 'movimiento_bien_id', ['bien_codigo', 'bien_descripcion', 'cantidad', 'unidad_medida_descripcion'])
    var htmlCombo = ''
    $("#cboProducto").empty();
    if (!isEmpty(data.dataMovimientoBien)) {
        $.each(data.dataMovimientoBien, function (index, item) {
            htmlCombo += '<option value="' + item.movimiento_bien_id + '">' + item.bien_codigo + ' | ' + item.bien_descripcion + ' | ' + formatearCantidad(item.cantidad) + ' ' + item.unidad_medida_descripcion + '</option>';
        });
        $('#cboProducto').append(htmlCombo);
        select2.asignarValor('cboProducto', data.dataMovimientoBien[0].movimiento_bien_id)
    }
    select2.cargar('cboOrganizador', data.dataOrganizador, 'id', 'descripcion');

    listarMovimientoBien(data.dataMovimientoBien);

//    llenar el detalle
    if (!isEmpty(data.dataPAtencion)) {
        $.each(data.dataPAtencion, function (i, itemDetalle) {
            var objDetalle = {};
            objDetalle.indice = i;
            objDetalle.moviBienId = itemDetalle.movimiento_bien_id;
            objDetalle.cantidad = formatearCantidad(itemDetalle.cantidad_programada);
            objDetalle.fechaProgramada = formatearFechaBDCadena(itemDetalle.fecha_programada);
            objDetalle.estadoId = itemDetalle.patencion_estado;
            objDetalle.estadoDesc = '';
            objDetalle.organizadorId = itemDetalle.organizador_id;
            objDetalle.organizadorDesc = (isEmpty(itemDetalle.organizador_desc) ? '' : itemDetalle.organizador_desc);
            objDetalle.programacionAtencionDetalleId = itemDetalle.patencion_id;
            objDetalle.cantidadAtendida = (isEmpty(itemDetalle.cantidad_atendida) ? 0 : itemDetalle.cantidad_atendida);

            listaProgramacionAtencionDetalle.push(objDetalle);
        });

        var fechaProg = null;
        var estadoProg = null;

        //si es una edicion desde el listado de detalle.
        var patencionDetalleId = document.getElementById("patencionDetalleId").value;
        if (!isEmpty(patencionDetalleId)) {
            var indiceDet;
            $.each(listaProgramacionAtencionDetalle, function (index, item) {
                if (item.programacionAtencionDetalleId == patencionDetalleId) {
                    indiceDet = index;
                }
            });
            fechaProg = listaProgramacionAtencionDetalle[indiceDet].fechaProgramada;
            estadoProg = listaProgramacionAtencionDetalle[indiceDet].estadoId;
        }

        onListarProgramacion(fechaProg, estadoProg);
    }
}

function listarMovimientoBien(dataMovimientoBien) {
    $('#dataTableMovimientoBienDetalle tbody tr').remove();
    var cuerpo = "";
    var ind = 0;

    if (!isEmpty(dataMovimientoBien)) {
        dataMovimientoBien.forEach(function (item) {
            var nuevo = "<a  onclick = 'abrirModalNuevoDetalle(\"" + item.movimiento_bien_id + "\")' >"
                    + "<i class='fa fa-plus-square' style='color:#1ca8dd;'></i></a>&nbsp;&nbsp;&nbsp;";

//            var visualizar = "<a  onclick = 'verDetalle(\"" + ind + "\")' ><i class='fa fa-eye'></i></a>&nbsp;&nbsp;&nbsp;";
            var visualizar = "";

            var atenciones = obtenerCantidadTotalDetalle(item.movimiento_bien_id);
            //N°	Producto	U.Medida	Cantidad	Atenciones	Accione
            cuerpo += "<tr>"
                    + "<td style='text-align:center;'>" + (ind + 1) + "</td>"
                    + "<td style='text-align:left;'>" + item.bien_codigo + " | " + item.bien_descripcion + "</td>"
                    + "<td style='text-align:left;'>" + item.unidad_medida_descripcion + "</td>"
                    + "<td style='text-align:right;'>" + formatearCantidad(item.cantidad) + "</td>"
                    + "<td style='text-align:center;'>" + atenciones + "</td>"
                    + "<td style='text-align:center;'>" + nuevo + visualizar + "</td>"
                    + "</tr>";

            ind++;
        });

        $('#dataTableMovimientoBienDetalle tbody').append(cuerpo);
    }
}

function abrirModalNuevoDetalle(moviBienId) {
    limpiarCamposProgramacionAtencionDetalle();
    onChangeEstado();
    if (isEmpty(moviBienId)) {
        moviBienId = dataInicial.dataMovimientoBien[0].movimiento_bien_id;
    }
    select2.asignarValor('cboProducto', moviBienId);
    if(!isEmpty(fechaProgramadaUltima)){
        $('#txtFechaProgamada').datepicker('setDate', fechaProgramadaUltima);
    }
    $('#modalProgramacionAtencionDetalle').modal('show');
}

//AGREGAR PROGRAMACION PAGO DETALLE

var listaProgramacionAtencionDetalle = [];
var fechaProgramadaUltima='';
function agregarProgramacionAtencionDetalle() {
    var moviBienId = select2.obtenerValor('cboProducto');
    var cantidad = $('#txtCantidad').val();
    var fechaProgramada = $('#txtFechaProgamada').val();
    var estadoId = select2.obtenerValor('cboEstado');
    var estadoDesc = select2.obtenerText('cboEstado');
    var organizadorId = select2.obtenerValor('cboOrganizador');
    var organizadorDesc = select2.obtenerText('cboOrganizador');
    var detalleIndice = $('#detalleIndice').val(); //el indice de edicion        
    var objDetalle = {};//Objeto para el detalle  

    if (validarFormularioProgramacionAtencionDetalle(moviBienId, cantidad, fechaProgramada, estadoId, organizadorId)) {
        if (validarProgramacionAtencionDetalleRepetido(moviBienId, fechaProgramada, organizadorId)) {

            objDetalle.moviBienId = moviBienId;
            objDetalle.cantidad = cantidad;
            objDetalle.fechaProgramada = fechaProgramada;
            objDetalle.estadoId = estadoId;
            objDetalle.estadoDesc = estadoDesc;
            objDetalle.organizadorId = organizadorId;
            objDetalle.organizadorDesc = organizadorDesc;
            
            fechaProgramadaUltima=fechaProgramada;

            if (detalleIndice != '') {// validamos si es edicion - programacionAtencionDetalleId -> id de tabla detalle                                             
                objDetalle.indice = detalleIndice;
                objDetalle.programacionAtencionDetalleId = listaProgramacionAtencionDetalle[detalleIndice].programacionAtencionDetalleId;
                listaProgramacionAtencionDetalle[detalleIndice] = objDetalle;
            } else {
                objDetalle.indice = listaProgramacionAtencionDetalle.length;
                objDetalle.programacionAtencionDetalleId = null;
                listaProgramacionAtencionDetalle.push(objDetalle);
            }

//          console.log(listaProgramacionAtencionDetalle);
//          console.log(listaProgramacionAtencionDetalleEliminado);
            onListarProgramacion(fechaProgramada, estadoId);
            limpiarCamposProgramacionAtencionDetalle();

            $('#modalProgramacionAtencionDetalle').modal('hide');
        }
    }
}

function validarFormularioProgramacionAtencionDetalle(moviBienId, cantidad, fechaProgramada, estadoId, organizadorId) {
    var bandera = true;

    if (isEmpty(moviBienId)) {
        mostrarAdvertencia('Seleccione producto.');
        bandera = false;
    }

    if (cantidad * 1 <= 0 || isEmpty(cantidad)) {
        mostrarAdvertencia('Cantidad tiene que ser positiva');
        bandera = false;
    }

    if (isEmpty(fechaProgramada)) {
        mostrarAdvertencia('Seleccione fecha programada');
        bandera = false;
    }

    if (isEmpty(estadoId)) {
        mostrarAdvertencia('Seleccione estado');
        bandera = false;
    } else if (estadoId == 3) {
        //valido que la fecha liberada tiene que ser menor o igual a fecha actual
        if (validateFechaMayorQue(fechaProgramada, datex.getNow1()) == 0) {
            mostrarAdvertencia('La fecha programada de liberación no puede ser mayor a la fecha actual');
            bandera = false;
        }
    }

    //validar que la suma de las cantidades de atenciones sea menor a la cantidad de movimiento bien        
    var cantidadTotalDet = obtenerCantidadTotalDetalle(moviBienId);
    //EN CASO SEA EDICION
    var detalleIndice = $('#detalleIndice').val();
    if (!isEmpty(detalleIndice)) {
        cantidadTotalDet = cantidadTotalDet - listaProgramacionAtencionDetalle[detalleIndice].cantidad * 1;
    }
    var moviBien = obtenerMovimientoBienXId(moviBienId);
    if ((cantidadTotalDet + cantidad * 1) > moviBien.cantidad * 1) {
        mostrarAdvertencia('La suma de las cantidades de atención no puede ser mayor a la cantidad solicitada (' + formatearCantidad(moviBien.cantidad) + ')');
        bandera = false;
    }

    return bandera;
}

function obtenerCantidadTotalDetalle(moviBienId) {
    var ctotal = 0;

    $.each(listaProgramacionAtencionDetalle, function (index, item) {
        if (item.moviBienId == moviBienId) {
            ctotal += item.cantidad * 1;
        }
    });

    return ctotal;
}

function limpiarCamposProgramacionAtencionDetalle() {
    $('#txtCantidad').val('');
    select2.asignarValor('cboEstado', 1);
    datex.setNow1('txtFechaProgamada');
    $('#detalleIndice').val('');
    select2.asignarValor('cboOrganizador', null);
}

function buscarProgramacionAtencionDetalle(moviBienId, fechaProgramada, organizadorId) {
    var ind = -1;
    var organizadorIdTemp = null;

    if (!isEmpty(listaProgramacionAtencionDetalle)) {
        $.each(listaProgramacionAtencionDetalle, function (i, item) {
            organizadorIdTemp = item.organizadorId;
            if (item.moviBienId == moviBienId && item.fechaProgramada == fechaProgramada) {
                if ((isEmpty(item.organizadorId) && !isEmpty(organizadorId)) || (isEmpty(organizadorId) && !isEmpty(item.organizadorId))) {
                    organizadorIdTemp = organizadorId;
                }
            }
            if (item.moviBienId == moviBienId && item.fechaProgramada == fechaProgramada && organizadorIdTemp == organizadorId) {
                ind = i;
                return false;
            }
        });
    }
    return ind;
}

function validarProgramacionAtencionDetalleRepetido(moviBienId, fechaProgramada, organizadorId) {
    var valido = true;
    var detalleIndice = $('#detalleIndice').val();

    if (detalleIndice != '') {
        //alert('igual');
        var indice = buscarProgramacionAtencionDetalle(moviBienId, fechaProgramada, organizadorId);
        if (indice != detalleIndice && indice != -1) {
            mostrarAdvertencia("La atención ya ha sido agregada");
            valido = false;
        } else {
            valido = true;
        }
    } else {
        //alert('diferente');
        var indice = buscarProgramacionAtencionDetalle(moviBienId, fechaProgramada, organizadorId);
        if (indice > -1) {
            mostrarAdvertencia("La atención ya ha sido agregada");
            valido = false;
        }
    }
    return valido;
}


function obtenerListaDetalleXIndice(indice) {
    var itemProg = listaDataProgramacion[indice];
    var data = [];

    $.each(listaProgramacionAtencionDetalle, function (index, item) {
        if (item.fechaProgramada == itemProg.fechaProgramada && item.estadoId == itemProg.estadoId) {
            data.push(item);
        }
    });

    return data;
}


function onListarProgramacionAtencionDetalle(indiceFila) {
    var data = obtenerListaDetalleXIndice(indiceFila);

    var html = '<table id="dataTableProgramacionAtencionDetalle_' + indiceFila + '" class="table table-striped">';
//    html += '                        <thead>';
//    html += '                            <tr>';
//    html += '                                <th style="text-align:center">N°</th>';
//    html += '                                <th style="text-align:center">Producto</th>';
//    html += '                                <th style="text-align:center">U.Medida</th>';
//    html += '                                <th style="text-align:center">Cant. Prog.</th>';
////    html += '                                <th style="text-align:center">Fecha Prog.</th>';
//    html += '                                <th style="text-align:center">Organizador</th>';
////    html += '                                <th style="text-align:center">Estado</th>';
//    html += '                                <th style="text-align:center">Acciones</th>';
//    html += '                            </tr>';
//    html += '                        </thead>';
    html += '                        <tbody>';

    var ind = 0;
    var patencionDetalleId = document.getElementById("patencionDetalleId").value;
    var colorFila = '';

    if (!isEmpty(data)) {
        data.forEach(function (item) {
            ind = item.indice;

            colorFila = '';
            if (item.programacionAtencionDetalleId == patencionDetalleId) {
                colorFila = " style='background: #c2ffae;' ";
            }
            var eliminar = "<a  onclick = 'eliminarProgramacionAtencionDetalle(\"" + ind + "\")' title='Eliminar detalle' >"
                    + "<i class='fa fa-trash-o' style='color:#cb2a2a;'></i></a>";

            var editar = "<a  onclick = 'editarProgramacionAtencionDetalle(\"" + ind + "\")' title='Editar detalle'>"
                    + "<i class='fa fa-edit' style='color:#E8BA2F;'></i></a>&nbsp;&nbsp;&nbsp;";

            if (item.estadoId == 5 || item.estadoId == 6) {
                eliminar = '';
                editar = '';
            }

            //obtener el icono del estado
//            var iconoEstado = obtenerIconoEstado(ind, item.estadoId);

            //N°	Producto	U.Medida	Cant. Prog.	Fecha Prog.	Estado	Acciones
            var num = obtenerNumeroCorrelativoDetalle(ind, item.moviBienId);
            var dataMoviBien = obtenerMovimientoBienXId(item.moviBienId);
            html += "<tr " + colorFila + ">"
                    + "<td style='text-align:center;'>" + num + "</td>"
                    + "<td style='text-align:left;'>" + dataMoviBien.bien_codigo + ' | ' + dataMoviBien.bien_descripcion + "</td>"
                    + "<td style='text-align:right;'>" + formatearCantidad(item.cantidad) + "</td>"
                    + "<td style='text-align:left;'>" + dataMoviBien.unidad_medida_descripcion + "</td>"
//                    + "<td style='text-align:center;'>" + item.fechaProgramada + "</td>"
                    + "<td style='text-align:center;'>" + (isEmpty(item.organizadorDesc) ? "" : item.organizadorDesc) + "</td>";
//                    + "<td style='text-align:center;'>" + iconoEstado + "</td>"
            if (item.estadoId == 5) {
                html += "<td style='text-align:center;'>Cantidad atendida " + formatearCantidad(item.cantidadAtendida) + "</td>"
            } else {
                html += "<td style='text-align:center;'>" + editar + eliminar + "</td>";
            }
            html += "</tr>";
        });
    }

    html += '                        </tbody>';
    html += '                    </table>';

    return html;
//        $('#dataTableProgramacionAtencionDetalle').DataTable({
//            "scrollX": true,
//            "paging": false,
//            "info": false,
//            "filter": false,
//            "ordering": true,
//            "autoWidth": true
//        });
}

function dibujarTablaProgramacionCabecera() {
    var html = '<table id="dataTableProgramacion" class="table table-striped table-bordered">';
    html += '                        <thead>';
    html += '                            <tr>';
    html += '                                <th style="text-align:center"></th>';
    html += '                                <th style="text-align:center">Fecha programada</th>';
    html += '                                <th style="text-align:center">Estado</th>';
    html += '                                <th style="text-align:center">Acciones</th>';
    html += '                            </tr>';
    html += '                        </thead>';
    html += '                        <tbody>';
    html += '                        </tbody>';
    html += '                    </table>';

    $('#divTablaProgramacion').append(html);
    modificarAnchoTabla('dataTableProgramacion');
}

var listaDataProgramacion = [];
function obtenerDataProgramacion() {
    listaDataProgramacion = [];

    $.each(listaProgramacionAtencionDetalle, function (index, item) {
        //valido que el item{fecha, estado} no este en el array
        if (validarProgramacionDuplicado(item.fechaProgramada, item.estadoId)) {
            listaDataProgramacion.push({fechaProgramada: item.fechaProgramada, estadoId: item.estadoId});
        }
    });
}

function validarProgramacionDuplicado(fechaProgramada, estadoId) {
    var valido = true;
    var indice = -1;
    if (!isEmpty(listaDataProgramacion)) {
        $.each(listaDataProgramacion, function (i, item) {
            if (item.fechaProgramada == fechaProgramada && item.estadoId == estadoId) {
                indice = i;
            }
        });
    }

    if (indice != -1) {
        valido = false;
    } else {
        valido = true;
    }
    return valido;
}

function onListarProgramacion(fechaProg, estadoProg) {
    $('#divTablaProgramacion').empty();
    dibujarTablaProgramacionCabecera();
    listarMovimientoBien(dataInicial.dataMovimientoBien);//PARA EL LISTADO DE MOVIMIENTO DE DOCUMENTO

    obtenerDataProgramacion();
    var data = listaDataProgramacion;

    $('#dataTableProgramacion tbody tr').remove();
    var cuerpo = "";
    var ind = 0;


    if (!isEmpty(data)) {
        data.forEach(function (item) {
            var eliminar = "<a  onclick = 'confirmarEliminarProgramacion(\"" + ind + "\")' title='Eliminar programación' >"
                    + "<i class='fa fa-trash-o' style='color:#cb2a2a;'></i></a>";
            var iconoEstado = obtenerIconoEstado(ind, item.estadoId);

            if (item.estadoId == 5 || item.estadoId == 6) {
                eliminar = '';
            }

            cuerpo += "<tr id='trDetalle" + ind + "'>"
                    + "<td class='details-control'></td>"
                    + "<td style='text-align:center;'><p style='display: none;'>"
                    + datex.parserControlador(item.fechaProgramada) + "</p>"
                    + obtenerHtmlFechaProgramada(ind, item.fechaProgramada) + "</td>"
                    + "<td style='text-align:center;'>" + iconoEstado + "</td>"
                    + "<td style='text-align:center;'>" + eliminar
                    + "</td>"
                    + "</tr>";

            ind++;
        });

        $('#dataTableProgramacion tbody').append(cuerpo);

        var table = $('#dataTableProgramacion').DataTable({
            "scrollX": true,
            "paging": false,
            "info": false,
            "filter": false,
            "ordering": true,
            "order": [1, 'asc'],
            "autoWidth": true
        });

        //funcion de clic en boton de tabla
        $('#dataTableProgramacion tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = table.row(tr);
            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            } else {
                // Open this row
                row.child(onListarProgramacionAtencionDetalle(row.index())).show();
                tr.addClass('shown');
            }
        });

        //MOSTRAR LOS HIJOS DEL RESPECTIVO PADRE
        if (!isEmpty(fechaProg) && !isEmpty(estadoProg)) {
            mostrarSubDetalle(table, fechaProg, estadoProg);
        }
    }
}

function mostrarSubDetalle(table, fechaProg, estadoProg) {
    var indiceProg = -1;
    $.each(listaDataProgramacion, function (index, item) {
        if (item.fechaProgramada == fechaProg && item.estadoId == estadoProg) {
            indiceProg = index;
        }
    });

    if (indiceProg != -1) {
        var tr = $('#trDetalle' + indiceProg);
        var row = table.row(tr);
        row.child(onListarProgramacionAtencionDetalle(row.index())).show();
        tr.addClass('shown');
    }
}

function obtenerHtmlFechaProgramada(ind, fechaProgramada) {
    var html = '';
    if (listaDataProgramacion[ind]['estadoId'] == 5 || listaDataProgramacion[ind]['estadoId'] == 6) {
        html = '<div class="form-group col-md-12">' +
                '<div class="col-md-12" style="padding-right: 0px;">' +
                fechaProgramada
        '</div>' +
                '</div>';
    } else {
        html = '<div class="form-group col-md-12">' +
                '<div class="col-md-12" style="padding-right: 0px;">' +
                '<a title="Modificar fecha programada" style="color: #0000ff"><p id="pFechaProgramada_' + ind + '" onclick="habilitarContenedorFechaProgramada(' + ind + ')">' + fechaProgramada + '</p></a>' +
                '<div id="contenedorFechaProgramada_' + ind + '"  class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12" style="display: none;">' +
                '<div class="input-group col-lg-12 col-md-12 col-sm-12 col-xs-12">' +
                '<input type="text" class="form-control fecha" placeholder="dd/mm/yyyy" id="txtFechaProgramada_' + ind + '">' +
                '<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>' +
                '<span class="input-group-addon btn-success" title="Actualizar fecha programada" onclick="actualizarFechaProgramada(' + ind + ')"><a><i class="ion-android-checkmark" style="color: white"></i></a></span>' +
                '<span class="input-group-addon btn-danger" title="Cancelar" onclick="habilitarFechaProgramada(' + ind + ')"><a><i class="ion-android-close" style="color: white"></i></a></span>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>';
    }
    return html;
}

function habilitarContenedorFechaProgramada(indice) {
    datePiker.iniciarPorElemento('txtFechaProgramada_' + indice);
    $('#txtFechaProgramada_' + indice).datepicker('setDate', $('#pFechaProgramada_' + indice).html());
    $('#contenedorFechaProgramada_' + indice).show();
    $('#pFechaProgramada_' + indice).hide();
}

function actualizarFechaProgramada(indiceProg) {
//    $('#pFechaProgramada_' + indice).html($('#txtFechaProgramada_' + indice).val());
//    habilitarFechaProgramada(indice);
    var nuevaFecha = $('#txtFechaProgramada_' + indiceProg).val();
    var itemProg = listaDataProgramacion[indiceProg];

    if (listaDataProgramacion[indiceProg]['estadoId'] == 3 && validateFechaMayorQue(nuevaFecha, datex.getNow1()) == 0) {
        mostrarAdvertencia('La fecha programada de liberación no puede ser mayor a la fecha actual');
        return;
    }

    $.each(listaProgramacionAtencionDetalle, function (indice, item) {
        if (item.fechaProgramada == itemProg.fechaProgramada && item.estadoId == itemProg.estadoId) {
            listaProgramacionAtencionDetalle[indice].fechaProgramada = nuevaFecha;
        }
    });

    onListarProgramacion();
}

function habilitarFechaProgramada(indice) {
    $('#pFechaProgramada_' + indice).show();
    $('#contenedorFechaProgramada_' + indice).hide();
}

function obtenerNumeroCorrelativoDetalle(indice, moviBienId) {
    var numProd;
    $.each(dataInicial.dataMovimientoBien, function (index, item) {
        if (item.movimiento_bien_id == moviBienId) {
            numProd = index + 1;
        }
    });

    var ind = 0;

    $.each(listaProgramacionAtencionDetalle, function (index, item) {
        if (item.moviBienId == moviBienId && index <= indice) {
            ind++;
        }
    });

    return numProd + '.' + ind;
}

function obtenerIconoEstado(indice, estadoId) {
    var html = '';
    switch (estadoId * 1) {
        case 1:
            html = html + "<a  onclick='confirmarActualizarEstadoPAtencionDetalle(" + indice + ",3)'><i class='fa fa-server' style='color:#1ca8dd;' title='Actualizar a liberado'></i></a>&nbsp;&nbsp;&nbsp;";
            break;
        case 3:
            html = html + "<a  onclick='confirmarActualizarEstadoPAtencionDetalle(" + indice + ",1)'><i class='fa fa-unlock' style='color:green;' title='Actualizar a programado'></i></a>&nbsp;";
            break;
        case 4:
            html = html + "<a  onclick='confirmarActualizarEstadoPAtencionDetalle(" + indice + ",3)'><i class='fa fa-lock' style='color:red;' title='Actualizar a liberado'></i></a>&nbsp;&nbsp;&nbsp;";
            break;
        case 5:
            html = html + "<i class='fa fa-th-large' style='color:orange;' title='Atendido parcialmente'></i>&nbsp;&nbsp;&nbsp;";
            break;
        case 6:
            html = html + "<i class='fa fa-th-large' style='color:green;' title='Atendido totalmente'></i>&nbsp;&nbsp;&nbsp;";
            break;
        default:
            html = html + "<i class='ion-close-circled' style='color:red;' title='Eliminado'></i>";
    }
    return html;
}

function obtenerMovimientoBienXId(moviBienId) {
    var dataMoviBien;
    $.each(dataInicial.dataMovimientoBien, function (index, item) {
        if (item.movimiento_bien_id == moviBienId) {
            dataMoviBien = item;
        }
    });

    return dataMoviBien;
}

function editarProgramacionAtencionDetalle(indice) {
    $('#detalleIndice').val(indice);
    $('#modalProgramacionAtencionDetalle').modal('show');

    var objDetalle = listaProgramacionAtencionDetalle[indice];
    select2.asignarValor('cboProducto', objDetalle.moviBienId);
    $('#txtCantidad').val(formatearCantidad(objDetalle.cantidad));
    $('#txtFechaProgamada').datepicker('setDate', objDetalle.fechaProgramada);
    select2.asignarValor('cboEstado', objDetalle.estadoId);
    onChangeEstado();
    if (objDetalle.estadoId != 1) {
        select2.asignarValor('cboOrganizador', objDetalle.organizadorId);
    }
}

var listaProgramacionAtencionDetalleEliminado = [];

function eliminarProgramacionAtencionDetalle(indice) {
    var fechaProg = listaProgramacionAtencionDetalle[indice].fechaProgramada;
    var estadoProg = listaProgramacionAtencionDetalle[indice].estadoId;

    if (!isEmpty(listaProgramacionAtencionDetalle[indice].programacionAtencionDetalleId)) {
        listaProgramacionAtencionDetalleEliminado.push(listaProgramacionAtencionDetalle[indice].programacionAtencionDetalleId);
    }

    listaProgramacionAtencionDetalle.splice(indice, 1);

    var detalleCopia = listaProgramacionAtencionDetalle.slice();
    listaProgramacionAtencionDetalle = [];

    if (!isEmpty(detalleCopia)) {
        $.each(detalleCopia, function (i, item) {
            item.indice = i;
            listaProgramacionAtencionDetalle.push(item);
        });
    }

    onListarProgramacion(fechaProg, estadoProg);
}

//------------ FIN DETALLE -----------------

function guardarProgramacionAtencion() {
    if (validarFormulario()) {
        loaderShow();
        ax.setAccion("guardarProgramacionAtencion");
        ax.addParamTmp("listaProgramacionAtencionDetalle", listaProgramacionAtencionDetalle);
        ax.addParamTmp("listaProgramacionAtencionDetalleEliminado", listaProgramacionAtencionDetalleEliminado);
        ax.consumir();
    }
}

function validarFormulario() {
    var bandera = true;

    if (isEmpty(listaProgramacionAtencionDetalle)) {
        mostrarAdvertencia("Ingrese el detalle de la atención");
        bandera = false;
    } else {

        //validamos que las cantidades de cada movimiento bien sea igual a la suma de las atenciones        
        $.each(dataInicial.dataMovimientoBien, function (index, item) {
            var cantDet = obtenerCantidadTotalDetalle(item.movimiento_bien_id);
            if (cantDet != item.cantidad * 1) {
                mostrarAdvertencia('Para: ' + item.bien_codigo + ' | ' + item.bien_descripcion + ' | ' + item.unidad_medida_descripcion + ' falta completar la cantidad solicitada (' + cantDet + '/' + formatearCantidad(item.cantidad) + ')');
                bandera = false;
            }
        });

    }
    return bandera;
}

function onResponseGuardarProgramacionAtencion(data) {
    if (data[0]['vout_exito'] == 1) {
        mostrarOk(data[0]['vout_mensaje']);
        cargarPantallaListar();
    } else {
        mostrarAdvertencia((data[0]['vout_mensaje']));
    }
}

//----------------------- VISUALIZAR DOCUMENTO -------------------------------
function obtenerDocumento(documentoId) {
    loaderShow();
    ax.setAccion("obtenerDocumento");
    ax.addParamTmp("documentoId", documentoId);
    ax.consumir();
}

function onResponseObtenerDocumento(data) {
    $('[data-toggle="popover"]').popover('hide');
    cargarDataDocumento(data.dataDocumento);
    cargarDataComentarioDocumento(data.comentarioDocumento);

    if (!isEmpty(data.detalleDocumento)) {
        cargarDetalleDocumento(data.detalleDocumento, data.dataMovimientoTipoColumna, data.organizador);
    } else {
        $('#formularioCopiaDetalle').hide();
    }

    $('#modalDetalleDocumento').modal('show');
}

function cargarDataComentarioDocumento(data) {
    $('#txtComentario').val(data[0]['comentario_documemto']);
    $('#txtDescripcion').val(data[0]['descripcion_documemto']);
}

function appendFormDetalle(html) {
    $("#formularioDetalleDocumento").append(html);
}

function cargarDataDocumento(data) {
    $("#formularioDetalleDocumento").empty();
    //$("#formularioDetalleDocumento").css("height", 75 * data.length);

    if (!isEmpty(data)) {
        $('#nombreDocumentoTipo').html(data[0]['nombre_documento']);
        // Mostraremos la data en filas de dos columnas
        $.each(data, function (index, item) {

            appendFormDetalle('</div>');
            appendFormDetalle('<div class="row">');

            var html = '<div class="form-group col-md-12"><div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">' +
                    '<label>' + item.descripcion + '</label>' +
                    '</div><div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">';

            var valor = quitarNULL(item.valor);

            if (!isEmpty(valor))
            {
                switch (parseInt(item.tipo)) {
                    case 1:
                        valor = formatearCantidad(valor);
                        break;
                    case 3:
                        valor = fechaArmada(valor);
                        break;
                    case 9:
                    case 10:
                    case 11:
                        valor = fechaArmada(valor);
                        break;
                    case 14:
                    case 15:
                    case 16:
                    case 19:
                        valor = formatearNumero(valor);
                        break;
                }
            }

            html += '' + valor + '';

            html += '</div></div>';
            appendFormDetalle(html);
        });
        appendFormDetalle('</div>');
    }
}

function fechaArmada(valor) {
    var fecha = separarFecha(valor);
    return fecha.dia + "/" + fecha.mes + "/" + fecha.anio;
}

var movimientoTipoColumna;
function cargarDetalleDocumento(data, dataMovimientoTipoColumna, dataOrganizador) {
    movimientoTipoColumna = dataMovimientoTipoColumna;
    if (!isEmptyData(data)) {
        $('#formularioCopiaDetalle').show();

        $.each(data, function (index, item) {
            data[index]["importe"] = formatearNumero(data[index]["cantidad"] * data[index]["valor_monetario"]);
            data[index]["cantidad"] = formatearCantidad(data[index]["cantidad"]);
            data[index]["valor_monetario"] = formatearNumero(data[index]["valor_monetario"]);
        });

        //CABECERA DETALLE
        var tHeadDetalle = $('#theadDetalle');
        tHeadDetalle.empty();

        var html = '';
        html += "<tr>";
//        if(existeColumnaCodigo(15)){
        if (!isEmpty(dataOrganizador)) {
            html += "<th style='text-align:center;'>Organizador</th>";
        }
        html += "<th style='text-align:center;'>Cantidad</th>";
        html += "<th style='text-align:center;'>Unidad de medida</th>";
        html += "<th style='text-align:center;'>Producto</th> ";
        if (existeColumnaCodigo(5)) {
            html += "<th style='text-align:center;'>Precio Unitario</th>";
            html += "<th style='text-align:center;'>Total</th>";
        }
        html += "</tr>";
        tHeadDetalle.append(html);


        //CUERPO DETALLE
        var tBodyDetalle = $('#tbodyDetalle');
        tBodyDetalle.empty();

        html = '';
        $.each(data, function (index, item) {
            html += "<tr>";
//            if(existeColumnaCodigo(15)){
            if (!isEmpty(dataOrganizador)) {
                html += "<td>" + item.organizador_descripcion + "</td>";
            }
            html += "<td style='text-align:right;'>" + item.cantidad + "</td>";
            html += "<td>" + item.unidad_medida_descripcion + "</td>";
            html += "<td>" + item.bien_descripcion + "</td> ";
            if (existeColumnaCodigo(5)) {
                html += "<td style='text-align:right;'>" + item.valor_monetario + "</td>";
                html += "<td style='text-align:right;'>" + item.importe + "</td>";
            }
            html += "</tr>";
        });

        tBodyDetalle.append(html);
    } else {
        var table = $('#datatable2').DataTable();
        table.clear().draw();
    }
}

function existeColumnaCodigo(codigo) {
    var dataColumna = movimientoTipoColumna;

    var existe = false;
    if (!isEmpty(dataColumna)) {
        $.each(dataColumna, function (index, item) {
            if (parseInt(item.codigo) === parseInt(codigo)) {
                existe = true;
                return false;
            }
        });
    }

    return existe;
}
//-------------------- FIN VISUALIZAR DOCUMENTO ----------------------------
function confirmarActualizarEstadoPAtencionDetalle(indiceProg, nuevoEstado) {
    var estadoDesc = '';
    if (nuevoEstado == 1) {
        estadoDesc = 'programado';
    } else {
        estadoDesc = 'liberado';
    }

    swal({
        title: "¿Está seguro que desea actualizar a " + estadoDesc + "?",
        text: "",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#33b86c",
        confirmButtonText: "Si, actualizar!",
        cancelButtonColor: '#d33',
        cancelButtonText: "No, cancelar!",
        closeOnConfirm: true,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            if (nuevoEstado == 3 && validateFechaMayorQue(listaDataProgramacion[indiceProg]['fechaProgramada'], datex.getNow1()) == 0) {
                mostrarAdvertencia('La fecha programada de liberación no puede ser mayor a la fecha actual');
            } else {
                actualizarEstadoPAtencionDetalle(indiceProg, nuevoEstado);
            }
        }
    });
}

function actualizarEstadoPAtencionDetalle(indiceProg, nuevoEstado) {
    var itemProg = listaDataProgramacion[indiceProg];

    $.each(listaProgramacionAtencionDetalle, function (indice, item) {
//        breakFunction();
        if (item.fechaProgramada == itemProg.fechaProgramada && item.estadoId == itemProg.estadoId) {
            listaProgramacionAtencionDetalle[indice].estadoId = nuevoEstado;

            //ORGANIZADOR 
            if (nuevoEstado == 1) {
                listaProgramacionAtencionDetalle[indice].organizadorId = null;
                listaProgramacionAtencionDetalle[indice].organizadorDesc = null;
            }else{
                if(isEmpty(listaProgramacionAtencionDetalle[indice].organizadorId)){
                    listaProgramacionAtencionDetalle[indice].organizadorId=64;                   
                    
                    select2.asignarValor('cboOrganizador', 64);
                    listaProgramacionAtencionDetalle[indice].organizadorDesc = select2.obtenerText('cboOrganizador');
                }                
            }
        }
    });

    onListarProgramacion();
}

$('#txtFechaProgamada').datepicker({
    isRTL: false,
    format: 'dd/mm/yyyy',
    autoclose: true,
    language: 'es'
}).on('changeDate', function (ev) {
    $('#txtDias').val('');
});

function onChangeEstado() {
    var estadoId = select2.obtenerValor('cboEstado');

    if (estadoId != 1) {
        $('#contenedorOrganizador').show();
        select2.asignarValor('cboOrganizador', 64);
    } else {
        $('#contenedorOrganizador').hide();
        select2.asignarValor('cboOrganizador', null);
    }
}

function confirmarEliminarProgramacion(indice) {
    swal({
        title: "¿Está seguro que desea eliminar la programación?",
        text: "",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#33b86c",
        confirmButtonText: "Si, eliminar!",
        cancelButtonColor: '#d33',
        cancelButtonText: "No, cancelar!",
        closeOnConfirm: true,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            eliminarProgramacion(indice);
        }
    });
}

function eliminarProgramacion(indice) {
    var itemProg = listaDataProgramacion[indice];

    var detalleCopia2 = listaProgramacionAtencionDetalle.slice();
    $.each(detalleCopia2, function (index, item) {
//        breakFunction();
        if (item.fechaProgramada == itemProg.fechaProgramada && item.estadoId == itemProg.estadoId) {
            //DETALLE  A ELIMINAR
            if (!isEmpty(detalleCopia2[index].programacionAtencionDetalleId)) {
                listaProgramacionAtencionDetalleEliminado.push(detalleCopia2[index].programacionAtencionDetalleId);
            }

            //HALLANDO EL INDICE A ELIMINAR
            var indexTemp = -1;
            $.each(listaProgramacionAtencionDetalle, function (indexDet, itemDet) {
                if (itemDet.indice == index) {
                    indexTemp = indexDet;
                }
            });
            listaProgramacionAtencionDetalle.splice(indexTemp, 1);
        }
    });

    var detalleCopia = listaProgramacionAtencionDetalle.slice();
    listaProgramacionAtencionDetalle = [];

    if (!isEmpty(detalleCopia)) {
        $.each(detalleCopia, function (i, item) {
            item.indice = i;
            listaProgramacionAtencionDetalle.push(item);
        });
    }

    onListarProgramacion();
}