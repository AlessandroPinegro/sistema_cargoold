<?php
include_once __DIR__ . '/../../util/Configuraciones.php';

try {

    //IMV
//    $comprobanteElectronico->emisorNroDocumento = '20481842515';
//    $comprobanteElectronico->docNroTicket = '1539382542296';
//    $comprobanteElectronico->usuarioSunatSOL = 'INDU2515';
//    $comprobanteElectronico->claveSunatSOL = 'industrial2515';

    //JR
//    $comprobanteElectronico->emisorNroDocumento = '20215098509';
//    $comprobanteElectronico->docNroTicket = '201802764778323';//OK
//    $comprobanteElectronico->usuarioSunatSOL = '3FACTUR4';
//    $comprobanteElectronico->claveSunatSOL = 'l4f4ctur3';
    
    //DVM
    $comprobanteElectronico->emisorNroDocumento = '20603228406';
    $comprobanteElectronico->docNroTicket = '201802786646336';//MAL
    $comprobanteElectronico->usuarioSunatSOL = 'FACTURA1';
    $comprobanteElectronico->claveSunatSOL = 'FACTURAE';

    $comprobanteElectronico = (array) $comprobanteElectronico;

    $client = new SoapClient(Configuraciones::EFACT_URL);

    $resultado = $client->procesarConsultaTicket($comprobanteElectronico)->procesarConsultaTicketResult;
    echo $resultado;
} catch (Exception $ex) {
    echo $ex->getMessage();
}