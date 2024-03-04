<?php

require_once __DIR__ . '/../../modelo/almacen/EmailEnvio.php';
require_once __DIR__ . '/../../modeloNegocio/core/ModeloNegocioBase.php';
require_once __DIR__ . '/../../util/EmailEnvioUtil.php';

class EmailEnvioNegocio extends ModeloNegocioBase {

    /**
     * 
     * @return EmailEnvioNegocio
     */
    static function create() {
        return parent::create();
    }

    public function obtenerPendientesEnvio() {
        return EmailEnvio::create()->obtenerPendientesEnvio();
    }
    
    public function obtenerPendientesEnvioXId($id) {
        return EmailEnvio::create()->obtenerPendientesEnvioXId($id);
    }

    public function enviarPendientesEnvio($id = null) {
        
        if(ObjectUtil::isEmpty($id)){
            $pendientesEnvio = $this->obtenerPendientesEnvio();
        } else {
            $pendientesEnvio = $this->obtenerPendientesEnvioXId($id);
        }

        $email = new EmailEnvioUtil();
        $correo= new EmailEnvio();
        $pattern = ("/^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/");
        
        $tamanio = count($pendientesEnvio);
        for ($i = 0; $i < $tamanio; $i++) {
            
            $intentos = $correo->obtenerIntentosXId($pendientesEnvio[$i]['id']);
            $numero = intval($intentos[0]['intentos']);
            $emailtemp = $pendientesEnvio[$i]['destinatario'];
//            $regres =  preg_match($pattern,$pendientesEnvio[$i]['destinatario'] ) ;
            
            $correoArray = explode(";", $pendientesEnvio[$i]['destinatario']);
            $correoArray=array_unique($correoArray);
            $validoCorreos=true;
            foreach ($correoArray as $indice => $itemCorreo) {
                if(!ObjectUtil::isEmpty($itemCorreo)){
                    $regres = preg_match($pattern, $itemCorreo);
                    if ($regres == 0) {
                        $validoCorreos = false;
                    }
                }
            }
//            $emailValido = filter_var($pendientesEnvio[$i]['destinatario'], FILTER_VALIDATE_EMAIL);
            //$checkDestinatario = strpos($pendientesEnvio[$i]['destinatario'], "@");
            
            //if ($numero < 4 && checkDestinatario !== false) //check for at symbol
            
//            if ($numero < 3 && $regres === 1) 
            if ($numero < 3 && $validoCorreos) 
            {
                if (ObjectUtil::isEmpty($pendientesEnvio[$i]['archivo_adjunto'])) 
                {
                    $enviar = $email->envio($pendientesEnvio[$i]['destinatario'], null,null, $pendientesEnvio[$i]['asunto'], $pendientesEnvio[$i]['cuerpo']);                    
                } 
                else 
                {
                    $enviar = $email->envio($pendientesEnvio[$i]['destinatario'], null,null, $pendientesEnvio[$i]['asunto'], $pendientesEnvio[$i]['cuerpo'], $pendientesEnvio[$i]['archivo_adjunto'], $pendientesEnvio[$i]['nombre_archivo']);
                }
            
                if ($enviar['status']*1 == 0) 
                {
                    $correo->actualizarIntentos($pendientesEnvio[$i]['id']);
                    $contenido = "No se envió el correo por alguna falla en el servidor.";
                    $correo->logActividad($pendientesEnvio[$i]['id'],$contenido);
                    
//                    echo $enviar['mensaje'] . ". En ID: " . $pendientesEnvio[$i]['id'] . "<br>";
                } 
                else 
                {
                    EmailEnvio::create()->actualizarEstadoEnviado($pendientesEnvio[$i]['id']);
                    $correo->actualizarIntentos($pendientesEnvio[$i]['id']);
                    $contenido = "Correo enviado satisfactoriamente. ";
                    $correo->logActividad($pendientesEnvio[$i]['id'],$contenido);
                    
//                    echo "Enviado Correctamente. ID: " . $pendientesEnvio[$i]['id'] . "<br>";                    
                    
                    // eliminar el documento enviado.
                    if (!ObjectUtil::isEmpty($pendientesEnvio[$i]['archivo_adjunto'])) {
                        unlink($pendientesEnvio[$i]['archivo_adjunto']);
                    }
                }
            }
            else{
                $contenido = "No se encontró e-mail válido.";
                $correo->actualizarIntentos($pendientesEnvio[$i]['id']);
                $correo->logActividad($pendientesEnvio[$i]['id'],$contenido);
                
//                echo "No se encontr&oacute; destinatario v&aacute;lido. En ID: " . $pendientesEnvio[$i]['id'] . "<br>";
            }
            
            if ($numero >= 2) {
                EmailEnvio::create()->actualizarEstadoError($pendientesEnvio[$i]['id']);
            }
        }
    }

    public function insertarEmailEnvio($destinatario, $asunto, $cuerpo, $estado, $usuarioId, $urlEmail = null, $nombreArchivo = null) {
        $response = EmailEnvio::create()->insertarEmailEnvio($destinatario, $asunto, $cuerpo, $estado, $usuarioId, $urlEmail, $nombreArchivo);
        return $response;
    }

}
