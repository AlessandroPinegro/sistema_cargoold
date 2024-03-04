/* 
 * 
 * @version 1.0
 * @copyright (c) 2013, IMAGINA TECHNOLOGIES S.A.C.
 * 
 * @abstract JSScript encargado de la visualizacion y cierre del Loading
 */
function LoadingClass()
{
    var loaders = [];
    
    
    
    this.show = function(containerId, showMsg)
    {
        var contenedor = null;
        var cssZindex = ''; // en caso el contenedor sea el body
        var cssHeight = ''; // en caso el contenedor sea el body
        var index_highest = 0;
        var message = ($ && $.etiqueta) ? $.etiqueta('common.loading', 'lblMessage') : 'Procesando, por favor espere ...';
        if(isEmpty(showMsg)) showMsg = true;
        
        if(isEmpty(containerId)) {
            // Si no se pone un contenedor, el contenedor sera el body 
            // con un z-index de modo que se posicione sobre todos los elementos.
            contenedor = $('body');
            $('.window-mask').each(function(){
                var index_current = parseInt($(this).css("z-index"), 10);
                if(index_current > index_highest) {
                    index_highest = index_current;
                }
            });
            cssHeight = ';height:' + contenedor.height();
            cssZindex = ';z-index:' + index_highest + 100;
        } else {
            if($.type(containerId) === 'string') contenedor = $('#' + containerId);
            else if($.type(containerId) === 'object' && containerId.length === 1) contenedor = containerId;
            else return;
            contenedor.children().each(function(){
                var index_current = parseInt($(this).css("z-index"), 10);
                if(index_current > index_highest) {
                    index_highest = index_current;
                }
            });
            cssZindex = ';z-index:' + index_highest + 100;
            // es necesario poner el contenedor como relativo para que el loading
            // solo ocupe el espacio del contendor.
            contenedor.css('position', 'relative');
        }
        var cssAltoMsg = '';
        if(contenedor.height() < 42) {
            cssAltoMsg = ';margin-top: -11px;padding-top: 2px;padding-bottom: 0px;';
        }

        $("<div id='loadingMask' class=\"loading-mask\" style=\"display:block" + cssZindex + cssHeight + "\"></div>").appendTo(contenedor);
        if(!showMsg) message = '';
        var msg = $("<div id='loadingMsg' class=\"loading-mask-msg\" style=\"display:block;left:50%" + cssZindex + cssAltoMsg + "\"></div>").html(message).appendTo(contenedor);
        msg.css("marginLeft", -msg.outerWidth() / 2);
        msg.css("marginLeft", -msg.outerWidth() / 2);
        
        addLoader(containerId);
    };
    
    function addLoader(id)
    {
        var existe = false;
        for(var i=0; i<loaders.length; i++) {
            var compara = false;
            if ($.type(loaders[i].id) === JSType.OBJECT && $.type(id) === JSType.OBJECT && loaders[i].id.length > 0 && id.length > 0)
            {
                compara = (loaders[i].id.get(0) == id.get(0));
            }
            else
            {
                compara = (loaders[i].id == id);
            }
            
            if(compara) {
                loaders[i].shows++;
                existe = true;
                break;
            }
        }
        if(!existe) {
            loaders.push({id:id, shows:1});
        }
    }
    
    function removeLoader(id)
    {
        for(var i=0; i<loaders.length; i++) {
            var compara = false;
            if ($.type(loaders[i].id) === JSType.OBJECT && $.type(id) === JSType.OBJECT && loaders[i].id.length > 0 && id.length > 0)
            {
                compara = (loaders[i].id.get(0) == id.get(0));
            }
            else
            {
                compara = (loaders[i].id == id);
            }
            
            if(compara) {
                loaders[i].shows--;
                if(loaders[i].shows === 0) {
                    loaders.splice(i, 1);
                    return true;
                }
                break;
            }
        }
        return false;
    }
    
    this.close = function (containerId)
    {
        if(removeLoader(containerId) === false) return;
        
        var contenedor = null;
        if(isEmpty(containerId)) {
            contenedor = $('body');
        } else {
            if($.type(containerId) === 'string') contenedor = $('#' + containerId);
            else if($.type(containerId) === 'object' && containerId.length === 1) contenedor = containerId;
            else return;
            contenedor.css('position', '');
        }
        
        contenedor.children("div.loading-mask-msg").remove();
        contenedor.children("div.loading-mask").remove();
    };
}

var Loading = new LoadingClass();