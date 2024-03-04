<?php

/*
 * @author Geancarlo Leiva <gleiva@imaginatecperu.com>
 * @version 1.0
 * @copyright (c) 2015, IMAGINA TECHNOLOGIES S.A.C.
 * @abstract Clase donde se implementará el Componente
 */
require_once __DIR__ . '/../../modeloNegocio/almacen/UsuarioNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/PerfilNegocio.php';
require_once __DIR__ . '/../../util/Configuraciones.php';
require_once __DIR__ . '/../core/ControladorBase.php';

class AlmacenIndexControlador extends ControladorBase{
//    public function getMenu() {
//        $usuario_id = $this->getParametro("usuario_id");
//        $opciones = PerfilNegocio::create()->getMenuPadreUsuario($usuario_id);
//        return $this->formatOpciones($opciones);
//    }
    
    public function obtenerPantallaPrincipal() {
        $id = $this->getUsuarioId();
        return PerfilNegocio::create()->obtenerPantallaPrincipal($id);
    }
    
    public function obtenerMenuXEmpresa() {
        $idEmpresa = $this->getParametro("id_empresa");
        $id = $this->getUsuarioId();
        return PerfilNegocio::create()->obtenerMenuXEmpresa($idEmpresa, $id);
    }
    
    public function ObtenerEmpresasXUsuarioId() {
        $id = $this->getUsuarioId();
        return PerfilNegocio::create()->ObtenerEmpresasXUsuarioId($id);
    }
    
    public function cambiarContrasena() {
//        $usuario = $this->getParametro("usuario");
        $usuario = $this->getUsuarioId();
        $contra_actual = $this->getParametro("contra_actual");
        $contra_nueva = $this->getParametro("contra_nueva");
        return UsuarioNegocio::create()->cambiarContrasena($usuario, $contra_actual, $contra_nueva);
    }
    
    public function obtenerPantallaXToken() {
        $token = $this->getParametro("token");
        $id = $this->getUsuarioId();
        $data= PerfilNegocio::create()->obtenerPantallaXToken($token,$id);
        return $data;
    }
}
