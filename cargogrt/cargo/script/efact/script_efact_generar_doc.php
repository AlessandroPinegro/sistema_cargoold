<?php

include_once __DIR__ . '/../../modelo/almacen/Persona.php';
include_once __DIR__ . '/../../modeloNegocio/almacen/MovimientoNegocio.php';
try {
    
        $totalDocumento = 33.60;
        $monedaId = 2;
        
        $enLetras = new EnLetras();
        $importeLetras = $enLetras->ValorEnLetras($totalDocumento, $monedaId);

        $comprobanteElectronico->emisorNroDocumento = "20481409714";
        $comprobanteElectronico->emisorTipoDocumento = "6";
        $comprobanteElectronico->emisorUbigeo = "130101";
        $comprobanteElectronico->emisorDireccion = "Av.Nicolas de Piérola Nª1402 Urb. Mochica La Libertad - Trujillo - Trujillo";
        $comprobanteElectronico->emisorUrbanizacion = "Californa";
        $comprobanteElectronico->emisorDepartamento = "LIBERTAD";
        $comprobanteElectronico->emisorProvincia = "TRUJILLO";
        $comprobanteElectronico->emisorDistrito = "TRUJILLO";
        $comprobanteElectronico->emisorNombreLegal = "DISTRIBUIDORA FARMACOS DEL NORTE SAC";
        $comprobanteElectronico->emisorNombreComercial = "DISTRIBUIDORA FARMACOS DEL NORTE SAC";
        $comprobanteElectronico->emisorNroTelefono = "044283323";

        // receptor
        $comprobanteElectronico->receptorNroDocumento = "10481939882";
        $comprobanteElectronico->receptorTipoDocumento = "6";
        $comprobanteElectronico->receptorNombreLegal = "PEREZ RUIZ NELLY LIZZETH";
        $comprobanteElectronico->receptorUbigeo = "130101";
        $comprobanteElectronico->receptorDireccion = "AV CESAR VALLEJO MCDO MAYORISTA PUESTO H-14 TRUJILLO , Trujillo, Trujillo - La Libertad";
        $comprobanteElectronico->receptorUrbanizacion = '';
        $comprobanteElectronico->receptorDepartamento = "LIBERTAD";
        $comprobanteElectronico->receptorProvincia = "TRUJILLO";
        $comprobanteElectronico->receptorDistrito = "TRUJILLO";

        $comprobanteElectronico->receptorEmail = "cefn530@gmail.com";
  

        $comprobanteElectronico->docSerie = "F004";
        $comprobanteElectronico->docNumero = "012140";
        $comprobanteElectronico->docFechaEmision = "2021-08-31";
        $comprobanteElectronico->docMoneda = "PEN";
        $comprobanteElectronico->docMontoEnLetras = $importeLetras; //'TRES MIL OCHOCIENTOS TREINTA Y CINCO SOLES CON 0/100';
        $comprobanteElectronico->docTotalIgv = 6.05;
        $comprobanteElectronico->docTotalVenta = 39.65;
        $comprobanteElectronico->docGravadas = 33.60;
        $comprobanteElectronico->docExoneradas = 0.0;
        $comprobanteElectronico->docInafectas = 0.0;
        $comprobanteElectronico->docGratuitas = 0.0;
        $comprobanteElectronico->docDescuentoGlobal = 0.0;
        
        // Detalle
        $index = 0;
        $items[$index][0] = $index + 1;
        $items[$index][1] = 3.00; // Cantidad
        $items[$index][2] = 11.17; // Precio unitario
        $items[$index][3] = 13.21; //Precio refencial
        $items[$index][4] = "01"; //Tipos de precio
        $items[$index][5] = "83938";
        $items[$index][6] = "ELVIVE SH.OL.EXTR.NUT.UNIV.X400 GR";
        $items[$index][7] = "NIU";
        $items[$index][8] = 6.05; //Impuesto
        $items[$index][9] = '10'; //Tipo de impuesto
        $items[$index][10] = 33.59; // Total Venta
        $items[$index][11] = 0; //Descuento
        $items[$index][12] = Configuraciones::IGV_PORCENTAJE; //porcentaje IGV
        
        $comprobanteElectronico->items = $items;      

        $discrepancias = array();
        $itemDiscrepancia[0] = "PRODUCTO EN MAL ESTADO";
        $itemDiscrepancia[1] = "F004-083579";
        $itemDiscrepancia[2] = "07";
        array_push($discrepancias, $itemDiscrepancia);
        
        $comprobanteElectronico->discrepancias = array($discrepancias[0]);

        $docRelacionados = array();
        $itemRelacion[0] = "F004-083579";
        $itemRelacion[1] = "01";
        array_push($docRelacionados, $itemRelacion);

        $comprobanteElectronico->docRelacionados = array($docRelacionados[0]);

        $comprobanteElectronico->usuarioSunatSOL = "";
        $comprobanteElectronico->claveSunatSOL = "";
        $comprobanteElectronico->usuarioOSE = "";
        $comprobanteElectronico->claveOSE = "";
        $comprobanteElectronico = (array) $comprobanteElectronico;

        $client = MovimientoNegocio::create()->conexionEFAC();

        try {
            $resultado = $client->procesarNotaCredito($comprobanteElectronico)->procesarNotaCreditoResult;
        } catch (Exception $e) {
            $resultado = $e->getMessage();
        }    
        
        echo $resultado;    
    } 
catch (Exception $ex) {
    echo $ex->getMessage();
}