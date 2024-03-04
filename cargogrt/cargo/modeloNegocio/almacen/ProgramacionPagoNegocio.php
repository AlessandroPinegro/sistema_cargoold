<?php

require_once __DIR__ . '/../../modelo/almacen/ProgramacionPago.php';
require_once __DIR__ . '/../../modelo/almacen/MovimientoBien.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/../../modeloNegocio/commons/TablaNegocio.php';
require_once __DIR__ . '/PersonaNegocio.php';
require_once __DIR__ . '/DocumentoTipoNegocio.php';
require_once __DIR__ . '/DocumentoNegocio.php';
require_once __DIR__ . '/MonedaNegocio.php';
require_once __DIR__ . '/MovimientoTipoNegocio.php';
require_once __DIR__ . '/OrganizadorNegocio.php';
require_once __DIR__ . '/EmailPlantillaNegocio.php';
require_once __DIR__ . '/EmailEnvioNegocio.php';

class ProgramacionPagoNegocio extends ModeloNegocioBase {

    /**
     * 
     * @return ProgramacionPagoNegocio
     */
    static function create() {
        return parent::create();
    }

    public function obtenerConfiguracionInicialListado() {
        $respuesta->documento_tipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoPPago();
        $respuesta->persona_activa = PersonaNegocio::create()->obtenerActivas();
        $respuesta->moneda = MonedaNegocio::create()->obtenerComboMoneda();
        $respuesta->personasMayorDocumentos = PersonaNegocio::create()->obtenerPersonasMayorDocumentosPPagoXTipos('(4)');

        return $respuesta;
    }

    public function obtenerDocumentosPPagoXCriterios($criterios, $elementosFiltrados, $columns, $order, $start) {
        $personaId = $criterios['personaId'];
        $documentoTipoId = $criterios['documentoTipoIds'];
        $serie = $criterios['serie'];
        $numero = $criterios['numero'];
        $monedaId = $criterios['monedaId'];
        $estadoProgramacion = $criterios['estadoProgramacion'];
        $fechaEmisionInicio = $this->formatearFechaBD($criterios['fechaEmision']['inicio']);
        $fechaEmisionFin = $this->formatearFechaBD($criterios['fechaEmision']['fin']);
        $fechaBlInicio = $this->formatearFechaBD($criterios['fechaBL']['inicio']);
        $fechaBlFin = $this->formatearFechaBD($criterios['fechaBL']['fin']);
        $documentoTipoIdFormateado = Util::convertirArrayXCadena($documentoTipoId);

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];

        return ProgramacionPago::create()->obtenerDocumentosPPagoXCriterios($documentoTipoIdFormateado, $personaId, $fechaEmisionInicio, $fechaEmisionFin, $fechaBlInicio, $fechaBlFin, $serie, $numero, $monedaId, $estadoProgramacion, $columnaOrdenar, $formaOrdenar, $elementosFiltrados, $start);
    }

    public function obtenerCantidadDocumentosPPagoXCriterios($criterios, $columns, $order) {
        $personaId = $criterios['personaId'];
        $documentoTipoId = $criterios['documentoTipoIds'];
        $serie = $criterios['serie'];
        $numero = $criterios['numero'];
        $monedaId = $criterios['monedaId'];
        $estadoProgramacion = $criterios['estadoProgramacion'];
        $fechaEmisionInicio = $this->formatearFechaBD($criterios['fechaEmision']['inicio']);
        $fechaEmisionFin = $this->formatearFechaBD($criterios['fechaEmision']['fin']);
        $fechaBlInicio = $this->formatearFechaBD($criterios['fechaBL']['inicio']);
        $fechaBlFin = $this->formatearFechaBD($criterios['fechaBL']['fin']);
        $documentoTipoIdFormateado = Util::convertirArrayXCadena($documentoTipoId);

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];
        return ProgramacionPago::create()->obtenerCantidadDocumentosPPagoXCriterios($documentoTipoIdFormateado, $personaId, $fechaEmisionInicio, $fechaEmisionFin, $fechaBlInicio, $fechaBlFin, $serie, $numero, $monedaId, $estadoProgramacion, $columnaOrdenar, $formaOrdenar);
    }

    private function formatearFechaBD($cadena) {
        if (!ObjectUtil::isEmpty($cadena)) {
            return DateUtil::formatearCadenaACadenaBD($cadena);
        }
        return "";
    }

    public function obtenerProgramacionPagoDetalleXCriterios($criterios, $elementosFiltrados, $columns, $order, $start) {
        $personaId = $criterios['personaId'];
        $documentoTipoId = $criterios['documentoTipoIds'];
        $serie = $criterios['serie'];
        $numero = $criterios['numero'];
        $monedaId = $criterios['monedaId'];
        $estadoPPago = $criterios['estadoPPago'];
        $fechaEmisionInicio = $this->formatearFechaBD($criterios['fechaEmision']['inicio']);
        $fechaEmisionFin = $this->formatearFechaBD($criterios['fechaEmision']['fin']);
        $fechaBLInicio = $this->formatearFechaBD($criterios['fechaBL']['inicio']);
        $fechaBLFin = $this->formatearFechaBD($criterios['fechaBL']['fin']);
        $fechaProgramadaInicio = $this->formatearFechaBD($criterios['fechaProgramada']['inicio']);
        $fechaProgramadaFin = $this->formatearFechaBD($criterios['fechaProgramada']['fin']);
        $documentoTipoIdFormateado = Util::convertirArrayXCadena($documentoTipoId);

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];

        return ProgramacionPago::create()->obtenerProgramacionPagoDetalleXCriterios($documentoTipoIdFormateado, $personaId, $fechaEmisionInicio, $fechaEmisionFin, $fechaBLInicio, $fechaBLFin, $fechaProgramadaInicio, $fechaProgramadaFin, $serie, $numero, $monedaId, $estadoPPago, $columnaOrdenar, $formaOrdenar, $elementosFiltrados, $start);
    }

    public function obtenerCantidadProgramacionPagoDetalleXCriterios($criterios, $columns, $order) {
        $personaId = $criterios['personaId'];
        $documentoTipoId = $criterios['documentoTipoIds'];
        $serie = $criterios['serie'];
        $numero = $criterios['numero'];
        $monedaId = $criterios['monedaId'];
        $estadoPPago = $criterios['estadoPPago'];
        $fechaEmisionInicio = $this->formatearFechaBD($criterios['fechaEmision']['inicio']);
        $fechaEmisionFin = $this->formatearFechaBD($criterios['fechaEmision']['fin']);
        $fechaBLInicio = $this->formatearFechaBD($criterios['fechaBL']['inicio']);
        $fechaBLFin = $this->formatearFechaBD($criterios['fechaBL']['fin']);
        $fechaProgramadaInicio = $this->formatearFechaBD($criterios['fechaProgramada']['inicio']);
        $fechaProgramadaFin = $this->formatearFechaBD($criterios['fechaProgramada']['fin']);
        $documentoTipoIdFormateado = Util::convertirArrayXCadena($documentoTipoId);

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];
        return ProgramacionPago::create()->obtenerCantidadProgramacionPagoDetalleXCriterios($documentoTipoIdFormateado, $personaId, $fechaEmisionInicio, $fechaEmisionFin, $fechaBLInicio, $fechaBLFin, $fechaProgramadaInicio, $fechaProgramadaFin, $serie, $numero, $monedaId, $estadoPPago, $columnaOrdenar, $formaOrdenar);
    }

    public function obtenerConfiguracionesIniciales($documentoId) {
        $resDocTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXDocumentoId($documentoId);
        $respuesta->dataDocumento = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);
        $respuesta->dataIndicador = ProgramacionPago::create()->obtenerIndicadorXDocumentoTipoId($resDocTipo[0]['id']);

        $resPPDetalle = ProgramacionPago::create()->obtenerProgramacionPagoDetalleXDocumentoId($documentoId); //obtenemos la pp detalle

        if (ObjectUtil::isEmpty($resPPDetalle)) {
//            if (($resDocTipo[0]['identificador_negocio'] == 17 || $resDocTipo[0]['identificador_negocio'] == 18) && 
//                   !ObjectUtil::isEmpty($respuesta->dataDocumento[0]['movimiento_id']) ) {
//                //DETALLE INICIAL DE COMPRA NACIONAL
//                $resPPDetalle = ProgramacionPago::create()->obtenerProgramacionPagoDetalleInicialXAprobacionParcial($documentoId);
//            } else {
                $resPPDetalle = ProgramacionPago::create()->obtenerProgramacionPagoDetalleInicialXDocumentoId($documentoId);
//            }
        }

        $respuesta->dataPPDetalle = $resPPDetalle;

        return $respuesta;
    }

    function guardarProgramacionPago($documentoId, $fechaTentativa, $personaId, $listaProgramacionPagoDetalle, $listaProgramacionPagoDetalleEliminado, $usuCreacion,$aprobacion_parcial) {
        //INSERTO LA CABECERA (ppago)
        $res = ProgramacionPago::create()->guardarProgramacionPago($documentoId, $this->formatearFechaBD($fechaTentativa), $personaId, $usuCreacion);

        if ($res[0]['vout_exito'] == 1) {
            $ppId = $res[0]['id'];

            //INSERTO EL DETALLE (ppago_detalle)
            if (!ObjectUtil::isEmpty($listaProgramacionPagoDetalle)) {
                foreach ($listaProgramacionPagoDetalle as $item) {
                    $resDet = ProgramacionPago::create()->guardarProgramacionPagoDetalle(
                            $ppId, $item['programacionPagoDetalleId'], $item['indicadorId'], $item['dias'], $this->formatearFechaBD($item['fechaProgramada']), $item['importe'], $item['estadoId'], $usuCreacion,$aprobacion_parcial);
                }
            }
            //ELIMINO EL DETALLE
            if (!ObjectUtil::isEmpty($listaProgramacionPagoDetalleEliminado)) {
                foreach ($listaProgramacionPagoDetalleEliminado as $valor) {
                    ProgramacionPago::create()->eliminarProgramacionPagoDetalle($valor);
                }
            }
        }

        return $res;
    }

    public function obtenerDocumento($documentoId) {
        $respuesta = new ObjectUtil();

        $respuesta->dataDocumento = DocumentoNegocio::create()->obtenerDetalleDocumento($documentoId);
        $respuesta->comentarioDocumento = DocumentoNegocio::create()->obtenerComentarioDocumento($documentoId);

        $res = MovimientoBien::create()->obtenerMovimientoIdXDocumentoId($documentoId);
        if (!ObjectUtil::isEmpty($res[0]['movimiento_id'])) {
            $respuesta->detalleDocumento = MovimientoBien::create()->obtenerXIdMovimiento($res[0]['movimiento_id']);

            $dataMovimientoTipo = MovimientoTipoNegocio::create()->obtenerXDocumentoId($documentoId);
            $respuesta->dataMovimientoTipoColumna = MovimientoTipoNegocio::create()->obtenerMovimientoTipoColumna($dataMovimientoTipo[0]['movimiento_tipo_id']);
            $respuesta->organizador = OrganizadorNegocio::create()->obtenerXMovimientoTipo($dataMovimientoTipo[0]['movimiento_tipo_id']);
        }

        return $respuesta;
    }

    public function actualizarEstadoPPagoDetalle($ppagoDetalleId, $nuevoEstado) {
        return ProgramacionPago::create()->actualizarEstadoPPagoDetalle($ppagoDetalleId, $nuevoEstado);
    }

    public function obtenerPendientePorLiberarXFechaProgramada($fecha) {
        $fechaProgramada = $this->formatearFechaBD($fecha);
        return ProgramacionPago::create()->obtenerPendientePorLiberarXFechaProgramada($fechaProgramada);
    }

    public function guardarEmailEnvioProgramacionPagoPendientePorLiberar($dataPPend, $fecha) {
        if (!ObjectUtil::isEmpty($dataPPend)) {
            $plantilla = EmailPlantillaNegocio::create()->obtenerEmailPlantillaXID(10);
            $correosPlantilla = EmailPlantillaNegocio::create()->obtenerEmailXDestinatario($plantilla[0]["destinatario"], null, null);

            $correos = '';
            foreach ($correosPlantilla as $email) {
                $correos = $correos . $email . ';';
            }
            //dibujando la cabecera
            $dataDetalle = '<table  border=1 cellspacing=0 cellpadding=5 bordercolor="000000"  width="91.7%">
                        <thead>';

            $html = '<tr>';
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Item</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>F.Programada</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Tipo de documento</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Proveedor</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>S/N</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Importe</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>F.Emisión</th>";
//            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>F.Indicador</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Indicador</th>";
//        $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>F.Creación</th>";
//        $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Usuario</th>";
            $html = $html . '</tr>';

            $dataDetalle = $dataDetalle . $html;
            $dataDetalle = $dataDetalle . '<thead>';
            $dataDetalle = $dataDetalle . '<tbody>';

            foreach ($dataPPend as $index => $item) {
                $html = '<tr>';
                $html = $html . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . ($index + 1);
                $html = $html . '<td style=\'text-align:center;font-size:14px;line-height:1.5;\'>' . DateUtil::formatearFechaBDAaCadenaVw($item['fecha_programada_alt']);
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['documento_tipo_descripcion'];
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['persona_nombre_completo'];
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['serie_numero'];
                $html = $html . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . $item['moneda_simbolo'] . '&nbsp;' . number_format($item['importe_programado'], 2, ".", ",");
                $html = $html . '<td style=\'text-align:center;font-size:14px;line-height:1.5;\'>' . DateUtil::formatearFechaBDAaCadenaVw($item['fecha_emision']);
//                $html = $html . '<td style=\'text-align:center;font-size:14px;line-height:1.5;\'>' . DateUtil::formatearFechaBDAaCadenaVw($item['fecha_bl']);
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['indicador_descripcion'];
//                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . DateUtil::formatearFechaBDAaCadenaVw($item['fecha_creacion']);
//                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['usuario'];
                $html = $html . '</tr>';

                $dataDetalle = $dataDetalle . $html;
            }

            $dataDetalle = $dataDetalle . '</tbody></table>';
            $descripcion = 'Detalle de programación de pago hasta la fecha actual (' . $fecha . ')';

            //logica correo:             
            $asunto = $plantilla[0]["asunto"];
            $cuerpo = $plantilla[0]["cuerpo"];

            $cuerpo = str_replace("[|titulo_email|]", 'PROGRAMACION DE PAGO PENDIENTE POR LIBERAR', $cuerpo);
            $cuerpo = str_replace("[|descripcion|]", $descripcion, $cuerpo);
            $cuerpo = str_replace("[|detalle_programacion|]", $dataDetalle, $cuerpo);

            $res = EmailEnvioNegocio::create()->insertarEmailEnvio($correos, $asunto, $cuerpo, 1, 1);
//            return $cuerpo;
            return 'Pendiente por liberar de programación de pago. ' . $res[0]['vout_mensaje'] . ' Id: ' . $res[0]['id'] . ' <br>';
        } else {
            return '';
        }
    }

    public function obtenerLiberadoPendienteDePagoXFechaProgramada($fecha) {
        $fechaProgramada = $this->formatearFechaBD($fecha);
        return ProgramacionPago::create()->obtenerLiberadoPendienteDePagoXFechaProgramada($fechaProgramada);
    }

    public function guardarEmailEnvioProgramacionPagoLiberadoPendienteDePago($dataPLib, $fecha) {
        if (!ObjectUtil::isEmpty($dataPLib)) {
            $plantilla = EmailPlantillaNegocio::create()->obtenerEmailPlantillaXID(11);
            $correosPlantilla = EmailPlantillaNegocio::create()->obtenerEmailXDestinatario($plantilla[0]["destinatario"], null, null);

            $correos = '';
            foreach ($correosPlantilla as $email) {
                $correos = $correos . $email . ';';
            }
            //dibujando la cabecera
            $dataDetalle = '<table  border=1 cellspacing=0 cellpadding=5 bordercolor="000000"  width="91.7%">
                        <thead>';

            $html = '<tr>';
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Item</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>F.Programada</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Tipo de documento</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Proveedor</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>S/N</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Importe</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>F.Emisión</th>";
//            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>F.Indicador</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Indicador</th>";
//        $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>F.Creación</th>";
//        $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Usuario</th>";
            $html = $html . '</tr>';

            $dataDetalle = $dataDetalle . $html;
            $dataDetalle = $dataDetalle . '<thead>';
            $dataDetalle = $dataDetalle . '<tbody>';

            foreach ($dataPLib as $index => $item) {
                $html = '<tr>';
                $html = $html . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . ($index + 1);
                $html = $html . '<td style=\'text-align:center;font-size:14px;line-height:1.5;\'>' . DateUtil::formatearFechaBDAaCadenaVw($item['fecha_programada_alt']);
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['documento_tipo_descripcion'];
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['persona_nombre_completo'];
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['serie_numero'];
                $html = $html . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . $item['moneda_simbolo'] . '&nbsp;' . number_format($item['importe_programado'], 2, ".", ",");
                $html = $html . '<td style=\'text-align:center;font-size:14px;line-height:1.5;\'>' . DateUtil::formatearFechaBDAaCadenaVw($item['fecha_emision']);
//                $html = $html . '<td style=\'text-align:center;font-size:14px;line-height:1.5;\'>' . DateUtil::formatearFechaBDAaCadenaVw($item['fecha_bl']);
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['indicador_descripcion'];
//                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . DateUtil::formatearFechaBDAaCadenaVw($item['fecha_creacion']);
//                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['usuario'];
                $html = $html . '</tr>';

                $dataDetalle = $dataDetalle . $html;
            }

            $dataDetalle = $dataDetalle . '</tbody></table>';
            $descripcion = 'Detalle de programación de pago hasta la fecha actual (' . $fecha . ')';

            //logica correo:             
            $asunto = 'Pendiente de pago';
            $cuerpo = $plantilla[0]["cuerpo"];

            $cuerpo = str_replace("[|titulo_email|]", 'PENDIENTE DE PAGO', $cuerpo);
            $cuerpo = str_replace("[|descripcion|]", $descripcion, $cuerpo);
            $cuerpo = str_replace("[|detalle_programacion|]", $dataDetalle, $cuerpo);
//
            $res = EmailEnvioNegocio::create()->insertarEmailEnvio($correos, $asunto, $cuerpo, 1, 1);
//            return $cuerpo;
            return 'Pendiente de pago. ' . $res[0]['vout_mensaje'] . ' Id: ' . $res[0]['id'] . ' <br>';
        } else {
            return '';
        }
    }

    public function obtenerProgramacionPagoXDocumentoId($documentoId) {
        return ProgramacionPago::create()->obtenerProgramacionPagoXDocumentoId($documentoId);
    }

    public function obtenerProgramacionPagoDetalleLiberadoPendienteDePagoXDocumentoIdXFecha($documentoId, $fecha) {
        return ProgramacionPago::create()->obtenerProgramacionPagoDetalleLiberadoPendienteDePagoXDocumentoIdXFecha($documentoId, $fecha);
    }
    
     public function obtenerFacturasXVencer() {
       // return ProgramacionPago::create()->obtenerFacturasXVencer();        
    }
    public function guardarEmailEnvioFacturasxVencer($data,$plantillaId) {
       $plantilla = EmailPlantillaNegocio::create()->obtenerEmailPlantillaXID($plantillaId);
       $correosPlantilla = EmailPlantillaNegocio::create()->obtenerEmailXDestinatario($plantilla[0]["destinatario"], null, null);
       $correos = '';
            foreach ($correosPlantilla as $email) {
                $correos = $correos . $email . ';';
            }
        //dibujando la cabecera
            $dataDetalle = '<table  border=1 cellspacing=0 cellpadding=5 bordercolor="000000"  width="91.7%">
                        <thead>';

            $html = '<tr>';
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Tipo</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Numero</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Cliente</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Moneda</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Total a Pagar</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Total pagado</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>F.Vencimiento</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Dias para vencimiento</th>";

            $html = $html . '</tr>';

            $dataDetalle = $dataDetalle . $html;
            $dataDetalle = $dataDetalle . '<thead>';
            $dataDetalle = $dataDetalle . '<tbody>';

            foreach ($data as $index => $item) {
                $html = '<tr>';
                $html = $html . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . $item['descripcion'];
                $html = $html . '<td style=\'text-align:center;font-size:14px;line-height:1.5;\'>' . $item['numero'];
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['nombre'];
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['moneda'];
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['total'];
                $html = $html . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . $item['pagado'];
                $html = $html . '<td style=\'text-align:center;font-size:14px;line-height:1.5;\'>'. $item['fecha_vencimiento'];
                $html = $html . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . $item['dias'];

                $html = $html . '</tr>';

                $dataDetalle = $dataDetalle . $html;
            }

            $dataDetalle = $dataDetalle . '</tbody></table>';
            $descripcion = 'Detalle de facturas y boletas por vencer pendientes por cobrar.';
            
            $asunto = 'Facturas y boletas por vencer pendientes de cobranza.';
            $cuerpo = $plantilla[0]["cuerpo"];

            $cuerpo = str_replace("[|titulo_email|]", 'PENDIENTE DE COBRO', $cuerpo);
            $cuerpo = str_replace("[|descripcion|]", $descripcion, $cuerpo);
            $cuerpo = str_replace("[|detalle_programacion|]", $dataDetalle, $cuerpo);
            
            $res = EmailEnvioNegocio::create()->insertarEmailEnvio($correos, $asunto, $cuerpo, 1, 1);
//            return $cuerpo;
            return 'Pendiente de pago. ' . $res[0]['vout_mensaje'] . ' Id: ' . $res[0]['id'] . ' <br>';

    }

}
