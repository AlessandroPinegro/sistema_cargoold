<?php

require_once __DIR__ . '/AlmacenIndexControlador.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/AgenciaNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/ColaboradorNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/PerfilNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/EmpresaNegocio.php';

class AgenciaControlador extends AlmacenIndexControlador
{

    public function getDataGridAgencia()
    {
        return AgenciaNegocio::create()->getDataAgencia();
    }

    public function getDataGridAgenciaZona()
    {
        return AgenciaNegocio::create()->getDataAgenciaZona();
    }

    public function getComboUbigeo()
    {
        return AgenciaNegocio::create()->getComboUbigeo();
    }

    public function getComboPerfil()
    {
        return UsuarioNegocio::create()->getComboPerfil(NULL, NULL);
    }

    public function insertAgencia()
    {
        $codigo = $this->getParametro("age_codigo");
        $descripcion = $this->getParametro("age_descripcion");
        $direccion = $this->getParametro("age_direccion");
        $ubigeo = $this->getParametro("id_ubigeo");
        $estado = $this->getParametro("estado");
        $usuarioCreacion = $this->getUsuarioId();
        $this->setTransaction();

        return AgenciaNegocio::create()->insertAgencia($codigo, $descripcion, $direccion, $ubigeo, $usuarioCreacion, $estado);
    }

    public function insertZona()
    {
        $agenciaId = $this->getParametro("agenciaId");
        $zona_descripcion = $this->getParametro("zona_descripcion");
        $estado = $this->getParametro("estado");
        $id_ubigeo = $this->getParametro("id_ubigeo");
        $zona_creacion = $this->getUsuarioId();

        return AgenciaNegocio::create()->insertAgenciaZona($agenciaId, $zona_descripcion, $zona_creacion, $estado,$id_ubigeo);
    }

    public function getAgencia()
    {
        $id = $this->getParametro("id_agencia");
        return AgenciaNegocio::create()->getAgencia($id);
    }

    public function getZona()
    {
        $id = $this->getParametro("id_zona");
        $usuarioId = $this->getUsuarioId();
        return AgenciaNegocio::create()->getZona($id, $usuarioId);
    }

    public function getDataInicialZona()
    {
        $id = $this->getParametro("id_zona");
        $usuarioId = $this->getUsuarioId();

        $respuesta = new stdClass();
        $respuesta->dataAgencia = AgenciaNegocio::create()->getDataAgencia();
        $respuesta->dataZona = AgenciaNegocio::create()->getZona($id, $usuarioId);

        return $respuesta;
    }

    public function updateAgencia()
    {
        $id = $this->getParametro("id_agencia");
        $age_nombre = $this->getParametro("age_codigo");
        $age_descripcion = $this->getParametro("age_descripcion");
        $age_direccion = $this->getParametro("age_direccion");
        $ubigeoId = $this->getParametro("id_ubigeo");
        $estado = $this->getParametro("estado");
        $organizadorDefectoId = $this->getParametro("organizadorDefectoId");
        $organizadorDefectoRecepcionId = $this->getParametro("organizadorDefectoRecepcionId");
        $usuarioId = $this->getUsuarioId();
        $this->setTransaction();
        return AgenciaNegocio::create()->updateAgencia($id, $age_nombre, $age_descripcion, $age_direccion, $ubigeoId, $estado, $usuarioId, $organizadorDefectoId, $organizadorDefectoRecepcionId);
    }

    public function updateZona()
    {
        $id = $this->getParametro("id_zona");
        $agenciaId = $this->getParametro("agenciaId");
        $zona_descripcion = $this->getParametro("zona_descripcion");
        $estado = $this->getParametro("estado");
         $id_ubigeo = $this->getParametro("id_ubigeo");
        $usuarioId = $this->getUsuarioId();
        $this->setTransaction(TRUE);
        return AgenciaNegocio::create()->updateZona($id, $agenciaId, $zona_descripcion, $usuarioId, $estado,$id_ubigeo);
    }

    public function deleteAgencia()
    {

        $id_agencia = $this->getParametro("id_agencia");
        $nom = $this->getParametro("nom");
        $des = $this->getParametro("des");
        $usuarioId = $this->getUsuarioId();
        //        throw new WarningException("hola");
        return AgenciaNegocio::create()->deleteAgencia($id_agencia, $nom, $des, $usuarioId);
    }

    public function deleteZona()
    {

        $id_zona = $this->getParametro("id_zona");
        $nom = $this->getParametro("zona");
        $usuarioId = $this->getUsuarioId();
        //        throw new WarningException("hola");
        return AgenciaNegocio::create()->deleteZona($id_zona, $nom, $usuarioId);
    }

    public function cambiarEstado()
    {
        $id_estado = $this->getParametro("id_estado");
        $usuarioId = $this->getUsuarioId();
        return AgenciaNegocio::create()->cambiarEstado($id_estado, $usuarioId);
    }

    public function cambiarEstadoZona()
    {
        $id_estado = $this->getParametro("id_estado");
        $usuarioId = $this->getUsuarioId();
        return AgenciaNegocio::create()->cambiarEstadoZona($id_estado, $usuarioId);
    }

    public function colaboradorPorUsuario($id_usuario)
    {
        return UsuarioNegocio::create()->colaboradorPorUsuario($id_usuario);
    }

    public function recuperarContrasena($age_email)
    {
        return UsuarioNegocio::create()->recuperarContrasena($age_email);
    }

    public function obtenerContrasenaActual()
    {
        $usu = $this->getParametro("usuario");
        return UsuarioNegocio::create()->obtenerContrasenaActual($usu);
    }

    public function obtenerPantallaPrincipalUsuario()
    {
        return UsuarioNegocio::create()->obtenerPantallaPrincipalUsuario();
    }

    public function getComboEmpresa()
    {
        $usuarioId = $this->getUsuarioId();
        return EmpresaNegocio::create()->getAllEmpresaByUsuarioId($usuarioId);
    }

    public function obtenerUsuarios()
    {
        return UsuarioNegocio::create()->getDataUsuario();
    }
}
