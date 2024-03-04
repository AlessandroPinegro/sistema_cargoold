//Obtenemos el objeto DOM del Location
function getDOMLocation()
{
    if (document.location !== null)
        return document.location;
    else if (window.location !== null)
        return window.location;
    
    return null;
}

//Obtenemos la ruta actual del archivo en ejecución
function getCurrentPagePath()
{
    //Obtenemos el Objeto DOM del Location
    var DOMLocation = getDOMLocation();
    if (DOMLocation === null) return '';
    
    //Sacamos el FULLPATH del archivo (incluyendo el nombre del archivo)
    var fullpath;
    if (DOMLocation.href !== null && trim(DOMLocation.href) !== '')
        fullpath = DOMLocation.href;
    else 
        fullpath = DOMLocation.origin + DOMLocation.pathname;
    
    return fullpath.substring(0, fullpath.lastIndexOf('/')+1);
}

//Obtenemos el nombre del archivo en ejecución
function getCurrentPageName()
{
    //Obtenemos el Objeto DOM del Location
    var DOMLocation = getDOMLocation();
    if (DOMLocation === null) return '';
    
    //Sacamos el FULLPATH del archivo (incluyendo el nombre del archivo)
    var fullpath;
    if (DOMLocation.href !== null && trim(DOMLocation.href) !== '')
        fullpath = DOMLocation.href;
    else 
        fullpath = DOMLocation.origin + DOMLocation.pathname;
    
    return fullpath.substring(fullpath.lastIndexOf('/')+1);
}

//Buscamos la ruta del script
function getPathOfScript(scriptname)
{
    if ($.type(scriptname) === JSType.UNDEFINED) return '';
    
    var searchExp = new RegExp(scriptname +'\\.js$');
    var replaceExp = new RegExp('(.*)'+ scriptname +'\\.js$');
    
    var scripts = document.getElementsByTagName('SCRIPT');
    var path = '';
    if(scripts && scripts.length>0) {
        for(var i = 0; i < scripts.length; i++) {
            if(scripts[i].src && scripts[i].src.match(searchExp)) {
                path = scripts[i].src.replace(replaceExp, '$1');
            }
        }
    }
    return path;
}

function pagerFilter(data)
{  
    if ($.type(data.length) === JSType.NUMBER && $.type(data.splice) === JSType.FUNCTION){    // is array  
        data = {  
            total: data.length,  
            rows: data  
        };
    }

    var dg = $(this);  
    var opts = dg.datagrid('options');  
    var pager = dg.datagrid('getPager');

    pager.pagination({  
        onSelectPage:function(pageNum, pageSize){  
            opts.pageNumber = pageNum;  
            opts.pageSize = pageSize;  
            pager.pagination('refresh',{  
                pageNumber:pageNum,  
                pageSize:pageSize  
            });  
            dg.datagrid('loadData',data);  
        }  
    });  
    if (!data.originalRows){  
        data.originalRows = (data.rows);  
    }  
    var start = (opts.pageNumber-1)*parseInt(opts.pageSize);  
    var end = start + parseInt(opts.pageSize);  
    data.rows = (data.originalRows.slice(start, end));  

    return data;  
}

var SBSDatagrid = {};

SBSDatagrid.extend = function($dg, options)
{
    var lastRowIndexSelected = -1;
    var opts = $.extend({}, $dg.datagrid('options'));
    
    $dg.datagrid($.extend({}, opts, {
            onSelect:function(rowIndex, rowData) {
                var row = opts.finder.getRow(this, rowIndex);
                opts.onSelect.call(this, rowIndex, row);
                if(options.selectOnlyOneRow === true) {
                    selectOnlyOneRow(rowIndex);
                }                
                lastRowIndexSelected = rowIndex;
            },
            onUnselect:function(rowIndex, rowData) {
                var row = opts.finder.getRow(this, rowIndex);
                opts.onUnselect.call(this, rowIndex, row);
                if(options.selectOnlyOneRow === true) {
                    selectOnlyOneRow(rowIndex);
                }                
                lastRowIndexSelected = rowIndex;
            },
            onRowContextMenu:function(e, rowIndex) {
                var row = opts.finder.getRow(this, rowIndex);
                opts.onRowContextMenu.call(this, e, rowIndex, row);
                if(options.selectOnlyOneRow === true) {
                    selectOnlyOneRow(rowIndex);
                }
                lastRowIndexSelected = rowIndex;
            },
            onCheckAll:function() {
                var rows = $dg.datagrid('getData');
                opts.onCheckAll.call(this, rows);
                if(options.selectOnlyOneRow === true) {
                    selectOnlyOneRow(lastRowIndexSelected);
                }
            },
            onUncheckAll:function() {
                var rows = $dg.datagrid('getData');
                opts.onUncheckAll.call(this, rows);
                if(options.selectOnlyOneRow === true) {
                    selectOnlyOneRow(lastRowIndexSelected);
                }
            },
            onLoadSuccess:function(data) {
                opts.onLoadSuccess.call(this, data);
                lastRowIndexSelected = -1;
            }
        }));
    
    
    function selectOnlyOneRow(rowIndex)
    {
        var prev = $dg.parent();
        //$dg.datagrid('clearSelections');
        //$dg.datagrid('highlightRow', rowIndex);
        
//        prev.find('div.datagrid-body table.datagrid-btable tr.datagrid-row-selected').removeClass('datagrid-row-selected');
//        prev.find('div.datagrid-body table.datagrid-btable [datagrid-row-index="' + rowIndex + '"]').addClass('datagrid-row-selected');

        prev.find('div.datagrid-body table tr.datagrid-row-selected').removeClass('datagrid-row-selected');
        prev.find('div.datagrid-body table [datagrid-row-index="' + rowIndex + '"]').addClass('datagrid-row-selected');

    };
};

SBSDatagrid.updateRowNumbers = function($dg)
{
    var optsPager = $dg.datagrid('getPager').pagination('options');
    var options = $dg.datagrid('options');
    options.pageNumber = optsPager.pageNumber;
    options.pageSize = optsPager.pageSize;
};

$.ajaxSetup({ cache: false });

var escapeRegExp;

(function () {
  // Referring to the table here:
  // https://developer.mozilla.org/en/JavaScript/Reference/Global_Objects/regexp
  // these characters should be escaped
  // \ ^ $ * + ? . ( ) | { } [ ]
  // These characters only have special meaning inside of brackets
  // they do not need to be escaped, but they MAY be escaped
  // without any adverse effects (to the best of my knowledge and casual testing)
  // : ! , = 
  // my test "~!@#$%^&*(){}[]`/=?+\|-_;:'\",<.>".match(/[\#]/g)

  var specials = [
        // order matters for these
          "-"
        , "["
        , "]"
        // order doesn't matter for any of these
        , "/"
        , "{"
        , "}"
        , "("
        , ")"
        , "*"
        , "+"
        , "?"
        , "."
        , "\\"
        , "^"
        , "$"
        , "|"
      ]

      // I choose to escape every character with '\'
      // even though only some strictly require it when inside of []
    , regex = RegExp('[' + specials.join('\\') + ']', 'g')
    ;

  escapeRegExp = function (str) {
    return str.replace(regex, "\\$&");
  };

  // test escapeRegExp("/path/to/res?search=this.that")
}());