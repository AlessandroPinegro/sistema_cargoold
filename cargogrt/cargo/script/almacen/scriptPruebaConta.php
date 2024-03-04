<?php

include_once __DIR__ . '/../../modeloNegocio/contabilidad/LibroDiarioNegocio.php';

$data = LibroDiarioNegocio::create()->obtenerAsientoPreCierre(2, 2020, 1);

echo($data);
