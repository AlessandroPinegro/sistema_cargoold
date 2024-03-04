<?php

require_once __DIR__ . '/../../modelo/almacen/Periodo.php';
require_once __DIR__ . '/../../modelo/almacen/Bien.php';
require_once __DIR__ . '/../../modelo/almacen/Reporte.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/BienPrecioNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/DocumentoNegocio.php';
require_once __DIR__ . '/../../modelo/contabilidad/Tabla.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/../../modeloNegocio/contabilidad/InvPermValorizadoNegocio.php';

class PeriodoNegocio extends ModeloNegocioBase {

    /**
     * 
     * @return PeriodoNegocio
     */
    static function create() {
        return parent::create();
    }

    public function obtenerConfiguracionesIniciales() {
        $resultado = new ObjectUtil();
        $resultado->dataMes = Tabla::create()->obtenerXPadreId(17);
        return $resultado;
    }

    public function guardarPeriodo($anio, $mes, $estadoId, $usuarioId, $empresaId, $periodoId) {
        $respuesta = new ObjectUtil();
        $resPeriodo = Periodo::create()->guardarPeriodo($anio, $mes, $estadoId, $usuarioId, $empresaId, $periodoId);

        $respuesta->resultado = $resPeriodo;
        return $respuesta;
    }

    public function listarPeriodo($empresaId) {
        return Periodo::create()->listarPeriodo($empresaId);
    }

    public function cambiarEstado($id) {
        return Periodo::create()->cambiarEstado($id);
    }

    public function eliminar($id, $nom) {
        $response = Periodo::create()->eliminar($id);
        $response[0]['descripcion'] = $nom;
        return $response;
    }

    public function obtenerPeriodoXid($id) {
        $resultado = new stdClass();
        $resultado->dataPeriodo = Periodo::create()->obtenerPeriodoXid($id);

        return $resultado;
    }

    public function cambiarIndicador($id, $indicador) {
        return Periodo::create()->cambiarIndicador($id, $indicador);
    }

    public function cambiarIndicadorContable($id, $indicador) {
        return Periodo::create()->cambiarIndicadorContable($id, $indicador);
    }

    public function periodoCierreBienGuardar($id, $usuarioId) {
        $dataPeriodo = Periodo::create()->obtenerPeriodoXid($id); //77

        $anio = $dataPeriodo[0]['anio'] * 1; 
        $mes = $dataPeriodo[0]['mes'] * 1;
        $empresaId = $dataPeriodo[0]['empresa_id'];

        //VALIDAR QUE EL MES ANTERIOR ESTE CERRADO
        if ($mes == 1) {
            $mesAnt = 12;
            $anioAnt = $anio - 1;
        } else {
            $mesAnt = $mes - 1;
            $anioAnt = $anio;
        }
        $dataPeriodoAnt = Periodo::create()->obtenerPeriodoXEmpresaXAnioXMes($empresaId, $anioAnt, $mesAnt);

        if (ObjectUtil::isEmpty($dataPeriodoAnt) || $dataPeriodoAnt[0]['indicador'] != 0) {
            throw new WarningException("El periodo anterior no esta cerrado.");
        }

        //REGISTRAR EN periodo_cierre_bien -  COSTO UNITARIO FINAL Y CANTIDAD DE INV. PERM. VAL.
//        $mes=($mes<10)?'0'.$mes:$mes;
//        
//        $inicio = "$anio-$mes-01";
//        $fin = ($mes * 1 == 12) ? ($anio + 1) . "-01-01" : "$anio-" . ($mes + 1) . "-01";
//        $dataCierre=  PeriodoNegocio::create()->obtenerPeriodoCierreEntreFechas($inicio, $fin);
        //SE CAMBIO EL MODO DE OBTENER LOS COSTOS INICIALES, AHORA VA SER COMO EL EXCEL
        //--------------------- INICIO OBTENCION DE DATOS DEL PERIODO --------------------------------- 
        $data = InvPermValorizadoNegocio::create()->obtenerDataExcelPorPeriodo($anio, $mes, $usuarioId);

        //OBTENER BIENES DIFERENTES 
        $bienIdArray = array();
        foreach ($data as $item) {
            array_push($bienIdArray, $item['bien_id']);
        }

        $bienIdArray = array_unique($bienIdArray);

        //PARA OBTENER LOS INDICES CORRELATIVOS EL ARRAY UNIQUE LOS ELIMINO
        $bienIds = array();
        foreach ($bienIdArray as $item) {
            array_push($bienIds, $item);
        }

        //AGRUPO LOS BIENES
        $dataKardexBien = $bienIds;
        foreach ($bienIds as $index => $bienId) {
            $dataKardexBien[$index] = array();
            foreach ($data as $item) {
                if ($bienId == $item['bien_id']) {
                    array_push($dataKardexBien[$index], $item);
                }
            }
        }

        $dataCierre = array();
        foreach ($dataKardexBien as $ind => $itemBien) {
            $contadorItem = count($itemBien);

            array_push($dataCierre, $itemBien[$contadorItem - 1]);
        }
        //------------------------ FIN DATOS DEL PERIODO ---------------------------

        if (!ObjectUtil::isEmpty($dataCierre)) {
            foreach ($dataCierre as $index => $item) {
//                $resPer=  Periodo::create()->periodoCierreBienGuardar($id,$item['bien_id'],$item['unidad_control_id'],2,$item['cantidad_final'],$item['costo_unitario_final'],$usuarioId);
                $costoUnitarioFinal = $item['costo_unitario_final'] * 1;
                if (ObjectUtil::isEmpty($costoUnitarioFinal) || $costoUnitarioFinal == 0) {
                    $costoUnitarioFinal = $item['costo_final'] * 1;
                }
                $resPer = Periodo::create()->periodoCierreBienGuardar($id, $item['bien_id'], -1, 2, $item['cantidad_final'] * 1, $costoUnitarioFinal, $usuarioId);
            }
        }

        return $resPer;
    }

    public function cerrarPeriodo($id, $usuarioId) {
        //ELIMINO PERIODO CIERRE BIEN SI YA SE GENERO ANTERIORMENTE - LOGICAMENTE ESTADO 2
        $resEliminar = PeriodoNegocio::create()->periodoCierreBienEliminarXPeriodoId($id); //ok
        $res = PeriodoNegocio::create()->periodoCierreBienGuardar($id, $usuarioId);
        return PeriodoNegocio::create()->cambiarIndicador($id, 0);
    }

    public function cerrarPeriodoContable($id, $usuarioId) {
        return PeriodoNegocio::create()->cambiarIndicadorContable($id, 0);
    }

    public function cerrarPeriodoReabierto($id, $usuarioId) {
        $respuesta = PeriodoNegocio::create()->cambiarIndicador($id, 0);

        if ($respuesta[0]['vout_exito'] == 0) {
            throw new WarningException($respuesta[0]['vout_mensaje']);
        }

        $dataPeriodos = Periodo::create()->obtenerPeriodosCerradosMayorIgualXPeriodoId($id);

        foreach ($dataPeriodos as $item) {
            //ELIMINO PERIODO CIERRE BIEN SI YA SE GENERO ANTERIORMENTE - LOGICAMENTE ESTADO 2
            $resEliminar = PeriodoNegocio::create()->periodoCierreBienEliminarXPeriodoId($item['id']);

            //GUARDO PERIODO CIERRE BIEN            
            $resNuevo = PeriodoNegocio::create()->periodoCierreBienGuardar($item['id'], $usuarioId);
        }

        return $respuesta;
    }

    public function obtenerPeriodoAbiertoXEmpresa($empresaId) {
        return Periodo::create()->obtenerPeriodoAbiertoXEmpresa($empresaId);
    }

    public function obtenerConfiguracionesInicialesGenerarPeriodoPorAnio() {
        return Periodo::create()->obtenerConfiguracionesInicialesGenerarPeriodoPorAnio();
    }

    public function generarPeriodoAnio($anio, $empresaId, $usuarioId) {
        //MESES A GENERAR
        $contador = 0;
        for ($iMes = 1; $iMes <= 12; $iMes++) {
            $resPeriodo = Periodo::create()->guardarPeriodo($anio, $iMes, 1, $usuarioId, $empresaId, null);

            //PARA CONTAR LOS QUE SE GUARDARON
            if ($resPeriodo[0]['vout_exito'] == 1) {
                $contador++;
            }
        }

        return $contador;
    }

    public function obtenerPeriodoCierreEntreFechas($inicio, $fin) {
        return Periodo::create()->obtenerPeriodoCierreEntreFechas($inicio, $fin);
    }

    public function periodoCierreBienEliminarXPeriodoId($periodoId) {
        return Periodo::create()->periodoCierreBienEliminarXPeriodoId($periodoId);
    }

    public function obtenerPeriodoXEmpresa($empresaId) {
        return Periodo::create()->obtenerPeriodoXEmpresa($empresaId);
    }

    public function obtenerUltimoPeriodoActivoXEmpresa($empresaId) {
        return Periodo::create()->obtenerUltimoPeriodoActivoXEmpresa($empresaId);
    }

    public function actualizarBanderaModificacion($id, $banderaContabilidad) {
        return Periodo::create()->actualizarBanderaModificacion($id, $banderaContabilidad);
    }

    public function obtenerPeriodoIdXFecha($dataPeriodo, $fecha) {
        $periodoId = null;

        if (!ObjectUtil::isEmpty($fecha)) {
            $fechaArray = explode('/', $fecha);

            $d = $fechaArray[0] * 1;
            $m = $fechaArray[1] * 1;
            $y = $fechaArray[2] * 1;

            foreach ($dataPeriodo as $item) {
                if ($item['anio'] * 1 == $y && $item['mes'] * 1 == $m) {
                    $periodoId = $item['id'];
                }
            }
        }

        return $periodoId;
    }
    
    public function obtenerPeriodoIdXFechas($fecha) {
          
            $fechaArray = explode('-', $fecha);

            $y = $fechaArray[0] * 1;
            $m = $fechaArray[1] * 1;
            $d = $fechaArray[2] * 1;
        return Periodo::create()->obtenerPeriodoIdXFecha($m,$y);
    }

}
