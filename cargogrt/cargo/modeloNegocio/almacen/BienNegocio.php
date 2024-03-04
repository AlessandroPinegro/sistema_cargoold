<?php

session_start();
require_once __DIR__ . '/../../modelo/almacen/Bien.php';
require_once __DIR__ . '/../../modelo/almacen/BienTipo.php';
require_once __DIR__ . '/../../modelo/almacen/UnidadMedida.php';
require_once __DIR__ . '/../../modelo/almacen/Unidad.php';
require_once __DIR__ . '/../../modelo/almacen/MovimientoBien.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
//require_once __DIR__ . '/../../util/PHPExcel/PHPExcel.php';
require_once __DIR__ . '/OrganizadorNegocio.php';
require_once __DIR__ . '/PersonaNegocio.php';
require_once __DIR__ . '/OrganizadorNegocio.php';
require_once __DIR__ . '/UnidadNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/contabilidad/SunatTablaNegocio.php';
require_once __DIR__ . '/EmailPlantillaNegocio.php';
require_once __DIR__ . '/EmailEnvioNegocio.php';

//require_once __DIR__ . '/../../modeloNegocio/almacen/barcode.inc.php';

class BienNegocio extends ModeloNegocioBase
{

    const PRECIO_COMPRA = 1;
    const PRECIO_VENTA = 2;
    const PARAMETRO_DESCUENTO = 0.36; // En realidad el  descuento es de 64 %
    const PARAMETRO_IGV = 1.18;

    /**
     * 
     * @return BienNegocio
     */
    static function create()
    {
        return parent::create();
    }

    public function getAllBienTipo()
    {
        return Bien::create()->getAllBienTipo();
    }

    public function getDataBienTipo()
    {
        $data = Bien::create()->getDataBienTipo();
        if (!ObjectUtil::isEmpty($data) && is_array($data)) {
            $tamanio = count($data);
            for ($i = 0; $i < $tamanio; $i++) {
                if ($data[$i]['estado'] == 1) {
                    $data[$i]['icono'] = "ion-checkmark-circled";
                    $data[$i]['color'] = "#5cb85c";
                } else {
                    $data[$i]['icono'] = "ion-flash-off";
                    $data[$i]['color'] = "#cb2a2a";
                }
            }
        }
        return $data;
    }

    public function insertBienTipo($codigo, $descripcion, $comentario, $estado, $usuarioCreacion, $bienTipoPadreId, $codigoSunatId, $codigoSunatId2)
    {

        $response = Bien::create()->insertBienTipo($codigo, $descripcion, $comentario, $estado, $usuarioCreacion, $bienTipoPadreId, $codigoSunatId, $codigoSunatId2);
        return $response;
    }

    public function getBienTipo($id)
    {
        return Bien::create()->getBienTipo($id);
    }

    public function getBienListar()
    {
        return Bien::create()->getBienListar();
    }

    public function updateBienTipo($id_bien_tipo, $descripcion, $codigo, $comentario, $estado, $bienTipoPadreId, $codigoSunatId, $codigoSunatId2)
    {
        $response = Bien::create()->updateBienTipo($id_bien_tipo, $descripcion, $codigo, $comentario, $estado, $bienTipoPadreId, $codigoSunatId, $codigoSunatId2);

        return $response;
    }

    public function deleteBienTipo($id_bien_tipo, $nom)
    {
        $response = Bien::create()->deleteBienTipo($id_bien_tipo);
        $response[0]['nombre'] = $nom;
        return $response;
    }

    public function cambiarTipoEstado($id_estado)
    {
        $data = Bien::create()->cambiarTipoEstado($id_estado);
        if (!ObjectUtil::isEmpty($data) && is_array($data)) {
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
        }
        return $data;
    }

    //////////////////////////////////////
    //bienes
    /////////////////////////////////////
    public function getDataBien($usuarioId, $empresaId)
    {

        return $response = Bien::create()->getDataBien($usuarioId, $empresaId);
    }

    public function insertarBienPersona($bienId, $proveedorId, $prioridad, $usu_creacion)
    {
        $res = Bien::create()->insertarBienPersona($bienId, $proveedorId, $prioridad, $usu_creacion);

        return $res;
    }

    public function insertBien(
        $descripcion,
        $codigo,
        $tipo,
        $estado,
        $usu_creacion,
        $comentario,
        $empresa,
        $file,
        $unidad_tipo,
        $unidad_control_id,
        $largo,
        $ancho,
        $alto,
        $peso_volumetrico
    ) {

        if ($tipo == -1) {
            $unidad_tipo[0] = -1;
        }

        $response = Bien::create()->insertBien(
            $descripcion,
            $codigo,
            $tipo,
            $estado,
            $usu_creacion,
            $comentario,
            $largo,
            $ancho,
            $alto,
            $peso_volumetrico
        );

        if ($response[0]['vout_exito'] == 0) {
            throw new WarningException($response[0]['vout_mensaje']);
        }
        $bienId = $response[0]['id'];

        $decode = Util::base64ToImage($file);
        if ($file != null || $file != '') {
            $imagen = $bienId . '.jpg';
            file_put_contents(__DIR__ . '/../../vistas/com/bien/imagen/' . $imagen, $decode);
        }

        if ($response[0]["vout_exito"] == 0) {
            return $response;
        }

        $response_empresa = Empresa::create()->getDataEmpresaTotal(); //2
        $response_unidad_medida = Unidad::create()->getDataUnidadTipo(); //-1
        if (!ObjectUtil::isEmpty($response_empresa) && is_array($response_empresa)) {
            for ($i = 0; $i < count($response_empresa); $i++) {
                $estadoep = 0;
                $id_emp = $response_empresa[$i]['id'];
                for ($j = 0; $j < count($empresa); $j++) {
                    if ($id_emp == $empresa[$j]) {
                        $estadoep = 1;
                    }
                }
                Bien::create()->insertBienEmpresa($bienId, $id_emp, 0, $estadoep, $unidad_control_id);
            }
        }
        if (!ObjectUtil::isEmpty($response_unidad_medida) && is_array($response_unidad_medida)) {
            for ($ii = 0; $ii < count($response_unidad_medida); $ii++) {
                $estadou = 0;
                $id_unidad_tipo = $response_unidad_medida[$ii]['id'];
                for ($jj = 0; $jj < count($unidad_tipo); $jj++) {
                    if ($id_unidad_tipo == $unidad_tipo[$jj]) {
                        $estadou = 1;
                    }
                }
                Bien::create()->insertBienUnidadTipo($bienId, $id_unidad_tipo, $estadou, $usu_creacion);
            }
        }
        return $response;
    }

    public function getBien($id)
    {
        $rutaImagenBien = __DIR__ . '/../../vistas/com/bien/imagen/';
        $extensionImagen = ".jpg";
        $imagenPorDefecto = "bienNone";

        $data = Bien::create()->getBien($id);
        if (!ObjectUtil::isEmpty($data) && is_array($data)) {
            $tamanio = count($data);
            for ($i = 0; $i < $tamanio; $i++) {
                if ($data[$i]['estado'] == 1) {
                    $data[$i]['icono'] = "ion-checkmark-circled";
                    $data[$i]['color'] = "#5cb85c";
                } else {
                    $data[$i]['icono'] = "ion-flash-off";
                    $data[$i]['color'] = "#cb2a2a";
                }
                /*
            if ($id_bandera != null) {
                $data[$i]['id_bandera'] = $id_bandera;
            }*/

                $imagen = $rutaImagenBien . $data[$i]['imagen'] . $extensionImagen;

                if (file_exists($imagen)) {
                    $data[$i]['imagen'] = $data[$i]['imagen'] . $extensionImagen;
                } else {
                    $data[$i]['imagen'] = $imagenPorDefecto . $extensionImagen;
                }
            }
        }
        return $data;
    }

    public function updateBien(
        $id_bien,
        $descripcion,
        $codigo,
        $tipo,
        $estado,
        $comentario,
        $empresa,
        $file,
        $unidad_tipo,
        $usuarioId,
        $unidad_control_id,
        $largo,
        $ancho,
        $alto,
        $peso_volumetrico
    ) {
        //        throw new WarningException(count($unidad_tipo));

        $decode = Util::base64ToImage($file);
        if ($file != null || $file != '') {
            $imagen = $id_bien . '.jpg';
            $direccion_imagen = __DIR__ . '/../../vistas/com/bien/imagen/' . $imagen;
            unlink($direccion_imagen);
            file_put_contents($direccion_imagen, $decode);
        }

        if ($tipo == -1) {
            $unidad_tipo[0] = -1;
        }

        $response = Bien::create()->updateBien(
            $id_bien,
            $descripcion,
            $codigo,
            $tipo,
            $estado,
            $comentario,
            $largo,
            $ancho,
            $alto,
            $peso_volumetrico
        );

        if ($response[0]['vout_exito'] == 0) {
            throw new WarningException($response[0]['vout_mensaje']);
        }

        if ($response[0]["vout_exito"] == 0) {
            return $response;
        } else {
            $response_empresa = Empresa::create()->getDataEmpresaTotal();
            $response_unidad_medida = Unidad::create()->getDataUnidadTipo();

            if (!ObjectUtil::isEmpty($response_empresa) && is_array($response_empresa)) {

                for ($i = 0; $i < count($response_empresa); $i++) {
                    $estadop = 0;
                    $id_emp = $response_empresa[$i]['id'];

                    for ($j = 0; $j < count($empresa); $j++) {
                        if ($id_emp == $empresa[$j]) {
                            $estadop = 1;
                        }
                    }
                    $res = Bien::create()->updateBienEmpresa($id_bien, $id_emp, $estadop, $unidad_control_id);
                }
            }

            if (!ObjectUtil::isEmpty($response_unidad_medida) && is_array($response_unidad_medida)) {

                for ($ii = 0; $ii < count($response_unidad_medida); $ii++) {
                    $estadou = 0;
                    $id_unidad_tipo = $response_unidad_medida[$ii]['id'];
                    for ($jj = 0; $jj < count($unidad_tipo); $jj++) {
                        if ($id_unidad_tipo == $unidad_tipo[$jj]) {
                            $estadou = 1;
                        }
                    }
                    Bien::create()->updateBienUnidadTipo($id_bien, $id_unidad_tipo, $estadou, $usuarioId);
                }
            }
            return $response;
        }
    }

    public function deleteBien($id_bien, $nom)
    {
        $response = Bien::create()->deleteBien($id_bien);
        $response[0]['nombre'] = $nom;
        return $response;
    }

    public function cambiarEstado($id_estado)
    {
        $data = Bien::create()->cambiarEstado($id_estado);

        if (!ObjectUtil::isEmpty($data) && is_array($data)) {
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
        }
        return $data;
    }

    /*
     * funciones para importar un excel
     * 1. importBien
     * 2. importarBienXML
     * 3. isValid
     */

    private function getKeyProveedores($str, $proveedores)
    {
        foreach ($proveedores as $id => $nombre) {
            if (strpos(strtolower($str), strtolower($nombre)) !== false) {
                return $id;
            }
        }
        return false;
    }

    private function easeElement($e)
    {
        $parseUnidad = array("unidad", "und.", "und", "unid", "unid.", "undades");
        $parseBolsas = array("bolsa", "bolsas");
        $parseJuegos = array("jgo.", "jgo", "juego", "juegos");
        $parseKilogs = array("kg.", "kg", "kilos", "kilogramos");
        $parseGramos = array("gr.", "gr", "gramos");
        $parseMetros = array("mts.", "mts.", "mts,", "metros");
        $parsePiezas = array("pza.", "pza,", "pza", "piezas");
        //$parseProvIn = array("BEC", "ELCOPE", "EPLI", "FARCESA", "HUEMURA", "MANELSA", "METICO", "PROMATISA", "SIGELEC", "STAR ELEC");
        //$parseProvee = array("pza.", "pza,", "pza", "piezas");
        $e->unidadcontrol = strtolower($e->unidadcontrol);
        $e->unidadcontrol = str_replace($parseUnidad, "Unidad(es)", $e->unidadcontrol);
        $e->unidadcontrol = str_replace($parseBolsas, "Bolsa(s)", $e->unidadcontrol);
        $e->unidadcontrol = str_replace($parseJuegos, "Juego(s)", $e->unidadcontrol);
        $e->unidadcontrol = str_replace($parseKilogs, "Kilogramo(s)", $e->unidadcontrol);
        $e->unidadcontrol = str_replace($parseGramos, "Gramo(s)", $e->unidadcontrol);
        $e->unidadcontrol = str_replace($parseMetros, "Metro(s)", $e->unidadcontrol);
        $e->unidadcontrol = str_replace($parsePiezas, "Pieza(s)", $e->unidadcontrol);
        return $e;
    }

    private function easeBien($p)
    {
        $e = new stdClass();
        $e->codigo = trim((string) $p->codigo);
        $e->descripcion = trim((string) $p->descripcion);
        $e->tipoBien = trim((string) $p->tipobien); // $tipo
        $e->tipoUnidad = trim((string) $p->tipounidad);
        $e->cantidadMinima = trim((string) $p->cantidadMinima) * 1; // $cantidad_minina
        $e->unidadControl = trim((string) $p->unidadControl);
        $e->precioCompra = trim((string) $p->precioCompra);
        $e->precioVenta = trim((string) $p->precioVenta);
        $e->prioridades = array(
            1 => trim($p->proveedorprioridad1),
            2 => trim($p->proveedorprioridad2),
            3 => trim($p->proveedorprioridad3),
            4 => trim($p->proveedorprioridad4)
        );
        foreach ($p as $key => $value) {
            if (strpos($key, "stock") !== false) {
                $e->$key = $value;
            }
        }
        return $e;
    }

    public function importaBienXML($xml, $usuarioCreacion, $empresaId)
    {
        return Bien::create()->importBienXML($xml, $usuarioCreacion, $empresaId);
    }

    public function importBien(
        $codigoBien,
        $descripcion,
        $tipoBienCodigo,
        $largo,
        $ancho,
        $alto,
        $pesoComercial,
        $usuarioCreacion
    ) {
        return Bien::create()->importBien(
            $codigoBien,
            $descripcion,
            $tipoBienCodigo,
            $largo,
            $ancho,
            $alto,
            $pesoComercial,
            $usuarioCreacion
        );
    }

    public function exportarBienExcel($usuarioId, $empresaId)
    {
        $estiloTituloReporte = array(
            'font' => array(
                'name' => 'Arial',
                'bold' => true,
                'italic' => false,
                'strike' => false,
                'size' => 10
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'rotation' => 0,
                'wrap' => TRUE
            )
        );

        $estiloTituloColumnas = array(
            'font' => array(
                'name' => 'Arial',
                'bold' => true,
                'size' => 10
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array(
                        'rgb' => '000000'
                    )
                )
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => FALSE
            )
        );

        $estiloTxtInformacion = array(
            'font' => array(
                'name' => 'Arial',
                'size' => 9
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_HAIR,
                    'color' => array(
                        'rgb' => '000000'
                    )
                )
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => FALSE
            )
        );

        $estiloNumInformacion = array(
            'font' => array(
                'name' => 'Arial',
                'size' => 8
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_HAIR,
                    'color' => array(
                        'rgb' => '000000'
                    )
                )
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => FALSE
            )
        );

        $objPHPExcel = new PHPExcel();

        $i = 1;
        $j = 2;
        $objPHPExcel->setActiveSheetIndex(0)
            ->mergeCells('B' . $i . ':F' . $i);

        $objPHPExcel->setActiveSheetIndex()
            ->setCellValue('B' . $i, 'Lista de Productos');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':J' . $i)->applyFromArray($estiloTituloReporte);
        $i += 2;
        $j += 2;

        $response = $this->getDataBien($usuarioId, $empresaId);

        $objPHPExcel->setActiveSheetIndex()
            ->setCellValue('A' . $i, '      ');
        $objPHPExcel->setActiveSheetIndex()
            ->setCellValue('B' . $i, 'Código');
        $objPHPExcel->setActiveSheetIndex()
            ->setCellValue('C' . $i, 'Descripcion');
        $objPHPExcel->setActiveSheetIndex()
            ->setCellValue('D' . $i, 'Tipo Producto');
        $objPHPExcel->setActiveSheetIndex()
            ->setCellValue('E' . $i, 'Tipo Unidad');
        $objPHPExcel->setActiveSheetIndex()
            ->setCellValue('F' . $i, 'Unidad Control');
        $objPHPExcel->setActiveSheetIndex()
            ->setCellValue('G' . $i, 'Largo');
        $objPHPExcel->setActiveSheetIndex()
            ->setCellValue('H' . $i, 'Ancho');
        $objPHPExcel->setActiveSheetIndex()
            ->setCellValue('I' . $i, 'Alto');
        $objPHPExcel->setActiveSheetIndex()
            ->setCellValue('J' . $i, 'Peso Comercial');

        $objPHPExcel->getActiveSheet()->getStyle('B' . $i . ':J' . $i)->applyFromArray($estiloTituloColumnas);

        foreach ($response as $campo) {
            $objPHPExcel->setActiveSheetIndex()
                ->setCellValue('B' . $j, $campo['codigo'])
                ->setCellValue('C' . $j, $campo['b_descripcion'])
                ->setCellValue('D' . $j, $campo['tb_descripcion'])
                ->setCellValue('E' . $j, $campo['unidad_medida_tipo_descripcion'])
                ->setCellValue('F' . $j, $campo['unidad_control'])
                ->setCellValue('G' . $j, round($campo['largo'], 2))
                ->setCellValue('H' . $j, round($campo['ancho'], 2))
                ->setCellValue('I' . $j, round($campo['alto'], 2))
                ->setCellValue('J' . $j, round($campo['peso_volumetrico'], 2));
            $i += 1;
            $j++;

            $objPHPExcel->getActiveSheet()->getStyle('B' . $i . ':J' . $i)->applyFromArray($estiloTxtInformacion);
            $objPHPExcel
                ->getActiveSheet()
                ->getStyle('G' . $i . ':J' . $i)
                ->getNumberFormat()
                ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
        }


        for ($i = 'A'; $i <= 'J'; $i++) {
            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }
        // Renombrar Hoja
        $objPHPExcel->getActiveSheet()->setTitle('Bienes');

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/lista_de_bienes.xlsx');
        return 1;
    }

    //funcion para generar codigo de Barras 
    //    public function generarCodigoBarras()
    //    {
    //        $code_number = '125689365472365458';
    ////        throw new WarningException("hola como estas");
    //        new barCodeGenrator($code_number,0,'barra.gif', 190, 130, true);
    //    }
    //motivo de saluida del bien

    public function getDataBienMotivoSalida($id_bandera = NULL)
    {
        $data = Bien::create()->getDataBienMotivoSalida();
        if (!ObjectUtil::isEmpty($data) && is_array($data)) {
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
        }
        return $data;
    }

    public function insertBienMotivoSalida($codigo, $descripcion, $comentario, $estado, $usuarioCreacion)
    {

        $response = Bien::create()->insertBienMotivoSalida($codigo, $descripcion, $comentario, $estado, $usuarioCreacion);
        if ($response[0]["vout_exito"] == 0) {
            return $response;
        } else {
            return $response;
        }
    }

    public function getBienMotivoSalida($id)
    {
        return Bien::create()->getBienMotivoSalida($id);
    }

    public function updateBienMotivoSalida($id, $descripcion, $codigo, $comentario, $estado)
    {
        $response = Bien::create()->updateBienMotivoSalida($id, $descripcion, $codigo, $comentario, $estado);
        if ($response[0]["vout_exito"] == 0) {
            return $response[0]["vout_mensaje"];
        } else {
            return $response;
        }
    }

    public function deleteBienMotivoSalida($id, $nom)
    {
        $response = Bien::create()->deleteBienMotivoSalida($id);
        $response[0]['nombre'] = $nom;
        return $response;
    }

    public function cambiarBienMotivoSalidaEstado($id_estado)
    {
        $data = Bien::create()->cambiarBienMotivoSalidaEstado($id_estado);
        if (!ObjectUtil::isEmpty($data) && is_array($data)) {
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
        }
        return $data;
    }

    public function getAllUnidadMedidaTipoCombo()
    {
        return Unidad::create()->getDataComboUnidadTipo();
    }

    public function obtenerActivos($empresaId = NULL)
    {
        return Bien::create()->obtenerActivos($empresaId);
    }

    public function obtenerActivosXEmpresaId($empresaId)
    {
        return Bien::create()->obtenerActivosXEmpresaId($empresaId);
    }

    public function obtenerActivosXMovimientoTipoId($empresaId = NULL, $movimientoTipoId)
    {
        return Bien::create()->obtenerActivosXMovimientoTipoId($empresaId, $movimientoTipoId);
    }

    public function obtenerActivosXAgenciasIdXPersonaId($agenciaOrigenId, $agenciaDestinoId, $personaId)
    {
        return Bien::create()->obtenerActivosXAgenciasIdXPersonaId($agenciaOrigenId, $agenciaDestinoId, $personaId);
    }

    public function obtenerActivosStock()
    {
        return Bien::create()->obtenerActivosStock();
    }

    public function obtenerBienXEmpresa($idEmpresa)
    {
        return Bien::create()->obtenerBienXEmpresa($idEmpresa);
    }

    public function obtenerBienKardexXEmpresa($idEmpresa)
    {
        return Bien::create()->obtenerBienKardexXEmpresa($idEmpresa);
    }

    public function obtenerServicioXEmpresa($idEmpresa)
    {
        return Bien::create()->obtenerServicioXEmpresa($idEmpresa);
    }

    public function obtenerBienTipoXEmpresa($idEmpresa)
    {
        return Bien::create()->obtenerBienTipoXEmpresa($idEmpresa);
    }

    public function obtenerBienTipoKardexXEmpresa($idEmpresa)
    {
        return Bien::create()->obtenerBienTipoKardexXEmpresa($idEmpresa);
    }

    public function obtenerStockPorBien($bienId, $empresaId)
    {
        $responseTipoBien = $this->getBien($bienId);
        if ($responseTipoBien[0]['bien_tipo_id'] == -1) {
            return null;
        } else {
            $stockBien = Bien::create()->obtenerStock(NULL, $bienId, NULL, '', '', $empresaId);
            return $stockBien;
        }
    }

    public function obtenerStockBase($organizadorId, $bienId)
    {
        return Bien::create()->obtenerStock($organizadorId, $bienId, NULL, '', '', NULL);
    }

    public function obtenerPrecioPorBien($bienId)
    {
        return $response = Bien::create()->obtenerPrecioPorBien($bienId);
    }

    //funcion para sacar el precio del bien a travez de su codigo
    private function obtenerPrecioSugeridoCompraXCodigo($bienCodigo, $bienId, $usuarioCreacion)
    {

        $bienCodigo = strtoupper($bienCodigo);
        $bandera = 1;
        $precio = 0;
        if (!ObjectUtil::isEmpty($bienCodigo)) {
            $arrayCodigo = explode(" ", trim($bienCodigo));

            if (!ObjectUtil::isEmpty($arrayCodigo[0])) {
                if (strlen(trim($arrayCodigo[0])) == 6) {

                    $alto = substr($arrayCodigo[0], 0, 2);
                    $ancho = substr($arrayCodigo[0], 2, 2);
                    $equivalenciaAlto = substr($arrayCodigo[0], 4, 1);
                    //                    $equivalenciaAlto = strtoupper($equivalenciaAlto);

                    $equivalenciaAncho = substr($arrayCodigo[0], 5, 1);
                    //                    $equivalenciaAncho = strtoupper($equivalenciaAncho);

                    $respuestaEquivalenciaAlto = Bien::create()->obtenerBienEquivalencia($equivalenciaAlto, 1);

                    if ($respuestaEquivalenciaAlto[0]['vout_exito'] == 1) {
                        $valorEquivalenciaAlto = $respuestaEquivalenciaAlto[0]['valor'];

                        $respuestaEquivalenciaAncho = Bien::create()->obtenerBienEquivalencia($equivalenciaAncho, 1);

                        if ($respuestaEquivalenciaAncho[0]['vout_exito'] == 1) {
                            $valorEquivalenciaAncho = $respuestaEquivalenciaAncho[0]['valor'];

                            if (!ObjectUtil::isEmpty($arrayCodigo[1])) {
                                $coeficiente = trim($arrayCodigo[1]);
                                $respuestaCoeficiente = Bien::create()->obtenerBienEquivalencia($coeficiente, 2);

                                if ($respuestaCoeficiente[0]['vout_exito'] == 1) {
                                    $valorCoeficiente = $respuestaCoeficiente[0]['valor'];

                                    $precio = ((($alto + $valorEquivalenciaAlto) * ($ancho + $valorEquivalenciaAncho)) * $valorCoeficiente);
                                    $precio = $precio * self::PARAMETRO_DESCUENTO * self::PARAMETRO_IGV;

                                    $respuesta = Bien::create()->guardarBienPrecio($bienId, $precio, self::PRECIO_COMPRA, $usuarioCreacion);

                                    if ($respuesta[0]['vout_exito'] == 0) {
                                        throw new WarningException("Error al guardar el precio del bien.");
                                    } else {
                                        return $precio;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $precio;
        //        return false;
    }

    function obtenerPrecioSugeridoVenta($bienId, $precioCompra, $agregado_precio_venta, $agregado_precio_venta_tipo, $usuarioCreacion)
    {
        /**
         * $agregado_precio_venta_tipo = puede ser de 2 tipos
         *  1 = importe
         *  2 = porcentaje
         */
        if ($agregado_precio_venta_tipo == 1) {
            $precioVenta = $precioCompra + $agregado_precio_venta;
        } else {
            $precioVenta = $precioCompra + ($precioCompra * ($agregado_precio_venta / 100));
        }

        $respuesta = Bien::create()->guardarBienPrecio($bienId, $precioVenta, self::PRECIO_VENTA, $usuarioCreacion);

        if ($respuesta[0]['vout_exito'] == 0) {
            throw new WarningException("Error al guardar el precio del bien.");
        }
    }

    function obtenerBienMovimientoEmpresa($empresaId)
    {
        return Bien::create()->obtenerBienMovimientoEmpresa($empresaId);
    }

    function obtenerBienXMovimientosActivos()
    {
        return Bien::create()->obtenerBienXMovimientosActivos();
    }

    function obtenerStockOrganizadoresXEmpresa($bienId, $unidadMedida, $movimientoTipoId)
    {

        $arrayStockXOrganizador = array();

        $respuestaOrganizador = OrganizadorNegocio::create()->obtenerXMovimientoTipo($movimientoTipoId);

        foreach ($respuestaOrganizador as $organizador) {

            if (!ObjectUtil::isEmpty($organizador['id'])) {
                $respuestaStockOrganizador = Bien::create()->obtenerStockXOrganizador($bienId, $organizador['id'], $unidadMedida);
            }

            if (!ObjectUtil::isEmpty($respuestaStockOrganizador[0]['stock'])) {
                if ($respuestaStockOrganizador[0]['stock'] > 0) {
                    array_push($arrayStockXOrganizador, $this->getStockXOrganizador($organizador['id'], $organizador['descripcion'], $respuestaStockOrganizador[0]['stock']));
                }
            }
        }
        return $arrayStockXOrganizador;
    }

    private function getStockXOrganizador($organizadorId, $organizadorNombre, $stock)
    {

        $data = new stdClass();
        $data->organizadorId = $organizadorId;
        $data->organizadorDescripcion = $organizadorNombre;
        $data->stock = $stock;

        return $data;
    }

    public function obtenerBienTipo()
    {
        return BienTipo::create()->obtener();
    }

    public function obtenerBienTipoXId($id)
    {
        return BienTipo::create()->obtenerXId($id);
    }

    public function obtenerUnidadControlXUnidadMedidaTipoId($id)
    {
        $unidaMedidasTipos = 0;

        if (!ObjectUtil::isEmpty($id)) {
            $unidaMedidasTipos = $id[0];

            for ($i = 1; $i < count($id); $i++) {
                $unidaMedidasTipos = $id[$i] . "," . $unidaMedidasTipos;
            }
        }

        return UnidadMedida::create()->obtenerUnidadControlXUnidadMedidaTipoId($unidaMedidasTipos);
    }

    public function obtenerCantidadMinima($bienId, $unidadMedidaId)
    {
        return Bien::create()->obtenerBienCantidadMinima($bienId, $unidadMedidaId);
    }

    public function obtenerStockActual($bienId, $organizadorId, $unidadMedidaId, $organizadorDestinoId = null)
    {
        return Bien::create()->obtenerStockActual($bienId, $organizadorId, $unidadMedidaId, $organizadorDestinoId);
    }

    public function obtenerBienActivoXDescripcion($bienDescripcion)
    {
        return Bien::create()->obtenerBienActivoXDescripcion($bienDescripcion);
    }

    public function obtenerBienPersonaXBienId($id)
    {
        return Bien::create()->obtenerBienPersonaXBienId($id);
    }

    public function obtenerActivosFijosXEmpresa($idEmpresa)
    {
        return Bien::create()->obtenerActivosFijosXEmpresa($idEmpresa);
    }

    public function obtenerCantidadMinimaConvertido($bienId, $organizadorId, $unidadMedidaId)
    {
        return Bien::create()->obtenerCantidadMinimaConvertido($bienId, $organizadorId, $unidadMedidaId);
    }

    public function obtenerBienesConStockMenorACantidadMinima($personaId, $organizadorId, $empresaId, $monedaId, $operador)
    {
        return Bien::create()->obtenerBienesConStockMenorACantidadMinima($personaId, $organizadorId, $empresaId, $monedaId, $operador);
    }

    public function obtenerStockTotalXBienIDXUnidadMedidaId($bienId, $unidadMedidaId)
    {
        return Bien::create()->obtenerStockTotalXBienIDXUnidadMedidaId($bienId, $unidadMedidaId);
    }

    public function obtenerMarcas()
    {
        return Bien::create()->obtenerMarcas();
    }

    public function obtenerBienTipoPadres()
    {
        return Bien::create()->getDataBienTipo();
    }

    public function obtenerBienTipoPadresDisponibles($bienTipoId)
    {
        return Bien::create()->obtenerBienTipoPadresDisponibles($bienTipoId);
    }

    public function obtenerMaquinarias()
    {
        return Bien::create()->obtenerMaquinarias();
    }

    public function obtenerConfiguracionesInicialesBienTipo($bienTipoId)
    {
        $respuesta = new stdClass();
        $respuesta->dataSunatDetalle = SunatTablaNegocio::create()->obtenerDetalleXSunatTablaId(5);
        $respuesta->dataSunatDetalle2 = SunatTablaNegocio::create()->obtenerDetalleXSunatTablaId(30);

        if (ObjectUtil::isEmpty($bienTipoId)) {
            $respuesta->dataBienTipoPadres = $this->obtenerBienTipoPadres();
        } else {
            $respuesta->dataBienTipo = $this->getBienTipo($bienTipoId);
            $respuesta->dataBienTipoPadres = $this->obtenerBienTipoPadresDisponibles($bienTipoId);
        }

        return $respuesta;
    }

    public function obtenerStockEntreFechasXBienIdXOrganizadorIdXUnidadMedidaId($bienId, $organizadorId, $unidadMedidaId, $fechaInicial, $fechaFinal, $organizadorDestinoId = null)
    {
        return Bien::create()->obtenerStockEntreFechasXBienIdXOrganizadorIdXUnidadMedidaId($bienId, $organizadorId, $unidadMedidaId, $fechaInicial, $fechaFinal, $organizadorDestinoId);
    }

    public function obtenerPrecioTipoXIndicador($indicador)
    {
        return Bien::create()->obtenerPrecioTipoXIndicador($indicador);
    }

    public function obtenerBienTipoPadre()
    {
        return BienTipo::create()->obtenerBienTipoPadre();
    }

    public function obtenerBienTipoHijosXBienTipoPadreId($bienTipoPadreId)
    {
        $respuesta = array();
        $bienTipoPadreId = Util::convertirArrayXCadena($bienTipoPadreId);
        $data = $this->obtenerBienTipoXBienTipoPadreId($bienTipoPadreId);

        if (!ObjectUtil::isEmpty($data)) {
            $respuesta = $data;
            $respuesta = $this->obtenerBienTipoRecursivo($respuesta, $data);
        }

        foreach ($respuesta as $index => $item) {
            $aux[$index] = $item['codigo'];
        }

        array_multisort($aux, SORT_ASC, $respuesta);

        return $respuesta;
    }

    public function obtenerBienTipoRecursivo($respuesta, $data)
    {

        foreach ($data as $item) {
            $data2 = $this->obtenerBienTipoXBienTipoPadreId($item['id']);

            if (!ObjectUtil::isEmpty($data2)) {
                $respuesta = array_merge($respuesta, $data2);
                $respuesta = $this->obtenerBienTipoRecursivo($respuesta, $data2);
            }
        }

        return $respuesta;
    }

    public function obtenerBienTipoXBienTipoPadreId($bienTipoPadreId)
    {
        return BienTipo::create()->obtenerBienTipoXBienTipoPadreId($bienTipoPadreId);
    }

    public function enviarNotificacionActivosFijosNoInternados()
    {
        $data = Bien::create()->obtenerActivosFijosNoInternados();

        if (!ObjectUtil::isEmpty($data)) {
            $plantilla = EmailPlantillaNegocio::create()->obtenerEmailPlantillaXID(16);
            $correosPlantilla = EmailPlantillaNegocio::create()->obtenerEmailXDestinatario($plantilla[0]["destinatario"], null, null);

            $correos = implode(";", $correosPlantilla);
            //            foreach ($correosPlantilla as $email) {
            //                $correos = $correos . $email . ';';
            //            }
            //dibujando la cabecera
            $dataDetalle = '<table  border=1 cellspacing=0 cellpadding=5 bordercolor="000000"  width="91.7%">
                        <thead>';

            $html = '<tr>';
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Item</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>F.Emisión</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Tipo de documento</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>S/N</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Bien</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>Cantidad</th>";
            $html = $html . "<th style='text-align:center;font-size:14px;line-height:1.5;'>U.M.</th>";
            $html = $html . '</tr>';

            $dataDetalle = $dataDetalle . $html;
            $dataDetalle = $dataDetalle . '<thead>';
            $dataDetalle = $dataDetalle . '<tbody>';

            foreach ($data as $index => $item) {
                $html = '<tr>';
                $html = $html . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . ($index + 1);
                $html = $html . '<td style=\'text-align:center;font-size:14px;line-height:1.5;\'>' . DateUtil::formatearFechaBDAaCadenaVw($item['fecha_emision']);
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['documento_tipo_descripcion'];
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['serie'] . '-' . $item['numero'];
                $html = $html . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . $item['bien_descripcion'];
                $html = $html . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . number_format($item['cantidad'], 2, ".", ",");
                $html = $html . '<td style=\'text-align:center;font-size:14px;line-height:1.5;\'>' . $item['unidad_medida_descripcion'];
                $html = $html . '</tr>';

                $dataDetalle = $dataDetalle . $html;
            }

            $dataDetalle = $dataDetalle . '</tbody></table>';
            $descripcion = 'Detalle de activos fijos pendientes de internar';

            //logica correo:             
            $asunto = $plantilla[0]["asunto"];
            $cuerpo = $plantilla[0]["cuerpo"];

            $cuerpo = str_replace("[|titulo_email|]", 'ACTIVOS FIJOS PENDIENTES DE INTERNAR', $cuerpo);
            $cuerpo = str_replace("[|descripcion|]", $descripcion, $cuerpo);
            $cuerpo = str_replace("[|detalle_programacion|]", $dataDetalle, $cuerpo);

            $res = EmailEnvioNegocio::create()->insertarEmailEnvio($correos, $asunto, $cuerpo, 1, 1);
            //            return $cuerpo;
            return 'Pendiente por aprobar. ' . $res[0]['vout_mensaje'] . ' Id: ' . $res[0]['id'] . ' <br>';
        } else {
            return '';
        }
    }

    public function obtenerMetodosDepreciacion()
    {
        return Bien::create()->obtenerMetodosDepreciacion();
    }

    public function obtenerDepreaciacionPorcentaje()
    {
        return Bien::create()->obtenerDepreaciacionPorcentaje();
    }

    public function obtenerDistribucionXBienId($bienId)
    {
        return Bien::create()->obtenerDistribucionXBienId($bienId);
    }

    public function eliminarDistribucionXBienId($bienId)
    {
        return Bien::create()->eliminarDistribucionXBienId($bienId);
    }

    public function guardarDistribucionXBienId($bienId, $centroCostoId, $porcentaje, $usuarioId)
    {
        return Bien::create()->guardarDistribucionXBienId($bienId, $centroCostoId, $porcentaje, $usuarioId);
    }

    public function actualizarEstadoDepreciado($bienId)
    {
        return Bien::create()->actualizarEstadoDepreciado($bienId);
    }
}
