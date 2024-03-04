<?php

require_once __DIR__ . '/../../modelo/almacen/Reporte.php';
require_once __DIR__ . '/../../modelo/almacen/Motivado.php';

class MotivadoNegocio extends ModeloNegocioBase {    
    /**
     *
     * @return MotivadoNegocio
     */
    static function create() {
        return parent::create();
    }

    public function obtenerConfiguracionInicial($idEmpresa) {

        $respuesta = new ObjectUtil();
        $respuesta->documento_tipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXEmpresa($idEmpresa);
        $respuesta->fecha_primer_documento = DocumentoNegocio::create()->obtenerFechaPrimerDocumento();
        return $respuesta;
    }

    public function insertMotivado($motivo,$descripcion, $ejemplo,$usu_creacion) {
        $response = Motivado::create()->insertMotivado($motivo,$descripcion, $ejemplo,$usu_creacion);

       if ($response[0]["vout_exito"] == 0 && $response[0]["vout_exito"] != '') {
            throw new WarningException($response[0]["vout_mensajes"]);
        }
        else { return $response;  }
    }

    public function obtenerDataMotivado() {
      $resultado = Motivado::create()->obtenerDataMotivado();           
      return $resultado;
    }


    public function getMotivado($id, $usuarioId) {      
        $response = Motivado::create()->getMotivado($id);
        return $response;
    }

    public function updateMotivado($id, $motivo, $descripcion, $ejemplo, $usuarioId) {
        $response = Motivado::create()->updateMotivado($id, $motivo, $descripcion, $ejemplo, $usuarioId);
        if ($response[0]["vout_exito"] == 0 && $response[0]["vout_exito"] != '') {
         throw new WarningException($response[0]["vout_mensajes"]);
        }
        else { return $response;  }
    }

    public function deleteMotivado($id, $nom, $usuarioId) {      
        $response = Motivado::create()->deleteMotivado($id, $usuarioId);
        $response[0]['nombre'] = $nom;
        return $response;
    }
}