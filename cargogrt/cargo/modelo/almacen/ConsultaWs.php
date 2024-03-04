<?php

require_once __DIR__ . "/../core/ModeloBase.php";
require_once __DIR__ . '/../../util/Configuraciones.php';

class ConsultaWs extends ModeloBase {

    const TIPO_METODO_CONSULTA = "C";
    const TIPO_METODO_REGISTRO = "R";
    const BANDERA_LOG_CONSULTA = FALSE;
    const BANDERA_LOG_REGISTRO = FALSE;
    const TIPO_WEB_SERVICES = 1;
    const TIPO_WEB_SERVICES_SUNAT = 2;

    /**
     * 
     * @return ConsultaWs
     */
    static function create() {
        return parent::create();
    }

    public function conexion($tipo = self::TIPO_WEB_SERVICES) {
        try {
            ini_set('soap.wsdl_cache_enabled', '0');
            ini_set('soap.wsdl_cache_ttl', '0');
            $url = "";
            switch ($tipo) {
                case self::TIPO_WEB_SERVICES:
                    $url = Configuraciones::B2BWS_URL;
                    break;
                case self::TIPO_WEB_SERVICES_SUNAT:
                    $url = Configuraciones::SUNAT_CONSULTA_URL;
                    break;
            }
            $conexion = new SoapClient($url);

            return $conexion;
        } catch (Exception $e) {
            $mensajeError = $e->getMessage();
            throw new WarningException("Imposible conectarse con el servicio web." . $mensajeError);
        }
    }

    public function consulta($empresaId, $metodo, $parametros, $tipoMetodo = self::TIPO_METODO_CONSULTA, $tipoWebServices = self::TIPO_WEB_SERVICES) {
        if ((ObjectUtil::isEmpty($empresaId) || !is_numeric($empresaId)) && $tipoWebServices != self::TIPO_WEB_SERVICES_SUNAT) {
            throw new WarningException("Se requiere del identificador de la empresa para realizar cualquier consulta.");
        }

        if (ObjectUtil::isEmpty($parametros)) {
            $parametros = new stdClass();
        }

        $parametros->idEmpresa = $empresaId;

        $conexion = self::conexion($tipoWebServices);
        //GUARDAMOS EL REGISTRO DE SINCRONIZACION
        if (($tipoMetodo === self::TIPO_METODO_REGISTRO && self::BANDERA_LOG_REGISTRO) || ($tipoMetodo === self::TIPO_METODO_CONSULTA && self::BANDERA_LOG_CONSULTA)) {
            $urlArchivoSincronizacion = self::crearArchivoSincronizacion($metodo, $parametros, $_SESSION["usuario_id"]);
        }

        if ($tipoMetodo === self::TIPO_METODO_REGISTRO) {
            session_start();
            $parametros->usuario = $_SESSION['usuario_acceso'];
        }

        $resultadoSap = NULL;
        $banderaRespuesta = FALSE;
        try {
            eval('$resultadoSap = $conexion->' . $metodo . '((array) $parametros)->' . $metodo . 'Result;');

            if (!ObjectUtil::isEmpty($urlArchivoSincronizacion)) {
                $banderaRespuesta = TRUE;
                self::guardarRespuestaArchivoSincronizacion($urlArchivoSincronizacion, $resultadoSap);
            }

            $respuesta = json_decode($resultadoSap);
            if ($respuesta->status == 'OK') {
                switch ($tipoMetodo) {
                    case self::TIPO_METODO_CONSULTA:
                        return ObjectUtil::devolverArrayDeObjetos($respuesta->data);
                    case self::TIPO_METODO_REGISTRO:
                        return $respuesta;
                }
            } else {
                $mensajeError = ($tipoMetodo == self::TIPO_METODO_CONSULTA ? "Error al consultar ($metodo): " : "");
                throw new WarningException($mensajeError . $respuesta->mensaje);
            }
        } catch (Exception $excepcion) {
            if (!ObjectUtil::isEmpty($urlArchivoSincronizacion) && !$banderaRespuesta) {
                self::guardarRespuestaArchivoSincronizacion($urlArchivoSincronizacion, $excepcion->getMessage());
            }
            throw new WarningException($excepcion->getMessage());
        }
    }

    /*     * ****************************************************************  FUNCIONES EXTRAS ********************************************************** */

    public function crearArchivoSincronizacion($metodo, $parametros, $usuario) {

        $nombreArchivo = $metodo . "_" . $usuario . date("Ymd") . "_" . date("His") . ".TXT";

        $direccion = __DIR__ . "/../../util/uploads/log_sincronizacion/" . $nombreArchivo;

        file_put_contents($direccion, null);

        $file = fopen($direccion, "w");

        fwrite($file, mb_convert_encoding(json_encode($parametros), "ISO-8859-1") . "\r\n");

        fclose($file);

        return $direccion;
    }

    public function guardarRespuestaArchivoSincronizacion($direccion, $respuesta) {

        $file = fopen($direccion, "a");

        fputs($file, mb_convert_encoding($respuesta, "ISO-8859-1") . "\r\n");

        fclose($file);

        return $direccion;
    }

    public function obtenerConsultaRucSunat($ruc) {
        $parametros = new stdClass();
        $parametros->ruc = $ruc;
        return self::consulta(NULL, "consultaRUC2", $parametros, self::TIPO_METODO_CONSULTA, self::TIPO_WEB_SERVICES_SUNAT);
    }

    public function validarComprobantePagoSunat($rucCliente, $rucEmisior, $tipoDocumento, $serie, $numero, $fechaEmision, $montoTotal) {
        $parametros = new stdClass();
        $parametros->rucCliente = $rucCliente;
        $parametros->rucEmisior = $rucEmisior;
        $parametros->tipoDocumento = $tipoDocumento;
        $parametros->serie = $serie;
        $parametros->numero = $numero;
        $parametros->fechaEmision = $fechaEmision;
        $parametros->montoTotal = $montoTotal;

        return self::consulta(NULL, "consultaComprobantePago", $parametros, self::TIPO_METODO_CONSULTA, self::TIPO_WEB_SERVICES_SUNAT);
    }

    public function validarComprobantePagoTokenSunat($rucCliente, $clienteId, $clientePass, $rucEmisior, $tipoDocumento, $serie, $numero, $fechaEmision, $montoTotal) {
        $parametros = new stdClass();
        $parametros->rucCliente = $rucCliente;
        $parametros->clienteId = $clienteId;
        $parametros->clientePass = $clientePass;
        $parametros->rucEmisior = $rucEmisior;
        $parametros->tipoDocumento = $tipoDocumento;
        $parametros->serie = $serie;
        $parametros->numero = $numero;
        $parametros->fechaEmision = $fechaEmision;
        $parametros->montoTotal = $montoTotal;

        return self::consulta(NULL, "consultaComprobantePagoToken", $parametros, self::TIPO_METODO_CONSULTA, self::TIPO_WEB_SERVICES_SUNAT);
    }
    public function validarToken(){
        $ch = curl_init(Configuraciones::CREACION_TOKEN);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $array = ['UsuarioName' => Configuraciones::USUARIO_TOKEN, 'UsuarioPassword' => Configuraciones::PASS_TOKEN];
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($array));
        $headers = [];
        $headers[] = 'Content-Type:application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result2 = curl_exec($ch);
        curl_close($ch);
        $login = json_decode($result2, true);
        $token = $login['token'];
        return $token;
    }

    public function obtenerConsultaRuc($ruc) {
        $url = Configuraciones::CONSULTA_RUC.$ruc;
        $ch = curl_init();
        $headers = array(
            "Content-Type: application/json",
            "Authorization: Bearer " . self::validarToken()
        );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result, true);
    }

    public function obtenerConsultaDocumento($tipoDocumn , $codigoIdentificacion) {
        $url = Configuraciones::CONSULTA_DOCUMENTO.$tipoDocumn.'/'.$codigoIdentificacion;
        $ch = curl_init();
        $headers = array(
            "Content-Type: application/json",
            "Authorization: Bearer " . self::validarToken()
        );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result, true);
    }
    
}
