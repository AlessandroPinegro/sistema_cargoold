<?php
include_once __DIR__.'/TemplateBase.php';

/**
 * @author Christopher Heredia<cheredia@imaginatecperu.com>
 * @todo Clase que se debe agregar en todas las páginas e inserta el contenido del head del HTML
 */

class TemplateHeader extends TemplateBase {
    private $titulo;
    
    /**
     * 
     * @return TemplateHeader
     */
    public static function create() {
        return parent::create();
    }
    
    public function inicia($titulo = "Imagina technologies"){
        $this->titulo = $titulo;
        echo '        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-Equiv="Cache-Control" Content="no-cache">
        <meta http-Equiv="Pragma" Content="no-cache">
        <meta http-Equiv="Expires" Content="0">
        <link rel="shortcut icon" href="img/favicon_1.ico">
        <title>'.$this->titulo.'</title>

        <!-- Bootstrap core CSS -->
        <link href="'.$this->getUrlLibsImagina().'css/bootstrap.min.css" rel="stylesheet">
        <link href="'.$this->getUrlLibsImagina().'css/bootstrap-reset.css" rel="stylesheet">

        <!--Animation css-->
        <link href="'.$this->getUrlLibsImagina().'css/animate.css" rel="stylesheet">

        <!--Icon-fonts css-->
        <link href="'.$this->getUrlLibsImagina().'assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
        <link href="'.$this->getUrlLibsImagina().'assets/ionicon/css/ionicons.min.css" rel="stylesheet" />

        <!-- sweet alerts -->
        <link href="'.$this->getUrlLibsImagina().'assets/sweet-alert/sweet-alert.min.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="'.$this->getUrlLibsImagina().'css/style.css" rel="stylesheet">
        <link href="'.$this->getUrlLibsImagina().'css/helper.css" rel="stylesheet">
        <link href="'.$this->getUrlLibsImagina().'css/style-responsive.css" rel="stylesheet" />


        <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
        <!--[if lt IE 9]>
          <script src="'.$this->getUrlLibsImagina().'js/html5shiv.js"></script>
          <script src="'.$this->getUrlLibsImagina().'js/respond.min.js"></script>
        <![endif]-->';
    }
    
    
}
