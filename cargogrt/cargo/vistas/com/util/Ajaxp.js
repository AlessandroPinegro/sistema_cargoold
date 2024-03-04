/* 
 * @author: Christopher Heredia
 * Clase llamado al controlador
 */

Ajaxp.prototype = new EventManagerClass();
//Constructor
function Ajaxp(url, method, dataType, success, param) {
    // - Inicializamos el padre.
    EventManagerClass.call(this);

    //  Atributos de la clase
    var _url = isEmpty(url) ? URL_EXECUTECONTROLLER : url;
    var _param = isEmpty(param) ? new Object() : param;
    var _method = isEmpty(method) ? 'POST' : method;
    var _dataType = isEmpty(dataType) ? 'JSON' : dataType;
    var _success = success;
    var _ins = this;

    var _param_tmp = new Object();
    var _stop = false;
    var _show_message = true;
    var _show_message_parent = false;
    // propiedades
    this.setStop = function (value) {
        _stop = value;
    }
    this.setMethod = function (value) {
        _method = value;
    }
    this.setMeEventType = function (value) {

    }

    this.showMessage = function (value) {
        _show_message = value;
    }
    this.showMessageParent = function (value) {
        _show_message_parent = value;
    }
    this.getParam = function () {
        return _param;
    }

    // metodos
    this.addParam = function (key, value) {
        _param[key] = value;
    }
    // - Agrega parametros temporales
    this.addParamTmp = function (key, value) {
        _param_tmp[key] = value;
    }
    // - Agrega la accion a consumir
    this.setAccion = function (accion) {
        _param_tmp[PARAM_ACCION_NAME] = accion;
    }
    this.setOpcion = function (opcion) {
        this.addParam(PARAM_OPCION_ID, opcion);
    }
    this.setTag = function (tag) {
        _param_tmp[PARAM_TAG] = tag;
    }
    this.setSuccess = function (successp) {
        _success = successp;
    }
    this.getParams = function () {
        var params = fusionarLogin(_param, _param_tmp);

        _param_tmp = deleteObject(_param_tmp);
        params[PARAM_SID] = getCookie(COOKIE_NAME_SID);
        params[PARAM_USUARIO_TOKEN] = getCookie(PARAM_USUARIO_TOKEN);
//        _param_tmp[PARAM_SID] = getCookie(COOKIE_NAME_SID);
//        if (document.getElementById("ldap_user"))
//            _param_tmp[PARAM_USU] = document.getElementById("ldap_user").value;
//        params = fusionarLogin(params, _param_tmp);
//        _param_tmp = deleteObject(_param_tmp);
        return params;
    }
    this.consumir = function () {
        var fun = _success;
        var params = this.getParams();

        $.ajax({
            data: params,
            url: _url,
            type: _method,
            dataType: _dataType,
            success:
                    function (data_response, textStatus, jqXHR) {

                        // *** probar que nos trae cada uno de los parametros devueltos
                        // necesitamos validar que la data retornada este correcta y en ese caso le mostramos 
                        // el mensaje de error
                        validateResponse.validaResponse(data_response, _show_message, _stop, _ins);
                        // Llamamos a una funcion general para tratar las peticiones comunes
                        commonsOnResponseAjaxp(data_response);
                        if (!isEmpty(fun))
                            eval(fun)(data_response);

                        if (mostrarMensajeEmergente(data_response) && _show_message_parent && parent['mostrarMensajeNoty'])
                            parent.mostrarMensajeNoty(data_response[RESPONSE_MENSAJE_EMERGENTE]['titulo'], data_response[RESPONSE_MENSAJE_EMERGENTE]['mensaje'], data_response[RESPONSE_MENSAJE_EMERGENTE]['tipo'], null, null, _ins);
                        _show_message_parent = false;
                        _ins.dispatchEvent('onSuccess', data_response, textStatus, jqXHR);
                    },
            error:
                    function (jqXHR, status, error) {
                        var message = jqXHR + ". \n * Error: " + error + ". \n * Status:" + status;
                        mostrarMensajeNoty('Error response ajax', message, MENSAJE_ERROR);
                        //$.messager.alert('Error',jqXHR+". \n * Error: "+error+". \n * Status:"+status,'error')
                        _ins.dispatchEvent('onError', jqXHR, status, error);
                        if (_stop == true)
                            window.stop();
                    },
            complete:
                    function (jqXHR, status) {
                        loaderClose();
                        _ins.dispatchEvent('onComplete', jqXHR, status);
                    }
        });

        params = deleteObject(params);
    }
    this.getAjaxSelect2 = function (_accionStatic) {
//        debugger;

        return {
            url: _url,
            type: _method,
            dataType: _dataType,
            allowClear: false,
            delay: 250,
            "data": function (d) {

                var params = ax.getParams();
                params[PARAM_ACCION_NAME] = _accionStatic;
                params[PARAM_FLAG_SELECT2] = 1;
                params['querySelect'] = d;
                return params;
            },
            results: function (response) {

                if (!isEmpty(response.data)) {
                    return {
                        results: response.data
                    };
                } else {
                    return {
                        results: {id: null, text: ''}
                    };
                }
            },
            transport: function (params, success, failure) {
//                debugger;

//                if (!banderaExecucion) {
//                    return;
//                }
                var $request = $.ajax(params);
//                banderaExecucion = false;
                return $request;


            },
            cache: true
        };
    }
    this.getAjaxDataTable = function (flagLoader = false, loaderId = null) {
        var params = this.getParams();
        return {
            url: _url,
            type: _method,
            dataType: _dataType,
            "data": function (d) {
                if (flagLoader) {
                    loaderShow(loaderId);
                }
                $.each(params, function (key, value) {
                    d[key] = value;
                });
                d[PARAM_FLAG_DATATABLE] = 1;
            },
            complete: function (data) {
                if (flagLoader) {
                    loaderClose();
                }
            }
        };
        params = deleteObject(params);
    }
    this.getDataDataTable = function (d) {
        var params = this.getParams();
        $.each(params, function (key, value) {
            d[key] = value;
        });
        d[PARAM_FLAG_DATATABLE] = 1;
    }
    // Control de los eventos de los mensajes del validador
    this.eventoMensajeOnShow = function () {
        _ins.dispatchEvent('messageOnShow');
    }
    this.eventoMensajeAfterShow = function () {
        _ins.dispatchEvent('messageAfterShow');
    }
    this.eventoMensajeOnCloseClick = function () {
        _ins.dispatchEvent('messageOnCloseClick');
    }
    this.eventoMensajeOnClose = function () {
        _ins.dispatchEvent('messageOnClose');
    }

    // funciones de apoyo

    // Mostramos el mensaje emergente si nos han enviado el RESPONSE_MESSAGE_OK
    function mostrarMensajeEmergente(data) {
        if (hasPropiertyObject(data, RESPONSE_MENSAJE_EMERGENTE)) {
            if (!isEmpty(data[RESPONSE_MENSAJE_EMERGENTE])) {
                // Mostramos la notificación enviada desde el controlador en la vista
                mostrarMensajeNoty(data[RESPONSE_MENSAJE_EMERGENTE]['titulo'], data[RESPONSE_MENSAJE_EMERGENTE]['mensaje'], data[RESPONSE_MENSAJE_EMERGENTE]['tipo'], null, null, _ins);
                return true;
            }
        }
        return false;
    }

    // elimina los params temporales
    function deleteObject(param_object) {
        delete param_object;
        param_object = null;
        param_object = new Object();
        return param_object;
    }

    // une dos objetos en uno
    function fusionarLogin(objeto1, objeto2) {
        var objeto = $.extend({}, objeto1);
        if (isEmpty(objeto2))
            return objeto;
        var propiedad;
        for (propiedad in objeto2) {
            objeto[propiedad] = objeto2[propiedad];
        }
        return objeto;
    }


    this.existsParamTmp = function (key)
    {
        for (var i in _param_tmp) {
            if (i == key)
                return true;
        }
        return false;
    }
}

function serviceRest($http) {
    //  Atributos de la clase
    var _url = URL_EXECUTECONTROLLER;
    var _param;
    var _ins = this;

    var _param_tmp = new Object();
    var _stop = false;
    var _show_message = true;
    var _show_message_parent = false;
    // propiedades
    this.setStop = function (value) {
        _stop = value;
    }
    this.showMessage = function (value) {
        _show_message = value;
    }
    this.showMessageParent = function (value) {
        _show_message_parent = value;
    }
    this.getParam = function () {
        return _param;
    }
    // metodos
    this.addParamPersistente = function (key, value) {
        _param[key] = value;
    }
    // - Agrega parametros temporales
    this.addParam = function (key, value) {
        _param_tmp[key] = value;
    }
    // - Agrega la accion a consumir
    this.setAccion = function (accion) {
        _param_tmp[PARAM_ACCION_NAME] = accion;
    }
    this.setOpcion = function (componente) {
        this.addParam(PARAM_COMPONENTE_ID, componente);
    }
    this.setTag = function (tag) {
        _param_tmp[PARAM_TAG] = tag;
    }
    this.getParams = function () {
        var params = fusionarLogin(_param, _param_tmp);
        _param_tmp = deleteObject(_param_tmp);
        params[PARAM_SID] = getCookie(COOKIE_NAME_SID);
//        _param_tmp[PARAM_SID] = getCookie(COOKIE_NAME_SID);
//        params = fusionarLogin(params, _param_tmp);
//        _param_tmp = deleteObject(_param_tmp);
        return params;
    }
    this.consumir = function (success, error) {
        var params = this.getParams();

        $http.post(URL_EXECUTECONTROLLER, params)
                .success(function (data_response) {
                    validateResponse.validaResponse(data_response, _show_message, _stop, _ins);
                    if (mostrarMensajeEmergente(data_response) && _show_message_parent && parent['mostrarMensajeNoty'])
                        parent.mostrarMensajeNoty(data_response[RESPONSE_MENSAJE_EMERGENTE]['titulo'], data_response[RESPONSE_MENSAJE_EMERGENTE]['mensaje'], data_response[RESPONSE_MENSAJE_EMERGENTE]['tipo'], null, null, _ins);
                    _show_message_parent = false;
                    success(data_response);
                })
                .error(error);
        params = deleteObject(params);
    }
    // funciones de apoyo

    // Mostramos el mensaje emergente si nos han enviado el RESPONSE_MESSAGE_OK
    function mostrarMensajeEmergente(data) {
        if (hasPropiertyObject(data, RESPONSE_MENSAJE_EMERGENTE)) {
            if (!isEmpty(data[RESPONSE_MENSAJE_EMERGENTE])) {
                // Mostramos la notificación enviada desde el controlador en la vista
                mostrarMensajeNoty(data[RESPONSE_MENSAJE_EMERGENTE]['titulo'], data[RESPONSE_MENSAJE_EMERGENTE]['mensaje'], data[RESPONSE_MENSAJE_EMERGENTE]['tipo'], null, null, _ins);
                return true;
            }
        }
        return false;
    }

    // elimina los params temporales
    function deleteObject(param_object) {
        delete param_object;
        param_object = null;
        param_object = new Object();
        return param_object;
    }

    // une dos objetos en uno
    function fusionarLogin(objeto1, objeto2) {
        var objeto = $.extend({}, objeto1);
        if (isEmpty(objeto2))
            return objeto;
        var propiedad;
        for (propiedad in objeto2) {
            objeto[propiedad] = objeto2[propiedad];
        }
        return objeto;
    }
    this.existsParamTmp = function (key)
    {
        for (var i in _param_tmp) {
            if (i == key)
                return true;
        }
        return false;
    }
}