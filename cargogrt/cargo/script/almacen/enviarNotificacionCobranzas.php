<?php

include_once __DIR__.'/../../modeloNegocio/almacen/PagoNegocio.php';
require_once __DIR__ . '/../../util/Configuraciones.php';

PagoNegocio::create()->enviarNotificacionCobranzas(Configuraciones::DIAS_VENCIMIENTO_DEFAULT,Configuraciones::REPORTE_COBRANZAS_VENCIDAS_ID);
PagoNegocio::create()->enviarNotificacionCobranzas(Configuraciones::DIAS_VENCIMIENTO_PROXIMO,Configuraciones::REPORTE_COBRANZAS_POR_VENCER_ID);

