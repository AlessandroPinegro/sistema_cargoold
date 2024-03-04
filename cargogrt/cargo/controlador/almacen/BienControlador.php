<?php

require_once __DIR__ . '/AlmacenIndexControlador.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/BienNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/UnidadNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/EmpresaNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/PersonaNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/OrganizadorNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/BienPrecioNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/MonedaNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/contabilidad/PlanContableNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/contabilidad/CentroCostoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/contabilidad/ActivoFijoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/contabilidad/SunatTablaNegocio.php';
require_once __DIR__ . '/../../util/Configuraciones.php';

require_once __DIR__ . '/../../util/ReaderExcel.php';

class BienControlador extends AlmacenIndexControlador {

    public function getDataGridBienTipo() {
        return BienNegocio::create()->getDataBienTipo();
    }

    public function insertBienTipo() {
        $descripcion = $this->getParametro("descripcion");
        $codigo = $this->getParametro("codigo");
        $comentario = $this->getParametro("comentario");
        $estado = $this->getParametro("estado");
        $bienTipoPadreId = $this->getParametro("bienTipoPadreId");
        $usu_creacion = $this->getUsuarioId();
        $codigoSunatId = $this->getParametro("codigoSunatId");
        $codigoSunatId2 = $this->getParametro("codigoSunatId2");
        return BienNegocio::create()->insertBienTipo($codigo, $descripcion, $comentario, $estado, $usu_creacion, $bienTipoPadreId, $codigoSunatId, $codigoSunatId2);
    }

    public function getBienTipo() {
        $id_bien_tipo = $this->getParametro("id_bien_tipo");
        $resultado = new stdClass();
        $resultado->dataBienTipo = BienNegocio::create()->getBienTipo($id_bien_tipo);
        $resultado->dataBienTipoPadres = BienNegocio::create()->obtenerBienTipoPadresDisponibles($id_bien_tipo);
        return $resultado;
    }

    public function updateBienTipo() {
        $id_bien_tipo = $usu_nombre = $this->getParametro("id_bien_tipo");
        $descripcion = $this->getParametro("descripcion");
        $codigo = $this->getParametro("codigo");
        $comentario = $this->getParametro("comentario");
        $estado = $this->getParametro("estado");
        $bienTipoPadreId = $this->getParametro("bienTipoPadreId");
        $codigoSunatId = $this->getParametro("codigoSunatId");
        $codigoSunatId2 = $this->getParametro("codigoSunatId2");
        return BienNegocio::create()->updateBienTipo($id_bien_tipo, $descripcion, $codigo, $comentario, $estado, $bienTipoPadreId, $codigoSunatId, $codigoSunatId2);
    }

    public function deleteBienTipo() {
        $id_bien_tipo = $this->getParametro("id_bien_tipo");
        $nom = $this->getParametro("nom");
        return BienNegocio::create()->deleteBienTipo($id_bien_tipo, $nom);
    }

    public function darDeBajaActivoFijo() {
        $bienId = $this->getParametro("bien_id");
        $periodoId = $this->getParametro("periodo_id");
        $fechaContable = $this->getParametro("fecha_contable");
        $cuentaContable = $this->getParametro("cuenta_contable");
        $usuarioId = $this->getUsuarioId();
        $this->setTransaction();
        return ActivoFijoNegocio::create()->darDeBajaActivoFijo($bienId, $periodoId, $usuarioId, $cuentaContable, $fechaContable);
    }

    public function cambiarTipoEstado() {
        $id_estado = $this->getParametro("id_estado");
        return BienNegocio::create()->cambiarTipoEstado($id_estado);
    }

    public function getComboEmpresaTipo() {
        $id_tipo = $this->getParametro("id_tipo");
        return EmpresaNegocio::create()->getDataEmpresaBienTipo($id_tipo);
        /*
        if ($id_tipo == null) {
            return EmpresaNegocio::create()->getDataEmpresa($id_tipo);
        } else {
            return EmpresaNegocio::create()->getDataEmpresaBienTipo($id_tipo);
        }*/
    }

    public function getDataGridBien() {
        $usuarioCreacion = $this->getUsuarioId();
        $empresaId = $this->getParametro("empresaId");
        $resultado = BienNegocio::create()->getDataBien($usuarioCreacion, $empresaId);

        return $resultado;
    }

    public function insertBien() {
        $this->setTransaction();
        $descripcion = $this->getParametro("descripcion");
        $codigo = $this->getParametro("codigo");
        $tipo = $this->getParametro("tipo");
        $estado = $this->getParametro("estado");
        $comentario = $this->getParametro("comentario");
        $empresa = $this->getParametro("empresa");
        $file = $this->getParametro("file");
        $unidad_tipo = $this->getParametro("unidad_tipo");
        $usu_creacion = $this->getUsuarioId();
        $unidad_control_id = $this->getParametro("unidad_control_id");
        $largo = $this->getParametro("largo");
        $ancho = $this->getParametro("ancho");
        $alto = $this->getParametro("alto");
        $peso_volumetrico = $this->getParametro("peso_volumetrico");

        return BienNegocio::create()->insertBien($descripcion, $codigo, $tipo, $estado, $usu_creacion, $comentario,
                        $empresa, $file, $unidad_tipo, $unidad_control_id, $largo, $ancho, $alto, $peso_volumetrico);
    }

    public function getBien() {
        $id_bien = $this->getParametro("id_bien");
        return BienNegocio::create()->getBien($id_bien);
    }

    public function updateBien() {
        $this->setTransaction();
        $id_bien = $this->getParametro("id_bien");
        $descripcion = $this->getParametro("descripcion");
        $codigo = $this->getParametro("codigo");
        $tipo = $this->getParametro("tipo");
        $estado = $this->getParametro("estado");
        $comentario = $this->getParametro("comentario");
        $empresa = $this->getParametro("empresa");
        $file = $this->getParametro("file");
        $unidad_tipo = $this->getParametro("unidad_tipo");
        $largo = $this->getParametro("largo");
        $ancho = $this->getParametro("ancho");
        $alto = $this->getParametro("alto");
        $peso_volumetrico = $this->getParametro("peso_volumetrico");
        $usuarioId = $this->getUsuarioId();
        $unidad_control_id = $this->getParametro("unidad_control_id");

        return BienNegocio::create()->updateBien($id_bien, $descripcion, $codigo, $tipo, $estado, $comentario, $empresa,
                        $file, $unidad_tipo, $usuarioId, $unidad_control_id, $largo, $ancho, $alto, $peso_volumetrico);
    }

    public function deleteBien() {
        $id_bien = $this->getParametro("id_bien");
        $nom = $this->getParametro("nom");
        return BienNegocio::create()->deleteBien($id_bien, $nom);
    }

    public function getAllBienTipo() {
        return BienNegocio::create()->getAllBienTipo();
    }

    public function cambiarEstado() {
        $id_estado = $this->getParametro("id_estado");
        return BienNegocio::create()->cambiarEstado($id_estado);
    }

    public function getAllEmpresa() {
        $usuarioId = $this->getUsuarioId();
        return EmpresaNegocio::create()->getAllEmpresaByUsuarioId($usuarioId);
    }

    public function getAllEmpresaImport() {
        $usuarioId = $this->getUsuarioId();
//        return EmpresaNegocio::create()->getAllEmpresaByUsuarioId($usuarioId);
        return EmpresaNegocio::create()->getEmpresaActivas();
    }

    public function obtenerConfiguracionesIniciales() {
        $usuarioId = $this->getUsuarioId();
        $bienId = $this->getParametro("bienId");
        $bienTipoId = $this->getParametro("bienTipoId");
        $empresaId = $this->getParametro("empresaId");
        $respuesta = new stdClass();
        // Obtengo las configuraciones comunes 
        $respuesta->unidadMedidaTipo = ($bienTipoId == -1) ? UnidadNegocio::create()->obtenerUnidadMedidaTipoXId(-1) : UnidadNegocio::create()->obtenerUnidadMedidaTipo(); // UnidadMedidaTipo
        //$respuesta->empresa = EmpresaNegocio::create()->getAllEmpresaByUsuarioId($usuarioId); // Empresas
        $respuesta->empresa = EmpresaNegocio::create()->getEmpresaActivas(); //todas las empresas
        $respuesta->bienTipo = ($bienTipoId == -1) ? BienNegocio::create()->obtenerBienTipoXId($bienTipoId) : BienNegocio::create()->obtenerBienTipo(); // BienTipo 
        $respuesta->bien = ($bienId > -2) ? BienNegocio::create()->getBien($bienId) : null; // > -2 , porque el id de comment = -1
        $respuesta->unidadMedidaUnidades = UnidadNegocio::create()->getUnidad(-1);

        return $respuesta;
    }

    public function obtenerUnidadControl() {

        $unidadMedidaTipoId = $this->getParametro("id_unidad_medida_tipo");
        $respuesta = new stdClass();
        $respuesta->unidadMedida = BienNegocio::create()->obtenerUnidadControlXUnidadMedidaTipoId($unidadMedidaTipoId);

        return $respuesta;
    }

    public function getAllUnidadMedidaTipoCombo() {
        return BienNegocio::create()->getAllUnidadMedidaTipoCombo();
    }

    public function getComboEmpresaAll() {
        return EmpresaNegocio::create()->getAllEmpresaByUsuarioId(null);
    }

    public function importBien() {
        $this->setTransaction();
        $error_xml = false;

        $file = $this->getParametro("file");
        $usuarioCreacion = $this->getUsuarioId();
        $empresaId = $this->getParametro("empresa_id");

        $decode = Util::base64ToImage($file);

        $direccion = __DIR__ . '/../../util/formatos/subidaBien.xlsx';
        if (file_exists($direccion)) {
            unlink($direccion);
        }
        file_put_contents($direccion, $decode);

        $fileExcel = '/formatos/subidaBien.xlsx';

        //validar que la cabecera del excel importado sea el mismo que la cabecera del formato.
        $cabeceraImporte = ImportacionExcel2::getCabeceraExcelBien("/formatos/subidaBien.xlsx");
        $cabeceraFormato = ImportacionExcel2::getCabeceraExcelBien("/formatos/formato_bien.xlsx");

        if ($cabeceraImporte != $cabeceraFormato) {
            throw new WarningException("Formato de importación incorrecto.");
        }

        $bienes = ImportacionExcel2::importExcelBien($fileExcel, $usuarioCreacion);

        $error = 0;
        $numErrores = 0;
        foreach ($bienes as $key => $value) {
            if ($value["0"] == 1) {
                $error = 1;
                $numErrores = $numErrores + 1;
            }
        }

        if ($error == 0) {
            return "Se importaron correctamente todas las filas";
        } else {
            $text = "Se detectaron " . $numErrores . " filas con errores";
            $errores = $text . "<br><br>No fue posible importar una o varias filas:<br>";

            $arrayAux = array();
            foreach ($bienes as $valor) {
                if ($valor[0] != 0) {
                    array_push($arrayAux, $valor);
                }
            }

            $excel = ImportacionExcel2::writeErroresBien($arrayAux, "errores_articulos",);
            if (strlen($excel) > 0) {
                $errores .= "<br><p><a href='util/$excel'>"
                        . "<div class='alert alert-danger' style='cursor : pointer; text-align:center;'>Descargue el documento de errores con el detalle aquí</div>"
                        . "</a></p>";
            }
            return $errores;
        }
    }

    public function getFormatoImportar() {
        $base = __DIR__ . '/../../util/formatos/formato_bien_base.xls';
        $path = __DIR__ . '/../../util/formatos/formato_bien.xls';
        $objPHPExcel = PHPExcel_IOFactory::load($base);
        $objWorksheet = $objPHPExcel->getActiveSheet();

        $colProv = "N"; //antes L
        $organizadores = OrganizadorNegocio::create()->getDataOrganizador();
        foreach ($organizadores as $organizador) {
            if ($organizador["organizador_padre"] != null) {
                $nombre = "Stock" . $organizador["a_descripcion"];
                $objWorksheet->insertNewColumnBefore($colProv, 1);
                $objWorksheet->duplicateStyle($objWorksheet->getStyle('M1'), $colProv . '1'); //antes K1
                $objWorksheet->getCell($colProv . "1")->setValue($nombre);
                $objWorksheet->getColumnDimension($colProv)->setWidth(15);
                $colProv++;
            }
        }

        $moneda = MonedaNegocio::create()->obtenerComboMoneda();
        $monedaLista = '';
        foreach ($moneda as $item) {
            $descripcion = $item['descripcion'];
            if ($monedaLista != '') {
                $descripcion = ',' . $descripcion;
            }
            $monedaLista = $monedaLista . $descripcion;
        }
        $monedaLista = '"' . $monedaLista . '"';

        for ($i = 2; $i <= 50; $i++) {
            $objValidation = $objPHPExcel->getActiveSheet()->getCell('K' . $i)->getDataValidation();
            $objValidation->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
            $objValidation->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
            $objValidation->setAllowBlank(false);
            $objValidation->setShowInputMessage(true);
            $objValidation->setShowErrorMessage(true);
            $objValidation->setShowDropDown(true);
            $objValidation->setErrorTitle('Error');
            $objValidation->setError('El valor no está en la lista.');
//           $objValidation->setPromptTitle('Pick from list');
//           $objValidation->setPrompt('Please pick a value from the drop-down list.');
//           $objValidation->setFormula1('"Item A,Item B,Item C"');
            $objValidation->setFormula1($monedaLista);
        }

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save($path);
        return true;
    }

    public function exportarBienExcel() {
        $usuarioId = $this->getUsuarioId();
        $empresaId = $this->getParametro("empresaId");
        return BienNegocio::create()->exportarBienExcel($usuarioId, $empresaId);
    }

    ///motivo de salida del bien 
    public function getDataGridBienMotivoSalida() {
        return BienNegocio::create()->getDataBienMotivoSalida();
    }

    public function insertBienMotivoSalida() {
        $descripcion = $this->getParametro("descripcion");
        $codigo = $this->getParametro("codigo");
        $comentario = $this->getParametro("comentario");
        $estado = $this->getParametro("estado");
        $usu_creacion = $this->getUsuarioId();
        return BienNegocio::create()->insertBienMotivoSalida($codigo, $descripcion, $comentario, $estado, $usu_creacion);
    }

    public function getBienMotivoSalida() {
        $id = $this->getParametro("id");
        return BienNegocio::create()->getBienMotivoSalida($id);
    }

    public function updateBienMotivoSalida() {
        $id = $usu_nombre = $this->getParametro("id");
        $descripcion = $this->getParametro("descripcion");
        $codigo = $this->getParametro("codigo");
        $comentario = $this->getParametro("comentario");
        $estado = $this->getParametro("estado");
        return BienNegocio::create()->updateBienMotivoSalida($id, $descripcion, $codigo, $comentario, $estado);
    }

    public function deleteBienMotivoSalida() {
        $id = $this->getParametro("id");
        $nom = $this->getParametro("nom");
        return BienNegocio::create()->deleteBienMotivoSalida($id, $nom);
    }

    public function cambiarBienMotivoSalidaEstado() {
        $id_estado = $this->getParametro("id_estado");
        return BienNegocio::create()->cambiarBienMotivoSalidaEstado($id_estado);
    }

    // modal proveedores
    public function obtenerComboProveedores() {
        //$data=PersonaNegocio::create()->obtenerComboPersonaXPersonaClaseId(-1);
        return PersonaNegocio::create()->obtenerComboPersonaProveedores();
    }

    // bien tipo padre
    public function obtenerBienTipoPadres() {
        return BienNegocio::create()->obtenerBienTipoPadres();
    }

    public function obtenerBienTipoPadresDisponibles() {
        $bienTipoId = $this->getParametro("bienTipoId");
        $data = BienNegocio::create()->obtenerBienTipoPadresDisponibles($bienTipoId);
        return $data;
    }

    public function obtenerConfiguracionesInicialesBienTipo() {
        $bienTipoId = $this->getParametro("bienTipoId");
        return BienNegocio::create()->obtenerConfiguracionesInicialesBienTipo($bienTipoId);
    }

}
