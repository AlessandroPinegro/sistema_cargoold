<?php

if (!isset($_SESSION)) {
    session_start();
}
require_once __DIR__ . '/../../modelo/itec/Usuario.php';
require_once __DIR__ . '/../../modelo/almacen/Empresa.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';

class EmpresaNegocio extends ModeloNegocioBase
{

    /**
     * 
     * @return EmpresaNegocio
     */

    static function create()
    {
        return parent::create();
    }

    public function getEmpresaActivas()
    {
        return Empresa::create()->getDataEmpresaTotal();
    }

    public function getAllEmpresaByUsuarioId($usuarioId)
    {
        $data = Empresa::create()->getAllEmpresaByUsuarioId($usuarioId);
        //        $tamanio = count($data);
        //        $j = 0;
        //        for ($i = 0; $i < $tamanio; $i++) {
        //            $data[$i]['id_bandera'] = $tipo_accion;
        //            if ($data[$i]['estado'] == 1) {
        //
        //                $data[$i]['cantidad'] = $tamanio;
        //                $j++;
        //            }
        //        }
        //        $data[0]['llenar'] = $j;
        //        $data[0]['id_bandera'] = $tipo_accion;
        return $data;
    }

    public function getDataEmpresaPorColaborador($id_bandera, $tipo_accion)
    {
        $id_usu_ensesion = $_SESSION['id_usuario'];
        $data = Empresa::create()->getDataEmpresaPorColaborador($id_bandera, $id_usu_ensesion);
        $tamanio = count($data);
        $j = 0;
        for ($i = 0; $i < $tamanio; $i++) {
            $data[$i]['id_bandera'] = $tipo_accion;
            if ($data[$i]['estado'] == 1) {

                $data[$i]['cantidad'] = $tamanio;
                $j++;
            }
        }
        $data[0]['llenar'] = $j;
        $data[0]['id_bandera'] = $tipo_accion;
        return $data;
    }

    public function getDataEmpresaPerfil($id_bandera)
    {
        $data = Empresa::create()->getDataEmpresaPerfil($id_bandera);
        $tamanio = count($data);
        $j = 0;
        for ($i = 0; $i < $tamanio; $i++) {
            $data[$i]['id_bandera'] = $id_bandera;
            $data[$i]['cantidad'] = $tamanio;
            $j++;
        }
        $data[0]['llenar'] = $j;
        $data[0]['id_bandera'] = $id_bandera;
        return $data;
    }

    public function getDataEmpresaPersona($id_bandera, $id_usuario, $tipo_accion)
    {
        $data = Empresa::create()->getDataEmpresaPersona($id_bandera);
        $data_usuario_empresa = Empresa::create()->getDataEmpresaUsuarioPorId($id_usuario);
        $tamanio = count($data);
        $j = 0;
        for ($i = 0; $i < $tamanio; $i++) {
            $data[$i]['id_bandera'] = $id_bandera;
            $data[$i]['cantidad'] = $tamanio;
            $data[$i]['estado_usuario_empresa'] = $data_usuario_empresa[$i]['estado'];
            $j++;
        }
        $data[0]['llenar'] = $j;
        $data[0]['id_bandera'] = $id_bandera;
        return $data;
    }

    public function getDataEmpresaUsuario($id_bandera)
    {
        $data = Empresa::create()->getDataEmpresaUsuario($id_bandera);
        $tamanio = count($data);
        $j = 0;
        for ($i = 0; $i < $tamanio; $i++) {
            $data[$i]['id_bandera'] = $id_bandera;
            $data[$i]['cantidad'] = $tamanio;
            $j++;
        }
        $data[0]['llenar'] = $j;
        $data[0]['id_bandera'] = $id_bandera;
        return $data;
    }

    public function getDataEmpresaBien($id_bandera)
    {
        $data = Empresa::create()->getDataEmpresaBien($id_bandera);
        $tamanio = count($data);
        $j = 0;
        for ($i = 0; $i < $tamanio; $i++) {
            $data[$i]['id_bandera'] = $id_bandera;
            $data[$i]['cantidad'] = $tamanio;
            $j++;
        }
        $data[0]['llenar'] = $j;
        $data[0]['id_bandera'] = $id_bandera;
        return $data;
    }

    public function getDataEmpresaOrganizador($id_bandera)
    {
        $data = Empresa::create()->getDataEmpresaOrganizador($id_bandera);
        $tamanio = count($data);
        $j = 0;
        for ($i = 0; $i < $tamanio; $i++) {
            $data[$i]['id_bandera'] = $id_bandera;
            $data[$i]['cantidad'] = $tamanio;
            $j++;
        }
        $data[0]['llenar'] = $j;
        $data[0]['id_bandera'] = $id_bandera;
        return $data;
    }

    public function getDataEmpresaBienTipo($id_bandera)
    {
        $id_usu_ensesion = $_SESSION['id_usuario'];
        $data = Empresa::create()->getDataEmpresaBienTipo($id_bandera, $id_usu_ensesion);
        //if(!ObjectUtil::isEmpty($data) && )
        $tamanio = count($data);
        $j = 0;
        for ($i = 0; $i < $tamanio; $i++) {
            $data[$i]['id_bandera'] = $id_bandera;
            $data[$i]['cantidad'] = $tamanio;
            $j++;
        }
        $data[0]['llenar'] = $j;
        $data[0]['id_bandera'] = $id_bandera;
        return $data;
    }

    public function getDataEmpresaUbicacionTipo($id_bandera)
    {
        $id_usu_ensesion = $_SESSION['id_usuario'];
        $data = Empresa::create()->getDataEmpresaUbicacionTipo($id_bandera, $id_usu_ensesion);
        if (!ObjectUtil::isEmpty($data) && is_array($data)) {
            $tamanio = count($data);
            $j = 0;
            for ($i = 0; $i < $tamanio; $i++) {
                $data[$i]['id_bandera'] = $id_bandera;
                $data[$i]['cantidad'] = $tamanio;
                $j++;
            }
            $data[0]['llenar'] = $j;
            $data[0]['id_bandera'] = $id_bandera;
        }
        return $data;
    }

    public function getDataEmpresaAlmacenTipo($id_bandera)
    {
        $id_usu_ensesion = $_SESSION['id_usuario'];
        $data = Empresa::create()->getDataEmpresaAlmacenTipo($id_bandera, $id_usu_ensesion);
        $tamanio = count($data);
        $j = 0;
        for ($i = 0; $i < $tamanio; $i++) {
            $data[$i]['id_bandera'] = $id_bandera;
            $data[$i]['cantidad'] = $tamanio;
            $j++;
        }
        $data[0]['llenar'] = $j;
        $data[0]['id_bandera'] = $id_bandera;
        return $data;
    }

    public function getDataEmpresaServicio($id_bandera)
    {
        $id_usu_ensesion = $_SESSION['id_usuario'];
        $data = Empresa::create()->getDataEmpresaServicio($id_bandera, $id_usu_ensesion);
        $tamanio = count($data);
        $j = 0;
        for ($i = 0; $i < $tamanio; $i++) {
            $data[$i]['id_bandera'] = $id_bandera;
            $data[$i]['cantidad'] = $tamanio;
            $j++;
        }
        $data[0]['llenar'] = $j;
        $data[0]['id_bandera'] = $id_bandera;
        return $data;
    }

    public function obtenerXUsuarioId($usuarioId)
    {
        return Empresa::create()->obtenerXUsuarioId($usuarioId);
    }

    public function obtenerEmpresaXId($empresaId)
    {
        return Empresa::create()->obtenerEmpresaXId($empresaId);
    }

    public function obtenerEmpresaXDocumentoId($documentoId)
    {
        return Empresa::create()->obtenerEmpresaXDocumentoId($documentoId);
    }
}
