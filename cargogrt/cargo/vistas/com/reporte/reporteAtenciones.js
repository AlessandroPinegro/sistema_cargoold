function wait(ms) {
    var deferred = $.Deferred();
    setTimeout(deferred.resolve, ms);

    return deferred.promise();
}

wait(1500).then(function () {
    //conectar los divs ergo dibujar el mapa
    graficar();
});

// Tal vez la conexion sea lenta, dibujamos de nuevo 5 s despues
wait(5000).then(function () {
    graficar();
});



function connect(div1, div2, color, thickness) {
    var off1 = getOffset(div1);
    var off2 = getOffset(div2);
    // centro
    var x1 = off1.left + off1.width;
    var y1 = off1.top + off1.height/2;
    // centro
    var x2 = off2.left; //+ off2.width;
    var y2 = off2.top+off1.height/2;
    // distance
    var length = Math.sqrt(((x2-x1) * (x2-x1)) + ((y2-y1) * (y2-y1)));
    // center
    var cx = ((x1 + x2) / 2) - (length / 2);
    var cy = ((y1 + y2) / 2) - (thickness / 2);
    // angle
    var angle = Math.atan2((y1-y2),(x1-x2))*(180/Math.PI);
    // make hr
    var htmlLine = "<div style='padding:0px; margin:0px; height:" + thickness + "px; background-color:" + color + "; line-height:1px;-moz-border-radius: 5px;border-radius: 5px; position:absolute; left:" + cx + "px; top:" + cy + "px; width:" + length + "px; -moz-transform:rotate(" + angle + "deg); -webkit-transform:rotate(" + angle + "deg); -o-transform:rotate(" + angle + "deg); -ms-transform:rotate(" + angle + "deg); transform:rotate(" + angle + "deg);' />";
    //
    //alert(htmlLine);
    // document.body.innerHTML += htmlLine;
    var oldContent = $("#contenedorMapaAtencion").html();
    $("#contenedorMapaAtencion").html(oldContent+htmlLine);
}

function getOffset( el ) {
    var rect = el.getBoundingClientRect();
    return {
        left: rect.left + window.pageXOffset,
        top: rect.top + window.pageYOffset,
        width: rect.width || el.offsetWidth,
        height: rect.height || el.offsetHeight
    };
}



function graficar()
{
    var pendientes="";
    var prefunc = "connect(";
    var pre = "document.getElementById('";
    var post  = "'),";
    var final = "'#407F7F',4);";
    //MEJORAR
    var allDivs = $(".divsMapa");
    $.each(allDivs, function(index, value){
        pendientes += prefunc;
        pendientes += pre;
        pendientes += $(this).attr('id');
        pendientes += post;
        pendientes += pre;
        pendientes += 'div2';
        pendientes += post;
        pendientes += final;
    });
    eval(pendientes);    pendientes="";

    var newallDivs = $(".divsMapaDerecha");
    $.each(newallDivs, function(index, value){
        pendientes += prefunc;
        pendientes += pre;
        pendientes += 'div2';
        pendientes += post;
        pendientes += pre;
        pendientes += $(this).attr('id');
        pendientes += post;
        pendientes += final;
    });
    eval(pendientes); pendientes="";
}

