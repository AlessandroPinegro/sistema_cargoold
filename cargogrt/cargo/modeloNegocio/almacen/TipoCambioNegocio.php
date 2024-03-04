<?php

require_once __DIR__ . '/../../modelo/almacen/TipoCambio.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/MonedaNegocio.php';

class TipoCambioNegocio extends ModeloNegocioBase {

    /**
     * 
     * @return TipoCambioNegocio
     */
    static function create() {
        return parent::create();
    }

    public function listarTipoCambio() {
        return TipoCambio::create()->listarTipoCambio();
    }

    public function obtenerMoneda() {
        return MonedaNegocio::create()->obtenerComboMoneda();
    }

    public function obtenerMonedaBase() {
        return MonedaNegocio::create()->obtenerMonedaBase();
    }

    public function obtenerMonedaDistintaBase() {
        return MonedaNegocio::create()->obtenerMonedaDistintaBase();
    }

    public function crearTipoCambio($tipoCambioId, $monedaId, $fecha, $equivalenciaCompra, $equivalenciaVenta, $usuCreacion) {

        $fechaBD = $this->formatearFechaBD($fecha);

        $res = TipoCambio::create()->insertarActualizarTipoCambio($tipoCambioId, $monedaId, $fechaBD, $equivalenciaCompra, $equivalenciaVenta, $usuCreacion);

        return $res;
    }

    public function formatearFechaBD($cadena) {
        if (!ObjectUtil::isEmpty($cadena)) {
            return DateUtil::formatearCadenaACadenaBD($cadena);
        }
        return "";
    }

    public function obtenerTipoCambioXid($id) {
        return TipoCambio::create()->obtenerTipoCambioXid($id);
    }

    public function obtenerTipoCambioXfecha($fecha) {
        return TipoCambio::create()->obtenerTipoCambioXfecha($fecha);
    }

    public function cambiarEstado($id_estado) {
        return TipoCambio::create()->cambiarEstado($id_estado);
    }

    public function eliminar($id, $nom) {
        $response = TipoCambio::create()->eliminar($id);
        $response[0]['fecha'] = $nom;
        return $response;
    }

    public function obtenerEquivalenciaSunatXFecha($fecha) {

        $dataEquivalencia = $this->obtenerEquivalenciasDolarSunat();

        //hallar la equivalencia de la fecha en el array
        foreach ($dataEquivalencia as $indice => $item) {
            if ($item['fecha'] == $fecha) {
                $equivalencia = $item;
                break;
            }
        }

        return $equivalencia;
    }

    public function obtenerEquivalenciasDolarSunat() {
        $pagina_inicio = file_get_contents('http://www.sunat.gob.pe/a/txt/tipoCambio.txt');
        $arrayDatos = explode("|", $pagina_inicio);
        $fecha = $arrayDatos[0];
        $equiCompra = $arrayDatos[1];
        $equiVenta = $arrayDatos[2];
        $indice = 0;
        $datosEquivalencia = array('fecha' => $fecha, 'compra' => $equiCompra, 'venta' => $equiVenta);
        $equivalenciaFechaDolar[0] = $datosEquivalencia;
        return $equivalenciaFechaDolar;
    }
    
    public function obtenerEquivalenciasDolarSunatOld() {
        $pagina_inicio = file_get_contents('http://www.sunat.gob.pe/cl-at-ittipcam/tcS01Alias');

        //Extraer parte de cadena
        //Dias
        $inicio = "<strong>";
        $fin = "</strong>";

        preg_match_all("|" . $inicio . "(.*).$fin.|sU", $pagina_inicio, $captura);
        $diaTodo = $captura[0];

        foreach ($diaTodo as $indice => $item) {
            $valor = $this->obtenerCadenaEntreCaracter($item, '>', '<');
            if (strlen($valor) == 1) {
                $valor = '0' . $valor;
            }
            $dia[$indice] = $valor;
        }


        //Equivalencias
        $inicio = "class=\"tne10\">";
        $fin = "</td>";

        preg_match_all("|" . $inicio . "(.*).$fin.|sU", $pagina_inicio, $captura);
        $equivalenciaTodo = $captura[0];

        $equiCompra = array();
        $equiVenta = array();

        foreach ($equivalenciaTodo as $indice => $item) {
            $valor = (float) $this->obtenerCadenaEntreCaracter($item, '>', '<');

            if ($indice % 2 == 0) {
                array_push($equiCompra, $valor);
            } else {
                array_push($equiVenta, $valor);
            }

            $equivalencia[$indice] = $valor;
        }

        //Mes Año
        $inicio = "<h3>";
        $fin = "</h3>";

        preg_match_all("|" . $inicio . "(.*).$fin.|sU", $pagina_inicio, $captura);
        $mesAnioTodo = $captura[0];
        $mesAnio = $this->obtenerCadenaEntreCaracter($mesAnioTodo[0], '>', '<');

        $anio = (int) substr($mesAnio, -4);
        ;
        $mesNombre = substr($mesAnio, 0, strlen($mesAnio) - 7);


        //Mes combo
        $inicio = "<option value=\"";
        $fin = ">";

        preg_match_all("|" . $inicio . "(.*).$fin.|sU", $pagina_inicio, $captura);
        $mesNumeroCombo = $captura[1];

        $inicio = "\">";
        $fin = "/option>";

        preg_match_all("|" . $inicio . "(.*).$fin.|sU", $pagina_inicio, $captura);
        $mesNombreComboTodo = $captura[1];

        foreach ($mesNombreComboTodo as $indice => $item) {
            if ($indice < count($mesNumeroCombo)) {
                $mesNombreCombo[$indice] = $mesNombreComboTodo[$indice];
            }
        }

        //hallar el valor numeor de mes
        $clave = array_search($mesNombre, $mesNombreCombo);
        $mes = $mesNumeroCombo[$clave];


        //variables finales
        /* $anio;
          $mes;
          $dia;
          $mesNombre;
          $mesNombreCombo;
          $mesNumeroCombo;
          $equiCompra;
          $equiVenta; */

        foreach ($dia as $indice => $item) {
            $datosEquivalencia = array('fecha' => $item . '/' . $mes . '/' . $anio, 'compra' => $equiCompra[$indice], 'venta' => $equiVenta[$indice]);

            $equivalenciaFechaDolar[$indice] = $datosEquivalencia;
        }

        //$equivalenciaFechaDolar;

        /* foreach ($equivalenciaFechaDolar as $indice => $item) {

          echo "Fecha: " . $item["fecha"] . " Compra: " . $item["compra"] . " Venta: " . $item["venta"] . "<br>";
          } */
        $data = $equivalenciaFechaDolar;

        return $equivalenciaFechaDolar;
    }

    public function obtenerCadenaEntreCaracter($contenido, $inicio, $fin) {
        $r = explode($inicio, $contenido);
        if (isset($r[1])) {
            $r = explode($fin, $r[1]);
            return $r[0];
        }
        return '';
    }

    public function obtenerTipoCambioXFechaUltima($fecha) {
        $fechaBD = $this->formatearFechaBD($fecha);
        return TipoCambio::create()->obtenerTipoCambioXFechaUltima($fechaBD);
    }

}
