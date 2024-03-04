<?php
include_once __DIR__.'/TemplateBase.php';

/**
 * @author Christopher Heredia<cheredia@imaginatecperu.com>
 * @todo Clase que se debe agregar en todas las páginas e inserta el contenido del head del HTML
 */

class TemplateBody extends TemplateBase {
    
    /**
     * 
     * @return TemplateBody
     */
    public static function create() {
        return parent::create();
    }
    
    public function addJSBase(){
        echo '<!-- js placed at the end of the document so the pages load faster -->
        <script src="'.$this->getUrlLibsImagina().'js/jquery.js"></script>
        <script src="'.$this->getUrlLibsImagina().'js/bootstrap.min.js"></script>
        <script src="'.$this->getUrlLibsImagina().'js/pace.min.js"></script>
        <script src="'.$this->getUrlLibsImagina().'js/wow.min.js"></script>
        <script src="'.$this->getUrlLibsImagina().'js/jquery.nicescroll.js" type="text/javascript"></script>


        <script src="'.$this->getUrlLibsImagina().'js/jquery.app.js"></script>
    
        <script src="'.$this->getUrlLibsImagina().'assets/notifications/notify.min.js"></script>
        <script src="'.$this->getUrlLibsImagina().'assets/notifications/notify-metro.js"></script>
        <script src="'.$this->getUrlLibsImagina().'assets/notifications/notifications.js"></script>
        
        <script type="text/javascript">var URL_BASE = "'.$this->getUrlBase().'";</script>
        
        <script src="'.$this->getUrlBase().'vistas/VistaConfiguraciones.js"></script>
        <script src="'.$this->getUrlBase().'vistas/com/util/Global.js"></script>
        <script src="'.$this->getUrlBase().'vistas/com/util/Enums.js"></script>
        <script src="'.$this->getUrlBase().'vistas/com/util/Include.js"></script>
        <script src="'.$this->getUrlBase().'vistas/com/util/EventManager.js"></script>
        <script src="'.$this->getUrlBase().'vistas/com/util/Utils.js"></script>
        <script src="'.$this->getUrlBase().'vistas/com/util/String.js"></script>
        <script src="'.$this->getUrlBase().'vistas/com/util/validatorResponse/ValidatorResponse.js"></script>
        <script src="'.$this->getUrlBase().'vistas/com/util/Ajaxp.js"></script>
        <script src="'.$this->getUrlBase().'vistas/com/util/Mensajes.js"></script>
        
        <script src="'.$this->getUrlBase().'vistas/com/ComponentCodes.js"></script>
        <script src="'.$this->getUrlBase().'vistas/index.js"></script>';
    }
    
    public function addBrand(){
        echo '<div class="logo">
                <a href="index.php" class="logo-expanded">
                    <img src="vistas/images/logo.png" alt="logo">
                    <span class="nav-label">NETAFIM</span>
                </a>
            </div>';
    }
    
    public function addMenu(){
        echo '<nav class="navigation">
                <ul class="list-unstyled">
                    <li class="has-submenu"><a href="../netafimlogin/menu.php"><i class="ion-home"></i><span class="nav-label">Menú principal</span></a>
                        
                    </li>
                    <li class="has-submenu active"><a href="#"><i class="ion-compose"></i> <span class="nav-label">Opciones</span></a>
                        <ul class="list-unstyled" id="menu">
                            
                        </ul>
                    </li>
                    
                </ul>
            </nav>';
    }
}
