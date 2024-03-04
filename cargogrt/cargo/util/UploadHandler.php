<?php
require_once __DIR__ . "/Extensiones.php";
require_once __DIR__ . "/Configuraciones.php";

$contentType = "application/x-www-form-urlencoded; charset=utf-8";
header("Content-Type: $contentType");
//header("Content-Size: " . strlen($buff));

extract($_REQUEST, EXTR_PREFIX_ALL, "f");

$name = Configuraciones::UPLOAD_NAME;
$extension = explode(".", $_FILES[$name]["name"]);
$extension = end($extension);

$e = new Extensiones();
if ($e->is_valid_ext($extension)) {
    if ($_FILES[$name]["size"] < $e->get_max_Size($extension)) {
        if (!$_FILES[$name]['error'] > 0 && !empty($_FILES[$name]['name'])) {
            $tmp = $_FILES[$name]['tmp_name'];
            $nombre_real = $_FILES[$name]['name'];
            $nombre = substr($nombre_real, -1* strlen($nombre_real),  (strlen($nombre_real) - strlen($extension) - 1));
            $nombre= preg_replace("/[^a-zA-Z0-9]/", "_", $nombre);
            $date = date('YmdHis');
            $nombre= str_replace(" ", "_", $nombre)."$date.$extension";
            $url = Configuraciones::UPLOAD_FOLDER."/$nombre";
            if (move_uploaded_file($tmp, $url)) {
                $newresponse = array();
                print("1;$nombre_real;$url;$extension");
            }else{
                print("0;No se pudo subir el archivo");
            }
        }  else {
            print("0;El archibo subió con errores");
        }
    }else{
        print("0;El archivo excede el tamaño permitido");
    }
}else{
    print("0;No tiene una extensión válida");
}
?>
