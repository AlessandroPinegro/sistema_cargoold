<?php

require_once __DIR__ . '/../../modelo/almacen/AprobacionParcial.php';
require_once __DIR__ . '/../../modelo/almacen/ProgramacionPago.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/PersonaNegocio.php';
require_once __DIR__ . '/DocumentoTipoNegocio.php';
require_once __DIR__ . '/MonedaNegocio.php';
require_once __DIR__ . '/EmailPlantillaNegocio.php';
require_once __DIR__ . '/EmailEnvioNegocio.php';

class AprobacionParcialNegocio extends ModeloNegocioBase {

    /**
     * 
     * @return AprobacionParcialNegocio
     */
    static function create() {
        return parent::create();
    }

    public function obtenerConfiguracionInicialListado() {
        $respuesta->documento_tipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoAprobacionParcial();
        $respuesta->persona_activa = PersonaNegocio::create()->obtenerActivas();
        $respuesta->moneda = MonedaNegocio::create()->obtenerComboMoneda();
        
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
        $documentoTipoIdFormateado = Util::convertirArrayXCadena($documentoTipoId);

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];

        return AprobacionParcial::create()->obtenerDocumentosPPagoXCriterios($documentoTipoIdFormateado, $personaId, $fechaEmisionInicio, $fechaEmisionFin, $serie, $numero, $monedaId, $estadoProgramacion,$columnaOrdenar, $formaOrdenar, $elementosFiltrados, $start);
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
        $documentoTipoIdFormateado = Util::convertirArrayXCadena($documentoTipoId);

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];
        return AprobacionParcial::create()->obtenerCantidadDocumentosPPagoXCriterios($documentoTipoIdFormateado, $personaId, $fechaEmisionInicio, $fechaEmisionFin, $serie, $numero, $monedaId,$estadoProgramacion, $columnaOrdenar, $formaOrdenar);
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
        $fechaProgramadaInicio = $this->formatearFechaBD($criterios['fechaProgramada']['inicio']);
        $fechaProgramadaFin = $this->formatearFechaBD($criterios['fechaProgramada']['fin']);
        $documentoTipoIdFormateado = Util::convertirArrayXCadena($documentoTipoId);

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];

        return AprobacionParcial::create()->obtenerProgramacionPagoDetalleXCriterios($documentoTipoIdFormateado, $personaId, $fechaEmisionInicio, $fechaEmisionFin, $fechaProgramadaInicio, $fechaProgramadaFin, $serie, $numero, $monedaId,$estadoPPago, $columnaOrdenar, $formaOrdenar, $elementosFiltrados, $start);
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
        $fechaProgramadaInicio = $this->formatearFechaBD($criterios['fechaProgramada']['inicio']);
        $fechaProgramadaFin = $this->formatearFechaBD($criterios['fechaProgramada']['fin']);
        $documentoTipoIdFormateado = Util::convertirArrayXCadena($documentoTipoId);

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];
        return AprobacionParcial::create()->obtenerCantidadProgramacionPagoDetalleXCriterios($documentoTipoIdFormateado, $personaId, $fechaEmisionInicio, $fechaEmisionFin, $fechaProgramadaInicio, $fechaProgramadaFin, $serie, $numero, $monedaId,$estadoPPago, $columnaOrdenar, $formaOrdenar);
    }

    public function obtenerConfiguracionesIniciales($documentoId) {
        $respuesta->dataDocumento = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);
        $respuesta->dataPPDetalle = ProgramacionPago::create()->obtenerProgramacionPagoDetalleSinIndicadorXDocumentoId($documentoId); //obtenemos la pp detalle

        return $respuesta;
    }
    
    public function obtenerPendientePorAprobarXFechaProgramada($fecha) {
        $fechaProgramada = $this->formatearFechaBD($fecha);
        return AprobacionParcial::create()->obtenerPendientePorAprobarXFechaProgramada($fechaProgramada);
    }
    
    public function guardarEmailEnvioPorAprobar($dataPPend, $fecha) {
        if (!ObjectUtil::isEmpty($dataPPend)) {
            $plantilla = EmailPlantillaNegocio::create()->obtenerEmailPlantillaXID(12);
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
            $html = $html . '</tr>';

            $dataDetalle = $dataDetalle . $html;
            $dataDetalle = $dataDetalle . '<thead>';
            $dataDetalle = $dataDetalle . '<tbody>';

            foreach ($dataPPend as $index => $item) {
                $html = '<tr>';
                $html = $html . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . ($index + 1);
                $html = $html . '<td style=\'text-align:center;font-size:14px;line-height:1.5;\'>' . DateUtil::formatearFechaBDAaCadenaVw($item['fecha_programada']);
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['documento_tipo_descripcion'];
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['persona_nombre_completo'];
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['serie_numero'];
                $html = $html . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . $item['moneda_simbolo'] . '&nbsp;' . number_format($item['importe_programado'], 2, ".", ",");
                $html = $html . '<td style=\'text-align:center;font-size:14px;line-height:1.5;\'>' . DateUtil::formatearFechaBDAaCadenaVw($item['fecha_emision']);
                $html = $html . '</tr>';

                $dataDetalle = $dataDetalle . $html;
            }

            $dataDetalle = $dataDetalle . '</tbody></table>';
            $descripcion = 'Detalle de programación pendiente por aprobar hasta la fecha actual (' . $fecha . ')';

            //logica correo:             
            $asunto = $plantilla[0]["asunto"];
            $cuerpo = $plantilla[0]["cuerpo"];

            $cuerpo = str_replace("[|titulo_email|]", 'PROGRAMACION PENDIENTE POR APROBAR', $cuerpo);
            $cuerpo = str_replace("[|descripcion|]", $descripcion, $cuerpo);
            $cuerpo = str_replace("[|detalle_programacion|]", $dataDetalle, $cuerpo);

            $res = EmailEnvioNegocio::create()->insertarEmailEnvio($correos, $asunto, $cuerpo, 1, 1);
//            return $cuerpo;
            return 'Pendiente por aprobar. ' . $res[0]['vout_mensaje'] . ' Id: ' . $res[0]['id'] . ' <br>';
        } else {
            return '';
        }
    }


}
