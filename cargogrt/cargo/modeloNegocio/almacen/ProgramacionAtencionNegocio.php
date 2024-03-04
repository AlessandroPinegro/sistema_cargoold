<?php

require_once __DIR__ . '/../../modelo/almacen/ProgramacionAtencion.php';
require_once __DIR__ . '/../../modelo/almacen/MovimientoBien.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/PersonaNegocio.php';
require_once __DIR__ . '/DocumentoTipoNegocio.php';
require_once __DIR__ . '/DocumentoNegocio.php';
require_once __DIR__ . '/MonedaNegocio.php';
require_once __DIR__ . '/MovimientoTipoNegocio.php';
require_once __DIR__ . '/OrganizadorNegocio.php';
require_once __DIR__ . '/EmailPlantillaNegocio.php';
require_once __DIR__ . '/EmailEnvioNegocio.php';

class ProgramacionAtencionNegocio extends ModeloNegocioBase {

    /**
     * 
     * @return ProgramacionAtencionNegocio
     */
    static function create() {
        return parent::create();
    }

    public function obtenerConfiguracionInicialListado() {
        $respuesta->documento_tipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoProgramacionAtencion();
        $respuesta->persona_activa = PersonaNegocio::create()->obtenerActivas();
        $respuesta->moneda = MonedaNegocio::create()->obtenerComboMoneda();

        return $respuesta;
    }

    public function obtenerDocumentosPAtencionXCriterios($criterios, $elementosFiltrados, $columns, $order, $start) {
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

        return ProgramacionAtencion::create()->obtenerDocumentosPAtencionXCriterios($documentoTipoIdFormateado, $personaId, $fechaEmisionInicio, $fechaEmisionFin, $serie, $numero, $monedaId, $estadoProgramacion, $columnaOrdenar, $formaOrdenar, $elementosFiltrados, $start);
    }

    public function obtenerCantidadDocumentosPAtencionXCriterios($criterios, $columns, $order) {
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
        return ProgramacionAtencion::create()->obtenerCantidadDocumentosPAtencionXCriterios($documentoTipoIdFormateado, $personaId, $fechaEmisionInicio, $fechaEmisionFin, $serie, $numero, $monedaId, $estadoProgramacion, $columnaOrdenar, $formaOrdenar);
    }

    private function formatearFechaBD($cadena) {
        if (!ObjectUtil::isEmpty($cadena)) {
            return DateUtil::formatearCadenaACadenaBD($cadena);
        }
        return "";
    }

    public function obtenerProgramacionAtencionDetalleXCriterios($criterios, $elementosFiltrados, $columns, $order, $start) {
        $personaId = $criterios['personaId'];
        $documentoTipoId = $criterios['documentoTipoIds'];
        $serie = $criterios['serie'];
        $numero = $criterios['numero'];
        $monedaId = $criterios['monedaId'];
        $estadoPAtencion = $criterios['estadoPAtencion'];
        $fechaEmisionInicio = $this->formatearFechaBD($criterios['fechaEmision']['inicio']);
        $fechaEmisionFin = $this->formatearFechaBD($criterios['fechaEmision']['fin']);
        $fechaProgramadaInicio = $this->formatearFechaBD($criterios['fechaProgramada']['inicio']);
        $fechaProgramadaFin = $this->formatearFechaBD($criterios['fechaProgramada']['fin']);
        $documentoTipoIdFormateado = Util::convertirArrayXCadena($documentoTipoId);

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];

        return ProgramacionAtencion::create()->obtenerProgramacionAtencionDetalleXCriterios($documentoTipoIdFormateado, $personaId, $fechaEmisionInicio, $fechaEmisionFin, $fechaProgramadaInicio, $fechaProgramadaFin, $serie, $numero, $monedaId, $estadoPAtencion, $columnaOrdenar, $formaOrdenar, $elementosFiltrados, $start);
    }

    public function obtenerCantidadProgramacionAtencionDetalleXCriterios($criterios, $columns, $order) {
        $personaId = $criterios['personaId'];
        $documentoTipoId = $criterios['documentoTipoIds'];
        $serie = $criterios['serie'];
        $numero = $criterios['numero'];
        $monedaId = $criterios['monedaId'];
        $estadoPAtencion = $criterios['estadoPAtencion'];
        $fechaEmisionInicio = $this->formatearFechaBD($criterios['fechaEmision']['inicio']);
        $fechaEmisionFin = $this->formatearFechaBD($criterios['fechaEmision']['fin']);
        $fechaProgramadaInicio = $this->formatearFechaBD($criterios['fechaProgramada']['inicio']);
        $fechaProgramadaFin = $this->formatearFechaBD($criterios['fechaProgramada']['fin']);
        $documentoTipoIdFormateado = Util::convertirArrayXCadena($documentoTipoId);

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];
        return ProgramacionAtencion::create()->obtenerCantidadProgramacionAtencionDetalleXCriterios($documentoTipoIdFormateado, $personaId, $fechaEmisionInicio, $fechaEmisionFin, $fechaProgramadaInicio, $fechaProgramadaFin, $serie, $numero, $monedaId, $estadoPAtencion, $columnaOrdenar, $formaOrdenar);
    }

    public function obtenerConfiguracionesIniciales($documentoId) {
        $dataDocumento = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);
        $respuesta->dataDocumento = $dataDocumento;
        $respuesta->dataMovimientoBien = MovimientoBien::create()->obtenerXIdMovimiento($dataDocumento[0]['movimiento_id']);
        $respuesta->dataOrganizador = OrganizadorNegocio::create()->obtenerOrganizadorActivo(56);
        $dataPAtencion = ProgramacionAtencion::create()->obtenerPAtencionXDocumentoId($documentoId); //

        if (ObjectUtil::isEmpty($dataPAtencion)) {
            $dataPAtencion = ProgramacionAtencionNegocio::create()->obtenerPAtencionInicialXDocumentoId($documentoId);
        }

        $respuesta->dataPAtencion = $dataPAtencion;

        return $respuesta;
    }

    public function obtenerPAtencionInicialXDocumentoId($documentoId) {
        $data = ProgramacionAtencion::create()->obtenerPAtencionInicialXDocumentoId($documentoId);

        foreach ($data as $index => $item) {
            if ($item['stock_disponible'] * 1 > $item['cantidad_solicitada'] * 1) {
                $data[$index]['cantidad_programada'] = $item['cantidad_solicitada'] * 1;
            } else {
                $data[$index]['cantidad_programada'] = $item['stock_disponible'];
            }
        }
        
        foreach ($data as $index => $item) {
            if ($item['cantidad_programada'] <= 0) {
                unset($data[$index]);
            }
        }
        
        //los indices del array $data cambiaron el orden, ordenarlos.
        $dataInd=[];
        foreach ($data as $index2 => $item2){
            array_push($dataInd, $item2);
        }
        
        return $dataInd;
    }

    function guardarProgramacionAtencion($listaProgramacionAtencionDetalle, $listaProgramacionAtencionDetalleEliminado, $usuCreacion) {
        //ELIMINO EL DETALLE
        if (!ObjectUtil::isEmpty($listaProgramacionAtencionDetalleEliminado)) {
            foreach ($listaProgramacionAtencionDetalleEliminado as $valor) {
                ProgramacionAtencion::create()->eliminarProgramacionAtencionDetalle($valor);
            }
        }

        //INSERTO EL DETALLE (patencion)
        if (!ObjectUtil::isEmpty($listaProgramacionAtencionDetalle)) {
            foreach ($listaProgramacionAtencionDetalle as $item) {
                if ($item['estadoId'] != 5 && $item['estadoId'] != 6) {
                    //valido el stock para atenciones liberadas o comprometidas
                    $this->validarStockProgramacionAtencion($item);

                    $resDet = ProgramacionAtencion::create()->guardarProgramacionAtencionDetalle(
                            $item['programacionAtencionDetalleId'], $item['moviBienId'], $item['organizadorId'], $item['cantidad'], $this->formatearFechaBD($item['fechaProgramada']), $item['estadoId'], $usuCreacion);
                }
            }
        }

        //ENVIAR CORREO DE ATENCIONES 
        $dataLiberado = ProgramacionAtencion::create()->obtenerPAtencionLiberadasXPAtencionId($resDet[0]['id']);
        if (!ObjectUtil::isEmpty($dataLiberado)) {
            $plantillaId = 15;
            $descripcionCorreo = 'Detalle de atenciones liberadas del documento: ' . $dataLiberado[0]['documento_tipo_descripcion'] . ' ' . $dataLiberado[0]['serie_numero'];
            $tituloCorreo = 'ATENCIONES LIBERADAS';
            $asuntoCorreo = 'Atenciones liberadas';
            $res3 = ProgramacionAtencionNegocio::create()->guardarEmailEnvioPAtencion($dataLiberado, $asuntoCorreo, $plantillaId, $descripcionCorreo, $tituloCorreo, 0);
        }
        return $resDet;
    }

    public function validarStockProgramacionAtencion($patencion) {
        if ($patencion['estadoId'] == 3 || $patencion['estadoId'] == 4) {
            $organizadorId = $patencion['organizadorId'];
            $dataMoviBien = MovimientoBien::create()->obtenerMovimientoBienXId($patencion['moviBienId']);

            $bienId = $dataMoviBien[0]['bien_id'];
            $unidadMedidaId = $dataMoviBien[0]['unidad_medida_id'];
            if (!ObjectUtil::isEmpty($organizadorId)) {
                $stock = BienNegocio::create()->obtenerStockActual($bienId, $organizadorId, $unidadMedidaId);
            } else {
                $stock = BienNegocio::create()->obtenerStockTotalXBienIDXUnidadMedidaId($bienId, $unidadMedidaId);
            }

            $cantidadAtencion = 0;
            if (!ObjectUtil::isEmpty($patencion['programacionAtencionDetalleId'])) {
                $dataPA = ProgramacionAtencion::create()->obtenerPAtencionXId($patencion['programacionAtencionDetalleId']);
                if ($dataPA[0]['estado'] == 3 || $dataPA[0]['estado'] == 4) {
                    $cantidadAtencion = $dataPA[0]['cantidad'];
                }
            }
            if (($stock[0]['stock'] * 1 + $cantidadAtencion * 1) < $patencion['cantidad'] * 1) {
                throw new WarningException("<p style='text-align: left;'>"
                . "No se pudo guardar un detalle de la programación de atención, no cuenta con stock sufiente para:<br><br>"
                . "<table style='text-align: left;'>"
                . "<tr> <th>Fecha programada:</th><td>" . $patencion['fechaProgramada'] . "</td></tr>"
                . "<tr> <th>Producto:</th><td>" . $dataMoviBien[0]['bien_descripcion'] . "</td></tr>"
                . "<tr> <th>Unidad medida:</th><td>" . $dataMoviBien[0]['unidad_descripcion'] . "</td></tr>"
                . "<tr> <th>Stock actual:</th><td>" . number_format($stock[0]['stock'] * 1 + $cantidadAtencion * 1, 2, ".", ",") . "</td></tr>"
                . "<tr> <th>Cantidad:</th><td>" . number_format($patencion['cantidad'], 2, ".", ",") . "</td></tr>"
                . "<tr> <th>Estado:</th><td>" . ($patencion['estadoId'] == 3 ? 'Liberado' : 'Comprometido') . "</td></tr>"
                . "</table></p>"
                )
                ;
            }
        }
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
        $respuesta->dataDocumentoAdjunto = DocumentoNegocio::create()->obtenerDocumentoAdjuntoXDocumentoId($documentoId);

        return $respuesta;
    }

    public function actualizarEstadoPAtencionDetalle($patencionDetalleId, $nuevoEstado) {

        if ($nuevoEstado == 3) {
            $dataPA = ProgramacionAtencion::create()->obtenerPAtencionXId($patencionDetalleId);
            $organizadorId = $dataPA[0]['organizador_id'];
            $dataMoviBien = MovimientoBien::create()->obtenerMovimientoBienXId($dataPA[0]['movimiento_bien_id']);

            $bienId = $dataMoviBien[0]['bien_id'];
            $unidadMedidaId = $dataMoviBien[0]['unidad_medida_id'];
            if (!ObjectUtil::isEmpty($organizadorId)) {
                $stock = BienNegocio::create()->obtenerStockActual($bienId, $organizadorId, $unidadMedidaId);
            } else {
                $stock = BienNegocio::create()->obtenerStockTotalXBienIDXUnidadMedidaId($bienId, $unidadMedidaId);
            }

            $cantidadAtencion = 0;
            if ($dataPA[0]['estado'] == 3 || $dataPA[0]['estado'] == 4) {
                $cantidadAtencion = $dataPA[0]['cantidad'];
            }
            if (($stock[0]['stock'] * 1 + $cantidadAtencion * 1) < $dataPA[0]['cantidad'] * 1) {
                throw new WarningException("<p style='text-align: left;'>"
                . "No se pudo guardar un detalle de la programación de atención, no cuenta con stock sufiente para:<br><br>"
                . "<table style='text-align: left;'>"
                . "<tr> <th>Fecha programada:</th><td>" . $patencion['fechaProgramada'] . "</td></tr>"
                . "<tr> <th>Producto:</th><td>" . $dataMoviBien[0]['bien_descripcion'] . "</td></tr>"
                . "<tr> <th>Unidad medida:</th><td>" . $dataMoviBien[0]['unidad_descripcion'] . "</td></tr>"
                . "<tr> <th>Stock actual:</th><td>" . number_format($stock[0]['stock'] * 1 + $cantidadAtencion * 1, 2, ".", ",") . "</td></tr>"
                . "<tr> <th>Cantidad:</th><td>" . number_format($dataPA[0]['cantidad'], 2, ".", ",") . "</td></tr>"
                . "<tr> <th>Estado:</th><td>" . ($patencion['estadoId'] == 3 ? 'Liberado' : 'Comprometido') . "</td></tr>"
                . "</table></p>"
                )
                ;
            }
        }

        $res = ProgramacionAtencion::create()->actualizarEstadoPAtencionDetalle($patencionDetalleId, $nuevoEstado);

        //ENVIAR CORREO DE ATENCIONES 
//        if ($nuevoEstado == 3) {
//            $dataLiberado = ProgramacionAtencion::create()->obtenerPAtencionLiberadaDetalleXPAtencionId($patencionDetalleId);
//            if (!ObjectUtil::isEmpty($dataLiberado)) {
//                $plantillaId = 15;
//                $descripcionCorreo = 'Atención liberada del documento: ' . $dataLiberado[0]['documento_tipo_descripcion'] . ' ' . $dataLiberado[0]['serie_numero'];
//                $tituloCorreo = 'ATENCION LIBERADA';
//                $asuntoCorreo='Atencion liberada';   
//                $res3 = ProgramacionAtencionNegocio::create()->guardarEmailEnvioPAtencion($dataLiberado, $asuntoCorreo, $plantillaId, $descripcionCorreo, $tituloCorreo, 0);
//            }
//        }
        return $res;
    }

    public function obtenerPAtencionXEstadoXFechaProgramada($patencionEstado, $fecha) {
        $fechaProgramada = $this->formatearFechaBD($fecha);
        return ProgramacionAtencion::create()->obtenerPAtencionXEstadoXFechaProgramada($patencionEstado, $fechaProgramada);
    }

    public function guardarEmailEnvioPAtencion($dataPAtencion, $asuntoCorreo, $plantillaId, $descripcionCorreo, $tituloCorreo, $mostrarDocumento = 1) {
        if (!ObjectUtil::isEmpty($dataPAtencion)) {
            $plantilla = EmailPlantillaNegocio::create()->obtenerEmailPlantillaXID($plantillaId);
            $correosPlantilla = EmailPlantillaNegocio::create()->obtenerEmailXDestinatario($plantilla[0]["destinatario"], null, null);

            $correos = '';
            foreach ($correosPlantilla as $email) {
                $correos = $correos . $email . ';';
            }
            //dibujando la cabecera
            $dataDetalle = '<table  border=1 cellspacing=0 cellpadding=5 bordercolor="000000"  width="91.7%">
                        <thead>';
//Acc.	Documento	Cliente	Producto	Cantidad	F.Programada	F.Creación	Estado	Usuario
            $html = '<tr>';
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Item</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>F.Programada</th>";
            if ($mostrarDocumento == 1) {
                $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Tipo de documento</th>";
                $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>S/N</th>";
            }
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Cliente</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Producto</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Cantidad</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>U. Medida</th>";
            $html = $html . '</tr>';

            $dataDetalle = $dataDetalle . $html;
            $dataDetalle = $dataDetalle . '<thead>';
            $dataDetalle = $dataDetalle . '<tbody>';

            foreach ($dataPAtencion as $index => $item) {
                $html = '<tr>';
                $html = $html . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . ($index + 1);
                $html = $html . '<td style=\'text-align:center;font-size:14px;line-height:1.5;\'>' . DateUtil::formatearFechaBDAaCadenaVw($item['fecha_programada']);
                if ($mostrarDocumento == 1) {
                    $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['documento_tipo_descripcion'];
                    $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['serie_numero'];
                }
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['persona_nombre_completo'];
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['bien_descripcion'];
                $html = $html . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . $item['cantidad_programada'] * 1;
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['unidad_medidad_descripcion'];
                $html = $html . '</tr>';

                $dataDetalle = $dataDetalle . $html;
            }

            $dataDetalle = $dataDetalle . '</tbody></table>';
            $descripcion = $descripcionCorreo;

            //logica correo:             
            if (ObjectUtil::isEmpty($asuntoCorreo)) {
                $asunto = $plantilla[0]["asunto"];
            } else {
                $asunto = $asuntoCorreo;
            }
            $cuerpo = $plantilla[0]["cuerpo"];

            $cuerpo = str_replace("[|titulo_email|]", $tituloCorreo, $cuerpo);
            $cuerpo = str_replace("[|descripcion|]", $descripcion, $cuerpo);
            $cuerpo = str_replace("[|detalle_programacion|]", $dataDetalle, $cuerpo);

            $res = EmailEnvioNegocio::create()->insertarEmailEnvio($correos, $asunto, $cuerpo, 1, 1);
//            return $cuerpo;
            return $tituloCorreo . ' ' . $res[0]['vout_mensaje'] . ' Id: ' . $res[0]['id'] . ' <br>';
        } else {
            return '';
        }
    }
    
    public function obtenerDocumentoAtencionEstadoLogico($documentoId){
        return ProgramacionAtencion::create()->obtenerDocumentoAtencionEstadoLogico($documentoId);        
    }
    
    public function obtenerCantidadAtendidaXMovimientoBienId($movimientoBienId){
        return ProgramacionAtencion::create()->obtenerCantidadAtendidaXMovimientoBienId($movimientoBienId);        
    }
}
