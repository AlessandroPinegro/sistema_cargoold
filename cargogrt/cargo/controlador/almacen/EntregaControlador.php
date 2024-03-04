<?php

require_once __DIR__ . '/MovimientoControlador.php';

class EntregaControlador extends MovimientoControlador
{

    public function obtenerConfiguracionesInicialesPedido()
    {
        $opcionId = $this->getOpcionId();
        $usuarioId = $this->getUsuarioId();
        $data = new stdClass();
        $data->persona_activa = PersonaNegocio::create()->obtenerActivas();
        $data->acciones = MovimientoNegocio::create()->obtenerMovimientoTipoAcciones($opcionId);
        $data->estadoNegocioPago = MovimientoNegocio::create()->obtenerDataEstadoEntregaPedidos();
        $data->documento_tipo = array(array("id" => "6", "descripcion" => "V. Boleta"), array("id" => "7", "descripcion" => "V. Factura"));
        $data->dataAgencia = PerfilAgenciaCajaNegocio::create()->obtenerPerfilAgenciaXUsuarioId($usuarioId);
        $data->dataUsuario = UsuarioNegocio::create()->getDataUsuario();

        //PARA MOSTRAR ICONO DE ACCION EDICION EN LEYENDA
        //SI HAY ACCION DE EDICION BUSCAR PERFIL
        foreach ($data->acciones as $index => $accion) {
            $data->acciones[$index]['mostrarAccion'] = 1;

            $mostrarAccEdicion = 0;
            if ($accion['id'] == 19) {//EDICION
                $dataPerfil = PerfilNegocio::create()->obtenerPerfilXUsuarioId($usuarioId);
                foreach ($dataPerfil as $itemPerfil) {
                    if ($itemPerfil['id'] == PerfilNegocio::PERFIL_ADMINISTRADOR_ID || $itemPerfil['id'] == PerfilNegocio::PERFIL_ADMINISTRADOR_TI_ID) {
                        $mostrarAccEdicion = 1;
                    }
                }
                $data->acciones[$index]['mostrarAccion'] = $mostrarAccEdicion;
            }
        }

        return $data;
    }

    public function obtenerDocumentos() {
        // seccion de obtencion de variables
        $usuarioId = $this->getUsuarioId();
        $opcionId = $this->getOpcionId();
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");
        //$arrayMovimientosExcluidos = [227, 230, 231, 232]; //233 allowed
        $arrayMovimientosExcluidos = [];
        // seccion de consumir negocio
        $data = MovimientoNegocio::create()->obtenerDocumentosPedidosXCriterios($opcionId, $criterios, $elemntosFiltrados, $columns, $order, $start);
//        $data = array();
        //INICIO CALCULO ESTADO NEGOCIO
        foreach ($data as $index => $item) {
//            if(!in_array($item['documento_tipo_id'], $arrayMovimientosExcluidos)) {
            if ($item['documento_estado_negocio_descripcion'] == 18) {
                $contadorestados = 0;
                $tamanio = 0;
                $estadoNegocio = MovimientoNegocio::create()->obtenerEstadoNegocioXMovimientoId($item['movimiento_id']);
                if (!ObjectUtil::isEmpty($estadoNegocio)) {
                    foreach ($estadoNegocio as $estadito) {
                        if ($estadito['estadoNegocio'] == 'Completa') {
                            $contadorestados++;
                        }
                    }
                    $tamanio = count($estadoNegocio);
                } else {
                    $contadorestados = -1;
                }
                if ($contadorestados >= $tamanio) {
                    $nuevoEstadoNegocio = "Atención Completa";
                } else {
                    $nuevoEstadoNegocio = "Atención Parcial";
                }
                if ($contadorestados == -1) {
                    $nuevoEstadoNegocio = "No atendido";
                }


                $data[$index]['documento_estado_negocio_descripcion'] = $nuevoEstadoNegocio;
            }
        }
        // FIN CALCULO ESTADO NEGOCIO
        $responseAcciones = MovimientoNegocio::create()->obtenerMovimientoTipoAcciones($opcionId);

//        //SI HAY ACCION DE EDICION BUSCAR PERFIL
//        $mostrarAccEdicion = false;
//        foreach ($responseAcciones as $index => $accion) {
//            if ($accion['id'] == 19) {//EDICION
//                $dataPerfil = PerfilNegocio::create()->obtenerPerfilXUsuarioId($usuarioId);
//                foreach ($dataPerfil as $itemPerfil) {
//                    if ($itemPerfil['id'] == PerfilNegocio::PERFIL_ADMINISTRADOR_ID || $itemPerfil['id'] == PerfilNegocio::PERFIL_ADMINISTRADOR_TI_ID) {
//                        $mostrarAccEdicion = true;
//                    }
//                }
//            }
//        }

        $cantidad_filas = $data[0]["total_filas"];
        $elemntosFiltrados = $cantidad_filas;
        $elementosTotales = $cantidad_filas;
        $tamanio = count($data);

        for ($i = 0; $i < $tamanio; $i++) {
            $stringAcciones = '';
            $estadoId = $data[$i]['documento_estado_id'];
            foreach ($responseAcciones as $jValue) {
                if (($data[$i]['documento_estado_id'] == 2) && ($jValue['id'] == 3 || $jValue['id'] == 4 || $jValue['id'] == 13 || $jValue['id'] == 14 || $jValue['id'] == 19 || ($jValue['id'] == 1 && $data[$i]['efact_ws_estado'] != 0))) {//13 y 14 acciones para QR. 19 Editar
                    // if (($data[$i]['documento_estado_id'] == 2 || $data[$i]['documento_estado_id'] == 3) && ($responseAcciones[$j]['id'] == 3 || $responseAcciones[$j]['id'] == 4 || $responseAcciones[$j]['id'] == 13 || $responseAcciones[$j]['id'] == 14 || $responseAcciones[$j]['id'] == 19 || ($responseAcciones[$j]['id'] == 1 && $data[$i]['documento_estado_id'] == 2))) {//13 y 14 acciones para QR. 19 Editar
                    $stringAcciones .= '';
                } elseif ($jValue['id'] == 1 && $data[$i]['efact_ws_estado'] != 0 && ObjectUtil::isEmpty($data[$i]['efact_pdf_nombre'])) {
                    $stringAcciones .= '';
                } elseif ((($data[$i]['documento_estado_id'] == 2) && ($jValue['id'] == 5))) {
                    $stringAcciones .= '';
                } elseif (($data[$i]['documento_estado_id'] != 2) && ($jValue['id'] == 20)) {
                    $stringAcciones .= '';
                } elseif ($data[$i]['documento_estado_id'] != 4 && $jValue['id'] == 19) {//EDICION
                    $stringAcciones .= '';
                } elseif ($jValue['id'] == 23 && ($data[$i]['efact_ws_estado'] == 1 || $data[$i]['documento_tipo_efact'] != 1)) {//para reenviar
                    $stringAcciones .= '';
                } else {
                    if ($jValue['id'] == 1 || $jValue['id'] == 23) {
                        $datoPivot = $data[$i]['documento_tipo_id'];
                    } else {
                        $datoPivot = $data[$i]['movimiento_id'];
                    }
                    $stringAcciones .= "<a  onclick='" . $jValue['funcion'] . "(" . $data[$i]['documento_id'] . "," . $datoPivot . ")' title='" . $jValue['descripcion'] . "'><b><i class='" . $jValue['icono'] . "' style='color:" . $jValue['color'] . "'></i></b></a>&nbsp;\n";
                }
            }
            $data[$i]['acciones'] = $stringAcciones;
        }

        return $this->obtenerRespuestaDataTable($data, $elemntosFiltrados, $elementosTotales);
    }

}