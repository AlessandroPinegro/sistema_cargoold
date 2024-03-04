/* 
 * 
 * @version 1.0
 * @copyright (c) 2013, IMAGINA TECHNOLOGIES S.A.C.
 * 
 * @abstract JSScript Base que contiene las funciones utilitarios de los input
 */
var InputUtils = { };

/**
 * Lista de KeyCode del evento KeyPress
 * 
 * @type int
 */
InputUtils.KeyCode = {
    backspace: 8,
    enter: 13,
    tab: 9,
    supr: 46, // deleted: 46, Cambiando el nombre delete por supr, debido a que al parecer IE lo interpreta como una funcion propia de un objeto.
    shift: 16,
    ctrl: 17,
    alt: 18,
    pause_break: 19,
    caps_lock: 20,
    escape: 27,
    page_up: 33,
    page_down: 34,
    end: 35,
    home: 36,
    left_arrow: 37,
    up_arrow: 38,
    righ_arrow: 39,
    down_arrow: 40,
    insert: 45,
    left_window_key: 91,
    right_window_key: 92,
    select_key: 93,
    num_lock: 144,
    scroll_lock: 145 
};

InputUtils.SpecialKeyCodes = new Array(
            InputUtils.KeyCode.backspace,
            InputUtils.KeyCode.enter,
            InputUtils.KeyCode.tab,
            InputUtils.KeyCode.supr,
            InputUtils.KeyCode.shift,
            InputUtils.KeyCode.ctrl,
            InputUtils.KeyCode.alt,
            InputUtils.KeyCode.pause_break,
            InputUtils.KeyCode.caps_lock,
            InputUtils.KeyCode.escape,
            InputUtils.KeyCode.page_up,
            InputUtils.KeyCode.page_down,
            InputUtils.KeyCode.end,
            InputUtils.KeyCode.home,
            InputUtils.KeyCode.left_arrow,
            InputUtils.KeyCode.up_arrow,
            InputUtils.KeyCode.righ_arrow,
            InputUtils.KeyCode.down_arrow,
            InputUtils.KeyCode.insert,
            InputUtils.KeyCode.left_window_key,
            InputUtils.KeyCode.right_window_key,
            InputUtils.KeyCode.select_key,
            InputUtils.KeyCode.num_lock,
            InputUtils.KeyCode.scroll_lock
        );

/**
 * Restringe el ingreso de valor al input al controlar el keypress
 * 
 * @param {string} inputId identificador del input
 * @param {string} restrictType tipo de restricción a aplicar. Puede ser: integer, decimal, hour
 * @param {array} params objeto con propiedades que influyen en la restricción segun el tipo de restriccion
 *                  type:integer
 *                   - param:signed => null: entero con signo negativo
 *                                  => true: entero con signo positivo y negativo
 *                                  => false: entero sin signo
 *                  type: decimal
 *                   - param:signed => null: decimal con signo negativo
 *                                  => true: decimal con signo positivo y negativo
 *                                  => false: decimal sin signo
 *                  type: hour
 *                   - param:with_seconds => true: La hora incluye segundos. (por defecto)
 *                                        => false: La hora no incluye segundos
 *                   - param:separator_symbol => character o símbolo que separa las horas, minutos y segundos. Por defcto el caráctes es ':'
 *                   - param:is_24hours => true: Tiene el limite de 24 horas (23:59:59)
 *                                      => false: No tiene límite en las horas
 * @param {function} externalFunction Función externa que se ejecutará en el keypress 
 * @param {type} externalParams Parámetros de la función externa
 */
InputUtils.setRestrictFunction = function (inputId, restrictType, params, externalFunction, externalParams)
{
    if (isEmpty(inputId)) return;
    if (isEmpty($('#'+inputId))) return;
    if (!$('#'+inputId).is('input')) return;
    
    $('#'+inputId).keypress(function(e)
    {
        if (!isEmpty(externalFunction) || $.type(externalFunction) === JSType.FUNCTION)
        {
            return externalFunction.call (this, externalParams);
        }
        
        if (isEmpty(e.target)) return false;

        var key = (document.all) ? e.keyCode : e.which;
        if (key === InputUtils.KeyCode.backspace) return true;
        if (key === InputUtils.KeyCode.supr) return true;
        
        var char = String.fromCharCode(key);
                
        var rg = null;
        
        //Verificamos si existe alguna validación para el primer caracter ingresado
        if (trim(e.target.value).length === 0)
        {
            switch (trim(restrictType).toLowerCase())
            {
                case "integer": 
                    var signed = getPropiertyObject(params,'signed');
                    
                    if (isEmpty(signed))
                    {
                        rg = new RegExp(/^(-|\d)$/);
                    }
                    else if ($.type(signed) === JSType.BOOLEAN && signed === true)
                    {
                        rg = new RegExp(/^(\+|-|\d)$/);
                    }
                    else if ($.type(signed) === JSType.BOOLEAN && signed === false)
                    {
                        rg = new RegExp(/^\d$/);
                    }
                    
                    break;
                case "decimal":
                    var signed = getPropiertyObject(params,'signed');
                    
                    if (isEmpty(signed))
                    {
                        rg = new RegExp(/^(-|\d|\.)$/);
                    }
                    else if ($.type(signed) === JSType.BOOLEAN && signed === true)
                    {
                        rg = new RegExp(/^(\+|-|\d|\.)$/);
                    }
                    else if ($.type(signed) === JSType.BOOLEAN && signed === false)
                    {
                        rg = new RegExp(/^(\d|\.)$/);
                    }
                    
                    break;
                case "hour": 
                    var is_24hours = getPropiertyObject(params,'is_24hours');
                    if (isEmpty(is_24hours)) is_24hours = true;
                    
                    if (!is_24hours)
                    {
                        rg = new RegExp(/^\d$/); 
                    }    
                    else
                    {
                        rg = new RegExp(/^[0-9]$/); 
                    }    
                    
                    break;
            }
        }
        //En caso de haber mas de un caracter ingresado
        else
        {
            switch (trim(restrictType).toLowerCase())
            {
                case "integer":
                        rg = new RegExp(/^\d$/); break;
                case "decimal":
                        if (trim(e.target.value).indexOf(".",0) === -1)
                        {
                            rg = new RegExp(/^(\d|\.)$/);
                        }
                        else
                        {
                            rg = new RegExp(/^\d$/);
                        }
                        break;
                case "hour":
                        var with_seconds = getPropiertyObject(params,'with_seconds');
                        var separator_symbol = getPropiertyObject(params,'separator_symbol');
                        var is_24hours = getPropiertyObject(params,'is_24hours');
                        
                        if (isEmpty(with_seconds)) with_seconds = true;
                        if (isEmpty(separator_symbol)) separator_symbol = ":";
                        if (isEmpty(is_24hours)) is_24hours = false;
                        
                        var regexp_symbol = parserStringToValidInRegExpPattern(separator_symbol);
                        
                        var val = trim(e.target.value);
                        var start = $(this).caret().start;
                        var newval = val.slice(0,start) + char + val.slice(start);
                        
                        var patron = "";
                        
                        if (is_24hours)
                        {
                            patron = "([0-9]|[0-1][0-9]?|2[0-3]?)";
                        }
                        else
                        {
                            patron = "[0-9]+";
                        }
                        
                        if (with_seconds)
                        {
                            patron = patron + "("+regexp_symbol+"([0-5][0-9]?)?|"+regexp_symbol+"[0-5][0-9]"+regexp_symbol+"([0-5][0-9]?)?)?";
                        }
                        else
                        {
                            patron = patron + "("+regexp_symbol+"([0-5][0-9]?)?)?";
                        }
                        
                        if (patron.length === 0) return false;
                        
                        rg = new RegExp("^" + patron + "$");
                        
                        if (isEmpty(rg)) return false;
                        
                        return rg.test(newval);
                        
                        break;
            }
        }
        
        if (isEmpty(rg)) return false;
        
        return rg.test(char);
    });
};