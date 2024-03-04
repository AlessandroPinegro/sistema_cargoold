<?php

/**
 * Description of Util
 *
 * @author cheredia
 * @version 1.0
 * @copyright (c) 2013, IMAGINA TECHNOLOGIES S.A.C.
 */
include_once __DIR__ . '/../util/Configuraciones.php';

class Util {

    const VOUT_EXITO = 1;

    /**
     * Genera un codigo aleatorio de longitud especificada.
     * 
     * @author cheredia
     * @param int $lenght
     * @return string
     */
    static public function generateCode($lenght = 8) {
        $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
        $code = "";
        for ($i = 0; $i < $lenght; $i++) {
            $code .= substr($str, rand(0, 62), 1);
        }
        return $code;
    }

    /**
     * Crea un objeto StdClass a partir de un array con valores
     * 
     * @param Array $object_as_array array con los keys y values que se transformarán en las propiedades del StdClass
     * @return null|\stdClass
     * 
     * 
     */
    static public function newStdClass($object_as_array) {
        if (ObjectUtil::isEmpty($object_as_array))
            return null;

        $object = new stdClass();

        foreach ($object_as_array as $key => $value) {
            $property = $value;

            if (ObjectUtil::isNumber($value)) {
                if (stripos($value, ".") === FALSE) {
                    $property = intval($value);
                } else {
                    $property = floatval($value);
                }
            }

            $object->{$key} = $property;
        }

        return $object;
    }

    /**
     * Metodo para ordenar arreglos, que representan tablas de la base de datos.
     * 
     * @author cheredia <cheredia@imaginatecperu.com> 
     * @param type $arrayRecords
     * @param type $campo
     * @param type $desc
     * @return array
     */
    static public function sortArrayTabla($arrayRecords, $campo, $orden = Order::asc) {
        $hash = array();

        foreach ($arrayRecords as $keyR => $record) {
            $hash[$record[$campo] . '-' . $keyR] = $record;
        }

        ($orden == Order::desc) ? krsort($hash) : ksort($hash);

        $arrayRecords = array();
        foreach ($hash as $record) {
            $arrayRecords [] = $record;
        }

        return $arrayRecords;
    }

    static public function crearCookie($usu_ad) {
        setcookie(Configuraciones::COOKIE_NAME_SID, self::encripta($usu_ad), time() + Configuraciones::TIME_OUT, "/");
    }

    static public function crearCookieToken($token) {
        setcookie(Configuraciones::PARAM_USUARIO_TOKEN, $token, time() + Configuraciones::TIME_OUT, "/");
    }

    static public function borrarCookie() {
        setcookie(Configuraciones::COOKIE_NAME_SID, "", time() - 360000, "/");
        unset($_COOKIE[Configuraciones::COOKIE_NAME_SID]);
    }

    static public function encripta($string) {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_iv = 'This is iv';
        $key = "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3";
        // hash
        $key = hash('sha256', $key);
        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
        return $output;
    }

    static public function desencripta($string) {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_iv = 'This is iv';
        $key = "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3";
        // hash
        $key = hash('sha256', $key);
        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        return $output;
    }

    static public function base64ToImage($Base64Img) {
        list(, $Base64Img) = explode(';', $Base64Img);
        list(, $Base64Img) = explode(',', $Base64Img);
        $Base64Img = base64_decode($Base64Img);
        return $Base64Img;
    }

    static public function convertirArrayXCadena($array) {

        $cadena = '';

        if (!empty($array)) {
            //$cadena = '';
            for ($i = 0; count($array) > $i; $i++) {
                $cadena .= '(' . $array[$i] . '),';
            }
            if (!empty($cadena)) {
                $cadena = substr($cadena, 0, -1);
                return $cadena;
            } else {
                return $cadena;
            }
        }
        return $cadena;
    }

    static public function fromArraytoString($array) {

        $cadena = '';

        if (!empty($array)) {
            //$cadena = '';
            for ($i = 0; count($array) > $i; $i++) {
                $cadena .= '' . $array[$i] . ',';
            }
            if (!empty($cadena)) {
                $cadena = substr($cadena, 0, -1);
                return $cadena;
            } else {
                return $cadena;
            }
        }
        return $cadena;
    }

    static public function convertirArrayEnCadena($array) {
        return (empty($array)) ? '' : implode(",", $array);
    }

    static public function normaliza($cadena) {
        $originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎ¡ÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîï¡ðñòóôõöøùúûýýþÿŔŕ';
        $modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
        $cadena = utf8_decode($cadena);
        $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
        $cadena = strtolower($cadena);
        return utf8_encode($cadena);
    }

    static public function eliminar_tildes($cadena) {

        //Codificamos la cadena en formato utf8 en caso de que nos de errores
//    $cadena = utf8_encode($cadena);
        //Ahora reemplazamos las letras
        $cadena = str_replace(
                array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), $cadena
        );

        $cadena = str_replace(
                array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), $cadena);

        $cadena = str_replace(
                array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), $cadena);

        $cadena = str_replace(
                array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), $cadena);

        $cadena = str_replace(
                array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), $cadena);

        $cadena = str_replace(
                array('ñ', 'Ñ', 'ç', 'Ç'), array('n', 'N', 'c', 'C'), $cadena
        );

        return $cadena;
    }

    static public function diasTranscurridos($fechaI, $fechaF) {
//        diasTranscurridos('2012-07-01','2012-07-18');
        $dias = (strtotime($fechaI) - strtotime($fechaF)) / 86400;
        $dias = abs($dias);
        $dias = floor($dias);
        return $dias;
    }

    static public function buscarArrayPorNombreColumnaIdentificador($array, $busquedadId, $nombreColumnaBusquedad, $nombreColumnaRespuesta = NULL) {
        $valor = NULL;
        if (array_search($busquedadId, array_column($array, $nombreColumnaBusquedad)) !== false) {
            if (!ObjectUtil::isEmpty($nombreColumnaRespuesta)) {
                return $array[array_search($busquedadId, array_column($array, $nombreColumnaBusquedad))][$nombreColumnaRespuesta];
            } else {
                return $array[array_search($busquedadId, array_column($array, $nombreColumnaBusquedad))];
            }
        }
        return $valor;
    }

    static public function redondearNumero($valor, $cantidadDecimales = NULL) {
        if (!ObjectUtil::isNumber($valor)) {
            $valor = $valor * 1;
        }
        return round($valor, $cantidadDecimales);
    }

    static public function filtrarArrayPorColumna($array, $nombreColumnaBusqueda, $valorBusqueda, $nombrecolumnaRespuesta = NULL) {
        $arrayRespuesta = array();
        $consulta = "";
        if (is_string($nombreColumnaBusqueda)) {
            $consulta = "\$arrayRespuesta = array_filter(\$array, function (\$item) { return \$item['$nombreColumnaBusqueda'] == '$valorBusqueda'; });";
            eval($consulta);
        } elseif (is_array($nombreColumnaBusqueda)) {
            $consulta .= "\$arrayRespuesta = array_filter(\$array, function (\$item) { return \$item['$nombreColumnaBusqueda[0]'] == '$valorBusqueda[0]'; }); \n";
            for ($i = 1; $i < count($nombreColumnaBusqueda); $i++) {
                $consulta .= "\$arrayRespuesta = array_filter(\$arrayRespuesta, function (\$item) { return \$item['$nombreColumnaBusqueda[$i]'] == '$valorBusqueda[$i]'; }); \n";
            }
            eval($consulta);
        }
        $arrayRespuesta = array_merge($arrayRespuesta);
        if (!ObjectUtil::isEmpty($nombrecolumnaRespuesta)) {
            return $arrayRespuesta[0][$nombrecolumnaRespuesta];
        }
        return $arrayRespuesta;
    }

    static public function rellenarEspacios($cantidad) {
        $espacio = "";
        for ($i = 0; $i < $cantidad; $i++) {
            $espacio .= " ";
        }
        return $espacio;
    }

    static public function obtenerUltimoDiaMes($anio, $mes) {
        return date('Y-m-d', mktime(0, 0, 0, $mes, date("d", mktime(0, 0, 0, (($mes * 1) + 1), 0, $anio)), $anio));
    }

    static public function obtenerArrayUnicoXNombreCampo($array, $nombreColumnaBusqueda) {
        eval("\$resultado =  array_unique(array_map(function(\$item) { return \$item['$nombreColumnaBusqueda'];}, \$array)); ");
        return $resultado;
    }

    static public function validateReCaptCha($key, $response) {

        $curlx = curl_init();

        curl_setopt($curlx, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($curlx, CURLOPT_HEADER, 0);
        curl_setopt($curlx, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlx, CURLOPT_POST, 1);

        $post_data = [
            'secret' => $key, //<--- your reCaptcha secret key
            'response' => $response
        ];

        curl_setopt($curlx, CURLOPT_POSTFIELDS, $post_data);

        $resp = json_decode(curl_exec($curlx));

        curl_close($curlx);

        if ($resp->success) {
            return true;
        } else {
            return false;
        }
    }

    static public function ImageTobase64($path) {
        $data = file_get_contents($path);
        return "@" . base64_encode($data);
    }
}

?>
