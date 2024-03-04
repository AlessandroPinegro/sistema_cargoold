<?php

      try {
            $ch = curl_init();

            // Check if initialization had gone wrong*    
            if ($ch === false) {
                throw new WarningException('inicio de curl');
            }
            //$fecha = '2023-01-12';
            $url = 'https://www.ittsabus.com/suiteapirest/Itinerario/Cargo/Listar/Fecha/' . $fecha;

            // Better to explicitly set URL
            curl_setopt($ch, CURLOPT_URL, $url);

            $headers = array(
                "Content-Type: application/json",
                "Authorization: Bearer " . $token
            );
            $headers[] = 'Content-Type:application/json';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // Set more options
            //curl_setopt(/* ... */);

            $content = curl_exec($ch);

            // Check the return value of curl_exec(), too
            if ($content === false) {
                echo 'ERROR : ' . curl_error($ch). curl_errno($ch);
            }

            // Check HTTP return code, too; might be something else than 200
            $httpReturnCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            $respuestaItinerario = json_decode($content, true);

            /* Process $content here */
        } catch (Exception $e) {

            echo 
                sprintf(
                    'Error al intentar obtener el token #%d: %s',
                    $e->getCode(),
                    $e->getMessage()
                )
            );
        } finally {

            if (is_resource($ch)) {
                curl_close($ch);
            }
        }

exit;

// phpinfo();

// exit;

try{
    $handle = curl_init();

    $url = "http://192.168.1.173/cargo/login.php";

    // Set the url
    curl_setopt($handle, CURLOPT_URL, $url);
    // Set the result output to be a string.
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

    $output = curl_exec($handle);

    curl_close($handle);

    echo $output;
echo "ok";
}catch(Exception $e){
echo "Error";
    echo $e->getMessage();
}

exit; 
	$url = "http://192.168.1.173/cargo/login.php";
	$ch = curl_init($url);
	$result2 = curl_exec($ch);
	curl_close($ch);

echo $result2;
echo "hola";
exit;
        $fecha='2022-09-27';
        $url = 'https://www.ittsabus.com/suiteapirest/Usuario/Login';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $array = ['UsuarioName' => '47046044', 'UsuarioPassword' => '47046044'];
        $data = json_encode($array);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $headers = [];
        $headers[] = 'Content-Type:application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result2 = curl_exec($ch);
var_dump($result2);
        curl_close($ch);
        $login = json_decode($result2, true);
        $token = $login['token'];
        //ITINERARIO DE BUSES
        $url = 'https://www.ittsabus.com/suiteapirest/Itinerario/Cargo/Listar/Fecha/' . $fecha;
        $ch = curl_init();
        $headers = array(
            "Content-Type: application/json",
            "Authorization: Bearer " . $token
        );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        curl_close($ch);
        $a = json_decode($result, true);
        $itinerarios = $a['listaItinerarios'];
        $data = array();
        $manifiesto = array();
        $c = 0;
        $m = 0;
   echo 1;
             echo $login['token'];
          

?>