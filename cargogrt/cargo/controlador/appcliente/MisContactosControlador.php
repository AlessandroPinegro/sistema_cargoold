<?php

require_once __DIR__ . '/../../modeloNegocio/almacen/PersonaNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/DocumentoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/UsuarioNegocio.php';
require_once __DIR__ . '/../../util/Configuraciones.php';
require_once __DIR__ . '/../core/ControladorBase.php';

class MisContactosControlador extends ControladorBase {

    public function consultarDocumento() {
        $tipoDocumento = $this->getParametro("tipoDocumento");
        $numeroDocumento = $this->getParametro("numeroDocumento");
        $usuarioCreacion = $this->getUsuarioId();
        $resultado = PersonaNegocio::create()->consultarDocumento($tipoDocumento, $numeroDocumento, $usuarioCreacion);

        return $resultado;
    }

    public function obtenerPersonaContactoXUsuarioId() {
        $usuarioId = $this->getUsuarioId();
        $personaId = $this->getParametro("personaId");
        return PersonaNegocio::create()->obtenerPersonaMisContactoXUsuarioId($usuarioId, $personaId);
    }

    public function obtenerContactoXId() {
        $usuarioId = $this->getUsuarioId();
        $personaId = $this->getParametro("personaId");
        return PersonaNegocio::create()->obtenerContactoXId($usuarioId, $personaId);
    }

    public function eliminarPersonaContacto() {
        $persona_id = $this->getParametro("persona_id");
        $usuarioCreacion = $this->getUsuarioId();
        return PersonaNegocio::create()->eliminarPersonaContacto($persona_id, $usuarioCreacion);
    }

    public function ActivarPersonaContacto() {
        $persona_id = $this->getParametro("persona_id");
        $estado = $this->getParametro("estado");
        $usuarioCreacion = $this->getUsuarioId();
        return PersonaNegocio::create()->ActivarPersonaContacto($persona_id, $estado, $usuarioCreacion);
    }

    public function zonaPersonaContacto() {
        $ciudad = $this->getParametro("ciudad");
        $usuarioCreacion = $this->getUsuarioId();
        return Persona::create()->zonaPersonaContacto($ciudad);
    }

    public function registrarPersonaContacto() {
        $tipoDocumentoId = $this->getParametro("tipoDocumentoId");
        $numeroDocumento = $this->getParametro("numeroDocumento");
        $nombres = $this->getParametro("nombres");
        $apellidoPaterno = $this->getParametro("apellidoPaterno");
        $apellidoMaterno = $this->getParametro("apellidoMaterno");
        $correo = $this->getParametro("correo");
        $correoAlternativo = $this->getParametro("correoAlternativo");
        $celular = $this->getParametro("celular");
        $contactoTipo = $this->getParametro("contactoTipo");
        $arrayDireccion = $this->getParametro("arrayDireccion");
        $usuarioId = $this->getUsuarioId();
        $this->setTransaction();
        return PersonaNegocio::create()->registrarPersonaContactos($tipoDocumentoId, $numeroDocumento,
                        $nombres, $apellidoPaterno, $apellidoMaterno, $correo, $correoAlternativo, $arrayDireccion, $usuarioId,
                        $celular, $contactoTipo);
    }

    public function updatePersonaContacto() {
        $tipoDocumentoId = $this->getParametro("tipoDocumentoId");
        $numeroDocumento = $this->getParametro("numeroDocumento");
        $nombres = $this->getParametro("nombres");
        $apellidoPaterno = $this->getParametro("apellidoPaterno");
        $apellidoMaterno = $this->getParametro("apellidoMaterno");
        $correo = $this->getParametro("correo");
        $correoAlternativo = $this->getParametro("correoAlternativo");
        $celular = $this->getParametro("celular");
        $contactoTipo = $this->getParametro("contactoTipo");
        $arrayDireccion = $this->getParametro("arrayDireccion");
        $usuarioId = $this->getUsuarioId();
        $this->setTransaction();
        return PersonaNegocio::create()->updatePersonaDatosContacto($tipoDocumentoId, $numeroDocumento,
                        $nombres, $apellidoPaterno, $apellidoMaterno, $correo, $correoAlternativo, $arrayDireccion, $usuarioId,
                        $celular, $contactoTipo);
    }

    public function registrarEmpresaContacto() {
        $tipoDocumentoId = $this->getParametro("tipoDocumentoId");
        $numeroDocumento = $this->getParametro("numeroDocumento");
        $razonSocial = $this->getParametro("razonSocial");
        $direccionFiscal = $this->getParametro("direccionFiscal");
        $nombreContacto = $this->getParametro("nombreContacto");
        $correo = $this->getParametro("correo");
        $celular = $this->getParametro("celular");
        $contactoTipo = $this->getParametro("contactoTipo");
        $arrayDireccion = $this->getParametro("arrayDireccion");
        $usuarioId = $this->getUsuarioId();
        $this->setTransaction();
        return PersonaNegocio::create()->registrarEmpresaContactos($tipoDocumentoId, $numeroDocumento,
                        $razonSocial, $direccionFiscal, $nombreContacto, $correo, $arrayDireccion, $usuarioId,
                        $celular, $contactoTipo);
    }

    public function updateEmpresaContacto() {
        $tipoDocumentoId = $this->getParametro("tipoDocumentoId");
        $numeroDocumento = $this->getParametro("numeroDocumento");
        $razonSocial = $this->getParametro("razonSocial");
        $direccionFiscal = $this->getParametro("direccionFiscal");
        $nombreContacto = $this->getParametro("nombreContacto");
        $correo = $this->getParametro("correo");
        $celular = $this->getParametro("celular");
        $contactoTipo = $this->getParametro("contactoTipo");
        $arrayDireccion = $this->getParametro("arrayDireccion");
        $usuarioId = $this->getUsuarioId();
        $this->setTransaction();
        return PersonaNegocio::create()->updateEmpresaDatosContacto($tipoDocumentoId, $numeroDocumento,
                        $razonSocial, $direccionFiscal, $nombreContacto, $correo, $arrayDireccion, $usuarioId,
                        $celular, $contactoTipo);
    }

    public function registrarContactos() {
        $personaId = $this->getParametro("personaId");
        $tipoDocumentoId = $this->getParametro("tipoDocumentoId");
        $numeroDocumento = $this->getParametro("numeroDocumento");
        $nombres = $this->getParametro("nombres");    // nombre , razon social
        $apellidoPaterno = $this->getParametro("apellidoPaterno");
        $apellidoMaterno = $this->getParametro("apellidoMaterno");
        $nombreContacto = $this->getParametro("nombreContacto");
        $direccionFiscal = $this->getParametro("direccionFiscal");
        $correo = $this->getParametro("correo");
        $correoAlternativo = $this->getParametro("correoAlternativo");
        $celular = $this->getParametro("celular");
        $contactoTipo = $this->getParametro("contactoTipo");
        $direcciones = $this->getParametro("direcciones");
        $direccionesEliminadas = $this->getParametro("direccionesEliminadas");
        $usuarioId = $this->getUsuarioId();
        $this->setTransaction();
        return PersonaNegocio::create()->registrarContactos($tipoDocumentoId, $numeroDocumento,
                        $nombres, $direccionFiscal, $nombreContacto, $correo, $direcciones, $usuarioId,
                        $celular, $contactoTipo, $personaId, $apellidoPaterno, $apellidoMaterno, $correoAlternativo,
                        $direccionesEliminadas);
    }

}
