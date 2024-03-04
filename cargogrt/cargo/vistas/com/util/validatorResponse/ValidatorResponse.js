/**
 * Todas las vistas deben importar este JS, para la validación respectiva 
 * y que me muestre el error pertinente 
 * @author: Christopher Heredia
 */
var params =new Object;
//params['control']= 'borrar_cookie';
var url = URL_BASE+"../netafimlogin/logout.php.php";

var validateResponse = {
    _this: this,
    validaResponse : function (data, show_message, stop, ajaxp){
        return validateResponse.validaResponseError(data, show_message, stop, false, ajaxp);
    },
    validaResponseError : function (data, show_message, stop, sbs_debug, ajaxp){
        var b_error = false;
        var message = null;
        var title = 'Error';
        var modal = true;
        var type = 'error';
        try{
            //validamos si existe un error mostramos el mensaje
            if (!isEmpty(data)){ 
                switch (data['status']){
                    case 'error':
                        b_error = true;
                        switch (data.type){
                            case -1:
                            case -2:
                            case -3:
                            case 0:
                            case 2:
                                // en el caso de error
                                type = MENSAJE_ERROR;
                                break;
                            case 3:
                                // em el caso de advertencias
                                type = MENSAJE_WARNING;
                                break;
                            default:
                                // en el caso de informacion a mostrar
                                type = MENSAJE_INFORMATION;
                        }
                        title = data.title;
                        message = data.message;
                        modal = data.modal;
                        break;
                    case 'ok':
                        return true;
                        break;
                    default:
                        b_error = true;
                        message = 'No se obtuvo un status válido';
                        break;
                }
            }else{
                b_error = true;
                message = 'Posiblemente no se especificaron todos los parámetros minimos necesarios';
            }
        }catch(e){
            message = 'Error al intentar validar: ' + e.message;
        }
        // en el caso haya ocurrido algun error
        if(b_error == true){
            if(show_message){                    
                mostrarMensajeNoty(title, message, type, function (){validateResponse.forzarLogout(data.type); }, MENSAJE_CALLBACK_ONCLOSECLICK, ajaxp, modal);
            }

            if(stop)
                window.stop();

            return false;
        }else{
            return true;
        }
    }
    ,
    forzarLogout: function(type)
    {
        if(type == TYPE_FORZAR_LOGOUT)
        {
            document.location.replace(URL_BASE+"logout.php");
//            var principal = window.parent.document;               
//            var nroIntentos = 0;
//            while(  ($.type(principal) !== "undefined" && $.type(principal) !== "null") &&
//                    (principal.URL != URL_BASE + "index.php" && principal.URL != URL_BASE + "index.php#" && principal.URL != URL_BASE)
//                 )
//            {   
//                principal=parent.document;
//                nroIntentos++;
//                if(nroIntentos === 20) break; // realizar solo 20 intentos para evitar la recurvsividad infinita
//            }    
//
//            if (($.type(principal) !== "undefined" && $.type(principal) !== "null"))
//            {
//                principal.location.replace(URL_BASE+"../netafimlogin/logout.php");
//            }
        }
    }
}
