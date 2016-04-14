<?php
namespace Arii\DocBundle\Service;

class AriiDoc {
    
    public function __construct() {  }
    
    public function DirInfo($dir) {
        $p = strpos($dir,"]");
        $type = substr($dir,1,$p-1);
        $name = substr($dir,$p+2);
        return array($type,$name);
    }
    
}
?>
