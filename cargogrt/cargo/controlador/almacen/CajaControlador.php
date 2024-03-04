<?php

require_once __DIR__ . '/AlmacenIndexControlador.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/CajaNegocio.php';

class CajaControlador extends AlmacenIndexControlador {

    public function getDataGridCaja() {
        return CajaNegocio::create()->getDataCaja();
    }

    public function getComboAgencias() {
        return CajaNegocio::create()->getComboAgencias();
    }

    public function getComboPerfil() {
        return UsuarioNegocio::create()->getComboPerfil(NULL, NULL);
    }

    public function insertCaja() {
        $caja_nombre = $this->getParametro("caja_nombre");
        $caja_descripcion = $this->getParametro("caja_descripcion");
        $caja_direccion = $this->getParametro("caja_direccion");
        $id_agencia = $this->getParametro("id_agencia");
        $banderaVirtual = $this->getParametro("bandera_virtual");

        $estado = $this->getParametro("estado");
        $caja_sufijo = $this->getParametro("caja_sufijo");
        $correlativo_inicio = $this->getParametro("correlativo_inicio");
        $listaCorrelativoDetalle = $this->getParametro("listaCorrelativoDetalle");
        $caja_creacion = $this->getUsuarioId();
        $this->setTransaction();

        return CajaNegocio::create()->insertCaja($caja_nombre, $caja_descripcion, $caja_direccion, $id_agencia, $caja_creacion,
                        $estado, $caja_sufijo, $correlativo_inicio, $listaCorrelativoDetalle,$banderaVirtual);
    }

    public function getCaja() {
        $id_caja = $this->getParametro("id_caja");
        $usuarioId = $this->getUsuarioId();
        return CajaNegocio::create()->getCaja($id_caja, $usuarioId);
    }

    public function updateCaja() {
        $id = $this->getParametro("id_caja");
        $caja_nombre = $this->getParametro("caja_nombre");
        $caja_descripcion = $this->getParametro("caja_descripcion");
        $id_agencia = $this->getParametro("id_agencia");
        $banderaVirtual = $this->getParametro("bandera_virtual");
        $caja_ip = $this->getParametro("caja_ip");

        $estado = $this->getParametro("estado");
        $caja_sufijo = $this->getParametro("caja_sufijo");
        $correlativo_inicio = $this->getParametro("correlativo_inicio");
        $listaCorrelativoDetalle = $this->getParametro("listaCorrelativoDetalle");
        $listaCorrelativoEliminado = $this->getParametro("listaCorrelativoEliminado");

        $usuarioId = $this->getUsuarioId();

        $this->setTransaction(TRUE);
        return CajaNegocio::create()->updateCaja(
                        $id,
                        $caja_nombre,
                        $id_agencia,
                        $caja_descripcion,
                        $estado,
                        $usuarioId,
                        $caja_sufijo,
                        $correlativo_inicio,
                        $listaCorrelativoDetalle,
                        $listaCorrelativoEliminado,
                        $banderaVirtual,
                        $caja_ip
        );
    }

    public function deleteCaja() {

        $id_caja = $this->getParametro("id_caja");
        $nom = $this->getParametro("nom");
        $usuarioId = $this->getUsuarioId();
        //        throw new WarningException("hola");
        return CajaNegocio::create()->deleteCaja($id_caja, $nom, $usuarioId);
    }

    public function cambiarEstado() {
        $id_estado = $this->getParametro("id_estado");
        $usuarioId = $this->getUsuarioId();
        return CajaNegocio::create()->cambiarEstado($id_estado, $usuarioId);
    }

    public function colaboradorPorUsuario($id_usuario) {
        return UsuarioNegocio::create()->colaboradorPorUsuario($id_usuario);
    }

    public function obtenerPantallaPrincipalUsuario() {
        return UsuarioNegocio::create()->obtenerPantallaPrincipalUsuario();
    }

    public function getComboEmpresa() {
        $usuarioId = $this->getUsuarioId();
        return EmpresaNegocio::create()->getAllEmpresaByUsuarioId($usuarioId);
    }

    public function obtenerUsuarios() {
        return UsuarioNegocio::create()->getDataUsuario();
    }

}
