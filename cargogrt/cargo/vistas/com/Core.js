/* 
 * @author Christopher Heredia
 * Prototipo Core
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function Core(content_div, name_element){
    //Atributos de la clase
    this._content_div=content_div;
    this._name_element=name_element;
    this._property_changes = {};
}

// metodo que agrega cambios de las propiedades al arreglo
Core.prototype.addPropertyChanges=function(property_name){
    this._property_changes[property_name] = true;
    //eval("this._property_changes."+property_name+"=true;");
}

// metodo que limpia el arreglo
Core.prototype.clearPropertyChanges=function(){
    //this._property_changes = 'undefinedññ';
    //this._property_changes = null;
    delete this._property_changes;
    this._property_changes = {};
}

// metodo que devuelve el arreglo
Core.prototype.getPropertyChanges=function(){
    return this._property_changes;
}

// metodo que valida si existe una propiedad que haya cambiado
Core.prototype.existsPropertyChanges=function(property_name){
    if(this._property_changes !== undefined && this._property_changes !== null){
        return (this._property_changes[property_name] !== undefined)? true:false;
    }else{
        return false;
    }
}