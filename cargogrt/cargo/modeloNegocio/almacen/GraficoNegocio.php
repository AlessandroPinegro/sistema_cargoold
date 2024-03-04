<?php

require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/../../modeloNegocio/commons/ConstantesNegocio.php';
//Librerias para dibujar 
require_once __DIR__ . '/../../util/jpgraph/src/jpgraph.php';
require_once __DIR__ . '/../../util/jpgraph/src/jpgraph_pie.php';
require_once __DIR__ . '/../../util/jpgraph/src/jpgraph_pie3d.php';

class GraficoNegocio extends ModeloNegocioBase {

    /**
     *
     * @return GraficoNegocio
     */
    static function create() {
        return parent::create();
    }

    public function graficarDeudasVencidasxCliente($data) {

        $dataImportes = array();
        $dataNombres = array();

        foreach ($data as $value) {
            array_push($dataNombres, $value['nombre'] . ' (%1.1f%%)');
            array_push($dataImportes, $value['deuda']);
        }

        $graph = new PieGraph(1400, 900, "auto");
        $graph->SetShadow();
        $graph->img->SetAntiAliasing();
        $graph->SetMarginColor('white');
        //$graph->SetShadow(); 
        // Setup margin and titles 
        $graph->title->Set("Porcentaje de deudas vencidas por cliente");
        $graph->title->SetFont(FF_DEFAULT, FS_BOLD, 24);

        $p1 = new PiePlot($dataImportes);
        $p1->SetSize(0.35);
        $p1->SetCenter(0.5);
        $p1->value->SetFont(FF_DEFAULT, FS_NORMAL, 14);

        // Setup slice labels and move them into the plot 
        $p1->value->SetColor("black");
//        $p1->SetLabelPos(1.4); 

        $p1->value->Show();

        $p1->SetLegends($dataNombres);

        $graph->legend->SetColumns(2);
        $graph->legend->SetFont(FF_DEFAULT, FS_BOLD, 12);
        $graph->legend->SetPos(0.5, 0.98, 'center', 'bottom');

        // Explode all slices 
        $p1->ExplodeAll();

        $graph->Add($p1);
        $fileName = __DIR__ . '/../../vistas/images/graficaDeudasVencidasxCliente.png';
        unlink($fileName);
        $graph->Stroke($fileName);

        return $fileName;
    }

    public function graficarDeudasVigentesxCliente($data) {

        $dataImportes = array();
        $dataNombres = array();

        foreach ($data as $value) {
            array_push($dataNombres, $value['nombre'] . ' (%1.1f%%)');
            array_push($dataImportes, $value['deuda']);
        }

        $graph = new PieGraph(1400, 900, "auto");
        $graph->SetShadow();
        $graph->img->SetAntiAliasing();
        $graph->SetMarginColor('white');
        //$graph->SetShadow(); 
        // Setup margin and titles 
        $graph->title->Set("Porcentaje de deudas por cobrar por cliente");
        $graph->title->SetFont(FF_DEFAULT, FS_BOLD, 24);
        $p1 = new PiePlot($dataImportes);
        $p1->SetSize(0.35);
        $p1->SetCenter(0.5);
        $p1->value->SetFont(FF_DEFAULT, FS_NORMAL, 14);

        // Setup slice labels and move them into the plot 
        $p1->value->SetColor("black");
//        $p1->SetLabelPos(1.4); 
        $p1->value->Show();

        $p1->SetLegends($dataNombres);

        $graph->legend->SetColumns(2);
        $graph->legend->SetFont(FF_DEFAULT, FS_BOLD, 12);
        $graph->legend->SetPos(0.5, 0.98, 'center', 'bottom');

        // Explode all slices 
        $p1->ExplodeAll();

        $graph->Add($p1);
        $fileName = __DIR__ . '/../../vistas/images/graficaDeudasVigentesxCliente.png';
        unlink($fileName);
        $graph->Stroke($fileName);

        return $fileName;
    }

}
