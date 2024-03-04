<?php

/**
 * 
 * @version 1.0
 * @copyright (c) 2013, IMAGINA TECHNOLOGIES S.A.C.
 * 
 * @abstract PHPClass que contiene las funciones utilitarias para el manejo de objetos
 */
class ObjectUtil {

    /**
     * Verifica si un Objeto tiene un valor válido
     * 
     * @param mixed $object
     * @return boolean
     */
    static public function isEmpty($object) {
        if (!isset($object))
            return true;
        if (is_null($object))
            return true;
        if (is_string($object) && strlen($object) <= 0)
            return true;
        if (is_array($object) && empty($object))
            return true;
        if (is_numeric($object) && is_nan($object))
            return true;

        return false;
    }

    /**
     * Verifica si un valor es considerado numérico
     * 
     * @param mixed $number
     * @return boolean
     */
    static public function isNumber($number) {
        if (self::isEmpty($number))
            return false;
        if (!is_numeric($number))
            return false;
        if (is_nan($number))
            return false;

        return true;
    }

    /**
     * Obtiene el valor de la propiedad de un objeto
     * 
     * @param mixed $object Objeto del que se obtendrá el valor de su propiedad
     * @param string $propierty nombre de la propiedad
     * @return null | mixed
     */
    static public function getPropiertyValue($object, $propierty) {
        if (!self::hasPropiertyValue($object, $propierty))
            return null;

        $value = null;
        if ($object instanceof stdClass)
            $value = $object->{$propierty};
        else
            $value = $object[$propierty];

        return $value;
    }

    /**
     * Verifica si la propiedad existe o no en un objeto
     * 
     * @param mixed $object objeto que se va a evaluar
     * @param string $propierty nombre de la propiedad
     * @return boolean 
     */
    static public function hasPropiertyValue($object, $propierty) {
        if (self::isEmpty($propierty))
            return false;
        if (self::isEmpty($object))
            return false;
        if (!is_object($object))
            return false;

        return property_exists($object, $propierty);
    }

    static public function formatDateTime($datetime) {
        if ($datetime != null)
            return date_format(new DateTime($datetime), 'Y-m-d H:i');
        return null;
    }

    static public function numeroDeSemanaPorAnio($fechaInicial, $fechaFinal) {
        $fini = new DateTime($fechaInicial);
        $ffin = new DateTime($fechaFinal);
        $interval = $fini->diff($ffin);
        $semanasXAnio = array();
        while ($fini < $ffin/* $interval->invert != 1 */) {
            $semanasXAnio[$fini->format("o")][] = $fini->format("W");
            $fini->add(new DateInterval("P7D"));
            $interval = $fini->diff($ffin);
        }
        return $semanasXAnio;
    }

    static public function strlen_utf8($str) {
        $i = 0;
        $count = 0;
        $len = strlen($str);
        $count_chars = count_chars($str);
        $saltos = $count_chars[10];
        while ($i < $len) {
            $chr = ord($str[$i]);
            $count++;
            $i++;
            if ($i >= $len)
                break;
            if ($chr & 0x80) {
                $chr <<= 1;
                while ($chr & 0x80) {
                    $i++;
                    $chr <<= 1;
                }
            }
        }
        return $count + $saltos;
    }

    static public function object_array($valor) {
        if (!@is_array($valor) and ! @is_object($valor)) {
            return $valor;
        } else {
            foreach ($valor as $key => $cadena) {
                $valores[$key] = self::object_array($cadena);
            }
        }

        return $valores;
    }

    static public function parseString($string) {
        $string = str_replace("\\", "\\\\", $string);
        $string = str_replace('/', "\\/", $string);
        $string = str_replace("\b", "\\b", $string);
        $string = str_replace("\t", "\\t", $string);
        $string = str_replace("\n", "<br>", $string);
        $string = str_replace("\f", "\\f", $string);
        $string = str_replace("\r", "\\r", $string);
        $string = str_replace("\u", "\\u", $string);
        return $string;
    }

    static public function multiArrayToArray($array, $key) {
        $simpleArray = array();
        foreach ($array as $value) {
            $simpleArray[] = $value[$key];
        }
        return $simpleArray;
    }

    static public function getArrayDeCampo($array, $campo) {
        if (self::isEmpty($array))
            return null;
        $respuesta = array();
        $objeto = $array[0];
        if (is_array($objeto)) {
            foreach ($array as $objeto) {
                array_push($respuesta, $objeto[$campo]);
            }
        } else {
            foreach ($array as $objeto) {
                array_push($respuesta, $objeto->$campo);
            }
        }
        return $respuesta;
    }
    
    static function getStringBetween($string, $start, $end){
        $string = trim(' ' . $string);
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }
    
    static function isStringContent($string, $content){
        $string = ' ' . $string;
        $ini = strpos($string, $content);
        return !($ini == 0);
    }
    
    static function devolverArrayDeObjetos($lista) {
        $respuesta = array();
        if (!ObjectUtil::isEmpty($lista)) {
            foreach ($lista as $index => $itemLista) {
                $respuestaItem = array();
                if (!ObjectUtil::isEmpty($itemLista)) {
                    foreach ($itemLista as $index => $itemString) {
                        $valorString = $itemString;

                        $arrayString = array(($valorString[0]) => ($valorString[1]));

                        $respuestaItem = array_merge($respuestaItem, $arrayString);
                    }
                } else {
                    foreach ($itemLista as $index => $itemString) {
                        $valorString = $itemString;

                        $arrayString = array(($valorString[0]) => ($valorString[1]));

                        $respuestaItem = array_merge($respuestaItem, $arrayString);
                    }
                }

                array_push($respuesta, $respuestaItem);
            }
        }

        return $respuesta;
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
}
