<?php

require_once __DIR__ . '/../../modelo/almacen/Reporte.php';
require_once __DIR__ . '/../../modelo/almacen/Tarifario.php';
require_once __DIR__ . '/OrganizadorNegocio.php';
require_once __DIR__ . '/DocumentoTipoNegocio.php';
require_once __DIR__ . '/BienNegocio.php';
require_once __DIR__ . '/DocumentoNegocio.php';
require_once __DIR__ . '/OrganizadorNegocio.php';
require_once __DIR__ . '/PersonaNegocio.php';
require_once __DIR__ . '/EmpresaNegocio.php';
require_once __DIR__ . '/BienPrecioNegocio.php';
require_once __DIR__ . '/DocumentoTipoDatoListaNegocio.php';
require_once __DIR__ . '/MovimientoNegocio.php';
require_once __DIR__ . '/OperacionNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/../../util/PHPExcel/PHPExcel.php';
require_once __DIR__ . '/GraficoNegocio.php';

class TarifarioNegocio extends ModeloNegocioBase {

    private $estiloTituloReporte, $estiloTituloColumnas, $estiloInformacion;

    /**
     *
     * @return TarifarioNegocio
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

    public function reporteBalance($criterios, $elementosFiltrados, $orden, $columnas, $tamanio) {

        $tipoDocumentoId = $criterios[0]['tipoDocumento'];
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmision']['inicio']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmision']['fin']);
        $vencimientoInicio = $this->formatearFechaBD($criterios[0]['fechaVencimiento']['inicio']);
        $vencimientoFin = $this->formatearFechaBD($criterios[0]['fechaVencimiento']['fin']);
        $bandera = $criterios[0]['bandera']; // si es 0 es balance, si es 1 es cantidad
        $tipoDocumentoIdFormateado = Util::convertirArrayXCadena($tipoDocumentoId);

        $columnaOrdenarIndice = $orden[0]['column'];
        $formaOrdenar = $orden[0]['dir'];

        $columnaOrdenar = $columnas[$columnaOrdenarIndice]['data'];

        $respuesta = new ObjectUtil();

        $respuesta->data = Reporte::create()->reporteBalance($tipoDocumentoIdFormateado, $emisionInicio, $emisionFin, $vencimientoInicio, $vencimientoFin, $elementosFiltrados, $formaOrdenar, $columnaOrdenar, $tamanio, $bandera);

        $respuesta->contador = Reporte::create()->totalReporteBalance($tipoDocumentoIdFormateado, $emisionInicio, $emisionFin, $vencimientoInicio, $vencimientoFin, $formaOrdenar, $columnaOrdenar, $bandera);

        return $respuesta;
    }

    public function obtenerConfiguracionesInicialesKardex($idEmpresa) {

        $respuesta = new ObjectUtil();
        $respuesta->organizador = OrganizadorNegocio::create()->obtenerOrganizadorActivoXEmpresa($idEmpresa);
        $respuesta->bien = BienNegocio::create()->obtenerBienKardexXEmpresa($idEmpresa);
        //        $respuesta->bien_tipo = BienNegocio::create()->obtenerBienTipoKardexXEmpresa($idEmpresa);//antes
        $respuesta->bien_tipo = BienTipo::create()->obtener();
        $respuesta->fecha_primer_documento = DocumentoNegocio::create()->obtenerFechaPrimerDocumento();
        return $respuesta;
    }

    public function obtenerConfiguracionesInicialesRankingServicios($idEmpresa) {

        $respuesta = new ObjectUtil();
        $respuesta->fecha_primer_documento = DocumentoNegocio::create()->obtenerFechaPrimerDocumento();
        return $respuesta;
    }

    public function obtenerConfiguracionesInicialesEntradaSalidaAlmacen($idEmpresa) {

        $respuesta = new ObjectUtil();
        $respuesta->organizador = OrganizadorNegocio::create()->obtenerOrganizadorActivoXEmpresa($idEmpresa); //descomentar por empresa
        //        $respuesta->organizador = OrganizadorNegocio::create()->obtenerOrganizadorActivo(56);// todas
        $respuesta->bien = BienNegocio::create()->obtenerBienKardexXEmpresa($idEmpresa);
        $respuesta->bien_tipo = BienNegocio::create()->obtenerBienTipoKardexXEmpresa($idEmpresa);
        $respuesta->fecha_primer_documento = DocumentoNegocio::create()->obtenerFechaPrimerDocumento();
        $respuesta->documento_tipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXEmpresa($idEmpresa);
        //  $respuesta->tipo_frecuencia = DocumentoTipoDatoListaNegocio::create()->obtenerXDocumentoTipoDato(505);;
        return $respuesta;
    }

    public function obtenerConfiguracionesInicialesEntradaSalidaAlmacenVirtual() {

        $respuesta = new ObjectUtil();
        //$respuesta->organizador = OrganizadorNegocio::create()->obtenerOrganizadorActivoXEmpresa($idEmpresa); //descomentar por empresa
        $respuesta->organizador = OrganizadorNegocio::create()->obtenerOrganizadorActivo(56); // todas
        $respuesta->fecha_primer_documento = DocumentoNegocio::create()->obtenerFechaPrimerDocumento();
        $respuesta->bien = BienNegocio::create()->obtenerActivos(null);
        return $respuesta;
    }

    public function obtenerConfiguracionesInicialesRankingColaboradores($idEmpresa) {

        $respuesta = new ObjectUtil();
        $respuesta->organizador = OrganizadorNegocio::create()->obtenerOrganizadorActivoXEmpresa($idEmpresa);
        $respuesta->bien = BienNegocio::create()->obtenerBienKardexXEmpresa($idEmpresa);
        $respuesta->bien_tipo = BienNegocio::create()->obtenerBienTipoKardexXEmpresa($idEmpresa);
        $respuesta->fecha_primer_documento = DocumentoNegocio::create()->obtenerFechaPrimerDocumento();
        $respuesta->persona_tipo = PersonaNegocio::create()->getAllPersonaTipo();
        return $respuesta;
    }

    public function obtenerConfiguracionesInicialesServiciosAtendidos($idEmpresa) {
        $respuesta = new ObjectUtil();
        $respuesta->organizador = OrganizadorNegocio::create()->obtenerOrganizadorActivoXEmpresa($idEmpresa);
        $respuesta->bien = BienNegocio::create()->obtenerServicioXEmpresa($idEmpresa);
        //$respuesta->bien_tipo = BienNegocio::create()->obtenerBienTipoKardexXEmpresa($idEmpresa);
        $respuesta->documento_tipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXEmpresa($idEmpresa);
        $respuesta->fecha_primer_documento = DocumentoNegocio::create()->obtenerFechaPrimerDocumento();
        return $respuesta;
    }

    //    public function reporteKardex($criterios,$elementosFiltrados,$orden,$columnas,$tamanio) {
    public function reporteKardex($criterios) {

        $organizadorId = $this->obtenerHijosOrganizador($criterios[0]['organizador']);
        $bienId = $criterios[0]['bien'];
        $bienTipoId = $criterios[0]['bienTipo'];

        //obtener bien tipo hijos
        if (!ObjectUtil::isEmpty($bienTipoId)) {
            $dataBienTipoHijos = BienNegocio::create()->obtenerBienTipoHijosXBienTipoPadreId($bienTipoId);
        }

        if (!ObjectUtil::isEmpty($dataBienTipoHijos)) {
            foreach ($dataBienTipoHijos as $item) {
                array_push($bienTipoId, $item['id']);
            }
        }
        //fin

        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmision']['inicio']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmision']['fin']);

        $empresaId = $criterios[0]['empresaId'];

        $organizadorIdFormateado = Util::convertirArrayXCadena($organizadorId);
        $bienIdFormateado = Util::convertirArrayXCadena($bienId);
        $BienTipoIdFormateado = Util::convertirArrayXCadena($bienTipoId);

        return Reporte::create()->reporteKardex($organizadorIdFormateado, $bienIdFormateado, $BienTipoIdFormateado, $emisionInicio, $emisionFin, $empresaId);
    }

    public function reporteBienesMayorRotacion($criterios) {

        $organizadorId = $this->obtenerHijosOrganizador($criterios[0]['organizador']);
        $bienId = $criterios[0]['bien'];
        $bienTipoId = $criterios[0]['bienTipo'];

        //obtener bien tipo hijos
        if (!ObjectUtil::isEmpty($bienTipoId)) {
            $dataBienTipoHijos = BienNegocio::create()->obtenerBienTipoHijosXBienTipoPadreId($bienTipoId);
        }

        if (!ObjectUtil::isEmpty($dataBienTipoHijos)) {
            foreach ($dataBienTipoHijos as $item) {
                array_push($bienTipoId, $item['id']);
            }
        }
        //fin	

        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmision']['inicio']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmision']['fin']);

        $empresaId = $criterios[0]['empresaId'];

        $organizadorIdFormateado = Util::convertirArrayXCadena($organizadorId);
        $bienIdFormateado = Util::convertirArrayXCadena($bienId);
        $BienTipoIdFormateado = Util::convertirArrayXCadena($bienTipoId);

        return Reporte::create()->reporteBienesMayorRotacion($organizadorIdFormateado, $bienIdFormateado, $BienTipoIdFormateado, $emisionInicio, $emisionFin, $empresaId);
    }

    // 

    public function reporteComprometidosDia($criterios) {

        $organizadorId = $this->obtenerHijosOrganizador($criterios[0]['organizador']);
        $bienId = $criterios[0]['bien'];
        $bienTipoId = $criterios[0]['bienTipo'];

        //obtener bien tipo hijos
        if (!ObjectUtil::isEmpty($bienTipoId)) {
            $dataBienTipoHijos = BienNegocio::create()->obtenerBienTipoHijosXBienTipoPadreId($bienTipoId);
        }

        if (!ObjectUtil::isEmpty($dataBienTipoHijos)) {
            foreach ($dataBienTipoHijos as $item) {
                array_push($bienTipoId, $item['id']);
            }
        }
        //fin	

        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmision']['inicio']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmision']['fin']);

        $empresaId = $criterios[0]['empresaId'];

        $organizadorIdFormateado = Util::convertirArrayXCadena($organizadorId);
        $bienIdFormateado = Util::convertirArrayXCadena($bienId);
        $BienTipoIdFormateado = Util::convertirArrayXCadena($bienTipoId);

        return Reporte::create()->reporteComprometidosDia($organizadorIdFormateado, $bienIdFormateado, $BienTipoIdFormateado, $emisionInicio, $emisionFin, $empresaId);
    }

    public function reporteRankingServicios($criterios) {
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmision']['inicio']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmision']['fin']);

        $empresaId = $criterios[0]['empresaId'];

        return Reporte::create()->reporteRankingServicios($emisionInicio, $emisionFin, $empresaId);
    }

    public function reporteDetalleEntradaSalidaAlmacen($criterios, $indicador) {

        $organizadorId = $this->obtenerHijosOrganizador($criterios[0]['organizador']);
        $bienId = $criterios[0]['bien'];
        $bienTipoId = $criterios[0]['bienTipo'];

        $emisionInicio = $criterios[0]['fechaEmision']['inicio'];
        $emisionFin = $criterios[0]['fechaEmision']['fin'];

        $empresaId = $criterios[0]['empresaId'];

        $organizadorIdFormateado = Util::convertirArrayXCadena($organizadorId);
        $bienIdFormateado = Util::convertirArrayXCadena($bienId);
        $BienTipoIdFormateado = Util::convertirArrayXCadena($bienTipoId);

        return Reporte::create()->reporteDetalleEntradaSalidaAlmacen($organizadorIdFormateado, $bienIdFormateado, $BienTipoIdFormateado, $emisionInicio, $emisionFin, $empresaId, $indicador);
    }

    public function reporteEntradaSalidaAlmacen($criterios) {

        $organizadorId = $this->obtenerHijosOrganizador($criterios[0]['organizador']);
        $bienId = $criterios[0]['bien'];
        $bienTipoId = $criterios[0]['bienTipo'];
        //obtener bien tipo hijos
        if (!ObjectUtil::isEmpty($bienTipoId)) {
            $dataBienTipoHijos = BienNegocio::create()->obtenerBienTipoHijosXBienTipoPadreId($bienTipoId);
        }

        if (!ObjectUtil::isEmpty($dataBienTipoHijos)) {
            foreach ($dataBienTipoHijos as $item) {
                array_push($bienTipoId, $item['id']);
            }
        }
        //fin	
        $documentoTipoId = $criterios[0]['documentoTipo'];

        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmision']['inicio']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmision']['fin']);

        $empresaId = $criterios[0]['empresaId'];

        $organizadorIdFormateado = Util::convertirArrayXCadena($organizadorId);
        $bienIdFormateado = Util::convertirArrayXCadena($bienId);
        $BienTipoIdFormateado = Util::convertirArrayXCadena($bienTipoId);
        $documentoTipoIdFormateado = Util::convertirArrayXCadena($documentoTipoId);

        return Reporte::create()->reporteEntradaSalidaAlmacen($organizadorIdFormateado, $bienIdFormateado, $BienTipoIdFormateado, $emisionInicio, $emisionFin, $empresaId, $documentoTipoIdFormateado);
    }

    public function obtenerDataEntradaSalidaAlmacenVirtualXCriterios($criterios) {

        $organizadorOrigenId = $this->obtenerHijosOrganizador($criterios[0]['origen']);
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmision']['inicio']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmision']['fin']);

        $organizadorOrigenIdFormateado = Util::convertirArrayXCadena($organizadorOrigenId);
        $productoIdFormateado = Util::convertirArrayXCadena($criterios[0]['producto']);

        return Reporte::create()->obtenerDataEntradaSalidaAlmacenVirtualXCriterios($organizadorOrigenIdFormateado, $productoIdFormateado, $emisionInicio, $emisionFin);
    }

    public function obtenerDataEntradaSalidaAlmacenVirtualDetalle($documentoId, $bienId) {
        return Reporte::create()->obtenerDataEntradaSalidaAlmacenVirtualDetalle($documentoId, $bienId);
    }

    public function reporteDispersionBienes($criterios) {

        $organizadorId = $this->obtenerHijosOrganizador($criterios[0]['organizador']);
        $bienId = $criterios[0]['bien'];
        $bienTipoId = $criterios[0]['bienTipo'];

        //obtener bien tipo hijos
        if (!ObjectUtil::isEmpty($bienTipoId)) {
            $dataBienTipoHijos = BienNegocio::create()->obtenerBienTipoHijosXBienTipoPadreId($bienTipoId);
        }

        if (!ObjectUtil::isEmpty($dataBienTipoHijos)) {
            foreach ($dataBienTipoHijos as $item) {
                array_push($bienTipoId, $item['id']);
            }
        }
        //fin	

        $documentoTipoId = $criterios[0]['documentoTipo'];
        $tipoFrecuenciaId = $criterios[0]['tipoFrecuencia'];

        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmision']['inicio']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmision']['fin']);

        //$empresaId = $criterios[0]['empresaId']; descomentar para busqueda por empresa
        $empresaId = null;

        if ($tipoFrecuenciaId == 1) {
            $dias = (strtotime($emisionInicio) - strtotime($emisionFin)) / 86400;
            $dias = abs($dias);
            $dias = floor($dias);

            if ($dias > 30) {
                throw new WarningException("Intérvalo de días superior a 30");
            }
        }
        if ($tipoFrecuenciaId == 2) {
            $meses = (strtotime($emisionInicio) - strtotime($emisionFin)) / (86400 * 30);
            $meses = abs($meses);
            $meses = floor($meses);

            if ($meses > 30) {
                throw new WarningException("Intérvalo de meses superior a 30");
            }
        }


        $organizadorIdFormateado = Util::convertirArrayXCadena($organizadorId);
        $bienIdFormateado = Util::convertirArrayXCadena($bienId);
        $BienTipoIdFormateado = Util::convertirArrayXCadena($bienTipoId);
        $documentoTipoIdFormateado = Util::convertirArrayXCadena($documentoTipoId);

        //$reporte=Reporte::create()->reporteDispersionBienes($organizadorIdFormateado, $bienIdFormateado, $BienTipoIdFormateado, $emisionInicio, $emisionFin, $empresaId,$documentoTipoIdFormateado);
        return Reporte::create()->reporteDispersionBienes($organizadorIdFormateado, $bienIdFormateado, $BienTipoIdFormateado, $emisionInicio, $emisionFin, $empresaId, $documentoTipoIdFormateado, $tipoFrecuenciaId);
    }

    public function reporteEntradaSalida($criterios) {

        $organizadorId = $this->obtenerHijosOrganizador($criterios[0]['organizador']);
        $bienId = $criterios[0]['bien'];
        $bienTipoId = $criterios[0]['bienTipo'];
        //obtener bien tipo hijos
        if (!ObjectUtil::isEmpty($bienTipoId)) {
            $dataBienTipoHijos = BienNegocio::create()->obtenerBienTipoHijosXBienTipoPadreId($bienTipoId);
        }

        if (!ObjectUtil::isEmpty($dataBienTipoHijos)) {
            foreach ($dataBienTipoHijos as $item) {
                array_push($bienTipoId, $item['id']);
            }
        }
        //fin	
        $documentoTipoId = $criterios[0]['documentoTipo'];

        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmision']['inicio']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmision']['fin']);

        $empresaId = $criterios[0]['empresaId'];

        $organizadorIdFormateado = Util::convertirArrayXCadena($organizadorId);
        $bienIdFormateado = Util::convertirArrayXCadena($bienId);
        $BienTipoIdFormateado = Util::convertirArrayXCadena($bienTipoId);
        $documentoTipoIdFormateado = Util::convertirArrayXCadena($documentoTipoId);

        return Reporte::create()->reporteEntradaSalida($organizadorIdFormateado, $bienIdFormateado, $BienTipoIdFormateado, $emisionInicio, $emisionFin, $empresaId, $documentoTipoIdFormateado);
    }

    public function reporteRankingColaboradores($criterios) {

        $organizadorId = $this->obtenerHijosOrganizador($criterios[0]['organizador']);
        $bienId = $criterios[0]['bien'];
        $bienTipoId = $criterios[0]['bienTipo'];
        $personaTipoId = $criterios[0]['personaTipo'];

        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmision']['inicio']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmision']['fin']);

        $empresaId = $criterios[0]['empresaId'];

        $organizadorIdFormateado = Util::convertirArrayXCadena($organizadorId);
        $bienIdFormateado = Util::convertirArrayXCadena($bienId);
        $BienTipoIdFormateado = Util::convertirArrayXCadena($bienTipoId);
        $personaTipoIdFormateado = Util::convertirArrayXCadena($personaTipoId);

        return Reporte::create()->reporteRankingColaboradores($organizadorIdFormateado, $bienIdFormateado, $BienTipoIdFormateado, $emisionInicio, $emisionFin, $empresaId, $personaTipoIdFormateado);
    }

    public function reporteServicios($criterios) {

        $organizadorId = $this->obtenerHijosOrganizador($criterios[0]['organizador']);
        $bienId = $criterios[0]['bien'];
        $documentoTipoId = $criterios[0]['documentoTipo'];

        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmision']['inicio']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmision']['fin']);

        $empresaId = $criterios[0]['empresaId'];

        $organizadorIdFormateado = Util::convertirArrayXCadena($organizadorId);
        $bienIdFormateado = Util::convertirArrayXCadena($bienId);
        $documentoTipoIdFormateado = Util::convertirArrayXCadena($documentoTipoId);

        //return Reporte::create()->reporteKardex($organizadorIdFormateado, $bienIdFormateado, $BienTipoIdFormateado, $emisionInicio, $emisionFin, $empresaId);
        return Reporte::create()->reporteServiciosAtendidos($organizadorIdFormateado, $bienIdFormateado, $documentoTipoIdFormateado, $emisionInicio, $emisionFin, $empresaId);
    }

    public function obtenerDetalleKardex($idBien, $idOrganizador, $fechaInicio, $fechaFin) {
        return Reporte::create()->obtenerDetalleKardex($idBien, $idOrganizador, $fechaInicio, $fechaFin);
    }

    public function obtenerDocumentoServicios($idBien, $fechaInicio, $fechaFin) {
        return Reporte::create()->obtenerDocumentoServicios($idBien, $fechaInicio, $fechaFin);
    }

    public function obtenerDetalleBienesMayorRotacion($idBien, $idOrganizador, $idUnidadMedida, $fechaInicio, $fechaFin) {
        return Reporte::create()->obtenerDetalleBienesMayorRotacion($idBien, $idOrganizador, $idUnidadMedida, $fechaInicio, $fechaFin);
    }

    public function obtenerDetalleComprometidosDia($idBien, $fechaInicio, $fechaFin, $empresaId) {
        return Reporte::create()->obtenerDetalleComprometidosDia($idBien, $fechaInicio, $fechaFin, $empresaId);
    }

    public function obtenerDetalleRankingServicios($idBien, $fechaInicio, $fechaFin) {
        return Reporte::create()->obtenerDetalleRankingServicios($idBien, $fechaInicio, $fechaFin);
    }

    // funciones extras

    private function formatearFechaBD($cadena) {
        if (!ObjectUtil::isEmpty($cadena)) {
            return DateUtil::formatearCadenaACadenaBD($cadena);
        }
        return "";
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

    //Reporte de deudas
    public function obtenerReporteDeudaXCriterios($tipo1, $tipo2, $criterios, $elemntosFiltrados, $columns, $order, $start) {
        $personaId = $criterios[0]['persona'];
        $mostrarPagados = $criterios[0]['mostrar'];
        $mostrarLib = $criterios[0]['mostrarLib'];
        if ($criterios[0]['fechaVencimientoDesde'] != '') {
            $fechaVencimientoDesde = DateUtil::formatearCadenaACadenaBD($criterios[0]['fechaVencimientoDesde']);
        }

        if ($criterios[0]['fechaVencimientoHasta'] != '') {
            $fechaVencimientoHasta = DateUtil::formatearCadenaACadenaBD($criterios[0]['fechaVencimientoHasta']);
        }
        $empresa = $criterios[0]['empresa'];
        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];

        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];
        //        $fechaVencimientoDesde = DateUtil::formatearCadenaACadenaBD($valor['inicio']);;
        //        echo "$mostrarPagados,$mostrarLib, $tipo1, $tipo2, $empresa, $personaId, $fechaVencimientoDesde, $fechaVencimientoHasta, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start";
        return Reporte::create()->obtenerReporteDeudaXCriterios($mostrarPagados, $mostrarLib, $tipo1, $tipo2, $empresa, $personaId, $fechaVencimientoDesde, $fechaVencimientoHasta, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start);
    }

    public function obtenerCantidadReporteDeudaXCriterio($tipo1, $tipo2, $criterios, $elemntosFiltrados, $columns, $order, $start) {
        $personaId = $criterios[0]['persona'];
        $mostrarPagados = $criterios[0]['mostrar'];
        $mostrarLib = $criterios[0]['mostrarLib'];
        if ($criterios[0]['fechaVencimientoDesde'] != '') {
            $fechaVencimientoDesde = DateUtil::formatearCadenaACadenaBD($criterios[0]['fechaVencimientoDesde']);
        }
        if ($criterios[0]['fechaVencimientoHasta'] != '') {
            $fechaVencimientoHasta = DateUtil::formatearCadenaACadenaBD($criterios[0]['fechaVencimientoHasta']);
        }
        $empresa = $criterios[0]['empresa'];
        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];

        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];
        return Reporte::create()->obtenerCantidadReporteDeudaXCriterios($mostrarPagados, $mostrarLib, $tipo1, $tipo2, $empresa, $personaId, $fechaVencimientoDesde, $fechaVencimientoHasta, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start);
    }

    //Reporte de deudas general
    public function obtenerConfiguracionesInicialesDeudaGeneral() {
        $response->persona = PersonaNegocio::create()->obtenerActivas();
        $response->empresa = EmpresaNegocio::create()->getEmpresaActivas();
        return $response;
    }

    public function obtenerReporteDeudaGeneralXCriterios($tipo1, $tipo2, $criterios, $elemntosFiltrados, $columns, $order, $start) {
        $personaId = $criterios[0]['persona'];
        $mostrar = $criterios[0]['mostrar'];
        $fecha = '';
        $empresa = Util::convertirArrayXCadena($criterios[0]['empresa']);

        if ($criterios[0]['fecha'] != '') {
            $fecha = DateUtil::formatearCadenaACadenaBD($criterios[0]['fecha']);
        }

        //$empresa = $criterios[0]['empresa'];
        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];

        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];
        //        $fechaVencimientoDesde = DateUtil::formatearCadenaACadenaBD($valor['inicio']);;

        return Reporte::create()->obtenerReporteDeudaGeneralXCriterios($mostrar, $tipo1, $tipo2, $empresa, $personaId, $fecha, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start);
    }

    public function obtenerCantidadReporteDeudaGeneralXCriterio($tipo1, $tipo2, $criterios, $elemntosFiltrados, $columns, $order, $start) {
        $personaId = $criterios[0]['persona'];
        $mostrar = $criterios[0]['mostrar'];
        $fecha = '';
        if ($criterios[0]['fecha'] != '') {
            $fecha = DateUtil::formatearCadenaACadenaBD($criterios[0]['fecha']);
        }

        $empresa = Util::convertirArrayXCadena($criterios[0]['empresa']);
        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];
        return Reporte::create()->obtenerCantidadReporteDeudaGeneralXCriterios($mostrar, $tipo1, $tipo2, $empresa, $personaId, $fecha, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start);
    }

    public function obtenerReporteBalanceExcel($criterios) {

        $tipoDocumentoId = $criterios[0]['tipoDocumento'];
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmision']['inicio']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmision']['fin']);
        $vencimientoInicio = $this->formatearFechaBD($criterios[0]['fechaVencimiento']['inicio']);
        $vencimientoFin = $this->formatearFechaBD($criterios[0]['fechaVencimiento']['fin']);

        $tipoDocumentoIdFormateado = Util::convertirArrayXCadena($tipoDocumentoId);

        $respuestaReporteBalanceExcel = Reporte::create()->reporteBalanceExcel($tipoDocumentoIdFormateado, $emisionInicio, $emisionFin, $vencimientoInicio, $vencimientoFin);

        if (ObjectUtil::isEmpty($respuestaReporteBalanceExcel)) {
            throw new WarningException("No existe datos para exportar");
        } else {
            $this->crearReporteBalanceExcel($respuestaReporteBalanceExcel, "Reporte balance");
        }
    }

    private function crearReporteBalanceExcel($reportes, $titulo) {

        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':G' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $titulo);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':G' . $i)->applyFromArray($this->estiloTituloReporte);

        $i += 2;

        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Fecha de emisión');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'Tipo de documento');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'Persona');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'Serie');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'Numero');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, 'Fecha Vencimiento');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, 'Importe');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':G' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;

        foreach ($reportes as $reporte) {

            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, DateUtil::formatearFechaBDAaCadenaVw($reporte['fecha_emision']));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $reporte['documento_tipo_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $reporte['persona_nombre']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, $reporte['serie']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, $reporte['numero']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, DateUtil::formatearFechaBDAaCadenaVw($reporte['fecha_vencimiento']));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, $reporte['importe']);

            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':G' . $i)->applyFromArray($this->estiloInformacion);

            $i += 1;
        }

        for ($i = 'A'; $i <= 'G'; $i++) {

            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle($titulo);

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/reporte.xlsx');

        return 1;
    }

    public function obtenerReporteServiciosAtendidosExcel($criterios, $tipo) {

        $respuestaReporteServiciosAtendidosExcel = $this->reporteServicios($criterios);

        if (ObjectUtil::isEmpty($respuestaReporteServiciosAtendidosExcel)) {
            throw new WarningException("No existe datos para exportar");
        } else {
            if ($tipo == 1) {
                $this->crearReporteServiciosAtendidosExcel($respuestaReporteServiciosAtendidosExcel, "Reporte Servicios Atendidos");
            } else {
                $this->crearReporteServiciosAtendidosExcel($respuestaReporteServiciosAtendidosExcel, "Reporte Servicios Atendidos General");
            }
        }
    }

    private function crearReporteServiciosAtendidosExcel($reportes, $titulo) {

        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':C' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $titulo);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':C' . $i)->applyFromArray($this->estiloTituloReporte);

        $i += 2;

        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Código');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'Servicio');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'Atenciones');
        //$objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'Unidad de medida');
        //$objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'Stock');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':C' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;

        foreach ($reportes as $reporte) {

            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $reporte['bien_codigo']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $reporte['bien_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $reporte['cantidad']);
            //$objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, $reporte['unidad_medida_descripcion']);
            //$objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, $reporte['stock']);

            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':C' . $i)->applyFromArray($this->estiloInformacion);

            $i += 1;
        }

        for ($i = 'A'; $i <= 'E'; $i++) {

            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle($titulo);

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/reporte.xlsx');

        return 1;
    }

    public function obtenerReporteBienesMayorRotacionExcel($criterios) {

        $respuestaBienesMayorRotacionExcel = $this->reporteBienesMayorRotacion($criterios);

        if (ObjectUtil::isEmpty($respuestaBienesMayorRotacionExcel)) {
            throw new WarningException("No existe datos para exportar");
        } else {
            $this->crearReporteBienesMayorRotacionExcel($respuestaBienesMayorRotacionExcel, "Reporte Bienes Mayor Rotación");
        }
    }

    public function obtenerReporteRankingColaboradoresExcel($criterios) {

        $respuestaRankingColaboradoresExcel = $this->reporteRankingColaboradores($criterios);

        if (ObjectUtil::isEmpty($respuestaRankingColaboradoresExcel)) {
            throw new WarningException("No existe datos para exportar");
        } else {
            $this->crearReporteRankingColaboradoresExcel($respuestaRankingColaboradoresExcel, "Reporte Raking de Colaboradores");
        }
    }

    private function crearReporteRankingColaboradoresExcel($reportes, $titulo) {

        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':E' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $titulo);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':E' . $i)->applyFromArray($this->estiloTituloReporte);

        $i += 2;
        /* Colaborador	F. Entrada	F. Salida	F. Total */
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'N°');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'Colaborador');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'F. Entrada');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'F. Salida');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'F. Total');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':E' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;

        foreach ($reportes as $index => $reporte) {

            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $index + 1);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $reporte['persona_nombre']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $reporte['frecuencia_ingreso']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, $reporte['frecuencia_salida']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, $reporte['frecuencia_total']);

            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':E' . $i)->applyFromArray($this->estiloInformacion);

            $i += 1;
        }

        for ($i = 'A'; $i <= 'E'; $i++) {

            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle($titulo);

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/reporte.xlsx');

        return 1;
    }

    public function obtenerReporteRankingServiciosExcel($criterios) {

        $respuestaRankingServiciosExcel = $this->reporteRankingServicios($criterios);

        if (ObjectUtil::isEmpty($respuestaRankingServiciosExcel)) {
            throw new WarningException("No existe datos para exportar");
        } else {
            $this->crearReporteRankingServiciosExcel($respuestaRankingServiciosExcel, "Reporte Ranking de Servicios");
        }
    }

    private function crearReporteRankingServiciosExcel($reportes, $titulo) {

        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':B' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $titulo);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':E' . $i)->applyFromArray($this->estiloTituloReporte);

        $i += 2;
        /* Servicio	Cantidad	Opciones */
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Servicio');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'Cantidad Bienes');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':B' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;

        foreach ($reportes as $reporte) {
            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $reporte['bien_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $reporte['cantidad_bienes']);

            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':B' . $i)->applyFromArray($this->estiloInformacion);

            $i += 1;
        }

        for ($i = 'A'; $i <= 'B'; $i++) {

            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle($titulo);

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/reporte.xlsx');

        return 1;
    }

    public function obtenerReporteComprometidosDiaExcel($criterios) {

        $respuestaComprometidosDiaExcel = $this->reporteComprometidosDia($criterios);

        if (ObjectUtil::isEmpty($respuestaComprometidosDiaExcel)) {
            throw new WarningException("No existe datos para exportar");
        } else {
            $this->crearReporteComprometidosDiaExcel($respuestaComprometidosDiaExcel, "Reporte comprometidos en el día");
        }
    }

    private function crearReporteComprometidosDiaExcel($reportes, $titulo) {

        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':E' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $titulo);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':E' . $i)->applyFromArray($this->estiloTituloReporte);

        $i += 2;
        /* F. Emisión	Bien	Bien Tipo	Unidad Medida	Cantidad */
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'F. Emisión');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'Bien');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'Bien Tipo');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'Unidad de medida');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'Cantidad');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':E' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;

        foreach ($reportes as $reporte) {
            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $reporte['fecha_emision']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $reporte['bien_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $reporte['bien_tipo_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, $reporte['unidad_control_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, $reporte['cantidad_control']);

            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':E' . $i)->applyFromArray($this->estiloInformacion);

            $i += 1;
        }

        for ($i = 'A'; $i <= 'E'; $i++) {

            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle($titulo);

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/reporte.xlsx');

        return 1;
    }

    public function obtenerReporteEntradaSalidaExcel($criterios) {

        $respuestaEntradaSalidaExcel = $this->reporteEntradaSalida($criterios);

        if (ObjectUtil::isEmpty($respuestaEntradaSalidaExcel)) {
            throw new WarningException("No existe datos para exportar");
        } else {
            $this->crearReporteEntradaSalidaExcel($respuestaEntradaSalidaExcel, "Reporte Entrada Salida");
        }
    }

    private function crearReporteEntradaSalidaExcel($reportes, $titulo) {

        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':I' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $titulo);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':I' . $i)->applyFromArray($this->estiloTituloReporte);

        $i += 2;
        /* F. Creación	F. Emisión	Documento Tipo	Numero	Organizador	Bien	Bien Tipo	Unidad Medida	Cantidad
         */
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'F. Creación');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'F. Emisión');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'Documento Tipo');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'Número');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'Organizador');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, 'Bien');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, 'Bien Tipo');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, 'Unidad Medida');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, 'Cantidad');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':I' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;

        foreach ($reportes as $reporte) {

            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $reporte['fecha_creacion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $reporte['fecha_emision']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $reporte['documento_tipo_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, $reporte['documento_numero']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, $reporte['organizador_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $reporte['bien_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, $reporte['bien_tipo_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, $reporte['unidad_control_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, $reporte['cantidad_control']);

            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':I' . $i)->applyFromArray($this->estiloInformacion);

            $i += 1;
        }

        for ($i = 'A'; $i <= 'I'; $i++) {

            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle($titulo);

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/reporte.xlsx');

        return 1;
    }

    public function obtenerReporteEntradaSalidaAlmacenExcel($criterios) {

        $respuestaEntradaSalidaAlmacenExcel = $this->reporteEntradaSalidaAlmacen($criterios);

        if (ObjectUtil::isEmpty($respuestaEntradaSalidaAlmacenExcel)) {
            throw new WarningException("No existe datos para exportar");
        } else {
            $this->crearReporteEntradaSalidaAlmacenExcel($respuestaEntradaSalidaAlmacenExcel, "Reporte Entrada Salida Almacen");
        }
    }

    private function crearReporteEntradaSalidaAlmacenExcel($reportes, $titulo) {

        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':C' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $titulo);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':C' . $i)->applyFromArray($this->estiloTituloReporte);

        $i += 2;
        /* Organizador	Tipo Frecuencia	Frecuencia	Opciones
         */
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Organizador');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'Tipo Frecuencia');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'Frecuencia');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':C' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;

        foreach ($reportes as $reporte) {

            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $reporte['organizador_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $reporte['tipo_frecuencia']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $reporte['frecuencia']);

            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':C' . $i)->applyFromArray($this->estiloInformacion);

            $i += 1;
        }

        for ($i = 'A'; $i <= 'C'; $i++) {

            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle($titulo);

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/reporte.xlsx');

        return 1;
    }

    private function crearReporteBienesMayorRotacionExcel($reportes, $titulo) {

        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':F' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $titulo);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':F' . $i)->applyFromArray($this->estiloTituloReporte);

        $i += 2;

        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Bien');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'Organizador');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'Tipo bien');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'Unidad de medida');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'Stock');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, 'Frecuencia');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':F' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;

        foreach ($reportes as $reporte) {

            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $reporte['bien_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $reporte['organizador_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $reporte['bien_tipo_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, $reporte['unidad_medida_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, $reporte['stock']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $reporte['frecuencia']);

            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':F' . $i)->applyFromArray($this->estiloInformacion);

            $i += 1;
        }

        for ($i = 'A'; $i <= 'F'; $i++) {

            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle($titulo);

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/reporte.xlsx');

        return 1;
    }

    public function obtenerReporteKardexExcel($criterios, $tipo) {

        $respuestaReporteKardexExcel = $this->reporteKardex($criterios);

        if (ObjectUtil::isEmpty($respuestaReporteKardexExcel)) {
            throw new WarningException("No existe datos para exportar");
        } else {
            if ($tipo == 1) {
                $this->crearReporteKardexExcel($respuestaReporteKardexExcel, "Reporte inventario");
            } else {
                $this->crearReporteKardexExcel($respuestaReporteKardexExcel, "Reporte inventario general");
            }
        }
    }

    private function crearReporteKardexExcel($reportes, $titulo) {

        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':E' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $titulo);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':E' . $i)->applyFromArray($this->estiloTituloReporte);

        $i += 2;

        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Bien');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'Organizador');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'Tipo bien');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'Unidad de medida');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'Stock');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':E' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;

        foreach ($reportes as $reporte) {

            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $reporte['bien_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $reporte['organizador_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $reporte['bien_tipo_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, $reporte['unidad_medida_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, $reporte['stock']);

            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':E' . $i)->applyFromArray($this->estiloInformacion);

            $i += 1;
        }

        for ($i = 'A'; $i <= 'E'; $i++) {

            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle($titulo);

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/reporte.xlsx');

        return 1;
    }

    public function obtenerConfiguracionesInicialesReporteXOrganizador($idEmpresa) {

        $respuesta = new ObjectUtil();
        $respuesta->organizador = OrganizadorNegocio::create()->obtenerOrganizadorActivoXEmpresa($idEmpresa);
        $respuesta->bien = BienNegocio::create()->obtenerBienKardexXEmpresa($idEmpresa);
        $respuesta->bien_tipo = BienNegocio::create()->obtenerBienTipoKardexXEmpresa($idEmpresa);
        $respuesta->fecha_primer_documento = DocumentoNegocio::create()->obtenerFechaPrimerDocumento();
        return $respuesta;
    }

    public function ReporteXOrganizador($criterios) {

        $dataRespuesta = $this->reporteKardex($criterios);

        $tamanho = count($dataRespuesta);

        for ($i = 0; $i < $tamanho; $i++) {
            $precioCompra = BienPrecioNegocio::create()->obtenerPrecioCompra($dataRespuesta[$i]['bien_id']);
            if (ObjectUtil::isEmpty($precioCompra)) {
                $dataRespuesta[$i]['total_monetario'] = 0;
            } else {
                $dataRespuesta[$i]['total_monetario'] = $dataRespuesta[$i]['stock'] * $precioCompra;
            }
        }

        return $dataRespuesta;
    }

    public function obtenerTotalBalance($criterios) {

        $tipoDocumentoId = $criterios[0]['tipoDocumento'];
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmision']['inicio']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmision']['fin']);
        $vencimientoInicio = $this->formatearFechaBD($criterios[0]['fechaVencimiento']['inicio']);
        $vencimientoFin = $this->formatearFechaBD($criterios[0]['fechaVencimiento']['fin']);
        $tipoDocumentoIdFormateado = Util::convertirArrayXCadena($tipoDocumentoId);

        $respuesta = new ObjectUtil();

        $importeTotal = Reporte::create()->importeTotal($tipoDocumentoIdFormateado, $emisionInicio, $emisionFin, $vencimientoInicio, $vencimientoFin);

        $respuesta->total = $importeTotal[0]['SUM(tabla.importe)'];

        return $respuesta;
    }

    private function estilosExcel() {

        $this->estiloTituloReporte = array(
            'font' => array(
                'name' => 'Arial',
                'bold' => true,
                'italic' => false,
                'strike' => false,
                'size' => 14
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => FALSE
            )
        );

        $this->estiloTituloColumnas = array(
            'font' => array(
                'name' => 'Arial',
                'bold' => true,
                'size' => 12
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array(
                        'rgb' => '000000'
                    )
                )
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => FALSE
            )
        );

        $this->estiloInformacion = array(
            'font' => array(
                'name' => 'Arial',
                'size' => 10
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_HAIR,
                    'color' => array(
                        'rgb' => '000000'
                    )
                )
            ),
        );

        $this->estiloNumInformacion = array(
            'font' => array(
                'name' => 'Arial',
                'size' => 10
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_HAIR,
                    'color' => array(
                        'rgb' => '000000'
                    )
                )
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => FALSE
            )
        );

        $this->estiloTextoInformacion = array(
            'font' => array(
                'name' => 'Arial',
                'size' => 10
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_HAIR,
                    'color' => array(
                        'rgb' => '000000'
                    )
                )
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => FALSE
            )
        );
    }

    public function obtenerConfiguracionesInicialesBalanceConsolidado($idEmpresa, $idTipos) {

        $respuesta = new ObjectUtil();
        $respuesta->documento_tipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXEmpresaXTipo($idEmpresa, $idTipos);
        $respuesta->persona = PersonaNegocio::create()->obtenerActivas();
        return $respuesta;
    }

    //Reporte de balance consolidado

    public function obtenerReporteBalanceConsolidadoXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start) {
        $personaId = $criterios[0]['persona'];
        $tipoDocumentoId = $criterios[0]['tipoDocumento'];
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmision']['inicio']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmision']['fin']);
        $vencimientoInicio = $this->formatearFechaBD($criterios[0]['fechaVencimiento']['inicio']);
        $vencimientoFin = $this->formatearFechaBD($criterios[0]['fechaVencimiento']['fin']);
        $empresa = $criterios[0]['empresa'];
        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $tipoDocumentoIdFormateado = Util::convertirArrayXCadena($tipoDocumentoId);
        $empresaId = Util::convertirArrayXCadena($empresa);
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];
        //        $fechaVencimientoDesde = DateUtil::formatearCadenaACadenaBD($valor['inicio']);;

        return Reporte::create()->obtenerReporteReporteBalanceConsolidadoXCriterios($empresaId, $tipoDocumentoIdFormateado, $personaId, $emisionInicio, $emisionFin, $vencimientoInicio, $vencimientoFin, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start);
    }

    public function obtenerCantidadReporteBalanceConsolidadoXCriterio($criterios, $elemntosFiltrados, $columns, $order, $start) {
        $personaId = $criterios[0]['persona'];
        $tipoDocumentoId = $criterios[0]['tipoDocumento'];
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmision']['inicio']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmision']['fin']);
        $vencimientoInicio = $this->formatearFechaBD($criterios[0]['fechaVencimiento']['inicio']);
        $vencimientoFin = $this->formatearFechaBD($criterios[0]['fechaVencimiento']['fin']);
        $empresa = $criterios[0]['empresa'];
        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $tipoDocumentoIdFormateado = Util::convertirArrayXCadena($tipoDocumentoId);
        $empresaId = Util::convertirArrayXCadena($empresa);
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];
        return Reporte::create()->obtenerCantidadReporteBalanceConsolidadoXCriterios($empresaId, $tipoDocumentoIdFormateado, $personaId, $emisionInicio, $emisionFin, $vencimientoInicio, $vencimientoFin, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start);
    }

    public function obtenerCantidadesTotalesBalanceConsolidado($criterios) {
        $personaId = $criterios[0]['persona'];
        $tipoDocumentoId = $criterios[0]['tipoDocumento'];
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmision']['inicio']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmision']['fin']);
        $vencimientoInicio = $this->formatearFechaBD($criterios[0]['fechaVencimiento']['inicio']);
        $vencimientoFin = $this->formatearFechaBD($criterios[0]['fechaVencimiento']['fin']);
        $empresa = $criterios[0]['empresa'];
        $tipoDocumentoIdFormateado = Util::convertirArrayXCadena($tipoDocumentoId);
        $respuesta = new ObjectUtil();

        $importeTotal = Reporte::create()->obtenerCantidadesTotalesBalanceConsolidado($empresa, $tipoDocumentoIdFormateado, $personaId, $emisionInicio, $emisionFin, $vencimientoInicio, $vencimientoFin);

        $respuesta->total = $importeTotal[0]['total'];
        $respuesta->importe_pendiente = $importeTotal[0]['importe_pendiente'];
        $respuesta->importe_pagado = $importeTotal[0]['importe_pagado'];

        return $respuesta;
    }

    //fin de reporte balance consolidado
    //Reporte movimiento vienes
    public function obtenerConfiguracionesInicialesMovimientoPersona($idEmpresa) {
        $respuesta = new ObjectUtil();
        //$tipo = '(1)';
        //        $respuesta->organizador = OrganizadorNegocio::create()->obtenerOrganizadorActivoXEmpresa($idEmpresa);
        //$respuesta->documento_tipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXEmpresaXTipo($idEmpresa, $tipo);
        $respuesta->documento_tipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXEmpresa($idEmpresa);
        $respuesta->persona = PersonaNegocio::create()->obtenerActivas();
        $respuesta->bien = BienNegocio::create()->obtenerBienMovimientoEmpresa($idEmpresa);
        $respuesta->fecha_primer_documento = DocumentoNegocio::create()->obtenerFechaPrimerDocumento();

        return $respuesta;
    }

    public function obtenerMovimientoPersonaXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start) {
        $personaId = $criterios[0]['persona'];
        $tipoDocumentoId = $criterios[0]['documentoTipo'];
        $bienId = $criterios[0]['bien'];
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmisionDesde']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmisionHasta']);
        $empresa = $criterios[0]['empresa'];
        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $tipoDocumentoIdFormateado = Util::convertirArrayXCadena($tipoDocumentoId);
        $empresaId = Util::convertirArrayXCadena($empresa);
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];
        //        $fechaVencimientoDesde = DateUtil::formatearCadenaACadenaBD($valor['inicio']);;

        return Reporte::create()->obtenerMovimientoPersonaXCriterios($empresaId, $tipoDocumentoIdFormateado, $personaId, $bienId, $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start);
    }

    public function obtenerCantidadMovimientoPersonaXCriterio($criterios, $elemntosFiltrados, $columns, $order, $start) {
        $personaId = $criterios[0]['persona'];
        $tipoDocumentoId = $criterios[0]['documentoTipo'];
        $bienId = $criterios[0]['bien'];
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmisionDesde']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmisionHasta']);
        $empresa = $criterios[0]['empresa'];
        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $tipoDocumentoIdFormateado = Util::convertirArrayXCadena($tipoDocumentoId);
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];
        $empresaId = Util::convertirArrayXCadena($empresa);
        return Reporte::create()->obtenerCantidadMovimientoPersonaXCriterio($empresaId, $tipoDocumentoIdFormateado, $personaId, $bienId, $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start);
    }

    public function obtenerCantidadesTotalesMovimientoPersona($criterios) {
        $personaId = $criterios[0]['persona'];
        $tipoDocumentoId = $criterios[0]['documentoTipo'];
        $bienId = $criterios[0]['bien'];
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmisionDesde']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmisionHasta']);
        $empresa = $criterios[0]['empresa'];
        $tipoDocumentoIdFormateado = Util::convertirArrayXCadena($tipoDocumentoId);
        $empresaId = Util::convertirArrayXCadena($empresa);

        $respuesta = new ObjectUtil();

        $importeTotal = Reporte::create()->obtenerCantidadesTotalesMovimientoPersona($empresaId, $tipoDocumentoIdFormateado, $personaId, $bienId, $emisionInicio, $emisionFin);

        $respuesta->total = $importeTotal[0]['total'];
        $respuesta->cantidad_total = $importeTotal[0]['cantidad_total'];

        return $respuesta;
    }

    //Fin reporte movimiento bienes
    //Reporte balance consolidado general
    public function obtenerConfiguracionesInicialesBalanceConsolidadoGeneral($idTipos) {

        $respuesta = new ObjectUtil();
        //        $respuesta->organizador = OrganizadorNegocio::create()->obtenerOrganizadorActivoXEmpresa($idEmpresa);
        $respuesta->documento_tipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXTipos($idTipos);
        $respuesta->persona = PersonaNegocio::create()->obtenerActivas();
        $respuesta->empresa = EmpresaNegocio::create()->getEmpresaActivas();
        //        $respuesta->fecha_primer_documento = DocumentoNegocio::create()->obtenerFechaPrimerDocumento();
        return $respuesta;
    }

    public function obtenerReporteBalanceConsolidadoGeneralXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start) {
        $personaId = $criterios[0]['persona'];
        $tipoDocumentoId = $criterios[0]['tipoDocumento'];
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmision']['inicio']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmision']['fin']);
        $vencimientoInicio = $this->formatearFechaBD($criterios[0]['fechaVencimiento']['inicio']);
        $vencimientoFin = $this->formatearFechaBD($criterios[0]['fechaVencimiento']['fin']);
        $empresa = $criterios[0]['empresa'];
        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $tipoDocumentoIdFormateado = Util::convertirArrayXCadena($tipoDocumentoId);
        $empresaId = Util::convertirArrayXCadena($empresa);
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];
        //        $fechaVencimientoDesde = DateUtil::formatearCadenaACadenaBD($valor['inicio']);;
        return Reporte::create()->obtenerReporteReporteBalanceConsolidadoXCriterios($empresaId, $tipoDocumentoIdFormateado, $personaId, $emisionInicio, $emisionFin, $vencimientoInicio, $vencimientoFin, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start);
    }

    public function obtenerCantidadReporteBalanceConsolidadoGeneralXCriterio($criterios, $elemntosFiltrados, $columns, $order, $start) {
        $personaId = $criterios[0]['persona'];
        $tipoDocumentoId = $criterios[0]['tipoDocumento'];
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmision']['inicio']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmision']['fin']);
        $vencimientoInicio = $this->formatearFechaBD($criterios[0]['fechaVencimiento']['inicio']);
        $vencimientoFin = $this->formatearFechaBD($criterios[0]['fechaVencimiento']['fin']);
        $empresa = $criterios[0]['empresa'];
        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $tipoDocumentoIdFormateado = Util::convertirArrayXCadena($tipoDocumentoId);
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];
        $empresaId = Util::convertirArrayXCadena($empresa);
        return Reporte::create()->obtenerCantidadReporteBalanceConsolidadoXCriterios($empresaId, $tipoDocumentoIdFormateado, $personaId, $emisionInicio, $emisionFin, $vencimientoInicio, $vencimientoFin, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start);
    }

    public function obtenerCantidadesTotalesBalanceConsolidadoGeneral($criterios) {
        $personaId = $criterios[0]['persona'];
        $tipoDocumentoId = $criterios[0]['tipoDocumento'];
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmision']['inicio']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmision']['fin']);
        $vencimientoInicio = $this->formatearFechaBD($criterios[0]['fechaVencimiento']['inicio']);
        $vencimientoFin = $this->formatearFechaBD($criterios[0]['fechaVencimiento']['fin']);
        $empresa = $criterios[0]['empresa'];
        $tipoDocumentoIdFormateado = Util::convertirArrayXCadena($tipoDocumentoId);
        $empresaId = Util::convertirArrayXCadena($empresa);
        $respuesta = new ObjectUtil();

        $importeTotal = Reporte::create()->obtenerCantidadesTotalesBalanceConsolidado($empresaId, $tipoDocumentoIdFormateado, $personaId, $emisionInicio, $emisionFin, $vencimientoInicio, $vencimientoFin);

        $respuesta->total = $importeTotal[0]['total'];
        $respuesta->importe_pendiente = $importeTotal[0]['importe_pendiente'];
        $respuesta->importe_pagado = $importeTotal[0]['importe_pagado'];
        return $respuesta;
    }

    //fin reporte balance consolidado general 
    //Reporte movimiento persona 
    public function obtenerConfiguracionesInicialesMovimientoPersonaGeneral() {

        $respuesta = new ObjectUtil();
        //        $respuesta->documento_tipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXTipos($idTipos);        
        $respuesta->documento_tipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXEmpresa(-1);
        $respuesta->persona = PersonaNegocio::create()->obtenerActivas();
        $respuesta->empresa = EmpresaNegocio::create()->getEmpresaActivas();
        $respuesta->bien = BienNegocio::create()->obtenerBienXMovimientosActivos();
        return $respuesta;
    }

    public function obtenerCantidadesTotalesMovimientoPersonaGeneral($criterios) {
        $personaId = $criterios[0]['persona'];
        $tipoDocumentoId = $criterios[0]['documentoTipo'];
        $bienId = $criterios[0]['bien'];
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmisionDesde']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmisionHasta']);
        $empresa = $criterios[0]['empresa'];
        $tipoDocumentoIdFormateado = Util::convertirArrayXCadena($tipoDocumentoId);
        $empresaId = Util::convertirArrayXCadena($empresa);
        $respuesta = new ObjectUtil();
        $importeTotal = Reporte::create()->obtenerCantidadesTotalesMovimientoPersona($empresaId, $tipoDocumentoIdFormateado, $personaId, $bienId, $emisionInicio, $emisionFin);
        $respuesta->total = $importeTotal[0]['total'];
        $respuesta->cantidad_total = $importeTotal[0]['cantidad_total'];

        return $respuesta;
    }

    //fin reporte mocimiento persona

    public function obtenerBienesCantMinimaAlcanzada($criterios) {
        $bienId = $criterios[0]['bien'];
        $empresaId = $criterios[0]['empresaId'];
        $bienIdFormateado = Util::convertirArrayXCadena($bienId);
        return Reporte::create()->obtenerBienesCantMinimaAlcanzada($bienIdFormateado, $empresaId);
    }

    public function obtenerReporteBienesCantMinimaAlcanzadaExcel($criterios) {
        $respuestaReporteBienesCantMinimaAlcanzada = $this->obtenerBienesCantMinimaAlcanzada($criterios);
        if (ObjectUtil::isEmpty($respuestaReporteBienesCantMinimaAlcanzada)) {
            throw new WarningException("No existe datos para exportar");
        } else {
            $this->crearReporteBienesCantMinimaAlcanzadaExcel($respuestaReporteBienesCantMinimaAlcanzada, "Cotización de compra");
        }
    }

    private function crearReporteBienesCantMinimaAlcanzadaExcel($reportes, $titulo) {
        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':F' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $titulo);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':F' . $i)->applyFromArray($this->estiloTituloReporte);

        $i += 2;
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Bien');
        //        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'Organizador');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'Tipo bien');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'Unidad de medida');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'Stock actual');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'Stock mínimo');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, 'Proveedor');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':F' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;
        foreach ($reportes as $reporte) {
            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $reporte['bien_descripcion']);
            //            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $reporte['organizador_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $reporte['bien_tipo_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $reporte['unidad_medida_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, $reporte['stock']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, $reporte['cantidad_minima']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, str_replace('<br/>', "\n", $reporte['proveedor']));
            $objPHPExcel->getActiveSheet()->getStyle('F' . $i)->getAlignment()->setWrapText(true);

            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':F' . $i)->applyFromArray($this->estiloInformacion);
            $i += 1;
        }

        for ($i = 'A'; $i <= 'F'; $i++) {
            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle($titulo);
        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/ReporteBienCantMinAlcanzada.xlsx');
        return 1;
    }

    //reporte ventas por vendedor
    public function obtenerConfiguracionesInicialesVentasPorVendedor() {
        $respuesta = new ObjectUtil();
        $tipo = '(1)';
        $respuesta->documento_tipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXTipos($tipo);
        //        $respuesta->persona = PersonaNegocio::create()->obtenerPersonaPerfilVendedor();
        $respuesta->persona = PersonaNegocio::create()->obtenerComboPersonaXPersonaClaseId(-2);
        $respuesta->bien = BienNegocio::create()->obtenerBienMovimientoEmpresa($idEmpresa);
        $respuesta->fecha_primer_documento = DocumentoNegocio::create()->obtenerFechaPrimerDocumento();
        $respuesta->empresa = EmpresaNegocio::create()->getEmpresaActivas();
        $respuesta->bien_tipo = BienNegocio::create()->obtenerBienTipoPadre();

        return $respuesta;
    }

    public function obtenerCantidadesTotalesVentasPorVendedor($criterios) {
        $personaId = $criterios[0]['persona'];
        $tipoDocumentoId = $criterios[0]['documentoTipo'];
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmisionDesde']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmisionHasta']);
        $tipoDocumentoIdFormateado = Util::convertirArrayXCadena($tipoDocumentoId);

        //seleccion de bien tipo e hijos        
        $bienTipo = $criterios[0]['bienTipo'];

        if (ObjectUtil::isEmpty($bienTipo)) {
            $bienTipo = $criterios[0]['bienTipoPadre'];
        }

        if (!ObjectUtil::isEmpty($bienTipo)) {
            $dataBienTipoHijos = BienNegocio::create()->obtenerBienTipoHijosXBienTipoPadreId($bienTipo);
        }

        if (!ObjectUtil::isEmpty($dataBienTipoHijos)) {
            foreach ($dataBienTipoHijos as $item) {
                array_push($bienTipo, $item['id']);
            }
        }
        $bienTipoIdFormateado = Util::convertirArrayXCadena($bienTipo);
        // fin 

        $empresaId = $criterios[0]['empresa'];
        $empresaIdFormateado = Util::convertirArrayXCadena($empresaId);

        $importeTotal = Reporte::create()->obtenerCantidadesTotalesVentasPorVendedor($empresaIdFormateado, $tipoDocumentoIdFormateado, $personaId, $emisionInicio, $emisionFin, $bienTipoIdFormateado);

        //        $respuesta->total = $importeTotal[0]['total'];

        return $importeTotal;
    }

    public function obtenerReporteVentasPorVendedorXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start) {
        $personaId = $criterios[0]['persona'];
        $tipoDocumentoId = $criterios[0]['documentoTipo'];
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmisionDesde']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmisionHasta']);
        $tipoDocumentoIdFormateado = Util::convertirArrayXCadena($tipoDocumentoId);

        //seleccion de bien tipo e hijos        
        $bienTipo = $criterios[0]['bienTipo'];

        if (ObjectUtil::isEmpty($bienTipo)) {
            $bienTipo = $criterios[0]['bienTipoPadre'];
        }

        if (!ObjectUtil::isEmpty($bienTipo)) {
            $dataBienTipoHijos = BienNegocio::create()->obtenerBienTipoHijosXBienTipoPadreId($bienTipo);
        }

        if (!ObjectUtil::isEmpty($dataBienTipoHijos)) {
            foreach ($dataBienTipoHijos as $item) {
                array_push($bienTipo, $item['id']);
            }
        }
        $bienTipoIdFormateado = Util::convertirArrayXCadena($bienTipo);
        // fin 

        $empresaId = $criterios[0]['empresa'];
        $empresaIdFormateado = Util::convertirArrayXCadena($empresaId);

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];

        return Reporte::create()->obtenerReporteVentasPorVendedorXCriterios($empresaIdFormateado, $tipoDocumentoIdFormateado, $personaId, $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start, $bienTipoIdFormateado);
    }

    public function obtenerCantidadReporteVentasPorVendedorXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start) {
        $personaId = $criterios[0]['persona'];
        $tipoDocumentoId = $criterios[0]['documentoTipo'];
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmisionDesde']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmisionHasta']);
        $tipoDocumentoIdFormateado = Util::convertirArrayXCadena($tipoDocumentoId);

        //seleccion de bien tipo e hijos        
        $bienTipo = $criterios[0]['bienTipo'];

        if (ObjectUtil::isEmpty($bienTipo)) {
            $bienTipo = $criterios[0]['bienTipoPadre'];
        }

        if (!ObjectUtil::isEmpty($bienTipo)) {
            $dataBienTipoHijos = BienNegocio::create()->obtenerBienTipoHijosXBienTipoPadreId($bienTipo);
        }

        if (!ObjectUtil::isEmpty($dataBienTipoHijos)) {
            foreach ($dataBienTipoHijos as $item) {
                array_push($bienTipo, $item['id']);
            }
        }
        $bienTipoIdFormateado = Util::convertirArrayXCadena($bienTipo);
        // fin 

        $empresaId = $criterios[0]['empresa'];
        $empresaIdFormateado = Util::convertirArrayXCadena($empresaId);

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];
        return Reporte::create()->obtenerCantidadReporteVentasPorVendedorXCriterios($empresaIdFormateado, $tipoDocumentoIdFormateado, $personaId, $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar, $bienTipoIdFormateado);
    }

    public function obtenerReporteVentasPorVendedorExcel($data) {
        if (ObjectUtil::isEmpty($data)) {
            throw new WarningException("No existe datos para exportar");
        } else {
            $this->crearReporteVentasPorVendedorExcel($data, "REPORTE DE VENTAS POR VENDEDOR");
        }
    }

    private function crearReporteVentasPorVendedorExcel($reportes, $titulo) {

        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':I' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $titulo);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':I' . $i)->applyFromArray($this->estiloTituloReporte);

        $i += 2;
        //Vendedor G.P. Principal G.P. Secundario F. Emisión Tipo documento Cliente S|N	Total S/.	Total $
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Vendedor');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'G.P. Principal');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'G.P. Secundario');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'F. Emisión');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'Tipo documento');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, 'Cliente');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, 'S|N');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, 'Total S/.');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, 'Total $');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':I' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;

        foreach ($reportes as $reporte) {

            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':I' . $i)->applyFromArray($this->estiloInformacion);

            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $reporte['vendedor_nombre']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $reporte['bien_tipo_padre_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $reporte['bien_tipo_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, str_replace(' 00:00:00', '', $reporte['fecha_emision']));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, $reporte['documento_tipo_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $reporte['persona_nombre_completo']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, $reporte['serie_numero']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, $reporte['total_soles']);
            $objPHPExcel->getActiveSheet()->getStyle('H' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, $reporte['total_dolares']);
            $objPHPExcel->getActiveSheet()->getStyle('I' . $i)->getNumberFormat()->setFormatCode('#,##0.00');

            $i += 1;
        }

        for ($i = 'A'; $i <= 'I'; $i++) {

            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle('reporte');

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/reporte.xlsx');

        return 1;
    }

    //reporte ventas por tienda
    public function obtenerConfiguracionesInicialesVentasPorTienda() {
        $respuesta = new ObjectUtil();
        $tipo = '(1)';
        $respuesta->documento_tipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXTipos($tipo);
        $respuesta->empresa = EmpresaNegocio::create()->getEmpresaActivas();
        $respuesta->bien = BienNegocio::create()->obtenerBienMovimientoEmpresa($idEmpresa);
        $respuesta->fecha_primer_documento = DocumentoNegocio::create()->obtenerFechaPrimerDocumento();

        return $respuesta;
    }

    public function obtenerCantidadesTotalesVentasPorTienda($criterios) {
        $empresaId = $criterios[0]['empresa'];
        $tipoDocumentoId = $criterios[0]['documentoTipo'];
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmisionDesde']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmisionHasta']);
        $tipoDocumentoIdFormateado = Util::convertirArrayXCadena($tipoDocumentoId);
        $empresaIdFormateado = Util::convertirArrayXCadena($empresaId);

        $respuesta = new ObjectUtil();

        $importeTotal = Reporte::create()->obtenerCantidadesTotalesVentasPorTienda($tipoDocumentoIdFormateado, $empresaIdFormateado, $emisionInicio, $emisionFin);

        $respuesta = $importeTotal;

        return $respuesta;
    }

    public function obtenerReporteVentasPorTiendaXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start) {
        $empresaId = $criterios[0]['empresa'];
        $tipoDocumentoId = $criterios[0]['documentoTipo'];
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmisionDesde']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmisionHasta']);
        $tipoDocumentoIdFormateado = Util::convertirArrayXCadena($tipoDocumentoId);
        $empresaIdFormateado = Util::convertirArrayXCadena($empresaId);

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];

        return Reporte::create()->obtenerReporteVentasPorTiendaXCriterios($tipoDocumentoIdFormateado, $empresaIdFormateado, $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start);
    }

    public function obtenerCantidadReporteVentasPorTiendaXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start) {
        $empresaId = $criterios[0]['empresa'];
        $tipoDocumentoId = $criterios[0]['documentoTipo'];
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmisionDesde']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmisionHasta']);
        $tipoDocumentoIdFormateado = Util::convertirArrayXCadena($tipoDocumentoId);
        $empresaIdFormateado = Util::convertirArrayXCadena($empresaId);

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];
        return Reporte::create()->obtenerCantidadReporteVentasPorTiendaXCriterios($tipoDocumentoIdFormateado, $empresaIdFormateado, $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start);
    }

    public function obtenerReporteVentasPorTiendaExcel($data) {
        if (ObjectUtil::isEmpty($data)) {
            throw new WarningException("No existe datos para exportar");
        } else {
            $this->crearReporteVentasPorTiendaExcel($data, "Reporte de Ventas por Empresa");
        }
    }

    private function crearReporteVentasPorTiendaExcel($reportes, $titulo) {

        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':I' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $titulo);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':I' . $i)->applyFromArray($this->estiloTituloReporte);

        $i += 2;
        /* F. Creación	F. Emisión	Vendedor	Tipo documento	Cliente	Serie	Número	Total
         */
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'F. Creación');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'F. Emisión');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'Empresa');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'Tipo documento');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'Cliente');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, 'Serie');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, 'Número');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, 'Total S/.');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, 'Total $');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':I' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;

        foreach ($reportes as $reporte) {

            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':I' . $i)->applyFromArray($this->estiloInformacion);

            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $reporte['fecha_creacion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $reporte['fecha_emision']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $reporte['razon_social']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, $reporte['documento_tipo_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, $reporte['persona_nombre_completo']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $reporte['serie']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, $reporte['numero']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, $reporte['total_soles']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, $reporte['total_dolares']);

            $i += 1;
        }

        for ($i = 'A'; $i <= 'I'; $i++) {

            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle($titulo);

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/reporte.xlsx');

        return 1;
    }

    //Reporte ventas comision por vendedor
    public function obtenerConfiguracionesInicialesComisionVendedor() {
        $respuesta = new ObjectUtil();
        //        $respuesta->persona = PersonaNegocio::create()->obtenerPersonaPerfilVendedor();
        $respuesta->persona = PersonaNegocio::create()->obtenerComboPersonaXPersonaClaseId(-2);
        $respuesta->fecha_primer_documento = DocumentoNegocio::create()->obtenerFechaPrimerDocumento();
        $respuesta->empresa = EmpresaNegocio::create()->getEmpresaActivas();

        return $respuesta;
    }

    public function reporteComisionVendedor($criterios) {

        $vendedor = $criterios[0]['vendedor'];
        $porcentaje = $criterios[0]['porcentaje'];

        $empresaId = $criterios[0]['empresa'];
        $empresaIdFormateado = Util::convertirArrayXCadena($empresaId);

        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmision']['inicio']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmision']['fin']);

        $vendedorIdFormateado = Util::convertirArrayXCadena($vendedor);

        return Reporte::create()->reporteVentasComisionVendedor($empresaIdFormateado, $porcentaje, $vendedorIdFormateado, $emisionInicio, $emisionFin);
    }

    public function obtenerReporteComisionVendedorExcel($criterios) {

        $respuesta = $this->reporteComisionVendedor($criterios);

        if (ObjectUtil::isEmpty($respuesta)) {
            throw new WarningException("No existe datos para exportar");
        } else {
            $this->crearReporteComisionVendedorExcel($respuesta, "Reporte de Comisión por Vendedor");
        }
    }

    private function crearReporteComisionVendedorExcel($reportes, $titulo) {

        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':C' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $titulo);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':C' . $i)->applyFromArray($this->estiloTituloReporte);

        $i += 2;

        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Vendedor');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'Total ventas');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'Comisión');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':C' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;

        foreach ($reportes as $reporte) {

            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $reporte['vendedor_nombre']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, round($reporte['total_ventas'], 2));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, round($reporte['comision'], 2));

            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':C' . $i)->applyFromArray($this->estiloInformacion);

            $i += 1;
        }

        for ($i = 'A'; $i <= 'C'; $i++) {

            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle("reporte");

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/reporte.xlsx');

        return 1;
    }

    //reporte ventas por tiempo
    public function obtenerConfiguracionesInicialesPorTiempo() {
        $respuesta = new ObjectUtil();
        $respuesta->empresa = EmpresaNegocio::create()->getEmpresaActivas();
        $respuesta->fecha_primer_documento = DocumentoNegocio::create()->obtenerFechaPrimerDocumento();

        return $respuesta;
    }

    public function reportePorTiempo($criterios) {

        $tienda = $criterios[0]['tienda'];
        $tiempo = $criterios[0]['tiempo'];

        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmision']['inicio']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmision']['fin']);

        $tiendaIdFormateado = Util::convertirArrayXCadena($tienda);

        return Reporte::create()->reporteVentasPorTiempo($tiempo, $tiendaIdFormateado, $emisionInicio, $emisionFin);
    }

    public function obtenerCantidadesTotalesVentasPorTiempo($criterios) {
        $tienda = $criterios[0]['tienda'];
        $tiempo = $criterios[0]['tiempo'];

        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmision']['inicio']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmision']['fin']);

        $tiendaIdFormateado = Util::convertirArrayXCadena($tienda);

        $importeTotal = Reporte::create()->obtenerCantidadesTotalesVentasPorTiempo($tiempo, $tiendaIdFormateado, $emisionInicio, $emisionFin);

        return $importeTotal;
    }

    public function obtenerReportePorTiempoExcel($criterios) {

        $respuesta = $this->reportePorTiempo($criterios);

        if (ObjectUtil::isEmpty($respuesta)) {
            throw new WarningException("No existe datos para exportar");
        } else {
            $this->crearReportePorTiempoExcel($respuesta, "Reporte de Ventas por Tiempo");
        }
    }

    private function crearReportePorTiempoExcel($reportes, $titulo) {

        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':E' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $titulo);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':E' . $i)->applyFromArray($this->estiloTituloReporte);

        $i += 2;
        $objPHPExcel->getActiveSheet()->mergeCells('B' . $i . ':C' . $i);
        $objPHPExcel->getActiveSheet()->mergeCells('D' . $i . ':E' . $i);

        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'SOLES');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'DOLARES');

        $objPHPExcel->getActiveSheet()->getStyle('B' . $i . ':E' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Tiempo');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'Núm. ventas');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'Total ventas');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'Núm. ventas');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'Total ventas');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':E' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;

        foreach ($reportes as $reporte) {

            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $reporte['fecha_tiempo']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $reporte['numero_soles']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, round($reporte['total_soles'], 2));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, $reporte['numero_dolares']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, round($reporte['total_dolares'], 2));

            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':E' . $i)->applyFromArray($this->estiloInformacion);

            $i += 1;
        }

        for ($i = 'A'; $i <= 'E'; $i++) {

            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle("reporte");

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/reporte.xlsx');

        return 1;
    }

    //Reporte ventas productos mas vendidos
    public function obtenerConfiguracionesInicialesProductosMasVendidos() {
        $respuesta = new ObjectUtil();
        $respuesta->empresa = EmpresaNegocio::create()->getEmpresaActivas();
        $respuesta->fecha_primer_documento = DocumentoNegocio::create()->obtenerFechaPrimerDocumento();

        return $respuesta;
    }

    public function reporteProductosMasVendidos($criterios) {

        $tienda = $criterios[0]['tienda'];
        $limite = $criterios[0]['limite'];

        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmision']['inicio']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmision']['fin']);

        $tiendaIdFormateado = Util::convertirArrayXCadena($tienda);

        return Reporte::create()->reporteVentasProductosMasVendidos($limite, $tiendaIdFormateado, $emisionInicio, $emisionFin);
    }

    public function obtenerReporteProductosMasVendidosExcel($criterios) {

        $respuesta = $this->reporteProductosMasVendidos($criterios);

        if (ObjectUtil::isEmpty($respuesta)) {
            throw new WarningException("No existe datos para exportar");
        } else {
            $this->crearReporteProductosMasVendidosExcel($respuesta, "Reporte de Productos más Vendidos");
        }
    }

    private function crearReporteProductosMasVendidosExcel($reportes, $titulo) {

        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':D' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $titulo);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':D' . $i)->applyFromArray($this->estiloTituloReporte);

        $i += 2;

        $objPHPExcel->getActiveSheet()->mergeCells('B' . $i . ':C' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'Núm. de ventas');
        $objPHPExcel->getActiveSheet()->getStyle('B' . $i . ':C' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;

        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Producto');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'Soles');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'Dólares');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'Total');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':D' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;

        foreach ($reportes as $reporte) {

            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $reporte['bien_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $reporte['productos_soles']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $reporte['productos_dolares']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, $reporte['productos_vendidos']);

            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':D' . $i)->applyFromArray($this->estiloInformacion);

            $i += 1;
        }

        for ($i = 'A'; $i <= 'D'; $i++) {

            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle("reporte");

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/reporte.xlsx');

        return 1;
    }

    //reporte ventas y compras por producto
    public function obtenerConfiguracionesInicialesPorProducto() {
        $respuesta = new ObjectUtil();
        $respuesta->empresa = EmpresaNegocio::create()->getEmpresaActivas();
        $respuesta->fecha_primer_documento = DocumentoNegocio::create()->obtenerFechaPrimerDocumento();
        $respuesta->bien = BienNegocio::create()->obtenerBienKardexXEmpresa(-1);
        //        $respuesta->bien_tipo = BienNegocio::create()->obtenerBienTipo();
        $respuesta->bien_tipo = BienNegocio::create()->obtenerBienTipoPadre();

        return $respuesta;
    }

    public function reportePorProducto($criterios, $tipo) {

        $tienda = $criterios[0]['tienda'];
        $bien = $criterios[0]['bien'];
        $bienTipo = $criterios[0]['bienTipo'];

        if (ObjectUtil::isEmpty($bienTipo)) {
            $bienTipo = $criterios[0]['bienTipoPadre'];
        }

        if (!ObjectUtil::isEmpty($bienTipo)) {
            $dataBienTipoHijos = BienNegocio::create()->obtenerBienTipoHijosXBienTipoPadreId($bienTipo);
        }

        if (!ObjectUtil::isEmpty($dataBienTipoHijos)) {
            foreach ($dataBienTipoHijos as $item) {
                array_push($bienTipo, $item['id']);
            }
        }

        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmision']['inicio']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmision']['fin']);

        $tiendaIdFormateado = Util::convertirArrayXCadena($tienda);
        $bienIdFormateado = Util::convertirArrayXCadena($bien);
        $bienTipoIdFormateado = Util::convertirArrayXCadena($bienTipo);

        return Reporte::create()->reporteVentasPorProducto($bienIdFormateado, $bienTipoIdFormateado, $tiendaIdFormateado, $emisionInicio, $emisionFin, $tipo);
    }

    public function obtenerReportePorProductoExcel($criterios, $tipo) {

        $respuesta = $this->reportePorProducto($criterios, $tipo);

        if ($tipo == 1) {
            $parametro->titulo = "REPORTE DE VENTAS POR PRODUCTO";
            $parametro->columnaImporte = "Importe vendido";
        }
        if ($tipo == 4) {
            $parametro->titulo = "REPORTE DE COMPRAS POR PRODUCTO";
            $parametro->columnaImporte = "Importe comprado";
        }

        if (ObjectUtil::isEmpty($respuesta)) {
            throw new WarningException("No existe datos para exportar");
        } else {
            $this->crearReportePorProductoExcel($respuesta, $parametro);
        }
    }

    private function crearReportePorProductoExcel($reportes, $parametro) {
        $titulo = $parametro->titulo;

        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':H' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $titulo);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':H' . $i)->applyFromArray($this->estiloTituloReporte);

        $i += 2;
        //Cód. Bien	Bien	Tipo bien		Importe vendido
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Código');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'Producto');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'G.P. Principal');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'G.P. Secundario');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'Cantidad');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, 'Unidad control');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, $parametro->columnaImporte . ' S/.');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, $parametro->columnaImporte . ' $');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':H' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;

        foreach ($reportes as $reporte) {
            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $reporte['bien_codigo']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $reporte['bien_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $reporte['bien_tipo_padre_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, $reporte['bien_tipo_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, $reporte['cantidad_conv']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $reporte['unidad_control']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, round($reporte['importe_total_soles'], 2));
            $objPHPExcel->getActiveSheet()->getStyle('G' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, round($reporte['importe_total_dolares'], 2));
            $objPHPExcel->getActiveSheet()->getStyle('H' . $i)->getNumberFormat()->setFormatCode('#,##0.00');

            $objPHPExcel->getActiveSheet()->getStyle('A' . $i)->applyFromArray($this->estiloTextoInformacion);
            $objPHPExcel->getActiveSheet()->getStyle('B' . $i . ':H' . $i)->applyFromArray($this->estiloInformacion);

            $i += 1;
        }

        for ($i = 'A'; $i <= 'H'; $i++) {

            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle("reporte");

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/reporte.xlsx');

        return 1;
    }

    //reporte ventas por stock valorizado
    public function obtenerConfiguracionesInicialesStockValorizado() {
        $respuesta = new ObjectUtil();
        $respuesta->organizador = OrganizadorNegocio::create()->obtenerOrganizadorActivo(56);
        $respuesta->bien = BienNegocio::create()->obtenerBienKardexXEmpresa(-1);
        $respuesta->bien_tipo = BienNegocio::create()->obtenerBienTipo();

        return $respuesta;
    }

    public function reporteStockValorizado($criterios) {

        $organizador = $criterios[0]['organizador'];
        $bien = $criterios[0]['bien'];
        $bienTipo = $criterios[0]['bienTipo'];

        $organizadorIdFormateado = Util::convertirArrayXCadena($organizador);
        $bienIdFormateado = Util::convertirArrayXCadena($bien);
        $bienTipoIdFormateado = Util::convertirArrayXCadena($bienTipo);

        return Reporte::create()->reporteVentasStockValorizado($bienIdFormateado, $bienTipoIdFormateado, $organizadorIdFormateado);
    }

    public function obtenerReporteStockValorizadoExcel($criterios) {

        $respuesta = $this->reporteStockValorizado($criterios);

        if (ObjectUtil::isEmpty($respuesta)) {
            throw new WarningException("No existe datos para exportar");
        } else {
            $this->crearReporteStockValorizadoExcel($respuesta, "Reporte de Stock Valorizado");
        }
    }

    private function crearReporteStockValorizadoExcel($reportes, $titulo) {

        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':E' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $titulo);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':E' . $i)->applyFromArray($this->estiloTituloReporte);

        $i += 2;
        //Tipo bien	Bien	Stock	Unidad control	Stock valorizado
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Tipo producto');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'Producto');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'Stock');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'Unidad control');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'Stock valorizado');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':E' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;

        foreach ($reportes as $reporte) {

            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $reporte['bien_tipo_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $reporte['bien_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, round($reporte['stock'], 2));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, $reporte['unidad_control']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, round($reporte['stock_valorizado'], 2));

            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':E' . $i)->applyFromArray($this->estiloInformacion);

            $i += 1;
        }

        for ($i = 'A'; $i <= 'E'; $i++) {

            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle("reporte");

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/reporte.xlsx');

        return 1;
    }

    //reporte compras
    public function obtenerConfiguracionesInicialesReporteCompras() {

        $provedoresNacionales = PersonaNegocio::create()->obtenerComboPersonaXPersonaClaseId(-1);
        $proveedores_extra = PersonaNegocio::create()->obtenerComboPersonaXPersonaClaseId(17);
        $respuesta = new ObjectUtil();
        $tipo = '(4)';
        $respuesta->documento_tipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXTipos($tipo);

        $respuesta->persona = array_merge($provedoresNacionales, $proveedores_extra);
        $respuesta->fecha_primer_documento = DocumentoNegocio::create()->obtenerFechaPrimerDocumento();

        return $respuesta;
    }

    public function obtenerCantidadesTotalesReporteCompras($criterios) {
        $personaId = $criterios[0]['persona'];
        $tipoDocumentoId = $criterios[0]['documentoTipo'];
        $origen = $criterios[0]['origen'];
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmisionDesde']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmisionHasta']);
        $tipoDocumentoIdFormateado = Util::convertirArrayXCadena($tipoDocumentoId);

        $respuesta = new ObjectUtil();

        $importeTotal = Reporte::create()->obtenerCantidadesTotalesReporteCompras($tipoDocumentoIdFormateado, $personaId, $origen, $emisionInicio, $emisionFin);

        $respuesta->total = $importeTotal[0]['total'];

        return $respuesta;
    }

    public function obtenerReporteReporteComprasXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start) {
        $personaId = $criterios[0]['persona'];
        $tipoDocumentoId = $criterios[0]['documentoTipo'];
        $origen = $criterios[0]['origen'];
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmisionDesde']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmisionHasta']);
        $tipoDocumentoIdFormateado = Util::convertirArrayXCadena($tipoDocumentoId);

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];

        return Reporte::create()->obtenerReporteReporteComprasXCriterios($tipoDocumentoIdFormateado, $personaId, $origen, $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start);
    }

    public function obtenerReporteReporteComprasXCriteriosProducto($criterios, $elemntosFiltrados, $columns, $order, $start) {
        $personaId = $criterios[0]['persona'];
        $tipoDocumentoId = $criterios[0]['documentoTipo'];
        $origen = $criterios[0]['origen'];
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmisionDesde']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmisionHasta']);
        $tipoDocumentoIdFormateado = Util::convertirArrayXCadena($tipoDocumentoId);

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];

        return Reporte::create()->obtenerReporteReporteComprasXCriteriosProducto($tipoDocumentoIdFormateado, $personaId, $origen, $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start);
    }

    public function obtenerReporteReporteAtenciones($criterios, $elemntosFiltrados, $columns, $order, $start) {
        //        $personaId = $criterios[0]['persona'];
        $tipoDocumentoId = $criterios[0]['documentoTipo'];
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmisionDesde']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmisionHasta']);
        $tipoDocumentoIdFormateado = Util::convertirArrayXCadena($tipoDocumentoId);
        if ($tipoDocumentoIdFormateado == "")
            $tipoDocumentoIdFormateado = "-1";
        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];
        $datita = Reporte::create()->obtenerReporteReporteAtenciones($tipoDocumentoIdFormateado, $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start);

        //        foreach ($datita as $index => $data)
        //        {
        //            $data[$index]['total'] = ""
        //        }

        return $datita;
    }

    public function obtenerCantidadReporteReporteComprasXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start) {
        $personaId = $criterios[0]['persona'];
        $tipoDocumentoId = $criterios[0]['documentoTipo'];
        $origen = $criterios[0]['origen'];
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmisionDesde']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmisionHasta']);
        $tipoDocumentoIdFormateado = Util::convertirArrayXCadena($tipoDocumentoId);

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];
        return Reporte::create()->obtenerCantidadReporteReporteComprasXCriterios($tipoDocumentoIdFormateado, $personaId, $origen, $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start);
        $tamanio = count($data);
        for ($i = 0; $i < $tamanio; $i++) {
            $stringAcciones = '';
            if ($data[$i]['documento_id'] != '' && $data[$i]['movimiento_id'] != '') {
                $stringAcciones = '<a onclick="verDetalleCompras(' . $data[$i]['documento_id'] . ',' . $data[$i]['movimiento_id'] . ')"><b><i class="fa fa-eye"" style="color:b"></i><b></a>';
            }
            $data[$i]['acciones'] = $stringAcciones;
        }
    }

    public function obtenerCantidadReporteReporteAtencionesXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start) {
        $tipoDocumentoId = $criterios[0]['documentoTipo'];
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmisionDesde']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmisionHasta']);
        $tipoDocumentoIdFormateado = Util::convertirArrayXCadena($tipoDocumentoId);

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];
        return Reporte::create()->obtenerCantidadReporteReporteAtencionesXCriterios($tipoDocumentoIdFormateado, $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start);
    }

    public function obtenerReporteReporteComprasExcel($data) {
        if (ObjectUtil::isEmpty($data)) {
            throw new WarningException("No existe datos para exportar");
        } else {
            $this->crearReporteReporteComprasExcel($data, "Reporte de Compras");
        }
    }

    private function crearReporteReporteComprasExcel($reportes, $titulo) {

        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':I' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $titulo);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':I' . $i)->applyFromArray($this->estiloTituloReporte);

        $i += 2;
        /* F. Creación	F. Emisión	Vendedor	Tipo documento	Cliente	Serie	Número	Total
         */
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'F. Creación');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'F. Emisión');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'Usuario');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'Tipo documento');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'Proveedor');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, 'Serie');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, 'Número');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, 'Origen');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, 'Total');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':I' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;

        foreach ($reportes as $reporte) {

            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':I' . $i)->applyFromArray($this->estiloInformacion);

            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $reporte['fecha_creacion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $reporte['fecha_emision']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $reporte['usuario_nombre']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, $reporte['documento_tipo_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, $reporte['persona_nombre_completo']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $reporte['serie']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, $reporte['numero']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, $reporte['origen']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, $reporte['total']);

            $i += 1;
        }

        for ($i = 'A'; $i <= 'I'; $i++) {

            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle($titulo);

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/reporte.xlsx');

        return 1;
    }

    public function obtenerReporteComprasProducto($data) {
        if (ObjectUtil::isEmpty($data)) {
            throw new WarningException("No existe datos para exportar");
        } else {
            $this->crearReporteReporteComprasExcelProducto($data, "Reporte de Compras");
        }
    }

    private function crearReporteReporteComprasExcelProducto($reportes, $titulo) {

        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':L' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $titulo);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':L' . $i)->applyFromArray($this->estiloTituloReporte);

        $i += 2;
        /* F. Creación	F. Emisión	Vendedor	Tipo documento	Cliente	Serie	Número	Total
         */
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'F. Creación');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'F. Emisión');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'Usuario');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'Tipo documento');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'Proveedor');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, 'Serie');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, 'Número');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, 'Bien');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, 'Cantidad');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('J' . $i, 'Sub Total');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('K' . $i, 'Origen');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('L' . $i, 'Total');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':L' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;

        foreach ($reportes as $reporte) {

            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':L' . $i)->applyFromArray($this->estiloInformacion);

            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $reporte['fecha_creacion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $reporte['fecha_emision']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $reporte['usuario_nombre']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, $reporte['documento_tipo_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, $reporte['persona_nombre_completo']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $reporte['serie']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, $reporte['numero']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, $reporte['bien']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, $reporte['cantidad']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('J' . $i, $reporte['subtotal']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('K' . $i, $reporte['origen']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('L' . $i, $reporte['total']);

            $i += 1;
        }

        for ($i = 'A'; $i <= 'L'; $i++) {

            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle($titulo);

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/reporte.xlsx');

        return 1;
    }

    //reporte activos fijos
    public function obtenerConfiguracionesInicialesActivosFijos() {
        $respuesta = new ObjectUtil();
        $respuesta->empresa = EmpresaNegocio::create()->getEmpresaActivas();
        $respuesta->fecha_primer_documento = DocumentoNegocio::create()->obtenerFechaPrimerDocumento();
        $respuesta->bien = BienNegocio::create()->obtenerActivosFijosXEmpresa(-1);
        $respuesta->motivo = DocumentoTipoDatoListaNegocio::create()->obtenerXDocumentoTipoDato(624);

        return $respuesta;
    }

    public function reporteActivosFijos($criterios) {

        $tienda = $criterios[0]['tienda'];
        $bien = $criterios[0]['bien'];
        $motivo = $criterios[0]['motivo'];

        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmision']['inicio']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmision']['fin']);

        $tiendaIdFormateado = Util::convertirArrayXCadena($tienda);
        $bienIdFormateado = Util::convertirArrayXCadena($bien);
        $motivoIdFormateado = Util::convertirArrayXCadena($motivo);

        return Reporte::create()->reporteVentasActivosFijos($bienIdFormateado, $motivoIdFormateado, $tiendaIdFormateado, $emisionInicio, $emisionFin);
    }

    public function obtenerReporteActivosFijosExcel($criterios) {

        $respuesta = $this->reporteActivosFijos($criterios);

        if (ObjectUtil::isEmpty($respuesta)) {
            throw new WarningException("No existe datos para exportar");
        } else {
            $this->crearReporteActivosFijosExcel($respuesta, "Reporte de Activos Fijos");
        }
    }

    private function crearReporteActivosFijosExcel($reportes, $titulo) {

        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':G' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $titulo);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':G' . $i)->applyFromArray($this->estiloTituloReporte);

        $i += 2;
        //Bien	Motivo	Proveedor	Tipo documento	Serie	Número	Precio
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Bien');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'Motivo');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'Proveedor');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'Tipo documento');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'Serie');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, 'Número');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, 'Precio');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':G' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;

        foreach ($reportes as $reporte) {

            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $reporte['bien_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $reporte['tipo_lista_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $reporte['persona_nombre_completo']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, $reporte['documento_tipo_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, $reporte['serie']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $reporte['numero']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, round($reporte['valor_monetario'], 2));

            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':G' . $i)->applyFromArray($this->estiloInformacion);

            $i += 1;
        }

        for ($i = 'A'; $i <= 'G'; $i++) {

            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle("reporte");

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/reporte.xlsx');

        return 1;
    }

    //reporte estadistico de ventas
    public function obtenerConfiguracionesInicialesReporteEstadisticoVentas() {

        $respuesta = new ObjectUtil();
        //$respuesta->organizador = OrganizadorNegocio::create()->obtenerOrganizadorActivo(56);        
        $respuesta->empresa = EmpresaNegocio::create()->getEmpresaActivas();
        $respuesta->bien = BienNegocio::create()->obtenerBienKardexXEmpresa(-1);
        $respuesta->bien_tipo = BienNegocio::create()->obtenerBienTipo();
        $respuesta->fecha_primer_documento = DocumentoNegocio::create()->obtenerFechaPrimerDocumento();
        $tipo = '(1)';
        $respuesta->documento_tipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXTipos($tipo);
        //  $respuesta->tipo_frecuencia = DocumentoTipoDatoListaNegocio::create()->obtenerXDocumentoTipoDato(505);;        
        $respuesta->dataMoneda = MonedaNegocio::create()->obtenerComboMoneda();
        return $respuesta;
    }

    public function reporteReporteEstadisticoVentas($criterios) {

        $empresaId = $criterios[0]['empresa'];
        $bienId = $criterios[0]['bien'];
        $bienTipoId = $criterios[0]['bienTipo'];
        $documentoTipoId = $criterios[0]['documentoTipo'];
        $tipoFrecuenciaId = $criterios[0]['tipoFrecuencia'];
        $monedaId = $criterios[0]['monedaId'];

        if (!ObjectUtil::isEmpty($bienTipoId)) {
            $dataBienTipoHijos = BienNegocio::create()->obtenerBienTipoHijosXBienTipoPadreId($bienTipoId);
        }

        if (!ObjectUtil::isEmpty($dataBienTipoHijos)) {
            foreach ($dataBienTipoHijos as $item) {
                array_push($bienTipoId, $item['id']);
            }
        }

        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmision']['inicio']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmision']['fin']);

        if ($tipoFrecuenciaId == 1) {
            $dias = (strtotime($emisionInicio) - strtotime($emisionFin)) / 86400;
            $dias = abs($dias);
            $dias = floor($dias);

            if ($dias > 30) {
                throw new WarningException("Intérvalo de días superior a 30");
            }
        }
        if ($tipoFrecuenciaId == 2) {
            $meses = (strtotime($emisionInicio) - strtotime($emisionFin)) / (86400 * 30);
            $meses = abs($meses);
            $meses = floor($meses);

            if ($meses > 30) {
                throw new WarningException("Intérvalo de meses superior a 30");
            }
        }


        $empresaIdFormateado = Util::convertirArrayXCadena($empresaId);
        $bienIdFormateado = Util::convertirArrayXCadena($bienId);
        $bienTipoIdFormateado = Util::convertirArrayXCadena($bienTipoId);
        $documentoTipoIdFormateado = Util::convertirArrayXCadena($documentoTipoId);

        return Reporte::create()->reporteReporteEstadisticoVentas($empresaIdFormateado, $bienIdFormateado, $bienTipoIdFormateado, $emisionInicio, $emisionFin, $documentoTipoIdFormateado, $tipoFrecuenciaId, $monedaId);
    }

    //reporte ventas por cliente
    public function obtenerConfiguracionesInicialesVentasPorCliente() {
        $respuesta = new ObjectUtil();
        $tipo = '(1)';
        $respuesta->documento_tipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXTipos($tipo);
        //        $respuesta->persona = PersonaNegocio::create()->obtenerComboPersonaXPersonaClaseId(16);// 16: cliente
        $respuesta->persona = PersonaNegocio::create()->obtenerActivas();
        $respuesta->bien = BienNegocio::create()->obtenerBienMovimientoEmpresa($idEmpresa);
        $respuesta->fecha_primer_documento = DocumentoNegocio::create()->obtenerFechaPrimerDocumento();
        $respuesta->empresa = EmpresaNegocio::create()->getEmpresaActivas();
        $respuesta->bien_tipo = BienNegocio::create()->obtenerBienTipoPadre();

        return $respuesta;
    }

    public function obtenerCantidadesTotalesVentasPorCliente($criterios) {
        $personaId = $criterios[0]['persona'];
        $tipoDocumentoId = $criterios[0]['documentoTipo'];
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmisionDesde']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmisionHasta']);

        //seleccion de bien tipo e hijos        
        $bienTipo = $criterios[0]['bienTipo'];

        if (ObjectUtil::isEmpty($bienTipo)) {
            $bienTipo = $criterios[0]['bienTipoPadre'];
        }

        if (!ObjectUtil::isEmpty($bienTipo)) {
            $dataBienTipoHijos = BienNegocio::create()->obtenerBienTipoHijosXBienTipoPadreId($bienTipo);
        }

        if (!ObjectUtil::isEmpty($dataBienTipoHijos)) {
            foreach ($dataBienTipoHijos as $item) {
                array_push($bienTipo, $item['id']);
            }
        }
        $bienTipoIdFormateado = Util::convertirArrayXCadena($bienTipo);
        // fin         

        $tipoDocumentoIdFormateado = Util::convertirArrayXCadena($tipoDocumentoId);

        $empresaId = $criterios[0]['empresa'];
        $empresaIdFormateado = Util::convertirArrayXCadena($empresaId);

        $respuesta = new ObjectUtil();

        $importeTotal = Reporte::create()->obtenerCantidadesTotalesVentasPorCliente($empresaIdFormateado, $tipoDocumentoIdFormateado, $personaId, $emisionInicio, $emisionFin, $bienTipoIdFormateado);

        $respuesta->totalSoles = $importeTotal[0]['total_soles'];
        $respuesta->totalDolares = $importeTotal[0]['total_dolares'];

        return $respuesta;
    }

    public function obtenerReporteVentasPorClienteXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start) {
        $personaId = $criterios[0]['persona'];
        $tipoDocumentoId = $criterios[0]['documentoTipo'];
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmisionDesde']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmisionHasta']);

        //seleccion de bien tipo e hijos        
        $bienTipo = $criterios[0]['bienTipo'];

        if (ObjectUtil::isEmpty($bienTipo)) {
            $bienTipo = $criterios[0]['bienTipoPadre'];
        }

        if (!ObjectUtil::isEmpty($bienTipo)) {
            $dataBienTipoHijos = BienNegocio::create()->obtenerBienTipoHijosXBienTipoPadreId($bienTipo);
        }

        if (!ObjectUtil::isEmpty($dataBienTipoHijos)) {
            foreach ($dataBienTipoHijos as $item) {
                array_push($bienTipo, $item['id']);
            }
        }
        $bienTipoIdFormateado = Util::convertirArrayXCadena($bienTipo);
        // fin         

        $tipoDocumentoIdFormateado = Util::convertirArrayXCadena($tipoDocumentoId);

        $empresaId = $criterios[0]['empresa'];
        $empresaIdFormateado = Util::convertirArrayXCadena($empresaId);

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];

        $data = Reporte::create()->obtenerReporteVentasPorClienteXCriterios($empresaIdFormateado, $tipoDocumentoIdFormateado, $personaId, $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start, $bienTipoIdFormateado);

        $tamanio = count($data);

        return $data;
    }

    public function visualizarDocumento($documentoId, $movimientoId) {
        return MovimientoNegocio::create()->visualizarDocumento($documentoId, $movimientoId);
    }

    public function obtenerCantidadReporteVentasPorClienteXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start) {
        $personaId = $criterios[0]['persona'];
        $tipoDocumentoId = $criterios[0]['documentoTipo'];
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmisionDesde']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmisionHasta']);

        //seleccion de bien tipo e hijos        
        $bienTipo = $criterios[0]['bienTipo'];

        if (ObjectUtil::isEmpty($bienTipo)) {
            $bienTipo = $criterios[0]['bienTipoPadre'];
        }

        if (!ObjectUtil::isEmpty($bienTipo)) {
            $dataBienTipoHijos = BienNegocio::create()->obtenerBienTipoHijosXBienTipoPadreId($bienTipo);
        }

        if (!ObjectUtil::isEmpty($dataBienTipoHijos)) {
            foreach ($dataBienTipoHijos as $item) {
                array_push($bienTipo, $item['id']);
            }
        }
        $bienTipoIdFormateado = Util::convertirArrayXCadena($bienTipo);
        // fin        

        $tipoDocumentoIdFormateado = Util::convertirArrayXCadena($tipoDocumentoId);

        $empresaId = $criterios[0]['empresa'];
        $empresaIdFormateado = Util::convertirArrayXCadena($empresaId);

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];
        return Reporte::create()->obtenerCantidadReporteVentasPorClienteXCriterios($empresaIdFormateado, $tipoDocumentoIdFormateado, $personaId, $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar, $bienTipoIdFormateado);
    }

    public function obtenerReporteVentasPorClienteExcel($data) {
        if (ObjectUtil::isEmpty($data)) {
            throw new WarningException("No existe datos para exportar");
        } else {
            $this->crearReporteVentasPorClienteExcel($data, "REPORTE DE VENTAS POR CLIENTE");
        }
    }

    private function crearReporteVentasPorClienteExcel($reportes, $titulo) {

        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':H' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $titulo);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':H' . $i)->applyFromArray($this->estiloTituloReporte);

        $i += 2;

        //        Cliente G.P. Principal G.P. Secundario F. Emisión	Tipo documento	S|N	Total S/.	Total $
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Cliente');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'G.P. Principal');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'G.P. Secundario');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'F. Emisión');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'Tipo documento');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, 'S|N');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, 'Total S/.');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, 'Total $');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':H' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;

        foreach ($reportes as $reporte) {

            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':H' . $i)->applyFromArray($this->estiloInformacion);

            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $reporte['persona_nombre_completo']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $reporte['bien_tipo_padre_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $reporte['bien_tipo_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, $reporte['fecha_emision']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, $reporte['documento_tipo_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $reporte['serie_numero']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, $reporte['total_soles']);
            $objPHPExcel->getActiveSheet()->getStyle('G' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, $reporte['total_dolares']);
            $objPHPExcel->getActiveSheet()->getStyle('H' . $i)->getNumberFormat()->setFormatCode('#,##0.00');

            $i += 1;
        }

        for ($i = 'A'; $i <= 'H'; $i++) {

            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle('reporte');

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/reporte.xlsx');

        return 1;
    }

    public function verDetallePorCliente($documentoId, $movimientoId) {
        return MovimientoNegocio::create()->visualizarDocumento($documentoId, $movimientoId);
    }

    //reporte ventas reporte de utilidades
    public function obtenerConfiguracionesInicialesReporteUtilidades() {
        $respuesta = new ObjectUtil();
        $respuesta->empresa = EmpresaNegocio::create()->getEmpresaActivas();
        $respuesta->fecha_primer_documento = DocumentoNegocio::create()->obtenerFechaPrimerDocumento();

        return $respuesta;
    }

    public function reporteReporteUtilidades($criterios) {

        $tienda = $criterios[0]['tienda'];
        $tiempo = $criterios[0]['tiempo'];

        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmision']['inicio']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmision']['fin']);

        $tiendaIdFormateado = Util::convertirArrayXCadena($tienda);

        return Reporte::create()->reporteVentasReporteUtilidades($tiempo, $tiendaIdFormateado, $emisionInicio, $emisionFin);
    }

    public function obtenerReporteReporteUtilidadesExcel($criterios) {

        $respuesta = $this->reporteReporteUtilidades($criterios);

        if (ObjectUtil::isEmpty($respuesta)) {
            throw new WarningException("No existe datos para exportar");
        } else {
            $this->crearReporteReporteUtilidadesExcel($respuesta, "Reporte de Utilidades");
        }
    }

    private function crearReporteReporteUtilidadesExcel($reportes, $titulo) {

        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':D' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $titulo);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':D' . $i)->applyFromArray($this->estiloTituloReporte);

        $i += 2;

        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Tiempo');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'Utilidad (%)');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'Utilidad soles');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'Utilidad dólares');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':D' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;

        foreach ($reportes as $reporte) {

            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $reporte['fecha_tiempo']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, round($reporte['utilidad_porcentaje_total'], 2));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, round($reporte['utilidad_total_soles'], 2));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, round($reporte['utilidad_dolares_soles'], 2));

            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':D' . $i)->applyFromArray($this->estiloInformacion);

            $i += 1;
        }

        for ($i = 'A'; $i <= 'D'; $i++) {

            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle("reporte");

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/reporte.xlsx');

        return 1;
    }

    //reporte tributario
    public function obtenerConfiguracionesInicialesReporteTributario() {
        $respuesta = new ObjectUtil();
        $respuesta->fecha_primer_documento = DocumentoNegocio::create()->obtenerFechaPrimerDocumento();
        $respuesta->empresa = EmpresaNegocio::create()->getEmpresaActivas();

        return $respuesta;
    }

    public function reporteTributario($criterios) {

        $tipoTributo = $criterios[0]['tipoTributo'];
        $empresaId = $criterios[0]['empresaId'];

        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmision']['inicio']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmision']['fin']);

        return Reporte::create()->reporteTributario($tipoTributo, $emisionInicio, $emisionFin, $empresaId);
    }

    public function obtenerCantidadesTotalesReporteTributario($criterios) {

        $tipoTributo = $criterios[0]['tipoTributo'];
        $empresaId = $criterios[0]['empresaId'];

        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmision']['inicio']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmision']['fin']);

        $importeTotal = Reporte::create()->obtenerCantidadesTotalesReporteTributario($tipoTributo, $emisionInicio, $emisionFin, $empresaId);

        return $importeTotal[0]['total'];
    }

    public function obtenerReporteTributarioExcel($criterios) {

        $respuesta = $this->reporteTributario($criterios);

        if (ObjectUtil::isEmpty($respuesta)) {
            throw new WarningException("No existe datos para exportar");
        } else {
            $this->crearReporteReporteTributarioExcel($respuesta, "Reporte Tributario");
        }
    }

    private function crearReporteReporteTributarioExcel($reportes, $titulo) {

        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':F' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $titulo);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':F' . $i)->applyFromArray($this->estiloTituloReporte);

        $i += 2;
        //Fecha	Tipo	S|N Tipo	Documento	S|N Documento	Importe
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Fecha');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'Tipo');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'S|N Tipo');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'Documento');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'S|N Documento');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, 'Importe');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':F' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;

        foreach ($reportes as $reporte) {
            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $reporte['fecha_emision']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $reporte['tipo_comprobante_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $reporte['serie_num_comprobante']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, $reporte['tipo_documento_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, $reporte['serie_num_documento']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, round($reporte['importe'], 2));

            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':F' . $i)->applyFromArray($this->estiloInformacion);

            $i += 1;
        }

        for ($i = 'A'; $i <= 'F'; $i++) {

            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle("reporte");

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/reporte.xlsx');

        return 1;
    }

    //reporte notas credito y debito
    public function obtenerConfiguracionesInicialesNotasCreditoDebito() {
        $respuesta = new ObjectUtil();
        $respuesta->documento_tipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoNotasCreditoDebito();
        $respuesta->empresa = EmpresaNegocio::create()->getEmpresaActivas();
        $respuesta->bien = BienNegocio::create()->obtenerBienMovimientoEmpresa($idEmpresa);
        $respuesta->fecha_primer_documento = DocumentoNegocio::create()->obtenerFechaPrimerDocumento();

        return $respuesta;
    }

    public function obtenerCantidadesTotalesNotasCreditoDebito($criterios) {
        $empresaId = $criterios[0]['empresa'];
        $tipoDocumentoId = $criterios[0]['documentoTipo'];
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmisionDesde']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmisionHasta']);
        $tipoDocumentoIdFormateado = Util::convertirArrayXCadena($tipoDocumentoId);
        $empresaIdFormateado = Util::convertirArrayXCadena($empresaId);

        $respuesta = new ObjectUtil();

        $importeTotal = Reporte::create()->obtenerCantidadesTotalesNotasCreditoDebito($tipoDocumentoIdFormateado, $empresaIdFormateado, $emisionInicio, $emisionFin);

        $respuesta->pagado_soles_reporte = $importeTotal[0]['pagado_soles_reporte'];
        $respuesta->total_soles_reporte = $importeTotal[0]['total_soles_reporte'];
        $respuesta->pagado_dolares_reporte = $importeTotal[0]['pagado_dolares_reporte'];
        $respuesta->total_dolares_reporte = $importeTotal[0]['total_dolares_reporte'];

        return $respuesta;
    }

    public function obtenerReporteNotasCreditoDebitoXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start) {
        $empresaId = $criterios[0]['empresa'];
        $tipoDocumentoId = $criterios[0]['documentoTipo'];
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmisionDesde']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmisionHasta']);
        $tipoDocumentoIdFormateado = Util::convertirArrayXCadena($tipoDocumentoId);
        $empresaIdFormateado = Util::convertirArrayXCadena($empresaId);

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];

        $data = Reporte::create()->obtenerReporteNotasCreditoDebitoXCriterios($tipoDocumentoIdFormateado, $empresaIdFormateado, $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start);

        $tamanio = count($data);
        for ($i = 0; $i < $tamanio; $i++) {
            $estadoNotas = "";
            if ($data[$i]['total'] == $data[$i]['importe_utilizado'])
                $estadoNotas = 'Uso total';

            if ($data[$i]['total'] > $data[$i]['importe_utilizado'] && $data[$i]['importe_utilizado'] != 0)
                $estadoNotas = 'Uso parcial';

            if ($data[$i]['importe_utilizado'] == 0)
                $estadoNotas = 'Pendiente de uso';

            $data[$i]['estado_nota'] = $estadoNotas;
        }
        return $data;
    }

    public function obtenerCantidadReporteNotasCreditoDebitoXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start) {
        $empresaId = $criterios[0]['empresa'];
        $tipoDocumentoId = $criterios[0]['documentoTipo'];
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmisionDesde']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmisionHasta']);
        $tipoDocumentoIdFormateado = Util::convertirArrayXCadena($tipoDocumentoId);
        $empresaIdFormateado = Util::convertirArrayXCadena($empresaId);

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];
        return Reporte::create()->obtenerCantidadReporteNotasCreditoDebitoXCriterios($tipoDocumentoIdFormateado, $empresaIdFormateado, $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start);
    }

    public function obtenerReporteNotasCreditoDebitoExcel($data) {
        if (ObjectUtil::isEmpty($data)) {
            throw new WarningException("No existe datos para exportar");
        } else {
            $this->crearReporteNotasCreditoDebitoExcel($data, "Reporte de Notas de Crédito y Débito");
        }
    }

    private function crearReporteNotasCreditoDebitoExcel($reportes, $titulo) {

        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':H' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $titulo);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':H' . $i)->applyFromArray($this->estiloTituloReporte);

        $i += 2;
        /* F. Creación	F. Emisión	Vendedor	Tipo documento	Cliente	Serie	Número	Total
         */
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'F. Creación');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'F. Emisión');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'Tienda');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'Tipo documento');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'Cliente');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, 'Serie');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, 'Número');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, 'Total');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':H' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;

        foreach ($reportes as $reporte) {

            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':H' . $i)->applyFromArray($this->estiloInformacion);

            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $reporte['fecha_creacion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $reporte['fecha_emision']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $reporte['razon_social']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, $reporte['documento_tipo_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, $reporte['persona_nombre_completo']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $reporte['serie']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, $reporte['numero']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, $reporte['total']);

            $i += 1;
        }

        for ($i = 'A'; $i <= 'H'; $i++) {

            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle("reporte_notas");

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/reporte.xlsx');

        return 1;
    }

    public function obtenerCantidadesTotalesCuentasPorCobrar($tipo1, $tipo2, $criterios) {
        $personaId = $criterios[0]['persona'];
        $mostrarPagados = $criterios[0]['mostrar'];
        $mostrarLib = $criterios[0]['mostrarLib'];
        if ($criterios[0]['fechaVencimientoDesde'] != '') {
            $fechaVencimientoDesde = DateUtil::formatearCadenaACadenaBD($criterios[0]['fechaVencimientoDesde']);
        }

        if ($criterios[0]['fechaVencimientoHasta'] != '') {
            $fechaVencimientoHasta = DateUtil::formatearCadenaACadenaBD($criterios[0]['fechaVencimientoHasta']);
        }
        $empresa = $criterios[0]['empresa'];

        $importeTotal = Reporte::create()->obtenerCantidadesTotalesCuentasPorCobrar($mostrarPagados, $mostrarLib, $tipo1, $tipo2, $empresa, $personaId, $fechaVencimientoDesde, $fechaVencimientoHasta);

        return $importeTotal;
    }

    public function obtenerCantidadesTotalesCuentasPorCobrarGeneral($tipo1, $tipo2, $criterios) {
        $personaId = $criterios[0]['persona'];
        $mostrar = $criterios[0]['mostrar'];
        $fecha = '';
        $empresa = Util::convertirArrayXCadena($criterios[0]['empresa']);

        if ($criterios[0]['fecha'] != '') {
            $fecha = DateUtil::formatearCadenaACadenaBD($criterios[0]['fecha']);
        }

        $importeTotal = Reporte::create()->obtenerCantidadesTotalesCuentasPorCobrarGeneral($mostrar, $tipo1, $tipo2, $empresa, $personaId, $fecha, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start);

        return $importeTotal;
    }

    //reporte ventas IGV VENTAS
    public function obtenerConfiguracionesInicialesVentasIgvVentas() {
        $respuesta = new ObjectUtil();
        $tipo = '(1)';
        $descripcion = 'Factura';
        $respuesta->documento_tipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXTiposxDescripcion($tipo, $descripcion);
        $respuesta->empresa = EmpresaNegocio::create()->getEmpresaActivas();
        $respuesta->bien = BienNegocio::create()->obtenerBienMovimientoEmpresa($idEmpresa);
        $respuesta->fecha_primer_documento = DocumentoNegocio::create()->obtenerFechaPrimerDocumento();

        return $respuesta;
    }

    public function obtenerCantidadesTotalesVentasIgvVentas($criterios) {
        $empresaId = $criterios[0]['empresa'];
        $tipoDocumentoId = $criterios[0]['documentoTipo'];
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmisionDesde']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmisionHasta']);
        $tipoDocumentoIdFormateado = Util::convertirArrayXCadena($tipoDocumentoId);
        $empresaIdFormateado = Util::convertirArrayXCadena($empresaId);

        $respuesta = new ObjectUtil();

        $importeTotal = Reporte::create()->obtenerCantidadesTotalesVentasIgvVentas($tipoDocumentoIdFormateado, $empresaIdFormateado, $emisionInicio, $emisionFin);

        return $importeTotal;
    }

    public function obtenerReporteVentasIgvVentasXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start) {
        $empresaId = $criterios[0]['empresa'];
        $tipoDocumentoId = $criterios[0]['documentoTipo'];
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmisionDesde']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmisionHasta']);
        $tipoDocumentoIdFormateado = Util::convertirArrayXCadena($tipoDocumentoId);
        $empresaIdFormateado = Util::convertirArrayXCadena($empresaId);

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];

        return Reporte::create()->obtenerReporteVentasIgvVentasXCriterios($tipoDocumentoIdFormateado, $empresaIdFormateado, $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start);
    }

    public function obtenerCantidadReporteVentasIgvVentasXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start) {
        $empresaId = $criterios[0]['empresa'];
        $tipoDocumentoId = $criterios[0]['documentoTipo'];
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmisionDesde']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmisionHasta']);
        $tipoDocumentoIdFormateado = Util::convertirArrayXCadena($tipoDocumentoId);
        $empresaIdFormateado = Util::convertirArrayXCadena($empresaId);

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];
        return Reporte::create()->obtenerCantidadReporteVentasIgvVentasXCriterios($tipoDocumentoIdFormateado, $empresaIdFormateado, $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start);
    }

    public function obtenerReporteVentasIgvVentasExcel($data) {
        if (ObjectUtil::isEmpty($data)) {
            throw new WarningException("No existe datos para exportar");
        } else {
            $this->crearReporteVentasIgvVentasExcel($data, "Reporte de Ventas IGV");
        }
    }

    private function crearReporteVentasIgvVentasExcel($reportes, $titulo) {

        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':I' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $titulo);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':I' . $i)->applyFromArray($this->estiloTituloReporte);

        $i += 2;
        /* F. Creación	F. Emisión	Vendedor	Tipo documento	Cliente	Serie	Número	Total
         */
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'F. Creación');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'F. Emisión');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'Tienda');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'Tipo documento');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'Cliente');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, 'Serie');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, 'Número');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, 'IGV S/.');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, 'IGV $');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':I' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;

        foreach ($reportes as $reporte) {

            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':I' . $i)->applyFromArray($this->estiloInformacion);

            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $reporte['fecha_creacion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $reporte['fecha_emision']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $reporte['razon_social']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, $reporte['documento_tipo_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, $reporte['persona_nombre_completo']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $reporte['serie']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, $reporte['numero']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, $reporte['igv_soles']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, $reporte['igv_dolares']);

            $i += 1;
        }

        for ($i = 'A'; $i <= 'I'; $i++) {

            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle($titulo);

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/reporte.xlsx');

        return 1;
    }

    //Reporte Caja Bancos por actividad
    public function obtenerConfiguracionesInicialesPorActividad($usuarioId, $empresaId, $scriptPersona) {
        $respuesta = new ObjectUtil();
        $respuesta->empresa = EmpresaNegocio::create()->getEmpresaActivas();
        $respuesta->agencia = Agencia::create()->getDataAgencia();
        $respuesta->zona = Agencia::create()->getDataZona();
//        $respuesta->persona = Persona::create()->obtenerPersonaActivoXStringBusqueda($scriptPersona);
        $respuesta->moneda = MonedaNegocio::create()->obtenerComboMoneda();
        $respuesta->bien = BienNegocio::create()->getDataBien($usuarioId, $empresaId);
        return $respuesta;
    }

    public function obtenerConfiguracionesInicialesTarifarioZona($usuarioId, $empresaId) {
        $respuesta = new ObjectUtil();
        // $respuesta->empresa = EmpresaNegocio::create()->getEmpresaActivas();
        $respuesta->agencia = Agencia::create()->getDataAgencia();
        $respuesta->zona = Agencia::create()->getDataZona();
        // $respuesta->persona = Persona::create()->getAllPersonaTarifario();
        $respuesta->moneda = MonedaNegocio::create()->obtenerComboMoneda();
        // $respuesta->bien = BienNegocio::create()->getDataBien($usuarioId, $empresaId);
        return $respuesta;
    }

    public function reportePorActividad($criterios) {

        $tienda = $criterios[0]['tienda'];
        $actividad = $criterios[0]['actividad'];
        $actividadTipo = $criterios[0]['actividadTipo'];
        $mes = $criterios[0]['mes'];
        $anio = $criterios[0]['anio'];

        //        $tiendaIdFormateado = Util::convertirArrayXCadena($tienda);
        $actividadIdFormateado = Util::convertirArrayXCadena($actividad);
        $actividadTipoIdFormateado = Util::convertirArrayXCadena($actividadTipo);

        return Reporte::create()->reporteCajaBancosPorActividad($actividadIdFormateado, $actividadTipoIdFormateado, $tienda, $mes, $anio);
    }

    public function obtenerReportePorActividadExcel($criterios) {

        $respuesta = $this->reportePorActividad($criterios);

        if (ObjectUtil::isEmpty($respuesta)) {
            throw new WarningException("No existe datos para exportar");
        } else {
            $this->crearReportePorActividadExcel($respuesta, "Reporte de Actividades");
        }
    }

    private function crearReportePorActividadExcel($reportes, $titulo) {

        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':D' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $titulo);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':D' . $i)->applyFromArray($this->estiloTituloReporte);

        $i += 2;
        //Cód.	Tipo actividad	Actividad	Total
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Código');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'Tipo actividad');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'Actividad');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'Total');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':D' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;

        foreach ($reportes as $reporte) {

            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $reporte['codigo_actividad']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $reporte['actividad_tipo_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $reporte['actividad_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, round($reporte['total'], 2));

            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':D' . $i)->applyFromArray($this->estiloInformacion);

            $i += 1;
        }

        for ($i = 'A'; $i <= 'D'; $i++) {

            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle("reporte");

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/reporte.xlsx');

        return 1;
    }

    //Reporte Caja Bancos por cuenta
    public function obtenerConfiguracionesInicialesPorCuenta() {
        $respuesta = new ObjectUtil();
        //Ingreso: 7,2,3 -- Salida: 8,5,6

        $tipoDato = 20; // tipo cuenta en documento_tipo_dato
        $respuesta->documento_tipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXDocumentoTipoDatoXTipo($tipoDato);
        $respuesta->fecha_primer_documento = DocumentoNegocio::create()->obtenerFechaPrimerDocumento();
        $respuesta->cuenta = CuentaNegocio::create()->obtenerCuentasActivas();
        $respuesta->empresa = EmpresaNegocio::create()->getEmpresaActivas();

        return $respuesta;
    }

    public function reportePorCuenta($criterios) {

        $documentoTipo = $criterios[0]['documentoTipo'];
        $cuenta = $criterios[0]['cuenta'];

        $mes = $criterios[0]['mes'];
        $anio = $criterios[0]['anio'];

        $documentoTipoIdFormateado = Util::convertirArrayXCadena($documentoTipo);
        $cuentaIdFormateado = Util::convertirArrayXCadena($cuenta);

        $empresaId = $criterios[0]['empresaId'];
        return Reporte::create()->reporteVentasPorCuenta($documentoTipoIdFormateado, $mes, $anio, $cuentaIdFormateado, $empresaId);
    }

    public function reportePorCuentaTotales($criterios) {

        $documentoTipo = $criterios[0]['documentoTipo'];
        $cuenta = $criterios[0]['cuenta'];

        $mes = $criterios[0]['mes'];
        $anio = $criterios[0]['anio'];

        $documentoTipoIdFormateado = Util::convertirArrayXCadena($documentoTipo);
        $cuentaIdFormateado = Util::convertirArrayXCadena($cuenta);

        $empresaId = $criterios[0]['empresaId'];
        return Reporte::create()->reportePorCuentaTotales($documentoTipoIdFormateado, $mes, $anio, $cuentaIdFormateado, $empresaId);
    }

    public function obtenerReportePorCuentaExcel($criterios) {

        $respuesta = $this->reportePorCuenta($criterios);

        if (ObjectUtil::isEmpty($respuesta)) {
            throw new WarningException("No existe datos para exportar");
        } else {
            $this->crearReportePorCuentaExcel($respuesta, "Reporte de Cuentas");
        }
    }

    private function crearReportePorCuentaExcel($reportes, $titulo) {

        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':T' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $titulo);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':T' . $i)->applyFromArray($this->estiloTituloReporte);

        $i += 2;
        /* Fecha emisión	Tipo documento	S | N	COD	Encargado	Tercero	Detalle	Cuenta	Caja chica	BCP (5701728785048)	Banco de la nación
          Ing.	Sal.	SALDO	Ing.	Sal.	SALDO	Ing.	Sal.	SALDO */
        $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':H' . $i);
        $objPHPExcel->getActiveSheet()->mergeCells('I' . $i . ':K' . $i);
        $objPHPExcel->getActiveSheet()->mergeCells('L' . $i . ':N' . $i);
        $objPHPExcel->getActiveSheet()->mergeCells('O' . $i . ':Q' . $i);
        $objPHPExcel->getActiveSheet()->mergeCells('R' . $i . ':T' . $i);

        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, ' ');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, 'Caja chica');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('L' . $i, 'BCP');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('O' . $i, 'BBVA');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('R' . $i, 'ScotiaBank');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':T' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Fecha emisión');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'Tipo documento');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'S | N');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'COD');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'Encargado');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, 'Tercero');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, 'Detalle');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, 'Cuenta');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, 'Ing.');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('J' . $i, 'Sal.');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('K' . $i, 'SALDO');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('L' . $i, 'Ing.');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('M' . $i, 'Sal.');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('N' . $i, 'SALDO');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('O' . $i, 'Ing.');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('P' . $i, 'Sal.');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('Q' . $i, 'SALDO');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('R' . $i, 'Ing.');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('S' . $i, 'Sal.');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('T' . $i, 'SALDO');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':T' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;

        foreach ($reportes as $reporte) {
            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $reporte['fecha_emision']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $reporte['documento_tipo_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, ' ' . $reporte['serie_numero']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, $reporte['actividad_codigo']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, $reporte['usuario_nombre']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $reporte['persona_nombre']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, $reporte['descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, $reporte['cuenta_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, round($reporte['total_caja_ingreso'], 2));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('J' . $i, round($reporte['total_caja_salida'], 2));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('K' . $i, round($reporte['total_caja_saldo'], 2));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('L' . $i, round($reporte['total_bcp_ingreso'], 2));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('M' . $i, round($reporte['total_bcp_salida'], 2));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('N' . $i, round($reporte['total_bcp_saldo'], 2));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('O' . $i, round($reporte['total_bn_ingreso'], 2));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('P' . $i, round($reporte['total_bn_salida'], 2));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('Q' . $i, round($reporte['total_bn_saldo'], 2));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('R' . $i, round($reporte['total_ret_ingreso'], 2));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('S' . $i, round($reporte['total_ret_salida'], 2));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('T' . $i, round($reporte['total_ret_saldo'], 2));

            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':T' . $i)->applyFromArray($this->estiloInformacion);

            $objPHPExcel->getActiveSheet()->getStyle('I' . $i . ':T' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
            $i += 1;
        }

        for ($i = 'A'; $i <= 'T'; $i++) {

            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle("reporte");

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/reporte.xlsx');

        return 1;
    }

    //Reporte Caja Bancos cierre caja
    public function obtenerConfiguracionesInicialesCierreCaja() {
        $respuesta = new ObjectUtil();
        //Ingreso: 7,2,3 -- Salida: 8,5,6

        $tipoDato = 20; // tipo cuenta en documento_tipo_dato
        //        $respuesta->documento_tipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXDocumentoTipoDatoXTipo($tipoDato);
        $respuesta->actividad_tipo = ActividadNegocio::create()->obtenerActividadTipoActivas();
        $respuesta->actividad = ActividadNegocio::create()->obtenerActividadesActivasTodo();
        $respuesta->fecha_primer_documento = DocumentoNegocio::create()->obtenerFechaPrimerDocumento();
        $respuesta->cuenta = CuentaNegocio::create()->obtenerCuentasActivas();

        return $respuesta;
    }

    public function reporteCierreCaja($criterios) {

        $actividad = $criterios[0]['actividad'];
        $actividadTipo = $criterios[0]['actividadTipo'];
        $cuenta = $criterios[0]['cuenta'];

        $fechaEmision = $this->formatearFechaBD($criterios[0]['fechaEmision']);

        $actividadIdFormateado = Util::convertirArrayXCadena($actividad);
        $actividadTipoIdFormateado = Util::convertirArrayXCadena($actividadTipo);
        $cuentaIdFormateado = Util::convertirArrayXCadena($cuenta);

        $empresaId = $criterios[0]['empresaId'];
        return Reporte::create()->reporteCierreCaja($actividadIdFormateado, $actividadTipoIdFormateado, $fechaEmision, $cuentaIdFormateado, $empresaId);
    }

    public function reporteCierreCajaTotales($criterios) {

        $actividad = $criterios[0]['actividad'];
        $actividadTipo = $criterios[0]['actividadTipo'];
        $cuenta = $criterios[0]['cuenta'];

        $fechaEmision = $this->formatearFechaBD($criterios[0]['fechaEmision']);

        $actividadIdFormateado = Util::convertirArrayXCadena($actividad);
        $actividadTipoIdFormateado = Util::convertirArrayXCadena($actividadTipo);
        $cuentaIdFormateado = Util::convertirArrayXCadena($cuenta);

        $empresaId = $criterios[0]['empresaId'];
        return Reporte::create()->reporteCierreCajaTotales($actividadIdFormateado, $actividadTipoIdFormateado, $fechaEmision, $cuentaIdFormateado, $empresaId);
    }

    public function obtenerReporteCierreCajaExcel($criterios) {

        $respuesta = $this->reporteCierreCaja($criterios);

        if (ObjectUtil::isEmpty($respuesta)) {
            throw new WarningException("No existe datos para exportar");
        } else {
            $this->crearReporteCierreCajaExcel($respuesta, "Reporte de Cuentas");
        }
    }

    private function crearReporteCierreCajaExcel($reportes, $titulo) {

        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':J' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $titulo);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':J' . $i)->applyFromArray($this->estiloTituloReporte);

        $i += 2;
        /* Fecha creación	Tipo documento	S|N	Tipo doc. pago	S|N pago	Cliente/Proveedor	COD	Actividad	Cuenta	Total
         */

        $i += 1;
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Fecha creación');
        //        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'Fecha emisión');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'Tipo documento');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'S | N');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'Tipo doc. pago');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'S|N pago');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, 'Cliente/Proveedor');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, 'COD');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, 'Actividad');
        //        $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, 'Usuario');
        //        $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, 'Detalle');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, 'Cuenta');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('J' . $i, 'Total');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':J' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;

        foreach ($reportes as $reporte) {
            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $reporte['fecha_creacion']);
            //            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $reporte['fecha_emision']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $reporte['documento_tipo_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, ' ' . $reporte['serie_numero']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, $reporte['documento_tipo_desc_pago']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, ' ' . $reporte['serie_numero_pago']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $reporte['persona_nombre']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, $reporte['actividad_codigo']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, $reporte['actividad_descripcion']);
            //            $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, $reporte['usuario_nombre']);
            //            $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, $reporte['descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, $reporte['cuenta_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('J' . $i, round($reporte['total_conversion'], 2));

            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':J' . $i)->applyFromArray($this->estiloInformacion);

            $objPHPExcel->getActiveSheet()->getStyle('J' . $i)->getNumberFormat()->setFormatCode('#,##0.00');

            $i += 1;
        }

        for ($i = 'A'; $i <= 'J'; $i++) {

            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle("reporte");

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/reporte.xlsx');

        return 1;
    }

    //reporte orden compra
    public function obtenerConfiguracionesInicialesReporteOrdenCompra() {
        $respuesta = new ObjectUtil();
        $respuesta->persona = PersonaNegocio::create()->obtenerComboPersonaXPersonaClaseId(-1);
        $respuesta->fecha_primer_documento = DocumentoNegocio::create()->obtenerFechaPrimerDocumento();

        return $respuesta;
    }

    public function obtenerCantidadesTotalesReporteOrdenCompra($criterios) {
        $personaId = $criterios[0]['persona'];
        $empresaId = $criterios[0]['empresa'];
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmisionDesde']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmisionHasta']);

        $respuesta = new ObjectUtil();

        $importeTotal = Reporte::create()->obtenerCantidadesTotalesReporteOrdenCompra($empresaId, $personaId, $emisionInicio, $emisionFin);

        $respuesta->total = $importeTotal[0]['total'];

        return $respuesta;
    }

    public function obtenerReporteReporteOrdenCompraXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start) {
        $personaId = $criterios[0]['persona'];
        $empresaId = $criterios[0]['empresa'];
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmisionDesde']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmisionHasta']);

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];

        return Reporte::create()->obtenerReporteReporteOrdenCompraXCriterios($empresaId, $personaId, $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start);
    }

    public function obtenerCantidadReporteReporteOrdenCompraXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start) {
        $personaId = $criterios[0]['persona'];
        $empresaId = $criterios[0]['empresa'];
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmisionDesde']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmisionHasta']);

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];
        return Reporte::create()->obtenerCantidadReporteReporteOrdenCompraXCriterios($empresaId, $personaId, $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start);
    }

    public function obtenerReporteReporteOrdenCompraExcel($data) {
        if (ObjectUtil::isEmpty($data)) {
            throw new WarningException("No existe datos para exportar");
        } else {
            $this->crearReporteReporteOrdenCompraExcel($data, "Reporte de Productos por llegar");
        }
    }

    private function crearReporteReporteOrdenCompraExcel($reportes, $titulo) {

        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':I' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $titulo);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':I' . $i)->applyFromArray($this->estiloTituloReporte);

        $i += 2;
        /* F. Tentativa	F. Emisión	Tipo documento	Proveedor	Serie	Número	Producto	Cantidad	Unidad medida	Total
         */
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'F. Tentativa');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'F. Emisión');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'Tipo documento');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'Proveedor');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'Serie');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, 'Número');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, 'Producto');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, 'Cantidad');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, 'Unidad medida');
        //        $objPHPExcel->setActiveSheetIndex()->setCellValue('J' . $i, 'Total');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':I' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;

        foreach ($reportes as $reporte) {

            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':I' . $i)->applyFromArray($this->estiloInformacion);

            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $reporte['fecha_tentativa']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $reporte['fecha_emision']);
            //            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $reporte['usuario_nombre']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $reporte['documento_tipo_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, $reporte['persona_nombre_completo']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, $reporte['serie']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $reporte['numero']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, $reporte['bien_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, $reporte['cantidad']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, $reporte['unidad_medida_descripcion']);
            //            $objPHPExcel->setActiveSheetIndex()->setCellValue('J' . $i, $reporte['total']);


            $i += 1;
        }

        for ($i = 'A'; $i <= 'I'; $i++) {

            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle($titulo);

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/reporte.xlsx');

        return 1;
    }

    //Reporte retencion detraccion
    public function obtenerConfiguracionesInicialesRetencionDetraccion() {
        $respuesta = new ObjectUtil();
        $respuesta->persona = PersonaNegocio::create()->obtenerComboPersonaXPersonaClaseId(16); // 16: cliente
        $respuesta->fecha_primer_documento = DocumentoNegocio::create()->obtenerFechaPrimerDocumento();

        return $respuesta;
    }

    public function reporteRetencionDetraccion($criterios) {

        $cliente = $criterios[0]['cliente'];
        $tipoRD = $criterios[0]['tipoRD'];
        $empresaId = $criterios[0]['empresa'];

        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmision']['inicio']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmision']['fin']);

        $clienteIdFormateado = Util::convertirArrayXCadena($cliente);

        return Reporte::create()->reporteVentasRetencionDetraccion($empresaId, $tipoRD, $clienteIdFormateado, $emisionInicio, $emisionFin);
    }

    public function obtenerReporteRetencionDetraccionExcel($criterios) {

        $respuesta = $this->reporteRetencionDetraccion($criterios);

        if (ObjectUtil::isEmpty($respuesta)) {
            throw new WarningException("No existe datos para exportar");
        } else {
            $this->crearReporteRetencionDetraccionExcel($respuesta, "Reporte de Retención/Detracción");
        }
    }

    private function crearReporteRetencionDetraccionExcel($reportes, $titulo) {

        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':D' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $titulo);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':D' . $i)->applyFromArray($this->estiloTituloReporte);

        $i += 2;

        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Producto');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'Número de ventas');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':B' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;

        foreach ($reportes as $reporte) {

            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $reporte['bien_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $reporte['productos_vendidos']);

            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':B' . $i)->applyFromArray($this->estiloInformacion);

            $i += 1;
        }

        for ($i = 'A'; $i <= 'B'; $i++) {

            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle("reporte");

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/reporte.xlsx');

        return 1;
    }

    //Reporte Caja Bancos por actividad por fecha
    public function obtenerConfiguracionesInicialesPorActividadPorFecha() {
        $respuesta = new ObjectUtil();
        $respuesta->empresa = EmpresaNegocio::create()->getEmpresaActivas();
        $respuesta->fecha_primer_documento = DocumentoNegocio::create()->obtenerFechaPrimerDocumento();
        $respuesta->actividad_tipo = ActividadNegocio::create()->obtenerActividadTipoActivas();
        $respuesta->actividad = ActividadNegocio::create()->obtenerActividadesActivasTodo();

        return $respuesta;
    }

    public function reportePorActividadPorFecha($criterios) {

        $tienda = $criterios[0]['tienda'];
        $actividad = $criterios[0]['actividad'];
        $actividadTipo = $criterios[0]['actividadTipo'];
        $fechaEmision = $this->formatearFechaBD($criterios[0]['fechaEmision']);

        //        $tiendaIdFormateado = Util::convertirArrayXCadena($tienda);
        $actividadIdFormateado = Util::convertirArrayXCadena($actividad);
        $actividadTipoIdFormateado = Util::convertirArrayXCadena($actividadTipo);

        return Reporte::create()->reporteCajaBancosPorActividadPorFecha($actividadIdFormateado, $actividadTipoIdFormateado, $tienda, $fechaEmision);
    }

    public function obtenerReportePorActividadPorFechaExcel($criterios) {

        $respuesta = $this->reportePorActividadPorFecha($criterios);

        if (ObjectUtil::isEmpty($respuesta)) {
            throw new WarningException("No existe datos para exportar");
        } else {
            $this->crearReportePorActividadPorFechaExcel($respuesta, "Reporte de Actividades");
        }
    }

    private function crearReportePorActividadPorFechaExcel($reportes, $titulo) {

        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':D' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $titulo);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':D' . $i)->applyFromArray($this->estiloTituloReporte);

        $i += 2;
        //Cód.	Tipo actividad	Actividad	Total
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Código');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'Tipo actividad');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'Actividad');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'Total');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':D' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;

        foreach ($reportes as $reporte) {

            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $reporte['codigo_actividad']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $reporte['actividad_tipo_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $reporte['actividad_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, round($reporte['total'], 2));

            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':D' . $i)->applyFromArray($this->estiloInformacion);

            $i += 1;
        }

        for ($i = 'A'; $i <= 'D'; $i++) {

            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle("reporte");

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/reporte.xlsx');

        return 1;
    }

    //Reporte Caja Bancos por cuenta fecha
    public function obtenerConfiguracionesInicialesPorCuentaFecha() {
        $respuesta = new ObjectUtil();
        //Ingreso: 7,2,3 -- Salida: 8,5,6
        //        $tipoDato = 20;// tipo cuenta en documento_tipo_dato
        //        $respuesta->documento_tipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoXDocumentoTipoDatoXTipo($tipoDato);
        $respuesta->fecha_primer_documento = DocumentoNegocio::create()->obtenerFechaPrimerDocumento();
        $respuesta->cuenta = CuentaNegocio::create()->obtenerCuentasActivas();
        $respuesta->empresa = EmpresaNegocio::create()->getEmpresaActivas();

        return $respuesta;
    }

    public function reportePorCuentaFecha($criterios) {

        $documentoTipo = $criterios[0]['documentoTipo'];
        $cuenta = $criterios[0]['cuenta'];

        $fechaEmision = $this->formatearFechaBD($criterios[0]['fechaEmision']);

        $documentoTipoIdFormateado = Util::convertirArrayXCadena($documentoTipo);
        $cuentaIdFormateado = Util::convertirArrayXCadena($cuenta);

        $empresaId = $criterios[0]['empresaId'];
        return Reporte::create()->reporteVentasPorCuentaFecha($documentoTipoIdFormateado, $fechaEmision, $cuentaIdFormateado, $empresaId);
    }

    public function reportePorCuentaFechaTotales($criterios) {

        $documentoTipo = $criterios[0]['documentoTipo'];
        $cuenta = $criterios[0]['cuenta'];

        $fechaEmision = $this->formatearFechaBD($criterios[0]['fechaEmision']);

        $documentoTipoIdFormateado = Util::convertirArrayXCadena($documentoTipo);
        $cuentaIdFormateado = Util::convertirArrayXCadena($cuenta);

        $empresaId = $criterios[0]['empresaId'];
        return Reporte::create()->reportePorCuentaFechaTotales($documentoTipoIdFormateado, $fechaEmision, $cuentaIdFormateado, $empresaId);
    }

    public function obtenerReportePorCuentaFechaExcel($criterios) {

        $respuesta = $this->reportePorCuentaFecha($criterios);

        if (ObjectUtil::isEmpty($respuesta)) {
            throw new WarningException("No existe datos para exportar");
        } else {
            $this->crearReportePorCuentaFechaExcel($respuesta, "Reporte de Cuentas");
        }
    }

    private function crearReportePorCuentaFechaExcel($reportes, $titulo) {

        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':T' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $titulo);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':T' . $i)->applyFromArray($this->estiloTituloReporte);

        $i += 2;
        /* Fecha emisión	Tipo documento	S | N	COD	Encargado	Tercero	Detalle	Cuenta	Caja chica	BCP (5701728785048)	Banco de la nación
          Ing.	Sal.	SALDO	Ing.	Sal.	SALDO	Ing.	Sal.	SALDO */
        $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':H' . $i);
        $objPHPExcel->getActiveSheet()->mergeCells('I' . $i . ':K' . $i);
        $objPHPExcel->getActiveSheet()->mergeCells('L' . $i . ':N' . $i);
        $objPHPExcel->getActiveSheet()->mergeCells('O' . $i . ':Q' . $i);
        $objPHPExcel->getActiveSheet()->mergeCells('R' . $i . ':T' . $i);

        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, ' ');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, 'Caja chica');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('L' . $i, 'BCP');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('O' . $i, 'BBVA');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('R' . $i, 'ScotiaBank');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':T' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Fecha emisión');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'Tipo documento');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'S | N');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'COD');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'Encargado');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, 'Tercero');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, 'Detalle');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, 'Cuenta');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, 'Ing.');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('J' . $i, 'Sal.');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('K' . $i, 'SALDO');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('L' . $i, 'Ing.');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('M' . $i, 'Sal.');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('N' . $i, 'SALDO');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('O' . $i, 'Ing.');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('P' . $i, 'Sal.');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('Q' . $i, 'SALDO');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('R' . $i, 'Ing.');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('S' . $i, 'Sal.');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('T' . $i, 'SALDO');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':T' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;

        foreach ($reportes as $reporte) {
            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $reporte['fecha_emision']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $reporte['documento_tipo_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, ' ' . $reporte['serie_numero']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, $reporte['actividad_codigo']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, $reporte['usuario_nombre']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $reporte['persona_nombre']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, $reporte['descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, $reporte['cuenta_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, round($reporte['total_caja_ingreso'], 2));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('J' . $i, round($reporte['total_caja_salida'], 2));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('K' . $i, round($reporte['total_caja_saldo'], 2));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('L' . $i, round($reporte['total_bcp_ingreso'], 2));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('M' . $i, round($reporte['total_bcp_salida'], 2));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('N' . $i, round($reporte['total_bcp_saldo'], 2));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('O' . $i, round($reporte['total_bn_ingreso'], 2));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('P' . $i, round($reporte['total_bn_salida'], 2));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('Q' . $i, round($reporte['total_bn_saldo'], 2));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('R' . $i, round($reporte['total_ret_ingreso'], 2));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('S' . $i, round($reporte['total_ret_salida'], 2));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('T' . $i, round($reporte['total_ret_saldo'], 2));

            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':T' . $i)->applyFromArray($this->estiloInformacion);

            $objPHPExcel->getActiveSheet()->getStyle('I' . $i . ':T' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
            $i += 1;
        }

        for ($i = 'A'; $i <= 'T'; $i++) {

            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle("reporte");

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/reporte.xlsx');

        return 1;
    }

    public function reporteKardexReporte($criterios) {
        $empresaId = $criterios[0]['empresaId'];
        $bienId = $criterios[0]['bien'];
        $bienTipoId = $criterios[0]['bienTipo'];

        //obtener bien tipo hijos
        if (!ObjectUtil::isEmpty($bienTipoId)) {
            $dataBienTipoHijos = BienNegocio::create()->obtenerBienTipoHijosXBienTipoPadreId($bienTipoId);
        }

        if (!ObjectUtil::isEmpty($dataBienTipoHijos)) {
            foreach ($dataBienTipoHijos as $item) {
                array_push($bienTipoId, $item['id']);
            }
        }
        //fin	

        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmision']['inicio']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmision']['fin']);

        $bienIdFormateado = Util::convertirArrayXCadena($bienId);
        $bienTipoIdFormateado = Util::convertirArrayXCadena($bienTipoId);

        return Reporte::create()->reporteKardexReporte($empresaId, $bienIdFormateado, $bienTipoIdFormateado, $emisionInicio, $emisionFin);
    }

    public function obtenerKardexReporteExcel($criterios, $tipo) {
        $respuestaReporteKardexExcel = $this->reporteKardexReporte($criterios);

        if (ObjectUtil::isEmpty($respuestaReporteKardexExcel)) {
            throw new WarningException("No existe datos para exportar");
        } else {
            $this->crearKardexReporteExcel($respuestaReporteKardexExcel, "REPORTE DE KARDEX");
        }
    }

    private function crearKardexReporteExcel($reportes, $titulo) {

        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':F' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $titulo);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':F' . $i)->applyFromArray($this->estiloTituloReporte);

        $i += 2;

        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Producto');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'Cód. Cont.');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'Tipo producto');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'Stock');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'Unidad de medida');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, 'Costo unit.');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':F' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;

        foreach ($reportes as $reporte) {

            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $reporte['bien_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $reporte['codigo_contable']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $reporte['bien_tipo_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, $reporte['unidad_medida_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, $reporte['stock']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $reporte['costo_inicial']);

            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':F' . $i)->applyFromArray($this->estiloInformacion);
            $objPHPExcel->getActiveSheet()->getStyle('E' . $i . ':F' . $i)->applyFromArray($this->estiloNumInformacion);

            $i += 1;
        }

        for ($i = 'A'; $i <= 'F'; $i++) {

            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle($titulo);

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/reporte.xlsx');

        return 1;
    }

    //kardex valorizado
    public function reporteKardexValorizado($criterios) {
        $empresaId = $criterios[0]['empresaId'];
        $bienId = $criterios[0]['bien'];
        $bienTipoId = $criterios[0]['bienTipo'];

        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmision']['inicio']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmision']['fin']);

        $bienIdFormateado = Util::convertirArrayXCadena($bienId);
        $bienTipoIdFormateado = Util::convertirArrayXCadena($bienTipoId);

        return Reporte::create()->reporteKardexValorizado($empresaId, $bienIdFormateado, $bienTipoIdFormateado, $emisionInicio, $emisionFin);
    }

    public function obtenerKardexValorizadoExcel($criterios, $tipo) {
        $respuestaReporteKardexExcel = $this->reporteKardexValorizado($criterios);

        if (ObjectUtil::isEmpty($respuestaReporteKardexExcel)) {
            throw new WarningException("No existe datos para exportar");
        } else {
            $this->crearKardexValorizadoExcel($respuestaReporteKardexExcel, "REPORTE DE KARDEX VALORIZADO");
        }
    }

    private function crearKardexValorizadoExcel($reportes, $titulo) {

        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':E' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $titulo);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':E' . $i)->applyFromArray($this->estiloTituloReporte);

        $i += 2;

        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Producto');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'Tipo producto');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'Unidad de medida');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'Stock');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'Stock valorizado');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':E' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;

        foreach ($reportes as $reporte) {

            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $reporte['bien_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $reporte['bien_tipo_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $reporte['unidad_medida_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, $reporte['stock']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, number_format($reporte['stock_valorizado'], 2, ".", ","));

            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':C' . $i)->applyFromArray($this->estiloInformacion);
            $objPHPExcel->getActiveSheet()->getStyle('D' . $i . ':E' . $i)->applyFromArray($this->estiloNumInformacion);

            $i += 1;
        }

        for ($i = 'A'; $i <= 'E'; $i++) {

            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle($titulo);

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/reporte.xlsx');

        return 1;
    }

    function obtenerConfiguracionesInicialesListadoAtencion($usuarioId, $empresaId) {
        $respuesta = new ObjectUtil();
        $respuesta->documento_tipo = DocumentoTipoNegocio::create()->obtenerTiposParaReporteAtenciones();
        $respuesta->cboProductoData = BienNegocio::create()->getDataBien($usuarioId, $empresaId);
        $respuesta->cboProductoTipoData = Bien::create()->getDataBienTipo();
        $respuesta->cboPersonaData = PersonaNegocio::create()->obtenerComboPersonaXPersonaClaseId(-1);
        return $respuesta;
    }

    //reporte OPERACIONES
    public function obtenerConfiguracionesInicialesReporteOperaciones() {
        $respuesta = new ObjectUtil();
        $operacionTipoIds = ''; //tipos: (1),(2) o '': para todos
        $respuesta->documento_tipo = DocumentoTipoNegocio::create()->obtenerDocumentoTipoReporteXOperacionTipos($operacionTipoIds);
        $respuesta->persona = PersonaNegocio::create()->obtenerActivas();
        $respuesta->fecha_primer_documento = DocumentoNegocio::create()->obtenerFechaPrimerDocumento();
        $respuesta->empresa = EmpresaNegocio::create()->getEmpresaActivas();

        return $respuesta;
    }

    public function obtenerCantidadesTotalesReporteOperaciones($criterios) {
        $personaId = $criterios[0]['persona'];
        $tipoDocumentoId = $criterios[0]['documentoTipo'];
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmisionDesde']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmisionHasta']);
        $tipoDocumentoIdFormateado = Util::convertirArrayXCadena($tipoDocumentoId);

        $empresaId = $criterios[0]['empresa'];
        $empresaIdFormateado = Util::convertirArrayXCadena($empresaId);

        $respuesta = new ObjectUtil();

        $importeTotal = Reporte::create()->obtenerCantidadesTotalesReporteOperaciones($empresaIdFormateado, $tipoDocumentoIdFormateado, $personaId, $emisionInicio, $emisionFin);

        $respuesta->totalSoles = $importeTotal[0]['total_soles'];
        $respuesta->totalDolares = $importeTotal[0]['total_dolares'];

        return $respuesta;
    }

    public function obtenerReporteReporteOperacionesXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start) {
        $personaId = $criterios[0]['persona'];
        $tipoDocumentoId = $criterios[0]['documentoTipo'];
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmisionDesde']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmisionHasta']);
        $tipoDocumentoIdFormateado = Util::convertirArrayXCadena($tipoDocumentoId);

        $empresaId = $criterios[0]['empresa'];
        $empresaIdFormateado = Util::convertirArrayXCadena($empresaId);

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];

        $data = Reporte::create()->obtenerReporteReporteOperacionesXCriterios($empresaIdFormateado, $tipoDocumentoIdFormateado, $personaId, $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start);

        $tamanio = count($data);
        for ($i = 0; $i < $tamanio; $i++) {
            $stringAcciones = '';
            if ($data[$i]['documento_id'] != '') {
                $stringAcciones = '<a onclick="verDetallePorOperacion(' . $data[$i]['documento_id'] . ')"><b><i class="fa fa-eye"" style="color:b"></i><b></a>';
            }
            $data[$i]['acciones'] = $stringAcciones;
        }

        return $data;
    }

    public function obtenerCantidadReporteReporteOperacionesXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start) {
        $personaId = $criterios[0]['persona'];
        $tipoDocumentoId = $criterios[0]['documentoTipo'];
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmisionDesde']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmisionHasta']);
        $tipoDocumentoIdFormateado = Util::convertirArrayXCadena($tipoDocumentoId);

        $empresaId = $criterios[0]['empresa'];
        $empresaIdFormateado = Util::convertirArrayXCadena($empresaId);

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];
        return Reporte::create()->obtenerCantidadReporteReporteOperacionesXCriterios($empresaIdFormateado, $tipoDocumentoIdFormateado, $personaId, $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start);
    }

    public function obtenerReporteReporteOperacionesExcel($data) {
        if (ObjectUtil::isEmpty($data)) {
            throw new WarningException("No existe datos para exportar");
        } else {
            $this->crearReporteReporteOperacionesExcel($data, "Reporte de Operaciones");
        }
    }

    private function crearReporteReporteOperacionesExcel($reportes, $titulo) {

        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':I' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $titulo);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':I' . $i)->applyFromArray($this->estiloTituloReporte);

        $i += 2;
        /* F. Creación	F. Emisión	Vendedor	Tipo documento	Cliente	Serie	Número	Total
         */
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'F. Creación');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'F. Emisión');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'Tipo documento');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'Persona');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'Serie');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, 'Número');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, 'Descripción');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, 'Total S/.');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, 'Total $');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':I' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;

        foreach ($reportes as $reporte) {

            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':I' . $i)->applyFromArray($this->estiloInformacion);

            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $reporte['fecha_creacion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, str_replace('00:00:00', '', $reporte['fecha_emision']));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $reporte['documento_tipo_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, $reporte['persona_nombre_completo']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, ' ' . $reporte['serie']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, ' ' . $reporte['numero']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, $reporte['descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, $reporte['total_soles']);
            $objPHPExcel->getActiveSheet()->getStyle('H' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, $reporte['total_dolares']);
            $objPHPExcel->getActiveSheet()->getStyle('I' . $i)->getNumberFormat()->setFormatCode('#,##0.00');

            $i += 1;
        }

        for ($i = 'A'; $i <= 'I'; $i++) {

            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle($titulo);

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/reporte.xlsx');

        return 1;
    }

    public function verDetallePorOperacion($documentoId) {
        return OperacionNegocio::create()->visualizarDocumento($documentoId);
    }

    //reporte ventas producto por periodo
    public function obtenerConfiguracionesInicialesProductoPorPeriodo() {
        $respuesta = new ObjectUtil();
        $respuesta->empresa = EmpresaNegocio::create()->getEmpresaActivas();
        $respuesta->fecha_primer_documento = DocumentoNegocio::create()->obtenerFechaPrimerDocumento();
        $respuesta->bien = BienNegocio::create()->obtenerBienKardexXEmpresa(-1);
        //        $respuesta->bien_tipo = BienNegocio::create()->obtenerBienTipo();
        $respuesta->bien_tipo = BienNegocio::create()->obtenerBienTipoPadre();

        return $respuesta;
    }

    public function reporteProductoPorPeriodo($criterios, $tipo) {
        $bienTipo = $criterios[0]['bienTipo'];
        $tienda = $criterios[0]['tienda'];
        $bien = $criterios[0]['bien'];
        $periodo = $criterios[0]['periodo'];

        if (ObjectUtil::isEmpty($bienTipo)) {
            $bienTipo = $criterios[0]['bienTipoPadre'];
        }

        if (!ObjectUtil::isEmpty($bienTipo)) {
            $dataBienTipoHijos = BienNegocio::create()->obtenerBienTipoHijosXBienTipoPadreId($bienTipo);
        }

        if (!ObjectUtil::isEmpty($dataBienTipoHijos)) {
            foreach ($dataBienTipoHijos as $item) {
                array_push($bienTipo, $item['id']);
            }
        }

        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmision']['inicio']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmision']['fin']);

        $tiendaIdFormateado = Util::convertirArrayXCadena($tienda);
        $bienIdFormateado = Util::convertirArrayXCadena($bien);
        $bienTipoIdFormateado = Util::convertirArrayXCadena($bienTipo);

        return Reporte::create()->reporteProductoPorPeriodo($bienIdFormateado, $bienTipoIdFormateado, $tiendaIdFormateado, $emisionInicio, $emisionFin, $tipo, $periodo);
    }

    public function obtenerReporteProductoPorPeriodoExcel($criterios, $tipo) {

        $respuesta = $this->reporteProductoPorPeriodo($criterios, $tipo);

        if ($tipo == 1) {
            $parametro->titulo = "REPORTE DE VENTAS DE PRODUCTOS POR PERIODO";
            $parametro->columnaImporte = "Importe vendido";
        }
        if ($tipo == 4) {
            $parametro->titulo = "REPORTE DE COMPRAS DE PRODUCTOS POR PERIODO";
            $parametro->columnaImporte = "Importe comprado";
        }

        if (ObjectUtil::isEmpty($respuesta)) {
            throw new WarningException("No existe datos para exportar");
        } else {
            $this->crearReporteProductoPorPeriodoExcel($respuesta, $parametro);
        }
    }

    private function crearReporteProductoPorPeriodoExcel($reportes, $parametro) {
        $titulo = $parametro->titulo;

        $this->estilosExcel();
        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':I' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $titulo);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':I' . $i)->applyFromArray($this->estiloTituloReporte);

        $i += 2;
        //Cód. Bien	Bien	Tipo bien		Importe vendido
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Código');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'Producto');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'Periodo');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'G.P. Principal');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'G.P. Secundario');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, 'Cantidad');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, 'Unidad control');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, $parametro->columnaImporte . ' S/.');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, $parametro->columnaImporte . ' $');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':I' . $i)->applyFromArray($this->estiloTituloColumnas);

        $i += 1;

        foreach ($reportes as $reporte) {
            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $reporte['bien_codigo']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $reporte['bien_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $reporte['fecha_tiempo']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, $reporte['bien_tipo_padre_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, $reporte['bien_tipo_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $reporte['cantidad_conv']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, $reporte['unidad_control']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, round($reporte['importe_total_soles'], 2));
            $objPHPExcel->getActiveSheet()->getStyle('H' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, round($reporte['importe_total_dolares'], 2));
            $objPHPExcel->getActiveSheet()->getStyle('I' . $i)->getNumberFormat()->setFormatCode('#,##0.00');

            $objPHPExcel->getActiveSheet()->getStyle('A' . $i)->applyFromArray($this->estiloTextoInformacion);
            $objPHPExcel->getActiveSheet()->getStyle('B' . $i . ':I' . $i)->applyFromArray($this->estiloInformacion);

            $i += 1;
        }

        for ($i = 'A'; $i <= 'I'; $i++) {

            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle("reporte");

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/reporte.xlsx');

        return 1;
    }

    public function reportePorClienteObtenerGraficoClientesDolares($criterios, $sumatoria) {
        return $this->reportePorClienteObtenerGraficoClientes($criterios, $sumatoria, 4);
    }

    public function reportePorClienteObtenerGraficoClientesSoles($criterios, $sumatoria) {
        return $this->reportePorClienteObtenerGraficoClientes($criterios, $sumatoria, 2);
    }

    public function reportePorClienteObtenerGraficoClientes($criterios, $sumatoria, $monedaId) {
        $personaId = $criterios[0]['persona'];
        $tipoDocumentoId = $criterios[0]['documentoTipo'];
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmisionDesde']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmisionHasta']);

        //seleccion de bien tipo e hijos        
        $bienTipo = $criterios[0]['bienTipo'];

        if (ObjectUtil::isEmpty($bienTipo)) {
            $bienTipo = $criterios[0]['bienTipoPadre'];
        }

        if (!ObjectUtil::isEmpty($bienTipo)) {
            $dataBienTipoHijos = BienNegocio::create()->obtenerBienTipoHijosXBienTipoPadreId($bienTipo);
        }

        if (!ObjectUtil::isEmpty($dataBienTipoHijos)) {
            foreach ($dataBienTipoHijos as $item) {
                array_push($bienTipo, $item['id']);
            }
        }
        $bienTipoIdFormateado = Util::convertirArrayEnCadena($bienTipo);
        // fin         

        $tipoDocumentoIdFormateado = Util::convertirArrayEnCadena($tipoDocumentoId);

        $empresaId = $criterios[0]['empresa'];
        $empresaIdFormateado = Util::convertirArrayEnCadena($empresaId);

        // Solo mostramos a los clientes que hayan representado más del 5% de ventas
        $importeMinimo = $sumatoria * 0.05;

        $reporte = Reporte::create()->reportePorClienteObtenerGraficoClientes($empresaIdFormateado, $tipoDocumentoIdFormateado, $personaId, $emisionInicio, $emisionFin, $bienTipoIdFormateado, $monedaId, $importeMinimo);

        // Sumamos los totales
        if (!ObjectUtil::isEmpty($reporte)) {
            $sumatoriaClientes = 0;
            foreach ($reporte as $item) {
                $sumatoriaClientes = $sumatoriaClientes + $item['total'];
            }
            if ($sumatoria - $sumatoriaClientes > 0) {
                array_push($reporte, array("id" => 0, "persona_nombre_completo" => "Otros", "total" => $sumatoria - $sumatoriaClientes));
            }
        }

        return $reporte;
    }

    public function reportePorClienteObtenerGraficoProductosDolares($criterios, $sumatoria) {
        return $this->reportePorClienteObtenerGraficoProductos($criterios, $sumatoria, 4);
    }

    public function reportePorClienteObtenerGraficoProductosSoles($criterios, $sumatoria) {
        return $this->reportePorClienteObtenerGraficoProductos($criterios, $sumatoria, 2);
    }

    public function reportePorClienteObtenerGraficoProductos($criterios, $sumatoria, $monedaId) {
        $personaId = $criterios[0]['persona'];
        $tipoDocumentoId = $criterios[0]['documentoTipo'];
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmisionDesde']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmisionHasta']);

        //seleccion de bien tipo e hijos        
        $bienTipo = $criterios[0]['bienTipo'];

        if (ObjectUtil::isEmpty($bienTipo)) {
            $bienTipo = $criterios[0]['bienTipoPadre'];
        }

        if (!ObjectUtil::isEmpty($bienTipo)) {
            $dataBienTipoHijos = BienNegocio::create()->obtenerBienTipoHijosXBienTipoPadreId($bienTipo);
        }

        if (!ObjectUtil::isEmpty($dataBienTipoHijos)) {
            foreach ($dataBienTipoHijos as $item) {
                array_push($bienTipo, $item['id']);
            }
        }
        $bienTipoIdFormateado = Util::convertirArrayEnCadena($bienTipo);
        // fin         

        $tipoDocumentoIdFormateado = Util::convertirArrayEnCadena($tipoDocumentoId);

        $empresaId = $criterios[0]['empresa'];
        $empresaIdFormateado = Util::convertirArrayEnCadena($empresaId);

        // Solo mostramos a los clientes que hayan representado más del 5% de ventas
        $importeMinimo = $sumatoria * 0.05;

        $reporte = Reporte::create()->reportePorClienteObtenerGraficoProductos($empresaIdFormateado, $tipoDocumentoIdFormateado, $personaId, $emisionInicio, $emisionFin, $bienTipoIdFormateado, $monedaId, $importeMinimo);

        // Sumamos los totales
        if (!ObjectUtil::isEmpty($reporte)) {
            $sumatoriaClientes = 0;
            foreach ($reporte as $item) {
                $sumatoriaClientes = $sumatoriaClientes + $item['total'];
            }
            if ($sumatoria - $sumatoriaClientes > 0) {
                array_push($reporte, array("id" => 0, "bien_tipo_descripcion" => "Otros", "bien_tipo_padre_descripcion" => "", "total" => $sumatoria - $sumatoriaClientes));
            }
        }

        return $reporte;
    }

    public function obtenerDataCotizaciones() {
        return Reporte::create()->obtenerDataCotizaciones();
    }

    public function obtenerCotizacionesDetalle($bienId) {
        return Reporte::create()->obtenerCotizacionesDetalle($bienId);
    }

    public function obtenerDataCotizacionesExt() {
        return Reporte::create()->obtenerDataCotizacionesExt();
    }

    public function obtenerCotizacionesDetalleExt($bienId) {
        return Reporte::create()->obtenerCotizacionesDetalleExt($bienId);
    }

    //TRANSFERENCIA TRANSFORMACION NO ATENDIDAS
    public function obtenerConfiguracionesInicialesTransferenciaTransformacionNoAtendida() {
        $respuesta = new ObjectUtil();
        $respuesta->dataMotivoTraslado = DocumentoTipoDatoListaNegocio::create()->obtenerXIds('321,322');
        //        $respuesta->dataPersona=  null;        
        $respuesta->dataFecha = DocumentoNegocio::create()->obtenerFechaPrimerDocumento();

        return $respuesta;
    }

    public function obtenerReporteTransferenciaTransformacionNoAtendidaXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start) {
        $motivoTraslado = $criterios[0]['motivoTraslado'];
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmisionDesde']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmisionHasta']);
        $motivoTrasladoIds = Util::convertirArrayXCadena($motivoTraslado);

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];

        return Reporte::create()->obtenerReporteTransferenciaTransformacionNoAtendidaXCriterios($motivoTrasladoIds, $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start);
    }

    public function obtenerCantidadReporteTransferenciaTransformacionNoAtendidaXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start) {
        $motivoTraslado = $criterios[0]['motivoTraslado'];
        $emisionInicio = $this->formatearFechaBD($criterios[0]['fechaEmisionDesde']);
        $emisionFin = $this->formatearFechaBD($criterios[0]['fechaEmisionHasta']);
        $motivoTrasladoIds = Util::convertirArrayXCadena($motivoTraslado);

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];
        return Reporte::create()->obtenerCantidadReporteTransferenciaTransformacionNoAtendidaXCriterios($motivoTrasladoIds, $emisionInicio, $emisionFin, $columnaOrdenar, $formaOrdenar);
    }

    //fin TRANSFERENCIA TRANSFORMACION NO ATENDIDAS
    // TRANSFERENCIA DE PRODUCTOS DIFERENTES
    public function obtenerReporteTransferenciaDiferenteXCriterios($elemntosFiltrados, $columns, $order, $start) {
        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];

        return Reporte::create()->obtenerReporteTransferenciaDiferenteXCriterios($columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start);
    }

    public function obtenerCantidadReporteTransferenciaDiferenteXCriterios($elemntosFiltrados, $columns, $order, $start) {
        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];

        return Reporte::create()->obtenerCantidadReporteTransferenciaDiferenteXCriterios($columnaOrdenar, $formaOrdenar);
    }

    //FIN TRANSFERENCIA DE PRODUCTOS DIFERENTES
    public function estiloTituloColumnasConParametros($fuenteNombre = 'Arial', $fuenteTamanio = '10', $bordeEstilo = 'thin', $colorCelda = 'FFFFFF', $rellenoEstilo = 'solid') {
        return
                array(
                    'font' => array(
                        'name' => $fuenteNombre,
                        'bold' => true,
                        'size' => $fuenteTamanio
                    ),
                    'borders' => array(
                        'allborders' => array(
                            //                    'style' => PHPExcel_Style_Border::BORDER_HAIR, 
                            'style' => $bordeEstilo,
                            'color' => array(
                                'rgb' => '000000'
                            )
                        )
                    ),
                    'alignment' => array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                        'wrap' => FALSE
                    ),
                    'fill' => array(
                        //                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'type' => $rellenoEstilo,
                        'color' => array('rgb' => $colorCelda)
                    )
        );
    }

    public function exportarReporteVentas($proveedor) {

        $data = Reporte::create()->exportarReporteVentas($proveedor);

        if (ObjectUtil::isEmpty($data)) {
            throw new WarningException("No existe información para exportar");
        } else {
            $data_grafico_vencidas = Reporte::create()->obtenerDataVencidasGraficoReporteVentas();
            $data_grafico_vigentes = Reporte::create()->obtenerDataVigentesGraficoReporteVentas();

            $this->estilosExcel();
            $objPHPExcel = new PHPExcel();
            $worksheet = $objPHPExcel->getSheet(0);

            $i = 1;
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':Q' . $i);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'REPORTE  DE FACTURAS DE VENTA');
            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':Q' . $i)->applyFromArray($this->estiloTituloReporte);

            $i += 2;

            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Fecha Emisión');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'Tipo');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'Número');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'Cliente');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'Moneda');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, 'Valor venta');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, 'Precio venta');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, 'Total pagado');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, 'Fecha pago Precio Venta');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('J' . $i, 'Importe por Nota de Crédito');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('K' . $i, 'Estado pago');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('L' . $i, 'Retención 3%');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('M' . $i, 'Fecha pago Retención');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('N' . $i, 'Fecha Recepción');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('O' . $i, 'Días Credito');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('P' . $i, 'Fecha Vencimiento');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('Q' . $i, 'Días vencidos');
            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':Q' . $i)->applyFromArray($this->estiloTituloColumnasConParametros());

            $i += 1;
            foreach ($data as $value) {
                $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $value['fecha_emision']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $value['tipo']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $value['numero']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, $value['nombre']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, $value['moneda']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $value['subtotal']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, $value['total']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, $value['importe_pagado']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, $value['fecha_total']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('J' . $i, $value['importe_nota']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('K' . $i, $value['estado_pago']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('L' . $i, $value['retencion']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('M' . $i, $value['fecha_retencion']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('N' . $i, $value['fecha_recepcion']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('O' . $i, $value['dias_credito']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('P' . $i, $value['fecha_vencimiento']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('Q' . $i, $value['dias_vencidos']);
                $i += 1;
            }

            $objPHPExcel->getActiveSheet()->getStyle('A4' . ':Q' . ($i - 1))->applyFromArray($this->estiloInformacion);
            $objPHPExcel->getActiveSheet()->getStyle('E4:H' . ($i - 1))->getNumberFormat()->setFormatCode('#,##0.00');
            $objPHPExcel->getActiveSheet()->getStyle('J4:J' . ($i - 1))->getNumberFormat()->setFormatCode('#,##0.00');
            $objPHPExcel->getActiveSheet()->getStyle('L4:L' . ($i - 1))->getNumberFormat()->setFormatCode('#,##0.00');

            //        for ($i = 'A'; $i <= 'I'; $i++) {
            //
            //            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
            //        }
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(100);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);

            if (!ObjectUtil::isEmpty($data_grafico_vencidas)) {
                $grafico_vencidas = GraficoNegocio::create()->graficarDeudasVencidasxCliente($data_grafico_vencidas);
                $objDrawingPType = new PHPExcel_Worksheet_Drawing();
                $objDrawingPType->setWorksheet($worksheet);
                $objDrawingPType->setName("grafico1");
                $objDrawingPType->setPath($grafico_vencidas);
                $celda1 = 'D' . ($i + 5);
                $objDrawingPType->setCoordinates($celda1);
                $objDrawingPType->setHeight(1400);
                $objDrawingPType->setWidth(700);
            }
            if (!ObjectUtil::isEmpty($data_grafico_vigentes)) {
                $grafico_vigentes = GraficoNegocio::create()->graficarDeudasVigentesxCliente($data_grafico_vigentes);
                $objDrawingPType = new PHPExcel_Worksheet_Drawing();
                $objDrawingPType->setWorksheet($worksheet);
                $objDrawingPType->setName("grafico2");
                $objDrawingPType->setPath($grafico_vigentes);
                $celda1 = 'D' . ($i + 30);
                $objDrawingPType->setCoordinates($celda1);
                $objDrawingPType->setHeight(1400);
                $objDrawingPType->setWidth(700);
            }

            $x = $i;
            for ($a = 1; $a <= $x; $a++) {
                $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
            }

            $objPHPExcel->getActiveSheet()->setTitle("Reporte Facturas de Venta");
            $objPHPExcel->setActiveSheetIndex(0);

            //        $fecReporte = date("d-m-Y_h-i_a");
            $nombre = "Reporte_Facturas_de_Venta.xlsx";
            $ruta_archivo = __DIR__ . '/../../util/formatos/' . $nombre;
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save($ruta_archivo);

            $url = explode(__DIR__ . "/../../", $ruta_archivo);
            $url = "modeloNegocio\almacen/../../" . $url[1];
            return $url;
        }
    }

    public function exportarReporteVentasConFormato($proveedor) {

        $data = Reporte::create()->exportarReporteVentas($proveedor);

        if (ObjectUtil::isEmpty($data)) {
            throw new WarningException("No existe información para exportar");
        } else {
            $data_grafico_vencidas = Reporte::create()->obtenerDataVencidasGraficoReporteVentas();
            $data_grafico_vigentes = Reporte::create()->obtenerDataVigentesGraficoReporteVentas();

            $estilos_cabecera = array(
                'font' => array(
                    'name' => 'Calibri',
                    'bold' => true,
                    'italic' => false,
                    'strike' => false,
                    'size' => 16,
                    'color' => array('rgb' => 'FFFFFF'),
                ), 'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'FF0000')
                )
            );

            $estilos_columna = array(
                'font' => array(
                    'name' => 'Calibri',
                    'bold' => true,
                    'italic' => false,
                    'strike' => false,
                    'size' => 11,
                    'color' => array('rgb' => 'FFFFFF'),
                ), 'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'FF0000'),
                )
            );

            $estilos_tabla = array(
                'font' => array(
                    'name' => 'Calibri',
                    'bold' => true,
                    'italic' => false,
                    'strike' => false,
                    'size' => 11,
                    'color' => array('rgb' => 'FFFFFF'),
                ), 'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'FF0000'),
                ), 'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            );
            $estilos_retencion = array(
                'font' => array(
                    'name' => 'Calibri',
                    'bold' => false,
                    'italic' => false,
                    'strike' => false,
                    'size' => 11,
                    'color' => array('rgb' => '000000'),
                ), 'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'A9D08E'),
                ), 'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            );
            $estilos_filas = array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'FFFBE5'),
                ), 'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            );

            $aplicar_estilo = function ($estado) {
                switch ($estado) {
                    case 'Pagada':
                        $color = '006100';
                        $fondo = 'C6EFCE';
                        break;
                    case 'Anulada':
                        $color = '9C6500';
                        $fondo = 'FFEB9C';
                        break;
                    case 'Por cobrar':
                        $color = '375623';
                        $fondo = 'CCFF66';
                        break;
                    case 'Transferencia gratuita':
                        $color = '305496';
                        $fondo = 'DDEBF7';
                        break;
                    case 'Vencida':
                    case 'Vencida Parcialmente':
                        $color = '960006';
                        $fondo = 'FFC7CE';
                        break;
                }
                return array(
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => $color)
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => $fondo)
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
                );
            };
            $objPHPExcel = new PHPExcel();
            $i = 1;
            $worksheet = $objPHPExcel->getSheet(0);
            $objPHPExcel->getActiveSheet()->getCell('A' . $i)->setValue('REPORTE FACTURAS DE VENTAS');
            $objPHPExcel->getActiveSheet()->getStyle('A' . $i)->applyFromArray($estilos_cabecera);
            $objPHPExcel->getActiveSheet()->mergeCells('A' . $i . ':S' . $i);
            $objPHPExcel->getActiveSheet()->getStyle('A' . $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $i++;
            $objPHPExcel->getActiveSheet()->setAutoFilter('A' . $i . ':S' . $i);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'CLIENTE');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'Nº FACTURA');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'FECHA EMISIÓN');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'MONEDA');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, '$ SIN IGV (VALOR VENTA)');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, 'CON IGV');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, 'RETENCIÓN 3%');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, 'COMP. RET');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, 'FECHA PAGO RETENC.');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('J' . $i, 'IMPORTE NOTA CRED.');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('K' . $i, 'PAGO NETO');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('L' . $i, 'FECHA RECEP.');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('M' . $i, 'CRÉDITO');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('N' . $i, 'FECHA VCTO.');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('O' . $i, 'ESTADO');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('P' . $i, 'DÍAS MOROSIDAD');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('Q' . $i, 'FECHA PAGO');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('R' . $i, 'BANCO');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('S' . $i, 'DÍAS DE PAGO');
            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':S' . $i)->applyFromArray($estilos_tabla);
            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':S' . $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':S' . $i)->getAlignment()->setWrapText(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(50);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(18);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(14);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(18);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(18);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(16);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(16);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(12);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(16);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(13);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(9);
            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(11);
            $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(13);
            $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(16);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(16);
            $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(16);
            $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(16);
            $objPHPExcel->getActiveSheet()->getStyle('A:S')->getAlignment()->setWrapText(true);
            $objPHPExcel->getActiveSheet()->getStyle('B:D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('H:S')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A:S')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('K')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(50);
            $i++;
            $pos_cabecera = $i;
            foreach ($data as $value) {
                $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $value['nombre']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $value['numero']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $value['fecha_emision']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, $value['moneda']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, $value['subtotal']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $value['total']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, is_null($value['retencion']) ? '0' : $value['retencion']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, is_null($value['retencion']) ? 'NO TIENE' : 'SI TIENE');
                $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, is_null($value['fecha_retencion']) ? '-' : $value['fecha_retencion']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('J' . $i, is_null($value['importe_nota']) ? '0' : $value['importe_nota']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('K' . $i, is_null($value['importe_pagado']) ? '0' : $value['importe_pagado']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('L' . $i, is_null($value['fecha_recepcion']) ? $value['fecha_emision'] : $value['fecha_recepcion']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('M' . $i, $value['dias_credito']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('N' . $i, $value['fecha_vencimiento']);
                $estado = $value['estado_pago'];
                $morosidad = is_null($value['fecha_emision']) || in_array(strtoupper($estado), ["PAGADA", "ANULADA", "TRANSFERENCIA GRATUITA"]);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('P' . $i, $morosidad ? '-' : ('=(TODAY()-L' . $i . ')-M' . $i));
                $objPHPExcel->getActiveSheet()->getStyle('P' . $i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_GENERAL);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('Q' . $i, is_null($value['fecha_total']) ? '-' : $value['fecha_total']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('R' . $i, is_null($value['banco']) ? '-' : $value['banco']);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('S' . $i, (!is_null($value['fecha_total']) && !is_null($value['fecha_emision'])) ? ('=Q' . $i . '-L' . $i) : '-');
                $objPHPExcel->setActiveSheetIndex()->setCellValue('O' . $i, $estado);
                $objPHPExcel->getActiveSheet()->getStyle('O' . $i)->applyFromArray($aplicar_estilo($estado));

                if ($value['simbolo'] === '$') {
                    $objPHPExcel->getActiveSheet()->getStyle('E' . $i . ':G' . $i)->getNumberFormat()->setFormatCode("[$$-es-US]#,##0.00");
                    $objPHPExcel->getActiveSheet()->getStyle('J' . $i . ':K' . $i)->getNumberFormat()->setFormatCode("[$$-es-US]#,##0.00");
                    //                    $objPHPExcel->getActiveSheet()->getStyle('K' . $i)->getNumberFormat()->setFormatCode("[$$-es-US]#,##0.00");
                } else {
                    //                    $objPHPExcel->getActiveSheet()->getStyle('D' . $i . ':E' . $i)->getNumberFormat()->setFormatCode("_(\"S/.\"* #,##0.00_);_(\"S/.\"* \(#,##0.00\);_(\"S/.\"* \"-\"??_);_(@_)");
                    $objPHPExcel->getActiveSheet()->getStyle('E' . $i . ':G' . $i)->getNumberFormat()->setFormatCode("\"S/.\"#,##0.00");
                    $objPHPExcel->getActiveSheet()->getStyle('J' . $i . ':K' . $i)->getNumberFormat()->setFormatCode("\"S/.\"#,##0.00");
                    //                    $objPHPExcel->getActiveSheet()->getStyle('K' . $i)->getNumberFormat()->setFormatCode("\"S/.\"#,##0.00");
                }
                $i += 1;
            }
            $objPHPExcel->getActiveSheet()->getStyle('A' . $pos_cabecera . ':N' . ($i - 1))->applyFromArray($estilos_filas);
            $objPHPExcel->getActiveSheet()->getStyle('P' . $pos_cabecera . ':S' . ($i - 1))->applyFromArray($estilos_filas);
            $objPHPExcel->getActiveSheet()->freezePane('B' . $pos_cabecera);
            if (!ObjectUtil::isEmpty($data_grafico_vencidas)) {
                $grafico_vencidas = GraficoNegocio::create()->graficarDeudasVencidasxCliente($data_grafico_vencidas);
                $objDrawingPType = new PHPExcel_Worksheet_Drawing();
                $objDrawingPType->setWorksheet($worksheet);
                $objDrawingPType->setName("grafico1");
                $objDrawingPType->setPath($grafico_vencidas);
                $celda1 = 'D' . ($i + 5);
                $objDrawingPType->setCoordinates($celda1);
                $objDrawingPType->setHeight(1400);
                $objDrawingPType->setWidth(700);
            }
            if (!ObjectUtil::isEmpty($data_grafico_vigentes)) {
                $grafico_vigentes = GraficoNegocio::create()->graficarDeudasVigentesxCliente($data_grafico_vigentes);
                $objDrawingPType = new PHPExcel_Worksheet_Drawing();
                $objDrawingPType->setWorksheet($worksheet);
                $objDrawingPType->setName("grafico2");
                $objDrawingPType->setPath($grafico_vigentes);
                $celda1 = 'D' . ($i + 40);
                $objDrawingPType->setCoordinates($celda1);
                $objDrawingPType->setHeight(1400);
                $objDrawingPType->setWidth(700);
            }
            $objPHPExcel->getActiveSheet()->setTitle("Reporte Facturas de Venta");
            $objPHPExcel->setActiveSheetIndex(0);
            $nombre = "Reporte_Facturas_de_Venta.xlsx";
            $ruta_archivo = __DIR__ . '/../../util/formatos/' . $nombre;
            $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
            $objWriter->setPreCalculateFormulas(true);
            $objWriter->save($ruta_archivo);

            $url = explode(__DIR__ . "/../../", $ruta_archivo);
            $url = "modeloNegocio\almacen/../../" . $url[1];
            return $url;
        }
    }

    //REPORTE CXP BHDT
    public function obtenerReporteDeudaBHDTXCriterios($tipo1, $tipo2, $criterios, $elemntosFiltrados, $columns, $order, $start) {
        $personaId = $criterios[0]['persona'];
        $mostrarPagados = $criterios[0]['mostrar'];
        $mostrarLib = $criterios[0]['mostrarLib'];
        if ($criterios[0]['fechaVencimientoDesde'] != '') {
            $fechaVencimientoDesde = DateUtil::formatearCadenaACadenaBD($criterios[0]['fechaVencimientoDesde']);
        }

        if ($criterios[0]['fechaVencimientoHasta'] != '') {
            $fechaVencimientoHasta = DateUtil::formatearCadenaACadenaBD($criterios[0]['fechaVencimientoHasta']);
        }
        $empresa = $criterios[0]['empresa'];
        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];

        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];
        //        $fechaVencimientoDesde = DateUtil::formatearCadenaACadenaBD($valor['inicio']);;
        //        echo "$mostrarPagados,$mostrarLib, $tipo1, $tipo2, $empresa, $personaId, $fechaVencimientoDesde, $fechaVencimientoHasta, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start";
        return Reporte::create()->obtenerReporteDeudaBHDTXCriterios($mostrarPagados, $mostrarLib, $tipo1, $tipo2, $empresa, $personaId, $fechaVencimientoDesde, $fechaVencimientoHasta, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start);
    }

    public function obtenerCantidadReporteDeudaBHDTXCriterio($tipo1, $tipo2, $criterios, $elemntosFiltrados, $columns, $order, $start) {
        $personaId = $criterios[0]['persona'];
        $mostrarPagados = $criterios[0]['mostrar'];
        $mostrarLib = $criterios[0]['mostrarLib'];
        if ($criterios[0]['fechaVencimientoDesde'] != '') {
            $fechaVencimientoDesde = DateUtil::formatearCadenaACadenaBD($criterios[0]['fechaVencimientoDesde']);
        }
        if ($criterios[0]['fechaVencimientoHasta'] != '') {
            $fechaVencimientoHasta = DateUtil::formatearCadenaACadenaBD($criterios[0]['fechaVencimientoHasta']);
        }
        $empresa = $criterios[0]['empresa'];
        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];

        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];
        return Reporte::create()->obtenerCantidadReporteDeudaBHDTXCriterios($mostrarPagados, $mostrarLib, $tipo1, $tipo2, $empresa, $personaId, $fechaVencimientoDesde, $fechaVencimientoHasta, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start);
    }

    private function cargaCabeceraTabla($oSheet, $i) {
        $oSheet->setCellValue('A' . $i, 'N°');
        $oSheet->setCellValue('B' . $i, 'PROVEEDOR');
        $oSheet->setCellValue('C' . $i, 'RUC/DNI');
        $oSheet->setCellValue('D' . $i, 'TIPO DOCUMENTO');
        $oSheet->setCellValue('E' . $i, 'NÚMERO');
        $oSheet->setCellValue('F' . $i, 'F.EMISIÓN');
        $oSheet->setCellValue('G' . $i, 'F.RECEPCIÓN');
        $oSheet->setCellValue('H' . $i, 'MONEDA');
        $oSheet->setCellValue('I' . $i, 'SUB TOTAL');
        $oSheet->setCellValue('J' . $i, 'IGV');
        $oSheet->setCellValue('K' . $i, 'TOTAL');
        $oSheet->setCellValue('L' . $i, 'MOROSIDAD');
        $oSheet->setCellValue('M' . $i, 'ESTADO');
        $oSheet->setCellValue('N' . $i, 'F.PAGO');
        $oSheet->setCellValue('O' . $i, 'IMPORTE PAGADO');
        $oSheet->setCellValue('P' . $i, 'S/ DETRACCIÓN');
        $oSheet->setCellValue('Q' . $i, 'DEUDA PROG');
        $oSheet->setCellValue('R' . $i, 'DEUDA POR PROG');
        $oSheet->setCellValue('S' . $i, 'CONDICIÓN DE PAGO');
        $oSheet->setCellValue('T' . $i, 'F.VENCIMIENTO');
        $oSheet->setCellValue('U' . $i, '¿PAGÓ?');
        $oSheet->setCellValue('V' . $i, 'F.DETRACCIÓN');
        $oSheet->setCellValue('W' . $i, 'F.PROG');
        $oSheet->setCellValue('X' . $i, 'DÍAS TRANSCURRIDOS');
        $oSheet->setCellValue('Y' . $i, 'N° CUENTA');
        $oSheet->setCellValue('Z' . $i, 'COMENTARIOS');

        //        $oSheet->getStyle('A' . $i . ':Z' . $i)->applyFromArray($this->estiloTituloColumnasConParametros("Arial", 10, 'thin', 'eff0f1', PHPExcel_Style_Fill::FILL_SOLID));
        $oSheet->getStyle('A' . $i . ':Z' . $i)->applyFromArray($this->estiloTituloColumnasConParametros("Arial", 10, 'thin', '50A3F1', PHPExcel_Style_Fill::FILL_SOLID));
    }

    private function cargaTituloTabla($oSheet, $i, $titulo) {
        $oSheet->mergeCells('A' . $i . ':Z' . ($i + 1));
        $oSheet->setCellValue('A' . $i, $titulo);
        $oSheet->getStyle('A' . $i . ':Z' . ($i + 1))->applyFromArray($this->estiloTituloReporte);
    }

    public function exportarExcelComprasBHDT2($data) {
        if (ObjectUtil::isEmpty($data)) {
            throw new WarningException("No existe información para exportar");
        } else {
            $this->estilosExcel();
            $objPHPExcel = new PHPExcel();

            $hAdicionales = $this->creaHojaDetalle($objPHPExcel, $data);

            $this->creaHojasViernes($objPHPExcel, $hAdicionales);

            $this->creaHojaResumen($objPHPExcel, $hAdicionales);

            $nombre = "REPORTE_DE_CUENTAS_POR_PAGAR_BHDT.xlsx";
            $ruta_archivo = __DIR__ . '/../../util/formatos/' . $nombre;
            //        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
            $objWriter->setPreCalculateFormulas();
            $objWriter->save($ruta_archivo);

            $url = explode(__DIR__ . "/../../", $ruta_archivo);
            $url = "modeloNegocio\almacen/../../" . $url[1];
            return $url;
        }
    }

    private function creaHojaDetalle($objPHPExcel, $data) {
        $hojaTemp = $objPHPExcel->createSheet(1);

        $i = 1;
        $this->cargaTituloTabla($hojaTemp, $i, "REPORTE DE CUENTAS POR PAGAR - BHDT");

        $i += 2;
        $this->cargaCabeceraTabla($hojaTemp, $i);

        $hAdicionales = array(); // CHL: Hojas adicionales
        $i += 1;
        $iInicio = $i;
        foreach ($data as $key => $value) {
            $data[$key]['correlativo'] = $key + 1;
            $hojaTemp->setCellValue('A' . $i, $data[$key]['correlativo']);
            $hojaTemp->setCellValue('B' . $i, $value['persona_nombre_completo']);
            $hojaTemp->setCellValue('C' . $i, $value['codigo_identificacion']);
            $hojaTemp->setCellValue('D' . $i, $value['documento_tipo_descripcion']);
            $hojaTemp->setCellValue('E' . $i, $value['serie'] . '-' . $value['numero']);
            $hojaTemp->setCellValue('F' . $i, $value['fecha_emision']);
            $data[$key]['fecha_recepcion'] = (ObjectUtil::isEmpty(trim($value['fecha_recepcion']))) ? $value['fecha_emision'] : $value['fecha_recepcion'];
            $hojaTemp->setCellValue('G' . $i, $data[$key]['fecha_recepcion']);
            $hojaTemp->setCellValue('H' . $i, $value['moneda_descripcion']);
            $hojaTemp->setCellValue('I' . $i, $value['subtotal']);
            $hojaTemp->setCellValue('J' . $i, $value['igv']);
            $hojaTemp->setCellValue('K' . $i, $value['total']);
            // CHL: Calculamos la morosidad, el vencimiento y la fecha de pago
            $hojaTemp->setCellValue('L' . $i, "=TODAY()-G$i");
            $hojaTemp->setCellValue('M' . $i, "=IF(L$i>=30, \"VENCIDO\", \"CREDITO\")");

            $hoy = date('d-m-Y');
            $frecepcion = $hojaTemp->getCell('G' . $i)->getValue();
            $fecha1 = new DateTime($hoy);
            $fecha2 = new DateTime($frecepcion);
            $morosidad = $fecha1->diff($fecha2);
            $diferencia = $morosidad->days;

            if ($diferencia >= 30) {
                $hojaTemp->getStyle('M' . $i)
                        ->applyFromArray(array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'FC6A5B')
                            )
                ));
            } else {
                $hojaTemp->getStyle('M' . $i)
                        ->applyFromArray(array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'F2D349')
                            )
                ));
            }
            $vencimiento = $this->calculaFechaVencimiento($data[$key]['fecha_recepcion']);
            $data[$key]['fecha_vencimiento'] = $vencimiento;
            $viernes = $this->calculaFechaPago($vencimiento);
            $hojaTemp->setCellValue('N' . $i, "P. $viernes");

            $hojaTemp->setCellValue('O' . $i, $value['importe_pagado']);
            $hojaTemp->setCellValue('P' . $i, $value['detraccion']);
            $hojaTemp->setCellValue('Q' . $i, $value['deuda_liberada']);
            $hojaTemp->setCellValue('R' . $i, $value['deuda_por_liberar']);
            $hojaTemp->setCellValue('S' . $i, $value['condicion_pago']);
            $hojaTemp->setCellValue('T' . $i, $data[$key]['fecha_vencimiento']);
            $hojaTemp->setCellValue('U' . $i, $value['pago']);
            $hojaTemp->setCellValue('V' . $i, $value['fecha_detraccion']);
            $hojaTemp->setCellValue('W' . $i, $value['fecha_p_proveedor']);
            $hojaTemp->setCellValue('X' . $i, $value['semaforo2']);
            $hojaTemp->setCellValueExplicit('Y' . $i, $value['numero_cuenta']);
            $hojaTemp->setCellValueExplicit('Z' . $i, $value['descripcion']);

            // CHL: Adicionamos array de hojas
            $hAdicionales = $this->agregaElementoAHojas($hAdicionales, $viernes, $data[$key]);

            $i += 1;
        }
        $hojaTemp->getStyle('A' . $iInicio . ':Z' . ($i - 1))->applyFromArray($this->estiloInformacion);

        $hojaTemp->getStyle('L' . $iInicio . ':L' . ($i - 1))
                ->applyFromArray(array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'fff7e5')
                    )
        ));
        //        $hojaTemp->getStyle('M' . $iInicio . ':M' . ($i - 1))
        //                ->applyFromArray(array(
        //                    'fill' => array(
        //                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        //                        'color' => array('rgb' => 'ecbc4e')
        //                    )
        //        ));
        $hojaTemp->getStyle('N' . $iInicio . ':N' . ($i - 1))
                ->applyFromArray(array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '5eba7d')
                    )
        ));
        $hojaTemp->getStyle('I' . $iInicio . ':K' . ($i - 1))->getNumberFormat()->setFormatCode('#,##0.00');
        $hojaTemp->getStyle('O' . $iInicio . ':R' . ($i - 1))->getNumberFormat()->setFormatCode('#,##0.00');

        $letra = 'A';
        for ($i = 1; $i <= 26; $i++) {
            $hojaTemp->getColumnDimension($letra)->setAutoSize(TRUE);
            $letra++;
        }

        $x = $letra;
        for ($a = 1; $a <= $x; $a++) {
            $hojaTemp->getRowDimension($i)->setRowHeight(-1);
        }

        $hojaTemp->setTitle("CTAS POR PAGAR BHDT");

        ksort($hAdicionales, SORT_NUMERIC);

        return $hAdicionales;
    }

    private function creaHojasViernes($objPHPExcel, $hAdicionales) {
        if (ObjectUtil::isEmpty($hAdicionales))
            return;

        $iHoja = 2;
        foreach ($hAdicionales as $key => $data) {
            $kAdicional = $data[0];
            $hAdicional = $data[1];
            $sheet = $objPHPExcel->createSheet($key + 2);

            $i = 1;
            $this->cargaTituloTabla($sheet, $i, "CUENTAS POR PAGAR AL $kAdicional");

            $i += 2;
            $this->cargaCabeceraTabla($sheet, $i);

            $hAdicionales = array(); // CHL: Hojas adicionales
            $i += 1;
            $iInicio = $i;
            foreach ($hAdicional as $value) {
                $sheet->setCellValue('A' . $i, $value['correlativo']);
                $sheet->setCellValue('B' . $i, $value['persona_nombre_completo']);
                $sheet->setCellValue('C' . $i, $value['codigo_identificacion']);
                $sheet->setCellValue('D' . $i, $value['documento_tipo_descripcion']);
                $sheet->setCellValue('E' . $i, $value['serie'] . '-' . $value['numero']);
                $sheet->setCellValue('F' . $i, $value['fecha_emision']);
                $sheet->setCellValue('G' . $i, $value['fecha_recepcion']);
                $sheet->setCellValue('H' . $i, $value['moneda_descripcion']);
                $sheet->setCellValue('I' . $i, $value['subtotal']);
                $sheet->setCellValue('J' . $i, $value['igv']);
                $sheet->setCellValue('K' . $i, $value['total']);
                // CHL: Calculamos la morosidad, el vencimiento y la fecha de pago
                $sheet->setCellValue('L' . $i, "=TODAY()-G$i");
                $sheet->setCellValue('M' . $i, "=IF(L$i>=30, \"VENCIDO\", \"CREDITO\")");

                $hoy = date('d-m-Y');
                $frecepcion = $sheet->getCell('G' . $i)->getValue();
                $fecha1 = new DateTime($hoy);
                $fecha2 = new DateTime($frecepcion);
                $morosidad = $fecha1->diff($fecha2);
                $diferencia = $morosidad->days;

                if ($diferencia >= 30) {
                    $sheet->getStyle('M' . $i)
                            ->applyFromArray(array(
                                'fill' => array(
                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                    'color' => array('rgb' => 'FC6A5B')
                                )
                    ));
                } else {
                    $sheet->getStyle('M' . $i)
                            ->applyFromArray(array(
                                'fill' => array(
                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                    'color' => array('rgb' => 'F2D349')
                                )
                    ));
                }

                $sheet->setCellValue('N' . $i, "P. $kAdicional");
                $sheet->setCellValue('O' . $i, $value['importe_pagado']);
                $sheet->setCellValue('P' . $i, $value['detraccion']);
                $sheet->setCellValue('Q' . $i, $value['deuda_liberada']);
                $sheet->setCellValue('R' . $i, $value['deuda_por_liberar']);
                $sheet->setCellValue('S' . $i, $value['condicion_pago']);
                $sheet->setCellValue('T' . $i, $value['condicion_pago']);
                $sheet->setCellValue('U' . $i, $value['pago']);
                $sheet->setCellValue('V' . $i, $value['fecha_detraccion']);
                $sheet->setCellValue('W' . $i, $value['fecha_p_proveedor']);
                $sheet->setCellValue('X' . $i, $value['semaforo2']);
                $sheet->setCellValueExplicit('Y' . $i, $value['numero_cuenta']);
                $sheet->setCellValueExplicit('Z' . $i, $value['descripcion']);

                $i += 1;
            }
            $sheet->getStyle('A' . $iInicio . ':Z' . ($i - 1))->applyFromArray($this->estiloInformacion);

            $sheet->getStyle('L' . $iInicio . ':L' . ($i - 1))
                    ->applyFromArray(array(
                        'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'fff7e5')
                        )
            ));

            $sheet->getStyle('N' . $iInicio . ':N' . ($i - 1))
                    ->applyFromArray(array(
                        'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => '5eba7d')
                        )
            ));
            $sheet->getStyle('I' . $iInicio . ':K' . ($i - 1))->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle('O' . $iInicio . ':R' . ($i - 1))->getNumberFormat()->setFormatCode('#,##0.00');

            $letra = 'A';
            for ($i = 1; $i <= 26; $i++) {
                $sheet->getColumnDimension($letra)->setAutoSize(TRUE);
                $letra++;
            }

            $x = $letra;
            for ($a = 1; $a <= $x; $a++) {
                $sheet->getRowDimension($i)->setRowHeight(-1);
            }

            $sheet->setTitle($kAdicional);
            $objPHPExcel->setActiveSheetIndex($iHoja);
            $iHoja += 1;
        }
    }

    private function creaHojaResumen($objPHPExcel, $hAdicionales) {
        // Dibujamos la hoja resumen
        $objPHPExcel->setActiveSheetIndex(0);
        $hojaTemp = $objPHPExcel->getActiveSheet();
        $hojaTemp->setTitle("RESUMEN");

        $i = 1;
        $hojaTemp->mergeCells('A' . $i . ':H' . ($i + 1));
        $hojaTemp->setCellValue('A' . $i, " RESUMEN DE REPORTE DE CUENTAS POR PAGAR - BHDT");
        $hojaTemp->getStyle('A' . $i . ':H' . ($i + 1))->applyFromArray($this->estiloTituloReporte);

        $i += 2;
        $hojaTemp->setCellValue('A' . $i, "DÍA");
        $hojaTemp->setCellValue('B' . $i, "F. PAGO");
        $hojaTemp->setCellValue('C' . $i, "($) DEUDA PROG");
        $hojaTemp->setCellValue('D' . $i, "($) DEUDA POR PROG");
        $hojaTemp->setCellValue('E' . $i, "(S/) DEUDA PROG");
        $hojaTemp->setCellValue('F' . $i, "(S/) DEUDA POR PROG");
        $hojaTemp->setCellValue('G' . $i, "($) TOTAL");
        $hojaTemp->setCellValue('H' . $i, "(S/) TOTAL");
        //        $hojaTemp->getStyle('A' . $i . ':E' . $i)->applyFromArray($this->estiloTituloColumnasConParametros("Arial", 10, 'thin', 'eff0f1', PHPExcel_Style_Fill::FILL_SOLID));
        $hojaTemp->getStyle('A' . $i . ':H' . $i)->applyFromArray($this->estiloTituloColumnasConParametros("Arial", 10, 'thin', 'FF8868', PHPExcel_Style_Fill::FILL_SOLID));

        $i += 1;
        $iInicio = $i;
        foreach ($hAdicionales as $hAdicional) {
            $hojaTemp->setCellValue('A' . $i, $this->obtnerNombreDiaSemana($hAdicional[0]));
            $hojaTemp->setCellValue('B' . $i, $hAdicional[0]);
            $hojaTemp->setCellValue('C' . $i, $hAdicional[2]);
            $hojaTemp->setCellValue('D' . $i, $hAdicional[3]);
            $hojaTemp->setCellValue('E' . $i, $hAdicional[4]);
            $hojaTemp->setCellValue('F' . $i, $hAdicional[5]);
            $hojaTemp->setCellValue('G' . $i, $hAdicional[6]);
            $hojaTemp->setCellValue('H' . $i, $hAdicional[7]);
            $i += 1;
        }
        $hojaTemp->getStyle('A' . $iInicio . ':H' . ($i - 1))->applyFromArray($this->estiloInformacion);
        $hojaTemp->getStyle('C4' . ':H' . ($i - 1))->getNumberFormat()->setFormatCode('#,##0.00');

        for ($i = 'A'; $i <= 'H'; $i++) {
            $hojaTemp->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $hojaTemp->getRowDimension($i)->setRowHeight(-1);
        }
    }

    private function agregaElementoAHojas($hAdicionales, $viernes, $fila) {
        $keyArray = strtotime($viernes);
        if (ObjectUtil::isEmpty($hAdicionales) || !array_key_exists($keyArray, $hAdicionales)) {
            // array("viernes", nuevoArray, sumDolares, sumSoles)
            $hAdicionales[$keyArray] = array($viernes, array(), 0, 0, 0, 0, 0, 0);
        }
        if ($fila['moneda_descripcion'] == 'Dólares') {
            $hAdicionales[$keyArray][2] += $fila['deuda_liberada'];
            $hAdicionales[$keyArray][3] += $fila['deuda_por_liberar'];
            $hAdicionales[$keyArray][6] += $fila['deuda_liberada'] + $fila['deuda_por_liberar'];
        } else {
            $hAdicionales[$keyArray][4] += $fila['deuda_liberada'];
            $hAdicionales[$keyArray][5] += $fila['deuda_por_liberar'];
            $hAdicionales[$keyArray][7] += $fila['deuda_liberada'] + $fila['deuda_por_liberar'];
        }

        array_push($hAdicionales[$keyArray][1], $fila);
        return $hAdicionales;
    }

    private function obtnerNombreDiaSemana($fecha) {
        $time = strtotime($fecha);
        $diaSemana = date('w', $time);
        switch ($diaSemana) {
            case 0:
                return "Domingo";
                break;
            case 1:
                return "Lunes";
                break;
            case 2:
                return "Martes";
                break;
            case 3:
                return "Miércoles";
                break;
            case 4:
                return "Jueves";
                break;
            case 5:
                return "Viernes";
                break;
            case 6:
                return "Sábado";
                break;
        }
    }

    private function calculaFechaPago($fechaVencimiento) {
        $time = (strtotime("now") > strtotime($fechaVencimiento)) ? strtotime("now") : strtotime($fechaVencimiento);
        $diaSemana = date('w', $time);
        if ($diaSemana <= 3) {
            $diaAdicional = 3 - $diaSemana;
        } else if ($diaSemana <= 5) {
            $diaAdicional = 5 - $diaSemana;
        } else {
            $diaAdicional = 10 - $diaSemana;
        }
        $viernes = (strtotime("now") > strtotime($fechaVencimiento)) ? strtotime("now +$diaAdicional day") : strtotime("$fechaVencimiento +$diaAdicional day");
        return date('d-m-Y', $viernes);
    }

    private function calculaFechaVencimiento($fechaRecepcion) {
        $vencimiento = strtotime("$fechaRecepcion +30 day");
        return date('d-m-Y', $vencimiento);
    }

    public function obtenerDocumento($documentoId) {
        $respuesta = new ObjectUtil();

        $respuesta->dataDocumento = DocumentoNegocio::create()->obtenerDetalleDocumento($documentoId);
        $respuesta->comentarioDocumento = DocumentoNegocio::create()->obtenerComentarioDocumento($documentoId);

        $res = MovimientoBien::create()->obtenerMovimientoIdXDocumentoId($documentoId);
        if (!ObjectUtil::isEmpty($res[0]['movimiento_id'])) {
            $respuesta->detalleDocumento = MovimientoBien::create()->obtenerXIdMovimiento($res[0]['movimiento_id']);

            $dataMovimientoTipo = MovimientoTipoNegocio::create()->obtenerXDocumentoId($documentoId);
            $respuesta->dataMovimientoTipoColumna = MovimientoTipoNegocio::create()->obtenerMovimientoTipoColumna($dataMovimientoTipo[0]['movimiento_tipo_id']);
            $respuesta->organizador = OrganizadorNegocio::create()->obtenerXMovimientoTipo($dataMovimientoTipo[0]['movimiento_tipo_id']);
        }

        return $respuesta;
    }

    public function insertTarifario($origen, $destino, $persona, $moneda, $kilogramo, $sobre, $paquete, $bien,
            $precioMinimo, $precioArticulo, $usu_creacion) {
            
           if ($sobre == "" || empty($sobre)){   
            $sobre = 0;
        }
        
        if ($kilogramo == "" || empty($kilogramo)){  
            $kilogramo = 0;
        }
        
          if ($paquete == "" || empty($paquete)){ 
            $paquete = 0;
        }
   

        $response = Tarifario::create()->insertTarifario($origen, $destino, $persona, $moneda, $kilogramo, $sobre,
                $paquete, $bien, $precioMinimo, $precioArticulo, $usu_creacion);

        if ($response[0]["vout_exito"] == 0 && $response[0]["vout_exito"] != '') {
            throw new WarningException($response[0]["vout_mensajes"]);
        } else {
            return $response;
        }
    }

    public function insertTarifarioZona($origen, $destino, $moneda, $txtsobre, $txt1k, $txt2k, $txt3k, $txt4k, $txt5k, $usu_creacion) {
        $response = Tarifario::create()->insertTarifarioZona($origen, $destino, $moneda, $txtsobre, $txt1k, $txt2k, $txt3k, $txt4k, $txt5k, $usu_creacion);

        if ($response[0]["vout_exito"] == 0 && $response[0]["vout_exito"] != '') {
            throw new WarningException($response[0]["vout_mensajes"]);
        } else {
            return $response;
        }
    }

    public function getDataTarifario($criterios, $elementosFiltrados, $columns, $order, $start) {
        $origen = $criterios[0]['origen'];
        $destino = $criterios[0]['destino'];
        $persona = $criterios[0]['persona'];
        $articulo = $criterios[0]['articulo'];

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];

        return Tarifario::create()->getDataTarifario($origen, $destino, $persona, $articulo, $columnaOrdenar, $formaOrdenar, $elementosFiltrados, $start);
    }

    public function getDataTarifarioZona($criterios) {
        $agencia = $criterios[0]['agencia'];
        $reparto = $criterios[0]['reparto'];
        $resultado = Tarifario::create()->getDataTarifarioZona($agencia, $reparto);
        return $resultado;
    }

    public function deleteTarifario($id, $nom, $usuarioId) {
        $response = Tarifario::create()->deleteTarifario($id, $usuarioId);
        $response[0]['nombre'] = $nom;
        return $response;
    }

    public function deleteTarifarioZona($id, $nom, $usuarioId) {
        //        $id_usu_ensesion = $_SESSION['id_usuario'];
        $response = Tarifario::create()->deleteTarifarioZona($id, $usuarioId);
        $response[0]['nombre'] = $nom;
        return $response;
    }

    public function getTarifario($id, $usuarioId) {
        $response = Tarifario::create()->getTarifario($id);
        return $response;
    }

    public function getTarifarioZona($id, $usuarioId) {
        //      $id_usu_ensesion = $_SESSION['id_usuario'];
        $response = Tarifario::create()->getTarifarioZona($id);
        return $response;
    }

    public function updateTarifario($id, $origen, $destino, $persona, $moneda, $kilogramo, $sobre, $paquete, $bien,
            $precioMinimo, $precioArticulo, $usuarioId) {

        $response = Tarifario::create()->updateTarifario($id, $origen, $destino, $persona, $moneda, $kilogramo, $sobre,
                $paquete, $bien, $precioMinimo, $precioArticulo, $usuarioId);
        if ($response[0]["vout_exito"] == 0 && $response[0]["vout_exito"] != '') {
            throw new WarningException($response[0]["vout_mensajes"]);
        } else {
            return $response;
        }
    }

    public function updateTarifarioZona($id, $origen, $destino, $moneda, $txtsobre, $txt1k, $txt2k, $txt3k, $txt4k, $txt5k, $usu_creacion) {
        $response = Tarifario::create()->updateTarifarioZona($id, $origen, $destino, $moneda, $txtsobre, $txt1k, $txt2k, $txt3k, $txt4k, $txt5k, $usu_creacion);
        if ($response[0]["vout_exito"] == 0 && $response[0]["vout_exito"] != '') {
            throw new WarningException($response[0]["vout_mensajes"]);
        } else {
            return $response;
        }
    }

    public function obtenerZonaxAgencia($AgenciaID) {
        $resultado = Tarifario::create()->obtenerZonaxAgencia($AgenciaID);
        return $resultado;
    }

    public function importTarifario($agenciaOr, $agenciaDes, $codPersona, $persona, $codArticulo, $articulo, $moneda, $precioKg,
            $precioSobre, $precio5Kg, $precioMinimo, $precioArticulo, $usuarioCreacion) {
        return Tarifario::create()->importTarifario($agenciaOr, $agenciaDes, $codPersona, $persona, $codArticulo, $articulo,
                        $moneda, $precioKg, $precioSobre, $precio5Kg, $precioMinimo, $precioArticulo, $usuarioCreacion);
    }

    public function importTarifarioZona($agencia, $zona, $moneda, $precioSobre, $precio50, $precio51, $precio101, $precio251, $precio500, $usuarioCreacion) {
        return Tarifario::create()->importTarifarioZona($agencia, $zona, $moneda, $precioSobre, $precio50, $precio51, $precio101, $precio251, $precio500, $usuarioCreacion);
    }

    public function exportarTarifario($criterios) {
        $estiloTituloReporte = array(
            'font' => array(
                'name' => 'Arial',
                'bold' => true,
                'italic' => false,
                'strike' => false,
                'size' => 10
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'rotation' => 0,
                'wrap' => TRUE
            )
        );

        $estiloTituloColumnas = array(
            'font' => array(
                'name' => 'Arial',
                'bold' => true,
                'size' => 10
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array(
                        'rgb' => '000000'
                    )
                )
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => FALSE
            )
        );

        $estiloTxtInformacion = array(
            'font' => array(
                'name' => 'Arial',
                'size' => 9
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_HAIR,
                    'color' => array(
                        'rgb' => '000000'
                    )
                )
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => FALSE
            )
        );

        $estiloNumInformacion = array(
            'font' => array(
                'name' => 'Arial',
                'size' => 8
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_HAIR,
                    'color' => array(
                        'rgb' => '000000'
                    )
                )
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => FALSE
            )
        );

        $objPHPExcel = new PHPExcel();

        $i = 1;
        $j = 2;
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B' . $i . ':I' . $i);

//        $response
        // Agregar Informacion
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'LISTA DE TARIFARIOS');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':I' . $i)->applyFromArray($estiloTituloReporte);
//        $objPHPExcel->getActiveSheet()->setSharedStyle($estiloTituloReporte, "A" . $i . ":C" . $i);
        $i += 2;
        //$j++;
        $j += 2;

        $origen = $criterios[0]['origen'];
        $destino = $criterios[0]['destino'];
        $persona = $criterios[0]['persona'];
        $articulo = $criterios[0]['articulo'];
        $resultado = Tarifario::create()->getDataTarifarioExcel($origen, $destino, $persona, $articulo);
        // return $resultado;
        if (ObjectUtil::isEmpty($resultado)) {
            throw new WarningException("No existe datos para exportar");
        } else {
            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, '      ');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'Agencia Origen');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'Agencia Destino');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'Moneda');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'Persona');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, 'Precio mínimo');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, 'Artículo');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, 'Precio artículo');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, 'Precio por Kilogramo');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('J' . $i, 'Precio <= 5K');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('K' . $i, 'Precio Sobre');
            $objPHPExcel->getActiveSheet()->getStyle('B' . $i . ':K' . $i)->applyFromArray($estiloTituloColumnas);

            foreach ($resultado as $campo) {
                $objPHPExcel->setActiveSheetIndex()
                        ->setCellValue('B' . $j, $campo['agencia_origen'])
                        ->setCellValue('C' . $j, $campo['agencia_destino'])
                        ->setCellValue('D' . $j, $campo['moneda'])
                        ->setCellValue('E' . $j, $campo['nombre'])
                        ->setCellValue('F' . $j, $campo['precio_minimo'])
                        ->setCellValue('G' . $j, $campo['bien_descripcion'])
                        ->setCellValue('H' . $j, $campo['precio_articulo'])
                        ->setCellValue('I' . $j, $campo['precio_xk'])
                        ->setCellValue('J' . $j, $campo['precio_5k'])
                        ->setCellValue('K' . $j, $campo['precio_sobre']);
                $i += 1;
                $j++;
                $objPHPExcel->getActiveSheet()->getStyle('B' . $i . ':E' . $i)->applyFromArray($estiloTxtInformacion);
                $objPHPExcel->getActiveSheet()->getStyle('F' . $i)->applyFromArray($estiloNumInformacion);
                $objPHPExcel->getActiveSheet()->getStyle('G' . $i)->applyFromArray($estiloTxtInformacion);
                $objPHPExcel->getActiveSheet()->getStyle('H' . $i . ':K' . $i)->applyFromArray($estiloNumInformacion);
            }

            for ($i = 'A'; $i <= 'K'; $i++) {
                $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
            }

            // Renombrar Hoja
            $objPHPExcel->getActiveSheet()->setTitle('Tarifario');

            // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
            $objPHPExcel->setActiveSheetIndex(0);

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save(__DIR__ . '/../../util/formatos/lista_tarifario.xlsx');
            return 1;
        }
    }

    public function exportarTarifarioZona($criterios) {

        $estiloTituloReporte = array(
            'font' => array(
                'name' => 'Arial',
                'bold' => true,
                'italic' => false,
                'strike' => false,
                'size' => 10
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'rotation' => 0,
                'wrap' => TRUE
            )
        );

        $estiloTituloColumnas = array(
            'font' => array(
                'name' => 'Arial',
                'bold' => true,
                'size' => 10
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array(
                        'rgb' => '000000'
                    )
                )
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => FALSE
            )
        );

        $estiloTxtInformacion = array(
            'font' => array(
                'name' => 'Arial',
                'size' => 9
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_HAIR,
                    'color' => array(
                        'rgb' => '000000'
                    )
                )
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => FALSE
            )
        );

        $estiloNumInformacion = array(
            'font' => array(
                'name' => 'Arial',
                'size' => 8
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_HAIR,
                    'color' => array(
                        'rgb' => '000000'
                    )
                )
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => FALSE
            )
        );

        $objPHPExcel = new PHPExcel();

        $i = 1;
        $j = 2;
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B' . $i . ':J' . $i);

//        $response
        // Agregar Informacion
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'LISTA TARIFARIO ZONA');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':J' . $i)->applyFromArray($estiloTituloReporte);
//        $objPHPExcel->getActiveSheet()->setSharedStyle($estiloTituloReporte, "A" . $i . ":C" . $i);
        $i += 2;
        //$j++;
        $j += 2;

        $agencia = $criterios[0]['agencia'];
        $reparto = $criterios[0]['reparto'];
        $resultado = Tarifario::create()->getDataTarifarioZona($agencia, $reparto);
        // return $resultado;
        if (ObjectUtil::isEmpty($resultado)) {
            throw new WarningException("No existe datos para exportar");
        } else {
            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, '      ');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'Agencia');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'Zona Reparto');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'Moneda');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'Precio Sobre Kg');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, 'Precio por 0-50 Kg');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, 'Precio por 51-100 Kg');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, 'Precio por 101-250 Kg');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('I' . $i, 'Precio por 251-500 Kg');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('J' . $i, 'Precio por +500 Kg');
            $objPHPExcel->getActiveSheet()->getStyle('B' . $i . ':J' . $i)->applyFromArray($estiloTituloColumnas);

            foreach ($resultado as $campo) {
                $objPHPExcel->setActiveSheetIndex()
                        ->setCellValue('B' . $j, $campo['agencia'])
                        ->setCellValue('C' . $j, $campo['zona'])
                        ->setCellValue('D' . $j, $campo['descripcion'])
                        ->setCellValue('E' . $j, $campo['precio_sobre'])
                        ->setCellValue('F' . $j, $campo['precio_50K'])
                        ->setCellValue('G' . $j, $campo['precio_100K'])
                        ->setCellValue('H' . $j, $campo['precio_250K'])
                        ->setCellValue('I' . $j, $campo['precio_500K'])
                        ->setCellValue('J' . $j, $campo['precio_max'])
                ;
                $i += 1;
                $j++;
                $objPHPExcel->getActiveSheet()->getStyle('B' . $i . ':D' . $i)->applyFromArray($estiloTxtInformacion);
                $objPHPExcel->getActiveSheet()->getStyle('E' . $i)->applyFromArray($estiloNumInformacion);
                $objPHPExcel->getActiveSheet()->getStyle('E' . $i . ':J' . $i)->applyFromArray($estiloTxtInformacion);
            }

            for ($i = 'A'; $i <= 'J'; $i++) {
                $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
            }
            // Renombrar Hoja
            $objPHPExcel->getActiveSheet()->setTitle('Tarifario Zona');

            // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
            $objPHPExcel->setActiveSheetIndex(0);

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save(__DIR__ . '/../../util/formatos/lista_tarifario_zona.xlsx');
            return 1;
        }
    }

    public function exportarTarifarioReporte($reportes, $titulo) {
        $estiloTituloReporte = array(
            'font' => array(
                'name' => 'Arial',
                'bold' => true,
                'italic' => false,
                'strike' => false,
                'size' => 10
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'rotation' => 0,
                'wrap' => TRUE
            )
        );

        $estiloTituloColumnas = array(
            'font' => array(
                'name' => 'Arial',
                'bold' => true,
                'size' => 10
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array(
                        'rgb' => '000000'
                    )
                )
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => FALSE
            )
        );

        $estiloTxtInformacion = array(
            'font' => array(
                'name' => 'Arial',
                'size' => 9
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_HAIR,
                    'color' => array(
                        'rgb' => '000000'
                    )
                )
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => FALSE
            )
        );

        $estiloNumInformacion = array(
            'font' => array(
                'name' => 'Arial',
                'size' => 8
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_HAIR,
                    'color' => array(
                        'rgb' => '000000'
                    )
                )
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => FALSE
            )
        );
        $objPHPExcel = new PHPExcel();

        $i = 1;

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':H' . $i);
        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $titulo);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':H' . $i)->applyFromArray($estiloTituloReporte);

        $i += 2;

        $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, 'Agencia Origen');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, 'Agencia Destino');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, 'Persona');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, 'Moneda');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, 'Articulo');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, 'Precio por Kilogramo');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, 'Precio Sobre');
        $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, 'Precio <= 5K');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':H' . $i)->applyFromArray($estiloTituloColumnas);

        $i += 1;

        foreach ($reportes as $reporte) {

            $objPHPExcel->setActiveSheetIndex()->setCellValue('A' . $i, $reporte['agencia_origen']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('B' . $i, $reporte['agencia_destino']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C' . $i, $reporte['nombre']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('D' . $i, $reporte['descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E' . $i, $reporte['bien_descripcion']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('F' . $i, $reporte['precio_xk']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('G' . $i, $reporte['precio_sobre']);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('H' . $i, $reporte['precio_5k']);

            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':H' . $i)->applyFromArray($estiloTituloColumnas);

            $i += 1;
        }

        for ($i = 'A'; $i <= 'H'; $i++) {

            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $x = $i;
        for ($a = 1; $a <= $x; $a++) {
            $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(-1);
        }

        $objPHPExcel->getActiveSheet()->setTitle("Tarifario");

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/reporte.xlsx');

        return 1;
    }

    public function obtenerTarifarioXPersonaDireccionId($personaDireccionId) {
        return Tarifario::create()->obtenerTarifarioXPersonaDireccionId($personaDireccionId);
    }

}
