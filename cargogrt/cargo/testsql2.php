<?php


try {
    $ch = curl_init();

    // Check if initialization had gone wrong*    
    if ($ch === false) {
        throw new Exception('failed to initialize');
    }	
    $url = 'https://www.ittsabus.com/suiteapirest/Usuario/Login';

    // Better to explicitly set URL
    curl_setopt($ch, CURLOPT_URL, $url);
    // That needs to be set; content will spill to STDOUT otherwise
     $array = ['UsuarioName' => '47046044', 'UsuarioPassword' => '47046044'];
            $data = json_encode($array);
            /* pass encoded JSON string to the POST fields */
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
$headers = [];
            $headers[] = 'Content-Type:application/json';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // Set more options
    //curl_setopt(/* ... */);
    
    $content = curl_exec($ch);

    // Check the return value of curl_exec(), too
    if ($content === false) {
        throw new Exception(curl_error($ch), curl_errno($ch));
    }

    // Check HTTP return code, too; might be something else than 200
    $httpReturnCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
$login = json_decode($content , true);
            $token = $login['token'];

echo $token ;
    /* Process $content here */

} catch(Exception $e) {

    trigger_error(sprintf(
        'Curl failed with error #%d: %s',
        $e->getCode(), $e->getMessage()),
        E_USER_ERROR);

} finally {

   if (is_resource($ch)) {
        curl_close($ch);
    }
}