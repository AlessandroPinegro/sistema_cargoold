<?php

require_once __DIR__ . '/../../modelo/almacen/ACCaja.php';
require_once __DIR__ . '/../../modelo/almacen/Caja.php';
require_once __DIR__ . '/../../modelo/almacen/Bien.php';
require_once __DIR__ . '/../../modelo/itec/Usuario.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/MovimientoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/OperacionNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/DocumentoTipoNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/PerfilAgenciaCajaNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/almacen/CajaNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/EmailPlantillaNegocio.php';
require_once __DIR__ . '/EmailEnvioNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/commons/ConstantesNegocio.php';
require_once __DIR__ . '/../../modeloNegocio/commons/SeguridadNegocio.php';

require_once __DIR__ . '/CuentaNegocio.php';
require_once __DIR__ . '/PeriodoNegocio.php';

class ACCajaNegocio extends ModeloNegocioBase {

    /**
     * 
     * @return ACCajaNegocio
     */
    static function create() {
        return parent::create();
    }

    public function obtenerBienXId($bien_id, $empresaId, $comodin, $idEditar = null) {
        $dataAperturaCaja = ACCaja::create()->obteneAperturaCajaUltimoXEmpresaId($empresaId, $idEditar);

        if ($comodin == "apertura") {
            $buscarBien = "0";
            $fechaInicial = "";
        } else if ($comodin == "cierre") {
            $buscarBien = "0";
//            $fechaInicial=$dataAperturaCaja[0]['fecha_apertura'];
            $fechaInicial = "";
        }

        $dataBien = Bien::create()->obtenerBienInventarioXEmpresa($bien_id, "0", $fechaInicial, "", $empresaId, $buscarBien);
        return $dataBien;
    }

    public function validarData($dataInicial, $dataFinal, $comodin, $empresaId = NULL, $cajaId = NULL, $usuarioId = NULL) {
        $mensaje = "Existen cambios con respecto a la carga inicial al momento que se habilitÃ³ Ã©sta pÃ¡gina, por favor vuelva a cargar el sistema";

        if ($comodin == "apertura") {
            //VERIFICAMOS SI EXISTE UNA CAJA APERTURADA
            $dataUltimaApertura = $this->obteneACCajaUltimoXUsuarioId($empresaId, NULL, NULL, $cajaId);
            if (!ObjectUtil::isEmpty($dataUltimaApertura)) {
                throw new WarningException("Ya existe una caja aperturada, por favor vuelva a cargar el sistema");
            }

            //VERIFICAMOS SI EXISTE UNA CAJA APERTURADA POR USUARIO
            $dataUltimaAperturaUsuario = $this->obteneACCajaUltimoXUsuarioId($empresaId, $usuarioId);
            if (!ObjectUtil::isEmpty($dataUltimaAperturaUsuario)) {
                throw new WarningException("El usuario ya tiene una caja aperturada: " . $dataUltimaAperturaUsuario[0]["caja_descripcion"]);
            }
        }




        //VALIDAR
        if (!ObjectUtil::isEmpty($dataInicial["dataACCaja"])) {
            if ($comodin == "apertura") {
                if ($dataInicial["dataACCaja"][0]["importe_cierre"] != $dataFinal->dataACCaja[0]["importe_cierre"]) {
                    throw new WarningException($mensaje);
                }
            }
        }

        if (!ObjectUtil::isEmpty($dataInicial["dataAperturaCaja"])) {
            if ($comodin == "cierre") {
                if ($dataInicial["dataAperturaCaja"][0]["importe_apertura"] != $dataFinal->dataAperturaCaja[0]["importe_apertura"]) {
                    throw new WarningException($mensaje);
                }
            }
        }

        if (!ObjectUtil::isEmpty($dataInicial["dataVisa"])) {
            if ($comodin == "cierre") {
                if ($dataInicial["dataVisa"][0]["importe_total"] != $dataFinal->dataVisa[0]["importe_total"]) {
                    throw new WarningException($mensaje);
                }
            }
        }

        if (!ObjectUtil::isEmpty($dataInicial["dataDeposito"])) {
            if ($comodin == "cierre") {
                if ($dataInicial["dataDeposito"][0]["importe_total"] != $dataFinal->dataDeposito[0]["importe_total"]) {
                    throw new WarningException($mensaje);
                }
            }
        }

        if (!ObjectUtil::isEmpty($dataInicial["dataAperturaCierre"])) {
            if ($comodin == "apertura") {
                if ($dataInicial["dataAperturaCierre"][0]["importe_apertura"] != $dataFinal->dataAperturaCierre[0]["importe_apertura"]) {
                    throw new WarningException($mensaje);
                }
            } else if ($comodin == "cierre") {
                if ($dataInicial["dataAperturaCierre"][0]["importe_cierre"] != $dataFinal->dataAperturaCierre[0]["importe_cierre"]) {
                    throw new WarningException($mensaje);
                }

                if ($dataInicial["dataAperturaCierre"][0]["traslado"] != $dataFinal->dataAperturaCierre[0]["traslado"]) {
                    throw new WarningException($mensaje);
                }

                if ($dataInicial["dataAperturaCierre"][0]["visa"] != $dataFinal->dataAperturaCierre[0]["visa"]) {
                    throw new WarningException($mensaje);
                }

                if ($dataInicial["dataAperturaCierre"][0]["deposito"] != $dataFinal->dataAperturaCierre[0]["deposito"]) {
                    throw new WarningException($mensaje);
                }
            }
        }

        if (!ObjectUtil::isEmpty($dataInicial["dataIngresoSalida"])) {
            if ($comodin == "cierre") {
                if ($comodin == "cierre") {
                    //if($dataInicial["dataIngresoSalida"][0]["ing_sal"] != $dataFinal->dataIngresoSalida[0]["ing_sal"]){
                    if (count($dataInicial["dataIngresoSalida"]) != count($dataFinal->dataIngresoSalida)) {
                        throw new WarningException($mensaje);
                    } else {
                        foreach ($dataInicial["dataIngresoSalida"] as $di) {
                            foreach ($dataFinal->dataIngresoSalida as $df) {
                                if ($df["ing_sal"] == $di["ing_sal"]) {
                                    if ($di["total_conversion"] != $df["total_conversion"]) {
                                        throw new WarningException($mensaje);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function obtenerConfiguracionesInicialesApertura($empresaId, $cajaId, $idEditar,$ip=null,$bandera=null) {
        

    
          $ipCajaF= ACCaja::create()->obtenerAccajaIpxusuario($cajaId);
         $ipCaja=$ipCajaF[0]['ip_caja'];
      
        
        $ipSession = $_SERVER['REMOTE_ADDR'];

        if ($ipSession != $ipCaja && ConfigGlobal::VALIDACION_IP) {
            throw new WarningException("Su ip actual ($ipSession) no coincide con la ip configurada para esta caja: ($ipCaja).");
        }

        $dataAperturaCierre = ACCaja::create()->obtenerDataAperturaCierreXId($idEditar, 1);

        if (!ObjectUtil::isEmpty($idEditar)) {
            $bienIds = (!ObjectUtil::isEmpty($dataAperturaCierre[0]['bien_ids']) ? $dataAperturaCierre[0]['bien_ids'] : "0");
            $fechaInicio = "";
            $stock = "0";
            $buscarBien = "0";
            $indicador = "1";
        } else {
            $bienIds = "";
            $stock = "1";
            $fechaInicio = "";
            $buscarBien = null;
            $indicador = null;
        }
        $resultado = new stdClass();
        $resultado->dataCaja = Caja::create()->obtenerXId($cajaId);
        $resultado->dataACCaja = ACCaja::create()->obteneACCajaUltimoXEmpresaId($empresaId, $idEditar, $cajaId);
        $resultado->dataAperturaCierre = $dataAperturaCierre;
        $resultado->dataApertura = ACCaja::create()->obtenerDataNuevaAperturaCierre($empresaId, "Apertura", $cajaId);

        //$fechaFin= !ObjectUtil::isEmpty(dataAperturaCierre)?$dataAperturaCierre[0]["fecha_cierre"]:"";
        $fechaFin = "";

//        $resultado->dataAperturaInventarioTabla = Bien::create()->obtenerBienInventarioXEmpresa($bienIds, $stock, 
//                                                  $fechaInicio, $fechaFin, $empresaId,$buscarBien,$indicador,$idEditar);
//        $resultado->dataAperturaInventarioCombo = Bien::create()->obtenerBienXTipoBien();
        return $resultado;
    }

    public function guardarAperturaCaja($idEditar, $usuCreacion, $importeApertura, $comentario, $empresaId, $indicador, $aperturaSugerido, $dataInicial, $cajaId) {

        $dataFinal = $this->obtenerConfiguracionesInicialesApertura($empresaId, $cajaId, $idEditar,$ip,$bandera=1);
        $validar = $this->validarData($dataInicial, $dataFinal, "apertura", $empresaId, $cajaId, $usuCreacion);

        $isEditado = $idEditar;

        $dataCierre = ACCaja::create()->obteneACCajaUltimoXEmpresaId($empresaId, $idEditar, $cajaId);

        if (!ObjectUtil::isEmpty($dataCierre)) {
            $importeCierre = $dataCierre[0]['importe_cierre'];
        } else {
            $importeCierre = "0.00";
            $dataCierre[0]['fecha_cierre_formato'] = "";
            $dataCierre[0]['importe_cierre'] = "0.00";
        }

        if (!ObjectUtil::isEmpty($idEditar)) {
            $dataAperturaCierre = ACCaja::create()->obtenerDataAperturaCierreXId($idEditar, 1);
            $importeCierre = $dataAperturaCierre[0]['importe_apertura'];
        }

        if ($importeCierre * 1 != $importeApertura * 1) {
            $is_pintar_apertura = 1;
            $data = new stdClass();
            $data->dataCierre = $dataCierre;

            if ($importeCierre * 1 > $importeApertura * 1) {
                $valorImporte = ($importeCierre * 1) - ($importeApertura * 1);
                $valorActividad = 36;
                $documentoTipoId = ACCaja::create()->obtenerDocumentoTipoIdXEmpresaId($empresaId, "egreso ", 8);
            } else {
                $valorImporte = ($importeApertura * 1) - ($importeCierre * 1);
                $valorActividad = 35;
                $documentoTipoId = ACCaja::create()->obtenerDocumentoTipoIdXEmpresaId($empresaId, "ingreso ", 7);
            }
            $documentoTipoDato = DocumentoTipoNegocio::create()->obtenerDocumentoTipoDato($documentoTipoId[0]["id"], $usuCreacion);

            $camposDinamicos = array();
            foreach ($documentoTipoDato as $dtd) {
                if ($dtd["tipo"] == 5) {
                    $valor = 22406;
                } else if ($dtd["tipo"] == 8) {
                    $valor = $dtd["data"];
                } else if ($dtd["tipo"] == 9) {
                    $valor = $dtd["data"];
                    $fechaEmision = $valor;
                } else if ($dtd["tipo"] == 14) {
                    $valor = $valorImporte;
                } else if ($dtd["tipo"] == 20) {
                    $valor = $dtd["numero_defecto"];
                } else if ($dtd["tipo"] == 21) {
                    $valor = $valorActividad;
                } else if ($dtd["tipo"] == 7) {
                    $valor = $dtd["cadena_defecto"];
                }

                array_push($camposDinamicos, array('id' => $dtd["id"], 'tipo' => $dtd["tipo"],
                    'opcional' => $dtd["opcional"], 'descripcion' => $dtd["descripcion"],
                    'valor' => $valor));
            }

            //------------- PERIODO ------------
            $dataPeriodo = PeriodoNegocio::create()->obtenerPeriodoAbiertoXEmpresa($empresaId);
            if (ObjectUtil::isEmpty($dataPeriodo)) {
                throw new WarningException("No existe periodo abierto.");
            }
            //OBTENIENDO EL PERIODO ASOCIADO A LA FECHA DE EMISION           $fechaEmision = date_create($parametros->camposDinamicos->fechaEmision);
//            $fechaEmisionForm = date_create($fechaEmision);
//            $fechaEmisionForm = date_format($fechaEmisionForm, 'd/m/Y');          
            $periodoId = PeriodoNegocio::create()->obtenerPeriodoIdXFecha($dataPeriodo, $fechaEmision);
            if (ObjectUtil::isEmpty($periodoId)) {
                throw new WarningException("Periodo invÃ¡lido, el periodo asociado a la fecha: " . $fechaEmision . " no esta abierto.");
            }
            //FIN PERIODO

            $documentoID = OperacionNegocio::create()->guardar(159, $usuCreacion, $documentoTipoId[0]["id"], $camposDinamicos, $comentario, "Ajuste apertura de caja", 2, null, null, $periodoId);
        } else {
            $data = new stdClass();
            $data->dataCierre = null;
            $is_pintar_apertura = 0;
        }

        $res = ACCaja::create()->guardarAperturaCaja($idEditar, $usuCreacion, $importeApertura, $aperturaSugerido, $comentario, $empresaId, $is_pintar_apertura, $cajaId);

        if (!ObjectUtil::isEmpty($data->dataCierre)) {
            //ENVIAR CORREO ADMIN            
            if (ObjectUtil::isEmpty($idEditar)) {
                $idEditar = $res[0]['id'];
            }
            $this->guardarEmailEnvioAperturaCaja($data, $idEditar, $isEditado);
        }

        if ($res[0]["vout_exito"] == 1) {
            $this->setMensajeEmergente($res[0]["vout_mensaje"]);
        } else {
            throw new WarningException($res[0]["vout_mensaje"]);
        }

        return $res;
    }

    public function guardarEmailEnvioAperturaCaja($data, $id, $isEditado) {
        $dataCierre = $data->dataCierre;

        $dataApertura = ACCaja::create()->obtenerDataAperturaCierreXId($id, 0);

        $plantilla = EmailPlantillaNegocio::create()->obtenerEmailPlantillaXID(19);
        $correosPlantilla = EmailPlantillaNegocio::create()->obtenerEmailXDestinatario($plantilla[0]["destinatario"], null, null);

        $correos = implode(";", $correosPlantilla);

        //logica correo:
        $asunto = 'Apertura de caja ' . $dataApertura[0]['fecha_apertura_formato'] . ' - ' . $dataApertura[0]['empresa_descripcion'];
        $cuerpo = $plantilla[0]["cuerpo"];
        $tituloCorreo = 'Apertura de caja';
        $descripcionCorreo = '';

        $texto = !ObjectUtil::isEmpty($isEditado) ? "actualizÃ³" : "registrÃ³";

        if (!ObjectUtil::isEmpty($dataCierre)) {
            $descripcionCorreo .= 'Se ' . $texto . ' una apertura de caja con fecha: ' . $dataApertura[0]['fecha_apertura_formato'] . ' por el usuario ' . $dataApertura[0]['usuario_apertura'] . ', en la tienda ' . $dataApertura[0]['empresa_descripcion'] . '. <br><br>';

            if ($dataCierre[0]['fecha_cierre_formato'] == "") {
                $descripcionCorreo .= '- Importe de cierre anterior: S/. ' . number_format($dataCierre[0]['importe_cierre'], 2, ".", ",") . '.<br>';
            } else {
                $descripcionCorreo .= '- El importe de cierre anterior (' . $dataCierre[0]['fecha_cierre_formato'] . ') es: S/. ' . number_format($dataCierre[0]['importe_cierre'], 2, ".", ",") . '.<br>';
            }

            $descripcionCorreo .= '- El importe de apertura registrado es: S/. ' . number_format($dataApertura[0]['importe_apertura'], 2, ".", ",") . '.<br><br>';
            $descripcionCorreo .= 'El importe de apertura y cierre son diferentes, motivo por el cual se generÃ³ este email.<br><br>';
        }

        $cuerpo = str_replace("[|tituloCorreo|]", $tituloCorreo, $cuerpo);
        $cuerpo = str_replace("[|descripcionCorreo|]", $descripcionCorreo, $cuerpo);

        $resEmail = EmailEnvioNegocio::create()->insertarEmailEnvio($correos, $asunto, $cuerpo, 1, 1);
    }

    public function obtenerConfiguracionesInicialesCierre($empresaId, $cajaId, $idEditar,$usuCreacion) {
        
        $perfil =Perfil::create()->obtnerPerfilCargoXUsuarioId($usuCreacion);
        $perfil_usuario = array_column($perfil, 'id');
        $perfil_asignado =ConfigGlobal::PERFILES_ASIGNADOS_CIERE_CAJA;

        $interseccion = array_intersect($perfil_usuario, $perfil_asignado);

        if (empty($interseccion)) {
         
            $dataAperturaCaja = ACCajaNegocio::create()->obteneACCajaUltimoXUsuarioId($empresaId, NULL, NULL, $cajaId);
            $usuario=$dataAperturaCaja[0]['usuario_apertura_id']; 
            $usuario_nombre=$dataAperturaCaja[0]['usuario_apertura']; 
            $ipCajaF= ACCaja::create()->obtenerAccajaIpxusuario($cajaId);
            $ipCaja=$ipCajaF[0]['ip_caja'];
         
           
           $ipSession = $_SERVER['REMOTE_ADDR'];
    
    
           if ($ipSession != $ipCaja && ConfigGlobal::VALIDACION_IP) {
               throw new WarningException("Su ip actual ($ipSession) no coincide con la ip configurada para esta caja: ($ipCaja).");
           }
            
           if ($usuCreacion != $usuario ) {
            throw new WarningException("Esta caja solo puede ser cerrada por el usuario que la aperturo : ($usuario_nombre).");
        }
       
    } 




        $dataAperturaCierre = ACCaja::create()->obtenerDataAperturaCierreXId($idEditar, (ObjectUtil::isEmpty($idEditar) ? 1 : 2));

        $dataAperturaCaja = ACCaja::create()->obteneAperturaCajaUltimoXEmpresaId($empresaId, $idEditar, $cajaId);

        if (!ObjectUtil::isEmpty($idEditar)) {
            $bienIds = $dataAperturaCierre[0]['bien_ids'];
            $stock = "0";
            $indicador = 2;
        } else {
            $bienIds = $dataAperturaCaja[0]['bien_ids'];
            $idEditar = $dataAperturaCaja[0]['ac_caja_id'];
            $stock = "1";
            $indicador = 1;
        }
        $resultado = new stdClass();
        $resultado->dataAperturaCaja = $dataAperturaCaja;
        $resultado->dataAperturaCierre = $dataAperturaCierre;
        $resultado->dataApertura = ACCaja::create()->obtenerDataNuevaAperturaCierre($empresaId, "Cierre");

        $idEditar = (!ObjectUtil::isEmpty($idEditar) ? $idEditar : $dataAperturaCaja[0]['ac_caja_id']);
     
//        $fechaInicial = ((array) $resultado->dataAperturaCaja[0]['fecha_apertura'])['date'];  
//        $fechaFinal = ((array) $resultado->dataAperturaCaja[0]['fecha_cierre'])['date'];  
        $ac_caja=$dataAperturaCaja[0]['ac_caja_id'];
        $dataCajaChica = null;
        if (ObjectUtil::isEmpty($resultado->dataAperturaCierre)) {
            $dataCajaChica = ACCaja::create()->obtenerSaldoCajaXEmpresaIdXAcCajaId($idEditar);
        }
        $resultado->dataCajaChica = $dataCajaChica;

        $resultado->dataVisa = ACCaja::create()->obtenerImporteTotalDocumentoPagoIdentificadorNegocioXAcCajaId($idEditar, DocumentoTipoNegocio::IN_TICKET_POS);
        $resultado->dataDeposito = ACCaja::create()->obtenerImporteTotalDocumentoPagoIdentificadorNegocioXAcCajaId($idEditar, DocumentoTipoNegocio::IN_TICKET_DEPOSITO);
        $resultado->dataTransferencia = ACCaja::create()->obtenerImporteTotalDocumentoPagoIdentificadorNegocioXAcCajaId($idEditar, DocumentoTipoNegocio::IN_TICKET_TRANSFERENCIA);

        $resultado->dataTraslado = ACCaja::create()->obtenerImporteTotalDocumentoTrasladoXacCajaId($idEditar);
        $resultado->dataIngresoSalida = ACCaja::create()->obtenerIngresoSalidaXAcCajaId($idEditar);
        
        $resultado->dataEfectivo= ACCaja::create()->obtenerDocumentoIngresoDatoxCajaId($ac_caja,282);
        $resultado->dataIngresos = ACCaja::create()->obtenerDocumentoIngresoxCajaId($ac_caja,109);
        $resultado->dataEgresosPOS = ACCaja::create()->obtenerDocumentoIngresoxCajaId($ac_caja,286);
        $resultado->dataEgresosOtros = ACCaja::create()->obtenerDocumentoIngresoxCajaId($ac_caja,287);
         $resultado->dataEgresosEfectivo = ACCaja::create()->obtenerDocumentoIngresoxCajaId($ac_caja,108);
        return $resultado;
    }

    public function guardarCierreCaja($idEditar, $id, $usuCreacion, $importeCierre, $comentario, $empresaId, $indicador, $visa, $traslado, $lstImportes_modificados, $is_pintar_cierre, $is_pintar_visa, $total_cierre, $cierre_sugerido, $visa_sugerido, $dataInicial, $deposito_sugerido, $deposito, $is_pintar_deposito, $transferencia, $is_pintar_transferencia, $transferencia_sugerido, 
            $cajaId,$egreso,$ingreso,  $egresoOtros,$egresoPos,$efectivo) {

        $isEditado = $idEditar;
        $dataFinal = $this->obtenerConfiguracionesInicialesCierre($empresaId, $cajaId, $idEditar,$usuCreacion);
        $validar = $this->validarData($dataInicial, $dataFinal, "cierre");

        if (!ObjectUtil::isEmpty($idEditar)) {
            $dataAperturaCierre = ACCaja::create()->obtenerDataAperturaCierreXId($idEditar, 1);
            $importeTraslado = (!ObjectUtil::isEmpty($dataAperturaCierre[0]['traslado']) ? $dataAperturaCierre[0]['traslado'] * 1 : 0);
            $importeVisa = $dataAperturaCierre[0]['visa'];
            $importeDeposito = $dataAperturaCierre[0]['deposito'];
            $importeTransferencia = $dataAperturaCierre[0]['transferencia'];
            $importe_cierre = (!ObjectUtil::isEmpty($dataAperturaCierre[0]['importe_cierre']) ? $dataAperturaCierre[0]['importe_cierre'] * 1 : 0);
            $importe_cierre = $importe_cierre + $importeTraslado;
        } else {
            $importeTraslado = "0.00";

            if ($visa * 1 == $visa_sugerido * 1) {
                $importeVisa = $visa * 1;
            } else {
                $importeVisa = $visa_sugerido * 1;
            }

            if ($deposito * 1 == $deposito_sugerido * 1) {
                $importeDeposito = $deposito;
            } else {
                $importeDeposito = $deposito_sugerido * 1;
            }

            $importeTransferencia = $transferencia_sugerido * 1;

            if ($total_cierre * 1 == $cierre_sugerido * 1) {
                $importe_cierre = $total_cierre * 1;
            } else {
                $importe_cierre = $cierre_sugerido * 1;
            }
        }

        if (!ObjectUtil::isEmpty($lstImportes_modificados)) {
            foreach ($lstImportes_modificados as $monto) {
                if ($monto["descripcion"] == "Caja") {
                    //$cierre_sugerido=($monto["monto_original"] * 1) - ($traslado * 1);
                    $cierre_sugerido = ($monto["monto_original"] * 1);
                }
            }
        } else {
            $cierre_sugerido = $importeCierre;
        }

        if ($total_cierre == $importeCierre) {
            $totalCierre = $total_cierre;
        } else {
            if (($importeCierre * 1) + ($traslado * 1) != $total_cierre) {
                $totalCierre = $total_cierre;
            } else {
                $totalCierre = ($importeCierre * 1) + ($traslado * 1);
            }
        }

        //------------- PERIODO ------------
        $dataPeriodo = PeriodoNegocio::create()->obtenerPeriodoAbiertoXEmpresa($empresaId);
        if (ObjectUtil::isEmpty($dataPeriodo)) {
            throw new WarningException("No existe periodo abierto.");
        }
        //OBTENIENDO EL PERIODO ASOCIADO A LA FECHA DE EMISION           $fechaEmision = date_create($parametros->camposDinamicos->fechaEmision);
//            $fechaEmisionForm = date_create($fechaEmision);
//            $fechaEmisionForm = date_format($fechaEmisionForm, 'd/m/Y');          
        $periodoId = PeriodoNegocio::create()->obtenerPeriodoIdXFecha($dataPeriodo, date('d/m/Y'));
//        if (ObjectUtil::isEmpty($periodoId)) {
//            throw new WarningException("Periodo invÃ¡lido, el periodo asociado a la fecha: " . $fechaEmision . " no esta abierto.");
//        }
        //FIN PERIODO

        if (($totalCierre * 1) != ($importe_cierre * 1)) {
            if ($totalCierre * 1 < $importe_cierre * 1) {
                $valorImporte = ($importe_cierre * 1) - ($totalCierre * 1);
                $valorActividad = 36;
                $documentoTipoId = ACCaja::create()->obtenerDocumentoTipoIdXEmpresaId($empresaId, "egreso ", 8);
            } else {
                $valorImporte = ($totalCierre * 1) - ($importe_cierre * 1);
                $valorActividad = 35;
                $documentoTipoId = ACCaja::create()->obtenerDocumentoTipoIdXEmpresaId($empresaId, "ingreso ", 7);
            }

            $documentoTipoDato = DocumentoTipoNegocio::create()->obtenerDocumentoTipoDato($documentoTipoId[0]["id"], $usuCreacion);

            $camposDinamicos = array();
            foreach ($documentoTipoDato as $dtd) {
                if ($dtd["tipo"] == 5) {
                    $valor = 22406;
                } else if ($dtd["tipo"] == 8) {
                    $valor = $dtd["data"];
                } else if ($dtd["tipo"] == 9) {
                    $valor = $dtd["data"];
                    $fechaEmision = $valor;
                } else if ($dtd["tipo"] == 14) {
                    $valor = $valorImporte;
                } else if ($dtd["tipo"] == 20) {
                    $valor = $dtd["numero_defecto"];
                } else if ($dtd["tipo"] == 21) {
                    $valor = $valorActividad;
                } else if ($dtd["tipo"] == 7) {
                    $valor = $dtd["cadena_defecto"];
                }

                array_push($camposDinamicos, array('id' => $dtd["id"], 'tipo' => $dtd["tipo"],
                    'opcional' => $dtd["opcional"], 'descripcion' => $dtd["descripcion"],
                    'valor' => $valor));
            }

            $documentoID = OperacionNegocio::create()->guardar(159, $usuCreacion, $documentoTipoId[0]["id"], $camposDinamicos, $comentario, "Ajuste cierre de caja - Total Cierre", 2, null, null, $periodoId, $idEditar);
        }

        if (($traslado * 1) != ($importeTraslado * 1)) {
            if ($traslado * 1 > $importeTraslado * 1) {
                $valorImporte = ($traslado * 1) - ($importeTraslado * 1);
                $valorActividad = 36;
                $documentoTipoId = ACCaja::create()->obtenerDocumentoTipoIdXEmpresaId($empresaId, "egreso ", 8);
            } else {
                $valorImporte = ($importeTraslado * 1) - ($traslado * 1);
                $valorActividad = 35;
                $documentoTipoId = ACCaja::create()->obtenerDocumentoTipoIdXEmpresaId($empresaId, "ingreso ", 7);
            }

            $documentoTipoDato = DocumentoTipoNegocio::create()->obtenerDocumentoTipoDato($documentoTipoId[0]["id"], $usuCreacion);

            $camposDinamicos = array();
            foreach ($documentoTipoDato as $dtd) {
                if ($dtd["tipo"] == 5) {
                    $valor = 22406;
                } else if ($dtd["tipo"] == 8 || $dtd["tipo"] == 9) {
                    $valor = $dtd["data"];
                } else if ($dtd["tipo"] == 14) {
                    $valor = $valorImporte;
                } else if ($dtd["tipo"] == 20) {
                    $valor = $dtd["numero_defecto"];
                } else if ($dtd["tipo"] == 21) {
                    $valor = $valorActividad;
                } else if ($dtd["tipo"] == 7) {
                    $valor = $dtd["cadena_defecto"];
                }

                array_push($camposDinamicos, array('id' => $dtd["id"], 'tipo' => $dtd["tipo"],
                    'opcional' => $dtd["opcional"], 'descripcion' => $dtd["descripcion"],
                    'valor' => $valor));
            }

            $documentoID = OperacionNegocio::create()->guardar(159, $usuCreacion, $documentoTipoId[0]["id"], $camposDinamicos, $comentario, "Ajuste cierre de caja - Importe traslado", 2, null, null, $periodoId, $idEditar);

            if ($documentoTipoId[0]["tipo"] == 7) {
                $documentoTipoGeneraId = ACCaja::create()->obtenerDocumentoTipoIdXEmpresaId($empresaId, "egreso ", 8);
                //$actividadRD= Configuraciones::ACTIVIDAD_TRANSFERENCIA_EGRESO;
                $actividadRD = 36;
            } else {
                $documentoTipoGeneraId = ACCaja::create()->obtenerDocumentoTipoIdXEmpresaId($empresaId, "ingreso ", 7);
                //$actividadRD= Configuraciones::ACTIVIDAD_TRANSFERENCIA_INGRESO;
                $actividadRD = 35;
            }

            $cuentaDestinoId = CuentaNegocio::ID_CUENTA_TRANSFERENCIA_CAJA;
            $camposDinamicosRD = $camposDinamicos;
            $configuraciones = DocumentoTipoNegocio::create()->obtenerDocumentoTipoDatoSimple($documentoTipoGeneraId[0]["id"]);
            foreach ($configuraciones as $indexConfig => $itemDtd) {
                foreach ($camposDinamicosRD as $indexCampos => $valorDtd) {
                    if ((int) $itemDtd["tipo"] === (int) $valorDtd["tipo"]) {
                        $camposDinamicosRD[$indexCampos]["id"] = $itemDtd["id"];
                        if ($itemDtd["tipo"] == 8) {
                            $camposDinamicosRD[$indexCampos]["valor"] = DocumentoNegocio::create()->obtenerNumeroAutoIncrementalXDocumentoTipo($documentoTipoGeneraId[0]["id"]);
                        }
                        if ($itemDtd["tipo"] == 20) {
                            $camposDinamicosRD[$indexCampos]["valor"] = $cuentaDestinoId;
                        }
                        if ($itemDtd["tipo"] == 21) {
                            $camposDinamicosRD[$indexCampos]["valor"] = $actividadRD;
                        }
                    }
                }
            }

            $documentoRetiroDepositoID = OperacionNegocio::create()->guardar(159, $usuCreacion, $documentoTipoGeneraId[0]["id"], $camposDinamicosRD, "Transferencia automatico desde cierre de caja", "Ajuste cierre de caja - Trasnferencia", 2, null, null, $periodoId, $idEditar);
            if ($cuentaDestinoId != 0) {
                //relaciones de documentos
                $resultado = DocumentoNegocio::create()->guardarDocumentoRelacionado($documentoID, $documentoRetiroDepositoID, 1, 1, $usuCreacion);
            }
        }

        if (($visa * 1) != ($importeVisa * 1)) {
            if ($visa * 1 > $importeVisa * 1) {
                $valorImporte = ($visa * 1) - ($importeVisa * 1);
                $valorActividad = 35;
                $documentoTipoId = ACCaja::create()->obtenerDocumentoTipoIdXEmpresaId($empresaId, "ingreso ", 7);
            } else {
                $valorImporte = ($importeVisa * 1) - ($visa * 1);
                $valorActividad = 36;
                $documentoTipoId = ACCaja::create()->obtenerDocumentoTipoIdXEmpresaId($empresaId, "egreso ", 8);
            }

            $documentoTipoDato = DocumentoTipoNegocio::create()->obtenerDocumentoTipoDato($documentoTipoId[0]["id"], $usuCreacion);

            $camposDinamicos = array();
            foreach ($documentoTipoDato as $dtd) {
                if ($dtd["tipo"] == 5) {
                    $valor = 22406;
                } else if ($dtd["tipo"] == 8 || $dtd["tipo"] == 9) {
                    $valor = $dtd["data"];
                } else if ($dtd["tipo"] == 14) {
                    $valor = $valorImporte;
                } else if ($dtd["tipo"] == 20) {
                    $valor = CuentaNegocio::ID_CUENTA_VISA_CAJA;
                } else if ($dtd["tipo"] == 21) {
                    $valor = $valorActividad;
                } else if ($dtd["tipo"] == 7) {
                    $valor = $dtd["cadena_defecto"];
                }

                array_push($camposDinamicos, array('id' => $dtd["id"], 'tipo' => $dtd["tipo"],
                    'opcional' => $dtd["opcional"], 'descripcion' => $dtd["descripcion"],
                    'valor' => $valor));
            }

            $documentoID = OperacionNegocio::create()->guardar(159, $usuCreacion, $documentoTipoId[0]["id"], $camposDinamicos, $comentario, "Ajuste cierre de caja - Importe VISA", 2, null, null, $periodoId, $idEditar);
        }

        if (($deposito * 1) != ($importeDeposito * 1)) {
            if ($deposito * 1 > $importeDeposito * 1) {
                $valorImporte = ($deposito * 1) - ($importeDeposito * 1);
                $valorActividad = 35;
                $documentoTipoId = ACCaja::create()->obtenerDocumentoTipoIdXEmpresaId($empresaId, "ingreso ", 7);
            } else {
                $valorImporte = ($importeDeposito * 1) - ($deposito * 1);
                $valorActividad = 36;
                $documentoTipoId = ACCaja::create()->obtenerDocumentoTipoIdXEmpresaId($empresaId, "egreso ", 8);
            }

            $documentoTipoDato = DocumentoTipoNegocio::create()->obtenerDocumentoTipoDato($documentoTipoId[0]["id"], $usuCreacion);

            $camposDinamicos = array();
            foreach ($documentoTipoDato as $dtd) {
                if ($dtd["tipo"] == 5) {
                    $valor = 22406;
                } else if ($dtd["tipo"] == 8 || $dtd["tipo"] == 9) {
                    $valor = $dtd["data"];
                } else if ($dtd["tipo"] == 14) {
                    $valor = $valorImporte;
                } else if ($dtd["tipo"] == 20) {
                    $valor = CuentaNegocio::ID_CUENTA_DEPOSITO_CAJA;
                } else if ($dtd["tipo"] == 21) {
                    $valor = $valorActividad;
                } else if ($dtd["tipo"] == 7) {
                    $valor = $dtd["cadena_defecto"];
                }

                array_push($camposDinamicos, array('id' => $dtd["id"], 'tipo' => $dtd["tipo"],
                    'opcional' => $dtd["opcional"], 'descripcion' => $dtd["descripcion"],
                    'valor' => $valor));
            }

            $documentoID = OperacionNegocio::create()->guardar(159, $usuCreacion, $documentoTipoId[0]["id"], $camposDinamicos, $comentario, "Ajuste cierre de caja - Importe DepÃ³sito", 2, null, null, $periodoId, $idEditar);
        }

        if (($transferencia * 1) != ($importeTransferencia * 1)) {
            if ($transferencia * 1 > $importeTransferencia * 1) {
                $valorImporte = ($transferencia * 1) - ($importeTransferencia * 1);
                $valorActividad = 35;
                $documentoTipoId = ACCaja::create()->obtenerDocumentoTipoIdXEmpresaId($empresaId, "ingreso ", 7);
            } else {
                $valorImporte = ($importeTransferencia * 1) - ($transferencia * 1);
                $valorActividad = 36;
                $documentoTipoId = ACCaja::create()->obtenerDocumentoTipoIdXEmpresaId($empresaId, "egreso ", 8);
            }

            $documentoTipoDato = DocumentoTipoNegocio::create()->obtenerDocumentoTipoDato($documentoTipoId[0]["id"], $usuCreacion);

            $camposDinamicos = array();
            foreach ($documentoTipoDato as $dtd) {
                if ($dtd["tipo"] == 5) {
                    $valor = 22406;
                } else if ($dtd["tipo"] == 8 || $dtd["tipo"] == 9) {
                    $valor = $dtd["data"];
                } else if ($dtd["tipo"] == 14) {
                    $valor = $valorImporte;
                } else if ($dtd["tipo"] == 20) {
                    $valor = CuentaNegocio::ID_CUENTA_DEPOSITO_CAJA; //Igual caja de deposito
                } else if ($dtd["tipo"] == 21) {
                    $valor = $valorActividad;
                } else if ($dtd["tipo"] == 7) {
                    $valor = $dtd["cadena_defecto"];
                }

                array_push($camposDinamicos, array('id' => $dtd["id"], 'tipo' => $dtd["tipo"],
                    'opcional' => $dtd["opcional"], 'descripcion' => $dtd["descripcion"],
                    'valor' => $valor));
            }

            $documentoID = OperacionNegocio::create()->guardar(159, $usuCreacion, $documentoTipoId[0]["id"], $camposDinamicos, $comentario, "Ajuste cierre de caja - Importe Transferencia", 2, null, null, $periodoId, $idEditar);
        }

        $res = ACCaja::create()->guardarCierreCaja($idEditar, $id, $usuCreacion, $importeCierre, $comentario, $empresaId, $visa, $traslado, $is_pintar_cierre, $is_pintar_visa, $cierre_sugerido, $deposito, $is_pintar_deposito, $transferencia, $is_pintar_transferencia,$egreso,$ingreso,$visa_sugerido,$transferencia_sugerido,$deposito_sugerido,$egresoOtros,$egresoPos,$efectivo);

        if ($res[0]["vout_exito"] == '1') {
            //ENVIAR CORREO ADMIN Y USUARIO APERTURADOR
            $this->guardarEmailEnvioUsuarioCierreCaja($res[0]["id"]);
        }

        $data = new stdClass();
        $data->lstImportes_modificados = !ObjectUtil::isEmpty($lstImportes_modificados) ? $lstImportes_modificados : NULL;

        if (ObjectUtil::isEmpty($idEditar)) {
            $idEditar = $id;
        }

        if (!ObjectUtil::isEmpty($data->lstImportes_modificados)) {
            $this->guardarEmailEnvioCierreCaja($data, $idEditar, $isEditado);
        }

        if ($res[0]["vout_exito"] == 1) {
            $this->setMensajeEmergente($res[0]["vout_mensaje"]);
        } else {
            throw new WarningException($res[0]["vout_mensaje"]);
        }

        return $res;
    }

    public function guardarEmailEnvioCierreCaja($data, $id, $isEditado) {
        $lstImportes = $data->lstImportes_modificados;

        $dataApertura = ACCaja::create()->obtenerDataAperturaCierreXId($id, 0);

        $plantilla = EmailPlantillaNegocio::create()->obtenerEmailPlantillaXID(19);
        $correosPlantilla = EmailPlantillaNegocio::create()->obtenerEmailXDestinatario($plantilla[0]["destinatario"], null, null);

        $correos = implode(";", $correosPlantilla);

        //logica correo:
        $asunto = 'Cierre de caja ' . $dataApertura[0]['fecha_apertura_formato'] . ' - ' . $dataApertura[0]['empresa_descripcion'];
        $cuerpo = $plantilla[0]["cuerpo"];
        $tituloCorreo = 'Cierre de caja';
        $descripcionCorreo = '';

        $texto = !ObjectUtil::isEmpty($isEditado) ? "actualizÃ³" : "registrÃ³";

        if (!ObjectUtil::isEmpty($lstImportes)) {
            $descripcionCorreo .= 'Se ' . $texto . ' un cierre de caja con fecha: ' . $dataApertura[0]['fecha_cierre_formato'] . ' por el usuario ' . $dataApertura[0]['usuario_cierre'] . ', en la tienda ' . $dataApertura[0]['empresa_descripcion'] . '. <br><br>';

            foreach ($lstImportes as $imp) {
                $descripcionCorreo .= '- El importe ' . $imp["descripcion"] . ' de cierre sugerido por el sistema de caja es: S/. ' . number_format($imp['monto_original'], 2, ".", ",") . '; ';
                $descripcionCorreo .= 'sin embargo el registrado por el usuario es: S/. ' . number_format($imp['monto_modificado'], 2, ".", ",") . '.<br><br>';
            }

            $descripcionCorreo .= 'Los importes de cierre son diferentes, motivo por el cual se generÃ³ este email.<br><br>';
        }

        $cuerpo = str_replace("[|tituloCorreo|]", $tituloCorreo, $cuerpo);
        $cuerpo = str_replace("[|descripcionCorreo|]", $descripcionCorreo, $cuerpo);

        $resEmail = EmailEnvioNegocio::create()->insertarEmailEnvio($correos, $asunto, $cuerpo, 1, 1);
    }

    public function guardarEmailEnvioUsuarioCierreCaja($id) {
        $dataApertura = ACCaja::create()->obtenerDataAperturaCierreXId($id, 0);
        if ($dataApertura[0]['usuario_apertura'] != $dataApertura[0]['usuario_cierre']) {
            $plantilla = EmailPlantillaNegocio::create()->obtenerEmailPlantillaXID(19);
            $correosPlantilla = EmailPlantillaNegocio::create()->obtenerEmailXDestinatario($plantilla[0]["destinatario"], null, null);

            $correos = implode(";", $correosPlantilla) . ";" . $dataApertura[0]['correos'];

            //logica correo:
            $asunto = 'Cierre de caja con usuario diferente ' . $dataApertura[0]['fecha_apertura_formato'] . ' - ' . $dataApertura[0]['empresa_descripcion'];
            $cuerpo = $plantilla[0]["cuerpo"];
            $tituloCorreo = 'Cierre de caja con usuario diferente';
            $descripcionCorreo .= 'Se registrÃ³ un cierre de caja con usuario <b>diferente</b> a la apertura con fecha: ' . $dataApertura[0]['fecha_cierre_formato'] . ', en la tienda ' . $dataApertura[0]['empresa_descripcion'] . '. <br><br>';
            $descripcionCorreo .= '- Usuario apertura: ' . $dataApertura[0]['usuario_apertura'] . '<br>';
            $descripcionCorreo .= '- Usuario cierre: ' . $dataApertura[0]['usuario_cierre'] . '<br>';

            $cuerpo = str_replace("[|tituloCorreo|]", $tituloCorreo, $cuerpo);
            $cuerpo = str_replace("[|descripcionCorreo|]", $descripcionCorreo, $cuerpo);

            $resEmail = EmailEnvioNegocio::create()->insertarEmailEnvio($correos, $asunto, $cuerpo, 1, 1);
        }
    }

  public function obtenerACCajaXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start) {
        $empresaId = $criterios['empresaId'];
        $cajaId = $criterios['cajaId'];
        $fechaAlterna= date('Y-m-d');
        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];
         
        $data = ACCaja::create()->obtenerACCajaXCriterios($empresaId, $cajaId, $columnaOrdenar, $formaOrdenar, $elemntosFiltrados, $start,
                null, null, null,null, null,$fechaAlterna);
        return $data;
    }

    public function obtenerACCajaXCriteriosReporte($criterios, $elemntosFiltrados, $columns, $order, $start) {
        $empresaId = $criterios['empresaId'];
        $cajaId = $criterios['cajaId'];
        $periodoId = $criterios['periodoId'];
        $usuarioId = $criterios['usuarioId'];
        $agenciaId = $criterios['agenciaId'];
         $fechaInicial = $criterios['fechaInicio'];
        $fechaInicio = DateUtil::formatearCadenaACadenaBD($criterios['fechaInicio']);
        $fechaFin = DateUtil::formatearCadenaACadenaBD($criterios['fechaFin']);
           if (ObjectUtil::isEmpty($fechaInicial)) {
             $fechaAlterna= date('Y-m-d');
        }
          
        
        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];

        $data = ACCaja::create()->obtenerACCajaXCriterios($empresaId, $cajaId, $columnaOrdenar,
                $formaOrdenar, $elemntosFiltrados, $start, $periodoId, $usuarioId, $agenciaId,
                $fechaInicio, $fechaFin,$fechaAlterna);
        return $data;
    }
    public function obtenerACCajaXCriteriosReportePdf($criterios, $elemntosFiltrados, $columns, $order, $start) {
        $empresaId = $criterios['empresaId'];
        $cajaId = $criterios['cajaId'];
        $periodoId = $criterios['periodoId'];
        $usuarioId = $criterios['usuarioId'];
        $agenciaId = $criterios['agenciaId'];
         $fechaInicial = $criterios['fechaInicio'];
        $fechaInicio = DateUtil::formatearCadenaACadenaBD($criterios['fechaInicio']);
        $fechaFin = DateUtil::formatearCadenaACadenaBD($criterios['fechaFin']);
           if (ObjectUtil::isEmpty($fechaInicial)) {
             $fechaAlterna= date('Y-m-d');
        }
          
        
        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $columnaOrdenar = $columns;

        $data = ACCaja::create()->obtenerACCajaXCriterios($empresaId, $cajaId, $columnaOrdenar,
                $formaOrdenar, $elemntosFiltrados, $start, $periodoId, $usuarioId, $agenciaId,
                $fechaInicio, $fechaFin,$fechaAlterna);
        return $data;
    }
    public function obtenerCantidadACCajaXCriterios($criterios, $elemntosFiltrados, $columns, $order, $start) {
        $empresaId = $criterios['empresaId'];
        $cajaId = $criterios['cajaId'];
        $periodoId = $criterios['periodoId'];
        $usuarioId = $criterios['usuarioId'];
        $agenciaId = $criterios['agenciaId'];
       $fechaInicial = $criterios['fechaInicio'];
        $fechaInicio = DateUtil::formatearCadenaACadenaBD($criterios['fechaInicio']);
        $fechaFin = DateUtil::formatearCadenaACadenaBD($criterios['fechaFin']);
               if (ObjectUtil::isEmpty($fechaInicial)) {
             $fechaAlterna= date('Y-m-d');
        }
        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $columnaOrdenar = (ObjectUtil::isEmpty($columns))==true? $columns[$columnaOrdenarIndice]['data']:$columns;
        return ACCaja::create()->obtenerCantidadACCajaXCriterios($empresaId, $cajaId, $columnaOrdenar, $formaOrdenar, $periodoId,
                        $usuarioId, $agenciaId, $fechaInicio, $fechaFin,$fechaAlterna);
    }
    
        public function obtenerCantidadACCajaXCriteriosA($criterios, $elemntosFiltrados, $columns, $order, $start) {
        $empresaId = $criterios['empresaId'];
        $cajaId = $criterios['cajaId'];
        $periodoId = $criterios['periodoId'];
        $usuarioId = $criterios['usuarioId'];
        $agenciaId = $criterios['agenciaId'];
         $fechaAlterna= date('Y-m-d');
        $fechaInicio = DateUtil::formatearCadenaACadenaBD($criterios['fechaInicio']);
        $fechaFin = DateUtil::formatearCadenaACadenaBD($criterios['fechaFin']);
        $columnaOrdenarIndice = $order[0]['column'];
        $formaOrdenar = $order[0]['dir'];
        $columnaOrdenar = $columns[$columnaOrdenarIndice]['data'];
        return ACCaja::create()->obtenerCantidadACCajaXCriterios($empresaId, $cajaId, $columnaOrdenar, $formaOrdenar, $periodoId,
                        $usuarioId, $agenciaId, $fechaInicio, $fechaFin,$fechaAlterna);
    }

    public function obtenerAperturaCierreUltimo($empresaId, $cajaId) {
        $resultado = new stdClass();
        $resultado->dataAperturaCaja = ACCaja::create()->obteneAperturaCajaUltimoXEmpresaId($empresaId, NULL, $cajaId);
        $resultado->dataACCaja = ACCaja::create()->obteneACCajaUltimoXEmpresaId($empresaId, NULL, $cajaId);

        return $resultado;
    }

    public function obtenerUsuarioDatos($usuarioId, $empresaId) {
        $resultado = new stdClass();
        $resultado->dataUsuario = Usuario::create()->getUsuario($usuarioId);
        $resultado->dataCaja = PerfilAgenciaCajaNegocio::create()->obtenerPerfilAgenciaCajaXUsuarioId($usuarioId);
        $resultado->dataAgencia = PerfilAgenciaCajaNegocio::create()->obtenerPerfilAgenciaXUsuarioId($usuarioId);
        $resultado->periodo = PeriodoNegocio::create()->obtenerPeriodoAbiertoXEmpresa($empresaId);
        $resultado->dataUsuario = UsuarioNegocio::create()->getDataUsuario();
        $dataAperturaCaja = ACCajaNegocio::create()->obteneACCajaUltimoXUsuarioId($empresaId, $usuarioId, $resultado->dataAgencia[0]['id']);
        $cajaDefault = NULL;
        if (!ObjectUtil::isEmpty($dataAperturaCaja)) {
            $cajaDefault = $dataAperturaCaja[0]['caja_id'];
        }
        $resultado->cajaDefault = $cajaDefault;
        return $resultado;
    }
    
       public function obtenerUsuarioDatosCaja($usuarioId, $empresaId) {
        $resultado = new stdClass();
        $resultado->dataUsuario = Usuario::create()->getUsuario($usuarioId);
        $perfil=Perfil::create()->obtnerPerfilLiquidacionAgencia($usuarioId);
        $resultado->perfil = $perfil;
        $idPerfil=$perfil[0]['id'];
        if($idPerfil==124){
        $resultado->dataAgencia = PerfilAgenciaCajaNegocio::create()->obtenerPerfilAgenciaXUsuarioId($usuarioId);
        $resultado->dataCaja = Caja::create()->getDataCajaXusuario($usuarioId); }
        else {
            $resultado->dataCaja = CajaNegocio::create()->getDataCaja();
            $resultado->dataAgencia = AgenciaNegocio::create()->getDataAgencia();

        }
       
        
        $resultado->dataAgencia2 = PerfilAgenciaCajaNegocio::create()->obtenerPerfilAgenciaXUsuarioId($usuarioId);
        $resultado->periodo = PeriodoNegocio::create()->obtenerPeriodoAbiertoXEmpresa($empresaId);
        $resultado->dataUsuario = UsuarioNegocio::create()->getDataUsuario();
        $dataAperturaCaja = ACCajaNegocio::create()->obteneACCajaUltimoXUsuarioId($empresaId, $usuarioId, $resultado->dataAgencia[0]['id']);
        $cajaDefault = NULL;
        if (!ObjectUtil::isEmpty($dataAperturaCaja)) {
            $cajaDefault = $dataAperturaCaja[0]['caja_id'];
        }
        $resultado->cajaDefault = $cajaDefault;
        return $resultado;
    }

    public function obtenerAccCaja($usuarioCreacion) {
        return ACCaja::create()->obtenerAccCaja($usuarioCreacion);
    }

    public function obteneACCajaUltimoXUsuarioId($empresaId, $usuarioId, $agenciaId = NULL, $cajaId = NULL) {
        return ACCaja::create()->obteneACCajaUltimoXUsuarioId($empresaId, $usuarioId, $agenciaId, $cajaId);
    }

    public function obtenerCajaCorrelativoXAgenciaId($agenciaId) {
        return ACCaja::create()->obtenerCajaCorrelativoXAgenciaId($agenciaId);
    }

    public function obteneACCajaUltimoVirtualXAgenciaId($agenciaId, $cajaId = NULL) {
        return ACCaja::create()->obteneACCajaUltimoVirtualXAgenciaId($agenciaId, $cajaId);
    }
    
    
        public function validarCajaIp($usuCreacion,  $ip, $cajaId) {
//            $ip='179.6.166.70';
        $ipCajaF= ACCaja::create()->obtenerAccajaIpxusuario($cajaId);
        $ipCaja=$ipCajaF[0]['ip_caja'];
        if($ipCaja==$ip){
            return 1;
        }
        else {
          return 0;
        }
        
    }
}
