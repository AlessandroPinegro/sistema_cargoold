<?php

require_once __DIR__ . '/../../modelo/almacen/ProgramacionPagoConfiguracion.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/../../modeloNegocio/commons/TablaNegocio.php';
require_once __DIR__ . '/PersonaNegocio.php';
require_once __DIR__ . '/../../modelo/almacen/BienTipo.php';

class ProgramacionPagoConfiguracionNegocio extends ModeloNegocioBase {

    /**
     * 
     * @return ProgramacionPagoConfiguracionNegocio
     */
    static function create() {
        return parent::create();
    }

    public function listarProgramacionPagoConfiguracion() {
        return ProgramacionPagoConfiguracion::create()->listarProgramacionPagoConfiguracion();
    }

    public function obtenerConfiguracionesIniciales($ppagoConfiguracionId) {
        $respuesta->dataProveedor = PersonaNegocio::create()->obtenerComboPersonaXPersonaClaseId(17);
        $respuesta->dataBienTipo = BienTipo::create()->obtener();
        $respuesta->dataIndicador = TablaNegocio::create()->obtenerXPadreId(61);
        
        if (!ObjectUtil::isEmpty($ppagoConfiguracionId)) {
            $respuesta->dataProgramacionPago = ProgramacionPagoConfiguracion::create()->obtenerProgramacionPagoConfiguracionXId($ppagoConfiguracionId);
            $respuesta->dataProgramacionPagoDetalle = ProgramacionPagoConfiguracion::create()->obtenerProgramacionPagoConfiguracionDetalleXId($ppagoConfiguracionId);
        }

        return $respuesta;
    }

    public function guardarProgramacionPagoConfiguracion(
    $programacionPagoConfiguracionId, $descripcion, $proveedorId, $grupoProducto, $listaProgramacionPagoDetalle, $listaProgramacionPagoDetalleEliminado, $comentario, $usuCreacion) {

        //INSERTO LA CABECERA (ppago_configuracion)
        $res = ProgramacionPagoConfiguracion::create()->guardarProgramacionPagoConfiguracion($programacionPagoConfiguracionId, $descripcion, $proveedorId, $comentario, $usuCreacion);

        if ($res[0]['vout_exito'] == 1) {
            $ppId = $res[0]['id'];

            //INSERTO LOS GRUPOS DE PRODUCTO (ppago_configuracion_bien_tipo)
            //ELIMINO TODO ESTADO A CERO 
            if (!ObjectUtil::isEmpty($grupoProducto)) {
                $res2 = ProgramacionPagoConfiguracion::create()->eliminarProgramacionPagoConfiguracionBienTipo($ppId);
            }
            //GUARDO GRUPOS DE PRODUCTO
            foreach ($grupoProducto as $itemBienTipoId) {
                $res3 = ProgramacionPagoConfiguracion::create()->guardarProgramacionPagoConfiguracionBienTipo($ppId, $itemBienTipoId, $usuCreacion);
            }

            //INSERTO EL DETALLE (ppago_configuracion_detalle)
            if (!ObjectUtil::isEmpty($listaProgramacionPagoDetalle)) {
                foreach ($listaProgramacionPagoDetalle as $item) {
                    $resDet = ProgramacionPagoConfiguracion::create()->guardarProgramacionPagoConfiguracionDetalle(
                            $ppId, $item['programacionPagoDetalleId'], $item['indicadorId'], $item['dias'], $item['porcentaje'], $usuCreacion);
                }
            }
            //ELIMINO EL DETALLE
            if (!ObjectUtil::isEmpty($listaProgramacionPagoDetalleEliminado)) {
                foreach ($listaProgramacionPagoDetalleEliminado as $valor) {
                    ProgramacionPagoConfiguracion::create()->eliminarProgramacionPagoConfiguracionDetalle($valor);
                }
            }
        }

        return $res;
    }
    
    public function actualizarEstadoProgramacionPagoConfiguracion($ppagoId,$estado){        
        return ProgramacionPagoConfiguracion::create()->actualizarEstadoProgramacionPagoConfiguracion($ppagoId,$estado);
    }

}
