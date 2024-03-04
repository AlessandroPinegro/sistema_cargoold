<?php

if (!isset($_SESSION)) {
    session_start();
}

include_once __DIR__ . '/../../util/Util.php';
require_once __DIR__ . '/../../modelo/almacen/Vehiculo.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/EmpresaNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/PersonaNegocio.php';

// require_once __DIR__ . '/../../controlador/commons/TCPDF-main/config/lang/eng.php';


class VehiculoNegocio extends ModeloNegocioBase {

    /**
     * 
     * @return VehiculoNegocio
     */
    static function create() {
        return parent::create();
    }

    // get vehiculos
    public function getDataVehiculo($id_bandera = null) {
        $data = new stdClass();
        $data->vehiculo = Vehiculo::create()->getDataVehiculo();
        //$data->empresa = Vehiculo::create()->obtenerProveedor(28);
        // $tamanio = count($data);
        // for ($i = 0; $i < $tamanio; $i++) {
        //   if ($data[$i]['estado'] == 1) {
        //     $data[$i]['icono'] = "ion-checkmark-circled";
        //     $data[$i]['color'] = "#5cb85c";
        //   } else {
        //     $data[$i]['icono'] = "ion-flash-off";
        //     $data[$i]['color'] = "#cb2a2a";
        //   }
        //   if ($id_bandera != null) {
        //     $data[$i]['id_bandera'] = $id_bandera;
        //   }
        // }
        return $data;
    }

    public function insertVehiculo($flotaId, $empresa, $flota, $placa, $marca, $capacidad, $tipo, $usu_creacion, $tarjetaCirculacion, $codigoConfiguracion) {

        $response = Vehiculo::create()->insertVehiculo($flotaId, $empresa, $flota, $placa, $marca, $capacidad, $tipo, $usu_creacion, $tarjetaCirculacion, $codigoConfiguracion);
        return $response;
    }

    public function getVehiculo($id) {
        return Vehiculo::create()->getVehiculo($id);
    }

    public function getEmpresas() {
        return Vehiculo::create()->obtenerProveedor(28);
    }

    public function updateVehiculo($id, $flotaId, $empresa, $flota, $placa, $marca, $capacidad, $tipo, $usu_creacion, $tarjetaCirculacion, $codigoConfiguracion) {
        $response = Vehiculo::create()->updateVehiculo($id, $flotaId, $empresa, $flota, $placa, $marca, $capacidad, $tipo, $usu_creacion, $tarjetaCirculacion, $codigoConfiguracion);
        return $response;
    }

    public function deleteVehiculo($id, $placa) {
        $response = Vehiculo::create()->deleteVehiculo($id);
        $response[0]['placa'] = $placa;
        return $response;
    }

    public function cambiarEstado($id_estado) {
        $data = Vehiculo::create()->cambiarEstado($id_estado);
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

    public function eliminar_acentos($cadena) {

        //Reemplazamos la A y a
        $cadena = str_replace(
                array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'), array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'), $cadena
        );

        //Reemplazamos la E y e
        $cadena = str_replace(
                array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'), array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'), $cadena
        );

        //Reemplazamos la I y i
        $cadena = str_replace(
                array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'), array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'), $cadena
        );

        //Reemplazamos la O y o
        $cadena = str_replace(
                array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'), array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'), $cadena
        );

        //Reemplazamos la U y u
        $cadena = str_replace(
                array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'), array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'), $cadena
        );

        //Reemplazamos la N, n, C y c
        $cadena = str_replace(
                array('Ñ', 'ñ', 'Ç', 'ç'), array('N', 'n', 'C', 'c'), $cadena
        );

        return $cadena;
    }

    public function generaQR($id, $placa, $tipo, $capacidad, $empresa, $usu_creacion) {
        require_once __DIR__ . '/../../util/phpqrcode/qrlib.php';
        $tipo = $this->eliminar_acentos($tipo);
        $empresa = $this->eliminar_acentos($empresa);

        if (!ObjectUtil::isEmpty($empresa)) {
            $textoQR = 'V|' . $placa . '|' . $tipo . '|' . $capacidad . '|' . $empresa;
        } else {
            $textoQR = 'V|' . $placa . '|' . $tipo . '|' . $capacidad.'|';
        }

        $ruta = __DIR__ . '/../../vistas/com/vehiculos/codigoQR/' . $placa . ".png";
       // $pdf_title = $placa . '.pdf';
        //$url = __DIR__ . '/../../vistas/com/vehiculos/codigoQR/' . $placa . '.pdf';
        QRcode::png($textoQR, $ruta, "Q", 10, 2);
        $response = Vehiculo::create()->insertQR($id, $textoQR, $usu_creacion);
        // $pdf = $this->imprimirQR($ruta, $url, $placa);    
        // $respuesta->url = $url;
        // $respuesta->ruta = $ruta;
        // $respuesta->pdf_title = $pdf_title;
        $r = array("id" => $id, "ruta" => $ruta);
        return $r;
    }

    public function generaPDFQR($idVehiculo) {
        require_once __DIR__ . '/../../controlador/commons/TCPDF-main/tcpdf.php';
        $medidas = array(100, 100); // Ajustar aqui segun los milimetros necesarios;
        $pdf = new TCPDF('P', 'mm', $medidas, true, 'UTF-8', false);
        $pdf->SetMargins(3, 1, 5);
        $pdf->startPageGroup();
        // set auto page breaks
        $pdf->SetAutoPageBreak(FALSE, PDF_MARGIN_BOTTOM);
        $pdf->AddPage();

        $dataVehiculo = $this->getVehiculo($idVehiculo);
        $tabla = '';
        $logo = __DIR__ . "/../../controlador/commons/tcpdf/images/logoIttzaBusPaqueteQR.png";
//        $imag = __DIR__ . "/../../controlador/commons/tcpdf/images/transporteIcono.png";

        $placa = $dataVehiculo[0]['flota_placa'];
        $marca = $dataVehiculo[0]['flota_marca'];
        $tipoDesc = $dataVehiculo[0]['tipo_desc'];
        $numero = $dataVehiculo[0]['flota_numero'];
        $empresa = $dataVehiculo[0]['empresa'];
        $archivoQR = __DIR__ . '/../../vistas/com/vehiculos/codigoQR/' . $placa . ".png";

        $separador = '<tr><td colspan="2"  style="font-size:4px;background-color:white;text-align:center;">
						_________________________________________________________________________________________________________________<br>        
					</td></tr>';
        $tabla = '<br> <br><table >
				<tr><td  style="background-color:white;text-align:center;"><img width="105" height="32" src="' . Util::ImageTobase64($logo) . ' ">
                                    </td>
                                    <td   style="font-size:14px;background-color:white;text-align:center;"><b>' . $placa . '</b></td>
				</tr>';
        $tabla = $tabla . '<tr>
					<td colspan="2"  style="font-size:15px;background-color:white;text-align:center;">
						<b> VEHICULO </b>      
					</td>	
                                </tr>';
        $tabla = $tabla . $separador;
        $tabla = $tabla . '<tr><td style=";background-color:white;text-align:center;" colspan="2"><img  src="' . Util::ImageTobase64($archivoQR) . ' " width="120" height="120"></td></tr>';

        $tabla = $tabla . $separador;

        $tabla = $tabla . '<tr>
                                    <td colspan="2" style="font-size:15px;background-color:white;text-align:center;">
                                            <b>' . $marca . ' </b>&nbsp;|&nbsp;<b>' . $tipoDesc .(!ObjectUtil::isEmpty($numero) ? '&nbsp;&nbsp;'.$numero: ''). ' </b>
                                    </td>				
				</tr>';

        $tabla = $tabla . $separador;

        $tabla = $tabla . '<tr>
                                <td colspan="2" style="font-size:17px;background-color:white;text-align:center;"> '
                . '                 <b>' . $empresa . '</b> 
                                </td>	
                           </tr>';
        $tabla = $tabla . '</table>';

        $pdf->writeHTML($tabla, false, false, false, false, '');

        $pdf_title = $placa . '.pdf';
        $url = __DIR__ . '/../../vistas/com/vehiculos/codigoQR/' . $pdf_title;
        ob_clean();
        $pdf->Output($url, 'F');

        if (file_exists($archivoQR)) {
            unlink($archivoQR);
        }

        return $pdf_title;
    }

    public function insertVehiculos($fecha, $usuarioCreacion) {
        // empresa ittsa
        $empresa = $data = Empresa::create()->obtenerEmpresaXId(2);
        $idEmpresa = $data[0]['id'];
        //ITINERARIO DE BUSES
        /* API URL */

        $url = 'https://www.ittsabus.com/suiteapirest/Usuario/Login';

        /* Init cURL resource */
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        /* Array Parameter Data */
        $array = ['UsuarioName' => '47046044', 'UsuarioPassword' => '47046044'];
        $data = json_encode($array);
        /* pass encoded JSON string to the POST fields */
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        /* set the content type json */
        $headers = [];
        $headers[] = 'Content-Type:application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        /* set return type json */
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        /* execute request */
        $result2 = curl_exec($ch);
        // return $result2;
        /* close cURL resource */
        curl_close($ch);

        $login = json_decode($result2, true);
        $token = $login['token'];
        //ITINERARIO DE BUSES
        /* API URL */
        $url = 'https://www.ittsabus.com/suiteapirest/Itinerario/Cargo/Listar/Fecha/' . $fecha;

        /* Init cURL resource */
        $ch = curl_init();
        /* Array Parameter Data */
        //    $data = ['name'=>'Hardik', 'email'=>'itsolutionstuff@gmail.com'];

        /* pass encoded JSON string to the POST fields */
        //    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        /* set the content type json */
        //    $headers = [];
        //    $headers[] = 'Content-Type: application/json';
        //    $headers[] = "Authorization: Bearer ".$token;
        $headers = array(
            "Content-Type: application/json",
            "Authorization: Bearer " . $token
        );

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        /* set return type json */

        /* execute request */
        $result = curl_exec($ch);

        /* close cURL resource */
        curl_close($ch);

        $a = json_decode($result, true);
        $itinerarios = $a['listaItinerarios'];

        foreach ($itinerarios as $e => $value) {
            $flotaId = $a['listaItinerarios'][$e]['flotaId'];
            $flotaNro = $a['listaItinerarios'][$e]["flotaNro"];
            $flotaPlaca = $a['listaItinerarios'][$e]["flotaPlaca"];
            $flotaMarca = $a['listaItinerarios'][$e]["flotaMarca"];
            $flotaCargaMX = $a['listaItinerarios'][$e]["flotaCargaMX"];
            $tipo = 1;
            $response = Vehiculo::create()->insertVehiculos($flotaId, 438, $flotaNro, $flotaPlaca, $flotaMarca, $flotaCargaMX, $tipo, $usuarioCreacion);
        }
        return $response;
    }

}
