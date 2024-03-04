<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <link rel="shortcut icon" href="vistas/images/icono_ittsa.ico">

        <title>ITTSA CARGO - INTERNACIONAL DE TRANSPORTE TURISTICO Y SERVICIOS SRL</title>
    </head>
    <body onload="history.replaceState(null, '', 'pdf')" style="overflow: hidden;"> 
        <iframe src="<?php echo $_GET['url_pdf']; ?>" 
                width="100%" height="100%" id="PDFtoPrint">

        </iframe>
    </body>

    <script type="text/javascript" language="javascript" src="vistas/libs/imagina/js/jquery.js"></script>

    <script>
//        $(document).ready(function () {
//            document.getElementById('PDFtoPrint').focus();
//            document.getElementById('PDFtoPrint').contentWindow.print();
//        });
    </script>

</html>
