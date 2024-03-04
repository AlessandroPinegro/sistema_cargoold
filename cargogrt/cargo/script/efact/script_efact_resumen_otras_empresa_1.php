<?php

include_once __DIR__ . '/../../modelo/almacen/Persona.php';
include_once __DIR__ . '/../../modeloNegocio/almacen/MovimientoNegocio.php';
try {
/*
    $documentoGenera = array();
    $documentoGenera['total'] = 1130.00;
    $documentoGenera['subtotal'] = 957.63;
    $documentoGenera['igv'] = 172.37;

    $documentoGenera['sunat_tipo_documento'] = '03'; 
    $documentoGenera['serie'] = 'BB01';
    $documentoGenera['numero'] = '00006684';
    $documentoGenera["sunat_moneda"] = 'PEN';    
    $documentoGenera['receptor_codigo_identificacion'] = '06804278';
    $documentoGenera['receptor_sunat_tipo_documento'] = '1';         

    $documentosResumen[] = $documentoGenera;
    */
    
    $documentoGenera = array();
    $documentoGenera['total'] = 42.00;
    $documentoGenera['subtotal'] = 35.59;
    $documentoGenera['igv'] = 6.41;

    $documentoGenera['sunat_tipo_documento'] = '03'; 
    $documentoGenera['serie'] = 'BB01';
    $documentoGenera['numero'] = '00006706';
    $documentoGenera["sunat_moneda"] = 'PEN';    
    $documentoGenera['receptor_codigo_identificacion'] = '80123900';
    $documentoGenera['receptor_sunat_tipo_documento'] = '1';      

    $documentosResumen[] = $documentoGenera;
    
    //obtenemos id de documentos que se enviaran a resumen                
//        $documentosResumen = DocumentoNegocio::create()->obtenerIdDocumentosResumenDiario();


    if (ObjectUtil::isEmpty($documentosResumen)) {
        throw new WarningException("No se encontraron documentos para generar por resumen diario");
    }

    $i = 0;
    foreach ($documentosResumen as $index => $fila) {

        $montoTotal = $fila["total"] * 1.0;
        $montoIgv = $fila["igv"] * 1.0;
        $montoAfecto = $fila["subtotal"] * 1.0;
        $montoGratuito = 0;
        $items[$index][0] = $i + 1; //Número de fila
        $serieDoc = $fila["serie"];
        $numeroDoc = $fila["numero"];
        $items[$index][1] = $serieDoc . '-' . $numeroDoc; //Número de serie del documento – Numero correlativo 
        $items[$index][2] = $fila["sunat_tipo_documento"]; //Tipo de documento
        $items[$index][3] = $fila["sunat_moneda"]; //moneda


        $items[$index][4] = $fila["receptor_codigo_identificacion"]; //Número de documento de Identidad del adquirente o usuario
        $items[$index][5] = $fila["receptor_sunat_tipo_documento"]; //Tipo de documento de Identidad del adquirente o usuario
        $items[$index][6] = 2; //Estado del ítem (1 es generar)
        $items[$index][7] = $montoTotal; //Importe total de la venta                 
        $items[$index][8] = $montoAfecto; //Total valor de venta - operaciones gravadas
        $items[$index][9] = 0.0; //Total valor de venta - operaciones exoneradas
        $items[$index][10] = 0.0; //Total valor de venta - operaciones inafectas
        $items[$index][11] = $montoGratuito; //Total Valor Venta operaciones Gratuitas
        $items[$index][12] = $montoIgv;  //Total IGV

        $items[$index][13] = null; //nroDocumentoRelacionado 
        $items[$index][14] = null; //tipoDocumentoRelacionado        
        $i++;
    }
    // Obtenemos Datos de emisor
//    $documento = DocumentoNegocio::create()->obtenerDocumentoFEXId($documentosResumen[0]["documentoId"]);
//    $empresa = EmpresaNegocio::create()->obtenerEmpresaXId($documento[0]['empresa_id']);
//    $ubigeoEmpresa = PersonaNegocio::create()->obtenerUbigeoXId($empresa[0]["ubigeo_id"]);

    $comprobanteElectronico->emisorNroDocumento = "20531807520";
    $comprobanteElectronico->emisorTipoDocumento = "6";
    $comprobanteElectronico->emisorUbigeo = "021809";
    $comprobanteElectronico->emisorDireccion = "Jr. Samanco N° 320";
    $comprobanteElectronico->emisorUrbanizacion = "Buenos Aires";
    $comprobanteElectronico->emisorDepartamento = "Áncash";
    $comprobanteElectronico->emisorProvincia = "Santa";
    $comprobanteElectronico->emisorDistrito = "Nuevo Chimbote";
    $comprobanteElectronico->emisorNombreLegal = "INVERSIONES HOTELERAS EIRL";
    $comprobanteElectronico->emisorNombreComercial = "INVERSIONES HOTELERAS EIRL";

    $comprobanteElectronico->docFechaEmision = "2021-08-05"; //$documento[0]["fecha_emision"];       
    $comprobanteElectronico->docFechaReferencia = "2021-08-05";
    $comprobanteElectronico->docSecuencial = 2;

    $comprobanteElectronico->resumenes = $items;
    $comprobanteElectronico->usuarioSunatSOL = "MARESTA2";
    $comprobanteElectronico->claveSunatSOL = "Maresta2018";
    $comprobanteElectronico->usuarioOSE = ''; // Configuraciones::EFACT_USER_OSE;
    $comprobanteElectronico->claveOSE = ''; // Configuraciones::EFACT_PASS_OSE;
    $comprobanteElectronico = (array) $comprobanteElectronico;
//    var_dump($comprobanteElectronico);
//    exit();

    $client = MovimientoNegocio::create()->conexionEFAC();

    $resultado = $client->procesarResumenDiarioNuevo($comprobanteElectronico)->procesarResumenDiarioNuevoResult;
    //        VALIDAR EL RESULTADO
//    $this->validarResultadoEfactura($resultado);
    var_dump($comprobanteElectronico);

    if (strpos($resultado, 'ticket') !== false) {
        $nroticket = explode(':', $resultado);
        $ticket = trim($nroticket[2]);
    }



//    for ($j = 0; $j < count($idDocumentos); $j++) {
//        DocumentoNegocio::create()->actualizarEstadoEfactAnulacionXDocumentoId($idDocumentos[$j], NULL, $ticket);
//        DocumentoNegocio::create()->actualizarEfactEstadoRegistro($idDocumentos[$j], 1);
//    }
} catch (Exception $ex) {
    echo $ex->getMessage();
}