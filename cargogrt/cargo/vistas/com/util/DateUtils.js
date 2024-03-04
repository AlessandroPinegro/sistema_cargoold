/* 
 * 
 * @version 1.0
 * @copyright (c) 2013, IMAGINA TECHNOLOGIES S.A.C.
 * 
 * @abstract JSScript Base que contiene las funciones utilitarias de los dates
 */
var DateUtils = { };

DateUtils.now = function ()
{
    return new Date();
};

DateUtils.toSbsFechaHoraFormat = function (date)
{
    if (isEmpty(date)) return '';
    if ($.type(date) !== JSType.DATE) return '';
    
    var nYear = date.getFullYear();
    var nMonth = date.getMonth()+1;
    var nDay = date.getDate();
    var nHour = date.getHours();
    var nMinute = date.getMinutes();
    var nSecond = date.getSeconds();
    
    var year = nYear.toString();
    var month = (nMonth < 10 ? '0' : '') + nMonth.toString();
    var day = (nDay < 10 ? '0' : '') + nDay.toString();
    var hour = (nHour < 10 ? '0' : '') + nHour.toString();
    var minute = (nMinute < 10 ? '0' : '') + nMinute.toString();
    var second = (nSecond < 10 ? '0' : '') + nSecond.toString();
    
    return day + '/' + month + '/' + year + ' ' + hour + ':' + minute + ':' + second;
};