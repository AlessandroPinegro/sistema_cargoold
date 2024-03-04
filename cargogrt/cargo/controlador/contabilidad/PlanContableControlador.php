<?php

require_once __DIR__ . '/../../modeloNegocio/contabilidad/PlanContableNegocio.php';
require_once __DIR__ . '/../core/ControladorBase.php';

class PlanContableControlador extends ControladorBase {

    public function listarPlanContablePadres() {
        $empresaId = $this->getParametro("empresaId");   
        return PlanContableNegocio::create()->listarPlanContablePadres($empresaId);
    }          
    
    public function obtenerHijos() {        
        $padreId = $this->getParametro("padreId");                        
        return PlanContableNegocio::create()->obtenerHijos($padreId);
    }   
    
    public function obtenerConfiguracionesIniciales() {
        $empresaId = $this->getParametro("empresaId"); 
        return PlanContableNegocio::create()->obtenerConfiguracionesIniciales($empresaId);        
    }
    
    public function obtenerCuentaEdicion() {
        $id = $this->getParametro("id");                        
        return PlanContableNegocio::create()->obtenerCuentaXId($id);        
    }
    
    public function guardarCuenta() {
        $this->setTransaction();
        //variables
        $codigo = $this->getParametro("codigo");
        $descripcion = $this->getParametro("descripcion");
        $codigoEqui = $this->getParametro("codigoEqui");
        $descripcionEqui = $this->getParametro("descripcionEqui");
        $estado = $this->getParametro("estado");
        $cuentaTipo = $this->getParametro("cuentaTipo");
        $moneda = $this->getParametro("moneda");
        $naturalezaCuenta = $this->getParametro("naturalezaCuenta");
        $cuentaCargo = $this->getParametro("cuentaCargo");
        $cuentaAbono = $this->getParametro("cuentaAbono");
        $comoAjustar = $this->getParametro("comoAjustar");
        $tipoCambio = $this->getParametro("tipoCambio");
        $dimension = $this->getParametro("dimension");
        $cuentaExige = $this->getParametro("cuentaExige");
        $checkTitulo = $this->getParametro("checkTitulo");
        $checkAjustar = $this->getParametro("checkAjustar");
        
        //ids
        $usuarioId=$this->getUsuarioId();
        $cuentaId = $this->getParametro("cuentaId");
        $padreCuentaId = $this->getParametro("padreCuentaId");
        $empresaId = $this->getParametro("empresaId");
        
        return PlanContableNegocio::create()->guardarCuenta($codigo,$descripcion,$codigoEqui,$descripcionEqui,
                $estado,$cuentaTipo,$moneda,$naturalezaCuenta,$cuentaCargo,$cuentaAbono,$comoAjustar,$tipoCambio,
                $dimension,$cuentaExige,$checkTitulo,$checkAjustar,$usuarioId,$cuentaId,$padreCuentaId,$empresaId);
        
    }
    
    public function eliminarCuenta() {
        $id = $this->getParametro("id");                        
        return PlanContableNegocio::create()->eliminarCuenta($id);        
    }
    
    public function exportarPlanContable() {
        $tipo = $this->getParametro("tipo");
        $empresaId = $this->getParametro("empresaId"); 
        $periodo = $this->getParametro("periodo");
        if ($tipo == 'excel') {
            return PlanContableNegocio::create()->obtenerPlanContableExcel();
        } elseif($tipo == 'txt') {
            return PlanContableNegocio::create()->obtenerPlanContableTxt($empresaId,$periodo);
        }
    }

}