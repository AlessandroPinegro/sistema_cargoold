<?php
try {
    $ch = curl_init();
    echo "Estos es una practica"."<br>";
    // Check if initialization had gone wrong*    
    if ($ch === false) {
        echo "INICIAR PARA VER SI VALIDA QUE NO DA LA CONEXION"."<br>";
        die();
    }
    $url = 'https://www.ittsabus.com/suiteapirest/Usuario/Login';

    // Better to explicitly set URL
    curl_setopt($ch, CURLOPT_URL, $url);
    // That needs to be set; content will spill to STDOUT otherwise
    $array = ['USUARIO_TOKEN' => '47046044', 'PASS_TOKEN' => '47046044'];
    $data = json_encode($array);
	
    echo ($data);

    /* pass encoded JSON string to the POST fields */
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
   $headers = [];
    $headers[] = 'Content-Type:application/json';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    // Set more options
    //curl_setopt(/* ... */);

    $content = curl_exec($ch);

    
    echo "imprimir lo siguiente".json_encode($content)."<br>";
      
    // Check the return value of curl_exec(), too
    if ($content === false) {
        echo "ERROR prueba : " . curl_error($ch) . "<br>";
        echo "ERROR CODIGO : " . curl_errno($ch) . "<br>";
        // echo(curl_error($ch) ." ". curl_errno($ch));
        die();
    }

    // Check HTTP return code, too; might be something else than 200
    $httpReturnCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    $login = json_decode($content, true);
    $token = $login['token'];


    /* Process $content here */
} catch (Exception $e) {

    echo (sprintf(
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


try {
    $ch = curl_init();

    // Check if initialization had gone wrong*    
    if ($ch === false) {
        echo ('inicio de curl');
        die();
    }
    //$fecha = '2023-01-12';
    $url = 'https://www.ittsabus.com/suiteapirest/Empresa/Listar/20600064941';

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
        echo (curl_error($ch) . " " . curl_errno($ch));
        die();
    }

    // Check HTTP return code, too; might be something else than 200
    $httpReturnCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    $respuestaItinerario = json_decode($content, true);

    var_dump($respuestaItinerario);

    /* Process $content here */
} catch (Exception $e) {

    echo (sprintf(
            'Error al intentar obtener el token #%d: %s',
            $e->getCode(),
            $e->getMessage()
        )
    );
    die();
} finally {

    if (is_resource($ch)) {
        curl_close($ch);
    }
}
