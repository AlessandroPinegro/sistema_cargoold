<?php

require_once __DIR__ . '/../../modelo/almacen/Organizador.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';

class OrganizadorNegocio extends ModeloNegocioBase {

    CONST ORGANIZADOR_TIPO_POS_ID = 12;

    /**
     * 
     * @return OrganizadorNegocio
     */
    static function create() {
        return parent::create();
    }

    public function getDataOrganizadorTipo($id_bandera) {
        $data = Organizador::create()->getDataOrganizadorTipo();
        $tamanio = count($data);
        for ($i = 0; $i < $tamanio; $i++) {
            if ($data[$i]['estado'] == 1) {
                $data[$i]['icono'] = "ion-checkmark-circled";
                $data[$i]['color'] = "#5cb85c";
            } else {
                $data[$i]['icono'] = "ion-flash-off";
                $data[$i]['color'] = "#cb2a2a";
            }
            if (id_usuario != null) {
                $data[$i]['id_bandera'] = $id_bandera;
            }
        }
        return $data;
    }

    public function insertOrganizadorTipo($descripcion, $codigo, $comentario, $estado, $usu_creacion, $empresa, $combo) {
        $response = Organizador::create()->insertOrganizadorTipo($descripcion, $codigo, $comentario, $estado, $usu_creacion);
        if ($response[0]["vout_exito"] == 0) {
            return $response;
        } else {
            return $response;
        }
    }

    public function getOrganizadorTipo($id) {
        return Organizador::create()->getOrganizadorTipo($id);
    }

    public function updateOrganizadorTipo($id_alm_tipo, $descripcion, $codigo, $comentario, $estado, $empresa, $combo) {
        $response = Organizador::create()->updateOrganizadorTipo($id_alm_tipo, $descripcion, $codigo, $comentario, $estado);
        if ($response[0]["vout_exito"] == 0) {
            return $response;
        } else {
            return $response;
        }
    }

    public function deleteOrganizadorTipo($organizadorId) {
        $response = Organizador::create()->deleteOrganizadorTipo($organizadorId);
        return $response;
    }

    public function getDataComboOrganizadorTipo() {
        return Organizador::create()->getDataComboOrganizadorTipo();
    }

    public function cambiarTipoEstado($id_estado) {
        $data = Organizador::create()->cambiarTipoEstado($id_estado);
        $tamanio = count($data);
        for ($i = 0; $i < $tamanio; $i++) {
            if ($data[$i]['estado_nuevo'] == 1) {
                $data[$i]['icono'] = "ion-checkmark-circled";
                $data[$i]['color'] = "#5cb85c";
            } else {
                $data[$i]['icono'] = "ion-flash-off";
                $data[$i]['color'] = "#cb2a2a";
            }
        }
        return $data;
    }

    //////////////////////////////////////
    //organizadores
    /////////////////////////////////////
    public function getDataOrganizador($id_bandera = null) {
        $data = Organizador::create()->getDataOrganizador();
        $tamanio = count($data);
        for ($i = 0; $i < $tamanio; $i++) {
            if ($data[$i]['estado'] == 1) {
                $data[$i]['icono'] = "ion-checkmark-circled";
                $data[$i]['color'] = "#5cb85c";
            } else {
                $data[$i]['icono'] = "ion-flash-off";
                $data[$i]['color'] = "#cb2a2a";
            }
            if ($id_bandera != null) {
                $data[$i]['id_bandera'] = $id_bandera;
            }
        }
        return $data;
    }

    public function insertOrganizador($descripcion, $codigo, $padre, $tipo, $estado, $usu_creacion, $comentario, $empresa, $agencia) {

        $response = Organizador::create()->insertOrganizador($descripcion, $codigo, $padre, $tipo, $estado, $usu_creacion, $comentario, $agencia);
        if ($response[0]["vout_exito"] == 0) {
            return $response;
        } else {
            $id_p = $response[0]['id'];

            Organizador::create()->insertOrganizadorEmpresa($id_p, $empresa, $estado, $usu_creacion);

            return $response;
        }
    }

    public function getOrganizador($id) {
        return Organizador::create()->getOrganizador($id);
    }

    public function updateOrganizador($id_alm, $descripcion, $codigo, $padre, $tipo, $estado, $comentario, $empresa, $agencia) {
        $response = Organizador::create()->updateOrganizador($id_alm, $descripcion, $codigo, $padre, $tipo, $estado, $comentario, $agencia);
        if ($response[0]["vout_exito"] == 0) {
            return $response;
        } else {
            Organizador::create()->updateOrganizadorEmpresa($id_alm, $empresa, $estado);

            return $response;
        }
    }

    public function deleteOrganizador($organizadorId) {
        $response = Organizador::create()->deleteOrganizador($organizadorId);
        return $response;
    }

    // get organizador padre por tipo
    public function getOrganizadorXTipo($tipo) {
        $response = Organizador::create()->getOrganizadorXTipo($tipo);
        return $response;
    }

    public function cambiarEstado($id_estado) {
        $data = Organizador::create()->cambiarEstado($id_estado);
        if ($data[0]['vout_exito'] == 0) {
            throw new WarningException($data[0]["vout_mensaje"]);
        }
        $tamanio = count($data);
        for ($i = 0; $i < $tamanio; $i++) {
            if ($data[$i]['estado_nuevo'] == 1) {
                $data[$i]['icono'] = "ion-checkmark-circled";
                $data[$i]['color'] = "#5cb85c";
            } else {
                $data[$i]['icono'] = "ion-flash-off";
                $data[$i]['color'] = "#cb2a2a";
            }
        }
        return $data;
    }

    public function obtenerOrganizadorActivo($id) {
        return Organizador::create()->obtenerOrganizadorActivo($id);
    }

    public function obtenerOrganizadorActivoXAgenciaIdXOrganizadorTipoId($agenciaId, $tipo) {
        return Organizador::create()->obtenerOrganizadorActivoXAgenciaIdXOrganizadorTipoId($agenciaId, $tipo);
    }

    public function organizadorEsPadre($id, $nombre) {

        $response = Organizador::create()->organizadorEsPadre($id);
        $response[0]['nombre'] = $nombre;
        $response[0]['id'] = $id;
        return $response;
    }

    public function obtenerXMovimientoTipo($movimientoTipoId) {
        return Organizador::create()->obtenerXMovimientoTipo($movimientoTipoId);
    }

    public function obtenerXMovimientoTipo2($movimientoTipoId, $organizadoresIds, $comodinMostrar = null) {
        return Organizador::create()->obtenerXMovimientoTipo2($movimientoTipoId, $organizadoresIds, $comodinMostrar);
    }

    public function obtenerOrganizadorActivoXEmpresa($idEmpresa) {
        return Organizador::create()->obtenerOrganizadorActivoXEmpresa($idEmpresa);
    }

    public function obtenerOrganizadorActivoXDescripcion($organizadorDescripcion) {
        return Organizador::create()->obtenerOrganizadorActivoXDescripcion($organizadorDescripcion);
    }

    public function obtenerEmpresaXOrganizadorId($organizadorId) {
        return Organizador::create()->obtenerEmpresaXOrganizadorId($organizadorId);
    }

    public function obtenerOrganizadorUbicacion($organizadorId) {
        return Organizador::create()->obtenerOrganizadorUbicacion($organizadorId);
    }

    public function obtenerOrganizadorUbicacionV($organizadorId, $id_agencia) {
        return Organizador::create()->obtenerOrganizadorUbicacionV($organizadorId, $id_agencia);
    }

    public function TraerAgenciaHijo($organizadorId) {
        return Organizador::create()->TraerAgenciaHijo($organizadorId);
    }

    public function obtenerConfiguracionInicialForm($organizadorId, $usuarioId) {
        $respuesta = new stdClass();
        $respuesta->dataEmpresa = EmpresaNegocio::create()->getAllEmpresaByUsuarioId($usuarioId);
        $respuesta->dataOrganizadorTipo = OrganizadorNegocio::create()->getDataComboOrganizadorTipo();
        $respuesta->dataAgencia = AgenciaNegocio::create()->getDataAgencia();
        $respuesta->dataOrganizadorActivo = OrganizadorNegocio::create()->obtenerOrganizadorActivo($organizadorId);
        $respuesta->dataOrganizador = OrganizadorNegocio::create()->getOrganizador($organizadorId);
        return $respuesta;
    }

    public function eliminar_acentos($cadena) {

        //Reemplazamos la A y a
        $cadena = str_replace(
                array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
                array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
                $cadena
        );

        //Reemplazamos la E y e
        $cadena = str_replace(
                array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
                array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
                $cadena);

        //Reemplazamos la I y i
        $cadena = str_replace(
                array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
                array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
                $cadena);

        //Reemplazamos la O y o
        $cadena = str_replace(
                array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
                array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
                $cadena);

        //Reemplazamos la U y u
        $cadena = str_replace(
                array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
                array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
                $cadena);

        //Reemplazamos la N, n, C y c
        $cadena = str_replace(
                array('Ñ', 'ñ', 'Ç', 'ç'),
                array('N', 'n', 'C', 'c'),
                $cadena
        );

        return $cadena;
    }

    public function generaQR($id, $codigo, $descripcion, $tipo, $usu_creacion) {
        require_once __DIR__ . '/../../util/phpqrcode/qrlib.php';
        $descripcion = $this->eliminar_acentos($descripcion);
        $tipo = $this->eliminar_acentos($tipo);
        $textoQR = 'ORG|' . "$codigo" . '|' . $descripcion . '|' . $tipo;

        $ruta = __DIR__ . '/../../vistas/com/organizador/codigoQR/' . $id . ".png";
        $pdf_title = $id . '.pdf';
        $url = __DIR__ . '/../../vistas/com/organizador/codigoQR/' . $id . '.pdf';

        QRcode::png($textoQR, $ruta, "Q", 10, 2);

        $response = Organizador::create()->insertQR($id, $codigo, $textoQR, $usu_creacion);
        $r = array("id" => $id, "ruta" => $ruta);
        return $r;
    }

    public function generaPDFQR($id) {
        require_once __DIR__ . '/../../controlador/commons/TCPDF-main/tcpdf.php';
        $medidas = array(100, 100); // Ajustar aqui segun los milimetros necesarios;
        $pdf = new TCPDF('P', 'mm', $medidas, true, 'UTF-8', false);
        $pdf->SetMargins(3, 1, 5);
        $pdf->startPageGroup();
        // set auto page breaks
        $pdf->SetAutoPageBreak(FALSE, PDF_MARGIN_BOTTOM);
        $pdf->AddPage();

        $data = $this->getOrganizador($id);
        $dataPadre = $this->getOrganizador($data[0]['organizador_padre_id']);

        $tabla = '';
        $logo = __DIR__ . "/../../controlador/commons/tcpdf/images/logoIttzaBusPaqueteQR.png";
//        $logo = __DIR__ . "/../../controlador/commons/tcpdf/images/logoIttzaBusPaqueteQR_.png";
//        $imag = __DIR__ . "/../../controlador/commons/tcpdf/images/organizadorIcono.png";

        $codigo = $data[0]['codigo'];
        $tipo = $data[0]['organizador_tipo'];
        $descripcion = $data[0]['descripcion'];
        $descripcionPadre = $data[0]['descripcion_padre'];
        $descripcionAlmacen = $dataPadre[0]['descripcion_padre'];
        $agencia = $data[0]['agencia'];
        $archivoQR = __DIR__ . '/../../vistas/com/organizador/codigoQR/' . $id . ".png";
        
        $cabecera = '';
        if ($data[0]['organizador_tipo_id'] == 12) {
            $cabecera = $descripcionPadre . ' - ' . $descripcion;
        }
        
        $separador = '<tr><td colspan="2"  style="font-size:4px;background-color:white;text-align:center;">
						_________________________________________________________________________________________________________________<br>        
					</td></tr>';
        $tabla = '<br> <br><table >
				<tr><td style="background-color:white;text-align:center;"><img width="105" height="32" src="' . Util::ImageTobase64($logo) . ' ">
                                    </td>
                                    <td   style="font-size:18px;background-color:white;text-align:center;"><b>&nbsp;</b></td>
				</tr>';
        $tabla = $tabla . '<tr>
					<td colspan="2"  style="font-size:21px;background-color:white;text-align:center;">
						<b> ' . $cabecera .' </b>      
					</td>	
                                </tr>';
        $tabla = $tabla . $separador;
        $tabla = $tabla . '<tr><td style=";background-color:white;text-align:center;" colspan="2"><img  src="' . Util::ImageTobase64($archivoQR) . ' " width="120" height="120"></td></tr>';

        $tabla = $tabla . $separador;

        $tabla = $tabla . '<tr>
                                    <td colspan="2" style="font-size:18x;background-color:white;text-align:center;">
                                           <b>' . $agencia . ' </b>
                                    </td>				
				</tr>';

        $tabla = $tabla . $separador;

        $tabla = $tabla . '<tr>
                                <td colspan="2" style="font-size:20px;background-color:white;text-align:center;">'
                . '                 <b>' . $descripcionAlmacen . '</b> 
                                </td>	
                           </tr>';
        $tabla = $tabla . '</table>';

        $pdf->writeHTML($tabla, false, false, false, false, '');

        $pdf_title = $codigo . '.pdf';
        $url = __DIR__ . '/../../vistas/com/organizador/codigoQR/' . $pdf_title;
        ob_clean();
        $pdf->Output($url, 'F');
        
        if (file_exists($archivoQR)) {
            unlink($archivoQR);
        }
        
        return $pdf_title;
    }

}
