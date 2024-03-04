<?php
require_once __DIR__ . '/util/Configuraciones.php';
$url_libs_imagina = Configuraciones::url_base() . "vistas/libs/imagina/";

$parametros = $_POST["parametro"];
?>

<!DOCTYPE html>
<html lang="en">

    <!-- Mirrored from coderthemes.com/velonic/admin/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 14 May 2015 23:17:26 GMT -->

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <link rel="shortcut icon" href="vistas/images/icono_ittsa.ico">

        <title>ITTSA CARGO - INTERNACIONAL DE TRANSPORTE TURISTICO Y SERVICIOS SRL</title>

        <style>
            @import url("https://fonts.googleapis.com/css?family=Oswald:300,400,500,600,700&display=block");
        </style>
        <link rel="stylesheet" href="vistas/libs/imagina/css/critical.css?v=e04a177be" as="style" onload="this.rel = 'stylesheet'" />
        <link rel="stylesheet" href="vistas/libs/imagina/css/fonts.css?v=e04a177be" as="style" onload="this.rel = 'stylesheet'" />
        <link rel="stylesheet" type="text/css" media="all" href="vistas/libs/imagina/wro/lambda_responsive.css?v=e04a177be" />
        <link rel="manifest" href='data:application/manifest+json,{ "name": "Marathon Sports", "short_name": "Marathon", "description": "Marathon Sports, compra en lÃ­nea ropa, accesorios y calzado deportivo en Peru."}' />

        <!-- Bootstrap core CSS -->
        <link href="<?php echo $url_libs_imagina; ?>css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo $url_libs_imagina; ?>css/bootstrap-reset.css" rel="stylesheet">

        <!--Animation css-->
        <link href="<?php echo $url_libs_imagina; ?>css/animate.css" rel="stylesheet">

        <!--Icon-fonts css-->
        <link href="<?php echo $url_libs_imagina; ?>assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
        <link href="<?php echo $url_libs_imagina; ?>assets/ionicon/css/ionicons.min.css" rel="stylesheet" />

        <!-- sweet alerts -->
        <link href="<?php echo $url_libs_imagina; ?>assets/sweet-alert/sweet-alert.min.css" rel="stylesheet">

        <!--para las tablas-->
        <!--<link href="<?php echo $url_libs_imagina; ?>assets/datatables/jquery.dataTables.min.css" rel="stylesheet" />-->
        <link href="<?php echo $url_libs_imagina; ?>assets/datatables/dataTables.bootstrap.min.css" rel="stylesheet" />
        <!--<link href="<?php echo $url_libs_imagina; ?>assets/datatables/columnsFixed.css" rel="stylesheet" />-->

        <!-- Custom styles for this template -->
        <link href="<?php echo $url_libs_imagina; ?>css/style.css" rel="stylesheet">
        <link href="<?php echo $url_libs_imagina; ?>css/helper.css" rel="stylesheet">
        <link href="<?php echo $url_libs_imagina; ?>css/style-responsive.css" rel="stylesheet" />


        <!--Adicionales-->  
        <link href="<?php echo $url_libs_imagina; ?>assets/notifications/notification.css" rel="stylesheet" />


        <!--librerias adicionaes-->

        <link href="<?php echo $url_libs_imagina; ?>assets/modal-effect/css/component.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="<?php echo $url_libs_imagina; ?>assets/colorpicker/colorpicker.css" />
        <link href="<?php echo $url_libs_imagina; ?>assets/timepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css"/>
<!--        <link rel="stylesheet" type="text/css" href="<?php echo $url_libs_imagina; ?>assets/jquery-multi-select/multi-select.css" />-->
        <link href="<?php echo $url_libs_imagina; ?>assets/select2/select2.css" rel="stylesheet"/>
        <link href="<?php echo $url_libs_imagina; ?>assets/nestable/jquery.nestable.css" rel="stylesheet" />
        <link href="<?php echo $url_libs_imagina; ?>assets/datatables/buttons.dataTables.min.css" rel="stylesheet" />

        <!-- Adicionales -->
        <!--<link href="vistas/css/estilos.css" rel="stylesheet" />-->
        <style type="text/css">
            @media screen and (max-width:480px) {
                input[type="radio"]~label {
                    color: grey;
                    font-size: 15pt;
                    text-align: center;
                }
            }

            @media screen and (min-width:481px) {
                input[type="radio"]~label {
                    color: grey;
                    font-size: 30pt;
                    text-align: center;
                }
            }

            input[type="radio"] {
                display: none !important;
                /*position: absolute;top: -1000em;*/
            }

            /*input[type = "radio"] ~ label{ color:grey; font-size: 30pt; text-align: center;}*/

            .clasificacion {
                direction: rtl;
                unicode-bidi: bidi-override;
                max-height: 10px;
            }

            input[type="radio"]:hover,
            input[type="radio"]:hover~label {
                color: orange;
                text-shadow: .1em .1em .2em rgba(0, 0, 0, .3);
            }

            input[type="radio"]:checked~label {
                color: orange;
                text-shadow: .1em .1em .2em rgba(0, 0, 0, .3);
            }

            .wizard.vertical>.steps>ul>li {
                display: none !important;
            }

            .wizard.vertical>.content {
                width: 95% !important;
            }
        </style>



        <style type="text/css">
            .im-caret {
                -webkit-animation: 1s blink step-end infinite;
                animation: 1s blink step-end infinite;
            }

            @keyframes blink {

                from,
                to {
                    border-right-color: black;
                }

                50% {
                    border-right-color: transparent;
                }
            }

            @-webkit-keyframes blink {

                from,
                to {
                    border-right-color: black;
                }

                50% {
                    border-right-color: transparent;
                }
            }

            .im-static {
                color: grey;
            }
        </style>
<!--        <style type="text/css" evg-experience="97tqK" evg-campaign="xLuav">
            .breadcrumb-section .breadcrumb {
                margin-bottom: 20px !important;
            }

            @media (max-width: 750px) {
                .breadcrumb-section {
                    padding: 10px 10px 0;
                }
            } 
        </style>-->

<!--        <style type="text/css">
            iframe#_hjRemoteVarsFrame {
                display: none !important;
                width: 1px !important;
                height: 1px !important;
                opacity: 0 !important;
                pointer-events: none !important;
            }
        </style>-->
        <script type="text/javascript">var URL_BASE = "<?php echo Configuraciones::url_base(); ?>";</script>

        <style type="text/css" id="smct-v5-anims">
            .smct-animated {
                -webkit-animation-duration: 1s;
                animation-duration: 1s;
                -webkit-animation-fill-mode: both;
                animation-fill-mode: both;
            }

            .smct-animated.smct-infinite {
                -webkit-animation-iteration-count: infinite;
                animation-iteration-count: infinite;
            }

            .smct-animated.smct-hinge {
                -webkit-animation-duration: 2s;
                animation-duration: 2s;
            }

            .smct-animated.smct-bounceIn,
            .smct-animated.smct-bounceOut,
            .smct-animated.smct-flipOutX,
            .smct-animated.smct-flipOutY {
                -webkit-animation-duration: 0.75s;
                animation-duration: 0.75s;
            }

            @-webkit-keyframes smct-rubberBand {
                0% {
                    -webkit-transform: scaleX(1);
                    transform: scaleX(1);
                }

                30% {
                    -webkit-transform: scale3d(1.25, 0.75, 1);
                    transform: scale3d(1.25, 0.75, 1);
                }

                40% {
                    -webkit-transform: scale3d(0.75, 1.25, 1);
                    transform: scale3d(0.75, 1.25, 1);
                }

                50% {
                    -webkit-transform: scale3d(1.15, 0.85, 1);
                    transform: scale3d(1.15, 0.85, 1);
                }

                65% {
                    -webkit-transform: scale3d(0.95, 1.05, 1);
                    transform: scale3d(0.95, 1.05, 1);
                }

                75% {
                    -webkit-transform: scale3d(1.05, 0.95, 1);
                    transform: scale3d(1.05, 0.95, 1);
                }

                to {
                    -webkit-transform: scaleX(1);
                    transform: scaleX(1);
                }
            }

            @keyframes smct-rubberBand {
                0% {
                    -webkit-transform: scaleX(1);
                    transform: scaleX(1);
                }

                30% {
                    -webkit-transform: scale3d(1.25, 0.75, 1);
                    transform: scale3d(1.25, 0.75, 1);
                }

                40% {
                    -webkit-transform: scale3d(0.75, 1.25, 1);
                    transform: scale3d(0.75, 1.25, 1);
                }

                50% {
                    -webkit-transform: scale3d(1.15, 0.85, 1);
                    transform: scale3d(1.15, 0.85, 1);
                }

                65% {
                    -webkit-transform: scale3d(0.95, 1.05, 1);
                    transform: scale3d(0.95, 1.05, 1);
                }

                75% {
                    -webkit-transform: scale3d(1.05, 0.95, 1);
                    transform: scale3d(1.05, 0.95, 1);
                }

                to {
                    -webkit-transform: scaleX(1);
                    transform: scaleX(1);
                }
            }

            .smct-rubberBand {
                -webkit-animation-name: smct-rubberBand;
                animation-name: smct-rubberBand;
            }

            @-webkit-keyframes smct-shake {

                0%,
                to {
                    -webkit-transform: translateZ(0);
                    transform: translateZ(0);
                }

                10%,
                30%,
                50%,
                70%,
                90% {
                    -webkit-transform: translate3d(-10px, 0, 0);
                    transform: translate3d(-10px, 0, 0);
                }

                20%,
                40%,
                60%,
                80% {
                    -webkit-transform: translate3d(10px, 0, 0);
                    transform: translate3d(10px, 0, 0);
                }
            }

            @keyframes smct-shake {

                0%,
                to {
                    -webkit-transform: translateZ(0);
                    transform: translateZ(0);
                }

                10%,
                30%,
                50%,
                70%,
                90% {
                    -webkit-transform: translate3d(-10px, 0, 0);
                    transform: translate3d(-10px, 0, 0);
                }

                20%,
                40%,
                60%,
                80% {
                    -webkit-transform: translate3d(10px, 0, 0);
                    transform: translate3d(10px, 0, 0);
                }
            }

            .smct-shake {
                -webkit-animation-name: smct-shake;
                animation-name: smct-shake;
            }

            @-webkit-keyframes smct-pulse {
                0% {
                    -webkit-transform: scaleX(1);
                    transform: scaleX(1);
                }

                50% {
                    -webkit-transform: scale3d(1.05, 1.05, 1.05);
                    transform: scale3d(1.05, 1.05, 1.05);
                }

                to {
                    -webkit-transform: scaleX(1);
                    transform: scaleX(1);
                }
            }

            @keyframes smct-pulse {
                0% {
                    -webkit-transform: scaleX(1);
                    transform: scaleX(1);
                }

                50% {
                    -webkit-transform: scale3d(1.05, 1.05, 1.05);
                    transform: scale3d(1.05, 1.05, 1.05);
                }

                to {
                    -webkit-transform: scaleX(1);
                    transform: scaleX(1);
                }
            }

            .smct-pulse {
                -webkit-animation-name: smct-pulse;
                animation-name: smct-pulse;
            }

            @-webkit-keyframes smct-flash {

                0%,
                50%,
                to {
                    opacity: 1;
                }

                25%,
                75% {
                    opacity: 0;
                }
            }

            @keyframes smct-flash {

                0%,
                50%,
                to {
                    opacity: 1;
                }

                25%,
                75% {
                    opacity: 0;
                }
            }

            .smct-flash {
                -webkit-animation-name: smct-flash;
                animation-name: smct-flash;
            }
            table.dataTable thead tr {
                background-color: #929292;
                color:white;
            }
        </style>
        <style>
            .account-section .account-orderdetail .order-store-hours{
                padding-top:20px;
            }
            }
            .account-section .account-orderdetail .account-orderdetail-orderTotalDiscount-section{
                clear:both;margin-bottom:20px;font-family:'Oswald', Arial, Helvetica, sans-serif;font-weight:400;
            }
            .account-section .account-orderdetail .account-orderdetail-orderTotalDiscount-section .order-total__taxes{
                color:#0068bd;text-align:right;
            }
            .account-section .account-orderdetail .account-orderdetail-orderTotalDiscount-section .order-savings__info{
                color:#0068bd;
            }
            .account-section .account-orderdetail .order-detail-title{
                padding:0 35px;font-family:'Oswald', Arial, Helvetica, sans-serif;font-weight:700;font-size:22px;
            }
            .account-section .account-address-removal-popup .address{
                margin-top:20px;
            }
            .account-section .account-address-removal-popup .btn{
                margin-top:10px;
            }
            .account-section .savedcart_restore_confirm_modal .restore-current-cart-form{
                margin-top:40px;
            }
            .account-section .not-active{
                pointer-events:none;cursor:default;font-weight:normal !important;color:black !important;
            }
            .account-section .text-sport-2{
                color:gray;font-weight:normal;
            }
            .account-section .my-orders-container{
                margin:0 auto;max-width:600px;padding:0 10px;width:100%;
            }
            .account-section .box-order{
                align-items:center;border-spacing:10px;border:3px dashed #ccc;display:flex;justify-content:space-between;line-height:1em;font-size:20px;margin-bottom:40px;padding:40px;width:100%;
            }
            @media(max-width:750px){
                .account-section .box-order:first-child{
                    border-top:1px solid rgba(0, 0, 0, 0.2);
                }
            }
            @media(max-width:750px){
                .account-section .box-order{
                    border:0;
                    border-bottom:1px solid rgba(0, 0, 0, 0.2);
                    margin-bottom:0;
                    padding:40px 20px;
                }
            }
            .account-section .box-order:last-of-type{
                margin-bottom:0;
            }
            .account-section .box-order__left-side{
                text-align:center;width:57%;
            }
            @media(max-width:750px){
                .account-section .box-order__left-side{
                    width:40%;
                }
            }
            .account-section .box-order__right-side{
                width:40%;
            }
            @media(max-width:750px){
                .account-section .box-order__right-side{
                    width:60%;
                }
            }
            .account-section .box-order__subtitle{
                font-family:'Oswald', Arial, Helvetica, sans-serif;font-weight:500;font-size:calc(13px - -5 *((100vw - 300px) /(1600 - 300)));margin:0 0 20px;text-transform:uppercase;
            }
            @media(max-width:750px){
                .account-section .box-order__subtitle{
                    margin-bottom:10px;
                }
            }
            .account-section .box-order__value{
                display:block;font-family:'Oswald', Arial, Helvetica, sans-serif;font-weight:300;font-size:calc(13px - -5 *((100vw - 300px) /(1600 - 300)));margin-bottom:20px;
            }
            .account-section .box-order__value:last-child{
                margin-bottom:0;
            }
            @media(max-width:750px){
                .account-section .box-order__value{
                    margin-bottom:10px;
                }
            }
            .account-section .box-order .img-orders{
                margin:0 auto;max-width:145px;width:100%;
            }
            @media(max-width:480px){
                .account-section .box-order .img-orders{
                    max-width:55px;
                }
            }
            .account-section h4{
                font-weight:bold;
            }
            .account-section .img-orders{
                width:182px;
            }
            .account-section .blue-text{
                color:#0085C5;
            }
            .account-section .icon-info-order{
                font-size:23px;color:#0085C5;padding-left:5px;
            }
            @media(max-width:480px){
                .account-section .icon-info-order{
                    font-size:18px;
                }
            }
            #colorbox .update-email-confirmation{
                font-family:'Oswald', Arial, Helvetica, sans-serif;font-weight:600;color:#000000;font-size:24px;letter-spacing:2px;padding:80px 0 80px 0;
            }
            #colorbox .update-email-confirmation .message{
                text-transform:uppercase;padding-bottom:25px;
            }
            .tracking-container{
                max-width:850px;margin:0 auto;width:100%;
            }
            .tracking-container h1{
                text-align:center;font-family:'Oswald', Arial, Helvetica, sans-serif;font-weight:600;font-size:24px;text-transform:uppercase;padding-bottom:33px;
            }
            .tracking-container p{
                margin:0;
            }
            .tracking-container p a{
                font-family:'Oswald', Arial, Helvetica, sans-serif;font-weight:300;font-size:12px;
            }
            .tracking-container .tracking{
                display:block;border-top:1px solid;border-bottom:1px solid;padding-bottom:41px;
            }
            .tracking-container .tracking .tracking-head{
                text-align:center;font-family:'Oswald', Arial, Helvetica, sans-serif;font-weight:500;text-transform:uppercase;font-size:20px;padding:29px 0px;
            }
            .tracking-container .tracking .tracking-head h2{
                display:initial;font-family:'Oswald', Arial, Helvetica, sans-serif;font-weight:300;font-size:20px;
            }
            .tracking-container .tracking .tracking-body{
                display:flex;justify-content:center;width:100%;padding-bottom:42px;padding-top:30px;
            }
            .tracking-container .tracking .tracking-body .track{
                margin:0 5px;width:131px;display:flex;flex-flow:row wrap;justify-content:center;
            }
            .tracking-container .tracking .tracking-body .track .align-text{
                order:1;height:111px;text-align:center;display:flex;flex-direction:column;justify-content:center;
            }
            .tracking-container .tracking .tracking-body .track .align-text span{
                text-transform:uppercase;font-family:'Oswald', Arial, Helvetica, sans-serif;font-weight:300;font-size:16px;
            }
            .tracking-container .tracking .tracking-body .track .align-text h2{
                height:15px;margin-top:5px;color:#F68721;font-size:16px;
            }
            .tracking-container .tracking .tracking-body .track .align-text h4{
                height:10px;margin-top:5px;color:#F68721;font-size:12px;
            }
            .tracking-container .tracking .tracking-body .track .align-columm{
                order:2;width:100%;
            }
            .tracking-container .tracking .tracking-body .track .align-columm .columm2{
                background-color:#ddd;position:relative;height:14px;
            }
            .tracking-container .tracking .tracking-body .track .align-columm .columm2:before{
                width:0;height:0;border-top:7px solid transparent;border-left:8px solid #fff;border-bottom:7px solid transparent;content:"";position:absolute;left:0;
            }
            .tracking-container .tracking .tracking-body .track .align-columm .columm2:after{
                width:0;height:0;border-top:7px solid transparent;border-left:8px solid #ddd;border-bottom:7px solid transparent;content:"";position:absolute;right:-8px;
            }
            .tracking-container .tracking .tracking-body .track .align-columm .columm2 i{
                display:block;border-radius:50%;background:#fff;height:8px;width:8px;left:50%;top:50%;position:absolute;margin-top:-4px;margin-left:-4px;
            }
            .tracking-container .tracking .tracking-body .track .align-icomoon{
                order:3;padding-top:46px;position:relative;
            }
            .tracking-container .tracking .tracking-body .track .align-icomoon .circle2{
                width:65px;height:65px;border-radius:50%;background:#ddd;color:white;font-size:35px;display:flex;align-items:center;justify-content:center;margin:0 auto;
            }
            .tracking-container .tracking .tracking-body .track .align-icomoon .circle2:before{
                background:#ddd;width:2px;height:46px;display:block;position:absolute;top:0;left:50%;margin-left:-1px;content:"";
            }
            .tracking-container .tracking .tracking-body .track:nth-child(even) .align-text{
                order:3;display:flex;align-items:center;
            }
            .tracking-container .tracking .tracking-body .track:nth-child(even) .align-icomoon{
                order:1;padding-top:0;padding-bottom:46px;
            }
            .tracking-container .tracking .tracking-body .track:nth-child(even) .align-icomoon .circle2:before{
                bottom:0;top:auto;
            }
            .tracking-container .tracking .tracking-body .track--hide .align-text{
                color:#EFEFEF;
            }
            .tracking-container .tracking .tracking-body .track--hide .align-columm .columm2{
                background-color:#ddd;
            }
            .tracking-container .tracking .tracking-body .track--hide .align-columm .columm2:after{
                border-left-color:#ddd;
            }
            .tracking-container .tracking .tracking-body .track--hide .align-icomoon .circle2{
                background:#ddd;
            }
            .tracking-container .tracking .tracking-body .track--hide .align-icomoon .circle2:before{
                background:#ddd;
            }
            @media(max-width:750px){
                .tracking-container{
                    width:100%;
                }
                .tracking-container .tracking .tracking-body{
                    display:grid;
                    width:100%;
                }
                .tracking-container .tracking .tracking-body .track{
                    justify-content:normal;
                    width:100%;
                }
                .tracking-container .tracking .tracking-body .track .align-text{
                    display:flex;
                    align-items:center;
                    justify-content:center;
                    flex-flow:column wrap;
                    width:calc(50% - 6.5px);
                }
                .tracking-container .tracking .tracking-body .track .align-columm{
                    width:13px;
                }
                .tracking-container .tracking .tracking-body .track .align-columm .columm2{
                    height:100px;
                    width:13px;
                }
                .tracking-container .tracking .tracking-body .track .align-columm .columm2:before{
                    border-right:6.5px solid transparent;
                    border-left:6.5px solid transparent;
                    border-top:8px solid #fff;
                    position:absolute;
                    left:auto;
                    top:-1px;
                    content:'';
                }
                .tracking-container .tracking .tracking-body .track .align-columm .columm2:after{
                    border-right:6.5px solid transparent;
                    border-left:6.5px solid transparent;
                    border-top:8px solid #ddd;
                    border-bottom:0;
                    position:absolute;
                    left:auto;
                    content:'';
                    right:auto;
                    bottom:-8px;
                }
                .tracking-container .tracking .tracking-body .track .align-columm .columm2 i{
                    height:6px;
                    width:6px;
                    margin-left:-3px;
                    margin-top:2px;
                }
                .tracking-container .tracking .tracking-body .track .align-icomoon{
                    width:calc(50% - 6.5px);
                    padding-top:0;
                    padding-left:46px;
                    display:flex;
                    align-items:center;
                }
                .tracking-container .tracking .tracking-body .track .align-icomoon .circle2{
                    margin:0;
                }
                .tracking-container .tracking .tracking-body .track .align-icomoon .circle2:before{
                    width:46px;
                    height:2px;
                    top:50%;
                    left:0;
                    margin-top:-1px;
                }
                .tracking-container .tracking .tracking-body .track:nth-child(even) .align-text{
                    order:3;
                    display:flex;
                    align-items:center;
                    justify-items:center;
                }
                .tracking-container .tracking .tracking-body .track:nth-child(even) .align-icomoon{
                    order:1;
                    padding-bottom:0;
                    padding-right:46px;
                    padding-left:0;
                    justify-content:flex-end;
                }
                .tracking-container .tracking .tracking-body .track:nth-child(even) .align-icomoon .circle2:before{
                    left:auto;
                    right:0;
                    bottom:auto;
                    top:auto;
                }
                .tracking-container .tracking .tracking-body .track--hide .align-columm .columm2:after{
                    border-top-color:#ddd;
                }
                .tracking-container .trk-body{
                    padding-left:30px;
                }
                .tracking-container .trk-footer{
                    padding-left:30px;
                }
            }
            .change-password-section .form-group{
                margin-bottom:15px;
            }
            .change-password-section .form-group .control-label{
                font-family:'Oswald', Arial, Helvetica, sans-serif;font-weight:400;display:block;text-align:center;text-transform:uppercase;
            }
            .change-password-section .password-info{
                text-align:right;font-size:10px;color:#0068bd;display:block;margin-top:-5px;font-family:'Oswald', Arial, Helvetica, sans-serif;font-weight:300;
            }
            .quick-view-popup .product-image{
                float:left;width:33%;
            }
            .quick-view-popup .product-image a{
                display:block;
            }
            .quick-view-popup .product-image img{
                width:100%;height:auto;
            }
            .quick-view-popup .product-details{
                margin-left:33%;padding-left:20px;
            }
            .quick-view-popup .product-details .name{
                font-size:inherit;font-weight:bold;
            }
            .quick-view-popup .product-details .rating:after{
                clear:none;
            }
            .quick-view-popup .product-details .price{
                font-size:inherit;font-weight:bold;
            }
            .quick-view-popup .addtocart-component{
                clear:both;padding-top:20px;
            }
            .quick-view-popup .addtocart-component .qty-selector .input-group-btn .js-qty-selector-minus .glyphicon-minus{
                position:absolute;margin-left:-9px;
            }
            .quick-view-popup .addtocart-component .qty-selector .input-group-btn .js-qty-selector-plus .glyphicon-plus{
                position:absolute;margin-left:-8px;
            }
            .quick-view-popup .addtocart-component .qty-selector .input-group-btn .js-qty-selector-plus .glyphicon-plus:before{
                margin-top:0px;
            }
            .quick-view-popup .addtocart-component .stock-status{
                padding:20px 0px;
            }
            .quick-view-popup .addtocart-component .btn{
                line-height:8px;
            }
            table{
                width:100%;
            }
            table th{
                font-size:13px;font-weight:400;text-transform:uppercase;background-color:#F68721;color:#fff;padding:8px 10px;vertical-align:central;
            }
            table th:last-child{
                text-align:center;
            }
            table tr.entry-group-header a{
                color:lightgrey;
            }
            table tr.entry-group-header a:hover{
                color:black;
            }
            table tr.entry-group-header .error{
                background-color:PaleVioletRed;
            }
            table .entry-group-error-message{
                text-transform:none;
            }
            table div.left-align{
                text-align:left;
            }
            table td{
                padding:20px 10px;vertical-align:top;
            }
            .responsive-table th:first-child{
                padding-left:30px;
            }
            @media(max-width:1024px){
                .responsive-table th:first-child{
                    padding-left:20px;
                }
            }
            .responsive-table th:last-child{
                text-align:right;padding-right:30px;
            }
            @media(max-width:1024px){
                .responsive-table th:last-child{
                    padding-right:20px;
                }
            }
            @media(min-width:750px){
                .responsive-table td:nth-child(2){
                    padding-left:20px;
                }
            }
            @media(min-width:1024px){
                .responsive-table td:nth-child(2){
                    padding-left:30px;
                }
            }
            .responsive-table td:last-child{
                text-align:right;padding-right:30px;
            }
            @media(max-width:750px){
                .responsive-table td:last-child{
                    padding-right:20px;
                }
            }
            .responsive-table-item{
                padding:10px;
            }
            @media(max-width:750px){
                .responsive-table-item{
                    padding:10px 20px;
                }
            }
            .responsive-table-item:nth-child(even){
                background-color:#f4f4f4;
            }
            .responsive-table-item a.responsive-table-link{
                color:#00689d;
            }
            .responsive-table-item a:hover,

        </style>

    </head>

    <body style="background-color: white;color: black;">
        <script src="<?php echo $url_libs_imagina; ?>js/jquery.js"></script>
        <script src="<?php echo $url_libs_imagina; ?>js/bootstrap.min.js"></script>
        <script src="<?php echo $url_libs_imagina; ?>js/pace.min.js"></script>
        <script src="<?php echo $url_libs_imagina; ?>js/wow.min.js"></script>
        <script src="<?php echo $url_libs_imagina; ?>js/jquery.nicescroll.js" type="text/javascript"></script>


        <script src="<?php echo $url_libs_imagina; ?>js/jquery.app.js"></script>

        <script src="<?php echo $url_libs_imagina; ?>assets/notifications/notify.min.js"></script>
        <script src="<?php echo $url_libs_imagina; ?>assets/notifications/notify-metro.js"></script>
        <script src="<?php echo $url_libs_imagina; ?>assets/notifications/notifications.js"></script>



        <!--<script src="<?php echo Configuraciones::url_base(); ?>vistas/VistaConfiguraciones.js"></script>-->
        <script src="<?php echo Configuraciones::url_base(); ?>vistas/com/util/Global.js"></script>
        <script src="<?php echo Configuraciones::url_base(); ?>vistas/com/util/Enums.js"></script>
        <script src="<?php echo Configuraciones::url_base(); ?>vistas/com/util/Include.js"></script>
        <script src="<?php echo Configuraciones::url_base(); ?>vistas/com/util/EventManager.js"></script>
        <!--<script src="<?php echo Configuraciones::url_base(); ?>vistas/com/util/Utils.js?<?php echo date('Y.m.d.H.i.s') ?>"></script>-->
        <script src="<?php echo Configuraciones::url_base(); ?>vistas/com/util/String.js"></script>
        <script src="<?php echo Configuraciones::url_base(); ?>vistas/com/util/Ajaxp.js?<?php echo date('Y.m.d.H.i.s') ?>"></script>
        <script src="<?php echo Configuraciones::url_base(); ?>vistas/com/util/Mensajes.js"></script>
        <script src="<?php echo Configuraciones::url_base(); ?>vistas/com/movimiento/imprimir.js?<?php echo date('Y.m.d.H.i.s') ?>"></script>        

        <!--librerias adicionales--> 
        <!--<script src="vistas/libs/imagina/assets/select2/select2.min.js"></script>-->
        <script src="<?php echo $url_libs_imagina; ?>assets/datatables/jquery.dataTables.min.js"></script>
        <script src="<?php echo $url_libs_imagina; ?>assets/datatables/dataTables.bootstrap.js"></script>          
        <!--<script src="<?php echo $url_libs_imagina; ?>assets/datatables/columnsFixed.js"></script>-->  
        <script src="<?php echo $url_libs_imagina; ?>assets/select2/select2.min.js"></script>
        <script src="<?php echo $url_libs_imagina; ?>assets/modal-effect/js/classie.js"></script>
        <script src="<?php echo $url_libs_imagina; ?>assets/modal-effect/js/modalEffects.js"></script>
        <script src="<?php echo $url_libs_imagina; ?>assets/sweet-alert/sweet-alert.min.js" type="text/javascript"></script>
        <script src="<?php echo $url_libs_imagina; ?>assets/timepicker/bootstrap-datepicker.js"></script>
        <script src="<?php echo $url_libs_imagina; ?>assets/timepicker/locales/bootstrap-datepicker.es.js"></script>
        <script src="<?php echo $url_libs_imagina; ?>assets/sparkline-chart/jquery.sparkline.min.js"></script>
        <script src="<?php echo $url_libs_imagina; ?>assets/nestable/jquery.nestable.js"></script>
        <script src="vistas/libs/print/jQuery.print.js" type="text/javascript"></script>
        <script src="<?php echo $url_libs_imagina; ?>assets/datatables/buttons.print.min.js"></script>
        <script src="<?php echo $url_libs_imagina; ?>assets/datatables/dataTables.buttons.min.js"></script>
        <script src="<?php echo $url_libs_imagina; ?>assets/datatables/buttons.colVis.min.js"></script>
        <script src="<?php echo $url_libs_imagina; ?>assets/bootstrap-inputmask/bootstrap-inputmask.min.js"></script> 

        <div id="trackingBody" class="yCmsContentSlot accountPageTopContent" hidden="">
            <div class="tracking-container">
                <h1 id="idTituloPedido" style="color: #F68721;">Sigue tu pedido N° </h1>
                <div id="cabeceraPedido" ></div> 

                <div class="tracking" style="border-top-color: #F68721;border-bottom-color: #F68721;">
                    <div id="divTrackBody" class="tracking-body"></div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div id="divDataTable" class="table-responsive">
                                <table id='dataTableDetalle' class="table table-striped table-bordered table-hover" style='width:100%'>
                                    <thead>
                                        <tr style=" background-color: #F68721;color:white;">
                                            <th style="" width='20%'>FLUJO</th>
                                            <th class='text-center' width='20%'>Fecha</th>
                                            <th class='text-center' width='20%'>Paquete</th> 
                                            <th class='text-center' width='20%'>Tipo documento</th>
                                            <th class='text-center' width='20%'>S/N</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div> 
                </div> 
            </div> 
        </div>





        <script>
            var url;
            function cerrar() {
                window.close();
            }

            $(document).ready(function () {
                esperar();
                obtenerParametrosGenerales();
            });

            function inicializarToogles() {
                $('.toggle').toggles();
                $('.toggle').toggles({
                    drag: true, // allow dragging the toggle between positions
                    click: true, // allow clicking on the toggle
                    text: {
                        on: 'SI', // text for the ON position
                        off: 'NO' // and off
                    },
                    on: true, // is the toggle ON on init
                    animate: 250, // animation time (ms)
                    easing: 'swing', // animation transition easing function
                    checkbox: null, // the checkbox to toggle (for use in forms)
                    clicker: null, // element that can be clicked on to toggle. removes binding from the toggle itself (use nesting)
                    width: 250, // width used if not set in css
                    height: 25, // height if not set in css
                    type: 'compact' // if this is set to 'select' then the select style toggle will be used
                });

            }

            function agregarEventoEstadoToggle(tggID, indice) {

                $('#' + tggID).toggles().on('toggle', function (e, active) {
                    if (active) {
                        $('#i' + tggID).val("1");
                        cambiarEstadoPregunta(indice, 1);
                    } else {
                        $('#i' + tggID).val("0");
                        cambiarEstadoPregunta(indice, 0);
                    }
                });
            }

            function cambiarEstadoPregunta(indice, flag) {

                if (flag == "0") {
                    document.getElementById("divEstrellas" + indice).style.display = "none";
                } else {
                    document.getElementById("divEstrellas" + indice).style.display = "block";
                }

            }

            function cambiarURL() {
                history.pushState(null, "", URL_BASE);
            }

            function isEmpty(value) {
                if ($.type(value) === 'undefined')
                    return true;
                if ($.type(value) === 'null')
                    return true;
                if ($.type(value) === 'string' && value.length <= 0)
                    return true;
                if ($.type(value) === 'array' && value.length === 0)
                    return true;
                if ($.type(value) === 'number' && isNaN(parseInt(value)))
                    return true;

                return false;
            }

            function number_format(amount, decimals) {
                amount += ''; // por si pasan un numero en vez de un string
                amount = parseFloat(amount.replace(/[^0-9\.]/g, '')); // elimino cualquier cosa que no sea numero o punto

                decimals = decimals || 0; // por si la variable no fue fue pasada

                // si no es un numero o es igual a cero retorno el mismo cero
                if (isNaN(amount) || amount === 0)
                    return parseFloat(0).toFixed(decimals);

                // si es mayor o menor que cero retorno el valor formateado como numero
                amount = '' + amount.toFixed(decimals);

                var amount_parts = amount.split('.'),
                        regexp = /(\d+)(\d{3})/;

                while (regexp.test(amount_parts[0]))
                    amount_parts[0] = amount_parts[0].replace(regexp, '$1' + ',' + '$2');

                return amount_parts.join('.');
            }


            function esperar() {
                swal({
                    title: "Procesando",
                    text: "Obteniendo la información...",
                    showConfirmButton: false
                });
            }

            function aceptar() {
                swal({
                    title: "Exito",
                    type: "success",
                    text: "Datos obtenidos",
                    showConfirmButton: true
                });
            }

            function mensajeWarning(mensaje) {
                swal({
                    title: "Validación",
                    type: "warning",
                    text: mensaje,
                    showConfirmButton: true
                });
            }

            parserFechaHora = function (s) {
                var ss = (s.split('-'));
                var y = parseInt(ss[0], 10);
                var m = parseInt(ss[1], 10);
                var d = parseInt(ss[2].substr(0, 2), 10);
                var h = ss[2].substr(2, 9);
                return (d < 10 ? ('0' + d) : d) + '/' + (m < 10 ? ('0' + m) : m) + '/' + y + h;
            }

            function parserFecha(s) {
                var ss = (s.split('-'));
                var y = parseInt(ss[0], 10);
                var m = parseInt(ss[1], 10);
                var d = parseInt(ss[2].substr(0, 2), 10);
                return (d < 10 ? ('0' + d) : d) + '/' + (m < 10 ? ('0' + m) : m) + '/' + y;
            }

            function parserHora(s) {
                var ss = (s.split('-'));
                var h = ss[2].substr(2, 6) + 'h';
                return h;
            }

            function datosCuestionario(idCuestionario, comentario, respuestas) {
                this.idCuestionario = idCuestionario;
                this.comentario = comentario;
                this.respuestas = respuestas;
            }

            var dataConfiguracionInicial;

            function obtenerParametrosGenerales() {
                $.post('vistas/com/mail/servicio_confirmacion_debloquear_pedido.php', {
                    accion: 'obtenerDatos',
                    lista: <?php echo "'" . $parametros . "'"; ?>
                }, function (respuesta) {
                    dataConfiguracionInicial = JSON.parse(respuesta);
                    if (!isEmpty(dataConfiguracionInicial)) {
                        if (dataConfiguracionInicial[0]['tipo_accion'] == 'AP') {
                            swal({
                                title: "¿Desea aprobar el desbloqueo de este pedido ?",
                                type: "info",
                                showCancelButton: true,
                                cancelButtonText: "Cancelar",
                                confirmButtonColor: "#5cb85c",
                                confirmButtonText: "Aprobar",
                                closeOnConfirm: false
                            }, function (isConfirm) {
                                if (!isConfirm) {
                                    cerrar();
                                } else {
                                    esperar();
                                    $.post('vistas/com/mail/servicio_confirmacion_debloquear_pedido.php', {
                                        accion: 'aprobar',
                                        documentoId: dataConfiguracionInicial[0]['documento_id'],
                                        usuarioId: dataConfiguracionInicial[0]['usuario_id']
                                    }, function (respuestaAp) {
                                        let respuestaApJson = JSON.parse(respuestaAp);
                                        let tipoMensaje = 'warning';
                                        let mensaje = respuestaApJson[0]['vout_mensaje'];
                                        if (respuestaApJson[0]['vout_exito'] == 1) {
                                            tipoMensaje = 'success';
                                        } else {
                                            tipoMensaje = 'warning';
                                        }

                                        swal({
                                            title: "Respuesta",
                                            type: tipoMensaje,
                                            text: mensaje,
                                            confirmButtonColor: "#5cb85c",
                                            confirmButtonText: "Aceptar"
                                        }, function (isConfirm) {
                                            if (isConfirm) {
                                                cerrar();
                                            }
                                        }
                                        );
                                    }).error(
                                            function () {
                                                swal({
                                                    title: "Aviso",
                                                    type: "warning",
                                                    text: "Error al ejecutar la petición",
                                                    confirmButtonColor: "#5cb85c",
                                                    confirmButtonText: "Aceptar"
                                                }, function (isConfirm) {
                                                    if (isConfirm) {
                                                        cerrar();
                                                    }
                                                });
                                            }
                                    );

                                }
                            });
                        } else if (dataConfiguracionInicial[0]['tipo_accion'] == 'RE') {
                            swal({
                                title: "¿Seguro que desea rechazar el desbloqueo del pedido?",
                                type: "warning",
                                showCancelButton: true,
                                cancelButtonText: "Cancelar",
                                confirmButtonColor: "#DD6B55",
                                confirmButtonText: "Rechazar",
                                closeOnConfirm: false
                            },
                                    function (inputValue) {
                                        if (inputValue === false) {
                                            cerrar();
                                        } else {
                                            cerrar();
                                        }
                                    });
                        } else {
                            aceptar();
                        }
                    } else {
                        mensajeWarning('No se obtuvo la información necesaria del pedido.')
                    }
                });
            }

            /*
             function workflow() {
             $.post('vistas/com/mail/servicio_verificar_conformidad.php', {lista: <?php echo "'" . $_POST["parametro"] . "'"; ?>}, function (respuesta) {
             
             if (accion === 'r') {
             swal({
             title: "¿Seguro que desea rechazar esta conformidad ?",
             type: "input",
             showCancelButton: true,
             cancelButtonText: "Cancelar",
             confirmButtonColor: "#DD6B55",
             confirmButtonText: "Rechazar",
             inputPlaceholder: "Motivo",
             closeOnConfirm: false
             },
             function (inputValue) {
             if (inputValue === false) {
             cerrar();
             } else {
             if (inputValue === "" || inputValue.trim() == "") {
             swal.showInputError("Debe ingresar un motivo");
             return false;
             } else {
             esperar();
             var datos = new datosConformidad(usuario, cod_confirmacion, compania, inputValue, workflow_id, accion);
             listaWF.push(datos);
             var listaJSON = JSON.stringify(listaWF);
             $.post('../sgr/vistas/com/mail/servicio_json_conformidad.php', {lista: listaJSON}, function (respuesta) {
             listaRes = JSON.parse(respuesta);
             mensaje = listaRes[0].mensaje;
             tipo_msg = listaRes[0].tipo_msg;
             
             swal({
             title: "Respuesta",
             type: tipo_msg,
             text: mensaje,
             confirmButtonColor: "#5cb85c",
             confirmButtonText: "Aceptar"
             }, function (isConfirm) {
             if (isConfirm) {
             cerrar();
             }
             }
             );
             
             }).error(
             function () {
             swal({
             title: "Aviso",
             type: "warning",
             text: "Error al ejecutar la petición",
             confirmButtonColor: "#5cb85c",
             confirmButtonText: "Aceptar"
             }, function (isConfirm) {
             if (isConfirm) {
             cerrar();
             }
             });
             }
             );
             
             
             }
             }
             });
             } else if (accion === 'a') {
             swal({
             title: "¿Desea aprobar esta conformidad ?",
             type: "info",
             showCancelButton: true,
             cancelButtonText: "Cancelar",
             confirmButtonColor: "#5cb85c",
             confirmButtonText: "Aprobar",
             closeOnConfirm: false
             }, function (isConfirm) {
             if (!isConfirm) {
             cerrar();
             } else {
             esperar();
             var datos = new datosConformidad(usuario, cod_confirmacion, compania, null, workflow_id, accion);
             listaWF.push(datos);
             var listaJSON = JSON.stringify(listaWF);
             $.post('vistas/com/mail/servicio_json_conformidad.php', {lista: listaJSON}, function (respuesta) {
             listaRes = JSON.parse(respuesta);
             tipo_msg = listaRes[0].tipo_msg;
             mensaje = listaRes[0].mensaje; 
             
             swal({
             title: "Respuesta",
             type: tipo_msg,
             text: mensaje,
             confirmButtonColor: "#5cb85c",
             confirmButtonText: "Aceptar"
             }, function (isConfirm) {
             if (isConfirm) {
             if (tipo_msg == "success") {
             var miEncuesta = listaRes[0]['encuesta'];
             if (!isEmpty(miEncuesta)) {
             mostrarPreguntasCuestionario(listaRes[0]['encuesta'], listaRes[0]['pregunta']);
             } else {
             cerrar();
             }
             } else {
             cerrar();
             }
             }
             }
             );
             
             }).error(
             function () {
             swal({
             title: "Aviso",
             type: "warning",
             text: "Error al ejecutar la petición",
             confirmButtonColor: "#5cb85c",
             confirmButtonText: "Aceptar"
             }, function (isConfirm) {
             if (isConfirm) {
             cerrar();
             }
             });
             }
             );
             
             }
             });
             }
             
             }
             }).error(
             function () {
             alert('Error al ejecutar la petición 1');
             }
             );
             
             */

        </script> 
        <script src="vistas/libs/imagina/assets/toggles/toggles.min.js" type="text/javascript"></script>
        <script src="vistas/libs/imagina/assets/form-wizard/bootstrap-validator.min.js" type="text/javascript"></script>
        <script src="vistas/libs/imagina/assets/form-wizard/jquery.steps.min.js" type="text/javascript"></script>
        <script src="vistas/libs/imagina/assets/jquery.validate/jquery.validate.min.js" type="text/javascript"></script>
        <script src="vistas/libs/imagina/assets/select2/select2.min.js" type="text/javascript"></script>
        <script src="vistas/libs/imagina/assets/form-wizard/wizard-init.js" type="text/javascript"></script> 
    </body>

</html>