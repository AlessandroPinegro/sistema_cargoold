if ($.type(String.prototype.trim) !== JSType.FUNCTION) {
    String.prototype.trim = function() {
        return this.replace(/^\s+|\s+$/g, "");
    };
}

if ($.type(String.prototype.trimLeft) !== JSType.FUNCTION) {
    String.prototype.trimLeft = function() {
        return this.replace(/^\s+/, "");
    };
}

if ($.type(String.prototype.trimRight) !== JSType.FUNCTION) {
    String.prototype.trimRight = function() {
        return this.replace(/\s+$/, "");
    };
}

function trim(value)
{
    if ($.type(value) === JSType.UNDEFINED) return '';
    if (value === null) return '';
    
    var str = String(value);
    return str.trim();
}

function trimLeft(value)
{
    if ($.type(value) === JSType.UNDEFINED) return '';
    if (value === null) return '';
    
    var str = String(value);
    return str.trimLeft();
}

function trimRight(value)
{
    if ($.type(value) === JSType.UNDEFINED) return '';
    if (value === null) return '';
    
    var str = String(value);
    return str.trimRight();
}

String.prototype.format = function() {
    var formatted = this;
    for(arg in arguments) {
        formatted = formatted.replace("{" + arg + "}", arguments[arg]);
    }
    return formatted;
};