<?php

require_once __DIR__ . '/AlmacenIndexControlador.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/UsuarioNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/ColaboradorNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/PerfilNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/EmpresaNegocio.php';

class UsuarioControlador extends AlmacenIndexControlador {   
    
    public function autenticarUsuario() {
        $usuario = $this->getParametro("usuario");
        $contrasena = Util::encripta($this->getParametro("contrasena"));
        $tokenUser = $this->getParametro("tokenUser");
        
        return UsuarioNegocio::create()->validateLogin($usuario, $contrasena,"movil",$tokenUser);
    }

    public function getDataGridUsuario() {
        return UsuarioNegocio::create()->getDataUsuario();
    }

    public function getComboColaborador() {
        return UsuarioNegocio::create()->getComboColaborador();
    }

    public function getComboPerfil() {
        return UsuarioNegocio::create()->getComboPerfil(NULL,NULL);
    }

    public function insertUsuario() { 
        $usu_nombre = $this->getParametro("usu_nombre");
        $id_colaborador = $this->getParametro("id_colaborador");
        $id_perfil = $this->getParametro("id_perfil");
        $estado = $this->getParametro("estado");
        $empresa = $this->getParametro("empresa");
        $combo = $this->getParametro("combo");
        $usu_creacion = $this->getUsuarioId();
        $jefeId = $this->getParametro("jefeId");
        $this->setTransaction(TRUE);
        return UsuarioNegocio::create()->insertUsuario($usu_nombre, $id_colaborador, $id_perfil, $usu_creacion,
                $estado, $empresa, $combo,$jefeId);
    }

    public function getUsuario() {
        $id_usuario = $this->getParametro("id_usuario");
        $usuarioId = $this->getUsuarioId();
        return UsuarioNegocio::create()->getUsuario($id_usuario, $usuarioId);
    }

    public function updateUsuario() {
        $id = $usu_nombre = $this->getParametro("id_usuario");
        $usu_nombre = $this->getParametro("usu_nombre");
        $id_colaborador = $this->getParametro("id_colaborador");
        $id_perfil = $this->getParametro("id_perfil");
        $estado = $this->getParametro("estado");
        $empresa = $this->getParametro("empresa");
        $combo = $this->getParametro("combo");
        $usuarioId = $this->getUsuarioId();
        $jefeId = $this->getParametro("jefeId");
        $this->setTransaction(TRUE);
        return UsuarioNegocio::create()->updateUsuario($id, $usu_nombre, $id_colaborador, $id_perfil, $estado,
                $empresa, $combo, $usuarioId,$jefeId);
    }

    public function deleteUsuario() {

        $id_usuario = $this->getParametro("id_usuario");
        $nom = $this->getParametro("nom");
        $usuarioId = $this->getUsuarioId();
//        throw new WarningException("hola");
        return UsuarioNegocio::create()->deleteUsuario($id_usuario, $nom, $usuarioId);
    }

    public function cambiarEstado() {
        $id_estado = $this->getParametro("id_estado");
        $usuarioId = $this->getUsuarioId();
        return UsuarioNegocio::create()->cambiarEstado($id_estado, $usuarioId);
    }

    public function colaboradorPorUsuario($id_usuario) {
        return UsuarioNegocio::create()->colaboradorPorUsuario($id_usuario);
    }

    public function recuperarContrasena($usu_email) {
        return UsuarioNegocio::create()->recuperarContrasena($usu_email);
    }

    public function obtenerContrasenaActual() {
        $usu = $this->getParametro("usuario");
        return UsuarioNegocio::create()->obtenerContrasenaActual($usu);
    }

    public function obtenerPantallaPrincipalUsuario() {
        return UsuarioNegocio::create()->obtenerPantallaPrincipalUsuario();
    }

    public function getComboEmpresa() {
        $usuarioId = $this->getUsuarioId();
        return EmpresaNegocio::create()->getAllEmpresaByUsuarioId($usuarioId);
    }
    
    public function obtenerUsuarios(){        
        return UsuarioNegocio::create()->getDataUsuario();
    }
    public function cerrarSesion(){
        $usuarioId = $this->getUsuarioId();
        return UsuarioNegocio::create()->cerrarSesion($usuarioId);
    }
}
