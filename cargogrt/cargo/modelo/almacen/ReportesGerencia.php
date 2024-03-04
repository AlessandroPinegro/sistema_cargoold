<?php

require_once __DIR__ . '/../core/ModeloBase.php';
require_once __DIR__ . "/../enumeraciones/EstadoGenerico.php";

class ReportesGerencia extends ModeloBase {

     /**
     * 
     * @return ReportesGerencia
     */
    static function create() {
        return parent::create();
    }

    public function obtenerReporteLiquidacionDeNotasDeVenta($fechaInicio = null,$fechaFin = null){
        $this->commandPrepare("sp_reporte_liquidacion_notas_de_venta");
        $this->commandAddParameter(":fecha_inicio", $fechaInicio);
        $this->commandAddParameter(":fecha_fin", $fechaFin);
        return $this->commandGetData();

    }

    public function getDataReportetest01() 
    {
        $this->commandPrepare("sp_caja_getAll");
        return $this->commandGetData();
    }


    public function getDataBuscartestreporte1($bfechaini,$bfechafin)
    {
        $this->commandPrepare("SP_LC_testpruebas");
        $this->commandAddParameter(":fechainicio", $bfechaini);
        $this->commandAddParameter(":fechafin", $bfechafin);
        return $this->commandGetData();
    }    


    //reportes Gerenciales 06092023 LMCC 
    public function getReporteVentasTotalesSolesxFecha($bfechaini,$bfechafin)
    {
            $this->commandPrepare("SP_LC_VentasTotalesSolesCargoXFecha");
            $this->commandAddParameter(":fechainiciorep", $bfechaini);
            $this->commandAddParameter(":fechafinrep", $bfechafin);
            return $this->commandGetData();
    }

    public function getReporteInventarioxFecha($fechaininv,$fechafinnv)
    {
        $this->commandPrepare("Sp_LC_InventarioPaquetesALL");
        $this->commandAddParameter(":fechaini", $fechaininv);
        $this->commandAddParameter(":fechafin", $fechafinnv);
        return $this->commandGetData();
    }

    public function getReporteInventarioDetallexFecha($fechaininv,$fechafinnv)
    {
        $this->commandPrepare("Sp_LC_InventarioDetallePaquetesALL");
        $this->commandAddParameter(":fechaini", $fechaininv);
        $this->commandAddParameter(":fechafin", $fechafinnv);
        return $this->commandGetData();
    }

	public function getReportePagosDetallexFecha($fechaininv,$fechafinnv,$codagencia)
     {
         $this->commandPrepare("Sp_LC_DetallePagosXAgencia");//@codagencia integer=0,@fechainic date,@fechafn 
         $this->commandAddParameter(":codagencia", $codagencia);
         $this->commandAddParameter(":fechainic", $fechaininv);
         $this->commandAddParameter(":fechafn", $fechafinnv);
         return $this->commandGetData();
     }


  public function getReportePedidoRDRDPCE($fechainipedidordpce,$fechafinpedidordpce)
    {
        $this->commandPrepare("SP_Detalle_Pedido_RD_RDCPE");
        $this->commandAddParameter(":fechainicio", $fechainipedidordpce);
        $this->commandAddParameter(":fechafin", $fechafinpedidordpce);
        return $this->commandGetData();
    }

}
