<?php

/**
 * Configurations: Constantes que comparten tanto la vista como el controlador
 * @version 1.0
 * @author Christopher Heredia <cheredia@imaginatecperu.com>
 */
require_once __DIR__ . '/config/ConfigGlobal.php';

class Configuraciones {

    // *******************************************************************************
    // - CONFIGURACION A CAMBIAR EN CADA SITIO WEB.
    // *******************************************************************************
    const COOKIE_NAME_SID = "imaginatecperu_ittsa_sid";
    // *******************************************************************************

    const TIME_OUT = 2592000; //60*60*24*30 = 2'592,000 = 30 dias
    const TIPO_SUPERUSUARIO = 0;
    const SBS_DEBUG = "itc-debug";
    const PARAM_SID = "param_sid";
    const PARAM_USU = "param_usu";
    const PARAM_TAG = "tag";
    const PARAM_COD_AD = "param_cod_ad";
    const SHOW_ERROR_LOG = FALSE; # Muestra los parametros de ejecucion en los sp
    const DEFAULT_CULTURE = 'es_pe';
    const PARAM_ACCION_NAME = "action_name";
    const PARAM_USUARIO_TOKEN = "usuario_token";
    const PARAM_ACCION_LOGIN = "autenticarUsuario";
    const PARAM_OPCION_ID = "param_opcion_id";
    const PARAM_CRITERIOS_BUSQUEDA = "criterios_busqueda";
    const PAGE_NUMBER = 'pageNumber';
    const PAGE_SIZE = 'pageSize';
    const PARAM_CONTEXT_FUNCTIONS = 'contextFunctions';
    const PATH_IMG_PDF = "controlador/commons/generadorPdf/plantillas/images/";
    const PARAM_COMPONENT_ID = 'componentId'; // Id del componente generado por el desarrollador
    // Representan los parametros que se usaran para obtener los labels dinamicamente
    const PARAM_GET_LABELS = "get_labels";
    const RESPONSE_LABELS = 'labels';
    // Parametros para obtener los labels de varios controles
    const PARAM_GET_LABELS_CONTROLS = 'get_labels_controls'; // parametro donde se pasaran los codigos de los controles de los que se quiere obtener sus labels.
    const PARAM_LABELS_CONTROLS = 'labels_controls'; // parametro donde se pasaran los LABELS de los controles que se especificaron sus codigos.
    const PARAM_GET_ACCIONES_SEGURIDAD = 'get_acciones_seguridad'; // parámetro que se le pasa al controlador para que me devuelva las acciones por el rol de usuario
    const PARAM_CAMPOS_GRILLA = 'configuraciones_campos_grilla'; // Configuraciones del espacio del Control por Espacio de Trabajo que contiene los  Campos a mostrar en la grilla
    const PARAM_CAMPOS_FORMULARIO = 'configuraciones_campos_formulario'; // Configuraciones del espacio del Control por Espacio de Trabajo que contiene los  Campos a mostrar en el formulario
    const PARAM_CONTROLES_MOSTRAR = 'configuraciones_controles_mostrar';  // Configuraciones del espacio del Control por Espacio de Trabajo que contiene los  Controles dependientes a mostrar
    const RESPONSE_CONFIGURACIONES_ESPACIO_TRABAJO = 'configuraciones_espacio_trabajo';
    const RESPONSE_ACCIONES_SEGURIDAD = 'acciones_seguridad';
    const PARAM_TIME_ZONE = 'param_time_zone';
    const CLASIFICACION_TICKET_ESTUDIO = 51; // Enumerado de la clasificaion del ticket
    const CLASIFICACION_TICKET_EVENTO = 46; // Enumerado de la clasificaion del ticket
    const MODO_ESTUDIO_ID = 39;
    const MODO_EVENTO_ID = 38;
    const MENSAJE_ERROR = 'error';
    const MENSAJE_WARNING = 'warning';
    const MENSAJE_INFORMATION = 'info';
    const MENSAJE_OK = 'success';
    const RESPONSE_MENSAJE_EMERGENTE = "response_mensaje_emergente";
    const RESPONSE_MENSAJE_EMERGENTE_MODAL = "response_mensaje_emergente_modal";
    const RESPONSE_MENSAJE_EMERGENTE_MENSAJE = "response_mensaje_emergente_mensaje";
    const UPLOAD_FOLDER = "uploads";
    const UPLOAD_NAME = "upload_file";
    const IMG_PDF_DIR = "vistas/images/netafimboletas.jpg";
    const PARAM_FLAG_DATATABLE = "param_flag_datatable";
    const PARAM_FLAG_SELECT2 = "param_flag_select2";
    const RUTA_MOVIMIENTO = "vistas/com/movimiento/movimiento_listar.php";
    const DOCUMENTO_TIPO_NOTA_PEDIDO_ID = 94;
    const DOCUMENTO_TIPO_GUIA_INTERNA_ID = 95;
//    const SOLICITUDES_DE_PEDIDO_OPCION_ID=122;
//    
    const SOLICITUDES_DE_PEDIDO_OPCION_ID_EMPRESA2 = 230;
    const SOLICITUDES_DE_PEDIDO_OPCION_ID_EMPRESA4 = 273;
    const SOLICITUDES_DE_PEDIDO_OPCION_ID_EMPRESA6 = 290;
    const DOCUMENTO_TIPO_SOLICITUD_COMPRA_ID_EMPRESA2 = 197;
    const DOCUMENTO_TIPO_SOLICITUD_COMPRA_ID_EMPRESA4 = 257;
    const DOCUMENTO_TIPO_SOLICITUD_COMPRA_ID_EMPRESA6 = 289;
    //id de guias internas
    /* 16A	227	  345	 341 -> nombre organizador
      126 	127	  128     129  -> OPCION
      60 	64	  69     70  -> id organizador */
    const GUIA_INTERNA_EMPRESA2_OPCION_ID = 126;
    const GUIA_INTERNA_EMPRESA6_OPCION_ID = 127;
    const GUIA_INTERNA_EMPRESA4_OPCION_ID = 128;
    const GUIA_INTERNA_EMPRESA7_OPCION_ID = 129;
    //actividad id de transferencia monetaria;     
    const ACTIVIDAD_TRANSFERENCIA_INGRESO = 31;
    const ACTIVIDAD_TRANSFERENCIA_EGRESO = 32;
    const CARPETA_SGI_ADMIN = 'sgi_admin';
    const BASE_SGI_ADMIN = 'xbkohbsw_bhdt_ear';
    const OPCION_ID_DUA = 311;
    const DOCUMENTO_TIPO_ID_PERCEPCION = 258;
    const CUENTA_CONTABLE_PRODUCTO = '20111'; //MUESTRA LA CUENTA EN EL COMBO DE CUENTA CONTABLE EN MANTENEDOR DE PRODUCTOS
    const MOVIMIENTO_TIPO_CODIGO_TRANSFERENCIA = 20; //codigo para movimiento tipo de transferencia de un solo paso
    const MOVIMIENTO_TIPO_CODIGO_RECEPCION = 21; //codigo para movimiento tipo de recepcion de un solo paso
    //DOCUMENTO TIPO DATO LISTA
    const DTDL_GUIA_INTERNA_REPOSICION = 351;
    const DTDL_GUIA_INTERNA_PENDIENTE_REPOSICION = 356;
    const REPORTE_COBRANZAS_VENCIDAS_ID = 22;
    const REPORTE_COBRANZAS_POR_VENCER_ID = 21;
    const DIAS_VENCIMIENTO_DEFAULT = 0;
    const DIAS_VENCIMIENTO_PROXIMO = 7;
    const MAXIMO_ARCHIVO = 10000;
    //URL de facturacion electrónica
//    const EFACT_URL = 'http://localhost:36971/EfacturaWsBeta.asmx?WSDL';
//    const EFACT_URL = 'http://apifact.facturasunat.com/EfacturaWsBeta.asmx?WSDL'; //OSE PRUEBAS 
//     const EFACT_URL = 'http://imagina.facturasunat.com/EfacturaWs.asmx?WSDL'; //PRODUCCION
    const EFACT_URL = 'http://imagina.facturasunat.com/EfacturaWsBeta.asmx?WSDL'; //PRUEBAS
    const EFACT_CORREO = 'comprobanteselectronicos@bhdrillingtools.com';
    const EFACT_CONTENEDOR_PDF_OLD = "http://www.wsefact.facturasunat.com/efacturas/prd/";
    const EFACT_CONTENEDOR_PDF = "http://www.wsefact.facturasunat.com/efacturas/beta/20600143361/Pdf/";
//    const EFACT_CONTENEDOR_PDF="http://www.wsefact.facturasunat.com/efacturas/prdp/20600143361/Pdf/" ;

    const SUNAT_CONSULTA_URL = 'http://44.194.84.229/ConsultaSunatWS/ConsultaSunatWS.asmx?WSDL';
    const EFACT_WS_CONTADOR_MAXIMO = 4;
    const CREACION_TOKEN = 'https://www.ittsabus.com/suiteapirest/Usuario/Login';
    const USUARIO_TOKEN = '47046044';
    const PASS_TOKEN = '47046044';
    const CONSULTA_RUC = 'https://www.ittsabus.com/suiteapirest/Empresa/Listar/';
    const CONSULTA_DOCUMENTO = 'https://www.ittsabus.com/suiteapirest/Cliente/Listar/';
//    const EFACT_USER_OSE = 'DRILLING_20600143361';
//    const EFACT_PASS_OSE = '6KVGwhrvdSbYfJ7u';

    const EFACT_USER_OSE = 'DRILLING_20600143361';
    const EFACT_PASS_OSE = 'DRILLING';
    const IGV_PORCENTAJE = 18;
    const FLAG_TRACE = FALSE;
    
    //CONSTANTE PARA WORKFLOW
    const PARAM_WORKFLOW='param_workflow';
    const PARAM_USUARIOX='usuariox';

    // Metodos 
    public static function url_base()
    {

        $url = $_SERVER['REMOTE_ADDR'];
        if (!ConfigGlobal::VALIDACION_IP) {
            return ConfigGlobal::PARAM_URL_HTTP . ConfigGlobal::PARAM_URL_ADD;
        } elseif ($url == ConfigGlobal::PARAM_IP) {
            return ConfigGlobal::PARAM_URL_HTTP . ConfigGlobal::PARAM_URL_ADD;
        } else {
            return ConfigGlobal::PARAM_URL_HTTP_IP . ConfigGlobal::PARAM_URL_ADD_IP;
        }
    }

    public static function url_host()
    {

        $url = $_SERVER['REMOTE_ADDR'];
        if (!ConfigGlobal::VALIDACION_IP) {
            return ConfigGlobal::PARAM_URL_HTTP . ConfigGlobal::PARAM_URL_ADD;
        } elseif ($url == ConfigGlobal::PARAM_IP) {
            return ConfigGlobal::PARAM_URL_HTTP . ConfigGlobal::PARAM_URL_ADD;
        } else {
            return ConfigGlobal::PARAM_URL_HTTP_IP . ConfigGlobal::PARAM_URL_ADD_IP;
        }
    }}

?>