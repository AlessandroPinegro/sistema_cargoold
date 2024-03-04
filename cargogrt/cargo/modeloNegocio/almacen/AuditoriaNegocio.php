<?php

require_once __DIR__ . '/../../modelo/almacen/Reporte.php';
require_once __DIR__ . '/../../modelo/almacen/Auditoria.php';
require_once __DIR__ . '/OrganizadorNegocio.php';
require_once __DIR__ . '/DocumentoTipoNegocio.php';
require_once __DIR__ . '/BienNegocio.php';
require_once __DIR__ . '/DocumentoNegocio.php';
require_once __DIR__ . '/OrganizadorNegocio.php';
require_once __DIR__ . '/PersonaNegocio.php';
require_once __DIR__ . '/ReporteNegocio.php';
require_once __DIR__ . '/EmpresaNegocio.php';
require_once __DIR__ . '/BienPrecioNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
//require_once __DIR__ . '/../../util/PHPExcel/PHPExcel.php';

class AuditoriaNegocio extends ModeloNegocioBase {

    private $estiloTituloReporte, $estiloTituloColumnas, $estiloInformacion;
    
    /**
     * 
     * @return AuditoriaNegocio
     */
    
    static function create() {
        return parent::create();
    }
     public function obtenerConfiguracionesInicialesAuditoria($idEmpresa) {

        $respuesta = new ObjectUtil();
        
        $respuesta->persona_activa = PersonaNegocio::create()->obtenerActivas();
        
//        $respuesta->organizador = OrganizadorNegocio::create()->obtenerOrganizadorActivoXEmpresa($idEmpresa);
//        $respuesta->bien = BienNegocio::create()->obtenerBienKardexXEmpresa($idEmpresa);
//        $respuesta->bien_tipo = BienNegocio::create()->obtenerBienTipoKardexXEmpresa($idEmpresa);
//        $respuesta->fecha_primer_documento = DocumentoNegocio::create()->obtenerFechaPrimerDocumento();
        return $respuesta;
    }
    public function listaAuditoria($criterios) {

        $organizadorId = ReporteNegocio::create()->obtenerHijosOrganizador($criterios[0]['organizador']);
        $bienId = $criterios[0]['bien'];
        $bienTipoId = $criterios[0]['bienTipo'];

        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmision']['inicio']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmision']['fin']);

        $empresaId = $criterios[0]['empresaId'];

        $organizadorIdFormateado = Util::convertirArrayXCadena($organizadorId);
        $bienIdFormateado = Util::convertirArrayXCadena($bienId);
        $BienTipoIdFormateado = Util::convertirArrayXCadena($bienTipoId);

        return Auditoria::create()->listaAuditoria($organizadorIdFormateado, $bienIdFormateado, $BienTipoIdFormateado, $emisionInicio, $emisionFin, $empresaId);

    }
    private function formatearFechaBD($cadena) {
        if (!ObjectUtil::isEmpty($cadena)) {
            return DateUtil::formatearCadenaACadenaBD($cadena);
        }
        return "";
    }
      public function obtenerAuditoriaXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start) {

        $personaId = $criterios[0]['persona'];
        
        $comenatrio = $criterios[0]['comenatrio'];

        $fechaInicio = $this->formatearFechaBD($criterios[0]['fechaInicio']);

        $fechaFin = $this->formatearFechaBD($criterios[0]['fechaFin']);
        
        $empresaId = $criterios[0]['empresaId'];

//        $organizadorIdFormateado = Util::convertirArrayXCadena($organizadorId);
//        $bienIdFormateado = Util::convertirArrayXCadena($bienId);
//        $BienTipoIdFormateado = Util::convertirArrayXCadena($bienTipoId);

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];

        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];

        return Auditoria::create()->obtenerAuditoriaXCriterios($personaId, $fechaInicio,$fechaFin, $comenatrio, $empresaId, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start);
    }

    public function obtenerCantidadAuditoriaXCriterio($criterios, $elemntosFiltrados, $columns, $order, $start) {
        
        $personaId = $criterios[0]['persona'];
        
        $comenatrio = $criterios[0]['comenatrio'];

        $fechaInicio = $this->formatearFechaBD($criterios[0]['fechaInicio']);

        $fechaFin = $this->formatearFechaBD($criterios[0]['fechaFin']);
        
        $empresaId = $criterios[0]['empresaId'];

//        $organizadorIdFormateado = Util::convertirArrayXCadena($organizadorId);
//        $bienIdFormateado = Util::convertirArrayXCadena($bienId);
//        $BienTipoIdFormateado = Util::convertirArrayXCadena($bienTipoId);
        
        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];

        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];
        return Auditoria::create()->obtenerCantidadAuditoriaXCriterios($personaId, $fechaInicio,$fechaFin, $comenatrio, $empresaId, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start);
    }
    
    public function reporteKardex($usuarioId,$criterios,$auditoriaId) {
 
        $organizadorId = $this->obtenerHijosOrganizador($criterios[0]['organizador']);
        $bienId = $criterios[0]['bien'];
        $bienTipoId = $criterios[0]['bienTipo'];
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmision']['inicio']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmision']['fin']);
        $empresaId = $criterios[0]['empresaId'];
        $organizadorIdFormateado = Util::convertirArrayXCadena($organizadorId);
        $bienIdFormateado = Util::convertirArrayXCadena($bienId);
        $BienTipoIdFormateado = Util::convertirArrayXCadena($bienTipoId);
//       $respuesta = new ObjectUtil(); 
         $responseUsuario = Usuario::create()->getUsuario($usuarioId);
        $personId = $responseUsuario[0]['persona_id'];
        $respuesta->lista = Reporte::create()->reporteKardex($organizadorIdFormateado, $bienIdFormateado, $BienTipoIdFormateado, $emisionInicio, $emisionFin, $empresaId);
        $respuesta->fecha = date('d/m/Y');
        $respuesta->persona_sesion = $personId;
        $respuesta->persona = PersonaNegocio::create()->obtenerActivas();
        
        if($auditoriaId!=0){
            $respuesta->auditoriaDetalle = Auditoria::create()->obtenerDetalleAuditoria($auditoriaId);
        }        
        
        return $respuesta;
// return Reporte::create()->reporteKardex($organizadorIdFormateado, $bienIdFormateado, $BienTipoIdFormateado, $emisionInicio, $emisionFin, $empresaId);
    }
    
    
    
    
    
    public function finalizarAuditoria($auditoriaIdBandera,$usuarioId,$fecha,$comenatrio,$auditoriaData,$personaId)
    {
        $responseUsuario = Usuario::create()->getUsuario($usuarioId);
        //$personId = $responseUsuario[0]['persona_id'];
        $personId = $personaId;

        //Insertar auditoria
        /*$fecha = $this->formatearFechaBD($fecha);
        $responseAuditoria = Auditoria::create()->insertarAuditoria($personId,$fecha,$comenatrio,$usuarioId);*/

        if($auditoriaIdBandera == null)
        {
            //Insertar auditoria
            $fecha = $this->formatearFechaBD($fecha);
            $responseAuditoria = Auditoria::create()->insertarAuditoria($personId,$fecha,$comenatrio,$usuarioId);
            
            $auditoriaId = $this->validateResponse($responseAuditoria);
            //           $auditoriaId = $this->validateResponse($responseAuditoria);
            if (ObjectUtil::isEmpty($auditoriaId) || $auditoriaId < 1) {
                throw new WarningException("No se pudo guardar la auditoria");
            }
        }else
        {
            //Actualizar auditoria
            $fecha = $this->formatearFechaBD($fecha);
            Auditoria::create()->actualizarAuditoria($auditoriaIdBandera,$personId,$fecha,$comenatrio,$usuarioId);
            
            $auditoriaId = $auditoriaIdBandera;   
            
            $this->setMensajeEmergente("Enviado correctamente");
        }
        
        for ($i = 0; $i < count($auditoriaData); $i++) {
            if(ObjectUtil::isEmpty($auditoriaData[$i]['stock_real_valor']) || $auditoriaId == '')
            {
//                throw new WarningException("no ingreso ningun valor real");
            }else
            {
            $organizadorId = $auditoriaData[$i]['organizador_id'];
            $bienId = $auditoriaData[$i]['bien_id'];
            $bienTipoId = $auditoriaData[$i]['bien_tipo_id'];
            $unidadMedidaId = $auditoriaData[$i]['unidad_medida_id'];
            $stockSistema = $auditoriaData[$i]['stock'];
            $stockReal = $auditoriaData[$i]['stock_real_valor'];
            $discrepancia = $auditoriaData[$i]['discrepancia_valor'];
            Auditoria::create()->insertarAuditoriaBien($auditoriaId,$organizadorId,$bienId,$unidadMedidaId,$stockSistema,$stockReal,$discrepancia,$usuarioId);
            }
        }
        return $auditoriaId;
    }
    public function obtenerDetalleAuditoria($auditoriaId)
    {
        return Auditoria::create()->obtenerDetalleAuditoria($auditoriaId);
    }
    
    public function obtenerHijosOrganizador($arrayOrganizadores) {
        $arrayOrganizador = array();
        foreach ($arrayOrganizadores as $organizador) {

            $responseOrganizador = OrganizadorNegocio::create()->organizadorEsPadre($organizador, "");
            array_push($arrayOrganizador, $organizador);
            if (!ObjectUtil::isEmpty($responseOrganizador)) {
                if ($responseOrganizador[0]['vout_exito'] == 1) {
                    if (!ObjectUtil::isEmpty($responseOrganizador[0]['hijo'])) {
                        $arrayHijos = explode(';', $responseOrganizador[0]['hijo']);
                        foreach ($arrayHijos as $hijo) {
                            array_push($arrayOrganizador, $hijo);
                        }
                    }
                }
            }
        }

        if (!ObjectUtil::isEmpty($arrayOrganizador)) {
            return array_unique($arrayOrganizador);
        }

        return $arrayOrganizador;
    }
}

