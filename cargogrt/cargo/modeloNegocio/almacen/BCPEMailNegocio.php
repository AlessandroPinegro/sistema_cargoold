<?php
require_once __DIR__ . '/../../modelo/almacen/BCPEMail.php';
require_once __DIR__ . '/../../modelo/almacen/ProgramacionPago.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/../../modeloNegocio/commons/ConstantesNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/PagoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/DocumentoTipoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/PersonaNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/MonedaNegocio.php';

class BCPEMailNegocio extends ModeloNegocioBase {
    public $hasTransaction = false;
    /**
     * 
     * @return BCPEMailNegocio
     */
    static function create() {
        return parent::create();
    }
    
    public function obtenerConfiguracionInicialListado() {
        $respuesta->documento_tipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoPPago();
        $respuesta->persona_activa = PersonaNegocio::create()->obtenerActivas();
        $respuesta->moneda = MonedaNegocio::create()->obtenerComboMoneda();

        return $respuesta;
    }
    
    public function obtenerXCriterios($criterios, $elementosFiltrados, $columns, $order, $start) {
        $personaId = $criterios['personaId'];
        $documentoTipoId = $criterios['documentoTipoIds'];
        $serie = $criterios['serie'];
        $numero = $criterios['numero'];
        $monedaId = $criterios['monedaId'];
        $estado = $criterios['estado'];
        $transferencia = $criterios['transferencia'];
        $documentoTipoIdFormateado = Util::convertirArrayXCadena($documentoTipoId);

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];

        return BCPEMail::create()->obtenerXCriterios($documentoTipoIdFormateado, $personaId, $transferencia, $serie, $numero, $monedaId, $estado, $columnaOrdenar, $formaOrdenar, $elementosFiltrados, $start);
    }
    
    public function obtenerXCriteriosContador($criterios, $columns, $order) {
        $personaId = $criterios['personaId'];
        $documentoTipoId = $criterios['documentoTipoIds'];
        $serie = $criterios['serie'];
        $numero = $criterios['numero'];
        $monedaId = $criterios['monedaId'];
        $estado = $criterios['estado'];
        $transferencia = $criterios['transferencia'];
        $documentoTipoIdFormateado = Util::convertirArrayXCadena($documentoTipoId);

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];

        return BCPEMail::create()->obtenerXCriteriosContador($documentoTipoIdFormateado, $personaId, $transferencia, $serie, $numero, $monedaId, $estado, $columnaOrdenar, $formaOrdenar);
    }

    public function reintentarPagoBCP($bcpEmailId){
//        $this->extraerData($bcpEmailId);
        return $this->pagarProgramacionXBCPEMailId($bcpEmailId);
    }
    
    public function sincronizarPagoBCP($ipCliente, $correoId, $remitente, $asunto, $cuerpo, $fecha) {
        $this->hasTransaction = false;
        $bcpEmail = BCPEMail::create()->guardar($ipCliente, $correoId, $remitente, $asunto, $cuerpo, $fecha);
        if ($this->isEmpty($bcpEmail) || $bcpEmail[0]["vout_id"] <= 0){
            throw new WarningException("El e-mail $correoId ya fue registrado con anterioridad.");
        }
        $bcpEmailId = $bcpEmail[0]["vout_id"];
        $this->extraerData($bcpEmailId);
        $this->pagarProgramacionXBCPEMailId($bcpEmailId);
    }

    public function extraerData($bcpEmailId){
        $data = BCPEmail::create()->obtenerXId($bcpEmailId);
        if (!ObjectUtil::isEmpty($data)){
            $asunto = $data[0]["asunto"];
            $cuerpo = $data[0]["cuerpo"];
            
            if (ObjectUtil::isStringContent($asunto, "Pago de servicios")){
                $this->extraerDataPagoServicios($bcpEmailId, $cuerpo);
            }else if (ObjectUtil::isStringContent($asunto, "Transferencia")){
                $this->extraerDataTransferencia($bcpEmailId, $cuerpo);
            }
        }
    }
    
    private function extraerDataPagoServicios($bcpEmailId, $cuerpo){
        $proveedorNombre = trim(ObjectUtil::getStringBetween($cuerpo, "Empresa proveedora:", "Servicio:"));
        $pagoFecha = trim(ObjectUtil::getStringBetween($cuerpo, "Fecha y hora:", "Datos del pago:"));
//        $numeroOperacion = trim(ObjectUtil::getStringBetween($cuerpo, "Mensaje:", "Por favor no conteste a este correo."));
//        $numeroOperacion = str_replace("Sin mensaje", "", $numeroOperacion);
        $importe = trim(ObjectUtil::getStringBetween($cuerpo, "Monto total:", "Contravalor:"));
        $moneda = trim(substr($importe, 0, 3));
        $importe = trim(substr($importe, strlen($moneda)+1, strlen($importe)));
        $log = "";
        $monedaId = $this->decideMonedaId($moneda);
        if (ObjectUtil::isEmpty($monedaId)){
            $log = " - No se especificó una moneda válida";
        }
        $pagoFechaBD = $this->formatearFechaABD($pagoFecha);
        if (ObjectUtil::isEmpty($pagoFechaBD)){
            $log = $log." - No se especificó una fecha válida";
        }
//        if (ObjectUtil::isEmpty($numeroOperacion)){
//            $log = $log." - No se especificó un número de operación en el campo mensaje";
//        }

        BCPEMail::create()->actualizarExtraccion($bcpEmailId, $proveedorNombre, $pagoFecha, $pagoFechaBD, $moneda, $monedaId, $importe, "", $log);
    }
    
    private function extraerDataTransferencia($bcpEmailId, $cuerpo){
        $proveedorNombre = trim(ObjectUtil::getStringBetween($cuerpo, "Beneficiario:", "N° de cuenta:"));
        $pagoFecha = trim(ObjectUtil::getStringBetween($cuerpo, "Fecha y hora:", "Beneficiario:"));
//        $numeroOperacion = trim(ObjectUtil::getStringBetween($cuerpo, "Mensaje:", "Para verificar esta información,"));
//        $numeroOperacion = str_replace("Sin mensaje", "", $numeroOperacion);
        $importe = trim(ObjectUtil::getStringBetween($cuerpo, "Importe:", "Contravalor"));
//        throw new WarningException($importe);
        $moneda = trim(substr($importe, 0, 3));
        $importe = trim(substr($importe, strlen($moneda)+1, strlen($importe)));
        $log = "";
        $monedaId = $this->decideMonedaId($moneda);
        if (ObjectUtil::isEmpty($monedaId)){
            $log = " - No se especificó una moneda válida";
        }
        $pagoFechaBD = $this->formatearFechaABD($pagoFecha);
        if (ObjectUtil::isEmpty($pagoFechaBD)){
            $log = $log." - No se especificó una fecha válida";
        }
//        if (ObjectUtil::isEmpty($numeroOperacion)){
//            $log = $log." - No se especificó un número de operación en el campo mensaje";
//        }

        BCPEMail::create()->actualizarExtraccion($bcpEmailId, $proveedorNombre, $pagoFecha, $pagoFechaBD, $moneda, $monedaId, $importe, "", $log);
    }
    
    private function decideMonedaId ($moneda){
        if (ObjectUtil::isEmpty($moneda)) return null;
        switch(str_replace(".", "", $moneda)){
            case 'S/': 
                return 2;
            case 'US$':
                return 4;
            default:
                return null;
        }
    }
    
    private function formatearFechaABD($fecha){
        if (ObjectUtil::isEmpty($fecha)) return null;
        $meses = Array("01"=>"enero", "02"=>"febrero", "03"=>"marzo", "04"=>"abril", "05"=>"mayo", "06"=>"junio", "07"=>"julio", "08"=>"agosto", "09"=>"setiembre", "10"=>"octubre", "11"=>"noviembre", "12"=>"diciembre");
        foreach ($meses as $key=>$mes){
            if (strpos($fecha, $mes)){
                $dia = substr($fecha, 0, 2);
                $anio = substr($fecha, 10+strlen($mes), 4);
                return "$anio-$key-$dia";
            }
        }
        return null;
    }

    public function pagarProgramacionXBCPEMailId($bcpEmailId){
        $this->hasTransaction = false;
        $data = BCPEmail::create()->obtenerXId($bcpEmailId);
        if (!ObjectUtil::isEmpty($data)){
            if (ObjectUtil::isEmpty($data[0]["extraccion_proveedor_id"])){
                $logMensaje = "No se ha encontrado un proveedor válido";
                BCPEMail::create()->actualizarLog($bcpEmailId, $logMensaje);
                throw new WarningException($logMensaje);
            }else if (ObjectUtil::isEmpty($data[0]["extraccion_moneda_id"])){
                $logMensaje = "No se ha encontrado una moneda válida";
                BCPEMail::create()->actualizarLog($bcpEmailId, $logMensaje);
                throw new WarningException($logMensaje);
            }else if (ObjectUtil::isEmpty($data[0]["extraccion_importebd"])){
                $logMensaje = "No se ha encontrado un importe válido";
                BCPEMail::create()->actualizarLog($bcpEmailId, $logMensaje);
                throw new WarningException($logMensaje);
            }else if (ObjectUtil::isEmpty($data[0]["extraccion_fechabd"])){
                $logMensaje = "No se ha encontrado una fecha válida";
                BCPEMail::create()->actualizarLog($bcpEmailId, $logMensaje);
                throw new WarningException($logMensaje);
            }else{
//                $fechaActual = DateUtil::formatearCadenaACadenaBD(date("d/m/Y"));
//                $proveedorId = $data[0]["extraccion_proveedor_id"];
                $proveedor = $data[0]["extraccion_proveedor"];
                $monedaId = $data[0]["extraccion_moneda_id"];
                $numeroOperacion = $data[0]["extraccion_numero_operacion"];
                $importe = $data[0]["extraccion_importebd"];
                $fechaPago = substr($data[0]["extraccion_fechabd"], 0, 10);
                $periodoId = $data[0]["periodo_id"];
                $fechaPagoFormat = DateUtil::formatearBDACadena($fechaPago);
                $ppago = ProgramacionPago::create()->obtenerLiberadoPendientePagoXBCP($fechaPago, $proveedor, $monedaId, $importe);
                if (ObjectUtil::isEmpty($ppago)){
                    $logMensaje = "No se encontró ninguna programación de pago con las características especificadas";
                    BCPEMail::create()->actualizarLog($bcpEmailId, $logMensaje);
                    throw new WarningException($logMensaje);
                }
                $proveedorId = $ppago[0]["persona_id"];
                
                $this->beginTransaction();
                $this->hasTransaction = true;
                //echo "x$periodoId";
                $documentoPagoId = $this->guardarDocumentoPago($proveedorId, $fechaPagoFormat, $numeroOperacion, $monedaId, $importe, $periodoId);
                $this->aplicarDocumentoPago($ppago[0]["documento_id"], $proveedorId, $fechaPagoFormat, $monedaId, $importe, $documentoPagoId);
                BCPEMail::create()->actualizarEstado($bcpEmailId, $documentoPagoId, $ppago[0]["documento_id"]);
                $this->commitTransaction();
            }
        }else{
            $logMensaje = "No se encontró el registro de BCP Email específico";
            BCPEMail::create()->actualizarLog($bcpEmailId, $logMensaje);
            throw new WarningException($logMensaje);
        }
    }
    
    private function guardarDocumentoPago ($proveedorId, $fechaPago, $numeroOperacion, $monedaId, $importe, $periodoId){
        $documentoTipoId=144;
        $camposDinamicos = Array();
        $camposDinamicos[0]["id"]=1314;
        $camposDinamicos[0]["tipo"]=5;
        $camposDinamicos[0]["opcional"]=0;
//        $camposDinamicos[0]["descripcion"]=Titular proveedor
        $camposDinamicos[0]["valor"]=$proveedorId;
        
        $camposDinamicos[1]["id"]=1316;
        $camposDinamicos[1]["tipo"]=9;
        $camposDinamicos[1]["opcional"]=0;
//        $camposDinamicos[1]["descripcion"]=Fecha
        $camposDinamicos[1]["valor"]=  $fechaPago;
        
        $camposDinamicos[2]["id"]=1315;
        $camposDinamicos[2]["tipo"]=8;
        $camposDinamicos[2]["opcional"]=0;
//        $camposDinamicos[2]["descripcion"]=Número de operación
        $camposDinamicos[2]["valor"]=$numeroOperacion
                ;
        $camposDinamicos[3]["id"]=1320;
        $camposDinamicos[3]["tipo"]=2;
        $camposDinamicos[3]["opcional"]=1;
//        $camposDinamicos[3]["descripcion"]=Cuenta destino
        $camposDinamicos[3]["valor"]="";
        
        $camposDinamicos[4]["id"]=1317;
        $camposDinamicos[4]["tipo"]=14;
        $camposDinamicos[4]["opcional"]=0;
//        $camposDinamicos[4]["descripcion"]=Importe
        $camposDinamicos[4]["valor"]=$importe;
        
        $camposDinamicos[5]["id"]=1318;
        $camposDinamicos[5]["tipo"]=20;
        $camposDinamicos[5]["opcional"]=0;
//        $camposDinamicos[5]["descripcion"]=Cuenta origen
        $camposDinamicos[5]["valor"]=1; // BCP
        
        $camposDinamicos[6]["id"]=1319;
        $camposDinamicos[6]["tipo"]=21;
        $camposDinamicos[6]["opcional"]=0;
//        $camposDinamicos[6]["descripcion"]=Actividad
        $camposDinamicos[6]["valor"]=15; // Por definir
        
        return PagoNegocio::create()->guardar(null, 1, $documentoTipoId, $camposDinamicos, $monedaId, $periodoId);        
    }
    
    private function aplicarDocumentoPago($documentoId, $proveedorId, $fecha, $monedaId, $importe, $documentoPagoId){
        $empresaId = 2;
        $retencion = 1;
        $monedaPago = $monedaId;
        $dolares = ($monedaId==4)?1:0;
        $usuarioId = 1;
        
        $documentoAPagar = array(array(documentoId => $documentoId,
                                      tipoDocumento => '',
                                      numero => '',
                                      serie => '',
                                      pendiente => (float)$importe,
                                      total => (float)$importe,
                                      dolares => $dolares
                                    )
                                  );
        $documentoPagoConDocumento = array();
        $totalPagos = 0;
        
        array_push($documentoPagoConDocumento, 
                    array(
                            documentoId => $documentoPagoId,
                            tipoDocumento => '',
                            tipoDocumentoId => '',
                            numero => '',
                            serie => '',
                            pendiente => (float) $importe*1,
                            total => (float) $importe*1,
                            monto => (float) $importe*1,
                            dolares => $dolares
                        )
                    );

        // Como todo se hace en la misma moneda, setearemos el tc en 1 
        $tipoCambio = 1;
        $pago= PagoNegocio::create()->registrarPago($proveedorId, $fecha, $documentoAPagar, $documentoPagoConDocumento,
                $usuarioId, 0, $tipoCambio, $monedaPago, $retencion, $empresaId, null);
        
    }
    
    public function actualizarNumeroOperacion($bcpEMailId, $numeroOperacion){
        return $this->validateResponse(BCPEMail::create()->actualizarNumeroOperacion($bcpEMailId, $numeroOperacion));
    }
}
