<?php

/*
 * @author Geancarlo Leiva <gleiva@imaginatecperu.com>
 * @version 1.0
 * @copyright (c) 2015, IMAGINA TECHNOLOGIES S.A.C.
 * @abstract Clase donde se implementará el Componente
 */

//require_once __DIR__ . '/AlmacenIndexControlador.php';
require_once __DIR__ . '/AlmacenIndexControlador.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/ColaboradorNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/EmpresaNegocio.php';

class ColaboradorControlador extends AlmacenIndexControlador {

    public function getDataGridColaborador() {
        return ColaboradorNegocio::create()->getDataColaborador();
    }

    public function insertColaborador() {
        $dni = $this->getParametro("dni");
        $nombre = $this->getParametro("nombre");
        $paterno = $this->getParametro("paterno");
        $materno = $this->getParametro("materno");
        $telefono = $this->getParametro("telefono");
        $celular = $this->getParametro("celular");
        $email = $this->getParametro("email");
        $direccion = $this->getParametro("direccion");
        $ref_direccion = $this->getParametro("ref_direccion");
        $usuario = $this->getUsuarioId();
        $estado = $this->getParametro("estado");
        $file = $this->getParametro("file");
        $empresa = $this->getParametro("empresa");
        return ColaboradorNegocio::create()->insertColaborador($dni, $nombre, $paterno, $materno, $telefono, $celular, $email, $direccion, $ref_direccion, $usuario, $estado,$file,$empresa);
    }

    public function getDetalleColaborador() {
        $id_colaborador = $this->getParametro("id_colaborador");
        return ColaboradorNegocio::create()->getDetalleColaborador($id_colaborador);
    }
    public function getColaborador() {
        $id_colaborador = $this->getParametro("id_colaborador");
        return ColaboradorNegocio::create()->getColaborador($id_colaborador);
    }
    
    public function updateColaborador() {
        $id = $this->getParametro("id_colaborador");
        $dni = $this->getParametro("dni");
        $nombre = $this->getParametro("nombre");
        $paterno = $this->getParametro("paterno");
        $materno = $this->getParametro("materno");
        $telefono = $this->getParametro("telefono");
        $celular = $this->getParametro("celular");
        $email = $this->getParametro("email");
        $direccion = $this->getParametro("direccion");
        $ref_direccion = $this->getParametro("ref_direccion");
        $estado = $this->getParametro("estado");
        $file = $this->getParametro("file");
        $empresa = $this->getParametro("empresa");
        return ColaboradorNegocio::create()->updateColaborador($id,$dni, $nombre, $paterno, $materno, $telefono, $celular, $email, $direccion, $ref_direccion, $estado,$file,$empresa);
    }
    
    public function deleteColaborador() {
        $id_colaborador = $this->getParametro("id_colaborador");
         $nom = $this->getParametro("nom");
        return ColaboradorNegocio::create()->deleteColaborador($id_colaborador,$nom);
    }
    public function cambiarEstado() {
        $id_estado = $this->getParametro("id_estado");
        return ColaboradorNegocio::create()->cambiarEstado($id_estado);
    }
    
    public function getAllEmpresa() {
        $usuarioId = $this->getUsuarioId();
        return EmpresaNegocio::create()->getAllEmpresaByUsuarioId($usuarioId);
    }
}
