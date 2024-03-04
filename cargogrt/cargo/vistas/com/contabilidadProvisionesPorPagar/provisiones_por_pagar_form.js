
$(document).ready(function () {
    loaderClose();
    select2.iniciar();
    datePiker.iniciarPorClase('fecha');

});

function abrirModal(nombreModal) {
    $('#' + nombreModal).modal('show');
}

function cerrarModal(nombreModal) {
    $('#' + nombreModal).modal('hide');
}

function cargarPantallaListar(){
    cargarDiv('#window', 'vistas/com/contabilidadProvisionesPorPagar/provisiones_por_pagar_listar.php', "Nuevo ");
}