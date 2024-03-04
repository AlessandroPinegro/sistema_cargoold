<?php
date_default_timezone_set("America/Lima");

session_start();
if (!isset($_SESSION['ldap_user']))
    header("location:index.php");
?>
