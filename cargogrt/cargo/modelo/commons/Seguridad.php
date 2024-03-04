<?php
require_once __DIR__."/../core/ModeloBase.php";
require_once __DIR__."/../enumeraciones/EstadoGenerico.php";

/*
 * @author Geancarlo Leiva <gleiva@imaginatecperu.com>
 * @version 1.0
 * @copyright (c) 2015, IMAGINA TECHNOLOGIES S.A.C.
 * @abstract Clase donde se implementará el Componente
 */

/**
 * Description of Seguridad
 *
 * @author GC
 */
class Seguridad extends ModeloBase {
    
    const DEFAULT_ALIAS = "seguridad";
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Crea la instancia del Componente.
     *
     * @return Componente
     */
    static function create()
    {
       return parent::create();
    }
    
    /**
     * Obtiene todos los productos
     * 
     * @param string $codigo El código en el componente
     * @param ComponenteTipo $tipo Es el tipo de componente a solicitar
     * @return DataTable
     */
    
    public function insertLogAcceso($usuario_id, $ip, $navegador) {
        $this->commandPrepare("sp_seg_insertar_log_acceso");
        $this->commandAddParameter(":vin_usuario_id", $usuario_id);
        $this->commandAddParameter(":vin_ip", $ip);
        $this->commandAddParameter(":vin_navegador", $navegador);
        return $this->commandGetData();
    }
    
    public function insertLogOperacion($log_acceso_id, $entidad, $operacion, $val_anterior, $val_actual) {
        $this->commandPrepare("sp_seg_insertar_log_operacion");
        $this->commandAddParameter(":vin_log_acceso_id", $log_acceso_id);
        $this->commandAddParameter(":vin_entidad", $entidad);
        $this->commandAddParameter(":vin_operacion", $operacion);
        $this->commandAddParameter(":vin_val_anterior", $val_anterior);
        $this->commandAddParameter(":vin_val_actual", $val_actual);
        return $this->commandGetData();
    }
    
    public function updateLogAcceso($id) {
        $this->commandPrepare("sp_adm_actualizar_log_acceso");
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }
    
    public function updateEstadoVisible($tabla, $campo, $bandera, $id) {
        $this->commandPrepare("sp_adm_cambiar_estado_visible");
        $this->commandAddParameter(":vin_tabla", $tabla);
        $this->commandAddParameter(":vin_campo", $campo);
        $this->commandAddParameter(":vin_bandera", $bandera);
        $this->commandAddParameter(":vin_id", $id);
        return $this->commandGetData();
    }
    
    public function confValidar() {
        $this->commandPrepare("sp_cnf_validar");
        return $this->commandGetData();
    }
    
    public function confGetAllPerfilId() {
        $this->commandPrepare("sp_cnf_get_all_perfil_id");
        return $this->commandGetData();
    }
    public function confInsertDetPerMap($perfil_id) {
        $this->commandPrepare("sp_cnf_insert_det_per_map");
        $this->commandAddParameter(":vin_perfil_id", $perfil_id);
        return $this->commandGetData();
    }
    
    public function confGetAllPerfilFlujoId() {
        $this->commandPrepare("sp_cnf_get_all_perfil_flu_id");
        return $this->commandGetData();
    }
    public function confInsertDetPerFluMap($perfil_flujo_id) {
        $this->commandPrepare("sp_cnf_insert_det_per_flu_map");
        $this->commandAddParameter(":vin_perfil_flujo_id", $perfil_flujo_id);
        return $this->commandGetData();
    }
    
    public function confGetAllElementoId() {
        $this->commandPrepare("sp_cnf_get_all_emento_id");
        return $this->commandGetData();
    }
    public function confInsertDetElePerMap($elemento_id) {
        $this->commandPrepare("sp_cnf_insert_det_ele_permap");
        $this->commandAddParameter(":vin_elemento_id", $elemento_id);
        return $this->commandGetData();
    }
}
?>