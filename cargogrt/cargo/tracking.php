 <!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>  
        <script src="vistas/libs/imagina/js/jquery.js"></script>
        <script src="vistas/libs/imagina/js/bootstrap.min.js"></script>
        <script src="vistas/libs/imagina/assets/sweetalert2/sweetalert.min.js"></script>
        <link href="vistas/libs/imagina/css/bootstrap.min.css" rel="stylesheet">
        <link href="vistas/libs/imagina/css/bootstrap-reset.css" rel="stylesheet">
        <link href="vistas/libs/imagina/css/style-responsive.css" rel="stylesheet" />
        
        <form id="formRedirecciona" action="ConsultaQRTracking.php" method="post">
            <input type="hidden" id="parametro" name="parametro" />
        </form>
        <script>
            
            var url;
            var lista = [];
            
            $(document).ready(function () {
                //debugger;
                url = window.location;
                var dat = new datosBusqueda(url);
                lista.push(dat);
                var urljason = JSON.stringify(lista);
                $("#parametro").val(urljason);
                document.forms[0].submit();
            });
            
            function datosBusqueda(url)
            {
                this.url = url;
            }
            //document.oncontextmenu = function(){return false};
        </script>    
    </body>
</html>
