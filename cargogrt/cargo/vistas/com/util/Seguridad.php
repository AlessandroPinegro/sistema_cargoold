<?php
session_start();
//$token=$_GET["token"];
//$codigoId=$_GET["id"];
//
//if($token!=null && $token!=''){
//    $_SESSION['token']=$token;
//}
//if($codigoId!=null && $codigoId!=''){
//    $_SESSION['codigoId']=$codigoId;
//}

if (count($_GET) > 0) {
    $_SESSION['arrayGet'] = $_GET;
}


//var_dump($_SESSION);
if (!isset($_SESSION['ldap_user']))
    header("location:login.php");