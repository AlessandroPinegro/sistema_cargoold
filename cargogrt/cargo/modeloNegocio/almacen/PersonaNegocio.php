<?php

session_start();
require_once __DIR__ . '/../../modelo/almacen/Persona.php';
require_once __DIR__ . '/../../modelo/almacen/Perfil.php';
require_once __DIR__ . '/../../modelo/almacen/PersonaContacto.php';
require_once __DIR__ . '/../../modelo/almacen/Zona.php';
require_once __DIR__ . '/../../modelo/almacen/ConsultaWs.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/../../modeloNegocio/commons/ConstantesNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/contabilidad/SunatTablaNegocio.php';
require_once __DIR__ . '/../../util/config/ConfigGlobal.php';

class PersonaNegocio extends ModeloNegocioBase
{

    /**
     * 
     * @return PersonaNegocio
     */
    static function create()
    {
        return parent::create();
    }

    public function getAllPersonaClase()
    {
        $data = Persona::create()->getAllPersonaClase();
        $tamanio = count($data);
        for ($i = 0; $i < $tamanio; $i++) {
            if ($data[$i]['persona_clase_estado'] == 1) {
                $data[$i]['icono'] = "ion-checkmark-circled";
                $data[$i]['color'] = "#5cb85c";
            } else {
                $data[$i]['icono'] = "ion-flash-off";
                $data[$i]['color'] = "#cb2a2a";
            }
        }
        return $data;
    }

    public function getAllPersonaTipo()
    {
        $data = Persona::create()->getAllPersonaTipo();
        $tamanio = count($data);
        for ($i = 0; $i < $tamanio; $i++) {
            if ($data[$i]['id'] == 2) {
                $data[$i]['ruta'] = "vistas/com/persona/persona_natural_form.php";
            } else {
                $data[$i]['ruta'] = "vistas/com/persona/persona_juridica_form.php";
            }
        }
        return $data;
    }

    public function ExportarPersonaExcel($usuarioId)
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
            ->mergeCells('B' . $i . ':N' . $i);

        //        $response
        // Agregar Informacion
        $objPHPExcel->setActiveSheetIndex()
            ->setCellValue('B' . $i, 'Lista de Personas');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':N' . $i)->applyFromArray($estiloTituloReporte);
        //        $objPHPExcel->getActiveSheet()->setSharedStyle($estiloTituloReporte, "A" . $i . ":C" . $i);
        $i += 2;
        //$j++;
        $j += 2;

        //Código	Descripción	Tipo Unidad	Control	Precio sugerido compra	Precio sugerido venta	Estado	Opciones
        $response = Persona::create()->getDataPersona($usuarioId);

        $objPHPExcel->setActiveSheetIndex()
            ->setCellValue('A' . $i, '      ');
        $objPHPExcel->setActiveSheetIndex()
            ->setCellValue('B' . $i, 'CodId');
        $objPHPExcel->setActiveSheetIndex()
            ->setCellValue('C' . $i, 'Tipo');
        $objPHPExcel->setActiveSheetIndex()
            ->setCellValue('D' . $i, 'Clase');
        $objPHPExcel->setActiveSheetIndex()
            ->setCellValue('E' . $i, 'Nombre');
        $objPHPExcel->setActiveSheetIndex()
            ->setCellValue('F' . $i, 'Apellido Paterno');
        $objPHPExcel->setActiveSheetIndex()
            ->setCellValue('G' . $i, 'Apellido Materno');
        $objPHPExcel->setActiveSheetIndex()
            ->setCellValue('H' . $i, 'Telefono');
        $objPHPExcel->setActiveSheetIndex()
            ->setCellValue('I' . $i, 'Celular');
        $objPHPExcel->setActiveSheetIndex()
            ->setCellValue('J' . $i, 'Email');
        $objPHPExcel->setActiveSheetIndex()
            ->setCellValue('K' . $i, 'Direccion1');
        $objPHPExcel->setActiveSheetIndex()
            ->setCellValue('L' . $i, 'Direccion2');
        $objPHPExcel->setActiveSheetIndex()
            ->setCellValue('M' . $i, 'Direccion3');
        $objPHPExcel->setActiveSheetIndex()
            ->setCellValue('N' . $i, 'Direccion4');
        $objPHPExcel->getActiveSheet()->getStyle('B' . $i . ':N' . $i)->applyFromArray($estiloTituloColumnas);

        //								
        foreach ($response as $campo) {
            $objPHPExcel->setActiveSheetIndex()
                //                ->setCellValue('A' . $i, 'Lista de Bienes')
                ->setCellValue('B' . $j, $campo['codid'])
                ->setCellValue('C' . $j, $campo['tipo'])
                ->setCellValue('D' . $j, $campo['clase'])
                ->setCellValue('E' . $j, $campo['nombre'])
                ->setCellValue('F' . $j, $campo['apellidopaterno'])
                ->setCellValue('G' . $j, $campo['apellidomaterno'])
                ->setCellValue('H' . $j, $campo['telefono'])
                ->setCellValue('I' . $j, $campo['celular'])
                ->setCellValue('J' . $j, $campo['email'])
                ->setCellValue('K' . $j, $campo['direccion_1'])
                ->setCellValue('L' . $j, $campo['direccion_2'])
                ->setCellValue('M' . $j, $campo['direccion_3'])
                ->setCellValue('N' . $j, $campo['direccion_4']);
            //            $objPHPExcel->getActiveSheet()->getStyle('B' . $j)->applyFromArray($estiloTituloColumnas);
            $i += 1;
            $j++;
            //        $objPHPExcel->setActiveSheetIndex()
            //                ->setCellValue('A' . $i, 'No Respondieron')
            //                ->setCellValue('B' . $i, 'dato2');
            //        $i +=1;
            //        $objPHPExcel->getActiveSheet()->getStyle('A' . ($i - 2) . ':A' . $i)->applyFromArray($estiloTituloColumnas);
            $objPHPExcel->getActiveSheet()->getStyle('B' . $i . ':N' . $i)->applyFromArray($estiloTxtInformacion);
            //        $objPHPExcel->getActiveSheet()->getStyle('I' . $i . ':L' . $i)->applyFromArray($estiloNumInformacion);
            //        $objPHPExcel->getActiveSheet()->getStyle('J' . $i . ':J' . $i)->applyFromArray($estiloTxtInformacion);
            //        $i +=1;
            //        $i +=2;
        }


        for ($i = 'A'; $i <= 'N'; $i++) {
            $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
        }
        // Renombrar Hoja
        $objPHPExcel->getActiveSheet()->setTitle('Personas');

        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(__DIR__ . '/../../util/formatos/lista_de_personas.xlsx');
        return 1;
    }

    public function insertPersonaClase($descripcion, $tipo, $estado, $usuarioCreacion)
    {
        $responsePersonaClase = Persona::create()->insertPersonaClase($descripcion, $estado, $usuarioCreacion);
        if ($responsePersonaClase[0]['vout_exito'] == 1) {
            $personaClaseId = $responsePersonaClase[0]['id'];
            $this->savePersonaClaseTipo($tipo, $personaClaseId, $usuarioCreacion);
        }
        return $responsePersonaClase;
    }

    public function cambiarEstadoPersonaClase($id, $estado)
    {
        return Persona::create()->cambiarEstadoPersonaClase($id, $estado);
    }

    public function updatePersonaClase($id, $descripcion, $tipo, $estado)
    {
        $responsePersonaClase = Persona::create()->updatePersonaClase($id, $descripcion, $estado);
        if ($responsePersonaClase[0]['vout_exito'] == 1) {
            $this->savePersonaClaseTipo($tipo, $id, $usuarioCreacion = 0);
        }
        return $responsePersonaClase;
    }

    function savePersonaClaseTipo($tipo, $personaClaseId, $usuarioCreacion)
    {
        Persona::create()->deletePersonaClaseTipo($personaClaseId);
        foreach ($tipo as $tipoId) {
            Persona::create()->savePersonaClaseTipo($tipoId, $personaClaseId, $usuarioCreacion);
        }
    }

    public function importaPersonaXML($xml, $usuarioCreacion, $empresaId)
    {
        return Persona::create()->importaPersonaXML($xml, $usuarioCreacion, $empresaId);
    }

    // para la tabla persona
    //    public function getAllPersona() {
    //        return Persona::create()->getAllPersona();
    //    }

    public function getAllPersona($nombres, $codigo, $tipoPersona, $clasePersona, $elemntosFiltrados, $columns, $order, $start, $usuarioId = 1)
    {
        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];
        $clasePersona = Util::convertirArrayXCadena($clasePersona);
        //        $fechaVencimientoDesde = DateUtil::formatearCadenaACadenaBD($valor['inicio']);;

        return Persona::create()->getAllPersona($nombres, $codigo, $tipoPersona, $clasePersona, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start, $usuarioId);
    }

    public function getCantidadAllPersona($nombres, $codigo, $tipoPersona, $clasePersona, $elemntosFiltrados, $columns, $order, $start, $usuarioId = 1)
    {

        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];
        $clasePersona = Util::convertirArrayXCadena($clasePersona);
        return Persona::create()->getCantidadAllPersona($nombres, $codigo, $tipoPersona, $clasePersona, $columnaOrdenar, $formaOrdenar, $usuarioId);
    }

    public function insertPersona($PersonaTipoIdo, $codigoIdentificacion, $nombre, $apellidoPaterno, $apellidoMaterno, $telefono, $celular, $email, $file, $estado, $usuarioCreacion, $empresa, $clase, $listaContactoDetalle, $listaDireccionDetalle, $codigoSunatId, $codigoSunatId2, $codigoSunatId3, $nombreBCP, $numero_cuenta_bcp, $cci, $listaCentroCostoPersona, $correoAlternativo, $tipoDocumentoId = null,$bandera_recojo_reparto=null ,$bandera_credito_persona=null,$detlicencia,$direccionofc)
    {

        //direcciones antes
        $direccion = '';
        $direccionReferencia = '';

        $decode = Util::base64ToImage($file);
        if ($file == null || $file == '') {
            $imagen = null;
        } else {
            $imagen = $codigoIdentificacion . '.jpg';
            file_put_contents(__DIR__ . '/../../vistas/com/persona/imagen/' . $imagen, $decode);
        }

        $responsePersona = Persona::create()->insertPersona($PersonaTipoIdo, $codigoIdentificacion, $nombre, $apellidoPaterno, $apellidoMaterno, $telefono, $celular, $email, $direccion, $direccionReferencia, $imagen, $estado, $usuarioCreacion, $codigoSunatId, $codigoSunatId2, $codigoSunatId3, $nombreBCP, $numero_cuenta_bcp, $cci, $correoAlternativo, $tipoDocumentoId,$bandera_recojo_reparto,$bandera_credito_persona, $detlicencia,$direccionofc);

        if ($responsePersona[0]["vout_exito"] == 1) {
            $personaId = $responsePersona[0]['id'];
            $this->savePersonaEmpresa($empresa, $personaId, $usuarioCreacion);
            $this->savePersonaClasePersona($clase, $personaId, $usuarioCreacion);

            //            //direccion 1
            //            $res=Persona::create()->guardarPersonaDireccion($personaId,1,$direccion, $usuarioCreacion);
            //            
            //            // direccion 2 -> campo de referencia            
            //            $res2=Persona::create()->guardarPersonaDireccion($personaId,2,$direccionReferencia, $usuarioCreacion);
            //            
            //            // direccion 3            
            //            Persona::create()->guardarPersonaDireccion($personaId,3,$direccion3, $usuarioCreacion);
            //            
            //            // direccion 4           
            //            Persona::create()->guardarPersonaDireccion($personaId,4,$direccion4, $usuarioCreacion);
            //guardar persona direccion
            if (!ObjectUtil::isEmpty($listaDireccionDetalle)) {

                foreach ($listaDireccionDetalle as $index => $item) {

                    if (in_array('direccion', array_keys($item))) {
                        $personaDireccionId = $listaDireccionDetalle[$index]['direccion_id'];
                        $direccionTipoId = $listaDireccionDetalle[$index]['direccion_tipo_id'];
                        $direccionTexto = $listaDireccionDetalle[$index]['direccion'];
                        $zonaId = $listaDireccionDetalle[$index]['zona_id'];
                        $referencia = $listaDireccionDetalle[$index]['referencia'];
                        $latitud = $listaDireccionDetalle[$index]['latitud'];
                        $longitud = $listaDireccionDetalle[$index]['longitud'];
                        $ubigeoId = $listaDireccionDetalle[$index]['ubigeo_id'];
                    } else { 
                        $personaDireccionId = $item[5];
                        $direccionTipo = $item[1];
                        $ubigeoId = $item[2];
                        $direccionTexto = $item[4];
                        $zonaId = $item[6];
                        $referencia = $item[8];
                        $latitud = $item[9] != "" ? $item[9] : null;
                        $longitud = $item[10] != "" ? $item[10] : null;
                        //si existe direccion tipo obtengo el id de lo contrario inserto direccion tipo.
                        $direccionTipo = trim($direccionTipo);
                        $resDireccionTipo = Persona::create()->obtenerDireccionTipoXDescripcion($direccionTipo);

                        if (ObjectUtil::isEmpty($resDireccionTipo)) {
                            $resDireccionTipo = Persona::create()->insertarDireccionTipo($direccionTipo, $usuarioCreacion);
                        }

                        $direccionTipoId = $resDireccionTipo[0]['id'];
                    }


                    $latitud = (ObjectUtil::isEmpty(trim($latitud)) || $latitud == trim("null") ? NULL : $latitud);
                    $longitud = (ObjectUtil::isEmpty(trim($longitud)) || $longitud == trim("null") ? NULL : $longitud);


                    // fin direccion tipo

                    $res = Persona::create()->guardarPersonaDireccion($personaId, ($index + 1), $direccionTexto, $usuarioCreacion, $personaDireccionId, $direccionTipoId, $ubigeoId, $longitud, $latitud, $referencia, $zonaId);

                    if ($res[0]['vout_exito'] == 0) {
                        throw new WarningException("Error al guardar la dirección. " . $res[0]['vout_mensaje']);
                    }
                }
            }
            if (!ObjectUtil::isEmpty($listaCentroCostoPersona)) {
                foreach ($listaCentroCostoPersona as $indice => $item) {
                    $respuestaGuardarPersonaCentroCosto = Persona::create()->guardarPersonaCentroCosto($personaId, $item['centro_costo_id'], $item['porcentaje'], $usuarioCreacion);
                    if ($respuestaGuardarPersonaCentroCosto[0]['vout_exito'] == 0) {
                        throw new WarningException("Error al guardar persona centro costo. " . $respuestaGuardarPersonaCentroCosto[0]['vout_mensaje']);
                    }
                }
            }

            //guardar contacto persona
            if (!ObjectUtil::isEmpty($listaContactoDetalle)) {
                foreach ($listaContactoDetalle as $indice => $item) {
                    $personaContactoId = $item[4];
                    $contactoId = $item[2];
                    $contactoTipo = $item[1];

                    //si existe contacto tipo obtengo el id de lo contrario inserto contacto tipo.
                    $contactoTipo = trim($contactoTipo);
                    $resContactoTipo = Persona::create()->obtenerContactoTipoXDescripcion($contactoTipo);

                    if (ObjectUtil::isEmpty($resContactoTipo)) {
                        $resContactoTipo = Persona::create()->insertarContactoTipo($contactoTipo, $usuarioCreacion);
                    }

                    $contactoTipoId = $resContactoTipo[0]['id'];
                    // fin contacto tipo

                    $res = Persona::create()->guardarPersonaContacto($personaId, $personaContactoId, $contactoId, $contactoTipoId, $usuarioCreacion);

                    if ($res[0]['vout_exito'] == 0) {
                        throw new WarningException("Error al guardar el contacto. " . $res[0]['vout_mensaje']);
                    } else {
                        // insertamos clase contacto a la persona
                        $resultado = Persona::create()->savePersonaClasePersona(-3, $contactoId, $usuarioCreacion);
                    }
                }
            }
        } else {
            throw new WarningException($responsePersona[0]['vout_mensaje']);
        }
        return $responsePersona;
    }

    public function updatePersona($id, $codigoIdentificacion, $nombre, $apellidoPaterno, $apellidoMaterno, $telefono, $celular, $email, $file, $estado, $empresa, $clase, $usuarioSesion, $listaContactoDetalle, $listaPersonaContactoEliminado, $listaDireccionDetalle, $listaPersonaDireccionEliminado, $codigoSunatId, $codigoSunatId2, $codigoSunatId3, $nombreBCP, $numero_cuenta_bcp, $cci, $listaCentroCostoPersona,$bandera_recojo_reparto=null,$bandera_credito_persona=null,$detlicencia,$direccionofc)
    {

        //direcciones antes
        $direccion = '';
        $direccionReferencia = '';

        $decode = Util::base64ToImage($file);
        if ($file == null || $file == '') {
            $imagen = null;
        } else {
            $imagen = $codigoIdentificacion . '.jpg';
            file_put_contents(__DIR__ . '/../../vistas/com/persona/imagen/' . $imagen, $decode);
        }

        $responsePersona = Persona::create()->updatePersona($id, $codigoIdentificacion, $nombre, $apellidoPaterno, $apellidoMaterno, $telefono, $celular, $email, $direccion, $direccionReferencia, $imagen, $estado, $usuarioSesion, $codigoSunatId, $codigoSunatId2, $codigoSunatId3, $nombreBCP, $numero_cuenta_bcp, $cci,$bandera_recojo_reparto,$bandera_credito_persona,$detlicencia,$direccionofc);

        if ($responsePersona[0]["vout_exito"] == 1) {
            if (!ObjectUtil::isEmpty($empresa)) {
                $this->savePersonaEmpresa($empresa, $id, $usuarioSesion);
            }
            if (!ObjectUtil::isEmpty($clase)) {
                $this->savePersonaClasePersona($clase, $id, $usuarioSesion);
            }

            $respuestaEliminarPersonaCentroCosto = Persona::create()->eliminarPersonaCentroCostoXPersonaId($id);
            if ($respuestaEliminarPersonaCentroCosto[0]['vout_exito'] != 1) {
                throw new WarningException("Error al eliminar persona centro costo. " . $respuestaEliminarPersonaCentroCosto[0]['vout_mensaje']);
            }

            if (!ObjectUtil::isEmpty($listaCentroCostoPersona)) {
                foreach ($listaCentroCostoPersona as $indice => $item) {
                    $respuestaGuardarPersonaCentroCosto = Persona::create()->guardarPersonaCentroCosto($id, $item['centro_costo_id'], $item['porcentaje'], $usuarioSesion);
                    if ($respuestaGuardarPersonaCentroCosto[0]['vout_exito'] != 1) {
                        throw new WarningException("Error al guardar persona centro costo. " . $respuestaGuardarPersonaCentroCosto[0]['vout_mensaje']);
                    }
                }
            }

            //            //direccion 1
            //            $res=Persona::create()->guardarPersonaDireccion($id,1,$direccion, $usuarioSesion);
            //            
            //            // direccion 2 -> campo de referencia            
            //            $res2=Persona::create()->guardarPersonaDireccion($id,2,$direccionReferencia, $usuarioSesion);
            //            
            //            // direccion 3            
            //            Persona::create()->guardarPersonaDireccion($id,3,$direccion3, $usuarioSesion);
            //            
            //            // direccion 4           
            //            Persona::create()->guardarPersonaDireccion($id,4,$direccion4, $usuarioSesion);
            //guardar persona direccion
            if (!ObjectUtil::isEmpty($listaDireccionDetalle)) {
                foreach ($listaDireccionDetalle as $indice => $item) {
                    //listaDireccionDetalle.push([direccionTipo,direccionTipoText,ubigeo,ubigeoText,direccionText,personaDireccionId]);

                    $personaDireccionId = $item[5];
                    $direccionTipo = $item[1];
                    $ubigeoId = $item[2];
                    $direccionTexto = $item[4];
                    $zonaId = $item[6];
                    $referencia = $item[8];
                    $latitud = $item[9];
                    $longitud = $item[10];
                    //si existe direccion tipo obtengo el id de lo contrario inserto direccion tipo.
                    $direccionTipo = trim($direccionTipo);
                    $resDireccionTipo = Persona::create()->obtenerDireccionTipoXDescripcion($direccionTipo);

                    if (ObjectUtil::isEmpty($resDireccionTipo)) {
                        $resDireccionTipo = Persona::create()->insertarDireccionTipo($direccionTipo, $usuarioSesion);
                    }

                    $latitud = (ObjectUtil::isEmpty(trim($latitud)) || $latitud == trim("null") ? NULL : $latitud);
                    $longitud = (ObjectUtil::isEmpty(trim($longitud)) || $longitud == trim("null") ? NULL : $longitud);

                    $direccionTipoId = $resDireccionTipo[0]['id'];
                    // fin direccion tipo

                    $res = Persona::create()->guardarPersonaDireccion($id, ($indice + 1), $direccionTexto, $usuarioSesion, $personaDireccionId, $direccionTipoId, $ubigeoId, $longitud, $latitud, $referencia, $zonaId);

                    if ($res[0]['vout_exito'] == 0) {
                        throw new WarningException("Error al guardar la dirección. " . $res[0]['vout_mensaje']);
                    }
                }
            }

            if (!ObjectUtil::isEmpty($listaPersonaDireccionEliminado)) {
                foreach ($listaPersonaDireccionEliminado as $indice => $item) {
                    $personaDireccionId = $item[0];

                    $res2 = Persona::create()->eliminarPersonaDireccion($personaDireccionId);
                }
            }

            //guardar contacto persona
            if (!ObjectUtil::isEmpty($listaContactoDetalle)) {
                foreach ($listaContactoDetalle as $indice => $item) {
                    $personaContactoId = $item[4];
                    $contactoId = $item[2];
                    $contactoTipo = $item[1];

                    //si existe contacto tipo obtengo el id de lo contrario inserto contacto tipo.
                    $contactoTipo = trim($contactoTipo);
                    $resContactoTipo = Persona::create()->obtenerContactoTipoXDescripcion($contactoTipo);

                    if (ObjectUtil::isEmpty($resContactoTipo)) {
                        $resContactoTipo = Persona::create()->insertarContactoTipo($contactoTipo, $usuarioSesion);
                    }

                    $contactoTipoId = $resContactoTipo[0]['id'];
                    // fin contacto tipo

                    $res = Persona::create()->guardarPersonaContacto($id, $personaContactoId, $contactoId, $contactoTipoId, $usuarioSesion);

                    if ($res[0]['vout_exito'] == 0) {
                        throw new WarningException("Error al guardar el contacto. " . $res[0]['vout_mensaje']);
                    } else {
                        // insertamos clase contacto a la persona
                        $resultado = Persona::create()->savePersonaClasePersona(-3, $contactoId, $usuarioSesion);
                    }
                }
            }

            if (!ObjectUtil::isEmpty($listaPersonaContactoEliminado)) {
                foreach ($listaPersonaContactoEliminado as $indice => $item) {
                    $personaContactoId = $item[0];

                    $res2 = Persona::create()->eliminarPersonaContacto($personaContactoId);
                }
            }
        } else {
            throw new WarningException($responsePersona[0]["vout_mensaje"]);
        }
        return $responsePersona;
    }

    public function cambiarEstadoPersona($id, $usuarioSesion, $estado)
    {
        //        $responsePersona = $this->obtenerPersonaXId($usuarioSesion);
        $responsePersona = Usuario::create()->getUsuario($usuarioSesion);
        $personaIdSesion = $responsePersona[0]['persona_id'];
        $response = Persona::create()->verificarPersona($id, $personaIdSesion);
        if ($response[0]['vout_exito'] == 0) {
            throw new WarningException($response[0]['vout_mensaje']);
        } else {
            if ($estado == 2) {
                Persona::create()->deletePersonaClasePersona($id);
            }
            $respuestaActualizarEstado = Persona::create()->cambiarEstadoPersona($id, $personaIdSesion, $estado);
            if ($respuestaActualizarEstado[0]['vout_exito'] == 0) {
                throw new WarningException($respuestaActualizarEstado[0]['vout_mensaje']);
            }
            return $respuestaActualizarEstado;
        }
    }

    function savePersonaEmpresa($empresa, $personaId, $usuarioCreacion)
    {

        if (!ObjectUtil::isEmpty($empresa) && !ObjectUtil::isEmpty($personaId)) {
            Persona::create()->deletePersonaEmpresa($personaId);

            if (is_array($empresa)) {
                foreach ($empresa as $empresaId) {
                    Persona::create()->savePersonaEmpresa($empresaId, $personaId, $usuarioCreacion);
                }
            } else {
                Persona::create()->savePersonaEmpresa($empresa, $personaId, $usuarioCreacion);
            }
        }
    }

    function obtenerPersonaXId($id)
    {
        return Persona::create()->obtenerPersonaGetById($id);
    }

    function obtenerPersonaXUsuarioId($usuarioId)
    {
         
       $datos= Persona::create()->obtenerPersonaXUsuarioId($usuarioId);

        return $datos;
    }

    function savePersonaClasePersona($clase, $personaId, $usuarioCreacion)
    {

        if (!ObjectUtil::isEmpty($clase) && !ObjectUtil::isEmpty($personaId)) {
            Persona::create()->deletePersonaClasePersona($personaId);
            if (is_array($clase)) {
                foreach ($clase as $claseId) {
                    Persona::create()->savePersonaClasePersona($claseId, $personaId, $usuarioCreacion);
                }
            } else {
                Persona::create()->savePersonaClasePersona($clase, $personaId, $usuarioCreacion);
            }
        }
    }

    public function obtenerComboPersonaClase()
    {
        return Persona::create()->obtenerComboPersonaClase();
    }

    public function obtenerConfiguracionesPersona($personaId, $personaTipoId, $usuarioId)
    {
        $respuesta = new ObjectUtil();
        //$respuesta->empresa = EmpresaNegocio::create()->getAllEmpresaByUsuarioId($usuarioId);
        $respuesta->empresa = EmpresaNegocio::create()->getEmpresaActivas();
        //        $respuesta->persona_clase = Persona::create()->getAllPersonaClaseByTipo($personaTipoId);
        $respuesta->persona_clase = Persona::create()->obtenerPersonaClaseXpersonaTipoIdXUsuarioId($personaTipoId, $usuarioId);
        $respuesta->persona = ($personaId > 0) ? $this->obtenerPersonaXId($personaId) : null;

        //contactos
        $respuesta->personaNatural = array(); // Persona::create()->obtenerPersonasXPersonaTipo(2); // 2-> natural
        $respuesta->contactoTipo = Persona::create()->obtenerContactoTipoActivos();
        $respuesta->personaContacto = array(); //($personaId > 0) ? $this->obtenerPersonaContactoXPersonaId($personaId) : null;
        $respuesta->tipoDocumento = Persona::create()->obtenerTipoDocumento();
        //direcciones
        $respuesta->direccionTipo = Persona::create()->obtenerDireccionTipoActivos();
        $respuesta->dataUbigeo = Persona::create()->obtenerUbigeoActivos();
        $respuesta->dataZona = Zona::create()->obtenerZonaActiva();
        $respuesta->personaDireccion = ($personaId > 0) ? $this->obtenerPersonaDireccionXPersonaId($personaId) : null;

        //persona clase asociada al usuario
        $respuesta->personaClaseXUsuario = Persona::create()->obtenerPersonaClaseXUsuarioId($usuarioId);

        //tablas sunat
        $respuesta->dataSunatDetalle = SunatTablaNegocio::create()->obtenerDetalleXSunatTablaId(35);
        $respuesta->dataSunatDetalle2 = SunatTablaNegocio::create()->obtenerDetalleXSunatTablaId(27);
        $respuesta->dataSunatDetalle3 = SunatTablaNegocio::create()->obtenerDetalleXSunatTablaId(25);
        $respuesta->dataCentroCosto = array(); //CentroCostoNegocio::create()->listarCentroCosto(2);
        $respuesta->dataCentroCostoPersona = array(); //Persona::create()->obtenerPersonaCentroCostoXPersonaId($personaId);
        $respuesta->perfil =Perfil::create()->obtnerPerfilCargoXUsuarioId($usuarioId);
        $respuesta->perfil_asignado =ConfigGlobal::PERFILES_ASIGNADOS;
        return $respuesta;
    }

    // otras funciones de sus tablas pivote

    public function getAllPersonaClaseByTipo($idTipo)
    {
        return Persona::create()->getAllPersonaClaseByTipo($idTipo);
    }

    public function obtenerActivas()
    {
        return Persona::create()->obtenerActivas();
    }

    public function obtenerPersonaClaseActivas()
    {
        return Persona::create()->obtenerPersonaClaseActivas();
    }

    public function obtenerPersonaClaseXUsuarioId($usuarioId)
    {
        return Persona::create()->obtenerPersonaClaseXUsuarioId($usuarioId);
    }

    //Nueva funcionalidad
    public function configuracionesInicialesPersonaListar($usuarioId)
    {
        //$respuesta->persona_clase = $this->obtenerPersonaClaseActivas();
        $respuesta = new stdClass();
        $respuesta->persona_clase = $this->obtenerPersonaClaseXUsuarioId($usuarioId);
        $respuesta->persona_tipo = $this->getAllPersonaTipo();
        return $respuesta;
    }

    public function obtenerComboPersonaXPersonaClaseId($personaClaseId, $parse = false)
    {
        $personas = Persona::create()->obtenerComboPersonaXPersonaClaseId($personaClaseId);
        if ($parse) {
            $data["-1"] = "Ninguno";
            foreach ($personas as $persona) {
                $data[$persona["persona_nombre"]] = trim($persona["persona_nombre"]);
            }
            return $data;
        }
        return $personas;
    }

    //obtener datos desde consulta ruc sunat  

    public function getDatosProveedor($tipoDocumn = null, $codigoIdentificacion)
    {
        /* $data = ConsultaWs::create()->obtenerConsultaRucSunat($ruc);
          if (!ObjectUtil::isEmpty($data)) {
          $data = $data[0];
          $data['NúmerodeRUC']['razon_social'] = $data['razonSocial'];
          //            $data['razonSocial'] = @trim(substr($data['RUC'], strpos($data['RUC'], "-") + 2));
          $data['departamento'] = $data['dpto'];
          $data['provincia'] = $data['prov'];
          $data['distrito'] = $data['dist'];
          $data['ubigeo'] = "";
          } */
        if ($tipoDocumn != null) {
            $data = ConsultaWs::create()->obtenerConsultaDocumento($tipoDocumn, $codigoIdentificacion);
        } else {
            $data = ConsultaWs::create()->obtenerConsultaRuc($codigoIdentificacion); //consulta ruc
        }
        return $data;
    }

    public function getDatosProveedorOld($ruc)
    {
        try {
            $res = $this->ComprobarRUC($ruc);
            $data = array();
            foreach ($res as $par) {
                $name = trim(str_replace(" ", "", str_replace(":", "", $par["name"])));
                $value = trim($par["value"]);
                if (strlen($value) > 3 && strpos($value, "-") !== false) {
                    $value = explode("-", $value);
                    $value = trim($value[0]) . " - " . trim($value[1]);
                }
                $data[$name] = $value;
            }
        } catch (Exception $ex) {
            $this->setMensajeEmergente($ex->getMessage(), '', Configuraciones::MENSAJE_ERROR);
            //            throw $ex;
        }

        $data['CondicióndelContribuyente'] = @trim($data['Condición']);
        $data['DireccióndelDomicilioFiscal'] = @trim($data['DomicilioFiscal']);
        $data['EstadodelContribuyente'] = @trim($data['Estado']);
        $data['razonSocial'] = @trim(substr($data['RUC'], strpos($data['RUC'], "-") + 2));
        return $data;
    }

    public function ComprobarRUC($ruc)
    {
        try {
            if (strlen($ruc) != 11) {
                throw new Exception("El número de ruc sólo puede contener once digitos.");
            }
            $cookie_jar = tempnam('/tmp', 'cookie.txt');
            $referer = "http://www.sunat.gob.pe/descarga/AgentRet/AgenRet1.html";
            $useragent = "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.111 Safari/537.36";
            $rand = $this->getRandomRazonSocial($cookie_jar, $referer, $useragent);

            $response = $this->getHTML($ruc, $rand, $cookie_jar, $referer, $useragent);
            $dom = new DOMDocument();
            @$dom->loadHTML($response["html"]);
            $dom->preserveWhiteSpace = false;
            $tables = $dom->getElementsByTagName('table');

            if ($response["status"] != 200) {
                throw new Exception("Hubo un error al tratar de conectar al servidor de la SUNAT.");
            }
            $rows = $tables->item(3)->getElementsByTagName('tr');
            foreach ($rows as $row) {
                $cols = $row->getElementsByTagName('td');
                if (!is_object($cols->item(1))) {
                    throw new Exception("RUC no válido");
                }
                $name = utf8_encode(trim(mb_convert_encoding($cols->item(0)->nodeValue, 'ISO-8859-1', 'utf-8')));
                $valu = utf8_encode(trim(mb_convert_encoding($cols->item(1)->nodeValue, 'ISO-8859-1', 'utf-8')));
                $res[] = array(
                    "name" => $name,
                    "value" => $valu
                );
            }
            unlink($cookie_jar);
            return $res;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function getRandomRazonSocial($cookie_jar, $referer, $useragent)
    {
        $razonSocial = "SUPERINTENDENCIA NACIONAL DE ADUANAS Y DE ADMINISTRACION TRIBUTARIA - SUNAT";
        $razonSocial = str_replace(" ", "%20", $razonSocial);

        $url = "https://e-consultaruc.sunat.gob.pe/cl-ti-itmrconsruc/jcrS00Alias?accion=consPorRazonSoc&razSoc=$razonSocial";
        $ch = curl_init();
        $timeout = 8;
        //        $fp = fopen($urlCaptcha, 'w+');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // enable if you want
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_LOW_SPEED_LIMIT, 2);
        curl_setopt($ch, CURLOPT_LOW_SPEED_TIME, $timeout);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
        curl_setopt($ch, CURLOPT_REFERER, $referer);
        curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
        $html = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_status == 200) {
            $dom = new DOMDocument();
            @$dom->loadHTML($html);
            $dom->preserveWhiteSpace = false;
            $xp = new DOMXpath($dom);
            $nodes = $xp->query('//input[@name="numRnd"]');
            if ($nodes->length > 0) {
                $rnd = $nodes->item(0)->getAttribute('value');
            }
        }
        return $rnd;
    }

    public function getRandom($cookie_jar, $referer, $useragent)
    {
        # Get captcha with POST method
        //        $url = "http://www.sunat.gob.pe/cl-ti-itmrconsruc/captcha";
        $url = "https://e-consultaruc.sunat.gob.pe/cl-ti-itmrconsruc/captcha?accion=random";
        $ch = curl_init();
        $timeout = 8;
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_LOW_SPEED_LIMIT, 2);
        curl_setopt($ch, CURLOPT_LOW_SPEED_TIME, $timeout);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
        //        curl_setopt($ch, CURLOPT_POST, 1);
        //        curl_setopt($ch, CURLOPT_POSTFIELDS, "accion=random");
        curl_setopt($ch, CURLOPT_REFERER, $referer);
        curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
        $rnd = curl_exec($ch);
        curl_close($ch);
        return $rnd;
    }

    public function getHTML($ruc_nro, $rnd, $cookie_jar, $referer, $useragent)
    {
        //        $url = "http://www.sunat.gob.pe/cl-ti-itmrconsruc/jcrS00Alias";
        $url = "https://e-consultaruc.sunat.gob.pe/cl-ti-itmrconsruc/jcrS00Alias";
        $ch = curl_init();
        $timeout = 8;
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_LOW_SPEED_LIMIT, 2); // bytes per second
        curl_setopt($ch, CURLOPT_LOW_SPEED_TIME, $timeout); // seconds
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "accion=consPorRuc&nroRuc=$ruc_nro&actReturn=1&numRnd=$rnd");
        curl_setopt($ch, CURLOPT_REFERER, $referer);
        curl_setopt($ch, CURLOPT_USERAGENT, $useragent); // simulate a real browser
        $html = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return array("html" => $html, "status" => $http_status);
    }

    public function getRandom2($cookie_jar, $referer, $useragent)
    {
        # Get captcha with POST method
        $url = "http://www.sunat.gob.pe/cl-ti-itmrconsruc/captcha";
        $ch = curl_init();
        $timeout = 8;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_LOW_SPEED_LIMIT, 2);
        curl_setopt($ch, CURLOPT_LOW_SPEED_TIME, $timeout);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "accion=random");
        curl_setopt($ch, CURLOPT_REFERER, $referer);
        curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
        $rnd = curl_exec($ch);
        curl_close($ch);
        return $rnd;
    }

    public function getHTML2($ruc_nro, $rnd, $cookie_jar, $referer, $useragent)
    {
        $url = "http://www.sunat.gob.pe/cl-ti-itmrconsruc/jcrS00Alias";
        $ch = curl_init();
        $timeout = 8;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_LOW_SPEED_LIMIT, 2); // bytes per second
        curl_setopt($ch, CURLOPT_LOW_SPEED_TIME, $timeout); // seconds
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "accion=consPorRuc&nroRuc=$ruc_nro&actReturn=1&numRnd=$rnd");
        curl_setopt($ch, CURLOPT_REFERER, $referer);
        curl_setopt($ch, CURLOPT_USERAGENT, $useragent); // simulate a real browser
        $html = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return array("html" => $html, "status" => $http_status);
    }

    public function obtenerPersonaDireccionXPersonaId($personaId)
    {
        return Persona::create()->obtenerPersonaDireccionXPersonaId($personaId);
    }

    public function obtenerPersonaPerfilVendedor()
    {
        return Persona::create()->obtenerPersonaPerfilVendedor();
    }

    public function buscarPersonaXNombreXDocumento($opcionId, $busqueda)
    {
        return Persona::create()->buscarPersonaXNombreXDocumento($opcionId, $busqueda);
    }

    public function obtenerPersonasMayorMovimiento($opcionId)
    {
        return Persona::create()->obtenerPersonasMayorMovimiento($opcionId);
    }

    public function obtenerPersonasMayorOperacion($opcionId)
    {
        return Persona::create()->obtenerPersonasMayorOperacion($opcionId);
    }

    public function buscarPersonaOperacionXNombreXDocumento($opcionId, $busqueda)
    {
        return Persona::create()->buscarPersonaOperacionXNombreXDocumento($opcionId, $busqueda);
    }

    public function obtenerActivasXDocumentoTipoId($documentoTipoId)
    {
        return Persona::create()->obtenerActivasXDocumentoTipoId($documentoTipoId);
    }

    public function buscarPersonasXDocumentoTipoXValor($documentoTipoArray, $valor)
    {
        return Persona::create()->buscarPersonasXDocumentoTipoXValor(Util::fromArraytoString($documentoTipoArray), $valor);
    }

    public function buscarPersonasXDocumentoPagar($empresaId, $tipo, $tipoProvisionPago, $busqueda)
    {
        return Persona::create()->buscarPersonasXDocumentoPagar($empresaId, $tipo, $tipoProvisionPago, $busqueda);
    }

    public function buscarPersonasXDocumentoPago($empresaId, $tipo, $tipoProvisionPago, $busqueda)
    {
        return Persona::create()->buscarPersonasXDocumentoPago($empresaId, $tipo, $tipoProvisionPago, $busqueda);
    }

    public function buscarPersonasXDocumentoPagado($empresaId, $tipo, $tipoProvisionPago, $busqueda)
    {
        return Persona::create()->buscarPersonasXDocumentoPagado($empresaId, $tipo, $tipoProvisionPago, $busqueda);
    }

    public function buscarPersonaListarXNombreXDocumento($busqueda, $usuarioId = 1)
    {
        return Persona::create()->buscarPersonaListarXNombreXDocumento($busqueda, $usuarioId);
    }

    public function buscarPersonaClaseXDescripcion($busqueda, $usuarioId = 1)
    {
        return Persona::create()->buscarPersonaClaseXDescripcion($busqueda, $usuarioId);
    }

    public function obtenerPersonasXPersonaTipo($personaTipoId)
    {
        return Persona::create()->obtenerPersonasXPersonaTipo($personaTipoId);
    }

    public function obtenerPersonaContactoXPersonaId($personaId)
    {
        return Persona::create()->obtenerPersonaContactoXPersonaId($personaId);
    }

    public function obtenerPersonaClaseXpersonaTipoIdXUsuarioId($personaTipoId, $usuarioId)
    {
        return Persona::create()->obtenerPersonaClaseXpersonaTipoIdXUsuarioId($personaTipoId, $usuarioId);
    }

    public function obtenerActivasXDocumentoTipoIdXUsuarioId($documentoTipoId, $usuarioId)
    {
        return Persona::create()->obtenerActivasXDocumentoTipoIdXUsuarioId($documentoTipoId, $usuarioId);
    }

    public function obtenerSunatTablaDetalleRelacionXTipoXSunatTablaDetalleId($tipo, $sunatTablaDetalleId)
    {
        return Persona::create()->obtenerSunatTablaDetalleRelacionXTipoXSunatTablaDetalleId($tipo, $sunatTablaDetalleId);
    }

    public function obtenerComboPersonaProveedores()
    {
        return Persona::create()->obtenerComboPersonaProveedores();
    }

    public function validarSimilitud($id, $nombre, $apellidoPaterno)
    {
        return Persona::create()->validarSimilitud($id, $nombre, $apellidoPaterno);
    }

    //busqueda de personas  en modal de relacionar

    public function buscarPersonasXDocumentoOperacion($documentoTipoArray, $valor)
    {
        return Persona::create()->buscarPersonasXDocumentoOperacion(Util::fromArraytoString($documentoTipoArray), $valor);
    }

    public function obtenerPersonaXOpcionMovimiento($opcionId)
    {
        return Persona::create()->obtenerPersonaXOpcionMovimiento($opcionId);
    }

    public function obtenerPersonasMayorDocumentosPPagoXTipos($tipos)
    {
        return Persona::create()->obtenerPersonasMayorDocumentosPPagoXTipos($tipos);
    }

    public function obtenerPersonaXCodigoIdentificacion($codigoIdentificacion)
    {
        return Persona::create()->obtenerPersonaXCodigoIdentificacion($codigoIdentificacion);
    }

    public function buscarPersonaDocumentoEarXNombreXDocumento($opcionId, $busqueda)
    {
        return Persona::create()->buscarPersonaDocumentoEarXNombreXDocumento($opcionId, $busqueda);
    }

    public function obtenerUbigeoXId($ubigeoId)
    {
        return Persona::create()->obtenerUbigeoXId($ubigeoId);
    }

    public function obtenerCorreosEFACT()
    {
        return Persona::create()->obtenerCorreosEFACT();
    }

    public function obtenerCuentaContableXPersonaId($personaId)
    {
        return Persona::create()->obtenerCuentaContableXPersonaId($personaId);
    }

    public function obtenerPersonaCentroCostoXCodigoIdentificacion($codigoIdentificacion)
    {
        return Persona::create()->obtenerPersonaCentroCostoXCodigoIdentificacion($codigoIdentificacion);
    }

    public function obtenerPorUsuarioId($usuarioId)
    {
        $persona = Persona::create()->obtenerPorUsuarioId($usuarioId);
        if (ObjectUtil::isEmpty($persona)) {
            throw new WarningException("No se encontró información de la persona asociada a este usuario");
        }
        $persona[0]['direccion'] = Persona::create()->obtenerPersonaDireccionXPersonaIdApp($persona[0]["id"], $usuarioId);
        return $persona;
    }

    public function updatePersonaDatos(
        $tipoDocumentoId,
        $numeroDocumento,
        $nombres,
        $apellidoPaterno,
        $apellidoMaterno,
        $correo,
        $correoAlternativo,
        $usuarioCreacion,
        $celular
    ) {
        $persona = Persona::create()->obtenerPorUsuarioId($usuarioCreacion);
        if (ObjectUtil::isEmpty($persona)) {
            throw new WarningException("No se encontró información de la persona asociada a este usuario");
        }
        $respuesta = Persona::create()->updatePersonaDatoApp($persona[0]['id'], $correo, $correoAlternativo, $celular);
        return $respuesta;
    }

    public function updatePersonaDatosContacto(
        $tipoDocumentoId,
        $numeroDocumento,
        $nombres,
        $apellidoPaterno,
        $apellidoMaterno,
        $correo,
        $correoAlternativo,
        $listaDireccionDetalle,
        $usuarioId,
        $celular,
        $contactoTipo
    ) {
        $persona = Persona::create()->obtenerPorUsuarioId($usuarioId);
        if (ObjectUtil::isEmpty($persona)) {
            throw new WarningException("No se encontró información de la persona asociada a este usuario");
        }

        $obtenerPersona = Persona::create()->getPersonaCodigo($numeroDocumento, $tipoDocumentoId);
        $id_colaborador = $obtenerPersona[0]['id'];

        $resUsuario = Persona::create()->updatePersonaContacto($persona[0]['id'], $id_colaborador, $contactoTipo, 1, $usuarioId, $correo, $celular, $correoAlternativo);
        /*
          Persona::create()->eliminarPersonaDireccionMovil($usuarioId, $id_colaborador);
          if (!ObjectUtil::isEmpty($listaDireccionDetalle)) {
          foreach ($listaDireccionDetalle as $indice => $item) {
          //listaDireccionDetalle.push([direccionTipo,direccionTipoText,ubigeo,ubigeoText,direccionText,personaDireccionId]);

          $personaDireccionId = $item[5];
          $direccionTipo = $item[1];
          $ubigeoId = $item[2];
          $direccionTexto = $item[4];
          $zonaId = $item[6];
          $referencia = $item[8];
          $latitud = $item[9] != "" ? $item[9] : null;
          $longitud = $item[10] != "" ? $item[10] : null;
          //si existe direccion tipo obtengo el id de lo contrario inserto direccion tipo.
          $direccionTipo = trim($direccionTipo);
          $resDireccionTipo = Persona::create()->obtenerDireccionTipoXDescripcion($direccionTipo);

          if (ObjectUtil::isEmpty($resDireccionTipo)) {
          $resDireccionTipo = Persona::create()->insertarDireccionTipo($direccionTipo, $usuarioCreacion);
          }

          $latitud = (ObjectUtil::isEmpty(trim($latitud)) || $latitud == trim("null") ? NULL : $latitud);
          $longitud = (ObjectUtil::isEmpty(trim($longitud)) || $longitud == trim("null") ? NULL : $longitud);

          $direccionTipoId = $resDireccionTipo[0]['id'];
          // fin direccion tipo

          $res = Persona::create()->guardarPersonaDireccion($id_colaborador, ($indice + 1), $direccionTexto, $usuarioId, $personaDireccionId, $direccionTipoId, $ubigeoId, $longitud, $latitud, $referencia, $zonaId);

          if ($res[0]['vout_exito'] == 0) {
          throw new WarningException("Error al guardar la dirección. " . $res[0]['vout_mensaje']);
          }
          }
          } */
        return $resUsuario;
    }

    public function updateEmpresaDatosContacto(
        $tipoDocumentoId,
        $numeroDocumento,
        $razonSocial,
        $direccionFiscal,
        $nombreContacto,
        $correo,
        $listaDireccionDetalle,
        $usuarioId,
        $celular,
        $contactoTipo
    ) {
        $persona = Persona::create()->obtenerPorUsuarioId($usuarioId);
        if (ObjectUtil::isEmpty($persona)) {
            throw new WarningException("No se encontró información de la persona asociada a este usuario");
        }

        $obtenerPersona = Persona::create()->getPersonaCodigo($numeroDocumento, $tipoDocumentoId);
        $id_colaborador = $obtenerPersona[0]['id'];

        $resUsuario = Persona::create()->updateEmpresaContacto($persona[0]['id'], $id_colaborador, $contactoTipo, 1, $usuarioId, $correo, $celular, $nombreContacto);
        Persona::create()->eliminarPersonaDireccionMovil($usuarioId, $id_colaborador);
        Persona::create()->guardarPersonaDireccion($id_colaborador, 1, $direccionFiscal, $usuarioId, $personaDireccionId, -1, null, null, null, null, null);
        if (!ObjectUtil::isEmpty($listaDireccionDetalle)) {
            foreach ($listaDireccionDetalle as $indice => $item) {
                //listaDireccionDetalle.push([direccionTipo,direccionTipoText,ubigeo,ubigeoText,direccionText,personaDireccionId]);

                $personaDireccionId = $item[5];
                $direccionTipo = $item[1];
                $ubigeoId = $item[2];
                $direccionTexto = $item[4];
                $zonaId = $item[6];
                $referencia = $item[8];
                $latitud = $item[9] != "" ? $item[9] : null;
                $longitud = $item[10] != "" ? $item[10] : null;
                //si existe direccion tipo obtengo el id de lo contrario inserto direccion tipo.
                $direccionTipo = trim($direccionTipo);
                $resDireccionTipo = Persona::create()->obtenerDireccionTipoXDescripcion($direccionTipo);

                if (ObjectUtil::isEmpty($resDireccionTipo)) {
                    $resDireccionTipo = Persona::create()->insertarDireccionTipo($direccionTipo, $usuarioCreacion);
                }

                $latitud = (ObjectUtil::isEmpty(trim($latitud)) || $latitud == trim("null") ? NULL : $latitud);
                $longitud = (ObjectUtil::isEmpty(trim($longitud)) || $longitud == trim("null") ? NULL : $longitud);

                $direccionTipoId = $resDireccionTipo[0]['id'];
                // fin direccion tipo

                $res = Persona::create()->guardarPersonaDireccion($id_colaborador, ($indice + 1), $direccionTexto, $usuarioId, $personaDireccionId, $direccionTipoId, $ubigeoId, $longitud, $latitud, $referencia, $zonaId);

                if ($res[0]['vout_exito'] == 0) {
                    throw new WarningException("Error al guardar la dirección. " . $res[0]['vout_mensaje']);
                }
            }
        }
        return $resUsuario;
    }

    public function obtenerDireccionTipoActivos()
    {
        return Persona::create()->obtenerDireccionTipoActivos();
    }

    public function guardarDireccionDatos($personaDireccionId, $direccionTipoId, $direccion, $referencia, $latitud, $longitud, $usuarioCreacion, $zonaId, $personaId)
    {

        //        $persona = Persona::create()->obtenerPorUsuarioId($usuarioCreacion);
        if (ObjectUtil::isEmpty($personaId) || $personaId == 0) {
            throw new WarningException("No se encontró información de la persona asociada a esta nueva dirección");
        }
        //        $personaId = $persona[0]['id'];
        return Persona::create()->guardarPersonaDireccion(
            $personaId,
            1,
            $direccion,
            $usuarioCreacion,
            $personaDireccionId,
            $direccionTipoId,
            null,
            $longitud,
            $latitud,
            $referencia,
            $zonaId
        );
    }

    public function guardarDireccionFavoritoXId($personaDireccionId, $favorito)
    {
        return Persona::create()->guardarDireccionFavoritoXId($personaDireccionId, $favorito);
    }

    public function guardarDireccionFavorito($personaDireccionId, $direccionTipoId, $direccion, $favorito, $usuarioCreacion)
    {

        $persona = Persona::create()->obtenerPorUsuarioId($usuarioCreacion);
        if (ObjectUtil::isEmpty($persona)) {
            throw new WarningException("No se encontró información de la persona asociada a este usuario");
        }
        if (ObjectUtil::isEmpty($personaDireccionId)) {
            throw new WarningException("No se encontró información de la direccion asociada a este usuario");
        }
        $personaId = $persona[0]['id'];
        return Persona::create()->guardarDireccionFavorito(
            $personaId,
            $direccion,
            $personaDireccionId,
            $favorito,
            $direccionTipoId
        );
    }

    public function obtenerPersonaDocumentoTipo()
    {
        return Persona::create()->obtenerPersonaDocumentoTipo();
    }

    public function eliminarPersonaDireccion($personaDireccionId)
    {
        return Persona::create()->eliminarPersonaDireccion($personaDireccionId);
    }

    public function registrarPersonaDatos(
        $tipoDocumentoId,
        $codigoIdentificacion,
        $nombre,
        $apellidoPaterno,
        $apellidoMaterno,
        $email,
        $correoAlternativo,
        $contraseña,
        $contraseña_confirmar,
        $listaDireccionDetalle,
        $celular
    ) {

        if ($contraseña != $contraseña_confirmar) {
            throw new WarningException("Las contraseñas no coinciden. Ingresar nuevamente.");
        }

        $PersonaTipoIdo = 2;
        if ($tipoDocumentoId == 3) {
            $PersonaTipoIdo = 4;
        }
        $clase = 16; //cliente
        $empresa = 2;
        $obtenerUsuario = Usuario::create()->getUsuarioID($codigoIdentificacion);
        $usuario_id = $obtenerUsuario[0]['id'];
        if (!ObjectUtil::isEmpty($usuario_id)) {
            throw new WarningException("Ya se encuentra registrado un usuario con este documento identificación.");
        } else {
            $validacionCorreo = Usuario::create()->obtenerUsuarioXCorreo($email);
            $validacionId = $validacionCorreo[0]['id'];

            if (!ObjectUtil::isEmpty($validacionId)) {
                throw new WarningException("Ya se encuentra registrado un usuario con este correo.");
            } else {
                $obtenerPersona = Persona::create()->getPersonaCodigo($codigoIdentificacion, $tipoDocumentoId);
                if (!ObjectUtil::isEmpty($obtenerPersona)) {
                    $resPersona = Persona::create()->updatePersonaDatoApp($obtenerPersona[0]['id'], $email, $correoAlternativo, $celular);
                } else {
                    $resPersona = $this->insertPersona(
                        $PersonaTipoIdo,
                        $codigoIdentificacion,
                        $nombre,
                        $apellidoPaterno,
                        $apellidoMaterno,
                        null,
                        $celular,
                        $email,
                        null,
                        1,
                        1,
                        $empresa,
                        $clase,
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                        $correoAlternativo,
                        $tipoDocumentoId
                    );
                }
                // CREAR USUARIO
                $id_colaborador = $resPersona[0]['id'];
              
                $clave = Util::encripta($contraseña);

                $resUsuario = Usuario::create()->insertUsuario($codigoIdentificacion, $id_colaborador, 1, 3, $clave, null);
                if ($resUsuario[0]['vout_exito'] != 1) {
                    throw new WarningException("Error intentar guardar el usuario " . $resUsuario[0]['vout_mensaje']);
                }
                $id_usuario = $resUsuario[0]['id'];

                if (!ObjectUtil::isEmpty($listaDireccionDetalle)) {
                    foreach ($listaDireccionDetalle as $indice => $item) {
                        $direccionTipoId = trim($item[0]);
                        $zonaId = $item[1];
                        $referencia = $item[2];
                        $direccionTexto = $item[3];
                        $latitud = (ObjectUtil::isEmpty($item[4]) || $item[4] == trim("null") ? NULL : trim($item[4]));
                        $longitud = (ObjectUtil::isEmpty($item[5]) || $item[5] == trim("null") ? NULL : trim($item[5]));

                        $res = Persona::create()->guardarPersonaDireccion($id_colaborador, ($indice + 1), $direccionTexto, $id_usuario, NULL, $direccionTipoId, $ubigeoId, $longitud, $latitud, $referencia, $zonaId);

                        if ($res[0]['vout_exito'] == 0) {
                            throw new WarningException("Error al guardar la dirección. " . $res[0]['vout_mensaje']);
                        }
                    }
                }

                $respuestaRegistroCorreo = UsuarioNegocio::create()->correoActivacionUsuario($id_usuario);
                $id_registroCorreo = $respuestaRegistroCorreo[0]['id'];
                $EnvioCorreo = EmailEnvioNegocio::create()->enviarPendientesEnvio($id_registroCorreo);
                $id_perfil = 2;

                Usuario::create()->insertDetUsuarioPerfil($id_usuario, $id_perfil, $empresa, 1, 1);
                //Enviar correo de 

                return $resUsuario[0];
            }
        }
    }

    public function restablecerContrasenia($correo)
    {
        $n1 = mt_rand(0, 9);
        $n2 = mt_rand(0, 9);
        $n3 = mt_rand(0, 9);
        $n4 = mt_rand(0, 9);
        $codigo = $n1 . $n2 . $n3 . $n4;

        $respuestaUsuario = UsuarioNegocio::create()->obtenerUsuarioXCorreo($correo);
        $id_usuario = $respuestaUsuario[0]['id'];
        if (ObjectUtil::isEmpty($id_usuario)) {
            throw new WarningException("Este correo no esta asignado a un usuario");
        } else {
            $respuestaRegistroCorreo = UsuarioNegocio::create()->correoRestablecerClave($id_usuario, $codigo);
            $id_registroCorreo = $respuestaRegistroCorreo[0]['id'];
            if ($respuestaRegistroCorreo[0]['vout_exito'] != 1) {
                throw new WarningException("No se pudo crear correo de forma satisfactoria");
            } else {
                $EnvioCorreo = EmailEnvioNegocio::create()->enviarPendientesEnvio($id_registroCorreo);
                $mensaje = 'El correo fue enviado de forma satisfactoria';
                $clave = Util::encripta($codigo);
                Usuario::create()->updateDeCodigoRecuperacion($id_usuario, $clave);
                return array('usuario' => $id_usuario, 'mensaje' => $mensaje);
            }
        }
    }

    public function validarCodigo($usuario, $codigo)
    {

        $claveEncriptada = Util::encripta($codigo);
        $respuestaUsuario = UsuarioNegocio::create()->obtenerUsuarioXCodidgo($usuario, $claveEncriptada);
        $id_usuario = $respuestaUsuario[0]['id'];
        if (ObjectUtil::isEmpty($id_usuario)) {
            throw new WarningException("Codigo ingresado es incorrecto, vuelva a revisar su correo");
        } else {
            $mensaje = 'El codigo es correcto';
            return array('usuario' => $id_usuario, 'mensaje' => $mensaje);
        }
    }

    public function registrarPersonaContactos(
        $tipoDocumentoId,
        $codigoIdentificacion,
        $nombre,
        $apellidoPaterno,
        $apellidoMaterno,
        $email,
        $correoAlternativo,
        $listaDireccionDetalle,
        $usuarioId,
        $celular,
        $contactoTipo
    ) {

        $persona = Persona::create()->obtenerPorUsuarioId($usuarioId);

        $PersonaTipoIdo = 2;
        if ($tipoDocumentoId == 3) {
            $PersonaTipoIdo = 4;
        }
        $clase = '-3'; //cliente
        $empresa = 2;

        $obtenerPersona = Persona::create()->getPersonaCodigo($codigoIdentificacion, $tipoDocumentoId);
        if (!ObjectUtil::isEmpty($obtenerPersona)) {
            $resPersona = $obtenerPersona;
        } else {
            $resPersona = $this->insertPersona(
                $PersonaTipoIdo,
                $codigoIdentificacion,
                $nombre,
                $apellidoPaterno,
                $apellidoMaterno,
                null,
                $celular,
                $email,
                null,
                1,
                $usuarioId,
                $empresa,
                $clase,
                null,
                $listaDireccionDetalle,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                $correoAlternativo,
                $tipoDocumentoId
            );
        }


        $id_colaborador = $resPersona[0]['id'];
        $obtenerPersonaContacto = Persona::create()->getPersonaContactoCodigoUsuario($id_colaborador, $usuarioId);

        if (!ObjectUtil::isEmpty($obtenerPersonaContacto)) {
            throw new WarningException("Este contacto ya esta registrado");
        } else {
            $resUsuario = Persona::create()->insertPersonaContacto($persona[0]['id'], $id_colaborador, $contactoTipo, 1, $usuarioId, $email, $celular, $correoAlternativo);

            return $resUsuario[0];
        }
    }

    public function registrarEmpresaContactos(
        $tipoDocumentoId,
        $numeroDocumento,
        $razonSocial,
        $direccionFiscal,
        $nombreContacto,
        $correo,
        $listaDireccionDetalle,
        $usuarioId,
        $celular,
        $contactoTipo
    ) {

        $persona = Persona::create()->obtenerPorUsuarioId($usuarioId);

        $PersonaTipoIdo = 2;
        if ($tipoDocumentoId == 3) {
            $PersonaTipoIdo = 4;
        }
        $clase = '-3'; //cliente
        $empresa = 2;

        $obtenerPersona = Persona::create()->getPersonaCodigo($numeroDocumento, $tipoDocumentoId);
        if (!ObjectUtil::isEmpty($obtenerPersona)) {
            $resPersona = $obtenerPersona;
        } else {
            $resPersona = $this->insertPersona(
                $PersonaTipoIdo,
                $numeroDocumento,
                $razonSocial,
                null,
                null,
                null,
                $celular,
                $correo,
                null,
                1,
                $usuarioId,
                $empresa,
                $clase,
                null,
                $listaDireccionDetalle,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                $correoAlternativo,
                $tipoDocumentoId
            );
        }


        $id_colaborador = $resPersona[0]['id'];
        $obtenerPersonaContacto = Persona::create()->getPersonaContactoCodigoUsuario($id_colaborador, $usuarioId);

        if (!ObjectUtil::isEmpty($obtenerPersonaContacto)) {
            throw new WarningException("Este contacto ya esta registrado");
        } else {
            $resUsuario = Persona::create()->insertEmpresaContacto($persona[0]['id'], $id_colaborador, $contactoTipo, 1, $usuarioId, $correo, $celular, $nombreContacto);
            Persona::create()->guardarPersonaDireccion($id_colaborador, 1, $direccionFiscal, $usuarioId, $personaDireccionId, -1, null, null, null, null, null);
            return $resUsuario[0];
        }
    }

    public function registrarContactos(
        $tipoDocumentoId,
        $numeroDocumento,
        $nombres,
        $direccionFiscal,
        $nombreContacto,
        $email,
        $listaDireccionDetalle,
        $usuarioId,
        $celular,
        $contactoTipo,
        $personaId,
        $apellidoPaterno,
        $apellidoMaterno,
        $correoAlternativo,
        $direccionesEliminadas
    ) {

        $persona = Persona::create()->obtenerPorUsuarioId($usuarioId);

        $PersonaTipoIdo = 2;
        if ($tipoDocumentoId == 3) {
            $PersonaTipoIdo = 4;
        }
        $clase = '-3'; //contacto
        $empresa = 2;

        //En caso no es vacio , se actualiza
        if (!ObjectUtil::isEmpty($personaId)) {

            foreach ($direccionesEliminadas as $index => $item) {
                $direccionId = $direccionesEliminadas[$index];
                Persona::create()->eliminarDireccionesxId($direccionId);
            }


            // se actualiza empresa
            if ($tipoDocumentoId == 3) {

                $obtenerPersona = Persona::create()->getPersonaCodigo($numeroDocumento, $tipoDocumentoId);
                $id_colaborador = $obtenerPersona[0]['id'];

                $resUsuario = Persona::create()->updateEmpresaContacto($persona[0]['id'], $id_colaborador, $contactoTipo, 1, $usuarioId, $email, $celular, $nombreContacto, $correoAlternativo);
            }

            // se actauliza persona
            else {

                $obtenerPersona = Persona::create()->getPersonaCodigo($numeroDocumento, $tipoDocumentoId);
                $id_colaborador = $obtenerPersona[0]['id'];

                $resUsuario = Persona::create()->updatePersonaContacto($persona[0]['id'], $id_colaborador, $contactoTipo, 1, $usuarioId, $email, $celular, $correoAlternativo);
            }

            if (!ObjectUtil::isEmpty($listaDireccionDetalle)) {
                foreach ($listaDireccionDetalle as $index => $item) {
                    $personaDireccionId = $item['direccion_id'];
                    $direccionTipoId = $item['direccion_tipo_id'];
                    $direccionTexto = $item['direccion'];
                    $zonaId = $item['zona_id'];
                    $referencia = $item['referencia'];

                    $latitud = (ObjectUtil::isEmpty(trim($item['latitud'])) || $item['latitud'] == trim("null") ? NULL : trim($item['latitud']));
                    $longitud = (ObjectUtil::isEmpty(trim($item['longitud'])) || $item['longitud'] == trim("null") ? NULL : trim($item['longitud']));

                    $res = Persona::create()->guardarPersonaDireccion($id_colaborador, ($index + 1), $direccionTexto, $usuarioId, $personaDireccionId, $direccionTipoId, $ubigeoId, $longitud, $latitud, $referencia, $zonaId);

                    if ($res[0]['vout_exito'] == 0) {
                        throw new WarningException("Error al guardar la dirección. " . $res[0]['vout_mensaje']);
                    }
                }
            }
            return $resUsuario;
        }

        //En caso sea vacio se registra
        else {

            //REGISTRO CONTACTO EMPRESA
            if ($tipoDocumentoId == 3) {

                $obtenerPersona = Persona::create()->getPersonaCodigo($numeroDocumento, $tipoDocumentoId);
                if (!ObjectUtil::isEmpty($obtenerPersona)) {
                    $resPersona = $obtenerPersona;
                } else {
                    $resPersona = $this->insertPersona(
                        $PersonaTipoIdo,
                        $numeroDocumento,
                        $nombres,
                        null,
                        null,
                        null,
                        $celular,
                        $email,
                        null,
                        1,
                        $usuarioId,
                        $empresa,
                        $clase,
                        null,
                        $listaDireccionDetalle,
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                        $correoAlternativo,
                        $tipoDocumentoId
                    );
                }

                $id_colaborador = $resPersona[0]['id'];
                $obtenerPersonaContacto = Persona::create()->getPersonaContactoCodigoUsuario($id_colaborador, $usuarioId);

                if (!ObjectUtil::isEmpty($obtenerPersonaContacto)) {
                    throw new WarningException("Este contacto ya esta registrado");
                } else {
                    $resUsuario = Persona::create()->insertEmpresaContacto($persona[0]['id'], $id_colaborador, $contactoTipo, 1, $usuarioId, $email, $celular, $nombreContacto, $correoAlternativo);
                    Persona::create()->guardarPersonaDireccion($id_colaborador, 1, $direccionFiscal, $usuarioId, $personaDireccionId, -1, null, null, null, null, null);

                    if (!ObjectUtil::isEmpty($listaDireccionDetalle)) {
                        foreach ($listaDireccionDetalle as $index => $item) {
                            $personaDireccionId = $item['direccion_id'];
                            $direccionTipoId = $item['direccion_tipo_id'];
                            $direccionTexto = $item['direccion'];
                            $zonaId = $item['zona_id'];
                            $referencia = $item['referencia'];

                            $latitud = (ObjectUtil::isEmpty(trim($item['latitud'])) || $item['latitud'] == trim("null") ? NULL : trim($item['latitud']));
                            $longitud = (ObjectUtil::isEmpty(trim($item['longitud'])) || $item['longitud'] == trim("null") ? NULL : trim($item['longitud']));

                            $res = Persona::create()->guardarPersonaDireccion($id_colaborador, ($index + 1), $direccionTexto, $usuarioId, $personaDireccionId, $direccionTipoId, $ubigeoId, $longitud, $latitud, $referencia, $zonaId);

                            if ($res[0]['vout_exito'] == 0) {
                                throw new WarningException("Error al guardar la dirección. " . $res[0]['vout_mensaje']);
                            }
                        }
                    }

                    return $resUsuario[0];
                }
            }

            //REGISTRO CONTACTO PERSONA
            else {

                $obtenerPersona = Persona::create()->getPersonaCodigo($numeroDocumento, $tipoDocumentoId);
                if (!ObjectUtil::isEmpty($obtenerPersona)) {
                    $resPersona = $obtenerPersona;
                } else {
                    $resPersona = $this->insertPersona(
                        $PersonaTipoIdo,
                        $numeroDocumento,
                        $nombres,
                        $apellidoPaterno,
                        $apellidoMaterno,
                        null,
                        $celular,
                        $email,
                        null,
                        1,
                        $usuarioId,
                        $empresa,
                        $clase,
                        null,
                        $listaDireccionDetalle,
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                        $correoAlternativo,
                        $tipoDocumentoId
                    );
                }

                $id_colaborador = $resPersona[0]['id'];
                $obtenerPersonaContacto = Persona::create()->getPersonaContactoCodigoUsuario($id_colaborador, $usuarioId);

                if (!ObjectUtil::isEmpty($obtenerPersonaContacto)) {
                    throw new WarningException("Este contacto ya esta registrado");
                } else {
                    $resUsuario = Persona::create()->insertPersonaContacto($persona[0]['id'], $id_colaborador, $contactoTipo, 1, $usuarioId, $email, $celular, $correoAlternativo);
                    if (!ObjectUtil::isEmpty($listaDireccionDetalle)) {
                        foreach ($listaDireccionDetalle as $index => $item) {
                            $personaDireccionId = $item['direccion_id'];
                            $direccionTipoId = $item['direccion_tipo_id'];
                            $direccionTexto = $item['direccion'];
                            $zonaId = $item['zona_id'];
                            $referencia = $item['referencia'];

                            $latitud = (ObjectUtil::isEmpty(trim($item['latitud'])) || $item['latitud'] == trim("null") ? NULL : trim($item['latitud']));
                            $longitud = (ObjectUtil::isEmpty(trim($item['longitud'])) || $item['longitud'] == trim("null") ? NULL : trim($item['longitud']));

                            $res = Persona::create()->guardarPersonaDireccion($id_colaborador, ($index + 1), $direccionTexto, $usuarioId, $personaDireccionId, $direccionTipoId, $ubigeoId, $longitud, $latitud, $referencia, $zonaId);

                            if ($res[0]['vout_exito'] == 0) {
                                throw new WarningException("Error al guardar la dirección. " . $res[0]['vout_mensaje']);
                            }
                        }
                    }
                    return $resUsuario[0];
                }
            }
        }
    }

    public function TraerConductores()
    {
        return Persona::create()->TraerConductores();
    }

    public function TraerConductor($persona_id)
    {
        return Persona::create()->TraerConductor($persona_id);
    }

    public function obtenerPersonaActivoXStringBusqueda($stringPersona, $personaId = null)
    {
        return Persona::create()->obtenerPersonaActivoXStringBusqueda($stringPersona, $personaId);
    }

    public function obtenerPersonaActivoXStringBusqueda2($stringPersona, $personaId = null)
    {
        return Persona::create()->obtenerPersonaActivoXStringBusqueda2($stringPersona, $personaId);
    }

    public function obtenerPersonaContactoXUsuarioId($usuarioId, $personaContactoId = NULL)
    {
        return PersonaContacto::create()->obtenerPersonaContactoXUsuarioId($usuarioId, $personaContactoId);
    }

    public function obtenerPersonaMisContactoXUsuarioId($usuarioId, $personaContactoId = NULL)
    {
        return PersonaContacto::create()->obtenerPersonaMisContactoXUsuarioId($usuarioId, $personaContactoId);
    }

    public function obtenerContactoXId($usuarioId, $contactoId)
    {
        $dataContacto = PersonaNegocio::create()->obtenerPersonaMisContactoXUsuarioId($usuarioId, $contactoId);
        if (!ObjectUtil::isEmpty($dataContacto)) {
            $dataContacto[0]['direcciones'] = PersonaNegocio::create()->obtenerContactoDireccionXPersonaIdXUsuarioId($contactoId, $usuarioId);
        }
        //        $respuesta = new stdClass();
        //        $respuesta->dataContacto = $dataContacto;
        //        $respuesta->dataContactoDireccion = $dataDestinatarioDireccion;
        return $dataContacto;
    }

    public function consultarDocumento($tipoDocumento, $numeroDocumento)
    {

        $responsePersona = Persona::create()->getPersonaCodigo($numeroDocumento, $tipoDocumento);
        if (!ObjectUtil::isEmpty($responsePersona)) {
            $nombre_persona = $responsePersona[0]['nombre_persona'];
            $paterno_persona = $responsePersona[0]['paterno_persona'];
            $materno_persona = $responsePersona[0]['materno_persona'];
            $direccion_fiscal = $responsePersona[0]['direccion_fiscal'];

            $datos = new ObjectUtil();
            $datos->tipoDocumento = $tipoDocumento;
            $datos->numeroDocumento = $numeroDocumento;
            $datos->nombrePersona = $nombre_persona;
            $datos->paternoPersona = $paterno_persona;
            $datos->maternoPersona = $materno_persona;
            $datos->direccionFiscal = $direccion_fiscal;

            return $datos;
        }
    }

    public function obtenerContactoDireccionXPersonaIdXUsuarioId($personaId, $usuarioId)
    {
        return Persona::create()->obtenerContactoDireccionXPersonaIdXUsuarioId($personaId, $usuarioId);
    }

    public function obtenerPersonaDireccionXUsuarioId($usuarioId)
    {
        return Persona::create()->obtenerPersonaDireccionXUsuarioId($usuarioId);
    }

    public function eliminarPersonaContacto($persona_id, $usuarioCreacion)
    {
        return Persona::create()->eliminarPersonaContactoMovil($persona_id, $usuarioCreacion);
    }

    public function ActivarPersonaContacto($persona_id, $estado, $usuarioCreacion)
    {
        return Persona::create()->ActivarPersonaContacto($persona_id, $estado, $usuarioCreacion);
    }
}
