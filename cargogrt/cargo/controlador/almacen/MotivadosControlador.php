<?php

require_once __DIR__ . '/AlmacenIndexControlador.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/MotivadoNegocio.php';

class MotivadosControlador extends AlmacenIndexControlador {

  
  public function obtenerConfiguracionesIniciales() {
      $idEmpresa = $this->getParametro("id_empresa");
      return MotivadoNegocio::create()->obtenerConfiguracionInicial($idEmpresa);
  }

  public function obtenerDataMotivado() {                   
    $resultado= MotivadoNegocio::create()->obtenerDataMotivado();     
   return $resultado;
  }

  public function insertMotivado() { 
    $motivo = $this->getParametro("motivo");
    $descripcion = $this->getParametro("descripcion");
    $ejemplo = $this->getParametro("ejemplo");          
    $usu_creacion = $this->getUsuarioId();        
    return MotivadoNegocio::create()->insertMotivado($motivo,$descripcion, $ejemplo,$usu_creacion);
  }

  public function getMotivado() {
    $id_motivado = $this->getParametro("id_motivado");
    $usuarioId = $this->getUsuarioId();
    return MotivadoNegocio::create()->getMotivado($id_motivado, $usuarioId);
  }

  public function updateMotivado() {
    $id =  $this->getParametro("id");
    $motivo = $this->getParametro("motivo");
    $descripcion = $this->getParametro("descripcion");
    $ejemplo = $this->getParametro("ejemplo");
    $usuarioId = $this->getUsuarioId();

    $this->setTransaction(TRUE);
    return MotivadoNegocio::create()->updateMotivado($id, $motivo, $descripcion, $ejemplo, $usuarioId);
}

  public function deleteMotivado() {

    $id = $this->getParametro("id");
    $nom = $this->getParametro("nom");
    $usuarioId = $this->getUsuarioId();  
    return MotivadoNegocio::create()->deleteMotivado($id, $nom, $usuarioId);
  }
}