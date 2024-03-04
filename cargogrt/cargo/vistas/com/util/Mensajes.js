/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
var MENSAJE_ERROR = 'error';
var MENSAJE_WARNING = 'warning';
var MENSAJE_INFORMATION = 'info';
var MENSAJE_OK = 'success';

var MENSAJE_CALLBACK_ONSHOW = 'onShow';
var MENSAJE_CALLBACK_ONAFFTERSHOW = 'afterShow';
var MENSAJE_CALLBACK_ONCLOSECLICK = 'onCloseClick';
var MENSAJE_CALLBACK_ONCLOSED = 'onClose';

function mostrarError(mensaje){
    mostrarMensajeNoty("Error", mensaje, MENSAJE_ERROR);
}
function mostrarAdvertencia(mensaje){
    mostrarMensajeNoty("Validación", mensaje, MENSAJE_WARNING);
}
function mostrarInformacion(mensaje){
    mostrarMensajeNoty("Información", mensaje, MENSAJE_INFORMATION);
}
function mostrarOk(mensaje){
    mostrarMensajeNoty("Éxito", mensaje, MENSAJE_OK);
}
function mostrarMensajeNoty(titulo, mensaje, tipo, callback, callback_tipo, ajaxp, modal){
    if (isEmpty(tipo)) tipo = MENSAJE_OK;
    if (isEmpty(modal)) modal = true;
    var objMensaje = new Object();
    $.Notification.autoHideNotify(tipo, 'top right', titulo, mensaje);

    if (!isEmpty(callback)){
        setTimeout(2000, callback());
    }else if (!isEmpty(ajaxp)){
        objMensaje.callback = {onShow: ajaxp.eventoMensajeOnShow,
                                afterShow: ajaxp.eventoMensajeAfterShow,
                                onCloseClick: ajaxp.eventoMensajeOnCloseClick,
                                onClose: ajaxp.eventoMensajeOnClose};
    }
}

