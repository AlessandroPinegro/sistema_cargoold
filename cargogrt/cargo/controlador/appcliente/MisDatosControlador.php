<?php

require_once __DIR__ . '/../../modeloNegocio/almacen/PersonaNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/UsuarioNegocio.php';
require_once __DIR__ . '/../../util/Configuraciones.php';
require_once __DIR__ . '/../core/ControladorBase.php';

class MisDatosControlador extends ControladorBase
{

    public function insertPersonaClase()
    {
        $descripcion = $this->getParametro("descripcion");
        $tipo = $this->getParametro("tipo");
        $estado = $this->getParametro("estado");
        $usuarioCreacion = $this->getUsuarioId();
        $this->setTransaction();
        return PersonaNegocio::create()->insertPersonaClase($descripcion, $tipo, $estado, $usuarioCreacion);
    }

    public function obtenerPersonaDatos()
    {
        $usuarioId = $this->getUsuarioId();
        return PersonaNegocio::create()->obtenerPorUsuarioId($usuarioId);
    }

    public function cambiarContrasenia()
    {
        $contra_actual = $this->getParametro("contra_actual");
        $contra_nueva = $this->getParametro("contra_nueva");
        $usuario = $this->getUsuarioId();
        $this->setTransaction();
        return UsuarioNegocio::create()->cambiarContrasena($usuario, $contra_actual, $contra_nueva);
    }

    public function updatePersonaDatos()
    {
        $tipoDocumentoId = $this->getParametro("tipoDocumentoId");
        $numeroDocumento = $this->getParametro("numeroDocumento");
        $nombres = $this->getParametro("nombres");
        $apellidoPaterno = $this->getParametro("apellidoPaterno");
        $apellidoMaterno = $this->getParametro("apellidoMaterno");
        $correo = $this->getParametro("correo");
        $correoAlternativo = $this->getParametro("correoAlternativo");
        $usuarioCreacion = $this->getUsuarioId();
        $celular = $this->getParametro("celular");
        $this->setTransaction();
        return PersonaNegocio::create()->updatePersonaDatos(
            $tipoDocumentoId,
            $numeroDocumento,
            $nombres,
            $apellidoPaterno,
            $apellidoMaterno,
            $correo,
            $correoAlternativo,
            $usuarioCreacion,
            $celular
        );
    }

    public function obtenerDireccionTipoDatos()
    {
        return PersonaNegocio::create()->obtenerDireccionTipoActivos();
    }

    public function guardarDireccionDatos()
    {
        $personaDireccionId = $this->getParametro("personaDireccionId");
        $direccionTipoId = $this->getParametro("direccionTipoId");
        $direccion = $this->getParametro("direccion");
        $referencia = $this->getParametro("referencia");
        $latitud = $this->getParametro("latitud");
        $longitud = $this->getParametro("longitud");
        $usuarioCreacion = $this->getUsuarioId();
        $zonaId = $this->getParametro("zonaId");
        $personaId = $this->getParametro("personaId");
        $this->setTransaction();
        return PersonaNegocio::create()->guardarDireccionDatos($personaDireccionId, $direccionTipoId, $direccion, $referencia, $latitud, $longitud, $usuarioCreacion, $zonaId, $personaId);
    }

    public function guardarDireccionFavorito()
    {
        $personaDireccionId = $this->getParametro("personaDireccionId");
        $favorito = $this->getParametro("favorito"); //0 1
        $this->setTransaction();
        return PersonaNegocio::create()->guardarDireccionFavoritoXId($personaDireccionId, $favorito);
    }

    public function obtenerPersonaDocumentoTipo()
    {
        $usuarioCreacion = $this->getUsuarioId();
        return PersonaNegocio::create()->obtenerPersonaDocumentoTipo($usuarioCreacion);
    }

    public function eliminarDireccionDatos()
    {
        $personaDireccionId = $this->getParametro("personaDireccionId");
        //        $usuarioCreacion = $this->getUsuarioId();
        return PersonaNegocio::create()->eliminarPersonaDireccion($personaDireccionId);
    }

    public function registrarPersonaDatos()
    {
        $tipoDocumentoId = $this->getParametro("tipoDocumentoId");
        $numeroDocumento = $this->getParametro("numeroDocumento");
        $nombres = $this->getParametro("nombres");
        $apellidoPaterno = $this->getParametro("apellidoPaterno");
        $apellidoMaterno = $this->getParametro("apellidoMaterno");
        $correo = $this->getParametro("correo");
        $correoAlternativo = $this->getParametro("correoAlternativo");
        $contraseña = $this->getParametro("contraseña");
        $contraseña_confirmar = $this->getParametro("contraseña_confirmar");
        $arrayDireccion = $this->getParametro("arrayDireccion");
        $celular = $this->getParametro("celular");
        $this->setTransaction();
        return PersonaNegocio::create()->registrarPersonaDatos(
            $tipoDocumentoId,
            $numeroDocumento,
            $nombres,
            $apellidoPaterno,
            $apellidoMaterno,
            $correo,
            $correoAlternativo,
            $contraseña,
            $contraseña_confirmar,
            $arrayDireccion,
            $celular
        );
    }

    public function zonaPersonaContacto()
    {
        $ciudad = $this->getParametro("ciudad");
        //        $usuarioCreacion = $this->getUsuarioId();
        return Persona::create()->zonaPersonaContacto($ciudad);
    }

    public function consultarDocumento()
    {
        $tipoDocumento = $this->getParametro("tipoDocumento");
        $numeroDocumento = $this->getParametro("numeroDocumento");
        $usuarioCreacion = $this->getUsuarioId();
        $resultado = PersonaNegocio::create()->consultarDocumento($tipoDocumento, $numeroDocumento, $usuarioCreacion);

        return $resultado;
    }

    public function restablecerContrasenia()
    {
        $correo = $this->getParametro("correo");
        $resultado = PersonaNegocio::create()->restablecerContrasenia($correo);

        return $resultado;
    }

    public function validarCodigo()
    {
        $usuario = $this->getParametro("usuario");
        $codigo = $this->getParametro("codigo");
        $resultado = PersonaNegocio::create()->validarCodigo($usuario, $codigo);

        return $resultado;
    }

    public function actualizarContrasenia()
    {

        $usuario = $this->getParametro("usuario");
        $contra_nueva = $this->getParametro("clave");
        $this->setTransaction();
        return UsuarioNegocio::create()->actualizarContrasenia($usuario, $contra_nueva);
    }
}
