<?php

require_once __DIR__ . '/AlmacenIndexControlador.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/MovimientoTipoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/MovimientoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/DocumentoTipoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/UnidadNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/PersonaNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/PerfilNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/BienNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/BienPrecioNegocio.php';
//require_once __DIR__ . '/../../util/ImportacionExcel.php';
//require_once __DIR__ . '/../../modeloNegocio/almacen/ExcelNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/EmailEnvioNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/EmailPlantillaNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/TipoCambioNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/OrganizadorNegocio.php';
//require_once __DIR__ . '/../commons/tcpdf/config/lang/eng.php';
//require_once __DIR__ . '/../commons/tcpdf/tcpdf.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/DocumentoTipoDatoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/PagoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/contabilidad/ContDistribucionContableNegocio.php';

class MovimientoControlador extends AlmacenIndexControlador {

    public function obtenerConfiguracionesInicialesManifiestoReparto() {

        $empresaId = $this->getParametro("empresaId");
        $opcionId = $this->getOpcionId();
        $usuarioId = $this->getUsuarioId();
        return MovimientoNegocio::create()->obtenerConfiguracionesInicialesManifiestoReparto($opcionId, $empresaId, $usuarioId);
    }

    public function obtenerConfiguracionesInicialesManifiestoRepartoDetalle() {
        $manifiestoId = $this->getParametro("manifiesto_id");
        $empresaId = $this->getParametro("empresaId");
        $usuarioId = $this->getUsuarioId();
        return MovimientoNegocio::create()->obtenerConfiguracionesInicialesManifiestoRepartoDetalle($manifiestoId, $empresaId, $usuarioId);
    }

    public function imprimirManifiestoXDespachoId() {
        $nroDespacho = $this->getParametro("nroDespacho");
        $vehiculo = $this->getParametro("vehiculo");
        $piloto = $this->getParametro("piloto");
        $copiloto = $this->getParametro("copiloto");
        return MovimientoNegocio::create()->PDFManifiestoXDespachoId($nroDespacho, $vehiculo, $piloto, $copiloto);
    }

    public function imprimirManifiesto() {
        $manifiestoId = $this->getParametro("manifiestoId");
        $vehiculo = $this->getParametro("vehiculo");
        $piloto = $this->getParametro("piloto");
        return MovimientoNegocio::create()->PDFManifiesto($manifiestoId, $vehiculo, $piloto);
    }

    public function generarGuiaRT() {
        $despachoId = $this->getParametro("despachoId");
        $serie = $this->getParametro("serie");
        $numero = $this->getParametro("numero");
        $vehiculoId = $this->getParametro("vehiculoId");
        $pilotoId = $this->getParametro("pilotoId");
        $copilotoId = $this->getParametro("copilotoId");
        $periodoId = $this->getParametro("periodoId");
        $fecha = $this->getParametro("fecha");
        $usuarioId = $this->getUsuarioId();
        $opcionId = $this->getOpcionId();
        $this->setTransaction();
        return MovimientoNegocio::create()->generarGuiaRT($despachoId, $serie, $numero, $vehiculoId, $pilotoId, $copilotoId, $periodoId, $fecha, $usuarioId, $opcionId);
    }

    public function generarGuiaRTXManifiestoId() {
        $manifiestoId = $this->getParametro("manifiestoId");
        $serie = $this->getParametro("serie");
        $numero = $this->getParametro("numero");
        $vehiculo = $this->getParametro("vehiculo");
        $piloto = $this->getParametro("piloto");
        $periodo = $this->getParametro("periodo");
        $fecha = $this->getParametro("fecha");
        $usuarioId = $this->getUsuarioId();
        $opcionId = $this->getOpcionId();
        $this->setTransaction();
        return MovimientoNegocio::create()->generarGuiaRTXManifiestoId($manifiestoId, $serie, $numero, $vehiculo, $piloto, $periodo, $fecha, $usuarioId, $opcionId);
    }

    public function verificar() {
        $nroDespacho = $this->getParametro("nroDespacho");
        return MovimientoNegocio::create()->verificar($nroDespacho);
    }

    public function verificarManifiestoReparto() {
        $nroDespacho = $this->getParametro("nroDespacho");
        return MovimientoNegocio::create()->verificarManifiestoReparto($nroDespacho);
    }

    public function generarPDFManifiesto() {
        $nroDespacho = $this->getParametro("nroDespacho");
        return MovimientoNegocio::create()->PDFManifiestoReparto($nroDespacho);
    }

    public function obtenerConfiguracionesInicialesDespacho() {

//        $usuarioId = $this->getUsuarioId();
        $opcionId = $this->getOpcionId();
        $usuarioId = $this->getUsuarioId();
        $empresaId = $this->getParametro("empresaId");
        return MovimientoNegocio::create()->obtenerConfiguracionesInicialesDespacho($opcionId, $empresaId, $usuarioId);
    }

    public function buscarDespacho() {

        $bus_id = $this->getParametro("bus_id");
        $fecha_salida = $this->getParametro("fecha_salida");
        $conductor_id = $this->getParametro("conductor_id");
        $nro_manifiesto = $this->getParametro("nro_manifiesto");
        $origin_id = $this->getParametro("origin_id");
        $destino_id = $this->getParametro("destino_id");
        $estado_id = $this->getParametro("estado_id");
        $copiloto_id = $this->getParametro("copiloto_id");
        $nro_despacho = $this->getParametro("nro_despacho");
        return MovimientoNegocio::create()->obtenertablaDespacho($bus_id, $fecha_salida, $conductor_id, $copiloto_id, $nro_manifiesto, $origin_id, $destino_id, $estado_id, $nro_despacho);
    }

    public function buscarManifiestoReparto() {

        $bus_id = $this->getParametro("bus_id");
        $fecha_salida = $this->getParametro("fecha_salida");
        $conductor_id = $this->getParametro("conductor_id");
        $nro_manifiesto = $this->getParametro("nro_manifiesto");
        $origin_id = $this->getParametro("origin_id");
        $estado_id = $this->getParametro("estado_id");
        $usuarioId = $this->getUsuarioId();
        return MovimientoNegocio::create()->obtenerConfiguracionesInicialesManifiestoReparto($bus_id, $fecha_salida, $conductor_id, $nro_manifiesto, $origin_id, $estado_id, $usuarioId);
    }

    public function obtenerConfiguracionesInicialesManifiesto() {

        $despacho_id = $this->getParametro("despacho_id");
        $empresaId = $this->getParametro("empresaId");
        $usuarioId = $this->getUsuarioId();
//        $manifiestos = $this->getParametro("manifiestos");
        return MovimientoNegocio::create()->obtenerConfiguracionesInicialesManifiesto($despacho_id, $empresaId, $usuarioId);
    }

    public function filtrarManifiestos() {
        $despacho_id = $this->getParametro("despacho_id");
        $destino_id = $this->getParametro("destino_id");
        $manifiestos = $this->getParametro("manifiestos");
        return MovimientoNegocio::create()->filtrarManifiestos($despacho_id, $destino_id, $manifiestos);
    }

    public function obtenerConfiguracionesIniciales() {
        $opcionId = $this->getOpcionId();
        $empresaId = $this->getParametro("empresaId");
        $documentoId = $this->getParametro("documentoId");
        $usuarioId = $this->getUsuarioId();
        $movimientoTipoId = $this->getParametro("movimientoTipoId");

        return MovimientoNegocio::create()->obtenerConfiguracionInicial($opcionId, $empresaId, $usuarioId, $documentoId, $movimientoTipoId);
    }

    public function ObtenerRelacionSerieCorrelativo() {
        $opcionId = $this->getOpcionId();
        $empresaId = '2';
        $documentoId = null;
        $usuarioId = $this->getUsuarioId();
        $movimientoTipoId = null;
        $comprobanteId = $this->getParametro("comprobanteId");

        return MovimientoNegocio::create()->ObtenerRelacionSerieCorrelativo($opcionId, $empresaId, $usuarioId, $documentoId, $movimientoTipoId,$comprobanteId);
    }

    public function obtenerDocumentoTipo() {
        $opcionId = $this->getOpcionId();
        $usuarioId = $this->getUsuarioId();
        $data = MovimientoNegocio::create()->obtenerDocumentoTipo($opcionId);
        $data->personasMayorMovimientos = PersonaNegocio::create()->obtenerPersonasMayorMovimiento($opcionId);
        $data->moneda = MonedaNegocio::create()->obtenerComboMoneda();
        $data->columna = MovimientoNegocio::create()->obtenerMovimientoTipoColumnaLista($opcionId);
        $data->acciones = MovimientoNegocio::create()->obtenerMovimientoTipoAcciones($opcionId);
        $data->estadoNegocioPago = MovimientoNegocio::create()->obtenerDataEstadoNegocioPago();
        $data->movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);

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

    public function obtenerUnidadMedida() {
//        $indice = $this->getParametro("indice");
        $bienId = $this->getParametro("bienId");
        $unidadMedidaId = $this->getParametro("unidadMedidaId");
        $precioTipoId = $this->getParametro("precioTipoId");
        $monedaId = $this->getParametro("monedaId");
        $fechaEmision = $this->getParametro("fechaEmision");
//        $opcionId = $this->getOpcionId();

        $data = MovimientoNegocio::create()->obtenerUnidadMedida($bienId, $unidadMedidaId, $precioTipoId, $monedaId, $fechaEmision);
        return $data;
    }

    public function obtenerPreciosEquivalentes() {
        $indice = $this->getParametro("indice");
        $bienId = $this->getParametro("bienId");
        $unidadId = $this->getParametro("unidadMedidaId");
        $precioTipoId = $this->getParametro("precioTipoId");
        $monedaId = $this->getParametro("monedaId");
        $fechaEmision = $this->getParametro("fechaEmision");
//        $incluyeIGV = $this->getParametro("incluyeIGV");    

        $opcionId = $this->getOpcionId();
        $respuesta = new stdClass();
//        $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);

        $dataPrecio = BienPrecioNegocio::create()->obtenerBienPrecioXBienIdXUnidadMedidaIdXPrecioTipoIdXMonedaId($bienId, $unidadId, $precioTipoId, $monedaId);
        if (ObjectUtil::isEmpty($dataPrecio)) {
            $precio = 0;
        } else {
            $precio = $dataPrecio[0]["precio"];
        }

        $precioCompra = BienPrecioNegocio::create()->obtenerPrecioCompraPromedio($bienId, $unidadId, $fechaEmision);

        if ($monedaId == 4) {
            $equivalenciaDolar = TipoCambioNegocio::create()->obtenerTipoCambioXFechaUltima($fechaEmision);
            $precioCompra = $precioCompra / $equivalenciaDolar[0]['equivalencia_venta'];
        }

        $respuesta->indice = $indice;

        $respuesta->precioCompra = $precioCompra;
        $respuesta->precio = $precio;
        return $respuesta;
    }

    public function obtenerPrecioUnitario() {
        $bienId = $this->getParametro("bienId");
        $unidadMedidaId = $this->getParametro("unidadMedidaId");
        $opcionId = $this->getOpcionId();

        $respuesta = new stdClass();
        $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);
        $respuesta->precio = MovimientoTipoNegocio::create()->obtenerPrecio($bienId, $movimientoTipo, $unidadMedidaId);
        return $respuesta;
    }

    public function obtenerBienPrecio() {
        $bienId = $this->getParametro("bienId");
        $unidadMedidaId = $this->getParametro("unidadMedidaId");
        $precioTipoId = $this->getParametro("precioTipoId");
        $monedaId = $this->getParametro("monedaId");

        $data = BienPrecioNegocio::create()->obtenerBienPrecioXBienIdXUnidadMedidaIdXPrecioTipoIdXMonedaId($bienId, $unidadMedidaId, $precioTipoId, $monedaId);
        if (ObjectUtil::isEmpty($data)) {
            $data = array(array('precio' => 0));
        }
        return $data;
    }

    public function obtenerStockActual() {
        //$opcionId = $this->getOpcionId();
        $bienId = $this->getParametro("bienId");
        $indice = $this->getParametro("indice");
        $organizadorId = $this->getParametro("organizadorId");
        $unidadMedidaId = $this->getParametro("unidadMedidaId");
        $organizadorDestinoId = $this->getParametro("organizadorDestinoId");

        $stock = MovimientoNegocio::create()->obtenerStockActual($bienId, $indice, $organizadorId, $unidadMedidaId, $organizadorDestinoId);
        return $stock;
    }

    /* public function enviar() {
      $opcionId = $this->getOpcionId();
      $usuarioId = $this->getUsuarioId();
      $documentoTipoId = $this->getParametro("documentoTipoId");
      $camposDinamicos = $this->getParametro("camposDinamicos");
      $detalle = $this->getParametro("detalle");
      $this->setTransaction();
      return MovimientoNegocio::create()->guardar($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle);
      } */

    public function enviar() {
//        return $this->params;
        $this->setTransaction();
        $opcionId = $this->getOpcionId();
        $usuarioId = $this->getUsuarioId();
        $documentoTipoId = $this->getParametro("documentoTipoId");
        $contOperacionTipoId = $this->getParametro("contOperacionTipoId");
        $camposDinamicos = $this->getParametro("camposDinamicos");
        $detalle = $this->getParametro("detalle");
        $documentoARelacionar = $this->getParametro("documentoARelacionar");
        $valorCheck = $this->getParametro("valor_check");
        $comentario = $this->getParametro("comentario");
        $checkIgv = $this->getParametro("checkIgv");
        $afectoAImpuesto = $this->getParametro("afectoAImpuesto");
        $monedaId = $this->getParametro("monedaId");
        $empresaId = $this->getParametro("empresaId");
        $accionEnvio = $this->getParametro("accionEnvio");
        $tipoPago = $this->getParametro("tipoPago");
        $listaPagoProgramacion = $this->getParametro("listaPagoProgramacion");
        $anticiposAAplicar = $this->getParametro("anticiposAAplicar");
        $percepcion = $this->getParametro("percepcion");
        $periodoId = $this->getParametro("periodoId");
        $origen_destino = $this->getParametro("origen_destino");
        $importeTotalInafectas = $this->getParametro("importeTotalInafectas");
        $detalleDistribucion = $this->getParametro("detalleDistribucion");
        $distribucionObligatoria = $this->getParametro("distribucionObligatoria");
        $datosExtras = $this->getParametro("datosExtras");

        return MovimientoNegocio::create()->validarGenerarDocumentoAdicional($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $documentoARelacionar, $valorCheck, $comentario, $checkIgv, $monedaId, $accionEnvio, $tipoPago, $listaPagoProgramacion, $anticiposAAplicar, $periodoId, $percepcion, $origen_destino, $importeTotalInafectas, $detalleDistribucion, $contOperacionTipoId, $distribucionObligatoria, $afectoAImpuesto, $datosExtras);
    }

    public function guardarDocumentoGenerado() {
        $this->setTransaction();
        $documentoARelacionarVenta = $this->getParametro("documentoARelacionar");
        $empresaId = $this->getParametro("empresaId");
        $checkIgv = $this->getParametro("checkIgv");
        $monedaId = $this->getParametro("monedaId");
        $accionEnvio = $this->getParametro("accionEnvio");

        if (ObjectUtil::isEmpty($documentoARelacionarVenta)) {
            $documentoARelacionarVenta = array();
        }

        //guia
        if (!ObjectUtil::isEmpty($this->getParametro("detalleGuia"))) {
            //$opcionId = Configuraciones::VENTAS_FALTANTES_OPCION_ID; // de ventas faltantes
            $usuarioId = $this->getUsuarioId();
//            $documentoTipoId = Configuraciones::DOCUMENTO_TIPO_GUIA_INTERNA_ID;
            $camposDinamicosGuia = $this->getParametro("camposDinamicos");
            $detalle = $this->getParametro("detalleGuia");
            $totalGuia = $this->getParametro("totalGuia");
            $documentoARelacionar = null;
            $valorCheck = null;
            $comentario = null;

            $dataTipoDocumentoId = array();

            foreach ($detalle as $index => $itemDet) {
                if (!in_array($itemDet["tipoDocumentoId"], $dataTipoDocumentoId)) {
                    array_push($dataTipoDocumentoId, $itemDet["tipoDocumentoId"]);
                }
            }

            $dataDetalleGuias = array();

            foreach ($detalle as $index => $itemDet) {
                foreach ($dataTipoDocumentoId as $indexOrg => $orgId) {
                    if ($itemDet["tipoDocumentoId"] == $orgId) {
                        if (ObjectUtil::isEmpty($dataDetalleGuias[$indexOrg])) {
                            $dataDetalleGuias[$indexOrg] = array();
                        }

                        array_push($dataDetalleGuias[$indexOrg], $itemDet);
                    }
                }
            }

            //guardar array notas
            foreach ($dataDetalleGuias as $index => $itemDet) {

                $documentoTipoId = $itemDet[0]["tipoDocumentoId"];
                //Obteniendo la opcion del documento a generar
                $opcionId = $this->getOpcionId(); //opcion actual
                $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);
                $dataOpcion = DocumentoTipoNegocio::create()->obtenerOpcionGenerarDocumentoXMovimientoTipoIdXDocumentoTipoId($movimientoTipo[0]["id"], $documentoTipoId);

                $opcionId = $dataOpcion[0]['opcion_id'];

                //obtenemos el precio tipo del movimiento
                $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);
                $precioTipoId = $movimientoTipo[0]['precio_tipo_id'];

                $total = 0;
                foreach ($itemDet as $i => $itemDetDet) {
                    $total = $total + $itemDetDet["cantidad"] * $itemDetDet["precio"];

                    if (!ObjectUtil::isEmpty($precioTipoId)) {
                        $itemDet[$i]['precioTipoId'] = $precioTipoId;
                    }
                }

                //cabecera del documento
                $configuraciones = DocumentoTipoNegocio::create()->obtenerDocumentoTipoDatoSimple($itemDet[0]["tipoDocumentoId"]);

                foreach ($configuraciones as $indexConfig => $itemDtd) {
                    foreach ($camposDinamicosGuia as $indexCampos => $valorDtd) {
                        if ((int) $itemDtd["tipo"] === (int) $valorDtd["tipo"]) {
                            $camposDinamicosGuia[$indexCampos]["id"] = $itemDtd["id"];
                            if ($itemDtd["tipo"] == 8) {
                                $camposDinamicosGuia[$indexCampos]["valor"] = DocumentoNegocio::create()->obtenerNumeroAutoIncrementalXDocumentoTipo($documentoTipoId);
                            }
                            if ($itemDtd["tipo"] == 14) {
                                $camposDinamicosGuia[$indexCampos]["valor"] = $total;
                            }
                        }
                    }
                }

                $resGuia = MovimientoNegocio::create()->guardar($opcionId, $usuarioId, $documentoTipoId, $camposDinamicosGuia, $itemDet, $documentoARelacionar, $valorCheck, $comentario, $checkIgv, $monedaId);
                array_push($documentoARelacionarVenta, array('documentoId' => $resGuia, 'movimientoId' => '', 'detalleLink' => '', 'posicion' => ''));
            }
        }

        //nota
        if (!ObjectUtil::isEmpty($this->getParametro("detallePedido"))) {
//            $opcionId = Configuraciones::SOLICITUDES_DE_PEDIDO_OPCION_ID;

            if ($empresaId == 2) {
                $opcionId = Configuraciones::SOLICITUDES_DE_PEDIDO_OPCION_ID_EMPRESA2; // de ventas faltantes
                $documentoTipoId = Configuraciones::DOCUMENTO_TIPO_SOLICITUD_COMPRA_ID_EMPRESA2;
            } else if ($empresaId == 6) {
                $opcionId = Configuraciones::SOLICITUDES_DE_PEDIDO_OPCION_ID_EMPRESA6; // de ventas faltantes
                $documentoTipoId = Configuraciones::DOCUMENTO_TIPO_SOLICITUD_COMPRA_ID_EMPRESA6;
            } else if ($empresaId == 4) {
                $opcionId = Configuraciones::SOLICITUDES_DE_PEDIDO_OPCION_ID_EMPRESA4; // de ventas faltantes
                $documentoTipoId = Configuraciones::DOCUMENTO_TIPO_SOLICITUD_COMPRA_ID_EMPRESA4;
            }

            $usuarioId = $this->getUsuarioId();
            $camposDinamicosNota = $this->getParametro("camposDinamicos");
            $detalle = $this->getParametro("detallePedido");
            //$proveedorId = $this->getParametro("proveedorId");
            $totalProv = $this->getParametro("totalProv");
            $documentoARelacionar = null;
            $valorCheck = null;
            $comentario = null;
            $dataProveedor = array();

            foreach ($detalle as $index => $itemDet) {
                if (!in_array($itemDet["proveedorId"], $dataProveedor)) {
                    array_push($dataProveedor, $itemDet["proveedorId"]);
                }
            }

            $dataDetalleNotas = array();
            //$dataDetalleNotas[]=array();
            foreach ($detalle as $index => $itemDet) {
                foreach ($dataProveedor as $indexProv => $provId) {
                    if ($itemDet["proveedorId"] == $provId) {
                        if (ObjectUtil::isEmpty($dataDetalleNotas[$indexProv])) {
                            $dataDetalleNotas[$indexProv] = array();
                        }

                        array_push($dataDetalleNotas[$indexProv], $itemDet);
                    }
                }
            }


            //configuracion los id de documento_tipo_dato igualar
            $configuraciones = DocumentoTipoNegocio::create()->obtenerDocumentoTipoDatoSimple($documentoTipoId);

            //obtenemos el precio tipo del movimiento
            $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);
            $precioTipoId = $movimientoTipo[0]['precio_tipo_id'];

            //guardar array notas

            foreach ($dataDetalleNotas as $index => $itemDet) {
                $total = 0;
                foreach ($itemDet as $i => $itemDetDet) {
                    $total = $total + $itemDetDet["cantidad"] * $itemDetDet["precio"];

                    //LA SOLICITUD DE COMPRA TIENE PRECIO TIPO A COMPRA = 1 
                    if (!ObjectUtil::isEmpty($precioTipoId)) {
                        $itemDet[$i]['precioTipoId'] = $precioTipoId;
                    }
                }

                foreach ($configuraciones as $indexConfig => $itemDtd) {
                    foreach ($camposDinamicosNota as $indexCampos => $valorDtd) {
                        if ((int) $itemDtd["tipo"] === (int) $valorDtd["tipo"]) {
                            $camposDinamicosNota[$indexCampos]["id"] = $itemDtd["id"];
                            if ($itemDtd["tipo"] == 8) {
                                $camposDinamicosNota[$indexCampos]["valor"] = DocumentoNegocio::create()->obtenerNumeroAutoIncrementalXDocumentoTipo($documentoTipoId);
                                $numeroDoc = $camposDinamicosNota[$indexCampos]["valor"];
                            }
                            if ($itemDtd["tipo"] == 5) {
                                $camposDinamicosNota[$indexCampos]["valor"] = $itemDet[0]["proveedorId"];
                                $proveedorId = $itemDet[0]["proveedorId"];
                            }
                            if ($itemDtd["tipo"] == 14) {//Importe Total
                                $camposDinamicosNota[$indexCampos]["valor"] = $total;
                            }
                            if ($itemDtd["tipo"] == 15) {//IGV
                                $camposDinamicosNota[$indexCampos]["valor"] = 0.18 * ($total / 1.18);
                            }
                            if ($itemDtd["tipo"] == 16) {// Importe Sub Total
                                $camposDinamicosNota[$indexCampos]["valor"] = $total / 1.18;
                            }
                            if ($itemDtd["tipo"] == 7) {
                                $serieDoc = $camposDinamicosNota[$indexCampos]["valor"];
                            }
                        }
                    }
                }

                $resNota = MovimientoNegocio::create()->guardar($opcionId, $usuarioId, $documentoTipoId, $camposDinamicosNota, $itemDet, $documentoARelacionar, $valorCheck, $comentario, $checkIgv, $monedaId);
                array_push($documentoARelacionarVenta, array('documentoId' => $resNota, 'movimientoId' => '', 'detalleLink' => '', 'posicion' => ''));

                //logica correo:             
//                $plantilla = EmailPlantillaNegocio::create()->obtenerEmailPlantillaXID(2);
//                $personaData = PersonaNegocio::create()->obtenerPersonaXId($proveedorId);
//
//                $trtdInicio = "<tr><td style='text-align:left;padding:0 55px 20px;font-size:14px;line-height:1.5;width:80%'><p align='justify' style='align: justify; line-height:1.5;'>";
//                $trtdFin = "</p></td></tr>";
//                $detalleEmail = "";
//                $numSerie = $serieDoc . " - " . $numeroDoc;
//                foreach ($itemDet as $i => $itemDetDet) {
//                    $cantidad = $itemDetDet["cantidad"];
//                    $bien = $itemDetDet["bienDesc"];
//                    $unidadMedida = $itemDetDet["unidadMedidaDesc"];
//
//                    $detalleEmail = $detalleEmail . $trtdInicio . $bien . " cantidad: " . $cantidad . " " . $unidadMedida . $trtdFin;
//                }
//
//                $bienDesc = $bien[0]["bien_desc"];
//                $umDesc = $bien[0]["um_desc"];
//                $asunto = $plantilla[0]["asunto"];
//                $cuerpo = $plantilla[0]["cuerpo"];
//
//                //$destinatario = $plantilla[0]["destinatario"];
//                if (ObjectUtil::isEmpty($personaData[0]["email"])) {
//                    $destinatario = $plantilla[0]["destinatario"];
//                } else {
//                    $destinatario = $personaData[0]["email"];
//                }
//
//                $asunto = str_replace("[|pedido_numero|]", $numSerie, $asunto);
//                $cuerpo = str_replace("[|pedido_numero|]", $numSerie, $cuerpo);
//                $cuerpo = str_replace("[|detalle_bien|]", $detalleEmail, $cuerpo);
//
//                EmailEnvioNegocio::create()->insertarEmailEnvio($destinatario, $asunto, $cuerpo, 1, $usuarioId);
                //Fin logica de correo
            }
        }

        //venta
        $opcionId = $this->getOpcionId();
        $usuarioId = $this->getUsuarioId();
        $documentoTipoId = $this->getParametro("documentoTipoId");
        $camposDinamicos = $this->getParametro("camposDinamicos");
        $detalle = $this->getParametro("detalleVenta");
        //$documentoARelacionarVenta = $this->getParametro("documentoARelacionar");
        //$valorCheckVenta = $this->getParametro("valor_check");
        $valorCheckVenta = 1;
        $comentario = $this->getParametro("comentario");

//        return MovimientoNegocio::create()->guardar($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $documentoARelacionarVenta, $valorCheckVenta, $comentario,$checkIgv,$monedaId);
        return MovimientoNegocio::create()->guardarXAccionEnvio($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $documentoARelacionarVenta, $valorCheckVenta, $comentario, $checkIgv, $monedaId, $accionEnvio);
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
        $data = MovimientoNegocio::create()->obtenerDocumentosXCriterios($opcionId, $criterios, $elemntosFiltrados, $columns, $order, $start);

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

        //SI HAY ACCION DE EDICION BUSCAR PERFIL
        $mostrarAccEdicion = false;
        foreach ($responseAcciones as $index => $accion) {
            if ($accion['id'] == 19) {//EDICION
                $dataPerfil = PerfilNegocio::create()->obtenerPerfilXUsuarioId($usuarioId);
                foreach ($dataPerfil as $itemPerfil) {
                    if ($itemPerfil['id'] == PerfilNegocio::PERFIL_ADMINISTRADOR_ID || $itemPerfil['id'] == PerfilNegocio::PERFIL_ADMINISTRADOR_TI_ID) {
                        $mostrarAccEdicion = true;
                    }
                }
            }
        }

        $response_cantidad_total = MovimientoNegocio::create()->obtenerCantidadDocumentosXCriterio($opcionId, $criterios, $elemntosFiltrados, $columns, $order, $start);

        // seccion de respuesta
//        $response_cantidad_total[0]['total'];
        $elemntosFiltrados = $response_cantidad_total[0]['total'];
        $elementosTotales = $response_cantidad_total[0]['total'];

//        $cantidad_filas = $data[0]["cantidad_filas"];
//        $elemntosFiltrados = $cantidad_filas;
//        $elementosTotales = $cantidad_filas;

        $tamanio = count($data);

        for ($i = 0; $i < $tamanio; $i++) {
            $stringAcciones = '';
            for ($j = 0; $j < count($responseAcciones); $j++) {
                if (($data[$i]['documento_estado_id'] == 2) && ($responseAcciones[$j]['id'] == 3 || $responseAcciones[$j]['id'] == 4 || $responseAcciones[$j]['id'] == 13 || $responseAcciones[$j]['id'] == 14 || $responseAcciones[$j]['id'] == 19 || ($responseAcciones[$j]['id'] == 1 && $data[$i]['efact_ws_estado'] != 0))) {//13 y 14 acciones para QR. 19 Editar
                    // if (($data[$i]['documento_estado_id'] == 2 || $data[$i]['documento_estado_id'] == 3) && ($responseAcciones[$j]['id'] == 3 || $responseAcciones[$j]['id'] == 4 || $responseAcciones[$j]['id'] == 13 || $responseAcciones[$j]['id'] == 14 || $responseAcciones[$j]['id'] == 19 || ($responseAcciones[$j]['id'] == 1 && $data[$i]['documento_estado_id'] == 2))) {//13 y 14 acciones para QR. 19 Editar     
                    $stringAcciones .= '';
                } elseif ($responseAcciones[$j]['id'] == 1 && $data[$i]['efact_ws_estado'] != 0 && ObjectUtil::isEmpty($data[$i]['efact_pdf_nombre'])) {
                    $stringAcciones .= '';
                } elseif ((($data[$i]['documento_estado_id'] == 2) && ($responseAcciones[$j]['id'] == 5))) {
                    $stringAcciones .= '';
                } elseif (($data[$i]['documento_estado_id'] != 2) && ($responseAcciones[$j]['id'] == 20)) {
                    $stringAcciones .= '';
                } elseif (!$mostrarAccEdicion && $responseAcciones[$j]['id'] == 19) {//EDICION
                    $stringAcciones .= '';
                } elseif ($responseAcciones[$j]['id'] == 23 && ($data[$i]['efact_ws_estado'] == 1 || $data[$i]['documento_tipo_efact'] != 1)) {//para reenviar  
                    $stringAcciones .= '';
                } else {
                    if ($responseAcciones[$j]['id'] == 1 || $responseAcciones[$j]['id'] == 23) {
                        $datoPivot = $data[$i]['documento_tipo_id'];
                    } else {
                        $datoPivot = $data[$i]['movimiento_id'];
                    }
                    $stringAcciones .= "<a  onclick='" . $responseAcciones[$j]['funcion'] . "(" . $data[$i]['documento_id'] . "," . $datoPivot . ")' title='" . $responseAcciones[$j]['descripcion'] . "'><b><i class='" . $responseAcciones[$j]['icono'] . "' style='color:" . $responseAcciones[$j]['color'] . "'></i></b></a>&nbsp;\n";
                }
            }
            $data[$i]['acciones'] = $stringAcciones;
        }

        return $this->obtenerRespuestaDataTable($data, $elemntosFiltrados, $elementosTotales);
    }

    public function obtenerDocumentoTipoDato() {
        $documentoTipoId = $this->getParametro("documentoTipoId");
        $usuarioId = $this->getUsuarioId();
        //$data=DocumentoTipoNegocio::create()->obtenerDocumentoTipoDato($documentoTipoId);
        return DocumentoTipoNegocio::create()->obtenerDocumentoTipoDato($documentoTipoId, $usuarioId);
    }

    public function getAllPersonaTipo() {
        return PersonaNegocio::create()->getAllPersonaTipo();
    }

    public function enviarEImprimir() {

        $this->setTransaction();
        $opcionId = $this->getOpcionId();
        $usuarioId = $this->getUsuarioId();
        $documentoTipoId = $this->getParametro("documentoTipoId");
        $camposDinamicos = $this->getParametro("camposDinamicos");
        $detalle = $this->getParametro("detalle");
        $documentoARelacionar = $this->getParametro("documentoARelacionar");
        $valorCheck = $this->getParametro("valor_check");
        $comentario = $this->getParametro("comentario");
        $checkIgv = $this->getParametro("checkIgv");
        $monedaId = $this->getParametro("monedaId");

        return MovimientoNegocio::create()->enviarEImprimir($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $documentoARelacionar, $valorCheck, $comentario, $checkIgv, $monedaId);
//        $opcionId = $this->getOpcionId();
//        $usuarioId = $this->getUsuarioId();
//        $documentoTipoId = $this->getParametro("documentoTipoId");
//        $camposDinamicos = $this->getParametro("camposDinamicos");
//        $detalle = $this->getParametro("detalle");
//        $this->setTransaction();
//        return MovimientoNegocio::create()->enviarEImprimir($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle);
    }

    public function obtenerPersonas() {
        $documentoTipoId = $this->getParametro("documentoTipoId");
        $usuarioId = $this->getUsuarioId();
        return DocumentoTipoNegocio::create()->obtenerPersonas($documentoTipoId, $usuarioId);
//       return PersonaNegocio::create()->obtenerActivas();
    }

    public function obtenerStockPorBien() {
        $bienId = $this->getParametro("bienId");
        $empresaId = $this->getParametro("empresaId");
        return BienNegocio::create()->obtenerStockPorBien($bienId, $empresaId);
    }

    public function obtenerPrecioPorBien() {
        $bienId = $this->getParametro("bienId");
        $unidadMedidaId = $this->getParametro("unidadMedidaId");
        $monedaId = $this->getParametro("monedaId");
        $incluyeIGV = $this->getParametro("incluyeIGV");

        $opcionId = $this->getOpcionId();

//        return BienNegocio::create()->obtenerPrecioPorBien($bienId);
        return MovimientoNegocio::create()->obtenerBienPrecioXBienId($bienId, $unidadMedidaId, $monedaId, $opcionId);
    }

    public function imprimir() {
        $documentoId = $this->getParametro("id");
        $documentoTipoId = $this->getParametro("documento_tipo_id");
        $usuarioId = $this->getUsuarioId();

        return MovimientoNegocio::create()->imprimirExportarPDFDocumento($documentoTipoId, $documentoId, $usuarioId);
    }

    public function anular() {
        $this->setTransaction();
        $documentoId = $this->getParametro("id");
        $documentoEstadoId = 2;
        $usuarioId = $this->getUsuarioId();

        //VALIDO SI ES FACTURACION ELECTRONICA PARA QUE INGRESE MOTIVO DE ANULACION
        $dataEmpresa = EmpresaNegocio::create()->obtenerEmpresaXDocumentoId($documentoId);
        $documentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXDocumentoId($documentoId);
        $idNegocio = $documentoTipo[0]['identificador_negocio'];
        $documento = DocumentoNegocio::create()->obtenerXId($documentoId, $documentoTipo[0]['id']);
        $serie = $documento[0]['serie'];
        if ($dataEmpresa[0]['efactura'] == 1 && (
//                $idNegocio == DocumentoTipoNegocio::IN_BOLETA_VENTA ||
                $idNegocio == DocumentoTipoNegocio::IN_FACTURA_VENTA ||
                ($idNegocio == DocumentoTipoNegocio::IN_NOTA_CREDITO_VENTA && $documento[0]['serie'][0] == 'F') ||
                $idNegocio == DocumentoTipoNegocio::IN_NOTA_DEBITO_VENTA )) {

            $respuesta->motivoAnulacion = 1;
            $respuesta->documento = $documento;
            return $respuesta;
        } else { //añadimos $idNegocio
            if (empty($idNegocio) || is_null($idNegocio)) {
                $idNegocio = 0;
            }
            return MovimientoNegocio::create()->anularDocumento($documentoId, $documentoEstadoId, $usuarioId, $idNegocio, $serie);
        }
    }

    public function anularDocumentoMensaje() {
        $this->setTransaction();
        $documentoId = $this->getParametro("documentoId");
        $motivoAnulacion = $this->getParametro("motivoAnulacion");
        $documentoEstadoId = 2;
        $usuarioId = $this->getUsuarioId();

        return MovimientoNegocio::create()->anularDocumentoMensaje($documentoId, $motivoAnulacion, $documentoEstadoId, $usuarioId);
    }

    public function anularDocumentoMensajePedido() {
        $this->setTransaction();
        $documentoId = $this->getParametro("documentoId");
        $motivoAnulacion = $this->getParametro("motivoAnulacion");
        $documentoEstadoId = 2;
        $usuarioId = $this->getUsuarioId();

        return MovimientoNegocio::create()->anularDocumentoMensajePedido($documentoId, $motivoAnulacion, $documentoEstadoId, $usuarioId);
    }

    public function aprobar() {
        $documentoId = $this->getParametro("id");
        $documentoEstadoId = 3;
        $usuarioId = $this->getUsuarioId();
        return MovimientoNegocio::create()->aprobar($documentoId, $documentoEstadoId, $usuarioId);
    }

    public function obtenerDocumentoRelacionVisualizar() {
        $documentoId = $this->getParametro("documentoId");
        $movimientoId = $this->getParametro("movimientoId");
        $data = MovimientoNegocio::create()->visualizarDocumento($documentoId, $movimientoId);

        $data->configuracionEditable = MovimientoNegocio::create()->obtenerDocumentoTipoDatoEditableXDocumentoId($documentoId);
        $data->emailPersona = DocumentoNegocio::create()->obtenerPersonaXDocumentoId($documentoId);
        $dataMovimientoTipo = MovimientoTipoNegocio::create()->obtenerXDocumentoId($documentoId);
        $data->dataAccionEnvio = MovimientoTipoNegocio::create()->obtenerMovimientoTipoAccionesVisualizacion($dataMovimientoTipo[0]['movimiento_tipo_id']);
        $data->dataMovimientoTipoColumna = MovimientoTipoNegocio::create()->obtenerMovimientoTipoColumna($dataMovimientoTipo[0]['movimiento_tipo_id']);
        $data->organizador = OrganizadorNegocio::create()->obtenerXMovimientoTipo($dataMovimientoTipo[0]['movimiento_tipo_id']);
        $data->dataDocumentoAdjunto = DocumentoNegocio::create()->obtenerDocumentoAdjuntoXDocumentoId($documentoId);
        $data->dataDistribucionContable = ContDistribucionContableNegocio::create()->obtenerContDistribucionContableXDocumentoId($documentoId);
//        $data->dataVoucherContable = ContVoucherDetalleNegocio::create()->obtenerVoucherDetalleXDocumentoId($documentoId, ContLibroNegocio::CLASIFICACION_COMPRAS);

        return $data;
    }

    public function obtenerDocumentoArchivosVisualizar() {
        $documentoId = $this->getParametro("documentoId");
        $movimientoId = $this->getParametro("movimientoId");
        $data = MovimientoNegocio::create()->visualizarDocumento($documentoId, $movimientoId);

        $data->dataDocumentoAdjunto = DocumentoNegocio::create()->obtenerDocumentoAdjuntoXDocumentoId($documentoId);

        return $data;
    }

    public function guardarArchivosXDocumentoID() {
        $this->setTransaction();
        $lstDocumento = $this->getParametro("lstDocumento");
        $lstDocEliminado = $this->getParametro("lstDocEliminado");
        $documentoId = $this->getParametro("documentoId");
        $usuCreacion = $this->getUsuarioId();
        return MovimientoNegocio::create()->guardarArchivosXDocumentoID($documentoId, $lstDocumento, $lstDocEliminado, $usuCreacion);
    }

    public function exportarReporteExcel() {

        // seccion de obtencion de variables
        $opcionId = $this->getOpcionId();
        $criterios = $this->getParametro("criterios");
        //$elemntosFiltrados = $this->getParametro("length");
        //$elemntosFiltrados = 200;
        //$order = $this->getParametro("order");
        //$columns = $this->getParametro("columns");
        //$start = $this->getParametro("start");
        //$start = 0;
        //$anio = $this->getParametro("anio");
        //$mes = $this->getParametro("mes");
        //$origen = "Movimientos";
        //$usuCreacion = $this->getUsuarioId();
        //return ExcelNegocio::create()->generarReporte($anio, $mes, $origen, $usuCreacion);
        //return ExcelNegocio::create()->generarReporte($opcionId, $criterios, $elemntosFiltrados, $columns, $order, $start);        
        return ExcelNegocio::create()->generarReporte($opcionId, $criterios);
    }

    public function descargarFormato() {

        // seccion de obtencion de variables
        $opcionId = $this->getOpcionId();
        $criterios = $this->getParametro("criterios");

        return ExcelNegocio::create()->generarFormatoMovimientos($opcionId, $criterios);
    }

    public function importarExcelMovimiento() {
        //$this->setTransaction();
        $error_xml = false;
        $documento = $this->getParametro("documento");
        $usuCreacion = $this->getUsuarioId();
        $opcionId = $this->getOpcionId();
        $usuarioId = $this->getUsuarioId();

        $docDecode = Util::base64ToImage($documento);
        $direccion = __DIR__ . '/../../util/formatos/subirMovimientos.xlsx';
        if (file_exists($direccion)) {
            unlink($direccion);
        }
        file_put_contents($direccion, $docDecode);

        if (strlen($documento) < 1)
            throw new WarningException("No se ha seleccionado ningun archivo.");
        else {
            $xml = ImportacionExcel::parseExcelMovimientoToXML("formatos/subirMovimientos.xlsx", $usuCreacion, "movi");
            $result = MovimientoNegocio::create()->importarExcelMovimiento($opcionId, $usuarioId, $xml, $usuCreacion);
            $errores = "";
            if (is_array($result)) {
                foreach ($result as $array) {
                    if (array_key_exists("cause", $array))
                        $errores .= "<li>Fila " . $array["row"] . ": " . $array["cause"] . "</li>";
                }
            }
            if ($errores !== "") {
                $errores = "No fue posible importar una o varias filas: <br><ul>$errores</ul>";

                $this->setMensajeEmergente($errores, '', Configuraciones::MENSAJE_ERROR);

                return ['vout_exito' => '0', 'vout_mensaje' => 'Errores'];
                //throw new WarningException($errores);                
            } else {
                $this->setMensajeEmergente("Importacion finalizada.");
                return ['vout_exito' => '1', 'vout_mensaje' => 'Correcto'];
            }
        }
    }

    //Area de funciones para copiar documento

    public function obtenerConfiguracionBuscadorDocumentoRelacion() {
        $opcionId = $this->getOpcionId();
        $empresaId = $this->getParametro("empresa_id");
        return MovimientoNegocio::create()->obtenerConfiguracionBuscadorDocumentoRelacion($opcionId, $empresaId);
    }

    public function buscarDocumentoRelacionPorCriterio() {

        $criterios = $this->getParametro("criterios");
        $elementosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");

        $opcionId = $this->getOpcionId();

        $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);

        $empresaId = $this->getParametro("empresa_id");
        $configuracionesDocumentoACopiar = MovimientoNegocio::create()->obtenerConfiguracionBuscadorDocumentoRelacion($opcionId, $empresaId);
        $documentoTipos = $configuracionesDocumentoACopiar->documento_tipo;

        if ($criterios["documento_tipo_ids"] == '') {
            $criterios["documento_tipo_ids"] = array();
            foreach ($documentoTipos as $index => $docTipo) {
                $criterios["documento_tipo_ids"][$index] = $docTipo['id'];
            }
        }

        $transferenciaTipo = $movimientoTipo[0]["transferencia_tipo"];
        $respuesta = MovimientoNegocio::create()->buscarDocumentoACopiar($criterios, $elementosFiltrados, $columns, $order, $start, $transferenciaTipo);

        return $this->obtenerRespuestaDataTable($respuesta->data, $respuesta->contador[0]['total'], $respuesta->contador[0]['total']);
    }

    public function obtenerDocumentoRelacion() {

        $opcionId = $this->getOpcionId();
        $documentoTipoOrigenId = $this->getParametro("documento_id_origen");
        $documentoTipoDestinoId = $this->getParametro("documento_id_destino");
        $movimientoId = $this->getParametro("movimiento_id");
        $documentoId = $this->getParametro("documento_id");
        $documentoRelacionados = $this->getParametro("documentos_relacinados");

        $tempDocumentosRelacionados = array();
        foreach ($documentoRelacionados as $index => $item) {
            if ($item['tipo'] == 1) {
                array_push($tempDocumentosRelacionados, $item);
            }
        }
        $documentoRelacionados = $tempDocumentosRelacionados;

        $data = MovimientoNegocio::create()->obtenerDocumentoRelacion($documentoTipoOrigenId, $documentoTipoDestinoId, $movimientoId, $documentoId, $opcionId, $documentoRelacionados);
        return $data;
    }

    public function obtenerNumeroNotaCredito() {

        $documentoTipoId = $this->getParametro("documentoTipoId");
        $documentoRelacionadoTipo = $this->getParametro("documentoRelacionadoTipo");
//        
        $data = MovimientoNegocio::create()->obtenerNumeroNotaCredito($documentoTipoId, $documentoRelacionadoTipo);
        return $data;
    }

    public function obtenerDocumentoRelacionCabecera() {

        $opcionId = $this->getOpcionId();
        $documentoOrigenId = $this->getParametro("documento_id_origen");
        $documentoDestinoId = $this->getParametro("documento_id_destino");
        $movimientoId = $this->getParametro("movimiento_id");
        $documentoId = $this->getParametro("documento_id");
        $documentoRelacionados = $this->getParametro("documentos_relacinados");

        $data = MovimientoNegocio::create()->obtenerDocumentoRelacionCabecera($documentoOrigenId, $documentoDestinoId, $movimientoId, $documentoId, $opcionId, $documentoRelacionados);
        return $data;
    }

    public function obtenerDocumentosRelacionados() {

        $documentoId = $this->getParametro("documento_id");
        $relacionados = MovimientoNegocio::create()->obtenerDocumentosRelacionados($documentoId);
        return $relacionados;
    }

    public function obtenerReporteDocumentosAsignaciones() {
        $documentoId = $this->getParametro("documento_id");

        return MovimientoNegocio::create()->obtenerReporteDocumentosAsignaciones($documentoId);
    }

    public function obtenerDocumentoRelacionDetalle() {

        $opcionId = $this->getOpcionId();
        $documentoRelacionados = $this->getParametro("documentos_relacionados");
        $monedaId = $this->getParametro("monedaId");
        $fechaEmision = $this->getParametro("fechaEmision");
        if (ObjectUtil::isEmpty($fechaEmision)) {
            $fechaEmision = date("d/m/Y");
        }

        $tempDocumentosRelacionados = array();

        foreach ($documentoRelacionados as $index => $item) {
            if ($item['tipo'] == 1) {
                array_push($tempDocumentosRelacionados, $item);
            }
        }

        $documentoRelacionados = $tempDocumentosRelacionados;

        $respuesta = new ObjectUtil();
        $respuesta->detalleDocumento = MovimientoNegocio::create()->obtenerDocumentoRelacionDetalle($movimientoId = null, $documentoId = null, $opcionId, $documentoRelacionados);

        //OBTENER DATA DE UNIDAD DE MEDIDA
        $documentoDetalle = $respuesta->detalleDocumento;
        foreach ($documentoDetalle as $index => $item) {
            $bienId = $item['bien_id'];
            $unidadMedidaId = $item['unidad_medida_id'];
            $precioTipoId = $item['precio_tipo_id'];

            $data = MovimientoNegocio::create()->obtenerUnidadMedida($bienId, $unidadMedidaId, $precioTipoId, $monedaId, $fechaEmision);
            $documentoDetalle[$index]['dataUnidadMedida'] = $data;
        }
        $respuesta->detalleDocumento = $documentoDetalle;
        //FIN OBTENER DATA UNIDAD MEDIDA  

        return $respuesta->detalleDocumento;
    }

    public function obtenerPersonaDireccion() {

        $personaId = $this->getParametro("personaId");
        return PersonaNegocio::create()->obtenerPersonaDireccionXPersonaId($personaId);
    }

    public function enviarCorreoDetalleDocumento() {
        $usuarioId = $this->getUsuarioId();
        $correo = $this->getParametro("correo");
        $documentoId = $this->getParametro("documentoId");
        $comentarioDocumento = $this->getParametro("comentarioDocumento");

        //guardamos comentario
        if (!ObjectUtil::isEmpty($comentarioDocumento)) {
            $respuesta = MovimientoNegocio::create()->editarComentarioDocumento($documentoId, $comentarioDocumento);
        }

        //logica correo:             
        return MovimientoNegocio::create()->enviarCorreoConPrecio($correo, $documentoId, $comentarioDocumento, $usuarioId, 3);

        //Fin logica de correo
    }

    public function obtenerBienesConStockMenorACantidadMinima() {
        $organizadorId = $this->getParametro("organizadorId");
        $personaId = $this->getParametro("personaId");
        $empresaId = $this->getParametro("empresaId");
        $monedaId = $this->getParametro("monedaId");
        $operador = $this->getParametro("operador");

        $data = MovimientoNegocio::create()->obtenerBienesConStockMenorACantidadMinima($personaId, $organizadorId, $empresaId, $monedaId, $operador);
        return $data;
    }

    public function verificarTipoUnidadMedidaParaTramo() {
        $unidadMedidaId = $this->getParametro("unidadMedidaId");

        $data = MovimientoNegocio::create()->verificarTipoUnidadMedidaParaTramo($unidadMedidaId);
        return $data;
    }

    public function registrarTramoBien() {
        $this->setTransaction();

        $unidadMedidaId = $this->getParametro("unidadMedidaId");
        $cantidadTramo = $this->getParametro("cantidadTramo");
        $bienId = $this->getParametro("bienId");
        $usuCreacion = $this->getUsuarioId();

        return MovimientoNegocio::create()->registrarTramoBien($unidadMedidaId, $cantidadTramo, $bienId, $usuCreacion);
    }

    public function obtenerTramoBien() {
        $bienId = $this->getParametro("bienId");

        $data = MovimientoNegocio::create()->obtenerTramoBien($bienId);
        return $data;
    }

    public function obtenerPrecioCompra() {
        // variables
        $bienId = $this->getParametro("bienId");
        $unidadId = $this->getParametro("unidadMedidaId");
        $monedaId = $this->getParametro("monedaId");
        $fechaEmision = $this->getParametro("fechaEmision");

        $precioCompra = BienPrecioNegocio::create()->obtenerPrecioCompraPromedio($bienId, $unidadId, $fechaEmision);

        if ($monedaId == 4) {
            $equivalenciaDolar = TipoCambioNegocio::create()->obtenerTipoCambioXFechaUltima($fechaEmision);
            $precioCompra = $precioCompra / $equivalenciaDolar[0]['equivalencia_venta'];
        }

        $respuesta = new stdClass();
        $respuesta->precioCompra = $precioCompra;

        return $respuesta;
    }

    public function editarComentarioDocumento() {
        // variables
        $comentario = $this->getParametro("comentario");
        $documentoId = $this->getParametro("documentoId");

        $respuesta = MovimientoNegocio::create()->editarComentarioDocumento($documentoId, $comentario);

        return $respuesta;
    }

    public function modificarDetallePrecios() {

        $detalle = $this->getParametro("detalle");
        $operador = $this->getParametro("operador");
        $monedaId = $this->getParametro("monedaId");
        $fechaEmision = $this->getParametro("fechaEmision");

        foreach ($detalle as $ind => $item) {

            $bienId = $item['bienId'];
            $unidadMedidaId = $item['unidadMedidaId'];
            $precioTipoId = $item['precioTipoId'];

            $dataPrecio = BienPrecioNegocio::create()->obtenerBienPrecioXBienIdXUnidadMedidaIdXPrecioTipoIdXMonedaId($bienId, $unidadMedidaId, $precioTipoId, $monedaId);

            if (!ObjectUtil::isEmpty($dataPrecio)) {
                $precioComparar1 = round($dataPrecio[0]["precio"], 2);
                $precioComparar2 = round($dataPrecio[0]["incluye_igv"], 2);

                if ($precioComparar1 == $item['precio'] || $precioComparar2 == $item['precio']) {
                    $precio = $dataPrecio[0]["precio"] * $operador;
                    $detalle[$ind]['precio'] = $precio;
                }
            }

            $precioCompra = BienPrecioNegocio::create()->obtenerPrecioCompraPromedio($bienId, $unidadMedidaId, $fechaEmision);

            if ($monedaId == 4) {
                $equivalenciaDolar = TipoCambioNegocio::create()->obtenerTipoCambioXFechaUltima($fechaEmision);
                $precioCompra = $precioCompra / $equivalenciaDolar[0]['equivalencia_venta'];
            }

            $detalle[$ind]['precioCompra'] = $precioCompra * $operador;
        }

        return $detalle;
    }

    public function buscarCriteriosBusqueda() {
        $busqueda = $this->getParametro("busqueda");
        $opcionId = $this->getOpcionId();

        $dataPersona = PersonaNegocio::create()->buscarPersonaXNombreXDocumento($opcionId, $busqueda);
        $dataDocumentoTipo = DocumentoTipoNegocio::create()->buscarDocumentoTipoXOpcionXDescripcion($opcionId, $busqueda);
        $dataSerieNumero = DocumentoNegocio::create()->buscarDocumentosXOpcionXSerieNumero($opcionId, $busqueda);

        $resultado = new stdClass();
        $resultado->dataPersona = $dataPersona;
        $resultado->dataDocumentoTipo = $dataDocumentoTipo;
        $resultado->dataSerieNumero = $dataSerieNumero;
        return $resultado;
    }

    public function buscarDocumentoRelacion() {
        $empresaId = $this->getParametro("empresa_id");
        $valor = $this->getParametro("busqueda");
        $opcionId = $this->getOpcionId();

        $tipoIds = '(0),(1),(4)';
        $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);
        $movimientoTipoId = $movimientoTipo[0]["id"];
        $documentoTipoArray = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXEmpresaXTipoXMovimientoTipo($movimientoTipoId, $empresaId, $tipoIds);
        $documentoTipoIdArray = [];
        foreach ($documentoTipoArray as $index => $docTipo) {
            $documentoTipoIdArray[] = $docTipo['id'];
        }
        $response = new stdClass();
        $response->dataPersona = PersonaNegocio::create()->buscarPersonasXDocumentoTipoXValor($documentoTipoIdArray, $valor);
        $response->dataDocumentoTipo = DocumentoTipoNegocio::create()->buscarDocumentoTipoXDocumentoTipoXDescripcion($documentoTipoIdArray, $valor);
        $response->dataSerieNumero = DocumentoNegocio::create()->buscarDocumentosXTipoDocumentoXSerieNumero($documentoTipoIdArray, $valor);
        return $response;
    }

    public function guardarEdicionDocumento() {

        $comentario = $this->getParametro("comentario");
        $documentoId = $this->getParametro("documentoId");

        $respuesta = MovimientoNegocio::create()->editarComentarioDocumento($documentoId, $comentario);
        $tipoEdicion = $this->getParametro("tipoEdicion");
        $camposDinamicos = $this->getParametro("camposDinamicos");
        $usuarioId = $this->getUsuarioId();
        if (!ObjectUtil::isEmpty($camposDinamicos)) {
            $this->setTransaction();
            $respuesta = MovimientoNegocio::create()->guardarEdicionDocumento($documentoId, $camposDinamicos, $comentario, null, null, null, null, null, null, $tipoEdicion);
            if ($respuesta[0]['compra_nacional_bienes'] == MovimientoNegocio::create()->dtCompraNacional) {
                MovimientoDuaNegocio::create()->generarPorDocumentoId($documentoId, $usuarioId);
                MovimientoDuaNegocio::create()->actualizarCostoUnitarioDuaXDocumentoId($documentoId, $usuarioId);
            } else {
                MovimientoDuaNegocio::create()->generarPorDocumentoId($documentoId, $usuarioId);
            }
        }

        return $respuesta;
    }

    public function modificarDetallePreciosXMonedaXOpcion() {

        $detalle = $this->getParametro("detalle");
        $operador = $this->getParametro("operador");
        $monedaId = $this->getParametro("monedaId");
        $fechaEmision = $this->getParametro("fechaEmision");
        $opcion = $this->getParametro("opcion");
        $equivalenciaDolar = TipoCambioNegocio::create()->obtenerTipoCambioXFechaUltima($fechaEmision);
        $equivalenciaVenta = $equivalenciaDolar[0]['equivalencia_venta'];

        foreach ($detalle as $ind => $item) {

            $bienId = $item['bienId'];
            $unidadMedidaId = $item['unidadMedidaId'];
            $precioTipoId = $item['precioTipoId'];

            $dataPrecio = BienPrecioNegocio::create()->obtenerBienPrecioXBienIdXUnidadMedidaIdXPrecioTipoIdXMonedaId($bienId, $unidadMedidaId, $precioTipoId, $monedaId);

            if ($opcion == 2) {
                if (!ObjectUtil::isEmpty($dataPrecio)) {
                    $precio = $dataPrecio[0]["precio"] * $operador;
                    $detalle[$ind]['precio'] = $precio;
                } else {
                    $detalle[$ind]['precio'] = 0;
                }
            } else {
                if ($monedaId == 4) {
                    $detalle[$ind]['precio'] = $detalle[$ind]['precio'] / $equivalenciaVenta;
                } else {
                    $detalle[$ind]['precio'] = $detalle[$ind]['precio'] * $equivalenciaVenta;
                }
            }

            $precioCompra = BienPrecioNegocio::create()->obtenerPrecioCompraPromedio($bienId, $unidadMedidaId, $fechaEmision);

            if ($monedaId == 4) {
                $precioCompra = $precioCompra / $equivalenciaVenta;
            }

            $detalle[$ind]['precioCompra'] = $precioCompra * $operador;
        }

        return $detalle;
    }

    public function enviarMovimientoEmailPDF() {
        $correo = $this->getParametro("correo");
        $documentoId = $this->getParametro("documentoId");
        $comentario = $this->getParametro("comentario");
        $usuarioId = $this->getUsuarioId();

        //guardamos comentario
        if (!ObjectUtil::isEmpty($comentario)) {
            $respuesta = MovimientoNegocio::create()->editarComentarioDocumento($documentoId, $comentario);
        }

        return MovimientoNegocio::create()->enviarMovimientoEmailPDF($correo, $documentoId, $comentario, $usuarioId, 7);
    }

    public function eliminarPDF() {
        $url = $this->getParametro("url");
        $url = str_replace("/home/xbkohbsw/01SGI/", "http://", $url);
        unlink($url);
        return 1;
    }

    public function eliminarPDFM() {
        $url = $this->getParametro("url");
        unlink(__DIR__ . $url);
    }

    public function obtenerTipoCambioXFecha() {
        $this->setTransaction();
        $fecha = $this->getParametro("fecha");
//        $fecha = explode("/", $fecha);
//        $fecha = "$fecha[2]-$fecha[1]-$fecha[0]";
        return TipoCambioNegocio::create()->obtenerTipoCambioXFechaUltima($fecha);
    }

    public function enviarMovimientoEmailCorreoMasPDF() {
        $correo = $this->getParametro("correo");
        $documentoId = $this->getParametro("documentoId");
        $comentario = $this->getParametro("comentario");
        $usuarioId = $this->getUsuarioId();

        //guardamos comentario
        if (!ObjectUtil::isEmpty($comentario)) {
            $respuesta = MovimientoNegocio::create()->editarComentarioDocumento($documentoId, $comentario);
        }

        return MovimientoNegocio::create()->enviarMovimientoEmailCorreoMasPDF($correo, $documentoId, $comentario, $usuarioId, 3);
    }

    public function obtenerMovimientoTipoColumnaLista() {
        $opcionId = $this->getOpcionId();
        $data = MovimientoNegocio::create()->obtenerMovimientoTipoColumnaLista($opcionId);
        return $data;
    }

    public function enviarCorreosMovimiento() {
        $usuarioId = $this->getUsuarioId();
        $txtCorreo = $this->getParametro("txtCorreo");
        $correosSeleccionados = $this->getParametro("correosSeleccionados");
        $respuestaCorreo = $this->getParametro("respuestaCorreo");
        $comentario = $this->getParametro("comentario");

        return MovimientoNegocio::create()->enviarCorreosMovimiento($usuarioId, $txtCorreo, $correosSeleccionados, $respuestaCorreo, $comentario);
    }

    public function obtenerEmailsXAccion() {
        $opcionId = $this->getOpcionId();
        $accion = $this->getParametro("accion");
        $documentoId = $this->getParametro("documentoId");

        return MovimientoNegocio::create()->obtenerEmailsXAccion($opcionId, $accion, $documentoId);
    }

    public function enviarCorreoXAccion() {
        $correo = $this->getParametro("correo");
        $comentario = $this->getParametro("comentario");
        $dataRespuestaEmail = $this->getParametro("dataRespuestaEmail");
        $documentoId = $this->getParametro("documentoId");
        $usuarioId = $this->getUsuarioId();

        //guardamos comentario
        if (!ObjectUtil::isEmpty($comentario)) {
            $respuesta = MovimientoNegocio::create()->editarComentarioDocumento($documentoId, $comentario);
        }

        $plantilla = $dataRespuestaEmail['plantilla'];
        $accion = $plantilla[0]['accion_funcion'];
        $plantillaId = $plantilla[0]['email_plantilla_id'];

        return MovimientoNegocio::create()->enviarCorreoXAccion($correo, $comentario, $accion, $documentoId, $plantillaId, $usuarioId);
    }

    //pagos
    public function obtenerDocumentoTipoDatoPago() {
        $documentoTipoId = $this->getParametro("documentoTipoId");
        $usuarioId = $this->getUsuarioId();
        return DocumentoTipoNegocio::create()->obtenerDocumentoTipoDato($documentoTipoId, $usuarioId);
    }

    public function guardarDocumentoAtencionSolicitud() {
        //------------------------
        $this->setTransaction();
        $opcionId = $this->getOpcionId();
        $usuarioId = $this->getUsuarioId();
        $documentoTipoId = $this->getParametro("documentoTipoId");
        $camposDinamicos = $this->getParametro("camposDinamicos");
        $detalle = $this->getParametro("detalle");
        $documentoARelacionar = $this->getParametro("documentoARelacionar");
        $valorCheck = $this->getParametro("valor_check");
        $comentario = $this->getParametro("comentario");
        $checkIgv = $this->getParametro("checkIgv");
        $monedaId = $this->getParametro("monedaId");
        $accionEnvio = $this->getParametro("accionEnvio");
        $tipoPago = $this->getParametro("tipoPago");
        $listaPagoProgramacion = $this->getParametro("listaPagoProgramacion");
        $atencionSolicitudes = $this->getParametro("atencionSolicitudes");
        $camposDinamicos['atencionesRef'] = $atencionSolicitudes;

//        $nuevoArraySolicitudes = array(array(
//                                    $atencionSolicitudes[0].detalleBien[index].bien_id;
//                                ));

        $resDataDocumento = MovimientoNegocio::create()->guardarXAccionEnvio($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $documentoARelacionar, $valorCheck, $comentario, $checkIgv, $monedaId, $accionEnvio, $tipoPago, $listaPagoProgramacion, $bandAtencion = true);

        $documentoId = $resDataDocumento->documentoId;
        //----------------------

        return $resDataDocumento;
    }

    public function reenviarComprobante() {
        $documentoId = $this->getParametro("id");
        $estado = $this->getParametro("estado");
        $documentoTipoId = $this->getParametro("documento_tipo_id");

        //GENERAR DOCUMENTO ELECTRONICO - SUNAT
        $dataEmpresa = EmpresaNegocio::create()->obtenerEmpresaXDocumentoId($documentoId);
        if ($dataEmpresa[0]['efactura'] == 1) {
            $documentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXId($documentoTipoId);
            $resEfact = MovimientoNegocio::create()->generarDocumentoElectronico($documentoId, $documentoTipo[0]['identificador_negocio'], $estado);
            return $resEfact;
        }
    }

    public function guardarDocumentoPago() {
        //documento operacion
        //------------------------
        $this->setTransaction();
        $opcionId = $this->getOpcionId();
        $usuarioId = $this->getUsuarioId();
        $documentoTipoId = $this->getParametro("documentoTipoId");
        $camposDinamicos = $this->getParametro("camposDinamicos");
        $detalle = $this->getParametro("detalle");
        $documentoARelacionar = $this->getParametro("documentoARelacionar");
        $valorCheck = $this->getParametro("valor_check");
        $comentario = $this->getParametro("comentario");
        $checkIgv = $this->getParametro("checkIgv");
        $monedaId = $this->getParametro("monedaId");
        $accionEnvio = $this->getParametro("accionEnvio");
        $tipoPago = $this->getParametro("tipoPago");
        $listaPagoProgramacion = $this->getParametro("listaPagoProgramacion");
        $periodoId = $this->getParametro("periodoId");

        $resDataDocumento = MovimientoNegocio::create()->guardarXAccionEnvio($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $documentoARelacionar, $valorCheck, $comentario, $checkIgv, $monedaId, $accionEnvio, $tipoPago, $listaPagoProgramacion, null, $periodoId);

        $documentoId = $resDataDocumento->documentoId;
        //----------------------
        //documento pago
        $documentoTipoIdPago = $this->getParametro("documentoTipoIdPago");
        $camposDinamicosPago = $this->getParametro("camposDinamicosPago");

        $documentoPagoId = null;
        if ($documentoTipoIdPago != 0) {
            $documentoPagoId = PagoNegocio::create()->guardar($opcionId, $usuarioId, $documentoTipoIdPago, $camposDinamicosPago, $monedaId, $periodoId);
        }
        //registrar pago
        //GENERAR DOCUMENTO ELECTRONICO - SUNAT
        $dataEmpresa = EmpresaNegocio::create()->obtenerEmpresaXDocumentoId($documentoId);
        if ($dataEmpresa[0]['efactura'] == 1) {
            $documentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXId($documentoTipoId);
            $resEfact = MovimientoNegocio::create()->generarDocumentoElectronico($documentoId, $documentoTipo[0]['identificador_negocio']);
            $resDataDocumento->resEfact = $resEfact;
        }
        //----------------------        

        $montoAPagar = $this->getParametro("montoAPagar"); // efectivo a pagar
        $tipoCambio = $this->getParametro("tipoCambio");
        $tipoCambio = strlen($tipoCambio) == 0 ? null : $tipoCambio;
        $cliente = $this->getParametro("cliente"); //
        $fecha = $this->getParametro("fecha");
        $retencion = 1;
        $monedaPago = $monedaId;
        $empresaId = $this->getParametro("empresaId");
        $actividadEfectivo = $this->getParametro("actividadEfectivo");

        $totalDocumento = $this->getParametro("totalDocumento");
        $totalPago = $this->getParametro("totalPago");
        $dolares = 0;
        if ($monedaPago == 4) {
            $dolares = 1;
        }
        $documentoAPagar = array(array(documentoId => $documentoId,
                tipoDocumento => '',
                numero => '',
                serie => '',
                pendiente => (float) $totalDocumento,
                total => (float) $totalDocumento,
                dolares => $dolares
            )
        );

        if (ObjectUtil::isEmpty($documentoPagoId)) {
            $documentoPagoConDocumento = null;
        } else {
            $documentoPagoConDocumento = array(array(
                    documentoId => $documentoPagoId,
                    tipoDocumento => '',
                    tipoDocumentoId => '',
                    numero => '',
                    serie => '',
                    pendiente => (float) $totalPago,
                    total => (float) $totalPago,
                    monto => (float) $totalPago,
                    dolares => $dolares
                )
            );
        }

        $pago = PagoNegocio::create()->registrarPago($cliente, $fecha, $documentoAPagar, $documentoPagoConDocumento, $usuarioId, $montoAPagar, $tipoCambio, $monedaPago, $retencion, $empresaId, $actividadEfectivo, 1);
        //return $pago;
        //fin registrar pago

        return $resDataDocumento;
    }

    public function getUserEmailByUserId() {
        $usuarioId = $this->getUsuarioId();
        return MovimientoNegocio::create()->getUserEmailByUserId($usuarioId);
    }

    public function generarBienUnicoXDocumentoId() {
        $this->setTransaction();
        $usuarioId = $this->getUsuarioId();
        $documentoId = $this->getParametro("documentoId");
        return MovimientoNegocio::create()->generarBienUnicoXDocumentoId($documentoId, $usuarioId);
    }

    public function anularBienUnicoXDocumentoId() {
        $this->setTransaction();
        $documentoId = $this->getParametro("documentoId");
        return MovimientoNegocio::create()->anularBienUnicoXDocumentoId($documentoId);
    }

    public function obtenerBienUnicoConfiguracionInicial() {
        $documentoId = $this->getParametro("documentoId");
        return MovimientoNegocio::create()->obtenerBienUnicoConfiguracionInicial($documentoId);
    }

    public function guardarBienUnicoDetalle() {
        $this->setTransaction();

        $usuarioId = $this->getUsuarioId();
        $opcionId = $this->getOpcionId();
        $listaBienUnicoDetalle = $this->getParametro("listaBienUnicoDetalle");
        $listaBienUnicoDetalleEliminado = $this->getParametro("listaBienUnicoDetalleEliminado");
        $estadoQR = $this->getParametro("estadoQR");
        return MovimientoNegocio::create()->guardarBienUnicoDetalle($listaBienUnicoDetalle, $listaBienUnicoDetalleEliminado, $usuarioId, $opcionId, $estadoQR);
    }

    public function obtenerProductos() {
        $opcionId = $this->getOpcionId();
        $empresaId = $this->getParametro("empresaId");
        $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);
        $movimientoTipoId = $movimientoTipo[0]["id"];

        return BienNegocio::create()->obtenerActivosXMovimientoTipoId($empresaId, $movimientoTipoId);
    }

    public function relacionarDocumento() {
        $usuarioId = $this->getUsuarioId();
        $documentoIdOrigen = $this->getParametro("documentoIdOrigen");
        $documentoIdARelacionar = $this->getParametro("documentoIdARelacionar");
        $this->setTransaction();
        return MovimientoNegocio::create()->relacionarDocumento($documentoIdOrigen, $documentoIdARelacionar, $usuarioId);
    }

    public function obtenerStockParaProductosDeCopia() {
        $organizadorDefectoId = $this->getParametro("organizadorDefectoId");
        $detalle = $this->getParametro("detalle");
        $organizadorDestinoId = $this->getParametro("organizadorDestinoId");

        $data = MovimientoNegocio::create()->obtenerStockParaProductosDeCopia($organizadorDefectoId, $detalle, $organizadorDestinoId);
        return $data;
    }

    //EDICION
    public function validarDocumentoEdicion() {
        $documentoId = $this->getParametro("documentoId");
        return MovimientoNegocio::create()->validarDocumentoEdicion($documentoId);
    }

    public function obtenerDocumentoRelacionEdicion() {
        $opcionId = $this->getOpcionId();
        $documentoTipoOrigenId = $this->getParametro("documento_id_origen");
        $documentoTipoDestinoId = $this->getParametro("documento_id_destino");
        $movimientoId = $this->getParametro("movimiento_id");
        $documentoId = $this->getParametro("documento_id");
        $documentoRelacionados = $this->getParametro("documentos_relacinados");
        $tempDocumentosRelacionados = array();

        foreach ($documentoRelacionados as $index => $item) {
            if ($item['tipo'] == 1) {
                array_push($tempDocumentosRelacionados, $item);
            }
        }

        $documentoRelacionados = $tempDocumentosRelacionados;

        $data = MovimientoNegocio::create()->obtenerDocumentoRelacionEdicion($documentoTipoOrigenId, $documentoTipoDestinoId, $movimientoId, $documentoId, $opcionId, $documentoRelacionados);
        return $data;
    }

    public function enviarEdicion() {
        $this->setTransaction();
        $documentoId = $this->getParametro("documentoId");
        $contOperacionTipoId = $this->getParametro("contOperacionTipoId");
        $opcionId = $this->getOpcionId();
        $usuarioId = $this->getUsuarioId();
        $documentoTipoId = $this->getParametro("documentoTipoId");
        $camposDinamicos = $this->getParametro("camposDinamicos");
        $detalle = $this->getParametro("detalle");
        $listaDetalleEliminar = $this->getParametro("listaDetalleEliminar");
        $documentoARelacionar = $this->getParametro("documentoARelacionar");
        $valorCheck = $this->getParametro("valor_check");
        $comentario = $this->getParametro("comentario");
        $checkIgv = $this->getParametro("checkIgv");
        $afectoAImpuesto = $this->getParametro("afectoAImpuesto");
        $monedaId = $this->getParametro("monedaId");
        $empresaId = $this->getParametro("empresaId");
        $accionEnvio = $this->getParametro("accionEnvio");
        //gclv: campo de tipo de pago (contado, credito)
        $tipoPago = $this->getParametro("tipoPago");
        $listaPagoProgramacion = $this->getParametro("listaPagoProgramacion");
        $anticiposAAplicar = $this->getParametro("anticiposAAplicar");
        $percepcion = $this->getParametro("percepcion");
        $periodoId = $this->getParametro("periodoId");
        $detalleDistribucion = $this->getParametro("detalleDistribucion");
        $distribucionObligatoria = $this->getParametro("distribucionObligatoria");
        return MovimientoNegocio::create()->guardarXAccionEnvioEdicion($documentoId, $opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $listaDetalleEliminar, $documentoARelacionar, $valorCheck, $comentario, $checkIgv, $monedaId, $accionEnvio, $tipoPago, $listaPagoProgramacion, $anticiposAAplicar, $periodoId, $percepcion, $detalleDistribucion, $contOperacionTipoId, $distribucionObligatoria, $afectoAImpuesto);
    }

    public function validarMovimientoBienEdicionEliminar() {
        $documentoId = $this->getParametro("documentoId");
        $item = $this->getParametro("item");

        return MovimientoNegocio::create()->validarMovimientoBienEdicionEliminar($documentoId, $item);
    }

    public function obtenerDireccionOrganizador() {
        $organizadorId = $this->getParametro("organizadorId");
        return MovimientoNegocio::create()->obtenerDireccionOrganizador($organizadorId);
    }

    public function consultarEstadoSunat() {
        $documentoId = $this->getParametro("documentoId");
        return MovimientoNegocio::create()->consultarTicket($documentoId);
    }

    public function cambiarEstadoDespacho() {
        $despacho_id = $this->getParametro("despacho_id");
        return MovimientoNegocio::create()->CambiarEstadoDespacho($despacho_id);
    }

    public function obtenerConfiguracionesInicialesGuiaRT() {
        $usuarioId = $this->getUsuarioId();
        return MovimientoNegocio::create()->obtenerConfiguracionesInicialesGuiaRT($usuarioId);
    }

    public function buscarGuiaRT() {
        $bus_id = $this->getParametro("bus_id");
        $fecha_salida = $this->getParametro("fecha_salida");
        $conductor_id = $this->getParametro("conductor_id");
        $origin_id = $this->getParametro("origin_id");
        $destino_id = $this->getParametro("destino_id");
        $nro_guia = $this->getParametro("nro_guia");
        return MovimientoNegocio::create()->obtenertablaGuia($bus_id, $fecha_salida, $conductor_id, $origin_id, $destino_id, $nro_guia);
    }

    public function visualizarDocumentoGuiaRT() {
        $guia_id = $this->getParametro("documentoId");
        return MovimientoNegocio::create()->visualizarDocumentoGuiaRT($guia_id);
    }

    public function anularDocumentoGuiaRT() {
        $guia_id = $this->getParametro("id");
        return MovimientoNegocio::create()->anularDocumentoGuiaRT($guia_id);
    }

    public function imprimirDocumentoGuiaRT() {
        $documentoId = $this->getParametro("id");

        //Cambios producción Cristopher
        $despacho = $this->getParametro("despacho");
        return MovimientoNegocio::create()->imprimirDocumentoGuiaRT($documentoId, $despacho);
    }

    public function obtenerSelectPersonas() {
        $stringBusqueda = $this->getParametro("querySelect");
        $personaId = $this->getParametro("personaId");
        return PersonaNegocio::create()->obtenerPersonaActivoXStringBusqueda($stringBusqueda, $personaId);
    }

    public function obtenerDocumentosDespacho() {
        // seccion de obtencion de variables
        $usuarioId = $this->getUsuarioId();
        $opcionId = $this->getOpcionId();

        $bus_id = $this->getParametro("bus_id");
        $fecha_salida = $this->getParametro("fecha_salida");
        $conductor_id = $this->getParametro("conductor_id");
        $nro_manifiesto = $this->getParametro("nro_manifiesto");
        $origin_id = $this->getParametro("origin_id");
        $destino_id = $this->getParametro("destino_id");
        $estado_id = $this->getParametro("estado_id");
        $copiloto_id = $this->getParametro("copiloto_id");
        $nro_despacho = $this->getParametro("nro_despacho");
         $bandera_devolucion = $this->getParametro("bandera_devolucion");
//        return MovimientoNegocio::create()->obtenertablaDespacho($bus_id, $fecha_salida, $conductor_id, $copiloto_id, $nro_manifiesto, $origin_id, $destino_id, $estado_id, $nro_despacho);


        $elemntosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");

        // seccion de consumir negocio
        $data = MovimientoNegocio::create()->obtenerDocumentosDespachoXCriterios($bus_id, $fecha_salida, $conductor_id, $copiloto_id, $nro_manifiesto, $origin_id, $destino_id, $estado_id, $nro_despacho,$bandera_devolucion, $elemntosFiltrados, $columns, $order, $start);
        $elementosTotales = (count($data) > 0 ? $data[0]["total_filas"] : 0);

        // FIN CALCULO ESTADO NEGOCIO
        $responseAcciones = MovimientoNegocio::create()->obtenerMovimientoTipoAcciones($opcionId, "DM");
        for ($i = 0; $i < count($data); $i++) {
            $stringAcciones = '';
            $estadoId = $data[$i]['documento_estado_id'];
            for ($j = 0; $j < count($responseAcciones); $j++) {
                if (!($estadoId == 1 || $estadoId == 14) && $responseAcciones[$j]['id'] == 29) { //GENERAR
                    $stringAcciones .= '';
                } else {
                    $documentoId = $data[$i]['documento_id'];
                    $datoPivot = $data[$i]['movimiento_id'];
                    $stringAcciones .= '<a  onclick="' . $responseAcciones[$j]['funcion'] . "(" . $documentoId . "," . $datoPivot . ')" title="' . $responseAcciones[$j]['descripcion'] . '"><b><i class="' . $responseAcciones[$j]['icono'] . '" style="color:' . $responseAcciones[$j]['color'] . '"></i></b></a>&nbsp;&nbsp;&nbsp;';
                }
            }

            $data[$i]['acciones'] = $stringAcciones;
        }

        return $this->obtenerRespuestaDataTable($data, $elementosTotales, $elementosTotales);
    }

    public function obtenerDocumentosManifiestoReparto() {
        // seccion de obtencion de variables
        $usuarioId = $this->getUsuarioId();
        $opcionId = $this->getOpcionId();

        $bus_id = $this->getParametro("bus_id");
        $fecha_salida = $this->getParametro("fecha_salida");
        $conductor_id = $this->getParametro("conductor_id");
        $nro_manifiesto = $this->getParametro("nro_manifiesto");
        $origin_id = $this->getParametro("origin_id");
        $estado_id = $this->getParametro("estado_id");

        $elemntosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");

        // seccion de consumir negocio
        $data = MovimientoNegocio::create()->obtenerDocumentosManifiestoRepartoXCriterios($bus_id, $fecha_salida, $conductor_id, $nro_manifiesto, $origin_id, $estado_id, $elemntosFiltrados, $columns, $order, $start);
        $elementosTotales = (count($data) > 0 ? $data[0]["total_filas"] : 0);

        // FIN CALCULO ESTADO NEGOCIO
        $responseAcciones = MovimientoNegocio::create()->obtenerMovimientoTipoAcciones($opcionId);
        for ($i = 0; $i < count($data); $i++) {
            $stringAcciones = '';
            $estadoId = $data[$i]['documento_estado_id'];
            for ($j = 0; $j < count($responseAcciones); $j++) {
                if (!($estadoId == 1 || $estadoId == 14) && $responseAcciones[$j]['id'] == 29) { //GENERAR
                    $stringAcciones .= '';
                } else {
                    $documentoId = $data[$i]['documento_id'];
                    $datoPivot = $data[$i]['movimiento_id'];
                    $stringAcciones .= '<a  onclick="' . $responseAcciones[$j]['funcion'] . "(" . $documentoId . "," . $datoPivot . ')" title="' . $responseAcciones[$j]['descripcion'] . '"><b><i class="' . $responseAcciones[$j]['icono'] . '" style="color:' . $responseAcciones[$j]['color'] . '"></i></b></a>&nbsp;&nbsp;&nbsp;';
                }
            }

            $data[$i]['acciones'] = $stringAcciones;
        }

        return $this->obtenerRespuestaDataTable($data, $elementosTotales, $elementosTotales);
    }

    public function obtenerCorrelativoGuia() {
        $usuarioId = $this->getUsuarioId();
        $agenciaId = $this->getParametro("agencia_id");
        $identificadorNegocio = $this->getParametro("identificador_negocio");
        return PedidoNegocio::create()->obtenerCorrelativoGuia($usuarioId, $agenciaId, $identificadorNegocio);
    }

    public function obtenerCorrelativoGuiaBoleta() {
        $agenciaId = $this->getParametro("agencia_id");
        $identificadorNegocio = $this->getParametro("identificador_negocio");
        return PedidoNegocio::create()->obtenerCorrelativoGuia(NULL, $agenciaId, $identificadorNegocio);
    }
    
        public function AbrirDespachoT() {
        $documentoId = $this->getParametro("despachoId");
        $usuarioId = $this->getUsuarioId();
        $opcionId = $this->getOpcionId();
        $this->setTransaction();
        return MovimientoNegocio::create()->AbrirDespachoT($documentoId,$usuarioId);
    }

}
