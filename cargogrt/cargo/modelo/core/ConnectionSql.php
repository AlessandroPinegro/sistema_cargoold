<?php

require_once __DIR__ . '/../exceptions/ModeloException.php';
require_once __DIR__ . '/../../util/Util.php';
require_once __DIR__ . '/../../util/config/ConfigGlobal.php';

class ConnectionSql {

    /**
     *
     * @var PDO
     */
    private $con;
//    private $time_zone = '-05:00';
    //Variable de Comando
    private $sp_name;
//    private $sp_fields;
    private $sp_params;
//    private $sp_order;
//    private $sp_where;
//    private $sp_where_agrupador;
    //Variables internas de la clase
    public $last_query = '';
//    public $last_error = array();
    public $has_error = FALSE;
    public $has_upper = TRUE;

//    public $last_id = 0;
    //Objecto de instancia


    public function __construct() {
        $this->connect();
    }

    public function __destruct() {
        $this->clearConnection();
//        $this->destruir();
    }

    public function clearConnection() {
        $this->con = NULL;
    }

    private function connect() {
        try {
            $host = ConfigGlobal::PARAM_DB_HOST;
            $dbname = ConfigGlobal::PARAM_DB_NAME;
            $username = ConfigGlobal::PARAM_DB_USER;
            $password = ConfigGlobal::PARAM_DB_PASS;
            
            $connectionInfo = array("Database" => $dbname, "UID" => $username, "PWD" => $password, "CharacterSet" => "UTF-8", "ReturnDatesAsStrings" => true);

            $this->con = sqlsrv_connect($host, $connectionInfo);

            if (!$this->con) {
                throw new ModeloException("No fue posible realizar la conexión con la base de datos");

//                $errores = sqlsrv_errors();
//                var_dump($errores);
            }
        } catch (Exception $e) {
            throw new ModeloException("No fue posible realizar la conexión con la base de datos... " . $e->getMessage());
        }
    }

    public function getConeccion() {
        return $this->con;
    }

    public function commandPrepare($sp_name) {
        $this->sp_name = $sp_name;
        $this->sp_params = array();
    }

    function commandAddParameter($key, $value, $has_upper = true) {
        $this->sp_params[$key] = $this->formatQuoteValue($value, $has_upper);
    }

    private function formatQuoteValue($v, $has_upper) {
        switch (gettype($v)) {
            case "integer":
            case "double":
            case "boolean":
                return $v;
            case "NULL":
                return null;
            case "string":
//                $v = mysql_real_escape_string($v);
//                return strtoupper($v);    
                if ($this->has_upper && $has_upper) {
                    return mb_strtoupper($v, 'UTF-8');
                } else {
                    return $v;
                }

            default :
        }
    }

    function commandGetData() {
        try {
            $this->has_error = FALSE;
//            $this->last_query = $this->sp_name;
            $strParams = "";

            for ($iParams = count($this->sp_params); $iParams > 0; $iParams--) {
                $strParams = $strParams . "?,";
            }
            $strParams = substr($strParams, -1 * strlen($strParams), (strlen($strParams) - 1));

//            $this->connect();
            $sentenciasSp = "{call $this->sp_name($strParams)}";

            //---- SQL ----
            $params = array();

            foreach ($this->sp_params as $index => $item) {
                array_push($params, $item);
            }

            /* Execute the query. */
            if (Configuraciones::SHOW_ERROR_LOG) {
                $this->showQuerySentence($sentenciasSp, $params);
            }
            $stmt = sqlsrv_prepare($this->con, $sentenciasSp, $params);

            if (sqlsrv_execute($stmt) === false) {
                $mensajeError = $this->obtenerMensajeError();
                throw new ModeloException('Error al ejecutar el procedimiento ' . $this->sp_name . ' ' . $mensajeError);
//                die(print_r(sqlsrv_errors(), true));
            }

            $data = array();
            do {
                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    // Loop through each result set and add to result array
                    array_push($data, $row);
//                    array_push($data, array_map('strtoupper', $row));
                }
            } while (sqlsrv_next_result($stmt));

            /* Free statement and connection resources. */
            sqlsrv_free_stmt($stmt);
//            sqlsrv_close($this->con);
            //---- FIN SQL ----

            return $data;
        } catch (Exception $e) {
            $this->has_error = TRUE;
            $this->last_error = $e->getMessage();
            throw new ModeloException($e->getMessage() . '. Procedure executed:' . $this->sp_name);
        }
    }

    function obtenerMensajeError() {
        $errores = sqlsrv_errors();

        $errorCadena = '';
        if (!ObjectUtil::isEmpty($errores)) {
            foreach ($errores as $error) {
//                $errorCadena .= "SQLSTATE: " . $error['SQLSTATE'] . "<br/>";
//                $errorCadena .= "Código: " . $error['code'] . "<br/>";
                $errorCadena .= "Mensaje: " . $error['message'] . "<br/>";
            }
        }

        return $errorCadena;
    }

    function beginTransaction() {
        sqlsrv_begin_transaction($this->con);
    }

    function rollbackTransaction() {
        sqlsrv_rollback($this->con);
    }

    function commitTransaction() {
        sqlsrv_commit($this->con);
    }

    private function showQuerySentence(string $sentenciasSp, array $params) {
        error_log("");
        error_log("Ejecutando sp: " . $sentenciasSp);
        error_log("Parametros sp: " . json_encode($params));
    }

}
