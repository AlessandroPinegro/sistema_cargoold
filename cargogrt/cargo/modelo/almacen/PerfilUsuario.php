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
 * Description of PerfilUsuario
 *
 * @author GC
 */
class PerfilUsuario extends ModeloBase {

    const DEFAULT_ALIAS = "det_usu_per";

    public function __construct() {
        parent::__construct();
        $this->schema_name = Schema::cbp;
        $this->table_name = 'det_usu_per';
        $this->fields = array('id', 'perfil_id', 'usuario_id', 'estado', 'visible', 'fec_creacion', 'usu_creacion');
    }

    static function create() {
        return parent::create();
    }

    public function getPerfilUsuario($usuario_id) {
        $this->commandPrepare("sp_usuario_perfil_getByUsuarioId");
        $this->commandAddParameter(":vin_usuario_id", $usuario_id);
        return $this->commandGetData();
    }
}

?>