<?php
require_once __DIR__ . '/../../util/ObjectUtil.php';

/**
 * Encargada de los formatos estandares que usa el sistema
 *
 * @author Christopher Heredia Lozada <cheredia@imaginatecperu.com>
 */
class FormatoNegocio {
    // <editor-fold defaultstate="collapsed" desc="region variables y constantes">
        static private $_instance = null;  
        public $pageNumber;
        public $pageSize;
        
        // variables de configuración de formatos por Empresa.
        private $formato_fecha = FormatoFecha::LECTURA_DMY_HI;
        private $formato_fecha_sin_hora = FormatoFecha::LECTURA_DMY;
        private $cantidad_decimales = 2;
        private $cantidad_decimales_personalizado;
        
        private $formatosColumnas = Array();
        private $agrupacionesColumnas = Array();
        
    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="constructor">
        public function _construct(){
            $this->pageNumber = null;
            $this->pageSize = null;
        }
        
        /**
         * Creamos un singleton
         * @return FormatoNegocio 
         */
        static function create()
        {
            if(ObjectUtil::isEmpty(self::$_instance)){
                // Si es la primera vez la empresa no debe ser nula
                self::$_instance = new FormatoNegocio();
            }
            // Limpiamos los formateadores
            $method = new ReflectionMethod(self::$_instance, 'clearFormatos');
            $method->invoke(self::$_instance);

            return self::$_instance;
        }
    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="Métodos principales">
        // <editor-fold defaultstate="collapsed" desc="Agregado de formatos">
            /**
             * Agrega el tipo de fotmato que va ha usar la columna
             * @param type $columna
             * @param type $tipoFormato
             * @param type $numeroDecimales
             */
            public function addFormatoColumna($columna, $tipoFormato){ //, $numeroDecimalesPersonalizados = null){
                $this->formatosColumnas[$columna] = $tipoFormato;
//                $formato = new stdClass();
//                $formato->columna= $columna;
//                $formato->tipo = $tipoFormato;
//                $formato->decimales = $numeroDecimalesPersonalizados;
//                $this->formatosColumnas[] = $formato;
                return $this;
            }

            /**
             * Asigna la agrupación a la que pertenecerá la columna
             * @param type $columna
             * @param type $nombreAgrupacion
             */
            public function addAgrupacionColumna($columna, $agrupacion){
                $this->agrupacionesColumnas[$columna] = $agrupacion;
                return $this;
            }
        // </editor-fold>
        
        // <editor-fold defaultstate="collapsed" desc="Aplicar formatos">
            public function applyFormatoRecord($row, $arrayToArray = false){
                $prepareFormat = $this->getPrepareFormatRecord($row, $this->formatosColumnas, $this->agrupacionesColumnas);
                return $this->getFormatRecord($prepareFormat, $arrayToArray);
            }
            public function applyFormatoData($rows, $agrupaObj = true){
                $prepareFormat = $this->getPrepareFormatRecord($rows, $this->formatosColumnas, $this->agrupacionesColumnas);
                return $this->getFormatData($prepareFormat, $agrupaObj);
            }
            public function applyFormatoDataGrid($rows, $total, $pageNumber, $pageSize, $agrupaObj = true, $cantidadDecimalesPersonalizado = 3){
                $prepareFormat = $this->getPrepareFormatDataGrid($rows, $total, $this->formatosColumnas, $this->agrupacionesColumnas, $pageNumber, $pageSize);
                return $this->getFormatDataGrid($prepareFormat, $agrupaObj, $cantidadDecimalesPersonalizado);
            }
        // </editor-fold>
            
        /**
         * Se encarga de limpiar las configuraciones de los formatos que se han realizado
         */
        public function clearFormatos(){
            $this->clearFormatosColumnas();
            $this->clearAgrupacionesColumnas();
        }
        
        /**
         * Limpia los formatos de las columnas
         */
        public function clearFormatosColumnas(){
            $this->formatosColumnas = array();
        }
        
        /**
         * Limpia la configuracion de agrupaciones
         */
        public function clearAgrupacionesColumnas(){
            $this->agrupacionesColumnas = array();
        }

        /**
         * @author Christopher Heredia Lozada <cheredia@imaginatecperu.com>
         * 
         * * Setea las variables de $rows, $total y $colsFormat en un arreglo 
         *   y servirá como formato de respuesta al controlador
         *   para que este lo formatee...
         * 
         * @param int $rows => data del grid
         * @param int $total => longitud de la data completa que se va a mostrar en la grilla
         * @param array $colsFormat => Arreglo con las columnas que se desea formatear
         * @param array $colsGroup => Arreglo con las columnas que se quieren agrupar
         * @param int $pageNumber Numero de pagina que se esta consultando
         * @param int $pageSize Tamaño de la pagina
         */
        //public function getPrepareFormatDataGrid($rows, $total, $colsFormat = null, $colsGroup = NULL){
        public function getPrepareFormatDataGrid($rows, $total = -1, $colsFormat = null, $colsGroup = NULL, $pageNumber = null, $pageSize = null){
            // - Se crea un array que contenga el formato requerido por nuestro objeto pager del datagrid de easyui
            $responseToController = array();
            $responseToController['rows'] = $rows;
            //$responseToController['total'] = $total;
            $responseToController['total'] = ($total = -1)?((ObjectUtil::isEmpty($rows))? 0: count($rows)):$total;
            $responseToController['colsFormat'] = $colsFormat;
            $responseToController['colsGroup'] = $colsGroup;
//            $responseToController[Configuraciones::PAGE_NUMBER] = $pageNumber;
//            $responseToController[Configuraciones::PAGE_SIZE] = $pageSize;

            return $responseToController;
        }

        /**
         * @author Christopher Heredia Lozada <cheredia@imaginatecperu.com>
         * 
         * Setea las variables de $rows, $total y $colsFormat en un arreglo 
         *   y servirá como formato de respuesta al controlador para que este lo formatee...
         * 
         * @param int $rows => data del grid
         * @param int $total => longitud de la data completa que se va a mostrar en la grilla
         * @param array $colsFormat => Arreglo con las columnas que se desea formatear
         * @param array $colsGroup => Arreglo con las columnas que se quieren agrupar
         * @param int $pageNumber Numero de pagina que se esta consultando
         * @param int $pageSize Tamaño de la pagina
         */
        public function getPrepareFormatRecord($row, $rowFormat = null, $rowGroup = NULL){
            // - Se crea un array que contenga el formato requerido por nuestro objeto pager del datagrid de easyui
            $responseToController = array();
            $responseToController['row'] = $row;
            $responseToController['colsFormat'] = $rowFormat;
            $responseToController['colsGroup'] = $rowGroup;

            return $responseToController;
        }
        

       /**
        * @author Christopher Heredia Lozada <cheredia@imaginatecperu.com>
        * 
        * Aplicamos los formatos de:
        * - Fecha
        * - Hora
        * - Numero entero
        * - Numero decimal
        * según se especifiquen en el $colsFormat
        * 
        * @param array $rows Data del grid
        * @param array $colsFormat Formato de columnas
        * @return array
        */
       public function getFormatRecord($responseToController, $arrayToArray = false){
           // - validamos el response
           if (ObjectUtil::isEmpty($responseToController)) return $responseToController;

           // Obtenemos los valores de las variables que necesitamos
           $row = (array_key_exists('row', $responseToController))? $responseToController['row']: NULL;
           $colsFormat = (array_key_exists('colsFormat', $responseToController))? $responseToController['colsFormat']: NULL;
           $colsGroup = (array_key_exists('colsGroup', $responseToController))? $responseToController['colsGroup']: NULL;

           if (ObjectUtil::isEmpty($row)) return $row;
           if (ObjectUtil::isEmpty($colsFormat) && ObjectUtil::isEmpty($colsGroup)) return $row;
           if (ObjectUtil::isEmpty($colsFormat)) $colsFormat = array();
           if (ObjectUtil::isEmpty($colsGroup)) $colsGroup = array();

           $cols = $this->getColsGroup($row, $colsGroup);

           if (is_object($row))
               return $this->getFormatPropertys($row, $colsFormat, $colsGroup, $cols);
           elseif(is_array($row)){
               if ($arrayToArray) 
                   return $this->getFormatElementRow($row, $colsFormat, $colsGroup, $cols);
               else
                   return $this->getFormatElementRowToObject($row, $colsFormat, $colsGroup, $cols);
           }

           return $row;
       }


       /**
        * @author Christopher Heredia Lozada <cheredia@imaginatecperu.com>
        * Metodo que me permite obtener un formato adecuado para manejar la petición bajo demanda que se utiliza en 
        * la vista por parte del componente datagrid de jeasyui:
        * - Se obtiene la ultima data obtenida usando la instancia que se pasa en el parametro $objModeloBase
        * - Se obtiene un count(*) tomando en cuenta solo la clapsula where y join.
        * - Se crea un array que contenga el formato requerido por nuestro objeto pager del datagrid de easyui
        * @param type $responseToController
        * @param type $agrupaObj: Este parametro indica si se hara las agrupaciones de Columnas en Objetos
        * @param int $pageNumber
        * @param int $pageSize
        * @param ModeloBase $objModeloBase => Objeto Heredero de la clase ModeloBase
        * @param array $colsFormat => Arreglo con las columnas que se desea formatear
        */
       public function getFormatDataGrid($responseToController,$agrupaObj=true,$cantidadDecimalesPersonalizado=3){
           // - validamos el response
           if (ObjectUtil::isEmpty($responseToController)) return $responseToController;

           // Obtenemos los valores de las variables que necesitamos
           $this->cantidad_decimales_personalizado=$cantidadDecimalesPersonalizado;
           $rows = (array_key_exists('rows', $responseToController))? $responseToController['rows']: NULL;
           $colsFormat = (array_key_exists('colsFormat', $responseToController))? $responseToController['colsFormat']: NULL;
           $colsGroup = (array_key_exists('colsGroup', $responseToController) && $agrupaObj)? $responseToController['colsGroup']: NULL;
           $total =  (array_key_exists('total', $responseToController))? $responseToController['total']: NULL;
//           $pageNumber =  (array_key_exists(Configuraciones::PAGE_NUMBER, $responseToController) && !ObjectUtil::isEmpty($responseToController[Configuraciones::PAGE_NUMBER]))? $responseToController['pageNumber']: $this->pageNumber;
//           $pageSize =  (array_key_exists(Configuraciones::PAGE_SIZE, $responseToController) && !ObjectUtil::isEmpty($responseToController[Configuraciones::PAGE_SIZE]))? $responseToController[Configuraciones::PAGE_SIZE]: $this->pageSize;
           if (!ObjectUtil::isEmpty($colsFormat) || !ObjectUtil::isEmpty($colsGroup)) {
               
               $rows = $this->getFormatColsDataGrid ($rows, $colsFormat, $colsGroup);
           }

           // - Se crea un array que contenga el formato requerido por nuestro objeto pager del datagrid de easyui
           $response = array();
           $response['rows'] = $rows;
           $response['total'] = $total;
//           $response['pageNumber'] = $pageNumber;
//           $response['pageSize'] = $pageSize;

           return $response;
       }

       /**
        * @author Christopher Heredia Lozada <cheredia@imaginatecperu.com>
        * Metodo que me permite obtener un formato adecuado para manejar la data 
        * @param type $responseToController
        * @param type $agrupaObj: Este parametro indica si se hara las agrupaciones de Columnas en Objetos
        */
       public function getFormatData($responseToController,$agrupaObj=true){
           // - validamos el response
           if (ObjectUtil::isEmpty($responseToController)) return $responseToController;

           // Obtenemos los valores de las variables que necesitamos
           $rows = (array_key_exists('rows', $responseToController))? $responseToController['rows']: NULL;
           $colsFormat = (array_key_exists('colsFormat', $responseToController))? $responseToController['colsFormat']: NULL;
           $colsGroup = (array_key_exists('colsGroup', $responseToController) && $agrupaObj)? $responseToController['colsGroup']: NULL;
           if (!ObjectUtil::isEmpty($colsFormat) || !ObjectUtil::isEmpty($colsGroup)) {
               
               $rows = $this->getFormatColsDataGrid ($rows, $colsFormat, $colsGroup);
           }

           // - Se crea un array que contenga el formato requerido por nuestro objeto pager del datagrid de easyui
           return $rows;
       }

       /**
        * @author Christopher Heredia Lozada <cheredia@imaginatecperu.com>
        * 
        * Aplicamos los formatos de:
        * - Fecha
        * - Hora
        * - Numero entero
        * - Numero decimal
        * según se especifiquen en el $colsFormat
        * 
        * @param array $rows Data del grid
        * @param array $colsFormat Formato de columnas
        * @return array
        */
       public function getFormatColsDataGrid($rows, $colsFormat, $colsGroup = NULL){
           if (ObjectUtil::isEmpty($rows)) return $rows;
           if (ObjectUtil::isEmpty($colsFormat) && ObjectUtil::isEmpty($colsGroup)) return $rows;
           if (ObjectUtil::isEmpty($colsFormat)) $colsFormat = array();
           if (ObjectUtil::isEmpty($colsGroup)) $colsGroup = array();

           $data = array();
           // sacamos un muestreo de la data del grid para ver como ha sido expresado cada record
           $muestreo = $rows[0];

           $cols = $cols = $this->getColsGroup($muestreo, $colsGroup);

           if (is_object($muestreo)){
               foreach ($rows as $record){
                   $data[] = $this->getFormatPropertys($record, $colsFormat, $colsGroup, $cols);
               }
           }elseif(is_array($muestreo)){
               foreach ($rows as $record){
                   $data[] = $this->getFormatElementRow($record, $colsFormat, $colsGroup, $cols);
               }
           }
           return $data;
       }

       /**
        * Obtiene el valor formateado en su tipo según su configuración por empresa
        * 
        * @param type $value
        * @param TipoFormato $format
        * @return string
        */
       public function getValueFormat($value, $format){
           if (ObjectUtil::isEmpty($value)) return '';

           // De momento se quedó en que solo se iban a aplicar los formatos por defecto
           // en adelante se deberia modificar 
           switch ($format){
               case FormatoTipo::DECIMAL_ESTANDAR:
                   return $this->getFormatoDecimal($value);
               case FormatoTipo::DECIMAL_PERSONALIZADO:
                   return $this->getFormatoDecimalPersonalizado($value);
               case FormatoTipo::DECIMAL_PERSONALIZADO_SIGNO:
                   return $this->getFormatoDecimalPersonalizado($value,true);
               case FormatoTipo::DECIMAL_SIN_MILES_ESTANDAR:
                   return $this->getFormatoDecimalSinMiles($value);
               case FormatoTipo::ENTERO_ESTANDAR:
                   return $this->getFormatoEntero($value);
               case FormatoTipo::FECHA_ESTANDAR:
                   return $this->getFormatoFecha($value);
               case FormatoTipo::FECHA_ESTANDAR_SIN_HORA:
                   return $this->getFormatoFechaSinHora($value);
               case FormatoTipo::ARRAY_ESTANDAR:
                   return explode(',', $value);
               case FormatoTipo::ARRAY_DE_ARRAY_ESTANDAR:
                   return array(explode(',', $value));
               case FormatoTipo::ARRAY_DE_ARRAY_DECIMAL:
                   $arrNros=explode(',', $value);
                   foreach ($arrNros as $key => $value) {
                       $arrNros[$key] = $this->getValueFormat($value, FormatoTipo::DECIMAL_ESTANDAR);
                   }
                   return array($arrNros);
               case FormatoTipo::ARRAY_DE_ARRAY_DECIMAL_SIN_MILES:
                   $arrNros=explode(',', $value);
                   foreach ($arrNros as $key => $value) {
                       $arrNros[$key] = $this->getValueFormat($value, FormatoTipo::DECIMAL_SIN_MILES_ESTANDAR);
                   }
                   return array($arrNros);
               case FormatoTipo::HTML_ESTANDAR:
                   return htmlspecialchars($value);
               default :
                   return $value;
           }
       }

       public function getFormatoFecha($value){
           return DateUtil::getDateTime($value, $this->formato_fecha);
       }

       public function getFormatoFechaSinHora($value){
           return DateUtil::getDateTime($value, $this->formato_fecha_sin_hora);
       }

       public function getFormatoDecimal($value){
           return (is_numeric($value))?number_format($value, $this->cantidad_decimales): 0;
       }

       public function getFormatoDecimalPersonalizado($value,$verSigno=false,$nro_decimales=-1){
           if ($nro_decimales==-1) $nro_decimales=$this->cantidad_decimales_personalizado;
           if (!is_numeric($value)) $value=0;

           return (($verSigno && $value>0)?'+':'') . number_format($value, $nro_decimales);      
       }

       public function getFormatoDecimalSinMiles($value){
           return (is_numeric($value))?number_format($value, $this->cantidad_decimales,'.',''): 0;
       }

       public function getFormatoEntero($value){
           return (is_numeric($value))?number_format($value, 0): 0;
       }
    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="funciones-de-apoyo">
    
    private function getColsGroup($row, $colsGroup){
        $cols = array();
        foreach ($colsGroup as $columna => $grupo){
            if (!in_array($grupo, $cols) && array_key_exists($columna, $row)) $cols[] = $grupo;
        }
        return $cols;
    }
    
    private function getFormatElementRowToObject($record, $colsFormat, $colsGroup, $cols){
        $obj = new stdClass();
        
        foreach ($record as $columna=>$valor){
            $obj->{$columna} = $valor;
        }
        return $this->getFormatPropertys($obj, $colsFormat, $colsGroup, $cols);
    }
    
    private function getFormatElementRow($record, $colsFormat, $colsGroup, $cols){
        // aplicamos el formato
        foreach($colsFormat as $columna=>$formato){
            $record[$columna] = $this->getValueFormat($record[$columna], $formato);
        }
        // Creamos los nuevos objetos que agruparan una o más columnas y lo agregamos a nuestra fila
        foreach ($cols as $objeto){
            $record[$objeto] = new stdClass();
        }
        // Seteamos los valores a cada objeto stdclass que creamos anteriormente
        foreach ($colsGroup as $columna=>$objeto){
            $ncolumna = str_replace($objeto."_", "", $columna);
            $record[$objeto]->{$ncolumna} = $record[$columna];
            unset($record[$columna]);
        }
        
        return $record;
    }

    private function getFormatPropertys($record, $colsFormat, $colsGroup, $cols){
        // aplicamos el formato
        foreach($colsFormat as $columna=>$formato){
            $record->{$columna} = $this->getValueFormat($record->{$columna}, $formato);
        }
        // Creamos los nuevos objetos que agruparan una o más columnas y lo agregamos a nuestra fila
        foreach ($cols as $objeto){
            $record->{$objeto} = new stdClass();
        }
        // Seteamos los valores a cada objeto stdclass que creamos anteriormente
        foreach ($colsGroup as $columna=>$objeto){
            $ncolumna = str_replace($objeto."_", "", $columna);
            $record->{$objeto}->{$ncolumna} = $record->{$columna};
            unset($record->{$columna});
        }

        return $record;
    }
// </editor-fold>
}

?>
