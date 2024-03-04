<?php

require_once __DIR__ . '/../../modelo/contabilidad/Planilla.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/MovimientoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/ExcelNegocio.php';

class PlanillaNegocio extends ModeloNegocioBase {

    /**
     * 
     * @return PlanillaNegocio
     */
    static function create() {
        return parent::create();
    }

    public function obtenerConfiguracionInicial($empresaId) {
        $respuesta->dataPeriodo = PeriodoNegocio::create()->obtenerPeriodoXEmpresa($empresaId);
        $respuesta->dataPeriodoActual = PeriodoNegocio::create()->obtenerUltimoPeriodoActivoXEmpresa($empresaId);
        $respuesta->dataMoneda = MonedaNegocio::create()->obtenerComboMoneda();
        return $respuesta;
    }

    public function registrarActualizarImportarArchivo($id, $arhivoContenido, $archivoNombre, $archivoTipo, $periodoId, $usuarioId) {
        $decode = Util::base64ToImage($arhivoContenido);
        $archivoUrl = "$usuarioId$periodoId$archivoTipo" . (new DateTime())->format("Ymdhis") . ".xls";
        $archivoUrlCompleta = __DIR__ . "/../../util/uploads/documentoPlanilla/$archivoUrl";
        if (file_put_contents($archivoUrlCompleta, $decode) === FALSE) {
            unlink($archivoUrlCompleta);
            throw new WarningException("Error al intentar guardar  $archivoNombre.");
        }

        if (!ObjectUtil::isEmpty($id)) {
            $respuesAnularVoucher = ContVoucherNegocio::create()->anularContVocuherRelacionXIdentificadorIdXIdentificadorNegocio($id, ContVoucherNegocio::IDENTIFICADOR_PLANILLA);
            if ($respuesAnularVoucher[0]['vout_exito'] != '1') {
                unlink($archivoUrlCompleta);
                throw new WarningException("Error al intentar registrar el arhivo $archivoNombre : " . $respuesAnularVoucher[0]['vout_mensaje']);
            }
            $respuestaGuardarArchivo = self::actualizarXId($id, $archivoNombre, $archivoUrl);
        } else {
            $respuestaGuardarArchivo = self::guardarPlaImportarArchivo($periodoId, $archivoNombre, $archivoUrl, $archivoTipo, $usuarioId);
        }
        if ($respuestaGuardarArchivo[0]['vout_exito'] != '1') {
            unlink($archivoUrlCompleta);
            throw new WarningException("Error al intentar registrar el arhivo $archivoNombre : " . $respuestaGuardarArchivo[0]['vout_mensaje']);
        }

        $importacionArchivoId = $respuestaGuardarArchivo[0]['id'];

        $excel = new Spreadsheet_Excel_Reader();
        $excel->setUTFEncoder('iconv');
        $excel->setOutputEncoding('UTF-8');
        $excel->read($archivoUrlCompleta);
        $cells = $excel->sheets[0]["cells"];

        $saltos = 0;
        switch ($archivoTipo * 1) {
            case 1:
                $saltos = 4;
                break;
            case 2:
            case 3:
                $saltos = 2;
                break;
        }
        if (ObjectUtil::isEmpty($cells) || count($cells) <= $saltos) {
            unlink($archivoUrlCompleta);
            throw new WarningException("No se ha especificado un excel correcto.");
        }

        // LOS PARAMETROS SON CONFIGURADOS SEGÚN EL TIPO DE ARCHIVO, EN LA TABLA PLA_IMPORTAR_ARCHIVO = tipo_archivo Y EN LA TABLA PARAMETRO = clasificacion, AMBOS DEBEN SER EL MISMO VALOR.
        $dataParametro = self::obtenerPlaPametroXClasificacion($archivoTipo);
        if (ObjectUtil::isEmpty($dataParametro)) {
            unlink($archivoUrlCompleta);
            throw new WarningException("Se requiere de los parámetros iniciales para continuar con la importación.");
        }

        foreach ($cells as $key => $value) {
            if ($key > $saltos) {
                $personaId = PersonaNegocio::create()->obtenerPersonaXCodigoIdentificacion($value[4])[0]['id'];
                // Poscion = 3 -> nombre
                $nombrePersona = self::limpiarString($value[3]);
                if (ObjectUtil::isEmpty($nombrePersona) || $nombrePersona == "") {
                    break;
                }
                switch ($archivoTipo * 1) {
                    case 1:
                        $respuestaRegistrarBoleta = self::guardarPlaBoleta($importacionArchivoId, $personaId, self::limpiarString($value[20]), self::limpiarString($value[38]), self::limpiarString($value[39]), self::limpiarString($value[40]), self::limpiarString($value[41]), self::limpiarString($value[42]), self::limpiarString($value[43]), self::limpiarString($value[37]), self::limpiarString($value[18]), self::limpiarString($value[19]), self::limpiarString($value[34]), $usuarioId);
                        if ($respuestaRegistrarBoleta[0]['vout_exito'] != 1) {
                            unlink($archivoUrlCompleta);
                            throw new WarningException("Error al intentar guardar el detalle de la planilla para " . $nombrePersona);
                        }
                        $boletaId = $respuestaRegistrarBoleta[0]['id'];
                        foreach ($dataParametro as $item) {
                            $monto = self::limpiarString($value[$item['numero_columna'] * 1]);
                            if (!ObjectUtil::isEmpty($monto) && trim($monto) != "" && $monto * 1 > 0) {
                                $respuestaBoletaParametro = self::guardarPlaBoletaParametro($boletaId, $item['id'], $monto, $usuarioId);
                            }
                        }
                        break;
                    case 2:
                    case 3:
                        $meses = self::limpiarString($value[12]);
                        $dias = self::limpiarString($value[13]);
                        $respuestaRegistrarCtsGratificacion = self::guardarPlaCtsGratificacion($importacionArchivoId, $personaId, $meses, $dias, $usuarioId);
                        if ($respuestaRegistrarCtsGratificacion[0]['vout_exito'] != 1) {
                            unlink($archivoUrlCompleta);
                            throw new WarningException("Error al intentar guardar el detalle para " . $nombrePersona);
                        }
                        $ctsGratifiacionId = $respuestaRegistrarCtsGratificacion[0]['id'];
                        foreach ($dataParametro as $item) {
                            $monto = self::limpiarString($value[$item['numero_columna'] * 1]);
                            if (!ObjectUtil::isEmpty($monto) && trim($monto) != "" && $monto * 1 > 0) {
                                $respuestaBoletaParametro = self::guardarPlaCtsGratificacionParametro($ctsGratifiacionId, $item['id'], $monto, $usuarioId);
                            }
                        }
                        break;
                }
            }
        }

        $respuestaVoucherPlanilla = ContVoucherNegocio::create()->registrarContVoucherPlanilla($importacionArchivoId, $archivoTipo, $periodoId, $usuarioId);
        return $respuestaVoucherPlanilla;
    }

    private function obtenerCelda($cells, $col, $row, $limpiaString = true) {
        if (!array_key_exists($row, $cells)) {
            return "";
        }
        $tildes = array("á" => "a", "é" => "e", "í" => "i", "ó" => "o", "ú" => "u", "." => "_", " " => "");
        $valor = ($limpiaString) ? $this->limpiarString($cells[$row][$col]) : $cells[$row][$col];
        return trim(strtr(trim($valor), $tildes) . "");
    }

    private function limpiarString($texto) {
        $textoLimpio = preg_replace('([^A-Za-z0-9.])', '', $texto);
        return $textoLimpio;
    }

    public function obtenerPlaPametroXClasificacion($clasificacion) {
        return Planilla::create()->obtenerPlaPametroXClasificacion($clasificacion);
    }

    public function guardarPlaImportarArchivo($periodoId, $archivoNombre, $archivoUrl, $archivoTipo, $usuarioId) {
        return Planilla::create()->guardarPlaImportarArchivo($periodoId, $archivoNombre, $archivoUrl, $archivoTipo, $usuarioId);
    }

    public function guardarPlaBoleta($importacionArchivoId, $personaId, $sistemaPensionesId, $diasTrabajados, $horasTrabajadas, $horasTotalTrabajadas, $horasExtras, $vacaciones, $licencia, $faltas, $remuneracionComputabilizable, $remuneracionBruta, $remuneracionNeta, $usuarioId) {
        return Planilla::create()->guardarPlaBoleta($importacionArchivoId, $personaId, $sistemaPensionesId, $diasTrabajados, $horasTrabajadas, $horasTotalTrabajadas, $horasExtras, $vacaciones, $licencia, $faltas, $remuneracionComputabilizable, $remuneracionBruta, $remuneracionNeta, $usuarioId);
    }

    public function guardarPlaCtsGratificacion($importacionArchivoId, $personaId, $meses, $dias, $usuarioId) {
        return Planilla::create()->guardarPlaCtsGratificacion($importacionArchivoId, $personaId, $meses, $dias, $usuarioId);
    }

    public function guardarPlaBoletaParametro($boletaId, $parametroId, $monto, $usuarioId) {
        return Planilla::create()->guardarPlaBoletaParametro($boletaId, $parametroId, $monto, $usuarioId);
    }

    public function guardarPlaCtsGratificacionParametro($ctsId, $parametroId, $monto, $usuarioId) {
        return Planilla::create()->guardarPlaCtsGratificacionParametro($ctsId, $parametroId, $monto, $usuarioId);
    }

    public function obtenerCuentasContableXPlaImportacionArhivoId($importacionArchivoId, $tipoArchivo) {
        return Planilla::create()->obtenerCuentasContableXPlaImportacionArhivoId($importacionArchivoId, $tipoArchivo);
    }

    public function obtenerValoresXParametroIdXPlaImportacionArhivoId($importacionArchivoId, $parametroId) {
        return Planilla::create()->obtenerValoresXParametroIdXPlaImportacionArhivoId($importacionArchivoId, $parametroId);
    }

    public function obtenerPlaImportarArchivo() {
        return Planilla::create()->obtenerPlaImportarArchivo();
    }

    public function actualizarXId($id, $nombre, $url) {
        return Planilla::create()->actualizarXId($id, $nombre, $url);
    }

    public function obtenerPlaImportarArchivoXId($id) {
        return Planilla::create()->obtenerPlaImportarArchivoXId($id);
    }

    private function obtenerImporte($importe) {
        if (strlen("" . importe) < 2) {
            $numero = str_replace(array("-", ",", "*", "?"), "", "" . $importe);
        } elseif (strpos($importe, "(")) {
            $numero = "-" . str_repeat(array("(", ")"), "", "" . $importe);
        } else {
            $numero = str_replace(array(",", "*", "?"), "", "" . $importe);
        }
        $numero = str_replace("_", ".", $numero);
        $numero = (ObjectUtil::isEmpty($numero)) ? (float) 0.00 : (float) $numero;
        return $numero;
    }

    private function formatoFecha($sFecha) {
        $aFecha = explode("/", $sFecha);
        return $aFecha[1] . "/" . $aFecha[0] . "/" . $aFecha[2];
    }

}
