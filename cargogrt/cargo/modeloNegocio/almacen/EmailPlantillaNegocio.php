<?php

  if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
require_once __DIR__ . '/../../modelo/almacen/EmailPlantilla.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/PerfilNegocio.php';
require_once __DIR__ . '/UsuarioNegocio.php';
require_once __DIR__ . '/DocumentoNegocio.php';
require_once __DIR__ . '/PersonaNegocio.php';

class EmailPlantillaNegocio extends ModeloNegocioBase {

    /**
     * 
     * @return EmailPlantillaNegocio
     */
    static function create() {
        return parent::create();
    }

    public function obtenerEmailPlantillaXID($id) {
        return EmailPlantilla::create()->obtenerEmailPlantillaXID($id);
    }

    public function obtenerPlantillaDestinatarioXAccionDescripcionXMovimientoTipoId($accionEnvio, $movimientoTipoId) {
        return EmailPlantilla::create()->obtenerPlantillaDestinatarioXAccionDescripcionXMovimientoTipoId($accionEnvio, $movimientoTipoId);
    }

    public function obtenerEmailXDestinatario($destinatario, $documentoId, $personaId) {

        $arrayCorreos = array();

        if (is_string($destinatario) && strlen($destinatario) > 0) {
            $destinatario = explode(";", $destinatario);
        }
        if (!is_null($destinatario) && is_array($destinatario)) {
            $destinatario = array_unique($destinatario);
        } else {
            return null;
        }

        foreach ($destinatario as $ind => $item) {
            $valor = $item;
            $contador1 = 0;
            $contador2 = 0;

            $valor = str_replace("[|", "", $valor, $contador1);
            $valor = str_replace("|]", "", $valor, $contador2);

            if ($contador1 == 0 && $contador2 == 0) {
                array_push($arrayCorreos, $item);
            }
            if ($contador1 == 1 && $contador2 == 1) {
                // si esta entre corchetes buscar
                //usuarios de perfil
                $dataPerfil = PerfilNegocio::create()->obtenerCorreosDeUsuarioXNombrePerfil($valor);
                if (!ObjectUtil::isEmpty($dataPerfil)) {
                    foreach ($dataPerfil as $itemPer) {
                        array_push($arrayCorreos, $itemPer['email']);
                    }
                }

                //usuario
                $dataUsuario = UsuarioNegocio::create()->obtenerCorreoXUsuario($valor);
                if (!ObjectUtil::isEmpty($dataUsuario)) {
                    foreach ($dataUsuario as $itemUser) {
                        array_push($arrayCorreos, $itemUser['email']);
                    }
                }

                //obtener los correos de la persona de documento tipo dato
                if ($valor == 'dtdPersona5') {
                    $emailPersona = DocumentoNegocio::create()->obtenerPersonaXDocumentoId($documentoId);
                    if (!ObjectUtil::isEmpty($emailPersona[0]['email'])) {
                        array_push($arrayCorreos, $emailPersona[0]['email']);
                    } else {
                        array_push($arrayCorreos, "EMAIL_INVALIDO");
                    }
                }

                //obtener los correos de los contactos de la persona
                if ($valor == 'PersonaContactos') {
                    $emailPersona = DocumentoNegocio::create()->obtenerPersonaXDocumentoId($documentoId);
                    if (!ObjectUtil::isEmpty($emailPersona[0]['email'])) {
                        array_push($arrayCorreos, $emailPersona[0]['email']);
                    }

                    $dataContactos = PersonaNegocio::create()->obtenerPersonaContactoXPersonaId($personaId);
                    if (!ObjectUtil::isEmpty($dataContactos)) {
                        foreach ($dataContactos as $item) {
                            if (!ObjectUtil::isEmpty($item['email'])) {
                                array_push($arrayCorreos, $item['email']);
                            }
                        }
                    }
                }

                //obtener correos de los usuarios que registraron las relaciones y el documento actual.
                if ($valor == 'Interesados') {
                    //USUARIO ACTUAL QUE REGISTRO EL DOCUMENTO
                    $resDocumentoId = DocumentoNegocio::create()->obtenerDocumentoXDocumentoId($documentoId);
                    $dataPersona = PersonaNegocio::create()->obtenerPersonaXId($resDocumentoId[0]['persona_usuario_id']);
                    if (!ObjectUtil::isEmpty($dataPersona[0]['email'])) {
                        array_push($arrayCorreos, $dataPersona[0]['email']);
                    }

                    //USUARIOS QUE REGISTRARON LOS DOCUMENTOS RELACIONADOS
                    $resData = DocumentoNegocio::create()->obtenerRelacionesDocumento($documentoId);
                    foreach ($resData as $itemDoc) {
                        $dataPersona = PersonaNegocio::create()->obtenerPersonaXId($itemDoc['persona_usuario_id']);
                        if (!ObjectUtil::isEmpty($dataPersona[0]['email'])) {
                            array_push($arrayCorreos, $dataPersona[0]['email']);
                        }
                    }
                }
            }
        }

        $arrayCorreos = array_unique($arrayCorreos);
        array_multisort($arrayCorreos); //ordernar los correos de forma ascendente
        return $arrayCorreos;
    }

    public function construirTablaCobranzas($cobranzas) {
        $cabecera = $this->construirCabeceras(["Item", "Cliente", "N&deg; Factura", "Monto total", "Saldo pendiente"]);
        $cuerpo = $this->construirCuerpo($cobranzas);
        
        return $cabecera.$cuerpo;
    }

    public function construirCabeceras($headers) {
        $cabecera = '<table  border=1 cellspacing=0 cellpadding=5 bordercolor="000000"  width="91.7%">
                        <thead>';
        $columnas = '<tr>';
        foreach ($headers as $header) {
            $columnas = $columnas . "<th style='text-align:center;font-size:14px;line-height:1.5;'>" . $header . "</th>";
        }
        $columnas = $columnas . '</tr>';
        $cabecera = $cabecera . $columnas;
        $cabecera = $cabecera . '<thead>';
        $cabecera = $cabecera . '<tbody>';
        return $cabecera;
    }
    
    public function construirCuerpo($cobranzas){
        $body = '';
         foreach ($cobranzas as $index => $cobranza) {
            $body = $body. '<tr>';
            $body = $body . '<td style=\'text-align:center;font-size:14px;line-height:1.5;\'>' . ($index + 1);
            $body = $body . '<td style=\'text-align:left;font-size:14px;line-height:1.5;\'>' . substr($cobranza['persona_nombre_completo'],0,42);
            $body = $body . '<td style=\'text-align:center;font-size:14px;line-height:1.5;\'>' . $cobranza['serie'] . '-' . $cobranza['numero'];
            $body = $body . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . $cobranza['moneda_simbolo'].number_format($cobranza['total'], 2, ".", ",");
            $body = $body . '<td style=\'text-align:right;font-size:14px;line-height:1.5;\'>' . $cobranza['moneda_simbolo']. number_format($cobranza['pendiente'], 2, ".", ",");
            $body = $body . '</tr>';
        }
        $body = $body . '</tbody></table>';
        
        return $body;
    }

}
