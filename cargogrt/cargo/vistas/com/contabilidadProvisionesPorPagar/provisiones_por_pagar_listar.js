$(document).ready(function () {
    cargarComponetentes();
    cambiarAnchoBusquedaDesplegable();
    $('#cboEstadoPPago').select2({
        minimumResultsForSearch: -1
    });
    $('#cboMoneda2').select2({
        minimumResultsForSearch: -1
    });
    loaderClose(); 
    select2.asignarValor('cboMoneda',null);
});

function cargarComponetentes() {
    cargarTitulo("titulo", "");
    select2.iniciar();
    datePiker.iniciarPorClase('fecha');
}

$('.dropdown-menu').click(function (e) {
    if (e.target.id != "btnBusqueda" && e.delegateTarget.id != "listaEmpresa") {
        e.stopPropagation();
    }
});

function abrirModal(nombreModal) {
    $('#' + nombreModal).modal('show');
}

function cerrarModal(nombreModal) {
    $('#' + nombreModal).modal('hide');
}

function verProvision(){    
    cargarDiv('#window', 'vistas/com/contabilidadProvisionesPorPagar/provisiones_por_pagar_form.php', "Nuevo ");
}


function cambiarAnchoBusquedaDesplegable() {
    var ancho = $("#divBuscador").width();
    $("#ulBuscadorDesplegable").width((ancho - 5) + "px");
}