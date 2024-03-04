<?php
include_once __DIR__.'/TemplateIncludes.php';
include_once __DIR__.'/../../../util/ObjectUtil.php';
/**
 * Description of Template
 *
 * @author CHL007
 */
class TemplateBase {
    static private $_instance = array();  // almacena la instancia de la clase hija
    
    public function __construct() {
       
    }
    
    /**
     * 
     * Crea la instancia. 
     * @return get_called_class()
     * 
     * @author Christopher Heredia Lozada <cheredia@imaginatecperu.com>
     */
    public static function create()
    {
        $class_child = get_called_class();
        
        if(ObjectUtil::isEmpty(self::$_instance)){
            //self::$_instance["Prod"] = 1;
            self::$_instance[$class_child] = new $class_child();
        }
        elseif(!array_key_exists($class_child, self::$_instance)){
            self::$_instance[$class_child] = new $class_child();
        }
        return self::$_instance[$class_child];
    }
    
    public function getUrlBase(){
        return Configuraciones::url_base();
    }
    
    public function getUrlLibsImagina(){
        return $this->getUrlBase()."/vistas/libs/imagina/";
    }
    
    public function addCss($styleDir){
        $this->echoCss($this->getUrlBase(), $styleDir);
    }
    public function addJS($jsDir){
        $this->echoJS($this->getUrlBase(), $jsDir);
    }
    public function addCssImagina($styleDir){
        $this->echoCss($this->getUrlLibsImagina(), $styleDir);
    }
    public function addJSImagina($jsDir){
        $this->echoJS($this->getUrlLibsImagina(), $jsDir);
    }
    
    private function echoCss($url, $styleDir){
        echo '<link href="'.$url.$styleDir.'" rel="stylesheet">';
    }
    public function echoJS($url, $jsDir){
        echo '<script src="'.$url.$jsDir.'"></script>';
    }
}

