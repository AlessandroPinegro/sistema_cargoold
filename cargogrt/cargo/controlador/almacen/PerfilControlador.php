<?php

//require_once __DIR__ . '/AlmacenIndexControlador.php';
require_once __DIR__ . '/AlmacenIndexControlador.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/EmpresaNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/PerfilNegocio.php';

class PerfilControlador extends AlmacenIndexControlador {

    
    public function getDataGridPerfil() {
        return PerfilNegocio::create()->getDataPerfilBase();
    }

    public function getMenu() {
        $id_perfil = $this->getParametro("id_perfil");
        return PerfilNegocio::create()->getMenu($id_perfil);
    }

    public function insertPerfil() {
        $nombre = $this->getParametro("descripcion");
        $descripcion = $this->getParametro("comentario");
        $estado = $this->getParametro("estado");
        $dashboard = $this->getParametro("dashboard");
        $email = $this->getParametro("email");
        $monetaria = $this->getParametro("monetaria");
        $usuario = $this->getUsuarioId();
        $pant_principal = $this->getParametro("pant_principal");
        $opcionT = $this->getParametro("opcionT");
        $personaClase = $this->getParametro("personaClase");
        $agenciaId = $this->getParametro("agenciaId");
        $cajaId = $this->getParametro("cajaId");
        $this->setTransaction();
        return PerfilNegocio::create()->insertPerfil($nombre, $descripcion, $estado, $dashboard, $email, $monetaria, $usuario,
                        $pant_principal, $opcionT, $personaClase, $agenciaId, $cajaId);
    }

    public function getPerfil() {
        $usuarioId = $this->getUsuarioId();
        $id_perfil = $this->getParametro("id_perfil");
        return PerfilNegocio::create()->getPerfil($id_perfil, $usuarioId);
    }

    public function updatePerfil() {
        $id_perfil = $this->getParametro("id_perfil");
        $descripcion = $this->getParametro("descripcion");
        $comentario = $this->getParametro("comentario");
        $estado = $this->getParametro("estado");
        $dashboard = $this->getParametro("dashboard");
        $email = $this->getParametro("email");
        $monetaria = $this->getParametro("monetaria");
        $pant_principal = $this->getParametro("pant_principal");
        $usuarioId = $this->getUsuarioId();
        $personaClase = $this->getParametro("personaClase");
        $agenciaId = $this->getParametro("agenciaId");
        $cajaId = $this->getParametro("cajaId");
        $this->setTransaction(TRUE);
        return PerfilNegocio::create()->updatePerfil($id_perfil, $descripcion, $comentario, $estado, $dashboard, $email, $monetaria,
                        $pant_principal, $usuarioId, $personaClase, $agenciaId, $cajaId);
    }

    public function deletePerfil() {

        $id_perfil = $this->getParametro("id_perfil");
        $usurioId = $this->getUsuarioId();
        $nom = $this->getParametro("nom");
        return PerfilNegocio::create()->deletePerfil($id_perfil, $nom, $usurioId);
    }

    public function insertDetOpcPerfil() {
        $id_per = $this->getParametro("id_per");
        $id_usu = $this->getParametro("id_usu");
        $id_opcion = $this->getParametro("id_opcion");
        $estado = $this->getParametro("estado");
        return PerfilNegocio::create()->insertDetOpcPerfil($id_per, $id_opcion, $id_usu, $estado);
    }

    public function updateDetOpcPerfil() {
        $opcionEdicion = $this->getParametro("opcionEdicion");
        $id_per = $this->getParametro("id_per");
//        $id_opcion = $this->getParametro("id_opcion");
//        $estado = $this->getParametro("estado");
        $usuario_creacion = $this->getUsuarioId();

        foreach ($opcionEdicion as $item) {
            $id_opcion = $item['opcionId'];
            $estado = $item['estado'];
            $visualizar = $item['visualizar'];
            $agregar = $item['agregar'];
            $editar = $item['editar'];
            $anular = $item['anular'];
            $eliminar = $item['eliminar'];

            $res = PerfilNegocio::create()->updateDetOpcPerfil($id_per, $id_opcion, $estado, $usuario_creacion
                    , $visualizar
                    , $agregar
                    , $editar
                    , $anular
                    , $eliminar
            );
        }

        return 1;
    }

//    public function obtenerPantallaXToken() {
//        $token = $this->getParametro("token");
//        $id = $this->getUsuarioId();
//        $data= PerfilNegocio::create()->obtenerPantallaXToken($token,$id);
//        return $data;
//    }

    public function obtenerImagenPerfil($id_per, $id_usu) {
        return PerfilNegocio::create()->obtenerImagenPerfil($id_per, $id_usu);
    }

    public function cambiarEstado() {
        $usuario_creacion = $this->getUsuarioId();
        $id = $this->getParametro("id_estado");
        return PerfilNegocio::create()->cambiarEstado($id, $usuario_creacion);
    }

    public function obterTipoMovimiento() {
        return PerfilNegocio::create()->obterTipoMovimiento();
    }

    public function obtenerImagenXUsuario($usuario) {
        return PerfilNegocio::create()->obtenerImagenXUsuario($usuario);
    }

    public function obtenerPersonaClase() {
        return PerfilNegocio::create()->obtenerPersonaClase();
    }

    public function obtenerConfiguracionInicialForm() {
        $empresaId = $this->getParametro("empresaId");
        $perfilId = $this->getParametro("perfilId");
        $usuarioId = $this->getUsuarioId();
        return PerfilNegocio::create()->obtenerConfiguracionInicialForm($empresaId, $perfilId, $usuarioId);
    }

    public function obtenerCajaXAgenciaId() {
        $agenciaId = $this->getParametro("agenciaId");
        return PerfilNegocio::create()->obtenerCajaXAgenciaId($agenciaId);
    }

    public function getComboAgencias() {
        return PerfilNegocio::create()->getComboAgencias();
    }

}
