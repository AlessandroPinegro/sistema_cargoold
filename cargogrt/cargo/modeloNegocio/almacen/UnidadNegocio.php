<?php

require_once __DIR__ . '/../../modelo/almacen/Unidad.php';
require_once __DIR__ . '/../../modelo/almacen/UnidadMedidaTipo.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/../../modelo/almacen/UnidadMedida.php';
require_once __DIR__ . '/../../modeloNegocio/contabilidad/SunatTablaNegocio.php';

class UnidadNegocio extends ModeloNegocioBase {

    /**
     * 
     * @return UnidadNegocio
     */
    static function create() {
        return parent::create();
    }
    
    public function getDataUnidadTipo($id_bandera) {
        $data = Unidad::create()->getDataUnidadTipo();
        $tamanio = count($data);
        for ($i = 0; $i < $tamanio; $i++) {
            if ($data[$i]['estado'] == 1) {
                $data[$i]['icono'] = "ion-checkmark-circled";
                $data[$i]['color'] = "#5cb85c";
            } else {
                $data[$i]['icono'] = "ion-flash-off";
                $data[$i]['color'] = "#cb2a2a";
            }
            if ($id_usuario != null) {
                $data[$i]['id_bandera'] = $id_bandera;
            }
            if ($data[$i]['unidad_medida_base'] == null || $data[$i]['unidad_medida_base'] == '') {
                $data[$i]['unidad_medida_base'] = '';
            }
        }
        return $data;
    }

    public function insertUnidadTipo($descripcion, $codigo, $comentario, $estado, $usu_creacion) {
        $response = Unidad::create()->insertUnidadTipo($descripcion, $codigo, $comentario, $estado, $usu_creacion);


//        if ($response[0]["vout_exito"] == 0) {
//            throw new WarningException($response[0]["vout_mensaje"]);
//        } else {
//            $this->setMensajeEmergente($response[0]["vout_mensaje"]);
//            return $response;
//        }
        return $response;
    }

    public function getUnidadTipo($id) {
        return Unidad::create()->getUnidadTipo($id);
    }

    public function updateUnidadTipo($id_uni_tipo, $descripcion, $codigo, $comentario, $estado) {
        $response = Unidad::create()->updateUnidadTipo($id_uni_tipo, $descripcion, $codigo, $comentario, $estado, -2);
        return $response;
    }

    public function deleteUnidadTipo($id_uni_tipo, $nom) {
        $response = Unidad::create()->deleteUnidadTipo($id_uni_tipo);
        $response[0]['nombre'] = $nom;
        return $response;
    }

    public function getDataComboUnidadTipo() {
        return Unidad::create()->getDataComboUnidadTipo();
    }

    public function cambiarTipoEstado($id_estado) {
        $data = Unidad::create()->cambiarTipoEstado($id_estado);
        $tamanio = count($data);
        for ($i = 0; $i < $tamanio; $i++) {
            if ($data[$i]['estado_nuevo'] == 1) {
                $data[$i]['icono'] = "ion-checkmark-circled";
                $data[$i]['color'] = "#5cb85c";
            } else {
                $data[$i]['icono'] = "ion-flash-off";
                $data[$i]['color'] = "#cb2a2a";
            }
        }
        return $data;
    }

    //////////////////////////////////////
    //unidades
    /////////////////////////////////////
    public function getDataUnidad($id_bandera) {
        $data = Unidad::create()->getDataUnidad();
        $tamanio = count($data);
        for ($i = 0; $i < $tamanio; $i++) {
            if ($data[$i]['estado'] == 1) {
                $data[$i]['icono'] = "ion-checkmark-circled";
                $data[$i]['color'] = "#5cb85c";
            } else {
                $data[$i]['icono'] = "ion-flash-off";
                $data[$i]['color'] = "#cb2a2a";
            }
            if ($data[$i]['unidad_medida_base'] == '' || $data[$i]['unidad_medida_base'] == null) {
                $data[$i]['unidad_medida_base'] = '';
            } else {
                $data[$i]['unidad_medida_base'] = 'fa fa-check-square';
            }
//            if ($id_bandera != null) {
            $data[$i]['id_bandera'] = '';
//            }
        }
        return $data;
    }

    public function getDataComboUnidadAlternativa($id_unidad) {
        $response = Unidad::create()->getUnidad($id_unidad);
        $data = Unidad::create()->getDataComboUnidadAlternativa($id_unidad, $response[0]['unidad_medida_tipo_id']);
        $tamanio = count($data);
        for ($i = 0; $i < $tamanio; $i++) {
//            if ($id_bandera != null) {
            $data[$i]['id_bandera'] = $id_bandera;
//            }
        }
        $data[0]['id_bandera'] = $id_bandera;
        return $data;
    }

    public function getDataComboUnidadBase() {
        $data = Unidad::create()->getDataComboUnidadBase();
        $tamanio = count($data);
        $j = 0;
        for ($i = 0; $i < $tamanio; $i++) {
            $data[$i]['id_bandera'] = $id_unidad;
            $j++;
        }
        return $data;
    }

    public function insertUnidad($descripcion, $codigo, $tipo, $simbolo, $estado, $usu_creacion, $unidad_base,$codigoSunatId) {
        
        $response = Unidad::create()->insertUnidad($descripcion, $codigo, $tipo, $simbolo, $estado,$usu_creacion,$codigoSunatId);
        if ($unidad_base == 1) {
            Unidad::create()->updateUnidadTipo($tipo, null, null, null, null, $response[0]['id']);
        }
        return $response;
    }

    public function getUnidad($id) {
        return Unidad::create()->getUnidad($id);
    }

    public function updateUnidad($id_uni, $descripcion, $codigo, $tipo, $simbolo, $estado, $unidad_base,$codigoSunatId) {
        $response = Unidad::create()->updateUnidad($id_uni, $descripcion, $codigo, $tipo, $simbolo, $estado,
                $unidad_base,$codigoSunatId);
        
        if ($response[0]['vout_exito'] != '0') {
            if ($unidad_base == 1) {
                Unidad::create()->updateUnidadTipo($tipo, null, null, null, null, $id_uni, $id_uni);
            } else {
                Unidad::create()->updateUnidadTipo(null, null, null, null, null, null, $id_uni);
            }
        }
        return $response;
    }

    public function deleteUnidad($id_uni, $nom) {
        $response = Unidad::create()->deleteUnidad($id_uni);
        $response[0]['nombre'] = $nom;
        return $response;
    }

    public function cambiarEstado($id_estado) {
        $data = Unidad::create()->cambiarEstado($id_estado);
        $tamanio = count($data);
        for ($i = 0; $i < $tamanio; $i++) {
            if ($data[$i]['estado_nuevo'] == 1) {
                $data[$i]['icono'] = "ion-checkmark-circled";
                $data[$i]['color'] = "#5cb85c";
            } else {
                $data[$i]['icono'] = "ion-flash-off";
                $data[$i]['color'] = "#cb2a2a";
            }
        }
        return $data;
    }

    public function validarAsignarUnidadBase($tipo_unidad, $tipo_accion, $unidadId) {
        if ($unidadId == null || $unidadId == '') {
            $unidadId = -1;
        }
        $response = Unidad::create()->validarAsignarUnidadBase($tipo_unidad, $unidadId);
        $response['0']['tipo_accion'] = $tipo_accion;
        return $response;
    }

    public function obtenerActivasXBien($bienId) {
        return Unidad::create()->obtenerActivasXBien($bienId);
    }

    public function obtenerUnidadMedidaTipo() {
        return UnidadMedidaTipo::create()->obtener();
    }
    public function obtenerUnidadMedidaTipoXId($id) {
        return UnidadMedidaTipo::create()->obtenerXId($id);
    }
    public function obtenerUnidadMedidaActivoXDescripcion($unidadMedidaDescripcion){
        return UnidadMedida::create()->obtenerUnidadMedidaActivoXDescripcion($unidadMedidaDescripcion);
    }
        
    public function obtenerUnidadMedidaEquivalenciaXIds($unidadIdBase,$unidadIdConvertir){
        return UnidadMedida::create()->obtenerUnidadMedidaEquivalenciaXIds($unidadIdBase,$unidadIdConvertir);
    }
    
    public function obtenerConfiguracionInicialUnidadTipo() {
        $codigo= UnidadMedidaTipo::create()->obtenerSiguienteCodigo();        
        $resultado->codigo=$codigo;        
        return $resultado;        
    }
    
    public function obtenerConfiguracionInicialUnidad(){
        $resultado->dataUnidadTipo= UnidadNegocio::create()->getDataComboUnidadTipo();
        $resultado->dataSunatDetalle= SunatTablaNegocio::create()->obtenerDetalleXSunatTablaId(6);
        
        return $resultado;        
    }
}
