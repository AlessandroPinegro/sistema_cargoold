<?php

require_once __DIR__ . '/../../modelo/almacen/PerfilAgenciaCaja.php';
require_once __DIR__ . '/../../modelo/almacen/Movimiento.php';
require_once __DIR__ . '/../../modelo/almacen/MovimientoBien.php';
require_once __DIR__ . '/../../modelo/almacen/BienUnico.php';
require_once __DIR__ . '/../../modelo/almacen/Agencia.php';
require_once __DIR__ . '/../../modelo/almacen/Documento.php';
require_once __DIR__ . '/../../modelo/almacen/Penalidad.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/../../modeloNegocio/contabilidad/CentroCostoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/contabilidad/PlanContableNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/contabilidad/ContDistribucionContableNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/contabilidad/DetraccionNegocio.php';
require_once __DIR__ . '/../commons/ConstantesNegocio.php';
require_once __DIR__ . '/PedidoNegocio.php';
require_once __DIR__ . '/DespachoNegocio.php';
require_once __DIR__ . '/MovimientoTipoNegocio.php';
require_once __DIR__ . '/DocumentoTipoNegocio.php';
require_once __DIR__ . '/OrganizadorNegocio.php';
require_once __DIR__ . '/UnidadNegocio.php';
require_once __DIR__ . '/BienNegocio.php';
require_once __DIR__ . '/EmailPlantillaNegocio.php';
require_once __DIR__ . '/EmailEnvioNegocio.php';
require_once __DIR__ . '/DocumentoNegocio.php';
require_once __DIR__ . '/ExcelNegocio.php';
require_once __DIR__ . '/PersonaNegocio.php';
require_once __DIR__ . '/DocumentoTipoDatoListaNegocio.php';
require_once __DIR__ . '/DocumentoDatoValorNegocio.php';
require_once __DIR__ . '/BienPrecioNegocio.php';
require_once __DIR__ . '/EmpresaNegocio.php';
require_once __DIR__ . '/MonedaNegocio.php';
require_once __DIR__ . '/PagoNegocio.php';
require_once __DIR__ . '/BienUnicoNegocio.php';
require_once __DIR__ . '/MovimientoDuaNegocio.php';
require_once __DIR__ . '/PeriodoNegocio.php';
require_once __DIR__ . '/ACCajaNegocio.php';
require_once __DIR__ . '/VehiculoNegocio.php';
require_once __DIR__ . '/ProgramacionAtencionNegocio.php';
require_once __DIR__ . '/../../util/NumeroALetra/EnLetras.php';
require_once __DIR__ . '/../../util/Configuraciones.php';
require_once __DIR__ . '/../../modelo/almacen/Actividad.php';
require_once __DIR__ . '/../../modelo/almacen/Pago.php';
require_once __DIR__ . '/../../modelo/contabilidad/Tabla.php';
require_once __DIR__ . '/../../util/phpqrcode/qrlib.php';
require_once __DIR__ . '/../../util/phpqrcode/qrlib.php';

$objPHPExcel = null;
$objWorkSheet = null;
$i = 0;
$j = 0;
$h = null;
$documentoTipoIdAnterior = null;

class MovimientoNegocio extends ModeloNegocioBase
{

    var $dtDuaId = 256;
    var $dtCompraNacional = "406";
    var $dataRetencion = array("id" => 1, "descripcion" => "001 | Retención", "monto_minimo" => 700.00, "porcentaje" => 3.0);
    var $cuentaBancoNacion = "";

    /**
     *
     * @return MovimientoNegocio
     */
    static function create()
    {
        return parent::create();
    }

    private $docInafectas;

    public function obtenerConfiguracionInicial($opcionId, $empresaId, $usuarioId, $documentoId = null, $movimientoTipoId = null)
    {
        // obtenemos el id del movimiento tipo que utiliza la opcion
        if (!ObjectUtil::isEmpty($movimientoTipoId)) {
            $movimientoTipo = MovimientoTipoNegocio::create()->getMovimientoTipo($movimientoTipoId);
        } else {
            $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);
            $movimientoTipoId = $movimientoTipo[0]["id"];
        }

        if (ObjectUtil::isEmpty($movimientoTipo)) {
            throw new WarningException("No se encontró el movimiento asociado a esta opción");
        }

        $respuesta = new stdClass();
        //        $respuesta->movimiento_tipo = MovimientoTipoNegocio::create()->getMovimientoTipo($movimientoTipoId);
        $respuesta->documento_tipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXMovimientoTipo($movimientoTipoId);
        if (ObjectUtil::isEmpty($respuesta->documento_tipo)) {
            throw new WarningException("El movimiento no cuenta con tipos de documentos asociados");
        }
        //identificador_negocio	  
        $documentoTipoDefectoId = $respuesta->documento_tipo[0]["id"];
        if (!ObjectUtil::isEmpty($movimientoTipo[0]['documento_tipo_defecto_id'])) {
            $documentoTipoDefectoId = $movimientoTipo[0]['documento_tipo_defecto_id'];
        }

        $respuesta->dataDocumento = null;
        if (!ObjectUtil::isEmpty($documentoId)) {
            $respuesta->dataDocumento = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);
            $documentoTipoDefectoId = $respuesta->dataDocumento[0]['documento_tipo_id'];
        }

        $respuesta->documento_tipo_conf = DocumentoTipoNegocio::create()->obtenerDocumentoTipoDato($documentoTipoDefectoId, $usuarioId);

        $respuesta->bien = BienNegocio::create()->obtenerActivosXMovimientoTipoId($empresaId, $movimientoTipoId);

        $respuesta->organizador = OrganizadorNegocio::create()->obtenerXMovimientoTipo($movimientoTipoId);

        $respuesta->movimientoTipo = $movimientoTipo;

        $respuesta->precioTipo = BienPrecioNegocio::create()->obtenerPrecioTipoXMovimientoTipo($movimientoTipoId);

        $dataEmpresa = EmpresaNegocio::create()->obtenerEmpresaXId($empresaId);
        $respuesta->dataEmpresa = $dataEmpresa;
        $respuesta->moneda = MonedaNegocio::create()->obtenerComboMoneda();

        $respuesta->accionesEnvio = Movimiento::create()->obtenerMovimientoTipoAcciones($movimientoTipoId, 2);
        $respuesta->accionEnvioPredeterminado = Movimiento::create()->obtenerMovimientoTipoAccionEnvioPredeterminado($movimientoTipoId);

        //obtner datos para las columnas del detalle
        $respuesta->movimientoTipoColumna = MovimientoTipoNegocio::create()->obtenerMovimientoTipoColumna($movimientoTipoId);

        $respuesta->conductor = Agencia::create()->getDataConductores();
        $respuesta->vehiculo = Vehiculo::create()->getDataVehiculo();
        $respuesta->agenciaOrigen = PerfilAgenciaCajaNegocio::create()->obtenerPerfilAgenciaXUsuarioId($usuarioId);
        $respuesta->agenciaDestino = AgenciaNegocio::create()->getDataAgencia();

        $respuesta->periodo = null;
        if (!ObjectUtil::isEmpty($documentoId)) {
            $respuesta->periodo = PeriodoNegocio::create()->obtenerPeriodoXEmpresa($empresaId);
        } else {
            $respuesta->periodo = PeriodoNegocio::create()->obtenerPeriodoAbiertoXEmpresa($empresaId);
        }

        if (ObjectUtil::isEmpty($respuesta->periodo)) {
            throw new WarningException("No existe periodo abierto.");
        }

        return $respuesta;
    }

    public function ObtenerRelacionSerieCorrelativo($opcionId, $empresaId, $usuarioId, $documentoId = null, $movimientoTipoId = null, $comprobanteId = null)
    {
        // obtenemos el id del movimiento tipo que utiliza la opcion
        if (!ObjectUtil::isEmpty($movimientoTipoId)) {
            $movimientoTipo = MovimientoTipoNegocio::create()->getMovimientoTipo($movimientoTipoId);
        } else {
            $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);
            $movimientoTipoId = $movimientoTipo[0]["id"];
        }

        if (ObjectUtil::isEmpty($movimientoTipo)) {
            throw new WarningException("No se encontró el movimiento asociado a esta opción");
        }

        $respuesta = new stdClass();
        //        $respuesta->movimiento_tipo = MovimientoTipoNegocio::create()->getMovimientoTipo($movimientoTipoId);
        $respuesta->documento_tipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXMovimientoTipo($movimientoTipoId);
        if (ObjectUtil::isEmpty($respuesta->documento_tipo)) {
            throw new WarningException("El movimiento no cuenta con tipos de documentos asociados");
        }
        //identificador_negocio	  
        $documentoTipoDefectoId = $respuesta->documento_tipo[0]["id"];
        if (!ObjectUtil::isEmpty($movimientoTipo[0]['documento_tipo_defecto_id'])) {
            $documentoTipoDefectoId = $movimientoTipo[0]['documento_tipo_defecto_id'];
        }

        $respuesta->dataDocumento = null;
        if (!ObjectUtil::isEmpty($documentoId)) {
            $respuesta->dataDocumento = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);
            $documentoTipoDefectoId = $respuesta->dataDocumento[0]['documento_tipo_id'];
        }

        $respuesta->documento_tipo_conf = DocumentoTipoNegocio::create()->obtenerDocumentoTipoDatoConf($documentoTipoDefectoId, $usuarioId, $comprobanteId);

        $respuesta->bien = BienNegocio::create()->obtenerActivosXMovimientoTipoId($empresaId, $movimientoTipoId);

        $respuesta->organizador = OrganizadorNegocio::create()->obtenerXMovimientoTipo($movimientoTipoId);

        $respuesta->movimientoTipo = $movimientoTipo;

        $respuesta->precioTipo = BienPrecioNegocio::create()->obtenerPrecioTipoXMovimientoTipo($movimientoTipoId);

        $dataEmpresa = EmpresaNegocio::create()->obtenerEmpresaXId($empresaId);
        $respuesta->dataEmpresa = $dataEmpresa;
        $respuesta->moneda = MonedaNegocio::create()->obtenerComboMoneda();

        $respuesta->accionesEnvio = Movimiento::create()->obtenerMovimientoTipoAcciones($movimientoTipoId, 2);
        $respuesta->accionEnvioPredeterminado = Movimiento::create()->obtenerMovimientoTipoAccionEnvioPredeterminado($movimientoTipoId);

        //obtner datos para las columnas del detalle
        $respuesta->movimientoTipoColumna = MovimientoTipoNegocio::create()->obtenerMovimientoTipoColumna($movimientoTipoId);

        $respuesta->conductor = Agencia::create()->getDataConductores();
        $respuesta->vehiculo = Vehiculo::create()->getDataVehiculo();
        $respuesta->agenciaOrigen = PerfilAgenciaCajaNegocio::create()->obtenerPerfilAgenciaXUsuarioId($usuarioId);
        $respuesta->agenciaDestino = AgenciaNegocio::create()->getDataAgencia();

        $respuesta->periodo = null;
        if (!ObjectUtil::isEmpty($documentoId)) {
            $respuesta->periodo = PeriodoNegocio::create()->obtenerPeriodoXEmpresa($empresaId);
        } else {
            $respuesta->periodo = PeriodoNegocio::create()->obtenerPeriodoAbiertoXEmpresa($empresaId);
        }

        if (ObjectUtil::isEmpty($respuesta->periodo)) {
            throw new WarningException("No existe periodo abierto.");
        }

        return $respuesta;
    }

    public function obtenerBienPrecioXBienId($bienId, $unidadMedidaId, $monedaId, $opcionId)
    {
        $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);
        $movimientoTipoId = $movimientoTipo[0]["id"];

        $data = BienPrecioNegocio::create()->obtenerBienPrecioXBienIdXMovimientoTipoId($bienId, $unidadMedidaId, $monedaId, $movimientoTipoId);
        return $data;
    }

    public function obtenerDocumentoTipo($opcionId)
    {
        // obtenemos el id del movimiento tipo que utiliza la opcion
        $contador = 0;
        $movimientoTipoId = $this->obtenerIdXOpcion($opcionId);
        $respuesta = new ObjectUtil();
        //        $respuesta->movimiento_tipo = MovimientoTipoNegocio::create()->getMovimientoTipo($movimientoTipoId);
        $respuesta->documento_tipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXMovimientoTipo($movimientoTipoId);
        if (!ObjectUtil::isEmpty($respuesta->documento_tipo)) {
            $respuesta->documento_tipo_dato = DocumentoTipoNegocio::create()->obtenerDocumentoTipoDatoXMovimientoTipo($movimientoTipoId);
        }

        if (!ObjectUtil::isEmpty($respuesta->documento_tipo_dato)) {

            $tamanio = count($respuesta->documento_tipo_dato);
            for ($i = 0; $i < $tamanio; $i++) {
                switch ((int) $respuesta->documento_tipo_dato[$i]['tipo']) {
                    case 5:
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "Persona";
                        break;
                    case 6:
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "CÃ³digo";
                        break;
                    case 7:
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "Serie";
                        break;
                    case 8:
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "NÃºmero";
                        break;
                    case 9:
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "Fecha de emisiÃ³n";
                        break;
                    case 10:
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "Fecha de vencimiento";
                        break;
                    case 11:
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "Fecha de tentativa";
                        break;
                    case 12:
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "DescripciÃ³n";
                        break;
                    case 13:
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "Comentario";
                        break;
                    case 14:
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "Importe";
                        break;
                    case 17:
                        $respuesta->documento_tipo_dato[$i]['descripcion'] = "Organizador Destino";
                        break;
                }
            }

            //            foreach ($respuesta->documento_tipo_dato as $documento) {
            //                $documento['descripcion'] = "hola";
            //            }


            foreach ($respuesta->documento_tipo_dato as $documento) {
                if ($documento['tipo'] == 4) {
                    $respuesta->documento_tipo_dato_lista[$contador]['id'] = $documento['id'];
                    $respuesta->documento_tipo_dato_lista[$contador]['data'] = DocumentoTipoDatoListaNegocio::create()->obtenerXDocumentoTipoDato($documento['id']);
                    $contador++;
                }
            }
        }

        //        $respuesta->persona_activa = PersonaNegocio::create()->obtenerActivas();
        return $respuesta;
    }

    public function obtenerIdXOpcion($opcionId)
    {
        $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);
        if (ObjectUtil::isEmpty($movimientoTipo)) {
            throw new WarningException("No se encontrÃ³ el movimiento asociado a esta opciÃ³n");
        }
        return $movimientoTipo[0]["id"];
    }

    private function obtenerValorCampoDinamicoPorTipo($camposDinamicos, $tipo)
    {
        foreach ($camposDinamicos as $campo) {
            if ($campo["tipo"] == $tipo) {
                return $campo["valor"];
            }
        }
    }

    public function validarGenerarDocumentoAdicional($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $documentoARelacionar, $valorCheck, $comentario = NULL, $checkIgv = 1, $monedaId = null, $accionEnvio, $tipoPago, $listaPagoProgramacion, $anticiposAAplicar = null, $periodoId = null, $percepcion = null, $origen_destino = null, $importeTotalInafectas = null, $detalleDistribucion = null, $contOperacionTipoId = null, $distribucionObligatoria = null, $afectoAImpuesto = null, $datosExtras = null)
    {

          //Comprobante a relacionar
          
         if($documentoTipoId==61 || $documentoTipoId==269){

          foreach ($documentoARelacionar as $documentoRelacion) {
            if (!ObjectUtil::isEmpty($documentoRelacion['documentoId']) && $documentoRelacion['tipo']==1) {              
            $datosDocumento=Documento::create()->obtenerDocumentoXDocumentoId( $documentoRelacion['documentoId']);               
            $tipoDocumento= $datosDocumento[0]['documento_tipo_id'];    
        }
        }

        // documento 
        foreach ($camposDinamicos as $indexCampos => $valor) {
            if ($valor["tipo"] == 7) {

             $serie=$valor["valor"];
             $primerTermino = substr($serie, 0, 1);
            

            if($tipoDocumento==6 && $primerTermino=='F' ) {
        
           throw new WarningException("La boleta no puede estar asignada a esta serie de nota que le pertenece a una factura");

            } else if($tipoDocumento==7 && $primerTermino=='B'){

            throw new WarningException("La factura no puede estar asignada a esta serie de nota que le pertenece a una boleta");

            }

             $datoNota=Documento::create()->obtenerTipoDocumentoXTipoRelacion( $documentoTipoId,$serie);
             $tipoDocumentoNota=$datoNota[0]['documento_tipo_relacion_id'];
             if(ObjectUtil::isEmpty($tipoDocumentoNota)){
             throw new WarningException("Debe asignar una serie para este tipo de documento");
             }
            }
        }

       // if($tipoDocumento!=$tipoDocumentoNota){
         //   throw new WarningException("Deben tanto comprobante y nota tener el mismo documento tipo");
       // }
       
    }

        //validacion en caso de bienes faltantes       

        $documentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXId($documentoTipoId);

        //SI ES TRANSFERENCIA INTERNA - DT: GUIA INTERNA
        if ($documentoTipo[0]['identificador_negocio'] == 23) {

            //VALIDAR QUE EL MOTIVO SEA  Pendiente de reposicion o Reposicion O PARA VALIDAR COPIA OBLIGATORIA
            $validarCopia = false;
            foreach ($camposDinamicos as $item) {
                if ($item['tipo'] == 4 && ($item['valor'] == Configuraciones::DTDL_GUIA_INTERNA_REPOSICION || $item['valor'] == Configuraciones::DTDL_GUIA_INTERNA_PENDIENTE_REPOSICION)) {
                    $validarCopia = true;
                }
            }

            if ($validarCopia && !ObjectUtil::isEmpty($origen_destino)) {
                if (ObjectUtil::isEmpty($documentoARelacionar)) {
                    if ($origen_destino == "O") {
                        throw new WarningException("Debe relacionar una guía interna para poder guardar");
                    } else if ($origen_destino == "D") {
                        throw new WarningException("Debe relacionar una guía de recepción para poder guardar");
                    }
                } else {
                    $copiaAlmVirtual = false;
                    foreach ($documentoARelacionar as $item) {
                        if (!ObjectUtil::isEmpty($item['documentoId'])) {
                            //buscando guia de recepcion en las copias.
                            $dataDocCopia = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($item['documentoId']);

                            if ($origen_destino == "O") {
                                if ($dataDocCopia[0]['identificador_negocio'] == 23) { //Guia interna / Transferencia Interna
                                    $copiaAlmVirtual = true;
                                }
                            } else if ($origen_destino == "D") {
                                if (
                                    $dataDocCopia[0]['identificador_negocio'] == 6 || //Guia de remision BH
                                    $dataDocCopia[0]['identificador_negocio'] == 22
                                ) { //Guia de recepcion
                                    $copiaAlmVirtual = true;
                                }
                            }
                        }
                    }

                    if (!$copiaAlmVirtual) {
                        if ($origen_destino == "O") {
                            throw new WarningException("Debe relacionar una guía interna para poder guardar");
                        } else if ($origen_destino == "D") {
                            throw new WarningException("Debe relacionar una guía de recepción para poder guardar");
                        }
                    }
                }
            }
            //            throw new WarningException("PASO TODO BIEN");
        }

        if ($documentoTipo[0]["generar_documento_adicional"] == 1 && ObjectUtil::isEmpty($documentoARelacionar)) {
            $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);
            $dataDocumentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoGenerarXMovimientoTipoId($movimientoTipo[0]["id"]);

            $dataProveedor = array();
            $dataOrganizador = array();

            $j = 0;
            foreach ($detalle as $indice => $item) {
                $cantidadFaltante = $item["cantidad"];
                //validar que no sea servicio
                $bien = BienNegocio::create()->getBien($item["bienId"]);

                if ($cantidadFaltante > 0 && $bien[0]['bien_tipo_id'] != -1) {

                    $dataOrganizador[$j] = array();

                    $dataP = BienNegocio::create()->obtenerBienPersonaXBienId($item["bienId"]);
                    array_push($dataProveedor, $dataP);

                    $dataStockBien = BienNegocio::create()->obtenerStockPorBien($item["bienId"], null);

                    foreach ($dataStockBien as $ind => $itemDataStock) {
                        if ($cantidadFaltante <= $itemDataStock["stock"] && $item["unidadMedidaId"] == $itemDataStock["unidad_medida_id"]) {

                            array_push($dataOrganizador[$j], array('organizadorId' => $itemDataStock["organizador_id"], 'descripcion' => $itemDataStock["organizador_descripcion"]));
                        }
                    }
                    $j++;
                }
            }

            $respuesta->generarDocumentoAdicional = 1;
            $respuesta->dataDocumentoTipo = $dataDocumentoTipo;
            $respuesta->dataOrganizador = $dataOrganizador;
            $respuesta->dataProveedor = $dataProveedor;
            $respuesta->dataDetalle = $detalle;

            return $respuesta;
        }
        //fin validacion  
        //validar si tipo de pago es contado
        //obtenemos valor del total
        $total = 0;
        foreach ($camposDinamicos as $item) {
            if ($item['tipo'] == 14) {
                $total = $item['valor'] * 1;
            }
        }
        $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);
        $esVentaGratuita = false;
        if (!ObjectUtil::isEmpty($movimientoTipo) && $movimientoTipo[0]['codigo'] == "18") {
            $esVentaGratuita = true;
        }
        if ($tipoPago == '1' && $total != 0 && !$esVentaGratuita) {
            $dataDocumentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXId($documentoTipoId);
            $empresaId = $dataDocumentoTipo[0]['empresa_id'];
            $tipoDocumento = $dataDocumentoTipo[0]['tipo'];

            if ($tipoDocumento == 1 || $tipoDocumento == 3 || $tipoDocumento == 4 || $tipoDocumento == 6) {

                if ($tipoDocumento == 1 || $tipoDocumento == 3) {
                    $tipo = 2;
                    $tipo2 = 3;
                    $tipoCobranzaPago = 1;
                }
                if ($tipoDocumento == 4 || $tipoDocumento == 6) {
                    $tipo = 5;
                    $tipo2 = 6;
                    $tipoCobranzaPago = 2;
                }

                $res->dataDocumentoPago = PagoNegocio::create()->obtenerConfiguracionInicialNuevoDocumento($empresaId, $tipo, $tipo2, $usuarioId);
                $res->actividad = Pago::create()->obtenerActividades($tipoCobranzaPago, $empresaId);
                return $res;
            }
        }

        //fin validacion tipo pago contado.
        // ATENCION DE SOLICITUDES(QUITAR EL FALSE PARA HABILITAR ATENCIONES)
        $bandAtiende = null;
        $habilitarAtencion = false;
        if ($habilitarAtencion) {
            foreach ($documentoARelacionar as $index => $item) {
                if ($item['tipo'] == 1) {

                    $dataMovBien = MovimientoBien::create()->obtenerXIdMovimiento($item['movimientoId']);
                    $documentoARelacionar[$index]['detalleBien'] = $dataMovBien;

                    foreach ($detalle as $indexDet => $itemDet) {
                        foreach ($dataMovBien as $itemMovBien) {
                            if ($itemDet['bienId'] == $itemMovBien['bien_id']) {
                                if (ObjectUtil::isEmpty($detalle[$indexDet]['cantidadSol'])) {
                                    $detalle[$indexDet]['cantidadSol'] = $itemMovBien['cantidad'] * 1;
                                } else {
                                    $detalle[$indexDet]['cantidadSol'] += $itemMovBien['cantidad'] * 1;
                                }
                            }
                        }
                    }

                    //}
                    $bandAtiende = false;
                    $bandExterna = $bandAtiende;
                    foreach ($detalle as $itemDeta) {
                        if ($itemDeta['cantidad'] < $itemDeta['cantidadSol']) {
                            $bandAtiende = true;
                        }
                    }

                    if ($bandAtiende) {
                        $res->dataAtencionSolicitud = $documentoARelacionar;
                        return $res;
                    }
                }
            }
        }
        // FIN ATENCION SOLICITUDES
        // Validación de anticipos
        // En el caso que sea un documento pendiente de pago, validamos si el proveedor tiene algún anticipo por aplicar
        if ($anticiposAAplicar["validacion"] * 1 == 0 && $documentoTipo[0]["tipo"] == 4) {
            // obtenemos el id del proveedor
            $proveedorId = $this->obtenerValorCampoDinamicoPorTipo($camposDinamicos, 5);
            //            if (!ObjectUtil::isEmpty($proveedorId)) {
            //                $anticipos = DocumentoNegocio::create()->obtenerAnticiposPendientesXPersonaId($proveedorId, $monedaId);
            //                if (!ObjectUtil::isEmpty($anticipos)) {
            //                    $respuesta->anticipos = $anticipos;
            //                    //                    $respuesta->actividades = Pago::create()->obtenerActividades(2, $$anticiposAAplicar->empresaId); 
            //                    return $respuesta;
            //                }
            //            }
        }

        $respuesta = $this->guardarXAccionEnvio($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $documentoARelacionar, $valorCheck, $comentario, $checkIgv, $monedaId, $accionEnvio, $tipoPago, $listaPagoProgramacion, $bandAtiende, $periodoId, $percepcion, $contOperacionTipoId, $afectoAImpuesto, $datosExtras);

        if (!ObjectUtil::isEmpty($detalleDistribucion)) {
            $respuestaGuardarDistribucion = ContDistribucionContableNegocio::create()->guardarContDistribucionContable($respuesta->documentoId, $contOperacionTipoId, $detalleDistribucion, $usuarioId);
        }

        if ($distribucionObligatoria == 1) {
            $respuestaValidarDistribucion = ContDistribucionContableNegocio::create()->validarDistribucionContable($respuesta->documentoId, $detalleDistribucion, $contOperacionTipoId);
        }

        $this->guardarAnticipos($respuesta, $anticiposAAplicar, $usuarioId, $camposDinamicos, $monedaId);

        $this->docInafectas = (!ObjectUtil::isEmpty($importeTotalInafectas)) ? $importeTotalInafectas : 0.0;
        if ($this->docInafectas * 1 > 0) {
            $actualizarMontoNoAfecto = DocumentoNegocio::create()->actualizarTipoCambioMontoNoAfectoXDocumentoId($respuesta->documentoId, NULL, $this->docInafectas);
        }

        //GENERAR DOCUMENTO ELECTRONICO - SUNAT
        $dataEmpresa = EmpresaNegocio::create()->obtenerEmpresaXDocumentoId($respuesta->documentoId);
        if ($dataEmpresa[0]['efactura'] == 1) {
            $resEfact = $this->generarDocumentoElectronico($respuesta->documentoId, $documentoTipo[0]['identificador_negocio']);
            $respuesta->resEfact = $resEfact;
        }

        return $respuesta;
    }

    public function generarDocumentoElectronico($documentoId, $identificadorNegocio, $soloPDF = 0, $tipoUso = 1)
    {
        //soloPDF = 1 -> solo generar PDF
        //tipoUso = 1 -> por sistema
        //tipoUso = 2 -> por script

        if (ObjectUtil::isEmpty($soloPDF)) {
            $soloPDF = 0;
        }

        if (ObjectUtil::isEmpty($tipoUso)) {
            $tipoUso = 1;
        }

        $esDocElectronico = 0;
        $respDocElectronico = null;

        switch ($identificadorNegocio * 1) {
            case DocumentoTipoNegocio::IN_FACTURA_VENTA:
                $respDocElectronico = $this->generarFacturaElectronica($documentoId, $soloPDF, $tipoUso);
                $esDocElectronico = 1;
                break;
            case DocumentoTipoNegocio::IN_BOLETA_VENTA:
                $respDocElectronico = $this->generarBoletaElectronica($documentoId, $soloPDF, $tipoUso);
                $esDocElectronico = 1;
                break;
            case DocumentoTipoNegocio::IN_NOTA_CREDITO_VENTA:
                $respDocElectronico = $this->generarNotaCreditoElectronica($documentoId, $soloPDF, $tipoUso);
                $esDocElectronico = 1;
                break;
            case DocumentoTipoNegocio::IN_NOTA_DEBITO_VENTA:
                $respDocElectronico = $this->generarNotaDebitoElectronica($documentoId, $soloPDF, $tipoUso);
                $esDocElectronico = 1;
                break;
        }
        $respuesta = new stdClass();
        $respuesta->esDocElectronico = $esDocElectronico;
        $respuesta->respDocElectronico = $respDocElectronico;

        return $respuesta;
    }

    public function validarResultadoEfactura($resultado)
    {
        $mensaje = "Resultado EFACT: " . $resultado;

        if (ObjectUtil::isEmpty($resultado)) {
            throw new WarningException("Se generó un error al registrar el documento electrónico.");
        } else if (strpos($mensaje, '[Cod: IMA01]') === false) {
            throw new WarningException($mensaje);
        }

        $this->setMensajeEmergente($mensaje);
    }

    public function validarResultadoEfacturaDocumento($resultado, $documentoId, $tipoUso)
    {
        $mensaje = $resultado;
        $urlPDF = '';
        $nombrePDF = '';

        switch (true) {
                //EXCEPCIONES DE LA WS - EFAC
            case strpos($mensaje, '[Cod: IMAEX') !== false:
                $tipoMensaje = DocumentoTipoNegocio::EFACT_ERROR_DESCONOCIDO;
                $mensaje = "Resultado EFACT: " . $resultado;
                //throw new WarningException("Resultado EFACT: " . $resultado);
                break;

                //REGISTRO EN LA WS - PENDIENTE DE ENVIO A SUNAT U OSE
            case strpos($mensaje, '[Cod: IMA00]') !== false:
                $tipoMensaje = DocumentoTipoNegocio::EFACT_PENDIENTE_ENVIO;
                $mensaje = "Resultado EFACT: " . $resultado;
                break;

                //REGISTRO CORRECTO
            case strpos($mensaje, '[Cod: IMA01]') !== false:
                $tipoMensaje = DocumentoTipoNegocio::EFACT_CORRECTO;
                $mensaje = "Resultado EFACT: " . $resultado;
                break;

                //ERROR CONTROLADO QUE GENERA EXCEPCION
            case strpos($mensaje, '[Cod: IMA02]') !== false:
                $tipoMensaje = DocumentoTipoNegocio::EFACT_ERROR_CONTROLADO;
                $mensaje = "Resultado EFACT (ERROR): " . $resultado;
                break;

                //ERROR CONTROLADO QUE GENERA RECHAZO EN SUNAT
            case strpos($mensaje, '[Cod: IMA03]') !== false:
                $tipoMensaje = DocumentoTipoNegocio::EFACT_ERROR_RECHAZADO;
                $mensaje = "Resultado EFACT (ERROR): " . $resultado;
                //CAMBIAR ESTADO ANULADO : 
                $respuestaAnularAsiento = ContVoucherNegocio::create()->anularContVocuherRelacionXIdentificadorIdXIdentificadorNegocio($documentoId, ContVoucherNegocio::IDENTIFICADOR_REGISTRO_VENTAS);
                $resDocEstado = DocumentoNegocio::create()->ActualizarDocumentoEstadoId($documentoId, 2, 1);
                break;

                //ERROR DESCONOCIDO
            case strpos($mensaje, '[Cod: IMA04]') !== false:
                $tipoMensaje = DocumentoTipoNegocio::EFACT_ERROR_DESCONOCIDO;
                $mensaje = "Se generó un error al registrar el documento electrónico. Resultado EFACT: " . $resultado;
                break;

            default:
                throw new WarningException("Resultado EFACT (ERROR): " . $resultado);
        }

        //        if ($tipoMensaje == DocumentoTipoNegocio::EFACT_ERROR_CONTROLADO && $tipoUso == 1) {
        //            throw new WarningException($mensaje);
        //        }

        $resEstadoRegistro = DocumentoNegocio::create()->actualizarEfactEstadoRegistro($documentoId, $tipoMensaje, $resultado); // guardar $resultado 
        if (strpos($resultado, '[FN:') > -1) {
            $indInicial = strpos($resultado, '[FN:');
            $indFinal = strpos($resultado, '.pdf]');

            $nombrePDF = substr($resultado, ($indInicial + 5), ($indFinal - $indInicial - 1));

            if (!ObjectUtil::isEmpty($nombrePDF)) {
                $urlPDF = Configuraciones::EFACT_CONTENEDOR_PDF . $nombrePDF;

                $resActNombrePDF = DocumentoNegocio::create()->actualizarEfactPdfNombre($documentoId, $nombrePDF);
            } else {
                $urlPDF = '';
            }
        }

        if ($tipoMensaje != DocumentoTipoNegocio::EFACT_CORRECTO) {
            $mensaje = "Se registró correctamente en el SGI, pero se ha presentado un problema en el envió a OSE<br>Detalle: " . $mensaje;
            $titulo = ", pendiente de emisión a OSE";
        }

        $respEfact = new stdClass();
        $respEfact->tipoMensaje = $tipoMensaje; //[Cod: IMAEX05] |  Error la generar el documento : Comprobante: F001-000349 presenta el error: Se ha especificado un tipo de proveedor no válido.
        $respEfact->mensaje = $mensaje;
        $respEfact->urlPDF = $urlPDF;
        $respEfact->nombrePDF = $nombrePDF;
        $respEfact->titulo = $titulo; //titulo que en caso de reenvio de comprobante  no será nulo
        return $respEfact;
    }

    public function generarFacturaElectronica($documentoId, $soloPDF, $tipoUso)
    {
        $dataDocumento = DocumentoNegocio::create()->obtenerDetalleDocumento($documentoId);
        // Obtenemos Datos
        $documento = DocumentoNegocio::create()->obtenerDocumentoFEXId($documentoId);
        if (ObjectUtil::isEmpty($documento)) {
            throw new WarningException("No se encontró el documento");
        }

        $empresa = EmpresaNegocio::create()->obtenerEmpresaXId($documento[0]['empresa_id']);

        $ubigeoEmpresa = PersonaNegocio::create()->obtenerUbigeoXId($empresa[0]["ubigeo_id"]);
        if (ObjectUtil::isEmpty($ubigeoEmpresa)) {
            throw new WarningException("No se especificó el ubigeo del emisor");
        }

        $persona = PersonaNegocio::create()->obtenerPersonaXId($documento[0]["persona_id"]);
        if (ObjectUtil::isEmpty($persona)) {
            throw new WarningException("No se encontró a la persona del documento");
        }
        $ubigeo = PersonaNegocio::create()->obtenerUbigeoXId($documento[0]["ubigeo_id"]);
        if (ObjectUtil::isEmpty($ubigeo)) {
            throw new WarningException("No se especificó el ubigeo del receptor");
        }

        $comprobanteElectronico->emisorNroDocumento = $empresa[0]['ruc'];
        $comprobanteElectronico->emisorTipoDocumento = $empresa[0]['sunat_tipo_documento'];
        $comprobanteElectronico->emisorUbigeo = $ubigeoEmpresa[0]['ubigeo_codigo'];
        $comprobanteElectronico->emisorDireccion = $empresa[0]['direccion_fe'];
        $comprobanteElectronico->emisorUrbanizacion = $empresa[0]['urbanizacion'];
        $comprobanteElectronico->emisorDepartamento = $ubigeoEmpresa[0]['ubigeo_dep'];
        $comprobanteElectronico->emisorProvincia = $ubigeoEmpresa[0]['ubigeo_prov'];
        $comprobanteElectronico->emisorDistrito = $ubigeoEmpresa[0]['ubigeo_dist'];
        $comprobanteElectronico->emisorNombreLegal = $empresa[0]['razon_social'];
        $comprobanteElectronico->emisorNombreComercial = $empresa[0]['nombre_comercial'];
        $comprobanteElectronico->emisorNroTelefono = $empresa[0]['telefono'];

        $tipoPago = ($documento[0]["tipo_pago"] * 1);
        // receptor
        $codigoIdentificacion = $persona[0]["codigo_identificacion"];
        $sunatTipoDocumento = $persona[0]["sunat_tipo_documento"];
        $totalIgv = $documento[0]["igv"] * 1.0;
        $totalGravadas = $documento[0]["subtotal"] * 1.0;
        $totalGratuitas = 0;
        $totalDocumento = $documento[0]["total"] * 1.0;
        if (strpos($persona[0]["persona_clase_id"], '18') !== FALSE) {
            $codigoIdentificacion = '0';
            $sunatTipoDocumento = 0;
            $totalIgv = 0;
            $totalGravadas = 0;
            //            $totalGratuitas = $documento[0]["total"] * 1.0;
        }

        //Ventas gratuitas
        if ($documento[0]["movimiento_tipo_codigo"] == "18") {
            $totalGratuitas = $documento[0]["total"] * 1.0;
            //Venta gratuita obsequio 
            if (($this->docInafectas * 1) > 0) {
                $totalGratuitas = $this->docInafectas;
            }
            $totalGravadas = 0;
            $totalIgv = 0;
            $totalDocumento = 0;
        }

        $enLetras = new EnLetras();
        $importeLetras = $enLetras->ValorEnLetras($totalDocumento, $documento[0]['moneda_id']);

        $comprobanteElectronico->receptorNroDocumento = $codigoIdentificacion;
        $comprobanteElectronico->receptorTipoDocumento = $sunatTipoDocumento;
        $comprobanteElectronico->receptorNombreLegal = $persona[0]["persona_nombre_completo"];
        $comprobanteElectronico->receptorUbigeo = $ubigeo[0]["ubigeo_codigo"];
        $comprobanteElectronico->receptorDireccion = $documento[0]["direccion"];
        $comprobanteElectronico->receptorUrbanizacion = '';
        $comprobanteElectronico->receptorDepartamento = $ubigeo[0]["ubigeo_dep"];
        $comprobanteElectronico->receptorProvincia = $ubigeo[0]["ubigeo_prov"];
        $comprobanteElectronico->receptorDistrito = $ubigeo[0]["ubigeo_dist"];

        $correos = PersonaNegocio::create()->obtenerCorreosEFACT();
        $comprobanteElectronico->receptorEmail = str_replace(';', ',', $persona[0]["email"] . "," . $correos[0]["correos"] . "," . Configuraciones::EFACT_CORREO);
        //        $comprobanteElectronico->receptorEmail = 'nleon@imaginatecperu.com';
        // factura
        //VALIDA SERIE
        if ($documento[0]["serie"][0] != 'F') {
            throw new WarningException("La serie del documento debe empezar con F");
        }

        $comprobanteElectronico->docSerie = $documento[0]["serie"];
        $comprobanteElectronico->docNumero = $documento[0]["numero"];
        $comprobanteElectronico->docFechaEmision = substr($documento[0]["fecha_emision"], 0, 10);
        $comprobanteElectronico->docMoneda = $documento[0]["sunat_moneda"];
        $comprobanteElectronico->docMontoEnLetras = $importeLetras; //'TRES MIL OCHOCIENTOS TREINTA Y CINCO SOLES CON 0/100';
        $comprobanteElectronico->docTotalIgv = $totalIgv;
        $comprobanteElectronico->docTotalVenta = $totalDocumento;
        $comprobanteElectronico->docGravadas = $totalGravadas;
        $comprobanteElectronico->docExoneradas = 0.0;
        $comprobanteElectronico->docInafectas = 0.0;
        //        $comprobanteElectronico->docInafectas = (!ObjectUtil::isEmpty($this->docInafectas)) ? $this->docInafectas : 0.0;

        $comprobanteElectronico->docGratuitas = $totalGratuitas;
        $comprobanteElectronico->docDescuentoGlobal = 0.00;
        $comprobanteElectronico->icbper = 0.0;

        if ((ObjectUtil::isEmpty($totalGratuitas) || $totalGratuitas == 0) && ($documento[0]["igv"] * 1) == 0) {
            $comprobanteElectronico->docGravadas = 0;
            $comprobanteElectronico->docExoneradas = $totalGravadas;
        }

        // Detalle
        $documentoDetalle = MovimientoBien::create()->obtenerXIdMovimiento($documento[0]["movimiento_id"]);
        foreach ($documentoDetalle as $index => $fila) {
            $porcentajeIgv = Configuraciones::IGV_PORCENTAJE;
            $valorMonetario = $fila['valor_monetario'] * 1;
            $valorMonetarioRef = $fila['valor_monetario'] * 1;
            if ($fila['incluye_igv'] == 1) {
                $valorMonetario = $fila['valor_monetario'] / (1 + ($porcentajeIgv / 100));
            } else {
                $valorMonetarioRef = $fila['valor_monetario'] * (1 + ($porcentajeIgv / 100));
            }
            $items[$index][0] = $index + 1;
            $items[$index][1] = $fila['cantidad'] * 1;
            $precio = $valorMonetario;
            $precioReferencial = $valorMonetarioRef;
            $tipoPrecio = "01";
            $impuesto = 0; //Impuesto
            $tipoImpuesto = '10';

            $porcentajeImpuesto = Configuraciones::IGV_PORCENTAJE;
            if (strpos($persona[0]["persona_clase_id"], '18') !== FALSE) {
                $tipoImpuesto = '40';
            } elseif ($totalGratuitas > 0) {
                if ($fila['incluye_igv'] * 1 == 1 && ($documento[0]["igv"] * 1) > 0) {
                    $valorMonetarioRef = $fila['valor_monetario'] / (1 + ($porcentajeIgv / 100));
                } else {
                    $valorMonetarioRef = $fila['valor_monetario'] * 1;
                }
                $precioReferencial = $valorMonetarioRef;
                $precio = 0;
                $tipoImpuesto = '21';
                $tipoPrecio = '02'; //Tipos de precio
            } elseif (($documento[0]["igv"] * 1) == 0) {
                $precioReferencial = $precio;
                $tipoImpuesto = '20';
                $porcentajeImpuesto = 0;
            } else {
                $impuesto = $valorMonetario * $fila['cantidad'] * ($porcentajeIgv / 100); //Impuesto 
            }

            $items[$index][2] = $precio;
            $items[$index][3] = $precioReferencial; //Precio refencial
            $items[$index][4] = $tipoPrecio; //Tipos de precio 
            $items[$index][5] = $fila['bien_codigo'];
            $items[$index][6] = $fila['bien_descripcion'];
            $items[$index][7] = $fila['sunat_unidad_medida'];
            $items[$index][8] = $impuesto;
            $items[$index][9] = $tipoImpuesto; //Tipo de impuesto
            $items[$index][10] = ($valorMonetario * $fila['cantidad']);
            $items[$index][11] = 0; //Descuento
            $items[$index][12] = Configuraciones::IGV_PORCENTAJE; //porcentaje IGV
            $items[$index][13] = $fila['bien_codigo_internacional']; //codigo internacional
            $items[$index][14] = 0.0; //ICBPER
        }

        $comprobanteElectronico->items = $items;

        //OBTENEMOS LOS DOCUMENTOS RELACIONADOS
        //guias de remision
        $docRelacion = DocumentoNegocio::create()->obtenerDocumentoRelacionadoActivoXDocumentoId($documentoId);
        $i = 0;
        foreach ($docRelacion as $index => $guias) {
            switch ($guias['identificador_negocio_relacion'] * 1) {
                case 6: {
                        $guiasRemision[$i][0] = $guias["serie_relacion"] . '-' . $guias["numero_relacion"];
                        $guiasRemision[$i][1] = $guias["fecha_emision_relacion"];
                        $guiasRemision[$i][2] = $guias["sunat_tipo_doc_rel"];
                        $i++;
                    }
            }
        }
        //DATOS ADICIONALES                
        if (strpos($persona[0]["persona_clase_id"], '18') !== FALSE) {
            $incoterm = '';

            foreach ($dataDocumento as $index => $item) {
                switch ($item['documento_tipo_id'] * 1) {
                    case 2925:
                        $incoterm = $item['valor'];
                        break;
                }
            }

            $datoAdicional[0][0] = 'Incoterm';
            $datoAdicional[0][1] = $incoterm;

            $comprobanteElectronico->extras = $datoAdicional;
        }
        //FIN DATOS ADICIONALES

        $comprobanteElectronico->guiasRemision = $guiasRemision;
        // orden de compra
        $docOrdenCompra = DocumentoNegocio::create()->obtenerDocumentoRelacionadoImpresion($documentoId);
        foreach ($docOrdenCompra as $index => $ordenCompra) {
            switch ($ordenCompra['identificador_negocio'] * 1) {
                case 2: {
                        $orden = $ordenCompra['serie_numero_original'];
                    }
            }
        }

        $afectoDetraccionRetencion = ($documento[0]["afecto_detraccion_retencion"] * 1);
        $porcentajeDetraccionRetencion = ($documento[0]["porcentaje_afecto"] * 1);
        $montoRetencionDetraccion = ($documento[0]["monto_detraccion_retencion"] * 1);
        $codigoDetraccion = $documento[0]["detraccion_codigo"];
        $tipoCambio = $documento[0]["tipo_cambio"];

        $datoAdicional = array();

        //OBTENER DATOS ADICIONALES        
        $datoAdicional[] = array('Fecha vencimiento', $documento[0]['fecha_vencimiento']);
        $datoAdicional[] = array('Forma pago', $tipoPago == 1 ? "Contado" : "Crédito");

        $formaPago = array();
        if ($tipoPago == 2) {
            if ($afectoDetraccionRetencion == 1) {
                $montoRetencionDetraccionSoles = $montoRetencionDetraccion * 1.0;
                if (ObjectUtil::isEmpty($codigoDetraccion)) {
                    throw new WarningException("Este documento requiere del código detracción aplicada.");
                }
                if (ObjectUtil::isEmpty($montoRetencionDetraccion) || $montoRetencionDetraccion <= 0) {
                    throw new WarningException("Este documento requiere del monto detracción aplicada aplicada.");
                }
                if (ObjectUtil::isEmpty($porcentajeDetraccionRetencion) || $porcentajeDetraccionRetencion <= 0) {
                    throw new WarningException("Este documento requiere del porcentaje detracción aplicada aplicada.");
                }
                if ($comprobanteElectronico->docMoneda != "PEN") {
                    if (ObjectUtil::isEmpty($tipoCambio)) {
                        throw new WarningException("Este documento requiere del tipo de campo para calcular la detracción en SOLES.");
                    }
                    $montoRetencionDetraccionSoles = round($montoRetencionDetraccionSoles * $tipoCambio, 0);
                }
                $formaPago[] = array("Detraccion" . $codigoDetraccion, $montoRetencionDetraccionSoles, "", $porcentajeDetraccionRetencion * 0.01, $this->cuentaBancoNacion);
                $afectoDescripcion = "DETRACCIÓN / OPERACION SUJETA AL SPOT D.L 940 - " . number_format($porcentajeDetraccionRetencion, 2) . "% (" . $comprobanteElectronico->docMoneda . " " . number_format($montoRetencionDetraccion, 2) . ") CTA BN: " . $this->cuentaBancoNacion;
                $datoAdicional[] = array('Afecto a', $afectoDescripcion);

                $comprobanteElectronico->tipoOperacion = "1001"; // Tipo de operación afecto a detracción.
            } elseif ($afectoDetraccionRetencion == 2) {
                if (ObjectUtil::isEmpty($montoRetencionDetraccion) || $montoRetencionDetraccion <= 0) {
                    throw new WarningException("Este documento requiere del monto retención aplicada aplicada.");
                }
                if (ObjectUtil::isEmpty($porcentajeDetraccionRetencion) || $porcentajeDetraccionRetencion <= 0) {
                    throw new WarningException("Este documento requiere del porcentaje retención aplicada aplicada.");
                }

                $formaPago[] = array("Retencion", $montoRetencionDetraccion * 1.0, "", $porcentajeDetraccionRetencion * 0.01);
                $afectoDescripcion = "RETENCIÓN - " . number_format($porcentajeDetraccionRetencion, 2) . "% (" . $comprobanteElectronico->docMoneda . " " . number_format($montoRetencionDetraccion, 2) . ")";
                $datoAdicional[] = array('Afecto a', $afectoDescripcion);
            }

            $montoNetoPago = round($comprobanteElectronico->docTotalVenta - ($montoRetencionDetraccion * 1), 2);
            $formaPago[] = array("Credito", $montoNetoPago);
            $formaPagoDetalle = PagoNegocio::create()->obtenerPagoProgramacionXDocumentoId($documentoId);
            if (ObjectUtil::isEmpty($formaPagoDetalle)) {
                throw new WarningException("Se requiere de la programación de pago de la factura.");
            }

            $arrayFechaVencimiento = array();
            foreach ($formaPagoDetalle as $indexFormaPago => $itemFormaPago) {
                $arrayFechaVencimiento[] = substr($itemFormaPago['fecha_pago'], 0, 10);
                $formaPago[] = array("Cuota" . str_pad(($indexFormaPago + 1), 3, "0", STR_PAD_LEFT), $itemFormaPago['importe'] * 1.0, substr($itemFormaPago['fecha_pago'], 0, 10));
            }
            $fechaMaximaCuota = date("Y-m-d", max(array_map('strtotime', $arrayFechaVencimiento)));
            if (substr($documento[0]['fecha_vencimiento'], 0, 10) != $fechaMaximaCuota) {
                throw new WarningException("La fecha de vencimiento de la factura (" . substr($documento[0]['fecha_vencimiento'], 0, 10) . ") debe ser igual a la última cuota de la programación de pagos ($fechaMaximaCuota)");
            }
        } elseif ($tipoPago == 1) {
            $formaPago[] = array("Contado", 0.0);
        } else {
            throw new WarningException("No se identifica la forma de pago para esta factura.");
        }

        $comprobanteElectronico->ordenCompra = $orden;
        $comprobanteElectronico->formaPago = $formaPago;
        $comprobanteElectronico->extras = $datoAdicional;
        $comprobanteElectronico->usuarioSunatSOL = $empresa[0]['usuario_sunat_sol'];
        $comprobanteElectronico->claveSunatSOL = $empresa[0]['clave_sunat_sol'];

        $comprobanteElectronico->usuarioOSE = Configuraciones::EFACT_USER_OSE;
        $comprobanteElectronico->claveOSE = Configuraciones::EFACT_PASS_OSE;

        if (strpos($persona[0]["persona_clase_id"], '18') !== FALSE) {
            $comprobanteElectronico->tipoOperacion = '0200';
        }


        $client = self::conexionEFAC();
        try {

            if ($soloPDF == 1) {
                $resultado = $client->procesarFacturaPDF((array) $comprobanteElectronico)->procesarFacturaPDFResult;
            } else if ($soloPDF == 2) {
                $comprobanteElectronico->tipoDocumento = $documento[0]["documento_tipo_sunat_codigo"];
                $resultado = $client->reenviarComprobanteElectronico((array) $comprobanteElectronico)->reenviarComprobanteElectronicoResult;
            } else {
                $resultado = $client->procesarFactura((array) $comprobanteElectronico)->procesarFacturaResult;
            }
            //            throw new WarningException($resultado);
            //DESCOMENTAR PARA PROBAR RESPUESTAS
            //            $resultado = "Resultado EFACT: [Cod: IMA01] | La Factura numero F001-000052, ha sido aceptada | [FN: 2018101517134720600143361-01-F001-000049.pdf]";
            //            $resultado = "Resultado EFACT: La Factura numero F001-000052, hay error desconocido | [FN: 2018111613502920600143361-01-F001-000058.pdf]";
            //            $resultado = "Resultado EFACT: [Cod: IMA02] | ERROR SUNAT .. ... -_-";
        } catch (Exception $e) {
            $resultado = $e->getMessage();
            //            throw new WarningException($resultado);
        }

        $resEfact = $this->validarResultadoEfacturaDocumento($resultado, $documentoId, $tipoUso);

        //        var_dump($comprobanteElectronico);
        return $resEfact;
    }

    public function generarBoletaElectronica($documentoId, $soloPDF, $tipoUso)
    {
        // Obtenemos Datos
        $documento = DocumentoNegocio::create()->obtenerDocumentoFEXId($documentoId);
        if (ObjectUtil::isEmpty($documento)) {
            throw new WarningException("No se encontró el documento");
        }

        $empresa = EmpresaNegocio::create()->obtenerEmpresaXId($documento[0]['empresa_id']);

        $ubigeoEmpresa = PersonaNegocio::create()->obtenerUbigeoXId($empresa[0]["ubigeo_id"]);
        if (ObjectUtil::isEmpty($ubigeoEmpresa)) {
            throw new WarningException("No se especificó el ubigeo del emisor");
        }

        $persona = PersonaNegocio::create()->obtenerPersonaXId($documento[0]["persona_id"]);
        if (ObjectUtil::isEmpty($persona)) {
            throw new WarningException("No se encontró a la persona del documento");
        }
        $ubigeo = PersonaNegocio::create()->obtenerUbigeoXId($documento[0]["ubigeo_id"]);
        if (ObjectUtil::isEmpty($ubigeo)) {
            throw new WarningException("No se especificó el ubigeo del receptor");
        }


        $totalIgv = $documento[0]["igv"] * 1.0;
        $totalGravadas = $documento[0]["subtotal"] * 1.0;
        $totalGratuitas = 0;
        $totalDocumento = $documento[0]["total"] * 1.0;
        //Ventas gratuitas
        if ($documento[0]["movimiento_tipo_codigo"] == "18") {
            $totalGratuitas = $documento[0]["total"] * 1.0;
            //Venta gratuita obsequio 
            if (($this->docInafectas * 1) > 0) {
                $totalGratuitas = $this->docInafectas;
            }
            $totalGravadas = 0;
            $totalIgv = 0;
            $totalDocumento = 0;
        }

        $enLetras = new EnLetras();
        $importeLetras = $enLetras->ValorEnLetras($totalDocumento, $documento[0]['moneda_id']);

        $comprobanteElectronico->emisorNroDocumento = $empresa[0]['ruc'];
        $comprobanteElectronico->emisorTipoDocumento = $empresa[0]['sunat_tipo_documento'];
        $comprobanteElectronico->emisorUbigeo = $ubigeoEmpresa[0]['ubigeo_codigo'];
        $comprobanteElectronico->emisorDireccion = $empresa[0]['direccion_fe'];
        $comprobanteElectronico->emisorUrbanizacion = $empresa[0]['urbanizacion'];
        $comprobanteElectronico->emisorDepartamento = $ubigeoEmpresa[0]['ubigeo_dep'];
        $comprobanteElectronico->emisorProvincia = $ubigeoEmpresa[0]['ubigeo_prov'];
        $comprobanteElectronico->emisorDistrito = $ubigeoEmpresa[0]['ubigeo_dist'];
        $comprobanteElectronico->emisorNombreLegal = $empresa[0]['razon_social'];
        $comprobanteElectronico->emisorNombreComercial = $empresa[0]['nombre_comercial'];
        $comprobanteElectronico->emisorNroTelefono = $empresa[0]['telefono'];

        // receptor
        $comprobanteElectronico->receptorNroDocumento = $persona[0]["codigo_identificacion"];
        $comprobanteElectronico->receptorTipoDocumento = $persona[0]["sunat_tipo_documento"];
        $comprobanteElectronico->receptorNombreLegal = $persona[0]["persona_nombre_completo"];
        $comprobanteElectronico->receptorUbigeo = $ubigeo[0]["ubigeo_codigo"];
        $comprobanteElectronico->receptorDireccion = $documento[0]["direccion"];
        $comprobanteElectronico->receptorUrbanizacion = '';
        $comprobanteElectronico->receptorDepartamento = $ubigeo[0]["ubigeo_dep"];
        $comprobanteElectronico->receptorProvincia = $ubigeo[0]["ubigeo_prov"];
        $comprobanteElectronico->receptorDistrito = $ubigeo[0]["ubigeo_dist"];

        $correos = PersonaNegocio::create()->obtenerCorreosEFACT();
        $comprobanteElectronico->receptorEmail = str_replace(';', ',', $persona[0]["email"] . "," . $correos[0]["correos"] . "," . Configuraciones::EFACT_CORREO);
        //        $comprobanteElectronico->receptorEmail = 'nleon@imaginatecperu.com';
        // factura        
        //VALIDA SERIE
        if ($documento[0]["serie"][0] != 'B') {
            throw new WarningException("La serie del documento debe empezar con B");
        }

        $comprobanteElectronico->docSerie = $documento[0]["serie"];
        $comprobanteElectronico->docNumero = $documento[0]["numero"];
        $comprobanteElectronico->docFechaEmision = substr($documento[0]["fecha_emision"], 0, 10);
        $comprobanteElectronico->docMoneda = $documento[0]["sunat_moneda"];
        $comprobanteElectronico->docMontoEnLetras = $importeLetras; //'TRES MIL OCHOCIENTOS TREINTA Y CINCO SOLES CON 0/100';
        $comprobanteElectronico->docTotalIgv = $totalIgv;
        $comprobanteElectronico->docTotalVenta = $totalDocumento;
        $comprobanteElectronico->docGravadas = $totalGravadas;
        $comprobanteElectronico->docExoneradas = 0.0;
        $comprobanteElectronico->docInafectas = 0.0;
        $comprobanteElectronico->docGratuitas = $totalGratuitas;
        $comprobanteElectronico->docDescuentoGlobal = 0.0;
        $comprobanteElectronico->icbper = 0.0;

        // Detalle
        $documentoDetalle = MovimientoBien::create()->obtenerXIdMovimiento($documento[0]["movimiento_id"]);
        foreach ($documentoDetalle as $index => $fila) {
            $porcentajeIgv = Configuraciones::IGV_PORCENTAJE;
            $valorMonetario = $fila['valor_monetario'] * 1;
            if ($fila['incluye_igv'] == 1) {
                $valorMonetario = $fila['valor_monetario'] / 1.18;
            }

            $items[$index][0] = $index + 1;
            $items[$index][1] = $fila['cantidad'] * 1;
            $precio = $valorMonetario;

            $precioReferencial = 0;
            $tipoPrecio = "01";
            $impuesto = 0; //Impuesto
            $tipoImpuesto = '10';

            if ($totalGratuitas > 0) {
                if ($fila['incluye_igv'] * 1 == 1 && ($documento[0]["igv"] * 1) > 0) {
                    $valorMonetario = $fila['valor_monetario'] / (1 + ($porcentajeIgv / 100));
                } else {
                    $valorMonetario = $fila['valor_monetario'] * 1;
                }
                $precio = 0;
                $precioReferencial = $valorMonetario;
                $tipoImpuesto = '21';
                $tipoPrecio = '02'; //Tipos de precio
            } else {
                $impuesto = $valorMonetario * $fila['cantidad'] * ($porcentajeIgv / 100); //Impuesto 
            }

            $items[$index][2] = $precio;
            $items[$index][3] = $precioReferencial; //Precio refencial
            $items[$index][4] = $tipoPrecio; //Tipos de precio 
            $items[$index][5] = $fila['bien_codigo'];
            $items[$index][6] = $fila['bien_descripcion'];
            $items[$index][7] = $fila['sunat_unidad_medida'];
            $items[$index][8] = $impuesto;
            $items[$index][9] = $tipoImpuesto; //Tipo de impuesto
            $items[$index][10] = $valorMonetario * $fila['cantidad'];
            $items[$index][11] = 0; //Descuento
            $items[$index][12] = Configuraciones::IGV_PORCENTAJE; //porcentaje IGV
            $items[$index][13] = $fila['bien_codigo_internacional']; //codigo internacional
            $items[$index][14] = 0.0; //ICBPER
        }

        $comprobanteElectronico->items = $items;
        $comprobanteElectronico->usuarioSunatSOL = $empresa[0]['usuario_sunat_sol'];
        $comprobanteElectronico->claveSunatSOL = $empresa[0]['clave_sunat_sol'];
        $comprobanteElectronico->usuarioOSE = Configuraciones::EFACT_USER_OSE;
        $comprobanteElectronico->claveOSE = Configuraciones::EFACT_PASS_OSE;

        $client = self::conexionEFAC();

        try {
            if ($soloPDF == 1) {
                $resultado = $client->procesarBoletaPDF((array) $comprobanteElectronico)->procesarBoletaPDFResult;
            } else if ($soloPDF == 2) {
                $comprobanteElectronico->tipoDocumento = $documento[0]["documento_tipo_sunat_codigo"];
                $resultado = $client->reenviarComprobanteElectronico((array) $comprobanteElectronico)->reenviarComprobanteElectronicoResult;
            } else {
                $resultado = $client->procesarBoleta((array) $comprobanteElectronico)->procesarBoletaResult;
            }
        } catch (Exception $e) {
            $resultado = $e->getMessage();
        }

        $resEfact = $this->validarResultadoEfacturaDocumento($resultado, $documentoId, $tipoUso);

        //        var_dump($comprobanteElectronico);
        return $resEfact;
    }

    public function generarNotaCreditoElectronica($documentoId, $soloPDF, $tipoUso)
    {
        $comprobanteElectronico = new ObjectUtil();

        // Obtenemos Datos
        $documento = DocumentoNegocio::create()->obtenerDocumentoFEXId($documentoId);
        if (ObjectUtil::isEmpty($documento)) {
            throw new WarningException("No se encontró el documento");
        }

        $empresa = EmpresaNegocio::create()->obtenerEmpresaXId($documento[0]['empresa_id']);

        $ubigeoEmpresa = PersonaNegocio::create()->obtenerUbigeoXId($empresa[0]["ubigeo_id"]);
        if (ObjectUtil::isEmpty($ubigeoEmpresa)) {
            throw new WarningException("No se especificó el ubigeo del emisor");
        }

        $persona = PersonaNegocio::create()->obtenerPersonaXId($documento[0]["persona_id"]);
        if (ObjectUtil::isEmpty($persona)) {
            throw new WarningException("No se encontró a la persona del documento");
        }
        $ubigeo = PersonaNegocio::create()->obtenerUbigeoXId($documento[0]["ubigeo_id"]);
        if (ObjectUtil::isEmpty($ubigeo)) {
            throw new WarningException("No se especificó el ubigeo del receptor");
        }
        $enLetras = new EnLetras();
        $importeTotalLetras = $documento[0]["total"];
        if ($documento[0]["motivo_codigo"] == 13) {
            $importeTotalLetras = 0.0;
        }
        $importeLetras = $enLetras->ValorEnLetras($importeTotalLetras, $documento[0]['moneda_id']);

        $comprobanteElectronico->emisorNroDocumento = $empresa[0]['ruc'];
        $comprobanteElectronico->emisorTipoDocumento = $empresa[0]['sunat_tipo_documento'];
        $comprobanteElectronico->emisorUbigeo = $ubigeoEmpresa[0]['ubigeo_codigo'];
        $comprobanteElectronico->emisorDireccion = $empresa[0]['direccion_fe'];
        $comprobanteElectronico->emisorUrbanizacion = $empresa[0]['urbanizacion'];
        $comprobanteElectronico->emisorDepartamento = $ubigeoEmpresa[0]['ubigeo_dep'];
        $comprobanteElectronico->emisorProvincia = $ubigeoEmpresa[0]['ubigeo_prov'];
        $comprobanteElectronico->emisorDistrito = $ubigeoEmpresa[0]['ubigeo_dist'];
        $comprobanteElectronico->emisorNombreLegal = $empresa[0]['razon_social'];
        $comprobanteElectronico->emisorNombreComercial = $empresa[0]['nombre_comercial'];
        $comprobanteElectronico->emisorNroTelefono = $empresa[0]['telefono'];

        // receptor
        $comprobanteElectronico->receptorNroDocumento = $persona[0]["codigo_identificacion"];
        $comprobanteElectronico->receptorTipoDocumento = $persona[0]["sunat_tipo_documento"];
        $comprobanteElectronico->receptorNombreLegal = $persona[0]["persona_nombre_completo"];
        $comprobanteElectronico->receptorUbigeo = $ubigeo[0]["ubigeo_codigo"];
        $comprobanteElectronico->receptorDireccion = $documento[0]["direccion"];
        $comprobanteElectronico->receptorUrbanizacion = '';
        $comprobanteElectronico->receptorDepartamento = $ubigeo[0]["ubigeo_dep"];
        $comprobanteElectronico->receptorProvincia = $ubigeo[0]["ubigeo_prov"];
        $comprobanteElectronico->receptorDistrito = $ubigeo[0]["ubigeo_dist"];

        $correos = PersonaNegocio::create()->obtenerCorreosEFACT();
        $comprobanteElectronico->receptorEmail = str_replace(';', ',', $persona[0]["email"] . "," . $correos[0]["correos"] . "," . Configuraciones::EFACT_CORREO);

        //        $comprobanteElectronico->receptorEmail = 'nleon@imaginatecperu.com';
        //        // factura
        //        $serieNum=  DocumentoNegocio::create()->obtenerSerieNumeroXDocumentoId($documentoId);
        //                
        //        //VALIDA SERIE
        //        if ($serieNum[0]["serie"][0] != 'F' && $serieNum[0]["serie"][0] != 'B') {
        //            throw new WarningException("La serie del documento debe empezar con F o B");
        //        }
        // factura
        //VALIDA SERIE
        if ($documento[0]["serie"][0] != 'F' && $documento[0]["serie"][0] != 'B') {
            throw new WarningException("La serie del documento debe empezar con F o B");
        }

        //        $comprobanteElectronico->docSerie = $serieNum[0]["serie"];
        //        $comprobanteElectronico->docNumero = $serieNum[0]["numero"];
        $comprobanteElectronico->docSerie = $documento[0]["serie"];
        $comprobanteElectronico->docNumero = $documento[0]["numero"];
        $comprobanteElectronico->docFechaEmision = substr($documento[0]["fecha_emision"], 0, 10);
        $comprobanteElectronico->docMoneda = $documento[0]["sunat_moneda"];
        $comprobanteElectronico->docMontoEnLetras = $importeLetras; //'TRES MIL OCHOCIENTOS TREINTA Y CINCO SOLES CON 0/100';
        $docTotalIgv = $documento[0]["total"] * 1 - $documento[0]["total"] / 1.18;
        $docTotalVenta = $documento[0]["total"] * 1;
        $docGravadas = $documento[0]["total"] / 1.18;
        if ($documento[0]["motivo_codigo"] == 13) { // Cambio de importes para la NC tipo 13
            $docTotalIgv = 0;
            $docTotalVenta = 0;
            $docGravadas = 0;
        }

        $comprobanteElectronico->docTotalIgv = $docTotalIgv;
        $comprobanteElectronico->docTotalVenta = $docTotalVenta;
        $comprobanteElectronico->docGravadas = $docGravadas;
        $comprobanteElectronico->docExoneradas = 0.0;
        $comprobanteElectronico->docInafectas = 0.0;
        $comprobanteElectronico->docGratuitas = 0.0;
        $comprobanteElectronico->docDescuentoGlobal = 0.0;

        // Detalle
        $documentoDetalle = MovimientoBien::create()->obtenerXIdMovimiento($documento[0]["movimiento_id"]);
        foreach ($documentoDetalle as $index => $fila) {
            $impuestoIgv = Configuraciones::IGV_PORCENTAJE / 100;
            $valorMonetario = $fila['valor_monetario'] * 1;
            if ($fila['incluye_igv'] == 1) {
                $valorMonetario = $fila['valor_monetario'] / (1 + $impuestoIgv);
            }

            $totalVentaItem = $valorMonetario * $fila['cantidad'];
            $impuestoItem = $valorMonetario * $fila['cantidad'] * $impuestoIgv;
            if ($documento[0]["motivo_codigo"] == 13) { // Cambio de importes para la NC tipo 13
                $valorMonetario = 0.0;
                $totalVentaItem = 0.0;
                $impuestoItem = 0.0;
            }

            $items[$index][0] = $index + 1;
            $items[$index][1] = $fila['cantidad'] * 1;
            $items[$index][2] = $valorMonetario;
            $items[$index][3] = 0; //Precio refencial
            $items[$index][4] = '01'; //Tipos de precio
            $items[$index][5] = $fila['bien_codigo'];
            $items[$index][6] = $fila['bien_descripcion'];
            $items[$index][7] = $fila['sunat_unidad_medida'];
            $items[$index][8] = $impuestoItem; //Impuesto
            $items[$index][9] = '10'; //Tipo de imp$impuestoItem
            $items[$index][10] = $totalVentaItem;
            $items[$index][11] = 0.0; //Descuento
            $items[$index][12] = Configuraciones::IGV_PORCENTAJE; //porcentaje IGV
        }

        $comprobanteElectronico->items = $items;

        //VALIDO EL COMENTARIO
        if (ObjectUtil::isEmpty($documento[0]["comentario"])) {
            throw new WarningException("Ingrese comentario (Sustento por el que se emite la NC)");
        }

        //OBTENEMOS LOS DOCUMENTOS RELACIONADOS
        $docRelacion = DocumentoNegocio::create()->obtenerDocumentoRelacionadoActivoXDocumentoId($documentoId);

        $discrepancias = array();
        foreach ($docRelacion as $indRel => $itemRel) {
            if (
                $itemRel['identificador_negocio_relacion'] == DocumentoTipoNegocio::IN_BOLETA_VENTA ||
                $itemRel['identificador_negocio_relacion'] == DocumentoTipoNegocio::IN_FACTURA_VENTA
            ) {

                //VALIDA SERIE
                /* if ($itemRel['identificador_negocio_relacion'] == DocumentoTipoNegocio::IN_BOLETA_VENTA && $itemRel["serie_relacion"][0] != 'B') {
                  throw new WarningException("La serie de la boleta relacionada debe empezar con B");
                  } else if ($itemRel['identificador_negocio_relacion'] == DocumentoTipoNegocio::IN_FACTURA_VENTA && $itemRel["serie_relacion"][0] != 'F') {
                  throw new WarningException("La serie de la factura relacionada debe empezar con F");
                  } */

                $itemDiscrepancia[0] = $documento[0]["comentario"];
                $itemDiscrepancia[1] = $itemRel["serie_relacion"] . '-' . $itemRel["numero_relacion"];
                $itemDiscrepancia[2] = $documento[0]["motivo_codigo"];

                array_push($discrepancias, $itemDiscrepancia);
            }
        }

        //VALIDO QUE HAYA DISCREPANCIAS
        if (ObjectUtil::isEmpty($discrepancias)) {
            throw new WarningException("Relacione un documento de venta (factura o boleta)");
        }

        $comprobanteElectronico->discrepancias = array($discrepancias[0]);

        $docRelacionados = array();
        foreach ($docRelacion as $indRel => $itemRel) {
            if (
                $itemRel['identificador_negocio_relacion'] == DocumentoTipoNegocio::IN_BOLETA_VENTA ||
                $itemRel['identificador_negocio_relacion'] == DocumentoTipoNegocio::IN_FACTURA_VENTA
            ) {
                $itemRelacion[0] = $itemRel["serie_relacion"] . '-' . $itemRel["numero_relacion"];
                $itemRelacion[1] = $itemRel['sunat_tipo_doc_rel'];

                array_push($docRelacionados, $itemRelacion);
            }
        }
        $tipoPago = ($documento[0]["tipo_pago"] * 1);
        if ($documento[0]["motivo_codigo"] == 13) {


            $datoAdicional = array();
            //OBTENER DATOS ADICIONALES        
            $datoAdicional[] = array('Fecha vencimiento', $documento[0]['fecha_vencimiento']);
            $datoAdicional[] = array('Forma pago', $tipoPago == 1 ? "Contado" : "Crédito");
            $comprobanteElectronico->extras = $datoAdicional;

            $formaPago = array();

            if ($tipoPago == 2) {
                $formaPagoDetalle = PagoNegocio::create()->obtenerPagoProgramacionXDocumentoId($documentoId);
                if (ObjectUtil::isEmpty($formaPagoDetalle)) {
                    throw new WarningException("Se requiere de la programación de pago de la factura.");
                }
                $montoNetoPago = 0.0;
                foreach ($formaPagoDetalle as $indexFormaPago => $itemFormaPago) {
                    $montoNetoPago += $itemFormaPago['importe'] * 1.0;
                }
                $formaPago[] = array("Credito", $montoNetoPago);

                $arrayFechaVencimiento = array();
                foreach ($formaPagoDetalle as $indexFormaPago => $itemFormaPago) {
                    $arrayFechaVencimiento[] = substr($itemFormaPago['fecha_pago'], 0, 10);
                    $formaPago[] = array("Cuota" . str_pad(($indexFormaPago + 1), 3, "0", STR_PAD_LEFT), $itemFormaPago['importe'] * 1.0, substr($itemFormaPago['fecha_pago'], 0, 10));
                }
            } elseif ($tipoPago == 1) {
                throw new WarningException("Nota de credito tipo 13 debe ser con forma de pago CREDITO. Se requiere de la programación de pago de la factura.");

                $formaPago[] = array("Contado", 0.0, "");
            } else {
                throw new WarningException("No se identifica la forma de pago para este comprobante.");
            }
            $comprobanteElectronico->formaPago = $formaPago;
        }

        $comprobanteElectronico->docRelacionados = array($docRelacionados[0]);

        $comprobanteElectronico->usuarioSunatSOL = $empresa[0]['usuario_sunat_sol'];
        $comprobanteElectronico->claveSunatSOL = $empresa[0]['clave_sunat_sol'];
        $comprobanteElectronico->usuarioOSE = Configuraciones::EFACT_USER_OSE;
        $comprobanteElectronico->claveOSE = Configuraciones::EFACT_PASS_OSE;

        $client = self::conexionEFAC();

        try {

            if ($soloPDF == 1) {
                $resultado = $client->procesarNotaCreditoPDF((array) $comprobanteElectronico)->procesarNotaCreditoPDFResult;
            } else if ($soloPDF == 2) {
                $comprobanteElectronico->icbper = 0.0;
                $comprobanteElectronico->tipoDocumento = $documento[0]["documento_tipo_sunat_codigo"];
                $resultado = $client->reenviarComprobanteElectronico((array) $comprobanteElectronico)->reenviarComprobanteElectronicoResult;
            } else {
                $resultado = $client->procesarNotaCredito((array) $comprobanteElectronico)->procesarNotaCreditoResult;
            }
        } catch (Exception $e) {
            $resultado = $e->getMessage();
        }

        $resEfact = $this->validarResultadoEfacturaDocumento($resultado, $documentoId, $tipoUso);

        //        var_dump($comprobanteElectronico);
        return $resEfact;
    }

    public function generarNotaDebitoElectronica($documentoId, $soloPDF, $tipoUso)
    {
        // Obtenemos Datos
        $documento = DocumentoNegocio::create()->obtenerDocumentoFEXId($documentoId);
        if (ObjectUtil::isEmpty($documento)) {
            throw new WarningException("No se encontró el documento");
        }

        $empresa = EmpresaNegocio::create()->obtenerEmpresaXId($documento[0]['empresa_id']);

        $ubigeoEmpresa = PersonaNegocio::create()->obtenerUbigeoXId($empresa[0]["ubigeo_id"]);
        if (ObjectUtil::isEmpty($ubigeoEmpresa)) {
            throw new WarningException("No se especificó el ubigeo del emisor");
        }

        $persona = PersonaNegocio::create()->obtenerPersonaXId($documento[0]["persona_id"]);
        if (ObjectUtil::isEmpty($persona)) {
            throw new WarningException("No se encontró a la persona del documento");
        }
        $ubigeo = PersonaNegocio::create()->obtenerUbigeoXId($documento[0]["ubigeo_id"]);
        if (ObjectUtil::isEmpty($ubigeo)) {
            throw new WarningException("No se especificó el ubigeo del receptor");
        }
        $enLetras = new EnLetras();
        $importeLetras = $enLetras->ValorEnLetras($documento[0]["total"], $documento[0]['moneda_id']);

        $comprobanteElectronico->emisorNroDocumento = $empresa[0]['ruc'];
        $comprobanteElectronico->emisorTipoDocumento = $empresa[0]['sunat_tipo_documento'];
        $comprobanteElectronico->emisorUbigeo = $ubigeoEmpresa[0]['ubigeo_codigo'];
        $comprobanteElectronico->emisorDireccion = $empresa[0]['direccion_fe'];
        $comprobanteElectronico->emisorUrbanizacion = $empresa[0]['urbanizacion'];
        $comprobanteElectronico->emisorDepartamento = $ubigeoEmpresa[0]['ubigeo_dep'];
        $comprobanteElectronico->emisorProvincia = $ubigeoEmpresa[0]['ubigeo_prov'];
        $comprobanteElectronico->emisorDistrito = $ubigeoEmpresa[0]['ubigeo_dist'];
        $comprobanteElectronico->emisorNombreLegal = $empresa[0]['razon_social'];
        $comprobanteElectronico->emisorNombreComercial = $empresa[0]['nombre_comercial'];
        $comprobanteElectronico->emisorNroTelefono = $empresa[0]['telefono'];

        // receptor
        $comprobanteElectronico->receptorNroDocumento = $persona[0]["codigo_identificacion"];
        $comprobanteElectronico->receptorTipoDocumento = $persona[0]["sunat_tipo_documento"];
        $comprobanteElectronico->receptorNombreLegal = $persona[0]["persona_nombre_completo"];
        $comprobanteElectronico->receptorUbigeo = $ubigeo[0]["ubigeo_codigo"];
        $comprobanteElectronico->receptorDireccion = $documento[0]["direccion"];
        $comprobanteElectronico->receptorUrbanizacion = '';
        $comprobanteElectronico->receptorDepartamento = $ubigeo[0]["ubigeo_dep"];
        $comprobanteElectronico->receptorProvincia = $ubigeo[0]["ubigeo_prov"];
        $comprobanteElectronico->receptorDistrito = $ubigeo[0]["ubigeo_dist"];

        $correos = PersonaNegocio::create()->obtenerCorreosEFACT();
        $comprobanteElectronico->receptorEmail = str_replace(';', ',', $persona[0]["email"] . "," . $correos[0]["correos"] . "," . Configuraciones::EFACT_CORREO);
        //        $comprobanteElectronico->receptorEmail = 'nleon@imaginatecperu.com';
        //VALIDA SERIE
        if ($documento[0]["serie"][0] != 'F' && $documento[0]["serie"][0] != 'B') {
            throw new WarningException("La serie del documento debe empezar con F o B");
        }

        $comprobanteElectronico->docSerie = $documento[0]["serie"];
        $comprobanteElectronico->docNumero = $documento[0]["numero"];
        $comprobanteElectronico->docFechaEmision = substr($documento[0]["fecha_emision"], 0, 10);
        $comprobanteElectronico->docMoneda = $documento[0]["sunat_moneda"];
        $comprobanteElectronico->docMontoEnLetras = $importeLetras; //'TRES MIL OCHOCIENTOS TREINTA Y CINCO SOLES CON 0/100';
        $comprobanteElectronico->docTotalIgv = $documento[0]["total"] * 1 - $documento[0]["total"] / 1.18;
        $comprobanteElectronico->docTotalVenta = $documento[0]["total"] * 1;
        $comprobanteElectronico->docGravadas = $documento[0]["total"] / 1.18;
        $comprobanteElectronico->docExoneradas = 0.0;
        $comprobanteElectronico->docInafectas = 0.0;
        $comprobanteElectronico->docGratuitas = 0.0;
        $comprobanteElectronico->docDescuentoGlobal = 0.0;

        // Detalle
        $documentoDetalle = MovimientoBien::create()->obtenerXIdMovimiento($documento[0]["movimiento_id"]);
        foreach ($documentoDetalle as $index => $fila) {

            $valorMonetario = $fila['valor_monetario'] * 1;
            if ($fila['incluye_igv'] == 1) {
                $valorMonetario = $fila['valor_monetario'] / 1.18;
            }

            $items[$index][0] = $index + 1;
            $items[$index][1] = $fila['cantidad'] * 1;
            $items[$index][2] = $valorMonetario;
            $items[$index][3] = 0; //Precio refencial
            $items[$index][4] = '01'; //Tipos de precio
            $items[$index][5] = $fila['bien_codigo'];
            $items[$index][6] = $fila['bien_descripcion'];
            $items[$index][7] = $fila['sunat_unidad_medida'];
            $items[$index][8] = $valorMonetario * $fila['cantidad'] * 0.18; //Impuesto
            $items[$index][9] = '10'; //Tipo de impuesto
            $items[$index][10] = $valorMonetario * $fila['cantidad'];
            $items[$index][11] = 0; //Descuento
            $items[$index][12] = Configuraciones::IGV_PORCENTAJE; //porcentaje IGV
        }

        $comprobanteElectronico->items = $items;

        //VALIDO EL COMENTARIO
        if (ObjectUtil::isEmpty($documento[0]["comentario"])) {
            throw new WarningException("Ingrese comentario (Sustento por el que se emite la ND)");
        }

        //OBTENEMOS LOS DOCUMENTOS RELACIONADOS
        $docRelacion = DocumentoNegocio::create()->obtenerDocumentoRelacionadoActivoXDocumentoId($documentoId);

        $discrepancias = array();
        foreach ($docRelacion as $indRel => $itemRel) {
            if (
                $itemRel['identificador_negocio_relacion'] == DocumentoTipoNegocio::IN_BOLETA_VENTA ||
                $itemRel['identificador_negocio_relacion'] == DocumentoTipoNegocio::IN_FACTURA_VENTA
            ) {

                //VALIDA SERIE
                /*
                  if ($itemRel['identificador_negocio_relacion'] == DocumentoTipoNegocio::IN_BOLETA_VENTA && $serieNumRel[0]["serie"][0] != 'B') {
                  throw new WarningException("La serie de la boleta relacionada debe empezar con B");
                  } else if ($itemRel['identificador_negocio_relacion'] == DocumentoTipoNegocio::IN_FACTURA_VENTA && $serieNumRel[0]["serie"][0] != 'F') {
                  throw new WarningException("La serie de la factura relacionada debe empezar con F");
                  } */

                $itemDiscrepancia[0] = $documento[0]["comentario"];
                $itemDiscrepancia[1] = $itemRel["serie_relacion"] . '-' . $itemRel["numero_relacion"];
                $itemDiscrepancia[2] = $documento[0]["motivo_codigo"];

                array_push($discrepancias, $itemDiscrepancia);
            }
        }

        //VALIDO QUE HAYA DISCREPANCIAS
        if (ObjectUtil::isEmpty($discrepancias)) {
            throw new WarningException("Relacione un documento de venta (factura o boleta)");
        }

        $comprobanteElectronico->discrepancias = $discrepancias;

        $docRelacionados = array();
        foreach ($docRelacion as $indRel => $itemRel) {
            if (
                $itemRel['identificador_negocio_relacion'] == DocumentoTipoNegocio::IN_BOLETA_VENTA ||
                $itemRel['identificador_negocio_relacion'] == DocumentoTipoNegocio::IN_FACTURA_VENTA
            ) {

                $itemRelacion[0] = $itemRel["serie_relacion"] . '-' . $itemRel["numero_relacion"];
                $itemRelacion[1] = $itemRel['sunat_tipo_doc_rel'];

                array_push($docRelacionados, $itemRelacion);
            }
        }

        $comprobanteElectronico->docRelacionados = $docRelacionados;

        $comprobanteElectronico->usuarioSunatSOL = $empresa[0]['usuario_sunat_sol'];
        $comprobanteElectronico->claveSunatSOL = $empresa[0]['clave_sunat_sol'];
        $comprobanteElectronico->usuarioOSE = Configuraciones::EFACT_USER_OSE;
        $comprobanteElectronico->claveOSE = Configuraciones::EFACT_PASS_OSE;

        $client = self::conexionEFAC();

        try {
            if ($soloPDF == 1) {
                $resultado = $client->procesarNotaDebitoPDF((array) $comprobanteElectronico)->procesarNotaDebitoPDFResult;
            } else if ($soloPDF == 2) {
                $comprobanteElectronico->icbper = 0.0;
                $comprobanteElectronico->tipoDocumento = $documento[0]["documento_tipo_sunat_codigo"];
                $resultado = $client->reenviarComprobanteElectronico((array) $comprobanteElectronico)->reenviarComprobanteElectronicoResult;
            } else {
                $resultado = $client->procesarNotaDebito((array) $comprobanteElectronico)->procesarNotaDebitoResult;
            }
        } catch (Exception $e) {
            $resultado = $e->getMessage();
        }

        $resEfact = $this->validarResultadoEfacturaDocumento($resultado, $documentoId, $tipoUso);

        //        var_dump($comprobanteElectronico);
        return $resEfact;
    }

    public function validarBienesFaltantes($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $documentoARelacionar, $valorCheck, $comentario = NULL, $checkIgv = 1, $monedaId = null, $accionEnvio)
    {
        //validacion en caso de bienes faltantes
        $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);
        $bandera = false;
        $dataOrganizador = array();
        $dataProveedor = array();

        if ($movimientoTipo[0]["indicador"] == MovimientoTipoNegocio::INDICADOR_SALIDA) {
            $j = 0;
            foreach ($detalle as $indice => $item) {
                $stockData = BienNegocio::create()->obtenerStockActual($item["bienId"], $item["organizadorId"], $item["unidadMedidaId"]);
                $stock = $stockData[0]["stock"];
                if ($stock < 0) {
                    $stock = 0;
                }

                $cantidadFaltante = $stock - $item["cantidad"];
                $cantidadFaltante = $cantidadFaltante * -1;
                //validar que no sea servicio
                $bien = BienNegocio::create()->getBien($item["bienId"]);

                if ($cantidadFaltante > 0 && $bien[0]['bien_tipo_id'] != -1) {

                    $dataOrganizador[$j] = array();
                    //$dataProveedor[$j]=array();

                    $dataP = BienNegocio::create()->obtenerBienPersonaXBienId($item["bienId"]);
                    array_push($dataProveedor, $dataP);

                    $bandera = true;
                    $detalleFaltantes[$j] = $item;

                    $detalle[$indice]["cantidad"] = $stock;
                    $detalleFaltantes[$j]["cantidad"] = $cantidadFaltante;
                    $detalleFaltantes[$j]["organizadorId"] = '';

                    //$dataStockBien=BienNegocio::create()->obtenerStockPorBien($item["bienId"], $movimientoTipo[0]["empresa_id"]);
                    $dataStockBien = BienNegocio::create()->obtenerStockPorBien($item["bienId"], null);

                    foreach ($dataStockBien->stockBien as $ind => $itemDataStock) {
                        if ($cantidadFaltante <= $itemDataStock["stock"] && $item["unidadMedidaId"] == $itemDataStock["unidad_medida_id"]) {

                            array_push($dataOrganizador[$j], array('organizadorId' => $itemDataStock["organizador_id"], 'descripcion' => $itemDataStock["organizador_descripcion"]));
                        }
                    }

                    $j++;
                }
            }

            $respuesta->detalleFaltantes = $detalleFaltantes;
            $respuesta->dataOrganizador = $dataOrganizador;
            $respuesta->dataProveedor = $dataProveedor;
            $respuesta->dataDetalle = $detalle;
        }

        if ($bandera) {
            return $respuesta;
        }


        //fin validaacion       

        return $this->guardarXAccionEnvio($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $documentoARelacionar, $valorCheck, $comentario, $checkIgv, $monedaId, $accionEnvio);
    }

    public function guardarNormalmente($documentoId, $dataMovBienPRM, $usuarioIdPRM)
    {
        $relacionadosDocumentoActual = DocumentoNegocio::create()->obtenerDocumentosRelacionados($documentoId);

        foreach ($relacionadosDocumentoActual as $item) {

            $docRelacionadoId = $item['documento_relacionado_id'];

            $movimientoIdActual = MovimientoBien::create()->obtenerMovimientoIdXDocumentoId($documentoId);
            $dataMovBien = $dataMovBienPRM;
            $bienesIdRelacionados = MovimientoBien::create()->obtenerBienesIdRelacionadosXDocumentoId($docRelacionadoId);
            foreach ($dataMovBien as $dataMovBienActual) {
                foreach ($bienesIdRelacionados as $bienesIdRelacionado) {

                    if ($dataMovBienActual['bien_id'] == $bienesIdRelacionado['bien_id']) {

                        $movimiento_bien_anterior = $bienesIdRelacionado['movimiento_bien_anterior'];
                        $movimiento_bien_destino = $dataMovBienActual['movimiento_bien_id'];
                        $cantidad = $bienesIdRelacionado['cantidad_solicitada'];
                        MovimientoBien::create()->guardarDocumentoAtencionSolicitud($movimiento_bien_anterior, $movimiento_bien_destino, $cantidad, $usuarioIdPRM);
                    }
                }
            }
        }
    }

    public function guardarDocumentoPercepcion($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $documentoARelacionar, $valorCheck, $comentario, $checkIgv, $monedaId, $tipoPago, $percepcion, $periodoId)
    {
        //OBTENIENDO PERCEPCION
        $percepcionMonto = $percepcion["importeSoles"];
        foreach ($camposDinamicos as $indexCampos => $valorDtd) {
            //            if ($valorDtd["tipo"] == 19) {
            //                $percepcionMonto = $valorDtd["valor"];
            //            }
            if ($valorDtd["tipo"] == 9) {
                $fechaEmisionDtd = $valorDtd["valor"];
            }
        }

        if ($opcionId == Configuraciones::OPCION_ID_DUA && $percepcionMonto * 1 != 0 && !ObjectUtil::isEmpty($percepcionMonto)) {
            $opcionIdPer = null;
            $documentoTipoIdPer = Configuraciones::DOCUMENTO_TIPO_ID_PERCEPCION;

            //cabecera del documento            
            $configuracionesDtd = DocumentoTipoNegocio::create()->obtenerDocumentoTipoDatoSimple($documentoTipoIdPer);

            foreach ($configuracionesDtd as $indexConfig => $itemDtd) {
                if ($itemDtd["tipo"] == 8) {
                    $configuracionesDtd[$indexConfig]["valor"] = DocumentoNegocio::create()->obtenerNumeroAutoIncrementalXDocumentoTipo($documentoTipoIdPer);
                }
                if ($itemDtd["tipo"] == 9) {
                    $configuracionesDtd[$indexConfig]["valor"] = $fechaEmisionDtd;
                }
                if ($itemDtd["tipo"] == 7) {
                    $configuracionesDtd[$indexConfig]["valor"] = $itemDtd['cadena_defecto'];
                }
                if ($itemDtd["tipo"] == 14) {
                    $configuracionesDtd[$indexConfig]["valor"] = $percepcionMonto;
                }
            }
            $monedaId = 2;
            $documentoId = PagoNegocio::create()->guardar($opcionIdPer, $usuarioId, $documentoTipoIdPer, $configuracionesDtd, $monedaId, $periodoId);

            $documentoARelacionarRecep = array('documentoId' => $documentoId, 'movimientoId' => '', 'detalleLink' => '', 'posicion' => '');

            $respuesta->documentoIdRecep = $documentoId;
            $respuesta->documentoARelacionarPercepcion = $documentoARelacionarRecep;
        } else {
            $respuesta = null;
        }
        return $respuesta;
    }

    public function validarDocumentoARelacionar($opcionId, $documentoTipoId, $documentoARelacionar, $valorCheck)
    {

        //Validación de relaciones de DUA con GR y con OC   - GR: GUIA DE REMISION BH     
        $dataDocumentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXId($documentoTipoId);
        if ($dataDocumentoTipo[0]['identificador_negocio'] == 21) {
            //ES DUA
            if (ObjectUtil::isEmpty($documentoARelacionar)) {
                throw new WarningException("Debe relacionar una orden de compra para poder guardar");
            } else {
                $copiaGuiaRec = false;
                foreach ($documentoARelacionar as $item) {
                    if (!ObjectUtil::isEmpty($item['documentoId'])) {
                        //buscando orden de compra para ver sus relaciones.
                        $dataDocCopia = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($item['documentoId']);
                        $dataDocTipoCopia = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXId($dataDocCopia[0]['documento_tipo_id']);

                        if ($dataDocTipoCopia[0]['identificador_negocio'] == 10) { //ES ORDEN DE COMPRA
                            //OBTENEMOS LAS RELACIONES DE O.C.
                            //                            $dataRelaciones=  DocumentoNegocio::create()->obtenerDocumentosRelacionadosXDocumentoId($item['documentoId']);
                            $dataRelaciones = DocumentoNegocio::create()->obtenerDocumentoRelacionadoImpresion($item['documentoId']);
                            foreach ($dataRelaciones as $itemRel) {
                                //VALIDAMOS SI ES UNA GUIA DE REMISION  o GUIA DE RECEPCION (SE QUITO LA GR DE ENTRADA)
                                if ($itemRel['identificador_negocio'] == 6 || $itemRel['identificador_negocio'] == 22) {
                                    $copiaGuiaRec = true;
                                }
                            }
                        }
                    }
                }

                if (!$copiaGuiaRec) {
                    throw new WarningException("Debe relacionar una guia de recepción con la orden de compra para poder guardar");
                }
            }
        }

        //SI ES RECEPCION DE TRANSFERENCIA - DT: RECEPCION
        if ($dataDocumentoTipo[0]['identificador_negocio'] == 9) {
            if (ObjectUtil::isEmpty($documentoARelacionar)) {
                throw new WarningException("Debe relacionar la Guía de remisión para poder guardar");
            } else {
                $copiaGuiaRem = false;
                foreach ($documentoARelacionar as $item) {
                    if (!ObjectUtil::isEmpty($item['documentoId'])) {
                        //buscando guia de remision en las copias.
                        $dataDocCopia = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($item['documentoId']);

                        if ($dataDocCopia[0]['identificador_negocio'] == 6) { //ES GUIA DE REMISION
                            $copiaGuiaRem = true;
                        }
                    }
                }

                if (!$copiaGuiaRem) {
                    throw new WarningException("Debe relacionar la Guía de remisión para poder guardar");
                }
            }
        }

        //        throw new WarningException("ERROR...PASO TODO BIEN");
    }

    public function validarDetalleContenidoEnDocumentoRelacion($opcionId, $documentoTipoId, $documentoARelacionar, $valorCheck, $detalle)
    {
        $bandera = false; //NO HAY ERRORES

        $dataDocumentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXId($documentoTipoId);
        if ($dataDocumentoTipo[0]['identificador_negocio'] == 23) {
            //ES GUIA INTERNA DE TRASLADO EN UN SOLO PASO
            if (!ObjectUtil::isEmpty($documentoARelacionar)) {
                $movimientoIds = '0';
                $contador = 0;
                foreach ($documentoARelacionar as $item) {
                    //                    $item['tipo']==1; ES EL PADRE DE LOS DOCUMENTOS RELACIONADO
                    if (!ObjectUtil::isEmpty($item['documentoId']) && $item['tipo'] == 1) {
                        $movimientoIds = $movimientoIds . ',' . $item['movimientoId'];
                        $contador++;
                    }
                }
                //obtenemos la data del detalle de la copia
                $bandera = false; //NO HAY ERRORES
                $df = $detalle;
                //                $dr = MovimientoBien::create()->obtenerXIdMovimiento($item['movimientoId']);
                $dr = MovimientoBien::create()->obtenerXMovimientoIds($movimientoIds);

                foreach ($df as $i => $itemDoc) {
                    $bandera2 = false; //HAY ERROR
                    foreach ($dr as $j => $itemRel) {
                        if ($itemDoc['bienId'] == $itemRel['bien_id'] && $itemDoc['cantidad'] * 1 <= $itemRel['cantidad'] * 1 && $itemDoc['unidadMedidaId'] == $itemRel['unidad_medida_id']) {
                            $bandera2 = true; //CORRECTO
                            break;
                        }
                    }
                    if (!$bandera2) { //SI HAY ERROR
                        $bandera = true; //ERROR
                        break;
                    }
                }
            }
        }

        if ($bandera) {
            $mensaje = "El detalle del formulario debe estar contenido en el detalle del documento relacionado";
            if ($contador > 1) {
                $mensaje = "El detalle del formulario debe estar contenido en el detalle de los documentos relacionados";
            }
            throw new WarningException($mensaje);
        }

        //        throw new WarningException("TODO BIEN");
    }

    public function guardarXAccionEnvio($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $documentoARelacionar, $valorCheck, $comentario, $checkIgv, $monedaId, $accionEnvio, $tipoPago, $listaPagoProgramacion, $atiende = null, $periodoId = null, $percepcion = null, $contOperacionTipoId = null, $afectoAImpuesto = null, $datosExtras = null)
    {
        //VALIDAR RELACIONES DE DOCUMENTOS
        $res = MovimientoNegocio::create()->validarDocumentoARelacionar($opcionId, $documentoTipoId, $documentoARelacionar, $valorCheck);

        //VALIDAR DETALLE DE DOCUMENTOS CON LA COPIA
        $resValDet = MovimientoNegocio::create()->validarDetalleContenidoEnDocumentoRelacion($opcionId, $documentoTipoId, $documentoARelacionar, $valorCheck, $detalle);

        $puedeGuardar = false;
        $obligatorio = $this->verificarDocumentoEsObligatorioXOpcionID($opcionId);

        if ($obligatorio[0]['movimiento_tipo_anterior_relacion'] == 1) {
            if (!ObjectUtil::isEmpty($documentoARelacionar)) {
                $puedeGuardar = true;
            } else {
                $puedeGuardar = false;
                throw new WarningException("Se requiere una " . $obligatorio[0]['anterior_descripcion'] . ", copie alguna.");
            }
        } else {
            $puedeGuardar = true;
        }

        if ($puedeGuardar) {
            //REGISTRAR LA RECEPCION DE TRANSFERENCIA DE UN SOLO PASO
            $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);
            if ($movimientoTipo[0]["transferencia_tipo"] == MovimientoTipoNegocio::TRANSFERENCIA_TIPO_SALIDA && $movimientoTipo[0]["codigo"] == Configuraciones::MOVIMIENTO_TIPO_CODIGO_TRANSFERENCIA) {
                $dataRecepcion = $this->guardarDocumentoRecepcion($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $documentoARelacionar, $valorCheck, $comentario, $checkIgv, $monedaId, $tipoPago, $periodoId);

                if (ObjectUtil::isEmpty($documentoARelacionar)) {
                    $documentoARelacionar = array();
                    $valorCheck = 1;
                }
                array_push($documentoARelacionar, $dataRecepcion->documentoARelacionarRecep);
            }
            //FIN REGISTRAR RECEPCION
            //----------------- GUARDAR DOCUMENTO DE PERCEPCION -------------------
            $dataPercepcion = $this->guardarDocumentoPercepcion($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $documentoARelacionar, $valorCheck, $comentario, $checkIgv, $monedaId, $tipoPago, $percepcion, $periodoId);
            if (!ObjectUtil::isEmpty($dataPercepcion)) {
                if (ObjectUtil::isEmpty($documentoARelacionar)) {
                    $documentoARelacionar = array();
                    $valorCheck = 1;
                }
                array_push($documentoARelacionar, $dataPercepcion->documentoARelacionarPercepcion);
            }
            //----------------- FIN GUARDAR PERCEPCION ----------------------------
            //Guardar documento
            if (
                $documentoTipoId == ContDistribucionContableNegocio::DOCUMENTO_TIPO_ID_FACTURA_VENTA ||
                $documentoTipoId == ContDistribucionContableNegocio::DOCUMENTO_TIPO_ID_BOLETA_VENTA ||
                $documentoTipoId == ContDistribucionContableNegocio::DOCUMENTO_TIPO_ID_NOTA_CREDITO_VENTA ||
                $documentoTipoId == ContDistribucionContableNegocio::DOCUMENTO_TIPO_ID_NOTA_DEBITO_VENTA
            ) {

                $contOperacionTipoId = ContVoucherNegocio::OPERACION_TIPO_ID_VENTAS;
            }

            $documentoId = $this->guardar($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $documentoARelacionar, $valorCheck, $comentario, $checkIgv, $monedaId, $tipoPago, $periodoId, $contOperacionTipoId, $afectoAImpuesto, $datosExtras);

            if (!ObjectUtil::isEmpty($atiende)) {
                if ($atiende == false) {
                    //---GUARDAR VALORES AUTOMATICOS
                    $relacionadosDocumentoActual = DocumentoNegocio::create()->obtenerDocumentosRelacionados($documentoId);

                    foreach ($relacionadosDocumentoActual as $item) {

                        $docRelacionadoId = $item['documento_relacionado_id'];

                        $movimientoIdActual = MovimientoBien::create()->obtenerMovimientoIdXDocumentoId($documentoId);
                        $dataMovBien = MovimientoBien::create()->obtenerXIdMovimiento($movimientoIdActual[0]['movimiento_id']);
                        $bienesIdRelacionados = MovimientoBien::create()->obtenerBienesIdRelacionadosXDocumentoId($docRelacionadoId);
                        foreach ($dataMovBien as $dataMovBienActual) {
                            foreach ($bienesIdRelacionados as $bienesIdRelacionado) {

                                if ($dataMovBienActual['bien_id'] == $bienesIdRelacionado['bien_id']) {

                                    $movimiento_bien_anterior = $bienesIdRelacionado['movimiento_bien_anterior'];
                                    $movimiento_bien_destino = $dataMovBienActual['movimiento_bien_id'];
                                    $cantidad = $bienesIdRelacionado['cantidad_solicitada'];
                                    MovimientoBien::create()->guardarDocumentoAtencionSolicitud($movimiento_bien_anterior, $movimiento_bien_destino, $cantidad, $usuarioId);
                                }
                            }
                        }
                    }
                } else {
                    //Guardar asignaciones que hace el usuario

                    $relacionadosDocumentoActual = DocumentoNegocio::create()->obtenerDocumentosRelacionados($documentoId);
                    $detallesMovimientoBien = $camposDinamicos['atencionesRef'];
                    //foreach ($relacionadosDocumentoActual as $item) {
                    //                        $docRelacionadoId = $item['documento_relacionado_id'];

                    $movimientoIdActual = MovimientoBien::create()->obtenerMovimientoIdXDocumentoId($documentoId);
                    $dataMovBien = MovimientoBien::create()->obtenerXIdMovimiento($movimientoIdActual[0]['movimiento_id']);
                    $guardadoNormal = array();
                    $guardadoNormal = $dataMovBien;
                    foreach ($detallesMovimientoBien as $detalleMB) {
                        foreach ($dataMovBien as $indiceMB => $dataMovBienActual) {
                            if ($dataMovBienActual['bien_id'] == $detalleMB[0]) {
                                //                                   array_splice($guardadoNormal, $indiceMB,1,null);
                                unset($guardadoNormal[$indiceMB]);
                                //                                    $guardadoNormal = $dataMovBien;
                            } else {
                                //array_push($guardadoNormal, $dataMovBienActual);
                            }
                        }
                    }


                    if (!ObjectUtil::isEmpty($guardadoNormal)) {
                        $this->guardarNormalmente($documentoId, $guardadoNormal, $usuarioId);
                    }

                    foreach ($dataMovBien as $dataMovBienActual) {
                        foreach ($detallesMovimientoBien as $detalleMB) {
                            if ($dataMovBienActual['bien_id'] == $detalleMB[0]) {
                                $detalleArray = $detalleMB[1];
                                $length = count($detalleMB[1]);
                                for ($x = 0; $x < $length; $x++) {
                                    $movimiento_bien_anterior = $detalleMB[1][$x]['mov_bien_ant_id'];
                                    $movimiento_bien_destino = $dataMovBienActual['movimiento_bien_id'];
                                    $cantidad = $detalleMB[1][$x]['cantidad'];
                                    MovimientoBien::create()->guardarDocumentoAtencionSolicitud($movimiento_bien_anterior, $movimiento_bien_destino, $cantidad, $usuarioId);
                                }
                            }
                        }
                    }
                    //}
                }
            }
            $respuesta = new stdClass();

            if (!ObjectUtil::isEmpty($listaPagoProgramacion)) {
                foreach ($listaPagoProgramacion as $ind => $item) {
                    //listaPagoProgramacion.push([ fechaPago, importePago, dias, porcentaje,glosa,pagoProgramacionId]);
                    $fechaPago = DateUtil::formatearCadenaACadenaBD($item[0]);
                    $importePago = $item[1];
                    $dias = $item[2];
                    $porcentaje = $item[3];
                    $glosa = $item[4];

                    $res = Pago::create()->guardarPagoProgramacion($documentoId, $fechaPago, $importePago, $dias, $porcentaje, $glosa, $usuarioId);
                }
            }

            if ($accionEnvio == 'guardar') {
                $respuesta->documentoId = $documentoId;
                return $respuesta;
            }

            if ($accionEnvio == 'confirmar') {
                DocumentoNegocio::create()->insertarDocumentoDocumentoEstado($documentoId, 13, $usuarioId);
                $respuesta->documentoId = $documentoId;
                return $respuesta;
            }

            if ($accionEnvio == 'enviar') {
                $respuesta->documentoId = $documentoId;
                return $respuesta;
            }

            if ($accionEnvio == 'enviarEImprimir') {
                $respuesta->dataImprimir = $this->imprimirExportarPDFDocumento($documentoTipoId, $documentoId, $usuarioId);
                $respuesta->documentoId = $documentoId;
                return $respuesta;
            } else {
                //obtener email de plantilla            
                $movimientoTipoId = $this->obtenerIdXOpcion($opcionId);
                $plantilla = EmailPlantillaNegocio::create()->obtenerPlantillaDestinatarioXAccionDescripcionXMovimientoTipoId($accionEnvio, $movimientoTipoId);
                $dataPersona = DocumentoNegocio::create()->obtenerPersonaXDocumentoId($documentoId);

                $correosPlantilla = EmailPlantillaNegocio::create()->obtenerEmailXDestinatario($plantilla[0]["destinatario"], $documentoId, $dataPersona[0]['id']);

                //validar si se muestra el modal de confirmacion de emails.
                if ($plantilla[0]["confirmacion"] == 1) {
                    $respuesta->dataPlantilla = $plantilla;
                    $respuesta->dataCorreos = $correosPlantilla;
                    $respuesta->documentoId = $documentoId;
                    return $respuesta;
                }

                if (ObjectUtil::isEmpty($correosPlantilla)) {
                    $this->setMensajeEmergente("Email en blanco, no se pudo enviar correo.", null, Configuraciones::MENSAJE_WARNING);
                    $respuesta->documentoId = $documentoId;
                    return $respuesta;
                }

                $correos = '';
                foreach ($correosPlantilla as $email) {
                    $correos = $correos . $email . ';';
                }

                $plantillaId = $plantilla[0]["email_plantilla_id"];
                $respuesta->dataEnvioCorreo = $this->enviarCorreoXAccion($correos, $comentario, $accionEnvio, $documentoId, $plantillaId, $usuarioId);
                $respuesta->documentoId = $documentoId;
                return $respuesta;
            }
        }
    }

    public function guardarDocumentoRecepcion($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $documentoARelacionar, $valorCheck, $comentario, $checkIgv, $monedaId, $tipoPago, $periodoId)
    {
        $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);

        $empresaDestinoId = $movimientoTipo[0]["empresa_id"];
        //OBTENIENDO ORGANIZADOR DESTINO        
        foreach ($camposDinamicos as $indexCampos => $valorDtd) {
            if ($valorDtd["tipo"] == 17) {
                $organanizadorDestinoId = $valorDtd["valor"];
            }
        }
        //OBTENIENDO EMPRESA DESTINO SEGUN ORGANIZADOR
        $dataEmpresa = OrganizadorNegocio::create()->obtenerEmpresaXOrganizadorId($organanizadorDestinoId);
        if (!ObjectUtil::isEmpty($dataEmpresa)) {
            $empresaDestinoId = $dataEmpresa[0]['empresa_id'];
        }

        $res = Movimiento::create()->obtenerMovimientoTipoRecepcionXEmpresaIdXCodigo($empresaDestinoId, Configuraciones::MOVIMIENTO_TIPO_CODIGO_RECEPCION);

        $opcionIdR = $res[0]['opcion_id'];
        $documentoTipoIdR = $res[0]['documento_tipo_id'];

        //cabecera del documento
        $camposDinamicosGuia = $camposDinamicos;
        $configuraciones = DocumentoTipoNegocio::create()->obtenerDocumentoTipoDatoSimple($documentoTipoIdR);

        foreach ($configuraciones as $indexConfig => $itemDtd) {
            foreach ($camposDinamicosGuia as $indexCampos => $valorDtd) {
                if ((int) $itemDtd["tipo"] === (int) $valorDtd["tipo"]) {
                    $camposDinamicosGuia[$indexCampos]["id"] = $itemDtd["id"];
                    if ($itemDtd["tipo"] == 8) {
                        $camposDinamicosGuia[$indexCampos]["valor"] = DocumentoNegocio::create()->obtenerNumeroAutoIncrementalXDocumentoTipo($documentoTipoIdR);
                    }
                }
            }
        }

        foreach ($detalle as $indexDet => $item) {
            $detalle[$indexDet]['organizadorId'] = $organanizadorDestinoId;
        }

        $documentoId = $this->guardar($opcionIdR, $usuarioId, $documentoTipoIdR, $camposDinamicosGuia, $detalle, null, 1, $comentario, $checkIgv, $monedaId, $tipoPago, $periodoId);
        $documentoARelacionarRecep = array('documentoId' => $documentoId, 'movimientoId' => '', 'detalleLink' => '', 'posicion' => '');

        $respuesta->documentoIdRecep = $documentoId;
        $respuesta->documentoARelacionarRecep = $documentoARelacionarRecep;

        return $respuesta;
    }

    public function imprimirExportarPDFDocumento($documentoTipoId, $documentoId, $usuarioId)
    {
        $dataDocumentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXId($documentoTipoId);
        $indicadorImprimir = $dataDocumentoTipo[0]['indicador_imprimir'] == 1;
        if ($indicadorImprimir == 1) { //genera pdf            
            $hoy = date("Y_m_d_H_i_s");
            $pdf = 'documento_' . $hoy . '_' . $usuarioId . '.pdf';
            $url = __DIR__ . '/../../vistas/com/movimiento/documentos/' . $pdf;
            $data = MovimientoNegocio::create()->imprimir($documentoId, $documentoTipoId);

            $nombre = MovimientoNegocio::create()->generarDocumentoPDF($documentoId, '', 'F', $url, $data);

            $respuesta->url = $url;
            $respuesta->nombre = $nombre;
            $respuesta->pdf = $pdf;
            return $respuesta;
        } else {
            //VALIDAMOS QUE TENGA PDF LA FACTURA ELECTRONICA
            $resDocElectronico = null;
            if ( // $dataDocumentoTipo[0]['identificador_negocio'] == DocumentoTipoNegocio::IN_FACTURA_VENTA ||
                //    $dataDocumentoTipo[0]['identificador_negocio'] == DocumentoTipoNegocio::IN_BOLETA_VENTA ||
                $dataDocumentoTipo[0]['identificador_negocio'] == DocumentoTipoNegocio::IN_NOTA_CREDITO_VENTA ||
                $dataDocumentoTipo[0]['identificador_negocio'] == DocumentoTipoNegocio::IN_NOTA_DEBITO_VENTA
            ) {

                $resDocElectronico = $this->imprimirDocumentoElectronico($documentoId);
            }

            if (!ObjectUtil::isEmpty($resDocElectronico) && !ObjectUtil::isEmpty($resDocElectronico->urlPDF)) {
                $respuesta->url = $resDocElectronico->urlPDF;
                $respuesta->nombre = $resDocElectronico->nombrePDF;
                $respuesta->contenedor = $resDocElectronico->contenedor;
                $respuesta->pdfSunat = 1;
                $respuesta->descargar = 0;
                return $respuesta;
            } else {
                //SI ES BOLETA o NOTA DE CREDITO EXPORTAMOS EN PDF
                if ($dataDocumentoTipo[0]['identificador_negocio'] == 5) {
                    $hoy = date("Y_m_d_H_i_s");
                    $pdf = 'documento_' . $hoy . '_' . $usuarioId . '.pdf';
                    $url = __DIR__ . '/../../reporteJasper/documentos/' . $pdf;
                    $data = MovimientoNegocio::create()->imprimir($documentoId, $documentoTipoId);

                    $nombre = MovimientoNegocio::create()->generarDocumentoImpresionPDF($documentoId, $url, $data);

                    $respuesta->url = $url;
                    $respuesta->nombre = $nombre;
                    $respuesta->pdf = $pdf;
                    $respuesta->iReport = 1;
                } else {
                    return MovimientoNegocio::create()->imprimir($documentoId, $documentoTipoId);
                }
            }
        }
    }

    public function imprimirDocumentoElectronico($documentoId)
    {
        $documento = DocumentoNegocio::create()->obtenerDocumentoFEXId($documentoId);

        if (strtotime($documento[0]['fecha_creacion']) < strtotime('2020-06-11')) {
            $url = Configuraciones::EFACT_CONTENEDOR_PDF_OLD;
        } else {
            $url = Configuraciones::EFACT_CONTENEDOR_PDF;
        }

        if (!ObjectUtil::isEmpty($documento[0]['efact_pdf_nombre'])) {
            $urlPDF = $url . $documento[0]['efact_pdf_nombre'];
        } else {
            $urlPDF = null;
        }

        $respuesta = new ObjectUtil();
        $respuesta->urlPDF = $urlPDF;
        $respuesta->nombrePDF = $documento[0]['efact_pdf_nombre'];
        $respuesta->contenedor = $url;

        return $respuesta;
    }

    public function existeColumnaCodigo($dataColumna, $codigo)
    {
        if (!ObjectUtil::isEmpty($dataColumna)) {
            foreach ($dataColumna as $item) {
                if ($item['codigo'] == $codigo) {
                    return true;
                }
            }
        }

        return false;
    }

    public function guardar($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $documentoARelacionar, $valorCheck, $comentario = NULL, $checkIgv = 1, $monedaId = null, $tipoPago = null, $periodoId = null, $contOperacionTipoId = null, $afectoAImpuesto = null, $datosExtras = null)
    {

        $movimientoTipoId = $this->obtenerIdXOpcion($opcionId);
        $dataDocumentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXId($documentoTipoId);

        // 1. Insertamos el movimiento
        $movimiento = Movimiento::create()->guardar($movimientoTipoId, 1, $usuarioId);
        $movimientoId = $this->validateResponse($movimiento);
        if (ObjectUtil::isEmpty($movimientoId) || $movimientoId < 1) {
            throw new WarningException("No se pudo guardar el movimiento");
        }

        $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);

        if (ObjectUtil::isEmpty($monedaId)) {
            $monedaId = $movimientoTipo[0]["moneda_id"];
        }
        $identificadorNegocio = $dataDocumentoTipo[0]['identificador_negocio'];
        $empresaId = $movimientoTipo[0]['empresa_id'];

        $dataAperturaCaja = ACCajaNegocio::create()->obteneACCajaUltimoXUsuarioId($empresaId, $usuarioId);
        if (ObjectUtil::isEmpty($dataAperturaCaja)) {
            throw new WarningException("No existe una caja aperturada para el usuario en sesión.");
        }
        $acCajaId = $dataAperturaCaja[0]['ac_caja_id'];
        $agenciaIdConsulta = $dataAperturaCaja[0]['agencia_id'];

        $datosExtras['agencia_id'] = $agenciaIdConsulta;

        // 2. Insertamos el documento
        $documento = DocumentoNegocio::create()->guardar($documentoTipoId, $movimientoId, null, $camposDinamicos, 1, $usuarioId, $monedaId, $comentario, null, $detalle[0]['utilidadTotal'], $detalle[0]['utilidadPorcentajeTotal'], $tipoPago, $periodoId, null, $contOperacionTipoId, $afectoAImpuesto, $datosExtras, $acCajaId);

        $documentoId = $this->validateResponse($documento);
        if (ObjectUtil::isEmpty($documentoId) || $documentoId < 1) {
            throw new WarningException("No se pudo guardar el documento");
        }

        // 3. Insertamos el detalle
        foreach ($detalle as $item) {
            // validaciones
            $item['unidadMedidaId'] = -1;
            if ($item["bienId"] == NULL) {
                throw new WarningException("No se especificó un valor válido para un producto. ");
            }
            if ($item["unidadMedidaId"] == NULL) {
                throw new WarningException("No se especificó un valor válido para Unidad Medida. ");
            }
            if ($item["cantidad"] == NULL or $item["cantidad"] <= 0) {
                throw new WarningException("No se especificó un valor válido para Cantidad. ");
            }

            //obtengo la fecha de emision
            $fechaEmision = null;
            $organizadorDestinoId = null;
            foreach ($camposDinamicos as $valorCampo) {
                if ($valorCampo["tipo"] == 9) {
                    $fechaEmision = DateUtil::formatearCadenaACadenaBD($valorCampo["valor"]);
                }
                if ($valorCampo["tipo"] == 17) { //ALMACEN DE LLEGADA
                    $organizadorDestinoId = $valorCampo["valor"];
                }
            }

            MovimientoNegocio::create()->obtenerStockAControlar($opcionId, $item["bienId"], $item["organizadorId"], $item["unidadMedidaId"], $item["cantidad"], $fechaEmision, $organizadorDestinoId);

            //validacion el precio unitario tiene que ser mayor al precio de compra.
            $precioCompra = 0;
            $validarPrecios = false;
            if ($item["precio"] * 1 == 0) {
                $validarPrecios = false;
            }

            if (!ObjectUtil::isEmpty($item["precioCompra"])) {
                $precioCompra = $item["precioCompra"];
            }
            if ($dataDocumentoTipo[0]["validacion"] == 1 && $validarPrecios) {
                $precioUnitario = $item["precio"];
                //                $precioCompra=$item["precioCompra"];

                if ($precioUnitario <= $precioCompra) {
                    throw new WarningException("No se pudo guardar un detalle del movimiento, precio unitario tiene que ser mayor al precio de compra."
                        . "<br> Producto: " . $item["bienDesc"]
                        . "<br> Precio compra: " . $precioCompra);
                }
            }

            //validacion: el precio minimo (descuento) no tiene que ser menor al precio unitaio
            //            if($movimientoTipo[0]["indicador"]==MovimientoTipoNegocio::INDICADOR_SALIDA){
            if ($dataDocumentoTipo[0]["tipo"] == 1 && $validarPrecios) {
                $precioUnitario = $item["precio"];

                //calculo de precio minimo (descuento)
                //                $precioCompra=$item["precioCompra"];

                $dataPrecio = BienPrecioNegocio::create()->obtenerBienPrecioXBienIdXUnidadMedidaIdXPrecioTipoIdXMonedaId($item["bienId"], $item["unidadMedidaId"], $item["precioTipoId"], $monedaId);
                if (!ObjectUtil::isEmpty($dataPrecio)) {
                    if ($checkIgv == 1) {
                        $precioVenta = $dataPrecio[0]["incluye_igv"];
                    } else {
                        $precioVenta = $dataPrecio[0]["precio"];
                    }
                    $cantidad = $item["cantidad"];
                    $utilidadSoles = ($precioVenta - $precioCompra) * $cantidad;
                    $subTotal = $precioVenta * $cantidad;
                    $utilidadPorcentaje = 0;
                    if ($subTotal != 0) {
                        $utilidadPorcentaje = ($utilidadSoles / $subTotal) * 100;
                    }

                    $descuentoPorcentaje = ($dataPrecio[0]["descuento"] / 100) * ($utilidadPorcentaje);
                    $precioMinimo = $precioVenta - ($descuentoPorcentaje / 100) * $precioVenta;
                    $precioMinimo = round($precioMinimo, 2);  // 1.96

                    if ($precioUnitario < $precioMinimo) {
                        throw new WarningException("No se pudo guardar un detalle del movimiento, precio unitario tiene que ser mayor o igual al precio mínimo (descuento)"
                            . "<br> Producto: " . $item["bienDesc"]
                            . "<br> Precio mínimo: " . $precioMinimo);
                    }
                }
            }

            //fin validaciones
            if (ObjectUtil::isEmpty($item["adValorem"])) {
                $item["adValorem"] = 0;
            }
            foreach ($camposDinamicos as $campoDinam) {
                if ($campoDinam['tipo'] == 4) {
                    $idValor = $campoDinam['valor'];
                    $resDTDL = DocumentoTipoDatoLista::create()->obtenerPorId($idValor);
                    if (!ObjectUtil::isEmpty($resDTDL) && $resDTDL[0]['valor'] == 13) {
                        $notaCreditoTipo13 = 1;
                    }
                }
            }
            $itemPrecio = $item["precio"];
            if ($notaCreditoTipo13 == 1) {
                $itemPrecio = 0.0;
            }
            $movimientoBien = MovimientoBien::create()->guardar(
                $movimientoId,
                $item["organizadorId"],
                $item["bienId"],
                $item["unidadMedidaId"],
                $item["cantidad"],
                $itemPrecio,
                1,
                $usuarioId,
                $item["precioTipoId"],
                $item["utilidad"],
                $item["utilidadPorcentaje"],
                $checkIgv,
                $item["adValorem"],
                $item["comentarioDetalle"],
                $item["bienActivoFijoId"],
                NULL,
                NULL,
                NULL,
                $item['peso'],
                $item["tipo"],
                NULL,
                NULL,
                $item["movimientoBienPadreId"],
                $item["documentoPadreId"],
                $item["movimientoPadreId"]
            );
            $movimientoBienId = $this->validateResponse($movimientoBien);
            if (ObjectUtil::isEmpty($movimientoBienId) || $movimientoBienId < 1) {
                throw new WarningException("No se pudo guardar un detalle del movimiento");
            }

            //guardar el detalle del detalle del movimiento en movimiento_bien_detalle
            if (!ObjectUtil::isEmpty($item["detalle"])) {
                foreach ($item["detalle"] as $valor) {
                    if (!ObjectUtil::isEmpty($valor['valorDet'])) {
                        if ($valor['columnaCodigo'] == 16 || $valor['columnaCodigo'] == 17) {
                            $resDetalle = MovimientoNegocio::create()->movimientoBienDetalleGuardarCadena($movimientoBienId, $valor['columnaCodigo'], $valor['valorDet'], $usuarioId);
                        }

                        if ($valor['columnaCodigo'] == 18) {
                            $fechaVencimiento = DateUtil::formatearCadenaACadenaBD($valor['valorDet']);
                            $resDetalle = MovimientoNegocio::create()->movimientoBienDetalleGuardarFecha($movimientoBienId, $valor['columnaCodigo'], $fechaVencimiento, $usuarioId);
                        }
                    }
                }
            }
        }

        //si el documento se a copiado guardamos las relaciones
        foreach ($documentoARelacionar as $documentoRelacion) {

            if (!ObjectUtil::isEmpty($documentoRelacion['documentoId'])) {
                if ($documentoTipoId == 284) {
                    DocumentoNegocio::create()->guardarDocumentoRelacionado($documentoRelacion['documentoId'], $documentoId, $valorCheck, 1, $usuarioId, NULL, 11);
                } else if ($documentoTipoId == 61) {
                    DocumentoNegocio::create()->guardarDocumentoRelacionado($documentoId, $documentoRelacion['documentoId'], $valorCheck, 1, $usuarioId, NULL, 12);
                } else if ($documentoTipoId == 269) {
                    DocumentoNegocio::create()->guardarDocumentoRelacionado($documentoId, $documentoRelacion['documentoId'], $valorCheck, 1, $usuarioId, NULL, 13);
                } else {
                    DocumentoNegocio::create()->guardarDocumentoRelacionado($documentoId, $documentoRelacion['documentoId'], $valorCheck, 1, $usuarioId, NULL, $documentoRelacion['tipo']);
                }
            }
        }

        // logica de envio de correo de documento
        foreach ($camposDinamicos as $indexCampos => $valor) {
            if ($valor["tipo"] == 9) {
                $fechaEmision = DateUtil::formatearCadenaACadenaBD($valor["valor"]);
                $hoy = date("Y-m-d");

                if ($fechaEmision < $hoy) {
                    $this->enviarCorreoDocumentoConFechaEmisionAnterior($documentoId, $movimientoId, $usuarioId);
                }
            }
        }
        // fin envio de correo de documento
        //logica para tramos        
        foreach ($detalle as $item) {
            // validaciones
            if (!ObjectUtil::isEmpty($item["bienTramoId"])) {
                Movimiento::create()->actualizarBienTramoEstado($item["bienTramoId"], $movimientoId);
            }
        }

        if (!ObjectUtil::isEmpty($datosExtras['detallePenalidad'])) {
            foreach ($datosExtras['detallePenalidad'] as $index => $item) {
                $penalidadId = $item['id'];

                if (ObjectUtil::isEmpty($penalidadId)) {
                    $respuestaRegistrar = Penalidad::create()->registrar($empresaId, $item['descripcion'], $usuarioId);
                    $penalidadId = $this->validateResponse($respuestaRegistrar);
                }

                Penalidad::create()->registrarDocumentoPenalidad($documentoId, $penalidadId, $usuarioId, $item['monto']);
            }
        }

        if ($datosExtras['accion'] == 'confirmar' && $identificadorNegocio == DocumentoTipoNegocio::IN_LIQUIDACION_COBRANZA) {

            $dataDocumentoRegistrado = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);
            if (ObjectUtil::isEmpty($dataDocumentoRegistrado)) {
                throw new WarningException("No obtuvo la data del documento - liquidación. " . $documentoId);
            }
            if (!ObjectUtil::isEmpty($dataDocumentoRegistrado[0]['documento_cargo_id'])) {
                throw new WarningException("Este pedido ya tiene un documento cargo relacionado. " . $dataDocumentoRegistrado[0]['documento_cargo_serie'] . "-" . $dataDocumentoRegistrado[0]['documento_cargo_numero']);
            }

            if ($dataDocumentoRegistrado[0]['documento_estado_id'] != 8) {
                throw new WarningException("La liquidación debe estar en el estado pendiente de confirmación. ");
            }

            $fechaEmision = date('d/m/Y');
            $anio = date('Y');
            $mes = date('m');
            $empresaId = $dataDocumentoRegistrado[0]['empresa_id'];

            $dataPeriodo = Periodo::create()->obtenerPeriodoXEmpresaXAnioXMes($empresaId, $anio, $mes);

            if (ObjectUtil::isEmpty($dataPeriodo)) {
                throw new WarningException("No existe un periodo activo para la fecha " . $fechaEmision);
            }

            $documentoGenerar = array();
            $documentoGenerar['documentoTipoId'] = $dataDocumentoRegistrado[0]['comprobante_tipo_id'];
            $documentoGenerar['documentoOrigenId'] = $documentoId;
            $documentoGenerar['fechaEmision'] = $fechaEmision;
            $documentoGenerar['fechaVencimiento'] = $fechaEmision;
            $documentoGenerar['periodoId'] = $dataPeriodo[0]['id'];
            $documentoGenerar['empresaId'] = $dataDocumentoRegistrado[0]['empresa_id'];
            $documentoGenerar['personaId'] = $dataDocumentoRegistrado[0]['persona_id'];
            $documentoGenerar['personaDireccionId'] = $dataDocumentoRegistrado[0]['persona_direccion_id'];
            $documentoGenerar['clienteId'] = $dataDocumentoRegistrado[0]['persona_destinatario_id'];
            $documentoGenerar['clienteDireccionId'] = $dataDocumentoRegistrado[0]['persona_direccion_destino_id'];
            $documentoGenerar['destinatarioDireccionId'] = $dataDocumentoRegistrado[0]['persona_direccion_origen_id'];
            $documentoGenerar['clienteDireccionDescripcion'] = $dataDocumentoRegistrado[0]['persona_direccion_destino'];
            $documentoGenerar['destinatarioDireccionDescripcion'] = $dataDocumentoRegistrado[0]['persona_direccion_origen'];
            $documentoGenerar['agenciaOrigenId'] = $agenciaIdConsulta;
            $documentoGenerar['monedaId'] = $dataDocumentoRegistrado[0]['moneda_id'];
            $documentoGenerar['documentoEstadoId'] = 1; // REGISTRADO
            $documentoGenerar['tipoPago']  = 2; // FACTURA AL CREDITO

            $documentoGenerar['monto_total'] = $dataDocumentoRegistrado[0]['total'];
            $documentoGenerar['monto_igv'] = $dataDocumentoRegistrado[0]['igv'];
            $documentoGenerar['monto_subtotal'] = $dataDocumentoRegistrado[0]['subtotal'];

            $itemDetalle = array();
            $itemDetalle["bienId"] = -2;
            $itemDetalle["bienDesc"] = $dataDocumentoRegistrado[0]['comentario'];
            $itemDetalle["comentarioDetalle"] = $dataDocumentoRegistrado[0]['comentario'];
            $itemDetalle["unidadMedidaId"] = -1;
            $itemDetalle["precioTipoId"] = 2;
            $itemDetalle["cantidad"] = 1;
            $itemDetalle["precio"] = $dataDocumentoRegistrado[0]['total'];

            PedidoNegocio::create()->guardar($opcionId, $usuarioId, $documentoGenerar, array($itemDetalle), $datosExtras['dataPago'], TRUE);
        }
        // fin logica para tramos

        $this->setMensajeEmergente("La operación se completó de manera satisfactoria");

        if ($documentoTipoId == $this->dtDuaId || $documento[0]['compra_nacional_bienes'] == $this->dtCompraNacional) {
            $respuesta = MovimientoDuaNegocio::create()->generarPorDocumentoId($documentoId, $usuarioId);
        }
        return $documentoId;
    }

    public function movimientoBienDetalleGuardarCadena($movimientoBienId, $columnaCodigo, $valorCadena, $usuarioId)
    {
        return MovimientoBien::create()->movimientoBienDetalleGuardar($movimientoBienId, $columnaCodigo, $valorCadena, null, $usuarioId);
    }

    public function movimientoBienDetalleGuardarFecha($movimientoBienId, $columnaCodigo, $valorFecha, $usuarioId)
    {
        return MovimientoBien::create()->movimientoBienDetalleGuardar($movimientoBienId, $columnaCodigo, null, $valorFecha, $usuarioId);
    }

    public function obtenerDocumentosPedidosXCriterios($opcionId, $criterios, $elemntosFiltrados, $columns, $order, $start)
    {
        $movimientoTipoData = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);
        $numeroPedido = $criterios['numero_pedido'];
        $fechaPedido = $criterios['fecha_pedido'];
        $personaId = $criterios['persona_id'];
        $personaOrigenId = $criterios['persona_orgien_id'];
        $personaDestinoId = $criterios['persona_destino_id'];
        $documentoTipoComprobanteId = $criterios['documento_tipo_id'];
        $numeroComprobante = $criterios['numero_comprobante'];
        $fechaInicio = $criterios['fecha_inicio'];
        $fechaFin = $criterios['fecha_fin'];
        $documentoEstadoId = $criterios['documento_estado_id'];
        $agenciaOrigenId = $criterios['agencia_origen_id'];
        $agenciaDestinoId = $criterios['agencia_destino_id'];
        $usuarioId = $criterios['usuario_id'];

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        //        error_log("Estado " . $documentoEstadoId);

        if (!ObjectUtil::isEmpty($fechaInicio)) {
            $fechaInicio = DateUtil::formatearCadenaACadenaBD($fechaPedido);
        }

        if (!ObjectUtil::isEmpty($fechaInicio)) {
            $fechaInicio = DateUtil::formatearCadenaACadenaBD($fechaInicio);
        }


        if (!ObjectUtil::isEmpty($fechaFin)) {
            $fechaFin = DateUtil::formatearCadenaACadenaBD($fechaFin);
        }

        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];
        return Movimiento::create()->obtenerDocumentosPedidosXCriterios(
            $numeroPedido,
            $fechaPedido,
            $personaId,
            $documentoTipoComprobanteId,
            $numeroComprobante,
            $fechaInicio,
            $fechaFin,
            $documentoEstadoId,
            $agenciaOrigenId,
            $agenciaDestinoId,
            $usuarioId,
            $columnaOrdenar,
            $formaOrdenar,
            $elemntosFiltrados,
            $start,
            $movimientoTipoData[0]['id'],
            $personaOrigenId,
            $personaDestinoId
        );
    }

    public function obtenerDocumentosXCriterios($opcionId, $criterios, $elemntosFiltrados, $columns, $order, $start)
    {
        $personaId = null;
        $codigo = null;
        $serie = null;
        $numero = null;
        $fechaEmision = null;
        $fechaVencimiento = null;
        $fechaTentativa = null;
        $descripcion = null;
        $comentario = null;
        $documentoTipoArray = null;
        $documentoTipoIds = '';
        $columnaOrdenarIndice = '0';
        $columnaOrdenar = '';
        $formaOrdenar = '';

        //obtnemos el id del tipo de movimiento
        $responseMovimientoTipo = Movimiento::create()->ObtenerMovimientoTipoPorOpcion($opcionId);
        $movimientoTipoId = $responseMovimientoTipo[0]['id'];

        // 1. Obtenemos la configuracion actual del tipo de documento
        $documentoTipoArray = $criterios[0]['tipoDocumento'];

        // 2. Obtenemos la moneda
        $monedaId = $criterios[0]['monedaId'];

        // 3. Obtenemos el estado negocio de pago
        $estadoNegocioPago = $criterios[0]['estadoNegocio'];

        //        for ($i = 0; count($documentoTipoArray) > $i; $i++) {
        //            $documentoTipoIds = $documentoTipoIds . '(' . $documentoTipoArray[$i] . '),';
        //        }
        $documentoTipoIds = Util::convertirArrayXCadena($documentoTipoArray);
        //$documentoTipoIds = substr($documentoTipoIds, 0, -1);

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];

        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];

        foreach ($criterios as $item) {
            if ($item['valor'] != null || $item['valor'] != '') {
                $valor = $item['valor'];
                switch ((int) $item["tipo"]) {
                    case DocumentoTipoNegocio::DATO_CODIGO:
                        $codigo = $valor;
                        break;
                    case DocumentoTipoNegocio::DATO_PERSONA:
                        $personaId = $valor;
                        break;
                    case DocumentoTipoNegocio::DATO_SERIE:
                        $serie = $valor;
                        break;
                    case DocumentoTipoNegocio::DATO_NUMERO:
                        $numero = $valor;
                        break;
                    case DocumentoTipoNegocio::DATO_FECHA_EMISION:

                        //                        $valor_fecha_emision = split(" - ", $valor);
                        if ($valor['inicio'] != '') {
                            $fechaEmisionDesde = DateUtil::formatearCadenaACadenaBD($valor['inicio']);
                        }
                        if ($valor['fin'] != '') {
                            $fechaEmisionHasta = DateUtil::formatearCadenaACadenaBD($valor['fin']);
                        }
                        break;
                    case DocumentoTipoNegocio::DATO_FECHA_VENCIMIENTO:

                        if ($valor['inicio'] != '') {
                            $fechaVencimientoDesde = DateUtil::formatearCadenaACadenaBD($valor['inicio']);
                        }
                        if ($valor['fin'] != '') {
                            $fechaVencimientoHasta = DateUtil::formatearCadenaACadenaBD($valor['fin']);
                        }

                        break;
                    case DocumentoTipoNegocio::DATO_FECHA_TENTATIVA:
                        if ($valor['inicio'] != '') {
                            $fechaTentativaDesde = DateUtil::formatearCadenaACadenaBD($valor['inicio']);
                        }
                        if ($valor['fin'] != '') {
                            $fechaTentativaHasta = DateUtil::formatearCadenaACadenaBD($valor['fin']);
                        }
                        break;
                    default:
                }
            }
        }
        return Movimiento::create()->obtenerDocumentosXCriterios($movimientoTipoId, $documentoTipoIds, $personaId, $codigo, $serie, $numero, $fechaEmisionDesde, $fechaEmisionHasta, $fechaVencimientoDesde, $fechaVencimientoHasta, $fechaTentativaDesde, $fechaTentativaHasta, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start, $monedaId, $estadoNegocioPago);
    }

    public function obtenerDocumentosXCriteriosExcel($opcionId, $criterios)
    {
        $personaId = null;
        $codigo = null;
        $serie = null;
        $numero = null;
        $fechaEmision = null;
        $fechaVencimiento = null;
        $fechaTentativa = null;
        $descripcion = null;
        $comentario = null;
        $documentoTipoArray = null;
        $documentoTipoIds = '';
        $columnaOrdenarIndice = '0';
        $columnaOrdenar = '';
        $formaOrdenar = '';

        //obtnemos el id del tipo de movimiento
        $responseMovimientoTipo = Movimiento::create()->ObtenerMovimientoTipoPorOpcion($opcionId);
        $movimientoTipoId = $responseMovimientoTipo[0]['id'];

        // 1. Obtenemos la configuracion actual del tipo de documento
        $documentoTipoArray = $criterios[0]['tipoDocumento'];

        //        for ($i = 0; count($documentoTipoArray) > $i; $i++) {
        //            $documentoTipoIds = $documentoTipoIds . '(' . $documentoTipoArray[$i] . '),';
        //        }
        $documentoTipoIds = Util::convertirArrayXCadena($documentoTipoArray);
        //$documentoTipoIds = substr($documentoTipoIds, 0, -1);
        //$columnaOrdenarIndice = $order[0]['column'];
        //$formaOrdenar = $order[0]['dir'];
        //$columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];

        foreach ($criterios as $item) {
            if ($item['valor'] != null || $item['valor'] != '') {
                $valor = $item['valor'];
                switch ((int) $item["tipo"]) {
                    case DocumentoTipoNegocio::DATO_CODIGO:
                        $codigo = $valor;
                        break;
                    case DocumentoTipoNegocio::DATO_PERSONA:
                        $personaId = $valor;
                        break;
                    case DocumentoTipoNegocio::DATO_SERIE:
                        $serie = $valor;
                        break;
                    case DocumentoTipoNegocio::DATO_NUMERO:
                        $numero = $valor;
                        break;
                    case DocumentoTipoNegocio::DATO_FECHA_EMISION:

                        //                        $valor_fecha_emision = split(" - ", $valor);
                        if ($valor['inicio'] != '') {
                            $fechaEmisionDesde = DateUtil::formatearCadenaACadenaBD($valor['inicio']);
                        }
                        if ($valor['fin'] != '') {
                            $fechaEmisionHasta = DateUtil::formatearCadenaACadenaBD($valor['fin']);
                        }
                        break;
                    case DocumentoTipoNegocio::DATO_FECHA_VENCIMIENTO:

                        if ($valor['inicio'] != '') {
                            $fechaVencimientoDesde = DateUtil::formatearCadenaACadenaBD($valor['inicio']);
                        }
                        if ($valor['fin'] != '') {
                            $fechaVencimientoHasta = DateUtil::formatearCadenaACadenaBD($valor['fin']);
                        }

                        break;
                    case DocumentoTipoNegocio::DATO_FECHA_TENTATIVA:
                        if ($valor['inicio'] != '') {
                            $fechaTentativaDesde = DateUtil::formatearCadenaACadenaBD($valor['inicio']);
                        }
                        if ($valor['fin'] != '') {
                            $fechaTentativaHasta = DateUtil::formatearCadenaACadenaBD($valor['fin']);
                        }
                        break;
                    default:
                }
            }
        }
        return Movimiento::create()->obtenerDocumentosXCriteriosExcel($movimientoTipoId, $documentoTipoIds, $personaId, $codigo, $serie, $numero, $fechaEmisionDesde, $fechaEmisionHasta, $fechaVencimientoDesde, $fechaVencimientoHasta, $fechaTentativaDesde, $fechaTentativaHasta);
    }

    public function ObtenerTotalDeRegistros()
    {
        return Movimiento::create()->ObtenerTotalDeRegistros();
    }

    public function obtenerCantidadDocumentosXCriterio($opcionId, $criterios, $elemntosFiltrados, $columns, $order, $start)
    {
        $personaId = null;
        $codigo = null;
        $serie = null;
        $numero = null;
        $fechaEmision = null;
        $fechaVencimiento = null;
        $fechaTentativa = null;
        $descripcion = null;
        $comentario = null;
        $documentoTipoArray = null;
        $documentoTipoIds = '';
        $columnaOrdenarIndice = '0';
        $columnaOrdenar = '';
        $formaOrdenar = '';

        //obtnemos el id del tipo de movimiento
        $responseMovimientoTipo = Movimiento::create()->ObtenerMovimientoTipoPorOpcion($opcionId);
        $movimientoTipoId = $responseMovimientoTipo[0]['id'];

        // 1. Obtenemos la configuracion actual del tipo de documento
        $documentoTipoArray = $criterios[0]['tipoDocumento'];
        // 2. Obtenemos la moneda
        $monedaId = $criterios[0]['monedaId'];
        // 3. Obtenemos el estado negocio de pago
        $estadoNegocioPago = $criterios[0]['estadoNegocio'];

        //        for ($i = 0; count($documentoTipoArray) > $i; $i++) {
        //            $documentoTipoIds = $documentoTipoIds . '(' . $documentoTipoArray[$i] . '),';
        //        }
        $documentoTipoIds = Util::convertirArrayXCadena($documentoTipoArray);
        //$documentoTipoIds = substr($documentoTipoIds, 0, -1);

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];

        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];

        foreach ($criterios as $item) {
            if ($item['valor'] != null || $item['valor'] != '') {
                $valor = $item['valor'];
                switch ((int) $item["tipo"]) {
                    case DocumentoTipoNegocio::DATO_CODIGO:
                        $codigo = $valor;
                        break;
                    case DocumentoTipoNegocio::DATO_PERSONA:
                        $personaId = $valor;
                        break;
                    case DocumentoTipoNegocio::DATO_SERIE:
                        $serie = $valor;
                        break;
                    case DocumentoTipoNegocio::DATO_NUMERO:
                        $numero = $valor;
                        break;
                    case DocumentoTipoNegocio::DATO_FECHA_EMISION:

                        //                        $valor_fecha_emision = split(" - ", $valor);
                        if ($valor['inicio'] != '') {
                            $fechaEmisionDesde = DateUtil::formatearCadenaACadenaBD($valor['inicio']);
                        }
                        if ($valor['fin'] != '') {
                            $fechaEmisionHasta = DateUtil::formatearCadenaACadenaBD($valor['fin']);
                        }
                        break;
                    case DocumentoTipoNegocio::DATO_FECHA_VENCIMIENTO:

                        if ($valor['inicio'] != '') {
                            $fechaVencimientoDesde = DateUtil::formatearCadenaACadenaBD($valor['inicio']);
                        }
                        if ($valor['fin'] != '') {
                            $fechaVencimientoHasta = DateUtil::formatearCadenaACadenaBD($valor['fin']);
                        }

                        break;
                    case DocumentoTipoNegocio::DATO_FECHA_TENTATIVA:
                        if ($valor['inicio'] != '') {
                            $fechaTentativaDesde = DateUtil::formatearCadenaACadenaBD($valor['inicio']);
                        }
                        if ($valor['fin'] != '') {
                            $fechaTentativaHasta = DateUtil::formatearCadenaACadenaBD($valor['fin']);
                        }
                        break;
                    default:
                }
            }
        }
        return Movimiento::create()->obtenerCantidadDocumentosXCriterios($movimientoTipoId, $documentoTipoIds, $personaId, $codigo, $serie, $numero, $fechaEmisionDesde, $fechaEmisionHasta, $fechaVencimientoDesde, $fechaVencimientoHasta, $fechaTentativaDesde, $fechaTentativaHasta, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start, $monedaId, $estadoNegocioPago);
    }

    public function obtenerMovimientoTipoAcciones($opcionId, $codigo = null)
    {
        $responseMovimientoTipo = Movimiento::create()->ObtenerMovimientoTipoPorOpcion($opcionId, $codigo);
        $movimientoTipoId = $responseMovimientoTipo[0]['id'];
        return Movimiento::create()->obtenerMovimientoTipoAcciones($movimientoTipoId);
    }

    public function obtenerMovimientoTipoAccioneXOpcionIdXTipo($opcionId, $usuarioId, $tipoAccion = 1)
    {
        return Movimiento::create()->obtenerMovimientoTipoAccioneXOpcionIdXTipo($opcionId, $usuarioId, $tipoAccion);
    }

    // obtener busqueda para pagos 


    public function enviarEImprimir($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $documentoARelacionar, $valorCheck, $comentario, $checkIgv, $monedaId)
    {

        //        $nombre_fichero = __DIR__ . '/../../vistas/com/movimiento/plantillas/' . $documentoTipoId . ".php";
        //
        //        if (!file_exists($nombre_fichero)) {
        //            throw new WarningException("No existe el archivo del documento para imprimir.");
        //        }

        $documentoId = $this->guardar($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $documentoARelacionar, $valorCheck, $comentario, $checkIgv, $monedaId);

        return $this->imprimir($documentoId, $documentoTipoId);
    }

    public function imprimir($documentoId, $documentoTipoId)
    {
        $igv = 18;
        $arrayDetalle = array();
        $respuesta = new ObjectUtil();

        $respuesta->documentoTipoId = $documentoTipoId;
        $datoDocumento = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);
        if (ObjectUtil::isEmpty($datoDocumento)) {
            throw new WarningException("No se encontró el documento");
        }

        $respuesta->dataDocumento = $datoDocumento;
        $movimientoId = $datoDocumento[0]["movimiento_id"];
        $documentoDetalle = MovimientoBien::create()->obtenerXIdMovimiento($movimientoId);
        $respuesta->dataDocumentoPenalidad = Penalidad::create()->obtenerPenalidadDocumentoXDocumentoId($documentoId);
        //SE NECESITA TAL Y COMO DEVUELVE DE LA BASE PARA iREPORT
        $respuesta->documentoDetalle = $documentoDetalle;

        $total = 0.00;
        foreach ($documentoDetalle as $detalle) {
            $subTotal = $detalle['cantidad'] * $detalle['valor_monetario'];
            array_push($arrayDetalle, $this->getDetalle("", $detalle['cantidad'], $detalle['bien_descripcion'], $detalle['valor_monetario'], $subTotal, $detalle['unidad_medida_descripcion'], $detalle['simbolo'], $detalle['bien_codigo'], $detalle['unidad_medida_id'], $detalle['bien_id'], $detalle['ad_valorem'], $detalle['movimiento_bien_comentario']));
            $total += $subTotal;
        }

        $respuesta->detalle = $arrayDetalle;
        $respuesta->valorIgv = $igv;
        $enLetra = new EnLetras();
        //        $respuesta->totalEnTexto = $enLetra->ValorEnLetras($datoDocumento[0]['total']);

        $respuesta->totalEnTexto = $enLetra->ValorEnLetras((float) $datoDocumento[0]['total'], $datoDocumento[0]['moneda_id']);

        //datos empresa
        $empresaId = $datoDocumento[0]["empresa_id"];
        $dataEmpresa = EmpresaNegocio::create()->obtenerEmpresaXId($empresaId);
        $respuesta->dataEmpresa = $dataEmpresa;

        // obtener documentos relacionados
        $respuesta->documentoRelacionado = DocumentoNegocio::create()->obtenerDocumentoRelacionadoImpresion($documentoId);
        return $respuesta;
    }

    public function anularDocumentoMensaje($documentoId, $motivoAnulacion, $documentoEstadoId, $usuarioId)
    {

        if (ObjectUtil::isEmpty($motivoAnulacion)) {
            throw new WarningException("Ingrese motivo de anulación.");
        }

        Documento::create()->actualizarMotivoAnulacionXDocumentoId($documentoId, $motivoAnulacion);

        $documento = DocumentoNegocio::create()->obtenerDocumentoFEXId($documentoId);
        $idNegocio = $documento[0]['identificador_negocio'];
        $serie = $documento[0]["serie"];
        $comprobantePagoId = $documento[0]["comprobante_id"];
        $guiaTransportistaId = $documento[0]["guia_transportista_id"];

        if ($idNegocio == DocumentoTipoNegocio::IN_PEDIDO || $idNegocio == DocumentoTipoNegocio::IN_LIQUIDACION_COBRANZA) {
            if (!ObjectUtil::isEmpty($comprobantePagoId)) {
                $this->anularDocumentoMensaje($comprobantePagoId, $motivoAnulacion, $documentoEstadoId, $usuarioId);
            }

            if (!ObjectUtil::isEmpty($guiaTransportistaId)) {
                $this->anularDocumentoMensaje($guiaTransportistaId, $motivoAnulacion, $documentoEstadoId, $usuarioId);
            }
        }

        return MovimientoNegocio::create()->anularDocumento($documentoId, $documentoEstadoId, $usuarioId, $idNegocio, $serie);
    }

    public function anularDocumentoMensajePedido($documentoId, $motivoAnulacion, $documentoEstadoId, $usuarioId)
    {

        if (ObjectUtil::isEmpty($motivoAnulacion)) {
            throw new WarningException("Ingrese motivo de anulación.");
        }

        Documento::create()->actualizarMotivoAnulacionXDocumentoId($documentoId, $motivoAnulacion);

        $documento = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);
        $idNegocio = '2';
        $serie = $documento[0]["serie"];

        return MovimientoNegocio::create()->anularPedido($documentoId, $documentoEstadoId, $usuarioId, $idNegocio, $serie);
    }

    public function anularDocumento($documentoId, $documentoEstadoId, $usuarioId, $idNegocio, $serie)
    {

        //ANULAR LA RECEPCION DE TRANSFERENCIA VIRTUAL O FISICO
        $res = MovimientoNegocio::create()->obtenerDocumentoRelacionadoTipoRecepcion($documentoId);

        if (!ObjectUtil::isEmpty($res)) {
            $res2 = MovimientoNegocio::create()->anular($res[0]['documento_relacionado_id'], $documentoEstadoId, $usuarioId, $idNegocio, $serie);
        }

        return MovimientoNegocio::create()->anular($documentoId, $documentoEstadoId, $usuarioId, $idNegocio, $serie);
    }

    public function anularPedido($documentoId, $documentoEstadoId, $usuarioId, $idNegocio, $serie)
    {

        //VALIDAMOS LA FECHA EN QUE SE ESTÁ HACIENDO LA ANULACIÓN
        $dataDocumento = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);
        $comprobanteId = $dataDocumento[0]['comprobante_id'];
        $fechaActual = new DateTime();
        $fechaDocumento = new DateTime($dataDocumento[0]['fecha_emision']);
        $diferencia = $fechaActual->diff($fechaDocumento);

        $relacionados = MovimientoNegocio::create()->obtenerDocumentosRelacionados($documentoId);

        $respuesta = new ObjectUtil();
        foreach ($relacionados as $index => $item) {

            if ($item['identificador_negocio'] == 3 || $item['identificador_negocio'] == 4) { //FACTURA O BOLETA
                if ($diferencia->d < 1) { //Si es en el mismo día
                    //ANULAR PAGOS
                    $documentosPago = DocumentoNegocio::create()->obtenerDocumentoPagoXDocumentoId($item['documento_relacionado_id']);
                    if (!ObjectUtil::isEmpty($documentosPago)) {
                        foreach ($documentosPago as $docPago) {
                            DocumentoNegocio::create()->anularDocumentoPago($docPago['documento_pago_id']);
                        }
                    }

                    MovimientoNegocio::create()->anular($item['documento_relacionado_id'], $documentoEstadoId, $usuarioId, $item['identificador_negocio'], $serie);
                } else {
                    $docAdicional = MovimientoNegocio::create()->generarNotaCreditoRelacionada($comprobanteId, $usuarioId);
                    $respuesta->notaCreditoId = $docAdicional->documentoId;
                }
            }

            if ($item['identificador_negocio'] == 43) { //NOTA DE VENTA
                MovimientoNegocio::create()->anular($item['documento_relacionado_id'], $documentoEstadoId, $usuarioId, '43', $item['serie_numero']);
            }

            if ($item['identificador_negocio'] == 6) { //GUIA DE REMISIÓN TRANSPORTISTA
                MovimientoNegocio::create()->anular($item['documento_relacionado_id'], $documentoEstadoId, $usuarioId, '6', $item['serie_numero']);
            }
        }

        $respuesta->anular = MovimientoNegocio::create()->anular($documentoId, $documentoEstadoId, $usuarioId, $idNegocio, $serie);
        return $respuesta;
    }

    public function anular($documentoId, $documentoEstadoId, $usuarioId, $idNegocio, $serie)
    {

        $dataMovimientoTipo = MovimientoTipoNegocio::create()->obtenerXDocumentoId($documentoId);

        if ($dataMovimientoTipo[0]["indicador"] == MovimientoTipoNegocio::INDICADOR_ENTRADA) {
            //validacion que al eliminar el documento no resulte negativo.
            $dataDocumento = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);

            $dataMovimientoBien = MovimientoBien::create()->obtenerXIdMovimiento($dataDocumento[0]['movimiento_id']);
            foreach ($dataMovimientoBien as $index => $item) {
                if ($item['bien_tipo_id'] != -1) {
                    //obtener las fechas posteriores de los documentos de salida
                    $dataFechas = DocumentoNegocio::create()->obtenerFechasPosterioresDocumentosSalidas(
                        $dataMovimientoTipo[0]['fecha_emision'],
                        $item['bien_id'],
                        $item['organizador_id']
                    );

                    if (!ObjectUtil::isEmpty($dataFechas)) {
                        $dataFechaInicial = DocumentoNegocio::create()->obtenerFechaPrimerDocumento();
                        $fechaInicial = $dataFechaInicial[0]['primera_fecha'];

                        foreach ($dataFechas as $itemFecha) {
                            $fechaFinal = $itemFecha['fecha_emision'];
                            //obtener stock
                            $stock = BienNegocio::create()->obtenerStockEntreFechasXBienIdXOrganizadorIdXUnidadMedidaId($item['bien_id'], $item['organizador_id'], $item['unidad_medida_id'], $fechaInicial, $fechaFinal);

                            $stockControlar = (!ObjectUtil::isEmpty($stock)) ? $stock[0]["stock"] : 0;

                            if ((floatval($stockControlar) - floatval($item['cantidad'])) < 0) {
                                throw new WarningException("No se puede eliminar el documento.<br>"
                                    . " Stock en fecha " . date_format((date_create($fechaFinal)), 'd/m/Y') . ": " . number_format($stockControlar, 2, ".", ",") . "<br>"
                                    . " Producto: " . $item['bien_descripcion'] . "<br>"
                                    . " Cantidad en documento: " . number_format($item['cantidad'], 2, ".", ","));
                            }
                        }
                    }
                }
            }
        }

        $respuestaAnular = DocumentoNegocio::create()->anular($documentoId);

        if ($respuestaAnular[0]['vout_exito'] == 1) {
            $this->setMensajeEmergente($respuestaAnular[0]['vout_mensaje']);

            $respuestaAnularDocumentoEstado = DocumentoNegocio::create()->ActualizarDocumentoEstadoId($documentoId, $documentoEstadoId, $usuarioId, 'AN', NULL);
            if ($respuestaAnularDocumentoEstado[0]['vout_exito'] != 1) {
                throw new WarningException("No se actualizó documento estado");
            }

            $dataEmpresa = EmpresaNegocio::create()->obtenerEmpresaXDocumentoId($documentoId);

            // actualizamos el estado de efact_estado_anulacion a 0 (pendiente)
            if (
                $dataEmpresa[0]['efactura'] == 1 &&
                ($idNegocio == DocumentoTipoNegocio::IN_BOLETA_VENTA ||
                    ($idNegocio == DocumentoTipoNegocio::IN_NOTA_CREDITO_VENTA && $serie[0] == 'B'))
            ) {
                Documento::create()->actualizarEfactEstadoAnulacionXDocumentoId($documentoId, 0);
            }

            if ($dataEmpresa[0]['efactura'] == 1 && ($idNegocio == DocumentoTipoNegocio::IN_FACTURA_VENTA ||
                ($idNegocio == DocumentoTipoNegocio::IN_NOTA_CREDITO_VENTA && $serie[0] == 'F'))) {
                $this->anularFacturaElectronica($documentoId);
            }
        } else {
            throw new WarningException($respuestaAnular[0]['vout_mensaje']);
        }
    }

    public function anularFacturaElectronica($documentoId)
    {
        // Obtenemos Datos
        $documento = DocumentoNegocio::create()->obtenerDocumentoFEXId($documentoId);

        if (ObjectUtil::isEmpty($documento)) {
            throw new WarningException("No se encontró el documento");
        }

        $idNegocio = $documento[0]['identificador_negocio'];

        if (
            $idNegocio == DocumentoTipoNegocio::IN_BOLETA_VENTA ||
            $idNegocio == DocumentoTipoNegocio::IN_FACTURA_VENTA ||
            $idNegocio == DocumentoTipoNegocio::IN_NOTA_CREDITO_VENTA ||
            $idNegocio == DocumentoTipoNegocio::IN_NOTA_DEBITO_VENTA
        ) {

            $empresa = EmpresaNegocio::create()->obtenerEmpresaXId($documento[0]['empresa_id']);

            $ubigeoEmpresa = PersonaNegocio::create()->obtenerUbigeoXId($empresa[0]["ubigeo_id"]);
            if (ObjectUtil::isEmpty($ubigeoEmpresa)) {
                throw new WarningException("No se especificó el ubigeo del emisor");
            }

            $persona = PersonaNegocio::create()->obtenerPersonaXId($documento[0]["persona_id"]);
            if (ObjectUtil::isEmpty($persona)) {
                throw new WarningException("No se encontró a la persona del documento");
            }
            $ubigeo = PersonaNegocio::create()->obtenerUbigeoXId($documento[0]["ubigeo_id"]);
            if (ObjectUtil::isEmpty($ubigeo)) {
                throw new WarningException("No se especificó el ubigeo del receptor");
            }
            $enLetras = new EnLetras();
            $importeLetras = $enLetras->ValorEnLetras($documento[0]["total"], $documento[0]['moneda_id']);

            $comprobanteElectronico->emisorNroDocumento = $empresa[0]['ruc'];
            $comprobanteElectronico->emisorTipoDocumento = $empresa[0]['sunat_tipo_documento'];
            $comprobanteElectronico->emisorUbigeo = $ubigeoEmpresa[0]['ubigeo_codigo'];
            $comprobanteElectronico->emisorDireccion = $empresa[0]['direccion_fe'];
            $comprobanteElectronico->emisorUrbanizacion = $empresa[0]['urbanizacion'];
            $comprobanteElectronico->emisorDepartamento = $ubigeoEmpresa[0]['ubigeo_dep'];
            $comprobanteElectronico->emisorProvincia = $ubigeoEmpresa[0]['ubigeo_prov'];
            $comprobanteElectronico->emisorDistrito = $ubigeoEmpresa[0]['ubigeo_dist'];
            $comprobanteElectronico->emisorNombreLegal = $empresa[0]['razon_social'];
            $comprobanteElectronico->emisorNombreComercial = $empresa[0]['nombre_comercial'];

            // factura
            $comprobanteElectronico->docFechaEmision = date("Y-m-d"); //$documento[0]["fecha_emision"];
            $comprobanteElectronico->docFechaReferencia = substr($documento[0]["fecha_emision"], 0, 10);
            $comprobanteElectronico->docSecuencial = $documento[0]['nro_secuencial_baja'];

            // Detalle
            // Det0           
            $serieDoc = $documento[0]["serie"];
            $numeroDoc = $documento[0]["numero"];

            if ($idNegocio == DocumentoTipoNegocio::IN_NOTA_DEBITO_VENTA) {
                $serieNum = DocumentoNegocio::create()->obtenerSerieNumeroXDocumentoId($documentoId);

                $serieDoc = $serieNum[0]["serie"];
                $numeroDoc = $serieNum[0]["numero"];
            }

            //VALIDA SERIE
            if ($idNegocio == DocumentoTipoNegocio::IN_BOLETA_VENTA && $serieDoc[0] != 'B') {
                throw new WarningException("La serie de la boleta a eliminar debe empezar con B");
            } else if ($idNegocio == DocumentoTipoNegocio::IN_FACTURA_VENTA && $serieDoc[0] != 'F') {
                throw new WarningException("La serie de la factura a eliminar debe empezar con F");
            } else if ($serieDoc[0] != 'B' && $serieDoc[0] != 'F') {
                throw new WarningException("La serie del documento a eliminar debe empezar con F o B");
            }

            //VALIDA MOTIVO ANULACION
            if (ObjectUtil::isEmpty($documento[0]['motivo_anulacion'])) {
                throw new WarningException("Motivo de anulación es obligatorio.");
            }

            $items[0][0] = 1;
            $items[0][1] = $documento[0]["sunat_tipo_doc_rel"];
            $items[0][2] = $serieDoc;
            $items[0][3] = $numeroDoc;
            $items[0][4] = $documento[0]['motivo_anulacion'];

            $comprobanteElectronico->bajas = $items;
            $comprobanteElectronico->usuarioSunatSOL = $empresa[0]['usuario_sunat_sol'];
            $comprobanteElectronico->claveSunatSOL = $empresa[0]['clave_sunat_sol'];
            $comprobanteElectronico->usuarioOSE = Configuraciones::EFACT_USER_OSE;
            $comprobanteElectronico->claveOSE = Configuraciones::EFACT_PASS_OSE;
            $comprobanteElectronico = (array) $comprobanteElectronico;

            $client = self::conexionEFAC();

            $resultado = $client->procesarComunicacionBaja($comprobanteElectronico)->procesarComunicacionBajaResult;
            //            $this->setMensajeEmergente("Resultado EFACT: ".$resultado);
            //            VALIDAR EL RESULTADO
            $this->validarResultadoEfactura($resultado);
            //            var_dump($comprobanteElectronico);

            if (strpos($resultado, 'ticket') !== false) {
                $nroticket = explode(':', $resultado);
                $ticket = trim($nroticket[2]);
            }

            //SI TODO ESTA BIEN ACTUALIZAMOS EL NUMERO SECUENCIAL DE BAJA Y EL TICKET QUE SE GENERÓ
            DocumentoNegocio::create()->actualizarNroSecuencialBajaXDocumentoId($documentoId, $documento[0]['nro_secuencial_baja'], $ticket);
        }
    }

    public function generarDocumentoElectronicoPorResumenDiario($documentosResumen)
    {
        //obtenemos id de documentos que se enviaran a resumen                
        //        $documentosResumen = DocumentoNegocio::create()->obtenerIdDocumentosResumenDiario();


        if (ObjectUtil::isEmpty($documentosResumen)) {
            throw new WarningException("No se encontraron documentos para generar por resumen diario");
        }

        $i = 0;
        foreach ($documentosResumen as $index => $fila) {

            //arreglo con los id del documento
            $idDocumentos[$index] = $fila['documentoId'];

            $documento = DocumentoNegocio::create()->obtenerDocumentoFEXId($fila['documentoId']);

            $montoTotal = $documento[0]["total"] * 1.0;
            $montoIgv = $documento[0]["igv"] * 1.0;
            $montoAfecto = $documento[0]["subtotal"] * 1.0;
            $montoGratuito = 0;
            if ($documento[0]['movimiento_tipo_codigo'] == "18") {
                $montoTotal = 0;
                $montoIgv = 0;
                $montoAfecto = 0;
                if ($documento[0]['noafecto'] * 1.0 > 0) {
                    $montoGratuito = $documento[0]['noafecto'] * 1.0;
                } else {
                    $montoGratuito = $documento[0]["total"] * 1.0;
                }
            }
            $items[$index][0] = $i + 1; //Número de fila
            $serieDoc = $documento[0]["serie"];
            $numeroDoc = $documento[0]["numero"];
            $items[$index][1] = $serieDoc . '-' . $numeroDoc; //Número de serie del documento – Numero correlativo 
            $items[$index][2] = $documento[0]["sunat_tipo_doc_rel"]; //Tipo de documento
            $items[$index][3] = $documento[0]["sunat_moneda"]; //moneda

            $persona = PersonaNegocio::create()->obtenerPersonaXId($documento[0]["persona_id"]);

            $items[$index][4] = $persona[0]["codigo_identificacion"]; //Número de documento de Identidad del adquirente o usuario
            $items[$index][5] = $persona[0]["sunat_tipo_documento"]; //Tipo de documento de Identidad del adquirente o usuario
            $items[$index][6] = 1; //Estado del ítem (1 es generar)
            $items[$index][7] = $montoTotal; //Importe total de la venta                 
            $items[$index][8] = $montoAfecto; //Total valor de venta - operaciones gravadas
            $items[$index][9] = 0.0; //Total valor de venta - operaciones exoneradas
            $items[$index][10] = 0.0; //Total valor de venta - operaciones inafectas
            $items[$index][11] = $montoGratuito; //Total Valor Venta operaciones Gratuitas
            $items[$index][12] = $montoIgv;  //Total IGV

            $items[$index][13] = null; //nroDocumentoRelacionado 
            $items[$index][14] = null; //tipoDocumentoRelacionado

            $idNegocio = $documento[0]['identificador_negocio'];

            if ($idNegocio == DocumentoTipoNegocio::IN_NOTA_CREDITO_VENTA && $serieDoc[0] == 'B') {

                //OBTENEMOS LOS DOCUMENTOS RELACIONADOS
                $docRelacion = DocumentoNegocio::create()->obtenerDocumentoRelacionadoActivoXDocumentoId($fila['documentoId']);

                foreach ($docRelacion as $indRel => $itemRel) {
                    if ($itemRel['identificador_negocio_relacion'] == DocumentoTipoNegocio::IN_BOLETA_VENTA) {
                        $items[$index][13] = $itemRel["serie_relacion"] . '-' . $itemRel["numero_relacion"]; //nroDocumentoRelacionado
                        $items[$index][14] = $itemRel['sunat_tipo_doc_rel']; //tipoDocumentoRelacionado
                    }
                }
            }
            $i++;
        }
        // Obtenemos Datos de emisor
        $documento = DocumentoNegocio::create()->obtenerDocumentoFEXId($documentosResumen[0]["documentoId"]);
        $empresa = EmpresaNegocio::create()->obtenerEmpresaXId($documento[0]['empresa_id']);
        $ubigeoEmpresa = PersonaNegocio::create()->obtenerUbigeoXId($empresa[0]["ubigeo_id"]);

        $comprobanteElectronico->emisorNroDocumento = $empresa[0]['ruc'];
        $comprobanteElectronico->emisorTipoDocumento = $empresa[0]['sunat_tipo_documento'];
        $comprobanteElectronico->emisorUbigeo = $ubigeoEmpresa[0]['ubigeo_codigo'];
        $comprobanteElectronico->emisorDireccion = $empresa[0]['direccion_fe'];
        $comprobanteElectronico->emisorUrbanizacion = $empresa[0]['urbanizacion'];
        $comprobanteElectronico->emisorDepartamento = $ubigeoEmpresa[0]['ubigeo_dep'];
        $comprobanteElectronico->emisorProvincia = $ubigeoEmpresa[0]['ubigeo_prov'];
        $comprobanteElectronico->emisorDistrito = $ubigeoEmpresa[0]['ubigeo_dist'];
        $comprobanteElectronico->emisorNombreLegal = $empresa[0]['razon_social'];
        $comprobanteElectronico->emisorNombreComercial = $empresa[0]['nombre_comercial'];

        $comprobanteElectronico->docFechaEmision = date('Y-m-d'); //$documento[0]["fecha_emision"];       
        $comprobanteElectronico->docFechaReferencia = $documento[0]["fecha_emision"];
        $comprobanteElectronico->docSecuencial = 2;

        $comprobanteElectronico->resumenes = $items;
        $comprobanteElectronico->usuarioSunatSOL = $empresa[0]['usuario_sunat_sol'];
        $comprobanteElectronico->claveSunatSOL = $empresa[0]['clave_sunat_sol'];
        $comprobanteElectronico->usuarioOSE = Configuraciones::EFACT_USER_OSE;
        $comprobanteElectronico->claveOSE = Configuraciones::EFACT_PASS_OSE;
        $comprobanteElectronico = (array) $comprobanteElectronico;
        //        var_dump($comprobanteElectronico);              
        //        exit();  

        $client = self::conexionEFAC();

        $resultado = $client->procesarResumenDiarioNuevo($comprobanteElectronico)->procesarResumenDiarioNuevoResult;
        //        VALIDAR EL RESULTADO
        $this->validarResultadoEfactura($resultado);
        //        var_dump($comprobanteElectronico);          

        if (strpos($resultado, 'ticket') !== false) {
            $nroticket = explode(':', $resultado);
            $ticket = trim($nroticket[2]);
        }

        for ($j = 0; $j < count($idDocumentos); $j++) {
            DocumentoNegocio::create()->actualizarEstadoEfactAnulacionXDocumentoId($idDocumentos[$j], NULL, $ticket);
            DocumentoNegocio::create()->actualizarEfactEstadoRegistro($idDocumentos[$j], 1);
        }
    }

    public function anularDocumentoElectronicoPorResumenDiario()
    {
        //obtenemos id de documentos que se enviaran a resumen                
        $documentosResumen = DocumentoNegocio::create()->obtenerIdDocumentosResumenDiario();
        $i = 0;

        if (ObjectUtil::isEmpty($documentosResumen)) {
            throw new WarningException("No se encontraron documentos para realizar baja por resumen diario");
        }

        foreach ($documentosResumen as $index => $fila) {

            //arreglo con los id del documento
            $idDocumentos[$index] = $fila['documentoId'];

            $documento = DocumentoNegocio::create()->obtenerDocumentoFEXId($fila['documentoId']);

            $montoTotal = $documento[0]["total"] * 1.0;
            $montoIgv = $documento[0]["igv"] * 1.0;
            $montoAfecto = $documento[0]["subtotal"] * 1.0;
            $montoGratuito = 0;
            if ($documento[0]['movimiento_tipo_codigo'] == "18") {
                $montoTotal = 0;
                $montoIgv = 0;
                $montoAfecto = 0;
                if ($documento[0]['noafecto'] * 1.0 > 0) {
                    $montoGratuito = $documento[0]['noafecto'] * 1.0;
                } else {
                    $montoGratuito = $documento[0]["total"] * 1.0;
                }
            }
            $items[$index][0] = $i + 1; //Número de fila
            $serieDoc = $documento[0]["serie"];
            $numeroDoc = $documento[0]["numero"];
            $items[$index][1] = $serieDoc . '-' . $numeroDoc; //Número de serie del documento – Numero correlativo 
            $items[$index][2] = $documento[0]["sunat_tipo_doc_rel"]; //Tipo de documento
            $items[$index][3] = $documento[0]["sunat_moneda"]; //moneda

            $persona = PersonaNegocio::create()->obtenerPersonaXId($documento[0]["persona_id"]);

            $items[$index][4] = $persona[0]["codigo_identificacion"]; //Número de documento de Identidad del adquirente o usuario
            $items[$index][5] = $persona[0]["sunat_tipo_documento"]; //Tipo de documento de Identidad del adquirente o usuario
            $items[$index][6] = 3; //Estado del ítem (3 es anulado)
            $items[$index][7] = $montoTotal; //Importe total de la venta                 
            $items[$index][8] = $montoAfecto; //Total valor de venta - operaciones gravadas
            $items[$index][9] = 0.0; //Total valor de venta - operaciones exoneradas
            $items[$index][10] = 0.0; //Total valor de venta - operaciones inafectas
            $items[$index][11] = $montoGratuito; //Total Valor Venta operaciones Gratuitas
            $items[$index][12] = $montoIgv;  //Total IGV

            $items[$index][13] = null; //nroDocumentoRelacionado 
            $items[$index][14] = null; //tipoDocumentoRelacionado

            $idNegocio = $documento[0]['identificador_negocio'];

            if ($idNegocio == DocumentoTipoNegocio::IN_NOTA_CREDITO_VENTA && $serieDoc[0] == 'B') {

                //OBTENEMOS LOS DOCUMENTOS RELACIONADOS
                $docRelacion = DocumentoNegocio::create()->obtenerDocumentoRelacionadoActivoXDocumentoId($fila['documentoId']);

                foreach ($docRelacion as $indRel => $itemRel) {
                    if ($itemRel['identificador_negocio_relacion'] == DocumentoTipoNegocio::IN_BOLETA_VENTA) {
                        $items[$index][13] = $itemRel["serie_relacion"] . '-' . $itemRel["numero_relacion"]; //nroDocumentoRelacionado
                        $items[$index][14] = $itemRel['sunat_tipo_doc_rel']; //tipoDocumentoRelacionado
                    }
                }
            }
            $i++;
        }
        // Obtenemos Datos de emisor
        $documento = DocumentoNegocio::create()->obtenerDocumentoFEXId($documentosResumen[0]["documentoId"]);
        $empresa = EmpresaNegocio::create()->obtenerEmpresaXId($documento[0]['empresa_id']);
        $ubigeoEmpresa = PersonaNegocio::create()->obtenerUbigeoXId($empresa[0]["ubigeo_id"]);

        $comprobanteElectronico->emisorNroDocumento = $empresa[0]['ruc'];
        $comprobanteElectronico->emisorTipoDocumento = $empresa[0]['sunat_tipo_documento'];
        $comprobanteElectronico->emisorUbigeo = $ubigeoEmpresa[0]['ubigeo_codigo'];
        $comprobanteElectronico->emisorDireccion = $empresa[0]['direccion_fe'];
        $comprobanteElectronico->emisorUrbanizacion = $empresa[0]['urbanizacion'];
        $comprobanteElectronico->emisorDepartamento = $ubigeoEmpresa[0]['ubigeo_dep'];
        $comprobanteElectronico->emisorProvincia = $ubigeoEmpresa[0]['ubigeo_prov'];
        $comprobanteElectronico->emisorDistrito = $ubigeoEmpresa[0]['ubigeo_dist'];
        $comprobanteElectronico->emisorNombreLegal = $empresa[0]['razon_social'];
        $comprobanteElectronico->emisorNombreComercial = $empresa[0]['nombre_comercial'];

        $comprobanteElectronico->docFechaEmision = date('Y-m-d'); //$documento[0]["fecha_emision"];       
        $comprobanteElectronico->docFechaReferencia = $documento[0]["fecha_emision"];
        $comprobanteElectronico->docSecuencial = 1;

        $comprobanteElectronico->resumenes = $items;
        $comprobanteElectronico->usuarioSunatSOL = $empresa[0]['usuario_sunat_sol'];
        $comprobanteElectronico->claveSunatSOL = $empresa[0]['clave_sunat_sol'];
        $comprobanteElectronico->usuarioOSE = Configuraciones::EFACT_USER_OSE;
        $comprobanteElectronico->claveOSE = Configuraciones::EFACT_PASS_OSE;
        $comprobanteElectronico = (array) $comprobanteElectronico;
        //        var_dump($comprobanteElectronico);              
        //        exit();  

        $client = self::conexionEFAC();

        $resultado = $client->procesarResumenDiarioNuevo($comprobanteElectronico)->procesarResumenDiarioNuevoResult;
        //        $this->setMensajeEmergente("Resultado EFACT: ".$resultado);
        //        VALIDAR EL RESULTADO
        $this->validarResultadoEfactura($resultado);
        //        var_dump($comprobanteElectronico);          

        if (strpos($resultado, 'ticket') !== false) {
            $nroticket = explode(':', $resultado);
            $ticket = trim($nroticket[2]);
        }

        for ($j = 0; $j < count($idDocumentos); $j++) {
            DocumentoNegocio::create()->actualizarEstadoEfactAnulacionXDocumentoId($idDocumentos[$j], 1, $ticket);
            DocumentoNegocio::create()->actualizarEfactEstadoRegistro($idDocumentos[$j], 1);
        }
    }

    public function aprobar($documentoId, $documentoEstadoId, $usuarioId)
    {
        //        $respuestaAnular = DocumentoNegocio::create()->anular($documentoId);
        $respuestaAnularDocumentoEstado = DocumentoNegocio::create()->ActualizarDocumentoEstadoId($documentoId, $documentoEstadoId, $usuarioId);
        if ($respuestaAnularDocumentoEstado[0]['vout_exito'] != 1) {
            throw new WarningException("No se Actualizo Documento estado");
        } else {
            $this->setMensajeEmergente($respuestaAnular[0]['vout_mensaje']);
        }
    }

    public function visualizarDocumento($documentoId, $movimientoId)
    {
        $arrayDetalle = array();
        $respuesta = new ObjectUtil();

        $respuesta->dataDocumento = DocumentoNegocio::create()->obtenerDetalleDocumento($documentoId);
        $respuesta->comentarioDocumento = DocumentoNegocio::create()->obtenerComentarioDocumento($documentoId);
        $respuesta->direccionEmpresa = DocumentoNegocio::create()->obtenerDireccionEmpresa($documentoId);

        $documentoDetalle = MovimientoBien::create()->obtenerXIdMovimiento($movimientoId);

        //        if (ObjectUtil::isEmpty($documentoDetalle)) {
        //            throw new WarningException("No se encontró detalles de este documento");
        //        }

        if (!ObjectUtil::isEmpty($documentoDetalle)) {
            $total = 0.00;
            foreach ($documentoDetalle as $detalle) {
                $subTotal = $detalle['cantidad'] * $detalle['valor_monetario']; // + $detalle['ad_valorem']
                array_push($arrayDetalle, $this->getDetalle($detalle['organizador_descripcion'], $detalle['cantidad'], $detalle['bien_descripcion'], $detalle['valor_monetario'], $subTotal, $detalle['unidad_medida_descripcion'], $detalle['simbolo'], $detalle['bien_codigo'], $detalle['unidad_medida_id'], $detalle["bien_id"], $detalle["ad_valorem"], $detalle["movimiento_bien_comentario"], $detalle['bien_peso']));
                $total += $subTotal;
            }
        }

        $respuesta->detalleDocumento = $arrayDetalle;
        return $respuesta;
    }

    private function getDetalle($organizador, $cantidad, $descripcion, $precioUnitario, $importe, $unidadMedida, $simbolo, $bien_codigo, $unidadMedidaID, $bienId, $adValorem = 0, $movimientoBienComentario = '', $peso = 0)
    {

        $detalle = new stdClass();
        $detalle->organizador = $organizador;
        $detalle->cantidad = $cantidad;
        $detalle->descripcion = $descripcion;
        $detalle->precioUnitario = $precioUnitario;
        $detalle->importe = $importe;
        $detalle->unidadMedida = $unidadMedida;
        $detalle->simbolo = $simbolo;
        $detalle->bien_codigo = $bien_codigo;
        $detalle->unidadMedidaId = $unidadMedidaID;
        $detalle->bienId = $bienId;
        $detalle->adValorem = $adValorem;
        $detalle->movimientoBienComentario = $movimientoBienComentario;
        $detalle->peso = $peso;
        return $detalle;
    }

    public function obtenerStockAControlar($opcionId, $bienId, $organizadorId, $unidadMedidaId, $cantidad, $fechaEmision = null, $organizadorDestinoId = null)
    {
        if (ObjectUtil::isEmpty($organizadorId))
            return -1;
        $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);
        if (!ObjectUtil::isEmpty($movimientoTipo)) {
            if ($movimientoTipo[0]["indicador"] == MovimientoTipoNegocio::INDICADOR_SALIDA) {
                $bien = BienNegocio::create()->getBien($bienId);
                if ($bien[0]['bien_tipo_id'] == -1) {
                    return -1;
                } else {
                    $dataFechas = null;
                    if (!ObjectUtil::isEmpty($fechaEmision)) {
                        $dataFechas = DocumentoNegocio::create()->obtenerFechasPosterioresDocumentosSalidas(
                            $fechaEmision,
                            $bienId,
                            $organizadorId
                        );
                    }

                    if (!ObjectUtil::isEmpty($dataFechas)) {
                        $arrayFecha = array("fecha_emision" => $fechaEmision);
                        array_push($dataFechas, $arrayFecha);
                        array_multisort($dataFechas);

                        //validamos stock por fecha posterior o igual a fecha emision
                        $dataFechaInicial = DocumentoNegocio::create()->obtenerFechaPrimerDocumento();
                        $fechaInicial = $dataFechaInicial[0]['primera_fecha'];

                        foreach ($dataFechas as $itemFecha) {
                            $fechaFinal = $itemFecha['fecha_emision'];
                            //obtener stock
                            $stock = BienNegocio::create()->obtenerStockEntreFechasXBienIdXOrganizadorIdXUnidadMedidaId($bienId, $organizadorId, $unidadMedidaId, $fechaInicial, $fechaFinal, $organizadorDestinoId);

                            $stockControlar = (!ObjectUtil::isEmpty($stock)) ? $stock[0]["stock"] : 0;

                            if ((floatval($stockControlar) - floatval($cantidad)) < 0) {
                                throw new WarningException("No cuenta con stock suficiente en el almacén seleccionado.<br>"
                                    . " Stock en fecha " . date_format((date_create($fechaFinal)), 'd/m/Y') . ": " . number_format($stockControlar, 2, ".", ",") . "<br>"
                                    . " Producto: " . $bien[0]['descripcion'] . "<br>"
                                    . " Cantidad: " . $cantidad);
                            }
                        }
                    } else {
                        // stock hasta fecha actual
                        $stock = BienNegocio::create()->obtenerStockActual($bienId, $organizadorId, $unidadMedidaId, $organizadorDestinoId);
                        // obtenerStockBase($organizadorId, $bienId);
                        $stockControlar = (!ObjectUtil::isEmpty($stock)) ? $stock[0]["stock"] : 0;
                        if ((floatval($stockControlar) - floatval($cantidad)) < 0) {
                            throw new WarningException("No cuenta con stock suficiente en el almacén seleccionado.<br>"
                                . " Stock: " . number_format($stockControlar, 2, ".", ",") . "<br>"
                                . " Producto: " . $bien[0]['descripcion'] . "<br>"
                                . " Cantidad: " . $cantidad);
                        } else {
                            return $stockControlar;
                        }
                    }
                }
            } else {
                return -1;
            }
        } else {
            return 0;
        }
    }

    private function guardarDeExcel($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $guardar)
    {
        $respuesta = new stdClass();
        try {
            $this->beginTransaction();
            MovimientoNegocio::create()->guardar($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, null, null);
            $this->commitTransaction();

            $respuesta->exito = true;
            $respuesta->mensaje = "Éxito";
        } catch (WarningException $we) {
            $this->rollbackTransaction();
            // Registrar en el excel el error
            ExcelNegocio::create()->generarExcelMovimientosErrores($opcionId, $documentoTipoId, $camposDinamicos, $detalle, $we->getMessage());

            $respuesta->exito = false;
            $respuesta->mensaje = $we->getMessage();
        } catch (ModeloException $me) {
            $this->rollbackTransaction();
            // Registrar en el excel el error
            ExcelNegocio::create()->generarExcelMovimientosErrores($opcionId, $documentoTipoId, $camposDinamicos, $detalle, $me->getMessage());

            $respuesta->exito = false;
            $respuesta->mensaje = $me->getMessage();
        } catch (Exception $ex) {
            $this->rollbackTransaction();
            // Registrar en el excel el error
            ExcelNegocio::create()->generarExcelMovimientosErrores($opcionId, $documentoTipoId, $camposDinamicos, $detalle, $ex->getMessage());

            $respuesta->exito = false;
            $respuesta->mensaje = $ex->getMessage();
        }

        if ($guardar == TRUE) {
            ExcelNegocio::create()->guardarExcelMovimientosErrores($opcionId);
        }

        return $respuesta;
    }

    public function importarExcelMovimiento($opcionId, $usuarioId, $xml, $usuCreacion)
    {
        $filasImportadas = 0;
        $row = 7;
        $errors = array();
        $dom = new DOMDocument;
        $xml = "<root>" . $xml . "</root>";
        //Documento tipo
        $xml = str_replace("documento tipo", "documentoTipo", $xml);

        $dom->loadXML($xml);
        $movExcel = simplexml_import_dom($dom);
        $detalle = array();
        for ($i = 0; $i < count($movExcel); $i++) {
            $bandera = false;
            $filaExcel = $movExcel->movi[$i];
            //documentoTipo
            $documentoTipoNombre = trim((string) $filaExcel->documentoTipo);

            $documentoTipo = DocumentoTipoNegocio::create()->obtenerIdXDocumentoTipoDescripcionOpcionId($documentoTipoNombre, $opcionId);
            $documentoTipoId = $documentoTipo[0][id];
            $empresaId = $documentoTipo[0][empresa_id];

            //Dinamico            
            $documentoTipoNombreDato = DocumentoTipoNegocio::create()->obtenerDocumentoTipoDato($documentoTipoId);

            $indice = 0;
            foreach ($documentoTipoNombreDato as $index => $dtd) {
                $nombreColumna = str_replace(' ', '', $dtd['descripcion']);
                if (property_exists($filaExcel, $nombreColumna)) {
                    $valorDinamico = trim((string) $filaExcel->{$nombreColumna});

                    switch ($dtd["tipo"]) {
                        case DocumentoTipoNegocio::DATO_PERSONA:
                            // buscar en data
                            $valor = null;
                            foreach ($dtd["data"] as $persona) {
                                if ($persona['nombre'] == $valorDinamico) {
                                    $valor = $persona[id];
                                    break;
                                }
                            }
                            $documentoTipoNombreDato[$index]["valor"] = $valor;
                            break;
                        case DocumentoTipoNegocio::DATO_LISTA:
                            $valor = null;
                            foreach ($dtd["data"] as $lista) {
                                if ($lista['descripcion'] == $valorDinamico) {
                                    $valor = $lista[id];
                                    break;
                                }
                            }
                            $documentoTipoNombreDato[$index]["valor"] = $valor;
                            break;

                        default:
                            //$documentoTipoNombreDato[$index]["valor"] = $filaExcel[$dtd["descripcion"]];
                            $documentoTipoNombreDato[$index]["valor"] = $valorDinamico;
                    }

                    $camposDinamicos[$indice] = array(
                        id => $documentoTipoNombreDato[$index]["id"],
                        tipo => $documentoTipoNombreDato[$index]["tipo"],
                        opcional => $documentoTipoNombreDato[$index]["opcional"],
                        descripcion => $documentoTipoNombreDato[$index]["descripcion"],
                        valor => $documentoTipoNombreDato[$index]["valor"],
                        valorExcel => $valorDinamico
                    );
                    $indice++;
                    //$valorDinamicoAntes=$valorDinamico;
                }
            }

            $camposDinamicosGuardar = $camposDinamicosAntes;
            $documentoTipoIdGuardar = $documentoTipoIdAntes;

            if ($i != 0) {
                if ($camposDinamicosAntes != $camposDinamicos) {
                    $bandera = true;
                }
            }

            $camposDinamicosAntes = $camposDinamicos;
            $documentoTipoIdAntes = $documentoTipoId;

            //detalle
            $organizador = trim((string) $filaExcel->Organizador);
            $cantidad = trim((string) $filaExcel->Cantidad);
            $unidadMedida = trim((string) $filaExcel->UnidadMedida);
            $bien = trim((string) $filaExcel->Bien);
            $precioUnitario = trim((string) $filaExcel->PrecioUnitario);
            $totalDetalle = trim((string) $filaExcel->TotalDetalle);

            //buscamos Id de los detalles
            $organizadorBuscado = OrganizadorNegocio::create()->obtenerOrganizadorActivoXDescripcion($organizador);
            $organizador_id = $organizadorBuscado[0][id];

            $unidadMedidaBuscado = UnidadNegocio::create()->obtenerUnidadMedidaActivoXDescripcion($unidadMedida);
            $unidadMedida_id = $unidadMedidaBuscado[0][id];

            $bienBuscado = BienNegocio::create()->obtenerBienActivoXDescripcion($bien);
            $bien_id = $bienBuscado[0][id];

            //array detalles
            if ($bandera == false) {
                array_push($detalle, array(
                    organizadorId => $organizador_id, bienId => $bien_id,
                    cantidad => $cantidad, unidadMedidaId => $unidadMedida_id, precio => $precioUnitario,
                    organizadorDesc => $organizador, bienDesc => $bien, unidadMedidaDesc => $unidadMedida,
                    subTotal => $totalDetalle
                ));
            }

            if ($i == (count($movExcel) - 1)) {
                if ($bandera == true) {
                    $response = MovimientoNegocio::create()->guardarDeExcel($opcionId, $usuarioId, $documentoTipoIdGuardar, $camposDinamicosGuardar, $detalle, false);
                    //Errores
                    if ($response > 0) {
                        $filasImportadas++;
                    } else {
                        $cause = $response[0]["vout_mensaje"];
                        $errors[] = array("row" => $row, "cause" => $cause);
                    }
                    $row++;

                    $detalle = array();

                    array_push($detalle, array(
                        organizadorId => $organizador_id, bienId => $bien_id,
                        cantidad => $cantidad, unidadMedidaId => $unidadMedida_id, precio => $precioUnitario,
                        organizadorDesc => $organizador, bienDesc => $bien, unidadMedidaDesc => $unidadMedida,
                        subTotal => $totalDetalle
                    ));

                    $response = MovimientoNegocio::create()->guardarDeExcel($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, true);
                    //Errores
                    if ($response->exito) {
                        $filasImportadas++;
                    } else {
                        $cause = $response->mensaje;
                        $errors[] = array("row" => $row, "cause" => $cause);
                    }
                    $row++;
                } else {
                    $response = MovimientoNegocio::create()->guardarDeExcel($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, true);
                    //Errores
                    if ($response->exito) {
                        $filasImportadas++;
                    } else {
                        $cause = $response->mensaje;
                        $errors[] = array("row" => $row, "cause" => $cause);
                    }
                    $row++;
                }
            } else {
                if ($bandera == true) {
                    $response = MovimientoNegocio::create()->guardarDeExcel($opcionId, $usuarioId, $documentoTipoIdGuardar, $camposDinamicosGuardar, $detalle, false);
                    //Errores
                    if ($response->exito) {
                        $filasImportadas++;
                    } else {
                        $cause = $response->mensaje;
                        $errors[] = array("row" => $row, "cause" => $cause);
                    }
                    $row++;

                    $detalle = array();

                    array_push($detalle, array(
                        organizadorId => $organizador_id, bienId => $bien_id,
                        cantidad => $cantidad, unidadMedidaId => $unidadMedida_id, precio => $precioUnitario,
                        organizadorDesc => $organizador, bienDesc => $bien, unidadMedidaDesc => $unidadMedida,
                        subTotal => $totalDetalle
                    ));
                }
            }
        }
        if ($row == $filasImportadas + 7) {
            $this->setMensajeEmergente("Importacion finalizada. Se procesaron $filasImportadas de " . ($row - 7) . " filas.");
        }

        return $errors;
    }

    //Area de funciones para copiar documento 

    function obtenerConfiguracionBuscadorDocumentoRelacion($opcionId, $empresaId)
    {
        $tipoIds = '(0),(1),(4)';
        $respuesta = new ObjectUtil();
        $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);
        $movimientoTipoId = $movimientoTipo[0]["id"];

        //$respuesta->documento_tipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXEmpresaXTipo($empresaId, $tipoIds);
        $respuesta->documento_tipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXEmpresaXTipoXMovimientoTipo($movimientoTipoId, $empresaId, $tipoIds);
        //        $respuesta->persona = PersonaNegocio::create()->obtenerActivas();

        return $respuesta;
    }

    function buscarDocumentoACopiar($criterios, $elementosFiltrados, $columnas, $orden, $tamanio, $transferenciaTipo)
    {

        $empresaId = $criterios['empresa_id'];
        $documentoTipoIds = $criterios['documento_tipo_ids'];
        $personaId = $criterios['persona_id'];
        $serie = $criterios['serie'];
        $numero = $criterios['numero'];
        $fechaEmisionInicio = DateUtil::formatearCadenaACadenaBD($criterios['fecha_emision_inicio']);
        $fechaEmisionFin = DateUtil::formatearCadenaACadenaBD($criterios['fecha_emision_fin']);
        $fechaVencimientoInicio = DateUtil::formatearCadenaACadenaBD($criterios['fecha_vencimiento_inicio']);
        $fechaVencimientoFin = DateUtil::formatearCadenaACadenaBD($criterios['fecha_vencimiento_fin']);

        $movimientoTipoId = $criterios['movimiento_tipo_id'];

        $documentoTipoIdFormateado = Util::convertirArrayXCadena($documentoTipoIds);

        $columnaOrdenarIndice = $orden[0]['column'];
        $formaOrdenar = $orden[0]['dir'];

        $columnaOrdenar = $columnas[$columnaOrdenarIndice]['data'];

        $respuesta = new ObjectUtil();

        $respuesta->data = Movimiento::create()->buscarDocumentoACopiar($empresaId, $documentoTipoIdFormateado, $personaId, $serie, $numero, $fechaEmisionInicio, $fechaEmisionFin, $fechaVencimientoInicio, $fechaVencimientoFin, $elementosFiltrados, $formaOrdenar, $columnaOrdenar, $tamanio, $transferenciaTipo, $movimientoTipoId);

        $respuesta->contador = Movimiento::create()->buscarDocumentoACopiarTotal($empresaId, $documentoTipoIdFormateado, $personaId, $serie, $numero, $fechaEmisionInicio, $fechaEmisionFin, $fechaVencimientoInicio, $fechaVencimientoFin, $formaOrdenar, $columnaOrdenar, $transferenciaTipo, $movimientoTipoId);

        return $respuesta;
    }

    function obtenerDocumentoRelacionCabecera($documentoOrigenId, $documentoDestinoId, $movimientoId, $documentoId, $opcionId, $documentoRelacionados)
    {
        $respuesta = new ObjectUtil();
        $datoDocumento = DocumentoNegocio::create()->obtenerDataDocumentoACopiar($documentoDestinoId, $documentoOrigenId, $documentoId);

        if (ObjectUtil::isEmpty($datoDocumento)) {
            throw new WarningException("No se encontró el documento");
        }

        $respuesta->dataDocumento = $datoDocumento;
        $respuesta->dataDocumentoRelacionada = DocumentoNegocio::create()->obtenerDataDocumentoACopiarRelacionada($documentoOrigenId, $documentoDestinoId, $documentoId);

        if ($documentoDestinoId != $documentoOrigenId) {
            $respuesta->documentoCopiaRelaciones = DocumentoNegocio::create()->obtenerRelacionesDocumento($documentoId);
        } else {
            $respuesta->documentoCopiaRelaciones = 1;
        }

        return $respuesta;
    }

    function obtenerDocumentoRelacion($documentoTipoOrigenId, $documentoTipoDestinoId, $movimientoId, $documentoId, $opcionId, $documentoRelacionados)
    {
        $respuesta = new ObjectUtil();

        $documentoACopiar = DocumentoNegocio::create()->obtenerDataDocumentoACopiar($documentoTipoDestinoId, $documentoTipoOrigenId, $documentoId);

        if (ObjectUtil::isEmpty($documentoACopiar)) {
            throw new WarningException("No se encontró el documento");
        }

        $respuesta->documentoACopiar = $documentoACopiar;
        $respuesta->dataDocumentoRelacionada = DocumentoNegocio::create()->obtenerDataDocumentoACopiarRelacionada($documentoTipoOrigenId, $documentoTipoDestinoId, $documentoId);
        $respuesta->detalleDocumento = $this->obtenerDocumentoRelacionDetalle($movimientoId, $documentoId, $opcionId, $documentoRelacionados);

        if ($documentoTipoDestinoId != $documentoTipoOrigenId) {
            $respuesta->documentosRelacionados = DocumentoNegocio::create()->obtenerRelacionesDocumento($documentoId);
        } else {
            $respuesta->documentosRelacionados = 1;
        }

        //        $respuesta->dataPagoProgramacion = PagoNegocio::create()->obtenerPagoProgramacionXDocumentoId($documentoId);
        //OBTENER DATA DE UNIDAD DE MEDIDA
        $documentoDetalle = $respuesta->detalleDocumento;
        foreach ($documentoDetalle as $index => $item) {
            $bienId = $item['bien_id'];
            $unidadMedidaId = $item['unidad_medida_id'];
            $precioTipoId = $item['precio_tipo_id'];
            $monedaId = $documentoACopiar[0]['moneda_id'];
            $fechaEmision = date("d/m/Y");
            foreach ($documentoACopiar as $itemDato) {
                if ($itemDato['tipo'] == 9) {
                    $fechaEmision = date_format((date_create($itemDato['valor'])), 'd/m/Y');
                }
            }
        }
        $respuesta->detalleDocumento = $documentoDetalle;
        //FIN OBTENER DATA UNIDAD MEDIDA

        return $respuesta;
    }

    function obtenerNumeroNotaCredito($documentoTipoId, $documentoRelacionadoTipo)
    {

        $respuesta = DocumentoNegocio::create()->obtenerNumeroNotaCredito($documentoTipoId, $documentoRelacionadoTipo);

        return $respuesta;
    }

    function obtenerDocumentoRelacionDua($documentoTipoOrigenId, $documentoTipoDestinoId, $movimientoId, $documentoId, $opcionId, $documentoRelacionados)
    {

        $respuesta = $this->obtenerDocumentoRelacion($documentoTipoOrigenId, $documentoTipoDestinoId, $movimientoId, $documentoId, $opcionId, $documentoRelacionados);

        // Obtenemos las cantidades de la GR relacionada a la OC que estamos copiando
        $gr = MovimientoBien::create()->obtenerGRCantidadesPorOCId($documentoId);
        if (!ObjectUtil::isEmpty($gr) && !ObjectUtil::isEmpty($respuesta->detalleDocumento)) {
            foreach ($respuesta->detalleDocumento as $iDD => $itemDD) {
                foreach ($gr as $itemGR) {
                    if ($itemDD["bien_id"] == $itemGR["bien_id"] && $itemDD["unidad_medida_id"] == $itemGR["unidad_medida_id"]) {
                        $respuesta->detalleDocumento[$iDD]["cantidad"] = $itemGR["cantidad"];
                        break;
                    }
                }
            }
        }

        return $respuesta;
    }

    private function validarStockDocumento($documentoDetalle, $movimientoTipoId)
    {

        $tamanhoDetalle = count($documentoDetalle);
        $organizadoresEmpresa = OrganizadorNegocio::create()->obtenerXMovimientoTipo($movimientoTipoId);

        for ($i = 0; $i < $tamanhoDetalle; $i++) {

            if ($this->verificarOrganizadorPertenece($documentoDetalle[$i]['organizador_id'], $organizadoresEmpresa)) {
                $stock = BienNegocio::create()->obtenerStockBase($documentoDetalle[$i]['organizador_id'], $documentoDetalle[$i]['bien_id']);
                $stockControlar = (!ObjectUtil::isEmpty($stock)) ? $stock[0]["stock"] : 0;
                if ((floatval($stockControlar) - floatval($documentoDetalle[$i]['cantidad'])) < 0) {
                    $stockOrganizadores = BienNegocio::create()->obtenerStockOrganizadoresXEmpresa(
                        $documentoDetalle[$i]['bien_id'],
                        $documentoDetalle[$i]['unidad_medida_id'],
                        $movimientoTipoId
                    );

                    $documentoDetalle[$i]['stock_organizadores'] = $stockOrganizadores;
                } else {
                    $documentoDetalle[$i]['stock_organizadores'] = null;
                }
            } else {
                $stockOrganizadores = BienNegocio::create()->obtenerStockOrganizadoresXEmpresa(
                    $documentoDetalle[$i]['bien_id'],
                    $documentoDetalle[$i]['unidad_medida_id'],
                    $movimientoTipoId
                );

                $documentoDetalle[$i]['stock_organizadores'] = $stockOrganizadores;
            }
        }

        return $documentoDetalle;
    }

    function verificarOrganizadorPertenece($organizador, $organizadores)
    {
        if (ObjectUtil::isEmpty($organizador)) {
            return false;
        }
        $bandera = false;
        foreach ($organizadores as $org) {
            if ($org['id'] == $organizador) {
                $bandera = true;
            }
        }

        return $bandera;
    }

    function obtenerDocumentoRelacionDetalle($movimientoId, $documentoId, $opcionId, $documentoRelacionados)
    {

        $banderaMerge = 0;
        $arrayDetalle = array();

        $tamanhoArrayRelacionado = count($documentoRelacionados);
        if (!ObjectUtil::isEmpty($movimientoId) && !ObjectUtil::isEmpty($documentoId)) {
            $documentoRelacionados[$tamanhoArrayRelacionado]['movimientoId'] = $movimientoId;
            $documentoRelacionados[$tamanhoArrayRelacionado]['documentoId'] = $documentoId;
        }

        $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);
        $transferenciaTipo = $movimientoTipo[0]["transferencia_tipo"];

        foreach ($documentoRelacionados as $documentoRelacion) {
            if ($transferenciaTipo == 2) {
                $documentoDetalle = MovimientoBien::create()->obtenerDetalleTransferenciaXIdMovimiento($documentoRelacion['movimientoId']);
            } else {
                //OBTENEMOS LOS DOCUMENTOS HIJOS DE LA COPIA
                $documentoRelacionHijos = DocumentoNegocio::create()->obtenerRelacionesDocumento($documentoRelacion['documentoId']);
                $movimientoIdHijos = '';
                if (!ObjectUtil::isEmpty($documentoRelacionHijos)) {
                    foreach ($documentoRelacionHijos as $itemRel) {
                        if (!ObjectUtil::isEmpty($itemRel['movimiento_id'])) {
                            $movimientoIdHijos = $movimientoIdHijos . $itemRel['movimiento_id'] . ',';
                        }
                    }
                }

                if ($movimientoIdHijos != '') {
                    //OBTIENE CON PRECIOS DE LOS DOCUMENTOS HIJOS
                    $documentoDetalle = MovimientoBien::create()->obtenerXIdMovimiento($documentoRelacion['movimientoId']);
                } else {
                    $documentoDetalle = MovimientoBien::create()->obtenerXIdMovimiento($documentoRelacion['movimientoId']);
                }
            }

            //$documentoDetalle = MovimientoBien::create()->obtenerXIdMovimiento($documentoRelacion['movimientoId']);

            $tamanhioArrayDetalle = count($arrayDetalle);

            foreach ($documentoDetalle as $detalle) {
                $i = 0;
                while ($i < $tamanhioArrayDetalle && $banderaMerge == 0) {
                    if ($detalle['bien_id'] == $arrayDetalle[$i]['bien_id'] && $detalle['unidad_medida_id'] == $arrayDetalle[$i]['unidad_medida_id']) {
                        $arrayDetalle[$i]['cantidad'] = $arrayDetalle[$i]['cantidad'] + $detalle['cantidad'];
                        $arrayDetalle[$i]['valor_monetario'] = $detalle['valor_monetario'];
                        $banderaMerge = 1;
                    }

                    $i++;
                }

                if ($banderaMerge == 0) {
                    //obtener datos de: movimiento_bien_detalle
                    $resMovimientoBienDetalle = MovimientoBien::create()->obtenerMovimientoBienDetalleXMovimientoBienId($detalle['movimiento_bien_id']);

                    array_push($arrayDetalle, $this->getDocumentoACopiarMerge(
                        $detalle['organizador_descripcion'],
                        $detalle['organizador_id'],
                        $detalle['cantidad'],
                        $detalle['bien_descripcion'],
                        $detalle['bien_id'],
                        $detalle['valor_monetario'],
                        $detalle['unidad_medida_id'],
                        $detalle['unidad_medida_descripcion'],
                        $detalle['precio_tipo_id'],
                        $resMovimientoBienDetalle,
                        $detalle['movimiento_bien_comentario']
                    ));
                }
                $banderaMerge = 0;
            }
            $banderaMerge = 0;
        }

        $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);
        if (ObjectUtil::isEmpty($movimientoTipo)) {
            throw new WarningException("No se encontró el movimiento asociado a esta opción");
        }
        $movimientoTipoId = $movimientoTipo[0]["id"];

        //        $respuesta = new ObjectUtil();
        //        return $this->validarStockDocumento($arrayDetalle, $movimientoTipoId);
        return $arrayDetalle;
        //        return $respuesta;
    }

    private function getDocumentoACopiarMerge($organizadorDescripcion, $organizadorId, $cantidad, $bienDescripcion, $bienId, $valorMonetario, $unidadMedidaId, $unidadMedidaDescripcion, $precioTipoId, $movimientoBienDetalle, $movimientoBienComentario)
    {

        $detalle = array(
            "organizador_descripcion" => $organizadorDescripcion,
            "organizador_id" => $organizadorId,
            "cantidad" => $cantidad,
            "bien_descripcion" => $bienDescripcion,
            "bien_id" => $bienId,
            "unidad_medida_id" => $unidadMedidaId,
            "unidad_medida_descripcion" => $unidadMedidaDescripcion,
            "valor_monetario" => $valorMonetario,
            "precio_tipo_id" => $precioTipoId,
            "movimiento_bien_detalle" => $movimientoBienDetalle,
            "movimiento_bien_comentario" => $movimientoBienComentario
        );

        return $detalle;
    }

    public function obtenerDocumentosRelacionados($documentoId)
    {

        return DocumentoNegocio::create()->obtenerDocumentosRelacionadosXDocumentoId($documentoId);
    }

    public function enviarCorreoDocumentoConFechaEmisionAnterior($documentoId, $movimientoId, $usuarioId)
    {
        $plantillaId = 17;
        $plantilla = EmailPlantillaNegocio::create()->obtenerEmailPlantillaXID($plantillaId);
        $correosPlantilla = EmailPlantillaNegocio::create()->obtenerEmailXDestinatario($plantilla[0]["destinatario"], null, null);

        $correos = '';
        foreach ($correosPlantilla as $email) {
            $correos = $correos . $email . ';';
        }

        $data = MovimientoNegocio::create()->visualizarDocumento($documentoId, $movimientoId);

        //dibujar cuerpo y detalle
        $nombreDocumentoTipo = '';
        $dataDocumento = '';

        // datos de documento
        if (!ObjectUtil::isEmpty($data->dataDocumento)) {

            $nombreDocumentoTipo = $data->dataDocumento[0]['nombre_documento'];

            // Mostraremos la data en filas de dos columnas            
            foreach ($data->dataDocumento as $index => $item) {
                $html = '<tr><td style=\'text-align:left;padding:0 25px 5px;font-size:14px;line-height:1.5;width:80%\'><b>' . $item['descripcion'] . ': </b>';

                $valor = $item['valor'];

                if (!ObjectUtil::isEmpty($valor)) {
                    switch ((int) $item['tipo']) {
                        case 3:
                        case 9:
                        case 10:
                        case 11:
                            $time = strtotime($valor);
                            $valor = date('d/m/Y', $time);
                            break;
                        case 1:
                        case 14:
                        case 15:
                        case 16:
                        case 19:
                            $valor = number_format($valor, 2, ".", ",");
                            break;
                    }
                }

                $html = $html . $valor;

                $html = $html . '</td></tr>';
                $dataDocumento = $dataDocumento . $html;
            }
        }

        // detalle de documento
        //obtener configuracion de las columnas de movimiento_tipo
        $res = MovimientoTipoNegocio::create()->obtenerXDocumentoId($documentoId);
        if (!ObjectUtil::isEmpty($res)) {
            $movimientoTipoId = $res[0]['movimiento_tipo_id'];
            $dataMovimientoTipoColumna = MovimientoTipoNegocio::create()->obtenerMovimientoTipoColumna($movimientoTipoId);
        }

        //dibujando la cabecera
        $dataDetalle = '<table  border=1 cellspacing=0 cellpadding=5 bordercolor="000000"  width="80%">
                        <thead>';

        $html = '<tr>';
        if ($this->existeColumnaCodigo($dataMovimientoTipoColumna, 15)) { //Organizador
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Organizador</th>";
        }
        $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Cantidad</th>";
        $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Unidad</th>";
        $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Descripcion</th>";

        if ($this->existeColumnaCodigo($dataMovimientoTipoColumna, 5)) { //Precio unitario
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>PU</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Total</th>";
        }
        $html = $html . '</tr>';

        $dataDetalle = $dataDetalle . $html;
        $dataDetalle = $dataDetalle . '<thead>';
        $dataDetalle = $dataDetalle . '<tbody>';

        if (!ObjectUtil::isEmpty($data->detalleDocumento)) {
            foreach ($data->detalleDocumento as $index => $item) {

                $html = '<tr>';
                if ($this->existeColumnaCodigo($dataMovimientoTipoColumna, 15)) { //Organizador
                    $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item->organizador;
                }
                $html = $html . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . number_format($item->cantidad, 2, ".", ",");
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item->unidadMedida;
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item->descripcion;

                if ($this->existeColumnaCodigo($dataMovimientoTipoColumna, 5)) { //Precio unitario
                    $html = $html . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . number_format($item->precioUnitario, 2, ".", ",");
                    $html = $html . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . number_format($item->importe, 2, ".", ",");
                }
                $html = $html . '</tr>';

                $dataDetalle = $dataDetalle . $html;
            }
        }
        $dataDetalle = $dataDetalle . '</tbody></table>';

        $comentarioFinalDocumento = '<tr><td style="text-align: left; padding: 0 25px 10px; font-size: 14px; line-height: 1.5; width: 80%">Documento generado con fecha de emisión anterior a la fecha actual, registrado en la empresa '
            . $data->direccionEmpresa[0]['razon_social']
            . ' ubicada en '
            . $data->direccionEmpresa[0]['direccion']
            . '</td></tr>';
        //fin dibujo

        $comentarioDocumento = $data->comentarioDocumento[0]['comentario_documemto'];
        //logica correo:                             
        $asunto = $plantilla[0]["asunto"];
        $cuerpo = $plantilla[0]["cuerpo"];

        $cuerpo = str_replace("[|documento_tipo|]", $nombreDocumentoTipo, $cuerpo);
        $cuerpo = str_replace("[|dato_documento|]", $dataDocumento, $cuerpo);
        $cuerpo = str_replace("[|detalle_documento|]", $dataDetalle, $cuerpo);
        $cuerpo = str_replace("[|comentario_documento|]", $comentarioDocumento, $cuerpo);
        $cuerpo = str_replace("[|comentario_final_documento|]", $comentarioFinalDocumento, $cuerpo);

        $res = EmailEnvioNegocio::create()->insertarEmailEnvio($correos, $asunto, $cuerpo, 1, $usuarioId);
        return 1;
    }

    public function obtenerBienesConStockMenorACantidadMinima($personaId, $organizadorId, $empresaId, $monedaId, $operador)
    {

        $data = BienNegocio::create()->obtenerBienesConStockMenorACantidadMinima($personaId, $organizadorId, $empresaId, $monedaId, $operador);
        return $data;
    }

    public function verificarTipoUnidadMedidaParaTramo($unidadMedidaId)
    {
        $data = UnidadMedidaTipo::create()->verificarTipoUnidadMedidaParaTramo($unidadMedidaId);
        return $data;
    }

    public function registrarTramoBien($unidadMedidaId, $cantidadTramo, $bienId, $usuCreacion)
    {
        $data = Movimiento::create()->registrarTramoBien($unidadMedidaId, $cantidadTramo, $bienId, $usuCreacion);
        return $data;
    }

    public function obtenerTramoBien($bienId)
    {
        $data = Movimiento::create()->obtenerTramoBienXBienId($bienId);
        return $data;
    }

    public function editarComentarioDocumento($documentoId, $comentario)
    {
        $res = DocumentoNegocio::create()->actualizarComentarioDocumento($documentoId, $comentario);
        return $res;
    }

    public function obtenerDocumentoTipoDatoEditableXDocumentoId($documentoId)
    {
        $data = DocumentoTipoNegocio::create()->obtenerDocumentoTipoDatoEditableXDocumentoId($documentoId);

        return $data;
    }

    public function guardarEdicionDocumento($documentoId, $camposDinamicos, $comentario = null, $periodoId = null, $tipoPago = null, $monedaId = null, $usuarioId, $contOperacionTipoId = null, $afectoAImpuesto = null, $tipoEdicion = null, $datosExtras = null, $acCajaId = null)
    {
        return DocumentoNegocio::create()->guardarEdicionDocumento($documentoId, $camposDinamicos, $comentario, $periodoId, $tipoPago, $monedaId, $usuarioId, $contOperacionTipoId, $afectoAImpuesto, $tipoEdicion, $datosExtras, $acCajaId);
    }

    public function enviarMovimientoEmailPDF($correo, $documentoId, $comentario, $usuarioId, $plantillaId)
    {

        $dataDocumentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXDocumentoId($documentoId);
        $documentoTipoId = $dataDocumentoTipo[0]['id'];
        $data = MovimientoNegocio::create()->imprimir($documentoId, $documentoTipoId);
        $dataDocumento = $data->dataDocumento;

        $serieDocumento = '';
        if (!ObjectUtil::isEmpty($dataDocumento[0]['serie'])) {
            $serieDocumento = $dataDocumento[0]['serie'] . " - ";
        }

        $dataPersona = DocumentoTipoDatoNegocio::create()->obtenerDocumentoTipoDatoXDocumentoIdXTipo($documentoId, 5);
        $descripcionPersona = $dataPersona[0]['descripcion'];
        $titulo = strtoupper($dataDocumento[0]['documento_tipo_descripcion']) . " " . $serieDocumento . $dataDocumento[0]['numero'];

        $plantilla = EmailPlantillaNegocio::create()->obtenerEmailPlantillaXID($plantillaId); // antes 7

        $hoy = date("Y_m_d_H_i_s");
        $url = __DIR__ . '/../../vistas/com/movimiento/documentos/documento_' . $hoy . '_' . $usuarioId . '.pdf';

        //crear PDF
        $this->generarDocumentoPDF($documentoId, $comentario, 'F', $url, $data);

        //envio de email
        $email = new EmailEnvioUtil();

        $asunto = $titulo;
        $cuerpo = $plantilla[0]["cuerpo"];

        $cuerpo = str_replace("[|titulo|]", $dataDocumentoTipo[0]['descripcion'], $cuerpo);
        $cuerpo = str_replace("[|descripcion_persona|]", strtolower($descripcionPersona), $cuerpo);
        $cuerpo = str_replace("[|nombre_persona|]", $dataDocumento[0]['nombre'], $cuerpo);
        $cuerpo = str_replace("[|nombre_documento|]", $dataDocumentoTipo[0]['descripcion'], $cuerpo);
        $cuerpo = str_replace("[|serie_numero|]", $serieDocumento . $dataDocumento[0]['numero'], $cuerpo);
        $nombreArchivo = $dataDocumentoTipo[0]['descripcion'] . ".pdf";

        $res = EmailEnvioNegocio::create()->insertarEmailEnvio($correo, $asunto, $cuerpo, 1, $usuarioId, $url, $nombreArchivo);

        if (!ObjectUtil::isEmpty($res[0]['id'])) {
            $this->setMensajeEmergente("Se enviará el correo aproximadamente en un minuto");
        }

        return $res;
    }

    public function generarDocumentoPDF($documentoId, $comentario, $tipoSalidaPDF, $url, $data)
    {
        require_once __DIR__ . '/../../controlador/commons/tcpdf/config/lang/eng.php';
        require_once __DIR__ . '/../../controlador/commons/tcpdf/tcpdf.php';

        //$tipoSalidaPDF: F-> guarda local        
        $dataDocumento = $data->dataDocumento;

        // create new PDF document

        $identificadorNegocio = $dataDocumento[0]['identificador_negocio'];

        switch ((int) $identificadorNegocio) {
            case 1: //generar pdf Cotizacion
                return $this->generarDocumentoPDFCotizacion($documentoId, $comentario, $tipoSalidaPDF, $url, $data);
            case 6: //guia remision
                return $this->generarDocumentoPDFGuiaRemision($documentoId, $comentario, $tipoSalidaPDF, $url, $data);
            case 8: //generar pdf orden de compra
                return $this->generarDocumentoPDFOrdenCompra($documentoId, $comentario, $tipoSalidaPDF, $url, $data);

            case 10: //generar pdf orden de compra extranjera
                return $this->generarDocumentoPDFOrdenCompraExt($documentoId, $comentario, $tipoSalidaPDF, $url, $data);

            case 11: //generar pdf solicitud de compra
                return $this->generarDocumentoPDFSolicitudCompra($documentoId, $comentario, $tipoSalidaPDF, $url, $data);

            case 12: //generar pdf solicitud de compra extranjera
                return $this->generarDocumentoPDFSolicitudCompraExt($documentoId, $comentario, $tipoSalidaPDF, $url, $data);

            case 13: //generar pdf Cotizacion compra
                return $this->generarDocumentoPDFCotizacionCompra($documentoId, $comentario, $tipoSalidaPDF, $url, $data);

            case 14: //generar pdf Cotizacion compra extranjera
                return $this->generarDocumentoPDFCotizacionCompraEXT($documentoId, $comentario, $tipoSalidaPDF, $url, $data);

            case 23: //generar pdf Guia interna para transferencia
                return $this->generarDocumentoPDFGuiaInterna($documentoId, $comentario, $tipoSalidaPDF, $url, $data);

            default:
                return $this->generarDocumentoPDFEstandar($documentoId, $comentario, $tipoSalidaPDF, $url, $data);
        }
    }

    public function generarDocumentoPDFGuiaInterna($documentoId, $comentario, $tipoSalidaPDF, $url, $data)
    {
        //$tipoSalidaPDF: F-> guarda local
        //obtenemos la data        
        $dataDocumentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXDocumentoId($documentoId);
        $documentoTipoId = $dataDocumentoTipo[0]['id'];

        $dataDocumento = $data->dataDocumento;
        $documentoDatoValor = $data->documentoDatoValor;
        $detalle = $data->detalle;
        $dataEmpresa = $data->dataEmpresa;
        $dataMovimientoTipoColumna = $data->movimientoTipoColumna;
        $dataDocumentoRelacion = $data->documentoRelacionado;

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator('Imagina Technologies S.A.C.');
        $pdf->SetAuthor('Imagina Technologies S.A.C.');
        $pdf->SetTitle(strtoupper($dataDocumentoTipo[0]['descripcion']));

        //        $pdf->SetHeaderData('logo.PNG', PDF_HEADER_LOGO_WIDTH,strtoupper($dataDocumentoTipo[0]['descripcion']),$dataEmpresa[0]['razon_social']."\n".$dataEmpresa[0]['direccion'], array(0,0,0), array(0,0,0));
        $cabeceraPDF1 = $dataEmpresa[0]['razon_social'] //                ."\nSoluciones Integrales de Perforación"
        ;

        $cabeceraPDF2 = $dataEmpresa[0]['direccion'] . "\n" .
            "Telfs: 044-728609\n" .
            "E-mail: gcardenas@bhdrillingtools.com Web site: www.bhdrillingtools.com\n" .
            "RUC: " . $dataEmpresa[0]['ruc'];
        $PDF_HEADER_LOGO_WIDTH = 40;
        $pdf->SetHeaderData('logo.PNG', $PDF_HEADER_LOGO_WIDTH, $cabeceraPDF1, $cabeceraPDF2, array(0, 0, 0), array(0, 0, 0));

        $pdf->setFooterData(array(0, 0, 0), array(0, 0, 0)); // 0,0,0
        // set header and footer fonts
        $PDF_FONT_SIZE_MAIN = 9;
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', $PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // --------------GENERAR PDF-------------------------------------------
        $pdf->SetFont('helvetica', '', 9);
        $pdf->AddPage();

        $serieDocumento = '';
        if (!ObjectUtil::isEmpty($dataDocumento[0]['serie'])) {
            $serieDocumento = $dataDocumento[0]['serie'] . " - ";
        }

        $titulo = strtoupper($dataDocumento[0]['documento_tipo_descripcion']) . " " . $serieDocumento . $dataDocumento[0]['numero'];

        $pdf->Ln(5);
        $pdf->writeHTMLCell(0, 0, '', '', "<h2>" . $titulo . "</h2>", 0, 1, 0, true, 'C', true);
        $pdf->Ln(5);

        //obtener la descripcion de persona de documento_tipo_dato        
        $dataPersona = DocumentoTipoDatoNegocio::create()->obtenerDocumentoTipoDatoXDocumentoIdXTipo($documentoId, 5);
        $descripcionPersona = $dataPersona[0]['descripcion'];

        $dataFechaEmision = DocumentoTipoDatoNegocio::create()->obtenerDocumentoTipoDatoXDocumentoIdXTipo($documentoId, 9);
        $descripcionFechaEmision = $dataFechaEmision[0]['descripcion'];

        $fecha = date_format((date_create($dataDocumento[0]['fecha_emision'])), 'd/m/Y');
        if (!ObjectUtil::isEmpty($dataDocumento[0]['fecha_emision'])) {
            $pdf->Cell(0, 0, $descripcionFechaEmision . ": " . $fecha, 0, 1, 'L', 0, '', 0);
        }
        if (!ObjectUtil::isEmpty($dataDocumento[0]['nombre'])) {
            $pdf->Cell(0, 0, $descripcionPersona . ": " . $dataDocumento[0]['nombre'], 0, 1, 'L', 0, '', 0);
        }
        if (!ObjectUtil::isEmpty($dataDocumento[0]['direccion'])) {
            $pdf->Cell(0, 0, "Dirección: " . $dataDocumento[0]['direccion'], 0, 1, 'L', 0, '', 0);
        }
        if (!ObjectUtil::isEmpty($dataDocumento[0]['codigo_identificacion'])) {
            $pdf->Cell(0, 0, $dataDocumento[0]['persona_documento_tipo'] . ": " . $dataDocumento[0]['codigo_identificacion'], 0, 1, 'L', 0, '', 0);
        }
        if (!ObjectUtil::isEmpty($dataDocumento[0]['descripcion'])) {
            $pdf->Cell(0, 0, "Descripción: " . $dataDocumento[0]['descripcion'], 0, 1, 'L', 0, '', 0);
        }
        if ($dataDocumento[0]['identificador_negocio'] != 23) {
            $pdf->Cell(0, 0, "Moneda: " . $dataDocumento[0]['moneda_descripcion'], 0, 1, 'L', 0, '', 0);
        }
        if (!ObjectUtil::isEmpty($dataDocumento[0]['fecha_vencimiento'])) {
            $pdf->Cell(0, 0, "Fecha de vencimiento: " . date_format((date_create($dataDocumento[0]['fecha_vencimiento'])), 'd/m/Y'), 0, 1, 'L', 0, '', 0);
        }
        if (!ObjectUtil::isEmpty($dataDocumento[0]['fecha_tentativa'])) {
            $pdf->Cell(0, 0, "Fecha tentativa: " . date_format((date_create($dataDocumento[0]['fecha_tentativa'])), 'd/m/Y'), 0, 1, 'L', 0, '', 0);
        }
        $origen = '';
        $destino = '';
        foreach ($documentoDatoValor as $indice => $item) {
            if ($item['documento_tipo_id'] == 2887) {
                $origen = $item['valor'];
            }
            if ($item['documento_tipo_id'] == 2888) {
                $destino = $item['valor'];
            }
        }

        if (ObjectUtil::isEmpty($origen)) {
            $origen = $dataDocumento[0]['org_origen_desc'];
        }
        if (ObjectUtil::isEmpty($destino)) {
            $destino = $dataDocumento[0]['org_destino_desc'];
        }

        $pdf->Cell(0, 0, "Origen: " . $origen, 0, 1, 'L', 0, '', 0);
        $pdf->Cell(0, 0, "Destino: " . $destino, 0, 1, 'L', 0, '', 0);

        $espacioComentario = 0;
        if (!ObjectUtil::isEmpty($comentario)) {
            $pdf->Ln(5);
            $pdf->writeHTMLCell(0, 0, '', '', $comentario, 0, 1, 0, true, 'L', true);
            $espacioComentario = 12;
        }

        $pdf->Ln(5);
        $pdf->writeHTMLCell(0, 0, '', '', "<h4>DETALLE DEL DOCUMENTO</h4>", 0, 1, 0, true, 'L', true);

        //espacio
        $pdf->Ln(5);

        //detalle
        $esp = '&nbsp;&nbsp;'; //espacio en blanco
        $existeColumnaPrecio = $this->existeColumnaCodigo($dataMovimientoTipoColumna, 5);

        $cont = 0;
        if ($existeColumnaPrecio) {
            $tabla = '<table cellspacing="0" cellpadding="1" border="1">
                    <tr>
                        <th style="text-align:center;" width="8%"><b>Cant.</b></th>
                        <th style="text-align:center;" width="12%"><b>Código</b></th>
                        <th style="text-align:center;" width="42%"><b>Descripción.</b></th>
                        <th style="text-align:center;" width="13%"><b>Unid.</b></th>
                        <th style="text-align:center;" width="12%"><b>P. Unit.</b></th>
                        <th style="text-align:center;" width="13%"><b>P. Total</b></th>
                    </tr>
                ';

            foreach ($detalle as $item) {
                $cont++;
                if (strlen($item->descripcion) > 39) {
                    $cont++;
                }

                $tabla = $tabla . '<tr>'
                    . '<td style="text-align:rigth"  width="8%">' . $esp . round($item->cantidad, 2) . $esp . '</td>'
                    . '<td style="text-align:left"  width="12%">' . $esp . $item->bien_codigo . $esp . '</td>'
                    . '<td style="text-align:left"  width="42%">' . $esp . $item->descripcion . $esp . '</td>'
                    . '<td style="text-align:center"  width="13%">' . $esp . $item->unidadMedida . $esp . '</td>'
                    . '<td style="text-align:rigth"  width="12%">' . $esp . number_format($item->precioUnitario, 2) . $esp . '</td>'
                    . '<td style="text-align:rigth"  width="13%">' . $esp . number_format($item->importe, 2) . $esp . '</td>'
                    . '</tr>';
            };

            if (!ObjectUtil::isEmpty($dataDocumento[0]['total'])) {
                $tabla = $tabla . '<tr>'
                    . '<td style="text-align:rigth;;"  width="75%" colspan="4"  ></td>'
                    . '<td style="text-align:center"  width="12%">TOTAL ' . $dataDocumento[0]['moneda_simbolo'] . '</td>'
                    . '<td style="text-align:rigth"  width="13%">' . $esp . number_format($dataDocumento[0]['total'], 2) . $esp . '</td>'
                    . '</tr>';
            }
        } else {
            $tabla = '<table cellspacing="0" cellpadding="1" border="1">
                    <tr>
                        <th style="text-align:center;" width="13%"><b>Cant.</b></th>
                        <th style="text-align:center;" width="16%"><b>Código</b></th>
                        <th style="text-align:center;" width="50%"><b>Descripción.</b></th>
                        <th style="text-align:center;" width="21%"><b>Unid.</b></th>
                    </tr>
                ';

            foreach ($detalle as $item) {
                $cont++;
                if (strlen($item->descripcion) > 39) {
                    $cont++;
                }

                $tabla = $tabla . '<tr>'
                    . '<td style="text-align:rigth"  width="13%">' . $esp . round($item->cantidad, 2) . $esp . '</td>'
                    . '<td style="text-align:left"  width="16%">' . $esp . $item->bien_codigo . $esp . '</td>'
                    . '<td style="text-align:left"  width="50%">' . $esp . $item->descripcion . $esp . '</td>'
                    . '<td style="text-align:center"  width="21%">' . $esp . $item->unidadMedida . $esp . '</td>'
                    . '</tr>';
            };
        }

        //fin tabla detalle
        $tabla = $tabla . '</table>';

        $pdf->writeHTML($tabla, true, false, false, false, 'C');

        $espaciado = 131 - ($cont * 4.4 + $espacioComentario);
        if ($espaciado < 15) {
            //            $espaciado = 15;
            $pdf->AddPage();
        }

        //        $pdf->Ln($espaciado); // cada fila es 4, total=131 , antes:115      

        if (!ObjectUtil::isEmpty($documentoDatoValor)) {
            $pdf->Ln(5);
            $pdf->Ln(5);

            if ($identificadorNegocio == 1) {
                $pdf->writeHTMLCell(0, 6, '', '', "<h4>TERMINOS Y CONDICIONES</h4>", 'TB', 1, 0, true, 'C', true);
            } else {;
                $pdf->writeHTMLCell(0, 6, '', '', "<h4>OTROS DATOS DEL DOCUMENTO</h4>", 'TB', 1, 0, true, 'C', true);
            }

            $pdf->Ln(1);
            $pdf->SetFillColor(255, 255, 255);
            foreach ($documentoDatoValor as $indice => $item) {
                if ($item['documento_tipo_id'] == 2870) {
                    if (
                        $dataDocumentoTipo[0]['identificador_negocio'] == 23 &&
                        ($item['documento_tipo_id'] == 2887 || $item['documento_tipo_id'] == 2888) && $item['valor'] == 'Virtual'
                    ) {
                        //NO MOSTRAR LA DIRECCION
                        $txtDescripcion = $item['descripcion'];
                        $valorItem = $item['valor'];
                    } else {
                        $txtDescripcion = $item['descripcion'];
                        $valorItem = $item['valor'];

                        if ($item['tipo'] == 1) {
                            $valorItem = number_format($valorItem, 2, ".", ",");
                        }

                        if ($item['tipo'] == 3) {
                            $valorItem = date_format((date_create($valorItem)), 'd/m/Y');
                        }

                        $pdf->MultiCell(60, 0, $txtDescripcion, 0, 'L', 1, 0, '', '', true, 0, false, true, 40, 'T');
                        $pdf->MultiCell(110, 0, $valorItem, 0, 'L', 1, 0, '', '', true, 0, false, true, 40, 'T');

                        //                        if ($indice < count($documentoDatoValor) - 1 || $identificadorNegocio == 1) {
                        //                            if (strlen($valorItem) > 55) {
                        //                                $pdf->Ln(10);
                        //                            } else {
                        //                                $pdf->Ln(6);
                        //                            }
                        //                        }
                        //                        if ($indice == count($documentoDatoValor) - 1) {
                        $pdf->Ln(1);
                        //                        }
                    }
                }
            };
            $pdf->writeHTMLCell(0, 1, '', '', "", 'B', 1, 0, true, 'C', true);
        }


        $documentosRelacionImprimir = array();
        $dataDocumentoTipo;
        if ($dataDocumentoTipo[0]['identificador_negocio'] == 22) { //GUIA DE RECEPCION            
            foreach ($dataDocumentoRelacion as $index => $itemR) {
                if ($itemR['identificador_negocio'] == 10) {
                    $itemDR = array('serie_num' => $itemR['serie_numero_original'], 'nombre_doc' => $itemR['documento_tipo_descripcion']);
                    array_push($documentosRelacionImprimir, $itemDR);

                    $relacionOC = MovimientoNegocio::create()->obtenerDocumentosRelacionados($itemR['id']);

                    foreach ($relacionOC as $itemROC) {
                        if ($itemROC['identificador_negocio'] == 21 || $itemROC['identificador_negocio'] == 24) { //21: DUA  24: Commercial Invoice
                            $itemDR = array('serie_num' => $itemROC['serie_numero'], 'nombre_doc' => $itemROC['documento_tipo'], 'serie_numero_original' => $itemROC['serie_numero_original']);
                            array_push($documentosRelacionImprimir, $itemDR);
                        }
                    }
                }
            }
        }

        if ($dataDocumentoTipo[0]['identificador_negocio'] == 23) { //GUIA INTERNA DE TRANSFERENCIA                         
            $relacionDoc = MovimientoNegocio::create()->obtenerDocumentosRelacionados($documentoId);

            foreach ($relacionDoc as $itemDoc) {
                $itemDR = array('serie_num' => $itemDoc['serie_numero'], 'nombre_doc' => $itemDoc['documento_tipo'], 'serie_numero_original' => $itemDoc['serie_numero_original']);
                array_push($documentosRelacionImprimir, $itemDR);
            }
        }

        if (!ObjectUtil::isEmpty($documentosRelacionImprimir)) {
            $pdf->Ln(5);

            $pdf->Ln(5);

            $pdf->writeHTMLCell(0, 6, '', '', "<h4>DOCUMENTOS RELACIONADOS</h4>", 'TB', 1, 0, true, 'C', true);

            $pdf->Ln(1);
            $pdf->SetFillColor(255, 255, 255);
            foreach ($documentosRelacionImprimir as $indice => $item) {
                $txtDescripcion = $item['nombre_doc'];
                $serieNum = $item['serie_num'];
                $serieNumOriginal = $item['serie_numero_original'];

                $pdf->MultiCell(60, 0, $txtDescripcion, 0, 'L', 1, 0, '', '', true, 0, false, true, 40, 'T');
                $pdf->MultiCell(30, 0, $serieNum, 0, 'C', 1, 0, '', '', true, 0, false, true, 40, 'T'); // 110

                if (!ObjectUtil::isEmpty($serieNumOriginal)) {
                    $pdf->MultiCell(10, 0, ' | ', 0, 'C', 1, 0, '', '', true, 0, false, true, 40, 'T');
                    $pdf->MultiCell(70, 0, $serieNumOriginal, 0, 'L', 1, 0, '', '', true, 0, false, true, 40, 'T');
                }

                if ($indice < count($documentosRelacionImprimir) - 1) {
                    if (strlen($serieNum) > 55) {
                        $pdf->Ln(10);
                    } else {
                        $pdf->Ln(6);
                    }
                }

                if ($indice == count($documentosRelacionImprimir) - 1) {
                    $pdf->Ln(1);
                }
            };
            $pdf->writeHTMLCell(0, 1, '', '', "", 'B', 1, 0, true, 'C', true);
        }


        ob_clean();

        if ($tipoSalidaPDF == 'F') {
            $pdf->Output($url, $tipoSalidaPDF);
        }

        return $titulo;
    }

    public function generarDocumentoPDFEstandar($documentoId, $comentario, $tipoSalidaPDF, $url, $data)
    {
        //$tipoSalidaPDF: F-> guarda local
        //obtenemos la data        
        $dataDocumentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXDocumentoId($documentoId);
        $documentoTipoId = $dataDocumentoTipo[0]['id'];

        $dataDocumento = $data->dataDocumento;
        $documentoDatoValor = $data->documentoDatoValor;
        $detalle = $data->detalle;
        $dataEmpresa = $data->dataEmpresa;
        $dataMovimientoTipoColumna = $data->movimientoTipoColumna;
        $dataDocumentoRelacion = $data->documentoRelacionado;

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator('Imagina Technologies S.A.C.');
        $pdf->SetAuthor('Imagina Technologies S.A.C.');
        $pdf->SetTitle(strtoupper($dataDocumentoTipo[0]['descripcion']));

        //        $pdf->SetHeaderData('logo.PNG', PDF_HEADER_LOGO_WIDTH,strtoupper($dataDocumentoTipo[0]['descripcion']),$dataEmpresa[0]['razon_social']."\n".$dataEmpresa[0]['direccion'], array(0,0,0), array(0,0,0));
        $cabeceraPDF1 = $dataEmpresa[0]['razon_social'] //                ."\nSoluciones Integrales de Perforación"
        ;

        $cabeceraPDF2 = $dataEmpresa[0]['direccion'] . "\n" .
            "Telfs: 044-728609\n" .
            "E-mail: gcardenas@bhdrillingtools.com Web site: www.bhdrillingtools.com\n" .
            "RUC: " . $dataEmpresa[0]['ruc'];
        $PDF_HEADER_LOGO_WIDTH = 40;
        $pdf->SetHeaderData('logo.PNG', $PDF_HEADER_LOGO_WIDTH, $cabeceraPDF1, $cabeceraPDF2, array(0, 0, 0), array(0, 0, 0));

        $pdf->setFooterData(array(0, 0, 0), array(0, 0, 0)); // 0,0,0
        // set header and footer fonts
        $PDF_FONT_SIZE_MAIN = 9;
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', $PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // --------------GENERAR PDF-------------------------------------------
        $pdf->SetFont('helvetica', '', 9);
        $pdf->AddPage();

        $serieDocumento = '';
        if (!ObjectUtil::isEmpty($dataDocumento[0]['serie'])) {
            $serieDocumento = $dataDocumento[0]['serie'] . " - ";
        }

        $titulo = strtoupper($dataDocumento[0]['documento_tipo_descripcion']) . " " . $serieDocumento . $dataDocumento[0]['numero'];

        $pdf->Ln(5);
        $pdf->writeHTMLCell(0, 0, '', '', "<h2>" . $titulo . "</h2>", 0, 1, 0, true, 'C', true);
        $pdf->Ln(5);

        //obtener la descripcion de persona de documento_tipo_dato        
        $dataPersona = DocumentoTipoDatoNegocio::create()->obtenerDocumentoTipoDatoXDocumentoIdXTipo($documentoId, 5);
        $descripcionPersona = $dataPersona[0]['descripcion'];

        $dataFechaEmision = DocumentoTipoDatoNegocio::create()->obtenerDocumentoTipoDatoXDocumentoIdXTipo($documentoId, 9);
        $descripcionFechaEmision = $dataFechaEmision[0]['descripcion'];

        $fecha = date_format((date_create($dataDocumento[0]['fecha_emision'])), 'd/m/Y');
        if (!ObjectUtil::isEmpty($dataDocumento[0]['fecha_emision'])) {
            $pdf->Cell(0, 0, $descripcionFechaEmision . ": " . $fecha, 0, 1, 'L', 0, '', 0);
        }
        if (!ObjectUtil::isEmpty($dataDocumento[0]['nombre'])) {
            $pdf->Cell(0, 0, $descripcionPersona . ": " . $dataDocumento[0]['nombre'], 0, 1, 'L', 0, '', 0);
        }
        if (!ObjectUtil::isEmpty($dataDocumento[0]['direccion'])) {
            $pdf->Cell(0, 0, "Dirección: " . $dataDocumento[0]['direccion'], 0, 1, 'L', 0, '', 0);
        }
        if (!ObjectUtil::isEmpty($dataDocumento[0]['codigo_identificacion'])) {
            $pdf->Cell(0, 0, $dataDocumento[0]['persona_documento_tipo'] . ": " . $dataDocumento[0]['codigo_identificacion'], 0, 1, 'L', 0, '', 0);
        }
        if (!ObjectUtil::isEmpty($dataDocumento[0]['descripcion'])) {
            $pdf->Cell(0, 0, "Descripción: " . $dataDocumento[0]['descripcion'], 0, 1, 'L', 0, '', 0);
        }
        if ($dataDocumento[0]['identificador_negocio'] != 23) {
            $pdf->Cell(0, 0, "Moneda: " . $dataDocumento[0]['moneda_descripcion'], 0, 1, 'L', 0, '', 0);
        }
        if (!ObjectUtil::isEmpty($dataDocumento[0]['fecha_vencimiento'])) {
            $pdf->Cell(0, 0, "Fecha de vencimiento: " . date_format((date_create($dataDocumento[0]['fecha_vencimiento'])), 'd/m/Y'), 0, 1, 'L', 0, '', 0);
        }
        if (!ObjectUtil::isEmpty($dataDocumento[0]['fecha_tentativa'])) {
            $pdf->Cell(0, 0, "Fecha tentativa: " . date_format((date_create($dataDocumento[0]['fecha_tentativa'])), 'd/m/Y'), 0, 1, 'L', 0, '', 0);
        }
        if ($dataDocumento[0]['identificador_negocio'] == 23) {
            $pdf->Cell(0, 0, "Origen: " . $dataDocumento[0]['org_origen_desc'], 0, 1, 'L', 0, '', 0);
            $pdf->Cell(0, 0, "Destino: " . $dataDocumento[0]['org_destino_desc'], 0, 1, 'L', 0, '', 0);
        }

        $espacioComentario = 0;
        if (!ObjectUtil::isEmpty($comentario)) {
            $pdf->Ln(5);
            $pdf->writeHTMLCell(0, 0, '', '', $comentario, 0, 1, 0, true, 'L', true);
            $espacioComentario = 12;
        }

        $pdf->Ln(5);
        $pdf->writeHTMLCell(0, 0, '', '', "<h4>DETALLE DEL DOCUMENTO</h4>", 0, 1, 0, true, 'L', true);

        //espacio
        $pdf->Ln(5);

        //detalle
        $esp = '&nbsp;&nbsp;'; //espacio en blanco
        $existeColumnaPrecio = $this->existeColumnaCodigo($dataMovimientoTipoColumna, 5);

        $cont = 0;
        if ($existeColumnaPrecio) {
            $tabla = '<table cellspacing="0" cellpadding="1" border="1">
                    <tr>
                        <th style="text-align:center;" width="8%"><b>Cant.</b></th>
                        <th style="text-align:center;" width="12%"><b>Código</b></th>
                        <th style="text-align:center;" width="42%"><b>Descripción.</b></th>
                        <th style="text-align:center;" width="13%"><b>Unid.</b></th>
                        <th style="text-align:center;" width="12%"><b>P. Unit.</b></th>
                        <th style="text-align:center;" width="13%"><b>P. Total</b></th>
                    </tr>
                ';

            foreach ($detalle as $item) {
                $cont++;
                if (strlen($item->descripcion) > 39) {
                    $cont++;
                }

                $tabla = $tabla . '<tr>'
                    . '<td style="text-align:rigth"  width="8%">' . $esp . round($item->cantidad, 2) . $esp . '</td>'
                    . '<td style="text-align:left"  width="12%">' . $esp . $item->bien_codigo . $esp . '</td>'
                    . '<td style="text-align:left"  width="42%">' . $esp . $item->descripcion . $esp . '</td>'
                    . '<td style="text-align:center"  width="13%">' . $esp . $item->unidadMedida . $esp . '</td>'
                    . '<td style="text-align:rigth"  width="12%">' . $esp . number_format($item->precioUnitario, 2) . $esp . '</td>'
                    . '<td style="text-align:rigth"  width="13%">' . $esp . number_format($item->importe, 2) . $esp . '</td>'
                    . '</tr>';
            };

            if (!ObjectUtil::isEmpty($dataDocumento[0]['total'])) {
                $tabla = $tabla . '<tr>'
                    . '<td style="text-align:rigth;;"  width="75%" colspan="4"  ></td>'
                    . '<td style="text-align:center"  width="12%">TOTAL ' . $dataDocumento[0]['moneda_simbolo'] . '</td>'
                    . '<td style="text-align:rigth"  width="13%">' . $esp . number_format($dataDocumento[0]['total'], 2) . $esp . '</td>'
                    . '</tr>';
            }
        } else {
            $tabla = '<table cellspacing="0" cellpadding="1" border="1">
                    <tr>
                        <th style="text-align:center;" width="13%"><b>Cant.</b></th>
                        <th style="text-align:center;" width="16%"><b>Código</b></th>
                        <th style="text-align:center;" width="50%"><b>Descripción.</b></th>
                        <th style="text-align:center;" width="21%"><b>Unid.</b></th>
                    </tr>
                ';

            foreach ($detalle as $item) {
                $cont++;
                if (strlen($item->descripcion) > 39) {
                    $cont++;
                }

                $tabla = $tabla . '<tr>'
                    . '<td style="text-align:rigth"  width="13%">' . $esp . round($item->cantidad, 2) . $esp . '</td>'
                    . '<td style="text-align:left"  width="16%">' . $esp . $item->bien_codigo . $esp . '</td>'
                    . '<td style="text-align:left"  width="50%">' . $esp . $item->descripcion . $esp . '</td>'
                    . '<td style="text-align:center"  width="21%">' . $esp . $item->unidadMedida . $esp . '</td>'
                    . '</tr>';
            };
        }

        //fin tabla detalle
        $tabla = $tabla . '</table>';

        $pdf->writeHTML($tabla, true, false, false, false, 'C');

        $espaciado = 131 - ($cont * 4.4 + $espacioComentario);
        if ($espaciado < 15) {
            //            $espaciado = 15;
            $pdf->AddPage();
        }

        //        $pdf->Ln($espaciado); // cada fila es 4, total=131 , antes:115      

        if (!ObjectUtil::isEmpty($documentoDatoValor)) {
            $pdf->Ln(5);
            //        $borde=array('LTRB' => array('width' => 1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));

            $pdf->Ln(5);

            if ($identificadorNegocio == 1) {
                $pdf->writeHTMLCell(0, 6, '', '', "<h4>TERMINOS Y CONDICIONES</h4>", 'TB', 1, 0, true, 'C', true);
            } else {;
                $pdf->writeHTMLCell(0, 6, '', '', "<h4>OTROS DATOS DEL DOCUMENTO</h4>", 'TB', 1, 0, true, 'C', true);
            }

            $pdf->Ln(1);
            $pdf->SetFillColor(255, 255, 255);
            foreach ($documentoDatoValor as $indice => $item) {
                if (
                    $dataDocumentoTipo[0]['identificador_negocio'] == 23 &&
                    ($item['documento_tipo_id'] == 2887 || $item['documento_tipo_id'] == 2888) && $item['valor'] == 'Virtual'
                ) {
                    //NO MOSTRAR LA DIRECCION
                    $txtDescripcion = $item['descripcion'];
                    $valorItem = $item['valor'];
                } else {
                    $txtDescripcion = $item['descripcion'];
                    $valorItem = $item['valor'];

                    if ($item['tipo'] == 1) {
                        $valorItem = number_format($valorItem, 2, ".", ",");
                    }

                    if ($item['tipo'] == 3) {
                        $valorItem = date_format((date_create($valorItem)), 'd/m/Y');
                    }

                    $pdf->MultiCell(60, 0, $txtDescripcion, 0, 'L', 1, 0, '', '', true, 0, false, true, 40, 'T');
                    $pdf->MultiCell(110, 0, $valorItem, 0, 'L', 1, 0, '', '', true, 0, false, true, 40, 'T');

                    if ($indice < count($documentoDatoValor) - 1 || $identificadorNegocio == 1) {
                        if (strlen($valorItem) > 55) {
                            $pdf->Ln(10);
                        } else {
                            $pdf->Ln(6);
                        }
                    }

                    if ($indice == count($documentoDatoValor) - 1) {
                        $pdf->Ln(1);
                    }
                }
            };
            $pdf->writeHTMLCell(0, 1, '', '', "", 'B', 1, 0, true, 'C', true);
        }


        $documentosRelacionImprimir = array();
        $dataDocumentoTipo;
        if ($dataDocumentoTipo[0]['identificador_negocio'] == 22) { //GUIA DE RECEPCION            
            foreach ($dataDocumentoRelacion as $index => $itemR) {
                if ($itemR['identificador_negocio'] == 10) {
                    $itemDR = array('serie_num' => $itemR['serie_numero_original'], 'nombre_doc' => $itemR['documento_tipo_descripcion']);
                    array_push($documentosRelacionImprimir, $itemDR);

                    $relacionOC = MovimientoNegocio::create()->obtenerDocumentosRelacionados($itemR['id']);

                    foreach ($relacionOC as $itemROC) {
                        if ($itemROC['identificador_negocio'] == 21 || $itemROC['identificador_negocio'] == 24) { //21: DUA  24: Commercial Invoice
                            $itemDR = array('serie_num' => $itemROC['serie_numero'], 'nombre_doc' => $itemROC['documento_tipo'], 'serie_numero_original' => $itemROC['serie_numero_original']);
                            array_push($documentosRelacionImprimir, $itemDR);
                        }
                    }
                }
            }
        }

        if ($dataDocumentoTipo[0]['identificador_negocio'] == 23) { //GUIA INTERNA DE TRANSFERENCIA                         
            $relacionDoc = MovimientoNegocio::create()->obtenerDocumentosRelacionados($documentoId);

            foreach ($relacionDoc as $itemDoc) {
                $itemDR = array('serie_num' => $itemDoc['serie_numero'], 'nombre_doc' => $itemDoc['documento_tipo'], 'serie_numero_original' => $itemDoc['serie_numero_original']);
                array_push($documentosRelacionImprimir, $itemDR);
            }
        }

        if (!ObjectUtil::isEmpty($documentosRelacionImprimir)) {
            $pdf->Ln(5);

            $pdf->Ln(5);

            $pdf->writeHTMLCell(0, 6, '', '', "<h4>DOCUMENTOS RELACIONADOS</h4>", 'TB', 1, 0, true, 'C', true);

            $pdf->Ln(1);
            $pdf->SetFillColor(255, 255, 255);
            foreach ($documentosRelacionImprimir as $indice => $item) {
                $txtDescripcion = $item['nombre_doc'];
                $serieNum = $item['serie_num'];
                $serieNumOriginal = $item['serie_numero_original'];

                $pdf->MultiCell(60, 0, $txtDescripcion, 0, 'L', 1, 0, '', '', true, 0, false, true, 40, 'T');
                $pdf->MultiCell(30, 0, $serieNum, 0, 'C', 1, 0, '', '', true, 0, false, true, 40, 'T'); // 110

                if (!ObjectUtil::isEmpty($serieNumOriginal)) {
                    $pdf->MultiCell(10, 0, ' | ', 0, 'C', 1, 0, '', '', true, 0, false, true, 40, 'T');
                    $pdf->MultiCell(70, 0, $serieNumOriginal, 0, 'L', 1, 0, '', '', true, 0, false, true, 40, 'T');
                }

                if ($indice < count($documentosRelacionImprimir) - 1) {
                    if (strlen($serieNum) > 55) {
                        $pdf->Ln(10);
                    } else {
                        $pdf->Ln(6);
                    }
                }

                if ($indice == count($documentosRelacionImprimir) - 1) {
                    $pdf->Ln(1);
                }
            };
            $pdf->writeHTMLCell(0, 1, '', '', "", 'B', 1, 0, true, 'C', true);
        }


        ob_clean();

        if ($tipoSalidaPDF == 'F') {
            $pdf->Output($url, $tipoSalidaPDF);
        }

        return $titulo;
    }

    public function generarDocumentoPDFGuiaRemision($documentoId, $comentario, $tipoSalidaPDF, $url, $data)
    {
        //$tipoSalidaPDF: F-> guarda local
        //obtenemos la data   
        /// RONALD AQUí
        $dataDocumentoFactura = DocumentoNegocio::create()->obtenerDocumentoRelacionadoImpresion($documentoId);
        $documentoFactura = $dataDocumentoFactura[0]['serie_numero_original'];

        $dataDocumentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXDocumentoId($documentoId);
        $documentoTipoId = $dataDocumentoTipo[0]['id'];

        $dataDocumento = $data->dataDocumento;

        $documentoDatoValor = $data->documentoDatoValor;
        $motivoDeTraslado = $documentoDatoValor[7]['valor'];
        $detalle = $data->detalle;
        $dataEmpresa = $data->dataEmpresa;
        $dataMovimientoTipoColumna = $data->movimientoTipoColumna;
        $dataDocumentoRelacion = $data->documentoRelacionado;
        $numeroOrdenCompra = $dataDocumentoRelacion[1]['serie_numero_original'];

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        // set document information
        $pdf->SetCreator('Imagina Technologies S.A.C.');
        $pdf->SetAuthor('Imagina Technologies S.A.C.');
        $pdf->SetTitle(strtoupper($dataDocumentoTipo[0]['descripcion']));
        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(9, 21, PDF_MARGIN_RIGHT);
        //        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        //        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // --------------GENERAR PDF-------------------------------------------
        $pdf->SetFont('helvetica', '', 6.5);
        $pdf->AddPage();

        $serieDocumento = '';
        if (!ObjectUtil::isEmpty($dataDocumento[0]['serie'])) {
            $serieDocumento = $dataDocumento[0]['serie'] . " - ";
        }
        // TITULO  
        $pdf->Ln(5);
        $pdf->Ln(5);
        $pdf->Ln(5);
        $pdf->Ln(5);
        $pdf->Ln(5);
        $pdf->Ln(3);
        $titulo = "AQUI PDF" . strtoupper($dataDocumento[0]['documento_tipo_descripcion']) . " " . $serieDocumento . $dataDocumento[0]['numero'];

        // $pdf->Ln(5);
        // $pdf->writeHTMLCell(0, 0, '', '', "<h2>" . $titulo . "</h2>", 0, 1, 0, true, 'C', true);
        //$pdf->Ln(5);
        $absisaY = 11;
        $absisaX = 8;
        //obtener la descripcion de persona de documento_tipo_dato        
        $dataPersona = DocumentoTipoDatoNegocio::create()->obtenerDocumentoTipoDatoXDocumentoIdXTipo($documentoId, 5);
        $descripcionPersona = $dataPersona[0]['descripcion'];

        $dataFechaEmision = DocumentoTipoDatoNegocio::create()->obtenerDocumentoTipoDatoXDocumentoIdXTipo($documentoId, 9);
        $descripcionFechaEmision = $dataFechaEmision[0]['descripcion'];

        $fecha = date_format((date_create($dataDocumento[0]['fecha_emision'])), 'd/m/Y');

        //$pdf->Cell(0, 9, 'Text-to-be-aligned-right', 0, false, 'R', 0, '', 0, false, 'T', 'M' );    
        if (!ObjectUtil::isEmpty($dataDocumento[0]['fecha_emision'])) {

            $pdf->setY($absisaY + 44);
            $pdf->setX($absisaX + 28);
            $pdf->Cell(53, 0, $fecha, 0, 0, 'L', 0, '', 0);
        }

        if (!ObjectUtil::isEmpty($documentoFactura)) { //FACTURA
            $pdf->setY($absisaY + 44);
            $pdf->setX($absisaX + 80);
            $pdf->Cell(0, 0, $documentoFactura, 0, 1, 'L', 0, '', 0);
        }
        if (!ObjectUtil::isEmpty($numeroOrdenCompra)) { //FACTURA
            $pdf->setY($absisaY + 44);
            $pdf->setX($absisaX + 123);
            $pdf->Cell(0, 0, $numeroOrdenCompra, 0, 1, 'L', 0, '', 0);
        }
        if (!ObjectUtil::isEmpty($numeroOrdenCompra)) { //FACTURA
            $pdf->setY($absisaY + 44);
            $pdf->setX($absisaX + 123);
            $pdf->Cell(0, 0, $numeroOrdenCompra, 0, 1, 'L', 0, '', 0);
        }

        if (!ObjectUtil::isEmpty($dataDocumento[0]['fecha_emision'])) {
            $pdf->setY($absisaY + 50);
            $pdf->setX($absisaX + 27); //PUNTODE PARTIDA 
            $pdf->Cell(0, 0, $documentoDatoValor[8]['valor'], 0, 1, 'L', 0, '', 0);
        }
        if (!ObjectUtil::isEmpty($documentoDatoValor[9]['valor'])) {
            $pdf->setY($absisaY + 51);
            $pdf->setX($absisaX + 121); //punto de llegada
            $pdf->Cell(0, 0, $documentoDatoValor[9]['valor'], 0, 1, 'L', 0, '', 0);
        }

        if (!ObjectUtil::isEmpty($dataDocumento[0]['fecha_emision'])) { //Fecha de inicio de traslado:
            $pdf->setY($absisaY + 56);
            $pdf->setX($absisaX + 39);
            $fecha_emision = date_format((date_create($dataDocumento[0]['fecha_emision'])), 'd/m/Y');
            $pdf->Cell(80, 0, $fecha_emision, 0, 0, 'L', 0, '', 0);
        }
        if (!ObjectUtil::isEmpty($dataDocumento[0]['nombre'])) { //Nombre o razón social del DESTINATARIO:
            $pdf->setY($absisaY + 54);
            $pdf->setX($absisaX + 148);
            $pdf->Cell(0, 0, $dataDocumento[0]['nombre'], 0, 1, 'L', 0, '', 0);
        }
        if (!ObjectUtil::isEmpty($dataDocumento[0]['fecha_emision'])) { //Costo mínimo:
            $pdf->setY($absisaY + 63);
            $pdf->setX($absisaX + 39);

            $valorItem3 = number_format($documentoDatoValor[10]['valor'], 2, ".", ",");
            $pdf->Cell(80, 0, $valorItem3, 0, 0, 'L', 0, '', 0);
        }
        if (!ObjectUtil::isEmpty($dataDocumento[0]['codigo_identificacion'])) {
            $pdf->setY($absisaY + 63);
            $pdf->setX($absisaX + 130);
            $pdf->Cell(0, 0, $dataDocumento[0]['persona_documento_tipo'] . ' ' . $dataDocumento[0]['codigo_identificacion'], 0, 1, 'L', 0, '', 0);
        }


        //TRANPOSRTE 

        $marcaYPlaca = $documentoDatoValor[3]['valor'] . ' ' . $documentoDatoValor[4]['valor'];
        $consInscripcion = $documentoDatoValor[5]['valor'];
        $licenciaConducior = $documentoDatoValor[6]['valor'];
        $porciones = explode("|", $dataDocumento[0]['dirigido_a']); // NOMBRE Y RUC DE LA EMPRESA  DE TRANSPORTES
        $razonSocialEmpresaTransportes = $porciones[0]; // porciÃ³n1
        $rucEmpresaTransportes = $porciones[1]; // porción2
        //$rucEmpresaTransportes=$dataDocumento[0]['codigo_identificacion'];

        if (!ObjectUtil::isEmpty($marcaYPlaca)) {
            $pdf->setY($absisaY + 72);
            $pdf->setX($absisaX + 39);
            $pdf->Cell(0, 0, $marcaYPlaca, 0, 1, 'L', 0, '', 0);
        }
        if (!ObjectUtil::isEmpty($razonSocialEmpresaTransportes)) {
            $pdf->setY($absisaY + 72);
            $pdf->setX($absisaX + 130);
            $pdf->Cell(0, 0, $razonSocialEmpresaTransportes, 0, 1, 'L', 0, '', 0);
        }
        if (!ObjectUtil::isEmpty($consInscripcion)) {
            $pdf->setY($absisaY + 77);
            $pdf->setX($absisaX + 46);
            $pdf->Cell(0, 0, $consInscripcion, 0, 1, 'L', 0, '', 0);
        }
        if (!ObjectUtil::isEmpty($licenciaConducior)) {
            $pdf->setY($absisaY + 82);
            $pdf->setX($absisaX + 47);
            $pdf->Cell(0, 0, $licenciaConducior, 0, 1, 'L', 0, '', 0);
        }
        if (!ObjectUtil::isEmpty($rucEmpresaTransportes)) {
            $pdf->setY($absisaY + 81);
            $pdf->setX($absisaX + 123);
            $pdf->Cell(0, 0, $rucEmpresaTransportes, 0, 1, 'L', 0, '', 0);
        }


        $espacioComentario = 0;
        if (!ObjectUtil::isEmpty($comentario)) {
            $pdf->Ln(5);
            $pdf->writeHTMLCell(0, 0, '', '', $comentario, 0, 1, 0, true, 'L', true);
            $espacioComentario = 12;
        }


        $existeColumnaPrecio = $this->existeColumnaCodigo($dataMovimientoTipoColumna, 5);

        $cont = 0;
        if ($existeColumnaPrecio) {

            $espacio = 0;
            foreach ($detalle as $item) {
                $cont++;
                if (strlen($item->descripcion) > 39) {
                    $cont++;
                }
                $pdf->setY($absisaY + 95 + $espacio);
                $pdf->setX($absisaX + 6);
                $pdf->Cell(0, 0, $item->bien_codigo, 0, 1, 'L', 0, '', 0);
                $pdf->setY($absisaY + 95 + $espacio);
                $pdf->setX($absisaX + 30);
                $pdf->Cell(0, 0, $item->descripcion, 0, 1, 'L', 0, '', 0);
                $pdf->setY($absisaY + 95 + $espacio);
                $pdf->setX($absisaX + 136);
                $pdf->Cell(0, 0, round($item->cantidad, 2), 0, 1, 'L', 0, '', 0);
                $pdf->setY($absisaY + 95 + $espacio);
                $pdf->setX($absisaX + 152);
                $pdf->Cell(0, 0, $item->unidadMedida, 0, 1, 'L', 0, '', 0);

                $espacio = $espacio + 6;
            };

            //            if (!ObjectUtil::isEmpty($dataDocumento[0]['total'])) {
            //                $tabla = $tabla . '<tr>'
            //                        . '<td style="text-align:rigth;;"  width="75%" colspan="4"  ></td>'
            //                        . '<td style="text-align:center"  width="12%">TOTAL ' . $dataDocumento[0]['moneda_simbolo'] . '</td>'
            //                        . '<td style="text-align:rigth"  width="13%">' . $esp . number_format($dataDocumento[0]['total'], 2) . $esp . '</td>'
            //                        . '</tr>';
            //            }
        } else {

            $espacio = 0;
            foreach ($detalle as $item) {
                $cont++;
                if (strlen($item->descripcion) > 39) {
                    $cont++;
                }


                $pdf->setY($absisaY + 95 + $espacio);
                $pdf->setX($absisaX + 8);
                $pdf->Cell(0, 0, $item->bien_codigo, 0, 1, 'L', 0, '', 0);
                $pdf->setY($absisaY + 95 + $espacio);
                $pdf->setX($absisaX + 30);
                $pdf->Cell(0, 0, $item->descripcion, 0, 1, 'L', 0, '', 0);
                $pdf->setY($absisaY + 95 + $espacio);
                $pdf->setX($absisaX + 136);
                $pdf->Cell(0, 0, round($item->cantidad, 2), 0, 1, 'L', 0, '', 0);
                $pdf->setY($absisaY + 95 + $espacio);
                $pdf->setX($absisaX + 152);
                $pdf->Cell(0, 0, $item->unidadMedida, 0, 1, 'L', 0, '', 0);

                $espacio = $espacio + 6;
            };
        }

        if (!ObjectUtil::isEmpty($documentoFactura)) { //FACTURA
            $pdf->setY($absisaY + 250);
            $pdf->setX($absisaX + 58);
            $pdf->Cell(0, 0, $documentoFactura, 0, 1, 'L', 0, '', 0);
        }

        //MOTIVO DE TRASLADO;
        $motivoDeTraslado;
        $pdf->SetFont('helvetica', '', 8);
        switch ($motivoDeTraslado) {
            case 'Venta':
                $pdf->setY($absisaY + 254);
                $pdf->setX($absisaX + 39);
                $pdf->Cell(0, 0, 'X', 0, 1, 'L', 0, '', 0);
                break;
            case 'Consignación':
                $pdf->setY($absisaY + 253);
                $pdf->setX($absisaX + 104);
                $pdf->Cell(0, 0, 'X', 0, 1, 'L', 0, '', 0);
                break;
            case 'Compra':
                $pdf->setY($absisaY + 259);
                $pdf->setX($absisaX + 41);
                $pdf->Cell(0, 0, 'X', 0, 1, 'L', 0, '', 0);
                break;
                //case 'Defectuoso':
            case 'Devolución':
                $pdf->setY($absisaY + 256);
                $pdf->setX($absisaX + 104);
                $pdf->Cell(0, 0, 'X', 0, 1, 'L', 0, '', 0);
                break;
            case 'Emisor itinerante':
                $pdf->setY($absisaY + 259);
                $pdf->setX($absisaX + 154);
                $pdf->Cell(0, 0, 'X', 0, 1, 'L', 0, '', 0);
                break;

            case 'Entre establecimientos de la misma empresa':
                $pdf->setY($absisaY + 259);
                $pdf->setX($absisaX + 104);
                $pdf->Cell(0, 0, 'X', 0, 1, 'L', 0, '', 0);
                break;
            case 'Exportación':
                $pdf->setY($absisaY + 259);
                $pdf->setX($absisaX + 183);
                $pdf->Cell(0, 0, 'X', 0, 1, 'L', 0, '', 0);
                break;
            case 'ImportaciÃ³n':
                $pdf->setY($absisaY + 256);
                $pdf->setX($absisaX + 183);
                $pdf->Cell(0, 0, 'X', 0, 1, 'L', 0, '', 0);
                break;
            case 'Otros':
                $pdf->setY($absisaY + 267);
                $pdf->setX($absisaX + 23);
                $pdf->Cell(0, 0, $motivoDeTraslado, 0, 1, 'L', 0, '', 0);
                break;
            case 'Para transformaciÃ³n':
                $pdf->setY($absisaY + 253);
                $pdf->setX($absisaX + 154);
                $pdf->Cell(0, 0, 'X', 0, 1, 'L', 0, '', 0);
                break;
            case 'Recojo bienes transformados':
                $pdf->setY($absisaY + 256);
                $pdf->setX($absisaX + 154);
                $pdf->Cell(0, 0, 'X', 0, 1, 'L', 0, '', 0);
                break;
            case 'Venta sujeta a confirmar':
                $pdf->setY($absisaY + 256);
                $pdf->setX($absisaX + 41);
                $pdf->Cell(0, 0, 'X', 0, 1, 'L', 0, '', 0);
                break;
            case 'Zona Primaria':
                $pdf->setY($absisaY + 253);
                $pdf->setX($absisaX + 183);
                $pdf->Cell(0, 0, 'X', 0, 1, 'L', 0, '', 0);
                break;
        }


        //fin tabla detalle
        //$tabla = $tabla . '</table>';
        //$pdf->writeHTML($tabla, true, false, false, false, 'C');

        $espaciado = 131 - ($cont * 4.4 + $espacioComentario);
        if ($espaciado < 15) {
            //            $espaciado = 15;
            $pdf->AddPage();
        }


        ob_clean();

        if ($tipoSalidaPDF == 'F') {
            $pdf->Output($url, $tipoSalidaPDF);
        }

        return $titulo;
    }

    public function generarDocumentoPDFSolicitudCompra($documentoId, $comentario, $tipoSalidaPDF, $url, $data)
    {
        //$tipoSalidaPDF: F-> guarda local
        //obtenemos la data        
        $dataDocumentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXDocumentoId($documentoId);
        $documentoTipoId = $dataDocumentoTipo[0]['id'];

        $dataDocumento = $data->dataDocumento;
        $documentoDatoValor = $data->documentoDatoValor;
        $detalle = $data->detalle;
        $dataEmpresa = $data->dataEmpresa;

        // create new PDF document                      
        $pdf = new TCPDF('p', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); //'p' is vertical and it is the default and 'l' is the horizonal.
        // set document information
        $pdf->SetCreator('Imagina Technologies S.A.C.');
        $pdf->SetAuthor('Imagina Technologies S.A.C.');
        $pdf->SetTitle(strtoupper($dataDocumentoTipo[0]['descripcion']));

        $cabeceraPDF1 = $dataEmpresa[0]['razon_social'] //                ."\nSoluciones Integrales de Perforación"
        ;

        $cabeceraPDF2 = $dataEmpresa[0]['direccion'] . "\n" .
            "Telfs: 044-728609\n" .
            "E-mail: gcardenas@bhdrillingtools.com Web site: www.bhdrillingtools.com\n" .
            "RUC: " . $dataEmpresa[0]['ruc'];
        $PDF_HEADER_LOGO_WIDTH = 40;
        $pdf->SetHeaderData('logo.PNG', $PDF_HEADER_LOGO_WIDTH, $cabeceraPDF1, $cabeceraPDF2, array(0, 0, 0), array(0, 0, 0));
        $pdf->setFooterData(array(0, 0, 0), array(0, 0, 0)); // 0,0,0
        // set header and footer fonts
        $PDF_FONT_SIZE_MAIN = 9;
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', $PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $PDF_MARGIN_FOOTER = 10;
        $pdf->SetFooterMargin($PDF_MARGIN_FOOTER);

        // set auto page breaks
        $PDF_MARGIN_BOTTOM = 10;
        $pdf->SetAutoPageBreak(TRUE, $PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // --------------GENERAR PDF-------------------------------------------
        // set font
        $pdf->SetFont('helvetica', '', 9);

        // add a page
        $pdf->AddPage();

        $serieDocumento = '';
        if (!ObjectUtil::isEmpty($dataDocumento[0]['serie'])) {
            $serieDocumento = $dataDocumento[0]['serie'] . " - ";
        }


        //        foreach ($documentoDatoValor as $indice => $item) {
        //            $txtDescripcion = $item['descripcion'];
        //            $valorItem = $item['valor'];
        //
        //            if ($item['tipo'] == 1) {
        //                $valorItem = number_format($valorItem, 2, ".", ",");
        //            }
        //
        //            if ($item['tipo'] == 3) {
        //                $valorItem = date_format((date_create($valorItem)), 'd/m/Y');
        //            }
        //
        //            switch ((int) $item['id']) {
        //                case 5465:
        //                    $dtdTiempoEntrega = $valorItem;
        //                    break;
        //                case 5466:
        //                    $dtdCondicionPago = $valorItem;
        //                    break;
        //            }
        //        };
        //        $ventaCotizacion=strpos(($dataDocumento[0]['documento_tipo_descripcion']), 'V. Cotizacion');  

        $titulo = strtoupper($dataDocumento[0]['documento_tipo_descripcion']) . " " . $serieDocumento . $dataDocumento[0]['numero'];
        //        $titulo2=strtoupper($dataDocumento[0]['documento_tipo_descripcion'])." <br> ".$serieDocumento.$dataDocumento[0]['numero'];
        $pdf->Ln(5);
        $pdf->writeHTMLCell(0, 0, '', '', "<h2>" . $titulo . "</h2>", 0, 1, 0, true, 'C', true);
        $pdf->Ln(5);
        //        $pdf->Cell(0, 10, $titulo, 0, 1, 'C', 0, '', 1);
        //obtener la descripcion de persona de documento_tipo_dato        
        $dataPersona = DocumentoTipoDatoNegocio::create()->obtenerDocumentoTipoDatoXDocumentoIdXTipo($documentoId, 5);
        $descripcionPersona = $dataPersona[0]['descripcion'];

        $fecha = date_format((date_create($dataDocumento[0]['fecha_emision'])), 'd/m/Y');
        $pdf->Cell(0, 0, "Fecha: " . $fecha, 0, 1, 'L', 0, '', 0);

        $pdf->MultiCell(120, 0, $descripcionPersona . ": " . $dataDocumento[0]['nombre'], 0, 'L', 0, 0, '', '', true, 0, false, true, 40, 'T');
        $pdf->MultiCell(60, 0, $dataDocumento[0]['persona_documento_tipo'] . ": " . $dataDocumento[0]['codigo_identificacion'], 0, 'L', 0, 0, '', '', true, 0, false, true, 40, 'T');

        $pdf->Ln(4);
        //        $pdf->Cell(0, 0, $descripcionPersona.": ".$dataDocumento[0]['nombre'], 0, 1, 'L', 0, '', 0);        
        $pdf->Cell(0, 0, "Dirección: " . $dataDocumento[0]['direccion'], 0, 1, 'L', 0, '', 0);
        //        $pdf->Cell(0, 0, $dataDocumento[0]['persona_documento_tipo'].": ".$dataDocumento[0]['codigo_identificacion'], 0, 1, 'L', 0, '', 0);         

        if (!ObjectUtil::isEmpty($dataDocumento[0]['descripcion'])) {
            $pdf->Cell(0, 0, "Descripción: " . $dataDocumento[0]['descripcion'], 0, 1, 'L', 0, '', 0);
        }
        //        $pdf->Cell(0, 0, "Moneda: ".$dataDocumento[0]['moneda_descripcion'], 0, 1, 'L', 0, '', 0);   

        if (!ObjectUtil::isEmpty($dataDocumento[0]['comentario'])) {
            $pdf->Ln(6);
            $pdf->Cell(0, 0, $dataDocumento[0]['comentario'], 0, 1, 'L', 0, '', 0);
        }

        //espacio
        $pdf->Ln(5);

        //detalle
        $esp = '&nbsp;&nbsp;'; //espacio en blanco

        $tabla = '<table cellspacing="0" cellpadding="1" border="1">
                <tr>
                    <th style="text-align:center;" width="5%"><b>Item</b></th>
                    <th style="text-align:center;" width="18%"><b>Código</b></th>
                    <th style="text-align:center;" width="50%"><b>Descripción</b></th>
                    <th style="text-align:center;" width="11%"><b>Cant.</b></th>
                    <th style="text-align:center;" width="16%"><b>U.M.</b></th>
                </tr>
            ';

        foreach ($detalle as $index => $item) {
            $tabla = $tabla . '<tr>'
                . '<td style="text-align:rigth"  width="5%">' . $esp . ($index + 1) . $esp . '</td>'
                . '<td style="text-align:left"  width="18%">' . $esp . $item->bien_codigo . $esp . '</td>'
                . '<td style="text-align:left"  width="50%">' . $esp . $item->descripcion . $esp . '</td>'
                . '<td style="text-align:rigth"  width="11%">' . $esp . round($item->cantidad, 2) . $esp . '</td>'
                . '<td style="text-align:left"  width="16%">' . $esp . $item->unidadMedida . $esp . '</td>'
                . '</tr>';
        };

        //fin tabla detalle
        $tabla = $tabla . '</table>';

        $pdf->writeHTML($tabla, true, false, false, false, 'C');

        $pdf->Ln(175);

        //Close and output PDF document
        ob_clean();

        if ($tipoSalidaPDF == 'F') {
            $pdf->Output($url, $tipoSalidaPDF);
        }

        return $titulo;
    }

    public function generarDocumentoPDFCotizacionCompra($documentoId, $comentario, $tipoSalidaPDF, $url, $data)
    {
        //$tipoSalidaPDF: F-> guarda local
        //obtenemos la data        
        $dataDocumentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXDocumentoId($documentoId);
        $documentoTipoId = $dataDocumentoTipo[0]['id'];

        $dataDocumento = $data->dataDocumento;
        $documentoDatoValor = $data->documentoDatoValor;
        $detalle = $data->detalle;
        $dataEmpresa = $data->dataEmpresa;

        // create new PDF document                      
        $pdf = new TCPDF('p', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); //'p' is vertical and it is the default and 'l' is the horizonal.
        // set document information
        $pdf->SetCreator('Imagina Technologies S.A.C.');
        $pdf->SetAuthor('Imagina Technologies S.A.C.');
        $pdf->SetTitle(strtoupper($dataDocumentoTipo[0]['descripcion']));

        $cabeceraPDF1 = $dataEmpresa[0]['razon_social'] //                ."\nSoluciones Integrales de Perforación"
        ;

        $cabeceraPDF2 = $dataEmpresa[0]['direccion'] . "\n" .
            "Telfs: 044-728609\n" .
            "E-mail: gcardenas@bhdrillingtools.com Web site: www.bhdrillingtools.com\n" .
            "RUC: " . $dataEmpresa[0]['ruc'];
        $PDF_HEADER_LOGO_WIDTH = 40;
        $pdf->SetHeaderData('logo.PNG', $PDF_HEADER_LOGO_WIDTH, $cabeceraPDF1, $cabeceraPDF2, array(0, 0, 0), array(0, 0, 0));
        $pdf->setFooterData(array(0, 0, 0), array(0, 0, 0)); // 0,0,0
        // set header and footer fonts
        $PDF_FONT_SIZE_MAIN = 9;
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', $PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $PDF_MARGIN_FOOTER = 10;
        $pdf->SetFooterMargin($PDF_MARGIN_FOOTER);

        // set auto page breaks
        $PDF_MARGIN_BOTTOM = 10;
        $pdf->SetAutoPageBreak(TRUE, $PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // --------------GENERAR PDF-------------------------------------------
        // set font
        $pdf->SetFont('helvetica', '', 9);

        // add a page
        $pdf->AddPage();

        $serieDocumento = '';
        if (!ObjectUtil::isEmpty($dataDocumento[0]['serie'])) {
            $serieDocumento = $dataDocumento[0]['serie'] . " - ";
        }


        foreach ($documentoDatoValor as $indice => $item) {
            $txtDescripcion = $item['descripcion'];
            $valorItem = $item['valor'];

            if ($item['tipo'] == 1) {
                $valorItem = number_format($valorItem, 2, ".", ",");
            }

            if ($item['tipo'] == 3) {
                $valorItem = date_format((date_create($valorItem)), 'd/m/Y');
            }

            switch ((int) $item['documento_tipo_id']) {
                case 2620:
                    $dtdTiempoEntrega = $valorItem;
                    break;
                case 2621:
                    $dtdCondicionComercial = $valorItem;
                    break;
                case 2624:
                    $dtdLugarEntrega = $valorItem;
                    break;
                case 2622:
                    $dtdNumeroCuenta = $valorItem;
                    break;
            }
        };

        //        $ventaCotizacion=strpos(($dataDocumento[0]['documento_tipo_descripcion']), 'V. Cotizacion');  

        $titulo = strtoupper($dataDocumento[0]['documento_tipo_descripcion']) . " " . $serieDocumento . $dataDocumento[0]['numero'];
        //        $titulo2=strtoupper($dataDocumento[0]['documento_tipo_descripcion'])." <br> ".$serieDocumento.$dataDocumento[0]['numero'];
        $pdf->Ln(5);
        $pdf->writeHTMLCell(0, 0, '', '', "<h2>" . $titulo . "</h2>", 0, 1, 0, true, 'C', true);
        $pdf->Ln(5);
        //        $pdf->Cell(0, 10, $titulo, 0, 1, 'C', 0, '', 1);
        //obtener la descripcion de persona de documento_tipo_dato        
        $dataPersona = DocumentoTipoDatoNegocio::create()->obtenerDocumentoTipoDatoXDocumentoIdXTipo($documentoId, 5);
        $descripcionPersona = $dataPersona[0]['descripcion'];

        $fecha = date_format((date_create($dataDocumento[0]['fecha_emision'])), 'd/m/Y');
        $pdf->Cell(0, 0, "Fecha: " . $fecha, 0, 1, 'L', 0, '', 0);

        $pdf->MultiCell(120, 0, $descripcionPersona . ": " . $dataDocumento[0]['nombre'], 0, 'L', 0, 0, '', '', true, 0, false, true, 40, 'T');
        $pdf->MultiCell(60, 0, $dataDocumento[0]['persona_documento_tipo'] . ": " . $dataDocumento[0]['codigo_identificacion'], 0, 'L', 0, 0, '', '', true, 0, false, true, 40, 'T');

        $pdf->Ln(4);
        //        $pdf->Cell(0, 0, $descripcionPersona.": ".$dataDocumento[0]['nombre'], 0, 1, 'L', 0, '', 0);        
        $pdf->Cell(0, 0, "Dirección: " . $dataDocumento[0]['direccion'], 0, 1, 'L', 0, '', 0);
        //        $pdf->Cell(0, 0, $dataDocumento[0]['persona_documento_tipo'].": ".$dataDocumento[0]['codigo_identificacion'], 0, 1, 'L', 0, '', 0);         

        if (!ObjectUtil::isEmpty($dataDocumento[0]['descripcion'])) {
            $pdf->Cell(0, 0, "Descripción: " . $dataDocumento[0]['descripcion'], 0, 1, 'L', 0, '', 0);
        }
        $pdf->Cell(0, 0, "Moneda: " . $dataDocumento[0]['moneda_descripcion'], 0, 1, 'L', 0, '', 0);

        $espacioComentario = 0;
        if (!ObjectUtil::isEmpty($dataDocumento[0]['comentario'])) {
            $pdf->Ln(7);
            $pdf->Cell(0, 0, $dataDocumento[0]['comentario'], 0, 1, 'L', 0, '', 0);
            $espacioComentario = 12;
        }

        //espacio
        $pdf->Ln(5);

        //detalle
        $esp = '&nbsp;&nbsp;'; //espacio en blanco

        $tabla = '<table cellspacing="0" cellpadding="1" border="1">
                <tr>
                    <th style="text-align:center;" width="5%"><b>Item</b></th>
                    <th style="text-align:center;" width="13%"><b>Código</b></th>
                    <th style="text-align:center;" width="42%"><b>Descripción</b></th>
                    <th style="text-align:center;" width="6%"><b>Cant.</b></th>
                    <th style="text-align:center;" width="11%"><b>U.M.</b></th>
                    <th style="text-align:center;" width="11%"><b>P. Unit.</b></th>
                    <th style="text-align:center;" width="12%"><b>Sub Total</b></th>
                </tr>
            ';

        $cont = 0;
        foreach ($detalle as $index => $item) {
            $cont++;
            if (strlen($item->descripcion) > 39) {
                $cont++;
            }
            $tabla = $tabla . '<tr>'
                . '<td style="text-align:rigth"  width="5%">' . $esp . ($index + 1) . $esp . '</td>'
                . '<td style="text-align:left"  width="13%">' . $esp . $item->bien_codigo . $esp . '</td>'
                . '<td style="text-align:left"  width="42%">' . $esp . $item->descripcion . $esp . '</td>'
                . '<td style="text-align:rigth"  width="6%">' . $esp . round($item->cantidad, 2) . $esp . '</td>'
                . '<td style="text-align:left"  width="11%">' . $esp . $item->unidadMedida . $esp . '</td>'
                . '<td style="text-align:rigth"  width="11%">' . $esp . number_format($item->precioUnitario, 2) . $esp . '</td>'
                . '<td style="text-align:rigth"  width="12%">' . $esp . number_format($item->importe, 2) . $esp . '</td>'
                . '</tr>';
        };

        //fin tabla detalle
        $tabla = $tabla . '</table>';

        $pdf->writeHTML($tabla, true, false, false, false, 'C');

        $espaciado = 145 - ($cont * 4.4 + $espacioComentario);
        if ($espaciado < 15) {
            $espaciado = 15;
        }

        $pdf->Ln($espaciado); // cada fila es 4, total=131 
        //        $pdf->Ln(125);        
        //        IF(!ObjectUtil::isEmpty($documentoDatoValor)){        
        $pdf->Ln(5);
        //        $borde=array('LTRB' => array('width' => 1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));

        $pdf->Ln(5);
        $pdf->SetFillColor(255, 255, 255);

        $tabla = '<table cellspacing="0" cellpadding="1" style="text-align:left; border-style: solid; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px">
                        <tr>
                          <td width="2%"></td>
                          <td width="31%"></td>
                          <td width="25%"></td>
                          <td width="8%"></td>
                          <td width="14%"></td>
                          <td width="20%"></td>
                        </tr>
                        <tr>
                          <td width="2%"></td>
                          <td width="31%"></td>
                          <td width="25%"></td>
                          <td width="8%"></td>
                          <td width="14%">' . $esp . 'Subtotal ' . $dataDocumento[0]['moneda_simbolo'] . '</td>
                          <td style="text-align:rigth" width="20%">' . $esp . number_format($dataDocumento[0]['subtotal'], 2) . $esp . '</td>
                        </tr>
                        <tr>
                          <td width="2%"></td>
                          <td style="text-align:center; border-style: solid; border-top-width: 1px;"  width="31%">' . $esp . 'Hecho por:</td>                       
                          <td width="25%"></td>  
                          <td width="8%"></td>
                          <td width="14%">' . $esp . 'IGV 18% ' . $dataDocumento[0]['moneda_simbolo'] . '</td>
                          <td style="text-align:rigth" width="20%">' . $esp . number_format($dataDocumento[0]['igv'], 2) . $esp . '</td>
                        </tr>
                        <tr>
                          <td width="2%"></td>
                          <td style="text-align:center;"  width="31%">' . $esp . $dataDocumento[0]['usuario'] . $esp . '</td>
                          <td width="25%"></td>
                          <td width="8%"></td>
                          <td width="14%">' . $esp . 'TOTAL ' . $dataDocumento[0]['moneda_simbolo'] . '</td>
                          <td style="text-align:rigth" width="20%">' . $esp . number_format($dataDocumento[0]['total'], 2) . $esp . '</td>
                        </tr>
                        <tr>
                          <td width="2%"></td>
                          <td width="31%"></td>
                          <td width="25%"></td>
                          <td width="8%"></td>
                          <td width="14%"></td>
                          <td width="20%"></td>
                        </tr>
                    </table>';

        $pdf->writeHTML($tabla, true, false, false, false, 'C');
        //        }           

        $pdf->Cell(0, 0, "Fecha de entrega: " . date_format((date_create($dataDocumento[0]['fecha_tentativa'])), 'd/m/Y'), 0, 1, 'L', 0, '', 0);
        $pdf->Cell(0, 0, "Tiempo de entrega: " . $dtdTiempoEntrega, 0, 1, 'L', 0, '', 0);
        $pdf->Cell(0, 0, "Número de cuenta: " . $dtdNumeroCuenta, 0, 1, 'L', 0, '', 0);
        $pdf->Cell(0, 0, "Lugar de entrega: " . $dtdLugarEntrega, 0, 1, 'L', 0, '', 0);
        $pdf->Cell(0, 0, "Condiciones comerciales: " . $dtdCondicionComercial, 0, 1, 'L', 0, '', 0);

        //Close and output PDF document
        ob_clean();

        if ($tipoSalidaPDF == 'F') {
            $pdf->Output($url, $tipoSalidaPDF);
        }

        return $titulo;
    }

    public function generarDocumentoPDFOrdenCompra($documentoId, $comentario, $tipoSalidaPDF, $url, $data)
    {
        //$tipoSalidaPDF: F-> guarda local
        //obtenemos la data        
        $dataDocumentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXDocumentoId($documentoId);
        $documentoTipoId = $dataDocumentoTipo[0]['id'];

        $dataDocumento = $data->dataDocumento;
        $documentoDatoValor = $data->documentoDatoValor;
        $detalle = $data->detalle;
        $dataEmpresa = $data->dataEmpresa;
        $dataDocumentoRelacion = $data->documentoRelacionado;

        $usuarioRequerimiento = null;
        foreach ($dataDocumentoRelacion as $index => $itemR) {
            if ($itemR['identificador_negocio'] == 13) {
                $usuarioRequerimiento = $itemR['usuario'];
            }
        }

        if (ObjectUtil::isEmpty($usuarioRequerimiento)) {
            $usuarioRequerimiento = $dataDocumento[0]['usuario'];
        }

        // create new PDF document                      
        $pdf = new TCPDF('p', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); //'p' is vertical and it is the default and 'l' is the horizonal.
        // set document information
        $pdf->SetCreator('Imagina Technologies S.A.C.');
        $pdf->SetAuthor('Imagina Technologies S.A.C.');
        $pdf->SetTitle(strtoupper($dataDocumentoTipo[0]['descripcion']));

        $cabeceraPDF1 = $dataEmpresa[0]['razon_social'] //                ."\nSoluciones Integrales de Perforación"
        ;

        $cabeceraPDF2 = $dataEmpresa[0]['direccion'] . "\n" .
            "Telfs: 044-728609\n" .
            "E-mail: gcardenas@bhdrillingtools.com Web site: www.bhdrillingtools.com\n" .
            "RUC: " . $dataEmpresa[0]['ruc'];
        $PDF_HEADER_LOGO_WIDTH = 40;
        $pdf->SetHeaderData('logo.PNG', $PDF_HEADER_LOGO_WIDTH, $cabeceraPDF1, $cabeceraPDF2, array(0, 0, 0), array(0, 0, 0));
        $pdf->setFooterData(array(0, 0, 0), array(0, 0, 0)); // 0,0,0
        // set header and footer fonts
        $PDF_FONT_SIZE_MAIN = 9;
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', $PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $PDF_MARGIN_FOOTER = 10;
        $pdf->SetFooterMargin($PDF_MARGIN_FOOTER);

        // set auto page breaks
        $PDF_MARGIN_BOTTOM = 10;
        $pdf->SetAutoPageBreak(TRUE, $PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // --------------GENERAR PDF-------------------------------------------
        // set font
        $pdf->SetFont('helvetica', '', 9);

        // add a page
        $pdf->AddPage();

        $serieDocumento = '';
        if (!ObjectUtil::isEmpty($dataDocumento[0]['serie'])) {
            $serieDocumento = $dataDocumento[0]['serie'] . " - ";
        }


        foreach ($documentoDatoValor as $indice => $item) {
            $txtDescripcion = $item['descripcion'];
            $valorItem = $item['valor'];

            if ($item['tipo'] == 1) {
                $valorItem = number_format($valorItem, 2, ".", ",");
            }

            if ($item['tipo'] == 3) {
                $valorItem = date_format((date_create($valorItem)), 'd/m/Y');
            }

            switch ((int) $item['documento_tipo_id']) {
                case 2520:
                    $dtdTiempoEntrega = $valorItem;
                    break;
                case 2521:
                    $dtdCondicionComercial = $valorItem;
                    break;
                case 2618:
                    $dtdLugarEntrega = $valorItem;
                    break;
                case 2625:
                    $dtdNumeroCuenta = $valorItem;
                    break;
            }
        };

        //        $ventaCotizacion=strpos(($dataDocumento[0]['documento_tipo_descripcion']), 'V. Cotizacion');  

        $titulo = strtoupper($dataDocumento[0]['documento_tipo_descripcion']) . " " . $serieDocumento . $dataDocumento[0]['numero'];
        //        $titulo2=strtoupper($dataDocumento[0]['documento_tipo_descripcion'])." <br> ".$serieDocumento.$dataDocumento[0]['numero'];
        $pdf->Ln(5);
        $pdf->writeHTMLCell(0, 0, '', '', "<h2>" . $titulo . "</h2>", 0, 1, 0, true, 'C', true);
        $pdf->Ln(5);
        //        $pdf->Cell(0, 10, $titulo, 0, 1, 'C', 0, '', 1);
        //obtener la descripcion de persona de documento_tipo_dato        
        $dataPersona = DocumentoTipoDatoNegocio::create()->obtenerDocumentoTipoDatoXDocumentoIdXTipo($documentoId, 5);
        $descripcionPersona = $dataPersona[0]['descripcion'];

        $fecha = date_format((date_create($dataDocumento[0]['fecha_emision'])), 'd/m/Y');
        $pdf->Cell(0, 0, "Fecha: " . $fecha, 0, 1, 'L', 0, '', 0);

        $pdf->MultiCell(180, 0, $descripcionPersona . ": " . $dataDocumento[0]['nombre'], 0, 'L', 0, 0, '', '', true, 0, false, true, 40, 'T');
        $pdf->Ln(4);
        $pdf->MultiCell(60, 0, $dataDocumento[0]['persona_documento_tipo'] . ": " . $dataDocumento[0]['codigo_identificacion'], 0, 'L', 0, 0, '', '', true, 0, false, true, 40, 'T');

        $pdf->Ln(4);
        //        $pdf->Cell(0, 0, $descripcionPersona.": ".$dataDocumento[0]['nombre'], 0, 1, 'L', 0, '', 0);        
        $pdf->Cell(0, 0, "Dirección: " . $dataDocumento[0]['direccion'], 0, 1, 'L', 0, '', 0);
        //        $pdf->Cell(0, 0, $dataDocumento[0]['persona_documento_tipo'].": ".$dataDocumento[0]['codigo_identificacion'], 0, 1, 'L', 0, '', 0);         

        if (!ObjectUtil::isEmpty($dataDocumento[0]['descripcion'])) {
            $pdf->Cell(0, 0, "Descripción: " . $dataDocumento[0]['descripcion'], 0, 1, 'L', 0, '', 0);
        }
        $pdf->Cell(0, 0, "Moneda: " . $dataDocumento[0]['moneda_descripcion'], 0, 1, 'L', 0, '', 0);

        $espacioComentario = 0;
        if (!ObjectUtil::isEmpty($dataDocumento[0]['comentario'])) {
            $pdf->Ln(7);
            $pdf->writeHTMLCell(0, 0, '', '', $dataDocumento[0]['comentario'], 0, 1, 0, true, 'L', true);
            //            $pdf->Cell(0, 0, $dataDocumento[0]['comentario'], 0, 1, 'L', 0, '', 0);
            $espacioComentario = 12;
        }

        //espacio
        $pdf->Ln(5);

        //detalle
        $esp = '&nbsp;&nbsp;'; //espacio en blanco

        $tabla = '<table cellspacing="0" cellpadding="1" border="1">
                <tr>
                    <th style="text-align:center;" width="5%"><b>Item</b></th>
                    <th style="text-align:center;" width="13%"><b>Código</b></th>
                    <th style="text-align:center;" width="42%"><b>Descripción</b></th>
                    <th style="text-align:center;" width="6%"><b>Cant.</b></th>
                    <th style="text-align:center;" width="11%"><b>U.M.</b></th>
                    <th style="text-align:center;" width="11%"><b>P. Unit.</b></th>
                    <th style="text-align:center;" width="12%"><b>Sub Total</b></th>
                </tr>
            ';

        $cont = 0;
        foreach ($detalle as $index => $item) {
            $cont++;
            if (strlen($item->descripcion) > 39) {
                $cont++;
            }

            $tabla = $tabla . '<tr>'
                . '<td style="text-align:rigth"  width="5%">' . $esp . ($index + 1) . $esp . '</td>'
                . '<td style="text-align:left"  width="13%">' . $esp . $item->bien_codigo . $esp . '</td>'
                . '<td style="text-align:left"  width="42%">' . $esp . $item->descripcion . $esp . '</td>'
                . '<td style="text-align:rigth"  width="6%">' . $esp . round($item->cantidad, 2) . $esp . '</td>'
                . '<td style="text-align:left"  width="11%">' . $esp . $item->unidadMedida . $esp . '</td>'
                . '<td style="text-align:rigth"  width="11%">' . $esp . number_format($item->precioUnitario, 2) . $esp . '</td>'
                . '<td style="text-align:rigth"  width="12%">' . $esp . number_format($item->importe, 2) . $esp . '</td>'
                . '</tr>';
        };

        //fin tabla detalle
        $tabla = $tabla . '</table>';

        $pdf->writeHTML($tabla, true, false, false, false, 'C');

        $espaciado = 131 - ($cont * 4.4 + $espacioComentario);
        if ($espaciado < 15) {
            $espaciado = 15;
        }

        $pdf->Ln($espaciado); // cada fila es 4, total=131 , antes:115       
        if (!ObjectUtil::isEmpty($documentoDatoValor)) {
            $pdf->Ln(5);
            //        $borde=array('LTRB' => array('width' => 1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));

            $pdf->Ln(5);
            $pdf->SetFillColor(255, 255, 255);

            $tabla = '<table cellspacing="0" cellpadding="1" style="text-align:left; border-style: solid; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px">
                        <tr>
                          <td width="2%"></td>
                          <td width="23%"></td>
                          <td width="8%"></td>
                          <td width="25%"></td>
                          <td width="8%"></td>
                          <td width="14%"></td>
                          <td width="20%"></td>
                        </tr>
                        <tr>
                          <td width="2%"></td>
                          <td width="23%"></td>
                          <td width="8%"></td>
                          <td width="25%"></td>
                          <td width="8%"></td>
                          <td width="14%">' . $esp . 'Subtotal ' . $dataDocumento[0]['moneda_simbolo'] . '</td>
                          <td style="text-align:rigth" width="20%">' . $esp . number_format($dataDocumento[0]['subtotal'], 2) . $esp . '</td>
                        </tr>
                        <tr>
                          <td width="2%"></td>
                          <td style="text-align:center; border-style: solid; border-top-width: 1px;"  width="23%">' . $esp . 'Hecho por:</td>
                          <td width="8%"></td>
                          <td style="text-align:center; border-style: solid; border-top-width: 1px;"  width="25%">' . $esp . 'Aprobado por:</td>  
                          <td width="8%"></td>
                          <td width="14%">' . $esp . 'IGV 18% ' . $dataDocumento[0]['moneda_simbolo'] . '</td>
                          <td style="text-align:rigth" width="20%">' . $esp . number_format($dataDocumento[0]['igv'], 2) . $esp . '</td>
                        </tr>
                        <tr>
                          <td width="2%"></td>
                          <td style="text-align:center;"  width="23%">' . $esp . $usuarioRequerimiento . $esp . '</td>
                          <td width="8%"></td>
                          <td style="text-align:center"  width="25%">' . $esp . $dataDocumento[0]['usuario'] . $esp . '</td>
                          <td width="8%"></td>
                          <td width="14%">' . $esp . 'TOTAL ' . $dataDocumento[0]['moneda_simbolo'] . '</td>
                          <td style="text-align:rigth" width="20%">' . $esp . number_format($dataDocumento[0]['total'], 2) . $esp . '</td>
                        </tr>
                        <tr>
                          <td width="2%"></td>
                          <td width="23%"></td>
                          <td width="8%"></td>
                          <td width="25%"></td>
                          <td width="8%"></td>
                          <td width="14%"></td>
                          <td width="20%"></td>
                        </tr>
                    </table>';

            $pdf->writeHTML($tabla, true, false, false, false, 'C');
        }

        $pdf->Cell(0, 0, "Fecha de entrega: " . date_format((date_create($dataDocumento[0]['fecha_tentativa'])), 'd/m/Y'), 0, 1, 'L', 0, '', 0);
        $pdf->Cell(0, 0, "Tiempo de entrega: " . $dtdTiempoEntrega, 0, 1, 'L', 0, '', 0);
        $pdf->Cell(0, 0, "Número de cuenta: " . $dtdNumeroCuenta, 0, 1, 'L', 0, '', 0);
        $pdf->Cell(0, 0, "Lugar de entrega: " . $dtdLugarEntrega, 0, 1, 'L', 0, '', 0);
        $pdf->Cell(0, 0, "Condiciones comerciales: " . $dtdCondicionComercial, 0, 1, 'L', 0, '', 0);

        $pdf->Ln(8);
        $pdf->SetFont('helvetica', '', 7);
        $pdf->Cell(0, 0, "Notas Importantes ", 0, 1, 'L', 0, '', 0);
        $pdf->Cell(0, 0, " 1.- Esta O/C tiene validez hasta la fecha de entrega aqui indicada por la parte del PROVEEDOR, de no cumplir esta quedara ANULADA.", 0, 1, 'L', 0, '', 0);
        $pdf->Cell(0, 0, " 2.- La FACTURA ORIGINAL se entregará de manera conjunta con copia SUNAT, 01 copia de GUIA DE REMISION y 01 copia de ORDEN DE COMPRA.", 0, 1, 'L', 0, '', 0);

        //Close and output PDF document
        ob_clean();

        if ($tipoSalidaPDF == 'F') {
            $pdf->Output($url, $tipoSalidaPDF);
        }

        return $titulo;
    }

    public function generarDocumentoPDFSolicitudCompraExt($documentoId, $comentario, $tipoSalidaPDF, $url, $data)
    {
        //$tipoSalidaPDF: F-> guarda local
        //obtenemos la data        
        $dataDocumentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXDocumentoId($documentoId);
        $documentoTipoId = $dataDocumentoTipo[0]['id'];

        $dataDocumento = $data->dataDocumento;
        $documentoDatoValor = $data->documentoDatoValor;
        $detalle = $data->detalle;
        $dataEmpresa = $data->dataEmpresa;

        // create new PDF document                      
        $pdf = new TCPDF('p', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); //'p' is vertical and it is the default and 'l' is the horizonal.
        // set document information
        $pdf->SetCreator('Imagina Technologies S.A.C.');
        $pdf->SetAuthor('Imagina Technologies S.A.C.');
        $pdf->SetTitle(strtoupper($dataDocumentoTipo[0]['descripcion']));

        $cabeceraPDF1 = $dataEmpresa[0]['razon_social'] //                ."\nSoluciones Integrales de Perforación"
        ;

        $cabeceraPDF2 = $dataEmpresa[0]['direccion'] . "\n" .
            "Phone: 044-728609\n" .
            "E-mail: gcardenas@bhdrillingtools.com Web site: www.bhdrillingtools.com\n" .
            "RUC: " . $dataEmpresa[0]['ruc'];
        $PDF_HEADER_LOGO_WIDTH = 40;
        $pdf->SetHeaderData('logo.PNG', $PDF_HEADER_LOGO_WIDTH, $cabeceraPDF1, $cabeceraPDF2, array(0, 0, 0), array(0, 0, 0));
        $pdf->setFooterData(array(0, 0, 0), array(0, 0, 0)); // 0,0,0
        // set header and footer fonts
        $PDF_FONT_SIZE_MAIN = 9;
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', $PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $PDF_MARGIN_FOOTER = 10;
        $pdf->SetFooterMargin($PDF_MARGIN_FOOTER);

        // set auto page breaks
        $PDF_MARGIN_BOTTOM = 10;
        $pdf->SetAutoPageBreak(TRUE, $PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // --------------GENERAR PDF-------------------------------------------
        // set font
        $pdf->SetFont('helvetica', '', 9);

        // add a page
        $pdf->AddPage();

        $serieDocumento = '';
        if (!ObjectUtil::isEmpty($dataDocumento[0]['serie'])) {
            $serieDocumento = $dataDocumento[0]['serie'] . " - ";
        }


        //        foreach ($documentoDatoValor as $indice => $item) {
        //            $txtDescripcion = $item['descripcion'];
        //            $valorItem = $item['valor'];
        //
        //            if ($item['tipo'] == 1) {
        //                $valorItem = number_format($valorItem, 2, ".", ",");
        //            }
        //
        //            if ($item['tipo'] == 3) {
        //                $valorItem = date_format((date_create($valorItem)), 'd/m/Y');
        //            }
        //
        //            switch ((int) $item['id']) {
        //                case 5541:
        //                    $dtdTiempoEntrega = $valorItem;
        //                    break;
        //                case 5542:
        //                    $dtdCondicionPago = $valorItem;
        //                    break;
        //            }
        //        };
        //        $ventaCotizacion=strpos(($dataDocumento[0]['documento_tipo_descripcion']), 'V. Cotizacion');  
        $titulo = "PURCHASE REQUEST " . $serieDocumento . $dataDocumento[0]['numero'];
        //        $titulo=strtoupper($dataDocumento[0]['documento_tipo_descripcion'])." ".$serieDocumento.$dataDocumento[0]['numero'];
        $pdf->Ln(5);
        $pdf->writeHTMLCell(0, 0, '', '', "<h2>" . $titulo . "</h2>", 0, 1, 0, true, 'C', true);
        $pdf->Ln(5);
        //        $pdf->Cell(0, 10, $titulo, 0, 1, 'C', 0, '', 1);
        //obtener la descripcion de persona de documento_tipo_dato        
        //        $dataPersona=DocumentoTipoDatoNegocio::create()->obtenerDocumentoTipoDatoXDocumentoIdXTipo($documentoId,5);
        $descripcionPersona = "Provider";

        $fecha = date_format((date_create($dataDocumento[0]['fecha_emision'])), 'd/m/Y');
        $pdf->Cell(0, 0, "Date: " . $fecha, 0, 1, 'L', 0, '', 0);

        $pdf->Cell(0, 0, $descripcionPersona . ": " . $dataDocumento[0]['nombre'], 0, 1, 'L', 0, '', 0);
        //        $pdf->Cell(0, 0, $descripcionPersona.": ".$dataDocumento[0]['nombre'], 0, 1, 'L', 0, '', 0);        
        $pdf->Cell(0, 0, "Address: " . $dataDocumento[0]['direccion'], 0, 1, 'L', 0, '', 0);
        //        $pdf->Cell(0, 0, $dataDocumento[0]['persona_documento_tipo'].": ".$dataDocumento[0]['codigo_identificacion'], 0, 1, 'L', 0, '', 0);         

        if (!ObjectUtil::isEmpty($dataDocumento[0]['descripcion'])) {
            $pdf->Cell(0, 0, "Description: " . $dataDocumento[0]['descripcion'], 0, 1, 'L', 0, '', 0);
        }

        //        $pdf->Cell(0, 0, "Coin: ".$dataDocumento[0]['moneda_descripcion'], 0, 1, 'L', 0, '', 0);   

        if (!ObjectUtil::isEmpty($dataDocumento[0]['comentario'])) {
            $pdf->Ln(7);
            $pdf->Cell(0, 0, $dataDocumento[0]['comentario'], 0, 1, 'L', 0, '', 0);
        }

        //espacio
        $pdf->Ln(5);

        //detalle
        $esp = '&nbsp;&nbsp;'; //espacio en blanco

        $tabla = '<table cellspacing="0" cellpadding="1" border="1">
                <tr>
                    <th style="text-align:center;" width="5%"><b>Item</b></th>
                    <th style="text-align:center;" width="18%"><b>Code</b></th>
                    <th style="text-align:center;" width="50%"><b>Description</b></th>
                    <th style="text-align:center;" width="11%"><b>QTY </b></th>
                    <th style="text-align:center;" width="16%"><b>Unit</b></th>
                </tr>
            ';

        foreach ($detalle as $index => $item) {
            $tabla = $tabla . '<tr>'
                . '<td style="text-align:rigth"  width="5%">' . $esp . ($index + 1) . $esp . '</td>'
                . '<td style="text-align:left"  width="18%">' . $esp . $item->bien_codigo . $esp . '</td>'
                . '<td style="text-align:left"  width="50%">' . $esp . $item->descripcion . $esp . '</td>'
                . '<td style="text-align:rigth"  width="11%">' . $esp . round($item->cantidad, 2) . $esp . '</td>'
                //                        . '<td style="text-align:left"  width="11%">' . $esp . $item->unidadMedida . $esp . '</td>'
                . '<td style="text-align:left"  width="16%">' . $esp . 'Pcs' . $esp . '</td>'
                . '</tr>';
        };

        //fin tabla detalle
        $tabla = $tabla . '</table>';

        $pdf->writeHTML($tabla, true, false, false, false, 'C');

        $pdf->Ln(175);

        //Close and output PDF document
        ob_clean();

        if ($tipoSalidaPDF == 'F') {
            $pdf->Output($url, $tipoSalidaPDF);
        }

        return $titulo;
    }

    public function generarDocumentoPDFCotizacionCompraExt($documentoId, $comentario, $tipoSalidaPDF, $url, $data)
    {
        //$tipoSalidaPDF: F-> guarda local
        //obtenemos la data        
        $dataDocumentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXDocumentoId($documentoId);
        $documentoTipoId = $dataDocumentoTipo[0]['id'];

        $dataDocumento = $data->dataDocumento;
        $documentoDatoValor = $data->documentoDatoValor;
        $detalle = $data->detalle;
        $dataEmpresa = $data->dataEmpresa;

        // create new PDF document                      
        $pdf = new TCPDF('p', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); //'p' is vertical and it is the default and 'l' is the horizonal.
        // set document information
        $pdf->SetCreator('Imagina Technologies S.A.C.');
        $pdf->SetAuthor('Imagina Technologies S.A.C.');
        $pdf->SetTitle(strtoupper($dataDocumentoTipo[0]['descripcion']));

        $cabeceraPDF1 = $dataEmpresa[0]['razon_social'] //                ."\nSoluciones Integrales de Perforación"
        ;

        $cabeceraPDF2 = $dataEmpresa[0]['direccion'] . "\n" .
            "Phone: 044-728609\n" .
            "E-mail: gcardenas@bhdrillingtools.com Web site: www.bhdrillingtools.com\n" .
            "RUC: " . $dataEmpresa[0]['ruc'];
        $PDF_HEADER_LOGO_WIDTH = 40;
        $pdf->SetHeaderData('logo.PNG', $PDF_HEADER_LOGO_WIDTH, $cabeceraPDF1, $cabeceraPDF2, array(0, 0, 0), array(0, 0, 0));
        $pdf->setFooterData(array(0, 0, 0), array(0, 0, 0)); // 0,0,0
        // set header and footer fonts
        $PDF_FONT_SIZE_MAIN = 9;
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', $PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $PDF_MARGIN_FOOTER = 10;
        $pdf->SetFooterMargin($PDF_MARGIN_FOOTER);

        // set auto page breaks
        $PDF_MARGIN_BOTTOM = 10;
        $pdf->SetAutoPageBreak(TRUE, $PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // --------------GENERAR PDF-------------------------------------------
        // set font
        $pdf->SetFont('helvetica', '', 9);

        // add a page
        $pdf->AddPage();

        $serieDocumento = '';
        if (!ObjectUtil::isEmpty($dataDocumento[0]['serie'])) {
            $serieDocumento = $dataDocumento[0]['serie'] . " - ";
        }


        foreach ($documentoDatoValor as $indice => $item) {
            $txtDescripcion = $item['descripcion'];
            $valorItem = $item['valor'];

            if ($item['tipo'] == 1) {
                $valorItem = number_format($valorItem, 2, ".", ",");
            }

            if ($item['tipo'] == 3) {
                $valorItem = date_format((date_create($valorItem)), 'd/m/Y');
            }

            switch ((int) $item['documento_tipo_id']) {
                case 2612:
                    $dtdFormaPago = $valorItem;
                    break;
                case 2627:
                    $dtdTiempoEntrega = $valorItem;
                    break;
                case 2628:
                    $dtdCondicionComercial = $valorItem;
                    break;
            }
        };

        //        $ventaCotizacion=strpos(($dataDocumento[0]['documento_tipo_descripcion']), 'V. Cotizacion');  
        $titulo = "PURCHASE REQUIREMENT " . $serieDocumento . $dataDocumento[0]['numero'];
        //        $titulo=strtoupper($dataDocumento[0]['documento_tipo_descripcion'])." ".$serieDocumento.$dataDocumento[0]['numero'];
        $pdf->Ln(5);
        $pdf->writeHTMLCell(0, 0, '', '', "<h2>" . $titulo . "</h2>", 0, 1, 0, true, 'C', true);
        $pdf->Ln(5);
        //        $pdf->Cell(0, 10, $titulo, 0, 1, 'C', 0, '', 1);
        //obtener la descripcion de persona de documento_tipo_dato        
        //        $dataPersona=DocumentoTipoDatoNegocio::create()->obtenerDocumentoTipoDatoXDocumentoIdXTipo($documentoId,5);
        $descripcionPersona = "Provider";

        $fecha = date_format((date_create($dataDocumento[0]['fecha_emision'])), 'd/m/Y');
        $pdf->Cell(0, 0, "Date: " . $fecha, 0, 1, 'L', 0, '', 0);
        $pdf->Cell(0, 0, $descripcionPersona . ": " . $dataDocumento[0]['nombre'], 0, 1, 'L', 0, '', 0);
        $pdf->Cell(0, 0, "Address: " . $dataDocumento[0]['direccion'], 0, 1, 'L', 0, '', 0);
        //        $pdf->Cell(0, 0, $dataDocumento[0]['persona_documento_tipo'].": ".$dataDocumento[0]['codigo_identificacion'], 0, 1, 'L', 0, '', 0);         

        if (!ObjectUtil::isEmpty($dataDocumento[0]['descripcion'])) {
            $pdf->Cell(0, 0, "Description: " . $dataDocumento[0]['descripcion'], 0, 1, 'L', 0, '', 0);
        }

        //        $pdf->Cell(0, 0, "Coin: ".$dataDocumento[0]['moneda_descripcion'], 0, 1, 'L', 0, '', 0);   

        $espacioComentario = 0;
        if (!ObjectUtil::isEmpty($dataDocumento[0]['comentario'])) {
            $pdf->Ln(7);
            $pdf->Cell(0, 0, $dataDocumento[0]['comentario'], 0, 1, 'L', 0, '', 0);
            $espacioComentario = 12;
        }

        //espacio
        $pdf->Ln(5);

        //detalle
        $esp = '&nbsp;&nbsp;'; //espacio en blanco

        $tabla = '<table cellspacing="0" cellpadding="1" border="1">
                <tr>
                    <th style="text-align:center;" width="5%"><b>Item</b></th>
                    <th style="text-align:center;" width="18%"><b>Code</b></th>
                    <th style="text-align:center;" width="50%"><b>Description</b></th>
                    <th style="text-align:center;" width="11%"><b>QTY </b></th>
                    <th style="text-align:center;" width="16%"><b>Unit</b></th>
                </tr>
            ';

        $cont = 0;
        foreach ($detalle as $index => $item) {
            $cont++;
            if (strlen($item->descripcion) > 39) {
                $cont++;
            }

            $tabla = $tabla . '<tr>'
                . '<td style="text-align:rigth"  width="5%">' . $esp . ($index + 1) . $esp . '</td>'
                . '<td style="text-align:left"  width="18%">' . $esp . $item->bien_codigo . $esp . '</td>'
                . '<td style="text-align:left"  width="50%">' . $esp . $item->descripcion . $esp . '</td>'
                . '<td style="text-align:rigth"  width="11%">' . $esp . round($item->cantidad, 2) . $esp . '</td>'
                //                        . '<td style="text-align:left"  width="11%">' . $esp . $item->unidadMedida . $esp . '</td>'
                . '<td style="text-align:left"  width="16%">' . $esp . 'Pcs' . $esp . '</td>'
                . '</tr>';
        };

        //fin tabla detalle
        $tabla = $tabla . '</table>';

        $pdf->writeHTML($tabla, true, false, false, false, 'C');

        $espaciado = 151 - ($cont * 4.4 + $espacioComentario);
        if ($espaciado < 15) {
            $espaciado = 15;
        }

        $pdf->Ln($espaciado); // cada fila es 4, total=131 , antes:115 
        //        $pdf->Ln(135);        
        if (!ObjectUtil::isEmpty($documentoDatoValor)) {
            $pdf->Ln(5);
            //        $borde=array('LTRB' => array('width' => 1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));

            $pdf->Ln(5);
            $pdf->SetFillColor(255, 255, 255);

            $tabla = '<table cellspacing="0" cellpadding="1" style="text-align:left; border-style: solid; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px">
                        <tr>
                          <td width="30%"></td>
                          <td width="40%"></td>
                          <td width="30%"></td>
                        </tr>
                        <tr>
                          <td width="30%"></td>
                          <td width="40%"></td>
                          <td width="30%"></td>
                        </tr>
                        <tr>
                          <td width="30%"></td>
                          <td style="text-align:center; border-style: solid; border-top-width: 1px;"  width="40%">' . $esp . 'Made by:</td>
                          <td width="30%"></td>
                        </tr>
                        <tr>
                          <td width="30%"></td>
                          <td style="text-align:center;"  width="40%">' . $esp . $dataDocumento[0]['usuario'] . $esp . '</td>
                          <td width="30%"></td>
                        </tr>
                        <tr>
                          <td width="30%"></td>
                          <td width="40%"></td>
                          <td width="30%"></td>
                        </tr>
                    </table>';

            $pdf->writeHTML($tabla, true, false, false, false, 'C');
        }

        $pdf->Cell(0, 0, "Deadtime: " . date_format((date_create($dataDocumento[0]['fecha_tentativa'])), 'd/m/Y'), 0, 1, 'L', 0, '', 0);

        $pdf->MultiCell(120, 0, "Payment form: " . $dtdFormaPago, 0, 'L', 0, 0, '', '', true, 0, false, true, 40, 'T');
        $pdf->MultiCell(60, 0, "Delivery time: " . $dtdTiempoEntrega, 0, 'L', 0, 0, '', '', true, 0, false, true, 40, 'T');

        $pdf->Ln(4);
        //        $pdf->Cell(0, 0, "Place of delivery: ".$dtdLugarEntrega, 0, 1, 'L', 0, '', 0);   
        $pdf->Cell(0, 0, "Incoterms: " . $dtdCondicionComercial, 0, 1, 'L', 0, '', 0);

        //Close and output PDF document
        ob_clean();

        if ($tipoSalidaPDF == 'F') {
            $pdf->Output($url, $tipoSalidaPDF);
        }

        return $titulo;
    }

    public function generarDocumentoPDFOrdenCompraExt($documentoId, $comentario, $tipoSalidaPDF, $url, $data)
    {
        //$tipoSalidaPDF: F-> guarda local
        //obtenemos la data        
        $dataDocumentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXDocumentoId($documentoId);
        $documentoTipoId = $dataDocumentoTipo[0]['id'];

        $dataDocumento = $data->dataDocumento;
        $documentoDatoValor = $data->documentoDatoValor;
        $detalle = $data->detalle;
        $dataEmpresa = $data->dataEmpresa;
        $dataDocumentoRelacion = $data->documentoRelacionado;

        $usuarioRequerimiento = null;
        foreach ($dataDocumentoRelacion as $index => $itemR) {
            if ($itemR['identificador_negocio'] == 14) {
                $usuarioRequerimiento = $itemR['usuario'];
            }
        }

        if (ObjectUtil::isEmpty($usuarioRequerimiento)) {
            $usuarioRequerimiento = $dataDocumento[0]['usuario'];
        }

        // create new PDF document                      
        $pdf = new TCPDF('p', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); //'p' is vertical and it is the default and 'l' is the horizonal.
        // set document information
        $pdf->SetCreator('Imagina Technologies S.A.C.');
        $pdf->SetAuthor('Imagina Technologies S.A.C.');
        $pdf->SetTitle(strtoupper($dataDocumentoTipo[0]['descripcion']));

        $cabeceraPDF1 = $dataEmpresa[0]['razon_social'] //                ."\nSoluciones Integrales de Perforación"
        ;

        $cabeceraPDF2 = $dataEmpresa[0]['direccion'] . "\n" .
            "Phone: 044-728609\n" .
            "E-mail: gcardenas@bhdrillingtools.com Web site: www.bhdrillingtools.com\n" .
            "RUC: " . $dataEmpresa[0]['ruc'];
        $PDF_HEADER_LOGO_WIDTH = 40;
        $pdf->SetHeaderData('logo.PNG', $PDF_HEADER_LOGO_WIDTH, $cabeceraPDF1, $cabeceraPDF2, array(0, 0, 0), array(0, 0, 0));
        $pdf->setFooterData(array(0, 0, 0), array(0, 0, 0)); // 0,0,0
        // set header and footer fonts
        $PDF_FONT_SIZE_MAIN = 9;
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', $PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $PDF_MARGIN_FOOTER = 10;
        $pdf->SetFooterMargin($PDF_MARGIN_FOOTER);

        // set auto page breaks
        $PDF_MARGIN_BOTTOM = 10;
        $pdf->SetAutoPageBreak(TRUE, $PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // --------------GENERAR PDF-------------------------------------------
        // set font
        $pdf->SetFont('helvetica', '', 9);

        // add a page
        $pdf->AddPage();

        $serieDocumento = '';
        if (!ObjectUtil::isEmpty($dataDocumento[0]['serie'])) {
            $serieDocumento = $dataDocumento[0]['serie'] . " - ";
        }


        foreach ($documentoDatoValor as $indice => $item) {
            $txtDescripcion = $item['descripcion'];
            $valorItem = $item['valor'];

            if ($item['tipo'] == 1) {
                $valorItem = number_format($valorItem, 2, ".", ",");
            }

            if ($item['tipo'] == 3) {
                $valorItem = date_format((date_create($valorItem)), 'd/m/Y');
            }

            switch ((int) $item['documento_tipo_id']) {
                case 2613:
                    $dtdFormaPago = $valorItem;
                    break;
                case 2614:
                    $dtdCodigoPedido = $valorItem;
                    break;
                case 2596:
                    $dtdTiempoEntrega = $valorItem;
                    break;
                case 2597:
                    $dtdCondicionComercial = $valorItem;
                    break;
                case 5537:
                    $dtdLugarEntrega = $valorItem;
                    break;
            }
        };

        //        $ventaCotizacion=strpos(($dataDocumento[0]['documento_tipo_descripcion']), 'V. Cotizacion');  
        $titulo = "PURCHASE ORDER " . $serieDocumento . $dataDocumento[0]['numero'];
        //        $titulo=strtoupper($dataDocumento[0]['documento_tipo_descripcion'])." ".$serieDocumento.$dataDocumento[0]['numero'];
        $pdf->Ln(5);
        $pdf->writeHTMLCell(0, 0, '', '', "<h2>" . $titulo . "</h2>", 0, 1, 0, true, 'C', true);
        $pdf->Ln(5);
        //        $pdf->Cell(0, 10, $titulo, 0, 1, 'C', 0, '', 1);
        //obtener la descripcion de persona de documento_tipo_dato        
        //        $dataPersona=DocumentoTipoDatoNegocio::create()->obtenerDocumentoTipoDatoXDocumentoIdXTipo($documentoId,5);
        $descripcionPersona = "Provider";

        $fecha = date_format((date_create($dataDocumento[0]['fecha_emision'])), 'd/m/Y');
        $pdf->Cell(0, 0, "Date: " . $fecha, 0, 1, 'L', 0, '', 0);

        $pdf->MultiCell(120, 0, $descripcionPersona . ": " . $dataDocumento[0]['nombre'], 0, 'L', 0, 0, '', '', true, 0, false, true, 40, 'T');
        $pdf->MultiCell(60, 0, "Order: " . $dtdCodigoPedido, 0, 'L', 0, 0, '', '', true, 0, false, true, 40, 'T');

        $espacioNombrePersona = 0;
        if (!ObjectUtil::isEmpty($dataDocumento[0]['nombre'])) {
            $lon = strlen($dataDocumento[0]['nombre']);
            if (strlen($dataDocumento[0]['nombre']) >= 55) {
                $pdf->Ln(3);
            }
            $espacioNombrePersona = 3;
        }

        $pdf->Ln(4);
        //        $pdf->Cell(0, 0, $descripcionPersona.": ".$dataDocumento[0]['nombre'], 0, 1, 'L', 0, '', 0);        
        $pdf->Cell(0, 0, "Address: " . $dataDocumento[0]['direccion'], 0, 1, 'L', 0, '', 0);
        //        $pdf->Cell(0, 0, $dataDocumento[0]['persona_documento_tipo'].": ".$dataDocumento[0]['codigo_identificacion'], 0, 1, 'L', 0, '', 0);         

        if (!ObjectUtil::isEmpty($dataDocumento[0]['descripcion'])) {
            $pdf->Cell(0, 0, "Description: " . $dataDocumento[0]['descripcion'], 0, 1, 'L', 0, '', 0);
        }

        //        $pdf->Cell(0, 0, "Coin: ".$dataDocumento[0]['moneda_descripcion'], 0, 1, 'L', 0, '', 0);   

        $espacioComentario = 0;
        if (!ObjectUtil::isEmpty($dataDocumento[0]['comentario'])) {
            $pdf->Ln(7);
            $pdf->Cell(0, 0, $dataDocumento[0]['comentario'], 0, 1, 'L', 0, '', 0);
            $espacioComentario = 12;
        }

        //espacio
        $pdf->Ln(5);

        //detalle
        $esp = '&nbsp;&nbsp;'; //espacio en blanco

        $tabla = '<table cellspacing="0" cellpadding="1" border="1">
                <tr>
                    <th style="text-align:center;" width="5%"><b>Item</b></th>
                    <th style="text-align:center;" width="13%"><b>Code</b></th>
                    <th style="text-align:center;" width="42%"><b>Description</b></th>
                    <th style="text-align:center;" width="6%"><b>QTY </b></th>
                    <th style="text-align:center;" width="11%"><b>Unit</b></th>
                    <th style="text-align:center;" width="11%"><b>Price Unit</b></th>
                    <th style="text-align:center;" width="12%"><b>Total Price</b></th>
                </tr>
            ';

        $cont = 0;
        foreach ($detalle as $index => $item) {
            $cont++;
            if (strlen($item->descripcion) > 39 || strlen($item->bien_codigo) > 11) {
                $cont++;
            }

            $tabla = $tabla . '<tr>'
                . '<td style="text-align:rigth"  width="5%">' . $esp . ($index + 1) . $esp . '</td>'
                . '<td style="text-align:left"  width="13%">' . $esp . $item->bien_codigo . $esp . '</td>'
                . '<td style="text-align:left"  width="42%">' . $esp . $item->descripcion . $esp . '</td>'
                . '<td style="text-align:rigth"  width="6%">' . $esp . round($item->cantidad, 2) . $esp . '</td>'
                //                        . '<td style="text-align:left"  width="11%">' . $esp . $item->unidadMedida . $esp . '</td>'
                . '<td style="text-align:left"  width="11%">' . $esp . 'Pcs' . $esp . '</td>'
                . '<td style="text-align:rigth"  width="11%">' . $esp . number_format($item->precioUnitario, 2) . $esp . '</td>'
                . '<td style="text-align:rigth"  width="12%">' . $esp . number_format($item->importe, 2) . $esp . '</td>'
                . '</tr>';
        };

        //fin tabla detalle
        $tabla = $tabla . '</table>';

        $pdf->writeHTML($tabla, true, false, false, false, 'C');

        $espaciado = 151 - ($cont * 4.1 + $espacioComentario + $espacioNombrePersona);
        //        if ($espaciado < 15) {
        //            $espaciado = 15;
        //        }

        if ($espaciado < 5) {
            $pdf->AddPage();
        } else {
            $pdf->Ln($espaciado); // cada fila es 4, total=131 , antes:115 
        }
        //        $pdf->Ln(135);        
        if (!ObjectUtil::isEmpty($documentoDatoValor)) {
            $pdf->Ln(5);
            //        $borde=array('LTRB' => array('width' => 1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));

            $pdf->Ln(5);
            $pdf->SetFillColor(255, 255, 255);

            $tabla = '<table cellspacing="0" cellpadding="1" style="text-align:left; border-style: solid; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px">
                        <tr>
                          <td width="2%"></td>
                          <td width="23%"></td>
                          <td width="8%"></td>
                          <td width="25%"></td>
                          <td width="8%"></td>
                          <td width="14%"></td>
                          <td width="20%"></td>
                        </tr>
                        <tr>
                          <td width="2%"></td>
                          <td width="23%"></td>
                          <td width="8%"></td>
                          <td width="25%"></td>
                          <td width="8%"></td>
                          <td width="14%"></td>
                          <td style="text-align:rigth" width="20%"></td>
                        </tr>
                        <tr>
                          <td width="2%"></td>
                          <td style="text-align:center; border-style: solid; border-top-width: 1px;"  width="23%">' . $esp . 'Made by:</td>
                          <td width="8%"></td>
                          <td style="text-align:center; border-style: solid; border-top-width: 1px;"  width="25%">' . $esp . 'Approved by:</td>  
                          <td width="8%"></td>
                          <td width="14%"></td>
                          <td style="text-align:rigth" width="20%"></td>
                        </tr>
                        <tr>
                          <td width="2%"></td>
                          <td style="text-align:center;"  width="23%">' . $esp . $usuarioRequerimiento . $esp . '</td>
                          <td width="8%"></td>
                          <td style="text-align:center"  width="25%">' . $esp . $dataDocumento[0]['usuario'] . $esp . '</td>
                          <td width="8%"></td>
                          <td width="14%"  style="text-align:rigth">' . $esp . 'TOTAL ' . $dataDocumento[0]['moneda_simbolo'] . '</td>
                          <td style="text-align:center" width="20%">' . $esp . number_format($dataDocumento[0]['total'], 2) . $esp . '</td>
                        </tr>
                        <tr>
                          <td width="2%"></td>
                          <td width="23%"></td>
                          <td width="8%"></td>
                          <td width="25%"></td>
                          <td width="8%"></td>
                          <td width="14%"></td>
                          <td width="20%"></td>
                        </tr>
                    </table>';

            $pdf->writeHTML($tabla, true, false, false, false, 'C');
        }

        $pdf->Cell(0, 0, "Deadtime: " . date_format((date_create($dataDocumento[0]['fecha_tentativa'])), 'd/m/Y'), 0, 1, 'L', 0, '', 0);

        $pdf->MultiCell(120, 0, "Payment form: " . $dtdFormaPago, 0, 'L', 0, 0, '', '', true, 0, false, true, 40, 'T');
        $pdf->MultiCell(60, 0, "Delivery time: " . $dtdTiempoEntrega, 0, 'L', 0, 0, '', '', true, 0, false, true, 40, 'T');

        $pdf->Ln(4);
        //        $pdf->Cell(0, 0, "Place of delivery: ".$dtdLugarEntrega, 0, 1, 'L', 0, '', 0);   
        $pdf->Cell(0, 0, "Incoterms: " . $dtdCondicionComercial, 0, 1, 'L', 0, '', 0);

        //Close and output PDF document
        ob_clean();

        if ($tipoSalidaPDF == 'F') {
            $pdf->Output($url, $tipoSalidaPDF);
        }

        return $titulo;
    }

    public function generarDocumentoPDFCotizacion($documentoId, $comentario, $tipoSalidaPDF, $url, $data)
    {
        //$tipoSalidaPDF: F-> guarda local
        //obtenemos la data        
        $dataDocumentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXDocumentoId($documentoId);
        $documentoTipoId = $dataDocumentoTipo[0]['id'];

        $dataDocumento = $data->dataDocumento;
        $documentoDatoValor = $data->documentoDatoValor;
        $detalle = $data->detalle;
        $dataEmpresa = $data->dataEmpresa;

        // create new PDF document                      
        $pdf = new TCPDF('l', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); //'p' is vertical and it is the default and 'l' is the horizonal.
        // set document information
        $pdf->SetCreator('Imagina Technologies S.A.C.');
        $pdf->SetAuthor('Imagina Technologies S.A.C.');
        $pdf->SetTitle(strtoupper($dataDocumentoTipo[0]['descripcion']));

        $cabeceraPDF1 = $dataEmpresa[0]['razon_social'] //                ."\nSoluciones Integrales de Perforación"
        ;

        $cabeceraPDF2 = $dataEmpresa[0]['direccion'] . "\n" .
            "Telfs: 044-728609\n" .
            "E-mail: gcardenas@bhdrillingtools.com;ddominguez@bhdrillingtools.com Web site: www.bhdrillingtools.com\n" .
            "RUC: " . $dataEmpresa[0]['ruc'];
        $PDF_HEADER_LOGO_WIDTH = 40;
        $pdf->SetHeaderData('logo.PNG', $PDF_HEADER_LOGO_WIDTH, $cabeceraPDF1, $cabeceraPDF2, array(0, 0, 0), array(0, 0, 0));
        $pdf->setFooterData(array(0, 0, 0), array(0, 0, 0)); // 0,0,0
        // set header and footer fonts
        $PDF_FONT_SIZE_MAIN = 9;
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', $PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // --------------GENERAR PDF-------------------------------------------
        // set font
        $pdf->SetFont('helvetica', '', 9);

        // add a page
        $pdf->AddPage();

        $serieDocumento = '';
        if (!ObjectUtil::isEmpty($dataDocumento[0]['serie'])) {
            $serieDocumento = $dataDocumento[0]['serie'] . " - ";
        }


        foreach ($documentoDatoValor as $indice => $item) {
            $txtDescripcion = $item['descripcion'];
            $valorItem = $item['valor'];

            if ($item['tipo'] == 1) {
                $valorItem = number_format($valorItem, 2, ".", ",");
            }

            if ($item['tipo'] == 3) {
                $valorItem = date_format((date_create($valorItem)), 'd/m/Y');
            }

            switch ((int) $item['documento_tipo_id']) {
                case 1850:
                    $dtdModoEntrega = $valorItem;
                    break;
                    //                case 2615:
                    //                    $dtdVigencia = $valorItem;
                    //                    break;
                case 2880:
                    $dtdAtencion = $valorItem;
                    break;
                case 2616:
                    $dtdTiempoEntrega = $valorItem;
                    break;
                case 2617:
                    $dtdNuestraRef = $valorItem;
                    break;
            }
        };

        //        $ventaCotizacion=strpos(($dataDocumento[0]['documento_tipo_descripcion']), 'V. Cotizacion');  

        $titulo = strtoupper($dataDocumento[0]['documento_tipo_descripcion']) . " " . $serieDocumento . $dataDocumento[0]['numero'];
        $titulo2 = strtoupper($dataDocumento[0]['documento_tipo_descripcion']) . " <br> " . $serieDocumento . $dataDocumento[0]['numero'];
        $pdf->Ln(-20);
        $pdf->writeHTMLCell(0, 0, '', '', "<h2>" . $titulo2 . "</h2>", 0, 1, 0, true, 'R', true);
        $pdf->Ln(17);
        //        $pdf->Cell(0, 10, $titulo, 0, 1, 'C', 0, '', 1);
        //obtener la descripcion de persona de documento_tipo_dato        
        $dataPersona = DocumentoTipoDatoNegocio::create()->obtenerDocumentoTipoDatoXDocumentoIdXTipo($documentoId, 5);
        $descripcionPersona = $dataPersona[0]['descripcion'];

        $fecha = date_format((date_create($dataDocumento[0]['fecha_emision'])), 'd/m/Y');
        $pdf->Cell(0, 0, "Fecha: " . $fecha, 0, 1, 'L', 0, '', 0);

        $pdf->MultiCell(120, 0, $descripcionPersona . ": " . $dataDocumento[0]['nombre'], 0, 'L', 0, 0, '', '', true, 0, false, true, 40, 'T');
        $pdf->MultiCell(60, 0, $dataDocumento[0]['persona_documento_tipo'] . ": " . $dataDocumento[0]['codigo_identificacion'], 0, 'L', 0, 0, '', '', true, 0, false, true, 40, 'T');

        $pdf->Ln(4);
        //        $pdf->Cell(0, 0, $descripcionPersona.": ".$dataDocumento[0]['nombre'], 0, 1, 'L', 0, '', 0);        
        $pdf->Cell(0, 0, "Dirección: " . $dataDocumento[0]['direccion'], 0, 1, 'L', 0, '', 0);
        //        $pdf->Cell(0, 0, $dataDocumento[0]['persona_documento_tipo'].": ".$dataDocumento[0]['codigo_identificacion'], 0, 1, 'L', 0, '', 0);         

        if (!ObjectUtil::isEmpty($dataDocumento[0]['descripcion'])) {
            $pdf->Cell(0, 0, "Descripción: " . $dataDocumento[0]['descripcion'], 0, 1, 'L', 0, '', 0);
        }
        //        $pdf->Cell(0, 0, "Moneda: ".$dataDocumento[0]['moneda_descripcion'], 0, 1, 'L', 0, '', 0);        
        $pdf->MultiCell(120, 0, "Nuestra Ref: " . $dtdNuestraRef, 0, 'L', 0, 0, '', '', true, 0, false, true, 40, 'T');
        $pdf->MultiCell(60, 0, "Moneda: " . $dataDocumento[0]['moneda_descripcion'], 0, 'L', 0, 0, '', '', true, 0, false, true, 40, 'T');
        $pdf->MultiCell(90, 0, "Tiempo de entrega: " . $dtdTiempoEntrega, 0, 'L', 0, 0, '', '', true, 0, false, true, 40, 'T');

        $pdf->Ln(4);
        $pdf->Cell(0, 0, "Atención: " . $dtdAtencion, 0, 1, 'L', 0, '', 0);

        $pdf->Ln(8);
        $pdf->Cell(0, 0, 'Estimados señores:', 0, 1, 'L', 0, '', 0);
        $pdf->Ln(1);
        $pdf->Cell(0, 0, 'De acuerdo a vuestro requerimiento nos es grato presentarles nuestra cotización de los siguientes Productos y/o Servicios:', 0, 1, 'L', 0, '', 0);

        //espacio
        $pdf->Ln(5);

        //detalle
        $esp = '&nbsp;&nbsp;'; //espacio en blanco

        $tabla = '<table cellspacing="0" cellpadding="1" border="1">
                <tr>
                    <th style="text-align:center;" width="5%"><b>Item</b></th>
                    <th style="text-align:center;" width="13%"><b>Código</b></th>
                    <th style="text-align:center;" width="36%"><b>Descripción</b></th>                    
                    <th style="text-align:center;" width="8%"><b>Cantidad</b></th>
                    <th style="text-align:center;" width="8%"><b>P. Unit.</b></th>
                    <th style="text-align:center;" width="10%"><b>Sub Total</b></th>
                    <th style="text-align:center;" width="20%"><b>Comentario</b></th>
                </tr>
            ';

        foreach ($detalle as $index => $item) {
            $dataStock = BienNegocio::create()->obtenerStockTotalXBienIDXUnidadMedidaId($item->bienId, $item->unidadMedidaId);
            $stock = $dataStock[0]['stock'];
            $disponible = $stock;
            if ($stock >= $item->cantidad) {
                $disponible = $item->cantidad;
            }

            $tabla = $tabla . '<tr>'
                . '<td style="text-align:rigth"  width="5%">' . $esp . ($index + 1) . $esp . '</td>'
                . '<td style="text-align:left"  width="13%">' . $esp . $item->bien_codigo . $esp . '</td>'
                . '<td style="text-align:left"  width="36%">' . $esp . $item->descripcion . $esp . '</td>'
                . '<td style="text-align:rigth"  width="8%">' . $esp . round($item->cantidad, 2) . $esp . '</td>'
                . '<td style="text-align:rigth"  width="8%">' . $esp . number_format($item->precioUnitario, 2) . $esp . '</td>'
                . '<td style="text-align:rigth"  width="10%">' . $esp . number_format($item->importe, 2) . $esp . '</td>'
                . '<td style="text-align:left"  width="20%">' . $esp . $item->movimientoBienComentario . $esp . '</td>'
                . '</tr>';
        };

        //fin tabla detalle
        $tabla = $tabla . '</table>';

        $pdf->writeHTML($tabla, true, false, false, false, 'C');

        if (!ObjectUtil::isEmpty($documentoDatoValor)) {
            $pdf->Ln(5);
            //        $borde=array('LTRB' => array('width' => 1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));

            $pdf->Ln(5);

            $pdf->writeHTMLCell(0, 6, '', '', "<h4>CONDICIONES COMERCIALES(*)</h4>", 0, 1, 0, true, 'L', true); //'TB'->borde:0


            $pdf->Ln(1);
            $pdf->SetFillColor(255, 255, 255);

            $cuentaNumero = $dataDocumento[0]['cuenta_numero'];
            $cuentaData = CuentaNegocio::create()->obtenerCuentaXId($dataDocumento[0]['cuenta_id']);
            $valorElaboradoPor = explode(" ", $dataDocumento[0]['perfil_usuario']);
            $valorElaboradoPor = $dataDocumento[0]['usuario'] . ' | ' . $valorElaboradoPor[0];

            $dtdVigencia = date_format((date_create($dataDocumento[0]['fecha_vencimiento'])), 'd/m/Y');

            $diasCredito = Util::diasTranscurridos($dataDocumento[0]['fecha_emision'], $dataDocumento[0]['fecha_vencimiento']);

            $dtdFormaPago = $dataDocumento[0]['tipo_pago_descripcion'];

            $formaPagoCompl = '';
            if ($dataDocumento[0]['tipo_pago'] == 2) {
                $formaPagoCompl = ' a ' . $diasCredito . ' días';
            }

            $dtdFormaPagoDesc = $dtdFormaPago . $formaPagoCompl;

            if ($dataDocumento[0]['moneda_id'] != 2) {
                $tabla = '<table cellspacing="0" cellpadding="1" border="1"  style="text-align:left">
                        <tr>
                          <td>' . $esp . 'Modo de entrega</td>
                          <td>' . $esp . $dtdModoEntrega . $esp . '</td>
                          <td>' . $esp . 'Fecha vencimiento</td>
                          <td>' . $esp . $dtdVigencia . $esp . '</td>
                          <td>' . $esp . 'Subtotal ' . $dataDocumento[0]['moneda_simbolo'] . '</td>
                          <td style="text-align:rigth">' . $esp . number_format($dataDocumento[0]['subtotal'], 2) . $esp . '</td>
                        </tr>
                        <tr>
                          <td>' . $esp . 'Forma de pago</td>
                          <td>' . $esp . $dtdFormaPagoDesc . $esp . '</td>
                          <td>' . $esp . 'Marca</td>
                          <td>' . $esp . 'BH' . $esp . '</td>
                          <td>' . $esp . 'IGV 18% ' . $dataDocumento[0]['moneda_simbolo'] . '</td>
                          <td style="text-align:rigth">' . $esp . number_format($dataDocumento[0]['igv'], 2) . $esp . '</td>
                        </tr>
                        <tr>
                          <td rowspan="2">' . $esp . 'Observaciones</td>
                          <td colspan="3">' . $esp . 'Cuenta: ' . ((!ObjectUtil::isEmpty($cuentaData)) ?
                    $cuentaData[0]['descripcion'] . ': ' . $cuentaData[0]['numero'] . '<br>' .
                    $esp . $esp . $esp . $esp . $esp . $esp . $esp . '&nbsp; CCI: ' . $cuentaData[0]['cci'] : '') .
                    '</td>
                          <td rowspan="2">' . $esp . 'TOTAL ' . $dataDocumento[0]['moneda_simbolo'] . '</td>
                          <td rowspan="2" style="text-align:rigth">' . $esp . number_format($dataDocumento[0]['total'], 2) . $esp . '</td>
                        </tr>
                        <tr>
                          <td colspan="3">' . $esp . 'T/C: ' . number_format($dataDocumento[0]['cambio_personalizado'], 3) . $esp . ' (El T/C es referencial)</td>
                        </tr>
                        <tr>
                          <td colspan="4">' . $esp . '(*) Las operaciones comerciales al crédito estarán sujetas a evaluación y revisión del Area de Créditos y Cobranzas<br>
                                          (*) Disponibilidad: Disponibilidad sujeta a ventas previas
                          </td>
                          <td>' . $esp . 'TOTAL S/.</td>
                          <td style="text-align:rigth;">' . $esp . number_format($dataDocumento[0]['cambio_personalizado'] * $dataDocumento[0]['total'], 2) . $esp . '</td>
                        </tr>
                    </table>';
            } else {
                $tabla = '<table cellspacing="0" cellpadding="1" border="1"  style="text-align:left">
                        <tr>
                          <td>' . $esp . 'Modo de entrega</td>
                          <td>' . $esp . $dtdModoEntrega . $esp . '</td>
                          <td>' . $esp . 'Fecha vencimiento</td>
                          <td>' . $esp . $dtdVigencia . $esp . '</td>
                          <td>' . $esp . 'Subtotal ' . $dataDocumento[0]['moneda_simbolo'] . '</td>
                          <td style="text-align:rigth">' . $esp . number_format($dataDocumento[0]['subtotal'], 2) . $esp . '</td>
                        </tr>
                        <tr>
                          <td>' . $esp . 'Forma de pago</td>
                          <td>' . $esp . $dtdFormaPago . $esp . '</td>
                          <td>' . $esp . 'Marca</td>
                          <td>' . $esp . 'BH' . $esp . '</td>
                          <td>' . $esp . 'IGV 18% ' . $dataDocumento[0]['moneda_simbolo'] . '</td>
                          <td style="text-align:rigth">' . $esp . number_format($dataDocumento[0]['igv'], 2) . $esp . '</td>
                        </tr>
                        <tr>
                          <td>' . $esp . 'Observaciones</td>
                          <td colspan="3">' . $esp . 'Cuenta: ' . $cuentaNumero . $esp . '</td>
                          <td>' . $esp . 'TOTAL ' . $dataDocumento[0]['moneda_simbolo'] . '</td>
                          <td style="text-align:rigth">' . $esp . number_format($dataDocumento[0]['total'], 2) . $esp . '</td>
                        </tr>
                        <tr>
                          <td colspan="6">' . $esp . '(*) Las operaciones comerciales al crédito estarán sujetas a evaluación y revisión del Area de Créditos y Cobranzas<br>
                                          (*) Disponibilidad: Disponibilidad sujeta a ventas previas
                          </td>
                        </tr>
                    </table>';
            }

            $pdf->writeHTML($tabla, true, false, false, false, 'C');
        }

        //            $pdf->Ln(5);
        //            $pdf->Cell(0, 0,'Sin otro particular, agradeciendo su gentil atención, quedamos a la espera de vuestra pronta respuesta.', 0, 1, 'L', 0, '', 0);        
        //            $pdf->Ln(1);
        //            $pdf->Cell(0, 0,'Atentamente.', 0, 1, 'L', 0, '', 0);        
        //            
        //            //telefonos
        //            $pdf->Ln(20);
        //            $borde=array('R' => array('width' => 1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
        ////            $pdf->MultiCell(145, 0, 'TELEFAX', 0, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');
        ////            $pdf->MultiCell(3, 0, '', $borde, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');
        ////            $pdf->MultiCell(30, 0, '044 262811', 0, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');            
        ////            $pdf->Ln();
        //            $pdf->MultiCell(145, 0, 'FIJO', 0, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');
        //            $pdf->MultiCell(3, 0, '', $borde, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');
        //            $pdf->MultiCell(30, 0, '044 209454', 0, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');         
        //            $pdf->Ln();
        //            $pdf->MultiCell(145, 0, 'RPC', 0, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');
        //            $pdf->MultiCell(3, 0, '', $borde, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');
        //            $pdf->MultiCell(30, 0, '977192256', 0, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');         
        //            $pdf->Ln();
        //            $pdf->MultiCell(145, 0, 'RPM', 0, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');
        //            $pdf->MultiCell(3, 0, '', $borde, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');
        //            $pdf->MultiCell(30, 0, '*445213', 0, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');         
        //            $pdf->Ln();
        //            $pdf->MultiCell(145, 0, 'NEXTEL', 0, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');
        //            $pdf->MultiCell(3, 0, '', $borde, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');
        //            $pdf->MultiCell(30, 0, '836*3196', 0, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');         
        //            $pdf->Ln();
        //            $pdf->MultiCell(145, 0, 'CELULAR', 0, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');
        //            $pdf->MultiCell(3, 0, '', $borde, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');
        //            $pdf->MultiCell(30, 0, '965076817', 0, 'R', 1, 0, '', '', true, 0, false, true, 40, 'T');         
        //            $pdf->Ln();
        //agregar pagina
        //        $pdf->AddPage();
        //Close and output PDF document
        ob_clean();

        if ($tipoSalidaPDF == 'F') {
            $pdf->Output($url, $tipoSalidaPDF);
        }

        return $titulo;
    }

    public function enviarCorreoConPrecio($correo, $documentoId, $comentarioDocumento, $usuarioId, $plantillaId)
    {

        $plantilla = EmailPlantillaNegocio::create()->obtenerEmailPlantillaXID($plantillaId); //antes 3

        $documento = Documento::create()->obtenerDocumentoDatos($documentoId);

        $data = MovimientoNegocio::create()->visualizarDocumento($documentoId, $documento[0]['movimiento_id']);

        //dibujar cuerpo y detalle

        $nombreDocumentoTipo = '';
        $dataDocumento = '';

        // datos de documento
        if (!ObjectUtil::isEmpty($data->dataDocumento)) {

            $nombreDocumentoTipo = $data->dataDocumento[0]['nombre_documento'];

            // Mostraremos la data en filas de dos columnas            
            foreach ($data->dataDocumento as $index => $item) {
                $html = '<tr><td style=\'text-align:left;padding:0 25px 5px;font-size:14px;line-height:1.5;width:80%\'><b>' . $item['descripcion'] . ': </b>';

                $valor = $item['valor'];

                if (!ObjectUtil::isEmpty($valor)) {
                    switch ((int) $item['tipo']) {
                        case 3:
                        case 9:
                        case 10:
                        case 11:
                            $time = strtotime($valor);
                            $valor = date('d/m/Y', $time);
                            break;
                        case 1:
                        case 14:
                        case 15:
                        case 16:
                        case 19:
                            $valor = number_format($valor, 2, ".", ",");
                            break;
                    }
                }

                $html = $html . $valor;

                $html = $html . '</td></tr>';
                $dataDocumento = $dataDocumento . $html;
            }
        }

        // detalle de documento
        //obtener configuracion de las columnas de movimiento_tipo
        $res = MovimientoTipoNegocio::create()->obtenerXDocumentoId($documentoId);
        if (!ObjectUtil::isEmpty($res)) {
            $movimientoTipoId = $res[0]['movimiento_tipo_id'];
            $dataMovimientoTipoColumna = MovimientoTipoNegocio::create()->obtenerMovimientoTipoColumna($movimientoTipoId);
        }

        //dibujando la cabecera
        $dataDetalle = '<table  border=1 cellspacing=0 cellpadding=5 bordercolor="000000"  width="80%">
                        <thead>';

        $html = '<tr>';
        if ($this->existeColumnaCodigo($dataMovimientoTipoColumna, 15)) { //Organizador
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Organizador</th>";
        }
        $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Cantidad</th>";
        $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Unidad</th>";
        $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Descripcion</th>";

        if ($this->existeColumnaCodigo($dataMovimientoTipoColumna, 5)) { //Precio unitario
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>PU</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Total</th>";
        }
        $html = $html . '</tr>';

        $dataDetalle = $dataDetalle . $html;
        $dataDetalle = $dataDetalle . '<thead>';
        $dataDetalle = $dataDetalle . '<tbody>';

        if (!ObjectUtil::isEmpty($data->detalleDocumento)) {
            foreach ($data->detalleDocumento as $index => $item) {

                $html = '<tr>';
                if ($this->existeColumnaCodigo($dataMovimientoTipoColumna, 15)) { //Organizador
                    $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item->organizador;
                }
                $html = $html . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . number_format($item->cantidad, 2, ".", ",");
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item->unidadMedida;
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item->descripcion;

                if ($this->existeColumnaCodigo($dataMovimientoTipoColumna, 5)) { //Precio unitario
                    $html = $html . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . number_format($item->precioUnitario, 2, ".", ",");
                    $html = $html . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . number_format($item->importe, 2, ".", ",");
                }
                $html = $html . '</tr>';

                $dataDetalle = $dataDetalle . $html;
            }
        }
        $dataDetalle = $dataDetalle . '</tbody></table>';

        $direccionEmpresa = '<tr><td style="text-align: left; padding: 0 25px 10px; font-size: 14px; line-height: 1.5; width: 80%">Documento generado en la empresa '
            . $data->direccionEmpresa[0]['razon_social']
            . ' ubicada en '
            . $data->direccionEmpresa[0]['direccion']
            . '</td></tr>';

        //fin dibujo
        //envio correo
        $usuarioId;
        $correo;
        $nombreDocumentoTipo;
        $dataDocumento;
        $dataDetalle;
        $comentarioDocumento;
        $direccionEmpresa;

        //logica correo:             
        //        $plantilla = EmailPlantillaNegocio::create()->obtenerEmailPlantillaXID(3);

        $asunto = $plantilla[0]["asunto"];
        $cuerpo = $plantilla[0]["cuerpo"];

        $asunto = str_replace("[|documento_tipo|]", $nombreDocumentoTipo, $asunto);
        $cuerpo = str_replace("[|documento_tipo|]", $nombreDocumentoTipo, $cuerpo);
        $cuerpo = str_replace("[|dato_documento|]", $dataDocumento, $cuerpo);
        $cuerpo = str_replace("[|detalle_documento|]", $dataDetalle, $cuerpo);
        $cuerpo = str_replace("[|comentario_documento|]", $comentarioDocumento, $cuerpo);
        $cuerpo = str_replace("[|direccion_empresa|]", $direccionEmpresa, $cuerpo);

        $res = EmailEnvioNegocio::create()->insertarEmailEnvio($correo, $asunto, $cuerpo, 1, $usuarioId);

        $this->setMensajeEmergente("Se enviará el correo aproximadamente en un minuto");
        return 1;
    }

    public function enviarMovimientoEmailCorreoMasPDF($correo, $documentoId, $comentario, $usuarioId, $plantillaId)
    {

        $dataDocumentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXDocumentoId($documentoId);
        $documentoTipoId = $dataDocumentoTipo[0]['id'];
        $data = MovimientoNegocio::create()->imprimir($documentoId, $documentoTipoId);
        $dataDocumento = $data->dataDocumento;

        $serieDocumento = '';
        if (!ObjectUtil::isEmpty($dataDocumento[0]['serie'])) {
            $serieDocumento = $dataDocumento[0]['serie'] . " - ";
        }

        $dataPersona = DocumentoTipoDatoNegocio::create()->obtenerDocumentoTipoDatoXDocumentoIdXTipo($documentoId, 5);
        $descripcionPersona = $dataPersona[0]['descripcion'];
        $titulo = strtoupper($dataDocumento[0]['documento_tipo_descripcion']) . " " . $serieDocumento . $dataDocumento[0]['numero'];

        $plantilla = EmailPlantillaNegocio::create()->obtenerEmailPlantillaXID($plantillaId);

        $hoy = date("Y_m_d_H_i_s");
        $url = __DIR__ . '/../../vistas/com/movimiento/documentos/documento_' . $hoy . '_' . $usuarioId . '.pdf';

        //crear PDF
        $this->generarDocumentoPDF($documentoId, $comentario, 'F', $url, $data);

        //---------------------GENERACION DE LA PARTE TEXTUAL DEL CORREO-------------------------------------------

        $documento = Documento::create()->obtenerDocumentoDatos($documentoId);
        $data = MovimientoNegocio::create()->visualizarDocumento($documentoId, $documento[0]['movimiento_id']);

        //dibujar cuerpo y detalle

        $nombreDocumentoTipo = '';
        $dataDocumento = '';

        // datos de documento
        if (!ObjectUtil::isEmpty($data->dataDocumento)) {

            $nombreDocumentoTipo = $data->dataDocumento[0]['nombre_documento'];
            $monedaDescripcionHTML = '<tr><td style=\'text-align:left;padding:0 25px 5px;font-size:14px;line-height:1.5;width:80%\'><b>Moneda: </b>' . $data->dataDocumento[0]['moneda_descripcion'] . '</td></tr>';

            // Mostraremos la data en filas de dos columnas            
            foreach ($data->dataDocumento as $index => $item) {
                $html = '<tr><td style=\'text-align:left;padding:0 25px 5px;font-size:14px;line-height:1.5;width:80%\'><b>' . $item['descripcion'] . ': </b>';

                $valor = $item['valor'];

                if (!ObjectUtil::isEmpty($valor)) {
                    switch ((int) $item['tipo']) {
                        case 3:
                        case 9:
                        case 10:
                        case 11:
                            $time = strtotime($valor);
                            $valor = date('d/m/Y', $time);
                            break;
                        case 1:
                        case 14:
                        case 15:
                        case 16:
                        case 19:
                            $valor = number_format($valor, 2, ".", ",");
                            break;
                    }
                }

                $html = $html . $valor;

                $html = $html . '</td></tr>';
                $dataDocumento = $dataDocumento . $html;
            }
            $dataDocumento = $dataDocumento . $monedaDescripcionHTML;
        }

        // detalle de documento
        //obtener configuracion de las columnas de movimiento_tipo
        $res = MovimientoTipoNegocio::create()->obtenerXDocumentoId($documentoId);
        if (!ObjectUtil::isEmpty($res)) {
            $movimientoTipoId = $res[0]['movimiento_tipo_id'];
            $dataMovimientoTipoColumna = MovimientoTipoNegocio::create()->obtenerMovimientoTipoColumna($movimientoTipoId);
        }

        //dibujando la cabecera
        $dataDetalle = '<table  border=1 cellspacing=0 cellpadding=5 bordercolor="000000"  width="80%">
                        <thead>';

        $html = '<tr>';
        if ($this->existeColumnaCodigo($dataMovimientoTipoColumna, 15)) { //Organizador
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Organizador</th>";
        }
        $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Cantidad</th>";
        $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Unidad</th>";
        $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Descripcion</th>";

        if ($this->existeColumnaCodigo($dataMovimientoTipoColumna, 5)) { //Precio unitario
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>PU</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Total</th>";
        }
        $html = $html . '</tr>';

        $dataDetalle = $dataDetalle . $html;
        $dataDetalle = $dataDetalle . '<thead>';
        $dataDetalle = $dataDetalle . '<tbody>';

        if (!ObjectUtil::isEmpty($data->detalleDocumento)) {
            foreach ($data->detalleDocumento as $index => $item) {

                $html = '<tr>';
                if ($this->existeColumnaCodigo($dataMovimientoTipoColumna, 15)) { //Organizador
                    $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item->organizador;
                }
                $html = $html . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . number_format($item->cantidad, 2, ".", ",");
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item->unidadMedida;
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item->descripcion;

                if ($this->existeColumnaCodigo($dataMovimientoTipoColumna, 5)) { //Precio unitario
                    $html = $html . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . number_format($item->precioUnitario, 2, ".", ",");
                    $html = $html . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . number_format($item->importe, 2, ".", ",");
                }
                $html = $html . '</tr>';

                $dataDetalle = $dataDetalle . $html;
            }
        }
        $dataDetalle = $dataDetalle . '</tbody></table>';

        //fin dibujo    
        //logica correo:             
        $asunto = $plantilla[0]["asunto"];
        $cuerpo = $plantilla[0]["cuerpo"];

        $asunto = str_replace("[|documento_tipo|]", $nombreDocumentoTipo, $asunto);
        $cuerpo = str_replace("[|documento_tipo|]", $nombreDocumentoTipo, $cuerpo);
        $cuerpo = str_replace("[|dato_documento|]", $dataDocumento, $cuerpo);
        $cuerpo = str_replace("[|detalle_documento|]", $dataDetalle, $cuerpo);
        $cuerpo = str_replace("[|comentario_documento|]", $comentario, $cuerpo);
        $cuerpo = str_replace("[|direccion_empresa|]", $direccionEmpresa, $cuerpo);

        //-----------------------------------------------------------------        
        //envio de email        
        $nombreArchivo = $dataDocumentoTipo[0]['descripcion'] . ".pdf";

        $res = EmailEnvioNegocio::create()->insertarEmailEnvio($correo, $asunto, $cuerpo, 1, $usuarioId, $url, $nombreArchivo);

        if (!ObjectUtil::isEmpty($res[0]['id'])) {
            $this->setMensajeEmergente("Se enviará el correo aproximadamente en un minuto");
        }

        return $res;
    }

    public function obtenerMovimientoTipoColumnaLista($opcionId)
    {
        $movimientoTipoId = $this->obtenerIdXOpcion($opcionId);
        return Movimiento::create()->obtenerMovimientoTipoColumnaListaXMovimientoTipoId($movimientoTipoId);
    }

    public function enviarCorreosMovimiento($usuarioId, $txtCorreo, $correosSeleccionados, $respuestaCorreo, $comentario)
    {
        $plantilla = $respuestaCorreo['dataPlantilla'];
        $accionEnvio = $plantilla[0]['accion_funcion'];
        $documentoId = $respuestaCorreo['documentoId'];

        $correos = '';
        if (!ObjectUtil::isEmpty($correosSeleccionados)) {
            foreach ($correosSeleccionados as $email) {
                $correos = $correos . $email . ';';
            }
        }
        if (!ObjectUtil::isEmpty($txtCorreo)) {
            $correos = $correos . $txtCorreo;
        }

        $plantillaId = $plantilla[0]["email_plantilla_id"];
        $this->enviarCorreoXAccion($correos, $comentario, $accionEnvio, $documentoId, $plantillaId, $usuarioId);
    }

    public function obtenerEmailsXAccion($opcionId, $accionEnvio, $documentoId)
    {
        //obtener email de plantilla            
        $movimientoTipoId = $this->obtenerIdXOpcion($opcionId);
        $plantilla = EmailPlantillaNegocio::create()->obtenerPlantillaDestinatarioXAccionDescripcionXMovimientoTipoId($accionEnvio, $movimientoTipoId);
        $dataPersona = DocumentoNegocio::create()->obtenerPersonaXDocumentoId($documentoId);

        $correosPlantilla = EmailPlantillaNegocio::create()->obtenerEmailXDestinatario($plantilla[0]["destinatario"], $documentoId, $dataPersona[0]['id']);

        $correos = '';
        if (!ObjectUtil::isEmpty($correosPlantilla)) {
            foreach ($correosPlantilla as $email) {
                $correos = $correos . $email . ';';
            }
        }
        $resultado->correo = $correos;
        $resultado->plantilla = $plantilla;

        return $resultado;
    }

    public function enviarCorreoXAccion($correos, $comentario, $accionEnvio, $documentoId, $plantillaId, $usuarioId)
    {
        switch ($accionEnvio) {
            case "enviarPDF":
                return $this->enviarMovimientoEmailPDF($correos, $documentoId, $comentario, $usuarioId, $plantillaId);
            case "enviarCorreo":
                return $this->enviarCorreoConPrecio($correos, $documentoId, $comentario, $usuarioId, $plantillaId);
            case "enviarCorreoPDF":
                return $this->enviarMovimientoEmailCorreoMasPDF($correos, $documentoId, $comentario, $usuarioId, $plantillaId);
        }
    }

    public function obtenerMovimientoEntradaSalidaXFechaXBienId($fechaEmision, $bienId)
    {
        return Movimiento::create()->obtenerMovimientoEntradaSalidaXFechaXBienId($fechaEmision, $bienId);
    }

    public function getUserEmailByUserId($id)
    {
        return Movimiento::create()->getUserEmailByUserId($id);
    }

    public function verificarDocumentoObligatorioExiste($actualId)
    {
        return Movimiento::create()->verificarDocumentoObligatorioExiste($actualId);
    }

    public function verificarDocumentoEsObligatorioXOpcionID($opcionId)
    {
        return Movimiento::create()->verificarDocumentoEsObligatorioXOpcionID($opcionId);
    }

    public function guardarDocumentoAtencionSolicitud($origenId, $destinoId, $cantidadAtendida, $usuarioId)
    {
        return MovimientoBien::create()->guardarDocumentoAtencionSolicitud($origenId, $destinoId, $cantidadAtendida, $usuarioId);
    }

    public function obtenerEstadoNegocioXMovimientoId($movimientoId)
    {
        return Movimiento::create()->obtenerEstadoNegocioXMovimientoId($movimientoId);
    }

    public function generarBienUnicoXDocumentoId($documentoId, $usuarioId)
    {
        $dataDocumento = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);
        if ($dataDocumento[0]['estado'] != 1) {
            throw new WarningException("Documento anulado, no se puede generar los productos únicos");
        }

        $dataBienUnico = BienUnicoNegocio::create()->obtenerMovimientoBienUnicoXDocumentoId($documentoId);
        if (!ObjectUtil::isEmpty($dataBienUnico)) {
            throw new WarningException("Ya se generó los productos únicos, refresque la página.");
        }

        $movimientoId = $dataDocumento[0]['movimiento_id'];
        $dataMovBien = MovimientoBien::create()->obtenerXIdMovimiento($movimientoId);

        $fechaEmision = $dataDocumento[0]['fecha_emision'];

        $anio = date_format((date_create($fechaEmision)), 'Y');
        $mes = date_format((date_create($fechaEmision)), 'm');

        foreach ($dataMovBien as $item) {
            //CODIGO PRODUCTO SIN ESPACIOS (20) + AÑO (4) + MES (2) + CORRELATIVO DE (7)
            //sin concatenacion de ceros, sin BH, + periodo, 5 digitos

            $codigoBien = $item['bien_codigo'];
            $codigoBien = str_replace(' ', '', $codigoBien);
            $codigoBien = str_replace('BH', '', $codigoBien);

            if (strlen($codigoBien) > 20) {
                $codigoBien = substr($codigoBien, 0, 20);
            }

            $codBienUnico = $codigoBien . $anio . $mes;

            $dataCorrelativo = BienUnico::create()->bienUnicoObternerUltimoCodigoCorrelativo($codBienUnico);

            $correlativo = 0;
            if (!ObjectUtil::isEmpty($dataCorrelativo)) {
                $correlativo = $dataCorrelativo[0]['correlativo'] * 1;
            }

            for ($i = 0; $i < $item['cantidad']; $i++) {

                $correlativo++;
                $correlativoCadena = str_pad($correlativo, 5, "0", STR_PAD_LEFT);

                $codigoBU = $codigoBien . $anio . $mes . $correlativoCadena;

                //insertar bien unico

                $resBU = BienUnico::create()->insertarBienUnico($item['bien_id'], $codigoBU, $usuarioId);

                if ($resBU[0]['vout_estado'] == 1) {
                    $resMBU = BienUnico::create()->insertarMovimientoBienUnico($item['movimiento_bien_id'], $resBU[0]['vout_id'], 1, $usuarioId);
                } else {
                    throw new WarningException("Error al guardar bien unico");
                }
            }
        }

        $r = DocumentoNegocio::create()->actualizarEstadoQRXDocumentoId($documentoId, 2);

        return $resBU;
    }

    function anularBienUnicoXDocumentoId($documentoId)
    {
        return BienUnico::create()->anularBienUnicoXDocumentoId($documentoId);
    }

    function obtenerBienUnicoConfiguracionInicial($documentoId)
    {
        $dataDocumento = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);

        $resultado->dataBienUnicoDisponible = BienUnicoNegocio::create()->obtenerBienUnicoDisponibleXDocumentoId($documentoId);
        $resultado->dataDocumento = $dataDocumento;
        $resultado->dataMovimientoBien = MovimientoBien::create()->obtenerXIdMovimiento($dataDocumento[0]['movimiento_id']);
        $resultado->dataMovimientoBienUnico = BienUnicoNegocio::create()->obtenerMovimientoBienUnicoXDocumentoId($documentoId);

        return $resultado;
    }

    function guardarBienUnicoDetalle($listaBienUnicoDetalle, $listaBienUnicoDetalleEliminado, $usuarioId, $opcionId, $estadoQR)
    {

        if (!ObjectUtil::isEmpty($listaBienUnicoDetalleEliminado)) {
            foreach ($listaBienUnicoDetalleEliminado as $itemEliminar) {
                $res = BienUnico::create()->eliminarMovimientoBienUnico($itemEliminar);

                if ($res[0]['vout_exito'] == 0) {
                    throw new WarningException($res[0]['vout_mensaje']);
                }
            }
        }

        $resDocumento = DocumentoNegocio::create()->obtenerDocumentoIdXMovimientoBienId($listaBienUnicoDetalle[0]['movimiento_bien_id']);
        $r = DocumentoNegocio::create()->actualizarEstadoQRXDocumentoId($resDocumento[0]['documento_id'], $estadoQR);

        foreach ($listaBienUnicoDetalle as $item) {
            $bienUnicoId = $item['bien_unico_id'];
            $movimientoBienId = $item['movimiento_bien_id'];

            $res = BienUnico::create()->guardarMovimientoBienUnico($bienUnicoId, $movimientoBienId, $usuarioId);

            if ($res[0]['vout_exito'] == 0) {
                throw new WarningException($res[0]['vout_mensaje']);
            }
        }

        $resultado->respuesta = $res;

        $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);
        $resultado->indicador = $movimientoTipo[0]["indicador"];

        return $resultado;
    }

    function obtenerDataEstadoNegocioPago()
    {
        return Tabla::create()->obtenerXPadreId(56);
    }

    function obtenerDataEstadoEntregaPedidos()
    {
        return Tabla::create()->obtenerXPadreId(78);
    }

    private function guardarAnticipos($resDataDocumento, $anticiposAAplicar, $usuarioId, $camposDinamicos, $monedaId)
    {
        if (ObjectUtil::isEmpty($anticiposAAplicar["data"]))
            return;

        $documentoId = $resDataDocumento->documentoId;
        $proveedorId = $this->obtenerValorCampoDinamicoPorTipo($camposDinamicos, 5);
        $fecha = $this->obtenerValorCampoDinamicoPorTipo($camposDinamicos, 9);
        $totalDocumento = $this->obtenerValorCampoDinamicoPorTipo($camposDinamicos, 14) * 1;
        //        $actividadEfectivo = $anticiposAAplicar["actividadId"];
        $empresaId = $anticiposAAplicar["empresaId"];

        $retencion = 1;
        $monedaPago = $monedaId;
        $dolares = ($monedaId == 4) ? 1 : 0;

        $documentoAPagar = array(
            array(
                documentoId => $documentoId,
                tipoDocumento => '',
                numero => '',
                serie => '',
                pendiente => (float) $totalDocumento,
                total => (float) $totalDocumento,
                dolares => $dolares
            )
        );
        $documentoPagoConDocumento = array();
        $totalPagos = 0;
        foreach ($anticiposAAplicar["data"] as $anticipo) {
            array_push(
                $documentoPagoConDocumento,
                array(
                    documentoId => $anticipo["documentoId"],
                    tipoDocumento => '',
                    tipoDocumentoId => '',
                    numero => '',
                    serie => '',
                    pendiente => (float) $anticipo["pendiente"] * 1,
                    total => (float) $anticipo["pendiente"] * 1,
                    monto => (float) $anticipo["pendiente"] * 1,
                    dolares => $dolares
                )
            );
        }

        // Como todo se hace en la misma moneda, setearemos el tc en 1 
        $tipoCambio = 1;
        $pago = PagoNegocio::create()->registrarPago($proveedorId, $fecha, $documentoAPagar, $documentoPagoConDocumento, $usuarioId, 0, $tipoCambio, $monedaPago, $retencion, $empresaId, null);
    }

    public function relacionarDocumento($documentoIdOrigen, $documentoIdARelacionar, $usuarioId)
    {
        $earRelacion = NULL;
        //VALIDAR QUE NO RELACIONEN UN DOCUMENTO YA RELACIONADO
        $dataRel = DocumentoNegocio::create()->obtenerDocumentoRelacionadoXDocumentoIdXDocumentoRelacionadoId($documentoIdOrigen, $documentoIdARelacionar);

        if (!ObjectUtil::isEmpty($dataRel)) {
            throw new WarningException('Documento a relacionar duplicado');
        }
        $banderaComprobanteCompraNacional = NULL;
        $camposDinamicos = DocumentoNegocio::create()->obtenerDetalleDocumento($documentoIdOrigen);
        if (!ObjectUtil::isEmpty($camposDinamicos)) {
            $banderaComprobanteCompraNacional = Util::filtrarArrayPorColumna($camposDinamicos, array('tipo', 'codigo'), array(DocumentoTipoNegocio::DATO_LISTA, '13'), 'valor_dato_listar');
            if ($banderaComprobanteCompraNacional == "1") {
                $earRelacion = 1;
            }
        }

        $respuestaRelacion = DocumentoNegocio::create()->guardarDocumentoRelacionado($documentoIdOrigen, $documentoIdARelacionar, 1, 1, $usuarioId, $earRelacion);
        if ($banderaComprobanteCompraNacional == "1") {
            $respuesta = MovimientoDuaNegocio::create()->generarPorDocumentoId($documentoIdOrigen, $usuarioId);
            $resActualizarCostos = MovimientoDuaNegocio::create()->actualizarCostoUnitarioDuaXDocumentoId($documentoIdOrigen, $usuarioId);
            if (ObjectUtil::isEmpty($resActualizarCostos) || $resActualizarCostos[0]['vout_exito'] != 1) {
                throw new WarningException('Error al intentar generar costo de la compra.');
            }
        }
        //                        throw new WarningException('Error al intentar relacionar el documento');

        return $respuestaRelacion;
    }

    public function obtenerUnidadMedida($bienId, $unidadMedidaId, $precioTipoId, $monedaId, $fechaEmision)
    {
        $unidad = UnidadNegocio::create()->obtenerActivasXBien($bienId);
        $respuesta = new stdClass();
        $respuesta->unidad_medida = $unidad;

        if ($unidadMedidaId == 0) {
            $unidadMedidaId = $unidad[0]["id"];
        }

        $dataPrecio = BienPrecioNegocio::create()->obtenerBienPrecioXBienIdXUnidadMedidaIdXPrecioTipoIdXMonedaId($bienId, $unidadMedidaId, $precioTipoId, $monedaId);
        if (ObjectUtil::isEmpty($dataPrecio)) {
            $precio = 0;
        } else {
            $precio = $dataPrecio[0]["precio"];
        }

        //        $precioCompra = BienPrecioNegocio::create()->obtenerPrecioCompraPromedio($bienId, $unidadMedidaId, $fechaEmision);

        if ($monedaId == 4) {
            $equivalenciaDolar = TipoCambioNegocio::create()->obtenerTipoCambioXFechaUltima($fechaEmision);
            $precioCompra = $precioCompra / $equivalenciaDolar[0]['equivalencia_venta'];
        }

        //        $respuesta->precioCompra = $precioCompra;
        $respuesta->precio = $precio;
        return $respuesta;
    }

    public function obtenerStockActual($bienId, $indice, $organizadorId, $unidadMedidaId, $organizadorDestinoId = null)
    {
        if (!ObjectUtil::isEmpty($organizadorId)) {
            //LA TRANSFERENCIA INTERNA TIENE ORGANIZADOR
            $stock = BienNegocio::create()->obtenerStockActual($bienId, $organizadorId, $unidadMedidaId, $organizadorDestinoId);
        } else {
            $stock = BienNegocio::create()->obtenerStockTotalXBienIDXUnidadMedidaId($bienId, $unidadMedidaId);
        }

        $cantidadMinima = BienNegocio::create()->obtenerCantidadMinimaConvertido($bienId, $organizadorId, $unidadMedidaId);
        $stock[0]['indice'] = $indice;
        $stock[0]['cantidad_minima'] = $cantidadMinima[0]['cantidad_minima'];

        return $stock;
    }

    public function obtenerStockParaProductosDeCopia($organizadorDefectoId, $detalle, $organizadorDestinoId = null)
    {
        $dataStock = array();
        foreach ($detalle as $item) {
            //TIENE QUE SER SIMILAR AL METODO DEL CONTROLADOR: obtenerStockActual
            $bienId = $item['bienId'];
            $unidadMedidaId = $item['unidadMedidaId'];
            $organizadorId = $item['organizadorId'];
            if (!ObjectUtil::isEmpty($organizadorDefectoId) && $organizadorDefectoId != 0) {
                $organizadorId = $organizadorDefectoId;
            }
            $stock = MovimientoNegocio::create()->obtenerStockActual($bienId, $item['index'], $organizadorId, $unidadMedidaId, $organizadorDestinoId);

            array_push($dataStock, $stock);
        }

        return $dataStock;
    }

    public function obtenerDocumentoRelacionadoTipoRecepcion($documentoId)
    {
        return Movimiento::create()->obtenerDocumentoRelacionadoTipoRecepcion($documentoId);
    }

    public function guardarArchivosXDocumentoID($documentoId, $lstDocumento, $lstDocEliminado, $usuCreacion)
    {
        return DocumentoNegocio::create()->guardarArchivosXDocumentoID($documentoId, $lstDocumento, $lstDocEliminado, $usuCreacion);
    }

    public function guardarEmailEnvioPendientesXReposicion($dataPAtencion, $asuntoCorreo, $plantillaId, $descripcionCorreo, $tituloCorreo, $mostrarDocumento = 1)
    {
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

            $html = '<tr>';
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Item</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>F.Traslado</th>";
            if ($mostrarDocumento == 1) {
                //                $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Tipo de documento</th>";
                $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>S/N</th>";
            }
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Origen</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Producto</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Cantidad</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Pendiente</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>U. Medida</th>";
            $html = $html . '</tr>';

            $dataDetalle = $dataDetalle . $html;
            $dataDetalle = $dataDetalle . '<thead>';
            $dataDetalle = $dataDetalle . '<tbody>';

            foreach ($dataPAtencion as $index => $item) {
                $html = '<tr>';
                $html = $html . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . ($index + 1);
                $html = $html . '<td style=\'text-align:center;font-size:14px;line-height:1.5;\'>' . $item['fecha_traslado'];
                if ($mostrarDocumento == 1) {
                    //                    $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['documento_tipo_descripcion'];
                    $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['serie_numero'];
                }
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['org_origen'];
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['bien_descripcion'];
                $html = $html . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . $item['cantidad'] * 1;
                $html = $html . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . ($item['cantidad'] - $item['cant_rep']) * 1;
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['unidad_medidad'];
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

    public function generarDocumentoImpresionPDF($documentoId, $url, $data)
    {
        //Import the PhpJasperLibrary
        require_once __DIR__ . '/../../reporteJasper/PhpJasperLibrary/tcpdf/tcpdf.php';
        require_once __DIR__ . '/../../reporteJasper/PhpJasperLibrary/PHPJasperXML.inc.php';

        $dataDocumento = $data->dataDocumento;
        $identificadorNegocio = $dataDocumento[0]['identificador_negocio'];

        switch ((int) $identificadorNegocio) {
            case 3: //boleta
                return $this->generarDocumentoImpresionPDFBoleta($documentoId, $url, $data);
                break;
            case 5: //nota de credito
                return $this->generarDocumentoImpresionPDFNotaCredito($documentoId, $url, $data);
                break;
            default:
                return $this->generarDocumentoPDFEstandar($documentoId, $comentario, $tipoSalidaPDF, $url, $data);
                break;
        }
    }

    public function generarDocumentoImpresionPDFBoleta($documentoId, $url, $data)
    {
        //CONEXION
        //        $server="localhost";
        //        $db="bhdt_20170901";
        //        $user="root";
        //        $pass="local";
        //DATOS
        $dataDocumento = $data->dataDocumento;
        $documentoDatoValor = $data->documentoDatoValor;
        //        $detalle = $data->detalle;
        $documentoDetalle = $data->documentoDetalle;
        $dataEmpresa = $data->dataEmpresa;
        $dataMovimientoTipoColumna = $data->movimientoTipoColumna;
        $dataDocumentoRelacion = $data->documentoRelacionado;

        $serieDocumento = '';
        if (!ObjectUtil::isEmpty($dataDocumento[0]['serie'])) {
            $serieDocumento = $dataDocumento[0]['serie'] . " - ";
        }

        $titulo = strtoupper($dataDocumento[0]['documento_tipo_descripcion']) . " " . $serieDocumento . $dataDocumento[0]['numero'];
        $fechaEmision = DateUtil::formatearBDACadena($dataDocumento[0]['fecha_emision']);

        $dia = date_format((date_create($dataDocumento[0]['fecha_emision'])), 'd');
        //        $mesNombre = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Setiembre","Octubre","Noviembre","Diciembre");
        $mes = date_format((date_create($dataDocumento[0]['fecha_emision'])), 'm');
        $anio = date_format((date_create($dataDocumento[0]['fecha_emision'])), 'y'); //Y año completo
        //TOTAL EN LETRAS
        $totalLetras = Util::normaliza($data->totalEnTexto);

        //FIN DATOS
        //REPORTE
        $urlJrxml = __DIR__ . '/../../reporteJasper/almacen/boleta.jrxml';
        $xml = simplexml_load_file($urlJrxml);
        $PHPJasperXML = new PHPJasperXML();

        //DETALLE: cantidad,simbolo,bien_descripcion,valor_monetario,sub_total        
        $PHPJasperXML->arrayParameter = array(
            //            "vin_movimiento_id"=>$dataDocumento[0]['movimiento_id'],
            "serie_numero" => $serieDocumento . $dataDocumento[0]['numero'],
            "nombre" => $dataDocumento[0]['nombre'],
            "direccion" => $dataDocumento[0]['direccion'],
            "documento" => $dataDocumento[0]['codigo_identificacion'],
            "fecha_dia" => $dia,
            //            "fecha_mes" => $mesNombre[$mes*1-1],
            "fecha_mes" => $mes,
            "fecha_anio" => $anio,
            "total_letras" => strtoupper($totalLetras),
            "total" => $dataDocumento[0]['total'],
            "moneda_simbolo" => $dataDocumento[0]['moneda_simbolo'],
            "fecha_pie" => $fechaEmision,
        );

        $PHPJasperXML->xml_dismantle($xml);
        //        $PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
        $PHPJasperXML->transferirDataSql($documentoDetalle); //SIN CONEXION
        //        $PHPJasperXML->outpage("I");    //page output method I:standard output  D:Download file

        $PHPJasperXML->outpage('F', $url);
        //FIN REPORTE

        return $titulo;
    }

    public function generarDocumentoImpresionPDFNotaCredito($documentoId, $url, $data)
    {
        //DATOS
        $dataDocumento = $data->dataDocumento;
        $documentoDatoValor = $data->documentoDatoValor;
        //        $detalle = $data->detalle;
        $documentoDetalle = $data->documentoDetalle;
        $dataEmpresa = $data->dataEmpresa;
        $dataMovimientoTipoColumna = $data->movimientoTipoColumna;
        $dataDocumentoRelacion = $data->documentoRelacionado;

        $serieDocumento = '';
        if (!ObjectUtil::isEmpty($dataDocumento[0]['serie'])) {
            $serieDocumento = $dataDocumento[0]['serie'] . " - ";
        }

        $titulo = strtoupper($dataDocumento[0]['documento_tipo_descripcion']) . " " . $serieDocumento . $dataDocumento[0]['numero'];
        $fechaEmision = DateUtil::formatearBDACadena($dataDocumento[0]['fecha_emision']);
        $fechaVencimiento = DateUtil::formatearBDACadena($dataDocumento[0]['fecha_vencimiento']);

        $dia = date_format((date_create($dataDocumento[0]['fecha_emision'])), 'd');
        $mesNombre = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Setiembre", "Octubre", "Noviembre", "Diciembre");
        $mes = date_format((date_create($dataDocumento[0]['fecha_emision'])), 'm');
        $anio = date_format((date_create($dataDocumento[0]['fecha_emision'])), 'Y'); //Y año completo
        //TOTAL EN LETRAS
        $totalLetras = Util::normaliza($data->totalEnTexto);

        //DOCUMENTO TIPO DATO
        $serieNumero_doc_rel = '';
        $tipo_doc_rel = '';
        $fechaEmision_doc_rel = '';
        if (!ObjectUtil::isEmpty($dataDocumentoRelacion)) {
            foreach ($dataDocumentoRelacion as $item) {
                if ($item['identificador_negocio'] == 3) {
                    $serieNumero_doc_rel = $item['serie_numero_original'];
                    $fechaEmision_doc_rel = DateUtil::formatearBDACadena($item['fecha_emision']);
                    $tipo_doc_rel = 'Boleta';
                }
                if ($item['identificador_negocio'] == 4) {
                    $serieNumero_doc_rel = $item['serie_numero_original'];
                    $fechaEmision_doc_rel = DateUtil::formatearBDACadena($item['fecha_emision']);
                    $tipo_doc_rel = 'Factura';
                }
            }
            $serieNumero_doc_rel = explode(" - ", $serieNumero_doc_rel);
        }


        $anulacion = '';
        $bonificacion = '';
        $descuento = '';
        $devoluciones = '';
        $otros = '';
        foreach ($documentoDatoValor as $item) {
            switch ($item["documento_tipo_id"] * 1) {
                case 2885:
                    $serieNota = $item["valor"];
                    break;
                case 2886:
                    $numeroNota = $item["valor"];
                    break;
                case 609:
                    if ($item["valor"] == "Anulación") {
                        $anulacion = 'X';
                    }
                    if ($item["valor"] == "Bonificaciones") {
                        $bonificacion = 'X';
                    }
                    if ($item["valor"] == "Descuentos") {
                        $descuento = 'X';
                    }
                    if ($item["valor"] == "Devoluciones") {
                        $devoluciones = 'X';
                    }
                    if ($item["valor"] == "Otros") {
                        $otros = 'X';
                    }
                    break;
                default:
                    break;
            }
        }

        //REPORTE
        $urlJrxml = __DIR__ . '/../../reporteJasper/almacen/nota_credito.jrxml';
        $xml = simplexml_load_file($urlJrxml);
        $PHPJasperXML = new PHPJasperXML();

        //DETALLE: cantidad,simbolo,bien_descripcion,valor_monetario,sub_total        
        $PHPJasperXML->arrayParameter = array(
            //            "vin_movimiento_id"=>$dataDocumento[0]['movimiento_id'],
            //            "serie_numero" => $serieDocumento . $dataDocumento[0]['numero'],
            "serie_numero" => $serieNota . '-' . $numeroNota,
            "nombre" => $dataDocumento[0]['nombre'],
            "total" => $dataDocumento[0]['total'],
            "documento" => $dataDocumento[0]['codigo_identificacion'],
            "total_letras" => 'SON: ' . strtoupper($totalLetras),
            "fecha_emision" => $dia . ' de ' . $mesNombre[$mes * 1 - 1] . ' del ' . $anio,
            "sub_total" => $dataDocumento[0]['subtotal'],
            "igv" => $dataDocumento[0]['igv'],
            "igv_porcentaje" => 18,
            "fecha_emision_doc_rel" => $fechaEmision_doc_rel,
            "tipo_doc_rel" => $tipo_doc_rel,
            //            "serie_rel" => $serieNumero_doc_rel[0].'-',
            "serie_doc_rel" => $serieNumero_doc_rel[0] . '-' . $serieNumero_doc_rel[1],
            "moneda_simbolo" => $dataDocumento[0]['moneda_simbolo'],
            "mot_anulacion" => $anulacion,
            "mot_bonificacion" => $bonificacion,
            "mot_descuento" => $descuento,
            "mot_devolucion" => $devoluciones,
            "mot_otros" => $otros,
        );

        $PHPJasperXML->xml_dismantle($xml);
        //        $PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
        $PHPJasperXML->transferirDataSql($documentoDetalle); //SIN CONEXION
        //        $PHPJasperXML->outpage("I");    //page output method I:standard output  D:Download file

        $PHPJasperXML->outpage('F', $url);
        //FIN REPORTE

        return $titulo;
    }

    //EDICION
    public function validarDocumentoEdicion($documentoId)
    {
        $respuesta = new stdClass();
        $respuesta->exito = 1;

        $dataDocumento = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);

        //ORDEN DE VENTA
        if ($dataDocumento[0]['identificador_negocio'] == 2) {
            $atencionEstado = ProgramacionAtencionNegocio::create()->obtenerDocumentoAtencionEstadoLogico($documentoId);

            if ($atencionEstado[0]['estado_atencion'] == 4) { //ATENCION COMPLETA
                $respuesta->exito = 0;
                $respuesta->mensaje = 'No se puede editar la orden de venta porque fue atendida completamente';
                return $respuesta;
            }
        }

        // Aprobación por contabilidad
        $respuestaDocumentoEstado = DocumentoNegocio::create()->obtenerDocumentoDocumentoEstadoXDocumentoId($documentoId);
        if ($respuestaDocumentoEstado[0]['documento_estado_id'] == 3) {
            $respuesta->exito = 0;
            $respuesta->mensaje = 'No se puede editar el documento por que ya fue aprobado por área de contabilidad.';
            return $respuesta;
        }


        //INVOICE COMMERCIAL
        /*
          if ($dataDocumento[0]['identificador_negocio'] == 24) {
          $respuestaDua = DocumentoNegocio::create()->obtenerDUAXInvoiceComercial($documentoId);
          if (!ObjectUtil::isEmpty($respuestaDua[0]['id'])) {
          $respuesta->exito = 0;
          $respuesta->mensaje = 'No se puede editar el invoice commercial porque ya existe una DUA relacionada.';
          return $respuesta;
          }
          }
         */
        return $respuesta;
    }

    function obtenerDocumentoRelacionEdicion($documentoTipoOrigenId, $documentoTipoDestinoId, $movimientoId, $documentoId, $opcionId, $documentoRelacionados)
    {

        $respuesta = new ObjectUtil();

        $documentoACopiar = DocumentoNegocio::create()->obtenerDataDocumentoACopiarEdicion($documentoTipoDestinoId, $documentoTipoOrigenId, $documentoId);

        if (ObjectUtil::isEmpty($documentoACopiar)) {
            throw new WarningException("No se encontró el documento");
        }

        $respuesta->documentoACopiar = $documentoACopiar;
        //        $respuesta->dataDocumentoRelacionada = DocumentoNegocio::create()->obtenerDataDocumentoACopiarRelacionada($documentoTipoOrigenId, $documentoTipoDestinoId, $documentoId);
        $respuesta->detalleDocumento = $this->obtenerDocumentoRelacionDetalleEdicion($movimientoId, $documentoId, $opcionId, $documentoRelacionados);

        if ($documentoTipoDestinoId != $documentoTipoOrigenId) {
            $respuesta->documentosRelacionados = DocumentoNegocio::create()->obtenerRelacionesDocumento($documentoId);
        } else {
            $respuesta->documentosRelacionados = 1;
        }

        $respuesta->dataPagoProgramacion = PagoNegocio::create()->obtenerPagoProgramacionXDocumentoId($documentoId);

        //OBTENER DATA DE UNIDAD DE MEDIDA
        $documentoDetalle = $respuesta->detalleDocumento;
        foreach ($documentoDetalle as $index => $item) {
            $bienId = $item['bien_id'];
            $unidadMedidaId = $item['unidad_medida_id'];
            $precioTipoId = $item['precio_tipo_id'];
            $monedaId = $documentoACopiar[0]['moneda_id'];
            $fechaEmision = date("d/m/Y");
            foreach ($documentoACopiar as $itemDato) {
                if ($itemDato['tipo'] == 9) {
                    $fechaEmision = date_format((date_create($itemDato['valor'])), 'd/m/Y');
                }
            }

            $data = MovimientoNegocio::create()->obtenerUnidadMedida($bienId, $unidadMedidaId, $precioTipoId, $monedaId, $fechaEmision);
            $documentoDetalle[$index]['dataUnidadMedida'] = $data;
        }
        $respuesta->detalleDocumento = $documentoDetalle;
        $respuesta->dataDistribucionContable = ContDistribucionContableNegocio::create()->obtenerContDistribucionContableXDocumentoId($documentoId);
        //FIN OBTENER DATA UNIDAD MEDIDA

        return $respuesta;
    }

    function obtenerDocumentoRelacionDetalleEdicion($movimientoId, $documentoId, $opcionId, $documentoRelacionados)
    {
        //solo va a ver un documento a copiar/editar
        $documentoDetalle = MovimientoBien::create()->obtenerXIdMovimiento($movimientoId);

        foreach ($documentoDetalle as $index => $detalle) {
            //obtener datos de: movimiento_bien_detalle
            $resMovimientoBienDetalle = MovimientoBien::create()->obtenerMovimientoBienDetalleXMovimientoBienId($detalle['movimiento_bien_id']);

            $documentoDetalle[$index]['movimiento_bien_detalle'] = $resMovimientoBienDetalle;
        }

        return $documentoDetalle;
    }

    public function guardarXAccionEnvioEdicion($documentoId, $opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $listaDetalleEliminar, $documentoARelacionar, $valorCheck, $comentario, $checkIgv, $monedaId, $accionEnvio, $tipoPago, $listaPagoProgramacion, $anticiposAAplicar = null, $periodoId = null, $percepcion = null, $detalleDistribucion = NULL, $contOperacionTipoId = NULL, $distribucionObligatoria = NULL, $afectoAImpuesto = NULL, $datosExtras = NULL)
    {

        //        $resEdicion = $this->guardarEdicion($documentoId, $opcionId, $usuarioId, $documentoTipoId, $detalle,$listaDetalleEliminar, $checkIgv, $accionEnvio, $comentario);
        $resEdicion = $this->guardarEdicion($documentoId, $opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $listaDetalleEliminar, $documentoARelacionar, $valorCheck, $comentario, $checkIgv, $monedaId, $tipoPago, $periodoId, $detalleDistribucion, $contOperacionTipoId, $distribucionObligatoria, $afectoAImpuesto, $datosExtras);

        //ACTUALIZAMOS IMPORTE DE PROGRAMACION DE PAGO
        if ($tipoPago == 2) {
            $resPP = Pago::create()->actualizarPagoProgramacionImporteXDocumentoId($documentoId);
        }

        $respuesta = new stdClass();

        if ($accionEnvio == 'guardar') {
            $respuesta->documentoId = $documentoId;
            return $respuesta;
        }

        if ($accionEnvio == 'confirmar') {
            DocumentoNegocio::create()->insertarDocumentoDocumentoEstado($documentoId, 13, $usuarioId);
            $respuesta->documentoId = $documentoId;
            return $respuesta;
        }

        if ($accionEnvio == 'enviar') {
            $respuesta->documentoId = $documentoId;
            return $respuesta;
        }

        if ($accionEnvio == 'enviarEImprimir') {
            $respuesta->dataImprimir = $this->imprimirExportarPDFDocumento($documentoTipoId, $documentoId, $usuarioId);
            $respuesta->documentoId = $documentoId;
            return $respuesta;
        } else {

            //obtener email de plantilla            
            $movimientoTipoId = $this->obtenerIdXOpcion($opcionId);
            $plantilla = EmailPlantillaNegocio::create()->obtenerPlantillaDestinatarioXAccionDescripcionXMovimientoTipoId($accionEnvio, $movimientoTipoId);
            $dataPersona = DocumentoNegocio::create()->obtenerPersonaXDocumentoId($documentoId);

            $correosPlantilla = EmailPlantillaNegocio::create()->obtenerEmailXDestinatario($plantilla[0]["destinatario"], $documentoId, $dataPersona[0]['id']);

            if (ObjectUtil::isEmpty($correosPlantilla)) {
                $this->setMensajeEmergente("Email en blanco, nose pudo enviar correo.", null, Configuraciones::MENSAJE_WARNING);
                $respuesta->documentoId = $documentoId;
                return $respuesta;
            }

            $correos = '';
            foreach ($correosPlantilla as $email) {
                $correos = $correos . $email . ';';
            }

            $plantillaId = $plantilla[0]["email_plantilla_id"];
            $respuesta->dataEnvioCorreo = $this->enviarCorreoXAccion($correos, $comentario, $accionEnvio, $documentoId, $plantillaId, $usuarioId);
            $respuesta->documentoId = $documentoId;
            return $respuesta;
        }
    }

    public function guardarEdicion($documentoId, $opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $listaDetalleEliminar, $documentoARelacionar, $valorCheck, $comentario, $checkIgv, $monedaId, $tipoPago, $periodoId, $detalleDistribucion = NULL, $contOperacionTipoId = NULL, $distribucionObligatoria = NULL, $afectoAImpuesto = NULL, $datosExtras = NULL)
    {

        $dataDocumento = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);
        $dataDocumentoTipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXId($documentoTipoId);
        $empresaId = $dataDocumentoTipo[0]['empresa_id'];
        $identificadorNegocio = $dataDocumentoTipo[0]['identificador_negocio'];

        $dataAperturaCaja = ACCajaNegocio::create()->obteneACCajaUltimoXUsuarioId($empresaId, $usuarioId);
        if (ObjectUtil::isEmpty($dataAperturaCaja)) {
            throw new WarningException("No existe una caja aperturada para el usuario en sesión.");
        }
        $acCajaId = $dataAperturaCaja[0]['ac_caja_id'];
        $agenciaIdConsulta = $dataAperturaCaja[0]['agencia_id'];

        $datosExtras['agencia_id'] = $agenciaIdConsulta;

        $respuestaDoc = MovimientoNegocio::create()->guardarEdicionDocumento($documentoId, $camposDinamicos, $comentario, $periodoId, $tipoPago, $monedaId, $usuarioId, $contOperacionTipoId, $afectoAImpuesto, NULL, $datosExtras, $acCajaId);

        //ELIMINAR MOVIMIENTO BIEN 
        if (!ObjectUtil::isEmpty($listaDetalleEliminar)) {
            foreach ($listaDetalleEliminar as $itemId) {
                $resElimina = MovimientoBien::create()->actualizarEstadoXId($itemId, 2);
            }
        } elseif ($identificadorNegocio == DocumentoTipoNegocio::IN_LIQUIDACION_COBRANZA) {
            $resElimina = MovimientoBien::create()->actualizarEstadoXDocumentoId($documentoId, 2);
        }

        //Insertamos el detalle
        foreach ($detalle as $item) {
            $valido = $this->validarItemDetalleMovimientoEdicion($item, $opcionId, $dataDocumentoTipo, $camposDinamicos, $monedaId);

            //REGISTRAR LA EDICION DEL DETALLE
            if (!ObjectUtil::isEmpty($item['movimientoBienId'])) {
                $movimientoBien = MovimientoBien::create()->editar($item['movimientoBienId'], $dataDocumento[0]['movimiento_id'], $item["organizadorId"], $item["bienId"], $item["unidadMedidaId"], $item["cantidad"], $item["precio"], 1, $usuarioId, $item["precioTipoId"], $item["utilidad"], $item["utilidadPorcentaje"], $checkIgv, $item["adValorem"], $item["comentarioDetalle"], $item["bienActivoFijoId"]);
            } else {
                $movimientoBien = MovimientoBien::create()->guardar(
                    $dataDocumento[0]['movimiento_id'],
                    $item["organizadorId"],
                    $item["bienId"],
                    $item["unidadMedidaId"],
                    $item["cantidad"],
                    $item["precio"],
                    1,
                    $usuarioId,
                    $item["precioTipoId"],
                    $item["utilidad"],
                    $item["utilidadPorcentaje"],
                    $checkIgv,
                    $item["adValorem"],
                    $item["comentarioDetalle"],
                    $item["bienActivoFijoId"],
                    NULL,
                    NULL,
                    NULL,
                    NULL,
                    $item["tipo"],
                    NULL,
                    NULL,
                    $item["movimientoBienPadreId"],
                    $item["documentoPadreId"],
                    $item["movimientoPadreId"]
                );
            }

            $movimientoBienId = $this->validateResponse($movimientoBien);
            if (ObjectUtil::isEmpty($movimientoBienId) || $movimientoBienId < 1) {
                throw new WarningException("No se pudo guardar un detalle del movimiento");
            }

            //guardar el detalle del detalle del movimiento en movimiento_bien_detalle
            //            if (!ObjectUtil::isEmpty($item["detalle"])) {
            //                foreach ($item["detalle"] as $valor) {
            //                        if ($valor['columnaCodigo'] == 18) {
            //                            $fechaVencimiento = DateUtil::formatearCadenaACadenaBD($valor['valorDet']);
            //                            //EDITA SI YA EXISTE LA FECHA DE VENCIMIENTO SINO REGISTRA
            //                            $resDetalle = MovimientoNegocio::create()->movimientoBienDetalleEditarFecha($movimientoBienId, $valor['columnaCodigo'], $fechaVencimiento, $usuarioId);
            //                        }
            //                }
            //            }
        }

        $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);
        $operacionTipoMovimiento = ContOperacionTipoNegocio::create()->obtenerContOperacionTipoXMovimientoTipoId($movimientoTipo[0]['id']);
        if (!ObjectUtil::isEmpty($operacionTipoMovimiento)) {
            if (array_search($contOperacionTipoId, array_column($operacionTipoMovimiento, 'id')) === false) {
                throw new WarningException("La operación tipo seleccionada no pertenece al movimiento tipo.");
            }
            if ($distribucionObligatoria == 1) {
                $respuestaValidarDistribucion = ContDistribucionContableNegocio::create()->validarDistribucionContable($documentoId, $detalleDistribucion, $contOperacionTipoId);
            }
            $respuestaGuardarDistribucion = ContDistribucionContableNegocio::create()->guardarContDistribucionContable($documentoId, $contOperacionTipoId, $detalleDistribucion, $usuarioId);
        }

        $respuestaAnularPenalidad = Penalidad::create()->anularPenalidadDocumentoXDocumentoId($documentoId);
        if (!ObjectUtil::isEmpty($datosExtras['detallePenalidad'])) {
            foreach ($datosExtras['detallePenalidad'] as $index => $item) {
                $penalidadId = $item['id'];

                if (ObjectUtil::isEmpty($penalidadId)) {
                    $respuestaRegistrar = Penalidad::create()->registrar($empresaId, $item['descripcion'], $usuarioId);
                    $penalidadId = $this->validateResponse($respuestaRegistrar);
                }

                Penalidad::create()->registrarDocumentoPenalidad($documentoId, $penalidadId, $usuarioId, $item['monto']);
            }
        }

        if ($datosExtras['accion'] == 'confirmar' && $identificadorNegocio == DocumentoTipoNegocio::IN_LIQUIDACION_COBRANZA) {

            $dataDocumentoRegistrado = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);
            if (ObjectUtil::isEmpty($dataDocumentoRegistrado)) {
                throw new WarningException("No obtuvo la data del documento - liquidación. " . $documentoId);
            }
            if (!ObjectUtil::isEmpty($dataDocumentoRegistrado[0]['documento_cargo_id'])) {
                throw new WarningException("Este pedido ya tiene un documento cargo relacionado. " . $dataDocumentoRegistrado[0]['documento_cargo_serie'] . "-" . $dataDocumentoRegistrado[0]['documento_cargo_numero']);
            }

            if ($dataDocumentoRegistrado[0]['documento_estado_id'] != 8) {
                throw new WarningException("La liquidación debe estar en el estado pendiente de confirmación. ");
            }

            $fechaEmision = date('d/m/Y');
            $anio = date('Y');
            $mes = date('m');
            $empresaId = $dataDocumentoRegistrado[0]['empresa_id'];

            $dataPeriodo = Periodo::create()->obtenerPeriodoXEmpresaXAnioXMes($empresaId, $anio, $mes);

            if (ObjectUtil::isEmpty($dataPeriodo)) {
                throw new WarningException("No existe un periodo activo para la fecha " . $fechaEmision);
            }

            $documentoGenerar = array();
            $documentoGenerar['documentoTipoId'] = $dataDocumentoRegistrado[0]['comprobante_tipo_id'];
            $documentoGenerar['documentoOrigenId'] = $documentoId;
            $documentoGenerar['fechaEmision'] = $fechaEmision;
            $documentoGenerar['fechaVencimiento'] = $fechaEmision;
            $documentoGenerar['periodoId'] = $dataPeriodo[0]['id'];
            $documentoGenerar['empresaId'] = $dataDocumentoRegistrado[0]['empresa_id'];
            $documentoGenerar['personaId'] = $dataDocumentoRegistrado[0]['persona_id'];
            $documentoGenerar['personaDireccionId'] = $dataDocumentoRegistrado[0]['persona_direccion_id'];
            $documentoGenerar['clienteId'] = $dataDocumentoRegistrado[0]['persona_destinatario_id'];
            $documentoGenerar['clienteDireccionId'] = $dataDocumentoRegistrado[0]['persona_direccion_destino_id'];
            $documentoGenerar['destinatarioDireccionId'] = $dataDocumentoRegistrado[0]['persona_direccion_origen_id'];
            $documentoGenerar['clienteDireccionDescripcion'] = $dataDocumentoRegistrado[0]['persona_direccion_destino'];
            $documentoGenerar['destinatarioDireccionDescripcion'] = $dataDocumentoRegistrado[0]['persona_direccion_origen'];
            $documentoGenerar['agenciaOrigenId'] = $agenciaIdConsulta;
            $documentoGenerar['monedaId'] = $dataDocumentoRegistrado[0]['moneda_id'];
            $documentoGenerar['documentoEstadoId'] = 1; // REGISTRADO
            $documentoGenerar['tipoPago']  = 2; // FACTURA AL CREDITO

            $documentoGenerar['monto_total'] = $dataDocumentoRegistrado[0]['total'];
            $documentoGenerar['monto_igv'] = $dataDocumentoRegistrado[0]['igv'];
            $documentoGenerar['monto_subtotal'] = $dataDocumentoRegistrado[0]['subtotal'];

            $itemDetalle = array();
            $itemDetalle["bienId"] = -2;
            $itemDetalle["bienDesc"] = $dataDocumentoRegistrado[0]['comentario'];
            $itemDetalle["comentarioDetalle"] = $dataDocumentoRegistrado[0]['comentario'];
            $itemDetalle["unidadMedidaId"] = -1;
            $itemDetalle["precioTipoId"] = 2;
            $itemDetalle["cantidad"] = 1;
            $itemDetalle["precio"] = $dataDocumentoRegistrado[0]['total'];

            PedidoNegocio::create()->guardar($opcionId, $usuarioId, $documentoGenerar, array($itemDetalle), $datosExtras['dataPago'], TRUE);
        }


        $respuestaAnularRelacion = DocumentoNegocio::create()->anularDocumentoRelacionXDocumentoId($documentoId);
        //si el documento se a copiado guardamos las relaciones
        foreach ($documentoARelacionar as $documentoRelacion) {
            if (!ObjectUtil::isEmpty($documentoRelacion['documentoId'])) {
                DocumentoNegocio::create()->guardarDocumentoRelacionado($documentoId, $documentoRelacion['documentoId'], $valorCheck, 1, $usuarioId, NULL, $documentoRelacion['tipo']);
            }
        }

        $this->setMensajeEmergente("La operación se completó de manera satisfactoria");

        $banderaComprobanteCompraNacional = NULL;
        //        $camposDinamicosGuardados = DocumentoNegocio::create()->obtenerDetalleDocumento($documentoId);
        //        if (!ObjectUtil::isEmpty($camposDinamicosGuardados)) {
        //            $banderaComprobanteCompraNacional = Util::filtrarArrayPorColumna($camposDinamicosGuardados, array('tipo', 'codigo'), array(DocumentoTipoNegocio::DATO_LISTA, '13'), 'valor_dato_listar');
        //        }

        if ($documentoTipoId == $this->dtDuaId || $banderaComprobanteCompraNacional == "1") {
            MovimientoDuaNegocio::create()->generarPorDocumentoId($documentoId, $usuarioId);
            MovimientoDuaNegocio::create()->actualizarCostoUnitarioDuaXDocumentoId($documentoId, $usuarioId);
        } else {
            MovimientoDuaNegocio::create()->eliminarCostoCifXDocumentoId($documentoId);
        }

        return $documentoId;
    }

    private function validarItemDetalleMovimientoEdicion($item, $opcionId, $dataDocumentoTipo = null, $camposDinamicos, $monedaId)
    {
        $movimientoTipo = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);
        // validaciones
        if ($item["bienId"] == NULL) {
            throw new WarningException("No se especificó un valor válido para Bien. ");
        }

        if ($item["unidadMedidaId"] == NULL) {
            throw new WarningException("No se especificó un valor válido para Unidad Medida. ");
        }
        if ($item["cantidad"] == NULL or $item["cantidad"] <= 0) {
            throw new WarningException("No se especificó un valor válido para Cantidad. ");
        }

        //obtengo la fecha de emision
        $fechaEmision = null;
        foreach ($camposDinamicos as $valorCampo) {
            if ($valorCampo["tipo"] == 9) {
                $fechaEmision = DateUtil::formatearCadenaACadenaBD($valorCampo["valor"]);
            }
        }

        if (ObjectUtil::isEmpty($item["movimientoBienId"])) {
            MovimientoNegocio::create()->obtenerStockAControlar($opcionId, $item["bienId"], $item["organizadorId"], $item["unidadMedidaId"], $item["cantidad"], $fechaEmision, $item["detalle"]);
        } else {
            //SI ES ENTRADA O SALIDA VALIDA STOCK
            if ($movimientoTipo[0]["indicador"] == MovimientoTipoNegocio::INDICADOR_SALIDA || $movimientoTipo[0]["indicador"] == MovimientoTipoNegocio::INDICADOR_ENTRADA) {
                //ACTUALIZO TEMPORALMENTE EL MOVIMIENTO BIEN A INACTIVO
                $resEstInac = MovimientoBien::create()->actualizarEstadoXId($item["movimientoBienId"], 0);

                MovimientoNegocio::create()->obtenerStockAControlar($opcionId, $item["bienId"], $item["organizadorId"], $item["unidadMedidaId"], $item["cantidad"], $fechaEmision, $item["detalle"], true);

                //ACTUALIZO MOVIMIENTO BIEN A ACTIVO
                $resEstAc = MovimientoBien::create()->actualizarEstadoXId($item["movimientoBienId"], 1);
            }
        }

        //validacion el precio unitario tiene que ser mayor al precio de compra.
        $precioCompra = 0;
        $validarPrecios = true;
        //        if ($item["precio"] * 1 == 0) {
        //            $validarPrecios = false;
        //        }

        if (!ObjectUtil::isEmpty($item["precioCompra"])) {
            $precioCompra = $item["precioCompra"];
        }
        if (!ObjectUtil::isEmpty($dataDocumentoTipo) && $dataDocumentoTipo[0]["validacion"] == 1 && $validarPrecios) {
            $precioUnitario = $item["precio"];
            if ($precioUnitario <= $precioCompra) {
                throw new WarningException(
                    "No se pudo guardar un detalle del movimiento, precio unitario tiene que ser mayor al precio de compra.<br>"
                        . "Producto: " . $item['bienDesc'] . '<br>'
                        . "Precio compra: " . number_format($precioCompra, 2, ".", ",") . '<br>'
                        . "Precio unitario: " . number_format($precioUnitario, 2, ".", ",") . '<br>'
                );
            }
        }
        if ($dataDocumentoTipo[0]["tipo"] == 1 && $validarPrecios) {
            $precioUnitario = $item["precio"];

            $dataPrecio = BienPrecioNegocio::create()->obtenerBienPrecioXBienIdXUnidadMedidaIdXPrecioTipoIdXMonedaId($item["bienId"], $item["unidadMedidaId"], $item["precioTipoId"], $monedaId);
            if (!ObjectUtil::isEmpty($dataPrecio)) {
                if ($checkIgv == 1) {
                    $precioVenta = $dataPrecio[0]["incluye_igv"];
                } else {
                    $precioVenta = $dataPrecio[0]["precio"];
                }
                $precioMinimo = $precioVenta * 1 - $dataPrecio[0]["descuento"] * 1;
                $precioMinimo = round($precioMinimo, 2);

                if ($precioUnitario < $precioMinimo) {
                    throw new WarningException("No se pudo guardar un detalle del movimiento, precio unitario tiene que ser mayor o igual al precio mínimo (descuento)"
                        . "<br> Producto: " . $item["bienDesc"]
                        . "<br> Precio mínimo: " . $precioMinimo);
                }
            }
        }
    }

    public function validarMovimientoBienEdicionEliminar($documentoId, $item)
    {
        $respuesta->exito = 1;

        if (!ObjectUtil::isEmpty($item['movimientoBienId'])) {
            $dataDocumento = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);

            //ORDEN DE VENTA
            if ($dataDocumento[0]['identificador_negocio'] == 2) {
                $cantidadAtendida = ProgramacionAtencionNegocio::create()->obtenerCantidadAtendidaXMovimientoBienId($item['movimientoBienId']);

                if (!ObjectUtil::isEmpty($cantidadAtendida[0]['cantidad_atendida']) && $cantidadAtendida[0]['cantidad_atendida'] > 0) {
                    //HAY ATENCION DEL MOVIMIENTO BIEN
                    $respuesta->exito = 0;
                    $respuesta->mensaje = 'La cantidad atendida del producto es ' . ($cantidadAtendida[0]['cantidad_atendida'] * 1) . ' por ello no se puede eliminar';
                }
            }
        }


        return $respuesta;
    }

    public function obtenerDireccionOrganizador($organizadorId)
    {
        return Organizador::create()->obtenerDireccionOrganizador($organizadorId);
    }

    function consultarTicket($documentoId)
    {

        $documento = DocumentoNegocio::create()->obtenerDocumentoFEXId($documentoId);

        $empresa = EmpresaNegocio::create()->obtenerEmpresaXId($documento[0]['empresa_id']);

        $ticket = Movimiento::create()->obtenerNroTicketEFACT($documentoId);
        if (!ObjectUtil::isEmpty($ticket)) {
            $comprobanteElectronico->emisorNroDocumento = $empresa[0]['ruc'];
            $comprobanteElectronico->docNroTicket = $ticket[0]['efact_ticket'];
            $comprobanteElectronico->usuarioSunatSOL = $empresa[0]['usuario_sunat_sol'];
            $comprobanteElectronico->claveSunatSOL = $empresa[0]['clave_sunat_sol'];
            $comprobanteElectronico->usuarioOSE = Configuraciones::EFACT_USER_OSE;
            $comprobanteElectronico->claveOSE = Configuraciones::EFACT_PASS_OSE;

            $comprobanteElectronico = (array) $comprobanteElectronico;

            $client = self::conexionEFAC();

            try {
                $resultado = $client->procesarConsultaTicket($comprobanteElectronico)->procesarConsultaTicketResult;
                return $resultado;
            } catch (Exception $e) {
                return $e->getMessage();
            }
        } else {
            return 'Este documento aun no ha sido procesado por el script por favor espere 30 minutos y vuelva a consultar.';
        }
    }

    function verificarAnulacionSunat()
    {
        $usuarioId = 1;
        $documentos = Movimiento::create()->obtenerDocumentosPorVerificarAnulacionSunat();

        $documentosCorreo = array();
        foreach ($documentos as $index => $item) {
            $resConsulta = $this->consultarTicket($item['documento_id']);
            //            $resConsulta = 'ERROR PROBANDO';
            if (strpos($resConsulta, '[Cod: IMA01]') === false) {
                //AGREGAR AL ARRAY PARA ENVIAR CORREO
                $item['error_sunat'] = $resConsulta;
                array_push($documentosCorreo, $item);

                //REVERTIR ESTADO
                $documentoEstadoId = 1;
                $resDocEstado = DocumentoNegocio::create()->ActualizarDocumentoEstadoId($item['documento_id'], $documentoEstadoId, $usuarioId);

                $estado = 1;
                $resDoc = DocumentoNegocio::create()->actualizarEstadoXId($item['documento_id'], $estado);
            } else {
                //CORRECTO: ACTUALIZAR ESTADO ANULACION SUNAT
                $resEst = Documento::create()->actualizarEstadoEfactAnulacionValido($item['documento_id'], 1);
            }
        }

        //ENVIAR CORREO
        $resEmailTodos = '';
        if (!ObjectUtil::isEmpty($documentosCorreo)) {
            $documentosGrupo = $this->obtenerDocumentosGrupoXUsuario($documentosCorreo);

            //A EFACT
            $plantilla = EmailPlantillaNegocio::create()->obtenerEmailPlantillaXID(23);
            $correosPlantilla = EmailPlantillaNegocio::create()->obtenerEmailXDestinatario($plantilla[0]["destinatario"], null, null);

            $correos = '';
            foreach ($correosPlantilla as $email) {
                $correos = $correos . $email . ';';
            }

            $descripcion = 'Se restablecieron los siguientes documentos, porque la verificación de la anulación fue incorrecta. Anule los documentos por el sistema.';

            $descripcion = 'El proceso de baja de algún documento no se pudo concretar, según política de SUNAT.<br>
                            Por tal motivo le recomendamos emitir nota de crédito.<br>
                            Asi mismo se restablecieron a un estado activo, los siguientes documentos:';

            foreach ($documentosGrupo as $key => $itemGrupo) {
                $correosEnvio = $correos . $itemGrupo['usuario_email'];
                $resEmail = $this->enviarCorreoDocumentosRevertidosDeAnulacionSunat($itemGrupo['documentos'], $plantilla, $correosEnvio, $descripcion);
                $resEmailTodos = $resEmailTodos . '<br>' . $resEmail;
            }
        }

        return $resEmailTodos;
    }

    function obtenerDocumentosGrupoXUsuario($documentosCorreo)
    {
        $documentosGrupo = array();

        $documentoItem = array();
        foreach ($documentosCorreo as $key => $item) {
            if (!$this->verificarUsuarioExisteEnArray($documentosGrupo, $item['usuario'])) {
                $documentoItem = array('usuario' => $item['usuario'], 'usuario_email' => $item['usuario_email'], 'documentos' => array());

                array_push($documentosGrupo, $documentoItem);
            }
        }

        foreach ($documentosGrupo as $indexGrupo => $itemGrupo) {
            foreach ($documentosCorreo as $index => $item) {
                if ($itemGrupo['usuario'] == $item['usuario']) {
                    array_push($documentosGrupo[$indexGrupo]['documentos'], $item);
                }
            }
        }

        return $documentosGrupo;
    }

    function verificarUsuarioExisteEnArray($documentosGrupo, $usuario)
    {
        $bandera = false;

        foreach ($documentosGrupo as $key => $item) {
            if ($item['usuario'] == $usuario) {
                $bandera = true;
            }
        }

        return $bandera;
    }

    function enviarCorreoDocumentosRevertidosDeAnulacionSunat($documentos, $plantilla, $correos, $descripcion)
    {

        if (!ObjectUtil::isEmpty($documentos)) {
            //            $plantilla = EmailPlantillaNegocio::create()->obtenerEmailPlantillaXID(21);
            //            $correosPlantilla = EmailPlantillaNegocio::create()->obtenerEmailXDestinatario($plantilla[0]["destinatario"], null, null);
            //
            //            $correos = '';
            //            foreach ($correosPlantilla as $email) {
            //                $correos = $correos . $email . ';';
            //            }
            //dibujando la cabecera
            $dataDetalle = '<table  border=1 cellspacing=0 cellpadding=5 bordercolor="000000"  width="91.7%">
                        <thead>';

            $html = '<tr>';
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Tipo de documento</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Cliente</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>S/N</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Importe</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>F.Emisión</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Motivo anulación</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>F.Anulación</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Error SUNAT</th>";
            $html = $html . '</tr>';

            $dataDetalle = $dataDetalle . $html;
            $dataDetalle = $dataDetalle . '<thead>';
            $dataDetalle = $dataDetalle . '<tbody>';

            foreach ($documentos as $index => $item) {
                $html = '<tr>';
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['documento_tipo_descripcion'];
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['persona_descripcion'];
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['serie_numero'];
                $html = $html . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . $item['moneda_simbolo'] . '&nbsp;' . number_format($item['total'], 2, ".", ",");
                $html = $html . '<td style=\'text-align:center;font-size:14px;line-height:1.5;\'>' . DateUtil::formatearFechaBDAaCadenaVw($item['fecha_emision']);
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['motivo_anulacion'];
                $html = $html . '<td style=\'text-align:center;font-size:14px;line-height:1.5;\'>' . DateUtil::formatearFechaBDAaCadenaVw($item['anulacion_fecha']);
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['error_sunat'];
                $html = $html . '</tr>';

                $dataDetalle = $dataDetalle . $html;
            }

            $dataDetalle = $dataDetalle . '</tbody></table>';
            //            $descripcion = 'Se restablecieron los siguientes documentos, porque la verificación de la anulación fue incorrecta. Anule los documentos por el sistema.';
            //logica correo:             
            $asunto = '[EFACT] ' . $plantilla[0]["asunto"];
            $cuerpo = $plantilla[0]["cuerpo"];

            $cuerpo = str_replace("[|titulo_email|]", 'DOCUMENTOS RESTABLECIDOS PENDIENTES DE ANULACION', $cuerpo);
            $cuerpo = str_replace("[|descripcion|]", $descripcion, $cuerpo);
            $cuerpo = str_replace("[|documento_detalle|]", $dataDetalle, $cuerpo);

            $res = EmailEnvioNegocio::create()->insertarEmailEnvio($correos, $asunto, $cuerpo, 1, 1);
            //            return $cuerpo;
            return 'Documentos restablecidos. ' . $res[0]['vout_mensaje'] . ' Id: ' . $res[0]['id'];
        } else {
            return '';
        }
    }

    public function generarDocumentosElectronicosPendientes()
    {
        $contadorMaximoRegistro = Configuraciones::EFACT_WS_CONTADOR_MAXIMO;

        $usuarioId = 1;
        $docPendientes = Documento::create()->obtenerDocumentosPendientesDeGeneracionEfact($contadorMaximoRegistro);

        $docCorrectos = array();
        $docErrorControlado = array();
        $docErrorDesconocido = array();

        foreach ($docPendientes as $index => $item) {
            $resValido = $this->consultarDocumentoSUNAT($item['documento_id']);

            //DES COMENTAR PARA PRUEBAS EN BETA:
            //            $resValido->tipoRespuesta = 0;
            //            $resValido->mensaje = 'Documento no existe';

            if ($resValido->tipoRespuesta == 1) {
                //GENERO SOLO PDF
                //                $resEfact = MovimientoNegocio::create()->generarDocumentoElectronico($item['documento_id'], $item['identificador_negocio'], 1, 2);                
                $resEstadoRegistro = DocumentoNegocio::create()->actualizarEfactEstadoRegistro($item['documento_id'], DocumentoTipoNegocio::EFACT_CORRECTO);
            } else {
                //GENERAR DOCUMENTO ELECTRONICO
                $resEfact = MovimientoNegocio::create()->generarDocumentoElectronico($item['documento_id'], $item['identificador_negocio'], 0, 2);

                $respDocElectronico = $resEfact->respDocElectronico;

                if ($respDocElectronico->tipoMensaje == 1) {
                    $item['url_PDF'] = $respDocElectronico->urlPDF;
                    $item['nombre_PDF'] = $respDocElectronico->nombrePDF;

                    array_push($docCorrectos, $item);
                }
                if ($respDocElectronico->tipoMensaje == 2) {
                    $item['efact_mensaje_respuesta'] = $respDocElectronico->mensaje;

                    array_push($docErrorControlado, $item);

                    $documentoEstadoId = 5; //ESTADO ELIMINADO
                    $resDocEstado = DocumentoNegocio::create()->ActualizarDocumentoEstadoId($item['documento_id'], $documentoEstadoId, $usuarioId);

                    $estado = 2; //DOCUMENTO ELIMINADO
                    $resDoc = DocumentoNegocio::create()->actualizarEstadoXId($item['documento_id'], $estado);
                }
                if ($respDocElectronico->tipoMensaje == 3) {
                    if (($item['efact_ws_contador'] * 1 + 1) == $contadorMaximoRegistro) {
                        $item['efact_mensaje_respuesta'] = trim($respDocElectronico->mensaje);
                        array_push($docErrorDesconocido, $item);
                    }

                    $resActContador = Documento::create()->actualizarEfactContadorRegistro($item['documento_id']);
                }
            }
        }

        //ENVIAR CORREO
        $resEmailTodos = '';
        if (!ObjectUtil::isEmpty($docPendientes)) {
            //A EFACT
            $plantilla = EmailPlantillaNegocio::create()->obtenerEmailPlantillaXID(24);
            $correosPlantilla = EmailPlantillaNegocio::create()->obtenerEmailXDestinatario($plantilla[0]["destinatario"], null, null);

            $correos = '';
            foreach ($correosPlantilla as $email) {
                $correos = $correos . $email . ';';
            }
        }

        //ENVIAR CORRECTOS
        if (!ObjectUtil::isEmpty($docCorrectos)) {
            $documentosGrupo = $this->obtenerDocumentosGrupoXUsuario($docCorrectos);
            $descripcion = 'Los siguientes documentos, fueron registrados en SUNAT correctamente';
            $tituloEmail = 'DOCUMENTOS GENERADOS CORRECTAMENTE - SUNAT';
            $asuntoEmail = '[EFACT] Documentos generados correctamente - SUNAT';

            foreach ($documentosGrupo as $key => $itemGrupo) {
                $correosEnvio = $correos . $itemGrupo['usuario_email'];
                $resEmail = $this->enviarCorreoDocumentosGeneradosSunat(DocumentoTipoNegocio::EFACT_CORRECTO, $itemGrupo['documentos'], $plantilla, $correosEnvio, $descripcion, $tituloEmail, $asuntoEmail);
                $resEmailTodos = $resEmailTodos . '<br>' . $resEmail;
            }
        }

        //ENVIAR CON ERROR CONTROLADO
        if (!ObjectUtil::isEmpty($docErrorControlado)) {
            $documentosGrupo = $this->obtenerDocumentosGrupoXUsuario($docErrorControlado);
            $descripcion = 'Los siguientes documentos, no se pudieron registrar en SUNAT, devolvieron un error controlado, se eliminaron del sistema, tiene que volver a registrar.';
            $tituloEmail = 'DOCUMENTOS NO GENERADOS CORRECTAMENTE - SUNAT';
            $asuntoEmail = '[EFACT] Documentos no generados correctamente - SUNAT - Eliminados del sistema';

            foreach ($documentosGrupo as $key => $itemGrupo) {
                $correosEnvio = $correos . $itemGrupo['usuario_email'];
                $resEmail = $this->enviarCorreoDocumentosGeneradosSunat(DocumentoTipoNegocio::EFACT_ERROR_CONTROLADO, $itemGrupo['documentos'], $plantilla, $correosEnvio, $descripcion, $tituloEmail, $asuntoEmail);
                $resEmailTodos = $resEmailTodos . '<br>' . $resEmail;
            }
        }

        //ENVIAR CON ERROR DESCONOCIDO
        if (!ObjectUtil::isEmpty($docErrorDesconocido)) {
            $documentosGrupo = $this->obtenerDocumentosGrupoXUsuario($docErrorDesconocido);
            $descripcion = 'Los siguientes documentos, no se pudieron registrar en SUNAT, devolvieron un error, revise los documentos. Se intentó registrar ' . $contadorMaximoRegistro . ' veces';
            $tituloEmail = 'DOCUMENTOS NO GENERADOS CORRECTAMENTE - SUNAT';
            $asuntoEmail = '[EFACT] Documentos no generados correctamente - SUNAT';

            foreach ($documentosGrupo as $key => $itemGrupo) {
                $correosEnvio = $correos . $itemGrupo['usuario_email'];
                $resEmail = $this->enviarCorreoDocumentosGeneradosSunat(DocumentoTipoNegocio::EFACT_ERROR_DESCONOCIDO, $itemGrupo['documentos'], $plantilla, $correosEnvio, $descripcion, $tituloEmail, $asuntoEmail);
                $resEmailTodos = $resEmailTodos . '<br>' . $resEmail;
            }
        }


        return $resEmailTodos;
    }

    function enviarCorreoDocumentosGeneradosSunat($tipoRespuesta, $documentos, $plantilla, $correos, $descripcion, $tituloEmail, $asuntoEmail)
    {

        if (!ObjectUtil::isEmpty($documentos)) {
            //dibujando la cabecera
            $dataDetalle = '<table  border=1 cellspacing=0 cellpadding=5 bordercolor="000000"  width="91.7%">
                        <thead>';

            $html = '<tr>';
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Tipo de documento</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Cliente</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>S/N</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Importe</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>F.Emisión</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>F.Creación</th>";

            if ($tipoRespuesta == DocumentoTipoNegocio::EFACT_CORRECTO) {
                $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>PDF</th>";
            }
            if (
                $tipoRespuesta == DocumentoTipoNegocio::EFACT_ERROR_CONTROLADO ||
                $tipoRespuesta == DocumentoTipoNegocio::EFACT_ERROR_DESCONOCIDO
            ) {
                $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Respuesta error</th>";
            }

            $html = $html . '</tr>';

            $dataDetalle = $dataDetalle . $html;
            $dataDetalle = $dataDetalle . '<thead>';
            $dataDetalle = $dataDetalle . '<tbody>';

            foreach ($documentos as $index => $item) {
                $html = '<tr>';
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['documento_tipo_descripcion'];
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['persona_descripcion'];
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['serie_numero'];
                $html = $html . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . $item['moneda_simbolo'] . '&nbsp;' . number_format($item['total'], 2, ".", ",");
                $html = $html . '<td style=\'text-align:center;font-size:14px;line-height:1.5;\'>' . DateUtil::formatearFechaBDAaCadenaVw($item['fecha_emision']);
                $html = $html . '<td style=\'text-align:center;font-size:14px;line-height:1.5;\'>' . DateUtil::formatearFechaBDAaCadenaVw($item['fecha_creacion']);

                if ($tipoRespuesta == DocumentoTipoNegocio::EFACT_CORRECTO) {
                    $urlPdfSgi = Configuraciones::url_base() . 'pdf2.php?url_pdf=' . $item['url_PDF'] . '&nombre_pdf=' . $item['nombre_PDF'];
                    $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . '<a href="' . $urlPdfSgi . '" target="_blank">Descargar</a>';
                }
                if (
                    $tipoRespuesta == DocumentoTipoNegocio::EFACT_ERROR_CONTROLADO ||
                    $tipoRespuesta == DocumentoTipoNegocio::EFACT_ERROR_DESCONOCIDO
                ) {
                    $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['efact_mensaje_respuesta'];
                }

                $html = $html . '</tr>';

                $dataDetalle = $dataDetalle . $html;
            }

            $dataDetalle = $dataDetalle . '</tbody></table>';

            $asunto = $asuntoEmail;
            $cuerpo = $plantilla[0]["cuerpo"];

            $cuerpo = str_replace("[|titulo_email|]", $tituloEmail, $cuerpo);
            $cuerpo = str_replace("[|descripcion|]", $descripcion, $cuerpo);
            $cuerpo = str_replace("[|documento_detalle|]", $dataDetalle, $cuerpo);

            $res = EmailEnvioNegocio::create()->insertarEmailEnvio($correos, $asunto, $cuerpo, 1, 1);
            //            return $cuerpo;
            return 'Respuesta tipo: ' . $tipoRespuesta . '. ' . $res[0]['vout_mensaje'] . ' Id: ' . $res[0]['id'];
        } else {
            return '';
        }
    }

    public function consultarDocumentoSUNAT($documentoId)
    {

        $documento = DocumentoNegocio::create()->obtenerDocumentoFEXId($documentoId);
        if (ObjectUtil::isEmpty($documento)) {
            throw new WarningException("No se encontró el documento");
        }

        $empresa = EmpresaNegocio::create()->obtenerEmpresaXId($documento[0]['empresa_id']);

        $comprobanteElectronico->emisorNroDocumento = $empresa[0]['ruc'];
        //        $comprobanteElectronico->emisorNroDocumento = '20531807520';
        $comprobanteElectronico->docTipoDocumento = $documento[0]['sunat_tipo_doc_rel'];
        $comprobanteElectronico->docSerie = $documento[0]["serie"];
        $comprobanteElectronico->docNumero = $documento[0]["numero"];

        $comprobanteElectronico->usuarioSunatSOL = $empresa[0]['usuario_sunat_sol'];
        $comprobanteElectronico->claveSunatSOL = $empresa[0]['clave_sunat_sol'];
        //        $comprobanteElectronico->usuarioSunatSOL = 'MARESTA2';
        //        $comprobanteElectronico->claveSunatSOL = 'Maresta2018';

        $comprobanteElectronico = (array) $comprobanteElectronico;

        $client = self::conexionEFAC();

        try {
            $resultado = $client->procesarConsultaDocumento($comprobanteElectronico)->procesarConsultaDocumentoResult;
        } catch (Exception $e) {
            $resultado = $e->getMessage();
        }

        if (strpos($resultado, '[Cod: IMA01]') === false) {
            $tipoRespuesta = 0;
        } else {
            $tipoRespuesta = 1;
        }

        $respuesta->tipoRespuesta = $tipoRespuesta;
        $respuesta->mensaje = $resultado;

        return $respuesta;
    }

    public function conexionEFAC()
    {
        try {
            $client = new SoapClient(Configuraciones::EFACT_URL);
        } catch (Exception $e) {
            //            $resultado = $e->getMessage();
            throw new WarningException("Imposible conectarse con el servicio facturador.");
        }
        return $client;
    }

    public function autogenerarNCTipo13XFacturaId($documentoId, $usuarioId)
    {

        $docConfiguracionNC = DocumentoTipoNegocio::create()->obtenerDocumentoTipoNC(5, 2);
        $opcionNCId = $docConfiguracionNC[0]['opcion_id'];
        $documentoTipoNCId = $docConfiguracionNC[0]['documento_tipo_id'];
        $movimientoTipoNCId = $docConfiguracionNC[0]['movimiento_tipo_id'];
        $documento_tipo_conf = DocumentoTipoNegocio::create()->obtenerDocumentoTipoDato($documentoTipoNCId, $usuarioId);
        $documentoARelacionarNC[0]['documentoId'] = $documentoId;

        if (!ObjectUtil::isEmpty($documento_tipo_conf)) {
            for ($i = 0; $i < count($documento_tipo_conf); $i++) {
                //                SERIE
                if ($documento_tipo_conf[$i]['tipo'] == 7) {
                    $idenSerie = $documento_tipo_conf[$i]['id'];
                    $valorSerie = $documento_tipo_conf[$i]['cadena_defecto'];
                }
                //                NUMERO
                if ($documento_tipo_conf[$i]['tipo'] == 8) {
                    $idenNumero = $documento_tipo_conf[$i]['id'];
                    $valorNumero = $documento_tipo_conf[$i]['data'];
                }
                //                CLIENTE
                if ($documento_tipo_conf[$i]['tipo'] == 5) {
                    $idenPersona = $documento_tipo_conf[$i]['id'];
                    $descPersona = $documento_tipo_conf[$i]['descripcion'];
                }
                //                FECHA EMISION
                if ($documento_tipo_conf[$i]['tipo'] == 9) {
                    $idenFecha = $documento_tipo_conf[$i]['id'];
                    $valorFecha = $documento_tipo_conf[$i]['data'];
                }
                //                FECHA VENCIMIENTO
                if ($documento_tipo_conf[$i]['tipo'] == 10) {
                    $idenFechaVenc = $documento_tipo_conf[$i]['id'];
                    $valorFechaVenc = $documento_tipo_conf[$i]['data'];
                }
                //                RETENCION
                if ($documento_tipo_conf[$i]['tipo'] == 4 && $documento_tipo_conf[$i]['codigo'] == 10) {
                    $idenRentencion = $documento_tipo_conf[$i]['id'];
                    $descRetencion = $documento_tipo_conf[$i]['descripcion'];
                }
                //                MOTIVO EMISION
                if ($documento_tipo_conf[$i]['tipo'] == 4 && isEmpty($documento_tipo_conf[$i]['codigo'])) {
                    $idenMotivo = $documento_tipo_conf[$i]['id'];
                    $descMotivo = $documento_tipo_conf[$i]['descripcion'];
                }
                ////                PROYECTO
                //                if ($documento_tipo_conf[$i]['tipo'] == 2) {
                //                    $idenProyecto = $documento_tipo_conf[$i]['id'];
                //                    $descProyecto = $documento_tipo_conf[$i]['descripcion'];
                //                }
                ////                DETRACCION
                //                if ($documento_tipo_conf[$i]['tipo'] == 36) {
                //                    $idenDetraccion = $documento_tipo_conf[$i]['id'];
                //                    $descDetraccion = $documento_tipo_conf[$i]['descripcion'];
                //                }
                //                Importe
                if ($documento_tipo_conf[$i]['tipo'] == 14) {
                    $idenImporte = $documento_tipo_conf[$i]['id'];
                    $descImporte = $documento_tipo_conf[$i]['descripcion'];
                }
                //                Sub total
                if ($documento_tipo_conf[$i]['tipo'] == 16) {
                    $idenSubTotal = $documento_tipo_conf[$i]['id'];
                    $descSubTotal = $documento_tipo_conf[$i]['descripcion'];
                }
                //                IGV
                if ($documento_tipo_conf[$i]['tipo'] == 15) {
                    $idenIGV = $documento_tipo_conf[$i]['id'];
                    $descIGV = $documento_tipo_conf[$i]['descripcion'];
                }
                ////                Forma de pago 
                //                if ($documento_tipo_conf[$i]['tipo'] == 12) {
                //                    $idenFormaPago = $documento_tipo_conf[$i]['id'];
                //                    $descFormaPago = $documento_tipo_conf[$i]['descripcion'];
                //                }
            }
        }
        //        obtenemos los datos de la factura para generar la NC
        $documento = DocumentoNegocio::create()->obtenerDocumentoFEXId($documentoId);
        $valorPersona = $documento[0]["persona_id"];
        $dataDocumento = DocumentoNegocio::create()->obtenerDetalleDocumento($documentoId);
        $monedaId = $documento[0]["moneda_id"];
        $tipoPago = $documento[0]["tipo_pago"];
        $empresaId = $documento[0]["empresa_id"];
        foreach ($dataDocumento as $index => $item) {
            switch ($item['documento_tipo_id'] * 1) {
                case 2906:
                    if ($item['valor'] == 378) { //Si aplica retencion
                        $retencionFact = 409;
                    } else {
                        $retencionFact = 408;
                    }
                    break;
                    //                case 2935:
                    //                    $proyectoFact = $item['valor'];
                    //                    break;
                    //                case 2931:
                    //                    $detraccionFact = $item['valor'];
                    //                    break;
                    //                case 2929:
                    //                    $prodDuplicadoFact = $item['valor'];
                    //                    break;
            }
        }

        //INICIO DE CABECERA
        $k = 0;
        $camposDinamicosNC[$k]['id'] = $idenSerie;
        $camposDinamicosNC[$k]['tipo'] = 7;
        $camposDinamicosNC[$k]['descripcion'] = "Serie";
        $camposDinamicosNC[$k]['valor'] = $valorSerie;
        $k++;
        $camposDinamicosNC[$k]['id'] = $idenNumero;
        $camposDinamicosNC[$k]['tipo'] = 8;
        $camposDinamicosNC[$k]['descripcion'] = "Número";
        $camposDinamicosNC[$k]['valor'] = $valorNumero;
        $k++;
        $camposDinamicosNC[$k]['id'] = $idenFecha;
        $camposDinamicosNC[$k]['tipo'] = 9;
        $camposDinamicosNC[$k]['descripcion'] = "Fecha";
        $camposDinamicosNC[$k]['valor'] = date("d/m/Y");
        $k++;
        $camposDinamicosNC[$k]['id'] = $idenFechaVenc;
        $camposDinamicosNC[$k]['tipo'] = 10;
        $camposDinamicosNC[$k]['descripcion'] = "Fecha de vencimiento";
        $camposDinamicosNC[$k]['valor'] = date("d/m/Y", strtotime(date("Ymd")));
        $k++;
        $camposDinamicosNC[$k]['id'] = $idenPersona;
        $camposDinamicosNC[$k]['tipo'] = 5;
        $camposDinamicosNC[$k]['descripcion'] = $descPersona;
        $camposDinamicosNC[$k]['valor'] = $valorPersona;
        $k++;
        $camposDinamicosNC[$k]['id'] = $idenMotivo;
        $camposDinamicosNC[$k]['tipo'] = 4;
        $camposDinamicosNC[$k]['descripcion'] = $descMotivo;
        $camposDinamicosNC[$k]['valor'] = 410; //tipo 13
        $k++;
        $camposDinamicosNC[$k]['id'] = $idenRentencion;
        $camposDinamicosNC[$k]['tipo'] = 4;
        $camposDinamicosNC[$k]['descripcion'] = $descRetencion;
        $camposDinamicosNC[$k]['valor'] = $retencionFact;
        //        $k++;
        //        $camposDinamicosNC[$k]['id'] = $idenDetraccion;
        //        $camposDinamicosNC[$k]['tipo'] = 36;
        //        $camposDinamicosNC[$k]['descripcion'] = $descDetraccion;
        //        $camposDinamicosNC[$k]['valor'] = isEmpty($detraccionFact)? '': $detraccionFact;
        //        $k++;
        //        $camposDinamicosNC[$k]['id'] = $idenProyecto;
        //        $camposDinamicosNC[$k]['tipo'] = 2;
        //        $camposDinamicosNC[$k]['descripcion'] = $descProyecto;
        //        $camposDinamicosNC[$k]['valor'] = isEmpty($proyectoFact)? '':$proyectoFact;
        $k++;
        $camposDinamicosNC[$k]['id'] = $idenImporte;
        $camposDinamicosNC[$k]['tipo'] = 14;
        $camposDinamicosNC[$k]['descripcion'] = $descImporte;
        $camposDinamicosNC[$k]['valor'] = 0;
        $k++;
        $camposDinamicosNC[$k]['id'] = $idenSubTotal;
        $camposDinamicosNC[$k]['tipo'] = 16;
        $camposDinamicosNC[$k]['descripcion'] = $descSubTotal;
        $camposDinamicosNC[$k]['valor'] = 0;
        $k++;
        $camposDinamicosNC[$k]['id'] = $idenIGV;
        $camposDinamicosNC[$k]['tipo'] = 15;
        $camposDinamicosNC[$k]['descripcion'] = $descIGV;
        $camposDinamicosNC[$k]['valor'] = 0;
        //        $k++;
        //        $camposDinamicosNC[$k]['id'] = $idenFormaPago;
        //        $camposDinamicosNC[$k]['tipo'] = 12;
        //        $camposDinamicosNC[$k]['descripcion'] = $descFormaPago;
        //        $k++;
        //        $camposDinamicosNC[$k]['id'] = 2930;
        //        $camposDinamicosNC[$k]['tipo'] = 32;
        //        $camposDinamicosNC[$k]['descripcion'] = "Producto duplicado";
        //        $camposDinamicosNC[$k]['valor'] = $prodDuplicadoFact;

        $comentario = 'Nota de credito generada para regularizar fechas de pago';

        $anio = date("Y");
        $mes = date("m");
        $dataPeriodo = Periodo::create()->obtenerPeriodoXEmpresaXAnioXMes($empresaId, $anio, $mes);
        $periodoId = $dataPeriodo[0]['id'];
        $valorCheck = 1;
        $accionEnvio = "enviar";
        //FIN DE CABECERA
        //Datos Extras
        $datosExtras['afecto_detraccion_retencion'] = $documento[0]["afecto_detraccion_retencion"];
        $datosExtras['porcentaje_afecto'] = $documento[0]["porcentaje_afecto"];
        $datosExtras['monto_detraccion_retencion'] = $documento[0]["monto_detraccion_retencion"];

        //Programacion de pago
        $formaPagoDetalle = PagoNegocio::create()->obtenerPagoProgramacionXDocumentoId($documentoId);
        $j = 0;
        $listaPagoProgramacion[0][0] = date("d/m/Y", strtotime(date("Ymd") . "+ 1 days"));
        $j++;
        $listaPagoProgramacion[0][1] = $formaPagoDetalle[0]['importe'];
        $j++;
        $listaPagoProgramacion[0][2] = $formaPagoDetalle[0]['dias'];
        $j++;
        $listaPagoProgramacion[0][3] = $formaPagoDetalle[0]['porcentaje'];
        $j++;
        $listaPagoProgramacion[0][4] = '';

        // DETALLE
        $documentoDetalle = MovimientoBien::create()->obtenerXIdMovimiento($documento[0]["movimiento_id"]);
        foreach ($documentoDetalle as $index => $fila) {

            $items[$index]['organizadorDesc'] = '';
            $items[$index]['unidadMedidaId'] = $fila['unidad_medida_id'];
            $items[$index]['index'] = $index;
            $items[$index]['stockBien'] = 0;
            $items[$index]['bienDesc'] = $fila['bien_descripcion'];
            $items[$index]['cantidad'] = $fila['cantidad'];
            $items[$index]['subTotal'] = $fila['cantidad'];
            $items[$index]['precio'] = $fila['valor_monetario'];
            $items[$index]['precioTipoId'] = $fila['precio_tipo_id'];
            $items[$index]['bienId'] = $fila['bien_id'];
            $items[$index]['unidadMedidaDesc'] = $fila['unidad_medida_descripcion'];
        }
        $detalle = $items;
        //guardarXAccionEnvio($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $documentoARelacionar, $valorCheck, $comentario, $checkIgv, $monedaId, $accionEnvio, $tipoPago, $listaPagoProgramacion, $atiende = null, $periodoId = null, $percepcion = null, $datosExtras = null) 
        $docNCId = $this->guardarXAccionEnvio($opcionNCId, $usuarioId, $documentoTipoNCId, $camposDinamicosNC, $detalle, $documentoARelacionarNC, $valorCheck, $comentario, 0, $monedaId, $accionEnvio, $tipoPago, $listaPagoProgramacion, null, $periodoId, null, $datosExtras);
        $dataEmpresa = EmpresaNegocio::create()->obtenerEmpresaXDocumentoId($docNCId->documentoId);
        if ($dataEmpresa[0]['efactura'] == 1) {
            $resEfact = $this->generarDocumentoElectronico($docNCId->documentoId, 5);
            $respuesta->resEfact = $resEfact;
            $mensaje = $resEfact->respDocElectronico->mensaje;
            return $mensaje;
        }
    }

    public function generarQR($archivo, $textoQR)
    {
        $rutaFisica = __DIR__ . "/../../util/generarqr/";
        //  $rutaWeb = Configuraciones::url_base() . $rutaComun;
        //        array_map('unlink', glob("$rutaFisica*.png"));
        $archivo = $rutaFisica . $archivo . ".png";
        QRcode::png($textoQR, $archivo, 'L', 2, 1);
    }

    public function guardarRegistroQR($textoQR, $tipo, $referenciaId, $usuarioId)
    {
        //        1:Pedido|P     2:paquete|Pqt    3:organizador|Org
        $res = Movimiento::create()->guardarRegistroQR($textoQR, $tipo, $referenciaId, $usuarioId);
        return $res;
    }

    public function obtenerDataQR($textoQR)
    {
        $arrayTextoQR = explode("|", $textoQR);
        $tipoTexto = $arrayTextoQR[0];
        return $res = Movimiento::create()->obtenerDataQR($tipoTexto, $textoQR);
    }

    public function consultarQRPaquete($textoQR, $usuarioCreacion)
    {
        $resultado = new stdClass();
        $arrayTextoQR = explode("|", $textoQR);
        $tipoTexto = $arrayTextoQR[0];
        if ($tipoTexto != 'PQT') {
            throw new WarningException("El QR leido no pertenece a un paquete");
        } else {
            $resultado->datosQR = MovimientoNegocio::create()->obtenerDataQR($textoQR, $usuarioCreacion);
            $this->validateResponse($resultado->datosQR);
            $organizadorId = $resultado->datosQR[0]['ubicacion'];
            $resultado->datosQR[0]['url_tracking'] = ConfigGlobal::PARAM_URL_HTTP . ConfigGlobal::PARAM_URL_ADD  . "tracking.php?" . Util::encripta("tipo=1&documentoId=" . $resultado->datosQR[0]['documento_id']);
            $resultado->datosQR[0]['url_tracking_paquete'] = ConfigGlobal::PARAM_URL_HTTP . ConfigGlobal::PARAM_URL_ADD  . "tracking_paquete.php?" . Util::encripta("tipo=1&paqueteId=" . $resultado->datosQR[0]['codigo_paquete']);
            $resultado->ubicacion = OrganizadorNegocio::create()->obtenerOrganizadorUbicacion($organizadorId);
            return $resultado;
        }
    }

    public function consultarCodigoPaquete($codigo, $usuarioCreacion)
    {
        $resultado = new stdClass();
        $tipo = 2;
        $respuesta_codigo = Movimiento::create()->obtenerDataCodigo($codigo, $tipo);
        $textoQR = $respuesta_codigo[0]['textoQr'];
        if ($textoQR == null) {
            throw new WarningException("El código no se encuentra registrado");
        } else {
            $arrayTextoQR = explode("|", $textoQR);
            $tipoTexto = $arrayTextoQR[0];
            $data = MovimientoNegocio::create()->obtenerDataQR($textoQR, $usuarioCreacion);
            if ($data == null) {
                throw new WarningException("El código paquete no tiene data asignada");
            } else {
                $resultado->datosQR = $data;
                $organizadorId = $resultado->datosQR[0]['ubicacion'];
                $resultado->datosQR[0]['url_tracking'] = ConfigGlobal::PARAM_URL_HTTP . ConfigGlobal::PARAM_URL_ADD  . "tracking.php?" . Util::encripta("tipo=1&documentoId=" . $resultado->datosQR[0]['documento_id']);
                $resultado->datosQR[0]['url_tracking_paquete'] = ConfigGlobal::PARAM_URL_HTTP . ConfigGlobal::PARAM_URL_ADD  . "tracking_paquete.php?" . Util::encripta("tipo=1&paqueteId=" . $resultado->datosQR[0]['codigo_paquete']);
                $resultado->ubicacion = OrganizadorNegocio::create()->obtenerOrganizadorUbicacion($organizadorId);
                return $resultado;
            }
        }
    }

    public function consultarQrOrganizador($textoQR, $usuarioCreacion)
    {
        $arrayTextoQR = explode("|", $textoQR);
        $tipoTexto = $arrayTextoQR[0];
        if ($tipoTexto != 'ORG') {
            throw new WarningException("El QR leido no pertenece a un organizador");
        } else {
            $response = MovimientoNegocio::create()->obtenerDataQR($textoQR, $usuarioCreacion);
            $this->validateResponse($response);
            $response_perfil = Perfil::create()->obtnerPerfilAgenciaXUsuarioId($usuarioCreacion);
            $id_perfil = $response_perfil[0]['id'];

            $response_agencia = PerfilAgenciaCaja::create()->obtenerPerfilAgenciaCajaXPerfilId($id_perfil);
            $id_agencia = $response_agencia[0]['agencia_id'];

            $organizadorId = $response[0]['id'];

            $resultado = OrganizadorNegocio::create()->obtenerOrganizadorUbicacionV($organizadorId, $id_agencia);
            if ($resultado[0]['vout_exito'] == '0') {
                throw new WarningException($resultado[0]['vout_mensaje']);
            } else {
                return $resultado;
            }
        }
    }

    public function registrarPaqueteQr($textoQR, $usuarioCreacion, $organizadorId)
    {

        $arrayTextoQR = explode("|", $textoQR);
        $tipoTexto = $arrayTextoQR[0];
        if ($tipoTexto != 'PQT') {
            throw new WarningException("El QR leido no pertenece a un paquete");
        }
        $response = MovimientoNegocio::create()->obtenerDataQR($textoQR, $usuarioCreacion);
        $this->validateResponse($response);
        $codigo_paquete = $response[0]['codigo_paquete'];

        $respuesta_paquete = Movimiento::create()->obtenerAlmacenxPaquete($organizadorId, $codigo_paquete);
        if ($respuesta_paquete[0]['vout_exito'] != 1) {
            throw new WarningException($respuesta_paquete[0]['vout_mensaje']);
        }
        Movimiento::create()->UpdateTracking($codigo_paquete);
        Movimiento::create()->InsertTracking($codigo_paquete, $organizadorId, $usuarioCreacion);

        $data = new stdClass();
        $data->CodigoProducto = $response[0]['codigo'];
        $data->AgenciaOrigen = $response[0]['origen'];
        $data->Remitente = $response[0]['remitente'];
        $data->Destino = $response[0]['destino'];
        $data->Destinatario = $response[0]['destinatario'];
        $data->OrganizadorDestino = OrganizadorNegocio::create()->obtenerOrganizadorUbicacion($organizadorId);

        return $data;
    }

    public function registrarPaqueteCodigo($codigo, $usuarioCreacion, $organizadorId)
    {
        $data = new ObjectUtil();
        $tipo = 2;
        $respuesta_codigo = Movimiento::create()->obtenerDataCodigo($codigo, $tipo);
        $textoQR = $respuesta_codigo[0]['textoQr'];

        return self::registrarPaqueteQr($textoQR, $usuarioCreacion, $organizadorId);
    }

    public function listarPaquete($fecha, $usuarioCreacion)
    {


        $response_perfil = Perfil::create()->obtnerPerfilAgenciaXUsuarioId($usuarioCreacion);
        $id_perfil = $response_perfil[0]['id'];

        $response = PerfilAgenciaCaja::create()->obtenerPerfilAgenciaCajaXPerfilId($id_perfil);
        $id_agencia = $response[0]['agencia_id'];

        $resultado = Movimiento::create()->ListarPaquete($id_agencia, $fecha);

        return $resultado;
    }

    public function eliminarPaquete($id)
    {

        $tipo = 3;
        $respuesta = Movimiento::create()->ObtenerTackingId($id, $tipo);

        $this->validateResponse($respuesta);
        Movimiento::create()->UpdateTackingAnterior($id);

        $response = Movimiento::create()->DelateTacking($id);
        return $response;
    }

    public function eliminarPaqueteDespacho($id)
    {

        $tipo = 2;
        $respuesta = Movimiento::create()->ObtenerTackingId($id, $tipo);

        $this->validateResponse($respuesta);
        Movimiento::create()->UpdateTackingAnterior($id);

        $response = Movimiento::create()->DelateTacking($id);
        return $response;
    }

    public function eliminarReparto($id)
    {
        $tipo = 6;
        $respuesta = Movimiento::create()->ObtenerTackingId($id, $tipo);

        $this->validateResponse($respuesta);
        Movimiento::create()->UpdateTackingAnterior($id);

        $response = Movimiento::create()->DelateTacking($id);
        return $response;
    }

    public function eliminarPaqueteRecepcionado($id)
    {
        $tipo = 4;
        $respuesta = Movimiento::create()->ObtenerTackingId($id, $tipo);

        $this->validateResponse($respuesta);
        Movimiento::create()->UpdateTackingAnterior($id);

        $response = Movimiento::create()->DelateTacking($id);
        return $response;
    }

    public function eliminarPaqueteReparto($id)
    {

        $tipo = 5;
        $respuesta = Movimiento::create()->ObtenerTackingId($id, $tipo);

        $this->validateResponse($respuesta);
        Movimiento::create()->UpdateTackingAnterior($id);

        $response = Movimiento::create()->DelateTacking($id);
        return $response;
    }

    public function obtenerDocumentoQR($textoQR, $usuarioCreacion)
    {
        $data = new stdClass();

        $arrayTextoQR = explode("|", $textoQR);
        $tipoTexto = $arrayTextoQR[0];
        if ($tipoTexto != 'V') {
            throw new WarningException("El QR leido no pertenece a un vehiculo");
        } else {
            $response = MovimientoNegocio::create()->obtenerDataQR($textoQR);
            $this->validateResponse($response);

            $estado = 1;
            $movimientoTipoId = 144;
            $documento_estado = 8;
            $documento_estadoA = 9;
            $tipo = 1;

            $vehiculoId = $response[0]['id'];
            $vehiculoPlaca = $response[0]['placa'];

            $response_perfil = Perfil::create()->obtnerPerfilAgenciaXUsuarioId($usuarioCreacion);
            $id_perfil = $response_perfil[0]['id'];

            $response_agencia = PerfilAgenciaCaja::create()->obtenerPerfilAgenciaCajaXPerfilId($id_perfil);
            $codigo_agencia = $response_agencia[0]['codigo'];
            $descripcion_agencia = $response_agencia[0]['agencia_descripcion'];
            $id_agencia = $response_agencia[0]['agencia_id'];

            $resultado_caja = ACCajaNegocio::create()->obtenerAccCaja($usuarioCreacion);
            $id_caja = $resultado_caja[0]['caja_id'];
            $sufijo = $resultado_caja[0]['sufijo_comprobante'];

            $fecha_salida = date("Y-m-d");
            $resultado_periodo = PeriodoNegocio::create()->obtenerPeriodoIdXFechas($fecha_salida);
            $id_periodo = $resultado_periodo[0]['id'];


            $dataManifiesto = DocumentoNegocio::create()->obtenerDocumentoManiestoxVehiculo($vehiculoId, $id_agencia);
            $documentoRecepcionId = $dataManifiesto[0]['documento_relacion_id'];

            $data->placaVehiculo = $vehiculoPlaca;
            $data->codigoAgencia = $codigo_agencia;
            $data->descripcionAgencia = $descripcion_agencia;

            if (ObjectUtil::isEmpty($documentoRecepcionId)) {
                $correlativo = Documento::create()->obtenerNumeroAutoXDocumentoTipo(277, NULL);
                $dato_correlativo = $correlativo[0]['numero'];

                $resultadoRegistroMovimiento = Movimiento::create()->guardar($movimientoTipoId, $estado, $usuarioCreacion);
                $movimientoRecepcionId = $resultadoRegistroMovimiento[0]['vout_id'];

                $resultadoDocumentoGuardarRecepcion = Documento::create()->InsertDocumentoRecepcion($vehiculoId, $usuarioCreacion, $id_agencia, $movimientoRecepcionId, $id_caja, $sufijo, $dato_correlativo, $id_periodo);
                $documentoRecepcionId = $resultadoDocumentoGuardarRecepcion[0]['id'];

                Documento::create()->insertarDocumentoDocumentoEstado($documentoRecepcionId, $documento_estado, $usuarioCreacion);
            }
            $data->documentoId = $documentoRecepcionId;
            $data->paquetesLeidos = Movimiento::create()->obtenerPaquetesxDocumento($documentoRecepcionId, $estado);
            $pedidosPorLeer = array();
            $data->documentoManifiestoId = NULL;

            foreach ($dataManifiesto as $index => $resultado) {
                $documentoId = $resultado['id'];
                $agenciaDestino = $resultado['agencia_destino_id'];
                $data->documentoManifiestoId = $documentoId;
                if (!ObjectUtil::isEmpty($agenciaDestino)) {
                    $documentoRelacionado = Documento::create()->obtenerDocumentoRelacionadoId($documentoId, 1);
                    if (ObjectUtil::isEmpty($documentoRelacionado)) {
                        $respuestaInsertar = Documento::create()->insertarDocumentoRelacionado($documentoId, $documentoRecepcionId, $estado, $usuarioCreacion, $tipo);
                    }

                    $dataPedidosPorLeer = Movimiento::create()->obtenerPaquetesxDocumento($documentoId, $estado);
                    if (!ObjectUtil::isEmpty($dataPedidosPorLeer)) {
                        $pedidosPorLeer = array_merge($pedidosPorLeer, $dataPedidosPorLeer);
                    }
                } else {

                    $documentoRelacionado = Documento::create()->obtenerDocumentoRelacionadoId($documentoId, 1);
                    if (ObjectUtil::isEmpty($documentoRelacionado)) {
                        $respuestaInsertar = Documento::create()->insertarDocumentoRelacionado($documentoId, $documentoRecepcionId, $estado, $usuarioCreacion, $tipo);
                    }

                    $dataPedidosPorLeer = Movimiento::create()->obtenerPaquetesxDocumento2($documentoId, $estado);
                    if (!ObjectUtil::isEmpty($dataPedidosPorLeer)) {
                        $pedidosPorLeer = array_merge($pedidosPorLeer, $dataPedidosPorLeer);
                    }
                }
            }
            // if (!ObjectUtil::isEmpty($data->documentoManifiestoId)) {
            //     $data->documentoManifiestoId = rtrim($data->documentoManifiestoId, ",");
            // }

            $data->paquetesPorLeer =  $pedidosPorLeer;
            return $data;
        }
    }

    public function obtenerDocumentoNumeroSerie($serie, $numero, $usuarioCreacion)
    {
        $data = new ObjectUtil();
        $estado = 1;
        $movimientoTipoId = 144;
        $documento_estado = 8;
        $documento_estadoA = 9;
        $tipo = 1;

        $response_perfil = Perfil::create()->obtnerPerfilAgenciaXUsuarioId($usuarioCreacion);
        $id_perfil = $response_perfil[0]['id'];

        $response_agencia = PerfilAgenciaCaja::create()->obtenerPerfilAgenciaCajaXPerfilId($id_perfil);
        $codigo_agencia = $response_agencia[0]['codigo'];
        $descripcion_agencia = $response_agencia[0]['agencia_descripcion'];
        $id_agencia = $response_agencia[0]['agencia_id'];

        $resultado_caja = ACCajaNegocio::create()->obtenerAccCaja($usuarioCreacion);
        $id_caja = $resultado_caja[0]['caja_id'];
        $sufijo = $resultado_caja[0]['sufijo_comprobante'];
        $fecha_salida = date("Y-m-d");
        $resultado_periodo = PeriodoNegocio::create()->obtenerPeriodoIdXFechas($fecha_salida);
        $id_periodo = $resultado_periodo[0]['id'];
        $resultado = Documento::create()->obtenerDocumentoxNumeroSerie($serie, $numero, $id_agencia);
        $documentoId = $resultado[0]['id'];
        $documentoEstado = $resultado[0]['estado'];
        $documentoDocumentoEstado = $resultado[0]['documento_estado_id'];
        $agenciaDestino = $resultado[0]['agencia_destino_id'];
        $agenciaOrigen = $resultado[0]['agencia_id'];
        $vehiculoPlaca = $resultado[0]['vehiculo_placa'];
        $vehiculoId = $resultado[0]['vehiculo_id'];
        $correlativo = Documento::create()->obtenerNumeroAutoXDocumentoTipo(277, $serie = NULL);
        $dato_correlativo = $correlativo[0]['numero'];
        if ($agenciaDestino != null) {
            if ($documentoEstado == 0) {
                throw new WarningException("Manifiesto pendiente de confirmacion");
            } else if ($agenciaDestino != $id_agencia) {
                throw new WarningException("El manifiesto activos no corresponden al destino");
            } else if ($documentoEstado == 1) {
                $documento_relacionado = Documento::create()->obtenerDocumentoRelacionadoId($documentoId, 1);
                $tipoP = $documento_relacionado[0]['tipo'];
                if ($tipoP != null) {
                    $documentoId2 = $documento_relacionado[0]['documento_relacionado_id'];
                } else {
                    $resultado_movimiento = Movimiento::create()->guardar($movimientoTipoId, $estado, $usuarioCreacion);
                    $id_movimiento = $resultado_movimiento[0]['vout_id'];

                    $resultado_documentoI = Documento::create()->InsertDocumentoRecepcion($vehiculoId, $usuarioCreacion, $id_agencia, $id_movimiento, $id_caja, $sufijo, $dato_correlativo, $id_periodo);
                    $documentoId2 = $resultado_documentoI[0]['id'];

                    Documento::create()->insertarDocumentoDocumentoEstado($documentoId2, $documento_estado, $usuarioCreacion);
                    Documento::create()->insertarDocumentoRelacionado($documentoId, $documentoId2, $estado, $usuarioCreacion, $tipo);
                    //                    Documento::create()->insertarDocumentoDocumentoEstado($documentoId, $documento_estadoA, $usuarioCreacion);
                }
                $data->placaVehiculo = $vehiculoPlaca;
                $data->codigoAgencia = $codigo_agencia;
                $data->descripcionAgencia = $descripcion_agencia;
                $data->documentoManifiestoId = $documentoId;
                $data->documentoId = $documentoId2;
                $data->paquetesPorLeer = Movimiento::create()->obtenerPaquetesxDocumento($documentoId, $estado);
                $data->paquetesLeidos = Movimiento::create()->obtenerPaquetesxDocumento($documentoId2, $estado);
                return $data;
            } else {

                throw new WarningException("El manifiesto no cumple los requisitos");
            }
        } else {

            if ($documentoEstado == 0) {
                throw new WarningException("Manifiesto pendiente de confirmacion");
            } else if ($agenciaOrigen != $id_agencia) {
                throw new WarningException("El manifiesto activos no corresponden a la agencia de recepcion");
            } else if ($documentoEstado == 1) {

                $documento_relacionado = Documento::create()->obtenerDocumentoRelacionadoId($documentoId, 1);
                $tipoP = $documento_relacionado[0]['tipo'];
                if ($tipoP != null) {
                    $documentoId2 = $documento_relacionado[0]['documento_id'];
                } else {
                    $resultado_movimiento = Movimiento::create()->guardar($movimientoTipoId, $estado, $usuarioCreacion);
                    $id_movimiento = $resultado_movimiento[0]['vout_id'];

                    $resultado_documentoI = Documento::create()->InsertDocumentoRecepcion($vehiculoId, $usuarioCreacion, $id_agencia, $id_movimiento, $id_caja, $sufijo);
                    $documentoId2 = $resultado_documentoI[0]['id'];

                    Documento::create()->insertarDocumentoDocumentoEstado($documentoId2, $documento_estado, $usuarioCreacion);
                    Documento::create()->insertarDocumentoRelacionado($documentoId, $documentoId2, $estado, $usuarioCreacion, $tipo);
                    //                    Documento::create()->insertarDocumentoDocumentoEstado($documentoId, $documento_estadoA, $usuarioCreacion);
                }
                $data->placaVehiculo = $vehiculoPlaca;
                $data->codigoAgencia = $codigo_agencia;
                $data->descripcionAgencia = $descripcion_agencia;
                $data->documentoManifiestoId = $documentoId;
                $data->documentoId = $documentoId2;
                $data->paquetesPorLeer = Movimiento::create()->obtenerPaquetesxDocumento2($documentoId, $estado);
                $data->paquetesLeidos = Movimiento::create()->obtenerPaquetesxDocumento($documentoId2, $estado);
                return $data;
            } else {

                throw new WarningException("El manifiesto no cumple los requisitos");
            }
        }
    }

    public function registrarRecepcionPaqueteQR($textoQR, $usuarioCreacion, $documentoId)
    {
        $data = new stdClass();
        $arrayTextoQR = explode("|", $textoQR);
        $tipoTexto = $arrayTextoQR[0];
        if ($tipoTexto != 'PQT') {
            throw new WarningException("El QR leido no pertenece a un paquete");
        } else {
            //:::DESARROLLO JESUS:::::::
            $pedidosPorLeer = array();
            $dataPedidosPorLeer = Movimiento::create()->obtenerPaquetesxDocumento2($documentoId, 1);
            if (!ObjectUtil::isEmpty($dataPedidosPorLeer)) {
                $pedidosPorLeer = array_merge($pedidosPorLeer, $dataPedidosPorLeer);
            }
         //:::DESARROLLO JESUS:::::::
            $response = MovimientoNegocio::create()->obtenerDataQR($textoQR, $usuarioCreacion);
            $this->validateResponse($response);
            $codigo_paquete = $response[0]['codigo_paquete'];
            $id_bien = $response[0]['id_bien'];

            $response_perfil = Perfil::create()->obtnerPerfilAgenciaXUsuarioId($usuarioCreacion);
            $id_perfil = $response_perfil[0]['id'];

            $response_agencia = PerfilAgenciaCaja::create()->obtenerPerfilAgenciaCajaXPerfilId($id_perfil);
            $organizador_defecto = $response_agencia[0]['organizador_defecto_recepcion_id'];

            $respuesta_movimiento = Movimiento::create()->obtenerMovimientoXDocumentoId($documentoId);
            $id_movimiento = $respuesta_movimiento[0]['movimiento_id'];

            $resultado_relacion = DocumentoNegocio::create()->obtenerDocumentoRelacionadoActivoXDocumentoIdXPaqueteId($documentoId, $codigo_paquete);
            $documentoId2 = $resultado_relacion[0]['documento_id'];

            $respuesta_paquete = Movimiento::create()->obtenerDocumentoRecepcionxPaquete($documentoId, $codigo_paquete, $documentoId2);
            $this->validateResponse($respuesta_paquete);

            $respuesta_movimiento_bien = Movimiento::create()->InsertMovimientoBien($id_movimiento, $id_bien, $usuarioCreacion);
            $id_movimiento_bien = $respuesta_movimiento_bien[0]['id'];
            Movimiento::create()->UpdateTracking($codigo_paquete);
            Movimiento::create()->InsertTrackingRecepcion($codigo_paquete, $id_movimiento_bien, $usuarioCreacion, $organizador_defecto);

            $data->AgenciaOrigen = $response[0]['origen'];
            $data->AgenciaDestino = $response[0]['destino'];
            $data->Remitente = $response[0]['remitente'];
            $data->Destinatario = $response[0]['destinatario'];
            $data->DocumentoId = $documentoId;
            $data->DocumentoManifiestoId = $documentoId2;
             //:::DESARROLLO JESUS
            $data->  $pedidosPorLeer;
            $datos ='LLEGUE';
            $data->$datos;
            //:::DESARROLLO JESUS
            return $data;
        }
    }

    public function registrarRecepcionPaqueteCodigo($codigo, $usuarioCreacion, $documentoId)
    {
        $data = new ObjectUtil();
        $tipo = 2;
        $respuesta_codigo = Movimiento::create()->obtenerDataCodigo($codigo, $tipo);
        $textoQR = $respuesta_codigo[0]['textoQr'];
        if ($textoQR == null) {
            throw new WarningException("El código no se encuentra registrado");
        }
        $arrayTextoQR = explode("|", $textoQR);
        $tipoTexto = $arrayTextoQR[0];
        if ($tipoTexto != 'PQT') {
            throw new WarningException("El QR leido no pertenece a un paquete");
        } else {
            $response = MovimientoNegocio::create()->obtenerDataQR($textoQR, $usuarioCreacion);
            $this->validateResponse($response);
            $codigo_paquete = $response[0]['codigo_paquete'];
            $id_bien = $response[0]['id_bien'];

            $response_perfil = Perfil::create()->obtnerPerfilAgenciaXUsuarioId($usuarioCreacion);
            $id_perfil = $response_perfil[0]['id'];

            $response_agencia = PerfilAgenciaCaja::create()->obtenerPerfilAgenciaCajaXPerfilId($id_perfil);
            $organizador_defecto = $response_agencia[0]['organizador_defecto_recepcion_id'];

            $respuesta_movimiento = Movimiento::create()->obtenerMovimientoXDocumentoId($documentoId);
            $id_movimiento = $respuesta_movimiento[0]['movimiento_id'];

            $resultado_relacion = DocumentoNegocio::create()->obtenerDocumentoRelacionadoActivoXDocumentoId2($documentoId);
            $documentoId2 = $resultado_relacion[0]['documento_id'];

            $respuesta_paquete = Movimiento::create()->obtenerDocumentoRecepcionxPaquete($documentoId, $codigo_paquete, $documentoId2);
            $this->validateResponse($respuesta_paquete);

            $respuesta_movimiento_bien = Movimiento::create()->InsertMovimientoBien($id_movimiento, $id_bien, $usuarioCreacion);
            $id_movimiento_bien = $respuesta_movimiento_bien[0]['id'];
            Movimiento::create()->UpdateTracking($codigo_paquete);
            Movimiento::create()->InsertTrackingRecepcion($codigo_paquete, $id_movimiento_bien, $usuarioCreacion, $organizador_defecto);

            $data->AgenciaOrigen = $response[0]['origen'];
            $data->AgenciaDestino = $response[0]['destino'];
            $data->Remitente = $response[0]['remitente'];
            $data->Destinatario = $response[0]['destinatario'];
            $data->DocumentoId = $documentoId;
            $data->DocumentoManifiestoId = $documentoId2;
            return $data;
        }
    }

    public function registrarPaqueteDespacho($textoQR, $documentoId, $usuarioCreacion, $agencia_id, $bandera)
    {
        $data = new ObjectUtil();
        $arrayTextoQR = explode("|", $textoQR);
        $tipoTexto = $arrayTextoQR[0];
        if ($tipoTexto != 'PQT') {
            throw new WarningException("El QR leido no pertenece a un paquete");
        } else {
            $response = MovimientoNegocio::create()->obtenerDataQR($textoQR, $usuarioCreacion);

            $this->validateResponse($response);

            $codigo_paquete = $response[0]['codigo_paquete'];
            $codigo_bien = $response[0]['codigo'];
            $id_bien = $response[0]['id_bien'];
            $remitente = $response[0]['remitente'];
            $destino = $response[0]['destino'];
            $destinatario = $response[0]['destinatario'];
            $destinoId = $response[0]['codigo_destino'];

            if ($agencia_id != $destinoId && $bandera == 0) {
                $modal = new stdClass();
                $bandera = 0;
                $mensaje = 'El QR leido no pertenece a este destino, ¿esta seguro de asignarlo?';
                $modal->bandera = $bandera;
                $modal->mensaje = $mensaje;
                return $modal;
            } else {

                $resultado = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);
                $chofer = $resultado[0]['chofer_id'];
                $copilotoId = $resultado[0]['copiloto_id'];
                $vehiculo = $resultado[0]['vehiculo_id'];
                $agenciaOrigen = $resultado[0]['agencia_id'];
                $agenciaDestino = $agencia_id;
                $periodo = $resultado[0]['periodo_id'];
                $fecha_salida = $resultado[0]['fecha_salida'];

                $movimientoTipoId = 143; //146
                $estado = 1;
                $documento_estado = 8;
                $tipo = 4;

                $correlativo = Documento::create()->obtenerNumeroAutoXDocumentoTipo(276, $serie = NULL);
                $dato_correlativo = $correlativo[0]['numero'];

                $resultado_caja = ACCajaNegocio::create()->obtenerAccCaja($usuarioCreacion);
                $id_caja = $resultado_caja[0]['caja_id'];
                $sufijo = $resultado_caja[0]['sufijo_comprobante'];

                $resultado_documento = Documento::create()->obtenerDocumentoManifiestoDespacho($vehiculo, $usuarioCreacion, $agenciaOrigen, $agenciaDestino, $documentoId);
                if ($resultado_documento[0]['vout_exito'] == 1) {

                    $respuesta_paquete = Movimiento::create()->obtenerDocumentoxPaquete($codigo_paquete);

                    $this->validateResponse($respuesta_paquete);

                    $resultado_movimiento = Movimiento::create()->guardar($movimientoTipoId, $estado, $usuarioCreacion);
                    $id_movimiento = $resultado_movimiento[0]['vout_id'];

                    $resultado_documentoI = Documento::create()->InsertDocumentoManifiesto($vehiculo, $usuarioCreacion, $agenciaOrigen, $fecha_salida, $fechaLlegada, $chofer, $agenciaDestino, $id_caja, $sufijo, $periodo, $id_movimiento, $dato_correlativo, $copilotoId);
                    $id_documento = $resultado_documentoI[0]['id'];

                    Documento::create()->insertarDocumentoDocumentoEstado($id_documento, $documento_estado, $usuarioCreacion);

                    Documento::create()->insertarDocumentoRelacionado($documentoId, $id_documento, $estado, $usuarioCreacion, $tipo);
                } else {

                    $id_movimiento = $resultado_documento[0]['movimiento_id'];
                    $id_documento = $resultado_documento[0]['codigo'];
                }

                $respuesta_movimiento_bien = Movimiento::create()->InsertMovimientoBien($id_movimiento, $id_bien, $usuarioCreacion);
                $id_movimiento_bien = $respuesta_movimiento_bien[0]['id'];
                Movimiento::create()->UpdateTracking($codigo_paquete);
                Movimiento::create()->InsertTrackingDespacho($codigo_paquete, $id_movimiento_bien, $usuarioCreacion);
                $data->documentoId = $documentoId;
                $data->agenciaDestino = $destino;
                $data->remitente = $remitente;
                $data->destinatario = $destinatario;
                $data->codigoPaquete = $codigo_bien;
                $data->paqueteId = $codigo_paquete;

                return $data;
            }
        }
    }

    public function registrarCodigoPaqueteDespacho($codigo, $documentoId, $usuarioCreacion, $agencia_id, $bandera)
    {
        $tipo = 2;
        $respuesta_codigo = Movimiento::create()->obtenerDataCodigo((int)$codigo, $tipo);
        if (ObjectUtil::isEmpty($respuesta_codigo)) {
            throw new WarningException("No se encontro información relacionada al código ingresado.");
        }

        $textoQR = $respuesta_codigo[0]['textoQr'];
        if (ObjectUtil::isEmpty($textoQR)) {
            throw new WarningException("El código no se encuentra registrado.");
        }

        return self::registrarPaqueteDespacho($textoQR, $documentoId, $usuarioCreacion, $agencia_id, $bandera);
    }

    public function registrarPaqueteReparto($textoQR, $documentoId, $usuarioCreacion)
    {
        $data = new ObjectUtil();

        $arrayTextoQR = explode("|", $textoQR);
        $tipoTexto = $arrayTextoQR[0];
        if ($tipoTexto != 'PQT') {
            throw new WarningException("El QR leido no pertenece a un paquete");
        } else {
            $response = MovimientoNegocio::create()->obtenerDataQR($textoQR, $usuarioCreacion);

            $this->validateResponse($response);
            $codigo_paquete = $response[0]['codigo_paquete'];
            $codigo_bien = $response[0]['codigo'];
            $id_bien = $response[0]['id_bien'];
            $remitente = $response[0]['remitente'];
            $destino = $response[0]['destino'];
            $origen = $response[0]['origen'];
            $destinatario = $response[0]['destinatario'];

            $respuesta_movimiento = Movimiento::create()->obtenerMovimientoXDocumentoId($documentoId);
            $id_movimiento = $respuesta_movimiento[0]['movimiento_id'];
            $respuesta_paquete = Movimiento::create()->obtenerDocumentoRxPaquete($documentoId, $codigo_paquete);

            $this->validateResponse($respuesta_paquete);

            $respuesta_movimiento_bien = Movimiento::create()->InsertMovimientoBien($id_movimiento, $id_bien, $usuarioCreacion);
            $id_movimiento_bien = $respuesta_movimiento_bien[0]['id'];
            Movimiento::create()->UpdateTracking($codigo_paquete);
            Movimiento::create()->InsertTrackingReparto($codigo_paquete, $id_movimiento_bien, $usuarioCreacion);

            $data->documentoId = $documentoId;
            $data->agenciaOrigen = $origen;
            $data->agenciaDestino = $destino;
            $data->remitente = $remitente;
            $data->destinatario = $destinatario;
            $data->codigoPaquete = $codigo_bien;
            $data->paqueteId = $codigo_paquete;

            return $data;
        }
    }

    public function registrarCodigoPaqueteReparto($codigo, $documentoId, $usuarioCreacion)
    {
        $data = new ObjectUtil();
        $tipo = 2;
        $respuesta_codigo = Movimiento::create()->obtenerDataCodigo($codigo, $tipo);
        $textoQR = $respuesta_codigo[0]['textoQr'];
        if ($textoQR == null) {
            throw new WarningException("El código no se encuentra registrado");
        }
        $arrayTextoQR = explode("|", $textoQR);
        $tipoTexto = $arrayTextoQR[0];
        if ($tipoTexto != 'PQT') {
            throw new WarningException("El QR leido no pertenece a un paquete");
        } else {
            $response = MovimientoNegocio::create()->obtenerDataQR($textoQR, $usuarioCreacion);

            $this->validateResponse($response);
            $codigo_paquete = $response[0]['codigo_paquete'];
            $codigo_bien = $response[0]['codigo'];
            $id_bien = $response[0]['id_bien'];
            $remitente = $response[0]['remitente'];
            $destino = $response[0]['destino'];
            $origen = $response[0]['origen'];
            $destinatario = $response[0]['destinatario'];

            $respuesta_movimiento = Movimiento::create()->obtenerMovimientoXDocumentoId($documentoId);
            $id_movimiento = $respuesta_movimiento[0]['movimiento_id'];
            $respuesta_paquete = Movimiento::create()->obtenerDocumentoRxPaquete($documentoId, $codigo_paquete);

            $this->validateResponse($respuesta_paquete);

            $respuesta_movimiento_bien = Movimiento::create()->InsertMovimientoBien($id_movimiento, $id_bien, $usuarioCreacion);
            $id_movimiento_bien = $respuesta_movimiento_bien[0]['id'];
            Movimiento::create()->UpdateTracking($codigo_paquete);
            Movimiento::create()->InsertTrackingReparto($codigo_paquete, $id_movimiento_bien, $usuarioCreacion);

            $data->documentoId = $documentoId;
            $data->agenciaOrigen = $origen;
            $data->agenciaDestino = $destino;
            $data->remitente = $remitente;
            $data->destinatario = $destinatario;
            $data->codigoPaquete = $codigo_bien;
            $data->paqueteId = $codigo_paquete;

            return $data;
        }
    }

    public function obtenerDocumentoPaquetes($documentoId, $usuarioCreacion)
    {
        $data = new stdClass();

        $estado = 1;
        $estado2 = 0;

        $response_perfil = Perfil::create()->obtnerPerfilAgenciaXUsuarioId($usuarioCreacion);
        $id_perfil = $response_perfil[0]['id'];

        $response_agencia = PerfilAgenciaCaja::create()->obtenerPerfilAgenciaCajaXPerfilId($id_perfil);
        $codigo_agencia = $response_agencia[0]['codigo'];
        $descripcion_agencia = $response_agencia[0]['agencia_descripcion'];

        $resultado = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);
        $vehiculoId = $resultado[0]['vehiculo_id'];
        $vehiculoPlaca = $resultado[0]['placa'];
        $id_agencia = $resultado[0]['agencia_destino_id'];


        $dataManifiesto = DocumentoNegocio::create()->obtenerDocumentoManiestoxVehiculo($vehiculoId, $id_agencia);
        $documentoRecepcionId = $dataManifiesto[0]['documento_relacion_id'];

        $data->placaVehiculo = $vehiculoPlaca;
        $data->codigoAgencia = $codigo_agencia;
        $data->descripcionAgencia = $descripcion_agencia;

        $data->documentoId = $documentoRecepcionId;
        $data->paquetesLeidos = Movimiento::create()->obtenerPaquetesxDocumento($documentoRecepcionId, $estado);
        $pedidosPorLeer = array();
        $data->documentoManifiestoId = NULL;

        foreach ($dataManifiesto as $index => $resultado) {
            $documentoId = $resultado['id'];
            $agenciaDestino = $resultado['agencia_destino_id'];
            $data->documentoManifiestoId = $documentoId;
            if (!ObjectUtil::isEmpty($agenciaDestino)) {
                $documentoRelacionado = Documento::create()->obtenerDocumentoRelacionadoId($documentoId, 1);
                if (ObjectUtil::isEmpty($documentoRelacionado)) {
                    $respuestaInsertar = Documento::create()->insertarDocumentoRelacionado($documentoId, $documentoRecepcionId, $estado, $usuarioCreacion, $tipo);
                }

                $dataPedidosPorLeer = Movimiento::create()->obtenerPaquetesxDocumento($documentoId, $estado);
                if (!ObjectUtil::isEmpty($dataPedidosPorLeer)) {
                    $pedidosPorLeer = array_merge($pedidosPorLeer, $dataPedidosPorLeer);
                }
            } else {

                $documentoRelacionado = Documento::create()->obtenerDocumentoRelacionadoId($documentoId, 1);
                if (ObjectUtil::isEmpty($documentoRelacionado)) {
                    $respuestaInsertar = Documento::create()->insertarDocumentoRelacionado($documentoId, $documentoRecepcionId, $estado, $usuarioCreacion, $tipo);
                }

                $dataPedidosPorLeer = Movimiento::create()->obtenerPaquetesxDocumento2($documentoId, $estado);
                if (!ObjectUtil::isEmpty($dataPedidosPorLeer)) {
                    $pedidosPorLeer = array_merge($pedidosPorLeer, $dataPedidosPorLeer);
                }
            }
        }
        // if (!ObjectUtil::isEmpty($data->documentoManifiestoId)) {
        //     $data->documentoManifiestoId = rtrim($data->documentoManifiestoId, ",");
        // }

        $data->paquetesPorLeer =  $pedidosPorLeer;
        return $data;


        // $vehiculoPlaca = $resultado[0]['placa'];
        // $agenciaDestinoId = $resultado[0]['agencia_destino_id'];

        // $resultado_relacion = DocumentoNegocio::create()->obtenerDocumentoRelacionadoActivoXDocumentoId3($documentoId);
        // $documentoId2 = $resultado_relacion[0]['documento_relacionado_id'];

        // $data->placaVehiculo = $vehiculoPlaca;
        // $data->codigoAgencia = $codigo_agencia;
        // $data->descripcionAgencia = $descripcion_agencia;
        // $data->documentoId = $documentoId2;
        // if (ObjectUtil::isEmpty($agenciaDestinoId)) {
        //     $data->paquetesPorLeer = Movimiento::create()->obtenerPaquetesxDocumento2($documentoId, $estado2);
        // } else {
        //     $data->paquetesPorLeer = Movimiento::create()->obtenerPaquetesxDocumento($documentoId, $estado2);
        // }
        // $data->paquetesLeidos = Movimiento::create()->obtenerPaquetesxDocumento($documentoId2, $estado);
        // return $data;
    }

    public function getDataAgenciaDespacho($usuarioCreacion)
    {
        $response_perfil = Perfil::create()->obtnerPerfilAgenciaXUsuarioId($usuarioCreacion);
        $id_perfil = $response_perfil[0]['id'];

        $response = PerfilAgenciaCaja::create()->obtenerPerfilAgenciaCajaXPerfilId($id_perfil);
        $id_agencia = $response[0]['agencia_id'];
        return Agencia::create()->getDataAgenciaDespacho($id_agencia);
    }

    public function obtenerDespachoQR($textoQR, $usuarioCreacion, $fecha)
    {
        $arrayTextoQR = explode("|", $textoQR);
        $tipoTexto = $arrayTextoQR[0];
        if ($tipoTexto != 'V') {
            throw new WarningException("El QR leido no pertenece a un vehiculo");
        }
        $resultado = MovimientoNegocio::create()->obtenerDataQR($textoQR, $usuarioCreacion);

        $this->validateResponse($resultado);

        $vehiculo_id = $resultado[0]['id'];
        $vehiculo_placa = $resultado[0]['placa'];

        $response_perfil = Perfil::create()->obtnerPerfilAgenciaXUsuarioId($usuarioCreacion);
        $id_perfil = $response_perfil[0]['id'];

        $response = PerfilAgenciaCaja::create()->obtenerPerfilAgenciaCajaXPerfilId($id_perfil);
        $id_agencia = $response[0]['agencia_id'];
        $descripcion_agencia = $response[0]['agencia_descripcion'];

        //DATA DE PRUEBA 
        $choferId = NULL;
        $copilotoId = NULL;
        $agenciaDestinoId = NULL;
        $descripcion_agenciaD = ' ';
        //            $fecha_salida = '2022-10-19 22:00:00';
        //            $fechaLlegada = '2022-10-19 04:00:00';

        $dataItinerario = self::obtenerAgenciasIttsa($fecha, $usuarioCreacion, $vehiculo_placa);

        if (!ObjectUtil::isEmpty($dataItinerario)) {
            usort($dataItinerario, function ($fechaInicial, $fechaFinal) {
                return strtotime(trim($fechaInicial['itinerarioHoraSalida'])) < strtotime(trim($fechaFinal['itinerarioHoraSalida']));
            });

            foreach ($dataItinerario as $item) {
                if (time() <= strtotime(trim($item['itinerarioHoraSalida']))) {
                    $fecha = str_replace('T', ' ', trim($item['itinerarioHoraSalida']));
                    $agenciaDestinoId = $item['agencia_destino_id'];
                    $descripcion_agenciaD = $item['destinoNombre'];
                    $dataTribulacion = $item['tripulación'];
                    foreach ($dataTribulacion as $valueIndex => $value) {
                        if ($value['pilotoCargo'] == 'PILOTO') {
                            $choferId  = $value['chofer_id'];
                        } elseif ($value['pilotoCargo'] == 'COPILOTO') {
                            $copilotoId  = $value['chofer_id'];
                        }
                    }
                    break;
                }
            }
        }

        $movimientoTipoId = 146; //146
        $estado = 1;
        $documento_estado = 8;
        //FIN DATA PRUEBA
        $resultado_periodo = PeriodoNegocio::create()->obtenerPeriodoIdXFechas($fecha);
        $id_periodo = $resultado_periodo[0]['id'];

        $resultado_caja = ACCajaNegocio::create()->obtenerAccCaja($usuarioCreacion);
        $id_caja = $resultado_caja[0]['caja_id'];
        $sufijo = $resultado_caja[0]['sufijo_comprobante'];
        $correlativo = Documento::create()->obtenerNumeroAutoXDocumentoTipo(279, $serie = NULL);
        $dato_correlativo = $correlativo[0]['numero'];
        $resultado_documento = Documento::create()->obtenerDocumentoDespacho($vehiculo_id, $usuarioCreacion, $id_agencia, $choferId, $agenciaDestinoId, $id_caja, $sufijo, $id_periodo);
        if ($resultado_documento[0]['vout_exito'] == 2) {
            throw new WarningException("EXISTE UN DESPACHO ACTIVO PARA ESTE VEHICULO CON ESTE ORIGEN");
        }
        if ($resultado_documento[0]['vout_exito'] == 1) {
            $resultado_movimiento = Movimiento::create()->guardar($movimientoTipoId, $estado, $usuarioCreacion);
            $id_movimiento = $resultado_movimiento[0]['vout_id'];

            $resultado_documentoI = Documento::create()->InsertDocumentoDespacho($vehiculo_id, $usuarioCreacion, $id_agencia, $choferId, $agenciaDestinoId, $id_caja, $sufijo, $id_periodo, $id_movimiento, $dato_correlativo, $copilotoId, $fecha);
            $id_documento = $resultado_documentoI[0]['id'];

            Documento::create()->insertarDocumentoDocumentoEstado($id_documento, $documento_estado, $usuarioCreacion);
        } else {

            $id_movimiento = $resultado_documento[0]['movimiento_id'];
            $id_documento = $resultado_documento[0]['codigo'];
        }

        $dataInput = new stdClass();
        $dataInput->vehiculoPlaca = $vehiculo_placa;
        $dataInput->agenciaOrigenId = $id_agencia;
        $dataInput->agenciaOrigenDescripcion = $descripcion_agencia;
        $dataInput->agenciaDestinoId = $agenciaDestinoId;
        $dataInput->agenciaDestinoDescripcion = $descripcion_agenciaD;
        $dataInput->fecha = $fecha;
        $dataInput->documentoId = $id_documento;
        $dataInput->movimientoId = $id_movimiento;
        $dataInput->pilotoId = $choferId;
        $dataInput->copilotoId = $copilotoId;
        return $dataInput;
    }

    public function obtenerDespachoIttsa(
        $agenciaDestinoId,
        $descripcion_agenciaD,
        $fecha_salida,
        $fechaLlegada,
        $flotaPlaca,
        $usuarioCreacion,
        $choferId,
        $copilotoId
    ) {

        $response_vehiculo_placa = Vehiculo::create()->obtenerVehiculoxPlaca($flotaPlaca);
        $vehiculo_id = $response_vehiculo_placa[0]['id'];
        $vehiculo_placa = $response_vehiculo_placa[0]['flota_placa'];

        $response_perfil = Perfil::create()->obtnerPerfilAgenciaXUsuarioId($usuarioCreacion);
        $id_perfil = $response_perfil[0]['id'];

        $response = PerfilAgenciaCaja::create()->obtenerPerfilAgenciaCajaXPerfilId($id_perfil);
        $id_agencia = $response[0]['agencia_id'];
        $descripcion_agencia = $response[0]['agencia_descripcion'];


        // $response_chofer_integracion = Persona::create()->obtenerPersonaXDocumentoChofer($choferId);
        // $chofer_id = $response_chofer_integracion[0]['id'];
        $response_agencia_integracion = Agencia::create()->obtenerAgenciaxIdIntegracion($agenciaDestinoId);
        $id_agenciaD = $response_agencia_integracion[0]['id'];


        $movimientoTipoId = 146;
        $estado = 1;
        $documento_estado = 8;

        $resultado_periodo = PeriodoNegocio::create()->obtenerPeriodoIdXFechas($fecha_salida);
        $id_periodo = $resultado_periodo[0]['id'];

        $resultado_caja = ACCajaNegocio::create()->obtenerAccCaja($usuarioCreacion);
        $id_caja = $resultado_caja[0]['caja_id'];
        $sufijo = $resultado_caja[0]['sufijo_comprobante'];
        $correlativo = Documento::create()->obtenerNumeroAutoXDocumentoTipo(279, $serie = NULL);
        $dato_correlativo = $correlativo[0]['numero'];
        $resultado_documento = Documento::create()->obtenerDocumentoDespacho($vehiculo_id, $usuarioCreacion, $id_agencia, $choferId, $id_agenciaD, $id_caja, $sufijo, $id_periodo);
        if ($resultado_documento[0]['vout_exito'] == 2) {
            throw new WarningException("EXISTE UN DESPACHO ACTIVO PARA ESTE VEHICULO CON ESTE ORIGEN");
        }
        if ($resultado_documento[0]['vout_exito'] == 1) {
            $resultado_movimiento = Movimiento::create()->guardar($movimientoTipoId, $estado, $usuarioCreacion);
            $id_movimiento = $resultado_movimiento[0]['vout_id'];

            $resultado_documentoI = Documento::create()->InsertDocumentoDespacho($vehiculo_id, $usuarioCreacion, $id_agencia, $choferId, $agenciaDestinoId, $id_caja, $sufijo, $id_periodo, $id_movimiento, $dato_correlativo, $copilotoId);
            $id_documento = $resultado_documentoI[0]['id'];

            Documento::create()->insertarDocumentoDocumentoEstado($id_documento, $documento_estado, $usuarioCreacion);
        } else {

            $id_movimiento = $resultado_documento[0]['movimiento_id'];
            $id_documento = $resultado_documento[0]['codigo'];
        }

        $dataInput = new ObjectUtil();
        $dataInput->vehiculoPlaca = $vehiculo_placa;
        $dataInput->agenciaOrigenId = $id_agencia;
        $dataInput->agenciaOrigenDescripcion = $descripcion_agencia;
        $dataInput->agenciaDestinoId = $agenciaDestinoId;
        $dataInput->agenciaDestinoDescripcion = $descripcion_agenciaD;
        $dataInput->fecha =  str_replace("T", " ", $fecha_salida); // $fecha_salida;
        $dataInput->documentoId = $id_documento;
        $dataInput->movimientoId = $id_movimiento;
        $dataInput->pilotoId = $choferId;
        $dataInput->copilotoId = $copilotoId;
        return $dataInput;
    }

    public function obtenerVehiculoRepartoQR($textoQR, $fecha, $usuarioCreacion)
    {
        $arrayTextoQR = explode("|", $textoQR);
        $tipoTexto = $arrayTextoQR[0];
        if ($tipoTexto != 'V') {
            throw new WarningException("El QR leido no pertenece a un vehiculo");
        } else {
            $resultado = MovimientoNegocio::create()->obtenerDataQR($textoQR, $usuarioCreacion);
            $this->validateResponse($resultado);
            $vehiculo_id = $resultado[0]['id'];
            $vehiculo_placa = $resultado[0]['placa'];

            $response_perfil = Perfil::create()->obtnerPerfilAgenciaXUsuarioId($usuarioCreacion);
            $id_perfil = $response_perfil[0]['id'];

            $response = PerfilAgenciaCaja::create()->obtenerPerfilAgenciaCajaXPerfilId($id_perfil);
            $id_agencia = $response[0]['agencia_id'];
            $descripcion_agencia = $response[0]['agencia_descripcion'];

            //DATA DE PRUEBA 
            $movimientoTipoId = 143;
            $estado = 1;
            $documento_estado = 8;
            //FIN DATA PRUEBA
            $resultado_periodo = PeriodoNegocio::create()->obtenerPeriodoIdXFechas($fecha);
            $id_periodo = $resultado_periodo[0]['id'];

            $resultado_caja = ACCajaNegocio::create()->obtenerAccCaja($usuarioCreacion);
            $id_caja = $resultado_caja[0]['caja_id'];
            $sufijo = $resultado_caja[0]['sufijo_comprobante'];

            $resultado_documento = Documento::create()->obtenerDocumentoManifiestoReparto($vehiculo_id, $usuarioCreacion, $id_agencia);

            if ($resultado_documento[0]['vout_exito'] == 1) {
                $resultado_movimiento = Movimiento::create()->guardar($movimientoTipoId, $estado, $usuarioCreacion);
                $id_movimiento = $resultado_movimiento[0]['vout_id'];

                $correlativo = Documento::create()->obtenerNumeroAutoXDocumentoTipo(276, $serie = NULL);
                $dato_correlativo = $correlativo[0]['numero'];
                $resultado_documentoI = Documento::create()->InsertDocumentoManifiestoReparto($vehiculo_id, $usuarioCreacion, $id_agencia, $fecha, $id_caja, $sufijo, $id_periodo, $id_movimiento, $dato_correlativo);
                $id_documento = $resultado_documentoI[0]['id'];

                Documento::create()->insertarDocumentoDocumentoEstado($id_documento, $documento_estado, $usuarioCreacion);
            } else {

                $id_movimiento = $resultado_documento[0]['movimiento_id'];
                $id_documento = $resultado_documento[0]['codigo'];
            }


            $dataInput = new ObjectUtil();
            $dataInput->vehiculoPlaca = $vehiculo_placa;
            $dataInput->agenciaOrigenId = $id_agencia;
            $dataInput->agenciaOrigenDescripcion = $descripcion_agencia;
            $dataInput->fecha = $fecha;
            $dataInput->documentoId = $id_documento;
            $dataInput->movimientoId = $id_movimiento;
            //        try {
            //            $client = new SoapClient(Configuraciones::CSRUC_URL);
            //            $resultados = $client->consultaDNI($dataInput)->consultaDNIResult;
            //        } catch (Exception $e) {
            //            $resultados = $e->getMessage();
            //        }
            //         
            //        $dataCadena=json_decode($resultados,true);
            //        $AtributoData=$dataCadena["data"];
            //        $agencia_origen=$AtributoData[0][0][1];
            //        $agencia_destino=$AtributoData[0][1][1];
            //        $vehiculo_id=$AtributoData[0][2][1];
            //        $vehiculo_placa=$AtributoData[0][3][1];
            //        $fecha_salida=$AtributoData[0][4][1];       
            //        $chofer=$AtributoData[0][5][1];        
            //
            //
            //        $data=array();
            //        $data['agencia_origen'] = $agencia_origen;
            //        $data['$agencia_destino'] = $agencia_destino;
            //        $data['vehiculo_id'] = $vehiculo_id;
            //        $data['vehiculo_placa'] = $vehiculo_placa;
            //         $data['fecha_salida'] = $fecha_salida;
            //         $data['chofer'] = $chofer;
            // const CSRUC_URL = 'http://44.194.84.229/ConsultaSunatWS/ConsultaSunatWS.asmx?WSDL'; en configuraciones
            // return $data;
            return $dataInput;
        }
    }

    public function listarPaqueteDespacho($documentoId, $usuarioCreacion, $agencia_id)
    {

        $data = new ObjectUtil();
        $estado = 1;
        $tipo = 2;
        $response_perfil = Perfil::create()->obtnerPerfilAgenciaXUsuarioId($usuarioCreacion);
        $id_perfil = $response_perfil[0]['id'];

        $response_agencia = PerfilAgenciaCaja::create()->obtenerPerfilAgenciaCajaXPerfilId($id_perfil);
        $codigo_agencia = $response_agencia[0]['codigo'];
        $id_agencia = $response_agencia[0]['agencia_id'];
        $descripcion_agencia = $response_agencia[0]['agencia_descripcion'];

        $resultado = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);
        $vehiculoPlaca = $resultado[0]['placa'];
        $agenciaDestinoId = $agencia_id;

        $data->placaVehiculo = $vehiculoPlaca;
        $data->codigoAgencia = $codigo_agencia;
        $data->descripcionAgencia = $descripcion_agencia;
        $data->documentoId = $documentoId;
        $data->paquetesLeidos = Movimiento::create()->obtenerPaquetesxDocumentoManifiesto($documentoId, $agencia_id, $estado, $tipo);
        $data->paquetesPorLeer = Movimiento::create()->obtenerPaquetesxAgenciaManifiesto($agenciaDestinoId, $id_agencia);

        return $data;
    }

    public function listarPaqueteReparto($documentoId, $usuarioCreacion)
    {

        $data = new ObjectUtil();
        $estado = 1;
        $tipo = 5;
        $response_perfil = Perfil::create()->obtnerPerfilAgenciaXUsuarioId($usuarioCreacion);
        $id_perfil = $response_perfil[0]['id'];

        $response_agencia = PerfilAgenciaCaja::create()->obtenerPerfilAgenciaCajaXPerfilId($id_perfil);
        $codigo_agencia = $response_agencia[0]['codigo'];

        $descripcion_agencia = $response_agencia[0]['agencia_descripcion'];

        $resultado = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);
        $vehiculoPlaca = $resultado[0]['placa'];

        $data->placaVehiculo = $vehiculoPlaca;
        $data->codigoAgencia = $codigo_agencia;
        $data->descripcionAgencia = $descripcion_agencia;
        $data->documentoId = $documentoId;
        $data->paquetesAsignadosReparto = Movimiento::create()->obtenerPaquetesxDocumentoManifiestoReparto($documentoId, $estado, $tipo);

        return $data;
    }

    public function guardarReparto($documentoId, $usuarioCreacion)
    {

        $data = new ObjectUtil();
        $documento_estado = 1;
        Documento::create()->insertarDocumentoDocumentoEstado($documentoId, $documento_estado, $usuarioCreacion);
        Documento::create()->actualizarEstadoXDocumentoIdXEstado($documentoId, $documento_estado);

        $resultado = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);
        $serie_numero = $resultado[0]['serie_numero'];
        $data->documentoId = $documentoId;
        $data->serie_numero = $serie_numero;

        return $data;
    }

    public function guardarDespacho($documentoId, $usuarioCreacion)
    {

        $data = new ObjectUtil();
        $documento_estado = 1;
        $respuesta_documento = Documento::create()->obtenerDocumentoRelacionadoxDocumento($documentoId);
        foreach ($respuesta_documento as $value) {
            $documentoRelacionado = $value['documento_relacionado_id'];

            Documento::create()->insertarDocumentoDocumentoEstado($documentoRelacionado, $documento_estado, $usuarioCreacion);
            Documento::create()->actualizarEstadoXDocumentoIdXEstado($documentoRelacionado, $documento_estado);
        }

        Documento::create()->insertarDocumentoDocumentoEstado($documentoId, $documento_estado, $usuarioCreacion);
        Documento::create()->actualizarEstadoXDocumentoIdXEstado($documentoId, $documento_estado);
        $resultado = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);
        $serie_numero = $resultado[0]['serie_numero'];
        $data->documentoId = $documentoId;
        $data->serie_numero = $serie_numero;
        $this->enviarNotificacionDespacho($documentoId);
        return $data;
    }



    public function AbrirDespachoT($documentoId, $usuarioCreacion)
    {
        $dataDespacho = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);
        if (ObjectUtil::isEmpty($dataDespacho)) {
            throw new WarningException("No se encontró la información del despacho.");
        }

        if ($dataDespacho[0]['estado'] != 1) {
            throw new WarningException("El despacho se encuentra inactivo.");
        }

        if ($dataDespacho[0]['documento_estado_id'] != 14 && $dataDespacho[0]['documento_estado_id'] != 1) {
            throw new WarningException("El despacho no se encuentra en el estado generado.");
        }

        $serieNumeroDespacho = $dataDespacho[0]['serie_numero'];
        $vehiculoId = $dataDespacho[0]['vehiculo_id'];
        $agenciaId = $dataDespacho[0]['agencia_id'];

        $documentoEstadoId = 8;
        $tipo = 4;
        $respuestaRelacion = Documento::create()->obtenerDocumentoRelacionadoxDocumentoIdXTipo($documentoId, $tipo);
        if (ObjectUtil::isEmpty($respuestaRelacion)) {
            throw new WarningException("No se encontraron manifiestos relacionados.");
        }
        $dataManifiesto = Util::filtrarArrayPorColumna($respuestaRelacion, "documento_tipo_id", DespachoNegocio::DOCUMENTO_TIPO_MANIFIESTO_ID);
        if (ObjectUtil::isEmpty($respuestaRelacion)) {
            throw new WarningException("No se encontraron manifiestos relacionados.");
        }
        foreach ($dataManifiesto as $item) {
            if ($agenciaId == $item['agencia_id'] && $vehiculoId == $item['vehiculo_id']) {
                $manifiestoId = $item['documento_id'];
                $serieNumeroManifiesto = $item['serie_numero'];

                if ($item['documento_estado_id'] != 1) {
                    throw new WarningException("El manifiesto $serieNumeroManifiesto no se encuentra en el estado registrado");
                }

                if ((int) $item['cantidad_otro_tipo'] > 0) {
                    throw new WarningException("El manifiesto $serieNumeroManifiesto existen paquetes no se encuentran en el estado despachado, verifique no este relacionado a un recepción o entrega.");
                }
                Documento::create()->insertarDocumentoDocumentoEstado($manifiestoId, $documentoEstadoId, $usuarioCreacion);
            }
        }

        Documento::create()->insertarDocumentoDocumentoEstado($documentoId, $documentoEstadoId, $usuarioCreacion);
        $data = new stdClass();
        $data->documentoId = $documentoId;
        $data->serie_numero = $serieNumeroDespacho;
        return $data;
    }

    public function enviarNotificacionDespacho($documentoId)
    {

        $manifiestos = Documento::create()->obtenerDocumentoRelacionadoxDocumento($documentoId);

        foreach ($manifiestos as $doc) {

            $arrayPedidos = $this->obtenerArrayPedidosXDocumentoId($doc['documento_relacionado_id']);

            //Después de tener el "ArrayPedidos" listo, implementamos la lógica del envío de correos electrónicos.
            // $plantilla = EmailPlantillaNegocio::create()->obtenerEmailPlantillaXID(29);
            foreach ($arrayPedidos as $pedidoDespacho) {

                $datosDocPedido = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($pedidoDespacho['pedidoId']);

                $dataUsuario = Documento::create()->obtenerPersonaUsuario($datosDocPedido[0]['id']);

                if (!ObjectUtil::isEmpty($dataUsuario)) {

                    $token = $dataUsuario[0]['token'];
                    //                $mensaje = $item['mensaje'];
                    //                $titulo = $item['titulo']; // Título de la notificación
                    $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
                    $apiKey = 'AAAAaOEXqeM:APA91bEsf6uv_yCZ2AaCT8S_1-zzjAskIeYsLHJ7-NIBmSmpJPAh5jDcKlVrqf2g_xAKbe3rWK3gDPFvyxljcSJLUQUKhMyF83x5KfKR0PiNZRr3feSL5_5qrnP4i_iJ-ZNdrG39fz33';
                    //$apiKey = $item['api_token'];

                    $notification = [
                        'title' => "Paquete Despachado",
                        'body' => "Paquete enviado correctamente",
                        'tag' => $datosDocPedido[0]['id'], // $item['pedido_id'], //$pendientesEnvio[$i]['codigo_solicitud_creada'] . "&" . $pendientesEnvio[$i]['modulo'] . "&" . $pendientesEnvio[$i]['opcion'] . "&", 
                        'sound' => 'mySound',
                        'icon' =>  '@drawable/icon_notification',
                        'image' => 'https://www.ittsabus.com/resources/img/Banner30a%C3%B1os.jpg',
                        // 'image' => 'https://taypapp.com/img/logo.png',
                        'color' => '#E54750',
                        "priority" => "high"
                    ];

                    $extraNotificationData = [
                        "priority" => "high",
                        "message" => $notification,
                        "moredata" => $datosDocPedido[0]['id'],
                        "tipoNotificacion" => $datosDocPedido[0]['id'],
                        "tipoPedido" => $datosDocPedido[0]['id'],
                        'tag' => $datosDocPedido[0]['id'], //$pendientesEnvio[$i]['modulo'] . "&" . $pendientesEnvio[$i]['id_modulo'] . "&" . $pendientesEnvio[$i]['opcion'] . "&" . $pendientesEnvio[$i]['id_opcion'] . "&" . $pendientesEnvio[$i]['busqueda1'] . "&" . $pendientesEnvio[$i]['campo1'] . "&" . $pendientesEnvio[$i]['busqueda2'] . "&" . $pendientesEnvio[$i]['campo2'] . "&" . $pendientesEnvio[$i]['busqueda3'] . "&" . $pendientesEnvio[$i]['campo3'] . "&",
                    ];
                    $fcmNotification = [
                        'to' => $token,
                        'notification' => $notification,
                        'data' => $extraNotificationData,
                    ];
                    $headers = ['Authorization: key=' . $apiKey, 'Content-Type: application/json'];
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $fcmUrl);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
                    $result = curl_exec($ch);
                    curl_close($ch);
                }
            }
        }
    }
    public function guardarRecepcion($documentoId, $usuarioCreacion, $bandera)
    {

        $data = new ObjectUtil();
        $documento_estado = 1;
        $documento_estadoA = 9;
        $estado2 = 0;
        $documento_relacionado = Documento::create()->obtenerDocumentoRelacionado($documentoId, 1);
        $documentoId2 = $documento_relacionado[0]['documento_id'];
        $resultadoReparto = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId2);
        $agencia_destino = $resultadoReparto[0]['agencia_destino_id'];
        if ($agencia_destino = null) {
            $dataC = Movimiento::create()->obtenerPaquetesxDocumento2($documentoId2, $estado2);
        } else {
            $dataC = Movimiento::create()->obtenerPaquetesxDocumento($documentoId2, $estado2);
        }
        $cantidadPaquete = sizeof($dataC);
        if ($cantidadPaquete >= 1 && $bandera == 0) {
            $modal = new ObjectUtil();
            $bandera = 0;
            $mensaje = 'Faltan recepcionar ' . $cantidadPaquete . ' paquetes, ¿esta seguro de culminar la recepción?';
            $modal->bandera = $bandera;
            $modal->mensaje = $mensaje;
            return $modal;
        } else {
            Documento::create()->insertarDocumentoDocumentoEstado($documentoId, $documento_estado, $usuarioCreacion);
            Documento::create()->actualizarEstadoXDocumentoIdXEstado($documentoId, $documento_estado);

            Documento::create()->insertarDocumentoDocumentoEstado($documentoId2, $documento_estadoA, $usuarioCreacion);
            $resultado = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);
            $serie_numero = $resultado[0]['serie_numero'];
            $data->documentoId = $documentoId;
            $data->serie_numero = $serie_numero;

            //lógica de envio de correos
            $this->enviarCorreoRecepcionPedido($documentoId, $usuarioCreacion);

            return $data;
        }
    }

    public function enviarNotificacionRecepcion($documentoId, $usuarioId)
    {

        $datosRecepcion = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);

        $arrayPedidos = $this->obtenerArrayPedidosXDocumentoId($documentoId);

        //Después de tener el "ArrayPedidos" listo, implementamos la lógica del envío de correos electrónicos.
        //        $plantilla = EmailPlantillaNegocio::create()->obtenerEmailPlantillaXID(26);
        foreach ($arrayPedidos as $pedidoRecepcion) {

            $datosDocPedido = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($pedidoRecepcion['pedidoId']);


            $dataUsuario = Documento::create()->obtenerPersonaUsuario($datosDocPedido[0]['id']);

            if (!ObjectUtil::isEmpty($dataUsuario)) {

                $token = $dataUsuario[0]['token'];
                //                $mensaje = $item['mensaje'];
                //                $titulo = $item['titulo']; // Título de la notificación
                $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
                $apiKey = 'AAAAaOEXqeM:APA91bEsf6uv_yCZ2AaCT8S_1-zzjAskIeYsLHJ7-NIBmSmpJPAh5jDcKlVrqf2g_xAKbe3rWK3gDPFvyxljcSJLUQUKhMyF83x5KfKR0PiNZRr3feSL5_5qrnP4i_iJ-ZNdrG39fz33';
                //$apiKey = $item['api_token'];

                $notification = [
                    'title' => "Paquete Recepcionado",
                    'body' => "Paquete recepcionado correctamente",
                    'tag' => $datosDocPedido[0]['id'], // $item['pedido_id'], //$pendientesEnvio[$i]['codigo_solicitud_creada'] . "&" . $pendientesEnvio[$i]['modulo'] . "&" . $pendientesEnvio[$i]['opcion'] . "&", 
                    'sound' => 'mySound',
                    'icon' =>  '@drawable/icon_notification',
                    'image' => 'https://www.ittsabus.com/resources/img/Banner30a%C3%B1os.jpg',
                    // 'image' => 'https://taypapp.com/img/logo.png',
                    'color' => '#E54750',
                    "priority" => "high"
                ];

                $extraNotificationData = [
                    "priority" => "high",
                    "message" => $notification,
                    "moredata" => $datosDocPedido[0]['id'],
                    "tipoNotificacion" => $datosDocPedido[0]['id'],
                    "tipoPedido" => $datosDocPedido[0]['id'],
                    'tag' => $datosDocPedido[0]['id'], //$pendientesEnvio[$i]['modulo'] . "&" . $pendientesEnvio[$i]['id_modulo'] . "&" . $pendientesEnvio[$i]['opcion'] . "&" . $pendientesEnvio[$i]['id_opcion'] . "&" . $pendientesEnvio[$i]['busqueda1'] . "&" . $pendientesEnvio[$i]['campo1'] . "&" . $pendientesEnvio[$i]['busqueda2'] . "&" . $pendientesEnvio[$i]['campo2'] . "&" . $pendientesEnvio[$i]['busqueda3'] . "&" . $pendientesEnvio[$i]['campo3'] . "&",
                ];
                $fcmNotification = [
                    'to' => $token,
                    'notification' => $notification,
                    'data' => $extraNotificationData,
                ];
                $headers = ['Authorization: key=' . $apiKey, 'Content-Type: application/json'];
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $fcmUrl);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
                $result = curl_exec($ch);
                curl_close($ch);
            }
        }
    }

    function calcularDistanciaGoogleMaps($origen, $destino) {
        $googleApiKey = 'AIzaSyCZpBM_8d8SHaCyZtdYZv5ngPW6hBplRAE'; // Reemplaza con tu API Key de Google Maps
    
        // Formatea las coordenadas de origen y destino
        $origen = str_replace(' ', '+', $origen);
        $destino = str_replace(' ', '+', $destino);
    
        // Construye la URL para la solicitud de distancia
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins=$origen&destinations=$destino&key=$googleApiKey";
    
        // Realiza la solicitud a Google Maps
        $response = file_get_contents($url);
        $data = json_decode($response, true);
    
        if ($data['status'] == 'OK' && !empty($data['rows'][0]['elements'][0]['distance']['value'])) {
            return $data['rows'][0]['elements'][0]['distance']['value']; // La distancia se devuelve en metros
        } else {
            return null;
        }
    }

public function obtenerReparto($usuarioCreacion,$latitud,$longitud)
{

    $data = new ObjectUtil();
    $response_persona = Persona::create()->obtenerPersonaXUsuarioId($usuarioCreacion);
    $choferId = $response_persona[0]['id'];

    $response_perfil = Perfil::create()->obtnerPerfilAgenciaXUsuarioId($usuarioCreacion);
    $id_perfil = $response_perfil[0]['id'];

    $response_agencia = PerfilAgenciaCaja::create()->obtenerPerfilAgenciaCajaXPerfilId($id_perfil);
    $codigo_agencia = $response_agencia[0]['agencia_id'];

    $resultado = DocumentoNegocio::create()->obtenerDocumentoxReparto($codigo_agencia, $choferId);
    $documentoId = $resultado[0]['id'];
    $vehiculoId = $resultado[0]['vehiculo_id'];

    $data->documentoId = $documentoId;
    $data->vehiculoId = $vehiculoId;
    $data->choferId = $choferId;
    $paquetesPorRepartir = Movimiento::create()->obtenerPaquetesxRepartir($documentoId);
    $origen = $latitud.','.$longitud;   
    // Calcular distancias y agregarlas al array de destinos
    foreach ($paquetesPorRepartir as &$destino) {
        $kilometros= MovimientoNegocio::create()->calcularDistanciaGoogleMaps($origen, $destino['latitud'] . ',' . $destino['longitud']);
        if($kilometros==null){
            $kilometros=-1;
        }
        $destino['distancia'] =$kilometros;
    }
    // Ordenar el array por distancia
    usort($paquetesPorRepartir, function($a, $b) {
        return $a['distancia'] <=> $b['distancia'];
    });
    // El array de destinos ahora est� ordenado por distancia
    $data->paquetesPorRepartir=$paquetesPorRepartir;

    $data->paquetesEntregados = Movimiento::create()->obtenerPaquetesEntregados($documentoId);

    return $data;
}
  
    public function obtenerDetalleReparto($documentoId, $personaId, $direccionId)
    {

        $data = new stdClass();

        $data->documentoId = $documentoId;
        $data->detallepaquetesPorRepartir = Movimiento::create()->obtenerDetallePaquetesxRepartir($documentoId, $personaId, $direccionId);
        return $data;
    }

     public function registrarReparto($documentoId, $personaId, $direccionId, $usuarioCreacion, $choferId, $vehiculoId, $personaRecepcionNombre, $personaRecepcionDocumento, $documentoAdjunto,$numeroPedido)
    {
        $movimientoTipoId = 145;
        $estado = 1;
        $tipo = 3;
        $resultado_movimiento = Movimiento::create()->guardar($movimientoTipoId, $estado, $usuarioCreacion);
        $id_movimiento = $resultado_movimiento[0]['vout_id'];

        $correlativo = Documento::create()->obtenerNumeroAutoXDocumentoTipo(278, $serie = NULL);
        $dato_correlativo = $correlativo[0]['numero'];
        $resultado_documentoI = Documento::create()->InsertDocumentoConstanciaReparto($vehiculoId, $choferId, $personaId, $direccionId, $personaRecepcionNombre, $personaRecepcionDocumento, $usuarioCreacion, $id_movimiento, $dato_correlativo);
        $id_documento = $resultado_documentoI[0]['id'];

        Documento::create()->insertarDocumentoRelacionado($documentoId, $id_documento, $estado, $usuarioCreacion, $tipo);
        //registrar documentos adjuntos
        MovimientoNegocio::create()->insertDocumentoAdjunto($id_documento, $documentoAdjunto, $usuarioCreacion);
        
        $partes = explode('-', $numeroPedido);

        $serie = $partes[0];
        $numero = $partes[1];
        $respuesta_paquetes = Movimiento::create()->obtenerDetallePaquetesxRepartir($documentoId, $personaId, $direccionId,$serie,$numero);
        $cont = 0;
        foreach ($respuesta_paquetes as $value) {
            $codigo_paquete = $value['paquete_id'];
            $id_bien = $value['bien_id'];

            $respuesta_movimiento_bien = Movimiento::create()->InsertMovimientoBien($id_movimiento, $id_bien, $usuarioCreacion);
            $id_movimiento_bien = $respuesta_movimiento_bien[0]['id'];
            Movimiento::create()->UpdateTracking($codigo_paquete);
            Movimiento::create()->InsertTrackingEntregado($codigo_paquete, $id_movimiento_bien, $usuarioCreacion);
            $cont++;
            $respuesta_pedido = Movimiento::create()->UpdatePedidoTracking($codigo_paquete);
            $documentoPedidoId = $respuesta_pedido[0]['id'];
            $estadoPedido = $respuesta_pedido[0]['documento_estado_id'];
            $totalPedido = $respuesta_pedido[0]['pedido_total'];
            $entregadoPedido = $respuesta_pedido[0]['entregado_pedido'];

            if ($totalPedido == $entregadoPedido) {
                Movimiento::create()->InsertPedidoEstadoTracking($documentoPedidoId, 12, $usuarioCreacion);
            } else if ($totalPedido > $entregadoPedido && $estadoPedido != '11') {
                Movimiento::create()->InsertPedidoEstadoTracking($documentoPedidoId, 11, $usuarioCreacion);
            }
        }
        // Movimiento::create()->UpdateTracking($paqueteId);
        // $data = Movimiento::create()->InsertTrackingEntregado($paqueteId, $usuarioCreacion);
        return $id_documento;
    }

    public function cancelarReparto($documentoId, $usuarioCreacion, $personaId, $direccionId, $motivo, $numeroPedido)
    {
        $partes = explode('-', $numeroPedido);

        $serie = $partes[0];
        $numero = $partes[1];
        $respuesta_paquetes = Movimiento::create()->obtenerDetallePaquetesxRepartir($documentoId, $personaId, $direccionId,$serie,$numero);
        $cont = 0;
        foreach ($respuesta_paquetes as $value) {
            $codigo_paquete = $value['paquete_id'];
            Movimiento::create()->UpdateTrackingCancelarReparto($codigo_paquete, $motivo);
            $cont++;
        }
        // Movimiento::create()->UpdateTracking($paqueteId);
        // $data = Movimiento::create()->InsertTrackingEntregado($paqueteId, $usuarioCreacion);
        return $documentoId;
    }

    public function repartoNoEntregado($documentoId, $usuarioCreacion, $personaId, $direccionId, $motivo, $observacion,$numeroPedido)
    {   

        $partes = explode('-', $numeroPedido);

        $serie = $partes[0];
        $numero = $partes[1];
        $respuesta_paquetes = Movimiento::create()->obtenerDetallePaquetesxRepartir($documentoId, $personaId, $direccionId,$serie,$numero);
        $cont = 0;
        foreach ($respuesta_paquetes as $value) {
            $codigo_paquete = $value['paquete_id'];
            Movimiento::create()->UpdateTrackingrepartoNoEntregado($codigo_paquete, $motivo, $observacion);
            $cont++;
        }
        // Movimiento::create()->UpdateTracking($paqueteId);
        // $data = Movimiento::create()->InsertTrackingEntregado($paqueteId, $usuarioCreacion);
        return $documentoId;
    }   
       public function insertDocumentoAdjunto($id_documento, $documentoAdjunto, $usuarioCreacion)
    {

        $cont = 0;
        foreach ($documentoAdjunto as $values) {

            $imagen = $values['imagen'];
            $nombreImagen = $values['imagen'];
            if ($imagen != null) {
                Documento::create()->insertarDocumentoAdjuntoInterna($id_documento, $nombreImagen, $imagen, $usuarioCreacion);
            }
        }
        // Movimiento::create()->UpdateTracking($paqueteId);
        // $data = Movimiento::create()->InsertTrackingEntregado($paqueteId, $usuarioCreacion);    
    }

    public function obtenerTokenLoginIttsa()
    {
        try {
            $ch = curl_init();

            // Check if initialization had gone wrong*    
            if ($ch === false) {
                throw new WarningException('inicio de curl');
            }
            $url = 'https://www.ittsabus.com/suiteapirest/Usuario/Login';

            // Better to explicitly set URL
            curl_setopt($ch, CURLOPT_URL, $url);
            // That needs to be set; content will spill to STDOUT otherwise
            $array = ['UsuarioName' => '47046044', 'UsuarioPassword' => '47046044'];
            $data = json_encode($array);
            /* pass encoded JSON string to the POST fields */
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $headers = [];
            $headers[] = 'Content-Type:application/json';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // Set more options
            //curl_setopt(/* ... */);

            $content = curl_exec($ch);

            // Check the return value of curl_exec(), too
            if ($content === false) {
                throw new WarningException(curl_error($ch), curl_errno($ch));
            }

            // Check HTTP return code, too; might be something else than 200
            $httpReturnCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            $login = json_decode($content, true);
            $token = $login['token'];

            return $token;
            /* Process $content here */
        } catch (Exception $e) {

            throw new WarningException(
                sprintf(
                    'Error al intentar obtener el token #%d: %s',
                    $e->getCode(),
                    $e->getMessage()
                )
            );
        } finally {

            if (is_resource($ch)) {
                curl_close($ch);
            }
        }
    }



    public function obtenerAgenciasIttsa($fecha, $usuarioCreacion, $placa = NULL)
    {

        $response_perfil = Perfil::create()->obtnerPerfilAgenciaXUsuarioId($usuarioCreacion);
        $id_perfil = $response_perfil[0]['id'];

        $response_agencia = PerfilAgenciaCaja::create()->obtenerPerfilAgenciaCajaXPerfilId($id_perfil);
        $integracion_agencia_id = $response_agencia[0]['integracion_agencia_id'];

        $token = self::obtenerTokenLoginIttsa();


        try {
            $ch = curl_init();

            // Check if initialization had gone wrong*    
            if ($ch === false) {
                throw new WarningException('inicio de curl');
            }
            //$fecha = '2023-01-12';
            $url = 'https://www.ittsabus.com/suiteapirest/Itinerario/Cargo/Listar/Fecha/' . $fecha;

            // Better to explicitly set URL
            curl_setopt($ch, CURLOPT_URL, $url);

            $headers = array(
                "Content-Type: application/json",
                "Authorization: Bearer " . $token
            );
            $headers[] = 'Content-Type:application/json';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // Set more options
            //curl_setopt(/* ... */);

            $content = curl_exec($ch);

            // Check the return value of curl_exec(), too
            if ($content === false) {
                throw new WarningException(curl_error($ch), curl_errno($ch));
            }

            // Check HTTP return code, too; might be something else than 200
            $httpReturnCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            $respuestaItinerario = json_decode($content, true);

            /* Process $content here */
        } catch (Exception $e) {

            throw new WarningException(
                sprintf(
                    'Error al intentar obtener el token #%d: %s',
                    $e->getCode(),
                    $e->getMessage()
                )
            );
        } finally {

            if (is_resource($ch)) {
                curl_close($ch);
            }
        }


        $dataItinerarios = $respuestaItinerario['listaItinerarios'];
        $dataItinerariosFiltrado = array();
        if (!ObjectUtil::isEmpty($dataItinerarios)) {
            $dataItinerariosFiltrado = Util::filtrarArrayPorColumna($dataItinerarios, "origenId", $integracion_agencia_id);

            //SI RECIVE EL CAMPO PLACA CONSULTA POR LA PLACA DEL BUS
            if (!ObjectUtil::isEmpty($placa)) {
                $dataItinerariosFiltrado = Util::filtrarArrayPorColumna($dataItinerariosFiltrado, "flotaPlaca", $placa);
            }
            if (!ObjectUtil::isEmpty($dataItinerariosFiltrado)) {
                foreach ($dataItinerariosFiltrado as $index => $item) {
                    if (!ObjectUtil::isEmpty($item['destinoId'])) {
                        $dataAgenciaDestino = AgenciaNegocio::create()->obtenerAgenciaxIdIntegracion($item['destinoId']);
                        if (!ObjectUtil::isEmpty($dataAgenciaDestino)) {
                            $dataItinerariosFiltrado[$index]['agencia_destino_id'] = $dataAgenciaDestino[0]['id'];
                        }
                    }

                    if (!ObjectUtil::isEmpty($item['flotaPlaca']) &&  $item['flotaPlaca'] != "-") {
                        $dataVehiculo = Vehiculo::create()->obtenerVehiculoxPlaca($item['flotaPlaca']);
                        if (!ObjectUtil::isEmpty($dataVehiculo)) {
                            $dataItinerariosFiltrado[$index]['vehiculo_id'] = $dataVehiculo[0]['id'];
                        }
                    }

                    if (!ObjectUtil::isEmpty($item['tripulación'])) {
                        foreach ($item['tripulación'] as $valueIndex => $value) {
                            $pilotoDocumento = $value['pilotoDocumento'];
                            $pilotoLicencia = $value['pilotoLicencia'];
                            $pilotoNombre = $value['pilotoNombre'];
                            $pilotoApellidos = $value['pilotoApellidos'];
                            $apellidos = explode(" ", $pilotoApellidos);
                            $paterno = $apellidos[0];
                            $materno = $apellidos[1];
                            //$pilotoCargo = $value['pilotoCargo'];
                            //$pilotoDireccion = $value['pilotoDireccion'];
                            $pilotoCelular = $value['pilotoCelular'];
                            $resultado = Persona::create()->insertPersonaConductor(
                                $pilotoDocumento,
                                $pilotoLicencia,
                                $pilotoNombre,
                                $paterno,
                                $materno,
                                $pilotoCelular
                            );
                            if ($resultado[0]['vout_exito'] == 1) {
                                $id = $resultado[0]['id'];
                                Persona::create()->insertPersonaConductorClase($id);
                                $dataItinerariosFiltrado[$index]['tripulación'][$valueIndex]['chofer_id'] = $id;
                            }
                        }
                    }
                }
            }
        }

        return $dataItinerariosFiltrado;
    }

    public function consultarDocumento($tipoDocumento, $numeroDocumento, $usuarioCreacion)
    {

        $response_persona = Persona::create()->getPersonaCodigo($numeroDocumento, $tipoDocumento);
        $id_persona = $response_persona[0]['datos_persona'];

        if (!ObjectUtil::isEmpty($id_persona)) {
            $datos = new ObjectUtil();
            $datos->tipoDocumento = $tipoDocumento;
            $datos->numeroDocumento = $numeroDocumento;
            $datos->nombrePersona = $id_persona;
        }

        //ITINERARIO DE BUSES
        /* API URL */ else {
            $url = 'https://www.ittsabus.com/suiteapirest/Usuario/Login';

            /* Init cURL resource */
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            /* Array Parameter Data */
            $array = ['UsuarioName' => '47046044', 'UsuarioPassword' => '47046044'];
            $data = json_encode($array);
            /* pass encoded JSON string to the POST fields */
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

            /* set the content type json */
            $headers = [];
            $headers[] = 'Content-Type:application/json';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            /* set return type json */
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            /* execute request */
            $result2 = curl_exec($ch);

            /* close cURL resource */
            curl_close($ch);

            $login = json_decode($result2, true);
            $token = $login['token'];
            //ITINERARIO DE BUSES
            /* API URL */
            $url = 'https://www.ittsabus.com/suiteapirest/Cliente/Listar/' . $tipoDocumento . '/' . $numeroDocumento;
            //        $url = 'https://www.ittsabus.com/suiteapirest/Itinerario/Cargo/Listar/Fecha/' . $fecha;
            /* Init cURL resource */
            $ch = curl_init();
            /* Array Parameter Data */
            //    $data = ['name'=>'Hardik', 'email'=>'itsolutionstuff@gmail.com'];

            /* pass encoded JSON string to the POST fields */
            //    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

            /* set the content type json */
            //    $headers = [];
            //    $headers[] = 'Content-Type: application/json';
            //    $headers[] = "Authorization: Bearer ".$token;
            $headers = array(
                "Content-Type: application/json",
                "Authorization: Bearer " . $token
            );

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            /* set return type json */

            /* execute request */
            $result = curl_exec($ch);

            /* close cURL resource */
            curl_close($ch);

            $a = json_decode($result, true);
            $clienteInfo = $a['clienteInfo'];
            $datos = new ObjectUtil();
            $datos->tipoDocumento = $clienteInfo['tipoDocumentoId'];
            $datos->numeroDocumento = $clienteInfo['clienteDocumento'];
            $datos->nombrePersona = $clienteInfo['clienteNombre'] . ' ' . $clienteInfo['clienteApellidoPaterno'] . ' ' . $clienteInfo['clienteApellidoMaterno'];
        }

        return $datos;
    }

    public function envioDirectoNotificacion($documentoId, $titulo, $mensaje, $tipoPedido = null, $tipoNotificacion = null)
    {

        $dataUsuario = Documento::create()->obtenerPersonaUsuario($documentoId);

        if (!ObjectUtil::isEmpty($dataUsuario)) {


            $token = $dataUsuario[0]['token'];
            //                $mensaje = $item['mensaje'];
            //                $titulo = $item['titulo']; // Título de la notificación
            $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
            $apiKey = 'AAAAaOEXqeM:APA91bEsf6uv_yCZ2AaCT8S_1-zzjAskIeYsLHJ7-NIBmSmpJPAh5jDcKlVrqf2g_xAKbe3rWK3gDPFvyxljcSJLUQUKhMyF83x5KfKR0PiNZRr3feSL5_5qrnP4i_iJ-ZNdrG39fz33';
            //$apiKey = $item['api_token'];

            $notification = [
                'title' => $titulo,
                'body' => $mensaje,
                'tag' => $pedidoId, // $item['pedido_id'], //$pendientesEnvio[$i]['codigo_solicitud_creada'] . "&" . $pendientesEnvio[$i]['modulo'] . "&" . $pendientesEnvio[$i]['opcion'] . "&", 
                'sound' => 'mySound',
                'icon' =>  '@drawable/icon_notification',
                'image' => 'https://www.ittsabus.com/resources/img/Banner30a%C3%B1os.jpg',
                // 'image' => 'https://taypapp.com/img/logo.png',
                'color' => '#E54750',
                "priority" => "high"
            ];

            $extraNotificationData = [
                "priority" => "high",
                "message" => $notification,
                "moredata" => $pedidoId,
                "tipoNotificacion" => $tipoNotificacion,
                "tipoPedido" => $tipoPedido,
                'tag' => $documentoId, //$pendientesEnvio[$i]['modulo'] . "&" . $pendientesEnvio[$i]['id_modulo'] . "&" . $pendientesEnvio[$i]['opcion'] . "&" . $pendientesEnvio[$i]['id_opcion'] . "&" . $pendientesEnvio[$i]['busqueda1'] . "&" . $pendientesEnvio[$i]['campo1'] . "&" . $pendientesEnvio[$i]['busqueda2'] . "&" . $pendientesEnvio[$i]['campo2'] . "&" . $pendientesEnvio[$i]['busqueda3'] . "&" . $pendientesEnvio[$i]['campo3'] . "&",
            ];
            $fcmNotification = [
                'to' => $token,
                'notification' => $notification,
                'data' => $extraNotificationData,
            ];
            $headers = ['Authorization: key=' . $apiKey, 'Content-Type: application/json'];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $fcmUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
            $result = curl_exec($ch);
            curl_close($ch);
        }
        return 1;
    }

    public function probandoAgenciasIttsa($fecha, $usuarioCreacion)
    {
        $response_perfil = Perfil::create()->obtnerPerfilAgenciaXUsuarioId($usuarioCreacion);
        $id_perfil = $response_perfil[0]['id'];
        $response_agencia = PerfilAgenciaCaja::create()->obtenerPerfilAgenciaCajaXPerfilId($id_perfil);
        $integracion_agencia_id = $response_agencia[0]['integracion_agencia_id'];
        $url = 'https://www.ittsabus.com/suiteapirest/Usuario/Login';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $array = ['UsuarioName' => '47046044', 'UsuarioPassword' => '47046044'];
        $data = json_encode($array);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $headers = [];
        $headers[] = 'Content-Type:application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result2 = curl_exec($ch);
        curl_close($ch);
        $login = json_decode($result2, true);
        $token = $login['token'];
        //ITINERARIO DE BUSES
        $url = 'https://www.ittsabus.com/suiteapirest/Itinerario/Cargo/Listar/Fecha/' . $fecha;
        $ch = curl_init();
        $headers = array(
            "Content-Type: application/json",
            "Authorization: Bearer " . $token
        );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        curl_close($ch);
        $a = json_decode($result, true);
        $itinerarios = $a['listaItinerarios'];
        $data = array();
        $manifiesto = array();
        $c = 0;
        $m = 0;
        foreach ($itinerarios as $value) {
            $ba = $a['listaItinerarios'][$c]['origenId'];
            if ($ba == $integracion_agencia_id) {
                $ca = $a['listaItinerarios'][$c];
                array_push($data, $ca);
            } else {
            }
            $c++;
        }

        $fechaActual = date('18:00:00');
        foreach ($data as $value) {
            $ba = $value['itinerarioHoraSalida'];
            $fechaSistema = date("H:i:s", strtotime($ba));
            if ($fechaSistema > $fechaActual) {
                $ca = $data[$m];
                array_push($manifiesto, $ca);
            } else {
                $fechaActual = $fechaActual;
            }
            $m++;
        }
        return $manifiesto[0];
    }

    public function obtenerConfiguracionesInicialesManifiestoReparto($opcionId, $empresaId, $usuarioId)
    {

        $data = new stdClass();
        $data->choferes = Agencia::create()->getDataConductores();
        $data->bus = Vehiculo::create()->getDataVehiculo();

        $data->acciones = MovimientoNegocio::create()->obtenerMovimientoTipoAcciones($opcionId);
        $movimientoTipoData = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);
        $data->estadoNegocioPago = MovimientoTipoNegocio::create()->obtenerDocumentoEstadoXId($movimientoTipoData[0]['id']);

        $data->dataAgenciaUsuario = PerfilAgenciaCajaNegocio::create()->obtenerPerfilAgenciaXUsuarioId($usuarioId);

        foreach ($data->acciones as $index => $accion) {
            $data->acciones[$index]['mostrarAccion'] = 1;
        }
        return $data;
    }

    public function obtenerConfiguracionesInicialesDespacho_($usuarioId)
    {

        $data = new ObjectUtil();
        $data->bus = VehiculoNegocio::create()->getDataVehiculo();
        // $data->conductores = MovimientoNegocio::create()->obtenerMovimientoTipoAcciones($opcionId) FALTA ;
        // $data->manifiesto = Movimiento::create()->getManifiestos();
        $data->agenciaUser = Agencia::create()->getDataAgenciaUser($usuarioId);
        $data->origen = Agencia::create()->getDataAgencia();
        $data->choferes = Agencia::create()->getDataConductores();
        $data->manifiesto = Movimiento::create()->getDespachos(null, null, null, null, null, null, null, null, null);
        // $data->table = Movimiento::create()->getDespachos($bus_id,$fecha_salida,$conductor_id,$nro_manifiesto,$this->agenciaUser[2],$destino_id, $estado_id);
        // $data->destino = Movimiento::create()->obtenerActivas();
        // $data->estado = Movimiento::create()->obtenerPerfilAgenciaXUsuarioId();
        return $data;
    }

    public function obtenerConfiguracionesInicialesDespacho($opcionId, $empresaId, $usuarioId)
    {

        $data = new stdClass();
        $data->choferes = Agencia::create()->getDataConductores();
        $data->bus = Vehiculo::create()->getDataVehiculo();

        $data->acciones = MovimientoNegocio::create()->obtenerMovimientoTipoAcciones($opcionId, "DM");
        $movimientoTipoData = MovimientoTipoNegocio::create()->obtenerXOpcion($opcionId);
        $data->estadoNegocioPago = MovimientoTipoNegocio::create()->obtenerDocumentoEstadoXId($movimientoTipoData[0]['id']);

        $data->dataAgenciaUsuario = PerfilAgenciaCajaNegocio::create()->obtenerPerfilAgenciaXUsuarioId($usuarioId);

        $data->dataAgencia = AgenciaNegocio::create()->getDataAgencia();

        $data->dataUsuario = UsuarioNegocio::create()->getDataUsuario();
        $dataAperturaCaja = ACCajaNegocio::create()->obteneACCajaUltimoXUsuarioId($empresaId, $usuarioId, $data->dataAgenciaUsuario[0]['id']);
        $cajaDefault = NULL;
        if (!ObjectUtil::isEmpty($dataAperturaCaja)) {
            $cajaDefault = $dataAperturaCaja[0]['caja_id'];
        }
        $data->cajaDefault = $cajaDefault;
        $agenciasId = implode(',', array_map(function ($item) {
            return $item['id'];
        }, $data->dataAgenciaUsuario));

        $banderaTodasCajas = FALSE;
        $dataPerfil = PerfilNegocio::create()->obtenerPerfilXUsuarioId($usuarioId);
        foreach ($dataPerfil as $itemPerfil) {
            if ($itemPerfil['id'] == PerfilNegocio::PERFIL_ADMINISTRADOR_ID || $itemPerfil['id'] == PerfilNegocio::PERFIL_ADMINISTRADOR_TI_ID) {
                $banderaTodasCajas = TRUE;
            }
        }


        $data->dataCajaUsuario = ($banderaTodasCajas ? PerfilAgenciaCajaNegocio::create()->obtenerCajaXAgenciaId($agenciasId) : PerfilAgenciaCajaNegocio::create()->obtenerPerfilAgenciaCajaXUsuarioId($usuarioId));
        //        $data->acciones = MovimientoNegocio::create()->obtenerMovimientoTipoAcciones($opcionId);
        foreach ($data->acciones as $index => $accion) {
            $data->acciones[$index]['mostrarAccion'] = 1;
        }
        return $data;
    }

    public function obtenertablaDespacho($bus_id, $fecha_salida, $conductor_id, $copiloto_id, $nro_manifiesto, $origin_id, $destino_id, $estado_id, $nro_despacho)
    {

        $nro_manifi = null;
        if (!ObjectUtil::isEmpty($nro_manifiesto)) {
            $nro_manifi = strtoupper($nro_manifiesto);
        }
        $nro_despa = null;
        if (!ObjectUtil::isEmpty($nro_despacho)) {
            $nro_despa = strtoupper($nro_despacho);
        }
        $data = Movimiento::create()->getDespachos($bus_id, $fecha_salida, $conductor_id, $copiloto_id, $nro_manifi, $origin_id, $destino_id, $estado_id, $nro_despa);

        return $data;
    }

    public function obtenerDocumentosDespachoXCriterios($bus_id, $fecha_salida, $conductor_id, $copiloto_id, $nro_manifiesto, $origin_id, $destino_id, $estado_id, $nro_despacho, $bandera_devolucion = null, $elemntosFiltrados, $columns, $order, $start)
    {

        $nro_manifi = null;
        if (!ObjectUtil::isEmpty($nro_manifiesto)) {
            $nro_manifi = strtoupper($nro_manifiesto);
        }
        $nro_despa = null;
        if (!ObjectUtil::isEmpty($nro_despacho)) {
            $nro_despa = strtoupper($nro_despacho);
        }

        if (!ObjectUtil::isEmpty($estado_id)) {
            $estado_id = implode(",", $estado_id);
        }

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];

        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];
        return Movimiento::create()->obtenerDocumentosDespachoXCriterios(
            $bus_id,
            $fecha_salida,
            $conductor_id,
            $copiloto_id,
            $nro_manifi,
            $origin_id,
            $destino_id,
            $estado_id,
            $nro_despa,
            $bandera_devolucion,
            $columnaOrdenar,
            $formaOrdenar,
            $elemntosFiltrados,
            $start
        );
    }

    public function obtenerDocumentosManifiestoRepartoXCriterios($bus_id, $fecha_salida, $conductor_id, $nro_manifiesto, $origin_id, $estado_id, $elemntosFiltrados, $columns, $order, $start)
    {

        $nro_manifi = null;
        if (!ObjectUtil::isEmpty($nro_manifiesto)) {
            $nro_manifi = strtoupper($nro_manifiesto);
        }

        if (!ObjectUtil::isEmpty($estado_id)) {
            $estado_id = implode(",", $estado_id);
        }

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];

        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];
        return Movimiento::create()->obtenerDocumentosManifiestoRepartoXCriterios(
            $bus_id,
            $fecha_salida,
            $conductor_id,
            $nro_manifi,
            $origin_id,
            $estado_id,
            $columnaOrdenar,
            $formaOrdenar,
            $elemntosFiltrados,
            $start
        );
    }

    public function obtenerConfiguracionesInicialesManifiesto($despachoId, $empresaId, $usuarioId)
    {
        $data = new ObjectUtil();
        $data->bus = Vehiculo::create()->getDataVehiculo();
        $data->origen = Agencia::create()->getDataAgencia();
        $data->table = Movimiento::create()->obtenerDespachoDetalle($despachoId);
        $data->periodo = PeriodoNegocio::create()->obtenerPeriodoAbiertoXEmpresa($empresaId);
        $data->choferes = PersonaNegocio::create()->TraerConductores();
        $data->dataCorrelativo = PedidoNegocio::create()->obtenerCorrelativoGuia(NULL, $data->table[0]['agencia_id'], DocumentoTipoNegocio::IN_BOLETA_VENTA);
        return $data;
    }

    public function filtrarManifiestos($despacho_id, $destino_id, $manifiestos)
    {
        $data = new ObjectUtil();
        $data->table = Movimiento::create()->getManifiesto($despacho_id, $destino_id, $manifiestos);
        $data->guias = Movimiento::create()->getGuiasRT($despacho_id);
        return $data;
    }

    public function obtenerConfiguracionesInicialesManifiestoRepartoDetalle($manifiestoId, $empresaId, $usuarioId)
    {

        $data = new ObjectUtil();
        $data->bus = Vehiculo::create()->getDataVehiculo();
        $data->periodo = PeriodoNegocio::create()->obtenerPeriodoAbiertoXEmpresa($empresaId);
        $data->choferes = PersonaNegocio::create()->TraerConductores();
        $data->table = Movimiento::create()->obtenerDetalleManifiesto($manifiestoId);
        $data->dataCorrelativo = PedidoNegocio::create()->obtenerCorrelativoGuia(NULL, $data->table[0]['agencia_id'], DocumentoTipoNegocio::IN_BOLETA_VENTA);

        return $data;
    }

    public function getManifiesto($despacho_id)
    {

        return Movimiento::create()->getManifiesto($despacho_id, null, null);
    }

    public function getManifiestoReparto($manifiesto_id)
    {

        return Movimiento::create()->getManifiestoReparto($manifiesto_id);
    }

    public function getDepartamento($ubigeo_id)
    {
        return Movimiento::create()->getDepartamento($ubigeo_id);
    }

    public function verificar($idDespacho)
    {

        return Movimiento::create()->verificar($idDespacho);
    }

    public function verificarManifiestoReparto($idDespacho)
    {

        $respuesta = Movimiento::create()->verificarManifiestoReparto($idDespacho);

        return $respuesta;
    }

    public function insertGuiaRT($idDespacho, $movimiento_id, $serie, $numero, $moneda_id, $usuario_creacion, $agencia_id, $agencia_destino_id, $vehiculo_id, $persona_origen, $persona_destino_id, $piloto, $copiloto, $periodo, $fecha)
    {

        return Movimiento::create()->insertGuiaRT($idDespacho, $movimiento_id, $serie, $numero, $moneda_id, $usuario_creacion, $agencia_id, $agencia_destino_id, $vehiculo_id, $persona_origen, $persona_destino_id, $piloto, $copiloto, $periodo, $fecha);
    }

    public function insertGuiaRTM($idDespacho, $serie, $numero, $moneda_id, $usuario_creacion, $agencia_id, $agencia_destino_id, $vehiculo_id)
    {

        return Movimiento::create()->insertGuiaRTM($idDespacho, $serie, $numero, $moneda_id, $usuario_creacion, $agencia_id, $agencia_destino_id, $vehiculo_id);
    }

    //Cambios Cristopher
    public function PDFManifiestoXDespachoId($idDespacho, $vehiculoId, $choferId, $copilotoId)
    {

        require_once __DIR__ . '/../../controlador/commons/TCPDF-main/tcpdf.php';

        $datosDocumentoTotal = Movimiento::create()->obtenerDespachoDetalle($idDespacho);
        $despacho = $datosDocumentoTotal[0]['despacho_serie_numero'];
        $Getconductor = PersonaNegocio::create()->TraerConductor($choferId);
        $Getcopiloto = PersonaNegocio::create()->TraerConductor($copilotoId);
        $Getbus = VehiculoNegocio::create()->getVehiculo($vehiculoId);

        $busNumero = $Getbus[0]["flota_numero"];
        $busPlaca = $Getbus[0]["flota_placa"];
        $conductor = $Getconductor[0]["conductor"];
        $copiloto = $Getcopiloto[0]["conductor"];

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->startPageGroup();

        $dataManifiesto = array_merge(array_unique(array_map(function ($item) {
            return $item['manifiesto_id'];
        }, $datosDocumentoTotal)));

        //38226
        foreach ($dataManifiesto as $manifiestoId) {
            $pdf->AddPage();
            $dataManifiestoDetalle = ObjectUtil::filtrarArrayPorColumna($datosDocumentoTotal, 'manifiesto_id', $manifiestoId);
            $fechaSalida = $dataManifiestoDetalle[0]['fecha_salida'];
            //    $horaSalida = date("g:i a", strtotime(substr($fechaSalida, -8)));
            $horaSalida = date("h:i A", strtotime($fechaSalida));
            $manifiestoCabecera = '
            <table style="font-size:9px; padding:5px 0px;">        
                        <tr>
                            <td style="font-size:12px;color:#333; background-color:white; text-align:center">
                              INTERNACIONAL DE TRANSPORTE TURISTICO Y SERVICIOS S.R.L
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size:12px;color:#333; background-color:white; text-align:center">
                              MANIFIESTO DE ENCOMIENDAS
                            </td>
                        </tr>
                        <tr>
                            <td style="color:#333; background-color:white; width:50%;text-align:left">
                              Oficina Origen: ' . $dataManifiestoDetalle[0]['agencia_origen'] . '
                            </td>
                            <td style="color:#333; background-color:white; width:50%;text-align:left">
                                Turno: ' . $horaSalida . '
                            </td>
                        </tr>
                        <tr>
                            <td style="color:#333; background-color:white; width:50%;text-align:left">
                                Oficina Destino: ' . $dataManifiestoDetalle[0]['agencia_destino'] . '
                            </td>
                            <td style="color:#333; background-color:white; width:50%;text-align:left">
                                Fecha de Envio:  ' . DateUtil::formatearFechaBDAaCadenaVw($fechaSalida) . '
                            </td>
                        </tr>
                        <tr>
                            <td style="color:#333; background-color:white; width:10%;text-align:left">
                              Bus: ' . $busNumero . '
                            </td>
                            <td style="color:#333; background-color:white; width:14%;text-align:left">
                            Placa: ' . $busPlaca . '
                          </td>
                          <td style="color:#333; background-color:white; width:38%;text-align:left">
                              Piloto: ' . $conductor . '
                            </td>
                            <td style="color:#333; background-color:white; width:38%;text-align:left">
                            Copiloto: ' . $copiloto . '
                          </td> 
                         </tr>
            </table>  ';

            $pdf->writeHTML($manifiestoCabecera, false, false, false, false, '');

            $tableDetalleTotal = '';
            foreach (array(
                array("comprobante_tipo_id" => 6, "descripcion" => "BOLETA"),
                array("comprobante_tipo_id" => 7, "descripcion" => "FACTURA"),
                array("comprobante_tipo_id" => 191, "descripcion" => "NOTA DE VENTA")
            ) as $comprobante) {

                $dataDetalle = ObjectUtil::filtrarArrayPorColumna($dataManifiestoDetalle, 'factura_documento_tipo_id', $comprobante['comprobante_tipo_id']);

                $dataDetalleCount = array_unique(array_map(function ($item) {
                    return $item['factura_id'];
                }, $dataDetalle));

                $totalItems = 0;
                $totalMonto = 0;
                $tableDetalle = '';

                //Cambios Cristopher
                $contador = 0;
                $facturaActual = $dataDetalle[0]["factura_serie_numero"];

                foreach ($dataDetalle as $item) {
                    if($facturaActual == $item["factura_serie_numero"]){
                        $contador += 1;
                    } else {
                        $contador = 1;
                        $facturaActual = $item["factura_serie_numero"];
                    }

                    if ($item["modalidad_id"] == 75) {
                        $modalidadDescipcion = 'PC/ENTREGA';
                    } else {
                        $modalidadDescipcion = '';
                    }
                    if ($item["modalidad_id"] == 77) {
                        $direccionDestino = 'OFICINA';
                    } else {
                        $lengthCaracter = $pdf->GetStringWidth(mb_strtoupper($item["persona_direccion_destino"], 'UTF-8'));
                        if ($lengthCaracter < 86) {
                            $direccionDestino = mb_strtoupper($item["persona_direccion_destino"], 'UTF-8');
                        } else {
                            $direccionDestino = substr(mb_strtoupper($item["persona_direccion_destino"], 'UTF-8'), 0, 50);
                        }
                    }

                    $lengthCaracterDestinatario = $pdf->GetStringWidth(mb_strtoupper($item["receptor_nombre"], 'UTF-8'));
                    if ($lengthCaracterDestinatario < 80) {
                        $destinatario = mb_strtoupper($item["receptor_nombre"], 'UTF-8');
                    } else {
                        $destinatario = substr(mb_strtoupper($item["receptor_nombre"], 'UTF-8'), 0, 50);
                    }

                    $bienDescripcion = str_replace(array('á', 'é', 'í', 'ó', 'ú'), array('a', 'e', 'i', 'o', 'u'), substr($item["bien_descripcion"], 0, 13));
                    $bienDescripcion = substr(mb_strtoupper($bienDescripcion, 'UTF-8'), 0, 13);
                    $direccionDestino = mb_strtoupper(str_replace(array('ñ','á', 'é', 'í', 'ó', 'ú'), array('n','a', 'e', 'i', 'o', 'u'), strtolower($direccionDestino)));

                    $cantidad = number_format($item["manifiesto_cantidad"], 2);
                    $precio = number_format($item["valor_monetario"], 2);
                    $totalItems = ($totalItems + $cantidad);
                    $total = ($cantidad * $precio);
                    $totalMonto = $totalMonto + strval($total);
                    $tableDetalle .= '<tr>
                                        <td style=" background-color:white; width:10%; text-align:left">' . (($contador == 1) ?$item["guia"] . "<br>"  :"") . $item["factura_serie_numero"] . '</td>
                                        <td style=" background-color:white; width:27%; text-align:left">' . $destinatario . '</td>
                                        <td style=" background-color:white; width:30%; text-align:left">' . $direccionDestino . '</td>
                                        <td style=" background-color:white; width:11%; text-align:right">' . $modalidadDescipcion . '</td>
                                        <td style=" background-color:white; width:14%; text-align:left"> ' . intval($item["manifiesto_cantidad"]) . ' ' . $bienDescripcion . ' </td>
                                        <td style=" background-color:white; width:8%; text-align:center">' . number_format($total, 2) . '</td>
                                    </tr>';
                }

                if (count($dataDetalleCount) > 0) {
                    $tableDetalleTotal .= '<table style="font-size:7.5px;padding:2px 0px;">
                                    <tr>
                                        <td style="color:#333; background-color:white; text-align:left">
                                            <b>' . $comprobante['descripcion'] . '</b>
                                        </td>
                                    </tr>
                                    <br>
                                    <tr>
                                        <td style="color:#333; background-color:white; text-align:left">
                                            ENCOMIENDAS
                                        </td>
                                    </tr>	
                                    <table style="font-size:8px; padding:5px 0px;">
                                    <tr>        
                                        <td style=" background-color:white; width:10%; text-align:left">' . $comprobante['descripcion'] . '</td>
                                        <td style=" background-color:white; width:27%; text-align:left">CONSIGNATARIO</td>
                                        <td style=" background-color:white; width:30%; text-align:left">DIRECCION</td>
                                        <td style=" background-color:white; width:11%; text-align:center"></td>
                                        <td style=" background-color:white; width:14%; text-align:left">DESCRIPCION</td>
                                        <td style=" background-color:white; width:8%; text-align:center">IMPORTE</td>
                                    </tr>    
                                    ' . $tableDetalle . '
                                    </table >
                                    <br>
                                    <tr>
                                        <td style="color:#333; background-color:white; width:60%; text-align:left;font-size:9px">
                                                Total de ' . $comprobante['descripcion'] . ': ' . count($dataDetalleCount) . '
                                        </td>
                                        <td style="color:#333; background-color:white;width:26%; text-align:left;font-size:9px">
                                                Total de Encomiendas: ' . (int) $totalItems . '
                                        </td>
                                        <td style="color:#333; background-color:white; width:14%;text-align:left;font-size:9px">
                                                Total: ' . number_format($totalMonto, 2) . '
                                        </td>
                                    </tr>
                                    </table>
                                    <br>';
                }
            }

            $tableDetalleTotal = ' <div style="font-size:4px; text-align:center;">  &nbsp; </div> ' . $tableDetalleTotal;
            // if ($manifiestoId == 38226) {
            //     echo $tableDetalleTotal;
            // }
            $pdf->writeHTML($tableDetalleTotal, false, false, false, false, '');
        }

        $pdf_title = $despacho . '.pdf';
        $url = __DIR__ . '/../../vistas/com/movimiento/documentos/' . $pdf_title;
        ob_clean();
        $pdf->Output($url, 'F');
        return $pdf_title;
    }

    //Cambios Cristopher
    public function PDFManifiesto($manifiestoId, $vehiculoId, $choferId)
    {

        require_once __DIR__ . '/../../controlador/commons/TCPDF-main/tcpdf.php';

        $dataManifiestoDetalle = Movimiento::create()->obtenerDetalleManifiesto($manifiestoId);
        $manifiestoSerieNumero = $dataManifiestoDetalle[0]['manifiesto'];
        $Getconductor = PersonaNegocio::create()->TraerConductor($choferId);
        $Getbus = VehiculoNegocio::create()->getVehiculo($vehiculoId);

        $busNumero = $Getbus[0]["flota_numero"];
        $busPlaca = $Getbus[0]["flota_placa"];
        $conductor = $Getconductor[0]["conductor"];

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->startPageGroup();

        $pdf->AddPage();
        $fechaSalida = $dataManifiestoDetalle[0]['fecha_salida'];
        $horaSalida = date("g:i a", strtotime(substr($fechaSalida, -8)));

        $manifiestoCabecera = '
            <table style="font-size:9px; padding:5px 0px;">        
                        <tr>
                            <td style="font-size:12px;color:#333; background-color:white; text-align:center">
                              INTERNACIONAL DE TRANSPORTE TURISTICO Y SERVICIOS S.R.L
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size:12px;color:#333; background-color:white; text-align:center">
                              MANIFIESTO DE ENCOMIENDAS
                            </td>
                        </tr>
                        <tr>
                            <td style="color:#333; background-color:white; width:50%;text-align:left">
                              Oficina Origen: ' . $dataManifiestoDetalle[0]['agencia_origen'] . '
                            </td>
                            <td style="color:#333; background-color:white; width:50%;text-align:left">
                                Turno: ' . $horaSalida . '
                            </td>
                        </tr>
                        <tr>
                            <td style="color:#333; background-color:white; width:50%;text-align:left">
                                Oficina Destino: ' . $dataManifiestoDetalle[0]['agencia_destino'] . '
                            </td>
                            <td style="color:#333; background-color:white; width:50%;text-align:left">
                                Fecha de Envio:  ' . DateUtil::formatearFechaBDAaCadenaVw($fechaSalida) . '
                            </td>
                        </tr>
                        <tr>
                            <td style="color:#333; background-color:white; width:10%;text-align:left">
                              Bus: ' . $busNumero . '
                            </td>
                            <td style="color:#333; background-color:white; width:14%;text-align:left">
                            Placa: ' . $busPlaca . '
                          </td>
                          <td style="color:#333; background-color:white; width:38%;text-align:left">
                              Piloto: ' . $conductor . '
                            </td>
                            <td style="color:#333; background-color:white; width:38%;text-align:left">
                            Copiloto: 
                          </td> 
                         </tr>
            </table>  ';

        $pdf->writeHTML($manifiestoCabecera, false, false, false, false, '');

        $tableDetalleTotal = '';
        foreach (array(
            array("comprobante_tipo_id" => 6, "descripcion" => "BOLETA"),
            array("comprobante_tipo_id" => 7, "descripcion" => "FACTURA"),
            array("comprobante_tipo_id" => 191, "descripcion" => "NOTA DE VENTA")
        ) as $comprobante) {

            $dataDetalle = ObjectUtil::filtrarArrayPorColumna($dataManifiestoDetalle, 'factura_documento_tipo_id', $comprobante['comprobante_tipo_id']);

            $dataDetalleCount = array_unique(array_map(function ($item) {
                return $item['factura_id'];
            }, $dataDetalle));

            $totalItems = 0;
            $totalMonto = 0;
            $tableDetalle = '';

            //Cambios Cristopher
            $contador = 0;
            $facturaActual = $dataDetalle[0]["factura"];

            foreach ($dataDetalle as $item) {
                if($facturaActual == $item["factura"]){
                    $contador += 1;
                } else {
                    $contador = 1;
                    $facturaActual = $item["factura"];
                }

                if ($item["modalidad_id"] == 75) {
                    $modalidadDescipcion = 'PC/ENTREGA';
                } else {
                    $modalidadDescipcion = '';
                }
                if ($item["modalidad_id"] == 77) {
                    $direccionDestino = 'OFICINA';
                } else {
                    $lengthCaracter = $pdf->GetStringWidth(mb_strtoupper($item["persona_direccion_destino"], 'UTF-8'));
                    if ($lengthCaracter < 86) {
                        $direccionDestino = mb_strtoupper($item["persona_direccion_destino"], 'UTF-8');
                    } else {
                        $direccionDestino = substr(mb_strtoupper($item["persona_direccion_destino"], 'UTF-8'), 0, 33);
                    }
                }

                $lengthCaracterDestinatario = $pdf->GetStringWidth(mb_strtoupper($item["receptor_nombre"], 'UTF-8'));
                if ($lengthCaracterDestinatario < 80) {
                    $destinatario = mb_strtoupper($item["receptor_nombre"], 'UTF-8');
                } else {
                    $destinatario = substr(mb_strtoupper($item["receptor_nombre"], 'UTF-8'), 0, 30);
                }

                $bienDescripcion = str_replace(array('á', 'é', 'í', 'ó', 'ú'), array('a', 'e', 'i', 'o', 'u'), substr($item["bien_descripcion"], 0, 13));
                $bienDescripcion = substr(mb_strtoupper($bienDescripcion, 'UTF-8'), 0, 13);

                $cantidad = number_format($item["cantidad"], 2);
                $precio = number_format($item["valor_monetario"], 2);
                $totalItems = ($totalItems + $cantidad);
                $total = ($cantidad * $precio);
                $totalMonto = $totalMonto + strval($total);
                $tableDetalle .= '<tr>
                                    <td style=" background-color:white; width:10%; text-align:left">' . (($contador == 1) ?$item["guia"] . "<br>"  :"") . $item["factura"] . '</td>         
                                        <td style=" background-color:white; width:27%; text-align:left">' . $destinatario . '</td>
                                        <td style=" background-color:white; width:30%; text-align:left">' . $direccionDestino . '</td>
                                        <td style=" background-color:white; width:11%; text-align:right">' . $modalidadDescipcion . '</td>
                                        <td style=" background-color:white; width:14%; text-align:left"> ' . intval($item["cantidad"]) . ' ' . $bienDescripcion . ' </td>
                                        <td style=" background-color:white; width:8%; text-align:center">' . number_format($total, 2) . '</td>
                                    </tr>';
            }

            if (count($dataDetalleCount) > 0) {
                $tableDetalleTotal .= '<table style="font-size:7.5px;padding:2px 0px;">
                                    <tr>
                                        <td style="color:#333; background-color:white; text-align:left">
                                            <b>' . $comprobante['descripcion'] . '</b>
                                        </td>
                                    </tr>
                                    <br>
                                    <tr>
                                        <td style="color:#333; background-color:white; text-align:left">
                                            ENCOMIENDAS
                                        </td>
                                    </tr>	
                                    <table style="font-size:8px; padding:5px 0px;">
                                    <tr>        
                                        <td style=" background-color:white; width:10%; text-align:left">' . $comprobante['descripcion'] . '</td>
                                        <td style=" background-color:white; width:27%; text-align:left">CONSIGNATARIO</td>
                                        <td style=" background-color:white; width:30%; text-align:left">DIRECCION</td>
                                        <td style=" background-color:white; width:11%; text-align:center"></td>
                                        <td style=" background-color:white; width:14%; text-align:left">DESCRIPCION</td>
                                        <td style=" background-color:white; width:8%; text-align:center">IMPORTE</td>
                                    </tr>    
                                    ' . $tableDetalle . '
                                    </table >
                                    <br>
                                    <tr>
                                        <td style="color:#333; background-color:white; width:60%; text-align:left;font-size:9px">
                                                Total de ' . $comprobante['descripcion'] . ': ' . count($dataDetalleCount) . '
                                        </td>
                                        <td style="color:#333; background-color:white;width:26%; text-align:left;font-size:9px">
                                                Total de Encomiendas: ' . (int) $totalItems . '
                                        </td>
                                        <td style="color:#333; background-color:white; width:14%;text-align:left;font-size:9px">
                                                Total: ' . number_format($totalMonto, 2) . '
                                        </td>
                                    </tr>
                                    </table>
                                    <br>';
            }
        }

        $html = <<<EOF
                    <div style="font-size:4px; text-align:center;">
                    &nbsp;
                    </div>             
            $tableDetalleTotal
            EOF;

        $pdf->writeHTML($html, false, false, false, false, '');

        $pdf_title = $manifiestoSerieNumero . '.pdf';
        $url = __DIR__ . '/../../vistas/com/movimiento/documentos/' . $pdf_title;
        ob_clean();
        $pdf->Output($url, 'F');
        return $pdf_title;
    }


    //Cambios Cristopher
    public function imprimirDocumentoGuiaRT($dataGuiaId, $despacho = null)
    {
        require_once __DIR__ . '/../../controlador/commons/TCPDF-main/tcpdf.php';

        sort($dataGuiaId);
        $dataGuiaUrl = array();
        $pdf_title_ = '';
        $detalle_guia_ = Movimiento::create()->getDocumentoGuiaRT($dataGuiaId[0]);
        $documentoDespachoOReparto = str_split($despacho)[0];

        //$medidas =  array(210, 270);
        $medidas = $detalle_guia_[0]["comprobante_tipo_id"] != 6 ? array(210, 400) : array(210, 470); // Ajustar aqui segun los milimetros necesarios;

        //$pdf = new TCPDF(PDF_PAGE_ORIENTATION, 'mm', $medidas, true, 'UTF-8', false);
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(0, 0, 0);
        $pdf->startPageGroup();


       
        foreach ($dataGuiaId as $indexGuia => $guiaId) {

            $detalle_guia = Movimiento::create()->getDocumentoGuiaRT($guiaId);
            if (ObjectUtil::isEmpty($detalle_guia)) {
                throw new WarningException("No se Encontraron Registros");
            }

            $pdf->AddPage();
            //            $arrayDestino = explode(',', $Listdestino);
            $bloque = '';

            $total_ = 0;
            $cabecera = '';
            $detalle = '';
            $pie = '';
            $DepartamentoOrigen = $this->getDepartamento($detalle_guia[0]["agencia_origen_ubigeo"]);
            $DepartamentoDestino = $this->getDepartamento($detalle_guia[0]["agencia_destino_ubigeo"]);

            $DepartOrigen = $DepartamentoOrigen[0]["departamento"];
            $DistritoOrigen = $DepartamentoOrigen[0]["distrito"];
            $DepartDestino = $DepartamentoDestino[0]["departamento"];
            $DistritoDestino = $DepartamentoDestino[0]["distrito"];

            $fechaGuia = substr($detalle_guia[0]["fecha_salia"] == NULL ? $detalle_guia[0]["fecha_emision"] : $detalle_guia[0]["fecha_salia"], 0, -4);
            $fechaFormateadaGuia = date("d/m/Y", strtotime($fechaGuia));

            $horaFormateadaGuia = date("H:i:s", strtotime($fechaGuia));
            $anio = $detalle_guia[0]["comprobante_tipo_id"] != 6 ? substr($fechaGuia, 0, 4) : substr($fechaGuia, 2, 2);
            $mes = substr($fechaGuia, 5, 2);
            $dia = substr($fechaGuia, 8, 2);
            $arraymes = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

            $codigoConfiguracion = $detalle_guia[0]['vehiculo_codigo_configuracion'];
            $tarjetaCirculacion = $detalle_guia[0]['vehiculo_tarjeta_circulacion'];

            $cantidad = 0;
            $descripcion = '';
            $peso = 0;
            $total = 0;
            $rucEmpresa='20132272418';
            $TipoComprobanteNro='31';
            $url=Configuraciones::url_base().'vistas/images/correoIttsa.png';
            $serieCorrelativoGuia=$detalle_guia[0]['guia_serie']. '-'  .$detalle_guia[0]['guia_numero'];
            $textoComprobante = $rucEmpresa . "|" . $TipoComprobanteNro. "|" .$detalle_guia[0]['guia_serie']."|".$detalle_guia[0]['guia_numero'];
            $urlComprobanteQr = DocumentoNegocio::create()->generaQRguia($textoComprobante, $serieCorrelativoGuia);


            $urlqr=Configuraciones::url_base().$urlComprobanteQr;
            $x = 5; // Coordenada X de la posición de la imagen
$y = 3; // Coordenada Y de la posición de la imagen
$ancho = 64; // Ancho de la imagen en mm
$alto = 30; // Alto de la imagen en mm

$anchoqr = 17; // Ancho de la imagen en mm
$altoqr = 14; // Alto de la imagen en mm
$yqr=261;
$xqr=93;

$pdf->Image($url, $x, $y, $ancho, $alto);
$pdf->Image($urlqr, $xqr, $yqr, $anchoqr, $altoqr);
            //for ($i = 1; $i <= 10; $i++) {
                $contador=1;
            foreach ($detalle_guia as $key => $item) {
                    
                    $cantidad = number_format((float) $item["cantidad"], 2);
                    $descripcion = $item["bien_descripcion"] . ' | ' . $item["comprobante_serie"] . '-' . $item["comprobante_numero"];
                    $peso = number_format(($item['bien_peso'] * $cantidad), 2);
                    $total = number_format(($item['valor_monetario'] * $cantidad), 2);
                    

                    $total_ = str_replace(',', '', $peso) + $total_;
                    $detalle .= '<tr style="padding:0px 0px !important;" cellspacing="0">
                    <td style="width:2%;height:0.7px">
                     
                    </td>
                    <td style=" border: 0.7px solid black; font-size:6px;color:#333; background-color:white; text-align:center;width:8%;height:0.7px;">
                    ' . $contador . '
                    </td>
                    <td style=" border: 0.7px solid black; font-size:6px;color:#333; background-color:white; text-align:center;width:8%;height:0.7px;">
                    ' . $item['bien_codigo'] . '
                    </td>

                    <td style=" border: 0.7px solid black; font-size:6px;color:#333; background-color:white; text-align:left;width:64%;height:0.7px;">
                    ' . $descripcion . '
                    </td>
													  
						
						 

                    <td style=" border: 0.7px solid black; font-size:6px;color:#333; background-color:white; text-align:center;width:8%;height:0.7px;">
                    ' . $cantidad .  '
						 
													 
						
                    </td>

                    <td style=" border: 0.7px solid black; font-size:6px;color:#333; background-color:white; text-align:center;width:8%;height:0.7px;">
                     NIU 
                    </td>
                    </tr>';
                
                $contador=$contador+1;
                }
                
                if($documentoDespachoOReparto == "D" || ObjectUtil::isEmpty($documentoDespachoOReparto)) {
                    $destinoguia = $detalle_guia[0]['agencia_destino_direccion'];

                } elseif($documentoDespachoOReparto == "M") {
                    $destinoguia = $detalle_guia[0]['direccion_destino_descripcion'];
                }

                if($detalle_guia[0]['guia_serie'] != 264) {
                    $certificadoMTC = $detalle_guia[0]['certificadoMTC'];
                    $tituloGuia = '
                    GUÍA DE REMISIÓN <br>
                    ELECTRÓNICA  <br>
                    TRANSPORTISTA <br>
                    AUT.MTC ' . $certificadoMTC . ' <br><br>';
                } else {
                    $tituloGuia = "
                    GUÍA <br>
                    INTERNA <br><br>";
                }

                $tipoNumeroDocumentoRemitente = (str_split($detalle_guia[0]['remitente_documento'],2)[0] == "20") ?"06" :"01";
                $tipoNumeroDocumentoDestinatario = (str_split($detalle_guia[0]['destinatario_documento'],2)[0] == "20") ?"06" :"01";

                /*if (!ObjectUtil::isEmpty($detalle_guia[0]['agencia_destino_direccion'])) {
                    $destinoguia = $detalle_guia[0]['direccion_destino_descripcion'];
                } else {
                    $destinoguia = $detalle_guia[0]['destinatario_direccion'];
                }*/
			 
            //}
            //if ($detalle_guia[0]["TipoComprobante"] == 6) {//boleta
            //boleta
                $cabecera .= '<table style="font-size:9px;padding:0.1px 0px;" cellspacing="0">
                <tr>
                <td  style="width:70%;height:3px">                    

                </td>

                </tr>
					
												   

                <tr>
                <td  style="width:75%;height:93px">
              
                <img  src='.$urlComprobanteQr.'>
  	
                </td>
            
                
                <td style="border: 1px solid black; font-size:10px;color:#333; background-color:white; text-align:center;width:23%;">
                <b>
                RUC: 20132272418 <br><br>
                ' . $tituloGuia . '
                ' .$detalle_guia[0]['guia_serie']. ' - '  .$detalle_guia[0]['guia_numero']. '
                </b>
                </td>
                </tr>
					
												   
					
					 
																													

                <tr>
																												   
							
					 
																												   
							
					 
																												   
														   
					 
                <td  style="width:2%;">    
					
                </td>
                <td style="font-size:9px;color:#333; background-color:white; text-align:left;width:96%;height:10px"><b>
                INTERNACIONAL DE TRANSPORTE TURISTICO Y SERVICIOS SRL </b>
                </td>
                </tr>

                <tr>
                <td  style="width:2%;">    
					
					 
																													
									   
					 
												  
					
                </td>
                <td style="font-size:7px;color:#333; background-color:white; text-align:left;width:96%;height:10px">
                AV. TUPAC AMARU NRO. 1198 URB. SANTA LEONOR LA LIBERTAD - TRUJILLO - TRUJILLO 
                </td>
                </tr>

                <tr>
                <td  style="width:2%;">    
					
					 
																													
										
					 
												  
					
                </td>
                <td style="font-size:7px;color:#333; background-color:white; text-align:left;width:96%;height:10px">
                    ' . strtoupper($detalle_guia[0]['ubigeo_descripcion']) .'
                </td>
                </tr>

                <tr>
                <td  style="width:2%;">    
					
                </td>
                <td style="font-size:7px;color:#333; background-color:white; text-align:left;width:96%;height:10px">
                Teléfono: (044) 728609 
                </td>
                </tr>
         
                </table>

                <table>
                <tr>
                <td  style="width:100%;height:0.1px">
                    
                </td>
         
                </tr>
                </table>
         
                <table style="font-size:8px;padding:0.1px 0px; border-collapse: collapse; " cellspacing="0">
                
                <tr>
                <td  style="width:2%;">    
                </td>
                <td style="border-left: 1px solid black; border-right: 1px solid black; border-top: 1px solid black;  font-size:6px;color:#333; background-color:white; text-align:left;width:96%;height:3px">
                Remitente:
                </td>
                </tr>

                <tr> 
                <td  style="width:2%;">    
                </td>
                <td style="border-left: 1px solid black; border-right: 1px solid black; font-size:6px;color:#333; background-color:white; text-align:left;width:96%;height:3px">
                Tipo y número de documento: ' .$tipoNumeroDocumentoRemitente." | " . $detalle_guia[0]['remitente_documento'] .'
                </td>
                </tr>

                <tr> 
                <td  style="width:2%;">    
                </td>
                <td style="border-left: 1px solid black; border-right: 1px solid black; font-size:6px;color:#333; background-color:white; text-align:left;width:96%;height:3px">
                Apellidos y nombres, denominación o razón social : ' .$detalle_guia[0]['remitente_nombre']. '
                </td>
                </tr>

                <tr> 
                <td  style="width:2%;">    
                </td>
                <td style="border-left: 1px solid black; border-right: 1px solid black; font-size:6px;color:#333; background-color:white; text-align:left;width:96%;height:3px">
                Destinatario:
                </td>
                </tr>

                <tr> 
                <td  style="width:2%;">    
                </td>
                <td style="border-left: 1px solid black; border-right: 1px solid black; font-size:6px;color:#333; background-color:white; text-align:left;width:96%;height:3px">
                Tipo y número de documento: '.$tipoNumeroDocumentoDestinatario. " | " .$detalle_guia[0]['destinatario_documento'] .'
                </td>
                </tr>

                <tr> 
                <td  style="width:2%;">    
                </td>
                <td style="border-left: 1px solid black; border-right: 1px solid black; font-size:6px;color:#333; background-color:white; text-align:left;width:96%;height:3px">
                Apellidos y nombres, denominación o razón social : ' .$detalle_guia[0]['destinatario_nombre']. '
                </td>
                </tr>

                <tr>
                <td  style="width:2%;">    
                </td>
                <td style="border-left: 1px solid black; border-right: 1px solid black; font-size:6px;color:#333; background-color:white; text-align:left;width:96%;height:3px">
                Fecha de emisión: '.$fechaFormateadaGuia.'
                </td>
                </tr>

                <tr>
                <td  style="width:2%;">    
                </td>
                <td style="border-left: 1px solid black; border-right: 1px solid black; font-size:6px;color:#333; background-color:white; text-align:left;width:96%;height:3px">
                Hora de emisión: '.$horaFormateadaGuia.'
                </td>
                </tr>

                <tr>
                <td  style="width:2%;">    
                </td>
                <td style="border-left: 1px solid black; border-right: 1px solid black; font-size:6px;color:#333; background-color:white; text-align:left;width:96%;height:3px">
                CONDUCTOR | Licencia: ' .$detalle_guia[0]['chofer_nombre']. ' | '.$detalle_guia[0]['licencia'].' 
                </td>
                </tr>

                <tr>
                <td  style="width:2%;">    
					 
																													
							
					 
																													
											 
					 
																													
							 
					 
												  
                </td>
                <td style="border-left: 1px solid black; border-right: 1px solid black; font-size:6px;color:#333; background-color:white; text-align:left;width:96%;height:3px">
                CONDUCTOR Secundario | Licencia: ' .$detalle_guia[0]['copiloto_nombre']. ' | '.$detalle_guia[0]['copiloto_licencia'].' 
                </td>
                </tr>

                <tr>
                <td  style="width:2%;">    
					 
																													
									   
					 
																													
									 
					 
												   
					 
																													
										
					 
												   
                </td>
                <td style="border-left: 1px solid black; border-right: 1px solid black; font-size:6px;color:#333; background-color:white; text-align:left;width:96%;height:3px">
                VEHÍCULO | Nro. Placa | Tarjeta Circulación : ' . $detalle_guia[0]['flota_marca'] . ' | ' . $detalle_guia[0]['flota_placa'] . ' | ' . $detalle_guia[0]['tarjeta_circulacion'] . '
                </td>
                </tr>

                <tr>
                <td  style="width:2%;">    
					 
																													
																		   
					 
									   
                </td>
                <td style="border-left: 1px solid black; border-right: 1px solid black; font-size:6px;color:#333; background-color:white; text-align:left;width:96%;height:3px">
                TIPO Y NÚMERO DE DOCUMENTO RELACIONADO: ' . $detalle_guia[0]['guia_relacion'] . '
                </td>
                </tr>

                <tr>
                <td  style="width:2%;">    
					 
																													
															   
					 
										
                </td>
                <td style="border-left: 1px solid black; border-right: 1px solid black;  font-size:6px;color:#333; background-color:white; text-align:left;width:96%;height:3px">
                Transportista(s): INTERNACIONAL DE TRANSPORTE TURISTICO Y SERVICIOS S.R.L
                </td>
                

                </tr>

                <tr>
                <td  style="width:2%;">    
                </td>
                <td style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; font-size:6px;color:#333; background-color:white; text-align:left;width:96%;height:3px">
                CERTIFICADO TRANSPORTE | CÓDIGO MTC: 
                </td>
                

													   
					 
																												   
										   
					 
                </tr>
               
                </table>
 
                <table>
                <tr>
                <td  style="width:100%;height:1px">
                    
																													
											
                </td>
         
                </tr>
                </table>
         
                <table style="font-size:8px;padding:0.1px 0px;" cellspacing="0">
                <tr style="border: 1px solid black;">
                <td  style="width:2%;">    
                </td>
                <td style=" border: 1px solid black; font-size:6px;color:#333; background-color:white; text-align:left;width:18%;height:7px">
                <b>Fecha de inicio de Traslado:</b>
                </td>

                <td style=" border: 1px solid black; font-size:6px;color:#333; background-color:white; text-align:left;width:78%;height:7px">
                '.$fechaFormateadaGuia.'
                </td>
                
                </tr>

                <tr style="border:1px;">
                <td  style="width:2%;">    
                </td>
                <td style=" border: 1px solid black; font-size:6px;color:#333; background-color:white; text-align:left;width:18%;height:7px">
                <b>Dirección de Partida:</b>
                </td>
                
                <td style=" border: 1px solid black; font-size:6px;color:#333; background-color:white; text-align:left;width:78%;height:7px">
                ' . $detalle_guia[0]['agencia_origen_cod_ubigeo'] . '-' . $detalle_guia[0]['agencia_origen_direccion'] . '
                </td>

                </tr>


                <tr style="border:1px;">
                <td  style="width:2%;">    
                </td>
                <td style=" border: 1px solid black; font-size:6px;color:#333; background-color:white; text-align:left;width:18%;height:7px">
                <b>Dirección de Llegada:</b>
                </td>
                
                <td style=" border: 1px solid black; font-size:6px;color:#333; background-color:white; text-align:left;width:78%;height:7px">
                ' . (ObjectUtil::isEmpty($detalle_guia[0]['agencia_destino_cod_ubigeo']) ?$detalle_guia[0]['agencia_origen_cod_ubigeo'] :$detalle_guia[0]['agencia_destino_cod_ubigeo']) . '-' . $destinoguia . '
                </td>

                </tr>

                <tr style="border:1px;">
                <td  style="width:2%;">    
                </td>
                <td style=" border: 1px solid black; font-size:6px;color:#333; background-color:white; text-align:left;width:18%;height:7px">
                <b>Peso bruto (KGM): </b>
                </td>

                <td style=" border: 1px solid black; font-size:6px;color:#333; background-color:white; text-align:left;width:78%;height:7px">
                ' . number_format($total_, 2) . '
                </td>
																												   
         
                </tr>
                <tr>
                <td  style="width:100%;height:2px">
                    
                </td>
                </tr>
 
                </table>

                <table style="font-size:9px;padding:0.5px 0px;">
                <tr >
                <td style="width:2%;height:5px">
                 
                </td>
                <td style=" border: 1px solid black; font-size:7px;color:#333; background-color:white; text-align:center;width:8%;">
                <b>Nro</b>
                </td>
                <td style=" border: 1px solid black; font-size:7px;color:#333; background-color:white; text-align:center;width:8%;">
                <b>Código</b>
                </td>

                <td style=" border: 1px solid black; font-size:7px;color:#333; background-color:white; text-align:center;width:64%;">
                <b>Descripción</b>
                </td>

                <td style=" border: 1px solid black; font-size:7px;color:#333; background-color:white; text-align:center;width:8%;">
                <b>Cantidad</b>
                </td>
       
                <td style=" border: 1px solid black; font-size:7px;color:#333; background-color:white; text-align:center;width:8%;">
                <b>Unidad</b>

                </td>
                </tr>
             ';

            $pdf_title_ .= '' . $detalle_guia[0]['guia_serie'] . '-' . $detalle_guia[0]['guia_numero'];
            

            $pie .= '</table>';
            $bloque .= $cabecera . $detalle . $pie;
            $pdf->writeHTML($bloque, false, false, false, false, '');
        }

        $date = strtotime($detalle_guia[0]['fecha_emision']);
        $currentDate = date('Y-m-d', $date);

        //$date = strtotime($detalle_guia[0]['fecha_emision']);
        //$currentDate = $date->format('Y-m-d H:i:s');
        //$currentDate = date('Y-m-d', $date);

        /*if (sizeof($dataGuiaId) > 1) { //boleta
            //$pdf_title = 'Guias-' . $pdf_title_ . '.pdf';
            //$pdf_title = 'GRTS_' . $pdf_title_ . '_' . $detalle_guia[0]['flota_numero'] . '_' . $detalle_guia[0]['flota_placa'] . '_' . $currentDate . '.pdf';
        } else {
            //$pdf_title = 'Guia-' . $detalle_guia[0]['guia_serie'] . '-' . $detalle_guia[0]['guia_numero'] . '.pdf';
            $pdf_title = 'GRT_' . $detalle_guia[0]['guia_serie'] . '-' . $detalle_guia[0]['guia_numero'] . '_' . $detalle_guia[0]['flota_numero'] . '_' . $detalle_guia[0]['flota_placa'] . '_' . $currentDate . '.pdf';
        }*/

        $correlativo = null;

        if (empty($despacho)) {
            $correlativo = $detalle_guia[0]['guia_serie'] . '-' . $detalle_guia[0]['guia_numero'];
        } else {
            $correlativo = $despacho;
        }

        if (empty($detalle_guia[0]['flota_numero'])) {
            $pdf_title = $correlativo . '_' . $currentDate . '.pdf';
        } else {
            $pdf_title = $correlativo . '_' . $detalle_guia[0]['flota_numero'] . '_' . $detalle_guia[0]['flota_placa'] . '_' . $currentDate . '.pdf';
        }
        
        $url = __DIR__ . '/../../vistas/com/movimiento/documentos/' . $pdf_title;
        ob_clean();
        $pdf->Output($url, 'F');

        $dataGuiaUrl[] = $pdf_title;
        return $dataGuiaUrl;
    }

    //Cambios Cristopher
    public function generarGuiaRT($despachoId, $serie, $numero, $vehiculoId, $pilotoId, $copilotoId, $periodoId, $fecha, $usuarioId, $opcionId)
    {
        $obtenerAgenciaxUsuario= PerfilAgenciaCaja::create()->obtenerPerfilAgenciaXUsuarioId($usuarioId);
        $agenciaId=$obtenerAgenciaxUsuario[0]['id'];
        $cantidadAgencias=count($obtenerAgenciaxUsuario);
      
         if ($cantidadAgencias > 1){
            throw new WarningException("Usuario no puede tener asignada mas de 1 agencia para generar despacho.");
        }
        $datosDocumento = Movimiento::create()->obtenerDespachoDetalle($despachoId);

        if (ObjectUtil::isEmpty($datosDocumento)) {
            throw new WarningException("No se encuentra el documento de despacho.");
        }

        $dataGuias = array_unique(array_map(
            function ($item) {
                return $item['guia_id'];
            },
            array_filter($datosDocumento, function ($itemFiltrado) {
                return ($itemFiltrado['factura_documento_tipo_id'] == 6 || $itemFiltrado['factura_documento_tipo_id'] == 7
                    || $itemFiltrado['factura_documento_tipo_id'] == 191);
            })
        ));

        foreach ($dataGuias as $guiaId) {
            Movimiento::create()->actualizarGrtDespacho($guiaId, $vehiculoId, $pilotoId, $copilotoId);
        }

        /*$dataBoletaManifiestoId = array_unique(array_map(
            function ($item) {
                return $item['manifiesto_id'];
            },
            array_filter($datosDocumento, function ($itemFiltrado) {
             //cambio
                return ($itemFiltrado['factura_documento_tipo_id'] == 6 || $itemFiltrado['factura_documento_tipo_id'] == 7 
                || $itemFiltrado['factura_documento_tipo_id'] == 191) && $itemFiltrado['guia_id'] == NULL;
            })
        ));

        foreach ($dataBoletaManifiestoId as $manifiestoId) {
            $dataDetalleSeleccionado = ObjectUtil::filtrarArrayPorColumna($datosDocumento, ['manifiesto_id', 'factura_documento_tipo_id'], [$manifiestoId, 6]);
            $dataDetalleSeleccionado = array_merge(array_filter($dataDetalleSeleccionado, function ($itemFiltrado) {
        //cambio
                return $itemFiltrado['guia_id'] == NULL;
            }));
            $detalle = array();
            $documento = array();
            foreach ($dataDetalleSeleccionado as $indexDetalle => $itemDetalle) {

                $itemDetalleAgregar = array();
                $itemDetalleAgregar["bienId"] = $itemDetalle['bien_id'];
                $itemDetalleAgregar["bienDesc"] = $itemDetalle['bien_descripcion'];
                $itemDetalleAgregar["unidadMedidaId"] = -1;
                $itemDetalleAgregar["precioTipoId"] = 2;
                $itemDetalleAgregar["cantidad"] = $itemDetalle['manifiesto_cantidad'];
                $itemDetalleAgregar["precio"] = $itemDetalle['valor_monetario'];
                $itemDetalleAgregar["movimientoBienPadreId"] = $itemDetalle['pedido_movimiento_bien_id'];
                $itemDetalleAgregar["documentoPadreId"] = $itemDetalle['manifiesto_id'];

                $detalle[] = $itemDetalleAgregar;
                $personaIttsaId = 22406;

                // obtenemos el detalle para el documento guia cada 30 item genera una guía
           //cambio
                 if ((($indexDetalle + 1) % 30 == 0) || ((count($dataDetalleSeleccionado) - 1) == $indexDetalle && !ObjectUtil::isEmpty($detalle))) {
                    $documento['documentoTipoId'] = 284;
                    $documento['serie'] = $serie;
                    $documento['numero'] = str_pad($numero, 6, "0", STR_PAD_LEFT);
                    $documento['monedaId'] = $itemDetalle['moneda_id'];
                    $documento['fechaEmision'] = $fecha;
                    $documento['periodoId'] = $periodoId;
                    $documento['fechaSalida'] = DateUtil::formatearFechaBDAaCadenaVw($itemDetalle['fecha_salida']);
                    $documento['documentoEstadoId'] = 1; // REGISTRADO
                    $documento['clienteId'] = $personaIttsaId; // PersonaIttsa;
                    $documento['personaId'] = $personaIttsaId; // PersonaIttsa;
                    $documento['destinatarioId'] = $personaIttsaId; // PersonaIttsa;
                    $documento['agenciaOrigenId'] = $itemDetalle['agencia_id'];
                    $documento['agenciaDestinoId'] = $itemDetalle['agencia_destino_id'];
                    $documento['empresaId'] = $itemDetalle['empresa_id'];

                    $documento['vehiculoId'] = $vehiculoId;
                    $documento['choferId'] = $pilotoId;
                    $documento['copilotoId'] = $copilotoId;

                    $respuestaGuardarGuia = PedidoNegocio::create()->guardar($opcionId, $usuarioId, $documento, $detalle);

                    $tipoRelacion = 8; // MANIFIESTO - GUIA 
                    $respuestaRelacion = DocumentoNegocio::create()->guardarDocumentoRelacionado($manifiestoId, $respuestaGuardarGuia->documentoId, 1, 1, $usuarioId, NULL, $tipoRelacion);
                    if ($respuestaRelacion[0]['vout_exito'] != 1) {
                        throw new WarningException("Error al intentar guardar la relación del documento.");
                    }

                    $detalle = array();
                    $documento = array();
                    $numero++;
             //cambio
                     }
      
            }
            
                  
            $dataComprobanteManifiesto = array_unique(array_map(
                function ($item) {
                    return $item['factura_id'];
                },
                array_filter($datosDocumento, function ($itemFiltrado) {
                 //cambio
                    return ($itemFiltrado['factura_documento_tipo_id'] == 6 || $itemFiltrado['factura_documento_tipo_id'] == 7 
                    || $itemFiltrado['factura_documento_tipo_id'] == 191) && $itemFiltrado['guia_id'] == NULL;
                })
            ));
           
            foreach ($dataComprobanteManifiesto as $documentoComprobante) {
            $dataDetalleSeleccionadoF = ObjectUtil::filtrarArrayPorColumna($datosDocumento, ['manifiesto_id', 'factura_id'], [$manifiestoId, $documentoComprobante]);
            $dataDetalleSeleccionadoF = array_filter($dataDetalleSeleccionadoF, function ($item) {
                $factura_documento_tipo_id = $item['factura_documento_tipo_id'];
                return($factura_documento_tipo_id == 191 || $factura_documento_tipo_id == 7) && $item['guia_id'] == NULL;
            });
           
            $detalle = [];
            foreach ($dataDetalleSeleccionadoF as $indexDetalle => $itemDetalle) {
            
                if($itemDetalle['factura_documento_tipo_id']==7){
                      $documentoIdentificador=4;
                } else {
                    $documentoIdentificador=43;
                }
                $listarSerieCorrelativo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoDato(284,null, $agenciaId, $documentoIdentificador);
               
                foreach ($listarSerieCorrelativo as $itemDtd) {
                    // asignamos la data necesaria para que cargue los componentes
                    switch ($itemDtd["tipo"]) {
                        case DocumentoTipoNegocio::DATO_SERIE:
                            $serieF = $itemDtd["data"];
                            break;
                        case DocumentoTipoNegocio::DATO_NUMERO:
                            $numeroF = $itemDtd["data"];
                            break;
                    }
                }

                $itemDetalleAgregar = array();
                $itemDetalleAgregar["bienId"] = $itemDetalle['bien_id'];
                $itemDetalleAgregar["bienDesc"] = $itemDetalle['bien_descripcion'];
                $itemDetalleAgregar["unidadMedidaId"] = -1;
                $itemDetalleAgregar["precioTipoId"] = 2;
                $itemDetalleAgregar["cantidad"] = $itemDetalle['manifiesto_cantidad'];
                $itemDetalleAgregar["precio"] = $itemDetalle['valor_monetario'];
                $itemDetalleAgregar["movimientoBienPadreId"] = $itemDetalle['pedido_movimiento_bien_id'];
                $itemDetalleAgregar["documentoPadreId"] = $itemDetalle['manifiesto_id'];
             //  $detalle = [];
                $detalle[] = $itemDetalleAgregar;
                $personaIttsaId = 22406;

                // obtenemos el detalle para el documento guia cada 30 item genera una guía
           //cambio
           if ((($indexDetalle + 1) % 30 == 0) || ((count($dataDetalleSeleccionadoF) - 1) == $indexDetalle && !ObjectUtil::isEmpty($detalle))) {
                    $documento['documentoTipoId'] = 284;
                    $documento['serie'] = $serieF;
                    $documento['numero'] = str_pad($numeroF, 6, "0", STR_PAD_LEFT);
                    $documento['monedaId'] = $itemDetalle['moneda_id'];
                    $documento['fechaEmision'] = $fecha;
                    $documento['periodoId'] = $periodoId;
                    $documento['fechaSalida'] = DateUtil::formatearFechaBDAaCadenaVw($itemDetalle['fecha_salida']);
                    $documento['documentoEstadoId'] = 1; // REGISTRADO
                    $documento['clienteId'] = $personaIttsaId; // PersonaIttsa;
                    $documento['personaId'] = $personaIttsaId; // PersonaIttsa;
                    $documento['destinatarioId'] = $personaIttsaId; // PersonaIttsa;
                    $documento['agenciaOrigenId'] = $itemDetalle['agencia_id'];
                    $documento['agenciaDestinoId'] = $itemDetalle['agencia_destino_id'];
                    $documento['empresaId'] = $itemDetalle['empresa_id'];

                    $documento['vehiculoId'] = $vehiculoId;
                    $documento['choferId'] = $pilotoId;
                    $documento['copilotoId'] = $copilotoId;

                    $respuestaGuardarGuia = PedidoNegocio::create()->guardar($opcionId, $usuarioId, $documento, $detalle);

                    $tipoRelacion = 8; // MANIFIESTO - GUIA 
                    $respuestaRelacion = DocumentoNegocio::create()->guardarDocumentoRelacionado($manifiestoId, $respuestaGuardarGuia->documentoId, 1, 1, $usuarioId, NULL, $tipoRelacion);
                    if ($respuestaRelacion[0]['vout_exito'] != 1) {
                        throw new WarningException("Error al intentar guardar la relación del documento.");
                     }
              }
              
            }
        }
    }*/

        $dataManifiestoId = array_unique(array_map(function ($item) {
            return $item['manifiesto_id'];
        }, $datosDocumento));

        foreach ($dataManifiestoId as $manifiestoId) {
            $respuestaActualizarEstado = self::actualizarDocumentoManifiestoChofer($manifiestoId, $pilotoId, $copilotoId, $vehiculoId);
        }

        DocumentoNegocio::create()->insertarDocumentoDocumentoEstado($despachoId, 14, $usuarioId);

        $respuesta = new stdClass();
        $respuesta->estado = 1;
        $respuesta->mensaje = 'OK';
        $respuesta->table = Movimiento::create()->obtenerDespachoDetalle($despachoId);
        return $respuesta;
    }

    //Cambios Cristopher
    public function generarGuiaRTXManifiestoId($manifiestoId, $serie, $numero, $vehiculo, $piloto, $periodo, $fecha, $usuarioId, $opcionId)
    {
        $obtenerAgenciaxUsuario = PerfilAgenciaCaja::create()->obtenerPerfilAgenciaXUsuarioId($usuarioId);
        $agenciaIdUsuario = $obtenerAgenciaxUsuario[0]['id'];

        //AQUI MODIFICAR
        $datosDocumento = Movimiento::create()->obtenerDetalleManifiesto($manifiestoId);

        $arrayGuias = array();

        if (ObjectUtil::isEmpty($datosDocumento)) {
            throw new WarningException("No se encuentra el documento de manifiesto.");
        }

        $dataBoletaManifiestoId = array_unique(array_map(
            function ($item) {
                return $item['manifiesto_id'];
            },
            array_filter($datosDocumento, function ($itemFiltrado) {
                return ($itemFiltrado['factura_documento_tipo_id'] == 6 || $itemFiltrado['factura_documento_tipo_id'] == 7
                    || $itemFiltrado['factura_documento_tipo_id'] == 191)  && ($itemFiltrado['guia_id'] == null);
            })
        ));

        foreach ($dataBoletaManifiestoId as $manifiesto) {
            $dataComprobanteManifiesto = array_unique(array_map(
                function ($item) {
                    return $item['factura_id'];
                },
                array_filter($datosDocumento, function ($itemFiltrado) {
                    //cambio
                    return ($itemFiltrado['factura_documento_tipo_id'] == 6 || $itemFiltrado['factura_documento_tipo_id'] == 7
                        || $itemFiltrado['factura_documento_tipo_id'] == 191) && $itemFiltrado['guia_id'] == NULL;
                })
            ));

            //nuevo
            foreach ($dataComprobanteManifiesto as $documentoComprobante) {
                $dataDetalleSeleccionado = ObjectUtil::filtrarArrayPorColumna($datosDocumento, ['manifiesto_id', 'factura_id'], [$manifiestoId, $documentoComprobante]);
                $dataDetalleSeleccionado = array_filter($dataDetalleSeleccionado, function ($item) {
                    return $item['guia_id'] == NULL;
                });
                //termina nuevo

                //$detalle = array();
                //$documento = array();

                $detalle = array();
                $monedaId = null;
                $fechaSalida = null;
                $personaId = null;
                $personaOrigenId = null;
                $personaDestinoId = null;
                $agenciaId = null;
                $empresaId = null;
                $personaDireccionDestinoId = null;

                foreach ($dataDetalleSeleccionado as $indexDetalle => $itemDetalle) {
                    if ($itemDetalle['factura_documento_tipo_id'] == 7) {
                        $documentoIdentificador = 4;
                    } else if ($itemDetalle['factura_documento_tipo_id'] == 191) {
                        $documentoIdentificador = 43;
                    } else {
                        $documentoIdentificador = 3;
                    }

                    $listarSerieCorrelativo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoDato(284, null, $agenciaIdUsuario, $documentoIdentificador);
                    //$listarSerieCorrelativo = PedidoNegocio::create()->obtenerCorrelativoGuia($usuarioId, $agenciaId, $documentoIdentificador);

                    foreach ($listarSerieCorrelativo as $itemDtd) {
                        // asignamos la data necesaria para que cargue los componentes
                        switch ($itemDtd["tipo"]) {
                            case DocumentoTipoNegocio::DATO_SERIE:
                                $serieF = $itemDtd["data"];
                                break;
                            case DocumentoTipoNegocio::DATO_NUMERO:
                                $numeroF = $itemDtd["data"];
                                break;
                        }
                    }

                    $itemDetalleAgregar = array();
                    $itemDetalleAgregar["bienId"] = $itemDetalle['bien_id'];
                    $itemDetalleAgregar["bienDesc"] = $itemDetalle['bien_descripcion'];
                    $itemDetalleAgregar["unidadMedidaId"] = -1;
                    $itemDetalleAgregar["precioTipoId"] = 2;
                    $itemDetalleAgregar["cantidad"] = $itemDetalle['cantidad'];
                    $itemDetalleAgregar["precio"] = $itemDetalle['valor_monetario'];
                    $itemDetalleAgregar["movimientoBienPadreId"] = $itemDetalle['movimiento_bien_id'];
                    $itemDetalleAgregar["documentoPadreId"] = $manifiestoId;
                    $detalle[] = $itemDetalleAgregar;

                    $monedaId = $itemDetalle['moneda_id'];
                    $fechaSalida = $itemDetalle['fecha_salida'];
                    $personaId = $itemDetalle['persona_id'];
                    $personaOrigenId = $itemDetalle['persona_origen_id'];
                    $personaDestinoId = $itemDetalle['persona_destinatario_id'];
                    $personaDireccionDestinoId = $itemDetalle['persona_direccion_destino_id'];
                    $agenciaId = $itemDetalle['agencia_id'];
                    $empresaId = $itemDetalle['empresa_id'];
                    $guiaRelacion = $itemDetalle['guia_relacion'];

                    if ((($indexDetalle + 1) % 30 == 0) || ((count($dataDetalleSeleccionado) - 1) == $indexDetalle && !ObjectUtil::isEmpty($detalle))) {
                        $documento['documentoTipoId'] = 284;
                        $documento['serie'] = $serieF;
                        $documento['numero'] = str_pad($numeroF, 6, "0", STR_PAD_LEFT);
                        $documento['monedaId'] = $monedaId;
                        $documento['fechaEmision'] = $fecha;
                        $documento['periodoId'] = $periodo;
                        $documento['fechaSalida'] = DateUtil::formatearFechaBDAaCadenaVw($fechaSalida);
                        $documento['documentoEstadoId'] = 1; // REGISTRADO
                        $documento['clienteId'] = $personaOrigenId;
                        $documento['personaId'] = $personaId;
                        $documento['destinatarioId'] = $personaDestinoId;
                        $documento['destinatarioDireccionId'] = $personaDireccionDestinoId;
                        $documento['agenciaOrigenId'] = $agenciaId;
                        $documento['empresaId'] = $empresaId;
                        $documento['guia_relacion'] = $guiaRelacion;

                        $documento['vehiculoId'] = $vehiculo;
                        $documento['choferId'] = $piloto;

                        $respuestaGuardarGuia = PedidoNegocio::create()->guardar($opcionId, $usuarioId, $documento, $detalle);
                        $arrayGuias[] = $respuestaGuardarGuia->documentoId;

                        $tipoRelacion = 8; // MANIFIESTO - GUIA 
                        $respuestaRelacion = DocumentoNegocio::create()->guardarDocumentoRelacionado($manifiestoId, $respuestaGuardarGuia->documentoId, 1, 1, $usuarioId, NULL, $tipoRelacion);
                        if ($respuestaRelacion[0]['vout_exito'] != 1) {
                            throw new WarningException("Error al intentar guardar la relación del documento.");
                        }
                        $detalle = array();
                        $documento = array();
                        //$numero++;
                    }
                }

                //comentado
                //$consulta = "\$dataDetalleSeleccionado = array_merge(array_filter(\$datosDocumento, function (\$item) { return \$item['guia_id'] == null ; }));";
                //eval($consulta);
                //termina comentado

                //$dataDetalleSeleccionado = ObjectUtil::filtrarArrayPorColumna($datosDocumento, 'factura_id', $boletaId);
            }
        }

        //throw new WarningException("Error al intentar guardar la relación del documento.");

        foreach ($arrayGuias as $guiaId) {
            Movimiento::create()->actualizarGrtDespacho($guiaId, $vehiculo, $piloto, null);
        }

        $respuestaActualizarEstado = self::actualizarDocumentoManifiestoChofer($manifiestoId, $piloto, null, $vehiculo);
        //DocumentoNegocio::create()->insertarDocumentoDocumentoEstado($manifiestoId, 14, $usuarioId);

        $respuesta = new stdClass();
        $respuesta->estado = 1;
        $respuesta->mensaje = 'OK';
        $respuesta->table = Movimiento::create()->obtenerDetalleManifiesto($manifiestoId);
        return $respuesta;
    }

    
    public function TraerPersonaidAgencia($rucEmpresa)
    {
        Movimiento::create()->TraerPersonaidAgencia($rucEmpresa);
    }

    public function enviarCorreoRecepcionPedido($documentoId, $usuarioId)
    {

        $datosRecepcion = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);

        $arrayPedidos = $this->obtenerArrayPedidosXDocumentoId($documentoId);

        //Después de tener el "ArrayPedidos" listo, implementamos la lógica del envío de correos electrónicos.
        $plantilla = EmailPlantillaNegocio::create()->obtenerEmailPlantillaXID(26);
        foreach ($arrayPedidos as $pedidoRecepcion) {

            $datosDocPedido = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($pedidoRecepcion['pedidoId']);

            $destinatario = $plantilla[0]["destinatario"];
            $correosPlantilla = EmailPlantillaNegocio::create()->obtenerEmailXDestinatario($destinatario, $documentoId, null);

            $correos = '';
            foreach ($correosPlantilla as $email) {
                $correos = $correos . $email . ';';
            }

            $nroPedido = $datosDocPedido[0]['serie_numero'];
            $nombreCliente = $datosDocPedido[0]['persona_nombre_e'];
            $agenciaDestino = $datosDocPedido[0]['agencia_destino'] . ' | ' . ($datosPedido[0]['modalidad_codigo'] == 1 ? $datosPedido[0]['persona_direccion_destino'] : 'Oficina');

            $asunto = $plantilla[0]["asunto"];
            $cuerpo = $plantilla[0]["cuerpo"];
            $asunto = str_replace("[|numero_pedido|]", $nroPedido, $asunto);
            $cuerpo = str_replace("[|nombre_cliente|]", $nombreCliente, $cuerpo);
            $cuerpo = str_replace("[|numero_pedido|]", $nroPedido, $cuerpo);
            $cuerpo = str_replace("[|agencia_destino|]", $agenciaDestino, $cuerpo);

            $items = '';
            foreach ($pedidoRecepcion['items'] as $index => $detalle) {

                $detallePedido = MovimientoBien::create()->obtenerMovimientoBienXId($detalle['movimientoBienId']);

                $dimensiones = '';
                if ($detallePedido[0]['tipo'] == 0) {
                    $dimensiones = number_format($detallePedido[0]['bien_alto'], 2) . ' x '
                        . number_format($detallePedido[0]['bien_ancho'], 2) . ' x '
                        . number_format($detallePedido[0]['bien_longitud'], 2) . ' ('
                        . number_format($detallePedido[0]['bien_peso'], 2) . ' kg) ';
                }

                //Lógica de los items
                $items .= '<table
                        width="100%"
                        style="
                          text-align: left;
                          padding-top: 16px;
                          padding-bottom: 16px;
                        "
                      >
                        <tbody>
                          <tr>
                            <td width="74" valign="middle">
                              <img
                                src="https://img.freepik.com/vector-premium/maqueta-caja-carton-cerrada-paquete-paquete-sellado-estilo-realista-aislado-sobre-fondo-blanco_533410-140.jpg"
                                width="74"
                                height="25px"
                                style="display: block"
                              />
                            </td>
                            <td width="20" valign="bottom">&nbsp;</td>
                            <td valign="middle">
                              <p
                                style="
                                  width: 100%;
                                  color: #767676;
                                  font-size: 14px;
                                  font-weight: 400;
                                  line-height: 140% !important;
                                  margin: 0 !important;
                                  letter-spacing: 0;
                                  text-transform: uppercase;
                                  letter-spacing: 0.5px;
                                "
                              >' .
                    ($detallePedido[0]['tipo'] == 0 ? 'Por medida' : 'Artículo')
                    . '</p>
                              <p
                                style="
                                  width: 100%;
                                  color: #333333;
                                  font-size: 14px;
                                  font-weight: bold;

                                  line-height: 140% !important;
                                  margin: 0 !important;
                                  letter-spacing: 0;
                                "
                              >' .
                    $detallePedido[0]['bien_descripcion'] . ' ' . $dimensiones
                    . '</p>
                              <p
                                style="
                                  width: 100%;
                                  color: #333333;
                                  font-size: 14px;
                                  font-weight: 400;
                                  line-height: 140% !important;
                                  margin: 0 !important;
                                  letter-spacing: 0;
                                "
                              ></p>
                              <p
                                style="
                                  width: 100%;
                                  color: #767676;
                                  font-size: 14px;
                                  font-weight: 400;
                                  line-height: 140% !important;
                                  margin: 0 !important;
                                  letter-spacing: 0;
                                "
                              >
                                Código: ' . $detallePedido[0]['bien_codigo'] .
                    '</p>
                            </td>
                            <td width="20" valign="bottom">&nbsp;</td>
                            <td valign="bottom">
                              <p
                                style="
                                  color: #333333;
                                  font-size: 16px;
                                  font-weight: 400;

                                  line-height: 140% !important;
                                  margin: 0 !important;
                                  letter-spacing: 0;
                                  min-width: 85px;
                                  text-align: right;
                                "
                              >' .
                    number_format($detalle['cantidad'], 0) . ' ' . $detallePedido[0]['simbolo']
                    . '</p>
                            </td>
                          </tr>
                        </tbody>
                      </table>';
            }

            $cuerpo = str_replace("[|detalle_pedido|]", $items, $cuerpo);
            EmailEnvioNegocio::create()->insertarEmailEnvio($correos, $asunto, $cuerpo, 1, $usuarioId);
        }
    }

    public function enviarCorreoDespachoPedido($documentoId, $usuarioId)
    {

        $manifiestos = Documento::create()->obtenerDocumentoRelacionadoxDocumento($documentoId);

        foreach ($manifiestos as $doc) {

            $arrayPedidos = $this->obtenerArrayPedidosXDocumentoId($doc['documento_relacionado_id']);

            //Después de tener el "ArrayPedidos" listo, implementamos la lógica del envío de correos electrónicos.
            $plantilla = EmailPlantillaNegocio::create()->obtenerEmailPlantillaXID(29);
            foreach ($arrayPedidos as $pedidoDespacho) {

                $datosDocPedido = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($pedidoDespacho['pedidoId']);

                $destinatario = $plantilla[0]["destinatario"];
                $correosPlantilla = EmailPlantillaNegocio::create()->obtenerEmailXDestinatario($destinatario, $documentoId, null);

                $correos = '';
                foreach ($correosPlantilla as $email) {
                    $correos = $correos . $email . ';';
                }

                $nroPedido = $datosDocPedido[0]['serie_numero'];
                $nombreCliente = $datosDocPedido[0]['persona_nombre_e'];
                $asunto = $plantilla[0]["asunto"];
                $cuerpo = $plantilla[0]["cuerpo"];
                $cuerpo = str_replace("[|nombre_cliente|]", $nombreCliente, $cuerpo);
                $cuerpo = str_replace("[|numero_pedido|]", $nroPedido, $cuerpo);

                $items = '';
                foreach ($pedidoDespacho['items'] as $index => $detalle) {

                    $detallePedido = MovimientoBien::create()->obtenerMovimientoBienXId($detalle['movimientoBienId']);

                    $dimensiones = '';
                    if ($detallePedido[0]['tipo'] == 0) {
                        $dimensiones = number_format($detallePedido[0]['bien_alto'], 2) . ' x '
                            . number_format($detallePedido[0]['bien_ancho'], 2) . ' x '
                            . number_format($detallePedido[0]['bien_longitud'], 2) . ' ('
                            . number_format($detallePedido[0]['bien_peso'], 2) . ' kg) ';
                    }

                    //Lógica de los items
                    $items .= '<table
                        width="100%"
                        style="
                          text-align: left;
                          padding-top: 16px;
                          padding-bottom: 16px;
                        "
                      >
                        <tbody>
                          <tr>
                            <td width="74" valign="middle">
                              <img
                                src="https://img.freepik.com/vector-premium/maqueta-caja-carton-cerrada-paquete-paquete-sellado-estilo-realista-aislado-sobre-fondo-blanco_533410-140.jpg"
                                width="74"
                                height="25px"
                                style="display: block"
                              />
                            </td>
                            <td width="20" valign="bottom">&nbsp;</td>
                            <td valign="middle">
                              <p
                                style="
                                  width: 100%;
                                  color: #767676;
                                  font-size: 14px;
                                  font-weight: 400;
                                  line-height: 140% !important;
                                  margin: 0 !important;
                                  letter-spacing: 0;
                                  text-transform: uppercase;
                                  letter-spacing: 0.5px;
                                "
                              >' .
                        ($detallePedido[0]['tipo'] == 0 ? 'Por medida' : 'Artículo')
                        . '</p>
                              <p
                                style="
                                  width: 100%;
                                  color: #333333;
                                  font-size: 14px;
                                  font-weight: bold;

                                  line-height: 140% !important;
                                  margin: 0 !important;
                                  letter-spacing: 0;
                                "
                              >' .
                        $detallePedido[0]['bien_descripcion'] . ' ' . $dimensiones
                        . '</p>
                              <p
                                style="
                                  width: 100%;
                                  color: #333333;
                                  font-size: 14px;
                                  font-weight: 400;
                                  line-height: 140% !important;
                                  margin: 0 !important;
                                  letter-spacing: 0;
                                "
                              ></p>
                              <p
                                style="
                                  width: 100%;
                                  color: #767676;
                                  font-size: 14px;
                                  font-weight: 400;
                                  line-height: 140% !important;
                                  margin: 0 !important;
                                  letter-spacing: 0;
                                "
                              >
                                Código: ' . $detallePedido[0]['bien_codigo'] .
                        '</p>
                            </td>
                            <td width="20" valign="bottom">&nbsp;</td>
                            <td valign="bottom">
                              <p
                                style="
                                  color: #333333;
                                  font-size: 16px;
                                  font-weight: 400;

                                  line-height: 140% !important;
                                  margin: 0 !important;
                                  letter-spacing: 0;
                                  min-width: 85px;
                                  text-align: right;
                                "
                              >' .
                        number_format($detalle['cantidad'], 0) . ' ' . $detallePedido[0]['simbolo']
                        . '</p>
                            </td>
                          </tr>
                        </tbody>
                      </table>';
                }

                $cuerpo = str_replace("[|detalle_pedido|]", $items, $cuerpo);
                EmailEnvioNegocio::create()->insertarEmailEnvio($correos, $asunto, $cuerpo, 1, $usuarioId);
            }
        }
    }

    public function enviarCorreoRepartoPedido($documentoId, $usuarioId)
    {

        $arrayPedidos = $this->obtenerArrayPedidosXDocumentoId($documentoId);

        //Después de tener el "ArrayPedidos" listo, implementamos la lógica del envío de correos electrónicos.
        $plantilla = EmailPlantillaNegocio::create()->obtenerEmailPlantillaXID(28);
        foreach ($arrayPedidos as $pedidoReparto) {

            $datosDocPedido = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($pedidoReparto['pedidoId']);

            $destinatario = $plantilla[0]["destinatario"];
            $correosPlantilla = EmailPlantillaNegocio::create()->obtenerEmailXDestinatario($destinatario, $documentoId, null);

            $correos = '';
            foreach ($correosPlantilla as $email) {
                $correos = $correos . $email . ';';
            }

            $nroPedido = $datosDocPedido[0]['serie_numero'];
            $nombreCliente = $datosDocPedido[0]['persona_nombre_e'];
            $direccionDestino = $datosDocPedido[0]['persona_direccion_destino'];

            $asunto = $plantilla[0]["asunto"];
            $cuerpo = $plantilla[0]["cuerpo"];
            $asunto = str_replace("[|numero_pedido|]", $nroPedido, $asunto);
            $cuerpo = str_replace("[|nombre_cliente|]", $nombreCliente, $cuerpo);
            $cuerpo = str_replace("[|numero_pedido|]", $nroPedido, $cuerpo);
            $cuerpo = str_replace("[|direccion_destino|]", $direccionDestino, $cuerpo);

            $items = '';
            foreach ($pedidoReparto['items'] as $index => $detalle) {

                $detallePedido = MovimientoBien::create()->obtenerMovimientoBienXId($detalle['movimientoBienId']);

                $dimensiones = '';
                if ($detallePedido[0]['tipo'] == 0) {
                    $dimensiones = number_format($detallePedido[0]['bien_alto'], 2) . ' x '
                        . number_format($detallePedido[0]['bien_ancho'], 2) . ' x '
                        . number_format($detallePedido[0]['bien_longitud'], 2) . ' ('
                        . number_format($detallePedido[0]['bien_peso'], 2) . ' kg) ';
                }

                //Lógica de los items
                $items .= '<table
                        width="100%"
                        style="
                          text-align: left;
                          padding-top: 16px;
                          padding-bottom: 16px;
                        "
                      >
                        <tbody>
                          <tr>
                            <td width="74" valign="middle">
                              <img
                                src="https://img.freepik.com/vector-premium/maqueta-caja-carton-cerrada-paquete-paquete-sellado-estilo-realista-aislado-sobre-fondo-blanco_533410-140.jpg"
                                width="74"
                                height="25px"
                                style="display: block"
                              />
                            </td>
                            <td width="20" valign="bottom">&nbsp;</td>
                            <td valign="middle">
                              <p
                                style="
                                  width: 100%;
                                  color: #767676;
                                  font-size: 14px;
                                  font-weight: 400;
                                  line-height: 140% !important;
                                  margin: 0 !important;
                                  letter-spacing: 0;
                                  text-transform: uppercase;
                                  letter-spacing: 0.5px;
                                "
                              >' .
                    ($detallePedido[0]['tipo'] == 0 ? 'Por medida' : 'Artículo')
                    . '</p>
                              <p
                                style="
                                  width: 100%;
                                  color: #333333;
                                  font-size: 14px;
                                  font-weight: bold;

                                  line-height: 140% !important;
                                  margin: 0 !important;
                                  letter-spacing: 0;
                                "
                              >' .
                    $detallePedido[0]['bien_descripcion'] . ' ' . $dimensiones
                    . '</p>
                              <p
                                style="
                                  width: 100%;
                                  color: #333333;
                                  font-size: 14px;
                                  font-weight: 400;
                                  line-height: 140% !important;
                                  margin: 0 !important;
                                  letter-spacing: 0;
                                "
                              ></p>
                              <p
                                style="
                                  width: 100%;
                                  color: #767676;
                                  font-size: 14px;
                                  font-weight: 400;
                                  line-height: 140% !important;
                                  margin: 0 !important;
                                  letter-spacing: 0;
                                "
                              >
                                Código: ' . $detallePedido[0]['bien_codigo'] .
                    '</p>
                            </td>
                            <td width="20" valign="bottom">&nbsp;</td>
                            <td valign="bottom">
                              <p
                                style="
                                  color: #333333;
                                  font-size: 16px;
                                  font-weight: 400;

                                  line-height: 140% !important;
                                  margin: 0 !important;
                                  letter-spacing: 0;
                                  min-width: 85px;
                                  text-align: right;
                                "
                              >' .
                    number_format($detalle['cantidad'], 0) . ' ' . $detallePedido[0]['simbolo']
                    . '</p>
                            </td>
                          </tr>
                        </tbody>
                      </table>';
            }

            $cuerpo = str_replace("[|detalle_pedido|]", $items, $cuerpo);
            EmailEnvioNegocio::create()->insertarEmailEnvio($correos, $asunto, $cuerpo, 1, $usuarioId);
        }
    }

    public function enviarCorreoPedidoEntregado($documentoId, $usuarioId)
    {

        $arrayPedidos = $this->obtenerArrayPedidosXDocumentoId($documentoId);

        //Después de tener el "ArrayPedidos" listo, implementamos la lógica del envío de correos electrónicos.
        $plantilla = EmailPlantillaNegocio::create()->obtenerEmailPlantillaXID(27);
        foreach ($arrayPedidos as $pedidoEntregado) {

            $datosDocPedido = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($pedidoEntregado['pedidoId']);

            $destinatario = $plantilla[0]["destinatario"];
            $correosPlantilla = EmailPlantillaNegocio::create()->obtenerEmailXDestinatario($destinatario, $documentoId, null);

            $correos = '';
            foreach ($correosPlantilla as $email) {
                $correos = $correos . $email . ';';
            }

            $nroPedido = $datosDocPedido[0]['serie_numero'];
            $nombreCliente = $datosDocPedido[0]['persona_nombre_e'];

            $asunto = $plantilla[0]["asunto"];
            $cuerpo = $plantilla[0]["cuerpo"];
            $asunto = str_replace("[|numero_pedido|]", $nroPedido, $asunto);
            $cuerpo = str_replace("[|nombre_cliente|]", $nombreCliente, $cuerpo);
            $cuerpo = str_replace("[|numero_pedido|]", $nroPedido, $cuerpo);

            $items = '';
            foreach ($pedidoEntregado['items'] as $index => $detalle) {

                $detallePedido = MovimientoBien::create()->obtenerMovimientoBienXId($detalle['movimientoBienId']);

                $dimensiones = '';
                if ($detallePedido[0]['tipo'] == 0) {
                    $dimensiones = number_format($detallePedido[0]['bien_alto'], 2) . ' x '
                        . number_format($detallePedido[0]['bien_ancho'], 2) . ' x '
                        . number_format($detallePedido[0]['bien_longitud'], 2) . ' ('
                        . number_format($detallePedido[0]['bien_peso'], 2) . ' kg) ';
                }

                //Lógica de los items
                $items .= '<table
                        width="100%"
                        style="
                          text-align: left;
                          padding-top: 16px;
                          padding-bottom: 16px;
                        "
                      >
                        <tbody>
                          <tr>
                            <td width="74" valign="middle">
                              <img
                                src="https://img.freepik.com/vector-premium/maqueta-caja-carton-cerrada-paquete-paquete-sellado-estilo-realista-aislado-sobre-fondo-blanco_533410-140.jpg"
                                width="74"
                                height="25px"
                                style="display: block"
                              />
                            </td>
                            <td width="20" valign="bottom">&nbsp;</td>
                            <td valign="middle">
                              <p
                                style="
                                  width: 100%;
                                  color: #767676;
                                  font-size: 14px;
                                  font-weight: 400;
                                  line-height: 140% !important;
                                  margin: 0 !important;
                                  letter-spacing: 0;
                                  text-transform: uppercase;
                                  letter-spacing: 0.5px;
                                "
                              >' .
                    ($detallePedido[0]['tipo'] == 0 ? 'Por medida' : 'Artículo')
                    . '</p>
                              <p
                                style="
                                  width: 100%;
                                  color: #333333;
                                  font-size: 14px;
                                  font-weight: bold;

                                  line-height: 140% !important;
                                  margin: 0 !important;
                                  letter-spacing: 0;
                                "
                              >' .
                    $detallePedido[0]['bien_descripcion'] . ' ' . $dimensiones
                    . '</p>
                              <p
                                style="
                                  width: 100%;
                                  color: #333333;
                                  font-size: 14px;
                                  font-weight: 400;
                                  line-height: 140% !important;
                                  margin: 0 !important;
                                  letter-spacing: 0;
                                "
                              ></p>
                              <p
                                style="
                                  width: 100%;
                                  color: #767676;
                                  font-size: 14px;
                                  font-weight: 400;
                                  line-height: 140% !important;
                                  margin: 0 !important;
                                  letter-spacing: 0;
                                "
                              >
                                Código: ' . $detallePedido[0]['bien_codigo'] .
                    '</p>
                            </td>
                            <td width="20" valign="bottom">&nbsp;</td>
                            <td valign="bottom">
                              <p
                                style="
                                  color: #333333;
                                  font-size: 16px;
                                  font-weight: 400;

                                  line-height: 140% !important;
                                  margin: 0 !important;
                                  letter-spacing: 0;
                                  min-width: 85px;
                                  text-align: right;
                                "
                              >' .
                    number_format($detalle['cantidad'], 0) . ' ' . $detallePedido[0]['simbolo']
                    . '</p>
                            </td>
                          </tr>
                        </tbody>
                      </table>';
            }

            $cuerpo = str_replace("[|detalle_pedido|]", $items, $cuerpo);
            EmailEnvioNegocio::create()->insertarEmailEnvio($correos, $asunto, $cuerpo, 1, $usuarioId);
        }
    }

    public function obtenerArrayPedidosXDocumentoId($documentoId)
    {

        $array = array(); //array que almacena el 'PedidoId', 'movimientoBienId' y 'cantidad'
        $array = MovimientoBien::create()->obtenerPedidoDetalleXDocumentoId($documentoId);

        $arrayPedidos = array(); //array que agrupa cada pedido con su detalle
        if (!ObjectUtil::isEmpty($array)) {
            foreach ($array as $item) {
                $indicePedido = $this->buscarPedido($arrayPedidos, $item['pedido_id']);
                if ($indicePedido == -1) {
                    array_push($arrayPedidos, $this->obtenerArrayPedido($item['pedido_id'], $item['movimiento_bien_pedido'], $item['cantidad']));
                } else {
                    $arrayPedidos = $this->obtenerArrayPedidoDet($arrayPedidos, $indicePedido, $item['movimiento_bien_pedido'], $item['cantidad']);
                }
            }
        }
        return $arrayPedidos;
    }

    private function buscarPedido($arrayPedidos, $pedidoId)
    {

        if (ObjectUtil::isEmpty($arrayPedidos)) {
            return -1;
        } else {
            foreach ($arrayPedidos as $indicePedido => $doc) {
                if ($doc['pedidoId'] == $pedidoId) {
                    return $indicePedido;
                }
            }
        }
        return -1;
    }

    private function obtenerArrayPedido($pedidoId, $movimientoBienId, $cantidad)
    {
        $detalle = [];
        $items = array("movimientoBienId" => $movimientoBienId, "cantidad" => $cantidad);
        array_push($detalle, $items);
        $arrayPedidos = array("pedidoId" => $pedidoId, "items" => $detalle);
        return $arrayPedidos;
    }

    private function obtenerArrayPedidoDet($arrayPedidos, $indicePedido, $movimientoBienId, $cantidad)
    {
        array_push($arrayPedidos[$indicePedido]['items'], array("movimientoBienId" => $movimientoBienId, "cantidad" => $cantidad));
        return $arrayPedidos;
    }

    public function CambiarEstadoDespacho($despacho_id)
    {
        return Movimiento::create()->CambiarEstadoDespacho($despacho_id);
    }

    public function actualizarDocumentoManifiestoChofer($manifiestoId, $pilotoId, $copilotoId, $vehiculoId)
    {
        return Movimiento::create()->actualizarDocumentoManifiestoChofer($manifiestoId, $pilotoId, $copilotoId, $vehiculoId);
    }

    public function verificarManifiesto($manifiesto_id)
    {

        return Movimiento::create()->verificarManifiesto($manifiesto_id);
    }

    //    private function elementosUnicos($array) {
    //        $arraySinDuplicados = [];
    //        foreach ($array as $elemento) {
    //            if (!in_array($elemento, $arraySinDuplicados)) {
    //                $arraySinDuplicados[] = $elemento;
    //            }
    //        }
    //        return $arraySinDuplicados;
    //    }

    public function obtenerConfiguracionesInicialesGuiaRT($usuarioId)
    {
        $data = new ObjectUtil();
        $data->bus = VehiculoNegocio::create()->getDataVehiculo();
        $data->agenciaUser = Agencia::create()->getDataAgenciaUser($usuarioId);
        $data->origen = Agencia::create()->getDataAgencia();
        $data->choferes = Agencia::create()->getDataConductores();
        //$data->manifiesto = Movimiento::create()->getGuiaRT(null, null, null, null, null, null);
        $data->guia = Movimiento::create()->getGuiaRT(null, null, null, null, null, null);
        return $data;
    }

    public function obtenertablaGuia($bus_id, $fecha_salida, $conductor_id, $origin_id, $destino_id, $nro_guia)
    {
        $data = Movimiento::create()->getGuiaRT($bus_id, $fecha_salida, $conductor_id, $origin_id, $destino_id, $nro_guia);
        return $data;
    }

    public function visualizarDocumentoGuiaRT($guia_id)
    {
        $data = Movimiento::create()->getDocumentoGuiaRT($guia_id);
        return $data;
    }

    public function anularDocumentoGuiaRT($guia_id)
    {
        $data = Movimiento::create()->anular_DocumentoGuiaRT($guia_id);
        return $data;
    }

    public function generarNotaCreditoRelacionada($documentoId, $usuarioId)
    {
        //GENERAMOS LA NOTA DE CRÉDITO AUTOMÁTICA
        $dataDocumento = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);
        $documentoTipo=$dataDocumento[0]['documento_tipo_id'];
        $movimientoId = $dataDocumento[0]["movimiento_id"];
        $documentoDetalle = MovimientoBien::create()->obtenerXIdMovimiento($movimientoId);
        $documento_tipo_conf = DocumentoTipoNegocio::create()->obtenerDocumentoTipoDato('61', $usuarioId, NULL,NULL,$documentoTipo);
        $comentario = 'Anulaci�n de la operaci�n. ';

        $camposDinamicos = array();
        $camposDinamicos[0]['id'] = 551;
        $camposDinamicos[0]['tipo'] = 5;
        $camposDinamicos[0]['opcional'] = 0;
        $camposDinamicos[0]['descripcion'] = "Cliente";
        $camposDinamicos[0]['valor'] = $dataDocumento[0]['persona_id'];

        $camposDinamicos[1]['id'] = 552;
        $camposDinamicos[1]['tipo'] = 7;
        $camposDinamicos[1]['opcional'] = 0;
        $camposDinamicos[1]['descripcion'] = "Serie";
        $camposDinamicos[1]['valor'] = $documento_tipo_conf[1]['cadena_defecto'];

        $camposDinamicos[2]['id'] = 553;
        $camposDinamicos[2]['tipo'] = 8;
        $camposDinamicos[2]['opcional'] = 0;
        $camposDinamicos[2]['descripcion'] = "Número";
        $camposDinamicos[2]['valor'] = $documento_tipo_conf[2]['data'];

        $camposDinamicos[3]['id'] = 554;
        $camposDinamicos[3]['tipo'] = 9;
        $camposDinamicos[3]['opcional'] = 0;
        $camposDinamicos[3]['descripcion'] = "Fecha de emisión";
        $camposDinamicos[3]['valor'] = $documento_tipo_conf[3]['data'];

        $camposDinamicos[4]['id'] = 560;
        $camposDinamicos[4]['tipo'] = 16;
        $camposDinamicos[4]['opcional'] = 0;
        $camposDinamicos[4]['descripcion'] = "Sub total";
        $camposDinamicos[4]['valor'] = $dataDocumento[0]['subtotal'];

        $camposDinamicos[5]['id'] = 561;
        $camposDinamicos[5]['tipo'] = 15;
        $camposDinamicos[5]['opcional'] = 0;
        $camposDinamicos[5]['descripcion'] = "IGV";
        $camposDinamicos[5]['valor'] = $dataDocumento[0]['igv'];

        $camposDinamicos[6]['id'] = 3015;
        $camposDinamicos[6]['tipo'] = 10;
        $camposDinamicos[6]['opcional'] = 0;
        $camposDinamicos[6]['descripcion'] = "Fecha de vencimiento";
        $camposDinamicos[6]['valor'] = $documento_tipo_conf[7]['data'];

        $camposDinamicos[7]['id'] = 559;
        $camposDinamicos[7]['tipo'] = 14;
        $camposDinamicos[7]['opcional'] = 0;
        $camposDinamicos[7]['descripcion'] = "Importe";
        $camposDinamicos[7]['valor'] = $dataDocumento[0]['total'];

        $camposDinamicos[8]['id'] = 3014;
        $camposDinamicos[8]['tipo'] = 25;
        $camposDinamicos[8]['opcional'] = 0;
        $camposDinamicos[8]['descripcion'] = "Forma de pago";
        $camposDinamicos[8]['valor'] = NULL;

        $$camposDinamicos[9]['id'] = 609;
        $$camposDinamicos[9]['tipo'] = 4;
        $$camposDinamicos[9]['opcional'] = 0;
        $$camposDinamicos[9]['descripcion'] = "Motivo de emisión";
        $$camposDinamicos[9]['valor'] = 46;

        $camposDinamicos[10]['id'] = 3080;
        $camposDinamicos[10]['tipo'] = 44;
        $camposDinamicos[10]['opcional'] = 0;
        $camposDinamicos[10]['descripcion'] = "Otros cargos";
        $camposDinamicos[10]['valor'] = $dataDocumento[0]['monto_otro_gasto'];

        $camposDinamicos[11]['id'] = 3081;
        $camposDinamicos[11]['tipo'] = 45;
        $camposDinamicos[11]['opcional'] = 0;
        $camposDinamicos[11]['descripcion'] = "Devolución cargo";
        $camposDinamicos[11]['valor'] = $dataDocumento[0]['monto_devolucion_gasto'];

        $camposDinamicos[12]['id'] = 3082;
        $camposDinamicos[12]['tipo'] = 46;
        $camposDinamicos[12]['opcional'] = 0;
        $camposDinamicos[12]['descripcion'] = "Costo reparto";
        $camposDinamicos[12]['valor'] = $dataDocumento[0]['monto_costo_reparto'];

        $camposDinamicos[13]['id'] = 3083;
        $camposDinamicos[13]['tipo'] = 47;
        $camposDinamicos[13]['opcional'] = 0;
        $camposDinamicos[13]['descripcion'] = "Ajuste precio";
        $camposDinamicos[13]['valor'] = $dataDocumento[0]['ajuste_precio'];

        $documentoARelacionar = array();
        $documentoARelacionar[0]['documentoId'] = $dataDocumento[0]['id'];
        $documentoARelacionar[0]['movimientoId'] = $dataDocumento[0]['movimiento_id'];
        $documentoARelacionar[0]['tipo'] = $dataDocumento[0]['documento_tipo_id'];
        $documentoARelacionar[0]['documentoPadreId'] = "";
        $documentoARelacionar[0]['detalleLink'] = $dataDocumento[0]['documento_tipo_descripcion'] . ": " . $dataDocumento[0]['serie_numero'];
        $documentoARelacionar[0]['posicion'] = 0;

        $detalle = array();
        foreach ($documentoDetalle as $index => $det) {
            $item = array();
            $item['bienId'] = $det['bien_id'];
            $item['bienDesc'] = $det['bien_descripcion'];
            $item['cantidad'] = $det['cantidad'];
            $item['precio'] = $det['valor_monetario'];
            $item['subTotal'] = $det['sub_total'];
            $item['index'] = $index;
            array_push($detalle, $item);
        }

        $docAdicional = MovimientoNegocio::create()->validarGenerarDocumentoAdicional(
            '294',
            $usuarioId,
            '61',
            $camposDinamicos,
            $detalle,
            $documentoARelacionar,
            '1',
            $comentario,
            '1',
            $dataDocumento[0]['moneda_id'],
            'enviar',
            null,
            null,
            null,
            $dataDocumento[0]['periodo_id'],
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null
        );
        return $docAdicional;
    }

    public function generarNotaDebitoRelacionada($documentoId, $montoTotal, $usuarioId)
    {
        //GENERAMOS LA NOTA DE DEBITO AUTOMÁTICA
        $dataDocumento = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);

        $dataDocumentoTipoDato = DocumentoTipoNegocio::create()->obtenerDocumentoTipoDato('269', $usuarioId, $dataDocumento[0]['agencia_id']);
        $comentario = 'Ajuste por el exceso de kg en el envió de paquetes.';
        foreach ($dataDocumentoTipoDato as $itemDtd) {
            // asignamos la data necesaria para que cargue los componentes
            switch ($itemDtd["tipo"]) {
                case DocumentoTipoNegocio::DATO_SERIE:
                    $serie = $itemDtd["data"];
                    break;
                case DocumentoTipoNegocio::DATO_NUMERO:
                    $numero = $itemDtd["data"];
                    break;
                case DocumentoTipoNegocio::DATO_FECHA_EMISION:
                    $fechaEmision = $itemDtd["data"];
                    break;
            }
        }

        $fechaEmisionDb = date('Y-m-d');
        $dataPeriodo = PeriodoNegocio::create()->obtenerPeriodoIdXFechas($fechaEmisionDb);
        $periodoId = $dataPeriodo[0]['id'];

        $montoIgv = (Configuraciones::IGV_PORCENTAJE / 100) * $montoTotal;
        $montoSubTotal = round($montoTotal - $montoIgv, 6);

        $camposDinamicos = array();
        $itemDocumentoTipoDato = array();
        $itemDocumentoTipoDato['id'] = 2910;
        $itemDocumentoTipoDato['tipo'] = 5;
        $itemDocumentoTipoDato['opcional'] = 0;
        $itemDocumentoTipoDato['descripcion'] = "Cliente";
        $itemDocumentoTipoDato['valor'] = $dataDocumento[0]['persona_id'];
        $camposDinamicos[] = $itemDocumentoTipoDato;

        $itemDocumentoTipoDato['id'] = 2911;
        $itemDocumentoTipoDato['tipo'] = 7;
        $itemDocumentoTipoDato['opcional'] = 0;
        $itemDocumentoTipoDato['descripcion'] = "Serie";
        $itemDocumentoTipoDato['valor'] = $serie;
        $camposDinamicos[] = $itemDocumentoTipoDato;

        $itemDocumentoTipoDato['id'] = 2912;
        $itemDocumentoTipoDato['tipo'] = 8;
        $itemDocumentoTipoDato['opcional'] = 0;
        $itemDocumentoTipoDato['descripcion'] = "Número";
        $itemDocumentoTipoDato['valor'] = $numero;
        $camposDinamicos[] = $itemDocumentoTipoDato;

        $itemDocumentoTipoDato['id'] = 2913;
        $itemDocumentoTipoDato['tipo'] = 9;
        $itemDocumentoTipoDato['opcional'] = 0;
        $itemDocumentoTipoDato['descripcion'] = "Fecha de emisión";
        $itemDocumentoTipoDato['valor'] = $fechaEmision;
        $camposDinamicos[] = $itemDocumentoTipoDato;

        $itemDocumentoTipoDato['id'] = 2914;
        $itemDocumentoTipoDato['tipo'] = 16;
        $itemDocumentoTipoDato['opcional'] = 0;
        $itemDocumentoTipoDato['descripcion'] = "Sub total";
        $itemDocumentoTipoDato['valor'] = $montoSubTotal;
        $camposDinamicos[] = $itemDocumentoTipoDato;

        $itemDocumentoTipoDato['id'] = 2915;
        $itemDocumentoTipoDato['tipo'] = 15;
        $itemDocumentoTipoDato['opcional'] = 0;
        $itemDocumentoTipoDato['descripcion'] = "IGV";
        $itemDocumentoTipoDato['valor'] = $montoIgv;
        $camposDinamicos[] = $itemDocumentoTipoDato;

        //        $camposDinamicos[6]['id'] = 3015;
        //        $camposDinamicos[6]['tipo'] = 10;
        //        $camposDinamicos[6]['opcional'] = 0;
        //        $camposDinamicos[6]['descripcion'] = "Fecha de vencimiento";
        //        $camposDinamicos[6]['valor'] = $documento_tipo_conf[7]['data'];

        $itemDocumentoTipoDato['id'] = 2916;
        $itemDocumentoTipoDato['tipo'] = 14;
        $itemDocumentoTipoDato['opcional'] = 0;
        $itemDocumentoTipoDato['descripcion'] = "Importe";
        $itemDocumentoTipoDato['valor'] = $montoTotal;
        $camposDinamicos[] = $itemDocumentoTipoDato;

        $itemDocumentoTipoDato['id'] = 2917;
        $itemDocumentoTipoDato['tipo'] = 4;
        $itemDocumentoTipoDato['opcional'] = 0;
        $itemDocumentoTipoDato['descripcion'] = "Motivo de emisión";
        $itemDocumentoTipoDato['valor'] = 380;
        $camposDinamicos[] = $itemDocumentoTipoDato;

        $documentoARelacionar = array();
        $documentoARelacionar[0]['documentoId'] = $dataDocumento[0]['id'];
        $documentoARelacionar[0]['movimientoId'] = $dataDocumento[0]['movimiento_id'];
        //$documentoARelacionar[0]['tipo'] = $dataDocumento[0]['documento_tipo_id'];
        $documentoARelacionar[0]['documentoPadreId'] = "";
        // $documentoARelacionar[0]['detalleLink'] = $dataDocumento[0]['documento_tipo_descripcion'] . ": " . $dataDocumento[0]['serie_numero'];
        // $documentoARelacionar[0]['posicion'] = 0;

        $item = array();
        $item['bienId'] = -2;
        $item['bienDesc'] = $comentario;
        $item['comentarioDetalle'] = $comentario;
        $item['cantidad'] = 1;
        $item['precio'] = $montoTotal;
        $item['subTotal'] = $montoTotal;
        $item['index'] = 0;
        $detalle = array($item);

        $docAdicional = MovimientoNegocio::create()->validarGenerarDocumentoAdicional(
            '323',
            $usuarioId,
            '269',
            $camposDinamicos,
            $detalle,
            $documentoARelacionar,
            '1',
            $comentario,
            '1',
            $dataDocumento[0]['moneda_id'],
            'enviar',
            null,
            null,
            null,
            $periodoId,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null
        );
        return $docAdicional;
    }

//add liquidacion reparto
    public function ObtenerLiquidacionReporte($repartoserie,$repartonumero)
    {
        $data = Movimiento::create()->getLiquidacionReporte($repartoserie,$repartonumero);
        return $data;
    }


    
}
