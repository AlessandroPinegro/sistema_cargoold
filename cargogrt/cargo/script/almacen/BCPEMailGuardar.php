<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once __DIR__ . '/../../modelo/exceptions/CriticalException.php';
require_once __DIR__ . '/../../modelo/exceptions/ErrorPersException.php';
require_once __DIR__ . '/../../modelo/exceptions/WarningException.php';
require_once __DIR__ . '/../../modelo/exceptions/InformationException.php';
include_once __DIR__ . '/../../modeloNegocio/almacen/BCPEMailNegocio.php';


//$respuesta = BCPEMailNegocio::create()->guardar($_SERVER['REMOTE_ADDR'], 
//                                                $_POST["correoId"], 
//                                                $_POST["remitente"], 
//                                                $_POST["asunto"], 
//                                                str_replace("[amberson]", "&", $_POST["cuerpo"]), 
//                                                $_POST["fecha"]);
//
//
//echo $respuesta;
//
//BCPEMailNegocio::create()->pagarProgramacionXBCPEMailId(18);

try{
    BCPEMailNegocio::create()->sincronizarPagoBCP($_SERVER['REMOTE_ADDR'], 
                                                $_POST["correoId"], 
                                                $_POST["remitente"], 
                                                $_POST["asunto"], 
                                                str_replace("[amberson]", "&", $_POST["cuerpo"]), 
                                                $_POST["fecha"]);
} catch (CriticalException $cex) {
    setException ($cex, "Error crítico");
} catch (WarningException $wex) {
    setException ($wex, "Advertencia");
} catch (ErrorPersException $pex) {
    setException ($pex, "Error personalizado");
} catch (InformationException $iex) {
    setException ($iex, "Información");
} catch (ModeloException $mex) {
    setException ($mex, "Error modelo");
} catch (\ErrorException $eex) {
    setException ($eex, "Error");
} catch (\Exception $ex) {
    setException ($ex, "Excepción general");
}

function setException ($ex, $tipo){
    if (BCPEMailNegocio::create()->hasTransaction){
        BCPEMailNegocio::create()->rollbackTransaction();
    }
    echo "$tipo: ".$ex->getMessage();
}