var banderaBuscar = 0;
var estadoTooltip = 0;


function iniciarDataPicker()
{
    $('.fecha').datepicker({
        isRTL: false,
        format: 'dd/mm/yyyy',
        autoclose: true,
        language: 'es'
    });
}

function objetoFecha(inicio, fin)
{
    var fecha = {inicio: inicio,
        fin: fin};

    return fecha;
}

function buscar()
{
    loaderShow();
    var cadena;
    cadena = obtenerDatosBusqueda();
    getDataTable();
    if (!isEmpty(cadena) && cadena !== 0)
    {
        $('#idPopover').attr("data-content", cadena);
    }
    $('[data-toggle="popover"]').popover('show');
    banderaBuscar = 1;
}

function cerrarPopover()
{
    if (banderaBuscar == 1)
    {
        if (estadoTooltip == 1)
        {
            $('[data-toggle="popover"]').popover('hide');
        }
        else
        {
            $('[data-toggle="popover"]').popover('show');
        }
    }
    else
    {
        $('[data-toggle="popover"]').popover('hide');
    }
    estadoTooltip = (estadoTooltip == 0) ? 1 : 0;
}

function negrita(cadena)
{
    return "<b>" + cadena + "</b>";
}



Number.prototype.formatMoney = function (c, d, t) {
    var n = this,
            c = isNaN(c = Math.abs(c)) ? 2 : c,
            d = d == undefined ? "." : d,
            t = t == undefined ? "," : t,
            s = n < 0 ? "-" : "",
            i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
            j = (j = i.length) > 3 ? j % 3 : 0;
    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};

