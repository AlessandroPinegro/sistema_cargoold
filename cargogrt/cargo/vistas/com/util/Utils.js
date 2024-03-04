function loaderShow(element = null) {
    if (isEmpty(element))
        element = "body";
    $(element).append('<div id="loaderImagina" class="panel-disabled-imagina"><div class="loader-1"></div></div>');
}
function loaderClose() {
    $("#loaderImagina").remove();
}
/*
 *FUNCION PARA CAPTURAR UNA COKIE
 * getCookie(NAME);
 **/
if ($.type(String.prototype.trimLeft) !== JSType.FUNCTION) {
    String.prototype.trimLeft = function () {
        return this.replace(/^\s+/, "");
    };
}
if ($.type(String.prototype.trimRight) !== JSType.FUNCTION) {
    String.prototype.trimRight = function () {
        return this.replace(/\s+$/, "");
    };
}
if ($.type(Array.prototype.map) !== JSType.FUNCTION) {
    Array.prototype.map = function (callback, thisArg) {
        for (var i = 0, n = this.length, a = []; i < n; i++) {
            if (i in this)
                a[i] = callback.call(thisArg, this[i]);
        }
        return a;
    };
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

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}
function getCookies() {
    var c = document.cookie, v = 0, cookies = {};
    if (document.cookie.match(/^\s*\$Version=(?:"1"|1);\s*(.*)/)) {
        c = RegExp.$1;
        v = 1;
    }
    if (v == 0) {
        c.split(/[,;]/).map(function (cookie) {
            var parts = cookie.split(/=/, 2),
                    name = decodeURIComponent(parts[0].trimLeft()),
                    value = parts.length > 1 ? decodeURIComponent(parts[1].trimRight()) : null;
            cookies[name] = value;
        });
    } else {
        c.match(/(?:^|\s+)([!#$%&'*+\-.0-9A-Z^`a-z|~]+)=([!#$%&'*+\-.0-9A-Z^`a-z|~]*|"(?:[\x20-\x7E\x80\xFF]|\\[\x00-\x7F])*")(?=\s*[,;]|$)/g).map(function ($0, $1) {
            var name = $0,
                    value = $1.charAt(0) == '"'
                    ? $1.substr(1, -1).replace(/\\(.)/g, "$1")
                    : $1;
            cookies[name] = value;
        });
    }
    return cookies;
}
function getCookie(name) {
    return getCookies()[name];
}



/*
 * Metodo en cargado de generar el JSON para enviar al controlador
 * v = new jArgs();
 * v.addArgs('key','value');
 * c = v.getjson();
 */
function jArgs() {
    this.str = '';
    this.addArgs = function (key, value) {
        this.str += (this.str.length) == 0 ? '"' + key + '":' + '"' + value + '"' : ',"' + key + '":' + '"' + value + '"';
    };
    this.getjson = function () {
        this.str = '{' + this.str + '}';
        return this.str;
    };

}

/**
 * Funcion trasnforma una cadena en MD5
 */
function md5(str) {
    var xl;

    var rotateLeft = function (lValue, iShiftBits) {
        return (lValue << iShiftBits) | (lValue >>> (32 - iShiftBits));
    };

    var addUnsigned = function (lX, lY) {
        var lX4, lY4, lX8, lY8, lResult;
        lX8 = (lX & 0x80000000);
        lY8 = (lY & 0x80000000);
        lX4 = (lX & 0x40000000);
        lY4 = (lY & 0x40000000);
        lResult = (lX & 0x3FFFFFFF) + (lY & 0x3FFFFFFF);
        if (lX4 & lY4) {
            return (lResult ^ 0x80000000 ^ lX8 ^ lY8);
        }
        if (lX4 | lY4) {
            if (lResult & 0x40000000) {
                return (lResult ^ 0xC0000000 ^ lX8 ^ lY8);
            } else {
                return (lResult ^ 0x40000000 ^ lX8 ^ lY8);
            }
        } else {
            return (lResult ^ lX8 ^ lY8);
        }
    };

    var _F = function (x, y, z) {
        return (x & y) | ((~x) & z);
    };
    var _G = function (x, y, z) {
        return (x & z) | (y & (~z));
    };
    var _H = function (x, y, z) {
        return (x ^ y ^ z);
    };
    var _I = function (x, y, z) {
        return (y ^ (x | (~z)));
    };

    var _FF = function (a, b, c, d, x, s, ac) {
        a = addUnsigned(a, addUnsigned(addUnsigned(_F(b, c, d), x), ac));
        return addUnsigned(rotateLeft(a, s), b);
    };

    var _GG = function (a, b, c, d, x, s, ac) {
        a = addUnsigned(a, addUnsigned(addUnsigned(_G(b, c, d), x), ac));
        return addUnsigned(rotateLeft(a, s), b);
    };

    var _HH = function (a, b, c, d, x, s, ac) {
        a = addUnsigned(a, addUnsigned(addUnsigned(_H(b, c, d), x), ac));
        return addUnsigned(rotateLeft(a, s), b);
    };

    var _II = function (a, b, c, d, x, s, ac) {
        a = addUnsigned(a, addUnsigned(addUnsigned(_I(b, c, d), x), ac));
        return addUnsigned(rotateLeft(a, s), b);
    };

    var convertToWordArray = function (str) {
        var lWordCount;
        var lMessageLength = str.length;
        var lNumberOfWords_temp1 = lMessageLength + 8;
        var lNumberOfWords_temp2 = (lNumberOfWords_temp1 - (lNumberOfWords_temp1 % 64)) / 64;
        var lNumberOfWords = (lNumberOfWords_temp2 + 1) * 16;
        var lWordArray = new Array(lNumberOfWords - 1);
        var lBytePosition = 0;
        var lByteCount = 0;
        while (lByteCount < lMessageLength) {
            lWordCount = (lByteCount - (lByteCount % 4)) / 4;
            lBytePosition = (lByteCount % 4) * 8;
            lWordArray[lWordCount] = (lWordArray[lWordCount] | (str.charCodeAt(lByteCount) << lBytePosition));
            lByteCount++;
        }
        lWordCount = (lByteCount - (lByteCount % 4)) / 4;
        lBytePosition = (lByteCount % 4) * 8;
        lWordArray[lWordCount] = lWordArray[lWordCount] | (0x80 << lBytePosition);
        lWordArray[lNumberOfWords - 2] = lMessageLength << 3;
        lWordArray[lNumberOfWords - 1] = lMessageLength >>> 29;
        return lWordArray;
    };

    var wordToHex = function (lValue) {
        var wordToHexValue = "",
                wordToHexValue_temp = "",
                lByte, lCount;
        for (lCount = 0; lCount <= 3; lCount++) {
            lByte = (lValue >>> (lCount * 8)) & 255;
            wordToHexValue_temp = "0" + lByte.toString(16);
            wordToHexValue = wordToHexValue + wordToHexValue_temp.substr(wordToHexValue_temp.length - 2, 2);
        }
        return wordToHexValue;
    };

    var x = [],
            k, AA, BB, CC, DD, a, b, c, d, S11 = 7,
            S12 = 12,
            S13 = 17,
            S14 = 22,
            S21 = 5,
            S22 = 9,
            S23 = 14,
            S24 = 20,
            S31 = 4,
            S32 = 11,
            S33 = 16,
            S34 = 23,
            S41 = 6,
            S42 = 10,
            S43 = 15,
            S44 = 21;

    str = this.utf8_encode(str);
    x = convertToWordArray(str);
    a = 0x67452301;
    b = 0xEFCDAB89;
    c = 0x98BADCFE;
    d = 0x10325476;

    xl = x.length;
    for (k = 0; k < xl; k += 16) {
        AA = a;
        BB = b;
        CC = c;
        DD = d;
        a = _FF(a, b, c, d, x[k + 0], S11, 0xD76AA478);
        d = _FF(d, a, b, c, x[k + 1], S12, 0xE8C7B756);
        c = _FF(c, d, a, b, x[k + 2], S13, 0x242070DB);
        b = _FF(b, c, d, a, x[k + 3], S14, 0xC1BDCEEE);
        a = _FF(a, b, c, d, x[k + 4], S11, 0xF57C0FAF);
        d = _FF(d, a, b, c, x[k + 5], S12, 0x4787C62A);
        c = _FF(c, d, a, b, x[k + 6], S13, 0xA8304613);
        b = _FF(b, c, d, a, x[k + 7], S14, 0xFD469501);
        a = _FF(a, b, c, d, x[k + 8], S11, 0x698098D8);
        d = _FF(d, a, b, c, x[k + 9], S12, 0x8B44F7AF);
        c = _FF(c, d, a, b, x[k + 10], S13, 0xFFFF5BB1);
        b = _FF(b, c, d, a, x[k + 11], S14, 0x895CD7BE);
        a = _FF(a, b, c, d, x[k + 12], S11, 0x6B901122);
        d = _FF(d, a, b, c, x[k + 13], S12, 0xFD987193);
        c = _FF(c, d, a, b, x[k + 14], S13, 0xA679438E);
        b = _FF(b, c, d, a, x[k + 15], S14, 0x49B40821);
        a = _GG(a, b, c, d, x[k + 1], S21, 0xF61E2562);
        d = _GG(d, a, b, c, x[k + 6], S22, 0xC040B340);
        c = _GG(c, d, a, b, x[k + 11], S23, 0x265E5A51);
        b = _GG(b, c, d, a, x[k + 0], S24, 0xE9B6C7AA);
        a = _GG(a, b, c, d, x[k + 5], S21, 0xD62F105D);
        d = _GG(d, a, b, c, x[k + 10], S22, 0x2441453);
        c = _GG(c, d, a, b, x[k + 15], S23, 0xD8A1E681);
        b = _GG(b, c, d, a, x[k + 4], S24, 0xE7D3FBC8);
        a = _GG(a, b, c, d, x[k + 9], S21, 0x21E1CDE6);
        d = _GG(d, a, b, c, x[k + 14], S22, 0xC33707D6);
        c = _GG(c, d, a, b, x[k + 3], S23, 0xF4D50D87);
        b = _GG(b, c, d, a, x[k + 8], S24, 0x455A14ED);
        a = _GG(a, b, c, d, x[k + 13], S21, 0xA9E3E905);
        d = _GG(d, a, b, c, x[k + 2], S22, 0xFCEFA3F8);
        c = _GG(c, d, a, b, x[k + 7], S23, 0x676F02D9);
        b = _GG(b, c, d, a, x[k + 12], S24, 0x8D2A4C8A);
        a = _HH(a, b, c, d, x[k + 5], S31, 0xFFFA3942);
        d = _HH(d, a, b, c, x[k + 8], S32, 0x8771F681);
        c = _HH(c, d, a, b, x[k + 11], S33, 0x6D9D6122);
        b = _HH(b, c, d, a, x[k + 14], S34, 0xFDE5380C);
        a = _HH(a, b, c, d, x[k + 1], S31, 0xA4BEEA44);
        d = _HH(d, a, b, c, x[k + 4], S32, 0x4BDECFA9);
        c = _HH(c, d, a, b, x[k + 7], S33, 0xF6BB4B60);
        b = _HH(b, c, d, a, x[k + 10], S34, 0xBEBFBC70);
        a = _HH(a, b, c, d, x[k + 13], S31, 0x289B7EC6);
        d = _HH(d, a, b, c, x[k + 0], S32, 0xEAA127FA);
        c = _HH(c, d, a, b, x[k + 3], S33, 0xD4EF3085);
        b = _HH(b, c, d, a, x[k + 6], S34, 0x4881D05);
        a = _HH(a, b, c, d, x[k + 9], S31, 0xD9D4D039);
        d = _HH(d, a, b, c, x[k + 12], S32, 0xE6DB99E5);
        c = _HH(c, d, a, b, x[k + 15], S33, 0x1FA27CF8);
        b = _HH(b, c, d, a, x[k + 2], S34, 0xC4AC5665);
        a = _II(a, b, c, d, x[k + 0], S41, 0xF4292244);
        d = _II(d, a, b, c, x[k + 7], S42, 0x432AFF97);
        c = _II(c, d, a, b, x[k + 14], S43, 0xAB9423A7);
        b = _II(b, c, d, a, x[k + 5], S44, 0xFC93A039);
        a = _II(a, b, c, d, x[k + 12], S41, 0x655B59C3);
        d = _II(d, a, b, c, x[k + 3], S42, 0x8F0CCC92);
        c = _II(c, d, a, b, x[k + 10], S43, 0xFFEFF47D);
        b = _II(b, c, d, a, x[k + 1], S44, 0x85845DD1);
        a = _II(a, b, c, d, x[k + 8], S41, 0x6FA87E4F);
        d = _II(d, a, b, c, x[k + 15], S42, 0xFE2CE6E0);
        c = _II(c, d, a, b, x[k + 6], S43, 0xA3014314);
        b = _II(b, c, d, a, x[k + 13], S44, 0x4E0811A1);
        a = _II(a, b, c, d, x[k + 4], S41, 0xF7537E82);
        d = _II(d, a, b, c, x[k + 11], S42, 0xBD3AF235);
        c = _II(c, d, a, b, x[k + 2], S43, 0x2AD7D2BB);
        b = _II(b, c, d, a, x[k + 9], S44, 0xEB86D391);
        a = addUnsigned(a, AA);
        b = addUnsigned(b, BB);
        c = addUnsigned(c, CC);
        d = addUnsigned(d, DD);
    }

    var temp = wordToHex(a) + wordToHex(b) + wordToHex(c) + wordToHex(d);

    return temp.toLowerCase();
}

function utf8_encode(argString) {

    if (argString === null || $.type(argString) === JSType.UNDEFINED) {
        return "";
    }

    var string = (argString + ''); // .replace(/\r\n/g, "\n").replace(/\r/g, "\n");
    var utftext = '',
            start, end, stringl = 0;

    start = end = 0;
    stringl = string.length;
    for (var n = 0; n < stringl; n++) {
        var c1 = string.charCodeAt(n);
        var enc = null;

        if (c1 < 128) {
            end++;
        } else if (c1 > 127 && c1 < 2048) {
            enc = String.fromCharCode((c1 >> 6) | 192, (c1 & 63) | 128);
        } else {
            enc = String.fromCharCode((c1 >> 12) | 224, ((c1 >> 6) & 63) | 128, (c1 & 63) | 128);
        }
        if (enc !== null) {
            if (end > start) {
                utftext += string.slice(start, end);
            }
            utftext += enc;
            start = end = n + 1;
        }
    }

    if (end > start) {
        utftext += string.slice(start, stringl);
    }

    return utftext;
}

// valida si el objeto result esta bien formado y si me presento algun errror
function validaResult(result) {
    if (!(result == undefined)) {
        if (!(result.status == undefined)) {
            if (result.status != "ok") {
                if (!(result.msg == undefined)) {
                    $.messager.show({
                        title: "Error",
                        msg: result.msg
                    });
                    return false;
                } else {
                    $.messager.show({
                        title: "Error",
                        msg: "Se produjo un error en la solicitud pero no se retornó el mensaje explicito."
                    });
                    return false;
                }
            } else {
                if (result.rows !== undefined) {
                    return true;
                } else {
                    $.messager.show({
                        title: "Error",
                        msg: "No se retornaron las filas."
                    });
                    return false;
                }
            }
        } else {
            $.messager.show({
                title: "Error",
                msg: "No se retornó el estado de la consulta."
            });
            return false;
        }
    } else {
        $.messager.show({
            title: "data",
            msg: "No se retornó un resultado válido."
        });
        return false;
    }
}

function setValueEtiqueta(etiqueta, valor) {
    document.getElementById(etiqueta).innerHTML = valor;
}

function setValueComponente(etiqueta, valor) {
    document.getElementById(etiqueta).value = valor;
}

// función que me valida si es que existe un cookie de inicio de sesión 
function validaCookie() {
    try {
        // obtengo el sid de inicio de sesión del Usuario 
        var sid = getCookie(COOKIE_NAME_SID);
        if (sid !== undefined) {
            return sid;
        } else {
            window.location.replace("vistas/com/core/login/login.php");
            return false;
        }
    } catch (e) {
        alert(e);
        window.location.replace("vistas/com/core/login/login.php");
        return false;
    }
}

// Variable Global que obtiene el sid actual.
var sid = getCookie(COOKIE_NAME_SID);

function is_object(mixed_var) {
    // http://kevin.vanzonneveld.net
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Legaev Andrey
    // +   improved by: Michael White (http://getsprink.com)
    // *     example 1: is_object('23');
    // *     returns 1: false
    // *     example 2: is_object({foo: 'bar'});
    // *     returns 2: true
    // *     example 3: is_object(null);
    // *     returns 3: false
    if (Object.prototype.toString.call(mixed_var) == '[object Array]') {
        return false;
    }
    return mixed_var !== null && typeof mixed_var == 'object';
}

function is_int(input) {
    return typeof (input) == 'number' && parseInt(input) == input;
}

function isEmpty(value) {
    if ($.type(value) === 'undefined')
        return true;
    if ($.type(value) === 'null')
        return true;
    if ($.type(value) === 'string' && value.length <= 0)
        return true;
    if ($.type(value) === 'array' && value.length === 0)
        return true;
    if ($.type(value) === 'number' && isNaN(parseInt(value)))
        return true;
    if ($.type(value) === 'object' && Object.keys(value).length === 0)
        return true;

    return false;
}

function isEmptyData(data) {
    if (isEmpty(data))
        return true;
    if ($.type(data) === JSType.ARRAY) {
        return data.length === 0;
    } else if ($.type(data) === JSType.OBJECT) {
        if (!hasPropiertyObject(data, 'rows'))
            return true;

        return isEmpty(data.rows);
    } else {
        return true;
    }

    return false;
}

function isArray(value) {
    if (typeof (value) == undefined)
        return false;
    if (value == null)
        return false;

    if (value instanceof Array)
        return true;

    return false;
}

function isBoolean(value) {
    if ($.type(value) === JSType.UNDEFINED)
        return false;
    if (value === null)
        return false;

    if ($.type(value) === JSType.BOOLEAN)
        return true;

    return false;
}

function crearWindow(title, divMensaje, messagerDefault) {
    var win = $("<div class=\"messager-body\"></div>").appendTo("body");
    win.append(divMensaje);
    if (messagerDefault) {
        var tb = $("<div class=\"messager-button\"></div>").appendTo(win);
        for (var itemMessagerDefault in messagerDefault) {
            $("<a></a>").attr("href", "javascript:void(0)").text(itemMessagerDefault).css("margin-left", 10).bind("click", eval(messagerDefault[itemMessagerDefault])).appendTo(tb).linkbutton();
        }
    }
    win.window({
        title: title, noheader: (title ? false : true), width: 300, height: "auto", modal: true, collapsible: false, minimizable: false, maximizable: false, resizable: false, onClose: function () {
            setTimeout(function () {
                win.window("destroy");
            }, 100);
        }
    });
    win.window("window").addClass("messager-window");
    win.children("div.messager-button").children("a:first").focus();
    return win;
}
;

$.extend($.messager, {
    cierreTicket: function (title, mensaje, paramsCombo, funcion) {
        var divMensaje = "<div class=\"messager-icon messager-question\"></div>" + mensaje
                + "<br/>" + "<div style=\"clear:both;\"/>" + '\
            <div id="' + paramsCombo['contenedor'] + '" style="display:inline-block"></div>';
        var combo = null;
        var messagerDefault = {};
        messagerDefault[$.messager.defaults.ok] = function () {
            win.window("close");
            if (funcion) {
                funcion(combo.getItemSeleccionado());
                return false;
            }
        };
        messagerDefault[$.messager.defaults.cancel] = function () {
            win.window("close");
            if (funcion) {
                funcion();
                return false;
            }
        };
        var win = crearWindow(title, divMensaje, messagerDefault);
        win.children("input.messager-input").focus();
        setTimeout(function () {
            combo = new SbsComboBox(paramsCombo['contenedor'], paramsCombo['contenedor'] + '_cmb');
            combo.setValueField(paramsCombo['valuefield']);
            combo.setTextField(paramsCombo['textfield']);
            combo.setOpcional(false);
            combo.setDataCombo(paramsCombo['data']);
            combo.setWidth(paramsCombo['width']);
            combo.setPanelWidth('auto');
            combo.setPanelHeight('auto');
            combo.setPanelMaxHeight('200');
            combo.load();
            $('#' + paramsCombo['contenedor']).prepend('<span>' + paramsCombo['labelEfectoCierre'] + '</span>');
        }, 100);
        return win;
    }
});

function parserStringToValidInRegExpPattern(string) {
    if (isEmpty(string))
        return "";

    var valid_string = string;

    valid_string = valid_string.replace("\\", "\\\\", 'g');
    valid_string = valid_string.replace("^", "\^", 'g');
    valid_string = valid_string.replace("$", "\$", 'g');
    valid_string = valid_string.replace("*", "\*", 'g');
    valid_string = valid_string.replace("+", "\+", 'g');
    valid_string = valid_string.replace("?", "\?", 'g');
    valid_string = valid_string.replace(".", "\.", 'g');
    valid_string = valid_string.replace("!", "\!", 'g');

    return valid_string;
}

function existsElement(value) {
    var elem = document.getElementById(value);
    if (isEmpty(elem))
        return false;

    return true;
}

/**
 * chl: Obtener el ancho en px segun el porcentaje especificado
 * porcent: porcentaje del ancho total que se desea obtener
 * pxreferencial: en el caso q se quiera comparar a algo diferente al window especificar el acho del div
 * pxmin: ancho especificado en pixeles de hasta cuanto el componente soporta como minimo
 * pxmax: ancho especificado en pixeles de hasta cuanto el componente soporta como máximo
 */
function getWidthPxByPorcentaje(porcent, pxreferencia, pxmin, pxmax) {
    var pxtotal = 0;

    if (isEmpty(pxreferencia)) {
        pxtotal = $(window).width();
    } else {
        pxtotal = pxreferencia;
    }
    return getSizePx(porcent, pxtotal, pxmax, pxmin);
}

/**
 * chl: Obtener el alto en px segun el porcentaje especificado
 * porcent: porcentaje del alto que se desea obtener
 * pxreferencial: en el caso q se quiera comparar a algo diferente al window especificar el alto del div
 * pxmin: alto especificado en pixeles de hasta cuanto el componente soporta como minimo
 * pxmax: alto especificado en pixeles de hasta cuanto el componente soporta como máximo
 */
function getHeightPxByPorcentaje(porcent, pxreferencia, pxmin, pxmax) {
    var pxtotal = 0;
    if (isEmpty(pxreferencia)) {
        pxtotal = $(window).height();
    } else {
        pxtotal = pxreferencia;
    }
    return getSizePx(porcent, pxtotal, pxmin, pxmax);
}


/**
 * * chl: Obtener el tamaño en px segun el porcentaje especificado
 * porcent: porcentaje del tamaño que se desea obtener
 * pxtotal: tamaño maximo = 100% de la comparación
 * pxmin: tamaño especificado en pixeles de hasta cuanto el componente soporta como minimo
 * pxmax: tamaño especificado en pixeles de hasta cuanto el componente soporta como máximo
 */
function getSizePx(porcent, pxtotal, pxmin, pxmax) {
    var px = 0;
    px = pxtotal * porcent / 100;
    if (isEmpty(pxmin)) {
        if (px < pxmin) {
            px = pxmin;
        }
    }
    if (isEmpty(pxmax)) {
        if (px > pxmax) {
            px = pxmax;
        }
    }
    return px;
}

// operaciones con times:
function padNmb(nStr, nLen) {
    var sRes = String(nStr);
    var sCeros = "0000000000";
    return sCeros.substr(0, nLen - sRes.length) + sRes;
}

function stringToSeconds(tiempo) {
    var sep1 = tiempo.indexOf(":");
    var sep2 = tiempo.lastIndexOf(":");
    var hor = tiempo.substr(0, sep1);
    var min = tiempo.substr(sep1 + 1, sep2 - sep1 - 1);
    var sec = tiempo.substr(sep2 + 1);
    return (Number(sec) + (Number(min) * 60) + (Number(hor) * 3600));
}

function secondsToTime(secs) {
    var hor = Math.floor(secs / 3600);
    var min = Math.floor((secs - (hor * 3600)) / 60);
    var sec = secs - (hor * 3600) - (min * 60);
    return padNmb(hor, 2) + ":" + padNmb(min, 2) + ":" + padNmb(sec, 2);
}

function substractTimes(t1, t2) {
    var secs1 = stringToSeconds(t1);
    var secs2 = stringToSeconds(t2);
    var secsDif = secs1 - secs2;
    return secondsToTime(secsDif);
}

function calcT3() {
    with (document.frm)
        t3.value = substractTimes(t1.value, t2.value);
}

function getValorByEtiqueta(etiquetas, etiqueta) {
    var valor = null;
    if (!isEmpty(etiquetas)) {
        $.each(etiquetas, function (index, item) {
            if (item.etiqueta == etiqueta) {
                valor = item.valor;
                return false;
            }
        });
    }
    return valor;
}

/**
 * Formateo de fecha de (dd/mm/yyyy HH:mm) a (yyyy-mm-dd HH:mm)
 * 
 * @param {type} str
 * @returns {String}
 */
function dateFormatRiaToWs(str) {
    if (isEmpty(str))
        return null;

    var fechaHora = str.split(" ");
    var fecha = fechaHora[0];
    var hora = fechaHora[1];
    var m = fecha.match(/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/);
    var date = (m) ? new Date(m[3], m[2] - 1, m[1]) : null;
    if (isEmpty(date))
        return null;
    var month = date.getMonth() + 1;
    month = month < 10 ? '0' + month : month;
    var newStr = date.getFullYear() + '-' + month + '-' + date.getDate() + ' ' + hora;

    return newStr;
}

function isEmptyObject(obj) {
    if (!is_object(obj))
        return false;
    return $.isEmptyObject(obj);
    //return Object.getOwnPropertyNames(obj).length == 0;
}

function hasPropiertyObject(obj, propierty) {
    if (isEmpty(obj))
        return false;
    if (isEmptyObject(obj))
        return false;

    return obj.hasOwnProperty(propierty);
}

function getPropiertyObject(obj, propierty) {
    if (!hasPropiertyObject(obj, propierty))
        return null;

    return obj[propierty];
}

function validarSubidaArchivos(files, ExtPermitidas, ExtProhibidas, tamanioMaximo) {
    if (isEmpty(files))
        return false;

    var ExtEjecutables = new Array(".exe", ".bat", ".sql", ".com", ".bin", ".cmd", ".ini");

    var examinarTamanio = false;
    if (!isEmpty(tamanioMaximo) && !isNaN(tamanioMaximo))
        examinarTamanio = true;
    var strExtPer = ExtPermitidas.length > 0 ? ExtPermitidas.join('') : '';
    var strExtExe = ExtEjecutables.join('');
    var strExtProh = ExtProhibidas.join('');
    var parts;
    for (var i = 0; i < files.length; i++) {
        parts = files[i].type.split('/');
        if (parts == 1) // el archivo no tiene extension
            $.messager.alert('Alerta', 'No puede subir un archivo que no tenga extension', 'info');
        if (parts.length > 1) {
            if (strExtExe.indexOf('.' + parts[parts.length - 1]) >= 0 || parts[parts.length - 1] == 'x-msdownload') {
                $.messager.alert('Alerta', 'No puede subir archivos ejecutables', 'info');
                return false;
            }
            if (strExtProh.indexOf('.' + parts[parts.length - 1]) >= 0 && strExtProh != '') {
                $.messager.alert('Alerta', 'Las extenciones ' + ExtProhibidas.join(' , ') + ' estan prohibidas', 'info');
                return false;
            }
            if (strExtPer.indexOf('.' + parts[parts.length - 1]) < 0 && strExtPer != '') {
                $.messager.alert('Alerta', 'Solo puede subir archivos con extensiones ' + ExtPermitidas.join(' , '), 'info');
                return false;
            }
            if (examinarTamanio && tamanioMaximo < files[i].size) {
                $.messager.alert('Alerta', 'Solo puede subir archivos de ' + tamanioMaximo + ' bytes como maximo', 'info');
                return false;
            }
        }
    }
    return true;
}

function convertSecondsToHours(seconds, showSeconds) {
    if (isEmpty(seconds)) {
        if (isEmpty(showSeconds) || showSeconds == true)
            return '00:00:00';
        else
            return '00:00';
    }

    var hours = Math.floor(seconds / 3600);
    if (hours >= 1)
        seconds = seconds - (hours * 3600);
    var min = Math.floor(seconds / 60);
    if (min >= 1)
        seconds = seconds - (min * 60);
    var sec = seconds;
    var hora = (hours < 9) ? "0" + hours : hours;
    var minuto = (min < 9) ? "0" + min : min;
    var segundo = (sec < 9) ? "0" + sec : sec;
    if (isEmpty(showSeconds) || showSeconds == true)
        return hora + ':' + minuto + ':' + segundo;
    else
        return hora + ':' + minuto;
}
function convertHoursToSeconds(time) {
    var times = time.split(':');
    var seconds = 0;
    if (times.length == 2)
        seconds = Math.floor(times[1]) + Math.floor(times[0] * 60);
    if (times.length == 3)
        seconds = Math.floor(times[2]) + Math.floor(times[1] * 60) + Math.floor(times[0] * 3600);
    return seconds;
}

function htmlEncode(value) {
    return $('<div/>').text(value).html();
}

function htmlDecode(value) {
    return $('<div/>').html(value).text();
}

(function ($) {
    var iframe = null;
    $.extend({
        downloadFile: function (fileUrl) {
            if (iframe) {
                iframe.attr('src', fileUrl);
            } else {
                //create a temporary iframe that is used to request the fileUrl as a GET request
                iframe = $("<iframe>")
                        .hide()
                        .prop("src", fileUrl)
                        .appendTo("body");
            }
        },
        downloadFileExcel: function (fileName, empresaId) {
            var fileUrl = URL_BASE + "download.php?file=" + fileName + "&empresa=" + empresaId + "&tipo=export";
            if (iframe) {
                iframe.attr('src', fileUrl);
            } else {
                //create a temporary iframe that is used to request the fileUrl as a GET request
                iframe = $("<iframe>")
                        .hide()
                        .prop("src", fileUrl)
                        .appendTo("body");
            }
        }

    });

})($);

function onlyNumbers() {
    var keynum = window.event ? window.event.keyCode : e.which;
    if (keynum === 8)
        return true;
    return /\d/.test(String.fromCharCode(keynum));
}

function validateEmail(email) {
    return /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i.test(email);
}

function generateCode(lenght) {
    if (isEmpty(lenght))
        lenght = 5;

    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for (var i = 0; i < lenght; i++) {
        text += possible.charAt(Math.floor(Math.random() * possible.length));
    }

    return text;
}

function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function getHexaColors(num) {
    var colors = new Array();
    for (var i = 0; i < num; i++) {
        do {
            var red = getRandomInt(120, 255);
            var green = getRandomInt(120, 255);
            var blue = getRandomInt(120, 255);
            var rgb = blue | (green << 8) | (red << 16);
        } while (colors.indexOf('#' + rgb.toString(16)) !== -1);
        colors.push('#' + rgb.toString(16));
    }
    return colors;
}

function breakFunction() {
    return null;
}

var componentes = {};
var acciones = {};
var isLoadFirst = false;
componentes.onClickBorrar = function () {
    $("#buscadorFormCriterios").form("clear");
}
componentes.iniciaBuscador = function () {
    var contenedor = $('body');
    //var busc = new GridSearch(toolbarId, btnSearch, "buscatareas");
    $('#buscadorButtonBuscarToolbar').tooltip({
        content: $('#buscadorContenedorGeneral'),
        showEvent: 'click',
        trackMouse: false,
        showDelay: 25,
        onUpdate: function (content) {
            // convertimos nuestros divs a panels
            $('#buscadorContenedorPrincipal').panel();
            $('#buscadorContenedorCriterios').panel({border: false});
            content.panel({border: false});
        },
        onHide: function () {
            contenedor.css('position', '');
            contenedor.children("div.content-disabled-grid-search").remove();
            isTooltipAbierto = false;
        },
        onShow: function () {
            var t = $(this);
            t.unbind().bind('click', function () {
                t.tooltip('show');
            });

            t.tooltip('tip').unbind().bind('mouseenter', function () {
                t.tooltip('show');
            });

            if (isTooltipAbierto)
                return;

            isTooltipAbierto = true;
            var zIndex = t.tooltip('tip').css('z-index') - 1;
            var div = $('<div class="content-disabled-grid-search" style="display:block; z-index: ' + zIndex + '"></div>').appendTo(contenedor);
            div.bind('click', function () {
                t.tooltip('hide');
            });

            t.tooltip('tip').css({
                //backgroundColor: '#E0ECFF',  
                backgroundColor: '#FFFFFF',
                borderColor: '#95B8E7',
                boxShadow: '3px 3px 10px #292929'
            });

            // Asignamos las propiedades a los botones
            $('#buscadorButtonCerrar').linkbutton({iconCls: 'icon-cancel', plain: false});
            var buttonCerrar = document.getElementById('buscadorButtonCerrar');
            buttonCerrar.onclick = function () {
                componentes.onClickCerrar(t);
            };
            $('#buscadorButtonBorrar').linkbutton({iconCls: 'icon-clear', plain: false});
            var buttonCerrar = document.getElementById('buscadorButtonBorrar');
            buttonCerrar.onclick = function () {
                componentes.onClickBorrarFiltro(t);
            };
            $('#buscadorButtonBuscar').linkbutton({iconCls: 'icon-filter', plain: false});  // Asignamos las propiedades del boton
            var buttonSearch = document.getElementById('buscadorButtonBuscar');
            buttonSearch.onclick = function () {
                if (!$('#buscadorFormCriterios').form('validate'))
                    return;
                componentes.onClickBuscar(t);
            };
        },
        onPosition: function (left, top) {
            var _1b3 = $.data(this, "tooltip");
            if (!_1b3 || !_1b3.tip)
                return;

            var tip = _1b3.tip;
            var anchoTooltip = tip._outerWidth();
            var altoTooltip = tip._outerHeight();
            var anchoVentana = $(window)._outerWidth();
            var altoVentana = $(window)._outerHeight();
            var anchoBoton = $(this)._outerWidth();
            var newLeft = left;
            var newArrowLeft = '50%';
            var newTop = $(this).offset().top - (altoTooltip + 12);
            var thisInstance = this;

            if (left < 0) {
                newLeft = 0;
                newArrowLeft = ($(this).offset().left) + (anchoBoton / 2);
            } else if (left + anchoTooltip > anchoVentana) {
                var anchoDerecha = ((left + anchoTooltip) - anchoVentana);
                newLeft = left - anchoDerecha;
                newArrowLeft = ($(this).offset().left - newLeft) + (anchoBoton / 2);
            }

            $(thisInstance).tooltip('arrow').css('display', 'none');
            // - Si el tooltip no entra en la parte inferior y si entra en la parte superior entonces posicionarlo arriba.
            if ((top + altoTooltip) > altoVentana && newTop > 0) { // Top
                setTimeout(function () {
                    tip.removeClass('tooltip-bottom');
                    tip.addClass('tooltip-top');
                    $($(thisInstance).tooltip('arrow')[0]).css('border-top-color', 'rgb(149, 184, 231)');
                    $($(thisInstance).tooltip('arrow')[1]).css('border-top-color', 'rgb(255, 255, 255)');
                    $($(thisInstance).tooltip('arrow')[0]).css('border-bottom-color', '');
                    $($(thisInstance).tooltip('arrow')[1]).css('border-bottom-color', '');
                    $(thisInstance).tooltip('arrow').css('display', 'block');
                }, 0);

            } else { // Bottom
                newTop = top;
                setTimeout(function () {
                    tip.removeClass('tooltip-top');
                    tip.addClass('tooltip-bottom');
                    $($(thisInstance).tooltip('arrow')[0]).css('border-bottom-color', 'rgb(149, 184, 231)');
                    $($(thisInstance).tooltip('arrow')[1]).css('border-bottom-color', 'rgb(255, 255, 255)');
                    $($(thisInstance).tooltip('arrow')[0]).css('border-top-color', '');
                    $($(thisInstance).tooltip('arrow')[1]).css('border-top-color', '');
                    $(thisInstance).tooltip('arrow').css('display', 'block');
                }, 0);
            }

            $(this).tooltip('tip').css('left', newLeft);
            $(this).tooltip('tip').css('top', newTop);
            $(this).tooltip('arrow').css('left', newArrowLeft);
        }
    });
    // No se xq pero el metodo 'update' no se ejecuta
    $('#buscadorButtonBuscarToolbar').tooltip('show');
    $('#buscadorButtonBuscarToolbar').tooltip('hide');
}
componentes.loadGridByName = function (name, response, showTag) {
    if (!isLoadFirst) {
        $("#" + name).datagrid({
            data: response.data
        }).datagrid('clientPaging');
        //$('#dg').datagrid().datagrid('clientPaging');
        isLoadFirst = true;
    } else {
        if (showTag && !isEmpty(response[PARAM_TAG])) {
            $("#buscadorButtonBuscarToolbarInfo").tooltip({
                position: 'right',
                content: '<span style="color:#fff">' + response[PARAM_TAG] + '</span>',
                onShow: function () {
                    $(this).tooltip('tip').css({
                        backgroundColor: '#666',
                        borderColor: '#666'
                    });
                }
            }).tooltip('show');
        } else {
            $("#buscadorButtonBuscarToolbarInfo").tooltip({
                position: 'right',
                content: '<span style="color:#fff">Todo</span>',
                onShow: function () {
                    $(this).tooltip('tip').css({
                        backgroundColor: '#666',
                        borderColor: '#666'
                    });
                }
            }).tooltip('show');
        }
        componentes.existeTooltip = 1;
        $("#" + name).datagrid('loadData', response.data);
    }
}
componentes.loadGrid = function (response, showTag) {
    componentes.loadGridByName('dg', response, showTag);
}
componentes.loadCustomGrid = function (response, showTag, id) {
    if (!isLoadFirst) {
        $(id).datagrid({
            data: response.data
        }).datagrid('clientPaging');
        //$('#dg').datagrid().datagrid('clientPaging');
        isLoadFirst = true;
    } else {
        $(id).datagrid('loadData', response.data);
    }
}
componentes.existeTooltip = 0;
componentes.cerrarTooltipBusqueda = function () {
    if (componentes.existeTooltip == 1)
        $("#buscadorButtonBuscarToolbarInfo").tooltip('hide');
}
componentes.estadoIcono = function (item) {
    if (item.estado == 1) {
        $('#linkEstado' + item.id).linkbutton({
            iconCls: "icon-inactivo",
            plain: true
        });
        $('#linkEstado' + item.id).bind('click', function () {
            acciones.inactivar(item.id);
        });
    } else {
        $('#linkEstado' + item.id).linkbutton({
            iconCls: "icon-activo",
            plain: true
        });
        $('#linkEstado' + item.id).bind('click', function () {
            acciones.activar(item.id);
        });
    }
}
componentes.onClickCerrar = function (t) {
    t.tooltip('hide');
}
componentes.onClickBorrarFiltro = function (t) {
    componentes.onClickBorrar();
    componentes.onClickBuscar(t);
}
componentes.getTagMensaje = function (campo, valor, strMensaje) {
    if (isEmpty(trim(valor)))
        return strMensaje;
    if (strMensaje !== "")
        strMensaje += " | ";
    strMensaje += campo + ": " + valor;
    return strMensaje;
}
componentes.onClickBuscar = function (t) {
    if (!isEmpty(t))
        componentes.onClickCerrar(t);
    buscar();
}
acciones.eliminar = function (id, tag) {
    ax.addParamTmp(PARAM_ACCION_NAME, "cambiarEstadoVisible");
    ax.addParamTmp(PARAM_TAG, tag);
    ax.addParamTmp("id", id);
    ax.addParamTmp("campo", "visible");
    ax.addParamTmp("bandera", 0);
    ax.consumir();
}

acciones.activar = function (id, tag) {
    ax.addParamTmp(PARAM_ACCION_NAME, "cambiarEstadoVisible");
    ax.addParamTmp(PARAM_TAG, tag);
    ax.addParamTmp("id", id);
    ax.addParamTmp("campo", "estado");
    ax.addParamTmp("bandera", 1);
    ax.consumir();
}
acciones.inactivar = function (id, tag) {
    ax.addParamTmp(PARAM_ACCION_NAME, "cambiarEstadoVisible");
    ax.addParamTmp(PARAM_TAG, tag);
    ax.addParamTmp("id", id);
    ax.addParamTmp("campo", "estado");
    ax.addParamTmp("bandera", 0);
    ax.consumir();
}
acciones.iniciaAjax = function (componenteId) {
    ax.addParam(PARAM_COMPONENTE_ID, componenteId);
    ax.addEventListener("onSuccess", onResponseAjaxp);
    ax.addEventListener("onError", acciones.onErrorAjaxp);
}

acciones.iniciaAjaxTest = function (componenteId, functionSuccess) {
    ax.addParam(PARAM_COMPONENTE_ID, componenteId);
    ax.setSuccess(functionSuccess);
    //    ax.addEventListener("onSuccess", functionSuccess);
    ax.addEventListener("onError", acciones.onErrorAjaxp);
}
acciones.onErrorAjaxp = function (event) {
    mostrarMensajeNoty("Error", "Se produjo un error no controlado", MENSAJE_ERROR);
}
var editor = {};
editor.getComboValue = function (dgNombre, index, field) {
    var ed = editor.getEd(dgNombre, index, field);
    return $(ed.target).combobox('getValue');
}
editor.getComboValues = function (dgNombre, index, field) {
    var ed = editor.getEd(dgNombre, index, field);
    return $(ed.target).combobox('getValues');
}
editor.getComboText = function (dgNombre, index, field) {
    var ed = editor.getEd(dgNombre, index, field);
    return $(ed.target).combobox('getText');
}
editor.isEmptyMultiSelect = function (dgNombre, index, field) {
    var ed = editor.getEd(dgNombre, index, field);
    return isEmpty(ed.target.value());
    //return $(ed.target).combobox('getText');
}
editor.getMultiSelectText = function (dgNombre, index, fieldGrid, fieldMulti) {
    if (editor.isEmptyMultiSelect(dgNombre, index, fieldGrid))
        return '';
    var ed = editor.getEd(dgNombre, index, fieldGrid);
    return ed.target.dataItems()[0][fieldMulti];
}
editor.setComboTextGrid = function (dgNombre, index, valueField, textField) {
    $('#' + dgNombre).datagrid('getRows')[index][textField] = editor.getComboText(dgNombre, index, valueField);
}
editor.setMultiSelectGrid = function (dgNombre, index, valueGrid, textGrid, valueMulti, textMulti) {
    $('#' + dgNombre).datagrid('getRows')[index][valueGrid] = editor.getMultiSelectText(dgNombre, index, valueGrid, valueMulti);
    $('#' + dgNombre).datagrid('getRows')[index][textGrid] = editor.getMultiSelectText(dgNombre, index, valueGrid, textMulti);
}
editor.getValue = function (dgNombre, index, field) {
    var ed = editor.getEd(dgNombre, index, field);
    return ed.target.val();
}
editor.getChecked = function (dgNombre, index, field) {
    var ed = editor.getEd(dgNombre, index, field);
    return ed.target[0].checked ? 1 : 0;
}
editor.getEd = function (dgNombre, index, field) {
    return $('#' + dgNombre).datagrid('getEditor', {index: index, field: field});
}
editor.setComboDataValue = function (dgNombre, index, field, data, value) {
    var ed = $("#" + dgNombre).datagrid('getEditor', {index: index, field: field});
    $(ed.target).combobox({data: data});
    $(ed.target).combobox('setValue', value);
}

var datex = {};
datex.formatoImagina = function (date) {
    if (isEmpty(date))
        return;
    var y = date.getFullYear();
    var m = date.getMonth() + 1;
    var d = date.getDate();
    return (d < 10 ? ('0' + d) : d) + '/' + (m < 10 ? ('0' + m) : m) + '/' + y;
}
datex.formatoImaginaDG = function (valor) {
    if (valor == '0000-00-00 00:00:00') {
        return "-";
    } else {
        if (isEmpty(valor))
            return '';
        var ss = (valor.split('-'));
        var d = parseInt(ss[2], 10);
        if (isEmpty(d) || d > 31 || d < 1)
            return "";
        var m = parseInt(ss[1], 10);
        if (isEmpty(m) || m > 12 || m < 1)
            return "";
        var y = parseInt(ss[0], 10);
        if (isEmpty(y) || y > 9999 || y < 1000)
            return "";

        return (d < 10 ? ('0' + d) : d) + '/' + (m < 10 ? ('0' + m) : m) + '/' + y;
    }
}
datex.parserImagina = function (s) {
    if (!s)
        return new Date();
    var ss = (s.split('/'));
    var d = parseInt(ss[0], 10);
    var m = parseInt(ss[1], 10);
    var y = parseInt(ss[2], 10);
    if (!isNaN(y) && !isNaN(m) && !isNaN(d)) {
        return new Date(y, m - 1, d);
    } else {
        return null;
    }
}
datex.parserControlador = function (s) {
    if (isEmpty(s))
        return '';
    var ss = (s.split('/'));
    var d = parseInt(ss[0], 10);
    if (isEmpty(d) || d > 31 || d < 1)
        return "";
    var m = parseInt(ss[1], 10);
    if (isEmpty(m) || m > 12 || m < 1)
        return "";
    var y = parseInt(ss[2], 10);
    if (isEmpty(y) || y > 9999 || y < 1000)
        return "";

    var oFecha = new Date(y, m - 1, d);
    if (!datex.isDateValida(oFecha))
        return "";
    var fecha = y + '-' + (m < 10 ? ('0' + m) : m) + '-' + (d < 10 ? ('0' + d) : d);

    return fecha;
}
datex.isDateValida = function (date) {
    if (Object.prototype.toString.call(date) === "[object Date]") {
        // it is a date
        if (isNaN(date.getTime())) {  // d.valueOf() could also work
            return false;
        } else {
            return true;
        }
    } else {
        return false;
    }
}
datex.setNow = function (dbxNombre) {
    $('#' + dbxNombre).datebox('setValue', datex.getNow1());
}
datex.setNow1 = function (idFecha) {
    $('#' + idFecha).datepicker('setDate', datex.getNow1());
}
datex.getNow1 = function () {
    var date = new Date();
    var y = date.getFullYear();
    var m = date.getMonth() + 1;
    var d = date.getDate();

    return (d < 10 ? ('0' + d) : d) + '/' + (m < 10 ? ('0' + m) : m) + '/' + y;
}
datex.getNow = function () {
    var date = new Date();
    var y = date.getFullYear();
    var m = date.getMonth() + 1;
    var d = date.getDate();

    return y + '-' + (m < 10 ? ('0' + m) : m) + '-' + (d < 10 ? ('0' + d) : d);
}
datex.parserFecha = function (s) {
    var ss = (s.split('-'));
    var y = parseInt(ss[0], 10);
    var m = parseInt(ss[1], 10);
    var d = parseInt(ss[2].substr(0, 2), 10);
    return (d < 10 ? ('0' + d) : d) + '/' + (m < 10 ? ('0' + m) : m) + '/' + y;
}

datex.parserFechaHora = function (s) {
    var ss = (s.split('-'));
    var y = parseInt(ss[0], 10);
    var m = parseInt(ss[1], 10);
    var d = parseInt(ss[2].substr(0, 2), 10);
    var h = ss[2].substr(2, 9);
    return (d < 10 ? ('0' + d) : d) + '/' + (m < 10 ? ('0' + m) : m) + '/' + y + h;
}
datex.setValor = function (componente, valor) {
    if (isEmpty(valor) || valor == '0000-00-00 00:00:00') {
        $("#" + componente).datebox('clear');
    } else {
        $("#" + componente).datebox('setValue', datex.parserFecha(valor));
    }
}
datex.getValor = function (componente) {
    var valor = $("#" + componente).datebox('getValue');
    valor = datex.parserControlador(valor);
    if (valor == "")
        $("#" + componente).datebox('setValue', "");
    return valor;
}
function uploadFileImport(f, n, d) {
    var data = new FormData($(f)[0]);
    var r;
    $.ajax({
        type: 'POST',
        encoding: "UTF-8",
        url: URL_UPLOADHANDLER,
        data: data,
        cache: false,
        async: false,
        contentType: false,
        processData: false,
        success: function (result) {
            r = result;
        }
    });
    $("#file").val('');
    return r;
}
function uploadFile(f, n, d) {
    var r = uploadFileAndResponse(f);
    return (r == -1) ? "" : r;
}

function uploadFileAndResponse(f) {
    var data = new FormData($(f)[0]);
    var r;
    $.ajax({
        type: 'POST',
        encoding: "UTF-8",
        url: URL_UPLOADHANDLER,
        data: data,
        cache: false,
        async: false,
        contentType: false,
        processData: false,
        success: function (result) {
            result = result.split(";");
            if (result[0] == 0) {
                mostrarAdvertencia(result[1]);
                r = -1;
                return;
            }
            r = {
                nombre: result[1],
                url: result[2],
                extension: result[3],
            };
        }
    });
    $("#file").val('');
    return r;
}
var select2 = {
    iniciar: function () {
        $(".select2").select2({
            width: '100%'
        });
        // $(".select2").each(function () {
        //     $(this).width($(this).parent().parent().width());
        // })
    },
    iniciarElemento: function (elemento) {
        $("#" + elemento).select2({
            width: '100%'
        });

        // $(".select2").each(function () {
        //     $(this).width($(this).parent().parent().width());
        // })
    },
    asignarValor: function (id, valor) {
        $("#" + id).select2().select2("val", valor);
        $("#" + id).select2({width: '100%'});

        //   $(".select2").each(function () {
        //     $(this).width($(this).parent().parent().width());
        // })

    },
    asignarValorTrigger: function (id, valor) {
        $("#" + id).select2().select2("val", valor);
        $("#" + id).select2({width: '100%'});
        $("#" + id).trigger('change');
        // $(".select2").each(function () {
        //     $(this).width($(this).parent().parent().width());
        // })
    },
    asignarValorQuitarBuscador: function (id, valor) {
        $("#" + id).select2().select2("val", valor);
        $("#" + id).select2({width: '100%'});
        $('#' + id).select2({
            minimumResultsForSearch: -1
        });
        // $(".select2").each(function () {
        //     $(this).width($(this).parent().parent().width());
        // })
    },
    readonly: function (id, valor) {
        $("#" + id).select2("readonly", valor);
    },
    cargar: function (id, data, val, text) {
        $("#" + id).empty();
        if (isArray(text)) {
            var sText;
            $.each(data, function (index, item) {
                sText = "";
                $.each(text, function (indexText, itemText) {
                    sText += (sText === "") ? item[itemText] : " | " + item[itemText];
                });
                $('#' + id).append('<option value="' + item[val] + '">' + sText + '</option>');
            });
        } else {
            $.each(data, function (index, item) {
                $('#' + id).append('<option value="' + item[val] + '">' + item[text] + '</option>');
            });
        }

    },
    cargarMayusculas: function (id, data, val, text) {
        $("#" + id).empty();
        if (isArray(text)) {
            var sText;
            $.each(data, function (index, item) {
                sText = "";
                $.each(text, function (indexText, itemText) {
                    sText += (sText === "") ? item[itemText] : " | " + item[text].toUpperCase();
                });
                $('#' + id).append('<option value="' + item[val] + '">' + sText + '</option>');
            });
        } else {
            $.each(data, function (index, item) {
                $('#' + id).append('<option value="' + item[val] + '">' + item[text].toUpperCase() + '</option>');
            });
        }

    },
    cargarDataSet: function (id, data, val, text, dataSet) {
        $("#" + id).empty();
        if (!isArray(text)) {
            text = [text];
        } else if (isEmpty(text)) {
            text = [];
        }

        if (!isArray(dataSet)) {
            dataSet = [dataSet];
        } else if (isEmpty(dataSet)) {
            dataSet = [];
        }

        var sText;
        var sDataSet;
        $.each(data, function (index, item) {
            sText = "";
            $.each(text, function (indexText, itemText) {
                sText += (sText === "") ? item[itemText] : " | " + item[itemText];
            });

            sDataSet = "";
            $.each(dataSet, function (indexDataSet, itemDataSet) {
                sDataSet += " data-" + itemDataSet + " = '" + item[itemDataSet]+"'";
            });

            $('#' + id).append('<option value="' + item[val] + '" '+sDataSet+'>' + sText + '</option>');
        });
    },
    cargarDataSetSeleccione: function (id, data, val, text, dataSet, textSeleccione) {
        $("#" + id).empty();
        if (!isArray(text)) {
            text = [text];
        } else if (isEmpty(text)) {
            text = [];
        }

        if (!isArray(dataSet)) {
            dataSet = [dataSet];
        } else if (isEmpty(dataSet)) {
            dataSet = [];
        }

        var sText;
        var sDataSet;
        $('#' + id).append('<option value="-1">' + textSeleccione + '</option>');
        $.each(data, function (index, item) {
            sText = "";
            $.each(text, function (indexText, itemText) {
                sText += (sText === "") ? item[itemText] : " | " + item[itemText];
            });

            sDataSet = "";
            $.each(dataSet, function (indexDataSet, itemDataSet) {
                let datoSet = (!isEmpty(item[itemDataSet]) ? item[itemDataSet] : '');
                sDataSet += "  data-" + itemDataSet + " = '" + datoSet + "'";
            });

            $('#' + id).append('<option value="' + item[val] + '" '+sDataSet+'>' + sText + '</option>');
        });
    },
    cargarSeleccione: function (id, data, val, text, textSeleccione) {//REV.
        $("#" + id).empty();
        $('#' + id).append('<option value="">' + textSeleccione + '</option>');
        if (!isEmpty(data)) {
            if (isArray(text)) {
                var sText;
                $.each(data, function (index, item) {
                    sText = "";
                    $.each(text, function (indexText, itemText) {
                        sText += (sText === "") ? item[itemText] : " | " + item[itemText];
                    });
                    $('#' + id).append('<option value="' + item[val] + '">' + sText + '</option>');
                });
            } else {
                $.each(data, function (index, item) {
                    $('#' + id).append('<option value="' + item[val] + '">' + item[text] + '</option>');
                });
            }
        }
    },
    recargar: function (id, data, val, text) {
        this.limpiar(id);
        this.cargar(id, data, val, text);
    },
    limpiar: function (id) {
        $('#' + id).empty();
    },
    cargarAsignaUnico: function (id, data, val, text) {
        if (!isEmpty(data)) {
            this.cargar(id, data, val, text);
            if (data.length === 1) {
                this.asignarValor(id, data[0][val]);
            }
        }
    },
    obtenerValorDataSet: function (id, text) {
        var data = $("#" + id).select2('data');
        if (isEmpty(data))
            return null; 
        return (data.element[0].dataset.hasOwnProperty(text)) ? data.element[0].dataset[text] : null;
    },
    obtenerValor: function (id) {
        var data = $("#" + id).select2('data');
        if (isEmpty(data))
            return null;
        return (data.hasOwnProperty("id")) ? data.id : null;
    },
    obtenerText: function (id) {
        var data = $("#" + id).select2('data');
        if (isEmpty(data))
            return null;
        return (data.hasOwnProperty("text")) ? data.text : null;
    },
    obtenerTextMultiple: function (id) {
        var cadena = "";
        var data = $("#" + id).select2('data');
        if (isEmpty(data)) {
            return null;
        }
        $.each(data, function (index, item) {

            if (!isEmpty(item.text)) {
                cadena += item.text + ", ";
            }

        });
        if (!isEmpty(cadena)) {
            return cadena.slice(0, -2);
        }
        return null;
        //return (data.hasOwnProperty("text")) ? data.text : null;
    },
    obtenerIdMultiple: function (id) {
        var data = $("#" + id).select2('data');
        var arrayIds = [];
        if (isEmpty(data)) {
            return null;
        }
        $.each(data, function (index, item) {
            if (!isEmpty(item.id)) {
                arrayIds.push(item.id);
            }
        });
        if (!isEmpty(arrayIds)) {
            return arrayIds;
        }
        return null;
    }
}

function cargarDiv(div, url) {
    $('div').remove('.sweet-overlay');
    $('div').remove('.sweet-alert');
    $("#window").html("");
    $(div).load(url);
    setTimeout(function () {
        calculaEspacio();
    }, 3000);
}
function cargarDivTitulo(div, url, titulo) {
    $('div').remove('.sweet-overlay');
    $('div').remove('.sweet-alert');
    $("#window").html("");
    $(div).load(url, function (responseTxt, statusTxt, xhr) {
        if (statusTxt == "success") {
            $('#titulo').empty();
            $('#titulo').append(titulo);
        }
    });
    setTimeout(function () {
        calculaEspacio();
    }, 3000);
}
/**
 * 
 * @param {int} id // el id donde se agregara el titulo
 * @param {string} data // data extra para poner al titulo como (nuevo, editar)
 * @returns {undefined}
 * 
 */
function cargarTitulo(id, data) {
    var titulo = commonVars.titulo;
    $('#' + id).empty();
    if (isEmpty(data)) {
        $('#' + id).append(titulo);
    } else {
        if (!isEmpty(titulo)) {
            titulo = titulo.toLowerCase();
        }
        $('#' + id).append(data + " " + titulo);
    }
}

function validateFechaMayorQue(fechaInicial, fechaFinal) {
    var valuesStart = fechaInicial.split("/");
    var valuesEnd = fechaFinal.split("/");

    // Verificamos que la fecha no sea posterior a la actual
    var dateStart = new Date(valuesStart[2], (valuesStart[1] - 1), valuesStart[0]);
    var dateEnd = new Date(valuesEnd[2], (valuesEnd[1] - 1), valuesEnd[0]);
    if (dateStart > dateEnd) {
        return 0;
    }
    return 1;
}

function formatearCadena(cadena) {
    if (isEmpty(cadena))
        return '';
    return cadena.replace(/<br>/g, "\n");
}

function formatearNumero(numero) {
    numero = parseFloat(numero);
    return (numero).formatMoney(2, '.');
}
function formatearNumeroPorCantidadDecimales(numero, decimales) {
    numero = parseFloat(numero);
    return (numero).formatMoney(decimales, '.');
}

function formatearCantidad(numero) {
    numero = String(numero);
    var arrayNumero = numero.split(".");  // declaro el array  
    var numeroEntero = arrayNumero[0];
    if (!isEmpty(arrayNumero[1])) {
        if (parseInt(arrayNumero[1]) == 0) {
            return numeroEntero;
        } else {
            return formatearNumero(numero);
        }
    } else {
        return numeroEntero;
    }

}

function esNumero(value) {
    value = parseFloat(value);
    return (typeof value === 'number');
}

var datatableJs = {
    obtenerData: function (id) {
        var data = $('#' + id).dataTable().fnGetData();
        return data;
    }
}

function quitarNULL(valor) {
    if (valor == "null" || valor == null) {
        return "";
    }
    return valor;

}

function separarFecha(fecha) {
    var fechaNueva = {dia: 0, mes: 0, anio: 0};

    var fechaPartes = fecha.split("-");

    if (!isEmpty(fechaPartes[0]) && !isEmpty(fechaPartes[1]) && !isEmpty(fechaPartes[2])) {

        fechaNueva.anio = fechaPartes[0];
        fechaNueva.mes = fechaPartes[1];

        if (fechaPartes[2].length >= 2) {
            fechaNueva.dia = fechaPartes[2].substr(0, 2);
        }
    }
    return fechaNueva;
}

function formatearFechaBDCadena(fecha) {
    var fechaNueva = {dia: 0, mes: 0, anio: 0};

    var fechaPartes = fecha.split("-");

    if (!isEmpty(fechaPartes[0]) && !isEmpty(fechaPartes[1]) && !isEmpty(fechaPartes[2])) {

        fechaNueva.anio = fechaPartes[0];
        fechaNueva.mes = fechaPartes[1];

        if (fechaPartes[2].length >= 2) {
            fechaNueva.dia = fechaPartes[2].substr(0, 2);
        }
    }
    return fechaNueva.dia + "/" + fechaNueva.mes + "/" + fechaNueva.anio;
}


function StringNegrita(cadena) {
    return "<b>" + cadena + "</b>";
}

function redondearNumeroXDecimales(numero, decimales = 2) {
    return (Math.round(numero * Math.pow(100, decimales)) / Math.pow(100, decimales)).toFixed(decimales);
}

function redondearNumero(numero) {
    //    return (Math.round(numero * 100) / 100).toFixed(2);
    return Math.round(numero * 100) / 100;
}

function redondearDosDecimales(num) {
    return redondearNumero(num).toFixed(2);
}


function formatearFechaJS(fecha) {
    if (isEmpty(fecha))
        return '';

    var date = new Date(fecha);
    if ($.type(date) !== JSType.DATE)
        return '';

    var nYear = date.getFullYear();
    var nMonth = date.getMonth() + 1;
    var nDay = date.getDate();
    //    var nHour = date.getHours();
    //    var nMinute = date.getMinutes();
    //    var nSecond = date.getSeconds();

    var year = nYear.toString();
    var month = (nMonth < 10 ? '0' : '') + nMonth.toString();
    var day = (nDay < 10 ? '0' : '') + nDay.toString();
    //    var hour = (nHour < 10 ? '0' : '') + nHour.toString();
    //    var minute = (nMinute < 10 ? '0' : '') + nMinute.toString();
    //    var second = (nSecond < 10 ? '0' : '') + nSecond.toString();

    //    return day + '/' + month + '/' + year + ' ' + hour + ':' + minute + ':' + second;
    return day + '/' + month + '/' + year;
}


function validarEmail(sEmail) {
    var filtro = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
    if (filtro.test(sEmail)) {
        return true;
    } else {
        return false;
    }
}

function fixCorreo(correo) {
    var last = correo.charAt(correo.length - 1);
    //console.log("Correo: " + correo);
    var fixed = correo;
    if (last != ";" && correo != '') {
        fixed += ";";
    }
    return fixed;
}

function copiarArray(arr) {
    var new_arr = arr.slice(0);
    for (var i = new_arr.length; i--; )
        if (new_arr[i] instanceof Array)
            new_arr[i] = copy(new_arr[i]);
    return new_arr;
}


//ancho de tabla:

function setearAnchoTabla(tablaId) {
    var ancho = $('#' + tablaId).css("width");
    $('#' + tablaId).attr("width", ancho);
}

function cambioTamanioVentanaAnchoTabla(tablaId) {
    $(window).resize(function () {
        $('#' + tablaId).attr("width", '100%');
        setTimeout(function () {
            $('#' + tablaId).attr("width", $('#' + tablaId).css("width"));
            $('#' + tablaId).attr("style", "width: " + $('#' + tablaId).css("width") + ";");
        }, 1000);
    });
}

function modificarAnchoTabla(tablaId) {
    setearAnchoTabla(tablaId);
    cambioTamanioVentanaAnchoTabla(tablaId);
}
var datePiker = {
    iniciar: function (elemento) {
        $(elemento).datepicker({
            isRTL: false,
            format: 'dd/mm/yyyy',
            autoclose: true,
            language: 'es',
            immediateUpdates: true,
            todayBtn: true,
            todayHighlight: true
        });
    },
    iniciarPorClase: function (clase) {
        this.iniciar('.' + clase);
    },
    iniciarPorElemento: function (elemento) {
        this.iniciar('#' + elemento);
    }
}

function sumaFecha(d, fecha) {
    var Fecha = new Date();
    var sFecha = fecha || (Fecha.getDate() + "/" + (Fecha.getMonth() + 1) + "/" + Fecha.getFullYear());
    var sep = sFecha.indexOf('/') != -1 ? '/' : '-';
    var aFecha = sFecha.split(sep);
    var fecha = aFecha[2] + '/' + aFecha[1] + '/' + aFecha[0];
    fecha = new Date(fecha);
    fecha.setDate(fecha.getDate() + parseInt(d));
    var anno = fecha.getFullYear();
    var mes = fecha.getMonth() + 1;
    var dia = fecha.getDate();
    mes = (mes < 10) ? ("0" + mes) : mes;
    dia = (dia < 10) ? ("0" + dia) : dia;
    var fechaFinal = dia + sep + mes + sep + anno;
    return (fechaFinal);
}


function filerArrayOfElement(data, text, id, flag, operator) {

    if (isEmpty(data) || isEmpty(id) || isEmpty(text)) {
        return [];
    }

    let symbol = ` == `;
    if (flag === 0) {
        symbol = ` != `;
    }

    if (isEmpty(operator)) {
        operator = ` && `;
    }

    let string = ``;
    if (isArray(text)) {
        string = ` item.` + text[0] + symbol + `'` + id[0] + `'`;
        for (let index = 1; index < text.length; index++) {
            string += ` ` + operator + ` item.` + text[index] + symbol + `'` + id[index] + `'`;
        }
    } else {
        string = `item.` + text + symbol + `'` + id + `'`;
    }

    let dataFilter = [];
    string = `dataFilter = data.filter(item => ` + string + `)`;
    eval(string);
    return dataFilter;
}
// window.addEventListener('resize', function(event) {
//     $(".select2").each(function () {
//         $(this).width($(this).parent().parent().width());
//     })
// }, true);


function isNumeric(evt) {
    var code = (evt.which) ? evt.which : evt.keyCode;

    if (code == 8) { // backspace.
        return true;
    } else if (code >= 48 && code <= 57) { // is a number.
        return true;
    } else { // other keys.
        return false;
    }
}


