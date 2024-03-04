<?php

require_once __DIR__ . '/AlmacenIndexControlador.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/DocumentoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/DocumentoTipoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/DocumentoTipoDatoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/PersonaNegocio.php';
require_once __DIR__ . '/../../util/ImportacionExcel.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/ExcelNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/EmailEnvioNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/EmailPlantillaNegocio.php';

class DocumentoControlador extends AlmacenIndexControlador {

    public function obtenerDocumentoTipoDatoXOpcionId(){
        $opcionId = $this->getOpcionId();
        return DocumentoTipoNegocio::create()->obtenerDocumentoTipoDatoXOpcionId($opcionId);
    }
    
    public function obtenerConfiguracionesIniciales() {
        $opcionId = $this->getOpcionId();
        $empresaId = $this->getParametro("empresaId");
        //$data=MovimientoNegocio::create()->obtenerConfiguracionInicial($opcionId, $empresaId);
        return MovimientoNegocio::create()->obtenerConfiguracionInicial($opcionId, $empresaId);
    }

    public function enviar() {
        $opcionId = $this->getOpcionId();
        $usuarioId = $this->getUsuarioId();
        $documentoTipoId = $this->getParametro("documentoTipoId");
        $camposDinamicos = $this->getParametro("camposDinamicos");
        $detalle = $this->getParametro("detalle");
        $documentoARelacionar = $this->getParametro("documentoARelacionar");
        $valorCheck = $this->getParametro("valor_check");
        $comentario = $this->getParametro("comentario");
        $this->setTransaction();
        
        return MovimientoNegocio::create()->validarBienesFaltantes($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $documentoARelacionar, $valorCheck,$comentario);
        //return MovimientoNegocio::create()->guardar($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle, $documentoARelacionar, $valorCheck,$comentario);
    }
    
    public function obtenerDocumentos() {
        // seccion de obtencion de variables
        $opcionId = $this->getOpcionId();
        $criterios = $this->getParametro("criterios");
        $elemntosFiltrados = $this->getParametro("length");
        $order = $this->getParametro("order");
        $columns = $this->getParametro("columns");
        $start = $this->getParametro("start");
        // seccion de consumir negocio
        $data = MovimientoNegocio::create()->obtenerDocumentosXCriterios($opcionId, $criterios, $elemntosFiltrados, $columns, $order, $start);
        $responseAcciones = MovimientoNegocio::create()->obtenerMovimientoTipoAcciones($opcionId);
        $response_cantidad_total = MovimientoNegocio::create()->obtenerCantidadDocumentosXCriterio($opcionId, $criterios, $elemntosFiltrados, $columns, $order, $start);

        // seccion de respuesta
        $response_cantidad_total[0]['total'];
        $elemntosFiltrados = $response_cantidad_total[0]['total'];
        $elementosTotales = $response_cantidad_total[0]['total'];
        $tamanio = count($data);

        for ($i = 0; $i < $tamanio; $i++) {
            $stringAcciones = '';
            for ($j = 0; $j < count($responseAcciones); $j++) {
                if (($data[$i]['documento_estado_id'] == 2 || $data[$i]['documento_estado_id'] == 3) && ($responseAcciones[$j]['id'] == 3 || $responseAcciones[$j]['id'] == 4) ) {
                    $stringAcciones.='';
                } elseif ((($data[$i]['documento_relacionado'] == 0) && ($responseAcciones[$j]['id'] == 5)) || (($data[$i]['documento_estado_id'] == 2) && ($responseAcciones[$j]['id'] == 5))) {
                    $stringAcciones.='';
                }else {
                    if($responseAcciones[$j]['id'] == 1)
                    {
                        $datoPivot = $data[$i]['documento_tipo_id'];
                    }  else {
                        $datoPivot = $data[$i]['movimiento_id'];
                    }
                    $stringAcciones .= "<a href='#' onclick='" . $responseAcciones[$j]['funcion'] . "(" . $data[$i]['documento_id'] . "," . $datoPivot . ")' title='" . $responseAcciones[$j]['descripcion'] . "'><b><i class='" . $responseAcciones[$j]['icono'] . "' style='color:" . $responseAcciones[$j]['color'] . "'></i><b></a>&nbsp;\n";
                }
            }
            $data[$i]['acciones'] = $stringAcciones;
        }

        return $this->obtenerRespuestaDataTable($data, $elemntosFiltrados, $elementosTotales);
    }

    public function obtenerDocumentoTipoDato() {
        $documentoTipoId = $this->getParametro("documentoTipoId");
        return DocumentoTipoNegocio::create()->obtenerDocumentoTipoDato($documentoTipoId);
    }

    public function enviarEImprimir() {

        $opcionId = $this->getOpcionId();
        $usuarioId = $this->getUsuarioId();
        $documentoTipoId = $this->getParametro("documentoTipoId");
        $camposDinamicos = $this->getParametro("camposDinamicos");
        $detalle = $this->getParametro("detalle");
        $this->setTransaction();
        return MovimientoNegocio::create()->enviarEImprimir($opcionId, $usuarioId, $documentoTipoId, $camposDinamicos, $detalle);
    }

    public function getAllPersona() {
        $documentoTipoId = $this->getParametro("documentoTipoId");
        return DocumentoTipoNegocio::create()->obtenerDocumentoTipoDato($documentoTipoId);
    }

    public function imprimir() {
        $documentoId = $this->getParametro("id");
        $documentoTipoId = $this->getParametro("documento_tipo_id");
        return MovimientoNegocio::create()->imprimir($documentoId, $documentoTipoId);
    }

    public function anular() {
        $documentoId = $this->getParametro("id");
        $documentoEstadoId = 2;
        $usuarioId = $this->getUsuarioId();
        return MovimientoNegocio::create()->anular($documentoId, $documentoEstadoId, $usuarioId);
    }

    public function aprobar() {
        $documentoId = $this->getParametro("id");
        $documentoEstadoId = 3;
        $usuarioId = $this->getUsuarioId();
        return MovimientoNegocio::create()->aprobar($documentoId, $documentoEstadoId, $usuarioId);
    }

    public function visualizarDocumento() {
        $documentoId = $this->getParametro("documento_id");
        $movimientoId = $this->getParametro("movimiento_id");
        //$data=MovimientoNegocio::create()->visualizarDocumento($documentoId, $movimientoId);
        return MovimientoNegocio::create()->visualizarDocumento($documentoId, $movimientoId);
    }
    
    public function exportarReporteExcel() {
        
        // seccion de obtencion de variables
        $opcionId = $this->getOpcionId();
        $criterios = $this->getParametro("criterios");
            
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
            $result = MovimientoNegocio::create()->importarExcelMovimiento($opcionId,$usuarioId,$xml, $usuCreacion);
            $errores = "";
            if (is_array($result)) {
                foreach ($result as $array) {
                    if (array_key_exists("cause", $array))
                        $errores .= "<li>Fila " . $array["row"] . ": " . $array["cause"] . "</li>";
                }
            }
            if ($errores !== "") {
                $errores = "No fue posible importar una o varias filas: <br><ul>$errores</ul>";                
                
                $this->setMensajeEmergente($errores,'',Configuraciones::MENSAJE_ERROR);
                
                return ['vout_exito'=>'0','vout_mensaje'=>'Errores'];
                //throw new WarningException($errores);                
            }else{
                $this->setMensajeEmergente("Importacion finalizada.");
                return ['vout_exito'=>'1','vout_mensaje'=>'Correcto'];
            }
                
                
        }   
    }
    
    public function obtenerPersonaDireccion() {
        
        $personaId = $this->getParametro("personaId");
        return PersonaNegocio::create()->obtenerPersonaDireccionXPersonaId($personaId);
        
    }
    
}
