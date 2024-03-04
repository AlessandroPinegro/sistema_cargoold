var JSType = {};
JSType.UNDEFINED = "undefined";
JSType.NULL = "null";
JSType.BOOLEAN = "boolean";
JSType.NUMBER = "number";
JSType.STRING = "string";
JSType.FUNCTION = "function";
JSType.ARRAY = "array";
JSType.DATE = "date";
JSType.ERROR = "error";
JSType.REGEXP = "regexp";
JSType.OBJECT = "object";

var ClasificacionTipoVerificacion = {};
ClasificacionTipoVerificacion.LISTA = 1;
ClasificacionTipoVerificacion.NUMERICO = 2;

var Align = {};
Align.LEFT = 0;
Align.CENTER = 1;
Align.RIGHT = 2;

var ConfiguracionModo = {};
ConfiguracionModo.EVENTO = 38;
ConfiguracionModo.ESTUDIO = 39;

var ClasificacionTicket = {};
ClasificacionTicket.EVENTO = 46;
ClasificacionTicket.ESTUDIO = 51;

var TipoEspacioTrabajoGrupo = {};
TipoEspacioTrabajoGrupo.PROPIETARIO = 'Propietarios';
TipoEspacioTrabajoGrupo.RESPONSABLE = 'Responsables';
TipoEspacioTrabajoGrupo.PROPIETARIOTAREA = 'PropietariosTarea';
TipoEspacioTrabajoGrupo.RESPONSABLETAREA = 'ResponsablesTarea';
TipoEspacioTrabajoGrupo.LISTACORREOSTICKET = 'ListaCorreosTicket';
TipoEspacioTrabajoGrupo.CLIMALABORAL_HISTORICO_CRITERIOBUSQUEDA = 'CriterioBusquedaClimaLaboral';
TipoEspacioTrabajoGrupo.TAREA_TABRECURSOS = 'RecursosTarea';

var TipoVariable = {};
TipoVariable.NUMERICO = 'var_numerico';
TipoVariable.INCIDENCIA = 'var_incidencia';
TipoVariable.EQUIPO = 'var_equipo';
TipoVariable.CLIMALABORAL = 'var_clima_laboral';
TipoVariable.INDICADOR = 'var_indicador';
TipoVariable.LISTA = 'var_lista';

var CruvasDistribucionTotalizado = {};
CruvasDistribucionTotalizado.MOSTRAR = 1;
CruvasDistribucionTotalizado.OCULTAR = 2;

var NivelAccesoReporte = {};
NivelAccesoReporte.PRIVADO = 1;
NivelAccesoReporte.PUBLICO = 2;

var MostrarCurvas = {};
MostrarCurvas.DISTRIBUCION_PRINCIPAL = 1;
MostrarCurvas.DISTRIBUCION_SECUNDARIA = 2;