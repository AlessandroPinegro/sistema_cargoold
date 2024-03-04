<?php
require_once __DIR__ . '/../../modelo/almacen/Reporte.php';
require_once __DIR__ . '/../../modelo/almacen/Widgets.php';
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
require_once __DIR__ . '/../../util/PHPExcel/PHPExcel.php';

class WidgetsNegocio extends ModeloNegocioBase {

    static function create() {
        return parent::create();
    }
      public function obtenerBienesComprometidosXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start) {

        $estadoId = $criterios[0]['estado'];
        
        $empresaId = $criterios[0]['empresaId'];

        $columnaOrdenarIndice = $order[0]['column'];
        
        $formaOrdenar = $order[0]['dir'];

        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];

        return Widgets::create()->obtenerBienesComprometidosXCriterios($estadoId,$empresaId, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start);
    }

    public function obtenerCantidadBienesComprometidosXCriterio($criterios, $elemntosFiltrados, $columns, $order, $start) {
        
        $estadoId = $criterios[0]['estado'];

        $empresaId = $criterios[0]['empresaId'];
        
        $columnaOrdenarIndice = $order[0]['column'];
        
        $formaOrdenar = $order[0]['dir'];

        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];
        return Widgets::create()->obtenerCantidadBienesComprometidosXCriterio($estadoId, $empresaId, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start);
    }
    
    public function obtenerCantidadBienesComprometidos($criterios) {
        $estadoId = $criterios[0]['estado'];

        $empresaId = $criterios[0]['empresaId'];

        $respuesta = new ObjectUtil();

        $cantidad = Widgets::create()->obtenerCantidadBienesComprometidos($estadoId,$empresaId);

        $respuesta = $cantidad[0]['total'];
        
        return $respuesta;
    }
    
    //Ranking bienes comprometidos
    public function obtenerRankingDistribucionXCriterios($empresa, $elemntosFiltrados, $columns, $order, $start)
    {
        $columnaOrdenarIndice = $order[0]['column'];
        
        $formaOrdenar = $order[0]['dir'];

        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];
        
        return Widgets::create()->obtenerRankingDistribucionXCriterios($empresa, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start);
    }  
    
        
    
    public function obtenerCantidadRankingDistribucionXCriterio($empresa, $elemntosFiltrados, $columns, $order, $start) {
       
        $columnaOrdenarIndice = $order[0]['column'];
        
        $formaOrdenar = $order[0]['dir'];

        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];
        
        return Widgets::create()->obtenerCantidadRankingDistribucionXCriterios($empresa,$columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start);
    }
    
    public function CantidadTotalRankingDistribucion($empresaId)
    {
        $cantidad = Widgets::create()->CantidadTotalRankingDistribucion($empresaId);

        $respuesta = $cantidad[0]['total'];
        
        return $respuesta;
    }
}

