<?php

require_once __DIR__ . '/../../modeloNegocio/almacen/MovimientoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/PersonaNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/UsuarioNegocio.php';
require_once __DIR__ . '/../../util/Configuraciones.php';
require_once __DIR__ . '/../core/ControladorBase.php';

class ConsultarPaqueteControlador extends ControladorBase
{

    public function consultarQRpaquete()
    {
        $textoQR = $this->getParametro("textoQR");
        $usuarioCreacion = $this->getUsuarioId();
        $this->setTransaction();
        $resultado = MovimientoNegocio::create()->consultarQRPaquete($textoQR, $usuarioCreacion);

        return $resultado;
    }

    public function consultarCodigopaquete()
    {
        $codigo = $this->getParametro("codigo");
        $usuarioCreacion = $this->getUsuarioId();
        $this->setTransaction();
        $resultado = MovimientoNegocio::create()->consultarCodigopaquete($codigo, $usuarioCreacion);
        return $resultado;
    }
}
