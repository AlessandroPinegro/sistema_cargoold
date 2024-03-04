<?php
/**
 * Extensiones permitidas pára la subida de archivos
 * con sus respectivo tamaños maximos.
 */
 
define('KB', 1024);
define('MB', 1048576);
define('GB', 1073741824);
define('TB', 1099511627776);

Class Extensiones {
	public $AllowedExt;
	
	function __construct(){
		$this->AllowedExt = array(
		"xls" => 3*MB,
		"xlsx" => 3*MB,
		"doc" => 2*MB,
		"docx" => 2*MB,
		"pdf" => 5*MB,
		"jpg" => 5*MB,
		"png" => 5*MB);
	}
	 
	public function is_valid_ext($extension) {
		if(array_key_exists($extension,$this->AllowedExt)) {
			return true;
		}
		else {
			return false;
		}
			
	}

	public function get_max_Size($extension) {
		$maxSize = 0;
		if(array_key_exists($extension,$this->AllowedExt)) {
			$maxSize = $this->AllowedExt[$extension];
		}
		return $maxSize;
	}
}

?>